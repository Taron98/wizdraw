<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTransfersTable
 */
class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('transaction_number', 15);
            $table->integer('client_id')->unsigned()->index();
            $table->integer('receiver_client_id')->unsigned()->index();
            $table->integer('type_id')->unsigned()->index();
            $table->integer('bank_account_id')->unsigned()->nullable()->index();
            $table->integer('receiver_country_id')->unsigned()->index();
            $table->integer('sender_country_id')->unsigned()->index();

            $table->decimal('amount', 14, 4);
            $table->decimal('commission', 10, 4);

            $table->integer('status_id')->unsigned()->index();
            $table->integer('receipt_id')->unsigned()->nullable()->index();

            $table->text('note')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('receiver_client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('type_id')
                ->references('id')->on('transfer_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('bank_account_id')
                ->references('id')->on('bank_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('status_id')
                ->references('id')->on('transfer_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('receipt_id')
                ->references('id')->on('transfer_receipts')
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
        Schema::dropIfExists('transfers');
    }
}
