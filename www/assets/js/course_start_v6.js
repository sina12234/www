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
	/*if(u in _currentAsk && data.user_to_token in _currentAsk[u]) 举手 发言 都在_currentAsk中 */
	studentInit();
	setInfo(_studentInfo);
	function delete_one_student(u){
		//u 是报名的人不是游客
		//	console.log("进入删除了");
		//	console.log("u="+u);
		if(u in _studentInfo && !_studentInfo[u]["free"]){
		//	console.log("发言中");
			var o = _studentInfo[u];
			o["free"] = true;
			//不在线
			if("" in o["token"]){
			//	console.log("没上线删除了");
				o["token"][""].detach();
				o["token"] = {};
			}else{
		//		console.log("上线删除中");
				var num = Object.keys(o["token"]).length;
				var v = o["token"];
				for(var k in o["token"]){
			//	console.log("上线删除中uuu"+u);
					if(u in _currentAsk && k in _currentAsk[u]){
			//				console.log("举手个数是"+_riseHand);
						//TODO 下线去除发言中
						//deleteOneAsk(uid, data.user_from_token);
						if(u==_speekUser && k ==_speekToken){
							cancelSpeech(u,k);
			//				console.log("举手个数是2"+_riseHand);
							_message.stop(u,k);
						}else{
							cancelRisehand(u,k);
			//				console.log("举手个数是1"+_riseHand);
							_message.refuse(u,k);
						}
					}
			//		console.log("开始删除");
					o["token"][k].detach();
			//		console.log("删除后");
					_onlineNode -= 1;
					_onlineNum2 += 1;
					o["token"][k] = 1;
				}
				_onlineNum -= 1;
				setOnlineNum(_onlineNum);
				setOnlineNum2(_onlineNum2);
			}
			_total_num--;
			$("#student_total").find("span[total]").text(_total_num);
		}
	}
	function add_one_student(data){
	//console.log("进入增加节点");
		var u = parseInt(data.pk_user);
		if(!(u in _studentInfo)){
			_studentInfo[u] = {"online":false, "free":true, "num":0, "token":{}, "microphone":"untest", "fullscreen":0};
		}
		var o = _studentInfo[u];
		if(o["free"]){
	//		console.log("进入增加节点1");
			var name = data.name;
			var thumb;
			if(typeof(data.thumb_med)=="string"){
				var med = data.thumb_med.trim();	
				if(med.length>0){
					thumb = filecdn+med;
				}else{
					thumb = filecdn+"4,058582450327";
				}
			}else{
				thumb = filecdn+"4,058582450327";
			}
			o["name"] = name;
			o["thumb"] = thumb;
			o["free"] = false;
			o["num2"] = 0;
			//var tmp1 = '<p status="off" style="display:none" class="lixian"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">离线</p><p status="called" style="display:none" class="yidianming"><img src="/assets/images/zhengq.png" style="margin-top:5px; margin-right:3px;">已点名</p><p status="calling" class="weidianming"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">未点名</p>';
			var tmp1 = '<p status="off" class="lixian"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">离线</p><p status="called" style="display:none" class="yidianming"><img src="/assets/images/zhengq.png" style="margin-top:5px; margin-right:3px;">已点名</p>';
			//<p status="calling" class="weidianming"><img src="/assets/images/cuow.png" style="margin-top:5px; margin-right:3px;">未点名</p>';
			var dom = $('<dl class="col-md-3 col-sm-4 col-xs-6" style="position:relative;"  uid="'+u+'"><dt><img width="52" thumb  src="'+thumb+'" ></dt><ul class="ces"><li microtest="untest"><img src="/assets/images/microphoneun.png" alt="1" title="麦克风未测试" /></li><li microtest="success" style="display:none;"><img src="/assets/images/microphoneok.png" alt="1" title="麦克风测试ok" /></li><li microtest="fail" style="display:none;"><img src="/assets/images/microphonefail.png" alt="1" title="麦克风测试失败" /></li><li fullshow="3" ><img src="/assets/images/unfull.png" alt="2" title="未全屏" /></li><li fullshow="1" style="display:none;"><img src="/assets/images/webfull.png" alt="3" title="网页全屏" /></li><li fullshow="2" style="display:none;"><img src="/assets/images/flashfull.png" alt="3" title="视频全屏" /></li></ul><dd><h4 username class="limithis" title="'+name+'">'+name+'</h4>'+tmp1+'<p><img src="/assets/images/dz.png">点赞:<b good>0</b>次</p></dd><div class="clear"></div><div class="usercontrl" style="display:none"><div><li good="" style="padding-left:5px;"><a>点赞</a></li><li call="" style="padding-left: 5px;"><a>点名</a></li><li asking=""><a>请发言</a></li></div></div></dl>');
			var num = Object.keys(o["token"]).length;
			if(0 == num){
//	console.log("进入增加节点2");
				o["token"][""] = dom;
				dom.addClass("gray");
				dom.appendTo(_students);
			}else{
//	console.log("进入增加节点3");
				o["online"] = true;
				for(var k in o["token"]){
					var dom2 = dom.clone(true);
					dom2.attr("token", k);
					o["token"][k] = dom2;
					dom2.insertAfter(_students.children().eq(_onlineNode-1));
					lineDisplay(u, k, "calling");
				}
				_onlineNode += num;
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
		
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
		}*/
		function deleteOneAsk(uid, token){
			if(uid in _currentAsk && token in _currentAsk[uid]){
				if(uid==_speekUser && token==_speekToken){
					_speekUser = 0;
				}
				delete _currentAsk[uid][token];
			}
		}
	/**取消举手*/	
	function cancelRisehand(uid,token){
		_riseHand--;
		if(_riseHand<0){_riseHand=0;}
		_studentInfo[uid]["token"][token].find(".add").remove();
		comeBack(uid,token);
		delete _currentAsk[uid][token];
	}
	/**取消发言*/	
	function cancelSpeech(uid,token){
		_speekUser = 0;
		_speekToken = 0;
		_studentInfo[uid]["token"][token].find(".add").remove();
		comeBack(uid,token);
		delete _currentAsk[uid][token];
	}
	/*节点取消状态后回归原节点*/
	function comeBack(uid,token){
		var v = _studentInfo[uid]["token"];
		var dom = v[token];
		//如果在线而且在举手或发言
		for(var k in v){
			if(token !=k && !( k in _currentAsk[uid])){
				dom.insertAfter(v[k]);
				return;
			}
		}
		dom.insertAfter(_students.children().eq(_onlineNode-1));
	}
	/*
	*	上课的时候双登陆 copy一个节点 
	*	data 是节点的信息
	*
	*/
	function addSameUser(uid,token){
		_onlineNode++; // 在线的节点+一个
		var v = _studentInfo[uid]["token"];
		for(var t1 in v){
			var dom1 = v[t1];
			if(!dom1.find(".add").length){
				var dom2 = dom1.clone(true);
				dom2.attr("uid",uid);
				dom2.attr("token",token);
				v[token] = dom2;
				setMicrophone(uid,token, "untest");
				lineDisplay(uid,token, "calling");
				//_students.prepend(dom2);
				dom2.insertAfter(dom1);
				return;
			}
		}
		var keys = Object.keys(v);
		var dom = v[keys[0]];
		var dom2 = dom.clone(true);
		dom2.attr("uid", uid);
		dom2.attr("token",token);
		dom2.find(".add").detach();
		v[token] = dom2;
		setMicrophone(uid, token, "untest");
		lineDisplay(uid, token, "calling");
		dom2.insertAfter(dom.parent().children().eq(_onlineNode-2));
	}
/*	function addSameUser(uid,data){
		_onlineNode++; // 在线的节点+一个
		var v = _studentInfo[uid]["token"];
		for(var t1 in v){
			var dom1 = v[t1];
			if(!dom1.find(".add").length){
				var dom2 = dom1.clone(true);
				dom2.attr("uid", data.user_from_id);
				dom2.attr("token", data.user_from_token);
				v[data.user_from_token] = dom2;
				setMicrophone(uid, data.user_from_token, "untest");
				lineDisplay(uid, data.user_from_token, "calling");
				//_students.prepend(dom2);
				dom2.insertAfter(dom1);
				return;
			}
		}
		var keys = Object.keys(v);
		var dom = v[keys[0]];
		var dom2 = dom.clone(true);
		dom2.attr("uid", data.user_from_id);
		dom2.attr("token", data.user_from_token);
		dom2.find(".add").detach();
		v[data.user_from_token] = dom2;
		setMicrophone(uid, data.user_from_token, "untest");
		lineDisplay(uid, data.user_from_token, "calling");
		dom2.insertAfter(dom.parent().children().eq(_onlineNode-2));
	}
	*/
	/*
	* 用户下线操作
	*/ 
	function offlineUser(uid,token){
		var v = _studentInfo[uid]["token"];
		var dom =_studentInfo[uid]["token"][token];
		var keys = Object.keys(v);
		if(uid in _currentAsk && token in _currentAsk[uid]){
			//TODO 下线去除发言中
			//deleteOneAsk(uid, data.user_from_token);
			if(uid==_speekUser && token ==_speekToken){
				_message.stop(uid,token);
				cancelSpeech(uid,token);
			}else{
				cancelRisehand(uid,token);
				_message.refuse(uid,token);
			}
		}
		_onlineNode--;
		if(keys.length > 1){
		//	console.log("不是游客多账号登陆");
			//删除节点
			dom.detach();
			delete v[token];
		}else{
		//	console.log("不是游客一个账号登陆");
			dom.appendTo(_students);
			delete v[token];
			v[""] = dom;
			dom.attr("token", "");
			_onlineNum--;
			_studentInfo[uid]["online"] = false;
		//	console.log("下线1213");
			setOnlineNum(_onlineNum);
			dom.addClass("gray");
			lineDisplay(uid,token, "off");
		}
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
			if("ask" == data.content){
					//	console.log("ask事件来了");
				if(u in _studentInfo && (data.user_from_token in _studentInfo[u]["token"]) && !_studentInfo[u]["free"]){
					if(!(u in _currentAsk)){
						console.log("空_studentinfsplay:block;' user_o事件来了");
						_currentAsk[u] = {};
					}
					/*没举手**/
					if(!(data.user_from_token in _currentAsk[u])){
				//		o = $("<div class='add'style='display:block;' user_id='"+data.user_from_id+"'><h1 ask='1' >举手中...</h1></div>");
						console.log("举手个数之前"+_riseHand);
						var dom = _studentInfo[u]["token"][data.user_from_token];
						_riseHand++;
						console.log("举手个数"+_riseHand);
						//如果前面有人说话	
						if((0!=_speekUser)){
							dom.insertAfter(_students.children().eq(_riseHand-1));
						//如果前面没人说话
						}else{
							//没人举手
							if(_riseHand==1){
								dom.prependTo(_students);
							//有人举手
							}else{
								dom.insertAfter(_students.children().eq(_riseHand-2));
							}
						}

						dom.find("dt").before("<div class='add hpoint'style='display:block;' user_id='"+data.user_from_id+"'><h1 ask='1' >举手中...</h1></div>");
						console.log("举手成功");
						_currentAsk[u][data.user_from_token] = 1;
						sayAsk(_studentInfo[u]["name"]);
					}
				}
			}else if("cancel" == data.content){
			//		console.log("取消举手了");
				if(u in _currentAsk && data.user_from_token in _currentAsk[u]){
			//		console.log("取消发言u"+u+"token"+data.user_from_token);
					if(u==_speekUser && data.user_from_token ==_speekToken){
			//		console.log("取消发言");
						cancelSpeech(u,data.user_from_token);
					}else{
			//		console.log("取消举手");
						cancelRisehand(u,data.user_from_token);
					}
				/*	deleteOneAsk(u, data.user_from_token);
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
					*/
				}
			}
		}else if(MessageType.agree_refuse == data.type){
			//考虑两个教师页面的时候，需要做
			// 这里是老师允许学生发言所以学生是to 老师是from  
			//判断学生的状态所以用to
			u = parseInt(data.user_to_id);
			//判断是否是合法用户
			if(u in _studentInfo && (data.user_to_token in _studentInfo[u]["token"]) && !_studentInfo[u]["free"]){
				if("agree" == data.content ||"asking" == data.content){
					if(u==_speekUser && data.user_to_token ==_speekToken){
						;
					}else{
						speechOnly(u,data.user_to_token);
						//如果在举手和发言中
						if(u in _currentAsk && data.user_to_token in _currentAsk[u]){
							_riseHand--;
						}else{
						//如果不再举手和发言中
							if(!(u in _currentAsk)){
							//初始化
								_currentAsk[u] = {};
							}
							//初始化 再赋值
							_currentAsk[u][data.user_to_token] = 1;
						}

						var dom=_studentInfo[u]["token"][data.user_to_token];
						dom.prependTo(_students);
						dom.find("dt").before("<div class='add hpoint' style='display:block;' user_id='"+data.user_from_id+"'><h1 ask='4' >发言中...</h1></div>");
					//	dom.find("li[asking]").parent().append('<h1 ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</h1>');
					}
				}
				else if("refuse" == data.content || "stop" == data.content){
					if(u in _currentAsk && data.user_to_token in _currentAsk[u]){
						//在发言中
						if(0!=_speekUser){
							cancelSpeech(u,data.user_to_token);
						}else{
							cancelRisehand(u,data.user_to_token);
						}
					}
				}
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
						//dom 就是本节点
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
							dom.prependTo(_students);
						}else{
							dom.insertAfter(_students.children().eq(_onlineNode-2));
						}
					}else{
						if(!(data.user_from_token in v)){
							if(_studentInfo[u]["free"]){
								v[data.user_from_token] = 1;
								_onlineNum2++;
								setOnlineNum2(_onlineNum2);
							}else{
								addSameUser(u,data.user_from_token);
							}
							if(1 == fee_type && _studentInfo[u]["free"]){
								;
							}else{
								sayLine(_studentInfo[u]["name"], true);
							}
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
					if(1 == fee_type){
						;
					}else{
						sayLine(_studentInfo[u]["name"], true);
					}
					}
				}
			}else if("offline" == data.content){
				//	console.log("下线进入");
				//	console.log("U是"+u);
				//	console.log("U是"+u);
					
				if(u in _studentInfo && data.user_from_token in _studentInfo[u]["token"]){
					var v = _studentInfo[u]["token"];
					var keys = Object.keys(v);
					var name = _studentInfo[u]["name"];
					if(_studentInfo[u]["free"]){
						delete v[data.user_from_token];
						if(0 == keys.length){
							delete _studentInfo[u];
						}
						_onlineNum2--;
						setOnlineNum2(_onlineNum2);
					}else{
						offlineUser(u,data.user_from_token);
					}
					if(1 == fee_type && _studentInfo[u]["free"]){
						;
					}else{
						sayLine(name, false);
					}
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
		/*}else if(MessageType.student_total == data.type){
			if(_total_num != parseInt(data.content)){
				refresh_student(update_students);
			}*/
		}else if(MessageType.microphone_result == data.type){
			if("succeed" == data.content){
				setMicrophone(u, data.user_from_token, "success");
			}else if("fail" == data.content){
				setMicrophone(u, data.user_from_token, "fail");
			}
		}else if(MessageType.fullscreen == data.type){
			setFullscreen(u, data.user_from_token, parseInt(data.content));
		}else if(MessageType.modify_student == data.type){
			if("add" == data.content){
				get_new_student(add_one_student, u);
			}else if("delete" == data.content){
				delete_one_student(u);
			}else{
			//	console.log("!!!error for modify student content=["+data.content+"]");
			}
		}else{
			;
		}
	}
	var _message = new Message(planId, userId, Player, chatWs, chatPull, msgDeal, null);
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
//		console.log("new来的e"+uid+"token"+token);
//		console.log("new来的e"+_speekUser+"token"+_speekToken);
		//如果新来的这个人之前有人发言 就吧他俩归零
		if((uid !=_speekUser || token != _speekToken) &&_speekUser!=0){
			_message.stop(_speekUser,_speekToken);
			cancelSpeech(_speekUser,_speekToken);
		}
		_speekUser=uid;
		_speekToken=token;
	}
/*	function speechOnly(uid,token){
		if((uid !=_speekUser || token != _speekToken) &&_speekUser!=0){
				_message.stop(_speekUser,_speekToken);
				var u = _speekUser;
					deleteOneAsk(u, _speekToken);
					_riseHand--;
					if(_riseHand<=0){_riseHand=0;}
					_studentInfo[u]["token"][_speekToken].find(".add").remove();

					var v = _studentInfo[u]["token"];
					var dom1 = _studentInfo[u]["token"][_speekToken];
					var keys = Object.keys(v);
					if(keys.length>1){
						if(_speekToken != keys[0]){
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
		}
		_speekUser=uid;
		_speekToken=token;
	}
*/	
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
		$(this).detach();
		p.append('<h1 ask="4" class="jss"><img src="/assets/images/fayan.png">发言中...</h1>');
		pp.prependTo(_students);
		speechOnly(pp.attr("uid"), pp.attr("token"));
	//	console.log("全局user"+_speekUser);
	//	console.log("全局Token"+_speekToken);
		_message.agree(pp.attr("uid"), pp.attr("token"));
		_riseHand--;
	//	console.log("放前面");
	});
	$("#speech_search").on("click", "h1[ask=3]", function(e){
		var p = $(this).parent();
		pp.prependTo(_students);
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
	//	console.log("结束token"+token);
		_message.stop(u, token);
		cancelSpeech(u,token);
	//	delete _currentAsk[u][token];
	//	p.prev().detach();
	//	p.detach();
	/*	var v = _studentInfo[u]["token"];
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
		*/
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
					var callback = function(){
						var data = {"user_id":u, "num":1}
						goodFunc(data, true);
						sayGood(teacherName, _studentInfo[u]["name"]);
					};
					_message.good(u, callback);
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
	//	console.log("请发言的uid"+u);
		p2.hide();
		p3.removeClass("current");
		if(_studentInfo[u]["online"]){
	//				console.log("到我遮了");
			if(u in _currentAsk && token in _currentAsk[u]){
				var a = _currentAsk[u][token].find("h1[ask=1],h1[ask=2],h1[ask=3]");
	//				console.log("a长度"+a.length);
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
	//			console.log("没有举手");
				var o = _studentInfo[u];
				var	a = $("<div class='add hpoint'style='display:block;' user_id='"+u+"'><h1 ask='4' >发言中...</h1></div>");
			//	var a = $('<div class="Ranking-p" user_id="'+u+'" token="'+token+'"><p><img width="46px" height="46px" src="'+o["thumb"]+'"></p><p class="dy1">'+o["name"]+'</p><p ask="4" class="jss"><img src="/assets/images/fayan.png" >发言中...</p></div>');
				if(!(u in _currentAsk)){
					_currentAsk[u] = {};
				}
				_currentAsk[u][token] = a;
				_studentInfo[u]["token"][token].find("dt").before("<div class='add hpoint'style='display:block;' user_id='"+u+"'><h1 ask='4' >发言中...</h1></div>");
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
