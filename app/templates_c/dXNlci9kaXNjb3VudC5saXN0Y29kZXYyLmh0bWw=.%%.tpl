<!DOCTYPE html>
<html>
<head>
<title>优惠码列表 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 专业的K12在线教育平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 优惠码列表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>" ></script>
<script type="text/javascript">
$(document).ready(function(){
			<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total>0){; ?>
			page("page_list", "/org.discount.listcodev2/<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->discount_id; ?>", <?php echo SlightPHP\Tpl::$_tpl_vars["limit"]; ?>, <?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>, Math.ceil(<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["limit"]; ?>));
			<?php }; ?>
			$("#discountcode").on("click", "[data-status]", function(e){
				if(0 == $(this).attr("data-status")){
					$.post("/org.discount.forbidcodev2/"+$(this).attr("data-id")+"/<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->discount_id; ?>");
					$(this).text("<?php echo tpl_modifier_tr('恢复正常','org'); ?>");
					$(this).attr("data-status", -1);
				}else{
					$.post("/org.discount.recovercodev2/"+$(this).attr("data-id")+"/<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->discount_id; ?>");
					$(this).text("<?php echo tpl_modifier_tr('设置失效','org'); ?>");
					$(this).attr("data-status", 0);
				}
			});
			/*$("#discountcode").on("mouseenter", "span[data-code=1]", function(e){
				$(this).hide();
				$(this).next().show();
			});
			$("#discountcode").on("mouseleave", "span[data-code=2]", function(e){
				$(this).hide();
				$(this).prev().show();
			}).on("click", "span[data-code=2]", function(e){
				$(this).prev();
			});*/
		$("#exportPromoCode").click(function(){
				var discount_id = $(this).attr("data-item");
				window.location.href="/phpexcel/platformstu/exportPromoCode/"+discount_id;
	});
});		
</script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd40">
		<div class="container">
		<div class="row">
			<?php echo tpl_function_part("/org.main.menu.discount"); ?>
			<div class="right-main col-sm-9 col-md-16">
				<div class="content">
					<div class='coupon-list-2-box'>
						<div class='color-plan-1'>
							<div class='nav-color col-sm-12 col-xs-12'>
								<li></li>
								<p><?php echo tpl_modifier_tr('使用统计','org'); ?></p>
							</div>
						</div>
						<div class="cou-nav fs14">
							<dl>
								<dd>
									<p class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('优惠码数量','org'); ?></p>
									<p class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('使用次数','org'); ?></p>
									<p class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('剩余有效个数','org'); ?></p>
									<p class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('单张券限制','org'); ?></p>
									<p class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('单个用户限制','org'); ?></p>
								</dd>
								<?php if(0==SlightPHP\Tpl::$_tpl_vars["discounts"]->total){; ?>
								<dt>
									<p class="col-sm-4">0</p>
									<p class="col-sm-4">0</p>
									<p class="col-sm-4">0</p>
									<p class="col-sm-4">0</p>
								</dt>
								<?php }else{; ?>
								<dt>
									<p class="col-sm-3 col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->total; ?></p>
									<p class="col-sm-3 col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->used; ?></p>
									<p class="col-sm-3 col-md-4"><?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total_num<0){; ?><?php echo tpl_modifier_tr('无限制','org'); ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->total_num-SlightPHP\Tpl::$_tpl_vars["discounts"]->used; ?><?php }; ?></p>
                                    <p class="col-sm-3 col-md-4"><?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->data[0]->total_num==0){; ?><?php echo tpl_modifier_tr('不限制','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('使用','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->data[0]->total_num; ?><?php echo tpl_modifier_tr('次','org'); ?><?php }; ?></p>
									<p class="col-sm-3 col-md-4"><?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->user_limit==0){; ?><?php echo tpl_modifier_tr('不限制','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('使用','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->user_limit; ?><?php echo tpl_modifier_tr('次','org'); ?><?php }; ?></p>
								</dt>
								<?php }; ?>
							</dl>
						</div>
					</div>
					<div class='coupon-box'>
						<div class='color-plan-1'>
							<div class='nav-color col-sm-12 col-xs-12'>
								<li></li>
								<p><?php echo tpl_modifier_tr('优惠码列表','org'); ?></p>
								<div class='produce'>
									<p><?php echo tpl_modifier_tr('新优惠码','org'); ?></p>
									<form method="POST" action="/org.discount.createcodev2/<?php echo SlightPHP\Tpl::$_tpl_vars["discounts"]->discount->discount_id; ?>">
										<select name="num">
											<option value="50"><?php echo tpl_modifier_tr('50个','org'); ?></option>
											<option value="20"><?php echo tpl_modifier_tr('20个','org'); ?></option>
											<option value="10"><?php echo tpl_modifier_tr('10个','org'); ?></option>
											<option value="5"><?php echo tpl_modifier_tr('5个','org'); ?></option>
											<option value="1"><?php echo tpl_modifier_tr('1个','org'); ?></option>
										</select>
										<button><?php echo tpl_modifier_tr('生成','org'); ?></button>
									</form>
									<div style="float:right;"><a id="exportPromoCode" data-item="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["discount_id"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["discount_id"]; ?><?php }; ?>">导出excel</a></div>
								</div>
							</div>
						</div>
						<table class="cou-nav-1 fs14 table-grid ">
								<thead id="discountcode" class="c-fl col-md-20 pd0">
								<?php if(SlightPHP\Tpl::$_tpl_vars["discounts"]->total>0){; ?>
									<tr class="c-fl col-md-20 pd0">
										<td class="col-md-4"><?php echo tpl_modifier_tr('优惠码','org'); ?></td>
										<td class="col-md-4"><?php echo tpl_modifier_tr('有效时间','org'); ?></td>
										<td class="col-md-4"><?php echo tpl_modifier_tr('使用次数','org'); ?></td>
										<td class="col-md-4"><?php echo tpl_modifier_tr('使用情况','org'); ?></td>
										<td class="col-md-4"><?php echo tpl_modifier_tr('操作','org'); ?></td>
									</tr>
								</thead>
								<tbody class="c-fl col-md-20 pd0">
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["discounts"]->data as SlightPHP\Tpl::$_tpl_vars["i"]){; ?>
									<tr class="c-fl col-md-20 pd0">
										<td class="col-md-4 pd0">
											<p class="ver"><a href="/org.discount.listcodeusedv2/<?php echo SlightPHP\Tpl::$_tpl_vars["i"]->discount_code; ?>" data-code="1"><?php echo SlightPHP\Tpl::$_tpl_vars["i"]->discount_code; ?></a><span data-code="2" style="display:none;">点击复制</span></p>
										</td>
										<td class="col-md-4 pd0">
											<p class=""><?php echo substr(SlightPHP\Tpl::$_tpl_vars["i"]->starttime,0,10); ?> <?php echo tpl_modifier_tr('至','site.teacher'); ?> <?php echo substr(SlightPHP\Tpl::$_tpl_vars["i"]->endtime,0,10); ?></p>
										</td>
										<td class="col-md-4 pd0">
											<p class=""><?php echo SlightPHP\Tpl::$_tpl_vars["i"]->used_num; ?></p>
										</td>
										<td class="col-md-4 pd0">
											<p class=""><?php if(SlightPHP\Tpl::$_tpl_vars["i"]->used_num>0){; ?><a href="/org.discount.listcodeusedv2/<?php echo SlightPHP\Tpl::$_tpl_vars["i"]->discount_code; ?>"><?php echo tpl_modifier_tr('使用详情','org'); ?></a><?php }else{; ?><?php echo tpl_modifier_tr('未使用','org'); ?><?php }; ?></p>
										</td>
										<td class="col-md-4 pd0">
											<p class="" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["i"]->discount_code_id; ?>" data-status="<?php echo SlightPHP\Tpl::$_tpl_vars["i"]->status; ?>"><?php if(0==SlightPHP\Tpl::$_tpl_vars["i"]->status){; ?><?php echo tpl_modifier_tr('设置失效','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('恢复正常','org'); ?><?php }; ?></p>
										</td>
									</tr>
									<?php }; ?>
								</tbody>
								<?php }; ?>
						</table>
						<div class='page-list' id="page_list">
						</div>
					</div>

				</div>
			</div>
		</div>
		</div>
        <div class='clear'></div>
    </section>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
