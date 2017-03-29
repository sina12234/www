<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>我的课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的课表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.calendar-v2.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;" id="scrollTops">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!--header-->
</style>
<section class="pd30">
<div class="container">
    <div class="row">
    <!-- leftmenu start -->
	<?php echo tpl_function_part("/user.main.menu.teacher.timetable"); ?>
    <!-- leftmenu end -->
    <!-- 我的课表 -->
    <div class="right-main col-sm-20 col-md-16 col-xs-20" id="wrap-my-course">
        <p class="mob-nav hidden-lg hidden-md">
        <a href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?>" class="col-xs-5">我的主页</a>
        <a href="/teacher.course.timetable2" class="col-xs-5">课程表</a>
        <a href="/teacher.course.teacherOfCourseList" class="col-xs-5">在教课程</a>
        <a href="/teacher.manage.student" class="col-xs-5">我的学生</a>
        </p>
        <div class="content">
            <div class="col-lg-20 pd0" style="display:none;">
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
            </div>
            <?php /*<h1 class="fs22 hidden-xs"><?php echo tpl_modifier_tr('我的课表','site.teacher'); ?></h1>*/?>
            <dl class="list-tab fs16 col-xs-20 col-md-20 col-sm-20">
                <dd class="col-sm-3 col-md-3 col-xs-8 curr"><?php echo tpl_modifier_tr('待上课','site.teacher'); ?><i></i></dd>
                <dd class="col-sm-4 col-md-3 col-xs-8"><?php echo tpl_modifier_tr('教学课程','site.teacher'); ?>（<?php echo SlightPHP\Tpl::$_tpl_vars["classCount"]; ?>）<i></i></dd>
            </dl>
            <ul id="list">
            <!-- 直播课程 -->
                <li id="notEnd">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["showData"])){; ?>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["showData"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <!-- 月份 -->
			 	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["start_time_month"])){; ?>
                    <div class="student-course-month col-sm-17 hidden-xs"><i class="col-sm-1 fs12"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]["start_time_month"],'site.teacher'); ?></i></div>
                <?php }; ?>
                <!-- /月份 -->
                <!-- list -->
                <div class="my-course-contents col-sm-20 col-xs-20 c-fl" m_d="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['m_d']; ?>" s_t_m_d="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['start_time_month_num']; ?>" >
                    <div class="col-sm-3 fs14 vertical-line col-xs-6">
                        <p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["start_time_m_d"]; ?></p>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["start_time_H_i"])){; ?>
                        <p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["start_time_H_i"]; ?></p>
						<?php }; ?>
                        <span></span>
                        <i></i>
                    </div>
                    <div class="col-sm-17 my-course-contents-rt col-xs-14">
                        <div class="col-sm-5">
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_title_link']; ?>">
                                <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>" alt="" />
                                <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["course_type"]; ?>
                                <!--
                                    <div class="g-icon3">录播课</div>
                                    <div class="taped-icon">线下课</div>
                                -->
                            </a>
                        </div>
                        <div class="col-sm-11 infor-subject">
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_title_link']; ?>">
								<h1 class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["course_name"]; ?></h1>
							</a>
                            <p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["class_name"]; ?> &nbsp;(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["user_total_class"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["max_user_class"]; ?>)<?php echo tpl_modifier_tr('人','site.teacher'); ?></p>
                            <p class="hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["section_name"]; ?></p>
                        </div>
                                <div class="col-sm-7 col-md-4 infor-student-btn hidden-xs hidden-sm hidden-md">
								<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['b_status_link']; ?>" target="_blank">
                                    <button class="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['btn_color']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["b_status"]; ?></button>
								</a>
                                    <p>
                                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['left_link']; ?>" title="" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["left_status"]; ?></a><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['mid_status']; ?><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['right_link']; ?>" title="" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["right_status"]; ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                     <!-- /list -->
					 <?php }; ?>
					 <?php }else{; ?>
                     <div class=" col-md-20 col-sm-20 col-xs-20">
                        <div class="mt20 tac">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>"><br>
                        <?php echo tpl_modifier_tr('还没有课程，联系机构为你排课吧','site.teacher'); ?>
                        </div>
                     </div>
					 <?php }; ?>
                    </li>
            <!-- /直播课程 -->
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tips"])){; ?>
			<div class="tp-lg-success-tip hidden-xs hidden-sm hidden-md">
				<div class="base-set-layer">
					<a href="javascript:;" class="c-fr"></a>
				</div>
			</div>
<script type="text/javascript">
$("body").css("overflow-y","hidden");
$(".base-set-layer a").click(function() {
    $.post( "teacher.course.CloseGrowthTips", function(r) {
        $(".tp-lg-success-tip").hide();
        $("body").css("overflowY","scroll")
    });
})
</script>
			<?php }; ?>
            <!-- 报名课程 -->
                    <li>
                        <div class="my-course-content col-sm-20">
                            <!-- 搜索 -->
                                <div class="col-sm-4 my-course-search-tp hidden-xs" style="padding:0;">
                                    <div class="my-course-search">
                                        <input placeholder="<?php echo tpl_modifier_tr('课程搜索','site.teacher'); ?>" type="text" class="col-sm-16" name="title" id="search_data"  value="<?php echo SlightPHP\Tpl::$_tpl_vars["scourse_title"]; ?>"/>
                                        <button class="col-sm-4" id="scourse_title"></button>
                                    </div>
                                </div>
                            <!-- /搜索 -->
                            <ul class="my-course-content-rt col-md-20 col-xs-20 pd0">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classListShow"]->data)){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["classListShow"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>

                                <li class="col-sm-10 bor1px col-xs-20">
                                <a href="/teacher.course.detail/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->class_id; ?>" target="_blank" title="">
                                    <div class="col-sm-10 col-xs-20">
                                       <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->course_thumb); ?>" alt="" />
										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==2){; ?>
                                        <span class="start-class"><?php echo tpl_modifier_tr('正在上课','site.teacher'); ?></span>
										<?php }; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type==3){; ?>
                                        <span class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></span>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->type==2)){; ?>
                                        <span class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></span>
										<?php }; ?>
                                    </div>
                                    <div class="col-sm-10">
                                        <p class="fs16 my-course-subname"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_title; ?></p>
                                        <p><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?> &nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_total; ?><?php echo tpl_modifier_tr('人','site.teacher'); ?></p>

										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->type==2){; ?>
                                        	<p><?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_info->section_count; ?><?php echo tpl_modifier_tr('章','site.teacher'); ?>  <?php echo tpl_modifier_tr('完成度','site.teacher'); ?>
											<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_info->planend_count; ?>
											<?php echo tpl_modifier_tr('章','site.teacher'); ?></p>
										<?php }else{; ?>

                                        	<p><?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_info->section_count; ?><?php echo tpl_modifier_tr('章','site.teacher'); ?>  <?php echo tpl_modifier_tr('进度第','site.teacher'); ?>
											<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->section_info->section_count==1){; ?>
											1
											<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->plan_info->planend_count +1 >= SlightPHP\Tpl::$_tpl_vars["v"]->section_info->section_count)){; ?>
											<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_info->planend_count; ?>
											<?php }else{; ?>
											<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_info->planend_count+1; ?>
											<?php }; ?>
											<?php echo tpl_modifier_tr('章','site.teacher'); ?></p>
										<?php }; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fee_type==1){; ?>
                                        <p style="color:#f71b25;">
										<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?><?php echo tpl_modifier_tr('元','site.teacher'); ?>
										<?php }else{; ?>
                                        <p class="free-color">
										<?php echo tpl_modifier_tr('免费','site.teacher'); ?>
										<?php }; ?>
										</p>
                                    </div>
                                    </a>
                                </li>
								<?php }; ?>
								<?php }else{; ?>
								 <div class="col-md-20 col-sm-20 col-xs-20">
									<div class="mt20 tac">
									<img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>"><br>
									<?php echo tpl_modifier_tr('还没有课程，联系机构为你排课吧','site.teacher'); ?>
									</div>
								 </div>
								<?php }; ?>
                            </ul>
                        </div>
						<div class="page-list" id="pagepage">
						</div>
						</li>
            <!-- /报名课程 -->
                    </ul>
                </div>
            </div>
            </div>
<!-- /我的课表 -->
        <div class='clear'></div>
    </div>
</div>
<!--分页开始-->
<!--分页结束-->
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<footer>
<?php echo tpl_function_part("/site.main.footer"); ?>
</footer>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script>
var tab = <?php echo SlightPHP\Tpl::$_tpl_vars["tab"]; ?>;
$(document).ready(function() {
    $('#list li').first().show();
	$('.list-tab>dd').click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
		$('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
	})
    $("#scourse_title").click(function(){
		var search_data = $("#search_data").val();
        location.href="/teacher.course.timetable?searchdata="+search_data;
	})
	page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["url"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["total"]; ?>);
});
</script>
<script id='planListnotend' type='text/template'>
	<<#data>>
	<div class="student-course-month col-sm-17 hidden-xs"><i class="col-sm-2 fs12"><<start_time_month>></i></div>
	<div class="my-course-contents col-xs-20 col-sm-20 c-fl" m_d="<<m_d>>" s_t_m_d="<<start_time_month_num>>" >
		<div class="col-sm-3 fs14 vertical-line col-xs-5">
			<p><<start_time_m_d>></p>
			<p><<start_time_H_i>></p>
			<span></span>
			<i></i>
		</div>
		<div class="col-sm-17 my-course-contents-rt col-xs-15">
			<div class="col-sm-5">
				<a href="<<course_title_link>>">
					<img src="<<thumb>>" alt="" />
					<<&course_type>>
				</a>
			</div>
			<div class="col-sm-14 infor-subject">
				<a href="<<course_title_link>>">
					<h1 class="fs16"><<course_name>></h1>
				</a>
				<p><<class_name>> &nbsp;(<<user_total_class>>/<<max_user_class>>)<?php echo tpl_modifier_tr('人','site.teacher'); ?></p>
				<p><<section_name>></p>
			</div>
			<div class="col-sm-7 infor-student-btn col-lg-4 hidden-xs hidden-sm hidden-md">
				<a href="<<b_status_link>>" target="_blank">
					<button class="<<btn_color>>"><<b_status>></button>
				</a>
				<p>
				<a href="<<left_link>>" title="" target="_blank"><<left_status>></a> <<mid_status>> <a href="<<right_link>>" title="" target="_blank"><<right_status>></a>
				</p>
			</div>
		</div>
	</div>
<?php /*
	<li>
	<div class="line-date col-sm-4 col-md-4 new-line-date ">
		<div class="list-icon new-list-icon hidden-xs"></div>
		<div class="list-date"><span class="fs14"><<start_date>></span></div>
	</div>
	<div class="new-list-right">
		<div class="pic col-sm-7 col-md-7 col-xs-10 col-lg-5"><a href="./course.plan.play/<<plan_id>>"><p><img src="<<thumb>>" alt=""></p></a></div>
		<div class="intro col-sm-5 col-md-5 col-xs-10 col-lg-7">
			<a href="./course.plan.play/<<plan_id>>"><p class="fs16"><<course_name>> <<class_name>></p></a>
			<p><span class="<<fee_info_color>>"><<fee_rnfo>></span> | 共<<countsecs>>章 进度 <<section_name>></p>
			<p><span style="margie-top:-1px;float:left"></span> 学生(<<user_total_class>>/<<max_user_class>>)</p>
			<?php /* tpl_modifier_a function not exists! */; ?>
		<</data>>
	</script>
<script>
	var arrend = [];
	var arrnotend = [];
	$(document).ready(function() {
    var getUrl=GetUrl("page");
    if(getUrl!=null){
		$(".list-tab dd:last").addClass('curr').siblings().removeClass('curr');
		$('#list>li:last').show().siblings().hide();
    }else{
		$('#list li').first().show();
    }
		$('.list-tab>dd').click(function() {
			$(this).addClass('curr').siblings().removeClass('curr');
			$('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
		})
		if(tab==2){
			$(".list-tab>dd:eq(1)").trigger("click");
			//$("#list li").first().hide();
			//console.log("131233");
			//$("#list>li:eq(1)").hide();
		}
		var loaded = true;
		//var loaded = false;
		var loadend = true;
		//var loadend = true;
		var startTime = "<?php echo SlightPHP\Tpl::$_tpl_vars["startTime"]; ?>";
		var endstartTime = "<?php echo SlightPHP\Tpl::$_tpl_vars["endstartTime"]; ?>";
		var Uid = "<?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>";
		var Page = 1;
		var Pagestart = 1;
		//var domisend = $("#isEnd li:last")[0];
		var domnotend = $("#notEnd .my-course-contents:last")[0];
		var md = $("#notEnd .my-course-contents:last").attr("m_d");
		var stmd = $("#notEnd .my-course-contents:last").attr("s_t_m_d");
							console.log("1md is "+md);
							console.log("1stmd is "+stmd);
		//var domnotend = $("#notEnd li:last")[0];
		//arrend.push(domisend);
		arrnotend.push(domnotend);
		$(window).scroll(function(){
			var tops = $(window).scrollTop();
			var contents = $(window).height();
			var prec1 = getClient();
			jiance(arrnotend, prec1, function(){
				Pagestart++;
				if(loaded){
					$.ajax({
						type: "POST",
						url: "/teacher.course.timetablestartAjaxnew",
						data:{ start_time:startTime,endstart_time:endstartTime,uid:Uid,page:Pagestart,m_d:md,stmd:stmd},
						dataType: "json",
						beforeSend: function () {
							//$("#submit").attr({ disabled: "disabled" });
						},
						success: function(r){
							data = r;
							var planListTpl1 = $('#planListnotend').html();
							var planList2 = Mustache.render(planListTpl1, data);
							$('#notEnd').append(planList2);
							domnotend = $("#notEnd .my-course-contents:last")[0];
							md = $("#notEnd .my-course-contents:last").attr("m_d");
							stmd = $("#notEnd .my-course-contents:last").attr("s_t_m_d");
							console.log("md is "+md);
							console.log("stmd is "+stmd);
							arrnotend.push(domnotend);
							if(r.error){
								//  $("#error").html(r.error);
								alert(r.error);
								//      location.reload();//这里未完善
								return false;
							}
							if(r){
								if(r.data.length ==0){
									loaded = false;
								}
							}
						},
						complete: function () {
							//$("#submit").removeAttr("disabled");
						},
						error: function (r) {
							Page--;
							domnotend = $("#notEnd li:last")[0];
							arrnotend.push(domnotend);
							//console.info("error: " + r.error);
						}

					});
				}

			})

		});


	});
function GetUrl(name){
    var reg=new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r=window.location.search.substr(1).match(reg);
    if(r!=null){
     return unescape(r[2]);
    }
    return null;
}
</script>
