<!DOCTYPE html>
<html>
<head>
<title>云课 - 专业的在线直播教育学习平台 - 云课网 </title>
<meta name="title" content="云课 - 专业的在线直播教学习平台">
<meta name="keywords" content="云课 - 云课堂 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php if(SlightPHP\Tpl::$_tpl_vars["ssl_flag"]){; ?>
<script>if(location.protocol !== "https:") location.protocol = "https:"; </script>
<?php }; ?>
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common.js'); ?>"></script>
<script type="text/javascript"   src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
<script type="text/javascript">
var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<section>
<div class="container">
    <div class="logo col-sm-2 col-xs-3 col-md-5 col-lg-4">
        <a href="/" title="云课">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课" class="hidden-xs hidden-sm">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/yunkelogo.png'); ?>" alt="云课" class="hidden-md hidden-lg">
        </a>
    </div>
        <div class="so col-sm-13 col-xs-17 fs14 col-md-12 col-lg-10">
            <form id="search_form"  method="get" action="">
                <div class="divselect fs14 select_border2px">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["search_name"])){; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["search_name"]=='course'){; ?>
						<cite>课程</cite>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["search_name"] == 'teacher')){; ?>
						<cite>老师</cite>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["search_name"] == 'organization')){; ?>
                        <cite>学校</cite>
                    <?php }; ?>
					<?php }else{; ?>
						<cite>课程</cite>
					<?php }; ?>
                    <dl>
                        <dd class="se-dd"><a selectid='course' >课程</a></dd>
                        <dd class="se-dd"><a selectid='teacher'>老师</a></dd>
                        <dd class="se-dd"><a selectid='organization'>学校</a></dd>
                    </dl>
                </div>
                    <?php /* <input id='search_name' name="search_name" type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["search_name"]; ?>" />*/?>
                <div class="so-input col-sm-12 col-xs-11 col-md-13 col-lg-15">
                    <input type="text" id="s_val" autocomplete="off" name="search_field" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["search_field"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["search_field"]; ?><?php }; ?>" class="fs14 search-input"  placeholder="搜课程名称，科目试试吧">
                    <img id="s_cha" src="<?php echo utility_cdn::img('/assets_v2/img/hide.png'); ?>" alt="" />
                </div>
                <button id="search_btn"  class="col-lg-2 col-md-3 col-sm-3 col-xs-3 fs16"></button>
            </form>
        </div>
            <?php /*<div class="so-hot fs14 cGray"><a href="#">小升初</a><a href="#">物理竞赛</a><a href="#">小升初</a></div>*/?>
    <div class="tel col-sm-5 hidden-xs">
        <div class="col-sm-16 c-fr"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/yunke-tel.png'); ?>" alt="客服电话"></div>
        <div class="icon tel-icon c-fr"></div>
    </div>
</div>
</section>
<!-- nav -->
<section id="nav">
    <div class="container">
        <div class="col-sm-4 categorys cWhite hidden-xs col-md-5 col-lg-4 hidden-sm">
            <p><span class="icon list-icon fs16"></span><span class="fs16"><a href="/index.course.list">全部课程</a></span></p>
            <div class="categorys-list">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateTree"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["cateTree"] as SlightPHP\Tpl::$_tpl_vars["m"]){; ?>
                    <ul class="categorys-menu pos-rel">
                        <li class="parent-menu fs16">
                            <a style="color:#fff;" class="parent-menu-title" href="<?php echo SlightPHP\Tpl::$_tpl_vars["m"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["m"]->grade; ?></a>
                        </li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["m"]->children)){; ?>
                        <li class="parent-menu fs14">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["m"]->children as SlightPHP\Tpl::$_tpl_vars["mm"]){; ?>
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->name; ?></a>
							<?php }; ?>
						</li>
						<?php }; ?>
                    <!-- sonmenu -->
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["m"]->children)){; ?>
                        <li class="categorys-menu-detail pos-abs">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["m"]->children as SlightPHP\Tpl::$_tpl_vars["mm"]){; ?>
                            <dl class="col-sm-20 fs14 clearfix">
                                <dt class="tec col-sm-2 pd0">
                                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->url; ?>" title=""><?php echo SlightPHP\Tpl::$_tpl_vars["mm"]->name; ?></a>
                                </dt>
                                <div class="pdr0 col-sm-18">
    								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["mm"]->attr)){; ?>
    								<?php foreach(SlightPHP\Tpl::$_tpl_vars["mm"]->attr as SlightPHP\Tpl::$_tpl_vars["ak"]=>SlightPHP\Tpl::$_tpl_vars["attr"]){; ?>
    									<dd class="tec">
    										<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->url; ?>" title="<?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->name; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["attr"]->name; ?></a>
    									</dd>
    									<?php if(SlightPHP\Tpl::$_tpl_vars["mm"]->attr_count != (SlightPHP\Tpl::$_tpl_vars["ak"]+1)){; ?>
    									<dd>|</dd>
    									<?php }; ?>
    								<?php }; ?>
    								<?php }; ?>
                                </div>
                            </dl>
							<?php }; ?>
                        </li>
						<?php }; ?>
                    <!-- /sonmenu -->
                    </ul>
				    <?php }; ?>
				<?php }; ?>
            </div>
        </div>
        <div class="col-sm-20 col-xs-20 fs16 col-md-15 col-lg-16 nav swiper-container4">
            <ul id="site_nav" class="swiper-wrapper" >
                <?php echo tpl_function_part("/index/main/getplatformnav"); ?>
                <li class="visible-xs visible-sm swiper-slide"><a href="/index.course.list">全部课程</a></li>
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["wonav"]) && !empty(SlightPHP\Tpl::$_tpl_vars["wonav"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["wonav"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li class="swiper-slide"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?><?php if(SlightPHP\Tpl::$_tpl_vars["v"]->title == '直播列表'){; ?><em class="new-icon"></em><?php }; ?></a></li>
                    <?php }; ?>
                <?php }; ?>
            </ul>
        </div>
    </div>
</section>
<!-- banner -->
<section style="margin-top:-2px">
    <div id="slide" class="slide_wrap">
        <ul class="slide_imglist conbox">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["banner"])){; ?>
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["banner"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["bo"]){; ?>
            <li class="i<?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?>"><a <?php if(SlightPHP\Tpl::$_tpl_vars["bo"]->link){; ?>href="<?php echo SlightPHP\Tpl::$_tpl_vars["bo"]->link; ?>" target="_blank"<?php }else{; ?>href="javascript:;"<?php }; ?>><img alt="<?php echo SlightPHP\Tpl::$_tpl_vars["bo"]->title; ?>" data-height="360" data-width="1920" src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["bo"]->url); ?>" id="banner-1"></a></li>
			<?php }; ?>
			<?php }; ?>
            </ul>
        <div class="switcher">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["banner"])){; ?>
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["banner"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["bo"]){; ?>
            <a href="#"  <?php if(SlightPHP\Tpl::$_tpl_vars["k"]==0){; ?>class="cur"<?php }; ?>></a>
			<?php }; ?>
			<?php }; ?>
        </div>
    </div>
</section>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.xslider.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script>
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
;(function() {
    if ($(window).width() < 768) {
        var mySwiper = new Swiper('.swiper-container4',{
        slidesPerView : 4,});
        var li_width = 0;
        li_width=$("#site_nav li").outerWidth();
        var li_index=$("#site_nav li").length;
        $("#site_nav").css('width',li_width*li_index);
    }
})()
</script>
<!-- 即将直播 -->
<section class="pt40 mob-pd10" id="live-broadcast">
<div class="container">
    <div class="floor-title interval-solid"><span class="fs26 cDarkgray fl hidden-xs hidden-sm">最近直播</span>
        <!--新增-->
        <ul class="col-sm-10 fs14 grade-ul  age-list" style="width: auto;">
            <li class="curr" grade="1"><a href="javascript:;">即将开始</a></li>
            <li grade="1004"><a href="javascript:;">精彩回放</a></li>
        </ul>
        <!--新增-->
        <a href="/index.plan.list" class="c-fr fs14"><span>更多</span><span class="icon more-icon"></span></a></div>
        <div class="live-list">
        <div class="row">
			<div id="living">
			</div>
			<script>
			$("#living").load("/index.main.living");
			</script>
        </div>
        </div>
    </div>
</div>
</section>
<!--免费直播试听课-->
<section id="free-live-streaming-wrap" class="pd20 c-fl mob-pd10">
    <div class="container">
        <div class="floor-title">
            <div class="row">
                <span class="fs26 cDarkgray pl15">免费直播试听课</span>
            </div>
        </div>
                <div id="free-live-streaming">
                    <div class="row">
                    <ul class="list">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["pointPlan"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["pointPlan"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <li class="col-sm-5 col-xs-10 col-lg-4 col-md-4">
                            <div class="border">
                                <div class="pic">
                                    <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->course_thumb_med); ?>" alt="" /></p>
                                    <div class="list-shade fs14">
                                        <div class="wrap-shade"></div>
                                        <div class="list-info">
                                            <div class="pb10 list-course-name">
                                                <span class="item-class"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_name; ?></span>
                                                <span class="item-course"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_name; ?></span>
                                            </div>
                                            <div class="tea-name pb10">讲师：<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->teacher_real_name; ?></div>
                                            <div class="fs12"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" class="free-live-streaming-a">进入详情页></a>
                                        </div>
                                        </div>
                                    </div>
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==2){; ?>
                                    <div class="liveing-icon">正在上课</div>
									<?php }; ?>
                                </div>
                                <div class="title single-line"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_name; ?></div>
                                <div class="list-con fs12 pl5 pr5">
                                    <span class="list-time txt-ellipsis">
                                        <span class="icon clock-icon"></span>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->start_time; ?>
                                    </span>
									<?php if(isset(SlightPHP\Tpl::$_tpl_vars["v"]->is_point)&&SlightPHP\Tpl::$_tpl_vars["v"]->is_point ==1){; ?>
									<button class="btn gray-btn c-fr">已预约</button>
									<?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["v"]->is_point)&&SlightPHP\Tpl::$_tpl_vars["v"]->is_point ==0)){; ?>
									<button class="btn green-btn c-fr ac_point" data-fkuser="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_user; ?>" data-courseid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" data-coursename="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_name; ?>" data-starttime="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->start_time; ?>">预约试听</button>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==2){; ?>
									<button class="btn yellow-btn c-fr">立即试听</button>
									<?php }; ?>
                                     <!--<button class="btn gray-btn c-fr">已预约</button>-->
                                    <!--<button class="btn green-btn c-fr">预约试听</button>-->
                                    <!--<button class="btn yellow-btn c-fr">立即试听</button>-->
                                </div>
                            </div>
                        </li>
                        
						<?php }; ?>
						<?php }; ?>

                    </ul>
                </div>
        </div>
    </div>
</section>
<!--免费课程体验区-->
<section id="recorded-broadcast" class="pd20 c-fl mob-pd10 gray-bg">
    <div class="container">
        <div class="floor-title">
            <div class="row">
                <span class="fs26 cDarkgray pl15"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["recordName"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["recordName"]; ?><?php }; ?></span>
                <a href=" " class="c-fr fs14"><span>更多</span><span class="icon more-icon"></span></a>
            </div>
        </div>
        <div class="w">
            <div class="row">
                <ul class="list">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["rescourse"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["rescourse"] as SlightPHP\Tpl::$_tpl_vars["key"]=>SlightPHP\Tpl::$_tpl_vars["val"]){; ?>
                    <li class="col-sm-5 col-xs-10 col-lg-4 col-md-4">
                        <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->course_id; ?>" target="_blank" title="">
                            <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["val"]->thumb_med); ?>" alt=" ">
								<?php if(SlightPHP\Tpl::$_tpl_vars["val"]->try ==1){; ?>
                                <span class="linelookat-icon"></span>
								<?php }; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["val"]->course_type ==2){; ?>
                                <span class="record-icon">录播</span>
								<?php }; ?>
                                <!--<span class="lineclass-icon">线下</span>-->
                            </p>
                            <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->title; ?></span>
                            </div>
                            <div class="thumb">
                                <span class="txt-ellipsis thumb-tea-name">
                                    <em class="hidden-xs">主讲：</em>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["val"]->teacher_real_name; ?>
                                </span>
                                <span class="c-fr">共<em><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->planCount; ?></em>课时</span>
                            </div>
                        </a>
                    </li>
					<?php }; ?>
                    <?php }; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- 小学 -->
<section id="xiaoxue" class=" pd40 c-fl mob-pd10">
    <div class="container">
        <div class="floor-title">
            <div class="row">
                <div class="fs26 cDarkgray col-sm-20 col-lg-4 col-md-4">小学阶段</div>
                <div class="col-sm-20 col-lg-16 col-md-16">
                    <div class="age-list interval-solid">
                        <ul class="col-sm-10 fs14 grade-ul">
                            <li class="curr" grade="1"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["pimary_all_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pimary_all_name"]; ?><?php }; ?></a></li>
                            <li grade="1004"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["pimary4_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pimary4_name"]; ?><?php }; ?></a></li>
                            <li grade="1005"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["pimary5_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pimary5_name"]; ?><?php }; ?></a></li>
                            <li grade="1006"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["pimary6_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pimary6_name"]; ?><?php }; ?></a></li>
                        </ul>
                        <a href="/index.course.list?fc=1&sc=7" class="c-fr fs14 more-a">
                            <span class="c-fl">更多</span>
                            <span class="icon more-icon"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w">
            <div class="row">
                <div class="rank-list col-sm-4 hidden-xs hidden-sm">
                    <div class="rank-tab fs14">
                        <a href="javascript:;" grade="pimary"  pay="0" class="col-sm-10 curr">免费课排行</a>
                        <a pay="1" grade="pimary" href="javascript:;" class="col-sm-10">付费课排行</a>
                    </div>
                    <div class="rank-c">
                        <ul style="display:block;">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_pimary0'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_pimary0'] as SlightPHP\Tpl::$_tpl_vars["lk"]=>SlightPHP\Tpl::$_tpl_vars["lv"]){; ?>
                            <li>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["lv"]->course_url; ?>" target="_blank">
                                    <i><?php echo SlightPHP\Tpl::$_tpl_vars["lk"]+1; ?></i><span><?php echo SlightPHP\Tpl::$_tpl_vars["lv"]->title; ?></span>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_pimary1'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_pimary1'] as SlightPHP\Tpl::$_tpl_vars["lk"]=>SlightPHP\Tpl::$_tpl_vars["lv"]){; ?>
                            <li>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["lv"]->course_url; ?>" target="_blank">
                                    <i><?php echo SlightPHP\Tpl::$_tpl_vars["lk"]+1; ?></i><span><?php echo SlightPHP\Tpl::$_tpl_vars["lv"]->title; ?></span>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-16 col-sm-20 col-xs-20 col-md-16">
                    <div class="row">
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary_all'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary_all'] as SlightPHP\Tpl::$_tpl_vars["rk"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_pimary_all"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary_all"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary_all"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary4'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary4'] as SlightPHP\Tpl::$_tpl_vars["rk"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_pimary4"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["type_pimary4"]=='vv' && SlightPHP\Tpl::$_tpl_vars["ro"]->vv >=10)){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["type_pimary4"]=='remain_user')){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary5'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary5'] as SlightPHP\Tpl::$_tpl_vars["rk"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_pimary5"]=='user_total' && SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary5"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary5"]=='vv' && SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary6'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_pimary6'] as SlightPHP\Tpl::$_tpl_vars["rk"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_pimary6"]=='user_total' && SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary6"]=='remain_user' ){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_pimary6"]=='vv' && SlightPHP\Tpl::$_tpl_vars["ro"]->vv >=10 ){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 初中 -->
<section id="chuzhong" class="gray-bg  pd40 c-fl mob-pd10">
    <div class="container">
        <div class="floor-title">
            <div class="row">
                <div class="col-md-4 col-lg-4 fs26 cDarkgray col-sm-20">初中阶段</div>
                <div class="col-sm-20 col-lg-16 col-md-16">
                    <div class="age-list interval-solid">
                        <ul class="col-sm-16 fs14 grade-ul">
                            <li grade="2" class="curr"><a href="javascript:void(0);"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["junior_all_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["junior_all_name"]; ?><?php }; ?></a></li>
                            <li grade="2001"><a href="javascript:void(0);"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["junior1_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["junior1_name"]; ?><?php }; ?></a></li>
                            <li grade="2002"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["junior2_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["junior2_name"]; ?><?php }; ?></li>
                            <li grade="2003" class="hidden-xs"><a href="javascript:void(0);"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["junior3_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["junior3_name"]; ?><?php }; ?></a></li>
                        </ul>
                        <a href="/index.course.list?fc=1&sc=8" class="c-fr fs14 more-a">
                            <span >更多</span><span class="icon more-icon"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w">
            <div class="row">
                <div class="rank-list col-sm-4 hidden-xs hidden-sm">
                    <div class="rank-tab fs14" >
                        <a pay="0" grade="junior" href="javascript:;" class="col-sm-10 curr">免费课排行</a>
                        <a pay="1" grade="junior" href="javascript:;" class="col-sm-10">付费课排行</a>
                    </div>
                    <div class="rank-c">
                        <ul style="display:block;">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_junior0'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_junior0'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
                            <li>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_url; ?>" target="_blank">
                                    <i><?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?></i><span><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->title; ?></span>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_junior1'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['l_junior1'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
                            <li>
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_url; ?>" target="_blank">
                                    <i><?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?></i><span><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->title; ?></span>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-16 col-sm-20 col-xs-20 col-md-16">
                    <div class="row">
                        <ul class="list" >
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior_all'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior_all'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_junior_all"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior_all"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior_all"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list" >
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior1'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior1'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_junior1"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior1"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior1"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list" >
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior2'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior2'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_junior2"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior2"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior2"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list" >
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior3'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_junior3'] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["ro"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ro"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["ro"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_junior3"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["ro"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior3"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["ro"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_junior3"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["ro"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["ro"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 高中 -->
<section id="gaozhong" class=" pd40 c-fl mob-pd10">
    <div class="container">
        <div class="floor-title">
            <div class="row">
                <div class="col-md-4 col-lg-4 fs26 cDarkgray col-sm-20">高中阶段</div>
                <div class="col-sm-20 col-md-16 col-lg-16">
                    <div class="age-list interval-solid">
                        <ul class="col-sm-15 fs14 grade-ul">
                            <li grade="3" class="curr"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["senior_all_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["senior_all_name"]; ?><?php }; ?></a></li>
                            <li grade="3001"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["senior1_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["senior1_name"]; ?><?php }; ?></a></li>
                            <li grade="3002"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["senior2_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["senior2_name"]; ?><?php }; ?></a></li>
                            <li grade="3003" class="hidden-xs"><a href="javascript:;"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["senior3_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["senior3_name"]; ?><?php }; ?></a></li>
                        </ul>
                        <a href="/index.course.list?fc=1&sc=9" class="c-fr fs14 more-a">
                            <span class="grade-more">更多</span><span class="icon more-icon"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w">
            <div class="row">
                <div class="hidden-sm floor-ad col-sm-4 hidden-xs">
                    <a href="//cjxxpt.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/mischool.jpg'); ?>" alt=""></a>
                </div>
                <div class="col-sm-20 col-xs-20 col-md-16 col-lg-16">
                    <div class="row">
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior_all'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior_all'] as SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["so"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_senior_all"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["so"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior_all"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["so"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior_all"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["so"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior1'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior1'] as SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>" data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["so"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_senior1"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["so"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior1"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["so"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior1"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["so"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior2'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior2'] as SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>"  data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["so"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_senior2"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["so"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior2"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["so"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior2"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["so"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                        <ul class="list">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior3'])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["all_data"]['r_senior3'] as SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
                            <li class="col-sm-5 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->course_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
                                    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>"  data-img="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["so"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?>">
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 2){; ?>
									<span class="record-icon">录播</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["so"]->course_type == 3)){; ?>
									<span class="lineclass-icon">线下</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["so"]->try == 1){; ?>
                                    <span class="linelookat-icon"></span>
									<?php }; ?>
									<?php }; ?>
									</p>
                                    <div class="title fs14"><span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->title; ?></span>

									</div>
                                    <div class="thumb">
                                        <span class="cost">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->fee_type==0){; ?>
                                            <span class="cGreen fs14">免费</span>
                                            <?php }else{; ?>
                                            <span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->price/100; ?></span>
                                            <?php }; ?>
                                            &nbsp; | &nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["so"]->org_subname; ?></span>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type_senior3"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["so"]->user_total>=10){; ?>
                                        <div class="num"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->user_total; ?></div>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior3"]=='remain_user'){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user=='0'){; ?><div class="num"><font color='red'>已报满</font></div><?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["so"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->remain_user; ?></div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["so"]->remain_user>5){; ?><div class="num"><span class="g-icon8"></span></div><?php }; ?>
                                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_senior3"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["so"]->vv>=10){; ?>
                                        <div class="num"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["so"]->vv; ?></div>
                                        <?php }; ?>
                                    </div>
                                </a>
                            </li>
                            <?php }; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 入驻机构 -->
<section class="gray-bg c-fl">
<div class="container">
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resOrg"])){; ?>
    <div class="floor-title interval-solid">
        <span class="fs26 cDarkgray">已加入我们</span>
        <a href="/index.org.list" class="c-fr fs14"><span>更多</span><span class="icon more-icon"></span></a>
    </div>
<div class="org-list">
        <ul class="row">
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["resOrg"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
			<li class="col-sm-4 col-xs-10 col-lg-4 col-md-4">
                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->subdomain; ?>" class="org-link" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?>"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->thumb_med; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?>"></a>
            </li>
			<?php }; ?>
			<!--<li class="col-sm-4 col-xs-10 col-lg-4 col-md-4">
                <a href="//100.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="高能100"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/logo1.png'); ?>" alt="高能100"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4">
                <a href="//cjxxpt.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="长郡学习平台"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/logo4.png'); ?>" alt="长郡学习平台"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4">
                <a href="//jerry.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="杰睿教育"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/jerry-logo.jpg'); ?>" alt="杰睿教育"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4">
                <a href="//yanyuan.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="燕园教育"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/yanyuan-logo.jpg'); ?>" alt="燕园教育"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//changzhengedu.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="长征教育"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/changzhengedu-logo.png'); ?>" alt="长征教育"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//twtw.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="拓维天问"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/twtw-logo.jpg'); ?>" alt="拓维天问"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//zuowen.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="麓山妙笔"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/zuowen-logo.jpg'); ?>" alt="麓山妙笔"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//shmogu.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="蘑菇培优"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/shmogu-logo.jpg'); ?>" alt="蘑菇培优"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//cztone.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="优佳成长通"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/cztone-logo.jpg'); ?>" alt="优佳成长通"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//gaozhaobang.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="高招帮"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/gaozhaobang-logo.jpg'); ?>" alt="高招帮"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//cj.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="长郡云教育"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/cj-logo.jpg'); ?>" alt="长郡云教育"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//sxhbgz.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="陕西汉滨高级中学"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/sxhbgz-logo.jpg'); ?>" alt="陕西汉滨高级中学"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//zysz.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="遵义四中"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/zysz-logo.jpg'); ?>" alt="遵义四中"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//zzbaihe.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="白鹤小学"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/logo-baihe.png'); ?>" alt="白鹤小学"></a>
            </li>
            <li class="col-sm-4 col-xs-10 col-lg-4 col-md-4 hidden-xs">
                <a href="//sisx.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>" target="_blank" class="org-link" title="滕州四实小"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/sisx-logo.jpg'); ?>" alt="滕州四实小"></a>
            </li>-->
        </ul>
        <?php /*<div class="col-md-20 tec">
            <a href="/index.org.list" class="fs16">更多&gt;&gt;</a>
        </div>*/?>
    </div>
<?php }; ?>
</div>
</section>
<!--右侧导航-->
<article>
    <div class="Top">
    <ul>
        <li>
            <a href="#xiaoxue">小</a><div class="Top_l"><a href="#xiaoxue">小学课程</a><em></em></div>
        </li>
        <li>
            <a href="#chuzhong">初</a><div class="Top_l"><a href="#chuzhong">初中课程</a><em></em></div>
        </li>
        <li>
            <a href="#gaozhong">高</a><div class="Top_l"><a href="#gaozhong">高中课程</a><em></em></div>
        </li>
        <li class="Top_wx top_p">
            <div class="Top_l1 Top_l"><img src="<?php echo utility_cdn::img('/assets_v2/img/qrcode.jpg'); ?>"alt="二维码"  /></div>
        </li>
        <li class="">
            <a class="Top_yj top_p" href="/index.feedback"></a><div class="Top_l"><a href="/index.feedback">意见反馈</a><em></em></div>
        </li>
        <li class="Top_kf top_p" style="display: none;">
            <div class="Top_l"><a href="#">在线客服</a><em></em></div>
        </li>
        <li class="Top_pic top_p" style="display: none;">
            <div class="Top_l"><a href="javascript:;">返回顶部</a><em></em></div>
        </li>
    </ul>
    </div>
</article>
<!--关注微信号弹框-->
<section id="reservation-layer" class="pt30" style="display: none;">
    <div class="tac pl30 pr30 fs14">
        <p class="tip-title fs18 fb">恭喜您预约成功!</p>
        <p class="tip-time cRed p_start_time"></p>
        <p class="tip-name p_course_name"></p>
        <p>
            <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/qrcode.png'); ?>" />
        </p>
        <p>扫描上方微信二维码</p>
        <p>直播前<span class="cRed">15</span>分钟您会在微信上收到直播提醒和直播链接</p>
    </div>
</section>
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
<!-- footer -->
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.so.js'); ?>"></script>
<script>
function search_submit(){
    var url = '';
    var search_name  = $('cite').text();
	var oSearchVal = $("input[name='search_field']").val();
    if(search_name == '课程'){
        url = '/index.course.list';
    }else if(search_name == '老师'){
        url = '/index.teacher.list';
    }else if(search_name == '学校'){
        url = '/index.org.list';
    }
	window.location.href=url+"?search_field="+escape(oSearchVal);
}
$(function() {
	
	$('.border').on('click','.ac_point',function(){
		var fk_user 	= $(this).attr("data-fkuser");
		var course_id 	= $(this).attr("data-courseid");
		var plan_id 	= $(this).attr("data-planid");
		var course_name = $(this).attr("data-coursename");
		var start_time   = $(this).attr("data-starttime");
		if(userApi.isLogin()){
			$.post( "/index.main.AddPointPlan",{ course_id:course_id,plan_id:plan_id } ,function(r) {
				if(r.result.code ==200){
					reservationLayer(fk_user,course_id,plan_id,course_name,start_time);
				}else{
					layer.msg(r.result.msg);
					return false;
				}
			},"json");
		}else{
			if($(window).width()>800){
				w='480px';
				h='400px';
			}else{
				w='90%';
				h='400px';
			}
			layer.open({
				title:false,
				type: 2,
				area: [w,h],
				shadeClose: true,
				content: '/index.main.alertLogin'
			});
		}
	});


    var siteNav = $("#site_nav");
    var winHref = window.location.pathname;
    siteNav.find("li").each(function(){
        var _this=$(this);
        var _this_href = _this.find('a').attr("href");
        _this_href = _this_href.substr(_this_href.lastIndexOf('/'));
        if(winHref == _this_href){
            _this.addClass("curr");
        }
    });
    var window_w=$(window).width();
    if((window_w)<800){
        $(".Top").hide();
    }
    //模拟select
    $.divselect(".divselect cite");
	//鼠标滑过
	$('ul.list').hide();
	$('.container').find('ul.list:first').show();
	$('.age-list li').hover(function(){
			$(this).addClass('curr').siblings().removeClass('curr');
			$(this).parents('.container').find('ul.list:eq('+$(this).index()+')').show().siblings().hide();
            $(this).parents('.container').find('ul.list:eq('+$(this).index()+')').find('img').each(function(){
                if($(this).attr('src').indexOf('video-img.png') != -1){
                    $(this).attr('src',$(this).attr('data-img'));
                }
            });
	},function(){ });

    $('.rank-list>.rank-tab>a').click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        $(this).parents('.rank-list').find('.rank-c>ul:eq(' + $(this).index() + ')').show().siblings().hide();
    })

    $('#search_btn').click(function(){
		search_submit();
		return false;
    });

		var old_se_name = $('cite').text();
        var oSearchVal = $("input[name='search_field']").val();
        //搜索框判断有无值
        if(oSearchVal != "") {
            $(".so-input img").show();
        }else{
            $(".so-input img").hide();
        }

	$('#s_cha').click(function(){
    	$("input[name='search_field']").val('');
        $(".so-input img").hide();
		search_submit();
		return false;
    });
	$('.se-dd a').click(function(){
		var s_val = $('#s_val').val();
		var now_se_name = $(this).text();
		$('cite').text(now_se_name);
		if(now_se_name != old_se_name && s_val !=''){
			search_submit();
			return false;
		}
	});
//menu
    $(".categorys-list ul").hover(function() {
        $(this).find(".categorys-menu-detail").show();
        $(this).find(".parent-menu-title").css("color","#0477c0");
    },function(){
        $(this).find(".categorys-menu-detail").hide();
        $(this).find(".parent-menu-title").css("color","#fff");
    })

// 预约弹框
    function reservationLayer(fk_user,course_id,plan_id,course_name,start_time){
        var w,h;
        if($(window).width() > 780) {
            w='500px';
            h='450px';
        }else{
            w='90%';
            h='360px';
        }
		$(".p_start_time").html(start_time);
		$(".p_course_name").html(course_name);
        layer.open({
            type:1,
            title:false,
            scrollbar:false,
            area:[w,h],
            btn:false,
            content: $("#reservation-layer"),
            success:function(){
                $(".layui-layer-setwin").css({
                    'position': 'absolute',
                    'right': '-15px',
                    'top': '-12px'
                });
            },
            cancel:function(){
                window.location.reload();
            }
        })

    }


});

    $('.num').each(function() {
        var num = parseInt($(this).text());
        if(num == 0) {
            $(this).hide();
        }
    });
	$(".Top li").hover(
        function(){
            $(this).find("div").stop().animate({ left:-112+'px', opacity: 'show'},"normal");
        },function(){
			$(".Top li").find("div").hide().css("left",-140+'px');
	});
	$(".Top_pic").click(function(){
        $('html,body').animate({ scrollTop: '0px' },'slow');
	});
	$(window).scroll(function() {
		if ($(document).scrollTop() > 250) {
			$(".Top_pic").css("display", 'block');
		} else {
			$(".Top_pic").css("display", 'none');
		}
	});
	
</script>
