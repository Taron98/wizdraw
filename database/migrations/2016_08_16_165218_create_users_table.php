<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateUsersTable
 */
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
            $table->string('email', 255)->unique()->nullable();
            /* $table->string('username', 30)->nullable()->unique(); */
            $table->string('password', 255)->nullable();
            $table->string('facebook_id', 20)->nullable();
            $table->string('facebook_token', 300)->nullable();
            $table->timestamp('facebook_token_expire')->nullable();
            $table->string('device_id', 40)->unique();
            $table->unsignedMediumInteger('verify_code')->nullable();
            $table->timestamp('verify_expire')->nullable();
            $table->boolean('is_pending')->default(true);

            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
