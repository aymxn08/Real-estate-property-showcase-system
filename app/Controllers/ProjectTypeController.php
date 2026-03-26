<?php

namespace App\Controllers;

use App\Models\ProjectTypeModel;
use App\Models\ProjectTypeFieldModel;

class ProjectTypeController extends BaseController
{
    protected $typeModel;
    protected $fieldModel;
    protected $companyId;

    public function __construct()
    {
        $this->typeModel = new ProjectTypeModel();
        $this->fieldModel = new ProjectTypeFieldModel();
        // Since constructor runs before session might be available in some CI contexts,
        // we'll assign companyId in methods or use a helper
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

        $data['types'] = $this->typeModel->where('company_id', $this->getCompanyId())->findAll();
        return view('company/project_types/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkAccess()) return $redirect;
        return view('company/project_types/create');
    }

    public function store()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $rules = [
            'type_name' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->typeModel->insert([
            'company_id' => $this->getCompanyId(),
            'type_name'  => $this->request->getPost('type_name')
        ]);

        return redirect()->to('/company/project-types')->with('success', 'Project Type created successfully.');
    }

    public function edit($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $data['type'] = $type;
        return view('company/project_types/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $rules = [
            'type_name' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->typeModel->update($id, [
            'type_name' => $this->request->getPost('type_name')
        ]);

        return redirect()->to('/company/project-types')->with('success', 'Project Type updated.');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $this->typeModel->delete($id);
        return redirect()->to('/company/project-types')->with('success', 'Project Type deleted.');
    }

    // Dynamic Fields Management

    public function fields($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $data['type'] = $type;
        $data['fields'] = $this->fieldModel->where('project_type_id', $id)->findAll();

        return view('company/project_types/fields', $data);
    }

    public function storeField($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $rules = [
            'field_name' => 'required|max_length[100]',
            'field_type' => 'required|in_list[Text,Number,Dropdown,Checkbox]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $options = $this->request->getPost('options_json');
        if ($this->request->getPost('field_type') !== 'Dropdown') {
            $options = null; // Only keep options for dropdowns
        }

        $this->fieldModel->insert([
            'project_type_id' => $id,
            'field_name'      => $this->request->getPost('field_name'),
            'field_type'      => $this->request->getPost('field_type'),
            'is_mandatory'    => $this->request->getPost('is_mandatory') ? 1 : 0,
            'options_json'    => $options
        ]);

        return redirect()->to('/company/project-types/fields/'.$id)->with('success', 'Field added successfully.');
    }

    public function deleteField($typeId, $fieldId)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $type = $this->typeModel->where('id', $typeId)->where('company_id', $this->getCompanyId())->first();
        if (!$type) return redirect()->to('/company/project-types')->with('error', 'Project Type not found.');

        $field = $this->fieldModel->where('id', $fieldId)->where('project_type_id', $typeId)->first();
        if ($field) {
            $this->fieldModel->delete($fieldId);
            return redirect()->to('/company/project-types/fields/'.$typeId)->with('success', 'Field removed.');
        }

        return redirect()->back()->with('error', 'Field not found.');
    }
}
