<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>课程简介 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
	<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
	<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
	<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
	<?php echo tpl_function_part("/site.main.header"); ?>
</head>

<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!-- tpInfo -->
<?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<section class="pb40">
	<div class="container">
		<div class="row">
	<!-- bdy -->
		<section>
			<?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
			<div class="col-md-16 pr0">
				<div class="gn-base-ct clearfix">
					<section class="col-md-20 fs14 base-content clearfix">
						<h1 class="base-title mb20">课程简介</h1>
						<div class="c-fl col-lg-20 pd0 ml40 mb30">
							<div class="c-fl">
								<h1 class="mt10">适合范围：</h1>
							</div>
							<div class="c-fl col-lg-17 range-Inp">
                                <input type="text" class="col-lg-20" id="add-scope" value="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['scope']; ?>" />
							</div>
						</div>
						<div class="c-fl ml40 mr20">
							<div class="c-fl col-lg-20 pd0">
								<div class="install c-fl mb10">
									<span>
										下载官方模版【
										<a href="/assets_v2/img/templates/template-one.zip">模版一</a>
										<a href="/assets_v2/img/templates/template-two.zip">模版二</a>
										<a href="/assets_v2/img/templates/template-three.zip">模版三</a>
										】
									</span>
								</div>
								<div class="label-for col-lg-20 pl0" style="width:800px;">
	                                <textarea name="descript" id="descript" class="big-wid" style="width:789px;height:250px;"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['descript']; ?></textarea>
		                         </div>
							</div>
						</div>
						<div class="col-md-20 tac mt20 mb30">
							<button class="green-button" onclick="setCourseDesc()">保存</button>
						</div>
					</section>
				</div>
			</div>	
		</section>
	<!-- /bdy -->
		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<link href="<?php echo utility_cdn::css('/assets/umeditor/themes/default/css/umeditor.css'); ?>" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="<?php echo utility_cdn::js('/assets/umeditor/umeditor.config.js'); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo utility_cdn::js('/assets/umeditor/umeditor.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/umeditor/lang/zh-cn/zh-cn.js'); ?>"></script>
<script>
var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
var um = UM.getEditor('descript');
function setCourseDesc() {
	var descript = um.getContent();
		descript = descript.replace(/\"/g,"'");
	var scope = $('#add-scope').val();
		scope = scope.replace(/&nbsp;/ig, '');
	var params = {
		courseId : courseId,
		descript : descript,
		scope    : scope
	};
	$.ajax({
		url: '/user/courseAjax/setCourseDesc',
		type: 'post',
		dataType: 'json',
		data: JSON.stringify(params),
		success:function(r) {
			if(r.code == 0) {
				layer.msg('修改成功');
				locationReload();
			}else {
				layer.msg(r.message);
			}
		}
	});

}
</script>
