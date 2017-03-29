<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>课程封面 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
	<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
	<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
	<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
	<?php echo tpl_function_part("/site.main.header"); ?>
	<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
	<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
	<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<style type="text/css">
#img_p_label{ cursor: pointer;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd40">
	<div class="container">
		<div class="row bgf gn-base-ct">
			<h1 class="base-title mb30">
				<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["sourceUrl"]; ?>" class="cGray">返回课程列表</a>
				> <a href="/org.course.type">课程类型</a>
				> <a href="/org.course.add.<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>">基本信息</a>
				> 课程封面
			</h1>
			<section class="c-content-info fs14 clearfix">
				<ul class="fs14 course-fans-text mb20">
					<li class="mb20 clearfix">
						<div class="c-fl">
							<div class="set-course-pic mb10 mr20" id="set-course-pic">
								<img id="img_p_label" width="320" height="180" src="/assets_v2/img/set-course-bg-img.jpg" alt="" />
								<img id="desc_img" src=""  width="320" height="180" alt="" />
							</div>
							<input type="hidden" size="4" id="x" name="x">
							<input type="hidden" size="4" id="y" name="y">
							<input type="hidden" size="4" id="x2" name="x2">
							<input type="hidden" size="4" id="y2" name="y2">
							<input type="hidden" size="4" id="w" name="w">
							<input type="hidden" size="4" id="h" name="h">
						</div>
						<div class="c-fl cGray tal fs14">
							<p class="mt40 mt-xs-5">
								图片大于480*270
							</p>
							<p>
								支持JPG，PNG，BMP格式（6M以下）
							</p>
							<p>
								<a href="/assets_v2/img/templates/template-word.zip" title="" class="cBlue">下载封面模板</a>
							</p>
						</div>
					</li>
					<li class="mb20 clearfix">
						<span class="c-fl mt5">适合范围：</span>
						<input maxlength="100" class="suitable-range-text" id="range-scope" placeholder="请输入适合人群和阶段（100字内）" />
					</li>
					<li class="mb20">课程简介：</li>
					<li class="mb20">
						<textarea name="descript" class="descript-text" id="descript" style="width:100%;height:270px;" placeholder="请输入结构简介"></textarea>
					</li>
					<li class="tac mb20">
						<a href="/org.course.add.<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" class="gray-button">上一步</a>
						<button class="green-button" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" onclick="addSetCourseDesc(this)">下一步</button>
					</li>
				</ul>
			</section>
		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
<!-- 封面 -->
<section id="upload-img-content" style="display:none;">
    <div class="upload-img tac mt20 mb20">
        <img src="/assets_v2/img/set-course-img.jpg" alt="" />
    </div>
    <div id="uploadIm_o">
        <img src="" id="img_o" alt="" />
    </div>
    <p class="tac">
        <button class="gray-button" id="uploadImg">上传图片</button>
        <button class="green-button" id="saveImg" style="display:none;margin-left:60px;">保存</button>
    </p>
    <input id="progress" type="hidden" />
</section>
<!-- /封面 -->
</body>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<link href="<?php echo utility_cdn::css('/assets/umeditor/themes/default/css/umeditor.css'); ?>" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="<?php echo utility_cdn::js('/assets/umeditor/umeditor.config.js'); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo utility_cdn::js('/assets/umeditor/umeditor.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/umeditor/lang/zh-cn/zh-cn.js'); ?>"></script>
<script type="text/javascript">
var data = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]; ?>;
var um = UM.getEditor('descript');
$(function(){
	um.setContent(data.descript);
	//$('#descript').val(data.descript);
	$('#range-scope').val(data.scope);
	if(data.thumb_med == ''){
		$('#img_p_label').show();
		$('#desc_img').hide();
		$('#img_o_label').show();
		$('#img_o').hide();
	}else{
		$('#img_p_label').hide();
		$('#desc_img').show();
		$('#img_o_label').hide();
		$('#img_o').show();
		$('#uploadIm_o').show();
		$('.upload-img').hide();
		$('#desc_img').attr('src', data.thumb_med);
		$('#img_o').attr('src', data.thumb_med);
	};
	$('#set-course-pic').click(function() {
		layer.open({
			type: 1,
			title:['添加封面'],
			area: ['610px', '450px'],
			shadeClose: true,
			content: $('#upload-img-content')
		});

	})

	var jcrop_api, boundx, boundy;
	var uploader = new plupload.Uploader({
			runtimes: 'html5,flash,silverlight,html4',
			browse_button: 'uploadImg',
			url: '/file.main.uploadCoursePicV2.'+data.userId,
			filters: {
				mime_types : [
				{ title : "Image files", extensions : "jpg,bpm,png" }
				],
				max_file_size:"6000kb"
			}
			,multi_selection:false
		});

		uploader.init();

		uploader.bind('FilesAdded', function(up, files) {
			uploader.start();
		});

		uploader.bind('Error', function(up, error) {
			if(error.code == -600){
				layer.msg('文件太大了，请选择小于6M的文件');
			}else if(error.code == -601){
				layer.msg('图片格式不正确')
			}else{
				layer.msg('出错了，请重新选择文件或者刷新页面再重新选择文件');
			}
		});

		uploader.bind('UploadProgress', function(up, file) {
			$("#progress").css({ "height":"100%" });
			$("#progress").attr('filePercent', file.percent);
			$('#uploadImg').text('上传'+file.percent+'%');
			if(file.percent == 100) {
				$('.upload-img').hide();
				$('#img_p_label').hide();
				$('#uploadIm_o').show();
				$('#desc_img').show();
				$('#saveImg').show();
				$('#uploadImg').text('重新上传');
			}
		});

		uploader.bind('FileUploaded', function(up, file, info) {
			if(info.response){
				var r = jQuery.parseJSON(info.response);
					if(!r) {
						layer.msg('上传失败');
						return false;
					}else if(r.error){
						layer.msg(r.error);
						return false;
					};
					$("#progress").css({ "height":"0" });
					if(jcrop_api)jcrop_api.destroy();
					$("#img_o").hide().attr("src",r.file+"?"+Math.random());
					$("#img_o").attr("src",r.file+"?"+Math.random());
					$("#desc_img").attr("src",r.file+"?"+Math.random());

					$('#img_o').Jcrop({
						boxWidth:480,
						boxHeight:270,
						minSize:[480,270],
						onChange: updatePreview,
						onSelect: updatePreview,
						minSize:[480,270],
						aspectRatio: 16/9
					},function(){
						var bounds = this.getBounds();
						boundx = bounds[0];
						boundy = bounds[1];
						jcrop_api = this;
						this.setSelect([100, 20, 320, 180]);
					}).show();
			}
		});

		function updatePreview(c){
			if (parseInt(c.w) > 0){
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);

				$pcnt = $('#set-course-pic');
				$pimg = $('#desc_img');

				xsize = $pcnt.width();
				ysize = $pcnt.height();
				var rx = xsize / c.w;
				var ry = ysize / c.h;

				$pimg.css({
					width: Math.round(rx * boundx) + 'px',
					height: Math.round(ry * boundy) + 'px',
					marginLeft: '-' + Math.round(rx * c.x) + 'px',
					marginTop: '-' + Math.round(ry * c.y) + 'px'
				});
			}
		};

		$('#saveImg').click(function() {
			layer.closeAll();
		});
});
</script>
</html>
