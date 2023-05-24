<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModulesMiddleware
{

    public function handle(Request $request, Closure $next, $path )
    {
        $res = getImporterYMLSettings($path) ;

        if ($res) {
            return $next($request);
        }
        return response()->view('errors.404', [], 404);
    }
}
