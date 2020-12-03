<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Result;
use App\Models\Answer;
use App\Services\ResultService;
use Log;

class ApiController extends Controller
{
    public function subject(int $id)
    {
        $questions = Question::where('subject_id', $id)
        ->where('is_hide', 0)
        ->with(['answers' => function ($query) {
            $query->select('id', 'question_id', 'title');
        }])
        ->get();

        return $questions;
    }

    public function answer(Request $request, $id, ResultService $resultService)
    {
        $results = $request->input('results');
        if (!$results) {
            return "非法请求";
        }

        $results = json_decode($results, true);
        /*
        $results = [
            2 => true,
            12 => 3,
            14 => true,
            35 => true ,
            38 => true ,
            40 => true ,
            42 => true ,
            46 => true ,
            49 => true ,
            50 => true ,
            53 => true ,
            55 => true ,
            56 => true ,
        ];
        */

        // 计算分组结果
        $group_results = $resultService->getGroupResult($results);

        // 计算结果
        $score = $this->calculateScore($results);

        $result = new Result();
        $result->subject_id = $id;
        $result->score = $score;
        $result->results = json_encode($results);
        $result->group_results = json_encode($group_results);
        $result->save();
        return $this->formatResult($result);
    }

    private function formatResult(Result $result)
    {
        $data = [];
        $data['subject_id'] = $result->subject_id;
        $data['score'] = $result->score;
        $group_results = json_decode($result->group_results, true);

        $i = 0;
        foreach ($group_results as $v) {
            $data['group']['titles'][$i] = $v['title'];
            $data['group']['levels'][$i] = $v['level'];
            $data['group']['scores'][$i] = $v['score'];
            $i++;
        }

        return $data;
    }
    
    public function calculateScore(array $results)
    {
        $answer_ids = array_keys($results);

        $score = Answer::whereIn('id', $answer_ids)->sum('score');
        Log::debug('score:' . $score);
        return $score;
    }
}
