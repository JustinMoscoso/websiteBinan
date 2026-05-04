<?php
namespace App\Models;
use CodeIgniter\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'ID';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'title', 
        'description', 
        'company', 
        'type',
        'publication_date', 
        'email',
        'status', 
        'created_date', 
        'updated_date'
    ];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';
    
    // Validation
    protected $validationRules = [
        'title' => 'required',
        'description' => 'required',
        'company' => 'required',
        'type' => 'required',
        'publication_date' => 'required',
        'email' => 'required|valid_email',
        'status' => 'required',
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Job title is required',
            'min_length' => 'Job title must be at least 3 characters long',
            'max_length' => 'Job title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Job description is required',
            'min_length' => 'Job description must be at least 10 characters long'
        ],
        'company' => [
            'required' => 'Company is required',
        ],
        'type' => [
            'required' => 'Job type is required',
            'in_list' => 'Please select a valid job type'
        ],
        'publication_date' => [
            'required' => 'Publication date is required',
            'valid_date' => 'Invalid publication date format'
        ],
        'email' => [
            'required' => 'Contact email is required',
            'valid_email' => 'Please enter a valid email address'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid status value'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $beforeInsert = ['setCreatedDate'];
    protected $beforeUpdate = ['setUpdatedDate'];
    
    protected function setCreatedDate(array $data)
    {
        if (!isset($data['data']['created_date'])) {
            $data['data']['created_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }
    
    protected function setUpdatedDate(array $data)
    {
        $data['data']['updated_date'] = date('Y-m-d H:i:s');
        return $data;
    }
} 