<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title class="head-title">查看结果</title>
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets_v2/js/viewer/viewer.min.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<body>
<?php echo tpl_function_part("/index.main.usernav.student"); ?>
<!-- body -->
<section id="look-result-task">
	<div class="container">
		<div class="row main-center pt-main-center" >
			<div class="ed-title mb20">
				<div class="tab-button hidden-xs ">作业</div>
				<div id="head-title" class="title col-lg-15 col-md-15 col-sm-15 col-xs-15">
				</div>
			</div>
            <!--table-->
            <table class="table-grid">

            </table>
            <script type="text/template" id="table-grid">
                <thead>
                    <tr>
                        <td class="col-md-8 col-xs-10"><%= taskData.create_time%>&nbsp;&nbsp;作业</td>
                        <td class="col-md-4 col-xs-10 pd0">讲师：<%= taskData.teacherName%></td>
                        <td class="col-md-8 col-xs-20">截止时间：<%= taskData.end_time%></td>
                    </tr>
                </thead>
            </script>
			<!-- tp -->
			<section id="pt-work-detail" class=" pt-work-detail">

			</section>
			<script type="template" id="work-detail">
				<div class="look-task-content show show-task-content">
                    <div class="look-task-type">
                        <h3><%= taskTitle%></h3>
                    </div>
					<div class="text-box">
						<%= taskData.desc%>
					</div>
					<!--图片-->
					<div  class="task-result">
						<% if(thumb.length>0){ thumb.forEach(function(item){ %>
						<div class="img-box col-xs-20">
							<img class="viewer-img" src="<%= item.src_big%>" data-original="<%= item.src_big%>" alt="">
						</div>
						<% }) }  %>
					</div>
					<!-- 附件 -->
					<div class="file-tag-box">
						<% if(attach.length > 0){ %>
						<p class="c-fl">附件</p>
						<% for(var i = 0 ; i < attach.length ; i++){ if( attach[i].file!='' ){ var fileType=attach[i].type.split('.')[1];  %>
						<div class="file-tag">
							<div class="file-info-box">
								<div class="info-img  col-sm-5 col-xs-5">
									<img src="<%= swappic[fileType]%>" alt="">
								</div>
								<div class="file-info col-sm-15 col-xs-15">
									<p><span class="file-info-name"><%= attach[i].name%><%= attach[i].type%></span></p>
									<p class="cGray"></p>
								</div>
								<div class="clear"></div>
							</div>
							<a href="<%= attach[i].src_attach%>" download="<%= attach[i].name%><%= attach[i].type%>" target="_blank">
								<div class="file-clear">
									<div class="download-icon">
									</div>
								</div>
							</a>
						</div>
						<%  } } } %>
						<div class="clear"></div>
					</div>
					<!-- /附件 -->
                    <% if(tags && tags['length'] && tags.length > 0  ){ %>
                    <div  class="task-result task-content">
                        <div class=" fs14">
                            掌握较差的知识点:
                        </div>
                        <div class="dropdown pt-dropdown  pl40 pt10 ">

                            <%  for(var i = 0 ; i < tags.length ; i++ ){ if(tags[i].name !=''){ %>
                                <div class="dropdown-show-tab left active" data-id="<%= tags[i].pk_tag%>">
                                    <div class="left-side"></div>
                                    <%= tags[i].name%>
                                </div>
                            <% }; }  %>
                        </div>
                    </div>
                    <% }%>
                    <div  class="task-result task-content">
                        <div class=" fs14">
                            评级:
                        </div>
                        <div class="level-flower-box">

                            <div class="task-<%=  taskData.level%>-flower">

                            </div>
                        </div>
                    </div>
					<!-- 展开收起 -->

					<!-- /展开收起 -->
				</div>
			</script>
            <section id="show-work-detail" class="pt-work-detail show-work-detail">

            </section>
            <script id="show-work" type="text/template">
                <div class="look-task-content show-task-content">
                    <div class="look-task-type">
                        <h3><%= taskTitle%></h3>
                    </div>
                    <div class="text-box">
                        <%= taskData.desc%>
                    </div>
                    <!--图片-->
                    <div  class="task-result">
                        <% if(thumb.length>0){ thumb.forEach(function(item){ %>
                        <div class="img-box">
                            <img class="viewer-img" src="<%= item.src_big%>" data-original="<%= item.src_big%>" alt="">
                        </div>
                        <% }) }  %>
                    </div>
                    <!-- 附件 -->
                    <div class="file-tag-box">
                        <% if(attach.length > 0){ for(var i = 0 ; i < attach.length ; i++){ if( attach[i].file!='' ){ var fileType=attach[i].type.split('.')[1];  %>
                        <div class="file-tag">
                            <div class="file-info-box">
                                <div class="info-img  col-sm-5 col-xs-5">
                                    <img src="<%= swappic[fileType]%>" alt="">
                                </div>
                                <div class="file-info col-sm-15 col-xs-15">
                                    <p><span class="file-info-name"><%= attach[i].name%><%= attach[i].type%></span></p>
                                    <p class="cGray"></p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <a href="<%= attach[i].src_attach%>" download="<%= attach[i].name%><%= attach[i].type%>" target="_blank">
                                <div class="file-clear">
                                    <div class="download-icon">
                                    </div>
                                </div>
                            </a>
                        </div>
                        <%  } } } %>
                        <div class="clear"></div>
                    </div>
                    <!-- /附件 -->
                    <!-- 展开收起 -->
                    <div class="look-task-unfolded tac">
                        <div class="title cGray">
                            <span>展开作业</span>
                            <div class="display-icon"></div>
                        </div>
                    </div>
                    <!-- /展开收起 -->
                </div>
            </script>
			<!-- /tp -->
		</div>
	</div>
</section>
<!-- /body -->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/viewer/viewer.min.js'); ?>" ></script>
<script>
    $(function () {
        var taskId = $.getUrlParam('taskId');
        var type = $.getUrlParam('type');
        var studentId = $.getUrlParam('studentId');
        var swappic = {
            txt : "/assets_v2/img/lesson-txt.png",
            pdf : "/assets_v2/img/lesson-pdf.png",
            doc : "/assets_v2/img/lesson-doc.png",
            docx: "/assets_v2/img/lesson-doc.png",
            ppt : "/assets_v2/img/lesson-ppt.png",
            pptx: "/assets_v2/img/lesson-ppt.png",
            jpg : "/assets_v2/img/lesson-jpg.png",
            xls : "/assets_v2/img/lesson-xls.png",
            xlsx: "/assets_v2/img/lesson-xls.png",
        };
        if(type == 'noCorrect'){
            $.ajax('index.task.studentTaskDetail',{
                dataType:'json',
                type:'get',
                data:{
                    pk_task_student:studentId
                },
                success:function (data) {
                    var result = data.result;
                    if(result.code == 200){
                        var data = data.data;
                        var taskInfo = data.taskInfo;

                        $('#head-title').text(taskInfo.title+' '+taskInfo.className);
                        document.title='查看作业'+taskInfo.title+taskInfo.className;
                        var tableHtml =$('#table-grid').html();
                        $('#look-result-task').find('.table-grid').html(ejs.render(tableHtml,{ taskData:taskInfo }));
                        if(data['commit']){
                            var commit = data.commit;
                            var commitData = commit.data.items[0];
                            var commitAttach = commit.attach;
                            var commitThumb = commit.thumb;
                            var commitHtml = $('#show-work').html();
                            $('#show-work-detail').append(ejs.render(commitHtml,{ taskTitle:'提交内容',taskData:commitData,attach:commitAttach,thumb:commitThumb,swappic:swappic}));
                        }
                        if(data['publish']){
                            var publish = data.publish;
                            var publishData = publish.data;
                            var publishAttach = publish.attach;
                            var publishThumb = publish.thumb;
                            var publishHtml = $('#show-work').html();
                            $('#show-work-detail').append(ejs.render(publishHtml,{ taskTitle:'作业内容',taskData:publishData,attach:publishAttach,thumb:publishThumb,swappic:swappic}));
                        }

                        $('#show-work-detail').find('.look-task-content').each(function () {
                            var lookTaskHeight = $(this).outerHeight();

                            if(lookTaskHeight > 264) {
                                $(this).addClass('flow');
                                $(this).find('.look-task-unfolded').show();
                            }else {
                                $(this).addClass('show');
                                $(this).find('.look-task-unfolded').hide();
                            }
                        })
                    }else if(result.code == -101){
                        location.href = '/task/publishTask/NotFound';
                    }else if(result.code == -102){
                        location.href = '/task/publishTask/Delete';
                    }else if(result.code == -108){
                        location.href = '/task.commitTask.studentTaskListShow';
                    }
                },
                error:function (data) {

                }
            })
        }else if(type == 'correct'){
            $.ajax('index.task.taskShow',{
                dataType:'json',
                type:'get',
                data:{
                    fk_task_student:studentId
                },
                success:function (data) {
                    var result = data.result;
                    if(result.code == 200){
                        var data = data.data;
                        var taskInfo = data.taskInfo;

                        $('#head-title').text(taskInfo.title+' '+taskInfo.className);
                        document.title='查看作业'+taskInfo.title+taskInfo.className;
                        var tableHtml =$('#table-grid').html();
                        $('#look-result-task').find('.table-grid').html(ejs.render(tableHtml,{ taskData:taskInfo }));
                        if(data['reply']){
                            var reply = data.reply;
                            var replyData = reply.data;
                            var replyAttach = reply.attach;
                            var replyThumb = reply.thumb;
                            var replyTags = reply.tag;
                            var detailHtml = $('#work-detail').html();
                            $('#pt-work-detail').html(ejs.render(detailHtml,{ taskTitle:'批改结果',taskData:replyData,attach:replyAttach,thumb:replyThumb,tags:replyTags,swappic:swappic}));
                        }

                        if(data['commit']){
                            var commit = data.commit;
                            var commitData = commit.data;
                            var commitAttach = commit.attach;
                            var commitThumb = commit.thumb;
                            var commitHtml = $('#show-work').html();
                            $('#show-work-detail').append(ejs.render(commitHtml,{ taskTitle:'提交内容',taskData:commitData,attach:commitAttach,thumb:commitThumb,swappic:swappic}));
                        }
                        if(data['publish']){
                            var publish = data.publish;
                            var publishData = publish.data;
                            var publishAttach = publish.attach;
                            var publishThumb = publish.thumb;
                            var publishHtml = $('#show-work').html();
                            $('#show-work-detail').append(ejs.render(publishHtml,{ taskTitle:'作业内容',taskData:publishData,attach:publishAttach,thumb:publishThumb,swappic:swappic}));
                        }
                        //点击图片放大
                        $('#pt-work-detail').viewer({
                            url: 'data-original',
                            selector: '.viewer-img'
                        });
                        $('#show-work-detail').viewer({
                            url: 'data-original',
                            selector: '.viewer-img'
                        });
                        $('#show-work-detail').find('.look-task-content').eq(0).addClass('not-first')
                        $('#show-work-detail').find('.look-task-content').each(function () {
                            var lookTaskHeight = $(this).outerHeight();
                            if(lookTaskHeight > 264) {
                                $(this).removeClass('show').addClass('flow');
                                $(this).find('.look-task-unfolded').show();
                            }else {
                                $(this).removeClass('flow').addClass('show');
                                $(this).find('.look-task-unfolded').hide();
                            }
                        })
                    }else if(result.code == -108){
                        location.href = '/student/publish.task.list.html';
                    }
                },
                error:function (data) {

                }
            })
        }
        //展开收起
        $('#show-work-detail').on('click','.look-task-unfolded',function () {
            var unfolded = $(this).find('.title');
            if(unfolded.find('.display-icon').hasClass('down')) {
                unfolded.find('.display-icon').removeClass('down');
                unfolded.find('span').text('展开作业');
                $(this).parents('.look-task-content').removeClass('show');
            }else {
                unfolded.find('.display-icon').addClass('down');
                unfolded.find('span').text('收起作业');
                $(this).parents('.look-task-content').addClass('show');
            }
        });
    })
	$.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    };
</script>

<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
