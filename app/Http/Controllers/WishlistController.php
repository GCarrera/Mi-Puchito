<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Wishlist;
use App\Dolar;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {
    	$user_id  = auth()->user()->id;
        $products = Wishlist::with('product.inventory')->where('user_id', $user_id)->get();
        $user = auth()->user();

        $carrito   = \Cart::content();
        // dd($products);
        $dolar = Dolar::orderby('id','DESC')->first();//ULTIMO DOLAR

    	return view('customer.wishlist')
    		->with('products', $products)
            ->with('carrito', $carrito)
            ->with('dolar', $dolar);
    }

    public function store(Request $req)
    {
        $producto = $req->input('productoid');
        $user     = auth()->user()->id;

        $b = Wishlist::where('product_id', $producto)->where('user_id', $user)->get();

        if (count($b) > 0) {
            return [
                'type' => 'warning',
                'mess' => 'Este producto ya lo tienes agregado.'
            ];
        }
        else {
            $wishlist = new Wishlist();

            $wishlist->product_id = $producto;
            $wishlist->user_id    = $user;

            $wishlist->save();

            return [
                'type' => 'success',
                'mess' => 'Producto agregado correctamente.',
                'wl_qty' => $this->get_wishlist()
            ];
        }
    }

    public function destroy($id)
    {
        Wishlist::destroy($id);

        return $this->get_wishlist();
    }


    public function get_wishlist()
    {
        $user_id = auth()->user()->id;

        return Wishlist::where('user_id', $user_id)->count();
    }
}
