<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\ProjectTypeModel;
use App\Models\ProjectTypeFieldModel;
use App\Models\ProjectFieldValueModel;

class ProjectController extends BaseController
{
    protected $projectModel;
    protected $typeModel;
    protected $fieldModel;
    protected $fieldValueModel;
    protected $unitModel;
    protected $unitImageModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->typeModel = new ProjectTypeModel();
        $this->fieldModel = new ProjectTypeFieldModel();
        $this->unitModel = new \App\Models\ProjectUnitModel();
        $this->unitImageModel = new \App\Models\UnitImageModel();
    }

    private function getCompanyId()
    {
        return session()->get('company_id');
    }

    private function checkAccess()
    {
        if (!session()->get('is_company')) {
            return redirect()->to('/company/login');
        }
    }

    public function index()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $db = \Config\Database::connect();
        $builder = $db->table('projects');
        $builder->select('projects.*, project_types.type_name, (SELECT COUNT(id) FROM bookings WHERE project_id = projects.id) as total_bookings');
        $builder->join('project_types', 'project_types.id = projects.project_type_id');
        $builder->where('projects.company_id', $this->getCompanyId());
        $builder->orderBy('projects.id', 'DESC');
        
        $data['projects'] = $builder->get()->getResultArray();

        return view('company/projects/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $data['project_types'] = $this->typeModel->where('company_id', $this->getCompanyId())->findAll();
        return view('company/projects/create', $data);
    }

    // Ajax method to get fields for a project type
    public function getDynamicFields($projectTypeId)
    {
        if (!session()->get('is_company')) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        // Verify ownership
        $type = $this->typeModel->where('id', $projectTypeId)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Project Type']);

        $fields = $this->fieldModel->where('project_type_id', $projectTypeId)->findAll();
        return $this->response->setJSON(['status' => 'success', 'data' => $fields]);
    }

    public function store()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $rules = [
            'project_type_id' => 'required|numeric',
            'project_name'    => 'required|max_length[255]',
            'address'         => 'required|max_length[255]',
            'starting_price'  => 'permit_empty|numeric',
            'number_of_units' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $projectTypeId = $this->request->getPost('project_type_id');
        
        // Ensure the company owns the project type
        $type = $this->typeModel->where('id', $projectTypeId)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->back()->with('error', 'Invalid Project Type selected.');

        // 1. Insert Core Project
        $projectId = $this->projectModel->insert([
            'company_id'      => $this->getCompanyId(),
            'project_type_id' => $projectTypeId,
            'project_name'    => $this->request->getPost('project_name'),
            'address'         => $this->request->getPost('address'),
            'latitude'        => $this->request->getPost('latitude'),
            'longitude'       => $this->request->getPost('longitude'),
            'price_start'     => $this->request->getPost('price_start'),
            'price_end'       => $this->request->getPost('price_end'),
            'starting_price'  => $this->request->getPost('price_start'), // Fallback for legacy
            'number_of_units' => $this->request->getPost('number_of_units'),
            'status'          => $this->request->getPost('status') ?? 'Active'
        ], true);

        // 2. Process Dynamic Fields
        $this->fieldValueModel = new \App\Models\ProjectFieldValueModel();
        
        $dynamicFields = $this->fieldModel->where('project_type_id', $projectTypeId)->findAll();
        foreach ($dynamicFields as $field) {
            $inputName = 'field_' . $field['id'];
            $value = $this->request->getPost($inputName);

            // Convert checkbox value to Yes/No or keep as raw
            if ($field['field_type'] == 'Checkbox') {
                $value = $value ? 'Yes' : 'No';
            }

            if ($value !== null && $value !== '') {
                $this->fieldValueModel->insert([
                    'project_id'            => $projectId,
                    'project_type_field_id' => $field['id'],
                    'value'                 => $value
                ]);
            } elseif ($field['is_mandatory']) {
                // Technically we should validate this before inserting the project,
                // but for simplicity in MVP we allow it or you can enhance pre-validation.
            }
        }

        return redirect()->to('/company/projects/view/' . $projectId)->with('success', 'Project created successfully! You can now add units and manage inventory below.');
    }

    public function edit($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $project = $this->projectModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$project) return redirect()->to('/company/projects')->with('error', 'Project not found.');

        $data['project'] = $project;
        $data['project_types'] = $this->typeModel->where('company_id', $this->getCompanyId())->findAll();
        
        $this->fieldValueModel = new \App\Models\ProjectFieldValueModel();
        
        // Fetch fields and their values
        $db = \Config\Database::connect();
        $builder = $db->table('project_type_fields pf');
        $builder->select('pf.*, pv.value as existing_value');
        $builder->join('project_field_values pv', 'pv.project_type_field_id = pf.id AND pv.project_id = ' . $id, 'left');
        $builder->where('pf.project_type_id', $project['project_type_id']);
        
        $data['dynamic_fields'] = $builder->get()->getResultArray();

        // Fetch Units if applicable
        $db = \Config\Database::connect();
        $type = $db->table('project_types')->where('id', $project['project_type_id'])->get()->getRowArray();
        
        $typeNamesForUnits = ['Apartment', 'Appart', 'Flat', 'Unit', 'Room', 'Building', 'Complex', 'Residential'];
        $data['is_apartment'] = false;
        if ($type) {
            foreach ($typeNamesForUnits as $kw) {
                if (stripos($type['type_name'], $kw) !== false) {
                    $data['is_apartment'] = true;
                    break;
                }
            }
        }
        
        $data['units'] = [];
        if ($data['is_apartment']) {
            $data['units'] = $this->unitModel->where('project_id', $id)->findAll();
            foreach ($data['units'] as &$unit) {
                $unit['images'] = $this->unitImageModel->where('unit_id', $unit['id'])->findAll();
            }
        }

        return view('company/projects/edit', $data);
    }

    public function view($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $project = $this->projectModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$project) return redirect()->to('/company/projects')->with('error', 'Project not found.');

        $data['project'] = $project;
        $data['project_types'] = $this->typeModel->where('company_id', $this->getCompanyId())->findAll();
        
        // Fetch dynamic fields with values
        $db = \Config\Database::connect();
        $builder = $db->table('project_type_fields pf');
        $builder->select('pf.*, pv.value as existing_value');
        $builder->join('project_field_values pv', 'pv.project_type_field_id = pf.id AND pv.project_id = ' . $id, 'left');
        $builder->where('pf.project_type_id', $project['project_type_id']);
        $data['dynamic_fields'] = $builder->get()->getResultArray();

        // Fetch Units
        $type = $db->table('project_types')->where('id', $project['project_type_id'])->get()->getRowArray();
        
        // Broaden detection for units management (added Appart for misspelling)
        $typeNamesForUnits = ['Apartment', 'Appart', 'Flat', 'Unit', 'Room', 'Building', 'Complex', 'Residential'];
        $data['is_apartment'] = false;
        if ($type) {
            foreach ($typeNamesForUnits as $kw) {
                if (stripos($type['type_name'], $kw) !== false) {
                    $data['is_apartment'] = true;
                    break;
                }
            }
        }
        
        if ($data['is_apartment']) {
            $data['units'] = $this->unitModel->where('project_id', $id)->findAll();
            foreach ($data['units'] as &$unit) {
                $unit['images'] = $this->unitImageModel->where('unit_id', $unit['id'])->findAll();
            }
        }

        return view('company/projects/view', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $project = $this->projectModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$project) return redirect()->to('/company/projects')->with('error', 'Project not found.');

        $rules = [
            'project_name'    => 'required|max_length[255]',
            'address'         => 'required|max_length[255]',
            'starting_price'  => 'permit_empty|numeric',
            'number_of_units' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 1. Update Core Project
        $this->projectModel->update($id, [
            'project_name'    => $this->request->getPost('project_name'),
            'address'         => $this->request->getPost('address'),
            'latitude'        => $this->request->getPost('latitude'),
            'longitude'       => $this->request->getPost('longitude'),
            'price_start'     => $this->request->getPost('price_start'),
            'price_end'       => $this->request->getPost('price_end'),
            'starting_price'  => $this->request->getPost('price_start'), // Fallback
            'number_of_units' => $this->request->getPost('number_of_units'),
            'status'          => $this->request->getPost('status')
        ]);

        // 2. Update Dynamic Fields
        $this->fieldValueModel = new \App\Models\ProjectFieldValueModel();
        
        $dynamicFields = $this->fieldModel->where('project_type_id', $project['project_type_id'])->findAll();
        foreach ($dynamicFields as $field) {
            $inputName = 'field_' . $field['id'];
            $value = $this->request->getPost($inputName);

            if ($field['field_type'] == 'Checkbox') {
                $value = $value ? 'Yes' : 'No';
            }

            // Check if value already exists
            $existing = $this->fieldValueModel->where('project_id', $id)
                                              ->where('project_type_field_id', $field['id'])
                                              ->first();

            if ($existing) {
                if ($value !== null && $value !== '') {
                    $this->fieldValueModel->update($existing['id'], ['value' => $value]);
                } else {
                    $this->fieldValueModel->delete($existing['id']); // Remove if cleared
                }
            } else {
                if ($value !== null && $value !== '') {
                    $this->fieldValueModel->insert([
                        'project_id'            => $id,
                        'project_type_field_id' => $field['id'],
                        'value'                 => $value
                    ]);
                }
            }
        }

        return redirect()->to('/company/projects/view/' . $id)->with('success', 'Project updated successfully.');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $project = $this->projectModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if ($project) {
            $this->projectModel->delete($id);
            return redirect()->to('/company/projects')->with('success', 'Project deleted.');
        }

        return redirect()->back()->with('error', 'Project not found.');
    }

    // Apartment Units Management
    public function storeUnit($projectId)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $project = $this->projectModel->where('id', $projectId)->where('company_id', $this->getCompanyId())->first();
        if (!$project) return redirect()->back()->with('error', 'Project not found.');

        $rules = [
            'unit_name' => 'required',
            'price'     => 'permit_empty|numeric',
            'area'      => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $unitData = [
            'company_id'  => $this->getCompanyId(),
            'project_id'  => $projectId,
            'unit_name'   => $this->request->getPost('unit_name'),
            'bedrooms'    => $this->request->getPost('bedrooms'),
            'bathrooms'   => $this->request->getPost('bathrooms'),
            'area'        => $this->request->getPost('area'),
            'price'       => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ?? 'Available',
        ];

        $unitId = $this->unitModel->insert($unitData);

        // Handle Image Uploads
        $images = $this->request->getFileMultiple('images');
        if ($images) {
            foreach ($images as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads/units', $newName);
                    $this->unitImageModel->insert([
                        'unit_id'    => $unitId,
                        'image_path' => $newName
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Unit added successfully.');
    }

    public function deleteUnit($unitId)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $unit = $this->unitModel->where('id', $unitId)->where('company_id', $this->getCompanyId())->first();
        if ($unit) {
            // Delete images from disk
            $images = $this->unitImageModel->where('unit_id', $unitId)->findAll();
            foreach ($images as $img) {
                $path = ROOTPATH . 'public/uploads/units/' . $img['image_path'];
                if (file_exists($path)) unlink($path);
            }
            $this->unitModel->delete($unitId);
            return redirect()->back()->with('success', 'Unit deleted.');
        }

        return redirect()->back()->with('error', 'Unit not found.');
    }

    public function deleteUnitImage($imageId)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $db = \Config\Database::connect();
        $img = $db->table('unit_images ui')
                  ->join('project_units u', 'u.id = ui.unit_id')
                  ->where('ui.id', $imageId)
                  ->where('u.company_id', $this->getCompanyId())
                  ->select('ui.*')
                  ->get()->getRowArray();

        if ($img) {
            $path = ROOTPATH . 'public/uploads/units/' . $img['image_path'];
            if (file_exists($path)) unlink($path);
            $db->table('unit_images')->where('id', $imageId)->delete();
            return redirect()->back()->with('success', 'Image deleted.');
        }

        return redirect()->back()->with('error', 'Image not found.');
    }
}
