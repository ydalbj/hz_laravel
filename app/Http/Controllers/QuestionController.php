<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Result;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index(int $id)
    {
        $questions = Question::where('subject_id', $id)
            ->where('is_hide', 0)
            ->with(['answers' => function ($query) {
                $query->select('id', 'question_id', 'title');
            }])
            ->get()
            ->toArray();

        $questions = $this->transformed($questions);
        return view('question', [
            'questions' => json_encode($questions),
            'subject_id' => $id,
        ]);
    }

    private function transformed(array $questions)
    {
        $transformed = [];
        $i = 0;
        foreach ($questions as $q) {
            switch ($q['type']) {
                // 选择题
                case 0:
                case 1:
                    break;

                // 填空题
                case 2:
                    // 如果是填空题，questiondatafield字段直接设置为question name值
                    $transformed[$i]['questiondatafield'] = $q['name'];
                    break;

                // 数字填空题
                case 3:
                    // 如果是填空题，questiondatafield字段直接设置为question name值
                    $transformed[$i]['questiondatafield'] = $q['name'];

                    // 暂时写死为年龄，后期如果扩展需判断一下
                    $transformed[$i]['minnum'] = 0;
                    $transformed[$i]['maxnum'] = 15;
                    $transformed[$i]['stepnum'] = 1;

                    break;
                default:
                    break;
            }

            $transformed[$i]['questitle'] = $q['title'];
            $transformed[$i]['questionName'] = $q['name'];
            $transformed[$i]['questionType'] = $q['is_required'];
            $transformed[$i]['questionType1'] = $q['is_hide'];
            $transformed[$i]['questionType2'] = $q['type'];

            $answers = [];
            $answers_data_field = [];
            foreach( $q['answers'] as $a) {
                $answers[] = $a['title'];
                $answers_data_field[] = $a['id'];
            }

            if (!empty($answers)) {
                $transformed[$i]['answer'] = $answers;
                $transformed[$i]['answerdatafield'] = $answers_data_field;
            } else {
                // 比如填空题，如果没有设置answers,则把问题title作为answer显示
                $transformed[$i]['answer'] = [$q['title']];
            }

            $i++;
        }

        return $transformed;
    }

    public function answer(Request $request, $id)
    {
        $results = $request->input('results');
        if (!$results) {
            return "非法请求";
        }

        $results = json_decode($results, true);

        // 计算结果
        $score = $this->calculateScore($results);

        $result = new Result();
        $result->subject_id = $id;
        $result->results = json_encode($results);
        $result->save();

        // 保存数据库
        return ['socre' => $score];
    }

    public function calculateScore(array $results)
    {
        $answer_ids = array_keys($results);

        $score = Answer::whereIn('id', $answer_ids)->sum('score');
        Log::debug('score:' . $score);
        return $score;
    }
}
