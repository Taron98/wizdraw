<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToCountryStoresTableCsNumberColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries_stores', function (Blueprint $table) {
            $table->string('cs_number', 30)->after('use_qr_code')->default('+639266509254');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries_stores', function (Blueprint $table) {
            $table->dropColumn('cs_number');
        });
    }
}
