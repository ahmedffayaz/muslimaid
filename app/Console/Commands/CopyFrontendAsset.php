<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CopyFrontendAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:frontend-asset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To copy frontend theme asset to public storage to make those visible to Laravel';

    private $storagePath = null;
    private $resourcePath = null;
    private $basePath = null;
    private $vendorPath = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->storagePath = storage_path('app/public/__asset');
        $this->resourcePath = resource_path('views/frontend/asset');
        $this->basePath = base_path('/');
        $this->vendorPath = resource_path('views/frontend/vendor');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->deleteDirectory(
                convertPathForOS($this->storagePath),
                convertPathForOS($this->vendorPath . '/*')
            );

            $this->copyDirectory(
                $this->resourcePath . '/*',
                $this->vendorPath . '/*'
            );

            $this->info('Frontend asset copied successfully.');
        } catch (\Throwable $th) {
            $this->error("\e[41;97m Something went wrong. \e[0m");
            Log::error($th->getMessage());
        }
    }

    /**
     * Delete previously copied files from storage
     */
    function deleteDirectory($directoryPath, $vendorPath = null)
    {
        if (File::isDirectory($directoryPath)) {
            $files = File::allFiles($directoryPath);

            foreach ($files as $file) {
                if (File::isDirectory($file)) {
                    deleteDirectory($file);
                } else {
                    File::delete($file);
                }
            }

            File::deleteDirectory($directoryPath);
        }
        if (isset($vendorPath)) {
            $scanVendorResult = glob(
                convertPathForOS($vendorPath)
            );
            foreach($scanVendorResult as $vendorResult){
                $filePath = convertPathForOS(
                    $this->basePath . '/' . explode('frontend/vendor/', $vendorResult)[1],
                );
                if(File::exists($filePath)){
                    File::delete($filePath);
                }
            }
        }
    }

    /**
     * Actually copy the asset
     */
    private function copyDirectory($source, $vendorPath = null)
    {
        if (!is_dir($this->storagePath)) mkdir($this->storagePath);

        $scanResult = glob(
            convertPathForOS($source)
        );

        foreach ($scanResult as $sourceDirOrFile) {
            $destinationDirOrFile = convertPathForOS(
                $this->storagePath . '/' . explode('frontend/asset/', $sourceDirOrFile)[1]
            );

            if (is_dir($sourceDirOrFile)) {
                if (!is_dir($destinationDirOrFile)) {
                    mkdir($destinationDirOrFile);
                }

                $this->copyDirectory($sourceDirOrFile . '/*');
            }

            if (!file_exists($destinationDirOrFile)) {
                copy(
                    $sourceDirOrFile,
                    $destinationDirOrFile
                );
            }
        }
        if (isset($vendorPath)) {
            $scanVendorResult = glob(
                convertPathForOS($vendorPath)
            );

            foreach($scanVendorResult as $vendorResult){
                $destination = convertPathForOS(
                    $this->basePath . '/' . explode('frontend/vendor/', $vendorResult)[1],
                );
                if (!file_exists($destination)) {
                    copy(
                        $vendorResult,
                        $destination
                    );
                }
            }
        }
    }
}
