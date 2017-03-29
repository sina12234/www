(function($){
	$.fn.Xslider = function(options){var settings ={
			affect: 'scrollx', //效果  有scrollx|scrolly|fade|none
			speed: 1200, //动画速度
			space: 4000, //时间间隔
			auto: true, //自动滚动
			trigger: 'mouseover', //触发事件 注意用mouseover代替hover
			conbox: '.conbox', //内容容器id或class
			ctag: 'a', //内容标签 默认为<a>
			switcher: '.switcher', //切换触发器id或class
			stag: 'a', //切换器标签 默认为a
			current:'cur', //当前切换器样式名称
			rand:false //是否随机指定默认幻灯图片
		};
		settings = $.extend({}, settings, options);
		var index = 1;
		var last_index = 0;
		var $conbox = $(this).find(settings.conbox),$contents = $conbox.find(settings.ctag);
		var $switcher = $(this).find(settings.switcher),$stag = $switcher.find(settings.stag);
		if(settings.rand) {index = Math.floor(Math.random()*$contents.length);slide();}
		if(settings.affect == 'fade'){$.each($contents,function(k, v){(k === 0) ? $(this).css({'position':'absolute','z-index':9}):$(this).css({'position':'absolute','z-index':1,'opacity':0});
			});
		}
		function slide(){if (index >= $contents.length) index = 0;
			$stag.removeClass(settings.current).eq(index).addClass(settings.current);
			switch(settings.affect){case 'scrollx':
					$conbox.width($contents.length*$contents.width());
					$conbox.stop().animate({left:-$contents.width()*index},settings.speed);
					break;
				case 'scrolly':
					$contents.css({display:'block'});
					$conbox.stop().animate({top:-$contents.height()*index+'px'},settings.speed);
					break;
				case 'fade':
					$contents.eq(last_index).stop().animate({'opacity': 0}, settings.speed/2).css('z-index',1)
							 .end()
							 .eq(index).css('z-index',9).stop().animate({'opacity': 1}, settings.speed/2)
					break;
				case 'none':
					$contents.hide().eq(index).show();
					break;
			}
			last_index = index;
			//alert( $('.conbox>li:eq('+ index+')').html());
			/*var c = $("#myCanvas")[0];
            var ctx = c.getContext("2d");
            var img;
            img = $('img', this)[0];
            ctx.drawImage(img,0,0);
            var imgData = ctx.getImageData(0, 0, c.width, c.height);
            // 反转颜色
            for (var i = 0; i < imgData.data.length; i += 4) {
                imgData.data[i] =255-imgData.data[0];
                imgData.data[i + 1] =255-imgData.data[1];
                imgData.data[i + 2] =255-imgData.data[2];
                imgData.data[i + 3] = 255;
            };
            var bgcolor1;
                red = 255-imgData.data[0];
                blue = 255-imgData.data[1];
                green = 255-imgData.data[2];
                alpha = 1;
                bgcolor1 = 'rgba(' + red + ',' + blue + ',' + green + ',' + alpha + ')';

                $('.conbox>li:eq('+ index+')').css('background-color',bgcolor1);
            ctx.putImageData(imgData, 0, 0);*/
			index++;
		};
		if(settings.auto) var Timer = setInterval(slide, settings.space);
		$stag.bind(settings.trigger,function(){_pause()
			index = $(this).index();
			slide();
			_continue()
		});
		$conbox.hover(_pause,_continue);
		function _pause(){
			clearInterval(Timer);
		}
		function _continue(){
			if(settings.auto)Timer = setInterval(slide, settings.space);
		}
	}
})(jQuery);