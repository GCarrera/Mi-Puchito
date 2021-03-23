<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use App\Despacho;
use App\Delivery;
use App\TravelRate;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    //

    public function get_pedido ($id)
    {
    	$subtotal = 0;
    	$iva = 0;
    	$total = 0;

    	if (Auth::user()->type == 'admin') {

    		$pedido = Sale::with('user.people', 'details', 'details.product', 'details.product.inventory')->findOrFail($id);
    	}else if(Auth::user()->type == 'customer'){

    		$pedido = Sale::with('user.people', 'details', 'details.product', 'details.product.inventory')->where('user_id', Auth::id())->findOrFail($id);
    	}

    	foreach ($pedido->details as $producto) {
            if ($producto->type == "al-mayor") {
                $subtotal += $producto->product->wholesale_packet_price * $producto->quantity;
                $iva += ($producto->product->wholesale_iva_amount * $producto->product->inventory->qty_per_unit) * $producto->quantity;
            }else if($producto->type == "al-menor"){

                $subtotal += ($producto->product->retail_total_price - $producto->product->retail_iva_amount) * $producto->quantity;
                $iva += $producto->product->retail_iva_amount * $producto->quantity;
            }


    	}

    	$total = $subtotal + $iva;

    	$pdf = PDF::loadView("pdf.factura", compact('pedido', 'subtotal', 'iva', 'total'));

    	//return response()->json($pedido);
    	return $pdf->stream();

    }

    public function get_despacho ($data)
    {
    	/*$subtotal = 0;
    	$iva = 0;
    	$total = 0;*/

    	if (Auth::user()->type == 'admin' || Auth::user()->type == 'almacen') {

        $despacho = Despacho::with(['productos' => function($producto){
            $producto->select('product_name');
        }, 'piso_venta'])->orderBy('id', 'desc')->findOrFail($data);

      	/*foreach ($pedido->details as $producto) {
              if ($producto->type == "al-mayor") {
                  $subtotal += $producto->product->wholesale_packet_price * $producto->quantity;
                  $iva += ($producto->product->wholesale_iva_amount * $producto->product->inventory->qty_per_unit) * $producto->quantity;
              }else if($producto->type == "al-menor"){

                  $subtotal += ($producto->product->retail_total_price - $producto->product->retail_iva_amount) * $producto->quantity;
                  $iva += $producto->product->retail_iva_amount * $producto->quantity;
              }


      	}

      	$total = $subtotal + $iva;*/

        //return response()->json($despacho);
      	$pdf = PDF::loadView("pdf.despacho", compact('despacho'));

      	return $pdf->stream();
      }

    }

    public function get_pedido_descarga ($id)
    {
        $subtotal = 0;
        $iva = 0;
        $total = 0;

        $pedido = Sale::with('user.people', 'details', 'details.product', 'details.product.inventory')->findOrFail($id);
        /*if (Auth::user()->type == 'admin') {

            $pedido = Sale::with('user.people', 'details', 'details.product', 'details.product.inventory')->findOrFail($id);
        }else if(Auth::user()->type == 'customer'){

            $pedido = Sale::with('user.people', 'details', 'details.product', 'details.product.inventory')->where('user_id', Auth::id())->findOrFail($id);
        }*/

        foreach ($pedido->details as $producto) {
            if ($producto->type == "al-mayor") {
                $subtotal += $producto->product->wholesale_packet_price * $producto->quantity;
                $iva += ($producto->product->wholesale_iva_amount * $producto->product->inventory->qty_per_unit) * $producto->quantity;
            }else if($producto->type == "al-menor"){

                $subtotal += ($producto->product->retail_total_price - $producto->product->retail_iva_amount) * $producto->quantity;
                $iva += $producto->product->retail_iva_amount * $producto->quantity;
            }


        }

        $rate   = Delivery::with('address_user_delivery')->where('sale_id', $id)->first();
        if (isset($rate->address_user_delivery->travel_rate_id)) {
    			$datadir = $rate->address_user_delivery->travel_rate_id;
    			$dir	  = TravelRate::with('sector')->where('id', $datadir)->first();
    			$pedido->dir = $dir;
    		} else {
    			$datadir = $rate->address_user_delivery_id;
    			$dir = DB::table('address_user_deliveries')->where('id', $datadir)->first();
    			$datasector = $dir->travel_rate_id;
    			//return $dir->travel_rate_id;
    			$sector = DB::table('travel_rates')->where('id', $datasector)->first();
    			if (isset($sector->sector_id)) {
    				$datasectrofinla = $sector->sector_id;
    				$sectorfinal = DB::table('sectors')->where('id', $datasectrofinla)->first();
    				//return $dir;
    				//$sector	  = TravelRate::with('sector')->get();
    				$pedido->sector = $sectorfinal;
    			}
    			$pedido->dir = $dir;
    		}
    		$pedido->rate = $rate;

        $total = $subtotal + $iva;

        $pdf = PDF::loadView("pdf.factura", compact('pedido', 'subtotal', 'iva', 'total'));

        //return response()->json($pedido);
        return $pdf->stream();
        //return $pdf->download('FC-00'.$pedido->id);

    }
}
