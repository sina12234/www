<!DOCTYPE html>
<html>
<head>
<title>首页维护 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页维护 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/colorpicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::js('/assets_v2/css/colorpicker.css'); ?>">
</head>
<body >
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/org.main.menu.setting"); ?>
            <div class="right-main col-sm-9 col-md-16">
                <div class="content">
                    <div class="tab-main fs14">
                        <div class="tab-hd">
                            <a class="tab-hd-opt curr" href="/org.main.setting"><?php echo tpl_modifier_tr('基础设置','org'); ?></a>
                            <a class="tab-hd-opt" href="/org.main.template"><?php echo tpl_modifier_tr('首页模版设置','org'); ?></a>
                            <!--a class="tab-hd-opt" href="/org.main.template">首页导航自定义</a>
                            <a class="tab-hd-opt" href="/org.main.template">App首页设置</a-->
                        </div>
                    </div>
                    <!--程热度展示-->
                    <div class='nav-color col-sm-12 col-xs-12 col-md-20'>
                        <span></span>
                        <p><?php echo tpl_modifier_tr('首页课程热度展示','org'); ?></p>
                    </div>
                    <div class='teacher-recommend fs14 col-md-20'>
                        <div class="col-sm-3 col-md-5">
                            <input type="radio" name="hottype" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==1){; ?>checked<?php }; ?> id="a1" value="1"/><span class="num-icon t-maintain-num-icon"></span>&nbsp;<?php echo tpl_modifier_tr('报名人数','org'); ?>
                        </div>
                        <div class="col-sm-3 col-md-5" style="position: relative;">
                            <input type="radio" name="hottype" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==2){; ?>checked<?php }; ?> id="a2" value="2" /> <?php echo tpl_modifier_tr('课程剩余报名人数','org'); ?>
                            <span class="g-icon8 t-maintain-g-icon8"></span>
                        </div>
                        <div class="col-sm-3 col-md-5">
                            <input type="radio" name="hottype" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==3){; ?>checked<?php }; ?> id="a3" value="3" style="float:left;margin-top:4px;" /> <span class="g-icon11 t-maintain-g-icon11"></span>&nbsp;<?php echo tpl_modifier_tr('课程播放量','org'); ?>
                        </div>
                        <div class="col-sm-3 col-md-5">
                            <input type="radio" name="hottype" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==4){; ?>checked<?php }; ?> id="a4" value="4"/> <?php echo tpl_modifier_tr('不显示','org'); ?>
                        </div>
                    </div>
                    <!--直播列表-->
                    <div class="col-md-20 nav-color pd0">
                        <span></span>
                        <p>直播列表</p>
                        <ul class="col-md-20 fs14">
                            <li class="col-md-3">
                            <div>
                                <input type="radio" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show==1){; ?>checked<?php }; ?> name="li_show" value="1"/> 显示
                            </div>
                            </li>
                            <li class="col-md-3">
                            <div>
                                <input type="radio" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show==2){; ?>checked<?php }; ?> name="li_show" value="2"/> 隐藏
                            </div>
                            </li>
                        </ul>
                    </div>
                    <!--标签展示-->
                    <div class='setting-bt nav-color col-md-5 c-fl'>
                        <span></span>
                        <p class="c-fl"><?php echo tpl_modifier_tr('标签展示','org'); ?></p>
                        <a href="#" class="doubt-icon c-fl ml10 mt5" title="1.设置在此处的标签将展现在机构全部课程页；
2.用户通过展示的标签可以更快捷的筛选课程。"></a>
                    </div>
				    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["selected"])){; ?>
                    <div class="tagas-modify col-md-20">
                        <p class="c-fl">
					   	<?php foreach(SlightPHP\Tpl::$_tpl_vars["selected"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                           <span class="c-fl taga-summer"><b></b><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
					   	<?php }; ?>
                        </p>
                        <a href="javascript:void(0);" class="c-fr modify-sow" id="editTag">修改标签</a>
                    </div>
				    <?php }else{; ?>
				    <div class="tags-default col-md-17" style="display:block">
                        <p class="c-fl">您还没有添加标签</p>
                        <p class="c-fr tage-sow" id="addTag">+新增标签</p>
                    </div>
				    <?php }; ?>

                    <div class="col-xs-15 col-md-20" style="display:none;" id="tags-list">
                        <div class="c-fl col-md-16 onmouse">
                            <div id="onmouse" class="c-fl"></div>
                            <div class="c-fl inputTaga tagNameStr">
                                <input class="inputTagator" name="inputTagator" id="inputTagator"  type="text" placeholder="点击空白输入标签">
                            </div>
                        </div>
                       <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"]) or !empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                        <div class="inputTaga-display col-lg-16" id="inputTaga">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"])){; ?>
                            <p>
                            <span class="c-fl mr10">最近使用:</span>&nbsp;
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["often"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <span class="c-fl taga-summer" ><b></b><i></i><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
                            <?php }; ?>
                            </p>
                            <?php }; ?>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                            <p>
                            <span class="c-fl mr10">常用标签:</span>&nbsp;
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["lasted"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <span class="c-fl taga-summer"><b></b><i></i><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
                            <?php }; ?>
                            </p>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                        <div class="taga-buton"><button id="tagbutton">保存</button><button class="tagas-cancel">取消</button></div>
                        <p class="col-xs-20 pd0 cGray fs12">每个标签限10个字符，输入标签名称后点击回车，最多添加6个标签</p>
                    </div>
                    <!--首页幻灯片-->
                    <div class='setting-bt nav-color col-md-5 c-fl'>
                        <span></span>
                        <p><?php echo tpl_modifier_tr('首页幻灯片','org'); ?></p>
                    </div>
                    <div class='slide'>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slideList"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["slideList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["slide"]){; ?>
                        <div class='col-sm-12 col-xs-12 hor wrap-manage-edit col-md-17'>
                            <form method="post" autocomplete="off" >
                                <input type="password" style="display:none;">
                                <div class='col-sm-12 col-xs-4 slide-pic col-md-20'>
								    <div class="slide-pic-bg" style="background-color:<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>;">
									   <p id="edit_<?php echo SlightPHP\Tpl::$_tpl_vars["sk"]; ?>" class="slide-pic-mourse" sid="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>"></p>
									   <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url); ?><?php }else{; ?>/assets_v2/img/img-sc.jpg<?php }; ?>" class="streak">
								    </div>
								    <p class="col-sm-12 hidden-sm mt5"><?php echo tpl_modifier_tr('尺寸支持890*360,支持jpg,gif,png,最大800K','org'); ?></p>
                                    <div class="c-fr mt5">背景颜色：<input class="icolor" id="colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>" style="background-color:<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>;"><input type="hidden" class="icolor-input" name="color_rgb" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>"></div>
								    <input type="hidden" name="sid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>">
								    <input type="hidden" name="fid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url; ?>">
                                </div>
                                <p class='clear'></p>
                                <div class='col-sm-12 col-xs-8 form-lab col-md-20'>
                                    <div class='slide-bt col-sm-1 col-md-2'><p><?php echo tpl_modifier_tr('链接','org'); ?></p></div>
                                    <label class="col-sm-9 col-md-15">
                                        <input type="text" class="col-sm-9 col-lg-15  manage-upload-title" readonly name="slide_link" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->slide_link; ?>">
                                    </label>
                                </div>
                                <p class="clear"></p>
                                <div class='hor-img'>
                                    <div class="wrap-but-btbox col-lg-7 ">
                                        <div class="wrap-but-no c-fr"><input type="button" class="edit-cancel" value="<?php echo tpl_modifier_tr('取消','org'); ?>"></div>
                                        <div class="wrap-but-off c-fr"><input type="button" class="edit-slide" value="<?php echo tpl_modifier_tr('保存','org'); ?>"></div>
                                    </div>
                                    <p class="c-fr but14 manage-edit-btn"><?php echo tpl_modifier_tr('编辑','org'); ?></p>&nbsp;&nbsp;&nbsp;
                                    <?php if(count(SlightPHP\Tpl::$_tpl_vars["slideList"])>1){; ?>
                                    <a href="javascript:;"  class="manage-delet-btn" sid="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>" id="hide"></a>
                                    <?php }; ?>
                                </div>
                            </form>
                        </div>
                        <?php }; ?>
                        <?php }; ?>
                        <div id='wrap' class='col-sm-12 col-xs-12 hor wrap-manage-edit col-md-17' style='display:none'>
                            <form method="post" autocomplete="off" >
                                <input type="password" style="display:none;">
                                <div class='col-sm-12 col-xs-4 slide-pic col-md-20'>
								    <div class='slide-pic-bg col-md-20 pos-rel'>
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/img-sc.jpg'); ?>"  class='img-responsive streak col-sm-10 col-md-20' id="add1">
                                        <div id="en-upload-imgtip" class="en-fns-tip pos-abs fs16">Click upload pictures</div>
								    </div>
								    <p class="col-sm-12 hidden-sm"><?php echo tpl_modifier_tr('尺寸支持890*360,支持jpg,gif,png,最大800K','org'); ?></p>
                                    <div class="c-fr mt5">背景颜色：<input class="icolor" id="colorSelector-add" style="background-color:rgb(0,0,0);"><input class="icolor-input" type="hidden" name="color_rgb" value="#000000"></div>
								    <input type="hidden" name="act" value="1">
								    <input type="hidden" name="fid" value="">
                                </div>
                                <p class="clear"></p>
                                <div class='col-sm-12 col-xs-8 form-lab col-md-20'>
                                    <div class='slide-bt col-sm-1 col-md-2'><p><?php echo tpl_modifier_tr('链接','org'); ?></p></div>
                                    <label class="col-sm-9 col-lg-15">
									   <input type="text" class="col-sm-9 col-md-15  manage-upload-title" style="border:1px solid #e8e8e8;" name="slide_link" value="">
								    </label>
								    <div class="wrap-but-btbox col-md-20 col-md-offset-3" style="display:block;">
                                        <div class="wrap-but-no c-fr"><input type="button" class="edit-reset" value="<?php echo tpl_modifier_tr('取消','org'); ?>"></div>
                                        <div class="wrap-but-off c-fr"><input type="button" class="edit-slide" value="<?php echo tpl_modifier_tr('保存','org'); ?>"></div>
								    </div>
                                </div>
                                <input type='hidden' name='noticeToken' value='<?php echo SlightPHP\Tpl::$_tpl_vars["noticeToken"]; ?>'>
                            </form>
                        </div>
                    </div>
                    <?php if(count(SlightPHP\Tpl::$_tpl_vars["slideList"])<5){; ?>
					<div class='slide-btn col-sm-12 mt20 row col-md-10'>
						<input type="button" class="col-sm-8 col-md-8" value="+<?php echo tpl_modifier_tr('继续添加幻灯片','org'); ?>" id="add-btn">
					</div>

                    <?php }; ?>
                    <p class="clear"></p>
                  <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
                  <?php }else{; ?>
                    <div class="menu-info-box c-fl nav_name_area">
                        <div class='teacher-recommend col-md-20 mt10 pdl0'>
                            <div class='nav-color col-md-5'>
                                <span></span>
                                <p>首页快速导航</p>
                            </div>
                            <div class="col-md-2 mt10 pd0 mr10">
                                <input type="radio" name="nav_name_btn" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav==1){; ?>checked<?php }; ?> value="1">&nbsp;开启
                            </div>
                            <div class="col-md-2 mt10 pd0 mr10">
                                <input type="radio" name="nav_name_btn" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav==0){; ?>checked<?php }; ?> value="0">&nbsp;关闭
                            </div>
                            <div class="col-md-10 mt10 pd0">
                                <p class="cGray">(至少添加一个菜单项才可开启快速导航)</p>
                            </div>
                        </div>

						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgNav"])){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["orgNav"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<div class="c-fl setting-menu-infov2 col-md-17 mt10" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>">
                        <div class="menu-info-one col-md-20 pd0">
                        <p class="c-fl">菜单项<?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?></p>
                        <p class="c-fr"><span class="icon edit-nav-btn" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>"></span><span class="icon1 del-nav-btn" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>"></span></p>
                        </div>
                        <div class="menu-info-two c-fl col-md-20 pd0 mt10">
                        <p>
                            <span class="c-fl lh30">文字:&nbsp;&nbsp;</span>
							<input type="text" class="col-md-5 pd0" name="e_name" disabled value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->nav_name; ?>"><span class="nav_tip" style="display:none;">请输入2~8个文字</span>
						</p>
                        </div>
                        <div class="menu-info-address c-fl col-md-20 pd0 mt10">
                        <p class="col-md-20 pd0">
                            <span class="c-fl lh30">地址:&nbsp;&nbsp;</span>
							              <input type="text" name="e_url" class="col-md-15 pd0" disabled value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>">
                        </p>
                        </div>
                        <div class="menu-info-revoke c-fl col-md-20 pd0" style="display:none;">
                            <input type="submit" class="menu-submit update_nav_btn" value="保存">
                            <input type="submit" class="menu-submit2" value="取消">
                        </div>
                        </div>
						<?php }; ?>
						<?php }; ?>
						<div class="c-fl setting-menu-info col-md-17" style="display:none">
                           <div class="menu-info-one col-md-20 pd0">
                               <p class="c-fl">菜单项</p>
                           </div>
                           <div class="menu-info-two c-fl col-md-20 pd0 mt10">
                                <p class="col-md-20 pd0">
                                    <span class="c-fl lh30">文字:&nbsp;&nbsp;</span>
                                    <input type="text" name="nav_name" class="col-md-5 pd0">&nbsp; 请输入2~8个文字
                                </p>
                           </div>
                            <div class="menu-info-address c-fl col-md-20 pd0 mt10">
                                <p class="col-md-20 pd0">
                                    <span class="c-fl lh30">地址:&nbsp;&nbsp;</span>
                                    <input type="text" name="nav_url" class="col-md-15 pd0">
                                </p>
                            </div>
                            <div class="menu-info-revoke c-fl col-md-20 pd0">
                                <input type="submit" class="menu-submit" id="nav_submit" value="保存">
                                <input type="submit" class="menu-submit2" value="取消">
                            </div>
                        </div>
							<?php if(count(SlightPHP\Tpl::$_tpl_vars["orgNav"])<6){; ?>
                            <div class="c-fl col-md-20 mt10 pd0">
                                <p class="menu-info-add">+添加菜单选项</p>
                            </div>
							<?php }; ?>
                    </div>

                    <!--教师推荐-->
                        <div class='teacher-recommend mt20'>
                             <div class='nav-color col-sm-12 col-xs-12'>
                                <span></span>
                                <p><?php echo tpl_modifier_tr('教师推荐','org'); ?></p>
                            </div>
                        </div>
                        <div class='teacher-icon col-sm-12 col-xs-12 col-md-20'>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["starTeacher"])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["starTeacher"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <div class='col-sm-2 col-xs-2 teacher-t col-md-3'>
                                <p class="mourse-opacity recomm-teacher" data-cid='0'>
                                <span class="top-teacher"><?php echo tpl_modifier_tr('点击修改','org'); ?></span>
                                </p>
                                <img src="<?php echo utility_cdn::img('/assets_v2/img/hove-end.png'); ?>" class="down-teacher" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_id; ?>">
                                <p><img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/defaultPhoto.gif<?php }; ?>"  class='img-responsive'></p>
                                <p><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?><?php }; ?></p>
                            </div>
                            <?php }; ?>
                            <?php }; ?>
                            <?php if(count(SlightPHP\Tpl::$_tpl_vars["starTeacher"])>=0&&count(SlightPHP\Tpl::$_tpl_vars["starTeacher"])<5){; ?>
                            <div class='col-sm-2 col-xs-2 teacher-t col-md-3 recomm-teacher' data-cid='1'>
                                <p class="no-img">
                                +<?php echo tpl_modifier_tr('添加教师','org'); ?></p>
                            </div>
                            <?php }; ?>
                            <?php }; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slideList"])){; ?>
$(function(){
<?php foreach(SlightPHP\Tpl::$_tpl_vars["slideList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["slide"]){; ?>
$('#colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>').ColorPicker({
    color: '#000000',
    onShow: function (colpkr) {
        $(colpkr).fadeIn(500);
        return false;
    },
    onHide: function (colpkr) {
        $(colpkr).fadeOut(500);
        return false;
    },
    onChange: function (hsb, hex, rgb) {
        $('#colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>').css('backgroundColor', '#' + hex);
        $('#colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>').siblings(".icolor-input").val('#' + hex);
        $('#colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>').closest(".slide-pic").find(".slide-pic-bg").css('backgroundColor', '#' + hex);
    }
});
<?php }; ?>
});
<?php }; ?>
$('#colorSelector-add').ColorPicker({
    color: '#000000',
    onShow: function (colpkr) {
        $(colpkr).fadeIn(500);
        return false;
    },
    onHide: function (colpkr) {
        $(colpkr).fadeOut(500);
        return false;
    },
    onChange: function (hsb, hex, rgb) {
        $('#colorSelector-add').css('backgroundColor', '#' + hex);
        $('#colorSelector-add').siblings(".icolor-input").val('#' + hex);
    }
});
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript">
$(function  () {
	function mystr(str){
		var l = str.length;
		var blen = 0;
		for(i=0; i<l; i++) {
            if ((str.charCodeAt(i) & 0xff00) != 0) {
			    blen ++;
			}
			    blen ++;
		}
		if(blen>10){
			layer.msg("不能超过10个字符");
			return false;
		}
	}
    //首页快速设置
    $(".menu-info-add").click(function(){
        $(".setting-menu-info").show();
    })


    //机构标签
    if($('#onmouse .on').length >= 6 ) {
        $('.inputTagator').hide();
    }
    $('#addTag').on("click",function(e){
        $('#tags-list').show();
        $('.tags-default').hide();
    })

    $('#tags-list .tagas-cancel').on("click",function (e) {
        $('#onmouse').empty();
        $('.tagNameStr').find('input:hidden').remove();
        $('#tags-list').hide();
        $('.tagas-modify').show();
    })
    $('#editTag').on("click",function(e){
        $('#tags-list').show();
        $('.tagas-modify span').each(function(){
            var that=$(this).text();
            $('#onmouse').append('<span class="on"><b></b>'+$(this).text()+'<i></i></span>')
            $(".tagNameStr").append("<input type='hidden' name='tagName[]' value='"+$(this).text()+"'/>");
            $('.taga-summer').each(function (e){
                if(that == $(this).text()){
                    $(this).addClass('taga-end');
                }
            })
        });
        if($('#onmouse .on').length >= 6 ) {
            $('.inputTagator').hide();
        }
        $('.tagas-modify').hide();

    })

    $('#tags-list .onmouse').click(function(e){
        e.stopPropagation();
        $('.inputTaga-display').show();
        $(".inputTaga input").focus();
        if($('#onmouse .on').length > 5){
            $(".inputTaga input").hide();
        }else{
            $(".inputTaga input").show();
        }
    })
    $('#onmouse').on('click','#tagats-end',function(){
        $(this).parent().hide();
    })
    $("#inputTagator").keydown(function(e){
        var e = e || event,
        keycode = e.which || e.keyCode;
        if (keycode==32 || keycode==13 || (event.shiftKey && event.keyCode==13)) {
            var tagName = $.trim($(this).val());
            if(tagName==''){
                layer.msg('标签不能为空');
                return false;
            } else {
                if(mystr(tagName)==false){
                    return false;
                }else{
                    if($('#onmouse .on').length > 6) {
                        $('.inputTagator').hide();
                        layer.msg('只能添加六项');
                        return false;
                    }else {
                        Contrasts(tagName);
                        if($('#onmouse .on').length == 6 ) {
                            $('.inputTagator').hide();
                            return false;
                        }
                    }
                }
            }
        }
        if (keycode===8) {
            var len = $('.inputTagator').val();
            var s_len = $('#onmouse>span');
            if (len.length < 1){
                if(s_len.length>0){
                    $('#onmouse span:last').remove();
                }
            }
        }
    });

    function Contrasts(that){
        var tagatHtml = '<span class="on"><b></b>'+that+'<i></i></span>';
        var tagsList=[];
        $('#onmouse .on').each(function(){
            tagsList.push($(this).text());
        });
        if($.inArray(that,tagsList) == -1){
            $("#onmouse").append(tagatHtml);
            $('input[name="inputTagator"]').val('');
            $(".tagNameStr").append("<input type='hidden' name='tagName[]' value='"+that+"'/>");
            tageEnd(that);
        }else{
            layer.msg('这个标签已经有了');
        }
        if($('#onmouse .on').length >= 6 ) {
            $('.inputTagator').hide();
            return false;
        }
    }
    function tageEnd(that){
        $(".taga-summer").each(function(){
            if($(this).text()==that){
                $(this).addClass('taga-end');
            }
        })
    }

    $('#inputTaga .taga-summer').on('click',function (e){
        e.stopPropagation();
        var tagName=$(this).text();
        if(!$(this).hasClass('on')){
            addtable(tagName);
        }
    });
    $('#onmouse').on('click','i',function(){
        if($(this).parent().attr('class')=="on"){
            var thatval = $(this).text();
            $("input[name='tagName[]").each(function (e){
                if(thatval==$(this).val()){
                    $(this).remove();
                }
            });
            removetable($(this).parent());
        }
    });

    function addtable(that){
        var tagatHtml = '<span class="on"><b></b>'+that+'<i></i></span>';
        if($('.onmouse .on').length >= 6) {
            $('.inputTagator').hide();
            layer.msg('只能添加六项');return false;
        }
        Contrasts(that);
    }
    $(".onmouse").on("click","i",function(e){
        var thatval = $(this).parents('.on').html();
        $(this).parents('.on').remove();
        $("input[name='tagName[]").each(function (e){
            if(thatval==$(this).val()){
                $(this).remove();
            }
        })
    });
    function removetable(that){
        that.remove().html();
        that.find("input[name='tagName[]']").remove();
        $('.taga-summer').each(function (e){
            if(that.text() == $(this).text()){
                $(this).removeClass('taga-end');
            }
        })
        $("input[name='tagName[]").each(function (e){
            if(that.text()==$(this).val()){
                $(this).remove();
            }
        })
    };
    $('body,html').click(function (e){
        e.stopPropagation();
        $('.inputTaga-display').hide();
    })
    //幻灯片
    $(".manage-edit-btn").on("click",function() {
        $(this).hide();
        $(this).parents('form').find(".wrap-but-btbox").show();
        $(this).parents('form').find(".manage-upload-title").css("border","1px solid #e8e8e8").removeAttr('readonly');
        $(this).parents('form').find(".slide-pic-bg > .slide-pic-mourse").css("display","block");
    });
    $(".edit-cancel").on("click",function() {
        $(this).parents(".hor-img").find(".manage-edit-btn").show();
        $(this).parents('form').find(".wrap-but-btbox").hide();
        $(this).parents('form').find(".manage-upload-title").css("border","none");
        $(this).parents('form').find(".manage-upload-title").attr('readonly','readonly');
        $(this).parents('form').find(".slide-pic-bg > .slide-pic-mourse").css("display","none");
    });
    $(".edit-reset").click(function() {
        $(this).parents('.wrap-manage-edit').hide();
        if($('.hor').length<=5){
            $("#add-btn").show();
        }
    });
    $(".manage-upload-title").blur(function() {
        $(this).parents('form').find(this).css("border","1px solid #ccc");
    });


	$('#add-btn').click(function(){
		if($('.hor').length>1){
            $(this).hide();
        }
		$('#wrap').show();

    });
    $('.slide').on('click','.close',function(){
        if($('.hor').length<=5){
           $('#add-btn').show();
        }
        if($('.hor').length>1){
            $(this).parents('.hor').remove();
        }
    });

    $('.slide').on('click','.manage-delet-btn',function(){
        var sid=$(this).attr("sid");
        if(sid){
            layer.confirm('您确定要删除这张幻灯片吗？', {
                btn: ['确定','取消'], //按钮
                shade: false //不显示遮罩
            }, function(){
                $.post("/org.main.delOrgSlideAjax","sid="+sid,function(r){
                    if (r && r.error) {
                        layer.msg(r.error);
                    }else{
                        location.reload();
                    }
                },"json");
            }, function(){
                //layer.msg('取消成功');
            });
        }else{
            $(this).parents('.wrap-manage-edit').remove();
        }
    });
    var _Lock=0;
    $('.slide').on('click','.edit-slide',function(){

        if(_Lock==0) {
           _Lock=1;
           $.post("/org.main.UpdateSlideAjax",$(this).parents('form').serialize(),function(r){
              _Lock=0;
              if(r.error){
                layer.msg(r.error);
                   return false;
              }else{
                layer.msg('保存成功',{ icon:6 },function(){ location.reload(); });
              }
          },"json");
       }
        return false;
    });


    $('#add-notice').click(function(){
        layer.open({
            type: 2,
            title: ['设置公告','color:#fff;background:#ffa81d'],
            shadeClose: true,
            shade: 0.8,
            area: ['600px', '90%'],
            content: '<?php echo "/org.main.iframeNotice"; ?>'
        });
    });
    $(".sett-nav").on('click','.edit-notice',function(){
        var nid=$(this).attr('nid');
        layer.open({
            type: 2,
            title: ['设置公告','color:#fff;background:#ffa81d'],
            shadeClose: true,
            shade: 0.8,
            area: ['600px', '90%'],
            content: '<?php echo "/org.main.iframeNotice."; ?>'+nid
        });
    });
    $('.top-course').each(function(i){
        var top=i+1;
        $(this).click(function(){
            layer.open({
                type: 2,
                title: ['课程推荐','color:#fff;background:#ffa81d'],
                shadeClose: true,
                shade: 0.8,
                area: ['600px', '50%'],
                content: '<?php echo "/org.main.iframeCourse."; ?>'+top
            });
        });
    });
    $('.down-course').click(function(){
        var cid=$(this).attr('cid');
        $.post("/org.main.downCourseAjax",{ 'cid':cid },function(r){
            if(r.error){
                layer.msg(r.error);
            }else{
                layer.msg('取消成功',{ icon:6 },function(){ location.reload(); });
            }
        },"json");
    });
    $('.recomm-teacher').each(function(i){
        var is_star=i+1;
        $(this).click(function(){
			var recommend_type = $(this).attr("data-cid");
			var old_id = $(this).next().attr("tid");
            layer.open({
                type: 2,
                title: ["<?php echo tpl_modifier_tr('教师推荐','org'); ?>",'color:#fff;background:#ffa81d'],
                shadeClose: true,
                shade: 0.8,
                area: ['600px', '50%'],
                content: '<?php echo "/org.main.iframeTeacher."; ?>'+is_star+'.'+recommend_type+'.'+old_id
            });
        });
    });
    $('.down-teacher').click(function(){
        var tid=$(this).attr('tid');
        $.post("/org.main.downteacherAjax",{ 'tid':tid },function(r){
            if(r.error){
                layer.msg(r.error);
            }else{
                layer.msg('取消成功',{ icon:6 },function(){ location.reload(); });
            }
        },"json");
    });

     //课程热度展示
    $('input[name="hottype"]').click(function(){
        var hot_type =$("input[name='hottype']:checked").val();
        var li_show =$("input[name='li_show']:checked").val();
        var nav_name =$("input[name='nav_name_btn']:checked").val();
        $.post("/org.main.updatehot",{ 'hot_type':hot_type,'li_show':li_show,'nav_name_btn':nav_name },function(r){
            if(r.error){
                layer.msg(r.error);
                return false;
            }else{
                layer.msg('修改成功');
            }
        },"json");
    });
    //直播列表显示,隐藏
    $('input[name="li_show"]').click(function(){
        var li_show =$("input[name='li_show']:checked").val();
        var hot_type =$("input[name='hottype']:checked").val();
		var nav_name =$("input[name='nav_name_btn']:checked").val();
        $.post("/org.main.updatehot",{ 'hot_type':hot_type,'li_show':li_show,'nav_name_btn':nav_name },function(r){
            if(r.error){
                layer.msg(r.error);
                return false;
            }else{
                layer.msg('修改成功');
            }
        },"json");
    });
	//快捷导航的展示隐藏
    $('input[name="nav_name_btn"]').click(function(){
        var li_show =$("input[name='li_show']:checked").val();
        var hot_type =$("input[name='hottype']:checked").val();
		var nav_name =$("input[name='nav_name_btn']:checked").val();
		console.log(nav_name);
        $.post("/org.main.updatehot",{ 'hot_type':hot_type,'li_show':li_show,'nav_name':nav_name},function(r){
            if(r.error){
                layer.msg(r.error);
                return false;
            }else{
                layer.msg('修改成功');
            }
        },"json");
    });
	$("#tags-list").on("click","#tagbutton",function(e){
		var tagName =$(".onmouse .on").map(function() {
			return $(this).text();
		}).get().join(",");

        $.post("/org.main.addOrgTagAjax",{ tagName:tagName },function(r){
            if(r.code==0){
				layer.msg("<?php echo tpl_modifier_tr('修改成功','org'); ?>",{ icon: 1 },function(){
                    location.reload();
                });
			}else{
				layer.msg(r.message);
			}
		},"json");
	});
	function navStr(str){
		var l = str.length;
		var blen = 0;
		for(i=0; i<l; i++) {
            if ((str.charCodeAt(i) & 0xff00) != 0) {
			    blen ++;
			}
			    blen ++;
		}
		if(blen<4||blen>16){
			layer.msg("请输入2~8个文字");
			return false;
		}
	}
	$(".nav_name_area").on("click","#nav_submit",function(){
		var nav_name = $.trim($("input[name='nav_name']").val());
		var nav_url = $("input[name='nav_url']").val();
		if(navStr(nav_name)==false){
			return false;
		}
		$.post("/org.main.addOrgOfNav",{ nav_name:nav_name,nav_url:nav_url },function(r){
            if(r.code<0){
                layer.msg(r.msg);
			}else{
				layer.msg("<?php echo tpl_modifier_tr('添加成功','org'); ?>",{ icon: 1 },function(){
                    location.reload();
                });
			}
		},"json");
	});
	$(".menu-submit2").click(function(){
        location.reload();
    })
	$(".nav_name_area").on("click",".del-nav-btn",function(){
		var nid = $(this).attr("data-nid");
		console.log(nid);
		layer.confirm("<?php echo tpl_modifier_tr('确定删除此菜单项吗','org'); ?>?", {
                    title:"<?php echo tpl_modifier_tr('删除信息','org'); ?>",
                    btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
                    shade: false //不显示遮罩
                    }, function(){
                    $.post("/org.main.delOrgOfNav",{ nid:nid},function(r){
						if(r.code<0){
							layer.msg(r.msg);
						}else{
							layer.msg("<?php echo tpl_modifier_tr('删除成功','org'); ?>",{ icon: 1 },function(){
								location.reload();
							});
						}
					},"json");
         }, function(){
        });
	});
	$(".nav_name_area").on("click",".edit-nav-btn",function(){
		var nid = $(this).attr("data-nid");
		$(this).parents('.setting-menu-infov2').find('input').prop('disabled', false);
		$(this).parents('.setting-menu-infov2').find('.menu-info-revoke').show();
		$(this).parent(".c-fr").hide();
		$(this).parents('.setting-menu-infov2').find('.nav_tip').show();

	});
	$('.update_nav_btn').each(function() {
		var $upDateNavBtn = $(this);
		$upDateNavBtn.click(function() {
			var $upDateName = $.trim($(this).parents('.setting-menu-infov2').find('input[name="e_name"]').val());
			var $upDateUrl = $(this).parents('.setting-menu-infov2').find('input[name="e_url"]').val();
			var $nid = $(this).parents('.setting-menu-infov2').attr('data-nid');
			if(navStr($upDateName)==false){
				return false;
			}
			$.post("/org.main.updateOrgOfNavOneInfo",{ nid:$nid,nav_name:$upDateName,nav_url:$upDateUrl},function(r){
						if(r.code<0){
							layer.msg(r.msg);
						}else{
							layer.msg(r.msg,{ icon: 1 },function(){
								location.reload();
							});
						}
		},"json");


		})
	})

})
</script>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {
	$(".wrap-manage-edit form").each(function(i){
		var edit = "edit_"+i;
		edit = new plupload.Uploader({
			runtimes:'html5,flash,silverlight,html4',
			browse_button: edit,
			url: '/file.main.uploadOrgSlide',
			filters: {
				mime_types : [
				{ title : "Image files", extensions : "jpg,gif,png" }
				],
				max_file_size:"800kb"
			}
			,multi_selection:false
		});
		edit.init();
		edit.bind('FilesAdded', function(up, files) {
			edit.start();
		});
		edit.bind('Error', function(up, err) {
			if(err.code=='-600'){
				layer.msg('图片大小不符合标准');
			}
		});
		edit.bind('FileUploaded', function(up, file,info) {
			if(info.response){
				var r = jQuery.parseJSON(info.response);
				if(r.error){
					layer.msg(r.error);
				}else{
					$('#edit_'+i).next().attr('src',r.src+"?"+Math.random());
					$('#edit_'+i).parents('.slide-pic').find('input[name=fid]').val(r.fid);
				}
			}
		});
	});


	var add1 = new plupload.Uploader({
        runtimes:'html5,flash,silverlight,html4',
        browse_button: 'add1',
        url: '/file.main.uploadOrgSlide',
        filters: {
            mime_types : [
            { title : "Image files", extensions : "jpg,gif,png" }
            ],
            max_file_size:"800kb"
        }
        ,multi_selection:false
    });
    add1.init();
    add1.bind('FilesAdded', function(up, files) {
        add1.start();
    });
    add1.bind('Error', function(up, err) {
        if(err.code=='-600'){
            layer.msg('图片大小不符合标准');
        }
    });
    add1.bind('FileUploaded', function(up, file,info) {
        if(info.response){
            var r = jQuery.parseJSON(info.response);
            if(r.error){
                layer.msg(r.error);
            }else{
                $('#add1').attr('src',r.src+"?"+Math.random());
                $('#add1').parents('.slide-pic').find('input[name=fid]').val(r.fid);
            }
        }
    });
});
</script>
</html>
