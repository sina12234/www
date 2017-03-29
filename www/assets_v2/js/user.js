if(!window.userApi){
	if(!window.COOKIE_UID_NAME) window.COOKIE_UID_NAME="uid";
	var userApi={
		isLogin:function(){
			var uid = this.getCookie(COOKIE_UID_NAME);
			if (parseInt(uid) > 0) {
				return true;
			} else {
				return false;
			}
		},
        getUid:function(){
            return parseInt(this.getCookie(COOKIE_UID_NAME)) || 0;
        },
		isWeiXin:function(){
			var ua = window.navigator.userAgent.toLowerCase();
			if(ua.match(/MicroMessenger/i) == 'micromessenger'){
				return true;
			}else{
				return false;
			}
		},
		clearCookie:function (name) {
			userApi.setCookie(name, "", -1);
		},
		setCookie:function(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + "; " + expires;
		},
		getCookie:function(c_name){
			if (document.cookie.length>0)
			{
				c_start=document.cookie.indexOf(c_name + "=")
				if (c_start!=-1)
				{
					c_start=c_start + c_name.length+1
					c_end=document.cookie.indexOf(";",c_start)
					if (c_end==-1) c_end=document.cookie.length
					return unescape(document.cookie.substring(c_start,c_end))
				}
			}
			return ""
		},
		setCookieV2:function(cname, cvalue, seconds) {
			var d = new Date();
			d.setTime(d.getTime() + (seconds*1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + "; " + expires;
		}
	}
}
