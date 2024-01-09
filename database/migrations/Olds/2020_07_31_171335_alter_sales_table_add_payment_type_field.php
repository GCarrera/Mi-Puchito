<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSalesTableAddPaymentTypeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_type', ['1', '2', '3'])->comment('1:dolares,2:transferencia,3:pago movil')->nullable()->after('dispatched');
            $table->string('attached_file')->nullable()->after('payment_reference_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('attached_file');
        });
    }
}
