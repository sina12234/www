<!DOCTYPE html>
<html>
<head>
<title>结算管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class='pd30'>
    <div class="container">
        <div class="row">
            <!--左侧-->
            <?php echo tpl_function_part("/org.main.menu.settle"); ?>
            <!--右侧-->
            <div class="right-main col-md-16">
                <div class="content">
                    <div class="tab-main">
                        <div class="tab-hd fs14">
                            <a class="tab-hd-opt" href="/org/settle">账户概览</a>
                            <a class="tab-hd-opt curr" href="#">结算账单</a>
                            <a class="tab-hd-opt"><a href="/org.settle.withdraw">提现记录</a>
                        </div>
                        <a target="_blank" href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platformUrl"]; ?>/index.help.settle" class="blue-link c-fr">结算帮助</a>
                    </div>

                    <div class="settle-list col-md-20 mt10" id="settle-list">
                        <div class="sl-title fs14" id="switch-btn">
                            <a href="javascript:void(0);" class="switch-btn switch-btn-on c-fl">已结算</a>
                            <a href="javascript:void(0);" class="switch-btn  c-fl">未结算</a>
                            <div class="time-picker-fr col-md-3" id="selectTime">
							<form method="post" action="<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>" id='ftime'>
								<input class="col-md-20" type="text" name='time' readonly placeholder="选择日期" onchange="this.form.submit();" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["time"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["time"]; ?><?php }; ?>"  id="time-picker">
								<i class="settle-arrow-icon"></i>
								<i id="settle-cha" class="settle-close-icon"></i>
							</form>
							</div>
                        </div>
                        <div class="settle-main">
                            <dl>
                            <dd class="sl-th fs23 mt10">
                            <p class="col-md-5">结算周期账单</p>
                            <p class="col-md-2">结算订单数</p>
                            <p class="col-md-2">课程收入</p>
                            <p class="col-md-2">会员收入</p>
                            <p class="col-md-2">推广支出</p>
                            <p class="col-md-2">分销收入</p>
                            <p class="col-md-3">本期结算收入[?]</p>
                            <p class="col-md-2">操作</p>
                            </dd>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["val"]){; ?>
                            <dt>
                            <p class="col-md-5 cGray">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["val"]['isNew']){; ?>
                                    <i class="st-new-icon"></i>
                                    <?php }; ?>
                                    <?php echo SlightPHP\Tpl::$_tpl_vars["val"]['createTime']; ?>-<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['endTime']; ?>  <span class="cGray">已结算订单</span>
                            </p>
                            <p class="col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]['orderCount']; ?></p>
                            <p class="col-md-2">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['course_price']; ?></p>
                            <p class="col-md-2">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['member_price']; ?></p>
                            <p class="col-md-2">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['price_promote']; ?></p>
                            <p class="col-md-2">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['price_resell']; ?></p>
                            <p class="col-md-3 cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['price']; ?></p>
                            <p class="col-md-2"><a href="/org/settle/accountinfo/<?php echo SlightPHP\Tpl::$_tpl_vars["val"]['clearId']; ?>" class="sBlue">查看明细</a></p>
                            </dt>
                            <?php }; ?>
							<?php }else{; ?>
							<div class="list-img tec mt40 col-md-20">
								<img src="/assets_v2/img/pet3.png">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["createTime"])){; ?>
								<p class="fs16 cGray"><?php echo tpl_modifier_tr('没有找到符合日期的提现记录','org'); ?>，<?php echo tpl_modifier_tr('请调整一下再试','org'); ?>~</p>
								<p class="fs12 cGray"><?php echo tpl_modifier_tr('您可以戳','org'); ?><a href="/org.settle.accountlist" class="sBlue"> <?php echo tpl_modifier_tr('这里','org'); ?> </a><?php echo tpl_modifier_tr('返回默认条件','org'); ?></p>
								<?php }else{; ?>
								<p class="fs12 cGray"><?php echo tpl_modifier_tr('您还没有提现记录哦','org'); ?>~</p>
								<?php }; ?>
							</div>
                            <?php }; ?>
				            </dl>
                            <div class="page-list" id="pagepage"></div>
                        </div>
                        <div class="settle-main org-view-Settled" style="display:none">
                            <dl style="">
                                <dd class="sl-title cDarkgray fs14 mt20">
                                    <span class="mr20"><b>未结算合计:</b>成功交易订单：<?php echo SlightPHP\Tpl::$_tpl_vars["unAccount"]['orders']; ?>个 </span>
                                    <span class="mr20">未结算总收入:<span class="cRed"><?php echo SlightPHP\Tpl::$_tpl_vars["unAccount"]['income']; ?></span>元 </span>
                                    <span class="mr20">渠道分销金额:0元 </span>
                                    <span class="mr20">推广支出:0元 </span>
                                </dd>
                            </dl>
                            <div class="col-md-20 mt5 pd0">
								<ul class="bor1px fs14 tec clearfix view-settled-btn">
									<li class="col-md-4 pd0 curr">
										课程收入明细
										<i class="c-fr mt10"></i>
									</li>
									<li class="col-md-4 pd0">
										会员收入明细
										<i class="c-fr mt10"></i>
									</li>
									<li class="col-md-4 pd0">
										渠道分销收入明细
										<i class="c-fr mt10"></i>
									</li>
									<!--<li class="col-md-4 pd0">
										推广费支出明细
										<i class="c-fr mt10"></i>
									</li>-->
								</ul>
							</div>
						<div class="col-md-20 fs14 pd0">
                        <ul class="view-settled-content clearfix">
                            <!--课程明细-->
        					<li class="cDarkgray"></li>
							<!--会员明细-->
        					<li class="cDarkgray"></li>
							<!--渠道分销-->
        					<li class="cDarkgray"></li>
							<!--推广费用-->
        					<li class="cDarkgray"></li>
                        </ul>
                    </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/mustache.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/scorll.js'); ?>" ></script>
<script id='course_list' type='text/template'>
<<#data>>
	<dl class="col-md-20 pd0">
		<dd>
                    <p class="col-md-8">课程名称：<<courseName>></p>
                    <p class="col-md-4"><span class="cRed mr5">￥<<price>></span><br>
                    <<^discountStatus>>
                        <span>
                            使用优惠券:<em class="price-discount cGray">￥<<discountPrice>></em>
                        </span>
                    <</discountStatus>>
                    </p>
                    <p class="col-md-4">￥<<pricePromote>></p>
                    <p class="col-md-4">￥<<income>></p>
                </dd>
		<dd>
                    <p class="col-md-15">班级：<<className>></p>
                    <p class="col-md-5"><span class="cGreen3"><<isFee>></span></span>
                </dd>
		<dd class="cGray">
                    <p class="col-md-5">订单ID：<<orderId>></p>
                    <p class="col-md-5">交易时间：<<createTime>></p>
                    <p class="col-md-5">支付方式：<<payType>></p>
                    <p class="col-md-5">购买者：<<userName>></p>
                </dd>
	</dl>
<</data>>
</script>
<script id='member_list' type='text/template'>
<<#data>>
	<dl class="col-md-20 pd0">
		<dd><p class="col-md-15">会员名称：<<memberName>></p><p class="col-md-5"><span class="fsRed">￥<<price>></span></p></dd>
		<dd><p class="col-md-7">购买人：<<userName>></p><p class="col-md-13">会员时限：<<useTime>></p></dd>
		<dd class="cGray"><p class="col-md-5">订单ID：<<orderId>></p><p class="col-md-5">交易时间：<<createTime>></p><p class="col-md-5">支付方式：<<payType>></p></dd>
	</dl>
<</data>>
</script>
<script id='ditch_list' type='text/template'>
<<#data>>
	<dl class="col-md-20 pd0">
		<dd><p class="col-md-10">课程名称：<<title>></p><p class="col-md-5">推广商：<<resellOrgName>></p></dd>
		<dd>
                    <p class="col-md-10">班级：<<className>></p>
                    <p class="col-md-5">成本价：￥<<pricePromote>><br>订单价格：￥<<price>></p>
                    <p class="col-md-5 tec"><span class="fsRed">￥<<income>></span></p>
                </dd>
		<dd>
                    <p class="col-md-5 cGray">订单ID：<<orderId>></p>
                    <p class="col-md-5 cGray">交易时间：<<createTime>></p>
                    <p class="col-md-5 cGray">支付方式：<<payType>></p>
                    <p class="col-md-5 cGray">购买者：<<userName>></p>
		</dd>
	</dl>
<</data>>
</script>
<script id='expand_list' type='text/template'>
<<#data>>
	<dl class="col-md-20 pd0">
		<dd>
            <p class="col-md-9">课程名称：<<title>></p>
		    <p class="col-md-5">推广商：<<orgSubname>>
        </dd>
		<dd>
			<p class="col-md-8">班级：<<className>></p>
                        <p class="col-md-9"> 成本价：￥<<priceResell>></p>
			<p class="col-md-5">订单价格：￥<<price>></p>
		</dd>
		<dd><p class="col-md-9"><span class="fsRed">￥<<income>></span></p></dd>
		<dd>
			<p class="col-md-5 cGray">订单ID：<<orderId>></p>
			<p class="col-md-5 cGray">交易时间：<<createTime>></p>
			<p class="col-md-5 cGray">支付方式：<<payType>></p>
			<p class="col-md-5 cGray">购买者：<<userName>></p>
		</dd>
	</dl>
<</data>>
</script>

<script type="text/javascript">
var page_1  = 1;
var data = <?php echo SlightPHP\Tpl::$_tpl_vars["unparams"]; ?>;
data.type  = 1;
var param = {  };
param.linum = 0;
param.head  = "<div class='col-md-7 bgf5 mt10 view-settled-title'>课程名称</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>销售价格</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>推广支出</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>实际收入</div>\n\<div id='courseCont'></div>";
param.txt   = '本期没有符合结算的课程收入~';
param.appentId = "#courseCont";
param.tplId    = "#course_list";

$(function() {
    $('.view-settled-btn li').click(function() {
        var data = <?php echo SlightPHP\Tpl::$_tpl_vars["unparams"]; ?>;
        $(this).addClass('curr').siblings().removeClass('curr');
        $('.view-settled-content li:eq(' + $(this).index() + ')').show().siblings().hide();
        param.linum = $(this).index();
        if(param.linum == 0){
            data.type = 1;            
            data.orgId = <?php echo SlightPHP\Tpl::$_tpl_vars["orgId"]; ?>;      
            param.head  = "<div class='col-md-7 bgf5 mt10 view-settled-title'>课程名称</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>销售价格</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>推广支出</div>\n\<div class='col-md-4 bgf5 mt10 view-settled-title'>实际收入</div>\n\<div id='courseCont'></div>";
            param.txt   = '本期没有符合结算的课程收入~';
            param.appentId = "#courseCont";
            param.tplId    = "#course_list";
        }else if(param.linum == 1){
            data.type = 11;
            data.orgId = <?php echo SlightPHP\Tpl::$_tpl_vars["orgId"]; ?>;      
            param.head  = "<div class='col-md-15 bgf5 mt10 view-settled-title'>交易详情</div><div class='col-md-5 bgf5 mt10 view-settled-title'>实际收入</div><div id='memCont'></div>";
            param.txt   = '本期没有符合结算的会员收入~';
            param.appentId = "#memCont";
            param.tplId    = "#member_list";
        }else if(param.linum == 2){
            data.type = 21;
            data.orgId = <?php echo SlightPHP\Tpl::$_tpl_vars["orgId"]; ?>;            
            data.resellOrgId = <?php echo SlightPHP\Tpl::$_tpl_vars["orgId"]; ?>;
            param.head  = "<div class='col-md-8 bgf5 tec mt10 view-extension'>课程信息</div><div class='col-md-6 bgf5 tec mt10 view-extension'>推广信息</div><div class='col-md-6 bgf5 tec mt10 view-extension'>总收入</div></div><div id='ditchCon'></div>";
            param.txt   = '本期没有符合结算的渠道分销收入~'; 
            param.appentId = "#ditchCon";
            param.tplId    = "#ditch_list";
	}else if(param.linum == 3){
            data.type = 21;
            data.promoteOrgId = <?php echo SlightPHP\Tpl::$_tpl_vars["orgId"]; ?>;
            param.head  = "<div class='col-md-9 bgf5 tec mt10 view-distribut'>课程信息</div><div class='col-md-4 bgf5 tec mt10 view-distribut'>分销信息</div><div class='col-md-4 bgf5 tec mt10 view-distribut'>费用情况</div><div class='col-md-3 bgf5 tec mt10 view-distribut'>总支出</div><div id='expandCon'></div>";
            param.txt   = '本期没有符合结算的推广费用支出~';
            param.appentId = "#expandCon";
            param.tplId    = "#expand_list";
        }
        page_1 = 1;
        getData(data,param,page_1);
    });

    $(window).scroll(function() {
		var scrollTop    = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var offetHeight  = $(this).height();
        
		if( scrollTop + offetHeight == scrollHeight){
			page_1++;
			$.post("/org/ajax/SettleInfoData",{ data:data,page:page_1 },function(r){
				if(r.code == 1){
					var tpl  = $(param.tplId).html();
					var list = Mustache.render(tpl, r);
					$(param.appentId).append(list);
                    }else{
                        $(window).unbind('scroll');
                    }
			},'json');
		}
    });
})
getData(data,param,page_1);
function getData(data,param,page_1){
    $.post("/org/ajax/SettleInfoData",{ data:data,page:page_1 },function(r){
        if(r.code == 1){
            $('.view-settled-content li:eq(' + param.linum + ')').html(param.head);
            var tpl  = $(param.tplId).html();
            var list = Mustache.render(tpl, r);
            $(param.appentId).html(list);
        }else{
            html = '';
            html += '<div class="list-img tec mt40">';
            html += '<img src="/assets_v2/img/pet3.png">';
            html += "<p class='fs12 cGray'>"+ param.txt +"</p>";
            html += '</div>';
            $('.view-settled-content li:eq(' + param.linum + ')').html(html);
        }
    },'json');
}
</script>
<script>
    $(function(){
        $('#switch-btn .switch-btn').click(function(){
            $(this).addClass('switch-btn-on');
            $(this).siblings().removeClass('switch-btn-on');
            $('#settle-list').find(".settle-main:eq("+$(this).index()+")").show().siblings('.settle-main').hide()
            if($(this).index() == 1){
                $("#selectTime").css('display','none');
            }else{
                $("#selectTime").css('display','block');
            }
        })
    })
    $("#time-picker").datetimepicker({
        lang:'ch',
        timepicker:false,
        format: 'Y-m-d'
    });

	$(function() {
		page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
	});
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["createTime"])){; ?>
		$('#settle-cha').show();
	<?php }else{; ?>
		$('#settle-cha').hide();
	<?php }; ?>

	$('#settle-cha').click(function(){
		$("input[name='time']").val('');
		$('#ftime').submit();
	});
</script>
