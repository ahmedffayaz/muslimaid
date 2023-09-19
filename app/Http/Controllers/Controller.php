<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        try {
            getImporterYMLSettings(config('app.charity_yaml_path'));
        } catch (\Throwable $th) {
            Log::error('An error has happened during application run. Modules.yml file not exist');
            echo "An error has happened during application run.";
            die();
        }
    }
}
