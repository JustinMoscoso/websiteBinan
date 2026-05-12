<?php

namespace App\Controllers;

use App\Models\UserAccount;

class Auther extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper('asset');
        $this->session = \Config\Services::session();
        if (is_cli()) {
            echo "CLI access disabled for this control.";
            exit;
        }
    }

    public function index()
    {
        return redirect(base_url());
    }

    public function login()
    {
        $sess_user = $this->session->get('user');
        if ($sess_user) {
            return redirect('admin/dashboard');
        } else {
            return view('login_page');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect('auth/login');
    }

    public function ajax($mode)
    {
        if (!$this->request->isAJAX()) {
            exit;
        }

        $this->response->setHeader('Access-Control-Allow-Origin', '*')
                       ->setHeader('Content-type', 'application/json');

        $status = 0;
        $message = "";
        $data = [];

        if ($mode == 'login') {
            $userAccountModel = new UserAccount();
            $username = $this->request->getPost('usern');
            $password = $this->request->getPost('passw');

            $status = 0;
            $message = 'Invalid login credentials.';

            if (empty($username) || empty($password)) {
                $message = 'Username and password are required.';
            }

            $user = $userAccountModel->where('username', $username)
                                     ->orWhere('email', $username)
                                     ->first();

            if ($user && password_verify($password, $user->pass)) {
                if ($user->status !== 'ACTIVE') {
                    $message = 'User account is not active.';
                } else if ($user->force_pass_reset == 1) {
                    $status = 2; // Signal for forced password change
                    $message = 'Temporary password detected. Please change your password.';
                    $data = ['userId' => $user->ID];
                } else {
                    // Set session
                    $this->session->set('user', $user);
                    $status = 1;
                    $message = 'Login successful.';
                }
            } else {
                $message = 'Invalid username or password.';
            }
        }

        if ($mode == 'change_temp_password') {
            $userId = $this->request->getPost('userId');
            $newPassword = $this->request->getPost('newPassword');

            if (empty($userId) || empty($newPassword)) {
                $message = 'Invalid request.';
            } else {
                $userAccountModel = new UserAccount();
                $updateData = [
                    'pass' => password_hash($newPassword, PASSWORD_DEFAULT),
                    'force_pass_reset' => 0,
                    'updated_date' => date('Y-m-d H:i:s')
                ];

                if ($userAccountModel->update($userId, $updateData)) {
                    $user = $userAccountModel->find($userId);
                    $this->session->set('user', $user);
                    $status = 1;
                    $message = 'Password updated successfully. Logging in...';
                } else {
                    $message = 'Failed to update password.';
                }
            }
        }

        echo json_encode([
            'status' => $status,
            'data' => isset($data) ? $data : '',
            'message' => $message,
        ]);
    }

    protected function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
