<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaciarCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaciar_cajas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('piso_venta_id');
            $table->decimal('monto', 10, 2);
            $table->bigInteger('id_extra')->nullable();
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
        Schema::dropIfExists('vaciar_cajas');
    }
}
