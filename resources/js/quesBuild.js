/**
 * Created by hkzt on 2016/11/24.
 * this是指在each函数中每次遍历得到的一个问题测试期间用QUES变量代表
 * {"questitle":"题目","questionName":"题目的name","questionType":"1：必填；0：非必填 ","questionType1":"0：非隐藏题；1：隐藏题","questionType2":"0：单选题；1：多选题；2：填空题；3：数字填空题","answer":['答案列表']"minnum":"最小数字","maxnum":"最大数字","stepnum":"点击按钮每次增减数字"，"hideQuestion":[{...隐藏题},{...}]}
 */
var QUES = [{"questitle":"客户姓名","questionName":"name","questiondatafield":"test1" ,"questionType":"0","questionType1":"0","questionType2":"2","answer":['姓名']},
    {"questitle":"客户年龄","questionName":"age","questiondatafield":"test2" ,"questionType":"1","questionType1":"0","questionType2":"3","answer":['年龄'],"minnum":"18","maxnum":"130","stepnum":"1"},
    {"questitle":"客户受教育程度","questionName":"cd","questionType":"0","questionType1":"0","questionType2":"0","answer":['不识字','小学','初中','高中','中专/技校','大学本科/专科','研究生及以上'] ,"answerdatafield":["radio1","radio2","radio3","radio4","radio5","radio6"]},
    {"questitle":"这是一道多选题","questionName":"dux","questionType":"1","questionType1":"0","questionType2":"1","answer":['吃','和','睡','写程序','做ui'],"answerdatafield":["checkbox1","checkbox2","checkbox3","checkbox4","checkbox5"]},
    {"questitle":"这是一道隐藏题","questionName":"yc","questionType":"0","questionType1":"1","questionType2":"0","hideQuestion":[{"hideQuestitle":"客户姓名","hideQuestionName":"hidename","hideQuestiondatafield":"test7","hideQuestionType":"1","hideQuestionType1":"0","hideQuestionType2":"2","hideQanswer":["姓名"]}, {"hideQuestitle":"客户年龄","hideQuestiondatafield":"test8","hideQuestionName":"hideage","hideQuestionType":"1","hideQuestionType1":"0","hideQuestionType2":"3","hideQanswer":["年龄"],"hideQminnum":"18","hideQmaxnum":"130","hideQstepnum":"1"}, {"hideQuestitle":"客户受教育程度","hideQuestiondatafield":"test9","hideQuestionName":"hidedx","hideQuestionType":"1","hideQuestionType1":"0","hideQuestionType2":"0","hideQanswer":["不识字","小学","初中","高中","中专/技校","大学本科/专科","研究生及以上"],"hideQanswerdatafield":["hideQradio1","hideQradio2","hideQradio3","hideQradio4","hideQradio5","hideQradio6"]},{"hideQuestitle":"这是一道多选题","hideQuestiondatafield":"test10","hideQuestionName":"hidedux","hideQuestionType":"1","hideQuestionType1":"0","hideQuestionType2":"1","hideQanswer":['吃','和','睡','写程序','做ui'],"hideQanswerdatafield":["hideQcheckbox1","hideQcheckbox2","hideQcheckbox3","hideQrcheckbox4","hideQcheckbox5"]}]},

    //{"questitle":"这是一道单选题","questionName":"dx","questiondatafield":"test6" ,"questionType":"1","questionType1":"0","questionType2":"0","answer":['吃','和','睡']},
];
$.each(QUES,function(n,value){
    var maxLength = QUES.length;
    var _this = this;
    var Question = "";
//添加card
    Question +=' <form  class="mui-card quesition"><div class="mui-card-header"> <p  name="'+_this.questionName+'">'+_this.questitle+'';
    _this.questionType==0?Question+='</p>':Question+='<span class="mui-badge mui-badge-danger mui-badge-inverted moust-do mui-left">*</span></p>';
    _this.questionType1==0?noHideQues(_this):hideQues();

    function hideQues(){
        var HIDEQUES = _this.hideQuestion;
        Question+='<div class="mui-switch mui-right min-switch"> <div class="mui-switch-handle"></div> </div>';
        Question +='</div><div class="mui-card-content"><div class="mui-card-content-inner"><ul class="mui-table-view">' ;
        $.each(HIDEQUES,function(n,value){
            Question +='<p name="'+this.hideQuestionName+'">'+this.hideQuestitle+'';
            this.hideQuestionType==0?Question+='</p>':Question+='<span class="mui-badge mui-badge-danger mui-badge-inverted moust-do mui-left">*</span></p>';
            var b = this.hideQuestionType2;
            switch(b){
                //单项选择
                case "0":
                    var answerNum = this.hideQanswer.length;
                    for(var i=0;i<answerNum;i++){
                        //Question+='<li class="mui-table-view-cell mui-radio mui-left"> <input name="radio" type="radio">'+this.hideQanswer[i]+'</li>';
                        Question+='<li class="mui-table-view-cell mui-radio mui-left"> <input  data-field="'+this.hideQanswerdatafield[i]+'" value=""  name="'+this.hideQuestionName+'" type="radio" ';
                        this.hideQuestionType==0?Question+='>'+this.hideQanswer[i]+'</li>':Question+='required>'+this.hideQanswer[i]+'</li>';
                    };
                    break;
                case "1"://多项选择
                    var answerNum = this.hideQanswer.length;
                    for(var i=0;i<answerNum;i++)
                    {
                        //Question+='<li class="mui-table-view-cell mui-checkbox mui-left"> <input name="checkbox" type="checkbox">'+this.hideQanswer[i]+'</li>';
                        Question+='<li class="mui-table-view-cell mui-checkbox mui-left"> <input  data-field="'+this.hideQanswerdatafield[i]+'" value="" name="'+this.hideQuestionName+'" type="checkbox" ';
                        if(i===0){
                            this.hideQuestionType==0?Question+='>'+this.hideQanswer[i]+'</li>':Question+='required>'+this.hideQanswer[i]+'</li>';
                        }else{
                            Question+='>'+this.hideQanswer[i]+'</li>';
                        }
                    };
                    break;
                case "2"://输入框
                    var answerNum = this.hideQanswer.length;
                    for(var i=0;i<answerNum;i++)
                    {
                        // Question+='<div class="mui-input-row"> <label>'+this.hideQanswer[i]+'</label> <input type="text" class="mui-input-clear" placeholder="请在此输入"></div>';
                        Question+='<div class="mui-input-row" > <label>'+this.hideQanswer[i]+'</label> <input  data-field="'+this.hideQuestiondatafield+'" value="" type="text" name="'+this.hideQuestionName+'" class="mui-input-clear" placeholder="请在此输入" ';
                        this.hideQuestionType==0?Question+='></div>':Question+='required></div>';
                    };
                    break;
                case "3"://数字输入框
                    var answerNum = this.hideQanswer.length;
                    for(var i=0;i<answerNum;i++)
                    {
                        //Question+='<div class="mui-input-row"> <label>'+this.hideQanswer[i]+'</label>  <div class="mui-numbox" data-numbox-min="'+this.hideQminnum+'" data-numbox-max="'+this.hideQmaxnum+'" data-numbox-step="'+this.hideQstepnum+'"> <button class="mui-btn mui-btn-numbox-minus" type="button">-</button> <input class="mui-input-numbox" type="number"> <button class="mui-btn mui-btn-numbox-plus" type="button">+</button> </div> </div>';
                        Question+='<div class="mui-input-row"> <label>'+this.hideQanswer[i]+'</label>  <div class="mui-numbox" data-numbox-min="'+this.hideQminnum+'" data-numbox-max="'+this.hideQmaxnum+'" data-numbox-step="'+this.hideQstepnum+'"> <button class="mui-btn mui-btn-numbox-minus" type="button">-</button> <input  data-field="'+this.hideQuestiondatafield+'" value="" name="'+this.hideQuestionName+'" class="mui-input-numbox" type="number"';
                        this.hideQuestionType==0?Question+='>':Question+='required>';
                        Question+='<button class="mui-btn mui-btn-numbox-plus" type="button">+</button> </div> </div>';
                    };
                    break;
            }

        });
    }

    function noHideQues(_this){
        Question +='</div><div class="mui-card-content"><div class="mui-card-content-inner"><ul class="mui-table-view">' ;
        var a = _this.questionType2
        switch(a)
        {
            //单项选择
            case "0":
                var answerNum = _this.answer.length;
                for(var i=0;i<answerNum;i++){
                    Question+='<li class="mui-table-view-cell mui-radio mui-left"> <input data-field="'+_this.answerdatafield[i]+'" value=""  name="'+_this.questionName+'" type="radio" ';
                    _this.questionType==0?Question+='>'+_this.answer[i]+'</li>':Question+='required>'+_this.answer[i]+'</li>';
                };
                break;
            case "1"://多项选择
                var answerNum = _this.answer.length;
                for(var i=0;i<answerNum;i++)
                {
                    Question+='<li class="mui-table-view-cell mui-checkbox mui-left"> <input data-field="'+_this.answerdatafield[i]+'" value="" name="'+_this.questionName+'" type="checkbox" ';
                    if(i===0){
                        _this.questionType==0?Question+='>'+_this.answer[i]+'</li>':Question+='required>'+_this.answer[i]+'</li>';
                    }else{
                        Question+='>'+_this.answer[i]+'</li>';
                    }
                };
                break;
            case "2"://输入框
                var answerNum = _this.answer.length;
                for(var i=0;i<answerNum;i++)
                {
                    Question+='<div  class="mui-input-row"> <label>'+_this.answer[i]+'</label> <input data-field="'+_this.questiondatafield+'" value="" type="text" name="'+_this.questionName+'" class="mui-input-clear" placeholder="请在此输入" ';
                       _this.questionType==0?Question+='></div>':Question+='required></div>';
                };
                break;
            case "3"://数字输入框
                var answerNum = _this.answer.length;
                for(var i=0;i<answerNum;i++)
                {
                    Question+='<div data-field="'+_this.questiondatafield+'" class="mui-input-row"> <label>'+_this.answer[i]+'</label>  <div class="mui-numbox" data-numbox-min="'+_this.minnum+'" data-numbox-max="'+_this.maxnum+'" data-numbox-step="'+_this.stepnum+'"> <button class="mui-btn mui-btn-numbox-minus" type="button">-</button> <input data-field="'+_this.questiondatafield+'" value="" name="'+_this.questionName+'" class="mui-input-numbox" type="number"';
                    _this.questionType==0?Question+='>':Question+='required>';
                    Question+='<button class="mui-btn mui-btn-numbox-plus" type="button">+</button> </div> </div>';
                };
                break;
        };
    }
    /*if(n==maxLength-1){
        Question+='</ul> </div> </div> <input style="display: none;"type="submit" class="sub" value="submit" /><div class="mui-card-footer"> <a class="mui-card-link prevQuestion">上一页</a> <button id="confirmBtn" type="button" class="mui-btn mui-btn-blue mui-btn-outlined mui-card-link">完成提交</button> <a class="mui-card-link turnToIndex ">首页</a> </div> </form>';
    }else{*/
        Question+='</ul> </div> </div> <input style="display: none;"type="submit" class="sub" value="submit" /><div class="mui-card-footer"> <a class="mui-card-link prevQuestion">上一页</a> <a type="submit"  class="mui-card-link nextQuestion">下一页</a> </div> </form>';
   /* };*/
    $(Question).appendTo(".mui-content");

});
var submiit = '';
submiit += ' <form class="mui-card " style="display: none"> <div class="mui-card-header mui-card-media" style="height:35vw;background-image:url(../img/b1.jpg)"></div> <div class="mui-card-content"> <div class="mui-card-content-inner"> <p>尊敬的健康管理客户：</p> <p style="color: #333;"> 您好:<br/>欢迎您体验健康管理服务。在对您提供个性化的健康管理服务之前，需要充分了解您的疾病史和日常生活习惯，以期了解您目前的健康状况，并评估可能存在的健康风险。健康管理师将根据主要疾病的风险评估报告制定健康促进计划，帮助您建立起更加健康的生活方式.我们充分尊重个人信息的隐私保护，任何个人或机构未经您的许可或授权，均不能获得您任何个人信息。请您认真、完整回答以下每个问题。画□的为多选题，画○的为单选题。感谢您的合作！</p> </div> </div> <div class="mui-card-footer"> <a class="mui-card-link prevQuestion">上一页</a> <button id="confirmBtn" type="button" class="mui-btn mui-btn-blue mui-btn-outlined mui-card-link">完成提交</button> <a class="mui-card-link turnToIndex ">首页</a> </div> </form>';
$(submiit).appendTo(".mui-content");