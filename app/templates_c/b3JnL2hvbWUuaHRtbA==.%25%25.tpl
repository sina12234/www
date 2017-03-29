<!DOCTYPE html>
<html>
<head>
<title>机构中心 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ie8/ejs.ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<style>
    a.doubt-icon{
        cursor:default;
    }
    a.doubt-icon span.doubt-content{
    height: 30px;
    padding: 0 15px;
    line-height: 28px;
    position: absolute;
    right:-50px;
    top:30px;
    width: auto;
    background-color: #fff;
    border: 1px solid #e7e7e7;
    display: none;
    }
</style>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class='pd30'>
    <div class="container">
        <div class="row">
            <!--左侧-->
            <?php echo tpl_function_part("/org.main.menu.home"); ?>
            <!--右侧-->
            <div class="right-main col-md-16">
                <p class="fs14 time">
                    <?php echo SlightPHP\Tpl::$_tpl_vars["today"]; ?>
                </p>
                <!-- 累计收入 -->
                <div class="col-md-7 org-profile mt15">
                    <div class="org-profile-content">
                        <p class="cDarkgray fs14 c-fl"><i class="income-icon"></i><span class="c-fl ml5 fb"><?php echo tpl_modifier_tr('累计收入','org'); ?>（元）</span></p>
                        <p class="org-income-amount col-md-14 fs22 lGray">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_all)){; ?>
                            ￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_all/100)), 2); ?>
                            <?php }else{; ?>￥----<?php }; ?>
                        </p>
                        <p class="st-rang col-md-6">
                            <span><?php echo tpl_modifier_tr('日','org'); ?>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == -1){; ?>
                                    <i class="rang-down-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["dayPercent"]->percent; ?>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == 1)){; ?>
                                    <i class="rang-up-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["dayPercent"]->percent; ?>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == 0)){; ?>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["dayPercent"]->percent; ?>
                                <?php }; ?>
                            </span>
                            <span data-start="<?php echo SlightPHP\Tpl::$_tpl_vars["weekStart"]; ?>" data-end="<?php echo SlightPHP\Tpl::$_tpl_vars["weekEnd"]; ?>"><?php echo tpl_modifier_tr('周','org'); ?>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == -1){; ?>
                                    <i class="rang-down-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["weekPercent"]->percent; ?>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == 1)){; ?>
                                    <i class="rang-up-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["weekPercent"]->percent; ?>
                                <?php }elseif((SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == 0)){; ?>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["weekPercent"]->percent; ?>
                                <?php }; ?>
                            </span>
                            <span data-start="<?php echo SlightPHP\Tpl::$_tpl_vars["monthStart"]; ?>" data-end="<?php echo SlightPHP\Tpl::$_tpl_vars["monthEnd"]; ?>"><?php echo tpl_modifier_tr('月','org'); ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == -1){; ?>
                                    <i class="rang-down-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["monthPercent"]->percent; ?>
                                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == 1)){; ?>
                                    <i class="rang-up-icon"></i>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["monthPercent"]->percent; ?>
                                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == 0)){; ?>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["monthPercent"]->percent; ?>
                                    <?php }; ?>
                            </span>
                        </p>
                    </div>
                </div>
                <!-- 提现现金 -->
                <div class="org-profile col-md-7 mt15">
                    <div class="org-profile-content">
                        <p class="cDarkgray fs14"><i class="cash-icon"></i><span class="ml5 fb"><?php echo tpl_modifier_tr('可提现金额','org'); ?>（元）</span><span class="fs12 cGray"><?php echo tpl_modifier_tr('截止到上周账单','org'); ?></span></p>
                        <p class="org-income-amount provide-money col-md-20 fs22" style="color:#ff4401;">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->withdraw)){; ?>
                            ￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->withdraw/100)), 2); ?>
                            <?php }else{; ?>￥----<?php }; ?>
                        </p>
                        <a id="provide-money-btn"  href="<?php if(SlightPHP\Tpl::$_tpl_vars["wflag"] == 1){; ?>/org.settle.applywithdraw<?php }else{; ?>javascript:void(0)<?php }; ?>" class="apply-btn">
                            <?php if(SlightPHP\Tpl::$_tpl_vars["wlog"] == 1){; ?>
                            <?php echo tpl_modifier_tr('提现审核中','org'); ?>
                            <?php }else{; ?>
                            <?php echo tpl_modifier_tr('申请提现','org'); ?>
                            <?php }; ?>
                        </a>
                        <a href="/org.settle.withdraw" class="record-btn"><?php echo tpl_modifier_tr('提现记录','org'); ?></a>
                    </div>
                </div>
                <!-- 银行卡 -->
                <div class="col-md-6 bank-card-profile mt15">
                    <div class="bc-profile-content">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cardInfo"])){; ?>
                        <p class="bc-name fb fs14"><span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->bank; ?></span><span class="c-fr"><?php echo tpl_modifier_tr('尾号','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->last_no; ?></span></p>
                        <p class="bc-userinfo cGray" style="position: relative;">
                            <span class="c-fl"><?php echo tpl_modifier_tr('持卡人姓名','org'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->user; ?></span>
                            <a href="javascript:;" class="doubt-icon c-fr">
                                <span class="doubt-content"><?php echo tpl_modifier_tr('解绑银行卡联系客服','org'); ?> 400-1188-683</span>
                            </a>
                        </p>
                        <p class="bc-userinfo cGray"><span class="c-fl fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->card_no; ?></span>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["cardInfo"]->status == 0){; ?>
                        <span class="c-fr"><?php echo tpl_modifier_tr('审核中','org'); ?></span>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["cardInfo"]->status == 2)){; ?>
                        <span class="c-fr"><?php echo tpl_modifier_tr('已开通','org'); ?></span>
                        <?php }; ?>
                        </p>
                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["isAdmin"] == 1)){; ?>
                        <a class="bc-noinfo" href="/org.settle.bankcard" target="_blank"><span class="add-bc-icon"><?php echo tpl_modifier_tr('添加银行账号','org'); ?></span></a>
                        <p class="bc-noinfotips"><?php echo tpl_modifier_tr('仅限添加一张用于提现','org'); ?></p>
                    <?php }else{; ?>
                        <p class="bc-noroot"><?php echo tpl_modifier_tr('银行卡管理仅机构创建者可见','org'); ?></p>
                    <?php }; ?>
                    </div>
                </div>
                <!--统计数-->
                <p class="fs14 fb mt20 col-xs-20 pd0">关键数据统计</p>
                <ul class="org-count" id="org-count">
                    <script type="text/html" id="org-count-html">
                    <li class="org-count-li">
                      <div class="org-count-item">
                          <div class="org-count-icon"><i class="order-num-icon"></i></div>
                          <div class="org-count-num">
                              <p class="fs14">订单数</p>
                              <p class="pd5">
                                  <span class="fs16 fb"><%= data.orderCount ==0 ? '---' : data.orderCount%></span>
                                  <span class="c-fr"><% if(data.orderStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.orderStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.orderPercentage ==0 ? '---': (data.orderPercentage+'%') %></span>
                              </p>
                              <p><?php echo tpl_modifier_tr('昨日','org'); ?>:<%= data.orderAddCount %> <a href="/org.main.order" class="c-fr white-link"><?php echo tpl_modifier_tr('详情','org'); ?> ></a></p>
                          </div>
                      </div>
                    </li>
                    <li class="org-count-li">
                      <div class="org-count-item">
                          <div class="org-count-icon"><i class="course-num-icon"></i></div>
                          <div class="org-count-num">
                            <p class="fs14">课程总数</p>
                            <p class="pd5">
                                <span class="fs16 fb"><%= data.courseCount ==0 ? '---': data.courseCount%></span>
                                <span class="c-fr"><% if(data.courseStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.courseStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.coursePercentage ==0 ? '---': (data.coursePercentage+'%')%></span>
                            </p>
                            <p><?php echo tpl_modifier_tr('昨日','org'); ?>:<%= data.courseAddCount %> <a href="/user.org.course" class="c-fr white-link"><?php echo tpl_modifier_tr('详情','org'); ?> ></a></p>
                          </div>
                      </div>
                    </li>
                    <li class="org-count-li">
                      <div class="org-count-item">
                          <div class="org-count-icon"><i class="student-num-icon"></i></div>
                          <div class="org-count-num">
  					        <p class="fs14">报名学生人数</p>
  						    <p class="pd5">
                                <span class="fs16 fb"><%= data.enrollCount ==0 ? '---': data.enrollCount%></span>
                                <span class="c-fr"><% if(data.enrollStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.enrollStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.enrollPercentage == 0 ? '---': (data.enrollPercentage+'%')%></span>
                            </p>
  						    <p><?php echo tpl_modifier_tr('昨日','org'); ?>:<%= data.enrollAddCount %><a href="/org.student.list" class="c-fr white-link"><?php echo tpl_modifier_tr('详情','org'); ?> ></a></p>
  					    </div>
                      </div>
                    </li>
                    </script>
                </ul>
                <table class="table-grid" >
                    <thead>
                        <tr>
                            <td class="col-xs-4 tal">统计项</td>
                            <td class="col-xs-4 tal">总数</td>
                            <td class="col-xs-4 tal">昨天</td>
                            <td class="col-xs-4 tal">变化</td>
                            <td class="col-xs-4 tal">操作</td>
                        </tr>
                    </thead>
                    <tbody id="count-list">
                        <script type="text/html" id="count-html">
                        <tr>
                            <td class="col-xs-4 tal">总收入</td>
                            <td class="col-xs-4 tal"><%= data.incomeCount == 0 ? '---' : data.incomeCount%></td>
                            <td class="col-xs-4 tal"><%= data.incomeAddCount == 0 ? '---' : data.incomeAddCount %></td>
                            <td class="col-xs-4 tal"><% if(data.incomeStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.incomeStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.incomePercentage == 0 ? '---' : (data.incomePercentage+'%')%></td>
                            <td class="col-xs-4 tal"><a href="/org.settle">详细</a></td>
                        </tr>
                        <tr>
                            <td class="col-xs-4 tal">注册用户数</td>
                            <td class="col-xs-4 tal"><%= data.regUserCount == 0 ? '---' : data.regUserCount %></td>
                            <td class="col-xs-4 tal"><%= data.regUserAddCount == 0 ? '---' : data.regUserAddCount %></td>
                            <td class="col-xs-4 tal"><% if(data.regUserStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.regUserStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.regUserPercentage == 0 ? '---' : (data.regUserPercentage+'%')%></td>
                            <td class="col-xs-4 tal"><a href="/org.student.list">详细</a></td>
                        </tr>
                        <tr>
                            <td class="col-xs-4 tal">教师数</td>
                            <td class="col-xs-4 tal"><%= data.teacherCount == 0 ? '---' : data.teacherCount%></td>
                            <td class="col-xs-4 tal"><%= data.teacherAddCount == 0 ? '---' : data.teacherAddCount%></td>
                            <td class="col-xs-4 tal"><% if(data.teacherStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.teacherStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.teacherPercentage == 0 ? '---' : (data.teacherPercentage+'%')%></td>
                            <td class="col-xs-4 tal"><a href="/org.teacher.list">详细</a></td>
                        </tr>
                        <tr>
                            <td class="col-xs-4 tal">视频播放次数</td>
                            <td class="col-xs-4 tal"><%= data.vv == 0 ? '---' : data.vv%></td>
                            <td class="col-xs-4 tal"><%= data.vvAdd == 0 ? '---' : data.vvAdd %></td>
                            <td class="col-xs-4 tal"><% if(data.vvStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.vvStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.vvPercentage == 0 ? '---' : (data.vvPercentage+'%')%></td>
                            <td class="col-xs-4 tal"><a href="/org/stat/TrafficStatistical">详细</a></td>
                        </tr>
                        <tr>
                            <td class="col-xs-4 tal">播放时长</td>
                            <td class="col-xs-4 tal"><%= data.vt == 0 ? '---' : data.vt%></td>
                            <td class="col-xs-4 tal"><%= data.vtAdd == 0 ? '---' : data.vtAdd%></td>
                            <td class="col-xs-4 tal"><% if(data.vtStatus==1){ %><i class="rang-up-icon"></i><% } else if(data.vtStatus==2){ %><i class="rang-down-icon"></i><%}%><%= data.vtPercentage == 0 ? '---' : (data.vtPercentage+'%')%></td>
                            <td class="col-xs-4 tal"><a href="/org/stat/ContentStatistical#length">详细</a></td>
                        </tr>
                        </script>
                    </tbody>
                </table>
                <!--排班-->
                <div class="tab-main mt30 c-fl" id="switch-bar">
                    <a href="javascript:void(0)" class="tab-hd-opt curr"><?php echo tpl_modifier_tr('今日排班','org'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["todayCount"]; ?>)</a>
                    <a href="javascript:void(0)" class="tab-hd-opt"><?php echo tpl_modifier_tr('明日排班','org'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["tomorrowCount"]; ?>)</a>
                </div>
                <!--p class="pd10 col-xs-20">
                    <a href="javascript:void(0);" class="pl10 theme-link">全部</a><a href="javascript:void(0);" class="pl10">直播</a><a href="#" class="pl10">未开课</a><a href="#" class="pl10">已完成</a>
                </p-->
                <!-- 机构排课 -->
                <div class="c-fl col-xs-20 pd0 mt10" id="time-table">
                    <ul class="org-timetable">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["todayPlanList"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["todayPlanList"] as SlightPHP\Tpl::$_tpl_vars["td"]){; ?>
                        <li class="col-md-10">
                            <p class="col-md-10 c-img"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["td"]->fk_course; ?>" target="_blank"><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["td"]->thumb_med); ?>" alt=""></a></p>
                            <p class="col-md-10 c-info">
                                <span class="fs14"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["td"]->fk_course; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["td"]->title; ?></a></span>
                                <span class="cGray"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["td"]->class_name,'site.index'); ?> <?php echo tpl_modifier_tr('主讲','org'); ?>：<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["td"]->fk_user_plan]['real_name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["td"]->fk_user_plan]['real_name']; ?><?php }; ?></span>
                                <span class="cGray"><?php echo tpl_modifier_tr('共','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["td"]->section_count; ?><?php echo tpl_modifier_tr('节','org'); ?> <?php echo tpl_modifier_tr('进度','org'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["td"]->section_name,'site.index'); ?></span>
                                <a href="/course.plan.start/<?php echo SlightPHP\Tpl::$_tpl_vars["td"]->pk_plan; ?>" target="_blank" class="blue-link">进入巡课教室</a>
                                <a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["td"]->pk_plan; ?>" target="_blank" class="blue-link">进入学生教室</a>
                            </p>
                        </li>
						<?php }; ?>
						<?php }else{; ?>
						<div class="my-collect-no-class" style="padding-top:45px;">
							<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
							<p class="cGray fob">您还没有今日的排班哦！</p>
						</div>
                        <?php }; ?>
                    </ul>
                    <ul class="org-timetable" style="display:none">
					    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tomorrowPlanList"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["tomorrowPlanList"] as SlightPHP\Tpl::$_tpl_vars["tt"]){; ?>
                        <li class="col-md-10">
                            <p class="col-md-10 c-img"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->fk_course; ?>" target="_blank"><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["tt"]->thumb_med); ?>" alt=""></a></p>
                            <p class="col-md-10 c-info">
                                <span class="fs14"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->fk_course; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->title; ?></a></span>
                                <span class="cGray"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["tt"]->class_name,'site.index'); ?> <?php echo tpl_modifier_tr('主讲','org'); ?>：<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["tt"]->fk_user_plan]['real_name'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teachers"][SlightPHP\Tpl::$_tpl_vars["tt"]->fk_user_plan]['real_name']; ?><?php }; ?></span>
                                <span class="cGray"><?php echo tpl_modifier_tr('共','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->section_count; ?><?php echo tpl_modifier_tr('节','org'); ?> <?php echo tpl_modifier_tr('进度','org'); ?> <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["tt"]->section_name,'site.index'); ?></span>
                                <!--i class="c-progress col-md-10"><i style="width:50%"></i></i-->
                                <a href="/course.plan.start/<?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->pk_plan; ?>" target="_blank" class="blue-link pt10">进入巡课教室</a>
                                <a href="/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["tt"]->pk_plan; ?>" target="_blank" class="blue-link pt10">进入学生教室</a>
                            </p>
                        </li>
					    <?php }; ?>
					    <?php }else{; ?>
						<div class="my-collect-no-class" style="padding-top:45px;">
							<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
							<p class="cGray fob">您还没有明日的排班哦！</p>
						</div>
                        <?php }; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script>
    $(function(){
        var orgCount = $('#org-count');
        var countList = $('#count-list');
        $.ajax('/org/stat/KeyIndicator',{
            type:'get',
            dataType:'json',
            success:function (data) {
                if(data.code == 0){
                    var countHtml = $('#count-html').html();
                    var orgcountHtml= $('#org-count-html').html();
                    countList.html(ejs.render(countHtml,{ data:data.data }));
                    orgCount.html(ejs.render(orgcountHtml,{ data:data.data }));
                }
            }
        });
        $('#switch-bar a').click(function(){
            $(this).addClass('curr');
            $(this).siblings().removeClass('curr');
            $('#time-table').find("ul:eq("+$(this).index()+")").show().siblings('ul').hide()
        })

        var money = $('p.provide-money').text();
        var moneyNumber=$.trim(money.slice(money.indexOf('￥')+1));
        if(moneyNumber=='----' ||!(Number(moneyNumber)>0) ){
            $('#provide-money-btn').css({
                'color':'#ccc',
                'cursor':'default',
                'text-decoration':'none'
            }).on({
                mouseover:function () {
                    $(this).css('color','#ccc');
                },
                click:function () {
                    return false;
                }
            })
        }
        $('a.doubt-icon').on({
            mouseover:function () {
                $(this).find('span.doubt-content').css('display','block');
            },
            mouseout:function () {
                $(this).find('span.doubt-content').css('display','none');
            }
        })
    })
</script>
