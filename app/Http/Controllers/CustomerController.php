<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Category;
use App\Enterprise;
use App\Inventory;


class CustomerController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer')->except(['index', 'categoria']);
		// $this->middleware('customer')->except('al-mayor');
		$this->middleware('auth')->except(['index', 'categoria']);
	}

	public function index(Request $request)
	{	
		
		if (auth()->check() && auth()->user()->type == 'admin') {
			return redirect('admin');
		} else {

			$empresas   = Enterprise::all();
			$categories = Category::select('id', 'name')->get();
			$search = str_replace("+", " ", $request->search); 
			$data = Category::select('id', 'name')
			->with(['inventory' => function($inventory) use($request, $search) {
				$inventory->with(['product' => function($product) {
					if ($product != NULL) {
					
					}
				}]);
				if ($request->enterprise) {
					$inventory->where('enterprise_id', $request->enterprise);
				}
				
				if ($search) {
					$inventory->where('product_name', 'like', '%'.$search.'%')
					->orWhereHas('category_p', function($categ) use($search) {
						return $categ->where('name', 'like', '%'.$search.'%');
					});
				}
				$inventory->whereHas('product');
			}])->whereHas('inventory', function ($inventory) use ($request, $search) {
				if ($request->enterprise) {
					$inventory->where('enterprise_id', $request->enterprise);
				}
				if ($search) {
					$inventory->where('product_name', 'like', '%'.$search.'%')
					->orWhereHas('category_p', function($categ) use($search) {
						return $categ->where('name', 'like', '%'.$search.'%');
					});
				}
				$inventory->whereHas('product');
			});
	
			if ($request->category) {
				$data = $data->where('id', $request->category);
				return response()->json($data);
			}
			
			$data = $data->get();
			//SI EL USUARIO ESTA AUTENTICADO NADA MAS
			if (auth()->check()) {
				$user = auth()->user();
				// \Cart::session($userId)->clear();
				$carrito   = \Cart::session($user->id)->getContent();

				return view('customer.index')
					->with('data', $data)
					->with('categories', $categories)
					->with('empresas', $empresas)
					->with('carrito', $carrito);	
			}else{


			//return $data[0]->inventory[0]->product->wholesale_iva_amount;
			
			return view('customer.index')
					->with('data', $data)
					->with('categories', $categories)
					->with('empresas', $empresas);	
			}
			
		}
		
	}

	public function al_mayor()
	{
		if (auth()->check() && auth()->user()->type == 'admin') {
			return redirect('admin');
		}
		else {

			$products   = Product::all();
			$categorias = Category::all();
			$empresas   = Enterprise::all();

			$data     = [];
			$al_mayor = true;

			foreach ($categorias as $k => $category) {
				foreach ($products as $key => $product) {
					if ($product->inventory->category->name == $category->name) {
						$p[] = $product;
					}
				}
				if (isset($p)) {
					$data[$category->name] = $p;
					$p = [];
				}
				else {
					$data = [];
				}
			}

			return view('customer.index')
					->with('data', $data)
					->with('products', $products)
					->with('categorias', $categorias)
					->with('al_mayor', $al_mayor)
					->with('empresas', $empresas);
		}
	}

	public function categoria($category)
	{
		$categorias = Category::all();
		$empresas   = Enterprise::all();

		$products   = Product::all();
	

		$data = Category::with(['inventory' =>  function($inventory){
			//STATUS 1 ESPECIFICO QUE ESTE HABILITADO PARA LA VENTA
			$inventory->where('status', 1)->with('product');
		}])->findOrFail($category);

		//SI EL USUARIO ESTA AUTENTICADO NADA MAS
		if (auth()->check()) {
			$user = auth()->user();
			// \Cart::session($userId)->clear();
			$carrito   = \Cart::session($user->id)->getContent();

			return view('customer.category_product', compact('data','categorias', 'empresas', 'carrito'));
		}else{

		//return $data;
			return view('customer.category_product', compact('data','categorias', 'empresas'));
		}
	}
}
