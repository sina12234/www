<!DOCTYPE html>
<html>
<head>
    <title>QQ管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/site.main.header"); ?>
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>"></script>

</head>
<body>
    <?php echo tpl_function_part("/site.main.nav"); ?>
    <section class="pd30">
        <div class="container">
            <div class="row">
                <?php echo tpl_function_part("/org.main.menu.tool"); ?>
                <div class="right-main col-md-16 col-sm-12">
                    <div class="col-md-20 pd20" id="QQMan" style="border-bottom: 1px solid #E3E3E3;">
                        <p class="col-md-2 fs14 pl5"><span class="c-name"><?php echo tpl_modifier_tr('QQ客服','org'); ?></span></p>
                        <p class="col-md-16 c-info fs12">
                            (请<a class=" ca-down" style="color:#3f82a6" title="" href="<?php echo utility_cdn::img('/assets_v2/img/QQhelper.docx'); ?>" ><?php echo tpl_modifier_tr('下载教程','org'); ?></a>并按照教程步骤添加客服)
                        </p>
                        <a id="addQQ" href="javascript:void(0);" class="blue-link c-fr"><span class="icon-add"></span>+<?php echo tpl_modifier_tr('添加新客服','org'); ?></a>
                        <div class="clearfix"></div>
                        <table class="table-grid mt10" id="QQList">
                            <thead>
                                <tr>
                                    <td class="col-md-7"><?php echo tpl_modifier_tr('客服名称','org'); ?></td>
                                    <td class="col-md-7"><?php echo tpl_modifier_tr('QQ号','org'); ?></td>
                                    <td class="col-md-6"><?php echo tpl_modifier_tr('操作','org'); ?></td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <script type="text/template" id="qqtrList">
                            <% if(data){ data.forEach(function(list){  %>
                            <tr>
                                <td class="col-md-7"><%= list.type_name %></td>
                                <td class="col-md-7"><%= list.type_value %></td>
                                <td class="col-md-6">
                                    <i class="edit-icon mr10 mt10" pid="<%= list.pk_customer %>"></i>
                                    <i class="del-icon" pid="<%= list.pk_customer %>"></i>
                                </td>
                            </tr>
                            <%});}%>
                        </script>
                        <div id="qqpager" class="mt10 c-fr"></div>
                    </div>
                    <div class="col-md-20 pd20" id="QQgroupMan">
                        <p class="col-md-2 fs14 pl5"><span class="c-name"><?php echo tpl_modifier_tr('QQ群管理','org'); ?></span></p>
                        <p class="col-md-16 c-info fs12">
                            (请<a class=" ca-down" style="color:#3f82a6" title="" href="<?php echo utility_cdn::img('/assets_v2/img/QQGrouphelper.docx'); ?>" ><?php echo tpl_modifier_tr('下载教程','org'); ?></a>并按照教程步骤添加QQ群)
                        </p>
                        <a id="addQQgroup" href="javascript:void(0);" class="blue-link c-fr"><span class="icon-add"></span>+<?php echo tpl_modifier_tr('添加QQ群','org'); ?></a>
                        <div class="clearfix"></div>
                        <table class="table-grid mt10" id="QQgroupList">
                            <thead>
                                <tr>
                                    <td class="col-md-7"><?php echo tpl_modifier_tr('QQ群名称','org'); ?></td>
                                    <td class="col-md-7"><?php echo tpl_modifier_tr('QQ群号','org'); ?></td>
                                    <td class="col-md-6"><?php echo tpl_modifier_tr('操作','org'); ?></td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <script type="text/template" id="qqGrouptrList">
                            <% if(data){ data.forEach(function(list){ %>
                            <tr>
                                <td class="col-md-7"><%= list.type_name %></td>
                                <td class="col-md-7"><%= list.type_value %></td>
                                <td class="col-md-6">
                                    <i class="edit-icon mr10 mt10" pid="<%= list.pk_customer %>"></i>
                                    <i class="del-icon" pid="<%= list.pk_customer %>"></i>
                                </td>
                            </tr>
                            <%});}%>
                        </script>
                        <div id="qqGrouppager" class="mt10 c-fr"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="tool_comment" style="display: none;">
        <div class="comment-layer col-sm-20 col-xs-20 form" id="comment_box" data-status="">
            <input type="hidden" name="pid" value=""/>
            <div class="row form">
                <div class="label col-sm-5 col-xs-5">客服名称</div>
                <input type="text" class="col-sm-15 col-xs-15" name="customerName" placeholder="请输入客服名称" />
            </div>
            <div class="row form">
                <div class="label col-sm-5 col-xs-5">QQ号</div>
                <input type="text" class="col-sm-15 col-xs-15" name="customerTel" placeholder="请输入QQ号" />
            </div>
            <div class="comment-btn col-sm-20 col-xs-20 view-hidden pt30 tac">
                <button id="comment_send" class="mr20 btn">确定</button>
                <button id="comment_cancel" class="gray-button">取消</button>
            </div>
        </div>
    </section>
    <section id="tools_comment" style="display: none;">
        <div class="comment-layer col-sm-20 col-xs-20 form" id="comments_box" data-status="">
            <input  type="hidden" name="pid" value=""/>
            <div class="row form mt10">
                <div class="label col-sm-5 col-xs-5">QQ群名称</div>
                <input type="text" class="col-sm-15 col-xs-15" name="qqGroupName" placeholder="请输入QQ群名称" />
            </div>
            <div class="row form">
                <div class="label col-sm-5 col-xs-5">QQ群号</div>
                <input type="text" class="col-sm-15 col-xs-15" name="qqGroupTel" placeholder="请输入QQ群号码" />
            </div>
            <div class="row form">
                <div class="label col-sm-5 col-xs-5">QQ群链接</div>
                <input type="text" class="col-sm-15 col-xs-15" name="qqGroupLink" placeholder="请参考教程方式获取群链接" />
            </div>
            <div class="comment-btn col-sm-20 col-xs-20 view-hidden pt20 tac">
                <button id="comment_send1" class="mr20 btn">确定</button>
                <button id="comment_cancel" class="gray-button">取消</button>
            </div>
        </div>
    </section>
</body>
</html>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ie8/ejs.ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>

<script type="text/javascript">
$(function(){
    getTools(1);
    getTools(2);
    $("#addQQ").click(function(){
        showQQ(1);
    })
    $("#addQQgroup").click(function(){
        showQQ(2);
    })
    $("#QQList").on('click','i.edit-icon',function(){
        var pid = $(this).attr('pid');
        editQQ(1,pid);
    });
    $("#QQgroupList").on('click','i.edit-icon',function(){
        var pid = $(this).attr('pid');
        editQQ(2,pid);
    });
    $("#QQList,#QQgroupList").on('click','i.del-icon',function(){
        var pid = $(this).attr('pid');
        layer.confirm("<?php echo tpl_modifier_tr('删除后，将无法继续关联，已关联的不会继续在前台页面显示','org'); ?>", {
            btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
            shade: false //不显示遮罩
        }, function(){
            delQQ(pid);
        });

    });
    $("#comment_cancel,#comment_cancel1").click(function(){
        $(this).parents('.layui-layer-content').find('input').val('');
        layer.closeAll();
    });
    $("#comment_send").click(function(){
        var obj=$("#tool_comment");
        var pid=obj.find('input[name="pid"]').val();
        var type_name=$.trim(obj.find('input[name="customerName"]').val());
        var type_value=$.trim(obj.find('input[name="customerTel"]').val());
        if(type_value==''){
            layer.msg("<?php echo tpl_modifier_tr('QQ号不能为空','org'); ?>");
            return false;
        }
        if(type_name.replace(/[\u0391-\uFFE5]/g,"aa").length > 12){
            layer.msg("<?php echo tpl_modifier_tr('长度不能超过6个汉字或10个字符','org'); ?>");
            return false;
        }else if(type_name == ''){
            layer.msg("<?php echo tpl_modifier_tr('客服名称不能为空','org'); ?>");
            return false;
        }
        if(!pid == ''){
            var param={
                type:1,
                pid:pid,
                type_name:type_name,
                type_value:type_value
            }
            updateQQ(param);
        }else{
            var param={
                type:1,
                type_name:type_name,
                type_value:type_value
            }
            addQQ(param);
        }

    });
    $("#comment_send1").click(function(){
        var obj=$("#tools_comment");
        var pid=obj.find('input[name="pid"]').val();
        var type_name=$.trim(obj.find('input[name="qqGroupName"]').val());
        var type_value=$.trim(obj.find('input[name="qqGroupTel"]').val());
        var ext=$.trim(obj.find('input[name="qqGroupLink"]').val());
        if(type_value==''){
            layer.msg("<?php echo tpl_modifier_tr('QQ号不能为空','org'); ?>");
            return false;
        }
        if(type_name.replace(/[\u0391-\uFFE5]/g,"aa").length > 20){
            layer.msg("<?php echo tpl_modifier_tr('长度不能超过10个汉字或20个字符','org'); ?>");
            return false;
        }else if(type_name == ''){
            layer.msg("<?php echo tpl_modifier_tr('客服名称不能为空','org'); ?>");
            return false;
        }
        if(ext == ''){
            layer.msg("<?php echo tpl_modifier_tr('QQ群链接不能为空','org'); ?>");
            return false;
        }
        if(!pid == ''){
            var param={
                type:2,
                pid:pid,
                type_name:type_name,
                type_value:type_value,
                ext:ext
            }
            updateQQ(param);
        }else{
            var param={
                type:2,
                type_name:type_name,
                type_value:type_value,
                ext:ext
            }
            addQQ(param);
        }

    });
})
/*
 * type:  1:qq;2:qqgroup
 */
function getTools(t,curr){
        var page = curr || 1;
        var params={
            page:page,
            pageSize:10,
            type:t
        }
        $.ajax({
            url:'/org/customerTools/listAjax',
            type:'post',
            data:params,
            async:false,
            dataType: 'json',
            success:function(xhr){
                if(xhr.code == 0){
                    if(t == 1){
                        $("#QQList").show();
//                        $("#qqpager").show();
                        if(xhr.data[0] == ''){
                            $("#QQList").hide();
                            $("#QQMan").append(addEmpty('你还没有添加QQ客服哦！',function(){ showQQ(1)}));
                        }else{
                            $("div.emptyList").remove();
                        }
                        var trList =$("#qqtrList").html();
                        var html = ejs.render(trList,{ data:xhr.data[0].qq });
                        $("#QQList tbody").html(html);
                        laypage({
                            cont: $("#qqpager"),
                            pages: xhr.data[1].totalPage,
                            curr: curr || 1,
                            jump: function(obj, first){
                                if(!first){
                                    getTools(t,obj.curr);
                                }
                            }
                        });
                    }else if(t == 2){
                        $("#QQgroupList").show();
//                        $("#qqGrouppager").show();
                        if(xhr.data[0]==''){
                            $("#QQgroupList").hide();
                            $("#QQgroupMan").append(addEmpty('你还没有添加QQ群哦！',function(){ showQQ(2)}));
                        }else{
                            $("div.emptyList").remove();
                        }
                        var trList =$("#qqGrouptrList").html();
                        var html = ejs.render(trList,{ data:xhr.data[0].qqun });
                        $("#QQgroupList tbody").html(html);
                        laypage({
                            cont: $("#qqGrouppager"),
                            pages: xhr.data[1].totalPage,
                            curr: curr || 1,
                            jump: function(obj, first){
                                if(!first){
                                    getTools(t,obj.curr);
                                }
                            }
                        });
                    }
                }else if(code == -1){
                    $("#QQgroupMan").append(addEmpty('你还没有添加QQ群哦！',showQQ(2)));
                    $("#QQMan").append(addEmpty('你还没有添加QQ客服哦！',showQQ(1)));
                }
            }
        })
    }
function addEmpty(msg,Fun){
    var m = msg || '还没有内容哦！快去添加吧';
    var DIV = $("<div>").addClass('col-md-20 fs14 emptyList');
    var P = $("<p>").html(m).css('display','inline-block');
    var A = $("<a>").html('点击添加').addClass('blue-link').attr('href','javascript:;').on('click',Fun);
    DIV.append(P).append(A);
    return DIV
}
function showQQ(type){
    if(type == 1){
//        $('#tool_comment').find('input[name="pid"]').val(pid);
        layer.open({
            type: 1,
            title: ['QQ客服'],
            closeBtn: 1,
            area: ['430px','280px'],
            shadeClose: true,
            content:$('#tool_comment')
        });
    }else if(type == 2){
//        $('#tools_comment').find('input[name="pid"]').val(pid);
        layer.open({
            type: 1,
            title: ['QQ群'],
            closeBtn: 1,
            area: ['430px','280px'],
            shadeClose: true,
            content:$('#tools_comment')
        });
    }

}
function editQQ(type,pid) {
    getOrgCustomInfo(pid).done(function (xhr) {
            if(xhr.code == 0){
                pushData(xhr);
            }else{
                layer.msg(xhr.msg);
                return
            }
    })
    function pushData (xhr) {

    if (type == 1) {
        var thisObj = $('#tool_comment');
            thisObj.find('input[name="pid"]').val(xhr.data.pk_customer);
            thisObj.find('input[name="customerName"]').val(xhr.data.type_name);
            thisObj.find('input[name="customerTel"]').val(xhr.data.type_value);
            showQQ(1);
        } else if (type == 2) {
            var thisObj = $('#tools_comment');
            thisObj.find('input[name="pid"]').val(xhr.data.pk_customer);
            thisObj.find('input[name="qqGroupName"]').val(xhr.data.type_name);
            thisObj.find('input[name="qqGroupTel"]').val(xhr.data.type_value);
            thisObj.find('input[name="qqGroupLink"]').val(xhr.data.ext);
            showQQ(2);
        }
    }
}
function getOrgCustomInfo(pid){
    return $.ajax({
        type: 'post',
        url: '/org/customerTools/getOrgCustomInfoAjax',
        data: { pid: pid},
        dataType: 'json',
        async: false
    })
}
function updateQQ(param){
    $.ajax({
        type:'post',
        url:'/org/customerTools/updateOrgCustomInfoAjax',
        data:param,
        dataTYpe:'json',
        success:function(xhr){
            xhr=JSON.parse(xhr);
            if(xhr.code == 0){
                layer.msg(xhr.msg);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }else{
                layer.msg(xhr.msg);
            }
        }
    })
}
function addQQ(param){
    $.ajax({
        type:'post',
        url:'/org/customerTools/addOrgCustomerInfoAjax',
        data:param,
        dataTYpe:'json',
        success:function(xhr){
            xhr=JSON.parse(xhr);
            if(xhr.code == 0){
                layer.msg(xhr.msg);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }else{
                layer.msg(xhr.msg);
            }
        }
    })
}
function delQQ(pid){
    $.ajax({
        type:'post',
        url:'/org/customerTools/delOrgCustomerInfoAjax',
        data:{ pid:pid},
        dataTYpe:'json',
        success:function(xhr){
            var xhr=JSON.parse(xhr);
            if(xhr.code == 0){
                layer.msg(xhr.msg);
                    window.location.reload();
            }else {
                layer.msg(xhr.msg);
            }

        }
    })
}
</script>
