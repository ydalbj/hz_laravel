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
        <div class="mui-card-header mui-card-media" style="height:100vw;background-image:url(/img/result.png)"></div>
    </form>
</div>
</body>