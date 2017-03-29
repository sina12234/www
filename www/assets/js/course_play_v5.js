function planPlay(){
	var _goodList = $("#good_list");
	var _input = $("#chat_input")
	var _pattern_reply = $("#pattern_reply");
	var _pattern_notalk = $("#pattern_notalk");
	var _notalkFlag = false;
	var _students = $("#students");
	var _studentInfo = {};
	var _onlineNum = 0;
	var _onlineNum2 = 0;
	_lastAddTime = [];
	/*if(!isReg){
		_onlineNum = 0;
		_onlineNum2 = 1;
	}*/
	var _callId = 0;
	var _oldFlag = true;
	var _total_num = parseInt($("#student_total").find("span[total]").text());
	function studentInit(){
		var i = 0;
		_students.children().each(function(){
			var uid = parseInt($(this).attr("uid"));
			var name = $(this).children().children().attr("title");
			var thumb = $(this).children().children().attr("src");
			_studentInfo[uid] = {"online":false, "name":name, "thumb":thumb, "free":false, "num":0, "token":{}, "dom":$(this)};
			if(name.length > 4){
				_studentInfo[uid]["shortName"] = name.substr(0, 3) + "...";
			}
			//if(uid != userId){
				$(this).addClass("gray");
			/*}else{
				//$("#self_thumb").attr("src", thumb);
				_studentInfo[uid]["token"] = {userFlag:$(this)};
			}*/
			++i;
		});
		/*if(!isReg){
			var t = {};
			t[userFlag] = 1;
			_studentInfo[userId] = {"online":true, "name":"me", "thumb":null, "free":true, "num":0, "token":t};
		}*/
	}
	studentInit();
	if(!isReg){
		chatNotalk(true);
	}
	setInfo(_studentInfo);
	function delete_one_student(u){
		if(u in _studentInfo && !_studentInfo[u]["free"]){
			var o = _studentInfo[u];
			_total_num--;
			o["free"] = "true";
			o["dom"].detach();
			delete o["dom"];
			var num = Object.keys(o["token"]).length;
			_onlineNum -= 1;
			_onlineNum2 += num;
			setOnlineNum(_onlineNum);
			setOnlineNum2(_onlineNum2);
			if(userId == u){
				isReg = 0;
				chatNotalk(true);
				$("#ask").addClass("gray");
				$("#ask").children().each(function(){
					$(this).addClass("gray");
				});
			}
			$("#student_total").find("span[total]").text(_total_num);
		}
	}
	function add_one_student(data){
		var u = parseInt(data.pk_user);
		if(!(u in _studentInfo)){
			_studentInfo[u] = {"online":false, "free":true,"num":0, "token":{}};
		}
		var o = _studentInfo[u];
		if(o["free"]){
			var name = data.name;
			var thumb = filecdn+data.thumb_med;
			o["name"] = name;
			o["thumb"] = thumb;
			o["free"] = false;
			var dom = $('<li uid="'+u+'"><div><img title="'+name+'" src="'+thumb+'"></div></li>');
			o["dom"] = dom;
			dom.appendTo(_students);
			var num = Object.keys(o["token"]).length;
			if(0 == num){
				dom.addClass("gray");
			}else{
				o["online"] = true;
				_onlineNum += 1;
				_onlineNum2 -= num;
				setOnlineNum(_onlineNum);
				setOnlineNum2(_onlineNum2);
			}
			_total_num++;
			$("#student_total").find("span[total]").text(_total_num);
		}
	}
	/*function update_students(data){
		var s = {};
		var length = data.length;
		for(var i=0;i<length;i++){
			s[parseInt(data[i].uid)] = 1;
		}
		for(var u in _studentInfo){
			var o = _studentInfo[u];
			if(!o["free"] && !(u in s)){
				_total_num--;
				o["free"] = "true";
				o["dom"].detach();
				delete o["dom"];
				var num = Object.keys(o["token"]).length;
				_onlineNum -= 1;
				_onlineNum2 += num;
				setOnlineNum(_onlineNum);
				setOnlineNum2(_onlineNum2);
				if(userId == u){
					isReg = 0;
					chatNotalk(true);
					$("#ask").addClass("gray");
				}
			}
		}
		for(var i=0;i<length;i++){
			var item = data[i];
			var u = parseInt(item.uid);
			if(!(u in _studentInfo)){
				_studentInfo[u] = {"online":false, "free":true,"num":0, "token":{}};
			}
			var o = _studentInfo[u];
			if(o["free"]){
				var name = item.user_info.student_name;
				if(!name){
					name = item.user_info.name;
				}
				var thumb = filecdn + item.user_info.thumb_med;
				o["name"] = name;
				o["thumb"] = thumb;
				o["free"] = false;
				var dom = $('<li uid="'+u+'"><div><img title="'+name+'" src="'+thumb+'"></div></li>');
				o["dom"] = dom;
				dom.appendTo(_students);
				var num = Object.keys(o["token"]).length;
				if(0 == num){
					dom.addClass("gray");
				}else{
					o["online"] = true;
					_onlineNum += 1;
					_onlineNum2 -= num;
					setOnlineNum(_onlineNum);
					setOnlineNum2(_onlineNum2);
				}
				_total_num++;
			}
		}
		//_total_num = data.length;
		$("#student_total").find("span[total]").text(_total_num);
	}*/
	function msgDeal(data){
		if(!data){
			_oldFlag = false;
			inputScroll();
			return;
		}
		var u = parseInt(data.user_from_id);
		if(MessageType.text == data.type){
			textResponse(data);
		}else if(MessageType.agree_refuse == data.type){
			if("agree" == data.content){
				if(isReg && data.user_to_id == userId && data.user_to_token == userFlag){
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
				if(data.user_to_id in _studentInfo){
					sayAsking(teacherName, _studentInfo[data.user_to_id]["name"], "同意");
				}
			}else if("refuse" == data.content || "stop" == data.content){
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
			}else if("asking" == data.content){
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
				}
				if(u in _studentInfo){
					sayAsking(teacherName, _studentInfo[u]["name"], "要求");
				}
			}
		}else if(MessageType.call == data.type){
			if(isReg && (0 == data.user_to_id || (data.user_to_id == userId && data.user_to_token == userFlag))){
				Player.quitFullScreen();
				$("#call_pop").show();
				_callId = u;
				//alert("点名了");
				//_message.reply(u);
			}
		}else if(MessageType.good == data.type){
			var u2 = data.user_id;
			if(u2 in _studentInfo){
				goodResponse(data, false);
				if(!_oldFlag){
					sayGood(teacherName, _studentInfo[u2]["name"]);
				}
			}
		}else if(MessageType.on_off_line == data.type){
			if(u in _studentInfo){
				var o = _studentInfo[u];
				if("online" == data.content){
					if(data.user_from_token in o["token"]){
						//重复信息不处理
					}else{
						o["token"][data.user_from_token] = 1;
						if(o["free"]){
							_onlineNum2++;
							setOnlineNum2(_onlineNum2);
						}else{
							if(1 == Object.keys(o["token"]).length){
								_onlineNum++;
								setOnlineNum(_onlineNum);
								o["online"] = true;
								o["dom"].removeClass("gray");
								o["dom"].prependTo(_students);
							}
						}
					}
				}else if("offline" == data.content){
					if(data.user_from_token in o["token"]){
						delete o["token"][data.user_from_token];
						if(o["free"]){
							_onlineNum2--;
							setOnlineNum2(_onlineNum2);
							if(0 == Object.keys(o["token"]).length){
								delete _studentInfo[u];
							}
						}else{
							if(0 == Object.keys(o["token"]).length){
								_onlineNum--;
								setOnlineNum(_onlineNum);
								o["online"] = false;
								o["dom"].addClass("gray");
								o["dom"].appendTo(_students);
							}
						}
					}
				}
			}else{
				if("online" == data.content){
					if(teacherId != u){		//过滤掉教师id
						var t = {};
						t[data.user_from_token] = 1
						_studentInfo[u] = {"online":true, "name":"游客", "free":true, "token":t};
						_onlineNum2++;
						setOnlineNum2(_onlineNum2);
					}
				}
			}
		}else if(MessageType.start_close == data.type){
			if("start" == data.content){
				try{Player.close();}catch(e){}
				try{Player.reInit();}catch(e){}
				startClass(_notalkFlag);
			}else if("stop" == data.content){
				try{Player.close();}catch(e){}
				stopClass();
			}
		}else if(MessageType.pattern == data.type){
			if("normal" == data.content){
				_pattern_reply.hide();
				_pattern_notalk.hide();
				_notalkFlag = false;
				_isPatternReply = false;
				if(isReg){
					chatNotalk(false);
				}
				$("#chat_list").find("[reply_star]").hide();
				$("#chat_list").find("[reply_text]").show();
			}else if("reply" == data.content){
				_pattern_reply.show();
				_pattern_notalk.hide();
				_notalkFlag = false;
				_isPatternReply = true;
				if(isReg){
					chatNotalk(false);
				}
			}else if("notalk" == data.content){
				_pattern_reply.hide();
				_pattern_notalk.show();
				_notalkFlag = true;
				_isPatternReply = false;
				if(isReg){
					chatNotalk(true);
				}
				$("#chat_list").find("[reply_star]").hide();
				$("#chat_list").find("[reply_text]").show();
			}
		}else if(MessageType.reply_text == data.type){
			if(userId == data.user_from_id){
				textResponse(data);
			}else{
				replyTextResponse(data);
			}
		/*}else if(MessageType.student_total == data.type){
			if(_total_num != parseInt(data.content)){
				refresh_student(update_students);
			}*/
		}else if(MessageType.microphone_test == data.type){
			if(isReg && (0 == data.user_to_id || (userId == data.user_to_id && userFlag == data.user_to_token))){
				Player.testMicrophone(true);
			}
			//sayAsking(teacherName, _studentInfo[u2]["name"], "要求");
		}else if(MessageType.modify_student == data.type){
			if("add" == data.content){
				get_new_student(add_one_student, u);
			}else if("delete" == data.content){
				delete_one_student(u);
			}else{
				console.log("!!!error for modify student content=["+data.content+"]");
			}
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
		ask.attr("ask", n);
		ask.children().each(function(){
			displayOne($(this), "ask", n);
		});
		return flag;
	}
	var _message = new Message(planId, userId, Player, chatWs, chatPull, msgDeal, null);
	_message.flash_callback = function(data){
		if(!isReg){
			return;
		}console.log("flash callback, data=["+JSON.stringify(data)+"]\n");
		var flag = false;
		for(var k in data){
			if("exist" == k){
				var ask = $("#ask").find("[ask=ask]");
				if(data[k]){
					ask.removeClass("gray");
					ask.children().each(function(){
						$(this).removeClass("gray");
					});
				}else{
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
}
