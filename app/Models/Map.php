<?php
namespace App\Models;
use CodeIgniter\Model;

class Map extends Model
{
    protected $table = 'map'; // Exact table name (lowercase)
    protected $primaryKey = 'ID'; // Exact primary key (uppercase)
    protected $allowedFields = ['brgy_name', 'top_loc', 'left_loc', 'details', 'status', 'created_date', 'updated_date'];
    protected $returnType = 'array'; // Return data as arrays
    protected $useTimestamps = false; // Disable timestamps for now
    protected $validationRules = []; // No validation rules
    protected $beforeInsert = []; // No hooks
    protected $beforeUpdate = []; // No hooks

    public function __construct()
    {
        parent::__construct();
        // Log model initialization for debugging
        error_log("Map model initialized. Table: " . $this->table . ", PrimaryKey: " . $this->primaryKey . ", AllowedFields: " . json_encode($this->allowedFields));
    }
}