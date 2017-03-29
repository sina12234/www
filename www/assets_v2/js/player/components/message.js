// 信号模块
define(['global', 'jquery'], function(global, $) {
    return (function() {
        var instance;
        var MessageType = {
            notice          :   1030,  //公告
            text            :   1,     //文本聊天
            call            :   6,     //点名
            reply           :   7,     //答道
            good            :   100,   //点赞
            reply_text      :   500,   //答题模式文本
            student_total   :   600,   //学生总数
            microphone_test :   700,   //测试麦克风
            fullscreen      :   800,   //全屏状态改变
            ask_cancel      :   1002,  //举手发言，取消发言
            agree_refuse    :   1004,  //同意发言，拒绝发言，结束发言，老师主动要求学生发言
            on_off_line     :   1006,  //用户上下线消息
            start_close     :   1008,  //上下课消息
            pattern         :   1010,  //进入普通模式，答题模式，禁言模式
            microphone_result:  1012,  //测麦成功，失败
            single_notalk   :   1014,  //禁止单个学生发言
            issue_ask       :   1016,  //老师出题
            issue_publish   :   1017,  //老师公布答案
            issue_answer    :   1018,  //学生答题
            issue_cancel    :   1019,  //老师强制收拾答题卡
            request_eval    :   1020,  //老师请求学生评价
            delete_text     :   1022,  //老师要求删除一条聊天记录
            score_info      :   1024,  //老师点赞引起的加分信息
            camera          :   1026,  //学生发言中开启摄像头或关闭
            gift            :   1040,  //学生给老师献礼物
            modify_student  :   1200,  //增加（或者减少）一个学生
            recess          :   1044,  //课间休息，老师发起的
            comeback        :   1045,  //学生响应休息结束
            isLive: function(t) {
                switch (parseInt(t)) {
                    case this.microphone_test:
                    case this.request_eval:
                    case this.modify_student:
                    case this.score_info:
                        return true;
                    default:
                        return false;
                }
            }
        };
        var MessageType2 = {};
        $.each(MessageType, function(k, v) {
            MessageType2[v] = k;
        });

        function getMessageType() {
            var m = MessageType;
            switch (parseInt(m)) {
                case m.text:
                case m.reply_text:
                    return "text";
                case m.good:
                    return "good";
                default:
                    return "signal";
            }
        }

        function init() {
            var g = global.get(['isLogin','plan_id', 'user_id', 'user_info', 'deviceType', 'teacher']);
            var isLogin = g.isLogin;
            var planId = g.plan_id;
            var userId = parseInt(g.user_id);
            var teacherId = parseInt(g.teacher.uid);
            var userName = g.user_info.name;
            var userToken = g.user_info.token;
            var userFlag = userToken && userToken.substring(0, 5);
            var deviceType = g.deviceType;
            window.set_callback = '';
            window.pull_callback = '';

            function type2Id(type) {
                var a = {
                    "text": "TextId",
                    "good": "GoodId",
                    "signal": "SignalId"
                };
                if (type in a) {
                    return a[type];
                }
                return null;
            }

            function type2Id2(type) {
                var a = {
                    "text": "st",
                    "good": "sg",
                    "signal": "ss"
                };
                if (type in a) {
                    return a[type];
                }
                return null;
            }
            var WS = {
                ws: null,
                wsOpenTimerId: null,
                wsTimerId: null,
                _wsURL: '',
                _pullURL: '',
                setURL: '',
                flagOpenSuccess: false,
                notPullFirst: 0,
                _miliSecondsForReconnect: 1000,
                successRadio: function(k, data) {
                    Message.emitSuccess(k, data);
                },
                error: function(data) {
                    console.log('error');
                    if (this.failRadio) {
                        this.failRadio(data);
                    }
                    if (!window.WebSocket) {
                        setTimeout(function() {
                            // this.startWs();
                        }, this._miliSecondsForReconnect);
                    }
                },
                failRadio: null,
                successCallback: null,
                failCallback: null,
                start: {
                    "text": 0,
                    "signal": 0,
                    "good": 0
                },
                g_msg_ids: {
                    "signal": {}
                },
                g_pullExtraNum: 1,
                _open: function() {
                    var _this = this;
                    return function() {
                        _this.flagOpenSuccess = true;
                        console.log('WebSocket Start!~\\(≧▽≦)/~');
                        _this._send();
                        var keepFunc = function() {
                            _this._send("{}");
                            _this.wsTimerId = setTimeout(keepFunc, 10000);
                        };
                        _this.wsTimerId = setTimeout(keepFunc, 10000);
                    }
                },
                _message: function() {
                    var _this = this;
                    return function(e) {
                        console.log('Get New Message!~(～￣▽￣)～');
                        _this.responseMsg(e.data);
                    }
                },
                _close: function() {
                    var _this = this;
                    return function() {
                        console.log('WebSocket Closed!(。﹏。*)');
                        _this.flagOpenSuccess = false;
                    }
                },
                _error: function() {
                    var _this = this;
                    return function() {
                        console.log('WebSocket Error!(。﹏。*)');
                        if (_this.wsOpenTimerId) {
                            clearTimeout(_this.wsOpenTimerId);
                            _this.wsOpenTimerId = null;
                        }
                        if (_this.flagOpenSuccess) {
                            setTimeout(_this.startWs, _this._miliSecondsForReconnect);
                            if (_this.wsTimerId) {
                                clearTimeout(_this.wsTimerId);
                                _this.wsTimerId = null;
                            }
                        } else {
                            _this.ws.onclose = null;
                            window.WebSocket = null;
                            _this._getURL = _this._pullURL;
                            _this.startWs();
                        }
                        _this.ws = null;
                    }
                },
                _dft: function() {
                    return {
                        MessageType: 'get', //  string 必须 消息类型，固定为 get
                        PlanId: planId, //        int 必须
                        UserFlagFrom: userFlag, // string //必须 目标用户标识，取前5位，下行的时候需要      

                        TextLimit: -50, // int 可选，文本限制数，默认为0，不限制，会读取全部，如果 >0 由 获取 大于 StartTextId 的 TextLimit数，如果 <0 ，获取小于 StartTextId的聊天文本条数。
                        StartGoodId: 0, //   int 可选，默认为0，当已经返回过good（点赞）信息后，再次获取时不能为0
                        StartTextId: 0, //   int 可选，默认为0，当已经返回过text（消息）信息后，再次获取时不能为0
                        StartSignalId: 0, // int 可选，默认为0，当已经返回过signal（信号）信息后，再次获取时不能为0

                        UserIdFrom: userId, //   int 可选   //可选 来源用户
                        DeviceType: deviceType // int 可选，终端类型，参考下面表格,0 表示没有设置（有可能是系统消息）
                            // #UserIdTo: //   int 可选   //目标用户
                            // #UserFlagTo: // string 可选 //目标用户标识，取前5位，下行的时候需要
                            // #Content: //     string 可选  //内容
                            // #ContentType: // int 可选    //内容类型
                            // #LastUpdate: //  string 可选  //内容
                            // #LiveSecond: //  float64 可选`json:"LiveSecond"` //直播时间
                    };
                },
                _send: function(opt) {
                    var dft = this._dft();
                    if(!this.flagOpenSuccess){
                        return false;
                    }
                    if (typeof opt == 'string') {
                        this.ws.send(opt);
                    } else if (typeof opt == 'object') {
                        var req = $.extend({}, dft, opt);
                        this.ws.send(JSON.stringify(req));
                    } else if (typeof opt == 'undefined') {
                        this.ws.send(JSON.stringify(dft));
                    }
                },
                responseMsg: function(data) {
                    if (typeof data == 'string') {
                        data = JSON.parse(data);
                    }
                    if (typeof data == 'object') {
                        if ($.isArray(data) && data.length > 0) {
                            // 处理信号
                            this.responseRadioMsg(data);
                        } else {
                            // 处理回调
                            this.responseCallback(data);
                        }
                    }
                    if (!window.WebSocket) {
                        this.startWs();
                    }
                },
                // 处理广播信号
                responseRadioMsg: function(data) {
                    if (data.length < 1) {
                        return false;
                    }
                    // 信号分类及格式化
                    var newMsg = {
                        "good": [],
                        "text": [],
                        "signal": []
                    };
                    $.each(data, function(i, v) {
                        var type = v['mt'];
                        if (type in newMsg) {
                            var idStr = type2Id2(type);
                            v["msg_id"] = v[idStr];
                            v["user_from_id"] = v["uf"];
                            v["user_from_token"] = v["uff"];
                            v["user_to_id"] = v["ut"];
                            v["user_to_token"] = v["uft"];
                            v["plan_id"] = v["pid"];
                            v["type"] = v["ct"];
                            v["live_second"] = v["ls"];
                            v["last_updated"] = v["lu"];
                            if (type == "good") {
                                v["num"] = 1;
                                v["user_id"] = v["user_to_id"];
                            } else {
                                v["content"] = v["c"];
                            }
                            if (teacherId == v["user_from_id"]) {
                                v["is_teacher"] = 1;
                            } else {
                                v["is_teacher"] = 0;
                            }
                            newMsg[type].push(v);
                        }
                    });
                    var _this = this;
                    $.each(newMsg, function(k, v) {
                        var successFlag = typeof _this.successRadio == 'function';
                        if (k == 'total') {
                            successFlag && _this.successRadio(k, [v]);
                        } else if (k == "good" || k == "text") {
                            if ($.isArray(v) && v.length > 0) {
                                if (v[v.length - 1].msg_id > _this.start[k]) {
                                    _this.start[k] = v[v.length - 1].msg_id;
                                }
                                successFlag && _this.successRadio(k, v);
                            }
                        } else if (k in _this.start) {
                            if ($.isArray(v) && v.length > 0) {
                                if (v[v.length - 1].msg_id > _this.start[k]) {
                                    _this.start[k] = v[v.length - 1].msg_id;
                                }
                                var ids = _this.g_msg_ids[k];
                                // 过滤、记录
                                for (var i = v.length - 1; i >= 0; i--) {
                                    if (v[i].msg_id in ids) {
                                        v.splice(i, 1);
                                    } else {
                                        ids[v[i].msg_id] = 1;
                                    }
                                }
                                successFlag && _this.successRadio(k, v);
                            }
                        } else {
                            console.log("*** serious bug! not k = [" + k + "]\n");
                        }
                        successFlag && _this.successRadio('null', null);
                    });
                },
                // 处理个人信号回调
                responseCallback: function(data) {
                    if ("value" in data) {
                        if (0 == data["value"]) {
                            typeof this.failCallback == 'function' && this.failCallback(data);
                        } else {
                            typeof this.successCallback == 'function' && this.successCallback(data);
                        }
                    }
                },
                sendMsg: function(op, success, fail) {
                    if (!op.type) {
                        return false;
                    }
                    this.successCallback = !success ? null : success;
                    this.failCallback = !fail ? null : fail;
                    var opt = {
                        "plan_id": parseInt(planId),
                        "user_from_id": parseInt(userId),
                        "user_from_token": userToken,
                        "message_type": getMessageType(op.type),
                        "type": parseInt(op.type),
                        "device_type": deviceType
                    };
                    op.user_to_id && (opt.user_to_id = parseInt(op.user_to_id));
                    op.user_to_token && (opt.user_to_token = op.user_to_token);
                    op.content && (opt.content = decodeURIComponent(op.content));
                    op.live_second && (opt.live_second = parseFloat(op.live_second));
                    if (window.WebSocket) {
                        this.flagOpenSuccess && this.ws.send(JSON.stringify(opt));
                    } else {
                        window.set_callback = this.successCallback;
                        $.getScript(this._setURL + "?callback=set_callback&data=" + JSON.stringify(opt)).error(this.failCallback);
                    }
                },
                setUrl: function(getURLWs, getURLPull, setURL) {
                    if (!getURLWs) {
                        window.WebSocket = null;
                    }
                    if (window.WebSocket) {
                        this._getURL = getURLWs;
                    } else {
                        this._getURL = getURLPull;
                    }
                    this._wsURL = getURLWs;
                    this._pullURL = getURLPull;
                    this._setURL = setURL;
                    return this;
                },
                limitText: function(textId, limit) {
                    var op = {
                        "StartTextId": textId,
                        "TextLimit": limit
                    };
                    if (window.WebSocket) {
                            this._send(op);
                    } else {
                        var dft = this._dft();
                        dft = $.extend({}, dft, op);
                        $.getScript(this._getURL + "?callback=pull_callback&data=" + JSON.stringify(dft)).error(this.error);
                        this.g_pullExtraNum++;
                    }
                },
                startWs:function(){
                    var _this = this;
                    if (window.WebSocket) {
                        this.ws = new WebSocket(this._wsURL);
                        this.ws.onopen = this._open();
                        this.ws.onmessage = this._message();
                        this.ws.onclose = this._close();
                        this.ws.onerror = this._error();
                    } else {
                        if (this.g_pullExtraNum > 0) {
                            this.g_pullExtraNum--;
                            return;
                        }
                        var dft = this._dft();
                        if (!_this.notPullFirst) {
                            $.getScript(this._getURL + "?callback=pull_callback&data=" + JSON.stringify(dft)).error(this.error);
                            _this.notPullFirst = 1;
                        } else {
                            delete dft.TextLimit;
                            $.getScript(this._getURL + "?callback=pull_callback&data=" + JSON.stringify(dft)).error(this.error);
                        }
                    }
                },
                init: function() {
                    if(!isLogin){
                        return false;
                    }
                    var _this = this;
                    this.setUrl(chatWs, chatPull, chatPullSet);
                    window.pull_callback = function(data) {
                        _this.responseMsg(data);
                    }
                    _this.startWs();
                    $.ajaxSetup({
                        timeout: 150000
                    });
                }
            };
            /**
             * 消息队列说明
             * 逻辑：
             * 发出消息=>加入队列
             * 初始化完毕=>处理队列消息=>判断=>取出第一条数据=>设置参数及回调函数=>等待响应=>响应完毕=>循环
             */
            var Message = {
                list: [], // 发送消息处理队列
                enqueupFlag: 0,
                _getFlag: false,
                _addMsgId: {
                    "text": [],
                    "good": [],
                    "signal": []
                },
                init: function() {

                },
                send: function() {
                    if (this.list.length) {
                        WS.sendMsg(this.list[0], this.sendSuccess(), this.sendFail());
                    }
                },
                filter: function(k, data) {
                    var realData = [];
                    var _this = this;
                    if (k != 'null') {
                        var finalDeal = {
                            "line": {},
                            "fullscreen": {},
                            "pattern": null,
                            "class": null,
                            "ask": {},
                            "issue": null,
                            "answer": [],
                            "reply": {},
                            "camera": {},
                            "comeback": {}
                        };
                        $.each(data, function(index, item) {
                            var ikey = _this._addMsgId[k].indexOf(item.msg_id);
                            if (ikey >= 0) {
                                _this._addMsgId[k] = _this._addMsgId[k].slice(ikey + 1);
                            } else {
                                if (!_this._getFlag && "signal" == k && !MessageType.isLive(item.type)) {
                                    var aaa = {};
                                    if (MessageType.on_off_line == item.type) {
                                        if (!(item.user_from_id in finalDeal["line"])) {
                                            finalDeal["line"][item.user_from_id] = {}
                                        }
                                        finalDeal["line"][item.user_from_id][item.user_from_token] = item;
                                        if (item.user_from_id in finalDeal.reply && item.user_from_token in finalDeal.reply[item.user_from_id]) {
                                            delete finalDeal.reply[item.user_from_id][item.user_from_token];
                                        }
                                        if (item.user_from_id in finalDeal.ask && item.user_from_token in finalDeal.ask[item.user_from_id]) {
                                            delete finalDeal.ask[item.user_from_id][item.user_from_token];
                                        }
                                    } else if (MessageType.fullscreen == item.type) {
                                        if (!(item.user_from_id in finalDeal["fullscreen"])) {
                                            finalDeal["fullscreen"][item.user_from_id] = {}
                                        }
                                        finalDeal["fullscreen"][item.user_from_id][item.user_from_token] = item;
                                    } else if (MessageType.pattern == item.type) {
                                        finalDeal["pattern"] = item;
                                    } else if (MessageType.start_close == item.type) {
                                        finalDeal["class"] = item;
                                    } else if (MessageType.ask_cancel == item.type) {
                                        if (!(item.user_from_id in finalDeal["ask"])) {
                                            finalDeal["ask"][item.user_from_id] = {};
                                        }
                                        finalDeal["ask"][item.user_from_id][item.user_from_token] = item;
                                        if (item.user_from_id in finalDeal["camera"] && item.user_from_token in finalDeal["camera"][item.user_from_id]) {
                                            delete finalDeal["camera"][item.user_from_id][item.user_from_token];
                                        }
                                    } else if (MessageType.reply == item.type) {
                                        if (item.user_from_id in finalDeal["reply"]) {
                                            finalDeal["reply"][item.user_from_id][item.user_from_token] = item;
                                        } else {
                                            aaa[item.user_from_token] = item;
                                            finalDeal["reply"][item.user_from_id] = aaa;
                                        }
                                    } else if (MessageType.call == item.type) {
                                        if (item.user_to_id in finalDeal["reply"]) {
                                            finalDeal["reply"][item.user_to_id][item.user_to_token] = item;
                                        } else {
                                            aaa[item.user_to_token] = item;
                                            finalDeal["reply"][item.user_to_id] = aaa;
                                        }
                                    } else if (MessageType.issue_ask == item.type || MessageType.issue_publish == item.type || MessageType.issue_cancel == item.type) {
                                        finalDeal["issue"] = item;
                                    } else if (MessageType.issue_answer == item.type) {
                                        finalDeal["answer"].push(item);
                                    } else if (MessageType.agree_refuse == item.type) {
                                        if (!(item.user_to_id in finalDeal["ask"])) {
                                            finalDeal["ask"][item.user_to_id] = {};
                                        }
                                        finalDeal["ask"][item.user_to_id][item.user_to_token] = item;
                                        if (item.user_to_id in finalDeal["camera"] && item.user_to_token in finalDeal["camera"][item.user_to_id]) {
                                            delete finalDeal["camera"][item.user_to_id][item.user_to_token];
                                        }
                                    } else if (MessageType.camera == item.type) {
                                        if (!(item.user_from_id in finalDeal["camera"])) {
                                            finalDeal["camera"][item.user_from_id] = {}
                                        }
                                        finalDeal["camera"][item.user_from_id][item.user_from_token] = item;
                                    } else if (MessageType.comeback == item.type) {
                                        if (item.user_from_id in finalDeal["comeback"]) {
                                            delete finalDeal["comeback"][item.user_from_id][item.user_from_token];
                                        } else {
                                            if (0 in finalDeal["comeback"]) {
                                                delete finalDeal["comeback"][0];
                                            }
                                        }
                                    } else if (MessageType.recess == item.type) {
                                        if (item.user_to_id in finalDeal["comeback"] && item.user_to_token in finalDeal["comeback"][item.user_to_id]) {
                                            finalDeal["comeback"][item.user_to_id][item.user_to_token] = item;
                                        } else {
                                            aaa[item.user_to_token] = item;
                                            finalDeal["comeback"][item.user_to_id] = aaa;
                                        }
                                    } else {
                                        realData.push(item);
                                    }
                                } else {
                                    realData.push(item);
                                }
                            }
                        });
                        if (!_this._getFlag && "signal" == k) {
                            for (var u in finalDeal["line"]) {
                                for (var t in finalDeal["line"][u]) {
                                    if ("online" == finalDeal["line"][u][t].content) {
                                        realData.push(finalDeal["line"][u][t]);
                                    }
                                }
                            }
                            for (var u in finalDeal["ask"]) {
                                var _asks = {
                                    "ask": 1,
                                    "agree": 1,
                                    "asking": 1
                                };
                                var _a = finalDeal["ask"][u];
                                for (var t in _a) {
                                    if (_a[t].content in _asks) { 
                                        realData.push(_a[t]);
                                    }
                                }
                            }
                            for (var u in finalDeal["comeback"]) {
                                for (var t in finalDeal["comeback"][u]) {
                                    realData.push(finalDeal["comeback"][u][t]);
                                }
                            }
                            for (var u in finalDeal["fullscreen"]) {
                                for (var t in finalDeal["fullscreen"][u]) {
                                    realData.push(finalDeal["fullscreen"][u][t]);
                                }
                            }
                            if (finalDeal["pattern"]) {
                                realData.push(finalDeal["pattern"]);
                            }
                            if (finalDeal["class"]) {
                                realData.push(finalDeal["class"]);
                            }
                            if (finalDeal["issue"]) {
                                realData.push(finalDeal["issue"]);
                            }
                            if (finalDeal["answer"]) {
                                for (var i in finalDeal["answer"]) {
                                    realData.push(finalDeal["answer"][i]);
                                }
                            }
                            for (var u in finalDeal["reply"]) {
                                for (var token in finalDeal["reply"][u]) {
                                    realData.push(finalDeal["reply"][u][token]);
                                }
                            }
                            var cameraFlag = false;
                            for (var u in finalDeal["camera"]) {
                                for (var token in finalDeal["camera"][u]) {
                                    if ("open" == finalDeal["camera"][u][token].content) {
                                        realData.push(finalDeal["camera"][u][token]);
                                        cameraFlag = true;
                                        break;
                                    }
                                }
                                if (cameraFlag) {
                                    break;
                                }
                            }
                            _this._getFlag = true;
                        }
                    }
                    return realData;
                },
                emitSuccess: function(k, data) {
                    if (k == 'null') {
                        return false;
                    }
                    if ($.isArray(data) && data.length > 0) {
                        data = this.filter(k, data);
                        $.each(data, function(i, v) {
                            var type = v.type || 0;
                            var name = 'emit' + type;
                            if (typeof Message[name] == 'function') {
                                Message[name](v);
                            }
                        });
                    }
                },
                sendSuccess: function() {
                    var _this = this;
                    return function(data) {
                        console.log('发送消息回调成功',data);
                        if(!data){
                            return false;
                        }
                        var list = _this.list.reverse();
                        if (list.length > 0) {
                            var item = list.pop();
                            _this.list = list.reverse();
                            var msgType = data['key'];
                            if (msgType in WS.g_msg_ids) {
                                var msgId = parseInt(data["value"]);
                                var ids = WS.g_msg_ids[msgType];
                                if (0 == msgId || msgId in ids) {
                                } else {
                                    if (typeof item.success == 'function') {
                                        var info = data.info ? JSON.parse(data.info) : null;
                                        ids[msgId] = 1;
                                        item.success(msgId, info)
                                    }
                                }
                            }
                        }
                    }
                },
                sendFail: function() {
                    var _this = this;
                    return function(data) {
                        if(!data){
                            return false;
                        }
                        var list = _this.list.reverse();
                        if (_this.enqueupFlag < 2) {
                            setTimeout(function() {
                                _this.send();
                                _this.enqueupFlag++;
                            }, 1000);
                        } else {
                            var item = list.pop();
                            _this.list = list.reverse();
                            if (typeof item.fail == 'function') {
                                item.fail(data, item);
                            }
                            if (_this.list.length) {
                                setTimeout(function() {
                                    _this.dequeue();
                                }, 1000);
                            }
                        }
                    }
                },
                // 出队
                dequeue: function() {
                    if (this.list.length) {
                        this.send();
                        this.enqueupFlag = 1;
                    } else {
                        this.enqueupFlag = 0;
                    }
                },
                // 入队
                enqueue: function(op) {
                    // {
                    //     user_to_id, // 可选
                    //     user_to_token, // 可选
                    //     type, // 必须，当使用regist方法注册时，可以省去
                    //     content, // 可选 发送消息的内容
                    //     success: null // 可选 发送成功回调函数
                    //     fail: null // 可选 发送成功回调函数
                    // }
                    var s = 0;
                    var a = global.Flash && global.Flash.info().currentTime;
                    if (a) {
                        s = a;
                    }
                    if (!s) {
                        a = global.H5Player && global.H5Player.info().currentTime;
                        if (a) {
                            s = a;
                        }
                    }
                    op.live_second = s;
                    this.list.push(op);
                    if (this.list.length == 1) {
                        this.dequeue();
                    }
                },
                register: function(op) {
                    var _this = this;
                    if (op.type && op.type in MessageType2) {
                        if (typeof op.send == 'string') {
                            if (op.send in Public) {
                                console.log(op.send + ' 已存在');
                            } else {
                                Public[op.send] = function(args) {
                                    var sendArgs = args || {};
                                    sendArgs.type = op.types;
                                    _this.enqueue(sendArgs);
                                }
                            }
                        }
                        if (typeof op.getMsg == 'function') {
                            var name = 'emit' + op.type;
                            if (name in Message) {
                                console.error(name + ' 已存在');
                            } else {
                                Message[name] = function(data) {
                                    op.getMsg(data);
                                }
                            }
                        }
                    }
                }

            };
            // 开放接口
            var Public = {
                showQueue: function() {
                    return Message.list;
                },
                getSocketStatus: function() {
                    if (window.WebSocket) {
                        return WS.flagOpenSuccess;
                    } else {
                        return '此浏览器不支持websocket';
                    }
                },
                MessageType: $.extend({}, MessageType),
                regist: function(op) {
                    Message.register(op);
                }
            };
            // 开放接口实例
            // 注册处理模块
            // {   
            //     type: 1045, // 信号类型
            //     send:{}, // 发送消息传入的参数
            //     getMsg:function(){} // 收到处理函数
            // }
            // Public.regist({
            //     type: 1045,
            //     send: 'comeback',
            //     getMsg: function(data) {
            //         console.log(data);
            //     }
            // });
            // 使用  'Public'为对象名称，视实际字面量而定
            // Public.comeback({type:1045});

            WS.init();
            return Public;
        }
        return (function() {
            if (!instance) {
                instance = init();
            }
            return instance;
        })();
    })();
});
