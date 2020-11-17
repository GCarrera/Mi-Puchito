<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('sub_total', 12, 2)->nullable();
            $table->integer('iva')->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->unsignedBigInteger('piso_venta_id');
            $table->enum('type', ['1', '2'])->comment('1:venta,2:compra');
            $table->bigInteger('id_extra')->nullable();
            $table->boolean('anulado')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('ventas');
    }
}
