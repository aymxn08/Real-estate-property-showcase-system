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
            'total_companies' => $companyModel->countAllResults(),
            'pending_companies' => $companyModel->where('status', 'Pending')->countAllResults(),
            'total_projects'  => $projectModel->countAllResults(),
            'total_bookings'  => $bookingModel->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }

    public function companies()
    {
        $companyModel = new CompanyModel();
        $data['companies'] = $companyModel->findAll();
        return view('admin/companies', $data);
    }

    public function updateCompanyStatus($id)
    {
        $companyModel = new CompanyModel();
        
        $status = $this->request->getPost('status');
        if (in_array($status, ['Pending', 'Approved', 'Suspended'])) {
            $companyModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Company status updated.');
        }
        return redirect()->back()->with('error', 'Invalid status.');
    }
}
