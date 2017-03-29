require.config({
    baseUrl: '/assets_v2/js/',
    paths: {
        'jquery': 'jquery/jquery-1.11.1.min', // jquery
        'share': 'jquery.share', // 插件-分享
        'cookie': 'jquery.cookie', // 插件-cookie https://github.com/carhartl/jquery-cookie#readme
        'layer': '../layer/layer', // 插件-弹出层 http://layer.layui.com/
        'jquery.WebSocket': 'jquery.WebSocket', // 插件 websocket兼容
        'swfobject': 'swfobject', // 插件-flash播放器
        'template': 'player/components/template', //插件 模板渲染
        'playerFlash': 'player', // 模块-播放API
        'playerPc': 'player/components/playerPc', // 模块-播放API
        'playerH5': 'player/components/playerH5', // 模块-H5视频播放
        //'message': 'player/components/message', // 模块-websocket
        'message': 'player/components/message', // 模块-websocket
        'remindCard': 'common/remindCard', // 模块-卡片提示
        'user': 'user', // 模块-user API
        'view': 'player/components/view', // 模块 普通view 事件，建议杂碎事件写这个里
        'chat': 'player/components/chat', // 模块 聊天模块
        'note': 'player/components/note', // 模块 笔记模块
        'util': 'player/components/util', // 模块 通用工具类函数
        'global': 'player/components/global', // 模块 存放全局对象
        'userApi': 'user',
        'rank' : 'player/components/rank', // 模块 排名模块
        'living' : 'player/components/living', // 模块 排名模块
        'curtain' : 'player/components/curtain', // 模块 视频表面信息层
        'fullscreen': ''
    },
    shim: {
        'share': {
            deps: ['jquery'],
            exports: '$'
        },
        'cookie': {
            deps: ['jquery'],
            exports: '$'
        },
        'layer': {
            deps: ['jquery'],
            exports: 'layer'
        },
        'jquery.WebSocket': {
            deps: ['jquery'],
            exports: '$'
        },
        'template': {
            exports: 'template'
        },
        'swfobject': {
            exports: 'swfobject'
        },
        'playerFlash': {
            exports: 'Player'
        },
        'userApi': {
            exports: 'userApi'
        }
    }
});

require(['require', 'jquery', 'global'], function(require, $, global) {
    window.global = global;
    $(document).ready(function($) {
        var plan_id = window.location.href.match(/.playV3\/(\d+)\D?/)[1];
        var resellOrgId = $('#resellOrgId').val();
        global.set({
            plan_id: plan_id,
            resellOrgId: resellOrgId
        });
        //加载首屏数据
        $.ajax({
                url: '/course/plan/playData',
                type: 'POST',
                dataType: 'json',
                data: {
                    planId: plan_id,
                    resellOrgId: resellOrgId
                }
            })
            .done(function(res) {
                // .always(function(res) { res = {code: 0};
                console.log(res);
                if (res.code == 0) {
                    var data = res.data;
                    // 存储数据
                    global.set(data);
                    global.set({
                        isLogin: data.logined,
                        isSign: data.isReg,
                        coursePrice: data.isMember ? 0 : data.plan_info.price,
                        teacherName: data.teacher.profile.real_name || data.teacher.name,
                        teacherId: data.teacher.uid
                    });
                    // 渲染数据
                    renderData(data);
                    require(['message']);
                } else {
                    console.error('初始化数据获取失败');
                }
            })
            .then(function() {
                // view事件绑定
                require(['view']);
                // 加载视频信息层
                require(['curtain']);
                // 加载播放器
                require(['playerPc']);
                // 加载直播
                require(['living']);

            })
            .fail(function(){
                console.error('获取初始化数据失败');
            });


        function renderData(data) {
            require(['template'], function(template) {
                // 渲染头部
                render('hCourse', data.plan_info);
                var resellOrgId = !resellOrgId ? '' : ('/' + resellOrgId);
                $('#hCourse').attr('href', '/course.info.show/' + data.plan_info.course_id + resellOrgId);
                var teacher = data.teacher;
                $('#hTeacher').attr('src', '/teacher.detail.entry/' + teacher.uid);
                teacher.avatar.small && $('#hTeacherThumb').attr('src', teacher.avatar.small);
                $('#hTeacherName').text(teacher.profile.real_name || teacher.name);
                // 渲染简介
                render('summary', data.courseDesc);
                // 渲染目录
                data.plans_info.length > 0 && render('catalog', {
                    resellOrgId: resellOrgId,
                    plan_id: plan_id,
                    data: data.plans_info
                });
                // 渲染课件
                if (data.isReg) {
                    render('courseware', {
                        cdn: data.filecdn,
                        list: data.listAtt
                    });
                    $('.support-courseware').show();
                }
                // 设置登录状态
                if (data.logined) {
                    $('#userName').text(data.user_info.name);
                    if (data.isAdmin) {
                        $('#navOrg').show();
                    } else if (data.isTeacher) {
                        $('#navTeacher').show();
                    } else {
                        $('#navGrowth').show();
                    }
                    $('#loginAfter').show().siblings().hide();
                }
                // 设置报名状态
                if(!data.isReg){
                    $('.sign-after').hide();
                    $('.sign-before').show();
                }else{
                    $('.sign-after').show();
                    $('.sign-before').hide();
                }
                function render(id, item) {
                    var o = $('#' + id);
                    o.html(template(o.attr('data-tpl'), item));
                }
            });
        }
    });
});
