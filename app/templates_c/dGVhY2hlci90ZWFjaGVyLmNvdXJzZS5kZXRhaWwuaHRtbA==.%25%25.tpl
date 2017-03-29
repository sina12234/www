<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>课程详情 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的课表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
	<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>
<div class="cont-body">
<!--header-->
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!-- /header -->
<!-- content -->
<section id="wrap-course-details" class="pd40">
	<div class="container bgf">
	<!-- 课程信息 -->
		<div class="my-course-info col-sm-20">
			<div class="col-sm-5 col-xs-10 course-img col-lg-5">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->thumb_big)){; ?>
			<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->thumb_big); ?>" alt="" />

			<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->course_type==3){; ?>
			<div class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></div>
			<?php }elseif((SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->course_type == 2)){; ?>
			<div class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></div>
			<?php }; ?>
			<!--  录播课与线下课
            <div class="g-icon3" style="display:none;">录播课</div>
            <div class="taped-icon">线下课</div>
            -->
            <?php }else{; ?>
			<img src="<?php echo utility_cdn::img('/assets_v2/img/about-img.png'); ?>" alt="" />
			<?php }; ?>
			</div>
			<div class="col-sm-10 col-xs-10">
				<h1 class="fs16">
					<a href="#" title="">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->thumb_big)){; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->title; ?>
						<?php }else{; ?>
						请设置
						<?php }; ?>
					</a>
				</h1>
				<p class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->name; ?>
			<?php /*	<span class="ml30">助教：旺旺</span> */?>
				</p>
			<?php /*
				<div class="s-course-pf fs14">
					<dl>
						<dd></dd>
						<dd></dd>
						<dd></dd>
						<dd></dd>
						<dd></dd>
					</dl>
					（5分）
				</div>
				*/?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->course_type)&&SlightPHP\Tpl::$_tpl_vars["courseInfo"]->data[0]->course_type!=2){; ?>
				<p class="fs14"><?php echo tpl_modifier_tr('学生','site.teacher'); ?>（<?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->max_user; ?>）<?php echo tpl_modifier_tr('人','site.teacher'); ?>
				<?php }; ?>
				<?php /*<span class="ml30">高能100</span> */?>
				</p>
				<p class="my-progress-subtip"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["t_chart"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["t_chart"]; ?><?php }else{; ?><?php echo tpl_modifier_tr('第','site.teacher'); ?>0<?php echo tpl_modifier_tr('章','site.teacher'); ?><?php }; ?></p>
				<div class="my-progress">
				   <div class="my-progress-bar" role="progressbar" aria-valuenow="<?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?>"
				      aria-valuemin="0" aria-valuemax="100" style="width: <?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?>%;">
				      <span><?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?>%</span>
				   </div>
				</div>
				<p>
					<span class="c-fl fs14"><?php echo tpl_modifier_tr('课程进度','site.teacher'); ?></span>
					<span class="c-fr fs14"><?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["listPlans"]->data); ?><?php echo tpl_modifier_tr('章','site.teacher'); ?></span>
				</p>
			</div>
		</div>
	<!-- /课程信息 -->
	</div>
	<div class="container ptb35 bgf mt15">
	<!-- 课程统计与下载资料 -->
		<!-- btn -->
		<div class="col-sm-20 col-xs-20">
			<ul class="clearfix fs18 s-list-tab-btn">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["lastUpdated"])){; ?>
				<li class="col-sm-2 col-xs-10 z-active pd0" style="display:block">
					<a href="javascript:;">课程统计</a>
					<i></i>
				</li>
				<?php }; ?>
				<li class="col-sm-3 col-xs-10 pd0">
					<a href="javascript:void(0);"><?php echo tpl_modifier_tr('上传资料','site.teacher'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["countAtt"]; ?>)</a>
					<i></i>
				</li>
			</ul>
		</div>
		<!-- /btn -->
		<!-- tab-content -->
		<div class="s-tab-content-box col-md-20 col-xs-20">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["lastUpdated"])){; ?>
			<div class="s-tab-content pb20" style="display:block;">
				<h1 class="fs12 ter lh22 fcg9">
					数据更新时间：<?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["lastUpdated"]; ?>
				</h1>
				<dl class="clearfix n-article-static">
					<dd class="bgf7 clearfix tac col-md-20 pd0">
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0">到课率</div>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0">点赞</div>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0">发言</div>
						<div class="col-lg-2 col-md-2 hidden-sm col-xs-2 pd0 hidden-xs">举手</div>
						<div class="col-lg-3 col-md-3 hidden-sm col-xs-3 pd0 hidden-xs">讨论区</div>
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-8 pd0">随堂测试正确率</div>
						<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">咨询回答率</div>
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-3 pd0">时长</div>
					</dd>
					<dd class="clearfix tac col-md-20 pd0">
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["attendance"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["attendance"]; ?>%<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["zan"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["zan"]; ?>次<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["call"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["call"]; ?>次<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-2 col-md-2 hidden-sm col-xs-2 pd0 hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["handup"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["handup"]; ?>次<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-3 col-md-3 hidden-sm col-xs-3 pd0 hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["discuss"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["discuss"]; ?>条<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-8 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["correct"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["correct"]; ?>%<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-3 col-md-3 hidden-sm hidden-xs"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["answerRate"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["answerRate"]; ?>%<?php }else{; ?>-<?php }; ?></div>
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-3 pd0"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["vvRecord"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classPlanStat"]["vvRecord"]; ?>分钟<?php }else{; ?>-<?php }; ?></div>
					</dd>
				</dl>
			</div>
			<?php }; ?>
			<div class="s-tab-content"  style="display:none;">
					<div class="s-tab-contnet-tp col-sm-20 col-xs-20">
						<label class="fs14">
							<input type="checkbox" name="chks[]" /> <?php echo tpl_modifier_tr('全选','site.teacher'); ?>
						</label>
						<button id="upload_data"><?php echo tpl_modifier_tr('上传资料','site.teacher'); ?></button>
						<button class="c-fr" id="delplanatt"><?php echo tpl_modifier_tr('删除资料','site.teacher'); ?></button>
					</div>
				<ul class="course-file-load teacher-course-file-load clearfix">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["listAtt"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["listAtt"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					<li class="col-md-5 col-xs-20" style="margin-bottom: 30px;">
						<label>
							<input type="checkbox" name="chk[]" class="c-fl mt10 planAtt"  planAttid = "<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->planattid; ?>"/>
							<span class="c-fl" style="margin-right: 20px;">
								<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->thumb)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->thumb; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?><?php }; ?>" style="width:30px;">
							</span>
							<span class="c-fl brief-word-icon"></span>
							<div class="brief-introduction c-fl">
								<p class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></p>
								<?php /*<p class="fs12">2015-09-11 1.8M</p>*/?>
							</div>
						</label>
					</li>
					<?php }; ?>
					<?php }; ?>
				</ul>
			</div>
		</div>
		<!-- /tab-content -->
<!-- /课程统计与下载资料 -->
<!-- 章节目录 -->
	<div class="col-sm-20 col-xs-20 course-article">
		<!-- btn -->
			<ul class="clearfix fs18 course-tab-btn">
				<li class="col-sm-2 col-xs-10 z-tab-active pd0">
					<a href="javascript:;"><?php echo tpl_modifier_tr('章节目录','site.teacher'); ?></a>
					<i></i>
				</li>
				<li class="col-sm-2 col-xs-10 pd0">
					<a href="javascript:;"><?php echo tpl_modifier_tr('学生列表','site.teacher'); ?></a>
					<i></i>
				</li>
			</ul>
		<!-- /btn -->
		<div class="wrap-detail-list">
			<div class="c-article-content" style="display:block;">
				<ul class="course-article-content col-sm-20 col-xs-20 clearfix fs14">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["listPlans"]->data)){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["listPlans"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					<li>
						<dl class="clearfix">
							<dt class="col-sm-3 col-xs-6"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_name; ?><i></i></dt>
							<dd class="col-sm-5 col-xs-6">

							<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==3){; ?>
							<?php /*已完结加在这里*/?>
							<?php }; ?>

							<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_descipt; ?> &nbsp;
								<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_class !=SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_plan){; ?>
									(<?php echo tpl_modifier_tr('代课','site.teacher'); ?> :
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_plan->real_name)){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_plan->real_name; ?> )
									<?php }else{; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_plan->name; ?>)
									<?php }; ?>
								<?php }else{; ?>
								<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id==1){; ?>
							<dd class="col-sm-4 col-xs-8 pd0 hidden-xs">
							<?php if(date("Y",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time))==date("Y",time())){; ?>
							<?php echo date("m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time)); ?>
							<?php }else{; ?>
							<?php echo date("Y-m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time)); ?>
							<?php }; ?>
							<i></i></dd>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->type_id==2)){; ?>
							<dd class="col-sm-4 col-lg-2 col-xs-5 pd0">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->video_total_time)){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->video_total_time; ?>
							<?php }else{; ?>
							<?php }; ?>
							<i></i></dd>
							<?php }else{; ?>
							<dd class="col-sm-4 col-xs-8 pd0">
							<?php if(date("Y",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time))==date("Y",time())){; ?>
							<?php echo date("m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time)); ?>
							<?php }else{; ?>
							<?php echo date("Y-m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time)); ?>
							<?php }; ?>
							<?php /*<dd class="col-sm-4 col-lg-2">*/?>
							<?php /*	<button class="now-study-tip">录播课<span></span></button> */?>
								<i></i>
							</dd>
							<?php }; ?>

							<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==2){; ?>
							<dd class="col-sm-4">
							<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title="" target="_blank">
								<button class="now-study-tip">
									<span class="c-fl"></span><?php echo tpl_modifier_tr('正在上课','LearningCenter'); ?>
								</button>
							</a>
							<i></i></dd>
							<?php }else{; ?>
							<dd class="col-sm-4 hidden-xs">
								<i></i>
								<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==3){; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id==1){; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title="" target="_blank">
									<button class="end-study-tip tel">
										<span class="c-fl"></span>
										<?php echo tpl_modifier_tr('观看视频','LearningCenter'); ?>
									</button>
								</a>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->type_id==2&&SlightPHP\Tpl::$_tpl_vars["v"]->status=='3')){; ?>
								<a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title="" target="_blank">
									<button class="end-study-tip tel">
										<span class="c-fl"></span>
										<?php echo tpl_modifier_tr('可回看','LearningCenter'); ?>
									</button>
								</a>
								<?php /*
								<button class="now-study-tip">
									<span class="c-fl"></span>
									<?php echo tpl_modifier_tr('正在学习','LearningCenter'); ?>
								</button>
								暂无视频
								<?php echo tpl_modifier_tr('暂无视频','LearningCenter'); ?>
								*/?>
									<?php }; ?>
								<?php }else{; ?>

								<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id==2){; ?>
								<?php echo tpl_modifier_tr('暂无视频','LearningCenter'); ?>
								<?php }; ?>

								<?php }; ?>
							</dd>
							<?php }; ?>

							<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_class ==SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_plan){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id!=3){; ?>
							<dd class="col-sm-4 details-btn">
					<?php /*		<?php echo tpl_modifier_tr('详情','site.teacher'); ?> */?>
								<?php /*	<a href="new-classroom-statistics.html" title="">课堂统计</a> */?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==3){; ?>
									<a href="/user.teacher.part/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>/2" target="_blank" class="view-stats-btn hidden-xs hidden-sm"><?php echo tpl_modifier_tr('视频管理','site.teacher'); ?>｜</a>
									<?php }else{; ?>
									<a href="/user.teacher.upload/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>/2" target="_blank" class="view-stats-btn hidden-xs hidden-sm"><?php echo tpl_modifier_tr('上传视频','site.teacher'); ?>｜</a>
									<?php }; ?>

									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id!=2){; ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_class ==SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_plan){; ?>
												<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==3){; ?>
									<a href="/course.plan.start/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title="" class="hidden-xs hidden-sm"><?php echo tpl_modifier_tr('继续上课','site.teacher'); ?>|</a>
								<!--<a href="javascript:void(0);" onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->class_id; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>) title="" ><?php echo tpl_modifier_tr('继续上课','site.teacher'); ?>|</a>-->
									<a href="/course.stat.getPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" target="_blank" title="">课堂统计</a>
												<?php }else{; ?>
													<?php if(date("Ymd",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time))<=date("Ymd",time())){; ?>
												<!--	 <a href="javascript:void(0);" onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->class_id; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>) title=""><?php echo tpl_modifier_tr('开始上课','site.teacher'); ?>｜</a>-->
									<a href="/course.plan.start/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title=""><?php echo tpl_modifier_tr('开始上课','site.teacher'); ?>｜</a>
                                <?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id!=2){; ?>
                            <a href="/teacher.manage.plan/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title=""><?php echo tpl_modifier_tr('备课','site.teacher'); ?></a>
                            <?php }; ?>
                            <?php }; ?>
                        <?php }; ?>
                <?php }else{; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status!=3){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_class ==SlightPHP\Tpl::$_tpl_vars["v"]->fk_user_plan){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id!=2){; ?>
                <a href="/teacher.manage.plan/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" title=""><?php echo tpl_modifier_tr('备课','site.teacher'); ?></a>
                            <?php }; ?>
                            <?php }; ?>
                        <?php }; ?>
                <?php }; ?>


            <div class="details-content-tip col-sm-5">
                <span class="z-article-jianjiao"></span>
            <?php /*	<a href="new-classroom-statistics.html" title="">课堂统计</a>
                */?>
            </div>
        </dd>
        <?php }else{; ?>
        <dd class="col-sm-4 details-btn visible-lg">
        </dd>
        <?php }; ?>
        <?php }else{; ?>
        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type_id!=3){; ?>
        <dd class="col-sm-4 details-btn visible-lg">
<?php /*		<?php echo tpl_modifier_tr('详情','site.teacher'); ?> */?>
            <?php /*	<a href="new-classroom-statistics.html" title="">课堂统计</a> */?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==3){; ?>
                <a href="/user.teacher.part/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>/2" target="_blank" class="view-stats-btn"><?php echo tpl_modifier_tr('视频管理','site.teacher'); ?></a>
                <?php }else{; ?>
                <a href="/user.teacher.upload/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>/2" target="_blank" class="view-stats-btn"><?php echo tpl_modifier_tr('上传视频','site.teacher'); ?></a>
                <?php }; ?>
            <div class="details-content-tip col-sm-5">
                <span class="z-article-jianjiao"></span>
            <?php /*	<a href="new-classroom-statistics.html" title="">课堂统计</a>
                */?>
            </div>
        </dd>
        <?php }else{; ?>
        <dd class="col-sm-4 details-btn visible-lg">
        </dd>
        <?php }; ?>
        <?php }; ?>
    </dl>
</li>
<?php }; ?>
<?php }; ?>
<?php /*
<li>
    <dl>
        <dt class="col-sm-2">第一章<i></i></dt>
        <dd class="col-sm-4">小升初模拟题库<i></i></dd>
        <dd class="col-sm-2">09月17日   19：00<i></i></dd>
        <dd class="col-sm-2">代课：喵喵老师<i></i></dd>
        <dd class="col-sm-2">未开课</dd>
    </dl>
</li>
<li>
    <dl>
        <dt class="col-sm-2">第一章<i></i></dt>
        <dd class="col-sm-4">小升初模拟题库<i></i></dd>
        <dd class="col-sm-2">
            <button class="now-study-tip">正在学习<span></span></button>
            <i></i>
        </dd>
        <dd class="col-sm-2"><i></i></dd>
        <dd class="col-sm-2">进入课堂</dd>
    </dl>
</li>
<li>
    <dl>
        <dt class="col-sm-2">第一章<i></i></dt>
        <dd class="col-sm-4">小升初模拟题库<i></i></dd>
        <dd class="col-sm-2">
            <button class="end-study-tip tel">已学习<span></span></button>
            <i></i>
        </dd>
        <dd class="col-sm-2"><i></i></dd>
        <dd class="col-sm-2 details-btn">详情
            <div class="details-content-tip col-sm-5">
                <span class="z-article-jianjiao"></span>
                <a href="new-classroom-statistics.html" title="">课堂统计</a>
                <a href="javascript:;" class="view-stats-btn">视频管理</a>
                <a href="#" title="">继续上课</a>
            </div>
        </dd>
    </dl>
</li>
<li>
    <dl>
        <dt class="col-sm-2">第一章<i></i></dt>
        <dd class="col-sm-4">小升初模拟题库<i></i></dd>
        <dd class="col-sm-2">09月17日   19：00<i></i></dd>
        <dd class="col-sm-2">代课：喵喵老师<i></i></dd>
        <dd class="col-sm-2">未开课</dd>
    </dl>
</li>
<li>
    <dl>
        <dt class="col-sm-2">第一章<i></i></dt>
        <dd class="col-sm-4">小升初模拟题库<i></i></dd>
        <dd class="col-sm-2">
            <button class="now-study-tip">正在学习<span></span></button>
            <i></i>
        </dd>
        <dd class="col-sm-2"><i></i></dd>
        <dd class="col-sm-2">进入课堂</dd>
    </dl>
</li>
*/?>
</ul>
</div>
<div class="c-article-content">
<div class="stuent-list-cont-tp fs14 mb10">
<div class="lt">
    <span>共<?php echo SlightPHP\Tpl::$_tpl_vars["totalStudent"]; ?>人</span>
    <?php if(empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?>
    <a href="/phpexcel/coursedetailstudent?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>">导出Excel</a>
    <?php }else{; ?>
    <a href="/phpexcel/coursedetailstudent?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>&sedata=<?php echo SlightPHP\Tpl::$_tpl_vars["sedata"]; ?>">导出Excel</a>
    <?php }; ?>
</div>
<div class="rt search-students col-sm-5 col-xs-10">
    <input class="lt col-sm-16 col-xs-16" placeholder="<?php echo tpl_modifier_tr('输入学生姓名','site.teacher'); ?>" name="stud[]" type="text" id="searchdata"
    value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["sedata"]; ?><?php }; ?>"
    />
    <button class="rt col-sm-4 col-xs-4"></button>
    <a class="close-btn" href="javascript:void(0)"></a>
</div>
</div>
<table class="stuent-list-content col-sm-20 col-xs-20" id="student_list">
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ret"]->data)){; ?>
<thead>
    <tr class="col-xs-20 col-sm-20 pd0">
        <th class="col-sm-3 col-xs-7 pd0"><?php echo tpl_modifier_tr('姓名','site.teacher'); ?></th>
        <th class="col-sm-3 hidden-xs"><?php echo tpl_modifier_tr('性别','site.teacher'); ?></th>
        <th class="col-sm-3 hidden-xs"><?php echo tpl_modifier_tr('地区','site.teacher'); ?></th>
        <th class="col-sm-4 col-xs-7 pd0"><?php echo tpl_modifier_tr('手机号','site.teacher'); ?></th>
        <th class="col-sm-5 pd0 hidden-xs"><?php echo tpl_modifier_tr('报名时间','site.teacher'); ?></th>
        <th class="col-sm-2 col-xs-6 pd0">操作</th>
    </tr>
</thead>
<tbody id="studentList">
    <?php foreach(SlightPHP\Tpl::$_tpl_vars["ret"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
    <tr>
        <td class="col-sm-3 col-xs-7 pd0">
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->thumb_med)){; ?>
            <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->thumb_med); ?>" alt="" />
            <?php }else{; ?>
            <img src="<?php echo utility_cdn::img('/assets_v2/img/defaultPhoto.gif'); ?>" alt="" />
            <?php }; ?>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name)){; ?>
            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name; ?>
            <?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->name))){; ?>
            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->name; ?>
            <?php }else{; ?>
            <?php echo tpl_modifier_tr('未设置','site.teacher'); ?>
            <?php }; ?>
        </td>
        <td class="col-sm-3 hidden-xs">
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender)){; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender==1){; ?>
                <?php echo tpl_modifier_tr('男','site.teacher'); ?>
                <?php }else{; ?>
                <?php echo tpl_modifier_tr('女','site.teacher'); ?>
                <?php }; ?>
            <?php }else{; ?>
            <?php echo tpl_modifier_tr('未设置','site.teacher'); ?>
            <?php }; ?>
        </td>
        <td class="col-sm-3 hidden-xs">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province)){; ?>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province==SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city)){; ?>
            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?>
            <?php }else{; ?>
            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?>
            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city; ?>
            <?php }; ?>
        <?php }else{; ?>
            <?php echo tpl_modifier_tr('未设置','site.teacher'); ?>
        <?php }; ?>
        </td>
        <td class="col-sm-4 col-xs-7 pd0">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile)){; ?>
        <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile; ?>
        <?php }else{; ?>
        <?php echo tpl_modifier_tr('未获取到','site.teacher'); ?>
        <?php }; ?>
        </td>
        <td class="col-sm-5 col-xs-6 hidden-xs">
            <?php echo date("Y-m-d",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->create_time)); ?>
        </td>
        <td class="col-sm-2 col-xs-6 pd0" >
            <a href="/course.stat.GetStudentPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->user_id)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->user_id; ?><?php }; ?>" target="_blank" title="">课堂统计</a>
        </td>
    </tr>
    <?php }; ?>
<?php }else{; ?>
    <div class="col-sm-20 col-xs-20 tac fs14 cGray">
        <p><img src="<?php echo utility_cdn::img('/assets_v2/img/tanhao.png'); ?>">&nbsp;&nbsp;
        <?php if(SlightPHP\Tpl::$_tpl_vars["showTag"] == 1){; ?>
        没有要找的学生哦
        <?php }else{; ?>
        您还没有学生报名哦！
        <?php }; ?>
        </p>
    </div>
<?php }; ?>
</tbody>

</table>
<!-- page -->
				<div class="col-sm-20 col-xs-20 mt15 page-list" id="pagepage">

				</div>
	<script>
		pageAjax("pagepage","/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>,"studentList");
	</script>
			<!-- /page -->
			</div>
		</div>
	</div>
<!-- /章节目录 -->
	</div>
</section>
<!-- /content -->
	<div id="rightWindow"></div>
	<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
   <?php echo tpl_function_part("/site.main.footer"); ?>
   </div>
</body>
</html>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.raphael.js'); ?>" type="text/javascript"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.cirls.js'); ?>" type="text/javascript"></script>
<div class="update-box-index">
	<div class="index-teac-update" id="index-teac-update">
		<div class="index-marcen">
			<p><img src="/assets_v2/img/duochangjing.png"><span>多场景上课模式</span></p>
			<p><img src="/assets_v2/img/suitang.png"><span>随堂测验</span></p>
			<p><img src="/assets_v2/img/zhibohudong.png"><span>直播互动</span></p>
	   </div>
	   <div class="index-marbut">
		<!--    <input type="button" onclick="down()" value="立即下载"> -->
		   <a type="button" href='<?php echo SlightPHP\Tpl::$_tpl_vars["host"]; ?>' onclick="down()" target="_blank">立即下载</a>

	   </div>
	</div>
   <div class="index-teac-bg" id="index-teac-bg" style="display:none;">
	<div class="title">
		<p>是否安装</p>
	</div>
	<div class="teac-content">
		<p>手动安装"云课教师助手"</p>
		<span>提示:下载后,找到下载包文件,双击进行手动安装</span>
		<div class="content-but">
			<input type="button" onclick="window.location.reload(true)" value="安装完成">
			<input type="button" value="遇到问题">
		</div>
	</div>
</div>
</div>

<style>
	.update-box-index{ display:none;}
    .layui-layer-setwin .layui-layer-close2{ top:2px;right:0;background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .layui-layer-setwin .layui-layer-close2:hover{ background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .index-teac-update{ background:url(/assets_v2/img/tcbg.png)no-repeat;width:489px;height:368px;}
    .index-teac-update{ background:url(/assets_v2/img/tcbg.png)no-repeat;width:489px;height:368px;}
    .index-teac-update p{ float:left;width:100px;margin-left:50px;text-align:center; }
    .index-teac-update p span{ float:left;padding-top:5px;font-size:14px;color:gray; }
    .index-teac-update p:nth-child(2) span{ padding-left:22px;}
    .index-teac-update p:nth-child(3) span{ padding-left:22px;}
    .index-marbut{ text-align:center;margin-top:30px;float:left;width:100%; }
    .index-marbut a{ width:100px;height:35px;line-height:35px;text-align:-webkit-center;background:#ffa81d;border-radius:3px;color:#fff;font-size:14px;display: -webkit-inline-box; }
    .index-marbut a:hover{ background:#ffa81d;opacity:0.8}
    .index-marcen{ margin-top:40%;float:left;  }
	.teac-content{ background:#fff;text-align:center;float:left;width:100% }
	.teac-content p{ padding-top:40px;color:#000;font-size:16px; }
	.teac-content span{ padding-top:20px;float:left;width:100%;color:gray; }
	.content-but{ margin-top:45px;float:left;width:100%; }
	.content-but input{ width:100px;height:30px;border-radius:3px;background:#f7f7f7;color:#333;border:1px solid #ccc; }
	.content-but input:nth-child(1){ background:#ffa81e;color:#fff;margin-right:20px;border:none; }
	.index-teac-bg{ float:left;width:100%;height:250px; }
	.index-teac-bg .title{ background:#f7f7f7;width:100%;height:35px; }
	.index-teac-bg .title p{ color:gray;font-size:14px;line-height:35px;padding-left:10px; }
</style>
<script>
    var flag = 0;
    function checkCallback(data){
        if(data["code"]==0){
          flag = 1;
//            jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=launch&user_name=<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>&user_token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&course_id=&class_id=&plan_id=");
        }else {

        }
    }
    function launchCallback(data){
        console.log(data['code']);
    }
	function down(){
		$('#index-teac-update').hide();
		//$('#index-teac-bg').show();
	}
	//关闭弹窗
	function close(){

	}
    function checks_st(plan,cla,course){
		check();
        if(flag==1){
            jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=launch&user_name=<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>&user_token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&course_id="+course+"&class_id="+cla+"&plan_id="+plan);
        }
		if(flag==0){
				layer.open({
				type:1,
				title:false,
				area: ['489px',''],
				content: $('.update-box-index')
		});
		}
    }
    function check(){
        jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=check");
    }
    $(document).ready(function(){
        check();
    });
	function down(){
		 window.location.reload();
	}
</script>


<script type="text/javascript">
$(function() {
	var getUrl = null;
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?>
	getUrl="<?php echo SlightPHP\Tpl::$_tpl_vars["sedata"]; ?>";
	<?php }; ?>

	var studentSelected = <?php echo SlightPHP\Tpl::$_tpl_vars["studentSelected"]; ?>;
	if(getUrl != null || studentSelected == 1){
	    $(".course-tab-btn li:last").addClass("z-tab-active").siblings().removeClass("z-tab-active");
	    $(".wrap-detail-list>div:last").show().siblings().hide();
	    $(".search-students input").val(getUrl)
		if(studentSelected == 1){
			$(".search-students button").show();
			$(".search-students .close-btn").hide();
		}else{
			$(".search-students button").hide();
			$(".search-students .close-btn").show();
		}
	}else{
	    $(".course-tab-btn li:first").addClass("z-tab-active").siblings().removeClass("z-tab-active");
	    $(".wrap-detail-list>div:first").show().siblings().hide();
	}

    $(".search-students .close-btn").click(function(){
        $(".search-students input").val('');
        $(".search-students .close-btn").hide();
        $(".search-students button").show();
		window.location.href="/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/1";//页面跳转并传参
    });
//学习进度章数
	$(".my-progress-subtip").css("width",$(".my-progress-bar span").html());

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

	if($(window).width() < 760) {
		$(".my-progress-subtip").css("marginTop","0");
	}

	  $("#upload_data").click(function(){
	    layer.open({
	        type: 2,
	        title:["<?php echo tpl_modifier_tr('上传资料','site.teacher'); ?>",'color:#fff;background:#198fee'],
	        area: ['480px', '380px'],
	        shadeClose: true,
			content: '/teacher.manage.batchupload.<?php echo SlightPHP\Tpl::$_tpl_vars["planId"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>'
	    });
	  });

	$("#searchdata").keydown(function(e){
		var curKey = e.which;
		if(curKey == 13){
			var sedata = $("#searchdata").val();
			window.location.href="/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>?sedata="+sedata;//页面跳转并传参
		}
	});
    $(".search-students button").click(function(){
		var sedata = $("#searchdata").val();
		window.location.href="/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>?sedata="+sedata;//页面跳转并传参
    });

	$("#delplanatt").click(function(){
		var planid;
		var ids = [];
		var id ;
		$(".planAtt:checked").each(function(i,item){
			id = $(this).attr("planAttid");
			ids.push(id);
		});
		if(0==ids.length){
			layer.msg("<?php echo tpl_modifier_tr('请选择要删除的课件','site.teacher'); ?>");
			return false;
		}
		planid = "1";
		classid = "<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>";
		console.log("planatt"+ids);
		if(confirm("<?php echo tpl_modifier_tr('您确定要删除这个课件吗','site.teacher'); ?>")){
			$.post("/teacher.manage.DelPlanAttAjax",{ planattid:ids,planid:planid,classid:classid },function(r){
				if(r.error){
					alert(r.error);
					return false;
				}
				if(r){
					location.reload();
					return false;
				}
			},"json");
		}
		return false;
	});

});
function GetUrl(name){
	var reg=new RegExp("(^|&)"+name+"=([^&]*)(&|$)");
	var r=window.location.search.substr(1).match(reg);
	if(r!=null){
		return unescape(r[2]);
	}
	return null;
}
</script>
<script>
	var totalPage=<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>;
	function studentList(_path,_page,_num){
		$.post(""+_path+"",{ page:_page,num:_num,act:'ajaxStudent' },function(r){
			var _html = '';
			if(r.code==1){
				for (var i = 0; i < r.data["data"].length; i++) {
					_html += '<tr>';
					_html += '<td class="col-sm-3 col-xs-7 pd0">';
					if(r.data["data"][i]["user_info"]["thumb_med"]!="") {
						_html += '<img src = "'+r.data["data"][i]["user_info"]["thumb_med"]+'" alt = "" / >';
					}else {
					_html += '<img src = "<?php echo utility_cdn::img("/assets_v2/img/defaultPhoto.gif"); ?>" alt = "" / >';
					}
					if(r.data["data"][i]["user_info"]["real_name"]!="") {
						_html += r.data["data"][i]["user_info"]["real_name"];
					}else if(r.data["data"][i]["user_info"]["name"]!=""){
						_html += r.data["data"][i]["user_info"]["name"];
					}else {
						_html += '未设置';
					}
					_html += '</td>';
					_html += '<td class="col-sm-3 hidden-xs">';
					if(r.data["data"][i]["user_info"]["gender"]>0){
						if(r.data["data"][i]["user_info"]["gender"]=="1"){
							_html += '男';
						}else{
							_html += '女';
						}
					}else{
						_html += '未设置';
					}
					_html += '</td>';
					_html += '<td class="col-sm-3 hidden-xs">';
					if (r.data["data"][i]["user_info"]["province"]!=""){
						if(r.data["data"][i]["user_info"]["city"] !="" && r.data["data"][i]["user_info"]["province"]==r.data["data"][i]["user_info"]["city"]){
							_html += r.data["data"][i]["user_info"]["province"];
						}else{
							_html += r.data["data"][i]["user_info"]["province"];
							_html += r.data["data"][i]["user_info"]["city"];
						}
					}else{
						_html += '未设置';
					}
					_html += '</td>';
					_html += '<td class="col-sm-4 col-xs-7 pd0">';
					if(r.data["data"][i]["user_info"]["mobile"]!=""){
						_html += r.data["data"][i]["user_info"]["mobile"];
					}else{
						_html += '未获取到';
					}
					_html += '</td>';
					_html += '<td class="col-sm-5 col-xs-6 hidden-xs">';
					_html += r.data["data"][i]["create_time"];
					_html += '</td>';
					_html += '<td class="col-sm-2 col-xs-6 pd0" >';
					_html += '<a href="/course.stat.GetStudentPlanStatByPid/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>/';
					if(r.data["data"][i]["user_info"]["user_id"]!=""){
						_html += r.data["data"][i]["user_info"]["user_id"]
					}
					_html += '" target="_blank" title="">课堂统计</a>';
					_html += '</td>';
					_html += '</tr>';
				}
				totalPage= r.data["total"];
			}else{
				_html += '<div class="col-sm-20 col-xs-20 tac fs14 cGray">';
				_html += '<p><img src="<?php echo utility_cdn::img('/assets_v2/img/tanhao.png'); ?>">&nbsp;&nbsp;';
				var showTag = <?php echo SlightPHP\Tpl::$_tpl_vars["showTag"]; ?>;
				if(showTag == 1) {
					_html += '没有要找的学生哦';
				}else{
					_html += '您还没有学生报名哦';
				}
			_html += '</p>';
			_html += '</div>';
			}
			$("#studentList").html(_html);
		},'json');
		$("#pagepage").html('');
		pageAjax("pagepage","/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>",20,_page,totalPage,"studentList");
	}
</script>