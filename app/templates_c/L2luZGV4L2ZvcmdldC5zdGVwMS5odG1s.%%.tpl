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
                <div class="col-lg-17 col-md-17 col-sm-20 col-xs-20">
                    <div class="forget-step col-lg-18 col-md-18 col-sm-20 col-xs-20">
                        <div class="col-sm-5 col-xs-5 step">
                            <p class="blue-bg"></p>
                            <p class="blue-num">1</p>
                            <p class="blue-text">确认账号</p>
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
                            <p class="bg"></p>
                            <p class="num">4</p>
                            <p class="text">完成</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- 账号确认 -->
                    <div class="col-sm-20 col-md-10 col-xs-20 col-lg-10 p40 col-md-offset-5 col-lg-offset-5">
                        <form novalidate action="" method="post" class="form col-sm-20" autocomplete="off" id="rcri-form">
                            <input type="password" style="display:none;">
                            <ul class="row">
                                <li>
                                    <div class="label col-sm-7 col-md-7 col-xs-7 col-lg-7 text-right">手机号：</div>
                                    <div class="label-for col-sm-13 col-lg-13 row">
                                        <input type="tel" class="col-sm-13 col-lg-18 col-md-15 lh-35" placeholder="请填写手机号" id="uname" name="uname" >
                                        <div class="tips cRed col-sm-20">&nbsp;</div>
                                    </div>
                                </li>
                                <li class="mt10">
                                    <div class="label col-sm-7 col-md-7 col-xs-7 col-lg-7 text-right">验证码：</div>
                                    <div class="label-for col-sm-13 col-xs-13 row">
                                        <input type="email" class="col-sm-5 col-md-8 col-xs-8 col-lg-11 lh-35" placeholder="请填写验证码" name="code" id="code">
                                        <img id="cap" src="/captcha.gen" style="height:35px;border:1px dashed #dfdfdf;" class="d-img  col-xs-10 col-sm-8">
                                        <div class="tips cRed col-sm-20">&nbsp;</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="mt10 label-for col-sm-15 row col-sm-offset-7 col-lg-12 col-md-17 col-lg-offset-7 col-xs-offset-7 col-xs-15"><button class="btn col-sm-11 blue-btn col-xs-13 row fs14">获取验证码,下一步</button></div>
                                </li>
                            </ul>
                        </form>
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
    $("input[type='text']").focus(function() {
        $(this).css("border","1px solid #ffa81d");
    });
    $("input[type='text']").change(function() {
        $(this).css("border","1px solid #ccc").nextAll('.tips').html("&nbsp");
    });
    $("input[type='text']").blur(function() {
        $(this).css("border","1px solid #ccc");
    });

    $("#cap").click(function(){
        $("#cap").attr("src","/captcha.gen?"+Math.random());
    }); 
    $("#rcri-form").submit(function(){
        if($('input[name=uname]').val()=='' || $('input[name=uname]').val()=="输入手机号"){
             $('input[name=uname]').focus().css("border","solid 1px #fc401b").nextAll('.tips').html("请输入手机号");
            return false;
        }else if($("#code").val()=='' || $("#code").val()=="验证码"){
            $('input[name=code]').focus().css("border","solid 1px #fc401b").nextAll('.tips').html('请输入验证码');
            return false;
        }else{
            $.post("/index.main.forget1Ajax",$("#rcri-form").serialize(),function(r){
                if(r.error){
					$("#cap").attr("src","/captcha.gen?"+Math.random());
                    $("[name="+r.field+"]").focus().css("border","solid 1px #fc401b").nextAll('.tips').html(r.error);
                    return false;
                }else{
                    location="/index.main.forget2"
                }
            },"json");
            return false;
        }
    })
})
</script>
</html>
