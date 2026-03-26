<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\BookingModel;

class CompanyDashboardController extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('is_company')) {
            return redirect()->to('/company/login');
        }

        $companyId = session()->get('company_id');
        $projectModel = new ProjectModel();
        $bookingModel = new BookingModel();

        $data = [
            'total_projects' => $projectModel->where('company_id', $companyId)->countAllResults(),
            'total_bookings' => $bookingModel->where('company_id', $companyId)->countAllResults(),
            'new_bookings'   => $bookingModel->where('company_id', $companyId)->where('status', 'New')->countAllResults(),
        ];

        return view('company/dashboard', $data);
    }

    public function profile()
    {
        if (!session()->get('is_company')) return redirect()->to('/company/login');

        $model = new CompanyModel();
        $data['company'] = $model->find(session()->get('company_id'));
        return view('company/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('is_company')) return redirect()->to('/company/login');

        $model = new CompanyModel();
        $companyId = session()->get('company_id');

        $data = [
            'company_name'   => $this->request->getPost('company_name'),
            'about'          => $this->request->getPost('about'),
            'contact_number' => $this->request->getPost('contact_number'),
            'address'        => $this->request->getPost('address'),
        ];

        $logo = $this->request->getFile('logo');
        if ($logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move('uploads/logos', $newName);
            $data['logo'] = $newName;
        }

        $model->update($companyId, $data);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
