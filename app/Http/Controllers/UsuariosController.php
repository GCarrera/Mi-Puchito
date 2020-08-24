<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Sale;

class UsuariosController extends Controller
{
    //
    public function index()
    {
    	$usuarios = User::with('people')->where('type', 'customer')->orderBy('id', 'desc')->paginate();

    	return view('admin.usuarios', compact('usuarios'));
    }

    public function show($id)
    {

    	$usuario = User::with(['people'])->where('type', 'customer')->findOrFail($id);

    	$sales = Sale::with('details')->where('user_id', $usuario->id)->orderBy('id', 'desc')->paginate();

    	//return $usuarios;

    	return view('admin.usuario_show', compact('usuario', 'sales'));
    }
}
