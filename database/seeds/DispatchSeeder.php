<?php

use App\Dispatch;
use App\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DispatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Dispatch::class, 5)->create()->each(function ($dispatch) {
            for ($i=0; $i < random_int(5, 20); $i++) { 
                DB::table('dispatch_producto')->insert([
                    'cantidad'      => random_int(1, 10),
                    'dispatch_id'   => $dispatch->id,
                    'producto_id'   => Producto::all()->random()->id,
                ]);
            }
        });
    }
}
