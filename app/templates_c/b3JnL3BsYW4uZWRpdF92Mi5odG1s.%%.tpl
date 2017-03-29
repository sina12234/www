<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>创建章节 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/site.main.header"); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!-- tpInfo -->
<?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<section class="pb40 course-edit-wrap">
    <div class="container">
        <div class="row">
            <!-- bdy -->
            <section>
                <?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
                <div class="col-md-16 pr0">
                    <!-- 修改排课 -->
                    <div class="gn-base-ct clearfix" id="update-planEdit" style="min-height: 415px;">
                        <!--
                        <h1 class="fs14 base-title mb20">
                            <a href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>" id="class-plan-name"></a> >
                            班级排课
                        </h1>
                        -->
                        <section class="arrangement-wrap col-md-20 fs14" id="arrangement-wrap-info">
                            <div class="row">
                                <div class="grid-layout col-md-20">
                                    <h1 class="base-title clearfix"><span class="rectangle-title"></span>班级排课
                                        <div class="edit-button c-fr" id="edit-btn">
                                            <span class="edit-icon"></span>编辑
                                        </div>
                                    </h1>
                                    <div class="grid-row-fluid pt10 col-md-20">
                                        <div class="col-md-6">
                                            <label class="grid-layout-label "><em class="cRed"></em>班级名称:</label>
                                            <span class="grid-layout-span className"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->name; ?><?php }; ?></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="grid-layout-label"><em class="cRed"></em>班级座位:</label>
                                            <span class="grid-layout-span studentNum"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->max_user; ?><?php }; ?></span>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="grid-layout-label"><em class="cRed"></em>班主任:</label>
                                            <span class="grid-layout-span teacherInfo" teacherid="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->user_class_id; ?><?php }; ?>">
                                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]) && !empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->user_class_id])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->user_class_id]; ?><?php }; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 3){; ?>
                                    <div class="grid-row-fluid pt10 col-md-20">
                                        <label class="grid-layout-label col-md-2"><em class="cRed">*</em>地址:</label>
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?>
                                        <div class="grid-layout-span col-md-3 row">
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level0])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level0]; ?><?php }; ?>
                                        </div>
                                        <div class="grid-layout-span col-md-3">
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level1])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level1]; ?><?php }; ?>
                                        </div>
                                        <div class="grid-layout-span col-md-5">
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level2])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["regionInfo"][SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level2]; ?><?php }; ?>
                                        </div>
                                        <?php }; ?>
                                    </div>
                                    <?php }; ?>
                                    <div class="grid-row-fluid p10 col-md-20">
                                        <div class="grid-layout-span col-md-17 col-md-offset-2 pl20 add-full"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->address; ?><?php }; ?></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="arrangement-wrap-edit" class="col-md-20" style="display:none">
                            <div class="row">
                                <div class="grid-layout col-md-20">
                                    <h1 class="base-title clearfix"><span class="rectangle-title"></span>班级排课
                                        <!--<div class=c-fr>-->
                                            <!--<span class="btn mr10 dis" id="save-btn" data-type="1">保存</span>-->
                                            <!--<span class="blue-link" id="cancel-btn">取消</span>-->
                                        <!--</div>-->
                                    </h1>
                                    <form class="grade-con-edit clearfix">
                                        <ul class="form-horizontal fs14">
                                            <li class="form-group">
                                                <div class="col-md-7 pl25">
                                                    <label class="grid-layout-label"><em class="cRed">*</em>班级名称:</label>
                                                    <input type="text" name="className" id="className" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->name; ?><?php }else{; ?>1班<?php }; ?>"></input>
                                                </div>
                                                <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 2){; ?>
                                                <div class="col-md-7">
                                                    <label class="grid-layout-label"><em class="cRed">*</em>班级座位:</label>
                                                    <input type="text" name="studentNum" id="studentNum" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->max_user)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->max_user; ?><?php }else{; ?>50<?php }; ?>"></input>
                                                </div>
                                                <?php }; ?>
                                                <div class="col-md-6">
                                                    <label class="grid-layout-label col-md-5"><em class="cRed">*</em>班主任:</label>
                                                    <select name="teacherName" id="teacherId" class="col-md-14">
                                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["teachers"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                                        <?php if(empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?>
                                                        <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]; ?></option>
                                                        <?php }else{; ?>
                                                        <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["k"]==SlightPHP\Tpl::$_tpl_vars["classInfo"]->user_class_id){; ?>selected='selected'<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["v"]; ?></option>
                                                        <?php }; ?>
                                                        <?php }; ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 3){; ?>
                                            <li class="form-group">
                                                <label class="col-md-2 control-label"><em class="cRed">*</em>地址:</label>
                                                <div class="col-md-16 label-for pl0">
                                                    <select name="add-pro" id="add-pro" class="col-md-5 mr15">
                                                        <option><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["firstRegion"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                                        <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->region_id; ?>" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])&&SlightPHP\Tpl::$_tpl_vars["v"]->region_id==SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level0){; ?>selected='selected'<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></option>
                                                        <?php }; ?>
                                                    </select>
                                                    <select name="add-city" id="add-city" class="col-md-5 mr15" style="display:none">
                                                        <option value="0"><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                                                    </select>
                                                    <select name="add-area" id="add-area" class="col-md-5 mr15" style="display:none">
                                                        <option value="0"><?php echo tpl_modifier_tr('请选择','org'); ?></option>
                                                    </select>
                                                    <textarea name="add-full" id="add-full" class="col-md-16 mt20" placeholder="请输入地址"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->address)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->address; ?><?php }; ?></textarea>
                                                </div>
                                            </li>
                                            <?php }; ?>
                                        </ul>
                                    </form>

                                </div>
                            </div>
                        </section>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classId"])){; ?>
                        <section class="class-setting-wrap col-md-20">
                            <div class="grid-layout">
                                <h1 class="base-title"><span class="rectangle-title"></span>设置课时</h1>
                            </div>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planInfo"])){; ?>
                            <ul class="border-main-content mt10 class-lists pos-rel" id="class-lists">
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["planInfo"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                <li class="class-item p15 main" id="main0">
                                    <div class="pb10">
                                        第<span class="class-num" planid=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->order_no; ?></span>课时:
                                        <span class="class-name"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->section_name; ?></span>
                                        <span class="right">
                                            <a class="editButton mr10 blue-link" data-plan="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" href="javascript:;">编辑</a>
                                            <a class="del-btn blue-link" data-plan="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->plan_id; ?>" href="javascript:;">删除</a>
                                        </span>
                                    </div>
                                    <div class="clearfix">
                                        <div class="col-md-4">
                                            <label>讲师:</label>
                                            <span class=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["v"]->user_plan_id])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["v"]->user_plan_id]; ?><?php }; ?></span>
                                        </div>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] !=2){; ?>
                                        <div class="col-md-7">
                                            <label>时间:</label>
                                            <span class=""><?php echo date("Y-m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->start_time)); ?></span>
                                            <label>至:</label>
                                            <span class=""><?php echo date("Y-m-d H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->end_time)); ?></span>
                                        </div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 1){; ?>
                                        <div class="col-md-4">
                                            <label>直播:</label>
                                            <span class=""><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->liveType; ?></span>
                                        </div>
                                        <?php }; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 3){; ?>
                                        <div class="col-md-5">
                                            <label>视频:</label>
                                            <span class=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->videoTime)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->videoTime; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->videoType; ?><?php }; ?></span>
                                        </div>
                                        <?php }; ?>
                                    </div>
                                </li>
                                <?php }; ?>
                            </ul>
                            <?php }; ?>
                            <div class="border-main-content p10 bacGray" id="operation-wrap">
                                <a id="add-single-class" class="mr20 ml30 blue-link" href="javascript:;">
                                    <span class="tab-add mr5"></span>单个添加课时
                                </a>
                                <a id="add-batch-class" class="mr20 blue-link" href="javascript:;">
                                    <span class="tab-add mr5"></span>批量添加课时
                                </a>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["planNum"] >= 2){; ?>
                                <a id="rank-class" class="blue-link" href="javascript:;">课时拖拽排序</a>
                                <?php }; ?>
                            </div>
                        </section>
                        <?php }; ?>
                        <section class="p30 clear tac">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classId"])){; ?>
                            <div id="finish-create">
                                <a class="btn yellow-btn" href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>">返回排课列表</a>
                            </div>
                            <div id="sort-save" style="display:none">
                                <a class="btn yellow-btn mr10" id="sort-plan" href="javascript:;">保存排序</a>
                                <a class="btn gray-btn" id="sort-cancel" href="">取消排序</a>
                            </div>
                            <?php }; ?>
                            <div id="finish-class" style="display: none;">
                                <span class="btn mr10 dis" id="save-btn" data-type="1">保存</span>
                                <span class="btn gray-btn" id="cancel-btn">取消</span>
                            </div>
                        </section>

                    </div>
                    <!-- /修改排课 -->
                </div>
            </section>
            <!-- /bdy -->
        </div>
    </div>
</section>
<!-- 批量添加 -->
<section id="add-more-course" class="form-horizontal" style="display:none;">
    <div class="col-xs-20 mt20 clearfix">
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">章节名：</div>
            <textarea class="col-xs-12 more-txt-content" id="plan-add-desc" placeholder="批量添加章节，每行输入一个章节名称，章节按顺序自动排序" style="height:200px;"></textarea>
            <p class="tar col-xs-18 lGray">批量添加章节，每行输入一个章节名称，章节按顺序自动排序</p>
        </div>
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">授课老师：</div>
            <div class="divselect divselect-32 col-md-5 p0 section-class-teacher-name">
                <select id="more-teacher">
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["teachers"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]; ?></option>
                    <?php }; ?>
                </select>
            </div>
        </div>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 2){; ?>
        <div class="col-xs-20 p0" id="add-more-startTime">
            <div class="col-xs-5 fs14 tar">开课时间：</div>
            <div class="col-xs-10 p0 course-date mb10 mr10 clearfix">
                <input type="text" placeholder="开课时间" class="datetime startime-plan-course course-name col-xs-18">
                <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
            </div>
        </div>
        <div class="col-xs-20 p0" id="add-morse-styBtn">
            <div class="col-xs-5 fs14 tar">循环方式：</div>
            <div class="divselect col-xs-5 p0 mr10" id="weekType">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" data-id="0">请选择</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="1">每天</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="2">每周</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="3">自定义</a>
                    </dd>
                </dl>
            </div>
            <span class="c-fl mr10">
                <img src="/assets_v2/img/list-rl.png" id="select-more-time" style="display:none;" alt="" />
            </span>
        </div>
        <div class="col-xs-20 pl40 pb10 dropdown" id="select-addTime" style="display:none;"></div>
        <div class="col-xs-20 p0" id="add-morse-selectBtn">
            <div class="col-xs-5 fs14 tar">课程时长：</div>
            <div class="divselect col-xs-5 p0 mr10" id="select-long-type">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" data-id="0">请选择</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="1">0.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="2">1小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="3">1.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="4">2小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="5">2.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="6">3小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="7">自定义</a>
                    </dd>
                </dl>
            </div>
            <input class="col-xs-8" class="course-name hours-ipt" style="display:none;" placeholder="输入时间（分钟）" id="select-more-minute" />
        </div>
        <?php }; ?>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 1){; ?>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">直播：</div>
            <div class="divselect divselect-32 live-select col-xs-5 p0" onclick="sidedownSel(event,1,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text livetype-text" liveType="">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" liveType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" liveType="1">试看整节</a>
                    </dd>
                </dl>
            </div>
        </div>
        <?php }; ?>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 3){; ?>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">视频：</div>
            <div class="divselect divselect-32 video-select col-xs-5 p0" onclick="sidedownSel(event,2,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text videotype-text" videoTime="<<videoTrialTime>>" videoType="<<videoPublicType>>">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="1">试看整节</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="300" videoType="2">试看5分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="600" videoType="2">试看10分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="1200" videoType="2">试看20分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="-2">隐藏视频</a>
                    </dd>
                </dl>
            </div>
        </div>
        <?php }; ?>
        <div class="clearfix mb10 tac col-xs-20 mt10">
            <button class="btn" id="quicksetCourse-btn" >确认</button>
            <button class="gray-button">取消</button>
        </div>
    </div>
</section>
<!-- /批量添加 -->
<!--单次添加-->
<section id="add-single-course" class="form-horizontal" style="display:none;">
    <div class="col-xs-20 mt20 clearfix">
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14" id="plan-num" orderNo=<?php echo SlightPHP\Tpl::$_tpl_vars["planNum"]+1; ?>>第<em class="courseNum"><?php echo SlightPHP\Tpl::$_tpl_vars["planNum"]+1; ?></em>课时：</div>
            <input type="text" class="col-xs-12 single-txt-content" name="planName"></input>
        </div>
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">讲师：</div>
            <div class="divselect divselect-32 col-xs-3 p0 section-class-teacher-name">
                <select id="lecturerId" class="col-xs-20">
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["teachers"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["k"]; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]; ?></option>
                    <?php }; ?>
                </select>
            </div>
        </div>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 2){; ?>
        <div class="col-xs-20 p0" id="add-single-time">
            <div class="col-xs-5 fs14 tar">时间：</div>
            <div class="col-xs-14 p0 course-date mb10 mr10 clearfix">
                <div class="col-xs-8 p0 clearfix">
                    <input type="text" placeholder="开始时间" id="start-one-time" class="datetime starttime-plan-course p5 col-xs-17">
                    <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
                </div>
                <div class="c-fl pl15 pt5 pr15">至</div>
                <div class="col-xs-8 p0 clearfix">
                    <input type="text" placeholder="结束时间" id="end-one-time" class="datetime endtime-plan-course p5 col-xs-17">
                    <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
                </div>
            </div>
        </div>
        <?php }; ?>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] == 1){; ?>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">直播：</div>
            <div class="divselect divselect-32 live-select col-xs-5 p0"  onclick="sidedownSel(event,1,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text livetype-text" liveType="">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" liveType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" liveType="1">试看整节</a>
                    </dd>
                </dl>
            </div>
        </div>
        <?php }; ?>
        <?php if(SlightPHP\Tpl::$_tpl_vars["type"] != 3){; ?>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">视频：</div>
            <div class="divselect divselect-32 video-select col-xs-5 p0"  onclick="sidedownSel(event,2,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text videotype-text" videoTime="" videoType="">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="1">试看整节</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="300" videoType="2">试看5分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="600" videoType="2">试看10分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="1200" videoType="2">试看20分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="-2">隐藏视频</a>
                    </dd>
                </dl>
            </div>
        </div>
        <?php }; ?>
        <div class="clearfix mb10 tac col-xs-20 mt10">
            <button class="btn" id="add-plan-btn">确认</button>
            <button class="gray-button">取消</button>
        </div>
    </div>
</section>
<!--单次添加ej模板-->
<section id="add-single-course-e" class="form-horizontal" style="display:none;"></section>
<script id="add-single-course-ej" type="text/template">
    <div class="col-xs-20 mt20 clearfix">
        <input type="hidden" value= <%= data.plan_id %> name="planId"/>
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14" id="plan-num" orderNo= <%= data.order_no%> >第<em class="courseNum"><%= data.order_no %></em>课时：</div>
            <input type="text" class="col-xs-12 single-txt-content" name="planName" value="<%= data.section_name%>" ></input>
        </div>
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">讲师：</div>
            <div class="divselect divselect-32 col-md-5 p0 section-class-teacher-name">
                <select id="lecturerId">
                    <% data.teachers.forEach(function(list) { %>
                    <% if(list.selected == '1'){ %>
                    <option selected value= <%= list.teacherId %>><%= list.teacherName %></option>
                    <% }else{ %>
                    <option value= <%= list.teacherId %> ><%= list.teacherName %></option>
                    <%} })%>
                </select>
            </div>
        </div>
        <% if(Type !=2){ %>
        <div class="col-xs-20 p0" id="add-single-time">
            <div class="col-xs-5 fs14 tar">时间：</div>
            <div class="col-xs-14 p0 course-date mb10 mr10 clearfix">
                <div class="col-xs-8 p0 clearfix">
                    <input type="text" value="<%=data.start_time%>" placeholder="开始时间" id="start-one-time" class="datetime starttime-plan-course p5 col-xs-17">
                    <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
                </div>
                <div class="c-fl pl15 pt5 pr15">至</div>
                <div class="col-xs-8 p0 clearfix">
                    <input type="text" value="<%=data.end_time%>" placeholder="结束时间" id="end-one-time" class="datetime endtime-plan-course p5 col-xs-17">
                    <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
                </div>
            </div>
        </div>
        <%}%>
        <% if(Type == 1){ %>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">直播：</div>
            <div class="divselect divselect-32 live-select col-xs-5 p0"  onclick="sidedownSel(event,1,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text livetype-text" liveType=<%= data.live_public_type %> ><%= data.liveTypeName %></span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" liveType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" liveType="1">试看整节</a>
                    </dd>
                </dl>
            </div>
        </div>
        <%}%>
        <% if(Type != 3){ %>
        <div class="col-xs-20 p0">
            <div class="c-fl mt10 tar col-xs-5">视频：</div>
            <div class="divselect divselect-32 video-select col-xs-5 p0"  onclick="sidedownSel(event,2,this)">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text videotype-text" videoTime=<%= data.video_trial_time %> videoType= <%= data.video_public_type %> >
                        <%= (data.videoTrialTime ==0)?data.videoTypeName:data.videoTrialTime  %>
                    </span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="0">无试看</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="1">试看整节</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="300" videoType="2">试看5分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="600" videoType="2">试看10分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="1200" videoType="2">试看20分钟</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" videoTime="0" videoType="-2">隐藏视频</a>
                    </dd>
                </dl>
            </div>
        </div>
        <%}%>
        <div class="clearfix mb10 tac col-xs-20 mt20">
            <button class="btn" id="add-plan-btn-e" >确认</button>
            <button class="gray-button">取消</button>
        </div>
    </div>
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ie8/ejs.ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.plan_v2.js'); ?>"></script>
<script type="text/javascript">
    var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
    var classId  = <?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>;
    var Type     = <?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>;
    var level0 = level1 = level2 = 0;
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level0)){; ?>
     level0   = <?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level0; ?>;
    <?php }; ?>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level1)){; ?>
     level1   = <?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level1; ?>;
    <?php }; ?>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level2)){; ?>
     level2   = <?php echo SlightPHP\Tpl::$_tpl_vars["classInfo"]->region_level2; ?>;
    <?php }; ?>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["classInfo"])){; ?>
    $("#arrangement-wrap-info").show();
    $("#arrangement-wrap-edit").hide();
    $("#finish-class").hide();
    <?php }else{; ?>
    $("#arrangement-wrap-info").hide();
    $("#arrangement-wrap-edit").show();
    $("#finish-class").show();
    <?php }; ?>
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
