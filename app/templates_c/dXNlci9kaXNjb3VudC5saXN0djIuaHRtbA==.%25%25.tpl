<!DOCTYPE html>
<html>
<head>
<title>优惠管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 优惠管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>" ></script>
<script type="text/javascript">
$(function(){
    <?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total>0){; ?>
        page("page_list", "/org.discount.listv2", <?php echo SlightPHP\Tpl::$_tpl_vars["limit"]; ?>, <?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>, Math.ceil(<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["limit"]; ?>));
	<?php }; ?>
    $("#submit").on("click", function(e){
        var discount_name = $("#discount_name").val().trim();
		var discount_value = $("#discount_value").val()
		var discount_type=$("input[name='discount_type']:checked").val();
		if(!discount_name){
			alert("规则名称 不能为空");
			return false;
		}
		if(discount_type==1){
			//减额
			ck_patener=<?php echo SlightPHP\Tpl::$_tpl_vars["ck_patener2"]; ?>;
			ck_patener_msg="优惠金额必须大于0，并且最多只能保留两位小数";
		}else{
			//减折扣
			ck_patener=<?php echo SlightPHP\Tpl::$_tpl_vars["ck_patener1"]; ?>;
			ck_patener_msg="减折扣必须大于0，并且最多只能保留一位小数";
		}
		if(!ck_patener.test(discount_value) || discount_value<=0){
			alert(ck_patener_msg);
			return false;
		}
		return true;
    });
    $("#discount").on("click", "[data-status]", function(e){
        if(0 == $(this).attr("data-status")){
			$.post("/org.discount.forbidv2/"+$(this).attr("data-id"));
            $(this).text("<?php echo tpl_modifier_tr('重新启动','org'); ?>");
			$(this).attr("data-status", -1);
        }else{
			$.post("/org.discount.recoverv2/"+$(this).attr("data-id"));
            $(this).text("<?php echo tpl_modifier_tr('暂停使用','org'); ?>");
			$(this).attr("data-status", 0);
        }
	});
});
</script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
	<div class="row">
			<?php echo tpl_function_part("/org.main.menu.discount"); ?>
			<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total==0){; ?>
			<div class="right-main col-md-16" id="discount_no">
				<div class="content">
					<div class='col-md-20 cen'>
						<img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
					</div>
					<div class='col-md-20 cen'>
						<p class='fs16'>没有发现任何优惠，快猛戳下面的按钮 给学生发福利</p>
					</div>
					<div class='col-md-20 cen'>
						<button onclick="$('#discount_no').hide();$('#discount_new').show();">创建新优惠</button>
					</div>
				</div>
			</div>
			<?php }; ?>
			<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total==0){; ?>
			<div class="right-main col-sm-16" style="display:none;" id="discount_new">
			<?php }else{; ?>
			<div class="right-main col-sm-16 col-md-16">
			<?php }; ?>
				<div class="content">
					  <p class='pdl5 cDarkgray fs16 fob' style="padding-bottom:20px;"><?php echo tpl_modifier_tr('优惠管理','org'); ?></p>
					<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total>0){; ?>
					<div class='col-sm-12 col-xs-12 col-md-20' style="margin-bottom:20px;padding:0" id="discount">
						<div class='color-plan hidden-xs hidden-md hidden-lg hidden-sm'>
							<div class='nav-color col-sm-12 col-xs-12'>
								<li></li>
								<p>优惠管理</p>
							</div>
						</div>
						<div class='coupon-nav col-sm-12 col-md-20'>
							<ul>
								<li class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('优惠名称','org'); ?></li>
								<li class="hidden-sm hidden-lg hidden-md"><?php echo tpl_modifier_tr('类型','org'); ?></li>
								<li class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('优惠内容','org'); ?></li>
								<!--
								<li class="hidden-sm visible-lg col-lg-2"><?php echo tpl_modifier_tr('单张券限制','org'); ?></li>
								<li class="hidden-sm visible-lg col-lg-2"><?php echo tpl_modifier_tr('单个用户限制','org'); ?></li>
								-->
								<li class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('有效时长','org'); ?></li>
								<li class="hidden-sm col-md-4"><?php echo tpl_modifier_tr('范围','org'); ?></li>
								<li class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('操作','org'); ?></li>
							</ul>
						</div>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["discounts"]->data as SlightPHP\Tpl::$_tpl_vars["discount"]){; ?>
						<div class='coupon-nav-1'>
							<ul class="col-sm-12 col-md-20" style="padding:0">
								<li class="col-sm-3 col-md-4"><a href="/org.discount.listcodev2/<?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->discount_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->name; ?></a></li>
								<?php if(1==SlightPHP\Tpl::$_tpl_vars["discount"]->discount_type){; ?>
								<li class="hidden-sm hidden-lg col-md-3">满额减</li>
								<li class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('满','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->min_fee/100; ?><?php echo tpl_modifier_tr('减','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->discount_value/100; ?></li>
								<?php }else{; ?>
								<li class="hidden-sm col-md-4"><?php echo tpl_modifier_tr('满','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->min_fee/100; ?><?php echo tpl_modifier_tr('打','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->discount_value/10; ?><?php echo tpl_modifier_tr('折','org'); ?></li>
								<?php }; ?>
								<!--
								<li class="hidden-sm visible-lg col-lg-2"><?php if(0==SlightPHP\Tpl::$_tpl_vars["discount"]->total_num){; ?><?php echo tpl_modifier_tr('不限制','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('使用','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->total_num; ?><?php echo tpl_modifier_tr('次','org'); ?><?php }; ?></li>
								<li class="hidden-sm visible-lg col-lg-2"><?php if(0==SlightPHP\Tpl::$_tpl_vars["discount"]->user_limit){; ?><?php echo tpl_modifier_tr('不限制','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('使用','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->user_limit; ?><?php echo tpl_modifier_tr('次','org'); ?><?php }; ?></li>
								-->
								<li class="col-sm-3 col-md-4"><?php if(0==SlightPHP\Tpl::$_tpl_vars["discount"]->duration){; ?><?php echo tpl_modifier_tr('永久','org'); ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->duration/86400; ?><?php echo tpl_modifier_tr('天','org'); ?><?php }; ?></li>
								<li class="hidden-sm col-md-4"><?php if(0==SlightPHP\Tpl::$_tpl_vars["discount"]->course_id){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["discount"]->course_name,'org'); ?><?php }else{; ?><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->course_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->course_name; ?></a><?php }; ?></li>
								<li class="col-sm-3 col-md-4" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->discount_id; ?>" data-status="<?php echo SlightPHP\Tpl::$_tpl_vars["discount"]->status; ?>"><?php if(0==SlightPHP\Tpl::$_tpl_vars["discount"]->status){; ?><?php echo tpl_modifier_tr('暂停使用','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('重新启动','org'); ?><?php }; ?></li>
							</ul>
						</div>
						<?php }; ?>
						<div class="page-list" id="page_list">
						</div>
                        <div class="col-md-4 col-sm-1"></div>
						<div class='coupon-but col-sm-10 col-md-10'>
							<button id="faq">+<?php echo tpl_modifier_tr('创建新优惠','org'); ?></button>
						</div>
					</div>
					<div class='clear'></div>
					<?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total==0){; ?>
					<form method="POST" action="/org.discount.createv2">
					<?php }else{; ?>
					<form method="POST" id="discount_create" class="col-md-20" style="display:none;" action="/org.discount.createv2">
					<?php }; ?>
					<div class="form fs16">
						<div class="form-group coupon-form col-md-20 mb20">
							<label class="col-md-4 ter">
								<span class="red">*</span>
								<?php echo tpl_modifier_tr('规则名称','org'); ?>:
							</label>
							<input type="text" id="discount_name" name="discount_name" class="form-cto bor1px col-md-10" id="exampleInputName2" placeholder='<?php echo tpl_modifier_tr("请务必填写真实名称","org"); ?>'>
						</div>
						<div class='coupon-form col-md-20 mb20'>
							<label class='col-md-4 fs16 ter'>
								<span class="red">*</span>
								<?php echo tpl_modifier_tr('优惠类型','org'); ?>:
							</label>
							<div class='col-md-10 fs16 lh22 pd0'>
								<label>
									<input type="radio" name="discount_type" value="1" checked onclick="$('#id_1').show();$('#id_2').hide();$('#id_3').hide();"/><?php echo tpl_modifier_tr('满额减','org'); ?>
								</label>
								<label>
									<input type="radio" name="discount_type" value="2" onclick="$('#id_1').hide();$('#id_2').show();$('#id_3').show();"/><?php echo tpl_modifier_tr('打折','org'); ?>
								</label>
								<div class='cou-big col-xs-12 col-md-20'>
									<label class="c-fl col-md-20 pd0">
										<div class='cou-l'>
											<div class='cou-l-1 c-fl'><p><?php echo tpl_modifier_tr('满','org'); ?></p></div>
											<div class='cou-l-1 c-fl'>
												<input type='text' name="min_fee" value='1999'>
											</div>
										</div>
										<div class='cou-l'>
											<div class='cou-l-1 c-fl'>
												<p id="id_1"><?php echo tpl_modifier_tr('减','org'); ?></p>
											</div>
											<div class='cou-l-2 c-fl'>
												<p id="id_2" style="display:none;"><?php echo tpl_modifier_tr('打','org'); ?></p>
											</div>
											<div class='cou-l-1 c-fl'>
												<input id="discount_value" type='text' name="discount_value" value='9'>
											</div>
											<div class='cou-l-2 c-fl'>
												<p id="id_3" style="display:none;"><?php echo tpl_modifier_tr('折','org'); ?></p>
											</div>
										</div>
									</label>
								</div>
							</div>
						</div>
						<div class='clear'></div>
						<div class="form-group coupon-form col-md-20 mb20">
							<label class="col-md-20">
								<span class="col-md-4 ter"><?php echo tpl_modifier_tr('使用限制','org'); ?>:</span>
								<span class="left tec mr10" style="color:#c3c3c3;padding:0;"><?php echo tpl_modifier_tr('单张券','org'); ?></span>
								<select class="col-md-5" name="total_num">
									<option value="0"><?php echo tpl_modifier_tr('重复使用','org'); ?></option>
									<option value="30"><?php echo tpl_modifier_tr('最多30次','org'); ?></option>
									<option value="10"><?php echo tpl_modifier_tr('最多10次','org'); ?></option>
									<option value="5"><?php echo tpl_modifier_tr('最多5次','org'); ?></option>
									<option value="1"><?php echo tpl_modifier_tr('最多1次','org'); ?></option>
								</select>
								<span class="left tec mr10" style="color:#c3c3c3;"><?php echo tpl_modifier_tr('单用户','org'); ?></span>
								<select class="col-md-5" name="user_limit">
									<option value="1"><?php echo tpl_modifier_tr('限用1次','org'); ?></option>
									<option value="5"><?php echo tpl_modifier_tr('限用5次','org'); ?></option>
									<option value="10"><?php echo tpl_modifier_tr('限用10次','org'); ?></option>
									<option value="0"><?php echo tpl_modifier_tr('不限使用','org'); ?></option>
								</select>
							</label>
						</div>
						<div class='clear'></div>
						<div class="form-group coupon-form col-md-20 mb20">
							<label class="col-md-20">
								<span class="col-md-4 ter"><?php echo tpl_modifier_tr('有效时长','org'); ?>:</span>
								<select class="col-md-5" name="duration">
									<option value="0"><?php echo tpl_modifier_tr('永久','org'); ?></option>
									<option value="365"><?php echo tpl_modifier_tr('一年','org'); ?></option>
									<option value="183"><?php echo tpl_modifier_tr('半年','org'); ?></option>
									<option value="90"><?php echo tpl_modifier_tr('90天','org'); ?></option>
									<option value="30"><?php echo tpl_modifier_tr('30天','org'); ?></option>
									<option value="7"><?php echo tpl_modifier_tr('7天','org'); ?></option>
								</select>
							</label>
						</div>
						<div class='clear'></div>
						<div class="form-group coupon-form col-md-20 mb20">
							<label class="col-md-20 mb20">
								<span class="col-md-4 ter"><?php echo tpl_modifier_tr('范围','org'); ?>:</span>
								<select class="col-md-5" name="course_id">
									<option value="0"><?php echo tpl_modifier_tr('全部课程','org'); ?></option>
									<?php if(SlightPHP\Tpl::$_tpl_vars["courses"]!=false){; ?>
										<?php foreach(SlightPHP\Tpl::$_tpl_vars["courses"] as SlightPHP\Tpl::$_tpl_vars["course"]){; ?>
										<option value="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->title; ?> <?php echo tpl_modifier_tr('价格','org'); ?>:<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->price; ?><?php echo tpl_modifier_tr('元','org'); ?></option>
										<?php }; ?>
									<?php }; ?>
								</select>
							</label>
							<div class='clear'></div>
							<div class='cou-group col-md-20'>
								<div class="col-md-3"></div>
								<div class="col-md-17">
									<input type="checkbox" name="create_code" checked>
									<span class="mt10"><?php echo tpl_modifier_tr('立即创建50张优惠券','org'); ?></span>
								</div>
							</div>
							<div class='cou-but'>
								<button id="submit" class="col-md-offset-7"><?php echo tpl_modifier_tr('创建','org'); ?></button>
							</div>
						</div>
					</div>
					</form>
                    <div class="col-md-16 tac mt10" style="display:none;padding-top:60px;">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                        <p class="fs14" style="font-weight: bold; color:#666;"><?php echo tpl_modifier_tr('您还没有创建优惠哦','org'); ?></p>
                    </div>
                </div>
			</div>
		</div>
		</div>
        <div class='clear'></div>
    </section>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>
$("#faq").click(function(){
	if ($("#discount_create").is(":hidden")) {
		$("#discount_create").slideDown(500);
	}else{
		$("#discount_create").slideUp(500);
	}
})
</script>
</html>
