<?php
declare(strict_types=1);

namespace App\Domain\Service;

use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

class FileService
{
    private array $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
    private array $allowedVideoTypes = ['mp4', 'mov', 'avi'];
    private array $allowedDocumentTypes = ['pdf', 'doc', 'docx', 'txt'];

    private string $uploadPath;
    private int $maxFileSize;

    public function __construct()
    {
        $this->uploadPath = $_ENV['UPLOAD_PATH'] ?? 'public/uploads';
        $this->maxFileSize = (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 10485760); // 10MB default
    }

    public function upload(UploadedFileInterface $file, int $propertyId, string $type): array
    {
        // Validate file
        $this->validateFile($file, $type);

        // Generate file path
        $filename = $file->getClientFilename();
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $uuid = Uuid::uuid4()->toString();
        $year = date('Y');
        $month = date('m');

        $relativePath = sprintf(
            '%s/%d/%s/%s/%s.%s',
            $type,
            $propertyId,
            $year,
            $month,
            $uuid,
            $extension
        );

        $fullPath = $this->uploadPath . '/' . $relativePath;

        // Create directory if not exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // Move uploaded file
        $file->moveTo($fullPath);

        return [
            'file_path' => 'uploads/' . $relativePath,
            'file_name' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMediaType() ?? mime_content_type($fullPath)
        ];
    }

    public function delete(string $filePath): bool
    {
        $fullPath = str_replace('uploads/', $this->uploadPath . '/', $filePath);

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    private function validateFile(UploadedFileInterface $file, string $type): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException(
                sprintf('File size exceeds maximum allowed size of %d bytes', $this->maxFileSize)
            );
        }

        // Check file extension
        $filename = $file->getClientFilename();
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $allowedExtensions = match($type) {
            'image' => $this->allowedImageTypes,
            'video' => $this->allowedVideoTypes,
            'document' => $this->allowedDocumentTypes,
            default => throw new \InvalidArgumentException("Invalid file type: {$type}")
        };

        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException(
                sprintf('File extension "%s" is not allowed for type "%s"', $extension, $type)
            );
        }

        // Check upload error
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload failed with error code: ' . $file->getError());
        }
    }

    public function getTypeFromExtension(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($extension, $this->allowedImageTypes)) {
            return 'image';
        }

        if (in_array($extension, $this->allowedVideoTypes)) {
            return 'video';
        }

        if (in_array($extension, $this->allowedDocumentTypes)) {
            return 'document';
        }

        throw new \InvalidArgumentException("Unsupported file extension: {$extension}");
    }
}
