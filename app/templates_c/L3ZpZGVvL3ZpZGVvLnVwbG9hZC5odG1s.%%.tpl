<!DOCTYPE html>
<html>
<head>
<title>视频上传-<?php echo tpl_function_part('/site.main.orgname'); ?>-云课-专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<body>
<?php echo tpl_function_part("/site.main.usernav.home"); ?>
<section class="pd30">
    <div class="container">
        <div class="col-md-20 main-center">
            <div class="org-video-path mb30 col-md-20">
                <p class="ov-path-tit fb fs14">视频管理</p>
                <p class="ov-path-section"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name,'site.index'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_name,'site.index'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["sectionDesc"],'site.index'); ?></p>
            </div>
            <!-- //未上传 -->
            <?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
            <div class="org-video-list col-md-13" style="display:none"  id="ov-list">
                <a href="javascript:;" class="theme-link mt10" id="video_add" style="display:none">+继续添加</a>
            </div>
            <div class="org-video-uploadarea col-md-13">
                <div class="ov-upload-btn">
                    <a href="javascript:void(0);" class="btn mr10 fs14" id="unvideo_add"><i class="upload-btn-icon mr5"></i>上传视频</a>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["video"])){; ?>
                    <a href="/video.point.videoPart/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>" class="gray-btn">返回编辑</a>
                    <p class="mt5 fs14">成功上传新视频转码成功后，会覆盖原有视频，原视频的剪辑和注释都将随之删除。</p>
                    <?php }; ?>
                </div>
            </div>
            <?php }; ?>
            <!-- //视频列表 -->
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
            <div class="org-video-list col-md-13"  id="ov-list">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["uploadFiles"] as SlightPHP\Tpl::$_tpl_vars["file"]){; ?>
                <div class="ov-list-item" status="1">
                    <p class="ov-item-name fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["file"]->filename_org; ?></p>
                    <div class="ov-item-progress col-md-15 pd0">
                        <i class="ov-item-bar" style="width:100%"></i>
                    </div>
                    <div class="ov-item-tips col-md-5 cGray">
                        <?php echo floor(SlightPHP\Tpl::$_tpl_vars["file"]->filesize/1024/1024*100)/100; ?>M,已完成
                        <?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
                        <i class="del-icon c-fr" fid="<?php echo SlightPHP\Tpl::$_tpl_vars["file"]->file_id; ?>"></i>
                        <?php /* tpl_modifier_SlightPHP\Tpl::$_tpl_vars["uploadTask"] function not exists! */; ?>

                        <?php }; ?>
                    </div>
                </div>
                <?php }; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
                <?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
                <a href="javascript:;" class="theme-link mt10" id="video_add">+继续添加</a>
                <?php }; ?>
                <?php }; ?>
                <?php if(empty(SlightPHP\Tpl::$_tpl_vars["uploadTask"])){; ?>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])){; ?>
                <?php if(0==SlightPHP\Tpl::$_tpl_vars["uploadFiles"][count(SlightPHP\Tpl::$_tpl_vars["uploadFiles"])-1]->encoding_status){; ?>
                <p class="tac"><a href="javascript:;" class="btn mr10 mt20" id="addtask"><?php echo tpl_modifier_tr('开始转码','site.course'); ?></a></p>
                <?php }else{; ?>
                <p class="tac"><a href="/video.point.videoPart/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>" id="jianji" class="btn mr10 mt20">编辑视频</a></p>
                <?php }; ?>
                <?php }; ?>
                <?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==-1){; ?>
                <p class="tac" ><a href="javascript:;" class="btn mr10 mt20" id="addtask"><?php echo tpl_modifier_tr('失败,重新转码','site.course'); ?></a></p>
                <?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==0){; ?>
                <p class="tac"><a href="javascript:;" class="btn mr10 mt20"><?php echo tpl_modifier_tr('等待中','site.course'); ?></a></p>
                <?php }elseif( SlightPHP\Tpl::$_tpl_vars["uploadTask"]->status==1){; ?>
                <p class="tac"><a href="javascript:;" class="btn mr10 mt20"><?php echo tpl_modifier_tr('转码中...','site.course'); ?></a></p>
                <?php }; ?>
                <p class="mt5 fs14">成功上传新视频转码成功后，会覆盖原有视频，原视频的剪辑和注释都将随之删除。</p>
            </div>
            <?php }; ?>
            <div class="org-video-uploadinfo col-md-7">
                <p class="fb">温馨提示</p>
                <p>1.文件大小不能超过5G。</p>
                <p>2.文件支持dat，asf，rm，ram，3gp，mov，m4v，dvix，dv，qt，divx，cpk，fli，flc，mod，mp4，wmv，flv，avi，mkv，vob，mpg，rmvb，mpeg，mov，mts格式。</p>
                <p>3.最多上传3个视频文件。</p>
                <p>4.上传成功后，服务器将自动对视频进行转码，转码需要一定的时间，在这个过程中视频将无法播放。</p>
                <p>5.上传多个文件，转码时将按排列顺序合并为一个视频。</p>
                <p>6.请勿上传色情或反动视频。</p>
            </div>

            </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript">
    var oList=$('#ov-list');
    var plan_id = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>;
    var uploader = new plupload.Uploader({
        //runtimes : 'html5,flash,silverlight,html4',
        browse_button :['video_add','unvideo_add'],
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
        $(".org-video-list").on('click','.video-pause-btn',function(){
            var offFid = $(this).attr('fid');
            offVideo(offFid);
        });
        $(".org-video-list").on('click','.del-icon',function(){
            var delFid = $(this).attr('fid');
            delVideo(delFid);
        });
    });

    uploader.bind('FilesAdded',function(up, files){
        // $(".org-video-uploadarea").hide();
        $("#novideo").css("display","none");
        if(oList.find('.ov-list-item').length>=3){
            $("#video_add").hide();
            return false;
        }
        for(var i in files){
            myAppend(files[i].id,files[i].name);
        }
        uploader.start();
        //$('#ov-list').append('<a href="javascript:void(0);" class="video-pause-btn btn mr10 mt20">暂停上传</a>').remove();
    });

    uploader.bind('UploadProgress',function(up, file){
        var speed = parseInt(QueueProgress.bytesPerSec/1024)+"KB/s";
        f = parseInt( (QueueProgress.loaded/1024)/1024 )+"M";
        var p = file.percent;
        var h ="<div id='bar_"+file.id+"' class='ov-item-bar' style='width:"+p+"%'></div>";
        //var m = "<span id='msg_"+file.id+"'><?php echo tpl_modifier_tr('速度','site.course'); ?>："+speed+" | <?php echo tpl_modifier_tr('已上传','site.course'); ?>："+f+" </span>";
        var m = "<span id='msg_"+file.id+"'><i id='remove_"+file.id+"' class='fn'><?php echo tpl_modifier_tr('已上传','site.course'); ?>：</i>"+f+",<i id='status_"+file.id+"'>"+p+"%</i></span>";
        $('#jd_'+file.id).html(h);
        $('#msg_'+file.id).html(m);
    });

    uploader.bind('FileUploaded',function(up, file, info){
        if(info.response){
            $("#"+file.id).attr("status",info.response);
            if(info.response==1){
                var status = 0;
                $(".ov-list-item").each(function(){
                    if($(this).attr("status")!=1){
                        status=1;
                    }
                });
                if(status<1){
                    addtask();
                }
                $("#remove_"+file.id).remove();
                $("#status_"+file.id).text('<?php echo tpl_modifier_tr("已完成","site.course"); ?>');
                //window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
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
        html+='<div class="ov-list-item" id="'+fileId+'" status="0">';
        html+='<p id="fid_'+fileId+'" class="ov-item-name fs14">'+fileName+'</p>';
        html+='<div id="jd_'+fileId+'" class="ov-item-progress col-md-15 pd0">';
        html+="</div>";
        html+='<div class="ov-item-tips col-md-5 cGray"><span id="msg_'+fileId+'">等待中</span><i class="del-icon c-fr" fid="'+fileId+'"></i></div>';
        html+="</div>";
        $('#ov-list').show();
        if($('#ov-list').find('.ov-list-item').length<2){
            $('#ov-list').find('#video_add').before(html);
            $('#video_add').show();
        }else{
            $('#ov-list').find('#video_add').before(html);
            $('#video_add').hide();
        }
        $('.org-video-uploadarea').hide();
    }

    //取消上传
    function offVideo(fid){
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
    function delVideo(fileId){
        var status = <?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>;
        var planUid = 0;
        var _self=oList.find('i[fid="'+fileId+'"]');
        if(status == 2){
            planUid = <?php echo SlightPHP\Tpl::$_tpl_vars["planUid"]; ?>;
        }
        if(confirm("<?php echo tpl_modifier_tr('你确定要删除此视频吗?','site.course'); ?>")){
            $.post("/user.teacher.delfile",{ planId:plan_id, fileId:fileId,status:status,planUid:planUid  },function(r){
                if(r.code == 1){
                    layer.msg('<?php echo tpl_modifier_tr("删除成功","site.course"); ?>',{ time:1000 },function(){
                        _self.parents('.ov-list-item').remove();
                        if(oList.find('.ov-list-item').length<3 && oList.find('.ov-list-item').length>0){
                            oList.find('#video_add').show();
                            if(oList.find('#addtask').length==0){
                                $('#jianji').after('<a href="javascript:;" class="btn mt20" id="addtask">开始转码</a>');
                            }
                        }else if(oList.find('.ov-list-item').length==0){
                            window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
                        }
                    });
                }else if(r.code == -2){
                    uploader.removeFile(fileId);
                    layer.msg('<?php echo tpl_modifier_tr("删除成功","site.course"); ?>',{ time:1000 },function(){
                        _self.parents('.ov-list-item').remove();
                        if(oList.find('.ov-list-item').length<3 && oList.find('.ov-list-item').length>0){
                            oList.find('#video_add').show();
                            if(oList.find('#addtask').length==0){
                                $('#jianji').after('<a href="javascript:;" class="btn mt20" id="addtask">开始转码</a>');
                            }

                        }else if(oList.find('.ov-list-item').length==0){
                            window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
                        }
                    });
                }else{
                    layer.msg('<?php echo tpl_modifier_tr("删除失败","site.course"); ?>',{ time:1000 },function(){
                        window.location.href="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>";
                    });
                }
            },"json");
        }
        return false;
    }
    //视频转码
    oList.on('click','#addtask',function(){
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
        if(oList.find('.ov-list-item').length>=3){
            $("#video_add").hide();
        }
        videoListHidden();
    })
    function videoListHidden(){
        if($('.video-list li').length>=3){
            $("#video_add").hide();
        }
    }
    function addtask(){
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
    }
</script>
</html>
