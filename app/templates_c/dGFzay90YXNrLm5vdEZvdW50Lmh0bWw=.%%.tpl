<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 布置作业 - 云课 - 专业的在线学习平台">
    <meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <title class="head-title">作业不存在</title>
    <?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<!-- body -->
<section id="look-result-task">
	<div class="container">
		<div class="row main-center pt-main-center" >
            <div class="list-tu" style="display:block">
            <div class="list-img">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                <div class="list-book">
                    <span>呀，这份作业不存在~</span>
                </div>
            </div>
           </div>
        </div>
    </div>
</section>
<script>
    var type = $.getUrlParam('type');
    if(type == 'noWork'){
          $('#look-result-task').find('.list-book span').text('已经没有作业可以批改了!');
        location.href = '/task/publishTask/teacherTaskList';
    }
</script>
<!-- /body -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
