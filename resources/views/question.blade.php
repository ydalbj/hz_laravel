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
        <!-- <ul id="progressbarBtn1" class="mui-pagination">
             <li><a href="javascript:;" data-progress="10">10%</a></li>
             <li><a href="javascript:;" data-progress="30">30%</a></li>
             <li><a href="javascript:;" data-progress="50">50%</a></li>
             <li><a href="javascript:;" data-progress="100">100%</a></li>
         </ul>-->
    </div>
    <form class="mui-card indexCard">
        <div class="mui-card-header mui-card-media" style="height:35vw;background-image:url(../img/b1.jpg)"></div>
        <div class="mui-card-content">
            <div class="mui-card-content-inner">
                <p>尊敬的健康管理客户：</p>
                <p style="color: #333;">
                    您好！
                    <br/>欢迎您体验健康管理服务。在对您提供个性化的健康管理服务之前，需要充分了解您的疾病史和日常生活习惯，以期了解您目前的健康状况，并评估可能存在的健康风险。健康管理师将根据主要疾病的风险评估报告制定健康促进计划，帮助您建立起更加健康的生活方式.我们充分尊重个人信息的隐私保护，任何个人或机构未经您的许可或授权，均不能获得您任何个人信息。
                    请您认真、完整回答以下每个问题。
                    感谢您的合作！</p>
            </div>
        </div>
        <div class="mui-card-footer">
            <!-- <a class="mui-card-link">返回</a> -->
            <!-- <a type="button" class="mui-card-link nextQuestion">开始</a> -->
            <button type="button" class="mui-btn mui-btn-primary mui-btn-block nextQuestion">开始测试</button>
        </div>
    </form>
</div>
<div id="info">

</div>
<div hidden>
    <form method="post" action="/subject/{{$subject_id}}/answer" id="results-form">
        @csrf
        <input type='text' name="results" />
    </form>
</div>
<script>
    var questions = JSON.parse('{!! $questions !!}');
    var subject_id = {{$subject_id ?? ''}};
</script>
<script src="{{URL::asset('/js/quesBuild.js')}}"></script>
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
</html>