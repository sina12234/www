var _lastDate = new Date();
var _chat_list;
var _chat_list_cache = [];
var _good_user_cache = [];
var _goodNum = 0;
var _info = null;
var _classStart = false;
var _isPatternReply = false;
var _div = $("<div></div>");
/*function htmlEscape(html){
	_div.html(html);
	return _div.text();
}*/
/*var _flag2url = {
	"00":"/assets/images/expression/14.gif",
	"01":"/assets/images/expression/57.gif",
	"02":"/assets/images/expression/50.gif",
	"03":"/assets/images/expression/29.gif",
	"04":"/assets/images/expression/30.gif",
	"05":"/assets/images/expression/32.gif",
	"06":"/assets/images/expression/24.gif",
	"07":"/assets/images/expression/21.gif",
	"08":"/assets/images/expression/26.gif",
	"09":"/assets/images/expression/11.gif",
	"0a":"/assets/images/expression/25.gif",
	"0b":"/assets/images/expression/7.gif",
	"0c":"/assets/images/expression/15.gif",
	"0d":"/assets/images/expression/90.gif">,
	"0e":"/assets/images/expression/36.gif",
	"0f":"/assets/images/expression/114.gif",
}*/
var _flag2url = {
	"00":"14",
	"01":"57",
	"02":"50",
	"03":"29",
	"04":"30",
	"05":"32",
	"06":"24",
	"07":"21",
	"08":"26",
	"09":"11",
	"0a":"25",
	"0b":"7",
	"0c":"15",
	"0d":"90",
	"0e":"36",
	"0f":"114",
	"0g":"17",
	"0h":"65",
	"0i":"76",
	"0j":"74",
	"0k":"13",
	"0l":"19",
	"0m":"20",
	"0n":"72",
	"0o":"75",
}
var _url2flag = {};
var _flag2urlReg = /\/:(..)/g;
var _url2flagReg = /<img src="\/assets\/images\/expression\/([0-9a-z]+)\.gif">/g;
function flag2img(s, p1){
	if(p1 in _flag2url){
		return '<img src="/assets/images/expression/'+_flag2url[p1]+'.gif" />';
	}else{
		return s;
	}
}
function img2flag(s, p1){
	if(p1 in _url2flag){
		return "/:" + _url2flag[p1];
	}else{
		return s;
	}
}
function urlPath(url){
	var i = url.indexOf("//");
	if(i < 0){
		return url;
	}
	i = url.indexOf("/", i+2);
	if(i < 0){
		return url;
	}
	return url.substr(i);
}
function setInfo(info)
{
	_info = info;
}
function placeCaretAtEnd(el) {
	el.focus();
	if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
		var range = document.createRange();
		range.selectNodeContents(el);
		range.collapse(false);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
	} else if (typeof document.body.createTextRange != "undefined") {
		var textRange = document.body.createTextRange();
		textRange.moveToElementText(el);
		textRange.collapse(false);
		textRange.select();
	}
}
function textResponse(data){ pyy = data;
	var thumb = "/asserts/images/grtx.jpg";
	var name = "不知道";
	var u = data.user_from_id;
	if(u in _info && "thumb" in _info[u]){
		thumb = _info[u]["thumb"];
		name = _info[u]["name"];
	}
	var dom;
	//var a = htmlEscape(data.content);
	var a = data.content.replace(_flag2urlReg, flag2img);
	if(data.is_teacher){
	/*	dom = '<div title="'+data.last_updated+'" class="dhk1" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>老师说:<b c>' + a + '</b></p></div></div>';
	*/	
		dom = '<div class="public-speak"><p>老师说('+data.last_updated+')</p><b>'+a+'</b></div>'
	}else{
	/*	dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>' + data.user_from_name + '说:<b c>' + a + '</b></p></div></div>';
	*/
		dom = '<div class="public-speak"><p>'+name+'说('+data.last_updated+')</p><b>'+a+'</b></div>'
	}
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push(dom);
}

function sayGood(teacherName, studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>'+teacherName+'老师 给 <b style="font-weight:bold;">'+studentName+'</b> 大赞一个 <img src="/assets/images/emjoydz.jpg" ></p></div><div class="clear"></div>');
}
function sayAsking(teacherName, studentName, command){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>'+teacherName+'老师 请 <b style="font-weight:bold;">'+studentName+'</b> 发言</p></div><div class="clear"></div>');
}
function sayLine(studentName, online){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	if(online){
		_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p><b style="font-weight:bold;">'+studentName+'</b> 进入教室 </p></div><div class="clear"></div>');
	}else{
		_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p><b style="font-weight:bold;">'+studentName+'</b> 退出教室 </p></div><div class="clear"></div>');
	}
}
function sayAsk(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>学生 <b style="font-weight:bold;">'+studentName+'</b> 申请发言</p></div><div class="clear"></div>');
}
function sayCall(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>老师对 <b style="font-weight:bold;">'+studentName+'</b> 进行点名</p></div><div class="clear"></div>');
}
function sayMicrophone(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>老师对 <b style="font-weight:bold;">'+studentName+'</b> 进行测麦</p></div><div class="clear"></div>');
}

function replyTextResponse(data){
	var thumb = "/asserts/images/grtx.jpg";
	var name = "不知道";
	var u = data.user_from_id;
	if(u in _info && "thumb" in _info[u]){
		thumb = _info[u]["thumb"];
		name = _info[u]["name"];
	}
	var dom;
	//dom = $('<div title="'+data.last_updated+'" class="dhk" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>' + data.user_from_name + '说:<b reply_star>'+a.join("")+'</b><b reply_text style="display:none;" c>'+''+'</b></p></div></div>');
	//dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>' + data.user_from_name + '说:<b reply_star>******</b><b reply_text style="display:none;" c>'+htmlEscape(data.content)+'</b></p></div></div>';
	//dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>' + data.user_from_name + '说:<b reply_star class="reply_star" title="答题模式不可见">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b reply_text style="display:none;" c>'+htmlEscape(data.content)+'</b></p></div></div>';
	dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + data.user_from_id + '"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess"><p>' + name + '说:<b reply_star class="reply_star" title="答题模式不可见">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b reply_text style="display:none;" c>'+data.content.replace(_flag2urlReg, flag2img)+'</b></p></div></div>';
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push(dom);
}

function inputScroll(){
	if(_chat_list_cache.length){
		var a = _chat_list_cache.slice(0, 30);
		_chat_list_cache = _chat_list_cache.slice(30);
		var dom = $(a.join('')).appendTo(_chat_list);
		if(!_isPatternReply){
			dom.find("[reply_star]").hide();
			dom.find("[reply_text]").show();
		}
		if(0 == _chat_list_cache.length){
   			_chat_list.scrollTop(_chat_list[0].scrollHeight);
		}
	}
	if(_good_user_cache.length){
		for(var i=0;i<8;i++){
			var u = _good_user_cache.shift();
			goodResponseLater2(u);
			if(0 == _good_user_cache.length){
				break;
			}
		}
	}
	if(_chat_list_cache.length + _good_user_cache.length > 0){
		setTimeout(inputScroll, 10);
	}
}
function isFullScreen(){
	if(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement){
		return true;
	}else{
		return false;
	}
}
function setGoodObj(uid, info){
	var o = info[uid]
	var good = o.good;
	good.attr("uid", uid);
	good.find("img[good]").attr("src", o.thumb);
	if(o.shortName){
		if(isFullScreen()){
			good.find("p[good]").text(o.name);
		}else{
			good.find("p[good]").text(o.shortName);
		}
	}else{
		good.find("p[good]").text(o.name);
	}
	good.find("b[good]").text(o.num);
}
function fullscreenchangeGoodResponse(e){
	var a = $("#good_list").find("p[good]");
	var isFull = isFullScreen();
	$("#good_list").find("p[good]").each(function(){
		var uid = $(this).parent().attr("uid");
		var o = _info[uid];
		if(o.shortName){
			if(isFull){
				$(this).text(o.name);
			}else{
				$(this).text(o.shortName);
			}
		}
	});
}
$(document).on("fullscreenchange", fullscreenchangeGoodResponse);
$(document).on("mozfullscreenchange", fullscreenchangeGoodResponse);
$(document).on("webkitfullscreenchange", fullscreenchangeGoodResponse);
$(document).on("MSfullscreenchange", fullscreenchangeGoodResponse);
function swapList(uid, info){
	var good = info[uid].good;
		console.log("good是"+good)
	if(1 == good.find("p[good2]").text()){
		return 0;
	}
	var good2 = good.prev().prev();
	if(parseInt(good.find("b[good]").text()) <= parseInt(good2.find("b[good]").text())){
		return 0;
	}
	var uid2 = parseInt(good2.attr("uid"));
	info[uid].good = good2;
	info[uid2].good = good;
	setGoodObj(uid, info);
	setGoodObj(uid2, info);
	return uid;
}
function goodResponse(u, info, quick){
	if(!_info){
		_info = info;
	}
	if(quick){
		goodResponseLater2(u);
	}else{
		_good_user_cache.push(u);
	}
}
function goodResponseLater(u){
	//var u = parseInt(data.content);
	if(u in _info){ console.log("------good, u=["+u+"]\n");
		var o = _info[u];
		o.num++;
		//set student
		if(1 == o.num){
			var good;
	/*		if(0 == _goodNum){
				good = $('<div class="Ranking-p" uid="'+u+'"><p good2 class="dy">1</p><p><img good width="46" height="46" src="'+o.thumb+'"></p><p class="dy1" good>'+o.name+'</p><p class="dz"><b good>1</b>次</p></div>');
			}else if(1 == _goodNum){
				good = $('<div class="Ranking-p" uid="'+u+'"><p good2 class="dy" style="background:#3CBC48;">2</p><p><img good width="46" height="46" src="'+o.thumb+'"></p><p good class="dy1">'+o.name+'</p><p class="dz"><b good style="color:#60D5C1;">1</b>次</p></div>');
			}else{
				good = $('<div class="Ranking-p" uid="'+u+'"><p good2 class="dy" style="background:#ccc;">'+(_goodNum+1)+'</p><p><img good width="46" height="46" src="'+o.thumb+'"></p><p good class="dy1">'+o.name+'</p><p class="dz"><b good>1</b>次</p></div>');
			}
	*/	
			if(0 == _goodNum){
				good = $('<li><div class="dznav pd5 col-sm-6 col-xs-12" uid="'+u+'"><div class="col-sm-4 col-xs-4 di"><p good2>1</p></div><div class="col-sm-4 col-xs-4"><p>'+o.name+'</p></div><div class="col-sm-4 col-xs-4 se"><p>点赞<b good>1</b>次</p></div></li>');
			}else if(1 == _goodNum){
				good = $('<li><div class="dznav pd5 col-sm-6 col-xs-12" uid="'+u+'"><div class="col-sm-4 col-xs-4 sl"><p good2>2</p></div><div class="col-sm-4 col-xs-4"><p>'+o.name+'</p></div><div class="col-sm-4 col-xs-4 se"><p>点赞<b good>1</b>次</p></div></li>');
			}else{
				good = $('<li><div class="dznav pd5 col-sm-6 col-xs-12" uid="'+u+'"><div class="col-sm-4 col-xs-4 jk"><p good2>'+(_goodNum+1)+'</p></div><div class="col-sm-4 col-xs-4"><p>'+o.name+'</p></div><div class="col-sm-4 col-xs-4 se"><p>点赞<b good>1</b>次</p></div></li>');
			}
			
		
			if(_goodNum){
				$("#good_list").append('<div></div>');
			}
			$("#good_list").append(good);
			o.good = good;
			_goodNum++;
		}else{
			good = o.good;
			good.find("b[good]").text(o.num);
			while(u){
				u = swapList(u, _info);
			}
		}
	}
}
function setGoodNum(){
	$("#good_list").children().each(function(i, elem){
		$(this).find("p[good2]").text(i+1);
	});
}
function sortGood(good){
	var good2 = good.prev();
	if(!good2.length){
		return;
	}
	if(parseInt(good.find("b[good]").text()) <= parseInt(good2.find("b[good]").text())){
		return;
	}
	var p = good2.prev();
	while(p.length){
		if(parseInt(good.find("b[good]").text()) <= parseInt(p.find("b[good]").text())){
			break;
		}
		good2 = p;
		p = good2.prev();
	}
	good.insertBefore(good2);
	setGoodNum();
}
function goodResponseLater2(u){
	if(u in _info){ console.log("------good, u=["+u+"]\n");
		var o = _info[u];
		o.num++;
	//	console.log(o);
		//set student
		if(1 == o.num){
			var good = $('<div class="dznav pd5 col-sm-6 col-xs-12" uid="'+u+'"><div class="col-sm-4 col-xs-4 jk"><p good2>1</p></div><div class="col-sm-4 col-xs-4"><p class="limithis" title="'+o.name+'">'+o.name+'</p></div><div class="col-sm-4 col-xs-4 se"><p>点赞<b good>1</b>次</p></div>');
//			dz"><b good>1</b>次</p></div><div class="clear"></div></li>');
			/*if(_goodNum){
				$("#good_list").append('<div class="clear"></div>');
				}*/
				//console.log("第一次点赞");
				$("#good_list").append(good);
				o.good = good;
				_goodNum++;
		}else{
			good = o.good;
			//console.log("多次点赞");
			good.find("b[good]").text(o.num);
			sortGood(good);

		}
	}
}


function setInlineNum(num){
	$("#student_total").find("b[inline]").text(num);
	$("#c_inline_info").find("b[inline]").text(num);
}
function displayOne(element, attr, value){
	if(element.attr(attr) == value){
		element.show();
	}else{
		element.hide();
	}
}
function addMsg(e){
	if($("#chat_send").hasClass("gray")){
		return;
	}
	var textarea = $("#chat_input");
	var v = textarea.html();
	v = v.replace(/^(&nbsp;( )*)*/g, "");
	v = v.replace(/(&nbsp;( )*)*$/g, "");
	if(!v){
		return;
	}
	v2 = v.replace(_url2flagReg, img2flag);
	if(v2.length > 200){
		//alert("输入内容过长，请删除部分内容");
		_chat_list.append('<div class="teacherdz" style="line-height:30px;color:#f00;float:right;padding:10px 10px 0px 10px;"><p>输入内容过长，请删除部分内容!</p></div><div class="clear"></div>');
   		_chat_list.scrollTop(_chat_list[0].scrollHeight);
		return;
	}
	//var info = flash.info();
	var dom = domMeFunc([v, 0], userThumb);
	var div = _chat_list;
	var t = new Date();
	if(t - _lastDate > 60000){
		div.append('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	div.append(dom);
//	div.scrollTop(div.outerHeight() + div.scrollTop());
	_chat_list.scrollTop(_chat_list[0].scrollHeight);
	message.text(v2);
	textarea.html("");
}
$(document).ready(function(){
	for(var k in _flag2url){
		var v = _flag2url[k];
		_url2flag[v] = k;
	}
	/*if(!String.prototype.trim) {
		String.prototype.trim = function () {
			return this.replace(/^\s+|\s+$/g,'');
		};
	}*/
	_chat_list = $("#chat_list");
	$("#chat_send").click(addMsg);
	$("#chat_input").keypress(function(e){
		if((10 == e.keyCode || 13 == e.keyCode) && e.ctrlKey){
			addMsg(e);
		}
	});
	$("#chat_input").keyup(function(e){
		var a = $(this).html();
		var b = a.replace(_flag2urlReg, flag2img);
		if(a != b){
			$(this).html(b);
			placeCaretAtEnd(this);
			//document.execCommand("insertText", false, "wsg");
		}
	});
	$("#chat_input").on("drop", function(e){e.preventDefault();});
	//$("#chat_input").height(100);	//需要修改
	var prev = null;
	$("#chat_input").on("paste", function(e){
		console.log("paste ...\n");
		function pasteFunc(elem){
			function getText(html){
				while(true){
					var pos1 = html.indexOf("<");
					if(pos1 < 0){
						return html;
					}
					var pos2 = html.indexOf(">", pos1);
					if(pos2 < 0){
						return html;
					}
					html = html.substring(0, pos1) + html.substring(pos2+1);
				}
			}
			if(prev){
				e.preventDefault();
				return;
			}
			prev = elem.html();
			setTimeout(function(){
				var post = elem.html();
				var i, j;
				for(i=0;i<prev.length;i++){
					if(prev[i] != post[i]){
						break;
					}
				}
				for(j=0;i+j<prev.length;j++){
					if(prev[prev.length-j-1] != post[post.length-j-1]){
						break;
					}
				}
				var text = getText(post.substring(i, post.length-j));
				elem.html(post.substring(0, i) + text + post.substring(post.length-j));
				prev = null;
			}, 1);
		}
		var data = (e.originalEvent || e).clipboardData;
		if(data){
			for(var i=0;i<data.types.length;i++){
				if("text/plain" == data.types[i]){
					continue;
				}else{
					pasteFunc($(this));
					return;
				}
			}
		}else{
			console.log("ie ...");
			pasteFunc($(this));
			return;
		}
	});
	$("img[show]").click(function(e){
		console.log(e.target.src);
		//$("#chat_input").append("<img src='"+e.target.src+"' />");
		var a = document.execCommand("insertImage", false, urlPath(e.target.src));
		if(!a){
			$("#chat_input").append("<img src='"+urlPath(e.target.src)+"' />");
		}
		//$("#chat_input").find("img").css("margin-top", "-10px");
	});
	/*var showTimeId = null;
	$("#show_pop").on("click", function(e){
		if(showTimeId){
			clearTimeout(showTimeId);
		}else{
			$("#show").show();
		}
		showTimeId = setTimeout(function(){
			if(showTimeId){
				clearTimeout(showTimeId);
				showTimeId = null;
				$("#show").hide();
			}
		}, 2000);
	});*/
	$("#show_pop").on("click", function(e){
		if($(this).hasClass("gray")){
			return;
		}
		$("#show").show().focus();
	});
	$("#show").on("mouseleave", function(e){
		$("#show").hide();
	});
	$("#show").hide();
});
//把用户输入的内容转换成dom元素
function domMeFunc(data, thumb){
	var t = new Date();
	var m = t.getMonth();
	if(m < 10){
		m = "0" + m;
	}
	var d = t.getDate();
	if(d < 10){
		d = "0" + d;
	}
	var h = t.getHours();
	if(h < 10){
		h = "0" + h;
	}
	var mi = t.getMinutes();
	if(mi < 10){
		mi = "0" + mi;
	}
	var s = t.getSeconds();
	if(s < 10){
		s = "0" + s;
	}
	var t2 = (1900+t.getYear())+"-"+m+"-"+d+" "+h+":"+mi+":"+s;
	var dom = $('<div title="'+t2+'" class="dhk1"><span><img src="' + thumb + '"></span><div class="jjj"></div><div class="mess">	<p>我说:<b me></b></p></div></div>');
	var	dom = $('<div class="public-speak"><p>我说('+t2+')</p><b me></b></div>');
	//	dom =$('<p>我说:<b me></b>');
	dom.find("b[me]").append(data[0]);
	return dom;
}
function domErrFunc(data){
	return $('<div class="dhk"><span><img src="/assets/images/grtx.jpg"></span><div class="jjj"></div><div class="mess"><p>' + 'error' + '说:<b>' + data[0] + '</b></p></div></div>');
}
function refresh_student(success_callback){
	var url = "/course.plan.getstudentsforclass";
	$.post(url, {"class_id":class_id}, success_callback, "json");
}
function chatMay(flag){
	if(flag){
		$("#chat_input").attr("contenteditable", true);
		$("#chat_send").removeClass("gray");
		$("#show_pop").removeClass("gray");
	}else{
		$("#chat_input").attr("contenteditable", false);
		$("#chat_send").addClass("gray");
		$("#show_pop").addClass("gray");
	}
}
function chatNotalk(flag){
	if(flag){
		chatMay(false);
	}else{
		//if(_classStart){
			chatMay(true);
		//}
	}
}
function startClass(notalkFlag){
	_classStart = true;
	message.startClass();
	/*if(!notalkFlag){
		chatMay(true);
	}*/
}
function stopClass(){
	_classStart = false;
	message.stopClass();
	//chatMay(false);
	var ask = $("#ask");
	if(ask){
		ask.attr("ask", "ask");
		ask.find("[ask]").each(function(){
			if("ask" == $(this).attr("ask")){
				$(this).show();
				$(this).addClass("gray");
			}else{
				$(this).hide();
			}
		});
	}
}

