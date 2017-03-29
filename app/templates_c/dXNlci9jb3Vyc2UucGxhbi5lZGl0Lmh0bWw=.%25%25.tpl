<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>修改排课</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40">
    <div class="container">
        <div class="row">
		<?php echo tpl_function_part("/org.main.menu.course"); ?>
		<div class="right-main col-md-16">
				<div class="content">
					<div class="manage-path fs14"><a href="/user.org.addplan.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>"><?php echo tpl_modifier_tr('返回','site.course'); ?></a>><span class="cGray"><?php echo tpl_modifier_tr('修改排课','site.course'); ?></span></div>
			<form action="/user.org.EditPlan.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>" method="post">
					<ul class="course-list">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list_section_data"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["list_section_data"] as SlightPHP\Tpl::$_tpl_vars["_sectionk"]=>SlightPHP\Tpl::$_tpl_vars["_section"]){; ?>
						<li>
						<input type="hidden" name="sectionid[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>">
						<div class="item fs14" style="width:9%;"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["_section"]->name,'site.index'); ?></div>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=2){; ?>
						<div class="item" style="width:19%:"><?php echo tpl_modifier_tr('时间','site.course'); ?>：
							<input type="datetime"  name="secstime[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" class="start-time" placeholder="开始日期" id="coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->start_time)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->start_time; ?><?php }; ?>">
						</div>
						<script>
							$(function() {
							//data-status="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->status; ?><?php }; ?>"
								/*$( "#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" ).datetimepicker({ 
									timepicker:true,lang:'zh', format:'Y-m-d H:i',step:15, mask:true
								});*/
								jQuery('#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').datetimepicker({
									timepicker:true, format:'Y-m-d H:i',step:15, mask:true,
									//format:'Y/m/d',
									validateOnBlur:false,
									onShow:function( ct ){
										this.setOptions({
											minDate:jQuery('#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').val()?jQuery('#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').val():false
										})
									}
									//timepicker:true
								})
								jQuery('#coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').datetimepicker({
									timepicker:true, format:'Y-m-d H:i',step:15, mask:true,
									validateOnBlur:false,
									onShow:function( ct ){
										this.setOptions({
											minDate:jQuery('#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').val()?jQuery('#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').val():false
										})
									},
								});
								//时间戳
							/*
								$("#coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>").blur(function() {
									var getHourseTamp = 3600;
									var getMinutesTamp = 300;
									var startTimeVal = $("#coursedate<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>").val();
									var startimeTamp = Date.parse(new Date(startTimeVal));
										startimeTamp = startimeTamp / 1000;
									var endTimeVal = $("#coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>").val();
									var endTimeTamp = Date.parse(new Date(endTimeVal));
										endTimeTamp = endTimeTamp /1000;
									var diffTimeTamp = endTimeTamp - startimeTamp;
									var allTimeTamp = startimeTamp + getHourseTamp;
									var d = new Date(allTimeTamp * 1000);
										if((d.getMonth() + 1) < 10) {
											dGetMonth = '0' + (d.getMonth() + 1);
										}else {
											dGetMonth = d.getMonth() + 1;
										}
										if(d.getDate() <10) {
											dGetDate = '0' + d.getDate();
										}else {
											dGetDate = d.getDate();
										}
									   if(d.getHours() < 10) {
											var dGetHours = '0' + d.getHours();
										}else {
											var dGetHours = d.getHours();
										}
										if(d.getMinutes() < 10) {
											var dGetMinutes = '0' + d.getMinutes();
										}else {
											var dGetMinutes = d.getMinutes();
										}
										if(d.getSeconds() < 10) {
											var dGetSeconds = '0' + d.getSeconds();
										}else {
											var dGetSeconds = d.getSeconds();
										}
										var date = (d.getFullYear()) + "-" + (dGetMonth) + "-" +(dGetDate) + " " + (dGetHours) + ":" + (dGetMinutes) + ":" + (dGetSeconds);

										var dateDate = date.substr(0,10);
										var dateTimeLength = date.substr(date.length-8);
										var dateTime = dateTimeLength.substr(0,5);

										if (diffTimeTamp < getMinutesTamp) {
											layer.msg("<p><?php echo tpl_modifier_tr('开始时间晚于结束时间','site.course'); ?></p><p><?php echo tpl_modifier_tr('请重新设定','site.course'); ?></p>");
											$("#coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>").val("");
											jQuery('#coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>').datetimepicker({
												timepicker:true, format:'Y-m-d H:i',step:15, mask:true,defaultDate:dateDate,defaultTime:dateTime
											});
											
										}

								})
								*/
							});
						</script>
						<div class="item" style="width:19%;"><?php echo tpl_modifier_tr('到','site.course'); ?>：
							<input type="datetime" name="secetime[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" class="end-time" placeholder="开始日期" id="coursedateend<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->end_time)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->end_time; ?><?php }; ?>">
						</div>
						<?php }; ?>
						<div class="item" style="width:15%">
							<span><?php echo tpl_modifier_tr('讲师','site.course'); ?>：</span>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"])){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->user_plan_id)){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->user_plan_id])){; ?>
									<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->user_plan_id]->real_name; ?>">
									<input type="hidden" class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->user_plan_id]->user_id; ?>">
 									<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]))){; ?>
									<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]->real_name; ?>">
									<input type="hidden" class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]->user_id; ?>">
 									<?php }else{; ?>
									<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
									<input type="hidden" class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
									<?php }; ?>
								<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]))){; ?>
									<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]->real_name; ?>">
									<input type="hidden" class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["user_class_id"]]->user_id; ?>">
								<?php }else{; ?>
									<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
									<input type="hidden" class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
								<?php }; ?>
							<?php }else{; ?>
								<input style="width:78px;" type="text" data-sectionid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="s-teacher tname<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_name[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
								<input type="hidden"  class="tid<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="user_plan_id[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="">
							<?php }; ?>
						</div>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]==1){; ?>
						<div class="item" style="width:17%;"><span><?php echo tpl_modifier_tr('直播试看','site.course'); ?>：</span>
							<div class="divselect" style="width:85px;margin-top: 12px;">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->live_public_type)){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["live_public_type_arr"] as SlightPHP\Tpl::$_tpl_vars["lptak"]=>SlightPHP\Tpl::$_tpl_vars["lptav"]){; ?>
									   	<?php if(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->live_public_type==SlightPHP\Tpl::$_tpl_vars["lptav"]['value']){; ?>
                              			  <cite>
                                              <span class=""></span>
											  <span class="cite-text"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["lptav"]["text"],'site.course'); ?></span>
										  </cite>
                               		 	  <input id="live_public_type<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="live_public_type[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" type="hidden" value = "<?php echo SlightPHP\Tpl::$_tpl_vars["lptav"]['value']; ?>" />
                              		 	<?php }; ?>
									<?php }; ?>
									<?php }else{; ?>
                               		 <cite>
										 <span class="cite-text"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["live_public_type_arr"][0]["text"],'site.course'); ?></span>
									 </cite>
                               		 	  <input id="live_public_type<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="live_public_type[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" type="hidden" value = "<?php echo SlightPHP\Tpl::$_tpl_vars["live_public_type_arr"][0]['value']; ?>" />
									<?php }; ?>
                                <dl>
                                	<?php foreach(SlightPHP\Tpl::$_tpl_vars["live_public_type_arr"] as SlightPHP\Tpl::$_tpl_vars["lptak"]=>SlightPHP\Tpl::$_tpl_vars["lptav"]){; ?>
                                    <dd><a href="javascript:;" selectid="<?php echo SlightPHP\Tpl::$_tpl_vars["lptav"]['value']; ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["lptav"]["text"],'site.course'); ?></a></dd>
                                    <?php }; ?>
                                </dl>
							</div>
						</div>
						<?php }; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["type_id"]!=3){; ?>
						<div class="item" style="width:20%"><span><?php echo tpl_modifier_tr('视频权限','site.course'); ?>：</span>
							<div class="divselect luzhi" style="width:85px;margin-top: 12px;">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_public_type)){; ?>
									<?php foreach(SlightPHP\Tpl::$_tpl_vars["video_public_type_arr"] as SlightPHP\Tpl::$_tpl_vars["vptak"]=>SlightPHP\Tpl::$_tpl_vars["vptav"]){; ?>
									   	<?php if((SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_public_type==SlightPHP\Tpl::$_tpl_vars["vptav"]['pubic_type']) &&(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_trial_time==SlightPHP\Tpl::$_tpl_vars["vptav"]["trial_time"])){; ?>
                              			  <cite>
                                              <span class="cite-text"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["vptav"]["text"],'site.course'); ?></span>
                                          </cite>
                               		 	  <input sid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" class="video_public_type_value" id="video_public_type_value<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="" type="hidden" value = "<?php echo SlightPHP\Tpl::$_tpl_vars["vptav"]['value']; ?>" />
                              		 	<?php }; ?>
									<?php }; ?>
									<?php }else{; ?>
                               		 <cite>
                                         <span class="cite-text"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["video_public_type_arr"][0]["text"],'site.course'); ?></span>
                                     </cite>
                               		 	  <input id="video_public_type_value<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="" type="hidden" value = "<?php echo SlightPHP\Tpl::$_tpl_vars["video_public_type_arr"][0]['value']; ?>" />
									<?php }; ?>
                                <dl>
                                	<?php foreach(SlightPHP\Tpl::$_tpl_vars["video_public_type_arr"] as SlightPHP\Tpl::$_tpl_vars["vptak"]=>SlightPHP\Tpl::$_tpl_vars["vptav"]){; ?>
                                    <dd><a href="javascript:;" sid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" selectid="<?php echo SlightPHP\Tpl::$_tpl_vars["vptav"]['pubic_type']; ?>" trial_time="<?php echo SlightPHP\Tpl::$_tpl_vars["vptav"]['trial_time']; ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["vptav"]["text"],'site.course'); ?></a></dd>
                                    <?php }; ?>
                                </dl>
							</div>
								<input type="hidden" id="video_public_type<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="video_public_type[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_public_type)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_public_type; ?><?php }; ?>"/>
								<input type="hidden" id="video_trial_time<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" name="video_trial_time[<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>]" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_trial_time)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->plan_info->video_trial_time; ?><?php }; ?>"/>
						</div>
						<?php }; ?>
						</li>
					<?php }; ?>
					<?php }; ?>
						<li>
						<button class="sub_btn"><?php echo tpl_modifier_tr('保存','site.course'); ?></button>
						</li>
					</ul>
				</form>
				</div>
			</div>
	</div>
	</div>
</section>
<script>
$(function() {
	$('.s-teacher').each(function(i){
		var sectionId= $(this).attr('data-sectionid');
		$(this).click(function(){
			layer.open({
				type: 2,
				title: ['修改教师','color:#fff;background:#ffa81d'],
				shadeClose: true,
				shade: 0.8,
				area: ['600px', '50%'],
				content: '<?php echo "/user.org.iframeTeacher."; ?>'+sectionId
				});
		});
	});

	var luzhi = $(".luzhi");
	$(".luzhi>dl>dd>a").click(function(){
		var sid = $(this).attr("sid");
		console.log(sid);
		var val = $(this).val();
		var opt = $(this).find("option").eq(val);
		var pt = $(this).attr("selectid");
		console.log(pt);
		var tt = $(this).attr("trial_time");
		console.log(tt);
		$("#video_public_type"+sid).val(pt);
		$("#video_trial_time"+sid).val(tt);
	});
	$(".video_public_type_value").change(function(){
		var id = $(this).attr("id");
		var sid = $(this).attr("sid");
		console.log(sid);
		console.log(id);
		var val = $(this).val();
		var opt = $(this).find("option").eq(val);
		var pt = $(opt).attr("pubic_type");
		console.log(pt);
		var tt = $(opt).attr("trial_time");
		$("#video_public_type"+sid).val(pt);
		$("#video_trial_time"+sid).val(tt);
	});
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["flag"])&&SlightPHP\Tpl::$_tpl_vars["flag"]==1){; ?>
	layer.msg("<?php echo tpl_modifier_tr('保存成功','site.course'); ?>");
	var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>;
	var classId = <?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>;
	window.location.href="/user.org.editplan."+courseId+"."+classId;//页面跳转并传参
	<?php }; ?>
/*
	$(".course-list input[type='datetime']").each(function() {
		if($(this).attr("data-status") == 3) {
			$(this).attr("disabled",true);
		}
	});
*/
});
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
