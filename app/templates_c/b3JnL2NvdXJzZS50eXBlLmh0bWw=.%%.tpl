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
<body>
<!--header-->
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40 p-xs-0">
    <div class="container">
    <div class="row">
        <div class="right-main col-md-20 col-xs-20">
            <div class="content">
                <h1 class="fs14">
                    <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["sourceUrl"]; ?>" class="cGray">
                        <?php echo tpl_modifier_tr('返回','site.course'); ?>
                    </a>
                     > <?php echo tpl_modifier_tr('课程类型','site.course'); ?>
                </h1>
                <div class="col-md-20">
                    <p class="tac fs22 mb20"><?php echo tpl_modifier_tr('请选择您要建的课程类型','site.course'); ?></p>
                    <div class="user-create-course clearfix">
					<div class="col-md-10 p0 col-xs-10">
						<a href="/org.course.add.1" class="u-direct-seed c-fl">直播课</a>
					</div>
                        <div class="c-fr col-md-10 p0 col-xs-10">
                            <a href="/org.course.add.2" class="u-record">录播课</a>
                            <a href="/org.course.add.3" class="u-line">线下课</a>
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
