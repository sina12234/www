// 功能模块文件

// chat 聊天模块
function ChatList(mainDom, number, step, moveCallback){
    if(mainDom && !mainDom.length){
        mainDom = null;
    }
    this._mainDom = mainDom;
    this._number = number;
    this._step = step;
    this._moveCallback = moveCallback;
    this._groups = {};
    this._div = null;
    this._news = null;
    this.newsNum = 0;
    if(mainDom){
        this._mainDisplay = new DisplayOptimize(mainDom, number, step, moveCallback);
    }
}
ChatList.prototype.addGroup = function(gid, dom, uids){
    if(gid in this._groups){
        return;
    }
    var a = {};
    a["dom"] = dom;
    a["uids"] = uids;
    a["display"] = new DisplayOptimize(dom, this._number, this._step, this._moveCallback);
    a["newsNum"] = 0;
    this._groups[gid] = a;
}
ChatList.prototype.setNews = function(dom){
    this._news = dom;
}
ChatList.prototype.modifyNewsNum = function(uid, num){
    uid = parseInt(uid);
    this.newsNum += num;
    if(uid){
        for(var gid in this._groups){
            var a = this._groups[gid];
            if(uid in a["uids"]){
                a["newsNum"] += num;
            }
        }
    }
}
ChatList.prototype.showNews = function(gid){
    var num = 0;
    if(gid){
        num = this._groups[gid].newsNum;
    }else{
        num = this.newsNum;
    }
    if(num > 0){
        var spanValue = "" + num + "条新消息";
        if(num > 99){
            spanValue = "99+条新消息";
        }
        this._news.find("div[class=chat-c]").text(spanValue);
        this._news.show();
    }
}
ChatList.prototype.switchNews = function(gid){
    this._news.hide();
    this.showNews(gid);
}
ChatList.prototype.isToEnd = function(gid){
    var dom = this._mainDom;
    if(gid){
        dom = this._groups[gid]["dom"];
    }
    if(dom.scrollTop() + 120 >= dom[0].scrollHeight - dom.height()){
        return true;
    }else{
        return false;
    }
}
ChatList.prototype.addDiv = function(){
    var dom = $("<div></div>");
    this._div = dom;
    if(this._mainDom){
        this._mainDisplay.prepend(dom);
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        a["div"] = dom.clone();
        a["display"].prepend(a["div"]);
    }
}
ChatList.prototype.addMore = function(){
    if(this._mainDom){
        if(this._div){
            this._mainDisplay.insert("<li data-more><p style='cursor:pointer'>更多</p></li>", this._div.index());
        }else{
            this._mainDisplay.prepend("<li data-more><p style='cursor:pointer'>更多</p></li>");
        }
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        if("div" in a){
            a["display"].insert("<li data-more><p style='cursor:pointer'>更多</p></li>", a["div"].index());
        }else{
            a["display"].prepend("<li data-more><p style='cursor:pointer'>更多</p></li>");
        }
    }
}
ChatList.prototype.deleteMore = function(){
    if(this._mainDom){
        var a = this._mainDom.children("li[data-more]");
        if(a.length){
            this._mainDisplay._delete(a);
        }
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        var b = a["dom"].children("li[data-more]");
        if(b.length){
            a["display"]._delete(b);
        }
    }
}
ChatList.prototype.append = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.append(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].append(dom.clone());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                //过滤，加内容
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].append(dom.clone());
                }
            }
        }
    }
}
ChatList.prototype.appends = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.appends(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].appends(dom.clone());
        }
    }else{
        for(var i=0;i<dom.length;i++){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].append($(dom[i]).clone());
                    }
                }
            }
        }
    }
}
ChatList.prototype.prepend = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.prepend(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].prepend(dom.clone());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].prepend(dom.clone());
                }
            }
        }
    }
}
ChatList.prototype.prepends = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.prepends(dom);
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].prepends(dom.clone());
        }
    }else{
        for(var i=dom.length-1;i>=0;i--){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].prepend($(dom[i]).clone());
                    }
                }
            }
        }
    }
}
ChatList.prototype.beforeDiv = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.insert(dom, this._div.index());
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].insert(dom.clone(), a["div"].index());
        }
    }else{
        var u = parseInt(dom.attr("data-uid"));
        if(u){
            for(var gid in this._groups){
                var a = this._groups[gid];
                if(u in a["uids"]){
                    a["display"].insert(dom.clone(), a["div"].index());
                }
            }
        }
    }
}
ChatList.prototype.beforeDivs = function(dom, groupFlag){
    if("string" == typeof(dom)){
        dom = $(dom);
    }
    if(this._mainDom){
        this._mainDisplay.inserts(dom, this._div.index());
    }
    if(groupFlag){
        for(var gid in this._groups){
            var a = this._groups[gid];
            a["display"].inserts(dom.clone(), a["div"].index());
        }
    }else{
        for(var i=0;i<dom.length;i++){
            var u = parseInt($(dom[i]).attr("data-uid"));
            if(u){
                for(var gid in this._groups){
                    var a = this._groups[gid];
                    if(u in a["uids"]){
                        a["display"].insert($(dom[i]).clone(), a["div"].index());
                    }
                }
            }
        }
    }
}
ChatList.prototype.deleteMsg = function(msg_id){
    if(this._mainDom){
        this._mainDisplay._delete(this._mainDom.find("li[data-msg="+msg_id+"]"));
    }
    for(var gid in this._groups){
        var a = this._groups[gid];
        var b = a["dom"].find("li[data-msg="+msg_id+"]");
        if(b.length){
            a["display"]._delete(b);
        }
    }
}
ChatList.prototype.move = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].move();
        }
    }else{
        this._mainDisplay.move();
    }
}
ChatList.prototype.moveStart = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].moveStart();
        }
    }else{
        this._mainDisplay.moveStart();
    }
}
ChatList.prototype.moveEnd = function(gid){
    if(gid){
        if(gid in this._groups){
            this._groups[gid]["display"].moveEnd();
            this._groups[gid]["newsNum"] = 0;
            if(this._news){
                this._news.hide();
            }
        }
    }else{
        this._mainDisplay.moveEnd();
        this.newsNum = 0;
        if(this._news){
            this._news.hide();
        }
    }
}
ChatList.prototype.showNumber = function(){
    if(this._mainDom){
        console.log("main number=["+this._mainDisplay.showNumber()+"]\n");
    }
    for(var gid in this._groups){
        console.log(gid+" number=["+this._groups[gid]["display"].showNumber()+"]");
    }
}
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
        if(!isReg){
            return false;
        }
        if(_issueId){
            return;
        }
        if(!isReg){
            return false;
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
                        if(!($(this).attr("type")=="checkbox" && $(this).siblings().hasClass('select'))){
                            _courseTest.find(".course-test-btn-sub").removeClass("course-test-btn");
                        }
                    }else{
                        if($(this).attr("type")=="radio" || $(this).attr("type")=="judge"){
                            ul.find("li").removeClass("select");
                            ul.find("li span").removeClass("select");
                        }
                        $(this).addClass("select");
                        $(this).find("span").addClass("select");
                    }
                });
                _courseTest.off('click');
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
                        if(issue["qType"] == 12){
                            answer[0] = answer[0] == 'A' ? '对':'错';
                        }
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
                // 答题加积分特效
                $.ajax({
                    url: '/user/point/AddPoint',
                    type: 'POST',
                    dataType: 'json',
                    data: {type: 'ANSWER_SHEET'}
                })
                .done(function(res) {
                    if(res.code == 0 && res.data){
                        $.expChangeEffect(res.data);
                    }
                });
                
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
    window.Notice = Notice;
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
                if(window.supportGift && typeof(GIFT) == "object" && window.isLogin && !window.isSign){
                    GIFT.receive();
                }
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
                if (typeof Relax == 'object') {
                    Relax._hide();
                }
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
                    if(res.data.add_score && res.data.add_score > 0){
                        var sign_add = "<span id='sign_add' class='sign-add'></span>";
                        $("#tool").append(sign_add);
                        $("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.data.add_score);
                        $("#sign_add").show(function () {
                            $("#sign_add").animate({top: "40%", opactiy: "1"}, function () {
                                $("#sign_add").hide().animate({top: "35%"});
                            });
                        });
                    }

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
            /*if(_oldFlag)
            {
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
        }else if(MessageType.gift == data.type){
            // console.log(data,'送花回调')
            if(window.supportGift && typeof(GIFT) == "object"){
               GIFT.messageAddMsg(data);
            }
        }else if(MessageType.recess == data.type) {
            if(isLiving){
                if (typeof Relax == 'object') {
                    if(data.c == 'over'){
                        Relax._add(-1);
                    }else{
                        Relax._add(1,data.lu,parseInt(data.c));
                    }
                }
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
    _message = new Message(planId, userId, Player, chatWs, chatPull, chatPullSet, msgDeal, addMsgErrorDeal);
    _message.getLiveTexts = function(start, duration){
        return _textList.getLiveTexts(start, duration);
    }
    function addMsgErrorDeal(data,a){
        var a = _this._meList.shift();
        if(a[6] && typeof a[6] == 'function'){
           if(a[3] == MessageType.gift){
               a[6](null, JSON.parse(data.info));
           }
        }
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

// comment 评论模块
$(document).ready(function(e) {
    var _addURL = "/comment/course/addscore";
    var _checkURL = "/comment/course/checkIsAddScore";
    var _getAscURL = "/comment.course.getcomments";
    var _getDescURL = "/comment.course.getcommentsdesc";
    var _input = $("#comment_input");
    var _input1 = $("#comment_input1");
    var _score_1 = $("#score_comment");
    var _score_11 = $("#score_comment1");
    var _score_all = $("#score_all");
    var _comment = $("#comment");
    var _comment_self = $("#comment_self");
    var _comment_list = [];
    var _comment_num = 0;
    var _leftButton = $("#comment_prev");
    var _rightButton = $("#comment_next");
    // function setAvgScore(extra){
    // 	var t = extra;
    // 	_score_1.find(".percent>dl>dd>i").each(function(i, elem){
    // 	t+=parseInt($(this).attr("data-score"));
    // 	//console.log(t);
    // 	});
    // 	t /= 3;
    // 	$("#avg_1").text(t.toFixed(1));
    // }
    // function setAvgScore1(extra){
    // 	var t = extra;
    // 	_score_11.find(".percent>dl>dd>i").each(function(i, elem){
    // 	t+=parseInt($(this).attr("data-score"));
    // 	//console.log(t);
    // 	});
    // 	t /= 3;
    // 	$("#avg_11").text(t.toFixed(1));
    // }
    function getScore(kind) {
        return _score_1.find("[score_type=" + kind + "]").attr("data-score");
    }

    function getScore1(kind) {
        return _score_11.find("[score_type=" + kind + "]").attr("data-score");
    }

    function addComment(e) {
        e.preventDefault();
        var t = $.trim(_input.text());
        //t = t.replace(/<.*?>/g, "");
        //t = t.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
        //t = t.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");
        if (!t) {
            alert("评论不能为空！");
            return;
        }
        if (t.length < 5) {
            layer.msg("评论不能少于4个字哦");
            return;
        }
        var request = {};
        request["score"] = getScore("avg_score");
        request["user_teacher"] = teacherId;
        request["plan_id"] = planId;
        request["user_owner"] = userOwner;
        for (i in request) {
            if (0 == request[i]) {
                alert(_score_1.find("[score_type=" + i + "]").prev().text() + "还没有打分呢！");
                return;
            }
        }
        request["comment"] = t;
        request["course_id"] = courseId;
        $("#comment_send").val("评论中...");
        var commentSuc = function(r) {
            res = JSON.parse(r);
            if (res.code == 1) {
                var result = res.result;
                var data = res.result.data;
                setComment(data.score, data.comment, result.section, result.time);
                var sign_add = "<span id='sign_add' class='sign-add'></span>";
                $("#tool").append(sign_add);
                $("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.result.addScore);
                $("#sign_add").show(function() {
                    $("#sign_add").animate({
                        top: "40%",
                        opactiy: "1"
                    }, function() {
                        $("#sign_add").hide().animate({
                            top: "35%"
                        });
                    });
                });

                if (res.result.upType) {
                    var types = '';
                    if (res.result.upType == 2) {
                        types = 'biggrowth';
                    }

                    if (res.result.upType == 1) {
                        types = 'smallgrowth';
                    }

                    $("body").GrowthLayer({
                        types: types, // five|smallgrowth|biggrowth
                        space: 5000, //时间间隔
                        auto: true, //自动关闭
                        growth: res.result.userLevel, // 等级,1,2,3
                        score: res.result.currentScore
                    });
                }
                layer.msg("感谢评价");
                return false;
            } else if (res.code == 2043) {
                layer.msg("已经评价");
                checkUserIsComment('living');
                return false;
            }
        }
        $.post(_addURL, request, commentSuc);
    }

    function addComment1(e) {
        e.preventDefault();
        var t = $.trim(_input1.html());
        //t = t.replace(/<.*?>/g, "");
        //t = t.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
        //t = t.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");
        if (!t) {
            layer.msg("评论不能为空！");
            return;
        }
        if (t.length < 5) {
            layer.msg("评论不能少于4个字哦");
            return;
        }
        var request = {};
        request["score"] = getScore1("avg_score");
        request["user_teacher"] = teacherId;
        request["plan_id"] = planId;
        request["user_owner"] = userOwner;
        var avg = $("#avg_11").text();
        for (i in request) {
            if (0 == request[i]) {
                alert(_score_11.find("[score_type=" + i + "]").prev().text() + "还没有打分呢！");
                return;
            }
        }
        request["comment"] = t;
        request["course_id"] = courseId;
        $("#comment_send").val("评论中...");
        var commentSuc = function(r) {
            res = JSON.parse(r);
            if (res.code == 1) {
                var result = res.result;
                var data = res.result.data;
                setComment1(data.score, data.comment, result.section, result.time);
                var sign_add = "<span id='sign_add' class='sign-add'></span>";
                $("#tool").append(sign_add);
                $("#sign_add").html('<img src="/assets_v2/img/exp-icon.png">+' + res.result.addScore);
                $("#sign_add").show(function() {

                    $("#sign_add").animate({
                        top: "40%",
                        opactiy: "1"
                    }, function() {
                        $("#sign_add").hide().animate({
                            top: "35%"
                        });
                    });
                });
                if (res.result.upType) {
                    var types = '';
                    if (res.result.upType == 2) {
                        types = 'biggrowth';
                    }

                    if (res.result.upType == 1) {
                        types = 'smallgrowth';
                    }
                    $("body").GrowthLayer({
                        types: types, // five|smallgrowth|biggrowth
                        space: 5000, //时间间隔
                        auto: true, //自动关闭
                        growth: res.result.userLevel, // 等级,1,2,3
                        score: res.result.currentScore
                    });
                }
                layer.msg("感谢评价");
                return false;
            } else if (res.code == 2043) {
                layer.msg("已经评价");
                checkUserIsComment('finished');
                return false;
            }
        }
        $.post(_addURL, request, commentSuc);
    }
    $("#comment_send1").click(addComment1);
    _input1.keypress(function(e) {
        if ((10 == e.keycode || 13 == e.keycode) && e.ctrlkey) {
            addComment1();
        }
    });
    $("#comment_send").click(addComment);
    _input1.keypress(function(e) {
        if ((10 == e.keycode || 13 == e.keycode) && e.ctrlkey) {
            addComment();
        }
    });

    //<!--星级评价
    $(function() {

        $("#score_percent>dl>dd>span").click(function() {
            $(this).css("background-position", "-4px -33px");
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
            $(this).parent().find('i').attr("data-score", $(this).attr('data-score'));
            // setAvgScore(0);
        });
        $("#score_percent1>dl>dd>span").click(function() {
            $(this).css("background-position", "-4px -33px");
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
            $(this).parent().find('i').attr("data-score", $(this).attr('data-score'));
            // setAvgScore1(0);
        });
        $("#score_percent>dl>dd>span").hover(function() {
            $(this).css("background-position", "-4px -33px");
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
            // setAvgScore(0);
        }, function() {
            var data_score = $(this).parent().find('i').attr("data-score");
            $(this).parent().find("span").each(function() {
                if ($(this).attr("data-score") == data_score) {
                    $(this).prevAll().css("background-position", " -4px -33px");
                    $(this).css("background-position", " -4px -33px");
                    $(this).nextAll().css("background-position", " -4px -3px");
                    $(this).parent().find('i').html($(this).attr('data-title'));
                }
            });
        });
        $("#score_percent1>dl>dd>span").hover(function() {
            $(this).css("background-position", "-4px -33px");
            $(this).prevAll().css("background-position", " -4px -33px");
            $(this).nextAll().css("background-position", " -4px -3px");
            $(this).parent().find('i').html($(this).attr('data-title'));
            // setAvgScore1(0);
        }, function() {
            var data_score = $(this).parent().find('i').attr("data-score");
            $(this).parent().find("span").each(function() {
                if ($(this).attr("data-score") == data_score) {
                    $(this).prevAll().css("background-position", " -4px -33px");
                    $(this).css("background-position", " -4px -33px");
                    $(this).nextAll().css("background-position", " -4px -3px");
                    $(this).parent().find('i').html($(this).attr('data-title'));
                }
            });
        });



    })
});

function setComment(score, comment, section, create_time) {
    if (create_time) {
        create_time = create_time.split(' ')[0];
    }
    var ret_score_comment = $("#ret_score_comment");
    var score_comment = $("#score_comment");
    var text_comment = $("#textComment");
    var ret_text_comment = $("#ret_textComment");
    var ret_comment_input = $("#ret_comment_input");
    var student1 = $("#avg_score").find("[data-score=" + score + "]");
    var course = $("#ret_course");
    var time = $("#ret_time");
    var scoreObj = $("#ret_score_score");
    $("#bar-title").hide();
    ret_score_comment.show();
    score_comment.hide();
    text_comment.hide();
    ret_text_comment.show();
    student1.css("background-position", "-4px -33px");
    student1.prevAll().css("background-position", "-4px -33px");
    student1.nextAll().css("background-position", "-4px -3px");
    $("#ret_avg_1").text(score);
    $("#ret_avg_12").text(score);
    ret_comment_input.text(comment);
    course.text(section);
    time.text(create_time);
    scoreObj.html('(' + score + '分)');

    //	student.find("[data-score=5]").prevAll().css("background-position","-4px -33px");
}

function setComment1(score, comment, section, create_time) {
    if (create_time) {
        create_time = create_time.split(' ')[0];
    }
    var ret_score_comment = $("#ret_score_comment1");
    var score_comment = $("#score_comment1");
    var text_comment = $("#textComment1");
    var ret_text_comment = $("#ret_textComment1");
    var ret_comment_input = $("#ret_comment_input1");
    var student1 = $("#avg_score1").find("[data-score=" + score + "]");
    var course = $("#ret_course1");
    var time = $("#ret_time1");
    var scoreObj = $("#ret_score_score1");
    $("#bar-title1").hide();
    ret_score_comment.show();
    score_comment.hide();
    text_comment.hide();
    ret_text_comment.show();
    student1.css("background-position", "-4px -33px");
    student1.prevAll().css("background-position", "-4px -33px");
    student1.nextAll().css("background-position", "-4px -3px");
    $("#ret_avg_11").text(score);
    $("#ret_avg_111").text(score);
    ret_comment_input.html(comment);
    course.text(section);
    time.text(create_time);
    scoreObj.html('(' + score + '分)');

    //	student.find("[data-score=5]").prevAll().css("background-position","-4px -33px");
}

function checkUserIsComment(type) {
    $.post('/comment/course/CheckISAddScore', {
            course_id: courseId,
            plan_id: planId
        },
        function(d) {
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

// note 笔记模块
$(document).ready(function($) {
	var plan_id = window.planId;
	function isLive(){return $("#live-status").attr('data-status') == 'living' ? true : false;}
	function getVideoStatus(){return isLive() ? 2 : 3;}
	function isH5player(){return $('video')[0] ? true : false;}
	var layerSkin = {skin: 'layui-layer-player'};
	var api = {
		add: '/course.note.NoteAdd',
		del: '/course.note.DelNote',
		edit: '/course.note.UpdateNote',
		load: '/course.note.NoteList',
		guideGet: '/course.note.noteInfo'
	};
	var getPlayTimeString = function (name) { //详情页笔记到播放页
	   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");
	   var r = window.location.search.substr(1).match(reg);
	   if (r!=null) return unescape(r[2]); return null;
	};
	(function(){
	    var t = null;
	    var time = location.href.match(/play_time=(\d+)/)
	    time = !time?NaN:parseInt(time[1]);
	    var canSeek = false;
	    function jump(){
	    	if(isH5player()){
	    		canSeek = !H5Player.$player[0].currentTime?false:true;
	    	}else{
	    		canSeek = Player.info()&&Player.info().playerState == 'playing'?true:false;
	    	}
	        if(canSeek){
	            isH5player()?H5Player.seek(time): Player.seek(time);
	        }else{
	            setTimeout(function(){
	                jump();
	            },200);
	        }
	    }
	    !isNaN(time)&&jump();
	})();
	var el = {
		note: $('#note-list'),
		list: $('#note-c'),
		list_add: $('#note-add'),
		list_del: '.note-del',
		list_edit: '.note-edit',
		list_content: '.note-content',
		list_time: '.note-time',
		list_del_popup: $('#note-popup'),
		list_del_popup_submit: $('#note-popup .submit'),
		list_del_popup_close: $('#note-popup .cancel'),
		edit: $('#note-edit-box'),
		edit_input: $('#note-edit-input'),
		edit_save: $('#note-edit-submit'),
		edit_cancel: $('#note-edit-cancel'),
		edit_time: $('#note-edit-time'),
		edit_countNum: $('#note-nowNum'),
		edit_count: $('#note-nowNum').parent(),
		down: $('#qute-note'),
		down_input: $('#qute-input'),
		down_time: $('#qute-time'),
		down_btn: $('#qute-btn'),
		down_save: $('#qute-submit'),
		down_cancel: $('#qute-cancel'),
		module_list: $('#note-list-module').html(),
		noNote: $('#no-note'),
		quteTip: $('#quteTip')
	};
	var temp = {
		edit_id: '',
		edit_time: '',
		edit_content: '',
		edit_obj: '',
		edit_type: '',
		edit_reset: function(){
			this.edit_id = '';
			this.edit_time = '';
			this.edit_content = '';
			this.edit_obj = '';
			this.edit_type = '';
		},
		play_time: 0,
		del_obj: '',
		del_id: ''
	};
	var maxNoteCount = 50;
	var quteTime = '';
	var NOTE = function(){
		this.nowCount = 0;
		isLive()?el.list.removeClass('unlive'):el.list.addClass('unlive');
		this.bind();
		this.getListData();
		return this;
	}
	NOTE.prototype = {
		getListData: function(){
			if(!isNaN(plan_id)&&plan_id>0){
				var self = this;
				var op = {
					url: api.load,
					param: {
						plan_id: plan_id,
						videoStatus: getVideoStatus()
					},
					success: function(res){
						if(res.data.totalSize>0){
							self.nowCount = res.data.totalSize;
							el.noNote.hide();
							self.renderList(res.data.items);
							if(supportDM){
								res.data.items.forEach(function(x){
									if(x.play_time&&!isNaN(x.play_time)){
										var stime = parseInt(x.play_time);
										stime = stime<0?0:stime;
										DM_note.insert({
											text: x.content,
											ex:{
												dbid: x.id,
												stime: stime,
												size: 20,
												color: 0xffffff,
												border: true
											}
										});
									}
								});
							}
						}
					},
					fail: function(res){
						//console.log(res);
					}
				};
				ajax(op);
			}
		},
		renderList: function(data){
			el.list.html(render({
				str: el.module_list,
				data: data
			}));
		},
		data_add: function(_content){
			var self = this;
			var content = _content == '' ? '重点': _content;
			if(verInputCount(content)){
				self.edit_error();
				return false;
			}
			if(this.nowCount>=maxNoteCount){
				layer.msg('最多只能添加'+maxNoteCount+'条笔记',layerSkin);
				return false;
			}
			var op = {
				url: api.add,
				param: {
					content: content,
					play_time_tmp: temp.play_time,
					plan_id: plan_id,
					videoStatus: getVideoStatus()
				},
				success: function(res){
					el.list.prepend(render({
						str: el.module_list,
						data: [{
							play_time_format: res.play_time_format,
							play_time_tmp_handle: res.play_time_tmp_handle,
							content: content,
							id: res.note_id
						}]
					}));
					if(supportDM && res.play_time && !isNaN(res.play_time)){
						var stime = parseInt(res.play_time);
						stime = stime<0?0:stime;
						var DM_content = {
							text: content,
							ex:{
								dbid: res.note_id,
								stime: stime,
								size: 20,
								color: 0xffffff,
								border: true
							}
						};
						DM_note.insert(DM_content);
						if(needDM){
							DM_note.send(DM_content);
						}
					}
					self.nowCount += 1;
					self.nowCount>0?el.noNote.hide(): el.noNote.show();
					if(el.down.hasClass('active')){
						self.quteTipAnimate();
					}
					self.edit_cancel();
					self.down_cancel();
					//layer.msg('添加成功');
                    // 增加积分
                    var expData = res.date;
                    if(expData && expData.code == 0){
                        if(expData.data){
                            $.expChangeEffect(expData.data);
                        }
                    }
				},
				fail: function(res){
					layer.msg(res.msg,layerSkin);
				}
			};
			return ajax(op);
		},
		add_save: function(){
			if(this.forbidAdd()){
				return false;
			}
			this.data_add(el.edit_input.val());
			return this;
		},
		add_cancel: function(){
			this.nowCount>0 ? el.noNote.hide(): el.noNote.show();
			this.edit_cancel();
		},
		edit_save: function(){
			var self = this;
			var content = el.edit_input.val();
			content = content == ''?'重点':content;
			if(verInputCount(content)){
				self.edit_error();
				return false;
			}
			self.edit_unerror();
			var op = {
				url: api.edit,
				param: {
					content: content,
					note_id: temp.edit_id,
					plan_id: plan_id,
					videoStatus: getVideoStatus()
				},
				success: function(){
					temp.edit_obj.find(el.list_content).text(content);
					if(supportDM){
						DM_note.edit(temp.edit_id,content);
					}
					self.edit_cancel();
				},
				fail: function(res){
					if(res.code == -120){
						self.edit_cancel();
					}else{
						layer.msg(res.msg,layerSkin);
					}
				}
			};
			return ajax(op);
		},
		edit_cancel: function(){
			if(temp.edit_type == 'edit'){
				temp.edit_obj.removeClass('active');
			}
			temp.edit_reset();
			el.edit_input.val('');
			this.setEditInput();
			this.edit_hide();
		},
		edit_hide: function(){
			el.edit.hide();
			el.list.after(el.edit);
		},
		edit_error: function(type){
			if(type=='edit'){
				el.edit.addClass('error');
			}else if(type=='down'){
				el.down.addClass('error');
			}
		},
		edit_unerror: function(type){
			if(type=='edit'){
				el.edit.removeClass('error');
			}else if(type=='down'){
				el.down.removeClass('error');
			}
		},
		list_add: function(){
			el.noNote.hide();
			temp.edit_reset();
			temp.edit_type = 'add';
			temp.play_time = isH5player() ? H5Player.info().currentTime: Player.info().currentTime;
			temp.edit_time = formatTime(temp.play_time);
			this.setEditInput();
			el.list.children('.active').removeClass('active');
			this.showEditInput();
		},
		list_edit: function(obj){
			var o = obj.closest('li');
			temp.edit_reset();
			temp.edit_type = 'edit';
			temp.edit_obj = o;
			temp.edit_id = o.attr('data-id');
			temp.edit_content = o.find(el.list_content).text();
			temp.edit_time = o.find(el.list_time).text();
			this.setEditInput();
			o.addClass('active').siblings().removeClass('active');
			this.showEditInput();
		},
		list_del: function(o){
			temp.del_obj = o.closest('li');
			temp.del_id = temp.del_obj.attr('data-id');
			el.list_del_popup.show();
		},
		list_del_popup_submit: function(){
			var self = this;
			var op = {
				url: api.del,
				param: {
					note_id: temp.del_id,
					plan_id: plan_id,
					videoStatus: getVideoStatus()
				},
				success: function(){
					self.nowCount -= 1;
					self.nowCount = self.nowCount<0?0:self.nowCount;
					if(temp.del_obj){temp.del_obj.remove();}
					self.nowCount>0?el.noNote.hide():el.noNote.show();
					if(supportDM){
						DM_note.remove(temp.del_id);
					}
					//layer.msg('删除成功');
					self.list_del_popup_close();
				},
				fail: function(res){
					layer.msg(res.msg,layerSkin);
					self.list_del_popup_close();
				}
			};
			ajax(op);
		},
		list_del_popup_close: function(){
			temp.del_obj = '';
			temp.del_id = '';
			el.list_del_popup.hide();
		},
		setEditInput: function(){
			el.edit.attr('data-id',temp.edit_id);
			el.edit_time.text(temp.edit_time);
			el.edit_input.val(temp.edit_content);
			var w = el.edit_input.val();
			el.edit_countNum.text(getLength(w));
			verInputCount(w)?this.edit_error('edit'): this.edit_unerror('edit');
		},
		showEditInput: function(){
			this.down_cancel();
			var type = temp.edit_type;
			if(type == 'edit'){
				temp.edit_obj.append(el.edit);
				el.edit.show();
			}else if(type == 'add'){
				el.edit.show();
				el.list.before(el.edit);
			}
		},
		down_show: function(){
			this.edit_cancel();
			temp.play_time = isH5player() ? H5Player.player().currentTime: Player.info().currentTime;
			temp.edit_time = formatTime(temp.play_time);
			el.down_time.text(temp.edit_time);
			el.down_input.val('').attr('placeHolder','按Enter键可以直接保存');
			el.down.addClass('active');
		},
		down_cancel: function(){
			el.down_input.val('').attr('placeHolder','输入此刻你要记录的内容').blur();
			el.down.removeClass('active').removeClass('error');
			el.down_time.text('记笔记：');
		},
		down_save: function(){
			if(!isLive()){this.playerPlay();}
			if(this.forbidAdd()){
				return false;
			}
			this.data_add(el.down_input.val());
			return this;
		},
		quteTipAnimate: function(){
			el.quteTip.hide();
			clearTimeout(quteTime);
			var pos1 = el.down_save.offset();
			var pos2 = $('#tabiconNote').offset();
			var w1 = el.down_save.width();
			var w2 = $('#tabiconNote').width();
			el.quteTip
			.css({
				top: pos1.top - 5 +'px',
				left: pos1.left + w1 + 5 +'px',
				width: '6px',
				height: '6px'
			})
			.show()
			.animate({
				top: pos2.top + 35 + 'px',
				left: pos2.left + w2 - 42 +'px',
				width: '8px',
				height: '8px'
			}, 1000 , function(){
				quteTime = setTimeout(function(){
					el.quteTip.fadeOut();
				},1000)
			});
		},
		forbidAdd: function(){
			var status = true;
			if(isH5player()){
				status = H5Player.player().currentTime>0?false: true;
			}else{
				status = Player.info()&&Player.info().currentTime>0?false: true;
			}
			if(!status){
				return false;
			}else{
				layer.msg('只有上课中才可以记笔记哦！',layerSkin);
				el.down_input.blur();
				return true;
			}
		},
		playerPlay: function(){
			isH5player()?H5Player.play(): Player.play();
		},
		playerPause: function(){
			isH5player()?H5Player.paused(): Player.pause();
		},
		bind: function(){
			var self = this;
			// 列表
			el.list
			.on('click', el.list_edit, function(event) {
				event.preventDefault();
				self.list_edit($(this));
			})
			.on('click', el.list_del, function(event) {
				event.preventDefault();
				self.list_del($(this));
			})
			.on('click', el.list_time, function(event) {
				event.preventDefault();
				var jumpTime = $(this).attr('data-handle');
				if(isLive()||$(this).hasClass('error')||isNaN(jumpTime)){
					//console.log('无法跳转')
				}else{
					isH5player()?H5Player.seek(jumpTime): Player.seek(jumpTime);
				}
				return $(this);
			});
			el.list_add.click(function() {
				if(self.forbidAdd()){
					return false;
				}
				if(self.nowCount>=maxNoteCount){
					layer.msg('最多只能添加'+maxNoteCount+'条笔记',layerSkin);
					return false;
				}
				if(!isLive()){self.playerPause();}
				self.list_add();
				return $(this);
			});
			//编辑
			el.edit_input.on('input',function(event) {
				event.preventDefault();
				var w = $(this).val();
				el.edit_countNum.text(getLength(w));
				verInputCount(w)?self.edit_error('edit'): self.edit_unerror('edit');
			});
			if(!$.support.opacity){
                el.edit_input.on('blur',function(event){
                    $(this).trigger('input');
                });
                el.edit_input.on('keyup',function(event){
                    $(this).trigger('input');
                });
            }
			el.edit_save.click(function(event) {
				event.preventDefault();
				if(temp.edit_type == 'edit'){
					self.edit_save()
				}else if(temp.edit_type == 'add'){
					self.add_save();
					if(!isLive()){self.playerPlay();}
				}
			})
			el.edit_cancel.click(function(event) {
				event.preventDefault();
				if(temp.edit_type == 'edit'){
					self.edit_cancel()
				}else if(temp.edit_type == 'add'){
					self.add_cancel();
					if(!isLive()){self.playerPlay();}
				}
			});
			//删除
			el.list_del_popup_submit.click(function() {
				self.list_del_popup_submit();
			});
			el.list_del_popup_close.click(function() {
				self.list_del_popup_close();
			});
			//底部笔记功能
			el.down.keyup(function(event) {
				if(event.keyCode==13){
					if(el.down.hasClass('active')&&el.down_input.is(':focus')){
						self.down_save();
					}
				}
			});
			el.down_input.click(function(event) {
				event.preventDefault();
				if(!isLogin){
					$('#regcheck1').trigger('click');
					return false;
				}
				if(isSign){
					layer.msg('您没有报名此课程，不能记笔记',layerSkin);
					return false;
				}
				if(self.nowCount>=maxNoteCount){
					layer.msg('最多只能添加'+maxNoteCount+'条笔记',layerSkin);
					return false;
				}
				if(!el.down.hasClass('active')&&!self.forbidAdd()){
					if(!isLive()){self.playerPause();}
					self.down_show();
				}
				return $(this);
			});
			el.down_input.on('input',function(event) {
				event.preventDefault();
				verInputCount($(this).val())?self.edit_error('down'):self.edit_unerror('down');
			});
			if(!$.support.opacity){
                el.down_input.on('blur',function(event){
                    $(this).trigger('input');
                });
                el.down_input.on('keyup',function(event){
                    $(this).trigger('input');
                });
            }
			el.down_save.click(function(event) {
				event.preventDefault();
				if(el.down.hasClass('active')){
					self.down_save();
				}
			});
			el.down_cancel.click(function(event) {
				event.preventDefault();
				self.down_cancel($(this));
				if(!isLive()){self.playerPlay();}
			});
		}
	};
	window.PlayerNote = new NOTE();
	function ajax(op){
		return $.ajax({
			url: op.url,
			type: op.type||'POST',
			dataType: op.dataType||'json',
			data: op.param||null
		})
		.done(function(res) {
			if(res.code == 200){
				if(typeof op.success == 'function'){
					op.success(res);
				}
			}else{
				if(typeof op.fail == 'function'){
					op.fail(res);
				}
			}
		});
	}
	function render(op){
        var data = op.data;
        var arr = [];
        $.each(data,function(i, n) {
            var str = '';
            if(n.play_time_tmp_handle=='error'){
                str += n.tailor&&n.tailor=='tailor' ? '<li data-id="'+n.id+'" class="timeError tailor">':'<li data-id="'+n.id+'" class="timeError notailor">';
            }else{
                str += '<li data-id="'+n.id+'" class="noError">';
            }
            str += '<div class="note-info">';
            if(n.tailor && n.tailor=='tailor'){
                str += '<span class="note-time c-fl" data-handle="'+n.play_time_tmp_handle+'"></span>';
            }else{
                str += '<span class="note-time c-fl" data-handle="'+n.play_time_tmp_handle+'">'+n.play_time_format+'</span>'
            }
            str += '<span class="note-btn c-fr"><i class="note-del"></i><i class="mr10 note-edit"></i></span>';
            str += '</div>';
            str += '<div class="note-content">'+(n.content==''?"重点":n.content)+'</div>'
            str += '</li>';
            arr.push(str);
        });
		return arr.join('');
	}
	function getLength(w){
		var l = w.replace(/[\u0391-\uFFE5]/g,"aa").length/2;
		return l>50?Math.ceil(l):Math.floor(l);
	}
	function verInputCount(w){
		return w.replace(/[\u0391-\uFFE5]/g,"aa").length>100?true:false;
	}
	function formatTime(time){
		var t = parseInt(time);
		var h = Math.floor(t/3600);
		var m = Math.floor((t - h*3600)/60);
		var s = Math.floor(t - h*3600 - m*60);
		h = h<10?'0'+h:h;
		m = m<10?'0'+m:m;
		s = s<10?'0'+s:s;
		return [h,m,s].join(':');
	}
});
// 笔记引导
function _guideNote(callback){
    this.endCallback = function(){
        // if(typeof(GuideGift) == "object"){
        //    GuideGift.init();
        // }else{
          resetAutoCloseList();
        // }
    }
	this.show = function(){
			$('#guideNote').show();
			$('#guideNote1').show();
	}
    var self = this;
	this.bind = function(){
		$('#guideNoteNext').click(function() {
			$('#guideNote1').hide();
			$('#guideNote2').show();
			$('#ck_list li').eq(3).trigger('click')
		});
		$('#guideNoteIgnore').click(function() {
			$('#guideNote').hide();
             self.endCallback();
		});
		$('#guideNoteComplete').click(function() {
			$('#guideNote').hide();
             self.endCallback();
		});
	}
    $.ajax({
        url: '/course.note.noteInfo',
        type: 'POST',
        dataType: 'json',
        data: { plan_id: window.planId},
        cache:false
    })
    .done(function(res) {
        if(res.data == 'true'){
            self.show();
            self.bind();
        }else{
            self.endCallback();
        }
    });
}
function resetAutoCloseList(){
    window.forbidCloseList = false;
}
jQuery(document).ready(function($) {
    window.GuideNote = new _guideNote();
});
// 笔记引导end
// gift
window.supportGift = true;
jQuery(document).ready(function($) {
    var planId = window.planId;
    var isLogin = window.isLogin;
    var isSign = !window.isSign;
    var isLive = function(){return $("#live-status").attr('data-status') == 'living' ? true : false;}
    window.useRedName = false;
    var flowerImg = '<i class="icon-gift-flower-small"></i>';
    var sendEffectImage = (function(){
        var arr = [];
        for (var i = 1; i < 11; i++) {
            arr.push('/assets_v2/img/player-gift/'+i+'.png');
        }
        return arr;
    })();
    var sendEffectImageFlag = sendEffectImage.slice();
    function preloadimages(arr){
        function load(i){
            return function(){
                var img = new Image()
                img.src=arr[i]
                img.onload = function(){
                    arr[i] = 1;
                }
            }
        }
        for (var i=0; i<arr.length; i++){
            load(i)();
        }
    }
    preloadimages(sendEffectImageFlag);
    var _config = {
        supportGift: window.supportGift,
        url: {
            dataGet: '/user/gift/getStudentGift',
            guide: '/user/gift/getGuide',
            ifReceive: '/user/gift/addPlanFlower',
            receive: '/user/gift/getFlowers'
        },
        effect:['#ffe11b','#ffcc1b','#ffbc1b','#ff961b','#ff711b','#ff561b','#ff3000','#f20303','#f20303','#f20379'],
        giftSendEffectDom: function(type,i){
            var str1 = '<div id="giftSendEffect" class="gift-send-effect" style="display:none">'
                     +'     <p>连送老师<i class="icon-gift-flower"></i><span class="send-count">x'+i+'</span></p>'
                     +'</div>';
            var str2 = '<div id="giftSendEffect" class="gift-send-effect" style="display:none">'
                     +  '<img src="' +sendEffectImage[i - 1]+ '">'
                     +  '</div>';
            return type == 'img'? str2 : str1;
        },
        giftReceiveDom: (function(){
            var str = '<div class="gift-receive" id="giftReceive" style="display:none">'
                     +'      <div class="gift-receive-header">'
                     +'          <img src="/assets_v2/img/gift-receive-title.png">'
                     +'          <i class="gift-receive-close">╳</i>'
                     +'      </div>'
                     +'      <div class="gift-receive-body">'
                     +'          <p>领取鲜花可以送给老师哦~<br />签到也可以领取哦</p>'
                     +'          <span class="gift-btn gift-receive-btn">点击领取</span>'
                     +'      </div>'
                     +'</div>';
            return str;
        })(),
        giftType:{
            1:'FLOWER'
        }
        
    };
    var el = {
        supportClassName: 'supportGift',
        forbidClassName: 'not-enough',
        giftSendEffectId: 'giftSendEffect',
        giftReceiveId: 'giftReceive',
        giftsTrigger: $('#giftsTrigger'),
        playerContent: $('#playerContent'),
        gift: $('#gift'),
        gifts: $('#gifts'),
        giftGuide: $('#guideGift'),
        teacherGift: $('#teacherGift'),
        teacherGiftCount: $('#teacherGiftCount'),
        userGiftCount: $('#userGiftCount'),
        sendClassName: {
            all: 'gift-send',
            1: 'gift-send-flower',
            2: 'gift-redName'
        }
    };

    // 送礼限制
    var temp_lastTime = null;
    var temp_thisTime = '';
    var temp_limitTime = 2000;
    var temp_forbidTimeout = null;
    var temp_sendTimeout = null;

    //兑换
    var temp_exp = 0;
    var temp_exchanging = false;
    var temp_exchangeCount = 0;
    var temp_exchangeUnit = 0;
    var temp_exchangeTimeOut = null;

    // 送礼连击
    var temp_sendCount = 0;
    var temp_sendType = null;
    var temp_effectTimeout = null;

    var temp_giftCount = {
        1: 0,
        2: 0,
        3: 0,
        4: 0
    };

    // 领取礼物定时
    var temp_receiveTimeout = null;

    var data_teacherGiftCount = 0;
    var data_userGiftCount = {
        1: 0,
        2: 0
    };
    var data_exp = 0;

    function updateTeacherGift(num){
        data_teacherGiftCount = num;
        el.teacherGiftCount.text(data_teacherGiftCount);
    }

    function dataTrans(d){
        var data = {};
        $.each(d,function(i, v) {
            data[v.giftId] = v.giftCount;
        });
        return data;
    }
    /**
     * [updateUserGift 更新礼物数量]
     * @param  {[type]} d [传入礼物数据]
     * @param  {[type]} flag   [是否禁止数据转换]
     */
    function updateUserGift(d,trans,flag){
        var data = null;
        if(!trans){
            data = dataTrans(d);
        }else{
            data = d;
        }
        if(!flag){
            temp_giftCount = $.extend({}, temp_giftCount, data);
        }else{
            data_userGiftCount = $.extend({}, data_userGiftCount, data);
            temp_giftCount = $.extend({}, temp_giftCount, data_userGiftCount);
        }
        $.each(temp_giftCount,function(i, v) {
            if(v <=0 ){
                setForBid.call($('.'+el.sendClassName[i]),true,i);
            }else{
                setForBid.call($('.'+el.sendClassName[i]),false,i);
            }
            var n = v > 99 ? '99+' : v < 0 ? 0 : v;
            $('.'+el.sendClassName[i]).find('.gift-count').text(n);
        });
    }
    function updateExp(n,flag){
        n = parseInt(n);
        if(!isNaN(n)){
            temp_exp = n;
            if(flag){
                data_exp = n;
            }
            $('#expNum').text(n);
            $('#giftsContents li').each(function(i, item) {
                 var _this = $(item);
                 var minexp = parseInt(_this.attr('data-minExp'));
                 if(!isNaN(minexp)){
                    if(n < minexp){
                        _this.addClass('forbid-exp');
                    }else{
                        _this.removeClass('forbid-exp');
                    }
                 }
            });
        }
    }
    function setForBid(flag,type){
        var f = flag;
        if(type && temp_giftCount[type] < 1){
            f = true;
        }
        f ? this.addClass(el.forbidClassName) : this.removeClass(el.forbidClassName);
    }
    function setRedName(flag){
        if(!flag){
            window.useRedName = false;
            $('#giftRedName').removeClass('used');
        }else{
            window.useRedName = true;
            $('#giftRedName').addClass('used');
        }
    }
    var _GIFT = function(op) {
        if (this instanceof _GIFT) {
            this.dft = {
            };
            this.opt = $.extend({},this.dft,op);
            this.init();
        } else {
            return new _GIFT(op);
        }
    };
    function giftLayerMsg(){
        return {
            area:'300px',
            offset:[($(window).height()-400)+'px',($(window).width()-375)+'px']
        }
    }
    _GIFT.prototype = {
        sendValid: function(){
            var flag = true;
            temp_thisTime = new Date();
            if(temp_thisTime - temp_lastTime < temp_limitTime){
                //layer.msg('赠送太频繁');
                flag = false;
                
            }
            if(!flag){
                setForBid.call($('.'+el.sendClassName[temp_sendType]),true,temp_sendType);
                clearTimeout(temp_forbidTimeout)
                temp_forbidTimeout = setTimeout(function(){
                    setForBid.call($('.'+el.sendClassName['all']),false);
                },temp_limitTime);
            }
            return flag;
        },
        send: function(type){
            if(temp_giftCount[type] < 1){
                layer.msg('请使用积分兑换礼物',giftLayerMsg());
                setForBid.call($('.'+el.sendClassName[type]),true,type);
                return false;
            }
            if($('.'+el.sendClassName[type]).hasClass(el.forbidClassName)){
                return false;
            }
            if(!temp_sendType){
                temp_sendType = type;
            }else if(temp_sendType != type){
                this.sendGift();
            }
            if(temp_sendCount>9){
                this.sendGift();
                return false;
            }
            if(!this.sendValid()){
                return false;
            }
            var self = this;
            clearTimeout(temp_sendTimeout);
            temp_sendCount += 1;
            temp_giftCount[type] -= 1;
            temp_giftCount[type] = temp_giftCount[type] < 0 ? 0 : temp_giftCount[type];
            if(temp_sendCount > 1){ this.sendEffect(); }
            var n = temp_giftCount[type]  > 99 ? '99+' : temp_giftCount[type]  < 0 ? 0 : temp_giftCount[type] ;
            $('.' + el.sendClassName[type]).find('.gift-count').text(n);
            temp_sendTimeout = setTimeout(function(){
                self.sendGift();
            },500);
        },
        sendGift: function(){
            temp_lastTime = new Date();
            if(temp_sendCount>0 && temp_sendType){
                this.messageSend();
                // console.log('赠送了'+temp_sendCount+'朵花');
                temp_sendCount = 0;
                temp_sendType = 0;
            }
        },
        sendEffect: function(){
            var dom = null;
            var newDom = null;
            if(sendEffectImageFlag[temp_sendCount-1] == 1){
                newDom = _config.giftSendEffectDom('img',temp_sendCount);
                if(!$('#'+el.giftSendEffectId)[0]){
                    dom = $(newDom);
                    $('#chat').append(dom);
                }else{
                    dom = $('#'+el.giftSendEffectId);
                    dom.html($(newDom).html());
                }
            }else{
                newDom = _config.giftSendEffectDom('text',temp_sendCount);
                if(!$('#'+el.giftSendEffectId)[0]){
                    dom = $(newDom);
                    $('#chat').append(dom);
                }else{
                    dom = $('#'+el.giftSendEffectId);
                    dom.html($(newDom).html());
                }
                if(temp_sendCount >1 && temp_sendCount< 11){
                    //dom.attr('data-effect','s'+temp_sendCount);
                    dom
                    .css('color', _config.effect[temp_sendCount-1])
                    .find('.send-count').css('font-size', 18+(temp_sendCount-1)*2+'px');
                }
            }
            if(dom.is(":hidden")){
                dom.fadeIn();
            }
            clearTimeout(temp_effectTimeout);
            temp_effectTimeout = setTimeout(function(){
                dom.fadeOut();
            },2000); 
        },
        sendFail: function(info){
            if(!info.ext && !isNaN(parseInt(info.ext))){
                updateUserGift({1:parseInt(info.ext)},true,true);
            }else{
                updateUserGift({1:data_userGiftCount[1]},true,true);
            }
        },
        sendSuccess: function(info){
            info.ext && !isNaN(parseInt(info.ext)) && updateUserGift({1:parseInt(info.ext)},true,true);
            // console.log('剩余花的数量:'+data_userGiftCount[1]);
        },
        messageSend: function(){
            // 赠送
            // console.log('赠送之前花的数量：'+data_userGiftCount[temp_sendType])
            var content = {
                "gift": _config.giftType[temp_sendType],
                "number": temp_sendCount
            };
            var self = this;
            message.giftSend(JSON.stringify(content),function(value,info){
                if(!temp_sendType && temp_sendCount == 0 && info){
                    if(info.code == 0){
                        self.sendSuccess(info);
                    }else{
                        self.sendFail(info);
                    }
                }
            });
        },
        messageAddMsg: function(res){
            var from = res.uf_n || '';
            var to = res.ut_n || window.teacherName;
            var c = res.c && JSON.parse(res.c) || '';
            if(from && to && c && _displayChatList){
                var num =  c.number;
                var type = c.gift;
                var str = '<li data-uid="'+userId+'"><p>\''+from+'\' 送给 \''+to+'\' ';
                if(num <= 1){
                    str += '一朵鲜花 '+ flowerImg;
                }else{
                    str += '鲜花 '+ flowerImg + ' x '+ num;
                }
                str += '</p></li>';
                _displayChatList.append(str);
                _displayChatList.moveEnd(_activeGroupId || 0);
                _scrollFlag = true;
                updateTeacherGift( parseInt(data_teacherGiftCount) + parseInt(num));
                return;
            }
        },
        bind: function(){
            var self = this;
            // 绑定dom事件
            $(document).click(function(event) { 
                var target = event.target;
                if(target.id == 'giftsTrigger' || target.id == 'gifts' || $(target).parents('#gifts').length > 0){
                    return false;
                }
                el.gifts.fadeOut();
            });
            el.giftsTrigger.click(function() {
                el.gifts.fadeToggle();
            });
            // 赠送礼物
            el.gift.on('click', '.gift-exp-help', function(event) {
                window.open($(this).attr('href'));
            });
            // 赠送礼物
            el.gift.on('click', '.'+el.sendClassName.all, function(event) {
                event.preventDefault();
                var type = $(this).attr('data-type');
                if(!type){
                    return false;
                }
                self.send(type);
            });
            // 兑换
            el.gift.on('click', '.gift-exchange-btn', function(event) {
                event.preventDefault();
                var p = $(this).parent();
                if(!p.hasClass('forbid-exp')){
                    self.exchange($(this));
                }
            });
            // 使用红名卡
            el.gift.on('click', '.gift-redName', function() {
                self.redNameCardUse(this);
            });
        },
        showFunction: function(){
            $('.'+el.supportClassName).removeClass('hide');
        },
        receiveFail: function(){

        },
        receiveEnd: function(flag){
            clearTimeout(temp_receiveTimeout);
            $('#'+el.giftReceiveId)[0] && $('#'+el.giftReceiveId).remove();
            flag && this.receiveSend();
        },
        receiveSend: function(){
            var self = this;
            console.log('领取成功');
            var ajax = $.ajax({
                url: _config.url.receive,
                type: 'POST',
                dataType: 'json',
                data: {planId: planId}
            })
            .done(function(res){
                if(res.code == 0){
                    receiveSuccess();
                }else{
                    self.receiveFail();
                }
            });
            return ajax;
        },
        receive: function(){
            var self = this;
            $.ajax({
                url: _config.url.ifReceive,
                type: 'POST',
                dataType: 'json',
                data: {
                    planId: planId,
                    courseId: window.courseId
                }
            })
            .done(function(res) {
                if(res.code == 0){
                    if(res.status && res.status == "true"){
                        if(!$('#'+el.giftReceiveId)[0]){
                            var dom = $(_config.giftReceiveDom);
                            el.playerContent.append(dom);
                            dom.on('click','.gift-receive-close',function(){
                                self.receiveEnd();
                            });
                            dom.on('click','.gift-receive-btn',function(){
                                self.receiveEnd(true);
                            });
                            dom.show();
                            temp_receiveTimeout = setTimeout(function(){
                                self.receiveEnd();
                            },300*1000);
                        }
                    }else{
                        self.receiveEnd();
                    }
                }else{
                    self.receiveEnd();
                    res.msg && console.log(res.msg)
                }
            });
            
        },
        refreshData: function(){
            $.ajax({
                url: _config.url.dataGet,
                type: 'POST',
                dataType: 'json',
                data: {planId: planId}
            })
            .done(function(res) {
                if(res.code == 0){
                    if(res.result){
                        // console.log('刷新礼物数据',res.result);
                        if(res.result.gift){
                            updateUserGift(res.result.gift,false,true);
                        }else{
                            setForBid.call($('.'+el.sendClassName['all']),true);
                        }
                        res.result.giftSum && updateTeacherGift(res.result.giftSum);
                        res.result.point && updateExp(res.result.point,true);
                        if(res.result.redNameCardStatus){
                            setRedName(true);
                        }else{
                            setRedName(false);
                        }
                    }
                }else{
                    console.log('礼物数据获取',res.msg);
                }
            });
            
        },
        redNameCardUse: function(item){
            var o = $(item);
            if(o.hasClass('used')){
                return false;
            }
            if(data_userGiftCount[2] <= 0){
                layer.msg('请使用积分兑换礼物',giftLayerMsg());
            }
            if(!o.hasClass(el.forbidClassName) && data_userGiftCount[2] > 0){
                $.ajax({
                    url: '/user/point/redCardUse',
                    type: 'POST',
                    dataType: 'json',
                    data: {giftId: '2'}
                })
                .done(function(res) {
                    var obj = {};
                    if(res.code == 200){
                        setRedName(true); // 红名卡开关
                        $('#useRedNameEffect').show(); //使用红名卡特效
                        setTimeout(function(){
                            $('#useRedNameEffect').fadeOut();
                        },2000);
                        if('cardNum' in res){
                            obj[2] = res.cardNum;
                        }else{
                            obj[2] = parseInt(data_userGiftCount[2]) - 1;
                        }
                        updateUserGift(obj,true,true); //更新持有数量
                    }else{
                        layer.msg(res.msg);
                        if('cardNum' in res){
                            obj[2] = res.cardNum;
                            updateUserGift(obj,true,true); //更新持有数量
                        }
                    }
                });
                
            }
        },
        expAdd: function(addPoint){
            // 对外增加积分
            if(addPoint > 0){
                updateExp(temp_exp + addPoint,true);
            }
        },
        exchangeAjax: function(){
            if(temp_exchanging){
                layer.msg('兑换太频繁',giftLayerMsg());
                return false;
            }
            var n = temp_exchangeCount * temp_exchangeUnit;
            if(temp_sendType && n > 0){
                var _this = this;
                temp_exchanging = true;
                console.log(temp_sendType,temp_exchangeCount,n);
                $.ajax({
                    url: '/user/point/PointConvertFlower',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        giftId: temp_sendType,
                        giftNum: temp_exchangeCount
                    }
                })
                .done(function(res) {
                    if(res.code == 200){
                        var obj = {};
                        obj[temp_sendType] = parseInt(data_userGiftCount[temp_sendType]) + temp_exchangeCount;
                        updateUserGift(obj,true,true);
                        if(!isNaN(res.allPoint)){
                            updateExp(res.allPoint,true);
                        }else{
                            updateExp(data_exp - n,true);
                        }
                        _this.exchangeReset();
                    }else{
                        if(!isNaN(res.allPoint)){
                            updateExp(res.allPoint,true);
                        }
                        layer.msg(res.msg,giftLayerMsg());
                        _this.exchangeFail();
                    }
                })
                .error(function() {
                    _this.exchangeFail();
                });
            }
        },
        exchangeFail: function(){
            updateUserGift(data_userGiftCount,false,true);
            updateExp(data_exp,true);
            this.exchangeReset();
        },
        exchangeReset:function(){
            temp_exchanging = false;
            temp_exchangeCount = 0;
            temp_sendType = 0;
            temp_exchangeUnit = 0;
            clearTimeout(temp_exchangeTimeOut);
            temp_exchangeTimeOut = null;
        },
        exchange: function(o){
            var _this = this;
            var p = o.parent();
            var t = parseInt(p.attr('data-type'));
            var n = parseInt(p.attr('data-minExp'));
            if(temp_sendType && t != temp_sendType){
                // 从一种点击到另一种，立即兑换
                clearTimeout(temp_exchangeTimeOut);
                _this.exchangeAjax();
                return;
            }
            if(!isNaN(n) && temp_exp < n){
                // 积分不足，立即兑换
                clearTimeout(temp_exchangeTimeOut);
                _this.exchangeAjax();
                return;
            }
            temp_sendType = t;
            temp_exchangeUnit = n;
            temp_exchangeCount += 1;
            temp_exp -= n;
            var obj = {};
            obj[t] = parseInt(temp_giftCount[t]) + 1;
            updateUserGift(obj,true,false);
            updateExp(temp_exp,false);
            clearTimeout(temp_exchangeTimeOut);
            temp_exchangeTimeOut = setTimeout(function(){
                _this.exchangeAjax();
            },2000);
        },
        init: function(){
            // 初始化
            // 礼物功能展现
            this.showFunction(); 
            // 事件绑定
            this.bind();
            // 获取数据
            this.refreshData(); 
            // 送礼功能展现
            if(isLogin){
                $('.gift-trigger').removeClass('hide');
            }
            // 礼物领取
            if(isLogin && isSign && isLive()){
                this.receive();
            }
        }
    };
    // pravite
    
    function receiveSuccess(){
        updateUserGift({1:parseInt(data_userGiftCount[1]) + 1},true,true);
    }
    window.GuideGift = {
        endCallback: function(){
            resetAutoCloseList();
        },
        bind: function(){
            var self = this;
            el.giftGuide.on('click','.gift-guide-btn,.gift-guide-close',function(event) {
                event.preventDefault();
                el.giftGuide.hide();
                self.endCallback();
            });
        },
        init: function(){
            var self = this;
            if(!supportGift){
                self.endCallback();
                return false;
            }
            if(!$('#content').hasClass('expend') || !$('#chat').hasClass('active')){
                $('[ck="talk"]').trigger('click');
            }
            $.ajax({
                url: _config.url.guide,
                type: 'POST',
                dataType: 'json',
                data: {
                    uid: userId,
                    planId: planId
                }
            })
            .done(function(res) {
                if(res.code == 0 && res.result.giftGuide && res.result.giftGuide == "true"){
                    self.bind();
                    el.giftGuide.show();
                }else{
                    // console.log('礼物引导： ',res);
                    self.endCallback();
                }
            });
        }
    };
    if(_config.supportGift && !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        window.GIFT = new _GIFT();
    }
});
// 课间休息
jQuery(document).ready(function($) {
var Relax = {
    _node: $('#relax'),
    _nodeClassBegin: $('#relaxEnd'),
    _nodeClassRelax: $('#relaxBegin'),
    _nodeAudio: $('#relaxAudio'),
    _btnBegin: $('#relaxEndBtn'),
    _btnRelax: $('#relaxBeginBtn'),
    _clock: $('#relaxClock'),
    _clockM: $('#relaxClockM'),
    _clockS: $('#relaxClockS'),
    timer: '',
    supportAudio: $.support.opacity,
    dur: 0,
    _hide: function(){
        clearTimeout(this.timer);
        this._clock.hide();
        this._node.hide();
        if(this.supportAudio && this._nodeAudio[0]){
            this._nodeAudio[0].pause();
            this._nodeAudio[0].currentTime = 0;
        }
    },
    _add: function(type,st,dur) {
        if (type > 0) {
            // 休息
            if(this.supportAudio && this._nodeAudio[0]){
                this._nodeAudio[0].pause();
                this._nodeAudio[0].currentTime = 0;
            }
            var duration = isNaN(dur)?10:dur;
            this.setClock(st,duration);
        } else {
            // 继续上课
            clearTimeout(this.timer);
            this._clock.hide();
            this._node.attr('data-type', 'end').show();
            if(this.supportAudio && this._nodeAudio[0]){
                this._nodeAudio[0].play();
            }
        }
    },
    setClock: function(stime,duration){
        var now = new Date();
        var st = new Date(stime.replace(/-/g,"/"));
        var sec = parseInt((now - st) / 1000);
        var dur = duration * 60 - sec;
        if(isNaN(dur) || dur <= 0){
            return false;
        }
        this.dur = dur > 3600 ? 3600 : dur ;
        this._node.attr('data-type', 'begin').show();
        this.timeClock();
        this._clock.show();
    },
    timeClock: function(){
        var item = this;
        clearTimeout(this.timer);
        item._clockM.text('00');
        item._clockS.text('00');
        this.timer = setInterval(function(){
            if(item.dur > 0){
                var sec = item.dur;
                var s = sec % 60;
                var m = (sec - s)/60;
                s = s<10?'0'+s:s;
                m = m<10?'0'+m:m;
                item._clockM.text(m);
                item._clockS.text(s);
                item.dur--;
            }else{
                 clearTimeout(item.timer);
                 item._add(-1);
            }
        },1000);
    },
    init: function() {
        var item = this;
        this._btnBegin.click(function() {
            item._node.hide();
            if(item.supportAudio && item._nodeAudio[0]){
                item._nodeAudio[0].pause();
                item._nodeAudio[0].currentTime = 0;
            }
            if (window.isLogin && !window.isSign) {
                message.comeback && message.comeback();
            }
        });
        this._btnRelax.click(function() {
            item._node.hide();
        });
    }
};
window.Relax = Relax;
Relax.init();
});
// 增加经验积分值效果
$.expChangeEffect = function(data){
    var add_point = data.add_point || 0; //积分
    var add_exp = data.add_score || 0;  //经验
    var up_type = data.up_type || 0; //升级效果
    var fk_level = data.fk_level || 0;
    var score = data.score || 0;
    if(add_point > 0){
        integralMedalLayer((parseInt(add_point)+'积分'));
        if(typeof GIFT == 'object' && typeof GIFT.expAdd == 'function'){
            GIFT.expAdd(add_point);
        }
    }
    if(up_type && fk_level){
        var types = '';
        if (up_type == 2){
            types = 'biggrowth';
        }else if (up_type == 1) {
            types = 'smallgrowth';
        }
        $("body").GrowthLayer({
            types: types,// five|smallgrowth|biggrowth
            space: 5000, //时间间隔
            auto: true, //自动关闭
            growth: fk_level,// 等级,1,2,3
            score: score
        });
    }
}