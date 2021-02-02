<div class="content-body">
    <div>
        <h3>{{$title}}<span class="small"> 得分：{{$score}}</span> </h3> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">基本信息</div>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            微信名：{{$result->wechat_name ?? ''}}
                        </li>
                        <li>
                            手机号：{{$result->telephone ?? ''}}
                        </li>
                        <li>
                            职业：{{$result->occupation ?? ''}}
                        </li>
                        <li>
                            生日：{{$result->birthday ?? ''}}
                        </li>
                        <li>
                            性别：{{$result->sex_string ?? ''}}
                        </li>
                        <li>
                            地区：{{$result->region ?? ''}}
                        </li>
                        <li>
                            顺产/难产/剖腹产：{{$result->birthinfo ?? ''}}
                        </li>
                        <li>
                            第几胎：{{$result->birthorder ?? ''}}
                        </li>
                        <li>
                            谁带孩子：{{$result->whotakecare ?? ''}}
                        </li>
                        <li>
                            孕期异常：
                            @if (isset($result->birth_situations))
                            @foreach (json_decode($result->birth_situations, true) as $v)
                                {{$v}}
                            @endforeach
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div
            <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">测评结果</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                                @if (isset($result->group_evaluations))
                                @foreach (json_decode($result->group_evaluations, true) as $v)
                                    <li>
                                        {{$v['group_name']}}：
                                            @if (isset($v['age_formatted']))
                                                测评年龄：{{$v['age_formatted']}}；
                                            @endif
                                            @if (isset($v['level_standard']))
                                                测评程度：@if ($v['level_standard'] > 4) 重 @elseif ($v['level_standard'] < 2) 轻 @else 中 @endif;
                                            @endif
                                            测评分数：{{$v['score']}};
                                    </li>
                                @endforeach
                                @endif
                        </ul>
                    </div>
            </div>
            @foreach ($questions as $q)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$q->title}}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($q->answers as $a)

                            <div class="">
                            @if ($q->type == 0 || $q->type == 1)
                                <input
                                    type="checkbox"
                                    name="a{{$loop->index}}"
                                    id="todoCheck{{$loop->index}}"
                                    @if (in_array($a->id, $user_answers[intval($q->id)])) checked @endif
                                >
                                <label for="todoCheck{{$loop->index}}" class=""></label>
                                {{$a->title}}
                            @else
                                {{$user_answers[$q->id] ?? ''}}
                            @endif
                            </div>
                        @endforeach
                    </div>
                    
                </div>

            @endforeach
        </div>
    </div>
</div>


<script>
Dcat.ready(function () {
    // js代码也可以放在模板里面
});
</script>