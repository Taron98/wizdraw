<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferNaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_natures', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('transfer_id')->unsigned()->index();
            $table->integer('nature_id')->unsigned()->index();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->foreign('transfer_id')
                ->references('id')->on('transfers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('nature_id')
                ->references('id')->on('natures')
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
        Schema::dropIfExists('transfer_natures');
    }
}
