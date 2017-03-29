//page limit导航
function paramsUrl(data){
	var url = location.protocol + "//" + location.host;
	if(location.port){
		url += ":" + location.port;
	}
	url += location.pathname + "?";
	for(var k in data){
		url += k + "=" + data[k] + "&";
	}
	return url.substring(0, url.length-1);
}
/*参数：page -- 当前页数（1开头）
 * 		num -- 每页个数
 * 		total -- 总个数
 * 		step -- 当前页前后额外显示的页码数（单项，大于0）
 * 		params -- url额外参数（默认{}）
 */
function page_limit(page, num, total, step, params){ console.log("page=["+page+"] num=["+num+"] total=["+total+"] step=["+step+"]\n");
	if(0 == total){
		return;
	}
	var total_page = parseInt((total+num-1) / num);
	var display_list = [];
	if(total_page <= 3+2*step){
		for(var i=0;i<total_page;i++){
			display_list.push(i+1);
		}
	}else{
		if(page <= 2+step){
			for(var i=0;i<2+2*step;i++){
				display_list.push(i+1);
			}
			display_list.push(0);
			display_list.push(total_page);
		}else if(page >= total_page-1-step){
			display_list.push(1);
			display_list.push(0);
			for(var i=total_page-2*step-2;i<total_page;i++){
				display_list.push(i+1);
			}
		}else{
			display_list.push(1);
			display_list.push(0);
			for(var i=page-step;i<=page+step;i++){
				display_list.push(i);
			}
			display_list.push(0);
			display_list.push(total_page);
		}
	}
	var ul = $("<ul></ul>");
	for(var i=0;i<display_list.length;i++){
		var v = display_list[i];
		if(0 == v){
			ul.append("<li>...</li>");
		}else if(page == v){
			ul.append('<li class="big"><a>'+v+'</a></li>');
		}else{
			ul.append('<li page="'+v+'" class="ban"><a>'+v+'</a></li>');
		}
	}
	//var div = $('<div class="Page"></div>');
	//div.append(ul);
	params["num"] = num;
	ul.find("li[page]").click(function(e){
		params["page"] = $(this).attr("page");
		location.href = paramsUrl(params);
	});
	return ul;
}
