function planPlay(){
	var _goodList = $("#good_list");
	var _input = $("#chat_input")
	var _pattern_reply = $("#pattern_reply");
	var _pattern_notalk = $("#pattern_notalk");
	var _notalkFlag = false;
	var _students = $("#students");
	var _studentInfo = {};
	var _inlineNum = 1;
	var _callId = 0;
	var _total_num = parseInt($("#student_total").find("span[total]").text());
	function studentInit(){
		var i = 0;
		_students.children().each(function(){
			var uid = parseInt($(this).attr("uid"));
			var name = $(this).children().children().attr("title");
			var thumb = $(this).children().children().attr("src");
			_studentInfo[uid] = {"inline":false, "name":name, "thumb":thumb, "num":0, "student":$(this)};
			if(name.length > 4){
				_studentInfo[uid]["shortName"] = name.substr(0, 3) + "...";
			}
			if(uid != userId){
				$(this).addClass("gray");
			}else{
				$("#self_thumb").attr("src", thumb);
			}
			++i;
		});
	}
	studentInit();
	setInfo(_studentInfo);
	function update_students(data){
		for(var i=0;i<data.length;i++){
			var item = data[i];
			if(!_studentInfo[item.uid]){
				var name = item.user_info.student_name;
				if(!name){
					name = item.user_info.name;
				}
				var thumb = filecdn + item.user_info.thumb_med;
				var dom = $('<li uid="'+item.uid+'"><div><img title="'+name+'" src="'+thumb+'"></div></li>');
				dom.appendTo(_students);
				_studentInfo[item.uid] = {"inline":item.live_online, "name":name, "thumb":thumb, "num":0, "student":dom};
				if(!item.live_online){
					dom.addClass("gray");
				}else{
					_inlineNum++;
					setInlineNum(_inlineNum);
				}
				_total_num++;
			}
		}
		//_total_num = data.length;
		$("#student_total").find("span[total]").text(_total_num);
	}
	function msgDeal(data){
		if(!data){
			inputScroll();
			return;
		}
		//console.log("msg id=["+data.msg_id+"] type=["+data.type+"]\n");
		var u = parseInt(data.user_from_id);
		if(MessageType.text == data.type){
			textResponse(data);
		}else if(MessageType.agree == data.type){
			var u2 = parseInt(data.content);
			if(u2 == userId){
				var ask = $("#ask");
				var a = ask.attr("ask");
				if("ask" == a){
					console.log("agree for ask\n");
				}else if("cancel" == a){
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
			}
			sayAsking(teacherName, _studentInfo[u2]["name"], "同意");
		}else if(MessageType.refuse == data.type || MessageType.stop == data.type){
			var ask = $("#ask");
			var a = ask.attr("ask"); console.log("type = ["+data.type+"] a = ["+a+"]\n");
			if("ask" == a){
				console.log("type=["+data.type+"] for ask");
			}else{
				if("stop" == a){ console.log("Player.stopRecord start\n");
					Player.stopRecord();
				}
				ask.attr("ask", "ask");
				ask.children().each(function(){
					displayOne($(this), "ask", "ask");
				});
			}
		}else if(MessageType.call == data.type){
			Player.quitFullScreen();
			$("#call_pop").show();
			_callId = u;
			//alert("点名了");
			//_message.reply(u);
		}else if(MessageType.asking == data.type){ console.log("one asking...+++++++++++++++++");
			var u2 = parseInt(data.content);
			if(u2 == userId){
				var ask = $("#ask");
				var a = ask.attr("ask");
				if("stop" != a){ console.log("a = ["+a+"]");
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
			}
			sayAsking(teacherName, _studentInfo[u2]["name"], "要求");
		}else if(MessageType.good == data.type){
			var u2 = parseInt(data.content);
			if(u2 in _studentInfo){
				goodResponse(u2, _studentInfo, false);
				sayGood(teacherName, _studentInfo[u2]["name"]);
			}
		}else if(MessageType.online == data.type){
			if(userId != u && u in _studentInfo && !_studentInfo[u]["inline"]){
				_inlineNum++;
				setInlineNum(_inlineNum);
				_studentInfo[u]["inline"] = true;
				_studentInfo[u]["student"].removeClass("gray");
			}
		}else if(MessageType.offline == data.type){
			if(userId != u && u in _studentInfo && _studentInfo[u]["inline"]){
				_inlineNum--;
				setInlineNum(_inlineNum);
				_studentInfo[u]["inline"] = false;
				_studentInfo[u]["student"].addClass("gray");
			}
		}else if(MessageType.start == data.type){
			try{Player.close();}catch(e){}
			try{Player.reInit();}catch(e){}
			startClass(_notalkFlag); console.log("----------start ...");
		}else if(MessageType.close == data.type){
			try{Player.close();}catch(e){}
			stopClass();console.log("----------end ...");
		}else if(MessageType.pattern_normal == data.type){
			_pattern_reply.hide();
			_pattern_notalk.hide();
			_notalkFlag = false;
			_isPatternReply = false;
			chatNotalk(false);
			$("#chat_list").find("[reply_star]").hide();
			$("#chat_list").find("[reply_text]").show();
		}else if(MessageType.pattern_reply == data.type){
			_pattern_reply.show();
			_pattern_notalk.hide();
			_notalkFlag = false;
			_isPatternReply = true;
			chatNotalk(false);
		}else if(MessageType.pattern_notalk == data.type){
			_pattern_reply.hide();
			_pattern_notalk.show();
			_notalkFlag = true;
			_isPatternReply = false;
			chatNotalk(true);
			$("#chat_list").find("[reply_star]").hide();
			$("#chat_list").find("[reply_text]").show();
		}else if(MessageType.reply_text == data.type){
			if(userId == data.user_from_id){
				textResponse(data);
			}else{
				replyTextResponse(data);
			}
		}else if(MessageType.student_total == data.type){
			if(_total_num != parseInt(data.content)){
				refresh_student(update_students);
			}
		}else if(MessageType.microphone_test == data.type){ console.log("one test...+++++++++++++++++");
			var u2 = parseInt(data.content);
			if(u2 == userId){ console.log("testmicrophone");
				Player.testMicrophone(true);
			}
			//sayAsking(teacherName, _studentInfo[u2]["name"], "要求");
		}else{
			;
		}
	}
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
		}else if("cancel" == a){
			_message.cancel(); n="ask";
		}else{
			_message.cancel(); n="ask";
			flag = true;
		}
		ask.attr("ask", n); console.log("n=["+n+"]");
		ask.children().each(function(){
			displayOne($(this), "ask", n);
		});
		return flag;
	}
	var _message = new Message(planId, userId, Player, msgDeal, null);
	_message.flash_callback = function(data){
		var flag = false;
		for(var k in data){ console.log("k = ["+k+"] v=["+data[k]+"] ask=["+$("#ask").attr("ask")+"]\n");
			if("exist" == k){
				if(data[k]){
					$("#ask").find("[ask=ask]").removeClass("gray");
				}else{
					$("#ask").find("[ask=ask]").addClass("gray");
					flag = true;
				}
			}else if("connected" == k){
				if(!data[k]){
					flag = true;
				}
			/*}else if("closed" == k){
				if(data[k]){
					flag = true;
				}*/
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
		}
	}
	_message.microphone_callback = function(flag){ console.log("microphone start");
		if(flag){
			_message.microphone_succeed();
		}else{
			_message.microphone_fail();
		}
	}
	_message.flash_fullscreen_callback = function(flag){ console.log("fullscreen start");
		if(flag){
			_message.fullscreen(2);
		}else{
			_message.fullscreen(0);
		}
	}
	$("#ask").click(function(e){ console.log("click............");
		var flag = ask_status();
		if(flag){
			Player.stopRecord();
		}
	});
	$("#call_reply").click(function(e){
		_message.reply(_callId);
		$("#call_pop").hide();
	});
	function webFullscreenChange(e){ console.log("fullchange!");
		var isFull = isFullScreen();
		if(isFull){
			_message.fullscreen(1);
		}else{
			_message.fullscreen(0);
		}
	}
	$(document).on("fullscreenchange", webFullscreenChange);
	$(document).on("mozfullscreenchange", webFullscreenChange);
	$(document).on("webkitfullscreenchange", webFullscreenChange);
	$(document).on("MSfullscreenchange", webFullscreenChange);
	function initFullscreen(){
		var isFull = isFullScreen();
		if(isFull){
			_message.fullscreen(1);
		}
		var info = Player.info();
		if(info.fullscreen){
			_message.fullscreen(2);
		}
		_message.fullscreen(0);
	}
	initFullscreen();
	return _message;
}
