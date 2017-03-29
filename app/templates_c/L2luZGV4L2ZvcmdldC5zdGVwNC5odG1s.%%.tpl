<!DOCTYPE html>
<html>

<head>
    <title>高能100 - 专业的K12在线教育平台</title>
    <meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
    <meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
    <meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
    <?php echo tpl_function_part("/index.main.header"); ?>
</head>

<body>
    <?php echo tpl_function_part("/index.main.top"); ?>
    <div class="container">
        <div class="row">
            <div class="logo col-sm-5"><a href="/"><img src="<?php echo utility_cdn::img('/assets_v2/img/logo.png'); ?>" alt="云课"></a></div>
        </div>
    </div>
    <section class="p20">
        <div class="container">
            <div class="row">
                <div class="col-sm-17">
                    <div class="forget-step col-sm-18">
                        <div class="col-sm-5 col-xs-5 step">
                            <p class="bg"></p>
                            <p class="num">1</p>
                            <p class="text">确认账号</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="bg"></p>
                            <p class="num">2</p>
                            <p class="text">验证身份</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="bg"></p>
                            <p class="num">3</p>
                            <p class="text">设置新密码</p>
                        </div>
                        <div class="step col-sm-5 col-xs-5">
                            <p class="blue-bg"></p>
                            <p class="blue-num">4</p>
                            <p class="blue-text">完成</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- 账号确认 -->
                    <div class="col-sm-10 col-md-10 col-lg-10 col-xs-20 col-md-offset-5 col-sm-offset-6 col-lg-offset-5 p40 c-fl">
                        <p class="ta-c fs16" style="height:50px"><span class="icon smile-icon"></span> <span>恭喜您成功设置密码!</span></p>
                        <p class="ta-c cGray2 fs14">页面 <span class="cYellow time">10</span> 秒自动跳转到登录，未跳转请点击 <a href="/index.main.login" class="cYellow">登录</a></p>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo tpl_function_part("/index.main.footer"); ?>
</body>
<script type="text/javascript">
$(function  () {
    var i =10;
    var it = setInterval(function(){
        i--
        $(".time").html(i)
        if(i==0){
            clearInterval(it);
            location="/index.main.login";
        }

   },1000);
})
</script>
</html>
