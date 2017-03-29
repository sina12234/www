//参数： 插入之前的div 输入框 send按钮 课程id 用户id flash对象 把返回信息变成dom的函数 把用户输入信息变成dom的函数 用户输入信息提交失败报错的生成dom的函数
function message(preDiv, textarea, sendButton, planId, userId, flash, domFunc, domMeFunc, domErrFunc)
{
	var _meList = [];	//正在提交的和没有提交的，因此第一个是正在提交的。内容为文本内容和flash时间
	var _getFlag = false;	//get成功过一次
	var _getURL = "/message.chat.getmsg";
	var _setURL = "/message.chat.addmsg";
	var _addFlag = 0;	//正在进行加的次数
	function getSuccess(data){	//取记录成功的处理函数。把记录（去掉自己的）写下来
		for(var i=0;i<data.length;i++){
			var item = data[i];
			if(_getFlag && item.user_from_id == userId){
				continue;
			}else{
				var dom = domFunc(item);
				$(dom).insertBefore(preDiv);
			}
		}
		_getFlag = true;
	}
	function postRecord(){
		if(_meList.length){
			_msg.addMsg(addSuccess, addError, _meList[0][0], _meList[0][1]);
			_addFlag = 1;
		}else{
			_addFlag = 0;
		}
	}
	function addSuccess(data){	//插数据库成功的回调函数
		_meList.shift();
		postRecord();
	}
	function addError(data){
		if(_addFlag < 2){
			_msg.addMsg(addSuccess, addError, _meList[0][0], _meList[0][1]);
			_addFlag++;
		}else{
			var a = _meList.shift();
			var dom = domErrFunc(a);
			$(dom).insertBefore(preDiv);
			postRecord();
		}
	}
	function addMsg(e){
		var v = textarea.val();
		if(!v){
			return;
		}
		var info = flash.info();
		var dom = domMeFunc([v, info.currentTime]);
		$(dom).insertBefore(preDiv);
		_meList.push([v, info.currentTime]);
		if(1 == _meList.length){
			postRecord();
		}
		textarea.val("");
	}
	sendButton.click(addMsg);
	textarea.keypress(function(e){
		if(10 == e.keyCode && e.ctrlKey){
			addMsg(e);
		}
	});
	function Msg(){ 
		this._start = 1;
		this._successCallback = null;
		this._errorCallback = null;
		_this = this;
		this._success = function(data){
			while(data.length){
				if(data[0].msg_id <= _this._start){
					data.shift();
				}else{
					break;
				}
			}
			if(!data.length){
				return;
			}
			_this._start = data[data.length-1].msg_id;
			if(_this._successCallback){
				_this._successCallback(data);
			}
		}
		this._error = function(data){
			if(_this._errorCallback){
				_this._errorCallback(data);
			}
		}
		$.ajaxSetup({timeout:2000});
	}
	Msg.prototype.setURL = function(getURL, setURL){ 
		this._getURL = getURL;
		this._setURL = setURL;
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
		var request = { "plan_id":this._planId, "msg_id":this._start};
		//var request = { "plan_id":this._planId, "msg_id":this._start, "user_from_id":userId};
		$.post(this._getURL, request, this._success, "json").error(this._error);
	};
	Msg.prototype.addMsg = function(successCallback, errorCallback, content, second, type, style, user_to_id){ 
		var request = { "plan_id":this._planId, "content":content, "live_second":second};
		//var request = { "plan_id":this._planId, "content":content, "live_second":second, "user_from_id":userId};
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
	var _msg = new Msg();
	_msg.setURL(_getURL, _setURL).setPlanId(planId).setSuccessCallback(getSuccess).setErrorCallback(null);
	function getMsg(){
		_msg.getMsg();
		setTimeout(getMsg, 3000);
	}
	getMsg();
}
