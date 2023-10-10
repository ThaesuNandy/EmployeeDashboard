<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use App\Models\EmployeeModel;

class Dashboard extends BaseController
{
    private $employee = '' ;

    public function __construct(){
      
        $this->employee = new EmployeeModel();       
    }

    public function index()
    {
        $session = session(); 
        $data['employees'] = $this->employee->orderBy('id', 'DESC')->findAll();       
        return view('dashboard', $data);
    }
    public function store()
    {
            helper(['form']);
            $rules = [
                'employeeName' => 'required|max_length[30]',
                'email' => 'required|valid_email|is_unique[employees.email]',
                'phone' => 'required|numeric|is_unique[employees.phone]',
            ];
            if($this->validate($rules))
            {
                $data = [
                    'name' => $this->request->getVar('employeeName'),
                    'email'  => $this->request->getVar('email'),
                    'phone'  => $this->request->getVar('phone'),
                    'address'  => $this->request->getVar('address'),          
                ];
                
                $this->employee->insert($data);    
                $session = session(); 
                $session->setFlashdata('msg', 'Employee Successfully Added');   
                return $this->response->redirect(site_url('/dashboard'));
            }else{
                $data['validation'] = $this->validator->getErrors();
                return $this->response->setJSON(['error' => $data['validation']])->setStatusCode(400);
            }
          
       
    }
}