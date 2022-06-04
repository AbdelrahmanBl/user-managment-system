<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;
use Illuminate\Http\Request;

class IsTrashed
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
        if(auth()->user()->trashed()){
            Session::flush();
        
            Auth::logout();

            return redirect('login');
        }
        return $next($request);
    }
}
