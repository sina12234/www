// note 笔记模块
define(['jquery', 'global', 'layer'], function($, global, layer) {
    console.log('笔记模块加载成功');
    global.set({
        'noteMoudleInit': true
    });
    $(document).ready(function($) {
        var g = global.get(['plan_id', 'isPc', 'enableDM', 'isSign','isLogin']);
        var isLive = global.getFunc('isLive');
        var plan_id = g.plan_id;
        var isPc = g.isPc;
        var enableDM = g.enableDM;
        var isLogin = g.isLogin;
        var isSign = g.isSign;
        if (enableDM) {
            var DM_note = global.DM_note;
        }

        function getVideoStatus() {
            return isLive() ? 2 : 3;
        }
        var layerSkin = {
            skin: 'layui-layer-player'
        };
        var api = {
            add: '/course.note.NoteAdd',
            del: '/course.note.DelNote',
            edit: '/course.note.UpdateNote',
            load: '/course.note.NoteList',
            guideGet: '/course.note.noteInfo'
        };

        function getPlayTimeString(name) { //详情页笔记到播放页
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        };
        (function() {
            var t = null;
            var time = location.href.match(/play_time=(\d+)/)
            time = !time ? NaN : parseInt(time[1]);
            var canSeek = false;

            function jump() {
                if (!isPc) {
                    canSeek = global.H5Player && !H5Player.$player[0].currentTime ? false : true;
                } else {
                    canSeek = global.Player && Player.info() && Player.info().playerState == 'playing' ? true : false;
                }
                if (canSeek) {
                    !isPc ? global.H5Player.seek(time) : global.Player.seek(time);
                } else {
                    setTimeout(function() {
                        jump();
                    }, 200);
                }
            }!isNaN(time) && jump();
        })();
        var el = {
            note: $('#note-list'),
            list: $('#noteContents'),
            list_add: $('#note-add'),
            list_del: '.note-del',
            list_edit: '.note-edit',
            list_content: '.note-content',
            list_time: '.note-time',
            list_del_popup: $('#note-popup'),
            list_del_popup_submit: $('#note-popup .submit'),
            list_del_popup_close: $('#note-popup .cancel'),
            edit: $('#note-edit-box'),
            edit_input: $('#note-edit-input'),
            edit_save: $('#note-edit-submit'),
            edit_cancel: $('#note-edit-cancel'),
            edit_time: $('#note-edit-time'),
            edit_countNum: $('#note-nowNum'),
            edit_count: $('#note-nowNum').parent(),
            down: $('#qute-note'),
            down_input: $('#qute-input'),
            down_time: $('#qute-time'),
            down_btn: $('#qute-btn'),
            down_save: $('#qute-submit'),
            down_cancel: $('#qute-cancel'),
            module_list: $('#note-list-module').html(),
            noNote: $('#no-note'),
            quteTip: $('#quteTip')
        };
        var temp = {
            edit_id: '',
            edit_time: '',
            edit_content: '',
            edit_obj: '',
            edit_type: '',
            edit_reset: function() {
                this.edit_id = '';
                this.edit_time = '';
                this.edit_content = '';
                this.edit_obj = '';
                this.edit_type = '';
            },
            play_time: 0,
            del_obj: '',
            del_id: ''
        };
        var maxNoteCount = 50;
        var quteTime = '';
        var NOTE = function() {
            this.nowCount = 0;
            isSign && el.list_add.show();
            isLive() ? el.list.removeClass('unlive') : el.list.addClass('unlive');
            this.bind();
            this.getListData();
            return this;
        }
        NOTE.prototype = {
            getListData: function() {
                if (!isNaN(plan_id) && plan_id > 0) {
                    var self = this;
                    var op = {
                        url: api.load,
                        param: {
                            plan_id: plan_id,
                            videoStatus: getVideoStatus()
                        },
                        success: function(res) {
                            if (res.data.totalSize > 0) {
                                self.nowCount = res.data.totalSize;
                                el.noNote.hide();
                                self.renderList(res.data.items);
                                if (enableDM) {
                                    res.data.items.forEach(function(x) {
                                        if (x.play_time && !isNaN(x.play_time)) {
                                            var stime = parseInt(x.play_time);
                                            stime = stime < 0 ? 0 : stime;
                                            DM_note.insert({
                                                text: x.content,
                                                ex: {
                                                    dbid: x.id,
                                                    stime: stime,
                                                    size: 20,
                                                    color: 0xffffff,
                                                    border: true
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        }
                    };
                    ajax(op);
                }
            },
            renderList: function(data) {
                el.list.html(render({
                    str: el.module_list,
                    data: data
                }));
            },
            data_add: function(_content) {
                var self = this;
                var content = _content == '' ? '重点' : _content;
                if (verInputCount(content)) {
                    self.edit_error();
                    return false;
                }
                if (this.nowCount >= maxNoteCount) {
                    layer.msg('最多只能添加' + maxNoteCount + '条笔记', layerSkin);
                    return false;
                }
                var op = {
                    url: api.add,
                    param: {
                        content: content,
                        play_time_tmp: temp.play_time,
                        plan_id: plan_id,
                        videoStatus: getVideoStatus()
                    },
                    success: function(res) {
                        el.list.prepend(render({
                            str: el.module_list,
                            data: [{
                                play_time_format: res.play_time_format,
                                play_time_tmp_handle: res.play_time_tmp_handle,
                                content: content,
                                id: res.note_id
                            }]
                        }));
                        if (enableDM && res.play_time && !isNaN(res.play_time)) {
                            var stime = parseInt(res.play_time);
                            stime = stime < 0 ? 0 : stime;
                            var DM_content = {
                                text: content,
                                ex: {
                                    dbid: res.note_id,
                                    stime: stime,
                                    size: 20,
                                    color: 0xffffff,
                                    border: true
                                }
                            };
                            DM_note.insert(DM_content);
                            if (enableDM) {
                                DM_note.send(DM_content);
                            }
                        }
                        self.nowCount += 1;
                        self.nowCount > 0 ? el.noNote.hide() : el.noNote.show();
                        if (el.down.hasClass('active')) {
                            self.quteTipAnimate();
                        }
                        self.edit_cancel();
                        self.down_cancel();
                        //layer.msg('添加成功');
                    },
                    fail: function(res) {
                        layer.msg(res.msg, layerSkin);
                    }
                };
                return ajax(op);
            },
            add_save: function() {
                if (this.forbidAdd()) {
                    return false;
                }
                this.data_add(el.edit_input.val());
                return this;
            },
            add_cancel: function() {
                this.nowCount > 0 ? el.noNote.hide() : el.noNote.show();
                this.edit_cancel();
            },
            edit_save: function() {
                var self = this;
                var content = el.edit_input.val();
                content = content == '' ? '重点' : content;
                if (verInputCount(content)) {
                    self.edit_error();
                    return false;
                }
                self.edit_unerror();
                var op = {
                    url: api.edit,
                    param: {
                        content: content,
                        note_id: temp.edit_id,
                        plan_id: plan_id,
                        videoStatus: getVideoStatus()
                    },
                    success: function() {
                        temp.edit_obj.find(el.list_content).text(content);
                        if (enableDM) {
                            DM_note.edit(temp.edit_id, content);
                        }
                        self.edit_cancel();
                    },
                    fail: function(res) {
                        if (res.code == -120) {
                            self.edit_cancel();
                        } else {
                            layer.msg(res.msg, layerSkin);
                        }
                    }
                };
                return ajax(op);
            },
            edit_cancel: function() {
                if (temp.edit_type == 'edit') {
                    temp.edit_obj.removeClass('active');
                }
                temp.edit_reset();
                el.edit_input.val('');
                this.setEditInput();
                this.edit_hide();
            },
            edit_hide: function() {
                el.edit.hide();
                el.list.after(el.edit);
            },
            edit_error: function(type) {
                if (type == 'edit') {
                    el.edit.addClass('error');
                } else if (type == 'down') {
                    el.down.addClass('error');
                }
            },
            edit_unerror: function(type) {
                if (type == 'edit') {
                    el.edit.removeClass('error');
                } else if (type == 'down') {
                    el.down.removeClass('error');
                }
            },
            list_add: function() {
                el.noNote.hide();
                temp.edit_reset();
                temp.edit_type = 'add';
                temp.play_time = !isPc ? global.H5player && global.H5player.info().currentTime : global.Player && global.Player.info().currentTime;
                temp.edit_time = formatTime(temp.play_time);
                this.setEditInput();
                el.list.children('.active').removeClass('active');
                this.showEditInput();
            },
            list_edit: function(obj) {
                var o = obj.closest('li');
                temp.edit_reset();
                temp.edit_type = 'edit';
                temp.edit_obj = o;
                temp.edit_id = o.attr('data-id');
                temp.edit_content = o.find(el.list_content).text();
                temp.edit_time = o.find(el.list_time).text();
                this.setEditInput();
                o.addClass('active').siblings().removeClass('active');
                this.showEditInput();
            },
            list_del: function(o) {
                temp.del_obj = o.closest('li');
                temp.del_id = temp.del_obj.attr('data-id');
                el.list_del_popup.show();
            },
            list_del_popup_submit: function() {
                var self = this;
                var op = {
                    url: api.del,
                    param: {
                        note_id: temp.del_id,
                        plan_id: plan_id,
                        videoStatus: getVideoStatus()
                    },
                    success: function() {
                        self.nowCount -= 1;
                        self.nowCount = self.nowCount < 0 ? 0 : self.nowCount;
                        if (temp.del_obj) {
                            temp.del_obj.remove();
                        }
                        self.nowCount > 0 ? el.noNote.hide() : el.noNote.show();
                        if (enableDM) {
                            DM_note.remove(temp.del_id);
                        }
                        //layer.msg('删除成功');
                        self.list_del_popup_close();
                    },
                    fail: function(res) {
                        layer.msg(res.msg, layerSkin);
                        self.list_del_popup_close();
                    }
                };
                ajax(op);
            },
            list_del_popup_close: function() {
                temp.del_obj = '';
                temp.del_id = '';
                el.list_del_popup.hide();
            },
            setEditInput: function() {
                el.edit.attr('data-id', temp.edit_id);
                el.edit_time.text(temp.edit_time);
                el.edit_input.val(temp.edit_content);
                var w = el.edit_input.val();
                el.edit_countNum.text(getLength(w));
                verInputCount(w) ? this.edit_error('edit') : this.edit_unerror('edit');
            },
            showEditInput: function() {
                this.down_cancel();
                var type = temp.edit_type;
                if (type == 'edit') {
                    temp.edit_obj.append(el.edit);
                    el.edit.show();
                } else if (type == 'add') {
                    el.edit.show();
                    el.list.before(el.edit);
                }
            },
            down_show: function() {
                this.edit_cancel();
                temp.play_time = !isPc ? global.H5player && global.H5player.player().currentTime : global.Player && global.Player.info().currentTime;
                temp.edit_time = formatTime(temp.play_time);
                el.down_time.text(temp.edit_time);
                el.down_input.val('').attr('placeHolder', '按Enter键可以直接保存');
                el.down.addClass('active');
            },
            down_cancel: function() {
                el.down_input.val('').attr('placeHolder', '输入此刻你要记录的内容').blur();
                el.down.removeClass('active').removeClass('error');
                el.down_time.text('记笔记：');
            },
            down_save: function() {
                if (!isLive()) {
                    this.playerPlay();
                }
                if (this.forbidAdd()) {
                    return false;
                }
                this.data_add(el.down_input.val());
                return this;
            },
            quteTipAnimate: function() {
                el.quteTip.hide();
                clearTimeout(quteTime);
                var pos1 = el.down_save.offset();
                var pos2 = $('#tabiconNote').offset();
                var w1 = el.down_save.width();
                var w2 = $('#tabiconNote').width();
                el.quteTip
                    .css({
                        top: pos1.top - 5 + 'px',
                        left: pos1.left + w1 + 5 + 'px',
                        width: '6px',
                        height: '6px'
                    })
                    .show()
                    .animate({
                        top: pos2.top + 35 + 'px',
                        left: pos2.left + w2 - 42 + 'px',
                        width: '8px',
                        height: '8px'
                    }, 1000, function() {
                        quteTime = setTimeout(function() {
                            el.quteTip.fadeOut();
                        }, 1000)
                    });
            },
            forbidAdd: function() {
                var status = true;
                if (!isPc) {
                    status = global.H5Player && global.H5Player.player().currentTime > 0 ? false : true;
                } else {
                    status = global.Player && global.Player.info() && global.Player.info().currentTime > 0 ? false : true;
                }
                if (!status) {
                    return false;
                } else {
                    layer.msg('只有上课中才可以记笔记哦！', layerSkin);
                    el.down_input.blur();
                    return true;
                }
            },
            playerPlay: function() {
                !isPc ? global.H5Player && global.H5Player.play() : global.Player && global.Player.play();
            },
            playerPause: function() {
                !isPc ? global.H5Player && global.H5Player.paused() : global.Player && global.Player.pause();
            },
            bind: function() {
                var self = this;
                // 列表
                el.list
                    .on('click', el.list_edit, function(event) {
                        event.preventDefault();
                        self.list_edit($(this));
                    })
                    .on('click', el.list_del, function(event) {
                        event.preventDefault();
                        self.list_del($(this));
                    })
                    .on('click', el.list_time, function(event) {
                        event.preventDefault();
                        var jumpTime = $(this).attr('data-handle');
                        if (isLive() || $(this).hasClass('error') || isNaN(jumpTime)) {
                            //console.log('无法跳转')
                        } else {
                            !isPc ? global.H5player && global.H5player.seek(jumpTime) : global.Player && global.Player.seek(jumpTime);
                        }
                        return $(this);
                    });
                el.list_add.click(function() {
                    if (self.forbidAdd()) {
                        return false;
                    }
                    if (self.nowCount >= maxNoteCount) {
                        layer.msg('最多只能添加' + maxNoteCount + '条笔记', layerSkin);
                        return false;
                    }
                    if (!isLive()) {
                        self.playerPause();
                    }
                    self.list_add();
                    return $(this);
                });
                //编辑
                el.edit_input.on('input', function(event) {
                    event.preventDefault();
                    var w = $(this).val();
                    el.edit_countNum.text(getLength(w));
                    verInputCount(w) ? self.edit_error('edit') : self.edit_unerror('edit');
                });
                if (!$.support.opacity) {
                    el.edit_input.on('blur', function(event) {
                        $(this).trigger('input');
                    });
                    el.edit_input.on('keyup', function(event) {
                        $(this).trigger('input');
                    });
                }
                el.edit_save.click(function(event) {
                    event.preventDefault();
                    if (temp.edit_type == 'edit') {
                        self.edit_save()
                    } else if (temp.edit_type == 'add') {
                        self.add_save();
                        if (!isLive()) {
                            self.playerPlay();
                        }
                    }
                })
                el.edit_cancel.click(function(event) {
                    event.preventDefault();
                    if (temp.edit_type == 'edit') {
                        self.edit_cancel()
                    } else if (temp.edit_type == 'add') {
                        self.add_cancel();
                        if (!isLive()) {
                            self.playerPlay();
                        }
                    }
                });
                //删除
                el.list_del_popup_submit.click(function() {
                    self.list_del_popup_submit();
                });
                el.list_del_popup_close.click(function() {
                    self.list_del_popup_close();
                });
                //底部笔记功能
                el.down.keyup(function(event) {
                    if (event.keyCode == 13) {
                        if (el.down.hasClass('active') && el.down_input.is(':focus')) {
                            self.down_save();
                        }
                    }
                });
                el.down_input.click(function(event) {
                    event.preventDefault();
                    if (!isLogin) {
                        $('#regcheck1').trigger('click');
                        return false;
                    }
                    if (isSign) {
                        layer.msg('您没有报名此课程，不能记笔记', layerSkin);
                        return false;
                    }
                    if (self.nowCount >= maxNoteCount) {
                        layer.msg('最多只能添加' + maxNoteCount + '条笔记', layerSkin);
                        return false;
                    }
                    if (!el.down.hasClass('active') && !self.forbidAdd()) {
                        if (!isLive()) {
                            self.playerPause();
                        }
                        self.down_show();
                    }
                    return $(this);
                });
                el.down_input.on('input', function(event) {
                    event.preventDefault();
                    verInputCount($(this).val()) ? self.edit_error('down') : self.edit_unerror('down');
                });
                if (!$.support.opacity) {
                    el.down_input.on('blur', function(event) {
                        $(this).trigger('input');
                    });
                    el.down_input.on('keyup', function(event) {
                        $(this).trigger('input');
                    });
                }
                el.down_save.click(function(event) {
                    event.preventDefault();
                    if (el.down.hasClass('active')) {
                        self.down_save();
                    }
                });
                el.down_cancel.click(function(event) {
                    event.preventDefault();
                    self.down_cancel($(this));
                    if (!isLive()) {
                        self.playerPlay();
                    }
                });
            }
        };
        window.PlayerNote = new NOTE();

        function ajax(op) {
            return $.ajax({
                    url: op.url,
                    type: op.type || 'POST',
                    dataType: op.dataType || 'json',
                    data: op.param || null
                })
                .done(function(res) {
                    if (res.code == 200) {
                        if (typeof op.success == 'function') {
                            op.success(res);
                        }
                    } else {
                        if (typeof op.fail == 'function') {
                            op.fail(res);
                        }
                    }
                });
        }

        function render(op) {
            var data = op.data;
            var arr = [];
            $.each(data, function(i, n) {
                var str = '';
                if (n.play_time_tmp_handle == 'error') {
                    str += n.tailor && n.tailor == 'tailor' ? '<li data-id="' + n.id + '" class="timeError tailor">' : '<li data-id="' + n.id + '" class="timeError notailor">';
                } else {
                    str += '<li data-id="' + n.id + '" class="noError">';
                }
                str += '<div class="note-info">';
                if (n.tailor && n.tailor == 'tailor') {
                    str += '<span class="note-time fl" data-handle="' + n.play_time_tmp_handle + '"></span>';
                } else {
                    str += '<span class="note-time fl icon-hover" data-handle="' + n.play_time_tmp_handle + '"><i class="icon icon-player-small-gray icon-normal mr5"></i><i class="icon icon-play-small icon-active mr5"></i>' + n.play_time_format + '</span>'
                }
                str += '<span class="note-btn fr pointer"><span class="note-del icon-hover fr"><i class="icon icon-del icon-active"></i><i class="icon icon-del-deep icon-normal"></i></span><span class="mr10 note-edit icon-hover fr"><i class="icon icon-pen icon-active"></i><i class="icon icon-pen-deep icon-normal"></i></span></span>';
                str += '</div>';
                str += '<div class="note-content">' + (n.content == '' ? "重点" : n.content) + '</div>'
                str += '</li>';
                arr.push(str);
            });
            return arr.join('');
        }

        function getLength(w) {
            var l = w.replace(/[\u0391-\uFFE5]/g, "aa").length / 2;
            return l > 50 ? Math.ceil(l) : Math.floor(l);
        }

        function verInputCount(w) {
            return w.replace(/[\u0391-\uFFE5]/g, "aa").length > 100 ? true : false;
        }

        function formatTime(time) {
            var t = parseInt(time);
            var h = Math.floor(t / 3600);
            var m = Math.floor((t - h * 3600) / 60);
            var s = Math.floor(t - h * 3600 - m * 60);
            h = h < 10 ? '0' + h : h;
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            return [h, m, s].join(':');
        }
    });
});
