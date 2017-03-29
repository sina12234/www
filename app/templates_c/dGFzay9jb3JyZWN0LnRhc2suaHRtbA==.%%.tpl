<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 批改作业 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <title class="head-title">批改作业</title>
    <?php echo tpl_function_part("/site.main.header"); ?>
    <link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
</head>
<body>
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.zclip.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.task.common.js'); ?>"></script>
<section class="org-section">
    <div class="container main-center ct-content">
        <div class="ed-title mb20">
            <div class="tab-button hidden-xs ">批改作业</div>
            <div id="head-title" class="title col-lg-15 col-md-15 col-sm-15 col-xs-15">
            </div>
        </div>
        <!-- tp -->
        <section id="pt-work-detail" class="mb20 pt-work-detail">
        </section>
        <script type="template" id="work-detail">
            <table class="table-grid">
                <thead>
                <tr>
                    <td class="col-md-8 col-xs-10"><%= taskInfo.create_time%></td>
                    <td class="col-md-4 col-xs-10" id="PkTaskStudent" data-task-student="<%= taskData.pk_task_student%>">学生：<%= taskInfo.StudentName%></td>
                    <td class="col-md-8 col-xs-17">提交时间：<%= taskInfo.start_time%></td>
                </tr>
                </thead>
            </table>
            <div class="look-task-content">
                <div class="text-box">
                    <%= taskData.desc%>
                </div>
                <!--图片-->
                <div class="task-result">
                    <% if(thumb.length>0){ thumb.forEach(function(item){ %>
                    <div class="img-box" data-count = '0' >
                        <div class="correct-box">
                            <div class="correct-action">
                                批改
                            </div>
                            <img src="<%= item.src_big%>" alt="">
                        </div>
                    </div>
                    <% }) }  %>
                </div>
                <!-- 附件 -->
                <div class="file-tag-box">
                    <% if(attach.length > 0){  %>
                    <p class="c-fl">附件</p>
                    <% for(var i = 0 ; i < attach.length ; i++){ var fileType=attach[i].type.split('.')[1], fileName=attach[i].file || 0; if(fileName != 0){ %>
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
                    <% }; %>
                    <% }; }; %>
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
        <!-- list -->
        <div class="task-content mt30">
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-10">
            </div>
            <div class="task-action col-lg-15 col-md-15 col-sm-15 col-xs-15 ">
                <div id="task-result" class="task-result left">

                </div>
                <script type="template" id="image-box">
                    <%  var index = index == 'undefined' ? ' ' : index ;  %>
                    <div class="image-box result-box left new" data-index="<%= index%>">
                        <div class="clear-icon"></div>
                        <input type="hidden" name="bigImg" value="<%= imgUrl.big%>">
                        <input type="hidden" name="smallImg" value="<%= imgUrl.small%>">
                        <input type="hidden" name="smallWidth" value="<%= imgW%>">
                        <input type="hidden" name="smallHeight" value="<%= imgH%>">
                        <img src="<%= imgUrl.src_small%>" alt="">
                    </div>
                </script>
                <div class="button-box left">
                    <div class="add-image left" id="add-image">
                        <div class="add-image-icon"></div>
                    </div>
                    <div class="add-image-info left">
                        最多上传10张图片
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="task-content mt30">
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-10">
                评语：
            </div>
            <div class="task-action col-lg-15 col-md-15 col-sm-15 col-xs-20 ">
                <div class="textarea-box left">
                    <div class="textarea" contenteditable="true" id="textarea" data-max="1000">

                    </div>
                    <div class="place-holder" id="place-holder">
                        最多输入1000个字符
                    </div>
                </div>
                <div  class="error">

                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="task-content mt30" id="knowledge-tab" >
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-20 pl0">
                选择掌握较差的知识点：
            </div>
            <div class="dropdown pt-dropdown  col-lg-15 col-md-15 col-sm-15 col-xs-15 ">
            </div>
            <script type="text/template" id="dropdown-show-tab">
                <% if(tags) { %>
                <% if(tags['length'] && tags.length>0 ) { for ( var i = 0 ; i < tags.length ; i++ ) { %>
                    <div class="dropdown-show-tab left" data-id="<%= tags[i].pk_tag%>">
                        <div class="left-side"></div>
                        <%= tags[i].name%>
                    </div>
                <% } }else{ $('#knowledge-tab').hide();} %>
                <%}%>
            </script>
            <div class="clear"></div>
        </div>

        <div class="task-content mt30">
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-20 pl0">
                评级：
            </div>
            <div class="divselect divselect-32"  id="level" >
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择作业质量级别</span>
                </cite>
                <dl>
                    <dd selected="selected">
                        <a href="javascript:;">请选择作业质量级别</a>
                    </dd>
                    <dd >
                        <a href="javascript:;" selectid="5">优(五朵小红花)</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" selectid="4">良(四朵小红花)</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" selectid="3">中(三朵小红花)</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" selectid="2">较差(二朵小红花)</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" selectid="1">差(一朵小红花)</a>
                    </dd>
                </dl>
                <input type="hidden" value=""  name="level">
            </div>
            <div class="clear"></div>
        </div>
            <div class="submit-box mt20">
                <div class="submit correct-submit" id="correct-sub">
                    <div class="btn">完成批改</div>
                    <a id="next-task" class="blue-link" href="javascript:;">完成并批改下一份作业</a>
                </div>
            </div>
        </div>
        <!-- /list -->

    </div>
</section>
<div class="object" id="object">
</div>
<script>

    $(function () {
        $(window).bind('beforeunload',function(){
            return '未保存的内容将会清空，且无法恢复。';
        });
        //批改作业
        $('#pt-work-detail').on('click','.img-box',function () {
            var count = $(this).attr('data-count');
            var imgUrl = $(this).find('img').attr('src');
            if(count == '0'){
                window['oldImg'] = imgUrl;
                $(this).attr('data-count','1')
            }
            window['dataImg'] = imgUrl;
            window['activeImgIndex'] = $(this).index();
            layer.open({
                type:2,
                title:false,
                closeBtn:0,
                area:['100%','100%'],
                content:['/task/publishTask/Flash','no'],
            });
        })

    })
</script>
<script type="text/javascript">
    $(function() {
        var taskId = $.getUrlParam('taskId');
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
        //获取数据渲染页面
        showDeatil();

        function showDeatil( ) {
            var showData  = { };
            showData['fk_task'] = taskId;

            if(studentId){
                showData['fk_user_student'] = studentId;
            }

            $.ajax('/task/replyTask/replyTaskShow/',{
                dataType:'json',
                type:'post',
                data:showData,
                success:function (data) {
                    var result = data.result;
                    if(result.code==200){
                        var data = data.data;
                        var taskInfo = data.taskInfo;
                        var taskData = data.data;
                        var attach = data.attach;
                        var thumb = data.thumb;
                        var tags = data.tag;

                        $('#head-title').text(taskInfo.title+' '+taskInfo.className);
                        document.title='批改作业'+taskInfo.title+taskInfo.className;
                        var detailHtml = $('#work-detail').html();
                        $('#pt-work-detail').html(ejs.render(detailHtml,{ taskInfo:taskInfo,taskData:taskData,attach:attach,thumb:thumb,swappic:swappic}));
                        var tagsHtml = $('#dropdown-show-tab').html();
                        $('#knowledge-tab').find('.dropdown').html(ejs.render(tagsHtml,{ tags:tags}));
                    }else if(result.code == -101){
                        $(window).unbind('beforeunload');
                        location.href = '/task/publishTask/NotFound';
                    }else if(result.code == -102){
                        $(window).unbind('beforeunload');
                        location.href = '/task/publishTask/Delete';
                    }else if (result.code == -104){
                        $(window).unbind('beforeunload');
                        location.href = '/task/publishTask/NotFound?type=noWork';
                    }
                },
                error:function () {
                    layer.msg('网络君开小差了!');
                }
            })
        }
        $('#next-task').on('click',function () {
            submitData.call(this,'nextTask');
        })
        //选择标签
        $('#knowledge-tab').on('click','.dropdown-show-tab',function () {
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
        })
        //展开收起
        $('#pt-work-detail').on('click','.look-task-unfolded',function () {
            var unfolded = $(this).find('.title');
            if(unfolded.find('.display-icon').hasClass('down')) {
                unfolded.find('.display-icon').removeClass('down');
                unfolded.find('span').text('展开作业');
                $('#pt-work-detail').find('.look-task-content').removeClass('show');
            }else {
                unfolded.find('.display-icon').addClass('down');
                unfolded.find('span').text('收起作业');
                $('#pt-work-detail').find('.look-task-content').addClass('show');
            }
        })
        //提交作业
        $('#correct-sub').on('click','.btn',function () {
            submitData.call(this,'submit');
        })
        function submitData(type) {
            var _this=$(this);
            _this.hide();
            _this.after('<div class="gray-btn">正在处理...</div>');
            var type = type || '' ;
            if($('#task-result').find('.no-img').length>0){
                layer.msg('文件正在上传，请稍后');
                return false;
            }

            if(type){
                $('#textarea').blur();
                if($('#textarea').hasClass('error-border')){
                    return false;
                }

                var taskData = { };
                taskData['desc'] = $('#textarea').text();
                taskData['fk_task'] = taskId;
                taskData['level'] = $('#level').find('input[name=level]').val();
                //获取图片
                var imgUrlArr = [];
                $('#task-result').find('.image-box').each(function () {
                    var imgUrl = { };
                    imgUrl['big'] = $(this).find('input[name=bigImg]').val();
                    imgUrl['small'] = $(this).find('input[name=smallImg]').val();
                    imgUrl['small_width'] = $(this).find('input[name=smallWidth]').val();
                    imgUrl['small_height'] = $(this).find('input[name=smallHeight]').val();
                    imgUrlArr.push(imgUrl);
                })
                taskData['taskImages'] = imgUrlArr;

                var taskTag = [];
                $('#knowledge-tab').find('.dropdown-show-tab').each(function () {
                    if($(this).hasClass('active')){
                        taskTag.push($.trim($(this).text()));
                    }
                })
                taskData['tags'] = taskTag.join(',');
                taskData['pk_task_student']= $('#PkTaskStudent').attr('data-task-student');
                $.ajax('/task/replyTask/teacherReplyTask/',{
                    dataType:'json',
                    type:'post',
                    data:taskData,
                    success:function (data) {
                        if(data.code == 200){
                            layer.msg('批改成功');
                            $('#correct-sub').find('.gray-btn').remove();
                            _this.show();
                            $(window).unbind('beforeunload');
                            if(type == 'submit'){
                                location.href = '/task/publishTask/teacherTaskList';
                            }else if(type == 'nextTask'){
                                location.href = '/task.replyTask.replyTask?taskId=' + taskId ;
                            }
                        }else if(data.code == -101){
                            $(window).unbind('beforeunload');
                            location.href = '/task/publishTask/NotFound';
                        }else if(data.code == -105){
                            layer.msg(data.msg);
                            $(window).unbind('beforeunload');
                            location.href = '/task/publishTask/teacherTaskList';
                        }else{
                            layer.msg(data.msg);
                        }
                    },
                    error:function () {
                        $('#correct-sub').find('.gray-btn').remove();
                        layer.msg('提交失败~');
                    }
                })
            }
        }
    })
</script>
<script type="template" id="error-info">
    <div class="tips">
        <span class="tips-icon"></span>
        <span class="tips-text"><%= message%></span>
    </div>
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
