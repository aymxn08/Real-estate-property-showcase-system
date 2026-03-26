<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ProjectModel;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $projectModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->projectModel = new ProjectModel();
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
        $builder = $db->table('bookings');
        $builder->select('bookings.*, projects.project_name');
        $builder->join('projects', 'projects.id = bookings.project_id');
        $builder->where('bookings.company_id', $this->getCompanyId());
        
        // Optional filtering by project
        $projectId = $this->request->getGet('project_id');
        if($projectId) {
            $builder->where('bookings.project_id', $projectId);
        }

        $builder->orderBy('bookings.id', 'DESC');
        $data['bookings'] = $builder->get()->getResultArray();
        
        $data['projects'] = $this->projectModel->where('company_id', $this->getCompanyId())->findAll();
        $data['filter_project'] = $projectId;

        return view('company/bookings/index', $data);
    }

    public function updateStatus($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $booking = $this->bookingModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if (!$booking) return redirect()->back()->with('error', 'Booking not found.');

        $status = $this->request->getPost('status');
        if (in_array($status, ['New', 'Contacted', 'Closed', 'Cancelled'])) {
            $this->bookingModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Booking status updated.');
        }

        return redirect()->back()->with('error', 'Invalid status.');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $booking = $this->bookingModel->where('id', $id)->where('company_id', $this->getCompanyId())->first();
        if ($booking) {
            $this->bookingModel->delete($id);
            return redirect()->back()->with('success', 'Booking deleted.');
        }

        return redirect()->back()->with('error', 'Booking not found.');
    }

    // This method simulates a customer submitting a frontend brochure form.
    // In Phase 2, this would be an API endpoint or part of the public site.
    // For now, allow company admin to manually log a booking if they want.
    public function create()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $rules = [
            'project_id'     => 'required|numeric',
            'customer_name'  => 'required|max_length[255]',
            'customer_email' => 'required|valid_email|max_length[255]',
            'customer_phone' => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid customer details provided.');
        }

        $projectId = $this->request->getPost('project_id');
        $project = $this->projectModel->where('id', $projectId)->where('company_id', $this->getCompanyId())->first();
        
        if(!$project) return redirect()->back()->with('error', 'Invalid project selected.');

        $this->bookingModel->insert([
            'company_id'     => $this->getCompanyId(),
            'project_id'     => $projectId,
            'customer_name'  => $this->request->getPost('customer_name'),
            'customer_email' => $this->request->getPost('customer_email'),
            'customer_phone' => $this->request->getPost('customer_phone'),
            'status'         => 'New'
        ]);

        return redirect()->back()->with('success', 'Booking manually added.');
    }
}
