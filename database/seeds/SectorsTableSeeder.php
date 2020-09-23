<?php

use Illuminate\Database\Seeder;

class SectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sectors')->delete();
        DB::table('sectors')->insert([
            [
                'sector' => 'Urb. La Haciendita',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Rafael Urdaneta',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. El Lechozal',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. 12 de Octubre',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Ciudad Jardín',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. El Toco',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. El Bosque',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Corinsa',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. La Exclusiva',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Prados de la Encrucijada',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. La Fundación',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. La Ciudadela',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Parque Residencial La Haciendita',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Santa Rosalía',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Fundacagua',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. El Carmen',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Jesús de Nazareth',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Manuelita Sáenz',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Los Cocos',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. El Samán',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. La Trinidad',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Prados de Aragua',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb. Blandín',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb: Rómulo Gallegos (No es parte de la Carpiera)',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Urb: El Corozal',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Res. Codazzi',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Res. Nathalie',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Bella Vista',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Barrios',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Alí Primera',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Guillén',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Brisas de Aragua, Principalmente conocida como Huete',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'La Comuna de Chávez',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'La Segundera',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Las Vegas',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Tamborito',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'La Carpiera',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Invasión La Esperanza',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'La Democracia',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Libertador',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'Bolívar',
                'city_id' => 44,
                'created_at' => now()
            ],
            [
                'sector' => 'La Candelaria',
                'city_id' => 44,
                'created_at' => now()
            ],
        ]);
    }
}
