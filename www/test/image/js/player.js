(function(){
	function _Player(id){
		this._id=id;
	}
	_Player.prototype.player=function(){
		if (navigator.appName.indexOf("Microsoft") != -1) {
			return window[this._id];
		} else {
			return document[this._id];
		}
	}
	_Player.prototype.inited = function(){
		console.log(this.getVersion());
		this.openImage();
	}
	_Player.prototype.getVersion = function(){
		return this.player(this._id).getVersion();
	}
	_Player.prototype.openImage = function(){
		console.log("openImage");
		var uploadURL = "http://dev.gn100.com/file/main/taskFlashUpload";
		var url = "http://dev.gn100.com/test/image/123.jpg";
		this.player(this._id).openImage(url, uploadURL,"HomeworkEditer.close", "HomeworkEditer.close");
	}
	_Player.prototype.close = function(){
		console.log("close");
	}
	
	window["HomeworkEditer"]=new _Player("HomeworkEditer");
})();