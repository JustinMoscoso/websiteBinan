<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin — Thin Facade Controller
 *
 * This controller delegates all work to feature-specific controllers
 * under App\Controllers\Admin\*. It exists solely for backward compatibility
 * with the existing frontend that calls:
 *   - admin/{mode}         → DashboardController::mode()
 *   - admin/ajax/{mode}    → dispatched to the appropriate feature controller
 *   - admin/image/...      → FileController::image()
 *   - admin/preview_file/… → FileController::previewFile()
 *
 * Previously this was a 3,188-line "God class" with a giant switch statement.
 * Now each case is a dedicated method on a focused controller.
 */
class Admin extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper('asset_helper');
        $this->session = \Config\Services::session();
    }

    /**
     * Create and initialize a child controller so it has access
     * to the current request, response, and logger objects.
     */
    private function delegate(string $class): object
    {
        $controller = new $class();
        $controller->initController(
            $this->request,
            $this->response,
            \Config\Services::logger()
        );
        return $controller;
    }

    // ── Page Routing ─────────────────────────────────────────────────────

    public function mode($mode = 'dashboard')
    {
        return $this->delegate(\App\Controllers\Admin\DashboardController::class)->mode($mode);
    }

    // ── Dashboard AJAX endpoints ─────────────────────────────────────────

    public function getUserCount()
    {
        return $this->delegate(\App\Controllers\Admin\DashboardController::class)->getUserCount();
    }

    public function getVisitCount()
    {
        return $this->delegate(\App\Controllers\Admin\DashboardController::class)->getVisitCount();
    }

    public function getRecentNews()
    {
        return $this->delegate(\App\Controllers\Admin\DashboardController::class)->getRecentNews();
    }

    public function getRecentAnns()
    {
        return $this->delegate(\App\Controllers\Admin\DashboardController::class)->getRecentAnns();
    }

    public function logVisit($page_url = '')
    {
        // No-op: handled by VisitCounter filter
    }

    // ── File serving ─────────────────────────────────────────────────────

    public function image($category, $fileName)
    {
        return $this->delegate(\App\Controllers\Admin\FileController::class)->image($category, $fileName);
    }

    public function preview_file($category, $filename)
    {
        return $this->delegate(\App\Controllers\Admin\FileController::class)->previewFile($category, $filename);
    }

    public function getOfficialDetails($id)
    {
        return $this->delegate(\App\Controllers\Admin\FileController::class)->getOfficialDetails($id);
    }

    // ── Master AJAX dispatcher ───────────────────────────────────────────

    /**
     * Maps old switch-case names → [ControllerClass, method]
     */
    private function getDispatchMap(): array
    {
        $user    = \App\Controllers\Admin\UserController::class;
        $job     = \App\Controllers\Admin\JobController::class;
        $post    = \App\Controllers\Admin\PostContentController::class;
        $entity  = \App\Controllers\Admin\EntityController::class;
        $fileMgr = \App\Controllers\Admin\FileManagementController::class;

        return [
            // Users
            'get_users'         => [$user, 'getUsers'],
            'get_user'          => [$user, 'getUser'],
            'create_user'       => [$user, 'createUser'],
            'update_user'       => [$user, 'updateUser'],
            'set_status_user'   => [$user, 'setStatusUser'],
            'reset_password'    => [$user, 'resetPassword'],

            // Jobs
            'get_jobs'          => [$job, 'getJobs'],
            'get_job'           => [$job, 'getJob'],
            'create_job'        => [$job, 'createJob'],
            'update_job'        => [$job, 'updateJob'],
            'set_status_job'    => [$job, 'setStatusJob'],
            'delete_job'        => [$job, 'deleteJob'],

            // Post Content
            'create_postcontent'     => [$post, 'createPostcontent'],
            'update_postcontent'     => [$post, 'updatePostcontent'],
            'set_status_postcontent' => [$post, 'setStatusPostcontent'],
            'delete_postcontent'     => [$post, 'deletePostcontent'],

            // Mayor
            'create_mayor'      => [$post, 'createMayor'],
            'update_mayor'      => [$post, 'updateMayor'],
            'set_status_mayor'  => [$post, 'setStatusMayor'],
            'delete_mayor'      => [$post, 'deleteMayor'],

            // About
            'create_about'      => [$post, 'createAbout'],
            'update_about'      => [$post, 'updateAbout'],
            'set_status_about'  => [$post, 'setStatusAbout'],

            // Barangay
            'create_barangay'       => [$entity, 'createBarangay'],
            'update_barangay'       => [$entity, 'updateBarangay'],
            'set_status_barangay'   => [$entity, 'setStatusBarangay'],

            // Department
            'create_dept'       => [$entity, 'createDept'],
            'update_dept'       => [$entity, 'updateDept'],
            'set_status_dept'   => [$entity, 'setStatusDept'],
            'delete_dept'       => [$entity, 'deleteDept'],

            // City Officials
            'create_cityoff'        => [$entity, 'createCityoff'],
            'update_cityoff'        => [$entity, 'updateCityoff'],
            'set_status_cityoff'    => [$entity, 'setStatusCityoff'],
            'delete_cityoff'        => [$entity, 'deleteCityoff'],
            'remove_carousel_image' => [$entity, 'removeCarouselImage'],

            // Services
            'create_services'       => [$entity, 'createServices'],
            'update_services'       => [$entity, 'updateServices'],
            'set_status_services'   => [$entity, 'setStatusServices'],

            // Full Disclosure Policy
            'create_fulldiscpol'     => [$fileMgr, 'createFulldiscpol'],
            'update_fulldiscpol'     => [$fileMgr, 'updateFulldiscpol'],
            'set_status_fulldiscpol' => [$fileMgr, 'setStatusFulldiscpol'],
            'delete_fulldiscpol'     => [$fileMgr, 'deleteFulldiscpol'],

            // Career
            'create_career'     => [$fileMgr, 'createCareer'],
            'update_career'     => [$fileMgr, 'updateCareer'],
            'set_status_career' => [$fileMgr, 'setStatusCareer'],
            'delete_careers'    => [$fileMgr, 'deleteCareers'],

            // Invest
            'create_invest'     => [$fileMgr, 'createInvest'],
            'update_invest'     => [$fileMgr, 'updateInvest'],
            'set_status_invest' => [$fileMgr, 'setStatusInvest'],
            'delete_invest'     => [$fileMgr, 'deleteInvest'],

            // Contacts
            'create_contact'     => [$fileMgr, 'createContact'],
            'update_contact'     => [$fileMgr, 'updateContact'],
            'set_status_contact' => [$fileMgr, 'setStatusContact'],
            'delete_contacts'    => [$fileMgr, 'deleteContacts'],

            // Map
            'create_map'        => [$fileMgr, 'createMap'],
            'get_map'           => [$fileMgr, 'getMap'],
            'get_map_details'   => [$fileMgr, 'getMapDetails'],
            'update_map_record' => [$fileMgr, 'updateMapRecord'],
            'set_status_map'    => [$fileMgr, 'setStatusMap'],
        ];
    }

    /**
     * Main AJAX endpoint — replaces the old 3000+ line switch statement.
     *
     * POST admin/ajax/{mode}
     */
    public function ajax($mode)
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 0, 'message' => 'Bad request']);
        }

        $map = $this->getDispatchMap();

        if (! isset($map[$mode])) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Invalid request', 'data' => '']);
        }

        [$controllerClass, $method] = $map[$mode];

        $controller = $this->delegate($controllerClass);
        return $controller->$method();
    }
}