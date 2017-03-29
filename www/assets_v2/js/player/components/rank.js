// 排名模块
define(['jquery'],function($){
	return {
		checksort: function(sort){
			var endinggoodlist = $("#endinggoodlist");
			var name = $("#good_list li").eq(sort).find(".name").html();
			var img = $("#good_list li").eq(sort).find(".face img").attr("src");
			var typeimg = typeof(img);
			var typename = typeof(name);
			if(typeimg=="string"||typename=="string"){
			                var dom = $('<li><i class="top-'+(sort+1)+'">'+(sort+1)+'</i><span class="face"><img src="'+img+'"></span><span class="name">'+name+'</span></li>');
			    endinggoodlist.append(dom);
			}
		}
	}
});