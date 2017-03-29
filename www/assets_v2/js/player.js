(function(name,id){
	function _Player(id){
		if(!id)id="player";
		this._id=id;
	}
	_Player.prototype.player=function(){
		if (navigator.appName.indexOf("Microsoft") != -1) {
			return window[this._id];
		} else {
			return document[this._id];
		}
	}
	_Player.prototype.id=function(){
		return this._id;
	}
	_Player.prototype.testMicrophone=function(flag){
		try{
			return this.player(this._id).testMicrophone(flag);
		}
		catch(e){
			return false;
		}
	}
	_Player.prototype.play = function(){
		try{
			return this.player(this._id).playVideo();
		}catch(e){return false;}
	}
	_Player.prototype.error= function(error){
		console.log(error);
		try{
			if(error.code!=-412){
				showPlayerMessage("您好，课程刚结束，还需要几分钟的处理才能观看，请稍候再来观看！",0,"");
				//showPlayerMessage('出错了('+error.code+")",0,error.msg);
			}
		}catch(e){
		}

	}
	_Player.prototype.reInit= function(){
		try{
			return this.player(this._id).reInit();
		}catch(e){return false;}
	}
	_Player.prototype.playEditer = function(direct, original){
		try{
			return this.player(this._id).reInit(direct, original);
		}catch(e){return false;}
	}
	_Player.prototype.pause= function(){
		try{
			return this.player(this._id).pauseVideo();
		}catch(e){return false;}
	}
	_Player.prototype.setFullScreen= function(flag){
		try{
			return this.player(this._id).setFullScreen(flag);
		}catch(e){return false;}
	}
	_Player.prototype.close= function(){
		try{
			return this.player(this._id).closeVideo();
		}catch(e){return false;}
	}
	_Player.prototype.quitFullScreen= function(){
		try{
			return this.player(this._id).quitVideoFullscreen();
		}catch(e){return false;}
	}
	_Player.prototype.stop= function(){
		try{
			return this.player(this._id).stopVideo();
		}catch(e){return false;}
	}
	_Player.prototype.seek= function(sec){
		try{
			return this.player(this._id).seekVideo(sec);
		}catch(e){return false;}
	}
	_Player.prototype.stopRecord= function(){
		try{
			return this.player(this._id).stoprecord();
		}catch(e){return false;}
	}
	_Player.prototype.record= function(){
		try{
			return this.player(this._id).record();
		}catch(e){return false;}
	}
	_Player.prototype.showSpeakingTips= function(name, head){
		try{
			return this.player(this._id).showSpeakingTips(name, head);
		}catch(e){return false;}
	}
	_Player.prototype.hideSpeakingTips= function(){
		try{
			return this.player(this._id).hideSpeakingTips();
		}catch(e){return false;}
	}
	_Player.prototype.startupCameraPlayer= function(){
		try{
			return this.player(this._id).startupCameraPlayer();
		}catch(e){return false;}
	}
	_Player.prototype.closeCameraPlayer= function(){
		try{
			return this.player(this._id).closeCameraPlayer();
		}catch(e){return false;}
	}
	_Player.prototype.info= function(){
		try{
			return this.player(this._id).info();
		}catch(e){return false;}
	}
	_Player.prototype.startPlan= function(plan_id,callback){
		$.post("/course.plan.startPlan/"+plan_id,{},function(r){
			if(r){
				if(r.code==0){
					Player.close();
					Player.reInit();
				}
				if(typeof (callback)=="function"){
					callback(r);
				}
			}
		},"json");
	}
	_Player.prototype.reStartPlan= function(plan_id,callback){
		$.post("/course.plan.startPlan/"+plan_id+"/cleanFile",{},function(r){
			if(r){
				if(r.code==0){
					Player.close();
					Player.reInit();
				}
				if(typeof (callback)=="function"){
					callback(r);
				}
			}
		},"json");
	}
	_Player.prototype.stopPlan= function(plan_id,callback){
		$.post("/course.plan.stopPlan/"+plan_id,{},function(r){
			if(r){
				if(r.code==0){
					Player.close();
				}
				if(typeof (callback)=="function"){
					callback(r);
				}
			}
		},"json");
	}
	window[name]=new _Player();
})("Player","player");
(function(name,id){
	var _isFull;
	function _FullScreen(id){
		this._id=id;
		this._isFull=false;
		var evento = function (e) {
			var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
			if (fullscreenElement) {
				// we are now on fullscreen
				_isFull=true;
				Player.setFullScreen(true);
			} else {
				_isFull=false;
				Player.setFullScreen(false);
				// we have exit fullscreen mode
			}
		};
		if(document.addEventListener){
			document.addEventListener('fullscreenchange', evento, false);
			document.addEventListener('mozfullscreenchange', evento, false);
			document.addEventListener('webkitfullscreenchange', evento, false);
			document.addEventListener('MSFullscreenChange', evento, false);
		}
	}
	_FullScreen.prototype.flag=function(flag){
		$("#"+this._id).attr("flag",falg);
	}
	_FullScreen.prototype.getFlag=function(){
		return $("#"+this._id).attr("flag");
	}
	_FullScreen.prototype.id=function(){
		return this._id;
	}
	_FullScreen.prototype.toggle=function(){
		console.log(_isFull);
		if(_isFull){
			this.exitFull();
		}else{
			this.Full();
		}
	}
	_FullScreen.prototype.exitFull=function() {
		var element = document;
		var requestMethod = element.cancelFullScreen || element.exitFullscreen || element.msExitFullscreen || element.mozCancelFullScreen || element.webkitCancelFullScreen ;
		console.log("ex1");
		if (requestMethod) { // Native full screen.
			requestMethod.call(element,Element.ALLOW_KEYBOARD_INPUT);
		}else{
			console.log("ex3");
			_isFull=false;
			Player.setFullScreen(false);
			$("#"+this._id).removeClass("fullscreen");
		}
	}
	_FullScreen.prototype.Full=function() {
		var element = document.getElementById(this._id);
		var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

		if (requestMethod) {
			requestMethod.call(element,Element.ALLOW_KEYBOARD_INPUT);
		}else {
			_isFull=true;
			Player.setFullScreen(true);
			$("#"+this._id).addClass("fullscreen");
		}
	}
	window[name]=new _FullScreen(id);
})("FullScreen","content");
