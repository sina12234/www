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
                            <p class="blue-bg"></p>
                            <p class="blue-num">2</p>
                            <p class="blue-text">验证身份</p>
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
                    <div class="col-sm-20 col-md-20 col-xs-20 col-lg-10 col-lg-offset-5 p40">
                        <form novalidate action="#" method="post" autocomplete="off" name="forms" id="rcri-form" class="form col-sm-20">
                            <input type="password" style="display:none;">
                            <ul class="row">
                                <li>
                                    <div class="label col-sm-8 col-md-7 col-xs-7 col-lg-7 text-right">手机号：</div>
                                    <div class="label-for col-sm-12 row">
                                        <?php /*<input type="text" class="col-sm-20 lh-35">
                                        <div class="tips col-sm-20">&nbsp;</div>*/?>
                                        <span class="lh-35 col-sm-20"><?php echo SlightPHP\Tpl::$_tpl_vars["uname"]; ?></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="label col-sm-8 col-md-7 col-xs-8 col-lg-7 text-right">手机验证码：</div>
                                    <div class="label-for col-sm-12 col-lg-13 col-md-offset-0 col-sm-offset-0 col-lg-offset-0 col-md-8 col-xs-20 row col-xs-offset-1">
                                        <input type="tel" name="code" placeholder="请填写手机验证码" class="col-sm-8 col-md-8 col-xs-5 lh-35">
                                        <button type="button" disabled id="get_code" class="verif col-sm-9 col-md-10 col-xs-10 col-lg-10">获取取验证码</button>
                                        <div class="tips cRed col-sm-20">&nbsp;</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="mt10 label-for col-xs-13 col-xs-offset-7 col-sm-13 row col-sm-offset-8 col-lg-offset-7 col-lg-15 col-md-offset-7 col-md-9"><button type="submit" class="btn col-sm-16 col-xs-16 col-lg-14 blue-btn row fs14">确认</button></div>
                                    
                                     
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
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/layer/layer.js'); ?>"></script>
<script type="text/javascript">
$(function  () {
    var i =<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["time"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["time"]; ?><?php }else{; ?>59<?php }; ?>;
    if(i>0){
        var it = setInterval(function(){
            $("#get_code").attr('disabled','disabled').html(i+"秒后，重新获取验证码")
            if(i==0){
                clearInterval(it);
                $("#get_code").removeAttr("disabled" ).html("获取验证码")
            }
            i--

        },1000);
    }
    $("input[type='text']").focus(function() {
        $(this).css("border","1px solid #ffa81d");
    });
   $("input[type='text']").blur(function() {
        $(this).css("border","1px solid #ccc");
   });

    $('#get_code').click(function(){
        $.post("/index.main.VerifyCodePwd",$("#x_user_name").serialize(),function(r){
            if(!r.ok){
                layer.msg(r.error);
                $("[name="+r.field+"]").focus();
            }else{
                layer.msg("验证码发送成功");
                $("[name="+r.field+"]").focus();
                var i2 =60;
                var it2 = setInterval(function(){
                i2--
                $("#get_code").attr('disabled','disabled').html(i2+"秒后，重新获取验证码")
                if(i2==0){
                    clearInterval(it2);
                    $("#get_code").removeAttr("disabled" ).html("获取验证码")
                }
                },1000);
            }
        },"json");
    }); 
    $("#cap").click(function(){
        $("#cap").attr("src","/captcha.gen?"+Math.random());
    }); 
    $("#rcri-form").submit(function(){
        if($("input[name=code]").val()=='' || $("input[name=code]").val()=="验证码"){
            $('input[name=code]').focus().css("border","solid 1px #fc401b").nextAll('.tips').html('请输入验证码');
            return false;
        }else{
            $.post("/index.main.forget2Ajax",$("#rcri-form").serialize(),function(r){
                if(r.error){
                    $("#cap").attr("src","/captcha.gen?"+Math.random());
                    $("[name="+r.field+"]").focus().css("border","solid 1px #fc401b").nextAll('.tips').html(r.error);
                    return false;
                }else{
                    location="/index.main.forget3";
                }
            },"json");
            return false;
        }
    })
})
</script>
</html>
