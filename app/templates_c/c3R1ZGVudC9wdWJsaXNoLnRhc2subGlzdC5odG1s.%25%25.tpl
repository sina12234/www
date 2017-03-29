<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 布置作业 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<title>作业列表</title>
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
            <?php echo tpl_function_part("/user.main.menu.student.studentTaskListShow"); ?>
			<div class="mob-nav hidden-lg hidden-md">
				<p class="swiper-wrapper" id="mob-nav">
					<a href="/student.main.growth" class="swiper-slide">我的首页</a>
					<a href="/student.course.mycourse" class="swiper-slide">我的课程</a>
					<a href="/task.commitTask.studentTaskListShow" class="swiper-slide  active">我的作业</a>
					<a href="/student.order.myorder" class="swiper-slide">我的订单</a>
					<a href="/student.fav.myfav" class="swiper-slide">我的收藏</a>
					<a href="/student.discount.mydiscount" class="swiper-slide">我的优惠券</a>
				</p>
			</div>
            <div class="right-main col-sm-20  col-md-16 col-xs-20">
                <div class="content" id="content">
                    <!-- navMenu -->
                    <nav class="tab-main clearfix">
                        <ul class="tab-hd fs14" id="tab-hd">
                            <a data-status="3" href="javascript:;" class="tab-hd-opt curr">全部</a>
                            <a data-status="0" href="javascript:;" class="tab-hd-opt">未提交</a>
                            <a data-status="1" href="javascript:;" class="tab-hd-opt">
                                待批改
                                <div class="prompt">
                                </div>
                            </a>
                            <a data-status="2" href="javascript:;" class="tab-hd-opt">
                                已批改
                            </a>
                        </ul>
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
                                        <% var workData = monthData[j] ; for (var p in workData) { %>
                                        <div class="co-md-20 zoom-info zoom-down clearfix p10" data-taskId="<%= p%>" >
                                            <!-- lt -->
                                            <div class="col-md-3 zoom-info-time col-xs-8">
                                                <p><%= workData[p].create_time_month%></p>
                                                <p class="cGray"><%= workData[p].create_time_time%></p>
                                                <span class="vertical-split"></span>
                                                <span class="zoom-info-icon"></span>
                                            </div>
                                            <!-- /lt -->
                                            <!-- rt -->
                                            <div class="col-md-17 zoom-info-course p10 clearfix col-xs-12">
                                                <div class="col-md-6">
                                                    <p class="ellipsis"><%= workData[p].courseName+' '+workData[p].className%></p>
                                                    <p class="cGray hidden-xs">
                                                        讲师：
                                                        <% if(workData[p].teacher_name != '' ){ %>
                                                        <span><%= workData[p].teacher_name %></span>
                                                        <% } else { %>
                                                        <span>——</span>
                                                        <% }%>
                                                    </p>
                                                </div>
                                                <div class="col-md-9">
                                                    <% if(workData[p].desc || workData[p].attach ||  workData[p].thumb) { %>
                                                    <p class="task_text_info">
                                                        <%  if(workData[p].student_status == null ){ %>
                                                        <a href="/task.commitTask.studentCommitTask?taskId=<%= p.match(/\d+/)[0]%>"  title="" ><%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%></a>
                                                        <% } else if(workData[p].student_status == "1") { %>
                                                        <a href="/task.publishTask.taskCorrectShow?taskId=<%= p.match(/\d+/)[0]%>&type=noCorrect&studentId=<%= workData[p].pk_task_student%>" title="" target="_blank" ><%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%></a>
                                                        <%} else if(workData[p].student_status == "2"){ %>
                                                        <a href="/task.publishTask.taskCorrectShow?taskId=<%= p.match(/\d+/)[0]%>&type=correct&studentId=<%= workData[p].pk_task_student%>" target="_blank" title="" ><%= initTaskTitle(workData[p].desc,workData[p].attach,workData[p].thumb)%></a>
                                                        <% }%>
                                                    </p>
                                                    <% } else { %>
                                                    <p>暂无标题</p>
                                                    <% }%>
                                                    <p class="cGray">截止时间：<%= workData[p].end_time_handle%></p>
                                                </div>
                                                <div class="col-md-5 zoom-info-action">
                                                    <%  if(workData[p].student_status == null ){ %>
                                                    <a href="/task.commitTask.studentCommitTask?taskId=<%= p.match(/\d+/)[0]%>"  title="" class="blue-link" >提交</a>
                                                    <% } else if(workData[p].student_status == "1") { %>
                                                    <a href="/task.publishTask.taskCorrectShow?taskId=<%= p.match(/\d+/)[0]%>&type=noCorrect&studentId=<%= workData[p].pk_task_student%>" title="" target="_blank" class="blue-link">查看</a>
                                                    <%} else if(workData[p].student_status == "2"){ %>
                                                    <a href="/task.publishTask.taskCorrectShow?taskId=<%= p.match(/\d+/)[0]%>&type=correct&studentId=<%= workData[p].pk_task_student%>" target="_blank" title="" class="blue-link">查看结果</a>
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
        var nowStatus = 3,totalPage,nowPage = 1;
        //渲染作业列表
        getWorkList(nowStatus);
        function getWorkList(status,page) {
            var workStatus = status ? status : 0 ;
            var dataPage = page ? page : 1 ;
            $.ajax('/task/commitTask/studentTaskList',{
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
                        totalPage = page.totalPage;
                        if(prompt=='noprompt'){
                            $('.prompt').addClass('hidden');
                        }else{
                            $('.prompt').removeClass('hidden');
                        }
                        var listHtml = $('#block-list').html();
                        $('#content').find('.block-list').append(ejs.render(listHtml, { data: data,page:page}))
                    }else{
                        $('#no-data').addClass('show');
                    }
                },
                error:function () {
                    $('#no-data').addClass('show');
                }
            })
        }
        //渲染更多作业列表
        function addWorkList(status,page) {
            var workStatus = status ? status : 3 ;
            var dataPage = page ? page : 1 ;
            $.ajax('/task/commitTask/studentTaskList',{
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
                                            dataHtml.find('.zoom-info').append('<div class="col-md-17 zoom-info-course p10 clearfix col-xs-15"><div class="col-md-6"><p class="ellipsis">'+workData[p].courseName+' '+workData[p].className+'</p></div><div class="col-md-9 workData-desc"></div><div class="col-md-5 zoom-info-action"></div</div>');
                                            if(workData[p].teacher_name != '' ){
                                                dataHtml.find('.ellipsis').after('<p class="cGray">讲师：<span>'+ workData[p].teacher_name+'</span></p>');
                                            }else {
                                                dataHtml.find('.ellipsis').after('<p class="cGray">讲师：<span>——</span></p>');
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
                                            var dataHtml=$('<div class="co-md-20 zoom-info zoom-down clearfix p10" data-taskId="'+p+'" ></div>');
                                            dataHtml.append('<div class="col-md-3 zoom-info-time col-xs-5"><p>'+workData[p].create_time_month+'</p><p class="cGray">'+workData[p].create_time_time+'</p><span class="vertical-split"></span><span class="zoom-info-icon"></span></div>');
                                            dataHtml.append('<div class="col-md-17 zoom-info-course p10 clearfix col-xs-15"><div class="col-md-6"><p class="ellipsis">'+workData[p].courseName+' '+workData[p].className+'</p></div><div class="col-md-9 workData-desc"></div><div class="col-md-5 zoom-info-action"></div</div>');
                                            if(workData[p].teacher_name != '' ){
                                                dataHtml.find('.ellipsis').after('<p class="cGray">讲师：<span>'+ workData[p].teacher_name+'</span></p>');
                                            }else {
                                                dataHtml.find('.ellipsis').after('<p class="cGray">讲师：<span>——</span></p>');
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
                                            listHtml.find('.zoom-head[data-month="'+j+'"]').parent().append(dataHtml);
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
            nowPage = 0;
            $('#content').find('.block-list').html('');
            $('#no-data').removeClass('show');
            $(this).parent().find('.curr').removeClass('curr');
            $(this).addClass('curr');
            nowStatus = $(this).attr('data-status');
            getWorkList(nowStatus);
        })
    })
</script>

<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
