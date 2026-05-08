<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitDataModel extends Model
{
    protected $table = 'visit_data';
    protected $primaryKey = 'id';
    protected $allowedFields = ['page_url', 'ip_address', 'visit_date'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getVisitCounts($filter = 'month', $pages = [])
{
    switch ($filter) {
        case 'today':
            $startDate = date('Y-m-d 00:00:00'); // e.g., 2025-06-11 00:00:00
            break;
        case 'month':
            $startDate = date('Y-m-01 00:00:00'); // e.g., 2025-06-01 00:00:00
            break;
        case 'year':
            $startDate = date('Y-01-01 00:00:00'); // e.g., 2025-01-01 00:00:00
            break;
        default:
            $startDate = date('Y-m-01 00:00:00'); // Default to start of current month
            break;
    }

    $data = ['categories' => [], 'data' => []];
    $pageMap = [
        '/websitebinan/public/home' => 'Home',
        '/websitebinan/public/about' => 'Mission & Vision',
        '/websitebinan/public/officials' => 'City Officials',
        '/websitebinan/public/history' => 'History',
        '/websitebinan/public/invest' => 'Invest',
        '/websitebinan/public/contact' => 'Contact',
        '/websitebinan/public/map' => 'Maps',
        '/websitebinan/public/fulldisc' => 'Full Disclosure Policy',
        '/websitebinan/public/careers' => 'Careers',
        '/websitebinan/public/jobs' => 'Jobs',
    ];

    // Query to get counts for grouped categories
    $query = $this->select("CASE 
                                WHEN page_url LIKE '%newsevents%' THEN 'News & Events'
                                WHEN page_url LIKE '%department%' THEN 'Departments'
                                WHEN page_url LIKE '%barangay%' THEN 'Barangays'
                                ELSE page_url 
                            END as grouped_page_url, COUNT(*) as total_visits")
                  ->where('visit_date >=', $startDate)
                  ->groupBy('grouped_page_url');

    $results = $query->get()->getResultArray();

    // Map results to categories and data
    foreach ($results as $result) {
        $category = $pageMap[$result['grouped_page_url']] ?? $result['grouped_page_url'];
        if (in_array($result['grouped_page_url'], $pages) || $category === 'News & Events' || $category === 'Departments' || $category === 'Barangays') {
            $data['categories'][] = $category;
            $data['data'][] = (int)$result['total_visits'];
        }
    }

    return $data;
}

    public function getTopPageByWeek()
{
    // Get the start of the current week (Monday) and end (Sunday)
    $startOfWeek = date('Y-m-d 00:00:00', strtotime('last Monday', strtotime('today')));
    $endOfWeek = date('Y-m-d 23:59:59', strtotime('next Sunday', strtotime('today')));

    $pageMap = [
        '/websitebinan/public/home' => 'Home',
        '/websitebinan/public/about' => 'Mission & Vision',
        '/websitebinan/public/officials' => 'City Officials',
        '/websitebinan/public/history' => 'History',
        '/websitebinan/public/invest' => 'Invest',
        '/websitebinan/public/contact' => 'Contact',
        '/websitebinan/public/map' => 'Maps',
        '/websitebinan/public/fulldisc' => 'Full Disclosure Policy',
        '/websitebinan/public/careers' => 'Careers',
        '/websitebinan/public/mayor' => 'Mayors Corner',
        '/websitebinan/public/hotlines' => 'Hotlines',
        '/websitebinan/public/announcements/1' => 'Announcements',
        '/websitebinan/public/jobs' => 'Jobs',
    ];

    // Use CASE to group related URLs
    $result = $this->select("CASE 
                                WHEN page_url LIKE '%newsevents%' THEN 'News & Events'
                                WHEN page_url LIKE '%department%' THEN 'Departments'
                                WHEN page_url LIKE '%barangay%' THEN 'Barangays'
                                WHEN page_url LIKE '%Announcement%' THEN 'Announcements'
                                ELSE page_url 
                             END as grouped_page_url, COUNT(*) as visit_count")
                   ->where('visit_date >=', $startOfWeek)
                   ->where('visit_date <=', $endOfWeek)
                   ->groupBy('grouped_page_url')
                   ->orderBy('visit_count', 'DESC')
                   ->first();

    if ($result) {
        $result['page_name'] = $pageMap[$result['grouped_page_url']] ?? $result['grouped_page_url'];
    }

    return $result;
}
}