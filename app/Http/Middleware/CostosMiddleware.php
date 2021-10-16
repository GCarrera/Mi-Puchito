<?php

namespace App\Http\Middleware;

use Closure;

class CostosMiddleware
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
      if (auth()->check() && auth()->user()->type == 'costos' || auth()->user()->type == 'admin' || auth()->user()->type == 'cajero') {
          return $next($request);
      }

      return redirect('/home');
    }
}
