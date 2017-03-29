<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>创建课程章节 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/searchteacher.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40">
<div class="container">
<?php echo tpl_function_part("/org.main.menu.course"); ?>
	<div class="right-main col-sm-9 col-md-16">
		<div class='content'>
				<div class="manage-path"><a href="/user.org.course"><?php echo tpl_modifier_tr('课程管理','site.course'); ?></a>><span class="cGray"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["courseTypeShow"],'site.course'); ?></span></div>
				<div class="step">
					<ul class="col-sm-9 col-md-20">
						<li class="col-sm-4 col-md-7">
						<p class="bg1"></p>
						<p class="n">
						1
						</p>
						<p class="text"><?php echo tpl_modifier_tr('课程信息','site.course'); ?></p>
						</li>
						<li class="col-sm-4 col-md-7">
						<p class="bg1"></p>
						<p class="n">
						2
						</p>
						<p class="text"><?php echo tpl_modifier_tr('设置章节','site.course'); ?></p>
						</li>
						<li class="col-sm-4 col-md-6">
						<p class="bg2"></p>
						<p class="n2">
						3
						</p>
						<p class="text2"><?php echo tpl_modifier_tr('开班授课','site.course'); ?></p>
						</li>
					</ul>
				</div>
				<div class="class-list fs14">
					<dl id="dl">
						<dd>
						<p class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('班级名','site.course'); ?></p>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<p class="col-sm-1 col-md-3"><?php echo tpl_modifier_tr('座位','site.course'); ?></p>
						<p class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('开课时间','site.course'); ?></p>
						<?php }; ?>
						<p class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('排课','site.course'); ?></p>
						<p class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('主讲老师','site.course'); ?></p>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<p class="col-sm-1 col-md-3"><?php echo tpl_modifier_tr('进度','site.course'); ?></p>
						<?php }; ?>
						<p class="col-sm-2 col-md-2"><?php echo tpl_modifier_tr('操作','site.course'); ?></p>
						</dd>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list_class"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["list_class"] as SlightPHP\Tpl::$_tpl_vars["_class"]){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->name)){; ?>
						<dt class="class">
						<p class="col-sm-2 col-md-3"><?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->name; ?></p>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<p class="col-sm-1 usertotal col-md-3" max="<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->max_user; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->max_user; ?></p>
						<p class="col-sm-2 col-md-3"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->plans[0]->start_time)){; ?><?php echo date("Y-n-j",strtotime(SlightPHP\Tpl::$_tpl_vars["_class"]->plans[0]->start_time)); ?><?php }; ?></p>
						<?php }; ?>
						<p class="col-sm-2 col-md-3"><a href="/user.org.editPlan.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->class_id; ?>" class="cGreen3"><?php echo tpl_modifier_tr('查看','site.course'); ?>/<?php echo tpl_modifier_tr('修改','site.course'); ?></a></p>
						<p class="col-sm-2 col-md-3">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->real_name)){; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->real_name; ?>
						<?php }else{; ?>
						<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->name; ?>
						<?php }; ?>
						</p>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<p class="col-sm-1 col-md-3">(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->plansend)){; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["_class"]->plansend); ?><?php }else{; ?>0<?php }; ?>/<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->plans)){; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["_class"]->plans); ?><?php }else{; ?>0<?php }; ?>)</p>
						<?php }; ?>
						<p class="col-sm-2 col-md-2" class_id="<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->class_id; ?>"uid="<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->pk_user; ?>" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>" classname="<?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->name; ?>" teacher="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->teacher->name; ?><?php }; ?>" address="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_class"]->address)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_class"]->address; ?><?php }else{; ?>暂无<?php }; ?>"><a href="" class="cGreen3 edit"><?php echo tpl_modifier_tr('修改','site.course'); ?></a> <a href="#" class="cPink remove"><?php echo tpl_modifier_tr('删除','site.course'); ?></a></p>
						</dt>
						<?php }; ?>
						<?php }; ?>
						<?php }; ?>
					</dl>
				</div>
				<?php if(empty(SlightPHP\Tpl::$_tpl_vars["hideCreate"])){; ?>
				<div class="add-btn">
					<p class="col-sm-7" id="class_add"><a href="#">+<?php echo tpl_modifier_tr('新建班级','site.course'); ?></a></p>
				</div>
				<?php }; ?>
				<form id ="form" method="post" name="oForm" action="/user.org.addPlan.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>">
					<div class="course-form fs14" id="class_add_text" style="display:none;">
						<ul class="col-sm-7 col-md-20">
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('班级名称','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" placeholder="1班" class="col-md-20 judgeval" id="className" name="classname">
							</div>
							</li>
							<li>
							<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('开课时间','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" value="" class="col-md-20 judgeval start-subject-time" id="start_time1" name="start_time1">
							</div>
							</li>
							<script>
								$(function() {
									//$( "#start_time1" ).datetimepicker({ timepicker:true,lang:'zh', format:'Y-m-d H:i',step:15, minDate:'-1970/01/01'});
								});
							</script>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('课程时长','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<select class="col-md-20" name="lasttime" id = "lasttime" style="height:35px;padding:0px">
									<option value="0"selected><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>
									<option value="1800">0.5<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="3600">1<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="5400">1.5<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="7200">2<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="9000">2.5<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="10800">3<?php echo tpl_modifier_tr('小时','site.course'); ?></option>
									<option value="1"><?php echo tpl_modifier_tr('自定义','site.course'); ?></option>
								</select>
								<div id="lastslef_div" style="display:none;">
								<input class="col-sm-6 ml5" placeholder="100" type="text" name="lasttime_self" value="" 
								onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
								<span style="vertical-align:-8px;">&nbsp;分钟</span>
								</div>
							</div>
							</li>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('排课时间','site.course'); ?>：</div>
							<div class="label-for col-md-10">
								<select class="col-sm-4" name="quicksettime" id = "select_day" style="height:35px;padding:0px">
									<option value="1"selected><?php echo tpl_modifier_tr('每周一','site.course'); ?></option>
									<option value="2" ><?php echo tpl_modifier_tr('每周二','site.course'); ?></option>
									<option value="3" ><?php echo tpl_modifier_tr('每周三','site.course'); ?></option>
									<option value="4" ><?php echo tpl_modifier_tr('每周四','site.course'); ?></option>
									<option value="5" ><?php echo tpl_modifier_tr('每周五','site.course'); ?></option>
									<option value="6" ><?php echo tpl_modifier_tr('每周六','site.course'); ?></option>
									<option value="0" ><?php echo tpl_modifier_tr('每周日','site.course'); ?></option>
									<option value="8" ><?php echo tpl_modifier_tr('自定义','site.course'); ?></option>
								</select>
								<div id="timebyself" style="display:none;" class="timebyself"></div>
								<input type="text" placeholder="18:00" class="col-sm-3 col-md-5  c-fl judgeval ml5 cy-select-time" id="hours" name="hours">
								<input type="text" placeholder="18:00" class="col-sm-3 c-fl" id="daybyself" name="daybyself" hidden value="">
								<div class="col-sm-5 cy-weekdate col-md-6">
									<input type="checkbox" name="controlShunyan" /> <?php echo tpl_modifier_tr('避开节假日','site.course'); ?>
								</div>
								<div class="col-sm-12" style="padding:0" id="timeappend">
									<?php /*	<span>5月1日</span> <span>5月13日</span> <span>5月1日</span>*/?>
								</div>
							</div>
							</li>
							<?php }; ?>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('主讲教师','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" name="user_class_id" size="24" maxlength="255" class="new-keyword text judgeval" value="" id="keyword" placeholder="<?php echo tpl_modifier_tr('输入要搜索的老师','site.course'); ?>" autocomplete="off" style="width:100%"><span class="hide-icon" id="key-hide"></span>
								<input type="text" class="col-md-20 judgeval" name="user_class_id" id="teahidden" value="" hidden>
								<ul class='last' style="display:none;">
								</ul>
						</div>
						</li>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<li>
						<div class="label col-md-8"><?php echo tpl_modifier_tr('上课桌位','site.course'); ?>：</div>
						<div class="label-for col-md-5">
							<input type="text" placeholder="100" class="col-md-20 judgeval" name="max_user" id="max_user" onkeypress = 'return /^\d$/.test(String.fromCharCode(event.keyCode||event.keycode||event.which))'
							oninput= 'this.value = this.value.replace(/\D+/g, "")'
							onpropertychange='if(!/\D+/.test(this.value)){ return;};this.value=this.value.replace(/\D+/g, "")'
							onblur = 'this.value = this.value.replace(/\D+/g, "")'>
						</div>
						</li>
						<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==3){; ?>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('上课地址','site.course'); ?>：</div>
							<div class="label-for col-md-8 col-xs-12">
								<select name="region_level0" id="level0" class="col-sm-3 col-xs-4">
									<option value=""><?php echo tpl_modifier_tr('请选择','site.user'); ?></option>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level0"])){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["level0"] as SlightPHP\Tpl::$_tpl_vars["region"]){; ?>
									<option value="<?php echo SlightPHP\Tpl::$_tpl_vars["region"]->region_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["region"]->name; ?></option>
									<?php }; ?>
									<?php }; ?>
								</select>
								<select name="region_level1" id="level1" class="col-sm-3 ml10 col-xs-4">
									<option value=""><?php echo tpl_modifier_tr('请选择','site.user'); ?></option>
								</select>
								<select style="display:none" class="col-sm-3 ml10 col-xs-3" name="region_level2" id="level2">
									<option value=""><?php echo tpl_modifier_tr('请选择','site.user'); ?></option>
								</select>
								<span id='region1' class="cRed"></span>
							</div>
							</li>
						<li>
							<div class="label col-md-8"></div>
							<div class="label-for col-md-8">
								<input type="text" placeholder="请输入街道详细地址..." class="col-md-20 judgeval" name="address" id="">
							</div>
						</li>
							<?php }; ?>
						<li>
						<div class="label col-md-8"></div>
						<div class="label-for col-md-5">
							<button class="col-sm-3 col-md-10"><?php echo tpl_modifier_tr('添加','site.course'); ?></button>
							</div>
							</li>
						</ul>
					</div>
				</form>
					<div class="course-form fs14" id="editClass" style="display:none;">
						<ul class="col-md-20">
							<li>
							<div class="error"></div>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('班级名称','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" placeholder="1班" class="col-md-20" name="editName" id="editName">
								<input type="text" placeholder="100" class="col-md-20" name="editClassid" id="editClassid" hidden>
							</div>
							</li>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('主讲教师','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" name="" size="24" maxlength="255" class="new-keyword text" value="" id="editkeyword" placeholder="<?php echo tpl_modifier_tr('输入要搜索的老师','site.course'); ?>" style="width:100%"><span class="hide-icon" id="edit-hide"></span>
								<input type="text" class="col-sm-12" name="editTeacher" id="editTeacher" value="" hidden>
								<ul class='editlast' style="display:none;">
								</ul>
							</div>
							</li>
							<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('上课桌位','site.course'); ?>：</div>
							<div class="label-for col-md-5">
								<input type="text" placeholder="50" class="col-md-20" name="editMaxuser" id="editMaxuser" onkeypress = 'return /^\d$/.test(String.fromCharCode(event.keyCode||event.keycode||event.which))'
								oninput= 'this.value = this.value.replace(/\D+/g, "")'
								onpropertychange='if(!/\D+/.test(this.value)){ return;};this.value=this.value.replace(/\D+/g, "")'
								onblur = 'this.value = this.value.replace(/\D+/g, "")'>
							</div>
							</li>
							<?php }; ?>
							<li>
							<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==3){; ?>
							<li>
							<div class="label col-md-8"><?php echo tpl_modifier_tr('详细地址','site.course'); ?>：</div>
							<div class="label-for col-md-8">
								<input type="text" placeholder="50" class="col-sm-20" id="address">
							</div>
							</li>
							<?php }; ?>
							<div class="label col-md-8"></div>
							<div class="label-for col-md-5">
								<button class="col-sm-3 col-md-10" id="editConfirm"><?php echo tpl_modifier_tr('修改','site.course'); ?></button>
							</div>
							</li>
						</ul>
					</div>
				<div class="course-step col-md-20 col-md-offset-6">
					<ul class="col-md-20">
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<li><input type="checkbox" id="adminstatus1" checked><?php echo tpl_modifier_tr('立即上架','site.course'); ?> <span class="cGreen3">(<?php echo tpl_modifier_tr('上架后课程将在8分钟内展现在前台，请耐心等待','site.course'); ?>)</span></li>
						<?php }; ?>
						<li>
						<a href="/user.org.sectioninfo.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>"><button class="btn1 col-sm-5 col-md-3  fs16"><?php echo tpl_modifier_tr('上一步','site.course'); ?></button></a>
						<button class="btn2 col-sm-5 fs16 col-md-3" id="adminstatus"><?php echo tpl_modifier_tr('完成','site.course'); ?></button>
						</li>
					</ul>

				</div>
			</div>
		</div>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
<script>
$(function() {
	var defClass = $("#dl>dt").length;
	$("#className").val((defClass+1)+"<?php echo tpl_modifier_tr('班','site.course'); ?>");

	$.divselect(".divselect");
	$("#class_add").click(function(){
					$("#class_add_text").toggle();
					$("#editClass").hide();
					if($("#class_add_text").is(":hidden")){
						//if($("#editClass").is(":hidden")){
							$(".course-step").show();
							}else{
							$(".course-step").hide();
						//}
					}
					return false;
				});
				$("#adminstatus").click(function(){
					var checkads = $("#adminstatus1").prop("checked");
					if(checkads){
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list_class"])){; ?>
						$.post("/user.org.setadminstatusajax.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>",{  },function(r){
							if(r.code==0){
								location.href="/user.org.course";
							}else{
								layer.msg(r.error);
								return false;
							}
						},"json");
						<?php }else{; ?>
						layer.msg("请至少创建一个班级");
						return false;
						<?php }; ?>
					}else{
						location.href="/user.org.course";
					}
				})
				$(".edit").click(function(){
					$("#class_add_text").hide();
					$("#editClass").toggle();
					var classid=$(this).parents("p").attr("class_id");
					var userid=$(this).parents("p").attr("uid");
					var cid=$(this).parents("p").attr("cid");
					var usertotal=$(this).parents("dt").children(".usertotal").attr("max");
					var classname = $(this).parents("p").attr("classname");
					var teacher = $(this).parents("p").attr("teacher");
					var address = $(this).parents("p").attr("address");
					$("#editName").val(classname);
					$("#editkeyword").val(teacher);
					$("#editClassid").val(classid);
					$("#editTeacher").val(userid);
					$("#editMaxuser").val(usertotal);
					$("#address").val(address);
					//	$("#editTeacher option["+teacher+"]").attr("selected",true);
					if($("#class_add_text").is(":hidden")){
						//if($("#editClass").is(":hidden")){
							$(".course-step").show();
							}else{
							$(".course-step").hide();
						//}
					}
				return false;
				});
				$("#editConfirm").click(function(){
					var reName	= $("#editName").val();
					var reTeacher	= $("#editkeyword").val();
					var reTeacherid	= $("#editTeacher").val();
					var reNamelen = reName.length;
					if(reNamelen>=6){
						//layer.msg("班级名称不能超过5个字哦~");
						layer.msg("<?php echo tpl_modifier_tr('班级名称不能超过5个字','site.course'); ?>");
						return ;
					}
					/*
					开放筛选老师
					*/
					if(reTeacher.length == 0){
						layer.msg("老师不能为空哦~");
						return ;
					}
					var reTeacher = $("#editTeacher").val();
					var classId = $("#editClassid").val();
					var classMaxuser = $("#editMaxuser").val();
					var address = $("#address").val();
					<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
					$.post("/user.org.EditClassAjax",{ reName:reName,reTeacher:reTeacher,classId:classId,classMaxuser:classMaxuser
					<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==3){; ?>
					,address:address
					<?php }; ?>
					},function(r){
					<?php }else{; ?>
					$.post("/user.org.EditClassvideoAjax",{ reName:reName,reTeacher:reTeacher,classId:classId },function(r){
					<?php }; ?>
						if(r.error){
							$(".error").html(r.error);
							$("[name="+r.field+"]").focus();
							alert(r.error);
							return false;
						}
						if(r){
							location.reload();
							return false;
						}
					},"json");


				});

			});
</script>
<script>
$(function() {
//默认开课时间
	var date = new Date();
	var year = date.getFullYear();
	var Month = date.getMonth()+1;
	var Nowth = date.getDate();
	var h = date.getHours();
	var mi = date.getMinutes();
	if(Month < 10){
        Month = "0" + Month;
    }
    if(h < 10){
        h = "0" + h;
    }
    if(mi < 10){
        mi = "0" + mi;
    }
    var oNowDate = year+"-"+Month+"-"+Nowth+" "+h+":"+mi;
    $("#start_time1").val(oNowDate);
    //避开节假日
    $("select[name='quicksettime']").change(function() {
        var oSelectVal = $("select[name='quicksettime']").val();
	        if(oSelectVal == 8) {
                $(".cy-weekdate").hide();
		    }else{
	            $(".cy-weekdate").show();
            }
	});
    $( "#start_time1" ).datetimepicker({ timepicker:true, format:'Y-m-d H:i',step:15, minDate:'-1970/01/01'});
    $( "#timebyself" ).datetimepicker({ timepicker:false, format:'Y-m-d H:i',step:15, minDate:'-1970/01/01',onSelectDate:function(ct,$i){
        var cts = ct.dateFormat('n月j日');
		var cts = ct.dateFormat('Y-m-d');
        var days = $("#daybyself").val();
        var countsec = "<?php echo SlightPHP\Tpl::$_tpl_vars["count_section"]; ?>";
        var timeset = $("#timeappend>span").length;
        if(timeset < countsec-1){
            $("<span>"+cts+"</span>"+"<b> </b>").appendTo("#timeappend");
            $("#daybyself").val(cts+","+days);
        }
    }
	});
				$( "#hours" ).datetimepicker({ datepicker:false, format:'H:i',step:15,});

$("#select_day").change(function(){
    var selday = $("#select_day").val();
    if(selday==8){
        $("#timebyself").show();
    }else{
        $("#timebyself").hide();
        $("#timeappend").empty();
        $("#daybyself").val("");
    }
})
$("#lasttime").change(function(){
    var lasttime = $(this).val();
    if(lasttime == 1){
        $("#lastslef_div").show();
    }else{
        $("#lastslef_div").hide();
        $("input[name=lasttime_self]").val('');
    }
})

$(".remove").click(function(){
    var lang = getCookie("language");
    var confirms;
    //console.log(lang);
    if(lang == "zh-cn"){
        confirms = "您确定要删除这个班级吗";
    }else if(lang == "en"){
        confirms = "Are you sure you want to delete this class";
    }else{
        confirms = "您确定要删除这个班级吗";
    }
    if(confirm(confirms)){
        //if(confirm("<?php echo tpl_modifier_tr('您确定要删除这个班级吗','site.course'); ?>")){
        var class_id=$(this).parents("p").attr("class_id");
        var cid=$(this).parents("p").attr("cid");
        $.post("/user.org.DelClassAjax",{ classid:class_id,couid:cid },function(r){
            if(r.error){
                layer.msg(r.error);
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

				var form = $("#form");
				var clName = $("#className");
				form.submit(function(){
					var flagthis = true;
					var	clNamelen = clName.val().length;
					<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==3){; ?>
					var region_level0 = $('select[name=region_level0]').val();
					var region_level1 = $('select[name=region_level1]').val();
					var region_level2 = $('select[name=region_level2]').val();
					if(!region_level0 || !region_level1){
						$('#region1').html('请选择常住地区');
						$('#level0').addClass('box-shadow');
						return false;
					}else{
						$('#region1').html('');
						$('#level0').removeClass('box-shadow');
					}
					<?php }; ?>

					$(".judgeval").each(function(){
						var thisval = $(this).val();
						if(0==thisval.length){
							flagthis = false;
							$(this).css({ "borderColor":"red"});
							}else{
							$(this).css({ "borderColor":"#ccc"});
						}
					});


					var lastTime =$("#lasttime").val();
					var lasttimeself = $('input[name=lasttime_self]').val();
					if( lastTime == 1 ){
						if( !lasttimeself ){
							layer.msg("请设置课程时长");
							flagthis = false;
						}
						if( lasttimeself > 1000 ){
							layer.msg("课程时长不能超过1000分钟!");
							flagthis = false;
						}
					}else if(0>=lastTime){
						layer.msg("请设置课程时长");
						flagthis = false;
					}
					if(clNamelen >= 6){
						layer.msg("<?php echo tpl_modifier_tr('班级名称不能超过5个字','site.course'); ?>");
						flagthis = false;
					}
					if(!flagthis){
						return false;
					}
		});




		<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==3){; ?>
		var region_level0 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0; ?><?php }; ?>";
		var region_level1 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level1; ?><?php }; ?>";
		var region_level2 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level2; ?><?php }; ?>";

		$("#level0").change(function(){
			var pid = $(this).val();
			if(pid){
				$.get("/user.course.ListRegion."+pid,{ },function(item){
					$("#level1").html("");
					if(item){
						$("#level1").append("<option  value=''>请选择</option>");
						for(var i in item){
							var s="";
							if(item[i].region_id == region_level1){
								s=" selected ";
							}
							$("#level1").append("<option "+s+" value='"+item[i].region_id+"'>"+item[i].name+"</option>");
						}
						$("#level1").show().trigger("change");
					}else{
						$("#level1").hide();
					}
				},"json");
				$('#level2').hide();
			}else{
				$('#level1').empty();
				$("#level1").append("<option  value=''>请选择</option>");
				$('#level2').empty();
				$("#level2").append("<option  value=''>请选择</option>");
				$('#level2').hide();
			}
		}).trigger("change");


		$("#level1").change(function(){
			var pid = $(this).val();
			$("#level2").empty();
			$("#level2").append("<option  value=''>请选择</option>");
					
			if(pid){
				$.get("/user.course.ListRegion."+pid,{ },function(item){
					$("#level2").html("");
					if(item){
						$("#level2").append("<option value=''>请选择</option>");
						for(var i in item){
							var s="";
							if(item[i].region_id == region_level2){
								s=" selected ";
							}
							$("#level2").append("<option "+s+" value='"+item[i].region_id+"'>"+item[i].name+"</option>");
							}
						$("#level2").show().trigger("change");
					}else{
						$("#level2").hide();
					}
				},"json").fail(function(){
				$("#level2").hide();
				});
			}
		});
		<?php }; ?>

});


function getCookie(cookieName) {
    var cookieValue = document.cookie;
    var cookieStartAt = cookieValue.indexOf(""+cookieName+"=");
    if(cookieStartAt==-1)  {
		cookieStartAt = cookieValue.indexOf(cookieName+"=");
    }
	if(cookieStartAt==-1)  {
        cookieValue = null;
	} else {
		cookieStartAt = cookieValue.indexOf("=",cookieStartAt)+1;
        cookieEndAt = cookieValue.indexOf(";",cookieStartAt);
		if(cookieEndAt==-1){
			cookieEndAt = cookieValue.length;
        }
		cookieValue = unescape(cookieValue.substring(cookieStartAt,cookieEndAt));//解码latin-1
	}
    return cookieValue;
} 

</script>
</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
