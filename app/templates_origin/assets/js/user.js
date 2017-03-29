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

	// 截屏按钮
	$('#jie').on('click',function(){
		var h=620;
		$(this).animate({
			width:'20px',
			height:'20px',
			top:h+'px',
			right:'-50px'
		},1000)
		if("dh" == $("#img").css("animation-name")){
			$("#img").css("animation-name", "none");
		}else{
			$("#img").css("animation-name", "dh");
		}
	})  
});
