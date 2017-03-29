<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>学习统计 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
 <body>
 <?php echo tpl_function_part("/site.main.nav.home"); ?>
    <!-- tpInfo -->
        <?php echo tpl_function_part("/org.course.managetop.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
    <!-- tpInfo -->
 <section class="pb30">
    <div class="container">
        <div class="row">
        <!-- bdy -->
        <section>
            <?php echo tpl_function_part("/org.course.managenav.".SlightPHP\Tpl::$_tpl_vars["courseId"]); ?>
            <div class="col-md-16 pr0">
                <div class="gn-base-ct clearfix">
                    <section class="col-md-20 fs14 base-content clearfix">
                        <div class="mb20 c-fl col-xs-12 col-sm-8 col-md-20 pd0">
							<a href="/phpexcel/classstat?classId=1567" class="c-fr" id="Excel_btn">导出Excel</a>
                            <ul class="tab-main fs14" id="get-class-list"></ul>
                        </div>
                        <!-- ct -->
                        <div class="col-md-20 p0 clearfix">
                            <div class="divselect divselect-32 c-fl">
                                <cite>
                                    <span class="cite-icon"></span>
                                    <span class="cite-text">章节统计</span>
                                </cite>
                                <dl id="statist-select">
                                    <dd>
                                        <a onclick="getClassStatAjax(classId, this)" href="javascript:;">章节统计</a>
                                    </dd>
                                    <dd>
                                        <a onclick="getClassStudentStat(this)" href="javascript:;">班级排名</a>
                                    </dd>
                                </dl>
                            </div>
                            <div id="update-time" class="c-fr fs12"></div>
                        </div>
                        <!-- /ct -->
                        <div class="c-fl col-md-20 pd0 file-more">
                            <table class="table-grid table-list" id="chapter-list">
                                <thead class="fs14">
                                    <tr>
                                        <td>课时</td>
                                        <td>到课率</td>
                                        <td>点赞</td>
                                        <td>发言</td>
                                        <td>举手</td>
                                        <td>讨论区</td>
                                        <td>最高同时在线</td>
                                        <td>随堂统计正确率</td>
                                        <td>询问回答率</td>
                                        <td>时长</td>
                                        <td>老师迟到</td>
                                    </tr>
                                </thead>
                                <tbody id="getClassStatAjax" class="getClassStatAjax"></tbody>
                            </table>
                            <table class="table-grid tble-class-student-list" id="tble-class-student-list" style="display:none;">
                                <thead class="fs14">
                                    <tr>
                                        <td>排名</td>
                                        <td>姓名</td>
                                        <td>到课次数</td>
                                        <td>赞数</td>
                                        <td>发言</td>
                                        <td>讨论区</td>
                                        <td>举手</td>
                                        <td>随堂测试</td>
                                        <td>观看直播</td>
                                        <td>观看录播</td>
                                    </tr>
                                </thead>
                                <tbody id="tble-class-student-infos"></tbody>
                            </table>
                            <div class="get-load-data" style="display:none"></div>
                        </div>
                        <div id="page-num" style="display:none;" class="mt20 col-md-20 tac"></div>
                    </section>
                </div>
            </div>
        </section>
    <!-- /bdy -->
        </div>
    </div>
 </section>
 <?php echo tpl_function_part("/site.main.footer"); ?>
 </body>
 </html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.create.course.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>"></script>
 <script type="text/template" id="getClassStatAjaxTpl">
 <<#planId>>
     <tr>
        <td><a href="/course.stat.getPlanStatByPid/<<planId>>" class="blue-link"><<name>></a></td>
        <td data="<<attendance>>" class="data_course"><<attendance>></td>
        <td data="<<zan>>"><<zan>></td>
        <td data="<<call>>" class="data_status"><<call>></td>
        <td data="<<handup>>" class="data_status"><<handup>></td>
        <td data="<<discuss>>" class="data_status"><<discuss>></td>
        <td data="<<maxOnline>>" class="data_status"><<maxOnline>></td>
        <td data="<<correct>>" class="data_course"><<correct>></td>
        <td data="<<answerRate>>" class="data_course"><<answerRate>></td>
        <td data="<<vvRecord>>" class="data_time"><<vvRecord>></td>
        <td data="<<status>>" class="teac_data"><<status>></td>
    </tr>
 <</planId>>
 </script>
<script type="text/template" id="getClassStudentStatusPlan">
    <<#data>>
        <tr>
            <td data="<<orderNum>>"><<orderNum>></td>
            <td data="<<name>>"><<name>></td>
            <td data="<<planCount>>"><<planCount>></td>
            <td data="<<zan>>"><<zan>></td>
            <td data="<<call>>" class="data_hand"><<call>></td>
            <td data="<<discuss>>" class="data_strip"><<discuss>></td>
            <td data="<<handup>>" class="data_hand"><<handup>></td>
            <td data="<<correct>>" class="data_course"><<correct>></td>
            <td data="<<vtLive>>" class="data_time"><<vtLive>></td>
            <td data="<<vtRecord>>" class="data_time"><<vtRecord>></td>
        </tr>
    <</data>>
</script>
 <script type="text/template" id="getClassListTpl">
    <<#classList>>
        <li class="tab-hd-opt" onclick="getClassStatAjax(<<classId>>, this)" classId="<<classId>>">
            <span  class="org-slide-a">
                <<className>>
            </span>
        </li>
    <</classList>>
</script>
 <script>
 var courseId = <?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>, classId;
window.onload = function() {
    classList();
    setTimeout(function() {
        $('#get-class-list').find('li:eq(0)').addClass('curr');
        classId = $('.tab-main').find('li:eq(0)').attr('classId');
        $('#statist-select').find('a').attr('classid', classId);
        getClassStatAjax(classId);
    }, 300);
}
function getClassStatAjax(classId, obt, curr) {
    $('#update-time').hide();
    $('#statist-select').parents('.divselect').find('.cite-text').text('章节统计');
    $(obt).addClass('curr').siblings().removeClass('curr');
    $('.file-more .table-list:eq('+$(obt).index()+')').show().siblings().hide();
    if(typeof($(obt).attr('classId')) != 'undefined'){
        classId = $(obt).attr('classId');
        $('#statist-select').find('a').attr('classid', classId);
    }
    $('#Excel_btn').attr('href','/phpexcel/classstat?classId='+classId);
    var url = '/course.stat.GetClassStatAjax/'+classId+'';
    var getClassStatAjaxTpl = $('#getClassStatAjaxTpl').html();
    var html;
    html = '<div style="width:100%;height:300px;" class="my-collect-no-class p40"><img src="/assets_v2/img/platform/pet3.png" alt="" /><div class="fs14">暂时还没有学习统计数据哦！</div></div>';
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data:'',
            success:function(r) {
                if( r.code == 0){
                    $('#chapter-list').show();
                    $('#tble-class-student-list').hide();
                    $("#page-num").hide();
                    if(r.data.planId == '') {
                        $('.get-load-data').html(html);
                        $('.get-load-data').show();
                    }else{
                        $('#getClassStatAjax').html(Mustache.render(getClassStatAjaxTpl, r.data));
                        $('.get-load-data').hide();
                    }
				}else {
                    $('#tble-class-student-list').hide();
                    $('.get-load-data').show();
                    $("#page-num").hide();
                    $('.get-load-data').html(html);
                }
                $('.getClassStatAjax').find('tr>td').each(function(){
                    var type = $(this).attr('data');
                    if($(this).hasClass('data_course')){
                        $(this).text(type+'%');
                    }
                    if(type == 0) {
                        $(this).text('-');
                    }
                    if($(this).hasClass('data_status') && type != 0){
                        $(this).text(type+'人');
                    }
                    if($(this).hasClass('data_time') && type != 0){
                        $(this).text(type+'分钟');
                    }
                    if($(this).hasClass('teac_data') && type == 1){
                        $(this).text('否');
                    }else if($(this).hasClass('teac_data') && type == 2){
                        $(this).text('是');
                    }
                })
            }
        });
}
function getClassStudentStat(obt, curr) {
    var classId = $(obt).attr('classid');
    var getClassStudentStatusPlan = $('#getClassStudentStatusPlan').html();
    $('.file-more .tble-class-student-list:eq('+$(obt).index()+')').show().siblings().hide();
    var page = curr || 1;
    $.post('/course.stat.GetClassStudentStatAjax', { classId:classId, page:page }, function(r) {
        if(r.code == 0) {
            $('#tble-class-student-infos').html(Mustache.render(getClassStudentStatusPlan, r.result));
            $('.get-load-data').hide();
            $('#chapter-list').hide();
            $('#tble-class-student-list').show();
            $('#update-time').show();
            $("#page-num").show();
            $('#update-time').text(r.result.lastUpdated);
            laypage({
                cont: $("#page-num"),
                pages: r.result.totalPage,
                curr: curr || 1,
                jump: function(obj, first){
                    if(!first){
                        getClassStudentStat(obt, obj.curr);
                    }
                }
            });
			$('.tble-class-student-list').find('tr>td').each(function(){
					var type = $(this).attr('data');
                    if($(this).hasClass('data_course')){
                        $(this).text(type+'%');
                    }
                    if(type == 0) {
                        $(this).text('-');
                    }
					if($(this).hasClass('data_hand') && type != 0){
						$(this).text(type+'次')
					}
					if($(this).hasClass('data_strip') && type != 0){
						$(this).text(type+'条')
					}
                    if($(this).hasClass('data_time') && type != 0){
                        $(this).text(type+'分钟');
                    }
			})
        }else{
            $('.get-load-data').show();
            $('#chapter-list').hide();
            $('#tble-class-student-list').hide();
            $('#update-time').hide();
            $("#page-num").hide();
            var html;
                html = '<table class="table-grid"><thead class="fs14">'
                html +='<tr><td>排名</td><td>姓名</td><td>到课次数</td><td>赞数</td><td>发言</td><td>讨论区</td><td>举手</td><td>随堂测试</td><td>观看直播</td><td>观看录播</td></tr></thead></table>'
                html +='<div style="width:100%;height:300px;" class="my-collect-no-class p40"><img src="/assets_v2/img/platform/pet3.png" alt="" /><div class="fs14">暂时还没有班级统计数据哦！</div></div>';
            $('.get-load-data').html(html);
        }
    }, 'json');
}
</script>