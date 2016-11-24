<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTransferReceiptsTable
 */
class CreateTransferReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_receipts', function (Blueprint $table) {
            $table->increments('id');

            // todo: change typings
            $table->string('number');
            $table->string('expense');
            $table->enum('expense_type', ['fluent', 'special'])->default('fluent');
            $table->string('remark');
            $table->text('note')->nullable();

            $table->timestamp('issued_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_receipts');
    }
}
