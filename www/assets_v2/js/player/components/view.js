// 杂项
define(['util', 'global', 'require', 'share', 'layer','message'], function($, global, require, $, layer,message) {
    // 侧边栏切换
    var g = global.get(['isLogin', 'isReg', 'plan_info', 'isSign', 'resellOrgId', 'userStatus']);
    var isLogin = g.isLogin;
    var isSign = g.isSign;
    var isReg = g.isReg;
    var resellOrgId = g.resellOrgId;
    var plan_info = g.plan_info;
    var course_id = plan_info.course_id;
    var class_id = plan_info.class_id;
    var source = 'reg';
    var courseFeeType = g.userStatus.courseFeeType;
    var coursePrice = g.coursePrice;

    var content = $('#content');
    $('#tabTriggers').on('click', '.tab-trigger', function() {
        if (!$(this).hasClass('active')) {
            content.addClass('openSide');
        } else {
            content.toggleClass('openSide');
        }
        $(this).toggleActive();
        $('.tab-content').toggleActive($(this).index());
    });
    $('#asideSwitch').click(function() {
        content.toggleClass('openSide');
    });
    // 登录报名弹出层
    $('.btn-sign,.btn-login').click($.login_sign);
    // 动态触发笔记模块
    $('#noteTrigger,#qute-note').click(function() {
        if (!global.get('noteMoudleInit')) {
            require(['note']);
        }
    });
    // 分享
    $('#shareSns .share-sns-content').share({
        sPic: global.get('shareImg'),
        wxHref: global.get('qrCode')
    });
    $('#shareSns').on('click', '.share-sns-btn', function() {
            $('#shareSns').toggleClass('active');
        })
        .on('click', '.close', function() {
            $('#shareSns').removeClass('active');
        });
    $('#shareMobile').click(function() {
        $(this).toggleClass('active');
    });
    if (isLogin && isReg && plan_info.status != 1) {
        $('#stoolComment').show();
        $("#stoolComment").click(function() {
            var g = global.get(['teacher', 'plan_id', 'user_owner', 'plan_info']);
            var teacherId = g.teacher.uid;
            var planId = g.plan_id;
            var user_owner = g.user_owner;
            var courseId = g.plan_info.course_id;
            layer.open({
                type: 2,
                title: ['评论'],
                area: ['430px', '280px'],
                shade: 0.8,
                closeBtn: 1,
                shadeClose: true,
                content: '/layer.main.playerComment?teacherId=' + teacherId + '&planId=' + planId + '&userOwner=' + user_owner + '&courseId=' + courseId
            });
        });
    }
    // 表情
    $('#emojiTrigger').click(function() {
        $('#emojisList').toggle();
    });
    $('#emojisList').on('click', '.emoji-item', function(event) {
        var a = document.execCommand("insertImage", false, $.urlPath(event.target.src));
        if (!a) {
            $("#chat_input").append("<img src='" + $.urlPath(event.target.src) + "' />");
        }
    });
});
