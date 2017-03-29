<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> - 主讲课程 - 云课 - 专业的在线直播教育学习平台 - 云课网 </title>
<meta name="title" content="云课 - 专业的在线直播教学习平台">
<meta name="keywords" content="云课 - 云课堂 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/index/teacherblog/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/course");</script>
<article class="container mt40 mb40">
    <!--左侧开始-->
    <div class="col-md-5  th_m_l mt20">
        <div class="c-fl m_l_1">
            <span class="col-sm-6 col-xs-6">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->student_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->student_count; ?><?php }; ?></strong>
                <p class="fs14">学生数</p>
            </span>
            <span class="col-sm-7 bl_r col-xs-6">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->course_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->course_count; ?><?php }; ?></strong>
                <p class="fs14">课程数</p>
            </span>
            <span class="col-sm-7 col-xs-6">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->totaltime)){; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["info"]->totaltime/3600); ?><?php }; ?></strong>
                <p class="fs14">教学时长</p>
            </span>
        </div>
        <article class="c-fl mt20">
            <h2 class="th_l_h2"><i></i>基础资料</h2>
            <div class="mt20 m_l_2">
                <dl>
                    <dt>
                        <span style="margin-right: 60px;">
                            <i class="icon_1 icon icon_pic"></i>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->province)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->province; ?><?php }; ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->city)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->city; ?><?php }; ?>
                        </span>
                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->years)){; ?>
                            <span ><i class="icon_2 icon icon_pic"></i><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->years; ?>年教龄</span>
                        <?php }; ?>
                    </dt>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->college)){; ?>
                        <dd><i class="icon_3 icon icon_pic"></i>毕业于 <?php echo SlightPHP\Tpl::$_tpl_vars["info"]->college; ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->diploma)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->diploma; ?><?php }; ?></dd>
                    <?php }; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->grade)){; ?>
                        <dd><i class="icon_4 icon icon_pic"></i>教学阶段：<?php echo SlightPHP\Tpl::$_tpl_vars["info"]->grade; ?></dd>
                    <?php }; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->subject)){; ?>
                        <dd><i class="icon_5 icon icon_pic"></i>擅长学科：<?php echo SlightPHP\Tpl::$_tpl_vars["info"]->subject; ?></dd>
                    <?php }; ?>
                </dl>
                <h3 class=""><i class="icon_6 icon icon_pic"></i>简介</h3>
                <div class="b_int">
                    <p class="mt10 intro" >
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->desc; ?><?php }else{; ?>这个老师有一点懒，还没写下什么呢~<?php }; ?>
                    </p>
                    <a href="javascript:;" class="details">【详细】</a>
                </div>
            </div>
        </article>
        <?php if(SlightPHP\Tpl::$_tpl_vars["info"]->avg_score){; ?>
        <article class="mt20 c-fl">
            <h2 class="th_l_h2"><i></i>学生评价</h2>
            <div class="statistics-cirl-solid-third03 th_eval tec col-md-20 mt40">
                <!--通过赋值修改font里的百分比-->
                <font style="display: none"> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->avg_score)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score*2; ?><?php }else{; ?>00<?php }; ?>%</font>
                <span class="score"><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score/10; ?>分</span>
                <div class="eval_pl col-md-20">
                    <span class="col-md-10" style="border-right:1px solid #e9e9e9;">综合得分<em><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score/10; ?></em>分</span>
                    <span class="col-md-10">评价数<em><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->comment; ?></em>条</span>
                </div>
            </div>
        </article>
        <?php }; ?>
    </div>
    <!--右侧开始-->
    <div class="col-md-15">
        <div class="courselist col-md-20 col-sm-20 pd0">
            <!-- <h2 class="th_l_h2"><i></i>主讲课程
                 <a href="/index/teacherblog/course/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" class="fr">更多课程>></a>
            </h2>-->
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
            <ul class="techear-courselist fs14 mr_course ">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                <li class="col-md-20">
                    <div class="c-pic col-md-5 col-xs-7">
                        <div class="pic">
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>"><img class="imgPic" src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>">
								<?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2){; ?>
								<span class="taped-class fs12">录播</span>
                                <span class="linelookat-icon hidden-lg hidden-md hidden-sm hidden-xs"></span>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3)){; ?>
								<span class="lineclass-icon fs12">线下</span>
                                <span class="linelookat-icon hidden-lg hidden-md hidden-sm hidden-xs"></span>
                                <?php }; ?>
							</a>
                        </div>
                    </div>
                    <div class="col-md-8 col-xs-8  course_tit pdr0">
                        <p class="item fs14 new-item ">
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?></a>
                        </p>
                        <p class="item cDarkgray hidden-xs">
                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['grade']; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['major']; ?> | 共<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['section_count']; ?>章节 <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['sectionName']; ?>
                            <?php if((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 1 or SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3) && SlightPHP\Tpl::$_tpl_vars["v"]['status'] == 3){; ?>
                                已完结
                            <?php }; ?>
                        </p>
                        <p class="cDarkgray">
                            <span class="client-logo-name"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['orgName']; ?></span>
                        </p>
                    </div>
                    <div class="col-md-2 col-xs-2 course_buy">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['fee_type'] == '免费'){; ?>
                        <p class="item fs14 mt38 cGreen">免费</p>
                        <?php }else{; ?>
                        <p class="item fs14 mt38 cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_type']; ?></p>
                        <?php }; ?>
                    </div>
                    <div class="rate hidden-xs col-md-5 course_enr">
                        <?php if((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 1 or SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3) && SlightPHP\Tpl::$_tpl_vars["v"]['status'] != 1){; ?>
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>">
                                <p class="item cDarkgray fs12 course_ab2 mt38 cDarkgray fr ">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['score']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>分 | <?php }; ?><?php if(SlightPHP\Tpl::$_tpl_vars["v"]['comment']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?>个评论<?php }; ?>
                                </p>
                            </a>
                        <?php }; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2){; ?>
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>">
                                <p class="item cDarkgray fs12 course_ab2 mt38 cDarkgray fr ">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['score']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>分 | <?php }; ?><?php if(SlightPHP\Tpl::$_tpl_vars["v"]['comment']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?>个评论<?php }; ?>
                                </p>
                            </a>
                        <?php }; ?>

                        <?php if((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 1 or SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3) && SlightPHP\Tpl::$_tpl_vars["v"]['status'] == 1){; ?>
                            <p class="course_ab2 cDarkgray fr mt35"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['start_time']; ?> 开课</p>
                        <?php }; ?>
                    </div>
                </li>
                <?php }; ?>
            </ul>
            <?php /* tpl_modifier_SlightPHP\Tpl::$_tpl_vars["info"] function not exists! */; ?>
            <!--既有上架课又有下架课-->
            <div class="col-md-6 col-md-offset-6 tec pd0">
                <p class="mt10 c_a3 fs14"  >部分课程已下架没有展示</p>
            </div>
            <?php }elseif((SlightPHP\Tpl::$_tpl_vars["info"]->course_off_count && SlightPHP\Tpl::$_tpl_vars["info"]->course_on_count < 1)){; ?>
            <!--只有下架课-->
            <div class="col-md-6 col-md-offset-6 tec pd0">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
                <p class="mt10 c_a3 fs14"  >部分课程已下架没有展示，去别处看看吧</p>
            </div>
            <?php }else{; ?>
            <!--没有课程时状态-->
            <div class="col-md-7 col-md-offset-6 tec">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
                <p class="mt10 c_a3 fs16"  >老师还没有课程，去别处看看吧</p>
            </div>
            <?php }; ?>
        </div>
    </div>
</article>
<!-- /content -->
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.raphael.js'); ?>" type="text/javascript"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.cirls.js'); ?>" type="text/javascript"></script>
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>

</html>
