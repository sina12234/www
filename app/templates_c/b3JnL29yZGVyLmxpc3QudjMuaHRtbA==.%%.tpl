<!DOCTYPE html>
<html>
<head>
<title>订单管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 订单管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- mob nav -->
<div class="g-nav hidden-lg hidden-md">
	<ul class="swiper-wrapper" id="mob-nav">
		<li class="swiper-slide"><a href="/org.discount.listnew">优惠管理</a></li>
		<li class="swiper-slide"><a href="/org.main.order" class="active">订单管理</a></li>
	</ul>
</div>
<!--直播列表 start-->
<section class="org-section">
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/org.main.menu.order"); ?>
            <div class="col-lg-16 right-main">
                <div class="tab-main hidden-xs hidden-sm fs14">
                    <div class="tab-hd">
                        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["flag"]=='all'){; ?>curr<?php }; ?>" href="org.main.order?shelf=all"><?php echo tpl_modifier_tr('全部订单','org'); ?></a>
                        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["flag"]=='success'){; ?>curr<?php }; ?>" href="org.main.order?shelf=success"><?php echo tpl_modifier_tr('交易成功','org'); ?></a>
                        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["flag"]=='expired'){; ?>curr<?php }; ?>" href="org.main.order?shelf=expired"><?php echo tpl_modifier_tr('已失效','org'); ?></a>
                        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["flag"]=='initial'){; ?>curr<?php }; ?>" href="org.main.order?shelf=initial"><?php echo tpl_modifier_tr('待支付','org'); ?></a>
                    </div>
                </div>
                <!--搜索-->
                <div class="order-so mt10">
					<!--时间搜索-->
					<div class="order-so-time hidden-xs hidden-sm">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['start_time'])){; ?>
							<div class="pos-rel c-fl mr5">时间：<input type="text"   readonly name="start_time"  id="start_time" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['start_time'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["params"]['start_time']; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('开始时间','org'); ?>">
							</div>
							<?php }else{; ?>
							<div class="pos-rel c-fl mr5">时间：<input type="text" readonly name="start_time"  id="start_time" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['start_time'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["params"]['start_time']; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('开始时间','org'); ?>">
								<!--<a href="javascript:void(0)" class="close-data" id="close_start"></a>-->
                            </div>
							<?php }; ?>
							<div class="pos-rel c-fl">至 <input type="text" class="ml5"  readonly name="end_time" id="end_time" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['end_time'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["params"]['end_time']; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('结束时间','org'); ?>">
								<a href="javascript:void(0)" class="close-data" id="close_end" style="top:5px;" ></a>
							</div>
					</div>

					<div class="order-so-discount c-fl mr10">
						<span class="hidden-xs hidden-sm">订单类型：</span>
						<select name="orderType" >
							<option value="0" <?php if(SlightPHP\Tpl::$_tpl_vars["orderType"]==0){; ?>selected<?php }; ?>>全部</option>
							<option value="1" <?php if(SlightPHP\Tpl::$_tpl_vars["orderType"]==1){; ?>selected<?php }; ?>>课程订单</option>
							<option value="11" <?php if(SlightPHP\Tpl::$_tpl_vars["orderType"]==11){; ?>selected<?php }; ?>>会员订单</option>
                        </select>
					</div>

					<select name='search' id="sea" class="left"  >
						<option value='3' <?php if(SlightPHP\Tpl::$_tpl_vars["search"]==3){; ?>selected<?php }; ?>>手机号</option>
						<option value='1' <?php if(SlightPHP\Tpl::$_tpl_vars["search"]==1){; ?>selected<?php }; ?>>订单号</option>
						<option value='2' <?php if(SlightPHP\Tpl::$_tpl_vars["search"]==2){; ?>selected<?php }; ?>>课程名</option>
						<option value='4' <?php if(SlightPHP\Tpl::$_tpl_vars["search"]==4){; ?>selected<?php }; ?>>用户名</option>
					</select>
					<input type="text" style="float: left;" name="keywords" value="<?php if(SlightPHP\Tpl::$_tpl_vars["keywords"]!=''){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["keywords"]; ?><?php }; ?>">
					<button id="search"></button>
                    <!--<a href="javascript:void(0)" class="close-key" id="close_key"></a>-->
                    <div class="so-more hidden-xs hidden-sm" id="so_more_btn">
						<em>更多条件</em>
						<span class="arrow"></span>
					</div>

                </div>
                <!--高级搜索-->

                <div class="order-so-more mt10" id="order_more" style="display:<?php if(SlightPHP\Tpl::$_tpl_vars["type"]){; ?>block<?php }else{; ?>none<?php }; ?>;">
                    <div class="order-so-price" id="order_so_price">
                        金额：<input type="text" name="price1" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['price1'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["params"]['price1']; ?><?php }; ?>" class="mr5">至 <input type="text" name="price2" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['price2'])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["params"]['price2']; ?><?php }; ?>" class="mr10 ml5">
                        <button id='so_price'>确定</button>
                    </div>
                    <div class="order-so-discount c-fl">优惠：
                        <select name="discount" >
                            <option value="2" <?php if(SlightPHP\Tpl::$_tpl_vars["discount"]=='2'){; ?>selected<?php }; ?>>全部</option>
                            <option value="1" <?php if(SlightPHP\Tpl::$_tpl_vars["discount"]==1){; ?>selected<?php }; ?>>有优惠</option>
                            <option value="0" <?php if(SlightPHP\Tpl::$_tpl_vars["discount"]==0){; ?>selected<?php }; ?>>无优惠</option>
                        </select>
                    </div>

                    <div class="distribution-order">
						<input type="checkbox" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["params"]['resell'])){; ?>checked<?php }; ?> id="resell" />
                        <span class="tag">分销订单</span>
                    </div>
                </div>
                <!--订单列表-->
                <p class="order-list-num hidden-xs hidden-sm">
					<span class="left fs14 cGray" id="order-num">共 <span><?php echo SlightPHP\Tpl::$_tpl_vars["totalSize"]; ?></span> 个订单</span>
                    <a id="exportOrderData" class="c-fr blue-link" href="javascript:void(0);"><?php echo tpl_modifier_tr('导出excel','org'); ?></a>
				</p>
                <dl class="order-list mt5">
                    <dt class="hidden-xs hidden-sm">
                        <p class="col-lg-8"><?php echo tpl_modifier_tr('商品','org'); ?></p>
                        <p class="col-lg-3"><?php echo tpl_modifier_tr('用户信息','org'); ?></p>
                        <p class="col-lg-3"><?php echo tpl_modifier_tr('优惠','org'); ?></p>
                        <p class="col-lg-3"><?php echo tpl_modifier_tr('实付款','org'); ?></p>
                        <p class="col-lg-3"><?php echo tpl_modifier_tr('交易状态','org'); ?></p>
                    </dt>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["orderList"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["orderList"] as SlightPHP\Tpl::$_tpl_vars["val"]){; ?>
                    <dd class="fs14">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->member)){; ?>
						<div class="col-lg-8 col-xs-10 order-item-pro">
							<div class="col-lg-8 order-item-img">
								<img src="<?php echo utility_cdn::img('/assets_v2/img/member-order.png'); ?>" alt="">
							</div>
                            <a href="javascript:;" class="col-lg-12 order-item-title">
                                <span class="col-sm-20 pd0 order-title-info"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->member['name']; ?></span>
								<span class="cLightgray col-sm-20 pd0">有效期：<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->member['day']; ?></span>
                            </a>
                        </div>
						<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["val"]->course)){; ?>
                        <p class="col-lg-4 col-xs-10 order-item-pro">
                            <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->object_id; ?>" class="order-item-img">
								<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->course['img']; ?>" alt="">
							</a>
                        </p>
                        <p class="col-lg-4 col-xs-10 order-item-title order-item-pro">
                            <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->object_id; ?>">
                                <span class="col-sm-20 col-xs-20 pd0 order-title-info"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->course['name']; ?></span>
                                <span class="cLightgray col-sm-20 col-xs-20 pd0">班级：<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->course['class'][SlightPHP\Tpl::$_tpl_vars["val"]->ext]['className']; ?></span>
                            </a>
                        </p>
						<?php }; ?>
                        <p class="col-lg-3 col-xs-10 order-item-tac order-item-pro"><em><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->stuName; ?></em><em><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->mobile; ?></em></p>
						<div class="col-lg-6 col-xs-20 order-item-pro">
							<p class="col-xs-10 order-item-tac">
	                            <span>
	                                <em>
										<span class="hidden-lg hidden-md">优惠：</span>
	                                    <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status == 0){; ?>无
	                                    <?php }else{; ?>
	                                  ￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->disPrice; ?>
	                                    <?php }; ?>
	                                </em>
	                                <em class="hidden-xs hidden-sm">
	                                    <?php echo SlightPHP\Tpl::$_tpl_vars["val"]->discount; ?>
	                                </em>
	                            </span>
	                        </p>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->member['price'])){; ?>
							<p class="col-lg-6 hidden-sm order-item-tac">
								<span class="hidden-lg hidden-md">实付款：</span>
								<em class="cYellow" id="price_<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->fk_order; ?>">
									<span><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->member['price']; ?></span>
	                                <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->price_old!=SlightPHP\Tpl::$_tpl_vars["val"]->price && SlightPHP\Tpl::$_tpl_vars["val"]->change_status==1){; ?><var class="fs_dsc">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_old; ?></var><?php }; ?>
								</em>
	                            <!--修改价格-->
	                            <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && empty(SlightPHP\Tpl::$_tpl_vars["val"]->promote_status) && empty(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status)){; ?>
	                            <em><a href="javascript:;" data-price="<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_old; ?>" class="update-order-price" orderId = "<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->fk_order; ?>">修改价格</a></em>
	                            <?php }; ?>
	                            <!--不可修改价格-->
	                            <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && !empty(SlightPHP\Tpl::$_tpl_vars["val"]->promote_status)){; ?>
	                            <span class="fs12 cGray">
	                                <var class="set-info-gray-icon"></var>
	                                推广课程不可以修改价格
	                            </span>
	                            <?php }elseif( SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && !empty(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status)){; ?>
	                            <span class="fs12 cGray">
	                            <var class="set-info-gray-icon"></var>
	                            优惠课程不可以修改价格
	                            </span>
	                            <?php }; ?>
							</p>
							<?php }else{; ?>
							<p class="col-xs-10 order-item-tac">
								<span class="hidden-lg hidden-md">实付款：</span>
								<em class="cYellow fs14" id="price_<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->fk_order; ?>">
									<span><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price; ?></span>
	                                <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->price_old!=SlightPHP\Tpl::$_tpl_vars["val"]->price && SlightPHP\Tpl::$_tpl_vars["val"]->change_status==1){; ?><var class="fs_dsc">￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_old; ?></var><?php }; ?>
	                            </em>
	                            <em class="hidden-xs hidden-sm">
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status)){; ?>
									<s class="cGray fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_old; ?></s>
									<?php }; ?>
								</em>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="success"&& !is_null(SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status)&&(SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status==1 or SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status==2)){; ?>
                                    <em class="cYellow fs14">
                                        <var class="fs_dsc">已退费￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_refund; ?></var>
                                    </em>
                                <?php }; ?>
	                            <!--修改价格-->
	                            <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && empty(SlightPHP\Tpl::$_tpl_vars["val"]->promote_status) && empty(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status)){; ?>
	                            <em><a href="javascript:;" data-price="<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_old; ?>" class="update-order-price" orderId = "<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->fk_order; ?>">修改价格</a></em>
	                            <?php }; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["val"]->promote_status){; ?>
	                            <span class="distribution-price col-md-20 p0">
	                                <span class="distribution-content">(含推广费: ￥<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->price_promote/100; ?>) <br/> </span>
	                                <span class="distribution-tag"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->resellName; ?>分销</span>
	                            </span>
								<?php }; ?>
	                            <!--不可修改价格-->
	                            <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && !empty(SlightPHP\Tpl::$_tpl_vars["val"]->promote_status)){; ?>
	                            <span class="fs12 cGray">
	                                <var class="set-info-gray-icon"></var>
	                                推广课程不可以修改价格
	                            </span>
	                            <?php }elseif( SlightPHP\Tpl::$_tpl_vars["val"]->status=="initial" && !empty(SlightPHP\Tpl::$_tpl_vars["val"]->discount_status)){; ?>
	                            <span class="fs12 cGray">
	                            <var class="set-info-gray-icon"></var>
	                            优惠课程不可以修改价格
	                            </span>
	                            <?php }; ?>
							</p>
							<?php }; ?>
						</div>
                        <p class="col-lg-3 col-xs-20 hidden-sm order-item-tac order-item-pro">
							<em class="sBlue">&nbsp;状态 :  <?php echo SlightPHP\Tpl::$_tpl_vars["val"]->orderStatus; ?></em>
							<span class="fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["val"]->create_time; ?></span>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["val"]->status=="success"){; ?>
                                <?php if(!is_null(SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status) && SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status==0){; ?>
                                    <em class="sBlue">退费审核中</em>
                                <?php }elseif( !is_null(SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status)&&(SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status==1 or SlightPHP\Tpl::$_tpl_vars["val"]->content_refund_status==2)){; ?>
                                    <em class="sBlue">退费成功</em>
                                <?php }elseif( SlightPHP\Tpl::$_tpl_vars["val"]->price>0&&SlightPHP\Tpl::$_tpl_vars["val"]->promote_status==0&&SlightPHP\Tpl::$_tpl_vars["val"]->object_type==1){; ?>
                                    <em class="sBlue"><a href="/org.main.refund/<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->fk_order; ?>">+申请退费</a></em>
                                <?php }; ?>
                            <?php }; ?>
						</p>
                        <p class="col-lg-20 col-xs-20 order-item-thumb fs12">
							<span class="left">订单ID:<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->orderSn; ?></span>
							<span class="right hidden-xs">
								<?php echo SlightPHP\Tpl::$_tpl_vars["val"]->payType; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["val"]->uniqueOrderId)){; ?>支付ID: <?php echo SlightPHP\Tpl::$_tpl_vars["val"]->uniqueOrderId; ?><?php }; ?>
							</span>
						</p>
                    </dd>
					<?php }; ?>
                </dl>
				<div class="page-list" id="pagepage"></div>
				<?php }else{; ?>
					<div class="col-xs-20 fs14 tac" style="padding-top:60px;display:block;">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>">
                        <p>您查询的订单不存在~</p>
                    </div>
				<?php }; ?>
            </div>
        </div>
    </div>
</section>
<div class="col-xs-20 pd20 tac" id="execl-layer" style="display:none">
    <p class="pb10 fs14">导出条数超出上线(1000)，<br>请修改删选条件</p>
    <a href="javascript:void(0);" class="btn">确定</a>
</div>
<section id="update-price-info" style="display:none;">
    <ul class="fs14 update-price-info clearfix mt25">
        <li class="col-xs-20 p0 mb20">
            <span class="col-xs-5">订单原价：</span>
            <span class="col-xs-15 cYellow p0">￥<i id="orderPrice">800</i></span>
        </li>
        <li class="col-xs-20 p0 mb30">
            <span class="col-xs-5">改后价格：</span>
            <input class="col-xs-8 new-order-price mr5" onkeyup="clearNum(this)" type="text" id="updatePrice" />
            <span class="col-xs-1 p0">元</span>
        </li>
        <li class="col-xs-20 p0 tac">
            <button class="yellow-btn mr10">确认</button>
            <button class="gray-button">取消</button>
            <input type="hidden" value="" id="orderId">
        </li>
    </ul>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>
    // 导出execl
    $("#exportOrderData").click(function(){
        var str  = '';
        var flag = "<?php echo SlightPHP\Tpl::$_tpl_vars["flag"]; ?>";
        var resell= getUrlParam('resell');
        var search = $("#sea option:selected").val();
        var discount = $("select[name='discount'] option:selected").val();
        var orderType = $("select[name='orderType'] option:selected").val();
        var keywords = $("input[name='keywords']").val();
        var price1 = $("input[name='price1']").val();
        var price2 = $("input[name='price2']").val();
        var startTime = $("#start_time").val();
        var endTime   = $("#end_time").val();
        var date1 = new Date(startTime);
        var date2 = new Date(endTime);
        var orderNum = $('#order-num span').text();

        var date4 = Math.floor((date2 - date1)/(24*3600*1000));

        str+="start_time="+startTime+"&end_time="+endTime+"&shelf="+flag+"&resell="+resell+"&orderType="+orderType+"&discount="+discount
        if(price1!=''&&price2!=''){
            str+="&price1="+price1+"&price2="+price2;
        }
        if(keywords!=''&&search!=''){
            str+="&keywords="+keywords+"&search="+search;
        }
        if(orderNum > 1000) {
                $('#execl-layer p').html('导出条数超出上线(1000)，<br>请修改筛选条件');
                layer.open({
                    type: 1,
                    shadeClose: true,
                    title: '提示',
                    closeBtn: true,
                    area: ['315px', '180px'],
                    content: $('#execl-layer')
                });
                return false;
        }
        location.href = "/org.main.orderExcel?" + str;
    });
    $('#execl-layer .btn').click(function(){
        layer.closeAll();
    });
    $(function(){
        //初始化 提交数据
        var submitData={  };
        submitData['start_time'] = getUrlParam('start_time');
        submitData['end_time'] = getUrlParam('end_time');
        submitData ['keywords'] = getUrlParam('keywords');
        submitData['shelf'] = getUrlParam('shelf') ? getUrlParam('shelf') : 'all';
        submitData['search'] = getUrlParam('search');
        submitData['discount'] = getUrlParam('discount');
        submitData['orderType'] = getUrlParam('orderType');
        submitData['price1'] = getUrlParam('price1');
        submitData['price2'] = getUrlParam('price2');
		submitData['resell'] = getUrlParam('resell');
        var order_so_price = $("#order_so_price");
        $("#so_more_btn").click(function(){
            if($("#order_more").is(":hidden")){
                $(this).find(".arrow").css("background-position","5px -33px");
                $(this).find("em").text("收起条件");
            }else{
                $(this).find(".arrow").css("background-position","5px -18px");
                $(this).find("em").text("更多条件");
            }
            $("#order_more").toggle();
        });
        $("#start_time").datetimepicker({
            format: 'Y/m/d',
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$('#end_time').val()?$('#end_time').val():false
                })
            },
            timepicker: false
        });
        $("#end_time").datetimepicker({
            format: 'Y/m/d',
            onShow:function( ct ){
                this.setOptions({
                    minDate:$('#start_time').val()?$('#start_time').val():false
                })
            },
            timepicker: false
        });
        $('#so_price').show();
        order_so_price.on({
            mouseover:function () {
                $(this).find("button").css('visibility','visible');
            },
            mouseout:function () {
                $(this).find("button").css('visibility','hidden');
            }
        }).find('#so_price').on('click',function () {
            var price1 =  $.trim(order_so_price.find('input[name=price1]').val());
            var price2 =  $.trim(order_so_price.find('input[name=price2]').val());
            submitData['price1'] = price1 == "" ? 0: price1;
            submitData['price2'] = price2 == "" ? 0: price2;
            if(price1!=0||price2!=0){
                updatePage();
            }
        });
        page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
        var _end_time = submitData['end_time'];
        if (!_end_time && typeof(_end_time)!="undefined" && _end_time!=0){

        }else{
            $('#close_end').show();
        }
        var discountSelect = $('div.order-so-discount');
        discountSelect.find('select').change(function (event) {
            if($(this).val()!=''){
                if(event.target.name=='discount'){
                    submitData['discount']=$(this).val() ;
                }else if(event.target.name=='orderType'){
                    submitData['orderType']=$(this).val();
                }
                updatePage();
            }
        });
        //keywords
        $('div.order-so').on('click','#search',function () {
            var keywords = $(this).prev('input').val();
            submitData['search'] = $('#sea').val();
            submitData['keywords'] = keywords;
            updatePage();
        });
        $('#start_time,#end_time').change(function (event) {
            if($(this).val() != ''){
                var value = $(this).val().replace(/\//g,'-');
                if(event.target.id=='start_time'){
                    submitData['start_time']=value ;
                }else if(event.target.id=='end_time'){
                    submitData['end_time']=value;
                }
            }

            if( submitData['start_time']&& submitData['end_time']){
                updatePage();
            }
        })
        $('#close_end').click(function () {
            $('#start_time,#end_time').val('');
            submitData['start_time']=null ;
            submitData['end_time']=null;
            $(this).hide();
            updatePage();
        })
		$('#resell').on('change',function(){
			if($(this).is(':checked')){
				submitData['resell']=1;
			}else{
				submitData['resell']=0;
			}
			updatePage();
		})
        function  updatePage() {
            var str='shelf='+submitData['shelf'];
            $.each(submitData,function (key,data) {
                if(data != '' && data != null && key != 'shelf' ){
                    str += "&"+key+"="+data;
                }
            });
            window.location.href="/org.main.order?"+str;
        }
    });
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值
    }
    function clearNum(obj) {
        obj.value = obj.value.replace( /[^\d.]/g, "" );
        obj.value = obj.value.replace( /^\./g, "" );
        obj.value = obj.value.replace( /\.{ 2, }/g, "." );
        obj.value = obj.value.replace( ".","$#$" ).replace( /\./g, "" ).replace( "$#$", "." );
        obj.value = obj.value.replace( /^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3' );
    }
;(function() {
    $('.order-list .update-order-price').click(function() {
        var price = $(this).attr('data-price');
        $('#update-price-info .cYellow').html('￥<i id="orderPrice">' + price+'</i>');
        $('#update-price-info .cYellow').attr('data-price', price);
        var orderId =  $(this).attr('orderId');
        $('#orderId').val(orderId);
        layer.open({
          type: 1,
          shade: true,
          title: ['修改订单价格'],
          area:['400px', '220px'],
          content: $('#update-price-info'),
          cancel: function(){
            $('#update-price-info .new-order-price').val('');
            layer.closeAll();
          }
        });
    });
    $('#update-price-info .yellow-btn').click(function() {
        var price = $('#update-price-info .cYellow').attr('data-price');
        if($('#update-price-info .new-order-price').val() == '') {
            layer.msg('请输入价格');
            return false
        }
        var updatePrice = $('#updatePrice').val()*100;
        var orderPrice = $.trim($('#orderPrice').text());
        var orderId = $('#orderId').val();
        if(orderId===''){
            layer.msg('请刷新页面！');
            return false;
        }
        if(updatePrice===''){
            layer.msg('请输入价格');
            return false;
        }
        if(updatePrice>=(orderPrice*100)){
            layer.msg('输入的价格应为0-'+orderPrice+'之间');
            return false
        }
        $.post("/org/ajax/updateOrderPrice",{ price:updatePrice,orderId:orderId },
                function(r){
                    if(r.code==0){
                        $('#price_'+orderId).html('￥<span>'+(updatePrice/100)+'</span><br/><var class="fs_dsc">￥'+orderPrice+'</var>');
                        layer.msg('修改成功！');
                        $('#update-price-info .new-order-price').val('');
                        layer.closeAll();
                    }else{
                        if(r.code==-4 || r.code==-5 || r.code==-6){
                            layer.msg(r.msg);
                            return false;
                        }else{
                            layer.msg('修改订单价格失败！');
                            return false;
                        }

                    }
                },
                "json");

    });
    $('#update-price-info .gray-button').click(function() {
        $('#update-price-info .new-order-price').val('');
        layer.closeAll();
    });
    $('#updatePrice').keyup(function(){
        var updatePrice = $(this).val()*100;
        var orderPrice = $.trim($('#orderPrice').text());
        if(updatePrice>=(orderPrice*100)){
            layer.msg('输入的价格应为0-'+orderPrice+'之间');
            return false
        }
    });
})()
</script>
</html>
