<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->deleteDirectory(
            convertPathForOS($this->storagePath)
        );

        $this->copyDirectory(
            $this->resourcePath . '/*'
        );
    }

    /**
     * Delete previously copied files from storage
     */
    function deleteDirectory($directoryPath)
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
    }

    /**
     * Actually copy the asset
     */
    private function copyDirectory($source)
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
    }
}
