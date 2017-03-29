/**
 * GrowthLayer
 * @authors liangchangqing
 * @date    2015-12-02 15:45:17
 * @version $1.0$
 */
(function($) {
    $.fn.GrowthLayer = function(options) {
        var settings = {
            bgclass: 'bg', //背景id或class
            types: 'five', // five|smallgrowth|biggrowth
            space: 1500, //时间间隔
            auto: true, //自动关闭
            growth: 0, //等级
            score:0,
        };
        settings = $.extend({}, settings, options);
        var $win_h = $(window).height(),
            $win_w = $(window).width();
        var $bg = '<div id="GrowthLayer" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);z-index:10000"><div style="position:fixed;z-index:1" id="gcontent"></div></div>';
        //var $contents='';
        var $conbox = $(document.body);
        var $gimg = '',
            $gimg_w = '',
            $gimg_h = '';
        $conbox.append($bg);
        switch (settings.types) {
            case 'five':
                $gimg = '<img id="gimg" src="/assets_v2/img/sign-bg.png" style="position:absolute;z-index:2;display:none">';
                $gimg2 = '<img id="gimg2" src="/assets_v2/img/bee.png" style="position:absolute;z-index:2;display:none;top:-10%;left:30%">';
                $gimg_w = 470;
                $gimg_h = 255;
                $("#gcontent").css({
                    "width": $gimg_w,
                    "height": $gimg_h,
                    "left": ($win_w - $gimg_w) / 2,
                    "top": ($win_h - $gimg_h) / 2
                });
                $("#gcontent").append($gimg);
                $("#gimg").fadeIn(100);
                $("#gcontent").append($gimg2);
                $("#gimg2").fadeIn(200);
                break;
            case 'smallgrowth':
                $gimg = '<img id="gimg" src="/assets_v2/img/upgrade-bg.png" style="position:absolute;z-index:2;display:none">';
                $gimg_w = 453;
                $gimg_h = 230;
                $("#gcontent").css({
                    "width": $gimg_w,
                    "height": $gimg_h,
                    "left": ($win_w - $gimg_w) / 2,
                    "top": ($win_h - $gimg_h) / 2
                });
                $("#gcontent").append($gimg);
                $("#gimg").fadeIn(100);
                if (settings.growth){
                    $gimg2 = '<span id="gimg2" class="level-icon'+settings.growth+'" style="position:absolute;z-index:2;display:none;bottom:40px;right:25%"></span>';
                    $("#gcontent").append($gimg2);
                    $("#gimg2").fadeIn(200);
                }
                break;
            case 'biggrowth':
                $gimg = '<img id="gimg" src="/assets_v2/img/upgrade-bg2.png" style="position:absolute;z-index:2;display:none">';
                $gimg_w = 650;
                $gimg_h = 445;
                $("#gcontent").css({
                    "width": $gimg_w,
                    "height": $gimg_h,
                    "left": ($win_w - $gimg_w) / 2,
                    "top": ($win_h - $gimg_h) / 2
                });
                $("#gcontent").append($gimg);
                $("#gimg").fadeIn(100);
                if (settings.growth){
                    $gimg2 = '<span id="gimg2" class="lvlg'+ settings.growth +'" style="position:absolute;z-index:2;bottom:10%;left:38%"></span>';
                    $("#gcontent").append($gimg2);
                }
                if (settings.score){
                    $gimg2 = '<span id="gimg3" style="position:absolute;z-index:2;bottom:0;left:60%;width:100px;height:35px;color:#875c04;font-size:18px;font-weight:bold">'+settings.score+'</span>';
                    $("#gcontent").append($gimg2);
                }
                break;
            case 'none':
                $gimg = '<img id="gimg" src="/assets_v2/img/sign-bg.png" style="position:absolute;z-index:2;display:none">';
                $gimg2 = '<img id="gimg2" src="/assets_v2/img/bee.png" style="position:absolute;z-index:2;display:none;top:-10%;left:30%">';
                $gimg_w = 470;
                $gimg_h = 255;
                $("#gcontent").css({
                    "width": $gimg_w,
                    "height": $gimg_h,
                    "left": ($win_w - $gimg_w) / 2,
                    "top": ($win_h - $gimg_h) / 2
                });
                $("#gcontent").append($gimg);
                $("#gimg").fadeIn(100);
                $("#gcontent").append($gimg2);
                $("#gimg2").fadeIn(200);
                break;
        }
        if (settings.auto) {
            setTimeout(function() {
                $("#GrowthLayer").remove();
            }, settings.space)
        }
    }

})(jQuery);
