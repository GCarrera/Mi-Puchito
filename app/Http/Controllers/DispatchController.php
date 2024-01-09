<?php

namespace App\Http\Controllers;

use App\Dispatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dispatch $dispatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispatch $dispatch)
    {
        //
    }

    /* API PETICIONS */

    public function lastDispatches(Request $request) {
        try {
            $despachos = Dispatch::with('productos:productos.id,nombre,precio_menor,productos.updated_at')->where('branch_id', $request->data["idBranch"])->where('id', '>', $request->data["dispatchId"])->select('id', 'created_at')->get();

            return response([
                'despachos' => $despachos,
                'error'     => false,
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'error'         => true,
                'errorMessage'  => 'Fallo la consulta',
                'errorData'     => $th->getMessage(),
                'errorLine'     => $th->getLine(),
                'request'       => $request->all(),
            ], 500);
        }
    }

    public function procesar(Request $request) {
        try {
            DB::beginTransaction();

            $dispatch = Dispatch::where('id', $request->data['dispatchId'])->first();
            $dispatch->status = ($request->data['status'] > 0) ? 'aceptado' : 'rechazado';
            $dispatch->save();

            DB::commit();

            return response([
                'error'     => false,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'error'         => true,
                'errorMessage'  => 'Fallo la query',
                'errorData'     => $th->getMessage(),
                'errorLine'     => $th->getLine(),
            ]);
        }
    }
}
