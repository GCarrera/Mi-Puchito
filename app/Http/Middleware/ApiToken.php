<?php

namespace App\Http\Middleware;

use App\Branch;
use Closure;

class ApiToken
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
        if (isset($request->data)) {
            $data = $request->data;
            if (isset($data["apiToken"]) && isset($data["idBranch"])) {
                $piso = Branch::where('id', $data["idBranch"])->where('api_token', $data['apiToken'])->exists();

                if ($piso) {
                    return $next($request);                
                } else {
                    return response([
                        'error'         => true,
                        'errorMessage'  => 'Autenticacion incorrecta',
                    ], 500);
                }
                
            } else {
                return response([
                    'error'         => true,
                    'errorMessage'  => 'Faltan datos para validar',
                    'errorData'     => var_dump($data["idBranch"]),
                ], 500);
            }
        } else {
            return response([
                    'error'         => true,
                    'errorMessage'  => 'Peticion vacia',
                    'errorData'     => $request->all(),
                ], 500);
        }
        
        
    }
}
