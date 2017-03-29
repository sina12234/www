/*
 *_selectId:
 *  1、全部学员
 *  2、未点名学员
 *  3、已点名学员
 *  4、匹配特殊字符串
 *点名：
 *  1、未点名，有灰圈，un-name
 *  2、点名，红圈，f-name
 *  3、答道，什么都没有，也没有class
 */
function planStart(){
    var _getGroupUrl = "/user/group/UserList";
    var _getGroupPageSize = 500;
    var _totalOperate = $("#total_operate");
    var _groupOperate = $("#group_operate");
    var _actStudent = $("<div data-pop><div class=triangle></div><ul><li data-good>点赞</li><li data-agree>发言</li><li data-stop>停止发言</li><li data-call>点名</li><li data-forbid>禁言</li><li data-free>解除禁言</li></ul></div>");
    var _deleteDom = $('<a class="cancel-msg" title="删除消息"></a>');
	var _updatePlanExamStatusUrl = "/course.plan.updateplanexamstatus";		//更新一个习题的状态
	var _logUserPlanUrl = "/exam.2121";			//一个学生答题log
	//var _singleForbidUrl = "/message.chat.getsingleforbid";		//得到一个plan对应的单个禁言url
	var _logIssueUrl = "/exam/log/logissue";		//答完一道题写的log
	var _forbidThumb = "/assets_v2/img/gag.png";
	var _startTime = 0;		//答题开始时间
	var _issueTimeType = 0;		//计算答题时间类型，1--本地时间，2--server时间
	var _domExams = false;		//答题部分的dom
	var _dataExams = exams;		//答题数据
	var _examsTimerId = null;	//答题更新时间timerid
	var _answerLimit = 10;
	var _info = {};				//学生信息数据
	var _students = $("#students");
    var _displayStudents = new DisplayStudent(_students, 100, 10);
    var _freeList = $("#free_list");
    var _displayFreeList = new DisplayOptimize(_freeList, 50, 5);
    var _freeListIndexFlag = false;     //设置游客序号标记（为了优化）
	var _lastDate = new Date();		//聊天框上一次输入信息时间
	var _chatList = $("#chat_list");
	var _chatListCache = [];
	var _scrollFlag = true;
	var _chatWaitFlag = false;		//hover聊天区学生头像，来新消息不滚动
	var _pattern = $("#pattern");
	var _pattern2 = $("#pattern2");
	var _goodUserCache = [];
	var _goodNum = 0;
    var _studentsDisplay = $("#students_display");
    var _chat = $("#chat");
	var _oldFlag = true;
    var _partialText = {"once":200, "accu":0, "_id":0, "_id2":0};    //once--一次取的个数，accu--一个websocket累计个数，_id--初始化的文本id（进入时最大id（减1））,_id2--中间取的id限制
    var _partialTextDirection = 1;      //增加聊天方向，1--正向(录播)， 2--反向（直播）
    if(isLiving){
        _partialTextDirection = 2;
    }
	var _freeNum = 0;	//游客数量
	var _testIndex = 0;		//正在答题的索引，0为没有答题
	var _totalFeeNum = 0;		//报名学生数
	var _correctNum = 0;		//答题正确人数
	var _errorNum = 0;			//答题错误人数
	var _flagClass = null;		//是否在上课（初始化为null，开始上课为true，结束上课为false）
	var _defaultThumb = "/assets_v2/img/no-img.png";
	var _div = $("<div></div>");
    var _deleteTextList = [];  //要删除的文本的msg_id
    var _activeGroupId = 0;
    var _displayChatList;
    var _groups = {};       //群id和群信息对应关系，包括学生，群名，群学生显示dom，对应的聊天列表
    //不分组初始化学生
    var dataIds = [];
    var dataGroupIds = [];
    var groupGIds = [];
    function initAllStudent(){
        $("#group_filter").parent().hide();
        var page = 1;
        function getGroupSuccess(data){
            if(0 == data["code"]){
                var l = data.data.length;
                for(var i=0;i<l;i++){
                    var a = data.data[i];
                    var uid = a["uid"];
                    if(dataIds.indexOf(uid) < 0){
                        dataIds.push(uid);
                    var name = a["real_name"];
                    if(!name){
                        name = a["name"];
                    }
                    var thumb = filecdn+a["thumb_small"];
                    var mobile = a["mobile"];
                    var dom = $('<li data-id="'+uid+'" data-name="'+name+'" data-token="" data-display="1" data-call="0" data-hand="0" data-good="0" class="gray" ><b data-index>1</b><span data-reply class="face"><img src="'+thumb+'"></span><span class="name">'+name+'</span><span class="num-praise">赞(<span data-good>0</span>)</span><span class="name col-lg-10 name-Mleft">('+mobile+')</span><div class="other"><span data-full="full" class="full-icon icon" style="display:none;"></span><span data-full="wfull" class="w-full-icon icon" style="display:none;"></span><span data-speech="speech" class="sound-icon icon" style="display:none;"></span><span data-hand="hand" class="hand-icon icon" style="display:none;"></span><span data-micro="succeed" class="m-phone-icon icon" style="display:none;"></span></div></li>').appendTo(_students);
                    _info[uid] = {"online":false, "name":name, "free":false, "thumb":thumb, "num":0, "token":{"":[dom, null]}, "microphone":"untest", "fullscreen":0, "display":true, "answer":"", "answerTimes":0, "forbid":false, "gid":0};
                    }
                }
                _displayStudents.setStudentsIndex(0);
                if(_getGroupPageSize == data.data.length){
                    page++;
                    getGroupData();
                }else{
                    studentHoverRegister();
                    _message.getMsg();
                    setOnlineNum();
                }
            }else{
                setTimeout(getGroupData, 5000);
            }
        }
        function getGroupError(data){
            setTimeout(getGroupData, 5000);
        }
        function getGroupData(){
            var request = {"classid":class_id, "groupid":-1, "type":2, "page":page, "pagesize":_getGroupPageSize};
            $.post(_getGroupUrl, request, getGroupSuccess, "json").error(getGroupError);
            //console.log("get group data, url=["+_getGroupUrl+"] data=["+JSON.stringify(request)+"]");
        }
        getGroupData();
    }
    function initGroup(){
        _displayChatList = new ChatList(_chatList, 200, 60, function(e){
            _displayChatList.move(_activeGroupId);
        });
        var news = $("#news");
        _displayChatList.setNews($("#news"));
        $("#news").click(function(e){
            _displayChatList.moveEnd(_activeGroupId);
            _scrollFlag = false;
        });
        if(Object.keys(groups).length){
            var groupFilter = $("#group_filter");
            if(groupGIds.indexOf(0) < 0){
                groupGIds.push(0);
                groupFilter.append('<dd><a selectid="0">全部分组</a></dd>');
            }
            function initOther(){
                //设置_info
                for(var gid in _groups){
                    var a = _groups[gid];
                    for(var uid in a["students"]){
                        if(dataGroupIds.indexOf(uid) < 0){
                            dataGroupIds.push(uid);
                            var b = a["students"][uid];
                            var dom = $('<li data-id="'+uid+'" data-name="'+b["name"]+'" data-token="" data-display="1" data-call="0" data-hand="0" data-good="0" class="gray" ><b data-index>1</b><span data-reply class="face"><img src="'+filecdn+b["thumb"]+'"></span><span class="name">'+b["name"]+'</span><span class="num-praise">赞(<span data-good>0</span>)</span><span class="name col-lg-10 name-Mleft">('+b["mobile"]+')</span><div class="other"><span data-full="full" class="full-icon icon" style="display:none;"></span><span data-full="wfull" class="w-full-icon icon" style="display:none;"></span><span data-speech="speech" class="sound-icon icon" style="display:none;"></span><span data-hand="hand" class="hand-icon icon" style="display:none;"></span><span data-micro="succeed" class="m-phone-icon icon" style="display:none;"></span></div></li>').appendTo(_students);
                            var dom2 = dom.clone().appendTo(a["dom"]);
                            _info[uid] = {"online":false, "name":b["name"], "free":false, "thumb":filecdn+b["thumb"], "num":0, "token":{"":[dom, dom2]}, "microphone":"untest", "fullscreen":0, "display":true, "answer":"", "answerTimes":0, "forbid":false, "gid":gid};
                            }
                        }
                    _displayStudents.setStudentsIndex(0);
                }
                groupFilter.on("click", "a", function(e){
                    var a = parseInt($(this).attr("selectid"));
                    if(a == _activeGroupId){
                        return;
                    }
                    if(0 == _activeGroupId){
                        _students.hide();
                        _chatList.hide();
                        _totalOperate.hide();
                        _pattern.hide();
                        _pattern2.hide();
                    }else{
                        _groups[_activeGroupId]["dom"].hide();
                        _groups[_activeGroupId]["display"].hide();
                        _groupOperate.hide();
                    }
                    if(0 == a){
                        _students.show();
                        _chatList.show();
                        _totalOperate.show();
                        _pattern.show();
                        _pattern2.show();
                    }else{
                        _groups[a]["dom"].show();
                        _groups[a]["display"].show();
                        _groupOperate.show();
                    }
                    _activeGroupId = a;
                    _displayChatList.switchNews(_activeGroupId);
                    if(_displayChatList.isToEnd(_activeGroupId)){
                        _displayChatList.moveEnd(_activeGroupId);
                    }
                    setOnlineNum();
                });
                for(var gid in _groups){
                    _groups[gid]["display"].on("click", "li[data-more]", dataMoreClick);
                }
                studentHoverRegister();
            }
            function init(gids){
                var page = 1;
                var gid = gids.pop();
                var students = {};
                function setOneGroup(){
                    if(!Object.keys(students).length){
                        return;
                    }
                    if(groupGIds.indexOf(gid) < 0){
                        groupGIds.push(gid);
                        groupFilter.append('<dd><a selectid="'+gid+'">'+groups[gid]["name"]+'</a></dd>');
                    }
                    var a = {};
                    a["students"] = students;
                    a["name"] = groups[gid]["name"];
                    a["dom"] = $('<ul data-student="act" class="Student-list" style="display:none;"></ul>').appendTo(_studentsDisplay);
                    a["display"] = $('<ul data-student="act" style="overflow-y:auto; height:360px; display:none;"></ul>').appendTo(_chat);
                    a["total"] = 1;
                    a["online"] = 0;
                    var uids = {};
                    for(var i in students){
                        uids[i] = 1;
                    }
                    uids[teacherId] = 1;
                    uids[parseInt(groups[gid]["uid"])] = 1;
                    _displayChatList.addGroup(gid, a["display"], uids);
                    _displayStudents.addGroup(gid, a["dom"]);
                    playResize();
                    _groups[gid] = a;
                }
                function getGroupSuccess(data){console.log("success, data=["+JSON.stringify(data)+"]");
                    if(0 == data["code"]){
                        for(var i=0;i<data.data.length;i++){
                            var a = data.data[i];
                            var b = {};
                            if(a["name"]){
                                b["name"] = a["real_name"];
                            }else{
                                b["name"] = a["name"];
                            }
                            b["thumb"] = a["thumb_small"];
                            b["mobile"] = a["mobile"];
                            students[parseInt(a["uid"])] = b;
                        }
                        if(_getGroupPageSize == data.data.length){
                            page++;
                            getGroupData();
                        }else{
                            setOneGroup();
                            gid = gids.pop();
                            if(gid){
                                page = 1;
                                students = {};
                                getGroupData();
                            }else{
                                initOther();
                                setOnlineNum();
                                _message.getMsg();
                            }
                        }
                    }else{
                        setTimeout(getGroupData, 5000);
                    }
                }
                function getGroupError(data){
                    setTimeout(getGroupData, 5000);
                }
                function getGroupData(){
                    var request = {"classid":class_id, "groupid":gid, "type":2, "page":page, "pagesize":_getGroupPageSize};
                    $.post(_getGroupUrl, request, getGroupSuccess, "json").error(getGroupError);
                    console.log("get group data2, url=["+_getGroupUrl+"] data=["+JSON.stringify(request)+"]");
                }
                if(gid){
                    getGroupData();
                }
            };
            init(Object.keys(groups));
        }else{
            initAllStudent();
        };
    }
    //一个websocket包之后对分批文本的处理
    function partialTextDeal(){
        if(_oldFlag){   //初始化
            if(0 == _partialText._id){
                return;
            }
            if(1 == _partialTextDirection){
                _displayChatList.addDiv();
                _message.limitText(_partialText._id2, _partialText.once);
            }else{
                _partialText._id++;
                _partialText._id2 = _partialText._id;
                _message.limitText(_partialText._id2, -_partialText.once);
            }
        }else{
            if(_partialText.accu > 0){
                if(1 == _partialTextDirection){
                    _displayChatList.deleteMore();
                    _displayChatList.addMore();
                    if(_partialText.once + 2 == _chatList.children().length){
                        _displayChatList.moveStart(_activeGroupId);
                    }
                }else{
                    _displayChatList.deleteMore();
                    _displayChatList.addMore();
                    _partialText._id = _partialText._id2;
                    if(_partialText.once + 1 == _chatList.children().length){
                        _displayChatList.moveEnd(_activeGroupId);
                    }
                }
            }
            _partialText.accu = 0;
        }
    }
    function partialTextOnce(data){
        if(_oldFlag){
            if(_partialText._id < data.msg_id){
                _partialText._id = data.msg_id;
            }
        }else{
            if(data.msg_id < _partialText._id){
                _partialText.accu++;
            }
            if(1 == _partialTextDirection){
                if(data.msg_id > _partialText._id2 && data.msg_id < _partialText._id){
                    _partialText._id2 = data.msg_id;
                }
            }else{
                if(data.msg_id < _partialText._id2){
                    _partialText._id2 = data.msg_id;
                }
            }
        }
    }
    function dataMoreClick(e){
        if(1 == _partialTextDirection){
            _message.limitText(_partialText._id2, _partialText.once);
        }else{
            _message.limitText(_partialText._id2, -_partialText.once);
        }
        $(e.target).css("visibility", "hidden");
    }
    _chatList.on("click", "li[data-more]", dataMoreClick);
	function htmlEscape(text){
		_div.html(text);
		return _div.text();
	}
	function issueShow(){
		_domExams.show();
		$("#handle-area").hide();
	}
	function issueHide(){
		_domExams.hide();
		if($('#in_issue_flag').length>0){
			$("#in_issue_flag").remove();
		}
		if(_testIndex !=0){
			$('.h-hall-test').append('<p id="in_issue_flag">答题卡发放中</p>');
		}
		$("#handle-area").show();
	}
	//设置在线学生数（不包括多点登陆）
	function setOnlineNum(){
        var a = 0;
        var b = 0;
        for(var u in _info){
            var o = _info[u];
            if(_activeGroupId == o.gid || 0 == _activeGroupId){
                b++;
                if(o.online){
                    a++;
                }
            }
        }
        $("#online_num").text(a);
        $("#fee_num").text(b);
	}
	//设置游客总数
	function setFreeNum(){
		$("#free_num").text(_freeNum);
	}
	//设置灰色
	function setGray(dom, flag){
		if(flag){
			dom.addClass("gray");
		}else{
			dom.removeClass("gray");
		}
	}
	//设置点名状态。status目前可以有三个值，为：uncall--未点名，unreply--已点名，但未答道，reply--答道了，其它值去掉所有class
	function setCallStatus(dom, status){
		if("uncall" == status){
			dom.find("span[data-reply]").addClass("un-name").removeClass("f-name");
		}else if("unreply" == status){
			dom.find("span[data-reply]").addClass("f-name").removeClass("un-name");
		}else if("reply" == status){
			dom.find("span[data-reply]").removeClass("un-name").removeClass("f-name");
		}else{
			dom.find("span[data-reply]").removeClass("un-name").removeClass("f-name");
		}
		if("reply" == status){
			dom.attr("data-call", 1);
		}else{
			dom.attr("data-call", 0);
		}
	}
	//得到一个游客dom（新创建的）
	function getFreeDom(u, name, thumb){
		var dom;
		if(0 == u){		//未登录
			dom = $('<li data-id="'+u+'"><b>'+_freeNum+'</b><span class="face"><img src="'+thumb+'"></span><span class="name">'+name+'</span></li>');
		}else{
			dom = $('<li data-id="'+u+'"><b>'+_freeNum+'</b><span class="face"><img src="'+thumb+'"></span><span class="name">'+name+'</span></li>');
		}
		return dom;
	}
	//去掉一个报名学生
	function deleteOneStudent(u){
	}
	//增加一个报名学生
	function addOneStudent(data){
        if(_groups){
            initGroup();
        }else{
            initAllStudent();
        }
	}
	//响应文本消息
	function textResponse(data){
		var thumb = filecdn + data.uf_t;
		var name = data.uf_n;
		var u = data.uf;
		var tokenStr = "";
		if(data.uff){
			tokenStr = ' data-token="'+data.uff+'"';
		}
		var dom;
		var a = data.content.replace(_flag2urlReg, flag2img);
		var displayTime = "";
        if(_partialText._id <= data.msg_id){
            var t = new Date();
            var minutes = t.getMinutes();
            if(minutes < 10){
                minutes = '0'+minutes;
            }
            if(t - _lastDate > 60000){
                displayTime = "<li data-uid='"+userId+"'><p>"+t.getHours()+":"+minutes+"</p></li>";
            }
            _lastDate = t;
        }
		if(u == userId){
			dom = '<li data-uid="'+u+'" class="self" data-msg="'+data.msg_id+'">'+displayTime+'<div class="s-info"><span class="face"><img src="'+thumb+'"></span>'+name+'</div><div class="c-main"><div class="angle"></div><div class="chat-c"><p>'+a+'</p></div></div></li>';
		}else{
			dom = '<li data-uid="'+u+'" data-id="'+u+'" '+tokenStr+' data-msg="'+data.msg_id+'">'+displayTime+'<div class="s-info"><span class="face"><img src="'+thumb+'"></span>'+name+'</div><div class="c-main"><div class="angle"></div><div class="chat-c"><p>'+a+'</p></div></div></li>';
		}
		_chatListCache.push(dom);
        if(_partialText._id <= data.msg_id){
            if(u == userId){
                _scrollFlag = true;
            }else{
                _displayChatList.modifyNewsNum(u, 1);
            }
        }
	}
    //删除一个聊天节点
    function deleteText(msg_id){
        _displayChatList.deleteMsg(msg_id);
    }
    //清除要删除的聊天节点
    function deleteTexts(){
        if(0 == _deleteTextList.length){
            return;
        }
        for(var i=0;i<_deleteTextList.length;i++){
            deleteText(_deleteTextList[i]);
        }
        _deleteTextList = [];
    }
	//响应点赞消息（修改点赞数，重新排序）
	function goodResponse(data){
		var u = data.user_id;
		if(u in _info && !_info[u]["free"]){
			var o = _info[u];
			o.num += parseInt(data.c);
			for(var token in o["token"]){
				var dom = o["token"][token][0];
				var dom2 = o["token"][token][1];
                _displayStudents.goodResponseToken(u, token, o.num, o.online, dom, dom2, o.gid);
			}
		}
	}
    //停止发言后的显示
    function cancelSpeech(dom, dom2, gid){
        var u = dom.attr("data-id");
        var online = _info[u]["online"];
        _displayStudents.cancelSpeech(dom, dom2, gid, online);
        Player.hideSpeakingTips();
    }
    //把一个dom显示成发言状态（包括去除之前的状态）
    function displaySpeech(dom, dom2, gid){	//肯定在线
        _displayStudents.displaySpeech(dom, dom2, gid);
        Player.showSpeakingTips(_info[_displayStudents.speechUser]["name"], location.protocol+_info[_displayStudents.speechUser]["thumb"]);
    }
    //增加一个同名报名学生
    function addSameUser(u, token, gid){
        var v = _info[u]["token"];
        var t = Object.keys(v);
        var dom = v[t[0]][0].clone();
        function f(dom){
            setCallStatus(dom, "uncall");
            dom.attr("data-token", token);
            dom.attr("data-display", "0");
            dom.attr("data-call", "0");
            dom.attr("data-hand", "0");
            dom.find("div > span").hide();
        }
        f(dom);
        var dom2 = null;
        if(gid){
            dom2 = dom.clone();
            f(dom2);
        }
        v[token] = [dom, dom2];
        _displayStudents.setDisplay(dom, dom2, gid, true, true);
    }
	//处理一个点赞消息
	function goodFunc(data, quick){
		if(quick){
			goodResponse(data);
		}else{
			_goodUserCache.push(data);
		}
	}
	//批量处理文本和点赞消息缓存
	function inputScroll(){
		if(_chatListCache.length){
            var dom = _chatListCache.join('');
            if(!_scrollFlag && 0 == _partialText.accu){
                if(_displayChatList.isToEnd(_activeGroupId)){
                    _scrollFlag = true;
                }
            }
            if(0 == _partialText.accu){
                _displayChatList.appends(dom);
            }else if(1 == _partialTextDirection){
                _displayChatList.beforeDivs(dom);
            }else{
                _displayChatList.prepends(dom);
            }
            _chatListCache = [];
            if(!_scrollFlag){
                _displayChatList.move(_activeGroupId);
            }else{
                _displayChatList.moveEnd(_activeGroupId);
                _scrollFlag = false;
            }
			if(!_scrollFlag || _chatWaitFlag){
                _displayChatList.showNews(_activeGroupId);
			}
		}
        deleteTexts();
		if(_goodUserCache.length){
			for(var i=0;i<18;i++){
				var data = _goodUserCache.shift();
				goodResponse(data);
				if(0 == _goodUserCache.length){
					break;
				}
			}
		}
		if(_goodUserCache.length > 0){
			setTimeout(inputScroll, 10);
		}
	}
	//显示系统消息
	function systemInfo(content, u){
		if(!_oldFlag){
			var dom = '<li class="teacher" data-uid="'+u+'"><p><span class="cBlue">'+content+'</span></p></li>';
			_chatListCache.push(dom);
            _displayChatList.modifyNewsNum(u, 1);
			inputScroll();
		}
	}
	//处理上线
	function dealOnline(data){
        var u = data.uf;
        var token = data.uff;
        var thumb = _defaultThumb;
        if(data.uf_t){
            thumb = filecdn + data.uf_t;
        }
		if(u in _info){
			if(token in _info[u]["token"]){
				return;
			}
			var o = _info[u];
			var v = o["token"];
			if(o["free"]){
				var keys = Object.keys(v);
				var dom = v[keys[0]].clone();
				v[token] = dom;
				_freeNum++;
				setFreeNum();
				//$("#free_list").append(dom);
                _displayFreeList.append(dom);
				dom.find("b").text(_freeNum);
			}else{
				if(o["online"]){
					addSameUser(u, token, o.gid);
				}else{
					var dom = v[""][0];
                    var dom2 = v[""][1];
					delete v[""];
					v[token] = [dom, dom2];
					o["online"] = true;
					dom.attr("data-token", token);
					setGray(dom, false);
					setCallStatus(dom, "uncall");
					dom.attr("data-display", "0");
                    if(dom2){
                        dom2.attr("data-token", token);
                        setGray(dom2, false);
                        setCallStatus(dom2, "uncall");
                        dom2.attr("data-display", "0");
                    }
                    _displayStudents.deleteDom(dom, dom2, o.gid);
					_displayStudents.setDisplay(dom, dom2, o.gid, true, true);
                    //下面需要修改
					setOnlineNum();
				}
			}
			systemInfo(o["name"]+" 上线", teacherId);
		}else{		//新游客
			if(teacherId != u){		//过滤掉教师id
				_freeNum++;
				var dom = getFreeDom(u, data.uf_n, thumb);
				var v = {};
				v[token] = dom;
				_info[u] = {"online":true, "name":data.uf_n, "free":true, "thumb":thumb, "token":v};
				setFreeNum();
                _displayFreeList.append(dom);
				systemInfo("游客上线", teacherId);
			}
		}
	}
	//一个报名学生下线
	function offlineUser(u, token, gid){
		var v = _info[u]["token"];
		var dom = v[token][0];
		var dom2 = v[token][1];
		if(u == _displayStudents.speechUser && token == _displayStudents.speechToken){
			_message.stop(u, token);
			cancelSpeech(dom, dom2, gid);
		}else if(dom.attr("data-hand")>0){
			_message.refuse(u, token);
			_displayStudents.cancelRaisehand(dom, dom2, gid, false);
		}
		var keys = Object.keys(v);
		if(keys.length > 1){
            _displayStudents.offlineMore(dom, dom2, gid);
			delete v[token];
		}else{
            _displayStudents.offlineOne(dom, dom2, gid);
			delete v[token];
			v[""] = [dom, dom2];
			_info[u]["online"] = false;
			setOnlineNum();
			setGray(dom, true);
			setCallStatus(dom, "");
            if(dom2){
                setGray(dom2, true);
                setCallStatus(dom2, "");
            }
		}
	}
	//处理下线
	function dealOffline(u, token){
		if(u in _info && token in _info[u]["token"]){
			var v = _info[u]["token"];
			var name = _info[u]["name"];
            var gid = _info[u]["gid"];
			if(_info[u]["free"]){
                _displayFreeList._delete(v[token]);
				delete v[token];
				var keys = Object.keys(v);
				if(0 == keys.length){
					delete _info[u];
				}
				_freeNum--;
				setFreeNum();
                if(!_freeListIndexFlag){
                    if(_freeList.children().length > 500){
                        _freeListIndexFlag = true;
                        setTimeout(function(){
                            _freeListIndexFlag = false;
                            _freeList.children().each(function(i, elem){
                                $(this).find("b").text(i+1);
                            });
                        }, 2000);
                    }else{
                        _freeList.children().each(function(i, elem){
                            $(this).find("b").text(i+1);
                        });
                    }
                }
			}else{
				offlineUser(u, token, gid);
			}
			systemInfo(name+" 下线", teacherId);
		}
	}
	//设置麦克风状态
	function setMicrophone(u, token, status){
		if(u in _info && !_info[u]["free"] && _info[u]["online"] && token in _info[u]["token"]){
            for(var i=0;i<2;i++){
                var dom = _info[u]["token"][token][i];
                if(dom){
                    dom.find("span[data-micro]").each(function(i, elem){
                        if($(this).attr("data-micro") == status){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    });
                }
            }
		}
	}
	//设置全屏状态
	function setFullscreen(u, token, intVal){
		if(u in _info && !_info[u]["free"]  && _info[u]["online"] && token in _info[u]["token"]){
			var status = "";
			if(1 == intVal){
				status = "wfull";
			}else if(2 == intVal){
				status = "full";
			}
            for(var i=0;i<2;i++){
                var dom = _info[u]["token"][token][i];
                if(dom){
                    dom.find("span[data-full]").each(function(i, elem){
                        if($(this).attr("data-full") == status){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    });
                }
            }
		}
	}
	function getExamIndexByPlanExam(planExamId){
		for(var i in _dataExams){
			if(_dataExams[i].plan_exam_id == planExamId){
				return parseInt(i);
			}
		}
		return -1;
	}
	//显示指定题目页面
	function displayIssueIndex(index){
		index++;
		var oldIndex = _domExams.attr("data-index");
		if(oldIndex != index){
			_domExams.find("div[data-index="+oldIndex+"]").hide();
			_domExams.find("div[data-index="+index+"]").show();
			_domExams.find("a[data-index="+oldIndex+"]").removeClass("curr");
			_domExams.find("a[data-index="+index+"]").addClass("curr");
			_domExams.attr("data-index", index);
		}
	}
	//响应发布题型消息
	function getDateByLu(lu){
		var t = new Date();
		var a = lu.split(" ");
		var days = a[0].split("-");
		var times = a[1].split(":");
		t.setFullYear(days[0]);
		t.setMonth(days[1]-1);
		t.setDate(days[2]);
		t.setHours(times[0]);
		t.setMinutes(times[1]);
		t.setSeconds(times[2]);
		t.setMilliseconds(0);
		return t;
	}
	function dealIssueAsk(content, lu){
		if(!_domExams || _testIndex){
			return;
		}
		var issue;
		try{
			issue = JSON.parse(content);
		}catch(e){
			return;
		}
		if("id" in issue){
			var index = getExamIndexByPlanExam(issue["id"]);
			if(index >= 0){
				var dom = _domExams.find("div[data-index="+(index+1)+"]").first();
				if(dom.length){
					_issueTimeType = 2;
					issueAskStatus(dom, (getDateByLu(lu)).valueOf());
					//_domExams.show();
					issueShow();
					displayIssueIndex(index);
				}
			}
		}
	}
	//响应公布答案消息
	function dealIssuePublish(content){
		if(!_domExams || !_testIndex){
			return;
		}
		var issue;
		try{
			issue = JSON.parse(content);
		}catch(e){
			return;
		}
		if("id" in issue){
			var index = getExamIndexByPlanExam(issue["id"]);
			if(index >= 0){
				var dom = _domExams.find("div[data-index="+(index+1)+"]").first();
				if(dom.length && index+1 == _testIndex){
					issuePublishStatus(dom);
					displayIssueIndex(index);
					//_domExams.show();
					issueShow();
				}
			}
		}
	}
		//响应取消答题消息
	function dealIssueCancel(content){
		if(!_domExams || !_testIndex){
			return;
		}
		var issue;
		try{
			issue = JSON.parse(content);
		}catch(e){
			return;
		}

		if("id" in issue){
			var index = getExamIndexByPlanExam(issue["id"]);
			if(index >= 0){
				var dom = _domExams.find("div[data-index="+(index+1)+"]").first();
				if(dom.length && index+1 == _testIndex){
					//dom.html(dom1.html());
					//_testIndex = 0;
					//dom.show();
					var t;

					t = new Date;

					t = parseFloat((t.valueOf() - _startTime)/1000);
					issueCancelStatus(dom);
				}
			}
		}
	}

	function dealIssueAnswer(u, token, answer, lu){
		if(!(u in _info) || _info[u]["free"] || _info[u]["answerTimes"]>=_answerLimit){
			return;
		}
		if(!_domExams || 0 == _testIndex){
			return;
		}
		try{
			answer = JSON.parse(answer);
		}catch(e){
			return;
		}
		if(!("id" in answer) || !("answer" in answer) || 0 == answer["answer"].length){
			return;
		}
		answer.answer = answer.answer.toLowerCase();
		var a = _dataExams[_testIndex-1];
		if(answer["id"] != a.plan_exam_id){
			return;
		}
		var o = _info[u];
		o["answerTimes"]++;
		if(o["answer"] == answer["answer"]){
			return;
		}
		var dom = _domExams.find("div[data-index="+_testIndex+"]").first();
		if(o["answer"]){	//原来有答案，要除去效果
			if(o["answer"] == a.answer){
				_correctNum--;
				var ul = dom.find("ul[data-list-head]");
				ul.find("li[data-id="+u+"]").remove();
				ul.children().each(function(i, elem){
					$(this).text((parseInt(i)+1)+"."+$(this).attr("data-name"));
				});
			}else{
				_errorNum--;
			}
			if(1 == a["type"]){		//单选
				var d = dom.find("div[data-list="+o["answer"]+"]");
				var ul = d.find("ul");
				ul.find("li[data-id="+u+"]").remove();
				ul.children().each(function(i, elem){
					$(this).text((parseInt(i)+1)+"."+o["name"]);
				});
				d.find("i[data-num]").text(ul.children().length);
			}
		}
		o["answer"] = answer["answer"];
		var t;
		if(2 == _issueTimeType){
			t = getDateByLu(lu);
		}else{
			t = new Date;
		}
		t = parseFloat((t.valueOf() - _startTime)/1000);
		o["answerDuration"] = t;
		if(o["answer"] == a.answer){	//新答案正确
			_correctNum++;
			dom.find("ul[data-list-head]").append("<li data-id='"+u+"' data-name='"+o["name"]+"' data-duration='"+t+"'>"+_correctNum+"."+o["name"]+"</li>");
		}else{
			_errorNum++;
		}
		dom.find("i[data-stat=correct]").text(_correctNum);
		dom.find("i[data-stat=top]").text(_correctNum);
		dom.find("i[data-stat=error]").text(_errorNum);
		dom.find("i[data-stat=unanswer]").text(_totalFeeNum-_correctNum-_errorNum);
		if(1 == a["type"]){		//单选
			var d = dom.find("div[data-list="+o["answer"]+"]");
			if(d.length){
				var ul = d.find("ul");
				var num = ul.children().length + 1;
				d.find("i[data-num]").text(num);
				ul.append("<li data-id='"+u+"'>"+num+"."+o["name"]+"</li>");
			}
		}
	}
	function msgDeal(data){
		if(!data){
			inputScroll();
            partialTextDeal();
            if(_oldFlag){
                _oldFlag = false;
                _displayStudents.initIndex();
                _displayStudents.setStudentsIndex(0);
            }
			return;
		}
		var u = data.user_from_id;
		if(MessageType.text == data.type || MessageType.reply_text == data.type){
            partialTextOnce(data);
            if(!_oldFlag){
			    textResponse(data);
            }
		}else if(MessageType.ask_cancel == data.type){
			if(u in _info && !_info[u]["free"] && _info[u]["online"] && data.user_from_token in _info[u]["token"]){
				var dom = _info[u]["token"][data.user_from_token];
                var gid = _info[u]["gid"];
				if("ask" == data.content){
					if(0 == dom[0].attr("data-hand")){
						if(_displayStudents.speechUser == u && _displayStudents.speechToken == data.user_from_token){
							;
						}else{
                            _displayStudents.raiseHand(dom[0], dom[1], gid);
						}
					}
				}else if("cancel" == data.content){
					if(u == _displayStudents.speechUser && data.user_from_token == _displayStudents.speechToken){
						cancelSpeech(dom[0], dom[1], gid);
						systemInfo(_info[u]["name"]+" 停止发言", teacherId);
					}else if(dom.attr("data-hand") > 0){
						_displayStudents.cancelRaiseHand(dom[0], dom[1], gid, _info[u]["online"]);
					}
				}
			}
		}else if(MessageType.agree_refuse == data.type){	//自己或者别的老师发的消息（这里的操作都是对应别的老师发的信息）
			var u2 = data.user_to_id;
			if(u2 in _info && !_info[u2]["free"] && _info[u2]["online"] && data.user_to_token in _info[u2]["token"]){
				if("agree" == data.content || "asking" == data.content){	//让学生发言
					if(u2 == _displayStudents.speechUser && data.user_to_token == _displayStudents.speechToken){
						;
					}else{
						if(0 != _displayStudents.speechUser){
                            var dom = _info[_displayStudents.speechUser]["token"][_displayStudents.speechToken];
							cancelSpeech(dom[0], dom[1], _info[_displayStudents.speechUser]["gid"]);
						}
                        var o = _info[u2];
						displaySpeech(o.token[data.user_to_token][0], _o.token[data.user_to_token][1], o.gid);
					}
				}else if("refuse" == data.content || "stop" == data.content){
					var dom = _info[u2]["token"][data.user_to_token];
                    var gid = _info[u2][gid];
					if(u2 == _displayStudents.speechUser && data.user_to_token == _displayStudents.speechToken){
						cancelSpeech(dom[0], dom[1], gid);
					}else if(dom.attr("data-hand") > 0){
						_displayStudents.cancelRaiseHand(dom[0], dom[1], gid, _info[u]["online"]);
					}
				}
			}
		}else if(MessageType.call == data.type){
			var u2 = data.user_to_id;
			if(u2 in _info && !_info[u2]["free"] && _info[u2]["online"] && data.user_to_token in _info[u2]["token"]){
				var dom = _info[u2]["token"][data.user_to_token];
                for(var i=0;i<2;i++){
                    var a = dom[i];
                    if(a){
                        setCallStatus(a, "unreply");
                    }
                }
			}
		}else if(MessageType.reply == data.type){
			if(u in _info && !_info[u]["free"] && _info[u]["online"] && data.user_from_token in _info[u]["token"]){
				var dom = _info[u]["token"][data.user_from_token];
                for(var i=0;i<2;i++){
                    var a = dom[i];
                    if(a){
                        setCallStatus(a, "reply");
                    }
                }
				systemInfo(_info[u]["name"]+" 答到", teacherId);
			}
		}else if(MessageType.good == data.type){
			var u2 = data.ut;
			if(u2 in _info){
				goodFunc(data, false);
				if(!_oldFlag){
					systemInfo("给 "+data.ut_n+" 点赞", teacherId);
				}
			}
		}else if(MessageType.on_off_line == data.type){
			if(u == userId && data.user_from_token == userFlag){	//去掉教师自己的信息
				;
			}else{
				if("online" == data.content){
					dealOnline(data);
				}else if("offline" == data.content){
                    if(!_oldFlag){
                        dealOffline(u, data.user_from_token);
                    }
				}
			}
		}else if(MessageType.start_close == data.type){
			if("start" == data.content){
				if(true !== _flagClass){
					$("#start-btn").hide();
					$("#continue-btn").hide();
					$("#stop-btn").show();
                    $("#player").show();
                    $("#player_2").hide();
					_flagClass = true;
					systemInfo("开始上课", teacherId);
				}
			}else if("close" == data.content){
				if(false !== _flagClass){
					$("#start-btn").hide();
					$("#continue-btn").show();
					$("#stop-btn").hide();
					//try{Player.close()}catch(e){}
					_flagClass = false;
					systemInfo("结束上课", teacherId);
				}
			}
		}else if(MessageType.pattern == data.type){
			if("normal" == data.content){
				_pattern.attr("data-pattern", "normal");
				_pattern.find("[data-pattern=normal]").show();
				_pattern.find("[data-pattern=notalk]").hide();
				_pattern2.attr("data-pattern", "normal");
				_pattern2.find("[data-pattern=normal]").show();
				_pattern2.find("[data-pattern=reply]").hide();
			}else if("reply" == data.content){
				_pattern.attr("data-pattern", "normal");
				_pattern.find("[data-pattern=normal]").show();
				_pattern.find("[data-pattern=notalk]").hide();
				_pattern2.attr("data-pattern", "reply");
				_pattern2.find("[data-pattern=normal]").hide();
				_pattern2.find("[data-pattern=reply]").show();
			}else if("notalk" == data.content){
				_pattern.attr("data-pattern", "notalk");
				_pattern.find("[data-pattern=normal]").hide();
				_pattern.find("[data-pattern=notalk]").show();
				_pattern2.attr("data-pattern", "normal");
				_pattern2.find("[data-pattern=normal]").show();
				_pattern2.find("[data-pattern=reply]").hide();
			}
		}else if(MessageType.microphone_result == data.type){
			setMicrophone(u, data.user_from_token, data.content);
		}else if(MessageType.fullscreen == data.type){
			setFullscreen(u, data.user_from_token, parseInt(data.content));
		}else if(MessageType.issue_ask == data.type){
			dealIssueAsk(data.content, data.lu);
		}else if(MessageType.issue_publish == data.type){
			dealIssuePublish(data.content);
		}else if(MessageType.issue_cancel == data.type){
			dealIssueCancel(data.content);
		}else if(MessageType.issue_answer == data.type){
			dealIssueAnswer(u, data.user_from_token, data.content, data.lu);
		}else if(MessageType.delete_text == data.type){
            if(_chatListCache.length){
                _deleteTextList.push(data.content);
            }else{
                deleteText(data.content);
            }
		}else if(MessageType.single_notalk == data.type){
			var u2 = data.user_to_id;
			if(u2 in _info){
				if("forbid" == data.content){
					_info[u2]["forbid"] = true;
					for(var token in _info[u2]["token"]){
                        for(var i=0;i<2;i++){
                            var dom = _info[u2]["token"][token][i];
                            if(dom){
                                dom.find("img").attr("src", _forbidThumb);
                            }
                        }
					}
				}else{
					_info[u2]["forbid"] = false;
					for(var token in _info[u2]["token"]){
                        for(var i=0;i<2;i++){
                            var dom = _info[u2]["token"][token][i];
                            if(dom){
                                dom.find("img").attr("src", _info[u2]["thumb"]);
                            }
                        }
					}
				}
			}
		}else if(MessageType.modify_student == data.type){
			if("add" == data.content){
                addOneStudent(data)
			}else if("delete" == data.content){
				deleteOneStudent(u);
			}else{
				console.log("!!!error for modify student content=["+data.content+"]");
			}
		}else if(MessageType.camera == data.type){
			if("open" == data.content){
                Player.startupCameraPlayer();
			}else if("close" == data.content){
                Player.closeCameraPlayer();
			}else{
				console.log("!!!error for modify student content=["+data.content+"]");
			}
		}else{
			;
		}
	}
	//全体点名
	$("#call_all").click(function(e){
		for(var u in _info){
			var o = _info[u];
			if(!o["free"] && o.online){
				for(var token in o["token"]){
		            _message.call(u, token);
                    for(var i=0;i<2;i++){
                        var dom = o["token"][token][i];
                        if(dom){
                            setCallStatus(dom, "unreply");
                        }
                    }
				}
			}
		}
		//_message.call(0, "");
		systemInfo("全体点名", teacherId);
	});
	//全体测麦
	$("#microphone_all").click(function(e){
		for(var u in _info){
			var o = _info[u];
			if(!o["free"] && o.online){
				for(var token in o["token"]){
					setMicrophone(u, token, "");
				}
			}
		}
		_message.microphone_test(0, "");
		systemInfo("全体测麦", teacherId);
	});
	//全体点赞
	$("#good_all").click(function(e){
		for(var u in _info){
			var o = _info[u];
			if(!o["free"] && o.online){
                clickGood(u);
			}
		}
		//clickGood(0);
		systemInfo("全体点赞", teacherId);
	});
	//组内点名
	$("#call_group").click(function(e){
        if(!_activeGroupId){
            return;
        }
		for(var u in _groups[_activeGroupId]["students"]){
			var o = _info[u];
			if(o.online){
				for(var token in o["token"]){
		            _message.call(u, token);
                    for(var i=0;i<2;i++){
                        var dom = o["token"][token][i];
                        if(dom){
                            setCallStatus(dom, "unreply");
                        }
                    }
				}
			}
		}
		//_message.call(0, "");
		systemInfo("组内点名", teacherId);
	});
	//组内测麦
	$("#microphone_group").click(function(e){
        if(!_activeGroupId){
            return;
        }
		for(var u in _groups[_activeGroupId]["students"]){
			var o = _info[u];
			if(!o["free"] && o.online){
				for(var token in o["token"]){
					setMicrophone(u, token, "");
                    _message.microphone_test(u, token);
				}
			}
		}
		systemInfo("组内测麦", teacherId);
	});
	//组内点赞
	$("#good_group").click(function(e){
        if(!_activeGroupId){
            return;
        }
		for(var u in _groups[_activeGroupId]["students"]){
			var o = _info[u];
			if(!o["free"] && o.online){
                clickGood(u);
			}
		}
		//clickGood(0);
		systemInfo("组内点赞", teacherId);
	});
	//点赞动作之后的活动
	function clickGood(u){
		_message.good(u);
	}
	//在线学生hover及相关动作
    function studentHoverRegister(){
        $("[data-student=act]").on("mouseenter", "span > img", function(e){
            var dom = $(this).parents("li[data-id]").first();
            if(_chatList == dom.parent().attr("id")){
                _chatWaitFlag = true;
            }
            var u = dom.attr("data-id");
            if(u in _info && !_info[u]["free"] && _info[u]["online"]){
                var token = dom.attr("data-token");
                if(_flagClass && token && token in _info[u]["token"]){
                    if(_displayStudents.speechUser == u && _displayStudents.speechToken == dom.attr("data-token")){
                        _actStudent.find("[data-stop]").show();
                        _actStudent.find("[data-agree]").hide();
                    }else{
                        _actStudent.find("[data-stop]").hide();
                        _actStudent.find("[data-agree]").show();
                    }
                }else{
                    _actStudent.find("[data-stop]").hide();
                    _actStudent.find("[data-agree]").hide();
                }
                if(_info[u]["forbid"]){
                    _actStudent.find("[data-forbid]").hide();
                    _actStudent.find("[data-free]").show();
                }else{
                    _actStudent.find("[data-forbid]").show();
                    _actStudent.find("[data-free]").hide();
                }
                dom.append(_actStudent);
                if(dom.position().top > dom.parent().height() / 2){
                    _actStudent.addClass("divhover-up").removeClass("divhover");
                }else{
                    _actStudent.addClass("divhover").removeClass("divhover-up");
                }
            }
        }).on("mouseleave", "li[data-id]", function(e){
            $(this).find("[data-pop]").remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-good]", function(e){		//点赞
            var u = $(this).parents("[data-id]").first().attr("data-id");
            clickGood(u);
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-agree]", function(e){		//允许发言或者asking
            var dom = $(this).parents("[data-id]").first();
            var u = dom.attr("data-id");
            var token = dom.attr("data-token");
            if(u in _info && _info[u]["online"] && token in _info[u]["token"]){
                if(u == _displayStudents.speechUser && token == _displayStudents.speechToken){
                    ;
                }else{
                    if(_displayStudents.speechUser > 0){
                        _message.stop(_displayStudents.speechUser, _displayStudents.speechToken);
                        cancelSpeech(_info[_displayStudents.speechUser]["token"][_displayStudents.speechToken]);
                    }
                    dom = _info[u]["token"][token];
                    if(dom[0].attr("data-hand") > 0){
                        _message.agree(u, token);
                        systemInfo("老师同意 "+_info[u]["name"]+" 发言", teacherId);
                    }else{
                        _message.asking(u, token);
                        systemInfo("老师要求 "+_info[u]["name"]+" 发言", teacherId);
                    }
                    displaySpeech(dom[0], dom[1], _info[u]["gid"]);
                }
            }
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-stop]", function(e){	//停止发言
            var dom = $(this).parents("[data-id]").first();
            var u = dom.attr("data-id");
            var token = dom.attr("data-token");
            if(_displayStudents.speechUser == u && _displayStudents.speechToken == token){
                dom = _info[u]["token"][token];
                cancelSpeech(dom[0], dom[1], _info[u]["gid"]);
                _message.stop(u, token);
                systemInfo("老师停止 "+_info[u]["name"]+" 发言", teacherId);
            }
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-forbid]", function(e){		//禁言
            var u = $(this).parents("[data-id]").first().attr("data-id");
            if(u in _info){
                _info[u]["forbid"] = true;
                _message.single_notalk(u);
                for(var token in _info[u]["token"]){
                    for(var i=0;i<2;i++){
                        var dom = _info[u]["token"][token][i];
                        if(dom){
                            dom.find("img").attr("src", _forbidThumb);
                        }
                    }
                }
                systemInfo("老师对 "+_info[u]["name"]+" 禁言", teacherId);
            }
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-free]", function(e){		//解除禁言
            var u = $(this).parents("[data-id]").first().attr("data-id");
            if(u in _info){
                _info[u]["forbid"] = false;
                _message.single_free(u);
                for(var token in _info[u]["token"]){
                    for(var i=0;i<2;i++){
                        var dom = _info[u]["token"][token][i];
                        if(dom){
                            dom.find("img").attr("src", _info[u]["thumb"]);
                        }
                    }
                }
                systemInfo("老师对 "+_info[u]["name"]+" 解除禁言", teacherId);
            }
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "div[data-pop] li[data-call]", function(e){		//点名
            var u = $(this).parents("[data-id]").first().attr("data-id");
            var token = $(this).parents("[data-id]").first().attr("data-token");
            if(u in _info && token in _info[u]["token"]){
                for(var i=0;i<2;i++){
                    var dom = _info[u]["token"][token][i];
                    if(dom){
                        setCallStatus(dom, "unreply");
                    }
                }
                _message.call(u, token);
                systemInfo("老师对 "+_info[u]["name"]+" 点名", teacherId);
            }
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
        }).on("click", "span[class=num-praise]", function(e){		//点赞
            var u = $(this).parents("[data-id]").first().attr("data-id");
            if(u in _info && !_info[u]["free"] && _info[u]["online"]){
            clickGood(u);
            $(this).parents("div[data-pop]").first().remove();
            _chatWaitFlag = false;
            }
        });
    }
    _chat.on("mouseenter", "div.chat-c", function(e){
        $(this).append(_deleteDom);
    }).on("mouseleave", "div.chat-c", function(e){
        _deleteDom.detach();
    });
    _deleteDom.on("click", function(e){
        var p = $(this).parents("[data-msg]");
        if(p.length){
            var a = p.first();
            _deleteDom.detach();
            var msg_id = a.attr("data-msg");
            a.remove();
            _message.delete_text(msg_id);
        }
    });
	//重新显示所有报名的学生
	function refreshStudents(){
		for(var u in _info){
			if(!_info[u]["free"]){
				var online = _info[u]["online"];
				for(var token in _info[u]["token"]){
                    var dom = _info[u]["token"][token];
					_displayStudents.setDisplay(dom[0], dom[1], _info[u]["gid"], online, false);
				}
			}
		}
		_displayStudents.setStudentsIndex(0);
	}
	(function(){
		//学生选择过滤器动作
		$("#student_filter").on("click", "a", function(e){
			_displayStudents.selectId = $(this).attr("selectid");
			refreshStudents();
		});
		//聊天区模式控制动作
		_pattern.click(function(e){
			if("normal" == $(this).attr("data-pattern")){
				$(this).attr("data-pattern", "notalk");
				$(this).find("[data-pattern=normal]").hide();
				$(this).find("[data-pattern=notalk]").show();
				_pattern2.attr("data-pattern", "normal");
				_pattern2.find("[data-pattern=normal]").show();
				_pattern2.find("[data-pattern=reply]").hide();
				_message.pattern_notalk();
				systemInfo("进入禁言模式", teacherId);
			}else{
				$(this).attr("data-pattern", "normal");
				$(this).find("[data-pattern=normal]").show();
				$(this).find("[data-pattern=notalk]").hide();
				_message.pattern_normal();
				systemInfo("取消禁言模式", teacherId);
			}
		});
		_pattern2.click(function(e){
			if("normal" == $(this).attr("data-pattern")){
				$(this).attr("data-pattern", "reply");
				$(this).find("[data-pattern=normal]").hide();
				$(this).find("[data-pattern=reply]").show();
				_pattern.attr("data-pattern", "normal");
				_pattern.find("[data-pattern=normal]").show();
				_pattern.find("[data-pattern=notalk]").hide();
				_message.pattern_reply();
				systemInfo("老师开启内容隐藏功能", teacherId);
			}else{
				$(this).attr("data-pattern", "normal");
				$(this).find("[data-pattern=normal]").show();
				$(this).find("[data-pattern=reply]").hide();
				_message.pattern_normal();
				systemInfo("内容隐藏功能已关闭", teacherId);
			}
		});
		//发送新消息
		function addMsg(e){
			var textarea = $("#chat_input");
			var v = textarea.html();
			v2 = v.replace(_url2flagReg, img2flag);
			v2 = v2.replace(/<.*?>/g, "");
			v2 = v2.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
			v2 = v2.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");

			if(!v2){
				return;
			}
			if(v2.length > 200){
				//alert("输入内容过长，请删除部分内容");
				_displayChatList.append('<div class="teacherdz" style="line-height:30px;color:#f00;float:right;padding:10px 10px 0px 10px;"><p>输入内容过长，请删除部分内容!</p></div><div class="clear"></div>');
                _displayChatList.moveEnd(_activeGroupId);
				_scrollFlag = true;
				return;
			}
			var callback = function(msg_id){
				var t = new Date();
				var displayTime = "";
				if(t - _lastDate > 60000){
					displayTime = "<p>"+t.getHours()+":"+t.getMinutes()+"</p>";
				}
				_lastDate = t;
				var dom = '<li class="self" data-msg="'+msg_id+'">'+displayTime+'<div class="s-info"><span class="face"><img src="'+teacherThumb+'"></span>'+teacherName+'</div><div class="c-main"><div class="angle"></div><div class="chat-c"><p>'+v+'</p></div></div></li>';
                _displayChatList.append(dom);
                _displayChatList.moveEnd(_activeGroupId);
				_scrollFlag = true;
			};
			_message.text(v2, callback);
			textarea.html("");
		}
		//发新消息的按钮和ctl+回车
		$("#chat_send").click(addMsg);
		$("#chat_input").keypress(function(e){
			//if((10 == e.keyCode || 13 == e.keyCode) && e.ctrlKey){
			if(10 == e.keyCode || 13 == e.keyCode){
				addMsg(e);
			}
		});
		$("#chat_input").keyup(function(e){
			var a = $(this).html();
			var b = a.replace(_flag2urlReg, flag2img);
			if(a != b){
				$(this).html(b);
				placeCaretAtEnd(this);
			}
		});
		$("#chat_input").on("drop", function(e){e.preventDefault();});
		var prev = null;
		$("#chat_input").on("paste", function(e){
			function pasteFunc(elem){
				function getText(html){
					while(true){
						var pos1 = html.indexOf("<");
						if(pos1 < 0){
							return html;
						}
						var pos2 = html.indexOf(">", pos1);
						if(pos2 < 0){
							return html;
						}
						html = html.substring(0, pos1) + html.substring(pos2+1);
					}
				}
				if(prev){
					e.preventDefault();
					return;
				}
				prev = elem.html();
				setTimeout(function(){
					var post = elem.html();
					var i, j;
					var length = prev.length;
					var length2 = post.length;
					for(i=0;i<length;i++){
						if(prev[i] != post[i]){
							break;
						}
					}
					for(j=0;i+j<length;j++){
						if(prev[length-j-1] != post[length2-j-1]){
							break;
						}
					}
					var text = getText(post.substring(i, length2-j));
					elem.html(post.substring(0, i) + text + post.substring(length2-j));
					prev = null;
				}, 1);
			}
			var data = (e.originalEvent || e).clipboardData;
			if(data){
				for(var i=0;i<data.types.length;i++){
					if("text/plain" == data.types[i]){
						continue;
					}else{
						pasteFunc($(this));
						return;
					}
				}
			}else{
				pasteFunc($(this));
				return;
			}
		});
		//表情操作
		$("img[show]").click(function(e){
			var a = document.execCommand("insertImage", false, urlPath(e.target.src));
			if(!a){
				$("#chat_input").append("<img src='"+urlPath(e.target.src)+"' />");
			}
		});
		$("#show_pop").on("click", function(e){
			if($(this).hasClass("gray")){
				return;
			}
			$("#show").show().focus();
		});
		$("#show").on("mouseleave", function(e){
			$("#show").hide();
		});
		$("#show").hide();
		//学生过滤器，搜索学生
		$("#search1").on("click", function(e){
			$("#search2").show();
			$("#search2").find("input").focus();
			//$("#students_display").toggle();
		});
		function searchHandler(e){
			var elem = e.target;
			var text = $(elem).val().trim();
			if(text){
				_displayStudents.selectId = 4;
				_displayStudents.queryStudent = text;
				refreshStudents();
				$(elem).val("");
			}
			$("#search2").hide();
		}
		$("#search2").find("input").on("keypress", function(e){
			if(13 == e.keyCode || 10 == e.keyCode){
				searchHandler(e);
			}
		});
		$("#search2").find("input").on("blur", searchHandler);
		$("#tourist").on("click", function(e){
			$("#students_display").toggle();
		});
		$("#feeUser").on("click", function(e){
			$("#students_display").toggle();
		});
	})();
	//------------------------帮助函数-------------------------
	var _flag2url = {
		"00":"14",
		"01":"57",
		"02":"50",
		"03":"29",
		"04":"30",
		"05":"32",
		"06":"24",
		"07":"21",
		"08":"26",
		"09":"11",
		"0a":"25",
		"0b":"7",
		"0c":"15",
		"0d":"90",
		"0e":"36",
		"0f":"114",
		"0g":"17",
		"0h":"65",
		"0i":"76",
		"0j":"74",
		"0k":"13",
		"0l":"19",
		"0m":"20",
		"0n":"72",
		"0o":"75",

		"0p":"001",
		"0q":"002",
		"0r":"003",
		"0s":"004",
		"0t":"005",
		"0u":"006",
		"0v":"008",
		"0w":"009",
		"0x":"010",
		"0y":"012",
		"0z":"016",
		"10":"018",
		"11":"022",
		"12":"023",
		"13":"027",
		"14":"028",
		"15":"031",
		"16":"033",
		"17":"034",

		"18":"035",
		"19":"037",
		"1a":"040",
		"1b":"044",
		"1c":"045",
		"1d":"046",
		"1e":"048",
		"1f":"051",
		"1g":"053",
		"1h":"054",

		"1i":"055",
		"1j":"056",
		"1k":"059",
		"1l":"061",
		"1m":"063",
		"1n":"064",
		"1o":"066",
		"1p":"070",
		"1q":"071",
		"1r":"073",

		"1s":"077",
		"1t":"081",
		"1u":"083",
		"1v":"085",
		"1w":"087",
		"1x":"088",
		"1y":"089",
		"1z":"091",
		"20":"120",
		"21":"121",

		"22":"125",
		"23":"129",
		"24":"150",
		"25":"170",
		"26":"171",
		"27":"172",
		"28":"173",
		"29":"174",
		"2a":"175",
		"2b":"176",

		"2c":"177",
		"2d":"178",
	}
	var _url2flag = {};
	for(var k in _flag2url){
		var v = _flag2url[k];
		_url2flag[v] = k;
	}
	var _flag2urlReg = /\/:(..)/g;
	var _url2flagReg = /<img src="\/assets\/images\/expression\/([0-9a-z]+)\.gif.*?".*?>/gi;
	function flag2img(s, p1){
		if(p1 in _flag2url){
			return '<img src="/assets/images/expression/'+_flag2url[p1]+'.gif" width="24px" height="24px" />';
		}else{
			return s;
		}
	}
	function img2flag(s, p1){
		if(p1 in _url2flag){
			return "/:" + _url2flag[p1];
		}else{
			return s;
		}
	}
	function urlPath(url){
		var i = url.indexOf("//");
		if(i < 0){
			return url;
		}
		i = url.indexOf("/", i+2);
		if(i < 0){
			return url;
		}
		return url.substr(i);
	}
	//得到答题顶部导航条
	function getDomExamsTop(data){
		var domPage = $('<p><span>选题:</span><a data-index="0" href="#" class="curr">全部</a></p>');
		for(var i=0;i<data.length;i++){
			domPage.append('<a data-index="'+(i+1)+'" >'+(i+1)+'</a>');
		}
		var domTop = $('<div class="topic-num pos-rel"><a href="#" class="close-btn pos-abs"><img src="/assets_v2/img/down.png" alt=""></a></div>').prepend(domPage);
		domTop.on("click", "img", function(e){
			//_domExams.hide();
			//$("#test").show();
			issueHide();
		});
		return domTop;
	}
	//得到答题目录页
	function getDomExamsDir(data){
		var domMain = $("<ul></ul>");
		for(var i=0;i<data.length;i++){
			if(data[i].q_desc){
				domMain.append('<li data-index="'+(i+1)+'">题干'+(i+1)+'：<a>'+htmlEscape(data[i].q_desc).substr(0, 10)+'</a></li>');
			}else{
				domMain.append('<li data-index="'+(i+1)+'">题干'+(i+1)+'：<a>图片题</a></li>');
			}
		}
		return $('<div data-index="0" class="topic-list"></div>').append(domMain);
	}
	//得到各个习题页
	function getDomExamsAll(data){
		var issues = [];
		for(var i=0;i<data.length;i++){
			var a = data[i];
			var answer = "";
			var ans = ['a', 'b', 'c', 'd', 'e'];
			for(var k in ans){
				var v = ans[k];
				if(a[v]){
					if(a.answer.indexOf(v) >= 0){
						answer += '<li><input type="checkbox" checked="checked" disables><label>'+v.toUpperCase()+' '+htmlEscape(a[v])+'</label></li>';
					}else{
						answer += '<li><input type="checkbox" disabled><label>'+v.toUpperCase()+' '+htmlEscape(a[v])+'</label></li>';
					}
				}
			}
			var question = "";
			var selectType = "单选";
			if(2 == a["type"]){
				selectType = "多选";
			}
			if(a.q_desc){
				question = '<div class="p">题干'+(i+1)+'（'+selectType+'）：'+htmlEscape(a.q_desc)+'</div>';
			}else{
				question = '<div class="p">题干'+(i+1)+'（'+selectType+'）：<img src="'+filecdn+a.q_desc_img+'" /></div>';
			}
			var button = "";
			if(0 == a.status){
				button = '<button data-issue="1" class="btn btn-yellow col-sm-5 fs16">发放题型</button>';
			}
			issues.push($('<div data-index="'+(i+1)+'" style="display:none;" class="topic-content fs14"><div class="col-sm-14 topic-content-c">'+question+'<div class="p"><span>答案：</span><ul>'+answer+'</ul></div><div class="text-c p">'+button+'</div></div></div>'));
		}
		return issues;
	}
	//得到习题
	(function(){
		if(!_dataExams){
			$("#test").hide();
			return;
		}
		var data = _dataExams;
		_domExams = $('<div data-test data-index="0" class="hall-test pos-abs"></div>');
		var domTop = getDomExamsTop(data);
		_domExams.append(domTop);
		_domExams.append(getDomExamsDir(data));
		var issues = getDomExamsAll(data);
		for(var i in issues){
			_domExams.append(issues[i]);
		}
		//导航响应
		function pageHandle(e){
			if(0 == _testIndex){
				var index = $(this).attr("data-index");
				var oldIndex = _domExams.attr("data-index");
				if(oldIndex != index){
					_domExams.find("div[data-index="+oldIndex+"]").hide();
					_domExams.find("div[data-index="+index+"]").show();
					_domExams.find("a[data-index="+oldIndex+"]").removeClass("curr");
					_domExams.find("a[data-index="+index+"]").addClass("curr");
					_domExams.attr("data-index", index);
				}
			}
		}
		_domExams.on("click", "a[data-index]", pageHandle);
		_domExams.on("click", "li[data-index]", pageHandle);
		//发放题型
		_domExams.on("click", "[data-issue]", function(e){
			if(_testIndex > 0){
				return;
			}
			var dom = $(this).parents("div[data-index]").first();
			var t = (new Date).valueOf();
			_issueTimeType = 1;
			issueAskStatus(dom, t);
			var a = _dataExams[_testIndex-1];
			var issue = {"id":a.plan_exam_id, "type":a["type"]};
			if(a.q_desc){
				issue["text"] = a.q_desc;
			}else{
				issue["img"] = a.q_desc_img;
			}
			var ans = ['a', 'b', 'c', 'd', 'e'];
			var answer = [];
			for(var k in ans){
				var v = ans[k];
				if(a[v]){
					answer.push(v.toUpperCase()+"  "+a[v]);
				}
			}
			issue["answer"] = answer;
			_message.issue_ask(JSON.stringify(issue));
		});
		_domExams.on("click", "[data-good=click]", function(e){
			var dom = $(this).parents(".option-list").first();
			//var names = [];
			dom.find("ul li").each(function(i, elem){
				var u = $(this).attr("data-id");
				if(u in _info){
					clickGood(u);
					//_message.good(u);
					//names.push(_info[u]["name"]);
				}
			});
			dom.find("ul").toggle();
		});

		$("#main").append(_domExams);
		//_domExams.hide();
		issueHide();
	})();
	$("#test").on("click", function(e){
		if(_domExams){
			//_domExams.show();
			//$(this).hide();
			issueShow();
		}
	});
	var _message = new Message(planId, userId, Player, chatWs, chatPull, chatPullSet, msgDeal, null);
    initGroup();
	_message.startClass = function(){
		_flagClass = true;
		_message.start(function(){
			systemInfo("开始上课", teacherId);
		});
	}
	_message.stopClass = function(){
		_flagClass = false;
		_message.close(function(){
			systemInfo("结束上课", teacherId);
		});
		_message.pattern_normal();
	}
	function issueAskStatus(dom, t){		//出题修改页面状态
		for(var u in _info){
			if(!_info[u]["free"]){
				_info[u]["answer"] = "";
				_info[u]["answerTimes"] = 0;
			}
		}
		_correctNum = 0;
		_errorNum = 0;
		_testIndex = dom.attr("data-index");
		dom1 = dom.clone();
		dom.find("button[data-issue]").parent().remove();
		var buttonDom = $('<button data-publish="1" class="btn btn-yellow col-lg-3 col-md-7">公布答案</button>');
		var timeDom = $('<span class="cLightGray" style="margin-left:10px">用时：0分0秒</span>');
		var closeDom = $('<a data-issue-cancel="1" class="cLightGray c-fr" style="margin-left:10px" title="点击学生将强制退出答题卡">结束答题</span>');
		_startTime = t;
		function timeFunc(){
			var t = ((new Date).valueOf() - _startTime)/1000;
			timeDom.text("用时："+parseInt(t/60)+"分"+parseInt(t%60)+"秒");
			_examsTimerId = setTimeout(timeFunc, 1000);
		}
		_examsTimerId = setTimeout(timeFunc, 1000);
		var firstDom = $('<div class="issue-btn pos-rel"></div>');
		$('<p class="text-c"></p>').append(buttonDom).append(timeDom).append(closeDom).appendTo(firstDom);
		closeDom.click(function(e){

			if(dom.attr("data-index") == _testIndex){
				//var dom1 = dom.clone();
				var a = _dataExams[_testIndex-1];
				var cancel_content = {"id":a.plan_exam_id,"answer":a.answer};

				_message.issue_cancel(JSON.stringify(cancel_content));
				//dom.hide();
				//_testIndex = dom.attr("data-index");

				//_domExams.re

				issueCancelStatus(dom);


			}
		});

		buttonDom.click(function(e){
			if(dom.attr("data-index") == _testIndex && 1 == $(this).attr("data-publish")){
				var a = _dataExams[_testIndex-1];
				var answer = {"id":a.plan_exam_id, "answer":a.answer, "rank":[], "unanswer":[]};
				dom.find("ul[data-list-head]").children().each(function(i, elem){
					answer["rank"].push({"id":$(this).attr("data-id"), "name":$(this).attr("data-name"), "duration":$(this).attr("data-duration")});
				});
				for(var u in _info){
					var o = _info[u];
					if(!o["free"]){
						if(!o["answer"]){
							answer["unanswer"].push({"id":u, "name":o["name"]});
						}
					}
				}
				_message.issue_publish(JSON.stringify(answer));
				//更新习题状态
				$.post(_updatePlanExamStatusUrl, {"plan_id":planId, "plan_exam_id":_dataExams[_testIndex-1].plan_exam_id, "status":1}, function(data){
					console.log("update planexam status succeed! data=["+data+"]");
				}, "json").error(function(data){
					console.log("update planexam status fail! data=["+data+"]");
				});
				//写log
				function getLogData(u){
					var o = _info[u];
					var data = {"user_id":u, "options":o["answer"]};
					var items = o["answer"].split(",");
					var fk_answers = "";
					for(var i in items){
						if(0 == i){
							fk_answers = a["answer_"+items[i]+"_id"];
						}else{
							fk_answers += "," + a["answer_"+items[i]+"_id"];
						}
					}
					data["fk_answers"] = fk_answers;
					if(a.answer == o.answer){
						data["correct"] = 1;
					}else{
						data["correct"] = 0;
					}
					return data;
				}
				var data = {"plan_id":planId, "question_id":a.question_id, "data":[]};
				for(var u in _info){
					if(!_info[u]["free"] && _info[u]["answer"]){
						data.data.push(getLogData(u));
					}
				}
				$.post(_logIssueUrl, data, function(data){
					console.log("log issue succeed!");
				}, "json").error(function(data){
					console.log("log issue fail!");
				});
				issuePublishStatus(dom);
			}
		});
		dom.append(firstDom);
		var statDom = $('<div data-result class="col-sm-18 issue-content-c"></div>');
		statDom.append('<p><span class="col-sm-5 row"><span class="icon correct-icon"></span>答对<i data-stat="correct">0</i>人</span><span class="col-sm-5 row"><span class="icon wrong-icon"></span>答错<i data-stat="error">0</i>人</span><span class="col-sm-5 row"><span class="icon warn-icon"></span>未答<i data-stat="unanswer">'+_totalFeeNum+'</i>人</span></p>');
		var a = _dataExams[_testIndex-1];
		statDom.append('<div class="option-list"><p class="fs14"><b class="c-fl">选正确答案 '+a.answer.toUpperCase()+'</b> 共计<i data-stat="top">0</i>人 <a data-good="click"></a><span class="c-fr"></span></p><ul data-list-head></ul></div>');
		if(1 == a["type"]){
			var ans = ['a', 'b', 'c', 'd', 'e'];
			for(var k in ans){
				var v = ans[k];
				if(a[v]){
					if(a.answer.indexOf(v) < 0){
						statDom.append('<div data-list="'+v+'" class="option-list"><p class="fs14"><b class="c-fl">选 '+v.toUpperCase()+'</b> 共计<i data-num>0</i>人 <span class="c-fr"></span></p><ul></ul></div>');
					}
				}
			}
		}
		$('<div class="issue-content col-sm-20"></div>').append(statDom).appendTo(dom);
		dom.find("div[data-result]").on("click", "div.option-list", function(e){
			$(this).find("ul").toggle();
		});
	}

		//点击取消答题之后答题区域显示
	function issueCancelStatus(dom){
		if(dom.attr("data-index") == _testIndex){
			_testIndex = 0;

			clearTimeout
			if(_examsTimerId){
				clearTimeout(_examsTimerId);
				_examsTimerId = null;
			}

			dom.html(dom1.html());

			dom.show();

		}
	}


	//公布答案之后答题区显示
	function issuePublishStatus(dom){
		if(dom.attr("data-index") == _testIndex){
			_testIndex = 0;
			_startTime = 0;
			if(_examsTimerId){
				clearTimeout(_examsTimerId);
				_examsTimerId = null;
			}
                /*<div class=" ">
                    <span class="c-fl fs16"></span><span class="c-fr fs14"><a href="#"><span class="icon edit-icon"></span>继续出题</a></span>
                </div>*/
			var a = dom.find("[data-publish]");
			dom.find("[data-issue-cancel]").hide();

			a.parent().removeClass("text-c").addClass("col-sm-18").addClass("issue-content-c");
			a.replaceWith('<span class="icon block-icon"></span>本次答题情况');
			dom.find("div[data-result]").find("a[data-good=click]").each(function(i, elem){
				if($(this).siblings("i").text() > 0){
					$(this).append('(<strong class="icon praise-icon"></strong>点赞)');
				}
			});
		}
	}
	//取单个禁言历史信息
	/*
	$.post(_singleForbidUrl, {"plan_id":planId}, function(data){
		if(1 == data){	//没有单个禁言记录
			return;
		}
		for(var i in data){
			var a = data[i];
			var u = a.user_id;
			if(u in _info){
				if("forbid" == a.status){
					_info[u]["forbid"] = true;
					for(var token in _info[u]["token"]){
                        for(var i=0;i<2;i++){
                            var dom = _info[u]["token"][token];
                            if(dom){
                                dom.find("img").attr("src", _forbidThumb);
                            }
                        }
					}
				}
			}
		}
	}, "json").error(function(data){
		console.log("get single forbid, error, data=["+data+"]");
	});
	*/
	return _message;
}
