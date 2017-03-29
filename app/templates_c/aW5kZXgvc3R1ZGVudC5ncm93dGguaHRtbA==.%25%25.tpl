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
</head>
<body>
<!-- header -->
<?php echo tpl_function_part("/index.main.usernav/student"); ?>
<!-- /header -->
<!-- body -->
<section class="p20">
<div class="container pos-rel">
<div class="row">
	<!-- left -->
	<?php echo tpl_function_part("/index.main.menu/student"); ?>
		<!-- /left -->
	<section id="index-growth">
		<!-- right -->
		<div class="col-md-16 col-xs-20">
			<!-- 成长值 -->
            <p class="mob-nav hidden-lg hidden-md">
            <a href="/index.student.course" class="col-xs-6">我的课程</a>
            <a href="/index.student.fav" class="col-xs-6">我的收藏</a>
            <a href="/index.student.discount" class="col-xs-6">我的优惠券</a>
            </p>
            <div class="growth-val mb20">
				<div class="col-sm-20 col-sm-offset-1 col-lg-15 col-lg-offset-0 fs14">
					<p>
						<strong>成长值</strong>还有<span id="cha_score"><?php echo SlightPHP\Tpl::$_tpl_vars["cha_score"]; ?></span>点升级到
						<a href="/index.rank.rule" id="levelIcon" class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["next_level"]->pk_level; ?>" target="_blank" style="margin: initial;">
						</a>, 超越全国
						<span id="user_percent"><?php echo SlightPHP\Tpl::$_tpl_vars["percent"]; ?></span>的同学！<a href="/index.rank" target="_blank" title="" class="c-fr">查看排行榜</a>
					</p>
						<input type="hidden" name="user_score" id="user_score" value="<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>">
					<ul id="wrap-progress-parents" class="growth-lv hidden-xs" title="您当前<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>经验值"><!--<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>-->
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level_info"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["level_info"] as SlightPHP\Tpl::$_tpl_vars["score"]=>SlightPHP\Tpl::$_tpl_vars["level"]){; ?>
						<li>
							<?php if(SlightPHP\Tpl::$_tpl_vars["level_count"] = 1){; ?><span class="val-cirl-lv1"><?php echo SlightPHP\Tpl::$_tpl_vars["score"]; ?></span><?php }; ?>
							<span class="<?php echo SlightPHP\Tpl::$_tpl_vars["level"]; ?>"></span>
							<span class="g-progress"></span>
						</li>
						<?php }; ?>
                        <input type="hidden" name="level_count" value="<?php echo SlightPHP\Tpl::$_tpl_vars["level_count"]--; ?>">
						<?php }; ?>
						<!--li class="col-sm-20">
							<div class="growth-progress">
								<div class="growth-chart"></div>
							</div>
						</li-->
					</ul>
				</div>
				<div class="col-sm-20 col-xs-20 col-xs-offset-3 col-lg-5 col-sm-offset-6 col-lg-offset-0">
					<div class="growth-calendar fs12">
						<h1>
							<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["curr_date"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["curr_week"]; ?></span>
							<span class="c-fr">连签<i id="sign-combo"><?php echo SlightPHP\Tpl::$_tpl_vars["user_combo"]; ?></i>日</span>
						</h1>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sign_info"])){; ?>
						<a href="javascript:void(0);" id="signed" style="color:#666;cursor:default">今日已签到</a>
						<?php }else{; ?>
						<a href="javascript:void(0);" id="sign-audo" onclick="SignAudio()">立即签到</a>
						<?php }; ?>
						<audio id="sign-audio" src="<?php echo utility_cdn::img('/assets_v2/js/audio/sign_audio.wav'); ?>"></audio>
                        <em id="sign_add"><img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+2</em>
					</div>
				</div>
			</div>
			<!-- /成长值 -->
			<!-- 学习统计 -->
			<div class="studey-stats mb20" style="padding:0;">
				<table>
					<tbody>
						<tr>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon1"></i>
								已报课程：<span><?php echo SlightPHP\Tpl::$_tpl_vars["user_course_count"]; ?></span>个
							</td>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon2"></i>
								已学课程：<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_plan_count"]; ?></span>节
							</td>
                            <td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
                                <i class="stats-icon4">
                                </i>学习时长:<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span>小时
                            </td>
							<td class="col-sm-5 col-lg-5 col-xs-20 col-md-5">
								<i class="stats-icon5"></i>
								观看视频：<span><?php echo SlightPHP\Tpl::$_tpl_vars["look_plan_count"]; ?></span>次
							</td>
							<!--td class="col-sm-6">
								<i class="stats-icon3"></i>
								完成作业：<span>3</span>次
							</td-->
						</tr>
						<tr>
							<!--td class="col-sm-7">
								<i class="stats-icon4">
								</i>学习时长：<span><?php echo SlightPHP\Tpl::$_tpl_vars["study_time"]; ?></span>小时
							</td
							<td class="col-sm-7">
								<i class="stats-icon5"></i>
								观看视频：<span><?php echo SlightPHP\Tpl::$_tpl_vars["look_plan_count"]; ?></span>次
							</td>-->
							<!--td class="col-sm-6">
								<i class="stats-icon6"></i>
								答题21题：正确率<span>60%</span>
							</td-->
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /学习统计 -->
			<!-- 课程表 -->
			<div class="growth-course-list mb20">
				<div class="growth-course-tp">
					<span class="c-fl fs18">课程表</span>
					<div class="c-fr fs14">
						<a href="/index.student.course">更多</a>
					</div>
				</div>
                <div class="tab">
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
							<div class="intro col-sm-7 col-md-7 col-lg-8">
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
							<div class="col-sm-9 infor-my-btn col-md-8 col-lg-5">
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
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tips"])&&SlightPHP\Tpl::$_tpl_vars["tips"]==1){; ?>
<!-- 成长体系上线后增加引导弹窗-->
	<div class="tp-lg-success-tip prop-tip-fixed hidden-xs hidden-sm hidden-md"></div>
	<div class="wrap-guide-tip hidden-xs hidden-sm hidden-md">
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
    //提示层
	$(".wrap-guide-tip .poprp-tip-bg1").show();
	$("body").css("overflow","hidden");

});
</script>
 <!--成长体系上线后增加引导弹窗 -->
<?php }; ?>
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
                console.log(3);
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
        window.open("https://www.yunke.com/index.rank.rule");
    })
    //进度条
    var user_score=$("#user_score").val();
    _Progress(0);

//日期
	$(".ul-width li").click(function() {
		$("#tab_main li").find("div").removeClass("on");
		$(this).find("div").addClass("on").siblings().removeClass("on");
		var checked_date = $(".on input[name=week_date]").val();
		$('#'+checked_date).removeClass('hidden').siblings('.plan-list').addClass('hidden');
	})
    var checked_date = $(".on input[name=week_date]").val();
    $('#'+checked_date).removeClass('hidden').siblings('.plan-list').addClass('hidden');
//日期滚动
        if($(window).width()<1025){
        var mySwiper = new Swiper('.swiper-container', {
        slidesPerView :6
        //autoplay: 1000,//可选选项，自动滑动
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
        }

//提示层
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
//签到声音
var _IsPlaying = false;//检测是否正在播放
function SignAudio() {
    var _Player = document.querySelector('#sign-audio');
    if (_IsPlaying) {
        // 如果正在播放, 停止播放并停止读取此音乐文件
        _Player.pause();
        _Player.src = '';
    } else {
        _Player.src = '/assets_v2/js/audio/sign_audio.wav';
        _Player.play();
    }

    var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->fk_user; ?>;
    $.ajax({
        type:"post",
        url: '/index.growth.signAjax',
        data:{ uid:uid },
        dataType:'json',
        success:function(ret){
            if(ret.code ==0){
                if(ret.data.combo == 5){
                    $("body").GrowthLayer({
                        types:"five",// five|smallgrowth|biggrowth
                        space:3000, //时间间隔
                        auto:true, //自动关闭
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
                //$('#sign-audo').remove();
                $('#sign-audo').animate({ height:"25px" },function(){
					$('#user_percent').text(ret.data.percent);
                    $('#sign-audio').before('<a href="javascript:void(0);" id="signed" style="color:#666;">今日已签到</a>');
                    $('#sign-audo').height("50px").hide();
                    $("#sign_add").html('<img src="<?php echo utility_cdn::img('/assets_v2/img/exp-icon.png'); ?>">+' + ret.data.add_score);
                    $("#sign_add").show(function(){
                    $("#sign_add").animate({ top:"-20px",opactiy:"1" },function(){
                        $("#sign_add").hide().animate({ top:"-40px" });
                        $("#wrap-progress-parents").attr("title","您当前"+ret.data.score+"经验值");
                        $("#user_score").val(parseInt(<?php echo SlightPHP\Tpl::$_tpl_vars["user_level"]->score; ?>)+parseInt("+"+ret.data.add_score));
                        $("#levelIconLeft").removeAttr("class").removeAttr("class").addClass("level-icon"+ret.data.fk_level);
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
