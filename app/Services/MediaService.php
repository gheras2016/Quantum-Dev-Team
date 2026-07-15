<?php

namespace App\Services;

use App\Models\Media;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;

/**
 * Handles storing uploaded files and attaching Media records to any model.
 * Shared by every admin service instead of duplicating upload logic.
 *
 * Raster images are automatically resized and converted to WebP to keep
 * page weight down; anything else is stored as-is.
 */
class MediaService
{
    /** Max stored width in pixels — larger images are scaled down. */
    private const MAX_WIDTH = 1600;

    /** WebP quality (0–100). */
    private const QUALITY = 82;

    private const OPTIMIZABLE = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];

    /**
     * Store a single uploaded file and return a reference to it.
     *
     * When Cloudinary is configured the file is uploaded there (persistent,
     * CDN-served) and its full URL is returned. Otherwise it is stored on the
     * local public disk, and optimizable images are converted to WebP.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        if ($this->cloudinaryEnabled() && str_starts_with((string) $file->getMimeType(), 'image/')) {
            try {
                return $this->storeOnCloudinary($file, $directory);
            } catch (\Throwable $e) {
                Log::warning('Cloudinary upload failed, falling back to local storage: '.$e->getMessage());
            }
        }

        if (in_array($file->getMimeType(), self::OPTIMIZABLE, true)) {
            try {
                return $this->storeOptimized($file, $directory);
            } catch (\Throwable $e) {
                Log::warning('Image optimization failed, storing original: '.$e->getMessage());
            }
        }

        return $file->store($directory, 'public');
    }

    private function cloudinaryEnabled(): bool
    {
        return filled(config('cloudinary.cloud_url', env('CLOUDINARY_URL')));
    }

    private function storeOnCloudinary(UploadedFile $file, string $directory): string
    {
        $uploaded = Cloudinary::upload($file->getRealPath(), [
            'folder' => 'quantum/'.trim($directory, '/'),
            'resource_type' => 'image',
            // Cap the size and let Cloudinary optimise quality on delivery.
            'transformation' => [
                ['width' => self::MAX_WIDTH, 'crop' => 'limit', 'quality' => 'auto:good'],
            ],
        ]);

        return $uploaded->getSecurePath();
    }

    private function storeOptimized(UploadedFile $file, string $directory): string
    {
        $image = ImageManagerStatic::make($file->getRealPath());

        if ($image->width() > self::MAX_WIDTH) {
            $image->resize(self::MAX_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $path = trim($directory, '/').'/'.Str::uuid()->toString().'.webp';
        Storage::disk('public')->put($path, (string) $image->encode('webp', self::QUALITY));

        return $path;
    }

    /**
     * Store an uploaded file and register a polymorphic Media record for the model.
     */
    public function attach(Model $model, UploadedFile $file, string $collection, string $directory): Media
    {
        $path = $this->store($file, $directory);
        $disk = Storage::disk('public');

        return $model->media()->create([
            'uuid' => (string) Str::uuid(),
            'collection_name' => $collection,
            'name' => $file->getClientOriginalName(),
            'file_name' => $path,
            // Reflect the stored (possibly converted) file, not the upload.
            'mime_type' => $disk->exists($path) ? $disk->mimeType($path) : $file->getMimeType(),
            'disk' => 'public',
            'size' => $disk->exists($path) ? $disk->size($path) : $file->getSize(),
        ]);
    }

    /**
     * Attach many uploaded files at once.
     */
    public function attachMany(Model $model, array $files, string $collection, string $directory): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $this->attach($model, $file, $collection, $directory);
            }
        }
    }

    /**
     * Delete a stored file from the public disk.
     */
    public function delete(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
