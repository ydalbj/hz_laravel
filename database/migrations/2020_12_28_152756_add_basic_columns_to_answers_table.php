<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBasicColumnsToAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->string('wechat_name', 100)->default('')->comment('微信号');
            $table->unsignedInteger('telephone')->nullable()->comment('手机号');
            $table->string('sex_string', 10)->default('')->comment('性别');
            $table->string('occupation', 50)->default('')->comment('职业');
            $table->string('birth_info', 10)->default('')->comment('顺产/早产/剖腹产');
            $table->string('birth_order', 10)->default('')->comment('第几胎');
            $table->string('who_take_care', 10)->default('')->comment('谁带孩子');
            $table->string('region', 50)->default('')->comment('地区');
            $table->string('birthday', 20)->default('')->comment('生日');
            $table->json('birth_situations')->comment('孕期及分娩异常情况');
            $table->json('group_evaluations')->comment('分组评估');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('wechat_name');
            $table->dropColumn('telephone');
            $table->dropColumn('sex_string');
            $table->dropColumn('occupation');
            $table->dropColumn('birth_info');
            $table->dropColumn('birth_order');
            $table->dropColumn('who_take_care');
            $table->dropColumn('region');
            $table->dropColumn('birthday');
            $table->dropColumn('birth_situations');
            $table->dropColumn('group_evaluations');
        });
    }
}
