(function(name,id){
	function Player(id){
		if(!id)id="OSMFPlayer";
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
		var x = this.player(this._id);
		return this.player(this._id).play();
	}
	Player.prototype.pause= function(){
		return this.player(this._id).pause();
	}
	Player.prototype.stop= function(){
		return this.player(this._id).stop();
	}
	Player.prototype.seek= function(msec){
		var x = this.player(this._id);
		return this.player(this._id).play(msec);
	}
	window[name]=new Player();
})("Player","OSMFPlayer");
