<!DOCTYPE html>
<html>
<head>
	<title>用户登录-云课网</title>
    <?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body class="error_box">
    <?php echo tpl_function_part("/index.main.top"); ?>
    <?php echo tpl_function_part("/index.main.nav.join"); ?>
    <div class="container">
        <div class="tec xw_error_c ">
            <img  class="" src="<?php echo utility_cdn::img('/assets_v2/img/platform/error404.jpg'); ?>" alt="404" />
            <span class="xw_es mt40">
                <p>很抱歉，页面它不小心迷路了~</p>
            </span>
            <p class="tec"><a class="home" href="https://www.<?php echo SlightPHP\Tpl::$_tpl_vars["domain"]; ?>/">返回首页</a></p>
        </div>
    </div>
    <?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
