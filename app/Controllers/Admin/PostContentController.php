<?php

namespace App\Controllers\Admin;

/**
 * PostContentController
 *
 * Handles CRUD for: postcontent, mayor, about content
 */
class PostContentController extends BaseAdminController
{
    // ── Post Content ─────────────────────────────────────────────────────

    public function createPostcontent(): \CodeIgniter\HTTP\ResponseInterface
    {
        $con_m  = new \App\Models\Content();
        $title  = $this->request->getPost('title');
        $author = $this->user->fname . ' ' . $this->user->lname;
        $desc   = $this->request->getPost('desc');
        $img    = $this->request->getFile('newsImg');
        $cat    = $this->request->getPost('content_category');

        $logoName = $img->getRandomName();
        $path     = WRITEPATH . 'uploads/POSTCONTENT';

        if (! $img->move($path, $logoName)) {
            return $this->jsonFail('Failed to upload file');
        }

        $data = [
            'title' => $title, 'author' => $author, 'description' => $desc,
            'file_loc' => $logoName, 'category' => $cat,
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];

        if ($con_m->insert($data)) {
            $this->audit('create_postcontent', 'TITLE: ' . $title);
            return $this->jsonSuccess('Post Content created successfully');
        }
        return $this->jsonFail('Failed to save data');
    }

    public function updatePostcontent(): \CodeIgniter\HTTP\ResponseInterface
    {
        $con_m = new \App\Models\Content();
        $id    = $this->request->getPost('id');
        $ne_dt = $con_m->find($id);

        if (! $ne_dt) return $this->jsonFail('Post Content not found.');

        $data = [
            'title'       => $this->request->getPost('editTitle'),
            'author'      => $this->user->fname . ' ' . $this->user->lname,
            'description' => $this->request->getPost('editDesc'),
            'category'    => $this->request->getPost('edit_content_category'),
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        $maxSize      = 4 * 1024 * 1024;
        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        $imgLogo      = $this->request->getFile('editNewsImg');

        if ($imgLogo && $imgLogo->isValid() && in_array($imgLogo->getMimeType(), $allowedTypes) && $imgLogo->getSize() <= $maxSize) {
            $logoName = $imgLogo->getRandomName();
            if (! $imgLogo->hasMoved() && $imgLogo->move(WRITEPATH . 'uploads/POSTCONTENT', $logoName)) {
                $data['file_loc'] = $logoName;
            }
        }

        try {
            $con_m->update($id, $data);
            $this->audit('update_postcontent', 'TITLE: ' . $data['title']);
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusPostcontent(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\Content(), 'POSTCONTENT_ID');
    }

    public function deletePostcontent(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\Content(), 'POSTCONTENT_ID', 'Post content');
    }

    // ── Mayor Content ────────────────────────────────────────────────────

    public function createMayor(): \CodeIgniter\HTTP\ResponseInterface
    {
        $may_m   = new \App\Models\MayorContent();
        $name    = $this->request->getPost('myrname');
        $section = $this->request->getPost('content_category');
        $content = $this->request->getPost('perdata');

        $existing = $may_m->where('section', $section)->first();
        if ($existing) return $this->jsonFail('Section already exists.');

        $data = [
            'mayor_name' => $name, 'section' => $section, 'content' => $content,
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];

        $imgs = $this->request->getFileMultiple('mayorimg');
        if ($imgs) {
            $uploaded = [];
            foreach ($imgs as $img) {
                if ($img->isValid() && ! $img->hasMoved() && $img->move(WRITEPATH . 'uploads/MAYOR', $img->getRandomName())) {
                    $uploaded[] = $img->getName();
                }
            }
            $data['mayor_img'] = json_encode($uploaded);
        }

        try {
            if ($may_m->insert($data)) {
                $this->audit('create_mayor', 'MAYOR_ID: ' . $may_m->getInsertID() . ' ' . $name);
                return $this->jsonSuccess('Content created successfully');
            }
            return $this->jsonFail('Failed to save data.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while saving.');
        }
    }

    public function updateMayor(): \CodeIgniter\HTTP\ResponseInterface
    {
        $may_m = new \App\Models\MayorContent();
        $id    = $this->request->getPost('id');
        $mc    = $may_m->find($id);

        if (! $mc) return $this->jsonFail('Content not found.');

        $name    = $this->request->getPost('editmyrname');
        $section = $this->request->getPost('edit_content_category');
        $content = $this->request->getPost('editperdata');

        $existing = $may_m->where('section', $section)->where('id !=', $id)->first();
        if ($existing) return $this->jsonFail('Section already exists.');

        $data = [
            'mayor_name' => $name, 'section' => $section, 'content' => $content,
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        $imgs = $this->request->getFileMultiple('editmayorimg');
        if ($imgs) {
            $uploaded = [];
            foreach ($imgs as $img) {
                if ($img->isValid() && ! $img->hasMoved() && $img->move(WRITEPATH . 'uploads/MAYOR', $img->getRandomName())) {
                    $uploaded[] = $img->getName();
                }
            }
            $data['mayor_img'] = json_encode($uploaded);
        } else {
            $data['mayor_img'] = $mc['mayor_img'];
        }

        try {
            $may_m->update($id, $data);
            $this->audit('update_mayor', 'MAYOR_ID: ' . $id . ' ' . $name);
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusMayor(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\MayorContent(), 'MAYOR_ID');
    }

    public function deleteMayor(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_delete(new \App\Models\MayorContent(), 'MAYOR_ID', "Mayor's content");
    }

    // ── About Content ────────────────────────────────────────────────────

    public function createAbout(): \CodeIgniter\HTTP\ResponseInterface
    {
        $about_m = new \App\Models\About();
        $section = $this->request->getPost('content_category');
        $title   = $this->request->getPost('TxtTitle');
        $desc    = $this->request->getPost('TxtDesc');
        $img     = $this->request->getFile('AboutImg');

        $existing = $about_m->where('section', $section)->first();
        if ($existing && $section === 'Home Page') return $this->jsonFail('Home Page content cannot be repeated.');

        $data = [
            'section' => $section, 'title' => $title, 'description' => $desc,
            'status' => 'ACTIVE', 'created_date' => date('Y-m-d H:i:s'),
        ];

        if ($img && $img->isValid() && ! $img->hasMoved()) {
            $logoName = $img->getRandomName();
            if ($img->move(WRITEPATH . 'uploads/ABOUT', $logoName)) {
                $data['about_img'] = $logoName;
            }
        }

        if ($about_m->insert($data)) {
            $this->audit('create_about', 'ABOUT_ID: ' . $about_m->getInsertID());
            return $this->jsonSuccess('Content created successfully');
        }
        return $this->jsonFail('Failed to save data');
    }

    public function updateAbout(): \CodeIgniter\HTTP\ResponseInterface
    {
        $about_m = new \App\Models\About();
        $id      = $this->request->getPost('id');
        if (! $about_m->find($id)) return $this->jsonFail('Content not found.');

        $data = [
            'section'      => $this->request->getPost('edit_content_category'),
            'title'        => $this->request->getPost('EditTxtTitle'),
            'description'  => $this->request->getPost('EditTxtDesc'),
            'updated_date' => date('Y-m-d H:i:s'),
        ];

        $img = $this->request->getFile('EditAboutImg');
        if ($img && $img->isValid() && $img->getSize() < 4 * 1024 * 1024) {
            $n = $img->getRandomName();
            if ($img->move(WRITEPATH . 'uploads/ABOUT', $n)) $data['about_img'] = $n;
        }

        try {
            $about_m->update($id, $data);
            $this->audit('update_about', 'ABOUT_ID: ' . $id);
            return $this->jsonSuccess('Content updated successfully.');
        } catch (\Exception $e) {
            return $this->jsonFail('An error occurred while updating.');
        }
    }

    public function setStatusAbout(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->_setStatus(new \App\Models\About(), 'ABOUT_ID');
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
        if (! $this->userCan(['DEVELOPER', 'SUPERADMIN', 'ADMIN'])) {
            return $this->jsonFail('Unauthorized access.');
        }
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
