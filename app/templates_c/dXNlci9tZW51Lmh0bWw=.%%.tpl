<div style="padding-right:15px">
<style>
	.left-2 ul{
		display:none;
	}
	.left-2 .cc{
		cursor: pointer;
	}
</style>
<div class='hidden-xs'>
	<div class='top_hea'>
		<div class='left_1' style="padding:10px;">
			<img width="50" height="50" src="<?php echo SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->avatar->medium; ?>"/>
		</div>
		<div clas='left_2' style="margin-top:22px;">
			<a href=""><span><?php echo SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->name; ?></span></a><?php /*<img src="<?php echo utility_cdn::img('/assets/images/left_2tb.jpg'); ?>" style="display: inline-block;padding-left: 5px;padding-top: 5px;">*/?>
		</div>
		<div clas='left_3' style="margin-top:5px;">
			<p style="color:#BCBCBC;"><?php echo tpl_modifier_default(SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->profile->desc,"TA还没写简介..."); ?></p>
		</div>
		<div class="left_4" style="margin-top:5px;">
			<img src="<?php echo utility_cdn::img('/assets/images/wz.jpg'); ?>" style="float:left;margin-top:2px;margin-right:5px;"><p style="color:#BCBCBC;"><?php echo SlightPHP\Tpl::$_tpl_vars["ipinfo"]->area_name; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["ipinfo"]->op_name; ?></p>
		</div>
	</div>
</div>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->types->organization)){; ?>
<!--机构权限 start-->
<div class='left-2'>
	<p class="cc" id=''>
	
	<a class="safety"><span class='Picture'></span>机构管理</a>
	</p>
	<ul path="/user.org">
		<!--	<li><a href="/user.home">机构信息</a></li>-->
		<li><a href="/user.org.info">机构信息</a></li>
		<li><a href="/user.org.teacher">教师管理</a></li>
		<!--<li><a href="/user.org.courseInfo">新建课程</a></li>-->
		<li><a href="/user.org.course">课程列表</a></li>
		<?php /*<!--<li><a href="/user.org.notice">公告列表</a></li>-->*/?>
	</ul>
</div>
<div class='left-2'>
	<p class="cc" id=''>
	
	<a class="safety"><span class='Picture-1'></span>优惠规则管理</a>
	</p>
	<ul path="/user.discount">
		<li><a href="/user.discount.create">创建优惠规则</a></li>
		<li><a href="/user.discount.list">优惠规则列表</a></li>
	</ul>
</div>
<!--end-->
<?php }; ?>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->types->teacher)){; ?>
<!--教师权限 start-->
<div class='left-2'>
	<p class="cc" id=''>
	
	<a class="safety"><span class='Picture-2'></span>我要讲课</a>
	</p>
	<ul path="/user.teacher">
		<li><a href="/user.teacher.info">教师信息</a></li>
		<li><a href="/user.teacher.plan">我的课程表</a></li>
        <li><a href="/user.teacher.student">我的学生</a></li>
		<li><a href="/user.teacher.help">如何上课</a></li>
	</ul>
</div>
<!--end-->
<?php }; ?>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userInfoMenu"]->types->student)){; ?>
<!--学生权限 start-->
<div class='left-2'>
	<p class="cc">
	
	<a class="safety"><span class='Picture-3'></span>我要听课</a>
	</p>
	<ul path="/user.course">
		<li><a href="/user.course.list">已报名课程</a></li>
		<li><a href="/user.course.fav">我喜欢的课程</a></li>
		<li><a href="/user.course.order">我的订单</a></li>
	</ul>
</div>
<div class="left-2">
	<p class="cc">
	
	<a href="#" class="safety"><span class='Picture-4'></span>我的作业</a>
	</p>
	<ul path="/user.homework">
		<li class="bb"><a href="user.homework.list">我的作业</a></li>
	</ul>
</div>
<!--end-->
<?php }; ?>
<div class='left-2'>
	<p class="cc">
	
	<a href="#" class="safety"><span class='Picture-5'></span>个人资料</a>
	</p>
	<ul path="/user.info">
		<li><a href="/user.info.studentInfo">学生信息</a></li>
		<li class="bb"><a href="/user.info.base">基本资料</a></li>
		<li><a href="/user.info.pic">修改头像</a></li>
		<li><a href="/user.info.picselect">选择头像</a></li>
	</ul>
</div>
<div class='left-2'>
	<p class="cc">
	
	<a class="safety"><span class='Picture-6'></span>账户充值</a>
	</p>
	<ul path="/user.mydiscount">
		<li><a href="/user.mydiscount.list">我的优惠券</a></li>
	</ul>
</div>
<div class='left-2'>
	<p class="cc">
	
	<a class="safety"><span class='Picture-7'></span>安全设置</a>
	</p>
	<ul path="/user.security">
		<!--<li><a href="/user.security">帐号安全</a></li>-->
		<li><a href="/user.security.password">修改密码</a></li>
	</ul>
</div>
<div class='left-2'>
	<p class="cc">
	
	<a class="safety"><span class='Picture-8'></span>我的消息</a>
	</p>
	<ul path="/user.message">
		<li><a href="/user.message">消息</a></li>
	</ul>
</div>
</div>
