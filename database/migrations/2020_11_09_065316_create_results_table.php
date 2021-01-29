<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id');
            $table->text('results', 1000)->comment('回答结果');
            $table->unsignedInteger('age')->comment('年龄');
            $table->unsignedTinyInteger('sex')->comment('性别');
            $table->unsignedInteger('score')->comment('得分');
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
        Schema::dropIfExists('results');
    }
}
