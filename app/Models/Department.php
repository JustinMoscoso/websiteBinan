<?php

namespace App\Models;

use CodeIgniter\Model;

class Department extends Model
{
    protected $table = 'department_content';
    protected $primaryKey = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = ['dept_name', 'head', 'post_title', 'mission', 'vision', 'img_logo', 'org_chart_img', 'quality_policy', 'about', 'contact', 'phone_number', 'landline', 'email_address', 'office_address', 'created_date', 'updated_date', 'status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'updated_date';
    protected $deletedField  = null;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
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

    // 'about' and 'contact' fields are now supported for department_content
}

?>
