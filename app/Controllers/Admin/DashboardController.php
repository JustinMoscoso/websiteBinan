<?php

namespace App\Controllers\Admin;

use App\Models\VisitCountModel;
use App\Models\VisitDataModel;
use App\Models\Department;

/**
 * DashboardController
 *
 * Handles:
 *  - mode()         — renders the admin SPA shell with dynamic content
 *  - getUserCount() — AJAX: user statistics
 *  - getVisitCount() — AJAX: site visit count
 *  - getRecentNews() — AJAX: recent news list
 *  - getRecentAnns() — AJAX: recent announcements list
 *  - logVisit()     — legacy endpoint (no-op, handled by filter)
 */
class DashboardController extends BaseAdminController
{
    /**
     * Main admin page renderer.
     * Loads the admin shell view with the appropriate module content.
     */
    public function mode(string $mode = 'dashboard')
    {
        // Restrict accounts_mgmt access to privileged roles
        if ($mode === 'accounts_mgmt' && ! $this->userCan(['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $data = [
            'user' => $this->user,
            'mode' => $mode,
        ];

        if ($mode === 'dashboard') {
            $visitCountModel = new VisitCountModel();
            $visitDataModel  = new VisitDataModel();
            $filter          = $this->request->getGet('filter') ?? 'today';

            $pages = [
                '/websitebinan/public/home',
                '/websitebinan/public/about',
                '/websitebinan/public/officials',
                '/websitebinan/public/history',
                '/websitebinan/public/barangays',
                '/websitebinan/public/jobs',
                '/websitebinan/public/invest',
                '/websitebinan/public/contact',
                '/websitebinan/public/department',
                '/websitebinan/public/map',
                '/websitebinan/public/fulldisc',
                '/websitebinan/public/careers',
            ];

            $visitData                = $visitDataModel->getVisitCounts($filter, $pages);
            $data['visit_count']      = $visitCountModel->getTodayVisitCount();
            $data['filter']           = $filter;
            $data['visit_counts']     = array_map('intval', $visitData['data']);
            $data['visit_labels']     = $visitData['categories'];
            $data['topPage']          = $visitDataModel->getTopPageByWeek();
        }

        if ($mode === 'jobs') {
            $data['departments'] = (new Department())->where('status', 'ACTIVE')->findAll();
        }

        $titles = [
            'dashboard'     => 'Dashboard',
            'contacts'      => 'Contacts',
            'accounts_mgmt' => 'Accounts Management',
            'postcontent'   => 'Post Content',
            'mayor'         => "Mayor's Content",
            'about'         => 'About',
            'brgy'          => 'Barangay',
            'services'      => 'Services',
            'dept'          => 'Department',
            'cityOff'       => 'City Officials',
            'fullDisc'      => 'Full Disclosure Policy',
            'careers'       => 'Careers',
            'jobs'          => 'Job Management',
            'invest'        => 'Invest',
            'profile'       => 'My Profile',
            'audit'         => 'System Logs',
            'map'           => 'Map Management',
        ];

        $data['title'] = $titles[$mode] ?? 'Unknown';

        return view('admin/admin_page', $data);
    }

    /**
     * AJAX: Return user count statistics.
     * GET admin/getUserCount?level=ADMIN
     */
    public function getUserCount()
    {
        $userAccount   = new \App\Models\UserAccount();
        $selectedLevel = $this->request->getGet('level');

        $userCount = ($selectedLevel && $selectedLevel !== 'ALL')
            ? $userAccount->where('user_lvl', $selectedLevel)->where('status', 'Active')->countAllResults()
            : $userAccount->countAllResults();

        $totalActive = $userAccount->where('status', 'Active')->countAllResults();

        return $this->response->setJSON([
            'count'        => $userCount,
            'active_users' => $totalActive,
        ]);
    }

    /**
     * AJAX: Return today's visit count.
     * GET admin/getVisitCount?filter=today
     */
    public function getVisitCount()
    {
        $filter = $this->request->getGet('filter');

        if (! $filter) {
            return $this->response->setContentType('application/json')
                ->setJSON(['error' => 'Filter missing']);
        }

        $visitCount = (new VisitCountModel())->getVisitCountByFilter($filter);

        return $this->response->setContentType('application/json')
            ->setJSON(['visit_count' => $visitCount]);
    }

    /**
     * AJAX: Return recent news items.
     * GET admin/getRecentNews?filter=Today
     */
    public function getRecentNews()
    {
        return $this->_getRecentContent('NEWS');
    }

    /**
     * AJAX: Return recent announcements.
     * GET admin/getRecentAnns?filter=Today
     */
    public function getRecentAnns()
    {
        return $this->_getRecentContent('ANNS');
    }

    /**
     * Shared content fetch for news/announcements.
     */
    private function _getRecentContent(string $category)
    {
        $filter       = $this->request->getGet('filter') ?? 'Today';
        $contentModel = new \App\Models\Content();

        $query = $contentModel
            ->select('title, created_date')
            ->where('category', $category)
            ->where('status', 'ACTIVE')
            ->orderBy('created_date', 'DESC');

        match ($filter) {
            'Today'      => $query->where('DATE(created_date)', date('Y-m-d')),
            'This Month' => $query->where('MONTH(created_date)', date('m'))->where('YEAR(created_date)', date('Y')),
            'This Year'  => $query->where('YEAR(created_date)', date('Y')),
            default      => null,
        };

        return $this->response->setJSON($query->findAll());
    }

    /**
     * Legacy visit logging endpoint — now a no-op.
     * Visit counting is handled by the VisitCounter filter.
     */
    public function logVisit(string $page_url = '')
    {
        // No-op: handled by App\Filters\VisitCounter
    }
}
