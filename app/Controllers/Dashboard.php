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
  
}