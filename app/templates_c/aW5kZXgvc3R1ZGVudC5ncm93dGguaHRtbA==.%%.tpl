<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>成长首页 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="成长首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swiper.min.js'); ?>"></script>
<style>.p31{ padding:31px;}</style>
</head>
<body>
<!-- header -->
<?php echo tpl_function_part("/index.main.usernav/student"); ?>
<!-- /header -->
<!-- body -->
<section class="pd20">
<div class="container pos-rel">
<div class="row">
	<!-- left -->
	<?php echo tpl_function_part("/index.main.menu/student"); ?>
		<!-- /left -->
	<section id="index-growth">
		<!-- right -->
		<div class="col-md-16 col-xs-20">
			<!-- 成长值 -->
			<div class="mob-nav hidden-lg hidden-md swiper-container3">
				<ul class="swiper-wrapper" id="mob-nav">
					<li class="swiper-slide"><a href="/index.growth.entry" class="active">学习中心</a></li>
					<li class="swiper-slide"><a href="/index.student.course">我的课程</a></li>
					<li class="swiper-slide"><a href="/index.student.fav">我的收藏</a></li>
					<li class="swiper-slide"><a href="/index.task.studentTaskListShow">我的作业</a></li>
					<li class="swiper-slide"><a href="/index.student.discount">我的优惠券</a></li>
				</ul>
			</div>
			<div class="clearfix">
				<div class="s-grow-left-info col-md-14 clearfix">
					<div class="col-md-14 p0 col-xs-20 s-grow-left-info-title">
		    			<p class="fs18 mb30 col-xs-10 col-lg-20 lGray">
		    				欢迎您~<span class="fs14 cGray ml10"><?php echo SlightPHP\Tpl::$_tpl_vars["curr_date"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["curr_week"]; ?></span>
		    				<a target="_blank" href="//www.yunke.com/index.rank" class="fs12 cBlue ml20 hidden-xs hidden-sm">查看排行榜</a>
		    			</p>
		    			<div class="s-growth-progress-info fs12 cGray clearfix mb25 col-xs-10 col-lg-20 tar">
			    			<div class="gi-growth-val c-fl mr10">
			    				<div class="tac">升级需<em id="cha_score"><?php echo SlightPHP\Tpl::$_tpl_vars["cha_score"]; ?></em>点</div>
			                    <ul  id="wrap-progress-parents" class="growth-lv hidden-xs hidden-sm" title="<?php echo tpl_modifier_tr('您当前','LearningCenter'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?> <?php echo tpl_modifier_tr('经验值','LearningCenter'); ?>">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level_info"])){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["level_info"] as SlightPHP\Tpl::$_tpl_vars["score"]=>SlightPHP\Tpl::$_tpl_vars["level"]){; ?>
									<li style="display:none;">
										<span class="val-cirl-lv1" style="display:none;"><?php echo SlightPHP\Tpl::$_tpl_vars["score"]; ?></span>
										<span class="level-icon <?php echo SlightPHP\Tpl::$_tpl_vars["level"]; ?>"></span>
			                            <span class="g-progress"></span>
										<input type="hidden" name="level_count" value="<?php echo SlightPHP\Tpl::$_tpl_vars["level_count"]--; ?>">
									</li>
									<?php }; ?>
									<?php }; ?>
								</ul>
							</div>
							<input type="hidden" name="user_score" id="user_score" value="<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>">
		    			</div>
		    			<p class="fs12 dGray clearfix visible-lg c-fl col-lg-20">
		    			 	<span class="c-fl mr20"><i class="s-growth-integral c-fl mr5"></i>积分：<em><?php echo SlightPHP\Tpl::$_tpl_vars["userPoint"]; ?></em></span>
		    			 	<span class="c-fl"><i class="gift-flower-icon c-fl mr5"></i>鲜花：<em id="gift-total-num" class="gift-total-num"></em></span>
		    			 	<a target="_blank" href="https://www.yunke.com/index.rank.rule" class="fs12 c-fr mr30"><i class="s-help-icon mr5 c-fl"></i>获得积分</a>
		    			</p>
	    			</div>
					<div class="col-md-6 hidden-xs hidden-sm s-course-infos tal fs14">
	    				<p>已学课程：<span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["study_plan_count"]; ?></span>节</p>
	    				<p>已学时长：<span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span>小时</p>
	    				<p><em class="cRed">*</em>待完成作业：<span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["unFinishNum"]; ?></span>份</p>
	    			</div>
					<div class="gi-growth-calendar <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>s-growth-orgsign<?php }else{; ?>s-growth-hassign<?php }; ?> col-xs-20 hidden-lg hidden-md fs12 mt20">
						<h1 class="s-has-sign">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>
							<a href="javascript:;" class="signed"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
							<?php }else{; ?>
							<a href="javascript:;" class="sign-audo" onclick="SignAudio()"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></a>
							<a href="javascript:;" class="signed" style="display:none;"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></a>
							<?php }; ?>
						</h1>
						<div class="cl-xs-20 mt10 tac"><?php echo tpl_modifier_tr('连签','LearningCenter'); ?><i id="sign-combo" class="sign-combo"><?php echo SlightPHP\Tpl::$_tpl_vars["user_combo"]; ?></i><?php echo tpl_modifier_tr('日','LearningCenter'); ?></div>
					</div>
					<p class="fs12 dGray clearfix visible-xs visible-sm col-xs-20">
		    			 	<span class="c-fl mr20"><i class="s-growth-integral c-fl mr5"></i>积分:<?php echo SlightPHP\Tpl::$_tpl_vars["userPoint"]; ?></span>
		    			 	<span class="c-fl"><i class="gift-flower-icon c-fl mr5"></i>鲜花：<em id="gift-total-num" class="gift-total-num"></em></span>
		    			 	<a target="_blank" href="https://www.yunke.com/index.rank.rule" class="fs12 c-fr mr30"><i class="s-help-icon mr5 c-fl"></i>获得积分</a>
		    		</p>
					<!--<div class="gi-studey-stats hidden-md hidden-lg mt20 col-xs-20" style="padding:0;">
						<table>
							<tbody>
							<tr>
								<td class="col-xs-7 tac">
									<i class="stats-icon2 hidden-xs hidden-sm"></i>
									<span class="col-xs-20 visible-xs visible-sm"><?php echo SlightPHP\Tpl::$_tpl_vars["study_plan_count"]; ?></span><?php echo tpl_modifier_tr('已学课程','LearningCenter'); ?><span class="hidden-xs hidden-sm">：</span><span class="hidden-xs hidden-sm"><?php echo SlightPHP\Tpl::$_tpl_vars["study_plan_count"]; ?></span><span class="hidden-xs hidden-sm"><?php echo tpl_modifier_tr('节','LearningCenter'); ?></span>
								</td>
								<td class="col-xs-6 tac">
									<i class="stats-icon4 hidden-xs hidden-sm"></i>
									<span class="col-xs-20 visible-xs visible-sm"><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span><?php echo tpl_modifier_tr('学习时长','LearningCenter'); ?><span class="hidden-xs hidden-sm">:</span><span class="hidden-xs hidden-sm"><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span><span class="hidden-xs hidden-sm"><?php echo tpl_modifier_tr('小时','LearningCenter'); ?></span>
								</td>
								<td class="col-xs-7 tac">
									<span class="visible-xs visible-sm">3</span><?php echo tpl_modifier_tr('待完成作业','LearningCenter'); ?><span class="hidden-xs hidden-sm">:</span>
								</td>
							</tr>
							</tbody>
						</table>
					</div>-->
				</div>
				<div class="col-md-6 pr0 hidden-xs hidden-sm s-growth-pr0">
	    			<div class="s-growth-rigth-info p31 tac clearfix">
		    			<div class="col-md-10 fs14">
		    				<a href="/index.help.flash" target="_blank">
			    				<span class="course-test-icon mt30 mb25"></span>
			    				<p>课堂检测</p>
			    			</a>
		    			</div>
		    			<div class="col-md-10 col-xs-20 sign-grobal-info fs14 clearfix">
		    				<div class="col-md-20 p0">
		    					<span class="fs12 c-fr">连续
		    						<em class="cYellow sign-combo" id="sign-combo"><?php echo SlightPHP\Tpl::$_tpl_vars["user_combo"]; ?></em>天
		    					</span>
		    				</div>
							<audio class="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
                        	<em class="sign_add" style="display:none;position:absolute;left:30%;"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>
							<a href="javascript:;" class="signed">
			    				<div class="col-md-20 col-xs-20 mt10 mb25">
			    					<span class="gray-sign-growth-icon"></span>
			    				</div>
								<p class="s-has-sign"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></p>
							</a>
							<?php }else{; ?>
							<a href="javascript:;" class="sign-audo" onclick="SignAudio()">
			    				<div class="col-md-20 col-xs-20 mt10 mb25">
			    					<span class="sign-growth-icon"></span>
			    				</div>
								<p class="s-has-sign"><?php echo tpl_modifier_tr('签到','LearningCenter'); ?></p>
							</a>
							<a href="javascript:;" class="signed" style="display:none;">
			    				<div class="col-md-20 col-xs-20 mt10 mb25">
			    					<span class="gray-sign-growth-icon"></span>
			    				</div>
								<p class="s-has-sign"><?php echo tpl_modifier_tr('已签到','LearningCenter'); ?></p>
							</a>
							<?php }; ?>
		    			</div>
	    			</div>
	    		</div>
			</div>
			<!-- 课程表 -->
			<div class="growth-course-list mb20">
				<div class="growth-course-tp">
					<span class="c-fl fs18">课程表</span>
					<div class="c-fr fs14">
						<a href="/index.student.course">更多</a>
					</div>
				</div>
                <div class="tab hidden-xs">
                    <div class="tab-main swiper-container" id="tab_main">
                       <ul class="ul-width growth-course-cont clearfix swiper-wrapper">
					   <?php foreach(SlightPHP\Tpl::$_tpl_vars["week_arr"] as SlightPHP\Tpl::$_tpl_vars["month"]=>SlightPHP\Tpl::$_tpl_vars["week"]){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["week"] as SlightPHP\Tpl::$_tpl_vars["wv"]){; ?>
                            <li class="swiper-slide" <?php if(SlightPHP\Tpl::$_tpl_vars["wv"]['course_count'] !=0 ){; ?>title="有要学习的课程"<?php }; ?>>
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
					<ul class="col-sm-20 list-c growth-cous-cont hidden plan-list" id="<?php echo SlightPHP\Tpl::$_tpl_vars["pk"]; ?>">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["plan"] as SlightPHP\Tpl::$_tpl_vars["po"]){; ?>
					<li class="info">
							<div class="pic col-sm-4">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_url; ?>" target="_blank" >
									<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->thumb_sma; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
								<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 2 ){; ?>
									<span class="come-class-tip">正在上课</span>
								<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->course_type == 3)){; ?>
								    <span class="lineclass-icon fs12">线下课</span>
                                <?php }; ?>
								</a>
							</div>
							<div class="intro col-sm-7 col-md-7 col-lg-10">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_url; ?>" target="_blank" >
								<h2 class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?></h2>
								</a>
								<p class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->class_name; ?> &nbsp;讲师：
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_url; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_name; ?></a></p>
								<p>时间：<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_date; ?>  <?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_hour; ?></p>
								<p>
									<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->section_name; ?></span>
									<em class="c-fl client-logo-name"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->org_name; ?></em>
								</p>
							</div>
							<div class="col-sm-9 infor-my-btn col-md-8 col-lg-5 col-xs-20 tec">
								<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_button; ?>
							</div>
					</li>
					<?php }; ?>
					<?php }else{; ?>
                        <div class="my-collect-no-class c-fl col-lg-offset-0 col-xs-offset-0 col-sm-offset-0 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                            <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="">
                            <p style="font-weight:bold;color:#666;">您还没有直播的课程哦~！ 快去<a href="/index">首页</a>看看吧</p>
                        </div>
					<?php }; ?>
					</ul>
					<?php }; ?>
				<?php }; ?>
            </div>
			<!-- /课程表 -->
			<!-- 学习轨迹 -->
			<!--div class="leam-path bor1px col-sm-20">
				<h3 class="fs18">学习轨迹</h3>
				<dl class="fs14">
					<dt class="col-sm-4">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-4">初一数学奥数的秘密初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-10">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-4">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-4">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-10">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-4">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-4">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-10">
						第一节
						<span>第一节直播上课时长<i>1小时45</i>分钟，答对0/0题点赞4次</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-4">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-4">初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-10">
						第一节
						<span>回看观看时长<i>40</i>分钟</span>
					</dd>
				</dl>
				<dl class="fs14">
					<dt class="col-sm-4">
						6月24日
						<span>17:00</span>
					</dt>
					<dd class="col-sm-4">初一数学奥数的秘密初一数学奥数的秘密</dd>
					<dd class="col-sm-2">尖子班</dd>
					<dd class="col-sm-10">
						第一节
					</dd>
				</dl>
			</div-->
			<!-- /学习轨迹 -->
		</div>
		<!-- /right -->
	</section>
</div>
<!-- 成长体系上线后增加引导弹窗-->
    <div class="tp-lg-success-tip prop-tip-fixed hidden-xs hidden-sm hidden-md" style="display:none;"></div>
    <div class="wrap-guide-tip hidden-xs hidden-sm hidden-md" style="display:none;">
    	<div class="poprp-tip-bg1 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    	<div class="poprp-tip-bg2_0 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    	<div class="poprp-tip-bg2 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    	<div class="poprp-tip-bg3 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    	<div class="poprp-tip-bg4 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    	<div class="poprp-tip-bg5 hidden-xs hidden-sm hidden-md">
    		<a href="javascript:;" title=""></a>
    	</div>
    </div>
    <script type="text/javascript">
    $(function(){
    	$.ajax({
    		url: '/user/guide/guide',
    		type: 'POST',
    		dataType: 'json',
    		data: {
                guide: [3]
            }
    	})
    	.done(function(res) {
    		if(res.code == '0'){
    			if(res.data[3] == 'true'){
    				$(".wrap-guide-tip .poprp-tip-bg1,.tp-lg-success-tip,.wrap-guide-tip").show();
    				$("body").css("overflow","hidden");
    			}
    		}else{
    			console.error(res.code,res.msg);
    		}
    	});
    		$(".poprp-tip-bg1 a").click(function() {
    			$(".wrap-guide-tip .poprp-tip-bg1").hide(),$(".wrap-guide-tip .poprp-tip-bg2_0").show();
    		});
    		$(".poprp-tip-bg2_0 a").click(function() {
    			$(".wrap-guide-tip .poprp-tip-bg1").hide(),$(".wrap-guide-tip .poprp-tip-bg2_0").hide(),$(".wrap-guide-tip .poprp-tip-bg2").show();
    		});
    		$(".poprp-tip-bg2 a").click(function() {
    			$(".wrap-guide-tip .poprp-tip-bg1").hide(),$(".wrap-guide-tip .poprp-tip-bg2").hide(),$(".wrap-guide-tip .poprp-tip-bg2_0").hide(),$(".wrap-guide-tip .poprp-tip-bg3").show();
    		});
    		$(".poprp-tip-bg3 a").click(function() {
    			$(".wrap-guide-tip .poprp-tip-bg1").hide(),$(".wrap-guide-tip .poprp-tip-bg2").hide(),$(".wrap-guide-tip .poprp-tip-bg2_0").hide(),$(".wrap-guide-tip .poprp-tip-bg3").hide(),$(".wrap-guide-tip .poprp-tip-bg4").show(),$(document).scrollTop(316);
    		});
    		$(".poprp-tip-bg4 a").click(function() {
    			$(".wrap-guide-tip .poprp-tip-bg1").hide(),$(".wrap-guide-tip .poprp-tip-bg2").hide(),$(".wrap-guide-tip .poprp-tip-bg2_0").hide(),$(".wrap-guide-tip .poprp-tip-bg3").hide(),$(".wrap-guide-tip .poprp-tip-bg4").hide(),$(".wrap-guide-tip .poprp-tip-bg5").show(),$(document).scrollTop(0);
    		});
    		$(".poprp-tip-bg5 a").click(function() {
    	        $.post( "index.growth.CloseGrowthTips", function(r) {
    	           $('.prop-tip-fixed').hide();
    	           $('.wrap-guide-tip').hide();
    	           $(document).scrollTop(0),$("body").css("overflowY","scroll");
    	        });
    		});
    });
    </script>
     <!--成长体系上线后增加引导弹窗 -->
</div>
</section>
<!-- /body -->
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
<!-- /footer -->
<!-- 基础设置
<div class="tp-lg-success-tip prop-tip-fixed hidden-xs hidden-sm hidden-md">
	<div class="base-set-layer">
		<a href="javascript:;" class="c-fr"></a>
	</div>
</div>
<script type="text/javascript">
/*
$("body").css("overflowY","scroll")
$("body").css("overflow-y","hidden");
$(".base-set-layer a").click(function() {
	$(".tp-lg-success-tip").hide();
})
*/
</script>
 /基础设置 -->
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
    	$("#wrap-progress-parents li:eq(0)").show();
    	$("#wrap-progress-parents li:eq(1)").hide();
    	$("#wrap-progress-parents li:eq(2)").hide();
    	$("#wrap-progress-parents li:eq(3)").hide();
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
    	$("#wrap-progress-parents li:eq(0)").hide();
    	$("#wrap-progress-parents li:eq(1)").show();
    	$("#wrap-progress-parents li:eq(2)").hide();
    	$("#wrap-progress-parents li:eq(3)").hide();
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
    	$("#wrap-progress-parents li:eq(0)").hide();
    	$("#wrap-progress-parents li:eq(1)").hide();
    	$("#wrap-progress-parents li:eq(2)").show();
    	$("#wrap-progress-parents li:eq(3)").hide();
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
    	$("#wrap-progress-parents li:eq(0)").hide();
    	$("#wrap-progress-parents li:eq(1)").hide();
    	$("#wrap-progress-parents li:eq(2)").hide();
    	$("#wrap-progress-parents li:eq(3)").show();
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
        window.open("https://www.yunke.com/index.rank.rule");
    })
    //进度条
    _Progress(0);

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
//日期
	function changeCheckedDate(date){
		$('#'+date).removeClass('hidden').siblings('.plan-list').addClass('hidden');
	}
	$(".ul-width li").click(function() {
		$("#tab_main li").find("div").removeClass("on");
		$(this).find("div").addClass("on");
		var checked_date = $(".on input[name=week_date]").val();
		changeCheckedDate(checked_date);
	});
	var checked_date = $(".on input[name=week_date]").val();
	changeCheckedDate(checked_date);
//日期滚动
if($(window).width()<1025){
    var mySwiper = new Swiper('.swiper-container', {
		slidesPerView :6
    });
    var li_w=$(".ul-width li").outerWidth();
    var li_l=$(".ul-width li").length;
    var ul_w=li_w*li_l+40;
    $(".ul-width").css("width",ul_w+'px');
}

if($(window).width()<1025){
    var mySwiper = new Swiper('.swiper-container3', {
		slidesPerView :3
    });
    var li_w=$("#mob-nav li").outerWidth();
    var li_l=$("#mob-nav li").length;
    var ul_w=li_w*li_l+40;
    $("#mob-nav").css("width",ul_w+'px');
}

if ($(window).width() < 769) {
	var mySwiper = new Swiper('.swiper-container',{
		slidesPerView : 1,
		initialSlide :(function(){
			return $("input[name=week_date][value='"+checked_date+"']").closest('li').index();
		})(),
	});
	var li_width = $(".ul-width li").outerWidth();
	var li_index = $(".ul-width li").length;
	$(".ul-width").css('width',li_width*li_index);
}


//签到声音
var _IsPlaying = false;//检测是否正在播放
function SignAudio() {
    var _Player = document.querySelector('.sign-audio');
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
        url: '/index.growth.signAjax',
        data:{ uid:uid },
        dataType:'json',
        success:function(ret){
            if(ret.code ==0){
                FlawerNum(ret.data.fk_level);
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
                $('.sign-combo').text(ret.data.combo);
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

                $('.sign-audo').animate({ height:"58px" },function(){
				    if($(window).width() <= 768) {
				    	$('.s-has-sign').html('<p class="tac fs18" style="color:#fff;">已签到</p>');
				    	$('.s-has-sign').css('paddingTop', '7px');
				    	$('.gi-growth-calendar').addClass('s-growth-orgsign').removeClass('s-growth-hassign');
				    }else{
				    	$('.s-has-sign').text('已签到');
				    }
                    $('.signed').show();
                    $('.sign-audo').hide();
                    $(".sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+'+ret.data.add_score);
                    $(".sign_add").show(function(){
                    $(".sign_add").animate({ top:"-20px",opactiy:"1" },function(){
                        $(".sign_add").hide().animate({ top:"-30px" });
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
//鲜花总数
function FlawerSumNum(element,type){
    $.ajax({
        type:'post',
        url:'/user/gift/getStudentOrTeacherGiftSum',
        data:{ type:type},
        dataType:'json',
        success:function(data){
            if(data.code=='0'){
                element.html(data.gift_count);
            }
        }

    })
}
//奖励鲜花数
function FlawerNum(le){
    var giftTotalNum=$(".gift-total-num");
    var giftAddNum=$("#gift-add-num");
    var giftAdd=$("#gift-add");
    var type=1; //1:学生 2:教师
    $.ajax({
        type:'post',
        url:'/user/gift/getGiftSign',
        data:{ level:le},
        dataType:'json',
        success:function(data){
            if(data.code=='0'){
                giftAddNum.html(data.giftNum);
                giftAdd.animate({ bottom:"-20px",opacity:"1"},function(){
                    giftAdd.animate({ bottom:"40px",opacity:"0"});
                });
                FlawerSumNum(giftTotalNum,type);
            }
        }
    })
}
FlawerSumNum($(".gift-total-num"),1);
</script>
