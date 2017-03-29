<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>如何上课 - <?php echo tpl_function_part('/site.main.orgname'); ?> -  云课 - 专业的在线学习平台</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<!--header-->
<header>
<?php echo tpl_function_part("/site.main.usernav.teacher"); ?>
</header>
<section class="pd30">
    <div class="container">
    <div class="row">
    <!-- leftmenu start-->
		<?php echo tpl_function_part("/user.main.menu.teacher.help"); ?>
    <!-- leftmenu end -->
        <div class="right-main col-md-16 col-xs-20 col-sm-20">
             <p class="mob-nav hidden-lg hidden-md">
                 <a href="/teacher.manage.edit" class="col-xs-5">教师资料</a>
                 <a href="/teacher.course.timetable" class="col-xs-5">我的课程</a>
                 <a href="/teacher.manage.student" class="col-xs-5">我的学生</a>
                 <a href="/teacher.course.help" class="col-xs-5 ">如何上课</a>
            </p>
            <div class="content">
                    <h1 class="fs16 fob"><?php echo tpl_modifier_tr('如何上课','site.teacher'); ?></h1>
                    <div class="help-title fs16"><?php echo tpl_modifier_tr('第一步：下载“云课教师助手”客户端','site.teacher'); ?></div>
                    <ul class="help-main fs14">
                        <li>
                            <div class="help-c ta-c" style="width:70%"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/help-img5.jpg'); ?>"></div>
                            <div style="margin-top:20px;width:30%" class="c-fr">
                                <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["download_url"]; ?>" class="help-btn" /><?php echo tpl_modifier_tr('点击下载','site.teacher'); ?></a>
                                <p style="width:80%;text-align:center;float:left"><?php echo tpl_modifier_tr('支持win7、win8系统','site.teacher'); ?></p>
                            </div>
                        </li>
                    </ul>
                    <div class="help-title fs16"><?php echo tpl_modifier_tr('第二步：安装“客户端”并登录账号','site.teacher'); ?></div>
                    <ul class="help-main fs14">
                        <li>
                            <div class="help-c">2-1 <?php echo tpl_modifier_tr('双击“安装包”安装客户端--立即安装--完成','site.teacher'); ?></div>
                            <div class="help-c ta-c"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/help-img4.jpg'); ?>"></div>
                        </li>
                        <li>
                            <div class="help-c">2-2 <?php echo tpl_modifier_tr('登录客户端--输入账号，密码','site.teacher'); ?></div>
                            <div class="help-c ta-c"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/help-img2.png'); ?>"></div>
                        </li>
                    </ul>
                    <div class="help-title fs16"><?php echo tpl_modifier_tr('第三步：查看我的课表--点击“开始上课”','site.teacher'); ?></div>
                    <ul class="help-main fs14">
                        <li>
                            <div class="help-c ta-c"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/help-img1.png'); ?>"></div>
                        </li>
                    </ul>
                    <div class="help-title fs16"><?php echo tpl_modifier_tr('第四步：进入教室--点击“上课”','site.teacher'); ?></div>
                    <ul class="help-main fs14">
                        <li>
                            <div class="help-c ta-c"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/help-img3.png'); ?>"></div>
                        </li>
                    </ul>
            </div>
            <div class='clear'></div>
        </div>
    </div>
    </div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<footer>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</footer>
</body>
    <script>
        $("#click-but").click(function(){
            if ($("#obs").is(":hidden")) {
                $("#obs").slideDown(500);
            }else{
                $("#obs").slideUp(500);
            }
        })
    </script>
</html>
