<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespachoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despacho_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('despacho_id');
            //$table->unsignedBigInteger('articulo_id');
            
            $table->integer('cantidad');
            $table->unsignedInteger('inventory_id');
            $table->timestamps();

            $table->foreign('despacho_id')->references('id')->on('despachos');
            $table->foreign('inventory_id')->references('id')->on('inventories');
            //$table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despacho_detalles');
    }
}
