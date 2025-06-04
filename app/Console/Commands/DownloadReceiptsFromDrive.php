<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;

class DownloadReceiptsFromDrive extends Command
{
    protected $signature = 'drive:download-receipts';
    protected $description = 'Download receipt images from Google Drive folder';

    public function handle()
    {

        $base64 = env('GOOGLE_DRIVE_CREDENTIALS');

        if (!$base64) {
            throw new \Exception('GOOGLE_DRIVE_CREDENTIALS not set');
        }

        // Decode base64 to get original JSON string
        $credentialsJson = base64_decode($base64);

        if (!$credentialsJson) {
            throw new \Exception('Failed to decode GOOGLE_DRIVE_CREDENTIALS');
        }

        $credentialsPath = storage_path('app/google-credentials.json');

        if (!file_exists($credentialsPath)) {
            Storage::disk('local')->put('google-credentials.json', $credentialsJson);
        }


        $folderId = '1oAN9RZ6sso2223i-Njtp4fDeBpJIx5w2pG44hQu31_KGxij_r5HKbqgXcBNUPd-0eGLNs_xx';
        $client = new \Google_Client();
        // $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->setAuthConfig($credentialsPath);

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
    protected function writeGoogleCredentialsToFile()
    {
        $json = env('GOOGLE_CREDENTIALS_JSON');

        if (!$json) {
            throw new \Exception('Google credentials not found in environment variable.');
        }

        $path = storage_path('app/google/credentials-temp.json');

        file_put_contents($path, $json);

        return $path;
    }

}
