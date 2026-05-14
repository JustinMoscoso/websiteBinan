<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAccount extends Model
{
    protected $table            = 'useradmin';
    protected $primaryKey       = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'fname', 'lname', 'username', 'pass', 'email',
        'user_lvl', 'updated_date', 'created_date', 'dept', 'status',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'updated_date';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'fname'    => 'required|min_length[2]|max_length[100]',
        'lname'    => 'required|min_length[2]|max_length[100]',
        'username' => 'required|min_length[3]|max_length[50]|is_unique[useradmin.username,ID,{ID}]',
        'email'    => 'required|valid_email|is_unique[useradmin.email,ID,{ID}]',
        'pass'     => 'permit_empty|min_length[8]',
        'user_lvl' => 'required|in_list[DEVELOPER,SUPERADMIN,ADMIN,STAFF]',
        'status'   => 'required|in_list[ACTIVE,INACTIVE]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'This username is already taken.',
        ],
        'email' => [
            'is_unique' => 'This email address is already registered.',
        ],
        'user_lvl' => [
            'in_list' => 'Invalid user level provided.',
        ],
        'pass' => [
            'min_length' => 'Password must be at least 8 characters.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}