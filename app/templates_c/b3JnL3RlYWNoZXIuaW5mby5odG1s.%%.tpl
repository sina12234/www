<!DOCTYPE html>
<html>
<head>
<title>教师详情 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 查看详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;">
    <?php echo tpl_function_part("/site.main.nav"); ?>
    <section>
        <div class='container mt40'>
		<div class='row'>
            <?php echo tpl_function_part("/org.main.menu.teacher"); ?>
            <div class="right-main col-sm-9 col-md-16">
                <div class="content">
                    <div class="manage-path fs14"><a href="/org.teacher.list"><?php echo tpl_modifier_tr('返回','org'); ?></a>><span class="cGray"><?php echo tpl_modifier_tr('查看详情','org'); ?></span></div>
                    <div class="t-data">
                        <div class="face col-sm-3"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>" alt=""></div>
                        <div class="info col-sm-9 fs14 col-md-16">
                            <p class="account col-sm-12"><span class="label"><?php echo tpl_modifier_tr('登录账号','org'); ?>：</span><span class="label-for"><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->mobile; ?></span></p>
                            <ul class="col-sm-5 col-md-8">
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('教师姓名','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfo"]->profile->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->profile->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->name; ?><?php }; ?></div>
                                </li>
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('头衔','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->title; ?><?php }else{; ?>无<?php }; ?></div>
                                </li>
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('科目','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->subject)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->subject; ?><?php }else{; ?>未设置<?php }; ?></div>
                                </li>
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('教龄','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->years)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->years; ?><?php }else{; ?>0<?php }; ?></div>
                                </li>
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('毕业于','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->college)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacherInfo"]->college; ?><?php }; ?></div>
                                </li>
                            </ul>
                            <ul class="col-sm-6 col-md-8">
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('上课课时','org'); ?>：</div>
                                    <div class="label-for">已上<?php echo count(SlightPHP\Tpl::$_tpl_vars["finishPlan"]); ?>节 未上<?php echo count(SlightPHP\Tpl::$_tpl_vars["normalPlan"]); ?>节</div>
                                </li>
                               <?php /*<li>
                                    <div class="label">出勤率：</div>
                                    <div class="label-for">80%</div>
                                </li>*/?>
                                <li>
                                    <div class="label"><?php echo tpl_modifier_tr('学生','org'); ?>：</div>
                                    <div class="label-for"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["countStudent"]->num)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["countStudent"]->num; ?><?php }else{; ?>0<?php }; ?></div>
                                </li>
                                <?php /*<li>
                                    <div class="label">学生评价：</div>
                                    <div class="label-for">
                                        <div class="star"><span class="sel"></span><span class="sel"></span><span class="sel"></span><span></span><span></span></div>
                                    </div>
                                </li>*/?>
                            </ul>
                        </div>
                        <div class="bar"><span class="t-info-icon"><?php echo tpl_modifier_tr('上课列表','org'); ?></span><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["total"])){; ?><span class="c-fr">共<?php echo SlightPHP\Tpl::$_tpl_vars["total"]; ?>个</span><?php }; ?></div>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"])){; ?>
                        <ul class="t-class-list">
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["planList"] as SlightPHP\Tpl::$_tpl_vars["plv"]){; ?>
                            <li>
                                <div class="t-course col-sm-7 col-md-10">
                                    <div class="item fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->course_name; ?></div>
                                    <div class="item cgray"><?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->class_name; ?> | <span class="cLightgray">共<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allClassPlan"][SlightPHP\Tpl::$_tpl_vars["plv"]->class_id])){; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["allClassPlan"][SlightPHP\Tpl::$_tpl_vars["plv"]->class_id]); ?><?php }else{; ?>0<?php }; ?>章 进度</span> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["nowClassPlan"][SlightPHP\Tpl::$_tpl_vars["plv"]->class_id])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->section_name; ?><?php }else{; ?>0<?php }; ?> </div>
                                    <div class="item fs14 cDarkgray"><?php echo date('m月d日',strtotime(SlightPHP\Tpl::$_tpl_vars["courses"][SlightPHP\Tpl::$_tpl_vars["plv"]->course_id]->start_time)); ?> 至 <?php echo date('m月d日',strtotime(SlightPHP\Tpl::$_tpl_vars["courses"][SlightPHP\Tpl::$_tpl_vars["plv"]->course_id]->end_time)); ?></div>
                                </div>
                                <div class="t-class col-sm-4 col-md-10">
                                    <div class="item fs14"><?php echo tpl_modifier_tr('班级情况','org'); ?></div>
                                    <div class="item fs14"><span class="num-icon"></span> <?php echo tpl_modifier_tr('学生','org'); ?>
 <?php if(SlightPHP\Tpl::$_tpl_vars["plv"]->course_type==2){; ?>                                  <?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->user_total; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["plv"]->max_user; ?><?php }; ?></div>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["plv"]->status==1){; ?>
                                    <div class="item fs14"><span class="time-icon"></span> <?php echo tpl_modifier_tr('下节课时间','org'); ?>：<?php echo date('m月d日H:i',strtotime(SlightPHP\Tpl::$_tpl_vars["plv"]->start_time)); ?></div>
                                    <?php }; ?>
                                </div>
                            <!-- 新增内容
                                <div class="col-sm-1 cy-new-t-info">

                                   <a href="javascript:;" class="cy-t-delet">踢出</a>
                                   <a href="javascript:;" class="cy-t-class">调班</a>
                                </div>
                            -->
                            </li>
                            <?php }; ?>
                        </ul>
                        <?php }else{; ?>
                        <div class="my-collect-no-class c-fl col-lg-offset-0 col-xs-offset-0 col-sm-offset-0 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                            <img class="mt40" src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="">
                            <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您还没有直播的课程哦','LearningCenter'); ?>~! <?php echo tpl_modifier_tr('快去','LearningCenter'); ?><a href="/"><?php echo tpl_modifier_tr('首页','LearningCenter'); ?></a><?php echo tpl_modifier_tr('看看吧','LearningCenter'); ?></p>
                        </div>
                        <div class="page-list" id="pagepage"></div>
                        <?php }; ?>
                    </div>
                </div>
            </div>
            <div class='clear'></div>
        </div>
		</div>
    </section>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript">
$(function  () {
    page("pagepage",'<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>',<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
})
</script>
</html>
