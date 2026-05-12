<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'support_tickets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_number', 
        'user_id', 
        'username', 
        'concern', 
        'status', 
        'assigned_admin_id', 
        'taken_at', 
        'resolved_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Not used
    protected $deletedField  = '';
}
