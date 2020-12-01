<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForagesForsexToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->tinyInteger('min_age')->default(-1)->comment('最小适用年龄,-1代表无限制，单位`月`');
            $table->tinyInteger('max_age')->default(-1)->comment('最大适用年龄,-1代表无限制，单位`月`');
            $table->tinyInteger('for_sex')->default(-1)->comment('适用性别，-1代表无限制');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('min_age');
            $table->dropColumn('max_age');
            $table->dropColumn('for_sex');
        });
    }
}
