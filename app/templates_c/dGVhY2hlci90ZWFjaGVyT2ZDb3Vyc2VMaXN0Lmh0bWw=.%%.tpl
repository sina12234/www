<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>我的课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的课表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body  id="scrollTops">
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- mob nav -->
<div class="g-nav hidden-lg hidden-md">
	<ul class="swiper-wrapper" id="mob-nav">
		<li class="swiper-slide"><a href="/teacher.detail.entry/<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userId"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userId"]; ?><?php }; ?>">我的主页</a></li>
		<li class="swiper-slide"><a href="/teacher.course.timetable2">课程表</a></li>
		<li class="swiper-slide"><a href="/task/publishTask/teacherTaskList">作业列表</a></li>
		<li class="swiper-slide"><a href="/teacher.course.teacherOfCourseList" class="active">在教课程</a></li>
		<li class="swiper-slide"><a href="/teacher.manage.student">我的学生</a></li>
	</ul>
</div>
<!--header-->
<style>
.divselect cite, .divselect input { text-align:right;width:90px;border:1px solid #f7f7f7;font-size:12px;color:#333;}
.divselect dl { font-size:12px; color:#333;}
.divselect dl dd a:hover{ color:#ffa81d;background: none;}
.so-student{ float:right;position:relative;height:30px;text-align:right;}
.so-student input{ float:right;display:none;width:150px;height:30px;border:1px solid #dbdbdb;padding:0 5px;line-height:30px;padding-right:20px;}
.so-student .del-btn{ border:none;display:none;background-position:-100px -522px;position:absolute;right:50px;top:8px;cursor:pointer;padding:10px;background:url(/assets_v2/img/web_organization.png) no-repeat -476px -677px;}
.so-student .so-btn,.so-student-input .so-btn{ border:none;display:none;width:20px;height:20px;background-position:-3px -518px;position:absolute;right:20px;top:5px;cursor:pointer;}
.s-so-icon{ width:50px;height:30px;background-position:-40px -515px;display:inline-block;}
.so-student-input .s-so-btn{ background:url(/assets_v2/img/platform/icon.png) no-repeat;}
.new-so-icon{ background:url(/assets_v2/img/platform/icon.png) no-repeat #efefef 11px -514px;right:0;position: absolute;}
.new-so-icon:hover{ background:url(/assets_v2/img/platform/icon.png) no-repeat -40px -514px;}
.t-so-student input { float:right;padding-right:22px;}
.live-plan-box{ float:left;width:100%; margin:15px 0 15px 0; }
.live-plan-box .culum-course { height:30px;line-height: 30px;float: left;background: #f7f7f7;width:100%;}
.live-plan-box .culum-course div.culum-wish{ width:100%; }
.live-plan-box .culum-course div.culum-wish span{ margin:0 20px; }
.live-plan-box .culum-course div.culum-wish span.cite-icon{ margin:0; }
.live-plan-box .culum-course div.culum-wish span a:hover{ color:#26b3c0; }
.divselect cite{ padding:0 20px 0 5px; }
.live-plan-box .culum-course p span a.curr{ color:#26b3c0;}
.org-timetable .c-title{ float: left;border:1px solid #ddd;padding:3px 10px;margin-top: 5px;border-radius: 3px;}
.org-timetable .c-progress{ float:left;height:7px;padding:0;background:#eee;overflow:hidden;-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;}
.org-timetable .c-progress i{ float:left;background:#02a1e5;height:6px;-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;}
.icon{ background:url(/assets_v2/img/icon-arr.png) no-repeat;}
.taped-icon{ top:10px;left:10px; }
.g-icon3{ top:10px;left:10px; }
</style>
<section class="org-section">
    <div class="container">
        <div class="row">
        <!-- leftmenu start -->
	    <?php echo tpl_function_part("/user.main.menu.teacher.teacherOfCourseList"); ?>
        <!-- right-main -->
        <div class="right-main col-sm-20 col-md-16 col-xs-20">
            <div class="tab-main hidden-xs hidden-sm">
                <div class="tab-hd fs14">
                    <a href="teacher.course.teacherOfCourseList?&fc=0" class="tab-hd-opt <?php if(isset(SlightPHP\Tpl::$_tpl_vars["fc"])&&SlightPHP\Tpl::$_tpl_vars["fc"]=='0'){; ?>curr<?php }; ?>">全部(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseNum"]['total'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseNum"]['total']; ?><?php }else{; ?>0<?php }; ?>)</a>
                    <a href="teacher.course.teacherOfCourseList?&fc=1" class="tab-hd-opt <?php if(isset(SlightPHP\Tpl::$_tpl_vars["fc"])&&SlightPHP\Tpl::$_tpl_vars["fc"]=='1'){; ?>curr<?php }; ?>">直播课(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseNum"]['livingNum'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseNum"]['livingNum']; ?><?php }else{; ?>0<?php }; ?>)</a>
                    <a href="teacher.course.teacherOfCourseList?&fc=2" class="tab-hd-opt <?php if(isset(SlightPHP\Tpl::$_tpl_vars["fc"])&&SlightPHP\Tpl::$_tpl_vars["fc"]=='2'){; ?>curr<?php }; ?>">录播课(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseNum"]['recordNum'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseNum"]['recordNum']; ?><?php }else{; ?>0<?php }; ?>)</a>
                    <a href="teacher.course.teacherOfCourseList?&fc=3" class="tab-hd-opt <?php if(isset(SlightPHP\Tpl::$_tpl_vars["fc"])&&SlightPHP\Tpl::$_tpl_vars["fc"]=='3'){; ?>curr<?php }; ?>">线下课(<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseNum"]['underNum'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseNum"]['underNum']; ?><?php }else{; ?>0<?php }; ?>)</a>
                </div>
                <!-- rt -->
				<?php if(SlightPHP\Tpl::$_tpl_vars["isOpenCreate"]==1){; ?>
                <a href="/org.course.type" id="goCreateCourse" class="c-fr add-button fs14" target="_blank">
                    <i class="add-icon c-fl"></i>新建课程
                </a>
				<?php }; ?>
                <form action="" method="get" class="c-fr mr20" name="search">
                    <div class="right search-frame hidden-xs so_student" style="padding:0;" id="so_student">
                        <input placeholder="输入课程名称" autocomplete="off" type="text" name="searchdata" id="search-data" class="search-input" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["searchdata"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["searchdata"]; ?><?php }; ?>"/>
                        <div class="search-box">
                            <span class="search-icon" id="form_sub" ></span>
                            <div class="clear-icon" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["searchdata"])){; ?>style="display:none;"<?php }; ?> ></div>
                        </div>
                        <input type="hidden"  name="course_id" value="0">
                        <input type="hidden"  name="class_id" value="0">
                        <input type="hidden"  name="ut" value="0">
                        <input type="hidden"  name="sub" value="1">
                    </div>
                    <!--<div class="so-student t-so-student col-xs-20 col-md-5" >-->
                        <!--<input type="text"  name="searchdata" placeholder="输入课程名称" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["searchdata"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["searchdata"]; ?><?php }; ?>">-->
                        <!--<input type="hidden"  name="course_id" value="0">-->
                        <!--<input type="hidden"  name="class_id" value="0">-->
                        <!--<input type="hidden"  name="ut" value="0">-->
                        <!--<span class="del-btn icon"></span>-->
                        <!--<input type="submit" name="sub" class="so-btn s-so-btn icon" value="1"  id="s-so-btn"/>-->
                        <!--<span class="s-so-icon new-so-icon"></span>-->
                    <!--</div>-->
                </form>
                <!-- /rt -->
            </div>
            <div class="live-plan-box  hidden-xs hidden-sm" style="border:none;">
                <div class="culum-course">
                    <div class="c-fl hidden-xs culum-wish">
                        <div class="divselect article-select c-fl">
                            <cite>
                                <span class="cite-icon"></span>
                                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["st"])&&SlightPHP\Tpl::$_tpl_vars["st"]==1){; ?>默认排序
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["st"])&&SlightPHP\Tpl::$_tpl_vars["st"]==2)){; ?>学生最多
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["st"])&&SlightPHP\Tpl::$_tpl_vars["st"]==3)){; ?>最新建课
                                <?php }; ?>
                            </cite>
                            <dl class="tac">
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st','1',''); ?>">默认排序</a></dd>
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st','2',''); ?>">学生最多</a></dd>
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st','3',''); ?>">最新建课</a></dd>
                            </dl>
                            <input name="" type="hidden" value="" />
                        </div>
                        <div class="divselect article-select c-fl mr20">
                            <cite>
                                <span class="cite-icon"></span>
                                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["ut"])&&SlightPHP\Tpl::$_tpl_vars["ut"]==0){; ?>全部状态
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["ut"])&&SlightPHP\Tpl::$_tpl_vars["ut"]==1)){; ?>未开课
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["ut"])&&SlightPHP\Tpl::$_tpl_vars["ut"]==2)){; ?>进行中
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["ut"])&&SlightPHP\Tpl::$_tpl_vars["ut"]==3)){; ?>已完成
                                <?php }; ?>
                            </cite>
                            <dl id="AllarticleNotice" class="tac">
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ut','0',''); ?>">全部状态</a></dd>
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ut','1',''); ?>">未开课</a></dd>
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ut','2',''); ?>">进行中</a></dd>
                                <dd class=""><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ut','3',''); ?>">已完成</a></dd>
                            </dl>
                            <input name="" type="hidden" value="" />
                        </div>
                        <div class="divselect article-select c-fl">
                            <cite style="width:auto">
                                <span class="cite-icon"></span>
                                <?php if(isset(SlightPHP\Tpl::$_tpl_vars["ad"])&&SlightPHP\Tpl::$_tpl_vars["ad"]==1){; ?>全部负责课程
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["ad"])&&SlightPHP\Tpl::$_tpl_vars["ad"]==2)){; ?>我是讲师的课程
                                <?php }elseif((isset(SlightPHP\Tpl::$_tpl_vars["ad"])&&SlightPHP\Tpl::$_tpl_vars["ad"]==3)){; ?>我是助教的课程
                                <?php }; ?>
                            </cite>
                            <dl>
                                <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ad','1',''); ?>">全部负责课程</a></dd>
                                <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ad','2',''); ?>">我是讲师的课程</a></dd>
                                <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'ad','3',''); ?>">我是助教的课程</a></dd>
                            </dl>
                            <input name="" type="hidden" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-20 pd0" id="time-table">
                <ul class="org-timetable">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseResult"]['data'])){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseResult"]['data'] as SlightPHP\Tpl::$_tpl_vars["m"]=>SlightPHP\Tpl::$_tpl_vars["n"]){; ?>
                    <li class="col-md-10 col-xs-20 col-sm-6">
						<a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["n"]['subdomain']; ?>/org.course.plan/<?php echo SlightPHP\Tpl::$_tpl_vars["n"]['course_id']; ?>" target="_blank">
							<div class="col-md-10 col-xs-10 c-img">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["n"]['type']==2){; ?>
                                <span class="g-icon3 lessons-icon c-fl">录播</span>
                                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["n"]['course_thumb']); ?>" width="100%" alt="">
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["n"]['type']==3)){; ?>
                                <span class="taped-icon ">线下</span>
                                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["n"]['course_thumb']); ?>" width="100%" alt="">
                                <?php }else{; ?>
                                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["n"]['course_thumb']); ?>" width="100%" alt="">
                                <?php }; ?>
                            </div>
                            <div class="col-md-10 col-xs-10 c-info">
                            	<span class="fs14 c-fl col-lg-20 pd0 col-xs-20"><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['title']; ?></span>

                            	<span class="cGray c-fl col-xs-20 pd0"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->name; ?><?php }; ?><?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->plan_info)){; ?>&nbsp;&nbsp;<?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->plan_info; ?>章<?php }; ?>
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->planend_progress)){; ?><?php echo tpl_modifier_tr('进度 ','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][0]->planend_progress; ?><?php }; ?>
                                </span>
								<span class="cGray c-fl col-xs-20 pd0"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->name; ?><?php }; ?>&nbsp;&nbsp;<?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->plan_info)){; ?><?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->plan_info; ?>章<?php }; ?>
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->planend_progress)){; ?><?php echo tpl_modifier_tr('进度 ','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][1]->planend_progress; ?><?php }; ?>
                                </span>
								<span class="cGray c-fl col-xs-20 pd0"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->name; ?><?php }; ?>&nbsp;&nbsp;<?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->plan_info)){; ?><?php echo tpl_modifier_tr('共','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->plan_info; ?>章<?php }; ?>
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->planend_progress)){; ?><?php echo tpl_modifier_tr('进度 ','site.teacher'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['class'][2]->planend_progress; ?><?php }; ?>
                                </span>

								<p class="c-title cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["n"]['subname']; ?></p>
                            </div>

								</a>
                    </li>
                            <?php }; ?>

                            <?php }else{; ?>
                            <div class="my-collect-no-class c-fl col-lg-offset-0 col-xs-offset-0 col-sm-offset-0 col-sm-20 col-md-20 col-lg-20 col-xs-20">
                                <img src="../assets_v2/img/platform/pet3.png" alt="">
                                <p style="font-weight:bold;color:#666;">没有找到课程，去找其它类型课程吧~</p>
                            </div>
                            <?php }; ?>
                        </ul>
                    </div>
                <div class="page-list" id="pagepage"></div>
            </div>
        </div>
    </div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script>
$(function(){
	page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["url"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["total"]; ?>);
    var dCookie=document.cookie;
    if(dCookie.indexOf("language")>=0){
    var language=dCookie.substring(dCookie.indexOf("language"));
    var languageItem=language.substring(0,language.indexOf(";"));
    var languageValue=languageItem.substr(languageItem.indexOf("=")+1)
    $("#lang a").each(function(){
        if($(this).attr("val")==languageValue){
        $(this).addClass("lang-select");
            if($(this).attr("val")=="en"){
                $(".video-try-icon").removeClass("try-icon").addClass("en-try-icon");
                $("#en-upload-imgtip").show();//机构管理-首页维护
            }
        }
    })
    }else{
    $("footer .lang:first").addClass("lang-select");

    }
})
$("footer .lang").click(function(){
    var d=new Date();
    var expireDays=300;
    d.setTime(d.getTime()+expireDays*24*3600*1000);
    document.cookie = "language="+$(this).attr("val")+"; path=/; expires="+d.toGMTString();
    location.reload();
});


//搜索
$(document).ready(function(){
    $('#form_sub').click(function(){
        var searchVal = $.trim($(this).parents('.search-frame').find('input[name=searchdata]').val());
        if(searchVal==''){
            return false;
        }
        $('form[name=search]').submit();
    });
    $('#search-data').on('keyup',function (event) {
        if(event.keyCode == 13){
            var searchVal = $.trim($(this).val());
            if(searchVal==''){
                return false;
            }
            $('form[name=search]').submit();
        }
    })
    $("#so_student .clear-icon").click(function(){
        $("#so_student input[name=searchdata]").val('');
        $('form[name=search]').submit();

    })
	$("#goCreateCourse").click(function(){
		$.post("/teacher.course.sourceurl",{  },function(r){

		});
	});
})

</script>
