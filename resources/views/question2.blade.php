<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="{{URL::asset('js/base.js')}}"></script>
    <title>儿童语言发育测试</title>
    <!--mui源码参考地址
    http://www.dcloud.io/hellomui/index.html
    -->
</head>
<body>
<div class="mui-content">
    <div id="going" class="mui-text-center">
        <p class="mui-progressbar mui-progressbar-in" data-progress="20"><span style="transform: translate3d(0%, 0px, 0px);"></span></p>
    </div>
    <form class="mui-card indexCard">
        <div class="mui-card-header mui-card-media" style="height:35vw;background-image:url(../img/b1.jpg)"></div>
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <p style="color: #333;"> 
                    您好！<br />
                    {{$subject->description}} 
                </p>
            </div>
        </div>
        <div class="mui-card-footer">
            <!-- <a class="mui-card-link">返回</a> -->
            <!-- <a type="button" class="mui-card-link nextQuestion">开始</a> -->
            <button type="button" class="mui-btn mui-btn-primary mui-btn-block nextQuestion">开始测试</button>
        </div>
    </form>

<!-- 问题卡片 -->
@foreach ($questions2 as $q)
<form class="mui-card quesition">
    <div class="mui-card-header">
        <p name="{{$q['name']}}">
            {{$q['title']}}
            @if ($q['is_required'])
                <span class="mui-badge mui-badge-danger mui-badge-inverted moust-do mui-left">*</span>
            @endif
        </p>
    </div>
    <div class="mui-card-content">
        <div class="mui-card-content-inner">
            <ul class="mui-table-view">
                @switch($q['type'])
                    @case(0)
                        @foreach ($q['answers'] as $a)
                        <li class="mui-table-view-cell mui-radio mui-left">
                            <input type="radio" name="{{$q['name']}}" data-field="{{$a['id']}}" @if($q['is_required']) required @endif>
                            {{$a['title']}}
                        </li>
                        @endforeach
                    @break

                    @case(1)
                        @foreach ($q['answers'] as $a)
                        <li class="mui-table-view-cell mui-checkbox mui-left">
                            <input type="checkbox" name="{{$q['name']}}" data-field="{{$a['id']}}" @if($q['is_required']) required @endif>
                            {{$a['title']}}
                        </li>
                        @endforeach
                    @break

                    @case(2)
                        @foreach ($q['answers'] as $a)
                            <div class="mui-input-row">
                                <label for="">{{$a['title']}}</label>
                                <input type="text" data-field="{{$a['id']}}" name="{{$q['name']}}" class="mui-input-clear" placeholder="请在此输入" @if($q['is_required']) required @endif>
                            </div>
                        @endforeach
                    @break

                    @case(3)
                        @foreach ($q['answers'] as $a)
                            <div class="mui-input-row">
                                <label>{{$a['title']}}</label>
                                <div class="mui-numbox" data-numbox-min="{{$q['minnum']}}" data-numbox-max="{{$q['maxnum']}}" data-numbox-step="{{$q['stepnum']}}">
                                    <button class="mui-btn mui-btn-numbox-minus" type="button">-</button>
                                    <input type="number" data-field="{{$a['id']}}" name="{{$q['name']}}" class="mui-input-numbox" value="3" @if($q['is_required']) required @endif>
                                    <button class="mui-btn mui-btn-numbox-plus" type="button">+</button>
                                </div>
                            </div>
                        @endforeach
                    @break

                    @default
                    @break
                @endswitch
            </ul>
        </div>
    </div>
    <input style="display: none;"type="submit" class="sub" value="submit" />
    <div class="mui-card-footer"> 
        <a class="mui-card-link prevQuestion">上一页</a>
        <a type="submit"  class="mui-card-link nextQuestion">下一页</a> 
    </div>
</form>
@endforeach

    <!-- 提交页面 -->
    <form class="mui-card" style="display:none">
        <div class="mui-card-header mui-card-media" style="height:35vw;background-image:url(../img/b1.jpg)"></div>
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <p style="color:#333;">{{$subject->description}}</p>
            </div>
        </div>
        <div class="mui-card-footer">
            <a class="mui-card-link prevQuestion">上一页</a>
            <button id="confirmBtn" type="button" class="mui-btn mui-btn-primary">完成提交</button>
            <a class="mui-card-link turnToIndex">首页</a>
        </div>
    </form>

    <!-- 隐藏提交结果表单 -->
    <div hidden>
        <form method="post" action="/subject/{{$subject_id}}/answer" id="results-form">
            @csrf
            <input type='text' name="results" />
        </form>
    </div>
</div>
<script src="{{URL::asset('/js/quesSet.js')}}"></script>
<script src="{{URL::asset('/js/commit.js')}}"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config(<?php echo app('wechat.official_account')->jssdk->buildConfig(array('updateAppMessageShareData', 'updateTimelineShareData')) ?>);
    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
    wx.updateAppMessageShareData({ 
        title: '儿童语言发育测试', // 分享标题
        desc: '随便写点什么', // 分享描述
        link: 'http://hz.hahahoho.top/subject/1', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'http://www.youxibeijia.com/images/yykt/y_06.jpg', // 分享图标
        success: function () {
        // 设置成功
        }
    });
      wx.updateTimelineShareData({ 
        title: '儿童语言发育测试', // 分享标题
        link: 'http://hz.hahahoho.top/subject/1', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'http://www.youxibeijia.com/images/yykt/y_06.jpg', // 分享图标
        success: function () {
        // 设置成功
        }
    });
    }); 
</script>
</body>