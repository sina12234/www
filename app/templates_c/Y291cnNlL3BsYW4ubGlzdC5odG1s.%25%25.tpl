<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>视频管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 视频管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav"); ?>
		<section class="mt40">
		<div class="container">
			<?php echo tpl_function_part("/org.main.menu.course"); ?>
			<div class="right-main col-sm-17 col-md-16">
				<div class="content">
					<p><a href="/user.org.course"><?php echo tpl_modifier_tr('返回','site.course'); ?></a>><?php echo tpl_modifier_tr('视频管理','site.course'); ?></p>
					<h1 class="fs16 fob mt20"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]['title'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course"]['title']; ?><?php }; ?></h1>
					<?php if(SlightPHP\Tpl::$_tpl_vars["course"]['type']==1){; ?>
					<p class="c-fl mt10">
					<span class="c-fl fs16" style="line-height:30px"><?php echo tpl_modifier_tr('班级','site.course'); ?>：</span><select id='sel' onchange="select(this.value)"  class="live-video-selt col-sm-2">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["class"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<option value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]['class_name'],'site.index'); ?></option>
						<?php }; ?>
						<?php }; ?>
					</select></p>
					<p style="line-height:30px" class="c-fl mt10 fs16"><span class="c-fl ml10"> <?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>：</span><span class="c-fl" id="tname"></span></p>
					<?php }else{; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class"]["class_name"])){; ?>
					<h2 id="t2" classid="<?php echo SlightPHP\Tpl::$_tpl_vars["class"]['class_id']; ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["class"]['class_name'],'site.index'); ?></h2>
					<?php }else{; ?>
					<h2 id="t2"><?php echo tpl_modifier_tr('该课程还未开设班级,请建立班级','site.course'); ?></h2>
					<?php }; ?>
					<p style="line-height:30px" class="c-fl mt10 fs16"><span class="c-fl"> <?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>：</span><span class="c-fl" id="tname"></span></p>
					<?php }; ?>
					<div class="course-info">
						<dl id="t"></dl>
				</div></div>
			</div>
			</section>
			<?php echo tpl_function_part("/site.main.footer"); ?>
		</body>
		<script>
			$(function(){
				var type = <?php echo SlightPHP\Tpl::$_tpl_vars["course"]['type']; ?>;
				if(type == 2)
				{
					var cid = $('#t2').attr('classid');
				}else
				{
					var cid = $('#sel option:first').val();
				}
				select(cid);
			});
			function getCookie(cookieName) {
				var strCookie = document.cookie;
				var arrCookie = strCookie.split("; ");
				for(var i = 0; i < arrCookie.length; i++){
					var arr = arrCookie[i].split("=");
					if(cookieName == arr[0]){
						return arr[1];
					}
				}
				return "";
			}
			function select(cid)
			{
				$('#t').html(html);
				var course_id = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
				var type = <?php echo SlightPHP\Tpl::$_tpl_vars["course"]['type']; ?>;
				var html = '';
				var lan = getCookie("language");
				$.post("/course/info/PlanListAjax",{ course_id:course_id,cid:cid,lan:lan },function(r)
				{
					$('#tname').text(r.admin_name);
					for(var i in r.data)
					{
						html+="<dd class='col-sm-20 fs14'>"; 
						html+="<div class='col-sm-3'>"+r.data[i].section_name+"</div>";
						html+="<div class='col-sm-14 length_sl'>";
						if(type == 2)
						{
							html+=r.data[i].section_descipt;
						}else
						{
							html+=r.data[i].section_descipt+'<span class="ml10">'+r.data[i].start_time+' '+r.data[i].teacher_name+'</span>';
						}

						html+="</div>";
						html+="<div class='col-sm-3'>";
						if(r.data[i].status==3)
						{
							html+='<a href="/user.teacher.part/'+r.data[i].plan_id+'/2" target="_blank" class="btn_yellow_2_1 col-sm-20"><?php echo tpl_modifier_tr("视频管理","site.course"); ?></a>';
						}else
						{
							html+='<a href="/user.teacher.upload/'+r.data[i].plan_id+'/2" target="_blank" class="btn_yellow_2_1 col-sm-20"><?php echo tpl_modifier_tr("上传视频","site.course"); ?></a>';
						}
						html+="</div>";
						html+="</dd>";
					}

					$('#t').html(html);
				},'json');
			}
		</script>
	</html>
