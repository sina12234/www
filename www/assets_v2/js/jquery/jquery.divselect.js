/**
 * @authors Your Name (you@example.org)
 * @date    2015-07-07 17:41:01
 * @version $Id$
 *
 */
jQuery.fn.watch = function (id, fn) {
    return this.each(function () {
        var self = this;
        var oldVal = $(self).text();
        $(self).data(
            'watch_timer',
            setInterval(function () {
                if ($(self).text() !== oldVal) {
                    fn.call(self, id, oldVal, $(self).text());
                    oldVal = $(self).text();
                }
            }, 100)
        );

    });
    return self;
};
jQuery.fn.unwatch = function (id) {
    return this.each(function () {
        clearInterval($(this).data('watch_timer'));
    });
};
jQuery.fn.valuechange = function (fn) {
    return this.bind('valuechange', fn);
};
jQuery.event.special.valuechange = {
    setup: function () {
        var elem = jQuery(this);
        elem.watch(elem.text(), function () {
            elem.trigger('valuechange');
        });
    },
    teardown: function () {
        jQuery(this).unwatch('value');
    }
};
var $_divselect, $_thisinput, $_thisul, $_thisval, $_thisdiv, $_thisText;
jQuery.divselect = function (divselectid) {
    $(divselectid).click(function () {
        $_thisinput = $(this);
        $_thisdiv = $_thisinput.parent();
        $_thisText = $_thisdiv.find('.cite-text');
        $_thisul = $_thisdiv.find("dl");
        $_thisval = $_thisdiv.find("input");
        $_divselect = $(divselectid).parent();
        $_divselect.css("z-index", "1").find('dl').css("z-index", "1").hide();
        if ($_thisul.css("display") == "none") {
            $_thisul.slideDown("fast");
            $_thisdiv.css("z-index", "12");
            $_thisul.css("z-index", "12").on('mouseleave', function () {
                $(this).fadeOut("100");
            }).on('click', 'dd', function () {
                if($_thisval.hasClass('verify-on')){
                    $_thisdiv.removeClass('error').parent().find('.tips').remove();
                    $_thisval.removeClass('verify-on');
                }
                $(this).parent().find('dd[selected]').removeAttr('selected');
                $(this).attr('selected', 'selected');
                $_thisText.text($(this).text());
                $_thisval.val($(this).find('a').attr('selectid'));
                $_thisul.css("z-index", "1");
                $_thisdiv.css("z-index", "1");
                $_thisul.fadeOut("fast");
            });
        } else {
            $_thisul.fadeOut("fast");
            $(this).css("z-index", "1");
        }
    });
    $(divselectid).parent().on('validateSelect', function (event) {
        $_divselect = $(this);
        $_thisinput = $_divselect.find('input');
        if($_thisinput.hasClass('verify-on')){
            return false;
        }
        $_thisval = $_thisinput.val();
        if ($.trim($_thisval) == '') {
            $_thisText = $_thisinput.attr('data-error');
            $_thisdiv = '<div class="tips pl20"><span class="tips-icon"></span><span class="tips-text cRed">' + $_thisText + '</span></div>';
            $_thisinput.addClass('verify-on').parent().addClass('error');
            $_divselect.parent().append($_thisdiv).find('.tips').css({
                'display':'block',
                'padding-left':'20px'
            }).find('.tips-icon').css('visibility','visible');
        }
    });
    $(document).click(function (event) {
        if (!$(event.target).is('cite') && event.target.className != 'cite-text'  && event.target.className != 'cite-icon') {
            $(divselectid).parent().css("z-index", "1").find('dl').css("z-index", "1").hide();
        }
    });
};
$(function () {
    (function () {
        var divselect = $('.divselect');
        var _select, _selectVal, _selectText;
        divselect.each(function () {
            _select = '';
            _selectVal = '';
            _selectText = '';
            _selectVal = $(this).find('dd a').eq(0).attr('selectid');
            _selectText = $(this).find('dd a').eq(0).text();
            $(this).find('dd').each(function () {
                _select = $(this).attr('selected');
                if (_select && _select == 'selected') {
                    _selectVal = $(this).find('a').attr('selectid');
                    _selectText = $(this).find('a').text();
                    $(this).find('input').val(_selectVal);
                    $(this).find('.cite-text').text(_selectText);
                    return false;
                }
            });
            $(this).find('input').val(_selectVal);
            $(this).find('.cite-text').text(_selectText);
        });
    })();
})
