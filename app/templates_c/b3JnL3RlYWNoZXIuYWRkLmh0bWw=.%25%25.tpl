<!DOCTYPE html>
<html>
<head>
<title>添加教师 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 添加教师 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd40">
    <div class="container">
    <div class="row">
        <?php echo tpl_function_part("/org.main.menu.teacher"); ?>
        <div class="right-main col-md-16">
                    <div class="content">
                        <div class="tl-manage-path fs14">
                            <a href="/org.teacher.list"><?php echo tpl_modifier_tr('返回','org'); ?></a>>
                            <span class="cGray"><?php echo tpl_modifier_tr('添加教师','org'); ?></span>
                        </div>
                        <div class="tl-add-form">
                            <dl class="tab">
                                <dd class="curr"><?php echo tpl_modifier_tr('单个添加','org'); ?></dd>
                                <dd><?php echo tpl_modifier_tr('批量添加','org'); ?></dd>
                            </dl>
                            <div class="box c-fl">
                                <ul class="form-horizontal" >
                                    <form id="form-one" class="add-t-div-pd0">
                                        <li class="form-group">
                                            <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('教师名称','org'); ?>：</div>
                                            <div class="label-for col-xs-10 col-sm-10 input-box">
                                                <input type="text" class="col-xs-20 col-sm-12 required verify-judge" data-valid="isNonEmpty||maxLength:10" data-error="教师名称不能为空||最多输入10个字符" data-status="0"  data-tip="请填写教师名称"   id="name" name="name">
                                            </div>
                                        </li>
                                        <li class="form-group">
                                            <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('手机号','org'); ?>：</div>
                                            <div class="label-for col-xs-10 col-sm-10 input-box">
                                                <input type="text" class="col-xs-20 col-sm-12 required verify-judge" data-valid="isNonEmpty||isMobile" data-error="手机号不能为空||手机号码格式不对" data-status="0"  data-tip="请填写手机号"  id="mobile" name="mobile">
                                            </div>
                                        </li>
                                        <li class="form-group common-select-32">
                                            <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('性别','org'); ?>：
                                            </div>
                                           <div class="label-for col-xs-10 col-sm-10">
                                               <div class="divselect divselect-32" id="school_type" class="col-sm-3 col-xs-3">
                                                   <cite>
                                                       <span class="cite-icon"></span>
                                                       <span class="cite-text"><?php echo tpl_modifier_tr('请选择','org'); ?></span>
                                                   </cite>
                                                   <dl>
                                                       <dd >
                                                           <a href="javascript:;" selectid=""><?php echo tpl_modifier_tr('请选择','org'); ?></a>
                                                       </dd>
                                                       <dd >
                                                           <a href="javascript:;" selectid="male"><?php echo tpl_modifier_tr('男','org'); ?></a>
                                                       </dd>
                                                       <dd >
                                                           <a href="javascript:;" selectid="female"><?php echo tpl_modifier_tr('女','org'); ?></a>
                                                       </dd>
                                                   </dl>
                                                   <input type="hidden" id="gender" class="verify-judge" data-error="性别不能为空" name="gender">
                                               </div>
                                           </div>
                                       </li>
                                        <li class="item_2 form-group">
                                            <div class="control-label col-xs-8 col-sm-5">
                                                <?php echo tpl_modifier_tr('擅长学科','org'); ?>：
                                            </div>
                                            <div class="label-for col-xs-12 col-sm-15 dropdown" >
                                                <div class="dropdown col-xs-6 col-sm-6  pd0">
                                                    <div class="dropdown-input" id="t-subj-content">
                                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["good_subject"])){; ?>
                                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["good_subject"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['name'])){; ?>
                                                        <div class="delect-subj dropdown-show-tab pd0 c-fl" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['id']; ?>" contenteditable="false">
                                                            <div class="left-side"></div>
                                                            <div class="tab-delete"></div>
                                                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['name']; ?>
                                                            <input type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['id']; ?>" name="good_subject[]">
                                                        </div>
                                                        <?php }; ?>
                                                        <?php }; ?>
                                                        <?php }; ?>
                                                    </div>
                                                    <div class=" dropdown-box pd0 " id="t-subjects-list">
                                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setArr"])){; ?>
                                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["setArr"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                                        <div class="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["tagStr"])&&SlightPHP\Tpl::$_tpl_vars["tagStr"]==SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag){; ?>on<?php }; ?> dropdown-tab" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>">
                                                            <div class="left-side"></div>
                                                            <div class="tab-add"></div>
                                                            <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->name,'course.list'); ?>
                                                        </div>
                                                        <?php }; ?>
                                                        <?php }; ?>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                                <div class="t-subjects-but col-xs-8 col-sm-8">
                                                    <div  class="tips tip1 pl20 col-xs-8 col-sm-8">
                                                        <span class="tips-icon"></span>
                                                        <span class="tips-text"><?php echo tpl_modifier_tr('至少选择一项，最多选择三项','site.teacher'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            </li>
                                        <!--<li class="form-group">-->
                                                <!--<div class="label col-sm-5"></div>-->
                                                <!--<div class="col-sm-9 t-subjects-list pd0" id="t-subjects-list">-->

                                                <!--</div>-->
                                        <!--</li>-->
                                        <li class="item_1 form-group">
                                            <div class="col-sm-5 control-label"><?php echo tpl_modifier_tr('角色权限','org'); ?>：</div>
                                            <div class="input-box col-sm-7">
                                                <label for="s1">
                                                    <input name="role[]" id="s1" type="checkbox" value="general" /><?php echo tpl_modifier_tr('普通老师','org'); ?>
                                                </label>
                                                <?php /*<input name="role[]" type="checkbox" value="assistant" />助教*/?>
                                                <label for="s2">
                                                    <input name="role[]" id="s2" type="checkbox" value="admin" /><?php echo tpl_modifier_tr('管理员','org'); ?>
                                                </label>
                                                <label class="tip1 cRed"></label>
                                            </div>
                                        </li>
                                        <li class="form-group">
                                            <div class="col-sm-5"> </div>
                                            <div class="col-sm-7 submit"><button class="yellow-button" type="submit"><?php echo tpl_modifier_tr('添加','org'); ?></button></div>
                                        </li>
                                    </form>
                                </ul>
                                <!-- 批量添加 -->
                                <ul class="form-more">
                                    <form id="form-more">
                                        <li>
                                            <div class="col-md-13 row">
                                                <textarea class="col-md-20"  name="mobile" id="" rows="5" placeholder="<?php echo tpl_modifier_tr('请每行添加一个手机号和姓名，手机号和姓名之间用逗号分隔','org'); ?>"></textarea>
                                            </div>
                                            <div class="col-md-7 tips cgray">
                                                <p><?php echo tpl_modifier_tr('添加成功后将通过短信对每个手机发出邀请，提示被加人已经被加入到机构','org'); ?></p>
                                                <p><?php echo tpl_modifier_tr('没有注册的老师获得手机号生成的账号和密码','org'); ?></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="  fs14" style="text-align:left;">
                                                <input name="role[]" type="checkbox" value="general" /><?php echo tpl_modifier_tr('普通老师','org'); ?>
                                                <?php /*<input name="role[]" type="checkbox" value="assistant" />助教*/?>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-10 row"><button  class="yellow-button" type="submit"><?php echo tpl_modifier_tr('添加','org'); ?></button></div>
                                        </li>
                                        
                                        <p class="form-more-import c-fl"><a href="" id="batch_import"><?php echo tpl_modifier_tr('批量导入','org'); ?></a>  <b>|</b>  <a href="<?php echo utility_cdn::img('/assets_v2/教师批量导入模板.xlsx'); ?>"><?php echo tpl_modifier_tr('模版下载','org'); ?></a></p>   
                                       
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='clear'></div>
        </div>
    </div>
</section>
        <?php echo tpl_function_part("/site.main.footer"); ?>
    </body>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.dropdown.tab.js'); ?>"></script>
<script>
$(".form-more-import .elastic-layer").click(function() {
        //弹层失败成功!
        layer.open({
            icon: 1 ,
            area:['300px','200px'],
            title:['批量添加','color:#fff;background:#ffa81d'],
            shadeClose: true,
            content: '<div style="text-align:center;font-size:16px;">上传成功</div>'
        });
        layer.open({
            icon: 2 ,
            area:['300px','200px'],
            title:['批量添加','color:#fff;background:#ffa81d'],
            shadeClose: true,
            content: '<div style="text-align:center;font-size:16px;">上传失败</div>'
        });
});
</script>
<script>
$(function() {
$("input[name='role[]']").each(function() {
	$(this).on('click',function() {
		 if($(this).attr("checked", true)) {
			$(".item_1 .tip1").html('');
		 }
	})
});
    var ddCurrIndex;
    $('.tab>dd').click(function() {
        ddCurrIndex=$(this).index();
        $(this).addClass('curr').siblings().removeClass('curr');
        $('.tl-add-form>div.box>ul').css('display','none');
        $('.tl-add-form>div.box>ul:eq('+ddCurrIndex+')').css('display','block');
    });
    var lock1 = 0,formValidate={ },formValidateJuage;
    $("#form-one").submit(function(){
        event.preventDefault();
        formValidateJuage = true;
        $('.divselect').trigger('validateSelect');
        if($('.divselect').hasClass('error')){
            formValidate['gender'] = false;
        }else{
            formValidate['gender'] = true;
        }
        $('.dropdown').find('.dropdown-input').trigger('dropdownValidate');
        if($('#t-subj-content').find('.dropdown-show-tab').size()==0){
            formValidate['dropdown'] = false;
        }else{
            formValidate['dropdown'] = true;
        }
        if($(this).find("input[name='role[]']:checked").length==0){
            $(".item_1 .tip1").html("<?php echo tpl_modifier_tr('请选择角色权限','org'); ?>");
            formValidate['role'] = false;
        }else{
            formValidate['role'] = true;
        }
        for(var i in formValidate){
            if(!formValidate[i]){
                formValidateJuage = false;
            }
        }
        if($(this).validate('submitValidate')&&formValidateJuage){
            if(lock1==0){
                lock1 = 1;
                $.ajax({
                    'type':'POST',
                    'async': false,
                    'url':'/org.teacher.addteacherAjax',
                    'data':$(this).serialize(),
                    'dataType':'json',
                    'success':function(r){
                        lock1 = 0;
                        if(r.error){
                            layer.msg(r.error);
                            $("[name="+r.field+"]").focus();
                        }else{
                            //$('form [type=submit]').attr('disabled',true);
                            //location.reload();
                            layer.msg("<?php echo tpl_modifier_tr('添加成功','org'); ?>",{ icon: 1 },function(){
                                location.href='/org.teacher.list';
                            });
                        }
                    }
                });
            }
        }else{
            $('.verify-judge').each(function () {
                if($(this).parent().hasClass('error')){
                    $(this).blur();
                    return false;
                }
            });
        }
//        if($(this).find('input[name=name]').val()==''){
//            $(this).find('input[name=name]').focus();
//            return false;
//        }else if($(this).find('input[name=mobile]').val()==''){
//            $(this).find('input[name=mobile]').focus();
//            return false;
//        }else if($(this).find('select[name=gender]').val()=='0'){
//            $('#gender').addClass('box-shadow');
//            return false;
//        }else if($("#t-subj-content .delect-subj").length==0){
//        $(".item_2 .cGray").css("color","red");
//        return false;
//        }else if($(this).find("input[name='role[]']:checked").length==0){
//       $(".item_1 .tip1").html("<?php echo tpl_modifier_tr('请选择角色权限','org'); ?>");
//        return false;
//        }else{

//        }
//        return false;
    });
    var lock2 = 0
    $("#form-more").submit(function(){
        if($(this).find("textarea").val()!=''){
            if(lock2==0){
                lock2 = 1;
                $.post("/org.teacher.addteacherallAjax",$(this).serialize(),function(r){
                    lock2 = 0;
                    if(r.error){
                        alert(r.error);
                    }else{
                        layer.msg("<?php echo tpl_modifier_tr('添加成功','org'); ?>",{ icon: 1 },function(){
                            location.href='/org.teacher.list';
                        });
                    }
                },"json");
            }
        }else{
            lock2 = 0;
            alert('请输入手机号码');
            $(this).find('textarea').focus();
        }
        return false;
    });
    //批量导入
    var uploader = new plupload.Uploader({
         runtimes: 'html5,flash,silverlight,html4',
         browse_button: 'batch_import',
         url: '/org.teacher.ImportTeacherInfo',
         filters: {
             mime_types : [
             { title : "excel files", extensions : "xlsx,txt,xls" }
             ],
             max_file_size:"8192kb"
         }
         ,multi_selection:false
     });
     uploader.init();
     uploader.bind('FilesAdded', function(up, files) {
         uploader.start();
     });
     uploader.bind('FileUploaded', function(up, file,info) {
		if(info.response){
	    	var r = $.parseJSON(info.response);
			if(r.code=='100'){
				var str = '导入成功';
				if(r.repeatNum){
					str = '<span class="fs14">成功'+r.success+'条,重复'+r.repeatNum+'条,失败'+r.failed+'条！</span>';
				}else{
					str = '<span class="fs14">成功'+r.success+'条,失败'+r.failed+'条！</span>';
				}
				layer.confirm(str, {
                    btn: ['确定'], //按钮
					area:['370','230'],
					title:['批量添加','color:#fff;background:#ffa81d'],
					shade: false //不显示遮罩
					},function(){
					if(r.failed=='0'){
						location.href='org.teacher.list';
					}else{
						location.href='org.teacher.orgErrorMobileInfo';
					}
                    
                    });
			}else{
				
			}
		}

	});
});
</script>
</html>
