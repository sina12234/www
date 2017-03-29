<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>评论管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/site.main.header"); ?>
	<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>

<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- tpInfo -->
<?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<!-- bd -->
<section class="pb40 divSelectFirstVal" id="divSelectFirstVal">
	<div class="container">
		<div class="row">
			<section>
				<?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
				<!-- rt -->
				<section>
					<div class="col-md-16 pr0 col-xs-20 parinfoWidth">
						<div class="gn-base-ct clearfix">
							<h1 class="base-title mb30">评价管理</h1>
							<dl class="col-md-20 course-comment clearfix fs14">
								<dt class="c-fl">共<?php if(isset(SlightPHP\Tpl::$_tpl_vars["data"]['totalSize'])){; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['totalSize']; ?> <?php }else{; ?> 0 <?php }; ?> 个评论</dt>
								<dd class="c-fr">
									<div class="divselect divselect-32" id="search-time" style="width:120px;">
										<cite>
											<span class="cite-icon"></span>
											<span class="cite-text">全部时间</span>
										</cite>
										<dl>
											<dd>
												<a onclick="selectTime(this)" time="all" href="javascript:;">全部时间</a>
											</dd>
											<dd>
												<a onclick="selectTime(this)" time="week" href="javascript:;">最近一周</a>
											</dd>
											<dd>
												<a onclick="selectTime(this)" time="month" href="javascript:;">最近一个月</a>
											</dd>
										</dl>
									</div>
								</dd>
								<dd class="c-fr">
									<div class="divselect divselect-32" id="search-score" style="width:120px;">
										<cite>
											<span class="cite-icon"></span>
											<span class="cite-text">全部评分</span>
										</cite>
										<dl>
											<dd>
												<a onclick="selectScore(this)" score="all" href="javascript:;">全部评分</a>
											</dd>
											<dd>
												<a onclick="selectScore(this)" score="5" href="javascript:;">5分</a>
											</dd>
											<dd>
												<a onclick="selectScore(this)" score="4" href="javascript:;">4分</a>
											</dd>
											<dd>
												<a onclick="selectScore(this)" score="3" href="javascript:;">3分</a>
											</dd>
											<dd>
												<a onclick="selectScore(this)" score="2" href="javascript:;">2分</a>
											</dd>
											<dd>
												<a onclick="selectScore(this)" score="1" href="javascript:;">1分</a>
											</dd>
										</dl>
									</div>
								</dd>
							</dl>
							<ul class="col-md-20 p0 mt20 course-comment-info">
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["data"]['data']) && !empty(SlightPHP\Tpl::$_tpl_vars["data"]['data'])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["data"]['data'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
								<li class="clearfix">
									<div class="col-md-2 tac p0">
										<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userThumb']; ?>" userId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['commentId']; ?>" class="comment-user-img" alt="">
										<div class="user-comment-name">
											<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userName']; ?>
										</div>
									</div>
									<div class="col-md-12 comment-content p0">
										<p class="fs14 dGray mb10"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?></p>
										<p class="fs12 cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['time']; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['className']; ?>  <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['sectionName']; ?></p>
									</div>
									<div class="col-md-3 tac fs18 fecolor"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['studentScore']; ?>分</div>
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["v"]['replay']->pk_replay)){; ?>
										<a href="javascript:;" class="c-fr col-md-2 tac cBlue reply-comment-btn">回复</a>
									<?php }; ?>
								<!-- 老师回复 -->
									<div class="col-md-20 teacher-replay-infos p0">
										<div class="col-md-3"></div>
										<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['replay']->pk_replay)){; ?>
										<div class="col-md-17 bgf7 clearfix p10 mb10">
											<div class="col-md-16 p0">
												<span class="cBlue mr10">[<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['replay']->manage_name; ?>老师回复]</span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['replay']->contents; ?>
											</div>
											<div class="col-md-4 tar">
												<p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['replay']->replay_time; ?> &nbsp;&nbsp;</p>
												<a href="javascript:;" replayId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['replay']->pk_replay; ?>" onclick="deleteCommentReplay(this)" class="cBlue">删除回复&nbsp;&nbsp;</a>
											</div>
										</div>
										<?php }; ?>
								<!-- /老师回复 -->
									<!-- 回复评论 -->
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["v"]['replay']->pk_replay)){; ?>
										<div class="reply-course-comment clearfix col-md-20 mt10 pb10 pt10">
											<div class="col-md-18 ml30">
												<textarea placeholder="请输入回复内容（100字内）" onkeyup="this.value=this.value.replace( /^\s*/, '')" class="student-replay-comment col-md-20"></textarea>
											</div>
											<div class="col-md-18 tar ml30">
												<a href="havascript:;" class="mr10 cBlue cancle-reply-comment">取消</a>
												<button class="btn mt10" commentId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['commentId']; ?>" onclick="InsertCommentReplay(this)">确定</button>
											</div>
										</div>
									<?php }; ?>
									<!-- /回复评论 -->
									</div>
									<?php }; ?>
								</li>
								<?php }else{; ?>
								<div class="my-collect-no-class p30"><img src="/assets_v2/img/platform/pet3.png" alt="" /><p class="fs14" style="border-bottom:0;">暂时还没有评论哦！</p></div>
								<?php }; ?>
							</ul>
							<!-- page -->
							<div class="page-list" id="pagepage">
							<script>
									page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
							</script>
							<!-- /page -->
							</div>
						</div>
					</div>
				</section>
				<!-- /rt -->
			</section>
		</div>
	</div>
</section>
<!-- /bd -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.comment.js'); ?>"></script>
<script>
var Winwidth = $(window).width();
if( Winwidth <= 760){
	$('.parinfoWidth').css(
		'padding-left','0'
	)
}else{
	$('#search-score').css(
		'margin-right','20px'
	)
}
</script>
