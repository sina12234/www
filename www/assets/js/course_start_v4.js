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
	var _onlineNum = 0;
	var _onlineNum2 = 0;
	var _onlineNode = 0;
	var _riseHand = 0;
	var _currentAsk = {};
	var _speekUser = 0;
	var _speekToken = 0;
	var _oldFlag = true;
	var _total_num = parseInt($("#student_total").find("span[total]").text());
	function studentInit(){
		_students.children("dl").each(function(i, elem){
			var uid = parseInt($(this).attr("uid"));
			var name = $(this).find("[username]").text();
			var thumb = $(this).find("img[thumb]").attr("src");
			_studentInfo[uid] = {"online":false, "name":name, "free":false, "thumb":thumb, "num":0, "num2":0, "token":{"":$(this)}, "microphone":"untest", "fullscreen":0};
			if(name.length > 4){
				_studentInfo[uid]["shortName"] = name.substr(0, 3) + "...";
			}
			$(this).addClass("gray");
		});
	}
	studentInit();
	setInfo(_studentInfo);
	function update_students(data){
		var s = {};
		for(var i=0;i<data.length;i++){
			s[parseInt(data[i].uid)] = 1;
		}
		for(var u in _studentInfo){
				console.log("开始调走了");
			var o = _studentInfo[u];
			if(!o["free"] && !(u in s)){
				_total_num--;
				console.log("在里面");
				o["free"] = true;
				deleteSpeech(u);
				if("" in o["token"]){
				console.log("离线删除");
					o["token"][""].detach();
					o["token"] = {};
				}else{
					var num = Object.keys(o["token"]).length;
				console.log("在线删除");
					_onlineNum -= 1;
					_onlineNode -= num;
					_onlineNum2 += num;
					setOnlineNum(_onlineNum);
					setOnlineNum2(_onlineNum2);
					for(var k in o["token"]){
						o["token"][k].detach();
						o["token"][k] = 1;
					}
				}
			}
		}
		for(var i=0;i<data.length;i++){
			var item = data[i];
			var u = parseInt(item.uid);
			if(!u in _studentInfo){
				_studentInfo[u] = {"online":false, "free":true, "num":0, "token":{}, "microphone":"untest", "fullscreen":0};
			}
			var o = _studentInfo[u];
			if(o["free"]){
				var name = item.user_info.student_name;
				if(!name){
					name = item.user_info.name;
				}
				var thumb = filecdn+item.user_info.thumb_med;
				o["name"] = name;
				o["thumb"] = thumb;
				o["free"] = false;
				o["num2"] = 0;
				var tmp1 = '<p status="off" style="display:none" class="lixian"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">离线</p><p status="called" style="display:none" class="yidianming"><img src="/assets/images/zhengq.png" style="margin-top:5px; margin-right:3px;">已点名</p><p status="calling" class="weidianming"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">未点名</p>';
				var dom = $('<dl class="col-md-3 col-sm-4 col-xs-6"   uid="'+u+'"><dt><img width="52" thumb  src="'+thumb+'" ></dt><ul class="ces"><li microtest="untest"><img src="/assets/images/microphoneun.png" alt="1" title="麦克风未测试" /></li><li microtest="success" style="display:none;"><img src="/assets/images/microphoneok.png" alt="1" title="麦克风测试ok" /></li><li microtest="fail" style="display:none;"><img src="/assets/images/microphonefail.png" alt="1" title="麦克风测试失败" /></li><li fullshow="0" ><img src="/assets/images/unfull.png" alt="2" title="未全屏" /></li><li fullshow="1" style="display:none;"><img src="/assets/images/webfull.png" alt="3" title="网页全屏" /></li><li fullshow="2" style="display:none;"><img src="/assets/images/flashfull.png" alt="3" title="视频全屏" /></li></ul><dd><h4 username class="limithis" title="'+name+'">'+name+'</h4>'+tmp1+'<p><img src="/assets/images/dz.png">点赞:<b good>0</b>次</p></dd><div class="clear"></div><div class="usercontrl" style="display:none"><div><li good="" style="padding-left:5px;"><a>点赞</a></li><li call="" style="padding-left: 5px;"><a>点名</a></li><li asking=""><a>请发言</a></li></div></div></dl>');
				var num = Object.keys(o["token"]).length;
				if(0 == num){
					o["token"][""] = dom;
					dom.addClass("gray");
				//	dom.appendTo(dom.parent());
					dom.appendTo(_students);
				}else{
					o["online"] = true;
					for(var k in o["token"]){
						var dom2 = dom.clone(true);
						dom2.attr("token", k);
						o["token"][k] = dom2;
					//	dom2.insertAfter(dom.parent().children().eq(_onlineNode-2));
					//	dom2.appendTo(_students);
						dom2.appendTo(_students.children().eq(_onlineNode-1));
						lineDisplay(u, k, "calling");
					}
					_onlineNode += num;
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
	}
	function deleteOneAsk(uid, token){
				delete _currentAsk[uid][token];
	}
	function lineDisplay(uid, token, status){
		if(uid in _studentInfo && !_studentInfo[uid]["free"] && token in _studentInfo[uid]["token"]){
			_studentInfo[uid]["token"][token].find("p[status]").each(function(){
				if($(this).attr("status") == status){
					$(this).show();
				}else{
					$(this).hide();
				}
			});
		}
	}
	function goodFunc(data, quick){
		var u = parseInt(data.user_id);
		if(u in _studentInfo && !_studentInfo[u]["free"]){
			goodResponse(data, quick);
			var o = _studentInfo[u];
			o.num2 += parseInt(data.num);
			for(var k in o["token"]){
				o["token"][k].find("b[good]").text(o.num2);
			}
		}
	}
	function setMicrophone(u, token,  status){
		if(u in _studentInfo && !_studentInfo[u]["free"]){
			if(token in _studentInfo[u]["token"]){
				_studentInfo[u]["token"][token].find("li[microtest]").each(function(i, elem){
					var v = $(this).attr("microtest");
					if(status == v){
						$(this).show();
					}else{
						$(this).hide();
					}
				});
			}
		}
	}
	function setFullscreen(u, token, status){
		if(u in _studentInfo && !_studentInfo[u]["free"]){
			if(token in _studentInfo[u]["token"]){
				_studentInfo[u]["token"][token].find("li[fullshow]").each(function(i, elem){
					var v = $(this).attr("fullshow");
					if(status == v){
						$(this).show();
					}else{
						$(this).hide();
					}
				});
			}
		}
	}
	function msgDeal(data){
		if(!data){
			_oldFlag = false;
			inputScroll();
			return;
		}
		var u = parseInt(data.user_from_id);
		if(MessageType.text == data.type){
			textResponse(data);
		}else if(MessageType.ask_cancel == data.type){
						console.log("举手事件来了");
			if("ask" == data.content){
						console.log("ask事件来了");
				if(u in _studentInfo){
						console.log("in_studentinfo事件来了");
					if(!(u in _currentAsk)){
						console.log("空_studentinfsplay:block;' user_o事件来了");
						_currentAsk[u] = {};
					}
					if(!(data.user_from_token in _currentAsk[u])){
					/*	var o =$('<div class="Ranking-p" user_id="'+data.user_from_id+'" token="'+data.user_from_token+'"> <p><img width="47px" height="46px" src="'+_studentInfo[u]["thumb"]+'"></p><p class="dy1">'+_studentInfo[u]["name"]+'</p> <p class="dz"> </p> <p ask="1" class="js">举手中...</p> </div><div class="clear"></div>');
						_speech.append('<div class="clear"></div>');
						_speech.append(o);

						*/
						o = $("<div class='add'style='display:block;' user_id='"+data.user_from_id+"'><h1 ask='1' >举手中...</h1></div>");
						console.log("举手个数之前"+_riseHand);
						_riseHand++;
						console.log("举手个数"+_riseHand);
						if(_riseHand==1||_riseHand==0){
							_studentInfo[u]["token"][data.user_from_token].prependTo(_studentInfo[u]["token"][data.user_from_token].parent());
						}else{
							_studentInfo[u]["token"][data.user_from_token].insertAfter(_studentInfo[u]["token"][data.user_from_token].parent().children().eq(_riseHand-2));
						}
						_studentInfo[u]["token"][data.user_from_token].find("dt").before("<div class='add'style='display:block;' user_id='"+data.user_from_id+"'><h1 ask='1' >举手中...</h1></div>");
						console.log("举手成功");
						_currentAsk[u][data.user_from_token] = o;
						sayAsk(_studentInfo[u]["name"]);
					}
				}
			}else if("cancel" == data.content){
					console.log("取消举手了");
				if(u in _currentAsk && data.user_from_token in _currentAsk[u]){
					deleteOneAsk(u, data.user_from_token);
					_riseHand--;
					console.log("举手了个数"+_riseHand);
					if(_riseHand<=0){_riseHand=0;}
					console.log("设定后的举手了个数"+_riseHand);
					_studentInfo[u]["token"][data.user_from_token].find(".add").remove();

					var v = _studentInfo[u]["token"];
					var dom1 = _studentInfo[u]["token"][data.user_from_token];
					var keys = Object.keys(v);
					if(keys.length>1){
						if(data.user_from_token != keys[0]){
							t2 = keys[0];
							var dom2 = v[t2];
							dom1.insertAfter(dom2);
						}else{
							t2 = keys[1];
							var dom2 = v[t2];
							dom1.insertAfter(dom2);
						}
					}else{
						dom1.insertAfter(dom1.parent().children().eq(_onlineNode-1));
					}
					console.log("已经取消举手了");
				}
			}
		}else if(MessageType.agree_refuse == data.type){
			if("agree" == data.content){
				//考虑两个教师页面的时候，需要做
			}else if("refuse" == data.content || "stop" == data.content){
				if(u in _currentAsk && data.user_from_token in _currentAsk[u]){
					deleteOneAsk(u, data.user_from_token);
					_riseHand--;
				}
			}else if("asking" == data.content){
				//考虑两个教师页面的时候，需要做
			}
		}else if(MessageType.reply == data.type){
			lineDisplay(u, data.user_from_token, "called");
		}else if(MessageType.good == data.type){
			var u2 = data.user_id;
			if(u2 in _studentInfo){
				goodFunc(data, false);
				if(!_oldFlag){
				sayGood(teacherName, _studentInfo[u2]["name"]);
				}
			}
		}else if(MessageType.on_off_line == data.type){
			if("online" == data.content){
				if(u in _studentInfo){
					var v = _studentInfo[u]["token"];
					if("" in v){
						/*for(var i in data){
							console.log("first data: key=["+i+"] value=["+data[i]+"]");
						}*/
						var dom = v[""];
						dom.attr("token", data.user_from_token);
						delete v[""];
						v[data.user_from_token] = dom;
						_studentInfo[u]["online"] = true;
						_onlineNum++;
						_onlineNode++;
						setOnlineNum(_onlineNum);
						dom.removeClass("gray");
						lineDisplay(u, data.user_from_token, "calling");
						sayLine(_studentInfo[u]["name"], true);
						if(_onlineNum==1){
							_studentInfo[u]["token"][data.user_from_token].prependTo(_studentInfo[u]["token"][data.user_from_token].parent());
						}else{
							_studentInfo[u]["token"][data.user_from_token].insertAfter(_studentInfo[u]["token"][data.user_from_token].parent().children().eq(_onlineNode-2));
						}
						/*for(var i in v){
							console.log("first v: key=["+i+"] value=["+v[i]+"]");
						}*/
					}else{
						if(!(data.user_from_token in v)){
							if(_studentInfo[u]["free"]){
								v[data.user_from_token] = 1;
								_onlineNum2++;
								setOnlineNum2(_onlineNum2);
							}else{
								/*for(var i in v){
									console.log("first v: key=["+i+"] value=["+v[i]+"]");
									}*/
									_onlineNode++;
									var flag = false;
									for(var t1 in v){
										var dom1 = v[t1];
										if(!dom1.find(".add")){
											var dom2 = dom1.clone(true);
											dom2.attr("uid", data.user_from_id);
											dom2.attr("token", data.user_from_token);
											v[data.user_from_token] = dom2;
											setMicrophone(u, data.user_from_token, "untest");
											lineDisplay(u, data.user_from_token, "calling");
											//_students.prepend(dom2);
											dom2.insertAfter(dom1);
											flag = true;
										console.log("flag了123132123");
											break;
										}

									}
									if(!flag){
										var keys = Object.keys(v);
										console.log("没有flag");
										var dom = v[keys[0]];
										var dom2 = dom.clone(true);
										dom2.attr("uid", data.user_from_id);
										dom2.attr("token", data.user_from_token);
										dom2.find(".add").detach();
										v[data.user_from_token] = dom2;
										setMicrophone(u, data.user_from_token, "untest");
										lineDisplay(u, data.user_from_token, "calling");
										//_students.prepend(dom2);
										dom2.insertAfter(dom.parent().children().eq(_onlineNode-2));
									//	dom2.insertAfter(dom);
									}
							}
							sayLine(_studentInfo[u]["name"], true);
						}
					}
				}else{
					if(teacherId != u){		//过滤掉教师id
					/*var dom = $('<dl style="position: relative;" class="col-md-3 col-sm-4 col-xs-6"  uid="'+data.user_from_id+'" token="'+data.user_from_token+'" ><dt><img width="52" thumb  src="/assets/images/qx.png" ></dt><ul class="ces"><li microtest="untest"><img src="/assets/images/microphoneun.png" alt="1" title="麦克风未测试" /></li><li microtest="success" style="display:none;"><img src="/assets/images/microphoneok.png" alt="1" title="麦克风测试ok" /></li><li microtest="fail" style="display:none;"><img src="/assets/images/microphonefail.png" alt="1" title="麦克风测试失败" /></li><li fullshow="3" ><img src="/assets/images/unfull.png" alt="2" title="未全屏" /></li><li fullshow="1" style="display:none;"><img src="/assets/images/webfull.png" alt="3" title="网页全屏" /></li><li fullshow="2" style="display:none;"><img src="/assets/images/flashfull.png" alt="3" title="视频全屏" /></li></ul><dd><h4 username class="limithis" title="游客">游客</h4></dd></dl>');
					_students.append(dom);*/
					var v = {};
					//v[data.user_from_token] = dom;
					v[data.user_from_token] = 1;
					_studentInfo[u] = {"online":true, "name":"游客", "free":true, "thumb":"/assets/images/qx.png", "num":0, "token":v, "microphone":"untest", "fullscreen":0};
					_onlineNum2++;
					setOnlineNum2(_onlineNum2);
					sayLine(_studentInfo[u]["name"], true);
					}
				}
			}else if("offline" == data.content){
					console.log("下线进入");
					console.log("U是"+u);
					console.log("U是"+u);
					
				if(u in _studentInfo && data.user_from_token in _studentInfo[u]["token"]){
					console.log("不是游客");
					var v = _studentInfo[u]["token"];
					var name = _studentInfo[u]["name"];
					if(_studentInfo[u]["free"]){
						delete v[data.user_from_token];
						if(0 == v.length){
							delete _studentInfo[u];
						}
						_onlineNum2--;
						setOnlineNum2(_onlineNum2);
					}else{
						_onlineNode--;
						var keys = Object.keys(v);
						if(keys.length > 1){
					console.log("不是游客多账号登陆");
							if(u in _currentAsk && data.user_from_token in _currentAsk[u]){
							//TODO 下线去除发言中
								deleteOneAsk(u, data.user_from_token);
								_message.stop(u,data.user_from_token);
								_riseHand--;
							}
							v[data.user_from_token].detach();
							delete v[data.user_from_token];
						}else{
							console.log("不是游客一个账号登陆");
							if(_studentInfo[u]["free"]){
								v[data.user_from_token].detach();
								delete _studentInfo[u];
							}else{
								if(u in _currentAsk && data.user_from_token in _currentAsk[u]){
									//TODO 下线去除发言中
									deleteOneAsk(u, data.user_from_token);
									_message.stop(u,data.user_from_token);
									_riseHand--;
								}
								_studentInfo[u]["token"][data.user_from_token].appendTo(_studentInfo[u]["token"][data.user_from_token].parent());
								var dom = v[data.user_from_token];
								delete v[data.user_from_token];
								v[""] = dom;
								dom.attr("token", "");
								_onlineNum--;
								_studentInfo[u]["online"] = false;
								console.log("下线1213");
								setOnlineNum(_onlineNum);
								dom.addClass("gray");
								lineDisplay(u, data.user_from_token, "off");
							}
						}
					}
					sayLine(name, false);
				}
			}
		}else if(MessageType.start_close == data.type){
			if("start" == data.content){
				$("#start").hide();
				$("#stop").show();
				startClass(false);
			}else if("close" == data.content){
				$("#start").show();
				$("#stop").hide();
				stopClass();
				try{Player.close()}catch(e){}
			}
		}else if(MessageType.pattern == data.type){
			if("normal" == data.content){
				_pattern_reply[0].checked = false;
				_pattern_notalk[0].checked = false;
			}else if("reply" == data.content){
				_pattern_reply[0].checked = true;
				_pattern_notalk[0].checked = false;
			}else if("notalk" == data.content){
				_pattern_reply[0].checked = false;
				_pattern_notalk[0].checked = true;
			}
		}else if(MessageType.reply_text == data.type){
			textResponse(data);
		}else if(MessageType.student_total == data.type){
			if(_total_num != parseInt(data.content)){
				refresh_student(update_students);
			}
		}else if(MessageType.microphone_result == data.type){
			if("succeed" == data.content){
				setMicrophone(u, data.user_from_token, "success");
			}else if("fail" == data.content){
				setMicrophone(u, data.user_from_token, "fail");
			}
		}else if(MessageType.fullscreen == data.type){
			setFullscreen(u, data.user_from_token, parseInt(data.content));
		}else{
			;
		}
	}
	var _message = new Message(planId, userId, Player, msgDeal, null);
	//同一时间只有一个学生可以发言，要把其它的都关掉
/*	function speechOnly(uid, token){
		for(var u in _currentAsk){
			for(var t in _currentAsk[u]){
			if(u != uid || t != token){
				var o = _currentAsk[u][t];
				var a = o.find("p[ask=4],p[ask=5]");
				if(a.length){
					_message.stop(u, t);
					delete _currentAsk[u][t];
					o.prev().detach();
					o.detach();
				}
			}
			}
		}
	}
	*/
	function speechOnly(uid,token){
		if(uid !=_speekUser && token != _speekToken &&_speekUser!=0){
				_message.stop(_speekUser,_speekToken);
				_speekUser = 0;
				_speekToken = 0;
		}
		_speekUser=uid;
		_speekToken=token;
	}
	function deleteSpeech(uid){
		if(uid in _currentAsk){
			for(var t in _currentAsk[uid]){
				var o = _currentAsk[uid][t];
				var a = o.find("p[ask=4],p[ask=5]");
				if(a.length){
					_message.stop(uid, t);
				}
				o.prev().detach();
				o.detach();
			}
			delete _currentAsk[uid];
		}
	}
	$("#speech_search").on("mouseenter", "h1[ask=1]", function(e){
		var p=$(this).parent();
		$(this).detach();
		//p.append('<p agree class="yunx" msg_type="4" user_id="'+userId+'"> 允许 </p><p refuse class="yunx" msg_type="5" user_id="'+userId+'"> 拒绝 </p>');
		p.append('<h1 ask="2" class="jsa"> 允许发言 </h1>');
	});
	$("#speech_search").on("mouseout", "h1[ask=2],h1[ask=3]", function(e){
		var p = $(this).parent();
		$("[ask]", p).detach();
		p.append('<h1 ask="1" class="js">举手中...</h1>');
	});
	$("#speech_search").on("click", "h1[ask=2]", function(e){
		var p = $(this).parent();
		var pp = p.parent();
		$("[ask]", p).detach();
		p.append('<h1 ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</h1>');
		pp.prependTo(pp.parent());
		speechOnly(pp.attr("uid"), pp.attr("token"));
		console.log("全局user"+_speekUser);
		console.log("全局Token"+_speekToken);
		_message.agree(pp.attr("uid"), pp.attr("token"));
		_riseHand--;
		console.log("放前面");
	});
	$("#speech_search").on("click", "h1[ask=3]", function(e){
		var p = $(this).parent();
		pp.prependTo(pp.parent());
		_message.refuse(pp.attr("uid"), pp.attr("token"));
		p.prev.detach();
		p.detach();
	});
	$("#speech_search").on("mouseenter", "h1[ask=4]", function(e){
		var p = $(this).parent();
		$(this).detach();
		p.append('<h1 ask="5" class="jsd" > 结束 </h1>');
	});
	$("#speech_search").on("mouseout", "h1[ask=5]", function(e){
		var p = $(this).parent();
		$(this).detach();
		p.append('<h1 ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</h1>');
		//p.append('<p ask="4" class="jushou" style="color:#333333;"><img src="../assets/images/fayan.png" style="margin-top:3px; margin-right:8px;margin-left:-10px;">发言中...</p>');
	});
	$("#speech_search").on("click", "h1[ask=5]", function(e){
		var p = $(this).parent();
		var pp = p.parent();
		var u = pp.attr("uid");
		var token = pp.attr("token");
		_message.stop(u, token);
		delete _currentAsk[u][token];
		p.prev().detach();
		p.detach();
		var v = _studentInfo[u]["token"];
		var dom1 = _studentInfo[u]["token"][token];
		var keys = Object.keys(v);
		if(keys.length>1){
			if(token != keys[0]){
				t2 = keys[0];
				var dom2 = v[t2];
				dom1.insertAfter(dom2);
			}else{
				t2 = keys[1];
				var dom2 = v[t2];
				dom1.insertAfter(dom2);
			}
		}else{
			dom1.insertAfter(dom1.parent().children().eq(_onlineNode-1));
		}
	//	_studentInfo[u]["token"][token].insertAfter(_studentInfo[u]["token"][token].parent().children().eq(_onlineNum-1));
	});
	_students.on("click", "li[good]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		//var p4 = p2.parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		p2.hide();
		p3.removeClass("current");
		if(!_studentInfo[u]["free"]){
			for(var k in _studentInfo[u]["token"]){
				if("" != k){
					_message.good(u);
					var data = {"user_id":u, "num":1}
					goodFunc(data, true);
					sayGood(teacherName, _studentInfo[u]["name"]);
					break;
				}
			}
		}
	});
	_students.on("click", "li[call]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		var token = p3.attr("token");
		var o = _studentInfo[u];
		if(o["online"]){
			lineDisplay(parseInt(u), token, "calling");
			_message.call(u, token);
			sayCall(o["name"]);
		}
		p2.hide();
		p3.removeClass("current");
	});
	_students.on("click", "li[asking]", function(e){
		e.preventDefault();
		var p2 = $(this).parent().parent();
		var p3 = p2.parent();
		var u = parseInt(p3.attr("uid"));
		var token = p3.attr("token");
		console.log("请发言的uid"+u);
		p2.hide();
		p3.removeClass("current");
		if(_studentInfo[u]["online"]){
					console.log("到我遮了");
			if(u in _currentAsk && token in _currentAsk[u]){
				var a = _currentAsk[u][token].find("h1[ask=1],h1[ask=2],h1[ask=3]");
					console.log("a长度"+a.length);
				if(a.length){
					var p = a.parent();
					$("[ask]", p).detach();
					//p.append('<p ask="4" class="jushou" style="color:#333333;"><img src="../assets/images/fayan.png" style="margin-top:3px; margin-right:8px;margin-left:-10px;">发言中...</p>');
					p.append('<h1 ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</h1>');
					_studentInfo[u]["token"][token].prependTo(_studentInfo[u]["token"][token].parent());
					_riseHand--;
					speechOnly(u, token);
					_message.agree(u, token);
				}
			}else{
				console.log("没有举手");
				var o = _studentInfo[u];
				var	a = $("<div class='add'style='display:block;' user_id='"+u+"'><h1 ask='4' >发言中...</h1></div>");
			//	var a = $('<div class="Ranking-p" user_id="'+u+'" token="'+token+'"><p><img width="46px" height="46px" src="'+o["thumb"]+'"></p><p class="dy1">'+o["name"]+'</p><p ask="4" class="jss"><img src="/assets/images/fayan.png" >发言中...</p></div>');
				if(!(u in _currentAsk)){
					_currentAsk[u] = {};
				}
				_currentAsk[u][token] = a;
				_studentInfo[u]["token"][token].find("dt").before("<div class='add'style='display:block;' user_id='"+u+"'><h1 ask='4' >发言中...</h1></div>");
				_studentInfo[u]["token"][token].prependTo(_studentInfo[u]["token"][token].parent());
				_speech.append('<div class="clear"></div>');
				_speech.append(a);
				speechOnly(u, token);
				_message.asking(u, token);
			}
		}
	});
	$("#call_all").click(function(e){
		for(var u in _studentInfo){
			var o = _studentInfo[u];
			if(!o["free"] && o.online){
				for(var token in o["token"]){
					_message.call(u, token);
					lineDisplay(u, token, "calling");
				}
			}
		}
		sayCall("全体学生");
	});
	$("#microphone_all").click(function(e){
		for(var u in _studentInfo){
			var o = _studentInfo[u];
			if(!o["free"] && o.online){
				for(var token in o["token"]){	//需要加是否已测判断
					_message.microphone_test(u, token);
				}
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
