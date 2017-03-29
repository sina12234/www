<!DOCTYPE html>
<html>
<head>
<script>
    console = { };
    console.log = function() { };
</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=9;IE=8;IE=7;IE=edge;chrome=1">
<title><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?> - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - <?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?> - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/bootstrap/bootstrap.min.css'); ?>">
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="/assets_v2/css/bootstrap/bootstrap.min.css">
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/html5shiv.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/respond.min.js'); ?>"></script>
<![endif]-->
<!--[if lt IE 8]>
<script src="<?php echo utility_cdn::js('/assets_v2/js/ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<link rel="stylesheet" type="text/css" id="cssSkin" href="<?php echo utility_cdn::css('/assets_v2/css/player1.css'); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo utility_cdn::img('/assets_v2/img/platform/yunke.ico'); ?>"/>
<script type="text/javascript" src="//www.yunke.com/crossdomain.php"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/swfobject.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/HTML5Player.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/display_optimize.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/chat_list.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/message_v2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/course_common_v2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/course_play_v2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/course_comment_v2.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.growthlayer.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.share.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.cookie.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common/remindCard.js'); ?>"></script>
<script type="text/javascript">
    var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
<?php echo tpl_function_part("/site.main.weixin"); ?>
<?php /*<meta name="weixin" title="" link="" imgurl="" desc=""/>*/?>
<meta name="weixin" imgurl="<?php echo utility_cdn::http(utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["plan_info"]->thumb_big)); ?>" desc="<?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["plan_info"]->title)); ?>"/>
<script type="text/javascript">
    var pyy = false;
    var title = '<?php echo addslashes(SlightPHP\Tpl::$_tpl_vars["plan_info"]->title); ?>';
    var planId = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>;
    var isLogin = <?php echo SlightPHP\Tpl::$_tpl_vars["logined"]; ?>;
    var iswxLogin = <?php echo SlightPHP\Tpl::$_tpl_vars["wxlogined"]; ?>;
    var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>;
    var class_id = <?php echo SlightPHP\Tpl::$_tpl_vars["class_id"]; ?>;
    var userId = parseInt(<?php echo SlightPHP\Tpl::$_tpl_vars["user_id"]; ?>);
    var userOwner = parseInt(<?php echo SlightPHP\Tpl::$_tpl_vars["user_owner"]; ?>);
    var isReg = <?php echo SlightPHP\Tpl::$_tpl_vars["isReg"]; ?>;
    var userToken = "<?php echo SlightPHP\Tpl::$_tpl_vars["user_token"]; ?>";
    var userFlag = userToken.substring(0, 5);
    var userThumb = "<?php echo SlightPHP\Tpl::$_tpl_vars["user_thumb"]; ?>";
    var filecdn = "<?php echo SlightPHP\Tpl::$_tpl_vars["filecdn"]; ?>";
    var chatPull = "<?php echo utility_cdn::chat_pull(); ?>";
    var chatWs = "<?php echo utility_cdn::chat_ws(); ?>";
    var chatPullSet = "<?php echo utility_cdn::chat_pullset(); ?>";
    var myHost = "<?php echo SlightPHP\Tpl::$_tpl_vars["myHost"]; ?>";
    var isLiving = <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status=="living"){; ?>true<?php }else{; ?>false<?php }; ?>;
    var teacherName = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>'<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>'<?php }else{; ?>'<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?><?php }else{; ?><?php }; ?>'<?php }; ?>;
    var teacherId = '<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->uid)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->uid; ?><?php }; ?>';
    var teacherThumb = '<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->medium; ?>';
    var levelshow ="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->fk_level)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->fk_level; ?><?php }; ?>";
    var leveltitleshow="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->title; ?><?php }; ?>";
    var fee_type = <?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->fee_type; ?>;
    var isSign=<?php echo SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']; ?>;
    wo.remindCard(undefined,planId);
    var displayAll = <?php echo SlightPHP\Tpl::$_tpl_vars["displayAll"]; ?>;
    var groupId = <?php echo SlightPHP\Tpl::$_tpl_vars["groupId"]; ?>;
    if(!isReg || !groupId){
        displayAll = 1;
    }
    var groupName = "<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>";
    var groupUid = <?php echo SlightPHP\Tpl::$_tpl_vars["groupUid"]; ?>;

    if ("/" != filecdn[filecdn.length - 1]) {
        filecdn += "/";
    }
    var message;
        $(document).ready(function () {
            if (userId) {
                $("#href_logout").attr("target", "");
            }
            $(window).resize(function () {
                var w = $("#playerContent").innerWidth();
                var h = parseInt(w / 16 * 9);
                var box_h = $(window).height() - 80;
                if (h > box_h) {
                    h = box_h;
                }
                playerbox();
            }).trigger("resize");
            //fix ie8 trigger (twice)
            setTimeout(function () {
                $(window).trigger("resize");
            }, 50);

            $("#fullscreen").click(function () {
                FullScreen.toggle();
            });

            var flashvars = {
                auto_play: true,
                stream_type: "高清",
                fullscreen_func: "FullScreen.toggle",
                chat_publisher_func: "message.flash_callback",
                microphone_func: "message.microphone_callback",
                player_fullscreen_func: "message.flash_fullscreen_callback",
                buy_box_func: "message.flash_buy_box_callback",
                error_func: "Player.error",
                video_end: "message.flash_end_callback",
                camera_handle: "message.flash_camera_handle",
                plan_id: "<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>",
                showPlayerMessage: "showPlayerMessage",
                hidePlayerMessage: "hidePlayerMessage",
                stopRecord:"message.stopRecord"
            };

            var params = {
                menu: "false",
                scale: "noScale",
                allowFullscreen: "true",
                allowScriptAccess: "always",
                bgcolor: "",
                wmode: "opaque"
            };

            var attributes = {
                id: "player",
                name: "player"
            };

            swfobject.embedSWF(
                    "<?php echo utility_cdn::swf('/assets/swf/Player.swf'); ?>",
                    "player", "100%", "100%", "13.0.0",
                    "<?php echo utility_cdn::swf('/assets/swf/expressInstall.swf'); ?>",
                    flashvars, params, attributes, function (r) {
                        if (r.success == false) {
                            H5Player.init(planId, "");
                        }
                    }
            );

            message = planPlay();
            <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->status == 3){; ?>
            $("#section").find("[plan_id=" + planId + "]").children("p").removeClass('play-living-status').addClass('play-back-status').html($("<strong class='fs14'><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_descipt; ?></strong><span class='sicon-3' ><?php echo tpl_modifier_tr('播放中','course.play'); ?></span>"));
            $("#section").find("[plan_id=" + planId + "]").children("p").find(".sicon-2").remove();
            <?php }; ?>

            <?php if((SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name<>SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']) && !empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['className'])){; ?>
            $("#chat_input").html("您进错教室了，<a href='/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["sameSectionPlanInfo"]->plan_id; ?>' style='color:#f93'>点击进入'<?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']; ?>'</a>");
            <?php }; ?>
    });
</script>
<script>
    $(document).ready(function(e){
        var course_type=<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_type; ?>;
        var source= "reg";
        var courseFeeType = <?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType']; ?>;

        if($(window).width() < 800) {
            if(userApi.isLogin()) {
                 $('#mobile-login-ct').hide();
                 if(isSign){
                    $('#login-layer').show();
                 }else{
                    $('#login-layer').hide();
                 }
            }else {
                $('#mobile-login-ct').show();
                $('#login-layer').hide();
            }
        }else {
            $('#mobile-login-ct').hide();
            $('#login-layer').hide();
        }


        /* 马上开通， 立即续费， 重新开通 */
        $("#open-vip,#renew-vip,#re-vip").click(function(){
             var setId = $(this).parent('p').attr('setId');
             parent.location.href = "/order.main.memberinfo/"+setId;
        });

        /* 立即报名 */
        $("#regcheck1,#regcheck2,#regcheck3,#regcheck4,#regcheck5,#regcheck6,#re-Enroll,#login-layer").click(function(){
            var w,h;
            if($(window).width()>800){
                w='476px';
                h='360px';
            }else{
                w='90%';
                h='400px';
            }
            if(userApi.isLogin()){
                if (courseFeeType == 0 ) {
                        $.post("/course/info/AddReg", { classId: class_id ,cid: courseId }, function (r) {
                            if (r.code == 0) {
                                layer.msg('报名成功',{ icon:1  }, function(){
                                    location.reload();
                                });
                            } else if (r.code == 1021) {
                                layer.open({
                                    type: 2,
                                    title: false,
                                    area: [w, h],
                                    shadeClose: true,
                                    content: ['/layer.main.userLogin/'+courseId+'/'+class_id+'/0/'+source+'/0/', 'no']
                                });
                                return false;
                            }  else {
                                layer.msg(r.errMsg, function(){
                                    location.reload();
                                });
                            }
                        }, "json");
                } else if (courseFeeType == 1) {
                    $.post("/course/pay/check", { classId:class_id ,cid:courseId }, function (r) {
                        if (r.code == 0) {
                            parent.location.href = "/order.main.buy/course/"+courseId+"/"+class_id ;
                        } else {
                            layer.msg(r.errMsg, function(){
                                location.reload();
                            });
                        }
                    }, "json");
                }
        //如果未登录
        }else{
            if (userApi.isWeiXin()) {
                userApi.setCookie("course.autoreg",<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>)
                location.replace("/weixin.user.login/?url=//"+location.hostname+"/course.info.show/"+courseId);
            } else {
                if($(window).width() < 800){
                    userApi.setCookie("course.autoreg",<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>)
                    location.replace("/site.main.login/?url=//"+location.hostname+"/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>")
                }else{
                    layer.open({
                        title:false,
                        type: 2,
                        area: [w, h],
                        shadeClose: true,
                        content: ['/layer.main.userLogin/'+courseId+'/'+class_id+'/0/'+source+'/0/', 'no']
                    });
                }
            }
        //弹登录层
        }
        return false;
    });
});
</script>
</head>
<body>
<header class="header hidden-xs hidden-sm">
    <div class="header-main">
        <div class="g-title">
            <div class="g-play-icon"></div>
            <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?>&nbsp;&nbsp;&nbsp;<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?><?php }; ?>&nbsp;&nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_name; ?></a><span id="live-status"></span>
        </div>
        <div class="g-teacher">
            <a href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->uid; ?>" target="_blank">
                <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->small){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->small; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                <span class="g-teacName"><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?></span>
                <?php }else{; ?>
                <span class="g-teacName"><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?></span>
                <?php }; ?>
            </a>
        </div>
        <div class="g-navright fs12">
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"])){; ?>
            <!--用户信息-->
            <ul class="user-menu hidden-xs">
                <li class="user-menu-info">
                    <a href="#"><span><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?></span></a><span class="c-fr">|</span>
                    <ul>
                        <i class="arrow-up"></i>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
                        <li class="hidden-xs hidden-sm">
                            <a href="/org" target="_blank"><span class="t-set-icon"></span><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a>
                        </li>
                        <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
                        <li class="hidden-xs hidden-sm">
                            <a href="/teacher.course.timetable" target="_blank"><span class="t-set-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
                        </li>
                        <?php }else{; ?>
                        <li class="hidden-xs hidden-sm">
                            <a href="/student.main.growth" target="_blank"><span class="School-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
                        </li>
                        <?php }; ?>
                        <li><a href="/student.main.infobase" target="_blank"><span class="compose-icon"></span><span><?php echo tpl_modifier_tr('基础资料','site.header'); ?></span></a></li>
                        <li><a href="/student.order.myorder" target="_blank"><span class="order-icon"></span><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a></li>
                        <li><a href="/student.security.password" target="_blank"><span class="card-icon"></span><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a></li>
                        <li class="visible-sm"><a href="/user.message" target="_blank"><span class="mess-icon"></span><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a></li>
                        <li><a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a></li>
                    </ul>
                </li>
                <li class="hidden-xs hidden-sm">
                    <a href="/user.message" target="_blank"><span class="msg-icon"></span>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"])){; ?>
                        <span class="msg-num"></span>
                        <?php }; ?>
                    </a>
                </li>
            </ul>
            <?php }else{; ?>
            <!--登录-->
            <div class="login">
                <a href="/site.main.login" id="login"><?php echo tpl_modifier_tr('登录','site.header'); ?></a> | <a href="/site.main.register"><?php echo tpl_modifier_tr('注册','site.header'); ?></a>
            </div>
            <?php }; ?>
        </div>
    </div>
</header>
<!-- coursebox -->
<div class="c" id="content">
    <div id="coursebox" class="coursebox">
            <div class="course-total">
                <!--直播课结束层 开始-->
                <!--<div class="course-box" style="height:100%;display:none;" id="courseingend">-->
                <div class="course-box"  id="courseingend" style="display:none">
                    <div class="discuss">
                        <div class="discuss-m col-sm-17">
                            <div class="d-tips">
                                <div class="img col-sm-17"><img src="<?php echo utility_cdn::img('/assets_v2/img/yun.png'); ?>"></div>
                                <div class="title">
                                    <span class="fs22">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name'])){; ?>
											<?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name']; ?>
                                        <?php }else{; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['name'])){; ?>
                                                <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?>
                                            <?php }else{; ?>
                                                <?php echo tpl_modifier_tr('游客','course.play'); ?>
                                            <?php }; ?>
                                        <?php }; ?>
                                        <?php echo tpl_modifier_tr('同学，下课喽！','course.play'); ?>
                                    </span>
                                    <span class="fs16 cWhite">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/d-star.png'); ?>" alt=""><?php echo tpl_modifier_tr('本节共获得老师点赞','course.play'); ?><b class="cPink" id="courseinggood">0</b><?php echo tpl_modifier_tr('次','course.play'); ?> <img src="<?php echo utility_cdn::img('/assets_v2/img/d-star.png'); ?>"></span>
                                    <span><?php echo tpl_modifier_tr('稍后可以在章节查看本节录像哦','course.play'); ?>~</span>
                                </div>
                            </div>
                            <div class="d-rank hidden-xs fs14">
                                <div class="an-rank col-sm-7">
                                    <div class="t">(<?php echo tpl_modifier_tr('答题统计','course.play'); ?>)</div>
                                    <ul class="an-list">
                                        <li>
                                            <p><span><img src="<?php echo utility_cdn::img('/assets_v2/img/r2.png'); ?>" alt=""></span></p><?php echo tpl_modifier_tr('共计问题','course.play'); ?>:<i id="countAll">20</i><?php echo tpl_modifier_tr('道','course.play'); ?>
                                        </li>
                                        <li>
                                            <p><span><img src="<?php echo utility_cdn::img('/assets_v2/img/r.png'); ?>" alt=""></span></p><?php echo tpl_modifier_tr('答对','course.play'); ?>:<span class="cPink fs16" id="countCorrect">10</span> <?php echo tpl_modifier_tr('道','course.play'); ?>
                                        </li>
                                        <li>
                                            <p><span><img src="<?php echo utility_cdn::img('/assets_v2/img/r.png'); ?>" alt=""></span></p><?php echo tpl_modifier_tr('答错','course.play'); ?>:<span class="cPink fs16" id="countMistake">10</span> <?php echo tpl_modifier_tr('道','course.play'); ?>
                                        </li>
                                        <li>
                                            <p><span><img src="<?php echo utility_cdn::img('/assets_v2/img/r.png'); ?>" alt=""></span></p><?php echo tpl_modifier_tr('未答','course.play'); ?>:<span class="cPink fs16" id="countMiss">10</span> <?php echo tpl_modifier_tr('道','course.play'); ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="c-rank col-sm-7">
                                    <div class="t"><?php echo tpl_modifier_tr('你的班级排名','course.play'); ?>:<b class="cPink" id="courseingsort">第0名</b></div>
                                    <ul class="rank-list" id="endinggoodlist"></ul>
                                </div>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"] && SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                            <!--开始-->
                            <div class="i-comment hidden-xs" id="score_comment" style="display:none;">
                                <div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>
                                <div class="rate">
                                    <strong id="avg_1">5.0</strong>
                                    <br><span class="fs16"><?php echo tpl_modifier_tr('综合评分','course.play'); ?></span>
                                </div>
                                <div class="percent fs14" id="score_percent">
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('学生满意度','course.play'); ?>：</dt>
                                        <dd>
                                            <span class="sel" data-title="很差" data-score="1"></span>
                                            <span data-title="差" data-score="2" class="sel"></span>
                                            <span data-score="3" data-title="还行" class="sel"></span>
                                            <span data-score="4" data-title="满意" class="sel"></span>
                                            <span data-score="5" data-title="很好" class="sel"></span>
                                            <i score_type="student_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('课程与描述相符','course.play'); ?>：</dt>
                                        <dd>
                                            <span class="sel" data-score="1" data-title="很差"></span>
                                            <span data-score="2" data-title="差" class="sel"></span>
                                            <span data-score="3" data-title="还行" class="sel"></span>
                                            <span data-score="4" data-title="满意" class="sel"></span>
                                            <span data-score="5" data-title="很好" class="sel"></span>
                                            <i score_type="desc_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('你的班级排名','course.play'); ?>：</dt>
                                        <dd>
                                            <span class="sel" data-score="1" data-title="很差"></span>
                                            <span data-score="2" data-title="差" class="sel"></span>
                                            <span data-score="3" data-title="还行" class="sel"></span>
                                            <span data-score="4" data-title="满意" class="sel"></span>
                                            <span data-score="5" data-title="很好" class="sel"></span>
                                            <i score_type="explain_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="i-comment hidden-xs" id="ret_score_comment" style="display:none;">
                                <div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>
                                <div class="rate">
                                    <strong id="ret_avg_1">5.0</strong>
                                    <br><span class="fs16"><?php echo tpl_modifier_tr('综合评分','course.play'); ?></span>
                                </div>
                                <div class="percent fs14">
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('学生满意度','course.play'); ?>：</dt>
                                        <dd id="student_score">
                                            <span class="sel" data-title="很差" data-score="1"></span>
                                            <span data-title="差" class="sel" data-score="2"></span>
                                            <span data-score="3" class="sel" data-title="还行"></span>
                                            <span data-score="4" class="sel" data-title="满意"></span>
                                            <span data-score="5" class="sel" data-title="很好"></span>
                                            <i score_type="student_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('课程与描述相符','course.play'); ?>：</dt>
                                        <dd id="desc_score">
                                            <span class="sel" data-score="1" data-title="很差"></span>
                                            <span data-score="2" class="sel" data-title="差"></span>
                                            <span data-score="3" class="sel" data-title="还行"></span>
                                            <span class="sell" data-score="4" data-title="满意"></span>
                                            <span data-score="5" data-title="很好"></span>
                                            <i score_type="desc_score" class="sel" data-score="5"></i>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('老师的讲解表达','course.play'); ?>：</dt>
                                        <dd id="explain_score">
                                            <span class="sel" data-score="1" data-title="很差"></span>
                                            <span data-score="2" class="sel" data-title="差"></span>
                                            <span data-score="3" class="sel" data-title="还行"></span>
                                            <span data-score="4" class="sel" data-title="满意"></span>
                                            <span class="sel" data-score="5" data-title="很好"></span>
                                            <i score_type="explain_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="t-area hidden-xs" id="textComment" style="display:none;">
                                <div class="textarea" contenteditable="true" style="overflow:auto;"
                                     id="comment_input"></div>
                                <button id="comment_send"><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                            </div>
                            <div id="ret_textComment" style="display:none;">
                                <ul class="comments-list">
                                    <li>
                                        <div class="user-info col-md-4 col-sm-8 col-xs-8">
                                            <p>
                                                <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['medium'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['medium']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets/images/defaultPhoto1.png'); ?><?php }; ?>">
                                            </p>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name'])){; ?>
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name']; ?>
                                            <?php }else{; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['name'])){; ?>
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?>
                                            <?php }else{; ?>
                                            <?php echo tpl_modifier_tr('游客','course.play'); ?>
                                            <?php }; ?>
                                            <?php }; ?>
                                        </div>
                                        <div class="p-comment col-md-17 col-sm-12 col-xs-12">
                                            <div class="user-item cGray">
                                                <span><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></span>
                                                <span>班主任：
                                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                                                        <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>
                                                    <?php }else{; ?>
                                                        <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>
                                                    <?php }; ?>
                                                </span>
                                                <span>(评分：<span id="ret_avg_12">4.0</span>)</span>
                                            </div>
                                            <p class="desc" id="ret_comment_input">非常好 </p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="ui-page">
                                    <a rel="2" href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank">更多>></a>
                                </div>
                            </div>
                            <!--结束-->
                            <?php }; ?>
                        </div>
                    </div>
                </div>
                <!--直播课结束层 结束-->
                <!--录播课结束层 开始-->
                <div class="course-box3" id="courseend" style="display:none">
                    <div class="tips" >
                        <div class="tips-text3 col-sm-7">
                            <p><img src="<?php echo utility_cdn::img('/assets_v2/img/flower.png'); ?>"></p>
                            <p class="time-tips hidden-xs" id="nextTime">距离下一节开课时间 4月15日</p>
                            <div class="div-btn">
                                <span class="fs22"><?php echo tpl_modifier_tr('本节学习完了','course.play'); ?></span>
                                <a class="col-sm-10 col-xs-8 btn fs16 auto flnone" id="nextPlan">
                                    <?php echo tpl_modifier_tr('学习下一节','course.play'); ?>
                                </a>
                                <a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>" class="col-sm-10 col-xs-8 fs16 cWhite auto flnone">
                                    <?php echo tpl_modifier_tr('重新学习','course.play'); ?>
                                </a>
                            </div>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["logined"] && SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                        <!--开始-->
                        <div class="i-comment" id="score_comment1" style="display: none;">
                            <div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>
                            <div class="rate">
                                <strong id="avg_11">5.0</strong>
                                <br><span class="fs16"><?php echo tpl_modifier_tr('综合评分','course.play'); ?></span>
                            </div>
                            <div class="percent fs14" id="score_percent1">
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('学生满意度','course.play'); ?>：</dt>
                                    <dd>
                                        <span class="sel" data-title="很差" data-score="1"></span>
                                        <span data-title="差" data-score="2" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="student_score" data-score="5"></i>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('课程与描述相符','course.play'); ?>：</dt>
                                    <dd>
                                        <span class="sel" data-score="1" data-title="很差"></span>
                                        <span data-score="2" data-title="差" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="desc_score" data-score="5"></i>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('老师的讲解表达','course.play'); ?>：</dt>
                                    <dd>
                                        <span class="sel" data-score="1" data-title="很差"></span>
                                        <span data-score="2" data-title="差" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="explain_score" data-score="5"></i>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="i-comment" id="ret_score_comment1" style="display:none;">
                            <div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>
                            <div class="rate">
                                <strong id="ret_avg_11">5.0</strong>
                                <br><span class="fs16"><?php echo tpl_modifier_tr('综合评分','course.play'); ?></span>
                            </div>
                            <div class="percent fs14">
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('学生满意度','course.play'); ?>：</dt>
                                    <dd id="student_score1">
                                        <span class="sel" data-title="很差" data-score="1"></span>
                                        <span data-title="差" data-score="2" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="student_score" data-score="5"></i>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('课程与描述相符','course.play'); ?>：</dt>
                                    <dd id="desc_score1">
                                        <span class="sel" data-score="1" data-title="很差"></span>
                                        <span data-score="2" data-title="差" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="desc_score" data-score="5"></i>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('老师的讲解表达','course.play'); ?>：</dt>
                                    <dd id="explain_score1">
                                        <span class="sel" data-score="1" data-title="很差"></span>
                                        <span data-score="2" data-title="差" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="explain_score" data-score="5"></i>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="t-area" id="textComment1" style="display:none;">
                            <div class="textarea" contenteditable="true" style="overflow:auto;" id="comment_input1"></div>
                            <button id="comment_send1"><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                        </div>
                        <div  id="ret_textComment1" style="display:none;">
                            <ul class="comments-list">
                                <li>
                                    <div class="user-info col-md-4 col-sm-8 col-xs-8">
                                        <p>
                                            <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['medium'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['medium']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets/images/defaultPhoto1.png'); ?><?php }; ?>">
                                        </p>
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name'])){; ?>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name']; ?>
                                        <?php }else{; ?>
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['name'])){; ?>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?>
                                        <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('游客','course.play'); ?>
                                        <?php }; ?>
                                        <?php }; ?>
                                    </div>
                                    <div class="p-comment col-lg-18 col-md-17 col-sm-12 col-xs-12">
                                        <div class="user-item cGray"><span><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></span><span>班主任：<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?></span>
                                            <span>(评分：<span id="ret_avg_111">4.0</span>)</span></div>
                                        <p class="desc" id="ret_comment_input1">老师讲的非常好 </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="ui-page">
                                <a rel="2" href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank">更多>></a>
                            </div>
                        </div>
                        <!--结束-->
                        <?php }; ?>
                    </div>
                </div>

                <div class="course-box2" id="courseing">
                    <div class="alert-layer" id="call_buff" style="display:none">
                        <div class="alert-bg2"></div>
                        <div class="alert-content2">
                            <div class="alert-title">
                                <span class="alert-close-btn" onclick="$('#call_buff').hide()"></span>
                                <span class="alert-title-txt"><span></span>云课网</span>
                            </div>
                            <div class="alert-main2">
                                <div class="alert-mark"></div>
                                <div class="alert-mark-txt fs14">
                                    <p>获取课程信息失败，请刷新页面。</p>
                                    <p class="tec"><span></span>点击刷新</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["needdatainfo"]){; ?>
                    <?php }else{; ?>
                    <div class="course-player" id="playerContent">
                        <div id="player"></div>
                    </div>
                    <?php }; ?>

                    <div class="overlay" id='fee_remind' style="display:none">
                        <div class="o-layer">
                            <p class="o-vip-tips fs18">试听时间到了，报名后可观看完整视频</p>
                            <p class="o-tips fs16"><?php echo tpl_modifier_tr('课程信息','course.play'); ?></p>
                            <p class="o-title fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('班级','course.play'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('主讲老师','course.play'); ?>：
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>
                                <?php }else{; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>
                                <?php }; ?>
                            </p>
                            <div class="o-item">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType'] == 0){; ?>
                                <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                <?php }else{; ?>
                                <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                                <div class="vip-a">
                                    会员课程
                                    <div class="vip-info">
                                        <i></i>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"] as SlightPHP\Tpl::$_tpl_vars["k"] => SlightPHP\Tpl::$_tpl_vars["member"]){; ?>
                                        <p setId="<?php echo SlightPHP\Tpl::$_tpl_vars["member"]['setId']; ?>">
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["member"]['title']; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]])){; ?>
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]]['is_expire'] == 0){; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="renew-vip">
                                                <?php echo tpl_modifier_tr('立即续费','course.info'); ?>
                                            </a>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="re-vip">
                                                <?php echo tpl_modifier_tr('重新开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="open-vip">
                                                <?php echo tpl_modifier_tr('马上开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                        </p>
                                        <?php }; ?>
                                    </div>
                                </div>
                                <?php }; ?>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"]){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck3" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck3" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16">注册</a>
                            <?php }; ?>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showDivInvalidMember']){; ?>
                        <div class="o-vip-end fs14" id="o-vip-end">
                            <i class="vip-tips"></i>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>
                            会员已失效，继续学习请
                                <a href="/member.list" class="cYellow" id="re-vip">重新开通</a> 会员 <a href="#" class="o-close-btn" id="o-close-btn"></a>
                            <?php }else{; ?>
                            会员已停用，继续学习请
                                <span class="cYellow" id="re-Enroll">重新报名课程</span> <a href="#" class="o-close-btn"></a>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                    </div>
                    <div class="overlay" id='sign_remind' style="display:none">
                        <div class="o-layer">
                            <p class="o-vip-tips fs18">试听时间到了，报名后可观看完整视频</p>
                            <p class="o-tips fs16"><?php echo tpl_modifier_tr('课程信息','course.play'); ?></p>
                            <p class="o-title fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('班级','course.play'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('主讲老师','course.play'); ?>：
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>
                                <?php }else{; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>
                                <?php }; ?>
                            </p>
                            <div class="o-item">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType'] == 0){; ?>
                                <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                <?php }else{; ?>
                                <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                                <div class="vip-a">
                                    会员课程
                                    <div class="vip-info">
                                        <i></i>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"] as SlightPHP\Tpl::$_tpl_vars["k"] => SlightPHP\Tpl::$_tpl_vars["member"]){; ?>
                                        <p setId="<?php echo SlightPHP\Tpl::$_tpl_vars["member"]['setId']; ?>">
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["member"]['title']; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]])){; ?>
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]]['is_expire'] == 0){; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="renew-vip">
                                                <?php echo tpl_modifier_tr('立即续费','course.info'); ?>
                                            </a>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="re-vip">
                                                <?php echo tpl_modifier_tr('重新开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="open-vip">
                                                <?php echo tpl_modifier_tr('马上开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                        </p>
                                        <?php }; ?>
                                    </div>
                                </div>
                                <?php }; ?>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"]){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16">注册</a>
                            <?php }; ?>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showDivInvalidMember']){; ?>
                        <div class="o-vip-end fs12">
                            <i class="vip-tips"></i>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>
                            会员已失效，继续学习请
                                <a href="/member.list" class="cYellow" id="re-vip">重新开通</a> 会员 <a href="#" class="o-close-btn" id="o-close-btn"></a>
                            <?php }else{; ?>
                            会员已停用，继续学习请
                                <span class="cYellow" id="re-Enroll">重新报名课程</span> <a href="#" class="o-close-btn"></a>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                    </div>
                    <div class="overlay" id='login_remind' style="display:none">
                        <div class="o-layer">
                            <p class="o-vip-tips fs18">试听时间到了，报名后可观看完整视频</p>
                            <p class="o-tips fs16"><?php echo tpl_modifier_tr('课程信息','course.play'); ?></p>
                            <p class="o-title fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('班级','course.play'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('主讲老师','course.play'); ?>：
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>
                                <?php }else{; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>
                                <?php }; ?>
                            </p>
                            <div class="o-item">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType'] == 0){; ?>
                                <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                <?php }else{; ?>
                                <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                                <div class="vip-a">
                                    会员课程
                                    <div class="vip-info">
                                        <i></i>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"] as SlightPHP\Tpl::$_tpl_vars["k"] => SlightPHP\Tpl::$_tpl_vars["member"]){; ?>
                                        <p setId="<?php echo SlightPHP\Tpl::$_tpl_vars["member"]['setId']; ?>">
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["member"]['title']; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]])){; ?>
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]]['is_expire'] == 0){; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="renew-vip">
                                                <?php echo tpl_modifier_tr('立即续费','course.info'); ?>
                                            </a>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="re-vip">
                                                <?php echo tpl_modifier_tr('重新开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="open-vip">
                                                <?php echo tpl_modifier_tr('马上开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                        </p>
                                        <?php }; ?>
                                    </div>
                                </div>
                                <?php }; ?>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"]){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck5" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck5" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16">注册</a>
                            <?php }; ?>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showDivInvalidMember']){; ?>
                        <div class="o-vip-end fs12">
                            <i class="vip-tips"></i>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>
                            会员已失效，继续学习请
                                <a href="/member.list" class="cYellow" id="re-vip">重新开通</a> 会员 <a href="#" class="o-close-btn" id="o-close-btn"></a>
                            <?php }else{; ?>
                            会员已停用，继续学习请
                                <span class="cYellow" id="re-Enroll">重新报名课程</span> <a href="#" class="o-close-btn"></a>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                    </div>

                    <div class="overlay" id='tryPlay' style="display:none">
                        <div class="o-layer">
                            <div class="video-tip-info">
                                <p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["playMsgInfo"]['msg']; ?></p>
                                <p class="fs16">
                                <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" class="btn-yellow">返回课程页</a>
                                <a href="/course.list" class="cYellow mt25 ml10">去找课</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="overlay" id='tryOverLay' style="display:none">
                        <div class="o-layer">
                            <!--<p class="o-vip-tips fs18">试听时间到了，报名后可观看完整视频</p>-->
                            <p class="o-tips fs16"><?php echo tpl_modifier_tr('课程信息','course.play'); ?></p>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']) && (SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']!=SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name) && empty(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll'])){; ?>
                            <p class="o-title fs16">您已报名“<?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']; ?>”，如需报名<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?>请联系机构调班</p>
                            <?php }; ?>
                            <p class="o-title fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('班级','course.play'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?></p>
                            <p class="o-item"><?php echo tpl_modifier_tr('主讲老师','course.play'); ?>：
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?>
                                <?php }else{; ?>
                                <?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?>
                                <?php }; ?>
                            </p>
                            <div class="o-item">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType'] == 0){; ?>
                                <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                <?php }else{; ?>
                                <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                                <div class="vip-a">
                                    会员课程
                                    <div class="vip-info">
                                        <i></i>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"] as SlightPHP\Tpl::$_tpl_vars["k"] => SlightPHP\Tpl::$_tpl_vars["member"]){; ?>
                                        <p setId="<?php echo SlightPHP\Tpl::$_tpl_vars["member"]['setId']; ?>">
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["member"]['title']; ?>
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]])){; ?>
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['userMemberSet'][SlightPHP\Tpl::$_tpl_vars["k"]]['is_expire'] == 0){; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="renew-vip">
                                                <?php echo tpl_modifier_tr('立即续费','course.info'); ?>
                                            </a>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="re-vip">
                                                <?php echo tpl_modifier_tr('重新开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                            <?php }else{; ?>
                                            <a href="javascript:void(0)" class="cYellow" id="open-vip">
                                                <?php echo tpl_modifier_tr('马上开通','course.info'); ?>
                                            </a>
                                            <?php }; ?>
                                        </p>
                                        <?php }; ?>
                                    </div>
                                </div>
                                <?php }; ?>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"]){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16">注册</a>
                            <?php }; ?>
                        </div>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showDivInvalidMember']){; ?>
                        <div class="o-vip-end fs12">
                            <i class="vip-tips"></i>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>
                            会员已失效，继续学习请
                                <a href="/member.list" class="cYellow" id="re-vip">重新开通</a> 会员 <a href="#" class="o-close-btn" id="o-close-btn"></a>
                            <?php }else{; ?>
                            会员已停用，继续学习请
                                <span class="cYellow" id="re-Enroll">重新报名课程</span> <a href="#" class="o-close-btn"></a>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                    </div>
                </div>

                <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->status==1){; ?>
                <div class="course-box" id="coursebefore" style="z-index:1">
                    <div class="tips">
                        <div class="tips-text">
                            <p><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->img; ?>"></p>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["beforeStart"]->plandesc)){; ?>
                            <p class="fs24"><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->plandesc; ?></p>
                            <?php }; ?>
                            <p class="fs16" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["beforeStart"]->seconds)){; ?>second ="<?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->seconds; ?>"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->time_countdown; ?></br><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["beforeStart"]->section_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->section_name; ?> <?php }; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["beforeStart"]->section_desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->section_desc; ?><?php }; ?> </p>
                        </div>
                    </div>
                </div>
                <?php }; ?>
                <!-- 课堂小测试 -->
                <div id="course_test" class="course-test hidden-xs" style="display:none;">
                    <div class="bar fs16" id="course_title">随堂小测试 <a href="javascript:void(0);" class="course-test-close"></a></div>
                    <div class="course-test-c" id="course_main"></div>
                </div>
                <div class="handle-area">
                    <!-- 评论弹窗引导
                        <div class="fixed-note-tip">
                            <div class="note-tip-box">
                                <a href="javascript:;"></a>
                            </div>
                        </div>
                     /评论弹窗引导 -->
                    <!-- 速笔记
                        <div class="qute-note">
                            <form action="" method="">
                                <div class="wrap-note">
                                    <textarea type="text" class="bor1px note-ipt" placeholder="开始记笔记吧..." ></textarea>
                                    <button class="sub-note-btn">速记</button>
                                </div>
                            </form>
                        </div>
                    速笔记 -->
                    <!-- 评论icon-->
                    <div class="share-mobile fs14 " id="share-mobile">
                        <span class="mobile-icon"></span>手机看
                        <div class="share-mobile-img">
                            <div class="close-img"></div>
                            <img class="share-mobile-bg" src="<?php echo utility_cdn::js('/assets_v2/img/share-mobile-skin.png'); ?>" alt="">
                        </div>
                    </div>
                    <div class="share-area fs14" id="share-area">
                        <span class="play-share-icon"></span>分享
                        <div class="share-sns">
                            <div class="close-img"></div>
                            <a href="javascript:;" class="share-qq" data-cmd="tqq" title="分享到QQ"></a>
                            <a href="javascript:;" class="share-weixin" data-cmd="weixin" title="分享到微信"></a>
                            <a href="javascript:;" class="share-tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                        </div>
                    </div>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["logined"] && SlightPHP\Tpl::$_tpl_vars["isReg"] && SlightPHP\Tpl::$_tpl_vars["plan_info"]->status != 1){; ?>
                    <div class="comment-area" id="layer-comment">
                        <!-- <span class="comment-icon" id="layer-comment"></span> -->
                        <span class="score-comment-icon"></span> 评论
                    </div>
                    <?php }; ?>
                    <script>
                        var wx_href='<?php echo SlightPHP\Tpl::$_tpl_vars["qrCode"]; ?>';
                        $(function () {
                            $(".share-area").share({
                                sPic    : '<?php echo SlightPHP\Tpl::$_tpl_vars["shareImg"]; ?>',
                                wxHref  : wx_href
                            });//分享
                        })
                    </script>
                    <!--复制
                        <input type="hidden" id="copyUrl">
                        <span class="copyurl" onclick="javascript:copyUrl('copyUrl');">复制链接</span>
                    -->
                </div>
                <div class="detect-zoom" id="detect-zoom"><i class="white-tips"></i>显示比列已缩放，影响正常上课，请按<span> Crtl - </span>恢复</div>
        </div>
    </div>
    <!-- toolbar -->
    <div id="tool" class="tool">
        <!-- toolmain -->
        <div id="toolbar-main" class="toolbar-main">
            <div class="section" id="section" style="height:0px">
                <div class="section-num hidden-xs"><?php echo tpl_modifier_tr('章节列表','course.play'); ?></div>
                <div class="section-c" id="sectionc">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plans_info"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["plans_info"] as SlightPHP\Tpl::$_tpl_vars["_planinfo"]){; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status==3){; ?>
                    <a plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>">
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status==2)){; ?>
                    <a plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>">
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==1)){; ?>
					<a onmouseover="this.style.cssText='background:none;'">
                    <?php }else{; ?>
                    <a>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==2){; ?>
                    <p class="play-living-status"><strong class="fs14" ><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <span class="sicon-3" ><?php echo tpl_modifier_tr('正在直播','course.play'); ?></span>
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==1)){; ?>
                    <p><strong class="fs14" style="color:#666666"><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <span class="data-status c-fr"></span>
                    <span class="sicon-4"><?php echo tpl_modifier_tr('未开课','course.play'); ?></span>
                    <?php }else{; ?>
                    <p><strong class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <span class="data-status c-fr"></span>
                    <span class="sicon-2"></span><?php }; ?></p>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->type_id !=2){; ?>
                    <span class="kstime cDarkgray"><?php echo date("m月d日 H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->start_time)); ?></span>
                    <?php }; ?>
                    </a>
                    <?php }; ?>
                    <?php }; ?>
                </div>
            </div>
            <div class="chat" id="chat" style="height:0px">
                <?php if(SlightPHP\Tpl::$_tpl_vars["groupId"] > 0){; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["displayAll"]){; ?>
                    <div class="chat-num curr col-xs-10" id="student_total"><?php echo tpl_modifier_tr('总在线人数','course.play'); ?>：<span class="cGray"><b online>1</b></span></div>
                    <div class="chat-num col-xs-10" id="student_group">'<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>'：<span class="cGray"><b online>1</b></span></div>
                    <ul id="chat_list"></ul>
                    <ul id="chat_group_list" style="display:none;"></ul>
                    <?php }else{; ?>
                    <div class="chat-num curr col-xs-20" id="student_group">'<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>'：<span class="cGray"><b online>1</b></span></div>
                    <ul id="chat_group_list"></ul>
                    <?php }; ?>
                <?php }else{; ?>
                    <div class="chat-num curr col-xs-20" id="student_total"><?php echo tpl_modifier_tr('总在线人数','course.play'); ?>：<span class="cGray"><b online>1</b></span></div>
                    <ul id="chat_list"></ul>
                <?php }; ?>
                <div id="pattern_reply" style="display:none;" class="pattern">
                    <p>当前内容，仅老师可见</p>
                </div>
                <div id="pattern_notalk" style="display:none;" class="pattern">
                    <p>禁止讨论</p>
                </div>
                <a class="new-msg"id="news" data-action="toEnd" style="display:none;">
                    <div class="angle"></div>
                    <div class="chat-c"></div>
                </a>
                <div class="forum" id="forum">
                    <div id="edit" class="edit col-md-20 col-sm-20 col-xs-20" data-id="">
                        <div class="edit-c">
                            <div class="edit-icon">
                                <div class="icon">
                                    <span style="cursor:pointer"><img id="show_pop" src="<?php echo utility_cdn::img('/assets_v2/img/icon.png'); ?>"></span>
                                    <div class="iconlist" id="show">
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/14.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/13.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/19.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/20.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/75.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/65.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/74.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/57.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/50.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/90.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/114.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/29.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/30.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/32.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/24.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/21.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/26.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/17.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/76.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/11.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/25.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/7.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/15.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/36.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/72.gif'); ?>"></a>
                                        <?php /*
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/001.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/002.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/003.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/004.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/005.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/006.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/007.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/008.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/009.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/010.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/012.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/016.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/018.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/022.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/023.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/027.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/027.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/031.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/033.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/034.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/035.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/037.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/040.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/044.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/045.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/046.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/048.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/051.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/053.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/054.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/055.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/056.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/059.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/061.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/063.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/064.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/066.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/070.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/071.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/073.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/077.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/081.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/083.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/085.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/087.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/088.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/089.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/091.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/120.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/121.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/125.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/129.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/150.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/170.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/171.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/172.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/173.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/174.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/175.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/176.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/177.gif'); ?>"></a>
                                        <a class="icon1"><img show src="<?php echo utility_cdn::img('/assets/images/expression/178.gif'); ?>"></a>
                                        */?>
                                    </div>
                                </div>
                                <div id="pattern_reply" style="display:none;" class="c-fr">
                                    <p>仅老师可见</p>
                                </div>
                                <div id="pattern_notalk" style="display:none;" class="c-fr">
                                    <p>禁止讨论</p>
                                </div>
                                <div id="ask" ask="ask" style="" class="c-fr hidden-sm hidden-xs">
                                    <span ask="ask" class="hand-icon hidden-sm hidden-xs" msg_type=2 style="display:none;"><?php echo tpl_modifier_tr('举手发言',"course.play"); ?></span>
                                    <span ask="cancel" class="handing-icon" style="display:none;"><?php echo tpl_modifier_tr('等待发言',"course.play"); ?></span>
                                    <span ask="stop" class="handed-icon" style="display:none;"><?php echo tpl_modifier_tr('停止发言',"course.play"); ?></span>
                                </div>
                            </div>
                            <div class="edit-text">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["inputText"])){; ?>
                                <div  placeholder="对老师说点什么吧" id="chat_input" contenteditable="true" style="overflow:auto;"></div>
                                <?php }else{; ?>
                                <div  placeholder="对老师说点什么吧" id="chat_input" contenteditable="false" style="overflow:auto;padding:15px 30px"><img src="<?php echo utility_cdn::img('/assets_v2/img/gag.png'); ?>" style="width:30px;height:30px">你已被老师禁言</div>
                                <?php }; ?>
                                <?php }else{; ?>
                                <div  placeholder="对老师说点什么吧" id="chat_input" contenteditable="true" style="overflow:auto"><?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><div id="regmind"><span class="cYellow regcheck" id="regcheck1" style="cursor:pointer;"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></span><?php echo tpl_modifier_tr('参与互动讨论','course.play'); ?></div><?php }; ?></div>
                                <?php }; ?>
                            </div>
                            <div class="edit-button">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["inputText"])){; ?>
                                <button id="chat_send" title=""><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                                <?php }else{; ?>
                                <button id="chat_send" disenable  title=""><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                                <?php }; ?>
                                <?php }else{; ?>
                                <button id="chat_send" disenable  title=""><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                                <?php }; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rank-list" id="rank-list" style="height:0px">
                <div class="rank-num hidden-xs"><?php echo tpl_modifier_tr('学生排名',"course.play"); ?></div>
                <div class="rank-self" id="good_self">
                    <span><i good2>0</i></span>
                        <span class="face">
                            <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['medium'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['medium']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets/images/defaultPhoto1.png'); ?><?php }; ?>">
                        </span>
                    <a href="/index.rank.rule" class="lv<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->fk_level)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->fk_level; ?><?php }; ?>" title="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->title; ?><?php }; ?>"></a>
                        <span class="name">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name'])){; ?>
							<?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name']; ?>
                            <?php }else{; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['name'])){; ?>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?>
                                <?php }else{; ?>
									<?php echo tpl_modifier_tr('游客','course.play'); ?>
                                <?php }; ?>
                            <?php }; ?>
                        </span>
                    <span class="info"><b good>0</b>次</span>
                </div>
                <ul id="good_list"></ul>
            </div>
            <div id="datum-list" class="datum-list">
                <div class="datum-num hidden-xs"><?php echo tpl_modifier_tr('课件下载',"course.play"); ?></div>
                <ul>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["needsign"]!=1){; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["listAtt"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["listAtt"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li>
                        <a href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->attach); ?>">
    							<span class="face">
                                    <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->thumb)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->thumb; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/lesson-jpg.png'); ?><?php }; ?>">
                                </span>
                        </a>
                        <a href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->attach); ?>" class="name" style="color:#cccccc;"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></a>
                        <a href="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->attach); ?>" class="down-load-tabicon c-fr"></a>
                    </li>
                    <?php }; ?>
                    <?php }else{; ?>
                    <li><span class="face"></span><span class="name"><?php echo tpl_modifier_tr('暂时还没有课件呦',"course.play"); ?>～</span></a></li>
                    <?php }; ?>
                    <?php }; ?>
                </ul>
            </div>

        </div>
        <!-- 手机登录提示 -->
        <div class="mobile-login" id="mobile-login-ct" style="display:none;">
            <!--p class="fs16 clearfix title">
                <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.yunke.android" class="c-fl">更多互动功能，请打开云课app！</a>
                <span class="c-fr mobile-close-btn" id="mobile-close-btn"></span>
            </p-->
            <div class="row content fs16">
                <a href="/site.main.login" class="col-xs-10 login">登录</a>
                <a href="/site.main.register" class="col-xs-10 reg">注册</a>
            </div>
        </div>
        <!-- /手机登录提示 -->
        <!-- toolbar -->
        <div id="toolbar" class="toolbar">
            <a href="#" id="rightbtn" class="rightbtn hidden-xs hidden-sm" title="隐藏菜单"><img src="<?php echo utility_cdn::img('/assets_v2/img/white-arrow.png'); ?>"></a>
            <ul id="ck_list">
                <li class="selected" ck="section"><span class="tabicon-1 hidden-xs hidden-sm"></span><span><?php echo tpl_modifier_tr('章节','course.play'); ?></span></li>
                <li ck="talk"><span class="tabicon-2 hidden-xs hidden-sm"></span><span class="c-fl"><?php echo tpl_modifier_tr('讨论','course.play'); ?></span></li>
                <li ck="good"><span class="tabicon-3 hidden-xs hidden-sm"></span><span class="c-fl"><?php echo tpl_modifier_tr('排名','course.play'); ?></span></li>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["listAtt"])){; ?>
                <li><span class="tabicon-5 hidden-xs hidden-sm"></span><span class="c-fl"><?php echo tpl_modifier_tr('课件','course.play'); ?></span></li>
                <?php }; ?>
                <!--li><span class="tabicon-4 hidden-xs hidden-sm"></span><span class="c-fl">笔记</span></li-->
            </ul>
            <!-- 线路切换
            <div class="switch-line" id="switch-line">
                <p><a href="javascript:void(0);">云课线路</a></p>
                <p><a href="javascript:void(0);">蓝讯线路</a></p>
            </div>
			-->
        </div>
        <div class="login-layer" id="login-layer"><span class="cYellow">立即报名</span>，参与互动讨论</div>
    </div>
    <div class="alert-layer" id="call_pop" style="display:none;">
        <div class="alert-bg"></div>
        <div class="alert-content">
            <span class="alert-close-btn" onclick="$('#call_pop').hide()"></span>
            <div class="alert-bg-arrow"></div>
            <div class="alert-pet">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/p-layer-pet.png'); ?>">
            </div>
            <div class="alert-main"><p class="tec fs16">同学们老师点名啦</p></div>
            <div class="alert-btn">
                <input id="call_reply" class="btn-confirm" type="button" value="签到">
            </div>
        </div>
    </div>
    <!--引导层-->
    <div class="alert-layer" id="guide-weixin" style="display:none">
        <div class="alert-bg"></div>
        <div class="alert-guide"><img src="<?php echo utility_cdn::img('/assets_v2/img/guide.png'); ?>"></div>
    </div>
</div>
<script type="text/javascript">
    $(function(){

        //
        $('#switch-line').click(function(){
            $(this).css('height','initial');
        });

        //手机 分享 图片效果
        $('#share-mobile').click(function () {
            $(this).find('.share-mobile-img').css('display','block');
        }).find('.close-img').on('click',function () {
            if (event.stopPropagation) {
                event.stopPropagation();
            } else if (window.event) {
                window.event.cancelBubble = true;
            }
            $(this).parent().css('display','none');
        })
        $('#share-area').click(function () {
            $(this).find('.share-sns').css('display','block');
        }).find('.close-img').on('click',function () {
            if (event.stopPropagation) {
                event.stopPropagation();
            } else if (window.event) {
                window.event.cancelBubble = true;
            }
            $(this).parent().css('display','none');
        })
        var guideParam=getUrlParam("guide");
        if(guideParam =="1"){
            $('#guide-weixin').show();
        }
        $('#guide-weixin').click(function(){
            $(this).hide();
        })
//note
        $("#note-list").on("click",".note-edit",function(){
            var note_txt=$(this).siblings("p").html();
            var note_li=$(this).closest("li");
            var note_textarea;
            var note_hidden="";
            var note_btn="<button class='cancel'>取消</button><button class='confirm'>确定</button>"
            note_textarea="<textarea>"+note_txt+"</textarea>";
            note_hidden="<input type='hidden' value='"+note_txt+"' data-time='"+$(this).siblings("span").html()+"'>";
            note_li.empty();
            note_li.append(note_textarea,note_btn,note_hidden);
        });
        $("#note-list").on("click",".cancel",function(){
            var note_txt="<p>"+$(this).siblings("input").val()+"</p>";
            var note_time="<span>"+$(this).siblings("input").attr("data-time")+"</span>";
            var note_li=$(this).closest("li");
            var note_btn="<i class='note-del'></i><i class='note-edit'></i>";
            note_li.empty();
            note_li.append(note_txt,note_time,note_btn);
        })
//评论弹层
        $("#layer-comment").click(function(){
            layer.open({
                type: 2,
                title:  ['评论'],
                area: ['430px', '280px'],
                shade: 0.8,
                closeBtn:1,
                shadeClose: true,
                content: '/layer.main.playerComment?teacherId='+teacherId+'&planId='+planId+'&userOwner='+userOwner+'&courseId='+courseId+''
            });
        });
//速记
        $(".note-tip-box a").click(function () {
            $(".note-tip-box").hide();
            $(".fixed-note-tip").hide();
        });

        var orgNav = $('#org-nav'), orgNavmenu = $('#org-sub-menu');
        orgNav.click(function () {
            if (orgNavmenu.height() == 0) {
                orgNavmenu.animate({ height: 90 });
                var t = setTimeout("$('#org-sub-menu').animate({ height:0 })", 5000);
            } else {
                orgNavmenu.animate({ height: 0 });
            }
        });

        orgNavmenu.find('a').click(function () {
            orgNavmenu.animate({ height: 0 })
        });
    //手机登录
        $('#mobile-close-btn').click(function() {
            $(this).parents('.title').hide();
        })

    });

    $(function ($) {
        var tool_width = $("#tool").width();
        var toolbar_main_width = $("#toolbar-main").width();
        var video_width = $("#coursebox").width();

        function close_list() {
            if ($("#tool").width() != 60) {
                $("#tool-main").animate({ width: "0px" });
                $("#tool").animate({ width: "60px" });
                $("#coursebox").animate({ width: video_width + tool_width - 60 });
            }
        }

        function open_list() {
            if ($("#tool").width() == 60) {
                $("#tool").animate({ width: "385px" });
                $("#tool-main").animate({ width: tool_width });
                $("#coursebox").animate({ width: video_width });
            }
        }

        var play_status='<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status; ?>';
        if(play_status == 'living') {
            $("#live-status").addClass("live-status-play").html("正在直播");
        }else if(play_status == 'finished'){
            $("#live-status").addClass("live-status-live").html("课程回放");
            if($(window).width()>800){
                if($('#sectionc').find('curr')){
                    setTimeout(function(){
                        $("#rightbtn img").attr("src","<?php echo utility_cdn::img('/assets_v2/img/white-arrow2.png'); ?>");
                        close_list();
                    },3000);
                }

            }
        }

        $('#rightbtn').click(function () {
            if($(this).find("img").attr("src")=="<?php echo utility_cdn::img('/assets_v2/img/white-arrow.png'); ?>"){
                $(this).find("img").attr("src","<?php echo utility_cdn::img('/assets_v2/img/white-arrow2.png'); ?>");
            }else{
                $(this).find("img").attr("src","<?php echo utility_cdn::img('/assets_v2/img/white-arrow.png'); ?>");
            }
            if ($("#tool").width() == tool_width) {
                close_list();
            } else {
                open_list();
            }
        });

        $("#sectionc > a strong").each(function (i, elem) {
            $(this).text((i + 1) + ". " + $(this).html());
        });
        /*设置滚动条*/
        var secheight = $("#sectionc").height();
        var secahetght = $("#sectionc > a").outerHeight();
        var sectext = $("#section").find("[plan_id=" + window.planId + "]").children("p").find("strong").text();
        var secnum = parseInt(sectext.split(".")[0]);
        $("#sectionc").scrollTop((secahetght * (secnum - 1)));
        //未登录未报名弹层
        <?php if(SlightPHP\Tpl::$_tpl_vars["needlogin"]){; ?>
        $("#tryOverLay").show();
        //$("#login_plan_need").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["needsign"])){; ?>
        $("#tryOverLay").show();
        //$("#sign_plan_need").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["playMsgInfo"]['code'])){; ?>
        $("#tryPlay").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["isReg"] && SlightPHP\Tpl::$_tpl_vars["userStatus"]['isMemberRegType'] && (SlightPHP\Tpl::$_tpl_vars["userStatus"]['isOpen'] == 0 or SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']))){; ?>
        $("#tryOverLay").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
        <?php }; ?>

        <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->status == 1){; ?>
        function NowTime(descSecond) {
            descSecond = parseInt($("#coursebefore").find("p").eq(1).attr("second"));
            if (isNaN(descSecond)) {
                return;
            }

            hours = Math.floor(descSecond / 3600);
            minute = Math.floor((descSecond - (hours * 3600)) / 60);

            descSecond = descSecond - 63;
            if (descSecond <= 0) {
                descSecond = $("#coursebefore").find("p").eq(1).html("老师还未进入教室，请您耐心等待");
                return;
            }
            descSecond = $("#coursebefore").find("p").eq(1).attr("second", descSecond);
            if (hours == 0) {
                descSecond = $("#coursebefore").find("p").eq(1).html("离开课时间:" + minute + "分");
            } else {
                descSecond = $("#coursebefore").find("p").eq(1).html("离开课时间:" + hours + "小时" + minute + "分");
            }
        }
        window.setInterval(NowTime, 60000);
        <?php }; ?>

        //设置限制字数
        $("#num_in").text(100 - $("#chat_input").text().length);
        $('#chat_input').bind('focus keyup input paste', function () {
            var curLength = $(this).text().length;
            if (curLength > 100) {
                var textin = $("#chat_input").text().substr(0, 100);
                $("#chat_input").text(textin);
            } else {
                $('#num_in').text(100 - $(this).text().length)
            }
        });

        /*设置goodself*/
        var goodlist2 = $("#good_list");
        var goodself2 = $("#good_self");
        var s2 = goodlist2.find("li[userid=" + window.userId + "]").find("i[good2]").text();
        goodself2.find("i[good2]").text(s2);
        var checkleft = "<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->status; ?>";
        $('#ck_list>li').click(function () {
            if ($('#tool').width() == 60) {
                if ($(window).width() > 800) {
                    open_list();
                    if($(this).attr("ck")=="talk"){
                        $("#forum").show();
                    }else{
                        $("#forum").hide();
                    }
                }
                if ($('#toolbar-main>div:eq(' + $(this).index() + ')').height() == 0) {
                    $('#toolbar-main>div:eq(' + $(this).index() + ')').css({ 'height': $("#toolbar-main").height() + 'px' }).siblings().css({ 'height': '0px' });
                    $(this).addClass('selected').siblings().removeClass('selected');
                    if($(this).attr("ck")=="talk"){
                        $("#forum").show();
                    }else{
                        $("#forum").hide();
                    }
                } else {
                    $(this).addClass('selected').siblings().removeClass('selected');
                }
            } else {
                if ($('#toolbar-main>div:eq(' + $(this).index() + ')').height() == 0) {
                    $('#toolbar-main>div:eq(' + $(this).index() + ')').css({ 'height': $("#toolbar-main").height() + 'px' }).siblings().css({ 'height': '0px' });
                    $(this).addClass('selected').siblings().removeClass('selected');
                    if($(this).attr("ck")=="talk"){
                        $("#forum").show();
                    }else{
                        $("#forum").hide();
                    }
                } else {
                    if ($(window).width() > 800) {
                        close_list();
                    }
                    if($(this).attr("ck")=="talk"){
                        $("#forum").show();
                    }else{
                        $("#forum").hide();
                    }
                    $('#toolbar ul li').removeClass('selected');
                }
            }
        });

        var domthisplan = $("#section").find("[plan_id=" + window.planId + "]");
        var domnextplan = domthisplan.next(); //下一个节点
        var haha = domnextplan.attr("");
        if (domnextplan.length == 0) {
            $("#nextTime").html("没有更多课程");
        } else {
            var planisset = domnextplan.attr("plan_id");
            if (typeof(planisset) == "undefined") {
                var secname = domnextplan.find("span:eq(1)").html();
                $("#nextTime").html("下一节开课时间:" + secname);
            } else {
                var secname = domnextplan.find("strong").html();
                $("#nextTime").html("下一节:" + secname);
            }
        }

        $("#section a").each(function () {
            var a = $(this).attr("plan_id");
            if ($(this).attr("plan_id") == window.planId) {
                $(this).addClass("curr");
            }
        });

        if (checkleft == 2) {
            $("#toolbar li[ck=talk]").trigger("click");
        } else {
            $("#toolbar li[ck=section]").trigger("click");
        }

        $(".upload_attach_msg").click(function(){
            if(0==isLogin){
                layer.msg("请登录后下载");
            }else if(0==isReg){
                layer.msg("请报名后下载");
            }
        });
    });

    function playerbox() {
        var section = $('#sectionc');
        var datumlist = $('#datum-list ul');
        var chat = $('#chat');
        var chatUl = $('#chat ul');
        var datum = $('#datum-list') || $('#datum-list:hidden');
        var rank_list = $('#rank-list ul');
        var note_list = $('#note-list ul');
        var w = $(window).width();
        var h = $(window).height();
        var main = $('#toolbar-main');
        var dZoom= detectZoom();
        if ($(window).width() > 850) {
            $('#content').height(h-$("header").height());
            $('#tool,#tool-main,#coursebox').height(h-$("header").height());
            section.height($(window).height() - 37 - 45);
            datumlist.height($(window).height() - 37 - 45);
            note_list.height($(window).height() - 37 - 45);
            if(chat.height()>0){
                chat.height(h -45);
            }
            chatUl.height(h - 165 -60);
            rank_list.height(h - 101 - 45);
            if ($('#toolbar-main').is(":hidden")) {
                $('#tool').width(60);
                $('#coursebox').width($(window).width() - 60);
            } else {
                $('#tool').width(385);
                $("#forum").find(".edit-text").removeAttr("style");
                $('#coursebox').width($(window).width() - 385);
            }
            //缩放提醒
            if(dZoom == 100){
                $('#detect-zoom').hide();
            }else{
                $('#detect-zoom').show().find('span').html(' Ctrl+ 0');
            }
        } else {
            var toolbar_box = $('#toolbar-main');
            var t_h = h - 150;
            var h2=h-parseInt(w*9/16)-50;
            //toolbar_box.css('height', h2);
            chatUl.height(h2-80);
            section.height(h2);
            $('#tool').height(h - parseInt(w * 9 / 16));
            $('#coursebox').height(parseInt(w * 9 / 16));
            rank_list.height(h2-60);
            var forumWidth=$("#forum").width();
            var forumicon=$("#forum").find(".edit-icon").width();
            var forumbtn=$("#forum").find(".edit-button").width();
            $("#forum").find(".edit-text").width(forumWidth-forumicon-forumbtn-50);
        };

    };
    $(window).on( 'orientationchange', function(e){
        orient();
    });
    function orient() {
        if (window.orientation == 0 || window.orientation == 180) {
            $("body").attr("class", "portrait");
            orientation = 'portrait';
            var w_h=$(window).height();
            var w_w=$(window).width();
            var w = $("#playerContent").innerWidth();
            var h = parseInt(w / 16 * 9);
            var header_h=$(window).height();
            var chatUl = $('#chat ul');
            var section = $('#sectionc');
            var datumlist = $('#datum-list ul');
            var h2= w_h-h-50;
            $("#content").css({ "height":"100%","width":"100%" });
            $("#tool").show();
            $("#tool").css({ "width":"100%" });
            $("#tool").height(w_h-parseInt(w*9/16));
            $("#chat_list").css('height',h2-40);
            chatUl.height(h2-50);
            section.height(h2);
            datumlist.height(h2);
            $("#coursebox").css({ "width":"100%" })
            return false;
        }
        else if (window.orientation == 90 || window.orientation == -90) {
            $("body").attr("class", "landscape");
            orientation = 'landscape';
            var w_h=$(window).height();
            var w_w=$(window).width();
            $("#content").css({ "height":"100%","width":"100%" });
            if(w_w > 760){
                /*var tool_h;
                 if($("header")){
                 tool_h=w_h-$("header").height();
                 }else{
                 tool_h=w_h;
                 }*/
                var header_h=$(window).height();
                var chatUl = $('#chat ul');
                var section = $('#sectionc');
                var datumlist = $('#datum-list ul');
                var w = $("#playerContent").innerWidth();
                var h = parseInt(h/ 16 * 9);
                var h2= w_h-h-50;
                $("#chat_list").css('height',h2-40);
                chatUl.height(h2-50);
                section.height(h2);
                datumlist.height(h2);
                $("#tool").css({ "width":"300px" });
                if(w_w > w_h){
                    $("#tool").height(w_h);
                }else{
                    $("#tool").height(w_h-parseInt(w*9/16));
                }
                $("tool-main").css({ "width":"240px" });
                $("tool-main").height(w_h-parseInt(w*9/16));
                $("#coursebox").css({ "width":w_w-300,"height":w_h });

                return false;
            }else{
                $("#tool").hide();
                $("#content").css({ "height":"100%","width":"100%" });
                $("#coursebox").css({ "height":"100%","width":"100%" });
            }
        }
    }
    //视频断开后弹层
    function showPlayerMessage(msg,butType){
        var alertId=$("#call_buff");
        var tipsTxt,btn;
        tipsTxt='<p>'+msg+'</p>';
        alertId.show();
        alertId.find(".alert-mark-txt").empty();//清空提示内容
        if(butType==1){
            btn='<a href="javascript:reloadPage()" class="tec"><span></span>点击刷新</a>';
        }else{
            btn="<em id='show_btn' class='yellow-btn col-xs-6' style='float:none'>确定</em>";
        }
        alertId.find(".alert-mark-txt").append(tipsTxt);
        alertId.find(".alert-mark-txt").append(btn);
        $("#show_btn").click(function(){
            $('#call_buff').hide();
        });
    }
    function reloadPage(){
        location.reload();
    }
    function hidePlayerMessage() {
        $('#call_buff').hide();
    }
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }
    $('.o-close-btn').click(function(){
        $('.o-vip-end').hide();
    });
    //判断浏览器百分比
    function detectZoom (){
    	var ratio = 0,screen = window.screen,ua = navigator.userAgent.toLowerCase();
    	if (window.devicePixelRatio !== undefined) {
    		ratio = window.devicePixelRatio;
    	} else if (~ua.indexOf('msie')) {
    		if (screen.deviceXDPI && screen.logicalXDPI) {
      			ratio = screen.deviceXDPI / screen.logicalXDPI;
    		}
    	} else if (window.outerWidth !== undefined && window.innerWidth !== undefined) {
    		ratio = window.outerWidth / window.innerWidth;
    	}
    	if (ratio){
    		ratio = Math.round(ratio * 100);
    	}
    	return ratio;
    };
</script>
<div style="display:none">
<?php echo tpl_function_part('/site.main.statistic'); ?>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253775234'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1253775234' type='text/javascript'%3E%3C/script%3E"));</script>
</div>
</body>
</html>