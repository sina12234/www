/**
 * @authors Your Name (you@example.org)
 * @date    2015-07-07 17:41:01
 * @version $Id$
 */
jQuery.fn.watch = function( id, fn ) {
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
        if(!$(event.target).is('cite') && event.target.className != 'cite-text' && event.target.className != 'cite-icon'){
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

})
