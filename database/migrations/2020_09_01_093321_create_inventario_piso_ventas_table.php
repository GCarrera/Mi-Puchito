<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioPisoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_piso_ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventario_id');
            $table->unsignedBigInteger('piso_venta_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('inventario_id')->references('id')->on('inventarios');
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
        Schema::dropIfExists('inventario_piso_ventas');
    }
}
