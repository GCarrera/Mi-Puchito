<?php

namespace App\Http\Middleware;

use Closure;

class AlmacenMiddleware
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
      if (auth()->check() && auth()->user()->type == 'almacen' || auth()->user()->type == 'admin' || auth()->user()->type == 'costos') {
          return $next($request);
      }

      return redirect('/home');
    }
}
