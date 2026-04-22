<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileFileUploadService
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload and compress avatar/logo image
     */
    public function uploadAvatar(UploadedFile $file, string $directory, ?string $oldFilePath = null): string
    {
        $this->deleteOldFilePublic($oldFilePath);

        $filename = Str::uuid() . '-' . time() . '.webp';
        $path = $directory . '/' . $filename;

        // Compress and convert to webp using Intervention Image v4
        $image = $this->imageManager->read($file->getRealPath());
        $image->scale(width: 400); // Scale keeping aspect ratio
        
        $encodedImage = $image->toWebp(quality: 80);
        
        Storage::disk('public')->put($path, (string) $encodedImage);

        return Storage::url($path);
    }

    /**
     * Upload secure documents (KTP, SIM, certificates)
     * Stored in private storage disk.
     */
    public function uploadSecureDocument(UploadedFile $file, string $directory, ?string $oldFilePath = null): string
    {
        $this->deleteOldFilePrivate($oldFilePath);

        $filename = Str::uuid() . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'local'); // 'local' is private

        return $path;
    }

    /**
     * Delete file from public disk
     */
    public function deleteOldFilePublic(?string $url): void
    {
        if ($url) {
            $path = str_replace('/storage/', '', $url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Delete file from private disk
     */
    public function deleteOldFilePrivate(?string $path): void
    {
        if ($path && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }
}
