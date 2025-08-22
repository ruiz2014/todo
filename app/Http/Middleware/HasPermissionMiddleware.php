<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dd($roles, $request->user()->rol);
        // if ($request->user()->id > 1) {
        //     return redirect('/');
        // }

        if (!in_array($request->user()->rol, $roles)){
            return redirect('/panel')->with('danger', 'No tiene permiso para esta seccion ...');
        }

        return $next($request);
    }
}
