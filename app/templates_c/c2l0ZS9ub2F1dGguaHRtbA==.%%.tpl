<!DOCTYPE html>
<html>
<head>
    <title>高能100 - 专业的K12在线教育平台</title>
    <meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
    <meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
    <meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
    <?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<div class="notfound pd40">
    <div class="noting">
        <div class="text">
            <img src="<?php echo utility_cdn::img('/assets_v2/img/noauth-bgt.png'); ?>" class="topimg" alt="" >
            <div class="jump">
                <span id="jumpTo">5</span>秒后<a id="jumpUrl" href="<?php echo SlightPHP\Tpl::$_tpl_vars["subdomain"]; ?>">返回首页</a>
            </div>
        </div>
        <img src="<?php echo utility_cdn::img('/assets_v2/img/noauth-bg.png'); ?>" alt="" style="width: 100%;">
    </div>

</div>
<footer>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</footer>
<script type="text/javascript">
    var surl= document.getElementById('jumpUrl').getAttribute('href');
    countDown(5,surl);
    function countDown(secs,surl){
        var jumpTo = document.getElementById('jumpTo');
        jumpTo.innerHTML=secs;
        if(--secs>0){
            setTimeout(function () {
                countDown(secs,surl);
            },1000);
        }
        else{
            location.href=surl;
        }
    }

</script>
</body>
