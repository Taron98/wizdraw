<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterFeedbacksTable
 */
class AlterFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->integer('transfer_id')->unsigned()->nullable()->change();
            $table->integer('feedback_question_id')->unsigned()->nullable()->change();
            $table->smallInteger('rating')->nullable()->change();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->integer('transfer_id')->unsigned()->change();
            $table->integer('feedback_question_id')->unsigned()->change();
            $table->smallInteger('rating')->change();
        });
    }
}
