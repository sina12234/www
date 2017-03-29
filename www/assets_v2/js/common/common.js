(function (factory) {//jQuery Cookie
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {
	var pluses = /\+/g;
	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}
	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}
	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}
	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}
	var config = $.cookie = function (key, value, options) {
		// Write
		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}
			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read
		var result = key ? undefined : {};
		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}
			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}
		return result;
	};
	config.defaults = {};
	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}
		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));

(function($,undefined){//根据cookie提示上课
    var wo={};
    window.wo=wo;
    wo.remindCard=function(uid,pre){
        var dataObj={};
        var ckData={};//cookie值
        var dataArr=[];//ID数组
        var itemId={};//单个ID
        var uid=uid;//用户ID
        var pre=pre;//排课ID
        ckData=$.cookie("card_plan_id");
        if(ckData){
            dataArr=JSON.parse(ckData);
        }
        $.ajax({
            type:'get',
            url:'/course.ajax.getUserWillBeginRemindAjax/',
            data:{ uid:uid },
            dataType:'json',
            contentType:'application/json;charset=utf-8',
            async:'false',
            success:function(data){
                if(data.code==0){
                    dataObj=data.data;
                    addreminder();
                    if(typeof(ckData)=="undefined" || dataObj.length == 0){
                        $.each(dataObj,function(){
                            var thisPlan_id=this.plan_id;
                            if(!(pre&&pre==thisPlan_id)){
                                addCar(this);
                            }
                        });
                    }else{
                        $.each(dataObj,function(){
                            var flag=false;
                            var thisPlan_id=this.plan_id;
                            ckData=eval(ckData);
                            $.each(ckData,function(){
                                if((thisPlan_id==this.plan_id)||(pre&&pre==thisPlan_id)){
                                    flag=true;
                                }
                            })
                            if(!flag){
                                addCar(this);
                            }
                        })
                    }
                }
            }
        });

    function addreminder(){
        var reminder=$("<div>").addClass('class-reminder');
        $('body').append(reminder);
    }
    function addCar(m){
        var Div=$("<div>").addClass('reminder-ct reminder-ct-one fs14').attr('id',m.plan_id);
        var spanIcon=$("<span>").addClass('btn-clear clear-reminder-btn');
        var H6=$("<h6>").html('上课提醒：');
        var H5=$("<h5>").addClass('tec mt10');
        var p1=$("<p>").html(m.course_name);
        var p2=$("<p>").html('<span>'+m.section_name+'</span><span style="color: #f01400;">'+m.plan_start_time+'</span><span>'+m.plan_status_info+'</span>');
        var A=$("<a>").html('进入课堂').attr('href',m.url);
        Div.append(spanIcon).append(H6).append(p1).append(p2).append(H5.append(A));
        $("div.class-reminder").append(Div);
        spanIcon.on('click',function(){
            var thisPlan_id=$(this).parent().attr("id");
            itemId={plan_id:thisPlan_id};
            dataArr.push(itemId);
            var dataStr=JSON.stringify(dataArr);
            var domain=document.domain;
            domain=domain.split('.')[1]+'.com';
            $.cookie('card_plan_id',dataStr,{expires:1,path:'/',domain:domain});
            Div.remove();
        });
    }
    }
})(jQuery);
(function ($) {//获取url参数值
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    };
})(jQuery);
$(function () {
    //左右内容对齐
    var user_left_menu = $('.user-left-menu');
    var user_rign_main = $('.right-main');
    if(user_left_menu.outerHeight() > user_rign_main.outerHeight()){
        user_rign_main.css('min-height',user_left_menu.outerHeight());
    } else {
         user_left_menu.css('min-height',user_rign_main.outerHeight());
    };
})

function toType(obj) {
    return ({ }).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
}
function transformArr(data) {
    var arr = [];
    for(var i in data){
        data[i]['id'] = i;
        arr.push(data[i]);
    }
    return arr;
}


jQuery.fn.watch = function( id, fn ) {//select模拟
    return this.each(function(){
        var self = this;
        var oldVal = $(self).text();
        $(self).data(
            'watch_timer',
            setInterval(function(){
                if ($(self).text() !== oldVal) {
                    fn.call(self, id, oldVal, $(self).text());
                    oldVal = $(self).text();
                }
            }, 100)
        );

    });
    return self;
};
jQuery.fn.unwatch = function( id ) {
    return this.each(function(){
        clearInterval( $(this).data('watch_timer') );
    });
};
jQuery.fn.valuechange = function(fn) {
    return this.bind('valuechange', fn);
};
jQuery.event.special.valuechange = {
    setup: function() {
        var elem = jQuery(this);
        elem.watch(elem.text(), function(){
            elem.trigger('valuechange');
        });
    },
    teardown: function() {
        jQuery(this).unwatch('value');
    }
};
jQuery.divselect = function(divselectid) {
    $(document).on('click', divselectid, function(){
        $(divselectid).parent().find('dl').hide();
        $(divselectid).parent().css("z-index","1");
        $(divselectid).parent().find('dl').css("z-index","1");
        var thisinput=$(this);
        var thisul=$(this).parent().find("dl");
        var thisval=$(this).parent().find("input");
        var thisdiv=$(this).parent();
        if(thisul.css("display")=="none"){
            thisul.slideDown("fast");
            thisdiv.css("z-index","12");
            thisul.css("z-index","12");
            thisul.hover(function(){},function(){thisul.fadeOut("100");})
            thisul.find("dd").click(function(){
                $(this).parent().find('dd').removeAttr('selected');
                $(this).attr('selected','selected');
                thisinput.find('.cite-text').text($(this).text());
                thisval.val($(this).find('a').attr('selectid'));
                thisul.css("z-index","1");
                thisdiv.css("z-index","1");
                thisul.fadeOut("fast");
                if($(this).find('a').attr('selectid')=="course"){
                    var so_txt="搜课程名称，科目试试吧";
                }else if($(this).find('a').attr('selectid')=="teacher"){
                    var so_txt="搜老师名称，科目试试吧";
                }else if($(this).find('a').attr('selectid')=="organization"){
                    var so_txt="搜学校名称，简介试试吧";
                }else{
                    var so_txt="搜课程名称，科目试试吧";
                }
                $("#s_val").attr("placeholder",so_txt);
            });
        }else{
            thisul.fadeOut("fast");
            $(this).css("z-index","1");
        }
    });
    $(document).click(function(event){
        if(!$(event.target).parents('cite')[0] &&!$(event.target).is('cite') && event.target.className != 'cite-text' && event.target.className != 'cite-icon'){
            $(divselectid).parent().find('dl').hide();
            $(divselectid).parent().css("z-index","1");
            $(divselectid).parent().find('dl').css("z-index","1");
        }
    });
};

$(window).load(function () {
    var divSelectFirstVal = $('#divSelectFirstVal');
        if(!divSelectFirstVal.hasClass('divSelectFirstVal')){
            (function () {
                var divselect = $('.divselect');
                    divselect.each(function () {
                        var _select='';var _selectVal='';var _selectText='';
                        var inputVal = $.trim($(this).find('input').val());
                        if(inputVal==''){
                            var firstDD = $(this).find('dd').eq(0);
                            firstDD.attr('selected','selected');
                            _selectVal = firstDD.find('a').attr('selectid');
                            _selectText = firstDD.find('a').text();
                            $(this).find('input').val(_selectVal);
                            $(this).find('.cite-text').text(_selectText);
                        }else{
                            $(this).find('dd').each(function () {
                                _select = $(this).find('a');
                                _selectVal = _select.attr('selectid');
                                if(_selectVal == inputVal){
                                    _selectText = _select.text();
                                    $(this).attr('selected','selected');
                                    $(this).parents('.divselect').find('.cite-text').text(_selectText);
                                    return false;
                                }
                            });
                        }
                    });
            })();
        }

});

// 复制
;(function($){
	$.fn.clipData=function(options){
		var defaults={
			ele:'.copyurl',//元素
			copyurl:'data-url',//复制内容
			tips:'您的浏览器不支持直接复制的功能，建议您使用Ctrl+C或右键全选进行地址复制',//不支持复制是，提示内容
		};
		var options= $.extend(defaults,options);
		var _copy_ele = this.find(options.ele);
		var _clipboardData = 0;
		window.clipboardData ? _clipboardData=1 : _clipboardData=0;

		$(_copy_ele).on('click',function(){
			var _text = $(this).attr(options.copyurl) || 0;
			if( _clipboardData == 0){
				layer.open({
					type: 1,
					anim: 2,
                    area: '250px',
					title:'提示',
					shadeClose: true, //开启遮罩关闭
					content: '<div class="p10 fs14" style="word-wrap:break-word;">'+options.tips +'<br><br>'+ _text+'</div>'
				});
			}else{
				window.clipboardData.setData("Text", _text);
				layer.msg('复制成功');
			}

		})
	}
})(jQuery);

/*
 * integralMedalLayer  积分弹框
 * msg : String 提示话术例如：2积分
 * fun : 弹框消失后执行回调
 * */
function integralMedalLayer(msg,fun){
    layer.msg('<div class="metal-note" style="width:200px;height:200px"><img src="/assets_v2/img/medal-bg.png" /><span>'+msg+'</span></div>', {
        shade:0,
        shift:0,
        time: 1300,
        success: function(layero, index){
            layero.css({
                'background-color': 'transparent'
            });
            layer.setTop(layero);
        }
    }, function(){
        typeof fun == "function" && fun();
    });
}

