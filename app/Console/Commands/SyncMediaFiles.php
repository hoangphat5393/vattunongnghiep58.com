<?php

namespace App\Console\Commands;

use App\Models\Backend\Page;
use App\Models\Backend\Product;
use App\Models\MediaFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SyncMediaFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:sync {--dry-run : Only simulate changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync upload directory with database and fix image paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting media sync...');

        // 1. Check permissions
        $uploadPaths = [
            public_path('assets/images'),
            public_path('uploads'),
        ];

        foreach ($uploadPaths as $path) {
            if (File::exists($path)) {
                if (! is_writable($path)) {
                    $this->error("Directory $path is not writable!");
                    Log::error("Directory $path is not writable!");

                    return 1;
                }
                $this->info("Permissions check passed for $path");
                $this->scanDirectory($path);
            }
        }

        // 2. Fix mismatched paths in Database
        $this->fixModelPaths(Product::class, 'image');
        $this->fixModelPaths(Page::class, 'image');

        // 3. Fix gallery paths (serialized array) for Product
        $this->fixGalleryPaths(Product::class);

        $this->info('Media sync completed.');

        return 0;
    }

    private function scanDirectory($path)
    {
        $files = File::allFiles($path);
        $count = 0;

        $this->info('Scanning '.count($files)." files in $path...");

        foreach ($files as $file) {
            // Get relative path from public folder
            $relativePath = str_replace(public_path().DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('\\', '/', $relativePath); // Ensure forward slashes

            // Check if exists in media_files
            $exists = MediaFile::where('file_path', $relativePath)->exists();

            if (! $exists) {
                if (! $this->option('dry-run')) {
                    try {
                        MediaFile::create([
                            'file_name' => $file->getFilename(),
                            'file_path' => $relativePath,
                            'file_size' => $file->getSize(),
                            'file_type' => $file->getExtension(),
                            'disk' => 'public',
                        ]);
                        Log::info("Added media file: $relativePath");
                    } catch (\Exception $e) {
                        Log::error("Failed to add media file $relativePath: ".$e->getMessage());
                    }
                }
                $count++;
            }
        }
        $this->info("Added $count new files to media_files table from $path.");
    }

    private function fixGalleryPaths($modelClass)
    {
        if (! class_exists($modelClass)) {
            return;
        }

        $this->info("Checking gallery paths for model: $modelClass");

        try {
            // Check if model has gallery column
            $dummy = new $modelClass;
            // We can't easily check column existence without schema, but we can try-catch

            $records = $modelClass::whereNotNull('gallery')->where('gallery', '!=', '')->get();
            $fixedCount = 0;

            foreach ($records as $record) {
                $galleryRaw = $record->gallery;
                $gallery = @unserialize($galleryRaw);

                if ($gallery === false && $galleryRaw !== 'b:0;') {
                    // Not serialized or corrupted
                    continue;
                }

                if (is_array($gallery)) {
                    $changed = false;
                    foreach ($gallery as $key => $path) {
                        // Ensure path is string
                        if (! is_string($path)) {
                            continue;
                        }

                        $decoded = urldecode($path);
                        if ($path !== $decoded && File::exists(public_path($decoded))) {
                            $gallery[$key] = $decoded;
                            $changed = true;
                        }
                    }

                    if ($changed) {
                        if (! $this->option('dry-run')) {
                            $record->gallery = serialize($gallery);
                            $record->save();
                            Log::info("Fixed $modelClass ID {$record->id} gallery paths.");
                        }
                        $fixedCount++;
                    }
                }
            }
            $this->info("Fixed $fixedCount gallery records for $modelClass.");
        } catch (\Exception $e) {
            // Column might not exist or other error
            $this->error("Error checking gallery for $modelClass: ".$e->getMessage());
            Log::error("Error checking gallery for $modelClass: ".$e->getMessage());
        }
    }

    private function fixModelPaths($modelClass, $column)
    {
        if (! class_exists($modelClass)) {
            return;
        }

        $this->info("Checking paths for model: $modelClass");

        try {
            // Get all records with non-empty column
            $records = $modelClass::whereNotNull($column)->where($column, '!=', '')->get();
            $fixedCount = 0;

            foreach ($records as $record) {
                $path = $record->$column;

                // Logic: Check if path is URL encoded and file exists at decoded path
                // Example: 'foo%20bar.jpg' -> 'foo bar.jpg'
                $decoded = urldecode($path);

                if ($path !== $decoded) {
                    // Check if file exists at decoded path
                    if (File::exists(public_path($decoded))) {
                        if (! $this->option('dry-run')) {
                            $record->$column = $decoded;
                            $record->save();
                            Log::info("Fixed $modelClass ID {$record->id} image path: $path -> $decoded");
                        }
                        $fixedCount++;
                    }
                }
            }
            $this->info("Fixed $fixedCount paths for $modelClass.");
        } catch (\Exception $e) {
            $this->error("Error checking $modelClass: ".$e->getMessage());
            Log::error("Error checking $modelClass: ".$e->getMessage());
        }
    }
}
