/**
 * Created by Administrator on 2017/1/12.
 */
$(function(){
    flag=true;
    var finishBtn=$("#finish-create");
    var sortSave=$("#sort-save");
    var classItem=$(".class-item");
    var operationWrap=$("#operation-wrap");
    /*拖拽排序*/
    $("#rank-class").click(function(){
        sortSave.show();
        finishBtn.hide();
        operationWrap.hide();
        classItem.css('cursor',"pointer").find('a').hide(); //编辑入口隐藏
        $( "#class-lists" ).sortable({
            stop:function(){
                $('.class-item').each(function(index){
                    $(this).find('span.class-num').html(index+1);
                });
            }
        });
        $( "#class-lists" ).disableSelection();
    })
    
    $("#sort-plan").click(function(){
        var This=$(this);
        This.prop("disabled","disabled");
        This.addClass('load-btn');
        var planno = [];
        $('.class-item').each(function(index){
            planno.push($(this).find('span.class-num').attr('planid'));
        });
        $.post("/org/planAjax/sortplan", { courseId:courseId,classId:classId,orderno:planno }, function(r){
            if(r.code == 0){
                layer.msg(r.msg);
                location.reload();
            }else{
                This.removeAttr("disabled");
                This.removeClass('load-btn');
                layer.msg(r.msg);
                return false;
            }
        },'json');
    });

    //班级排课--保存
    $("#save-btn").click(function(){
        if(!flag){
            return false;
        }
        flag=false;
        $("form.grade-con-edit").validate('submitValidate')
        var This=$(this);
        This.addClass('load-btn');
        var className  = $("input[name='className']").val();    //班级名称
        var studentNum = $("input[name='studentNum']").val();   //学生数量
        var teacherId  = $("#teacherId").val();                 //教师id
        var province   = $("#add-pro").val();                   //省
        var city       = $("#add-city").val();                  //市
        var area       = $("#add-area").val();                  //区
        var address    = $("#add-full").val();                  //详细地址
        var type = $(this).attr("data-type");

        $.post("/org/planAjax/setClass", { courseId:courseId,classId:classId,className:className,studentNum:studentNum,teacherId:teacherId,province:province,city:city,area:area,address:address }, function(r){
            if(r.code == 0){
                if(type == 1){
                    window.location = "/org.course.editPlan."+courseId+"."+r.data.classId;
                }else{
                    location.reload();
                }
            }else{
                flag=true;
                // This.removeAttr("disabled");
                This.removeClass('load-btn');
                layer.msg(r.msg);
                return false;
            }
        },'json');
    });
    //时间插件初始化
    $(".datetime").datetimepicker({
        format: "Y-m-d H:i",
        timepicker:true,
        step:5
        //minDate:jQuery('.datetime').val(),
    });
    function addDateTime(){
        $(".datetime").datetimepicker({
            format: "Y-m-d H:i",
            timepicker:true,
            step:5
            //minDate:jQuery('.datetime').val(),
        });
        //日历图标部署时间插件
        $(".datertime-icon").click(function(){
            $(this).siblings('.datetime').datetimepicker('show');
        });
        $(".xdsoft_datetimepicker").css("z-index", '19891016');
    }
    //日历图标部署时间插件
    $(".datertime-icon").click(function(){
        $(this).siblings('.datetime').datetimepicker('show');
    });
    //添加单个课时--弹框
    $("#add-single-class").click(function(){
        $(".xdsoft_datetimepicker").css("z-index", '33333333');
        layer.open({
            type:1,
            title:['单个添加课时'],
            closeBtn:1,
            area:['765px','350px'],
            shadeClose:true,
            content:$('#add-single-course'),
            success: function(layero, index){
                var thisElem=layero.find('.layui-layer-content');
                thisElem.find('input[name="planName"]').val('');
                thisElem.find("#lecturerId option:first").prop('selected',1);
                thisElem.find('#start-one-time').val('');
                thisElem.find('#end-one-time').val('');
                thisElem.find('span[livetype]').attr('livetype','0').html('无试看');
                thisElem.find('span[videotype]').attr('videotime','0').attr('videotype','0').html('无试看');
                layero.find('.gray-button').click(function(){
                    layer.close(index);
                })
            }
        });
    });
    //单个课时添加--确定
    function addSinglePlan(){
        $("#add-plan-btn,#add-plan-btn-e").click(function(){
            var This=$(this);
            var thisElem=$(this).parent().parent();
            var planName  = thisElem.find("input[name='planName']").val();
            var teacherId = thisElem.find("#lecturerId").val();
            var startTime = thisElem.find("#start-one-time").val();
            var endTime   = thisElem.find("#end-one-time").val();
            var originalStartTime = thisElem.find("#start-one-time").attr('original');
            var originalEndTime   = thisElem.find("#end-one-time").attr('original');
            var orderNo   = thisElem.find("#plan-num").attr('orderNo');
            var planId    = thisElem.find('input[name="planId"]').val();
            var liveType  = thisElem.find("cite span[livetype]").attr('livetype');
            var videoTime = thisElem.find("cite span[videotime]").attr('videotime');
            var videoType = thisElem.find("cite span[videotype]").attr('videotype');
            // if(!startTime || !endTime){
            //     var timeEle=$("#timeVal");
            //     timeEle.parents("#add-single-time").addClass('error');
            //     timeEle.show();
            //     timeEle.find('span.tips-text').html('请选择正确时间');
            //     return false
            // }else{
            //     var timeEle=$("#timeVal");
            //     timeEle.parents("#add-single-time").removeClass('error');
            //     timeEle.hide();
            //     timeEle.find('span.tips-text').html('');
            // }
            This.prop("disabled","disabled");
            This.addClass('load-btn');
            $.post("/org/planAjax/SetPlan", {
                    courseId:courseId,
                    classId:classId,
                    planId:planId,
                    planName:planName,
                    teacherId:teacherId,
                    startTime:startTime,
                    endTime:endTime,
                    orderNo:orderNo,
                    liveType:liveType,
                    videoTime:videoTime,
                    videoType:videoType,
                    originalStartTime:originalStartTime,
                    originalEndTime:originalEndTime
                /*直播、视频状态码*/
                }, function(r){
                if(r.code == 0){
                    location.reload();
                }else{
                    This.removeAttr("disabled");
                    This.removeClass('load-btn');
                    layer.msg(r.msg);
                    return false;
                }
            },'json');
        });
    }
    addSinglePlan();

    //批量添加--弹框
    $("#add-batch-class").click(function(){
        $(".xdsoft_datetimepicker").css("z-index", '33333333');
        layer.open({
            type:1,
            title:['批量添加课时'],
            closeBtn:1,
            area:['765px','550px'],
            shadeClose:true,
            content:$('#add-more-course'),
            success: function(layero, index){
                var thisElem=layero.find('.layui-layer-content');
                thisElem.find('#plan-add-desc').val('');
                thisElem.find("#more-teacher option:first").prop('selected',1);
                thisElem.find(".datetime").val('');
                thisElem.find(".cite-text").html('请选择');
                thisElem.find("#select-more-minute").val('');
                thisElem.find("#select-more-time").hide();
                thisElem.find("#select-addTime").html('').hide();
                layero.find('.gray-button').click(function(){
                    layer.close(index);
                })
            }
        });
    });
    //批量添加--确认
    $("#quicksetCourse-btn").click(function(){
        var This=$(this);
        This.prop("disabled","disabled");
        This.addClass('load-btn');
        var plannames = $.trim($("#plan-add-desc").val()).split("\n");
        var teacherId = $("#more-teacher").val();
        var startTime = $("#add-more-startTime").find(".startime-plan-course").val();
        var weekType  = $("#weekType").find(".cite-text").attr('data-id');
        if(weekType == 3){
            var myTimes = $("#select-more-time").attr("mytimes");
        }
        var longType = $("#select-long-type").find(".cite-text").attr('data-id');
        if(longType == 7){
            var myLongTime = $("#select-more-minute").val();
        }

        $.post("/org/planAjax/QuicksetPlan", { courseId:courseId,classId:classId,data:plannames,teacherId:teacherId,startTime:startTime,weekType:weekType,myTimes:myTimes,longType:longType,myLongTime:myLongTime },function(r){
            if(r.code == 0){
                location.reload();
            }else{
                This.removeAttr("disabled");
                This.removeClass('load-btn');
                layer.msg(r.msg);
                return false;
            }
        },'json')

    });
    /*
     *循环方式选择
     * data-id：3 自定义状态 显示选取时间图标，自定义时间
     * 章节数量对应时间数量
     */
    $('#weekType').on('click', 'a', function() {
        $(this).parents('.divselect').find('.cite-text').text($.trim($(this).text()));
        $(this).parents('.divselect').find('.cite-text').attr('data-id', $(this).attr('data-id'));
        if($(this).attr('data-id') == 3) {
            $('#select-more-time').show();
            $('#select-addTime').show().css('padding-left','184px');
        }else {
            $('#select-more-time').hide();
            $('#select-addTime').hide();
            $('#select-addTime').html(''); //清空选中的时间
        }
    });
    $("#select-more-time").datetimepicker({
        timepicker:false,
        format:'Y-m-d H:i',
        step:5,
        // autoclose:true,
        minDate:jQuery('#select-more-time').val(),
        onSelectDate:function(ct,$i){
            var cts = ct.dateFormat('n月j日');
            var cts = ct.dateFormat('Y-m-d');
            orderName = $('#plan-add-desc').val().split("\n");
            for(var i = 0 ; i<orderName.length; i++) {
                if(orderName[i] == "" || typeof(orderName[i]) == "undefined") {
                    orderName.splice(i, 1);
                    i = i - 1;
                }
            }
            var timeset = $("#select-addTime .dropdown-show-tab").length;
            var myTime = [];
            if($('#plan-add-desc').val() == '') {
                layer.msg('章节内容为空');
                $('#plan-add-desc').focus();//章节名获取焦点
                $('#select-more-time').datetimepicker('hide');//时间插件隐藏
                return false;
            }
            if(timeset < i){
                $("<div class='dropdown-show-tab col-xs-5' onclick='deltDate(this)'><div class='tab-delete'></div><div class='left-side'></div>"+cts+"</div>").appendTo("#select-addTime");
                $('#select-addTime').find('.dropdown-show-tab').each(function() {
                    myTime.push($(this).text());
                });
                $('#select-more-time').attr('myTimes', myTime.join(','));
            }
        }
    });
    /*
     * 课程时长选择
     * data-id：7  自定义状态
     * 课程时长输入选框展示
     * */
    $('#select-long-type').on('click', 'a', function() {
        $(this).parents('.divselect').find('.cite-text').text($.trim($(this).text()));
        $(this).parents('.divselect').find('.cite-text').attr('data-id', $(this).attr('data-id'));
        if($(this).attr('data-id') == 7) {
            $('#select-more-minute').show();
        }else {
            $('#select-more-minute').hide();
            $('#select-more-minute').val('');
        }
    });
    //编辑课时
    $(".editButton").click(function() {
        var planId = $(this).attr('data-plan');
        $.ajax({
            type: 'post',
            url: '/org/planAjax/planinfo',
            data: {planId: planId,courseId:courseId},
            dataType: 'json',
            success: function (r) {
                if (r.code == 1) {
                    var data = r.data;
                    var trList = $("#add-single-course-ej").html();
                    var html = ejs.render(trList, {data: r.data});
                    $("#add-single-course-e").html(html);
                    layer.open({
                        type: 1,
                        title: ['单个添加课时'],
                        closeBtn: 1,
                        area: ['765px', '400px'],
                        shadeClose: true,
                        content: $('#add-single-course-e'),
                        success: function (layero, index) {
                            addDateTime();
                            addSinglePlan();
                            layero.find('.gray-button').click(function () {
                                layer.close(index);
                            })
                        }
                    });
                }
            }
        })
    })
    //删除课时
    $(".del-btn").click(function(){
        var planId = $(this).attr('data-plan');
        layer.confirm(
        '确定删除课时？',
        {title:'提示'},
        function(index){
            $.post("/org/planAjax/delplan", { courseId:courseId,classId:classId,planId:planId }, function(r){
                if(r.code == 0){
                    layer.close(index);
                    layer.msg(r.msg);
                    location.reload();
                }else{
                    layer.msg(r.msg);
                    return false;
                }
            },'json');
            }
        )
    });
    
    $("#edit-btn").click(function(){
    if(level0 != 0){
        $.post("/user/course/listRegion."+level0,{  }, function(items){
            if(items){
                $("#add-city").css("display","block");
                for(i in items){
                    if(level1 == items[i].region_id){
                        $("#add-city").append("<option value='"+items[i].region_id+"' selected='selected'>"+items[i].name+"</option>");
                    }else{
                        $("#add-city").append("<option value='"+items[i].region_id+"'>"+items[i].name+"</option>");
                    }
                }
            }
        },'json');
    }
    if(level1 != 0){
         $.post("/user/course/listRegion."+level1,{  }, function(items){
            if(items){
                $("#add-area").css("display","block");
                for(i in items){
                    if(level2 == items[i].region_id){
                        $("#add-area").append("<option value='"+items[i].region_id+"' selected='selected'>"+items[i].name+"</option>");
                    }else{
                        $("#add-area").append("<option value='"+items[i].region_id+"'>"+items[i].name+"</option>");
                    }
                }
            }
        },'json');
    }
    });
    
    $("#add-pro").change(function(){
        var cityId = $(this).val();
        $("#add-city").find('option').remove();
        $.get("/user/course/listRegion."+cityId, {  }, function(items){
            if(items){
                $("#add-city").css("display","block");
                for(i in items){
                    $("#add-city").append("<option value='"+items[i].region_id+"'>"+items[i].name+"</option>");
                }
            }          
        },'json');
    });

    $("#add-city").change(function(){
        var areaId = $(this).val();
        $("#add-area").find('option').remove();
        $.get("/user/course/listRegion."+areaId, {  }, function(items){
            if(items){
                $("#add-area").css("display","block");
                for(i in items){
                    $("#add-area").append("<option value='"+items[i].region_id+"'>"+items[i].name+"</option>");
                }
            }
        },'json');
    });

    $("#cancel-btn").click(function(){
        if(classId == 0){
            window.location = "/org.course.plan."+courseId;
            return;
        }
        $("#arrangement-wrap-edit").hide();
        $("#arrangement-wrap-info").show();
        $(".class-setting-wrap").show();
        $("#finish-create").show();
        $("#finish-class").hide();
    });
    $("#edit-btn").click(function(){
        var thisElem=$("#arrangement-wrap-info");
        var thisElemEdit=$("#arrangement-wrap-edit");
        var className=thisElem.find(".className").html();
        var studentNum=thisElem.find(".studentNum").html();
        var teacherInfo=thisElem.find(".teacherInfo").attr("teacherid");
        var addFull=thisElem.find(".add-full").html();
        thisElemEdit.find("#className").val(className);
        thisElemEdit.find("#studentNum").val(studentNum);
        thisElemEdit.find("#teacherId").val(teacherInfo);
        thisElemEdit.find("#add-full").val(addFull);
        $("#arrangement-wrap-edit").show();
        $("#arrangement-wrap-info").hide();
        $(".class-setting-wrap").hide();
        $("#finish-create").hide();
        $("#finish-class").show();
    });

})
//删除多个章节日期
function deltDate(obj) {
    var myTime = [];
    objParents = $(obj);
    objParents.remove();
    $('#select-addTime').find('.dropdown-show-tab').each(function() {
        myTime.push($(this).text());
    });
    $('#select-more-time').attr('myTimes', myTime.join(','));
}
function sidedownSel(e,v,obj){
    var obj =$(obj);
    var citeObj= obj.find('cite');
    var dlObj =obj.find('dl');
    var spanObj = citeObj.find('span.cite-text');
    dlObj.find('dd a').click(function(){
        if(v == 1){ //v类型  1:直播 2：视频
            var livetype=$(this).attr('livetype')
            spanObj.attr('livetype',livetype);
        } else if (v == 2){
            var videotime=$(this).attr('videotime')
            var videotype=$(this).attr('videotype')
            spanObj.attr('videotime',videotime);
            spanObj.attr('videotype',videotype);
        }
    });
}
