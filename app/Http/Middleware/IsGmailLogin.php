<?php

namespace App\Http\Middleware;

use Closure;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class IsGmailLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!LaravelGmail::check()) {
            return redirect()->route('login');
        }
        return $next($request);

    }
}
