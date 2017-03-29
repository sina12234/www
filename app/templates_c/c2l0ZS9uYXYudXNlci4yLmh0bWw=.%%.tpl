<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
    <!-- 搜索 -->
    <div class="m-sub-seach" id="m-sub-seach">
        <i class="m-sub-seach-icon"></i>
    </div>
    <!-- /搜索 -->
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
<ul class="user-menu hidden-xs">
    <li class="user-menu-info">
        <a href="javascript:void(0)">
            <span class="face c-fl"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
            <span class="left fs14 user-name-flow"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
            <span class="arrow-down"></span>
        </a>
        <p class="down-menu">
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?><a href="/org"><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a><?php }; ?>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) or !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
            <a href="/teacher.course.timetable2" class="hidden-xs"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
            <?php }; ?>
			<a href="/student.course.mycourse"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
            <a href="/student.main.infobase"><?php echo tpl_modifier_tr('基础设置','site.header'); ?></a>
            <a href="/site.main.logout"><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
        <p/>
    </li>
    <li class="hidden-xs hidden-sm">
        <a href="/user.message" ><span class="mail-icon"></span>
            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"])){; ?>
            <span class="msg-num"></span>
            <?php }; ?>
        </a>
    </li>
</ul>
<div class="m-user-menu visible-xs">
      <div class="m-user-menu-info" id="mobile-user-menu">
          <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
          <span style="color:#333;" class="hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
      </div>
      <p class="m-user-show-menu" style="display:none" id="m-show-menu">
          <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?><a href="/org.discount.listnew"><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a><?php }; ?>
          <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
              <a href="/teacher.course.timetable2"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
          <?php }; ?>
          <a href="/student.main.growth"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
          <a href="/student.main.infobase"><?php echo tpl_modifier_tr('基础设置','site.header'); ?></a>
          <a href="/site.main.logout"><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
      </p>
  </div>
  <?php }else{; ?>
<!--登录-->
  <div class="g-unlogin">
    <span class="hidden-xs"><a href="/site.main.login" id="login" class="user-icon"><?php echo tpl_modifier_tr('登录','site.header'); ?></a> | <a href="/site.main.register"><?php echo tpl_modifier_tr('注册','site.header'); ?></a></span>
		<a class="right visible-xs" href="/site.main.login">
			<span class="m-login-img"></span>
		</a>
  </div>
  <?php }; ?>
<?php }else{; ?>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
<!--用户信息-->
<ul class="user-menu hidden-xs">
    <li class="user-menu-info">
      <a href="javascript:void(0)">
        <span class="face">
        <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
      </span>
      <span class="gn-user-name left"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
      <span class="left">
        <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/index-down.png'); ?>">
      </span>
    </a>
    <span class="right hidden-xs hidden-md hidden-sm">|</span>
    <p class="down-menu">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
        <a href="/org" class="hidden-xs hidden-sm"><span class="org-mechanism"></span><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a>
        <?php }; ?>
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
        <a href="/teacher.course.timetable2"><span class="edu-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
        <?php }; ?>
        <a href="/student.main.growth"><span class="study-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
        <a href="/student.main.infobase"><span class="compose-icon"></span><?php echo tpl_modifier_tr('基础设置','site.header'); ?></a>
        <a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
    <p/>
  </li>

  <li class="hidden-xs hidden-sm">
    <a href="/user.message" ><span class="mail-icon"></span>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"])){; ?>
      <span class="msg-num"></span>
      <?php }; ?>
      </a>
  </li>
</ul>
	<div class="m-sub-seach" id="m-sub-seach">
		<i class="m-sub-seach-icon"></i>
	</div>
<div class="m-user-menu visible-xs">
    <div class="m-user-menu-info" id="mobile-user-menu">
        <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
        <span style="color:#333;" class="hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
  </div>
    <p class="m-user-show-menu" style="display:none" id="m-show-menu">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?><a href="/org.discount.listnew"><span class="edu-icon"></span><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a><?php }; ?>
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
        <a href="/teacher.course.timetable2"><span class="edu-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
        <?php }; ?>
        <a href="/student.main.growth"><span class="study-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
		<a href="/student.order.myorder"><span class="order-icon"></span><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a>
        <a href="/student.main.infobase"><span class="compose-icon"></span>基础设置</a>
    <a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
    </p>
</div>
  <?php }else{; ?>
  <div class="m-sub-seach" id="m-sub-seach">
      <i class="m-sub-seach-icon"></i>
  </div>
  <!--登录-->
  <div class="g-unlogin">
    <span class="hidden-xs"><a href="/site.main.login" id="login" class="user-icon"><?php echo tpl_modifier_tr('登录','site.header'); ?></a> | <a href="/site.main.register"><?php echo tpl_modifier_tr('注册','site.header'); ?></a></span>
		<a class="right visible-xs" href="/site.main.login">
			<span class="m-login-img"></span>
		</a>
	<!--<div class="m-sub-seach" id="m-sub-seach">
		<i class="m-sub-seach-icon"></i>
	</div>-->
  </div>
  <?php }; ?>
<?php }; ?>

<script>
$(function(){
    $('#mobile-user-menu').click(function(){
        $('#m-show-menu').toggle();
    });
	$('.m-sub-seach').click(function(){
		var menulist_hide = $('.seach-input');
		menulist_hide.toggle();
	})
});
</script>
