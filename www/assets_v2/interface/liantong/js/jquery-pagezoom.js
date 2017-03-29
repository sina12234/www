/* 
 * 名称 ：移动端响应式框架
 * 作者 ：亮亮 
 * 版本 ：v1.0
 * 日期 ：2015.10.9
 */
function pageResponse(opt) {
    var dw = $(window).width(),
        dh = $(window).height(),
        cw = opt.width||360,
        ch = dh * cw / dw,
        sc = dw / cw;
    $(opt.selector).css({
        "width":cw+"px",
        "transform-origin":"left top 0px",
        "-ms-transform-origin":"left top 0px",
        "-webkit-transform-origin":"left top 0px",
        "-moz-transform-origin":"left top 0px",
        "-o-transform-origin":"left top 0px",
        "transform": "scale("+sc+")",
        "-ms-transform": "scale("+sc+")",
        "-webkit-transform": "scale("+sc+")",
        "-moz-transform": "scale("+sc+")",
        "-o-transform": "scale("+sc+")"
    });
}

$(document).ready(function(){
    pageResponse({
        "selector" : '.page', //模块的类名
        "width" : 720     //默认宽320px 
    });
    $(window).resize(function(){
            pageResponse({
            "selector" : '.page', //模块的类名
            "width" : 720     //默认宽320px 
        });
    });
    if(window.location.href.indexOf("/flowquery.html")==-1)
    $(".page").show();
})
/*  使用方法
 *  window.onload = window.onresize = function(){
 *      pageResponse({
 *          selector : '输入类名', //模块的类名
 *          width : '320',     //默认宽320px 
 *      })
 *   }
 */