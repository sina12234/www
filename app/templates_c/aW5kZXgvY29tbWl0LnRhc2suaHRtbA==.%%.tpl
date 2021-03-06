<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title class="head-title">提交作业</title>
<?php echo tpl_function_part("/index.main.header"); ?>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
</head>
<body>
<?php echo tpl_function_part("/index.main.usernav/student"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.zclip.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.task.common.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets_v2/js/viewer/viewer.min.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/viewer/viewer.min.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.growthlayer.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<section class="pd20">
    <div class="container main-center">
        <div class="ed-title mb20">
            <div class="tab-button hidden-xs ">提交作业</div>
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
                    <td class="col-md-5 col-xs-12 tal pd0"><%= taskData.create_time%>&nbsp;&nbsp;作业</td>
                    <td class="col-md-7 col-xs-8 pd0">讲师：<%= taskData.teacherName%></td>
                    <td class="col-md-8 col-xs-20">截止时间：<%= taskData.end_time%></td>
                </tr>
                </thead>
            </table>
            <div class="look-task-content">
                <div class="text-box">
                    <%= taskData.desc%>
                </div>
                <!--图片-->
                <ul  class="task-result" id="img-list">
                    <% if(thumb.length>0){ thumb.forEach(function(item){ %>
                    <li class="img-box">
                        <img class="viewer-img" src="<%= item.src_big%>" data-original="<%= item.src_big%>" alt="">
                    </li>
                    <% }) }  %>
                </ul>
                <!-- 附件 -->
                <div class="file-tag-box">
                    <% if(attach.length > 0){  %>
                    <p>附件</p>
                    <% for(var i = 0 ; i < attach.length ; i++){ if( attach[i].file!='' ){ var fileType=attach[i].type.split('.')[1]; %>
                    <div class="file-tag">
                        <div class="file-info-box">
                            <div class="info-img  col-sm-5 col-xs-5">
                                <img src="<%= swappic[fileType]%>" alt="">
                            </div>
                            <div class="file-info col-sm-15 col-xs-15">
                                <p><span class="file-info-name ellipsis"><%= attach[i].name%><%= attach[i].type%></span></p>
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
        <!-- list -->
        <div class="task-content mt30">
            <div class="task-tag col-lg-2 col-md-3 col-sm-3 col-xs-20">
                提交作业：
            </div>
            <div class="task-action col-lg-15 col-md-15 col-sm-15 col-xs-20 ">
                <div class="textarea-box left">
                    <div id="textarea" class="textarea" contenteditable="true">

                    </div>
                    <div class="place-holder">
                        最多输入2000个字符
                    </div>
                </div>
                <div id="textarea-verify" class="error">

                </div>
                <div id="task-result" class="task-result left">

                </div>
                <script type="template" id="image-box">
                    <div class="image-box result-box left new">
                        <div class="clear-icon"></div>
                        <img src="<%= imgUrl.src_small%>" alt="">
                        <input type="hidden" name="bigImg" value="<%= imgUrl.big%>">
                        <input type="hidden" name="smallImg" value="<%= imgUrl.small%>">
                        <input type="hidden" name="small_width" value="<%= imgUrl.small_width%>">
                        <input type="hidden" name="small_height" value="<%= imgUrl.small_height%>">

                    </div>
                </script>
                <div class="button-box left">
                    <div class="add-image left" id="add-image">
                        <div class="add-image-icon"></div>
                    </div>
                    <div class="add-image-info left">
                        最多上传10张图片
                    </div>
                </div>
                <div class="file-box result-box left hidden-xs">
                    <div class="file-name">
                        <span id="add-file" class="cBlue">+添加附件</span> (<span class="cGray">最多上传3个附件</span>)
                    </div>
                    <div class="file-tag-box" id="file-tag-box">
                        <script type="template" id="file-box">
                            <div class="file-tag new">
                                <div class="file-info-box">
                                    <div class="info-img  col-sm-5 col-xs-5">
                                        <img src="<%= data.thumb%>" alt="">
                                    </div>
                                    <div class="file-info col-sm-15 col-xs-15">
                                        <p><span class="file-info-name ellipsis"><%= data.name%>.<%= data.type%></span></p>
                                        <p class="cGray"><%= data.size%></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="file-clear">
                                    <div class="delete-icon">
                                    </div>
                                </div>
                                <input type="hidden" name="fileType" value=".<%= data.type%>" >
                                <input type="hidden" name="fileName" value="<%= data.name%>">
                                <input type="hidden" name="fileUrl" value="<%= data.fileUrl%>">
                            </div>
                        </script>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="submit-box ">
                <div class="submit" id="submit">
                    <div class="btn">提交作业</div>
                </div>
            </div>
        </div>
        <!-- /list -->

    </div>
</section>
<script type="text/javascript">
(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    };
})(jQuery);

    $(function() {
        $(window).bind('beforeunload',function(){
            return '未保存的内容将会清空，且无法恢复。';
        });
        var taskId = $.getUrlParam('taskId');
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
            $.ajax('/index/task/updateTaskShow/',{
                dataType:'json',
                type:'get',
                data:{
                    pk_task: taskId,
                    act:"student"
                },
                success:function (data) {
                    var result = data.result;
                    if(result.code==200){
                        var data = data.data;
                        var taskInfo = data.info;
                        var taskData = data.data;
                        var attach = data.attach;
                        var thumb = data.thumb;

                        $('#head-title').text(taskInfo.title+' '+taskInfo.name);
                        $('head .head-title').text('提交作业'+taskInfo.title+taskInfo.name);
                        var detailHtml = $('#work-detail').html();
                        $('#pt-work-detail').html(ejs.render(detailHtml,{ taskInfo:taskInfo,taskData:taskData,attach:attach,thumb:thumb,swappic:swappic}));

                        //点击图片放大
                        $('#pt-work-detail').find('#img-list').viewer({
                            url: 'data-original',
                            selector: '.viewer-img'
                        });

                        var lookTaskHeight = $('#pt-work-detail').find('.look-task-content').outerHeight();
                        if(lookTaskHeight > 264) {
                            $('#pt-work-detail').find('.look-task-content').addClass('flow');
                            $('#pt-work-detail').find('.look-task-unfolded').show();
                        }else {
                            $('#pt-work-detail').find('.look-task-content').addClass('show');
                            $('#pt-work-detail').find('.look-task-unfolded').hide();
                        }
                    }else if(result.code == -101){
                        location.href = '/task/publishTask/NotFound';
                    }else if(result.code == -102){
                        location.href = '/task/publishTask/Delete';
                    }
                },
                error:function () {
                    layer.msg('网络君开小差了!');
                }
            })
        }

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
        $('#submit').on('click','.btn',function () {
            var _this=$(this);
            _this.hide();
            _this.after('<div class="gray-btn">正在处理...</div>');
            $('#textarea').blur();
            if($('#textarea').hasClass('error-border')){
                return false;
            }
            if($('#task-result').find('.no-img').length>0){
                layer.msg('文件正在上传，请稍后');
                return false;
            }

            var taskData = { };
            taskData['desc'] = $('#textarea').text();
            taskData['desc'].replace("<img", "");
            taskData['fk_task'] = taskId;

            //获取图片
            var imgUrlArr = [];
            $('#task-result').find('.image-box').each(function () {
                var imgUrl = { };
                imgUrl['big'] = $(this).find('input[name=bigImg]').val();
                imgUrl['small'] = $(this).find('input[name=smallImg]').val();
                imgUrl['small_width'] = $(this).find('input[name=small_width]').val();
                imgUrl['small_height'] = $(this).find('input[name=small_height]').val();
                imgUrlArr.push(imgUrl);
            })
            taskData['taskImages'] = imgUrlArr;

            //获取文件
            var fileName = [],fileType = [],fileUrl = [];
            $('#file-tag-box').find('.file-tag').each(function () {
                fileName.push($(this).find('input[name=fileName]').val());
                fileType.push($(this).find('input[name=fileType]').val());
                fileUrl.push($(this).find('input[name=fileUrl]').val());
            })
            taskData['attachName'] = fileName.join(',');
            taskData['attachType'] = fileType.join(',');
            taskData['attachFile'] = fileUrl.join('&');
            var imgLength = $('#task-result').find('.image-box').size();
            var fileLength = $('#file-tag-box').find('.file-tag ').size();
            if( $.trim(taskData['desc'])  == '' && imgLength == 0 && fileLength == 0 ){
                layer.msg('图片 附件 描述 不能都为空');
                return false;
            }

            $.ajax('/index/task/commitTask/',{
                dataType:'json',
                type:'post',
                data:taskData,
                success:function (data) {
                    if(data.code == 200){
                        if (data.data.code==0){
                            integralMedalLayer(data.data.data.add_point+'积分',function(){
                                $(window).unbind('beforeunload');
                                $('#submit').find('.gray-btn').remove();
                                _this.show();
                                if(data.data.data.up_type == 1){
                                    $("body").GrowthLayer({
                                        types:"smallgrowth",
                                        space:2000,
                                        auto:true,
                                        growth:data.data.data.fk_level,
                                        score:data.data.data.score
                                    });
                                    setTimeout(function(){
                                        location.href= '/task.commitTask.studentTaskListShow';
                                    },2000);
                                }else if(data.data.data.up_type == 2){
                                    $("body").GrowthLayer({
                                        types:"biggrowth",
                                        space:1000,
                                        auto:true,
                                        growth:data.data.data.fk_level,
                                        score:data.data.data.score
                                    });
                                    setTimeout(function(){
                                        location.href= '/task.commitTask.studentTaskListShow';
                                    },2000)
                                }else{
                                    location.href= '/task.commitTask.studentTaskListShow';
                                }
                            });
                        }else{
                            $(window).unbind('beforeunload');
                            $('#submit').find('.gray-btn').remove();
                            _this.show();
                            location.href= '/index.task.studentTaskListShow';
                        }
                    }else{
                        layer.msg(data.msg);
                        $('#submit').find('.gray-btn').remove();
                    }
                },
                error:function () {
                    layer.msg('提交失败~');
                    $('#submit').find('.gray-btn').remove();
                }
            })

        })
    })
</script>
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
