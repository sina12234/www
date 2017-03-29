var _lastDate = new Date();
var _chat_list;
var _chat_list_cache = [];
var _scrollFlag = true;
var _news_num = 0;
var _news;
var _good_user_cache = [];
var _goodNum = 0;
var _info = null;
var _classStart = false;
var _isPatternReply = false;
var _div = $("<div></div>");
var _lastAddTime = false;
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
	"0o":"75"
}
var _url2flag = {};
var _flag2urlReg = /\/:(..)/g;
var _url2flagReg = /<img src="\/assets\/images\/expression\/([0-9a-z]+)\.gif".*?>/g;
function flag2img(s, p1){
	if(p1 in _flag2url){
		return '<img src="/assets/images/expression/'+_flag2url[p1]+'.gif" width="24px" height="24px" />';
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
function setInfo(info){
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
function textResponse(data){
	var thumb = "/asserts/images/grtx.jpg";
	var name = "不知道";
	var u = data.user_from_id;
	if(u in _info && "thumb" in _info[u]){
		thumb = _info[u]["thumb"];
		name = _info[u]["name"];
	}
	var dom;
	var a = data.content.replace(_flag2urlReg, flag2img);
	if(data.is_teacher){
		dom = '<div title="'+data.last_updated+'" class="dhk1" user_id="' + u + '"><span><img src="' + teacherThumb + '" width="24" height="24"></span><div class="jjj"></div><div class="mess"><p>老师说:<b c>' + a + '</b></p></div></div>';
	}else{
		dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + u + '"><span><img src="' + thumb + '" width="24" height="24"></span><div class="jjj"></div><div class="mess"><p>' + name + '说:<b c>' + a + '</b></p></div></div>';
	}
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push(dom);
	if(u == userId){
		_scrollFlag = true;
		_news.hide();
		_news_num = 0;
	}else{
		_news_num++;
	}
}

function sayGood(teacherName, studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>'+teacherName+'老师 给 <b style="font-weight:bold;">'+studentName+'</b> 大赞一个 <img src="/assets/images/emjoydz.jpg" ></p></div><div class="clear"></div>');
	_news_num++;
}
function sayAsking(teacherName, studentName, command){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>'+teacherName+'老师 请 <b style="font-weight:bold;">'+studentName+'</b> 发言</p></div><div class="clear"></div>');
	_news_num++;
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
	_news_num++;
}
function sayAsk(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>学生 <b style="font-weight:bold;">'+studentName+'</b> 申请发言</p></div><div class="clear"></div>');
	_news_num++;
}
function sayCall(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>老师对 <b style="font-weight:bold;">'+studentName+'</b> 进行点名</p></div><div class="clear"></div>');
	_news_num++;
}
function sayMicrophone(studentName){
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push('<div class="teacherdz" style="line-height:30px;color:#3399cc;float:right;padding:10px 10px 0px 10px;"><p>老师对 <b style="font-weight:bold;">'+studentName+'</b> 进行测麦</p></div><div class="clear"></div>');
	_news_num++;
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
	dom = '<div title="'+data.last_updated+'" class="dhk" user_id="' + u + '"><span><img src="' + thumb + '" width="24" height="24"></span><div class="jjj"></div><div class="mess"><p>' + name + '说:<b reply_star class="reply_star" title="答题模式不可见">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b reply_text style="display:none;" c>'+data.content.replace(_flag2urlReg, flag2img)+'</b></p></div></div>';
	var t = new Date();
	if(t - _lastDate > 60000){
		_chat_list_cache.push('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
	}
	_lastDate = t;
	_chat_list_cache.push(dom);
	if(u == userId){
		_scrollFlag = true;
		_news.hide();
		_news_num = 0;
	}else{
		_news_num++;
	}
}

function inputScroll(){
	if(_chat_list_cache.length){
		var a = _chat_list_cache.slice(0, 30);
		_chat_list_cache = _chat_list_cache.slice(30);
		var dom = $(a.join('')).appendTo(_chat_list);
		if(!_scrollFlag){
			var spanValue = ""+_news_num;
			if(_news_num > 99){
				spanValue = "99+"
			}
			_news.find("span").text(spanValue);
			_news.show();
		}
		if(!_isPatternReply){
			dom.find("[reply_star]").hide();
			dom.find("[reply_text]").show();
		}
		if(0 == _chat_list_cache.length && _scrollFlag){
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
		if(uid in _info){
			var o = _info[uid];
			if(o.shortName){
				if(isFull){
					$(this).text(o.name);
				}else{
					$(this).text(o.shortName);
				}
			}
		}
	});
}
$(document).on("fullscreenchange", fullscreenchangeGoodResponse);
$(document).on("mozfullscreenchange", fullscreenchangeGoodResponse);
$(document).on("webkitfullscreenchange", fullscreenchangeGoodResponse);
$(document).on("MSfullscreenchange", fullscreenchangeGoodResponse);
function goodResponse(data, quick){
	if(quick){
		goodResponseLater2(data);
	}else{
		_good_user_cache.push(data);
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
function goodResponseLater2(data){
	var u = parseInt(data.user_id);
	if(u in _info && !_info[u]["free"]){
		var o = _info[u];
		var num = parseInt(data.num);
		o.num += num;
		//set student
		if(num == o.num){
			var good = $('<div><div class="Ranking-p" uid="'+u+'"><p good2 class="dy">'+(_goodNum+1)+'</p><p><img good width="46" height="46" src="'+o.thumb+'"></p><p class="dy1 limithis good-max-width" good>'+o.name+'</p><p class="dz"><b good>1</b>次</p></div><div class="clear"></div></div>');
			/*if(_goodNum){
				$("#good_list").append('<div class="clear"></div>');
			}*/
			$("#good_list").append(good);
			o.good = good;
			_goodNum++;
		}else{
			good = o.good;
			good.find("b[good]").text(o.num);
			sortGood(good);
		}
	}
}
function setOnlineNum(num){
	$("#student_total").find("b[online]").text(num);
}
function setOnlineNum2(num){
	if(1 != fee_type){
		$("#tourist_total").find("b[online]").text(num);
	}
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
	v2 = v.replace(_url2flagReg, img2flag);
	v2 = v2.replace(/<.*?>/g, "");
	v2 = v2.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
	v2 = v2.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");

	if(!v2){
		return;
	}
	if(v2.length > 200){
		//alert("输入内容过长，请删除部分内容");
		_chat_list.append('<div class="teacherdz" style="line-height:30px;color:#f00;float:right;padding:10px 10px 0px 10px;"><p>输入内容过长，请删除部分内容!</p></div><div class="clear"></div>');
   		_chat_list.scrollTop(_chat_list[0].scrollHeight);
		_scrollFlag = true;
		_news_num = 0;
		return;
	}
	if(_lastAddTime){
		var now = Date.now();
		if(0 == _lastAddTime.length){
			_lastAddTime = [now];
		}else if(3 == _lastAddTime.length){
			if(now - _lastAddTime[2] < 30000){
				_chat_list.append('<div class="teacherdz" style="line-height:30px;color:#f00;float:right;padding:10px 10px 0px 10px;"><p>操作过于频繁，请稍后再试!</p></div><div class="clear"></div>');
				_chat_list.scrollTop(_chat_list[0].scrollHeight);
				_scrollFlag = true;
				_news_num = 0;
				return;
			}else{
				_lastAddTime = [now];
			}
		}else{
			if(now - _lastAddTime[_lastAddTime.length-1] < 2000){
				_lastAddTime.push(now);
			}else{
				_lastAddTime = [now];
			}
		}
	}
	//var info = flash.info();
	var callback = function(to_id, to_token, content){
		var dom = domMeFunc([v, 0], userThumb);
		var div = _chat_list;
		var t = new Date();
		if(t - _lastDate > 60000){
			div.append('<div class="xinxi"><p class="time">'+t.getHours()+':'+t.getMinutes()+'</p></div>');
		}
		_lastDate = t;
		div.append(dom);
		div.scrollTop(div[0].scrollHeight);
		_scrollFlag = true;
		_news_num = 0;
	};
	message.text(v2, callback);
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
	_news = $("#news");
	_news.click(function(e){
		_chat_list.scrollTop(_chat_list[0].scrollHeight);
	});
	_chat_list.scroll(function(e){
		if(_chat_list.scrollTop() + 120 < _chat_list[0].scrollHeight-_chat_list.width()){
			_scrollFlag = false;
		}else{
			_scrollFlag = true;
			_news_num = 0;
			_news.hide();
		}
	});
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
				var length = prev.length;
				var length2 = post.length;
				for(i=0;i<length;i++){
					if(prev[i] != post[i]){
						break;
					}
				}
				for(j=0;i+j<length;j++){
					if(prev[length-j-1] != post[length2-j-1]){
						break;
					}
				}
				var text = getText(post.substring(i, length2-j));
				elem.html(post.substring(0, i) + text + post.substring(length2-j));
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
			pasteFunc($(this));
			return;
		}
	});
	$("img[show]").click(function(e){
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
	var dom = $('<div title="'+t2+'" class="dhk1"><span><img src="' + thumb + '" width="24" height="24"></span><div class="jjj"></div><div class="mess">	<p>我说:<b me></b></p></div></div>');
	dom.find("b[me]").append(data[0]);
	return dom;
}
function domErrFunc(data){
	return $('<div class="dhk"><span><img src="/assets/images/grtx.jpg"></span><div class="jjj"></div><div class="mess"><p>' + 'error' + '说:<b>' + data[0] + '</b></p></div></div>');
}
/*function refresh_student(success_callback){
	var url = "/course.plan.getstudentsforclass";
	$.post(url, {"class_id":class_id}, success_callback, "json");
}*/
function get_new_student(success_callback, u){
	var url = "/user/user/getbasicuser";
	$.post(url, {"user_id":u}, success_callback, "json");
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
