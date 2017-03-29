<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>创建课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script src="<?php echo utility_cdn::js('/assets_v2/js/commonfun.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;">
<!--header-->
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40">
    <div class="container">
    <div class="row">
		<?php echo tpl_function_part("/org.main.menu.course"); ?>
        <div class="right-main col-sm-9 col-md-16">
            <div class="content">
                <h1 class="fs14"><a href="/user.org.course" class="cGray"><?php echo tpl_modifier_tr('返回','site.course'); ?></a> > <?php echo tpl_modifier_tr('新建课程','site.course'); ?></h1>
            <!-- 旧版 
                <div class="class-add">
                    <p class="tac fs22"><?php echo tpl_modifier_tr('请选择您要建的课程类型','site.course'); ?></p>
                    <div class="col-md-6">
                        <a href="/user.org.courseinfo?type=1" class="class-type">
                            <span class="v3"></span>
                        </a>
                    <?php echo tpl_modifier_tr('直播课','site.course'); ?>
                    </div>
                    <div class="col-md-6">
                        <a href="/user.org.courseinfo?type=2" class="class-type">
                            <span class="v2"></span>
                        </a>
                    <?php echo tpl_modifier_tr('录播课','site.course'); ?>
                    </div>
                    <div class="col-md-6">
                        <a href="/user.org.courseinfo?type=3" class="class-type">
                            <span class=""></span>
                        </a>
                    <?php echo tpl_modifier_tr('线下课','site.course'); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
             /旧版 -->
            <!-- new -->
                <div class="col-md-20">
                    <p class="tec fs22 mb20"><?php echo tpl_modifier_tr('请选择您要建的课程类型','site.course'); ?></p>
                    <div class="user-create-course clearfix">
                        <a href="/user.org.courseinfo.1" class="u-direct-seed c-fl">直播课</a>
                        <div class="c-fr u-fr">
                            <a href="/user.org.courseinfo.2" class="u-record">录播课</a>
                            <a href="/user.org.courseinfo.3" class="u-line">线下课</a>
                        </div>
                    </div>  
                </div>
            <!-- /new -->
            </div>
        </div>
    </div>
    <div class="clear"></div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
