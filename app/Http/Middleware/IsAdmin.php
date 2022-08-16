<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if (Auth::user() && Auth::user()->admin) {
            return $next($request);
        }

        $userEmail = Auth::user()->email;
        Auth::logout();

        return redirect('/login')->with('error','No administrative permission granted')->withInput(['email' => $userEmail]);
    }
}
