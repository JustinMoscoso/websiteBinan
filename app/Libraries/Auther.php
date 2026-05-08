<?php

namespace App\Libraries;

class Auther
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function is_allow_access($priv)
    {
        $user = $this->session->get('user');
        $priv[] = 'DEV';
        if ($user->status == 'ACTIVE') {
            foreach ($priv as $elem) {
                if (in_array($elem, $user->account_level)) {
                    return true;
                }
            }
        }
        return false;
    }


    public function devGate($username, $password)
    {
        $cfg = config('Auther');
        $acct = (object)array(
            'ID' => -1,
            'username' => 'admin',
            'password' => '1234',
            'email' => 'admin@example.com',
            'firstname' => 'Adjara',
            'lastname' => 'Min',
            'account_level' => 'DEV',
            'status' => 'ACTIVE',
            'dtcreated' => date('Y-m-d H:i:s'),
            'dtupdated' => null,
        );

        return $cfg->user == $username && password_verify($password, $cfg->password) ? $acct : null;
    }
}