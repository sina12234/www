<!DOCTYPE html>
<html>
<head>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - <?php if(SlightPHP\Tpl::$_tpl_vars["objectType"] == 'member'){; ?>开通会员<?php }else{; ?>购买课程<?php }; ?></title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 课程详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<style>
.auto{ margin: 0 auto;position: relative;border:2px solid #FFA81E !important;height: 56px; }
.auto .dui{ position: absolute;top:-4px;right:0; display:block !important;}
.new-pay-btn { width: 140px;height:51px;border: 2px solid #ccc;float: left;}
.layui-layer-btn{ padding:0 0 10px; }
@media screen and (max-width: 760px) and (min-width: 320px){
.new-pay-btn{ margin:0 auto 10px auto;float: none;display: block;}
#more-pay-infos{ width: 140px;line-height: 28px;text-align: center;margin:0 auto;font-size:14px;}
#layui-layer1{ width:100%; }

}
</style>
<body class="bgf7">
<?php echo tpl_function_part("/site.main.nav3"); ?>
<section id="open-vip">
	<div class="container">
		<div class="row">
            <div class="bgf pdb30 order-box-w">
                <div class="vip-intro mt30 fs14 clearfix member-order-info">
                    <div class="c-fl cDarkgray">订单ID：<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->orderSn; ?></div>
                    <div class="c-fr">
                        <?php echo tpl_modifier_tr('应付金额：','course.info'); ?>
                        <span class="cRed">
                            ￥<em><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->price; ?></em>
                        </span>
                    </div>
                </div>
                <div class="col-md-20 pd0 clearfix mb20 fs14 lh22 mt10">
					<div class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->title; ?> <span class="ml10 cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->extName; ?></span></div>
                    <div class="c-fr cGray">
                        <?php echo tpl_modifier_tr('请于60分钟内完成支付，否则订单将会取消，付款剩余时间：','course.info'); ?>
                        <span class="cRed" id="timer"></span>
                    </div>
                </div>
                <!-- pay -->
                <div class="col-sm-20 pd0 mt20">
                    <div class="col-sm-2 cDarkgray fs16 pd0 tel"><?php echo tpl_modifier_tr('支付方式：','course.info'); ?></div>
                    <?php if(0 != SlightPHP\Tpl::$_tpl_vars["orderInfo"]->price){; ?>
					<div class="col-sm-18 tel col-xs-20 pd0 mt10">
                        <div tip="用支付宝支付" method="url" payurl="<?php echo SlightPHP\Tpl::$_tpl_vars["aliPayUrl"]; ?>" class="paymethod new-pay-btn zhifubao-pay">
                            <img class="mauto" width="100%" src="<?php echo utility_cdn::img('/assets_v2/img/pay_zfb.jpg'); ?>" alt="" />
                            <div class='dui' style="display:none;">
                                <img src="<?php echo utility_cdn::img('/assets/images/duihao.png'); ?>" alt="">
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div tip="用微信支付" name="otherPayUrl" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["qrCode"])){; ?>method="app"<?php }else{; ?>method="qrCode"<?php }; ?> payurl="" class="weixin-pay paymethod new-pay-btn">
                            <div class="bag">
                                <img class="mauto" width="100%" src="<?php echo utility_cdn::img('assets_v2/img/pay_weixin.jpg'); ?>" alt=""/>
                            </div>
                            <div class='dui' style="display:none;">
                                <img src="<?php echo utility_cdn::img('/assets/images/duihao.png'); ?>" alt="">
                            </div>
                        </div>
                        <div class="hidden" id="weixinPayUrl"></div>
						<div id="more-pay-infos" style="display:none;">更多方式</div>
					<!-- 钱包
						<div class="col-md-1"></div>
                        <div tip="钱包支付" method="url" payurl="<?php echo SlightPHP\Tpl::$_tpl_vars["aliPayUrl"]; ?>" class="paymethod new-pay-btn c-fl mr5">
                            <img class="mauto" width="100%" src="<?php echo utility_cdn::img('/assets_v2/img/s-money-sel.jpg'); ?>" alt="" />
                            <div class='dui' style="display:none;">
                                <img src="<?php echo utility_cdn::img('/assets/images/duihao.png'); ?>" alt="">
                            </div>
                        </div>
					 /钱包 -->
                    </div>
					<?php }; ?>
                </div>
                <div class="col-xs-20 mt30 tac">
                    <a id="buybt" method="" target="_blank" class="fs14 tac mt40 btn" value="去支付" type="submit"><?php echo tpl_modifier_tr('请选择支付方式','course.info'); ?></a>
                </div>
                <!-- /pay -->
            </div>
		</div>
	</div>
</section>
<!-- 订单信息 -->
<div class="col-xs-20 tec orderTimeout" style="display:none;">
    <p class="fs14 mt30 lh22"><?php echo tpl_modifier_tr('该订单已超时，请返回重新下单','course.info'); ?></p>
    <p class="fs14 lh22"><?php echo tpl_modifier_tr('您可以选择：','course.info'); ?>
        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->url; ?>" class="cRed"><?php echo tpl_modifier_tr('物品详情页','course.info'); ?></a>
    </p>
</div>
<div id="wrap-courseBuy-success" class="col-xs-20 wrap-buy-success" style="display:none;">
	<h1 class="fs16 tel mt20"><span class="success-icon c-fl mr5"></span>恭喜你，购买成功</h1>
	<h2 class="fs14 mt20 mb10">已付款：￥<span class="cRed"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->price; ?></span></h2>
	<div class="col-sm-20 pd0 mb20">
		<div class="col-xs-6 pd0" id="wrap-courseBuy-success-img">
			<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->courseThumb; ?>" width="100%" height="76" alt="">
		</div>
		<div class="col-xs-14">
			<p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->title; ?></p>
			<p class="fs14 cDarkgray">班级：<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->extName; ?></p>
			<p class="fs14 cDarkgray mt15">讲师：<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->teacherName; ?></p>
		</div>
	</div>
	<div class="col-xs-20 pd0 lh22 mt10 mb20">
		<div class="col-xs-4 pd0 fs12 mt5 lhh36 tac">猜你想去</div>
		<div class="col-xs-16 fs14">
			<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->url; ?>" class="btn mr10">去上课</a>
		</div>
	</div>
</div>
<div id="wrap-memberBuy-success" class="col-xs-20 wrap-buy-success" style="display:none;">
	<h1 class="fs16 tel mt20"><span class="success-icon c-fl mr5"></span>恭喜你，购买成功</h1>
	<h2 class="fs14 mt20 mb10">已付款：￥<span class="cRed"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->price; ?></span></h2>
	<div class="col-xs-20 pd0 mb20">
		<div class="col-xs-6 pd0" id="wrap-buy-success-img">
			<img src="<?php echo utility_cdn::img('/assets_v2/img/member-order.jpg'); ?>" width="100%" height="76" alt="">
		</div>
		<div class="col-xs-14">
			<p class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->title; ?></p>
			<p class="fs14 cDarkgray mt15">有效期：<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->extName; ?></p>
		</div>
	</div>
	<div class="col-xs-20 pd0 lh22 mb20">
		<div class="col-xs-4 pd0 fs12 mt5 lhh36 tac">猜你想去</div>
		<div class="col-xs-16 fs14 p0">
			<a href="/<?php if(isset(SlightPHP\Tpl::$_tpl_vars["is_pro"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0){; ?>course.list<?php }else{; ?>course.list<?php }; ?>?ms=<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->object_id; ?>" class="btn c-fl">去选课</a>
			<a href="/student.course.mycourse" style="margin-left:4px;" class="btn c-fl">我的课程</a>
		</div>
	</div>
</div>
<!-- /订单信息 -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript">
$(function(){
	isWeiXin();
	$('.layerCloseBtn').click(function() {
		layer.closeAll();
	})

	$(".paymethod").on('click',function(){
		var uniqueOrderId = '<?php echo SlightPHP\Tpl::$_tpl_vars["uniqueOrderId"]; ?>';
		var flag = false;
		$.ajax({
			type: "POST",
			url: "/order/ajax/checkOrder",
			data: { uniqueOrderId:uniqueOrderId },
			dataType: "json",
			async: false,
			success: function(r){
				flag = r.code;
			}
		});
		if(flag){
			$("#buybt").attr("href",$(this).attr("payurl"));
			$(".paymethod").removeClass("auto");
			$(this).toggleClass("auto");
			$("#buybt").html($(this).attr("tip"));
			$("#buybt").attr("method",$(this).attr("method"));
			$("#buybt").parent().removeClass("disa");
		}else{
			layer.msg("该订单已失效",{ time:2000 },function(){
				window.location.reload();
			});
		}
	});

	$("#buybt").click(function(){
		var qrCode=  '<?php echo SlightPHP\Tpl::$_tpl_vars["qrCode"]; ?>';
		var weiXinPayUrl = '<?php echo SlightPHP\Tpl::$_tpl_vars["weiXinPayUrl"]; ?>';
		var price = <?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->price; ?>;
		var status1 = 0;
		$.ajax({
			//提交数据的类型 POST GET
			type:"POST",
			//提交的网址
			url:"/order/ajax/getOrderPay/<?php echo SlightPHP\Tpl::$_tpl_vars["objectType"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["uniqueOrderId"]; ?>",
			async : false,
			//提交的数据
			data:{  },
			//返回数据的格式
			dataType: "json",
			//成功返回之后调用的函数
			success:function(r){
				if(r.code!=0){
					status1 = 1;
				}else {
					if (r.items["orderInfo"]["price"] != price) {
						qrCode = r.items["qrCode"];
						weiXinPayUrl = r.items["weiXinPayUrl"];
						if ($("#buybt").attr("method") == "url") {
							$("#buybt").attr("href", r.items["aliPayUrl"]);
						}
					}
				}
			},
			//调用出错执行的函数
			error: function(){
				//请求出错处理
			}
		});
		if(status1==1){
			$("#buybt").attr("href", "");
			$("#buybt").attr("target", "");
			window.location.reload();
		}else {
			if ($(this).attr("method") == "qrCode") {
				layer.confirm('<p class="fs14 tac"><img src="' + qrCode + '" alt=""></p>', {
					btn: ['支付遇到问题', '支付成功'],
					title: ['<?php echo tpl_modifier_tr('微信支付提示','course.info'); ?>']
				}, function () {
					window.location.reload();
				}, function () {
					window.location.reload();
				});
				return false;
			} else if ($(this).attr("method") == "app") {
				callpay(weiXinPayUrl);
				return false;
			}
			if ($(this).attr("href") && $(this).attr("href").length > 0) {
				layer.confirm('<p class="fs14 tac"><?php echo tpl_modifier_tr('支付完成前，请不要关闭此支付验证窗口。','course.info'); ?></p><p class="mt10 fs14 tec lh22"><?php echo tpl_modifier_tr('支付完成后，请根据您支付的情况点击下面按钮。','course.info'); ?></p>', {
					btn: ['支付遇到问题', '支付成功'],
					title: ['<?php echo tpl_modifier_tr('网上支付提示','course.info'); ?>']
				}, function () {
					window.location.reload();
				}, function () {
					window.location.reload();
				}
			)
				;
			} else {
				layer.msg("请选择支付方式");
			}
		}
	});

	var layerVal = <?php echo SlightPHP\Tpl::$_tpl_vars["layer"]; ?>;
	var localUrl = window.location.pathname;
	var substringStr = localUrl.substring(16 ,22);

	if(layerVal == 0) {
		return false;
	}else {
		var w, h;
		if($(window).width() <= 768) {
			w = '90%';
			h = '320px';
		} else{
			w = '450px';
			h = '320px';
		}
		if(substringStr == 'course') {
            layer.open({
                type: 1,
                title: ['<?php echo tpl_modifier_tr('购买提示','course.info'); ?>'],
                shadeClose: true,
                shade: 0.8,
                area: [w, h],
                content: $('#wrap-courseBuy-success')
            });
		}else if(substringStr == 'member') {
            layer.open({
                type: 1,
                title: ['<?php echo tpl_modifier_tr('购买提示','course.info'); ?>'],
                shadeClose: true,
                shade: 0.8,
                area: [w, h],
                content: $('#wrap-memberBuy-success')
            });
		}else {
			return false;
		}
	}
});

function callpay(weiXinPayUrl){
	location.replace(weiXinPayUrl);
}

var orderTime='<?php echo SlightPHP\Tpl::$_tpl_vars["orderInfo"]->create_time; ?>';
var maxtime = 60*60,timer;
function CountDown(){
	var starTime=new Date(orderTime.replace(/-/g,"/"));
	var nowTime  = new Date();
	var t=nowTime.getTime()-starTime.getTime();
	var m=59-Math.floor(t/1000/60%60);
	var s=59-Math.floor(t/1000%60);
	var msg = m+"分"+s+"秒";
	var w, h;
	if($(window).width() <= 768) {
		w = '90%';
		h = '220px';
	} else{
		w = '400px';
		h = '220px';
	}
	if (t > 0 && m > 0 && m < 60) {
		document.all["timer"].innerHTML=msg;
		--maxtime;
	}else if(m == 0 && s == 0) {
		document.all["timer"].innerHTML="订单已经过时";
		layer.open({
			type: 1,
			title:['<?php echo tpl_modifier_tr('订单超时','course.info'); ?>','color:#fff;background:#ffa81d'],
			area: [w, h],
			shadeClose: true,
			content: $('.orderTimeout')
		});
		clearInterval(timer);
	}else {
		return false;
	}
}
timer = setInterval("CountDown()",1000);

function isWeiXin() {
 	var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
    	$('.weixin-pay').addClass('auto');
    	$('#buybt').attr('method', $('.weixin-pay').attr('method'));
    	$('#buybt').attr('href', $('.weixin-pay').attr('payurl'));
    	$('#buybt').text($('.weixin-pay').attr('tip'));
        $('.zhifubao-pay').hide();
        $('#more-pay-infos').show();
        $('#more-pay-infos').click(function() {
        	$('.zhifubao-pay').show();
        	$(this).hide();
        });
    }else{
    	$('.zhifubao-pay').addClass('auto');
    	$('#buybt').attr('method', $('.zhifubao-pay').attr('method'));
    	$('#buybt').attr('href', $('.zhifubao-pay').attr('payurl'));
    	$('#buybt').text($('.zhifubao-pay').attr('tip'));
        if($(window).width() <= 768) {
        	$('.weixin-pay').hide();
	        $('#more-pay-infos').show();
	        $('#more-pay-infos').click(function() {
	        	$('.weixin-pay').show();
	        	$(this).hide();
	        });
        }
    }
}

</script>
</html>
