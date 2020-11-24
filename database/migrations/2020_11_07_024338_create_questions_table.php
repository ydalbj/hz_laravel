<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id')->index();
            $table->unsignedInteger('group_id')->default(0);
            $table->string('name', 50);
            $table->string('title', 255);
            $table->unsignedTinyInteger('is_required')->comment('是否必填')->default(1);
            $table->unsignedTinyInteger('is_hide')->comment('是否隐藏')->default(0);
            $table->unsignedTinyInteger('type')->comment('题目类型：0-单选题，1-多选题，2-填空题，3-数字');
            // $table->integer('order')->comment('排序，按order值从小到大排列');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
