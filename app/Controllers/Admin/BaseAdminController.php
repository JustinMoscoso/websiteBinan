<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * BaseAdminController
 *
 * Shared foundation for all admin feature controllers.
 * Provides:
 *  - Session-based user resolution
 *  - Standard CORS + JSON headers for AJAX endpoints
 *  - Standard JSON success/fail response helpers
 *  - Audit log initialization
 */
abstract class BaseAdminController extends BaseController
{
    protected $session;
    protected $user;

    public function __construct()
    {
        helper('asset_helper');
        $this->session = \Config\Services::session();
        $this->user    = $this->session->get('user');
    }

    // -------------------------------------------------------------------------
    // Response Helpers
    // -------------------------------------------------------------------------

    /**
     * Set standard JSON response headers (CORS + Content-Type).
     */
    protected function setJsonHeaders(): void
    {
        $origin = ENVIRONMENT === 'production'
            ? 'https://' . $_SERVER['HTTP_HOST']
            : '*';

        $this->response
            ->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Content-Type', 'application/json');
    }

    /**
     * Return a successful JSON response.
     */
    protected function jsonSuccess(string $message, $data = null): \CodeIgniter\HTTP\ResponseInterface
    {
        $payload = ['status' => 1, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        return $this->response->setJSON($payload);
    }

    /**
     * Return a failure JSON response.
     */
    protected function jsonFail(string $message, int $httpStatus = 200): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response
            ->setStatusCode($httpStatus)
            ->setJSON(['status' => 0, 'message' => $message]);
    }

    // -------------------------------------------------------------------------
    // Audit Log Helper
    // -------------------------------------------------------------------------

    /**
     * Build a default audit log context for this request.
     */
    protected function initAuditLog(string $process): array
    {
        return [
            'userID'         => $this->user->ID ?? 0,
            'action'         => $process,
            'processDetails' => '',
            'ipaddress'      => $this->request->getIPAddress(),
            'created_date'   => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Check if the current user has one of the given levels.
     */
    protected function userCan(array $levels): bool
    {
        return in_array($this->user->user_lvl ?? '', $levels, true);
    }
}
