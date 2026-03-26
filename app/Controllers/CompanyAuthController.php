<?php

namespace App\Controllers;

use App\Models\CompanyModel;

class CompanyAuthController extends BaseController
{
    public function register()
    {
        return view('company/register');
    }

    public function processRegister()
    {
        $model = new CompanyModel();
        
        $rules = [
            'company_name' => 'required|min_length[3]|max_length[255]',
            'email'        => 'required|valid_email|is_unique[companies.email]',
            'password'     => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'company_name'   => $this->request->getPost('company_name'),
            'email'          => $this->request->getPost('email'),
            'contact_number' => $this->request->getPost('contact_number'),
            'password_hash'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'status'         => 'Pending'
        ];

        $model->insert($data);

        return redirect()->to('/company/login')->with('success', 'Registration successful! Please wait for Super Admin approval before logging in.');
    }

    public function login()
    {
        if (session()->get('company_id')) {
            return redirect()->to('/company/dashboard');
        }
        return view('company/login');
    }

    public function processLogin()
    {
        $session = session();
        $model = new CompanyModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $company = $model->where('email', $email)->first();

        if ($company) {
            if ($company['status'] === 'Pending') {
                return redirect()->back()->with('error', 'Your account is pending approval by the Super Admin.');
            }
            if ($company['status'] === 'Suspended') {
                return redirect()->back()->with('error', 'Your account has been suspended.');
            }

            if (password_verify($password, $company['password_hash'])) {
                $sessionData = [
                    'company_id'   => $company['id'],
                    'company_name' => $company['company_name'],
                    'email'        => $company['email'],
                    'is_company'   => true,
                ];
                $session->set($sessionData);
                return redirect()->to('/company/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            return redirect()->back()->with('error', 'Company not found.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/company/login');
    }
}
