<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CashbackEarningsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return getImporterYMLSettings(config('app.cashback_earnings_admin_yml_path')) ? $next($request) : response()->view('errors.401', [], 401);
    }
}
