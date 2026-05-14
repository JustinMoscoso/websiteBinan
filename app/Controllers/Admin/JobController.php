<?php

namespace App\Controllers\Admin;

use App\Models\Job as JobModel;
use App\Models\Audit;

/**
 * JobController
 *
 * Handles all AJAX operations for job management:
 *   GET  cases: get_jobs, get_job
 *   POST cases: create_job, update_job, set_status_job, delete_job
 */
class JobController extends BaseAdminController
{
    private JobModel $jobModel;

    public function __construct()
    {
        parent::__construct();
        $this->jobModel = new JobModel();
    }

    // -------------------------------------------------------------------------
    // GET
    // -------------------------------------------------------------------------

    /**
     * Return all jobs (with department name).
     * AJAX GET admin/ajax/get_jobs
     */
    public function getJobs(): \CodeIgniter\HTTP\ResponseInterface
    {
        $jobs = $this->jobModel
            ->select('jobs.*, department_content.dept_name as company_name')
            ->join('department_content', 'department_content.ID = jobs.company', 'left')
            ->orderBy('jobs.created_date', 'DESC')
            ->findAll();

        return $this->response->setJSON(['status' => 1, 'data' => $jobs]);
    }

    /**
     * Return a single job by ID.
     * AJAX POST admin/ajax/get_job
     */
    public function getJob(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id  = $this->request->getPost('id');
        $job = $this->jobModel
            ->select('jobs.*, department_content.dept_name as company_name')
            ->join('department_content', 'department_content.ID = jobs.company', 'left')
            ->where('jobs.ID', $id)
            ->first();

        return $job
            ? $this->response->setJSON(['status' => 1, 'data' => $job])
            : $this->jsonFail('Job not found.');
    }

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------

    /**
     * Create a new job posting.
     * AJAX POST admin/ajax/create_job
     */
    public function createJob(): \CodeIgniter\HTTP\ResponseInterface
    {
        $title            = trim($this->request->getPost('title'));
        $description      = trim($this->request->getPost('description'));
        $company          = $this->request->getPost('company');
        $type             = $this->request->getPost('type');
        $publication_date = $this->request->getPost('publication_date');
        $email            = trim($this->request->getPost('email'));

        if (empty($title) || empty($description) || empty($company) || empty($type) || empty($publication_date) || empty($email)) {
            return $this->jsonFail('All fields are required.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonFail('Please enter a valid email address.');
        }

        if (strtotime($publication_date) > time()) {
            return $this->jsonFail('Publication date cannot be in the future.');
        }

        $jobData = [
            'title'            => $title,
            'description'      => $description,
            'company'          => $company,
            'type'             => $type,
            'publication_date' => $publication_date,
            'email'            => $email,
            'status'           => 'ACTIVE',
            'created_date'     => date('Y-m-d H:i:s'),
            'updated_date'     => date('Y-m-d H:i:s'),
        ];

        try {
            if ($this->jobModel->insert($jobData)) {
                $newId = $this->jobModel->getInsertID();
                $this->saveAuditLog('create_job', "JOB_ID: $newId $title");
                return $this->jsonSuccess('Job created successfully.');
            }

            $errors = $this->jobModel->errors();
            return $this->jsonFail(! empty($errors)
                ? 'Validation errors: ' . implode(', ', $errors)
                : 'Failed to create job. Please check your input and try again.');
        } catch (\Exception $e) {
            log_message('error', '[create_job] ' . $e->getMessage());
            return $this->jsonFail('An error occurred. Please try again.');
        }
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------

    /**
     * Update an existing job posting.
     * AJAX POST admin/ajax/update_job
     */
    public function updateJob(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id               = $this->request->getPost('id');
        $title            = trim($this->request->getPost('title'));
        $description      = trim($this->request->getPost('description'));
        $company          = $this->request->getPost('company');
        $type             = $this->request->getPost('type');
        $publication_date = $this->request->getPost('publication_date');
        $email            = trim($this->request->getPost('email'));
        $status           = $this->request->getPost('status');

        if (! $id || ! is_numeric($id)) {
            return $this->jsonFail('Invalid job ID.');
        }

        if (empty($title) || empty($description) || empty($company) || empty($publication_date) || empty($email)) {
            return $this->jsonFail('All fields are required.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonFail('Please enter a valid email address.');
        }

        if (strtotime($publication_date) > time()) {
            return $this->jsonFail('Publication date cannot be in the future.');
        }

        if (! $this->jobModel->where('ID', $id)->first()) {
            return $this->jsonFail('Job not found.');
        }

        $jobData = [
            'title'            => $title,
            'description'      => $description,
            'company'          => $company,
            'type'             => $type,
            'publication_date' => $publication_date,
            'email'            => $email,
            'updated_date'     => date('Y-m-d H:i:s'),
        ];

        if ($status !== null) {
            $jobData['status'] = $status;
        }

        try {
            if ($this->jobModel->update($id, $jobData)) {
                $this->saveAuditLog('update_job', "JOB_ID: $id");
                return $this->jsonSuccess('Job updated successfully.');
            }

            $errors = $this->jobModel->errors();
            return $this->jsonFail(! empty($errors)
                ? 'Validation errors: ' . implode(', ', $errors)
                : 'Failed to update job. Please check your input and try again.');
        } catch (\Exception $e) {
            log_message('error', '[update_job] ' . $e->getMessage());
            return $this->jsonFail('An error occurred. Please try again.');
        }
    }

    // -------------------------------------------------------------------------
    // STATUS / DELETE
    // -------------------------------------------------------------------------

    /**
     * Set job status ACTIVE or INACTIVE.
     * AJAX POST admin/ajax/set_status_job
     */
    public function setStatusJob(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id        = $this->request->getPost('id');
        $statusVal = $this->request->getPost('status');

        if (! $id || ! is_numeric($id)) {
            return $this->jsonFail('Invalid job ID.');
        }

        if (! in_array($statusVal, ['ACTIVE', 'INACTIVE'], true)) {
            return $this->jsonFail('Invalid status value.');
        }

        if (! $this->jobModel->where('ID', $id)->first()) {
            return $this->jsonFail('Job not found.');
        }

        $this->jobModel->update($id, [
            'status'       => $statusVal,
            'updated_date' => date('Y-m-d H:i:s'),
        ]);

        $this->saveAuditLog('set_status_job', "JOB_ID: $id - $statusVal");
        return $this->jsonSuccess('Job status updated successfully.');
    }

    /**
     * Delete a job posting.
     * AJAX POST admin/ajax/delete_job
     */
    public function deleteJob(): \CodeIgniter\HTTP\ResponseInterface
    {
        $id = $this->request->getPost('id');

        if (! $id || ! is_numeric($id)) {
            return $this->jsonFail('Invalid job ID.');
        }

        if (! $this->jobModel->where('ID', $id)->first()) {
            return $this->jsonFail('Job not found.');
        }

        if ($this->jobModel->delete($id)) {
            $this->saveAuditLog('delete_job', "JOB_ID: $id - DELETED");
            return $this->jsonSuccess('Job deleted successfully.');
        }

        return $this->jsonFail('Failed to delete job.');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function saveAuditLog(string $process, string $details): void
    {
        try {
            $log = $this->initAuditLog($process);
            $log['processDetails'] = $details;
            (new \App\Models\Audit())->save($log);
        } catch (\Exception $e) {
            log_message('error', '[AuditLog] ' . $e->getMessage());
        }
    }
}
