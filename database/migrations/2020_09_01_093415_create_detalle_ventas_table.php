<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('inventario_id');
            $table->integer('cantidad');
            $table->decimal('sub_total', 12);
            $table->integer('iva');
            $table->decimal('total', 12, 2);
            //$table->enum('tipo', ['1', '2'])->comment('1:al-menor,2:al-mayor')->nullable();
            $table->unsignedBigInteger('venta_id');
            $table->timestamps();

            $table->foreign('inventario_id')->references('id')->on('inventarios');
            $table->foreign('venta_id')->references('id')->on('ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ventas');
    }
}
