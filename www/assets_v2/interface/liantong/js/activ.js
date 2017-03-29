var pid="";
$(document).ready(function(){
    
    if(phoneNumber.length==11)
    sendPhoneinfo();

    $(".link_haveorder a").attr("href","order.html"+window.location.search);
    $("#txt_userphone").val(phoneNumber);


    $("#txt_userphone").blur(function(event) {
        if($(this).val().length==11)
        {
            //手机号码修改打点
            dotLog({
                'clickEvent': "toutiao0002",
                'pageType':"1",
                'newusercode': $(this).val()
            }, LOG_TYPE.ChangePhone);
        }
    });

    $("#btn_getcode").click(function(event) {
        phoneNumber=$("#txt_userphone").val();
        if(!$("#txt_userphone").val())
        {
            showtip("请输入联通手机号！");
            return false;
        }
        else
        {
            if (!phoneReg.test(phoneNumber)) {
                showtip("非联通号码，无法订购！");
                return false;
            }
            else
            {
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

                    //发送验证码打点
                    dotLog({
                        'clickEvent': "toutiao0003",
                        'pagetype': "1",
                        'usercode' : phoneNumber
                    }, LOG_TYPE.GetSmsCode);
                    
                }
            }
        }
    });

    $("#btn_submit").click(function(event) {
        phoneNumber=$("#txt_userphone").val();
        var para={
            "header":{
                "reqTime": Date.parse(new Date())/1000,
                "channel":spid,
                "version":ver,
                "appid":appid,
                "sign":hex_md5(Date.parse(new Date())/1000+spid+ver+appid)
            },
            "body":{
                "phoneNum":phoneNumber
            }
        };
        $.ajax({
            url:weburl+'wf-service/ipconvergence/getEnPhone.do',
            type:"post",
            datatype:"json",
            data:JSON.stringify(para),
            async:true,
            timeout : 30000,
            success: function(data){
                if(data.rescode=="0000")
                {
                    phonecode=data.data.phone;
                    active();
                }
            },
            complete : function(XMLHttpRequest,status){
                if(status=='timeout'){
                    showtip("网络通信错误，请稍后再试！");
                }
            }
        });
    });

});

function active () {
    var authcode=$("#txt_usercode").val();
        phoneNumber=$("#txt_userphone").val();
        if(phoneNumber=="")
        {
            showtip("请输入联通手机号！");
        }
        else if (!phoneReg.test(phoneNumber)) {
            showtip("非联通号码，无法激活！");
            return false;
        }
        else
        {
            if($("#txt_usercode").val()=="")
            {
                showtip("请输入验证码！");
                return false;
            }
            else
            {
                if($("#txt_usercode").val().length==4)
                {
                    //验证码填充打点
                    dotLog({
                        'clickEvent': "toutiao0004",
                        'usercode': phoneNumber,
                        'pagetype' : "1",
                        'code':$("#txt_usercode").val()
                    }, LOG_TYPE.ChangeCode);
                }
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
                            "validCode":authcode,
                            "osType":"",
                            "sysVersion":"",
                            "sdkVersion":"",
                            "clientInfo":clientInfo
                        }
                    };
                //console.log(JSON.stringify(para));
                $.ajax({
                    url:weburl+'wf-service/ipconvergence/activeUser.do',
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
                        showtip(data.resmsg);

                        //激活打点
                        dotLog({
                            'clickEvent': "toutiao0006",
                            'pagetype': "1",
                            'usercode':phoneNumber,
                            'code':$("#txt_usercode").val(),
                            'resultcode':data.rescode,
                            'resultinfo':data.resmsg
                        }, LOG_TYPE.Active);

                        var resCode="";
                        if(data.rescode=="0000")
                        {
                            resCode=0;
                            showsucctip("激活成功！");
                            //getdcode(1,serviceStatus,2,resCode,data.data.idkey);
                        }
                        else
                        {
                            if(data.data.forwardUrl)
                            {
                                window.location.href=data.data.forwardUrl;
                            }
                            resCode=1;
                        }
                        //回调app方法 传返回参数
                        returnresult(2,resCode,phoneNumber,data.data.idkey);
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
}