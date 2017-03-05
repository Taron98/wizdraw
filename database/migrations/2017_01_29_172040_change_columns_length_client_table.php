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
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name', 70)->change();
            $table->string('middle_name', 70)->change();
            $table->string('last_name', 70)->change();
            $table->string('state', 120)->change();
            $table->string('city', 120)->change();
            $table->string('address', 120)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

//        Schema::table('clients', function (Blueprint $table) {
//            $table->string('first_name', 40)->change();
//            $table->string('middle_name', 25)->change();
//            $table->string('last_name', 35)->change();
//            $table->string('state', 35)->change();
//            $table->string('city', 30)->change();
//            $table->string('address', 60)->change();
//        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name', 70)->change();
            $table->string('middle_name', 70)->change();
            $table->string('last_name', 70)->change();
            $table->string('state', 120)->change();
            $table->string('city', 120)->change();
            $table->string('address', 120)->change();
        });
    }
}
