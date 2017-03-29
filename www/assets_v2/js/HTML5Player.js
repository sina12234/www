/**
 * ...
 * @author gonghao
 */
(function(id) {
	var _id = id;
	var _route = "hls_v2";
	var _TimerId = null;

	function HTML5Player(player){
	}
	HTML5Player.prototype.init = function(planID, infoDomain, errorFun){
		if($(window).width()>768){
			$('#flash-test').after($('#switch-lines'));
		}
		//alert("v0.0.6");
		this._infoDomain = "/";
		this._infoFileName = "player.plan.info";
		this._playId=  (new Date()).getTime().toString() + Math.round(Math.random()*100000).toString();
		this._playLogNum= 0;
		this._playLogIntervalTime= 60;
		this._playLogInterval;
		this._url = "";
		//this._org = "";
		//this._low = "";
		this._playerInfo = null;
		this._hlsInfo = null;
		this._streamType = "";
		this._liveDuration= 0;
		this._livePublicType = 0;
		this._videoPublicType = 0;
		this._trialTime = 0;
		this.$player = $("#" + id);
		var v = document.createElement("video");
		v.setAttribute("webkit-playsinline",true);
		v.setAttribute("poster","/live.live.thumb/"+planId);
		v.style.width="100%";
		v.style.height="100%";
		//v.addEventListener("click", function(){
		//this.play();
		//});
		v.controls="controls";
		v.preload="auto";
		v.autoplay=true;
		this.$player.replaceWith(v);
		//this.$player = $("#" + id);
		this.$player = $(v);//("#" + id);
		this._player =v;//this.$player.get(0);
		// console.log(this.$player);
		// console.log(this._player);
		this._errorFun = "";
		this.addEvents();
		this._planID = planID;
		this._infoDomain = infoDomain;
		this._errorFun = errorFun;
		this.loadInfo();
	}
	HTML5Player.prototype.loadInfo = function(){
		var url = this._infoDomain + "/" + this._infoFileName  + "/" + this._planID + "?callback=?";
		var _this = this;
		$.getJSON(url, function(data, statusTxt){
			if(statusTxt == "success"){
				_this._playerInfo = data;
				if(data.hasOwnProperty("error")){
					//	alert(data.error.msg);
					//	alert(data.error.link);
					if(data.error.code == -401){
						var dom = $("#login_plan_need");
						dom.find("[data-class=href]").attr("href", data.error.link);
						dom.show();
						_this.playerError(data);
					}else if(data.error.code == -402){
						var dom = $("#sign_plan_need");
						dom.find("[data-class=mind]").html(data.error.msg);
						dom.find("[data-class=href]").attr("href", data.error.link);
						dom.show();
						_this.playerError(data);
					}else{
						try{
							if(data.error.code!=-412){
								showPlayerMessage('出错了('+data.error.code+")",0,data.error.msg);
							}
						}catch(e){
							_this.playerError(data);
						}
					}
				} else{
					if(data.hasOwnProperty("cdn_hls")){
						var cdnhlsLength = data.cdn_hls.length;
						if(cdnhlsLength > 1){
							$('#switch-lines').show();
						}else{
							$('#switch-lines').html('画面卡，点击<span class="pl5 openListBtn"><a href="#" onclick="window.location.reload()">刷新页面</a></span>').show();
						}
						for (var i = 0; i < cdnhlsLength; i++) {
							if(data.cdn_hls[i]['default']==1){
								_this._route=data.cdn_hls[i].hls;
								$('#switch-lines').find('ul').append('<li class="active" data-hls="'+data.cdn_hls[i].hls+'">'+data.cdn_hls[i]['name']+'</li>');
							}else{
								$('#switch-lines').find('ul').append('<li data-hls="'+data.cdn_hls[i].hls+'">'+data.cdn_hls[i]['name']+'</li>');
							}
						}
					}
					console.log(_this._route);
					_this.changeRoute();
				}

				if(data['plan']['video_trial_time']){
					var s = parseInt(data['plan']['video_trial_time']/60);
					var trial='<div class="try-tips" id="try-tips">只能试看'+s+'分钟</div>';
                	if($("#try-tips").length==0){
				    	$("#playerContent").prepend(trial);
                	}
				}
			}
			else if(statusTxt == "error"){
				this.showStatus("请求服务器信息失败.");
			}

            //学生播放页面log
		});
	}
	HTML5Player.prototype.changeRoute = function(){
		var _this = this;
		var data = _this._playerInfo;
		if(data.hasOwnProperty(_this._route)){
			_this._hlsInfo = data[_this._route];
			// makeUrl
			var hlsURL = data[_this._route]["url"];
			if(hlsURL[hlsURL.length-1]=="/"){
				hlsURL=hlsURL.substr(0,hlsURL.length-1);
			}
			var stream = data[_this._route]["stream"];
			var key_n = data[_this._route]["key_name"];
			var key_v = data[_this._route]["key_value"];
			var args = "?"+key_n+"=" + key_v;
			_this._url = hlsURL + stream + args;
			var pointIndex = stream.indexOf(".");
			var streamName = stream.substring(0, pointIndex);
			_this._streamType = data["streamType"];
			_this._livePublicType = data["plan"]["live_public_type"];
			_this._videoPublicType = data["plan"]["video_public_type"];
			_this._trialTime = data["plan"]["video_trial_time"] || 0;
			// makeUrl
			if(!_this._playLogInterval){
				_this.playLog(data);
				_this._playLogInterval = setInterval(function(){_this.playLog(data)},_this._playLogIntervalTime*1000);
			}
			_this.$player.attr("src", _this._url);
			_this._player.load();
			_this._player.play();
		}
	};
	HTML5Player.prototype.playLog= function(data, isEnd){
		var mydate=new Date();
		var d={
			"clid":"",
			"op":"",
			"tp":"",
			"oid":"",
			"vst":0,
			"an":"",
			"uid":"",
			"pid":"",
			"ua":"",
			"lgt":0,
			"lct":0,
			"ct":0,
			"tt":0,
			"ip":"",
			"vid":0,
			"cid":"",
			"id":"14914834959570252193",
			"vv":1,
			"bf":[

			],
			"sid":0,
			"cdnid":"",
			"pm":"hls"
		}
		d.oid = data["plan"]["organization_user_id"];
		d.cid = data["plan"]["course_id"];
		d.clid = data["plan"]["class_id"];
		d.vst = 0;
		if(data[this._route] && data[this._route]["video_id"]){
			d.vid = parseInt(data[this._route]["video_id"]);
		}

		if(this._playLogNum >0 ){
			d.lgt = this._playLogNum * this._playLogIntervalTime * 1000
			d.lct = (this._playLogNum + 1 ) * this._playLogIntervalTime * 1000
			d.vv=0;//后续发送不算vv
		}
		if(isEnd){//直播结束
			d.ct = d.tt = this.player().duration()
		}else{
			d.ct = this._liveDuration + this.player().currentTime;
			if(_this._streamType == "live"){
				d.tt = 0;//直播不需要总时间
			}else{
				d.tt = this.player().duration
			}
		}
		this._playLogNum ++ 
		d.pid = data['plan']['plan_id'];
		d.tp = 2;
		d.ua = navigator.userAgent;

		this._logUrl = data["playLog"];
		d.uid = userId;
		//d.id = userId.toString() + mydate.getMilliseconds().toString() + Math.round(Math.random()*10000).toString();
		d.id = userId.toString() + this._playId
		d.pm = data["playmode"];
		//d.sid = data["plan"]["section_id"];
		d.clid = data['plan']['class_id'];
		d.cid = data['plan']['course_id'];
		d.cdnid = data[this._route]["cdn_id"];
		d.op = data["user"]["ip"]["op_name"];
		d.ip = data["user"]["ip"]["ip"];
		d.an = data["user"]["ip"]["area_name"];
		$.ajax({
			url:this._logUrl,
			type:"POST",
			data:JSON.stringify(d),
			success: function(data){ },
			error: function(XmlHttpRequest,textStatus,errorThrown) { },
			statusCode:{
				//400: function() {alert('400!');},
				//404: function() {alert('404!');},
				//401: function() {alert('404!');},
				//403: function() {alert('404!');},
				//200: function() {/*alert('200!');*/},
				//503: function() {alert('503!');}
			},
			dataType: "json"
		});
	};
	HTML5Player.prototype.playerError = function(data){
		if(this._errorFun != null && this._errorFun != ""){
			this._errorFun(data["error"]);
		}
	}
	/**
	*自动切换播放流
	*/
	HTML5Player.prototype.playAuto = function(){
		this._player.src = this._url;
		this.play();
	}
	/**
	*清除播放流
	*/
	HTML5Player.prototype.clearStream = function(){
		this._player.src = null;
		this.play();
	}
	/**
	*播放原画流
	*/
	//HTML5Player.prototype.playOrg = function(){
	//	this._player.src = this._org;
	//	this.play();
	//}
	/**
	*播放标清流
	*/
	//HTML5Player.prototype.playLow = function(){
	//	this._player.src = this._low;
	//	this.play();
	//}
	/**
	*获取播放器对象
	*/
	HTML5Player.prototype.player = function(){
		return this._player;
	}
	HTML5Player.prototype.play = function(){
		this.player().play();
	}
	HTML5Player.prototype.paused = function(){
		this.player().pause();
	}
	HTML5Player.prototype.muted = function(){
		this.player().muted = !this.player().muted;
	}
	HTML5Player.prototype.stop = function(){
		this.player().stop();
	}
	/**
	*输出信息
	*/
	HTML5Player.prototype.info = function(){
		return { currentTime : this._liveDuration + this.player().currentTime}
		//alert("v1.0: " + this._url + "\n" + this._org + "\n" + this._low);
		//this.showStatus("v1.0: <br/>" + this._url + "<br/>" + this._org + "<br/>" + this._low + "<br/>");
		//alert("v1.0.1: " + this._player.src);
	}
	HTML5Player.prototype.seek = function(s){
		this.player().currentTime = s;
	}
	HTML5Player.prototype.duration= function(){
		return this.player().duration;
	}
	HTML5Player.prototype.stop = function(){
		this.player().pause();
		this.player().currentTime = 0;
	}
	HTML5Player.prototype.addVolume = function(){
		if(this._player.volume + 0.1 > 1){
			this._player.volume = 1;
		}
		else{
			this.player().volume += 0.1;
		}
	}
	HTML5Player.prototype.subVolume = function(){
		if(this._player.volume - 0.1 < 0){
			this._player.volume = 0;
		}
		else{
			this.player().volume -= 0.1;
		}
	}
	/**
	*监听播放器的事件
	*/
	var H5PlayWaittingStatus = false;
	HTML5Player.prototype.addEvents = function(){
		var _this = this;
		/*this.player().addEventListener("click", function(){
			if(Player._player.paused){
				Player.play();
			}
			else{
				Player.paused();
			}
		});*/

		this.player().addEventListener("play",function(){
			_this.showStatus("playing");
			if(_this._player.currentTime == 0){
				$('#H5PlayWaitting').show();
				H5PlayWaittingStatus = true;
				//$('#switch-lines').hide();
			}
			// 试看结束
			if(_this._trialTime != 0){
				var _currentTime;
				_TimerId = setInterval(function(){
					_currentTime = _this._player.currentTime || 0;
					if(_currentTime >= _this._trialTime){
						clearInterval(_TimerId);
						_this.currentTime=0;
						_this.stop();
						alert("试看试时间结束.");
						$('#playerContent').hide();
						$('#login_remind').show();
					}
				},1000);
			}
		});

		this.player().addEventListener("pause",function(){
			if(_this._player.currentTime == 0){
				_this.showStatus("stop");
				//$('#switch-lines').hide();
			} else {
				_this.showStatus("paused");
				//$('#switch-lines').show();
			}
		});
		this.player().addEventListener("timeupdate",function(){
			if(H5PlayWaittingStatus && _this._player.currentTime>0){
				$('#H5PlayWaitting').hide();
				if(isLiving){
					$("#sectionc a[plan_id="+window.planId+"]").addClass('living').removeClass('playing');
				}else{
					$("#sectionc a[plan_id="+window.planId+"]").addClass('playing').removeClass('living');
				}
				H5PlayWaittingStatus = false;
			}
			var duration = _this._player.duration;
			var currentTime = _this._player.currentTime;
			var time = _this.formatTime(duration) + "/" + _this.formatTime(currentTime);
			$("#time").text(time);
		});

		this.player().addEventListener("ended", function(){
			$("#courseing").hide();
			$("#courseend").show();
			$("#sectionc a[plan_id="+window.planId+"]").removeClass('playing');

			var next = $("#sectionc a[plan_id="+window.planId+"]").next();
			var next_v = $("#sectionc a[plan_id="+window.planId+"]").next().find(".sicon-2");
			var prev = $("#sectionc a[plan_id="+window.planId+"]").prev();
			if(next_v.size()){
			    var id = $(next).attr("plan_id");
			    $("#nextPlan").attr("href","/course.plan.play/"+id);
			}else if(prev.size()){
			    var id = $(prev).attr("plan_id");
			    $("#nextPlan").hide();
			}else{
			    $("#nextPlan").hide();
			}
	        _this.showStatus("ended");
			_this.playLog(_this._playerInfo,true)
			if(_this._playLogInterval){
				clearInterval(_this._playLogInterval);
			}
		});

		this.player().addEventListener("waiting", function(){
			_this.showStatus("wating");
		});

		this.player().addEventListener("seeking", function(){
			_this.showStatus("seeking");
		});
		this.player().addEventListener("seeked", function(){
			_this.showStatus("seeked");
		});
		this.player().addEventListener("volumechange", function(){
			var volume = _this._player.volume.toFixed(2);
			_this.showStatus(volume);
		});

		this.player().addEventListener("error", function(){
			switch (_this._player.error.code) {
				case _this._player.error.MEDIA_ERR_DECODE:
				case _this._player.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
				case _this._player.error.MEDIA_ERR_NETWORK:
				case _this._player.error.MEDIA_ERR_ABORTED:
					break;
				default:
					break;
			}
			_this.showStatus("error.code: " + _this._player.error.code);
		});
        //加载检测
        this.player().addEventListener("progress", function(){
            //alert("开始缓冲");
        });
		//加载线路
        this.player().addEventListener("touchstart", function(){
            //$('#switch-lines').show();
			//setTimeout(function(){$('#switch-lines').show();},1000);
        });
	}
	/**
	*显示播放器状态
	*/
	HTML5Player.prototype.showStatus = function(status){
		var $statusTxt = $("#status");
		$statusTxt.append(status + ", ");
	}
	/**
	*格式化时间格式
	*/
	HTML5Player.prototype.formatTime = function(time){
		var m = Math.floor(time / 60);
		var s = Math.floor(time % 60);
		return m + ":" + s;
	}
	HTML5Player.prototype.isFreeTime = function(time){
		if(_this._streamType == "recorded"){
			if(_this._videoPublicType == 0){
				return false;
			}else if(_this._videoPublicType == 1){
				return true;
			}else if(_this._videoPublicType == 2){
				if(time < _this._trialTime){
					return true;
				}
			}
			return false;
		}else if(_this._streamType == "live"){
			return _this._livePublicType == 1;
		}
		return false;
	}

	window["H5Player"] = new HTML5Player();
})("player");
