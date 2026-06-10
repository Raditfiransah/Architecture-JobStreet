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
     * Upload and compress banner/cover image (landscape, wider crop)
     */
    public function uploadBanner(UploadedFile $file, string $directory, ?string $oldFilePath = null): string
    {
        $this->deleteOldFilePublic($oldFilePath);

        if (function_exists('imagewebp')) {
            $filename = Str::uuid() . '-' . time() . '.webp';
            $path     = $directory . '/' . $filename;

            $image = $this->imageManager->decodePath($file->getRealPath());
            $image->cover(1200, 400); // landscape crop
            $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 82));
            Storage::disk('public')->put($path, (string) $encoded);
        } elseif (function_exists('imagejpeg')) {
            $filename = Str::uuid() . '-' . time() . '.jpg';
            $path     = $directory . '/' . $filename;

            $image = $this->imageManager->decodePath($file->getRealPath());
            $image->cover(1200, 400);
            $encoded = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: 82));
            Storage::disk('public')->put($path, (string) $encoded);
        } else {
            $filename = Str::uuid() . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs($directory, $filename, 'public');
        }

        return Storage::url($path);
    }

    /**
     * Upload and compress avatar/logo image
     */
    public function uploadAvatar(UploadedFile $file, string $directory, ?string $oldFilePath = null): string
    {
        $this->deleteOldFilePublic($oldFilePath);

        // Cek dukungan format gambar di library GD server
        if (function_exists('imagewebp')) {
            $filename = Str::uuid() . '-' . time() . '.webp';
            $path = $directory . '/' . $filename;

            // Compress and convert to webp using Intervention Image v4
            $image = $this->imageManager->decodePath($file->getRealPath());
            $image->scale(width: 400); // Scale keeping aspect ratio
            $encodedImage = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 80));
            Storage::disk('public')->put($path, (string) $encodedImage);
        } elseif (function_exists('imagejpeg')) {
            $filename = Str::uuid() . '-' . time() . '.jpg';
            $path = $directory . '/' . $filename;

            // Compress and convert to jpeg using Intervention Image v4
            $image = $this->imageManager->decodePath($file->getRealPath());
            $image->scale(width: 400); // Scale keeping aspect ratio
            $encodedImage = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(quality: 80));
            Storage::disk('public')->put($path, (string) $encodedImage);
        } else {
            // Fallback total: simpan file asli langsung tanpa manipulasi gambar jika GD tidak didukung penuh
            $filename = Str::uuid() . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($directory, $filename, 'public');
        }

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
