<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Inventory;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'product_name' => 'required|max:191',
            'description' => 'nullable|max:191',
            'cantidad' => 'required|max:191',
            'tipo_unidad' => 'required|max:191',
            'cant_prod' => 'required|max:191',
            'category' => 'required|integer',
            'enterprise' => 'required|integer',
		]);
		
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $inventory = new Inventory();

        $inventory->product_name   = $req->input('product_name');
        $inventory->description    = $req->input('description');
        $inventory->quantity       = $req->input('cantidad');
        $inventory->unit_type      = $req->input('tipo_unidad');
        $inventory->qty_per_unit   = $req->input('cant_prod');
        $inventory->unit_type_menor   = $req->input('tipo_unidad_menor');
        // $inventory->qty_wholesale  = $req->input('whole_sale_quantity');
        $inventory->total_qty_prod = $req->input('cantidad_producto_hd');
        $inventory->category_id    = $req->input('category');
        $inventory->warehouse_id   = auth()->user()->warehouses[0]->id;
        $inventory->enterprise_id  = $req->input('enterprise');

        $inventory->save();

        return redirect()->back()->with('success', 'Producto registrado exitosamente en el inventario.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Inventory::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'product_name' => 'required|max:191',
            'description' => 'nullable|max:191',
            'cantidad' => 'required|max:191',
            'tipo_unidad' => 'required|max:191',
            'cant_prod' => 'required|max:191',
            'category' => 'required|integer',
            'enterprise' => 'required|integer',
		]);
		
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $inventory = Inventory::find($id);

        $inventory->product_name   = $req->input('product_name');
        $inventory->description    = $req->input('description');
        $inventory->quantity       = $req->input('cantidad');
        $inventory->unit_type      = $req->input('tipo_unidad');
        $inventory->qty_per_unit   = $req->input('cant_prod');
        // $inventory->qty_wholesale  = $req->input('whole_sale_quantity');
        $inventory->total_qty_prod = $req->input('cantidad_producto_hd');
        $inventory->category_id    = $req->input('category');
        $inventory->warehouse_id   = auth()->user()->warehouses[0]->id;
        $inventory->enterprise_id  = $req->input('enterprise');

        $inventory->save();

        return redirect()->back()->with('success', 'Producto editado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }

    public function sumar_producto(Request $request, $id)
    {

        $inventory = Inventory::find($id);
        $inventory->total_qty_prod += $request->cantidad;
        $inventory->quantity = $inventory->total_qty_prod / $inventory->qty_per_unit;
        $inventory->save();

        return $inventory->total_qty_prod;

    }

    public function restar_producto(Request $request, $id)
    {

        $inventory = Inventory::find($id);
        $inventory->total_qty_prod -= $request->cantidad;
        $inventory->quantity = $inventory->total_qty_prod / $inventory->qty_per_unit;
        $inventory->save();

        return $inventory->total_qty_prod;

    }
}
