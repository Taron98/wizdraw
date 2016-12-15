<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddColorToTransferStatusesTable
 */
class AddColorToTransferStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_statuses', function (Blueprint $table) {
            $table->string('color', 7)->after('original_status');
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
            $table->dropColumn('color');
        });
    }
}
