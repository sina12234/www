var Overplus=0.8;
var isUnlimited="0";
var timeout=getUrlParam("timeout");
$(document).ready(function() {
    $(".page").hide();
    if(serviceStatus=="1")      //关闭状态
    {
        $("#flowstatus1,#flowstatus2").html("小沃学堂流量已关闭");
        $("#btn_flowswitch").html("开启使用");
    }
    else if(serviceStatus=="0")     //开启状态
    {
        $("#flowstatus1,#flowstatus2").html("小沃学堂流量已开启");
        $("#btn_flowswitch").html("暂停使用").addClass('btn_paused');
    }

    $(".sp_userphone").html(phoneNumber);

    sendPhoneinfo();    //查询流量流程 手机号码加密+查询流量

    $(".leftlink").attr("href","unorder.html"+window.location.search);
    $(".rightlink").attr("href","active.html"+window.location.search);

    $("#btn_flowswitch").click(function(event) {
        if(serviceStatus=="1")
        {
            serviceStatus="0";
            $("#flowstatus1,#flowstatus2").html("小沃学堂流量已开启");
            $("#btn_flowswitch").html("暂停使用");
            returnresult(3,'',phoneNumber,'');
            //getdcode(1,serviceStatus,3,0,"");
        }
        else if(serviceStatus=="0")
        {
            layer.open({
                content: '确认暂停使用小沃学堂流量包？'
                ,btn: ['取消', '确定']
                ,yes: function(index){
                  layer.closeAll();
                },
                no:function(){
                    serviceStatus="1";
                    $("#flowstatus1,#flowstatus2").html("小沃学堂流量已关闭");
                    $("#btn_flowswitch").html("开启使用");
                    returnresult(3,'',phoneNumber,'');
                    //getdcode(1,serviceStatus,3,0,"");
                }
            });
        }

        //流量查询页面开启/暂停使用按钮打点
        dotLog({
            'clickEvent': "toutiao0008",
            'pagetype': "3",
            'usercode':phoneNumber,
            'pid':productid,
            'flowstatus':serviceStatus
        }, LOG_TYPE.PauseFlow);
    });

    $(".leftlink").click(function(event) {
        //流量查询页面去退订按钮打点
        dotLog({
            'clickEvent': "toutiao0009",
            'pagetype': "3",
            'usercode':phoneNumber,
            'btntype':"1"
        }, LOG_TYPE.BtnClick);
    });

    $(".rightlink").click(function(event) {
        //流量查询页面去激活按钮打点
        dotLog({
            'clickEvent': "toutiao0009",
            'pagetype': "3",
            'usercode':phoneNumber,
            'btntype':"2"
        }, LOG_TYPE.BtnClick);
    });
});

function setCallBackData(data)
{
    if(data.resCode=="0")
    {
        if(data.resType=="0")
            window.location.href=data.resMsg;
    }
}

function flowquery () {
    var para={
    "header":{
        "reqTime":Date.parse(new Date())/1000,
        "channel":spid,
        "version":ver,
        "sign":hex_md5(Date.parse(new Date())/1000+spid+ver+appid),
        "appid":appid
    },
    "body": {
        "phone" : phonecode,
        "imei" : "",
        "imsi" : "",
        "osType":"",
        "sysVersion":"",
        "sdkVersion":""
        }
    };

    if(timeout)
    {
        var refresh=layer.open({
            content: '抱歉，免流量连接请求失败！',
            btn:["重试"],
            shadeClose:false,
            yes:function(){
                serviceStatus="0";
                returnresult(3,'',phoneNumber,'');
                //getdcode(1,serviceStatus,3,0,"");
                layer.close(refresh);
                startload();
            }
        });
    }
    else
    {
        startload();
    }
    var timestamp=getNowFormatDate();
    var spwd=hex_md5(spid+timestamp+"shFLOW^(");
    $.ajax({
        url:weburl+'wf-service/ipconvergence/getUserFlowTraffic.do',
        type:"post",
        datatype:"json",
        data:JSON.stringify(para),
        async:true,
        timeout : 30000,
        error: function(){
            layer.open({
                content: '网络通信错误，请稍后再试！',
                btn: ['重试', '取消'],
                yes: function(index){
                  location.reload();
                  layer.close(index);
                }
            });
            //showtip('网络通信错误，请稍后再试！');
            layer.close(loading);
        },
        success: function(data){
            layer.close(loading);
            if(typeof(data)=="string")
            data=eval('(' + data + ')');
            if(data.rescode==0)
            {
                $(".page").show();
                if(data.data[0].isUnlimited==0)
                {
                    $("#nolimit").remove();
                    Overplus=1.4*(data.data[0].flowOverplus/data.data[0].flowAll)+0.8;
                    showflowdetail();  //绘图
                    $("#lastflow").html((data.data[0].flowOverplus/1024).toFixed(1));
                    $("#totalflowpk").html((data.data[0].flowAll/1024).toFixed(1)+"M");
                    if(data.data[0].flowOverplus<=0)
                    {
                        $("#flowstatus").html("小沃学堂流量已用完").css("color","#ff0000");
                        $("#btn_flowswitch").hide();
                    }
                    $("#sp_querytime1,#sp_querytime2").html(date("Y-m-d H:i",data.data[0].lastRefreshedTime));
                }
                else
                {
                    $("#haslimit").remove();
                    $("#nolimit").show();
                    $("#sp_querytime1,#sp_querytime2").html(date("Y-m-d H:i",data.data[0].lastRefreshedTime));
                }
            }
            else
            {
                showtip(data.resmsg);
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                layer.open({
                    content: '网络通信错误，请稍后再试！',
                    btn: ['重试', '取消'],
                    yes: function(index){
                      location.reload();
                      layer.close(index);
                    }
                });
                layer.close(loading);
            }
        }
    });
}

function showflowdetail()
{
    var c=document.getElementById("mycanvas1");
    var ctx=c.getContext("2d");
    ctx.beginPath();
    ctx.lineCap="round";
    ctx.arc(200,200,190,0.8*Math.PI,2.2*Math.PI,false);
    ctx.strokeStyle = "#FF7D0C";
    ctx.lineWidth=20;
    ctx.stroke();
    var c1=document.getElementById("mycanvas2");
    var ctx1=c1.getContext("2d");
    var t=0.8;
    var tt=setInterval(function()
    {

        if(t>=Overplus)
        {
            clearInterval(tt);
        }
        else
        {
            ctx1.clearRect(0,0,500,500);
            t=t+0.05;
            ctx1.beginPath();
            ctx1.lineCap="round";
            ctx1.strokeStyle = "#FBD4A2";
            ctx1.lineWidth=20;
            ctx1.arc(200,200,190,0.8*Math.PI,t*Math.PI,false);
            ctx1.stroke();
        }
    },20);

}
