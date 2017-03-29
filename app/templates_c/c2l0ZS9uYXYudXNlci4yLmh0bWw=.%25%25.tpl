<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["user"])){; ?>
  <ul class="user-menu hidden-xs">
    <li class="user-menu-info">
      <a href="javascript:void(0)">
        <span class="face">
        <img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>">
      </span>
      <span class="left fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
      <span class="arrow-down"></span>
    </a>
<p class="down-menu">
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
        <a href="/org"><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a>
      <?php }; ?>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
        <a href="/teacher.course.teacherOfCourseList" class="hidden-xs"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
      <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
      <a href="/teacher.course.teacherOfCourseList" class="visible-sm"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
      <?php }; ?>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) or !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
      <a href="/student.course.mycourse"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
	  <?php }else{; ?>
	  <a href="/student.course.mycourse"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
      <?php }; ?>
      <a href="/student.main.infobase"><?php echo tpl_modifier_tr('基础资料','site.header'); ?></a>
      <a href="/student.order.myorder"><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a>
      <a class="hidden-xs" href="/student.security.password"><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a>
      <a class="visible-sm" href="/user.message"><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a>
      <a href="/site.main.logout"><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
    <p/>

  </li>
  <li class="hidden-xs hidden-sm">
    <a href="/user.message" ><span class="mail-icon"></span>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"]) && SlightPHP\Tpl::$_tpl_vars["retMessagesNum"] !=0){; ?>
      <span class="msg-num"></span>
      <?php }; ?>
      </a>
  </li>
  </ul>
  <div class="m-user-menu visible-xs">
      <div class="m-user-menu-info" id="mobile-user-menu">
          <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
          <span style="color:#333;"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
    </div>
      <p class="m-user-show-menu" style="display:none" id="m-show-menu">
          <a href="/student.main.infobase"><?php echo tpl_modifier_tr('基础资料','site.header'); ?></a>
          <a href="/student.order.myorder"><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a>
          <a href="/student.security.password"><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
          <a href="/teacher.course.teacherOfCourseList"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
      <?php }; ?>
      <a href="/student.main.growth"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
      <a href="/user.message" ><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a>
      <a href="/site.main.logout"><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
      </p>
  </div>
  <?php }else{; ?>
  <!--登录-->
  <div class="g-unlogin ml20">
      <a href="/site.main.login" id="login"><?php echo tpl_modifier_tr('登录','site.header'); ?></a> | <a href="/site.main.register"><?php echo tpl_modifier_tr('注册','site.header'); ?></a>
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
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) and !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
        <a href="/teacher.course.teacherOfCourseList" class="hidden-xs"><span class="edu-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
      <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
      <a href="/teacher.course.teacherOfCourseList" class="visible-sm"><span class="edu-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
      <?php }; ?>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"]) or !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
      <a href="/student.main.growth"><span class="study-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
      <?php }; ?>
      <a href="/student.main.infobase"><span class="compose-icon"></span><?php echo tpl_modifier_tr('基础资料','site.header'); ?></a>
      <a href="/student.order.myorder"><span class="order-icon"></span><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a>
      <a class="hidden-xs" href="/student.security.password"><span class="card-icon"></span><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a>
      <a class="visible-sm" href="/user.message"><span class="mess-icon"></span><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a>
      <a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
    <p/>
  </li>
  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isAdmin"])){; ?>
    <li class="hidden-xs hidden-sm">
    <a href="/org"><?php echo tpl_modifier_tr('机构管理','site.header'); ?></a><span class="right">|</span>
  </li>
    <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
  <li class="hidden-sm">
    <a href="/teacher.course.teacherOfCourseList"><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a><span class="right">|</span>
    </li>
  <?php }else{; ?>
    <li class="hidden-xs">
    <a href="/student.main.growth"><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a> <span class="right">|</span>
  </li>
    <?php }; ?>
  <li class="hidden-xs hidden-sm">
    <a href="/user.message" ><span class="mail-icon"></span>
      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["retMessagesNum"]) && SlightPHP\Tpl::$_tpl_vars["retMessagesNum"] !=0){; ?>
      <span class="msg-num"></span>
      <?php }; ?>
      </a>
  </li>
</ul>
<div class="m-user-menu visible-xs">
    <div class="m-user-menu-info" id="mobile-user-menu">
        <span class="face"><img src="<?php if(SlightPHP\Tpl::$_tpl_vars["user"]['large']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['large']; ?><?php }else{; ?><?php echo utility_cdn::img('/assets_v2/img/1.png'); ?><?php }; ?>"></span>
        <span style="color:#333;"><?php echo SlightPHP\Tpl::$_tpl_vars["user"]['name']; ?></span>
  </div>
    <p class="m-user-show-menu" style="display:none" id="m-show-menu">
        <a href="/student.main.infobase"><span class="compose-icon"></span><?php echo tpl_modifier_tr('基础资料','site.header'); ?></a>
        <a href="/student.order.myorder"><span class="order-icon"></span><?php echo tpl_modifier_tr('我的订单','site.header'); ?></a>
        <a href="/student.security.password"><span class="card-icon"></span><?php echo tpl_modifier_tr('安全设置','site.header'); ?></a>
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?>
        <a href="/teacher.course.teacherOfCourseList"><span class="edu-icon"></span><?php echo tpl_modifier_tr('教学管理','site.header'); ?></a>
    <?php }; ?>
    <a href="/student.main.growth"><span class="study-icon"></span><?php echo tpl_modifier_tr('学习中心','site.header'); ?></a>
    <a href="/user.message" ><span class="msg-icon"></span><?php echo tpl_modifier_tr('我的消息','site.header'); ?></a>
    <a href="/site.main.logout"><span class="close-icon"></span><?php echo tpl_modifier_tr('退出','site.header'); ?></a>
    </p>
</div>
  <?php }else{; ?>
  <!--登录-->
  <div class="g-unlogin">
      <a href="/site.main.login" id="login" class="user-icon"><?php echo tpl_modifier_tr('登录','site.header'); ?></a> | <a href="/site.main.register"><?php echo tpl_modifier_tr('注册','site.header'); ?></a>
  </div>
  <?php }; ?>
<?php }; ?>

<script>
$(function(){
    $('#mobile-user-menu').click(function(){
        $('#m-show-menu').toggle();
    });
});
</script>
