$(document).ready(function(){
    //展开列表
	$("body").on("click","#menu .cc",function(){
      $(this).toggleClass('shou');
	    $(this).next().toggle();
	    
	}).on("click",'#menu_xs',function(){
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
