<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\State;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sectors')->delete();
        DB::table('cities')->delete();
        DB::table('states')->delete();

        State::create(["state" => 'Amazonas']);
        State::create(["state" => 'Anzoategui']);
        State::create(["state" => 'Apure']);
        State::create(["state" => 'Aragua']);
        State::create(["state" => 'Barinas']);
        State::create(["state" => 'Bolivar']);
        State::create(["state" => 'Carabobo']);
        State::create(["state" => 'Cojedes']);
        State::create(["state" => 'Delta Amacuro']);
        State::create(["state" => 'Distrito Federal']);
        State::create(["state" => 'Falcon']);
        State::create(["state" => 'Guarico']);
        State::create(["state" => 'Lara']);
        State::create(["state" => 'Merida']);
        State::create(["state" => 'Miranda']);
        State::create(["state" => 'Monagas']);
        State::create(["state" => 'Nueva Esparta']);
        State::create(["state" => 'Portuguesa']);
        State::create(["state" => 'Sucre']);
        State::create(["state" => 'Tachira']);
        State::create(["state" => 'Trujillo']);
        State::create(["state" => 'Vargas']);
        State::create(["state" => 'Yaracuy']);
        State::create(["state" => 'Zulia']);
    }
}
