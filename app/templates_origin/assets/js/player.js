(function(name,id){
	function Player(id){
		if(!id)id="player";
		this._id=id;
	}
	Player.prototype.player=function(){
		if (navigator.appName.indexOf("Microsoft") != -1) {
			return window[this._id];
		} else {
			return document[this._id];
		}
	}
	Player.prototype.id=function(){
		return this._id;
	}
	Player.prototype.play = function(){
		return this.player(this._id).playVideo();
	}
	Player.prototype.pause= function(){
		return this.player(this._id).pauseVideo();
	}
	Player.prototype.setFullScreen= function(flag){
		return this.player(this._id).setFullScreen(flag);
	}
	Player.prototype.stop= function(){
		return this.player(this._id).stopVideo();
	}
	Player.prototype.seek= function(sec){
		return this.player(this._id).seekVideo(sec);
	}
	Player.prototype.stopRecord= function(sec){
		return this.player(this._id).stoprecord(sec);
	}
	Player.prototype.record= function(sec){
		return this.player(this._id).record(sec);
	}
	Player.prototype.startPlan= function(plan_id){
		$.post("/course.plan.startPlan/"+plan_id,{},function(r){
		if(r){
			if(r.code==0){
				alert("成功");
			}else{
				alert(r.error);
			}
		}
		},"json");
	}
	Player.prototype.stopPlan= function(plan_id){
		$.post("/course.plan.stopPlan/"+plan_id,{},function(r){
			if(r){
				if(r.code==0){
					alert("成功");
				}else{
					alert(r.error);
				}
			}
		},"json");
	}
	window[name]=new Player();
})("Player","player");
(function(name,id){
	function FullScreen(id){
		this._id=id;
		this._isFull=false;
		_this = this;
		var evento = function (e) {
			var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
			if (fullscreenElement) {
				// we are now on fullscreen
				_this._isFull=true;
				Player.setFullScreen(true);
			} else {
				_this._isFull=false;
				Player.setFullScreen(false);
				// we have exit fullscreen mode
			}
			//console.log(_this._isFull);
		};
		if(document.addEventListener){
			document.addEventListener('fullscreenchange', evento, false);
			document.addEventListener('mozfullscreenchange', evento, false);
			document.addEventListener('webkitfullscreenchange', evento, false);
			document.addEventListener('MSFullscreenChange', evento, false);
		}
	}
	FullScreen.prototype.id=function(){
		return this._id;
	}
	FullScreen.prototype.toggle=function(){
		if(this._isFull){
			this.exitFull();
		}else{
			this.Full();
		}
	}
	FullScreen.prototype.exitFull=function() {
		var element = document;
		var requestMethod = element.cancelFullScreen || element.exitFullscreen || element.msExitFullscreen || element.mozCancelFullScreen || element.webkitCancelFullScreen ;

		if (requestMethod) { // Native full screen.
			requestMethod.call(element);
		}else{
			this._isFull=false;
			Player.setFullScreen(false);
			$("#"+this._id).attr("style","");
		}
	}
	FullScreen.prototype.Full=function() {
		var element = document.getElementById(this._id);
		var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

		if (requestMethod) { 
			requestMethod.call(element);
		}else {
			this._isFull=true;
			Player.setFullScreen(true);
			$("#"+this._id).attr("style","position:absolute;top:0px;width:100%;height:100%;background-color:white;");
		}
	}
	window[name]=new FullScreen(id);
})("FullScreen","content");
