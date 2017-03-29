var _lastDate = new Date();
var _activeGroupId = 0;
var _chat_list;
var _chat_group_list;
var _displayChatList;
var _chat_list_cache = [];
var _scrollFlag = true;
var _good_user_cache = [];
var _goodNum = 0;
var _classStart = false;
var _isPatternReply = false;
var _div = $("<div></div>");
var _lastAddTime = false;
var _replyLeft = {};
var _replyOnly = 1;
var _replyOnce = true;
var _displayNumber = 200;   //聊天区显示的内容条数（常数）
var _displayStep = 20;      //每一次更新内容的个数
var _displayStart = -1;       //显示的第一条序号
var _displayEnd = -1;     //显示的最后一条序号
var _lastTotal = 0;      //上一次处理时聊天区内容总数（包括隐藏的）
var _deleteTextList = [];  //要删除的文本的msg_id
var _iconList = ['14','13','19','20','75','65','74','57','50','90','114','29','30','32','24','21','26','17','76','11','25','7','15','36','72'] ; //标签头像列表
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
	"0p":"001",
	"0q":"002",
	"0r":"003",
	"0s":"004",
	"0t":"005",
	"0u":"006",
	"0v":"008",
	"0w":"009",
	"0x":"010",
	"0y":"012",
	"0z":"016",
	"10":"018",
	"11":"022",
	"12":"023",
	"13":"027",
	"14":"028",
	"15":"031",
	"16":"033",
	"17":"034",
	"18":"035",
	"19":"037",
	"1a":"040",
	"1b":"044",
	"1c":"045",
	"1d":"046",
	"1e":"048",
	"1f":"051",
	"1g":"053",
	"1h":"054",
	"1i":"055",
	"1j":"056",
	"1k":"059",
	"1l":"061",
	"1m":"063",
	"1n":"064",
	"1o":"066",
	"1p":"070",
	"1q":"071",
	"1r":"073",
	"1s":"077",
	"1t":"081",
	"1u":"083",
	"1v":"085",
	"1w":"087",
	"1x":"088",
	"1y":"089",
	"1z":"091",
	"20":"120",
	"21":"121",
	"22":"125",
	"23":"129",
	"24":"150",
	"25":"170",
	"26":"171",
	"27":"172",
	"28":"173",
	"29":"174",
	"2a":"175",
	"2b":"176",
	"2c":"177",
	"2d":"178",
}
var _url2flag = {};
var _flag2urlReg = /\/:(..)/g;
var _url2flagReg = /<img src="\/assets\/images\/expression\/([0-9a-z]+)\.gif.*?".*?>/gi;
var _sortGoodTimerId = null;
var _sortGoodUser = {};
var _inputScrollTimerId = null;
function replaceUrl(str){
    if(/(yunke|gn100)\.com/i.test(str)){
        return str.replace('&nbsp;',' ').replace(/http(s?)(:\/\/)([a-z0-9_]+\.)?(yunke|gn100)(\.com)(\/\S*)?/ig,function(res){
            return "<a href='" + res + "' target='_blank'>" + res + "</a>";
        })
    }else{
        return str;
    }
}
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
function textResponse(data, _id){
    var dom = _chat_list.children("li[data-msg="+data.msg_id+"]");
    if(dom.length){
        return;
    }
    var thumb = data.uf_t;
    var name = data.uf_n;
    var level = data.uf_l;
    var leveltitle = data.uf_lt;
    var u = data.user_from_id;
    var vip = data.uf_vip || [];
    redName = (vip.length && vip.indexOf('red') > -1)?'red-name-card':'';
	if(!data.content){
		return false;
	}
    var a = data.content.replace(_flag2urlReg, flag2img);
    if(data.is_teacher){
        dom = '<li class="teacher" data-uid="'+u+'" data-msg="'+data.msg_id+'"><div class="s-face"><img src="'+teacherThumb+'"></div><div class="chat-main"><span class="s-name"><span>老师说</span></span><div class="chat-c"><i me>'+replaceUrl(a)+'</i></div></div></li>';
    }else{
        if(u==userId){
            dom ='<li class="self" data-uid="'+u+'" data-msg="'+data.msg_id+'"><div class="s-face"><img src="'+filecdn+thumb+'"></div><div class="chat-main"><span class="s-name"><a class="lv'+level+'" title="'+leveltitle +'"  href="https://www.yunke.com/index.rank.rule" target="_blank"></a> <span class="'+redName+'" data-name="我说">我说</span></span><div class="chat-c"><i me>'+replaceUrl(a)+'</i></div></div></li>';
        }else{
            dom = '<li data-uid="'+u+'" data-msg="'+data.msg_id+'"><div class="s-face"><img src="'+filecdn+thumb+'" title="'+name+'"></div><div class="chat-main"><span class="s-name" data-name="'+name+'"><a class="lv'+level+'" title="'+leveltitle +'"  href="https://www.yunke.com/index.rank.rule" target="_blank"></a><span class="'+redName+'" data-name="'+name+'">'+name+'</span></span><div class="chat-c"><i me>'+replaceUrl(a)+'</i></div></div></li>';
        }
    }
    if(_id < data.msg_id){
        var t = new Date();
        if(t - _lastDate > 60000){
            var mm = t.getMinutes();
            if(mm<10) mm = '0' + mm;
            _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
        }
        _lastDate = t;
    }
    _chat_list_cache.push(dom);
    // 加入弹幕列表 start
    if(supportDM && DM_comment.isNew){
        var DM_comment_c = {
            text: a,
            type: data.is_teacher?'teacher':u == userId?'self':null,
            ex: {
                stime: data.live_second,
                date: data.last_updated||'',
                dbid: data.msg_id,
                border: u == userId? true:false
            }
        };
        if(data.is_teacher){
            DM_comment_c.thumb = filecdn+thumb;
        }
        if(!isLiving){
            DM_comment.insert(DM_comment_c);
        }
        if(needDM){
            DM_comment.send(DM_comment_c);
        }
    }
    // 加入弹幕列表 end
    if(_id < data.msg_id){
        if(u == userId){
            _scrollFlag = true;
        }else{
            _displayChatList.modifyNewsNum(u, 1);
        }
    }
}

function sayGood(teacherName, uid, studentName){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">'+teacherName+'老师给'+studentName+'一个赞<img src="/assets_v2/img/icon-hand.png" style="vertical-align:top"></p></li>');
    _scrollflag = true;
}
function sayforbid(teachername, uid, studentname,command){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    if(command){
        _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">'+teachername+'老师对'+studentname+'禁言</p></li>');
    }else{
        _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">'+teachername+'老师对'+studentname+'解除禁言</p></li>');

    }
    _scrollflag = true;
}
function sayAsking(teachername, uid, studentname, command){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">'+teachername+'老师 请<b style="font-weight:normal">'+studentname+'</b>发言</p></li>');
    _scrollflag = true;
}
function sayline(uid, studentname, online){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    if(online){
        _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><div class="s-info"></div><div class="chat-main"><p class=""><b>'+studentname+'</b>进入教室</p></div></li>');
    }else{
        _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><div class="s-info"></div><div class="chat-main"><p class=""><b>'+studentname+'</b>退出教室</p></div></li>');
    }
    _displayChatList.modifyNewsNum(uid, 1);
}
function sayask(uid, studentname){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class=""><b>'+studentname+'</b>申请发言</p></li>');
    _displayChatList.modifyNewsNum(uid, 1);
}
function saycall(uid, studentname){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastdate = t;
    _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">老师对<b>'+studentname+'</b>进行点名</p></li>');
    _scrollFlag = true;
}
function sayMicrophone(uid, studentName){
    var t = new Date();
    if(t - _lastDate > 60000){
        var mm = t.getMinutes();
        if(mm<10) mm = '0' + mm;
        _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
    }
    _lastDate = t;
    _chat_list_cache.push('<li data-uid="'+uid+'" class="teacher"><p class="">老师对<b>'+studentName+'</b>进行测麦</p></li>');
    _scrollFlag = true;
}

function replyTextResponse(data, _id){
    var dom = _chat_list.children("li[data-msg="+data.msg_id+"]");
    if(dom.length){
        return;
    }
    var thumb = data.uf_t;
    var name = data.uf_n;
    var level = data.uf_l;
    var leveltitle = data.uf_lt;
    var u = data.uf;
    dom = '<li data-uid="'+u+'" data-msg="'+data.msg_id+'"title="'+data.last_updated+'" user_id="'+data.uf+'"><div class="s-face"><img src="'+filecdn+thumb+'"></div><div class="chat-main"><span class="s-name" data-name="'+name+'"><a class="lv'+level+'"  title="'+leveltitle +'"   href="https://www.yunke.com/index.rank.rule" target="_blank"></a><span data-name="'+name+'">'+name+'</span></span><div class="angle"></div><div class="chat-c"><b id="reply'+_replyOnly+'" class="reply_star" title="答题模式不可见">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></div></div></li>';
    _replyLeft[_replyOnly] = '<b c>'+replaceUrl(data.content.replace(_flag2urlReg, flag2img))+'</b>';
    _replyOnly++;
    if(_id < data.msg_id){
        var t = new Date();
        if(t - _lastDate > 60000){
            var mm = t.getMinutes();
            if(mm<10) mm = '0' + mm;
            _chat_list_cache.push('<li data-uid="'+userId+'"><p>'+t.getHours()+':'+mm+'</p></li>');
        }
        _lastDate = t;
    }
    _chat_list_cache.push(dom);
    console.log('回复')
    if(_id < data.msg_id){
        if(u == userId){
            _scrollFlag = true;
        }else{
            _displayChatList.modifyNewsNum(u, 1);
        }
    }
}
function deleteText(msg_id){
    _displayChatList.deleteMsg(msg_id);
}
function deleteTexts(){
    if(0==_deleteTextList.length){
        return;
    }
    for(var i=0;i<=_deleteTextList.length;i++){
        deleteText(_deleteTextList[i]);
    }
    _deleteTextList = [];
}

function replyRid(){
    if(0==_chat_list_cache.length){
        for(var i in _replyLeft){
            $("#reply"+i).replaceWith(_replyLeft[i]);
        }
        _replyLeft = {};
    }
}
function inputScroll(append){
    if(_chat_list_cache.length){
        var dom = $(_chat_list_cache.join(''));
        if(!_scrollFlag && true == append){
            if(_displayChatList.isToEnd(_activeGroupId)){
                _scrollFlag = true;
            }
        }
        if(true === append){
            _displayChatList.appends(dom);
        }else if(false === append){
            _displayChatList.prepends(dom);
        }else{
            _displayChatList.beforeDivs(dom);
        }
        _chat_list_cache = [];
        if(!_inputScrollTimerId){
            _inputScrollTimerId = setTimeout(function(){
                if(!_scrollFlag){
                    _displayChatList.move(_activeGroupId);
                }else{
                    _displayChatList.moveEnd(_activeGroupId);
                    _scrollFlag = false;
                }
                _inputScrollTimerId = null;
            }, 500);
        }
        if(!_scrollFlag){
            _displayChatList.showNews(_activeGroupId);
        }
    }
    if(!_isPatternReply){
        if(0!=_replyLeft.length){
            replyRid();
        }
    }
    deleteTexts();
    if(_good_user_cache.length){
        for(var i=0;i<100;i++){
            var u = _good_user_cache.shift();
            goodResponseLater2(u);
            if(0 == _good_user_cache.length){
                break;
            }
        }
    }
    if(_good_user_cache.length > 0){
        setTimeout(function(){inputScroll(true);}, 10);
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
function goodResponse(data, quick){
    if(quick){
        goodResponseLater2(data);
    }else{
        _good_user_cache.push(data);
    }
}
function setGoodNum(){
    $("#good_list").children().each(function(i, elem){
        $(this).find("i[good2]").text(i+1);
    });
    var dom = $("#good-"+userId);
    if(!dom.length){
        return;
    }
    var s = parseInt(dom.find("i[good2]").text());
    s = !isNaN(s)&&s>0?("第"+s+"名"):'暂无排名';
    var goodself = $("#good_self");
    goodself.find("i[good2]").text(s);
    $('#rankSelfNum').text(s);
    var courseingsort = $("#courseingsort");
    courseingsort.text(s);
}
function sortGoodInternal(u){
    var good = $("#good-"+u);
    if(!good.length){
        return;
    }
    var good2 = good.prev();
    if(!good2.length){
        return;
    }
    var num = parseInt(good.find("b[good]").text());
    if(num < parseInt(good2.find("b[good]").text())){
        return;
    }
    var p = good2.prev();
    while(p.length){
        if(num < parseInt(p.find("b[good]").text())){
            break;
        }
        good2 = p;
        p = good2.prev();
    }
    good.insertBefore(good2);
}
function sortGood2(){
    var data = [];
    for(var u in _sortGoodUser){
        data.push([u, _sortGoodUser[u]]);
    }
    data.sort(function(a, b){return b[1]-a[1];});
    for(var i in data){
        sortGoodInternal(data[i][0]);
    }
    setGoodNum();
    _sortGoodUser = {};
    _sortGoodTimerId = null;
}
function sortGood(u, num){
    _sortGoodUser[u] = num;
    if(_sortGoodTimerId){
        return;
    }
    _sortGoodTimerId = setTimeout(sortGood2, 1000);
}
function goodResponseLater2(data){
    var u = parseInt(data.ut);
    var num = parseInt(data.c);
    var thumb = data.ut_t;
    var level = data.ut_l;
    var leveltitle = data.ut_lt;
    var name = data.ut_n;
    var goodself = $("#good_self");
    var good_list = $("#good_list");
    var good = $("#good-"+u);
    if(0 == good.length){
        var i = good_list.children().length;
        good = $('<li id="good-'+u+'" userid = "'+u+'"><span><i good2>'+(i+1)+'</i></span><span class="face"><img src="'+filecdn+thumb+'"></span><a class="lv'+level+'" href="https://www.yunke.com/index.rank.rule" title="'+leveltitle+'" target="_blank"></a><span class="name" title="'+name+'">'+name+'</span><span class="info"><b good>'+num+'</b>次</span></li>');
        good_list.append(good);
    }else{
        num += parseInt(good.find("b[good]").text());
        good.find("b[good]").text(num);
    }
    if(u == userId){
        var courseinggood = $("#courseinggood");
        goodself.find("b[good]").text(num);
        courseinggood.text(num);
    }
    sortGood(u, num);
}
function setTotalModify(modify){
    var total = parseInt($("#student_total").find("b[total]").text());
    total += modify;
    if(total > 0){
        $("#student_total").find("b[total]").text(total);
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
    if($("#chat_send").hasClass("gray")||$("#chat_input").hasClass("error")){
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
        _displayChatList.append('<li data-uid="'+userId+'"><p>输入内容过长，请删除部分内容!</p></li>');
        _displayChatList.moveEnd(_activeGroupId);
        _scrollFlag = true;
        return;
    }
    if(_lastAddTime){
        var now = new Date();
        if(0 == _lastAddTime.length){
            _lastAddTime = [now];
        }else if(3 == _lastAddTime.length){
            if(now - _lastAddTime[2] < 30000){
                _displayChatList.append('<li data-uid="'+userId+'"><p>操作过于频繁，请稍后再试!</p><li>');
                _displayChatList.moveEnd(_activeGroupId);
                _scrollFlag = true;
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
    message.text(v2);
    textarea.html("");
    $("#chat_input").empty();
}
$(document).ready(function(){
    for(var k in _flag2url){
        var v = _flag2url[k];
        _url2flag[v] = k;
    }
    if(!_chat_list){
        _chat_list = $("#chat_list");
    }
    if(!_chat_group_list){
        _chat_group_list = $("#chat_group_list");
    }
    $("#chat_send").click(addMsg);
    $("#chat_input").keypress(function(e){
        if(10 == e.keyCode || 13 == e.keyCode){
            addMsg(e);
            setTimeout("$('#chat_input').empty()",100);
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
            pasteFunc($(this));
            return;
        }
    });
    $("#show").on('click','img[show]',function(e){
        var a = document.execCommand("insertImage", false, urlPath(e.target.src));
        if(!a){
            $("#chat_input").append("<img src='"+urlPath(e.target.src)+"' />");
        }
        $("#show").hide();
        //$("#chat_input").find("img").css("margin-top", "-10px");
    });
    $("#show_pop").on("click", function(e){
        if($(this).hasClass("gray")){
            return;
        }
		if($("#show").find('a').length == 0){
			for(i in _iconList){
				$("#show").append('<a class="icon1"><img show src="/assets/images/expression/'+_iconList[i]+'.gif"></a>');
			}
		}
        $("#show").show().focus();
    });
    $("#show").on("mouseleave", function(e){
        $("#show").hide();
    });
    $("#show").hide();
});
//把用户输入的内容转换成dom元素
function domMeFunc(data, thumb ,msg_id){
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
    var dom;
	dom =$('<li class="self" data-msg="'+msg_id+'"><div class="s-info"><span class="face"><img src="'+thumb+'"></span><a class="lv'+levelshow+'" title="'+leveltitleshow +'"  href="https://www.yunke.com/index.rank.rule" target="_blank"></a>我说</div><div class="chat-main"><div class="angle"></div><div class="chat-c"><i me></i></div></div></li>');
        dom.find("i[me]").append(replaceUrl(data[0]));
    return dom;
}
function domErrFunc(data){
    return $('<div class="dhk"><span><img src="/assets/images/grtx.jpg"></span><div class="jjj"></div><div class="mess"><p>' + 'error' + '说:<i me>' + data[0] + '</i></p></div></div>');
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
        chatMay(true);
    }
}
function startClass(notalkFlag){
    _classStart = true;
    message.startClass();
}
/*停止讲课**/
/* 停止讲课 发送停止讲课信息
 * 然后设置举手 然后设置成灰的
 */
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
