<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 布置作业 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<title class="head-title">布置作业</title>
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
</head>
<body>
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.task.common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.publish.task.js'); ?>"></script>
<section>
    <div class="container  main-center">
        <div class="ed-title">
            <div class="tab-button hidden-xs ">布置作业</div>
            <div id="head-title" class="title col-lg-15 col-md-15 col-sm-15 col-xs-15">
            </div>
            <div class="delete-icon hidden" id="delete-task">
            </div>
        </div>
        <div class="task-content mt30">
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-20">
                作业内容：
            </div>
            <div class="task-action col-lg-15 col-md-15 col-sm-15 col-xs-20">
                <div class="textarea-box left">
                    <div id="textarea" class="textarea" contenteditable="true" data-max="2000">

                    </div>
                    <div class="place-holder" id="place-holder">
                        最多输入2000个字符
                    </div>
                </div>
                <div id="task-result" class="task-result left">

                </div>
                <script type="template" id="image-box">
                    <% var type = type ? type : '' ; if( type == 'update') { imgUrl.forEach(function(item){ %>
                    <div class="image-box result-box left <%= className%>">
                        <div class="clear-icon"></div>
                        <img src="<%= item.src_mall%>" alt="">
                        <input type="hidden" name="bigImg" value="<%= item.thumb_big%>">
                        <input type="hidden" name="smallImg" value="<%= item.thumb_small%>">
                        <input type="hidden" name="ImgId" value="<%= item.pk_thumb%>">
                    </div>
                    <% })  } else {  %>
                    <div class="image-box result-box left <%= className%>">
                        <div class="clear-icon"></div>
                        <img src="<%= imgUrl.src_small%>" alt="">
                        <input type="hidden" name="bigImg" value="<%= imgUrl.big%>">
                        <input type="hidden" name="smallImg" value="<%= imgUrl.small%>">
                    </div>
                    <% } %>
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
                <div class="file-box result-box left hidden-xs">
                    <div class="file-name">
                        <span id="add-file" class="cBlue">+添加附件</span> (<span class="cGray">最多上传3个附件</span>)
                    </div>
                    <div class="file-tag-box" id="file-tag-box">
                        <script type="template" id="file-box">
                            <% var type = type ? type : '' ;if(type == 'update') { data.forEach(function(item){ if(item.file!=''){ var fileType=item.type.split('.')[1]; %>
                            <div class="file-tag <%= className%>">
                                <div class="file-info-box">
                                    <div class="info-img  col-sm-5 col-xs-5">
                                        <img src=" <%= swappic[fileType]%>" alt="">
                                    </div>
                                    <div class="file-info col-sm-15 col-xs-15">
                                        <p><span class="file-info-name"><%= item.name%><%= item.type%></span></p>
                                        <p class="cGray"><%= data.size%></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="file-clear">
                                    <div class="delete-icon">
                                    </div>
                                </div>
                                <input type="hidden" name="fileType" value="<%= item.type%>" >
                                <input type="hidden" name="fileName" value="<%= item.name%>">
                                <input type="hidden" name="fileUrl" value="<%= item.file%>">
                                <input type="hidden" name="fileId" value="<%= item.pk_attach%>">
                            </div>
                            <% } })  } else { %>
                            <div class="file-tag <%= className%>">
                                <div class="file-info-box">
                                    <div class="info-img  col-sm-5 col-xs-5">
                                        <img src="<%= data.thumb%>" alt="">
                                    </div>
                                    <div class="file-info col-sm-15 col-xs-15">
                                        <p><span class="file-info-name"><%= data.name%>.<%= data.type%></span></p>
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
                            <% }%>
                        </script>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="task-content mt30">
            <div class="task-tag col-lg-3 col-md-3 col-sm-3 col-xs-20">
                考察知识点：
            </div>
            <div class="task-action col-lg-15 col-md-15 col-sm-15 col-xs-20">
                <div class="dropdown" >
                    <div class="dropdown-input label-dropdown-input" >
                        <div id="label-content" class="left">
                        </div>
                        <input id="dropdown-input" type="text" data-input=""  class="course-name course-name-ipt" value="" placeholder="添加标签" name="">
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="dropdown-tips" id="dropdown-tips">
                    <div  class="tips pl20">
                        <span class="tips-icon"></span>
                        <div class="tips-text">至少选择一项，最多选择五项</div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="task-content mt30">
                <div class="task-time col-lg-8 col-md-8 col-sm-20 col-xs-20 mb10">
                    <div class="task-tag col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        发布时间：
                    </div>
                    <div class="task-action col-lg-13 col-md-13 col-sm-13 col-xs-13 ">
                        <input type="text" class="start-time" id="start-time" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="task-time  col-lg-8 col-md-8 col-sm-20 col-xs-20">
                    <div class="task-tag col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        截止日期：
                    </div>
                    <div class="task-action col-lg-13 col-md-13 col-sm-13 col-xs-13  ">
                        <input type="text" class="end-time" id="end-time" >
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
        </div>
        <div class="submit-box ">
            <div class="submit">
                <div id="submit" class="btn">保存</div>
                <a href="/task/publishTask/teacherTaskList/" class="blue-link">取消返回上级</a>
            </div>
        </div>
    </div>
</section>
<script type="template" id="error-info">
    <div class="tips">
        <span class="tips-icon"></span>
        <span class="tips-text"><%= message%></span>
    </div>
</script>
<script>
    // copy 代码 需整理  生成标签
    (function () {
        $(window).bind('beforeunload',function(){
            return '未保存的内容将会清空，且无法恢复。';
        });
        var dropdown = $('.dropdown');
        var dropdownInput = dropdown.find('.dropdown-input');
        var inputValue = [];
        function addVal(str) {
            inputValue.push(str);
            return inputValue.join(',');
        }
        function deleteVal(totalStr,str) {
            var arr = totalStr.split(',');
            var vrr = [];
            for(var i=0;i<arr.length;i++){
                if(arr[i]!=str){
                    vrr.push(arr[i]);
                }
            }
            return vrr.join(',');
        }
        //回车键生成标签
        dropdown.on('keydown', '.course-name-ipt', function(e) {
            var e = e || event,
                    keycode = e.which || e.keyCode,addHtml;
            if (keycode==32 || keycode==13 || (event.shiftKey && event.keyCode==13)) {
                var tagName = $.trim($(this).val());
                if(tagName==''){
                    layer.msg('标签不能为空');
                    return false;
                }else {
                    if(maxLengthKeyUp(tagName)){
                        addHtml = '<div class="dropdown-show-tab p0 c-fl" data-id="">'
                        addHtml += '<div class="left-side"></div>'
                        addHtml += '<div class="tab-delete"></div>'
                        addHtml += tagName
                        addHtml += '</div>';

                        var labelContentLength = $('#label-content').find('.dropdown-show-tab').length,IptTagName;

                        if(labelContentLength < 5 ){
                            $('#label-content').find('.dropdown-show-tab').each(function() {
                                IptTagName = $.trim($(this).text());
                            });
                            if( IptTagName == tagName) {
                                layer.msg('标签重复');
                            }else {
                                $('#label-content').append(addHtml);
                                $(this).attr('data-input',addVal(tagName));
                            }
                            $('.course-name-ipt').val('');
                        }
                        if(labelContentLength == 5 ){
                            layer.msg('最多添加5个标签');
                            $('.course-name-ipt').val('');
                            $('.course-name-ipt').hide();
                        }
                    }else{
                        layer.msg('标签字数超过10个汉字或者20个英文字符,请重新输入!');
                    }
                }
                if(keycode==32){
                    event.preventDefault();
                    $(this).val('');
                }
            }
        });

        dropdownInput.on('click',function () {
            if (event.stopPropagation) {
                event.stopPropagation();
            }else if (window.event) {
                window.event.cancelBubble = true;
            }
            $(this).parents(".dropdown").find('.dropdown-box').attr('data-show','true').css('display','block');
            $(this).find('#dropdown-input').focus();
        });
        $(document).on('click',function (event) {
            if(event.target.className.indexOf('dropdown-tab')== -1){
                $('.dropdown-box[data-show=true]').css('display','none');
            }
        });
        //删除标签
        dropdownInput.on('click','.tab-delete',function () {
            var _self = $(this);
            var tabIndex = $(this).parents('.dropdown-show-tab').attr('data-id');
            if(tabIndex){
                $.ajax('/task/publishTask/DelTag',{
                    dataType:'json',
                    type:'post',
                    data:{
                        pk_tag: tabIndex
                    },
                    success:function (data) {
                        if(data.result.code==200){
                            var thisIptDelTagName = $.trim(_self.parents(".dropdown-show-tab").text());
                            $('.course-name-ipt').show();
                            _self.parents(".dropdown-show-tab").remove();
                            $('.course-name-ipt').attr('data-input',deleteVal($('.course-name-ipt').attr('data-input'),thisIptDelTagName));
                        }else{
                            layer.msg('删除失败');
                        }
                    },
                    error:function () {
                        layer.msg('删除失败');
                    }
                })
            }else{
                var thisIptDelTagName = $.trim($(this).parents(".dropdown-show-tab").text());
                $('.course-name-ipt').show();
                $(this).parents(".dropdown-show-tab").remove();
                $('.course-name-ipt').attr('data-input',deleteVal($('.course-name-ipt').attr('data-input'),thisIptDelTagName));
            }
        });

    })();
</script>

<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
