<!DOCTYPE html>
<head>
<title>资料信息 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 资料信息 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
		<div class="row">
    <?php echo tpl_function_part("/org.main.menu.info"); ?>
<?php if(isset(SlightPHP\Tpl::$_tpl_vars["org"]->tmp_status)&&empty(SlightPHP\Tpl::$_tpl_vars["act"])){; ?>
    <div class="right-main col-md-16 col-sm-16" style="background:none;">
        <div class='content'>
            <p class='pdl5 cDarkgray fs16 fob' style="padding-bottom:20px;"><?php echo tpl_modifier_tr('资料信息','org'); ?></p>
            <div class='pos-rel status'>
		<?php if(SlightPHP\Tpl::$_tpl_vars["org"]->tmp_status==0){; ?>
			<div class="pos-abs status-tips cGray2">
			    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>" alt="" width="90"></p>
				 <p class="fs16"><?php echo tpl_modifier_tr('您的资料信息已提交，我们将在两个工作日内完成资料审核，并通过站内信进行反馈','org'); ?>~</p>
				<p class="p10">
				<a href="org.main.info?act=edit" class="c-fr fs16"><span class="data-edit-icon"></span><?php echo tpl_modifier_tr('回上一步继续修改','org'); ?></a>
				  </p>
			 </div>
		<?php }; ?>
		<?php if(SlightPHP\Tpl::$_tpl_vars["org"]->tmp_status==1){; ?>
			<div class="pos-abs status-tips cGray2">
			    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/pet4.png'); ?>" alt="" width="90"></p>
				 <p class="fs16"><?php echo tpl_modifier_tr('您的资料已经通过审核','org'); ?>~</p>
				<p class="p10">
				<a href="org.main.delCheckInfo?sk=tag"><button class="btn col-sm-3 blue-btn"><?php echo tpl_modifier_tr('点击查看','org'); ?></button></a>
				  </p>
			 </div>
		<?php }; ?>
		<?php if(SlightPHP\Tpl::$_tpl_vars["org"]->tmp_status==-1){; ?>
			<div class="pos-abs status-tips cGray2">
			    <p><img src="<?php echo utility_cdn::img('/assets_v2/img/pet2.png'); ?>" alt="" width="90"></p>
				 <p class="fs16"><?php echo tpl_modifier_tr('非常抱歉您提交的资料未通过审核，原因已经通过站内信反馈给您','org'); ?>~</p>
				<p class="p10">
				<a href="org.main.info?act=edit"><button class="btn col-sm-3 blue-btn"><?php echo tpl_modifier_tr('重新提交资料','org'); ?></button></a>
				  </p>
			 </div>
		<?php }; ?>
            </div>
        </div>
    </div>
<?php }else{; ?>
    <div class='right-main col-md-16 col-sm-16' style="background:none;">
        <div class='content'>
            <p class='pdl5 cDarkgray fs16 fob' style="padding-bottom:20px;"><?php echo tpl_modifier_tr('资料信息','org'); ?></p>
            <form id="info-form">
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('机构LOGO','org'); ?>:</label>
					<label class='j-img'>
						<div id="panel" style="width:180px;height:48px;overflow:hidden;">
							<a class="institution" id="meg">
								<p class="pd0"><?php echo tpl_modifier_tr('点击修改','org'); ?></p>
								<img id="img_p" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big); ?><?php }else{; ?>/assets/images/jgt.jpg<?php }; ?>" alt="">
							</a>
							<?php if(isset(SlightPHP\Tpl::$_tpl_vars["org"]->tmp_status)){; ?>
							 <input type="hidden" name="thumb_big" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big; ?>">
							 <input type="hidden" name="thumb_big" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_med; ?>">
							 <input type="hidden" name="thumb_big" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_small; ?>">
							 <?php }; ?>
							 <input type="hidden" size="4" id="x" name="x">
							 <input type="hidden" size="4" id="y" name="y">
							 <input type="hidden" size="4" id="x2" name="x2">
							 <input type="hidden" size="4" id="y2" name="y2">
							 <input type="hidden" size="4" id="w" name="w">
							 <input type="hidden" size="4" id="h" name="h">
						</div>
					</label>
                    <?php /*<label class="j-img col-lg-8 col-sm-8 col-md-8 col-xs-8">
                        <div class='box-tcimg col-lg-4 col-sm-9 col-md-5'>
                            <img class="col-lg-12 col-sm-12 pd0" src="<?php if(SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/mty.png<?php }; ?>">
                        <p class="col-lg-10 col-lsm-12 pd0"><?php echo tpl_modifier_tr('点击修改','org'); ?></p>
                        </div>
                        <div class='box-from col-sm-2 col-lg-5 pd0 col-md-4'>
                            <input type="button" id="logo" value="<?php echo tpl_modifier_tr('上传图片','org'); ?>">
                            <a class="col-lg-8 hidden-lg hidden-sm hidden-md hidden-xs"><?php echo tpl_modifier_tr('上传图片','org'); ?></a>
                        </div>
                        <div class='form-box'>
                            <input type="hidden" name="thumb_small" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_big; ?>">
                            <input type="hidden" name="thumb_med" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_med; ?>">
                            <input type="hidden" name="thumb_big" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->thumb_small; ?>">
                        </div>
                    </label>*/?>
                    <span class="click-span" style="display:none"><?php echo tpl_modifier_tr('点击我','org'); ?>+</span>
                    <label class="absolite visible-lg"><p><?php echo tpl_modifier_tr('尺寸宽度小于180px，高度48px,支持jpg、png格式，图片小于3000KB','org'); ?></p></label>
                </div>
				<div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('机构全称','org'); ?>:</label>
					<input type="text" class="form-cto fs14 col-md-8" placeholder="<?php echo tpl_modifier_tr('汉字少于30个，支持120英文字符','org'); ?>" maxlength="30" name="name" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->name; ?>">
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('机构简称','org'); ?>:</label>
                    <input type="text" class="form-cto col-md-8 fs14" name="subname" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->subname; ?>" placeholder="<?php echo tpl_modifier_tr('请务必填写真实机构简称','org'); ?>">
                    <div class='clear'></div>
                    <label class="absolite2 visible-lg"><p><?php echo tpl_modifier_tr('便于页面展现,最大长度6个汉字','org'); ?></p></label>
                </div>
                <?php /*
				<div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('公司名称','org'); ?>:</label>
                    <input type="text" class="form-cto col-md-8 fs14" name="company" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->company; ?>" placeholder="<?php echo tpl_modifier_tr('请务必填写真实名称','org'); ?>">
                    <div class='clear'></div>
                    <label class="absolite1 visible-lg"><p><?php echo tpl_modifier_tr('用于正式文档展现,最大长度30个汉字','org'); ?></p</label>
                </div>
				*/?>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('培训范围','org'); ?>:</label>
                   <div class="label-for col-md-17 row" id="train">
                        <input type="text" class="col-sm-6 fs14 form-cto" name="scopestxt" id="scopestxt" style="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org"]->scopes)){; ?>display:block;<?php }else{; ?>display:none;<?php }; ?>" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org"]->scopes)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->scopes; ?><?php }; ?>">
                        <input type="hidden" name="scopes" id="scopes" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["org"]->idStr)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->idStr; ?><?php }; ?>">
                        <span class="edit-train">点击可修改</span>
                        <span class=" msg fs12 cRed"></span>
                    </div>
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('所在地区','org'); ?>:</label>
                    <select id="province" class="sel-1 col-sm-3 col-md-3 fs14" name="province">
                        <option value='0'><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["province_list"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["province_list"] as SlightPHP\Tpl::$_tpl_vars["province"]){; ?>
                        <option value='<?php echo SlightPHP\Tpl::$_tpl_vars["province"]->region_id; ?>' <?php if(SlightPHP\Tpl::$_tpl_vars["org"]->province==SlightPHP\Tpl::$_tpl_vars["province"]->region_id){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["province"]->name; ?></option>
                        <?php }; ?>
                        <?php }; ?>
                    </select>
                    <select class="city sel-2 col-sm-3 col-md-3 fs14" name="city" style="display:none;">
                        <option value='0' class="fs12"><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                    </select>
                    <select class="countyi col-sm-2 col-md-2 sel-2 fs14" name="county" style="display:none;">
                        <option value='0' class="fs12"><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                    </select>
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('联系地址','org'); ?>:</label>
                    <input type="address" class="form-cto col-md-8 fs14" name="address" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->address; ?>" placeholder="<?php echo tpl_modifier_tr('请务必填写真实联系地址','org'); ?>">
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('服务电话','org'); ?>:</label>
                    <input type="text" class="form-cto col-md-8 col-sm-6 fs14" placeholder="<?php echo tpl_modifier_tr('请务必填写真实服务电话','org'); ?>" name="hotline" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->hotline; ?>">
                    <div class="col-sm-5 lh22 fs14 cLightgray mt10 pdr0">区号-电话号 例：010-2109822</div>
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('电子邮箱','org'); ?>:</label>
                    <input type="email" class="form-cto fs14 col-md-8" placeholder="<?php echo tpl_modifier_tr('请务必填写真实邮箱地址','org'); ?>" name="email" value="<?php echo SlightPHP\Tpl::$_tpl_vars["org"]->email; ?>">
                </div>
                <div class="form-group col-md-20">
                    <label class="col-md-3"><span class="cRed">*</span>&nbsp;<?php echo tpl_modifier_tr('关于我们','org'); ?>:</label>
                    <br>
                    <div class="clear"></div>
                    <textarea id="desc" name="desc" placeholder="请输入结构简介"><?php if(empty(SlightPHP\Tpl::$_tpl_vars["org"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->desc; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->desc; ?><?php }; ?></textarea>
                </div>
                <div class="form-group col-md-20 ">
                    <input type="submit" value="<?php echo tpl_modifier_tr('提交审核','org'); ?>" class='form-but col-md-6 col-md-offset-7'>
                </div>
            </form>
        </div>
    </div>
	<?php } ; ?>
	</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<style>
.layui-layer-btn a.layui-layer-btn0{ background:#ffa81d; }
.layui-layer-btn .layui-layer-btn1 { background:#d3d3d3; }
</style>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<script type="text/javascript">
$(function(){
    $('#meg').on('click', function(){
        layer.open({
            type: 2,
            title: ['上传图片','color:#fff;background:#ffa81d'],
            shadeClose: true,
            shade: 0.8,
            area: ['500px','400px'],
            //content: '/f-1.html'
            content: '<?php echo "/org.main.iframeLogo"; ?>'
        });
    });

    $('#megs').on('click', function(){
        layer.open({
            type: 2,
            title: '添加修改',
            area: ['800px', '600px'],
            shadeClose: true,
            content: '<?php echo "/org.main.iframeAbout"; ?>'
        });
    });
})
</script>
<script type="text/javascript">
$(function  () {
    var logo = new plupload.Uploader({
        browse_button: 'logo', // this can be an id of a DOM element or the DOM element itself
        url: '/file.main.uploadOrgLogo',
        filters: {
            mime_types : [
            { title : "Image files", extensions : "jpg,png" }
            ],
            max_file_size:"100kb"
        }
        ,multi_selection:false
    });
    logo.init();
    logo.bind('FilesAdded', function(up, files) {
        logo.start();
    });
    logo.bind('Error', function(up, err) {
        if(err.code=='-600'){
            layer.msg('图片大小不符合标准');
        }
    });
    logo.bind('UploadProgress', function(up, file) {
        $("#logo").val("上传中："+ file.percent +"%");
    });
    logo.bind('FileUploaded', function(up, file,info) {
        $("#logo").val("上传完成");
        if(info.response){
            var r = jQuery.parseJSON(info.response);
            if(r.error){
                layer.msg(r.error);
                $("#logo").val("上传图片");
            }else{
                $('.j-img').find('.box-tcimg img').attr('src',r.src+"?"+Math.random());
                $('.j-img').find('input[name=thumb_small]').val(r.thumb_small);
                $('.j-img').find('input[name=thumb_med]').val(r.thumb_med);
                $('.j-img').find('input[name=thumb_big]').val(r.thumb_big);
            }
        }
        
    });
    /*$('.box-tc form').submit(function(){
        var src=$('#show-big').find('input[name=thumb_small]').val();
        $.post("/user.org.uploadOrgLogoAjax",$(this).serialize(),function(r){
            if(r.error){
                alert(r.error);
                return false;
            }else{
			//parent.layer.closeAll()
			 window.parent.location.reload();		
			}
		},"json");
        return false;
    });*/


});
</script>
<script src="<?php echo utility_cdn::js('/assets/libs/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets/libs/ckeditor/adapters/jquery.js'); ?>"></script>
<script type="text/javascript">
$(function  () {
    $(window).bind('beforeunload',function(){
        return '您输入的内容尚未保存，确定离开此页面吗？';
    });
	//培训范围
	$("#train").on("click","input,.edit-train",function(){
		layer.open({
			type:2,
			title: ['选择培训范围','background:#ffa918;color:#fff;'],
			shadeClose: true,
			shade: 0.8,
			area: ['450px', '320px'],
			content: '<?php echo "/org.main.orgCourseRange"; ?>'
		});
	});
	
    $( '#desc' ).ckeditor( { customConfig: '/assets/libs/activeNotice.config.js' } );
        //弹层失败成功!
      <?php /*  layer.open({
            area:['300px','200px'],
            title:['温馨提示','color:#fff;background:#ffa81d'],
            shadeClose: true,
            btn: ['确定', '离开'],
            content: '<div style="text-align:center;font-size:16px;color:#333;">您正在编辑状态，需要进行审核</div>'
        });
        
        */?>
    $('#province').change(function(){
        var city=<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org"]->city)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->city; ?><?php }else{; ?>''<?php }; ?>;
        $('.city').html('').hide();
        $('.county').html('').hide();
        $.post("/user.org.getRegionAjax",{ rid:$(this).val(),level:1 },function(r){
            if(r.data){
                var json=r.data;
                var option='';
                $(json).each(function(i){
                    var s='';
                    if(json[i].region_id==city){
                        s='selected';
                    }
                    option+=('<option '+s+' value="' +json[i].region_id+ '">' +json[i].name+ '</option>');
                });
                $('.city').html(option);
                $('.city').show().trigger('change');
            }
        },"json");
    }).trigger('change');

    $('.city').change(function(){
        var county=<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["org"]->county)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["org"]->county; ?><?php }else{; ?>''<?php }; ?>;
        $('.county').html('').hide();
        $.post("/user.org.getRegionAjax",{ rid:$(this).val(),level:2 },function(r){
            if(r.data){
                var json=r.data;
                var option='';
                $(json).each(function(i){
                    var s='';
                    if(json[i].region_id==county){
                        s='selected';
                    }
                    option+=('<option '+s+' value="' +json[i].region_id+ '">' +json[i].name+ '</option>');
                });
                $('.county').html(option);
                $('.county').show();
            }
        },"json"); 
    });
	
    $("#info-form").submit(function(){
        if($(this).find('input[name=name]').val() == ''){
            layer.msg('机构全称不能为空');
        }else if($(this).find('input[name=subname]').val()==''){
            layer.msg('机构简称不能为空');
            $(this).find('input[name=subname]').focus().css("background-color","#FFFFCC");
        }else if($(this).find('input[name=subname]').val().length>6){
            layer.msg('机构简称不能超过六个字');
            $(this).find('input[name=subname]').focus().css("background-color","#FFFFCC");
        }else{
		layer.confirm('修改资料信息，需要提交平台进行审核，平台通过后修改生效。', {
		            title:'提交资料审核',
                    btn: ['确定','取消'], //按钮
					shade: false //不显示遮罩
					}, function(){
					//layer.msg("提交成功");
					$.post("/org.main.updateOrgProfileAjax",$("#info-form").serialize(),function(r){
                if(r.error){
                    layer.msg(r.error);
                    if(r.field){
                        $("[name="+r.field+"]").focus();
                    }
                }else{
                    $(window).unbind('beforeunload');
                    layer.msg('提交成功',{ icon: 1 },function(){
                        location.href='org.main.Info';
                    });
            }
            return false;
            },"json");



		 }, function(){
			 //layer.msg('取消成功');
		});


		}
        return false;
    });
});
</script>
</html>
