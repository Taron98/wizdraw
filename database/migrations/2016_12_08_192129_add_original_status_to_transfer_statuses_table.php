<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddOriginalStatusToTransferStatusesTable
 */
class AddOriginalStatusToTransferStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_statuses', function (Blueprint $table) {
            $table->string('original_status', 40)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_statuses', function (Blueprint $table) {
            $table->dropColumn('original_status');
        });
    }
}
