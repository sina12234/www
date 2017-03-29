var pinfo=getUrlParam("pinfo");
$(document).ready(function(){
    if(phoneNumber.length==11)
    sendPhoneinfo();

    if(pinfo)
    {
        pinfo=decodeURIComponent(decodeURIComponent(getUrlParam("pinfo")));
        pinfo=eval('(' + pinfo + ')');
        if(pinfo.length==0)
        {
            $(".userinfo").html("您手机号所属省份目前暂未开通订购。<br />我们正在努力帮您开通中，敬请期待！").css({
                "color":"#888",
                "text-align":"center"
            })
        }
        else
        {
            productid=pinfo[0].pid;
            var plist="";
            $.each(pinfo, function(i, v) {
                 plist+='<div class="packagetitle" data-pid="'+pinfo[i].pid+'"><span class="tb_gou"><img src="img/tb_ungou.gif" alt="" /></span> <span style="vertical-align:middle">小沃学堂流量包 每月'+pinfo[i].price/100+'元/'+pinfo[i].flow/1024+'G</span></div>';
            });
            $(".packagetitle").remove();
            $(".userinfo").prepend(plist);
            $(".packagetitle").eq(0).addClass('selected');
            $(".packagetitle").eq(0).children('.tb_gou').children('img').attr("src","img/tb_gou.gif");
        }

    }
    else
    {
        if(weburl.indexOf("http://woshield.wostore.cn")>=0)
        {
            if(appid=="926303")
            {
                productid="2100082603";
            }
        }
        else
        {
            productid="3100000005";
        }
    }

    $(".packagetitle").live('click', function(event) {
        if($(this).attr("data-pid"))
        {
            $(this).addClass('selected').siblings(".packagetitle").removeClass('selected');
            $(".tb_gou img").attr("src","img/tb_ungou.gif");
            $(this).children('.tb_gou').children('img').attr("src","img/tb_gou.gif");
            productid=$(this).attr("data-pid");
        }
    });

    $(".link_haveorder a").attr("href","active.html"+window.location.search);
    $("#txt_userphone").val(phoneNumber);


    $("#txt_userphone").blur(function(event) {
        if($(this).val().length==11)
        {
            //手机号码修改打点
            dotLog({
                'clickEvent': "toutiao0002",
                'pageType':"0",
                'newusercode': $(this).val()
            }, LOG_TYPE.ChangePhone);
        }
    });

    $(".link_haveorder a").click(function(event) {
        //已订购，去激活按钮打点
            dotLog({
                'clickEvent': "toutiao0011",
                'pageType':"0"
            }, LOG_TYPE.BtnToActiveClick);
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
                        'pagetype': "0",
                        'usercode' : phoneNumber
                    }, LOG_TYPE.GetSmsCode);
                }
            }
        }
    });

    $("#btn_submit").click(function(event) {
        startload();
        //订购页面“确认订购按钮打点”
        dotLog({
            'clickEvent': "toutiao0010",
            'pagetype': "0",
            'usercode' : phoneNumber
        }, LOG_TYPE.BtnOrderClick);

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

                    if(phoneNumber=="")
                    {
                        showtip("请输入联通手机号！");
                    }
                    else if (!phoneReg.test(phoneNumber)) {
                        showtip("非联通号码，无法订购！");
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
                                    'pagetype' : "0",
                                    'code':$("#txt_usercode").val()
                                }, LOG_TYPE.ChangeCode);
                            }
                            startload();
                            var timeStamp=getNowFormatDate();
                            var orderpwd=hex_md5(spid+appid+phoneNumber+productid+timeStamp+"shFLOW^(");
                            var para='usercode='+phoneNumber+'&spid='+spid+'&password='+orderpwd+'&timeStamp='+timeStamp+'&appid='+appid+'&pid='+productid+'&ver=3.0&authcode='+$("#txt_usercode").val();
                            //para='{"productid":"2100081104","userid":"13095061115","spid":"92173","appid":"92173"}';
                            //var myurl='{"order":"'+encodeURIComponent(encryptByDES(para,'xyyx2015'))+'","verifycode":"'+$("#txt_usercode").val()+'"}';

                            $.ajax({
                                url:weburl+'server/store/servicedata.do?serviceid=order',
                                type:"get",
                                datatype:"json",
                                contentType:"application/json",
                                data: $.trim(para),
                                async:false,
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
                                        'clickEvent': "toutiao0005",
                                        'pagetype': "0",
                                        'pid':productid,
                                        'usercode':phoneNumber,
                                        'code':$("#txt_usercode").val(),
                                        'resultcode':data.resultcode,
                                        'resultinfo':data.resultinfo
                                    }, LOG_TYPE.Order);

                                    //回调app方法 传返回参数
                                    var resCode="";
                                    var orderStatus="";
                                    if(data.resultcode=="0")
                                    {
                                        resCode=0;
                                        orderStatus=1;
                                        showsucctip("订购成功！");
                                        returnresult(0,resCode,phoneNumber,"");
                                        widow.location.href='flowquery.html'+window.location.search;
                                        //getdcode(orderStatus,serviceStatus,0,resCode,"");
                                    }
                                    else if(data.resultcode=="1200"||data.resultcode=="200018"||data.resultcode=="200019"||data.resultcode=="200023"||data.resultcode=="200026")
                                    {
                                        showmsg("您的号码已订购，正在帮您激活!");
                                        autoActive();       //如果状态为“已订购”则自动激活;

                                    }
                                    else
                                    {
                                        resCode=1;
                                        orderStatus=0;
                                        returnresult(0,resCode,phoneNumber,"");
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

function autoActive () {
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
}

function active () {
    var authcode=$("#txt_usercode").val();
    phoneNumber=$("#txt_userphone").val();
    if (!phoneReg.test(phoneNumber)) {
        showtip("非联通号码，无法订购！");
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
                        "sdkVersion":""
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
                    layer.closeAll();
                },
                success: function(d,s,data){
                    layer.closeAll();
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
                        showsucctip("<span style='font-size:22px;color:#000;padding:0 20px'>您是已订购用户，本次自动激活，未产生费用。</span>",2);
                        //getdcode(1,serviceStatus,2,resCode,data.data.idkey);
                    }
                    else
                    {
                        resCode=1;
                    }
                    //回调app方法 传返回参数
                    returnresult(2,resCode,phoneNumber,data.data.idkey);
                },
                complete : function(XMLHttpRequest,status){
                    if(status=='timeout'){
                        showtip("网络通信错误，请稍后再试！");
                        layer.closeAll();
                    }
                }
            });
        }
    }
}
