<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
// use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form'];

    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $loginId = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');

        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType === 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules'  => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required'      => 'Email is required',
                        'valid_email'   => 'Please check the email field',
                        'is_not_unique' => 'Email does not exist in our system'
                    ]
                ],
                'password' => [
                    'rules'  => 'required|min_length[5]|max_lenght[45]',
                    'errors' => [
                        'required'    => 'Password is required',
                        'min_length'  => 'Password must be at least 5 characters long',
                        'max_length'  => 'Password must not have characters more than 45 in length'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules'  => 'required|alpha_numeric|is_not_unique[users.username]',
                    'errors' => [
                        'required'      => 'Username is required',
                        'is_not_unique' => 'Username does not exist in our system'
                    ]
                ],
                'password' => [
                    'rules'  => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required'    => 'Password is required',
                        'min_length'  => 'Password must be at least 6 characters long',
                        'max_length'  => 'Password must not have characters more than 45 in length'
                    ]
                ]
            ]);
        }

        if (!$isValid) {
            return view('backend/pages/auth/login',[
                'pageTitle' =>'Login',
                'validation'=>$this->validator
            ]);
        }else{
            $user = new User();
            $userInfo = $user->where($fieldType,$this->request->getVar('login_id'))->first();
            $check_password = Hash::check($this->getVar('password'),$userInfo['password']);
            if(!$check_password){
                return redirect()->route('admin.login.form')->with('fail','Wrong password')->withInput();
            }else{
                CIAuth::setCIAut($userInfo);
                return redirect()->route('admin.home');
            }
        }

        // Proceed with authentication logic here (e.g., check credentials)
    }

    public 
}
