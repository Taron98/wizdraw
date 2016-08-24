<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned()->index();
            $table->string('username', 30)->unique();
            $table->string('password');
            $table->string('facebook_token', 255)->nullable();
            $table->string('device_id', 40)->unique();
            $table->timestamp('last_login_at');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')->on('clients')
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
        Schema::drop('users');
    }
}
