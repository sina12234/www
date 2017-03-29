<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>客服设置 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 客服设置 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>" ></script>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
</head>

<body>
<!--header-->
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
    <div class="row">
    <?php echo tpl_function_part("/org.main.menu.customer"); ?>
    <div class="right-main col-sm-9 col-md-16">
        <div class="content" id="customer">
            <p class='pdl5 cDarkgray fs16 fob pd10' style="border-bottom:1px solid #ccc"><?php echo tpl_modifier_tr('客服设置','org'); ?>
                <span class="customer-intro cGray fs14 pl10">
                    <?php echo tpl_modifier_tr('温馨提示','org'); ?>：<?php echo tpl_modifier_tr('客服信息将以右下角浮标形式展现在机构前台，填写后有助于学员联系机构','org'); ?>
                </span>
            </p>
                <div class="customer-li">
                <p class="col-lg-2"><span class="c-name"><?php echo tpl_modifier_tr('二维码设置','org'); ?></span></p>
                <p class="col-md-10 c-info fs12 ml20"><?php echo tpl_modifier_tr('( 机构二维码上传，展现在首页位置，扫码关注 )','org'); ?></p>
				<?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima)){; ?>
                <div class="customer-main col-md-18">
                    <div class="customer-main-name col-lg-3 fs14"><?php echo tpl_modifier_tr('上传二维码','org'); ?>：</div>
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
					<input type="hidden" name="type" value="1">
					 <?php foreach(SlightPHP\Tpl::$_tpl_vars["customerData"]->weima as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
					 <input type="hidden" name="c_id" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_customer; ?>">
                    <div class="customer-main-c col-lg-12">
                        <div class="qrcode-lg">
                        <img id="img_o" src=""  />
						<label id="progress" for="" class="photoRate"></label>
                        </div>
                        <div class="c-fl ml10" style="float: left; width: 180px;">
                            <div class="qrcode-sm" id="panel" style="width:128px;height:128px;overflow:hidden">
							<img id="img_p" src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?>"  style="width:128px;height:128px;"  alt=""/>
							</div>
                            <p class="col-lg-20">128*128<?php echo tpl_modifier_tr('像素','org'); ?></p>
                            <div class="mt10"><button id="browse"><?php echo tpl_modifier_tr('选择图片','org'); ?></button><button id="up-save"><?php echo tpl_modifier_tr('保存图片','org'); ?></button></div>
                        </div>
                    </div>
					<?php }; ?>
					</form>
                </div>
				<?php }else{; ?>
						<div class="customer-main" style="display:block">
                    <div class="customer-main-name col-md-3 fs14"><?php echo tpl_modifier_tr('上传二维码','org'); ?>：</div>
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
					<input type="hidden" name="type" value="1">
					 <input type="hidden" name="c_id" value="">
                    <div class="customer-main-c col-lg-12">
                        <div class="qrcode-lg">
                        <img id="img_o" src=""  />
						<label id="progress" for="" class="photoRate"></label>
                        </div>
                        <div class="c-fl ml10" style="float: left;width: 180px;">
                            <div class="qrcode-sm" id="panel" style="width:128px;height:128px;overflow:hidden">
							<img id="img_p" src=""  style="width:128px;height:128px;"  alt=""/>
							</div>
                            <p class="col-lg-20">128*128<?php echo tpl_modifier_tr('像素','org'); ?></p>
                            <div class="mt10"><button id="browse"><?php echo tpl_modifier_tr('选择图片','org'); ?></button><button id="up-save"><?php echo tpl_modifier_tr('保存图片','org'); ?></button></div>
                        </div>
                    </div>

					</form>
                </div>
				<?php }; ?>
            </div>
            <div class="customer-li" id="tel">
                <p class="col-md-2"><span class="c-name"><?php echo tpl_modifier_tr('客服电话','org'); ?></span></p>
                <p class="col-md-18 c-info fs12"><?php echo tpl_modifier_tr('( 方便学员通过电话联系机构，最多可填4个 )','org'); ?></p>
                <div class="customer-item">
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <div class="customer-main customer-main2 fs14 customer-list col-md-8" style="display: block;">
                        <div class="col-md-7 c-names fs14">
                            <span class="tel-icon"></span>
                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>
                        </div>
                        <div class="col-md-11">
                            <?php echo tpl_modifier_tr('电话','org'); ?>：
                            <span class="tel"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?></span>
                        </div>
                        <a href="javascript:void(0)" class="c-del del-icon" c_type_value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type; ?>" item_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_customer; ?>"></a>
                        <a href="javascript:void(0)" item_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_customer; ?>" class="c-edit edit-icon"></a>
				    </div>
                <?php }; ?>
				<?php }else{; ?>

                <?php }; ?>
                </div>
                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)&&!empty(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)){; ?>
				<?php if(isset(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel)&&count(SlightPHP\Tpl::$_tpl_vars["customerData"]->tel) < 4){; ?>
				<div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
				    <a class="cYellow" id="tel_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
				</div>
                                <?php }; ?>
		<?php }else{; ?>
                                <div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
				    <a class="cYellow" id="tel_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
				</div>
                <?php }; ?>
            </div>
            <div class="customer-li" id="qq">
                <p class="col-md-2"><span class="c-name"><?php echo tpl_modifier_tr('QQ客服','org'); ?></span></p>
                <p class="col-md-18 c-info fs12">
                    (从客服库中关联QQ客服，最多可关联4个)
                </p>
                <div class="customer-item">
				<?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq)&&!empty(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq)){; ?>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <div class="customer-main customer-main2 fs14 customer-list col-md-8" style="display: block;">
                    <div class="col-md-7 c-names fs14">
                        <span class="qq-icon"></span>
                        <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>
                    </div>
                    <div class="col-md-11">
                        <?php echo tpl_modifier_tr('QQ','org'); ?>：
                        <span class="c-nums"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?></span>
                    </div>
                    <input type="hidden" name="qq_type" value="3">

                    <a href="javascript:void(0)" class="c-del del-icon" c_type_value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type; ?>" kf_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_relation; ?>" item_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_customer; ?>"></a>

                </div>
				<?php }; ?>

				<?php }; ?>
                </div>

		<?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq)&&!empty(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq)){; ?>
                                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq)&&count(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qq) < 4){; ?>
				<div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
				    <a class="cYellow " id="qq_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
				</div>
                                <?php }; ?>
                <?php }else{; ?>
                <div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
				    <a class="cYellow " id="qq_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
				</div>
                <?php }; ?>

            </div>
            <div class="customer-li" id="qqs">
                <p class="col-md-3"><span class="c-name"><?php echo tpl_modifier_tr('课程咨询群','org'); ?></span></p>
                <p class="col-md-18 c-info fs12">
                    (从QQ群库里关联QQ群，最多可关联4个)
                </p>
                <div class="customer-item">
				<?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun)&&!empty(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun)){; ?>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <div class="customer-main customer-main2 fs14 customer-list col-md-8" style="display: block;">
                    <div class="col-sm-10 c-names fs14">
                        <span class="mans-icon"></span>
                        <?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_name; ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo tpl_modifier_tr('QQ群号','org'); ?>：
                        <span class="c-nums"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type_value; ?></span>
                    </div>
                    <input type="hidden" name="qqun_type" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type; ?>">

                   
                    <a href="javascript:void(0)" class="c-del del-icon" c_type_value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->type; ?>" kf_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_relation; ?>" item_id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_customer; ?>"></a>

                </div>
				<?php }; ?>
                                <?php }; ?>
                </div>
		<?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun)&&!empty(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun)){; ?>
                                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun)&&count(SlightPHP\Tpl::$_tpl_vars["qqdata"]->qqun) < 4){; ?>
				<div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
                    <a href="javascript:void(0)" class="cYellow" id="qqun_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
                </div>
                                <?php }; ?>
               <?php }else{; ?>
               <div class="customer-main fs14 col-md-18" style="display: block;">
                    <span class="icon-add"></span>
                    <a href="javascript:void(0)" class="cYellow" id="qqun_add_continue">+<?php echo tpl_modifier_tr('添加','org'); ?></a>
                </div>
               <?php }; ?>



            </div>
            </div>
        </div>
    </div>
</div>
    <!--客服-->
    <section class="layer-teacherlist multiple-select-list" id="multiple-select-list" style="display:none;">
        <input type="hidden" name="type" value=""/>
        <div class="lc-so-name mt20">
            <div class="search-frame ml40">
                <input name="keyword" class="search-input" id="search-teacher-infos" type="text" value="" placeholder="搜索名称" />
                <button class="search-box org-t-search-btn" id="subsearch" style="float:left;">
                    <span class="search-icon" style="margin: 0;"></span>
                    <div class="t-list-img clear-icon" id="t-delt-btn" style="display:none;">
                    </div>
                </button>
            </div>
        </div>
        <div class="lc-list mt15 ml40">
            <ul class="multiple-select mt10" id="multiple-left" style="overflow-y: scroll;"></ul>
        </div>
        <div class="co-option mt15" id="co-option">
            <a href="javascript:;" id="add-btn">添加 》</a>
            <a href="javascript:;" class="mt10" id="del-btn">《 删除</a>
        </div>
        <div class="lc-list">
            <p class="fs14">已选择</p>
            <ul class="multiple-select mt10">
                <li class="defalut tac multiple-tip">没有数据</li>
                <ul id="multiple-right"></ul>
            </ul>
        </div>
        <div class="col-xs-20 tac mt10">
            <button class="mr20 btn" id="course_add">确定</button>
            <button class="gray-button" id="course_cel">取消</button>
        </div>
    </section>
    <!--/客服-->
    <!--客服电话-->
<section id="tool_comment" style="display: none;">
    <div class="comment-layer col-sm-20 col-xs-20 form" id="comment_box" data-status="">
        <input type="hidden" name="tid" value=""/>
        <input type="hidden" name="c_type" value="2"/>
        <div class="row form">
            <div class="label col-sm-5 col-xs-5">客服名称</div>
            <input type="text" class="col-sm-15 col-xs-15" name="tel_name" placeholder="请输入客服名称" />
        </div>
        <div class="row form">
            <div class="label col-sm-5 col-xs-5">电话号码</div>
            <input type="text" class="col-sm-15 col-xs-15" name="telphone" placeholder="请输入电话号码" />
        </div>
        <div class="comment-btn col-sm-20 col-xs-20 view-hidden pt30 tac">
            <button id="comment_send" class="mr20 btn">确定</button>
            <button id="comment_cancel" class="gray-button">取消</button>
        </div>
    </div>
</section>
<!--/客服电话-->
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script  type="text/javascript">

$(function() {
    /*客服电话*/
    //客服电话--删除
    $('#tel .c-del').click(function(){
        var item_id = $(this).attr("item_id");
		var type = $(this).attr("c_type");
        layer.confirm("<?php echo tpl_modifier_tr('确定删除吗','org'); ?>?", {
                    title:"<?php echo tpl_modifier_tr('客服电话','org'); ?>",
                    btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
                    shade: false //不显示遮罩
                    }, function(){
                    $.post("/org/customerServices/delOrgCustomerInfo",{ type:type,tid:item_id },function(r){
                    if(r.code=='1'){
                        location.reload();
                    }else if(r.code=='-1'){
						layer.msg("<?php echo tpl_modifier_tr('删除失败','org'); ?>");
                        return false;
                    }
                },"json");
         }, function(){
        });

    });
//    客服电话--编辑
    $('#tel .c-edit').click(function(){
        var item_id = $(this).attr("item_id");//tid数值
        var item_value=$(this).siblings('.c-names');//客服名称
        var item_value2=$(this).siblings().find('.tel');//电话号码
        showQQ();
        $('input[name="tid"]').val(item_id);
        $('input[name="c_type"]').val(2);
        $('input[name="tel_name"]').val($.trim(item_value.text()));
        $('input[name="telphone"]').val(item_value2.text());
    });
//    客服电话--添加
    $(".customer-main").on("click","#tel_add_continue",function(){
        if($("#tel .customer-main").length=='6'){
            layer.msg("<?php echo tpl_modifier_tr('最多只能增加4个电话','org'); ?>");
            return false;
        }
        showQQ();

    });
//    添加客服电话--保存
    $("#tool_comment").on("click","#comment_send",function(){
        var item_id = $('input[name="tid"]').val();
        var telphone = $.trim($("input[name=telphone]").val());
        var telname = $.trim($("input[name=tel_name]").val());
        var type = $("input[name=c_type]").val();
        if(telname.replace(/[\u0391-\uFFE5]/g,"aa").length >12){
            layer.msg("<?php echo tpl_modifier_tr('长度不能超过6个汉字或12个字符','org'); ?>");
            return false;
        }
        if(telname==''){
            layer.msg("<?php echo tpl_modifier_tr('客服电话不能为空','org'); ?>");
            return false;
        }
        if(telphone==''){
            layer.msg("<?php echo tpl_modifier_tr('客服电话不能为空','org'); ?>");
            return false;
        }
        if(!item_id){
            $.post("/org/customerServices/addOrgCustomerInfo",{ type_name:telname,type_value:telphone,type:type },function(r){
                if(r.error){
                    layer.msg(r.error);
                    return false;
                }
                if(r.code=='1'){
                    layer.msg("<?php echo tpl_modifier_tr('添加成功','org'); ?>",{ icon: 1 },function(){
                        window.location.reload();
                    });
                }
            },"json");
        }else{
            $.post("/org/customerServices/updateOrgCustomInfo",{ type_name:telname,type_value:telphone,tid:item_id,type:type },function(r){
                if(r.error){
                    layer.msg(r.error);
                }
                if(r.code=='1'){
                    layer.msg("<?php echo tpl_modifier_tr('修改成功','org'); ?>",{ icon: 1 },function(){
                        window.location.reload();
                    });
                }
            },"json");
        };

    });
//    客服电话--取消
    $('#tel').on('click','.cancel',function(){
        var item_value=$(this).siblings('input[name="type_value"]').val();
        $(this).closest('.customer-main').find('.c-edit').show();
        $(this).closest('.customer-main').find('.c-del').show();
        $(this).closest('.c-names').html('客服电话：<span>'+item_value+'</span>');

    });
    /*QQ客服*/

//    QQ客服--添加
    $(".customer-main").on("click","#qq_add_continue",function(){
        if($("#qq .customer-main2").length >=4){
            layer.msg("<?php echo tpl_modifier_tr('最多只能增加4个QQ客服','org'); ?>");
            return false;
        }
        layer.open({
            type: 1,
            title: ['关联客服'],
            closeBtn: 1,
            area: ['765px','510px'],
            shadeClose: true,
            content:$('#multiple-select-list')
        });
        $('#add-btn').removeClass('allow');//初始化添加按钮
        $('#del-btn').removeClass('allow');//初始化删除按钮
        $("#subsearch").siblings('input[name="keyword"]').val('');//初始化--清空--搜索内容
        $('#multiple-select-list').find('input[name="type"]').val(1);//type 1:QQ客服 2:QQ群课程咨询群
        // 过滤已添加数据
        var idStr='';
        $("#qq .customer-main2").each(function(){
            var item_id=$(this).find('a').attr('item_id');
            var c_names=$(this).find('.c-names').text();
            idStr += '<li class="dropdown-cents" pk_customer="'+item_id+'"><div class="tab-delete"></div>'+c_names+'</li>'
        });
        $("#multiple-right").html(idStr);
        getTools(1);
    });

//    QQ客服--删除
    $('#qq .c-del').click(function(){
        var kf_id = $(this).attr("kf_id");//注意* 删除所用ID 和courseid用的不是同一个
        layer.confirm("<?php echo tpl_modifier_tr('确定要删除吗','org'); ?>?", {
            title:"<?php echo tpl_modifier_tr('QQ客服','org'); ?>",
            btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
//            area:['600px','200px'],
            shade: false //不显示遮罩
        }, function(){
            $.post("/org/customerTools/unbindCustomerInfoAjax",{ pid:kf_id },function(r){
                if(r.code=='0'){
                    layer.msg("删除成功~");
                    location.reload();
                }else if(r.code=='-1'){
                    layer.msg("<?php echo tpl_modifier_tr('删除失败','org'); ?>");
                    return false;
                }
            },"json");
        });
    })
//    课程咨询群--添加
    $(".customer-main").on("click","#qqun_add_continue",function(){
        if($("#qqs .customer-main2").length >=4){
            layer.msg("最多只能增加4个客服群~");
            return false;
        }
        layer.open({
            type: 1,
            title: ['关联QQ群'],
            closeBtn: 1,
            area: ['765px','510px'],
            shadeClose: true,
            content:$('#multiple-select-list')
        });
        $('#add-btn').removeClass('allow');//初始化添加按钮
        $('#del-btn').removeClass('allow');//初始化删除按钮
        $("#subsearch").siblings('input[name="keyword"]').val('');//初始化--清空--搜索内容
        $('#multiple-select-list').find('input[name="type"]').val(2);//type 1:QQ客服 2:QQ群课程咨询群
        // 过滤已添加数据
        var idStr='';
        $("#qqs .customer-main2").each(function(){
            var item_id=$(this).find('a').attr('item_id');
            var c_names=$(this).find('.c-names').text();
            idStr += '<li class="dropdown-cents" pk_customer="'+item_id+'"><div class="tab-delete"></div>'+c_names+'</li>'
        });
        $("#multiple-right").html(idStr);
        getTools(2);

    });

//    课程咨询群--删除
    $('#qqs .c-del').click(function(){
        var kf_id = $(this).attr("kf_id");
        layer.confirm("<?php echo tpl_modifier_tr('确定要删除吗','org'); ?>?", {
            title:"<?php echo tpl_modifier_tr('课程咨询群','org'); ?>",
            btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
            shade: false //不显示遮罩
        }, function(){
            $.post("/org/customerTools/unbindCustomerInfoAjax",{ pid:kf_id },function(r){
                if(r.code=='0'){
                    layer.msg("删除成功~");
                    location.reload();
                }else if(r.code=='-1'){
                    layer.msg("<?php echo tpl_modifier_tr('删除失败','org'); ?>");
                    return false;
                }
            },"json");
        });
    })

//    点击（添加、编辑弹框）取消按钮
    $("#comment_cancel").click(function(){
        $(this).parents('.layui-layer-content').find('input').val('');//清空填写内容
        layer.closeAll();
    });

    //二维码设置
    var uploader = new plupload.Uploader({
         runtimes: 'html5,flash,silverlight,html4',
         browse_button: 'browse',
         url: '/file.main.uploadPic',
         filters: {
             mime_types : [
             { title : "Image files", extensions : "jpg,jpeg,gif,png" }
             ],
             max_file_size:"8192kb"
         }
         ,multi_selection:false
     });

     uploader.init();

     uploader.bind('FilesAdded', function(up, files) {
         uploader.start();
     });

     uploader.bind('UploadProgress', function(up, file) {
         $("#progress").css({ "height":"100%" });
         $("#progress").html("上传中："+ file.percent +"%");
     });
     uploader.bind('FileUploaded', function(up, file,info) {
         if(info.response){
             $("#progress").css({ "height":"0" });
             if(jcrop_api)jcrop_api.destroy();
             $("#img_o").hide().attr("src",info.response+"?"+Math.random());
             $("#img_o").attr("src",info.response+"?"+Math.random());
             $("#img_p").attr("src",info.response+"?"+Math.random());

             $('#img_o').Jcrop({
                 boxWidth:200,boxHeight:200,
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
     //保存图片
     $("#up-save").click(function(){
         var big = $('input[name=big]').val();
         if(isNaN(parseInt($("#w").val())) && !big){
             layer.msg("<?php echo tpl_modifier_tr('请选择需要上传的二维码','org'); ?>");
             return false;
         }
         $.post("/org.customerServices.twoCode",$('#up-form').serialize(),function(r){
             if(r.error){
                 layer.msg(r.error);
                 return false;
             }else{
                layer.msg("<?php echo tpl_modifier_tr('保存成功','org'); ?>",{ icon: 1 },function(){
                window.location.reload();
                });
             }
         },"json");
         return false;
     });

                var jcrop_api,
                boundx,
                boundy;
});
//展示（添加、编辑）弹框
function showQQ(){
        layer.open({
            type: 1,
            title: ['添加电话客服'],
            closeBtn: 1,
            area: ['430px','280px'],
            shadeClose: true,
            content:$('#tool_comment')
        });
}
(function() {
    var $selectParentId = $('#multiple-select-list');
    var $selectAddChildren = $('#add-btn');
    var $selectDelChildren = $('#del-btn');
    var $selectMultipleRt = $('#multiple-right');
    var $selectMultipleLt = $('#multiple-left');
    var $selectCourseAdd = $('#course_add');
    var $selectCourseCancle = $('#course_cel');
    var $searchChildren = $("#subsearch");
//  点击选择li
    $selectParentId.on('click', 'li:not(.selected)', function() {
        if($(this).hasClass('select')){
            $(this).removeClass('select');
        }else{
            $(this).addClass('select');
        }
        if($selectMultipleRt.find('li.select').length > 0){
            $selectDelChildren.addClass('allow');
        }else{
            $selectDelChildren.removeClass('allow');
        }
        if($selectMultipleLt.find('li.select').length > 0 ){
            $selectAddChildren.addClass('allow');
        }else{
            $selectAddChildren.removeClass('allow');
        }
    });
//  添加按钮添加到右侧
    $selectAddChildren.click(function() {
        if($(this).hasClass('allow')){
            var cHtml='';
            $selectMultipleLt.find('li.select').each(function() {
                cHtml += '<li class="dropdown-cents" pk_customer="'+$(this).attr('pk_customer')+'">'+$(this).html()+'</li>';
                $(this).removeClass('select');
                $(this).addClass('selected');
            });
            $selectMultipleRt.append(cHtml);
            $selectMultipleRt.find('.defalut').remove();
            $('.multiple-tip').hide();
            if($selectMultipleLt.find('li').length==0){
                $(this).removeClass('allow');
            }
        }else{
            $selectMultipleLt.css('border','1px solid #ffa91e');
        }
        $(this).removeClass('allow');
    })
//  删除按钮从右侧删除
    $selectDelChildren.click(function() {
        if($(this).hasClass('allow')){
            var cHtml='';
            $selectMultipleRt.find('li.select').each(function() {
                var id = $(this).attr('pk_customer');
                cHtml += '<li class="dropdown-cents" pk_customer="'+id+'">'+$(this).html()+'<div class="tab-delete"></div></li>';
                $(this).remove();
                $selectMultipleLt.find('.selected').each(function(){
                    if($(this).attr('pk_customer') == id){
                        $(this).removeClass('selected');
                    }
                })
            });
//            $selectMultipleLt.find('li').removeClass('selected');
            if($selectMultipleRt.find('li').length==0){
                $(this).removeClass('allow');
                $selectMultipleRt.html('');
                $('.multiple-tip').show();
            }
        }else{
            $selectMultipleRt.css('border','1px solid #ffa91e');
        }
        $(this).removeClass('allow');
    })
//  确定按钮--添加数据
    $selectCourseAdd.click(function() {
        if($('#multiple-right').find('.dropdown-cents').length >=5) {
            layer.msg('最多选择四个数据');
            return false;
        }else {
            var type=$('#multiple-select-list').find('input[name="type"]').val();
            var customerids=[];
            $("#multiple-right li").each(function(){
                var customerid=$(this).attr('pk_customer');
                customerids.push(customerid);
            });
            customerids=customerids.join();
            var params={
                object_type:1,
                type:type,
                customerids:customerids
            }
            $.ajax({
                type:'post',
                url:'/org/customerTools/bindCustomerInfoAjax',
                data:params,
                dataTYpe:'json',
                success:function(xhr){
                    xhr=JSON.parse(xhr);
                    if(xhr.code == 0) {
                        layer.msg(xhr.msg);
                        window.location.reload();
                    }else{
                        layer.msg(xhr.msg);
                    }
                }
            })

        }
    })
//  取消按钮
    $selectCourseCancle.click(function() {
        layer.closeAll();
    })
//  搜索匹配
    $searchChildren.click(function(){
        var keyWords=$(this).siblings('input[name="keyword"]').val();
        var type=$(this).parents("#multiple-select-list").find('input[name="type"]').val();
        $.ajax({
            type:'post',
            url:'/org/customerTools/listAjax',
            data:{ type:type,type_name:keyWords},
            dataType:'json',
            success:function(r){
                if(r.code == 0 ){
                    if(type == 1){
                        var data=r.data[0].qq;
                        var liStr='';
                        $(data).each(function(i,item){
                            var selected = 0;
                            $('#multiple-right li').each(function(){
                                if($(this).attr('pk_customer') == item.pk_customer){
                                    selected = 1;
                                }
                            });
                            if(selected == 1){
                                liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents show selected"><div class="tab-delete"></div>'+item.type_name+'</li>'
                            }else{
                                liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents"><div class="tab-delete"></div>'+item.type_name+'</li>'
                            }
                        });
                        if(liStr == ''){
                            var errorStr='<p class="fs12 tac">没有数据哦</p>'
                            $('#multiple-left').html(errorStr);
                            return false
                        }else{
                            $('.multiple-tip').hide();
                            $('#multiple-left').html('').append(liStr);
                        }

                    }else if(type == 2){
                        var data=r.data[0].qqun;
                        var liStr='';
                        $(data).each(function(i,item){
                            var selected = 0;
                            $('#multiple-right li').each(function(){
                                if($(this).attr('pk_customer') == item.pk_customer){
                                    selected = 1;
                                }
                            });
                            if(selected == 1){
                                liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents show selected"><div class="tab-delete"></div>'+item.type_name+'</li>'
                            }else{
                                liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents"><div class="tab-delete"></div>'+item.type_name+'</li>'
                            }
                        });
                        if(liStr == ''){
                            var errorStr='<p class="fs12 tac">没有数据哦</p>'
                            $('#multiple-left').html(errorStr);
                            return false
                        }else{
                            $('.multiple-tip').hide();
                            $('#multiple-left').html('').append(liStr);
                        }

                    }


                }
            }
        })
    })
//  左侧滚动加载
    var Page1=1,Page2=2;
    $selectMultipleLt.scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(this)[0].scrollHeight;
        var offetHeight  = $('#multiple-left').height();
        if(scrollTop + offetHeight == scrollHeight){
            var type=$selectParentId.find('input[name="type"]').val();
            if(type == 1){
                Page1++;
                if(!getTools(1,Page1)){
                    $('#multiple-left').unbind('scroll');
                }
            }else if(type == 2){
                Page2++;
                if(!getTools(2,Page2)){
                    $('#multiple-left').unbind('scroll');
                }
            }

        }
    })

})();
function getTools(t,curr){
    var orgQQServiceTpl = $('#orgQQServiceTpl').html();
    var page = curr || 1;
    var params={
        page:page,
        pageSize:11,
        type:t
    }
    $.ajax({
        url:'/org/customerTools/listAjax',
        type:'post',
        data:params,
        dataType: 'json',
        success:function(r){
            if(curr == 1 || !curr ){
                $('.multiple-tip').show();
                $('#multiple-left').html('')
            }
            if(r.code == 0) {
                if(t == 1){
                    if(r.data[0].qq == ''){
                        $('.multiple-tip').show();
                        return false;
                    }
                    var data=r.data[0].qq;
                    var liStr='';
                    $(data).each(function(i,item){
                        var selected = 0;
                        $('#multiple-right li').each(function(){
                            if($(this).attr('pk_customer') == item.pk_customer){
                                selected = 1;
                            }
                        });
                        if(selected == 1){
                            liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents show selected"><div class="tab-delete"></div>'+item.type_name+'</li>'
                        }else{
                            liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents"><div class="tab-delete"></div>'+item.type_name+'</li>'
                        }
                    });
                    $('.multiple-tip').hide();
                    $('#multiple-left').append(liStr);
                    return true

                }else {
                    if(r.data[0].qq == ''){
                        $('.multiple-tip').show();
                        return false;
                    }
                    var data=r.data[0].qqun;
                    var liStr='';
                    $(data).each(function(i,item){
                        var selected = 0;
                        $('#multiple-right li').each(function(){
                            if($(this).attr('pk_customer') == item.pk_customer){
                                selected = 1;
                            }
                        });
                        if(selected == 1){
                            liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents show selected"><div class="tab-delete"></div>'+item.type_name+'</li>'
                        }else{
                            liStr += '<li pk_customer='+item.pk_customer+' class="dropdown-cents"><div class="tab-delete"></div>'+item.type_name+'</li>'
                        }
                    });
                    $('.multiple-tip').hide();
                    $('#multiple-left').append(liStr);
                    return true
                }

            }
        }
    })
}
</script>
