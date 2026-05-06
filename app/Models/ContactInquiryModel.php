<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactInquiryModel extends Model
{
    protected $table = 'contact_inquiries';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'subject', 'message', 'status'];
}