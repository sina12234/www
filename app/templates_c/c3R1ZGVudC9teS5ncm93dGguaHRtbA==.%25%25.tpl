<!DOCTYPE html>
<html>
<head>
<title>成长首页 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 成长首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<!--header-->
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<!-- mob nav -->
			<div class="mob-nav hidden-lg hidden-md">
				<p class="swiper-wrapper" id="mob-nav">
					<a href="/student.main.growth" class="swiper-slide active">我的首页</a>
					<a href="/student.course.mycourse" class="swiper-slide">我的课程</a>
					<a href="/task.commitTask.studentTaskListShow" class="swiper-slide">我的作业</a>
					<a href="/student.order.myorder" class="swiper-slide">我的订单</a>
					<a href="/student.fav.myfav" class="swiper-slide">我的收藏</a>
					<a href="/student.discount.mydiscount" class="swiper-slide">我的优惠券</a>
				</p>
			</div>
<section class="pd30">
  <div class="container">
    <div class="row">
    <!-- leftmenu start-->
    <?php echo tpl_function_part("/user.main.menu.student.growth"); ?>
    <!-- leftmenu end -->
    <!-- 我的课表 -->
    <div class="right-main col-md-16 col-xs-20">
         <!--
            <div class="clearfix">
            <aside>
            	<div class="col-md-7 col-xs-20 gi-growth-val clearfix" id="g-growth-lt">
            		<div class="s-money-tal mt20 clearfix">
            			<span class="fs16 c-fl">我的钱包</span>
            			<div class="s-money-status c-fr fs14 tac growth-rule-tip">
            				<span class="failed-vip-icon mr5" id="s-vip-icon"></span>
            				开通会员
            				<div class="message-frame">
								<span class="arrow-up-icon">
									<i class="frame-icon"></i>
								</span>
	            				<dl class="tal">
	            					<dd>
	            						<h1 class="clearfix">
		            						<span class="c-fl fs14">数学专项学习</span>
		            						<a href="#" class="c-fr fs12">续费</a>
		            					</h1>
	            						<h1 class="fs12 cGray">有效期至2016-09-20</h1>
	            					</dd>
	            					<dd>
	            						<h1 class="clearfix">
		            						<span class="c-fl fs14">英语会员</span>
		            						<a href="#" class="c-fr fs12">重新开通</a>
	            						</h1>
	            						<h1 class="fs12 cGray">已过期</h1>
	            					</dd>
	            				</dl>
	            			</div>
            			</div>
            		</div>
            		<h1 class="fs14 cYellow mb30">
            			￥<span class="fs18">1213,123.50</span>
            		</h1>
            		<div class="mb10 clearfix">
            			<div class="s-money-status c-money-status c-fl fs14 tac">
            				充值
            			</div>
            			<span class="c-fr fs14 mt5">
            				<a href="#" class="cBlue c-fl mr20" title="">申请提现</a>
            				<em class="mr20 c-fl">|</em>
            				<a href="#" class="cBlue c-fl" title="">查看流水</a>
            			</span>
            		</div>
            	</div>
            </aside>
            <section>
            	<div class="col-md-13 col-xs-20 pr0">
			            <p class="mob-nav hidden-lg hidden-md">
			            <a href="/student.course.mycourse" class="col-xs-5 col-sm-3">我的课程</a>
			            <a href="/student.order.myorder" class="col-xs-5 hidden-xs hidden-sm hidden-md hidden-lg">我的订单</a>
			            <a href="/student.fav.myfav" class="col-xs-5 col-sm-3">我的收藏</a>
			            <a href="/student.discount.mydiscount" class="col-xs-5 col-sm-3">我的优惠券</a>
			            </p>
			            <div class="gi-growth-val mb20 clearfix" id="g-growth-rt">
							<div class="col-sm-20 col-lg-14 pl0 fs14">
								<p>
									<strong><?php echo tpl_modifier_tr('成长值','LearningCenter'); ?></strong>
									 <?php echo tpl_modifier_tr('你已经超越全国','LearningCenter'); ?>
									<span id="user_percent"><?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?></span><?php echo tpl_modifier_tr('的同学！','LearningCenter'); ?>！
								</p>
								<input type="hidden" name="user_score" id="user_score" value="<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>">
								<div class="progress-content hidden-xs mb20">
				                    <ul id="wrap-progress-parents" class="growth-lv hidden-xs clearfix" title="<?php echo tpl_modifier_tr('您当前','LearningCenter'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?> <?php echo tpl_modifier_tr('经验值','LearningCenter'); ?>" style="width:100%;">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level_info"])){; ?>
										<?php foreach(SlightPHP\Tpl::$_tpl_vars["level_info"] as SlightPHP\Tpl::$_tpl_vars["score"]=>SlightPHP\Tpl::$_tpl_vars["level"]){; ?>
										<li <?php if(SlightPHP\Tpl::$_tpl_vars["level_count"]==1){; ?> style="width:10%;" class="gv-fourli"<?php }; ?> style="width:30%;">
											<span class="val-cirl-lv1"><?php echo SlightPHP\Tpl::$_tpl_vars["score"]; ?></span>
											<span class="level-icon  <?php echo SlightPHP\Tpl::$_tpl_vars["level"]; ?>"></span>
				                            <span class="g-progress"></span>
										<input type="hidden" name="level_count" value="<?php echo SlightPHP\Tpl::$_tpl_vars["level_count"]--; ?>">
										</li>
										<?php }; ?>
										<?php }; ?>
									</ul>
			                    </div>
			                    <a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.rank" class="c-fl fs12 look-leve" target="_blank"><?php echo tpl_modifier_tr('查看排行榜','LearningCenter'); ?></a>
							</div>
							<div class="col-sm-20 fs12 col-xs-20 col-md-6 p0 col-sm-offset-5 col-md-offset-0">
								<div class="s-sign-content tac bor1px clearfix mb10">
									<h1 class="c-fl mt10 col-md-8 p0">
										<p><?php echo SlightPHP\Tpl::$_tpl_vars["curr_week"]; ?></p>
										<p><?php echo SlightPHP\Tpl::$_tpl_vars["curr_date"]; ?></p>
									</h1>
									<h1 class="c-fr fs14 col-md-12 p0" id="s-has-sign">
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>
										<a href="javascript:;" class="click-btn" id="signed"><?php echo tpl_modifier_tr('今日已签到','LearningCenter'); ?></a>
										<?php }else{; ?>
										<a href="javascript:;" class="click-btn" id="sign-audo" onclick="SignAudio()"><?php echo tpl_modifier_tr('立即签到','LearningCenter'); ?></a>
										<?php }; ?>
									</h1>
								</div>
								<div class="s-sign-growth mb10 clearfix" id="s-sign-growth">
									<span class="c-fl sign-list"></span>
									<em class="c-fl"></em>
									<span class="c-fl sign-list"></span>
									<em class="c-fl"></em>
									<span class="c-fl sign-list"></span>
									<em class="c-fl"></em>
									<span class="c-fl sign-list"></span>
									<em class="c-fl"></em>
									<span class="c-fl sign-list"></span>
								</div>
								<h1 class="clearfix">
									<span class="c-fl">
										已连签
										<em class="cYellow" id="sign-combo"><?php echo SlightPHP\Tpl::$_tpl_vars["user_combo"]; ?></em>次
									</span>
									<div class="cBlue c-fr growth-rule-tip">
										奖励规则
										<div class="message-frame cGray" >
											<span class="arrow-up-icon">
												<i class="frame-icon"></i>
											</span>
											<p>每次签到获得 2 经验值</p>
											<p>连续5天获得 7 经验值哦~</p>
										</div>
									</div>
								</h1>
								<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
		                        <em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
							</div>
						</div>
            	</div>
            </section>
            </div>-->
			<!-- 成长值 -->
            <div class="gi-growth-val mb20 clearfix">
				<div class="col-sm-20 col-lg-15 fs14">
					<p>
						<strong><?php echo tpl_modifier_tr('成长值','LearningCenter'); ?></strong><?php echo tpl_modifier_tr('还有','LearningCenter'); ?>
						<span id="cha_score"><?php echo SlightPHP\Tpl::$_tpl_vars["cha_score"]; ?></span><?php echo tpl_modifier_tr('点升级到','LearningCenter'); ?>
						<a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.rank.rule" id="levelIcon" class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["next_level"]->pk_level; ?>" target="_blank" style="float: initial;">
						</a>, <?php echo tpl_modifier_tr('超越全国','LearningCenter'); ?>
						<span id="user_percent"><?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?></span><?php echo tpl_modifier_tr('的同学！','LearningCenter'); ?>！
						<a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.rank" class="c-fr look-leve" target="_blank"><?php echo tpl_modifier_tr('查看排行榜','LearningCenter'); ?></a>
					</p>
					<input type="hidden" name="user_score" id="user_score" value="<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>">
					<div class="progress-content hidden-xs">
                    <ul  id="wrap-progress-parents" class="growth-lv hidden-xs" title="<?php echo tpl_modifier_tr('您当前','LearningCenter'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?> <?php echo tpl_modifier_tr('经验值','LearningCenter'); ?>">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level_info"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["level_info"] as SlightPHP\Tpl::$_tpl_vars["score"]=>SlightPHP\Tpl::$_tpl_vars["level"]){; ?>
						<li <?php if(SlightPHP\Tpl::$_tpl_vars["level_count"]==1){; ?>class="gv-fourli"<?php }; ?>>
							<span class="val-cirl-lv1"><?php echo SlightPHP\Tpl::$_tpl_vars["score"]; ?></span>
							<span class="level-icon  <?php echo SlightPHP\Tpl::$_tpl_vars["level"]; ?>"></span>
                            <span class="g-progress"></span>
						<input type="hidden" name="level_count" value="<?php echo SlightPHP\Tpl::$_tpl_vars["level_count"]--; ?>">
						</li>
						<?php }; ?>
						<?php }; ?>
					</ul>
                    </div>
                    <p class="clearfix"></p>
				</div>
				<div class="col-sm-20 col-xs-20 col-md-5 col-sm-offset-5 col-md-offset-0">
					<div class="gi-growth-calendar fs12">
						<h1>
							<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["curr_date"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["curr_week"]; ?></span>
							<span class="c-fr"><?php echo tpl_modifier_tr('连签','LearningCenter'); ?><i id="sign-combo"><?php echo SlightPHP\Tpl::$_tpl_vars["user_combo"]; ?></i><?php echo tpl_modifier_tr('日','LearningCenter'); ?></span>
						</h1>
						<h1 id="s-has-sign">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>
							<a href="javascript:;" id="signed"><?php echo tpl_modifier_tr('今日已签到','LearningCenter'); ?></a>
							<?php }else{; ?>
							<a href="javascript:;" id="sign-audo" onclick="SignAudio()"><?php echo tpl_modifier_tr('立即签到','LearningCenter'); ?></a>
							<?php }; ?>
						</h1>
						<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
                        <em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
					</div>
				</div>
			</div>
			<!-- /成长值 -->
			<!-- 学习统计 -->
			<div class="gi-studey-stats  mb20" style="padding:0;">
				<table>
					<tbody>
						<tr>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon1"></i>
								<?php echo tpl_modifier_tr('已报课程','LearningCenter'); ?>：<span><?php echo SlightPHP\Tpl::$_tpl_vars["user_course_count"]; ?></span><?php echo tpl_modifier_tr('个','LearningCenter'); ?>
							</td>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon2"></i>
								<?php echo tpl_modifier_tr('已学课程','LearningCenter'); ?>：<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_plan_count"]; ?></span><?php echo tpl_modifier_tr('节','LearningCenter'); ?>
							</td>
                            <td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
                                <i class="stats-icon4">
                                </i><?php echo tpl_modifier_tr('学习时长','LearningCenter'); ?>:<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span><?php echo tpl_modifier_tr('小时','LearningCenter'); ?>
                            </td>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon5"></i>
								<?php echo tpl_modifier_tr('观看视频','LearningCenter'); ?>：<span><?php echo SlightPHP\Tpl::$_tpl_vars["look_plan_count"]; ?></span><?php echo tpl_modifier_tr('次','LearningCenter'); ?>
							</td>
							<!--td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon3"></i>
								完成作业：<span>3</span>次
							</td-->
						</tr>
						<tr>
							<!--td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon4">
								</i>学习时长：<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span>小时
							</td
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5"  style="border-bottom:1px solid #ddd;border-right:1px solid #ddd;">
								<i class="stats-icon5"></i>
								观看视频：<span><?php echo SlightPHP\Tpl::$_tpl_vars["look_plan_count"]; ?></span>次
							</td>-->
							<!--td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon6"></i>
								答题21题：正确率<span>60%</span>
							</td-->
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /学习统计 -->
			<!-- 课程表 -->
			<div class="growth-course-list" id="index-growth">
				<div class="growth-course-tp">
					<span class="c-fl fs16"><?php echo tpl_modifier_tr('课程表','LearningCenter'); ?></span>
					<div class="c-fr fs14">
						<a href="/student.course.mycourse"><?php echo tpl_modifier_tr('更多','LearningCenter'); ?></a>
					</div>
				</div>
                <div class="growth-tab">
                    <div class="growth-tab-main swiper-container" id="tab_main">
                       <ul class="ul-width clearfix swiper-wrapper">
					   <?php foreach(SlightPHP\Tpl::$_tpl_vars["week_arr"] as SlightPHP\Tpl::$_tpl_vars["month"]=>SlightPHP\Tpl::$_tpl_vars["week"]){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["week"] as SlightPHP\Tpl::$_tpl_vars["wv"]){; ?>
                            <li class="swiper-slide" <?php if(SlightPHP\Tpl::$_tpl_vars["wv"]['course_count'] != 0){; ?>title="<?php echo tpl_modifier_tr('有要学习的课程','LearningCenter'); ?>"<?php }; ?>>
                            	<div <?php if(SlightPHP\Tpl::$_tpl_vars["wv"]['day'] == SlightPHP\Tpl::$_tpl_vars["curr_day"]){; ?>class="on"<?php }; ?>>
                                    <p class="week"><?php echo SlightPHP\Tpl::$_tpl_vars["wv"]['week']; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["wv"]['course_count'] != 0){; ?>
                                    <span class="red-dott"></span></p>
                                    <?php }; ?>
                                    <p class="date"><?php echo SlightPHP\Tpl::$_tpl_vars["wv"]['day']; ?></p>
                                    <input type="hidden" name="week_date" value="<?php echo SlightPHP\Tpl::$_tpl_vars["wv"]['date']; ?>">
                                </div>
                            </li>
							<?php }; ?>
						<?php }; ?>
                       </ul>
                    </div>
                </div>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["pk"]=>SlightPHP\Tpl::$_tpl_vars["plan"]){; ?>
                <ul class="col-sm-20 growth-cous-cont col-xs-20 hidden plan-list pb10" id="<?php echo SlightPHP\Tpl::$_tpl_vars["pk"]; ?>">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["plan"] as SlightPHP\Tpl::$_tpl_vars["po"]){; ?>
                    <li class="info pb10 clear">
                            <div class="pic col-sm-4 col-md-4 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_url; ?>" target="_blank">
                                <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 2 ){; ?>
                                    <span class="come-class-tip"><?php echo tpl_modifier_tr('正在上课','LearningCenter'); ?></span>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 3)){; ?>
                                <span class="lineclass-icon fs12"><?php echo tpl_modifier_tr('线下课','LearningCenter'); ?></span>
                                <?php }; ?>
                                </a>
                            </div>
                            <div class="intro col-sm-7 col-md-7 col-xs-10">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_url; ?>" target="_blank">
                                <h2 class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?></h2>
                                </a>
                                <p class="fs14"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["po"]->class_name,'site.index'); ?> &nbsp;		<?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>：
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_url; ?>" target="_blank" style="float:none;"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_name; ?></a>
                                </p>
                                <p><?php echo tpl_modifier_tr('时间','LearningCenter'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_date; ?>  <?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_hour; ?></p>
                                <p><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["po"]->section_name,'site.index'); ?></p>
                            </div>
                            <div class="col-sm-9 col-xs-10 infor-my-btn tec">
                                <?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_button; ?>
                            </div>
                        </li>
                    <?php }; ?>
                    <?php }else{; ?>
                    <div class="my-collect-no-class col-md-20 col-sm-20 col-xs-20">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" class="mt30" alt="">
                        <p ><?php echo tpl_modifier_tr('您还没有直播的课程哦','LearningCenter'); ?>~!<?php echo tpl_modifier_tr('快去','LearningCenter'); ?><a href="/site"><?php echo tpl_modifier_tr('首页','LearningCenter'); ?></a><?php echo tpl_modifier_tr('看看吧','LearningCenter'); ?></p>
                    </div>
                    <?php }; ?>
                </ul>
                <?php }; ?>
                <?php }; ?>
            </div>
			<!-- /课程表 -->
			<!-- 学习轨迹 -->
			<!--div class="leam-path bor1px col-sm-12">
				<h3 class="fs18">学习轨迹</h3>
				<dl class="fs14">
					<dt class="col-sm-2">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-3">初一数学奥数的秘密初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-5">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-2">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-3">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-5">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-2">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-3">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-5">
						第一节
						<span>第一节直播上课时长<i>1小时45</i>分钟，答对0/0题点赞4次</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-2">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-3">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-5">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-2">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-3">初一数学奥数的秘密初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-5">
						第一节
					</dd>
				</dl>
			</div-->
			<!-- /学习轨迹 -->
        </div>
		<!-- /right -->
    </div>
    </div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
	<?php echo tpl_function_part("/site.main.footer"); ?>
<!--
弹层提示
<div id="growth-layer">
	<div class="w471">
		<div class="w137"></div>
		<p class="growth-val-tp"><span><em>+</em>7</span></p>
		<p class="growth-val-ft"><span>5</span></p>
	</div>
</div>
-->
<!-- /基础设置 -->
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.growthlayer.js'); ?>"></script>
<script type="text/javascript">
function _Progress(status){
    var _ProgVal = $("#user_score").val();
    var _ProgLevel_0 = parseInt($("#wrap-progress-parents li:eq(0)").find(".val-cirl-lv1").html());//0
    var _ProgLevel_1 = parseInt($("#wrap-progress-parents li:eq(1)").find(".val-cirl-lv1").html());//31
    var _ProgLevel_2 = parseInt($("#wrap-progress-parents li:eq(2)").find(".val-cirl-lv1").html());//211
    var _ProgLevel_3 = parseInt($("#wrap-progress-parents li:eq(3)").find(".val-cirl-lv1").html());//661
    var _Status=status;
    if(_ProgVal >= _ProgLevel_0 && _ProgVal < _ProgLevel_1){
        if (_ProgLevel_0==_ProgVal){
            var Prog_Stage_0 = 0;
        }else{
            var Prog_Stage_0 = Math.round((_ProgVal-_ProgLevel_0)*134/(_ProgLevel_1-_ProgLevel_0));
        }
        if(_Status==0){
            $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width : Prog_Stage_0 }, 1000);
            $("#wrap-progress-parents li:nth-child(1)").find(".val-cirl-lv1").addClass("val-cirl");
        }else{
            $("#cha_score").html(_ProgLevel_1-_ProgVal);
            $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width : Prog_Stage_0 }, 2000);
            $("#wrap-progress-parents li:nth-child(1)").find(".val-cirl-lv1").addClass("val-cirl");
        }
    }else if(_ProgVal >= _ProgLevel_1 && _ProgVal < _ProgLevel_2) {
        if (_ProgVal==_ProgLevel_1){
            var Prog_Stage_1 = 0;
        }else{
            var Prog_Stage_1 = Math.round((_ProgVal-_ProgLevel_1)*134/(_ProgLevel_2-_ProgLevel_1));
        }
        if(_Status==0){
            $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width :"100%" }, 500,function(){
                $("#wrap-progress-parents li:nth-child(2)").find(".g-progress").animate({ width : Prog_Stage_1 }, 500);
            });
            $("#wrap-progress-parents li:nth-child(1)").find(".val-cirl-lv1").addClass("val-cirl");
            $("#wrap-progress-parents li:nth-child(2)").find(".val-cirl-lv1").addClass("val-cirl");
        }else{
            $("#cha_score").html(_ProgLevel_2-_ProgVal);
            $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width :"100%" }, 500,function(){
                $("#wrap-progress-parents li:nth-child(2)").find(".g-progress").animate({ width : Prog_Stage_1 }, 500);
            });
            $("#wrap-progress-parents li:nth-child(1)").find(".val-cirl-lv1").addClass("val-cirl");
            $("#wrap-progress-parents li:nth-child(2)").find(".val-cirl-lv1").addClass("val-cirl");
        }
    }else if(_ProgVal >= _ProgLevel_2 && _ProgVal < _ProgLevel_3) {
        if (_ProgVal==_ProgLevel_2){
            var Prog_Stage_2 = 0;
        }else{
            var Prog_Stage_2 = Math.round((_ProgVal-_ProgLevel_2)*134/(_ProgLevel_3-_ProgLevel_2));
        }
        if(_Status==0){
        $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width :"100%" }, 300,function(){
            $("#wrap-progress-parents li:nth-child(2)").find(".g-progress").animate({ width : "100%" }, 300,function(){
                $("#wrap-progress-parents li:nth-child(3)").find(".g-progress").animate({ width : Prog_Stage_2 },300);
            });
        });
    }else{
        $("#cha_score").html(_ProgLevel_3-_ProgVal);
        $("#wrap-progress-parents li:nth-child(1)").find(".g-progress").animate({ width :"100%" }, 300,function(){
            $("#wrap-progress-parents li:nth-child(2)").find(".g-progress").animate({ width : "100%" }, 300,function(){
                $("#wrap-progress-parents li:nth-child(3)").find(".g-progress").animate({ width : Prog_Stage_2 },300);
            });
        });
    }
        $("#wrap-progress-parents li:nth-child(1)").find(".val-cirl-lv1").addClass("val-cirl");
        $("#wrap-progress-parents li:nth-child(2)").find(".val-cirl-lv1").addClass("val-cirl");
        $("#wrap-progress-parents li:nth-child(3)").find(".val-cirl-lv1").addClass("val-cirl");
    }

}
$(function() {
    $("#wrap-progress-parents").click(function(){
        window.open("https://<?php echo SlightPHP\Tpl::$_tpl_vars["platform_url"]; ?>/index.rank.rule");
    })

    $('#wrap-progress-parents').find('li:last').css({
    	'width' : '26px',
    	'height': '6px'
    })
    //进度条
    _Progress(0);

//日期
	$(".ul-width li").click(function() {
		$("#tab_main li").find("div").removeClass("on");
		$(this).find("div").addClass("on");
		var checked_date = $(".on input[name=week_date]").val();
		$('#'+checked_date).removeClass('hidden').siblings('.plan-list').addClass('hidden');
	})
    var checked_date = $(".on input[name=week_date]").val();
    $('#'+checked_date).removeClass('hidden').siblings('.plan-list').addClass('hidden');
//签到
    var signClickNum = parseInt($('#sign-combo').text());

    if(signClickNum == 1) {
    	$('#s-sign-growth').find('.sign-list:eq(0)').addClass('selected');
    	$('#s-sign-growth').find('.sign-list:eq(0)').prevAll().addClass('selected');
    }else if(signClickNum == 2) {
    	$('#s-sign-growth').find('.sign-list:eq(1)').addClass('selected');
    	$('#s-sign-growth').find('.sign-list:eq(1)').prevAll().addClass('selected');
    }else if(signClickNum == 3) {
    	$('#s-sign-growth').find('.sign-list:eq(2)').addClass('selected');
    	$('#s-sign-growth').find('.sign-list:eq(2)').prevAll().addClass('selected');
    }else if(signClickNum == 4) {
    	$('#s-sign-growth').find('.sign-list:eq(3)').addClass('selected');
    	$('#s-sign-growth').find('.sign-list:eq(3)').prevAll().addClass('selected');
    }else if(signClickNum == 5) {
    	$('#s-sign-growth').find('.sign-list:eq(4)').addClass('selected');
    	$('#s-sign-growth').find('.sign-list:eq(4)').prevAll().addClass('selected');
    }else {
    	$('#s-sign-growth').find('.sign-list').removeClass('selected');
    }

    $('#g-growth-lt').css('height', $('#g-growth-rt').outerHeight());
    $('.s-money-status').hover(function() {
    	$('#s-vip-icon').removeClass('failed-vip-icon');
    	$('#s-vip-icon').addClass('vip-icon');
    	$(this).css('color', '#fe8135');
    	$(this).css('border', '1px solid #fe8135');
    	$(this).find('.message-frame').show();
    }, function() {
    	$('#s-vip-icon').addClass('failed-vip-icon');
    	$('#s-vip-icon').removeClass('vip-icon');
    	$(this).css('color', '#333');
    	$(this).css('border', '1px solid #ddd');
    	$(this).find('.message-frame').hide();
    })


});

//日期滚动
if($(window).width()<1025){
        var mySwiper = new Swiper('.swiper-container', {
        slidesPerView :6
        })
        var li_w=$(".ul-width li").outerWidth();
        var li_l=$(".ul-width li").length;
        var ul_w=li_w*li_l+40;
        $(".ul-width").css("width",ul_w+'px');
}

if($(window).width()<760){
	var mySwiper = new Swiper('.swiper-container', {
	slidesPerView :3
	//autoplay: 1000,//可选选项，自动滑动
	})
	var li_w=$(".ul-width li").outerWidth();
	var li_l=$(".ul-width li").length;
	var ul_w=li_w*li_l+40;
	$(".ul-width").css("width",ul_w+'px');

	$('#g-growth-lt,#g-growth-rt').removeClass('pr0');
}


//签到声音
var _IsPlaying = false;//检测是否正在播放
function SignAudio() {
    var _Player = document.querySelector('#sign-audio');
    if(navigator.userAgent.indexOf("MSIE")<0){
        if (_IsPlaying) {
            // 如果正在播放, 停止播放并停止读取此音乐文件
            _Player.pause();
            _Player.src = '';
        } else {
            _Player.src = '/assets_v2/js/audio/sign_audio.wav';
            _Player.play();
        }
    }

    var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->fk_user; ?>;
    $.ajax({
        type:"post",
        url: '/student.main.signAjax',
        data:{ uid:uid },
        dataType:'json',
        success:function(ret){
            if(ret.code ==0){
                if(ret.data.combo == 5){
                    $("body").GrowthLayer({
                        types:"five",// five|smallgrowth|biggrowth
                        space:3000, //时间间隔
                        auto: true, //自动关闭
                    });
                }
                if(ret.data.up_type == 1){
                    setTimeout(function(){
	                    $("body").GrowthLayer({
	                        types:"smallgrowth",
	                        space:5000,
	                        auto:true,
	                        growth:ret.data.fk_level,
	                        score:ret.data.score
	                    })
                    },3000);
            $("#levelIcon").removeAttr("class").removeAttr("class").addClass("level-icon"+(parseInt(ret.data.fk_level)+1));
            $("#levelIconLeft").removeAttr("class").removeAttr("class").addClass("level-icon"+ret.data.fk_level);

                }else if(ret.data.up_type == 2){
                    setTimeout(function(){
	                    $("body").GrowthLayer({
	                        types:"biggrowth",
	                        space:5000,
	                        auto:true,
	                        growth:ret.data.fk_level,
	                        score:ret.data.score
	                    });
	                    window.location.reload();
                	},3000)
                }
                $('#sign-combo').text(ret.data.combo);
                var signNum = ret.data.combo;

			    if(signNum == 1) {
			    	$('#s-sign-growth').find('.sign-list:eq(0)').addClass('selected');
			    	$('#s-sign-growth').find('.sign-list:eq(0)').prevAll().addClass('selected');
			    }else if(signNum == 2){
			    	$('#s-sign-growth').find('.sign-list:eq(1)').addClass('selected');
			    	$('#s-sign-growth').find('.sign-list:eq(1)').prevAll().addClass('selected');
			    }else if(signNum == 3) {
			    	$('#s-sign-growth').find('.sign-list:eq(2)').addClass('selected');
			    	$('#s-sign-growth').find('.sign-list:eq(2)').prevAll().addClass('selected');
			    }else if(signNum == 4) {
			    	$('#s-sign-growth').find('.sign-list:eq(3)').addClass('selected');
			    	$('#s-sign-growth').find('.sign-list:eq(3)').prevAll().addClass('selected');
			    }else if(signNum == 5) {
			    	$('#s-sign-growth').find('.sign-list:eq(4)').addClass('selected');
			    	$('#s-sign-growth').find('.sign-list:eq(4)').prevAll().addClass('selected');
			    }else {
			    	$('#s-sign-growth').find('.sign-list').removeClass('selected');
			    }

                $('#sign-audo').animate({ height:"58px" },function(){
					$('#user_percent').text(ret.data.percent);
                    $('#s-has-sign').append('<a href="javascript:void(0);" class="click-btn" id="signed" >'+"<?php echo tpl_modifier_tr('今日已签到','LearningCenter'); ?>"+'</a>');
                    $('#sign-audo').height("50px").hide();
                    $("#sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+'+ret.data.add_score);
                    $("#sign_add").show(function(){
                    $("#sign_add").animate({ top:"-20px",opactiy:"1" },function(){
                        $("#sign_add").hide().animate({ top:"-30px" });
                        $("#wrap-progress-parents").attr("title","<?php echo tpl_modifier_tr('您当前','LearningCenter'); ?> "+ret.data.score+" <?php echo tpl_modifier_tr('经验值','LearningCenter'); ?>");
                        $("#user_score").val(parseInt(<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>)+parseInt(ret.data.add_score));
                        _Progress(1);
                        //$("#wrap-progress-parents").attr("title","您当前<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>经验值");
                    });
                    });
                });
            }
        }
    })
}
</script>
