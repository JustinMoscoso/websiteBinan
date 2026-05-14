<?php

namespace App\Controllers\Admin;

/**
 * EntityController
 *
 * Handles CRUD for: barangay, department, city officials, services
 */
class EntityController extends BaseAdminController
{
    // ── Barangay ─────────────────────────────────────────────────────────

    public function createBarangay(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\Barangay();
        $name = $this->request->getPost('txtBrgy');
        if ($m->where('brgy_name', $name)->first()) return $this->jsonFail('Barangay name already exists.');

        $img      = $this->request->getFile('brgyImg');
        $logoName = $img->getRandomName();
        $path     = WRITEPATH . 'uploads/BARANGAY';

        if (! $img->move($path, $logoName)) return $this->jsonFail('Failed to upload files');

        $data = [
            'brgy_name' => $name, 'brngy_capt' => $this->request->getPost('txtCapt'),
            'mission' => $this->request->getPost('txtMission'), 'vision' => $this->request->getPost('txtVision'),
            'about' => $this->request->getPost('createAbout'), 'contact' => $this->request->getPost('txtContact'),
            'barangay_staff' => $this->request->getPost('txtStaff'), 'img_logo' => $logoName,
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];

        if ($m->insert($data)) {
            $this->audit('create_barangay', 'BRGY_ID: ' . $m->getInsertID() . ' ' . $name);
            return $this->jsonSuccess('Barangay created successfully');
        }
        return $this->jsonFail('Failed to save data');
    }

    public function updateBarangay(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Barangay();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Barangay not found.');

        $name = $this->request->getPost('editBrgy');
        if ($m->where('brgy_name', $name)->where('id !=', $id)->first()) return $this->jsonFail('Barangay name already exists.');

        $data = [
            'contact' => $this->request->getPost('editContact'), 'about' => $this->request->getPost('editAbout'),
            'brgy_name' => $name, 'brngy_capt' => $this->request->getPost('editCapt'),
            'mission' => $this->request->getPost('editMission'), 'vision' => $this->request->getPost('editVision'),
            'barangay_staff' => $this->request->getPost('editStaff'), 'updated_date' => date('Y-m-d H:i:s'),
        ];

        $img = $this->request->getFile('editbrgyImg');
        if ($img && $img->isValid() && $img->getSize() < 4 * 1024 * 1024) {
            $n = $img->getRandomName();
            if ($img->move(WRITEPATH . 'uploads/BARANGAY', $n)) $data['img_logo'] = $n;
        }

        try {
            $m->update($id, $data);
            $this->audit('update_barangay', "BRGY_ID: $id");
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusBarangay(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Barangay(), 'BRGY_ID');
    }

    // ── Department ───────────────────────────────────────────────────────

    public function createDept(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\Department();
        $name = $this->request->getPost('txtDept');
        if ($m->where('dept_name', $name)->first()) return $this->jsonFail('Department name already exists.');

        $img      = $this->request->getFile('deptImg');
        $logoName = $img->getRandomName();
        $orgChart = $this->request->getFile('deptOrgChart');
        $orgName  = $orgChart ? $orgChart->getRandomName() : null;
        $path     = WRITEPATH . 'uploads/DEPT';

        if (! $img->move($path, $logoName)) return $this->jsonFail('Failed to upload files');

        $data = [
            'dept_name' => $name, 'head' => $this->request->getPost('txtHead'),
            'post_title' => $this->request->getPost('txtTitle'),
            'mission' => $this->request->getPost('txtMission'), 'vision' => $this->request->getPost('txtVision'),
            'img_logo' => $logoName, 'org_chart_img' => $orgName,
            'quality_policy' => $this->request->getPost('txtPolicy'),
            'about' => $this->request->getPost('txtAbout'), 'contact' => $this->request->getPost('txtContact'),
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];

        if ($m->insert($data)) {
            $this->audit('create_dept', 'DEPT_ID: ' . $m->getInsertID());
            return $this->jsonSuccess('Department created successfully');
        }
        return $this->jsonFail('Failed to save data');
    }

    public function updateDept(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Department();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Department not found.');

        $name = $this->request->getPost('editDept');
        if ($m->where('dept_name', $name)->where('id !=', $id)->first()) return $this->jsonFail('Department name already exists.');

        $data = [
            'dept_name' => $name, 'head' => $this->request->getPost('editHead'),
            'post_title' => $this->request->getPost('editTitle'),
            'mission' => $this->request->getPost('editMission'), 'vision' => $this->request->getPost('editVision'),
            'quality_policy' => $this->request->getPost('editPolicy'),
            'about' => $this->request->getPost('editAbout'), 'contact' => $this->request->getPost('editContact'),
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        $maxmb = 4;
        $img = $this->request->getFile('editdeptImg');
        if ($img && $img->isValid() && $img->getSize() < $maxmb * 1024 * 1024) {
            $n = $img->getRandomName();
            if (! $img->hasMoved() && $img->move(WRITEPATH . 'uploads/DEPT', $n)) $data['img_logo'] = $n;
        }
        $org = $this->request->getFile('editdeptOrgChart');
        if ($org && $org->isValid() && $org->getSize() < $maxmb * 1024 * 1024) {
            $n = $org->getRandomName();
            if (! $org->hasMoved() && $org->move(WRITEPATH . 'uploads/DEPT', $n)) $data['org_chart_img'] = $n;
        }

        try {
            $m->update($id, $data);
            $this->audit('update_dept', "DEPT_ID: $id");
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusDept(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Department(), 'DEPT_ID');
    }

    public function deleteDept(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\Department(), 'DEPT_ID', 'Department');
    }

    // ── City Officials ───────────────────────────────────────────────────

    public function createCityoff(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\CityOfficial();
        $name = $this->request->getPost('offname');
        $pos  = $this->request->getPost('offpos');
        $rank = $this->request->getPost('offrank');
        $img  = $this->request->getFile('offimg');

        if (! empty($rank) && $m->where('ranking', $rank)->countAllResults() > 0) {
            return $this->jsonFail('Ranking number already exists.');
        }
        if ($pos !== 'CITY COUNCILOR' && $m->where('off_position', $pos)->countAllResults() > 0) {
            return $this->jsonFail('The position already exists. Only "City Councilor" can repeat.');
        }

        $logoName = $img->getRandomName();
        $path     = WRITEPATH . 'uploads/CITYOFFICIAL/';
        if (! is_dir($path)) mkdir($path, 0777, true);

        $carouselNames = [];
        if ($img->move($path, $logoName)) {
            $files = $this->request->getFiles()['carouselimages'] ?? [];
            $count = 0;
            foreach ($files as $f) {
                if ($f->isValid() && ! $f->hasMoved() && $count < 3) {
                    $cn = $f->getRandomName();
                    if ($f->move($path, $cn)) { $carouselNames[] = $cn; $count++; }
                }
            }

            $data = [
                'off_name' => $name, 'off_position' => $pos, 'img_loc' => $logoName,
                'ranking' => empty($rank) ? null : $rank, 'status' => 'ACTIVE',
                'created_date' => date('Y-m-d H:i:s'),
                'years_of_service' => $this->request->getPost('years_of_service'),
                'personal_data' => $this->request->getPost('personal_data'),
                'awards' => $this->request->getPost('awards'),
                'carouselimages' => implode(',', $carouselNames),
            ];

            if ($m->insert($data)) {
                $this->audit('create_cityoff', 'CITYOFFICIAL_ID: ' . $m->getInsertID() . ' ' . $pos);
                return $this->jsonSuccess('City Official Info created successfully');
            }
            return $this->jsonFail('Failed to save data');
        }
        return $this->jsonFail('Failed to upload file');
    }

    public function updateCityoff(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\CityOfficial();
        $id = $this->request->getPost('id');
        $co = $m->find($id);
        if (! $co) return $this->jsonFail('City Official not found.');

        $name = $this->request->getPost('editoffname');
        $pos  = $this->request->getPost('editoffpos');
        $rank = $this->request->getPost('editoffrank');

        if (! empty($rank) && $m->where('ranking', $rank)->where('id !=', $id)->first()) {
            return $this->jsonFail('Ranking number already exists.');
        }
        if ($pos !== 'CITY COUNCILOR' && $m->where('off_position', $pos)->where('id !=', $id)->countAllResults() > 0) {
            return $this->jsonFail('The position already exists. Only "City Councilor" can repeat.');
        }

        $data = [
            'off_name' => $name, 'off_position' => $pos, 'ranking' => empty($rank) ? null : $rank,
            'years_of_service' => $this->request->getPost('edit_years_of_service'),
            'awards' => $this->request->getPost('edit_awards'),
            'personal_data' => $this->request->getPost('edit_personal_data'),
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        $maxmb      = 4;
        $uploadPath = WRITEPATH . 'uploads/CITYOFFICIAL/';
        if (! is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        $img = $this->request->getFile('editoffimg');
        if ($img && $img->isValid() && $img->getSize() < $maxmb * 1024 * 1024) {
            $n = $img->getRandomName();
            if (! $img->hasMoved() && $img->move($uploadPath, $n)) $data['img_loc'] = $n;
        }

        // Handle carousel images
        $existing = [];
        $ei = $this->request->getPost('existing_images');
        if (! empty($ei)) {
            $existing = array_filter(explode(',', $ei), 'trim');
        } elseif (! empty($co->carouselimages)) {
            $existing = array_filter(explode(',', $co->carouselimages), 'trim');
        }

        $newFiles = [];
        $files    = $this->request->getFileMultiple('editoffcaroimg');
        if (! empty($files)) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            foreach ($files as $f) {
                if ($f && $f->isValid() && $f->getSize() < $maxmb * 1024 * 1024 && in_array(strtolower($f->getClientExtension()), $allowed)) {
                    $fn = $f->getRandomName();
                    if (! $f->hasMoved() && $f->move($uploadPath, $fn)) $newFiles[] = $fn;
                }
            }
        }

        $combined = array_unique(array_merge($existing, $newFiles));
        if (count($combined) > 3) {
            return $this->jsonFail('Cannot exceed 3 carousel images.');
        }
        $data['carouselimages'] = ! empty($combined) ? implode(',', $combined) : null;

        try {
            $m->update($id, $data);
            $this->audit('update_cityoff', "CITYOFFICIAL_ID: $id $name");
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function removeCarouselImage(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m    = new \App\Models\CityOfficial();
        $id   = $this->request->getPost('id');
        $img  = $this->request->getPost('image');
        $co   = $m->find($id);

        if (! $co || ! $co->carouselimages) return $this->jsonFail('City official or carousel images not found.');

        $images = array_filter(explode(',', $co->carouselimages), 'trim');
        if (! in_array($img, $images)) return $this->jsonFail('Image not found in the carousel.');

        $images = array_values(array_diff($images, [$img]));

        // Delete file
        $fp = WRITEPATH . 'uploads/CITYOFFICIAL/' . $img;
        if (file_exists($fp)) @unlink($fp);

        $m->update($id, ['carouselimages' => ! empty($images) ? implode(',', $images) : null]);
        return $this->jsonSuccess('Image removed successfully.');
    }

    public function setStatusCityoff(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\CityOfficial(), 'CITYOFFICIAL_ID');
    }

    public function deleteCityoff(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\CityOfficial();
        $id = $this->request->getPost('id');
        if ($m->find($id)) {
            $m->delete($id);
            $this->audit('delete_cityoff', "CITYOFFICIAL_ID: $id - DELETED");
            return $this->jsonSuccess('City Official deleted successfully.');
        }
        return $this->jsonFail('City Official not found.');
    }

    // ── Services ─────────────────────────────────────────────────────────

    public function createServices(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m = new \App\Models\Services();
        $data = [
            'serv_name' => $this->request->getPost('serviceName'),
            'content' => $this->request->getPost('content'),
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];
        $dept = $this->request->getPost('txtDept');
        $brgy = $this->request->getPost('txtBrgy');
        if ($dept) $data['dept_cont_ID'] = $dept;
        elseif ($brgy) $data['brngy_cont_ID'] = $brgy;

        try {
            $m->save($data);
            $this->audit('create_services', 'SERVICE_ID: ' . $m->getInsertID());
            return $this->jsonSuccess('Service created successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving.');
        }
    }

    public function updateServices(): \CodeIgniter\HTTP\ResponseInterface
    {
        $m  = new \App\Models\Services();
        $id = $this->request->getPost('id');
        if (! $m->find($id)) return $this->jsonFail('Service not found.');

        $data = [
            'serv_name' => $this->request->getPost('editServiceName'),
            'content' => $this->request->getPost('editContent'),
            'updated_date' => date('Y-m-d H:i:s'),
        ];
        $dept = $this->request->getPost('editDept');
        $brgy = $this->request->getPost('editBrgy');
        if ($dept) { $data['dept_cont_ID'] = $dept; $data['brngy_cont_ID'] = null; }
        elseif ($brgy) { $data['brngy_cont_ID'] = $brgy; $data['dept_cont_ID'] = null; }

        try {
            $m->update($id, $data);
            $this->audit('update_services', "SERVICE_ID: $id");
            return $this->jsonSuccess('Service updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusServices(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Services(), 'SERVICE_ID');
    }

    // ── Shared helpers ───────────────────────────────────────────────────

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
