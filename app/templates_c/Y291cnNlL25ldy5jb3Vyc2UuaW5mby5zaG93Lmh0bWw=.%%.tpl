<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title']; ?>-<?php echo tpl_function_part('/site.main.orgname'); ?>-云课-专业的在线学习平台</title>
	<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 课程详情 - 云课 - 专业的在线学习平台">
	<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
	<meta name="description" content="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['desc'])){; ?><?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['desc'])); ?><?php }else{; ?>这节课让我收获大大的，你也来看看吧！
<?php }; ?>">
	<?php echo tpl_function_part("/site.main.header"); ?>
	<?php echo tpl_function_part("/site.main.weixin"); ?>
	<?php /*<meta name="weixin" title="" link="" imgurl="" desc=""/>*/?>
	<meta name="weixin" imgurl="<?php echo utility_cdn::http(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumb']); ?>" desc="<?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title'])); ?>"/>
	<script type="text/javascript">
		var COOKIE_UID_NAME="<?php if(!empty(COOKIE_UID_NAME)){; ?><?php echo COOKIE_UID_NAME; ?><?php }else{; ?><?php }; ?>";
	</script>
	<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
</head>
<style type="text/css">
.layui-layer-btn .layui-layer-btn1{ background: #ccc;}
.layui-layer-btn .layui-layer-btn1:hover{ background: #ccc;}
.g-icon3,.taped-icon{ width: 65px;height:28px;line-height: 28px;text-align:center;}
</style>
<body classId="<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>">
<?php echo tpl_function_part("/site.main.nav3"); ?>
<section>
	<div class="container" id="course-info-show">
		<div class="row mb20">
		<!-- 详情上 -->
			<section class="bgf clearfix mt10 mb10">
				<div class="course-info-top clearfix">
					<div class="col-md-8 col-xs-20 p0">
						<!-- mobile -->
						<div class="course-mobile-info tac hidden-lg hidden-sm hidden-md">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["try"])&&SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType']!=3 && empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
							<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["try_plan"]; ?>" class="course-audio-icon">试看课程</a>
							<?php }; ?>
							<!--
							<div class="course-chapter-name tac c-fl">
								<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
									<span class="cRed mr10">学习有效期已失效，请重新参与课程</span>
								<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
										<span class="course-being-live" style="color: #FFF">正在直播</span>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanName']; ?>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
										<span class="course-being-live" style="color: #FFF">即将直播</span>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanName']; ?>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanName']; ?>
									<?php }else{; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanName']; ?>
									<?php }; ?>
								<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanName']; ?>
									<?php }else{; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanName']; ?>
									<?php }; ?>
								<?php }else{; ?>
								<?php }; ?>
							</div>
							-->
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanName']; ?></div>
										<a class="fs16 tac s-come-btn mt20 cGreen" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId']; ?>">
											进入课堂
										</a>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanName']; ?></div>
										<a class="fs16 tac s-come-btn mt20 cGreen" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId']; ?>">
											进入课堂
										</a>
									<?php }elseif( empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])&&empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
											<div class="fs16 tac mt20">直播无回看</div>
										<?php }else{; ?>
											<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>
												<div class="fs16 tac mt20">直播无回看</div>
											<?php }else{; ?>
												<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanName']; ?></div>
												<a class="fs16 tac cGreen s-start-btn" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
													开始学习
												</a>
											<?php }; ?>
										<?php }; ?>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanName']; ?></div>
										<a class="fs16 tac cGreen s-start-btn mt20" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]; ?><?php }; ?>">
											继续学习
										</a>
									<?php }else{; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanName']; ?></div>
										<a class="fs16 tac cGreen s-start-btn mt20" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
											开始学习
										</a>
									<?php }; ?>
								<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
										<div class="tac fs16 mt20">暂无视频</div>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanName']; ?></div>
										<a class="tac fs16 cGreen s-start-btn mt20" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']]; ?>">
											继续学习
										</a>
										<?php }else{; ?>
										<div class="mb10 course-progress-info"><?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanName']; ?></div>
										<a class="tac fs16 s-start-btn cGreen mt20" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>">
											开始学习
										</a>
										<?php }; ?>
									<?php }else{; ?>
										<div class="tac fs16 mt20">暂无视频</div>
									<?php }; ?>
								<?php }else{; ?>
								<!--
								<span class="course-being-live">上次学习到</span>
								08 一元二次方程组详解
								-->
								<?php }; ?>
							<?php }; ?>
						</div>
						<!-- /mobile -->
						<img class="course-info-img" src="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumb']; ?>" alt="" />
						<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
						<span class="g-icon3"><?php echo tpl_modifier_tr('录播','course.info'); ?></span>
						<?php }; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 3){; ?>
						<span class="taped-icon"><?php echo tpl_modifier_tr('线下','course.info'); ?></span>
						<?php }; ?>
					</div>
					<div class="col-md-8 col-xs-20 p0 bort1px course-class-infos">
						<h1 class="fs20 mb15 course-info-tit"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title']; ?></h1>
						<div class="course-infos-height">
							<ul class="course-info-tp-price mb15 clearfix">
								<li class="mr20">
									<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['feeType'] or SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>0){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
												<span class="cGreen fs20">免费</span>
											<?php }else{; ?>
												<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
												<span class="cGreen fs20">免费</span>
												<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }else{; ?>
												<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }; ?>
											<?php }; ?>
										<?php }else{; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
												<span class="cGreen fs20">免费</span>
												<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">原价：￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
											<?php }else{; ?>
												<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
													<span class="cGreen fs20">免费</span>
													<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }else{; ?>
													<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
														<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
													<?php }else{; ?>
														<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
														<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
													<?php }; ?>
												<?php }; ?>
											<?php }; ?>
										<?php }; ?>
									<?php }else{; ?>
										<span class="cGreen fs20">免费</span>
									<?php }; ?>
								</li>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetList"])){; ?>
									<li>
										<div class="select-member-category">
											<span class="member-title c-fl">会员免费学</span>
											<span class="c-fl member-btn"><i class="course-member-up-icon"></i></span>
											<dl>
												<?php foreach(SlightPHP\Tpl::$_tpl_vars["memberSetList"] as SlightPHP\Tpl::$_tpl_vars["mo"]){; ?>
												<dd>
													<a href="javascript:;" class="col-md-20 col-xs-20 p0" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_id; ?>" onclick="memberOpen(this,'<?php echo SlightPHP\Tpl::$_tpl_vars["sourceSubdomain"]; ?>')">
														<span class="course-title"><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_name; ?></span>
														<span class="cRed c-fr">
															<?php if(isset(SlightPHP\Tpl::$_tpl_vars["userMemberSet"][SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_id]) && SlightPHP\Tpl::$_tpl_vars["userMemberSet"][SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_id]==0){; ?>
															立即续费>
															<?php }else{; ?>
																去购买>
															<?php }; ?>
														</span>
													</a>
												</dd>
												<?php }; ?>
											</dl>
										</div>
									</li>
								<?php }; ?>
							</ul>
							<!-- 单个班级 -->
							<?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]==1){; ?>
							<ul class="oneCourse-classInfos pos-rel">
								<li class="dGray"><span class="cGray">班级：</span><?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['className']; ?></li>
								<li class="dGray">
									<p>
										<span class="cGray">课时：</span>共<?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['planNum']; ?>课时   <?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['progress']; ?>
										<?php if(empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
											<span class="cRed"><?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['classStatus']; ?></span>
									<?php }; ?>
								</li>
								<li class="dGray" style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><span class="cGray">主讲：</span><?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['teacherName']; ?></li>
								<li class="course-address" style="display:none;"><?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["classId"]]['classAddress']; ?></li>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
								<li class="pos-abs course-buy-position hidden-xs"><i class="course-buy"></i></li>
								<?php }elseif( SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]!=1){; ?>
								<li class="pos-abs course-buy-position hidden-xs"><i class="course-buy"></i></li>
								<?php }; ?>
								<?php }; ?>
							</ul>
							<?php }; ?>
							<!-- /单个班级 -->
							<!-- 多个班级 -->
							<?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]>1){; ?>
							<ul class="sixCourse-className clearfix">
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["classList"] as SlightPHP\Tpl::$_tpl_vars["class"]){; ?>
								<li classId="<?php echo SlightPHP\Tpl::$_tpl_vars["class"]['classId']; ?>" class="<?php if(SlightPHP\Tpl::$_tpl_vars["classId"]==SlightPHP\Tpl::$_tpl_vars["class"]['classId']){; ?>curr<?php }else{; ?><?php }; ?>" onmouseover="selectClass(<?php echo SlightPHP\Tpl::$_tpl_vars["class"]['classId']; ?>,this)"><?php echo SlightPHP\Tpl::$_tpl_vars["class"]['className']; ?></li>
								<?php }; ?>
							</ul>
							<ul class="sixCourse-classInfos clearfix">
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["classList"] as SlightPHP\Tpl::$_tpl_vars["class"]){; ?>
								<li>
									<p class="dGray">
										<span class="cGray">课时：</span>共<?php echo SlightPHP\Tpl::$_tpl_vars["class"]['planNum']; ?>课时   <?php echo SlightPHP\Tpl::$_tpl_vars["class"]['progress']; ?>
										<span class="cRed"><?php echo SlightPHP\Tpl::$_tpl_vars["class"]['classStatus']; ?></span>
									</p>
									<p class="dGray">
										<span class="cGray">主讲：</span><?php echo SlightPHP\Tpl::$_tpl_vars["class"]['teacherName']; ?>
									</p>
	 								<p class="dGray course-address" style="display:none;">
	 									<?php echo SlightPHP\Tpl::$_tpl_vars["class"]['classAddress']; ?>
	 								</p>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
									<li class="pos-abs course-buy-position hidden-xs"><i class="course-buy"></i></li>
									<?php }elseif( SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]!=1){; ?>
									<li class="pos-abs course-buy-position hidden-xs"><i class="course-buy"></i></li>
									<?php }; ?>
									<?php }; ?>
								</li>
								<?php }; ?>
							</ul>
							<?php }; ?>
							<!-- /多个班级 -->
						</div>
						<div class="course-infos-btn hidden-xs">
							<button class="course-end fs16 mr5" style="display:none;">已结束</button>
							<button class="course-expired fs16 mr5" style="display:none;">报名已满</button>
							<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
							<button class="fs16 add-course course-reg mr5" onclick="autoreg(this)">参与课程</button>
							<?php }elseif( empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result) && SlightPHP\Tpl::$_tpl_vars["classNum"]>0){; ?>
							<button class="fs16 add-course course-reg mr5" onclick="autoreg(this)">参与课程</button>
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["try"])&&SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType']!=3 && empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
							<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["try_plan"]; ?>" class="look-course fs16" target="_blank">
								试看课程
							</a>
							<?php }; ?>
						</div>
					</div>
					<div class="col-md-3 hidden-xs hidden-sm p0" style="width:210px;margin-left:16px;">
						<div class="course-severice-heigth clearfix">
							<h2 class="fs22 tac dGray mt40">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_score"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_score"]; ?>分<?php }else{; ?>暂无评分<?php }; ?>
							</h2>
							<div class="fs14 tac mt25 cGray" id="course-total-num">
								<?php if(SlightPHP\Tpl::$_tpl_vars["hotType"]!=4&&SlightPHP\Tpl::$_tpl_vars["hotTotal"]>0){; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["hotType"]==1){; ?>
									<!--报名人数-->
										<div class="tac">
											<span class="nav-teacher mr5"></span>
											<span class="s-course-num"><?php echo SlightPHP\Tpl::$_tpl_vars["hotTotal"]; ?>人已学习</span>
										</div>
									<?php }elseif( SlightPHP\Tpl::$_tpl_vars["hotType"]==2){; ?>
									<!--剩余报名人数-->
										<?php if(SlightPHP\Tpl::$_tpl_vars["hotTotal"]>10){; ?>
											<span class="cRed">热报中</span>
										<?php }else{; ?>
											<div class="tac">
												剩余<?php echo SlightPHP\Tpl::$_tpl_vars["hotTotal"]; ?>人
											</div>
										<?php }; ?>
									<?php }elseif( SlightPHP\Tpl::$_tpl_vars["hotType"]==3){; ?>
									<!--课程播放量-->
									<div class="tac">
										<span class="nav-teacher mr5"></span>
										<span class="s-course-num"><?php echo SlightPHP\Tpl::$_tpl_vars["hotTotal"]; ?>次</span>
									</div>
									<?php }; ?>
								<?php }; ?>
							</div>
						</div>
						<ul class="course-service-tp mt20 clearfix">
							<li>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["try"])){; ?>
								<span class="course-shi-icon"></span>
								<?php }else{; ?>
								<span class="course-shi-gray-icon"></span>
								<?php }; ?>
							</li>
							<li>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["recorded"])){; ?>
								<span class="course-lu-icon"></span>
								<?php }else{; ?>
								<span class="course-lu-gray-icon"></span>
								<?php }; ?>
							</li>
							<li>
								<span class="course-zuo-icon" style="display:none"></span>
								<span class="course-zuo-gray-icon"></span>
							</li>
						</ul>
						<ul class="course-service-ft cGray mt25 clearfix">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["qq"])){; ?>
							<li>
								<a href="javascript:;" class="cGray" target="_blank" onclick="openQQ(<?php echo SlightPHP\Tpl::$_tpl_vars["qq"]->type_value; ?>)">
									<img src="<?php echo utility_cdn::img('/assets_v2/img/gn-qq-icon.png'); ?>" alt="" /> 咨询
								</a>
							</li>
							<?php }; ?>
							<li style="width:66px;display:block;">
								<a href="javascript:;" class="cGray" cid=<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?> onclick="likeCollect(this);">
									<?php if(SlightPHP\Tpl::$_tpl_vars["like"]==1){; ?>
										<img width="18" height="15" src="<?php echo utility_cdn::img('/assets_v2/img/red-heart.png'); ?>" alt="已收藏" /> 已收藏
									<?php }else{; ?>
										<img width="18" height="15" src="<?php echo utility_cdn::img('/assets_v2/img/blank-heart.png'); ?>" alt="收藏" /> 收藏
									<?php }; ?>
								</a>
							</li>
							<li class="course-share-infos">
								<span class="play-share-icon c-fl"></span>&nbsp;分享
								<div class="share-content">
									<i class="permiss-border-arrow-up"></i>
	                                <a href="javascript:;" width="20" height="20" class="share-qq" data-cmd="tqq" title="分享到QQ"></a>
	                                <a href="javascript:;" width="20" height="20" class="share-weixin" data-cmd="weixin" title="分享到微信"></a>
	                                <a href="javascript:;" width="20" height="20" class="share-tsina" data-cmd="tsina" title="分享到新浪微博"></a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</section>
		<!-- /详情上 -->
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sourceOrgName"])){; ?>
			<section>
				<div class="bgf mb10 hidden-sm hidden-lg hidden-md">
					<h1 class="rt-title">课程来源于【<?php echo SlightPHP\Tpl::$_tpl_vars["sourceOrgName"]; ?>】</h1>
				</div>	
			</section>
			<?php }; ?>
		<!-- center-info -->
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)&&SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] != 3){; ?>
			<section>
				<div class="course-center-contents hidden-xs clearfix" status="0">
					<div class="mt5 col-md-17 p0 fs14 clearfix">
						<div class="course-recond c-fl">
							<a href="/course.stat.GetStudentPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["courseUser"]->result->fk_user; ?>" target="_blank">
								<span class="course-record-icon c-fl mr10"></span>
								<span class="dGray fs16 mr10">学习记录</span>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userTotalTime"])){; ?>
									<span style="color: #CCC;">学习时长已达到<?php echo SlightPHP\Tpl::$_tpl_vars["userTotalTime"]; ?>分钟~</span>
								<?php }else{; ?>
									<span style="color: #CCC;">还没有学习记录哦~</span>
								<?php }; ?>
							</a>
						</div>
						<div class="course-chapter-name c-fl">
							<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
								<span class="cRed mr10">学习有效期已失效，请重新参与课程</span>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
									<span class="course-being-live" style="color: #FFF">正在直播</span>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanName']; ?>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
									<span class="course-being-live" style="color: #FFF">即将直播</span>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanName']; ?>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanName']; ?>
								<?php }else{; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanName']; ?>
								<?php }; ?>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanName']; ?>
								<?php }else{; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanName']; ?>
								<?php }; ?>
							<?php }else{; ?>
							<!--
							<span class="course-being-live">上次学习到</span>
							08 一元二次方程组详解
							-->
							<?php }; ?>
						</div>
					</div>
					<div class="course-infos-btn tac c-fr">
						<button class="course-end fs16 mr5" style="display:none;">已结束</button>
						<button class="course-expired fs16 mr5" style="display:none;">报名已满</button>
						<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
						<button class="fs16 add-course course-reg mr5" onclick="autoreg(this)">参与课程</button>
						<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId']; ?>">
									<button class="add-course fs16 course-comme">进入课堂</button>
								</a>
							<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId']; ?>">
									<button class="add-course fs16 course-comme">进入课堂</button>
								</a>
							<?php }elseif( empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])&&empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
									<button class="gray-button fs16">直播无回看</button>
								<?php }else{; ?>
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>
										<button class="gray-button fs16">直播无回看</button>
									<?php }else{; ?>
										<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
											<button class="continue-course course-start fs16">开始学习</button>
										</a>
									<?php }; ?>
								<?php }; ?>
							<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]; ?>">
										<button class="continue-course fs16">继续学习</button>
									</a>
								<?php }elseif( isset(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]) && SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]>0){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>">
										<button class="continue-course fs16">重新学习</button>
									</a>
								<?php }else{; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>">
										<button class="continue-course fs16">开始学习</button>
									</a>
								<?php }; ?>
							<?php }else{; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
									<button class="continue-course course-start fs16">开始学习</button>
								</a>
							<?php }; ?>
						<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
							<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
								<button class="gray-button fs16">暂无视频</button>
							<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']]; ?>">
									<button class="continue-course course-start fs16">继续学习</button>
								</a>
								<?php }else{; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>">
										<button class="continue-course course-start fs16">开始学习</button>
									</a>
								<?php }; ?>
							<?php }else{; ?>
								<button class="gray-button fs16">暂无视频</button>
							<?php }; ?>
						<?php }else{; ?>
						<!--
						<span class="course-being-live">上次学习到</span>
						08 一元二次方程组详解
						-->
						<?php }; ?>
					</div>
				</div>
			</section>
			<?php }else{; ?>
			<div class="course-center-contents" style="display:none;" status="1"></div>
			<?php }; ?>
		<!-- /center-info -->
		<!-- 详情下 -->
			<section class="clearfix">
				<!-- lt -->
				<div class="col-md-14 p0">
					<div class="bgf bor1px">
						<!-- btn -->
						<nav id="nav-course" class="nav-course swiper-wrapper clearfix">
							<ul class="course-ft-nav swiper-wrapper course-nav-menu clearfix">
								<li class="col-md-3 col-xs-3 swiper-slide" status="1" onclick="clickCourseInfo(this)">
									介绍
								</li>
								<li class="col-md-3 col-xs-4 swiper-slide" status="2" onclick="clickCourseInfo(this)">
									目录(<span class="chapter-num"></span>)
								</li>
								<li class="col-md-3 col-xs-4 swiper-slide" status="3" onclick="clickCourseInfo(this)">
									评价<span class="comment-num"></span>
								</li>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
								<li class="col-md-3 col-xs-4 swiper-slide" status="4" onclick="clickCourseInfo(this)">
									作业<span class="task-num"></span>
								</li>
								<li class="col-md-3 col-xs-4 swiper-slide" status="5" onclick="clickCourseInfo(this)">
									笔记<span class="note-num"></span>
								</li>
								<li class="col-xs-4 hidden-lg swiper-slide hidden-md hidden-sm" status="6" onclick="clickCourseInfo(this)">
									资料
								</li>
								<li class="col-xs-4 hidden-lg swiper-slide hidden-md hidden-sm" status="7" onclick="clickCourseInfo(this)">
									<a href="javascript:;" class="course-static-info">
										统计
									</a>
								</li>
								<?php }; ?>
							</ul>
						</nav>
						<!-- /btn -->
						<!-- content -->
						<div id="wrap-course-ft">
							<div class="course-info-content" <?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]<1){; ?>style="display:block;"<?php }; ?>>
							<!-- mobile-teacher -->
								<ul class="rt-course-teacher hidden-lg hidden-md hidden-sm">
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["teacherMasterList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
									<li class="clearfix">
										<a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="fs12"  target="_blank">
											<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherThumb']; ?>" alt="" />
											<div class="teacher-infos">
												<span style="display: block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherName']; ?></span>
												<div>
													<span class="course-teacher-infos"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['desc'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['desc']; ?><?php }else{; ?>暂无简介<?php }; ?></span>
													<span class="course-teacher-detail">详细&gt;</span>
												</div>
											</div>
										</a>
									</li>
									<?php }; ?>
								</ul>
							<!-- /mobile-teacher -->
								<div class="course-introduce">
								<?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]<1){; ?>
									<p class="tac mt10">
										<div class="course-no-data">
											<span class="course-blank-intro-icon c-fl mr10"></span>
											<div class="mt5">还没来得及填写简介哦~</div>
										</div>
									</p>
								<?php }else{; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['scope'])){; ?>
									<p>
										<span class="cGray">适合范围：</span>
										<div class="course-font">
											<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['scope']; ?>
										</div>
									</p>
									<?php }; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['desc'])){; ?>
									<p>
										<span class="cGray">
											课程简介：
										</span>
										<div class="course-font">
											<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['desc']; ?>
										</div>
									</p>
									<?php }else{; ?>
									<p class="tac mt10 no-course-font">
										<div class="course-no-data">
											<span class="course-blank-intro-icon c-fl mr10"></span>
											<div class="mt5">还没来得及填写简介哦~</div>
										</div>
									</p>
									<?php }; ?>
								<?php }; ?>
								</div>
							</div>
							<div class="course-info-content course-chapter" <?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]>0){; ?>style="display:block;"<?php }; ?>>
								<ul id="planList"></ul>
							</div>
							<div class="course-info-content">
								<!-- selectstore -->
								<div class="select-store clearfix">
									<div class="divselect divselect-32 bgf mr20" id="select-score">
										<cite>
											<span class="cite-icon"></span>
											<span class="cite-text fs12" score="">全部分级</span>
										</cite>
										<dl class="fs12">
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="all">全部分级</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="5">5分评价</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="4">4分评价</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="3">3分评价</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="2">2分评价</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectScore(this);" score="1">1分评价</a>
											</dd>
										</dl>
									</div>
									<div class="divselect divselect-32 bgf mr20" id="select-time">
										<cite>
											<span class="cite-icon"></span>
											<span class="cite-text fs12" time="">全部时间</span>
										</cite>
										<dl class="fs12">
											<dd>
												<a href="javascript:;" onclick="selectTime(this);" time="">全部时间</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectTime(this);" time="week">最近一周</a>
											</dd>
											<dd>
												<a href="javascript:;" onclick="selectTime(this);" time="month">最近一个月</a>
											</dd>
										</dl>
									</div>
									<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])&&!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
									<label class="checked-me-comment hidden-xs">
										<input type="checkbox" onclick="courseComment(this)" id="checked-me-comment" /> 只看我的
									</label>
									<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
									<label class="checked-me-comment hidden-xs">
										<input type="checkbox" onclick="courseComment(this)" id="checked-me-comment" /> 只看我的
									</label>
									<?php }; ?>
								</div>
								<!-- /selectstore -->
								<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!empty(SlightPHP\Tpl::$_tpl_vars["userMemberSet"])&&!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
								<p class="tac mt10 mb10" id="send-comment">
									<button class="fs14 send-comment" onclick="sendCourseComment()">发布评价</button>
								</p>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
								<p class="tac mt10 mb10" id="send-comment">
									<button class="fs14 send-comment" onclick="sendCourseComment()">发布评价</button>
								</p>
								<?php }; ?>
								<ul class="course-comment-infos" id="course-comment-list"></ul>
								<!-- page -->
								<div id="course-comment-page" class="tac mt20"></div>
								<!-- /page -->
							</div>
							<div class="course-info-content course-task">
								<ul id="course-task-list"></ul>
								<!-- page -->
								<div id="course-task-page" class="tac mt20"></div>
								<!-- /page -->
							</div>
							<div class="course-info-content course-note">
								<!-- noteChapter -->
								<ul class="note-chapter clearfix" style="display:none;" id="note-chapter-num"></ul>
								<!-- /noteChapter -->
								<!-- note-list -->
								<ul class="course-note-list mt10" id="course-note-list"></ul>
								<!-- /note-list -->
								<!-- note-page -->
								<div id="course-note-page" class="mt20 tac"></div>
								<!-- /note-page -->
							</div>
							<div class="course-info-content course-ziliao">
								<ul class="rt-course-ziliao">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attachData"])){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["attachData"] as SlightPHP\Tpl::$_tpl_vars["ao"]){; ?>
									<li class="clearfix" onclick="downloadCount(<?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->planattid; ?>)">
										<a href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ao"]->attach); ?>" target="_blank">
											<img width="35" height="35" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ao"]->thumb)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->thumb; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?><?php }; ?>" alt="" />
											<div class="ziliao-infos">
												<p><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->title; ?></p>
												<p class="cGray">
													<span><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["fileData"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach])){; ?><?php echo round((SlightPHP\Tpl::$_tpl_vars["fileData"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach]/1024),2); ?>MB<?php }; ?></span>
													<span>
														下载 <em class="course-download-icon"></em>
													</span>
												</p>
											</div>
										</a>
									</li>
									<?php }; ?>
								<?php }else{; ?>
									<div class="tac course-no-data">
										<img width="15" height="16" class="mr10" src="<?php echo utility_cdn::img('/assets_v2/img/compass.png'); ?>" alt="">
										暂无资料
									</div>
								<?php }; ?>
								</ul>
							</div>
						</div>
						<!-- /content -->
					</div>
				</div>
				<!-- /lt -->
				<!-- rt -->
				<div class="col-md-6 pr0 hidden-xs hidden-sm">
					<!-- ziliao -->
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
					<div class="bgf bordu3px mb10">
						<h3 class="rt-title">资料</h3>
						<ul class="rt-course-ziliao">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attachData"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["attachData"] as SlightPHP\Tpl::$_tpl_vars["ao"]){; ?>
							<li class="clearfix" onclick="downloadCount(<?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->planattid; ?>)" style="display:none;">
								<a href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ao"]->attach); ?>" target="_blank">
									<img width="35" height="35" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ao"]->thumb)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->thumb; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?><?php }; ?>" alt="" />
									<div class="ziliao-infos">
										<p><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->title; ?></p>
										<p class="cGray">
											<span class="mr10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["fileData"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach])){; ?><?php echo round((SlightPHP\Tpl::$_tpl_vars["fileData"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach]/1024),2); ?>MB<?php }; ?></span>
											<span>
												下载
												<em class="course-download-icon"></em>
											</span>
										</p>
									</div>
								</a>
							</li>
							<?php }; ?>
						<?php }else{; ?>
							<div class="tac course-no-data" style="line-height:60px;min-height:auto;margin:20px auto;">
								<img width="15" height="16" class="mr10" src="<?php echo utility_cdn::img('/assets_v2/img/compass.png'); ?>" alt="">
								暂无资料
							</div>
						<?php }; ?>
						</ul>
						<div class="course-more-list" id="course-download-more" style="display:none;">
							<a href="javascript:;" status="1" onclick="courseMoreList(this)">
								<em>更多</em>
								<span class="more-dowm-icon"></span>
							</a>
						</div>
					</div>
					<?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sourceOrgName"])){; ?>
					<div class="bgf bordu3px mb10">
						<h3 class="rt-title" style="border-bottom:0;font-size:14px;">课程来源于【<?php echo SlightPHP\Tpl::$_tpl_vars["sourceOrgName"]; ?>】</h3>
					</div>
					<?php }; ?>
					<!-- /ziliao -->
					<div class="bgf bordu3px mb10">
						<h3 class="rt-title">授课老师</h3>
						<ul class="rt-course-teacher" id="rt-course-teacher">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["teacherMasterList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
							<li class="pos-rel clearfix" style="display:none;">
								<div class="pos-rel">
									<a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="fs12" target="_blank">
									<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherThumb']; ?>" alt="" />
									</a>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['planid'])){; ?>
									<div class="course-plan-video" onclick="videoPlanId(this)" data-planid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['planid']; ?>">
										<span class="course-teacher-layer pos-abs"></span>
										<span class="course-teac-video">视频简介</span>
										<span class="course-small pos-abs"></span>
									</div>
									<?php }; ?>
								</div>
								<a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="fs12" target="_blank">
									<div class="teacher-infos">
										<span class="fs14" style="display: block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherName']; ?></span>
										<div>
											<span class="course-teacher-infos"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['desc'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['desc']; ?><?php }else{; ?>暂无简介<?php }; ?></span>
											<span class="course-teacher-detail" style="margin-right:7px;">详细&gt;</span>
										</div>
									</div>
								</a>
							</li>
							<?php }; ?>
						</ul>
						<div class="course-more-list" id="course-more-teacher" style="display:none;">
							<a href="javascript:;" onclick="courseMoreList(this)">
								<em>更多</em>
								<span class="more-dowm-icon"></span>
							</a>
						</div>
					</div>
					<!-- study -->
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["qqQun"])){; ?>
					<div class="bgf bordu3px mb10">
						<h3 class="rt-title">学习群</h3>
						<ul class="add-course-qq">
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["qqQun"] as SlightPHP\Tpl::$_tpl_vars["qn"]){; ?>
							<li class="p10 clearfix">
								<img width="18" class="add-qq-num mr15" height="14" src="<?php echo utility_cdn::img('/assets_v2/img/qq-num-icon.png'); ?>" alt="" />
								<div class="add-qq-infos">
									<p class="fs14 dGray"><?php echo SlightPHP\Tpl::$_tpl_vars["qn"]->type_name; ?></p>
									<span class="cGray">(群号：<?php echo SlightPHP\Tpl::$_tpl_vars["qn"]->type_value; ?>)</span>
								</div>
								<a target="_blank" class="c-fr mr30" href="<?php echo SlightPHP\Tpl::$_tpl_vars["qn"]->ext; ?>">
									<img width="90" height="22" src="<?php echo utility_cdn::img('/assets_v2/img/add-qq-icon.png'); ?>" class="add-course-qq" alt="" />
								</a>
							</li>
						<?php }; ?>
						</ul>
					</div>
					<?php }; ?>
					<!-- /study -->
					<!-- students -->
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["regUserList"])){; ?>
					<div class="bgf bordu3px">
						<h3 class="rt-title">正在学习</h3>
						<ul class="rt-course-students clearfix">
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["regUserList"] as SlightPHP\Tpl::$_tpl_vars["user"]){; ?>
							<li>
								<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["user"]['thumb']; ?>" alt="" />
								<p><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></p>
							</li>
							<?php }; ?>
						</ul>
					</div>
					<?php }; ?>
					<!-- /students -->
				</div>
				<!-- /rt -->
			</section>
		<!-- /详情下 -->
		<!-- fix -->
			<section id="course-fix-nav" class="hidden-xs hidden-sm" style="display:none;">
				<div class="course-fix-nav">
					<div class="container">
						<div class="row">
							<div class="col-md-14 p0">
								<nav>
									<ul class="course-ft-nav course-nav-menu clearfix">
										<li class="col-md-3 col-xs-3" status="1" onclick="clickCourseInfo(this)">
											介绍
										</li>
										<li class="col-md-3 col-xs-4" status="2" onclick="clickCourseInfo(this)">
											目录(<span class="chapter-num"></span>)
										</li>
										<li class="col-md-3 col-xs-4" status="3" onclick="clickCourseInfo(this)">
											评价<span class="comment-num"></span>
										</li>
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
										<li class="col-md-3 col-xs-4" status="4" onclick="clickCourseInfo(this)">
											作业<span class="task-num"></span>
										</li>
										<li class="col-md-3 col-xs-4" status="5" onclick="clickCourseInfo(this)">
											笔记<span class="note-num"></span>
										</li>
										<li class="col-xs-4 hidden-lg hidden-md hidden-sm" status="6" onclick="clickCourseInfo(this)">
											资料
										</li>
										<li class="col-xs-4 hidden-lg hidden-md hidden-sm" status="7" onclick="clickCourseInfo(this)">
											<a href="javascript:;" class="course-static-info">
												统计
											</a>
										</li>
										<?php }; ?>
									</ul>
								</nav>
							</div>
							<div class="col-md-6 pr0 tac clearfix">
									<div class="course-infos-btn course-fix-infos-btn col-md-20 p0 c-fl">
										<button class="course-end fs16 mr5" style="display:none;">已结束</button>
										<button class="course-expired fs16 mr5" style="display:none;">报名已满</button>
									<?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]>0 && empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
										<button class="fs16 add-course course-reg" onclick="autoreg(this)">参与课程</button>
									<?php }; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
										<button class="fs16 add-course course-reg mr5" onclick="autoreg(this)">参与课程</button>
										<div class="course-sign-class c-fl fs22 mr30">
											<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['feeType'] or SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>0){; ?>
												<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
													<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
														<span class="cGreen fs20">免费</span>
													<?php }else{; ?>
														<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
															<span class="cGreen fs20">免费</span>
															<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
														<?php }else{; ?>
															<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
														<?php }; ?>
													<?php }; ?>
												<?php }else{; ?>
													<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
														<span class="cGreen fs20">免费</span>
														<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">原价：￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
													<?php }else{; ?>
														<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
															<span class="cGreen fs20">免费</span>
															<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
														<?php }else{; ?>
															<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
																<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
															<?php }else{; ?>
																<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
																<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
															<?php }; ?>
														<?php }; ?>
													<?php }; ?>
												<?php }; ?>
											<?php }else{; ?>
												<span class="cGreen fs20">免1费</span>
											<?php }; ?>
										</div>
										<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId']; ?>">
													<button class="add-course course-comme fs16">进入课堂</button>
												</a>
											<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId']; ?>">
													<button class="add-course course-comme fs16">进入课堂</button>
												</a>
											<?php }elseif( empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
												<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])&&empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
													<button class="gray-button fs16">直播无回看</button>
												<?php }else{; ?>
													<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>
														<button class="gray-button fs16">直播无回看</button>
													<?php }else{; ?>
														<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
															<button class="continue-course course-start fs16">开始学习</button>
														</a>
													<?php }; ?>
												<?php }; ?>
											<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
												<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]; ?>">
													<button class="continue-course fs16">继续学习</button>
												</a>
												<?php }elseif( isset(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]) && SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]>0){; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>">
													<button class="continue-course fs16">重新学习</button>
												</a>
												<?php }else{; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?>">
													<button class="continue-course fs16">开始学习</button>
												</a>
												<?php }; ?>
											<?php }else{; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
													<button class="continue-course course-start fs16">开始学习</button>
												</a>
											<?php }; ?>
										<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
											<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
												<button class="gray-button fs16">暂无视频</button>
											<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
												<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']]; ?>">
													<button class="continue-course course-start fs16">继续学习</button>
												</a>
												<?php }else{; ?>
												<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>">
													<button class="continue-course course-start fs16">开始学习</button>
												</a>
												<?php }; ?>
											<?php }else{; ?>
												<button class="gray-button fs16">暂无视频</button>
											<?php }; ?>
										<?php }else{; ?>
										<!--
                                        <span class="course-being-live">上次学习到</span>
                                        08 一元二次方程组详解
                                        -->
										<?php }; ?>
									</div>
								<?php }else{; ?>
									<div class="course-sign-class c-fl fs22 mr30">
									<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['feeType'] or SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>0){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
												<span class="cGreen fs20">免费</span>
											<?php }else{; ?>
												<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
													<span class="cGreen fs20">免费</span>
													<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }else{; ?>
													<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }; ?>
											<?php }; ?>
										<?php }else{; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']==0){; ?>
												<span class="cGreen fs20">免费</span>
												<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">原价：￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
											<?php }else{; ?>
												<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
													<span class="cGreen fs20">免费</span>
													<span class="fs14 member-price cGracd">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
												<?php }else{; ?>
													<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']>SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']){; ?>
														<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
													<?php }else{; ?>
														<span class="cRed fs24"><em class="fs16">￥</em><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
														<span class="fs14 member-price cGracd" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?>">￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['originPrice']; ?></span>
													<?php }; ?>
												<?php }; ?>
											<?php }; ?>
										<?php }; ?>
									<?php }else{; ?>
										<span class="cGreen fs20">免费</span>
									<?php }; ?>
									</div>
								<?php }; ?>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- /fix -->
			<!-- course-mobile-fix -->
			<section id="course-mobile-fix" style="display:none;">
				<div class="course-mobile-fix clearfix col-xs-20 p0 tac bgf">
					<div class="col-xs-10 mt10">
						<a href="javascript:;" cid=<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?> onclick="likeCollect(this);">
							<?php if(SlightPHP\Tpl::$_tpl_vars["like"]==1){; ?>
								<img width="18" height="15" src="<?php echo utility_cdn::img('/assets_v2/img/red-heart.png'); ?>" alt="已收藏" /> 已收藏
							<?php }else{; ?>
								<img width="18" height="15" src="<?php echo utility_cdn::img('/assets_v2/img/blank-heart.png'); ?>" alt="收藏" /> 收藏
							<?php }; ?>
						</a>
					</div>
					<div class="col-xs-10 course-infos-btn p0">
							<button class="course-end fs16 mr5" style="display:none;">已结束</button>
							<button class="course-expired fs16 mr5" style="display:none;">报名已满</button>
						<?php if(SlightPHP\Tpl::$_tpl_vars["classNum"]>0 && empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
							<button class="fs16 add-course course-reg" onclick="autoreg(this)">参与课程</button>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>
							<button class="fs16 add-course course-reg mr5" onclick="autoreg(this)">参与课程</button>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 1){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId'])){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['isLivePlanId']; ?>">
										<button class="add-course course-comme fs16">进入课堂</button>
									</a>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId'])){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['theLivePlanId']; ?>">
										<button class="add-course course-comme fs16">进入课堂</button>
									</a>
								<?php }elseif( empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])&&empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>
										<button class="gray-button fs16">直播无回看</button>
									<?php }else{; ?>
										<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>
											<button class="gray-button fs16">直播无回看</button>
										<?php }else{; ?>
											<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
												<button class="continue-course course-start fs16">开始学习</button>
											</a>
										<?php }; ?>
									<?php }; ?>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId'])){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoLivePlanId']]; ?><?php }; ?>">
										<button class="continue-course fs16">继续学习</button>
									</a>
								<?php }else{; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']])){; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['livePlanId']]; ?><?php }; ?>">
										<button class="continue-course course-start fs16">开始学习</button>
									</a>
								<?php }; ?>
							<?php }elseif( SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType'] == 2){; ?>
								<?php if(empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']) or empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanType'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
									<button class="gray-button fs16">暂无视频</button>
								<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId'])){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']])){; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>?play_time=<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['recordPlanTime'][SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']]; ?>">
										<button class="continue-course course-start fs16">继续学习</button>
									</a>
									<?php }else{; ?>
									<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["haveClassStatus"]['videoRecordPlanId']; ?>">
										<button class="continue-course course-start fs16">开始学习</button>
									</a>
									<?php }; ?>
								<?php }else{; ?>
									<button class="gray-button fs16">暂无视频</button>
								<?php }; ?>
							<?php }else{; ?>
							<!--
                            <span class="course-being-live">上次学习到</span>
                            08 一元二次方程组详解
                            -->
							<?php }; ?>
					<?php }; ?>
					</div>
				</div>
			</section>
			<!-- /course-mobile-fix -->
			<!-- comment -->
			<section id="send-comment-info" style="display:none;">
				<ul class="send-comment-info divSelectFirstVal fs14 pt20" id="divSelectFirstVal">
					<li class="clearfix">
						<span class="comment-title comment-title-pc tar mt5">待评价课时：</span>
						<span class="comment-title comment-title-mobile tar mt5" style="display:none;">待评价：</span>
						<div class="comment-ct pl0">
							<div class="divselect divselect-32">
								<cite>
									<span class="cite-icon"></span>
									<span class="cite-text fs12">请选择</span>
								</cite>
								<dl class="fs12" id="select-comment-chapter"></dl>
							</div>
						</div>
					</li>
					<li class="clearfix">
						<span class="comment-title comment-title-pc tar">课时评分：</span>
						<span class="comment-title comment-title-mobile tar" style="display:none;">待评分：</span>
						<div class="comment-ct pl0">
							<dl class="col-xs-6 p0 comment-stars">
								<dd class="stars-solid" score="1" title="课程太差，我要吐槽"></dd>
								<dd class="stars-solid" score="2" title="勉强中评，课程不满意"></dd>
								<dd class="stars-solid" score="3" title="中评，课程一般"></dd>
								<dd class="stars-solid" score="4" title="好评，课程还不错，继续保持"></dd>
								<dd class="stars-solid" score="5" title="五星好评！课程非常棒，点赞"></dd>
							</dl>
							<span class="comment-score" score="5">五星好评！课程非常棒，点赞</span>
						</div>
					</li>
					<li class="pos-rel clearfix">
						<div class="growth-score pos-abs" style="display:none">
							<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>" alt="" /> +2
						</div>
						<span class="comment-title comment-title-pc tar">学习感受：</span>
						<span class="comment-title comment-title-mobile tar" style="display:none;">感受：</span>
						<textarea class="comment-ct" maxlength="100" placeholder="老师讲得很不错，好喜欢！（最多输入100字）"></textarea>
					</li>
					<li class="tac clearfix course-infos-btn">
						<button class="add-course fs16" onclick="addScore()">确认</button>
						<button class="gray-button" onclick="addScoreCancle()">取消</button>
					</li>
				</ul>
				<div class="course-comment-tip tac fs16" style="display:none;"></div>
			</section>
			<!-- /comment -->
			<!-- 收件信息 -->
			<section id="received-course-info" style="display:none;">
				<div class="pt15 fs12 tac mb10">
					<p>授课机构将通过此联系方式将课程学习资料寄送到您的手中，请务必填写准确信息。</p>
					<p>本地址将同步到个人中心</p>
				</div>
				<ul class="fs12">
					<li class="mb20 clearfix">
						<span class="col-md-5 col-xs-7 tar">
							<em class="cRed ">*</em>
							收件人：
						</span>
						<div class="col-md-14 col-xs-13 pl0">
							<input type="text" placeholder="长度不超过20个字符" maxlength="20" name="name"/>
						</div>
					</li>
					<li class="mb20 clearfix">
						<span class="col-md-5 col-xs-7 tar">
							<em class="cRed ">*</em>
							收件人电话：
						</span>
						<div class="col-md-14 col-xs-13 pl0">
							<input type="text" placeholder="请填写正确的手机号" maxlength="11" name="tel"/>
						</div>
					</li>
					<li class="mb20 clearfix">
						<span class="col-md-5 col-xs-7 tar">
							<em class="cRed ">*</em>
							收件地址：
						</span>
						<div class="col-md-14 col-xs-13 mb10 pl0">
							<select id="level0">
		                        <option value="0">请选择</option>								
							</select>
							<select id="level1">
		                            <option value='0'>请选择</option>                                
		                    </select>
		                    <select id="level2">
		                            <option value='0'>请选择</option>                                
		                    </select>
						</div>
						<span class="col-md-5 col-xs-7"></span>
						<div class="col-md-14 col-xs-13 pl0">
							<input type="text" placeholder="详细地址" name="add" />
						</div>
					</li>
					<li class="mb20 clearfix">
						<span class="col-md-5 col-xs-7 tar">
							备注：
						</span>
						<div class="col-md-14 col-xs-13 pl0">
							<input type="text" placeholder="长度不超过50个字符" maxlength="50" name="remark"/>
						</div>
					</li>
					<li class="tac mb20 clearfix">
						<button class="yellow-btn mr10" id="saveBtn">保存</button>
						<button class="gray-button" onclick="closeReceivedInfo()">取消</button>
					</li>
				</ul>
			</section>
			<!-- /收件信息 -->
		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script id="planListTpl" type="text/template">
	<<#planList>>
		<li class="clearfix" planId="<<planId>>" playTime="<<playTime>>" type=<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType']; ?>>
			<a href="javascript:;" planUrl="/course.plan.play/<<planId>>/<<resellOrgId>>" target="_blank">
				<span class="chapter-title"><<sectionName>></span>
				<div class="chapter-icon c-fl hidden-xs hidden-sm">
					<i class="chapter-circular"></i>
					<span class="chapter-vertical-line"></span>
				</div>
				<dl class="chapter-info col-xs-12 col-md-15 c-fl">
					<dd class="fs14 dGray course-plan-title">
						<<&sectionDesc>>
					</dd>
					<dd class="cGray course-long-time"><<startTime>></dd>
				</dl>
				<div class="chapter-look-icon c-fr">
					<<&planStatus>>
				</div>
			</a>
		</li>
	<</planList>>
</script>
<script id="NoteChapterTpl" type="text/template">
	<<#planList>>
		<li onclick="getNoteList(this);" planId="<<planId>>"><<sectionName>></li>
	<</planList>>
</script>
<script id="selectCommentTpl" type="text/template">
	<<#planList>>
		<dd>
			<a href="javascript:;" planId="<<planId>>" onclick="selectCommentChapter(this);"><<sectionName>></a>
		</dd>
	<</planList>>
</script>
<script id="planCommentStatusTpl" type="text/template">
	<<#list>>
		<dd>
			<a href="javascript:;" type="<<type>>" planId="<<planId>>" onclick="selectCommentChapter(this);"><<sectoinName>></a>
		</dd>
	<</list>>
</script>
<script id="courseCommentTpl" type="text/template">
	<<#data>>
		<li class="p15 clearfix">
			<div class="course-user-hd col-md-2 col-xs-5 col-sm-2">
				<img src="<<userThumb>>" alt="" />
				<p class="tac">
					<span class="level-sicon<<userLevel>>"></span>
					<<userName>>
				</p>
			</div>
			<div class="course-comments-content col-md-17 col-xs-14 col-sm-17">
				<dl class="clearfix" studentScore="<<studentScore>>">
					<dd class="stars-hollow"></dd>
					<dd class="stars-hollow"></dd>
					<dd class="stars-hollow"></dd>
					<dd class="stars-hollow"></dd>
					<dd class="stars-hollow"></dd>
				</dl>
				<p class="dGray mt5"><<comment>></p>
				<p class="cGray mt5"><<sectionName>> <<time>>
					<a href="javascript:;" onclick="delComment(this)" style="display:none;" planId="<<planId>>" userId="<<userId>>" class="user-del-comment cBlue ml10">删除</a>
				</p>
			</div>
			<div class="course-user-replay col-xs-20 col-md-17 c-fr" replay-contents="<<replay.contents>>">
				<span>【老师回复】</span>：<<replay.contents>>
			</div>
		</li>
	<</data>>
</script>
<script id="courseTaskTpl" type="text/template">
	<<#data>>
		<li class="clearfix">
			<div class="course-task-date">
				<p><<create_time_month>></p>
				<p><<create_time_time>></p>
			</div>
			<div class="course-task-content">
				<p class="dGray fs14"><<task_desc>></p>
				<p class="cGray">
					<span class="hidden-xs"><<teacher_name>></span>
					截止时间：<<end_time_handle>>
				</p>
			</div>
			<div class="couse-task-status c-fr" data-url="<<url>>" data-level="<<level>>" data-status="<<student_status>>"></div>
		</li>
	<</data>>
</script>
<script id="courseNoteTpl" type="text/template">
	<<#items>>
		<li class="clearfix">
			<div class="note-content">
				<p data-content="<<content>>" class="dGray fs14 note-title"><<content>></p>
				<p class="cGray clearfix">
					<span class="c-fl mr10"><<last_updated>></span>
					<a href="javascript:;" onclick="courseNoteUpdate(this)" class="course-compose-icon hidden-xs mr10 c-fl"></a>
					<a href="javascript:;" onclick="courseDelNote(this)" planId="<<fk_plan>>" noteId="<<id>>" class="course-task-del hidden-xs c-fl"></a>
				</p>
			</div>
			<div class="c-fr">
				<a href="javascript:;" target="_blank" data-hidden="<<hidden>>" data-url="<<url>>" data-plan-time="<<play_time_tmp_handle>>" data-time="<<play_time_tmp>>" class="course-time-info">
					<i class="border-arrow-right"></i>
					<<play_time_format>>
				</a>
			</div>
			<div class="course-note-update" style="display:none;">
				<textarea placeholder="最多输入50个汉字" maxlength="50"></textarea>
				<div class="note-btns clearfix">
					<button onclick="courseNoteDel(this)" class="note-cancle">取消</button>
					<button onclick="courseAddNote(this)"planId="<<fk_plan>>" noteId="<<id>>" class="note-submit">保存</button>
				</div>
			</div>
		</li>
	<</items>>
</script>

<script type="text/javascript">
var isMemberRegType=true;
<?php if(SlightPHP\Tpl::$_tpl_vars["isMemberRegType"]==1&&!in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>isMemberRegType=false;<?php }; ?>
var	courseUser = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result)){; ?>true<?php }else{; ?>false<?php }; ?>;
var classId = <?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>;
var planList = <?php echo SlightPHP\Tpl::$_tpl_vars["planList"]; ?>;
var classList = <?php echo json_encode(SlightPHP\Tpl::$_tpl_vars["classList"]); ?>;
var cid = <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>;
var classNum = <?php echo SlightPHP\Tpl::$_tpl_vars["classNum"]; ?>;
var courseFeeType = <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['feeType']; ?>;
var regSource = <?php echo SlightPHP\Tpl::$_tpl_vars["regSource"]; ?>;
var resellOrgId = <?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?>;
var userId = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseUser"]->result->fk_user)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseUser"]->result->fk_user; ?><?php }else{; ?>0<?php }; ?>;
var type = <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseType']; ?>;
var sPic = "<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumb']; ?>";
var sTitle = "<?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title']); ?>-<?php echo tpl_function_part('/site.main.orgname'); ?>-云课-专业的在线学习平台";
var wx_href='<?php echo SlightPHP\Tpl::$_tpl_vars["qrCode"]; ?>';
var qudaoCode = '<?php echo SlightPHP\Tpl::$_tpl_vars["qudaoCode"]; ?>';
//登陆
function checkLogin(source, setId) {
	if(!setId){
		setId=0;
	}
	var selectedClassId = classId;
	var w,h;

	if($(window).width() > 780) {
		w='476px';
		h='390px';
	}else{
		w='90%';
		h='360px';
	}
	if (userApi.isLogin()) return true;

	if (userApi.isWeiXin() || $(window).width()<780) {
		//存储自动报名
		userApi.setCookie("course.autoreg",<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>);
		if (userApi.isLogin()){
			return true;
		}else{
			var url = '/site.main.login/?url=/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]["courseId"]; ?>&cid='+cid+'&clid='+selectedClassId+'&source='+source;
			if(resellOrgId){
				url += '&resellOrgId='+resellOrgId;
			}else if (qudaoCode){
				url += '&qudaoCode='+qudaoCode;
			}
			location.href=url;
		}
	}else{
		var url = '/layer.main.userLogin/'+cid+'/'+selectedClassId+'/'+classNum+'/'+source+'/'+setId;
		if(resellOrgId){
			url += '/'+resellOrgId;
		}else if (qudaoCode){
			url += '/'+qudaoCode;
		}
		layer.open({
			type: 2,
			title:false,
			area: [w, h],
			shadeClose: true,
			content: [url, 'no']
		});
		return false;
	}
}
//立即报名按钮
function autoreg(setId){	
			if(!setId){
					setId=0;
				}
				var classId = $('body').attr('classid');
				var source = 'reg';
				var oid = <?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?>;
				var w,h;
				var MultipleType = false;
				if(!classId || !cid ) {
					layer.msg('<?php echo tpl_modifier_tr('班级或课程ID不能为空','course.info'); ?>');
					return false;
				}

				if (checkLogin(source)) {
					getUserAddress(function onBackCode(r) {
					if(r == 1) { //有地址
						if(classNum >1) {
							if($(window).width() > 780) {
								w='460px';
								h='260px';
							}else {
								w='90%';
								h='260px';
							}
							var url = "/course/info/GetClassList/"+cid+'/'+classId;
							if(resellOrgId){
								url += "/"+resellOrgId;
							}else if(qudaoCode){
								url += "/"+qudaoCode;
							}
							if($(window).width() < 780) {
								MultipleType = true;
							}else {
								layer.open({
									type: 2,
									title:['报名'],
									area: [w, h],
									shadeClose: true,
									content: url
								});

							}
						}
						if(classNum == 1 || MultipleType) {
							var type = <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['feeType']; ?>;
							// var index = layer.load(2);
							var coursePrice =<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?>;
							<?php if(in_array(0,SlightPHP\Tpl::$_tpl_vars["userMemberSet"])){; ?>coursePrice=0;<?php }; ?>
							if(coursePrice>0) {
								// 付费课程报名，需要先支付
								$.post("/course/pay/check", { classId:classId ,cid:cid,resellOrgId:resellOrgId }, function (r) {
									if (r.code == 0) {
										if (resellOrgId) {
											parent.location.href = "/order.main.buy/course/"+cid+"/"+classId+"/"+resellOrgId;
										}else if(qudaoCode) {
											parent.location.href = "/order.main.buy/course/"+cid+"/"+classId+"/"+qudaoCode;
										}else {
											parent.location.href = "/order.main.buy/course/"+cid+"/"+classId;
										}
									} else {
										layer.msg(r.errMsg);
									}
								}, "json");
							} else {
								$.post("/course/info/AddReg", { classId: classId ,cid: cid ,oid: oid, source: regSource }, function (r) {
									//add weixin 后缀，解决微信里不能刷新的问题
									// layer.close(index);
									if (r.code == 0) {
										layer.msg('<?php echo tpl_modifier_tr('报名成功','course.info'); ?>',{ icon:1  }, function(){
											location.href='//'+location.hostname+"/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>#reg=1";
										});
									} else if (r.code == 1021) {
										layer.open({
											type: 2,
											title:false,
											area: [w, h],
											shadeClose: true,
											content: ['/layer.main.userLogin/'+cid+'/'+classId+'/'+classNum+'/'+source+'/'+setId+'/'+resellOrgId, 'no']
										});
										return false;
									} else {
										layer.msg(r.errMsg, { icon:1 }, function(){
											location.href='//'+location.hostname+"/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>#reg=0";
										});
									}
								}, "json");
							}
						}
					}else{ //无地址
						return;
					}
	})	
				}
			
	
}

function getUserAddress(callBackCode){
	$.get('/student/main/getUserAddressExist/'+cid, function(r){
		if(r == 0) {
			if($(window).width() > 780) {
				w='480px';
				h='420px';
			}else {
				w='90%';
				h='420px';
			}
			layer.open({
			  type: 1,
			  shadeClose: true,
			  area: [w, h],
			  title:['收件信息'], 
			  content: $('#received-course-info')
			});
			callBackCode(r)
		}else{
			callBackCode(r);
		}		
	},'json')
}
$(function() {
	//收件信息
	$.get('/student.main.getleve/0', function(data){
	        if (data) {            
	          var html = '';
	          $(data).each(function(i,item){
	            html += '<option value="'+item.region_id+'"';
	            html += '>'+item.name+'</option>';
	          })
	          $('#level0').show().html('<option>请选择</option>'+html);  
	        };
	        },'json');

	$('.mb10').on('change','#level0',function(event){
	    var lel = this.value;
	    $.get('/student.main.getleve/'+lel, function(data){
	        if (data) {            
	          var html = '';
	          $(data).each(function(i,item){
	            html += '<option value="'+item.region_id+'"';
	            html += '>'+item.name+'</option>';
	          })
	          $('#level1').show().html('<option>请选择</option>'+html);  
	        };
	        },'json');
	})

	$('.mb10').on('change','#level1',function(event){
	    var lel = this.value;    
	    $.get('/student.main.getleve/'+lel, function(data){
	        if (data) {            
	          var html = '';
	          $(data).each(function(i,item){
	            html += '<option value="'+item.region_id+'"';
	            html += '>'+item.name+'</option>';
	          })
	          $('#level2').show().html('<option>请选择</option>'+html);  
	        }else{
	            $('#level2').hide();
	            $('select[id=level2]').val('0');
	        }
	        },'json');
	})
	$('#received-course-info').on('click', '#saveBtn', function(event){
		if($('input[name=name]').val() == '') {
			layer.msg('请输入收件人');
		}else if($('input[name=tel]').val() == '') {
			layer.msg('请输入手机号');
		}else if($('input[name=tel]').val() < 13000000000 || $('input[name=tel]').val() > 19999999999) {
			layer.msg('请输入有效手机号');
		}else if($('select[id=level0]').val() == 0 || $('select[id=level1]').val() == 0) {
			layer.msg('请选择省市');
		}else if($('input[name=add]').val() == '') {
			layer.msg('请输入详细地址');
		}else{
		    var params = {
		        'receiver': $('input[name=name]').val(),
		        'phone': $('input[name=tel]').val(),
		        'level0':$('select[id=level0]').val(),
		        'level1':$('select[id=level1]').val(),
		        'level2':$('select[id=level2]').val(),
		        'address':$('input[name=add]').val(),
		        'desc':$('input[name=remark]').val()        
		    }  
		    $.ajax({
		        type:'POST',
		        url :'/student.main.adduseraddress',
		        data: params,
		        dataType: 'json',
		        success:function(data){
		           if (data.code==0) {
		                closeReceivedInfo();
		                autoreg();
		           }else{
		                layer.msg(data.msg);
		           }                
		        }      

		    });
		}  
	});
})

function closeReceivedInfo() {
	layer.closeAll();
}
</script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.share.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.info.js'); ?>"></script>
