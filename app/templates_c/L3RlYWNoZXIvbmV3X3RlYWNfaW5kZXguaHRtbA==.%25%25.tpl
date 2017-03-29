<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  教师首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  云课(Yunke.com) - 专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/teacher/detail/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/entry");</script>
<article class="container pd40">
    <div class="col-md-5 th_m_l mt20">
        <div class="m_l_1 c-fl">
            <span class="col-sm-6 col-xs-7">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->student_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->student_count; ?><?php }; ?></strong>
                <p>学生数</p>
            </span>
            <span class="col-sm-7 bl_r col-xs-6">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->course_count)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->course_count; ?><?php }; ?></strong>
                <p>课程数</p>
            </span>
            <span class="col-sm-7 col-xs-7">
                <strong><?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->totaltime)){; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["info"]->totaltime/3600); ?><?php }; ?></strong>
                <p>教学时长</p>
            </span>
        </div>
        <article class="mt20 c-fl col-xs-20">
            <h2 class="th_l_h2"><i></i>基础资料</h2>
            <div class="mt20 m_l_2">
                <dl>
                    <dt>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->province)){; ?>
                        <span style="margin-right: 60px;">
                            <i class="icon_1 icon icon_pic"></i><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->province; ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->city)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->city; ?><?php }; ?>
                        </span>
                        <?php }; ?>
                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["info"]->years)){; ?>
                            <span><i class="icon_2 icon icon_pic"></i><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->years; ?>年教龄</span>
                        <?php }; ?>
                    </dt>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->college)){; ?>
                    <dd>
                        <i class="icon_3 icon icon_pic"></i>毕业于 <?php echo SlightPHP\Tpl::$_tpl_vars["info"]->college; ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->diploma)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->diploma; ?><?php }; ?>
                    </dd>
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
                    <a href="javascript:;" class="details">【展开】</a>
                </div>
            </div>
        </article>
        <article class="mt20 c-fl col-xs-20">
            <h2 class="th_l_h2"><i></i>学生评价</h2>
            <div class="statistics-cirl-solid-third03 th_eval tec col-md-20 mt20">
                <!--通过赋值修改font里的百分比-->
	        <font style="display: none"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->avg_score)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score*2; ?><?php }else{; ?>00<?php }; ?>%</font>
                <span class="score"><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score/10; ?>分</span>
                <div class="eval_pl col-md-20">
                    <span class="col-md-10" style="border-right:1px solid #e9e9e9;">综合得分<em><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->avg_score/10; ?></em>分</span>
                    <span class="col-md-10">评价数<em><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->comment; ?></em>条</span>
                </div>
            </div>
        </article>
    </div>
    <?php if(empty(SlightPHP\Tpl::$_tpl_vars["courseList"]) && empty(SlightPHP\Tpl::$_tpl_vars["imgList"]) && empty(SlightPHP\Tpl::$_tpl_vars["articleList"])){; ?>
        <div class="col-md-5 col-md-offset-3 mt20 tec pd45">
            <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
            <p class="mt10 c_a3 fs16"  >老师还在准备中...</p>
        </div>
    <?php }else{; ?>
        <div class="col-md-15 mt20 c-fl">
            <h2 class="th_l_h2 col-md-20 row"><i></i>最新课程
                <a href="/teacher/detail/course/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" class="fr">更多>></a>
            </h2>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                <ul class="col-md-20 col-xs-20 row techear-courselist mt20">
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li class="col-md-5 col-xs-10">
                        <div class="pic">
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>"><img class="imgPic" src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2){; ?>
                                    <span class="taped-class fs12">录播</span>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3)){; ?>
                                    <span class="lineclass-icon fs12">线下</span>
                                <?php }; ?>
                            </a>
                        </div>
                        <p class="item fs14 new-item ">
                            <a class="length_sl" href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['domain']; ?>/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['title']; ?></a>
                        </p>
                        <p class="item cDarkgray hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['grade']; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['major']; ?> <em class="fr"> 共<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['section_count']; ?>章节</em></p>
                        <div>
                            <span class="fl col-md-16 row length_sl  fs12">
                                <div class="row col-md-13 course_buy" style="width: auto;">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['fee_type'] == '免费'){; ?>
                                    <p class="item  cGreen">免费</p>
                                    <?php }else{; ?>
                                    <p class="item  cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_type']; ?></p>
                                    <?php }; ?>
                                </div>
                               <!--<sapn class="c_a3"> &nbsp;|&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['org_subname']; ?></sapn>-->
                            </span>
                            <?php if((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 1 or SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3) && SlightPHP\Tpl::$_tpl_vars["v"]['status'] != 1){; ?>
                                <span class="fr ter c_a3"> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>分</span>
                                <!--<span class="fr ter c_a3"> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?></span>-->
                            <?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 2){; ?>
                                <span class="fr ter c_a3"> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['score']; ?>分</span>
                                <!--<span class="fr ter c_a3"> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?></span>-->
                            <?php }; ?>
                            <?php if((SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 1 or SlightPHP\Tpl::$_tpl_vars["v"]['course_type'] == 3) && SlightPHP\Tpl::$_tpl_vars["v"]['status'] == 1){; ?>
                                <p class="course_ab2 cDarkgray fr mt35">开课时间：<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['start_time']; ?></p>
                            <?php }; ?>
                        </div>
                    </li>
                <?php }; ?>
            </ul>
            <?php }else{; ?>
                <div class="col-md-6 col-md-offset-6 mt20 tec pd45">
                    <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
                    <p class="mt10 c_a3 fs16"  >老师还在准备中...</p>
                </div>
            <?php }; ?>
            <!--相册-->
            <div class="col-md-20 col-sm-20 mt20 row c-fl col-xs-20">
                <h2 class="th_l_h2 col-xs-20"><i></i>相册
                    <a href="/teacher/detail/style/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" class="fr">更多>></a>
                </h2>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["imgList"])){; ?>
                <div class="th_mr_pic row col-xs-20">
                    <ul>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["imgList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <li class="col-md-6 col-sm-6 col-xs-20">
                                <a href='/teacher/detail/imgInfo/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>'><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb_med']; ?>"></a>
                            </li>
                        <?php }; ?>
                    </ul>
                </div>
                <?php }else{; ?>
                <div class="col-md-6 col-md-offset-6 mt20 tec pd45">
                    <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
                    <p class="mt10 c_a3 fs16"  >老师还在准备中...</p>
                </div>
                <?php }; ?>
            </div>
            <!--文章资料-->
            <div class="th_mr_data row col-sm-20 mt20 col-md-20 c-fl col-xs-20">
                <h2 class="th_l_h2 col-xs-20"><i></i>文章资料
                    <a href="/teacher/detail/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" class="fr">更多>></a>
                </h2>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["articleList"])){; ?>
                <ul class="techear-courselist">
                    <!--右侧带图-->
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["articleList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li class="col-md-20 col-xs-20">
                        <a href="/teacher/detail/ArticleDetail/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['articleId']; ?>">
                            <span class="col-md-14 col-sm-14">
                                <h3 class="length_sl fs14 mb10"><?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["v"]['title']); ?></h3>
                                <p class="fs12 c_a3"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['summary']; ?></p>
                                <div class="data_icon mt30">
                                    <span class="c_a3 fs12 mr20"><i class="icon_pic zf">&nbsp;</i>&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['share']; ?></span>
                                    <span class="c_a3 fs12 "><i class="icon_pic pl">&nbsp;</i>&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?></span>
                                </div>
                            </span>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['thumb']){; ?>
                            <span class="col-md-6 col-sm-6 text-right"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>"></span>
                            <!--<span class="col-md-4 col-sm-4"><img src="<?php echo utility_cdn::img('/assets_v2/img/zl_pic.jpg'); ?>"></span>-->
                            <?php }; ?>
                        </a>
                    </li>
                    <?php }; ?>
                </ul>
                <?php }else{; ?>
                <div class="col-md-6 col-md-offset-6 mt20 tec pd45">
                    <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
                    <p class="mt10 c_a3 fs16"  >老师还在准备中...</p>
                </div>
                <?php }; ?>
            </div>

        </div>
    <?php }; ?>
</article>
<!-- 内容结束 -->
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
<!-- /content -->
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.raphael.js'); ?>" type="text/javascript"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.cirls.js'); ?>" type="text/javascript"></script>
</body>
</html>
