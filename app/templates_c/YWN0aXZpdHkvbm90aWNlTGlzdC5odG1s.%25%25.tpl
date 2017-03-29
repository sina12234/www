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
<section class="pd40">
<div class="container wrap-scrollHeight">
    <div class="row">
        <div class="col-md-4 hidden-xs col-sm-4 org-left" style="padding:0;">
            <ul class="fs16 nl-notice-menu" >
                   <li <?php if(SlightPHP\Tpl::$_tpl_vars["c"]=='0'){; ?>class="curr"<?php }; ?>><a href="/activity.main.list?c=0"> <span class="nl-arrow-right"></span> 全部文章</a></li>
                   <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateList"])){; ?>
				   <?php foreach(SlightPHP\Tpl::$_tpl_vars["cateList"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                   <li <?php if(SlightPHP\Tpl::$_tpl_vars["c"]==SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate){; ?>class="curr"<?php }; ?>><a href="/activity.main.list?c=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate; ?>"> <span class="nl-arrow-right"></span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></a></li>
                   <?php }; ?>
                   <?php }; ?>
            </ul>
        </div>
        <!--right-->
        <div class="col-md-16 right-main col-sm-16 col-xs-20">
            <div class="content">
                <div class="bulletin-title" style="float:left\9;display:block\9;width:100%\9;height:45px\9">
                    <b style="font-size:18px\9;color:#ff9000\9;"> <?php echo SlightPHP\Tpl::$_tpl_vars["catename"]; ?></b>
                </div>
                <div class="nl-notice-content-list" style="float:left\9;display:block\9;width:100%\9;" id="notice-content" >
                            <ul class="nl-notice-content-ul">
							  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->data)){; ?>
							  <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"]->data as SlightPHP\Tpl::$_tpl_vars["notice"]){; ?>
                                <li class="col-lg-20 nl-list-li" style="width:100%\9;float:left\9;line-height:50px\9;" >
									<?php if(SlightPHP\Tpl::$_tpl_vars["notice"]->sort >0){; ?>
                                    <span class="nl-list-li-icon"></span>
                                    <?php }; ?>
                                    <a href="/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["notice"]->pk_notice_id; ?>" style="flaot:left\9;width:100%\9;">
                                        <p style="width:53%\9;">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["notice"]->fk_cate !='0'){; ?>【<?php echo SlightPHP\Tpl::$_tpl_vars["notice"]->name; ?>】<?php }; ?><?php echo SlightPHP\Tpl::$_tpl_vars["notice"]->notice_title; ?>
                                        </p>
                                        <span class="update_time" style="width:40%\9;text-align:right\9;float:right\9"><?php echo SlightPHP\Tpl::$_tpl_vars["notice"]->update_time; ?></span>
                                    </a>
                                </li>
                                
							<?php }; ?>
							<?php }else{; ?>
							<div class="list-tu">
							<div class="list-img">
								<img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
								<div class="list-book">
									<span>该机构还未发布文章哦~</span>
								</div>
							</div>
							</div>
							<?php }; ?>
                            </ul>
                        </div>  
            <div class="col-sm-20">
                <div class="page-list" id="pagepage">
                </div>
            </div>
            </div>
        </div>
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
</script>
