<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthProducer
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('producer')->guest()) {
            if (!$request->ajax() || !$request->wantsJson()) {
                return redirect(route('producer.login'));
            }
        }
        $response = $next($request);
        return $response;
    }
}
