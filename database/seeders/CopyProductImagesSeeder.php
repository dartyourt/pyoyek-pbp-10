<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CopyProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to sample images
        $sourcePath = database_path('seeders/sample-images');
        
        // Make sure the products directory exists
        $targetPath = storage_path('app/public/products');
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }
        
        // Get all files from the sample-images directory
        $files = File::files($sourcePath);
        
        // Copy each file to the storage directory
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            // Check if file already exists in target location
            if (!File::exists("$targetPath/$fileName")) {
                File::copy($file->getRealPath(), "$targetPath/$fileName");
                $this->command->info("Copied $fileName to storage.");
            } else {
                $this->command->info("File $fileName already exists in storage.");
            }
        }
        
        $this->command->info('All product images have been copied to storage successfully!');
        $this->command->info('Remember to run "php artisan storage:link" if you haven\'t already to make the images accessible from the web.');
    }
}