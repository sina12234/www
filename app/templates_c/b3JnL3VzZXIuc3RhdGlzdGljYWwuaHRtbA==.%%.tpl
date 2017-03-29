<!DOCTYPE html>
<html>
<head>
<title>用户统计 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/echarts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class='pd30'>
    <div class="container">
        <div class="row">
            <!--左侧-->
            <?php echo tpl_function_part("/org.main.menu.stat"); ?>
            <!--右侧-->
            <div class="right-main col-md-16">
                <div class="tab-main">
                        <div class="tab-hd fs14">
                            <a class="tab-hd-opt curr" href="#">用户统计</a>
                            <a class="tab-hd-opt" href="/org/stat/TrafficStatistical">流量统计</a>
                            <a class="tab-hd-opt" href="/org/stat/ContentStatistical">内容统计</a>
                        </div>
                    </div>

                <p class="fs14 fb mt20 mb10 pd0 titleP">关键数据统计</p>
                <ul class="org-count org-chart col-lg-20 liHtml pd0">
                        <script id="liHtml" type="text/template">
                        <li class="col-lg-4 org-item ">
                                <div class="org-count-titlel c1">
                                    注册用户
                                    <i class="icon-que-count" title="本机构注册页，用户注册成功的总数"></i>
                                </div>
                                <div class="org-count-com c1">
                                    <p class="fs24">
                                        <%if(data.regUserCount == 0){ %>
                                        <a href="javascript:void(0);" title="暂无数据" class="fs24 fb cWhite">---</a>
                                        <% }else{ %>
                                        <a href="javascript:void(0);" title="总数" class="fs24 fb cWhite"><%=data.regUserCount%></a>
                                        <% }%>
                                    </p>
                                    <% if(data.regUserStatus==1) { %>
                                    <i class="rang-up-icon"></i>
                                    <% }else if(data.regUserStatus==2){ %>
                                    <i class="rang-down-icon"></i>
                                    <% }else{ %>
                                    <% }%>
                                    <%if(data.regUserPercentage == 0){ %>
                                    <span href="javascript:void(0);" title="暂无数据" class="fs12 cWhite">---</span>
                                    <% }else{ %>
                                    <span href="javascript:void(0);" title="昨日与前日增长百分比" class="fs12 cWhite"><%=data.regUserPercentage%></span>
                                    <% }%>
                                </div>
                        </li>
                        <li class="col-lg-4 org-item">
                                <div class="org-count-titlel c2">
                                    教师总数
                                    <i class="icon-que-count" title="本机构下添加为教师身份的总人数"></i>
                                </div>
                                <div class="org-count-com c2">
                                    <p class="fs24">
                                        <%if(data.teacherCount == 0){ %>
                                        <a href="javascript:void(0);" title="暂无数据" class="fs24 fb cWhite">---</a>
                                        <% }else{ %>
                                        <a href="javascript:void(0);" title="总数" class="fs24 fb cWhite"><%=data.teacherCount%></a>
                                        <% }%>
                                    </p>
                                    <% if( data.teacherStatus==1 ) { %>
                                    <i class="rang-up-icon"></i>
                                    <% }else if( data.teacherStatus==2 ){ %>
                                    <i class="rang-down-icon"></i>
                                    <% }%>
                                    <%if(data.teacherPercentage == 0){ %>
                                    <span href="javascript:void(0);" title="暂无数据" class="fs12">---</span>
                                    <% }else{ %>
                                    <span href="javascript:void(0);" title="昨日与前日增长百分比" class="fs12"><%=data.teacherPercentage%></span>
                                    <% }%>
                                </div>
                        </li>
                        <li class="col-lg-4 org-item">
                            <div class="org-count-titlel c3">
                                    报名学生人次
                                    <i class="icon-que-count" title="本机构下报名课程的学生人次的总数"></i>
                            </div>
                            <div class="org-count-com c3">
                                    <p class="fs24">
                                        <%if(data.enrollCount == 0){ %>
                                        <a href="javascript:void(0);" title="暂无数据" class="fs24 fb cWhite">---</a>
                                        <% }else{ %>
                                        <a href="javascript:void(0);" title="总数" class="fs24 fb cWhite"><%=data.enrollCount%></a>
                                        <% }%>
                                    </p>
                                    <% if( data.enrollStatus==1 ) { %>
                                    <i class="rang-up-icon"></i>
                                    <% }else if( data.enrollStatus==2 ){ %>
                                    <i class="rang-down-icon"></i>
                                    <% }else{ %>
                                    <i></i>
                                    <% }%>
                                    <%if(data.enrollPercentage == 0){ %>
                                    <span href="javascript:void(0);" title="暂无数据" class="fs12">---</span>
                                    <% }else{ %>
                                    <span href="javascript:void(0);" title="昨日与前日增长百分比" class="fs12"><%=data.enrollPercentage%></span>
                                    <% }%>
                            </div>
                        </li>
                        <li class="col-lg-4 org-item">
                                <div class="org-count-titlel c4">
                                    激活用户
                                    <i class="icon-que-count" title="本机构注册或导入成功并登录的用户总数"></i>
                                </div>
                                <div class="org-count-com c4">
                                    <p class="fs24">
                                        <%if(data.activeUserCount == 0){ %>
                                        <a href="javascript:void(0);" title="暂无数据" class="fs24 fb cWhite">---</a>
                                        <% }else{ %>
                                        <a href="javascript:void(0);" title="总数" class="fs24 fb cWhite"><%=data.activeUserCount%></a>
                                        <% }%>
                                    </p>
                                    <% if( data.activateStudentStatus==1 ) { %>
                                    <i class="rang-up-icon"></i>
                                    <% }else if( data.activateStudentStatus==2 ){ %>
                                    <i class="rang-down-icon"></i>
                                    <% }else{ %>
                                    <i></i>
                                    <% }%>
                                    <%if(data.activateStudentPercentage == 0){ %>
                                    <span href="javascript:void(0);" title="暂无数据" class="fs12">---</span>
                                    <% }else{ %>
                                    <span href="javascript:void(0);" title="昨日与前日增长百分比" class="fs12"><%=data.activateStudentPercentage%></span>
                                    <% }%>
                                </div>
                        </li>
                        <li class="col-lg-4 org-item">
                                <div class="org-count-titlel c5">
                                    导入用户
                                    <i class="icon-que-count" title="本机构导入的用户总数"></i>
                                </div>
                                <div class="org-count-com c5">
                                    <p class="fs24">
                                        <%if(data.importUserCount == 0){ %>
                                        <a href="javascript:void(0);" title="暂无数据" class="fs24 fb cWhite">---</a>
                                        <% }else{ %>
                                        <a href="javascript:void(0);" title="总数" class="fs24 fb cWhite"><%=data.importUserCount%></a>
                                        <% }%>
                                    </p>
                                    <% if( data.importUserStatus==1 ) { %>
                                    <i class="rang-up-icon"></i>
                                    <% }else if( data.importUserStatus==2 ){ %>
                                    <i class="rang-down-icon"></i>
                                    <% }%>
                                    <%if(data.importUserPercentage == 0){ %>
                                    <span href="javascript:void(0);" title="暂无数据" class="fs12">---</span>
                                    <% }else{ %>
                                    <span href="javascript:void(0);" title="昨日与前日增长百分比" class="fs12"><%=data.importUserPercentage%></span>
                                    <% }%>
                                </div>
                        </li>
                        </script>
                    </ul>

                    <form id="ftime" action="/org/stat/UserTrend" method="post">
                        <div class="bc-path col-md-20 mt20 fs14">
                            <div class="c-fl">
                                <select name="charts_type" id="charts_type">
                                    <option value="regUser"><?php echo tpl_modifier_tr('注册用户','org'); ?></option>
                                    <option value="activeUser"><?php echo tpl_modifier_tr('激活用户','org'); ?></option>
                                    <option value="importUser"><?php echo tpl_modifier_tr('导入用户','org'); ?></option>
                                    <option value="teacher"><?php echo tpl_modifier_tr('教师数','org'); ?></option>
                                    <option value="activeTeacher"><?php echo tpl_modifier_tr('激活教师数','org'); ?></option>
                                    <option value="videoTeacher"><?php echo tpl_modifier_tr('有视频教师数','org'); ?></option>
                                    <option value="enroll"><?php echo tpl_modifier_tr('报名学生数','org'); ?></option>
                                    <option value="payEnroll"><?php echo tpl_modifier_tr('收费课报名人数','org'); ?></option>
                                </select>
                            </div>
                            <div class="time-picker">
                                <input name="start" type="text" placeholder="开始时间"  id="time-picker" value="2016-07-05">
                                <i class=" settle-arrow-icon settle-arrow-icon-left"></i>
                                <span>至</span>
                                <input name="end" type="text" placeholder="结束时间" id="time-picker2" value="2016-08-03">
                                <i class="settle-arrow-icon settle-arrow-icon-right"></i>
                            </div>
                        </div>
                    </form>
                    <div  id="main" class="col-md-20 pd0" style="height: 400px;" ></div>

                    <p>
                        <span class="fb fs14"><?php echo tpl_modifier_tr('数据表格','org'); ?></span>
                        <a id="exportTableData" href="javascript:void(0);" class="blue-link c-fr"><?php echo tpl_modifier_tr('导出','org'); ?> Excel</a>
                    </p>
                    <table class="table-grid mt5" id="chanelList">
                        <thead>
                        <tr>
                            <td class="col-md-5"><?php echo tpl_modifier_tr('时间','org'); ?></td>
                            <td class="col-md-6" id="selectCheck"><?php echo tpl_modifier_tr('注册用户（总）','org'); ?></td>
                            <td class="col-md-6"><?php echo tpl_modifier_tr('当日 ','org'); ?><span class="question-icon" title="当日增长数：今日与昨日总数的差"></span></td>
                            <td class="col-md-3"><?php echo tpl_modifier_tr('对比昨日变化 ','org'); ?><span class="question-icon" title="当日与昨日增长数的百分比"></span></td>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <script id="trList" type="text/template">
                        <% if(data){ data.forEach(function(list){ %>
                        <tr>
                            <td class="col-md-5"><%= list.day %></td>
                            <td class="col-md-6"><%= list.count %></td>
                            <td class="col-md-6"><%= list.addCount %></td>
                            <td class="col-md-3">
                                <% if(list.percentage!=0&&list.status==2){ %>
                                <i class="rang-down-icon"></i>
                                <% }else if(list.percentage!=0&&list.status==1){ %>
                                <i class="rang-up-icon"></i>
                                <% }%>
                                <%=(list.percentage!=0)?(list.percentage+'%'):'---' %>
                            </td>
                        </tr>
                        <% })}%>
                    </script>
                    <div id="pager" class="mt10 c-fr"></div>
            </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ie8/ejs.ie8.js'); ?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<script type="text/javascript">
    require.config({
        paths: {
            echarts: "<?php echo utility_cdn::jsurl('/assets_v2/js'); ?>"
        }
    });

$(function() {
    var length=10;
    var myDate = new Date();
    var mytime=myDate.getTime();
    var endtime =mytime-86400000;
    var startime=mytime-86400000*7;
    endtime=new Date(endtime);
    endtime=endtime.toLocaleDateString();
    endtime=endtime.replace(/\//g,'\-');
    endtime=endtime.replace(/[年月]/g,'\-').replace(/[日]/g,'');
    startime=new Date(startime);
    startime=startime.toLocaleDateString();
    startime=startime.replace(/\//g,'\-');
    startime=startime.replace(/[年月]/g,'\-').replace(/[日]/g,'');
    $("#time-picker").val(startime);
    $("#time-picker2").val(endtime);
    var params={
        startDay:$("#time-picker").val(),
        endDay:$("#time-picker2").val(),
        act:$("#charts_type").val()
    };
    var legendName = "注册用户";
    var Hash=window.location.hash;
    if(Hash){
        Hash=Hash.replace('#','');
        switch (Hash) {
            case "regUser":
                legendName = "注册用户";
                break;
            case "activeUser":
                legendName = "激活用户";
                break;
            case "importUser":
                legendName = "导入用户";
                break;
            case "teacher":
                legendName = "教师数";
                break;
            case "activeTeacher":
                legendName = "激活教师数";
                break;
            case "videoTeacher":
                legendName = "有视频教师数";
                break;
            case "enroll":
                legendName = "报名学生数";
                break;
            case "payEnroll":
                legendName = " 收费课报名人数";
                break;
            default:
                break;

        }
        $("#selectCheck").html(legendName+'(总)');
        $("select").val(Hash);
    }
    chartData();
    indexData();
    TableData();
    $("#charts_type").on('change',function(){
        params.startDay=$("#time-picker").val();
        params.endDay=$("#time-picker2").val(),
        params.act=$(this).val();
        switch (params.act) {
            case "regUser":
                legendName = "注册用户";
                break;
            case "activeUser":
                legendName = "激活用户";
                break;
            case "importUser":
                legendName = "导入用户";
                break;
            case "teacher":
                legendName = "教师数";
                break;
            case "activeTeacher":
                legendName = "激活教师数";
                break;
            case "videoTeacher":
                legendName = "有视频教师数";
                break;
            case "enroll":
                legendName = "报名学生数";
                break;
            case "payEnroll":
                legendName = " 收费课报名人数";
                break;
            default:
                break;

        }
        $("#selectCheck").html(legendName+'(总)');
        chartData();
        TableData();

    });
    // echarts 从后台获取数据并显示
    function chartData(){
        params={
            startDay:$("#time-picker").val(),
            endDay:$("#time-picker2").val(),
            act:$("select").val()
        };
        $.ajax({
            url: "/org/stat/UserTrend",
            type: "post",
            data: params,
            dataType: "json",
            success: function(data){
                var day=[];
                var count = [];

                 $.each(data.data,function(){
                   var item = this;
                   day.push(item.day);
                   count.push(item.addCount);
                 });

                require(
                        [
                            'echarts',
                            'echarts/chart/line',
                        ],
                        function (ec) {
                            var myChart = ec.init(document.getElementById('main'));
                            var option = {
                                tooltip : {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data:['日增变化']
                                },
                                xAxis : [
                                    {
                                        data : day
                                    }
                                ],
                                yAxis : [
                                ],
                                series : [
                                    {
                                        name:legendName,
                                        type:'line',
                                        smooth:true,
                                        data:count
                                    }
                                ]
                            };

                            myChart.setOption(option);
                        }
                );
            }
        });
    }

    function getTimeByDateStr(dateStr){
        var year = parseInt(dateStr.substring(0,4));
        var month = parseInt(dateStr.substring(5,7),10)-1;
        var day = parseInt(dateStr.substring(8,10),10);
        return new Date(year, month, day).getTime();
    }

    $("#time-picker").datetimepicker({
        format: 'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                maxDate:$('#time-picker2').val()?$('#time-picker2').val():false
            })
        },
        timepicker: false
    }).on('change',function(){
            params.startDay=$("#time-picker").val();
            chartData();
            TableData();
    });
    $("#time-picker2").datetimepicker({
        format: 'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                maxDate:$('#time-picker').val()?$('#time-picker').val():false,
            })
        },
        timepicker: false
    }).on('change',function(){
        params.endDay=$("#time-picker2").val();
        chartData();
        TableData();
    });

//导出excel
    $("#exportTableData").click(function(){
        var str  = '';
        var startDay = $("#time-picker").val();
        var endDay   = $("#time-picker2").val();
        var act=$("#charts_type").val();
        str+="startDay="+startDay+"&endDay="+endDay+"&act="+act
        location.href = "/phpexcel/dayorgstat?" + str;
    });
})


    //用户统计关键指标
    function indexData(){
        $.ajax({
            url:'/org/stat/UserKeyIndicator',
            dataTYpe:'json',
            type:'post',
            success:function(data) {
                var data = JSON.parse(data);
                if (data.code == 0) {
                    var liHtml = $("#liHtml").html();
                    var html = ejs.render(liHtml, { data: data.data});
                    $('body .liHtml').html(html);

                }else{
                    var data={
                        "regUserCount":"0",
                        "teacherCount":"0",
                        "enrollCount":"0",
                        "activeUserCount":"0",
                        "importUserCount":"0",
                        "regUserPercentage":0,
                        "regUserStatus":0,
                        "teacherPercentage":0,
                        "teacherStatus":0,
                        "enrollPercentage":0,
                        "enrollStatus":0,
                        "activateStudentPercentage":0,
                        "activateStudentStatus":0,
                        "importUserPercentage":0,
                        "importUserStatus":0
                    }
                    var liHtml = $("#liHtml").html();
                    var html = ejs.render(liHtml, { data: data});
                    $('body .liHtml').html(html);
                }
            }
        })
    }
    function TableData(curr) {
    var page = curr || 1;
        params={
            startDay:$("#time-picker").val(),
            endDay:$("#time-picker2").val(),
            act:$("#charts_type").val()
        };
        var Params={ };
        Params=params;
        Params.page=page;
        Params.length=10;
        $.ajax({
            url: "/org/stat/UserTable",
            type: 'post',
            dataType: 'json',
            data: Params,
            success: function (data) {
                if (data.code == 0) {
                    var trList = $("#trList").html();
                    var html = ejs.render(trList, { data: data.data.list});
                    $("#chanelList tbody").html(html);
                    laypage({
                        cont: $("#pager"),
                        pages: data.data.totalPage,
                        curr: curr || 1,
                        jump: function(obj, first){
                            if(!first){
                                TableData(obj.curr);
                            }
                        }
                    });
                }
            }
        });

    }
</script>
