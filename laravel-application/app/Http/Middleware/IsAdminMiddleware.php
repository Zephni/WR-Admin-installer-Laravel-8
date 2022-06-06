<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdminMiddleWare
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
        if(Auth::guest() || !Auth::user()->isAdmin())
        {
            return redirect('/', 301)->with("Admins only");
        }

        return $next($request);
    }
}
