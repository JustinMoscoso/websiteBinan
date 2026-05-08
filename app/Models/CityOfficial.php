<?php

namespace App\Models;

use CodeIgniter\Model;

class CityOfficial extends Model
{
    protected $table = 'officials_content';
    protected $primaryKey = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = ['off_name', 'off_position', 'img_loc', 'created_date', 'updated_date', 'ranking', 'status', 'years_of_service', 'awards', 'personal_data', 'carouselimages'];

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

    public function getAllCityOfficialsSorted()
    {
        $positionsOrder = [
            'CONGRESS',
            'CITY MAYOR',
            'CITY VICE MAYOR',
            'CITY COUNCILOR',
            'ABC PRESIDENT',
            'SK FEDERATION PRESIDENT'
        ];

        $orderQuery = "FIELD(off_position, '" . implode("','", $positionsOrder) . "')";
        return $this->orderBy($orderQuery)->orderBy('ranking')->findAll();
    }

    public function getOfficialById($id)
    {
        return $this->find($id);
    }
}