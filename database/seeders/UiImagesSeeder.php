<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UiImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to sample images
        $sourcePath = database_path('seeders/sample-images');
        
        // Target path for UI images
        $targetPath = storage_path('app/public/ui');
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }
        
        // List of UI images to copy
        $uiImages = [
            'header.jpeg',
            'header2.png',
            'header3.png',
            'header4.png',
            'model1.png',
            'model2.png',
            'model3.png',
        ];
        
        foreach ($uiImages as $imageName) {
            $sourceFile = "$sourcePath/$imageName";
            $targetFile = "$targetPath/$imageName";

            if (File::exists($sourceFile) && !File::exists($targetFile)) {
                File::copy($sourceFile, $targetFile);
                $this->command->info("Copied UI image: $imageName");
            } elseif (File::exists($targetFile)) {
                $this->command->info("UI image already exists: $imageName");
            } else {
                $this->command->warn("UI image source not found: $imageName");
            }
        }
        
        $this->command->info('UI images seeded successfully!');
    }
}
