<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Auth extends BaseController 
{
    public function register()
    {
        $rules = [
            'name' => 'required|is_unique[users.name]',
            'password' => 'required|min_length[8]|max_length[255]'
        ];
        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules)) 
        {
            return $this->getResponse
                (
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $userModel = new UserModel();
        $userModel->save($input);

        $this->getJWTForUser(
            $input['name'],
            ResponseInterface::HTTP_CREATED
        );
        return redirect()->to('/');

    }
   
    public function login()
    {
        $rules = [
            'name' => 'required',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[name, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];
        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules, $errors)) 
        {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
       $this->getJWTForUser($input['name']);
        return redirect()->to('/dashboard');
    }

    private function getJWTForUser( string $name,int $responseCode = ResponseInterface::HTTP_OK)
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByName($name);
            unset($user['password']);

            helper('jwt');

            return $this->getResponse([
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($name)
                    ]);
        } catch (Exception $exception) {
            return $this->getResponse([
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}