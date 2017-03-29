/*
* pageid    节点id
* path  路径
* num   每页个数 
* page  页号
* total 总页数
*/
function page(pageid,path,num,page,total){
	var dom = $("<ul></ul>").appendTo($("#"+pageid));
	var dompre = $("#"+pageid);
	var _path = path;
	var _num = num;
	var _total= total;
	var _page = page;
	var pagebefore;
	var pageafter;
	var pagethis;
	var cla;
	var title;
	var language = getCookie("language");
	if(0 == _page){
		return;
	}
	if(_total<=10){
		if(_page-1 > 0){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页");
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page-1,_num,"PREV","PREV");
			}else{
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页");
			}
			dom.append(prev);
		}
		for(var i=9;i>0;i--){
			if(_page-i>0){
				pagebefore = createDom("",_path,_page-i,_num,"",_page-i);
				dom.append(pagebefore);
			}
		}
	//	if(_total != 1){
		if((_total!=1)&&(_total!=0)){
			var pagethis = $('<li class="active">'+_page+'</li>');
			dom.append(pagethis);
		}
		for(var a=1;a<10;a++){
			if(_page+a <=_total){
				pageafter = createDom("",_path,_page+a,_num,"",_page+a);
				dom.append(pageafter);
			}
		}
		if(_page+1 <= _total){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页");
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page+1,_num,"NEXT","NEXT");
			}else{
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页");
			}
			dom.append(prev);
		}
	}else{
		if(_page-1 > 0){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页");
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page-1,_num,"PREV","PREV");
			}else{
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页");
			}
			dom.append(prev);
		}
		if(_page-2 > 1){
			var index = createDom("prev",_path,1,_num,"首页","1");
			dom.append(index);
			var start = createDom("next",_path,_total,_num,"",_total);
			dom.append(end);
			var dot = $('<li class="dot">...</li>');
			dom.append(dot);
		}
		for(var i=2;i>0;i--){
			if(_page-i>0){
				pagebefore = createDom("",_path,_page-i,_num,"",_page-i);
				dom.append(pagebefore);
			}
		}
		var pagethis = $('<li class="active">'+_page+'</li>');
		dom.append(pagethis);
		for(var a=1;a<3;a++){
			if(_page+a <=_total){
				pageafter = createDom("",_path,_page+a,_num,"",_page+a);
				dom.append(pageafter);
			}
		}
		if(_page+2 < _total){
			var dot = $('<li class="dot">...</li>');
			dom.append(dot);
			var end = createDom("next",_path,_total,_num,"",_total);
			dom.append(end);
		}
		if(_page+1 <= _total){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页");
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page+1,_num,"NEXT","NEXT");
			}else{
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页");
			}
			dom.append(prev);
		}
	}

	function createDom(_cla,_path,_page,_num,_title,_text){
		if(_path.indexOf("?")==-1){
			return  $('<li class="'+_cla+'"><a href="'+_path+'?page='+_page+'&size='+_num+'"  title="'+_title+'">'+_text+'</a></li>')
		}else{
			return  $('<li class="'+_cla+'"><a href="'+_path+'&page='+_page+'&size='+_num+'"  title="'+_title+'">'+_text+'</a></li>')
		}
	}
	function getCookie(cookieName)  {
		var cookieValue = document.cookie;
		var cookieStartAt = cookieValue.indexOf(""+cookieName+"=");
		if(cookieStartAt==-1)  {
			cookieStartAt = cookieValue.indexOf(cookieName+"=");
		}
		if(cookieStartAt==-1)  {
			cookieValue = null;
		}
		else {
			cookieStartAt = cookieValue.indexOf("=",cookieStartAt)+1;
			cookieEndAt = cookieValue.indexOf(";",cookieStartAt);
			if(cookieEndAt==-1){
				cookieEndAt = cookieValue.length;
			}
			cookieValue = unescape(cookieValue.substring(cookieStartAt,cookieEndAt));//解码latin-1
		}
		return cookieValue;
	} 
}
/*
 * pageid    节点id
 * path  路径
 * num   每页个数
 * page  页号
 * total 总页数
 */
function pageAjax(pageid,path,num,page,total,_click){
	var dom = $("<ul></ul>").appendTo($("#"+pageid));
	var dompre = $("#"+pageid);
	var _path = path;
	var _num = num;
	var _total= total;
	var _page = page;
	var pagebefore;
	var pageafter;
	var pagethis;
	var cla;
	var title;
	var language = getCookie("language");
	if(0 == _page){
		return;
	}
	if(_total<=10){
		if(_page-1 > 0){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页",_click);
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page-1,_num,"PREV","PREV",_click);
			}else{
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页",_click);
			}
			dom.append(prev);
		}
		for(var i=9;i>0;i--){
			if(_page-i>0){
				pagebefore = createDom("",_path,_page-i,_num,"",_page-i,_click);
				dom.append(pagebefore);
			}
		}
		//	if(_total != 1){
		if((_total!=1)&&(_total!=0)){
			var pagethis = $('<li class="active">'+_page+'</li>');
			dom.append(pagethis);
		}
		for(var a=1;a<10;a++){
			if(_page+a <=_total){
				pageafter = createDom("",_path,_page+a,_num,"",_page+a,_click);
				dom.append(pageafter);
			}
		}
		if(_page+1 <= _total){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页",_click);
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page+1,_num,"NEXT","NEXT",_click);
			}else{
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页",_click);
			}
			dom.append(prev);
		}
	}else{
		if(_page-1 > 0){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页",_click);
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page-1,_num,"PREV","PREV",_click);
			}else{
				var prev = createDom("prev",_path,_page-1,_num,"上一页","上一页",_click);
			}
			dom.append(prev);
		}
		if(_page-2 > 1){
			var index = createDom("prev",_path,1,_num,"首页","1",_click);
			dom.append(index);
			var start = createDom("next",_path,_total,_num,"",_total,_click);
			dom.append(end);
			var dot = $('<li class="dot">...</li>');
			dom.append(dot);
		}
		for(var i=2;i>0;i--){
			if(_page-i>0){
				pagebefore = createDom("",_path,_page-i,_num,"",_page-i,_click);
				dom.append(pagebefore);
			}
		}
		var pagethis = $('<li class="active">'+_page+'</li>');
		dom.append(pagethis);
		for(var a=1;a<3;a++){
			if(_page+a <=_total){
				pageafter = createDom("",_path,_page+a,_num,"",_page+a,_click);
				dom.append(pageafter);
			}
		}
		if(_page+2 < _total){
			var dot = $('<li class="dot">...</li>');
			dom.append(dot);
			var end = createDom("next",_path,_total,_num,"",_total,_click);
			dom.append(end);
		}
		if(_page+1 <= _total){
			if(language=="zh-cn"){
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页",_click);
			}else if(language=="en"){
				var prev = createDom("prev",_path,_page+1,_num,"NEXT","NEXT",_click);
			}else{
				var prev = createDom("prev",_path,_page+1,_num,"下一页","下一页",_click);
			}
			dom.append(prev);
		}
	}

	function createDom(_cla,_path,_page,_num,_title,_text,_click){
		if (_path.indexOf("?") == -1) {
			return $('<li class="' + _cla + '"><a href="javascript:;"  title="' + _title + '" onclick="'+_click+'(\'' + _path + '\',' + _page + ',' + _num + ')">' + _text + '</a></li>')
		} else {
			return $('<li class="' + _cla + '"><a href="javascript:;"  title="' + _title + '"onclick="'+_click+'(\'' + _path + '\',' + _page + ',' + _num + ')">' + _text + '</a></li>')
		}
	}
	function getCookie(cookieName)  {
		var cookieValue = document.cookie;
		var cookieStartAt = cookieValue.indexOf(""+cookieName+"=");
		if(cookieStartAt==-1)  {
			cookieStartAt = cookieValue.indexOf(cookieName+"=");
		}
		if(cookieStartAt==-1)  {
			cookieValue = null;
		}
		else {
			cookieStartAt = cookieValue.indexOf("=",cookieStartAt)+1;
			cookieEndAt = cookieValue.indexOf(";",cookieStartAt);
			if(cookieEndAt==-1){
				cookieEndAt = cookieValue.length;
			}
			cookieValue = unescape(cookieValue.substring(cookieStartAt,cookieEndAt));//解码latin-1
		}
		return cookieValue;
	}
}