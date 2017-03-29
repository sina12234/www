<html>
<head>
<title>云课网-上传头像</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/index.main.header"); ?>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/index.main.usernav/setting"); ?>
<section class="p20 container">
	<?php echo tpl_function_part("/index.main.menu"); ?>
	<div class="col-lg-16 col-md-16 col-sm-20 col-xs-20">
    <!--mob-->
    <p class="mob-nav hidden-lg hidden-md">
        <a href="/index.user.info" class="col-xs-8 col-sm-6">基础资料</a>
        <a href="/index.user.uploadPic/1" class="col-xs-8 col-sm-6">修改头像</a>
    </p>
<div class="right-content mainCon">
  		<div class="col-md-20 mb10">
  			<a class="fs16 cYellow" href="/index.user.uploadPic/1">自定义头像</a>
			<em class="cGray">&nbsp;&nbsp;|&nbsp;&nbsp;</em>
  			<a class="fs16" href="/index.user.uploadPic/2">系统头像</a>
  		</div>
		<form id="up-form" name="upload" method="post"  >
			<input name="big" type="hidden" value="">
			<input name="med" type="hidden" value="">
			<input name="small" type="hidden" value="">
 			<input type="hidden" size="4" id="x" name="x">
 			<input type="hidden" size="4" id="y" name="y">
 			<input type="hidden" size="4" id="x2" name="x2">
 			<input type="hidden" size="4" id="y2" name="y2">
 			<input type="hidden" size="4" id="w" name="w">
 			<input type="hidden" size="4" id="h" name="h">
 			<div class="col-md-12 col-lg-12 col-xs-20 col-sm-20 mt20">
    			<div class="upload-click-but">
					<a href="#"><button id="browse">本地上传</button></a>
    				<p class="cGray mt10">仅支持.JPG、.PNG图片格式文件，且文件图片小于8M</p>
					<label id="progress" for="" class="col-lg-20 col-md-20 col-sm-20 col-xs-20 control-label"></label>
				</div>
    			<div class="photoPicShow col-md-20 col-lg-20 mt20">
					<img id="img_o" src="" style="display:none;" /><?php /*<a class="fs16 showa"  href="javascript:;">选择你要上传的头像</a>*/?>
				</div>
  			</div>

  			<div class="col-md-8 col-lg-8 col-xs-8">
    			<p class="fs16 mt20 hidden-sm hidden-xs" style="height:40px;line-height:35px">预览：</p>
    			<p class="cYellow mt10 fs14 hidden-sm hidden-xs">您上传的头像会自动生成两种尺寸，注意清晰度</p>
    			<ul class="photoView hidden-sm hidden-xs" style="overflow:hidden;margin-top:25px">
      				<li class="c-fl" style="margin-right:10px">
	  					<div id="panel" style="width:128px;height:128px;overflow:hidden">
      						<img id="img_p" src="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->avatar->large; ?>"  style="width:128px;height:128px;"  alt=""/>
						</div>
      					<p class="mt10 lh30">128*128像素</p>
					</li>
      				<li class="c-fl">
	  					<div id="panel-fr" style="width:68px;height:68px;overflow:hidden">
	  						<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->avatar->medium; ?>" style="width:68px;height:68px;" id="img_p2"  alt=""/>
						</div>
						<p class="mt10 lh30">68*68像素</p>
					</li>
				</ul>
    			<div class="clear"></div>
    			<p class="mt10 hidden-sm lh30 clear hidden-xs">我使用过的头像：</p>
    			<ul class="mt10 photoView hidden-sm hidden-xs" style="overflow:hidden;">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_thumb"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["user_thumb"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["to"]){; ?>
      				<li <?php if(SlightPHP\Tpl::$_tpl_vars["k"]>0){; ?>class="c-fl ml10"<?php }else{; ?> class="c-fl"<?php }; ?>>
					<img class="his-pic" src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["to"]->thumb_small); ?>"  bigurl="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["to"]->thumb_big); ?>" medurl="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["to"]->thumb_med); ?>"big="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->thumb_big; ?>" med="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->thumb_med; ?>" small="<?php echo SlightPHP\Tpl::$_tpl_vars["to"]->thumb_small; ?>"  width="30" height="30"  alt=""/></li>
					<?php }; ?>
					<?php }; ?>
    			</ul>
    			<div class="clear"></div>
    			<div class="mt20 clear"><button id="up-save" class="blue-btn btn col-lg-10 col-md-10 col-sm-20 col-xs-20 col-xs-offset-20 col-lg-offset-0 col-sm-offset-15">保存</button></div>
  			</div>
  		</form>
	</div>
	</div>
</section>
	<?php echo tpl_function_part("/index.main.footer"); ?>

<script>
	$("#login_form").submit(function(){
		if($("#user_name").val()=='' || $("#user_name").val()=="手机号"){
			$(".x_error").html("请输入手机号");
			return false;
			}else if($("#x_pass").val()=='' || $("#x_pass").val()=="密码"){
			$(".x_error").html("帐号或密码错误");
			return false;
			}else{
			return true;
		};
		parent.layer.close(index);
	})

	 $('.his-pic').click(function(){
	 	var med_url = $(this).attr('medurl');
        var big_url = $(this).attr('bigurl');
        $('#img_p').attr('src', big_url);
	    $('#img_p2').attr('src', med_url);
	    $('input[name=big]').val($(this).attr('big'));
	    $('input[name=med]').val($(this).attr('med'));
        $('input[name=small]').val($(this).attr('small'));
		$('#w').val(0);
     });


	$(document).ready(function(){
		$("#up-save").click(function(){
			var big = $('input[name=big]').val();
			if(isNaN(parseInt($("#w").val())) && !big){
				layer.msg("请选择头像图片");
				return false;
			}
			$.post("/index.user.uploadPicAjax",$('#up-form').serialize(),function(r){
				if(r.error){
					layer.msg(r.error);
					return false;
				}else{
                    layer.msg('保存成功！');
					window.parent.location.reload();
				}
			},"json");
			return false;
		});
		var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
			browse_button: 'browse', 
			url: '/file.main.uploadPic',
			filters: {
				mime_types : [
					{ title : "Image files", extensions : "jpg,jpeg,gif,png" }
				],
				max_file_size:"8192kb"
			},
            multi_selection:false
		});

		uploader.init();

		uploader.bind('FilesAdded', function(up, files) {
			uploader.start();
		});

		uploader.bind('UploadProgress', function(up, file) {
			$("#progress").html("上传中："+ file.percent +"%");
		});
		uploader.bind('FileUploaded', function(up, file,info) {
			if(info.response){
				if(jcrop_api)jcrop_api.destroy();
				$('.showa').remove();
				$("#img_o").attr("src",info.response+"?"+Math.random());
				$("#img_p").attr("src",info.response+"?"+Math.random());
				$("#img_p2").attr("src",info.response+"?"+Math.random());
				$('#img_o').Jcrop({
					boxWidth:300,boxHeight:300,
					onChange: updatePreview,
					onSelect: updatePreview,
					aspectRatio: 1 //xsize / ysize
				},function(){
				// Use the API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];
					// Store the API in the jcrop_api variable
					jcrop_api = this;
					this.setSelect([0,0,300,300]);
				}).show();
			}
		});
		function showCoords(c){
		};
		function updatePreview(c){
			if (parseInt(c.w) > 0){
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);

				$pcnt = $('#panel');
				$pimg = $('#img_p');

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

				$pcntfr = $('#panel-fr');
				$pimgfr = $('#img_p2');
				xsizefr = $pcntfr.width();
				ysizefr = $pcntfr.height();
                console.log(xsizefr);
                console.log(ysizefr);
				var rxr = xsizefr / c.w;
				var ryr = ysizefr / c.h;
				$pimgfr.css({
				    width: Math.round(rxr * boundx) + 'px',
				    height: Math.round(ryr * boundy) + 'px',
				    marginLeft: '-' + Math.round(rxr * c.x) + 'px',
				    marginTop: '-' + Math.round(ryr * c.y) + 'px'
				});
			}
		};

		uploader.bind('Error', function(up, err) {
			if(err.code==-600){
				layer.msg("文件太大了,服务器君忙不过来了～请您上传8M以下的图片");
			}else{
				layer.msg("\nError #" + err.code + ": " + err.message);
			}
		});

		var jcrop_api,
		boundx,
		boundy;
	});
</script>
</body>
</html>
