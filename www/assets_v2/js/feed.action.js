// add comment
function addComment() {
    var data = [];
    $.post("/tweeter/ajax/AddComment", {data: data}, function (r) {
        if (r.code) {
            layer.msg(r.errMsg);
        } else {
            layer.msg('success', {icon: 1});
        }
    }, "json")
}

// push feed
function push() {
    var data = [];
    $.post("/tweeter/ajax/Push", {data: data}, function (r) {
        if (r.code) {
            layer.msg(r.errMsg);
        } else {
            layer.msg('success', {icon: 1});
        }
    }, "json")
}

// get comment list
function getCommentList(tweeterId, page, length) {
    $.post('/tweeter/ajax/GetComments', {tweeterId: tweeterId, page: page, length: length}, function (r) {
        if (r.code == 0) {
            var commentListTpl = $('#commentListTpl').html();
            $('#commentList').html(Mustache.render(commentListTpl, r.result));

            laypage({
                cont: $("#commentList"),
                pages: r.result.totalPage,
                curr: page,
                jump: function (obj, first) {
                    if (!first) {
                        getCommentList(tweeterId, obj.curr, length);
                    }
                }
            });
        }
    }, 'json');
}

// get pic list by tweeter id
function getPicList(tweeterId) {
    $.post('/tweeter/ajax/GetPic', {tweeterId: tweeterId}, function (r) {
        if (r.code == 0) {
            var picListTpl = $('#picListTpl').html();
            $('#picList').html(Mustache.render(picListTpl, r.result));
        }
    }, 'json');
}

// get my fans
function getMyFans(uid, page, length) {
    $.post('/tweeter/ajax/GetMyFans', {uid: uid, page: page, length: length}, function (r) {
        if (r.code == 0) {
            var fanListTpl = $('#fanListTpl').html();
            $('#fanList').html(Mustache.render(fanListTpl, r.result));

            laypage({
                cont: $("#fanList"),
                pages: r.result.totalPage,
                curr: page,
                jump: function (obj, first) {
                    if (!first) {
                        getMyFans(uid, obj.curr, length);
                    }
                }
            });
        }
    }, 'json');
}

// follow
function follow(followId, groupId) {
    $.post('/tweeter/ajax/Follow', {followId: followId, groupId: groupId}, function (r) {
        if (r.code == 0) {
            layer.msg('success', {icon: 1});
        }
    }, 'json');
}

// cancel follow
function cancelFollow(followId) {
    $.post('/tweeter/ajax/CancelFollow', {followId: followId}, function (r) {
        if (r.code == 0) {
            layer.msg('success', {icon: 1});
        } else {
            layer.msg(r.errMsg);
        }
    }, 'json');
}

function zan(tweeterId) {
    $.post('/tweeter/ajax/Zan', {tweeterId: tweeterId}, function (r) {
        if (r.code == 0) {
            layer.msg('success', {icon: 1});
        } else {
            layer.msg(r.errMsg);
        }
    }, 'json');
}

function DelComment(commentId) {
    $.post('/tweeter/ajax/DelComment', {commentId: commentId}, function (r) {
        if (r.code == 0) {
            layer.msg('success', {icon: 1});
        } else {
            layer.msg(r.errMsg);
        }
    }, 'json');
}


