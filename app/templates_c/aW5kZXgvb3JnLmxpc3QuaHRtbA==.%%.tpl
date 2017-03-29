<!DOCTYPE html>
<html>
<head>
<title>云课 - 机构列表 - 专业的在线学习平台</title>
<meta name="title" content="云课 - 机构列表 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content=" 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>

    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>
<!-- header -->
<?php echo tpl_function_part("/index.main.top"); ?>
<?php echo tpl_function_part("/index.main.nav/organization"); ?>
<section class="p10">
    <div class="container">
        <div class="allCourse">
            <!--面包屑部分-->
            <div class="condition col-md-20 clear">
                <dl class="cond_bg col-md-20">
                    <dt>分类 :</dt>
                    <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == -1){; ?>class="curr"<?php }; ?>>全部</a></dd>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["firstCateList"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["firstCateList"] as SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
                    <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc',SlightPHP\Tpl::$_tpl_vars["fo"]->pk_cate); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == SlightPHP\Tpl::$_tpl_vars["fo"]->pk_cate){; ?>class="curr"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["fo"]->name; ?></a></dd>
                    <?php }; ?>
                    <?php }; ?>
                </dl>
            </div>
        </div>
    </div>
</section>
<!-- 列表 -->
<section>
    <div class="container">
        <div class="col-xs-20 col-sm-20 col-md-16 pd0">
               <div class="sort-row fs14">
                    <ul class="sort">
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'org:desc'){; ?>class="curr"<?php }; ?>><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','org:desc'); ?>">综合排序</a></li>
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'course_count:desc'){; ?>class="curr"<?php }; ?>><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','course_count:desc'); ?>">课程数</a></li>
                        <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort'] == 'teacher_count:desc'){; ?>class="curr"<?php }; ?>><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','teacher_count:desc'); ?>">老师数</a></li>
                    </ul>
                <?php /*    <div class="area col-sm-2 fs14">
                        <cite>城市</cite>
                        <dl>
                            <dt class="interval-solid">猜你们在：<span>北京</span></dt>
                            <dd class="dashed-solid"><span class="c-fl">A</span><p class="c-fl"><a href="#">安宁</a><a href="#">安庆</a><a href="#">安康</a><a href="#">安康</a><a href="#">安康</a><a href="#">安康</a></p></dd>
                            <dd class="dashed-solid"><span class="c-fl">B</span><p class="c-fl"><a href="#">北京</a></p></dd>
                            <dd class="dashed-solid"><span class="c-fl">C</span><p class="c-fl"><a href="#">沧州</a><a href="#">长春</a></p></dd>
                        </dl>
                    </div>*/?>
                </div>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_list"])){; ?>
                <ul class="instit-list fs14">
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["org_list"] as SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
                    <li>
                        <div class="col-sm-5 pd0">
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->show_url; ?>" target="_blank"  class="instit-pic"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vo"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["vo"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/logo.png<?php }; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->name; ?>"></a>
                        </div>
                        <div class="col-sm-15">
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->show_url; ?>" target="_blank"><p class="item fs18"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->name; ?></p></a>
							<?php if(mb_strlen(SlightPHP\Tpl::$_tpl_vars["vo"]->desc,'utf-8') > 180){; ?>
                            <p class="item-info cGray"><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["vo"]->desc,0,180,'utf-8'); ?> ...</p>
							<?php }else{; ?>
                            <p class="item-info cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->desc; ?></p>
							<?php }; ?>
                            <p class="item cGray2">
								<span class="col-sm-4 pdr0 row">教师团队：<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->show_url; ?>/teacher.list" target="_blank"><span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->visiable_teacher_count; ?></span>人</a></span>
								<span class="col-sm-5 tec row">开设课程：<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->show_url; ?>/course.list" target="_blank"><span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_count; ?></span>个</a></span>
								<span class="col-sm-13 row">培训范围：<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vo"]->orgScopesName)){; ?>【<?php echo implode('】、【',SlightPHP\Tpl::$_tpl_vars["vo"]->orgScopesName); ?>】<?php }else{; ?>未设置<?php }; ?></span>
							</p>
                        </div>
                    </li>
				<?php }; ?>
                </ul>
				<?php }; ?>
                <div class="page-list cy-page-list fs14 pd-b20" id="org_page">
        </div>
            <script>
                page("org_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
            </script>
    </div>
    <div class="right-tj col-sm-4 visible-lg">
        <div class="title-bar fs16"><span><?php if(isset(SlightPHP\Tpl::$_tpl_vars["hotrecomm_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["hotrecomm_name"]; ?><?php }; ?></span></div>
        <ul class="list-recommend">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["recomm_course"])){; ?>
        <?php foreach(SlightPHP\Tpl::$_tpl_vars["recomm_course"] as SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
            <li class="col-sm-20 col-xs-10">
                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->show_url; ?>" target="_blank">
                    <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_med); ?>" alt="">
					  <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->register == 0 && SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
							<span class="linelookat-icon"></span>
						<?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type==2){; ?>
					<span class="record-icon">录播课</span>
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
					<span class="lineclass-icon">线下课</span>
                    <?php }; ?>           
                    </p>
                    <div class="fs14">
                        <div class="title c-fl">
                            <?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>    
                        </div>
                      
                    </div>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                        <div class="thumb"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                    <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='remain_user'){; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="thumb"><font color='red'>已报满</font></div><?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?>
                        <div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?>
                        <div class="thumb"><span class="g-icon8"></span></div>
                    <?php }; ?>
                    <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_recomm"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                    <div class="thumb"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                    <?php }; ?>
                </a>
            </li>
            <?php }; ?>
        <?php }; ?>
        </ul>
    </div>
    </div>
</section>
<!-- footer -->
<?php echo tpl_function_part('/index.main.footer'); ?>
</body>
</html>
<script>
$(function() {
    $.divselect(".divselect cite,.area cite");
});
</script>
