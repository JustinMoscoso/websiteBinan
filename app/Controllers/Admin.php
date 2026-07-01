<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use App\Models\VisitCountModel;
use App\Models\UserAccount;
use App\Models\Content;
use App\Models\Job;
use App\Services\ProfilePictureService;

class Admin extends BaseController
{
    protected $session;
    protected $userAccount;

    public function __construct()
    {
        helper('asset_helper');
        // Load session service
        $this->session = \Config\Services::session();

        $this->userAccount = new UserAccount();
    }
    public function getOfficialDetails($id)
    {
        $model = new \App\Models\CityOfficial();
        $official = $model->getOfficialById($id);

        if ($official) {
            return $this->response->setJSON($official);
        } else {
            return $this->response->setJSON(['error' => 'Official not found'], 404);
        }
    }
    public function mode($mode = 'dashboard')
    {
        $user = $this->session->get('user');

        if (empty($user)) {
            return redirect()->to(base_url('login'));
        }

        $freshUser = $this->userAccount->find($user->ID);
        if ($freshUser) {
            $user = $freshUser;
            $this->session->set('user', $user);
        }

        if (isset($user->user_lvl)) {
            $user->user_lvl = strtoupper(trim($user->user_lvl));
        }

        // Department-scoped ADMIN/ENCODER/VIEWER: restrict to profile, services, and dashboard
        $isDeptScopedAdmin = (in_array($user->user_lvl, ['ADMIN', 'ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'DEPARTMENT');
        $isBrgyScopedAdmin = (in_array($user->user_lvl, ['ADMIN', 'ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'BARANGAY');

        // Encoder/Viewer accounts scoped to a specific department or barangay
        $isDeptScopedEncoder = (in_array($user->user_lvl, ['ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'DEPARTMENT');
        $isBrgyScopedEncoder = (in_array($user->user_lvl, ['ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'BARANGAY');

        // Restrict accounts_mgmt access
        if ($mode === 'accounts_mgmt' && (!in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']) || $isDeptScopedAdmin)) {
            return redirect()->to(base_url($isDeptScopedAdmin ? 'admin/services' : 'admin/dashboard'));
        }

        // Restrict audit (System Logs) to DEVELOPER and SUPERADMIN only
        if ($mode === 'audit' && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN'])) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        // Determine if user belongs to the HRDO, PESO, BPLO, Mayor, or CIO department
        $isHRDO = false;
        $isPESO = false;
        $isBPLO = false;
        $isMayor = false;
        $isCIO = false;
        if (!empty($user->entity_ref_id) && ($user->account_type ?? '') === 'DEPARTMENT') {
            $deptModel = new \App\Models\Department();
            $linkedDept = $deptModel->find($user->entity_ref_id);
            if ($linkedDept) {
                if (stripos($linkedDept->dept_name, 'HRDO') !== false || stripos($linkedDept->dept_name, 'Human Resources') !== false) {
                    $isHRDO = true;
                }
                if (stripos($linkedDept->dept_name, 'PESO') !== false || stripos($linkedDept->dept_name, 'Public Employment Service') !== false) {
                    $isPESO = true;
                }
                if (stripos($linkedDept->dept_name, 'BPLO') !== false || stripos($linkedDept->dept_name, 'Business Permit') !== false) {
                    $isBPLO = true;
                }
                if (stripos($linkedDept->dept_name, 'Mayor') !== false) {
                    $isMayor = true;
                }
                if (stripos($linkedDept->dept_name, 'Information Officer') !== false || stripos($linkedDept->dept_name, 'CIO') !== false) {
                    $isCIO = true;
                }
            }
        }

        // Modes blocked for dept-scoped accounts
        $deptOnlyModes = ['cityOff']; // CIO has access to about and fullDisc, so we remove them from the base list if CIO is true. Wait, we can construct the list conditionally.

        if (!$isCIO && !$isMayor) {
            $deptOnlyModes[] = 'about';
        }
        if (!$isCIO && !$isMayor) {
            $deptOnlyModes[] = 'fullDisc';
        }

        if (!$isHRDO) {
            $deptOnlyModes[] = 'careers'; // non-HRDO depts cannot access careers
        }
        if (!$isPESO) {
            $deptOnlyModes[] = 'jobs'; // non-PESO depts cannot access jobs
        }
        if (!$isBPLO) {
            $deptOnlyModes[] = 'invest'; // non-BPLO depts cannot access invest
        }
        if (!$isMayor && !$isCIO) {
            $deptOnlyModes[] = 'postcontent';
            $deptOnlyModes[] = 'mayor';
        }

        // Restrict specific mode routes based on their required department
        if (in_array($mode, ['postcontent', 'mayor']) && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']) && !($isMayor || $isCIO)) {
            // ADMIN accounts generally have postcontent/mayor access if not dept-scoped.
            return redirect()->to(base_url('admin/dashboard'));
        }
        if ($mode === 'careers' && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']) && !$isHRDO) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        if ($mode === 'jobs' && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']) && !$isPESO) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        if ($mode === 'invest' && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']) && !$isBPLO) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        // Restrict services: non-admin DEPARTMENT accounts cannot access services.
        if ($mode === 'services' && ($user->account_type ?? '') === 'DEPARTMENT' && !$isDeptScopedAdmin) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $deptBlockedModes = array_merge($deptOnlyModes, ['brgy', 'dept']);
        if (!$isMayor && !$isCIO) {
            $deptBlockedModes[] = 'contacts';
        }

        if ($isDeptScopedAdmin && in_array($mode, $deptBlockedModes)) {
            return redirect()->to(base_url('admin/services'));
        }
        if ($isBrgyScopedAdmin && in_array($mode, array_merge($deptOnlyModes, ['dept']))) {
            return redirect()->to(base_url('admin/brgy'));
        }

        // ENCODER scoped to a department: block brgy/dept and unrelated content modules
        if ($isDeptScopedEncoder && in_array($mode, $deptBlockedModes)) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        // ENCODER scoped to a barangay: block dept and unrelated content modules (brgy is allowed)
        if ($isBrgyScopedEncoder && in_array($mode, array_merge($deptOnlyModes, ['dept']))) {
            return redirect()->to(base_url('admin/brgy'));
        }



        $data['user'] = $user;
        $data['mode'] = $mode;
        $data['is_hrdo'] = $isHRDO;
        $data['is_peso'] = $isPESO;
        $data['is_bplo'] = $isBPLO;
        $data['is_mayor'] = $isMayor;
        $data['is_cio'] = $isCIO;
        $data['is_dept_encoder'] = $isDeptScopedEncoder;
        $data['is_brgy_encoder'] = $isBrgyScopedEncoder;

        if ($mode === 'dashboard') {
            $visitCountModel = new \App\Models\VisitCountModel();
            $data['visit_count'] = $visitCountModel->getTodayVisitCount();

            $visitDataModel = new \App\Models\VisitDataModel();
            $filter = $this->request->getGet('filter') ?? 'today';
            $data['filter'] = $filter;
            $pages = [
                '/websitebinan/public/home',              // Page 1: Home
                '/websitebinan/public/about',             // Page 1: Mission & Vision
                '/websitebinan/public/officials',         // Page 1: City Officials
                '/websitebinan/public/history',           // Page 1: History
                '/websitebinan/public/barangays',         // Page 1: Barangays
                '/websitebinan/public/jobs',              // Page 1: Jobs


                '/websitebinan/public/invest',            // Page 2: Invest
                '/websitebinan/public/contact',           // Page 2: Contact
                '/websitebinan/public/department',        // Page 2: Departments
                '/websitebinan/public/map',               // Page 2: Maps
                '/websitebinan/public/fulldisc',          // Page 2: Full Disclosure Policy
                '/websitebinan/public/careers',           // Page 2: Careers
            ];

            $visit_data = $visitDataModel->getVisitCounts($filter, $pages);
            $data['visit_counts'] = array_map('intval', $visit_data['data']); // Force integers
            $data['visit_labels'] = $visit_data['categories'];

            // Add the top page data for the week
            $topPage = $visitDataModel->getTopPageByWeek();
            $data['topPage'] = $topPage;
        }

        switch ($mode) {
            case 'dashboard':
                $data['title'] = 'Dashboard';
                break;
            case 'contacts':
                $data['title'] = 'Contacts';
                break;
            case 'accounts_mgmt':
                $data['title'] = 'Accounts Management';
                break;
            case 'postcontent':
                $data['title'] = 'Post Content';
                break;
            case 'mayor':
                $data['title'] = 'Mayor\'s Content';
                break;
            case 'about':
                $data['title'] = 'About';
                break;
            case 'brgy':
                $data['title'] = 'Barangay';
                break;
            case 'services':
                $data['title'] = 'Services';
                break;
            case 'dept':
                $data['title'] = 'Department';
                break;
            case 'cityOff':
                $data['title'] = 'City Officials';
                break;
            case 'fullDisc':
                $data['title'] = 'Full Disclosure Policy';
                break;
            case 'careers':
                $data['title'] = 'Careers';
                break;
            case 'jobs':
                $data['title'] = 'Job Management';
                // Load departments for job form
                $deptModel = new \App\Models\Department();
                $data['departments'] = $deptModel->where('status', 'ACTIVE')->findAll();
                break;
            case 'invest':
                $data['title'] = 'Invest';
                break;
            case 'profile':
                $data['title'] = 'My Profile';
                $data['current_department'] = '';
                $data['profile_department'] = null;
                $data['profile_barangay'] = null;
                $data['profile_picture_url'] = !empty($user->profile_image)
                    ? site_url('admin/image/PROFILE/' . $user->profile_image)
                    : '';
                if (!empty($user->entity_ref_id) && ($user->account_type ?? '') === 'DEPARTMENT') {
                    $deptModel = new \App\Models\Department();
                    $department = $deptModel->find($user->entity_ref_id);
                    $data['profile_department'] = $department;
                    $data['current_department'] = $department->dept_name ?? '';
                } elseif (!empty($user->entity_ref_id) && ($user->account_type ?? '') === 'BARANGAY') {
                    $brgyModel = new \App\Models\Barangay();
                    $barangay = $brgyModel->find($user->entity_ref_id);
                    $data['profile_barangay'] = $barangay;
                    $data['current_department'] = $barangay->brgy_name ?? '';
                } elseif (!empty($user->dept)) {
                    $data['current_department'] = $user->dept;
                }
                break;
            case 'audit':
                $data['title'] = 'System Logs';
                break;
            case 'map':
                $data['title'] = 'Map Management';
                break;

            default:
                $data['title'] = 'Unknown';
                break;
        }

        return view('admin/admin_page', $data);
    }
    public function logVisit($page_url)
    {
        $this->logPageVisit(); // Use the base controller method
    }

    public function getUserCount()
    {
        $selectedLevel = $this->request->getGet('level');

        if ($selectedLevel && $selectedLevel !== 'ALL') {
            $userCount = $this->userAccount
                ->where('user_lvl', $selectedLevel)
                ->where('status', 'Active')
                ->countAllResults();
        } else {
            // Fetch all users when "ALL" is selected
            $userCount = $this->userAccount
                //->where('status', 'Active')
                ->countAllResults();
        }

        $totalActiveUsers = $this->userAccount
            ->where('status', 'Active')
            ->countAllResults();

        return $this->response->setJSON([
            'count' => $userCount,
            'active_users' => $totalActiveUsers
        ]);
    }

    public function getRecentNews()
    {
        // Corrected: Match the request key with frontend data-filter attribute
        $filter = $this->request->getGet('filter'); // "Today", "This Month", "This Year"
        $contentModel = new Content();

        // Ensure filter has a valid value
        if (!$filter) {
            $filter = "Today"; // Default to Today
        }

        // Initialize query
        $query = $contentModel->select('title, created_date')
            ->where('category', 'NEWS')
            ->where('status', 'ACTIVE')
            ->orderBy('created_date', 'DESC');

        // Apply filters based on the selection
        if ($filter === "Today") {
            $query->where('DATE(created_date)', date('Y-m-d'));
        } elseif ($filter === "This Month") {
            $query->where('MONTH(created_date)', date('m'))
                ->where('YEAR(created_date)', date('Y'));
        } elseif ($filter === "This Year") {
            $query->where('YEAR(created_date)', date('Y'));
        }

        // Debugging step: Print SQL query for review
        error_log($query->getLastQuery());

        return $this->response->setJSON($query->findAll());
    }

    public function getRecentAnns()
    {
        // Corrected: Match the request key with frontend data-filter attribute
        $filter = $this->request->getGet('filter'); // "Today", "This Month", "This Year"
        $contentModel = new Content();

        // Ensure filter has a valid value
        if (!$filter) {
            $filter = "Today"; // Default to Today
        }

        // Initialize query
        $query = $contentModel->select('title, created_date')
            ->where('category', 'ANNS')
            ->where('status', 'ACTIVE')
            ->orderBy('created_date', 'DESC');

        // Apply filters based on the selection
        if ($filter === "Today") {
            $query->where('DATE(created_date)', date('Y-m-d'));
        } elseif ($filter === "This Month") {
            $query->where('MONTH(created_date)', date('m'))
                ->where('YEAR(created_date)', date('Y'));
        } elseif ($filter === "This Year") {
            $query->where('YEAR(created_date)', date('Y'));
        }

        // Debugging step: Print SQL query for review
        error_log($query->getLastQuery());

        return $this->response->setJSON($query->findAll());
    }

    public function getVisitCount()
    {
        $filter = $this->request->getGet('filter');
        $visitModel = new VisitCountModel();

        if (!$filter) {
            return $this->response->setContentType('application/json')->setJSON(['error' => 'Filter missing']);
        }

        $visitCount = $visitModel->getVisitCountByFilter($filter);

        return $this->response->setContentType('application/json')->setJSON(['visit_count' => $visitCount]);
    }


    public function ajax($mode)
    {
        if (!$this->request->isAJAX()) {
            exit;
        }

        $this->response
            ->setHeader('Access-Control-Allow-Origin', ENVIRONMENT == 'production' ? '*' : '*')
            ->setHeader('Content-type', 'application/json');

        $status = 0;
        $message = "";
        $data = array();

        $user = $this->session->get('user');

        if (!$user) {
            echo json_encode([
                'status' => 0,
                'data' => [],
                'message' => 'Session expired or unauthorized',
            ]);
            exit;
        }

        $freshUser = $this->userAccount->find($user->ID);
        if ($freshUser) {
            $user = $freshUser;
            $this->session->set('user', $user);
        }

        if (isset($user->user_lvl)) {
            $user->user_lvl = strtoupper(trim($user->user_lvl));
        }

        // Helper flags for scoped-ADMIN/ENCODER/VIEWER enforcement
        $isDeptScopedAdmin = (in_array($user->user_lvl, ['ADMIN', 'ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'DEPARTMENT');
        $isBrgyScopedAdmin = (in_array($user->user_lvl, ['ADMIN', 'ENCODER', 'VIEWER']) && ($user->account_type ?? '') === 'BARANGAY');

        // Determine if user belongs to HRDO, PESO, BPLO, Mayor, or CIO department
        $isHRDO = false;
        $isPESO = false;
        $isBPLO = false;
        $isMayor = false;
        $isCIO = false;
        if (!empty($user->entity_ref_id) && ($user->account_type ?? '') === 'DEPARTMENT') {
            $deptModel = new \App\Models\Department();
            $linkedDept = $deptModel->find($user->entity_ref_id);
            if ($linkedDept) {
                if (stripos($linkedDept->dept_name, 'HRDO') !== false || stripos($linkedDept->dept_name, 'Human Resources') !== false) {
                    $isHRDO = true;
                }
                if (stripos($linkedDept->dept_name, 'PESO') !== false || stripos($linkedDept->dept_name, 'Public Employment Service') !== false) {
                    $isPESO = true;
                }
                if (stripos($linkedDept->dept_name, 'BPLO') !== false || stripos($linkedDept->dept_name, 'Business Permit') !== false) {
                    $isBPLO = true;
                }
                if (stripos($linkedDept->dept_name, 'Mayor') !== false) {
                    $isMayor = true;
                }
                if (stripos($linkedDept->dept_name, 'Information Officer') !== false || stripos($linkedDept->dept_name, 'CIO') !== false) {
                    $isCIO = true;
                }
            }
        }
        // Privileged roles (DEVELOPER, SUPERADMIN) are always treated as capable
        $canManageCareers = $isHRDO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
        $canManageJobs = $isPESO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
        $canManageInvest = $isBPLO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
        $canManageMayor = $isMayor || $isCIO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']);

        $canManageAbout = $isCIO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']);
        $canManageFullDisc = $isCIO || in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']);
        $isMayorDeptAdmin = $isDeptScopedAdmin && $isMayor;
        $isSpecialDeptAdmin = $isDeptScopedAdmin && ($isMayor || $isHRDO || $isPESO || $isBPLO || $isCIO);

        // Only DEVELOPER and SUPERADMIN can see archived records in data tables
        $canSeeArchived = in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN'], true);

        $currentUserFullName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        $log_m = new \App\Models\Audit();

        $agent = $this->request->getUserAgent();
        $deviceType = 'Desktop';
        if ($agent->isMobile()) {
            $mobileDevice = $agent->getMobile();
            $deviceType = 'Mobile' . (!empty($mobileDevice) ? ' (' . $mobileDevice . ')' : '');
        } elseif ($agent->isRobot()) {
            $deviceType = 'Bot/Robot';
        }

        $browserName = 'Unknown';
        if ($agent->isBrowser()) {
            $browserName = $agent->getBrowser() . ' ' . $agent->getVersion();
        } elseif ($agent->isRobot()) {
            $browserName = $agent->getRobot();
        } elseif ($agent->isMobile()) {
            $browserName = $agent->getMobile() ?: 'Mobile Browser';
        } else {
            $agentString = $agent->getAgentString();
            if (!empty($agentString)) {
                $browserName = substr($agentString, 0, 50);
            }
        }

        $log_c = [
            'ipaddress' => $this->request->getIPAddress(),
            'action' => $mode,
            'UserID' => $user->ID ?? 0,
            'device' => $deviceType,
            'browser' => $browserName,
        ];

        switch ($mode) {

            /* -------------------
            |
            | GET DETAILS
            |
            ------------------- */

            case 'update_profile': {
                $fname = trim((string) $this->request->getPost('fname'));
                $mname = trim((string) $this->request->getPost('mname'));
                $lname = trim((string) $this->request->getPost('lname'));
                $suffix = trim((string) $this->request->getPost('suffix'));
                
                $email = trim((string) $this->request->getPost('email'));
                $username = trim((string) $this->request->getPost('username'));
                $dept = trim((string) $this->request->getPost('department'));

                if ($fname === '' && $lname === '') {
                    $fullName = trim((string) $this->request->getPost('fullName'));
                    if ($fullName === '' || $email === '' || $username === '') {
                        $message = 'Full name, email, and username are required.';
                        break;
                    }
                    $nameParts = preg_split('/\s+/', $fullName);
                    $lname = count($nameParts) > 1 ? array_pop($nameParts) : '';
                    $fname = trim(implode(' ', $nameParts));
                    if ($fname === '') {
                        $fname = $fullName;
                    }
                    $mname = '';
                    $suffix = '';
                } else {
                    if ($fname === '' || $mname === '' || $lname === '') {
                        $message = 'First name, middle name, and last name are required.';
                        break;
                    }
                }

                if ($email === '' || $username === '') {
                    $message = 'Email and username are required.';
                    break;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Please enter a valid email address.';
                    break;
                }

                $user_m = new \App\Models\UserAccount();
                $existing = $user_m
                    ->groupStart()
                    ->where('username', $username)
                    ->orWhere('email', $email)
                    ->groupEnd()
                    ->where('ID !=', $user->ID)
                    ->first();

                if ($existing) {
                    $message = 'Username or email already exists.';
                    break;
                }

                $updateData = [
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'suffix' => $suffix,
                    'username' => $username,
                    'email' => $email,
                    'dept' => $dept,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                if ($user_m->update($user->ID, $updateData)) {
                    $updatedUser = $user_m->find($user->ID);
                    $this->session->set('user', $updatedUser);
                    
                    $fullNameParts = [];
                    if (!empty($updatedUser->fname)) $fullNameParts[] = $updatedUser->fname;
                    if (!empty($updatedUser->mname)) $fullNameParts[] = $updatedUser->mname;
                    if (!empty($updatedUser->lname)) $fullNameParts[] = $updatedUser->lname;
                    if (!empty($updatedUser->suffix)) $fullNameParts[] = $updatedUser->suffix;
                    $formattedFullName = implode(' ', $fullNameParts);

                    $data = [
                        'fullName' => $formattedFullName,
                        'fname' => $updatedUser->fname ?? '',
                        'mname' => $updatedUser->mname ?? '',
                        'lname' => $updatedUser->lname ?? '',
                        'suffix' => $updatedUser->suffix ?? '',
                        'email' => $updatedUser->email ?? '',
                        'username' => $updatedUser->username ?? '',
                    ];
                    $status = 1;
                    $message = 'Profile updated successfully.';
                    $log_c['processDetails'] = 'PROFILE_ID: ' . $user->ID;
                } else {
                    $message = 'Failed to update profile.';
                }
                break;
            }

            case 'change_profile_password': {
                $oldPassword = (string) $this->request->getPost('oldPassword');
                $newPassword = (string) $this->request->getPost('newPassword');

                if ($oldPassword === '' || $newPassword === '') {
                    $message = 'Old password and new password are required.';
                    break;
                }

                if (strlen($newPassword) < 8) {
                    $message = 'New password must be at least 8 characters.';
                    break;
                }

                $user_m = new \App\Models\UserAccount();
                $currentUser = $user_m->find($user->ID);

                if (!$currentUser || !password_verify($oldPassword, $currentUser->pass)) {
                    $message = 'Old password is incorrect.';
                    break;
                }

                $updateData = [
                    'pass' => password_hash($newPassword, PASSWORD_ARGON2ID),
                    'force_pass_reset' => 0,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                if ($user_m->update($user->ID, $updateData)) {
                    $updatedUser = $user_m->find($user->ID);
                    $this->session->set('user', $updatedUser);
                    $status = 1;
                    $message = 'Password changed successfully.';
                    $log_c['processDetails'] = 'PROFILE_PASSWORD_ID: ' . $user->ID;
                } else {
                    $message = 'Failed to change password.';
                }
                break;
            }

            case 'update_profile_picture': {
                try {
                    $profileImage = $this->request->getFile('profileImage');
                    $user_m = new \App\Models\UserAccount();
                    $currentUser = $user_m->find($user->ID);

                    if (!$currentUser) {
                        $message = 'User not found.';
                        break;
                    }

                    $pictureService = new ProfilePictureService();
                    $uploadResult = $pictureService->store($profileImage, $currentUser->profile_image ?? null);

                    if (!$uploadResult['status']) {
                        $message = $uploadResult['message'];
                        break;
                    }

                    $updateData = [
                        'profile_image' => $uploadResult['filename'],
                        'updated_date' => date('Y-m-d H:i:s'),
                    ];

                    if ($user_m->update($user->ID, $updateData)) {
                        $updatedUser = $user_m->find($user->ID);
                        $this->session->set('user', $updatedUser);
                        $data = [
                            'profileImageUrl' => site_url('admin/image/PROFILE/' . $uploadResult['filename']),
                        ];
                        $status = 1;
                        $message = 'Profile picture updated successfully.';
                        $log_c['processDetails'] = 'PROFILE_IMAGE_ID: ' . $user->ID;
                    } else {
                        $message = 'Failed to update profile picture.';
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Profile picture upload failed: ' . $e->getMessage());
                    $message = str_contains($e->getMessage(), 'profile_image')
                        ? 'Profile picture database column is missing. Please run the database migration.'
                        : 'Unable to save profile picture. Please try again.';
                }
                break;
            }

            case 'update_profile_department': {
                if (($user->account_type ?? '') !== 'DEPARTMENT' || empty($user->entity_ref_id)) {
                    $message = 'No linked department found for this account.';
                    break;
                }

                $dept_m = new \App\Models\Department();
                $department = $dept_m->find($user->entity_ref_id);
                if (!$department) {
                    $message = 'Department not found.';
                    break;
                }

                $deptName = trim((string) $this->request->getPost('deptName'));
                $head = trim((string) $this->request->getPost('head'));
                $deptStatus = trim((string) $this->request->getPost('status'));
                $about = (string) $this->request->getPost('about');
                $contact = (string) $this->request->getPost('contact');
                $mission = (string) $this->request->getPost('mission');
                $vision = (string) $this->request->getPost('vision');
                $qualityPolicy = (string) $this->request->getPost('qualityPolicy');

                if ($deptName === '' || $deptStatus === '') {
                    $message = 'Department name and status are required.';
                    break;
                }

                if (!in_array($deptStatus, ['ACTIVE', 'INACTIVE', 'ARCHIVED'], true)) {
                    $message = 'Invalid department status.';
                    break;
                }

                // Encoders cannot archive a department
                if ($user->user_lvl === 'ENCODER' && $deptStatus === 'ARCHIVED') {
                    $message = 'Encoders are not permitted to archive a department.';
                    break;
                }

                $existingDept = $dept_m
                    ->where('dept_name', $deptName)
                    ->where('ID !=', $department->ID)
                    ->first();
                if ($existingDept) {
                    $message = 'Department name already exists.';
                    break;
                }

                $updateData = [
                    'dept_name' => $deptName,
                    'head' => $head,
                    'about' => $about,
                    'contact' => $contact,
                    'mission' => $mission,
                    'vision' => $vision,
                    'quality_policy' => $qualityPolicy,
                    'status' => $deptStatus,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $uploadPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'DEPT';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $logo = $this->request->getFile('deptLogo');
                if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                    if ($logo->getSize() > (4 * 1024 * 1024)) {
                        $message = 'Department logo must not exceed 4 MB.';
                        break;
                    }
                    if (!in_array($logo->getMimeType(), $allowedTypes, true)) {
                        $message = 'Department logo must be a valid image file.';
                        break;
                    }

                    $logoName = $logo->getRandomName();
                    if ($logo->move($uploadPath, $logoName)) {
                        $updateData['img_logo'] = $logoName;
                    }
                }

                $orgChart = $this->request->getFile('deptOrgChart');
                if ($orgChart && $orgChart->isValid() && !$orgChart->hasMoved()) {
                    if ($orgChart->getSize() > (4 * 1024 * 1024)) {
                        $message = 'Organizational chart must not exceed 4 MB.';
                        break;
                    }
                    if (!in_array($orgChart->getMimeType(), $allowedTypes, true)) {
                        $message = 'Organizational chart must be a valid image file.';
                        break;
                    }

                    $orgChartName = $orgChart->getRandomName();
                    if ($orgChart->move($uploadPath, $orgChartName)) {
                        $updateData['org_chart_img'] = $orgChartName;
                    }
                }

                try {
                    $dept_m->update($department->ID, $updateData);
                    $updatedDepartment = $dept_m->find($department->ID);
                    $data = [
                        'ID' => $updatedDepartment->ID,
                        'dept_name' => $updatedDepartment->dept_name,
                        'head' => $updatedDepartment->head,
                        'status' => $updatedDepartment->status,
                        'about' => $updatedDepartment->about,
                        'contact' => $updatedDepartment->contact,
                        'mission' => $updatedDepartment->mission,
                        'vision' => $updatedDepartment->vision,
                        'quality_policy' => $updatedDepartment->quality_policy,
                        'logoUrl' => !empty($updatedDepartment->img_logo)
                            ? site_url('admin/image/DEPT/' . $updatedDepartment->img_logo)
                            : '',
                        'orgChartUrl' => !empty($updatedDepartment->org_chart_img)
                            ? site_url('admin/image/DEPT/' . $updatedDepartment->org_chart_img)
                            : '',
                    ];
                    $status = 1;
                    $message = 'Department updated successfully.';
                    $log_c['processDetails'] = 'PROFILE_DEPT_ID: ' . $department->ID;
                } catch (\Throwable $e) {
                    log_message('error', 'Profile department update failed: ' . $e->getMessage());
                    $message = 'Unable to update department. Please try again.';
                }
                break;
            }

            case 'set_status_profile_department': {
                if (!in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)) {
                    $message = 'You are not authorized to change the status of this department.';
                    break;
                }

                if (($user->account_type ?? '') !== 'DEPARTMENT' || empty($user->entity_ref_id)) {
                    $message = 'No linked department found for this account.';
                    break;
                }

                $deptStatus = trim((string) $this->request->getPost('status'));
                if (!in_array($deptStatus, ['ACTIVE', 'INACTIVE'], true)) {
                    $message = 'Invalid department status.';
                    break;
                }

                $dept_m = new \App\Models\Department();
                $department = $dept_m->find($user->entity_ref_id);
                if (!$department) {
                    $message = 'Department not found.';
                    break;
                }

                $dept_m->update($department->ID, [
                    'status' => $deptStatus,
                    'updated_date' => date('Y-m-d H:i:s'),
                ]);

                $status = 1;
                $message = 'Department status updated successfully.';
                $data = [
                    'ID' => $department->ID,
                    'status' => $deptStatus,
                ];
                $log_c['processDetails'] = 'PROFILE_DEPT_ID: ' . $department->ID . ' - ' . $deptStatus;
                break;
            }

            case 'delete_profile_department': {
                if (!in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)) {
                    $message = 'You are not authorized to delete this department.';
                    break;
                }

                if (($user->account_type ?? '') !== 'DEPARTMENT' || empty($user->entity_ref_id)) {
                    $message = 'No linked department found for this account.';
                    break;
                }

                $dept_m = new \App\Models\Department();
                $department = $dept_m->find($user->entity_ref_id);
                if (!$department) {
                    $message = 'Department not found.';
                    break;
                }

                if ($dept_m->delete($department->ID)) {
                    $this->userAccount->update($user->ID, [
                        'entity_ref_id' => null,
                        'account_type' => '',
                        'updated_date' => date('Y-m-d H:i:s'),
                    ]);
                    $freshUser = $this->userAccount->find($user->ID);
                    if ($freshUser) {
                        $this->session->set('user', $freshUser);
                    }
                    $status = 1;
                    $message = 'Department deleted successfully.';
                    $log_c['processDetails'] = 'PROFILE_DEPT_ID: ' . $department->ID . ' - DELETED';
                } else {
                    $message = 'Unable to delete department.';
                }
                break;
            }

            case 'get_users': {
                $userId = $this->request->getPost('id');
                $searchUser = $this->request->getPost('searchUser');
                $searchStatus = $this->request->getPost('searchStatus');
                $searchUserLevel = $this->request->getPost('searchUserLevel');

                $user_m = new \App\Models\UserAccount();

                // Fetch details of a specific user if ID is provided
                if ($userId) {
                    $target = $user_m->find($userId);
                    // Non-developers cannot see developer accounts
                    if ($target && $target->user_lvl === 'DEVELOPER' && $user->user_lvl !== 'DEVELOPER') {
                        $message = 'User not found';
                    } elseif ($target) {
                        // Dept-scoped ADMIN can only view users from their own entity
                        if ($isDeptScopedAdmin && ((int) $target->entity_ref_id !== (int) $user->entity_ref_id || $target->account_type !== 'DEPARTMENT')) {
                            $message = 'User not found';
                        } elseif ($isBrgyScopedAdmin && ((int) $target->entity_ref_id !== (int) $user->entity_ref_id || $target->account_type !== 'BARANGAY')) {
                            $message = 'User not found';
                        } else {
                            $data = $target;
                            $status = 1;
                        }
                    } else {
                        $message = 'User not found';
                    }
                } else {
                    $builder = $user_m->orderBy('created_date', 'desc');

                    // Non-developers never see DEVELOPER accounts
                    if ($user->user_lvl !== 'DEVELOPER') {
                        $builder->where('user_lvl !=', 'DEVELOPER');
                    }

                    // Non-privileged users cannot see archived accounts
                    if (!$canSeeArchived) {
                        $builder->where('status !=', 'ARCHIVED');
                    }

                    // Dept-scoped ADMIN: only see users from their own entity
                    if ($isDeptScopedAdmin) {
                        $builder->where('account_type', 'DEPARTMENT')
                            ->where('entity_ref_id', $user->entity_ref_id);
                    } elseif ($isBrgyScopedAdmin) {
                        $builder->where('account_type', 'BARANGAY')
                            ->where('entity_ref_id', $user->entity_ref_id);
                    }

                    if (!empty($searchUser)) {
                        $builder->groupStart()
                            ->like('username', $searchUser)
                            ->orLike('fname', $searchUser)
                            ->orLike('lname', $searchUser)
                            ->groupEnd();
                    }
                    if (!empty($searchStatus)) {
                        $builder->where('status', $searchStatus);
                    }
                    if (!empty($searchUserLevel)) {
                        // Guard: non-developers can't filter by DEVELOPER
                        if ($searchUserLevel !== 'DEVELOPER' || $user->user_lvl === 'DEVELOPER') {
                            $builder->where('user_lvl', $searchUserLevel);
                        }
                    }

                    $users_d = $builder->findAll(200);
                    foreach ($users_d as $u) {
                        if (in_array($u->user_lvl, ['DEVELOPER', 'SUPERADMIN']) || empty($u->account_type)) {
                            $u->account_type = 'System';
                        }
                        $data[] = $u;
                    }
                    $status = 1;
                }
                break;
            }

            case 'get_barangay': {
                $brgyId = $this->request->getPost('id');
                $searchBrgy = $this->request->getPost('searchBrgy');
                $searchCapt = $this->request->getPost('searchCapt');
                $status = $this->request->getPost('status');

                $brgy_m = new \App\Models\Barangay();

                if ($brgyId) {
                    $barangay = $brgy_m->find($brgyId);
                    if ($barangay) {
                        $data = $barangay;
                        $status = 1;
                    } else {
                        $message = 'Barangay not found';
                    }
                } else {
                    $builder = $brgy_m->orderBy('created_date', 'desc');
                    if (($user->user_lvl === 'ENCODER' && $user->account_type === 'BARANGAY') || $isBrgyScopedAdmin) {
                        $builder->where('ID', $user->entity_ref_id);
                    }

                    // Non-privileged users cannot see archived barangays
                    if (!$canSeeArchived) {
                        $builder->where('status !=', 'ARCHIVED');
                    }

                    // Add partial match filters if provided
                    if (!empty($searchBrgy)) {
                        $builder->like('LOWER(brgy_name)', strtolower($searchBrgy));
                    }
                    if (!empty($searchCapt)) {
                        $builder->like('LOWER(brngy_capt)', strtolower($searchCapt));
                    }
                    if (!empty($status)) {
                        $builder->where('status', $status);
                    }

                    $brgy_d = $builder->findAll();

                    foreach ($brgy_d as $brgy) {
                        $data[] = $brgy;
                    }
                    $status = 1;
                }
                break;
            }

            case 'get_dept': {
                $deptId = $this->request->getPost('id');
                $status_filter = $this->request->getPost('status');
                $dept_m = new \App\Models\Department();
                if ($deptId) {
                    $department = $dept_m->find($deptId);
                    if ($department) {
                        $data = (array) $department;
                        $status = 1;
                    } else {
                        $message = 'Department not found';
                    }
                } else {
                    $builder = $dept_m->orderBy('created_date', 'desc');
                    if (($user->user_lvl === 'ENCODER' && $user->account_type === 'DEPARTMENT') || $isDeptScopedAdmin) {
                        $builder->where('ID', $user->entity_ref_id);
                    }

                    // Non-privileged users cannot see archived departments
                    if (!$canSeeArchived) {
                        $builder->where('status !=', 'ARCHIVED');
                    }

                    if (!empty($status_filter)) {
                        $builder->where('status', $status_filter);
                    }

                    $dept_d = $builder->findAll();
                    foreach ($dept_d as $dept) {
                        $data[] = $dept;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_departments': {
                $deptId = $this->request->getPost('id');
                $searchDept = $this->request->getPost('searchDept');
                $searchOfficer = $this->request->getPost('searchOfficer');
                $status = $this->request->getPost('status');

                $dept_m = new \App\Models\Department();

                if ($deptId) {
                    $department = $dept_m->find($deptId);
                    if ($department) {
                        $data = $department;
                        $status = 1;
                    } else {
                        $message = 'Department not found';
                    }
                } else {
                    $builder = $dept_m->orderBy('created_date', 'desc');

                    // If user is ENCODER/dept-scoped ADMIN with DEPARTMENT account type, only show their own department
                    if (($user->user_lvl === 'ENCODER' && $user->account_type === 'DEPARTMENT') || $isDeptScopedAdmin) {
                        $builder->where('ID', $user->entity_ref_id);
                    }

                    // Non-privileged users cannot see archived departments
                    if (!$canSeeArchived) {
                        $builder->where('status !=', 'ARCHIVED');
                    }

                    // Add partial match filters if provided
                    if (!empty($searchDept)) {
                        $builder->like('LOWER(dept_name)', strtolower($searchDept));
                    }
                    if (!empty($searchOfficer)) {
                        $builder->like('LOWER(officer_in_charge)', strtolower($searchOfficer));
                    }
                    if (!empty($status)) {
                        $builder->where('status', $status);
                    }

                    $deptData = $builder->findAll();

                    foreach ($deptData as $dept) {
                        $data[] = $dept;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_cityoff': {
                $coId = $this->request->getPost('id');
                $co_m = new \App\Models\CityOfficial();

                if ($coId) {
                    $official = $co_m->find($coId);
                    if ($official) {
                        $data = $official;
                        $status = 1;
                    } else {
                        $message = 'Official not found';
                    }
                } else {
                    $search_kw      = $this->request->getPost('search_kw');
                    $position_filter = $this->request->getPost('position');
                    $status_filter  = $this->request->getPost('status');

                    $co_builder = $co_m->orderBy('ranking', 'ASC');
                    // Non-privileged users cannot see archived city officials
                    if (!$canSeeArchived) {
                        $co_builder->where('status !=', 'ARCHIVED');
                    }
                    // Keyword filter
                    if (!empty($search_kw)) {
                        $co_builder->groupStart()
                            ->like('off_name', $search_kw)
                            ->orLike('off_position', $search_kw)
                            ->groupEnd();
                    }
                    // Position dropdown filter
                    if (!empty($position_filter)) {
                        $co_builder->where('off_position', $position_filter);
                    }
                    // Status dropdown filter
                    if (!empty($status_filter)) {
                        $co_builder->where('status', $status_filter);
                    }
                    $co_d = $co_builder->findAll();
                    foreach ($co_d as $cityoff) {
                        $data[] = $cityoff;
                    }
                    $status = 1;
                }
                break;
            }

            case 'get_postcontent': {
                $conId = $this->request->getPost('id');
                $con_m = new \App\Models\Content();

                if ($conId) {
                    $news = $con_m->find($conId);
                    if ($news) {
                        // If restricted Mayor or CIO, check ownership
                        $isMayorOrCIORestricted = ($isMayor || $isCIO) && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
                        $deptNames = $this->getDepartmentUserNames($user);
                        if ($isMayorOrCIORestricted && !in_array($news->author, $deptNames)) {
                            $message = 'Unauthorized: You can only view your own created data.';
                        } else {
                            $data = $news;
                            $status = 1;
                        }
                    } else {
                        $message = 'Content not found';
                    }
                } else {
                    // Get filter parameters from the search form
                    $search = $this->request->getPost('search');
                    $category = $this->request->getPost('category');
                    $status_filter = $this->request->getPost('status');

                    $query = $con_m;

                    // Non-privileged users cannot see archived post content
                    if (!$canSeeArchived) {
                        $query = $query->where('status !=', 'ARCHIVED');
                    }

                    // Enforce ownership for restricted Mayor or CIO
                    $isMayorOrCIORestricted = ($isMayor || $isCIO) && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
                    if ($isMayorOrCIORestricted) {
                        $deptNames = $this->getDepartmentUserNames($user);
                        if (!empty($deptNames)) {
                            $query = $query->whereIn('author', $deptNames);
                        } else {
                            $query = $query->where('author', $currentUserFullName);
                        }
                    }

                    if (!empty($search)) {
                        $query = $query->groupStart()
                            ->like('title', $search)
                            ->orLike('author', $search)
                            ->orWhere('YEAR(created_date)', $search)
                            ->groupEnd();
                    }
                    if (!empty($category)) {
                        $query = $query->where('category', $category);
                    }
                    if (!empty($status_filter)) {
                        $query = $query->where('status', $status_filter);
                    }

                    $con_d = $query->orderBy('created_date', 'desc')->findAll();
                    foreach ($con_d as $pol) {
                        $data[] = $pol;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_audit': {
                $log_m = new \App\Models\Audit();
                $user_m = new \App\Models\UserAccount();

                // Build query with user information using correct table names
                $query = $log_m->select('audit_trails.*, useradmin.fname, useradmin.lname, useradmin.username')
                    ->join('useradmin', 'useradmin.ID = audit_trails.userID', 'left')
                    ->orderBy('audit_trails.created_date', 'desc');

                $searchAction = $this->request->getPost('searchAction');
                $searchDate = $this->request->getPost('searchDate');
                $isSearching = false;

                if (!empty($searchAction)) {
                    $query->groupStart()
                        ->like('audit_trails.action', $searchAction)
                        ->orLike('audit_trails.processDetails', $searchAction)
                        ->orLike('audit_trails.device', $searchAction)
                        ->orLike('audit_trails.browser', $searchAction)
                        ->groupEnd();
                    $isSearching = true;
                }

                if (!empty($searchDate)) {
                    $query->where('DATE(audit_trails.created_date)', $searchDate);
                    $isSearching = true;
                }

                if (!$isSearching) {
                    $query->limit(200);
                }

                $log_d = $query->find();
                $data = [];
                foreach ($log_d as $logs) {
                    // Create user display name
                    $userName = 'System';
                    if ($logs->userID && !empty($logs->username)) {
                        $userName = $logs->username;
                    } elseif ($logs->userID && $logs->fname && $logs->lname) {
                        $userName = $logs->fname . ' ' . $logs->lname;
                    } elseif ($logs->userID) {
                        $userName = 'User ID: ' . $logs->userID;
                    }

                    // Dynamically resolve target entity name from database if ID is present
                    $resolvedDetails = $logs->processDetails;
                    try {
                    if (!empty($resolvedDetails) && preg_match('/(ACCOUNT|PROFILE|PROFILE_PASSWORD|PROFILE_IMAGE|BRGY|DEPT|PROFILE_DEPT|JOB|NEWS|ANNOUNCEMENT|ANNNOUNCEMENT|CITYOFFICIAL|SERVICE|CONTACT|HOTLINE|ABOUT|MAYOR|POSTCONTENT|INVEST|FULLDISC)_ID:\s*(\d+)/i', $resolvedDetails, $matches)) {
                        $prefix = strtoupper($matches[1]);
                        $targetId = (int) $matches[2];
                        $name = '';
                        $suffix = '';

                        $db = \Config\Database::connect();
                        if (in_array($prefix, ['ACCOUNT', 'PROFILE', 'PROFILE_PASSWORD', 'PROFILE_IMAGE'], true)) {
                            $userRow = $db->table('useradmin')->select('fname, lname, username, user_lvl')->where('ID', $targetId)->get()->getRow();
                            if ($userRow) {
                                $fullName = trim(($userRow->fname ?? '') . ' ' . ($userRow->lname ?? ''));
                                $name = !empty($fullName) ? $fullName : ($userRow->username ?? '');
                                $suffix = !empty($userRow->user_lvl) ? ' [' . $userRow->user_lvl . ']' : '';
                            }
                        } elseif ($prefix === 'DEPT' || $prefix === 'PROFILE_DEPT') {
                            $deptRow = $db->table('department_content')->select('dept_name')->where('ID', $targetId)->get()->getRow();
                            if ($deptRow) {
                                $name = $deptRow->dept_name;
                            }
                        } elseif ($prefix === 'BRGY') {
                            $brgyRow = $db->table('barangay_content')->select('brgy_name')->where('ID', $targetId)->get()->getRow();
                            if ($brgyRow) {
                                $name = $brgyRow->brgy_name;
                            }
                        } elseif ($prefix === 'JOB') {
                            $jobRow = $db->table('jobs')->select('title')->where('ID', $targetId)->get()->getRow();
                            if ($jobRow) {
                                $name = $jobRow->title;
                            }
                        } elseif (in_array($prefix, ['NEWS', 'ANNOUNCEMENT', 'ANNNOUNCEMENT', 'POSTCONTENT'], true)) {
                            $contentRow = $db->table('content_tbl')->select('title')->where('ID', $targetId)->get()->getRow();
                            if ($contentRow) {
                                $name = $contentRow->title;
                            }
                        } elseif ($prefix === 'ABOUT') {
                            $aboutRow = $db->table('about_content')->select('title')->where('ID', $targetId)->get()->getRow();
                            if ($aboutRow) {
                                $name = $aboutRow->title;
                            }
                        } elseif ($prefix === 'MAYOR') {
                            $mayorRow = $db->table('mayor_content')->select('section')->where('ID', $targetId)->get()->getRow();
                            if ($mayorRow) {
                                $name = $mayorRow->section;
                            }
                        } elseif ($prefix === 'CITYOFFICIAL') {
                            $officialRow = $db->table('officials_content')->select('off_name, off_position')->where('ID', $targetId)->get()->getRow();
                            if ($officialRow) {
                                $name = $officialRow->off_name ?? ($officialRow->off_position ?? '');
                            }
                        } elseif ($prefix === 'SERVICE') {
                            $serviceRow = $db->table('service_content')->select('serv_name')->where('ID', $targetId)->get()->getRow();
                            if ($serviceRow) {
                                $name = $serviceRow->serv_name;
                            }
                        } elseif ($prefix === 'INVEST') {
                            $investRow = $db->table('file_tbl')->select('file_category')->where('ID', $targetId)->get()->getRow();
                            if ($investRow) {
                                $name = $investRow->file_category;
                            }
                        } elseif ($prefix === 'FULLDISC') {
                            $fdRow = $db->table('file_tbl')->select('file_category, year, quarter')->where('ID', $targetId)->get()->getRow();
                            if ($fdRow) {
                                $category = $fdRow->file_category;
                                $yearVal = $fdRow->year;
                                $qtrVal = $fdRow->quarter;
                                
                                $hasCategory = stripos($resolvedDetails, $category) !== false;
                                $hasYear = stripos($resolvedDetails, $yearVal) !== false;
                                $hasQtr = stripos($resolvedDetails, $qtrVal) !== false;
                                
                                if (!$hasCategory && !$hasYear && !$hasQtr) {
                                    $name = $category . ' ' . $yearVal . ' - ' . $qtrVal;
                                } elseif (!$hasCategory) {
                                    $name = $category;
                                }
                            }
                        } elseif (in_array($prefix, ['CONTACT', 'HOTLINE'], true)) {
                            $hotlineRow = $db->table('hotlines')->select('section, content_ref_id, number, telco')->where('ID', $targetId)->get()->getRow();
                            if ($hotlineRow) {
                                $section = $hotlineRow->section ?? '';
                                $refId   = $hotlineRow->content_ref_id ?? null;
                                $entityName = '';
                                if ($section === 'Department' && $refId) {
                                    $dRow = $db->table('department_content')->select('dept_name')->where('ID', (int)$refId)->get()->getRow();
                                    if ($dRow) $entityName = $dRow->dept_name;
                                } elseif ($section === 'Barangay' && $refId) {
                                    $bRow = $db->table('barangay_content')->select('brgy_name')->where('ID', (int)$refId)->get()->getRow();
                                    if ($bRow) $entityName = $bRow->brgy_name;
                                } elseif ($section === 'Others' && $refId) {
                                    // For 'Others', content_ref_id stores the name string directly
                                    $entityName = $refId;
                                }
                                if (!empty($entityName)) {
                                    $name = $entityName . ' (' . $section . ')';
                                } elseif (!empty($section)) {
                                    $num = $hotlineRow->number ?? ($hotlineRow->telco ?? '');
                                    $name = ($num ? $num . ' - ' : '') . $section;
                                } else {
                                    $num = $hotlineRow->number ?? ($hotlineRow->telco ?? '');
                                    $name = $num ?: '';
                                }
                            }
                        }

                        if (!empty($name)) {
                            $replacement = $matches[0];
                            if (in_array($prefix, ['ACCOUNT', 'PROFILE', 'PROFILE_PASSWORD', 'PROFILE_IMAGE'], true)) {
                                if (stripos($resolvedDetails, $name) === false) {
                                    $replacement = $matches[0] . ' ' . $name . $suffix;
                                }
                            } elseif (in_array($prefix, ['JOB', 'NEWS', 'ANNOUNCEMENT', 'ANNNOUNCEMENT', 'ABOUT', 'POSTCONTENT'], true)) {
                                if (stripos($resolvedDetails, 'TITLE:') === false) {
                                    $replacement = $matches[0] . ' TITLE: ' . $name;
                                }
                            } elseif ($prefix === 'MAYOR') {
                                if (stripos($resolvedDetails, 'SECTION:') === false) {
                                    $replacement = $matches[0] . ' SECTION: ' . $name;
                                }
                            } else {
                                if (stripos($resolvedDetails, $name) === false) {
                                    $replacement = $matches[0] . ' ' . $name;
                                }
                            }
                            if ($replacement !== $matches[0]) {
                                $resolvedDetails = preg_replace('/' . preg_quote($matches[0], '/') . '/', $replacement, $resolvedDetails, 1);
                            }
                        }
                    }
                    } catch (\Throwable $e) {
                        log_message('error', 'Audit resolver error: ' . $e->getMessage());
                    }

                    // Create the data object with user name
                    $data[] = [
                        'ID' => $logs->ID,
                        'created_date' => $logs->created_date,
                        'ipaddress' => $logs->ipaddress,
                        'action' => $logs->action,
                        'processDetails' => $resolvedDetails,
                        'device' => $logs->device ?? 'Unknown',
                        'browser' => $logs->browser ?? 'Unknown',
                        'userID' => $userName // Replace userID with user name
                    ];
                }
                $status = 1;
                break;
            }

            case 'get_mayor': {
                $mayId = $this->request->getPost('id');
                $may_m = new \App\Models\MayorContent();

                if ($mayId) {
                    $maycontent = $may_m->find($mayId);
                    if ($maycontent) {
                        $data = $maycontent;
                        $status = 1;
                    } else {
                        $message = 'Content not found';
                    }
                } else {
                    $search_kw       = $this->request->getPost('search_kw');
                    $category_filter = $this->request->getPost('category');
                    $status_filter   = $this->request->getPost('status');

                    $may_builder = $may_m->orderBy('created_date', 'desc');
                    // Non-privileged users cannot see archived mayor content
                    if (!$canSeeArchived) {
                        $may_builder->where('status !=', 'ARCHIVED');
                    }
                    // Keyword filter (section / mayor_name)
                    if (!empty($search_kw)) {
                        $may_builder->groupStart()
                            ->like('section', $search_kw)
                            ->orLike('mayor_name', $search_kw)
                            ->groupEnd();
                    }
                    // Category / Section dropdown filter
                    if (!empty($category_filter)) {
                        $may_builder->where('section', $category_filter);
                    }
                    // Status dropdown filter
                    if (!empty($status_filter)) {
                        $may_builder->where('status', $status_filter);
                    }
                    $may_d = $may_builder->findAll();
                    foreach ($may_d as $mayorc) {
                        $data[] = $mayorc;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_fulldiscpol': {
                $policyId = $this->request->getPost('id');
                $policy_m = new \App\Models\FileTbl();
                $data = []; // Explicitly initialize

                if ($policyId) {
                    $policies = $policy_m->find($policyId);
                    if ($policies) {
                        $data = $policies;
                        $status = 1;
                    } else {
                        $message = 'Policy not found';
                    }
                } else {
                    // Get filter parameters from the search form
                    $search = $this->request->getPost('search');
                    if (is_array($search)) {
                        $search = $search['value'] ?? '';
                    }
                    $frequency = $this->request->getPost('frequency');
                    $file_category = $this->request->getPost('file_category');
                    $status_filter = $this->request->getPost('status');

                    // Build the query with filters
                    $query = $policy_m->where('category', 'FULLDISC');

                    // Non-privileged users cannot see archived full disclosure policies
                    if (!$canSeeArchived) {
                        $query = $query->where('status !=', 'ARCHIVED');
                    }

                    // Apply combined search filter: category text and year
                    if (!empty($search)) {
                        $query = $query->groupStart()
                            ->like('file_category', $search)
                            ->orWhere('year', $search)
                            ->groupEnd();
                    }

                    // Apply frequency filter
                    if (!empty($frequency)) {
                        if ($frequency === 'ANNUAL') {
                            // Annual reports
                            $annual_categories = [
                                'Annual Budget Report',
                                'Annual Procurement Plan or Procurement List',
                                'Supplemental Procurement Plan',
                                'Annual Gender and Development Accomplishment Report'
                            ];
                            $query = $query->whereIn('file_category', $annual_categories);
                        } elseif ($frequency === 'QUARTERLY') {
                            // Quarterly reports
                            $quarterly_categories = [
                                'Quarterly Statement of Cash Flow',
                                'Statement of Receipts and Expenditures',
                                '20% Component of the Internal Revenue Allotment Utilization',
                                'Local Disaster Risk Reduction and Management Fund Utilization',
                                'Report of Special Education Fund Utilization',
                                'Trust Fund (PDAF) Utilization',
                                'Unliquidated Cash Advances',
                                'Bid Results on Civil Works and Goods and Services',
                                'Manpower Complement',
                                'Annual Statement of Indebtedness, Payments and Balances'
                            ];
                            $query = $query->whereIn('file_category', $quarterly_categories);
                        }
                    }

                    // Apply filters if they are provided and not empty
                    if (!empty($file_category) && $file_category !== '- File Category -') {
                        $query = $query->where('file_category', $file_category);
                    }

                    if (!empty($status_filter) && $status_filter !== '- Status -') {
                        $query = $query->where('status', $status_filter);
                    }

                    $policy_d = $query->orderBy('created_date', 'desc')->findAll();
                    foreach ($policy_d as $pol) {
                        $data[] = $pol;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_career': {
                $careerId = $this->request->getPost('id');
                $career_m = new \App\Models\FileTbl();

                if ($careerId) {
                    $c = $career_m->find($careerId);
                    if ($c) {
                        $data = $c;
                        $status = 1;
                    } else {
                        $message = 'Career not found';
                    }
                } else {
                    $search_kw    = $this->request->getPost('search_kw');
                    $level_filter = $this->request->getPost('level');
                    $status_filter = $this->request->getPost('status');

                    $career_builder = $career_m
                        ->where('category', 'CAREER')
                        ->orderBy('created_date', 'desc');
                    // Non-privileged users cannot see archived career postings
                    if (!$canSeeArchived) {
                        $career_builder->where('status !=', 'ARCHIVED');
                    }
                    // Date filter (supports YYYY, YYYY-MM, or YYYY-MM-DD)
                    if (!empty($search_kw)) {
                        $search_kw = trim((string) $search_kw);

                        if (preg_match('/^\d{4}$/', $search_kw)) {
                            $career_builder->where('YEAR(publication_date)', $search_kw);
                        } elseif (preg_match('/^\d{4}-\d{2}$/', $search_kw)) {
                            [$year, $month] = explode('-', $search_kw, 2);
                            $career_builder->where('YEAR(publication_date)', $year)
                                ->where('MONTH(publication_date)', $month);
                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search_kw)) {
                            $career_builder->where('DATE(publication_date)', $search_kw);
                        } else {
                            $career_builder->like('publication_date', $search_kw);
                        }
                    }
                    // Level dropdown filter
                    if ($level_filter !== null && $level_filter !== '') {
                        $career_builder->where('level', $level_filter);
                    }
                    // Status dropdown filter
                    if (!empty($status_filter)) {
                        $career_builder->where('status', $status_filter);
                    }
                    $career_d = $career_builder->findAll();
                    foreach ($career_d as $car) {
                        $data[] = $car;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_invest': {
                $investId = $this->request->getPost('id');
                $invest_m = new \App\Models\FileTbl();

                if ($investId) {
                    $inv_d = $invest_m->find($investId);
                    if ($inv_d) {
                        $data = $inv_d;
                        $status = 1;
                    } else {
                        $message = 'File not found';
                    }
                } else {
                    $search_kw       = $this->request->getPost('search_kw');
                    $category_filter = $this->request->getPost('category');
                    $status_filter   = $this->request->getPost('status');

                    $invest_builder = $invest_m
                        ->where('category', 'INVEST')
                        ->orderBy('created_date', 'desc');
                    // Non-privileged users cannot see archived invest content
                    if (!$canSeeArchived) {
                        $invest_builder->where('status !=', 'ARCHIVED');
                    }
                    // Keyword filter (file_category / file_name)
                    if (!empty($search_kw)) {
                        $invest_builder->groupStart()
                            ->like('file_category', $search_kw)
                            ->orLike('file_name', $search_kw)
                            ->groupEnd();
                    }
                    // Category dropdown filter
                    if (!empty($category_filter)) {
                        $invest_builder->where('file_category', $category_filter);
                    }
                    // Status dropdown filter
                    if (!empty($status_filter)) {
                        $invest_builder->where('status', $status_filter);
                    }
                    $inv_d = $invest_builder->findAll();
                    foreach ($inv_d as $inv) {
                        $data[] = $inv;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_services': {
                $servId = $this->request->getPost('id');
                $serv_m = new \App\Models\Services();
                $decorateServiceRow = function ($row) {
                    if (!$row) {
                        return $row;
                    }

                    $scope = 'Unknown';
                    $entityName = '';

                    if (!empty($row->brngy_cont_ID)) {
                        $scope = 'Barangay';
                        $entityName = $row->brgy_name ?? '';
                    } elseif (!empty($row->dept_cont_ID)) {
                        $scope = 'Department';
                        $entityName = $row->dept_name ?? '';
                    }

                    $row->service_scope = $scope;
                    $row->service_entity_name = $entityName;

                    return $row;
                };

                if ($servId) {
                    // Existing single service lookup
                    $serv_d = $serv_m
                        ->select('service_content.*, barangay_content.brgy_name, department_content.dept_name')
                        ->join('barangay_content', 'barangay_content.ID = service_content.brngy_cont_ID', 'left')
                        ->join('department_content', 'department_content.ID = service_content.dept_cont_ID', 'left')
                        ->where('service_content.ID', $servId)
                        ->first();

                    if ($serv_d) {
                        if (!$this->canAccessServiceRecord($serv_d, $user)) {
                            $message = 'Service not found';
                        } else {
                            $data = $decorateServiceRow($serv_d);
                            $status = 1;
                        }
                    } else {
                        $message = 'Service not found';
                    }
                } else {
                    // Build the filtered query
                    $builder = $serv_m
                        ->select('service_content.*, barangay_content.brgy_name, department_content.dept_name')
                        ->join('barangay_content', 'barangay_content.ID = service_content.brngy_cont_ID', 'left')
                        ->join('department_content', 'department_content.ID = service_content.dept_cont_ID', 'left');

                    // Scoped ADMIN: restrict to their own department's services
                    if ($isDeptScopedAdmin) {
                        $builder->where('service_content.dept_cont_ID', $user->entity_ref_id);
                    } elseif ($isBrgyScopedAdmin) {
                        $builder->where('service_content.brngy_cont_ID', $user->entity_ref_id);
                    }

                    // Non-privileged users cannot see archived services
                    if (!$canSeeArchived) {
                        $builder->where('service_content.status !=', 'ARCHIVED');
                    }

                    // Service Name filter
                    if ($this->request->getPost('service_name')) {
                        $builder->like('service_content.serv_name', $this->request->getPost('service_name'));
                    }

                    // Category filter (Barangay/Department)
                    if ($this->request->getPost('category')) {
                        $category = $this->request->getPost('category');

                        if ($category === 'BARANGAY') {
                            if ($this->request->getPost('brgy')) {
                                $builder->where('service_content.brngy_cont_ID', $this->request->getPost('brgy'));
                            } else {
                                $builder->where('service_content.brngy_cont_ID IS NOT NULL');
                            }
                        } elseif ($category === 'DEPARTMENT') {
                            if ($this->request->getPost('dept')) {
                                $builder->where('service_content.dept_cont_ID', $this->request->getPost('dept'));
                            } else {
                                $builder->where('service_content.dept_cont_ID IS NOT NULL');
                            }
                        }
                    }

                    // Status filter
                    if ($this->request->getPost('status')) {
                        $builder->where('service_content.status', $this->request->getPost('status'));
                    }

                    // Order by creation date
                    $builder->orderBy('service_content.created_date', 'desc');

                    // Get results
                    $results = $builder->findAll();

                    $data = [];
                    foreach ($results as $row) {
                        $data[] = $decorateServiceRow($row);
                    }
                    $status = 1;
                }

                // Return response
                return $this->response->setJSON([
                    'status' => $status ?? 0,
                    'message' => $message ?? '',
                    'data' => $data ?? []
                ]);
                break;
            }
            case 'get_contact': {
                $hotlineId = $this->request->getPost('id');
                $hot_m = new \App\Models\Hotlines();

                if ($hotlineId) {
                    $hotline_d = $hot_m
                        ->select('hotlines.*, barangay_content.brgy_name, department_content.dept_name')
                        ->join('barangay_content', 'barangay_content.ID = hotlines.content_ref_id', 'left')
                        ->join('department_content', 'department_content.ID = hotlines.content_ref_id', 'left')
                        ->where('hotlines.ID', $hotlineId)
                        ->first();

                    if ($hotline_d) {
                        $data = $hotline_d;
                        $status = 1;
                    } else {
                        $message = 'Hotline not found';
                    }

                } else {
                    $query_param = $this->request->getPost('query');
                    $category_param = $this->request->getPost('category');
                    $status_param = $this->request->getPost('status');

                    $builder = $hot_m
                        ->select('hotlines.*, barangay_content.brgy_name, department_content.dept_name')
                        ->join('barangay_content', 'barangay_content.ID = hotlines.content_ref_id', 'left')
                        ->join('department_content', 'department_content.ID = hotlines.content_ref_id', 'left');

                    // Scoped ADMIN: restrict to their own department/barangay contacts (CIO has global access)
                    if ($isDeptScopedAdmin && !$isCIO) {
                        $builder->where('hotlines.section', 'Department')
                            ->where('hotlines.content_ref_id', $user->entity_ref_id);
                    } elseif ($isBrgyScopedAdmin) {
                        $builder->where('hotlines.section', 'Barangay')
                            ->where('hotlines.content_ref_id', $user->entity_ref_id);
                    }

                    // Non-privileged users cannot see archived contacts
                    if (!$canSeeArchived) {
                        $builder->where('hotlines.status !=', 'ARCHIVED');
                    }

                    if (!empty($query_param)) {
                        $builder->groupStart()
                            ->like('barangay_content.brgy_name', $query_param)
                            ->orLike('department_content.dept_name', $query_param)
                            ->orLike('hotlines.content_ref_id', $query_param)
                            ->orLike('hotlines.number', $query_param)
                            ->groupEnd();
                    }

                    if (!empty($category_param)) {
                        $section_map = ['BRGY' => 'Barangay', 'DEPT' => 'Department', 'Others' => 'Others'];
                        $section = $section_map[$category_param] ?? $category_param;
                        $builder->where('hotlines.section', $section);
                    }

                    if (!empty($status_param)) {
                        $builder->where('hotlines.status', $status_param);
                    }

                    $hotline_d = $builder->orderBy('hotlines.created_date', 'desc')->findAll();

                    foreach ($hotline_d as $hotl) {
                        $data[] = $hotl;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_career': {
                $careerId = $this->request->getPost('id');
                $career_m = new \App\Models\FileTbl();

                if ($careerId) {
                    $c = $career_m->find($careerId);
                    if ($c) {
                        $data = $c;
                        $status = 1;
                    } else {
                        $message = 'Career not found';
                    }
                } else {
                    $career_builder2 = $career_m
                        ->where('category', 'CAREER')
                        ->orderBy('created_date', 'desc');
                    // Non-privileged users cannot see archived career postings
                    if (!$canSeeArchived) {
                        $career_builder2->where('status !=', 'ARCHIVED');
                    }
                    $career_d = $career_builder2->findAll();
                    foreach ($career_d as $car) {
                        $data[] = $car;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_about': {
                $aboutId = $this->request->getPost('id');
                $about_m = new \App\Models\About();

                if ($aboutId) {
                    $abt_d = $about_m->find($aboutId);
                    if ($abt_d) {
                        $data = $abt_d;
                        $status = 1;
                    } else {
                        $message = 'File not found';
                    }
                } else {
                    $search_kw      = $this->request->getPost('search_kw');
                    $section_filter = $this->request->getPost('section');
                    $status_filter  = $this->request->getPost('status');

                    $about_builder = $about_m->orderBy('created_date', 'desc');
                    // Non-privileged users cannot see archived about content
                    if (!$canSeeArchived) {
                        $about_builder->where('status !=', 'ARCHIVED');
                    }
                    // Keyword filter (title / description)
                    if (!empty($search_kw)) {
                        $about_builder->groupStart()
                            ->like('title', $search_kw)
                            ->orLike('description', $search_kw)
                            ->groupEnd();
                    }
                    // Section dropdown filter
                    if (!empty($section_filter)) {
                        $about_builder->where('section', $section_filter);
                    }
                    // Status dropdown filter
                    if (!empty($status_filter)) {
                        $about_builder->where('status', $status_filter);
                    }
                    $abt_d = $about_builder->findAll();
                    foreach ($abt_d as $inv) {
                        $data[] = $inv;
                    }
                    $status = 1;
                }
                break;
            }
            case 'get_jobs': {
                $job_m = new \App\Models\Job();

                $search_kw    = $this->request->getPost('search_kw');
                $type_filter  = $this->request->getPost('type');
                $status_filter = $this->request->getPost('status');

                $jobs_builder = $job_m->orderBy('created_date', 'desc');
                // Non-privileged users cannot see archived jobs
                if (!$canSeeArchived) {
                    $jobs_builder->where('status !=', 'ARCHIVED');
                }
                // Keyword filter (title / company / email)
                if (!empty($search_kw)) {
                    $jobs_builder->groupStart()
                        ->like('title', $search_kw)
                        ->orLike('company', $search_kw)
                        ->orLike('email', $search_kw)
                        ->groupEnd();
                }
                // Job type dropdown filter
                if (!empty($type_filter)) {
                    $jobs_builder->where('type', $type_filter);
                }
                // Status dropdown filter
                if (!empty($status_filter)) {
                    $jobs_builder->where('status', $status_filter);
                }
                $jobs = $jobs_builder->findAll();
                // Ensure each job has an 'ID' field (uppercase)
                $jobs = array_map(function ($job) {
                    if (isset($job['ID']))
                        return $job;
                    if (isset($job['id'])) {
                        $job['ID'] = $job['id'];
                        unset($job['id']);
                    }
                    return $job;
                }, $jobs);
                $data = $jobs;
                $status = 1;
                break;
            }



            case 'get_job': {
                $job_m = new \App\Models\Job();
                $id = $this->request->getPost('id');

                if (!$id || !is_numeric($id)) {
                    $message = 'Invalid job ID';
                    break;
                }

                $job = $job_m->where('ID', $id)->first();
                if ($job) {
                    $data = $job;
                    $status = 1;
                } else {
                    $message = 'Job not found';
                }
                break;
            }
            case 'create_job': {
                if (!$canManageJobs) {
                    $message = 'Unauthorized: Only the PESO department can manage jobs.';
                    break;
                }
                $job_m = new \App\Models\Job();
                $title = trim($this->request->getPost('title'));
                $description = trim($this->request->getPost('description'));
                $company = trim($this->request->getPost('company'));
                $type = trim($this->request->getPost('type'));
                $publication_date = $this->request->getPost('publication_date');
                $email = trim($this->request->getPost('email'));

                // Debug: Log the received data
                log_message('debug', 'Create Job - Received data: ' . json_encode([
                    'title' => $title,
                    'description' => $description,
                    'company' => $company,
                    'type' => $type,
                    'publication_date' => $publication_date,
                    'email' => $email
                ]));

                // Basic validation
                if (empty($title) || empty($description) || empty($company) || empty($type) || empty($publication_date) || empty($email)) {
                    $message = 'All fields are required';
                    break;
                }

                // Validate job type
                if (!in_array($type, ['Full Time', 'Part Time'])) {
                    $message = 'Please select a valid job type';
                    break;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Please enter a valid email address';
                    break;
                }

                // Check if publication date is not in the future
                if (strtotime($publication_date) > time()) {
                    $message = 'Publication date cannot be in the future';
                    break;
                }

                $jobData = [
                    'title' => $title,
                    'description' => $description,
                    'company' => $company,
                    'type' => $type,
                    'publication_date' => $publication_date,
                    'email' => $email,
                    'status' => 'ACTIVE'
                ];

                // Debug: Log the data being inserted
                log_message('debug', 'Create Job - Data to insert: ' . json_encode($jobData));

                // Try to create the job with better error handling
                try {
                    // Try direct database insert first
                    $db = \Config\Database::connect();
                    $result = $db->table('jobs')->insert($jobData);

                    if ($result) {
                        $insertedId = $db->insertID();
                        $status = 1;
                        $message = 'Job created successfully';
                        $log_c['processDetails'] = 'JOB_ID: ' . $insertedId . ' TITLE: ' . $title;
                        log_message('debug', 'Create Job - Success with direct DB insert');
                    } else {
                        // Get validation errors if any
                        $errors = $job_m->errors();
                        if (!empty($errors)) {
                            $message = 'Validation errors: ' . implode(', ', $errors);
                            log_message('error', 'Create Job - Validation errors: ' . json_encode($errors));
                        } else {
                            $message = 'Failed to create job. Please check your input and try again.';
                            log_message('error', 'Create Job - Insert failed without validation errors');
                        }
                    }
                } catch (\Exception $e) {
                    $message = 'Database error: ' . $e->getMessage();
                    log_message('error', 'Create Job - Exception: ' . $e->getMessage());
                }
                break;
            }
            case 'update_job': {
                if (!$canManageJobs) {
                    $message = 'Unauthorized: Only the PESO department can manage jobs.';
                    break;
                }
                $job_m = new \App\Models\Job();
                $id = $this->request->getPost('id');
                $title = trim($this->request->getPost('title'));
                $description = trim($this->request->getPost('description'));
                $company = trim($this->request->getPost('company'));
                $type = trim($this->request->getPost('type'));
                $publication_date = $this->request->getPost('publication_date');
                $email = trim($this->request->getPost('email'));
                $statusVal = $this->request->getPost('status');

                if (!$id || !is_numeric($id)) {
                    $message = 'Invalid job ID';
                    break;
                }

                // Check if job exists
                $job = $job_m->where('ID', $id)->first();
                if (!$job) {
                    $message = 'Job not found';
                    break;
                }

                // Basic validation
                if (empty($title) || empty($description) || empty($company) || empty($publication_date) || empty($email)) {
                    $message = 'All fields are required';
                    break;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Please enter a valid email address';
                    break;
                }

                // Check if publication date is not in the future
                if (strtotime($publication_date) > time()) {
                    $message = 'Publication date cannot be in the future';
                    break;
                }

                $jobData = [
                    'title' => $title,
                    'description' => $description,
                    'company' => $company,
                    'type' => $type,
                    'publication_date' => $publication_date,
                    'email' => $email,
                    'updated_date' => date('Y-m-d H:i:s')
                ];

                if ($statusVal !== null) {
                    $jobData['status'] = $statusVal;
                }

                // Try to update the job with better error handling
                try {
                    if ($job_m->update($id, $jobData)) {
                        $status = 1;
                        $message = 'Job updated successfully';
                        $log_c['processDetails'] = 'JOB_ID: ' . $id . ' TITLE: ' . $title;
                    } else {
                        // Get validation errors if any
                        $errors = $job_m->errors();
                        if (!empty($errors)) {
                            $message = 'Validation errors: ' . implode(', ', $errors);
                        } else {
                            $message = 'Failed to update job. Please check your input and try again.';
                        }
                    }
                } catch (\Exception $e) {
                    $message = 'Database error: ' . $e->getMessage();
                }
                break;
            }

            /* -------------------
            |
            | CREATE
            |
            ------------------- */

            case 'create_user': {
                $user_m = new \App\Models\UserAccount();
                $fname = $this->request->getPost('txtFirstName');
                $mname = $this->request->getPost('txtMiddleName') ?: '';
                $lname = $this->request->getPost('txtLastName');
                $suffix = $this->request->getPost('txtSuffix') ?: '';
                $usern = $this->request->getPost('txtUsername');
                $email = $this->request->getPost('txtEmail');
                $passw = $this->request->getPost('txtPassword');
                $acclvl = $this->request->getPost('txtAccLevel');
                $dept = $this->request->getPost('txtDept') ?: '';
                // #25 – account type & linked entity
                $acct_type = $this->request->getPost('txtAccountType') ?: null;
                $entity_ref = $this->request->getPost('txtEntityRef') ?: null;

                if (empty($fname) || empty($mname) || empty($lname) || empty($usern) || empty($email) || empty($passw) || empty($acclvl)) {
                    $message = 'Please fill in all required fields (First, Middle, and Last Names are required).';
                    break;
                }

                // Nobody can create a DEVELOPER account
                if ($acclvl === 'DEVELOPER') {
                    $message = 'Creating additional Developer accounts is not allowed.';
                    break;
                }

                // Only DEVELOPER can create SUPERADMIN accounts
                if ($acclvl === 'SUPERADMIN' && $user->user_lvl !== 'DEVELOPER') {
                    $message = 'Only Developers can create Super Admin accounts.';
                    break;
                }

                // ADMIN can only create ENCODER or VIEWER accounts
                if ($user->user_lvl === 'ADMIN' && !in_array($acclvl, ['ENCODER', 'VIEWER'])) {
                    $message = 'Admin accounts can only create Encoder or Viewer accounts.';
                    break;
                }

                // Validate account type — DEPT/BRGY need a linked entity
                $valid_types = ['DEPARTMENT', 'BARANGAY'];
                if (!in_array($acct_type, $valid_types)) {
                    $acct_type = '';
                }
                // For high-privilege roles the account_type should be empty string
                if (in_array($acclvl, ['SUPERADMIN'])) {
                    $acct_type = '';
                    $entity_ref = null;
                }
                // ADMIN: auto-assign their own entity_ref_id; they cannot set a different one
                if ($user->user_lvl === 'ADMIN') {
                    $acct_type = $user->account_type ?? '';
                    $entity_ref = $user->entity_ref_id ?? null;
                }
                // DEPARTMENT / BARANGAY accounts MUST have an entity
                if (in_array($acct_type, ['DEPARTMENT', 'BARANGAY']) && empty($entity_ref)) {
                    $message = 'A Department or Barangay account must be linked to an entity.';
                    break;
                }

                $linkedEntityName = $this->resolveAccountEntityName($acct_type, $entity_ref);
                if ($linkedEntityName === null) {
                    $message = $acct_type === 'DEPARTMENT'
                        ? 'Selected department was not found.'
                        : 'Selected barangay was not found.';
                    break;
                }
                if ($linkedEntityName !== '') {
                    $dept = $linkedEntityName;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Invalid email address.';
                } elseif ($user_m->where('username', $usern)->orWhere('email', $email)->countAllResults() > 0) {
                    $message = 'Username or email already exists.';
                } else {
                    $userData = [
                        'fname' => $fname,
                        'mname' => $mname,
                        'lname' => $lname,
                        'suffix' => $suffix,
                        'username' => $usern,
                        'pass' => password_hash($passw, PASSWORD_ARGON2ID),
                        'email' => $email,
                        'user_lvl' => $acclvl,
                        'dept' => $dept,
                        'account_type' => $acct_type,
                        'entity_ref_id' => $entity_ref ? (int) $entity_ref : null,
                        'status' => 'ACTIVE',
                    ];
                    try {
                        $user_m->save($userData);
                        $status = 1;
                        $message = 'User created successfully.';
                        $useracc_id = $user_m->getInsertID();
                        $log_c['processDetails'] = 'ACCOUNT_ID: ' . $useracc_id . ' ' . $lname . ' [' . $acct_type . ']';
                    } catch (\Exception $e) {
                        $message = 'An error occurred while saving the user data.';
                        return;
                    }
                }
                break;
            }
            case 'create_barangay': {
                // #24b – BARANGAY accounts cannot create new barangays
                if ($user->account_type === 'BARANGAY') {
                    $message = 'Barangay accounts cannot create new Barangays.';
                    break;
                }
                $brgy_m = new \App\Models\Barangay();
                $brgy_name = $this->request->getPost('txtBrgy');
                $brngy_capt = $this->request->getPost('txtCapt');
                $mission = $this->request->getPost('txtMission');
                $vision = $this->request->getPost('txtVision');
                $about = $this->request->getPost('createAbout');
                $contact = $this->request->getPost('txtContact');
                $barangay_staff = $this->request->getPost('txtStaff');
                $imgLogo = $this->request->getFile('brgyImg');
                // $imgCapt = $this->request->getFile('brgyImgCapt'); // Captain image - commented out as not needed

                // Check if the department name already exists
                $existing_brgy = $brgy_m->where('brgy_name', $brgy_name)->first();

                if ($existing_brgy) {
                    $message = 'Barangay name already exists.';
                } else {
                    $logoName = $imgLogo->getRandomName();
                    // $captName = $imgCapt->getRandomName(); // Captain image - commented out as not needed

                    $file_category = 'BARANGAY';
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    if ($imgLogo->move($path, $logoName)) { // Removed captain image upload requirement
                        // Save other form data to the database
                        $data = [
                            'brgy_name' => $brgy_name,
                            'brngy_capt' => $brngy_capt,
                            'mission' => $mission,
                            'vision' => $vision,
                            'about' => $about,
                            'contact' => $contact,
                            'barangay_staff' => $barangay_staff,
                            'img_logo' => $logoName,
                            // 'img_capt' => $captName, // Captain image - commented out as not needed
                            'status' => 'ACTIVE',
                            'created_date' => date('Y-m-d H:i:s')
                        ];

                        if ($brgy_m->insert($data)) {
                            $status = 1;
                            $message = 'Barangay created successfully';
                            $brgy_id = $brgy_m->getInsertID();
                            $log_c['processDetails'] = 'BRGY_ID: ' . $brgy_id . ' ' . $brgy_name;
                        } else {
                            $message = 'Failed to save data';
                            break;
                        }
                    } else {
                        $message = 'Failed to upload files';
                        break;
                    }
                }
                break;
            }
            case 'create_dept': {
                // #23b – DEPARTMENT accounts cannot create new departments unless DEVELOPER
                // Department-scoped ADMINs also cannot create new departments
                if (($user->account_type === 'DEPARTMENT' && $user->user_lvl !== 'DEVELOPER') || $isDeptScopedAdmin) {
                    $message = 'Department accounts cannot create new Departments.';
                    break;
                }
                $dept_m = new \App\Models\Department();
                $dept_name = $this->request->getPost('txtDept');
                $head = $this->request->getPost('txtHead');
                $post_title = $this->request->getPost('txtTitle');
                $mission = $this->request->getPost('txtMission');
                $vision = $this->request->getPost('txtVision');
                $quality_policy = $this->request->getPost('txtPolicy');
                $imgLogo = $this->request->getFile('deptImg');
                $imgOrgChart = $this->request->getFile('deptOrgChart');
                $about = $this->request->getPost('txtAbout');
                $contact = $this->request->getPost('txtContact');

                // Check if the department name already exists
                $existing_dept = $dept_m->where('dept_name', $dept_name)->first();

                if ($existing_dept) {
                    $message = 'Department name already exists.';
                } else {
                    $logoName = $imgLogo->getRandomName();
                    $orgChartName = $imgOrgChart ? $imgOrgChart->getRandomName() : null;

                    $file_category = 'DEPT';
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    if ($imgLogo->move($path, $logoName)) {
                        // Save other form data to the database
                        $data = [
                            'dept_name' => $dept_name,
                            'head' => $head,
                            'post_title' => $post_title,
                            'mission' => $mission,
                            'vision' => $vision,
                            'img_logo' => $logoName,
                            'org_chart_img' => $orgChartName,
                            'quality_policy' => $quality_policy,
                            'about' => $about,
                            'contact' => $contact,
                            'status' => 'ACTIVE',
                            'created_date' => date('Y-m-d H:i:s')
                        ];

                        if ($dept_m->insert($data)) {
                            $status = 1;
                            $message = 'Department created successfully';
                            $dept_id = $dept_m->getInsertID();
                            $log_c['processDetails'] = 'DEPT_ID: ' . $dept_id;
                        } else {
                            $message = 'Failed to save data';
                            return;
                        }
                    } else {
                        $message = 'Failed to upload files';
                        return;
                    }
                }
                break;
            }

            case 'create_cityoff': {
                $co_m = new \App\Models\CityOfficial();
                $off_name = $this->request->getPost('offname');
                $off_position = $this->request->getPost('offpos');
                $img_loc = $this->request->getFile('offimg');
                $ranking = $this->request->getPost('offrank');
                $years_of_service = $this->request->getPost('years_of_service');
                $personal_data = $this->request->getPost('personal_data');
                $awards = $this->request->getPost('awards');
                $carouselImages = $this->request->getFiles()['carouselimages'] ?? [];
                // Set ranking to null if empty
                if (empty($ranking)) {
                    $ranking = null;
                } else {
                    // Check if the ranking already exists
                    if ($co_m->where('ranking', $ranking)->countAllResults() > 0) {
                        $status = 0;
                        $message = 'Ranking number already exists. Please choose a different ranking.';
                        break;
                    }
                }
                // Check if the position is unique unless it is 'City Councilor'
                if ($off_position !== 'CITY COUNCILOR' && $co_m->where('off_position', $off_position)->countAllResults() > 0) {
                    $status = 0;
                    $message = 'The position already exists. Only "City Councilor" can repeat.';
                    break;
                }
                $logoName = $img_loc->getRandomName();
                $path = WRITEPATH . 'uploads/CITYOFFICIAL/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $carouselFilenames = [];
                if ($img_loc->move($path, $logoName)) {
                    // Handle carousel images (up to 3)
                    if (!empty($carouselImages) && is_array($carouselImages)) {
                        $count = 0;
                        foreach ($carouselImages as $file) {
                            if ($file->isValid() && !$file->hasMoved() && $count < 3) {
                                $carouselName = $file->getRandomName();
                                if ($file->move($path, $carouselName)) {
                                    $carouselFilenames[] = $carouselName;
                                    $count++;
                                }
                            }
                        }
                    }
                    $data = [
                        'off_name' => $off_name,
                        'off_position' => $off_position,
                        'img_loc' => $logoName,
                        'ranking' => $ranking,
                        'status' => 'ACTIVE',
                        'created_date' => date('Y-m-d H:i:s'),
                        'years_of_service' => $years_of_service,
                        'personal_data' => $personal_data,
                        'awards' => $awards,
                        'carouselimages' => implode(',', $carouselFilenames)
                    ];
                    if ($co_m->insert($data)) {
                        $status = 1;
                        $message = 'City Official Info created successfully';
                        $co_id = $co_m->getInsertID();
                        $log_c['processDetails'] = 'CITYOFFICIAL_ID: ' . $co_id . ' ' . $off_position;
                    } else {
                        $message = 'Failed to save data';
                        return;
                    }
                } else {
                    $message = 'Failed to upload file';
                    return;
                }
                break;
            }

            case 'create_postcontent': {
                $con_m = new \App\Models\Content();
                $title = $this->request->getPost('title');
                $author = trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')); // Automatically set author from session
                $desc = $this->request->getPost('desc');
                $imgLogo = $this->request->getFile('newsImg');
                $category = $this->request->getPost('content_category');

                $logoName = $imgLogo->getRandomName();
                $file_category = 'POSTCONTENT';
                $path = WRITEPATH . 'uploads/' . $file_category;

                if ($imgLogo->move($path, $logoName)) {
                    // Save other form data to the database
                    $data = [
                        'title' => $title,
                        'author' => $author,
                        'description' => $desc,
                        'file_loc' => $logoName,
                        'category' => $category,
                        'status' => 'ACTIVE',
                        'created_date' => date('Y-m-d H:i:s')
                    ];

                    if ($con_m->insert($data)) {
                        $status = 1;
                        $message = 'Post Content created successfully';
                        $insertedId = $con_m->getInsertID();
                        $log_c['processDetails'] = 'POSTCONTENT_ID: ' . $insertedId . ' TITLE: ' . $title;
                    } else {
                        $message = 'Failed to save data';
                        return;
                    }
                } else {
                    $message = 'Failed to upload file';
                    return;
                }
                break;
            }
            case 'create_mayor': {

                if (!$canManageMayor) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $may_m = new \App\Models\MayorContent();
                $mayor_name = $this->request->getPost('myrname');
                $section = $this->request->getPost('content_category');
                $content = $this->request->getPost('perdata');

                // Check if the section already exists
                $existing_section = $may_m->where('section', $section)->first();

                if ($existing_section) {
                    $message = 'Only one entry per section';
                } else {
                    // Prepare data for saving
                    $data = [
                        'mayor_name' => $mayor_name,
                        'section' => $section,
                        'content' => $content,
                        'status' => 'ACTIVE',
                        'created_date' => date('Y-m-d H:i:s')
                    ];

                    $file_category = 'MAYOR';
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    // Handle file upload
                    $mayor_img = $this->request->getFileMultiple('mayorimg');
                    if ($mayor_img) {
                        $uploaded_files = [];
                        foreach ($mayor_img as $img) {
                            if ($img->isValid() && !$img->hasMoved() && $img->move($path, $img->getRandomName())) {
                                $uploaded_files[] = $img->getName();
                            }
                        }
                        $data['mayor_img'] = json_encode($uploaded_files); // Store as JSON
                    }

                    // Insert data into the database
                    try {
                        if ($may_m->insert($data)) {
                            $status = 1;
                            $message = 'Content created successfully';
                            $mayor_id = $may_m->getInsertID();
                            $log_c['processDetails'] = 'MAYOR_ID: ' . $mayor_id . ' SECTION: ' . $section;
                        } else {
                            $message = 'Failed to save data.';
                        }
                    } catch (\Exception $e) {
                        $message = 'An error occurred while saving: ' . $e->getMessage();
                    }
                }
                break;
            }

            case 'create_fulldiscpol': {
                if (!$canManageFullDisc) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $policy_m = new \App\Models\FileTbl();
                $fc = $this->request->getPost('fileCategory');
                $yr = $this->request->getPost('yr');
                $qtr = $this->request->getPost('qtr');
                $policyFile = $this->request->getFile('policyFile');

                // Validate the file
                if ($policyFile->isValid() && !$policyFile->hasMoved()) {
                    $fileName = $policyFile->getRandomName();
                    $file_category = 'FULLDISC';
                    $path = WRITEPATH . 'uploads/' . $file_category;
                    $policyFile->move($path, $fileName);
                } else {
                    $message = 'Failed to upload the file. Please try again.';
                    return;
                }

                $data = [
                    'file_name' => $fileName,
                    'file_category' => $fc,
                    'category' => $file_category,
                    'created_date' => date('Y-m-d H:i:s'),
                    'quarter' => $qtr,
                    'status' => 'ACTIVE',
                    'year' => $yr,
                ];
                try {
                    $policy_m->save($data);
                    $status = 1;
                    $message = 'Policy created successfully.';
                    $fulldisc_id = $policy_m->getInsertID();
                    $log_c['processDetails'] = 'FULLDISC_ID: ' . $fulldisc_id . ' ' . $fc . ' ' . $yr . ' - ' . $qtr;
                } catch (\Exception $e) {
                    $message = 'An error occurred while saving the data.';
                    return;
                }
                break;
            }

            case 'create_career': {
                // Only HRDO department and privileged roles can manage careers
                if (!$canManageCareers) {
                    $message = 'Unauthorized: Only the HRDO department can manage career postings.';
                    break;
                }
                $career_m = new \App\Models\FileTbl();
                $publication = $this->request->getPost('publication');
                $careerFile = $this->request->getFile('careerFile');
                $level = $this->request->getPost('level'); // Get level from POST

                $careerExt = strtolower((string) $careerFile->getClientExtension());
                $careerMime = strtolower((string) $careerFile->getClientMimeType());
                if ($careerExt !== 'pdf' || ($careerMime !== 'application/pdf' && $careerMime !== 'application/x-pdf')) {
                    $message = 'Please upload a PDF file only.';
                    break;
                }

                // Validate the file
                if ($careerFile->isValid() && !$careerFile->hasMoved()) {
                    $fileName = $careerFile->getRandomName();
                    $file_category = 'CAREERS';
                    $path = WRITEPATH . 'uploads/' . $file_category;
                    $careerFile->move($path, $fileName);
                } else {
                    $message = 'Failed to upload the file. Please try again.';
                    return;
                }

                $data = [
                    'file_name' => $fileName,
                    'category' => 'CAREER',
                    'created_date' => date('Y-m-d H:i:s'),
                    'publication_date' => $publication,
                    'status' => 'ACTIVE',
                    'level' => $level // Save level
                ];
                try {
                    $career_m->save($data);
                    $status = 1;
                    $message = 'Career created successfully.';
                    $c_id = $career_m->getInsertID();
                    $log_c['processDetails'] = 'CAREER_ID: ' . $c_id;
                } catch (\Exception $e) {
                    $message = 'An error occurred while saving the data.';
                    return;
                }
                break;
            }


            case 'create_invest': {
                if (!$canManageInvest) {
                    $message = 'Unauthorized: Only the BPLO department can manage investment content.';
                    break;
                }
                $invest_m = new \App\Models\FileTbl();
                $fc = $this->request->getPost('fileCategory');
                $investFile = $this->request->getFile('investFile');

                $existing_inv = $invest_m->where('file_category', $fc)->first();

                if ($existing_inv) {
                    $status = 0;
                    $message = 'File category already exists.';
                } else {
                    if ($investFile->isValid() && !$investFile->hasMoved()) {
                        $fileName = $investFile->getRandomName();
                        $file_category = 'INVEST';
                        $path = WRITEPATH . 'uploads/' . $file_category;
                        $investFile->move($path, $fileName);
                    } else {
                        $status = 0;
                        $message = 'Failed to upload the file. Please try again.';
                    }

                    $data = [
                        'file_name' => $fileName,
                        'file_category' => $fc,
                        'category' => $file_category,
                        'created_date' => date('Y-m-d H:i:s'),
                        'status' => 'ACTIVE'
                    ];
                    try {
                        $invest_m->save($data);
                        $status = 1;
                        $message = 'Content created successfully.';
                        $in_id = $invest_m->getInsertID();
                        $log_c['processDetails'] = 'INVEST_ID: ' . $in_id . ' ' . $fc;
                    } catch (\Exception $e) {
                        $status = 0;
                        $message = 'An error occurred while saving the data.';
                    }
                }

                break;
            }
            case 'create_services': {
                $serv_m = new \App\Models\Services();
                $serv_name = $this->request->getPost('serviceName');
                $content = $this->request->getPost('content');
                $dept_cont_ID = $this->request->getPost('txtDept');
                $brngy_cont_ID = $this->request->getPost('txtBrgy');

                // #23/#24 – DEPARTMENT/BARANGAY accounts can only create
                // services linked to their OWN entity.
                if ($user->account_type === 'DEPARTMENT') {
                    $dept_cont_ID = $user->entity_ref_id;
                    $brngy_cont_ID = null;
                } elseif ($user->account_type === 'BARANGAY') {
                    $brngy_cont_ID = $user->entity_ref_id;
                    $dept_cont_ID = null;
                }

                $data = [
                    'serv_name' => $serv_name,
                    'content' => $content,
                    'status' => 'ACTIVE',
                    'created_date' => date('Y-m-d H:i:s'),
                ];

                if ($dept_cont_ID) {
                    $data['dept_cont_ID'] = $dept_cont_ID;
                } elseif ($brngy_cont_ID) {
                    $data['brngy_cont_ID'] = $brngy_cont_ID;
                }

                try {
                    $serv_m->save($data);
                    $status = 1;
                    $message = 'Service created successfully.';
                    $serv_id = $serv_m->getInsertID();
                    $log_c['processDetails'] = 'SERVICE_ID: ' . $serv_id;
                } catch (\Exception $e) {
                    $message = 'An error occurred while saving the data.';
                    return;
                }
                break;
            }
            case 'create_contact': {
                $hot_m = new \App\Models\Hotlines();
                $telco = $this->normalizePhilippineLandlineNumber($this->request->getPost('telco'));
                $number = $this->normalizePhilippineLandlineNumber($this->request->getPost('contact'));
                $smart = $this->normalizePhilippineMobileNumber($this->request->getPost('smart'));
                $globe = $this->normalizePhilippineMobileNumber($this->request->getPost('globe'));
                $dept_cont_ID = $this->request->getPost('txtDept');
                $brngy_cont_ID = $this->request->getPost('txtBrgy');
                $others_cont_ID = $this->request->getPost('txtOthers');

                if ($number !== '' && !$this->isValidPhilippineLandlineNumber($number)) {
                    $message = 'PLDT Landline must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.';
                    break;
                }

                if ($telco !== '' && !$this->isValidPhilippineLandlineNumber($telco)) {
                    $message = 'INTELCO Line must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.';
                    break;
                }

                if ($smart !== '' && !$this->isValidPhilippineMobileNumber($smart)) {
                    $message = 'SMART number must be in the format +63 9XX XXX XXXX.';
                    break;
                }

                if ($globe !== '' && !$this->isValidPhilippineMobileNumber($globe)) {
                    $message = 'GLOBE number must be in the format +63 9XX XXX XXXX.';
                    break;
                }

                // Dept/brgy-scoped ADMIN: force their own entity
                if ($isDeptScopedAdmin) {
                    $dept_cont_ID = $user->entity_ref_id;
                    $brngy_cont_ID = null;
                    $others_cont_ID = null;
                } elseif ($isBrgyScopedAdmin) {
                    $brngy_cont_ID = $user->entity_ref_id;
                    $dept_cont_ID = null;
                    $others_cont_ID = null;
                }

                if ($dept_cont_ID) {
                    $content_ref_id = $dept_cont_ID;
                    $section = 'Department';
                } else if ($brngy_cont_ID) {
                    $content_ref_id = $brngy_cont_ID;
                    $section = 'Barangay';
                } else if ($others_cont_ID) {
                    $content_ref_id = $others_cont_ID;
                    $section = 'Others';
                }

                // Check for existing hotline with the same content_ref_id and section
                $existingHotline = $hot_m->where('section', $section)
                    ->where('content_ref_id', $content_ref_id)
                    ->first();

                if ($existingHotline) {
                    $status = 0;
                    $message = 'A hotline for this ' . strtolower($section) . ' already exists.';
                    break;
                }

                $data = [
                    'telco' => $telco,
                    'number' => $number,
                    'smart' => $smart,
                    'globe' => $globe,
                    'status' => 'ACTIVE',
                    'section' => $section,
                    'content_ref_id' => $content_ref_id,
                    'created_date' => date('Y-m-d H:i:s'),
                ];

                try {
                    $hot_m->save($data);
                    $status = 1;
                    $message = 'Contact created successfully.';
                    $hot_id = $hot_m->getInsertID();
                    $log_c['processDetails'] = 'CONTACT_ID: ' . $hot_id;
                } catch (\Exception $e) {
                    $message = 'An error occurred while saving the data.';
                }
                break;
            }
            case 'create_about': {
                if (!$canManageAbout) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $about_m = new \App\Models\About();
                $section = $this->request->getPost('content_category');
                $title = $this->request->getPost('TxtTitle');
                $description = $this->request->getPost('TxtDesc');
                $about_img = $this->request->getFile('AboutImg');

                $existing = $about_m->where('section', $section)->first();
                if ($existing && $section === 'Home Page') {
                    $message = 'Home Page content cannot be repeated.';
                } else {
                    $logoName = null;
                    if ($about_img && $about_img->isValid() && !$about_img->hasMoved()) {
                        $logoName = $about_img->getRandomName();
                        $file_category = 'ABOUT';
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        if (!$about_img->move($path, $logoName)) {
                            $status = 0;
                            $message = 'Failed to upload file.';
                            return $this->response->setJSON(['status' => $status, 'message' => $message]);
                        }
                    }

                    $data = [
                        'section' => $section,
                        'title' => $title,
                        'description' => $description,
                        'status' => 'ACTIVE',
                        'created_date' => date('Y-m-d H:i:s')
                    ];

                    // If an image was uploaded, add it to the data array
                    if ($logoName !== null) {
                        $data['about_img'] = $logoName;
                    }
                    if ($about_m->insert($data)) {
                        $status = 1;
                        $message = 'Content created successfully';
                        $abt_id = $about_m->getInsertID();
                        $log_c['processDetails'] = 'ABOUT_ID: ' . $abt_id . ' TITLE: ' . $title;
                    } else {
                        $status = 0;
                        $message = 'Failed to save data';
                    }
                }
                break;
            }

            /* -------------------
            |
            | UPDATE
            |
            ------------------- */

            case 'update_user': {
                $user_m     = new \App\Models\UserAccount();
                $id         = $this->request->getPost('id');
                // Shared modal uses 'txt*' field names; fall back to legacy 'edit*' names
                $fname      = $this->request->getPost('txtFirstName')   ?: $this->request->getPost('editFirstName');
                $mname      = $this->request->getPost('txtMiddleName')  ?: $this->request->getPost('editMiddleName')  ?: '';
                $lname      = $this->request->getPost('txtLastName')    ?: $this->request->getPost('editLastName');
                $suffix     = $this->request->getPost('txtSuffix')      ?: $this->request->getPost('editSuffix')      ?: '';
                $usern      = $this->request->getPost('txtUsername')    ?: $this->request->getPost('editUsername');
                $email      = $this->request->getPost('txtEmail')       ?: $this->request->getPost('editEmail');
                $acclvl     = $this->request->getPost('txtAccLevel')    ?: $this->request->getPost('editAccLevel');
                $dept       = $this->request->getPost('editDept')       ?: '';
                $passw      = $this->request->getPost('txtPassword')    ?: $this->request->getPost('editPassword');
                // Account type & linked entity
                $acct_type  = $this->request->getPost('txtAccountType') ?: $this->request->getPost('editAccountType') ?: null;
                $entity_ref = $this->request->getPost('txtEntityRef')   ?: $this->request->getPost('editEntityRef')   ?: null;

                if (empty($fname) || empty($mname) || empty($lname) || empty($usern) || empty($email) || empty($acclvl)) {
                    $message = 'Please fill in all required fields (First, Middle, and Last Names are required).';
                    break;
                }

                // Nobody can promote an account to DEVELOPER
                if ($acclvl === 'DEVELOPER') {
                    $message = 'Cannot set account level to Developer.';
                    break;
                }

                // Only DEVELOPER can promote/set an account to SUPERADMIN
                if ($acclvl === 'SUPERADMIN' && $user->user_lvl !== 'DEVELOPER') {
                    $message = 'Only Developers can set an account to Super Admin.';
                    break;
                }

                // ADMIN scope rules
                if ($user->user_lvl === 'ADMIN') {
                    $target = $user_m->find($id);
                    // Admin cannot edit other Admin-level or higher accounts
                    if (
                        $target && in_array($target->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])
                        && (int) $target->ID !== (int) $user->ID
                    ) {
                        $message = 'You do not have permission to edit this account.';
                        break;
                    }
                    // Admin can only assign ENCODER or VIEWER
                    if (!in_array($acclvl, ['ENCODER', 'VIEWER'])) {
                        $message = 'Admin accounts can only set Encoder or Viewer level.';
                        break;
                    }
                    // Admin cannot change entity — force their own
                    $acct_type = $user->account_type ?? '';
                    $entity_ref = $user->entity_ref_id ?? null;
                }

                $valid_types = ['DEPARTMENT', 'BARANGAY'];
                if (!in_array($acct_type, $valid_types)) {
                    $acct_type = '';
                }
                // For high-privilege roles, clear entity binding
                if (in_array($acclvl, ['SUPERADMIN'])) {
                    $acct_type = '';
                    $entity_ref = null;
                }
                if (in_array($acct_type, ['DEPARTMENT', 'BARANGAY']) && empty($entity_ref)) {
                    $message = 'A Department or Barangay account must be linked to an entity.';
                    break;
                }

                $linkedEntityName = $this->resolveAccountEntityName($acct_type, $entity_ref);
                if ($linkedEntityName === null) {
                    $message = $acct_type === 'DEPARTMENT'
                        ? 'Selected department was not found.'
                        : 'Selected barangay was not found.';
                    break;
                }
                if ($linkedEntityName !== '') {
                    $dept = $linkedEntityName;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Invalid email address.';
                } else {
                    $existingUser = $user_m->where('id !=', $id)
                        ->groupStart()
                        ->where('username', $usern)
                        ->orWhere('email', $email)
                        ->groupEnd()
                        ->first();

                    if ($existingUser) {
                        $message = 'Username or email already exists.';
                    } else {
                        $data = [
                            'fname' => $fname,
                            'mname' => $mname,
                            'lname' => $lname,
                            'suffix' => $suffix,
                            'username' => $usern,
                            'email' => $email,
                            'user_lvl' => $acclvl,
                            'dept' => $dept,
                            'account_type' => $acct_type,
                            'entity_ref_id' => $entity_ref ? (int) $entity_ref : null,
                            'updated_date' => date('Y-m-d H:i:s'),
                        ];

                        if (!empty($passw)) {
                            $data['pass'] = password_hash($passw, PASSWORD_ARGON2ID);
                        }

                        try {
                            $user_m->update($id, $data);
                            $status = 1;
                            $message = 'User updated successfully.';
                            $log_c['processDetails'] = 'ACCOUNT_ID: ' . $id . ' [' . $acct_type . ']';
                        } catch (\Exception $e) {
                            $message = 'An error occurred while updating the user data.';
                            return;
                        }
                    }
                }
                break;
            }
            case "update_barangay": {
                $brgy_m = new \App\Models\Barangay();
                $id = $this->request->getPost('id');
                $barangay = $brgy_m->find($id);

                if (!$barangay) {
                    $message = "Barangay not found.";
                    break;
                }

                // #24 – BARANGAY accounts can only update THEIR OWN barangay
                if (
                    $user->account_type === 'BARANGAY' &&
                    (int) $user->entity_ref_id !== (int) $id
                ) {
                    $message = 'You can only update your own Barangay.';
                    break;
                }

                $brgy_contact = $this->request->getPost('editContact');
                $brgy_about = $this->request->getPost('editAbout');
                $brgy_name = $this->request->getPost('editBrgy');
                $brngy_capt = $this->request->getPost('editCapt');
                $mission = $this->request->getPost('editMission');
                $vision = $this->request->getPost('editVision');
                $barangay_staff = $this->request->getPost('editStaff');

                // Check if the barangay name already exists for other records
                $existing_brgy = $brgy_m->where('brgy_name', $brgy_name)->where('id !=', $id)->first();

                if ($existing_brgy) {
                    $message = 'Barangay name already exists.';
                    break;
                }

                $data = [
                    'contact' => $brgy_contact,
                    'about' => $brgy_about,
                    'brgy_name' => $brgy_name,
                    'brngy_capt' => $brngy_capt,
                    'mission' => $mission,
                    'vision' => $vision,
                    'barangay_staff' => $barangay_staff,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                $maxmb = 4;
                $file_category = 'BARANGAY';

                // Handle file uploads for barangay logo
                $imgLogo = $this->request->getFile('editbrgyImg');
                if ($imgLogo && $imgLogo->isValid() && $imgLogo->getSize() < ($maxmb * 1024 * 1024)) {
                    $logoName = $imgLogo->getRandomName();
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    if ($imgLogo->move($path, $logoName)) {
                        $data['img_logo'] = $logoName;
                    }
                }

                // Captain image upload handling - commented out as not needed
                /*
                // Handle file uploads for captain image
                $imgCapt = $this->request->getFile('editbrgyImgCapt');
                if ($imgCapt && $imgCapt->isValid() && $imgCapt->getSize() < ($maxmb * 1024 * 1024)) {
                    $captName = $imgCapt->getRandomName();
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    if ($imgCapt->move($path, $captName)) {
                        $data['img_capt'] = $captName;
                    }
                }
                */

                // Update barangay data
                try {
                    $brgy_m->update($id, $data);
                    $status = 1;
                    $message = 'Content updated successfully.';
                    $log_c['processDetails'] = 'BRGY_ID: ' . $id;
                } catch (\Exception $e) {
                    $message = 'An error occurred while updating.';
                }

                break;
            }

            case "update_dept": {
                $dept_m = new \App\Models\Department();
                $id = $this->request->getPost('id');
                $department = $dept_m->find($id);

                if (!$department) {
                    $message = 'Department not found.';
                    break;
                }

                // #23 – DEPARTMENT accounts can only update THEIR OWN department unless DEVELOPER
                // This also applies to dept-scoped ADMIN accounts
                if (
                    ($user->account_type === 'DEPARTMENT' && $user->user_lvl !== 'DEVELOPER' &&
                        (int) $user->entity_ref_id !== (int) $id) ||
                    ($isDeptScopedAdmin && (int) $user->entity_ref_id !== (int) $id)
                ) {
                    $message = 'You can only update your own Department.';
                    break;
                }

                if ($department) {
                    $dept_name = $this->request->getPost('editDept');
                    $head = $this->request->getPost('editHead');
                    $post_title = $this->request->getPost('editTitle');
                    $mission = $this->request->getPost('editMission');
                    $vision = $this->request->getPost('editVision');
                    $quality_policy = $this->request->getPost('editPolicy');
                    $about = $this->request->getPost('editAbout');
                    $contact = $this->request->getPost('editContact');

                    // Check if the department name already exists, excluding the current department
                    $existing_dept = $dept_m->where('dept_name', $dept_name)->where('id !=', $id)->first();

                    if ($existing_dept) {
                        $message = 'Department name already exists.';
                    } else {
                        // Prepare data for saving
                        $data = [
                            'dept_name' => $dept_name,
                            'head' => $head,
                            'post_title' => $post_title,
                            'mission' => $mission,
                            'vision' => $vision,
                            'quality_policy' => $quality_policy,
                            'about' => $about,
                            'contact' => $contact,
                            'updated_date' => date('Y-m-d H:i:s')
                        ];

                        $maxmb = 4;
                        $file_category = 'DEPT';

                        $logoName = null;

                        // Handle file uploads for dept logo
                        $imgLogo = $this->request->getFile('editdeptImg');
                        if ($imgLogo && $imgLogo->isValid() && $imgLogo->getSize() < ($maxmb * 1024 * 1024)) {
                            $logoName = $imgLogo->getRandomName();
                            $path = WRITEPATH . 'uploads/' . $file_category;

                            if (!$imgLogo->hasMoved() && $imgLogo->move($path, $logoName)) {
                                $data['img_logo'] = $logoName;
                            }
                        }

                        // Handle file uploads for org chart
                        $imgOrgChart = $this->request->getFile('editdeptOrgChart');
                        if ($imgOrgChart && $imgOrgChart->isValid() && $imgOrgChart->getSize() < ($maxmb * 1024 * 1024)) {
                            $orgChartName = $imgOrgChart->getRandomName();
                            $path = WRITEPATH . 'uploads/' . $file_category;

                            if (!$imgOrgChart->hasMoved() && $imgOrgChart->move($path, $orgChartName)) {
                                $data['org_chart_img'] = $orgChartName;
                            }
                        }
                        // Update department data
                        try {
                            $dept_m->update($id, $data);
                            $status = 1;
                            $message = 'Content updated successfully.';
                            $log_c['processDetails'] = 'DEPT_ID: ' . $id;
                        } catch (\Exception $e) {
                            $message = 'An error occurred while updating.';
                            return;
                        }
                    }
                } else {
                    $message = "Department not found.";
                }
                break;
            }
            case "update_cityoff": {
                $cityofficialmodel = new \App\Models\CityOfficial();

                $id = $this->request->getPost('id');
                $cityofficial = $cityofficialmodel->find($id);

                if ($cityofficial) {
                    $off_name = $this->request->getPost('editoffname');
                    $off_position = $this->request->getPost('editoffpos');
                    $ranking = $this->request->getPost('editoffrank');
                    $years_of_service = $this->request->getPost('edit_years_of_service');
                    $awards = $this->request->getPost('edit_awards');
                    $personal_data = $this->request->getPost('edit_personal_data');

                    if (empty($ranking)) {
                        $ranking = null;
                    } else {
                        $existingRanking = $cityofficialmodel->where('ranking', $ranking)->where('id !=', $id)->first();
                        if ($existingRanking) {
                            $status = 0;
                            $message = 'Ranking number already exists. Please choose a different ranking.';
                            break;
                        }
                    }

                    if ($off_position !== 'CITY COUNCILOR' && $cityofficialmodel->where('off_position', $off_position)->where('id !=', $id)->countAllResults() > 0) {
                        $status = 0;
                        $message = 'The position already exists. Only "City Councilor" can repeat.';
                        break;
                    }

                    $data = [
                        'off_name' => $off_name,
                        'off_position' => $off_position,
                        'ranking' => $ranking,
                        'years_of_service' => $years_of_service,
                        'awards' => $awards,
                        'personal_data' => $personal_data,
                        'updated_date' => date('Y-m-d H:i:s'),
                    ];

                    $maxmb = 4;
                    $file_category = 'CITYOFFICIAL';
                    $upload_path = WRITEPATH . 'uploads/CITYOFFICIAL/';

                    // Ensure the upload directory exists
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0755, true);
                    }

                    // Handle the main image (img_loc)
                    $logoName = null;
                    $imgLogo = $this->request->getFile('editoffimg');
                    if ($imgLogo && $imgLogo->isValid() && $imgLogo->getSize() < ($maxmb * 1024 * 1024)) {
                        $logoName = $imgLogo->getRandomName();
                        if (!$imgLogo->hasMoved() && $imgLogo->move($upload_path, $logoName)) {
                            $data['img_loc'] = $logoName;
                        }
                    }

                    // Handle carousel images (editoffcaroimg[])
                    $carousel_filenames = [];
                    $existing_images = [];

                    // Get existing images from the form or database
                    $existing_images_input = $this->request->getPost('existing_images');
                    if (!empty($existing_images_input)) {
                        $existing_images = explode(',', $existing_images_input);
                        $existing_images = array_filter($existing_images, 'trim'); // Remove empty values
                    } elseif (!empty($cityofficial->carouselimages)) {
                        $existing_images = explode(',', $cityofficial->carouselimages);
                        $existing_images = array_filter($existing_images, 'trim');
                    }

                    // Handle new uploads
                    $files = $this->request->getFileMultiple('editoffcaroimg');
                    if (!empty($files)) {
                        $max_images = 3;
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif',];

                        foreach ($files as $file) {
                            if ($file && $file->isValid() && $file->getSize() < ($maxmb * 1024 * 1024)) {
                                $file_ext = strtolower($file->getClientExtension());
                                if (in_array($file_ext, $allowed_extensions)) {
                                    $new_file_name = $file->getRandomName();
                                    if (!$file->hasMoved() && $file->move($upload_path, $new_file_name)) {
                                        $carousel_filenames[] = $new_file_name;
                                    }
                                }
                            }
                        }
                    }

                    // Combine existing and new images
                    $combined_images = array_merge($existing_images, $carousel_filenames);
                    $combined_images = array_unique($combined_images); // Remove duplicates
                    $total_images = count($combined_images);

                    // Validate total images against the limit
                    if ($total_images > 3) {
                        $status = 0;
                        $message = 'Cannot exceed 3 carousel images. You currently have ' . count($existing_images) . ' image(s) and tried to add ' . count($carousel_filenames) . ' more.';
                        break;
                    }

                    // Update carouselimages field
                    $data['carouselimages'] = !empty($combined_images) ? implode(',', $combined_images) : NULL;

                    try {
                        $cityofficialmodel->update($id, $data);
                        $status = 1;
                        $message = 'Content updated successfully.';
                        $log_c['processDetails'] = 'CITYOFFICIAL_ID: ' . $id . ' ' . $off_name;
                    } catch (\Exception $e) {
                        $status = 0;
                        $message = 'An error occurred while updating: ' . $e->getMessage();
                        break;
                    }
                } else {
                    $status = 0;
                    $message = "City Official not found.";
                    break;
                }
                break;
            }
            case "remove_carousel_image": {
                // Clear any output buffers to prevent stray output
                while (ob_get_level()) {
                    ob_end_clean();
                }

                // Start a new output buffer to catch any unexpected output
                ob_start();

                $cityofficialmodel = new \App\Models\CityOfficial();

                $id = $this->request->getPost('id');
                $imageName = $this->request->getPost('image');
                $cityofficial = $cityofficialmodel->find($id);

                $response = ['status' => 0, 'message' => ''];

                log_message('debug', "Starting remove_carousel_image for ID: $id, Image: $imageName");

                if ($cityofficial && $cityofficial->carouselimages) {
                    log_message('debug', "Current carouselimages: " . $cityofficial->carouselimages);

                    $images = explode(',', $cityofficial->carouselimages);
                    $images = array_filter($images, 'trim'); // Remove empty values

                    // Find and remove the image by name
                    if (in_array($imageName, $images)) {
                        log_message('debug', "Removing image: $imageName");
                        $images = array_diff($images, [$imageName]); // Remove the image
                        $images = array_values($images); // Reindex array
                        $newCarouselImages = !empty($images) ? implode(',', $images) : NULL;

                        // Delete the file from the filesystem
                        $upload_path = WRITEPATH . 'uploads/CITYOFFICIAL/';
                        $file_path = $upload_path . $imageName;
                        if (file_exists($file_path)) {
                            if (unlink($file_path)) {
                                log_message('debug', "Successfully deleted file: $file_path");
                            } else {
                                log_message('warning', "Failed to delete file: $file_path (possibly a permissions issue)");
                            }
                        } else {
                            log_message('warning', "File not found: $file_path");
                        }

                        // Update the database
                        $data = ['carouselimages' => $newCarouselImages];
                        log_message('debug', "Updating carouselimages to: " . ($newCarouselImages ?? 'NULL'));
                        if ($cityofficialmodel->update($id, $data)) {
                            $response['status'] = 1;
                            $response['message'] = 'Image removed successfully.';
                        } else {
                            $response['message'] = 'Failed to update database.';
                            log_message('error', "Failed to update database for ID: $id");
                        }
                    } else {
                        $response['message'] = 'Image not found in the carousel.';
                        log_message('error', "Image $imageName not found in carouselimages: " . $cityofficial->carouselimages);
                    }
                } else {
                    $response['message'] = 'City official or carousel images not found.';
                    log_message('error', "City official not found for ID: $id or no carousel images");
                }

                log_message('debug', "Sending response: " . json_encode($response));

                // Get any stray output (for debugging)
                $stray_output = ob_get_clean();
                if (!empty($stray_output)) {
                    log_message('error', "Stray output detected: " . $stray_output);
                }

                // Set headers for JSON response
                header('Content-Type: application/json');
                http_response_code($response['status'] === 1 ? 200 : 400);

                echo json_encode($response);
                exit; // Ensure no further output
                break;
            }
            case "update_postcontent": {
                $con_m = new \App\Models\Content();
                $id = $this->request->getPost('id');
                $ne_dt = $con_m->find($id);

                if ($ne_dt) {
                    $isMayorRestricted = $isMayor && !$isCIO && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
                    $deptNames = $this->getDepartmentUserNames($user);
                    if ($isMayorRestricted && !in_array($ne_dt->author, $deptNames)) {
                        $message = 'Unauthorized: You can only update your own created data.';
                        break;
                    }
                    $title = $this->request->getPost('editTitle');
                    $author = trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')); // Automatically set author from session
                    $desc = $this->request->getPost('editDesc');
                    $category = $this->request->getPost('edit_content_category');

                    $data = [
                        'title' => $title,
                        'author' => $author,
                        'description' => $desc,
                        'category' => $category,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];

                    $maxSize = 4 * 1024 * 1024; // 4MB
                    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
                    $file_category = 'POSTCONTENT';
                    $imgLogo = $this->request->getFile('editNewsImg');

                    if ($imgLogo && $imgLogo->isValid() && in_array($imgLogo->getMimeType(), $allowedTypes) && $imgLogo->getSize() <= $maxSize) {
                        $logoName = $imgLogo->getRandomName();
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        if (!$imgLogo->hasMoved() && $imgLogo->move($path, $logoName)) {
                            $data['file_loc'] = $logoName;
                        }
                    }

                    try {
                        $con_m->update($id, $data);
                        $status = 1;
                        $message = 'Content updated successfully.';
                        $log_c['processDetails'] = 'POSTCONTENT_ID: ' . $id . ' TITLE: ' . $title;
                    } catch (\Exception $e) {
                        $message = 'An error occurred while updating.';
                        return;
                    }
                } else {
                    $message = "Post Content not found.";
                    return;
                }
                break;
            }
            case 'update_mayor': {
                if (!$canManageMayor) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $may_m = new \App\Models\MayorContent();
                // Accept shared modal 'id'; fall back to 'recordId' if needed
                $id = $this->request->getPost('id') ?: $this->request->getPost('recordId');
                if (!$id || !is_numeric($id)) {
                    $message = 'Invalid record ID.';
                    break;
                }
                $mayorcontent = $may_m->find($id);

                if ($mayorcontent) {
                    $currentMayorImagesRaw = is_object($mayorcontent)
                        ? ($mayorcontent->mayor_img ?? '')
                        : (is_array($mayorcontent) ? ($mayorcontent['mayor_img'] ?? '') : '');
                    // Shared modal uses shared names; fall back to legacy edit-prefixed names
                    $mayor_name = $this->request->getPost('myrname')            ?: $this->request->getPost('editmyrname');
                    $section    = $this->request->getPost('content_category')   ?: $this->request->getPost('edit_content_category');
                    $content    = $this->request->getPost('perdata')             ?: $this->request->getPost('editperdata');

                    // Check if the section already exists
                    $existing_section = $may_m->where('section', $section)->where('id !=', $id)->first();
                    if ($existing_section) {
                        $message = 'Section already exists.';
                    } else {
                        // Prepare data for saving
                        $data = [
                            'mayor_name' => $mayor_name,
                            'section' => $section,
                            'content' => $content,
                            'updated_date' => date('Y-m-d H:i:s')
                        ];

                        $maxmb = 4;
                        $file_category = 'MAYOR';
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        // Handle file uploads for mayor image if new files are selected
                        // Shared modal uses 'mayorimg[]'; fall back to legacy 'editmayorimg[]'
                        $mayor_img = $this->request->getFileMultiple('mayorimg') ?: $this->request->getFileMultiple('editmayorimg');
                        $uploaded_files = [];
                        if ($mayor_img) {
                            foreach ($mayor_img as $img) {
                                if ($img->isValid() && !$img->hasMoved() && $img->move($path, $img->getRandomName())) {
                                    $uploaded_files[] = $img->getName();
                                }
                            }
                        }

                        $existing_mayor_images = $this->request->getPost('existing_mayor_images');
                        $retained_images = [];
                        if (!empty($existing_mayor_images)) {
                            $decoded_images = json_decode($existing_mayor_images, true);
                            if (is_array($decoded_images)) {
                                $retained_images = array_values(array_filter($decoded_images, static function ($image) {
                                    return is_string($image) && trim($image) !== '';
                                }));
                            } else {
                                $retained_images = array_values(array_filter(array_map('trim', explode(',', (string) $existing_mayor_images))));
                            }
                        } elseif (!empty($currentMayorImagesRaw)) {
                            $decoded_existing = json_decode((string) $currentMayorImagesRaw, true);
                            if (is_array($decoded_existing)) {
                                $retained_images = array_values(array_filter($decoded_existing, static function ($image) {
                                    return is_string($image) && trim($image) !== '';
                                }));
                            }
                        }

                        $final_images = array_values(array_unique(array_merge($retained_images, $uploaded_files)));
                        $data['mayor_img'] = json_encode($final_images);
                        $existing_db_images = json_decode((string) $currentMayorImagesRaw, true);
                        if (!is_array($existing_db_images)) {
                            $existing_db_images = [];
                        }

                        // Update mayor data
                        try {
                            $may_m->update($id, $data);
                            $removed_images = array_diff($existing_db_images, $final_images);
                            foreach ($removed_images as $removed_image) {
                                $remove_path = $path . DIRECTORY_SEPARATOR . $removed_image;
                                if (is_file($remove_path)) {
                                    @unlink($remove_path);
                                }
                            }
                            $status = 1;
                            $message = 'Content updated successfully.';
                            $log_c['processDetails'] = 'MAYOR_ID: ' . $id . ' SECTION: ' . $section;
                        } catch (\Exception $e) {
                            $message = 'An error occurred while updating: ' . $e->getMessage();
                        }
                    }
                } else {
                    $message = "Content not found.";
                }
                break;
            }
            case "update_fulldiscpol": {
                if (!$canManageFullDisc) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $policy_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $policy = $policy_m->find($id);

                if ($policy) {
                    $fc = $this->request->getPost('editFileCategory');
                    $yr = $this->request->getPost('edityr');
                    $qtr = $this->request->getPost('editqtr');

                    $data = [
                        'file_category' => $fc,
                        'updated_date' => date('Y-m-d H:i:s'),
                        'quarter' => $qtr,
                        'year' => $yr,
                    ];

                    $file_category = 'FULLDISC';
                    $policyFile = $this->request->getFile('editpolicyFile');

                    if ($policyFile && $policyFile->isValid()) {
                        $fileName = $policyFile->getRandomName();
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        if (!$policyFile->hasMoved()) {
                            $policyFile->move($path, $fileName);
                            $data['file_name'] = $fileName;
                        }
                    }

                    try {
                        $policy_m->update($id, $data);
                        $status = 1;
                        $message = 'Policy updated successfully.';
                        $log_c['processDetails'] = 'FULLDISC_ID: ' . $id . ' ' . $fc . ' ' . $yr . ' - ' . $qtr;
                    } catch (\Exception $e) {
                        $message = 'An error occurred while updating.';
                        return;
                    }
                }
                break;
            }

            case 'create_map': {
                // Initialize Map model
                $map_m = new \App\Models\Map();

                // Validate percentage input for top_loc and left_loc
                function validatePercentage($value, $fieldName)
                {
                    $cleanValue = trim($value);
                    if (empty($cleanValue) || strpos($cleanValue, '%') === false) {
                        return ['status' => 0, 'message' => "$fieldName must include a percentage ('%')."];
                    }
                    $cleanValue = str_replace('%', '', $cleanValue);
                    if (!is_numeric($cleanValue) || $cleanValue < 0 || $cleanValue > 100) {
                        return ['status' => 0, 'message' => "$fieldName must be a valid numeric percentage between 0 and 100."];
                    }
                    return ['status' => 1, 'value' => $cleanValue . '%'];
                }

                // Validate top_loc and left_loc inputs
                $top_loc_result = validatePercentage($this->request->getPost('top_loc'), 'Top Location');
                if ($top_loc_result['status'] === 0) {
                    return $this->response->setJSON($top_loc_result);
                }
                $left_loc_result = validatePercentage($this->request->getPost('left_loc'), 'Left Location');
                if ($left_loc_result['status'] === 0) {
                    return $this->response->setJSON($left_loc_result);
                }

                // Prepare data for insertion
                $data = [
                    'brgy_name' => $this->request->getPost('brgy_name'),
                    'top_loc' => $top_loc_result['value'],
                    'left_loc' => $left_loc_result['value'],
                    'details' => $this->request->getPost('details'),
                    'status' => 'Active'
                ];

                // Log data to be inserted
                error_log("Attempting to create map record with data: " . json_encode($data));

                // Perform the insertion using the Map model
                try {
                    $insertResult = $map_m->insert($data);
                    error_log("Model insert result: " . var_export($insertResult, true) . ". Last query: " . $map_m->getLastQuery());

                    if ($insertResult) {
                        return $this->response->setJSON(['status' => 1, 'message' => 'Map record created']);
                    } else {
                        error_log("Model insert failed. Data: " . json_encode($data) . ". Last query: " . $map_m->getLastQuery());
                        return $this->response->setJSON(['status' => 0, 'message' => 'Failed to create map record using model. Check server logs for details.']);
                    }
                } catch (\Exception $e) {
                    error_log("Model insert error: " . $e->getMessage() . ". Data: " . json_encode($data) . ". Last query: " . $map_m->getLastQuery());
                    return $this->response->setJSON(['status' => 0, 'message' => 'An error occurred while creating the record: ' . $e->getMessage()]);
                }
                break;
            }
            case 'get_map': {
                $mapId = $this->request->getPost('id');
                $map_m = new \App\Models\Map(); // Adjust model name if needed

                if ($mapId) {
                    $map_d = $map_m->find($mapId);
                    if ($map_d) {
                        $data = $map_d;
                        $status = 1;
                    } else {
                        $message = 'Map record not found';
                    }
                } else {
                    $map_builder = $map_m->orderBy('brgy_name', 'desc');
                    // Non-privileged users cannot see archived map records
                    if (!$canSeeArchived) {
                        $map_builder->where('status !=', 'ARCHIVED');
                    }
                    $map_d = $map_builder->findAll();
                    foreach ($map_d as $map) {
                        $data[] = $map;
                    }
                    $status = 1;
                }
                break;
            }
            case 'set_status_map': {
                $map_m = new \App\Models\Map();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');

                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];

                $map_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'MAP_ID: ' . $id . ' - ' . $status;

                $status = 1;
                break;
            }
            case 'update_map_record': {
                // Initialize Map model
                $map_m = new \App\Models\Map();
                $id = $this->request->getPost('id', FILTER_SANITIZE_STRING);

                // Log received ID and form data for debugging
                error_log("Received ID for update: " . var_export($id, true));
                error_log("Received POST data: " . json_encode($_POST));

                // Check if record exists
                $map_d = $map_m->find($id);
                if (!$map_d) {
                    error_log("Record not found for ID: " . var_export($id, true) . ". Available IDs: " . json_encode($map_m->findColumn('ID')));
                    echo json_encode(['status' => 0, 'msg' => 'Record not found for ID: ' . $id]);
                    exit;
                }

                // Validate percentage input for top_loc and left_loc
                function validatePercentage($value, $fieldName)
                {
                    $cleanValue = trim($value);
                    if (empty($cleanValue) || strpos($cleanValue, '%') === false) {
                        echo json_encode(['status' => 0, 'msg' => "$fieldName must include a percentage ('%')."]);
                        exit;
                    }
                    $cleanValue = str_replace('%', '', $cleanValue);
                    if (!is_numeric($cleanValue) || $cleanValue < 0 || $cleanValue > 100) {
                        echo json_encode(['status' => 0, 'msg' => "$fieldName must be a valid numeric percentage between 0 and 100."]);
                        exit;
                    }
                    return $cleanValue . '%';
                }

                // Validate top_loc and left_loc inputs
                $top_loc = validatePercentage($this->request->getPost('top_loc', FILTER_SANITIZE_STRING), 'Top Location');
                $left_loc = validatePercentage($this->request->getPost('left_loc', FILTER_SANITIZE_STRING), 'Left Location');

                // Prepare data for update
                $data = [
                    'brgy_name' => $this->request->getPost('brgy_name', FILTER_SANITIZE_STRING),
                    'top_loc' => $top_loc,
                    'left_loc' => $left_loc,
                    'details' => $this->request->getPost('details', FILTER_SANITIZE_STRING)
                ];

                // Log data to be updated with ID
                error_log("Attempting to update record with ID: $id and data: " . json_encode($data));

                // Perform the update using the Map model
                try {
                    $update = $map_m->update($id, $data);
                    error_log("Model update result: " . var_export($update, true) . ". Last query: " . $map_m->getLastQuery());

                    if ($update) {
                        echo json_encode(['status' => 1, 'msg' => 'Record updated successfully!']);
                    } else {
                        error_log("Model update failed for ID $id. Data: " . json_encode($data) . ". Last query: " . $map_m->getLastQuery());
                        echo json_encode(['status' => 0, 'msg' => 'Failed to update record using model. Check server logs for details.']);
                    }
                } catch (\Exception $e) {
                    error_log("Model update error for ID $id: " . $e->getMessage() . ". Data: " . json_encode($data) . ". Last query: " . $map_m->getLastQuery());
                    echo json_encode(['status' => 0, 'msg' => 'An error occurred while updating the record. Check server logs: ' . $e->getMessage()]);
                }

                exit;
            }
            case 'get_map_details': {
                $map_m = new \App\Models\Map();
                $id = $this->request->getPost('id');

                if ($id) {
                    $record = $map_m->where('ID', $id)->first(); // ✅ Fetches only one record

                    if ($record) {
                        $response = [
                            'status' => 1,
                            'msg' => 'Record found!',
                            'data' => $record // ✅ Sends data for modal population
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'msg' => 'Record not found.'
                        ];
                    }
                } else {
                    $response = [
                        'status' => 0,
                        'msg' => 'Invalid request. Missing ID.'
                    ];
                }

                echo json_encode($response);
                exit;
            }

            case "update_career": {
                // Only HRDO department and privileged roles can manage careers
                if (!$canManageCareers) {
                    $message = 'Unauthorized: Only the HRDO department can manage career postings.';
                    break;
                }
                $career_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $career = $career_m->find($id);

                if ($career) {
                    $editpublication = $this->request->getPost('editpublication');
                    $editlevel = $this->request->getPost('editlevel'); // Get editlevel from POST

                    $data = [
                        'updated_date' => date('Y-m-d H:i:s'),
                        'publication_date ' => $editpublication,
                        'level' => $editlevel // Update level
                    ];

                    $file_category = 'CAREERS';
                    $careerFile = $this->request->getFile('editCareerFile');

                    if ($careerFile && $careerFile->isValid()) {
                        $careerExt = strtolower((string) $careerFile->getClientExtension());
                        $careerMime = strtolower((string) $careerFile->getClientMimeType());
                        if ($careerExt !== 'pdf' || ($careerMime !== 'application/pdf' && $careerMime !== 'application/x-pdf')) {
                            $message = 'Please upload a PDF file only.';
                            break;
                        }

                        $fileName = $careerFile->getRandomName();
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        if (!$careerFile->hasMoved()) {
                            $careerFile->move($path, $fileName);
                            $data['file_name'] = $fileName;
                        }
                    }

                    try {
                        $career_m->update($id, $data);
                        $status = 1;
                        $message = 'Career updated successfully.';
                        $log_c['processDetails'] = 'CAREER_ID: ' . $id;
                    } catch (Exception $e) {
                        $message = 'An error occurred while updating.';
                        return;
                    }
                }
                break;
            }
            case "update_invest": {
                if (!$canManageInvest) {
                    $message = 'Unauthorized: Only the BPLO department can manage investment content.';
                    break;
                }
                $invest_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $inv_d = $invest_m->find($id);

                if ($inv_d) {
                    $fc = $this->request->getPost('editFileCategory');

                    $data = [
                        'file_category' => $fc,
                        'updated_date' => date('Y-m-d H:i:s'),
                    ];

                    $file_category = 'INVEST';
                    $invFile = $this->request->getFile('editInvestFile');

                    if ($invFile && $invFile->isValid() && !$invFile->hasMoved()) {
                        $fileName = $invFile->getRandomName();
                        $path = WRITEPATH . 'uploads/' . $file_category;

                        if ($invFile->move($path, $fileName)) {
                            $data['file_name'] = $fileName;
                        } else {
                            $status = 0;
                            $message = 'Failed to upload the new file.';
                            break;
                        }
                    }

                    try {
                        $invest_m->update($id, $data);
                        $status = 1;
                        $message = 'Content updated successfully.';
                        $log_c['processDetails'] = 'INVEST_ID: ' . $id . ' ' . $fc;
                    } catch (\Exception $e) {
                        $status = 0;
                        $message = 'An error occurred while updating.';
                    }
                } else {
                    $status = 0;
                    $message = 'Content not found.';
                }
                break;
            }
            case "update_services": {
                $serv_m = new \App\Models\Services();
                $id = $this->request->getPost('id');
                $serv = $serv_m->find($id);

                if (!$serv) {
                    $message = 'Service not found.';
                    break;
                }

                if (!$this->canAccessServiceRecord($serv, $user)) {
                    $message = 'You can only update services linked to your own entity.';
                    break;
                }

                if ($serv) {
                    $serv_name = $this->request->getPost('editServiceName');
                    $content = $this->request->getPost('editContent');
                    $dept_cont_ID = $this->request->getPost('editDept');
                    $brngy_cont_ID = $this->request->getPost('editBrgy');

                    if ($isDeptScopedAdmin) {
                        $dept_cont_ID = $user->entity_ref_id;
                        $brngy_cont_ID = null;
                    } elseif ($isBrgyScopedAdmin) {
                        $brngy_cont_ID = $user->entity_ref_id;
                        $dept_cont_ID = null;
                    }

                    $data = [
                        'serv_name' => $serv_name,
                        'content' => $content,
                        'updated_date' => date('Y-m-d H:i:s'),
                    ];
                    //log_message('debug', 'Received data: ' . print_r(compact('dept_cont_ID', 'brngy_cont_ID'), true));

                    if ($dept_cont_ID) {
                        $data['dept_cont_ID'] = $dept_cont_ID;
                        $data['brngy_cont_ID'] = NULL; // Set the other id to null
                    } else if ($brngy_cont_ID) {
                        $data['brngy_cont_ID'] = $brngy_cont_ID;
                        $data['dept_cont_ID'] = NULL; // Set the other id to null
                    }

                    try {
                        $serv_m->update($id, $data);
                        $status = 1;
                        $message = 'Service updated successfully.';
                        $log_c['processDetails'] = 'SERVICE_ID: ' . $id;
                    } catch (\Exception $e) {
                        $message = 'An error occurred while updating';
                        log_message('error', '[ERROR] {exception}', ['exception' => $e]);
                        return;
                    }
                }
                break;
            }
            case "update_contact": {
                $hot_m = new \App\Models\Hotlines();
                $id = $this->request->getPost('id');
                $hot = $hot_m->find($id);

                // Scoped ADMIN ownership check (CIO bypassed)
                if ($hot && $isDeptScopedAdmin && !$isCIO) {
                    if ($hot->section !== 'Department' || (int) $hot->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only update contacts linked to your own department.';
                        break;
                    }
                } elseif ($hot && $isBrgyScopedAdmin) {
                    if ($hot->section !== 'Barangay' || (int) $hot->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only update contacts linked to your own barangay.';
                        break;
                    }
                }

                if ($hot) {
                    $telco = $this->normalizePhilippineLandlineNumber($this->request->getPost('editTelco'));
                    $number = $this->normalizePhilippineLandlineNumber($this->request->getPost('editContact'));
                    $smart = $this->normalizePhilippineMobileNumber($this->request->getPost('editSmart'));
                    $globe = $this->normalizePhilippineMobileNumber($this->request->getPost('editGlobe'));
                    $dept_cont_ID = $this->request->getPost('editDept');
                    $brngy_cont_ID = $this->request->getPost('editBrgy');
                    $others_cont_ID = $this->request->getPost('editOthers');

                    if ($number !== '' && !$this->isValidPhilippineLandlineNumber($number)) {
                        $message = 'PLDT Landline must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.';
                        break;
                    }

                    if ($telco !== '' && !$this->isValidPhilippineLandlineNumber($telco)) {
                        $message = 'INTELCO Line must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.';
                        break;
                    }

                    if ($smart !== '' && !$this->isValidPhilippineMobileNumber($smart)) {
                        $message = 'SMART number must be in the format +63 9XX XXX XXXX.';
                        break;
                    }

                    if ($globe !== '' && !$this->isValidPhilippineMobileNumber($globe)) {
                        $message = 'GLOBE number must be in the format +63 9XX XXX XXXX.';
                        break;
                    }

                    // Scoped ADMIN: force their own entity reference
                    if ($isDeptScopedAdmin) {
                        $dept_cont_ID = $user->entity_ref_id;
                        $brngy_cont_ID = null;
                        $others_cont_ID = null;
                    } elseif ($isBrgyScopedAdmin) {
                        $brngy_cont_ID = $user->entity_ref_id;
                        $dept_cont_ID = null;
                        $others_cont_ID = null;
                    }

                    if ($dept_cont_ID) {
                        $content_ref_id = $dept_cont_ID;
                        $section = 'Department';
                    } else if ($brngy_cont_ID) {
                        $content_ref_id = $brngy_cont_ID;
                        $section = 'Barangay';
                    } else if ($others_cont_ID) {
                        $content_ref_id = $others_cont_ID;
                        $section = 'Others';
                    }
                    $data = [
                        'telco' => $telco,
                        'number' => $number,
                        'smart' => $smart,
                        'globe' => $globe,
                        'status' => 'ACTIVE',
                        'section' => $section,
                        'content_ref_id' => $content_ref_id,
                        'updated_date' => date('Y-m-d H:i:s'),
                    ];

                    try {
                        $hot_m->update($id, $data);
                        $status = 1;
                        $message = 'Contact updated successfully.';
                        $log_c['processDetails'] = 'CONTACT_ID: ' . $id;
                    } catch (\Exception $e) {
                        $message = 'An error occurred while updating';
                        return;
                    }
                }
                break;
            }
            case 'update_about': {
                if (!$canManageAbout) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $about_m = new \App\Models\About();
                $id = $this->request->getPost('id');
                $about = $about_m->find($id);

                if (!$about) {
                    $message = "Content not found.";
                    break;
                }

                $section = $this->request->getPost('edit_content_category');
                $title = $this->request->getPost('EditTxtTitle');
                $description = $this->request->getPost('EditTxtDesc');

                $data = [
                    'section' => $section,
                    'title' => $title,
                    'description' => $description,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                $maxmb = 4;
                $file_category = 'ABOUT';

                $imgLogo = $this->request->getFile('EditAboutImg');
                if ($imgLogo && $imgLogo->isValid() && $imgLogo->getSize() < ($maxmb * 1024 * 1024)) {
                    $logoName = $imgLogo->getRandomName();
                    $path = WRITEPATH . 'uploads/' . $file_category;

                    if ($imgLogo->move($path, $logoName)) {
                        $data['about_img'] = $logoName;
                    }
                }
                try {
                    $about_m->update($id, $data);
                    $status = 1;
                    $message = 'Content updated successfully.';
                    $log_c['processDetails'] = 'ABOUT_ID: ' . $id . ' TITLE: ' . $title;
                } catch (\Exception $e) {
                    $message = 'An error occurred while updating.';
                }

                break;
            }

            case 'reset_password': {
                $user_m = new \App\Models\UserAccount();
                $id = $this->request->getPost('id');

                $userData = $user_m->find($id);
                if (!$userData) {
                    $status = 0;
                    $message = 'User not found.';
                    break;
                }

                $temporaryCode = bin2hex(random_bytes(4)); // Generates an 8-character temporary code    
                $data = [
                    'pass' => password_hash($temporaryCode, PASSWORD_ARGON2ID),
                    'updated_date' => date('Y-m-d H:i:s'),
                    'force_pass_reset' => 1
                ];

                if ($user_m->update($id, $data)) {
                    // EMAIL SERVICE
                    $emailService = \Config\Services::email();
                    $emailService->setTo($userData->email);
                    $emailService->setFrom('websiteBinan@gmail.com', 'Website Support');
                    $emailService->setSubject('Password Reset');
                    $emailService->setMessage("
                        Hello {$userData->fname},

                        Your password has been successfully reset.
                        Your temporary password is: {$temporaryCode}

                        Please log in and change your password immediately.
                    ");
                    $emailService->send();

                    $log_c['processDetails'] = 'ACCOUNT_ID: ' . $id . ' PASSWORD RESET & EMAIL SENT';
                    $status = 1;
                    $message = $temporaryCode;
                } else {
                    $status = 0;
                    $message = 'Failed to reset password.';
                }
                break;
            }

            /* -------------------
            |
            | SET STATUS
            |
            ------------------- */
            case 'set_status_user': {
                $user_m = new \App\Models\UserAccount();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');
                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $user_m->update($id, $data);
                $message = 'User status updated successfully.';
                $log_c['processDetails'] = 'ACCOUNT_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }

            case 'set_status_barangay': {
                $id = $this->request->getPost('id');
                if (($user->account_type ?? '') === 'BARANGAY' && (int)$id === (int)($user->entity_ref_id ?? 0) && !in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)) {
                    $message = 'You are not authorized to change the status of your linked barangay.';
                    break;
                }

                $brgy_m = new \App\Models\Barangay();
                $status = $this->request->getPost('status');
                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $brgy_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'BRGY_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }

            case 'set_status_dept': {
                $id = $this->request->getPost('id');
                if (($user->account_type ?? '') === 'DEPARTMENT' && (int)$id === (int)($user->entity_ref_id ?? 0) && !in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)) {
                    $message = 'You are not authorized to change the status of your linked department.';
                    break;
                }

                $dept_m = new \App\Models\Department();

                // Enforce ENCODER/dept-scoped ADMIN restriction
                if (($user->account_type === 'DEPARTMENT' && $user->user_lvl !== 'DEVELOPER') || $isDeptScopedAdmin) {
                    if ((int) $id !== (int) $user->entity_ref_id) {
                        $message = 'You are only authorized to manage your own department.';
                        break;
                    }
                }

                $status = $this->request->getPost('status');
                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $dept_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'DEPT_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }
            case 'set_status_cityoff': {
                $cityofficialmodel = new \App\Models\CityOfficial();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');
                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $cityofficialmodel->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'CITYOFFICIAL_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }
            case 'delete_cityoff': {
                $cityofficialmodel = new \App\Models\CityOfficial();
                $id = $this->request->getPost('id');

                // Check if the record exists
                if ($cityofficialmodel->find($id)) {
                    $cityofficialmodel->delete($id);
                    $message = 'City Official deleted successfully.';
                    $log_c['processDetails'] = 'CITYOFFICIAL_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'City Official not found.';
                    $log_c['processDetails'] = 'CITYOFFICIAL_ID: ' . $id . ' - NOT FOUND';
                    $status = 0;
                }
                break;
            }
            case 'set_status_mayor': {
                if (!$canManageMayor) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $may_m = new \App\Models\MayorContent();
                $id = $this->request->getPost('id');
                $statusVal = $this->request->getPost('status');
                $mayorRecord = $may_m->find($id);
                if ($mayorRecord) {
                    $sect = $mayorRecord->section ?? '';
                    $data = [
                        'status' => $statusVal,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];
                    $may_m->update($id, $data);
                    $message = 'Content status updated successfully.';
                    $log_c['processDetails'] = 'MAYOR_ID: ' . $id . ' SECTION: ' . $sect . ' - ' . $statusVal;
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }
            case 'set_status_postcontent': {
                $anns_m = new \App\Models\Content();
                $id = $this->request->getPost('id');

                $isMayorRestricted = $isMayor && !$isCIO && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
                if ($isMayorRestricted) {
                    $post = $anns_m->find($id);
                    $deptNames = $this->getDepartmentUserNames($user);
                    if ($post && !in_array($post->author, $deptNames)) {
                        $message = 'Unauthorized: You can only archive your own created data.';
                        break;
                    }
                }

                $statusVal = $this->request->getPost('status');
                $postRecord = $anns_m->find($id);
                if ($postRecord) {
                    $title = $postRecord->title ?? '';
                    $data = [
                        'status' => $statusVal,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];
                    $anns_m->update($id, $data);
                    $message = 'Content status updated successfully.';
                    $log_c['processDetails'] = 'POSTCONTENT_ID: ' . $id . ' TITLE: ' . $title . ' - ' . $statusVal;
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'set_status_fulldiscpol': {
                if (!$canManageFullDisc) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $fulldisc_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');
                $fdRow = $fulldisc_m->find($id);
                if ($fdRow) {
                    $fc = $fdRow->file_category;
                    $yr = $fdRow->year;
                    $qtr = $fdRow->quarter;
                    $data = [
                        'status' => $status,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];
                    $fulldisc_m->update($id, $data);
                    $message = 'Content status updated successfully.';
                    $log_c['processDetails'] = 'FULLDISC_ID: ' . $id . ' ' . $fc . ' ' . $yr . ' - ' . $qtr . ' - ' . $status;
                    $status = 1;
                } else {
                    $message = 'Policy not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_fulldiscpol': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!$canManageFullDisc) {
                    $message = 'Unauthorized access. You do not have permission to delete content.';
                    $status = 0;
                    break;
                }

                $fulldisc_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $fdRow = $fulldisc_m->find($id);
                if ($fdRow) {
                    $fc = $fdRow->file_category;
                    $yr = $fdRow->year;
                    $qtr = $fdRow->quarter;
                    $fulldisc_m->delete($id);
                    $message = 'Content deleted successfully.';
                    $log_c['processDetails'] = 'FULLDISC_ID: ' . $id . ' ' . $fc . ' ' . $yr . ' - ' . $qtr . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_contacts': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!$isCIO && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                $hot_m = new \App\Models\Hotlines();
                $id = $this->request->getPost('id');

                // Dept-scoped ADMIN can only delete contacts linked to their own department (CIO bypassed)
                if ($isDeptScopedAdmin && !$isCIO) {
                    $hotline = $hot_m->find($id);
                    if (!$hotline || $hotline->section !== 'Department' || (int) $hotline->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only delete contacts linked to your own department.';
                        $status = 0;
                        break;
                    }
                } elseif ($isBrgyScopedAdmin) {
                    $hotline = $hot_m->find($id);
                    if (!$hotline || $hotline->section !== 'Barangay' || (int) $hotline->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only delete contacts linked to your own barangay.';
                        $status = 0;
                        break;
                    }
                }
                if ($hot_m->find($id)) {
                    $hot_m->delete($id);
                    $message = 'Contact deleted successfully.';
                    $log_c['processDetails'] = 'CONTACT_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Contact not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_invest': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!$canManageInvest) {
                    $message = 'Unauthorized: Only the BPLO department can manage investment content.';
                    $status = 0;
                    break;
                }
                $invest_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $investRow = $invest_m->find($id);
                if ($investRow) {
                    $fc = $investRow->file_category;
                    $invest_m->delete($id);
                    $message = 'Investment content deleted successfully.';
                    $log_c['processDetails'] = 'INVEST_ID: ' . $id . ' ' . $fc . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_careers': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                // Only HRDO department and privileged roles can manage careers
                if (!$canManageCareers) {
                    $message = 'Unauthorized: Only the HRDO department can manage career postings.';
                    $status = 0;
                    break;
                }
                $career_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                if ($career_m->find($id)) {
                    $career_m->delete($id);
                    $message = 'Career deleted successfully.';
                    $log_c['processDetails'] = 'CAREER_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Career not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_dept': {
                if (!in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                // Dept-scoped ADMINs cannot delete departments at all
                if ($isDeptScopedAdmin) {
                    $message = 'Department accounts cannot delete departments.';
                    $status = 0;
                    break;
                }
                $dept_m = new \App\Models\Department();
                $id = $this->request->getPost('id');
                if ($dept_m->find($id)) {
                    $dept_m->delete($id);
                    $message = 'Department deleted successfully.';
                    $log_c['processDetails'] = 'DEPT_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Department not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_mayor': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!$canManageMayor) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                $may_m = new \App\Models\MayorContent();
                $id = $this->request->getPost('id');
                $mayorRecord = $may_m->find($id);
                if ($mayorRecord) {
                    $sect = $mayorRecord->section ?? '';
                    $may_m->delete($id);
                    $message = "Mayor's content deleted successfully.";
                    $log_c['processDetails'] = 'MAYOR_ID: ' . $id . ' SECTION: ' . $sect . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_postcontent': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                $isMayorRestricted = $isMayor && !$isCIO && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN']);
                if (!$isMayorRestricted && !in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                $con_m = new \App\Models\Content();
                $id = $this->request->getPost('id');

                if ($isMayorRestricted) {
                    $post = $con_m->find($id);
                    $deptNames = $this->getDepartmentUserNames($user);
                    if ($post && !in_array($post->author, $deptNames)) {
                        $message = 'Unauthorized: You can only delete your own created data.';
                        $status = 0;
                        break;
                    }
                }
                $postRecord = $con_m->find($id);
                if ($postRecord) {
                    $title = $postRecord->title ?? '';
                    $con_m->delete($id);
                    $message = 'Post content deleted successfully.';
                    $log_c['processDetails'] = 'POSTCONTENT_ID: ' . $id . ' TITLE: ' . $title . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_about': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!$canManageAbout) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                $about_m = new \App\Models\About();
                $id = $this->request->getPost('id');
                $aboutRecord = $about_m->find($id);
                if ($aboutRecord) {
                    $title = $aboutRecord->title ?? '';
                    $about_m->delete($id);
                    $message = 'About content deleted successfully.';
                    $log_c['processDetails'] = 'ABOUT_ID: ' . $id . ' TITLE: ' . $title . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_barangay': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts cannot delete barangays.';
                    $status = 0;
                    break;
                }
                // Only Developer and Superadmin can delete barangays
                if (!in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN'], true)) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }
                $brgy_m = new \App\Models\Barangay();
                $id = $this->request->getPost('id');
                if ($brgy_m->find($id)) {
                    $brgy_m->delete($id);
                    $message = 'Barangay deleted successfully.';
                    $log_c['processDetails'] = 'BRGY_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Barangay not found.';
                    $status = 0;
                }
                break;
            }

            case 'delete_services': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                $services_m = new \App\Models\Services();
                $id = $this->request->getPost('id');
                $svc = $services_m->find($id);

                if (!$svc) {
                    $message = 'Service not found.';
                    $status = 0;
                    break;
                }

                if (!$this->canAccessServiceRecord($svc, $user)) {
                    $message = 'Unauthorized: not your service.';
                    $status = 0;
                    break;
                }

                if ($services_m->delete($id)) {
                    $message = 'Service deleted successfully.';
                    $log_c['processDetails'] = 'SERVICE_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Failed to delete service.';
                    $status = 0;
                }
                break;
            }

            case 'delete_map': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    $status = 0;
                    break;
                }

                if (!in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'], true)) {
                    $message = 'Unauthorized access.';
                    $status = 0;
                    break;
                }

                $map_m = new \App\Models\Map();
                $id = $this->request->getPost('id');
                if ($map_m->find($id)) {
                    $map_m->delete($id);
                    $message = 'Map record deleted successfully.';
                    $log_c['processDetails'] = 'MAP_ID: ' . $id . ' - DELETED';
                    $status = 1;
                } else {
                    $message = 'Map record not found.';
                    $status = 0;
                }
                break;
            }

            case 'set_status_career': {
                // Only HRDO department and privileged roles can manage careers
                if (!$canManageCareers) {
                    $message = 'Unauthorized: Only the HRDO department can manage career postings.';
                    break;
                }
                $career_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');
                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $career_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'CAREER_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }
            case 'set_status_invest': {
                if (!$canManageInvest) {
                    $message = 'Unauthorized: Only the BPLO department can manage investment content.';
                    break;
                }
                $invest_m = new \App\Models\FileTbl();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');
                $investRow = $invest_m->find($id);
                if ($investRow) {
                    $fc = $investRow->file_category;
                    $data = [
                        'status' => $status,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];
                    $invest_m->update($id, $data);
                    $message = 'Content status updated successfully.';
                    $log_c['processDetails'] = 'INVEST_ID: ' . $id . ' ' . $fc . ' - ' . $status;
                    $status = 1;
                } else {
                    $message = 'Investment content not found.';
                    $status = 0;
                }
                break;
            }
            case 'set_status_services': {
                $invest_m = new \App\Models\Services();
                $id = $this->request->getPost('id');
                $statusVal = $this->request->getPost('status');

                $svc = $invest_m->find($id);
                if (!$svc) {
                    $message = 'Service not found.';
                    break;
                }

                if (!$this->canAccessServiceRecord($svc, $user)) {
                    $message = 'Unauthorized: not your service.';
                    break;
                }

                $data = [
                    'status' => $statusVal,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];
                $invest_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'SERVICE_ID: ' . $id . ' - ' . $statusVal;
                $status = 1;
                break;
            }
            case 'set_status_about': {
                if (!$canManageAbout) {
                    $message = 'Unauthorized access.';
                    break;
                }
                $about_m = new \App\Models\About();
                $id = $this->request->getPost('id');
                $statusVal = $this->request->getPost('status');
                $aboutRecord = $about_m->find($id);
                if ($aboutRecord) {
                    $title = $aboutRecord->title ?? '';
                    $data = [
                        'status' => $statusVal,
                        'updated_date' => date('Y-m-d H:i:s')
                    ];
                    $about_m->update($id, $data);
                    $message = 'Content status updated successfully.';
                    $log_c['processDetails'] = 'ABOUT_ID: ' . $id . ' TITLE: ' . $title . ' - ' . $statusVal;
                    $status = 1;
                } else {
                    $message = 'Content not found.';
                    $status = 0;
                }
                break;
            }
            case 'set_status_contact': {
                $invest_m = new \App\Models\Hotlines();
                $id = $this->request->getPost('id');
                $status = $this->request->getPost('status');

                // Scoped ADMIN ownership check (CIO bypassed)
                $hotline = $invest_m->find($id);
                if ($hotline && $isDeptScopedAdmin && !$isCIO) {
                    if ($hotline->section !== 'Department' || (int) $hotline->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only manage contacts linked to your own department.';
                        break;
                    }
                } elseif ($hotline && $isBrgyScopedAdmin) {
                    if ($hotline->section !== 'Barangay' || (int) $hotline->content_ref_id !== (int) $user->entity_ref_id) {
                        $message = 'You can only manage contacts linked to your own barangay.';
                        break;
                    }
                }

                $data = [
                    'status' => $status,
                    'updated_date' => date('Y-m-d H:i:s')
                ];
                $invest_m->update($id, $data);
                $message = 'Content status updated successfully.';
                $log_c['processDetails'] = 'CONTACT_ID: ' . $id . ' - ' . $status;
                $status = 1;
                break;
            }
            case 'set_status_job': {
                if (!$canManageJobs) {
                    $message = 'Unauthorized: Only the PESO department can manage jobs.';
                    break;
                }
                $job_m = new \App\Models\Job();
                $id = $this->request->getPost('id');
                $statusVal = $this->request->getPost('status');

                if (!$id || !is_numeric($id)) {
                    $message = 'Invalid job ID';
                    break;
                }

                if (!in_array($statusVal, ['ACTIVE', 'INACTIVE', 'ARCHIVED'])) {
                    $message = 'Invalid status value';
                    break;
                }

                // Check if job exists
                $job = $job_m->where('ID', $id)->first();
                if (!$job) {
                    $message = 'Job not found';
                    break;
                }

                $data = [
                    'status' => $statusVal,
                    'updated_date' => date('Y-m-d H:i:s'),
                ];

                try {
                    if ($job_m->update($id, $data)) {
                        $status = 1;
                        $message = 'Job status updated successfully';
                        $log_c['processDetails'] = 'JOB_ID: ' . $id . ' - ' . $statusVal;
                    } else {
                        $message = 'Failed to update job status';
                    }
                } catch (\Exception $e) {
                    $message = 'Database error: ' . $e->getMessage();
                }
                break;
            }
            case 'delete_job': {
                if ($isSpecialDeptAdmin) {
                    $message = 'Unauthorized access. Department accounts can archive records, but cannot delete them.';
                    break;
                }

                if (!$canManageJobs) {
                    $message = 'Unauthorized: Only the PESO department can manage jobs.';
                    break;
                }
                $job_m = new \App\Models\Job();
                $id = $this->request->getPost('id');

                if (!$id || !is_numeric($id)) {
                    $message = 'Invalid job ID';
                    break;
                }

                // Check if job exists
                $job = $job_m->where('ID', $id)->first();
                if (!$job) {
                    $message = 'Job not found';
                    break;
                }

                if ($job_m->delete($id)) {
                    $status = 1;
                    $message = 'Job deleted successfully';
                    $log_c['processDetails'] = 'JOB_ID: ' . $id . ' - DELETED';
                } else {
                    $message = 'Failed to delete job';
                }
                break;
            }

            default:
                $message = 'Invalid request';
                break;
        }

        if (!str_contains($mode, 'get_')) {
            $log_m->save($log_c);
        }
        echo json_encode([
            'status' => $status,
            'data' => $data ?? '',
            'message' => $message,
        ]);
    }


    public function image($category, $fileName)
    {
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            $fileInfo = getimagesize($filePath);
            $fileType = $fileInfo['mime'];

            header("Content-Type: $fileType");
            readfile($filePath);
            exit;
        } else {
            // If file does not exist, return a default image or an error message
            log_message('error', 'File not found: ' . $filePath);
            header("HTTP/1.0 404 Not Found");
            echo 'File not found.';
            exit;
        }
    }

    private function resolveAccountEntityName($accountType, $entityRef): ?string
    {
        if (empty($accountType) || empty($entityRef)) {
            return '';
        }

        if ($accountType === 'DEPARTMENT') {
            $department = (new \App\Models\Department())->find($entityRef);
            return $department ? (string) $department->dept_name : null;
        }

        if ($accountType === 'BARANGAY') {
            $barangay = (new \App\Models\Barangay())->find($entityRef);
            return $barangay ? (string) $barangay->brgy_name : null;
        }

        return '';
    }

    private function canAccessServiceRecord($service, $user): bool
    {
        if (!$service) {
            return false;
        }

        $accountType = $user->account_type ?? '';
        if ($accountType === 'DEPARTMENT') {
            return (int) $this->recordValue($service, 'dept_cont_ID') === (int) ($user->entity_ref_id ?? 0);
        }

        if ($accountType === 'BARANGAY') {
            return (int) $this->recordValue($service, 'brngy_cont_ID') === (int) ($user->entity_ref_id ?? 0);
        }

        return true;
    }

    private function getDepartmentUserNames($user): array
    {
        $names = [];
        $currentName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
        if ($currentName !== '') {
            $names[] = $currentName;
        }

        if (!empty($user->entity_ref_id) && ($user->account_type ?? '') === 'DEPARTMENT') {
            $user_m = new \App\Models\UserAccount();
            $deptUsers = $user_m->where('account_type', 'DEPARTMENT')
                                 ->where('entity_ref_id', $user->entity_ref_id)
                                 ->findAll();
            foreach ($deptUsers as $du) {
                $name = trim(($du->fname ?? '') . ' ' . ($du->lname ?? ''));
                if ($name !== '') {
                    $names[] = $name;
                }
            }
        }
        return array_unique(array_filter($names));
    }

    private function recordValue($record, string $field)
    {
        if (is_array($record)) {
            return $record[$field] ?? null;
        }

        if (is_object($record)) {
            return $record->{$field} ?? null;
        }

        return null;
    }

    private function normalizePhilippineMobileNumber($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if ($value === '+63 9') {
            return '';
        }

        $digits = preg_replace('/\D+/', '', $value);
        if ($digits === '') {
            return $value;
        }

        if (str_starts_with($digits, '63')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (!preg_match('/^9\d{9}$/', $digits)) {
            return $value;
        }

        return '+63 ' . substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6, 4);
    }

    private function normalizePhilippineLandlineNumber($value): string
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '(' || $value === '(0' || $value === '(02' || $value === '(049' || $value === '(02)' || $value === '(049)') {
            return '';
        }

        $digits = preg_replace('/\D+/', '', $value);
        if ($digits === '') {
            return $value;
        }

        if (str_starts_with($digits, '63') && strlen($digits) > 2) {
            $digits = substr($digits, 2);
        }

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '049')) {
            $subscriber = substr($digits, 3, 7);
            if ($subscriber === '') {
                return '';
            }
            return '(049) ' . substr($subscriber, 0, 3) . '-' . substr($subscriber, 3, 4);
        }

        if (str_starts_with($digits, '02')) {
            $subscriber = substr($digits, 2, 8);
            if ($subscriber === '') {
                return '';
            }
            return '(02) ' . substr($subscriber, 0, 4) . '-' . substr($subscriber, 4, 4);
        }

        return $value;
    }

    private function isValidPhilippineLandlineNumber($value): bool
    {
        $value = trim((string) $value);
        return (bool) preg_match('/^\(049\)\s\d{3}-\d{4}$/', $value) || (bool) preg_match('/^\(02\)\s\d{4}-\d{4}$/', $value);
    }

    private function isValidPhilippineMobileNumber($value): bool
    {
        return (bool) preg_match('/^\+63\s9\d{2}\s\d{3}\s\d{4}$/', trim((string) $value));
    }

    public function preview_file($category, $filename)
    {
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filePath)) {
            $fileType = mime_content_type($filePath);

            header("Content-Type: $fileType");
            readfile($filePath);
            exit;
        } else {
            log_message('error', 'File not found: ' . $filePath);
            header("HTTP/1.0 404     Not Found");
            echo 'File not found.';
            exit;
        }
    }

}
