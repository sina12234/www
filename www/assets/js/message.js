//信息类型定义
var MessageType = {
	text	:	1,		//文本聊天
	ask		:	2,		//举手发言
	cancel	:	3,		//取消发言或结束发言（学生发起的）
	agree	:	4,		//同意发言
	refuse	:	5,		//拒绝发言
	call	:	6,		//点名
	reply	:	7,		//答道
	stop	:	8,		//结束发言（老师发起的）
	asking	:	9,		//老师主动要求学生发言
	good	: 	100,	//点赞
	online 	: 	200,	//用户上线消息
	offline	: 	201,	//用户下线消息
	start	: 	300,	//开始上课消息
	close	: 	301,	//结束上课消息	
	pattern_normal	:	400,	//普通模式
	pattern_reply	:	401,	//答题模式
	pattern_notalk	:	402,	//禁言模式
	reply_text	:	500,	//答题模式文本
	reply_text_display	:	501,	//显示答题模式文本指令
	student_total	:	600,	//学生总数
	microphone_test	:	700,	//测试麦克风
	microphone_succeed	:	701,	//测试麦克风成功
	microphone_fail	:	702,	//测试麦克风失败
	fullscreen	:	800,	//全屏状态改变
	isLive	:	function(t){
					switch(parseInt(t)){
					case this.ask:
					case this.cancel:
					case this.agree:
					case this.refuse:
					case this.call:
					case this.reply:
					case this.stop:
					case this.asking:
					case this.microphone_test:
					case this.microphone_succeed:
					case this.microphone_fail:
					//case this.start:
					//case this.stop:
						return true;
					default:
						return false;
					}
				}
};

function BasicMessage(){
	function Msg(){ 
		this._start = 1;
		this._successCallback = null;
		this._errorCallback = null;
		_this = this;
		this._success = function(data){
			var i = 0;
			if(data.length > 0 && MessageType.student_total == data[0].type){
				if(_this._successCallback){
					_this._successCallback([data[0]]);
					i = 1;
				}
			}
			for(;i<data.length;i++){
				if(data[i].msg_id > _this._start){
					break;
				}
			}
			if(data.length == i){
				return;
			}
			_this._start = data[data.length-1].msg_id;
			if(_this._successCallback){
				_this._successCallback(data.slice(i));
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
	Msg.prototype.setStart = function(start){
		if(start > 0){
			this._start = start;
		}
		return this;
	};
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
		var request = { "plan_id":this._planId, "msg_id":this._start, "class_id":class_id};
		$.post(this._getURL, request, this._success, "json").error(this._error);
	};
	Msg.prototype.addMsg = function(successCallback, errorCallback, content, second, type, style, user_to_id){ 
		console.log("add content=["+content+"] type=["+type+"] second=["+second+"]");
		var request = { "plan_id":this._planId, "content":content, "live_second":second};
		if(type){ 
			request["type"] = type;
		}
		if(style){ 
			request["style"] = style;
		}
		if(user_to_id){ 
			request["user_to_id"] = user_to_id;
		}
		$.post(this._setURL, request, successCallback, "json").error(errorCallback);
	};
	Msg.prototype.deleteMsg = function(successCallback, errorCallback, msg_id, status){ 
		var request = { "msg_id":msg_id};
		if(status || 0===status){ 
			request["status"] = status;
		}
		$.post(this._deleteURL, request, successCallback, "json").error(errorCallback);
	};
	var _msg = new Msg();
	return _msg;
}

function Message(planId, userId, flash, getSuccessCallback, addErrorCallback){
	function Outer(){
		this._meList = [];	//正在提交的和没有提交的，因此第一个是正在提交的。内容为处理类型（1--add，2--delete），add情况，后面为 文本内容和flash时间，type，style，user+to_id；delete情况。后面为 msg_id [status]
		this._addMsgId = [];
		this._getFlag = false;	//get成功过一次
		this._getURL = "/message.chat.getmsg";
		this._setURL = "/message.chat.addmsg";
		this._deleteURL = "/message.chat.deletemsg";
		this._addFlag = 0;	//正在进行加的次数
		this._msgTimeoutId = 0;
		this._timeoutInterval = 5000;
		var _this = this;
		//取记录成功的处理函数。把记录（去掉自己的）写下来
		function getSuccess(data){
			var finalDeal = {"line":{}, "fullscreen":{}, "pattern":null, "class":null};
			var normalNum = 0;
			for(var i=0;i<data.length;i++){
				var item = data[i];
				if(item.user_to_id != 0 && item.user_to_id != userId){
					continue;
				}
				//var index = _this._addMsgId.indexOf(parseInt(item.msg_id));
				if(MessageType.student_total != item.type){
					normalNum++;
				}
				var index = jQuery.inArray(parseInt(item.msg_id),_this._addMsgId);
				if(index >= 0){
					_this._addMsgId = _this._addMsgId.slice(index+1);
				}else{
					if(!_this._getFlag){
						if(MessageType.isLive(item.type)){
							continue;
						}
						if(MessageType.online == item.type || MessageType.offline == item.type){
							finalDeal["line"][item.user_from_id] = item;
							continue;
						}else if(MessageType.fullscreen == item.type){
							finalDeal["fullscreen"][item.user_from_id] = item;
							continue;
						}else if(MessageType.pattern_normal == item.type || MessageType.pattern_reply == item.type || MessageType.pattern_notalk == item.type){
							finalDeal["pattern"] = item;
							continue;
						}else if(MessageType.start == item.type || MessageType.close == item.type){
							finalDeal["class"] = item;
							continue;
						}
					}
					getSuccessCallback(item);
				}
			}
			for(u in finalDeal["fullscreen"]){
				getSuccessCallback(finalDeal["fullscreen"][u]);
			}
			for(u in finalDeal["line"]){
				getSuccessCallback(finalDeal["line"][u]);
			}
			if(finalDeal["pattern"]){
				getSuccessCallback(finalDeal["pattern"]);
			}
			if(finalDeal["class"]){
				getSuccessCallback(finalDeal["class"]);
			}
			if(normalNum){
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
				_this._addMsgId.push(parseInt(data));
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
	Outer.prototype.addFunc = function(content, type, style, user_to_id){ console.log("addFunc type=["+type+"]");
		var second = 0;
		try{
			var a = flash.info().currentTime;
			if(a){
				second = a;
			}
		}catch(e){
		}
		this._meList.push([1, content, second, type, style, user_to_id]);
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
	Outer.prototype.text = function(t, toId){
		this.addFunc(t, MessageType.text, 0, toId);
	};
	Outer.prototype.ask = function(){
		this.addFunc("", MessageType.ask);
	};
	Outer.prototype.cancel = function(){
		this.addFunc("", MessageType.cancel);
	};
	Outer.prototype.agree = function(toId){
		//this.addFunc("", MessageType.agree, 0, toId);
		this.addFunc(""+toId, MessageType.agree);
	};
	Outer.prototype.refuse = function(toId){
		this.addFunc("", MessageType.refuse, 0, toId);
	};
	Outer.prototype.call = function(toId){
		this.addFunc("", MessageType.call, 0, toId);
	};
	Outer.prototype.reply = function(toId){
		this.addFunc("", MessageType.reply, 0, toId);
	};
	Outer.prototype.stop = function(toId){
		this.addFunc("", MessageType.stop, 0, toId);
	};
	Outer.prototype.asking = function(toId){
		//this.addFunc("", MessageType.asking, 0, toId);
		this.addFunc(""+toId, MessageType.asking);
	};
	Outer.prototype.good = function(toId){
		this.addFunc(""+toId, MessageType.good);
	};
	Outer.prototype.start = function(){
		this.addFunc("", MessageType.start);
	};
	Outer.prototype.close = function(){
		this.addFunc("", MessageType.close);
	};
	Outer.prototype.pattern_normal = function(){
		this.addFunc("", MessageType.pattern_normal);
	};
	Outer.prototype.pattern_reply = function(){
		this.addFunc("", MessageType.pattern_reply);
	};
	Outer.prototype.pattern_notalk = function(){
		this.addFunc("", MessageType.pattern_notalk);
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
	Outer.prototype.microphone_test = function(toId){
		this.addFunc(""+toId, MessageType.microphone_test);
	};
	Outer.prototype.microphone_succeed = function(){
		this.addFunc("", MessageType.microphone_succeed);
	};
	Outer.prototype.microphone_fail = function(){
		this.addFunc("", MessageType.microphone_fail);
	};
	Outer.prototype.fullscreen = function(t){
		this.addFunc(""+t, MessageType.fullscreen);
	};
	var _outer = new Outer();
	return _outer;
}
