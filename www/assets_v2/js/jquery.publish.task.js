$(function () {
    var courseId = $.getUrlParam('courseId');
    var classId = $.getUrlParam('classId');
    var type = $.getUrlParam('type');
    var taskId = $.getUrlParam('taskId');
    //文件上传
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

    if(type=='create'){
        $.ajax('/task/publishTask/classCourseInfo/',{
            dataType:'json',
            type:'post',
            data:{
                courseId:courseId,
                classId:classId
            },
            success:function (data) {
                var result = data.result;
                if(result.code==200){
                    var data = result.data;
                    $('head .head-title').text('布置作业'+data.title + data.name);
                    $('#head-title').text(data.title +' '+ data.name);
                }
            }
        })
    }else if(type=='update'){
        $('#delete-task').removeClass('hidden');
        $('#delete-task').on('click',function () {
            layer.open({
                title: '删除作业',
                btn:['确定','取消'],
                content:'删除后,此份作业的相关数据都将消失,<br/> 是否删除？',
                shadeClose: true,
                yes:function (index,layero) {
                    $.ajax('/task/publishTask/deleteTask',{
                        dataType:'json',
                        type:'post',
                        data:{
                            pk_task:taskId
                        },
                        success:function (data) {
                             if(data.result.code==200){
                                 location.href='/task/publishTask/teacherTaskList';
                             }
                        }
                    })
                }
            });
        })
        $.ajax('/task/publishTask/updateTaskShow/',{
            dataType:'json',
            type:'get',
            data:{
                pk_task:taskId
            },
            success:function (data) {
                var result = data.result;
                if(result.code==200){
                    var data = data.data;
                    var taskInfo = data.info;
                    var taskData = data.data;
                    var attach = data.attach;
                    var thumb = data.thumb;
                    //初始化tag
                    if(toType(data.tag)=='object'){
                        var tags = [ ];
                        for(var i in data.tag){
                            tags.push(data.tag[i]);
                        }
                    }else if(toType(data.tag)=='array'){
                        var tags = data.tag;
                    }
                    $('head .head-title').text('布置作业'+taskInfo.title + taskInfo.name);
                    $('#head-title').text(taskInfo.title +' '+ taskInfo.name);
                    $('#textarea').text(taskData.desc);
                    var imgHtml = $('#image-box').html();
                    $('#task-result').append(ejs.render(imgHtml,{ imgUrl:thumb,type:type,className:''}));
                    var fileHtml = $('#file-box').html();
                    $('#file-tag-box').append(ejs.render(fileHtml,{ data:attach,type:type,swappic:swappic,className:''}));
                    if(tags.length>0){
                        var tagHtml = '';
                        for(var i = 0 ; i < tags.length ; i++){
                            tagHtml += '<div class="dropdown-show-tab p0 c-fl " data-id="'+tags[i].pk_tag+'" ><div class="left-side"></div><div class="tab-delete"></div>'+tags[i].name+'</div>';
                        }
                        $('#label-content').html(tagHtml);
                    }

                    $('#start-time').val(taskData.start_time);
                    $('#end-time').val(taskData.end_time);
                }
            }
        })

    }

    //初始化弹窗样式
    layer.config({
        extend: ['skin/frame/style.css'],
        skin: 'layer-ext-frame'
    });
    //初始化日期和时间
    var startSecond = new Date().getTime() + 5*60*1000;
    var endSecond = startSecond + 3*24*3600000;
    function getDate(times) {
        var nowTime = new Date(times);
        var year = nowTime.getFullYear();
        var month = nowTime.getMonth()+1;
        var day = nowTime.getDate();
        var hour = nowTime.getHours();
        var min = nowTime.getMinutes();
        if(month<10){
            month = '0'+month;
        }
        if(day<10){
            day = '0'+day;
        }
        if(hour<10){
            hour = '0' + hour;
        }
        if(min<10){
            min = '0' + min ;
        }
        return year+'-'+month+'-'+day+' '+hour+':'+min;
    }
    $('#start-time').val(getDate(startSecond));
    $('#end-time').val(getDate(endSecond));
    jQuery('#start-time').datetimepicker({
        format:'Y-m-d H:i',
        formatDate:'Y-m-d',
        formatTime:'H:i',
        step:15,
        validateOnBlur:true,
        defaultSelect:true,
        minDate:new Date(),
        onChangeDateTime:function (ct,$input) {
            var endTime = ct ? ct.getTime()+3*24*3600*1000 : startSecond;
            jQuery('#end-time').val(getDate(endTime));
            $('#end-time').datetimepicker('setOptions',{minDate:new Date(endTime)});
        },
        timepicker:true
    });
    jQuery('#end-time').datetimepicker({
        format:'Y-m-d H:i',
        step:15,
        validateOnBlur:true,
        defaultSelect:true,
        minDate:new Date(),
        onShow:function( ct ,$input){
            if(jQuery('#start-time').val()==''){
                layer.msg('请先填写发布时间');
                return false;
            }
        },
        onChangeDateTime:function (ct,$input) {
            var startTime = new Date($('#start-time').val()).getTime();
            if(startTime >= ct.getTime()){
                layer.msg('截止时间须晚于发布时间');
                jQuery('#end-time').datetimepicker('reset');
                return false;
            }
        },
        timepicker:true
    });


    //标签判断
    $('#dropdown-input').on({
        focus:function () {
            $(this).parent().removeClass('error-border');
        },
        blur:function () {
            // var length = $('#label-content').find('.dropdown-show-tab').size();
            // if(length==0){
            //     $(this).parent().addClass('error-border');
            //     var message = '最少输入1个标签';
            //     var errorHtml = $('#error-info').html();
            //     $('#dropdown-tips').addClass('error').html(ejs.render(errorHtml,{ message:message}));
            // }else{
            //     $(this).parent().removeClass('error-border');
            //     var message = '至少选择一项，最多选择五项';
            //     var errorHtml = $('#error-info').html();
            //     $('#dropdown-tips').removeClass('error').html(ejs.render(errorHtml,{ message:message}));
            // }
        }

    })

    $('#submit').on('click','.btn',function () {
        var _this=$(this);
        _this.hide();
        _this.after('<div class="gray-btn">正在处理...</div>');
        $(window).unbind('beforeunload');
        $('#textarea').blur();
        if($('#textarea').hasClass('error-border')){
            return false;
        }
        // $('#dropdown-input').blur();
        // if($('#dropdown-input').parent().hasClass('error-border')){
        //     return false;
        // }
        if($('#task-result').find('.no-img').length>0){
            layer.msg('文件正在上传，请稍后');
            return false;
        }
        var taskData = { };
        $('#textarea').find('img').each(function(){
           $(this).remove();
        })
        taskData['desc'] = $('#textarea').text();
        taskData['start_time'] = $('#start-time').val();
        taskData['end_time'] = $('#end-time').val();

        //获取标签
        var labelTag = [];
        $('#label-content').find('.dropdown-show-tab').each(function () {
            labelTag.push($(this).text());
        })
        taskData['tags'] = labelTag.join(',');

        //获取图片
        var imgUrlArr = [];
        $('#task-result').find('.image-box').each(function () {
            if($(this).hasClass('new')){
                var imgUrl = { };
                imgUrl['big'] = $(this).find('input[name=bigImg]').val();
                imgUrl['small'] = $(this).find('input[name=smallImg]').val();
                imgUrl['small_width'] = $(this).find('img').attr('data-width');
                imgUrl['small_height'] = $(this).find('img').attr('data-height');
                imgUrlArr.push(imgUrl);
            }
        })
        taskData['taskImages'] = imgUrlArr;

        //获取文件
        var fileName = [],fileType = [],fileUrl = [];
        $('#file-tag-box').find('.file-tag').each(function () {
            if($(this).hasClass('new')){
                fileName.push($(this).find('input[name=fileName]').val());
                fileType.push($(this).find('input[name=fileType]').val());
                fileUrl.push($(this).find('input[name=fileUrl]').val());
            }
        })
        taskData['attachName'] = fileName.join(',');
        taskData['attachType'] = fileType.join(',');
        taskData['attachFile'] = fileUrl.join('&');

        var imgLength = $('#task-result').find('.image-box').size();
        var fileLength = $('#file-tag-box').find('.file-tag ').size();
        if( $.trim(taskData['desc'])  == '' && imgLength == 0 && fileLength == 0 ){
             layer.msg('图片 附件 文字 不能都为空');
                return false;
        }
        if(type == 'create'){
            taskData['fk_course'] = courseId;
            taskData['fk_class'] = classId;

            $.ajax('/task/publishTask/publishTask',{
                dataType:'json',
                type:'post',
                data:taskData,
                success:function (data) {
                    if(data.code == 200){
                        $(window).unbind('beforeunload');
                        $('#submit').find('.gray-btn').remove();
                        _this.show();
                         location.href = '/task/publishTask/teacherTaskList';
                    }else{
                        layer.msg('提交失败!');
                        $('#submit').find('.gray-btn').remove();
                        _this.show();
                    }
                },
                error:function () {
                    layer.msg('提交失败~');
                    $('#submit').find('.gray-btn').remove();
                    _this.show();
                    return false;
                }
            })
        }else if(type == 'update'){
            taskData['pk_task'] = taskId;
            $.ajax('/task/publishTask/updateTask',{
                dataType:'json',
                type:'post',
                data:taskData,
                success:function (data) {
                    if(data.code == 200){
                        layer.msg('修改成功');
                        $(window).unbind('beforeunload');
                         location.href = '/task/publishTask/teacherTaskList';
                    }else if(data.code == -101){
                        $(window).unbind('beforeunload');
                        location.href = '/task/publishTask/NotFound';
                    }else if(data.code == -102){
                        $(window).unbind('beforeunload');
                        location.href = '/task/publishTask/Delete';
                    }else{
                        layer.msg(data.msg);
                        $('#submit').find('.gray-btn').remove();
                        _this.show();
                        return false;
                    }
                },
                error:function () {
                    layer.msg('提交失败~');
                    return false;
                }
            })
        }
    });

})
