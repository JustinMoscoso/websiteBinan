<?php

namespace App\Controllers\Admin;

use App\Models\UserAccount;
use App\Models\Audit;

/**
 * UserController
 *
 * Handles all AJAX operations for admin user management:
 *   GET  cases: get_users, get_user
 *   POST cases: create_user, update_user, set_status_user, reset_password, change_pass
 *
 * Called via: POST/GET admin/ajax/[case_name]
 */
class UserController extends BaseAdminController
{
    private UserAccount $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserAccount();
    }

    // -------------------------------------------------------------------------
    // GET
    // -------------------------------------------------------------------------

    /**
     * Return all users.
     * AJAX GET admin/ajax/get_users
     */
    public function getUsers(): \CodeIgniter\HTTP\ResponseInterface
    {
        $users = $this->userModel->findAll();
        return $this->response->setJSON(['status' => 1, 'data' => $users]);
    }

    /**
     * Return a single user by ID.
     * AJAX POST admin/ajax/get_user
     */
    public function getUser(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id   = $this->request->getPost('id');
        $user = $this->userModel->find($id);

        return $user
            ? $this->response->setJSON(['status' => 1, 'data' => $user])
            : $this->jsonFail('User not found.');
    }

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------

    /**
     * Create a new admin user.
     * AJAX POST admin/ajax/create_user
     */
    public function createUser(): \CodeIgniter\HTTP\ResponseInterface
    {
        $fname  = $this->request->getPost('txtFirstName');
        $lname  = $this->request->getPost('txtLastName');
        $usern  = $this->request->getPost('txtUsername');
        $email  = $this->request->getPost('txtEmail');
        $passw  = $this->request->getPost('txtPassword');
        $acclvl = $this->request->getPost('txtAccLevel');
        $dept   = $this->request->getPost('txtDept');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonFail('Invalid email address.');
        }

        if ($this->userModel->where('username', $usern)->orWhere('email', $email)->countAllResults() > 0) {
            return $this->jsonFail('Username or email already exists.');
        }

        $userData = [
            'fname'        => $fname,
            'lname'        => $lname,
            'username'     => $usern,
            'pass'         => password_hash($passw, PASSWORD_ARGON2ID),
            'email'        => $email,
            'user_lvl'     => $acclvl,
            'dept'         => $dept,
            'status'       => 'ACTIVE',
            'created_date' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->userModel->save($userData);
            $newId = $this->userModel->getInsertID();
            $this->saveAuditLog('create_user', "ACCOUNT_ID: $newId $lname");
            return $this->jsonSuccess('User created successfully.');
        } catch (\Exception $e) {
            log_message('error', '[create_user] ' . $e->getMessage());
            return $this->jsonFail('An error occurred while saving the user data.');
        }
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------

    /**
     * Update an existing admin user.
     * AJAX POST admin/ajax/update_user
     */
    public function updateUser(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id     = $this->request->getPost('id');
        $fname  = $this->request->getPost('editFirstName');
        $lname  = $this->request->getPost('editLastName');
        $usern  = $this->request->getPost('editUsername');
        $email  = $this->request->getPost('editEmail');
        $acclvl = $this->request->getPost('editAccLevel');
        $dept   = $this->request->getPost('editDept');
        $passw  = $this->request->getPost('editPassword');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonFail('Invalid email address.');
        }

        $existing = $this->userModel
            ->where('id !=', $id)
            ->groupStart()
                ->where('username', $usern)
                ->orWhere('email', $email)
            ->groupEnd()
            ->first();

        if ($existing) {
            return $this->jsonFail('Username or email already exists.');
        }

        $data = [
            'fname'        => $fname,
            'lname'        => $lname,
            'username'     => $usern,
            'email'        => $email,
            'user_lvl'     => $acclvl,
            'dept'         => $dept,
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        if (! empty($passw)) {
            $data['pass'] = password_hash($passw, PASSWORD_ARGON2ID);
        }

        try {
            $this->userModel->update($id, $data);
            $this->saveAuditLog('update_user', "ACCOUNT_ID: $id");
            return $this->jsonSuccess('User updated successfully.');
        } catch (\Exception $e) {
            log_message('error', '[update_user] ' . $e->getMessage());
            return $this->jsonFail('An error occurred while updating the user data.');
        }
    }

    /**
     * Reset a user's password to a random temporary code.
     * AJAX POST admin/ajax/reset_password
     */
    public function resetPassword(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id            = $this->request->getPost('id');
        $temporaryCode = bin2hex(random_bytes(4));

        $this->userModel->update($id, [
            'pass'         => password_hash($temporaryCode, PASSWORD_ARGON2ID),
            'updated_date' => date('Y-m-d H:i:s'),
        ]);

        $this->saveAuditLog('reset_password', "ACCOUNT_ID: $id PASSWORD RESET");
        return $this->jsonSuccess($temporaryCode);
    }

    // -------------------------------------------------------------------------
    // STATUS
    // -------------------------------------------------------------------------

    /**
     * Toggle user account active/inactive status.
     * AJAX POST admin/ajax/set_status_user
     */
    public function setStatusUser(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $this->userModel->update($id, [
            'status'       => $status,
            'updated_date' => date('Y-m-d H:i:s'),
        ]);

        $this->saveAuditLog('set_status_user', "ACCOUNT_ID: $id - $status");
        return $this->jsonSuccess('User status updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function saveAuditLog(string $process, string $details): void
    {
        try {
            $log = $this->initAuditLog($process);
            $log['processDetails'] = $details;
            (new \App\Models\Audit())->save($log);
        } catch (\Exception $e) {
            log_message('error', '[AuditLog] ' . $e->getMessage());
        }
    }
}
