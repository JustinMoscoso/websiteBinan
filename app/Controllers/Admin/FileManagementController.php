<?php

namespace App\Controllers\Admin;

/**
 * FileManagementController
 *
 * Handles CRUD for: fulldiscpol, career, invest, contacts, map
 */
class FileManagementController extends BaseAdminController
{
    // ── Full Disclosure Policy ────────────────────────────────────────────

    public function createFulldiscpol(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\FileTbl();
        $fc   = $this->request->getPost('fileCategory');
        $yr   = $this->request->getPost('yr');
        $qtr  = $this->request->getPost('qtr');
        $file = $this->request->getFile('policyFile');

        if (! $file->isValid() || $file->hasMoved()) return $this->jsonFail('Failed to upload the file.');

        $fn = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/FULLDISC', $fn);

        $data = [
            'file_name' => $fn, 'file_category' => $fc, 'category' => 'FULLDISC',
            'created_date' => date('Y-m-d H:i:s'), 'quarter' => $qtr, 'status' => 'ACTIVE', 'year' => $yr,
        ];

        try {
            $m->save($data);
            $this->audit('create_fulldiscpol', 'FULLDISC_ID: ' . $m->getInsertID() . " $yr - $qtr");
            return $this->jsonSuccess('Policy created successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving the data.');
        }
    }

    public function updateFulldiscpol(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\FileTbl();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Policy not found.');

        $fc  = $this->request->getPost('editFileCategory');
        $yr  = $this->request->getPost('edityr');
        $qtr = $this->request->getPost('editqtr');
        $data = ['file_category' => $fc, 'updated_date' => date('Y-m-d H:i:s'), 'quarter' => $qtr, 'year' => $yr];

        $file = $this->request->getFile('editpolicyFile');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $fn = $file->getRandomName();
            if ($file->move(WRITEPATH . 'uploads/FULLDISC', $fn)) $data['file_name'] = $fn;
        }

        try {
            $m->update($id, $data);
            $this->audit('update_fulldiscpol', "FULLDISC_ID: $id $yr - $qtr");
            return $this->jsonSuccess('Policy updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusFulldiscpol(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\FileTbl(), 'FULLDISC_ID');
    }

    public function deleteFulldiscpol(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\FileTbl(), 'FULLDISC_ID', 'Content');
    }

    // ── Career ───────────────────────────────────────────────────────────

    public function createCareer(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\FileTbl();
        $pub  = $this->request->getPost('publication');
        $lvl  = $this->request->getPost('level');
        $file = $this->request->getFile('careerFile');

        if (! $file->isValid() || $file->hasMoved()) return $this->jsonFail('Failed to upload the file.');

        $fn = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/CAREERS', $fn);

        $data = [
            'file_name' => $fn, 'category' => 'CAREER', 'created_date' => date('Y-m-d H:i:s'),
            'publication_date' => $pub, 'status' => 'ACTIVE', 'level' => $lvl,
        ];

        try {
            $m->save($data);
            $this->audit('create_career', 'CAREER_ID: ' . $m->getInsertID());
            return $this->jsonSuccess('Career created successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving the data.');
        }
    }

    public function updateCareer(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\FileTbl();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Career not found.');

        $data = [
            'updated_date' => date('Y-m-d H:i:s'),
            'publication_date' => $this->request->getPost('editpublication'),
            'level' => $this->request->getPost('editlevel'),
        ];

        $file = $this->request->getFile('editCareerFile');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $fn = $file->getRandomName();
            if ($file->move(WRITEPATH . 'uploads/CAREERS', $fn)) $data['file_name'] = $fn;
        }

        try {
            $m->update($id, $data);
            $this->audit('update_career', "CAREER_ID: $id");
            return $this->jsonSuccess('Career updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusCareer(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\FileTbl(), 'CAREER_ID');
    }

    public function deleteCareers(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\FileTbl(), 'CAREER_ID', 'Career');
    }

    // ── Invest ───────────────────────────────────────────────────────────

    public function createInvest(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\FileTbl();
        $fc = $this->request->getPost('fileCategory');
        if ($m->where('file_category', $fc)->first()) return $this->jsonFail('File category already exists.');

        $file = $this->request->getFile('investFile');
        if (! $file->isValid() || $file->hasMoved()) return $this->jsonFail('Failed to upload the file.');

        $fn = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/INVEST', $fn);

        try {
            $m->save(['file_name' => $fn, 'file_category' => $fc, 'category' => 'INVEST', 'created_date' => date('Y-m-d H:i:s'), 'status' => 'ACTIVE']);
            $this->audit('create_invest', 'INVEST_ID: ' . $m->getInsertID());
            return $this->jsonSuccess('Content created successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving the data.');
        }
    }

    public function updateInvest(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\FileTbl();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Content not found.');

        $data = ['file_category' => $this->request->getPost('editFileCategory'), 'updated_date' => date('Y-m-d H:i:s')];

        $file = $this->request->getFile('editInvestFile');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $fn = $file->getRandomName();
            if ($file->move(WRITEPATH . 'uploads/INVEST', $fn)) $data['file_name'] = $fn;
            else return $this->jsonFail('Failed to upload the new file.');
        }

        try {
            $m->update($id, $data);
            $this->audit('update_invest', "INVEST_ID: $id");
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusInvest(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\FileTbl(), 'INVEST_ID');
    }

    public function deleteInvest(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\FileTbl(), 'INVEST_ID', 'Investment content');
    }

    // ── Contacts / Hotlines ──────────────────────────────────────────────

    public function createContact(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\Hotlines();
        $dept = $this->request->getPost('txtDept');
        $brgy = $this->request->getPost('txtBrgy');
        $oth  = $this->request->getPost('txtOthers');

        if ($dept) { $refId = $dept; $section = 'Department'; }
        elseif ($brgy) { $refId = $brgy; $section = 'Barangay'; }
        elseif ($oth) { $refId = $oth; $section = 'Others'; }
        else return $this->jsonFail('No entity selected.');

        if ($m->where('section', $section)->where('content_ref_id', $refId)->first()) {
            return $this->jsonFail('A hotline for this ' . strtolower($section) . ' already exists.');
        }

        $data = [
            'telco' => $this->request->getPost('telco'), 'number' => $this->request->getPost('contact'),
            'smart' => $this->request->getPost('smart'), 'globe' => $this->request->getPost('globe'),
            'status' => 'ACTIVE', 'section' => $section, 'content_ref_id' => $refId,
            'created_date' => date('Y-m-d H:i:s'),
        ];

        try {
            $m->save($data);
            $this->audit('create_contact', 'CONTACT_ID: ' . $m->getInsertID());
            return $this->jsonSuccess('Contact created successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving.');
        }
    }

    public function updateContact(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Hotlines();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Contact not found.');

        $dept = $this->request->getPost('editDept');
        $brgy = $this->request->getPost('editBrgy');
        $oth  = $this->request->getPost('editOthers');
        if ($dept) { $refId = $dept; $section = 'Department'; }
        elseif ($brgy) { $refId = $brgy; $section = 'Barangay'; }
        elseif ($oth) { $refId = $oth; $section = 'Others'; }
        else return $this->jsonFail('No entity selected.');

        $data = [
            'telco' => $this->request->getPost('editTelco'), 'number' => $this->request->getPost('editContact'),
            'smart' => $this->request->getPost('editSmart'), 'globe' => $this->request->getPost('editGlobe'),
            'status' => 'ACTIVE', 'section' => $section, 'content_ref_id' => $refId,
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        try {
            $m->update($id, $data);
            $this->audit('update_contact', "CONTACT_ID: $id");
            return $this->jsonSuccess('Contact updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusContact(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Hotlines(), 'CONTACT_ID');
    }

    public function deleteContacts(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\Hotlines(), 'CONTACT_ID', 'Contact');
    }

    // ── Map ──────────────────────────────────────────────────────────────

    public function createMap(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m = new \App\Models\Map();
        $topResult  = $this->_validatePercentage($this->request->getPost('top_loc'), 'Top Location');
        $leftResult = $this->_validatePercentage($this->request->getPost('left_loc'), 'Left Location');
        if ($topResult['status'] === 0) return $this->response->setJSON($topResult);
        if ($leftResult['status'] === 0) return $this->response->setJSON($leftResult);

        $data = [
            'brgy_name' => $this->request->getPost('brgy_name'),
            'top_loc' => $topResult['value'], 'left_loc' => $leftResult['value'],
            'details' => $this->request->getPost('details'), 'status' => 'Active',
        ];

        try {
            if ($m->insert($data)) return $this->jsonSuccess('Map record created');
            return $this->jsonFail('Failed to create map record.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred: ' . $e->getMessage());
        }
    }

    public function getMap(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Map();
        $id = $this->request->getPost('id');
        if ($id) {
            $r = $m->find($id);
            return $r ? $this->response->setJSON(['status' => 1, 'data' => $r])
                      : $this->jsonFail('Map record not found');
        }
        return $this->response->setJSON(['status' => 1, 'data' => $m->orderBy('brgy_name', 'desc')->findAll()]);
    }

    public function getMapDetails(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Map();
        $id = $this->request->getPost('id');
        if (! $id) return $this->jsonFail('Invalid request. Missing ID.');
        $r = $m->where('ID', $id)->first();
        return $r ? $this->response->setJSON(['status' => 1, 'msg' => 'Record found!', 'data' => $r])
                  : $this->jsonFail('Record not found.');
    }

    public function updateMapRecord(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Map();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Record not found.');

        $topResult  = $this->_validatePercentage($this->request->getPost('top_loc'), 'Top Location');
        $leftResult = $this->_validatePercentage($this->request->getPost('left_loc'), 'Left Location');
        if ($topResult['status'] === 0) return $this->response->setJSON($topResult);
        if ($leftResult['status'] === 0) return $this->response->setJSON($leftResult);

        $data = [
            'brgy_name' => $this->request->getPost('brgy_name'),
            'top_loc' => $topResult['value'], 'left_loc' => $leftResult['value'],
            'details' => $this->request->getPost('details'),
        ];

        try {
            $m->update($id, $data);
            return $this->jsonSuccess('Record updated successfully!');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusMap(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Map(), 'MAP_ID');
    }

    // ── Shared helpers ───────────────────────────────────────────────────

    private function _validatePercentage($value, $fieldName): array
    {
        $v = trim($value ?? '');
        if (empty($v) || strpos($v, '%') === false) return ['status' => 0, 'message' => "$fieldName must include a '%'."];
        $n = str_replace('%', '', $v);
        if (! is_numeric($n) || $n < 0 || $n > 100) return ['status' => 0, 'message' => "$fieldName must be 0-100."];
        return ['status' => 1, 'value' => $n . '%'];
    }

    protected function _setStatus($model, string $logKey): \CodeIgniter\HTTP\ResponseInterface
    {
        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $model->update($id, ['status' => $status, 'updated_date' => date('Y-m-d H:i:s')]);
        $this->audit('set_status', "$logKey: $id - $status");
        return $this->jsonSuccess('Content status updated successfully.');
    }

    protected function _delete($model, string $logKey, string $label): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->userCan(['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) return $this->jsonFail('Unauthorized access.');
        $id = $this->request->getPost('id');
        if ($model->find($id)) {
            $model->delete($id);
            $this->audit('delete', "$logKey: $id - DELETED");
            return $this->jsonSuccess("$label deleted successfully.");
        }
        return $this->jsonFail("$label not found.");
    }

    protected function audit(string $process, string $details): void
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
