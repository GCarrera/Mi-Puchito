<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespachosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despachos', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->unsignedBigInteger('almacen_id');
            $table->unsignedBigInteger('piso_venta_id');
            $table->enum('type', ['1', '2'])->comment('1:despacho,2:retiro');
            $table->boolean('confirmado')->nullable();
            $table->bigInteger('id_extra')->nullable();
            
            $table->timestamps();

            //$table->foreign('almacen_id')->references('id')->on('almacens');
            $table->foreign('piso_venta_id')->references('id')->on('piso_ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despachos');
    }
}
