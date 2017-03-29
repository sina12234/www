<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9;IE=8;IE=7;IE=edge;chrome=1">
	<title><?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?> - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
	<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - <?php echo SlightPHP\Tpl::$_tpl_vars["title"]; ?> - 云课 - 专业的在线学习平台">
	<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
	<meta name="description" content="<?php echo SlightPHP\Tpl::$_tpl_vars["teacherName"]; ?>-<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no"/>
	<!--[if lt IE 8]>
	<script src="<?php echo utility_cdn::js('/assets_v2/js/ie8.js'); ?>"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="/assets_v2/js/player/common.css">
	<link rel="stylesheet" type="text/css" href="/assets_v2/js/player/style.css">
	<link rel="stylesheet" type="text/css" href="/assets_v2/css/player.icon.css">
	<link rel="stylesheet" type="text/css" href="/assets_v2/layer/skin/layer.css">
	<script type="text/javascript" data-main="/assets_v2/js/player/main.js" src="/assets_v2/js/requirejs/require.js"></script>
</head>
<body>
	<input type="text" id="resellOrgId" style="display:none" value="<?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?>">
	<header class="header tac" id="header">
		<a href="#" class="fl h-course cLightgrayHover fs14" id="hCourse" data-tpl="tpl-h-course">
			<i class="icon icon-book ml10 mr10"></i>
			<span class="mr10 h-course-name">课程名称</span>
			<span class="mr10 h-course-class">班级</span>
			<span class="mr10 h-course-hour">课时</span>
			<span class="mr10 h-course-status fs12">课程类型</span>
		</a>
		<script id="tpl-h-course" type="text/html">
			<i class="icon icon-book ml10 mr10"></i>
			<span class="mr10 h-course-name">{ title }</span>
			<span class="mr10 h-course-class">{ class_name }</span>
			<span class="mr10 h-course-hour">{ section_name }</span>
			<span class="mr10 h-course-status fs12 { plan_status != 'finished' && 'isLive'  }">{ plan_status == 'finished'?'课程回放':'正在直播' }</span>
		</script>
		<a class="h-teacher cLightgrayHover fs14" id="hTeacher" href="#">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/player/defaultPhoto.png'); ?>" class="h-teacher-thumb align-mid mr5" id="hTeacherThumb">
			<span class="h-teacher-name ellipsis tal" id="hTeacherName">教师名</span>
			<span class="support-gift">本节共收到<b class="pl5 pr5 cYellow" id="">0</b>朵鲜花</span>
		</a>
		<!-- login info -->
		<div class="fr pr10 fs12">
			<div class="login-before login-info active">
				<a href="">登录</a><span class="pl5 pr5">|</span><a href="">注册</a>
			</div>
			<div class="login-after login-info" id="loginAfter">
				<span class="h-nav-o">
					<a class="user-name" id="userName" href="#">用户名</a>
					<ul class="h-nav">
						<li class="hidden-xs" style="display:none" id="navOrg"><a href="/org" target="_blank"><i class="icon icon-org"></i><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a></li>
                        <li class="hidden-xs" style="display:none" id="navTeacher"><a href="/teacher.course.timetable2" target="_blank"><i class="icon icon-org"></i><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a></li>
                        <li class="hidden-xs" style="display:none" id="navGrowth"><a href="/student.main.growth" target="_blank"><i class="icon icon-hat"></i><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a></li>
						<li><a href="/student.main.infobase" target="_blank"><i class="icon icon-edit"></i><?php echo tpl_modifier_tr('基础资料','site.header'); ?></a></li>
                        <li><a href="/student.order.myorder" target="_blank"><i class="icon icon-order"></i><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a></li>
                        <li><a href="/student.security.password" target="_blank"><i class="icon icon-safe"></i><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a></li>
                        <li><a href="/site.main.logout"><i class="icon icon-forbid"></i><?php echo tpl_modifier_tr('退出','site.header'); ?></a></li>
					</ul>
				</span>
				<span class="pr5">|</span><a href="http://jinye.gn100.com/user.message" target="_blank"><i class="icon icon-mail ml5"></i></a>
			</div>
		</div>
		<!-- 登录信息 end -->
	</header>
	<div class="content" id="content">
		<div class="main">
			<section class="player">
				<div id="player"></div>
			</section>
			<section class="sTool pl10">
				<span class="share-mobile share-box mr10" id="shareMobile" data-role="分享到手机">
					<i class="icon icon-mobile"></i>
					<div class="share-mobile-content share-content">
						<img src="<?php echo utility_cdn::js('/assets_v2/img/player/share-mobile-skin.png'); ?>">
						<i class="icon icon-close16x16 close"></i>
					</div>
				</span>
				<span class="share-sns share-box mr10" data-role="分享" id="shareSns">
					<i class="icon icon-share-small share-sns-btn"></i>
					<div class="share-sns-content share-content">
						<a href="javascript:void(0)" class="icon icon-share-qq share-qq" data-cmd="tqq" title="分享到QQ"></a>
						<a href="javascript:void(0)" class="icon icon-share-weixin share-weixin" data-cmd="weixin" title="分享到微信"></a>
						<a href="javascript:void(0)" class="icon icon-share-sina share-tsina" data-cmd="tsina" title="分享到新浪微博"></a>
						<i href="javascript:void(0)" class="icon icon-close12x12-deep close"></i>
					</div>
				</span>
				<span class="stool-comment mr10" id="stoolComment" style="display:none" data-role="评论"><i class="icon icon-comment"></i></span>
				<a href="/site.help.flash" target="_blank">检测助手</a>
				<div class="qute-note mt5 mr10 fr" id="qute-note">
				    <div class="wrap-note fl clear">
				        <span class="qute-time cGreen fl" id="qute-time">记笔记：</span>
				        <input type="text" class=" note-ipt" id="qute-input" placeholder="输入此刻你要记录的内容">
				    </div>
				    <div class="qute-btn fl" id="qute-btn">
				        <button class="mr5 button-green2 button-green2-hover2 button-md fl" id="qute-submit">保存</button>
				        <button class="button-md cDarkgray cWhite-hover fl" id="qute-cancel">取消</button>
				    </div>
				    <div class="qute-tip" id="quteTip" style="display:none"></div>
				</div>
			</section>
			<section class="curtain"></section>
		</div>
		<!-- 侧边栏 -->
		<aside class="aside active" id="aside">
			<!-- tab 触发列 -->
			<section class="tab-triggers">
				<a href="javascript:void(0)" id="asideSwitch" class="aside-switch">
					<i class="icon icon-arrow-left-gray icon-normal"></i>
					<i class="icon icon-arrow-right-gray icon-active"></i>
				</a>
				<ul id="tabTriggers">
					<li class="tab-trigger">
						<i class="icon icon-tab-summary icon-normal"></i>
						<i class="icon icon-tab-summary-active icon-active"></i>
						<p>简介</p>
					</li>
					<li class="tab-trigger">
						<i class="icon icon-tab-sec icon-normal"></i>
						<i class="icon icon-tab-sec-active icon-active"></i>
						<p>目录</p>
					</li>
					<li class="tab-trigger active">
						<i class="icon icon-tab-chat icon-normal"></i>
						<i class="icon icon-tab-chat-active icon-active"></i>
						<p>讨论</p>
					</li>
					<li class="tab-trigger">
						<i class="icon icon-tab-rank icon-normal"></i>
						<i class="icon icon-tab-rank-active icon-active"></i>
						<p>排名</p>
					</li>
					<li class="tab-trigger" id="noteTrigger">
						<i class="icon icon-tab-note icon-normal"></i>
						<i class="icon icon-tab-note-active icon-active"></i>
						<p>笔记</p>
					</li>
					<li class="tab-trigger support-courseware" style="display:none">
						<i class="icon icon-tab-datum icon-normal"></i>
						<i class="icon icon-tab-datum-active icon-active"></i>
						<p>课件</p>
					</li>
				</ul>
			</section>
			<!-- tab 触发列 end -->
			<ul class="tab-contents">
				<!-- 简介 -->
				<li class="tab-content summary fs14" id="summary" data-role="简介" data-tpl="tpl-summary"></li>
				<script id="tpl-summary" type="text/html">
	                <div class="summary-title pt10">
	                    <a href="{ courseUrl }" class="ellipsis" target="_blank"><span>{ courseName }</span><i class="hidden-lg icon icon-arrow-right-gray fr"></i></a>
					</div>
	                <div class="pt10 pb5">
	                    <h6 class="pb5">授课讲师：</h6>
	                    <div class="summary-teacher pb10">
	                        <a href="{ teacherUrl }" target="_blank">
	                            <img src="{ teacherImg ? teacherImg : '<?php echo utility_cdn::img('/assets_v2/img/player/defaultPhoto.png'); ?>' }">
	                            <p class="summary-teacher-name">{ teacherName }</p>
	                            <p class="summary-teacher-info fs12">{ teacherDesc ? teacherDesc : 'TA还没有写简介哦！' }</p></a>
	                    </div>
	                </div>
	                <div class="summary-content pt10 pb5">
	                    <h6 class="pb5">课程简介：</h6>
	                    <p>{ if courseDesc }{ courseDesc }{ else }还没有简介哦！{ /if }</p>
	                </div>
	            </script>
				<!-- 简介 end -->
				<!-- 目录 -->
				<li class="tab-content catalog" data-role="目录">
					<div class="tab-header">目录列表</div>
					<div class="tab-body" id="catalog" data-tpl="tpl-catalog" id="catalog"></div>
					<script id="tpl-catalog" type="text/html">
						{ each data as v }
							{ if v.status == 3 || v.status == 2 }
								<a { #v.plan_id == plan_id && 'class="active"' } data-planId="{ v.plan_id }" href="/course.plan.play/{ v.plan_id }{ resellOrgId }">
							{ else }
								<a href="#" { #v.status == 1 && 'class="bg-hover-none"' }></a>
							{ /if }
							    <p><strong { #v.status == 1 && 'style="color:#666666"' }>{ v.section_descipt }</strong>
							    	<span class="fr plan-type">
									{ if v.status == 1 }
										<?php echo tpl_modifier_tr('未开课','course.play'); ?>
									{ else if v.status == 2 }
										正在直播
									{ else }
										<i class="icon icon-video"></i>
									{ /if }
									</span>
							    </p>
							    <p>{ if v.type_id !=2 }<span class="time">{ v.start_time }</span>{ /if }</p>
							</a>
						{ /each }
					</script>
				</li>
				<!-- 目录 end -->
				<!-- 讨论 -->
				<li class="tab-content chat active" data-role="讨论">
					<div class="tab-header">
						<div>在线同学：1</div>
						<div style="display:none"></div>
					</div>
					<div class="tab-body">
						<ul class="chat-list"></ul>
						<script id="tpl-chatList" type="text/html">
							<li data-uid="{ uid }" data-mid="{ mid }" { if me }class="me"{ /if }>
								<div class="chat-photo"><img src="{ photoUrl }" title="{ name }"></div>
								<div class="chat-main">
									<p class="chat-name"><a href="lv{ lv }" title="{ lvName }" href="{ url }" target="_blank"></a>杨红月</p>
									<p class="chat-content">{ content }</p>
								</div>
							</li>
						</script>
						<div class="pattern">
							<p style="display:none" id>当前内容，仅老师可见</p>
							<p style="display:none">禁止讨论</p>
						</div>
						<span href="" class="new-msg-count" style="display:none"></span>
						<div class="chat-input">
							<div class="chat-header">
								<span class="emojis" id="emojis">
									<i class="icon icon-smile-face pointer fl" id="emojiTrigger"></i>
									<div class="emojis-list" id="emojisList"></div>
								</span>
								<div class="gift fl support-gift" id="gift" style="display:block">
									<div class="gift-send-flower gift-send hide" id="giftsFlower" data-type="1"><i class="icon-gift-flower"></i><span class="gift-count">0</span></div>
								</div>
								<div id="ask" ask="ask" style="display:block" class="fr hidden-xs">
                                    <span ask="ask" class="hand-icon hidden-xs" msg_type=2 style="display:none;"><?php echo tpl_modifier_tr('举手发言',"course.play"); ?></span>
                                    <span ask="cancel" class="handing-icon" style="display:none;"><?php echo tpl_modifier_tr('等待发言',"course.play"); ?></span>
                                    <span ask="stop" class="handed-icon" style="display:none;"><?php echo tpl_modifier_tr('停止发言',"course.play"); ?></span>
                                </div>
							</div>
							<div class="chat-body">
								<div class="chat-forbid sign-before" id="chatForbid"><span class="cYellow btn-sign"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></span><?php echo tpl_modifier_tr('参与互动讨论','course.play'); ?></div>
								<div class="chat-edit sign-after" id="chatEdit" style="display:none"></div>
							</div>
							<div class="chat-footer">
								<button class="fr button-md bgc-yellow cWhite cWhite-hover pointer mr5 mt5">发&nbsp;&nbsp;送</button>
							</div>
						</div>
					</div>
				</li>
				<!-- 讨论 end -->
				<!-- 排名 -->
				<li class="tab-content" data-role="排名">
					<div class="tab-header">学生排名</div>
					<div class="tab-body"></div>
				</li>
				<!-- 排名 end -->
				<!-- 笔记 -->
				<li class="tab-content note cGray" data-role="笔记">
					<div class="tab-header">课程笔记</div>
					<div class="tab-body por">
						<button id="note-add" class="fr fs12 note-add button-md button-gray button-green-hover sign-after" style="display:none">新建笔记</button>
                        <div class="no-note tac" id="no-note"><span>还没有任何笔记哦~</span></div>
                        <div class="note-edit-box" id="note-edit-box" style="display:none">
                            <div class="clearfix note-info"><span id="note-edit-time" class="note-time fl">8月20日 10:30</span></div>
                            <textarea id="note-edit-input" class="note-input"></textarea>
                            <span class="text-num fr"><span id="note-nowNum">0</span>/50</span>
                            <div class="clearfix"><button class="button-md cDarkgray cWhite-hover fr" id="note-edit-cancel">取消</button><button class="mr5 button-green2 button-green2-hover2 button-md fr" id="note-edit-submit">确定</button></div>
                        </div>
                        <ul class="note-contents clearfix" id="noteContents"></ul>
                        <div class="note-popup  tac" id="note-popup" style="display:none">
                            <div class="note-popup-title"><span class="fr cancel mr5 mt5 icon-hover"><i class="icon icon-close12x12-deep icon-normal"></i><i class="icon icon-close12x12 icon-active"></i></span></div>
                            <div class="note-popup-content fz14 cLightgray">删除这条笔记？</div>
                            <div class="clearfix note-popup-btn"><button class="mr5 button-green2 button-green2-hover2 button-md submit">确定</button><button class="button-md cDarkgray cWhite-hover cancel">取消</button></div>
                        </div>
                    </div>
				</li>
				<!-- 笔记 end -->
				<!-- 课件下载 -->
				<li class="tab-content courseware support-courseware" style="display:none" data-role="课件">
					<div class="tab-header">课件下载</div>
					<div class="tab-body" id="courseware" data-tpl="tpl-courseware"></div>
					<script id="tpl-courseware" type="text/html">
					{ if list.length>0 }
						{ each list as v }
							<a href="{ cdn }/{ v.attach }">
								<img src="{ if !v.thumb }<?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?>{ else }{ v.thumb }{ /if }">
								<span>{ v.title }</span>
								<i class="icon icon-download fr"></i>
							</a>
						{ /each }
					{ else }
						<div class="pt10 pb10 tac"><?php echo tpl_modifier_tr('暂时还没有课件呦',"course.play"); ?>～</div>
					{ /if }
					</script>
				</li>
				<!-- 课件下载 end -->
			</ul>
		</aside>
		<!-- 侧边栏 end -->
	</div>
</body>
</html>