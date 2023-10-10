<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmployeeController extends BaseController
{
	public function index()
    {
		$data=[];
		$employeeModel = new EmployeeModel();
		$data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		$data['perPage'] = 50;
		$data['total'] = $employeeModel->countAll();
		$data['data'] = $employeeModel->orderBy('updated_at', 'desc')
									  ->paginate($data['perPage']);
        $data['pager'] = $employeeModel->pager;
        return view('dashboard', $data);
    }

	public function editEmployee($employeeId)
    {
        $employeeModel = new \App\Models\EmployeeModel();
        $employee = $employeeModel->find($employeeId);

        if ($employee === null) {
            return redirect()->to('/dashboard')->with('error', 'Employee not found.');
        }

        $data = [
            'employee' => $employee,
        ];

        return view('edit_employee', $data); 
    }
	public function updateEmployee($employeeId)
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');

        $employeeModel = new \App\Models\EmployeeModel(); 

        $existingEmployee = $employeeModel->find($employeeId);

        if ($existingEmployee === null) {
            return redirect()->to('/dashboard')->with('error', 'Employee not found.');
        }

        $dataToUpdate = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
        ];

        $employeeModel->update($employeeId, $dataToUpdate);

        return redirect()->to('/dashboard')->with('success', 'Employee updated successfully.');
    }

	public function deleteEmployee($employeeId)
    {
        $employeeModel = new \App\Models\EmployeeModel(); 

        $existingEmployee = $employeeModel->find($employeeId);

        if ($existingEmployee === null) {
            return redirect()->to('/dashboard')->with('error', 'Employee not found.');
        }

        $employeeModel->delete($employeeId);

		session()->setFlashdata('message', 'Employee deleted successfully.');
		session()->setFlashdata('alert-class', 'alert-success');

        return redirect()->to('/dashboard');
    }

    function export()
    {
        $employee_object = new EmployeeModel();
		$data = $employee_object->findAll();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'ID');
		$sheet->setCellValue('B1', 'Employee Name');
		$sheet->setCellValue('C1', 'Email');
		$sheet->setCellValue('D1', 'Phone');
		$sheet->setCellValue('E1', 'Address');

		$count = 2;

		foreach ($data as $row) {
			$sheet->setCellValue('A' . $count, $row['id']);
			$sheet->setCellValue('B' . $count, $row['name']);
			$sheet->setCellValue('C' . $count, $row['email']);
			$sheet->setCellValue('D' . $count, $row['phone']);
			$sheet->setCellValue('E' . $count, $row['address']);
			$count++;
		}

		$file_name = 'Employee_Data.xlsx';
		$writer = new Xlsx($spreadsheet);
		$writer->save($file_name);

		header("Content-Type: application/vnd.ms-excel");

		header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

		header('Expires: 0');

		header('Cache-Control: must-revalidate');

		header('Pragma: public');

		header('Content-Length:' . filesize($file_name));

		flush();

		readfile($file_name);

		exit;
	}

	public function importCsvToDb()
    {
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,xls,xlsx,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            return view('dashboard', $data); 
        }else
		{
            $file = $this->request->getFile('file');

            if ($file->isValid() && ! $file->hasMoved()) 
			{
                $newName = $file->getRandomName();
				$file->move('../public/excelfiles', $newName);
				$filePath = "../public/excelfiles/" . $newName;
				$spreadsheet = IOFactory::load($filePath);
				$worksheet = $spreadsheet->getActiveSheet();
				$highestRow = $worksheet->getHighestRow();

				$employees = new EmployeeModel();
				$count = 0;
				for ($row = 2; $row <= $highestRow; $row++) 
				{
					$employeeData = [
						'name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
						'email' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
						'phone' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
						'address' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
					];
	
					$email = $employeeData['email'];
					$employee = $employees->where('email', $email)->first();
	
					if ($employee) {
						$employees->update($employee['id'], $employeeData);
						$count++;
					} else {
						$employees->insert($employeeData);
						$count++;
					}
				}

				session()->setFlashdata('message', $count . ' rows successfully added/updated.');
				session()->setFlashdata('alert-class', 'alert-success');
			} else {
				session()->setFlashdata('message', 'Excel file could not be uploaded.');
				session()->setFlashdata('alert-class', 'alert-danger');
			}
		}

        return redirect()->to('/dashboard'); 
    }

	public function modifyDatabaseWithExcel()
    {
        $input = $this->validate([
            'employee_excel_file' => 'uploaded[employee_excel_file]|max_size[employee_excel_file,2048]|ext_in[employee_excel_file,xls,xlsx,csv]',
        ]);

        if (!$input) {
            session()->setFlashdata('message', 'Validation failed. Please upload a valid Excel file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        } else {
            $file = $this->request->getFile('employee_excel_file');

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('../public/excelfiles', $newName);
                $filePath = "../public/excelfiles/" . $newName;

                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();

				$employees = new EmployeeModel();
                $count = 0;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $employeeData = [
                        'name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'email' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        'phone' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'address' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                    ];

                    $email = $employeeData['email'];
                    $employee = $employees->where('email', $email)->first();

                    if ($employee) {
                        $employees->update($employee['id'], $employeeData);
                        $count++;
                    }
                }

                session()->setFlashdata('message', 'Database modified successfully.');
                session()->setFlashdata('alert-class', 'alert-success');
            } else {
                session()->setFlashdata('message', 'Excel file could not be uploaded.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }

        return redirect()->to('/dashboard');
    }
}
