<!DOCTYPE html>
<html>
<head>
<title>结算管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/echarts.js'); ?>"></script>
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<style>
	a.doubt-icon{
		cursor:default;
	}
	a.doubt-icon span.doubt-content{
    height: 30px;
    padding: 0 15px;
    line-height: 28px;
    position: absolute;
    right:-25px;
    top:30px;
    width: auto;
    background-color: #fff;
    border: 1px solid #e7e7e7;
    display: none;
	}
    ul.org-overview li:last-child{
        border-right: none;
    }
    .settle-arrow-icon-left{
        width: 15px;
        height: 15px;
        background-position: 0 -220px;
        position: absolute;
        top: 15px;
        left:85px;
    }
    .settle-arrow-icon-right{
        right: -15px;
    }
</style>
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
                            <a class="tab-hd-opt curr" href="/org.settle" class="fs14"><?php echo tpl_modifier_tr('账户概览','org'); ?></a>
                            <a class="tab-hd-opt" href="/org/settle/accountlist/" class="fs14"><?php echo tpl_modifier_tr('结算账单','org'); ?></a>
                            <a class="tab-hd-opt" href="/org.settle.withdraw" class="fs14"><?php echo tpl_modifier_tr('提现记录','org'); ?></a>
                        </div>
                    </div>
                    <div class="col-md-13 st-profile mt15">
                        <div class="col-md-12">
                            <p class="cDarkgray fs14"><?php echo tpl_modifier_tr('累计收入','org'); ?>（元）</p>
                            <p class="st-amount col-md-14 fs22 fcorange_1" style="color:#333;">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_all)){; ?>
							￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_all/100)), 2); ?>
							<?php }else{; ?>￥----<?php }; ?>
							</p>
                            <p class="st-rang col-md-6">
                                <span><?php echo tpl_modifier_tr('日','org'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == -1){; ?>
									<i class="rang-down-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == 1)){; ?>
									<i class="rang-up-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["dayPercent"]->status == 0)){; ?>
									<?php }; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["dayPercent"]->percent; ?>
								</span>
                                <span data-start="<?php echo SlightPHP\Tpl::$_tpl_vars["weekStart"]; ?>" data-end="<?php echo SlightPHP\Tpl::$_tpl_vars["weekEnd"]; ?>"><?php echo tpl_modifier_tr('周','org'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == -1){; ?>
									<i class="rang-down-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == 1)){; ?>
									<i class="rang-up-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["weekPercent"]->status == 0)){; ?>
									<?php }; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["weekPercent"]->percent; ?>
								</span>
                                <span data-start="<?php echo SlightPHP\Tpl::$_tpl_vars["monthStart"]; ?>" data-end="<?php echo SlightPHP\Tpl::$_tpl_vars["monthEnd"]; ?>"><?php echo tpl_modifier_tr('月','org'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == -1){; ?>
									<i class="rang-down-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == 1)){; ?>
									<i class="rang-up-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["monthPercent"]->status == 0)){; ?>
									<?php }; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["monthPercent"]->percent; ?>
								</span>
                            </p>
                        </div>
                        <div class="col-md-8">
                            <p class="cDarkgray fs14"><?php echo tpl_modifier_tr('可提现金额','org'); ?>（元）<span class="fs12 cGray"><?php echo tpl_modifier_tr('截止到上周账单','org'); ?></span></p>
                            <p class="st-amount col-md-20 fs22" style="color:#ff4401;">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->withdraw)){; ?>
								￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->withdraw/100)), 2); ?>
							<?php }else{; ?>￥----<?php }; ?> 
							</p>	
							<a id="provide-money-btn" href="<?php if(SlightPHP\Tpl::$_tpl_vars["wflag"] == 1){; ?>/org.settle.applywithdraw<?php }else{; ?>javascript:void(0)<?php }; ?>" class="apply-btn">
							<?php if(SlightPHP\Tpl::$_tpl_vars["wlog"] == 1){; ?>
							<?php echo tpl_modifier_tr('提现审核中','org'); ?>
							<?php }else{; ?>
							<?php echo tpl_modifier_tr('申请提现','org'); ?>
							<?php }; ?>
							</a>
							<a href="/org.settle.withdraw" class="record-btn"><?php echo tpl_modifier_tr('提现记录','org'); ?></a>
                        </div>
                    </div>
                    <div class="col-md-7 bc-profile mt15">
                        <div class="bc-profile-c">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cardInfo"])){; ?>
                            <p class="bc-name fb"><span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->bank; ?></span><span class="c-fr"><?php echo tpl_modifier_tr('尾号','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->last_no; ?></span></p>
                            <p class="bc-userinfo cGray" style="position: relative;">
                                <span class="c-fl"><?php echo tpl_modifier_tr('持卡人姓名','org'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->user; ?></span>
                                <a href="javascript:;" class="doubt-icon c-fr">
                                    <span class="doubt-content"><?php echo tpl_modifier_tr('解绑银行卡联系客服','org'); ?> 400-1188-683</span>
                                </a>
                            </p>
                            <p class="bc-userinfo cGray"><span class="c-fl fs14"><?php echo SlightPHP\Tpl::$_tpl_vars["cardInfo"]->card_no; ?></span>
							<?php if(SlightPHP\Tpl::$_tpl_vars["cardInfo"]->status == 0){; ?>
							<span class="c-fr"><?php echo tpl_modifier_tr('审核中','org'); ?></span>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["cardInfo"]->status == 2)){; ?>
							<span class="c-fr"><?php echo tpl_modifier_tr('已开通','org'); ?></span>
							<?php }; ?>
							</p>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["isAdmin"] == 1)){; ?>	
							<a class="bc-noinfo" href="/org.settle.bankcard"><span class="add-bc-icon"><?php echo tpl_modifier_tr('添加银行账号','org'); ?></span></a>
							<p class="bc-noinfotips"><?php echo tpl_modifier_tr('仅限添加一张用于提现','org'); ?></p>
						<?php }else{; ?>
                            <p class="bc-noroot"><?php echo tpl_modifier_tr('银行卡管理仅机构创建者可见','org'); ?></p>
						<?php }; ?>
                        </div>
                    </div>
                    <ul class="org-overview col-md-20 mt20">
                        <li class="col-md-5"><p class="fs20 cDarkgray">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->orders_last_month)){; ?>
							<?php echo number_format(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->orders_last_month); ?>
						<?php }else{; ?>----<?php }; ?>
						</p><p class="fs14 cGray"><?php echo tpl_modifier_tr('上月销售订单','org'); ?>（个）</p></li>
                        <li class="col-md-5"><p class="fs20 cDarkgray">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_last_month)){; ?>
							￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_last_month/100)), 2); ?>
						<?php }else{; ?>￥----<?php }; ?>
						</p><p class="fs14 cGray"><?php echo tpl_modifier_tr('上月总收入','org'); ?>（元）</p></li>
                        <li class="col-md-5"><p class="fs20 cDarkgray">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->orders_last_week)){; ?>
							<?php echo number_format(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->orders_last_week); ?>
						<?php }else{; ?>----<?php }; ?></p>
						<p class="fs14 cGray"><?php echo tpl_modifier_tr('上周销售订单','org'); ?>（个）</p></li>
                        <li class="col-md-5"><p class="fs20 cDarkgray">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_last_week)){; ?>
							￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["orgAccount"]->income_last_week/100)), 2); ?>
						<?php }else{; ?>￥----<?php }; ?>
						</p><p class="fs14 cGray"><?php echo tpl_modifier_tr('上周总收入','org'); ?>（元）</p></li>
                    </ul>
					<form id="ftime" action="/org.settle" method="get">
						<div class="bc-path col-md-20 mt20 fs14">
							<div class="c-fl">
							<select name="charts_type" id="charts_type">
								<option <?php if(SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'account'){; ?>selected<?php }; ?> value="account"><?php echo tpl_modifier_tr('总收入','org'); ?></option>
								<option <?php if(SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'order'){; ?>selected<?php }; ?> value="order"><?php echo tpl_modifier_tr('销售订单','org'); ?></option>
							</select>
							</div>
							<div class="time-picker">
								<input name="start" type="text" placeholder="开始时间"  id="time-picker" value="<?php echo SlightPHP\Tpl::$_tpl_vars["start"]; ?>">
                                <i class=" settle-arrow-icon settle-arrow-icon-left"  ></i>
                                <span style="margin-left: 12px;">至</span>
								<input name="end" type="text" placeholder="结束时间"  id="time-picker2" value="<?php echo SlightPHP\Tpl::$_tpl_vars["end"]; ?>">
								<i class="settle-arrow-icon settle-arrow-icon-right" ></i>
							</div>
							<span class="c-fr">
								<input type="hidden" name="xtype" value="<?php echo SlightPHP\Tpl::$_tpl_vars["xtype"]; ?>">
								<a href="<?php echo utility_tool::getUrl('/org.settle','xtype','day'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["xtype"] == 'day'){; ?>class="cYellow"<?php }; ?>><?php echo tpl_modifier_tr('日','org'); ?></a>
								<!--a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'xtype','week'); ?>" class="ml10">周</a-->
								<a class="ml10 <?php if(SlightPHP\Tpl::$_tpl_vars["xtype"] == 'month'){; ?>cYellow<?php }; ?>" href="<?php echo utility_tool::getUrl('/org.settle','xtype','month'); ?>"><?php echo tpl_modifier_tr('月','org'); ?></a>
							</span>
						</div>
					</form>
                    <div  id="main" class="col-md-20 pd0" style="height: 400px;" ></div>
                    <dl class="settle-list col-md-20 mt20">
                        <dd class="sl-title fs14">
                            <span class="cDarkgray"><?php echo tpl_modifier_tr('数据明细','org'); ?></span>
                            <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["exportUrl"]; ?>" class="sBlue c-fr"><?php echo tpl_modifier_tr('下载','org'); ?> Excel</a>
                        </dd>
                        <dd class="sl-th fs14 cGray">
                            <div class="col-md-5"><?php echo tpl_modifier_tr('日期','org'); ?></div>
							<?php if(SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'account'){; ?>
                            <div class="col-md-6"><?php echo tpl_modifier_tr('总收入','org'); ?></div>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'order')){; ?>
							<div class="col-md-6"><?php echo tpl_modifier_tr('销售订单','org'); ?></div>
							<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["xtype"] == 'day'){; ?>
                            <div class="col-md-6"><?php echo tpl_modifier_tr('昨日对比','org'); ?></div>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["xtype"] == 'month')){; ?>
							<div class="col-md-6"><?php echo tpl_modifier_tr('上月对比','org'); ?></div>
							<?php }; ?>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('操作','org'); ?></div>
                        </dd>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["lk"]=>SlightPHP\Tpl::$_tpl_vars["lo"]){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'account'){; ?>
								<dt class="fs14">
									<div class="col-md-5"><?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?></div>
									<div class="col-md-6">￥<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['income_all']; ?></div>
									<div class="col-md-6">
									<?php if(SlightPHP\Tpl::$_tpl_vars["lo"]['status'] == -1){; ?>
									<i class="rang-down-icon"></i>
									<?php }elseif((SlightPHP\Tpl::$_tpl_vars["lo"]['status'] == 1)){; ?>
									<i class="rang-up-icon"></i>
									<?php }; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['percent']; ?>
									</div>
									<div class="col-md-3">
									<?php if(SlightPHP\Tpl::$_tpl_vars["xtype"] == 'day'){; ?>
									<a href="/org.main.order?shelf=success&start_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?>&end_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?>" class="sBlue"><?php echo tpl_modifier_tr('查询订单','org'); ?></a>
									<?php }else{; ?>
									<a href="/org.main.order?shelf=success&start_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['start_date']; ?>&end_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['end_date']; ?>" class="sBlue"><?php echo tpl_modifier_tr('查询订单','org'); ?></a>
									<?php }; ?>
									</div>
								</dt>
								<?php }elseif((SlightPHP\Tpl::$_tpl_vars["chartsType"] == 'order')){; ?>
									<dt class="fs14">
										<div class="col-md-5 cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?></div>
										<div class="col-md-6 cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['order_count']; ?></div>
										<div class="col-md-6 cGray">
										<?php if(SlightPHP\Tpl::$_tpl_vars["lo"]['status'] == -1){; ?>
										<i class="rang-down-icon"></i>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["lo"]['status'] == 1)){; ?>
										<i class="rang-up-icon"></i>
										<?php }; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['percent']; ?>
										</div>
										<div class="col-md-3">
										<?php if(SlightPHP\Tpl::$_tpl_vars["xtype"] == 'day'){; ?>
										<a href="/org.main.order?shelf=success&start_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?>&end_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lk"]; ?>" class="sBlue"><?php echo tpl_modifier_tr('查询订单','org'); ?></a>
										<?php }else{; ?>
										<a href="/org.main.order?shelf=success&start_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['start_date']; ?>&end_time=<?php echo SlightPHP\Tpl::$_tpl_vars["lo"]['end_date']; ?>" class="sBlue"><?php echo tpl_modifier_tr('查询订单','org'); ?></a>
										<?php }; ?>
										</div>
									</dt>
								<?php }; ?>
							<?php }; ?>
						<?php }; ?>
                    </dl>
					<div class="col-sm-20 col-md-20 col-xs-20">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
					 <div class="page-list" id="settle_page">
					 </div>
						<?php }; ?>
					</div>
                </div>
            </div>
        </div>
    </div>
    </section>
<?php echo tpl_function_part("/site.main.footer"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script>
	require.config({ 
        paths: { 
            echarts: "<?php echo utility_cdn::jsurl('/assets_v2/js'); ?>"
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/line',
        ],
        function (ec) { 
            var myChart = ec.init(document.getElementById('main'));
            var option = {
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:["<?php echo SlightPHP\Tpl::$_tpl_vars["chartsName"]; ?>"]
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : [<?php echo SlightPHP\Tpl::$_tpl_vars["xdata"]; ?>]
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:"<?php echo SlightPHP\Tpl::$_tpl_vars["chartsName"]; ?>",
                            type:'line',
                            smooth:true,
                            symbol:'emptyCircley',
                            data:[<?php echo SlightPHP\Tpl::$_tpl_vars["ydata"]; ?>]
                        }
                    ]
            };
            myChart.setOption(option);
		}
    );
	$(document).ready(function() {
		<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
		page("settle_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
		<?php }; ?>
		$('#charts_type').change(function(){
			$('#ftime').submit();
		});
		$('#time-picker,#time-picker2').change(function(){
			$('#ftime').submit();
		});
	
	
	});
    $("#time-picker,#time-picker2").datetimepicker({
        lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		formatDate:'Y-m-d',
    });
	var money = $('p.provide-money').text();
	var moneyNumber=$.trim(money.slice(money.indexOf('￥')+1));
	if(moneyNumber=='----' ||!(Number(moneyNumber)>0) ){
		$('#provide-money-btn').css({
			'color':'#ccc',
			'cursor':'default',
			'text-decoration':'none'
		}).on({
			mouseover:function () {
				$(this).css('color','#ccc');
			},
			click:function () {
				return false;
			}
		})
	}
	$('a.doubt-icon').on({
		mouseover:function () {
			$(this).find('span.doubt-content').css('display','block');
		},
		mouseout:function () {
			$(this).find('span.doubt-content').css('display','none');
		}
	})
</script>
</body>
</html>

