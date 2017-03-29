// 聊天模块
define(['jquery','global','message','util'],function($,global,message){
	var g = global.get(['isLogin','isReg']);
	var isLogin = g.isLogin;
	var isReg = g.isReg;
	return {
		getNewMeg: function(){},
		setGroup: function(){},
		noticeAdd: function(){},
		noticeRemove: function(){},
		setScroll: function(){},
		submitValid: function(){},
		initEmoji: function(){},
		setGag: function(){},
		cancelGag: function(){},
		handUp: function(){},
		cancelHandUp: function(){},
		renderTalk: function(){},
		renderSysInfo: function(){},
		sendTalk: function(){},
		bind: function(){

		},
		init: function(){
			this.setGroup();
			this.bind();
		}
	}
});