<!DOCTYPE html>
<html>
<head>
<title><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['title']; ?>-<?php echo tpl_function_part('/site.main.orgname'); ?>-云课-专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 课程详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['desc'])); ?>">
<?php echo tpl_function_part("/site.main.header"); ?>
<?php echo tpl_function_part("/site.main.weixin"); ?>
<?php /*<meta name="weixin" title="" link="" imgurl="" desc=""/>*/?>
<meta name="weixin" imgurl="<?php echo utility_cdn::http(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['thumb']); ?>" desc="<?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['title'])); ?>"/>
<script src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.share.js'); ?>"></script>
<script type="text/javascript">
var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<!-- 课程详情上 -->
<section id="cy-new-course-detail" class="bgf">
    <div class="container">
        <div class="row pb20">
            <div class="col-sm-20 col-xs-20 fs14 course-link-title">
                <div class="col-md-8 pd0">
                    <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fName']){; ?>
                        <a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fCate']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fName']; ?></a> >
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fCate'] && SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['sCate']){; ?>
                        <a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fCate']; ?>&sc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['sCate']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['sName']; ?></a> >
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fCate'] && SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['sCate'] && SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['tCate']){; ?>
                        <a href="/course.list?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['fCate']; ?>&sc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['sCate']; ?>&tc=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['tCate']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['tName']; ?></a> >
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['major']){; ?>
                        <span><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['major']; ?></span>
                    <?php }; ?>
                </div>
                <div class="col-md-10 tel fs14 cDarkgray overdue-member-tip">
                    <div class="member-ct bor1px mt10">
                        <span class="tilt-icon c-fl"></span>
                        <?php echo tpl_modifier_tr('学习有效期已失效，继续学习请','course.info'); ?>
                        <a href="javascript:;" class="cYellow registration-buyBtn" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?>><?php echo tpl_modifier_tr('立即购买','course.info'); ?></a> <?php echo tpl_modifier_tr('课程或','course.info'); ?>
                        <a href="/member.list" class="cYellow" title=""><?php echo tpl_modifier_tr('重新开通','course.info'); ?></a> <?php echo tpl_modifier_tr('会员','course.info'); ?>
                    </div>
                </div>
				 <div class="col-md-10 tel fs14 cDarkgray overdue-member-tip2">
                    <div class="member-ct bor1px mt10">
                        <span class="tilt-icon c-fl"></span>
                        <?php echo tpl_modifier_tr('学习有效期已失效，继续学习请','course.info'); ?>
                        <a href="javascript:;" class="cYellow registration-buyBtn" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?>><?php echo tpl_modifier_tr('立即购买','course.info'); ?></a> <?php echo tpl_modifier_tr('课程','course.info'); ?>
                    </div>
                </div>
				<div class="col-md-10 tel fs14 cDarkgray overdue-member-tip3">
                    <div class="member-ct bor1px mt10">
                        <span class="tilt-icon c-fl"></span>
                        <?php echo tpl_modifier_tr('学习有效期已失效，继续学习请重新报名','course.info'); ?>
                    </div>
                </div>
            </div>
            <div class="course-pic look-course-video col-sm-8 c-fl mr15 pos-rel">
                    <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['thumb']; ?>" alt="">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["try"])){; ?>
                    <a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["try_plan"]; ?>" target="_blank" id="look-course-video-icon" class="look-course-video-icon tac fs18 fcf pos-abs">
                        <?php echo tpl_modifier_tr('点击试听','course.info'); ?>&nbsp;&nbsp;
					<?php }; ?>
                    </a>
                <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseType'] == 2){; ?>
                    <span class="g-icon3" id="g-icon3"><?php echo tpl_modifier_tr('录播','course.info'); ?></span>
                <?php }; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseType'] == 3){; ?>
                <span class="taped-icon" id="taped-icon"><?php echo tpl_modifier_tr('线下','course.info'); ?></span>
                <?php }; ?>
            </div>
            <div class="col-sm-9 col-xs-20 pd0 course-item-info" style="float:left">
                <div class="col-sm-20 detail-h pd0 c-fl col-xs-20">
                    <h1 class="detail-title fs18"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['title']; ?></h1>
                    <div class="fs14 detail-price course-priceInfo clearfix">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['feeType']){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['price']==SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['originPrice']){; ?>
                                <span class="c-fl ml5 mr10 fcg9 course-freePrice">
                                <?php echo tpl_modifier_tr('价格','course.info'); ?>：
                                <em class="fs20">￥<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['price']; ?></em>
                                <var class="cGreen fs20"><?php echo tpl_modifier_tr('免费','course.info'); ?></var>
                            </span>
                            <?php }else{; ?>
                                <span class="c-fl ml5 mr10 fcg9 course-freePrice">
                                    <?php echo tpl_modifier_tr('价格','course.info'); ?>：
                                    <em class="fs20">￥<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['price']; ?></em>
                                    <var class="cGreen fs20"><?php echo tpl_modifier_tr('免费','course.info'); ?></var>
                                </span>
                                <span class="c-fl course-y-price fs14 fcg9" courseyprice="<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['originPrice']; ?>" style="display: block;"><?php echo tpl_modifier_tr('原价','course.info'); ?>：￥<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['originPrice']; ?></span>
                            <?php }; ?>
                        <?php }else{; ?>
                            <span class="c-fl ml5 mr10 fs18 cGreen course-charge"><?php echo tpl_modifier_tr('免费','course.info'); ?></span>
                        <?php }; ?>
    					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['memberset'])){; ?>
                            <div class="vip-course-category pos-rel col-md-4 pd0">
                                <span class="vip-icon c-fl"></span> <?php echo tpl_modifier_tr('会员课程','course.info'); ?>
                                <dl class="col-md-20 pos-abs vip-course-list vip-course-listHover pd0">
    								<?php foreach(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['memberset'] as SlightPHP\Tpl::$_tpl_vars["mo"]){; ?>
                                    <dd class="col-md-20 pd0">
                                         <span class="courseTitle"><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_name; ?></span>
                                        <a href="javascript:;"  data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->member_set_id; ?>" onclick="memberOpen(this)" class="cYellow"><?php echo tpl_modifier_tr('马上开通','course.info'); ?></a>
                                    </dd>
    								<?php }; ?>
                                </dl>
                            </div>
    					<?php }; ?>
                        <span class="c-fr mr5 fcg9">
                            <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['score']){; ?>
                            <?php echo tpl_modifier_tr('评分','course.info'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['score']; ?>
                            <?php }else{; ?><?php echo tpl_modifier_tr('暂无评分','course.info'); ?><?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['vv'] > 10){; ?>
                            | <?php echo tpl_modifier_tr('播放','course.info'); ?> <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['vv']; ?> <?php echo tpl_modifier_tr('次','course.info'); ?>
                            <?php }; ?>
                        </span>
                    </div>
                <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['classNum'] == 1){; ?>
                <!-- 一个班级 -->
                    <div class="col-sm-20 col-xs-20 pd0" id="classListOneName"></div>
                    <div class="seal" style='display:none'>
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/new-reg.png'); ?>">
                    </div>
                <!-- /一个班级 -->
                <?php }; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['classNum'] > 1){; ?>
                <!-- 多个班级 -->
                    <div class="col-sm-20 col-xs-20 fs14 pd0 mt10 classSixNameParents">
                        <ul class="col-sm-20 col-xs-20 pd0 detail-tab-btn" id="classListSixName"></ul>
                        <ul class="col-sm-20 col-xs-20 pd0 detail-tab-content cDarkgray" id="classListSixData"></ul>
                    </div>
                    <div class="seal" style='display:none'>
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/new-reg.png'); ?>">
                    </div>
                <!-- /多个班级 -->
                <?php }; ?>
                </div>
            <!-- 按钮状态 -->
                <div class="col-sm-20 col-xs-20 course-btn fs14 pd0">
                    <div class="col-sm-10 col-lg-6 col-xs-15" style="margin-left:-15px;">
                        <a style="display:none;" class="s-course-btn registration" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?> ><?php echo tpl_modifier_tr('立即报名','course.info'); ?></a>
                        <a style="display:none;" class="s-course-btn registration-buy" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?>><?php echo tpl_modifier_tr('立即购买','course.info'); ?></a>
                        <a class="s-course-end-btn" style='display:none'><?php echo tpl_modifier_tr('已报满','course.info'); ?></a>
                        <a class="s-courseEnd-btn" style='display:none'><?php echo tpl_modifier_tr('已结束','course.info'); ?></a>
                        <div class="inClassRoom" style='display:none'>
                            <a href='#' target="_blank" class="s-course-come-btn"><?php echo tpl_modifier_tr('进入课堂','course.info'); ?></a>
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-5 bought pd0">
                        <a href="javascript:;" id='outLike' cid=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?> onclick="likeCollect(this)" class="selected" style='display:none'><?php echo tpl_modifier_tr('已收藏','course.info'); ?></a>
                        <a href="javascript:;" id='overLike' cid=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?> onclick="likeCollect(this)"><?php echo tpl_modifier_tr('收藏','course.info'); ?></a>
                    </div>
                    <div class="col-sm-3 col-xs-10 mtp12 pd0 hidden-xs">
                        <div class="share tel pos-rel wrap-courseshare">
                            <span class="play-share-icon c-fl"></span><?php echo tpl_modifier_tr('分享','course.info'); ?>
                            <div class="share-sns-box pos-abs">
                                <a href="javascript:;" class="share-qq" data-cmd="tqq" title="分享到QQ"></a>
                                <a href="javascript:;" class="share-weixin" data-cmd="weixin" title="分享到微信"></a>
                                <a href="javascript:;" class="share-tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-11 col-lg-6 col-xs-20 pdr0 cDarkgray c-fr pd0" style="float:right">
                        <p class="fs14 lh22 course-time mt10 ter">
                            <span class="service-title tel"><?php echo tpl_modifier_tr('课程有效期','course.info'); ?>：</span><?php echo tpl_modifier_tr('长期有效','course.info'); ?>
                        </p>
                        <div class="col-sm-20 pd0 tel course-service" style="display:none;">
                            <span class="ter service-title service-title-icon"><?php echo tpl_modifier_tr('包含服务','course.info'); ?>：</span>
                            <span class="video-icon" title="本课程可试看"></span>
                            <span class="lesson-icon" title="本课程有录播视频"></span>
                        </div>
                    </div>
                </div>
            <!-- /按钮状态 -->
            </div>
        </div>
    </div>
</section>
<!-- /课程详情上 -->
<!-- /课程详情下 -->
<section id="cy-new-course-detail-ft">
<div class="course-detail-ft">
    <div class="container">
        <div class="row">
            <!-- 右侧 -->
            <div class="col-sm-6 col-xs-20 pd0 c-fr" style="float:right\9">
                <div class="col-sm-20 bgf bor1px mb10 boradius3px">
                    <!-- 授课老师 -->
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["data"]['teacherMasterList'])){; ?>
                        <h2 class="fs16 t-teacher-title"><?php echo tpl_modifier_tr('授课讲师','course.info'); ?></h2>
                        <ol class="t-teacher-list mt15 clearfix">
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["data"]['teacherMasterList'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <li>
                                <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="col-sm-8 col-md-5 col-xs-4 pd0 t-teacher-img">
                                    <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherThumb']; ?>" alt="">
                                </a>
                                <div class="col-sm-12 col-xs-16 col-md-15 pd0">
                                    <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="t-teacher-name fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherName']; ?></a>
                                    <div class="col-sm-20 pd0 fs12 colorae">
                                        <span class="teacher-introduct"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['desc']; ?></span>
                                       <a href="/teacher/detail/entry/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['teacherId']; ?>" class="c-fr t-teacher-detail" title="详细" style="display:none;"><?php echo tpl_modifier_tr('详细','course.info'); ?></a>
                                    </div>
                                </div>
                            </li>
                            <?php }; ?>
                        </ol>
                    <?php }; ?>
                    <!-- /授课老师 -->
                </div>
                <div class="col-sm-20 bgf bor1px now-study-model hidden-xs boradius3px" style="display:none;">
                   <!-- 正在学习 -->
                        <h3 class="fs16 t-teacher-title"><?php echo tpl_modifier_tr('正在学习','course.info'); ?></h3>
                        <ol class="t-student-list t-student-study clearfix"></ol>
                    <!-- /正在学习 -->
                </div>
            </div>
            <!-- /右侧 -->
            <!-- 左侧 -->
            <div class="col-sm-14 col-xs-20 c-fr col-md-14">
                <ul class="clearfix fs16 course-article-btn general-course-liBtn bgf bor1px">
                    <li class="col-sm-3 col-xs-6 tac pd0 curr courserOutline" name="courserOutline"><?php echo tpl_modifier_tr('课程概要','course.info'); ?></li>
                    <li class="col-sm-3 col-xs-6 pd0 tac courserSection" name="courserSection"><?php echo tpl_modifier_tr('课程章节','course.info'); ?></li>
                    <li class="col-sm-3 col-xs-6 pd0 tac courserComment" name="courserComment"><?php echo tpl_modifier_tr('学员评论','course.info'); ?></li>
                </ul>
                <div class="col-sm-20 pd0 bgf" id="courserOutline">
                <!-- 课程概要 -->
                    <div class="col-sm-20 col-xs-20 course-article-content" id="article-content">
                        <div class="col-sm-20 pd0 bottom1px col-xs-20">
                            <article class="fs14">
                                <p class="cDarkgray mt10 mb10"><?php echo tpl_modifier_tr('适合范围','course.info'); ?>：</p>
                                <div class="cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['scope']; ?></div>
                                <p class="cDarkgray mb10 mt10"><?php echo tpl_modifier_tr('课程简介','course.info'); ?>：</p>
                                <div class="cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['desc']; ?></div>
                            </article>
                        </div>
                    </div>
                <!-- /课程概要 -->
                <!-- 课程章节 -->
                    <div class="col-sm-20 col-xs-20 course-article-content" id="courserSection">
                        <div class="col-sm-20 pd0 bottom1px col-xs-20">
                            <h1 class="fs16 mt20 mb20"><i class="c-vertical-line mr5"></i><?php echo tpl_modifier_tr('课程章节','course.info'); ?></h1>
                            <div class="col-sm-20 pd0 course-article-list col-xs-20">
                                <ul id="planList"></ul>
                            </div>
                        </div>
                    </div>
                <!-- /课程章节 -->
                <!-- 学员评论 -->
                    <div id="courseDetailComment"></div>
                <!-- /学员评论 -->
                </div>
            </div>
            <!-- /左侧 -->
        </div>
    </div>
</div>
</section>
<!-- /课程详情下 -->
<!-- 浮动条 -->
<section id="wrap-course-float" style="display:none;">
    <div class="course-float">
        <div class="container">
            <div class="row">
                <div class="col-md-14 col-sm-10 col-xs-20">
                    <ul class="clearfix general-course-liBtn float-course-liBtn col-sm-20 pd0 fs16 col-xs-20">
                        <li class="tac col-sm-5 pd0 curr col-xs-6 col-md-3" target="_self" name="courserOutline" target="_self">
                            <?php echo tpl_modifier_tr('课程概要','course.info'); ?>
                        </li>
                        <li class="col-sm-5 tac pd0 courserSection col-xs-6 col-md-3" name="courserSection" target="_self">
                            <?php echo tpl_modifier_tr('课程章节','course.info'); ?>
                        </li>
                        <li class="col-sm-5 tac pd0 courserComment col-xs-6 col-md-3" name="courserComment" target="_self">
                            <?php echo tpl_modifier_tr('学员评论','course.info'); ?>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-10 hidden-xs pd0 c-fr mt5 course-priceInfo">
                    <div class="divselect fs14 course-divselect c-fr">
                        <cite></cite>
                        <dl style="z-index:10;" id="selectListName"></dl>
                    </div>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['feeType']){; ?>
                        <span class="course-price fs20 c-fl course-freePrice">
                            <em>￥<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['price']; ?></em>
                            <var class="cGreen fs20">免费</var>
                        </span>
                    <?php }else{; ?>
                        <span class="c-fl ml5 mr10 fs20 cGreen mt10 lh22"><?php echo tpl_modifier_tr('免费','course.info'); ?></span>
                    <?php }; ?>
                    <a style="display:none;" class="s-course-btn course-signUp c-fr fs14 tac registration" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?> ><?php echo tpl_modifier_tr('立即报名','course.info'); ?></a>
                    <a style="display:none;" class="s-course-btn course-signUp fs14 tac c-fr registration-buy" classId=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?>><?php echo tpl_modifier_tr('立即购买','course.info'); ?></a>
                    <a class="s-course-end-btn c-fr" style='display:none'><?php echo tpl_modifier_tr('已报满','course.info'); ?></a>
                    <a class="s-courseEnd-btn c-fr" style='display:none'><?php echo tpl_modifier_tr('已结束','course.info'); ?></a>
                    <div class="inClassRoom c-fr" style='display:none'>
                        <a href='#' target="_blank" class="s-course-come-btn fs14"><?php echo tpl_modifier_tr('进入课堂','course.info'); ?></a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- /浮动条 -->
<script type="text/javascript">
    var cid = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>;
    var classNum = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classNum']; ?>;
    var classList = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classList']; ?>;
    var planList = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['planList']; ?>;
    var classId = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['classId']; ?>;
	var membersetList = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['membersetList']; ?>;
	var userMemberCourse;
	var courseFeeType = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['feeType']; ?>;
    var setIdStr = '<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['setIdStr']; ?>';
    var userId = userApi.getUid();
    var checkedRegClassId;
    var getPlanCourseTapedIcon = document.getElementById("taped-icon");
    var checkListOneName = document.getElementById("classListOneName");
    var checkListSixName = document.getElementById("classListSixName");
    var regSource = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['regSource']; ?>;
    var resellOrgId = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['resellOrgId']; ?>;
</script>
<script type="text/javascript">
    //登陆
    function checkLogin(source,setId) {
        var cid = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>;
        var selectedClassId = $(".registration").attr("classId");
        var w,h;

        if($(window).width()>780){
            w='476px';
            h='390px';
        }else{
            w='90%';
            h='360px';
        }
		if (userApi.isLogin()) return true;

        if (userApi.isWeiXin() || $(window).width()<780) {
			//存储自动报名
			userApi.setCookie("course.autoreg",<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>);
            location.href='/site.main.login/?url=//'+location.hostname+"/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>";
		}else{
			layer.open({
				type: 2,
				title:false,
				area: [w, h],
				shadeClose: true,
				content: ['/layer.main.userLogin/'+cid+'/'+selectedClassId+'/'+classNum+'/'+source+'/'+setId+'/'+resellOrgId, 'no']
			});
			return false;
		}
    }

    //检查用户是否报名
    function checkUserIsRegAndLike() {
        if(!classId) {
            $(".registration").hide();
            $(".s-course-end-btn").hide();
            $(".inClassRoom").hide();
        }

        if (userApi.isLogin() && userId) {
            $.post('/course/ajax/CheckUserIsRegAndLike' , { courseId:cid, userId:userId, setIdStr:setIdStr } ,function(r) {
                if (r.code == 0) {
                    if (r.result.isLike) {
                        $("#outLike").show();
                        $("#overLike").hide();
                    }
					userMemberCourse = r.result;
					checkedRegClassId = r.result.regId;
                //单个班与多个班级识别会员与非会员
                    if(checkListOneName) { //单个班级
						if(!checkedRegClassId){ //没报名
							if(r.result.isMember == 1){
								if(r.result.isExpire == 0){
                                    if($(".course-y-price").attr("courseyprice")>0) {
                                        $(".course-freePrice em").text("￥" + $(".course-y-price").attr("courseyprice"));

                                        $(".course-y-price").hide();
                                    }
									$('.vip-course-list dd').each(function() {
										var thisUserMemberId = $(this).find('a').attr('data-id');
										var userMemberSetArr = r.result.userMemberSet;
										for(var i = 0; i < userMemberSetArr.length; i++) {
											if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
												if(userMemberSetArr[i]['is_expire'] == 0){
													$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
												}else{
													$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
												}
											}
										}
									});
									$('.course-freePrice').find('em').addClass('fs_dec');
									$('.course-freePrice').find('var').show();
									courseFeeType = 0;
								}else{
									$('.vip-course-list dd').each(function() {
										var thisUserMemberId = $(this).find('a').attr('data-id');
										var userMemberSetArr = r.result.userMemberSet;
										  for(var i = 0; i < userMemberSetArr.length; i++) {
												if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
													$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
												}
											}
									});
								}
							}
						}else{
							if(r.result.isMemberRegType == 0) { //普通身份报名
								if(classId == r.result.regId) { //报名的班级
                                    $('.video-try-icon').hide();
									if(r.result.isMember == 0) { //非会员课程
										$(".look-course-video-icon").hide();
										if(getPlanCourseTapedIcon){ //线下课
											$(".seal").show();
											$(".registration").hide();
											$(".s-course-end-btn").hide();
											$(".inClassRoom").hide();
											$(".s-courseEnd-btn").hide();
											var planCourseEnd = $("#planList").find(".courseEnd:last").html();
											if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
												$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
												$(".course-item-info").find(".cRed").hide();
												$(".s-courseEnd-btn").show();
											}
											checkedRegClassId = r.result.regId;
										}else{
											$(".seal").show();
											$(".inClassRoom").show();
											$(".registration").hide();
											$(".s-course-end-btn").hide();
											$(".s-courseEnd-btn").hide();
											$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);
										}
									}else { //会员课程
										if(r.result.isExpire == 0) { //会员课程未失效
											$('.vip-course-list dd').each(function() {
												var thisUserMemberId = $(this).find('a').attr('data-id');
												var userMemberSetArr = r.result.userMemberSet;
												for(var i = 0; i < userMemberSetArr.length; i++) {
													if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
														if(userMemberSetArr[i]['is_expire'] == 0){
															$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
														}else{
															$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
														}
													}
												}
											});

											$('.course-freePrice').find('em').addClass('fs_dec');
											$('.course-freePrice').find('var').show();
											courseFeeType = 0;

											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon){ //线下课
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$(".course-item-info").find(".cRed").hide();
													$(".s-courseEnd-btn").show();
												}
											}else{
												$(".seal").show();
												$(".inClassRoom").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".s-courseEnd-btn").hide();
												$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);
											}
										}else { //会员课程失效
											$('.vip-course-list dd').each(function() {
												var thisUserMemberId = $(this).find('a').attr('data-id');
												var userMemberSetArr = r.result.userMemberSet;
												for(var i = 0; i < userMemberSetArr.length; i++) {
													if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
														$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
													}else {
														return false;
													}
												}
											});
											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon){ //线下课
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$(".course-item-info").find(".cRed").hide();
													$(".s-courseEnd-btn").show();
												}
											}else{
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").show();
												$(".s-courseEnd-btn").hide();
												$('.registration-buy').hide();
											}
										}
									}
								}
							}else { //会员身份报名
								if(classId == r.result.regId) { //报名的班级
									if(r.result.isMember == 0) { //学生非会员
										if(setIdStr == ''){ //课程不是会员课程
											if(courseFeeType == 1){ //课程是付费课程
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon){ //线下课
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$(".course-item-info").find(".cRed").hide();
														$(".s-courseEnd-btn").show();
													}
												}else{
													$(".seal").hide();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													$('.registration-buy').show();
													$('.overdue-member-tip2').show();
												}
											}else{
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon){ //线下课
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$(".course-item-info").find(".cRed").hide();
														$(".s-courseEnd-btn").show();
													}
													checkedRegClassId = r.result.regId;
												}else{
													$(".seal").hide();
													$(".registration").show();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													$('.registration-buy').hide();
													$('.overdue-member-tip3').show();
												}
											}
										}else{
											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon){ //线下课
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$(".course-item-info").find(".cRed").hide();
													$(".s-courseEnd-btn").show();
												}
												checkedRegClassId = r.result.regId;
											}else{
												$(".seal").hide();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												$('.registration-buy').show();
												$('.overdue-member-tip').show();
											}
										}
									}else { //会员课程
										if(r.result.isExpire == 0) { //会员课程未失效
                                            if($(".course-y-price").attr("courseyprice")>0) {
                                                $(".course-freePrice em").text("￥" + $(".course-y-price").attr("courseyprice"));

                                                $(".course-y-price").hide();
                                            }
											$('.vip-course-list dd').each(function() {
												var thisUserMemberId = $(this).find('a').attr('data-id');
												var userMemberSetArr = r.result.userMemberSet;
												for(var i = 0; i < userMemberSetArr.length; i++) {
													if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
														if(userMemberSetArr[i]['is_expire'] == 0){
															$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
														}else{
															$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
														}
													}
												}
											});

											$('.course-freePrice').find('em').addClass('fs_dec');
											$('.course-freePrice').find('var').show();
											courseFeeType = 0;

											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon){ //线下课
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$(".course-item-info").find(".cRed").hide();
													$(".s-courseEnd-btn").show();
												}
												checkedRegClassId = r.result.regId;
											}else{
												$(".seal").show();
												$(".inClassRoom").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".s-courseEnd-btn").hide();
												checkedRegClassId = r.result.regId;
												$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);

											}
										}else { //会员课程失效
											$('.vip-course-list dd').each(function() {
												var thisUserMemberId = $(this).find('a').attr('data-id');
												var userMemberSetArr = r.result.userMemberSet;
												  for(var i = 0; i < userMemberSetArr.length; i++) {
														if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
															$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
														}
													}
											});
											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon){ //线下课
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$(".course-item-info").find(".cRed").hide();
													$(".s-courseEnd-btn").show();
												}
												checkedRegClassId = r.result.regId;
											}else{
												$(".seal").hide();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();
												$('.registration-buy').show();
												$('.overdue-member-tip').show();
											}
										}
									}
								}
							}
						}
                    }else if(checkListSixName) { //多个班级
                        checkedRegClassId = r.result.regId; //全局变量赋值->报名的班级
						if(!checkedRegClassId){ //未报名
							if(r.result.isMember == 1){
								if(r.result.isExpire == 0){
                                    if($(".course-y-price").attr("courseyprice")>0) {
                                        $(".course-freePrice em").text("￥" + $(".course-y-price").attr("courseyprice"));

                                        $(".course-y-price").hide();
                                    }
									$('.vip-course-list dd').each(function() {
										var thisUserMemberId = $(this).find('a').attr('data-id');
										var userMemberSetArr = r.result.userMemberSet;
										for(var i = 0; i < userMemberSetArr.length; i++) {
											if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
												if(userMemberSetArr[i]['is_expire'] == 0){
													$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
												}else{
													$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
												}
											}
										}
									});
									$('.course-freePrice').find('em').addClass('fs_dec');
									$('.course-freePrice').find('var').show();
									courseFeeType = 0;
								}else{
									$('.vip-course-list dd').each(function() {
										var thisUserMemberId = $(this).find('a').attr('data-id');
										var userMemberSetArr = r.result.userMemberSet;
										  for(var i = 0; i < userMemberSetArr.length; i++) {
												if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
													$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
												}
											}
									});
								}
							}
						}else{
							$('.registration-buyBtn').attr('classId',checkedRegClassId);
							if(r.result.isMemberRegType == 0) { //普通身份报名
								$("#classListSixName").find("li").each(function(){
									var selectClassId = $(this).attr("classid");
									if(checkedRegClassId == selectClassId) { //报名的班级
                                        $('.video-try-icon').hide();
										$("#classListSixName").find("li").removeClass("curr");
										$(this).addClass("curr");
										$('#classListSixData').find("li").hide();
										$('#classListSixData').find('li:eq('+ $(this).index() +')').show();
										$(".course-divselect").find("cite").html($(this).find("span").html());
										if(r.result.isMember == 0) { //非会员
											$(".look-course-video-icon").hide();
											if(getPlanCourseTapedIcon) {
												$(".seal").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".inClassRoom").hide();
												$(".s-courseEnd-btn").hide();

												var planCourseEnd = $("#planList").find(".courseEnd:last").html();
												if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
													$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
													$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
													$(".s-courseEnd-btn").show();
												}

												checkedRegClassId = r.result.regId;
											}else {
												$(".seal").show();
												$(".inClassRoom").show();
												$(".registration").hide();
												$(".s-course-end-btn").hide();
												$(".s-courseEnd-btn").hide();
												checkedRegClassId = r.result.regId;
												$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);

											}
										}else { //会员课程
											if(r.result.isExpire == 0) { //会员课程未失效
												$('.vip-course-list dd').each(function() {
													var thisUserMemberId = $(this).find('a').attr('data-id');
													var userMemberSetArr = r.result.userMemberSet;
													for(var i = 0; i < userMemberSetArr.length; i++) {
														if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
															if(userMemberSetArr[i]['is_expire'] == 0){
																$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
															}else{
																$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
															}
														}
													}
												});

												$('.course-freePrice').find('em').addClass('fs_dec');
												$('.course-freePrice').find('var').show();
												courseFeeType = 0;
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon) {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();

													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
														$(".s-courseEnd-btn").show();
													}

													checkedRegClassId = r.result.regId;
												}else {
													$(".seal").show();
													$(".inClassRoom").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".s-courseEnd-btn").hide();
													checkedRegClassId = r.result.regId;
													$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);
												}
											}else {  //会员课程失效
												$('.vip-course-list dd').each(function() {
													var thisUserMemberId = $(this).find('a').attr('data-id');
													var userMemberSetArr = r.result.userMemberSet;
													  for(var i = 0; i < userMemberSetArr.length; i++) {
															if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
																$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
															}else {
																return false;
															}
														}
												});
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon) {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();

													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
														$(".s-courseEnd-btn").show();
													}
													checkedRegClassId = r.result.regId;
												}else {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").show();
													$(".s-courseEnd-btn").hide();
													$('.registration-buy').hide();
												}
											}
										}
									}
								})
							}else { //会员身份报名
								$("#classListSixName").find("li").each(function(){
									var selectClassId = $(this).attr("classid");
									if(checkedRegClassId == selectClassId) { //报名的班级
										$("#classListSixName").find("li").removeClass("curr");
										$(this).addClass("curr");
										$('#classListSixData').find("li").hide();
										$('#classListSixData').find('li:eq('+ $(this).index() +')').show();
										$(".course-divselect").find("cite").html($(this).find("span").html());
										if(r.result.isMember == 0) { //学生非会员
											if(setIdStr == ''){ //课程不是会员课程
												if(courseFeeType == 1){ //课程是付费课程
													$(".look-course-video-icon").hide();
													if(getPlanCourseTapedIcon){ //线下课
														$(".seal").show();
														$(".registration").hide();
														$(".s-course-end-btn").hide();
														$(".inClassRoom").hide();
														$(".s-courseEnd-btn").hide();
														var planCourseEnd = $("#planList").find(".courseEnd:last").html();
														if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
															$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
															$(".course-item-info").find(".cRed").hide();
															$(".s-courseEnd-btn").show();
														}
														checkedRegClassId = r.result.regId;
													}else{
														$(".seal").hide();
														$(".registration").hide();
														$(".s-course-end-btn").hide();
														$(".inClassRoom").hide();
														$(".s-courseEnd-btn").hide();
														$('.registration-buy').show();
														$('.overdue-member-tip2').show();
													}
												}else{
													$(".look-course-video-icon").hide();
													if(getPlanCourseTapedIcon){ //线下课
														$(".seal").show();
														$(".registration").hide();
														$(".s-course-end-btn").hide();
														$(".inClassRoom").hide();
														$(".s-courseEnd-btn").hide();
														var planCourseEnd = $("#planList").find(".courseEnd:last").html();
														if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
															$('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
															$(".course-item-info").find(".cRed").hide();
															$(".s-courseEnd-btn").show();
														}
														checkedRegClassId = r.result.regId;
													}else{
														$(".seal").hide();
														$(".registration").show();
														$(".s-course-end-btn").hide();
														$(".inClassRoom").hide();
														$(".s-courseEnd-btn").hide();
														$('.registration-buy').hide();
														$('.overdue-member-tip3').show();
													}
												}
											}else{
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon) {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
														$(".s-courseEnd-btn").show();
													}
													checkedRegClassId = r.result.regId;
												}else {
													$(".seal").hide();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													$('.registration-buy').show();
													$('.overdue-member-tip2').show();
												}
											}
										}else { //会员课程
											if(r.result.isExpire == 0) { //会员课程未失效
                                                if($(".course-y-price").attr("courseyprice")>0) {
                                                    $(".course-freePrice em").text("￥" + $(".course-y-price").attr("courseyprice"));

                                                    $(".course-y-price").hide();
                                                }
												$('.vip-course-list dd').each(function() {
													var thisUserMemberId = $(this).find('a').attr('data-id');
													var userMemberSetArr = r.result.userMemberSet;
													for(var i = 0; i < userMemberSetArr.length; i++) {
														if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
															if(userMemberSetArr[i]['is_expire'] == 0){
																$(this).find('a').html('<?php echo tpl_modifier_tr('立即续费','course.info'); ?>');
															}else{
																$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
															}
														}
													}
												});

												$('.course-freePrice').find('em').addClass('fs_dec');
												$('.course-freePrice').find('var').show();
												courseFeeType = 0;

												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon) {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();

													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
														$(".s-courseEnd-btn").show();
													}

													checkedRegClassId = r.result.regId;
												}else {
													$(".seal").show();
													$(".inClassRoom").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".s-courseEnd-btn").hide();
													checkedRegClassId = r.result.regId;
													$(".inClassRoom").find("a").attr("href","/course.info.playUrl/"+cid+"/"+checkedRegClassId);

												}
											}else { //会员课程失效
												$('.vip-course-list dd').each(function() {
													var thisUserMemberId = $(this).find('a').attr('data-id');
													var userMemberSetArr = r.result.userMemberSet;
													  for(var i = 0; i < userMemberSetArr.length; i++) {
															if(thisUserMemberId == userMemberSetArr[i]['fk_member_set']) {
																$(this).find('a').html('<?php echo tpl_modifier_tr('重新开通','course.info'); ?>');
															}
														}
												});
												$(".look-course-video-icon").hide();
												if(getPlanCourseTapedIcon) {
													$(".seal").show();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();

													var planCourseEnd = $("#planList").find(".courseEnd:last").html();
													if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
														$('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
														$('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
														$(".s-courseEnd-btn").show();
													}
													checkedRegClassId = r.result.regId;
												}else {
													$(".seal").hide();
													$(".registration").hide();
													$(".s-course-end-btn").hide();
													$(".inClassRoom").hide();
													$(".s-courseEnd-btn").hide();
													$('.registration-buy').show();
													$('.overdue-member-tip').show();
													checkedRegClassId = r.result.regId;
												}
											}
										}
									}
								});
							}
						}
                    }else {
                        return false;
                    }

                }
            },'json');
        }

    }

//马上开通
	function memberOpen(obj){
		var setId = $(obj).attr('data-id');
		var source = 'memberset';
		if (checkLogin(source,setId)) {
			location.href = '/order.main.memberinfo/'+setId;
		}
	}

    //收藏
    function likeCollect(obj) {
        var cid = $(obj).attr("cid");
        var val = $(obj).html();
        var source = 'like';

        if (checkLogin(source)) {
            if(val == '<?php echo tpl_modifier_tr('收藏','course.info'); ?>') {
                $.post("/course.info.checkfavajax",{ "couid": cid } ,function(r) {
                    if(r.error) {
                        layer.msg(r.error);
                    }else {
                        $(obj).addClass("selected");
                        $(obj).html('<?php echo tpl_modifier_tr('已收藏','course.info'); ?>');
                    }
                },"json")
            }

            if(val == '<?php echo tpl_modifier_tr('已收藏','course.info'); ?>') {
                $.post("/course.info.delFav" ,{ "cid": cid } ,function(r) {
                    if(r.error) {
                        layer.msg(r.error);
                    }else {
                        $(obj).removeClass("selected");
                        $(obj).html('<?php echo tpl_modifier_tr('收藏','course.info'); ?>');
                    }
                },"json")
            }

        }

    }

    //课程章节
    function getPlanListData(classId, obj) {
        var objClassId = $(obj).attr("classid");
        var fullNum=$('#classListSixData li:eq(' + $(obj).index() + ')').find('.cRed').length;
        var fullTxt=$('#classListSixData li:eq(' + $(obj).index() + ')').find('.cRed').html();
        var endNum=$('#classListSixData li:eq(' + $(obj).index() + ')').find('.courseEnd').length;
        var endTxt=$('#classListSixData li:eq(' + $(obj).index() + ')').find('.courseEnd').html();

        $(obj).addClass('curr').siblings().removeClass('curr');
        $('.detail-tab-content li:eq(' + $(obj).index() + ')').show().siblings().hide();
        $(".s-course-btn").attr("classid",objClassId);
		$('.registration-buyBtn').attr("classid",objClassId);
        $(".course-divselect").find("cite").html($(obj).find("span").html());

        if (checkedRegClassId) {
            if(userMemberCourse.isMemberRegType == 0) { //普通身份报名
                    if (checkedRegClassId == objClassId) {
                        $(".inClassRoom").show();
                        $(".seal").show();
                        $(".s-course-end-btn").hide();
                        $('.registration').hide();
                        $(".s-courseEnd-btn").hide();
                        $('.registration-buy').hide();
                    } else {
                        $(".s-course-end-btn").hide();
                        $('.registration').hide();
                        $(".s-courseEnd-btn").hide();
                        $(".inClassRoom").hide();
                        $(".seal").hide();
                        $('.registration-buy').hide();
                    }
                    if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                        $(".s-course-end-btn").hide();
                    }
            }else { //会员身份报名
                if(userMemberCourse.isMember == 0) { //非会员
                    if(setIdStr == '') {
                        if(courseFeeType == 1){ //课程是付费课程
                            if (checkedRegClassId == objClassId) {
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $('.registration-buy').show();
                            } else {
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $('.registration-buy').hide();
                            }
                            if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                $(".s-course-end-btn").hide();
                            }
                        }else { //课程免费课
                            if (checkedRegClassId == objClassId) {
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $(".s-course-end-btn").hide();
                                $('.registration').show();
                                $(".s-courseEnd-btn").hide();
                                $('.registration-buy').hide();
                            } else {
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $('.registration-buy').hide();
                            }
                            if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                $(".s-course-end-btn").hide();
                            }
                        }
                    }else { //会员课程
						if (checkedRegClassId == objClassId) {
							$(".inClassRoom").hide();
							$(".seal").hide();
							$(".s-course-end-btn").hide();
							$('.registration').hide();
							$(".s-courseEnd-btn").hide();
							$('.registration-buy').show();
						} else {
							$(".s-course-end-btn").hide();
							$('.registration').hide();
							$(".s-courseEnd-btn").hide();
							$(".inClassRoom").hide();
							$(".seal").hide();
							$('.registration-buy').hide();
						}
						if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
							$(".s-course-end-btn").hide();
						}
                    }
                }else { //是会员
                    if(userMemberCourse.isExpire == 0) {  //会员未失效
                        if (checkedRegClassId == objClassId) {
                            $(".inClassRoom").show();
                            $(".seal").show();
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $('.registration-buy').hide();
                        } else {
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $(".inClassRoom").hide();
                            $(".seal").hide();
                            $('.registration-buy').hide();
                        }
                        if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                            $(".s-course-end-btn").hide();
                        }
                    }else { //会员失效
                        if (checkedRegClassId == objClassId) {
                            $(".inClassRoom").hide();
                            $(".seal").hide();
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $('.registration-buy').show();
                        } else {
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $(".inClassRoom").hide();
                            $(".seal").hide();
                            $('.registration-buy').hide();
                        }
                        if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                            $(".s-course-end-btn").hide();
                        }
                    }
                }
            }
        } else {
			$(".s-course-end-btn").hide();
			$('.registration').hide();
			$(".s-courseEnd-btn").hide();
			$(".inClassRoom").hide();
			$(".seal").hide();
			$('.registration-buy').hide();

			if (fullNum > 0 && fullTxt === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
				$(".s-course-end-btn").show();
			}else{
				$('.registration').show();
			}
        }

        if (endNum > 0 && endTxt === '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
            $(".s-courseEnd-btn").show();
            $(".s-course-end-btn").hide();
            $('.registration').hide();
            $(".inClassRoom").hide();
            $(".seal").hide();
            $('.registration-buy').hide();
        }

        if(getPlanCourseTapedIcon) {
            $(".inClassRoom").hide();
        }

        if(classId =="" || classId == null) {
            return false;
        }else {
            var planListData = [];
		        planListData['planList'] = planList.planList && planList.planList[classId] ? planList.planList[classId] : [];
            var planListTpl = $('#planListTpl').html();
            $('#planList').html(Mustache.render(planListTpl, planListData));
        }

        if($("#planList").find("li").attr("coursetype") == 3)  {
            $("#planList").find("a").attr("href","javascript:;");
            $("#planList").find("a").css("cursor","default");
        }

        $('#planList .course-article-content-title').each(function() {
            var strCourseTitle = $(this).attr('data-title');
            strCourseTitle = strCourseTitle.replace(/<\/?[^>]*>/g,'');
            $(this).attr('title',strCourseTitle);
        })

    }

    //删除评论
    function commentDel(obj) {
        var uid = $(obj).attr("data-uid");
        var pid = $(obj).attr("data-pid");
        var cli= $(obj).parents("li");

        $.post("/course/ajax/DelComment", { userId:uid ,courseId:cid ,planId:pid }, function (r) {
            if(r.code==0){
                cli.remove();
                layer.msg('<?php echo tpl_modifier_tr('删除成功','course.info'); ?>');
            }else{
                layer.msg(r.errMsg);
            }
        },"json");
    }


    $(function() {
        getClassData();
        checkUserIsRegAndLike();
        setTimeout(function() {
           getRegUserList();
        },1000);

        $(".s-courseEnd-btn").hide();
        $(".seal").hide();
        $(".s-course-end-btn").hide();
        $(".inClassRoom").hide();
        $("#outLike").hide();
        $(".registration").show();
        $("#overLike").show();

    //初始化six且线下课结束
        if (checkedRegClassId) {
            getPlanListData(checkedRegClassId);
        }else{
            getPlanListData(classId);
            $("#classListSixData").find("li").hide();
            $("#classListSixData").find("li:eq(0)").show();
        }
    //是否存在线下课
        if(getPlanCourseTapedIcon){
            $(".course-time").hide();
            var planCourseEnd = $("#planList").find(".courseEnd:last").html();

            if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
                $('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
                $(".course-item-info").find(".cRed:eq(0)").hide();
                $(".s-courseEnd-btn").show();
                $(".s-course-end-btn").hide();
                $('.registration').hide();
                $(".inClassRoom").hide();
                $(".seal").hide();
            }

            if(checkListSixName) {
                $('#classListSixName').on('mouseover' ,'li' ,function() {
                    var planCourseEnd = $("#planList").find(".courseEnd:last").html();
                    if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
                        $('#classListSixData li:eq('+$(this).index()+')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
                        $('#classListSixData li:eq('+$(this).index()+')').find('.cRed').hide();
                        $(".s-courseEnd-btn").show();
                        $(".s-course-end-btn").hide();
                        $('.registration').hide();
                        $(".inClassRoom").hide();
                        //$(".seal").hide();
                    }
                })
            }

        }

        if(classId == 0 || classId == null) {
            $(".s-course-end-btn").hide();
            $('.registration').hide();
            $(".s-courseEnd-btn").hide();
            $(".inClassRoom").hide();
            $(".seal").hide();
        }

        if(checkListOneName) {
            $(".course-divselect").remove();
			if($(".course-item-info").find("span.cRed:eq(0)").html() == '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
				$(".registration").hide();
				$(".inClassRoom").hide();
				$(".s-courseEnd-btn").hide();
				$(".seal").hide();
				$(".s-course-end-btn").show();
			}
        }else if(checkListSixName) {
            $("#classListSixName").find("li").each(function() {
                var classListName = $(this).find("span").html();
                    if($(this).hasClass('curr')) {
                        $(".course-divselect").find("cite").html(classListName);
                    }else {
                        return false;
                    }
            })
            if($("#classListSixData li:eq(0)").find("span.cRed:eq(0)").html() == '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                $(".registration").hide();
                $(".inClassRoom").hide();
                $(".s-courseEnd-btn").hide();
                $(".seal").hide();
                $(".s-course-end-btn").show();
            }else {
                $(".inClassRoom").hide();
                $(".s-courseEnd-btn").hide();
                $(".seal").hide();
                $(".s-course-end-btn").hide();

                if(getPlanCourseTapedIcon) {
                    var planCourseEnd = $("#planList").find(".courseEnd:last").html();
                    if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
                        $('.course-item-info').find('.classProgress:eq(0)').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
                        $(".course-item-info").find(".cRed:eq(0)").hide();
                        $(".s-courseEnd-btn").show();
                        $(".s-course-end-btn").hide();
                        $('.registration').hide();
                        $(".inClassRoom").hide();
                        $(".seal").hide();
                    }else {
                        $(".s-courseEnd-btn").hide();
                        $(".s-course-end-btn").hide();
                        $('.registration').show();
                        $(".inClassRoom").hide();
                        $(".seal").hide();
                    }
                }else {
                    $(".registration").show();
                }
            }
        }else {
            $(".course-divselect").hide();
        }

        //立即报名按钮
        function autoreg(objClassId){
            var source = 'reg';
            var cid = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>;
            var oid = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['resellOrgId']; ?>;
            var w,h;
            var MultipleType = false;
            if(!objClassId || !cid ) {
                layer.msg('<?php echo tpl_modifier_tr('班级或课程ID不能为空','course.info'); ?>');
                return false;
            }

            if (checkLogin(source)) {
                if(classNum >1) {
                    if($(window).width() > 780) {
                        w='420px';
                        h='360px';
                    }else {
                        w='90%';
                        h='360px';
                    }
                    var url = "/course/info/GetClassList/"+cid+'/'+objClassId;
                    if(resellOrgId){
                        url += "/"+resellOrgId;
                    }
                    if($(window).width() < 780) {
                        MultipleType = true;
                    }else {
                        layer.open({
                            type: 2,
                            title:['<?php echo tpl_modifier_tr('报名','course.info'); ?>','color:#fff;background:#ffa81d'],
                            area: [w,h],
                            shadeClose: true,
                            content: url
                        });

                    }
                }

                if(classNum == 1 || MultipleType) {
                    var type = <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['feeType']; ?>;
                    var index = layer.load(2);

                    if(courseFeeType) {
                        // 付费课程报名，需要先支付
                        $.post("/course/pay/check", { classId:objClassId ,cid:cid }, function (r) {
                            if (r.code == 0) {
                                if (resellOrgId) {
                                    parent.location.href = "/order.main.buy/course/"+cid+"/"+objClassId+"/"+resellOrgId;
                                } else {
                                    parent.location.href = "/order.main.buy/course/"+cid+"/"+objClassId;
                                }
                            } else {
                                layer.msg(r.errMsg);
                            }
                        }, "json");
                    } else {
                        $.post("/course/info/AddReg", { classId: objClassId ,cid: cid ,oid: oid, source: regSource }, function (r) {
                            //add weixin 后缀，解决微信里不能刷新的问题
                            layer.close(index);
                            if (r.code == 0) {
                                layer.msg('<?php echo tpl_modifier_tr('报名成功','course.info'); ?>',{ icon:1  }, function(){
                                    location.href='//'+location.hostname+"/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>#reg=1";
                                });
                            } else if (r.code == 1021) {
                                layer.open({
                                    type: 2,
                                    title:false,
                                    area: [w, h],
                                    shadeClose: true,
                                    content: ['/layer.main.userLogin/'+cid+'/'+selectedClassId+'/'+classNum+'/'+source+'/'+setId, 'no']
                                });
                                return false;
                            } else {
                                layer.msg(r.errMsg, { icon:1 }, function(){
                                    location.href='//'+location.hostname+"/course/info/show/<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>#reg=0";
                                });
                            }
                        }, "json");
                    }
                }
            }
        };
        $(".registration,.registration-buy,.registration-buyBtn").bind("click" ,function() {
            var objClassId = $(this).attr("classid");
			return autoreg(objClassId);
        })
    //comment
        $(".detail-comments-list .comments-del").each(function() {
            var userUid = $(this).attr("data-uid");
            if (userApi.getUid() == userUid) {
                $(this).removeClass("hidden");
            }
        })

        //select
        $("#selectListName").on("click" ,"a" ,function() {
            var seclectClassId = $(this).attr("classid");
            var selectRegFull = $(this).find(".cRed").html();
            var selectRegFullNum = $(this).find(".cRed").length;
            var selectRegEnd = $(this).find(".courseEnd").html();
            var selectRegEndNum = $(this).find(".courseEnd").length;
            $(".course-divselect cite").html($(this).find("#selectClassName").html());
            $('#classListSixData').find("li").hide();
            $('#classListSixData').find('li:eq('+ $(this).parent(".course-select").index() +')').show();

            if (checkedRegClassId) {
                if(userMemberCourse.isMemberRegType == 0) { //普通身份报名
                        if (checkedRegClassId == seclectClassId) {
                            $(".inClassRoom").show();
                            $(".seal").show();
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $('.registration-buy').hide();
                        } else {
                            $(".s-course-end-btn").hide();
                            $('.registration').hide();
                            $(".s-courseEnd-btn").hide();
                            $(".inClassRoom").hide();
                            $(".seal").hide();
                            $('.registration-buy').hide();
                        }
                        if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                            $(".s-course-end-btn").hide();
                        }
                }else { //会员身份报名
                    if(userMemberCourse.isMember == 0) { //非会员
                        if(setIdStr == '') {
                            if(courseFeeType == 1){ //课程是付费课程
                                if (checkedRegClassId == seclectClassId) {
                                    $(".inClassRoom").hide();
                                    $(".seal").hide();
                                    $(".s-course-end-btn").hide();
                                    $('.registration').hide();
                                    $(".s-courseEnd-btn").hide();
                                    $('.registration-buy').show();
                                } else {
                                    $(".s-course-end-btn").hide();
                                    $('.registration').hide();
                                    $(".s-courseEnd-btn").hide();
                                    $(".inClassRoom").hide();
                                    $(".seal").hide();
                                    $('.registration-buy').hide();
                                }
                                if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                    $(".s-course-end-btn").hide();
                                }
                            }else { //课程免费课
                                if (checkedRegClassId == seclectClassId) {
                                    $(".inClassRoom").hide();
                                    $(".seal").hide();
                                    $(".s-course-end-btn").hide();
                                    $('.registration').show();
                                    $(".s-courseEnd-btn").hide();
                                    $('.registration-buy').hide();
                                } else {
                                    $(".s-course-end-btn").hide();
                                    $('.registration').hide();
                                    $(".s-courseEnd-btn").hide();
                                    $(".inClassRoom").hide();
                                    $(".seal").hide();
                                    $('.registration-buy').hide();
                                }
                                if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                    $(".s-course-end-btn").hide();
                                }
                            }
                        }else { //会员课程
                            if (checkedRegClassId == seclectClassId) {
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $('.registration-buy').show();
                            } else {
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $('.registration-buy').hide();
                            }
                            if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                $(".s-course-end-btn").hide();
                            }
                        }
                    }else { //是会员
                        if(userMemberCourse.isExpire == 0) {  //会员未失效
                            if (checkedRegClassId == seclectClassId) {
                                $(".inClassRoom").show();
                                $(".seal").show();
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $('.registration-buy').hide();
                            } else {
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $('.registration-buy').hide();
                            }
                            if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                $(".s-course-end-btn").hide();
                            }
                        }else { //会员失效
                            if (checkedRegClassId == seclectClassId) {
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $('.registration-buy').show();
                            } else {
                                $(".s-course-end-btn").hide();
                                $('.registration').hide();
                                $(".s-courseEnd-btn").hide();
                                $(".inClassRoom").hide();
                                $(".seal").hide();
                                $('.registration-buy').hide();
                            }
                            if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                                $(".s-course-end-btn").hide();
                            }
                        }
                    }
                }
            } else {
                    $(".s-course-end-btn").hide();
                    $('.registration').hide();
                    $(".s-courseEnd-btn").hide();
                    $(".inClassRoom").hide();
                    $(".seal").hide();
                    $('.registration-buy').hide();

                    if (selectRegFullNum > 0 && selectRegFull === '<?php echo tpl_modifier_tr('报名已满','course.info'); ?>') {
                        $(".s-course-end-btn").show();
                    }else{
                        $('.registration').show();
                    }
            }

            if (selectRegEndNum > 0 && selectRegEnd === '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
                $(".s-courseEnd-btn").show();
                $(".s-course-end-btn").hide();
                $('.registration').hide();
                $(".inClassRoom").hide();
                $(".seal").hide();
                $('.registration-buy').hide();
            }

            if(getPlanCourseTapedIcon) {
                $(".inClassRoom").hide();
                var planCourseEnd = $("#planList").find(".courseEnd:last").html();
                if(planCourseEnd == '<?php echo tpl_modifier_tr('已结束','course.info'); ?>') {
                    $('#classListSixData li:eq(' +$(this).index()+ ')').find('.classProgress').html('<?php echo tpl_modifier_tr('已结束','course.info'); ?>');
                    $('#classListSixData li:eq(' +$(this).index()+ ')').find('.cRed').hide();
                    $(".s-courseEnd-btn").show();
                    $(".s-course-end-btn").hide();
                    $('.registration').hide();
                    $(".inClassRoom").hide();
                    $(".seal").hide();
                    $('.registration-buy').hide();
                }
            }

            $("#classListSixName").find("li").removeClass("curr");
            $("#classListSixName").find("li").each(function() {
                var classId = $(this).attr("classid");
                if(seclectClassId == classId) {
                    $(this).addClass("curr");
                    $(".registration").attr("classid",$(this).attr("classid"));
                    $('.registration-buyBtn').attr("classid",$(this).attr("classid"));
                }
            })
			if(userApi.getCookie("course.autoreg") == <?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>){
				var objClassId = $(".registration").first().attr("classid");
				autoreg(objClassId);
			}
			if(location.hash.indexOf("autoreg")>0){
				var objClassId = $(".registration").first().attr("classid");
				autoreg(objClassId);
			}

        })

        //分享
        var sPic = "<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['thumb']; ?>";
        var sTitle = "<?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['title']); ?>";
        $(".wrap-courseshare").share({
            sPic    : sPic,
            sTitle  : sTitle
        });

        if($(window).width() < 800){
            $('#article-content').find('table,img,td').removeAttr('style');
        }
    })
    $("#courseDetailComment").load("/course.info.comment/<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseId']; ?>?page=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['page']; ?>");
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.detail.js'); ?>"></script>

<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script id="regUserListTpl" type='text/template'>
    <<#regUserList>>
        <li class="col-sm-6 pd0 col-lg-4">
            <img src="<<thumb>>" alt="">
            <p class="s-student-name"><<name>></p>
        </li>
    <</regUserList>>
</script>

<script id='selectListNameTpl' type='text/template'>
    <<#classList>>
        <dd class="course-select">
            <a href="javascript:;" onclick="getPlanListData(<<classId>> ,this)" classId='<<classId>>'>
                <span id="selectClassName"><<className>></span>
                <div style="display:none;">
                    <<&classStatus>>
                </div>
            </a>
        </dd>
    <</classList>>
</script>

<script id='classListOneNameTpl' type='text/template'>
    <<#classList>>
            <div class="col-sm-20 pd0 detail-lh fs14 mt10 cDarkgray">
                <?php echo tpl_modifier_tr('班级','course.info'); ?>： <span class="oneClassName mr20"><<className>></span>
                <em><<&classStatus>></em>
            </div>
            <div class="col-sm-20 pd0 detail-lh fs14 cDarkgray"><?php echo tpl_modifier_tr('主讲','course.info'); ?>：<<teacherName>></div>
            <div class="col-sm-20 pd0 detail-lh fs14 cDarkgray"><?php echo tpl_modifier_tr('课时','course.info'); ?>：<?php echo tpl_modifier_tr('共','course.info'); ?><<planNum>><?php echo tpl_modifier_tr('节','course.info'); ?>
                <span class="ml10 classProgress"><<progress>></span>
            </div>
            <div class="col-sm-20 detail-lh fs14 cDarkgray pd0" title="<<classAddress>>" >
                <<classAddress>>
            </div>
    <</classList>>
</script>

<script id='classListSixNameTpl' type='text/template'>
    <<#classList>>
        <li onmouseover="getPlanListData(<<classId>> ,this)" classId="<<classId>>" class="col-sm-3 col-xs-3 pd0 tac">
            <span><<className>></span>
            <i></i>
        </li>
    <</classList>>
</script>

<script id='classListSixDataTpl' type='text/template'>
    <<#classList>>
        <li class="col-sm-20 col-xs-20">
            <div class="detail-lh">
                <a href='/teacher/detail/entry/<<teacherId>>' class='mr15 c-fl'>主讲：<<teacherName>></a>
                <em class="c-fr mr15"><<&classStatus>></em>
            </div>
            <div class="detail-lh">
                课时：共<<planNum>>节
                <span class="ml10 classProgress"><<progress>></span>
            </div>
            <div class="detail-lh" title="<<classAddress>>">
                <<classAddress>>
            </div>
        </li>
    <</classList>>
</script>

<script id="planListTpl" type="text/template">
    <<#planList>>
        <li class="col-sm-20 fs14 col-xs-20 planList-courseType" courseType=<?php echo SlightPHP\Tpl::$_tpl_vars["data"]['courseInfo']['courseType']; ?>>
            <a href="/course.plan.play/<<planId>>" target="_blank">
                <span class="pdr0 pdr0-x c-fl hidden-xs">
                    <i></i>
                </span>
                    <div class="col-sm-11 col-xs-10 pdr0" >
                    <div class="c-fl col-sm-18 pos-rel course-article-content-title cDarkgray col-xs-20 pd0 pos-rel" data-title="<<&sectionDesc>>">
                        <span class="col-md-4 col-xs-6 pd0"><<sectionName>></span>
                        <div class="col-md-16 col-xs-14 pd0 article-title">
                            <<&sectionDesc>>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-8 pd0 clearfix c-fr col-xs-9">
                        <div class="cGray col-sm-10 pd0 fs12 planStartTime col-xs-15"><<&startTime>></div>
                        <div class="cGray col-sm-10 pd0 ter planstatus"><<&planStatus>></div>
                    </div>
            </a>
        </li>
    <</planList>>
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
