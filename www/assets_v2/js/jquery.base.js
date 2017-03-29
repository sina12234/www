/*
*Name:Changyuan.Liu
*Date:10-16-2015
*Main:PC-ClassSubjectDate
*/
(function($, window, document) {

    var pluginName = 'calendar',
        pl = null,
        d = new Date();

    defaults = {
        d: d,
        year: d.getFullYear(),
        today: d.getDate(),
        month: d.getMonth(),
        current_year: d.getFullYear(),
        tipsy_gravity: 's'
    };

    month_array = [
        '一月',
        '二月',
        '三月',
        '四月',
        '五月',
        '六月',
        '七月',
        '八月',
        '九月',
        '十月',
        '十一月',
        '十二月'
    ];

    month_days = [
        '31', // jan
        '28', // feb
        '31', // mar
        '30', // apr
        '31', // may
        '30', // june
        '31', // july
        '31', // aug
        '30', // sept
        '31', // oct
        '30', // nov
        '31'  // dec
    ];

    // Main Plugin Object
    function Calendar(element, options) {
        pl = this;
        this.element = element;
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;

        // Begin
        this.init();
    }

    // Init
    Calendar.prototype.init = function() {
        this.print();
    }

    Calendar.prototype.print = function(year) {
        var the_year = (year) ? parseInt(year) : parseInt(pl.options.year);

        $(this.element).empty();

        $('.label').css({
            display: 'none'
        });
        //年份
        $(this.element).append('<div id=\"calendar\"></div>');

        var $_calendar = $('#calendar');

        $.each(the_year.toString(), function(i,o) {
            $_calendar.append('<div class=\"year\">' + o + '</div>');
        });

        $_calendar.append('<div class=\"clear\"></div>');

        //月份
        $.each(month_array, function(i, o) {

            $_calendar.append("<div class='now-month'>" + o + "</div>");
            $_calendar.append('<div class=\"clear\"></div>');
            //月份的天数28 or 29
            if (o === 'February') {
                if (pl.isLeap(the_year)) {
                    month_days[i] = 29;
                } else {
                    month_days[i] = 28;
                }
            }
            //判断是否为今天
            for (j = 1; j <= parseInt(month_days[i]); j++) {

                var today = '';
                if (i === pl.options.month && the_year === d.getFullYear()) {
                    if (j === pl.options.today) {
                        today = 'today';

                    }
                }
                if((parseInt(i) + 1)<10){
                    var _Month = '0' + (parseInt(i) + 1);
                }else{
                    var _Month = (parseInt(i) + 1);
                }
                if(j<10){
                    var _Day = '0' + j;
                }else{
                    var _Day = j;
                }

                $_calendar.append("<div data-date='" + the_year + '-' + (parseInt(i) + 1) + '-' + j + "' class='label day " + today + "'>" + "<div class='week'>" + "</div>" + "<div class='date'>" + (parseInt(i) + 1) + '-' + j + "</div>" + "<input type='hidden' name='alltime' class='hidden-date' value='"+ the_year +'-'+ _Month +'-'+ _Day +"' />" + '</div>');
            }   

            $_calendar.append('<div class=\"clear\"></div>');

        });

        for (k = 0; k < $('.label').length; k++) {
            (function(j) {
                    $($('.label')[j]).fadeIn('fast', function() {
                        $(this).attr('original-title', pl.returnFormattedDate($(this).attr('data-date')));
                        var _week=$(this).attr('original-title');
                        if($(".tips span").html()=="Course list") {
                            if(_week=="周一") {
                                $(this).find(".week").html("Mon");
                            }else if(_week=="周二"){
                                $(this).find(".week").html("Tues");
                            }else if(_week=="周三"){
                                $(this).find(".week").html("Wed");
                            }else if(_week=="周四"){
                                $(this).find(".week").html("Thur");
                            }else if(_week=="周五"){
                                $(this).find(".week").html("Fri");
                            }else if(_week=="周六"){
                                $(this).find(".week").html("Sat");
                            }else if(_week=="周日"){
                                $(this).find(".week").html("Sun");
                            }
                        }else{
                            $(this).find(".week").html(pl.returnFormattedDate($(this).attr('data-date')));
                        }
                    });
            })(k);
        }

        $('.label').tipsy({gravity: pl.options.tipsy_gravity});
    }
    //btn-right
    $(document).on('click', '.next', function() {
        pl.options.year = parseInt(pl.options.year) + 1;
        pl.print(pl.options.year);

    });
    //btn-left
    $(document).on('click', '.prev', function() {
        pl.options.year = parseInt(pl.options.year) - 1;
        pl.print(pl.options.year);
    });

    Calendar.prototype.isLeap = function(year) {
        var leap = 0;
        leap = new Date(year, 1, 29).getMonth() == 1;
        return leap;
    }

    Calendar.prototype.returnFormattedDate = function(date) {
        var returned_date;
        var d = new Date(date);
        var da = d.getDay();

        if (da === 1) {
            returned_date = '周一';
        } else if (da === 2) {
            returned_date = '周二';
        } else if (da === 3) {
            returned_date = '周三';
        } else if (da === 4) {
            returned_date = '周四';
        } else if (da === 5) {
            returned_date = '周五';
        } else if (da === 6) {
            returned_date = '周六';
        } else if (da === 0) {
            returned_date = '周日';
        }

        return returned_date;
    }

    $.fn[pluginName] = function(options ) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Calendar(this, options));
            }
        });
    }

})(jQuery, window, document);


(function($) {

    function maybeCall(thing, ctx) {
        return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
    };

    function Tipsy(element, options) {
        this.$element = $(element);
        this.options = options;
        this.enabled = true;
        this.fixTitle();
    };

    Tipsy.prototype = {
        show: function() {
            var title = this.getTitle();
            if (title && this.enabled) {
                var $tip = this.tip();

                $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
                $tip[0].className = 'tipsy';
                $tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).prependTo(document.body);

                var pos = $.extend({}, this.$element.offset(), {
                    width: this.$element[0].offsetWidth,
                    height: this.$element[0].offsetHeight
                });

                var actualWidth = $tip[0].offsetWidth,
                    actualHeight = $tip[0].offsetHeight,
                    gravity = maybeCall(this.options.gravity, this.$element[0]);

                var tp;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 's':
                        tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 'e':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
                        break;
                    case 'w':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
                        break;
                }

                if (gravity.length == 2) {
                    if (gravity.charAt(1) == 'w') {
                        tp.left = pos.left + pos.width / 2 - 15;
                    } else {
                        tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                    }
                }

                $tip.css(tp).addClass('tipsy-' + gravity);
                $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
                if (this.options.className) {
                    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
                }

                if (this.options.fade) {
                    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
                } else {
                    $tip.css({visibility: 'visible', opacity: this.options.opacity});
                }
            }
        },

        hide: function() {
            if (this.options.fade) {
                this.tip().stop().fadeOut(function() { $(this).remove(); });
            } else {
                this.tip().remove();
            }
        },

        fixTitle: function() {
            var $e = this.$element;
            if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
                $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
            }
        },

        getTitle: function() {
            var title, $e = this.$element, o = this.options;
            this.fixTitle();
            var title, o = this.options;
            if (typeof o.title == 'string') {
                title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
            } else if (typeof o.title == 'function') {
                title = o.title.call($e[0]);
            }
            title = ('' + title).replace(/(^\s*|\s*$)/, '');
            return title || o.fallback;
        },

        tip: function() {
            if (!this.$tip) {
                this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
            }
            return this.$tip;
        },

        validate: function() {
            if (!this.$element[0].parentNode) {
                this.hide();
                this.$element = null;
                this.options = null;
            }
        },

        enable: function() { this.enabled = true; },
        disable: function() { this.enabled = false; },
        toggleEnabled: function() { this.enabled = !this.enabled; }
    };

    $.fn.tipsy = function(options) {

        if (options === true) {
            return this.data('tipsy');
        } else if (typeof options == 'string') {
            var tipsy = this.data('tipsy');
            if (tipsy) tipsy[options]();
            return this;
        }

        options = $.extend({}, $.fn.tipsy.defaults, options);

        function get(ele) {
            var tipsy = $.data(ele, 'tipsy');
            if (!tipsy) {
                tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
                $.data(ele, 'tipsy', tipsy);
            }
            return tipsy;
        }

        function enter() {
            var tipsy = get(this);
            tipsy.hoverState = 'in';
            if (options.delayIn == 0) {
                tipsy.show();
            } else {
                tipsy.fixTitle();
                setTimeout(function() { if (tipsy.hoverState == 'in') tipsy.show(); }, options.delayIn);
            }
        };

        function leave() {
            var tipsy = get(this);
            tipsy.hoverState = 'out';
            if (options.delayOut == 0) {
                tipsy.hide();
            } else {
                setTimeout(function() { if (tipsy.hoverState == 'out') tipsy.hide(); }, options.delayOut);
            }
        };

        if (!options.live) this.each(function() { get(this); });

        if (options.trigger != 'manual') {
            var binder = options.live ? 'live' : 'bind',
                eventIn = options.trigger == 'hover' ? 'mouseenter' : 'focus',
                eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
            this[binder](eventIn, enter)[binder](eventOut, leave);
        }

        return this;

    };

    $.fn.tipsy.defaults = {
        className: null,
        delayIn: 0,
        delayOut: 0,
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        live: false,
        offset: 0,
        opacity: 0.8
    };

    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };

    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };

    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };

     $.fn.tipsy.autoBounds = function(margin, prefer) {
        return function() {
            var dir = {ns: prefer[0], ew: (prefer.length > 1 ? prefer[1] : false)},
                boundTop = $(document).scrollTop() + margin,
                boundLeft = $(document).scrollLeft() + margin,
                $this = $(this);

            if ($this.offset().top < boundTop) dir.ns = 'n';
            if ($this.offset().left < boundLeft) dir.ew = 'w';
            if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
            if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

            return dir.ns + (dir.ew ? dir.ew : '');
        }
    };

})(jQuery);
//skinChange
var skinChange = function() {
    var $book = $(".tab-main");
    var $left = $("#btn-right");
    var $right = $("#btn-left");
    var $ani = $book.find(".ul_width");
    var $w = 840;
    var $book_num = $book.find(".label").length;
    var $max_left = -(61 * $w);

    $left.click(function() {
        var $cur_coord = parseFloat($ani.css("left"));
        if (!$ani.is(":animated")) {
            if ($cur_coord > $max_left) {
                var $cur_coord = $ani.css("left");
                $ani.animate({
                    left: "-=" + $w + "px"
                }, 500);
            }
        }else {
            return false;
        };

    });

    $right.click(function() {
        var $cur_coord = $ani.css("left");
        if (!$ani.is(":animated")) {
            if ($cur_coord != "0px") {
                $ani.animate({
                    left: "+=" + $w + "px"
                }, 500);
            }
        } else {
            return false;
        };
    });
};
/*
*Name:Changyuan.Liu
*Company:www.yunke.com
*Function:invoking Jquery
*/
$(function(){
    var myDate = new Date();
    var _y=myDate.getFullYear();
    var _FullDate = myDate.getFullYear() + '-' + (myDate.getMonth()+1) + '-' + myDate.getDate();
    skinChange();

    //后面追加的
    var Html="<div class='afterbox'>"
        Html+="<div class='label day' data-date='2016-01-01'>"+"<div class='week'>"+"周五"+"</div>"+"<div class='date'>"+"01-01"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-01' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-02'>"+"<div class='week'>"+"周六"+"</div>"+"<div class='date'>"+"01-02"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-02' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-03'>"+"<div class='week'>"+"周日"+"</div>"+"<div class='date'>"+"01-03"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-03' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-04'>"+"<div class='week'>"+"周一"+"</div>"+"<div class='date'>"+"01-04"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-04' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-05'>"+"<div class='week'>"+"周二"+"</div>"+"<div class='date'>"+"01-05"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-05' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-06'>"+"<div class='week'>"+"周三"+"</div>"+"<div class='date'>"+"01-06"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-06' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-07'>"+"<div class='week'>"+"周四"+"</div>"+"<div class='date'>"+"01-07"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-07' />"+"</div>"
        Html+="<div class='label day' data-date='2016-01-08'>"+"<div class='week'>"+"周五"+"</div>"+"<div class='date'>"+"01-08"+"</div>"+"<input type='hidden' name='alltime' class='hidden-date' value='2016-01-08' />"+"</div>"
        Html+="</div>";
    $(".wrap_after").append(Html);

    //调用插件
    $("#wrap_calendar").calendar({
        tipsy_gravity: 's'
    });

    //初始化
    $("#main-container").find(".wrap_after").hide();
    if(_y=="2015") {
        $("#main-container").find(".wrap_after").show();
    }else{
        $("#main-container").find(".wrap_after").hide();
    }

    $("#calendar").find(".year").remove();
    $("#calendar").find(".clear").remove();
    $("#calendar").find(".now-month").remove();


    //遍历所有的.label
    $(".ul_width .label").each(function() {
        var _Data_Date = $(this).attr("data-date");
        var _t = $(this).attr("data-date");
        //显示当前加on的
        if(_FullDate == _Data_Date){
            $(".ul_width").css("left",-($(this).index())*140);
            $(this).addClass('on').siblings().removeClass('on');
            getCourseList(_t);
            if($(".tips span").html()=="Course list"){
                $(this).find(".date").html("Today");
            }else{
                $(this).find(".date").html("今天");
            }
        }
    });
 //事件委托绑定添加on事件
     $("#calendar").on("click",".label",function() {
        var c=$(this).attr("data-date");
        $(this).addClass('on').siblings().removeClass('on');
        getCourseList(c);
        $(".afterbox .label").removeClass("on");
     })
     $(".afterbox").on("click",".label",function() {
        $("#calendar .label").removeClass("on");
        var _c=$(this).attr("data-date");
        $(this).addClass('on').siblings().removeClass('on');
        getCourseList(_c);
     })
    //搜索
    $(".select-search-btn").on("click",function() {
        //获取input的值
        var oDateTimePickerMask = $(".datetimepicker_mask").val();
        var _DateTimePickerMaskLength = oDateTimePickerMask.substring(0,4);
        //判断input的val是否为空
        if(oDateTimePickerMask == "") {
            if($(".tips span").html()=="Course list") {
                $(".dele-date-btn").hide();
                layer.msg("Date can not be empty");
            }else{
                $(".dele-date-btn").hide();
                layer.msg("日期不能为空");
            }
        }else{
            getCourseList(oDateTimePickerMask);
            $(".dele-date-btn").show();
       }

        $(".ul_width .label").each(function() {        
            var oLiValue = $(this).find(".hidden-date").val();
            var _SearchData_Date = $(this).find(".hidden-date").val();

            if(oDateTimePickerMask == _SearchData_Date) {
                $(this).addClass('on').siblings().removeClass('on');
                $(".ul_width").css("left",-(($(this).index())*140));
            }else{
                $(this).removeClass('on');
            };

        });

        //点击清空val的值
        $(".dele-date-btn").on('click',function() {
            $(".ul_width .label").each(function() {
                var index = $(".ul_width .label").index(this);
                var _Search_Data_Date = $(this).attr("data-date");
                if(_FullDate == _Search_Data_Date) {
                    $(this).addClass('on').siblings().removeClass('on');
                   $(".ul_width").css("left",-($(this).index())*140);
                }
            });
            $(".datetimepicker_mask").val("");
            $(this).hide();
        });

        //返回今日show
        $(".btn-back-today").show();
        $(".btn-back-today").on('click',function() {
            $(".ul_width .label").each(function() {
                var _BackBtn_Data_Date = $(this).attr("data-date");
                if(_FullDate == _BackBtn_Data_Date) {
                    $(".ul_width").css("left",-($(this).index())*140);
                    $(this).addClass('on').siblings().removeClass('on');
                    $(".btn-back-today").hide();
                }
            })
        });

    });

    //placeholder
    if($(".tips span").html()=="Course list"){
        $("#datetimepicker_mask").attr("placeholder","Select Search");
    }else{
        $("#datetimepicker_mask").attr("placeholder","请选择搜索");
    }


});
