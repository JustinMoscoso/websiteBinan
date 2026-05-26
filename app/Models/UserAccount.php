<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAccount extends Model
{
    protected $table = 'useradmin';
    protected $primaryKey = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = false;
    protected $allowedFields = ['fname', 'lname', 'username', 'pass', 'email', 'profile_image', 'user_lvl', 'account_type', 'entity_ref_id', 'updated_date', 'created_date', 'dept', 'status', 'force_pass_reset'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';
    protected $deletedField = null;

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}

?>