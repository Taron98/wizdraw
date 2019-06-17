<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToTransfersWizdrawIlsRateAndWizdrawIlsExchangeRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->float('ils_exchange_rate', 10, 4)->nullable()->after('commission');
            $table->float('ils_base_rate', 10, 4)->nullable()->after('commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('ils_base_rate');
            $table->dropColumn('ils_exchange_rate');
        });
    }
}
