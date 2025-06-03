<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Drive;

class DownloadReceiptsFromDrive extends Command
{
    protected $signature = 'drive:download-receipts';
    protected $description = 'Download receipt images from Google Drive folder';

    public function handle()
    {
        $folderId = '1oAN9RZ6sso2223i-Njtp4fDeBpJIx5w2pG44hQu31_KGxij_r5HKbqgXcBNUPd-0eGLNs_xx'; // Your Drive folder ID here

        // Initialize Google Client
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json')); // path to your JSON key
        $client->addScope(Drive::DRIVE_READONLY);

        $driveService = new Drive($client);

        $pageToken = null;
        do {
            // List files in the folder
            $response = $driveService->files->listFiles([
                'q' => "'$folderId' in parents and trashed = false",
                'fields' => 'nextPageToken, files(id, name)',
                'pageToken' => $pageToken,
            ]);

            foreach ($response->files as $file) {
                $this->info("Downloading: {$file->name}");

                $filePath = public_path('images/receipts/' . $file->name);

                if (file_exists($filePath)) {
                    $this->info("Skipping (already exists): {$file->name}");
                    continue;
                }
                // Get file content
                $content = $driveService->files->get($file->id, ['alt' => 'media']);

                // $filePath = storage_path('app/receipts/' . $file->name);
                // Make sure directory exists
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0755, true);
                }

                // Save the file content
                file_put_contents($filePath, $content->getBody()->getContents());


                $this->info("Saved to: $filePath");
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken != null);

        $this->info('All receipt files downloaded!');
    }
}
