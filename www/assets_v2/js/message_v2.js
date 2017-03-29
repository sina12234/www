if(!Object.keys){
	Object.keys = function(d){
		var result = [];
		for(var k in d){
			result.push(k);
		}
		return result;
	}
}

//g_msg_ids = {"signal":{}, "text":{}, "good":{}}
g_msg_ids = {"signal":{}}
g_pullExtraNum = 0;

//信息类型定义
var MessageType = {
	notice  :   1030,   //公告
	text	:	1,		//文本聊天
	call	:	6,		//点名
	reply	:	7,		//答道
	good	: 	100,	//点赞
	reply_text	:	500,	//答题模式文本
	student_total	:	600,	//学生总数
	microphone_test	:	700,	//测试麦克风
	fullscreen	:	800,	//全屏状态改变
	ask_cancel	:	1002,
	agree_refuse	:	1004,
	on_off_line		:	1006,
	start_close		:	1008,
	pattern			:	1010,
	microphone_result	:	1012,
	single_notalk	:	1014,
	issue_ask	:	1016,
	issue_publish	:	1017,
	issue_answer			:	1018,
	issue_cancel	:1019,
	request_eval    :   1020,
	delete_text     :   1022,
	score_info     :   1024,
	camera          :   1026,
	gift            :   1040,  //学生给老师献礼物
	modify_student	:	1200,
	recess          :   1044,  //课间休息，老师发起的
	comeback        :   1045,  //学生响应休息结束
	isLive	:	function(t){
		switch(parseInt(t)){
		//case this.call:
		//case this.reply:
		case this.microphone_test:
		//case this.ask_cancel:		//这个和下一个，初始化时老师这边需要考虑合并取最后一个状态
		//case this.agree_refuse:
        case this.request_eval:
		case this.modify_student:
        case this.score_info:
			return true;
		default:
			return false;
		}
	},
	getMessageType	:	function(t){
		switch(parseInt(t)){
			case this.text:
			case this.reply_text:
				return "text";
			case this.good:
				return "good";
			default:
				return "signal";
		}
	}
};

function type2Id(type){
	var a = {"text":"TextId", "good":"GoodId", "signal":"SignalId"};
	if(type in a){
		return a[type];
	}
	return null;
}
function type2Id2(type){
	var a = {"text":"st", "good":"sg", "signal":"ss"};
	if(type in a){
		return a[type];
	}
	return null;
}

var pull_callback;
var set_callback;

function BasicMessage(outer){
	function Msg(){
		this._start = {"text":0, "signal":0, "good":0};
		this._successCallback = null;
		this._errorCallback = null;
		this._miliSecondsForReconnect = 1000;
		_this = this;
        this._outer = outer;
		pull_callback = function(data){
			_this._success(data);
		}
		this._success = function(data){//console.log("success:"+JSON.stringify(data)+"\n");
			/*
			 *{"MessageType":"text","StartGoodId":0,"StartTextId":58,"StartSignalId":0,"PlanId":475,"UserIdFrom":149,"UserIdTo":0,"UserTokenFrom":"faa8800b18f94089ffc33389692f113e","UserTokenTo":"","Content":"155 halo","ContentType":1,"LiveSecond":0}
			 {"MessageType":"good","StartGoodId":1,"StartTextId":0,"StartSignalId":0,"PlanId":475,"UserIdFrom":149,"UserIdTo":155,"UserTokenFrom":"eb8b882bb0419640885fe14f7cb23ada","UserTokenTo":"","Content":"","ContentType":100,"LiveSecond":0}
			 * */
			//var data = data2;
			if("string" == typeof data){
				data = JSON.parse(data);
			}
			if("object" == typeof data && "length" in data){
				var a = {"good":[], "text":[], "signal":[]};
				var length = data.length;
				for(var i=0;i<length;i++){
					var b = data[i];
					var mType = b["mt"];
					if(mType in a){
						var o = a[mType];
						var idStr = type2Id2(mType);
						b["msg_id"] = b[idStr];
						b["user_from_id"] = b["uf"];
						b["user_from_token"] = b["uff"];
						b["user_to_id"] = b["ut"];
						b["user_to_token"] = b["uft"];
						b["plan_id"] = b["pid"];
						b["type"] = b["ct"];
						b["live_second"] = b["ls"];
						b["last_updated"] = b["lu"];
						if(mType == "good"){
							b["num"] = 1;
							b["user_id"] = b["user_to_id"];
						}else{
							b["content"] = b["c"];
						}
						if(teacherId == b["user_from_id"]){
							b["is_teacher"] = 1;
						}else{
							b["is_teacher"] = 0;
						}
						o.push(b);
					}
				}
				data = a;
				/*for(var i in a){
					console.log(i+" ["+a[i].length+"]\n");
				}*/
				for(var k in data){
					if("total" == k){
						if(_this._successCallback){
							_this._successCallback(k, [data[k]])
						}
                    }else if(k == "good" || k == "text"){
						var v = data[k];
						if(v.length && v[v.length-1].msg_id > _this._start[k]){
							_this._start[k] = v[v.length-1].msg_id;
						}
						if(v.length){
							if(_this._successCallback){
								_this._successCallback(k, v);
							}
						}
					}else if(k in _this._start){
						var i = 0;
						var v = data[k];
						if(v.length && v[v.length-1].msg_id > _this._start[k]){
							_this._start[k] = v[v.length-1].msg_id;
						}
						var ids = g_msg_ids[k];
						for(i=v.length-1;i>=0;i--){
							if(v[i].msg_id in ids){
								v.splice(i,1);
							}else{
								ids[v[i].msg_id] = 1;
							}
						}
						if(v.length){
							if(_this._successCallback){
								_this._successCallback(k, v);
							}
						}
					}else{
						console.log("*** serious bug! not k = ["+k+"]\n");
					}
				}//console.log("start=["+JSON.stringify(_this._start)+"]\n");
				_this._successCallback("null", null);
			}else{
                if(_this._successSend && "value" in data){
                    if(0 == data["value"]){
                        _this._errorSend(data);
                    }else{
                        _this._successSend(data);
                    }
                }
            }
			if(!window.WebSocket){
				_this.getMsg();
			}
		}
		this._error = function(data){
			if(_this._errorCallback){
				_this._errorCallback(data);
			}
			if(!window.WebSocket){
				setTimeout(function(){_this.getMsg();}, _this._miliSecondsForReconnect);
			}
		}
		$.ajaxSetup({timeout:150000});
	}
	Msg.prototype.setURL = function(getURLWs, getURLPull, setURL){
        if(!getURLWs){
            window.WebSocket = null;
        }
		if(window.WebSocket){
			this._getURL = getURLWs;
		}else{
			this._getURL = getURLPull;
		}
		this._wsURL = getURLWs;
		this._pullURL = getURLPull;
		this._setURL = setURL;
		return this;
	};
	/*Msg.prototype.setStart = function(start){
		if(start > 0){
			this._start = start;
		}
		return this;
	};*/
	Msg.prototype.setPlanId = function(planId){
		this._planId = planId;
		return this;
	};
	Msg.prototype.setSuccessCallback = function(successCallback){
		this._successCallback = successCallback;
		return this;
	};
	Msg.prototype.setErrorCallback = function(errorCallback){
		this._errorCallback = errorCallback;
		return this;
	};
	Msg.prototype.getMsg = function(){
		var request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":this._planId, "StartTextId":this._start["text"], "StartSignalId":this._start["signal"], "StartGoodId":this._start["good"], "DeviceType":deviceType};
		var data = {"data":JSON.stringify(request)};
		var _this = this;
		var flagOpenSuccess = false;
		if(window.WebSocket){
			function startWebSocket(){
				var ws = new WebSocket(_this._getURL);//console.log("new ["+new Date()+"]\n");
				var wsOpenTimerId = setTimeout(function(){
					console.log("websocket open fails, retry!");
					ws.close();
                    wsOpenTimerId = null;
					//_this.getMsg();
				},5000);
				var wsTimerId = null;
				ws.onopen = function(e){//console.log("open ["+new Date()+"]\n");
					clearTimeout(wsOpenTimerId);
					wsOpenTimerId = null;
					flagOpenSuccess = true;
		            var request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":_this._planId, "StartTextId":_this._start["text"], "StartSignalId":_this._start["signal"], "StartGoodId":_this._start["good"], "TextLimit":-50, "DeviceType":deviceType};
					ws.send(JSON.stringify(request));
					var keepFunc = function(){
						ws.send("{}");
						wsTimerId = setTimeout(keepFunc, 10000);
					};
					wsTimerId = setTimeout(keepFunc, 10000);
                    _this._ws = ws;
                    if(_this._outer.postRecord){
                        _this._outer.postRecord();
                    }
				};
				ws.onmessage = function(e){//console.log("message ["+new Date()+"] num=["+e.data.length+"]\n");
					_this._success(e.data);
					//request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":_this._planId, "StartTextId":_this._start["text"], "StartSignalId":_this._start["signal"], "StartGoodId":_this._start["good"]};
					//console.log("request=["+JSON.stringify(request)+"]\n");
					//ws.send(JSON.stringify(request));
				};
				ws.onclose = function(e){console.log("close.....");
					if(wsOpenTimerId){
						clearTimeout(wsOpenTimerId);
						wsOpenTimerId = null;
					}
					if(flagOpenSuccess){
						setTimeout(startWebSocket, _this._miliSecondsForReconnect);
						if(wsTimerId){
							clearTimeout(wsTimerId);
							wsTimerId = null;
						}
					}else{
                        ws.onclose = null;
						window.WebSocket = null;
						_this._getURL = _this._pullURL;
						_this.getMsg();
					}
                    _this._ws = null;
				}
			}
			startWebSocket();
		}else{
            if(g_pullExtraNum > 0){
                g_pullExtraNum--;
                return;
            }
            if(!_this.notPullFirst){
                var request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":_this._planId, "StartTextId":_this._start["text"], "StartSignalId":_this._start["signal"], "StartGoodId":_this._start["good"], "TextLimit":-50, "DeviceType":deviceType};
                $.getScript(this._getURL+"?callback=pull_callback&data="+JSON.stringify(request)).error(this._error);
                _this.notPullFirst = 1;
            }else{
                var request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":_this._planId, "StartTextId":_this._start["text"], "StartSignalId":_this._start["signal"], "StartGoodId":_this._start["good"], "DeviceType":deviceType};
                $.getScript(this._getURL+"?callback=pull_callback&data="+JSON.stringify(request)).error(this._error);
            }
		}
	};
	Msg.prototype.addMsg = function(successCallback, errorCallback, user_to_id, user_to_token, type, content, second){
		var request = { "plan_id":parseInt(this._planId), "user_from_id":parseInt(userId), "user_from_token":userToken, "message_type":MessageType.getMessageType(type), "type":parseInt(type), "device_type":deviceType};
		if(user_to_id){
			request["user_to_id"] = parseInt(user_to_id);
		}
		if(user_to_token){
			request["user_to_token"] = user_to_token;
		}
		if(content){
			request["content"] = decodeURIComponent(content);
		}
		if(second){
			request["live_second"] = parseFloat(second);
		}
        if(window.WebSocket){
            if(this._ws){
                this._ws.send(JSON.stringify(request));
                this._successSend = successCallback;
                this._errorSend = errorCallback;
            }
        }else{
            set_callback = successCallback;
            $.getScript(this._setURL+"?callback=set_callback&data="+JSON.stringify(request)).error(errorCallback);
        }
	};
    Msg.prototype.limitText = function(textId, limit){
		var request = {"MessageType":"get", "UserIdFrom":userId, "UserFlagFrom":userFlag, "PlanId":_this._planId, "StartTextId":textId, "StartSignalId":_this._start["signal"], "StartGoodId":_this._start["good"], "TextLimit":limit, "DeviceType":deviceType};
        if(window.WebSocket){
            if(this._ws){
                this._ws.send(JSON.stringify(request));
            }
        }else{
            $.getScript(this._getURL+"?callback=pull_callback&data="+JSON.stringify(request)).error(this._error);
            g_pullExtraNum++;
        }
    };
	var _msg = new Msg();
	return _msg;
}

function Message(planId, userId, flash, urlWs, urlPull, setURL, getSuccessCallback, addErrorCallback){
	function Outer(){
		this._meList = [];	//正在提交的和没有提交的，因此第一个是正在提交的。内容为处理类型（1--add，），add情况，后面为 user_to_id user_to_token type 文本内容和flash时间,加上一个参数，回调函数；
		this._getFlag = false;	//get成功过一次(signal)
		this._addMsgId = {"text":[], "good":[], "signal":[]};
		//this._getURL = "/message.chat.getmsgv2";
		this._getURL = "http://dev.gn100.com:8001/msg/pull";
		this._addFlag = 0;	//正在进行加的次数
		this._msgTimeoutId = 0;
		this._timeoutInterval = 5000;
		var _this = this;
		//取记录成功的处理函数。把记录（去掉自己的）写下来
		function getSuccess(k, data){
			if("null" == k){
				getSuccessCallback(null);
			}else{
				var finalDeal = {"line":{}, "fullscreen":{}, "pattern":null, "class":null, "ask":{}, "issue":null, "answer":[], "reply":{}, "camera":{},"comeback":{}};
				var length = data.length;
				for(var i=0;i<length;i++){
					var item = data[i];
					var index = jQuery.inArray(parseInt(item.msg_id),_this._addMsgId[k]);
					if(index >= 0){
						_this._addMsgId[k] = _this._addMsgId[k].slice(index+1);
					}else{
						if(!_this._getFlag && "signal" == k){
							if(MessageType.isLive(item.type)){
								continue;
							}
							if(MessageType.on_off_line == item.type){
								if(!(item.user_from_id in finalDeal["line"])){
									finalDeal["line"][item.user_from_id] = {}
								}
								finalDeal["line"][item.user_from_id][item.user_from_token] = item;
								if(item.user_from_id in finalDeal.reply && item.user_from_token in finalDeal.reply[item.user_from_id]){
									delete finalDeal.reply[item.user_from_id][item.user_from_token];
								}
								if(item.user_from_id in finalDeal.ask && item.user_from_token in finalDeal.ask[item.user_from_id]){
									delete finalDeal.ask[item.user_from_id][item.user_from_token];
								}
							}else if(MessageType.fullscreen == item.type){
								if(!(item.user_from_id in finalDeal["fullscreen"])){
									finalDeal["fullscreen"][item.user_from_id] = {}
								}
								finalDeal["fullscreen"][item.user_from_id][item.user_from_token] = item;
							}else if(MessageType.pattern == item.type){
								finalDeal["pattern"] = item;
							}else if(MessageType.start_close == item.type){
								finalDeal["class"] = item;
							}else if(MessageType.ask_cancel == item.type){
								if(!(item.user_from_id in finalDeal["ask"])){
									finalDeal["ask"][item.user_from_id] = {};
								}
								finalDeal["ask"][item.user_from_id][item.user_from_token] = item;
                                if(item.user_from_id in finalDeal["camera"] && item.user_from_token in finalDeal["camera"][item.user_from_id]){
                                    delete finalDeal["camera"][item.user_from_id][item.user_from_token];
                                }
							}else if(MessageType.reply == item.type){
								if(item.user_from_id in finalDeal["reply"]){
									finalDeal["reply"][item.user_from_id][item.user_from_token] = item;
								}else{
									var aaa = {};
									aaa[item.user_from_token] = item;
									finalDeal["reply"][item.user_from_id] = aaa;
								}
							}else if(MessageType.call == item.type){
								if(item.user_to_id in finalDeal["reply"]){
									finalDeal["reply"][item.user_to_id][item.user_to_token] = item;
								}else{
									var aaa = {};
									aaa[item.user_to_token] = item;
									finalDeal["reply"][item.user_to_id] = aaa;
								}
							}else if(MessageType.issue_ask == item.type || MessageType.issue_publish == item.type || MessageType.issue_cancel == item.type){
								finalDeal["issue"] = item;
							}else if(MessageType.issue_answer == item.type){
								finalDeal["answer"].push(item);
							}else if(MessageType.agree_refuse == item.type){
								if(!(item.user_to_id in finalDeal["ask"])){
									finalDeal["ask"][item.user_to_id] = {};
								}
								finalDeal["ask"][item.user_to_id][item.user_to_token] = item;
                                if(item.user_to_id in finalDeal["camera"] && item.user_to_token in finalDeal["camera"][item.user_to_id]){
                                    delete finalDeal["camera"][item.user_to_id][item.user_to_token];
                                }
                            }else if(MessageType.camera == item.type){
								if(!(item.user_from_id in finalDeal["camera"])){
									finalDeal["camera"][item.user_from_id] = {}
								}
								finalDeal["camera"][item.user_from_id][item.user_from_token] = item;
							}else if(MessageType.comeback == item.type){
								if(item.user_from_id in finalDeal["comeback"]){
									delete finalDeal["comeback"][item.user_from_id][item.user_from_token];
								}else{
									if(0 in finalDeal["comeback"]){
										delete finalDeal["comeback"][0];
									}
								}
							}else if(MessageType.recess == item.type){
								if(item.user_to_id in finalDeal["comeback"] && item.user_to_token in finalDeal["comeback"][item.user_to_id]){
									finalDeal["comeback"][item.user_to_id][item.user_to_token] = item;
								}else{
									var aaa = {};
									aaa[item.user_to_token] = item;
									finalDeal["comeback"][item.user_to_id] = aaa;
								}
							}else{
								getSuccessCallback(item);
							}
						}else{
							getSuccessCallback(item);
						}
					}
				}
				if(!_this._getFlag && "signal" == k){
					for(var u in finalDeal["line"]){
						for(var t in finalDeal["line"][u]){
							if("online" == finalDeal["line"][u][t].content){
								getSuccessCallback(finalDeal["line"][u][t]);
							}
						}
					}
					for(var u in finalDeal["ask"]){
						var _asks = {"ask":1, "agree":1, "asking":1};
						var _a = finalDeal["ask"][u];
						for(var t in _a){
							if(_a[t].content in _asks){//console.log("ask:["+JSON.stringify(_a[t])+"]\n");
								getSuccessCallback(_a[t]);
							}
						}
					}
					for(var u in finalDeal["comeback"]){
						for(var t in finalDeal["comeback"][u]){
							getSuccessCallback(finalDeal["comeback"][u][t]);
						}
					}
					for(var u in finalDeal["fullscreen"]){
						for(var t in finalDeal["fullscreen"][u]){
							getSuccessCallback(finalDeal["fullscreen"][u][t]);
						}
					}
					if(finalDeal["pattern"]){
						getSuccessCallback(finalDeal["pattern"]);
					}
					if(finalDeal["class"]){
						getSuccessCallback(finalDeal["class"]);
					}
					if(finalDeal["issue"]){
						getSuccessCallback(finalDeal["issue"]);
					}
					if(finalDeal["answer"]){
						for(var i in finalDeal["answer"]){
							getSuccessCallback(finalDeal["answer"][i]);
						}
					}
					for(var u in finalDeal["reply"]){
						for(var token in finalDeal["reply"][u]){
							getSuccessCallback(finalDeal["reply"][u][token]);
						}
					}
                    var cameraFlag = false;
                    for(var u in finalDeal["camera"]){
                        for(var token in finalDeal["camera"][u]){
                            if("open" == finalDeal["camera"][u][token].content){
							    getSuccessCallback(finalDeal["camera"][u][token]);
                                cameraFlag = true;
                                break;
                            }
                        }
                        if(cameraFlag){
                            break;
                        }
                    }
					_this._getFlag = true;
				}
			}
		}
		this._msg = BasicMessage(this);
		/*var geturl = this._getURL;
		window.WebSocket = null;
		if(window.WebSocket){
			geturl = this._getURLByWS
		}*/
		this._msg.setURL(urlWs, urlPull, setURL).setPlanId(planId).setSuccessCallback(getSuccess).setErrorCallback(null);
		this.getMsg = function(){
			_this._msg.getMsg();
			//_this._msgTimeoutId = setTimeout(getMsg, _this._timeoutInterval);
		}
		//getMsg();
		//this.getMsg = getMsg;
		this.addOrDelete = function(){
			if(1 == _this._meList[0][0]){
				_this._msg.addMsg(_this.addSuccess, _this.addError, _this._meList[0][1], _this._meList[0][2], _this._meList[0][3], _this._meList[0][4], _this._meList[0][5]);
			}
		};
		this.postRecord = function(){
			if(_this._meList.length){
				_this.addOrDelete();
				_this._addFlag = 1;
			}else{
				_this._addFlag = 0;
			}
		};
		//插数据库成功的回调函数
		this.addSuccess = function(data){
			var a = _this._meList.shift();
			if(1 == a[0]){
				var mType = data["key"];
				if(mType in g_msg_ids){
                    msg_id = parseInt(data["value"]);
                    var ids = g_msg_ids[mType];
                    if(0 == msg_id || msg_id in ids){
                        ;
                    }else{
                    	if(a[6]){
	                    	if(a[3] == MessageType.gift){
	                    		a[6](msg_id, JSON.parse(data.info));
	                    	}else{
	                    		ids[msg_id] = 1;
	                    		a[6](msg_id);
	                    	}
                    	}
                    }
				}
			}
			_this.postRecord();
		};
		this.addError = function(data){
			if(_this._addFlag < 2){
				setTimeout(function(){
					_this.addOrDelete();
					_this._addFlag++;
				}, 1000);
			}else{
				var a = _this._meList.shift();
				if(addErrorCallback){
					addErrorCallback(data, a);
				}
				if(_this._meList.length){
					setTimeout(function(){
						_this.postRecord();
					}, 1000);
				}
			}
		};
	}
	//加信息接口，也提供给外部使用
	Outer.prototype.addFunc = function(user_to_id, user_to_token, type, content, callback){
		var second = 0;
		try{
			var a = flash.info().currentTime;
			if(a){
				second = a;
			}
		}catch(e){
		}
		if(!second){
			try{
				var a = H5Player.info().currentTime;
				if(a){
					second = a;
				}
			}catch(e){}
		}
		this._meList.push([1, user_to_id, user_to_token, type, content, second, callback]);
		if(1 == this._meList.length){
			this.postRecord();
		}
	};
	Outer.prototype.comeback = function(){
		this.addFunc(0, "", MessageType.comeback);
	}
	Outer.prototype.recess = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.recess);
	};
	Outer.prototype.text = function(t, callback){
		this.addFunc(0, "", MessageType.text, t, callback);
	};
	Outer.prototype.giftSend = function(t, callback){
		this.addFunc(0, "", MessageType.gift, t, callback);
	};
	Outer.prototype.ask = function(){
		this.addFunc(0, "", MessageType.ask_cancel, "ask");
	};
	Outer.prototype.cancel = function(){
		this.addFunc(0, "", MessageType.ask_cancel, "cancel");
	};
	Outer.prototype.cameraOpen = function(){
		this.addFunc(0, "", MessageType.camera, "open");
	};
	Outer.prototype.cameraClose = function(){
		this.addFunc(0, "", MessageType.camera, "close");
	};
	Outer.prototype.agree = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.agree_refuse, "agree");
	};
	Outer.prototype.refuse = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.agree_refuse, "refuse");
	};
	Outer.prototype.call = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.call);
	};
	Outer.prototype.reply = function(){
		this.addFunc(0, "", MessageType.reply);
	};
	Outer.prototype.stop = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.agree_refuse, "stop");
	};
	Outer.prototype.asking = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.agree_refuse, "asking");
	};
	Outer.prototype.good = function(toId, callback){
		this.addFunc(toId, "", MessageType.good, "1", callback);
	};
	Outer.prototype.offline = function(){
		this.addFunc(0, "", MessageType.on_off_line, "offline");
	};
	Outer.prototype.start = function(callback){
		this.addFunc(0, "", MessageType.start_close, "start", callback);
	};
	Outer.prototype.close = function(callback){
		this.addFunc(0, "", MessageType.start_close, "close", callback);
	};
	Outer.prototype.pattern_normal = function(){
		this.addFunc(0, "", MessageType.pattern, "normal");
	};
	Outer.prototype.pattern_reply = function(){
		this.addFunc(0, "", MessageType.pattern, "reply");
	};
	Outer.prototype.pattern_notalk = function(){
		this.addFunc(0, "", MessageType.pattern, "notalk");
	};
	Outer.prototype.startClass = function(){
		this._timeoutInterval = 2000;
		/*if(!this._msgTimeoutId){
			this.getMsg();
		}*/
	}
	Outer.prototype.stopClass = function(){
		this._timeoutInterval = 5000;
		/*if(this._msgTimeoutId){
			clearTimeout(this._msgTimeoutId);
			this._msgTimeoutId = null;
		}*/
	}
	Outer.prototype.microphone_test = function(toId, toToken){
		this.addFunc(toId, toToken, MessageType.microphone_test);
	};
	Outer.prototype.microphone_succeed = function(){
		this.addFunc(0, "", MessageType.microphone_result, "succeed");
	};
	Outer.prototype.microphone_fail = function(){
		this.addFunc(0, "", MessageType.microphone_result, "fail");
	};
	Outer.prototype.single_notalk = function(toId){
		this.addFunc(toId, "", MessageType.single_notalk, "forbid");
	};
	Outer.prototype.single_free = function(toId){
		this.addFunc(toId, "", MessageType.single_notalk, "free");
	};
	Outer.prototype.issue_ask = function(t){
		this.addFunc(0, "", MessageType.issue_ask, t);
	};
	Outer.prototype.issue_publish = function(t){
		this.addFunc(0, "", MessageType.issue_publish, t);
	};
	Outer.prototype.issue_answer = function(t){
		this.addFunc(0, "", MessageType.issue_answer, t);
	};
	Outer.prototype.fullscreen = function(t){
		this.addFunc(0, "", MessageType.fullscreen, ""+t);
	};
	Outer.prototype.request_eval = function(t){
		this.addFunc(0, "", MessageType.request_eval, ""+t);
	};
	Outer.prototype.issue_cancel = function(t){
		this.addFunc(0, "", MessageType.issue_cancel, t);
	};
	Outer.prototype.delete_text = function(t){
		this.addFunc(0, "", MessageType.delete_text, t);
	};
    Outer.prototype.limitText = function(textId, limit){
        this._msg.limitText(textId, limit);
    };
	var _outer = new Outer();
	return _outer;
}
