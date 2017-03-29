<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> - 教师相册 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  教师首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  云课(Yunke.com) - 专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/teacher/detail/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/style");</script>
<article class="th_pic_new container pd40">
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
    <ul class="col-md-18 col-md-offset-1 mt20">
        <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
        <li  name="upImg" class="col-md-4" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" imgId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>" >
            <a href="/teacher/detail/imgInfo/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb_med']; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['name']; ?>"/>
                <p class=".length_sl"><?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["v"]['name']); ?></p>
            </a>
        </li>
        <?php }; ?>
    </ul>
    <?php }else{; ?>
    <!--没有课程时状态-->
    <div class="col-md-8 col-md-offset-6 mt20 tec pd45">
        <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
        <p class="mt10 c_a3 fs16"  >还没来得及添加哦 ...</p>
    </div>
    <?php }; ?>
</article>
<!-- /content -->
<!-- 内容结束 -->
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>