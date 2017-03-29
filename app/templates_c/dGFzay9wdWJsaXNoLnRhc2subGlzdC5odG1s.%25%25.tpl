<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 布置作业 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<title class="head-title">作业列表</title>
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<section class="pd30">
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/user.main.menu.teacher.teacherTaskList"); ?>
            <p class="mob-nav hidden-lg hidden-md">
                <a href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?>" class="col-xs-5">我的主页</a>
                <a href="/teacher.course.timetable2" class="col-xs-5">课程表</a>
                <a href="/teacher.course.teacherOfCourseList" class="col-xs-5">在教课程</a>
                <a href="/teacher.manage.student" class="col-xs-5">我的学生</a>
            </p>
            <div class="right-main col-sm-20  col-md-16 col-xs-20">
                <div class="content" id="content">
                    <!-- navMenu -->
                    <nav class="tab-main clearfix">
                        <ul class="tab-hd fs14" id="tab-hd">
                            <a data-status="3" href="javascript:;" class="tab-hd-opt curr">全部</a>
                            <a data-status="0" href="javascript:;" class="tab-hd-opt">未发布</a>
                            <a data-status="1" href="javascript:;" class="tab-hd-opt">
                                待批改
                                <div class="prompt">
                                </div>
                            </a>
                            <a data-status="2" href="javascript:;" class="tab-hd-opt">已批改</a>
                        </ul>
                        <a href="javascript:;" class="add-button c-fr" id="send-task-btn">布置作业</a>
                    </nav>
                    <!-- /navMenu -->
                    <!--no-data-->
                    <div class='list-tu' id="no-data">
                        <div class='list-img'>
                            <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                            <div class='list-book'>
                                <span>还没有作业,布置一份试试吧~</span>
                            </div>
                        </div>
                    </div>
                    <!-- list -->
                    <section class="block-list" >
                    </section>
                    <script id="block-list" type="template">
                        <% if(data) { for(var i in data) { %>
                        <div class="block-floor">
                            <div class="first-zoom">
                                <% var nowYear = new Date().getFullYear(); if(nowYear != i){ %>
                                <div class="zoom-head" data-year="<%= i%>">
                                    <span class="left zoom-title cYellow"><%= i%>年</span>
                                    <div class="zoom-icon right"></div>
                                    <div class="zoom-split right col-sm-18 hidden-xs">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <% }%>
                                <ul class="zoom-list zoom-down">
                                    <% var monthData = data[i] ; for (var j in monthData) { %>
                                    <li class="col-md-20 p0">
                                        <div class="zoom-head" data-month="<%= j%>">
                                            <span class="fs14 left zoom-title lGray"><%= j%>月</span>
                                            <div class="zoom-icon right"></div>
                                            <div class="zoom-split right col-sm-19 hidden-xs">
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <% var workData = monthData[j] ;  for (var p in workData) {   %>
                                        <div class="co-md-20 zoom-info zoom-down clearfix p10 col-xs-20" data-taskId="<%= p%>" >
                                            <!-- lt -->
                                            <div class="col-md-3 zoom-info-time col-xs-5">
                                                <p><%= workData[p].create_time_month%></p>
                                                <p class="cGray"><%= workData[p].create_time_time%></p>
                                                <span class="vertical-split"></span>
                                                <span class="zoom-info-icon"></span>
                                            </div>
                                            <!-- /lt -->
                                            <!-- rt -->
                                            <div class="col-md-17 zoom-info-course p10 clearfix col-xs-15">
                                                <div class="col-md-6 pd0">
                                                    <p class="ellipsis"><%= workData[p].title+' '+workData[p].name%></p>
                                                    <p class="cGray">
                                                        提交：
                                                        <% if(workData[p].user_total != '0' ){ %>
                                                        <span><%= workData[p].student_count +'/'+workData[p].user_total %></span>
                                                        <% } else { %>
                                                        <span>——</span>
                                                        <% }%>
                                                        批改：
                                                        <% if(workData[p].student_count != '0' ){  if( workData[p].mark_count == workData[p].student_count ){ workData[p].correct =false; }   %>
                                                        <span><%= workData[p].mark_count+'/'+workData[p].student_count%></span>
                                                        <% } else { %>
                                                        <span>——</span>
                                                        <% }%>
                                                    </p>
                                                </div>
                                                <div class="col-md-9 pd0">
                                                    <%  if(workData[p].desc || workData[p].attach ||  workData[p].thumb) { %>
                                                    <p>
                                                        <% if(workData[p].status == "0" ){ %>
                                                        <a href="/task.publishTask.teacherPublishTask?type=update&taskId=<%= p.match(/\d+/)[0]%>" title="" >
                                                            <%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%>
                                                        </a>
                                                        <% } else if(workData[p].status == "1") { %>
                                                        <a href="/task/publishTask/taskDetailShow?taskId=<%= p.match(/\d+/)[0]%>" title="" target="_blank" >
                                                            <%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%>
                                                        </a>
                                                        <%} else if(workData[p].status == "2"){ %>
                                                        <a href="/task/publishTask/taskDetailShow?taskId=<%= p.match(/\d+/)[0]%>" target="_blank" title="">
                                                            <%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%>
                                                        </a>
                                                        <% }%>
                                                    </p>
                                                    <% } else { %>
                                                    <p>暂无标题</p>
                                                    <% }%>
                                                    <p class="cGray">截止时间：<%= workData[p].end_time_handle%></p>
                                                </div>
                                                <div class="col-md-5 zoom-info-action">
                                                    <% if(workData[p].status == "0" ){ %>
                                                    <a href="/task.publishTask.teacherPublishTask?type=update&taskId=<%= p.match(/\d+/)[0]%>" title="" class="blue-link" >修改</a>
                                                    <% } else if(workData[p].status == "1") { %>
                                                    <a href="/task/publishTask/taskDetailShow?taskId=<%= p.match(/\d+/)[0]%>" title="" target="_blank" class="blue-link">查看</a>
                                                    <% if(workData[p].correct ){ %>
                                                    |
                                                    <a href="/task.replyTask.replyTask?taskId=<%= p.match(/\d+/)[0]%>" title="" class="blue-link" target="_blank">批改</a>
                                                    <% }%>
                                                    <%} else if(workData[p].status == "2"){ %>
                                                    <a href="/task/publishTask/taskDetailShow?taskId=<%= p.match(/\d+/)[0]%>" target="_blank" title="" class="blue-link">查看</a>
                                                    <% }%>
                                                </div>
                                            </div>
                                            <!-- /rt -->
                                        </div>
                                        <% }%>
                                    </li>
                                    <% } %>
                                    <div class="clear"></div>
                                </ul>
                            </div>
                        </div>
                        <% } }; %>
                        <% pages=parseInt(page.page);if(pages != page.totalPage) { %>
                        <div class="col-md-20 tac mt20">
                            <a href="javascript:;" title="" class="more-button show">
                                点击查看更多
                            </a>
                        </div>
                        <% }%>
                    </script>
                    <!-- /list -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 发放作业 -->
<section id="send-task-info" class="pt-send-task-info" style="display:none;">
    <div class="p10">
        <p class=" pt30"></p>
        <div class="course divselect divselect-32 col-xs-10 mr10 p0">
            <cite>
                <span class="cite-icon"></span>
                <span class="cite-text">请选择</span>
            </cite>
            <dl>
            </dl>
            <input type="hidden" value="" name="courseId"/>
        </div>
        <div class="class divselect divselect-32 col-xs-9 p0">
            <cite>
                <span class="cite-icon"></span>
                <span class="cite-text">请选择</span>
            </cite>
            <dl>
            </dl>
            <input type="hidden" value="" name="classId"/>
        </div>
        <button class="yellow-button col-xs-offset-8 mt20">确定</button>
    </div>
</section>
<script type="text/javascript">
    //处理标题
    function initTaskTitle(desc,attach,thumb) {
        var str = '';
        var desc = $.trim(desc);
        if(desc){
            str =  desc.slice(0,20) + '...';
        }else if( attach && thumb ){
            str = '1.' + thumb + ' ' + '2.' + attach;
        }else if(!attach || !thumb){
            str = '1.' + ( thumb || attach );
        }
        return str;
    }

    $(function () {
        var nowStatus = 3,totalPage,nowPage = 1,courseStatus=1;
        //布置作业
        var sendTaskInfo=$('#send-task-info');
        $('#send-task-btn').click(function () {
            if(sendTaskInfo.find('.course').find('dd').length==0){
                getCourse();
            }
            if(courseStatus == 0){
                layer.msg('您还没有课程，无法布置作业！');
            }else{
                layer.open({
                    type: 1,
                    title: ['选择课程班级'],
                    area: ['348px', '218px'],
                    shadeClose: true,
                    content: $('#send-task-info'),
                    scrollbar:false,
                    success:function () {
                        $('body,html').css({
                            width:'100%',
                            height:'100%',
                            overflow:'hidden'
                        });
                    },
                    end:function () {
                        $('body,html').css({
                            width:'auto',
                            height:'auto',
                            overflow:'visible'
                        });
                    }
                });
            }

        })

        //同步班级信息
        $('#send-task-info .course .cite-text').on('valuechange', function () {
            var courseId = $(this).parents('.divselect').find('input').val();
            if(courseId != ''){
                $.ajax('/task/publishTask/getCourseClass', {
                    dataType: 'json',
                    type: 'post',
                    data: {
                        course_id: courseId
                    },
                    success: function (data) {
                        var result = data.result;
                        if (result.code == 200) {
                            var data = result.data.result;
                            if (data.length > 0) {
                                var classHtml = '';
                                for (var i = 0; i < data.length; i++) {
                                    if (i == 0) {
                                        classHtml += '<dd selected="selected" ><a href="javascript:;" selectid="' + data[i].pk_class + '" title="">' + data[i].name + '</a></dd>';
                                    } else {
                                        classHtml += '<dd ><a href="javascript:;" selectid="' + data[i].pk_class + '" title="">' + data[i].name + '</a></dd>';
                                    }
                                }
                                var classSelect = $('#send-task-info').find('.class.divselect');
                                classSelect.find('input').val(data[0].pk_class);
                                classSelect.find('.cite-text').text(data[0].name);
                                classSelect.find('dl').html(classHtml);
                            }
                        }
                    }
                });
            }
        })

        $('#send-task-info').on('click', '.yellow-button', function () {
            var courseId = $(this).parent().find('input[name=courseId]').val();
            var classId = $(this).parent().find('input[name=classId]').val();
            location.href='/task.publishTask.teacherPublishTask?type=create&courseId='+courseId+'&classId='+classId;
        })
        //渲染作业列表
        getWorkList(nowStatus);
        function getWorkList(status,page) {
            var workStatus = status ? status : 3 ;
            var dataPage = page ? page : 1 ;
            $.ajax('/task/publishTask/taskList',{
                dataType:'json',
                type:'get',
                data:{
                    status:workStatus,
                    page:dataPage
                },
                success:function (data) {
                    var result = data.result;
                    if(result.code == 200){
                        if($.isEmptyObject(data)){
                            $('#no-data').addClass('show');
                            return false;
                        }
                        var page = data.data.page;
                        var prompt = data.data.status;
                        var data = data.data.data;
                        if(prompt=='prompt'){
                            $('.prompt').addClass('show');
                        }else{
                            $('.prompt').removeClass('show');
                        }
                        var listHtml = $('#block-list').html();
                        $('#content').find('.block-list').html(ejs.render(listHtml, { data: data,page:page}))
                    }else{
                        $('#no-data').addClass('show');
                    }
                },
                error:function () {
                    $('#no-data').addClass('show');
                }
            })
        };
        //渲染更多作业列表
        function addWorkList(status,page) {
            var workStatus = status ? status : 3 ;
            var dataPage = page ? page : 1 ;
            $.ajax('/task/publishTask/taskList',{
                dataType:'json',
                type:'get',
                data:{
                    status:workStatus,
                    page:dataPage
                },
                success:function (data) {
                    var result = data.result;
                    if(result.code == 200){
                        if($.isEmptyObject(data)){
                            $('#no-data').addClass('show');
                            return false;
                        }
                        var page = data.data.page;
                        var prompt = data.data.status;
                        var totalPage = page.totalPage;
                        var currpage= page.page;
                        var data = data.data.data;
                        if(currpage==totalPage){
                            $('.more-button').hide().removeClass('show');
                        }
                        if(prompt=='prompt'){
                            $('.prompt').addClass('show');
                        }else{
                            $('.prompt').removeClass('show');
                        }
                        var listHtml = $('.block-list');
                        if(data) {
                            for(var i in data) {
                                if(i != new Date().getFullYear()){
                                    var yearHtml='<div class="first-zoom"><div class="zoom-head" data-year="'+i+'"><span class="left zoom-title cYellow">'+i+'年</span><div class="zoom-icon right"></div><div class="zoom-split right col-sm-18 hidden-xs"></div><div class="clear"></div></div></div>';
                                    listHtml.find('.block-floor').append(yearHtml);
                                };
                                var monthData = data[i];

                                for (var j in monthData) {
                                    var num=0;
                                    $('.block-list').find('.zoom-head[data-month="'+j+'"]') ? num=$('.block-list').find('.zoom-head[data-month="'+j+'"]').length : num=0;
                                    var workData = monthData[j] ;
                                    if(num == 0){
                                        var dataHtml=$('<li class="col-md-20 p0"></li>');
                                        dataHtml.append('<div class="zoom-head" data-month="'+j+'"><span class="fs14 left zoom-title lGray">'+j+'月</span><div class="zoom-icon right"></div><div class="zoom-split right col-sm-19 hidden-xs"></div><div class="clear"></div></div>')
                                        for (var p in workData) {
                                            dataHtml.append('<div class="co-md-20 zoom-info zoom-down clearfix p10 col-xs-20" data-taskId="'+p+'" ></div>');
                                            dataHtml.find('.zoom-info').append('<div class="col-md-3 zoom-info-time col-xs-8"><p>'+workData[p].create_time_month+'</p><p class="cGray">'+workData[p].create_time_time+'</p><span class="vertical-split"></span><span class="zoom-info-icon"></span></div>');
                                            dataHtml.find('.zoom-info').append('<div class="col-md-17 zoom-info-course p10 clearfix col-xs-15"><div class="col-md-6 pd0"><p class="ellipsis">'+workData[p].title+' '+workData[p].name+'</p></div><div class="col-md-9 workData-desc pd0"></div><div class="col-md-5 zoom-info-action"></div</div>');
                                            if(workData[p].user_total != '0' ){
                                                dataHtml.find('.ellipsis').after('<p class="cGray">提交：<span>'+ workData[p].student_count+'/'+workData[p].user_total +'</span></p>');
                                            }else {
                                                dataHtml.find('.ellipsis').after('<p class="cGray">提交：<span>——</span></p>');
                                            };
                                            if(workData[p].student_count != '0' ){
                                                if( workData[p].mark_count == workData[p].student_count ){ workData[p].correct =false; }
                                                dataHtml.find('.ellipsis').siblings().append(' 批改：<span>'+ workData[p].mark_count+'/'+workData[p].student_count +'</span>');
                                            }else {
                                                dataHtml.find('.ellipsis').siblings().append(' 批改：<span>——</span>');
                                            };

                                            if(workData[p].desc || workData[p].attach ||  workData[p].thumb) {
                                                if(workData[p].status == "0" ){
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.teacherPublishTask?type=update&taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                } else if(workData[p].status == "1") {
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                } else if(workData[p].status == "2"){
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                }
                                            }else {
                                                dataHtml.find('.workData-desc').append('暂无标题');
                                            };
                                            dataHtml.find('.workData-desc').append('<p class="cGray">截止时间：'+workData[p].end_time_handle+'</p>');
                                            if(workData[p].status == "0" ){
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task.publishTask.teacherPublishTask?type=update&taskId='+p.match(/\d+/)[0]+'" title="" class="blue-link" >修改</a>');
                                            } else if(workData[p].status == "1") {
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task/publishTask/taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" target="_blank" class="blue-link">查看</a>');
                                                if(workData[p].correct ){
                                                    dataHtml.find('.zoom-info-action').append('| <a href="/task.replyTask.replyTask?taskId='+p.match(/\d+/)[0]+'" title="" class="blue-link" target="_blank">批改</a>');
                                                }
                                            } else if(workData[p].status == "2"){
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task/publishTask/taskDetailShow?taskId='+p.match(/\d+/)[0]+'" target="_blank" title="" class="blue-link">查看</a>');
                                            };
                                        };
                                        listHtml.find('.zoom-list li:last').after(dataHtml);
                                    }else if(num == 1){
                                        for (var p in workData) {
                                            var dataHtml=$('<div class="co-md-20 zoom-info zoom-down clearfix p10 col-xs-20" data-taskId="'+p+'" ></div>');
                                            dataHtml.append('<div class="col-md-3 zoom-info-time col-xs-5"><p>'+workData[p].create_time_month+'</p><p class="cGray">'+workData[p].create_time_time+'</p><span class="vertical-split"></span><span class="zoom-info-icon"></span></div>');
                                            dataHtml.append('<div class="col-md-17 zoom-info-course p10 clearfix col-xs-15"><div class="col-md-6 pd0"><p class="ellipsis">'+workData[p].title+' '+workData[p].name+'</p></div><div class="col-md-9 pd0 workData-desc"></div><div class="col-md-5 zoom-info-action"></div</div>');
                                            if(workData[p].user_total != '0' ){
                                                dataHtml.find('.ellipsis').after('<p class="cGray">提交：<span>'+ workData[p].student_count+'/'+workData[p].user_total +'</span></p>');
                                            }else {
                                                dataHtml.find('.ellipsis').after('<p class="cGray">提交：<span>——</span></p>');
                                            };
                                            if(workData[p].student_count != '0' ){
                                                if( workData[p].mark_count == workData[p].student_count ){ workData[p].correct =false; }
                                                dataHtml.find('.ellipsis').siblings().append(' 批改：<span>'+ workData[p].mark_count+'/'+workData[p].student_count +'</span>');
                                            }else {
                                                dataHtml.find('.ellipsis').siblings().append(' 批改：<span>——</span>');
                                            };

                                            if(workData[p].desc || workData[p].attach ||  workData[p].thumb) {
                                                if(workData[p].status == "0" ){
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.teacherPublishTask?type=update&taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                } else if(workData[p].status == "1") {
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                } else if(workData[p].status == "2"){
                                                    dataHtml.find('.workData-desc').append('<p><a href="/task.publishTask.taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" >'+initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)+'</a></p>');
                                                }
                                            }else {
                                                dataHtml.find('.workData-desc').append('暂无标题');
                                            };
                                            dataHtml.find('.workData-desc').append('<p class="cGray">截止时间：'+workData[p].end_time_handle+'</p>');
                                            if(workData[p].status == "0" ){
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task.publishTask.teacherPublishTask?type=update&taskId='+p.match(/\d+/)[0]+'" title="" class="blue-link" >修改</a>');
                                            } else if(workData[p].status == "1") {
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task/publishTask/taskDetailShow?taskId='+p.match(/\d+/)[0]+'" title="" target="_blank" class="blue-link">查看</a>');
                                                if(workData[p].correct ){
                                                    dataHtml.find('.zoom-info-action').append('| <a href="/task.replyTask.replyTask?taskId='+p.match(/\d+/)[0]+'" title="" class="blue-link" target="_blank">批改</a>');
                                                }
                                            } else if(workData[p].status == "2"){
                                                    dataHtml.find('.zoom-info-action').append('<a href="/task/publishTask/taskDetailShow?taskId='+p.match(/\d+/)[0]+'" target="_blank" title="" class="blue-link">查看</a>');
                                            };
                                            $('.block-list').find('.zoom-head[data-month="'+j+'"]').parent().append(dataHtml);
                                        }
                                    }

                                }
                            }
                        }
                    }else{
                        $('#no-data').addClass('show');
                    }
                },
                error:function () {
                    $('#no-data').addClass('show');
                }
            })
        };
        //点击加载更多
        $('#content').on('click','.more-button',function () {
            nowPage++;
            addWorkList(nowStatus,nowPage);
            if(nowPage == totalPage){
                $(this).removeClass('show');
                return false;
            }
        })
        //收起列表
        $('#content').on('click','.zoom-icon',function () {
            var rootParent = $(this).parent();
            if($(this).hasClass('down')){
                $(this).removeClass('down');
                rootParent.find('.zoom-split').removeClass('show');
                rootParent.parent().find('.zoom-down').removeClass('hidden');
            }else{
                $(this).addClass('down');
                rootParent.find('.zoom-split').addClass('show');
                rootParent.parent().find('.zoom-down').addClass('hidden');
            }
        })
        //切换tab
        $('#tab-hd').on('click','.tab-hd-opt',function () {
            $('#content').find('.block-list').html('');
            $('#no-data').removeClass('show');
            $(this).parent().find('.curr').removeClass('curr');
            $(this).addClass('curr');
            nowStatus = $(this).attr('data-status');
            getWorkList(nowStatus);
        })
    })

    function getCourse(){
        //课程信息获取
        $.ajax('/task/publishTask/courseInfo', {
            dataType: 'json',
            type: 'post',
            success: function (data) {
                var result = data.result;
                if (result.code == 200) {
                    var data = data.data;
                    var select = $('#send-task-info').find('.course.divselect dl');
                    var courseHtml = $('#divselect').html();
                    select.html(ejs.render(courseHtml, { lists: data}));
                    select.find('dd').eq(0).attr('selected','selected');
                    var selectText = select.find('dd').eq(0).find('a').text();
                    var selectId = select.find('dd').eq(0).find('a').attr('selectid');
                    select.parents('.divselect').find('input').val(selectId);
                    select.parents('.divselect').find('.cite-text').text(selectText);
                }else if(result.code == -109){
                    courseStatus=0;
                }
            }
        });
    }
</script>
<script id="divselect" type="template">
    <% if(lists){  for(var i in lists){  %>
    <dd>
        <a href="javascript:;" selectid="<%= i%>" title=""><%= lists[i]%></a>
    </dd>
    <% }  }else { %>
    <dd selected="selected">
        <a href="javascript:;" title="">请选择</a>
    </dd>
    <% }; %>
</script>

<!-- /发放作业 -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
