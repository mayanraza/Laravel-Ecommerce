<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['Piad', 'Unpaid'])->after("grand_total")->default('Unpaid');
            $table->enum('status', ['pending', 'shipped', 'deliver'])->after("payment_status")->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("payment_status");
            $table->dropColumn("status");

        });
    }
}
