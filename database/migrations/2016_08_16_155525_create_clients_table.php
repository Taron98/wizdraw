<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateClientsTable
 */
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

            $table->integer('identity_type_id')->unsigned()->nullable()->index();
            $table->string('identity_number', 20)->nullable();
            $table->date('identity_expire')->nullable();
            $table->string('first_name', 40);
            $table->string('middle_name', 25)->nullable();
            $table->string('last_name', 35);
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone', 20)->nullable();
            $table->integer('default_country_id')->unsigned()->nullable();     // FK to cached administration_country
            $table->integer('resident_country_id')->unsigned()->nullable();     // FK to cached administration_country
            /*$table->string('state', 35);*/
            $table->string('city', 30)->nullable();
            $table->string('address', 60)->nullable();
            /*$table->string('zip', 10);*/
            $table->enum('client_type', ['sender', 'receiver'])->nullable();
            $table->boolean('did_setup')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

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
        Schema::dropIfExists('clients');
    }
}
