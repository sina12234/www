<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>我的学生 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的学生 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
<!--header-->
<?php echo tpl_function_part("/index.main.usernav.teacher"); ?>
<section class="p20">
    <div class="container">
    <div class="row">
    <!-- leftmenu start-->
    <?php echo tpl_function_part("/index.main.menu.teacher"); ?>
    <!-- leftmenu end -->
		<div class="col-lg-16 col-md-16 col-sm-20 col-xs-20">
		<!--mob-->
    <p class="mob-nav hidden-lg hidden-md">
        <a href="/index.teacher.student" class="col-xs-7">我的学生</a>
        <a href="/index.teacher.timetable" class="col-xs-7">我的课程</a>
        <a href="/index.teacher.edit" class="col-xs-6">教师资料</a>
    </p>
            <div class="right-content">
					<h1 class="fs16 fob">我的学生</h1>
					<div class="list-filter">
						<div class="c-fl">
							<select name="course_id" id="course_id" class="form-control">
								<option value="0">请选择课程</option>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["coursedata"])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["coursedata"] as SlightPHP\Tpl::$_tpl_vars["_course"]){; ?>
								<?php if(mb_strlen(SlightPHP\Tpl::$_tpl_vars["_course"]['title'],'utf8')>16){; ?>
								<option title="<?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['title']; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["course_id"]==SlightPHP\Tpl::$_tpl_vars["_course"]['course_id']){; ?>selected<?php }; ?> value=<?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['course_id']; ?>><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["_course"]['title'],0,15,'utf-8'); ?>... (报名:<?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['user_total']; ?>)</option>
								<?php }else{; ?>
								<option <?php if(SlightPHP\Tpl::$_tpl_vars["course_id"]==SlightPHP\Tpl::$_tpl_vars["_course"]['course_id']){; ?>selected<?php }; ?> value=<?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['course_id']; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['title']; ?> (报名:<?php echo SlightPHP\Tpl::$_tpl_vars["_course"]['user_total']; ?>)</option>
								<?php }; ?>
								<?php }; ?>
								<?php }; ?>
							</select>
							<select id="class" name="class_id" class="form-control">
								<option value="0">所有班级</option>
							</select>
				<?php /*			<select name="" id="">
								<option value="">课程</option>
								<option value="">初一数学</option>
							</select>
							<select name="" id="">
								<option value="">班级</option>
								<option value="">火箭班</option>
								<option value="">冲刺班</option>
							</select>
				*/?>
						</div>
						<?php /*  <div class="num fs14 c-fl">共有学生<span class="cYellow">30</span>个</div> */?>
						  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?>
						  	<div class="num fs14 c-fl cBlue hidden-xs">
						  		<a href="/phpexcel/platformstusearch?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>&sedata=<?php echo SlightPHP\Tpl::$_tpl_vars["sedata"]; ?>" target="_blank">导出Excel</a>
						  	</div>
				  		<?php }else{; ?>
							<div class="num fs14 c-fl cBlue hidden-xs">
								<a href="/phpexcel/platformstusearch?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course_id"]; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>" target="_blank">导出Excel</a>
								</div>
						  <?php }; ?>
                        <div class="so-student t-so-student col-xs-20 pd0 col-sm-4" id="so_student">
                          	<input type="text" id="searchdata" placeholder="输入手机号或姓名" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["sedata"]; ?><?php }; ?>">
                          	<span class="del-btn icon"></span>
                          	<span class="so-btn icon" id="so_btn_icon"></span>
                          	<span class="icon s-so-icon new-so-icon c-fr"></span>
                        </div>
					</div>
					<ul class="list-student t-list-students">
						<li class="fs16">
						<div class="col-md-3 row col-lg-3 col-sm-4 col-xs-8">姓名</div>
						<div class="row col-lg-2 col-sm-2 col-md-2 col-xs-4">性别</div>
						<div class="col-md-4 row col-lg-3 col-sm-3 col-xs-8">手机号</div>
						<div class="col-md-2 row col-lg-3 col-sm-2 hidden-xs">地区</div>
						<div class="col-md-5 row col-lg-3 col-sm-5 hidden-xs">课程</div>
						<div class="col-md-4 row col-lg-3 col-sm-4 hidden-xs">班级</div>
						<div class="col-sm-4 hidden-md hidden-sm hidden-xs row col-lg-3 col-md-4">报名时间</div>
					<?php /*
						<div class="col-md-4 row">出勤率</div>
						<div class="col-md-4 row">发消息</div>
						<div class="col-md-2 row">作业完成情况</div>
					*/?>
						</li>
						<?php if(empty(SlightPHP\Tpl::$_tpl_vars["ret"]->data)){; ?>
						<div class="row">
							<div class="col-md-10 col-md-offset-5 course-schedule mt15">
								<div class="col-md-20 mt15 fs14 tec">
									<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"/> <br>
									<?php if(empty(SlightPHP\Tpl::$_tpl_vars["course_id"])){; ?>
									<span>请选择需要查看的课程</span>
									<?php }else{; ?>
									<span>这个课程还没有报名的学生</span>
									<?php }; ?>
								</div>
							</div>
						</div>
						<?php }else{; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ret"]->data)){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["ret"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<li class="fs14">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->name)){; ?>
							<div class="col-sm-4 row col-lg-3 col-md-3 col-xs-8"><span class="avatar">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->thumb_small)){; ?>
							<?php /*<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->thumb_small); ?>">*/?>
							<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/no-img.png'); ?>">
							<?php }else{; ?>
							<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/no-img.png'); ?>">
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name)){; ?>
							</span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name; ?></div>
							<?php }else{; ?>
							</span>未完善</div>
							<?php }; ?>
						<?php }else{; ?>
						<div class="col-sm-2 col-xs-8 row col-lg-3">
							<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/no-img.png'); ?>">
						<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->real_name; ?></div>
						<?php }; ?>

						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info)){; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender ==1){; ?>
						<div class="col-sm-2 row col-lg-2 col-xs-4">男</div>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->user_info->gender ==2)){; ?>
						<div class="col-sm-2 row col-lg-2 col-xs-4">女</div>
						<?php }else{; ?>
						<div class="col-sm-2 row col-lg-2 col-xs-4">未设置</div>
						<?php }; ?>
						<?php }else{; ?>
						<div class="col-sm-2 row col-lg-2 col-xs-4">未设置</div>
						<?php }; ?>
						<?php if(isset(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile)){; ?>
						<div class="col-sm-3 col-md-4 row col-lg-3 col-xs-8"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->mobile; ?></div>
						<?php }else{; ?>
						<div class="col-sm-3 col-xs-8 col-md-4 row col-lg-3">未设置</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->user_info)){; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province==SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city){; ?>
						<div class="col-sm-2 row col-lg-3 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?></div>
						<?php }else{; ?>
						<div class="col-sm-2 row col-lg-3 hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->province; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_info->city; ?></div>
						<?php }; ?>
						<?php }else{; ?>
						<div class="col-sm-2 row col-lg-3 hidden-xs">未设置</div>
						<?php }; ?>
						<?php /*<div class="col-sm-3 row col-lg-3"><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->title; ?></div>*/?>
						<div class="col-sm-5 row col-lg-3 hidden-xs">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["coursedata"][SlightPHP\Tpl::$_tpl_vars["v"]->cid]['title'])){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["coursedata"][SlightPHP\Tpl::$_tpl_vars["v"]->cid]['title']; ?>
						<?php }else{; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["coursesdata"][SlightPHP\Tpl::$_tpl_vars["v"]->cid]->course_name)){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["coursesdata"][SlightPHP\Tpl::$_tpl_vars["v"]->cid]->course_name; ?>
							<?php }else{; ?>
							课程加载失败
							<?php }; ?>
						<?php }; ?>
						</div>
						<?php /*<div class="col-sm-2 row col-lg-3"><?php echo SlightPHP\Tpl::$_tpl_vars["classes"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name; ?></div>*/?>
						<div class="col-sm-4 row col-lg-3 hidden-xs">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classShowdata"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name)){; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["classShowdata"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name; ?>
						<?php }else{; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->class_id; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classesdata"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name)){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["classesdata"][SlightPHP\Tpl::$_tpl_vars["v"]->class_id]->name; ?>
							<?php }; ?>

						<?php }; ?>

						</div>
						<div class="col-sm-2 hidden-md hidden-xs hidden-sm row col-lg-3" style="text-overflow:clip;display:inline;"><?php echo date("Y-m-d",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->create_time)); ?></div>
			<?php /*			<div class="col-sm-1 row">80%</div>
						<div class="col-sm-2 row">80%</div>
			*/?>
						</li>
							<?php }; ?>
							<?php }; ?>
						<?php }; ?>
			<?php /*			<li class="fs14">
						<div class="col-sm-1 row">李天一</div>
						<div class="col-sm-1 row">男</div>
						<div class="col-sm-2 row">13800110011</div>
						<div class="col-sm-2 row">初一数学</div>
						<div class="col-sm-1 row">火箭班</div>
						<div class="col-sm-2 row">2015-01-10</div>
						<div class="col-sm-1 row">80%</div>
						<div class="col-sm-2 row">80%</div>
						</li>
				*/?>
					</ul>
					<div class="col-sm-12">
						<?php /*
						<div class="page-list">
							<ul>
								<li class="prev prev-disabled">
								首页
								</li>
								<li class="prev"><a href="#">上一页</a></li>
								<li class="active">1</li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li class="dot">...</li>
								<li><a href="#">10</a></li>
								<li class="next"><a  href="#">下一页</a></li>
								<li class="next prev-disabled">末页</li>
							</ul>
						</div>
						*/?>
					</div>
				</div>
			</div>
			<div class='clear'></div>
		</div></div>
		</section>
		<?php echo tpl_function_part("/index.main.footer"); ?>
	</body>
</html>
		<script type="text/javascript">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["coursedata"])){; ?>
			var course_data=<?php echo SJson::encode(SlightPHP\Tpl::$_tpl_vars["coursedata"]); ?>;
			var class_data=<?php echo SJson::encode(SlightPHP\Tpl::$_tpl_vars["classdata"]); ?>;
			//console.log(course_data);
			<?php }else{; ?>
			var course_data=[];
			<?php }; ?>
			var class_id="<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>";
		/*
			*/
			$(document).ready(function(){
				//$("#course_id").change(function(){
					var tmp_cid = $("#course_id ").val();
					for(var i in class_data){
						if(i==tmp_cid){
							//console.log(i);
							//console.log(tmp_cid);
							//console.log("123");
							$("#class").html("");
							$("#class").append('<option value="0">所有班级</option>');
							for(var j in class_data[i]){
								s="";
								//console.log(class_data[i][j].class_id);
								//console.log(course_data[i]);
								
								if(class_data[i][j].class_id==class_id){
									s=" selected "; 
								}
								$("#class").append('<option '+s+' value="'+class_data[i][j].class_id+'">'+class_data[i][j].name+'</option>');
								//console.log("ok");
								//console.log("ok i"+class_data[j][h]+"/n");

								//$("#class").append('<option '+s+' value="'+class_data[i][j].class_id+'">'+class_data[i][j].name+'</option>');
							}
						}
					}
				//});
					
				/*	for(var i in course_data){
						if(course_data[i].course_id==tmp_cid){
							$("#class").html("");
							$("#class").append('<option value="0">所有班级</option>');
							for(var j in course_data[i].class){
								s="";
								console.log(course_data[i]);
								if(class_id == course_data[i].class[j].class_id)s=" selected ";
								$("#class").append('<option '+s+' value="'+course_data[i].class[j].class_id+'">'+course_data[i].class[j].name+'</option>');
							}
						}
					}
					*/
			//	}).trigger("change");
			//	});

				//t-my-student-wrap无内容时填充内容高度
				if(screen.availWidth > 768) {
					if($('.t-my-student-wrap').outerHeight() <= 223){
						$('.t-my-student-wrap').css('height','400');
					}
				}

			});

			$('#class').change(function(){
				var p1 = $("#course_id").val();
				var p2 = $(this).children('option:selected').val();//这就是selected的值
				var sedata = $("#searchdata").val();
				if(sedata.length==0){
					window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
				}else{
					window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2+"&sedata="+sedata;//页面跳转并传参
				}
				//window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
			})

			$('#course_id').change(function(){
				var p1 = $("#course_id").val();
				var p2 = $("#class").children('option:selected').val();//这就是selected的值
				p2=0;
				var sedata = $("#searchdata").val();
				//window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
				if(sedata.length==0){
					window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
				}else{
					window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2+"&sedata="+sedata;//页面跳转并传参
				}
			})
			/*$('.num').click(function(){
				var sedata = $("#searchdata").val();
				//console.log("112 is"+sedata);
				window.location.href="/index.teacher.student?sedata="+sedata;//页面跳转并传参
			})
			*/
			$("#searchdata").keydown(function(e){
				var p1 = $("#course_id").val();
				var p2 = $("#class").children('option:selected').val();//这就是selected的值
				var curKey = e.which;
				if(curKey == 13){
					var sedata = $("#searchdata").val();
					//window.location.href="/index.teacher.student?sedata="+sedata;//页面跳转并传参
					if(sedata.length==0){
						window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
					}else{
						window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2+"&sedata="+sedata;//页面跳转并传参
					}
					//window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2+"&sedata="+sedata;//页面跳转并传参
				}
			});
           
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sedata"])){; ?>
				$("#so_student .s-so-icon").hide();
				$("#so_student input").show();
				$("#so_student .so-btn").hide();
				$("#so_student .del-btn").show();
			<?php }else{; ?>
	            $("#so_student .s-so-icon").click(function(){
	                $(this).hide();
	                $("#so_student input").show();
	                $("#so_student .so-btn").show();
	            })
			<?php }; ?>
		
	    $("#so_student input").keyup(function(){
	        $("#so_student .so-btn").hide();
	        //$("#so_student .del-btn").show();
			$("#so_student .so-btn").show();
	    })
		$("#so_btn_icon").click(function(){
				var p1 = $("#course_id").val();
				var p2 = $("#class").children('option:selected').val();
				var sedata = $("#searchdata").val();
				if(sedata.length==0){	window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;//页面跳转并传参
				}else{
						window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2+"&sedata="+sedata;//页面跳转并传参
				}
		});
		var p1 = $("#course_id").val();
		var p2 = $(this).children('option:selected').val();
		if(!p2){
			p2=0;
		}
	    $("#so_student .del-btn").click(function(){
	    	$(this).hide();
	    	$("#so_student .so-btn").hide();
	        $("#so_student input").val('');
	        //$("#so_student input").hide();
	        $("#so_student .s-so-icon").hide();
			window.location.href="/index.teacher.student?course_id="+p1+"&class_id="+p2;
	    })
		</script>
