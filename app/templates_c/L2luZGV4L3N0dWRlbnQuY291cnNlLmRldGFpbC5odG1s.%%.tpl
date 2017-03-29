<!DOCTYPE html>
<html>
<head>
<title>课程详情 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="课程详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
    <!--header-->
<?php echo tpl_function_part("/index.main.usernav/student"); ?>
<!-- /header -->
<!-- content -->
<section id="wrap-course-details" style="margin-bottom: 40px;">
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?>
	<div class="container bgf">
	<!-- 课程信息 -->
		<div class="my-course-info col-sm-20">
			<div class="col-sm-5 course-img">
				<a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?><?php }; ?>" title="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_name']; ?><?php }; ?>" target="_blank">
					<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_thumb_sma']; ?><?php }; ?>" alt="" />					
					<?php if(SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_type'] == 2){; ?>
					    <span class="taped-class fs12">录播课</span>
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_type'] == 3)){; ?>
					    <span class="lineclass-icon fs12">线下课</span>
                    <?php }; ?>
				</a>
			</div>
			<div class="col-sm-5 avg-score" data-score=<?php echo SlightPHP\Tpl::$_tpl_vars["avg_score"]; ?>>
			
				<div class="my-score-content-tp">
					<h1 class="fs16">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?>" title="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_name']; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_name']; ?></a><?php }; ?>
					</h1>
					<p class="fs14"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"]['class_name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['class_name']; ?><?php }; ?></p>
				
					<p class="fs14"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org_name"]; ?><?php }; ?></p>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["avg_score"])){; ?>
					<div class="s-course-pf fs14">
						<dl>
							<dd class="s-empty-star"></dd>
							<dd class="s-empty-star"></dd>
							<dd class="s-empty-star"></dd>
							<dd class="s-empty-star"></dd>
							<dd class="s-empty-star"></dd>
						</dl>
						（<?php echo SlightPHP\Tpl::$_tpl_vars["avg_score"]; ?>分）
						<input type="hidden" class="ipt-score" name="score" value="<?php echo SlightPHP\Tpl::$_tpl_vars["avg_score"]; ?>">
					</div>
					<?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"]['address']) && SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_type'] == 3){; ?>
					<p class="local-adress fs14"><i class="local-icon c-fl"></i><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['address']; ?></p>
					<?php }; ?>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="col-md-20 pd0 fs14 tel">
				<?php if(SlightPHP\Tpl::$_tpl_vars["memberRet"]['isMemberRegType'] == 1){; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["memberRet"]['isMember'] == 1 ){; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["memberRet"]['isExpire'] == 1){; ?>
							<span class="failed-vip-icon"></span>
							学习有效期已失效，继续学习请
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?>" class="c-orange" target="_blank">立即购买</a>
							课程或
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['member_url']; ?>" target="_blank" class="c-orange">重新开通</a>
							会员
						<?php }else{; ?>
							<span class="vip-icon"></span>
							会员课程
						<?php }; ?>
					<?php }else{; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->fee_type == '免费'){; ?>
							<span class="failed-vip-icon"></span>
							学习有效期已失效，继续学习请
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?>" class="c-orange" target="_blank">重新报名</a>
						<?php }else{; ?>
							<span class="failed-vip-icon"></span>
							学习有效期已失效，继续学习请
							<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?>" class="c-orange" target="_blank">立即购买</a>
							课程
						<?php }; ?>
					<?php }; ?>
				<?php }; ?>
				</div>
				<div class="my-score-content-ft mt40">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"]['curr_sname'])){; ?>
					<p class="my-progress-subtip" style="text-align:right;">			
					<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['curr_sname']; ?>
					</p>
					<?php }; ?>
					<div class="my-progress">
					   <div class="my-progress-bar" role="progressbar" aria-valuenow="60" 
					      aria-valuemin="0" aria-valuemax="100" style="width: <?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['percent']; ?>;">
					      <span><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['percent']; ?></span>
					   </div>
					</div>
				</div>
				<p>
					<span class="c-fl fs12 cGray2">课程进度</span>
					<span class="c-fr fs12 cGray2"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?>共<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['section_count']; ?>章<?php }; ?></span>
				</p>
			</div>
			<div class="col-sm-5 mt10 course-teacher-img">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class_detail"])){; ?>
				<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['admin_url']; ?>" title="" target="_blank">	
					<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['admin_thumb']; ?>" alt="" />
				</a>
				<p class="pos-rel">主讲：<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['admin_url']; ?>" title="" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['admin_name']; ?></a>
					<span onclick="postChatCtrl()" class="my-info-icon pos-abs"></span>
				</p>
				<?php }; ?>
			</div>
		</div>
	<!-- /课程信息 -->
	</div>
	<?php }; ?>
	<div class="container ptb35 bgf mt15">
	<!-- 课程统计与下载资料 -->
		<!-- btn -->
		<div class="col-sm-20 col-lg-20 col-xs-20">
			<ul class="clearfix fs16 s-list-tab-btn" >
				<?php if(SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_type'] == 1 &&!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["lastUpdated"])){; ?>
				<li style="display: block" class="col-sm-2 col-lg-3 col-md-8 col-xs-10 z-active"  <?php if(SlightPHP\Tpl::$_tpl_vars["first_show"] == 1){; ?>style="border-right:1px solid #ddd;"<?php }; ?>>
					<a href="javascript:;">课程统计</a>
					<i></i>
				</li>
				<?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attach_count"])){; ?>
				<li class="col-sm-2 col-xs-10 col-lg-3<?php if(empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]['lastUpdated'])){; ?>z-active<?php }; ?>"  <?php if(SlightPHP\Tpl::$_tpl_vars["second_show"] == 1){; ?>style="border-right:1px solid #ddd"<?php }; ?>>
					<a href="javascript:;">下载资料(<?php echo SlightPHP\Tpl::$_tpl_vars["attach_count"]; ?>)</a>
					<i></i>
				</li>
				<?php }; ?>
			</ul>
		</div>
	
		<!-- /btn -->
		<!-- tab-content -->
		<div class="s-tab-content-box col-sm-20 col-xs-20 c-fl">
			<div class="s-tab-content" style="display:block;">
				<!--<ul class="tab-classroom-statistics col-sm-20 clearfix col-xs-20 col-lg-20 col-lg-offset-7">
					<li class="col-sm-10 col-lg-3 col-xs-10 col-md-10">
						<div class="statistics-cirl o-cirl-tiem"></div>
						<p class="fs14">排名第<span><?php echo SlightPHP\Tpl::$_tpl_vars["sort"]; ?></span>名</p>
					</li>
			<?php /*		<li class="col-sm-3">
						<div class="statistics-cirl t-cirl-tiem"></div>
						<p class="fs14">发言<span>5</span>次</p>
						<i></i>
					</li>*/?>
					<li class="col-sm-10 col-lg-3 col-xs-10 col-md-10">
						<div class="statistics-cirl th-cirl-tiem"></div>
						<p class="fs14">点赞共<span><?php echo SlightPHP\Tpl::$_tpl_vars["good_count"]; ?></span>次</p>
						<i></i>
					</li>
					<?php /*<li class="col-sm-3">
						<div class="statistics-cirl f-cirl-tiem">
							<div class="statistics-cirl-second-layer statistics-cirl-solid-one">
								<font>90%</font>
								<span>6次</span>
							</div>
						</div>
						<p class="fs14">准时到课<span>90%</span></p>
						<i></i>
					</li>
					<li class="col-sm-3">
						<div class="statistics-cirl fv-cirl-tiem">
							<div class="statistics-cirl-second-layer statistics-cirl-solid-two">
								<font><?php echo SlightPHP\Tpl::$_tpl_vars["right_percent"]; ?></font>
								<span><?php echo SlightPHP\Tpl::$_tpl_vars["right_count"]; ?>题</span>
							</div>
						</div>
						<p class="fs14">答题正确率<span><?php echo SlightPHP\Tpl::$_tpl_vars["right_percent"]; ?></span></p>
						<i></i>
					</li>*/?>
					<?php /*<li class="col-sm-3">
						<div class="statistics-cirl fv-cirl-tiem">
							<div class="statistics-cirl-second-layer statistics-cirl-solid-third">
								<font>5%</font>
								<span>5次</span>
							</div>
						</div>
						<p class="fs14">作业完成<span>5%</span></p>
						<i></i>
					</li>*/?>
				</ul>-->
			<!--新版课堂统计内容-->
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["lastUpdated"])){; ?>
				<h1 class="fs12 ter lh22 fcg9">
					截止到目前已上直播课章节的情况（数据更新时间：<?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["lastUpdated"]; ?>）
				</h1>
				<dl class="clearfix n-article-static col-md-20 pd0 col-xs-20 col-sm-20 col-lg-20">
					<dd class="bgf7 clearfix tec col-md-20 pd0 col-xs-20 col-sm-20 col-lg-20">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 pd0">到课率</div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0">准时</div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs">未到</div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs">迟到</div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0">点赞</div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0">发言</div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs">举手</div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs">讨论区</div>
							<div class="col-lg-2 col-md-4 col-sm-4 col-xs-8 pd0">随堂测试正确率</div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs">咨询回答率</div>
							<div class="col-lg-2 col-md-4 col-sm-4 hidden-xs pd0">观看直播</div>
							<div class="col-lg-2 col-md-4 col-sm-4 hidden-xs pd0">观看录播</div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs">下载资料</div>
					</dd>
					<dd class="clearfix tec col-md-20 pd0 col-xs-20 col-sm-20 col-lg-20">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["attendance"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["attendance"]; ?>%<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["onTime"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["onTime"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["noTo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["noTo"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["late"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["late"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["zan"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["zan"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["call"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["call"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-1 hidden-md hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["handup"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["handup"]; ?>次<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["discuss"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["discuss"]; ?>条<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-2 col-md-4 col-sm-4 col-xs-8 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["correct"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["correct"]; ?>%<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["answerRate"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["answerRate"]; ?>%<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-2 col-md-4 col-sm-4 hidden-xs pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vvRecord"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vvRecord"]; ?>分钟<?php }else{; ?>-<?php }; ?></div>
							<div class="col-lg-2 col-md-4 col-sm-4 hidden-xs pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vtRecord"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vtRecord"]; ?><?php }else{; ?>0<?php }; ?>分钟<!--（<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vtLive"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["vtLive"]; ?><?php }else{; ?>0<?php }; ?>次）--></div>
							<div class="col-lg-2 hidden-md hidden-sm hidden-xs">-</div>
					</dd>
				</dl>
				<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["orgSubdomain"]; ?>/course.stat.GetStudentPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["user_id"]; ?>" title="" class="detail-btn fs12 c-fr">查看统计详情&gt;</a>
			<?php }; ?>
			<!--/新版课堂统计内容-->
			</div>
			<div class="s-tab-content" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["userStatTotal"]["lastUpdated"])){; ?>style="display:block;"<?php }; ?>>
				<ul class="course-file-load col-sm-20 clearfix">
					<form name="attach" method="post" >
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attach_data"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["attach_data"] as SlightPHP\Tpl::$_tpl_vars["ao"]){; ?>
					<li class="col-sm-3 mb20 col-xs-20" onclick="downloadCount(<?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->planattid; ?>)">
						<label>
							<a <?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["ao"]->attach); ?>"<?php }else{; ?>href="javascript:void(0);" <?php }; ?> target="_blank">
								<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
								<div class="load-icon c-fl mr10"></div>
								<?php }; ?>
								<div>
									<span class="c-fl"><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ao"]->thumb)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->thumb; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?><?php }; ?>"></span>
									<div class="brief-introduction c-fl ml25">
										<p class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["ao"]->title; ?></p>
										<p class="fs12"><?php echo date('Y-m-d',strtotime(SlightPHP\Tpl::$_tpl_vars["ao"]->create_time)); ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["file_data"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["file_data"][SlightPHP\Tpl::$_tpl_vars["ao"]->attach]; ?>KB<?php }; ?></p>
									</div>
								</div>
							</a>
						</label>
					</li>
					<?php }; ?>
					<?php }; ?>
					</form>
				</ul>
			</div>
		</div>
		<!-- /tab-content -->
<!-- /课程统计与下载资料 -->
<!-- 章节目录 -->
	<div class="col-sm-20 course-article col-xs-20 c-fl">
		<!-- btn -->
			<ul class="clearfix fs16 course-tab-btn">
				<li class="col-sm-4 z-tab-active col-md-4 pd0" style="border-right:1px solid #ddd">
					<a href="javascript:;">章节目录</a>
					<i></i>
				</li>
			</ul>
		<!-- /btn -->
		<div class="wrap-detail-list">
			<div class="c-article-content" style="display:block;">
				<ul class="course-article-content col-sm-20 fs14 clearfix col-xs-20 pd0">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planlist"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["planlist"] as SlightPHP\Tpl::$_tpl_vars["po"]){; ?>
					<li class="col-lg-20 col-sm-20 col-md-20 col-xs-20 pd0">
						<dl>
							<dt class="col-sm-3 col-lg-4 col-xs-4 col-md-3"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->section_name; ?><i></i></dt>
							<dd class="col-sm-6 col-lg-6 col-xs-5 col-md-6">
							<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->course_type !=3 ){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["study_flag"][SlightPHP\Tpl::$_tpl_vars["po"]->plan_id])){; ?>
								<span class="study-icon mt10 c-fl"></span>
								<?php }else{; ?>
								<span class="no-study-icon mt10 c-fl"></span>
								<?php }; ?>
							<?php }; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->section_desc; ?>&nbsp;
							<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name != SlightPHP\Tpl::$_tpl_vars["po"]->admin_real_name){; ?>
							(代课：<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_name; ?><?php }; ?>)
							<?php }; ?>
							<i></i></dd>
							<dd class="col-sm-4 col-lg-4 col-md-4 hidden-xs">
							<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 2 ){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["po"]->video_time)){; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->video_time; ?>
								<?php }; ?>	
							<?php }else{; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_date; ?>
							<?php }; ?>
							<i></i></dd>
							
							<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 3){; ?>
								<dd class="col-sm-4 hidden-xs"><i></i></dd>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 2)){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["po"]->video_id)){; ?>
									<dd class="col-sm-4 col-lg-4 col-xs-7 col-md-4">
										<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
										<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank">
										<button class="end-study-tip">观看视频<span></span></button>
										</a>
										<?php }; ?>
										<i></i>
									</dd>
								<?php }else{; ?>
									<dd class="col-sm-4 hidden-xs">暂无视频<i></i></dd>
								<?php }; ?>
							<?php }else{; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 1){; ?>
								<dd class="col-sm-4 col-lg-4 col-md-4 hidden-xs hidden-md hidden-sm"><i></i></dd>
								<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->status == 2)){; ?>
								<dd class="col-sm-4 col-lg-4 col-xs-6 col-md-4 pd0">
									<button class="now-study-tip">正在学习<span></span></button>
									<i></i>
								</dd>
								<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->status == 3 && SlightPHP\Tpl::$_tpl_vars["po"]->video_public_type != -2 )){; ?>
								<dd class="col-sm-4 col-lg-4 col-xs-6 col-md-4">
									<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
									<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank">
									<button class="end-study-tip">可回看<span></span></button>
									</a>
									<?php }; ?>
									<i></i>
								</dd>
								<?php }else{; ?>
								<dd class="col-sm-4 hidden-xs"><i></i></dd>
								<?php }; ?>
							<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 1){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["curr_date"] == SlightPHP\Tpl::$_tpl_vars["po"]->start_day){; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status != 3){; ?>
										<dd class="col-sm-4 col-xs-5 col-md-2">
											<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
											<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank">进入课堂</a>
											<?php }; ?>
										</dd>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->status == 3)){; ?>
									<dd class="col-sm-4 col-xs-7 col-md-2">
										<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_comment"][SlightPHP\Tpl::$_tpl_vars["po"]->plan_id])){; ?>
												<a href="javascript:;" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" class="view-comment hidden-xs">查看评价</a>
											<?php }else{; ?>
												<a class="course-article-comment hidden-xs" href="javascript:;" data-teacher=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_id; ?> data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>">写评论</a>
											<?php }; ?>
										<?php }; ?>
										<!--a  data-pid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" href="javascript:;" class="view-stats-btn">课堂统计</a-->
									</dd>
									<?php }; ?>
								<?php }else{; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 1){; ?>
										<dd class="col-sm-4 col-lg-2 col-xs-7 col-md-4">
											未开课
										</dd>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->status == 2)){; ?>
										<dd class="col-sm-2 col-xs-5 col-md-2 col-lg-2">
											<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
											<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank">进入课堂</a>
											<?php }; ?>
										</dd>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->status == 3)){; ?>
									<dd class="col-sm-2">
										<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
											<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_comment"][SlightPHP\Tpl::$_tpl_vars["po"]->plan_id])){; ?>
												<a href="javascript:;" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" class="view-comment hidden-xs">查看评价</a>
											<?php }else{; ?>
												<a class="course-article-comment hidden-xs" href="javascript:;" data-teacher=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_id; ?> data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>">写评论</a>
											<?php }; ?>
										<?php }; ?>
										<!--<a  data-pid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" href="/course.plan.getPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" target="_blank" class="view-stats-btn">课堂统计</a>-->
									</dd>
									<?php }; ?>
								<?php }; ?>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 2 && !empty(SlightPHP\Tpl::$_tpl_vars["po"]->video_time) && SlightPHP\Tpl::$_tpl_vars["po"]->status == 3)){; ?>
							<?php /*	<dd class="col-sm-2">
								<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_comment"][SlightPHP\Tpl::$_tpl_vars["po"]->plan_id])){; ?>
										<a href="javascript:;" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" class="view-comment hidden-xs">查看评价</a>
									<?php }else{; ?>
										<a class="course-article-comment hidden-xs" href="javascript:;" data-teacher=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_id; ?> data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>">写评论</a>
									<?php }; ?>
								<?php }; ?>
								</dd>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 3 && (strtotime(SlightPHP\Tpl::$_tpl_vars["po"]->start_time) < time()))){; ?>
								<dd class="col-sm-2">
								<?php if(SlightPHP\Tpl::$_tpl_vars["memberRole"] == 1){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_comment"][SlightPHP\Tpl::$_tpl_vars["po"]->plan_id])){; ?>
										<a href="javascript:;" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" class="view-comment hidden-xs">查看评价</a>
									<?php }else{; ?>
										<a class="course-article-comment hidden-xs" href="javascript:;" data-teacher=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_id; ?> data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>">写评论</a>
									<?php }; ?>
								<?php }; ?>
								</dd>*/?>
							<?php }; ?>
						</dl>
					</li>
					<?php }; ?>
					<?php }; ?>
				</ul>
			</div>
		</div>
	</div>
<!-- /章节目录 -->
	</div>
</section>
<!-- 评论 -->
<section id="score_comment">
	<div class="comment-layer col-sm-20 col-xs-20 pos-rel" id="comment_box" data-status="">
		<div id="tool"></div>
	    <div class="percent col-sm-15 col-xs-9 fs14" id="score_percent">
	        <dl>
	            <dt>综合评价：</dt>
	            <dd id="avg_score">
	                <span class="sel" data-title="很差" data-score="1"></span>
	                <span class="sel" data-title="差" data-score="2"></span>
	                <span class="sel" data-title="还行" data-score="3"></span>
	                <span class="sel" data-title="满意" data-score="4"></span>
	                <span class="sel" data-title="很好" data-score="5"></span>
	                <i score_type="avg_score" data-score="5">很好</i>
	            </dd>
	        </dl>
	        <!--<dl>-->
	            <!--<dt>课程与描述相符：</dt>-->
	            <!--<dd id="desc_score">-->
	                <!--<span class="sel" data-title="很差" data-score="1"></span>-->
	                <!--<span class="sel" data-title="差" data-score="2"></span>-->
	                <!--<span class="sel" data-title="还行" data-score="3"></span>-->
	                <!--<span class="sel" data-title="满意" data-score="4"></span>-->
	                <!--<span class="sel" data-title="很好" data-score="5"></span>-->
	                <!--<i score_type="desc_score" data-score="5">很好</i>-->
	            <!--</dd>-->
	        <!--</dl>-->
	        <!--<dl>-->
	            <!--<dt>老师的讲解表达：</dt>-->
	            <!--<dd id="explain_score">-->
	                <!--<span class="sel" data-title="很差" data-score="1"></span>-->
	                <!--<span class="sel" data-title="差"  data-score="2"></span>-->
	                <!--<span class="sel" data-title="还行"  data-score="3"></span>-->
	                <!--<span class="sel" data-title="满意"  data-score="4"></span>-->
	                <!--<span class="sel" data-title="很好"  data-score="5"></span>-->
	                <!--<i score_type="explain_score" data-score="5">很好</i>-->
	            <!--</dd>-->
	        <!--</dl>-->
	    </div>
<!-- 评论过 -->
    <div class="percent col-sm-15 col-xs-9 fs14" id="score_percent1">
        <dl>
            <dt>综合评价：</dt>
            <dd id="avg_score1">
                <span data-title="很差" data-score="1"></span>
                <span data-title="差" data-score="2"></span>
                <span data-title="还行" data-score="3"></span>
                <span data-title="满意" data-score="4"></span>
                <span data-title="很好" data-score="5"></span>
                <i score_type="avg_score" data-score="5">很好</i>
            </dd>
        </dl>
        <!--<dl>-->
            <!--<dt>课程与描述相符：</dt>-->
            <!--<dd id="desc_score1">-->
                <!--<span data-title="很差" data-score="1"></span>-->
                <!--<span data-title="差" data-score="2"></span>-->
                <!--<span data-title="还行" data-score="3"></span>-->
                <!--<span data-title="满意" data-score="4"></span>-->
                <!--<span data-title="很好" data-score="5"></span>-->
                <!--<i score_type="desc_score" data-score="5">很好</i>-->
            <!--</dd>-->
        <!--</dl>-->
        <!--<dl>-->
            <!--<dt>老师的讲解表达：</dt>-->
            <!--<dd id="explain_score1">-->
                <!--<span data-title="很差" data-score="1"></span>-->
                <!--<span data-title="差"  data-score="2"></span>-->
                <!--<span data-title="还行"  data-score="3"></span>-->
                <!--<span data-title="满意"  data-score="4"></span>-->
                <!--<span data-title="很好"  data-score="5"></span>-->
                <!--<i score_type="explain_score" data-score="5">很好</i>-->
            <!--</dd>-->
        <!--</dl>-->
    </div>
<!--  /评论过 -->
		<div id="course_sign_add" class="pos-abs"></div>
	    <div class="comment-c col-sm-20 col-xs-20 view-hidden">
	      <textarea placeholder="老师讲的很好，妈妈再也不用担心我的学习了！" id="comment_input" maxlength="100" class="col-sm-20 col-xs-20"></textarea>
	      <span class="text-num">请输入<span id="num_in">100</span>字以内</span>
	    </div>
	    <div class="comment-btn col-sm-20 col-xs-20 view-hidden">
			<input type="hidden" name="plan_id" value="">
			<input type="hidden" name="plan_teacher" value="">
	        <button id="comment_send">确定</button>
	        <button id="comment_cancel">取消</button>
	    </div>
		<div class="col-sm-20 col-xs-20 tel fs14 lh22" id="view_content">
			<p>已评论：</p>
			<p id="comment_content"></p>
			<p class="ter mt30">
				<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['course_url']; ?>" target="_blank">查看更多</a>
			</p>
		</div>
	</div>
</section>
<!-- /评论 -->
<!-- /content -->
	<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.raphael.js'); ?>" type="text/javascript"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.cirls.js'); ?>" type="text/javascript"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.sign.animation.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.growthlayer.js'); ?>"></script>
<script type="text/javascript">
	function downloadCount(id){
		$.post('/course/stat/UpdateDownloadCount', { planAttachId:id }, function(r) {

		},'json');
	}
//chatDetail
function postChatCtrl() {
    var content = '很高兴能和你聊天哦~';
    var userFrom = <?php echo SlightPHP\Tpl::$_tpl_vars["class_detail"]['admin_id']; ?>;
    $.post('/message/ajax/DirectChat', { userFrom:userFrom, content:content }, function(r) {
        if(r.code) {
            layer.msg(r.errMsg);
        }else {
            location.href = '/index.user.message';
        }
    },'json')
}

function getScore(kind){
	return $('#score_comment').find("[score_type="+kind+"]").attr("data-score");
}

var _score = $("#score_comment");
function setAvgScore(extra){
    var t = extra;
    _score.find("#score_percent>dl>dd>i").each(function(i, elem){
        t+=parseInt($(this).attr("data-score"));
    });
    t /= 3;
    $("#avg").text(t.toFixed(1));
}

$(function() {

	var courseId  = <?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>;
	var userOwner = <?php echo SlightPHP\Tpl::$_tpl_vars["owner_id"]; ?>;
	var userId    = <?php echo SlightPHP\Tpl::$_tpl_vars["user_id"]; ?>;

//学习进度章数
	$(".my-progress-subtip").css("width",$(".my-progress-bar span").html());
//Score
	//$(".s-course-pf dd").eq(Math.round( $(".s-course-pf input[name='score']").val() )-1).removeClass('s-empty-star');
	if(Math.round( $(".s-course-pf input[name='score']").val() )==0) {
		$(".s-course-pf dd").eq(Math.round( $(".s-course-pf input[name='score']").val() )).addClass("s-empty-star");
	} else if(Math.round( $(".s-course-pf input[name='score']").val() )==5) {
		$(".s-course-pf dd").eq(4).removeClass("s-empty-star");
		$(".s-course-pf dd").eq(4).prevAll("dd").removeClass("s-empty-star");
	} else {
		$(".s-course-pf dd").eq(Math.round( $(".s-course-pf input[name='score']").val() )).prevAll("dd").removeClass("s-empty-star")
	}

   $(".s-course-pf dd").eq(Math.round( $(".s-course-pf input[name='score']").val() )).prevAll("dd").removeClass("s-empty-star"); 
   $('.s-list-tab-btn li').click(function() {
      var index=$(this).index();
      $(this).addClass('z-active').siblings().removeClass('z-active')
      $('.s-tab-content-box .s-tab-content').eq(index).show().siblings().hide()
   });
   $('.course-tab-btn li').click(function() {
      var index=$(this).index();
      $(this).addClass('z-tab-active').siblings().removeClass('z-tab-active')
      $('.wrap-detail-list .c-article-content').eq(index).show().siblings().hide()
   });
   //AllCheck
	$(".s-tab-contnet-tp input[name='chks[]']").click(function(){
	    if(this.checked){
	        $(".course-file-load input[name='chk[]']").each(function(){
	            this.checked = true;
	        }); 
	    }else{ 
	        $(".course-file-load input[name='chk[]']").each(function(){
	            this.checked = false;
	        }); 
	    } 
	});
	//详情
	$("#wrap-course-details .details-btn").hover(function() {
		$(this).find(".details-content-tip").show();
	},function() {
		$(this).find(".details-content-tip").hide();
	})

	$('#attach_btn').click(function(){
		$('form[name=attach]').submit();	
	});

  //layer
/*  $(".wrap-detail-list .view-stats-btn").each(function() {
  		$(this).click(function() {
			var pid = $(this).attr('data-pid');
            layer.open({
                type: 2,
                title:false,
                closeBtn:true,
                area: ['610px', '630px'],
                shadeClose: true, //点击遮罩关闭
                //content: ['new-classroom-statistics.html','no']
                content: '/index.student.stat/'+pid
            });
  		})
  })*/
//评论弹层
	$(".course-article-comment").click(function(){
		$('input[name=plan_id]').val($(this).attr('data-id'));
		$('input[name=plan_teacher]').val($(this).attr('data-teacher'));
		$('.view-hidden').show();
		$('#view_content').hide();
		$("#score_percent").show();
		$("#score_percent1").hide();
		layer.open({
	        type: 1,
	        title: ['评论', 'background:#00a7ef;color:#ffffff'],
	        closeBtn: 1,
	        area: ['430px','280px'],
	        shadeClose: true,
			content:$('#score_comment')
		});
	});
	$(".view-comment").click(function(){
		var plan_id   = $(this).attr('data-id');
		var course_id = <?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>;
		$('#comment_box').attr('data-status',1);
		$("#score_percent1").show();
		$("#score_percent").hide();
		$.ajax({
			type:'post',
			url :'/comment/course/checkIsAddScore',
			data: { plan_id:plan_id,course_id:course_id},
			dataType: 'json',
			success:function(ret){
				if(ret.result.code == 0){
					$('#comment_content').text(ret.result.data.comment);
					$('#score_comment').find("[score_type=avg_score]").attr("data-score",ret.result.data.score);

					var score_ = ret.result.data.score;

					$("#score_percent1 dl:eq(0) span").each(function() {
						if($(this).attr("data-score") == score_) {
							$(this).css("background-position", " -4px -33px");
		                    $(this).prevAll().css("background-position", " -4px -33px");
		                    $(this).nextAll().css("background-position", " -4px -3px");
		                    $(this).siblings('i').html($(this).attr('data-title'));
						}
					})


				}
			}
		});
		$('.view-hidden').hide();
		$('#view_content').show();
		layer.open({
	        type: 1,
	        title: ['评论', 'background:#00a7ef;color:#ffffff'],
	        closeBtn: 1,
	        area: ['430px','280px'],
	        shadeClose: true,
			content:$('#score_comment')
		});
	});
    //星级评价
	$("#score_percent>dl>dd>span").click(function() {
	    $(this).css("background-position", "-4px -33px");
	    $(this).prevAll().css("background-position", " -4px -33px");
	    $(this).nextAll().css("background-position", " -4px -3px");
	    $(this).parent().find('i').html($(this).attr('data-title'));
	    $(this).parent().find('i').attr("data-score",$(this).attr('data-score'));
	    setAvgScore(0);
	});
	$("#score_percent>dl>dd>span").hover(function() {
	    $(this).css("background-position", "-4px -33px");
	    $(this).prevAll().css("background-position", " -4px -33px");
	    $(this).nextAll().css("background-position", " -4px -3px");
	    $(this).parent().find('i').html($(this).attr('data-title'));
	    setAvgScore(0);
	},function(){
	    var data_score=$(this).parent().find('i').attr("data-score");
	    $(this).parent().find("span").each(function(){
	        if($(this).attr("data-score") == data_score){
	            $(this).prevAll().css("background-position", " -4px -33px");
	            $(this).css("background-position", " -4px -33px");
	            $(this).nextAll().css("background-position", " -4px -3px");
	            $(this).parent().find('i').html($(this).attr('data-title'));
	        }
	    });
	});

    $("#comment_cancel").click(function(){
        layer.closeAll();
    });

    $("#comment_input").keyup(function() {
    	$("#num_in").text(100-$("#comment_input").val().length);
    	var curLength=$(this).val().length;
        if(curLength>100){
            layer.msg("对不起，就这么多了");
            var textin=$("#comment_input").text().substr(0,100);
            $("#comment_input").text(textin);
        }else{
            $('#num_in').text(100-$(this).val().length)
        }
    })
	$('#comment_send').click(function(){
		var comment = $('#comment_input').val().trim();
//		comment=comment.replace(/<\/?[^>]*>/g,'');
		if(!comment){
			layer.msg("评论不能为空！");
			return;
		}
		var request = {
			'score' : getScore("avg_score")
		};
		for(i in request){
			if(0 == request[i]){
				layer.msg($('#score_comment').find("[score_type="+i+"]").prev().text()+"还没有打分呢！");
				return;
			}
		}
		var avg = $('#avg').text();
		request['comment'] = comment;
		request["plan_id"] = $('input[name=plan_id]').val();
		request["user_owner"] = userOwner;
		request['course_id']  = courseId;
		request["user_teacher"] = $('input[name=plan_teacher]').val();

		$.ajax({
			type:'post',
			url :'/comment/course/addscore',
			data: request,
			dataType: 'json',
			success:function(ret){
				if(ret.code == 1){
					layer.msg('评价成功!')
	                var addScoreResult = ret.result.addScore;
					var sign_add = "<span id='sign_add' class='sign-add' style='left: 40%;top:50%;z-index:3;'></span>";
					$("#tool").append(sign_add);
					$("#sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+' + addScoreResult);
					$("#sign_add").show(function () {
						$("#sign_add").animate({ top: "30%",  opactiy: "1"}, function () {
							$("#sign_add").hide().animate({ top: "40%"});
						});
					});
					if(ret.result.upType == 1){
						layer.closeAll();
							$("body").GrowthLayer({
							types:"smallgrowth",
							space:5000,
							auto:true,
							growth:ret.result.userLevel,
							score:ret.result.currentScore
						})
					}else if(ret.result.upType == 2){
						layer.closeAll();
							$("body").GrowthLayer({
								types:"biggrowth",
								space:5000,
								auto:true,
								growth:ret.result.userLevel,
								score:ret.result.currentScore
							});
					}
					setTimeout(function() {
						window.location.reload();
					} ,1000);
				}else{
					layer.msg(ret.errMsg);
				}
			}
		});
	});

});
</script>
