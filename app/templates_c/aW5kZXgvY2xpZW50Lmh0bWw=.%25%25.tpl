<!DOCTYPE html>
<html>
<head>
<title>云课 - 客户端下载 - 专业的在线学习平台</title>
<meta name="title" content="云课 - 企业申请入住 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content=" 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/jquery.fullPage.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.fullPage.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-ui.min.js'); ?>"></script>
<style>
#fp-nav ul li a.active span, .fp-slidesNav ul li a.active span{ background: #f26432;border: 1px solid #fff; width: 16px;height: 16px;}
#fp-nav ul li a span, .fp-slidesNav ul li a span{ width: 16px;height: 16px;border: 1px solid #666;}
#fp-nav ul li, .fp-slidesNav ul li{ height: 18px;}
</style>
</head>
<body>
<div id="fullpage">
    <div class="section">
        <?php echo tpl_function_part("/index.main.top"); ?>
        <?php echo tpl_function_part("/index.main.nav.pc"); ?>
        <div class="d_container client_m1">
            <div class="container m1_cont">
                <div class="row">
                    <div class="col-md-20">
                        <div class="col-md-10 m1_cont_l">
                            <img class="m1bg_pic" src="<?php echo utility_cdn::img('/assets_v2/img/main1_left_pic.png'); ?>" alt="精品课程 明星老师在线课堂 等你来"/>
                            <div class=" iph_and mt20">
                                <div class="download  ">
                                    <span class="fl">
                                        <a href="https://itunes.apple.com/cn/app/yun-ke/id1071519213?l=zh&ls=1&mt=8" class="ios"></a>
                                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["down"]; ?>" class="android"></a>
                                    </span>
                                    <span class="fl ml10">
                                        <img class="hidden-xs" src="<?php echo utility_cdn::img('/assets_v2/img/app_qrcode.jpg'); ?>" alt="二维码" width="100" height="100"/>
                                        <p class="tec hidden-xs" style="color: #fff;">扫码直接下载</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 m1_cont_r">
                            <img class="m1bg_pic" src="<?php echo utility_cdn::img('/assets_v2/img/main1_right_pic.png'); ?>" alt="云课教师助手上课的好帮手"/>
                            <div class="downL mt20">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["host"]; ?>"><img src="<?php echo utility_cdn::img('/assets_v2/img/download_p.png'); ?>"></a>
                                <p class="mt10">仅支持win7、win8系统</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
    <!--main2-->
    <div class="section">
        <div class="client_m2">
            <div class="container m2_cont">
                <div class="row">
                    <div class="col-md-20">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/m2_main.png'); ?>" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--main3-->
    <div class="section">
        <div class="client_m3">
            <div class="container m3_cont">
                <div class="row">
                    <div class="col-md-20">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/m3_main.png'); ?>" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--main3-->
    <div class="section">
        <div class="client_m4">
            <div class="container m4_cont">
                <div class="row">
                    <div class="col-md-20">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/m4_main.png'); ?>" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--main5-->
    <div class="section">
        <div class="client_m5">
            <div class="container m5_cont">
                <div class="row">
                    <div class="col-md-20">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/m5_main.png'); ?>" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>

</html>
<script>
$(function() {
    $('#fullpage').fullpage({
        'verticalCentered': false,
        'css3': true,
        'sectionsColor': ['#fff', '#00FF00'],
        anchors: ['1', '2','3','4','5'],
        'navigation': true,
        'navigationPosition': 'right',
        //'navigationTooltips': ['云课助手', '云课app', '移动在线课堂','在线答题卡','多场景上课模式'],
      //  'scrollOverflow': true,
        loopBottom:true,
        /*afterLoad: function(anchorLink, index){
            if(index != 3){
                $('#s_val').blur();
            }
        }*/
    });
    setInterval(function() {
        $.fn.fullpage.moveSlideRight();
    }, 10000);
    var pic_h=$(window).height();
    if(pic_h>670){
        $(".m1_cont .m1bg_pic").css({
            width : '100%',
            bottom:  '0'
        });
        $(".m1_cont_l .iph_and").css({
            right:'14px'
        })
    }
});
</script>
