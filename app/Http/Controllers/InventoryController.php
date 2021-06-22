<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Inventory;
use App\Inventario;
use DB;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('almacen');
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

      $sofdeletevalidate = Inventory::withTrashed()->where('product_name', $req->input('product_name'))->where('deleted_at', '!=', NULL)->first();
      if (isset($sofdeletevalidate)) {

        $id = $sofdeletevalidate->id;

        $validator = Validator::make($req->all(), [
            'product_name' => 'required|max:191',
            'description' => 'nullable|max:191',
            'cantidad' => 'required|max:191',
            'stock_min' => 'required|max:191',
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
        $inventory = Inventory::withTrashed()->find($id)->restore();
        $inventory = Inventory::withTrashed()->find($id);

        $inventory->product_name   = $req->input('product_name');
        $inventory->description    = $req->input('description');
        $inventory->quantity       = $req->input('cantidad');
        $inventory->unit_type      = $req->input('tipo_unidad');
        //$inventory->qty_per_unit   = $req->input('cant_prod');
        if ($req->input('cant_prod') != 0) {
          $inventory->qty_per_unit   = $req->input('cant_prod');
        } else {
          $inventory->qty_per_unit   = '1';
        }
        $inventory->unit_type_menor   = $req->input('tipo_unidad_menor');
        // $inventory->qty_wholesale  = $req->input('whole_sale_quantity');
        $inventory->total_qty_prod = $req->input('cantidad_producto_hd');
        $inventory->category_id    = $req->input('category');
        $inventory->warehouse_id   = auth()->user()->warehouses[0]->id;
        $inventory->enterprise_id  = $req->input('enterprise');
        $inventory->stock_min      = $req->input('stock_min');

        $inventory->save();

        return redirect()->back()->with('success', 'Producto registrado exitosamente en el inventario.');

      } else {

        $validator = Validator::make($req->all(), [
            'product_name' => 'required|max:191|unique:App\Inventory,product_name',
            'description' => 'nullable|max:191',
            'cantidad' => 'required|max:191',
            'tipo_unidad' => 'required|max:191',
            'stock_min' => 'required|max:191',
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
        //$inventory->qty_per_unit   = $req->input('cant_prod');
        if ($req->input('cant_prod') != 0) {
          $inventory->qty_per_unit   = $req->input('cant_prod');
        } else {
          $inventory->qty_per_unit   = '1';
        }
        $inventory->unit_type_menor   = $req->input('tipo_unidad_menor');
        // $inventory->qty_wholesale  = $req->input('whole_sale_quantity');
        $inventory->total_qty_prod = $req->input('cantidad_producto_hd');
        $inventory->category_id    = $req->input('category');
        $inventory->warehouse_id   = auth()->user()->warehouses[0]->id;
        $inventory->enterprise_id  = $req->input('enterprise');
        $inventory->stock_min  = $req->input('stock_min');

        $inventory->save();

        return redirect()->back()->with('success', 'Producto registrado exitosamente en el inventario.');
      }

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
            'stock_min' => 'required|max:191',
            'tipo_unidad' => 'required|max:191',
            'presentacion' => 'required|max:191',
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

        $inventory->product_name    = $req->input('product_name');
        $inventory->description     = $req->input('description');
        $inventory->quantity        = $req->input('cantidad');
        $inventory->unit_type       = $req->input('tipo_unidad');
        $inventory->unit_type_menor = $req->input('presentacion');
        if ($req->input('cant_prod') != 0) {
          $inventory->qty_per_unit   = $req->input('cant_prod');
        } else {
          $inventory->qty_per_unit   = '1';
        }
        // $inventory->qty_wholesale  = $req->input('whole_sale_quantity');
        $inventory->total_qty_prod = $req->input('cantidad_producto_hd');
        $inventory->category_id    = $req->input('category');
        $inventory->warehouse_id   = auth()->user()->warehouses[0]->id;
        $inventory->enterprise_id  = $req->input('enterprise');
        $inventory->stock_min      = $req->input('stock_min');

        $inventory->save();

        $producto = Inventario::where('inventory_id', $id)->update(['name' => $req->input('product_name')]);

        return redirect()->back()->with('success', 'Producto editado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        //return $inventory;
        return redirect()->back()->with('success', 'Producto eliminado exitosamente.');
    }

    public function sumar_producto(Request $request, $id)
    {

      try {
        DB::beginTransaction();
        $inventory = Inventory::find($id);
        $inventory->total_qty_prod += $request->cantidad;
        if ($inventory->qty_per_unit != 0) {
          $inventory->quantity = $inventory->total_qty_prod / $inventory->qty_per_unit;
        }
        $inventory->save();

        DB::commit();
        return $inventory->total_qty_prod;
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json($e);
      }

    }

    public function restar_producto(Request $request, $id)
    {
      try {
        DB::beginTransaction();
        $inventory = Inventory::find($id);
        $inventory->total_qty_prod -= $request->cantidad;
        if ($inventory->qty_per_unit != 0) {
          $inventory->quantity = $inventory->total_qty_prod / $inventory->qty_per_unit;
        }
        $inventory->save();

        DB::commit();
        return $inventory->total_qty_prod;
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json($e);
      }

    }
}
