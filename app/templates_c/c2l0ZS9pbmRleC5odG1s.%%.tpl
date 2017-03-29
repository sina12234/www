<!DOCTYPE html>
<html>
<head>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - 首页 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php if(SlightPHP\Tpl::$_tpl_vars["ssl_flag"]){; ?>
<script> if (location.protocol !== "https:") location.protocol = "https:"; </script>
<?php }; ?>
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common.js'); ?>"></script>
<body class="bgf">
<?php echo tpl_function_part("/site.main.usernav.home"); ?>
<?php if(isset(SlightPHP\Tpl::$_tpl_vars["liShow"]->is_nav)&&(SlightPHP\Tpl::$_tpl_vars["liShow"]->is_nav==1)&&!empty(SlightPHP\Tpl::$_tpl_vars["orgNav"])){; ?>
<section class="fast-nav hidden-xs hidden-sm fs14">
  <div class="container">
    <div class="row">
        <i class="fast-nav-icon"></i>
		<span>快速导航</span>
	    <?php foreach(SlightPHP\Tpl::$_tpl_vars["orgNav"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"  target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->nav_name; ?></a>
		<?php }; ?>
    </div>
  </div>
</section>
<?php }; ?>
<!--banner-->
<section class="c-fl mt10 bgf">
    <div id="main" class="pos-rel">
        <div id="slide" class="slide_wrap">
            <ul class="slide_imglist conbox">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slideList"])){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["slideList"] as SlightPHP\Tpl::$_tpl_vars["k1"]=>SlightPHP\Tpl::$_tpl_vars["slide"]){; ?>
                <li class="i<?php echo SlightPHP\Tpl::$_tpl_vars["k1"]+1; ?>" style="background-color:<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>;">
                <div class="container pd0">
                  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->slide_link; ?>" target="_blank">
                      <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url); ?>" data-width="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->width; ?>" data-height="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->height; ?>">
                  </a>
                </div>
                </li>
                <?php }; ?>
                <?php }else{; ?>
                <li class="i1" target="_blank">
                    <div class="container" >
                    <a href="/">
                        <img alt="" src="<?php echo utility_cdn::img('/assets_v2/img/banner033.jpg'); ?>">
                    </a>
                    </div>
				<!--
                <div class="title">
                    <div class="title-bg">
                        <p></p>
                        <div class="title-t container">
                            欢迎入驻云课平台。
                        </div>
                    </div>
                </div>
				-->
                </li>
                <?php }; ?>
            </ul>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slideList"])){; ?>
			<div class="switcher">
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["slideList"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					<a href="#" <?php if(SlightPHP\Tpl::$_tpl_vars["k"]==0){; ?>class="cur"<?php }; ?>></a>
				<?php }; ?>
			</div>
            <?php }; ?>
        </div>
        <div class="login-index hidden-xs hidden-sm" id="indexuser">
        </div>
    </div>
</section>
<!--即将直播-->
<section id="living" class="c-fl bgf pb30"></section>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["liShow"]->living_show)&&SlightPHP\Tpl::$_tpl_vars["liShow"]->living_show==1){; ?>
<script>
	$("#living").load("/site.main.living");
</script>
<?php }; ?>
<!--课程模版-->
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["templates"])){; ?>
<?php foreach(SlightPHP\Tpl::$_tpl_vars["templates"] as SlightPHP\Tpl::$_tpl_vars["tv"]){; ?>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
<section class="index-section">
    <div class="container">
        <div class="box-title mb10">
            <div class="box-title-left hidden-xs"></div>
            <div class="box-title-name fs24"><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?></div>
            <div class="box-title-right hidden-xs"></div>
            <p class="clearfix"></p>
            <p class="square-red"><i></i></p>
        </div>
        <ul class="row course-list">
           <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
           <?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->courses as SlightPHP\Tpl::$_tpl_vars["tcv"]){; ?>
		   <?php if(isset(SlightPHP\Tpl::$_tpl_vars["tcv"]->diplayStatus)&&SlightPHP\Tpl::$_tpl_vars["tcv"]->diplayStatus ==1){; ?>
            <li class="col-sm-5 col-lg-5 col-md-5 col-xs-10">
                <div class="course-item">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tcv"]->url)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->url; ?><?php }else{; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->course_id; ?><?php }; ?>" target="_blank" class="course-img">
                        <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["tcv"]->thumb_big); ?>">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->course_type==2){; ?>
                        <span class="recorded-icon"><?php echo tpl_modifier_tr('录播','course.list'); ?></span>
                        <?php }; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->course_type==3){; ?>
                        <span class="offline-icon"><?php echo tpl_modifier_tr('线下','course.list'); ?></span>
                        <?php }; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->status==2){; ?>
                        <span class="living-icon"><?php echo tpl_modifier_tr('正在上课','course.list'); ?></span>
                        <?php }; ?>
                    </a>
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tcv"]->url)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->url; ?><?php }else{; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->course_id; ?><?php }; ?>" target="_blank" class="course-tit fs14"><p><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->title; ?></p></a>
					<!-- 课程分类-章数-->
                    <div class="course-status clear">
                        <div class="course-status-fit">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tcv"]->third_cate_name)){; ?>
                                <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["tcv"]->third_cate_name,'course.list'); ?>&nbsp;
                            <?php }; ?>
                                |
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["tcv"]->course_id])){; ?>
								<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["tcv"]->course_id],'course.list'); ?>
							<?php }; ?>
                        </div>
                        <div class="course-status-step">
                            <?php /*<span class="cGray">共<?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->sectionNum; ?>章</span>*/?>
                        </div>
                    </div>
                    <!--/课程分类-章数 -->
                    <!-- 价格-报名状况 -->
                    <div class="course-count clear">
                        <div class="course-price">
							<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->fee_type==0&&isset(SlightPHP\Tpl::$_tpl_vars["tcv"]->is_promote)&&SlightPHP\Tpl::$_tpl_vars["tcv"]->is_promote==0){; ?>
							<span class="cGreen"><?php echo tpl_modifier_tr('免费','course.list'); ?></span>
							<?php }elseif( isset(SlightPHP\Tpl::$_tpl_vars["tcv"]->fee->price)){; ?>
                            <span class="cRed">
								￥<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->fee->price%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->fee->price/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["tcv"]->fee->price/100,2); ?><?php }; ?>
							</span>
							<?php }else{; ?>
							<span class="cRed">
								<?php if(empty(SlightPHP\Tpl::$_tpl_vars["tcv"]->price)){; ?>
								<span class="cGreen"><?php echo tpl_modifier_tr('免费','course.list'); ?></span>
								<?php }else{; ?>
								￥<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->price%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->price/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["tcv"]->price/100,2); ?><?php }; ?>
								<?php }; ?>

							</span>
							<?php }; ?>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->try==1 && SlightPHP\Tpl::$_tpl_vars["tcv"]->status!=2){; ?>
                            <span class="i-course-status">可试看</span>
                        <?php }; ?>
                        <div class="course-num" style="display:none;">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==1){; ?>
                            <span class="num-icon"></span>
                            <?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total; ?><?php echo tpl_modifier_tr(' ','course.list'); ?>
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==2){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->max_user-SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total>5){; ?><span class="g-icon8"></span><?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->max_user-SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total>0&&SlightPHP\Tpl::$_tpl_vars["tcv"]->max_user-SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total<=5){; ?><font color='#009900' class="ter"><?php echo tpl_modifier_tr('剩余','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->max_user - SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total; ?><?php echo tpl_modifier_tr('个名额','course.list'); ?></font><?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->max_user-SlightPHP\Tpl::$_tpl_vars["tcv"]->user_total<=0){; ?><font class="ter cYellow"><?php echo tpl_modifier_tr('已报满','course.list'); ?></font><?php }; ?>
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["org_hot_type"]->hot_type==3){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tcv"]->vv >=10){; ?><span class="g-icon11 c-fl"></span><?php echo SlightPHP\Tpl::$_tpl_vars["tcv"]->vv; ?><?php echo tpl_modifier_tr('次','course.list'); ?><?php }; ?>
							<?php }; ?>
                        </div>
                    <!-- /价格-报名状况 -->
                    </div>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tcv"]->resellTips)&&SlightPHP\Tpl::$_tpl_vars["tcv"]->resellTips==1){; ?>
						<div class="tuiguang-icon">
						</div>
					<?php }; ?>
            </li>
            <?php }; ?>
			<?php }; ?>

			<?php }else{; ?>

            <?php }; ?>

            </div>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend)&&SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->set_url)){; ?>
				<p class="tac"><a class="index-more" target="_blank" href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->set_url)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->set_url; ?><?php }; ?>">更多<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>&gt;&gt;</a></p>
				<?php }; ?>
			<?php }else{; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)&&count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)>=4){; ?>
				<p class="tac"><a class="index-more" target="_blank" href="/course.list<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query)){; ?>?<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->query; ?><?php }; ?>">更多<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>&gt;&gt;</a></p>
				<?php }; ?>
			<?php }; ?>
            </li>
            <?php }; ?>
        </ul>
        </ul>
        </div>
		<?php if(empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
			<div class="container">
				<div class="row">
					<div class="seize-seat">
						<ul class="seat-list">
							<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10 seat-list-item">
								<div class="seat-li-list">暂无课程</div>
							</li>
							<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10 seat-list-item">
								<div class="seat-li-list">暂无课程</div>
							</li>
							<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10 seat-list-item">
								<div class="seat-li-list">暂无课程</div>
							</li>
							<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10 seat-list-item">
								<div class="seat-li-list">暂无课程</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<?php }; ?>
</section>
<?php }; ?>
<?php }; ?>

<!--明星老师-->
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"])){; ?>
<section class="index-section">
    <div class="container">
        <div class="row">
            <div class="box-title">
                <p class="tac fs24"><span class="cYellow"><?php echo tpl_modifier_tr('推荐','site.index'); ?></span><?php echo tpl_modifier_tr('老师展示','site.index'); ?></p>
                <div class="box-title-left hidden-xs"></div>
                <div class="box-title-name fs18"><?php echo tpl_modifier_tr('强大的师资力量，让你离梦想更进一步','site.index'); ?></div>
                <div class="box-title-right hidden-xs"></div>
                <p class="clearfix"></p>
            </div>
        </div>
        <div class="row">
            <div class="c-fl mt10 u-lector-box col-md-20 pd0 mb10 swiper-container-horizontal swiper-container4" style="float:left;width:100%">
                <ul class="swiper-wrapper" id="teacnav">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["teachers"] as SlightPHP\Tpl::$_tpl_vars["teacher"]){; ?>
                <li class="col-sm-10 col-md-4 col-xs-10 lector-list swiper-slide">
                        <span class="u-lector">
                            <div class="pic u-lector-pic">
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]['thumb_big'])){; ?>
                                <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['user_id']; ?>" target="_blank"><img class="imgPic" src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["teacher"]['thumb_big']); ?>"></a><?php }else{; ?>
                                <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['user_id']; ?>" target="_blank"><img class="imgPic" src="<?php echo utility_cdn::img('/assets_v2/img/defaultPhoto.gif'); ?>"></a> <?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]['planid'])){; ?>
								<span class="video-box visible-lg" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['planid']; ?>">
									<span class="pos-rel index-teac-vio"></span>
									<span class="t-video-info">视频简介</span>
									<span class="t-video-icon"></span>
								</span>
							<?php }; ?>
							</div>
							<a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['user_id']; ?>" target="_blank">
								<div class="u-name fs18"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]['real_name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['real_name']; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['name']; ?><?php }; ?></div>
								<div class="u-title"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]['title'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['title']; ?><?php }else{; ?>TA还没有头衔<?php }; ?></div>
								<div class="college fs12"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]['desc'])){; ?><?php if(mb_strlen(SlightPHP\Tpl::$_tpl_vars["teacher"]['desc'],'utf-8')>50){; ?><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["teacher"]['desc'],0,30,'utf-8'); ?>...<?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]['desc']; ?><?php }; ?><?php }else{; ?>还未完善简介哦!<?php }; ?></div>
							</a>
						</span>
                </li>
                <?php }; ?>
            </ul>
            </div>
            </div>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"]) && count(SlightPHP\Tpl::$_tpl_vars["teachers"])>3){; ?>
    <p class="tac"><a class="index-more" target="_blank" href="/teacher.list"><?php echo tpl_modifier_tr('更多老师','site.index'); ?>>></a> <?php }; ?>
    </div>
</section>
<?php }; ?>
<!--资讯-->
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)){; ?>
<section class="index-section hidden-sm">
    <div class="container">
        <div class="row">
            <div class="box-title">
                <div class="box-title-left hidden-xs"></div>
                <div class="box-title-name fs24"><?php echo tpl_modifier_tr('学习资讯','site.index'); ?></div>
                <div class="box-title-right hidden-xs"></div>
                <p class="clearfix"></p>
                <p class="square-red"></p>
            </div>
        </div>
        <div class="row mt10">
            <ul class="newslist-img pdl0 <?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>4){; ?>col-md-10<?php }else{; ?>col-md-20<?php }; ?>">
                <?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)<=4){; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0])){; ?>
                <li class="<?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)=='1'){; ?>col-md-20 col-xs-20<?php }elseif((count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>1 and  count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data) <=4)){; ?>col-md-10<?php }; ?>">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20 col-xs-13<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">1</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_title; ?></span> </span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1])){; ?>
                <li class="<?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)=='1'){; ?>col-md-20<?php }elseif((count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>1 and  count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data) <=4)){; ?>col-md-10<?php }; ?>">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20 col-xs-13<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">2</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_title; ?></span></span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2])){; ?>
                <li class="<?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)=='1'){; ?>col-md-20<?php }elseif((count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>1 and  count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data) <=4)){; ?>col-md-10<?php }; ?>">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20 col-xs-13<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">3</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->notice_title; ?></span> </span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[2]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3])){; ?>
                <li class="col-md-10">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">4</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->notice_title; ?></span> </span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[3]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
				<?php }else{; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0])){; ?>
                <li class="col-md-20 col-xs-20">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20 col-xs-13<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">1</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->notice_title; ?></span> </span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[0]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1])){; ?>
                <li class="col-md-20 col-xs-20">
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->pk_notice_id; ?><?php }; ?>" target="_blank">
                        <div class="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb)){; ?>col-md-14 col-xs-13<?php }else{; ?>col-md-20 col-xs-13<?php }; ?>">
                            <span class="news-title fs16"><i class="dott-icon">2</i> <span><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->notice_title; ?></span> </span>
                            <div class="news-intro"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->sub_content; ?></div>
                            <span class="news-date"><?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->update_time; ?></span>
                        </div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb)){; ?>
                        <p class="news-img col-md-5 col-xs-7"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->data[1]->thumb; ?>"></p>
                        <?php }; ?>
                    </a>
                </li>
                <?php }; ?>
				<?php }; ?>
            </ul>
            <?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>4){; ?>
            <div class="newslist-txt col-md-10 fs14 hidden-xs">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data as SlightPHP\Tpl::$_tpl_vars["nlk"]=>SlightPHP\Tpl::$_tpl_vars["nlv"]){; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["nlk"]>1){; ?>
                <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["nlv"]->notice_link)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["nlv"]->notice_link; ?><?php }else{; ?>/activity.main.info/<?php echo SlightPHP\Tpl::$_tpl_vars["nlv"]->pk_notice_id; ?><?php }; ?>" target="_blank">
                    <i class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["nlk"]+1; ?></i><p class="col-md-12 length_sl"><?php echo SlightPHP\Tpl::$_tpl_vars["nlv"]->notice_title; ?></p>
                    <span class="c-fr cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["nlv"]->update_time; ?></span>
                </a>
                <?php }; ?>
                <?php }; ?>
            </div>
            <?php }; ?>
        </div>
        <?php if(count(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)>7){; ?>
        <p class="tac"><a class="index-more" target="_blank" href="/activity.main.list"><?php echo tpl_modifier_tr('更多资讯','site.index'); ?>>></a></p>
        <?php }; ?>
    </div>
</section>
<?php }; ?>
<?php if(empty(SlightPHP\Tpl::$_tpl_vars["planList"])&&empty(SlightPHP\Tpl::$_tpl_vars["templates"])&&empty(SlightPHP\Tpl::$_tpl_vars["mfclist"]->data)&&empty(SlightPHP\Tpl::$_tpl_vars["teachers"])){; ?>
<section>
    <div class="container new-load">
        <p><img src="<?php echo utility_cdn::img('/assets_v2/img/gn-load-img.jpg'); ?>" alt="" />正在准备建设中...  敬请期待</p>
    </div>
</section>
<?php }; ?>
<div id="rightWindow"></div>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.xslider.js'); ?>"></script>
<script>$("#rightWindow").load("/org.rightWindow.rightWindownew");</script>
<script>
$(".cont_tab").tab();
$(function(){
    $("#indexuser").load("/site.main.userinfo");
    // banner 图自适应
    var window_width = $(window).width();
    function setSlideImg(w,o){
        var parent = $(o).closest('a');
        if(w > 890){
            if(window_width > 1920){
                parent.width(1920);
            }else if(window_width > 1182){
                parent.css({
                    'width': '1920px',
                    'margin-left': - (w - 1185) / 2
                });
            }else{
                parent.css({
                    'width': w * 5 / 12,
                    'margin-left': -(w * 5 / 12 - window_width) / 2
                })
            }
        }
    }
    $('#slide li img').each(function(){
        var o = this;
        var src = o.src;
        var img =  new Image();
        img.src = src;
        if(img.complete){
            setSlideImg(img.width,o);
        }else{
            img.onload = function(){
                setSlideImg(img.width,o);
                img.onload = null;
            }
        }
    });
})
// 焦点图片淡隐淡现
$("#slide").Xslider({
    affect: 'fade',
    ctag: 'li', //内容标签 默认为<a>
    speed: 800, //动画速度
    space: 3000, //时间间隔
    auto: true, //自动滚动
    trigger: 'mouseover', //触发事件 注意用mouseover代替hover
    conbox: '.conbox', //内容容器id或class
    switcher: '.switcher', //切换触发器id或class
    stag: 'a', //切换器标签 默认为a
    current: 'cur', //当前切换器样式名称
    rand: false //是否随机指定默认幻灯图片
});
if($(".slide_imglist li").length<=1) {
    $(".switcher").hide();
}
;(function() {
    var window_w = $(window).width();
    var li_width = 0;
    var l_index;
    var live_num;
    if ($(window).width() < 768) {
        //获取对应class的位置
        var nIndex=$(".r_ul").find(".live-icon1").parents(".o_list").last().index();
        if(nIndex<0){
            nIndex=$(".r_ul").find(".live-icon2").parents(".o_list").last().index();
            if(nIndex<0){
                nIndex=$(".r_ul").find(".live-icon6").parents(".o_list").last().index();
                if(nIndex<0){
                    nIndex=0;
                }
            }
        };

        //移动端滑动
        var mySwiper=new Swiper('.swiper-container', {
        //  autoplay: 5000,//可选选项，自动滑动
            initialSlide :nIndex,
        });
    } else {
        $(".R_box1").carousel();//即将直播
        $(".R_box2").carousel({
            R_li:'.o_list1',//需要滚动的元素
            R_ul:'.r_ul1',//滚动区域
            prev:'.prev1',//点击向上翻转箭头
            next:'.next1',//点击向下翻转箭头
        }) //精彩回放
    }
})()
;(function() {
    if ($(window).width() < 768) {
        var mySwiper = new Swiper('.swiper-container4',{
        slidesPerView : 2,});
        var li_width = 0;
        li_width=$("#teacnav li").outerWidth();
        var li_index=$("#teacnav li").length;
        $("#teacnav").css('width',li_width*li_index);
    }
})()
//教师简介
$(function() {
    var planId;
	$('.video-box').click(function(){
        planId=$(this).attr('data-planid');
		layer.open({
			  type: 2,
			  title:false,
			  shadeClose: true,
			  area: ['710px', '399px'],
			  content:'/user.teacher.TeacherVideoPreview#'+planId  //iframe的url
		});
	})
});
(function() {
    $('.course-num').each(function() {
        var num = parseInt($(this).text());
        if(num != 0) {
            $(this).show();
        }
    })
})();
</script>
