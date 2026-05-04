<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitCountModel extends Model
{
    protected $table = 'visit_counts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['visit_date', 'ip_address', 'visit_count'];

    public function incrementVisitCount($ipAddress)
    {
        $today = date('Y-m-d');
        $visit = $this->where('visit_date', $today)->where('ip_address', $ipAddress)->first();

        if ($visit) {
            // Do nothing if IP already visited today (unique count)
        } else {
            // Insert a new record for a unique IP visit
            $this->insert([
                'visit_date' => $today,
                'ip_address' => $ipAddress,
                'visit_count' => 1
            ]);
        }
    }

    public function getTodayVisitCount()
    {
        $today = date('Y-m-d');
        $visits = $this->where('visit_date', $today)->findAll();
        return count($visits); // Returns the number of unique IP visits today
    }

    // New method to fetch visit counts based on a filter
public function getVisitCountByFilter($filter) 
{
    if ($filter === 'Today') {
        return $this->selectSum('visit_count')
                    ->where('visit_date', date('Y-m-d'))
                    ->get()
                    ->getRow()
                    ->visit_count ?? 0;
    } elseif ($filter === 'This Month') { // Fixed filter name
        return $this->selectSum('visit_count')
                    ->where('visit_date >=', date('Y-m-01'))
                    ->where('visit_date <=', date('Y-m-t'))
                    ->get()
                    ->getRow()
                    ->visit_count ?? 0;
    } elseif ($filter === 'This Year') { // Fixed filter name
        return $this->selectSum('visit_count')
                    ->where('visit_date >=', date('Y-01-01'))
                    ->where('visit_date <=', date('Y-12-31'))
                    ->get()
                    ->getRow()
                    ->visit_count ?? 0;
    }
    return 0; // Default case
}
}