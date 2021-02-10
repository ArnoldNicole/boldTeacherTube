<?php

namespace App\Http\Middleware;

use Closure;

class Lecturer
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
        if (auth()->user()->account_type == 'Lecturer') {
           return $next($request);
        }
        else{
            abort(404);
        }
    }
}
