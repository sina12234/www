$(document).ready(function(){
	//展开列表
	var path=document.location.pathname;
	$("#menu li a").each(function(i,item){
		var ul  = $(this).parents("ul");	
		if(path.indexOf($(ul).attr("path"))!==-1){
			ul.show().parents("div").find("p").addClass("shou");
		}
		if(path == $(item).attr("href")){
			$(this).parents("li").removeClass("curborc");
			$(this).parents("li").addClass("curborc");
		}
	});
	$("#menu .cc").click(function(){
		$(this).toggleClass('shou');
		$(this).next().toggle();
	});
	$('#menu_xs').on('click',function(){
		var list=$('.user_menu');
		var content=$('.user_content');
		if(list.hasClass("hidden-xs")){
			list.removeClass("hidden-xs");
			content.addClass("hidden-xs");
		}else{
			list.addClass("hidden-xs");
			content.removeClass("hidden-xs");
		}
	})    
});
