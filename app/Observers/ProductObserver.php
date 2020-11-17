<?php

namespace App\Observers;

use App\Product;
use App\Inventario;
use App\Precio;
use App\Events\StockDisponible;

class ProductObserver
{
    //
    public function updated(Product $product)
    {
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
    }
}
