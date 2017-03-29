var weburl=window.location.protocol+"//"+window.location.host+"/WoShieldServer/";
var phoneReg =/^1(3[0-2]|5[56]|8[56]|4[5]|7[56])\d{8}$/;
var loading="";
var spid=getUrlParam("spid");
var appid=getUrlParam("appid");
var productid=getUrlParam("pid");
var serviceStatus=getUrlParam("serviceStatus");;
var clientInfo=decodeURIComponent(decodeURIComponent(getUrlParam("clientInfo")));
var pwdkey="shFLOW^(";
var ver="1.0";
var myDate=new Date();
var servtime=myDate.toLocaleDateString();
var starttime="2016-10-20";
var deadtime="2016-10-24";
var phoneNumber=getUrlParam("phoneNumber");
var phonecode="";
var ipAdress="";

$(document).ready(function() {
    if(!serviceStatus)
    {
        serviceStatus="0";
    }
    getServiceinfo();
    if(appid=="&s"||appid=="")
    {
        appid="926303";
    }

    $(".goback").click(function(event) {
        closepage();
    });
});


function pbinitPage () {
    
    var isAutoGetPhone="0";
    if(phoneNumber)
    {
        isAutoGetPhone=1;
    }
    initHeader({
        "reqip": ipAdress,
        "userCode": phoneNumber,
        "Cpid": spid,
        "appid": appid,
        "logtype": "1",
        "reqTime": getNowFormatDate(),
        "clientInfo":clientInfo,
        "pid":"h5fortoutiao"
    });
    if(window.location.href.indexOf("/order.html")>-1)
    {
        //页面类型打点
        dotLog({
            'clickEvent': "toutiao0001",
            'pageType': "0",
            'isAutoGetPhone':isAutoGetPhone
        }, LOG_TYPE.PageType);
    }
    else if(window.location.href.indexOf("/unorder.html")>-1)
    {
         //页面类型打点
        dotLog({
            'clickEvent': "toutiao0001",
            'pageType': "1",
            'isAutoGetPhone':isAutoGetPhone
        }, LOG_TYPE.PageType);
    }
    else if(window.location.href.indexOf("/active.html")>-1)
    {
         //页面类型打点
        dotLog({
            'clickEvent': "toutiao0001",
            'pageType': "2",
            'isAutoGetPhone':isAutoGetPhone
        }, LOG_TYPE.PageType);
    }
    else if(window.location.href.indexOf("/flowquery.html")>-1)
    {
         //页面类型打点
        dotLog({
            'clickEvent': "toutiao0001",
            'pageType': "3",
            'isAutoGetPhone':isAutoGetPhone
        }, LOG_TYPE.PageType);
    }
}


function sendPhoneinfo()
{
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
                if(window.location.href.indexOf("/unorder.html")>-1)
                    getOrderlist();
                if(window.location.href.indexOf("/flowquery.html")>-1)
                    flowquery();
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                showtip("网络通信错误，请稍后再试！");
            }
        }
    });
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return encodeURIComponent(r[2]); return "";
}

function closewindow () {
    layer.closeAll();
}

function closepage(){
    $('#closeiframe').remove();
    $('body').append('<iframe src="xiaowocloseCallback://xiaowocall/" frameborder="0" style="display:none" id="closeiframe"></iframe>');
}

function showmsg(msg)
{
    layer.open({
        content: msg,
        style: 'background-color:#fff; color:#333; border:none;',
        time: 2
    });
}

function showtip(msg)
{
    layer.open({
        content: msg,
        style: 'background-color:#fff; color:#333; border:none;font-size:14px',
        time: 3
    });
}

/*function showsucctip(msg)
{
    var pageii = layer.open({
      type: 1,
      content: '<div style="text-align:center;padding:50px 0;font-size:3rem">'+msg+'</div>',
      anim: 'up',
      style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;',
      shadeClose: false
    });
}*/

function showsucctip(msg,type)
{
    var i=5;
    var n=3;
    layer.open({
        content: '<div class="pfcontentform1"><div class="msgform">'+msg+'</div><div class="counttip" style="text-align:center"><span class="tiptimmer">'+i+'</span>秒后跳转..</div><span class="btn_closepage" onclick="closepage()">确定('+n+')</span></div>',
        shadeClose: false
    });
    var temp=setInterval(function(){
        i--;
        $(".tiptimmer").html(i);
        $(".btn_closepage").html('确定('+i+')');
        if(i<=0)
        {
            clearInterval(temp);
            closepage();
        }
    }, 1000);
}

function startload()
{
    loading=layer.open({
        content:'<div class="loadEffect"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div><div style="text-align:center;color:#fff">加载中..</div>',
        style:'background:none;box-shadow:none',
        shadeClose: false
    });
}

function woFlowCallback (para) {      
    $('#tempiframe').remove();
    $('body').append('<iframe src="xiaowoOrderCheckCallback://xiaowocall/'+para+'" frameborder="0" style="display:none" id="tempiframe"></iframe>');
}


function getdcode (orderStatus,serviceStatus,woType,woStatus,woIdkey) {
    var para={
        "header":{
            "reqTime": Date.parse(new Date())/1000,
            "channel":spid,
            "version":ver,
            "appid":appid,
            "sign":hex_md5(Date.parse(new Date())/1000+spid+ver+appid)
        },
        "body":{
            "phone" : phonecode,
            "orderStatus":orderStatus,
            "serviceStatus":serviceStatus,
            "woType":woType,
            "woStatus":woStatus,
            "idkey":woIdkey,
            "imei" : "",
            "imsi" : "",
            "osType":"",
            "sysVersion":"",
            "sdkVersion":""
        }
    };
    $.ajax({
        url:weburl+'wf-service/ipconvergence/encryptCallbackData.do',
        type:"post",
        datatype:"json",
        data:JSON.stringify(para),
        async:true,
        timeout : 30000,
        error: function(){
            showtip('网络通信错误，请稍后再试！');
        },
        success: function(data){
            if(data.rescode=="0000")
            {
                woFlowCallback(data.data.encryptStr);
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                showtip("网络通信错误，请稍后再试！");
            }
        }
    });
}

//DES 加密
function encryptByDES(message, key) {
    var keyHex = CryptoJS.enc.Utf8.parse(key);
    var encrypted = CryptoJS.DES.encrypt(message, keyHex, {
        mode: CryptoJS.mode.ECB,
        padding: CryptoJS.pad.Pkcs7
    });
    return encrypted.toString();
}

//DES 解密

function decryptByDES(ciphertext, key) {
    var keyHex = CryptoJS.enc.Utf8.parse(key);
    // direct decrypt ciphertext
    var decrypted = CryptoJS.DES.decrypt({
        ciphertext: CryptoJS.enc.Base64.parse(ciphertext)
    }, keyHex, {
        mode: CryptoJS.mode.ECB,
        padding: CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8);
}

/*
type: 0 订购，1 退订，2 激活
resCode: 0 成功，1 失败
phoneNumber ：订购退订激活都传
idkey： 只有激活传，其他时候给 “”
 */

//返回请求结果
function returnresult(type,resCode,phoneNumber,idkey)
{
    if(getUrlParam("osType")=="Android")
    {
        window.xiaowo.WOSDKJsCallBack(type,resCode,phoneNumber,idkey);
    }
    else if(getUrlParam("osType")=="ios")
    {
        setupWebViewJavascriptBridge(function(bridge) {              
            bridge.callHandler('WOSDKJsCallBack', {'type': type, 'status':resCode, 'phoneNumber': phoneNumber, 'idkey':idkey}, function(response) {})
        });
    }
}

function setupWebViewJavascriptBridge(callback) {
    if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
    if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
    window.WVJBCallbacks = [callback];
    var WVJBIframe = document.createElement('iframe');
    WVJBIframe.style.display = 'none';
    WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
    document.documentElement.appendChild(WVJBIframe);
    setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
}

function getNowFormatDate() {       //type=1 YYYYMMDDHHmmss |type=0 MMDDHHmmss
    var date = new Date();
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    var strHours = date.getHours();
    var strMinutes = date.getMinutes();
    var strSeconds = date.getSeconds();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    if(strHours >= 0 && strHours <=9)
    {
        strHours = "0" + strHours;
    }
    if(strMinutes >= 0 && strMinutes <=9)
    {
        strMinutes = "0" + strMinutes;
    }
    if(strSeconds >= 0 && strSeconds <=9)
    {
        strSeconds = "0" + strSeconds;
    }
    var currentdate = date.getFullYear().toString()  + month.toString()  + strDate.toString() + strHours.toString() + strMinutes.toString() + strSeconds.toString();
    return currentdate;
}

function getServiceinfo () {
    var para={
        "header":{
            "reqTime": Date.parse(new Date())/1000,
            "channel":spid,
            "version":ver,
            "appid":appid,
            "sign":hex_md5(Date.parse(new Date())/1000+spid+ver+appid)
        },
        "body":{
            "osType":"",
            "sysVersion":"",
            "sdkVersion":""
        }
    };
    $.ajax({
        url:weburl+'wf-service/ipconvergence/getReqClientInfo.do',
        type:"post",
        datatype:"json",
        data:JSON.stringify(para),
        async:true,
        timeout : 30000,
        success: function(data){
            if(data.rescode=="0000")
            {
                ipAdress=data.data.reqAddr;
                servtime=data.data.reqDate;
                pbinitPage();
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                showtip("网络通信错误，请稍后再试！");
            }
        }
    });
}


//取得cookie  
function getCookie(name) {  
    var nameEQ = name + "=";  
    var ca = document.cookie.split(';');    //把cookie分割成组  
    for(var i=0;i < ca.length;i++) {  
        var c = ca[i];                      //取得字符串  
        while (c.charAt(0)==' ') {          //判断一下字符串有没有前导空格  
            c = c.substring(1,c.length);      //有的话，从第二位开始取  
        }  
        if (c.indexOf(nameEQ) == 0) {       //如果含有我们要的name  
            return unescape(c.substring(nameEQ.length,c.length));    //解码并截取我们要值  
        }  
    }  
    return false;  
}  
  
//清除cookie  
function clearCookie(name) {  
    setCookie(name, "", -1);  
}  
  
//设置cookie  
function setCookie(name, value, seconds) {  
    seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。  
    var expires = "";  
    if (seconds != 0 ) {      //设置cookie生存时间  
        var date = new Date();  
        date.setTime(date.getTime()+(seconds*1000));  
        expires = "; expires="+date.toGMTString();  
    }  
    document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值  
}
