/*
*Date:07-08-2016
*Name(changyuan.Liu)
*Jquery.course.plan.js
*主要为章节添加,章节修改后追加代码文件
*/
function planInfoDataTime() {
  $('.datestart').datetimepicker({
        format: "Y-m-d H:i",
        timepicker:true,
        step:5,
        minDate:jQuery('.datestart').val(),
    });
}
//创建新章节
function addPlanInfoList() {
    var articleList = $('#article-list');
    var type = $('#grobal-course').attr('type');
    var zHtml, lHtml, xHtml;
    var lTxt = articleList.find('.article-txt').length;
    var l = articleList.find('.planList').length + 1;
	zHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0">'
	zHtml+='<span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
	zHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
	zHtml+='<a href="javascript:;" class="c-fr mt5 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
	zHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl ml22">讲师：</div>'
	zHtml+='<div class="divselect divselect-32 teacher-select col-md-14 p0"><cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite>'
	zHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0"><div class="c-fl mt5">时间：</div>'
	zHtml+='<input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
	zHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">至&nbsp;</div><input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd>'
	zHtml+='<dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">直播：</div><div class="divselect divselect-32 live-select col-md-15 p0" livetype="0">'
	zHtml+='<cite><span class="cite-icon"></span><span class="cite-text livetype-text" livetype="0">无试看</span></cite><dl>'
	zHtml+='<dd><a href="javascript:;" livetype="0">无试看</a></dd><dd><a href="javascript:;" livetype="1">试看整节</a></dd></dl></div></dd>'
	zHtml+='<dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">视频：</div><div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
	zHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotype="" videotime="">请选择</span></cite><dl>'
	zHtml+='<dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd><dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd>'
	zHtml+='<dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd><dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd>'
	zHtml+='<dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd><dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a></dd></dl></div></dd></dl></li>';

	lHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0">'
	lHtml+='<span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
	lHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
	lHtml+='<a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a>'
	lHtml+='</div><dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0">'
	lHtml+='<div class="c-fl mt5 ml22">讲师：</div><div class="divselect divselect-32 teacher-select col-md-14 p0">'
	lHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite>'
	lHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">视频：</div>'
	lHtml+='<div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
	lHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotime="0" videotype="0">无试看</span></cite>'
	lHtml+='<dl><dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd><dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd>'
	lHtml+='<dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd><dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd>'
	lHtml+='<dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd><dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a></dd></dl></div></dd></dl></li>';

	xHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0"><span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
	xHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc"><a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
	xHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl mt5 ml22">讲师：</div><div class="divselect divselect-32 teacher-select col-md-14 p0">'
	xHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite><dl class="select-teacher-list"></dl></div></dd>'
	xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">时间：</div><input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
	xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">至&nbsp;</div><input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd></dl></li>';

	if(lTxt > 100) {
		layer.msg('最多添加100章');
		return false;
	}else {
		setTimeout(function() {
			courseTeacher();
		}, 300);
		articleList.find('#no-plan-list').remove();
		if(type == 1){
			articleList.append(zHtml);
            planInfoDataTime();
		}else if(type == 2){
			articleList.append(lHtml);
            planInfoDataTime();
		}else {
			articleList.append(xHtml);
            planInfoDataTime();
		}
	}
}
//plan.edit
function addCoursePlan(obj) {
    var type = $(obj).attr('type');
    var zHtml, lHtml, xHtml, l;
    var wrapPlanEdit = $('#wrap-plan-edit');
    l = wrapPlanEdit.find('li').length + 1;
    zHtml='<li class="col-md-20 p0"><div class="name-h clearfix col-lg-20 p0">'
    zHtml+='<span class="c-fl mt10 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    zHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
    zHtml+='<a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
    zHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl ml18 mt5">讲师：</div>'
    zHtml+='<div class="divselect divselect-32 teacher-select col-md-13 p0"><cite><span class="cite-icon"></span><span class="cite-text teacher-text">请选择</span></cite>'
    zHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0"><div class="c-fl mt10">时间：</div>'
    zHtml+='<input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
    zHtml+='<dd class="col-md-4 p0"><div class="c-fl mt10">至&nbsp;</div>'
    zHtml+='<input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd>'
    zHtml+='<dd class="col-md-4 p0"><div class="c-fl mt10 tar">直播：</div>'
    zHtml+='<div class="divselect divselect-32 live-select col-md-15 p0" livetype="0">'
    zHtml+='<cite><span class="cite-icon"></span><span class="cite-text livetype-text" livetype="0">无试看</span></cite><dl>'
    zHtml+='<dd><a href="javascript:;" livetype="0">无试看</a></dd>'
    zHtml+='<dd><a href="javascript:;" livetype="1">试看整节</a></dd></dl></div></dd><dd class="col-md-4 p0"><div class="c-fl mt10 tar">视频：</div>'
    zHtml+='<div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
    zHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotime="0" videotype="0">无试看</span></cite><dl>'
    zHtml+='<dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd><dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd>'
    zHtml+='<dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd><dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd>'
    zHtml+='<dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd><dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a>'
    zHtml+='</dd></dl></div></dd></dl></li>';

    lHtml='<li class="col-md-20 p0"><div class="name-h clearfix col-lg-20 p0">'
    lHtml+='<span class="c-fl mt10 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    lHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
    lHtml+='<a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a>'
    lHtml+='</div><dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0">'
    lHtml+='<div class="c-fl ml18 mt5">讲师：</div><div class="divselect divselect-32 teacher-select col-md-13 p0">'
    lHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text">请选择</span></cite>'
    lHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0"><div class="c-fl mt10 tar">视频：</div>'
    lHtml+='<div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
    lHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotime="0" videotype="0">无试看</span></cite>'
    lHtml+='<dl><dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd>'
    lHtml+='<dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd><dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd>'
    lHtml+='<dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd><dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd>'
    lHtml+='<dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a></dd></dl></div></dd></dl></li>';

    xHtml='<li class="col-md-20 p0"><div class="name-h clearfix col-lg-20 p0"><span class="c-fl mt10 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    xHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc"><a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
    xHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl ml18 mt5">讲师：</div><div class="divselect divselect-32 teacher-select col-md-13 p0" style="z-index: 1;">'
    xHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text">请选择</span></cite><dl class="select-teacher-list"></dl></div></dd>'
    xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt10">时间：</div><input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
    xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt10">至&nbsp;</div><input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd></dl></li>';

    if(l > 100) {
        layer.msg('最多添加100章');
    }else {
        courseTeacher();
        if(type == 1) {
            wrapPlanEdit.append(zHtml);
            planInfoDataTime();
        }else if(type == 2){
            wrapPlanEdit.append(lHtml);
            planInfoDataTime();
        }else {
            wrapPlanEdit.append(xHtml);
            planInfoDataTime();
        }
    }
}

function addPlanEditBtn(obj) {
    $(obj).hide();
    var type = $('#grobal-course').attr('type');
    var zHtml, lHtml, xHtml, l = 0;

    zHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0">'
    zHtml+='<span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    zHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
    zHtml+='<a href="javascript:;" class="c-fr mt5 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
    zHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl ml22">讲师：</div>'
    zHtml+='<div class="divselect divselect-32 teacher-select col-md-14 p0"><cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite>'
    zHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0"><div class="c-fl mt5">时间：</div>'
    zHtml+='<input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
    zHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">至&nbsp;</div><input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd>'
    zHtml+='<dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">直播：</div><div class="divselect divselect-32 live-select col-md-15 p0" livetype="0">'
    zHtml+='<cite><span class="cite-icon"></span><span class="cite-text livetype-text" livetype="0">无试看</span></cite><dl>'
    zHtml+='<dd><a href="javascript:;" livetype="0">无试看</a></dd><dd><a href="javascript:;" livetype="1">试看整节</a></dd></dl></div></dd>'
    zHtml+='<dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">视频：</div><div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
    zHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotype="" videotime="">请选择</span></cite><dl>'
    zHtml+='<dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd><dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd>'
    zHtml+='<dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd><dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd>'
    zHtml+='<dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd><dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a></dd></dl></div></dd></dl></li>';

    lHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0">'
    lHtml+='<span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    lHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc">'
    lHtml+='<a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a>'
    lHtml+='</div><dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0">'
    lHtml+='<div class="c-fl mt5 ml22">讲师：</div><div class="divselect divselect-32 teacher-select col-md-14 p0">'
    lHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite>'
    lHtml+='<dl class="select-teacher-list"></dl></div></dd><dd class="col-md-4 p0" style="display:none;"><div class="c-fl mt5 tar">视频：</div>'
    lHtml+='<div class="divselect divselect-32 video-select col-md-15 p0" videotype="0" videotime="0">'
    lHtml+='<cite><span class="cite-icon"></span><span class="cite-text videotype-text" videotime="0" videotype="0">无试看</span></cite>'
    lHtml+='<dl><dd><a href="javascript:;" videotime="0" videotype="0">无试看</a></dd><dd><a href="javascript:;" videotime="0" videotype="1">试看整节</a></dd>'
    lHtml+='<dd><a href="javascript:;" videotime="300" videotype="2">试看5分钟</a></dd><dd><a href="javascript:;" videotime="600" videotype="2">试看10分钟</a></dd>'
    lHtml+='<dd><a href="javascript:;" videotime="1200" videotype="2">试看20分钟</a></dd><dd><a href="javascript:;" videotime="0" videotype="-2">隐藏视频</a></dd></dl></div></dd></dl></li>';

    xHtml='<li class="col-md-20 planList" data-liveType="0" data-videoType="0" data-videoTime="0"><div class="name-h mb5 clearfix col-lg-20 p0"><span class="c-fl mt5 global-sectId" planid="0" data-l="'+l+'">第'+l+'课时：</span>'
    xHtml+='<input type="text" value="" class="col-lg-8 c-fl add-plan-text update-sectionDesc"><a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" onclick="deltNewPlanEditBtn(this)">删除</a></div>'
    xHtml+='<dl class="name-cont clearfix col-lg-20 p0"><dd class="col-md-4 p0"><div class="c-fl mt5 ml22">讲师：</div><div class="divselect divselect-32 teacher-select col-md-14 p0">'
    xHtml+='<cite><span class="cite-icon"></span><span class="cite-text teacher-text" teacherid>请选择</span></cite><dl class="select-teacher-list"></dl></div></dd>'
    xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">时间：</div><input type="text" value="" class="datestart col-md-15 update-startTime" placeholder="开始日期"></dd>'
    xHtml+='<dd class="col-md-4 p0"><div class="c-fl mt5">至&nbsp;</div><input type="text" class="datestart col-md-15 update-endTime" value="" placeholder="结束日期"></dd></dl></li>';

    if(type == 1){
        $(obj).parents('li').before(zHtml);
        planInfoDataTime()
    }else if(type == 2){
        $(obj).parents('li').before(lHtml);
        planInfoDataTime();
    }else {
        $(obj).parents('li').before(xHtml);
        planInfoDataTime();
    }

    $('#wrap-plan-edit').find('li').each(function() {
        var l = $(this).index() + 1 ;
        $(this).find('.global-sectId').text('第'+ l +'课时：');
        $(this).find('.global-sectId').attr('data-l', l);
    });
}

function planInfo(classId, obj) {
    var addMorePlanInfo = $('#addMorePlanInfo');
        if(!addMorePlanInfo.hasClass('addMorePlanInfo')){ //plan.Info
            $(obj).addClass('curr').siblings().removeClass('curr');
            $('#plan-edit-info:eq('+$(obj).index()+')').show().siblings().hide();
            $('#updateBtn-plan-edit').attr('href', '/org.course.editPlan.'+courseId+'.'+classId);
            $('#updateBtn-plan-edit').show();
            $('#plan-edit-info').show();
            if(typeof($(obj).attr('classid')) != 'undefined'){
                classId = $(obj).attr('classid');
                $('#updateBtn-plan-edit').attr('classId', classId);
				$('#get-class-tp-info').show();
                $('#updateBtn-plan-edit').attr('href', '/org.course.editPlan.'+courseId+'.'+classId);
            };
            var params = {
                classId : classId,
                courseId: courseId
            };
            var updatePlanEditTpl = $('#updatePlanEditTpl').html();
            var courseTutorTpl = $('#courseTutorTpl').html();
            var getPlanEditTpl = $('#getPlanEditTpl').html();
            var updateRegionOneTpl = $('#updateRegionOneTpl').html();
            var updateRegionTwoTpl = $('#updateRegionTwoTpl').html();
            var updateRegionThreeTpl = $('#updateRegionThreeTpl').html();
            var updateSectionTeacher = $('#update-section-class-teacher');
            var planEditInfo = $('#plan-edit-info');
            var wrapPlanEdit = $('#wrap-plan-edit');
            var updateRegLeveZero = $('#update-regLevelv0');
            var updateRegLeveOne = $('#update-regLevelv1');
            var updateRegLeveTwo = $('#update-regLevelv2');
                $.ajax({
                    url: '/user/planAjax/planInfo',
                    type: 'post',
                    dataType: 'json',
                    data: JSON.stringify(params),
                    success:function(r) {
                        if(r.code == 0) {
                            if(r.result.section == '') {
                                courseTeacher();
                                $('#plan-edit-info').html('<div style="width:100%;border-bottom:1px solid #ccc;" class="my-collect-no-class p30"><img src="/assets_v2/img/platform/pet3.png" alt="" /><p class="fs14" style="border-bottom:0;">暂时还没有章节哦！</p></div>');
                            }else {
                                $('#plan-edit-info').html(ejs.render(getPlanEditTpl, { data:r.result }));
                            }
                            $('#wrap-plan-edit').html(Mustache.render(updatePlanEditTpl, r.result));
                            $('#select-tutor-list').html(Mustache.render(courseTutorTpl, r.result));
                            $('#wrap-plan-edit').find('.select-teacher-list').html(Mustache.render(courseTutorTpl, r.result));
                            $('#update-address-levelOne').html(Mustache.render(updateRegionOneTpl, r.result));
                            $('#update-address-levelTwo').html(Mustache.render(updateRegionTwoTpl, r.result));
                            $('#update-address-levelThree').html(Mustache.render(updateRegionThreeTpl, r.result));
                            planInfoDataTime();
                            if(r.result.top.teacher == '') {
                                $('#plan-teacherName').text('请选择班主任');
                            }else {
                                $('#plan-teacherName').text('班主任：'+r.result.top.teacher);
                            }
                            $('#plan-userTotal').text('人数：'+r.result.top.userNum);
                            $('#section-class-name').val(r.result.class.name);
                            $('#class-plan-name').text(r.result.class.name)
                            $('#section-class-name').attr('classid', r.result.class.id);
                            $('#section-class-number').val(r.result.class.maxUser);

                            if(!r.result.address) {
                                $('#get-course-address').hide();
                            }else {
                                $('#get-course-address').val(r.result.address);
                                $('#get-course-address').show();
                            }
                            if(!$('#select-tutor-list').find('a[checkedid=1]')) {
                                updateSectionTeacher.find('.cite-text').text('请选择');
                            }else{
                                updateSectionTeacher.find('.cite-text').text($('#select-tutor-list').find('a[checkedid=1]').text());
                                updateSectionTeacher.find('.cite-text').attr('teacherid', $('#select-tutor-list').find('a[checkedid=1]').attr('teacherid'));
                            }
                            var province = $('#update-address-levelOne').find('a[checkedid=1]');
                            if(province) {
                                updateRegLeveZero.show();
                                updateRegLeveZero.find('.cite-text').text($('#update-regLevelv0').find('a[checkedid=1]').text());
                                updateRegLeveZero.find('.cite-text').attr('regid', $('#update-regLevelv0').find('a[checkedid=1]').attr('regid'));
                            }else {
                                getRegion();
                                updateRegLeveZero.show();
                                updateRegLeveZero.find('.cite-text').text('请选择');
                            }
                            var city = $('#update-address-levelTwo').find('a[checkedid=1]');
                            if(city) {
                                updateRegLeveOne.show();
                                updateRegLeveOne.find('.cite-text').text($('#update-regLevelv1').find('a[checkedid=1]').text());
                                updateRegLeveOne.find('.cite-text').attr('regid', $('#update-regLevelv1').find('a[checkedid=1]').attr('regid'));
                            }else {
                                updateRegLeveOne.hide();
                                updateRegLeveOne.find('.cite-text').text('请选择');
                            }
                            var area = $('#update-address-levelThree').find('a[checkedid=1]');
                            if(area) {
                                updateRegLeveTwo.show();
                                updateRegLeveTwo.find('.cite-text').text($('#update-regLevelv2').find('a[checkedid=1]').text());
                                updateRegLeveTwo.find('.cite-text').attr('regid', $('#update-regLevelv2').find('a[checkedid=1]').attr('regid'));
                            }else {
                                updateRegLeveTwo.hide();
                                updateRegLeveTwo.find('.cite-text').text('请选择');
                            }
                            $('#wrap-plan-edit').find('.livetype-text').each(function() {
                                if($(this).attr('livetype') == 0) {
                                    $(this).text("无试看");
                                }else {
                                    $(this).text("试看整节");
                                }
                            });
                            $('#wrap-plan-edit').find('.videotype-text').each(function() {
                                if($(this).attr('videotype') == 0) {
                                    $(this).text('无试看');
                                }else if($(this).attr('videotype') == 1){
                                    $(this).text('试看整节');
                                }else if($(this).attr('videotype') == -2){
                                    $(this).text('隐藏视频');
                                }else if($(this).attr('videotype') == 2){
                                    var t = $(this).attr('videotime') / 60;
                                        $(this).text('试看'+t+'分钟');
                                }else{
                                    $(this).text('无试看');
                                    $(this).attr('videoTime', 0);
                                    $(this).attr('videoType', 0);
                                }
                            });
                            if(r.result.type == 3) {
                                $('#class-number-info').show();
                                $('#update-address-infos').show();
                                planEditInfo.find('.plan-liveType').hide();
                                planEditInfo.find('.plan-videoType').hide();
                                wrapPlanEdit.find('.live-select').parent('dd').hide();
                                wrapPlanEdit.find('.video-select').parent('dd').hide();
                                wrapPlanEdit.find('.update-startTime').parent('dd').show();
                                wrapPlanEdit.find('.update-endTime').parent('dd').show();
                            }
                            if(r.result.type == 2) {
                                $('#class-number-info').hide();
                                planEditInfo.find('.startTime-type').hide();
                                planEditInfo.find('.plan-liveType').hide();
                                wrapPlanEdit.find('.update-startTime').parent('dd').hide();
                                wrapPlanEdit.find('.update-endTime').parent('dd').hide();
                                wrapPlanEdit.find('.live-select').parent('dd').hide();
                                wrapPlanEdit.find('.video-select').parent('dd').show();
                            }
                            if(r.result.type == 1) {
                                $('#class-number-info').show();
                                wrapPlanEdit.find('.update-startTime').parent('dd').show();
                                wrapPlanEdit.find('.update-endTime').parent('dd').show();
                                wrapPlanEdit.find('.live-select').parent('dd').show();
                                wrapPlanEdit.find('.video-select').parent('dd').show();
                            }
                            $('#plan-edit-info').find('.plan-liveType').each(function() {
                                if($(this).attr('livepublictype') == 0) {
                                  $(this).text('直播：无试看');
                                }else {
                                  $(this).text('直播：试看整节');
                                }
                            });
                            $('#plan-edit-info').find('.plan-videoType').each(function() {
                                if($(this).attr('videotype') == 0) {
                                    $(this).text('视频：无试看');
                                }else if($(this).attr('videotype') == 1) {
                                    $(this).text('视频：试看整节');
                                }else if($(this).attr('videotype') == -2) {
                                    $(this).text('视频：隐藏视频');
                                }else {
                                    var t = $(this).attr('videotime') / 60;
                                        $(this).text('视频：试看'+t+'分钟');
                                }
                            });
                            $('#update-section-class-teacher').find('.cite-text').text($('#select-tutor-list').find('a[checkedid=1]').text());
                            $('#update-section-class-teacher').find('.cite-text').attr('teacherid', $('#select-tutor-list').find('a[checkedid=1]').attr('teacherid'));
                            $('#update-plan-list').attr('disabled', false);
                            $('#update-plan-list').removeClass('gray-button').addClass('green-button');
                        }else {
                            courseTeacher();
                            getRegion();
                            $('#update-plan-list').attr('disabled', true);
                            $('#update-plan-list').removeClass('green-button').addClass('gray-button');
                            $('#get-address-level0').text('请选择');
                            $('#plan-edit-info').html('<div style="width:100%;border-bottom:1px solid #ccc;" class="my-collect-no-class p30"><img src="/assets_v2/img/platform/pet3.png" alt="" /><p class="fs14" style="border-bottom:0;">暂时还没有章节哦！</p></div>');
                        }
                    }
                });
        }else { //planAdd
            var planCourseId = $('#grobal-course').attr('courseid');
            var planClassId = $('#grobal-course').attr('classid');
            var getPlanListInfoTpl = $('#getPlanListInfoTpl').html();
            var updateRegionOneTpl = $('#updateRegionOneTpl').html();
            var updateRegionTwoTpl = $('#updateRegionTwoTpl').html();
            var updateRegionThreeTpl = $('#updateRegionThreeTpl').html();
            var courseTutorTpl = $('#courseTutorTpl').html();
            var getRegLeveZero = $('#get-regLevelv0');
            var getRegLeveOne = $('#get-regLevelv1');
            var getRegLeveTwo = $('#get-regLevelv2');
            var articleList = $('#article-list');
            var params = {
                    courseId : planCourseId,
                    classId  : planClassId
                };
                $.ajax({
                    url: '/user/planAjax/planInfo',
                    type: 'post',
                    dataType: 'json',
                    data: JSON.stringify(params),
                    success:function(r) {
                        if(r.code == 0) {
                            $('#addMoreCoursePlan').show();
                            if(r.result.section == '') {
                                $('#section-class-teacher-name').find('.cite-text').text($('.select-teacher-list').find('a:eq(0)').text());
                                $('#section-class-teacher-name').find('.cite-text').attr('teacherid', $('.select-teacher-list').find('a:eq(0)').attr('teacherid'));
                                $('#get-address-level0').text('请选择');
                                $('#get-address-level1').text('请选择');
                                $('#get-address-level2').text('请选择');
                                $('#article-list').html('<div style="width:100%;" id="no-plan-list" class="p30"></div>');
                            }else {
                                $('#article-list').html(Mustache.render(getPlanListInfoTpl, r.result));
                            }
                            $('#section-class-name').val(r.result.class.name);
                            $('#section-class-name').attr('data-id', r.result.class.id);
                            $('#section-class-name').attr('classId', r.result.class.id);
                            $('#section-class-number').val(r.result.class.maxUser);
                            $('.select-teacher-list').html(Mustache.render(courseTutorTpl, r.result));
                            $('#get-address-levelOne').html(Mustache.render(updateRegionOneTpl, r.result));
                            $('#get-address-levelTwo').html(Mustache.render(updateRegionTwoTpl, r.result));
                            $('#get-address-levelThree').html(Mustache.render(updateRegionThreeTpl, r.result));
                            $('#get-course-address').val(r.result.address);
                            $('#get-course-address').show();
                            planInfoDataTime();
                            $('#section-class-teacher-name').find('.cite-text').text($('#section-class-teacher-name').find('a[checkedid=1]').text());
                            $('#section-class-teacher-name').find('.cite-text').attr('teacherid', $('#section-class-teacher-name').find('a[checkedid=1]').attr('teacherid'));
                            var province = getRegLeveZero.find('a[checkedid=1]');
                            if(province) {
                                getRegLeveZero.show();
                                getRegLeveZero.find('.cite-text').text($('#get-regLevelv0').find('a[checkedid=1]').text());
                                getRegLeveZero.find('.cite-text').attr('regid', $('#get-regLevelv0').find('a[checkedid=1]').attr('regid'));
                            }else {
                                getRegion();
                                getRegLeveZero.show();
                                getRegLeveZero.find('.cite-text').text('请选择');
                            }
                            var city = getRegLeveOne.find('a[checkedid=1]');
                            if(city) {
                                getRegLeveOne.show();
                                getRegLeveOne.find('.cite-text').text($('#get-regLevelv1').find('a[checkedid=1]').text());
                                getRegLeveOne.find('.cite-text').attr('regid', $('#get-regLevelv1').find('a[checkedid=1]').attr('regid'));
                            }else {
                                getRegLeveOne.hide();
                            }
                            var area = getRegLeveTwo.find('a[checkedid=1]');
                            if(area) {
                                getRegLeveTwo.show();
                                getRegLeveTwo.find('.cite-text').text($('#get-regLevelv2').find('a[checkedid=1]').text());
                                getRegLeveTwo.find('.cite-text').attr('regid', $('#get-regLevelv2').find('a[checkedid=1]').attr('regid'));
                            }else {
                                getRegLeveTwo.hide();
                            }
                            $('#article-list').find('.plan-edit-deltBtn').each(function() {
                                if($(this).attr('authority') == 1) {
                                    $(this).hide();
                                }else {
                                    $(this).show();
                                }
                            });
							if(r.result.type == 1) {
								$('#article-list').find('.live-select').each(function(){
									if($(this).attr('livetype') == 0) {
										$(this).find('.cite-text').text('无试看');
										$(this).find('.cite-text').attr('livetype', 0);
									}else{
										$(this).find('.cite-text').text('试看整节');
										$(this).find('.cite-text').attr('livetype', 1);
									}
								});
							}
							if(r.result.type == 2) {
								articleList.find('.live-select').parents('dd').hide();
								articleList.find('.update-startTime').parents('dd').hide();
								articleList.find('.update-endTime').parents('dd').hide();
							}
							if(r.result.type == 3){
								articleList.find('.live-select').parents('dd').hide();
								articleList.find('.video-select').parents('dd').hide();
							}
							$('#article-list').find('.videotype-text').each(function(){
                                if($(this).attr('videotype') == 0) {
                                    $(this).text('无试看');
                                }else if($(this).attr('videotype') == 1){
                                    $(this).text('试看整节');
                                }else if($(this).attr('videotype') == -2){
                                    $(this).text('隐藏视频');
                                }else {
                                    var t = $(this).attr('videotime') / 60;
                                        $(this).text('试看'+t+'分钟');
                                }
							});
                        }else{
                            courseTeacher();
                            $('#get-address-level0').text('请选择');
                            $('#section-class-name').val('1班');
                            $('#section-class-number').val('50');
                            setTimeout(function() {
                                $('#section-class-teacher-name').find('.cite-text').text($('#section-class-teacher-name').find('a:eq(0)').text());
                                $('#section-class-teacher-name').find('.cite-text').attr('teacherid', $('#section-class-teacher-name').find('a:eq(0)').attr('teacherid'));
                            }, 200);
                            $('#article-list').html('<div style="width:100%;" id="no-plan-list" class="p30" ></div>');
                            return false;
                        }
                    }
                });
        }
}
//删除章节
function deltPlanEditBtn(obj) {
    var removeParentsElement = $(obj).parents('li'), planId, classId;
        planId = $(obj).attr('planid');
        classId = $('#section-class-name').attr('classid');
    var params = {
            courseId  : courseId,
            planId    : planId,
            classId   : classId
        }
        $.ajax({
            url: '/user/planAjax/delSection',
            type: 'post',
            dataType: 'json',
            data: JSON.stringify(params),
            success:function(r) {
                if(r.code == 0) {
                    removeParentsElement.remove();
                    planInfo(classId);
                }else {
                    layer.msg(r.message);
                }
            }
        });
}
function clearLayerInfo(){
    var sectionClassTeacherName = $('.section-class-teacher-name');
    var selectTweekType = $('#selectTweekType');
    var selectLoneType = $('#selectLongType');
    var addMoreType = $('#add-morse-typeTwo');
    sectionClassTeacherName.find('.cite-text').text('请选择');
    sectionClassTeacherName.find('.cite-text').attr('teacherid', '');
    selectTweekType.find('.cite-text').text('请选择');
    selectTweekType.find('.cite-text').attr('data-id', '');
    selectLoneType.find('.cite-text').text('请选择');
    selectLoneType.find('.cite-text').attr('data-id', '');
    addMoreType.find('.cite-text').attr('time', 0);
    addMoreType.find('.cite-text').attr('type', 0);
    $('#plan-add-desc').val('');
    $('.startime-plan-course').val('');
    $('#select-addTime').html('');
    $('#select-more-minute').val('');
    $('#select-more-time').attr('mytimes', '');
}
function layerPlanOpen() {
    $('.xdsoft_datetimepicker').css('z-index', '33333333');
    layer.open({
        type: 1,
        title:['批量添加'],
        area: ['550px', '550px'],
        shadeClose: true,
        content: $('#add-more-course'),
        cancel: function(){
            locationReload();
        }
    });
}
function addMorseCoursePlan(obj) {
    var addMorePlanInfo = $('#addMorePlanInfo');
    var type = $(obj).attr('type');
    courseTeacher();
    clearLayerInfo();
    if($(obj).attr('type') == 2) {
        $('#add-more-startTime').hide();
        $('#add-morse-styBtn').hide();
        $('#add-morse-selectBtn').hide();
        $('#add-morse-typeTwo').hide();
    }else {
        $('#add-more-startTime').show();
        $('#add-morse-styBtn').show();
        $('#add-morse-selectBtn').show();
        $('#add-morse-typeTwo').hide();
    }
    if(addMorePlanInfo.hasClass('addMorePlanInfo')) { //planAdd
        var classId = $('#section-class-name').attr('classid');
        if(type == 1) {
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
            }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else{
                setPlan(function onError(r){
                    if(r != 0 ) {
                        planInfo(classId);
                        return false;
                    }else {
                        layerPlanOpen();
                        planInfo(classId);
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }else if(type == 3){
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
            }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else if($('#get-course-address').val() == '' || $('#get-address-level0').attr('regid') == '' || $('#get-address-level1').attr('regid') == '') {
                layer.msg('地址不完整');
            }else{
                setPlan(function onError(r){
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        layerPlanOpen();
                        planInfo(classId);
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }else{
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else{
                setPlan(function onError(r){
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        layerPlanOpen();
                        planInfo(classId);
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }
    }else {//plan.info
        var classId = $('#section-class-name').attr('classid');
        if(type == 1){
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else{
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        planInfo(classId);
                        layerPlanOpen();
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }else if(type == 3){
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else if($('#get-course-address').val() == '' || $('#get-address-level0').attr('regid') == '' || $('#get-address-level1').attr('regid') == '') {
                layer.msg('地址不完整');
            }else{
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        planInfo(classId);
                        layerPlanOpen();
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }else{
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择班主任');
            }else{
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        planInfo(classId);
                        layerPlanOpen();
                        $(obj).attr('disabled', true);
                    }
                });
            }
        }
    }
}
function deltNewPlanEditBtn(obj) {
    var removeParentsElement = $(obj).parents('li');
        removeParentsElement.remove();

    $('#wrap-plan-edit,#article-list').find('li').each(function() {
        var l = $(this).index() + 1 ;
        $(this).find('.global-sectId').text('第'+ l +'课时：');
        $(this).find('.global-sectId').attr('data-l', l);
    });
}
function createPlanSuccess(obj) {
    var type = $('#grobal-course').attr('type');
	var sorurl = $('#grobal-course').attr('sorurl');
    var classId = $('#section-class-name').attr('classid');
    var objBtn = $(obj);
    if(type == 1) {
        if($('#section-class-name').val() == '') {
            layer.msg('请填写班级名称');
        }else if($('#section-class-number').val() == '') {
            layer.msg('请填写班级座位');
        }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
            layer.msg('请选择班主任');
        }else if($('#article-list').find('li').length == '') {
            layer.confirm('<p>您没有创建课时,必须添加课时上架后学生可报名。</p>', {
              btn: ['去创建课时', '暂不创建'],
              title:['添加课时']
            }, function(){
                layer.closeAll();
            }, function() {
                $(obj).attr('disabled', true);
                $(obj).removeClass('green-button').addClass('gray-button');
                setTimeout(function() {
                    window.location.href='/'+sorurl;
                }, 500);
            });
        }else{
            setPlan(function onError(r) {
                if(r != 0) {
                    planInfo(classId);
                    return false;
                }else {
                        objBtn.attr('disabled', true);
                        objBtn.removeClass('green-button').addClass('gray-button');
                    setTimeout(function() {
                        window.location.href='/'+sorurl;
                    }, 500);
                }
            });
        }
    }else if(type == 3){
        if($('#section-class-name').val() == '') {
            layer.msg('请填写班级名称');
        }else if($('#section-class-number').val() == '') {
            layer.msg('请填写班级座位');
        }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
            layer.msg('请选择班主任');
        }else if($('#get-course-address').val() == '' || $('#get-address-level0').attr('regid') == '' || $('#get-address-level1').attr('regid') == '') {
            layer.msg('地址不完整');
        }else{
            setPlan(function onError(r) {
                if(r != 0) {
                    planInfo(classId);
                    return false;
                }else {
                        objBtn.attr('disabled', true);
                        objBtn.removeClass('green-button').addClass('gray-butt');
                    setTimeout(function() {
                       window.location.href='/'+sorurl;
                    }, 500);
                }
            });
        }
    }else{
        if($('#section-class-name').val() == '') {
            layer.msg('请填写班级名称');
        }else if($('#section-class-teacher-name').find('.cite-text').attr('teacherid') == '') {
            layer.msg('请选择班主任');
        }else{
            setPlan(function onError(r) {
                if(r != 0) {
                    planInfo(classId);
                    return false;
                }else {
                        objBtn.attr('disabled', true);
                        objBtn.removeClass('green-button').addClass('gray-button');
                    setTimeout(function() {
                        window.location.href='/'+sorurl;
                    }, 500);
                }
            });
        }
    }
}
function backHistoryGo() {
    window.location.href='/org.course/SetDesc';
}
function backPlanEdit() {
    locationReload();
}
function addClassPlanBtn(obj) {
    classList();
    var courseId = $('#grobal-course').attr('courseid');
    var params = {
        courseId : courseId
    }
    $.ajax({
        url: '/user/planAjax/AddClass',
        type: 'post',
        dataType: 'json',
        data: JSON.stringify(params),
        success:function(r) {
            if(r.code == 0) {
				window.location.href="/org.course.editPlan."+courseId+"."+r.classId;
            }else {
                layer.msg(r.message);
            }
        }
    });
};
;(function() {
    $('#wrap-plan-edit').on('blur', '.update-sectionDesc,.update-startTime', function() {
        if($(this).val() == '') {
            $(this).css('border', '1px solid red');
        }else{
            $(this).css('border', '1px solid #ccc');
        }
    });
    $('#wrap-plan-edit').on('blur', '.update-endTime', function() {
        var startTime = $(this).parents('li').find('.update-startTime').val();
        var endTime = $(this).val();
        var startTmeTamp = Date.parse(new Date(startTime));
        var endTimeTamp = Date.parse(new Date(endTime));
        if(startTmeTamp > endTimeTamp) {
            $(this).val('');
            $(this).parents('li').find('.update-startTime').css('border', '1px solid red');
            $(this).css('border', '1px solid red');
            layer.msg('开始时间大于结束时间');
        }else{
            $(this).parents('li').find('.update-startTime').css('border', '1px solid #ccc');
            $(this).css('border', '1px solid #ccc');
        }
    })
})()

//下载视频
function downPlanVideo(obj) {
    var thisPlan = $(obj);
    if($(obj).text() == '备课') {
      $(obj).attr('href', $(obj).attr('data-url')); 
    }else{
        thisPlan.attr('href', $(obj).attr('data-url'));
        var planId = $(obj).attr('data-planid');
        var classId = $('#get-class-list').find('li.curr').attr('classid');
        var params = {
            courseId:courseId, 
            classId:classId, 
            planId:planId
        }
        $.ajax({
            url: '/user.planAjax.uploadvideo',
            type: 'post',
            dataType: 'json',
            data: JSON.stringify(params),
            success:function(r) {
                if(r.code == 0) {
                    if(r.result.data.length <= 1) {
                        //window.location.href = r.result.data[0].url;
                        var elemIF = document.createElement("iframe");  
                        elemIF.src = r.result.data[0].url;
                        elemIF.style.display = "none";
                        $('body').append(elemIF);
                    }else{
                        var getPlanDataTpl = $('#getPlanDataTpl').html();
                        $('#get-data-video').html(ejs.ender(getPlanDataTpl, { data:r.result }));
                        layer.open({
                          type: 1,
                          title:['下载视频'],
                          area: ['570px', '350px'],
                          shadeClose: true,
                          content: $('#plan-video-info')
                        }); 
                    }
                }else{
                    layer.msg(r.message);
                }
            }   
        });
    }
}
