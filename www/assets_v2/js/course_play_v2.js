/**
 * app/templates/course/plan.play.v2.html 播放页已经不再引用此文件
 * 引用的此文件被合并到了 /assets_v2/js/player/module.js
 * 如有修改 请同时修改 /assets_v2/js/player/module.js 的部分
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
//记录所有聊天记录的
function TextList(){
    this._id = 0;
    this._completed = false;
    this._tmpList = [];
    this._before = [];
    this._after = [];
    this._total = [];
    this._num = 0;
}
TextList.prototype.setId = function(_id){
    this._id = _id;
}
TextList.prototype.complete = function(){
    this._completed = true;
    this._num = this._before.length;
    this._total = this._before.concat(this._after);
    this._before = false;
    this._after = false;
    this._liveList = this._total.slice(0);
    this._liveList.sort(function(a, b){return a.ls-b.ls;});
}
TextList.prototype._delete = function(_id){
    if(this._completed){
        for(var i=0;i<this._total.length;i++){
            if(this._total[i].st == _id){
                this._total = this._total.slice(0, i).concat(this._total[i+1]);
                break;
            }
        }
        for(var i=0;i<this._liveList.length;i++){
            if(this._liveList[i].st == _id){
                this._liveList = this._liveList.slice(0, i).concat(this._liveList[i+1]);
                return;
            }
        }
    }else{
        for(var i=0;i<this._tmpList.length;i++){
            if(this._tmpList[i].st == _id){
                this._tmpList = this._tmpList.slice(0, i).concat(this._tmpList[i+1]);
                return;
            }
        }
        for(var i=0;i<this._before.length;i++){
            if(this._before[i].st == _id){
                this._before = this._before.slice(0, i).concat(this._before[i+1]);
                return;
            }
        }
        for(var i=0;i<this._after.length;i++){
            if(this._after[i].st == _id){
                this._after = this._after.slice(0, i).concat(this._after[i+1]);
                return;
            }
        }
    }
}
TextList.prototype.append = function(data){
    if(data.st <= this._id){
        this._tmpList.push(data);
    }else{
        if(this._completed){
            this._total.push(data);
        }else{
            this._after.push(data);
        }
    }
}
TextList.prototype.onceComplete = function(){
    if(!this._completed){
        if(this._tmpList.length){
            if(this._before.length){
                if(this._tmpList[0].st < this._before[0].st){
                    this._before = this._tmpList.concat(this._before);
                }else{
                    this._before = this._before.concat(this._tmpList);
                }
            }else{
                this._before = this._tmpList;
            }
            this._tmpList = [];
        }
    }
}
TextList.prototype.getFirst = function(direction, num){
    if(!this._num){
        return [];
    }
    if(1 == direction){
        if(num <= this._num){
            this._index = num;
        }else{
            this._index = this._num;
        }
        return this._total.slice(0, this._index);
    }else{
        if(num <= this._num){
            this._index = this._num - num;
        }else{
            this._index = 0;
        }
        return this._total.slice(this._index, this._num);
    }
}
TextList.prototype.getText = function(direction, num){
    if(1 == direction){
        if(this._index >= this._num){
            return [];
        }else{
            var index = this._index;
            if(this._index + num <= this._num){
                this._index = this._index + num;
            }else{
                this._index = this._num;
            }
            return this._total.slice(index, this._index);
        }
    }else{
        if(this._index <= 0){
            return [];
        }else{
            var index = this._index;
            if(num <= this._index){
                this._index = this._index - num;
            }else{
                this._index = 0;
            }
            return this._total.slice(this._index, index);
        }
    }
}
TextList.prototype.getLiveTexts = function(start, duration){
    if(!this._completed){
        return "waiting";
    }
    if(!this._liveList.length){
        return [];
    }
    var liveList = this._liveList;
    function getIndex(ls){
        if(ls <= liveList[0].ls){
            return 0;
        }
        if(liveList[liveList.length-1].ls < ls){
            return -1;
        }
        var start = 0, end = liveList.length - 1;
        while(start + 1 < end){
            var middle = parseInt((start + end + 1) / 2);
            if(liveList[middle].ls < ls){
                start = middle;
            }else{
                end = middle;
            }
        }
        return end;
    }
    var index = getIndex(start);
    if(index >= 0){
        var result = [this._liveList[index]];
        var ls = this._liveList[index].ls + 1;
        if(ls < start + duration){
            ls = start + duration;
        }
        for(var i=index+1;i<this._liveList.length;i++){
            if(this._liveList[i].ls < ls){
                result.push(this._liveList[i]);
            }
        }
        return result;
    }else{
        return [];
    }
}
function planPlay(){
    var _getGroupUrl = "/course/group/UserList";
    var _getGroupPageSize = 500;
    var _goodList = $("#good_list");
    var _input = $("#chat_input");
    var _pattern_reply = $("#pattern_reply");
    var _pattern_notalk = $("#pattern_notalk");
    var _notalkFlag = false;
    var _singlenotalkFlag = false;
    var _students = $("#students");
    var _onlineNum = 1;
    var _issueId = 0;   //答题id
    var _courseTest = $("#course_test");
    var _courseTestTimer = null;
    var _testTime=null;
    var timeIndex = 0;
    var _endsort = false;
    _lastAddTime = [];
    var _callId = 0;
    var _message = null;
    var _oldFlag = true;
    var _partialText = {"once":200, "accu":0, "_id":0, "_id2":0};    //once--一次取的个数，accu--一个websocket累计个数，_id--初始化的文本id（进入时最大id）,_id2--中间取的id限制
    var _partialTextDirection = 1;      //增加聊天方向，1--正向， 2--反向
    if(isLiving){
        _partialTextDirection = 2;
    }
    var _isInitTextOk = false;
    var _studentTotal = $("#student_total");
    var _studentGroup = $("#student_group");
    var _total_num = parseInt($("#student_total").find("b[total]").text());
    if(!isReg){
        chatNotalk(true);
        $("#cantalk").hide();
    }
    var _videoEnd = true;
    var _voiceTimerId = null;
    var _askTimerId = null;
    var _voiceNum = 15;
    var _cameraOpen = false;
    var _speakUser = 0;
    var _speakToken = "";
    var _groupUids = {};
    var _groupOnlineNum = 1;
    var _textList = new TextList();
    //如果有分组，发ajax请求取分组（有可能多次请求），取到分组后，并且设置？？？
    function initGroup(){
        if(!_chat_list){
            _chat_list = $("#chat_list");
        }
        if(!_chat_group_list){
            _chat_group_list = $("#chat_group_list");
        }
        _displayChatList = new ChatList(_chat_list, 200, 60, function(e){
            _displayChatList.move(_activeGroupId);
        });
        var news = $("#news");
        _displayChatList.setNews($("#news"));
        $("#news").click(function(e){
            _displayChatList.moveEnd(_activeGroupId);
            _scrollFlag = false;
        });
        if(groupId > 0){
            var page = 1;
            function getGroupSuccess(data){
                if(0 == data["code"]){
                    for(var i=0;i<data.data.length;i++){
                        _groupUids[parseInt(data.data[i])] = 1;
                    }
                    if(_getGroupPageSize == data.data.length){
                        page++;
                        getGroupData();
                    }else{
                        _groupUids[teacherId] = 1;
                        _groupUids[groupUid] = 1;
                        _displayChatList.addGroup(groupId, _chat_group_list, _groupUids);
                        _message.getMsg();
                    }
                }else{
                    setTimeout(getGroupData, 5000);
                }
            }
            function getGroupError(data){
                setTimeout(getGroupData, 5000);
            }
            function getGroupData(){
                var request = {"classid":class_id, "groupid":groupId, "type":1, "page":page, "pagesize":_getGroupPageSize};
                $.post(_getGroupUrl, request, getGroupSuccess, "json").error(getGroupError);
                //console.log("get group data, url=["+_getGroupUrl+"] data=["+JSON.stringify(request)+"]");
            }
            getGroupData();
            if(_studentTotal.length){
                _studentTotal.on("click", function(e){
                    $(this).addClass('curr').siblings('.chat-num').removeClass('curr');
                    _activeGroupId = 0;
                    _chat_list.show();
                    _chat_group_list.hide();
                    _displayChatList.switchNews(_activeGroupId);
                });
                _studentGroup.on("click", function(e){
                    $(this).addClass('curr').siblings('.chat-num').removeClass('curr');
                    _activeGroupId = groupId;
                    _chat_list.hide();
                    _chat_group_list.show();
                    _displayChatList.switchNews(_activeGroupId);
                });
            }else{
                _activeGroupId = groupId;
            }
        }else{
            setTimeout(function(){
                _message.getMsg();
            }, 20);
        }
    }
    initGroup();
    //一个websocket包之后对分批文本的处理
    function historyTextDisplay(dataList){
        for(var i in dataList){
            if(supportDM){DM_comment.isNew = false;}
            textDeal(dataList[i]);
        }
        if(1 == _partialTextDirection){
            inputScroll(1);
        }else{
            inputScroll(false);
        }
        if(dataList.length == _partialText.once){
            _displayChatList.deleteMore();
            _displayChatList.addMore();
            if(1 == _partialTextDirection){
                if(_partialText.once + 2 == _chat_list.children().length){
                    _scrollFlag = false;
                    _displayChatList.moveStart(_activeGroupId);
                }
            }else{
                if(_partialText.once + 1 == _chat_list.children().length){
                    _displayChatList.moveEnd(_activeGroupId);
                }
            }
        }else if(dataList.length > 0){
            if(1 == _partialTextDirection){
                if(_partialText.accu + 2 == _chat_list.children().length){
                    _scrollFlag = false;
                    _displayChatList.moveStart(_activeGroupId);
                }
            }else{
                if(_partialText.accu + 1 == _chat_list.children().length){
                    _displayChatList.moveEnd(_activeGroupId);
                }
            }
        }
    }
    function historyTextComplete(){
        _textList.complete();
        var dataList = _textList.getFirst(_partialTextDirection, _partialText.once);
        if(dataList.length){
            historyTextDisplay(dataList);
        }
    }
    function partialTextDeal(){
        if(_textList._completed){
            return;
        }
        if(_oldFlag){   //初始化
            if(0 == _partialText._id){
                _textList.setId(0);
                _textList.complete();
                return;
            }
            _textList.setId(_partialText._id+1);
            _displayChatList.addDiv();
            _message.limitText(_partialText._id2, _partialText.once);
        }else{
            if(_partialText.accu > 0){
                _textList.onceComplete();
                if(_isInitTextOk){
                    historyTextComplete();
                }else{
                    _message.limitText(_partialText._id2, _partialText.once);
                }
            }
            _partialText.accu = 0;
        }
    }
    function replyText(data){
        if(userId == data.user_from_id){
            textResponse(data, _partialText._id);
        }else{
            if(_isPatternReply){
                replyTextResponse(data, _partialText._id);
            }else{
                textResponse(data, _partialText._id);
            }
        }
    }
    function textDeal(data){
        if(MessageType.reply_text == data.type){
            replyText(data);
        }else{
            textResponse(data, _partialText._id);
        }
    }
    function partialTextOnce(data){
        if(_oldFlag){
            if(data.st > _partialText._id){
                _partialText._id = data.st;
            }
        }else{
            if(data.st <= _partialText._id){
                _partialText.accu++;
                if(data.st == _partialText._id){    //初始化的时候，不能碰到老师正好删除最后一条文本
                    _isInitTextOk = true;
                }
            }
            if(data.st > _partialText._id2 && data.st < _partialText._id){
                _partialText._id2 = data.st;
            }
            _textList.append(data);
            if( _partialText._id < data.st){
                if(supportDM){DM_comment.isNew = true;}
                textDeal(data);
            }
        }
    }
    function dataMoreClick(e){
        var dataList = _textList.getText(_partialTextDirection, _partialText.once);
        if(dataList.length){
            historyTextDisplay(dataList);
        }
        $(e.target).css("visibility", "hidden");
    }
    _chat_list.on("click", "li[data-more]", dataMoreClick);
    _chat_group_list.on("click", "li[data-more]", dataMoreClick);
    function dealIssueAsk(content){
        if(_issueId){
            return;
        }
        var issue;
        try{
            issue = JSON.parse(content);
        }catch(e){
            return;
        }
        if("id" in issue && "qType" in issue && "answer" in issue && ("text" in issue || "img" in issue)){
            _issueId = issue["id"];
            if(_courseTestTimer){
                clearTimeout(_courseTestTimer);
                _courseTestTimer = null;
            }
            if(_testTime){
                clearInterval(_testTime);
                _testTime = null;
                timeIndex = 0;
            }
            var type = "checkbox";
            if(1 == issue["qType"] || 10 == issue["qType"]){
                type = "radio";
            } else if(12 == issue["qType"]){
                type = "judge";
            } else if(13 == issue["qType"]){
                type="fill";
            }
            if (issue["qType"] === 20 ){
                $("#ask-layer,.ask-bg").remove();
                var _alertBg=$('<div class="alert-layer" id="ask-layer"></div>');
                var _askBg=$('<div class="ask-bg"></div>');
                var _askTime=$('<span class="ask-time" id="ask-time">30</span>');
                for(var i=0,an_length=issue["answer"].length;i<an_length;i++){
                    if(i==0){
                    _askBg.append('<button class="ask-l-btn" type="'+type+'" id="test_'+i+'" data-a="'+issue["answer"][i]+'" name="data-issue-'+issue["id"]+'">'+issue["answer"][i].substr(2,12)+'</button>');
                    }else{
                    _askBg.append('<button class="ask-r-btn" type="'+type+'" id="test_'+i+'" data-a="'+issue["answer"][i]+'" name="data-issue-'+issue["id"]+'">'+issue["answer"][i].substr(2,12)+'</button>');
                    }
                };
                _askBg.append(_askTime);
                $(document.body).append(_alertBg,_askBg);
                timeIndex = 30;
                _testTime = setInterval(function(){
                    if(timeIndex>=0){
                        $('#ask-time').html(timeIndex);
                        --timeIndex;
                    }else{
                        _askBg.remove();
                        _alertBg.remove();
                    }
                }, 1000);
                if(isReg){
                    var answered = false;
                    _askBg.on('click','button',function(){
                        var answer = [];
                        answer.push($(this).attr("data-a"));
                        if(answer.length){
                            answered = true;
                            data = {"id":_issueId, "answer":answer.join(',')};
                            _message.issue_answer(JSON.stringify(data));
                            clearInterval(_testTime);
                            timeIndex = 0;
                            _askBg.remove();
                            _alertBg.remove();
                        }
                    });
                }
            } else {
            var _courseTitle= _courseTest.find("#course_title");
            var dom = _courseTest.find("#course_main");
            var _course_wait=$('<div class="course-wait" id="course_wait"><div class="fs24">等待老师公布答案</div></div>');
            dom.removeClass("course-test-c2");
            dom.empty();
            _courseTest.find("#course_result").remove();
            _courseTest.find("#course_wait").remove();
            $(".course-test-icon").remove();
            _courseTest.find("#every_time").remove();
            _courseTest.find(".course-test-btn-sub").remove();
            var selectType = "单选";
            if(2 === issue["qType"] || 11 === issue["qType"]){
                selectType = "多选";
            }
            if(12 === issue["qType"]){
                selectType = "判断";
            }
            if(13 === issue["qType"]){
                selectType = "填空";
            }
            if (issue["qType"]===10 || issue["qType"]===11 || issue["qType"]===12 || issue["qType"]===13){
                dom.addClass('course-test-c2');
            }
            dom.append('<div class="select-type">【'+selectType+'】</div>');
            var div;
            if(13 != issue["qType"]){
                div = $('<div class="title">请选择你的答案</div>');
            } else {
                div = $('<div class="title">请在下方输入你的答案</div>');
            }
            if("text" in issue){
                if(issue["text"].length>0){
                    div.text(issue["text"]);
                }
                dom.append(div);
            }else{
                div.append('<div class="title-img"><img src="'+filecdn+issue["img"]+'"/></div>');
                dom.append(div);
            }
            var ul = $("<ul></ul>");
            if (issue["qType"]===13){
                ul.append('<li type="'+type+'" id="test_fill"><input id="fill" placeholder="请输入答案" class="course-test-input"  maxlength="20"></li>');
            }else{
                $.each(issue["answer"],function(i){
                if (issue["qType"]===10 || issue["qType"]===11 || issue["qType"]===12){
                    if(12 == issue["qType"]){
                    ul.append('<li type="'+type+'" id="test_'+i+'" data-a="'+issue["answer"][i][0]+'" name="data-issue-'+issue["id"]+'" class="judge-li"><span>'+issue["answer"][i]+'</span><i></i></li>');
                    }else{
                    ul.append('<li type="'+type+'" id="test_'+i+'" data-a="'+issue["answer"][i][0]+'" name="data-issue-'+issue["id"]+'" style="width:'+Math.round(100/issue["answer"].length)+'%"><i></i>'+issue["answer"][i]+'</li>');
                    }
                } else {
                    ul.append('<li type="'+type+'" id="test_'+i+'" data-a="'+issue["answer"][i][0]+'" name="data-issue-'+issue["id"]+'"><i></i>'+issue["answer"][i]+'</li>');
                }
                });
            }
            dom.append(ul);
            dom.append('<p id="course_sub" class="col-xs-20 tac mt10"><a href="javascript:void(0)" class="course-test-btn-sub">提交答案</a></p>');
            dom.show();
            _courseTest.show();
            _courseTitle.append('<div class="every-time" id="every_time"><span></span></div>');
            setTime();
            _testTime = setInterval(setTime, 1000);
            if(isReg){
                var answered = false;
                ul.on("click", "li", function(e){
                    if(!answered && issue["qType"] !=13){
                        _courseTest.find(".course-test-btn-sub").addClass("course-test-btn");
                    }
                    if($(this).hasClass("select")){
                        $(this).removeClass("select");
                        $(this).find("span").removeClass("select");
                    }else{
                        if($(this).attr("type")=="radio" || $(this).attr("type")=="judge"){
                            ul.find("li").removeClass("select");
                            ul.find("li span").removeClass("select");
                        }
                        $(this).addClass("select");
                        $(this).find("span").addClass("select");
                    }
                });
                _courseTest.on("click",".course-test-btn", function(e){
                    var answer = [];
                    if (issue["qType"] !=13){
                        ul.find("li").each(function(i, elem){
                            if($(this).hasClass("select")){
                                answer.push($(this).attr("data-a"));
                            }
                        });
                    }else{
                        if(!$("#fill").val()){
                            layer.msg('答案不能为空');
                            return false;
                        }
                        answer.push($("#fill").val());
                    }
                    if(answer.length){
                        answered = true;
                        data = {"id":_issueId, "answer":answer.join(',')};
                        _message.issue_answer(JSON.stringify(data));
                        _courseTest.find(".course-test-btn-sub").removeClass("course-test-btn").addClass("course-test-btn-sub");
                        ul.find("li").each(function(){
                            if($(this).attr('class')){
                                if (issue["qType"]===10 || issue["qType"]===11 || issue["qType"]===12){
                                    $(this).replaceWith('<dd class="'+$(this).attr('class')+'" style="width:'+Math.round(100/issue["answer"].length)+'%" type="'+$(this).attr('type')+'">'+$(this).html()+'</dd>');
                                }else if(issue["qType"]===13){
                                    $(this).replaceWith('<dd class="'+$(this).attr('class')+'" type="'+$(this).attr('type')+'"><input class="course-test-input" value='+$("#fill").val()+' disabled="disabled"></dd>');
                                }else{
                                    $(this).replaceWith('<dd class="'+$(this).attr('class')+'" type="'+$(this).attr('type')+'">'+$(this).html()+'</dd>');
                                }
                            }else{
                                if (issue["qType"]===10 || issue["qType"]===11 || issue["qType"]===12){
                                    $(this).replaceWith('<dd style="width:'+Math.round(100/issue["answer"].length)+'%">'+$(this).html()+'</dd>');
                                }else if(issue["qType"]===13){
                                    $(this).replaceWith('<dd type="'+$(this).attr('type')+'"><input class="course-test-input" value='+$("#fill").val()+' disabled="disabled"></dd>');
                                }else{
                                    $(this).replaceWith('<dd>'+$(this).html()+'</dd>');
                                }
                            }
                        });
                        clearInterval(_testTime);
                        timeIndex = 0;
                    }
                    $("#course_main").hide();
                    _courseTest.find("#course_wait").remove();
                    _course_wait.find("div").append('<p class="fs14">我提交的答案：<span class="green">'+answer+'</span></p>');
                    _courseTest.append(_course_wait);
                });
            }
            _courseTest.on("click",".course-test-close",function(){
                _courseTest.hide();
                $('.course-test-icon').remove();
                var _courseIco=$('<div class="course-test-icon">题</div>');
                _courseTest.after(_courseIco);
                _courseIco.click(function(){
                    $(this).remove();
                     _courseTest.show();
                })
            });
            $("#fill").on("input",function(){
                var txt=$(this).val();
                if ($.trim(txt)==""){
                    _courseTest.find(".course-test-btn").removeClass("course-test-btn");
                }else{
                    _courseTest.find(".course-test-btn-sub").addClass("course-test-btn");
                }
            });
            if(!$.support.opacity){
                $('#fill').on('blur',function(event){
                    $(this).trigger('input');
                });
                $('#fill').on('keyup',function(event){
                    $(this).trigger('input');
                });
            }

            }
        }

    }
    function dealIssuePublish(content){
        if(!_issueId){
            return;
        }
        var issue;
        try{
            issue = JSON.parse(content);
        }catch(e){
            return;
        }
        if("id" in issue && "answer" in issue && "rank" in issue && "unanswer" in issue){
            if(_issueId != issue["id"]){
                return;
            }
            _courseResult=$('<div class="course-test-c" id="course_result"></div>');
            var _courseResultView=$('<p class="course-view" id="course_view">查看题目></p>');
            var _courseResultView2=$('<p class="course-view" id="course_view_rank">查看排行></p>');
            _courseTest.find("#course_main").append(_courseResultView2);
            _courseTest.find('.course-test-btn-sub,#course_wait').remove();
            _courseTest.find("#course_main").hide();
            clearInterval(_testTime);
            timeIndex = 0;
            _courseTest.find("#every_time").remove();
            var index = -1;
            var currusername,curruserduration ;
            for(var i in issue["rank"]){
                if(userId == issue["rank"][i]["id"]){
                    index = i;
                    currusername = issue.rank[i].name;
                    curruserduration = issue.rank[i].duration;
                    break;
                }
            }
            var currindex = 0;
            var ask_type=$('#course_main dd').attr('type');
            if (ask_type=='judge'){
                if (issue.answer=='A'){
                    issue.answer='对';
                }else if(issue.answer=='B'){
                    issue.answer='错';
                }
            }
            if(index >= 0){
                currindex=Number(index)+1;
                var _domdl = $('<div class="col-sm-12 result-list"></div>');
                if(index < 3){
                                        _domdl.append('<p class="issue-ok"><i></i><span class="fs24">恭喜你答对了</span></p>');
                    //_domdl.append('恭喜你获得了第'+ currindex +'名 正确答案为'+issue.answer.toUpperCase()+'');
                                        _courseResult.append(_domdl);
                }else{
                                        _domdl.append('<p class="issue-ok"><i></i><span class="fs24">恭喜你答对了</span></p>');
                                        _courseResult.append(_domdl);
                    //_courseTest.find("#test_tips_text").html('恭喜你答对了  正确答案为'+issue.answer.toUpperCase()+'');
                    var others = [];
                    if(3 == index){
                        if(issue.rank.length > 5){
                            others = [4, 5];
                        }else if(issue.rank.length > 4){
                            others = [4];
                        }
                    }else if(issue.rank.length-1 == index){
                        if(issue.rank.length > 5){
                            others = [issue.rank.length-3, issue.rank.length-2];
                        }else if(issue.rank.length > 4){
                            others = [issue.rank.length-2];
                        }
                    }else{
                        others = [index-1, parseInt(index)+1];
                    }
                }
            }else{
                if (issue["qType"] ==12){
                    if (issue.answer=='A'){
                        issue.answer='对';
                    }else if(issue.answer=='B'){
                        issue.answer='错';
                    }
                }
                index = -1;
                currindex = -1;
                for(var i in issue.unanswer){
                    if(userId == issue.unanswer[i]["id"]){
                        index = i;
                        break;
                    }
                }
                var _domdl=$('<div class="col-sm-12 result-list"></div>');
                if(index >= 0){     //未回答
                                    _domdl.append('<p class="issue-no"><i></i><span class="fs24">很遗憾,时间到了</span></p>');
                    if (issue["qType"] !=13){
                    _domdl.append('<p class="result-tips">正确答案：<span class="green">'+issue.answer.toUpperCase()+'</span></p>');
                    }else{
                    _domdl.append('<p class="result-tips">正确答案：<span class="green">'+issue.answer+'</span></p>');
                    }
                }else{      //答错了
                    _domdl.append('<p class="issue-no"><i></i><span class="fs24">很遗憾,你答错了</span></p>');
                    if (issue["qType"] !=13){
                    _domdl.append('<p class="result-tips">正确答案：<span class="green">'+issue.answer.toUpperCase()+'</span></p>');
                    }else{
                    _domdl.append('<p class="result-tips">正确答案：<span class="green">'+issue.answer+'</span></p>');
                    }
                }
                _courseResult.append(_domdl);
            }
            if (issue["qType"] === 1 || issue["qType"] === 2){
                _courseResult.find('.result-list').append(_courseResultView);
            }
            var dl2 = $('<dl class="col-sm-8 result-rank"></dl>');
            dl2.append('<dt class="fs16">本题排行榜</dt>');
            if(issue.rank.length >0 ){
                if(currindex > 0){
                    if(index < 3){
                        for(var i=0;i<3&&i<issue.rank.length;i++){
                            dl2.append('<dd>第'+(i+1)+'名 '+issue.rank[i].name+'<span class="cGray">'+issue.rank[i].duration+'秒</span></dd>');
                        }
                    }else{
                        for(var i=0;i<2&&i<issue.rank.length;i++){
                            dl2.append('<dd>第'+(i+1)+'名 '+issue.rank[i].name+'<span class="cGray">'+issue.rank[i].duration+'秒</span></dd>');
                        }
                        dl2.append('<dd class="dott fs24">...</dd><dd>第'+currindex+'名'+currusername+'<span class="cGray">'+curruserduration+'秒</span></dd>');
                    }
                }else{
                        for(var i=0;i<3&&i<issue.rank.length;i++){
                            dl2.append('<dd>第'+(i+1)+'名 '+issue.rank[i].name+'<span class="cGray">'+issue.rank[i].duration+'秒</span></dd>');
                        }
                }

            } else {
                dl2.append('<dd>这道题太难了，竟然没有人答对呢！</dd>');
            }
            _courseResult.append(dl2);
            _courseTest.find("#course_main").after(_courseResult);
            _courseTestTimer = setTimeout(function(){
                _courseTest.hide();
                _courseTestTimer = null;
            }, 30000);
            _issueId = 0;
            _courseTest.on("click","#course_view",function(){
                _courseTest.find("#course_result").hide();
                _courseTest.find("#course_main").show();
            });
            _courseTest.on("click","#course_view_rank",function(){
                _courseTest.find("#course_result").show();
                _courseTest.find("#course_main").hide();
            });
        }
    }
    function dealIssueCancel(content){
        if(!_issueId){
            return;
        }
        var issue;
        try{
            issue = JSON.parse(content);
        }catch(e){
            return;
        }
        if("id" in issue){
            if(_issueId !=issue["id"]){
                return;
            }
            _courseTest.find("#course_main").empty();
            _courseTest.hide();
            $('.course-test-icon').remove();
            _issueId = 0;

        }
    }
    function checksort(sort){
        var endinggoodlist = $("#endinggoodlist");
        var name = $("#good_list li").eq(sort).find(".name").html();
        var img = $("#good_list li").eq(sort).find(".face img").attr("src");
        var typeimg = typeof(img);
        var typename = typeof(name);
        if(typeimg=="string"||typename=="string"){
                        var dom = $('<li><i class="top-'+(sort+1)+'">'+(sort+1)+'</i><span class="face"><img src="'+img+'"></span><span class="name">'+name+'</span></li>');
            endinggoodlist.append(dom);
        }
    };
    // 退出 fullscreen
    function exitFullscreen() {
      if(document.exitFullscreen) {
        document.exitFullscreen();
      } else if(document.mozExitFullScreen) {
        document.mozExitFullScreen();
      } else if(document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      }
    }

    function endingcallback(){
        $("#courseing").hide();
        $("#courseingend").show();
        $("#sectionc a[plan_id="+window.planId+"]").removeClass('playing').removeClass('living');
        checkUserIsComment('living');

        for(var i=0;i<=2;i++){
            checksort(i);
        }
        var url = "/exam.log.loguserques";
        var request = {};
        request["user_id"] = userId;
        request["plan_id"] = planId;
        var successCallback = function(r){
            $("#countAll").text(r.all);
            $("#countCorrect").text(r.correct);
            $("#countMistake").text(r.mistake);
            $("#countMiss").text(r.miss);
            // 调用退出全屏方法!
            exitFullscreen();
        };

        $.post(url, request, successCallback, "json");
    }

    function checkUserIsComment(type) {
        $.post('/comment/course/CheckISAddScore', {course_id: courseId, plan_id: planId},
            function (d) {
                if (type == 'living') {
                    if (d.result.code == 0) {
                        // 已评论
                        setComment(d.result.data.score, d.result.data.comment, d.section, d.result.data.create_time);
                        $('#ret_score_comment').show();
                        $('#ret_textComment').show();
                    } else {
                        $('#score_comment').show();
                        $('#textComment').show();
                    }
                }

                if (type == 'finished') {
                    if (d.result.code == 0) {
                        // 已评论：0  未评论：101
                        setComment1(d.result.data.score, d.result.data.comment, d.section, d.result.data.create_time);
                        $('#ret_score_comment1').show();
                        $('#ret_textComment1').show();
                    } else {
                        $('#score_comment1').show();
                        $('#textComment1').show();
                    }
                }
            }, 'json'
        );
    }
    //课堂公告 jinye 2016-10-24
    function htmlEncode(str){
         var s = "";
         if(str.length == 0) return "";
         s = str.replace(/&/g,"&amp;");
         s = s.replace(/</g,"&lt;");
         s = s.replace(/>/g,"&gt;");
         s = s.replace(/ /g,"&nbsp;");
         s = s.replace(/\'/g,"&#39;");
         s = s.replace(/\"/g,"&quot;");
         return s;
    }
    var Notice = {
        nodeNotice:$('#notice'),
        nodeChat:$('#chat'),
        nodeContent:$('#notice-content'),
        _add:function(text){
            if(!text){
                this._remove();
            }else{
                var s = htmlEncode(text).replace(/\r?\n/g,"<br />").replace(/\s/g,"&nbsp;");
                this.nodeContent.html(s);
                this.nodeChat.addClass('hasNotice');
                this.nodeNotice.addClass('active');
            }
        },
        _remove:function(){
            this.nodeChat.removeClass('hasNotice');
            this.nodeNotice.removeClass('active');
            this.nodeContent.html('');
        },
        _edit:function(text){
            this._add(text);
        },
        //_ask:function(){
        //    var _self = this;
        //    $.ajax({
        //        url: '/course/announcement/GetAnnouncement',
        //        type: 'POST',
        //        dataType: 'json',
        //        data:{
        //            fk_plan:planId
        //        }
        //    })
        //    .done(function(res) {
        //        if(res.code == 200){
        //            if(res.data.length>0){
        //                res.data[0].content&&_self._add(res.data[0].content);
        //            }
        //        }
        //    });
        //},
        init:function(){
            //this._ask();
            $('.notice-btn').click(function(){
                $('#notice').toggleClass('active');
            });
        }
    };
    Notice.init();
    //课堂公告 end
    function voiceTimeout(a, b){
        if(_voiceNum > 0){
            var ret = null;
            try{
                ret = Player.showSpeakingTips(a, b);
            }catch(e){
            }
            if(ret){
                _voiceTimerId = null;
                if(_cameraOpen){
                    _cameraOpen = false;
                    Player.startupCameraPlayer();
                }
            }else{
                _voiceNum--;
                _voiceTimerId = setTimeout(function(){voiceTimeout(a,b);}, 2000);
            }
        }else{
            _voiceTimerId = null;
        }
    }
    function msgDeal(data){
        if(!data){
            if(0 == _partialText.accu){
                inputScroll(true);
            }else if(1 == _partialTextDirection){
                inputScroll(1);
            }else{
                inputScroll(false);
            }
            partialTextDeal();
            _oldFlag = false;
            return;
        }
        var u = parseInt(data.user_from_id);
        var plan_id = data.plan_id;
        if(MessageType.text == data.type){
            partialTextOnce(data);
            /*if(!_oldFlag){
                textResponse(data, _partialText._id);
            }*/
        }else if(MessageType.agree_refuse == data.type){
            // h5 打开app 提示
            //if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            //    if(isLogin && !isSign){
            //        web2app && !$.cookie(web2app.typeConfig['answer']) && web2app.show('answer');
            //    }
            //}
            cancelAskTimeout();
            if("agree" == data.content){
                _speakUser = data.ut;
                _speakToken = data.uft;
                if(isReg && data.user_to_id == userId && data.user_to_token == userFlag){
                    var ask = $("#ask");
                    var a = ask.attr("ask");
                    if("ask" == a){
                        console.log("agree for ask\n");
                    }else if("cancel" == a){
                        Player.hideSpeakingTips();
                        try{
                            Player.record();
                        }catch(e){
                            console.log("Player.record error=["+e+"]\n");
                        }
                        ask.attr("ask", "stop");
                        ask.children().each(function(){
                            displayOne($(this), "ask", "stop");
                        });
                    }else{
                        console.log("agree for stop\n");
                    }
                } else {
                    var headImg = location.protocol+filecdn+data.uf_t;
                    var ret = null;
                    try{
                        ret = Player.showSpeakingTips(data.ut_n, headImg);
                    }catch(e){
                    }
                    if(!ret){
                        _voiceTimerId = setTimeout(function(){voiceTimeout(data.ut_n, headImg);}, 2000);
                    }
                }
                sayAsking(teacherName, data.ut, data.ut_n, "同意");
            }else if("refuse" == data.content || "stop" == data.content){
                if(_speakUser == data.ut && _speakToken == data.uft){
                    _speakUser = 0;
                    _speakToken = "";
                    //if(!_oldFlag)
					{
                        if(_voiceTimerId){
                            clearTimeout(_voiceTimerId);
                            _voiceTimerId = null;
                        }
                        Player.hideSpeakingTips();
                        if(isReg && data.user_to_id == userId && data.user_to_token == userFlag){
                            var ask = $("#ask");
                            var a = ask.attr("ask");
                            if("ask" == a){
                                console.log("type=["+data.type+"] for ask");
                            }else{
                                if("stop" == a){
                                    Player.stopRecord();
                                }
                                ask.attr("ask", "ask");
                                ask.children().each(function(){
                                    displayOne($(this), "ask", "ask");
                                });
                            }
                        }
                    }
                }
            }else if("asking" == data.content){
                _speakUser = data.ut;
                _speakToken = data.uft;
                if(isReg && data.user_to_id == userId && data.user_to_token == userFlag){
                    var ask = $("#ask");
                    var a = ask.attr("ask");
                    if("stop" != a){
                        try{
                            Player.record();
                        }catch(e){
                            console.log("Player.record error=["+e+"]\n");
                        }
                        ask.attr("ask", "stop");
                        ask.children().each(function(){
                            displayOne($(this), "ask", "stop");
                        });
                    }
                } else {
                    var headImg = location.protocol+filecdn+data.ut_t;
                    var ret = null;
                    try{
                        ret = Player.showSpeakingTips(data.ut_n, headImg);
                    }catch(e){
                    }
                    if(!ret){
                        _voiceTimerId = setTimeout(function(){voiceTimeout(data.ut_n, headImg);}, 2000);
                    }
                }
                sayAsking(teacherName, data.ut, data.ut_n, "要求");
            }
        }else if(MessageType.ask_cancel == data.type){
            if(isReg && data.uf == userId && data.uff == userFlag && "ask" == data.c){
                var ask = $("#ask");
                ask.attr("ask", "cancel");
                ask.children().each(function(){
                    displayOne($(this), "ask", "cancel");
                });
                startsetAskTimeout();
                console.log('stop ask');
                // _askTimerId = setTimeout(function(){
                //     $("#ask").find("[ask=ask]").show();
                //     $("#ask").find("[ask=cancel]").hide();
                //     $("#ask").find("[ask=stop]").hide();
                //     _message.cancel();
                //     //alert('老师没有同意,举手自动取消');
                // }, 60000);
            }
            if("cancel" == data.c){
                if(_speakUser == data.uf && _speakToken == data.uff){
                    _speakUser = 0;
                    _speakToken = "";
                    if(_voiceTimerId){
                        clearTimeout(_voiceTimerId);
                        _voiceTimerId = null;
                    }
                    if(0 == data.user_to_id || (data.user_to_id == userId && data.user_to_token == userFlag)){
                        Player.hideSpeakingTips();
                    }
                }
            }
        }else if(MessageType.call == data.type){
            cancelAskTimeout();
            if(isReg && (0 == data.user_to_id || (data.user_to_id == userId && data.user_to_token == userFlag))){
                Player.quitFullScreen();
                $("#call_pop").show();
                _callId = u;
            }
        }else if(MessageType.good == data.type){
            var u2 = data.ut;
            goodResponse(data, false);
            if(!_oldFlag){
                sayGood(teacherName, u2, data.ut_n);
            }
        }else if(MessageType.on_off_line == data.type){
            if(u != userId || data.uff != userFlag){
                if("online" == data.c){
                    _onlineNum++;
                    if(groupId > 0){
                        if(u in _groupUids){
                            _groupOnlineNum++;
                        }
                    }
                }else if("offline" == data.c){
                    //if(!_oldFlag)
					{
                        _onlineNum--;
                        if(groupId > 0){
                            if(u in _groupUids){
                                _groupOnlineNum--;
                            }
                        }
                    }
                }
                setOnlineNum();
                if(groupId > 0 &&  u in _groupUids){
                    setGroupOnlineNum();
                }
            }
        }else if(MessageType.start_close == data.type){
            if("start" == data.content){
                $("#coursebefore").hide();
                $("#courseingend").hide();
                $("#courseing").show();
                $("#playerContent").show();
                $("#live-status").removeClass('live-status-live').addClass("live-status-play").html("正在直播").attr('data-status','living');
                isLiving = true;
                $("#note-c").removeClass('unlive');
                $("#section").find("[plan_id=" + planId + "]").removeClass('playing').addClass('living');
                window.PlayerNote.getListData();
                $("#sectionc").find('a[plan_id='+plan_id+'] p').removeClass('play-back-status').addClass('play-living-status');
                _videoEnd = false;
                try{Player.close();}catch(e){}
                try{Player.reInit();}catch(e){}
                startClass(_notalkFlag);
                setTimeout(function(){_videoEnd=true;}, 1000);
            }else if("close" == data.content){
                $("#live-status").removeClass('live-status-play').addClass("live-status-live").html("课程回放").attr('data-status','finished');
                isLiving = false;
                $("#sectionc").find('a[plan_id='+plan_id+'] p').removeClass('play-living-status').addClass('play-back-status');
                //try{Player.close();}catch(e){}
                stopClass();
                if(!_oldFlag){
                //endingcallback();
                }
            }
        }else if(MessageType.pattern == data.type){
            if("normal" == data.content){
                _pattern_reply.hide();
                _pattern_notalk.hide();
                _notalkFlag = false;
                _isPatternReply = false;
                if(!_notalkFlag){
                    if(isReg){
                        //if(!_oldFlag)
						{
                            chatNotalk(false);
                            $("#cantalk").show();
                            $("#ask").find("[ask=ask]").show();
                        }
                    }
                }
                replyRid();
            }else if("reply" == data.content){
                _pattern_reply.show();
                _pattern_notalk.hide();
                _notalkFlag = false;
                _isPatternReply = true;
                if(!_notalkFlag){
                    if(isReg){
                        //if(!_oldFlag)
						{
                            chatNotalk(false);
                            $("#cantalk").show();
                        }
                    }
                }
            }else if("notalk" == data.content){
                _pattern_reply.hide();
                _pattern_notalk.show();
                _notalkFlag = true;
                _isPatternReply = false;
                if(isReg){
                    chatNotalk(true);
                    $("#cantalk").hide();
                    $("#ask").find("[ask=ask]").hide();
                }
                replyRid();
            }
        }else if(MessageType.score_info == data.type) {
            if (userId == data.user_to_id) {
                var res = JSON.parse(data.content);
                if (res.code == 0) {
                    var sign_add = "<span id='sign_add' class='sign-add'></span>";
                    $("#tool").append(sign_add);
                    $("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.data.add_score);
                    $("#sign_add").show(function () {
                        $("#sign_add").animate({top: "40%", opactiy: "1"}, function () {
                            $("#sign_add").hide().animate({top: "35%"});
                        });
                    });

                    if (res.data.up_type) {
                        var types = '';
                        if (res.data.up_type == 2) {
                            types = 'biggrowth';
                        }

                        if (res.data.up_type == 1) {
                            types = 'smallgrowth';
                        }
                        $("body").GrowthLayer({
                            types: types,// five|smallgrowth|biggrowth
                            space: 5000, //时间间隔
                            auto: true, //自动关闭
                            growth: res.data.fk_level,// 等级,1,2,3
                            score: res.data.score
                        });
                    }
                }
            }
        }else if(MessageType.camera == data.type) {
            if(_oldFlag && "close" == data.c){
                return;
            }
            if("open" == data.c){
                if(_voiceTimerId){
                    _cameraOpen = true;
                }else{
                    Player.startupCameraPlayer();
                    _cameraOpen = false;
                }
            }else{
                Player.closeCameraPlayer();
                _cameraOpen = false;
            }
        }else if(MessageType.reply_text == data.type){
            partialTextOnce(data);
            /*if(!_oldFlag){
                if(userId == data.user_from_id){
                    textResponse(data, _partialText._id);
                }else{
                    if(_oldFlag || _isPatternReply){
                        replyTextResponse(data, _partialText._id);
                    }else{
                        textResponse(data, _partialText._id);
                    }
                }
            }*/
        }else if(MessageType.microphone_test == data.type){
            if(isReg && (0 == data.user_to_id || (userId == data.user_to_id && userFlag == data.user_to_token))){
                Player.testMicrophone(true);
            }
        }else if(MessageType.issue_ask == data.type){
            // h5 打开app 提示
            //if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            //    if(isLogin && !isSign){
            //        web2app && !$.cookie(web2app.typeConfig['exam']) && web2app.show('exam');
            //    }
            //}
            /*if(_oldFlag){
                var datafromtime = data.last_updated;
                datafromtime = datafromtime.replace(/-/g,'/');
                var datafromstr = Date.parse(new Date(datafromtime));
                var datathisstr = Date.parse(new Date())
                //如果下课已经俩小时了还有没回答的问题就不显示了
                if(datathisstr-datafromstr>=600000){
                        return ;
                }
            }*/
            dealIssueAsk(data.content);
        }else if(MessageType.issue_publish == data.type){
            dealIssuePublish(data.content);
        }else if(MessageType.issue_cancel == data.type){
            dealIssueCancel(data.content);
        }else if(MessageType.modify_student == data.type){
            if("add" == data.content){
                setTotalModify(1);
                setOnlineNum();
                setGoodNum();
            }else if("delete" == data.content){
                setTotalModify(-1);
                setOnlineNum();
                $("#good_list").find("li[userid="+data.ut+"]").remove();
                setGoodNum();
            }else{
                console.log("!!!error for modify student content=["+data.content+"]");
            }
        }else if(MessageType.request_eval == data.type){
            if("stop" == data.content){
                endingcallback();
            }
        }else if(MessageType.delete_text == data.type){
            if(_chat_list_cache.length){
                _deleteTextList.push(data.content);
            }else{
                deleteText(data.content);
            }
            _textList._delete(data.content);
        }else if(MessageType.single_notalk == data.type){
            if(data.user_to_id ==userId){
                if("forbid" == data.content){
                    _singlenotalkFlag = true;
                    if(isReg){
                        //if(!_oldFlag)
						{
                            chatNotalk(true);
                            $("#cantalk").hide();
                            sayforbid(teacherName, data.user_to_id, data.ut_n, true);
                            $("#chat_input").html('<img src="/assets_v2/img/gag.png">你已被老师禁言');
                            $("#ask").find("[ask=ask]").hide();
                        }
                    }
                }else{
                    _singlenotalkFlag = false;
                    if(!_singlenotalkFlag){
                        if(isReg){
                            //if(!_oldFlag)
							{
                                chatNotalk(false);
                                $("#cantalk").show();
                                sayforbid(teacherName, data.user_to_id, data.ut_n, false);
                                $("#chat_input").html("");
                                $("#ask").find("[ask=ask]").show();
                            }
                        }
                    }
                }
            }
        }else if(MessageType.notice == data.type){
            if(!data.content){
                Notice._remove();
            }else{
                Notice._edit(data.content);
            }
        }else{
            ;
        }
    }
    function setOnlineNum(){
        if(_studentTotal.length > 0){
            if(_onlineNum >= 1){
                _studentTotal.find("b[online]").text(_onlineNum);
                $('.student_total').find("b[online]").text(_onlineNum);
            }
        }
    }
    function setGroupOnlineNum(){
        if(_studentGroup.length > 0){
            if(_groupOnlineNum >= 1){
                _studentGroup.find("b[online]").text(_groupOnlineNum);
                $('.student_group').find("b[online]").text(_groupOnlineNum);
            }
        }
    }
    var askTimer = '';
    var askTimerLimit = 60;
    var askTimerSec = askTimerLimit;
    var askTimerStatus = false;
    function startsetAskTimeout(){
        if(!askTimerStatus){
            askTimerStatus = true;
            setAskTimeout();
        }
    }
    function setAskTimeout(){
        var ask = $("#ask");
        if(!$("#askTime")[0]){
            ask.append($("<span id='askTime' style='padding-left:10px'>"+askTimerLimit+"s</span>"));
        }
        $("#askTime").show();
        askTimer = setTimeout(function(){
            if(askTimerSec<=0){
                ask_status();
            }else{
                $("#askTime").text(askTimerSec+'s');
                askTimerSec--;
                askTimerSec = askTimerSec<10?'0'+askTimerSec:askTimerSec;
                setAskTimeout();
            }
        },1000);
    }
    function cancelAskTimeout(){
        askTimerStatus = false;
        clearTimeout(askTimer);
        $("#askTime").hide().text('0s');
        askTimerSec = askTimerLimit;
    }
    $('#ask [ask="cancel"]').hover(function(){
        $(this).text('取消发言');
    },function(){
        $(this).text('等待发言');
    });
    function ask_status(){
        var ask = $("#ask");
        if("ask" == ask.attr("ask") && ask.find("[ask=ask]").hasClass("gray")){
            return false;
        }
        var a = ask.attr("ask");
        var n;
        var flag = false;
        if("ask" == a){
            _message.ask(); n="cancel";
            startsetAskTimeout();
        }else if("cancel" == a){
            cancelAskTimeout();
            _message.cancel(); n="ask";
        }else{
            cancelAskTimeout();
            _message.cancel(); n="ask";
            flag = true;
        }
        ask.attr("ask", n);
        ask.children().each(function(){
            displayOne($(this), "ask", n);
        });
        return flag;
    }
    _message = new Message(planId, userId, Player, chatWs, chatPull, chatPullSet, msgDeal, null);
    _message.getLiveTexts = function(start, duration){
        return _textList.getLiveTexts(start, duration);
    }
    _message.flash_callback = function(data){
        if(!isReg){
            return;
        }
        var flag = false;
        for(var k in data){
            if("exist" == k){
                var p = $("#ask");
                var a = p.attr("ask");
                var ask = p.find("[ask="+a+"]");
                if(data[k]){
                    ask.show();
                    ask.removeClass("gray");
                    ask.children().each(function(){
                        $(this).removeClass("gray");
                    });
                }else{
                    ask.hide();
                    ask.addClass("gray");
                    ask.children().each(function(){
                        $(this).addClass("gray");
                    });
                    flag = true;
                }
            }else if("connected" == k){
                if(!data[k]){
                    flag = true;
                }
            }else if("recorded" == k){
                if(!data[k]){
                    flag = true;
                }
            }else{
                console.log("k=["+k+"] v=["+data[k]+"], unimplemented!\n");
            }
        }
        if("stop" == $("#ask").attr("ask") && flag){
            ask_status();
            cancelAskTimeout();
        }
    }
    _message.microphone_callback = function(flag){
        if(!isReg){
            return;
        }
        if(flag){
            _message.microphone_succeed();
        }else{
            _message.microphone_fail();
        }
    }

    _message.flash_camera_handle = function(flag){
        if (!isReg) {
            return;
        }
        if (flag) {
            _message.cameraOpen();
        } else {
            _message.cameraClose();
        }
    }
    _message.stopRecord = function(){
        _message.cancel();
    }
    _message.flash_fullscreen_callback = function(flag){
        if(!isReg){
            return;
        }
        if(flag){
            _message.fullscreen(2);
        }else{
            _message.fullscreen(3);
        }
    }
    _message.flash_buy_box_callback = function(data){
        var publicType = parseInt(data["publicType"]);
        if(0 == publicType){
            var dom = $("#login_remind");
            dom.find("[data-class=title]").text(data["title"]);
            //dom.find("[data-class=teacher]").text(data["teacher"]);
            dom.find("[data-class=teacher]").text(teacherName);
            dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
            dom.show();
        }else if(1 == publicType){
            var dom = $("#fee_remind");
            dom.find("[data-class=fee]").text("￥"+data["price"]);
            dom.find("[data-class=title]").text(data["title"]);
            //dom.find("[data-class=teacher]").text(data["teacher"]);
            dom.find("[data-class=teacher]").text(teacherName);
            dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
            dom.find("[data-class=href]").attr("href", "/course.info.show/"+data["id"]);
            dom.show();
        }else{
            var dom = $("#sign_remind");
            dom.find("[data-class=title]").text(data["title"]);
            //dom.find("[data-class=teacher]").text(data["teacher"]);
            dom.find("[data-class=teacher]").text(teacherName);
            dom.find("[data-class=thumb]").attr("src", data["thumMed"]);
            dom.find("[data-class=href]").attr("href", "/course.info.show/"+data["id"]);
            dom.show();
        }
    }
    //录播课结束
    _message.flash_end_callback = function (data) {
        if(!_videoEnd){
            return;
        }
        $("#courseing").hide();
        $("#courseend").show();

        var now = $("#sectionc a[plan_id="+window.planId+"]");
        now.removeClass('playing').removeClass('living');;
        var next = now.next();
        var next_v = now.next().find(".sicon-2");
        var prev = now.prev();
        if(next_v.size()){
            var id = $(next).attr("plan_id");
            $("#nextPlan").attr("href","/course.plan.play/"+id);
        }else if(prev.size()){
            var id = $(prev).attr("plan_id");
            $("#nextPlan").hide();
        }else{
            $("#nextPlan").hide();
        }

        checkUserIsComment('finished');
    };
    // 直播课结束
    _message.flash_ending_callback = function(data){
        $("#courseing").hide();
        $("#courseingend").show();
        $("#sectionc a[plan_id="+window.planId+"]").removeClass('playing').removeClass('living');;
        //$.get("/course/ajax/GetNextPlanId", { planId: planId, },function(data){
            //alert("Data Loaded: " + data);
        //});
        checkUserIsComment('living');
        for(var i=0;i<=2;i++){
            checksort(i);
        }
        var url = "/exam.log.loguserques";
        var request = {};
        request["user_id"] = userId;
        request["plan_id"] = planId;
        var successCallback = function(r){
            //console.log("answer=["+JSON.stringify(r)+"]");
            $("#countAll").text(r.all);
            $("#countCorrect").text(r.correct);
            $("#countMistake").text(r.mistake);
            $("#countMiss").text(r.miss);
        }
    //  $.post(this.url, request, successCallback, "json").error(errorCallback);
        $.post(url, request, successCallback, "json");

    }
    function checksort(sort){
        if(_endsort){
            return;
        }
        var endinggoodlist = $("#endinggoodlist");
        var name = $("#good_list li").eq(sort).find(".name").html();
        var img = $("#good_list li").eq(sort).find(".face img").attr("src");
        var typeimg = typeof(img);
        var typename = typeof(name);
        if(typeimg=="string"||typename=="string"){
                        var dom = $('<li><i class="top-'+(sort+1)+'">'+(sort+1)+'</i><span class="face"><img src="'+img+'"></span><span class="name">'+name+'</span></li>');
            endinggoodlist.append(dom);
        }
        if(sort==2){
            _endsort = true;
        }
    };

    $("#ask").click(function(e){
        if(isReg){
            var flag = ask_status();
            if(flag){
                Player.stopRecord();
            }
        }
    });
    $("#call_reply").click(function(e){
        _message.reply(_callId);
        $("#call_pop").hide();
    });
    function webFullscreenChange(e){
        var isFull = isFullScreen();
        if(isFull){
            _message.fullscreen(1);
        }else{
            _message.fullscreen(3);
        }
    }
    function initFullscreen(){
        var isFull = isFullScreen();
        if(isFull){
            _message.fullscreen(1);
        }
        var info = Player.info();
        if(info.fullscreen){
            _message.fullscreen(2);
        }
        _message.fullscreen(3);
    }
    if(isReg){
        $(document).on("fullscreenchange", webFullscreenChange);
        $(document).on("mozfullscreenchange", webFullscreenChange);
        $(document).on("webkitfullscreenchange", webFullscreenChange);
        $(document).on("MSfullscreenchange", webFullscreenChange);
        //initFullscreen();
    }
    /*window.onbeforeunload = function(e){
        _message.offline();
    }*/
    return _message;
            function setTime(){
                var hour = parseInt(timeIndex / 3600);    // 计算时
                var minutes = parseInt((timeIndex % 3600) / 60);    // 计算分
                var seconds = parseInt(timeIndex % 60);    // 计算秒
                hour = hour < 10 ? "0" + hour : hour;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                $("#every_time").html('<span></span>您已用时:' + minutes + '分' + seconds +'秒');
                timeIndex++;
            }
}
