var web2app = {
	nowType: '',
	timeout: 600,
	t: '',
	typeConfig: {
		plat: 'web2appPlat',
		video: 'web2appVideo',
		answer: 'web2appAnswer',
		exam: 'web2appExam'
	},
	textConfig: {
		plat:   '精彩好课不错过，打开云课APP',
		video:  '打开云课APP，直播互动精彩不可错过',
		answer: '网页不支持互动发言，打开云课APP',
		exam:   '网页不支持答题卡，打开云课APP'
	},
	posConfig: {
		plat: {bottom: '0px'},
		video: {bottom: '0px'},
		answer: {bottom: '40px'},
		exam: {bottom: '40px'}
	},
	urlConfig: {
		open: 'com.yunke://',
		downAdr: ('https:' == document.location.protocol ? ' https://' : ' http://') +'a.app.qq.com/o/simple.jsp?pkgname=com.yunke.android',
		downIOS: ('https:' == document.location.protocol ? ' https://' : ' http://') +'itunes.apple.com/us/app/yun-ke/id1071519213?l=zh&ls=1&mt=8i'
		//down: ('https:' == document.location.protocol ? ' https://' : ' http://') +'a.app.qq.com/o/simple.jsp?pkgname=com.yunke.android'
	},
	body: document.getElementsByTagName('body')[0],
	dom: '<section data-type="" id="web2app" style="display:none;position: fixed;bottom:0;height:44px;line-height:44px;font-size:14px;color:#fff;z-index:999999;background-color:rgba(0,0,0,0.6);width:100%;"><img src="/assets_v2/img/yunke_share.png" width="30" style="margin-left:10px;margin-right:5px;border-radius:5px;"><span id="web2appText">精彩好课不错过，安装云课APP</span><svg id="web2appClose" style="height:100%;float:right;margin-right:5px" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1864" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"><defs><style type="text/css"><![CDATA[]]></style></defs><path d="M835.3792 236.4416 774.7584 172.8512 512 448.4096 512 448.4096 512 448.4096 512 448.4096 249.2416 172.8512 188.6208 236.4416 451.3792 512 188.6208 787.5584 249.2416 851.1488 512 575.5904 512 575.5904 512 575.5904 512 575.5904 774.7584 851.1488 835.3792 787.5584 572.6208 512Z" p-id="1865" fill="#fff"></path></svg></section>',
	addDom: function(){
		var a = document.createElement('span');
        a.innerHTML = this.dom;
        this.body.appendChild(a.children[0]);
	},
	changeText: function(type){
		this.nowType = type;
		this.el_text.innerHTML = this.textConfig[type];
	},
	add: function(){
		!document.getElementById('web2app')&&this.addDom();
		var self = this;
		this.el = document.getElementById('web2app');
		this.el_text = document.getElementById('web2appText');
		this.el.onclick = function(event){
			var e = event || window.event;
			if(e.target.id == 'web2appClose' || e.target.parentNode.id == 'web2appClose'){
				self.close.call(self);
				return false;
			}else{
				self.openClient.call(self);
			}
		};
	},
	openClient: function(){
		var st = Date.now();
		var ifr = document.createElement('iframe');
		var self = this;
		var href = location.href;
		if(/course(\.|\/)plan(\.|\/)play/ig.test(href)){
	     	ifr.src = this.urlConfig.open + '?courseID='+courseId+'&planID='+planId;
		}else if(/course(\.|\/)info(\.|\/)show/ig.test(href)){
		    ifr.src = this.urlConfig.open + '?courseID='+cid;
		}else{
		 	ifr.src = this.urlConfig.open;
		}
		//ifr.src = this.urlConfig.open;
		ifr.style.display = 'none';
		if(/iPhone|iPad|iPod/i.test(navigator.userAgent)){
			window.location.href = ifr.src;
			window.setTimeout(function(){
				window.location.href = self.urlConfig.downIOS;
			}, 1500);
		}else{
			document.body.appendChild(ifr);
			this.t = setTimeout(function(){
				var et = Date.now();
				if(!st || et - st < self.timeout + 200){
					window.location.href = /iPhone|iPad|iPod/i.test(navigator.userAgent) ? self.urlConfig.downIOS : self.urlConfig.downAdr;
					//window.location.href = self.urlConfig.down;
				}
			},this.timeout);
			window.onblur = function(){
				clearTimeout(self.t);
			}
		}
	},
	show: function(type){
		if(!$.cookie(this.typeConfig[type])){
			this.changeText(type);
			this.el.style.background = 'rgba(0,0,0,0.6)';
			if(type == 'video'){
				document.getElementById('courseing').appendChild(this.el);
				this.el.style.background = '#303030';
			}else if(type == 'answer' || type == 'exam'){
				document.getElementById('tool').appendChild(this.el);
			}
			//this.el.style.bottom = this.posConfig[type].bottom;
			this.el.style.display = 'block';
			if(type == 'answer' || type == 'exam' || type == 'video'){
				this.el.style.position = 'absolute';
				//clearTimeout(this.t);
				//var self = this;
				//this.t = setTimeout(function(){
				//	self.el.style.display = 'none';
				//},10000);
			}
		}
	},
	hide: function(){
		this.el.style.display = 'none';
	},
	close: function(){
		var s = (this.nowType == 'answer' || this.nowType == 'exam') ? 1 : 365;
		$.cookie(this.typeConfig[this.nowType], '1',{ expires: s,path: '/' });
		this.hide();
	},
	init: function(){
		this.add();
		var href = location.href;
		if(/course(\.|\/)plan(\.|\/)play/ig.test(href)){
			this.posConfig.video.bottom = document.getElementById('tool').style.height;
			this.show('video');
		}else{
			this.show('plat');
		}
	}
};
window.addEventListener('load',function(){
	window.web2app = web2app;
	if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)&&!document.getElementById('openApp')){
		web2app.init();
	}
});
