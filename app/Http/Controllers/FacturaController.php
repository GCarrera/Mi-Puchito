<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF; 

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

    public function get_pedido_descarga ($id)
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
        return $pdf->download('FC-00'.$pedido->id);
   
    }
}
