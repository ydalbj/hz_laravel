/**
 * Created by hkzt on 2016/11/24.
 */
//进度条
window.onload = function() {
    mui("#going").progressbar({progress:1}).show();
    var quesAllNum = $(".mui-card").length;

//开关
    mui.init({
        swipeBack:true //启用右滑关闭功能
    });
    mui('.mui-content .mui-switch').each(function() {
        //循环所有toggle
        //toggle.classList.contains('mui-active') //可识别该toggle的开关状态
        // this.parentNode.querySelector('span').innerText = '状态：' + (this.classList.contains('mui-active') ? 'true' : 'false');
        //.this.classList.contains('mui-active') ? $(this).parent().siblings("").show(1000):$(this).parent().next().hide(1000);var isYinCang = true;
        if (this.classList.contains('mui-active')) {
            $(this).parent().siblings("").show(1000);
            isYinCang = false;
        }else{
            $(this).parent().next().hide(1000);
            isYinCang = true;
        }
        /**
         * toggle 事件监听
         */
        this.addEventListener('toggle', function(event) {

            //event.detail.isActive 可直接获取当前状态
            //this.parentNode.querySelector('span').innerText = '状态：' + (event.detail.isActive ? 'true' : 'false');
            if(event.detail.isActive==false){
                //this.parentNode.querySelector('div.mui-card-content').setAttribute('dtyle','display:none');
                //this.parentNode.querySelector('.mui-card-footer').style.display="none";
                $(this).parent().next().hide(1000);
                isYinCang = true;
                // $(".mui-card-content").hide();
            }else{
                // $(".mui-card-content").show();
                isYinCang = false;
                $(this).parent().siblings().not(".sub").show(1000);
            }
        });
    });
//每页一个卡片
//;$("#going").next().show();
    mui('.prevQuestion').each(function(){//上一题
        $(this).parent().parent().prev().hasClass("mui-card")?$(this).attr("onclick","prevQues()"):$(this).addClass("disabled-mouse");
        this.addEventListener('click', function() {
            $(this).parent().parent("form").hide(800);
            $(this).parent().parent().prev("form").show(800);
            //进度条
            var quesNowNum =  $(this).parent().parent().prevAll(".mui-card").length-1;
            mui("#going").progressbar().setProgress(quesNowNum/quesAllNum*100);
        });
    });
    mui('.nextQuestion').each(function(){//下一题
        $(this).parent().parent().next().hasClass("mui-card")?$(this).attr("onclick","nextQues()"):$(this).addClass("disabled-mouse");
        this.addEventListener('click', function() {
            $(this).parent().prev(".sub").click(function(){
                return false;//否则会提交刷新
            });
            if($(this).parent().prevAll(".mui-card-header").find("p[name='yc']").length>0 && isYinCang === true){
                $(this).parent().parent("form").hide(800);
                $(this).parent().parent().next("form").show(800);
                var quesNowNum = $(this).parent().parent().prevAll(".mui-card").length + 1;
                mui("#going").progressbar().setProgress(quesNowNum / quesAllNum * 100);
            }else{
                var required = $(this).parent().prevAll(".mui-card-content").find("input").prop("required");
                if(required==true){
                    var YNrequired = $(this).parent().prevAll(".mui-card-content").find("input");
                    var YNrequiredLength = YNrequired.length;
                    var isNull = false;
                    for (var i=0;i<YNrequiredLength;i++){

                        if(YNrequired[i].validationMessage!=""){
                            var tip = '<span class="weitianTip">'+YNrequired[i].validationMessage+'</span>';
                            //$("p[name='"+YNrequired[i].name+"']").append(YNrequired[i].validationMessage);//在题目后面加提示
                            if($("p[name='"+YNrequired[i].name+"']").children("span.weitianTip").length>0){//只显示一个提示
                                return false
                            }else{
                                var removeTip =  function removeTip(){
                                    $("p[name='"+YNrequired[i].name+"']").children("span.weitianTip").remove();
                                }
                                $("p[name='"+YNrequired[i].name+"']").append(tip);
                                setTimeout(removeTip,10000);
                            }

                            isNull=true;
                            break;
                        }
                    }
                    if(isNull==false){
                        $(this).parent().parent("form").hide(800);
                        $(this).parent().parent().next("form").show(800);
                        var quesNowNum = $(this).parent().parent().prevAll(".mui-card").length + 1;
                        mui("#going").progressbar().setProgress(quesNowNum / quesAllNum * 100);
                    }
                } else{
                    $(this).parent().parent("form").hide(800);
                    $(this).parent().parent().next("form").show(800);
                    var quesNowNum = $(this).parent().parent().prevAll(".mui-card").length + 1;
                    mui("#going").progressbar().setProgress(quesNowNum / quesAllNum * 100);
                }
            }
        });
    });
//完成提交
    document.getElementById("confirmBtn").addEventListener('tap', function() {
        var btnArray = ['是', '否'];
        mui.confirm('是否完成本次本卷并提交？', 'Hello MUI', btnArray, function(e) {
            if (e.index == 0) {
                mui("#going").progressbar({progress:100}).show();
                getEntity(".quesition");
                console.log(result);
            } else {
                info.innerText = '你放弃了本次问卷的提交'
            }
        })
    });};
//返回首页
$(".turnToIndex").click(function(){
    $(this).parent().parent("form").hide(800);
    $(this).parent().parent().prevAll(".indexCard").show(800);
    //进度条
    mui("#going").progressbar({progress:1}).show();
})
