<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSincronizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sincronizacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('piso_venta_id');
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
        Schema::dropIfExists('sincronizacions');
    }
}
