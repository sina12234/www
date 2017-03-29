<!DOCTYPE html>
<html>
<head>
<title>直播列表 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="云课 - 直播列表 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content=" 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<script type="text/javascript">
	var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
<style>
	.ul-date a{ display: block; float: left; }
</style>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<?php echo tpl_function_part("/index.main.nav/plan"); ?>
<!-- 直播列表 -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-20 col-xs-20 col-lg-16 col-md-20">
				<div class="time-table ">
					<div class="swiper-container" style="width: 100%; overflow: hidden;">
						<ul class="fs16 ul-date swiper-wrapper" >
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["week_arr"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["week_arr"] as SlightPHP\Tpl::$_tpl_vars["w"]){; ?>
							<a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sdate',SlightPHP\Tpl::$_tpl_vars["w"]['ydate']); ?>" class="swiper-slide">
								<li data="<?php echo SlightPHP\Tpl::$_tpl_vars["w"]['ydate']; ?>" class="<?php if(SlightPHP\Tpl::$_tpl_vars["ydate"] == SlightPHP\Tpl::$_tpl_vars["w"]['ydate']){; ?>curr<?php }; ?> "><p><?php echo SlightPHP\Tpl::$_tpl_vars["w"]['date']; ?></p><p><?php echo SlightPHP\Tpl::$_tpl_vars["w"]['week']; ?></p></li>
							</a>
							<?php }; ?>
							<?php }; ?>
						</ul>
					</div>
					<script>
					if($(window).width()<760){
						$(function(){
							var ul_w=$(".ul-date a").outerWidth()*$(".ul-date li").length+20;
							$(".ul-date").css("width",ul_w+'px');
						})
						//课表时间手机效果
						var mySwiper = new Swiper('.swiper-container',{
							slidesPerView : 3,
							centeredSlides : false,
						})
					}
					</script>
					<p class="degree fs14">
						<a data="" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',''); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == ''){; ?>class="curr"<?php }; ?>>全部</a>
						<a data="9" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',9); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 9){; ?>class="curr"<?php }; ?>>高中</a>
						<a data="8" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',8); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 8){; ?>class="curr"<?php }; ?>>初中</a>
						<a data="7" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',7); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 7){; ?>class="curr"<?php }; ?>>小学</a>
					</p>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
					<dl class="fs16" id="planList">
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
						<dd plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->plan_id; ?>" nochecked>
							<div class="line"><span class="line-icon"></span></div>
							<div class="cGray2">
								<a plan_url="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->plan_url; ?>" course_url="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_url; ?>" href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->plan_url; ?>" target="_blank">
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->start_time; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->try_video == 1){; ?>
										<span class="linelookat-icon"></span>
										<?php }; ?>
									</span>
									<span class="col-sm-3 col-lg-3 col-xs-6 col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->org_subname; ?></span>
									<span class="col-sm-13 col-lg-6 col-md-6 col-xs-10"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_name; ?>&nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->class_name; ?>
									</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->fee_type == 0){; ?>
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cGreen">免费</span>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["vo"]->fee_type == 1)){; ?>
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->price/100; ?></span>
									<?php }; ?>
									<span class="col-sm-offset-5 col-lg-offset-0 col-md-offset-0 col-sm-2 col-lg-2 col-md-2 col-xs-6 hidden-xs">
										<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->section_name; ?>
									</span>
									<span class="col-sm-3 col-xs-6 col-md-3 col-lg-2">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_name; ?><?php }; ?>
									</span>
									<span class="plan_status col-sm-3 col-lg-3 col-md-3 col-xs-8 <?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->tip_class; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->tip; ?></span>
								</a>
							</div>
							</dd>
							<?php }; ?>
						</dl>
						<?php }else{; ?>
						<div class="pos-rel status">
							<div class="pos-abs status-tips cGray2">
								<p><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/no-data.png'); ?>" alt="" width="90"></p>
								<p class="fs16">没有直播课程哦!<br>看看其他日期的课程吧！</p>
							</div>
						</div>
						<?php }; ?>
					</div>
				</div>
				<div class="right-tj col-sm-4 col-xs-20 visible-lg col-lg-4">
					<div class="title-bar fs16"><span><?php if(isset(SlightPHP\Tpl::$_tpl_vars["before_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["before_name"]; ?><?php }; ?></span></div>
					<ul class="list-recommend">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_list"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["course_list"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
						<li class="col-sm-20 col-xs-10">
						<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->show_url; ?>" target="_blank">
							<p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["co"]->thumb_sma); ?>" alt="">
							<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->try == 1){; ?>
								<span class="linelookat-icon"></span>
								<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->course_type==2){; ?>
							<span class="record-icon">录播课</span>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["co"]->course_type == 3)){; ?>
							<span class="lineclass-icon">线下课</span>
							<?php }; ?>
							</p>
							<div class="title fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?>

							</div>
							<?php if(SlightPHP\Tpl::$_tpl_vars["type_hotcomm"]=='user_total'&&SlightPHP\Tpl::$_tpl_vars["co"]->user_total>=10){; ?>
							<div class="thumb"><span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->user_total; ?></div>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_hotcomm"]=='remain_user'){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->remain_user=='0'){; ?><div class="thumb"><font color='red'>已报满</font></div><?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->remain_user>0 && SlightPHP\Tpl::$_tpl_vars["co"]->remain_user<5){; ?><div class="num">剩余<span class="num-icon icon"></span><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->remain_user; ?></div>
							<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["co"]->remain_user>5){; ?><div class="thumb"><span class="g-icon8"></span></div><?php }; ?>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["type_hotcomm"]=='vv'&&SlightPHP\Tpl::$_tpl_vars["co"]->vv>=10){; ?>
							<div class="thumb"><span class="g-icon11"></span><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->vv; ?></div>
							<?php }; ?>
						</a>
						</li>
						<?php }; ?>
						<?php }; ?>
					</ul>
				</div>
		<?php /*<div class="col-lg-16 time-table">
			<div class="content">
				<div class="pd0">
                    <!--日历 -->
                    <div id="calendar">
                        <div class="curr-day col-lg-5 col-md-5 col-sm-5">
                            <div class="calendar-title">
                                <a id="month_perv" class="cWhite pdh15" href="javascript:void(0)"> &lt; </a>
                                <span id="calendar-select-year" class="fs14">2016</span>年
                                <span id="select-month" class="fs14">6</span>月
                                <a id="month_next" class="cWhite pdh15" href="javascript:void(0)"> &gt; </a>
                            </div>
                            <div class="calendar-show" id="calendar-show">
                                <div class="calendar-show-base"><a href="#" "=" class="c-fr calendar-yellow" id="course_num">1节课</a></div>
                                <div class="calendar-show-panel hidden-xs">27</div>
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

                            <tbody class="calendar-main col-md-20 col-xs-20 pd0" id="calendar-main"><tr class="col-md-20 col-xs-20 pd0"><td class="aboluo-pervMonthDays"><a href="javascript:;" date="2016-5-30" onclick="javascript:pervA(2016,5,30,this);" style="color: rgb(191, 191, 197);"><i>30</i></a></td><td class="aboluo-pervMonthDays"><a href="javascript:;" date="2016-5-31" onclick="javascript:pervA(2016,5,31,this);" style="color: rgb(191, 191, 197);"><i>31</i></a></td><td><a href="javascript:;" date="2016-6-1" onclick="javascript:setRigth(2016,6,1);"><i>1</i></a></td><td><a href="javascript:;" date="2016-6-2" onclick="javascript:setRigth(2016,6,2);"><i>2</i></a></td><td><a href="javascript:;" date="2016-6-3" onclick="javascript:setRigth(2016,6,3);"><i>3</i></a></td><td><a href="javascript:;" date="2016-6-4" onclick="javascript:setRigth(2016,6,4);"><i>4</i></a></td><td><a href="javascript:;" date="2016-6-5" onclick="javascript:setRigth(2016,6,5);"><i>5</i></a></td></tr><tr class="col-md-20 col-xs-20 pd0"><td><a href="javascript:;" date="2016-6-6" onclick="javascript:setRigth(2016,6,6);"><i>6</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-7" onclick="javascript:setRigth(2016,6,7);"><i>7</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-8" onclick="javascript:setRigth(2016,6,8);"><i>8</i></a></td><td><a href="javascript:;" date="2016-6-9" onclick="javascript:setRigth(2016,6,9);"><i>9</i></a></td><td><a href="javascript:;" date="2016-6-10" onclick="javascript:setRigth(2016,6,10);"><i>10</i></a></td><td><a href="javascript:;" date="2016-6-11" onclick="javascript:setRigth(2016,6,11);"><i>11</i></a></td><td><a href="javascript:;" date="2016-6-12" onclick="javascript:setRigth(2016,6,12);"><i>12</i></a></td></tr><tr class="col-md-20 col-xs-20 pd0"><td><a href="javascript:;" date="2016-6-13" onclick="javascript:setRigth(2016,6,13);"><i>13</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-14" onclick="javascript:setRigth(2016,6,14);"><i>14</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-15" onclick="javascript:setRigth(2016,6,15);"><i>15</i></a></td><td><a href="javascript:;" date="2016-6-16" onclick="javascript:setRigth(2016,6,16);"><i>16</i></a></td><td><a href="javascript:;" date="2016-6-17" onclick="javascript:setRigth(2016,6,17);"><i>17</i></a></td><td><a href="javascript:;" date="2016-6-18" onclick="javascript:setRigth(2016,6,18);"><i>18</i></a></td><td><a href="javascript:;" date="2016-6-19" onclick="javascript:setRigth(2016,6,19);"><i>19</i></a></td></tr><tr class="col-md-20 col-xs-20 pd0"><td><a href="javascript:;" date="2016-6-20" onclick="javascript:setRigth(2016,6,20);"><i>20</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-21" onclick="javascript:setRigth(2016,6,21);"><i>21</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-22" onclick="javascript:setRigth(2016,6,22);"><i>22</i></a></td><td><a href="javascript:;" date="2016-6-23" onclick="javascript:setRigth(2016,6,23);"><i>23</i></a></td><td><a href="javascript:;" date="2016-6-24" onclick="javascript:setRigth(2016,6,24);"><i>24</i></a></td><td><a href="javascript:;" date="2016-6-25" onclick="javascript:setRigth(2016,6,25);"><i>25</i></a></td><td><a href="javascript:;" date="2016-6-26" onclick="javascript:setRigth(2016,6,26);"><i>26</i></a></td></tr><tr class="col-md-20 col-xs-20 pd0"><td><a href="javascript:;" date="2016-6-27" class="calendar-this" onclick="javascript:setRigth(2016,6,27);"><i>今天</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-28" onclick="javascript:setRigth(2016,6,28);"><i>28</i><span class="calendar-dott"></span></a></td><td><a href="javascript:;" date="2016-6-29" onclick="javascript:setRigth(2016,6,29);"><i>29</i></a></td><td><a href="javascript:;" date="2016-6-30" onclick="javascript:setRigth(2016,6,30);"><i>30</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-1" onclick="javascript:nextA(2016,7,1,this);" style="color: rgb(191, 191, 197);"><i>1</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-2" onclick="javascript:nextA(2016,7,2,this);" style="color: rgb(191, 191, 197);"><i>2</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-3" onclick="javascript:nextA(2016,7,3,this);" style="color: rgb(191, 191, 197);"><i>3</i></a></td></tr><tr class="col-md-20 col-xs-20 pd0"><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-4" onclick="javascript:nextA(2016,7,4,this);" style="color: rgb(191, 191, 197);"><i>4</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-5" onclick="javascript:nextA(2016,7,5,this);" style="color: rgb(191, 191, 197);"><i>5</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-6" onclick="javascript:nextA(2016,7,6,this);" style="color: rgb(191, 191, 197);"><i>6</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-7" onclick="javascript:nextA(2016,7,7,this);" style="color: rgb(191, 191, 197);"><i>7</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-8" onclick="javascript:nextA(2016,7,8,this);" style="color: rgb(191, 191, 197);"><i>8</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-9" onclick="javascript:nextA(2016,7,9,this);" style="color: rgb(191, 191, 197);"><i>9</i></a></td><td class="aboluo-nextMonthDays"><a href="javascript:;" date="2016-7-10" onclick="javascript:nextA(2016,7,10,this);" style="color: rgb(191, 191, 197);"><i>10</i></a></td></tr></tbody></table>
                        </div>
                    </div>
                     <p class="degree fs14 mt20">
						<a data="" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',''); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == ''){; ?>class="curr"<?php }; ?>>全部</a>
						<a data="9" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',9); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 9){; ?>class="curr"<?php }; ?>>高中</a>
						<a data="8" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',8); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 8){; ?>class="curr"<?php }; ?>>初中</a>
						<a data="7" href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'cate',7); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["cate"] == 7){; ?>class="curr"<?php }; ?>>小学</a></p>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
						<dl class="fs16" id="planList">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["vo"]){; ?>
							<dd plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->plan_id; ?>" nochecked>
							<div class="cGray2">
								<a plan_url="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->plan_url; ?>" course_url="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_url; ?>" href="<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_url; ?>" target="_blank">
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->start_time; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->try_video == 1){; ?>
										<i class="try-icon"></i>
										<?php }; ?>
									</span>
									<span class="col-sm-3 col-lg-3 col-xs-6 col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->org_subname; ?></span>
									<span class="col-sm-13 col-lg-6 col-md-6 col-xs-10"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->course_name; ?>&nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->class_name; ?>
									</span>
									<?php if(SlightPHP\Tpl::$_tpl_vars["vo"]->fee_type == 0){; ?>
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cGreen">免费</span>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["vo"]->fee_type == 1)){; ?>
									<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->price/100; ?></span>
									<?php }; ?>
									<span class="col-sm-offset-5 col-lg-offset-0 col-md-offset-0 col-sm-2 col-lg-2 col-md-2 col-xs-6 hidden-xs">
										<?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->section_name; ?>
									</span>
									<span class="col-sm-3 col-xs-6 col-md-3 col-lg-2">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->teacher_name; ?><?php }; ?>
									</span>
									<span class="plan_status col-sm-3 col-lg-3 col-md-3 col-xs-8 <?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->tip_class; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["vo"]->tip; ?></span>
								</a>
							</div>
							</dd>
							<?php }; ?>
						</dl>
						<?php }else{; ?>
						<div class="pos-rel status">
							<div class="pos-abs status-tips cGray2">
								<p><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/no-data.png'); ?>" alt="" width="90"></p>
								<p class="fs16">没有直播课程哦!<br>看看其他日期的课程吧！</p>
							</div>
						</div>
						<?php }; ?>
						<ul id="live_list_today" class="mt20 live-class-list"></ul>
                </div>
			</div>
		</div>*/?>

			</div>
		</div>
	</div>
</section>

<script>
	var top_nav_img="<?php echo utility_cdn::img('/assets_v2/img/qrcode.jpg'); ?>";
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/top_nav.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>

<script>
	function verifyPlan(){
		if(!userApi.isLogin()) return ;
		var plan_ids=[];
		$("dl>dd[plan_id][nochecked]").each(function(i,item){
			plan_ids.push($(item).attr("plan_id"));
			$(this).removeAttr("nochecked");
		});
		if(plan_ids.length>0){
			$.ajax({
				type:"post",
				url: '/index.plan.planAjax',
				data:{ plan_ids:plan_ids},
				dataType:'json',
				success:function(r){
					if(r){
						for(var plan_id in r){
							if(r[plan_id].ok){ //进入课堂
								$("dd[plan_id="+plan_id+"] .plan_status").html("进入课堂").addClass("enter-btn");
								$("dd[plan_id="+plan_id+"] a").attr("href", $("dd[plan_id="+plan_id+"] a").attr("plan_url"));
							}
							if(r[plan_id].reg){ //关闭试看
								$("dd[plan_id="+plan_id+"] .try-icon").hide();
							}
							if(r[plan_id].ok==false && r[plan_id].reg==false){
							}
						}
					}
				}
			});
		}
	}
	$(function() {
		verifyPlan();
		var page = 1;
		var cate = $('.degree').find('.curr').attr('data');
		var sdate = "<?php echo SlightPHP\Tpl::$_tpl_vars["ydate"]; ?>";
		var arrList = [];
		var conList = $('#planList dd:last')[0];
		var planlist_pages=<?php echo SlightPHP\Tpl::$_tpl_vars["planlist_pages"]; ?>;
		arrList.push(conList);
		$(window).scroll(function(){
			var prec = getClient();
			jiance(arrList, prec,function(){
				page++;
				if(page>planlist_pages){
					return false;
				}
				$.ajax({
					type:"post",
					url: '/index.plan.planAjax',
					data:{ cate:cate,sdate:sdate,page:page },
					dataType:'json',
					success:function(r){
						var con = '';
						var data = r.data;
						if(data.length > 0 ){
							for(var i=0; i<data.length;i++){
								con +=  '<dd plan_id="'+data[i].plan_id+'" nochecked>'+
										'<div class="line"><span class="line-icon"></span></div>'+
										'<div class="cGray2">'+
										'<a target="_blank" plan_url="'+data[i].plan_url+'" course_url="'+data[i].course_url+'" href="'+data[i].plan_url+'">'+
										'<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2">'+data[i].start_time+'</span>'+
										'<span class="col-sm-3 col-xs-6 col-lg-3 col-md-2">'+data[i].org_subname+'</span>'+
										'<span class="col-sm-13 col-xs-10 col-lg-6 col-md-6">'+data[i].course_name+'&nbsp;&nbsp;&nbsp;'+data[i].class_name;
								if(data[i].try_video == 1){
									con += '<i class="try-icon"></i>';
								}
								con += '</span>';
								if(data[i].fee_type == 0){
									con += '<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cGreen">免费</span>';
								}else if(data[i].fee_type == 1){
									con += '<span class="col-sm-2 col-xs-4 col-lg-2 col-md-2 cRed">￥'+data[i].price/100+'</span>';
								}
								var teacher_name = '';
								if( data[i].teacher_real_name ){
									teacher_name = data[i].teacher_real_name;
								}else{
									teacher_name = data[i].teacher_name;
								}
								con +=  '<span class="col-sm-offset-5 col-lg-offset-0 col-md-offset-0 col-sm-2 col-lg-2 col-md-2 col-xs-6 hidden-xs">'+data[i].section_name+'</span>'+
										'<span class="col-sm-3 col-xs-6 col-md-3 col-lg-2">'+teacher_name+'</span>'+
										'<span class="plan_status col-sm-3 col-xs-8 col-lg-3 col-md-3 '+data[i].tip_class+'">'+data[i].tip+'</span>'+
										'</a>'+
										'</div>'+
										'</dd>';
							}
							$('#planList').append(con);
							var conList = $('#planList dd:last')[0];
							arrList.push(conList);
							verifyPlan();
						}else{
							$(window).unbind('scroll');
						}
					},
					complete:function(){
					},
					error: function(r){
						page--;
						conList = $("#planList dd:last")[0];
						arrList.push(conList);
					}
				});
			});
		});
	});
</script>
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
