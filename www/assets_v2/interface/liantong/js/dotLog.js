var weburl=window.location.protocol+"//"+window.location.host+"/WoShieldServer/";
Date.prototype.format = function (format) {
    var args = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),  //quarter
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(format))
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var i in args) {
        var n = args[i];
        if (new RegExp("(" + i + ")").test(format))
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? n : ("00" + n).substr(("" + n).length));
    }
    return format;
};

// 日志打点类型
var LOG_TYPE = {
  PageType : "woFlowPageType",         //页面类型
  ChangePhone : "woFlowChangePhone",   //修改手机号码
  GetSmsCode : "woFlowGetSmsCode",     //获取验证码
  ChangeCode : "woFlowChangeCode",     //修改验证码
  Order : "woFlowOrder",               //订购
  Active : "woFlowActive",             //激活
  Unorder : "woFlowUnorder",           //退订
  PauseFlow : "woFlowPauseFlow",       //流量查询页面开启/暂停使用按钮打点
  BtnClick : "woFlowBtnClick",          //流量查询页面去退订/去激活按钮打点
  BtnOrderClick : "woFlowBtnOrderClick",       //订购页面“确认激活”按钮打点
  BtnToActiveClick : "woFlowBtnToActiveClick"      //订购页面“已订购，去激活”按钮打点
};


// 全局变量
var GLOBAL = {
    UA : navigator.userAgent.toLowerCase(),
    LOG_URL : "http://140.206.176.12:8060/logserver/woflow/"       //测试打点
    //LOG_URL : "http://unilog.wostore.cn:8061/logserver/woflow/"         //线上打点
};

// 打点头部基础字段
var headerJson = {
    "reqip": "",
    "userCode": "",
    "Cpid": "",
    "appid": "",
    "logtype": "1",
    "reqTime": "",
    "clientInfo":"",
    "pid":""
};

// 页面类型打点
var PageType = {
    'clickEvent': "",
    'pageType':"",
    'isAutoGetPhone':""
};
// 修改电话号码打点
var ChangePhone = {
    'clickEvent': "",
    'pageType':"",
    'newusercode': ""
};

// 获取验证码打点
var GetSmsCode = {
    'clickEvent': "",
    'pagetype': "",
    'usercode' : ""
};

// 填充验证码打点
var ChangeCode = {
    'clickEvent': "",
    'usercode': "",
    'pagetype' : "",
    'code':""
};

// 订购打点
var Order = {
    'clickEvent': "",
    'pagetype': "",
    'pid':"",
    'usercode':"",
    'code':"",
    'resultcode':"",
    'resultinfo':""
};
// 激活打点
var Active = {
    'clickEvent': "",
    'pagetype':"",
    'usercode':"",
    'code':"",
    'resultcode':"",
    'resultinfo':""
};
// 退订打点
var Unorder = {
    'clickEvent': "",
    'pagetype': "",
    'usercode':"",
    'pid':"",
    'code':"",
    'resultcode':"",
    'resultinfo':""
};

// 流量查询页面开启/暂停使用按钮打点
var PauseFlow = {
    'clickEvent': "",
    'pagetype': "",
    'usercode':"",
    'pid':"",
    'flowstatus':""
};

// 流量查询页面去退订/去激活按钮打点
var BtnClick = {
    'clickEvent': "",
    'pagetype': "",
    'usercode':"",
    'btntype':""
};

//订购页面“确认激活”按钮打点
var BtnOrderClick = {
    'clickEvent': "",
    'pagetype': ""
}

//订购页面“已订购，去激活”按钮打点
var BtnToActiveClick = {
    'clickEvent': "",
    'pagetype': ""
}

/**
 * 初始化打点时调用
 * @param header
 */
function initHeader(header) {
    headerJson = $.extend(headerJson, header);
}

// 发送日志
function dotLog(body, key, header) {
    if (key == LOG_TYPE.PageType) {
        body = $.extend({}, PageType, body);
    }
    if (key == LOG_TYPE.ChangePhone) {
        body = $.extend({}, ChangePhone, body);
    }
    if (key == LOG_TYPE.GetSmsCode) {
        body = $.extend({}, GetSmsCode, body);
    }
    if (key == LOG_TYPE.Order) {
        body = $.extend({}, Order, body);
    }
    if (key == LOG_TYPE.Active) {
        body = $.extend({}, Active, body);
    }
    if (key == LOG_TYPE.Unorder) {
        body = $.extend({}, Unorder, body);
    }
    if (key == LOG_TYPE.PauseFlow) {
        body = $.extend({}, PauseFlow, body);
    }
    if (key == LOG_TYPE.BtnClick) {
        body = $.extend({}, BtnClick, body);
    }
    var jsonStr = JSON.stringify($.extend({}, headerJson, header, body));
    $.ajax({url:GLOBAL.LOG_URL + key, type:"POST", cache:false, data:jsonStr});
}


