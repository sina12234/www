// 全局变量模块
define(['jquery'], function($) {
    /**
     * 增加改变值：
     * global.set({ key: value })
     * 获取值：
     * global.get(args)
     * 要获取多个值时，传入数组
     *
     * 获取实时值时
     * getFunc 可以返回一个查询函数
     */
    var o = {
        isLogin: false, // 登录
        isSign: false,  // 报名
        isTeacher: false,  // 老师身份
        isAdmin: false,  // 管理员
        isLive: false,  // 直播中
        isPc: !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent), // pc浏览器端
        enableGift: false, // 是否启用礼物功能
        enableDM: false,  // 是否启用弹幕功能
        supportDM: false,  // 播放器是否支持弹幕
        plan_id: 0,  
        _videoEnd: true, //录播课结束
        userRank:0, //用户排名
        userGood:0, //用户获赞
        _cameraOpen: false,  //  摄像机开启状态
        _singlenotalkFlag: false,  // 用户被禁言状态
        _notalkFlag: false,  //  全员禁言状态
        groupId: 0,  // 分组id
        _groupUids: [],  // 分组id列表
        _onlineNum: 0,  // 在线人数
        _groupOnlineNum: 0,  // 分组在线人数
        _classStart: false,  // 开始上课状态
        browser: {
            versions: function() {
                var u = navigator.userAgent;
                return {
                    trident: u.indexOf('Trident') > -1, //IE内核
                    presto: u.indexOf('Presto') > -1, //opera内核
                    webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                    gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                    mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                    ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                    android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
                    iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
                    pad: u.indexOf('Pad') > -1, //是否Pad
                    webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
                    weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
                    qq: u.match(/\sQQ/i) == " qq" //是否QQ
                };
            }(),
            language: (navigator.browserLanguage || navigator.language).toLowerCase()
        }
    };
    var deviceType = 10;
    if(o.browser.versions.weixin){
        deviceType = 43;
    }else if(o.browser.versions.ios || o.browser.versions.android){
        if(o.browser.versions.pad){
            deviceType = 30;
        }else{
            deviceType = 20;
        }
    }
    o.deviceType = deviceType;
    return {
        set: function(obj) {
            o = $.extend(true, o, obj);
        },
        get: function(args) {
            if (!args) {
                return $.extend({}, o);
            }
            if (Object.prototype.toString.call(args) == '[object Array]') {
                var obj = {};
                for (var i = 0, l = args.length; i < l; i++) {
                    obj[args[i]] = o[args[i]];
                }
                return obj;
            } else {
                return o[args];
            }
        },
        getFunc: function(args) {
            return function() {
                if (!args) {
                    return $.extend({}, o);
                }
                if (Object.prototype.toString.call(args) == '[object Array]') {
                    var obj = {};
                    for (var i = 0, l = args.length; i < l; i++) {
                        obj[args[i]] = o[args[i]];
                    }
                    return obj;
                } else {
                    return o[args];
                }
            }
        }
    };
});
