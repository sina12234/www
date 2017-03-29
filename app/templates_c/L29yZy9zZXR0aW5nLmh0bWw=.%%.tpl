<!DOCTYPE html>
<html>
<head>
<title>首页维护 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页维护 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.minicolors.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::js('/assets_v2/css/jquery.minicolors.css'); ?>">
</head>
<body >
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/org.main.menu.setting"); ?>
            <div class="right-main col-sm-15 col-md-16">
                <div class="content main-setting">
                    <!-- 主导航设置 -->
                    <?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
                    <div class="item main-style" id="style">
                        <h5 class="thead">主题皮肤设置</h5>
                        <ul class="tbody">
                            <li class="style-item">
                                <div class="blue-bg bg">
                                    <i class="affirm <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin==4){; ?>show<?php }; ?>" data-cid="blue"></i>
                                </div>
                                呼吸蓝
                            </li>
                            <li class="style-item c-fl">
                                <div class="orange-bg bg">
                                    <i class="affirm <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin==1){; ?>show<?php }; ?>" data-cid="orange"></i>
                                </div>
                                活力橙
                            </li>
                            <li class="style-item c-fl">
                                <div class="green-bg bg">
                                     <i class="affirm <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin==2){; ?>show<?php }; ?>" data-cid="green"></i>
                                </div>
                                青春绿
                            </li>
                            <li class="style-item c-fl">
                                <div class="skyblue-bg bg">
                                     <i class="affirm <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->site_skin==3){; ?>show<?php }; ?>" data-cid="skyblue"></i>
                                </div>
                                夜空蓝
                            </li>
                        </ul>
                    </div>
                   <div class="item main-nav-set">
                       <h5 class="thead"><?php echo tpl_modifier_tr('主导航设置','org'); ?><span class="tip">（最多可设置3个）</span><a class="c-fr edit" id="mainNavSetEdit" href="javascript:void(0)" ><i class="edit-icon"></i></a></h5>
                       <div class="tbody fs14 clearfix">
                           <ul>
                                <li class="col-xs-10 col-sm-6 col-md-4">全部课程</li>
                                <?php echo tpl_function_part("/org.custom.ShowNav"); ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["ret"]->items)){; ?>
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["ret"]->items as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                <li class="col-xs-10 col-sm-6 col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></li>
                                    <?php }; ?>
                                <?php }; ?>
                               <li class="col-xs-10 col-sm-6 col-md-4">我的学习</li>
                           </ul>
                       </div>
                   </div>
                   <?php }; ?>
                    <!-- 主导航设置 end -->
                    <!-- 课程热度 -->
                   <div class="item course-heat">
                       <h5 class="thead"><?php echo tpl_modifier_tr('课程热度','org'); ?></h5>
                       <div class="tbody fs14 clearfix">
                           <form>
                               <label class="col-xs-20 col-md-5"><input type="radio" class="org-main-updatehot" name="courseHeat" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==1){; ?>checked<?php }; ?> id="a1" value="1"><?php echo tpl_modifier_tr('报名人数','org'); ?></label>
                               <label class="col-xs-20 col-md-5"><input type="radio" class="org-main-updatehot" name="courseHeat" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==2){; ?>checked<?php }; ?> id="a2" value="2"><?php echo tpl_modifier_tr('课程剩余报名人数','org'); ?></label>
                               <label class="col-xs-20 col-md-5"><input type="radio" class="org-main-updatehot" name="courseHeat" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==3){; ?>checked<?php }; ?> id="a3" value="3"><?php echo tpl_modifier_tr('课程播放量','org'); ?></label>
                               <label class="col-xs-20 col-md-5"><input type="radio" class="org-main-updatehot" name="courseHeat" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->hot_type==4){; ?>checked<?php }; ?> id="a4" value="4"><?php echo tpl_modifier_tr('不显示','org'); ?></label>
                           </form>
                       </div>
                   </div>
                   <!-- 课程热度 end -->
                   <!-- 直播列表 -->
                   <div class="item">
                       <h5 class="thead">直播列表</h5>
                       <div class="tbody fs14">
                           <form>
                               <label class="col-xs-10 col-md-4"><input type="radio" class="org-main-updatehot" name="liveList" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show==1){; ?>checked<?php }; ?> name="li_show" value="1">显示</label>
                               <label class="col-xs-10 col-md-4"><input type="radio" class="org-main-updatehot" name="liveList" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->living_show==2){; ?>checked<?php }; ?> name="li_show" value="2">隐藏</label>
                           </form>
                       </div>
                   </div>
                   <!-- 直播列表 end -->
                   <!-- 标签展示 -->
                   <div class="item label-display" id="labelDisplay">
                       <h5 class="thead"><?php echo tpl_modifier_tr('标签展示','org'); ?><a class="c-fr" id="labelDisplayEdit" href="javascript:void(0)" ><i class="edit-icon"></i></a></h5>
                       <div class="tbody">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["selected"])){; ?>
                           <ul id="labelDisplayList">
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["selected"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                               <li class="label-display-item bubble-left"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></li>
                               <?php }; ?>
                           </ul>
                           <p class="no-label" style="display:none">您还没有添加标签</p>
                           <?php }else{; ?>
                           <ul id="labelDisplayList" style="display:none"></ul>
                           <p class="no-label">您还没有添加标签</p>
                           <?php }; ?>
                       </div>
                       <div class="tfoot p15" style="display:none">
                           <div class="label-edit clearfix">
                               <div class="label-edit-list pl10 pb10" id="labelEditList">
                                   <!-- <span class="label-display-item bubble-left"><span class="label-edit-content">标签1</span><a class="label-edit-del" href="javascript:void(0)" title="删除">×</a></span> -->
                                   <span class="input-tagator-o pt10"><input class="input-tagator" name="inputTagator" id="inputTagator" type="text" placeholder="点击空白输入标签">
                               </div>
                               <div id="labelWillAdd" style="display:none"></div>
                               <div class="label-edit-button mt10 mr10">
                                   <button class="btn mr5" id="labelDisplayEditSubmit">确定</button>
                                   <button class="gray-btn" id="labelDisplayEditCancel">取消</button>
                               </div>
                           </div>
                           <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"]) or !empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                           <div class="label-history" id="labelHistory">
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"])){; ?>
                               <div class="label-recent">
                                   <span>最近使用：</span>
                                   <?php foreach(SlightPHP\Tpl::$_tpl_vars["often"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                   <span class="label-display-item bubble-left"><b>+</b><span class="label-edit-content"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span></span>
                                   <?php }; ?>
                               </div>
                               <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                               <div class="label-usual">
                                   <span>常用标签：</span>
                                   <?php foreach(SlightPHP\Tpl::$_tpl_vars["lasted"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                   <span class="label-display-item bubble-left"><b>+</b><span class="label-edit-content"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span></span>
                                   <?php }; ?>
                               </div>
                               <?php }; ?>
                           </div>
                           <?php }; ?>
                           <p class="label-tip mt10">每个标签限10个字符，输入标签名称后点击回车，最多添加6个标签</p>
                       </div>
                   </div>
                   <!-- 标签展示 end -->
                   <!-- 首页幻灯片 -->
                   <div class="item home-slide" id="homeSlide">
                       <h5 class="thead"><?php echo tpl_modifier_tr('首页幻灯片','org'); ?><a class="c-fr edit" id="homeSlideEdit" href="<?php echo utility_cdn::js('/assets_v2/img/banner.zip'); ?>" ><i class="btn-edit"></i>幻灯片模板下载</a></h5>
                       <div class="tbody">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slideList"])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["slideList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["slide"]){; ?>
                           <div class="menu-item">
                                <form method="post" autocomplete="off" >
                                <input type="password" style="display:none;">
                               <h6 class="menu-thead">
                                    <span class="menu-head-text">菜单项1</span>
                                    <span class="tip">（尺寸支持890*360，支持jpg、gif、png，最大800K）</span>
                                    <?php if(count(SlightPHP\Tpl::$_tpl_vars["slideList"])>1){; ?>
                                    <a class="c-fr home-slide-del" href="javascript:void(0)" sid="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>"><i class="del-icon"></i></a>
                                    <?php }; ?>
                                    <a class="c-fr home-slide-edit mr10" href="javascript:void(0)"><i class="edit-icon"></i></a>
                               </h6>
                               <div class="menu-tbody clearfix">
                                   <div class="upload-area" style="background-color:<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>;">
                                        <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url); ?><?php }else{; ?>/assets_v2/img/img-sc.jpg<?php }; ?>" class="streak">
                                        <button class="gray-btn change-img" id="edit_<?php echo SlightPHP\Tpl::$_tpl_vars["sk"]; ?>" sid="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>">更换图片</button>
                                        <input type="hidden" name="fid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->slide_url; ?>">
                                   </div>
                                   <div class="menu-form"><label class="c-fl"><?php echo tpl_modifier_tr('链接','org'); ?>：</label><input class="menu-addr col-xs-18 col-sm-13" type="text" readonly name="slide_link" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->slide_link; ?>"></div>
                                    <div class="mt5 col-sm-5 col-xs-20 icolor-select">背景颜色：<input class="icolor" id="colorSelector<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>" data-control="hue" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>"><input type="hidden" class="icolor-input" name="color_rgb" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->rgb; ?>" ></div>
                                    <input type="hidden" name="sid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["slide"]->pk_slide; ?>">
                               </div>
                               <div class="menu-tfoot pl20 col-sm-offset-2">
                                   <button class="btn menu-submit"><?php echo tpl_modifier_tr('保存','org'); ?></button>
                                   <button class="ml15 gray-btn menu-cancel"><?php echo tpl_modifier_tr('取消','org'); ?></button>
                               </div>
                               </form>
                           </div>
                           <?php }; ?>
                            <?php }; ?>
                            <div id="homeSlideModule" class="menu-item menu-module" style='display:none'>
                                <form method="post" autocomplete="off" >
                                <input type="password" style="display:none;">
                               <h6 class="menu-thead">
                                    <span class="menu-head-text">菜单项1</span>
                                    <span class="tip">（<?php echo tpl_modifier_tr('尺寸支持890*360，支持jpg、gif、png，最大800K','org'); ?>）</span>
                               </h6>
                               <div class="menu-tbody clearfix">
                                   <div class="upload-area">
                                     <img src="<?php echo utility_cdn::img('/assets_v2/img/img-sc.jpg'); ?>">
                                     <input type="hidden" name="fid" value="">
                                     <button class="gray-btn change-img" id="imgUploadArea">+添加图片</button>
                                   </div>
                                   <div class="menu-form"><label class="c-fl">地址：</label><input class="menu-addr col-xs-18 col-sm-13" type="text" name="slide_link" placeHolder="http://www.gn100.com/"></div>
                                    <div class="mt5 col-sm-5 col-xs-20">背景颜色：<input class="icolor" id="colorSelector-add" value="#ffffff" data-control="hue"><input class="icolor-input" type="hidden" name="color_rgb" value="#ffffff"></div>
                                    <input type="hidden" name="act" value="1">
                               </div>
                               <div class="menu-tfoot pl20 col-sm-offset-2" style="display:block">
                                   <button class="btn menu-submit" id="homeSlideSubmit"><?php echo tpl_modifier_tr('确定','org'); ?></button>
                                   <button class="ml15 gray-btn menu-cancel" id="homeSlideCancel"><?php echo tpl_modifier_tr('取消','org'); ?></button>
                               </div>
                               <input type='hidden' name='noticeToken' value='<?php echo SlightPHP\Tpl::$_tpl_vars["noticeToken"]; ?>'>
                               </form>
                           </div>
                       </div>
                       <?php if(count(SlightPHP\Tpl::$_tpl_vars["slideList"])<5){; ?>
                       <div class="tfoot tac">
                           <button class="menu-add gray-btn" id="AddHomeSlide">+<?php echo tpl_modifier_tr('继续添加幻灯片','org'); ?></button>
                       </div>
                       <?php }; ?>
                   </div>
                   <!-- 首页幻灯片 end -->
                   <!-- 首页快速导航 -->
                   <?php if(!SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
                   <div class="item home-quick-nav" id="homeQuickNav">
                       <h5 class="thead">首页快速导航<span class="c-fr ml15 fs12 hidden-xs">（至少添加一个菜单项才可开启快捷导航）</span>
                            <form class="c-fr fs12">
                               <label><input type="radio" class="org-main-updatehot" name="homeQuickNav" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav==1){; ?>checked<?php }; ?> value="1">显示</label>
                               <label><input class="ml5 org-main-updatehot" type="radio" name="homeQuickNav" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav)&&SlightPHP\Tpl::$_tpl_vars["hot_type"]->is_nav==0){; ?>checked<?php }; ?> value="0">隐藏</label>
                            </form>
                       </h5>
                       <div class="tbody">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgNav"])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["orgNav"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <div class="menu-item" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>">
                                <h6 class="menu-thead col-md-16"><span class="menu-head-text">菜单项<?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?></span>
                                     <a class="c-fr home-quickNav-del" href="script:void" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>"><i class="del-icon"></i></a>
                                     <a class="c-fr home-quickNav-edit mr5" href="javascript:void(0)" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_nav_id; ?>"><i class="edit-icon"></i></a>
                                </h6>
                                <div class="menu-tbody clear">
                                    <div class="menu-form clearfix"><label class="c-fl">文字：</label><input type="text" class="col-xs-20 col-sm-14" disabled value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->nav_name; ?>" name="e_name"></div>
                                    <div class="menu-form clearfix"><label class="c-fl"><?php echo tpl_modifier_tr('地址','org'); ?>：</label><input type="text" class="col-xs-20 col-sm-14" disabled value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>" name="e_url"></div>
                                </div>
                                <div class="menu-tfoot pl20 col-sm-offset-2">
                                    <button class="btn menu-submit">确定</button>
                                    <button class="ml15 gray-btn menu-cancel">取消</button>
                                </div>
                            </div>
                            <?php }; ?>
                            <?php }; ?>
                            <div class="menu-item menu-module" id="homeQuickNavModule" style="display:none">
                                <h6 class="menu-thead col-md-16"><span class="menu-head-text">菜单项1</span></h6>
                                <div class="menu-tbody clear">
                                    <div class="menu-form clearfix"><label class="c-fl">文字：</label><input type="text" name="nav_name" class="col-xs-20 col-sm-14" placeHolder="导航文字"></div>
                                    <div class="menu-form clearfix"><label class="c-fl"><?php echo tpl_modifier_tr('地址','org'); ?>：</label><input type="text" name="nav_url" class="col-xs-20 col-sm-14" placeHolder="http://www.gn100.com/"></div>
                                </div>
                                <div class="menu-tfoot pl20 col-sm-offset-2">
                                    <button class="btn menu-submit">确定</button>
                                    <button class="ml15 gray-btn menu-cancel">取消</button>
                                </div>
                            </div>
                       </div>
                       <?php if(count(SlightPHP\Tpl::$_tpl_vars["orgNav"])<6){; ?>
                       <div class="tfoot tac">
                           <button class="menu-add gray-btn" id="homeQuickNavAdd">+添加菜单选项</button>
                       </div>
                       <?php }; ?>
                   </div>
                   <?php }; ?>
                   <!-- 首页快速导航 end -->
                   <!-- 教师推荐 -->
                   <div class="item teacher-recommended" id="teacherRecommended">
                        <h5 class="thead"><?php echo tpl_modifier_tr('教师推荐','org'); ?></h5>
                        <div class="tbody">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["starTeacher"])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["starTeacher"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <div class='col-sm-2 col-xs-2 teacher-item col-md-3'>
                                <p class="mourse-opacity recomm-teacher" data-cid='0'> <span class="top-teacher"><?php echo tpl_modifier_tr('点击修改','org'); ?></span></p>
                                <img src="<?php echo utility_cdn::img('/assets_v2/img/hove-end.png'); ?>" class="del-teacher" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_id; ?>">
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
                        </div>
                   </div>
                   <!-- 教师推荐 end -->
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script>
$(function(){
    // 风格设置
    var style=$('#style');
    style.on('click','li',function(){
        $(this).find('.affirm').addClass('show');
		var skin_type = $(this).children().find("i").attr("data-cid");
        $(this).siblings().find('.affirm').removeClass('show');
		 $.post("/org.main.updatehot",{ 'skin':skin_type},function(r){
            if(r.error){
                layer.msg(r.error);
                return false;
            }else{
				location.reload();
                layer.msg('修改成功');
            }
        },"json");
    })
//------ 主导航设置
    $('#mainNavSetEdit').on('click',function () {
        layer.open({
            type: 2,
            title: ['<?php echo tpl_modifier_tr('主导航设置','org'); ?>'],
            shadeClose: true,
            shade: 0.8,
            area: ['535px', '335px'],
            content: '<?php echo "/org.custom.CusNav"; ?>',
            closeBtn:2
        });
    });
//------ 课程热度展示,直播列表显示,快捷导航的展示隐藏
    $('.org-main-updatehot').change(function(){
        var hot_type =$("input[name='courseHeat']:checked").val();
        var li_show =$("input[name='liveList']:checked").val();
        var nav_name =$("input[name='homeQuickNav']:checked").val();
        $.post("/org.main.updatehot",{ 'hot_type':hot_type,'li_show':li_show,'nav_name':nav_name },function(r){
            if(r.error){
                layer.msg(r.error);
                return false;
            }else{
                layer.msg('修改成功');
            }
        },"json");
    });
//------ 标签展示
    $('#labelDisplayEdit').click(function(event) {
        $('#labelDisplay .tbody').hide();
        $('#labelDisplay .tfoot').show();
        var nodes = [],nodesValue = [];
        $('#labelDisplayList li').each(function(){
            var content = $(this).text();
            nodes.push($(labelModule(content)));
            nodesValue.push($(labelValueModule(content)));
        });
        $('#inputTagator').parent().siblings().remove().end().before(nodes);
        $('#labelWillAdd').append(nodesValue);
    });
    //首页快速设置
    $("#homeQuickNavAdd").click(function(){
        $("#homeQuickNavModule").show();
    });
    //enter 增加
    $('#inputTagator').keyup(function(event) {
        var e = e || event,
        keycode = e.which || e.keyCode;
        var content = $.trim($(this).val());
        if (keycode == 32 || keycode == 13 || (event.shiftKey && event.keyCode == 13)) {
            addLabel(content);
        }
        if (keycode==8) {
            var items = $('#labelEditList .label-display-item');
            if (content == '' && items.length > 0){
                items.last().remove();
            }
        }
    });
    $('#labelEditList').on('click', '.label-edit-del', function(event) {
        event.preventDefault();
        var item = $(this).parent();
        var content = item.find('.label-edit-content').text();
        delLabel(item,content);
    });
    //提交标签修改
    $('#labelDisplayEditSubmit').click(function(){
        var data = $('#labelEditList .label-display-item').map(function() {
            return $(this).find('.label-edit-content').text();
        }).get().join(",");

        $.post("/org.main.addOrgTagAjax",{ tagName:data },function(r){
            if(r.code==0){
                layer.msg("<?php echo tpl_modifier_tr('修改成功','org'); ?>",{ icon: 1 },function(){
                    location.reload();
                });
            }else{
                layer.msg(r.message);
            }
        },"json");
    });
    //取消标签修改
    $('#labelDisplayEditCancel').click(function(){
        $('#labelWillAdd').empty();
        $('#labelEditList .label-display-item').empty();
        $('#labelDisplay .tbody').show();
        $('#labelDisplay .tfoot').hide();
    });
    // 点击预选项中添加
    $('#labelHistory').on('click', '.label-display-item', function(event) {
        event.preventDefault();
        var content = $.trim($(this).find('.label-edit-content').text());
        addLabel(content);
    });
    //显示、隐藏标签输入
    function showInput(){
        $('#inputTagator').val('');
        if($('#labelEditList .label-display-item').length >= 6 ) {
            $('#inputTagator').hide();
        }else{
            $('#inputTagator').show();
        }
    }
    // 增加标签
    function addLabel(content){
        if($('#labelEditList .label-display-item').length >= 6) {
            $('#inputTagator').hide();
            layer.msg('只能添加六项');
            return false;
        }
        if(content==''){
            layer.msg('标签不能为空');
            return false;
        }
        if(countStr(content)>10){
            layer.msg('不能超过10个字符');
            return false;
        }
        if(hasLabel(content)){
            layer.msg('这个标签已经有了');
        }else{
            var newLabel = $(labelModule(content));
            $('#inputTagator').parent().before(newLabel);
            $("#labelWillAdd").append(labelValueModule(content));
            addLabelGray(content);
        }
        showInput();
    }
    //删除标签
    function delLabel(obj,content){
        //删除本身
        obj.remove();
        //删除隐藏域的提交
        $("input[name='tagName[]").each(function(){
            if(content==$(this).val()){
                $(this).remove();
            }
        });
        //取消置灰
        cancelLabelGray(content);
        //显隐输入框
        showInput();
    }
    // 标签编辑模板
    function labelModule(str){
        return '<span class="label-display-item bubble-left"><span class="label-edit-content">'+str+'</span><a class="label-edit-del" href="javascript:void(0)" title="删除">×</a></span>';
    }
    // 隐藏域模板
    function labelValueModule(str){
        return "<input type='hidden' name='tagName[]' value='"+str+"'/>";
    }
    // 判断是否重复
    function hasLabel(str){
        var now = $('#labelEditList .label-display-item').map(function(i,v){
            return $.trim($(v).find('.label-edit-content').text());
        });
        return $.inArray(str,now) == -1 ? false : true;
    }
    // 增加置灰
    function addLabelGray(str){
        $('#labelEditList .label-display-item').each(function(){
            if($(this).text()==str){
                $(this).addClass('active');
            }
        })
    }
    // 增加置灰
    function cancelLabelGray(str){
        $('#labelEditList .label-display-item').each(function(){
            if($(this).text()==str){
                $(this).removeClass('active');
            }
        })
    }
//------ 首页幻灯片

    var homeSlideModule = $('#homeSlideModule');
    var homeSlideAddBtn = $('#AddHomeSlide');
    // 添加新幻灯片
    homeSlideAddBtn.click(function(){
        homeSlideModule.show().siblings('.menu-item').each(function(index, el) {
          $(this).find('.menu-cancel').trigger('click');
        });
    });
    // 编辑
    var _Lock=0;
    $('#homeSlide')
    .on('click', '.home-slide-edit', function(event) {
        var item = $(this).closest('.menu-item');
        item.addClass('active').siblings('.menu-item').removeClass('active');
        var input = item.find(".menu-addr");
        input.removeAttr('readonly').attr('data-text',input.val());
        homeSlideModule.hide();
    })
    // 取消
    .on('click', '.menu-cancel', function(event) {
        event.preventDefault();
        var item = $(this).closest('.menu-item');
        if(item.hasClass('menu-module')){
          homeSlideModule.hide();
        }else if(item.hasClass('active')){
          item.removeClass('active');
          var input = item.find(".menu-addr");
          input.attr('readonly','readonly').val(input.attr('data-text'));
        }
    })
    // 提交
    .on('click', '.menu-submit', function(event) {
      event.preventDefault();
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
    })
    .on('click', '.home-slide-del', function(event) {
      event.preventDefault();
      var sid = $(this).attr("sid");
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
            $(this).parents('.menu-item').remove();
        }
    });
    // 上传文件
    // 颜色选择器
    $('.icolor').each(function(){
      $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        change: function(hex, opacity) {
          var log;
          log = hex ? hex : 'transparent';
          if( opacity ){
               log += ', ' + opacity;
          }
          $(this).parents('.menu-item').find('.upload-area').css('background-color',log);
          $(this).parents('.menu-item').find('.icolor-input').val(log);
        },
        theme: 'default'
      });
    });
    if(isPro == 1){
      $('#tab-app').show();
    }
//-------首页快速导航
    // 新增
    $('#homeQuickNavAdd').click(function(){
        $('#homeQuickNavModule').show().siblings('.menu-item').each(function(index, el) {
          $(this).find('.menu-cancel').trigger('click');
        });
    });
    $('#homeQuickNav')
    // 编辑
    .on('click', '.home-quickNav-edit', function(event) {
      event.preventDefault();
      var item = $(this).closest('.menu-item');
      item.addClass('active').siblings('.menu-item').each(function(index, el) {
        $(this).find('.menu-cancel').trigger('click');
      });

      item.find('input').each(function(index, el) {
        $(this).prop('disabled', false);
        $(this).attr('data-text',$(this).val());
      });
    })
    // 删除
    .on('click', '.home-quickNav-del', function(event) {
      event.preventDefault();
      var nid = $(this).attr("data-nid");
      layer.confirm("<?php echo tpl_modifier_tr('确定删除此菜单项吗','org'); ?>?",
             {
                      title:"<?php echo tpl_modifier_tr('删除信息','org'); ?>",
                      btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
                      shade: false //不显示遮罩
              },
              function(){
                      $.post("/org.main.delOrgOfNav",{ nid:nid},function(r){
                        if(r.code<0){
                          layer.msg(r.msg);
                        }else{
                          layer.msg("<?php echo tpl_modifier_tr('删除成功','org'); ?>",{ icon: 1 },function(){
                            location.reload();
                          });
                        }
                      },"json");
           }, function(){ });
    })
    // 提交
    .on('click', '.menu-submit', function(event) {
      event.preventDefault();
      var item = $(this).closest('.menu-item');
      if(item.hasClass('menu-module')) {
        // 提交新增
        var nav_name = $.trim(item.find("input[name='nav_name']").val());
        var nav_url = item.find("input[name='nav_url']").val();
        var nav_count = countStr(nav_name);
        if(nav_count < 4 || nav_count > 16){
          layer.msg("请输入2~8个文字");
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
      }else{
        // 提交修改
        var $upDateName = $.trim(item.find('input[name="e_name"]').val());
        var $upDateUrl = item.find('input[name="e_url"]').val();
        var $nid = item.attr('data-nid');
        var $count = countStr($upDateName);
        if($count < 4 || $count > 16){
          layer.msg("请输入2~8个文字");
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
      }

    })
    // 取消
    .on('click', '.menu-cancel', function(event) {
      event.preventDefault();
      var item = $(this).closest('.menu-item');
      if(item.hasClass('menu-module')) {
        item.hide();
      }else if(item.hasClass('active')){
          item.removeClass('active');
          item.find('input').each(function(index, el) {
              $(this).attr('disabled',true);
              $(this).val($(this).attr('data-text'));
          });
      }
    });
// 教师推荐
    $('#teacherRecommended')
    .on('click', '.recomm-teacher', function(event) {
      event.preventDefault();
      var is_star=$(this).index()+1;
      var recommend_type = $(this).attr("data-cid");
      var old_id = $(this).next().attr("tid");
      layer.open({
          type: 2,
          title: ['教师推荐'],
          shadeClose: true,
          shade: 0.6,
          area: ['600px', '50%'],
          content: '<?php echo "/org.main.iframeTeacher."; ?>'+is_star+'.'+recommend_type+'.'+old_id
      });
    })
    .on('click', '.del-teacher', function(event) {
      event.preventDefault();
      var tid=$(this).attr('tid');
        $.post("/org.main.downteacherAjax",{ 'tid':tid },function(r){
            if(r.error){
                layer.msg(r.error);
            }else{
                layer.msg('取消成功',{ icon:6 },function(){ location.reload(); });
            }
        },"json");
    });
// tools
    //计算字符串
    function countStr(str){
        return str.replace(/[\u0391-\uFFE5]/g,"aa").length;
    }
});
</script>
<script>
  // 首页幻灯片图片上传
$(document).ready(function() {
    $("#homeSlide .menu-item").each(function(i,v){
      var edit_id = $(v).hasClass('menu-module')?'imgUploadArea':"edit_"+ i;
      var edit = new plupload.Uploader({
        runtimes:'html5,flash,silverlight,html4',
        browse_button: edit_id,
        url: '/file.main.uploadOrgSlide',
        filters: {
          mime_types : [{ title : "Image files", extensions : "jpg,gif,png" }],
          max_file_size:"800kb"
        },
        multi_selection:false
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
            $('#'+edit_id).parent().find('img').attr('src',r.src+"?"+Math.random());
            $('#'+edit_id).parent().find('input[name=fid]').val(r.fid);
          }
        }
      });
    });
});
</script>
</html>
