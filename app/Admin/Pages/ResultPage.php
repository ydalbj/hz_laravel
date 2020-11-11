<?php

namespace App\Admin\Pages;

use App\Models\Question;
use App\Models\Result;
use App\Models\Subject;
use Illuminate\Contracts\Support\Renderable;
use Dcat\Admin\Admin;
use Illuminate\Support\Facades\Request;

class ResultPage implements Renderable
{
    // 定义页面所需的静态资源，这里会按需加载
    public static $js = [
        // js脚本不能直接包含初始化操作，否则页面刷新后无效
        // 'xxx/js/page.min.js',
    ];
    public static $css = [
        // 'xxx/css/page.min.css',
    ];

    public function script()
    {
        return <<<JS
        console.log('所有JS脚本都加载完了');
        // 初始化操作写在这里
JS;        
    }

    public function render()
    {
        // 在这里可以引入你的js或css文件
        Admin::js(static::$js);
        Admin::css(static::$css);

        // 需要在页面执行的JS代码
        // 通过 Admin::script 设置的JS代码会自动在所有JS脚本都加载完毕后执行
        Admin::script($this->script());

        $subject_id = Request::input('subject_id');
        $questions = Question::where('subject_id', $subject_id)->with('answers')->get();
        $id = Request::input('id');
        $result = Result::find($id);
        $results = json_decode($result->results, true);
        $subject = Subject::find($subject_id);

        return view('admin.pages.result_page', [
                'questions' => $questions,
                'results' => $results,
                'title' => $subject->title,
                'score' => $result->score,
            ])
            ->render();
    }
}