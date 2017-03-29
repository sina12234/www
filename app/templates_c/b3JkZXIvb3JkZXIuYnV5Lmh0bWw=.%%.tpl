<!DOCTYPE html>
<html>
<head>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"])){; ?>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - 购买课程</title>
<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["memberInfo"])){; ?>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - 开通会员</title>
<?php }; ?>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 课程详情 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<style>
.bgf7{ background: #f7f7f7;}
.bor1px { border:1px solid #ddd;}
.coupon-type{ height:90px;}
.preferent-code-info .select-code-info .c-price{ line-height: 90px;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav3"); ?>
<section class="pd40 bgf" id="open-vip">
	<div class="container">
		<div class="row">
            <div id="orderBuy_width" style="width:80%;margin:0 auto;">
                <div class="col-sm-14 pd0 tel mb10">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberInfo"])){; ?>
                    <a href="/order.main.memberInfo/<?php echo SlightPHP\Tpl::$_tpl_vars["objectId"]; ?>" class="member-payBtn-stepTwo fs12" title="">&lt;&lt;<?php echo tpl_modifier_tr('返回','course.info'); ?></a>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"])){; ?>
					<a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["objectId"]; ?>" class="member-payBtn-stepTwo fs12" title=""><?php echo tpl_modifier_tr('<返回','course.info'); ?></a>
					<?php }; ?>
				</div>
                <div class="col-sm-20 fs14 bor1px bgf7 pd10 mb20 hidden-xs">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-14"><?php echo tpl_modifier_tr('订单信息','course.info'); ?></div>
                    <div class="col-sm-3 tec"><?php echo tpl_modifier_tr('价格','course.info'); ?></div>
                </div>
                <div class="clearfix col-sm-20">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberInfo"])){; ?>
                    <div class="col-sm-3 pdl0 col-xs-20">
						<img src="<?php echo utility_cdn::img('/assets_v2/img/member-order.jpg'); ?>" alt="" width="100%" height="100%">
					</div>
                    <div class="col-sm-14 pdl0 col-xs-20">
                        <h1 class="fs16 new-pay-title"><?php echo SlightPHP\Tpl::$_tpl_vars["memberInfo"]['title']; ?></h1>
                        <p class="mt20 cDarkgray info-buy-teac">有效期：<?php echo SlightPHP\Tpl::$_tpl_vars["memberInfo"]['time']; ?></p>
                        <div class="fl"></div>
                    </div>
                    <div class="fs16 col-sm-3 tec mt30">
                        <strong class="fwn fs18 cRed lh22">
                            ¥<span><?php echo SlightPHP\Tpl::$_tpl_vars["memberInfo"]['price']; ?></span>
                        </strong>
                    </div>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"])){; ?>
					<div class="col-sm-3 pdl0 col-xs-20 hidden-xs">
						<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumbMed']; ?>" alt="" width="100%" height="auto">
					</div>
                    <div class="col-sm-14 pdl0 col-xs-20">
                        <h1 class="fs16 new-pay-title"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title']; ?></h1>
                        <p class="mt20">班级：<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['className']; ?></p>
                        <div class="fl"></div>
                    </div>
                    <div class="fs16 col-sm-3 tec mt30">
                        <strong class="fwn fs18 cRed lh22">
                            ¥<span><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?></span>
                        </strong>
                    </div>
					<?php }; ?>
                </div>
                <!-- 优惠码 -->
                <div class="col-md-20 p0 mt40" id="preferent-code-info">
                    <h1 class="fs14 mb10">使用优惠券/优惠码</h1>
                    <div class="preferent-code-info clearfix">
                        <ul class="click-code-btn clearfix">
                            <li onclick="clickCodeBtn(this)" data-status="0" class="active col-xs-10 col-md-2">优惠券</li>
                            <li onclick="clickCodeBtn(this)" data-status="1" class="col-md-2 col-xs-10">优惠码</li>
                        </ul>
                        <div class="fill-code-infos">
                            <li class="select-code-info couponlist col-md-20 col-xs-20 p10 clearfix" id="select-code-info"></li>
                            <li class="fill-code-info" style="height:90px;">
                                <div class="col-md-20 p15 mt10 clearfix">
                                    <span class="col-md-3 col-xs-10 p0 tar mt5 fs14">请输入优惠码：</span>
                                    <input class="fill-code-input col-md-4 col-xs-10" onkeyup="this.value=this.value.replace(/[%&'#￥%……^）)>》‘;'：《<(*（,;=?$\x22]/g,'')" maxlength="6" type="text" />
                                    <button id="use-code-ipt" class="gray-button c-fl" onclick="ajaxCheckCode();">使用</button>
                                </div>
                            </li>
                        </div>
                    </div>
                </div>
                <!-- /优惠码 -->
                <div class="col-md-20 p0 tac fs14 mt40" id="preferent-code-info-no" style="display: none;">
                    <p>本次支付不支持优惠！</p>
                </div>
                <div class="col-md-20 ter fs18 lh22 order-priceInfo" style="display:none;">
                    <p class="tar fs16 mt20">
                        原价：<span class="cRed order-priceOld mr35 tdlt"></span>
                    </p>
                    <p class="tar fs16 mt20">
                        优惠金额：<span class="cRed order-disPrice mr35"></span>
                    </p>
                    <p class="tar fs16 mt20">
                        应付金额：<span class="cRed order-totalPrice mr35"></span>
                    </p>
                </div>
				<div class="tac col-xs-20 mt30 mb30 pd0">
                    <button class="btn fs16 mt30 col-md-offset-9 request-pay-stepTwo" onclick="orderPay()">
                        <?php echo tpl_modifier_tr('提交订单','course.info'); ?>
                    </button>
                </div>
                <div class="clear"></div>
            </div>

		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/template" id="DiscountTicketTpl">
    <<#codeList>>
        <div class="coupon-item col-xs-10 col-md-4" onclick="selectCheckCode(this);" data-code="<<code>>" data-discountCodeId="<<discountCodeId>>" data-discount_value="<<discount_value>>" data-min_fee="<<min_fee>>" data-price="<<price>>" data-priceOld="<<priceOld>>" data-type="<<type>>" data-disPrice="<<disPrice>>">
            <i class="icon"></i>
            <div class="coupon-main">
                <div class="coupon-type tac">
                    <p class="c-price fb fs30"></p>
                </div>
                <p class="tac c-limit fs18"></p>
            </div>
        </div>
    <</codeList>>
</script>
<script>
var objectId  = <?php echo SlightPHP\Tpl::$_tpl_vars["objectId"]; ?>;
var flag = 0;
var memberInfo = <?php echo json_encode(SlightPHP\Tpl::$_tpl_vars["memberInfo"]); ?>;
var resellRes = <?php echo json_encode(SlightPHP\Tpl::$_tpl_vars["resellRes"]); ?>;
$(function(){
    if (resellRes!=""||memberInfo!="") {
        $("#preferent-code-info").hide();
        $("#preferent-code-info-no").show();
    }else {
        DiscountTicket();
    }
    if($(window).width() > 768) {
        $('#orderBuy_width').css('height', '420px');
    }else{
        $('.fill-code-input').css('width', '100px');
    }
});

function clickCodeBtn(obj) {
    $(obj).addClass('active').siblings().removeClass('active');
    $('.fill-code-infos li:eq('+$(obj).index()+')').show().siblings().hide();
    $('.fill-code-input').val('');
    if($(obj).attr('data-status') == 0) {
        if($('#select-code-info').attr('data-code') == 0) {
            $('#select-code-info').find('.coupon-item:eq(0)').addClass('active');
            $('.order-priceInfo').show();
            var obj = $('#select-code-info').find('.coupon-item:eq(0)');
            selectCheckCode(obj);
        }else{
            $('.order-priceOld').html('');
            $('.order-disPrice').html('');
            $('.order-totalPrice').html('');
            $('.order-priceInfo').hide();
        }
    }else{
        $('.order-priceOld').html('');
        $('.order-disPrice').html('');
        $('.order-totalPrice').html('');
        $('.order-priceInfo').hide();
    }
}

function orderPay() { //支付
    var objectType= '<?php echo SlightPHP\Tpl::$_tpl_vars["objectType"]; ?>';
    var ext       = <?php echo SlightPHP\Tpl::$_tpl_vars["ext"]; ?>;
    var code;
    if(flag){
        code = $.trim($('.fill-code-input').val());
    }else{
        if($('#select-code-info').find('.coupon-item').hasClass('active')) {
            code = $('#select-code-info').find('.active').attr('data-code');
        }else{
            code = '';
        }
    }
    var orgId = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["resellRes"]['resellOrgId'])){; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["resellRes"]['resellOrgId']; ?> <?php }else{; ?> 0 <?php }; ?>;
    $.post('/order.ajax.payNew',{ objectId:objectId,objectType:objectType,ext:ext,code:code,orgId:orgId },function(r){
        if(r.code == 0){
            if(r.result.back == 1){
                if(r.result.blackUrl!=''){
                    window.location.href = r.result.blackUrl;
                }else{
                    window.location.href = "/student.course.detail/"+r.result.objectId+"/"+r.result.ext;
                }
            }else{
                window.location.href = "/order/main/pay/<?php echo SlightPHP\Tpl::$_tpl_vars["objectType"]; ?>/"+r.result.uniqueOrderId;
            }
        }else{
            layer.msg(r.errMsg);
        }
    },'json');
}

function ajaxCheckCode() { //优惠码
    var code = $.trim($('.fill-code-input').val());
    if(code == ''){
        layer.msg('请输入优惠码');
        return false;
    }else{
        $.post('/order.ajax.checkCodeNew', { code:code, objectId:objectId }, function(r) {
            if(r.code == 0){
                $('.order-priceOld').html('￥'+r.result.priceOld);
                $('.order-disPrice').html('￥'+r.result.disPrice);
                $('.order-totalPrice').html('￥'+r.result.price);
                $('.order-priceInfo').show();
                flag = 1;
            }else{
                $('.order-priceOld').html('');
                $('.order-disPrice').html('');
                $('.order-totalPrice').html('');
                $('.order-priceInfo').hide();
                layer.msg(r.errMsg);
            }
        },'json');
    }
}

function selectCheckCode(obj) { //优惠券
    $('#select-code-info').find('.coupon-item:eq(0)').addClass('active');
    $(obj).addClass('active').siblings().removeClass('active');
    $('.order-priceInfo').show();
    $('.order-priceOld').html('￥'+$(obj).attr('data-priceold'));
    $('.order-disPrice').html('￥'+$(obj).attr('data-disPrice'));
    $('.order-totalPrice').html('￥'+$(obj).attr('data-price'));
}

function DiscountTicket() { //优惠券列表
    var DiscountTicketTpl = $('#DiscountTicketTpl').html();
    $.post('/order/ajax/DiscountTicket', { objectId:objectId }, function(r) {
        if(r.code == 0) {
            $('#select-code-info').html(Mustache.render(DiscountTicketTpl, r.result));
            $('#select-code-info').attr('data-code', r.code);
            var obj = $('#select-code-info').find('.coupon-item:eq(0)');
            selectCheckCode(obj);
            $('#select-code-info').find('.coupon-item').each(function() {
                var type = $(this).attr('data-type');
                if(type == 1) {
                    $(this).find('.c-limit').text('满'+$(this).attr('data-min_fee'));
                    $(this).find('.c-price').text('减'+$(this).attr('data-discount_value')+'元');
                }else if(type == 2){
                    $(this).find('.c-limit').text('满'+$(this).attr('data-min_fee'));
                    $(this).find('.c-price').text($(this).attr('data-discount_value')*10+'折');
                }else{
                    return false;
                }
            })
        }else{
            var html = '<div style="width:100%;min-height:40px;" class="my-collect-no-class p20"><div class="fs14">暂时还没有可用优惠券哦！</div></div>';
            $('#select-code-info').attr('data-code', r.code);
            $('#select-code-info').html(html);
            $('.click-code-btn li').removeClass('active');
            $('.click-code-btn li:eq(1)').addClass('active');
            $('.preferent-code-info .select-code-info').hide();
            $('.preferent-code-info .fill-code-info').show();
        }
    }, 'json');
}
</script>
</html>
