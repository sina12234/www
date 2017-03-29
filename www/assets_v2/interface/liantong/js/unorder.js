var phoneReg =/^1(3[0-2]|5[56]|8[56]|4[5]|7[56])\d{8}$/;
var pid="";
    sendPhoneinfo();
$(document).ready(function(){
    

    $("#txt_userphone").html(phoneNumber);


    $("#btn_getcode").click(function(event) {
        var smscode_time=getCookie("smscode_time");
        if(smscode_time)
        {
            var currtime=new Date().getTime();

            if((currtime - smscode_time)/1000 < 60)
            {
                var timer=parseInt(60 - (currtime - smscode_time)/1000);
                showtip("还需要"+timer+"秒才能发送验证码");
                return false;
            }
        }
        
        if(!$(this).hasClass('timming'))
        {
            //发送验证码打点
            dotLog({
                'clickEvent': "toutiao0003",
                'pagetype': "2",
                'usercode' : phoneNumber
            }, LOG_TYPE.GetSmsCode);
            //发送验证码
            startload();
            var timestamp=getNowFormatDate();
            var spwd=hex_md5(spid+timestamp+"shFLOW^(");
            $.ajax({
                url:weburl+'server/store/servicedata.do?serviceid=authcode&spid='+spid+'&usercode='+phoneNumber+'&password='+spwd+'&timeStamp='+timestamp,
                type:"post",
                datatype:"json",
                async:true,
                timeout : 30000,
                error: function(){
                    showtip('网络通信错误，请稍后再试！');
                    layer.close(loading);
                },
                success: function(data){
                    layer.close(loading);
                    if(typeof(data)=="string")
                    data=eval('(' + data + ')');
                    if(data.resultcode==0)
                    {
                        showtip("验证码已发送！");
                        
                        var smscode_time=new Date().getTime();
                        setCookie("smscode_time",smscode_time);
                        var timer=60;
                        $("#btn_getcode").html("获取("+timer+")");
                        if(!$("#btn_getcode").hasClass('timming'))
                        {
                            var yzmdjs=setInterval(function(){
                                timer--;
                                if(timer<=0)
                                {
                                    $("#btn_getcode").removeClass('timming').html("获取验证码");
                                    checkSubmitFlg=false;
                                    clearInterval(yzmdjs);
                                }
                                else
                                    $("#btn_getcode").addClass('timming').html("获取("+timer+")");
                            },1000);
                        }
                    }
                    else
                    {
                        showtip("验证码发送失败！");
                    }
                },
                complete : function(XMLHttpRequest,status){
                    if(status=='timeout'){
                        showtip("网络通信错误，请稍后再试！");
                        layer.close(loading);
                    }
                }
            });
        }
    });

    $("#btn_submit").click(function(event) {
        if($("#txt_usercode").val().length==4)
        {
            //验证码填充打点
            dotLog({
                'clickEvent': "toutiao0004",
                'usercode': phoneNumber,
                'pagetype' : "2",
                'code':$("#txt_usercode").val()
            }, LOG_TYPE.ChangeCode);
        }
        else
        {
            showtip('请输入验证码！');
            return false;
        }
        startload();
        var authcode=$("#txt_usercode").val();
        var timestamp=getNowFormatDate();
        //alert(timestamp);
        var spwd=hex_md5(spid+timestamp+pwdkey);
        $.ajax({
            url:weburl+'server/store/servicedata.do?serviceid=unorder&usercode='+phoneNumber+'&spid='+spid+'&appid='+appid+'&pid='+pid+'&sourcetype=0&authcode='+authcode+'&password='+spwd+'&timeStamp='+timestamp,
            type:"post",
            datatype:"json",
            async:true,
            timeout : 30000,
            error: function(){
                showtip('网络通信错误，请稍后再试！');
                layer.close(loading);
            },
            success: function(data){
                layer.close(loading);
                if(typeof(data)=="string")
                data=eval('(' + data + ')');
                if(data.msg)
                showtip(data.msg);
                else if(data.resultinfo)
                showtip(data.resultinfo);

                //订购打点
                dotLog({
                    'clickEvent': "toutiao0007",
                    'pagetype': "2",
                    'pid':pid,
                    'usercode':phoneNumber,
                    'code':$("#txt_usercode").val(),
                    'resultcode':data.resultcode,
                    'resultinfo':data.resultinfo
                }, LOG_TYPE.Unorder);

                //回调app方法 传返回参数
                var resCode="";
                if(data.resultcode=="0"||data.resultcode=="0000")
                {
                    resCode=0;
                    showsucctip("退订成功！");
                    //getdcode(0,serviceStatus,1,resCode,"");
                }
                else
                {
                    resCode=1;
                }
                returnresult(1,resCode,phoneNumber,"");
            },
            complete : function(XMLHttpRequest,status){
                if(status=='timeout'){
                    showtip("网络通信错误，请稍后再试！");
                    layer.close(loading);
                }
            }
        });
    });

});


function getOrderlist() {
    if (!phoneReg.test(phoneNumber)) {
        showtip("非联通号码，无法订购！");
        return false;
    }
    else
    {
        startload();
        var para={
                "header":{
                    "reqTime": Date.parse(new Date())/1000,
                    "channel":spid,
                    "version":ver,
                    "appid":appid,
                    "sign":hex_md5(Date.parse(new Date())/1000+spid+ver+appid)
                },
                "body":{
                    "phone":phonecode,
                    "imsi":"",
                    "imei":"",
                    "osType":"",
                    "sysVersion":"",
                    "sdkVersion":""
                }
            };
        //console.log(JSON.stringify(para));
        $.ajax({
            url:weburl+'wf-service/ipconvergence/getUserFlowInfo.do',
            type:"post",
            datatype:"json",
            data:JSON.stringify(para),
            async:true,
            timeout : 30000,
            error: function(){
                showtip('网络通信错误，请稍后再试！');
                layer.close(loading);
            },
            success: function(d,s,data){
                layer.close(loading);
                //showtip(data.responseText);
                if(typeof(data.responseText)=="string")
                data=eval('(' + data.responseText + ')');
                if(data.rescode=="0000")
                {
                    pid=data.data[0].pid;
                }
            },
            complete : function(XMLHttpRequest,status){
                if(status=='timeout'){
                    showtip("网络通信错误，请稍后再试！");
                    layer.close(loading);
                }
            }
        });
    }
}

