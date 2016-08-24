<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('identity_type_id')->unsigned()->index();
            $table->string('identity_number', 20);
            $table->date('identity_expire');
            $table->string('first_name', 40);
            $table->string('middle_name', 25)->nullable();
            $table->string('last_name', 35);
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('phone', 20);
            $table->integer('resident_country_id')->unsigned();     // FK to cached administration_country
            $table->string('state', 35);
            $table->string('city', 30);
            $table->string('address', 60);
            $table->string('zip', 10);

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('identity_type_id')
                ->references('id')->on('identity_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clients');
    }
}
