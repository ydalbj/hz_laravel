/**
 * Created by hkzt on 2016/12/2.
 * 获取每个input框的data-field和value数据
 * 生成题目时可以参见此获取机制设计数据
 */


function getEntity(form){
     result = {};
    $(form).find("[data-field]").each(function(){
        var field = $(this).attr("data-field");
        var val;
        if($(this).attr('type')=='checkbox'){
            val = $(this).prop('checked');
        }else if($(this).attr('type')=='radio'){
            val = $(this).prop('checked');
        }else{
            val = $(this).val();
        }

        if (val !== false) {
            //获取单个属性的值并扩展到result对象里面
            getField(field.split('.'),val,result);
        }
    });
    return result;
}

function getField(fieldNames,value,result){
    if(fieldNames.length>1){
        var fieldNamesLength = fieldNames.length;
        for(var i=0;i<fieldNamesLength;i++){
            if(result[fieldNames[i]] == undefined){
                result[fieldNames[i]] = {};
            }
            result = result[fieldNames[i]];
        }
        result[fieldNames[fieldNamesLength - 1]] = value;
    }else{
        result[fieldNames[0]] = value;
    }
}
