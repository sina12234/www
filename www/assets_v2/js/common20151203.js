/*
 * 公共的效果*/

//图左右滑动
;(function($){
	$.fn.carousel=function(options){
		var defaults={
			R_li:'.o_list',//需要滚动的元素
			R_ul:'.r_ul',//滚动区域
			prev:'.prev',//点击向上翻转箭头
			next:'.next',//点击向下翻转箭头
		};
		var options= $.extend(defaults,options);
		this.each(function(){
			var _this=$(this);
			var n_count=0;
			var Len_li=_this.find(options.R_li).length;
			var W_li=_this.find(options.R_li).outerWidth();
			var H_ul=Len_li*W_li;
			var W_box=1155;

			$(options.R_ul).css("width",H_ul+'px');
			var H_li=H_ul/_this.find(options.R_li).length;
			//获取对应class的位置
			var n_class=$(".r_ul").find(".live-icon1").parents(".o_list").last().index();
			if(n_class<0){
				n_class=$(".r_ul").find(".live-icon2").parents(".o_list").last().index();
				if(n_class<0){
					n_class=$(".r_ul").find(".live-icon6").parents(".o_list").last().index();
					if(n_class<0){
						n_class=0;
					}
				}
			};
			$(options.R_ul).css("left",-n_class*W_li+'px');
			var W_rul=$(options.R_ul).outerWidth();
			var nTop=$(options.R_ul).css("left");
			var str=Math.floor(Number(nTop.substring(0,nTop.length-2)));
			var n_ct=parseInt(str/W_box);
			//页面加载判断区域

			if(H_ul+str<W_box) {
				$(".next").addClass("dasabled").css({
					cursor: 'auto'
				});
			}
			if(n_ct==0){
				n_count=-1;
			}else{
				n_count=parseInt(str/W_box);
			}
			function r_ul(n_count){
				$(options.R_ul).stop().animate({left : W_box*n_count},'slow');
			}
			//next
			function up_fn(){
				if($(options.next).hasClass("dasabled")){
					return false;
				}
				if(W_box*(n_count-1)>=-H_ul){
					n_count--;
					if(H_ul+W_box*(n_count)>W_box){
						r_ul(n_count);
						$(".prev").removeClass("dasabled").css({
							cursor: 'pointer'
						});
					}else{
						$(options.R_ul).stop().animate({left : -(H_ul-W_box)+'px'},'slow');
						$(".next").addClass("dasabled").css({
							cursor: 'auto'
						});
						return false;
					}
				}else{
					return false
				}
			};
			$(options.next).on('click',function(){
				up_fn();
			});
			//prev
			$(options.prev).on('click',function(){
				//初始判断
				if(W_box*n_count<0){
					n_count++;
					console.log(n_count*W_box+':'+n_count)
					r_ul(n_count);
					console.log(n_count)
					$(".next").removeClass("dasabled").css({
						cursor: 'pointer'
					});
					if(W_box*n_count==0){
						$(".prev").addClass("dasabled").css({
							cursor: 'auto'
						});
						return false;
					}
					$(".next").removeClass("dasabled").css({
						cursor: 'pointer'
					});
				}else{
					$(".prev").addClass("dasabled").css({
						cursor: 'auto'
					});
					return false;
				}
			});
		});
	}
})(jQuery);

$(function(){

	var phoReg=/^1(3|5|8)[0-9]{9}$/;//手机验证
	var nReg=/^-?[0-9]\d*$/;//匹配整数
	var nameReg=/^[\u4e00-\u9fa5]{2,5}$|^[A-Za-z]{1,16}$/;//匹配名字
//显示图片验证
//验证手机
	//$("#mobile").bind({
		//blur:function(){
			//var sPho=$(this).val();
			//if(sPho){
			//	if(!(phoReg.test(sPho))){
			//		$(".mobile-error-tip").html("手机号格式错误").show();
			//	}else{
			//		$(".mobile-error-tip").html("");
			//	}
			//}else{
			//	$(".mobile-error-tip").html("");
			//}
		//},
		//keyup:function(){
		//	var nPho=$(this).val();
		//	if(nPho){
		//		if( nPho.length>11 || !(nReg.test(nPho))){
		//			$(".mobile-error-tip").html("请输入正确手机号").show();
		//		}else{
		//			$(".mobile-error-tip").html("");
		//		}
		//	}else{
		//		$(".mobile-error-tip").html("");
		//	}
		//}
//	});
//验证姓名
	$(".nameCode").bind('blur',function(){
		var sName=$(this).val();
		if(sName){
			if(!(nameReg.test(sName))){
				$(".name-error-tip").html("姓名少于5个汉字或16个字符，用于上课使用").show();
			}else{
				$(".name-error-tip").html("").css({
					"height":"22px",
					"padding":"1px"
				});
			}
		}else{
			$(".name-error-tip").html("").css({
				"height":"22px",
				"padding":"1px"
			});
		}
	});
});

