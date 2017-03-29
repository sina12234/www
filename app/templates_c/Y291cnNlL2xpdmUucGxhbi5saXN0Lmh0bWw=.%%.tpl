<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>直播列表 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 直播列表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body class="bgf">
<?php echo tpl_function_part("/site.main.nav.live"); ?>
<script type="text/javascript" src="assets_v2/js/jquery.calendar.js"></script>
<!--直播列表 start-->
<section class="mt20">
    <div class="container">
        <div class="row">
            <div class="col-lg-16 pd0">
                <!--日历 -->
                <div id="calendar">
                <div class="curr-day col-lg-5 col-md-5 col-sm-5">
                    <div class="calendar-title">
                        <a id="month_perv" class="cWhite pdh30" href="javascript:void(0)"> < </a>
                        <span id="calendar-select-year" class="fs14"></span>年
                        <span id="select-month" class="fs14"></span>月
                        <a id="month_next" class="cWhite pdh30" href="javascript:void(0)"> > </a>
                    </div>
                    <div class="calendar-show" id="calendar-show">
                        <div class="calendar-show-base"><a href="#" class="c-fl toToday">返回今天</a><a href="#" class="c-fr calendar-yellow" id="course_num">4节课</a></div>
                        <div class="calendar-show-panel hidden-xs">31</div>
                    </div>
                </div>
                <div class="calendar-day col-lg-15 col-xs-20 col-md-15 col-sm-15">
                    <table id="calendar-table" class="col-xs-20 col-md-20 pd0">
                        <thead class="calendar-title col-xs-20 col-md-20 pd0">
                            <tr class="col-md-20 col-xs-20 pd0">
                                <td>一</td>
                                <td>二</td>
                                <td>三</td>
                                <td>四</td>
                                <td>五</td>
                                <td>六</td>
                                <td>日</td>
                            </tr>
                        </thead>
                        <tbody class="calendar-main" id="calendar-main">
                            <tr>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td><span class="calendar-this">今天</span><span class="calendar-dott"></span></td>
                                <td>10</td>
                                <td>11<span class="calendar-dott"></span></td>
                                <td>12</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <!--课程列表 -->
                <ul class="mt20 live-class-list" id="live_list_today">

                </ul>
            </div>
            <div class="col-lg-4 right-tj hidden-xs hidden-md hidden-sm">
				<div class="title-bar fs16">热门课程</div>
				<ul class="list-recommend">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["houtCourse"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["houtCourse"] as SlightPHP\Tpl::$_tpl_vars["val"]){; ?>
					<li class="col-sm-20 col-xs-10">
					<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->courseUrl; ?>" target="_blank">
						<p class="pic">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["val"]->thumb_med); ?>">
						</p>
						<div class="title fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->title; ?></div>
						<p class="cGray"><span class="c-fl">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->third_cate_name)){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->third_cate_name; ?>
							<?php }else{; ?>
							暂无
							<?php }; ?>
						|
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->attrName)){; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->attrName; ?>
						<?php }else{; ?>
						暂无
						<?php }; ?>
						</span>
						<?php /*
						<span class="c-fr">
						共<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->sectionNum; ?>章
						</span>
						*/?>
					</p>
						<p class="cGray">
							<?php if(SlightPHP\Tpl::$_tpl_vars["val"]->fee_type==0){; ?>
						<span class="c-fl cGreen">
							免费
							<?php }else{; ?>
						<span class="c-fl cRed">
							￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price/100; ?>
							<?php }; ?>
							</span><span class="c-fr">
						</span></p>
					</a>
					</li>
					<?php }; ?>
					<?php }; ?>
                </ul>
                </ul>
            </div>
        </div>
  </div>
</section>
<!--直播列表 end-->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
