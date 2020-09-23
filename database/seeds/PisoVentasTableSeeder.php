<?php

use Illuminate\Database\Seeder;
use App\Piso_venta;

class PisoVentasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 1',
        					'ubicacion' => 'centro cagua',
        					'dinero' => 0
        					]);


        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 2',
        					'ubicacion' => 'la segundera',
        					'dinero' => 0
        					]);

        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 3',
        					'ubicacion' => 'cagua la villa',
        					'dinero' => 0
        					]);
    }
}
