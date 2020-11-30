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
            $table->unsignedTinyInteger('min_age')->default(0)->comment('最小适用年龄,0代表无限制，单位`月`');
            $table->unsignedTinyInteger('max_age')->default(0)->comment('最大适用年龄,0代表无限制，单位`月`');
            $table->unsignedTinyInteger('for_sex')->nullable()->comment('适用性别，null代表无限制');
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
