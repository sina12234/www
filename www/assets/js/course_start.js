/*举手状态说明：
 *1、显示 举手中
 *2、显示 同意发言
 *3、显示 拒绝发言
 *4、    发言中
 *5、    结束发言
*/
function planStart(){
	var _goodList = $("#good_list");
	var _speech = $("#speech");
	var _students = $("#students");
	var _pattern_reply = $("#pattern_reply");
	var _pattern_notalk = $("#pattern_notalk");
	var _studentInfo = {};
	var _inlineNum = 0;
	var _currentAsk = {};
	var _total_num = parseInt($("#student_total").find("span[total]").text());
	function studentInit(){
		_students.children("dl").each(function(i, elem){
			var uid = parseInt($(this).attr("uid"));
			var name = $(this).find("[username]").text();
			var thumb = $(this).find("img[thumb]").attr("src");
			_studentInfo[uid] = {"inline":false, "name":name, "thumb":thumb, "num":0, "student":$(this), "microphone":"untest", "fullscreen":0};
			if(name.length > 4){
				_studentInfo[uid]["shortName"] = name.substr(0, 3) + "...";
			}
			$(this).addClass("gray");
		});
	}
	studentInit();
	setInfo(_studentInfo);
	function update_students(data){ console.log("***** total \n");pyy = data;
		for(var i=0;i<data.length;i++){
			var item = data[i];
			if(!_studentInfo[item.uid]){
				console.log("add new user\n");
				var name = item.user_info.student_name;
				if(!name){
					name = item.user_info.name;
				}
				var tmp1;
				if(!item.live_online){		//有问题***********************
					tmp1 = '<p status="off" class="lixian"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">离线</p><p status="called" style="display:none" class="yidianming"><img src="/assets/images/zhengq.png" style="margin-top:5px; margin-right:3px;">已点名</p><p status="calling" style="display:none" class="weidianming"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">未点名</p>';
				}else{
					tmp1 = '<p status="off" style="display:none" class="lixian"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">离线</p><p status="called" style="display:none" class="yidianming"><img src="/assets/images/zhengq.png" style="margin-top:5px; margin-right:3px;">已点名</p><p status="calling" class="weidianming"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">未点名</p>';
				}
				var thumb = filecdn+item.user_info.thumb_med;

				var dom = $('<dl class="col-md-3 col-sm-4 col-xs-6"   uid="'+item.uid+'" online="'+item.live_online+'"><dt><img width="52" thumb  src="'+thumb+'" ></dt><ul class="ces"><li microtest="untest"><img src="/assets/images/microphoneun.png" alt="1" title="麦克风未测试" /></li><li microtest="success" style="display:none;"><img src="/assets/images/microphoneok.png" alt="1" title="麦克风测试ok" /></li><li microtest="fail" style="display:none;"><img src="/assets/images/microphonefail.png" alt="1" title="麦克风测试失败" /></li><li fullshow="0" ><img src="/assets/images/unfull.png" alt="2" title="未全屏" /></li><li fullshow="1" style="display:none;"><img src="/assets/images/webfull.png" alt="3" title="网页全屏" /></li><li fullshow="2" style="display:none;"><img src="/assets/images/flashfull.png" alt="3" title="视频全屏" /></li></ul><dd><h4 username>'+name+'</h4>'+tmp1+'<p><img src="/assets/images/dz.png">点赞:<b good>0</b>次</p></dd><div class="clear"></div><div class="usercontrl" style="display:none"><div><li good="" style="padding-left:5px;"><a>点赞</a></li><li call="" style="padding-left: 5px;"><a>点名</a></li><li asking=""><a>请发言</a></li></div></div></dl>').appendTo(_students);
				_studentInfo[item.uid] = {"inline":item.live_online, "name":name, "thumb":thumb, "num":0, "student":dom, "microphone":"untest", "fullscreen":0};
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
	function deleteOneAsk(uid){
		$("#speech").children("div[user_id]").each(function(i, elem){
			if(uid == $(this).attr("user_id")){
				$(this).prev().detach();
				$(this).detach();
				delete _currentAsk[uid];
				return;
			}
		});
	}
	function lineDisplay(uid, status){
		if(uid in _studentInfo){
			_studentInfo[uid]["student"].find("p[status]").each(function(){
				if($(this).attr("status") == status){
					$(this).show();
				}else{
					$(this).hide();
				}
			});
		}
	}
	function goodFunc(u, quick){
		if(u in _studentInfo){
			goodResponse(u, _studentInfo, quick);
			var o = _studentInfo[u];
			o.student.find("b[good]").text(o.num);
		}
	}
	function setMicrophone(u, status){
		_studentInfo[u]["microphone"] = status;
		_studentInfo[u]["student"].find("li[microtest]").each(function(i, elem){
			var v = $(this).attr("microtest");
			if(status == v){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	}
	function setFullscreen(u, status){ console.log("u=["+u+"] status=["+status+"]\n");
		_studentInfo[u]["fullscreen"] = status;
		_studentInfo[u]["student"].find("li[fullshow]").each(function(i, elem){
			var v = $(this).attr("fullshow");console.log("v = ["+v+"]\n");
			if(status == v){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	}
	function msgDeal(data){
		if(!data){
			inputScroll();
			return;
		}
		//console.log("msg id=["+data.msg_id+"] type=["+data.type+"] u=["+data.user_from_id+"]\n");
		var u = parseInt(data.user_from_id);
		/*if(u in _studentInfo){
		}else{
			console.log("error u = ["+u+"] for id=["+data.msg_id+"]\n");
			return;
		}*/
		if(MessageType.text == data.type){
			textResponse(data);
		}else if(MessageType.ask == data.type){
			if(u in _currentAsk){
			}else{
				if(u in _studentInfo){
					var	thumb = _studentInfo[u]["thumb"];
					var	name = _studentInfo[u]["name"];
					//if(1){ console.log("id=["+data.user_from_id+"]");
					//currentRequest[data.user_from_id] = 1;
					//$("#speech").append('<div>'+data.user_from_name+'<span class="agree" msg_type="4" user_id="'+data.user_from_id+'"> 允许 </span><span class="refuse" msg_type="5" user_id="'+data.user_from_id+'"> 拒绝 </span></div>');
					//var o = $('<div class="Ranking-p" user_id="'+data.user_from_id+'"><p><img width="46px" height="46px" src="'+filecdn+data.user_from_thumb+'"></p><p class="dy1">'+data.user_from_name+'</p><p ask="1" class="jushou">举手中...</p></div>');
					var o =$('<div class="Ranking-p" user_id="'+data.user_from_id+'"> <p><img width="46px" height="46px" src="'+thumb+'" width="24" height="24"></p><p class="dy1">'+name+'</p> <p class="dz"> </p> <p ask="1" class="js">举手中...</p> </div><div class="clear"></div>');
					_currentAsk[u] = o;
					_speech.append('<div class="clear"></div>');
					_speech.append(o);
					sayAsk(_studentInfo[u]["name"]);
				}else{
					console.log("error call student = [" + data.user_from_id + "]");
				}
			}
		}else if(MessageType.cancel == data.type){
			if(u in _currentAsk){
				deleteOneAsk(u);
			}else{
			}
		}else if(MessageType.agree == data.type){
			//考虑两个教师页面的时候，需要做
		}else if(MessageType.refuse == data.type || MessageType.stop == data.type){
			if(u in _currentAsk){
				deleteOneAsk(u);	
			}
		}else if(MessageType.reply == data.type){
			lineDisplay(u, "called");
		}else if(MessageType.asking == data.type){
			//考虑两个教师页面的时候，需要做
		}else if(MessageType.good == data.type){
			var u2 = parseInt(data.content);
			if(u2 in _studentInfo){
				goodFunc(u2, false);
				sayGood(teacherName, _studentInfo[u2]["name"]);
			}
		}else if(MessageType.online == data.type){
			if(u in _studentInfo){
				lineDisplay(u, "calling");
				if(!_studentInfo[u]["inline"]){
					_inlineNum++;
					setInlineNum(_inlineNum);
					_studentInfo[u]["inline"] = true;
					_studentInfo[u]["student"].removeClass("gray");
					sayLine(_studentInfo[u]["name"], true);
				}
			}
		}else if(MessageType.offline == data.type){
			if(u in _studentInfo){
				lineDisplay(u, "off");
				if(_studentInfo[u]["inline"]){
					_inlineNum--;
					setInlineNum(_inlineNum);
					_studentInfo[u]["inline"] = false;
					_studentInfo[u]["student"].addClass("gray");
					sayLine(_studentInfo[u]["name"], false);
				}
				if(u in _currentAsk){
					var o = _currentAsk[u];
					_message.stop(u);
					delete _currentAsk[u];
					o.prev().detach();
					o.detach();
				}
				setMicrophone(u, "untest");
			}
		}else if(MessageType.start == data.type){
			$("#start").hide();
			$("#stop").show();
			startClass(false);
		}else if(MessageType.close == data.type){
			$("#start").show();
			$("#stop").hide();
			stopClass();
			try{Player.close()}catch(e){}
		}else if(MessageType.pattern_normal == data.type){
			_pattern_reply[0].checked = false;
			_pattern_notalk[0].checked = false;
		}else if(MessageType.pattern_reply == data.type){
			_pattern_reply[0].checked = true;
			_pattern_notalk[0].checked = false;
		}else if(MessageType.pattern_notalk == data.type){
			_pattern_reply[0].checked = false;
			_pattern_notalk[0].checked = true;
		}else if(MessageType.reply_text == data.type){
			textResponse(data);
		}else if(MessageType.student_total == data.type){
			if(_total_num != parseInt(data.content)){
				refresh_student(update_students);
			}
		}else if(MessageType.microphone_succeed == data.type){
			if(u in _studentInfo){
				setMicrophone(u, "success");
			}
		}else if(MessageType.microphone_fail == data.type){
			if(u in _studentInfo){
				setMicrophone(u, "fail");
			}
		}else if(MessageType.fullscreen == data.type){
			if(u in _studentInfo){ console.log("fullscreen = ["+data.content+"]");
				setFullscreen(u, parseInt(data.content));
				//_studentInfo[u]["fullscreen"] = parseInt(data.content);
			}
		}else{
			;
		}
	}
	var _message = new Message(planId, userId, Player, msgDeal, null);
	//同一时间只有一个学生可以发言，要把其它的都关掉
	function speechOnly(uid){
		for(var u in _currentAsk){
			if(u != uid){ console.log("u=["+u+"] uid=["+uid+"]\n");
				var o = _currentAsk[u];
				var a = o.find("p[ask=4],p[ask=5]");
				if(a.length){
					_message.stop(u);
					delete _currentAsk[u];
					o.prev().detach();
					o.detach();
				}
			}
		}
	}
	$("#speech").on("mouseenter", "p[ask=1]", function(e){
		var p=$(this).parent();
		$(this).detach();
		//p.append('<p agree class="yunx" msg_type="4" user_id="'+userId+'"> 允许 </p><p refuse class="yunx" msg_type="5" user_id="'+userId+'"> 拒绝 </p>');
		p.append('<p ask="2" class="jsa"> 允许发言 </p>');
	});
	$("#speech").on("mouseout", "p[ask=2],p[ask=3]", function(e){
		var p = $(this).parent();
		$("[ask]", p).detach();
		p.append('<p ask="1" class="js">举手中...</p>');
	});
	$("#speech").on("click", "p[ask=2]", function(e){
		var p = $(this).parent();
		$("[ask]", p).detach();
		//p.append('<p ask="4" class="jushou" style="color:#333333;"><img src="../assets/images/fayan.png" style="margin-top:3px; margin-right:8px;margin-left:-10px;">发言中...</p>');
		p.append('<p ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</p>');
		speechOnly(p.attr("user_id"));
		_message.agree(p.attr("user_id"));
	});
	$("#speech").on("click", "p[ask=3]", function(e){
		var p = $(this).parent();
		_message.refuse(p.attr("user_id"));
		p.prev.detach();
		p.detach();
	});
	$("#speech").on("mouseenter", "p[ask=4]", function(e){
		var p = $(this).parent();
		$(this).detach();
		p.append('<p ask="5" class="jsd" > 结束 </p>');
	});
	$("#speech").on("mouseout", "p[ask=5]", function(e){
		var p = $(this).parent();
		$(this).detach();
		p.append('<p ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</p>');
		//p.append('<p ask="4" class="jushou" style="color:#333333;"><img src="../assets/images/fayan.png" style="margin-top:3px; margin-right:8px;margin-left:-10px;">发言中...</p>');
	});
	$("#speech").on("click", "p[ask=5]", function(e){
		var p = $(this).parent();
		var u = p.attr("user_id");
		_message.stop(u);
		delete _currentAsk[u];
		p.prev().detach();
		p.detach();
	});
	_students.on("click", "li[good]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		//var p4 = p2.parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		p2.hide();
		p3.removeClass("current");
		if(_studentInfo[u]["inline"]){
			_message.good(u);
			goodFunc(u, true);
			sayGood(teacherName, _studentInfo[u]["name"]);
		}
	});
	_students.on("click", "li[call]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		if(_studentInfo[u]["inline"]){
			lineDisplay(parseInt(u), "calling");
			_message.call(u);
			sayCall(_studentInfo[u]["name"]);
		}
		p2.hide();
		p3.removeClass("current");
	});
	_students.on("click", "li[asking]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		p2.hide();
		p3.removeClass("current");
		if(_studentInfo[u]["inline"]){
			if(u in _currentAsk){
				var a = _currentAsk[u].find("p[ask=1],p[ask=2],p[ask=3]");
				if(a.length){
					var p = a.parent();
					$("[ask]", p).detach();
			//		p.append('<p ask="4" class="jushou" style="color:#333333;"><img src="../assets/images/fayan.png" style="margin-top:3px; margin-right:8px;margin-left:-10px;">发言中...</p>');
			p.append('<p ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</p>');
					speechOnly(u);
					_message.agree(u);
				}
			}else{
				var o = _studentInfo[u];
				var a = $('<div class="Ranking-p" user_id="'+u+'"><p><img width="46px" height="46px" src="'+o["thumb"]+'"></p><p class="dy1">'+o["name"]+'</p><p ask="4" class="jss"><img src="/assets/images/fayan.png" >发言中...</p></div>');
				_currentAsk[u] = a;
				_speech.append('<div class="clear"></div>');
				_speech.append(a);
				speechOnly(u);
				_message.asking(u);
			}
		}
	});
	$("#call_all").click(function(e){
		for(u in _studentInfo){
			var o = _studentInfo[u]; console.log("u=["+u+"] inline=["+o.inline+"]\n");
			if(o.inline){
				_message.call(u);
				lineDisplay(u, "calling");
			}
		}
		sayCall("全体学生");
	});
	$("#microphone_all").click(function(e){
		for(u in _studentInfo){
			var o = _studentInfo[u]; console.log("u=["+u+"] inline=["+o.inline+"]\n");
			if(o.inline && "untest" == o.microphone){
				_message.microphone_test(u);
			}
		}
		sayMicrophone("全体学生");
	});
	_pattern_reply.click(function(e){
		if(this.checked){
			_pattern_notalk[0].checked = false;
			_message.pattern_reply();
		}else{
			if(_pattern_notalk[0].checked){
				_message.pattern_notalk();
			}else{
				_message.pattern_normal();
			}
		}
	});
	_pattern_notalk.click(function(e){
		if(this.checked){
			_pattern_reply[0].checked = false;
			_message.pattern_notalk();
		}else{
			if(_pattern_reply[0].checked){
				_message.pattern_reply();
			}else{
				_message.pattern_normal();
			}
		}
	});
	return _message;
}
