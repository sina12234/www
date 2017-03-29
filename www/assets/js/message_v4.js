//信息类型定义
var MessageType = {
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
	isLive	:	function(t){
		switch(parseInt(t)){
		case this.call:
		case this.reply:
		case this.microphone_test:
		case this.ask_cancel:		//这个和下一个，初始化时老师这边需要考虑合并取最后一个状态
		case this.agree_refuse:
			return true;
		default:
			return false;
		}
	}
};

function BasicMessage(){
	function Msg(){ 
		this._start = {"text":0, "signal":0, "good":0};
		this._successCallback = null;
		this._errorCallback = null;
		_this = this;
		this._success = function(data){
			for(var k in data){
				if("total" == k){
					if(_this._successCallback){
						_this._successCallback(k, [data[k]])
					}
				}else if(k in _this._start){
					var i = 0;
					var v = data[k];
					for(;i<v.length;i++){
						if(v[i].msg_id > _this._start[k]){
							break;
						}
					}
					if(v.length != i){
						_this._start[k] = v[v.length-1].msg_id;
						if(_this._successCallback){
							_this._successCallback(k, v.slice(i));
						}
					}
				}else{
					console.log("*** serious bug! not k = ["+k+"]\n");
				}
			}
		}
		this._error = function(data){
			if(_this._errorCallback){
				_this._errorCallback(data);
			}
		}
		$.ajaxSetup({timeout:2000});
	}
	Msg.prototype.setURL = function(getURL, setURL, deleteURL){ 
		this._getURL = getURL;
		this._setURL = setURL;
		this._deleteURL = deleteURL;
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
	Msg.prototype.setErrorCallback = function(errerCallback){
		this._errorCallback = errerCallback;
		return this;
	};
	Msg.prototype.getMsg = function(){
		var request = { "plan_id":this._planId, "msg_id":this._start};
		$.post(this._getURL, request, this._success, "json").error(this._error);
	};
	Msg.prototype.addMsg = function(successCallback, errorCallback, user_to_id, user_to_token, type, content, second){
		var request = { "plan_id":this._planId, "type":type};
		if(user_to_id){
			request["user_to_id"] = user_to_id;
		}
		if(user_to_token){
			request["user_to_token"] = user_to_token;
		}
		if(content){
			request["content"] = content;
		}
		$.post(this._setURL, request, successCallback, "json").error(errorCallback);
	};
	Msg.prototype.deleteMsg = function(successCallback, errorCallback, msg_id, status){ 
		console.log("no deleteMsg!");
	};
	var _msg = new Msg();
	return _msg;
}

function Message(planId, userId, flash, getSuccessCallback, addErrorCallback){
	function Outer(){
		this._meList = [];	//正在提交的和没有提交的，因此第一个是正在提交的。内容为处理类型（1--add，2--delete），add情况，后面为 user_to_id user_to_token type 文本内容和flash时间；delete情况。后面为 msg_id [status]
		this._getFlag = false;	//get成功过一次(signal)
		this._addMsgId = {"text":[], "good":[], "signal":[]};
		this._getURL = "/message.chat.getmsgv2";
		this._setURL = "/message.chat.addmsgv2";
		this._deleteURL = "/message.chat.deletemsg";
		this._addFlag = 0;	//正在进行加的次数
		this._msgTimeoutId = 0;
		this._timeoutInterval = 5000;
		var _this = this;
		//取记录成功的处理函数。把记录（去掉自己的）写下来
		function getSuccess(k, data){
			var finalDeal = {"line":{}, "fullscreen":{}, "pattern":null, "class":null};
			for(var i=0;i<data.length;i++){
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
						}else if(MessageType.fullscreen == item.type){
							if(!(item.user_from_id in finalDeal["fullscreen"])){
								finalDeal["fullscreen"][item.user_from_id] = {}
							}
							finalDeal["fullscreen"][item.user_from_id][item.user_from_token] = item;
						}else if(MessageType.pattern == item.type){
							finalDeal["pattern"] = item;
						}else if(MessageType.start_close == item.type){
							finalDeal["class"] = item;
						}
					}else{
						getSuccessCallback(item);
					}
				}
			}
			if(!_this._getFlag && "signal" == k){
				for(var u in finalDeal["line"]){
					for(var t in finalDeal["line"][u]){
						getSuccessCallback(finalDeal["line"][u][t]);
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
				_this._getFlag = true;
			}
			getSuccessCallback(null);
		}
		this._msg = BasicMessage();
		this._msg.setURL(this._getURL, this._setURL, this._deleteURL).setPlanId(planId).setSuccessCallback(getSuccess).setErrorCallback(null);
		function getMsg(){
			_this._msg.getMsg();
			_this._msgTimeoutId = setTimeout(getMsg, _this._timeoutInterval);
		}
		getMsg();
		this.getMsg = getMsg;
		this.addOrDelete = function(){
			if(1 == _this._meList[0][0]){
				_this._msg.addMsg(_this.addSuccess, _this.addError, _this._meList[0][1], _this._meList[0][2], _this._meList[0][3], _this._meList[0][4], _this._meList[0][5]);
			}else{
				_this._msg.deleteMsg(_this.addSuccess, _this.addError, _this._meList[0][1], _this._meList[0][2]);
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
				if(data["key"] in _this._addMsgId){
					_this._addMsgId[data["key"]].push(parseInt(data["value"]));
				}
			}
			_this.postRecord();
		};
		this.addError = function(data){
			if(_this._addFlag < 2){
				_this.addOrDelete();
				_this._addFlag++;
			}else{
				var a = _this._meList.shift();
				if(addErrorCallback){
					addErrorCallback(data, a);
				}
				_this.postRecord();
			}
		};
	}
	//加信息接口，也提供给外部使用
	Outer.prototype.addFunc = function(user_to_id, user_to_token, type, content){
		var second = 0;
		try{
			var a = flash.info().currentTime;
			if(a){
				second = a;
			}
		}catch(e){
		}
		this._meList.push([1, user_to_id, user_to_token, type, content, second]);
		if(1 == this._meList.length){
			this.postRecord();
		}
	};
	Outer.prototype.deleteFunc = function(msg_id, status){
		this._meList.push([2, msg_id, status]);
		if(1 == this._meList.length){
			this.postRecord();
		}
	};
	Outer.prototype.text = function(t){
		this.addFunc(0, "", MessageType.text, t);
	};
	Outer.prototype.ask = function(){
		this.addFunc(0, "", MessageType.ask_cancel, "ask");
	};
	Outer.prototype.cancel = function(){
		this.addFunc(0, "", MessageType.ask_cancel, "cancel");
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
	Outer.prototype.good = function(toId){
		this.addFunc(toId, "", MessageType.good);
	};
	Outer.prototype.start = function(){
		this.addFunc(0, "", MessageType.start_close, "start");
	};
	Outer.prototype.close = function(){
		this.addFunc(0, "", MessageType.start_close, "close");
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
	Outer.prototype.fullscreen = function(t){
		this.addFunc(0, "", MessageType.fullscreen, ""+t);
	};
	var _outer = new Outer();
	return _outer;
}
