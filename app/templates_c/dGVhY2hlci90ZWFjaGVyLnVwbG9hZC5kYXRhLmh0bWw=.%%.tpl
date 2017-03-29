<!DOCTYPE html>
<html>
<head>
    <title>高能100 - 专业的K12在线教育平台</title>
    <meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
    <meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
    <meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
    <?php echo tpl_function_part("/site.main.header"); ?>
	<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
	<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<style>
	#uploadthis{	width: 130px;
		height: 40px;
		background: #198fee;
		color: #fff;
		border: none;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		-o-border-radius: 5px;
		-ms-border-radius: 5px;
		-khtml-border-radius: 5px;
		border-radius: 5px;
	}
</style>
<body>
	<div class="new-upload-data" id="showthis">
	<!--打开弹层-->
		<div id="up1">
			<p class="up-most"><?php echo tpl_modifier_tr('最多上传10个文件','site.teacher'); ?></p>
			<p class="up-format cGray"><?php echo tpl_modifier_tr('支持格式','site.teacher'); ?>：txt、pdf、jpg、doc、docx、pptx、xls、xlsx</p>
			<p class="new-upload-btn"><button id="browse"><?php echo tpl_modifier_tr('选择本地文件','site.teacher'); ?></button></p>
		</div>
	<!--打开弹层-->
	<!--选择文件-->
		<div id="up2" style="display:none;">
			<form action="./teacher.manage.batchupload.<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>" enctype="multipart/form-data" method="post"  name="uploadfile" id="form">
				<p attr="<?php echo SlightPHP\Tpl::$_tpl_vars["errorcode"]; ?>" id="code" style="text-align:center;line-height:40px;"><?php /*$error*/?></p>
				<ul class="new-data-list" id="data_list"></ul>
				<p class="fs12"><?php echo tpl_modifier_tr('注：单次最多上传10个','site.teacher'); ?>,<?php echo tpl_modifier_tr('支持格式','site.teacher'); ?>：txt、pdf、jpg、doc、docx、pptx、xls、xlsx</p>
				<p class="new-upload-btn" id="butthis">
					<button id="uploadsuc" style="display:none"><?php echo tpl_modifier_tr('上传中','site.teacher'); ?></button>
				</p>
			</form>
			<p class="new-upload-btn">
				<button id="uploadthis"><?php echo tpl_modifier_tr('确定上传','site.teacher'); ?></button>
		    </p>
		</div>
	</div>
</body>
<script>
	$("#showthis").hide();
	$(document).ready(function(){
		var	swappic = {
			txt:"<?php echo utility_cdn::img('/assets_v2/img/lesson-txt.png'); ?>",
			pdf:"<?php echo utility_cdn::img('/assets_v2/img/lesson-pdf.png'); ?>",
			doc:"<?php echo utility_cdn::img('/assets_v2/img/lesson-doc.png'); ?>",
			docx:"<?php echo utility_cdn::img('/assets_v2/img/lesson-doc.png'); ?>",
			ppt:"<?php echo utility_cdn::img('/assets_v2/img/lesson-ppt.png'); ?>",
			pptx:"<?php echo utility_cdn::img('/assets_v2/img/lesson-ppt.png'); ?>",
			jpg:"<?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?>",
			xls:"<?php echo utility_cdn::img('/assets_v2/img/lesson-xls.png'); ?>",
			xlsx:"<?php echo utility_cdn::img('/assets_v2/img/lesson-xls.png'); ?>",
			//	"8"=>"<?php echo utility_cdn::img('/assets_v2/img/lesson-png.png'); ?>",
			//	"9"=>"<?php echo utility_cdn::img('/assets_v2/img/lesson-gif.png'); ?>",
		};
		var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		var code = $("#code").attr("attr");
		if(code == 200){
			$("#showthis").hide();
			parent.location.reload();
			parent.layer.close(index);
			}else{
			$("#showthis").show();
		}
		if(code==200  ||code==100){
		}else{
			layer.msg("<?php echo SlightPHP\Tpl::$_tpl_vars["error"]; ?>");
		}
		var form = $("#form");
		var clName = $("#title");
		form.submit(function(){
			var flagthis = true;
			$(".judgeval").each(function(){
				var thisval = $(this).val();
				if(0==thisval.length){
					flagthis = false;
					$(this).css({ "borderColor":"red"});
				}else if(strlen(thisval)>=60){
					layer.msg("课件名称不能超过60个字");
					$(this).css({ "borderColor":"#ccc"});
					flagthis = false;
				}
			});
			if(!flagthis){
				return false;
			}
		});

		var uploader = new plupload.Uploader({
			browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
			url: '/file.main.batchuploadattach.<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>',
			filters: {
				mime_types : [
				{ title : "Attach Files", extensions : "jpg,txt,pdf,doc,docx,ppt,pptx,xls,xlsx" }
				],
				prevent_duplicates:false,
				max_file_size:"10mb"
			}
			,multi_selection:true
			,file_data_name:'upfile'
		});

		uploader.bind('FilesAdded', function(up, files) {
			for(var i in files){
				var att = files[i].name.split(".");
				var attrtitle 
				attrtitle = cutstr(att[0],60);
				var picurl;
				var fromstr;
				formstr = att.pop();
				var lower = formstr.toLocaleLowerCase();
				if(swappic.hasOwnProperty(lower)){
					for(var x in swappic){
						if(lower==x){
							picurl = swappic[x];
						}
					}
				}else{
					picurl = "<?php echo utility_cdn::img('/assets_v2/img/lesson-doc.png'); ?>";
				}
				$("#title").val(attrtitle);
				appendDom(files[i].id,attrtitle,files[i].size,picurl);
			}
			$('#uploadthis').html("<?php echo tpl_modifier_tr('确定上传','site.teacher'); ?>");
			$('#uploadthis').show();
			$('#uploadsuc').hide();
			$("#up1").hide();
			$("#up2").show();
		});

		uploader.bind('UploadProgress', function(up, file) {
				var p = file.percent;
				$('#percent_'+file.id).html(file.percent+"%");
				$('#uploadthis').html("上传中");
		});
		$("#uploadthis").click(function(){
			uploader.start();
		})
		uploader.bind('FileUploaded', function(up, file,info) {
			var r = jQuery.parseJSON(info.response);
			if(r.errorcode=="200"){
				$("#type"+file.id).val(r.type);
				$("#attach"+file.id).val(r.attach);
				$("#thumb"+file.id).val(r.thumb);
				$('#uploadthis').hide();
				$('#uploadsuc').show();
			}else{
				$('#uploadthis').html("上传失败");

			}
		});
		uploader.bind('UploadComplete', function(up, files) {
			$('#uploadsuc').html("完成");
		});
		uploader.bind('Error', function(up, err) {
			if(err.code==-600){
				layer.msg("文件太大了");
			}else if(err.code==-601){
				layer.msg("文件类型不对");
			}else{
				layer.msg("\nError #" + err.code + ": " + err.message);
			}
		});
		uploader.init();
	});


	function appendDom(fileId,fileName,size,picurl){
		var si;
		f = parseFloat( (size/1024)/1024 ).toFixed(1);
		si = f+"M";
		if(0.5>=f){
			f = parseInt( (size/1024) );
			si = f+"KB";
		}
		var html='';
		html+="<li>";
		html+='<span class="c-fl"><img src="'+picurl+'"></span>';
		html+='<span class="c-fl ml10">'+si+'</span>';
		html+='<label class="delthis c-fr" style="cursor:pointer;">'+"<?php echo tpl_modifier_tr('删除','site.user'); ?>"+'</label>';
		html+='<div class="ml10 named">'+"<?php echo tpl_modifier_tr('命名：','site.teacher'); ?>"+'<input type="text" name="title[]" class="judgeval" id="title'+fileId+'" value="'+fileName+'">';
		html+='<span class="cRed" id="percent_'+fileId+'">0%</span>';
		html+="</div>";
		html+='<label><input type="text" name="attach[]" id="attach'+fileId+'" hidden></label>';
		html+='<label><input type="text" name="thumb[]" id="thumb'+fileId+'" hidden></label>';
		html+='<label><input type="text" name="type[]" id="type'+fileId+'" hidden></label>';
		html+='</li>';
		$("#data_list").append(html);
	}
    $("#data_list").on('click','.delthis',function(){
		$(this).parent().detach();
		var lilength = $("#data_list").find("li").length;
		if(0==lilength){
			$("#up2").hide();
			$("#up1").show();
		}
	});

	function strlen(str){
		var len = 0;
		for (var i=0; i<str.length; i++) { 
			var c = str.charCodeAt(i); 
			//单字节加1 
			if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) { 
				len++; 
			} 
			else { 
				len+=2; 
			} 
		} 
		return len;
	}


	function cutstr(str, len) {
		var str_length = 0;
		var str_len = 0;
		str_cut = new String();
		str_len = str.length;
		for (var i = 0; i < str_len; i++) {
			a = str.charAt(i);
			str_length++;
			if (escape(a).length > 4) {
				str_length++;
			}
			str_cut = str_cut.concat(a);
			if (str_length >= len) {
				str_cut = str_cut.concat("...");
				return str_cut;
			}
		}
		if (str_length < len) {
			return str;
		}
	}


</script>
</html>
