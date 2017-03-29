<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>我的学生 - <?php echo tpl_function_part('/site.main.orgname'); ?> -  云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的学生 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<!--header-->
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- mob nav -->
<div class="g-nav hidden-lg hidden-md">
	<ul class="swiper-wrapper" id="mob-nav">
		<li class="swiper-slide"><a href="/teacher.detail.entry/<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userId"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?><?php }; ?>">我的主页</a></li>
		<li class="swiper-slide"><a href="/teacher.course.timetable2">课程表</a></li>
		<li class="swiper-slide"><a href="/task/publishTask/teacherTaskList">作业列表</a></li>
		<li class="swiper-slide"><a href="/teacher.course.teacherOfCourseList">在教课程</a></li>
		<li class="swiper-slide"><a href="/teacher.manage.student" class="active">我的学生</a></li>
	</ul>
</div>
<section class="org-section">
<div class="container">
<div class="row">
	<!-- leftmenu start-->
			<?php echo tpl_function_part("/user.main.menu.teacher.student"); ?>
			<!-- leftmenu end -->
			<div class="right-main col-sm-20 col-md-16 col-xs-20">
				<div class="list-filter clearfix mb10">
						<div class="c-fl mr10">
							<select name="course_id" id="course_id" class="form-control">
								<option value="0"><?php echo tpl_modifier_tr('请选择课程','site.teacher'); ?></option>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["cv"]){; ?>
								<?php if(mb_strlen(SlightPHP\Tpl::$_tpl_vars["cv"]['title'],'utf8')>16){; ?>
								<option title="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]['title']; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["course_id"]==SlightPHP\Tpl::$_tpl_vars["ck"]){; ?>selected<?php }; ?> value=<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["cv"]['title'],0,15,'utf-8'); ?>... (报名:<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]['user_total']; ?>)</option>
								<?php }else{; ?>
								<option <?php if(SlightPHP\Tpl::$_tpl_vars["course_id"]==SlightPHP\Tpl::$_tpl_vars["ck"]){; ?>selected<?php }; ?> value=<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]['title']; ?> (报名:<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]['user_total']; ?>)</option>
								<?php }; ?>
								<?php }; ?>
							</select>
							<select id="class" name="class_id" class="form-control">
								<option value="0"><?php echo tpl_modifier_tr('所有班级','site.teacher'); ?></option>
							</select>

						</div>
						<div class="num fs14 c-fl cBlue hidden-xs hidden-sm hidden-md">
						  <a href="javascript:;" target="_blank" id="phpexcelexport"><?php echo tpl_modifier_tr('导出','site.teacher'); ?>Excel</a>
						</div>
						<form action="" method="get" name="search" class="hidden-xs hiddex-sm">
							<div class="right search-frame so_student" style="padding:0;" id="so_student">
								<input placeholder="<?php echo tpl_modifier_tr('输入手机号或姓名','site.teacher'); ?>" autocomplete="off" type="text" name="mobile" id="mobile" class="search-input" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["mobile"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?><?php }; ?>"/>
								<div class="search-box">
									<span class="search-icon" id="form_sub" ></span>
									<div class="clear-icon" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["mobile"])){; ?>style="display:none;"<?php }; ?> ></div>
								</div>
								<input type="hidden"  name="course_id" value="<?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>">
								<input type="hidden"  name="class_id" value="<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>">

							</div>
							<!--<div class="so-student t-so-student col-xs-20 col-md-5" id="so_student">-->
								<!--<input type="text" id="mobile" name="mobile" placeholder="<?php echo tpl_modifier_tr('输入手机号或姓名','site.teacher'); ?>" value="<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>">-->
								<!--<input type="hidden"  name="course_id" value="<?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>">-->
								<!--<input type="hidden"  name="class_id" value="<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>">-->

								<!--<span class="del-btn icon"></span>-->
								<!--<span class="so-btn s-so-btn icon" id="s-so-btn"></span>-->
								<!--<span class="icon s-so-icon new-so-icon"></span>-->
							<!--</div>-->
						</form>
					</div>
					<table class="table-grid col-xs-20">
						<thead>
							<tr>
								<td class="col-sm-3 col-xs-10"><?php echo tpl_modifier_tr('姓名','site.teacher'); ?></td>
								<td class="col-sm-2 col-xs-7 hidden-xs"><?php echo tpl_modifier_tr('性别','site.teacher'); ?></td>
								<td class="col-sm-3 col-xs-10"><?php echo tpl_modifier_tr('手机号','site.teacher'); ?></td>
								<td class="col-sm-3 col-xs-8 hidden-xs"><?php echo tpl_modifier_tr('地域','site.teacher'); ?></td>
								<td class="col-sm-3 hidden-xs col-xs-4"><?php echo tpl_modifier_tr('课程','site.teacher'); ?></td>
								<td class="col-sm-3 hidden-xs col-xs-4"><?php echo tpl_modifier_tr('班级','site.teacher'); ?></td>
								<td class="col-sm-3 hidden-xs"><?php echo tpl_modifier_tr('报名时间','site.teacher'); ?></td>
							</tr>
						</thead>
						<?php if(empty(SlightPHP\Tpl::$_tpl_vars["studentList"])){; ?>
						<tbody>
						<tr>
							<td colspan="7">
								<div class="col-md-20 tac mt40">
									<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"/></br>
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["course_id"])){; ?>
										<span class="fs14" style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('请选择需要查看的课程','site.teacher'); ?>~</span>
										<?php }else{; ?>
										<span class="fs14" style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('这个课程还没有报名的学生','site.teacher'); ?>~</span>
										<?php }; ?>
									</div>
								</div>
							</td>
						</tr>
							<?php }else{; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["studentList"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
							 <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info)){; ?>
							<tr class="fs14 data">
							<td class="col-sm-3 col-xs-10"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->name; ?><?php }; ?></td>
							<?php if(isset(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender)&&SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender==1){; ?>
							<td class="col-sm-2 col-xs-7 hidden-xs"><?php echo tpl_modifier_tr('男','site.teacher'); ?></td>
							<?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender)&&SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender==2)){; ?>
							<td class="col-sm-2 col-xs-7 hidden-xs"><?php echo tpl_modifier_tr('女','site.teacher'); ?></td>
							<?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender)&&SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender==0)){; ?>
							<td class="col-sm-2 col-xs-7 hidden-xs"><?php echo tpl_modifier_tr('未设置','site.teacher'); ?></td>
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile)){; ?>
							<td class="col-sm-3 col-xs-10"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile; ?></td>
							<?php }else{; ?>
							<td class="col-sm-3 col-xs-7">未设置</td>
							<?php }; ?>

							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province)){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province==SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city){; ?>
							<td class="col-sm-3 col-xs-8 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?></td>
								<?php }else{; ?>
							<td class="col-sm-3 col-xs-8 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city; ?></td>
								<?php }; ?>
							<?php }else{; ?>
							<td class="col-sm-3 col-xs-8 hidden-xs"><?php echo tpl_modifier_tr('未设置','site.teacher'); ?></td>
							<?php }; ?>
							<td class="col-sm-3 col-xs-4 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["courseList"][SlightPHP\Tpl::$_tpl_vars["v"]->cid]['title']; ?></td>
							<td class="col-sm-3 hidden-xs col-xs-4"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classList"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name; ?><?php }; ?></td>
							<td class="col-sm-3 hidden-xs"><?php echo date("Y-m-d",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->create_time)); ?></td>
							</tr>
							<?php }; ?>
							<?php }; ?>
							<?php }; ?>
						</tr>
						</tbody>
					</table>

					<div class="col-sm-20"></div>
				</div>
    	</div>
    </div>
</section>
<script type="text/javascript">
var mobile = $("#mobile").val();
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
var course_data=<?php echo SJson::encode(SlightPHP\Tpl::$_tpl_vars["courseList"]); ?>;
<?php }else{; ?>
var course_data=[];
<?php }; ?>
var class_id="<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>";
$(document).ready(function(){
		var tmp_cid = $("#course_id ").val();
		if(tmp_cid != 0){
			$("#class").html("");
			$("#class").append('<option value="0">'+"<?php echo tpl_modifier_tr('所有班级','site.teacher'); ?>"+'</option>');
			var classInfo = course_data[tmp_cid].class_data;
			for(var i in classInfo){
				var s="";
				if(class_id == classInfo[i].class_id)s=" selected ";
				$("#class").append('<option '+s+' value="'+classInfo[i].class_id+'">'+classInfo[i].name+'</option>');
			}
		}
//搜索
    $('form[name=search]').keydown(function (event) {
        if(event.keyCode == 13){
            return false;
        }
    })
	$('#form_sub').click(function(){
		var p1 = $("#course_id").val();
		var p2 = $("#class").children('option:selected').val();
		if(p1=='0'){
			p2='0';
			layer.msg('请选择课程');
			return false;
		}
		var mobile = $("#mobile").val();
		if(mobile==''){
			layer.msg('请输入手机号或姓名');
			return false;
		}
		window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2+"&mobile="+mobile;
	});
	$('#mobile').on('keyup',function (event) {
		var p1 = $("#course_id").val();
		var p2 = $("#class").children('option:selected').val();
		if(event.keyCode == 13){
			var searchVal = $.trim($(this).val());
			if(p1=='0'){
				layer.msg('请选择课程');
				return false;
			}
			if(searchVal==''){
				return false;
			}
			if(mobile==''){
				layer.msg('请输入手机号或姓名');
				return false;
			}
			window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2+"&mobile="+mobile;//页面跳转并传参
		}
	})
	$("#so_student .clear-icon").click(function(){
		var p1 = $("#course_id").val();
		var p2 = $("#class").children('option:selected').val();
		if(p1=='0'){
			p2='0';
		}
		window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2;
	})
});


				$('#class').change(function(){
					var p1 = $("#course_id").val();
					var p2 = $(this).children('option:selected').val();//这就是selected的值
					window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
				})

				$('#course_id').change(function(){
					var p1 = $("#course_id").val();
					var p2 =0;
					window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2;
				})

//				if(mobile !=''){
//					$("#so_student .s-so-icon").hide();
//					$("#so_student input").show();
//					//$("#so_student .so-btn").show();
//					$("#so_student .del-btn").show();
//				}

//				$("#so_student input").keydown(function(e){
//						//$("#so_student .so-btn").show();
//						var mobile = $("#mobile").val();
//						var curKey = e.which;
//							var p1 = $("#course_id").val();
//							var p2 = $("#class").children('option:selected').val();
//						if(curKey == 13){
//								if(p1=='0'){
//								layer.msg('请选择课程');
//								return false;
//								}
//						if(mobile==''){
//							layer.msg('请输入手机号或姓名');
//						return false;
//					}
//					window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2+"&mobile="+mobile;//页面跳转并传参
//				}
//			});
//			$("#so_student .del-btn").click(function(){
//				var p1 = $("#course_id").val();
//				var p2 = $("#class").children('option:selected').val();
//				if(p1=='0'){
//					p2='0';
//				}
//
//			window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2;
//			})
//			$("#s-so-btn").click(function(){
//				var p1 = $("#course_id").val();
//				var p2 = $("#class").children('option:selected').val();
//				if(p1=='0'){
//					p2='0';
//					layer.msg('请选择课程');
//					return false;
//				}
//				var mobile = $("#mobile").val();
//			window.location.href="/teacher.manage.student?course_id="+p1+"&class_id="+p2+"&mobile="+mobile;
//			})
			//excel导出
			$("#phpexcelexport").click(function(){
				var p1 = $("#course_id").val();
				var p2 = $("#class").children('option:selected').val();

				if(p1=='0'){
					p2='0';
				}
				var mobile = $("#mobile").val();
				if($(".table-grid tr").hasClass("data")) {
					window.location.href="/phpexcel/platformstu/myStudent?course_id="+p1+"&class_id="+p2+"&mobile="+mobile;
				}else{
					layer.msg("请选择导出课程");
				}
			})
		</script>
		<div id="rightWindow"></div>
		<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
		<?php echo tpl_function_part("/site.main.footer"); ?>
	</body>
</html>
