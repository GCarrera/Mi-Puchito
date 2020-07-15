<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Category;
use App\Enterprise;


class CustomerController extends Controller
{
	public function __construct()
	{
		$this->middleware('customer')->except('index');
		// $this->middleware('customer')->except('al-mayor');
		$this->middleware('auth')->except('index');
	}

	public function index()
	{
		if (auth()->check() && auth()->user()->type == 'admin') {
			return redirect('admin');
		}
		else {

			$products   = Product::all();
			$categorias = Category::all();
			$empresas   = Enterprise::all();

			$data     = [];
			$al_mayor = false;

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

			// return $data;

			return view('customer.index')
					->with('data', $data)
					->with('products', $products)
					->with('categorias', $categorias)
					->with('al_mayor', $al_mayor)
					->with('empresas', $empresas);
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
		$data = [];

		foreach ($products as $key => $value) {
			if ($value->inventory->category->name == $category) {
				$data[] = $value;
			}
		}

		return view('customer.category_product')
				->with('data', $data)
				->with('cat', $category)
				->with('categorias', $categorias)
				->with('empresas', $empresas);
	}
}
