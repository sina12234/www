<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>课程详情 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的课表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.calendar-v2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
<!--检测客户端-->
</head>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<script>
var  timetableInfo = '<?php echo SlightPHP\Tpl::$_tpl_vars["jsonTimeArr"]; ?>';
</script>
<style>
		.live-jxsk{ width:75px;text-align:right; }
	@media screen and (min-width:320px) and (max-width:768px){
		.live-bkym{ width:135px; }
		.live-jxsk{ width:115px; }
	}
	@media screen and (max-width:1024px) and (min-width:768px){
		.live-jxsk{ width:120px;text-align:right; }
	}
</style>
<!-- mob nav -->
<div class="mob-nav hidden-lg hidden-md">
    <p class="swiper-wrapper" id="mob-nav">
        <a href="/teacher.detail.entry/<?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?>" class="swiper-slide">我的主页</a>
        <a href="/teacher.course.timetable2" class="swiper-slide active">课程表</a>
        <a href="/teacher.course.teacherOfCourseList" class="swiper-slide">在教课程</a>
        <a href="/teacher.manage.student" class="swiper-slide">我的学生</a>
    </p>
</div>
<section class="pd30">
    <div class="container">
        <div class="row">
		<?php echo tpl_function_part("/user.main.menu.teacher.newtimetable"); ?>
        <div class="right-main col-sm-20  col-md-16">
            <div class="content">
                <div class="pd0">
                    <!--日历 -->
                    <div id="calendar">
                        <div class="curr-day col-lg-5 col-md-5 col-sm-5">
                            <div class="calendar-title">
                                <a id="month_perv" class="cWhite pdh15" href="javascript:void(0)"> < </a>
                                <span id="calendar-select-year" class="fs14"></span>年
                                <span id="select-month" class="fs14"></span>月
                                <a id="month_next" class="cWhite pdh15" href="javascript:void(0)"> > </a>
                            </div>
                            <div class="calendar-show" id="calendar-show">
                                <div class="calendar-show-base"><a href="#" class="c-fl toToday">返回今天</a><a href="#" class="c-fr calendar-yellow" id="course_num">4节课</a></div>
                                <div class="calendar-show-panel hidden-xs">31</div>
                            </div>
                        </div>
                        <div class="calendar-day col-lg-15 col-xs-20 col-md-15 col-sm-15">
                            <table id="calendar-table" class="col-xs-20 col-md-20 pd0">
                                <thead class="calendar-title col-xs-20 col-md-20 pd0">
                                <tr class="col-md-20 col-xs-20 pd0">
                                    <td>一</td>
                                    <td>二</td>
                                    <td>三</td>
                                    <td>四</td>
                                    <td>五</td>
                                    <td>六</td>
                                    <td>日</td>
                                </tr>
                                </thead>
                                <tbody class="calendar-main" id="calendar-main">
                                <tr>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td><span class="calendar-this">今天</span><span class="calendar-dott"></span></td>
                                    <td>10</td>
                                    <td>11<span class="calendar-dott"></span></td>
                                    <td>12</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <ul id="live_list_today" class="mt20 live-class-list">
                    </ul>
                </div>
            </div>
        </div>


        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</div>
</body>
<div class="update-box-index">
	<div  class="index-teac-update" id="index-teac-update">
		<div class="index-marcen">
			<p><img src="/assets_v2/img/duochangjing.png"><span>多场景上课模式</span></p>
			<p><img src="/assets_v2/img/suitang.png"><span>随堂测验</span></p>
			<p><img src="/assets_v2/img/zhibohudong.png"><span>直播互动</span></p>
	   </div>
	   <div class="index-marbut">
		<!--    <input type="button" onclick="down()" value="立即下载"> -->
		   <a type="button" href='<?php echo SlightPHP\Tpl::$_tpl_vars["host"]; ?>' onclick="down()" target="_blank">立即下载</a>

	   </div>
   </div>
	<div class="index-teac-bg" id="index-teac-bg" style="display:none;">
		<div class="title">
			<p>是否安装</p>
		</div>
		<div class="teac-content">
			<p>手动安装"云课教师助手"</p>
			<span>提示:下载后,找到下载包文件,双击进行手动安装</span>
			<div class="content-but">
				<input type="button" onclick="window.location.reload(true)" value="安装完成">
				<input type="button" value="遇到问题">
			</div>
		</div>
	</div>
</div>

<style>
	.update-box-index{ display:none;}
    .layui-layer-setwin .layui-layer-close2{ top:2px;right:0;background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .layui-layer-setwin .layui-layer-close2:hover{ background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .index-teac-update{ background:url(/assets_v2/img/tcbg.png)no-repeat;width:489px;height:368px;}
    .index-teac-update p{ float:left;width:100px;margin-left:50px;text-align:center; }
    .index-teac-update p span{ float:left;padding-top:5px;font-size:14px;color:gray; }
    .index-teac-update p:nth-child(2) span{ padding-left:22px;}
    .index-teac-update p:nth-child(3) span{ padding-left:22px;}
    .index-marbut{ text-align:center;margin-top:30px;float:left;width:100%; }
    .index-marbut a{ width:100px;height:35px;line-height:35px;text-align:-webkit-center;background:#ffa81d;border-radius:3px;color:#fff;font-size:14px;display: -webkit-inline-box; }
    .index-marbut a:hover{ background:#ffa81d;opacity:0.8}
    .index-marcen{ margin-top:40%;float:left;  }
	.teac-content{ background:#fff;text-align:center;float:left;width:100% }
	.teac-content p{ padding-top:40px;color:#000;font-size:16px; }
	.teac-content span{ padding-top:20px;float:left;width:100%;color:gray; }
	.content-but{ margin-top:45px;float:left;width:100%; }
	.content-but input{ width:100px;height:30px;border-radius:3px;background:#f7f7f7;color:#333;border:1px solid #ccc; }
	.content-but input:nth-child(1){ background:#ffa81e;color:#fff;margin-right:20px;border:none; }
	.index-teac-bg{ float:left;width:100%;height:250px; }
	.index-teac-bg .title{ background:#f7f7f7;width:100%;height:35px; }
	.index-teac-bg .title p{ color:gray;font-size:14px;line-height:35px;padding-left:10px; }
</style>
<script>
    var flag = 0;
    function checkCallback(data){
        if(data["code"]==0){
          flag = 1;
//            jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=launch&user_name=<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>&user_token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&course_id=&class_id=&plan_id=");
        }else {

        }
    }
    function launchCallback(data){
        console.log(data['code']);
    }
	function down(){
		$('#layui-layer-shade1').hide();
		$('#index-teac-update').hide();
	}
	//关闭弹窗
	function close(){

	}
    function check_st(plan,cla,course){
        check();
		if(flag==1){
            jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=launch&user_name=<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>&user_token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&course_id="+course+"&class_id="+cla+"&plan_id="+plan);
        }
		if(flag==0){
				layer.open({
				type:1,
				title:false,
				area: ['489px',''],
				content: $('.update-box-index')
		});
		}
    }
    function check(){
        jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=check");
    }
    $(document).ready(function(){
        check();
    });
	function down(){
		 window.location.reload();
	}
</script>
</html>
