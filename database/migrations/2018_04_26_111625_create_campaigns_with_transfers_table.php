<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsWithTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_with_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned()->index();
            $table->integer('transfer_id')->unsigned()->index();
            $table->string('transaction_number', 15);

            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');

            $table->foreign('transfer_id')
                ->references('id')->on('transfers')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns_with_transfers');
    }
}
