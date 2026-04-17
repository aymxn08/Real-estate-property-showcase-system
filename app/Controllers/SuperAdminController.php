<?php

namespace App\Controllers;

use App\Models\SuperAdminModel;
use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\BookingModel;

class SuperAdminController extends BaseController
{
    public function login()
    {
        if (session()->get('is_super_admin')) {
            return redirect()->to('/super-admin/dashboard');
        }
        return view('admin/login');
    }

    public function processLogin()
    {
        $session = session();
        $model = new SuperAdminModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $admin = $model->where('email', $email)->first();

        if ($admin) {
            if (password_verify($password, $admin['password_hash'])) {
                $sessionData = [
                    'super_admin_id' => $admin['id'],
                    'email'          => $admin['email'],
                    'is_super_admin' => true,
                ];
                $session->set($sessionData);
                return redirect()->to('/super-admin/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            return redirect()->back()->with('error', 'Admin not found.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/super-admin/login');
    }

    public function dashboard()
    {
        $companyModel = new CompanyModel();
        $projectModel = new ProjectModel();
        $bookingModel = new BookingModel();

        $data = [
            'total_companies'   => $companyModel->where('is_deleted', 0)->countAllResults(),
            'pending_companies' => $companyModel->where('status', 'Pending')->where('is_deleted', 0)->countAllResults(),
            'total_projects'    => $projectModel->countAllResults(),
            'total_bookings'    => $bookingModel->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }

    public function companies()
    {
        $companyModel = new CompanyModel();
        // findAll() is overridden in model to filter by is_deleted
        $data['companies'] = $companyModel->findAll();
        return view('admin/companies', $data);
    }

    public function deleteCompany($id)
    {
        // Role-based authorization check
        if (!session()->get('is_super_admin')) {
            return redirect()->to('/super-admin/login')->with('error', 'Unauthorized access.');
        }

        $companyModel = new CompanyModel();
        $projectModel = new ProjectModel();

        // Check if company exists and is not already deleted
        $company = $companyModel->where('is_deleted', 0)->find($id);
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }

        // Prevent deletion if company has active projects
        $activeProjects = $projectModel->where('company_id', $id)
                                      ->where('status', 'Active')
                                      ->countAllResults();
        
        if ($activeProjects > 0) {
            return redirect()->back()->with('error', "Cannot delete company. It has {$activeProjects} active project(s).");
        }

        // Perform soft delete
        if ($companyModel->update($id, ['is_deleted' => 1])) {
            return redirect()->to('/super-admin/companies')->with('success', 'Company has been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete company.');
        }
    }

    public function updateCompanyStatus($id)
    {
        $companyModel = new CompanyModel();
        
        // Ensure we don't update a deleted company
        $company = $companyModel->where('is_deleted', 0)->find($id);
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }

        $status = $this->request->getPost('status');
        if (in_array($status, ['Pending', 'Approved', 'Suspended'])) {
            $companyModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Company status updated.');
        }
        return redirect()->back()->with('error', 'Invalid status.');
    }
}
