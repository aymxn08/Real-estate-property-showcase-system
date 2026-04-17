<?php

namespace App\Controllers;

use App\Models\EnquiryModel;

class EnquiryController extends BaseController
{
    protected $enquiryModel;

    public function __construct()
    {
        $this->enquiryModel = new EnquiryModel();
    }

    public function index()
    {
        $companyId = session()->get('company_id');

        // Fetch enquiries with project name
        $enquiries = $this->enquiryModel->select('enquiries.*, projects.project_name')
            ->join('projects', 'projects.id = enquiries.project_id')
            ->where('enquiries.company_id', $companyId)
            ->orderBy('enquiries.created_at', 'DESC')
            ->findAll();

        $data = [
            'enquiries' => $enquiries
        ];

        return view('company/enquiries/index', $data);
    }

    public function updateStatus($id)
    {
        $companyId = session()->get('company_id');
        $enquiry = $this->enquiryModel->where('company_id', $companyId)->find($id);

        if (!$enquiry) {
            return redirect()->back()->with('error', 'Enquiry not found or access denied.');
        }

        $newStatus = $this->request->getPost('status');
        if (in_array($newStatus, ['New', 'Read', 'Contacted'])) {
            $this->enquiryModel->update($id, ['status' => $newStatus]);
            return redirect()->back()->with('success', 'Enquiry status updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid status.');
    }
}
