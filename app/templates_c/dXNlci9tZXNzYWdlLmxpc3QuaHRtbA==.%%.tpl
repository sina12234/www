<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>我的消息 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的消息 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/store.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.user.messages.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav3"); ?>
<section class="pd20 bgf" id="wrap-msg">
    <div class="container">
        <div class="row msg-lt-menu pos-rel">
            <!--联系人-->
            <?php echo tpl_function_part("/user.message.leftMenu"); ?>
            <!-- /联系人 -->
            <!--消息列表-->
            <div class="msg-right col-sm-16 col-xs-20 pos-rel" id="msg-right">
                <!-- title -->
                <div class="msg-object" id="category-title">
                    <a href="javascript:;" class="msg-back" onclick="BackGetMsg()"></a>
                    <p class="msg-obj-title fs14" id="msg-detail-title"></p>
                    <!--<a href="javascript:;" class="linkman-set-icon linkman-set category-set" style="display:none\9">-->
                        <!-- 设置 -->
                        <div class="wrap-rt-set mesages-set bor1px">
                            <i class="rt-jiao"></i>
                            <h1 class="msg-set-title"></h1>
                            <div id="wrap_categorymsg_remind" class="wrap-remind-tip fs12 up-class clearfix"></div>
                            <div style="text-align:center;width:100%" class="mt10 mb10">
                                <button class="request-btn" onclick="RequestBtn()"><?php echo tpl_modifier_tr('确定','message'); ?></button>
                                <button class="cancle-btn"><?php echo tpl_modifier_tr('取消','message'); ?></button>
                            </div>
                        </div>
                        <!-- /设置 -->
                    </a>
                    <a href="javascript:;" class="msg-del msg-type" id="msg-alldel" onclick="DelMsg(0, 0, this)"></a>
                </div>
                <div class="msg-list-title tac fs14"><?php echo tpl_modifier_tr('消息中心','message'); ?></div>
                <!-- /title -->
                <!--消息总列表-->
                <ul class="msg-list mb20 bor1px" id="all-msg-list"></ul>
                <!--消息总列表-->
                <!-- page -->
                <div id="msg-page-list" class="pos-abs"></div>
                <!-- /page -->
                <!--系统消息列表-->
                <div id="systerm-msg" class="col-sm-24 c-fl" style="display: none;width: 100%">
                    <ol reversed class="chart-list" id="wrap_sysMsg"></ol>
                </div>
            </div>

            <!--聊天消息-->
            <div id="interact-msg" class="col-sm-16" style="display: none">
                <div class="msg-object">
                    <a href="javascript:void(0)" class="msg-back" onclick="BackIndex()"></a>
                    <p href="javascript:void(0)" class="msg-obj-title chat-obj-title fs14">
                        <?php echo tpl_modifier_tr('与','message'); ?> <span id="chat-name">小明</span> <?php echo tpl_modifier_tr('聊天中','message'); ?>
                    </p>
                    <img id="chat-user-img" class="hidden-xs hidden-lg hidden-sm hidden" src="" alt="">
                    <div class="down-arrow" id="msg-object-down">
                        <div id="msg-object-edit" class="msg-object-edit">
                            <a href="javascript:void(0)" class="msg-type chat-userFrom" onclick="DelMsg(0, 10003, this)"><?php echo tpl_modifier_tr('清除聊天记录','message'); ?></a>
                            <a href="javascript:void(0)" onclick="GetChatList()" id="add_obj"><?php echo tpl_modifier_tr('添加到联系人','message'); ?></a>
                            <!--
                                <a href="javascript:void(0)" onclick="TopMsg(0,0,0)">置顶该聊天</a>
                            -->
                            <a href="javascript:void(0)" class="add-black-btn" onclick="addBlackName(0,-5)"><?php echo tpl_modifier_tr('加入黑名单','message'); ?></a>
                        </div>
                    </div>
                </div>
                <ol reversed class="chart-list clearfix" id="wrap_chart"></ol>
                <div class="msg-enter">
                    <textarea class="div-input" id="div-input" type="text" onkeyup="this.value=this.value.replace( /^\s*/, '')"></textarea>
                    <!--
                        <div class="brow" id="brow">
                            <span></span>
                            <p id="face_list" style="display:none"></p>
                        </div>
                    -->
                    <button onclick="SendInfo()"><?php echo tpl_modifier_tr('发送','message'); ?></button>
                </div>
            </div>

            <!--聊天消息end-->

            <!-- 设置分组 -->
            <ul class="my-groups" id="my-groups">
                <li>
                    <span class="fs12 w70 c-fl"><?php echo tpl_modifier_tr('请设置分组','message'); ?></span>
                    <select class="c-fr fs12" id="set-mesage-select"></select>
                </li>
                <li>
                    <div class="new_div fs14"><?php echo tpl_modifier_tr('新建分组','message'); ?></div>
                    <div class="new_set_group">
                        <span class="fs12 w70 c-fl mr10"><?php echo tpl_modifier_tr('分组名称','message'); ?></span>
                        <input maxlength="10" class="bor1px new_group_name c-fr" />
                        <div class="del-btn" id="delt-group-name"></div>
                    </div>
                </li>
                <li style="text-align:center;" class="fs14">
                    <button class="sub-form" onclick="SubMitForm()"><?php echo tpl_modifier_tr('保存','message'); ?></button>
                </li>
            </ul>
            <!-- /设置分组 -->
        </div>
    </div>
</section>
<style type="text/css">
.layui-layer-btn a:hover{color:#fff;}
.msgId{display:none;}
</style>
<script type="text/javascript">
    var remindOption = <?php echo SlightPHP\Tpl::$_tpl_vars["remindOption"]; ?>;
    var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]['uid']; ?>;
    var userName = '<?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]['name']; ?>';
    var userThumb = '<?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]['medium']; ?>';
    var msgTime="";
    var t = '';

   window.onload=function() {
        getGroupList();
        getMessageList();
    }

    $(function() {
       //滚动条实时加载
//        var curr=1;
//        $("#systerm-msg").scroll(function(){
//            var scrollTop = $(this).scrollTop();
//            var scrollHeight = $(this)[0].scrollHeight;
//            var offetHeight  = $('#wrap_sysMsg').height();
//            if(scrollTop + offetHeight == scrollHeight){
//                    curr++;
//                    $("#msg-alldel").attr("userFrom", userFrom);
//                    $("#msg-alldel").attr("msgType", msgType);
//                    if(!getsystermMsg(userFrom, msgType, curr)){
//                        $('#multiple-left').unbind('scroll');
//                    }
//            }
//        })


        //搜索
        $("#form_sub").click(function () {
            var keyword = $("#user_sech_ipt").val();
            if (keyword == "") {
                $("#linkMan").html("<p style='text-align:center;'>没有符合的条件结果</p>");
                $("#del_lst_btn").show();
            } else {
                $.post("/message/ajax/SearchUser", {keyword:keyword}, function (r) {
                    if (r.code) {
                        $("#linkMan").html("<p style='text-align:center;'>没有符合的条件结果</p>");
                        $("#del_lst_btn").show();
                    } else {
                       var SeachHtml="<li>"+"<dl>"+"<dd>"
                        SeachHtml+='<span class="face">'+'<img src='+r.result[0].userThumb+' alt="" />'+'</span>'
                        SeachHtml+='<span id="username" class="c-fl" userid='+r.result[0].uid+'>'+r.result[0].userName+'</span>'
                        SeachHtml+="</dd>"+"</dl>"+"</li>";
                        $("#linkMan").html(SeachHtml);
                        $("#linkMan").find("dl").css("height", "auto");
                        $("#del_lst_btn").show();
                    }
                }, "json");
            }
        });

        //清空val
        $("#del_lst_btn").click(function () {
            $(this).hide();
            $("#user_sech_ipt").val("");
            getGroupList();
            getMessageList();
        });

        //linkman
        $("#linkMan").on("click", "p", function () {
            if($(this).parent().hasClass("on")) {
                $(this).parent().removeClass("on");
                $(this).siblings("dl").height("0px");
            }else{
                $(this).parent().addClass("on");
                $(this).siblings("dl").height("auto");
                var groupId=$(this).attr("groupId");
                getContactsList(groupId);
            }
        });


        $(".chat-obj-title").html("");
        $("#linkMan").on("click","dd",function () {
            clearInterval(t);
            clearInterval(msgTime);

            $("#linkMan").find("dd").removeClass("on");
            $(this).addClass("on").siblings().removeClass("on");

            var userId = $(this).find("#username").attr("userId");
            var ChatName = $(this).find("#username").html();
            var userImg=$(this).find("img").attr("src");
            var userGroupId=$(this).find("#username").attr("groupid");
            var msgIdArr = [];

            $("#chat-user-img").attr("src",userImg);
            $(".chat-obj-title").find("img").hide();
            $(".chat-obj-title").html('<?php echo tpl_modifier_tr('与','message'); ?> <span id="chat-name">'+ChatName+'</span> <?php echo tpl_modifier_tr('聊天中','message'); ?>');
            $("#chat-name").attr('dataId', userId);
            $("#chat-name").attr('groupId',userGroupId);
            $(".msg-type").attr('msgtype',"10003");
            $(".msg-type").attr("userfrom",userId);
            $("#interact-msg").show();
            $("#msg-right").hide();

            getChatDetailList(userId);
            t = setInterval(function(){
                getChatDetailList(userId);

                var orderIdArray = [];
                var idIndex = [];
                var msgId = $(".msgId");

                msgId.each(function(i) {
                    var id = parseInt($(this).html());
                    idIndex[id] = i;
                    orderIdArray.push(id);
                });

                orderIdArray = orderIdArray.sort(function(a, b){return (a > b) ? 1 : -1});

                var list = $("#wrap_chart").find("li");
                var _length = orderIdArray.length;

                for (var i=0; i<_length; i++)
                {
                    $("#wrap_chart").append(list.eq(idIndex[orderIdArray[i]]));
                }

            },5000);

            var userGroupId = $("#chat-name").attr("groupid");
            if(userGroupId==-1) {
                $(".add-black-btn").hide();
            }

        })
        //
        $('.msg-list ').on('click','li',function (event) {
            if(event.target.nodeName.toLowerCase()=='a'&&$(event.target).parent().hasClass('msg-edit')){
                event.stopPropagation();
                return false;
            }else{
                var userForm = $(this).attr('data-userFrom');
                var msgType = $(this).attr('data-msgType');
                DetailLink(userForm,msgType,this);
            }
        });

        //msg-edit
        $(".msg-list").on("mouseover",".down-arrow",function(event) {
            $(this).find("#msg-edit").show();
            $(this).addClass("down-arrow-on");
        })

        $(".msg-list").on("mouseout",".down-arrow",function() {
            $(this).find("#msg-edit").hide();
            $(this).removeClass("down-arrow-on");
        });
//        $(".msg-list").on("click",".deletmessage,.topmessage",function(event) {
//            event.stopPropagation();
//            return false;
//        })

        //brow
        $("#face_list img").click(function() {
            $("#face_list").hide();
        })

        $("#brow>span").on("click", function(e){
            $("#face_list").show();
            $(document).on("click", function(){
                $("#face_list").hide();
            });
            e.stopPropagation();
        });

        //msg-object-down
        $("#msg-object-down").hover(function() {
            $(this).find("#msg-object-edit").show();
            $(this).addClass("down-arrow-on");
        },function() {
            $(this).find("#msg-object-edit").hide();
            $(this).removeClass("down-arrow-on");
        })

        //add_obj
        $("#add_obj").click(function () {
            $("#msg-object-edit").hide();
        });


        //左边设置
        $("#my-mesages-set").hover(function () {
            $(this).addClass("z-ative-linkset").removeClass("linkman-set");
            $(this).find(".my-mesages-set").show();
        }, function () {
            $(this).find(".my-mesages-set").hide();
            $(this).addClass("linkman-set").removeClass("z-ative-linkset")
        });

        $("#tip-close-btn").click(function() {
            $(".my-mesages-set").hide();
        })

        //left set
        $(".msg-set-title").html("");
        $("#wrap_msg_remind").html("");

        //category set
        $(".category-set").hover(function() {
            $(this).find(".wrap-rt-set").show();
            $(this).addClass("z-ative-linkset").removeClass("linkman-set");
        },function(){
            $(this).find(".wrap-rt-set").hide();
            $(this).addClass("linkman-set").removeClass("z-ative-linkset");
        });

        $(".cancle-btn").click(function() {
            $(".mesages-set").hide();
        });

//new group
        $("#my-groups .new_div").click(function() {
            $(this).hide();
            $("#my-groups .new_set_group").show();
        });

        $("#delt-group-name").click(function() {
            $(this).parent().hide();
            $("#my-groups .new_group_name").val("");
            $("#my-groups .new_div").show();
        });

        //脚注
        if($(window).width() > 768) {
            if($("#msg-right").outerHeight() < 393) {
                $("#msg-right").css("height","910");
            }
        }

  //enter键
        $("#div-input").keydown(function(e){
            var e = e || event,
            keycode = e.which || e.keyCode;
            if (keycode==13) {
                SendInfo();
            }
        })

    })

    //删除消息
    function DelMsg(userFrom, msgType, obj) {
        var userFrom = $(obj).attr("userfrom");
        var msgType = $(obj).attr("msgtype");

        layer.confirm("<?php echo tpl_modifier_tr('确定要删除消息吗？删除后内容将不可恢复','message'); ?>",{
            btn: ['<?php echo tpl_modifier_tr('确定','message'); ?>','<?php echo tpl_modifier_tr('取消','message'); ?>'],
            title:['<?php echo tpl_modifier_tr('删除消息','message'); ?>']
        }, function() {
            $.post("/message/ajax/MsgDel",{ userFrom:userFrom,msgType:msgType },function(r) {
                if(r.code) {
                    layer.msg(r.errMsg);
                }else{
                    $("#wrap_chart").html("");
                    $(obj).parents(".msgList").remove();
                    $(obj).parents(".read").remove();
                    layer.msg(r.errMsg);
                    layer.closeAll();
                    location.reload();
                }
            },"json")
        });
    }

    //消息置顶
    function TopMsg(userFrom, msgType,type){
        layer.confirm("<?php echo tpl_modifier_tr('确定','message'); ?>?",{
            btn: ['<?php echo tpl_modifier_tr('确定','message'); ?>','<?php echo tpl_modifier_tr('取消','message'); ?>'],
            title:['<?php echo tpl_modifier_tr('置顶消息','message'); ?>']
        }, function() {
            $.post("/message/ajax/MsgTop",{ userFrom:userFrom,msgType:msgType,type:type },function(r) {
                if(r.code) {
                    layer.msg(r.errMsg);
                }else{
                    layer.msg(r.errMsg,{ icon:1  }, function(){
                        getMessageList();
                    });
                }
            },"json")
        });
    }

    //黑名单
    function DeltBlackName(userId,groupId,obj) {
        var userIdArray = [];
        userIdArray[0] = userId || $("#chat-name").attr("dataid");
        var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]['uid']; ?>;
        var grouId = $(obj).attr("groupId");

        if(grouId == -5) {
            layer.confirm("<p><?php echo tpl_modifier_tr('是否将该联系人移出黑名单','message'); ?>？</p>",{
                btn: ['<?php echo tpl_modifier_tr('确定','message'); ?>','<?php echo tpl_modifier_tr('取消','message'); ?>'],
                title:['<?php echo tpl_modifier_tr('移出黑名单','message'); ?>']
            }, function() {
                $.post("/message/ajax/ContactsMove",{ userId:userIdArray, groupId:groupId },function(r) {
                    if(r.code) {
                        layer.msg("失败");
                    }else{
                        getGroupList();
                        layer.closeAll();
                    }
                },"json")
            });
        }else {
            layer.confirm("<p><?php echo tpl_modifier_tr('是否将该联系人删除','message'); ?>？</p>",{
                btn: ['<?php echo tpl_modifier_tr('确定','message'); ?>','<?php echo tpl_modifier_tr('取消','message'); ?>'],
                title:['<?php echo tpl_modifier_tr('移出联系人','message'); ?>']
            }, function() {
                $.post("/message/ajax/delContact",{ userId:uid, linkMan:userIdArray },function(r) {
                    if(r.code) {
                        layer.msg("失败");
                    }else{
                        getGroupList();
                        layer.closeAll();
                    }
                },"json")
            });
        }
        event.stopPropagation();
    }

    function addBlackName(userId,groupId) {
        var userIdArray = [];
        userIdArray[0] = userId || $("#chat-name").attr("dataid");

        layer.confirm("<p><?php echo tpl_modifier_tr('加入黑名单后，将无法与对方发送消息','message'); ?><?php echo tpl_modifier_tr('确定加入黑名单','message'); ?>？</p>",{
            btn: ['<?php echo tpl_modifier_tr('确定','message'); ?>','<?php echo tpl_modifier_tr('取消','message'); ?>'],
            title:['<?php echo tpl_modifier_tr('加入黑名单','message'); ?>']
        }, function() {
            $.post("/message/ajax/ContactsMove",{ userId:userIdArray, groupId:groupId },function(r) {
                if(r.code) {
                    layer.msg("失败");
                }else{
                    getGroupList();
                    layer.closeAll();
                }
            },"json")
        });
    }

    //消息分类设置
    function DetailLink(userFrom, msgType, obj, curr) {
        if (msgType == '10003') {
            clearInterval(msgTime);
            clearInterval(t);

            $("#interact-msg").show();
            $("#msg-right").hide();
            $("#msg-page-list").hide();

            var userId = $(obj).attr("userid");

            var ChatName = $(obj).attr("data-name");
            $(".chat-obj-title").find("img").hide();
            $(".chat-obj-title").html('<?php echo tpl_modifier_tr('与','message'); ?> <span id="chat-name" class="msg-type" userfrom=' + userId + ' msgType=' + msgType + ' dataid=' + userId + '>' + ChatName + '</span> <?php echo tpl_modifier_tr('聊天中','message'); ?>');
            $(".chat-userFrom").attr("userFrom",userId);
            $(".chat-userFrom").attr("msgType","10003");
            getChatDetailList(userId);
            $(window).scrollTop(0); //定位到顶部
            $("body").css('overflow-y','hidden');//去除顶部滚动条
            msgTime = setInterval(function () {
                getChatDetailList(userId);

                var orderIdArray = [];
                var idIndex = [];
                var msgId = $(".msgId");

                msgId.each(function(i) {
                    var id = parseInt($(this).html());
                    idIndex[id] = i;
                    orderIdArray.push(id);
                });

                orderIdArray = orderIdArray.sort(function(a, b){return (a > b) ? 1 : -1});

                var list = $("#wrap_chart").find("li");
                var _length = orderIdArray.length;

                for (var i=0; i<_length; i++)
                {
                    $("#wrap_chart").append(list.eq(idIndex[orderIdArray[i]]));
                }

            }, 5000);
        }else {//msgType==10009 代表为系统消息
            $("#systerm-msg").show();
            $("#wrap_sysMsg").html('');
            $("#msg-right").show();//消息列表
            $("#msg-page-list").hide();//分页
            $("#category-title").show();//系统title栏
            $(".msg-list-title").hide();//个人聊天title栏
            $("#all-msg-list").hide();
            $("#msg-alldel").attr("userFrom", userFrom);
            $("#msg-alldel").attr("msgType", msgType);
            var curr=1;
            getsystermMsg(userFrom, msgType, curr);
            $(window).scrollTop(0); //定位到顶部
            $("body").css('overflow-y','hidden');//去除顶部滚动条
            $("#wrap_sysMsg").scroll(function(){
                var scrollTop = $(this).scrollTop();
                var scrollHeight = $(this)[0].scrollHeight;
                var windowHeight = $(this).height();
                if (scrollTop + windowHeight == (scrollHeight-20)) {
                    curr++;
                    $("#msg-alldel").attr("userFrom", userFrom);
                    $("#msg-alldel").attr("msgType", msgType);
                    if(!getsystermMsg(userFrom, msgType, curr)){
                        $('#wrap_sysMsg').unbind('scroll');
                    }
                }
            })
        }

    }

    //分组
    function GetChatList() {
        getOptionGroupList();
        layer.open({
            type: 1,
            title: ['<?php echo tpl_modifier_tr('设置分组','message'); ?>'],
            closeBtn: 1,
            area: ['320px','220px'],
            shadeClose: true,
            content: $('#my-groups')
        });
    }

//返回首页
    function BackIndex(curr) {
        clearInterval(msgTime);
        clearInterval(t);
        getMessageList(curr);
        $("#div-input").val("");
        $("#interact-msg").hide();
        $("#msg-right").show();
        $("#msg-page-list").show();
        $(".msg-list-title").show();
        $("body").css('overflow-y','auto');

    }
</script>
<!--onclick="DetailLink(<<userFrom>>, <<msgType>>, this)"-->>
<script id="messageListTpl" type='text/template'>
    <<#chatMsg>>
        <li class="msgList" data-userFrom="<<userFrom>>" data-msgType="<<msgType>>"  data-name="<<msgTypeTitle>>" userid="<<userFrom>>">
            <span class="face">
                <img id="list-userImg" src="<<msgTitleThumb>>">
                <<^redPoint>>
                    <em class="msg-num"><<num>></em>
                <</redPoint>>
            </span>
            <div class="msg-main">
                <div class="msg-info col-sm-20 col-xs-20">
                    <a href="javascript:;" class="fs14 c-fl msg-category-title" id="category_msg_title"><<msgTypeTitle>></a>
                    <<^isTop>>
                    <span class="stick-icon fs14 ml10" style="display:block;"><<topFlag>></span>
                    <</isTop>>
                    <div class="c-fr cGray msg-other">
                        <span class="c-fl hidden-xs"><<time>></span>
                        <div class="down-arrow">
                            <div class="msg-edit" id="msg-edit">
                                <a href="javascript:;" class="deletmessage msg-type" msgtype="<<msgType>>" userfrom="<<userFrom>>" onclick="DelMsg(<<userFrom>>, <<msgType>>, this)"><?php echo tpl_modifier_tr('删除该消息','message'); ?></a>
                                <a href="javascript:;" class="topmessage" onclick="TopMsg(<<userFrom>>, <<msgType>>, <<isTop>>)"><<isTopStr>></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="msg-text msg-text2 col-sm-20 col-xs-20"><<&message>></div>
            </div>
        </li>
        <</chatMsg>>
</script>

<script id="sysMessageListTpl" type='text/template'>
    <<#systemMsg>>
        <li class="msgList" data-userFrom="<<userFrom>>" data-msgType="<<msgType>>" data-name="<<msgTypeTitle>>" userid="<<userFrom>>">
            <span class="face">
                <img id="list-userImg" src="<<msgTitleThumb>>">
                <<^redPoint>>
                    <em class="msg-num"><<num>></em>
                <</redPoint>>
            </span>
            <div class="msg-main">
                <div class="msg-info col-sm-20 col-xs-20">
                    <a href="javascript:;" class="fs14 c-fl msg-category-title" id="category_msg_title"><<msgTypeTitle>></a>
                    <<^isTop>>
                    <span class="stick-icon fs14 ml10" style="display:block;"><<topFlag>></span>
                    <</isTop>>
                    <div class="c-fr cGray msg-other">
                        <span class="c-fl hidden-xs"><<time>></span>
                        <!--<div class="down-arrow">-->
                            <!--<div class="msg-edit" id="msg-edit">-->
                                <!--<a href="javascript:;" class="deletmessage msg-type" msgtype="<<msgType>>" userfrom="<<userFrom>>" onclick="DelMsg(<<userFrom>>, <<msgType>>, this)"><?php echo tpl_modifier_tr('删除该消息','message'); ?></a>-->
                                <!--<a href="javascript:;" class="topmessage" onclick="TopMsg(<<userFrom>>, <<msgType>>, <<isTop>>)"><<isTopStr>></a>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </div>
                <div class="msg-text msg-text2 col-sm-20 col-xs-20"><<&message>></div>
            </div>
        </li>
        <</systemMsg>>
</script>

<script id="messageDetailTpl" type='text/template'>
    <<#messageDetail>>
        <li class="read">
            <span class="face msgDetailImg"><img alt="<<msgTypeTitle>>" src="<<msgTitleThumb>>"></span>
            <div class="msg-main">
                <div class="msg-info col-sm-20 col-xs-20">
                    <span class="fs14 c-fl hidden-xs"><<msgTypeTitle>></span>
                    <div class="c-fr cGray msg-other"><<time>></div>
                </div>
                <div class="msg-text col-sm-20 col-xs-20"><<&message>></div>
            </div>
        </li>
    <</messageDetail>>
</script>

<script id="chatDetailListTpl" type='text/template'>
    <<#chatDetailList>>
        <li class='myinfo clearfix <<^isLeft>>otherinfo<</isLeft>>' msgid='<<msgId>>'>
            <span class="msgId"><<msgId>></span>
            <span class="face">
                <img src='<<userFromImg>>'  alt="<<userFromName>>">
                <<^isLeft>>
                <img src='<<userToImg>>'  alt="userToName">
                <</isLeft>>
            </span>
            <div class="chart-main">
                <span class="chart-angle"></span>
                <div class="chart-text">
                    <<&content>>
                </div>
            </div>
        </li>
        <</chatDetailList>>
</script>

<script id="chatSyslListTpl" type='text/template'>
    <<#messageDetail>>
        <li class='myinfo clearfix otherinfo'>
            <span class="face">
                <img src='<<msgTitleThumb>>'>
            </span>
            <div class="chart-main">
                <span class="chart-angle"></span>
                <div class="chart-text">
                    <<&message>>
                </div>
            </div>
            <span class="c-fl mt25 pl10"><<&time>></span>
        </li>
        <</messageDetail>>
</script>

<script id="optionGroupListTpl" type='text/template'>
    <<#groupList>>
        <option value="<<groupId>>"><<name>></option>
    <</groupList>>
</script>

<script>
    //chat
    var FaceUrl="/assets_v2/img/QQ/";//表情路径
    var FaceNums=25//表情数量
    var replaceId="wrap_chart" //表情元素ID
    var textId="div-input" //输入框ID
    FaceNum();
    Replace();
    Click();

    function FaceNum() {
        $("#face_list").html("");
        for(var i=1;i<FaceNums;i++) {
            Str=FaceUrl+i+".gif";
            $("#face_list").append("<a href='javascript:;'><img src="+Str+" fn=[@"+i+"@] /></a>");
        }
    }

    function Replace() {
        rContent=$("#"+replaceId).html();
        rContent = rContent.replace(/\[@/g, "<img src="+FaceUrl+"");
        rContent = rContent.replace(/\@]/g, ".gif />");
        $("#"+replaceId).html(rContent);
    }

    function Click() {
        $("#face_list img").click(function(){
            $("#"+textId).append($(this).attr("fn"))
        })
    }
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
