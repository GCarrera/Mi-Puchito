<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Category;
use App\Enterprise;
use App\Inventory;
use App\Dolar;
use Carbon\Carbon;
use App\Sale;


class CustomerController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer')->except(['index', 'categoria', 'get_compras', 'search']);
		// $this->middleware('customer')->except('al-mayor');
		$this->middleware('auth')->except(['index', 'categoria', 'get_compras', 'search']);
	}

	public function get_compras()
	{

		if (auth()->check() && auth()->user()->type == 'customer') {
			$user = auth()->user();
			$compras  = Sale::orderBy('id', 'desc')
			->where('user_id', $user->id)
			->where('notify', '1')
			->get();
			return $compras;
		} else {
			return "false";
		}

	}

	public function finish_notify($id)
	{

		if (auth()->check() && auth()->user()->type == 'customer') {
			$user = auth()->user();
			$compras = Sale::find($id);
			$compras->notify = '0';
			$compras->save();
			return 'true';
		}

	}

	public function index(Request $request)
	{

		if (auth()->check() && auth()->user()->type == 'admin') {
			return redirect('admin');
		} else {
			$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR
			$empresas   = Enterprise::all();
			$categories = Category::select('id', 'name')->get();
			$search = str_replace("+", " ", $request->search);
			/*$productos = Product::where('oferta', 1)
			->with('inventory')
			->whereHas('inventory', function($query) {
	        $query->where('total_qty_prod', '>', 0);
	    })
			->paginate();*/
			$data = Category::select('id', 'name')
			->with(['inventory' => function($inventory) use($request, $search) {
				$inventory->where('status', 1)->where('total_qty_prod', '>', 0);
				//EL TAKE DETERMINA LA CANTIDAD DE PRODUCTOS A MOSTRAR
				$inventory->take(20)->with(['product' => function($product) {
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
				$inventory->whereHas('product', function ($product) use ($request, $search) {
					$product->where('retail_total_price', '>', 0);
				});
			}])->whereHas('inventory', function ($inventory) use ($request, $search) {
				if ($request->enterprise) {
					$inventory->where('enterprise_id', $request->enterprise)
					->where('total_qty_prod', '>', 0);
				}
				if ($search) {
					$inventory->where('product_name', 'like', '%'.$search.'%')
					->where('total_qty_prod', '>', 0)
					->orWhereHas('category_p', function($categ) use($search) {
						return $categ->where('name', 'like', '%'.$search.'%');
					});
				}
				$inventory->whereHas('product')
				->where('total_qty_prod', '>', 0);
			});

			if ($request->category) {
				$data = $data->where('id', $request->category);
				return response()->json($data);
			}

			$data = $data->get();

			if ($search) {
				$bibi = $search;
			} else {
				$bibi = false;
			}

			if ($request->enterprise) {
				$senter = Enterprise::select('name')->where('id', $request->enterprise)->first();
			} else {
				$senter = false;
			}



				//$user = auth()->user();
				// \Cart::session($userId)->clear();
				$carrito   = \Cart::content();

				return view('customer.index')
					->with('data', $data)
					->with('categories', $categories)
					->with('empresas', $empresas)
					->with('carrito', $carrito)
					->with('bibi', $bibi)
					->with('senter', $senter)
					->with('dolar', $dolar);

		}

	}

	public function search(Request $request)
	{
			$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR
			$empresas   = Enterprise::all();
			$categories = Category::select('id', 'name')->get();
			$search = str_replace("+", " ", $request->search);

			if ($search) {
				$bibi = $search;
			} else {
				$bibi = false;
			}

			if ($request->enterprise) {
				$senter = Enterprise::select('name')->where('id', $request->enterprise)->first();
			} else {
				$senter = false;
			}

				//$user = auth()->user();
				// \Cart::session($userId)->clear();
				$carrito   = \Cart::content();

				$data = Inventory::where('product_name', 'like', '%'.$search.'%')->where('status', 1)->where('total_qty_prod', '>', 0)->with('product')->get();

				//return $data;

				return view('customer.search')
					->with('data', $data)
					->with('categories', $categories)
					->with('empresas', $empresas)
					->with('carrito', $carrito)
					->with('bibi', $bibi)
					->with('senter', $senter)
					->with('dolar', $dolar);

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

		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		$data = Category::with(['inventory' =>  function($inventory){
			//STATUS 1 ESPECIFICO QUE ESTE HABILITADO PARA LA VENTA
			$inventory->where('status', 1)->with('product');
		}])->findOrFail($category);

		//SI EL USUARIO ESTA AUTENTICADO NADA MAS

			// \Cart::session($userId)->clear();
			$carrito   = \Cart::content();

		return view('customer.category_product', compact('data','categorias', 'empresas', 'carrito', 'dolar'));

	}

	public function empresa($empresa)
	{
		$categorias = Category::all();
		$empresas   = Enterprise::all();

		//$products   = Product::all();

		$dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

		$senter = Enterprise::select('name')->where('id', $empresa)->first();

		/*$phone = Enterprise::where('id', $empresa)->with(['inventory' => function ($inventory) use($empresa)
		{
			$inventory->where('status', 1)->where('total_qty_prod', '>', 0)->with('product');
		}])->get();*/

		//$phone = Enterprise::where('id', $empresa)->with('inventory')->get();

		$data = Inventory::where('enterprise_id', $empresa)->where('status', 1)->where('total_qty_prod', '>', 0)->with('product')->get();

		/*return $phone;

		$data = Category::with(['inventory' =>  function($inventory) use($empresa) {
			//STATUS 1 ESPECIFICO QUE ESTE HABILITADO PARA LA VENTA
			$inventory->where('status', 1)->where('total_qty_prod', '>', 0)->where('enterprise_id', $empresa)->with('product');
			//$inventory->where('status', 1)->where('total_qty_prod', '>', 0)->where('enterprise_id', $empresa)->with('product');
		}])->get();*/

		//return $data;

		//SI EL USUARIO ESTA AUTENTICADO NADA MAS

			// \Cart::session($userId)->clear();
			$carrito   = \Cart::content();

		return view('customer.empresa_product', compact('data','categorias', 'empresas', 'carrito', 'dolar', 'senter'));

	}
}
