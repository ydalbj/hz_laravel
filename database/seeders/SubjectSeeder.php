<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert(
            [
                'id' => 1,
                'title' => 'test subject1',
                'description' => 'test description1',
            ]
        );

        DB::table('questions')->insert([
            [
                'id' => 1,
                'subject_id' => 1,
                'name' => 'age',
                'title' => '孩子年龄',
                'is_required' => 1,
                'is_hide' => 0,
                'type' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'subject_id' => 1,
                'name' => 'sex',
                'title' => '孩子性别',
                'is_required' => 1,
                'is_hide' => 0,
                'type' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'subject_id' => 1,
                'name' => 'cd',
                'title' => '客户受教育程度',
                'is_required' => 1,
                'is_hide' => 0,
                'type' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'subject_id' => 1,
                'name' => 'dux',
                'title' => '这是一道多选题',
                'is_required' => 1,
                'is_hide' => 0,
                'type' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('answers')->insert([
            [
                'id' => 1,
                'question_id' => 2,
                'title' => '男孩',
                'score' => 0,
            ],
            [
                'id' => 2,
                'question_id' => 2,
                'title' => '女孩',
                'score' => 0,
            ],
            [
                'id' => 3,
                'question_id' => 3,
                'title' => '小学',
                'score' => 1,
            ],
            [
                'id' => 4,
                'question_id' => 3,
                'title' => '中学',
                'score' => 2,
            ],
            [
                'id' => 5,
                'question_id' => 3,
                'title' => '大学',
                'score' => 3,
            ],
            [
                'id' => 6,
                'question_id' => 3,
                'title' => '幼儿园',
                'score' => 0,
            ],
            [
                'id' => 7,
                'question_id' => 4,
                'title' => 'A:选我',
                'score' => 1,
            ],
            [
                'id' => 8,
                'question_id' => 4,
                'title' => 'B:选我',
                'score' => 1,
            ],
            [
                'id' => 9,
                'question_id' => 4,
                'title' => 'C:选我',
                'score' => 1,
            ],
            [
                'id' => 10,
                'question_id' => 4,
                'title' => 'D:选我',
                'score' => 1,
            ],
            [
                'id' => 11,
                'question_id' => 4,
                'title' => 'E:选我',
                'score' => 1,
            ],
        ]);
    }
}
