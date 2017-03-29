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
<meta name="description" content="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?><?php }; ?>-<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no"/>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/bootstrap/bootstrap.min.css'); ?>">
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/bootstrap/bootstrap.min.css'); ?>">
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/html5shiv.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/respond.min.js'); ?>"></script>
<![endif]-->
<!--[if lt IE 8]>
<script src="<?php echo utility_cdn::js('/assets_v2/js/ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="//www.yunke.com/crossdomain.php"></script>
<link rel="stylesheet" type="text/css" id="cssSkin" href="<?php echo utility_cdn::css('/assets_v2/css/player1.css'); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo utility_cdn::img('/assets_v2/img/platform/yunke.ico'); ?>"/>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/player/lib.plugin.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swfobject.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/player.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/HTML5Player.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/message_v2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/course_common_v2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/player/module.js'); ?>"></script>
<?php echo tpl_function_part("/site.main.weixin"); ?>
<meta name="weixin" imgurl="<?php echo utility_cdn::http(utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["plan_info"]->thumb_big)); ?>" desc="<?php echo trim(strip_tags(SlightPHP\Tpl::$_tpl_vars["plan_info"]->title)); ?>"/>
<script type="text/javascript">
    var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";
</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
<script type="text/javascript">
    var resellOrgId = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellOrgId"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?><?php }else{; ?>0<?php }; ?>;
    var coursePrice = <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1){; ?>0<?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?><?php }; ?>;
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
    var displayAll = <?php echo SlightPHP\Tpl::$_tpl_vars["displayAll"]; ?>;
    var groupId = <?php echo SlightPHP\Tpl::$_tpl_vars["groupId"]; ?>;
    if(!isReg || !groupId){
        displayAll = 0;
    }
    var groupName = "<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>";
    var groupUid = <?php echo SlightPHP\Tpl::$_tpl_vars["groupUid"]; ?>;
    window.forbidCloseList = true;
    wo.remindCard(undefined,planId);
    window.supportDM = (function(){
        if(!$.support.opacity || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            return false;
        }
        return true;
    })();
    window.needDM = (function(){
        if($.cookie('barrageOpen')){
            return true;
        }
        return false;
    })();

    if ("/" != filecdn[filecdn.length - 1]) {
        filecdn += "/";
    }
    //获取特征码方法
    var browser={
        versions:function(){
            var u = navigator.userAgent, app = navigator.appVersion;
            return {
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
                iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
                pad: u.indexOf('Pad') > -1, //是否Pad
                webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
                weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
                qq: u.match(/\sQQ/i) == " qq" //是否QQ
            };
        }(),
        language:(navigator.browserLanguage || navigator.language).toLowerCase()
    }
    var deviceType = 10;
    if(browser.versions.weixin){
        deviceType = 43;
    }else if(browser.versions.ios || browser.versions.android){
        if(browser.versions.pad){
            deviceType = 30;
        }else{
            deviceType = 20;
        }
    }
    var message;
    $(document).ready(function () {
        if (userId) {
            $("#href_logout").attr("target", "");
        }
        $(window).resize(function () {
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
            stopRecord:"message.stopRecord",
            useBarrge: !supportDM ?false:true,
            defaultBarrageStatus: !supportDM ?false: needDM,
            updateTime: function(curTime){
                if(!isLiving){
                    DMS.updateTime(curTime);
                }
            },
            barrageSwitch: function(flag){
                if(supportDM){
                    DMS.openOrClose(flag);
                }
            },
            playOrPauseVideo:function(flag){
                if(supportDM){
                    if(needDM){
                        DMS.playOrPause(flag);
                    }
                }
            }
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
            $("#section").find('[plan_id=' + planId + ']').children('p')
                .removeClass('play-living-status')
                .addClass('play-back-status')
                .html('<strong class="fs14"><?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_descipt,ENT_QUOTES); ?></strong><span class="sicon-3"><img src="<?php echo utility_cdn::img('/assets_v2/img/player-icon.gif'); ?>"></span>')
                .find(".sicon-2").remove();
            <?php if(!SlightPHP\Tpl::$_tpl_vars["needlogin"] && !SlightPHP\Tpl::$_tpl_vars["needsign"]){; ?>
                if(!navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)){
                    $("#section").find("[plan_id=" + planId + "]").removeClass('living').addClass('playing');
                }
            <?php }; ?>
        <?php }; ?>

        <?php if((SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name<>SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']) && !empty(SlightPHP\Tpl::$_tpl_vars["userStatus"]['className'])){; ?>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["sameSectionPlanInfo"])){; ?>
			    $("#chat_input").html("您进错教室了，<a href='/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["sameSectionPlanInfo"]->plan_id; ?>' style='color:#f93'>点击进入'<?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']; ?>'</a>");
			<?php }else{; ?>
			    $("#chat_input").html("您进错教室了，'<?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['className']; ?>'");
			<?php }; ?>
		<?php }; ?>
    });
</script>
<script>
$(document).ready(function(e){
    var course_type=<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_type; ?>;
    var source= "reg";
    var courseFeeType = <?php echo SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType']; ?>;

    if($(window).width() < 769) {
        if(userApi.isLogin()) {
            $('#mobile-login-ct').hide();
            if(isSign){
                $('#mobile-login-ct').show();
                $('#join').show();
            }else{
                $('#join').hide();
            }
        }else{
            $('#mobile-login-ct').show();
            $('#join').show();
        }
    }else{
        $('#mobile-login-ct').hide();
        $('#join').hide();
    }


    /* 马上开通， 立即续费， 重新开通 */
    $("#open-vip,#renew-vip,#re-vip").click(function(){
        var setId = $(this).parent('p').attr('setId');
        parent.location.href = "<?php echo SlightPHP\Tpl::$_tpl_vars["sourceSubdomain"]; ?>/order.main.memberinfo/"+setId;
    });

    /* 立即报名 */
    $("#join,#regcheck1,#regcheck2,#regcheck3,#regcheck4,#regcheck5,#regcheck6,#re-Enroll,#login-layer").click(function(){
        var w,h;
        if($(window).width()>800){
            w = '476px';
            h = '360px';
        }else{
            w = '90%';
            h = '400px';
        }
        if(userApi.isLogin()){
            if(courseFeeType==1 && coursePrice==0){
                courseFeeType=0;
            }
            if (courseFeeType == 0 && coursePrice==0) {
                $.post("/course/info/AddReg", { classId: class_id ,cid: courseId }, function (r) {
                    if (r.code == 0) {
                        layer.msg('报名成功',{ icon:1 }, function(){
						    reloadPage();
                        });
                    } else if (r.code == 1021) {
                        layer.open({
                            type: 2,
                            title: false,
                            area: [w, h],
                            shadeClose: true,
                            content: ['/layer.main.userLogin/'+courseId+'/'+class_id+'/0/'+source+'/0/'+resellOrgId, 'no']
                        });
                        return false;
                    } else {
                        layer.msg(r.errMsg, function(){
							reloadPage();
                        });
                    }
                }, "json");
            } else if (courseFeeType == 1 || coursePrice>0) {
                $.post("/course/pay/check",{ classId:class_id ,cid:courseId,resellOrgId:resellOrgId }, function (r) {
                    if (r.code == 0) {
                        parent.location.href = "/order.main.buy/course/"+courseId+"/"+class_id+"/"+resellOrgId ;
                    } else {
                        layer.msg(r.errMsg, function(){
							reloadPage();
                        });
                    }
                }, "json");
            }
        //如果未登录
        }else{
            userApi.setCookie("course.autoreg",<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>);
            var argsStr = location.hostname+"/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>&cid="+courseId+"&clid="+class_id+"&source="+source+"&resellOrgId="+resellOrgId;
            if (userApi.isWeiXin()) {
                location.replace("/weixin.user.login/?url=//"+argsStr);
            } else {
                if($(window).width() < 800){
                    location.replace("/site.main.login/?url=//"+argsStr);
                }else{
                    layer.open({
                        title:false,
                        type: 2,
                        area: [w, h],
                        shadeClose: true,
                        content: ['/layer.main.userLogin/'+courseId+'/'+class_id+'/0/'+source+'/0/'+resellOrgId, 'no']
                    });
                }
            }
        }
        return false;
    });
});
</script>
</head>
<body>
<header class="header hidden-xs ">
    <div class="header-main">
        <div class="g-title">
            <div class="g-play-icon"></div>
            <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellOrgId"])){; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?><?php }; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->title; ?>&nbsp;&nbsp;&nbsp;<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->class_name; ?><?php }; ?>&nbsp;&nbsp;&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->section_name; ?></a><span id="live-status" data-status="<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status; ?>"></span>
        </div>
        <div class="g-teacher">
            <a href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->uid; ?>" target="_blank">
                <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->small){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->avatar->small; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name)){; ?>
                <span class="g-teacName"><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->profile->real_name; ?></span>
                <?php }else{; ?>
                <span class="g-teacName"><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?></span>
                <?php }; ?>
                <span class="supportGift teacher-gift hide" id="teacherGift">本节共收到<b class="teacher-gift-count" id="teacherGiftCount">0</b>朵鲜花</span>
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
                        <li class="hidden-xs ">
                            <a href="/org" target="_blank"><span class="t-set-icon"></span><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a>
                        </li>
                        <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
                        <li class="hidden-xs ">
                            <a href="/teacher.course.timetable2" target="_blank"><span class="t-set-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
                        </li>
                        <?php }else{; ?>
                        <li class="hidden-xs ">
                            <a href="/student.main.growth" target="_blank"><span class="School-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
                        </li>
                        <?php }; ?>
                        <li><a href="/student.main.infobase" target="_blank"><span class="compose-icon"></span><span><?php echo tpl_modifier_tr('基础资料','site.header'); ?></span></a></li>
                        <li><a href="/student.order.myorder" target="_blank"><span class="order-icon"></span><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a></li>
                        <li><a href="/student.security.password" target="_blank"><span class="card-icon"></span><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a></li>
                        <!--li class="visible-sm"><a href="/user.message" target="_blank"><span class="mess-icon"></span><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a></li-->
                        <li><a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a></li>
                    </ul>
                </li>
                <li class="hidden-xs ">
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
<div class="c expend" id="content">
    <div id="coursebox" class="coursebox">
            <div class="course-total">
                <!--直播课结束层 开始-->
                <!--<div class="course-box" style="height:100%;display:none;" id="courseingend">-->
                <div class="course-box"  id="courseingend" style="display:none">
                    <div class="play-end-box">
                        <div class="play-end-box-content">
                            <span class="fs18">
                                <?php echo tpl_modifier_tr('同学们，下课了！','course.play'); ?>
                            </span>
                            <p class="fs14 mt10 tac">排名：<b class="cYellow" id="courseingsort">暂无排名</b>&nbsp;&nbsp;&nbsp;&nbsp;获赞：<b class="cYellow" id="courseinggood">0</b></p>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"] && SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                            <div class="i-comment hidden-xs mt10" id="score_comment" style="display:none;">
                               <!--<div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>-->
                                <div class="percent fs14" id="score_percent">
                                    <dl>
                                        <dt><?php echo tpl_modifier_tr('综合评分','course.play'); ?>：</dt>
                                        <dd>
                                            <span class="sel" data-title="很差" data-score="1"></span>
                                            <span data-title="差" data-score="2" class="sel"></span>
                                            <span data-score="3" data-title="还行" class="sel"></span>
                                            <span data-score="4" data-title="满意" class="sel"></span>
                                            <span data-score="5" data-title="很好" class="sel"></span>
                                            <i score_type="avg_score" data-score="5"></i>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="t-area hidden-xs" id="textComment" style="display:none;">
                                <div class="textarea" contenteditable="true" style="overflow:auto;" id="comment_input"></div>
                                <button id="comment_send"><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                            </div>
                            <div id="ret_textComment" class="mt25" style="display:none;">
                                <ul class="comments-list">
                                    <div class="ui-page">
                                        <span>我的评价：</span>
                                        <a rel="2" href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank">更多>></a>
                                    </div>
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
                                        <div class="desc col-md-10">
                                            <p class="desc" id="ret_comment_input">老师讲的非常好 </p>
                                            <p class="line-2">
                                                <span id="ret_time"></span>
                                                <span id="ret_course"></span>
                                            </p>
                                        </div>
                                        <div class="p-comment col-md-6 col-sm-6 col-xs-6">
                                            <div class="i-comment hidden-xs" id="ret_score_comment" style="display:none;">
                                                <div class="percent fs14">
                                                    <dl>
                                                        <dd id="avg_score">
                                                            <span class="sel" data-title="很差" data-score="1"></span>
                                                            <span data-title="差" class="sel" data-score="2"></span>
                                                            <span data-score="3" class="sel" data-title="还行"></span>
                                                            <span data-score="4" class="sel" data-title="满意"></span>
                                                            <span data-score="5" class="sel" data-title="很好"></span>
                                                            <i score_type="avg_score" data-score="5"></i>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <p style="float:right;" id="ret_score_score"></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                            <?php }; ?>
                        </div>
                    </div>
                </div>
                <!--直播课结束层 结束-->
                <!--录播课结束层 开始-->
                <div class="course-box3" id="courseend" style="display:none">
                    <div class="play-end-box">
                        <div class="play-end-box-content">
                            <p class="time-tips hidden-xs" style="display:none" id="nextTime">距离下一节开课时间 4月15日</p>
                            <span class="fs18"><?php echo tpl_modifier_tr('本章学习已完成','course.play'); ?>！</span>
                            <div class="mt10">
                                <a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_id"]; ?>" class="btn playbox-href-btn cWhite">
                                    <?php echo tpl_modifier_tr('重新学习','course.play'); ?>
                                </a>
                                <a class="btn playbox-href-btn ml10 cWhite" id="nextPlan">
                                    <?php echo tpl_modifier_tr('学习下一节','course.play'); ?>
                                </a>
                            </div>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["logined"] && SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                            <!--课程评价-->
                            <div class="i-comment mt10" id="score_comment1" style="display: none;">
                            <!--<div class="bar fs16">(<?php echo tpl_modifier_tr('课程评价','course.play'); ?>)</div>-->
                            <div class="percent fs14 mt10" id="score_percent1">
                                <dl>
                                    <dt><?php echo tpl_modifier_tr('综合评分','course.play'); ?>：</dt>
                                    <dd>
                                        <span class="sel" data-title="很差" data-score="1"></span>
                                        <span data-title="差" data-score="2" class="sel"></span>
                                        <span data-score="3" data-title="还行" class="sel"></span>
                                        <span data-score="4" data-title="满意" class="sel"></span>
                                        <span data-score="5" data-title="很好" class="sel"></span>
                                        <i score_type="avg_score" data-score="5"></i>
                                    </dd>
                                </dl>
                            </div>
                            </div>
                            <div class="t-area" id="textComment1" style="display:none;">
                                <div class="textarea" contenteditable="true" style="overflow:auto;" id="comment_input1"></div>
                                <button id="comment_send1"><?php echo tpl_modifier_tr('发送',"course.play"); ?></button>
                            </div>
                            <div id="ret_textComment1" class="mt25" style="display:none;">
                            <ul class="comments-list">
                                <div class="ui-page">
                                    <span>我的评价：</span>
                                    <a rel="2" href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank">更多>></a>
                                </div>
                                <li>
                                    <div class="user-info col-md-3 col-sm-6 col-xs-6">
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
                                    <div class="desc col-md-11">
                                        <p class="desc" id="ret_comment_input1">老师讲的非常好 </p>
                                        <p class="line-2">
                                            <span id="ret_time1"></span>
                                            <span id="ret_course1"></span>
                                        </p>
                                    </div>
                                    <div class="p-comment col-md-6 col-sm-6 col-xs-6">
                                        <div class="i-comment" id="ret_score_comment1" style="display:none;">
                                            <div class="percent fs14">
                                                <dl>
                                                    <dd id="avg_score1">
                                                        <span class="sel" data-title="很差" data-score="1"></span>
                                                        <span data-title="差" data-score="2" class="sel"></span>
                                                        <span data-score="3" data-title="还行" class="sel"></span>
                                                        <span data-score="4" data-title="满意" class="sel"></span>
                                                        <span data-score="5" data-title="很好" class="sel"></span>
                                                        <i score_type="avg_score" data-score="5"></i>
                                                    </dd>
                                                </dl>
                                            </div>
                                            <p style="float:right;" id="ret_score_score1"></p>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                            </div>
                            <!--课程评价 end-->
                            <?php }; ?>
                        </div>
                    </div>
                </div>


                <div class="course-box2" id="courseing">
                    <div class="alert-layer" id="call_buff" style="display:none">
                        <div class="alert-bg2"></div>
                        <div class="alert-content2">
                            <div class="alert-title">
                                <span class="alert-close-btn" onclick="$('#call_buff').hide()"></span>
                                <span class="alert-title-txt">提示</span>
                            </div>
                            <div class="alert-main2 tec">
                                <p><i class="alert-mark"></i>获取课程信息失败，请刷新页面。</p>
                                <p class="tec mt25">点击刷新</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert-layer alert-call-player-msg" id="callPlayerMsg" style="display:none">
                        <div class="alert-bg2"></div>
                        <div class="alert-content2">
                            <div class="alert-title">
                                <span class="alert-close-btn" onclick="hidePlayerMessage()" id="callPlayerMsgClose"></span>
                                <span class="alert-title-txt">提示</span>
                            </div>
                            <div class="alert-main2 tac">
                                <div class="alert-call-player-msgs">
                                    <i class="alert-mark"></i>
                                    <p class="alert-call-player-msg1" id="callPlayerMsg1">视频已断开，自动重连中。。</p>
                                    <p class="alert-call-player-msg2" id="callPlayerMsg2"></p>
                                </div>
                                <button onclick="reloadPage()" class="alert-call-player-msg-button" id="callPlayerMsgButton"  style="display:none">点击刷新</button>
                            </div>
                        </div>
                    </div>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["needdatainfo"]){; ?>
                    <?php }else{; ?>
                    <div class="course-player" id="playerContent">
                        <div id="player"></div>
                        <!--h5 播放器点击播放后的缓加载层-->
                        <div class="overlay tac H5-play-waitting" id="H5PlayWaitting" style="display:none;">
                            <div class="o-layer">
                                <i class="play-loading-icon animate-pulse"></i>
                                <p class="fs12 pt10">视频缓存中，精彩课程马上就来~</p>
                            </div>
                        </div>
                        <!-- 课间休息 -->
                        <div class="relax" id="relax"  style="display: none;">
                            <div class="relax-begin" id="relaxBegin">
                                <p>放松身心，眺望一下远方~</p>
                                <span class="relax-begin-btn" id="relaxBeginBtn">×</span>
                            </div>
                            <div class="relax-end" id="relaxEnd">
                                <img src="<?php echo utility_cdn::img('/assets_v2/img/player-relax/clock.gif'); ?>" class="relax-clock-img">
                                <p>休息时间到，老师在召唤你，你快点回来哦！</p>
                                <span class="relax-end-btn" id="relaxEndBtn">
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/player-relax/know.png'); ?>" class="relax-end-btn-img">
                                </span>
                            </div>
                            <audio id="relaxAudio" loop>
                                <source src="<?php echo utility_cdn::img('/assets_v2/img/player-relax/bell.ogg'); ?>" type="audio/ogg">
                                <source src="<?php echo utility_cdn::img('/assets_v2/img/player-relax/bell.mp3'); ?>" type="audio/mpeg">
                            </audio>
                        </div>
                        <div class="relax-clock" id="relaxClock" style="display: none;">
                            <span class="c-fl" id="relaxClockM">00</span>
                            <span class="c-fr" id="relaxClockS">00</span>
                        </div>
                    </div>
                    <div class="h5Toolbar visible-xs visible-sm" id="h5Toolbar">
                        <div class="c-fl pos-rel h5ToolbarCnt h5ChangeNetLine" id="switch-lines">画面卡，试试<span class="pl5 openListBtn">切换线路</span><ul class="openList"></ul>
                        </div>
                        <div class="c-fr pos-rel h5ToolbarCnt h5chatNum">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["groupId"] > 0){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["displayAll"]){; ?>
                                <span class="pr5 openListBtn student_group"><?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>
                            <?php }else{; ?>
                                <span class="pr5 openListBtn student_total">在线人数
                            <?php }; ?>
                        <?php }else{; ?>
                            <span class="pr5 openListBtn student_total">在线人数
                        <?php }; ?><b class="onlineNum" online="">1</b></span>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["groupId"] > 0 && !SlightPHP\Tpl::$_tpl_vars["displayAll"]){; ?>
                        <ul class="openList w100">
                            <li class="student_total active" data-to="#chat_list">在线同学<b class="onlineNum" online="">1</b></li>
                            <li class="student_group" data-to="#chat_group_list"><?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?><b class="onlineNum" online="">1</b></li>
                        </ul>
                        <?php }; ?>
                        </div>
                    </div>
                    <div class="handle-area">
                        <!-- 评论icon-->
                        <div class="share-mobile fs14 " id="share-mobile">
                            <span class="mobile-icon"></span>
                            <div class="share-mobile-img">
                                <div class="close-img"></div>
                                <img class="share-mobile-bg" src="<?php echo utility_cdn::js('/assets_v2/img/share-mobile-skin.png'); ?>" alt="">
                            </div>
                        </div>
                        <div class="share-area fs14" id="share-area">
                            <span class="play-share-icon"></span>
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
                            <span class="score-comment-icon"></span>
                        </div>
                        <?php }; ?>
                        <div class="flash-test" id="flash-test">
                            <a class="flash-text" href="/site.help.flash" target="_blank">检测助手</a>
                        </div>
                        <script>
                            var wx_href='<?php echo SlightPHP\Tpl::$_tpl_vars["qrCode"]; ?>';
                            $(function () {
                                $(".share-area").share({
                                    sPic    : '<?php echo SlightPHP\Tpl::$_tpl_vars["shareImg"]; ?>',
                                    wxHref  : wx_href
                                });//分享
                            })
                        </script>
                        <!-- 评论 分享 手机看icon icon-->
                    <!-- 速笔记-->
                    <div class="qute-note" id="qute-note">
                            <div class="wrap-note c-fl clear">
                                <span class="qute-time c-fl" id="qute-time">记笔记：</span>
                                <input type="text" class="bor1px note-ipt" id="qute-input" placeholder="输入此刻你要记录的内容" ></input>
                            </div>
                            <div class="qute-btn c-fl" id="qute-btn"><button class="note-gray-button c-fl" id="qute-submit">保存</button><button class="note-cancel-button c-fl" id="qute-cancel">取消</button></div>
                            <div class="qute-tip" id="quteTip" style="display:none"></div>
                    </div>
                </div>
                    <?php }; ?>
                     <!-- 弹幕 begin -->
                    <div id="DM_comment" class="DM_comment DM_box abp">
                        <div id="DM_comment_stage" class="DM_stage container"></div>
                    </div>
                    <div id="DM_note" class="DM_note DM_box abp">
                        <div id="DM_note_stage" class="DM_stage container"></div>
                    </div>
                    <!-- 弹幕 end -->
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
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->price>0){; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1){; ?>
                                            <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                        <?php }else{; ?>
                                            <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                        <?php }; ?>
                                    <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }; ?>
                                <?php }else{; ?>
                                    <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1 or SlightPHP\Tpl::$_tpl_vars["plan_info"]->price==0){; ?>
                                        <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }else{; ?>
                                        <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                    <?php }; ?>
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
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck3" class="btn-yellow hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck3" class="btn-yellow hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
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
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                            <a class="btn playbox-href-btn need-member-btn cYellow hidden-md hidden-lg" href="/member.list" target="_blank"><?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>续费会员<?php }else{; ?>开通会员<?php }; ?></a>
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
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->price>0){; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1){; ?>
                                            <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                        <?php }else{; ?>
                                            <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                        <?php }; ?>
                                    <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }; ?>
                                <?php }else{; ?>
                                    <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1 or SlightPHP\Tpl::$_tpl_vars["plan_info"]->price==0){; ?>
                                        <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }else{; ?>
                                        <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                    <?php }; ?>
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
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16  hidden-xs ">注册</a>
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
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                            <a class="btn playbox-href-btn need-member-btn cYellow hidden-md hidden-lg" href="/member.list" target="_blank"><?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>续费会员<?php }else{; ?>开通会员<?php }; ?></a>
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
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->price>0){; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1){; ?>
                                            <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                        <?php }else{; ?>
                                            <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                        <?php }; ?>
                                    <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }; ?>
                                <?php }else{; ?>
                                    <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1 or SlightPHP\Tpl::$_tpl_vars["plan_info"]->price==0){; ?>
                                        <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }else{; ?>
                                        <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                    <?php }; ?>
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
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck5" class="btn-yellow  hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck5" class="btn-yellow  hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
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
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                            <a class="btn playbox-href-btn need-member-btn cYellow hidden-md hidden-lg" href="/member.list" target="_blank"><?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>续费会员<?php }else{; ?>开通会员<?php }; ?></a>
                        <?php }; ?>
                    </div>

                    <div class="overlay" id='tryPlay' style="display:none">
                        <div class="o-layer">
                            <div class="video-tip-info">
                                <p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["playMsgInfo"]['msg']; ?></p>
                                <p class="fs16 mt10">
                                <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->course_id; ?>" target="_blank" class="btn fs12 playbox-href-btn cWhite">查看课程详情</a>
                                <!--a href="/course.list" class="cYellow mt25 ml10">去找课</a-->
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
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->name; ?><?php }; ?>
                                <?php }; ?>
                            </p>
                            <div class="o-item">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['courseFeeType'] == 0){; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->price>0){; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1){; ?>
                                            <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                        <?php }else{; ?>
                                            <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                        <?php }; ?>
                                    <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('价格','course.play'); ?>：<?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }; ?>
                                <?php }else{; ?>
                                    <?php echo tpl_modifier_tr('价格','course.play'); ?>：
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isMember"]==1 or SlightPHP\Tpl::$_tpl_vars["plan_info"]->price==0){; ?>
                                        <?php echo tpl_modifier_tr('免费','course.play'); ?>
                                    <?php }else{; ?>
                                        <span class="cYellow">￥<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->price; ?></span>
                                    <?php }; ?>
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
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow  hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <?php }else{; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><a href="" data-class="href" target="_blank" id="regcheck4" class="btn-yellow  hidden-xs "><?php echo tpl_modifier_tr('立即报名','course.play'); ?></a><?php }; ?>
                            <a href="/site.main/register" class="cYellow fs16  hidden-xs ">注册</a>
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
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMemberInfo"])){; ?>
                            <a class="btn playbox-href-btn need-member-btn cYellow hidden-md hidden-lg" href="/member.list" target="_blank"><?php if(SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']){; ?>续费会员<?php }else{; ?>开通会员<?php }; ?></a>
                        <?php }; ?>
                    </div>
                </div>

                <?php if(SlightPHP\Tpl::$_tpl_vars["plan_info"]->status==1){; ?>
                <div class="course-box" id="coursebefore" style="z-index:1;display:none">
                    <div class="overlay">
                        <div class="o-layer fs16">
                            <p><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["beforeStart"]->seconds)){; ?><span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->time_countdown; ?></span><?php }else{; ?>本节为&nbsp;&nbsp;<span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["beforeStart"]->time_countdown; ?></span>&nbsp;&nbsp;开始<?php }; ?></p>
                            <p class="mt10">要提前做好准备哟~</p>
                        </div>
                    </div>
                </div>
                <?php }; ?>
                <!-- 课堂小测试 -->
            <div id="course_test" class="course-test hidden-xs" style="display:none;">
                <div class="bar fs16" id="course_title">随堂小测试 <a href="javascript:void(0);" class="course-test-close"></a></div>
                <div class="course-test-c" id="course_main"></div>
            </div>
            <div class="detect-zoom" id="detect-zoom"><i class="white-tips"></i>显示比列已缩放，影响正常上课，请按<span> Crtl - </span>恢复<i id="detectClose" class="white-close-icon"></i></div>
        </div>
    </div>
    <!-- toolbar -->
    <div id="tool" class="tool">
        <!-- toolmain -->
        <div id="toolbar-main" class="toolbar-main">
            <!-- 简介 -->
            <div class="summary" id="summary">
                <div class="summary-title">
                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['courseUrl']; ?>" target="_blank"><span><?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['courseName']; ?></span><img class="visible-xs" src="<?php echo utility_cdn::img('/assets_v2/img/white-arrow.png'); ?>"></a>
				</div>
                <div>
                    <h6>授课讲师：</h6>
                    <div class="summary-teacher">
                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['teacherUrl']; ?>" target="_blank">
                            <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['teacherImg']; ?>">
                            <p class="summary-teacher-name"><?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['teacherName']; ?></p>
                            <p class="summary-teacher-info"><?php if(!SlightPHP\Tpl::$_tpl_vars["courseDesc"]['teacherDesc']){; ?>TA还没有写简介哦！<?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['teacherDesc']; ?><?php }; ?></p></a>
                    </div>
                </div>
                <div class="summary-content">
                    <h6>课程简介：</h6>
                    <p><?php if(!SlightPHP\Tpl::$_tpl_vars["courseDesc"]['courseDesc']){; ?>还没有简介哦！<?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseDesc"]['courseDesc']; ?><?php }; ?></p>
                </div>
            </div>
            <!-- 目录 -->
            <div class="section" id="section">
                <div class="section-num toolbar-main-title hidden-xs"><?php echo tpl_modifier_tr('目录列表','course.play'); ?></div>
                <div class="section-c toolbar-main-body" id="sectionc">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["plans_info"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["plans_info"] as SlightPHP\Tpl::$_tpl_vars["_planinfo"]){; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status==3){; ?>
                    <a plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellOrgId"])){; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?><?php }; ?>">
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status==2)){; ?>
                    <a plan_id="<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?>" href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->plan_id; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellOrgId"])){; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["resellOrgId"]; ?><?php }; ?>">
					<?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==1)){; ?>
					<a onmouseover="this.style.cssText='background:none;'">
                    <?php }else{; ?>
                    <a>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==2){; ?>
                    <p class="play-living-status"><strong class="fs14" ><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <?php if(!SlightPHP\Tpl::$_tpl_vars["needlogin"] && !SlightPHP\Tpl::$_tpl_vars["needsign"]){; ?><span class="sicon-3"><img src="<?php echo utility_cdn::img('/assets_v2/img/player-icon.gif'); ?>"></span><?php }; ?>
                    <span class="sicon-5">正在直播</span>
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["_planinfo"]->status ==1)){; ?>
                    <p><strong class="fs14" style="color:#666666"><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <span class="data-status c-fr"></span>
                    <span class="sicon-4"><?php echo tpl_modifier_tr('未开课','course.play'); ?></span>
                    <?php }else{; ?>
                    <p><strong class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["_planinfo"]->section_descipt; ?></strong>
                    <span class="data-status c-fr"></span>
                    <span class="sicon-2"><img src="<?php echo utility_cdn::img('/assets_v2/img/player-video.png'); ?>"></span><?php }; ?></p>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->type_id !=2){; ?>
                    <span class="kstime cDarkgray"><?php echo date("m月d日 H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["_planinfo"]->start_time)); ?></span>
                    <?php }; ?>
                    </a>
                    <?php }; ?>
                    <?php }; ?>
                </div>
            </div>
            <!-- 讨论 -->
            <div class="chat active" id="chat">
                <?php if(SlightPHP\Tpl::$_tpl_vars["groupId"] > 0){; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["displayAll"]){; ?>
                        <div class="chat-num toolbar-main-title curr col-xs-20" id="student_group">'<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>'：<span class="cGray"><b online>1</b></span></div>
                        <div class="toolbar-main-body">
                        <ul id="chat_group_list"></ul>
                    <?php }else{; ?>
                        <div class="toolbar-main-title chat-show-group hidden-xs">
                            <div class="chat-num curr col-xs-10" id="student_total"><?php echo tpl_modifier_tr('在线同学','course.play'); ?>：<span class="cGray"><b online>1</b></span></div>
                            <div class="chat-num col-xs-10" id="student_group">'<?php echo SlightPHP\Tpl::$_tpl_vars["groupName"]; ?>'：<span class="cGray"><b online>1</b></span></div>
                        </div>
                        <div class="toolbar-main-body">
                            <ul id="chat_list"></ul>
                            <ul id="chat_group_list" style="display:none;"></ul>
                    <?php }; ?>
                <?php }else{; ?>
                    <div class="chat-num toolbar-main-title curr col-xs-20" id="student_total">
                        <span class="hidden-xs"><?php echo tpl_modifier_tr('在线同学','course.play'); ?>：<span class="cGray"><b online>1</b></span></span>
                    </div>
                <div class="toolbar-main-body">
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
                    <div class="forum <?php if(SlightPHP\Tpl::$_tpl_vars["needlogin"] or SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?>hidden-xs<?php }; ?>" id="forum">
                        <div id="edit" class="edit" data-id="">
                            <div class="edit-c">
                                <div class="edit-icon">
                                    <div class="icon">
                                        <span style="cursor:pointer"><img id="show_pop" src="<?php echo utility_cdn::img('/assets_v2/img/icon-m.png'); ?>"></span>
                                        <div class="iconlist" id="show">
                                        </div>
                                    </div>
                                    <!-- 礼物系统 -->
                                    <div class="gift c-fl supportGift hide ml10 hide" id="gift">
                                        <div class="gift-trigger hide"><i class="icon icon-gift" id="giftsTrigger"></i>
						                   <div class="gifts" id="gifts" style="display:none">
                                                <div class="gifts-header">
                                                    <span class="c-fl cWhite fs14"><i class="icon icon-gift-exp-s mr10"></i><span id="expNum">0</span></span>
                                                    <a href="https://www.yunke.com/index.rank.rule" class="gift-exp-help cGray c-fr" target="_blank">如何获得积分</a>
                                                </div>                  
                                                <ul class="gifts-contents tac pt5" id="giftsContents">
                                                    <li class="forbid-exp" data-minExp="1" data-type="1">
                                                        <div class="gift-item gift-flower gift-send-flower gift-send not-enough" id="giftsFlower"  data-type="1">
                                                            <i class="icon icon-gift-flower"></i>
                                                            <span class="gift-count">0</span>
                                                            <p class="cWhite">鲜花</p>
                                                            <span class="gift-item-tip">赠送</span>
                                                        </div>
                                                        <p><i class="icon icon-gift-exp"></i>1</p>
                                                        <span class="gift-exchange-btn">兑换</span>
                                                    </li>
                                                    <li class="forbid-exp" data-minExp="50"  data-type="2">
                                                        <div class="gift-item gift-redName not-enough" id="giftRedName">
                                                            <div class="flip">
                                                                <div class="flip-front">
                                                                    <i class="icon icon-gift-redName-front"></i>
                                                                </div>
                                                                <div class="flip-back">
                                                                    <i class="icon icon-gift-redName-back"></i>
                                                                </div>
                                                            </div>
                                                            <span class="gift-count">0</span>
                                                            <p class="cWhite">红名特权</p>
                                                            <span class="gift-item-tip">使用<span style="display:none">中<span></span>
                                                        </div>
                                                        <p><i class="icon icon-gift-exp"></i>50</p>
                                                        <span class="gift-exchange-btn">兑换</span>
                                                    </li>
                                                    <li class="forbid-exp" data-minExp="">
                                                        <div class="gift-item not-enough locking">
                                                            <i class="icon icon-gift-lock"></i>
                                                            <p class="cGray">敬请期待</p>
                                                        </div>
                                                        <p class="cGray"><i class="icon icon-gift-exp forbid"></i>*</p>
                                                        <span class="gift-exchange-btn">兑换</span>
                                                    </li>
                                                    <li class="forbid-exp" data-minExp="">
                                                        <div class="gift-item not-enough locking">
                                                            <i class="icon icon-gift-lock"></i>
                                                            <p cass="cGray">敬请期待</p>
                                                        </div>
                                                        <p class="cGray"><i class="icon icon-gift-exp"></i>*</p>
                                                        <span class="gift-exchange-btn">兑换</span>
                                                    </li>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <!-- 礼物系统 end -->
				    <div id="pattern_reply" style="display:none;" class="c-fr">
					 <p>仅老师可见</p>
                                    </div>
                                    <div id="pattern_notalk" style="display:none;" class="c-fr">
                                        <p>禁止讨论</p>
                                    </div>
                                    <div id="ask" ask="ask" style="" class="c-fr hidden-xs">
                                        <span ask="ask" class="hand-icon hidden-xs" msg_type=2 style="display:none;"><?php echo tpl_modifier_tr('举手发言',"course.play"); ?></span>
                                        <span ask="cancel" class="handing-icon" style="display:none;"><?php echo tpl_modifier_tr('等待发言',"course.play"); ?></span>
                                        <span ask="stop" class="handed-icon" style="display:none;"><?php echo tpl_modifier_tr('停止发言',"course.play"); ?></span>
                                    </div>
                                </div>
                                <div class="edit-text">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["inputText"])){; ?>
                                    <div placeholder="对老师说点什么吧" id="chat_input" contenteditable="true" style="overflow:auto;"></div>
                                    <?php }else{; ?>
                                    <div placeholder="对老师说点什么吧" id="chat_input" contenteditable="false" style="overflow:auto;padding:15px 30px"><img src="<?php echo utility_cdn::img('/assets_v2/img/gag.png'); ?>" style="width:30px;height:30px">你已被老师禁言</div>
                                    <?php }; ?>
                                    <?php }else{; ?>
                                    <div placeholder="对老师说点什么吧" id="chat_input" contenteditable="true" style="overflow:auto"><?php if(SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll']){; ?><div id="regmind"><span class="cYellow regcheck" id="regcheck1" style="cursor:pointer;"><?php echo tpl_modifier_tr('立即报名','course.play'); ?></span><?php echo tpl_modifier_tr('参与互动讨论','course.play'); ?></div><?php }; ?></div>
                                    <?php }; ?>
                                </div>
                                <div class="edit-button">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["isReg"]){; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["inputText"])){; ?>
                                    <button id="chat_send" title=""><?php echo tpl_modifier_tr('发&nbsp;&nbsp;送',"course.play"); ?></button>
                                    <?php }else{; ?>
                                    <button id="chat_send" disenable  title=""><?php echo tpl_modifier_tr('发&nbsp;&nbsp;送',"course.play"); ?></button>
                                    <?php }; ?>
                                    <?php }else{; ?>
                                    <button id="chat_send" disenable  title=""><?php echo tpl_modifier_tr('发&nbsp;&nbsp;送',"course.play"); ?></button>
                                    <?php }; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 公告功能 -->
                    <div class="notice" id="notice">
                        <a href="javascript:void(0)" class="notice-show notice-btn fs14">公告</a>
                        <p><span>【公告】</span><span id="notice-content">一百字的公告内容之一一百字的公告内容之二一百字的公告内容之三一百字的公告内容之四一百字的公告内容之五</span></p>
                        <a href="javascript:void(0)" class="notice-close2 notice-btn">×</a>
                    </div>
                    <!-- 公告功能 end -->
                </div>
                <div class="gift-send-effect use-redName-effect" id="useRedNameEffect" style="display:none">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"])){; ?>
                    <span>
                        <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_thumb"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user_thumb"]; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/player/defaultPhoto.png'); ?><?php }; ?>"><span class="red-name-card" data-name="<?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['name']; ?></span>
                    </span>
                <?php }; ?>
                </div>
            </div>
            <!-- 排名 -->
            <div class="rank-list" id="rank-list">
                <div class="rank-num toolbar-main-title hidden-xs"><?php echo tpl_modifier_tr('学生排名',"course.play"); ?></div>
                <div class="toolbar-main-body">
                    <div class="rank-self" id="good_self">
                            <span class="face">
                                <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['medium'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user_info"]['medium']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets/images/defaultPhoto1.png'); ?><?php }; ?>">
                            </span>
                            <span class="name">
                                <p><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['real_name'])){; ?>
                                        我<a href="/index.rank.rule" class="lv-tag-icon lv<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->fk_level)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->fk_level; ?><?php }; ?>" title="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->title; ?><?php }; ?>"></a>
                                <?php }else{; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user_info"]['name'])){; ?>
                                        我<a href="/index.rank.rule" class="lv-tag-icon lv<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->fk_level)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->fk_level; ?><?php }; ?>" title="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["level"]->title; ?><?php }; ?>"></a>
                                    <?php }else{; ?>
                                        <?php echo tpl_modifier_tr('游客','course.play'); ?>
                                    <?php }; ?>
                                <?php }; ?>
                                </p>
                                <p id="rankSelfNum">暂无排名</p>
                            </span>
                        <span class="info"><b good>0</b>次</span>
                    </div>
                    <ul id="good_list"></ul>
                </div>
            </div>
            <!-- cheshireCat 笔记模块 -->
            <div class="note-list" id="note-list">
                    <div class="note-num toolbar-main-title hidden-xs">课程笔记</div>
                    <?php if(!SlightPHP\Tpl::$_tpl_vars["btnMemberInfo"]['showBtnEnroll'] && SlightPHP\Tpl::$_tpl_vars["logined"]){; ?>
                    <button id="note-add" class="c-fr fs12 note-add">新建笔记</button>
                    <?php }; ?>
                    <div class="toolbar-main-body">
                        <div class="no-note tac" id="no-note"><span>还没有任何笔记哦~</span></div>
                        <div class="note-edit-box" id="note-edit-box" style="display:none">
                            <div class="clearfix note-info"><span id="note-edit-time" class="note-time c-fl">8月20日 10:30</span></div>
                            <textarea id="note-edit-input" class="note-input"></textarea>
                            <span class="text-num c-fr"><span id="note-nowNum">0</span>/50</span>
                            <div class="clearfix"><button class="note-cancel-button c-fr" id="note-edit-cancel">取消</button><button class="note-green-button c-fr" id="note-edit-submit">确定</button></div>
                        </div>
                        <ul class="note-c clearfix" id="note-c"></ul>
                        <div class="note-popup bor1px tac" id="note-popup" style="display:none">
                            <div class="note-popup-title"><i class="c-fr cancel note-popup-close"></i></div>
                            <div class="note-popup-content">删除这条笔记？</div>
                            <div class="clearfix note-popup-btn"><button class="note-green-button submit">确定</button><button class="note-cancel-button cancel">取消</button></div>
                        </div>
                    </div>
            </div>
            <!-- cheshireCat 笔记模块 end -->
            <div id="datum-list" class="datum-list">
                <div class="datum-num toolbar-main-title hidden-xs"><?php echo tpl_modifier_tr('课件下载',"course.play"); ?></div>
                <ul class="toolbar-main-body">
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
            <div class="row content fs16">
                <a href="javascript:;" class="col-xs-10 login" id="join">点击加入学习</a>
            </div>
        </div>
        <!-- /手机登录提示 -->
        <!-- toolbar -->
        <div id="toolbar" class="toolbar swiper-container">
            <a href="#" id="rightbtn" class="rightbtn hidden-xs " title="隐藏菜单"><img class="right-btn1" src="<?php echo utility_cdn::img('/assets_v2/img/white-arrow2.png'); ?>"><img class="right-btn2" src="<?php echo utility_cdn::img('/assets_v2/img/white-arrow.png'); ?>"></a>
            <ul id="ck_list" class="swiper-wrapper">
                <li class="swiper-slide" ck="summary"><span class="tabicon-0 hidden-xs "></span><span><?php echo tpl_modifier_tr('简介','course.play'); ?></span></li>
                <li ck="section" class="swiper-slide"><span class="tabicon-1 hidden-xs "></span><span><?php echo tpl_modifier_tr('目录','course.play'); ?></span></li>
                <li ck="talk" class="selected swiper-slide"><span class="tabicon-2 hidden-xs "></span><span class="c-fl"><?php echo tpl_modifier_tr('讨论','course.play'); ?></span></li>
                <li ck="good" class="swiper-slide"><span class="tabicon-3 hidden-xs "></span><span class="c-fl"><?php echo tpl_modifier_tr('排名','course.play'); ?></span></li>
                <li id="tabiconNote" ck="note" class="swiper-slide"><span class="tabicon-4 hidden-xs "></span><span class="c-fl">笔记</span></li>
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["listAtt"])){; ?>
                <li class="swiper-slide"><span class="tabicon-5 hidden-xs "></span><span class="c-fl"><?php echo tpl_modifier_tr('课件','course.play'); ?></span></li>
                <?php }; ?>
            </ul>
        </div>
        <!-- <div class="login-layer" id="login-layer"><span class="cYellow">立即报名</span>，参与互动讨论</div> -->
    </div>
    <!-- 点名层 -->
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
    <!--引导层 笔记-->
    <div class="alert-layer guide-note hidden-xs " id="guideNote" style="display:none">
        <div class="alert-bg"></div>
        <div class="alert-guide1 alert-layer" id="guideNote1">
            <div class="alert-note">
                <img  class="alert-note-bg alert-layer" src="<?php echo utility_cdn::img('/assets_v2/img/guide-note/1.png'); ?>">
                <div class="alert-note-text">在对话框中输入你想要记录的内容，点击<span style="color:#e90000">保存按钮</span>进行记录。</div>
                <div class="alert-button">
                    <button class="alert-btn-submit" id="guideNoteNext">下一步</button>
                    <button class="alert-btn-cancel" id="guideNoteIgnore">忽略</button>
                </div>
            </div>
            <img class="alert-note-down" src="<?php echo utility_cdn::img('/assets_v2/img/guide-note/down.png'); ?>">
        </div>
        <div class="alert-guide2 alert-layer" id="guideNote2">
            <div class="alert-note">
                <img class="alert-note-bg alert-layer" src="<?php echo utility_cdn::img('/assets_v2/img/guide-note/2.png'); ?>">
                <div class="alert-note-text">在这里可以对你的笔记进行管理哦！</div>
                <div class="alert-button">
                    <button class="alert-btn-submit" id="guideNoteComplete">完成</button>
                </div>
            </div>
            <img class="alert-note-right" src="<?php echo utility_cdn::img('/assets_v2/img/guide-note/right.png'); ?>">
        </div>
    </div>
    <!--引导层 礼物-->
    <div class="alert-layer hidden-xs hidden-sm" id="guideGift" style="display:none">
        <div class="alert-bg"></div>
        <div class="gift-guide">
            <img src="<?php echo utility_cdn::img('/assets_v2/img/gift-guide.png'); ?>">
            <span class="gift-btn gift-guide-btn">知道啦</span>
            <i class=" gift-guide-close"></i>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function($){
    //手机 分享 图片效果
    function eventStopPropagation(event){
            if (event.stopPropagation) {
                event.stopPropagation();
            } else if (window.event) {
                window.event.cancelBubble = true;
            }
    }
    $('#share-mobile')
            .click(function () {
                $(this).find('.share-mobile-img').css('display','block');
            })
            .find('.close-img').on('click',function (event) {
                eventStopPropagation(event);
                $(this).parent().css('display','none');
            });
    $('#share-area')
            .click(function () {
                $(this).find('.share-sns').css('display','block');
            })
            .find('.close-img').on('click',function () {
                eventStopPropagation(event);
                $(this).parent().css('display','none');
            });
    var guideParam=getUrlParam("guide");
    guideParam =="1" && $('#guide-weixin').show();
    $('#guide-weixin').click(function(){
        $(this).hide();
    });
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

    function close_list() {
        $(window).width() > 768 && $("#content").removeClass('expend');
        setTimeout(function(){
            supportDM && DMS.resize();
        },1000);
    }

    function open_list() {
        $("#content").addClass('expend');
        setTimeout(function(){
            supportDM && DMS.resize();
        },1000);
    }

    var play_status='<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->plan_status; ?>';
    if(play_status == 'living') {
        $("#live-status").addClass("live-status-play").html("正在直播").attr('data-status',play_status);
        isLiving = true;
        $("#section").find("[plan_id=" + planId + "]").removeClass('playing').addClass('living');
        $("#note-c").removeClass('unlive');
    }else if(play_status == 'finished'){
        $("#live-status").addClass("live-status-live").html("课程回放").attr('data-status',play_status);
        isLiving = false;
        $("#note-c").addClass('unlive');
        if($(window).width()>768){
            if($('#sectionc').find('curr')){
                function autoClose_list(){
                    setTimeout(function(){
                        if(!window.forbidCloseList){
                            close_list();
                        }else{
                           autoClose_list();
                        }
                    },3000);
                }
                //autoClose_list();
            }
        }
    }

    $('#rightbtn').click(function () {
        if($("#content").hasClass('expend')) {
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
        $("#playerContent").hide();
        $("#coursebefore").hide();
    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["needsign"])){; ?>
        $("#tryOverLay").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["playMsgInfo"]['code'])){; ?>
        $("#tryPlay").show();
        $('#note-c').removeClass('unlive');
        $("#playerContent").hide();
        $("#coursebefore").hide();
    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["isReg"] && SlightPHP\Tpl::$_tpl_vars["userStatus"]['isMemberRegType'] && (SlightPHP\Tpl::$_tpl_vars["userStatus"]['isOpen'] == 0 or SlightPHP\Tpl::$_tpl_vars["userStatus"]['isExpire']))){; ?>
        $("#tryOverLay").show();
        $("#playerContent").hide();
        $("#coursebefore").hide();
    <?php }else{; ?>
        $("#coursebefore").show();
    <?php }; ?>

    //评论限制字数
    $('#chat_input').on('input', function(event) {
        event.preventDefault();
        var t = $(this);
        var v = t.html();
        var v2 = '';
        v2 = v.replace(_url2flagReg, img2flag);
        v2 = v2.replace(/<.*?>/g, "");
        v2 = v2.replace(/^((&nbsp;)|( )|(\u3000))*/g, "");
        v2 = v2.replace(/((&nbsp;)|( )|(\u3000))*$/g, "");
        var l = v2.replace(/[\u0391-\uFFE5]/g,"aa").length;
        l > 200 ? t.addClass('error') : t.removeClass('error');
    });
    if(!$.support.opacity){
        $('#chat_input').on('blur',function(event){
            $(this).trigger('input');
        });
        $('#chat_input').on('keyup',function(event){
            $(this).trigger('input');
        });
    }

    /*设置goodself*/
    var goodlist2 = $("#good_list");
    var goodself2 = $("#good_self");
    var s2 = parseInt(goodlist2.find("li[userid=" + window.userId + "]").find("i[good2]").text());
    s2 = !isNaN(s2)&&s2>0?("第"+s2+"名"):'暂无排名';
    goodself2.find("i[good2]").text(s2);
    var checkleft = "<?php echo SlightPHP\Tpl::$_tpl_vars["plan_info"]->status; ?>";

    $('#ck_list>li').click(function () {
        $(this).hasClass('selected') && $('#content').hasClass('expend') ? close_list() : open_list();
        $(this).addClass('selected').siblings().removeClass('selected');
        $('#toolbar-main>div').eq($(this).index()).addClass('active').siblings().removeClass('active');
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
    //checkleft == 2 ? $("#toolbar li[ck=talk]").trigger("click") : $("#toolbar li[ck=section]").trigger("click");

    $(".upload_attach_msg").click(function(){
        if(0==isLogin){
            layer.msg("请登录后下载");
        }else if(0==isReg){
            layer.msg("请报名后下载");
        }
    });
});
    function detectZoom(){
        var ratio = 0,screen = window.screen,ua = navigator.userAgent.toLowerCase();
        if( ua.indexOf('firefox') > 0 ){ //火狐浏览器
            if( window.devicePixelRatio !== undefined ){
                ratio = window.devicePixelRatio;
            }
        }else if( ua.indexOf('msie') > 0 ){  //IE浏览器
            if( screen.deviceXDPI && screen.logicalXDPI ){
                ratio = screen.deviceXDPI / screen.logicalXDPI;
            }
        }else if( window.outerWidth !== undefined && window.innerWidth !== undefined ){
            ratio = window.outerWidth / window.innerWidth;
        }
        if( ratio ){
            ratio = Math.round( ratio * 100 );
        }
        if( ratio === 99 || ratio === 101 ){
            ratio = 100;
        }
        return ratio;
    }
    $('#detectClose').click(function(){
        $('#detect-zoom').hide();
        var d = new Date();
        var ed = new Date(d.getFullYear(),d.getMonth(),d.getDate()+1,0,0,0);
        $.cookie('detectZoom',1,{ expires: ed,path: '/' });
    });
    function swiperTab(){
        var li = $('#ck_list li');
        var ul = $('#ck_list');
        if($(window).width() < 769){
            var outerWidth = $(window).width();
            var realWidth = li.length * 70;
            var max = realWidth - outerWidth;
            if(max > 0){
                $('#ck_list').width(realWidth);
                bindSwiper.call($('#ck_list'),max);
            }
        }else{
            ul.removeAttr('style');
        }
    }
    function playerbox() {
        var w = $(window).width();
        var h = $(window).height();
        var dZoom = detectZoom();
        if(w <= 768){
            $('#tool').height(h - parseInt(w * 9 / 16) - $('#h5Toolbar').height());
            $('#coursebox').height(parseInt(w * 9 / 16) + $('#h5Toolbar').height());
            $('#content').addClass('expend');
        }else{
            $('#tool').height('auto');
            $('#coursebox').height('100%')
        }
        if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            if(dZoom == 100 ){
                $('#detect-zoom').hide();
            }else if($.cookie('detectZoom') != 1){
                $('#detect-zoom').show().find('span').html(' Ctrl+ 0');
            }
        }
        swiperTab();
    };
    //视频断开后弹层
    function showPlayerMessage(msg1,butType,msg2){
        $('#callPlayerMsg1').text(msg1);
        $('#callPlayerMsg2').text(msg2||'');
        var reloadBtn = $('#callPlayerMsgButton');
        butType == 0 ? reloadBtn.show() : reloadBtn.hide();
        $('#callPlayerMsg').show();
    }
    function reloadPage(){
		// fix weixin reload
		if (userApi.isWeiXin()) {
		window.location.href=window.location.href+"?"+10000*Math.random();
		}else{
		window.location.href=window.location.href;
		}
    }
    function hidePlayerMessage() {
        $('#callPlayerMsg').hide();
    }
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }
    $('.o-close-btn').click(function(){
        $('.o-vip-end').hide();
    });
    //if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
       // $('body').append('<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/web2app.js'); ?>"><\/script>');
    //}

    function bindSwiper(max){
        if(!$(this).attr('data-swiper')){
            var x0 = 0;
            var left = 0;
            var flag = false;
            var xs = 0;
            var d = 0;
            var xe = 0;
            $(this)
            .attr('data-swiper',1)
            .on('touchstart',function(event){
                flag = true;
                x0 = event.originalEvent.touches[0].pageX;
                xe = parseInt($(this).css('margin-left'));
            })
            .on('touchmove',function(event){
                event.preventDefault();
                if(!flag){
                    return false;
                }
                xs = event.originalEvent.touches[0].pageX;
                d = xs - x0;
                left = xe + d;
                left = left > 0 ? 0 : left;
                left = left < -max ? -max : left;
                left = left ;
                $(this).css('margin-left',left+'px');
            })
            .on('touchend',function(event){
                flag = false;
            })
        }
    }
    $(window).width() < 769 && swiperTab();
    // 弹幕动态加载
    if(supportDM){
        $('body').append('<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/course_barrage_v2.js'); ?>"><\/script>');
    }else{
        $('.DM_box').hide();
    }
    // h5 线路切换 在线人数切换
    $('.openListBtn').click(function(){
        $(this).parent().toggleClass('active').siblings().removeClass('active');
    });
    $('#switch-lines').on('click','li',function(){
        var _this=$(this);
        var _self_hls=_this.attr('data-hls');
        _this.addClass('active').siblings().removeClass('active');
        H5Player._route = _self_hls;
        H5Player.changeRoute();
    });
    $('.h5chatNum li').click(function(){
        var self = $(this);
        self.addClass('active').siblings().removeClass('active');
        self.parents('.h5ToolbarCnt').find('.openListBtn').html(self.html());
        $(self.attr('data-to')).show().siblings('ul').hide();
    });
    $(document).click(function(event){
        var target = $(event.target);
        if(!target.hasClass('openListBtn')&&!target.parent().hasClass('openListBtn')){
            $('.h5ToolbarCnt').removeClass('active');
        }
    });
    // h5 线路切换 在线人数切换 end
</script>
<div style="display:none">
<?php echo tpl_function_part('/site.main.statistic'); ?>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253775234'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1253775234' type='text/javascript'%3E%3C/script%3E"));</script>
</div>
</body>
</html>
