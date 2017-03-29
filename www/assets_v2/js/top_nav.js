/*
* 右侧导航*/

if(top_nav_img){
	var tHtml=" <div id='TopNav' class='Top'><ul><li class='Top_wx top_p'><div class='Top_l1 Top_l'><img src='"+top_nav_img+"' alt='二维码'  /></div></li><li><a class='Top_yj top_p' href='/index.feedback'></a><div class='Top_l'><a href='/index.feedback'>意见反馈</a><em></em></div></li><li class='Top_pic top_p' style='display: none;'><div class='Top_l'><a href='javascript:;'>返回顶部</a><em></em></div></li></ul></div>"
	if($(window).width()>768){
		$("body").append(tHtml);
	};
	$("#TopNav li").hover(
		function(){
			$(this).find("div").stop().animate({
				left:-112+'px', opacity: 'show'
			},"normal");
		},
		function(){
			$(".Top li").find("div").hide().css("left",-140+'px');
		}
	);
	$("#TopNav").on( 'click','.Top_pic',function(){
		$('html,body').animate({ scrollTop: '0px' },'slow');
	});
	$(window).scroll(function() {
		var win_h = $(window).height();
		if ($(document).scrollTop() > win_h) {
			$("#TopNav .Top_pic").css("display", 'block');
		} else {
			$("#TopNav .Top_pic").css("display", 'none');
		}
	});

}
