<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OptimizeImages extends Command
{
    protected $signature   = 'images:optimize {--quality=90 : WebP quality 1-100} {--force : Overwrite existing WebP files}';
    protected $description = 'Generate WebP versions of all uploaded package/slot images';

    public function handle(): int
    {
        $quality  = (int) $this->option('quality');
        $manager  = new ImageManager(new Driver());
        $disk     = Storage::disk('public');
        $allFiles = $disk->allFiles('uploads');
        $count    = 0;
        $skipped  = 0;

        foreach ($allFiles as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            // Skip non-images and already-converted webp files
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                continue;
            }

            $webpPath = preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $file);

            if ($disk->exists($webpPath) && !$this->option('force')) {
                $skipped++;
                continue;
            }

            try {
                $sourceFull = $disk->path($file);
                $webpFull   = $disk->path($webpPath);

                $manager->read($sourceFull)
                    ->scaleDown(width: 1200, height: 900)
                    ->toWebp(quality: $quality)
                    ->save($webpFull);
                $count++;
                $this->line("  ✓ {$file}");
            } catch (\Throwable $e) {
                $this->warn("  ✗ {$file}: " . $e->getMessage());
            }
        }

        $this->info("Done. Generated: {$count}, Skipped (already exist): {$skipped}");

        return self::SUCCESS;
    }
}
