<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Inventory;
use App\Product;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Inventario;
use App\Precio;

class ProductController extends Controller
{
	public function __construct()
	{
		$this->middleware('admin')->except('show');
	}

	public function index()
	{
//
	}

	public function store(Request $req)
	{
		$validator = Validator::make($req->all(), [
            'product' => 'required|integer',
			'cost' => 'required|numeric',
			'iva_percent' => 'required|numeric',
			'retail_margin_gain' => 'required|numeric',
			'wholesale_margin_gain' => 'required|numeric',
			'fileinput' => 'required',
			'oferta' => 'required'
		]);
		
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
		}
		
		$product = new Product();
		$inventory = Inventory::find($req->input('product'));

		$retail_subtotal_price =  $req->input('cost') + ($req->input('cost')*($req->input('retail_margin_gain')/100));
		$retail_iva_amount = $retail_subtotal_price * ($req->input('iva_percent')/100);
		$retail_total_price = $retail_subtotal_price + $retail_iva_amount;

		$wholesale_subtotal_price = $req->input('cost') + ($req->input('cost')*($req->input('wholesale_margin_gain')/100));
		$wholesale_iva_amount =  $wholesale_subtotal_price * ($req->input('iva_percent')/100);
		$wholesale_total_individual_price = $wholesale_subtotal_price + $wholesale_iva_amount;

		$product->cost                   = $req->input('cost');
		$product->iva_percent            = $req->input('iva_percent');
		$product->retail_margin_gain     = $req->input('retail_margin_gain');
		// $product->retail_pvp             = $req->input('retail_pvp');
		$product->retail_total_price     = $retail_total_price;
		$product->retail_iva_amount      = $retail_iva_amount;
		$product->image                  = explode('public/', $req->file('fileinput')->store('public'))[1];
		$product->wholesale_margin_gain  = $req->input('wholesale_margin_gain');
		// $product->wholesale_pvp          = $req->input('wholesale_pvp');
		$product->wholesale_packet_price = $req->input('wholesale_packet_price');
		$product->wholesale_total_packet_price     = $req->input('wholesale_packet_price');
		$product->wholesale_total_individual_price = $wholesale_total_individual_price;
		$product->wholesale_iva_amount   = $wholesale_iva_amount;
		$product->inventory_id           = $req->input('product');
		//OFERTA
		$product->oferta = $req->input('oferta');
		$product->save();

		$inventory->status = 1;
		$inventory->save();

		return redirect()->back()->with('success', 'Producto registrado exitosamente.');
	}

	public function show($id)
	{
		$product    = Product::find($id);

		$inventory  = $product->inventory;
		$category   = $product->inventory->category;
		$enterprise = $product->inventory->enterprise;
		$warehouse  = $product->inventory->warehouse;

		return $product;
	}

	public function update(Request $req, $id)
	{
		try{

            DB::beginTransaction();

			$product = Product::find($id);
			
			$product->cost                   = $req->input('cost');
			$product->iva_percent            = $req->input('iva_percent');
			$product->retail_margin_gain     = $req->input('retail_margin_gain');
			// $product->retail_pvp             = $req->input('retail_pvp');
			$product->retail_total_price     = $req->input('retail_total_price');
			$product->retail_iva_amount      = $req->input('retail_iva_amount');
			$product->image                  = $product->image;
			$product->wholesale_margin_gain  = $req->input('wholesale_margin_gain');
			// $product->wholesale_pvp          = $req->input('wholesale_pvp');
			$product->wholesale_packet_price = $req->input('wholesale_packet_price');
			$product->wholesale_total_individual_price = $req->input('wholesale_total_individual_price');
			$product->wholesale_total_packet_price     = $req->input('wholesale_total_packet_price');
			$product->wholesale_iva_amount   = $req->input('wholesale_iva_amount');
			$product->inventory_id           = $product->inventory_id;

			$product->save();

			//PISOS DE VENTA
			
			$inventarios = Inventario::with('inventory')->where('inventory_id', $product->inventory_id)->get();

			foreach ($inventarios as $producto) {

				$precio = Precio::where('inventario_id', $producto['id'])->orderBy('id', 'desc')->first();
	            $precio->costo = $product->cost;
	            $precio->iva_porc = $product->iva_percent;
	            $precio->iva_menor = $product->retail_iva_amount;
	            $precio->sub_total_menor = $product->retail_total_price - $product->retail_iva_amount;
	            $precio->total_menor = $product->retail_total_price;
	            $precio->iva_mayor = $product->wholesale_iva_amount * $producto['inventory']['qty_per_unit'];
	            $precio->sub_total_mayor = $product->wholesale_packet_price;
	            $precio->total_mayor = $precio->sub_total_mayor + $precio->iva_mayor;
	            $precio->save();
			}
			
			DB::commit();

			return redirect()->back()->with('success', 'Producto editado exitosamente.');

		}catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
	}

	public function destroy($id)
	{
		//
	}
}
