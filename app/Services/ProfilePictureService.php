<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

class ProfilePictureService
{
    private const UPLOAD_CATEGORY = 'PROFILE';
    private const MAX_SIZE = 2097152;
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function store(?UploadedFile $file, ?string $currentFilename = null): array
    {
        if (!$file || !$file->isValid()) {
            return [
                'status' => false,
                'message' => 'Please choose a valid profile picture.',
            ];
        }

        if ($file->hasMoved()) {
            return [
                'status' => false,
                'message' => 'The selected profile picture has already been processed.',
            ];
        }

        if ($file->getSize() > self::MAX_SIZE) {
            return [
                'status' => false,
                'message' => 'Profile picture must not exceed 2 MB.',
            ];
        }

        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
            return [
                'status' => false,
                'message' => 'Profile picture must be a PNG, JPG, JPEG, or WEBP image.',
            ];
        }

        $uploadPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . self::UPLOAD_CATEGORY;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = $file->getRandomName();
        if (!$file->move($uploadPath, $filename)) {
            return [
                'status' => false,
                'message' => 'Unable to save the profile picture.',
            ];
        }

        $this->deleteExisting($uploadPath, $currentFilename);

        return [
            'status' => true,
            'filename' => $filename,
            'message' => 'Profile picture saved.',
        ];
    }

    private function deleteExisting(string $uploadPath, ?string $currentFilename): void
    {
        if (!$currentFilename || basename($currentFilename) !== $currentFilename) {
            return;
        }

        $currentPath = $uploadPath . DIRECTORY_SEPARATOR . $currentFilename;
        if (is_file($currentPath)) {
            unlink($currentPath);
        }
    }
}
