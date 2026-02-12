<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CompressImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compress-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manager = new ImageManager(new Driver());

        $folder = storage_path('app/public/images');
        $thumbFolder = $folder . '/thumbs';

        if (!File::exists($thumbFolder)) {
            File::makeDirectory($thumbFolder, 0755, true);
        }

        $files = File::files($folder);
        $processed = 0;

        foreach ($files as $file) {

            $extension = strtolower($file->getExtension());

            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                continue;
            }

            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            $webpPath = $folder . '/' . $filename . '.webp';
            $thumbWebpPath = $thumbFolder . '/' . $filename . '.webp';

            // Skip if already converted
            if (File::exists($webpPath)) {
                $this->line("Skipped: " . $file->getFilename());
                continue;
            }

            // Main image resize + convert
            $image = $manager->read($file->getPathname());

            $image->scale(width: 1200)
                ->toWebp(quality: 80)
                ->save($webpPath);

            // Thumbnail (crop center)
            $thumb = $manager->read($file->getPathname());

            $thumb->cover(400, 400)
                ->toWebp(quality: 70)
                ->save($thumbWebpPath);

            $processed++;

            $this->info("Processed: " . $file->getFilename());
        }

        $this->info("Done! Total processed: $processed");
    }


}