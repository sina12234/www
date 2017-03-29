<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>创建章节 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/site.main.header"); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<style type="text/css">
.form-horizontal li:nth-child(even) dd{ background: #f7f7f7;}
.dropdown .dropdown-show-tab{ width: 20%;padding: 0 10px 0 0; margin:5px;float: left;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!-- tpInfo -->
<?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<section class="pb40">
	<div class="container">
		<div class="row">
	<!-- bdy -->
		<section>
			<?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
			<div class="col-md-16 pr0">
                <!-- 修改排课 -->
                <div class="gn-base-ct clearfix" id="update-planEdit">
                    <h1 class="fs14 base-title mb20">
                        <a href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>" id="class-plan-name"></a> >
                        班级排课
                    </h1>
                  <div class="file-more mb20">
                <!-- 班级信息 -->
                    <ul class="col-md-20 form-horizontal fs14 clearfix p0">
                        <li class="col-md-6 clearfix p0">
                            <span class="c-fl mt10"><em class="cRed">*</em>班级名称：</span>
                            <input id="section-class-name" maxlength="5" type="text" class="class-inpt-text" />
                        </li>
                        <li class="col-md-6 clearfix p0" id="class-number-info" style="display:none;">
                            <span class="c-fl mt10"><em class="cRed">*</em>班级座位：</span>
                            <input onkeyup="this.value=this.value.replace(/\D/g,'')" id="section-class-number" type="text" class="class-inpt-text" />
                        </li>
                        <li class="col-md-6 clearfix p0">
                            <span class="c-fl mt10"><em class="cRed">*</em>班主任：</span>
                            <div class="divselect divselect-32 col-md-14 p0 c-fl" id="update-section-class-teacher">
                                <cite>
                                    <span class="cite-icon"></span>
                                    <span class="cite-text" teacherid="">请选择</span>
                                </cite>
                                <dl id="select-tutor-list"></dl>
                            </div>
                        </li>
                        <li class="col-md-20 p0 clearfix mt15 mb15" id="update-address-infos" style="display:none;">
                            <span class="col-md-1 p0" style="width:6%;"><em class="cRed">*</em>地址：</span>
                            <div class="col-md-19 p0" style="width:94%;">
                                <div class="divselect divselect-32 col-md-4 mr20 p0" id="update-regLevelv0">
                                    <cite>
                                        <span class="cite-icon"></span>
                                        <span class="cite-text" id="get-address-level0" regid="">请选择</span>
                                    </cite>
                                    <dl id="update-address-levelOne"></dl>
                                </div>
                                <div class="divselect divselect-32 col-md-4 mr20 p0" style="display:none;" id="update-regLevelv1">
                                    <cite>
                                        <span class="cite-icon"></span>
                                        <span class="cite-text" id="get-address-level1" regid="">请选择</span>
                                    </cite>
                                    <dl id="update-address-levelTwo"></dl>
                                </div>
                                <div class="divselect divselect-32 col-md-4 mr20 p0" style="display:none;" id="update-regLevelv2">
                                    <cite>
                                        <span class="cite-icon"></span>
                                        <span class="cite-text" id="get-address-level2" regid="">请选择</span>
                                    </cite>
                                    <dl id="update-address-levelThree"></dl>
                                </div>
                            </div>
                            <div class="col-md-20 p0">
                                <span class="col-md-1 p0" style="width:6%;"></span>
                                <textarea class="col-md-13 address-info-txt" style="display:none;" placeholder="请输入地址" id="get-course-address"></textarea>
                            </div>
                        </li>
                    </ul>
                    <!-- /班级信息 -->
                    <div class="form-horizontal col-lg-20 pd0">
                        <h1 class="fs14">设置章节</h1>
                        <div class="p-box col-lg-20 pd0">
                            <ul class="form-horizontal" id="wrap-plan-edit"></ul>
                        </div>
                        <ul class="gn-base-ct col-md-20 clearfix" style="padding:15px;border-top:0;">
                            <li class="c-fl">
                                <a href="javascript:;" class="col-md-20 cBlue" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" onclick="addCoursePlan(this)">+添加单个课时</a>
                            </li>
                            <li class="c-fl">
                                <a href="javascript:;" class="col-md-20 cBlue addMoreCoursePlan"  status="0" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" onclick="addMorseCoursePlan(this)">+添加多个课时</a>
                            </li>
                        </ul>
                    </div>
                  </div>
                  <!-- 保存 -->
                  <div class="mt20 col-md-20 tac mb20">
                        <button class="green-button mr10" id="update-plan-list">保存</button>
                        <a href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>.<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>" class="cBlue">返回排课列表</a>
                  </div>
                  <!-- /保存 -->
                </div>
        <!-- /修改排课 -->
			</div>
		</section>
	<!-- /bdy -->
		</div>
	</div>
</section>
<!-- 批量添加 -->
<section id="add-more-course" class="add-more-course" style="display:none;">
    <div class="col-xs-20 mt20 clearfix">
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">章节名：</div>
            <textarea class="col-xs-12 more-txt-content" placeholder="批量添加章节，每行输入一个章节名称，章节按顺序自动排序" id="plan-add-desc"></textarea>
            <p class="tar col-xs-18 lGray">批量添加章节，每行输入一个章节名称，章节按顺序自动排序</p>
        </div>
        <div class="clearfix col-xs-20 p0 mb10">
            <div class="col-xs-5 tar fs14">授课老师：</div>
            <div class="divselect divselect-32 col-md-5 p0 section-class-teacher-name">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择</span>
                </cite>
                <dl class="select-teacher-list"></dl>
            </div>
        </div>
        <div class="col-xs-20 p0" id="add-more-startTime">
            <div class="col-xs-5 fs14 tar">开课时间：</div>
            <div class="col-xs-10 p0 course-date mb10 mr10 clearfix">
                <input type="text" placeholder="开课时间" class="startime-plan-course course-name col-xs-18">
                <img src="/assets_v2/img/list-rl.png" class="c-fr datertime-icon mt5" alt="">
            </div>
        </div>
        <div class="col-xs-20 p0" id="add-morse-styBtn">
            <div class="col-xs-5 fs14 tar">循环方式：</div>
            <div class="divselect col-xs-5 p0 mr10" id="selectTweekType">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" data-id="1">每天</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="2">每周</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="3">自定义</a>
                    </dd>
                </dl>
            </div>
            <span class="c-fl mr10">
                <img src="/assets_v2/img/list-rl.png" id="select-more-time" style="display:none;" alt="" />
            </span>
        </div>
        <div class="col-xs-20 p10 dropdown" id="select-addTime" style="display:none;"></div>
        <div class="col-xs-20 p0" id="add-morse-selectBtn">
            <div class="col-xs-5 fs14 tar">课程时长：</div>
            <div class="divselect col-xs-5 p0 mr10" id="selectLongType">
                <cite>
                    <span class="cite-icon"></span>
                    <span class="cite-text">请选择</span>
                </cite>
                <dl>
                    <dd>
                        <a href="javascript:;" data-id="1">0.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="2">1小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="3">1.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="4">2小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="5">2.5小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="6">3小时</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-id="7">自定义</a>
                    </dd>
                </dl>
            </div>
            <input class="col-xs-8" class="course-name hours-ipt" style="display:none;" placeholder="输入时间（分钟）" id="select-more-minute" />
        </div>
        <div class="col-xs-20 p0" id="add-morse-typeTwo" style="display:none;">
            <div class="col-xs-5 tar fs14">视频：</div>
            <div class="divselect divselect-32 col-xs-5 p0">
            <cite>
                <span class="cite-icon"></span>
                <span class="cite-text" time="0" type="0">请选择</span>
            </cite>
            <dl>
                <dd>
                    <a href="javascript:;" time="0" type="0">无试看</a>
                </dd>
                <dd>
                    <a href="javascript:;" time="0" type="1">试看整节</a>
                </dd>
                <dd>
                    <a href="javascript:;" time="300" type="2">试看5分钟</a>
                </dd>
                <dd>
                    <a href="javascript:;" time="600" type="2">试看10分钟</a>
                </dd>
                <dd>
                    <a href="javascript:;" time="1200" type="2">试看20分钟</a>
                </dd>
                <dd>
                    <a href="javascript:;" time="0" type="-2">隐藏视频</a>
                </dd>
            </dl>
        </div>
        </div>
        <div class="clearfix mb10 tac">
            <button class="green-button" data-status="0" id="quicksetCourse-btn" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" onclick="quicksetCourse(this)">确认</button>
            <button class="gray-button" onclick="quicksetCourseCancle()" >取消</button>
        </div>
    </div>
</section>
<!-- /批量添加 -->
<input class="hidden" updateId="0" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" id="grobal-course" />
<input type="hidden" classId="<?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>" id="plan-info" />
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.plan.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<script type="text/template" id="updatePlanEditTpl">
    <<#section>>
       <li class="col-md-20 p0">
           <div class="name-h clearfix col-lg-20 p0">
            <span planId="<<planId>>" class="c-fl mt10 global-sectId" data-l="<<orderNo>>"><<sectionName>>：</span>
            <input type="text" value="<<sectiondesc>>" class="col-lg-8 c-fl update-sectionDesc">
            <a href="javascript:;" class="c-fr mt10 cBlue plan-edit-deltBtn" authority="<<authority>>" planId="<<planId>>" onclick="deltPlanEditBtn(this)">删除</a>
            <!--<a href="javascript:;" class="c-fr mr20 mt10 cBlue" onclick="addPlanEditBtn(this)">插入课时</a>-->
           </div>
            <dl class="name-cont clearfix col-lg-20 p0">
               <dd class="col-md-4 p0">
                    <div class="c-fl ml18 mt5">
                      讲师：
                    </div>
                    <div class="divselect divselect-32 teacher-select col-md-13 p0">
                        <cite>
                            <span class="cite-icon"></span>
                            <span class="cite-text teacher-text" teacherId="<<teacherId>>"><<teacher>></span>
                        </cite>
                        <dl class="select-teacher-list"></dl>
                    </div>
               </dd>
               <dd class="col-md-4 p0" style="display:none;">
                  <div class="c-fl mt10">
                      时间：
                  </div>
                  <input type="text" value="<<startTime>>" class="datestart update-startTime col-md-15" placeholder="开始日期" />
               </dd>
               <dd class="col-md-4 p0" style="display:none;">
                  <div class="c-fl mt10">
                      至&nbsp;
                  </div>
                  <input type="text" class="datestart update-endTime col-md-15" value="<<endTime>>" placeholder="结束日期" />
               </dd>
               <dd class="col-md-4 p0" style="display:none;">
                    <div class="c-fl mt10 tar">直播：</div>
                    <div class="divselect divselect-32 live-select col-md-15 p0" liveType="<<livePublicType>>">
                        <cite>
                            <span class="cite-icon"></span>
                            <span class="cite-text livetype-text" liveType="<<livePublicType>>">请选择</span>
                        </cite>
                        <dl>
                            <dd>
                                <a href="javascript:;" liveType="0">无试看</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" liveType="1">试看整节</a>
                            </dd>
                        </dl>
                    </div>
               </dd>
               <dd class="col-md-4 p0" style="display:none;">
                    <div class="c-fl mt10 tar">视频：</div>
                    <div class="divselect divselect-32 video-select col-md-15 p0">
                        <cite>
                            <span class="cite-icon"></span>
                            <span class="cite-text videotype-text" videoTime="<<videoTrialTime>>" videoType="<<videoPublicType>>">请选择</span>
                        </cite>
                        <dl>
                            <dd>
                                <a href="javascript:;" videoTime="0" videoType="0">无试看</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" videoTime="0" videoType="1">试看整节</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" videoTime="300" videoType="2">试看5分钟</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" videoTime="600" videoType="2">试看10分钟</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" videoTime="1200" videoType="2">试看20分钟</a>
                            </dd>
                            <dd>
                                <a href="javascript:;" videoTime="0" videoType="-2">隐藏视频</a>
                            </dd>
                        </dl>
                    </div>
               </dd>
           </dl>
       </li>
   <</section>>
</script>
<script type="text/template" id="courseTutorTpl">
    <<#classTeacher>>
        <dd>
            <a href="javascript:;" onclick="selectCourseTeacher(this)" checkedId="<<checked>>" teacherId="<<teacherId>>"><<teacherName>></a>
        </dd>
    <</classTeacher>>
</script>
<script type="text/template" id="courseTeacherTpl">
    <<#teacher>>
        <dd>
            <a href="javascript:;" onclick="selectCourseTeacher(this)" checkedId="<<checked>>" teacherId="<<teacherId>>"><<teacherName>></a>
        </dd>
    <</teacher>>
</script>
<script type="text/template" id="getRegionOneTpl">
<<#region>>
    <dd>
        <a href="javascript:;" onclick="getRegionTwo(this)" regId="<<regId>>" checkedId="<<checked>>" level="<<level>>"><<regName>></a>
    </dd>
<</region>>
</script>
<script type="text/template" id="getRegionTwoTpl">
<<#region>>
    <dd>
        <a href="javascript:;" onclick="getRegionThree(this)" regId="<<regId>>" checkedId="<<checked>>" level="<<level>>"><<regName>></a>
    </dd>
<</region>>
</script>
<script type="text/template" id="getRegionThreeTpl">
<<#region>>
    <dd>
        <a href="javascript:;" onclick="getRegionLast(this)" regId="<<regId>>" checkedId="<<checked>>" level="<<level>>"><<regName>></a>
    </dd>
<</region>>
</script>
<script type="text/template" id="updateRegionOneTpl">
<<#province>>
    <dd>
        <a href="javascript:;" onclick="getRegionTwo(this)" regId="<<regionId>>" checkedId="<<checked>>"><<name>></a>
    </dd>
<</province>>
</script>
<script type="text/template" id="updateRegionTwoTpl">
<<#city>>
    <dd>
        <a href="javascript:;" onclick="getRegionThree(this)" regId="<<regionId>>" checkedId="<<checked>>"><<name>></a>
    </dd>
<</city>>
</script>
<script type="text/template" id="updateRegionThreeTpl">
<<#area>>
    <dd>
        <a href="javascript:;" onclick="getRegionLast(this)" regId="<<regionId>>" checkedId="<<checked>>"><<name>></a>
    </dd>
<</area>>
</script>
<script>
var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
var classId = <?php echo SlightPHP\Tpl::$_tpl_vars["classId"]; ?>;
var type = <?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>;
window.onload = function() {
    planInfo(classId);
    $.divselect(".divselect cite");
    $('.startime-plan-course').datetimepicker({
        format: "Y-m-d H:i",
        timepicker:true,
        step:5,
        minDate:jQuery('.startime-plan-course').val(),
    });
    $('.datertime-icon').click(function() {
        $('.startime-plan-course').datetimepicker('show');
    })
    $( "#select-more-time" ).datetimepicker({
        timepicker:false,
        format:'Y-m-d H:i',
        step:5,
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
                locationReload();
                return false;
            }
            if(timeset < i){
                $("<div class='dropdown-show-tab' onclick='deltDate(this)'><div class='tab-delete'></div><div class='left-side'></div>"+cts+"</div>").appendTo("#select-addTime");
                $('#select-addTime').find('.dropdown-show-tab').each(function() {
                    myTime.push($(this).text());
                });
                $('#select-more-time').attr('myTimes', myTime.join(','));
            }
        }
    });
    $('#update-plan-list').click(function() {
        classId = $('#section-class-name').attr('classid');
        if(type == 1) {
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
                return false;
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
                return false;
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择教师');
                return false;
            }else {
				$('#update-plan-list').attr('disabled', true);
				$('#update-plan-list').removeClass('green-button').addClass('gray-button');
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                    }else {
                        $('#update-plan-list').attr('disabled', true);
                        $('#update-plan-list').removeClass('green-button').addClass('gray-button');
                        setTimeout(function() {
                            window.location.href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>."+classId;
                        }, 200);
                    }
                });
            }
        }else if(type == 3){
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
                return false;
            }else if($('#section-class-number').val() == '') {
                layer.msg('请填写班级座位');
                return false;
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择教师');
                return false;
            }else if($('#get-address-level0').attr('regid') == '' || $('#get-address-level1').attr('regid') == '' || $('#get-course-address').val() == '') {
                layer.msg('地址不完整');
            }else {
				$('#update-plan-list').attr('disabled', true);
				$('#update-plan-list').removeClass('green-button').addClass('gray-button');
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        $('#update-plan-list').attr('disabled', true);
                        $('#update-plan-list').removeClass('green-button').addClass('gray-button');
                        setTimeout(function() {
                            window.location.href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>."+classId;
                        }, 200);
                    }
                });
            }
        }else {
            if($('#section-class-name').val() == '') {
                layer.msg('请填写班级名称');
                return false;
            }else if($('#update-section-class-teacher').find('.cite-text').attr('teacherid') == '') {
                layer.msg('请选择教师');
                return false;
            }else {
				$('#update-plan-list').attr('disabled', true);
				$('#update-plan-list').removeClass('green-button').addClass('gray-button');
                setPlan(function onError(r) {
                    if(r != 0) {
                        planInfo(classId);
                        return false;
                    }else {
                        $('#update-plan-list').attr('disabled', true);
                        $('#update-plan-list').removeClass('green-button').addClass('gray-button');
                        setTimeout(function() {
                            window.location.href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>."+classId;
                        }, 200);
                    }
                });
            }
        }
    });
}
</script>