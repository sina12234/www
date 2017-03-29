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
.file-more .plan-edit-type{ line-height: 20px;}
.file-more .plan-edit-type span{ width: 100%;}
.dropdown .dropdown-show-tab{ width: 20%;padding: 0 10px 0 0; margin:5px;float: left;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<!-- tpInfo -->
<?php echo tpl_function_part("/user.commont.coursetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
<!-- tpInfo -->
<section class="pb40">
	<div class="container">
		<div class="row">
	<!-- bdy -->
    		<section>
                <?php echo tpl_function_part("/user.commont.coursenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
    			<div class="col-md-16 pr0">
    				<div class="gn-base-ct top-style-info clearfix">
    					<div class="clearfix" style="border-bottom:1px solid #e8e8e8;">
    						<ul class="tab-main c-fl clearfix" id="get-class-list" style="width:auto;border-bottom:0;"></ul>
    						<a href="javascript:;" id="add-class-plan-btn" onclick="addClassPlanBtn(this)" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" class="cBlue ml15 c-fr" style="display:none;">增加班级</a>
    					</div>
    					<dl class="gn-base-ct clearfix mt20" id="get-class-tp-info" style="padding:10px 5px;background:#f7f7f7;" style="display:none;">
    						<dd class="col-lg-9" id="plan-teacherName"></dd>
    						<dd class="col-lg-4" id="plan-userTotal"></dd>
    						<dd class="col-lg-5 c-fr clearfix">
                                <a href="javascript:;" onclick="delClass(this)" id="delClassBtn" class="cBlue c-fl mr20">删除班级</a>
    							<a href="" class="cBlue c-fr" id="updateBtn-plan-edit">
    								<img src="/assets_v2/img/update-icon.jpg" alt="">
    								修改排课
    							</a>
    						</dd>
    					</dl>
    					<div class="file-more mb20">
    					  <div class="class-hour col-lg-20 pd0" id="plan-edit-info"></div>
    					</div>
    				</div>
    			</div>
    		</section>
	<!-- /bdy -->
		</div>
	</div>
</section>
<input class="hidden" updateId="0" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" id="grobal-course" />
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.course.plan.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<script type="text/template" id="getClassListTpl">
    <<#classList>>
        <li class="tab-hd-opt" onclick="planInfo(<<classId>><<userId>>, this)" classId="<<classId>>">
            <span  class="org-slide-a">
                <<className>>
            </span>
        </li>
    <</classList>>
</script>
<script type="text/template" id="getPlanEditTpl">
    <<#section>>
        <div class="plan-edit-list col-lg-20">
            <dl>
                <dt class="col-lg-3"><<sectionName>></dt>
                <dd class="col-lg-6 plan-list-cont">
                    <span><<sectiondesc>></span>
                    <span>讲师：<<teacher>></span>
                </dd>
                <dd class="col-lg-5 plan-edit-type clearfix">
                    <span class="startTime-type"><<startTime>></span>
                    <span class="clearfix">
                        <em class="plan-liveType c-fl mr10" livePublicType="<<livePublicType>>"></em>
                        <em class="c-fl plan-videoType" videoType="<<videoPublicType>>" videoTime="<<videoTrialTime>>"></em>
                    </span>
                </dd>
                <dd class="col-lg-3 clearfix">
                    <a href="<<button1.url>>" target="_blank" class="cBlue c-fl"><<button1.name>></a>
                    <a href="<<button3.url>>" target="_blank" class="cBlue c-fr"><<button3.name>></a>
                </dd>
                <dd class="col-lg-3 tac">
                    <a href="<<button2.url>>" target="_blank" class="cBlue"><<button2.name>></a> 
                </dd>
           </dl>
        </div>
    <</section>>
</script>
<script>
var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>;
var type = <?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>;
var updateId = '<?php echo SlightPHP\Tpl::$_tpl_vars["updateId"]; ?>';
$(function() {
    classList();
    var classId;
    $.divselect(".divselect cite");
    setTimeout(function() {
        $('#get-class-list').find('li:eq(0)').addClass('curr');
        if($('#get-class-list').find('li').length == '') {
            $('#get-class-tp-info').hide();
			$('#add-class-plan-btn').show();
        }else {
            $('#get-class-tp-info').show();
            if(type == 2){
                $('#plan-userTotal').hide();
                if($('#get-class-list').find('li').length >= 1) {
                    $('#add-class-plan-btn').hide();
                }else {
					$('#add-class-plan-btn').show();
                }
            }else {
                var l = $('#get-class-list').find('li').length;
                if(l >= 6) {
                    $('#add-class-plan-btn').hide();
                }else {
                    $('#add-class-plan-btn').show();
                } 
            }   
        }
        $('#get-class-list').find('li').each(function() {
            if($(this).attr('classid') == updateId) {
                $('#get-class-list').find('li').removeClass('curr');
                $(this).addClass('curr');
            }
        });
        classId = $('#get-class-list').find('li.curr').attr('classid');
        $('#updateBtn-plan-edit').attr('classId', classId);
        planInfo(classId);
    }, 300);
});
</script>
