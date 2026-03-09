<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait GeneratesWebp
{
    /**
     * Generate a .webp version of an uploaded image stored on the 'public' disk.
     *
     * @param  string  $storagePath  e.g. "uploads/3/packages/9/abc.jpg"
     * @param  int     $quality      WebP quality 1-100 (90 = visually identical to source)
     */
    protected function generateWebp(string $storagePath, int $quality = 90): void
    {
        try {
            $ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                return; // already webp or unsupported
            }

            $webpPath = preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $storagePath);

            // Skip if webp already exists
            if (Storage::disk('public')->exists($webpPath)) {
                return;
            }

            $sourceFull = Storage::disk('public')->path($storagePath);
            $webpFull   = Storage::disk('public')->path($webpPath);

            if (!file_exists($sourceFull)) {
                return;
            }

            $manager = new ImageManager(new Driver());
            $manager->read($sourceFull)
                ->scaleDown(width: 1200, height: 900)
                ->toWebp(quality: $quality)
                ->save($webpFull);
        } catch (\Throwable $e) {
            // Non-fatal — log but don't break upload response
            logger()->warning("WebP generation failed for {$storagePath}: " . $e->getMessage());
        }
    }
}
