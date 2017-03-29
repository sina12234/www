// 直播模块
define(['require', 'jquery', 'message', 'global', 'playerFlash', 'util','template'], function(require, $, message, global, Player, $,template) {
    var messageType = message.MessageType;
    var g = global.get(['isReg', 'isLogin', 'isSign', 'plan_id', 'user_id', 'user_info', 'deviceType', 'teacher', 'teacherName']);
    var isReg = g.isReg;
    var isLogin = g.isLogin;
    var isSign = g.isSign;
    var planId = g.plan_id;
    var userId = parseInt(g.user_id);
    var teacherId = parseInt(g.teacher.uid);
    var teacherName = g.teacherName;
    var userName = g.user_info.name;
    var userToken = g.user_info.token;
    var userFlag = userToken && userToken.substring(0, 5);
    // 点名
    var RollCall = {
        el: {
            dom: '#rollCall',
            confirm: '.confirm',
            close: '.close',
            tpl: '#tpl-rollcall'
        },
        item: '',
        _callId: 0,
        responseMsg: function(data) {
            Speak && (typeof Speak.cancelTimeout == 'function') && Speak.cancelTimeout();
            if (isReg && (0 == data.user_to_id || (data.user_to_id == userId && data.user_to_token == userFlag))) {
                Player.quitFullScreen();
                this.showDom();
                this._callId = parseInt(data.user_from_id);
            }
            this.showDom();
        },
        sendMsg: function() {
            message.reply();
        },
        createDom: function(callback) {
            var _this = this;
            if (!$(this.el.dom)[0]) {
                $.ajax({
                    url: '/assets_v2/js/player/tpl/mask-rollcall.js',
                    dataType: 'html'
                })
                .done(function(res) {
                    var code = res;
                    _this.item = $(code);
                    _this.item.hide().appendTo('#maskContent');
                    (typeof callback == 'function')&& callback();
                });
            } else {
                this.item = $(this.el.dom);
                (typeof callback == 'function')&& callback();
            }
        },
        showDom: function() {
            if (!this.item[0]) {
                this.init();
            }
            this.item.show().siblings('.mask-item').hide();
        },
        bind: function() {
            var _this = this;
            this.item.on('click', this.el.confirm, function() {
                    $('#mask').hide();
                    _this.item.hide();
                    _this.sendMsg();
                })
                .on('click', this.el.close, function() {

                    $('#mask').hide();
                    _this.item.hide();
                });
        },
        registMsg: function() {
            // 注册点到
            message.regist({
                type: messageType.call,
                getMsg: this.responseMsg
            });
            // 注册答道
            message.regist({
                type: messageType.reply,
                send: 'reply'
            })
        },
        init: function() {
            var _this = this;
            this.createDom(function(){
                _this.bind();
            });
            this.registMsg();
        }
    };
    RollCall.init();
    // 点赞
    var Parise = {
        responseMsg: function() {}
    };
    // 测麦
    var TestMicro = {
        responseMsg: function(data) {
            if (isReg && (0 == data.user_to_id || (userId == data.user_to_id && userFlag == data.user_to_token))) {
                Player.testMicrophone(true);
            }
        },
        init: function() {
            message.regist({
                type: messageType.microphone_test,
                getMsg: this.responseMsg
            })
        }
    };
    TestMicro.init();
    // 发言
    var Speak = {
        item: '#ask',
        timeText: '#handingTime',
        timer: null,
        sec: 60,
        nowSec: 0,
        _speakUser: '',
        _speakToken: '',
        _voiceTimerId: null,
        _voiceNum: 15,
        _cameraOpen: false,
        voiceTimeout: function(a, b) {
            var _this = this;
            if (_this._voiceNum > 0) {
                var ret = (typeof Player.showSpeakingTips == 'function') ? Player.showSpeakingTips(a, b) : null;
                if (ret) {
                    _this._voiceTimerId = null;
                    var _cameraOpen = global.get('_cameraOpen');
                    if (_cameraOpen) {
                        global.set({
                            '_cameraOpen': false
                        });
                        Player.startupCameraPlayer();
                    }
                } else {
                    _this._voiceNum--;
                    _this._voiceTimerId = setTimeout(function() {
                        _this.voiceTimeout(a, b);
                    }, 2000);
                }
            } else {
                this._voiceTimerId = null;
            }
        },
        responseMsg: function(data) {
            var _this = this;
            var f = data.content;
            this._speakUser = data.ut;
            this._speakToken = data.uft;
            switch (f) {
                case 'agree':
                    _this.resAgree(data);
                    break;
                case 'refuse':
                case 'stop':
                    _this.resStop(data);
                    break;
                case 'asking':
                    _this.resAsking(data);
                    break;
            }
        },
        resAgree: function(data) {
            var _this = this;
            if (isReg && data.user_to_id == userId && data.user_to_token == userFlag) {
                _this.applySpeakEnd('ed');
            } else {
                _this.showSpeakingTips(data);
            }
            require(['chat'],function(chat){
                chat.sayAsking(teacherName, data.ut, data.ut_n, "同意");
            });
        },
        resStop: function(data) {
            var _this = this;
            if (_this._speakUser == data.ut && _this._speakToken == data.uft) {
                _this._speakUser = 0;
                _this._speakToken = "";
                if (_this._voiceTimerId) {
                    clearTimeout(_this._voiceTimerId);
                    _this._voiceTimerId = null;
                }
                Player.hideSpeakingTips();
                if (isReg && data.user_to_id == userId && data.user_to_token == userFlag) {
                    _this.applySpeakEnd('op');
                }
            }
        },
        resAsking: function(data) {
            var _this = this;
            if (isReg && data.user_to_id == userId && data.user_to_token == userFlag) {
                _this.applySpeakEnd('ed');
            } else {
                _this.showSpeakingTips(data);
            }
            require(['chat'],function(chat){
                chat.sayAsking(teacherName, data.ut, data.ut_n, "要求");
            });
        },
        sendMsg: function(msg) {
            message.ask_cancel({
                content: msg
            });
        },
        showSpeakingTips: function(data) {
            var _this = this;
            var headImg = location.protocol + global.get('filecdn') + data.uf_t;
            var ret = null;
            if (typeof Player.showSpeakingTips == 'function') {
                ret = Player.showSpeakingTips(data.ut_n, headImg);
            }
            if (!ret) {
                _this._voiceTimerId = setTimeout(function() {
                    _this.voiceTimeout(data.ut_n, headImg);
                }, 2000);
            }
        },
        changeStatus: function(st) {
            $(this.item).attr('data-ask', st);
        },
        applySpeak: function() {
            this.changeStatus('ing');
            clearTimeout(this.timer);
            this.nowSec = this.sec;
            this.sendMsg('ask');
            this.timeOut();
        },
        applySpeakEnd: function(st, flag) {
            clearTimeout(this.timer);
            $(this.timeText).hide();
            this.changeStatus(st);
            this.handCancel();
            flag && this.sendMsg('cancel');
        },
        handCancel: function() {
            Player.hideSpeakingTips();
            if (typeof Player.record == 'function') {
                Player.record();
            }
        },
        timeOut: function() {
            this.nowSec--;
            if (this.nowSec <= 0) {
                this.applySpeakEnd('op', true);
            } else {
                $(this.timeText).text(this.nowsec).show();
                this.timer = setTimeout(this.timeOut, 1000);
            }
        },
        cancelTimeout: function() {
            clearTimeout(this.timer);
            this.applySpeakEnd('op', true);
        },
        reset: function() {
            clearTimeout(this.timer);
            this.changeStatus('op');
        },
        dealStatus: function() {
            var _this = this;
            var status = $(this.item).attr('data-ask');
            if (status == 'op') {
                _this.applySpeak();
            } else if (status == 'ing') {
                _this.applySpeakEnd('op', true);
            } else if (status == 'ed') {
                _this.applySpeakEnd('op');
            }
        },
        bind: function() {
            var _this = this;
            $(this.item).on('click', function() {
                _this.dealStatus();
            });
        },
        regist: function() {
            // 注册学生发起的 举手，取消发言
            message.regist({
                type: messageType.ask_cancel,
                send: 'ask_cancel'
            });
            // 注册 老师发起的同意，拒绝，结束，要求学生发言
            message.regist({
                type: messageType.agree_refuse,
                getMsg: this.responseMsg
            });
        },
        init: function() {
            $(this.item).show();
            this.regist();
            this.bind();
        }
    };
    Speak.init();
    // 禁言 个人
    var Gag = {
        responseMsg: function(data) {
            if (data.user_to_id == userId) {
                if ("forbid" == data.content) {
                    this.singleGag(data);
                } else if ("free" == data.content) {
                    this.singleFree(data);
                }
            }
        },
        singleGag: function(data) {
            global.set({
                _singlenotalkFlag: true
            });
            if (isReg) {
                $('#chatInput').addClass('chat-forbid');
                if(data){
                    require(['chat'],function(chat){
                        chat.sayforbid(teacherName, data.user_to_id, data.ut_n, true);
                    });
                }
                $("#chatEdit").html('<img src="/assets_v2/img/gag.png">你已被老师禁言');
                $("#ask").hide();
            }
        },
        singleFree: function(data) {
            global.set({
                _singlenotalkFlag: true
            });
            if (isReg) {
                $('#chatInput').removeClass('chat-forbid');
                if(data){
                    require(['chat'],function(chat){
                        chat.sayforbid(teacherName, data.user_to_id, data.ut_n, false);
                    });
                }
                $("#chat_input").html("");
                $("#ask").show();
            }
        },
        forbidChat: function(flag) {
            if (!flag) {
                // 解除禁言
                $("#chat_input").attr("contenteditable", true);
                $("#chat_send").removeClass("gray");
                $("#show_pop").removeClass("gray");
            } else {
                // 禁止聊天
            }
        },
        regist: function() {
            message.regist({
                type: messageType.single_notalk,
                getMsg: this.responseMsg
            });
        },
        init: function() {
            this.regist();
        }
    };
    Gag.init();
    // 用户上下线
    var UserLineAction = {
        responseMsg: function(data) {
            if (data.user_from_id != userId || data.uff != userFlag) {
                if ("online" == data.c) {
                    console.log(this)
                    this.lineAction(data, 1);
                } else if ("offline" == data.c) {
                    this.lineAction(data, -1);
                }
                this.setOnlineNum();
                var groupId = global.get('groupId');
                var _groupUids = global.get('_groupUids');
                if (groupId > 0 && data.user_from_id in _groupUids) {
                    this.setGroupOnlineNum();
                }
            }
        },
        setOnlineNum: function() {

        },
        setGroupOnlineNum: function() {

        },
        lineAction: function(data, flag) {
            var _onlineNum = parseInt(global.get('_onlineNum'));
            _onlineNum += flag;
            global.set({
                '_onlineNum': _onlineNum
            });
            var groupId = global.get('groupId');
            if (groupId > 0) {
                if (data.user_from_id in global.get('_groupUids')) {
                    var _groupOnlineNum = parseInt(global.get('_groupOnlineNum'));
                    _groupOnlineNum += flag;
                    global.set({
                        _groupOnlineNum: _groupOnlineNum
                    });
                }
            }
        },
        regist: function() {
            message.regist({
                type: messageType.on_off_line,
                send: 'on_off_line',
                getMsg: this.responseMsg
            })
        },
        sendMsg: function() {
            message.on_off_line({
                content: 'offline'
            });
        },
        init: function() {
            this.regist();
        }
    };
    UserLineAction.init();
    // 开始结束上课
    var ClassAction = {
        responseMsg: function(data) {
            var c = data.content;
            if (c == 'start') {
                this.openLive();
            } else if (c == 'close') {
                this.closeLive();
            }
        },
        openLive: function() {
            global.set({
                isLive: true
            });
            $('#playerContent').show();
            // 开启直播后领取鲜花
            require(['gift'], function(gift) {
                isLogin && isSign && gift.receive();
            });
            // 重新获取笔记数据，更新笔记时间
            $('#noteContents').removeClass('unlive');
            require(['note'], function(note) {
                note.getListData();
            });
            // 重新启动播放器
            Player.close();
            Player.reInit();
            global.set({
                _videoEnd: false
            });
            setTimeout(function() {
                global.set({
                    _videoEnd: true
                })
            }, 1000);
            // 开始上课
            this.startClass();
        },
        closeLive: function() {
            global.set({
                isLive: false
            });
            this.stopClass();
            /*不重新更新笔记，因为刚直播完，后台正在处理笔记时间，无法获取*/
            // 关闭休息层
            require(['relax'], function(relax) {
                relax._hide();
            });
        },
        startClass: function() {
            global.set({
                _classStart: true
            });
            message.startClass();
        },
        stopClass: function() {
            global.set({
                _classStart: false
            });
            message.stopClass();
            Speak.reset();
        },
        regist: function() {
            message.regist({
                type: messageType.start_close,
                getMsg: this.responseMsg
            });
            message.startClass = function() {
                this._timeoutInterval = 2000;
            }
            message.stopClass = function() {
                this._timeoutInterval = 5000;
            }
        },
        init: function() {
            this.regist();
        }
    };
    ClassAction.init();
    // 公告
    var Notice = {
        nodeNotice: $('#notice'),
        nodeChat: $('#chat'),
        nodeContent: $('#notice-content'),
        responseMsg: function(data) {
            if (!data.content) {
                this._remove();
            } else {
                this._edit(data.content);
            }
        },
        _add: function(text) {
            if (!text) {
                this._remove();
            } else {
                var s = $.htmlEncode(text).replace(/\r?\n/g, "<br />").replace(/\s/g, "&nbsp;");
                this.nodeContent.html(s);
                this.nodeChat.addClass('hasNotice');
                this.nodeNotice.addClass('active');
            }
        },
        _remove: function() {
            this.nodeChat.removeClass('hasNotice');
            this.nodeNotice.removeClass('active');
            this.nodeContent.html('');
        },
        _edit: function(text) {
            this._add(text);
        },
        init: function() {
            message.regist({
                type: messageType.notice,
                getMsg: this.responseMsg
            });
            $('.notice-btn').click(function() {
                $('#notice').toggleClass('active');
            });
        }
    };
    // 答题
    var QA = {
        nowType: 0,
        item: $('#qa'),
        question: '#qaQuestion',
        qaSubmitBtn:'#qaSubmitBtn',
        tpl:'',
        responseMsgQuestion: function(data) {
            var _this = this;
            this.renderQuestion(data,function(code,_data){
                _this.preDealQuestion(code,_data);
            });
        },
        responseMsgAnswer: function(data) {
            this.renderAnswer(data);
        },
        responseMsgCancel: function() {

        },
        renderQuestion: function(data,callback){
            var _this = this;
            if(!this.tpl){
                $.ajax({
                    url: '/assets_v2/js/player/tpl/qa-question.js',
                    dataType: 'html'
                })
                .done(function(res) {
                    data = {
                        qType:13
                    }
                    _this.tpl = res;
                    res = template.compile(res)(data);
                    (typeof callback == 'function') && callback(res,data);
                });
            }else{
                var res = template.compile(_this.tpl)(data);
                (typeof callback == 'function') && callback(res,data);
            }
        },
        preDealQuestion: function(code){
            $(this.question).html(code);
        },
        createDom: function(){
            var _this = this;
            $.ajax({
                url: '/assets_v2/js/player/tpl/qa-box.js',
                dataType: 'html'
            })
            .done(function(res) {
                _this.item.html(res);
                _this.responseMsgQuestion({});
            });
        },
        setSubmit: function(flag){
            !flag ? $(qaSubmitBtn).removeClass('active'):$(qaSubmitBtn).addClass('active');
        },
        bind: function(){
            var _this = this;
            this.item.on('click', '.qa-close', function(event) {
                _this.item.removeClass('active');
            })
            .on('click', '.qa-open', function(event) {
                _this.item.addClass('active');
            })
            .on('click', 'input[type=checkbox]', function(event) {
                $('input[type=checkbox]:checked').length > 0 ? _this.setSubmit(true):_this.setSubmit(false);
            })
            .on('click', 'input[type=radio]', function(event) {
                $('input[type=radio]:checked').length > 0 ? _this.setSubmit(true):_this.setSubmit(false);
            })
            .on('keyup', '.qa-edit-item', function(event) {
                !$.trim($(this).val()) ? _this.setSubmit(false):_this.setSubmit(true);
            });
        },
        sendMsg: function(ans){
            var a = {
                id:this.examId,
                answer: ans
            };
            message.issue_answer({
                content:JSON.stringify(a)
            });
        },
        regist: function(){
            message.regist({
                type:messageType.issue_ask,
                getMsg:this.responseMsgQuestion
            });
            message.regist({
                type:messageType.issue_publish,
                getMsg:this.responseMsgAnswer
            });
            message.regist({
                type:messageType.issue_cancel,
                getMsg:this.responseMsgCancel
            });
            message.regist({
                type:messageType.issue_answer,
                send: 'issue_answer'
            });
        },
        init: function(){
            this.createDom();
            this.bind();
            this.regist();
        }
    };
    QA.init();
    var FullScreen = {
        isFullScreen: function() {
            if (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
                return true;
            } else {
                return false;
            }
        },
        webChange: function() {
            var isFull = this.isFullScreen();
            if (isFull) {
                this.setWeb();
            } else {
                this.cancel()
            }
        },
        initFullscreen: function() {
            var isFull = this.isFullScreen();
            if (isFull) {
                this.setWeb();
            }
            var info = Player.info();
            if (info.fullscreen) {
                this.setPlayer();
            }
            this.cancel();
        },
        setWeb: function() {
            message.fullscreen({
                content: 1
            });
        },
        setPlayer: function() {
            message.fullscreen({
                content: 2
            });
        },
        cancel: function() {
            message.fullscreen({
                content: 3
            });
        },
        bind: function() {
            if (isReg) {
                $(document).on("fullscreenchange", this.webChange);
                $(document).on("mozfullscreenchange", this.webChange);
                $(document).on("webkitfullscreenchange", this.webChange);
                $(document).on("MSfullscreenchange", this.webChange);
                this.initFullscreen();
            }
        },
        regist: function() {
            message.regist({
                type: messageType.fullscreen,
                send: 'fullscreen'
            });
        },
        init: function() {
            this.regist();
            this.bind();
        }
    };
    FullScreen.init();
    var Pattern = {
        _pattern_reply: $('#pattern_reply'),
        _pattern_notalk: $('#pattern_notalk'),
        responseMsg: function(data) {
            var _this = this;
            var c = data.content || '';
            switch (c) {
                case 'normal':
                    _this.toNormal(data);
                    break;
                case 'reply':
                    _this.toQA(data);
                    break;
                case 'notalk':
                    _this.toGag(data);
                    break;
            }
        },
        toNormal: function() {
            this._pattern_reply.hide();
            this._pattern_notalk.hide();
            global.set({
                _notalkFlag: false
            });
            global.set({
                _isPatternReply: false
            });
            isReg && Gag.singleGag();
            require(['chat'], function(chat) {
                chat.replyRid();
            });
        },
        toQA: function() {
            this._pattern_reply.show();
            this._pattern_notalk.hide();
            global.set({
                _notalkFlag: false
            });
            global.set({
                _isPatternReply: true
            });
            isReg && Gag.singleFree();
        },
        toGag: function() {
            this._pattern_reply.hide();
            this._pattern_notalk.show();
            global.set({
                _notalkFlag: true
            });
            global.set({
                _isPatternReply: false
            });
            isReg && Gag.singleFree();
            require(['chat'], function(chat) {
                chat.replyRid();
            });
        },
        regist: function() {
            message.regist({
                type: messageType.pattern,
                getMsg: this.responseMsg
            })
        },
        init: function() {
            this.regist();
        }
    };
    Pattern.init();
    return {

    }
});
