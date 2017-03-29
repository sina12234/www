<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan_info"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?><?php }; ?> - 上传视频 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/css/user.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/bootstrap-3.3.0/css/bootstrap.min.css'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="title" content="高能100 - 视频上传 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/player.css'); ?>">
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<body style="background:#f7f7f7">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40">
	<div class='container'>
		<div class="row">
			<div class="video-path col-lg-17 fs20"><?php echo tpl_modifier_tr('上传视频','site.course'); ?></div>
			<div class="video-main col-lg-17">
				<div class="video-classname  col-md-20 fs16">
                    <?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name,'site.index'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_name,'site.index'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["sectionDesc"],'site.index'); ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
					<?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
					<a class="video-add-more" id="video_add"><?php echo tpl_modifier_tr('添加更多视频','site.course'); ?></a>
					<?php }; ?>
					<?php }; ?>
				</div>
				<!--无视频-->
				<?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>

				<div class="col-sm-20" id='novideo'>
					<div class="video-add  col-md-14">
					  <div class="video-addbtn">
						 <span class="video-upload"></span>
						 <button class="fs16" id="video_add"><?php echo tpl_modifier_tr('上传视频','site.course'); ?></button>
					  </div>
					</div>
					<div class="video-tips col-md-6 fs14">
				<p class="fs16"><?php echo tpl_modifier_tr('温馨提示','site.course'); ?></p>
				<p class="cGray"><?php echo tpl_modifier_tr('1.文件大小不能超过10G','site.course'); ?></p>
				<p class="cGray"><?php echo tpl_modifier_tr('2.文件支持dat，asf，rm，ram，3gp，mov，m4v，dvix，dv，qt，divx，cpk，fli，flc，mod，mp4，wmv，flv，avi，mkv，vob，mpg，rmvb，mpeg，mov，mts格式','site.course'); ?></p>
				<p class="cGray"><?php echo tpl_modifier_tr('3.请勿上传色情或反动视频','site.course'); ?></p>
				<p class="cGray"><?php echo tpl_modifier_tr('4.一次最多上传3个视频','site.course'); ?></p>
			</div>
				</div>
				<!--上传列表-->
				<?php }; ?>
				<ul class="video-list">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["uploadFiles"] as SlightPHP\Tpl::$_tpl_vars["file"]){; ?>
					<li>
						<p class="video-pic col-lg-4"><img src='/assets_v2/img/video-img.png'></p>
						<div class="video-txt col-lg-11">
							<p class="video-tit"><?php echo SlightPHP\Tpl::$_tpl_vars["file"]->filename_org; ?> <span class="cRed"><?php echo tpl_modifier_tr('上传成功','site.course'); ?>!</span></p>
							<div class="video-progress">
								<div class="video-bar" style="width:100%">100%</div>
							</div>
							<p class="video-status cGray"><?php echo tpl_modifier_tr('文件大小','site.course'); ?>:<?php echo floor(SlightPHP\Tpl::$_tpl_vars["file"]->filesize/1024/1024*100)/100; ?>M</p>
						</div>
						<div class="video-edit col-lg-5">
							<?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
							<a href="javascript:;" onclick="delVideo(<?php echo SlightPHP\Tpl::$_tpl_vars["file"]->file_id; ?>);" class="video-edit-btn"><?php echo tpl_modifier_tr('删除','site.course'); ?></a>
							<?php /* tpl_modifier_SlightPHP\Tpl::$_tpl_vars["uploadTask"] function not exists! */; ?>

							<?php }; ?>
						</div>
					</li>
					<?php }; ?>
					<?php }; ?>
				</ul>

				<div class="trans-btn">
					<?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
							<?php if(0==SlightPHP\Tpl::$_tpl_vars["uploadFiles"][count(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])-1]->encoding_status){; ?>
								<button class="trans-code fs16" id="addtask"><?php echo tpl_modifier_tr('开始转码','site.course'); ?></button>
							<?php }else{; ?>
								<a href="/user.teacher.part/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>">
									<button class="trans-code fs16"><?php echo tpl_modifier_tr('去剪辑','site.course'); ?></button>
								</a>
							<?php }; ?>
						<?php }; ?>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==-1){; ?>
					<button class="trans-code fs16" id="addtask"><?php echo tpl_modifier_tr('失败,重新转码','site.course'); ?></button>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==0){; ?>
					<button class="trans-code fs16"><?php echo tpl_modifier_tr('等待中','site.course'); ?></button>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==1){; ?>
					<button class="trans-code fs16"><?php echo tpl_modifier_tr('转码中...','site.course'); ?></button>
					<?php }; ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript">
var plan_id = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>;
var uploader = new plupload.Uploader({
	//runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'video_add',
	url: '<?php echo SlightPHP\Tpl::$_tpl_vars["upload_video"]; ?>?token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&planid=<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>',
	flash_swf_url:"<?php echo utility_cdn::img('/assets/js/plupload/js/Moxie.swf'); ?>",
	chunk_size: '2000kb',
	max_retries: 100,
	filters: {
		mime_types : [
			{ title : "Video Files", extensions : "dat,asf,rm,ram,3gp,mov,m4v,dvix,dv,qt,divx,cpk,fli,flc,mod,mp4,wmv,flv,avi,mkv,vob,mpg,rmvb,mpeg,mov,mts" }
		],
		prevent_duplicates:false,
		max_file_size:"10G"
	},
	multi_selection:false,
	file_data_name:'video'
});

var QueueProgress = uploader.total;
var f = '';


uploader.bind('PostInit',function(){
    $(".video-list").on('click','.video-edit-btn',function(){
		var offFid = $(this).attr('fid');
		offVideo(offFid);
	});
    $(".video-list").on('click','.video-del-btn',function(){
        var delFid = $(this).attr('fid');
        delVideo(delFid);
    });
});

uploader.bind('FilesAdded',function(up, files){
	$("#novideo").css("display","none");
	$("#video_add").css("display","none");
    for(var i in files){
        myAppend(files[i].id,files[i].name);
    }
    uploader.start();
});

uploader.bind('UploadProgress',function(up, file){
	var speed = parseInt(QueueProgress.bytesPerSec/1024)+"KB/s";
	f = parseInt( (QueueProgress.loaded/1024)/1024 )+"M";
    var p = file.percent;
	var h ="<div id='bar_"+file.id+"' class='video-bar' style='width:"+p+"%'>"+p+"%</div>";
	var m = "<span id='msg_"+file.id+"'><?php echo tpl_modifier_tr('速度','site.course'); ?>："+speed+" | <?php echo tpl_modifier_tr('已上传','site.course'); ?>："+f+" </span>";
	$('#jd_'+file.id).html(h);
	$('#msg_'+file.id).html(m);
});

uploader.bind('FileUploaded',function(up, file, info){
    if(info.response){
		if(info.response==1){
			$("#fid_"+file.id).html(file.name+':<span class="cRed"><?php echo tpl_modifier_tr("上传成功","site.course"); ?>！</span>');
			window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
		}else if(info.response==-21){
			$("#msg_"+file.id).text("<?php echo tpl_modifier_tr('不是有效的视频文件','site.course'); ?>:"+info.response);
		}else if(info.response==-1){
			$("#msg_"+file.id).text("<?php echo tpl_modifier_tr('上传失败,错误码','site.course'); ?>:"+info.response);  
		}else{
			$("#msg_"+file.id).text("<?php echo tpl_modifier_tr('上传失败,错误码','site.course'); ?>:"+info.response);
		}
	}
});

uploader.bind('Error',function(up, err){
    if(err.code == -600){
        layer.msg("<?php echo tpl_modifier_tr('文件太大','site.course'); ?>");
		return false;
    }else if(err.code == -601){
        layer.msg("<?php echo tpl_modifier_tr('视频格式不支持，请选择别的视频文件','site.course'); ?>");
		return false;
    }else{
        //layer.msg("出错了，请联系管理员："+err.message);
    }
});

uploader.init();


//追加
function myAppend(fileId, fileName){
    var html='';
    html+="<li id="+fileId+">";
	html+="<p class='video-pic col-lg-4'><img src='<?php echo utility_cdn::img('/assets_v2/img/video-img.png'); ?>'></p>";
	html+="<div class='video-txt col-lg-11'>"; 
	html+="<p id='fid_"+fileId+"' class='video-tit'><?php echo tpl_modifier_tr("正在上传","site.course"); ?>:"+fileName+"</p>";
	html+="<div id='jd_"+fileId+"' class='video-progress'>";
	html+="</div>";
	html+="<p class='video-status cGray'>";
	html+="<span id='msg_"+fileId+"'></span>";
	html+="</p>";
	html+="</div>";
	html+="<div class='video-edit col-lg-5'>";
	html+="<a id='off_"+fileId+"' fid="+fileId+" class='video-edit-btn'><?php echo tpl_modifier_tr("取消上传","site.course"); ?></a>";
	html+="</div>";
	html+="</li>";
    $('.video-list').append(html);
}

//取消上传
function offVideo(fid)
{
	var off = '';
	for(var i in uploader.files){
		if(fid == uploader.files[i].id){
			off = i;
		}
	}
	uploader.splice(off, 1);
	$("#video_add").css("display","block");
	window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
}
//删除视频
function delVideo(fileId)
{    
    var status = <?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>;
    var planUid = 0;
    if(status == 2){
        planUid = <?php echo SlightPHP\Tpl::$_tpl_vars["planUid"]; ?>;
    }
    if(confirm("<?php echo tpl_modifier_tr('你确定要删除此视频吗?','site.course'); ?>")){
        $.post("/user.teacher.delfile",{ planId:plan_id, fileId:fileId,status:status,planUid:planUid  },function(r){
            if(r.code == 1){
                layer.msg('<?php echo tpl_modifier_tr("删除成功","site.course"); ?>',{ time:2000 },function(){
					window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
				});
            }else{
                layer.msg('<?php echo tpl_modifier_tr("删除失败","site.course"); ?>',{ time:2000 },function(){
					window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
				});
            }
        },"json");
    }
    return false;
}
//视频转码
$("#addtask").click(function(){
	var plan_id = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>;
	var status  = <?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>;
	$.post("/user.teacher.uploadtask/",{ plan_id:plan_id, type:"UPLOAD", status:status },function(r){
		if(r.code==1){
            layer.msg('<?php echo tpl_modifier_tr("玩命转码中,您可以离开","site.course"); ?>',{ time:2000 },function(){
				window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
            });
		}else{
			layer.msg(r.msg,{ time:2000 },function(){
				window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
            });
			return false;
		}
	},"json");
});
$(function(){
	videoListHidden();
})
function videoListHidden(){
	if($('.video-list li').length>=3){
		$("#video_add").remove();
	}
}
</script>
</html>

