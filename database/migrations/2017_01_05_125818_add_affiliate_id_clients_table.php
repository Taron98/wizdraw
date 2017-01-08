<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddAffiliateIdClientsTable
 */
class AddAffiliateIdClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('affiliate_id')->unsigned()->nullable()->index()->after('is_approved');

            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['affiliate_id']);
            $table->dropColumn('affiliate_id');
        });
    }
}
