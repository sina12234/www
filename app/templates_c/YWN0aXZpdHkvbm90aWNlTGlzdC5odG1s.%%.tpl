<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>学习资讯 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav.activity"); ?>

<section class="pd20">
    <div class="container">
          <div class="row">
              <div class="activity-box col-lg-16 col-lg-offset-2 col-xs-20">
                    <div class="tab-but-switch col-lg-20 col-xs-20 swiper-container pd0">
						<ul class="swiper-wrapper" id="tab">
                          <li class="swiper-slide"><a href="/activity.main.list" <?php if(SlightPHP\Tpl::$_tpl_vars["c"]==0){; ?>class="curr"<?php }; ?>>全部资讯</a></li>
						  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateList"])){; ?>
						  <?php foreach(SlightPHP\Tpl::$_tpl_vars["cateList"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                          <li class="swiper-slide"><a href="activity.main.list?c=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["c"]==SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></a></li>
						  <?php }; ?>
                          <?php }; ?>
						</ul>
						 <?php if(SlightPHP\Tpl::$_tpl_vars["cateNum"] >5 ){; ?>
                          <p class="pos-abs table-hv">
                              <b>三</b>
							  <span class="pos-abs table-show">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateResult"])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["cateResult"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
								<a href="/activity.main.list?c=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></a>
								<?php }; ?>
								<?php }; ?>	
							  </span>
                          </p>
						 <?php }; ?>
                    </div>
                    <div class="activity-cont-list fl col-lg-20 col-xs-20" id="content">
                          <ul style="display:block">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->data)){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["list"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <div class="activity-cont col-lg-20 col-xs-20">
                                <h1>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->sort)){; ?><b>[ 置顶 ]</b><?php }; ?>
                                    <a href="/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>" target="_blank">
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->notice_title; ?></a>
                                </h1>
                                <span class="notice-icon"><a href="/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>" target="_blank"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->thumb; ?>" alt=""></a></span>
                                <a class="mt10 notice-content notice-content-text" target="_blank" href="/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>">
                                  <span class="notice-bt"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->sub_content; ?></span>
                                </a>
                                <span class="p0 mt10 fl notice-time col-lg-15"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->create_time; ?></span>
                            </div>
							<?php }; ?>
                            <?php }; ?>
                          </ul>
                    </div>
					  <?php if(empty(SlightPHP\Tpl::$_tpl_vars["list"]->data)){; ?>
						  <div class="col-lg-20 col-xs-20 col-lg-offset-2 bgf activity-empty-cont">
								<p class="cont-empty"><img src="/assets_v2/img/pet3.png"></p>
								<p>暂无资讯</p>
						  </div>
					  <?php }; ?>
              </div>
			  <div class="page-list" id="pagepage"></div>
          </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>
page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["list"]->page; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["list"]->total; ?>);
</script>
</html>
<script type="text/javascript">
$(function() {
    if($(window).width() > 768 ) {
        if($('.wrap-scrollHeight').height() < 500) {
            $('.notice-menu').css('height','700px');
            $('.content').css('height','700px');
        }else {
            return false;
        }
    }
})
$(function(){
	$('.notice-icon').each(function() {
		if($(this).find('img').attr('src') == '') {
			$(this).hide();
			$(this).parents('.activity-cont').find('.notice-content').css('width', '100%');
		}
	})
});
;(function() {
    if ($(window).width() < 768) {
        var mySwiper = new Swiper('.swiper-container',{
        slidesPerView : 3,});
        var li_width = 0;
        li_width=$("#tab li").outerWidth();
        var li_index=$("#tab li").length;
        $("#tab").css('width',li_width*li_index + 10);
    }
})()
</script>
