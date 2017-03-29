/*
*Date:12-29-2015
*My Messages
*/
//设置
function RequestBtn() {
    var remindArray = [];

    $(".wrap-remind-tip input:checked").each(function() {
        var msgType = $(this).attr("msgtype");
        var type = $(this).val();
        remindArray.push({'msgType':msgType, 'type':type})
    });
    if (remindArray.length <1) {
        return false;
    }

    $.post("/message/ajax/MsgRemind",{ data:remindArray},function(r) {
        if(r.code) {
            layer.msg(r.errMsg);
        }else{
            $("#my-mesages-set .mesages-set").hide();
            location.reload();
        }
    },"json")
}

// 分组列表
function getGroupList() {
    $.post('/message/ajax/GetGroupList', function(r){
        if (r.code == 0) {
            var groupListTpl = $('#groupListTpl').html();
            $('#linkMan').html(Mustache.render(groupListTpl, r.result));
        }
    }, 'json');
}

// 分组列表
function getOptionGroupList() {
    $.post('/message/ajax/GetGroupList',{filter:1}, function(r){
        if (r.code == 0) {
            var optionGroupListTpl = $('#optionGroupListTpl').html();
            $('#set-mesage-select').html(Mustache.render(optionGroupListTpl, r.result));
        }
    }, 'json');
}

// 首页消息列表
function getMessageList(curr) {
    $.post('/message/ajax/GetMyMessages', {page:curr || 1}, function(r){
        if (r.code == 0) {
            $('#all-msg-list').html('');
            if((!curr)||curr == 1 ){
                var messageListTpl = $('#sysMessageListTpl').html();
                $('#all-msg-list').html(Mustache.render(messageListTpl, r.result.messageList));
                var messageListTpl = $('#messageListTpl').html();
                var liStr=Mustache.render(messageListTpl, r.result.messageList);
                $('#all-msg-list').append(liStr);
            }else{
                var messageListTpl = $('#messageListTpl').html();
                var liStr=Mustache.render(messageListTpl, r.result.messageList);
                $('#all-msg-list').append(liStr);
            }

            laypage({
                cont: $("#msg-page-list"),
                pages: r.result.messageList.totalPage,
                curr: curr || 1,
                jump: function(obj, first){
                    if(!first){
                        getMessageList(obj.curr);
                    }
                }
            });
        }else {
            $('#all-msg-list').html('<p class="mt30 tac">还没有收到消息哦~</p>')
        }
    }, 'json');
}

//返回首页列表
function BackGetMsg(curr) {
    getMessageList(curr);
    $("#category-title").hide();
    $("#msg-right").show();
    $("#systerm-msg").hide();
    $("#all-msg-list").show();
    $("#msg-page-list").show();
    $(".msg-list-title").show();
    $("body").css('overflow-y','auto');
}

// 聊天详情
function getChatDetailList(userFrom) {
    var chatDetailListTpl = $('#chatDetailListTpl').html();
    var storeUserFrom = store.get('userFrom'+userFrom);
    var maxId = store.get('maxId');
    var ChartDetailScroll=document.getElementById("wrap_chart");

    $.post('/message/ajax/GetChatDetail',{userFrom:userFrom}, function(r){
        if (r.code == 0) {
            if (r.result.chatDetailList[0].maxId) {
                 store.set('maxId', r.result.chatDetailList[0].maxId);
                 store.set('userFrom'+userFrom, r.result);
            }
            $('#wrap_chart').html(Mustache.render(chatDetailListTpl, r.result));
            ChartDetailScroll.scrollTop = ChartDetailScroll.scrollHeight;
        }
    }, 'json');
}
//系统消息详情
function getsystermMsg(userFrom, msgType, curr){
    var page = curr || 1;
    var dt=true;
    $.ajax({
        type:'post',
        url:'/message/ajax/GetMsgDetail',
        data:{userFrom:userFrom,msgType:msgType, page:page},
        dataType:'json',
        async:false,
        success:function(r) {
            if (r.code == 0) {
                $("#msg-detail-title").html("系统消息");
                $(".msg-set-title").html(r.result.messageDetail[0].msgTypeTitle);

                var isRemindTpl = $('#isRemindTpl').html();
                var remindData = [];

                if (r.result.messageDetail[0].msgType == "10003") {
                    $(".category-set").remove();
                } else {
                    var key = r.result.messageDetail[0].msgType;
                    remindData['isRemind'] = remindOption[key];
                    $('#wrap_categorymsg_remind').html(Mustache.render(isRemindTpl, remindData));

                }

                var chatSyslListTpl = $('#chatSyslListTpl').html();
                var planList1 = Mustache.render(chatSyslListTpl, r.result);
                $('#wrap_sysMsg').append(planList1);

            } else {
                dt = false
            }
        }
    })
    return dt
}
//联系人列表内容
function getContactsList(groupId) {
    if (groupId == -1) {
        $.post('/message/ajax/LatestUser', function(r){
            if (r.code == 0) {
                var contactsListTpl = $('#contactsListTpl').html();
                $('#groupContacts'+groupId).html(Mustache.render(contactsListTpl, r.result));
            }
        }, 'json');
    } else {
        $.post('/message/ajax/GetContactsListByGroupId',{groupId:groupId}, function(r){
            if (r.code == 0) {
                var contactsListTpl = $('#contactsListTpl').html();
                $('#groupContacts'+groupId).html(Mustache.render(contactsListTpl, r.result));
            }
        }, 'json');
    }

}

$("#msg-detail-title").html("");
//消息分类
function getDetailList(userFrom, msgType, curr) {
    var page = curr || 1;
    $.post("/message/ajax/GetMsgDetail",{userFrom:userFrom,msgType:msgType, page:page},function(r){
       if (r.code == 0) {
			$("#msg-detail-title").html("系统消息");
            $(".msg-set-title").html(r.result.messageDetail[0].msgTypeTitle);

            var isRemindTpl = $('#isRemindTpl').html();
            var remindData=[];

            if(r.result.messageDetail[0].msgType=="10003") {
                $(".category-set").remove();
            }else{
                var key = r.result.messageDetail[0].msgType;
                remindData['isRemind'] = remindOption[key];
                $('#wrap_categorymsg_remind').html(Mustache.render(isRemindTpl, remindData));

            }

            var messageDetailTpl = $('#messageDetailTpl').html();
            $('#all-msg-list').html(Mustache.render(messageDetailTpl, r.result));

            laypage({
                cont: $("#msg-page-list"),
                pages: r.result.totalPage,
                curr: curr || 1,
                jump: function(obj, first){
                    if(!first){
                        getDetailList(userFrom, msgType, obj.curr);
                    }
                }
            });
        }else {
            $("#msg-right").hide();
            location.reload();
        }
  },"json")
}



//左边消息设置
function setMsg(key,txt) {
    var isRemindTpl = $('#isRemindTpl').html();
    var remindData=[];
    remindData['isRemind'] = remindOption[key];

    $('#wrap_msg_remind').html(Mustache.render(isRemindTpl, remindData));
    $(".wrap-lt-set").show();
    $(".my-mesages-set").hide();
    $(".msg-set-title").html(txt);
}

//发送消息
function SendInfo() {
    store.clear();
    var content = $("#div-input").val();
    var pattern = new RegExp("[{} <> <>/? ]");
        var chatContent = "";
    for (var i = 0; i < content.length; i++) {
        chatContent = chatContent + content.substr(i, 1).replace(pattern, '');
    }

    var userToId = $("#chat-name").attr('dataId');
    var userChatImg=userThumb;
    if (content.length<1) return false;
    if (userToId<1) return false;
    $.post("/message/ajax/ChatAdd",{userTo:userToId, content:chatContent},function(r) {
        if(r.code) {
            layer.msg(r.errMsg);
        }else{
            var ChartContent=$("#wrap_chart");
            var ChartCont=$("#div-input").val();
            var ChartContentScroll=document.getElementById("wrap_chart");
            //发送消息
            var _HtmlIptRt="<li class='myinfo'>"
            _HtmlIptRt+="<span class='face'>"
            _HtmlIptRt+='<img src='+userChatImg+' alt="" />'
            _HtmlIptRt+="</span>"
            _HtmlIptRt+="<div class='chart-main'>"
            _HtmlIptRt+='<span class="chart-angle">'+'</span>'
            _HtmlIptRt+="<div class='chart-text'>"+ChartCont+"</div>"
            _HtmlIptRt+="</div>"
            _HtmlIptRt+="</li>";

            ChartContent.append(_HtmlIptRt);
            ChartContentScroll.scrollTop = ChartContentScroll.scrollHeight;
            $("#div-input").val("");
            Replace();
        }
    },"json")

}

function SubMitForm() {
    var userIdArr = [];
    var NewGroupName=$("#my-groups .new_group_name").val();
    var ChangeVal=$("#set-mesage-select").val();
    var chatUserImg=$("#chat-user-img").attr("src");
    var chatUserName=$("#chat-name").html();
    var userId=$("#chat-name").attr("dataid");
        userIdArr[0] = userId;

    if(NewGroupName!=""){
        $.post("/message/ajax/AddGroup",{groupName:NewGroupName},function(r) {
            getGroupList();
            $("#my-groups .new_group_name").val("");
            $("#my-groups .new_set_group").hide();
            $("#my-groups .new_div").show();
        },"json");
    }else{
        $.post("/message/ajax/ContactsMove",{userId:userIdArr,groupId:ChangeVal},function(r) {
            var groupList='<dd>'
                groupList+='<span class="face">'+'<img src='+chatUserImg+' />'+'</span>'
                groupList+='<span id="username" userid='+userId+' class="c-fl">'+chatUserName+'</span>'
                groupList+='<div class="delt_black_name c-fr mr20 mt10" onclick="DeltBlackName('+userId+','+ChangeVal+')">'
                groupList+='<i>'+'</i>'
                groupList+='</div>'
                groupList+='</dd>';
            $("#linkMan li").find("#groupContacts"+ChangeVal).append(groupList);
            $("#my-groups .new_group_name").val("");
            $("#my-groups .new_set_group").hide();
            $("#my-groups .new_div").show();
        },"json");
    }
    getGroupList();
    layer.closeAll();
}


