<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->smallInteger('question_count')->default(-1)->comment('该分组下问题数量');
            $table->smallInteger('max_score')->default(-1)->comment('该分组下所有问题总的最高得分（单选题按最高选项分数，多选题按所有选项分数之和）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('question_count');
            $table->dropColumn('max_score');
        });
    }
}
