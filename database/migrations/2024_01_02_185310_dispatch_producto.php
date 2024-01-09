<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DispatchProducto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_producto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cantidad');
            $table->unsignedBigInteger('dispatch_id');
            $table->unsignedBigInteger('producto_id');

            $table->foreign('dispatch_id')->references('id')->on('dispatches')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_producto');
    }
}
