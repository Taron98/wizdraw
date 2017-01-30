<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsLengthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name', 70)->nullable()->change();
            $table->string('middle_name', 70)->nullable()->change();
            $table->string('last_name', 70)->nullable()->change();
            $table->string('state', 120)->nullable()->change();
            $table->string('city', 120)->nullable()->change();
            $table->string('address', 120)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name', 40)->nullable()->change();
            $table->string('middle_name', 25)->nullable()->change();
            $table->string('last_name', 35)->nullable()->change();
            $table->string('state', 35)->nullable()->change();
            $table->string('city', 30)->nullable()->change();
            $table->string('address', 60)->nullable()->change();
        });
    }
}
