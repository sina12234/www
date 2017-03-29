// flash 模块
define(['message', 'global', 'playerFlash', 'swfobject', 'curtain', 'rank', 'playerH5'], function(message, global, playerFlash, swfobject, curtain, rank, H5Player) {
    //视频断开后弹层
    var g = global.get(['isReg', 'supportDM', 'needDM', 'plan_id', 'teacherName', 'user_id']);
    var isLive = global.getFunc('isLive');
    var supportDM = g.supportDM;
    var needDM = g.needDM;
    var planId = g.plan_id;
    var userId = g.user_id;
    var isReg = g.isReg;
    var teacherName = g.teacherName;

    var messageType = message.MessageType;
    var flash_callback = function(data) {
        if (!isReg) {
            return;
        }
        var flag = false;
        for (var k in data) {
            if ("exist" == k || "connected" == k || "recorded" == k) {
                if (!data[k]) {
                    flag = true;
                }
            } else {
                console.log("k=[" + k + "] v=[" + data[k] + "], unimplemented!\n");
            }
        }
        if ("ed" == $("#ask").attr("ask") && flag) {
            require(['living'],function(living){
                living.Speak.dealStatus();
            });
        }
    }
    var microphone_callback = function(flag) {
        if (!isReg) {
            return;
        }
        message.regist({
            type: messageType.microphone_result,
            send: 'microphone_result'
        });
        if (flag) {
            message.microphone_result({
                content: 'succeed'
            });
        } else {
            message.microphone_result({
                content: 'fail'
            });
        }
    }

    var flash_camera_handle = function(flag) {
        if (!isReg) {
            return;
        }
        message.regist({
            type: messageType.camera,
            send: 'camera'
        });
        if (flag) {
            message.camera({
                content: 'open'
            });
        } else {
            message.camera({
                content: 'close'
            });
        }
    }
    var stopRecord = function() {
        message.regist({
            type: messageType.ask_cancel,
            send: 'ask_cancel'
        });
        message.ask_cancel({
            content: 'cancel'
        });
    }
    var flash_fullscreen_callback = function(flag) {
        if (!isReg) {
            return;
        }
        require(['living'],function(living){
            var fullscreen = living.fullscreen;
            if (flag) {
                fullscreen.setPlayer();
            } else {
                fullscreen.cancel();
            }
        });
    }
    var flash_buy_box_callback = function(data) {
            var publicType = parseInt(data["publicType"]);
            var dom;
            if (0 == publicType) {
                dom = $("#login_remind");
                dom.find("[data-class=title]").text(data["title"]);
                dom.find("[data-class=teacher]").text(teacherName);
                dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
                dom.show();
            } else if (1 == publicType) {
                dom = $("#fee_remind");
                dom.find("[data-class=fee]").text("￥" + data["price"]);
                dom.find("[data-class=title]").text(data["title"]);
                dom.find("[data-class=teacher]").text(teacherName);
                dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
                dom.find("[data-class=href]").attr("href", "/course.info.show/" + data["id"]);
                dom.show();
            } else {
                dom = $("#sign_remind");
                dom.find("[data-class=title]").text(data["title"]);
                dom.find("[data-class=teacher]").text(teacherName);
                dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
                dom.find("[data-class=href]").attr("href", "/course.info.show/" + data["id"]);
                dom.show();
            }
        }
        //录播课结束
    var flash_end_callback = function() {
        if (!global.get('true')) {
            return;
        }
        curtain.showVideoEnd();
    };
    // 直播课结束
    var flash_ending_callback = function() {
            curtain.showLiveEnd();
            for (var i = 0; i <= 2; i++) {
                rank.checksort(i);
            }
            var url = "/exam.log.loguserques";
            var request = {};
            request["user_id"] = userId;
            request["plan_id"] = planId;
            var successCallback = function(r) {
                $("#countAll").text(r.all);
                $("#countCorrect").text(r.correct);
                $("#countMistake").text(r.mistake);
                $("#countMiss").text(r.miss);
            }
            $.post(url, request, successCallback, "json");
        }
        // 和播放器交互的页面dom
    var PlayerFunc = {
        showPlayerMessage: function(msg1, butType, msg2) {
            $('#callPlayerMsg1').text(msg1);
            $('#callPlayerMsg2').text(msg2 || '');
            var reloadBtn = $('#callPlayerMsgButton');
            butType == 0 ? reloadBtn.show() : reloadBtn.hide();
            $('#callPlayerMsg').show();
        },
        hidePlayerMessage: function() {
            $('#callPlayerMsg').hide();
        },
        flash_callback: flash_callback,
        microphone_callback: microphone_callback,
        flash_fullscreen_callback: flash_fullscreen_callback,
        flash_buy_box_callback: flash_buy_box_callback,
        flash_end_callback: flash_end_callback,
        flash_ending_callback: flash_ending_callback,
        flash_camera_handle: flash_camera_handle,
        stopRecord: stopRecord,
        updateTime: function(curTime) {
            isLive() && DMS.updateTime(curTime);
        },
        barrageSwitch: function(flag) {
            supportDM && DMS.openOrClose(flag);
        },
        playOrPauseVideo: function(flag) {
            supportDM && needDM && DMS.playOrPause(flag);
        }

    };
    window.PlayerFunc = PlayerFunc;

    var flashvars = {
        auto_play: true,
        stream_type: "高清",
        fullscreen_func: "FullScreen.toggle",
        chat_publisher_func: "PlayerFunc.flash_callback",
        microphone_func: "PlayerFunc.microphone_callback",
        player_fullscreen_func: "PlayerFunc.flash_fullscreen_callback",
        buy_box_func: "PlayerFunc.flash_buy_box_callback",
        error_func: "Player.error",
        video_end: "PlayerFunc.flash_end_callback",
        camera_handle: "PlayerFunc.flash_camera_handle",
        plan_id: planId,
        showPlayerMessage: "PlayerFunc.showPlayerMessage",
        hidePlayerMessage: "PlayerFunc.hidePlayerMessage",
        stopRecord: "PlayerFunc.stopRecord",
        useBarrge: !supportDM ? false : true,
        defaultBarrageStatus: !supportDM ? false : needDM,
        updateTime: "PlayerFunc.updateTime",
        barrageSwitch: "PlayerFunc.barrageSwitch",
        playOrPauseVideo: "PlayerFunc.playOrPauseVideo"
    };

    var params = {
        menu: "true",
        scale: "noScale",
        allowFullscreen: "true",
        allowScriptAccess: "always",
        bgcolor: "",
        wmode: "opaque"
    };

    var attributes = {
        id: "player",
        name: "player"
    };

    swfobject.embedSWF(
        swf_player, "player", "100%", "100%", "13.0.0", swf_expressInstall, flashvars, params, attributes,
        function(r) {
            if (r.success == false) {
                H5Player.init(planId, "");
            }
        }
    );
    return PlayerFunc;
});
