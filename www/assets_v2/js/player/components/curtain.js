// 课前课后层 模块
define(['jquery', 'global', 'template', 'util', 'layer'], function($, global, template, $, layer) {
    var g = global.get(['plan_id', 'user_id', 'teacher_name', 'teacherId', 'user_owner', 'plan_info', 'teacherName', 'userStatus', 'isMember', 'courseMemberInfo', 'isSign', 'isLogin', 'btnMemberInfo', 'playMsgInfo']);
    // var isLive = global.getFunc('islive');
    var planInfo = g.plan_info;
    var courseId = planInfo.course_id;
    var planId = g.plan_id;
    var teacherName = g.teacherName;
    var userStatus = g.userStatus;
    var isMember = g.isMember;
    var isLogin = g.isLogin;
    var isSign = g.isSign;
    var courseMemberInfo = g.courseMemberInfo;
    var btnMemberInfo = g.btnMemberInfo;
    var playMsgInfo = g.playMsgInfo;
    var teacherId = g.teacherId;
    var userOwner = g.user_owner;
    $.preloadimages(['/assets_v2/img/player/playerbg.jpg']);

    function renderModuleFile(file, data, callback) {
        $.ajax({
                url: file,
                dataType: 'html'
            })
            .done(function(res) {
                res = template.compile(res)(data);
                callback(res);
            });
    }
    var curtain = {
        container: $('#curtain'),
        idPrefix: 'curtain_',
        classPrefix: 'curtain-',
        itemClass: 'curtain-item',
        endType: '',
        showCurtain: function(op) {
            var _this = this;
            var dft = {
                moduleType: 'courseInfo', // courseInfo:课程信息模板，msg:信号信息模板，custom:自定义
                content: '',
                tryEnd: false,
                courseName: planInfo.title || '',
                className: planInfo.class_name || '',
                teacherName: teacherName,
                isLogin: isLogin,
                isSign: isSign,
                btnMemberInfo: btnMemberInfo,
                userStatus: userStatus,
                courseMemberInfo: courseMemberInfo,
                planInfo: planInfo
            };
            var opt = $.extend({}, dft, op);
            var status = opt.status;
            if (!$('#' + _this.idPrefix + status)[0]) {
                _this.render(opt, function(content) {
                    var it = $(content).eq(0);
                    it.attr('id', _this.idPrefix + status).addClass(_this.itemClass).addClass(_this.classPrefix + opt.moduleType).appendTo(_this.container).show();
                    _this.showContainer(status, opt);
                });
            } else {
                _this.showContainer(status, opt);
            }
        },
        showContainer: function(status, opt) {
            var item = $('#' + this.idPrefix + status);
            var _this = this;
            var type = opt.endType;
            if (status == 'courseEnd') {
                if (type == 'live') {
                    _this.dealEndLive(item);
                    item.find('.courseEnd-live').show().siblings('.courseEnd-video').hide();
                } else if (type == 'video') {
                    _this.dealEndVideo(item);
                    item.find('.courseEnd-video').show().siblings('.courseEnd-live').hide();
                }
                _this.dealEndComment(type, function() {
                    item.siblings('.' + _this.itemClass).hide();
                    _this.container.show();
                });
            } else {
                item.siblings('.' + _this.itemClass).hide();
                _this.container.show();
            }
        },
        dealEndLive: function(item) {
            var rank = global.get('userRank') || 0;
            var good = global.get('userGood') || 0;
            rank = rank > 0 ? ('第' + rank + '名') : '暂无排名';
            item.find('.courseEnd-live-rank').text(rank);
            item.find('.courseEnd-live-good').text(good);
        },
        dealEndVideo: function(item) {
            item.find('.courseEnd-video-reStudy').attr('href', ('/course.plan.play/' + planId));
            var plans = global.get('plans_info');
            var l = plans.length;
            if (l > 0) {
                $.each(plans, function(i, v) {
                    plans[i] = parseInt(v.plan_id);
                });
                var index = plans.indexOf(planId);
                if (index > -1) {
                    if (index >= l - 1) {
                        item.find('.courseEnd-video-next').hide();
                    } else {
                        item.find('.courseEnd-video-next').attr('href', ('/course.plan.play/' + plans[index + 1])).show();
                    }
                }
            }
        },
        renderEndComment: function(type, data) {
            var comment = data.comment || '';
            var score = data.score || '';
            var time = data.create_time || '';
            var section = data.section || '';
            if (time) {
                time = time.split(' ')[0];
            }
            var item = this.container.find('.courseEnd-commented');
            var userInfo = global.get('user_info');
            userInfo.large && item.find('.userthumb').attr('src', userInfo.large);
            item.find('.more').attr('href', ('/course.info.show/' + courseId));
            item.find('.username').text(userInfo.real_name || userInfo.name);
            item.find('.contents').text(comment);
            item.find('.time').text(time);
            item.find('.section').text(section);
            item.find('.score').text(score);
            item.find('.courseEnd-comment-stars').attr('data-star', score);
        },
        dealEndComment: function(type, callback) {
            var _this = this;
            _this.container.find('#courseEnd-input').val('');
            _this.changeStar('',true);
            if (isLogin && isSign) {
                $.post('/comment/course/CheckISAddScore', {
                        course_id: courseId,
                        plan_id: planId
                    },
                    function(d) {
                        var item = _this.container;
                        if (d.result.code == 0) {
                            // 已评论
                            _this.renderEndComment(type, d.result.data);
                            item.find('.courseEnd-comment').hide();
                            item.find('.courseEnd-commented').show();
                        } else {
                            item.find('.courseEnd-comment').show();
                            item.find('.courseEnd-commented').hide();
                        }
                        (typeof callback == 'function') && callback();
                    }, 'json'
                );
            } else {
                (typeof callback == 'function') && callback();
            }
        },
        showCourseEnd: function(type) {
            this.endType = type;
            this.showCurtain({
                status: 'courseEnd',
                moduleType: 'custom',
                endType: type
            });
        },
        showLiveEnd: function() {
            this.showCourseEnd('live');
        },
        showVideoEnd: function() {
            this.showCourseEnd('video');
        },
        getContentCourseInfo: function(opt, callback) {
            var data = opt;
            // 处理价钱
            var hasPrice = false;
            var price = 0;
            if (userStatus.courseFeeType == 0) {
                if (planInfo.price > 0 && !isMember) {
                    hasPrice = true;
                    price = planInfo.price;
                }
            } else {
                if (!(isMember == 1 || planInfo.price == 0)) {
                    hasPrice = true;
                    price = planInfo.price;
                }
            }
            data.hasPrice = hasPrice;
            data.price = price;
            // 处理会员
            var hasMemberInfo = false;
            var memberInfo = [];
            $.each(courseMemberInfo, function(k, v) {
                var o = {};
                o.setId = v.setId;
                o.title = v.title;
                if (!userStatus.userMemberSet[k]) {
                    o.status = 'open';
                    o.text = '马上开通';
                } else {
                    if (userStatus.userMemberSet[k]['is_expire'] == 0) {
                        o.status = 'openContinue';
                        o.text = '立即续费';
                    } else {
                        o.status = 'reOpen';
                        o.text = '重新开通';
                    }
                }
                memberInfo.push(o);
            });
            data.hasMemberInfo = hasMemberInfo;
            data.memberInfo = memberInfo;
            renderModuleFile('/assets_v2/js/player/tpl/courseInfo.js', data, callback);
        },
        getCoureEnd: function(opt, callback) {
            var data = opt;
            renderModuleFile('/assets_v2/js/player/tpl/courseEnd.js', data, callback);
        },
        render: function(opt, callback) {
            if (typeof callback != 'function') {
                return false;
            }

            function packCode(m) {
                if (!m) {
                    return ''
                }
                var cStart = '<div class="curtain-item tac cWhite fs16" style="display:none"><div class="curtain-item-content">';
                var cEnd = '</div></div>';
                return cStart + m + cEnd;
            }
            var m = '';
            if (opt.moduleType == 'courseInfo') {
                // 课程信息
                this.getContentCourseInfo(opt, callback);
            } else if (opt.moduleType == 'msg') {
                // 异常信息
                m = '<p>' + opt.content + '<p><p class="mt10"><a href="/course.info.show/4046" target="_blank" class="button-sm button-white button-yellow-hover fs12 cWhite" style="padding-left:10px;padding-right:10px">查看课程详情</a></p>'
                callback(packCode(m));
            } else if (opt.moduleType == 'custom') {
                var status = opt.status;
                if (status == 'courseBefore') {
                    // 开课前
                    var beforeStart = global.get('beforeStart');
                    var s = beforeStart.seconds || 0;
                    var t = beforeStart.time_countdown || '';
                    if (s) {
                        m = '<p>本节为<span class="ml10 mr10 cYellow">' + t + '</span>开始</p>';
                    } else {
                        m = '<p><span class="cYellow">' + t + '</span></p>';
                    }
                    m += '<p class="mt10">要提前做好准备哟~</p>';
                    return packCode(m);
                } else if (status == 'courseEnd') {
                    // 课程结束
                    this.getCoureEnd(opt, callback);
                }
            }
        },
        commentStar: 0,
        commentStarTexts: ['','很差', '差', '还行', '满意', '很好'],
        changeStar: function(obj, flag) {
            var index = 0;
            if($(obj)[0]){
                index = $(obj).index() + 1;
            }
            $('#courseEnd-comment-stars-text').text(this.commentStarTexts[index]);
            $('#courseEnd-comment-stars').attr('data-star', index);
            if (flag) {
                this.commentStar = index;
            }
        },
        submitCommentFlag: false,
        submitComment: function() {
            if (this.submitCommentFlag) {
                layer.msg('评论中...');
                return;
            }
            this.submitCommentFlag = true;
            var _this = this;
            var input = _this.container.find('#courseEnd-input');
            var star = _this.container.find('#courseEnd-comment-stars');
            var score = star.attr('data-star');
            if (!score) {
                layer.msg('您还没有打分呢！');
                return;
            }
            var t = $.trim(input.val());
            if (!t) {
                layer.msg("评论不能为空！");
                return;
            }
            if (t.length < 5) {
                layer.msg("评论不能少于4个字哦");
                return;
            }
            var request = {};
            request.score = score;
            request.user_teacher = teacherId;
            request.plan_id = planId;
            request.user_owner = userOwner;
            request.comment = t;
            request.course_id = courseId;
            $.ajax({
                    url: '/comment/course/addscore',
                    type: 'POST',
                    dataType: 'json',
                    data: request
                })
                .done(function(res) {
                    if (res.code == 1) {
                        var data = res.result.data;
                        var o = {
                            score: data.score,
                            comment: data.comment,
                            section: data.section,
                            time: data.create_time
                        }
                        _this.renderEndComment(_this.endType, o);
                        var item = _this.container;
                        item.find('.courseEnd-comment').hide();
                        item.find('.courseEnd-commented').show();
                        // 评论加经验积分以及升级
                        var result = res.result;
                        $.add_score_point({
                            addScore: result.addScore || 0,
                            upType: result.upType || 0,
                            level: result.userLevel || 0,
                            score: result.currentScore || 0
                        });
                        layer.msg("感谢评价");
                        return false;
                    } else if (res.code == 2043) {
                        layer.msg("已经评价");
                        _this.showCourseEnd(_this.endType);
                        return false;
                    }
                })
                .always(function() {
                    this.submitCommentFlag = false;
                });
        },
        bind: function() {
            var _this = this;
            _this.container
                .on('click', '.btn-sign', function(event) {
                    event.preventDefault();
                    $.login_sign();
                })
                .on('click', '.curtain-top-close', function(event) {
                    event.preventDefault();
                    // 关闭顶部提示条
                    $(this).closest('.curtain-top').hide();

                })
                .on('click', '.open-vip', function(event) {
                    event.preventDefault();
                    // 开通会员
                    var setId = $(this).parent('p').attr('data-setId');
                    window.location.href = "/order.main.memberinfo/"+setId;
                })
                // 星级评价
                .on('mousemove', '.courseEnd-comment-star-item', function() {
                    _this.changeStar(this, false);
                })
                .on('mouseleave', '#courseEnd-comment-stars', function() {
                    $(this).attr('data-star', _this.commentStar);
                    $('#courseEnd-comment-stars-text').text(_this.commentStarTexts[_this.commentStar]);
                })
                .on('click', '.courseEnd-comment-star-item', function() {
                    _this.changeStar(this, true);
                })
                .on('click', '#courseEnd-input-button', function() {
                    _this.submitComment();
                })
                .on('keyup', '#courseEnd-input', function(event) {
                    event.preventDefault();
                    if ((10 == event.keycode || 13 == event.keycode) && event.ctrlkey) {
                        _this.submitComment();
                    }
                });
        },
        init: function() {
            var _this = this;
            _this.bind();
            if (!isLogin || !isSign) {
                this.showCurtain({
                    status: 'normal'
                });
            }
            if (playMsgInfo.code) {
                this.showCurtain({
                    status: 'msg' + playMsgInfo.code,
                    moduleType: 'msg',
                    content: playMsgInfo.msg
                });
                $('#note-c').removeClass('unlive');
            } else if (isSign && userStatus['isMemberRegType'] && (userStatus['isOpen'] == 0 || userStatus['isExpire'])) {
                this.showCurtain({
                    status: 'normal'
                });
            } else if (planInfo.status == 1) {
                this.showCurtain({
                    status: 'courseBefore',
                    moduleType: 'custom'
                });
            }
        }
    }
    curtain.init();
    return curtain;
});
