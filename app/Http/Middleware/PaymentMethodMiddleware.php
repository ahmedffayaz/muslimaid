<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaymentMethodMiddleware
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
        return getImporterYMLSettings(config('app.payment_method_yaml_path')) ? $next($request) : response()->json([
            'message' => 'Unauthorized access',
            'response' => 'error'
        ], 401);
    }
}
