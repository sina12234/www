<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=9;IE=8;IE=7;IE=edge;chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
<link rel="stylesheet" type="text/css" href="<?php echo SlightPHP\Tpl::$_tpl_vars["urlskin"]; ?>">
<?php }else{; ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/index.css'); ?>">
<?php }; ?>
<!--[if lt IE 9]>
<?php if(SlightPHP\Tpl::$_tpl_vars["is_pro"]){; ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/skin_blue.css'); ?>">
<?php }else{; ?>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets_v2/css/index.css'); ?>">
<?php }; ?>
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/html5shiv.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/bootstrap-3.3.0/js/respond.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ie8/polyfill.js'); ?>"></script>
<![endif]-->
<!--[if lt IE 8]>
<script src="<?php echo utility_cdn::js('assets_v2/js/ie8.js'); ?>"></script>
<![endif]-->
<?php if(utility_net::getDomainRoot()!="yunke.com"){; ?>
<script type="text/javascript" src="//www.yunke.com/crossdomain.php"></script>
<?php }; ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/common/common.js'); ?>"></script>
<link rel="icon" type="image/x-icon" href="<?php echo utility_cdn::img('/assets_v2/img/platform/yunke.ico'); ?>" />
<script>
var isPro = '<?php echo SlightPHP\Tpl::$_tpl_vars["is_pro"]; ?>';
</script>
