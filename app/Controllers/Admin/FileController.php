<?php

namespace App\Controllers\Admin;

use App\Models\CityOfficial;

/**
 * FileController
 *
 * Handles file serving for the admin panel:
 *  - image()             — serve uploaded images by category/filename
 *  - previewFile()       — serve any file (PDF, doc, image) for inline preview
 *  - getOfficialDetails() — JSON: return full city official record for modal
 */
class FileController extends BaseAdminController
{
    /**
     * Serve an uploaded image file.
     * GET admin/image/{category}/{filename}
     *
     * Example: admin/image/POSTCONTENT/abc123.jpg
     */
    public function image(string $category, string $fileName)
    {
        // Sanitize path components to prevent directory traversal
        $category = basename($category);
        $fileName = basename($fileName);

        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $fileName;

        if (! file_exists($filePath)) {
            log_message('error', '[FileController::image] File not found: ' . $filePath);
            $this->response->setStatusCode(404);
            echo 'File not found.';
            return;
        }

        $fileInfo = @getimagesize($filePath);
        $mimeType = $fileInfo['mime'] ?? 'application/octet-stream';

        header('Content-Type: ' . $mimeType);
        header('Cache-Control: public, max-age=86400'); // cache for 1 day
        readfile($filePath);
        exit;
    }

    /**
     * Serve any uploaded file (PDF, image, etc.) for inline preview.
     * GET admin/preview_file/{category}/{filename}
     */
    public function previewFile(string $category, string $filename)
    {
        // Sanitize path components to prevent directory traversal
        $category = basename($category);
        $filename = basename($filename);

        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $filename;

        if (! file_exists($filePath)) {
            log_message('error', '[FileController::previewFile] File not found: ' . $filePath);
            $this->response->setStatusCode(404);
            echo 'File not found.';
            return;
        }

        $mimeType = mime_content_type($filePath);

        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    }

    /**
     * Return full details for a city official (for the public modal).
     * GET admin/official/{id}
     */
    public function getOfficialDetails(int $id)
    {
        $official = (new CityOfficial())->getOfficialById($id);

        return $official
            ? $this->response->setJSON($official)
            : $this->response->setStatusCode(404)->setJSON(['error' => 'Official not found']);
    }
}
