<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        if (auth()->user()->type == 'admin') {
            return '/admin';
        }

        if (auth()->user()->type == 'costos') {
            return '/admin/venta';
        }

        if (auth()->user()->type == 'almacen') {
            return '/despachos-almacen';
        }

        if (auth()->user()->type == 'operador') {
            return '/admin';
        }
        
        if (auth()->user()->type == 'cajero') {
            return '/admin/venta'; 
        }

        return '/home';
    }
}
