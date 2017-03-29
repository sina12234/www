$(document).ready(function(){
	$(".part").each(function(i,item){
		$.ajax({url:$(this).attr("url"),context:this,success:function(data){
			$(this).html(data);
		}});
	});
});

<!--标签切换
function c(i){
	var i;
	document.getElementById("cs").className="csD"+i;
	window.name=i;//window.name版
}
onload=function(){
	var a=document.links;
	for(var i=0;i<a.length;i++)a[i].onfocus=function(){this.blur();}
}
//-->
		
<!--星级评价	
//挪走了
/*$(function(){
	
	$('.star_ul a').hover(function(){
		$(this).addClass('active-star');
		$(this).parent().closest("[score]").find(".s_result").css('color','#ff711b').html($(this).attr('title'))
		//$('.s_result').css('color','#ff711b').html($(this).attr('title'))
	},function(){
		$(this).removeClass('active-star');
		var p = $(this).parent().closest("[score]");
		p.find('.s_result').css('color','#333').html(p.attr("title"))
		//$('.s_result').css('color','#333').html('&nbsp;')
	});
	
	$('.star_ul a').click(function(e){
	//	console.log($('.s_result').html($(this).attr('title'))
	//	alert($('.s_result').html());
		e.preventDefault();
		var p = $(this).parent().closest("[score]");
		p.attr("score", $(this).attr("score"));
		p.attr("title", $(this).attr("title"));
		$(this).addClass('active-star');
	});
	
	$('.square_ul a').hover(function(){
		$(this).addClass('active-square');
		$(this).parents('.starbox').find('.s_result_square').css('color','#c00').html($(this).attr('title'))
	},function(){
		$(this).removeClass('active-square');
		$(this).parents('.starbox').find('.s_result_square').css('color','#333').html('&nbsp;')
	});
	
	$('.square_ul a').click(function(){
		alert($(this).parents('.starbox').find('.s_result_square').html());
	});
})*/
//-->

<!--显示隐藏
function Layer_HideOrShow(cur_div)
{ var current=document.getElementById(cur_div);
    if(current.style.display="none")
    {
        current.style.display="";
    }
    else
    {
        current.style.display="none";
    }
}
//-->


function show_1() {
    
    if (document.getElementById("hideshow_1").checked) {
        
        document.getElementById("hs_1").style.display = "block";
        document.getElementById("hs_2").style.display = "none";
        
    }
    
    else {
        
        document.getElementById("hs_1").style.display = "none";
        document.getElementById("hs_2").style.display = "block";
        
    }
    
}

/*下拉菜单*/
$(function(){
  $(".select").each(function(){
                    var s=$(this);
                    var z=parseInt(s.css("z-index"));
                    var dt=$(this).children("dt");
                    var dd=$(this).children("dd");
                    var _show=function(){dd.slideDown(200);dt.addClass("cur");s.css("z-index",z+1);};   //展开效果
                    var _hide=function(){dd.slideUp(200);dt.removeClass("cur");s.css("z-index",z);};    //关闭效果
                    dt.click(function(){dd.is(":hidden")?_show():_hide();});
                    dd.find("a").click(function(){dt.html($(this).html());_hide();});     //选择效果（如需要传值，可自定义参数，在此处返回对应的“value”值 ）
                    $("body").click(function(i){ !$(i.target).parents(".select").first().is(s) ? _hide():"";});
                    })
  })

