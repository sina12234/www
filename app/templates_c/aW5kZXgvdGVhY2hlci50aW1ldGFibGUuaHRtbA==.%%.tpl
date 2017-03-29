<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>我的课程 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的课表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<body id="scrollTops">
<!--header-->
<?php echo tpl_function_part("/index.main.usernav.teacher"); ?>
<section class="pd20">
    <div class='container'>
    <!-- leftmenu start-->
        <div class="row">
        <?php echo tpl_function_part("/index.main.menu.teacher"); ?>
    <!-- leftmenu end -->
        <div class="col-lg-16 col-md-16 col-sm-20 col-xs-20">
        <!--mob-->
<p class="mob-nav hidden-lg hidden-md">
    <a href="/index.teacher.student" class="col-xs-6">我的学生</a>
    <a href="/index.teacher.timetable" class="col-xs-6">我的课程</a>
    <a href="/index.teacher.edit" class="col-xs-6">教师资料</a>
</p>
            <div class="right-content">
                <dl class="new-list-tab list-tab fs14 col-lg-20 col-md-20 col-sm-20 col-xs-20" style="padding:0 0 20px 0;">
                    <dd class="curr col-lg-3 col-md-3 col-sm-6 col-xs-8">待上课<i></i></dd>
                    <dd class="col-lg-3 col-md-3 col-sm-6 col-xs-8">已结束<i></i></dd>
                    <div class="c-fr">
                    <?php /*
                    <form action="" method="post">
                        <input type="text" name="starttime" id="timeStart" class="form-control" placeholder="开始日期" >
                        <input type="text" name="endstarttime" id="timeEnd" class="form-control" placeholder="结束日期" >
                        <input type="submit" class="form-control" value="提交" >
                    </form>
                    */?>
                    </div>
                </dl>
                <ul id="list">
                <li>
                    <ul class="list-c2" id="notEnd" style="border:0;">
                  <?php /*  <li>
                        <div class="line-date col-sm-1 col-xs-6 new-line-date">
                            <div class="list-icon-on"></div>
                            <div class="list-date"><span class="fs14">今日</span><br>19:00</div>
                        </div>
                        <div class="new-list-right col-xs-13 col-sm-18">
                                <div class="pic col-md-5"><p><img src="<?php echo utility_cdn::img('/assets_v2/img/gao.png'); ?>" alt=""></p></div>
                                <div class="intro col-md-6">
                                    <p class="fs16">五年级春季精品班（尖子/超常）</p>
                                    <p><span class="cGreen">免费</span> | 共三章 学生(20/30)</p>
                                    <p><span style="margin-top:-1px;float:left"><img src="<?php echo utility_cdn::img('/assets_v2/img/time.png'); ?>"></span>6月21日 19:00 第三章</p>
                                </div>
                                <div class='col-md-5 row c-fl lin-h'>
                                    <p>距离开课时间:19分钟</p>
                                </div>
                                <div class="col-md-4 row ml20">
                                    <p>
                                        <button>开始上课</button>
                                    </p>
                                    <p><a href="#">备课</a> | <a href="#">布置作业</a></p>
                                </div>
                        </div>
                    </li>
                    */?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["showData"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["showData"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li>
                        <div class="line-date col-sm-3 col-md-2 col-xs-6 new-line-date">
                            <div class="list-icon new-list-icon"></div>
                            <div class="list-date"><span class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["start_date"]; ?></span><?php /*<br>19:00*/?></div>
                        </div>
                        <div class="new-list-right col-xs-13 col-sm-18 col-lg-18">
                                <div class="pic col-md-5 col-sm-5">
                                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['play']; ?>">
                                        <p>
                                        <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>" alt="" />
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_type']; ?>
                                       </p>
                                    </a>
                                </div>
                                <div class="intro col-sm-8 col-md-6 col-xs-20">
                                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['play']; ?>" target="_blank"><p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["course_name"]; ?>  <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["class_name"]; ?></p></a>
                                <?php /*    <p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["course_name"]; ?></p> */?>
                                    <p><span class="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_info_color']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["fee_info"]; ?></span> | 共<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["countsecs"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["countsecs"]; ?><?php }else{; ?>0<?php }; ?>章 进度 <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["section_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["section_name"]; ?><?php }; ?></p>
                                    <p><span style="margin-top:-1px;float:left"></span> 学生(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["user_total_class"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["max_user_class"]; ?>)<p>
									<p class="mt5">
                                        <span class="c-fl client-logo-name"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->subname)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->subname; ?><?php }else{; ?><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->name,0,6,'utf-8'); ?><?php }; ?></span>
                                    </p>
								</div>
                                <?php /*<div class='col-sm-3 row c-fl lin-h'><p><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["time_countdown"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["time_countdown"]; ?><?php }; ?></p></div>*/?>
                                <div class='hidden-sm col-md-4 hidden-xs row c-fl lin-h'>
                                    <p></p>
                                </div>
                                <div class="col-md-4 col-sm-5 col-xs-20 hidden-xs hidden-sm hidden-md row ml20">
                                    <p>
                                            <!--<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_status']; ?>-->
                                             <a href="javascript:void(0);" class="btn col-lg-20" onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>) title="">开始上课</a>
                                        </p>
                                        <p class="ta-c lh30 hidden-xs mt10 fs14"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson']; ?></a> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["mid"]; ?> <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_manage_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_manage']; ?></a> </p>
                                        <?php /*   <p><a href="#">备课</a> | <a href="#">布置作业</a></p> */?>
                                    </div>
                            <!--显示月份
                                <div class="student-course-month hidden-xs"><i class="col-sm-1 fs12">11月</i></div>
                            -->
                            </div>
                        </li>
                        <?php }; ?>
                        <?php }else{; ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-5 course-schedule mt15">
                                <div class="col-md-20 mt15 fs14 tac">
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"/>
                                    <br><span>您还没有待上的课程哦!请联系机构排课</span>
                                </div>
                            </div>
                        </div>
                        <?php }; ?>
                        </ul>
                    <div class="col-sm-12">
                    </div>
                    </li>
                    <li>
                        <ul class="list-c2" id = "isEnd" style="border:0;">
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["showDataend"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["showDataend"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <li>
                            <div class="line-date col-xs-6 col-md-2 col-sm-3 new-line-date">
                                <div class="list-icon  new-list-icon"></div>
                                <div class="list-date "><span class="fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["start_date"]; ?></span><?php /*<br>19:00*/?></div>
                            </div>
                            <div class="new-list-right col-xs-14 col-sm-17 col-lg-18">
                                    <div class="pic col-md-5 col-sm-5">
                                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['play']; ?>" target="_blank">
                                            <p>
                                            <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb']; ?>" alt="" />
                                             <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_type']; ?>
                                            </p>
                                        </a>
                                    </div>
                                   <?php /* <div class="pic col-sm-2"><p><img src="<?php echo utility_cdn::img('/assets_v2/img/gao.png'); ?>" alt=""></p></div> */?>
                                    <div class="intro col-sm-8 col-md-6">
                                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['play']; ?>" target="_blank"><p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["course_name"]; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["class_name"]; ?></p></a>
                                        <p><span class="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['fee_info_color']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["fee_info"]; ?></span> | 共<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["countsecs"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["countsecs"]; ?><?php }else{; ?>0<?php }; ?>章 进度 <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]["section_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]["section_name"]; ?><?php }; ?></p>
                                        <p><span style="margin-top:-1px;float:left"></span> 学生(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["user_total_class"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]["max_user_class"]; ?>)<p>
										<p class="mt5">
                                            <span class="c-fl client-logo-name"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->subname)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->subname; ?><?php }else{; ?><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["v"]['org_info']->name,0,6,'utf-8'); ?><?php }; ?></span>
                                        </p>
									</div>
                                    <div class='col-md-4 row c-fl hidden-sm lin-h hidden-xs'>
                                        <p></p>
                                    </div>
                                    <div class="col-md-4 col-sm-5 row ml20 visible-lg">
                                        <p>
                                            <!--<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_status']; ?>-->
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['status']==1){; ?>
                                            <a href="javascript:void(0);" class="onclick_btn col-xs-20" onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>) title="">开始上课</a>
                                            <?php }else{; ?>
                                            <?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_status']; ?>
                                            <?php }; ?>
                                        </p>
                                        <!--<p class="ta-c lh30 hidden-xs mt10 fs14"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson']; ?></a> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["mid"]; ?> <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_manage_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_manage']; ?></a> </p>-->
                                       <?php if( SlightPHP\Tpl::$_tpl_vars["v"]['plan_play'] =='继续上课' ){; ?>
                                        <p class="ta-c lh30 hidden-xs mt10 fs14"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson']; ?></a> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["mid"]; ?> <a href="javascript:void(0);"  onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>) title=""><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_play']; ?></a> </p>
                                        <?php }else{; ?>
                                        <p class="ta-c lh30 hidden-xs mt10 fs14"><a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['lesson']; ?></a> <?php echo SlightPHP\Tpl::$_tpl_vars["v"]["mid"]; ?> <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_play_link']; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_play']; ?></a> </p>
                                        <?php }; ?>

                                      <?php /*  <p><a href="#">备课</a> | <a href="#">批改作业</a></p> */?>
                                    </div>
                            <!--显示月份
                                <div class="student-course-month hidden-xs"><i class="col-sm-1 fs12">11月</i></div>
                            -->
                            </div>
                        </li>
                        <?php }; ?>
                        <?php }else{; ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 col-lg-20 col-lg-offset-4 course-schedule mt15">
                                <div class="col-md-12 mt15 fs14" style="text-align:center;">
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"/>
                                    <p>您还没有已结束的课程哦!</p>
                                </div>
                            </div>
                        </div>
                        <?php }; ?>
                        </ul>
                    <div class="col-sm-12">
                    </div>
                    </li>

                    </ul>
                </div>
            </div>
            <div class='clear'></div>
        </div>
        </div>
    </section>
<script id='planListisend' type='text/template'>
    <<#data>>
        <li>
            <div class="line-date col-md-2 col-xs-6 col-sm-3 new-line-date">
                <div class="list-icon col-xs-13 new-list-icon"></div>
                <div class="list-date"><span class="fs14"><<start_date>></span></div>
            </div>
            <div class="new-list-right col-xs-13 col-lg-18 col-sm-17">
                <div class="pic col-sm-5 col-md-5">
                    <a href="<<play>>"><p><img src="<<thumb>>" alt=""><<&course_type>></p></a>
                    <p class="mt10">
                        <span class="c-fl client-logo-name"><<org_info_name>></span>
                    </p>
                </div>

                <div class="intro col-sm-8 col-md-6">
                    <a href="<<play>>"><p class="fs16"><<course_name>> <<class_name>></p></a>
                    <p><span class="cGreen"><<fee_info>></span> | 共<<countsecs>>章 进度 第2章</p>
                    <p><span style="margin-top:-1px;float:left"></span> 学生(<<user_total_class>>/<<max_user_class>>)<p>
                </div>
                <div class='hidden-sm col-md-4 row c-fl lin-h hidden-xs'>
                    <p></p>
                </div>
                <div class="col-md-4 col-sm-5 row ml20 visible-lg">
                    <p>
                        <<&plan_status>>
                    </p>
                    <div data-status="<<status>>" class="plan-status">
                        <p style="display:none;" class="fs14 no-plan-link">
                            <a href="<<plan_play_link>>"><<plan_play>></a> |
                            <a href="<<lesson_link>>"><<lesson>></a>
                        </p>
                        <p style="display:none;" class="ta-c fs14 lesson-plan-link">
                            | <a href="javascript:;" onclick=checks_st(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['plan_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['class_id']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['course_id']; ?>) ><<plan_play>></a>
                        </p>
                    </div>
                    <p class="ta-c lh30 hidden-xs mt10 fs14"></p>
                </div>
            </div>
        </li>
        <</data>>
</script>
    <script id='planListnotend' type='text/template'>
		<<#data>>
        <li>
            <div class="line-date col-xs-6 col-md-2 new-line-date">
                <div class="list-icon new-list-icon"></div>
                <div class="list-date"><span class="fs14"><<start_date>></span></div>
            </div>

            <div class="new-list-right col-xs-13 col-sm-18">
                    <div class="pic col-md-5">
                        <a href="<<play>>"><p><img src="<<thumb>>" alt="" height="90" /><<&course_type>></p></a>
                        <p class="mt10">
                           <span class="c-fl client-logo-name"><<org_info_name>></span>
                        </p>
                    </div>

                    <div class="intro col-md-6">
                        <a href="<<play>>"><p class="fs16"><<course_name>>  <<class_name>></p></a>
                                            <p><span class="cGreen"><<fee_info>></span> | 共<<countsecs>>章 进度 <<section_name>></p>
                        <p><span style="margin-top:-1px;float:left"></span> 学生(<<user_total_class>>/<<max_user_class>>)<p>
                    </div>
                                        <div class='col-md-4 row c-fl lin-h  hidden-xs'>
                        <p></p>
                    </div>
                    <div class="col-md-4 col-xs-20 row ml20">
                        <p>
                        <<&plan_status>>
                        </p>
                        <p class="ta-c lh30 hidden-xs mt10 fs14"><a href="<<lesson_link>>"><<lesson>></a> <<mid>> <a href="<<plan_manage_link>>"><<plan_manage>></a> </p>
                    </div>
            </div>
        </li>
		<</data>>
    </script>
    <footer>
    <?php echo tpl_function_part("/index.main.footer"); ?>
    </footer>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script tyee="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<script>
var arrend = [];
var arrnotend = [];
$(document).ready(function() {
    $('#list li').first().show();
    $('.list-tab>dd').click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        $('#list>li:eq(' + $(this).index() + ')').show().siblings().hide();
                })
        var loaded = true;
        //var loaded = false;
        var loadend = true;
        //var loadend = true;
        var startTime = "<?php echo SlightPHP\Tpl::$_tpl_vars["startTime"]; ?>";
        var endstartTime = "<?php echo SlightPHP\Tpl::$_tpl_vars["endstartTime"]; ?>";
        var Uid = "<?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>";
        var Page = 1;
        var Pagestart = 1;
        var domisend = $("#isEnd li:last")[0];
        var domnotend = $("#notEnd li:last")[0];
        arrend.push(domisend);
        arrnotend.push(domnotend);
        $(window).scroll(function(){
            var tops = $(window).scrollTop();
            var contents = $(window).height();
            var prec1 = getClient();
            jiance(arrend, prec1, function(){
                if(loadend){
                    Page++;
                    $.ajax({
                        type: "POST",
                        url: "/index.teacher.timetableendAjax",
                        data:{ start_time:startTime,endstart_time:endstartTime,uid:Uid,page:Page },
                        dataType: "json",
                        beforeSend: function () {
                            //$("#submit").attr({ disabled: "disabled" });
                        },
                        success: function(r){
                            datas = r;
                            var planListTpl = $('#planListisend').html();
                            var planList1 = Mustache.render(planListTpl, datas);
                            $('#isEnd').append(planList1);
                            domisend = $("#isEnd li:last")[0];
                            $('#isEnd').find('.plan-status').each(function() {
                                if($(this).attr('data-status') == 3){
                                    $(this).find('.lesson-plan-link').show();
                                }else{
                                    $(this).find('.no-plan-link').show();
                                }
                            })
                            arrend.push(domisend);
                            if(r.error){
                                //  $("#error").html(r.error);
                                alert(r.error);
                                //      location.reload();//这里未完善
                                return false;
                            }
                            if(r){
                                if(r.data.length ==0){
                                    loadend = false;
                                }
                            }
                        },
                        complete: function () {
                            //$("#submit").removeAttr("disabled");
                        },
                        error: function (r) {
                            Page--;
                            domisend = $("#isEnd li:last")[0];
                            arrend.push(domisend);
                            console.info("error: " + r.error);
                        }

                    });
                }
            })
            jiance(arrnotend, prec1, function(){
                Pagestart++;
                if(loaded){
                    $.ajax({
                        type: "POST",
                        url: "/index.teacher.timetablestartAjax",
                        data:{ start_time:startTime,endstart_time:endstartTime,uid:Uid,page:Pagestart },
                        dataType: "json",
                        beforeSend: function () {
                            //$("#submit").attr({ disabled: "disabled" });
                        },
                        success: function(r){
                            data = r;
                            var planListTpl1 = $('#planListnotend').html();
                            var planList2 = Mustache.render(planListTpl1, data);
                            $('#notEnd').append(planList2);
                            domnotend = $("#notEnd li:last")[0];
                            arrnotend.push(domnotend);
                            if(r.error){
                                //  $("#error").html(r.error);
                                alert(r.error);
                                //      location.reload();//这里未完善
                                return false;
                            }
                            if(r){
                                if(r.data.length ==0){
                                    loaded = false;
                                }
                            }
                        },
                        complete: function () {
                            //$("#submit").removeAttr("disabled");
                        },
                        error: function (r) {
                            Page--;
                            domnotend = $("#notEnd li:last")[0];
                            arrnotend.push(domnotend);
                            console.info("error: " + r.error);
                        }

                    });
                }

            })

        });


});
</script>
<script>
 jQuery(function(){
    jQuery('#timeStart').datetimepicker({
            format:'Y-m-d',
            onShow:function( ct ){
            this.setOptions({
                maxDate:jQuery('#timeEnd').val()?jQuery('#timeEnd').val():false
                })
            },
           timepicker:false
    });
jQuery('#timeEnd').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
        this.setOptions({
        minDate:jQuery('#timeStart').val()?jQuery('#timeStart').val():false
        })
        },
        timepicker:false
    });
});
</script>
</body>
    <div class="index-teac-update" id="index-teac-update">
        <div class="index-marcen">
            <p><img src="/assets_v2/img/duochangjing.png"><span>多场景上课模式</span></p>
            <p><img src="/assets_v2/img/suitang.png"><span>随堂测验</span></p>
            <p><img src="/assets_v2/img/zhibohudong.png"><span>直播互动</span></p>
       </div>
       <div class="index-marbut">
        <!--    <input type="button" onclick="down()" value="立即下载"> -->
           <a href='<?php echo SlightPHP\Tpl::$_tpl_vars["host"]; ?>' onclick="down()" target="_blank" id="lijixz">立即下载</a>
        
       </div>
    </div>
    <?php /*<div class="index-teac-bg" id="index-teac-bg" style="display:none;">
        <div class="title">
            <p>是否安装</p>
        </div>
        <div class="teac-content">
            <p>手动安装"云课教师助手"</p>
            <span>提示:下载后,找到下载包文件,双击进行手动安装</span>
            <div class="content-but">
                <input type="button" onclick="location.reload()" value="安装完成">
                <input type="button" value="遇到问题">
            </div>
        </div>
    </div>*/?>



<style>
    .index-teac-update{ display:none;}
    .layui-layer-setwin .layui-layer-close2{ top:2px;right:0;background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .layui-layer-setwin .layui-layer-close2:hover{ background:url(/assets_v2/img/mr-end.png)no-repeat;  }
    .index-teac-update{ background:url(/assets_v2/img/tcbg.png)no-repeat;width:489px;height:368px;}
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
    .content-but input:nth-child(1){ background:#ffa81e;color:#fff;margin-right:20px;border:none; }
    <?php /*.index-teac-bg{ float:left;width:100%;height:250px; }
    .index-teac-bg .title{ background:#f7f7f7;width:100%;height:35px; }
    .index-teac-bg .title p{ color:gray;font-size:14px;line-height:35px;padding-left:10px; }*/?>
</style>
<script>
    var flag = 0;
    function checkCallback(data){
        if(data["code"]==0){
          flag = 1;
        }else {

        }
    }
    function launchCallback(data){
        console.log(data['code']);
    }
    
    function down(){
        $('#layui-layer1').hide();
        $('.layui-layer-shade').hide();
    }
    //关闭弹窗
    function close(){
        
    }
    
    function checks_st(plan,cla,course){
        check();
        if(flag==1){
            jQuery.getScript("http://127.0.0.1:58891/teacher_client/?command=launch&user_name=<?php echo SlightPHP\Tpl::$_tpl_vars["mobile"]; ?>&user_token=<?php echo SlightPHP\Tpl::$_tpl_vars["token"]; ?>&course_id="+course+"&class_id="+cla+"&plan_id="+plan);
        }
        if(flag==0){
                layer.open({
                type:1,
                title:false,
                area: ['489px',''],
                content: $('#index-teac-update')
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
