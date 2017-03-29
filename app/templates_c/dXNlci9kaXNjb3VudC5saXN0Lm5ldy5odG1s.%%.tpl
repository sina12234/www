<!DOCTYPE html>
<html>
<head>
<title>优惠管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 优惠管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/laypage/laypage.js'); ?>" ></script>
</head>
<style type="text/css">
.search-frame .search-input{ float: right;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav3"); ?>
<!-- mob nav -->
<div class="g-nav hidden-lg hidden-md">
	<ul class="swiper-wrapper" id="mob-nav">
		<li class="swiper-slide"><a href="/org.discount.listnew" class="active">优惠管理</a></li>
		<li class="swiper-slide"><a href="/org.main.order">订单管理</a></li>
	</ul>
</div>
<section class="org-section">
	<div class="container">
		<div class="row">
			<?php echo tpl_function_part("/org.main.menu.discount"); ?>
			<!-- right-main -->
			<div class="right-main col-md-16 col-xs-20">
				<div class="tab-main">
                    <div class="tab-hd fs14">
                        <p class="tab-hd-opt" style="padding-left:0">优惠管理</p>
					</div>
                    <a href="/org.discount.setup" id="faq" class="c-fr add-button"><i class="add-icon c-fl"></i>创建新优惠</a>
                </div>
				<div class="col-md-20 col-xs-20 p0 mt10 mb10">
					<span class="c-fl mt5">状态：</span>
					<select style="width:100px;" id="conpunStatus" class="divselected">
                        <option value="3" selected>全部</option>
						<option value="1">启用</option>
                        <option value="on">过期</option>
                        <option value="2">停用</option>
                    </select>
					<div class="c-fr search-frame p0" id="search-form">
                        <button class="search-box">
                            <span class="search-icon" id="so-btn" style="margin: 0;"></span>
                            <i class="t-list-img clear-icon" id="so-del-btn" style="display:none;"></i>
                    	</button>
                    	<input name="keyword" class="search-input" autocomplete="off" type="text" value="" placeholder="搜索优惠名称">
                    </div>
				</div>
				<!-- list -->
				<ul class="table-grid-code" id="discountList">
					<li class="fs14">
                    	<dl class="hd-title clearfix">
                    		<dd class="col-md-4 col-xs-6 p0">优惠名称</dd>
                            <dd class="col-md-4 col-xs-7 p0">规则</dd>
                            <dd class=" hidden-xs col-md-4 p0">有效期</dd>
                            <dd class="col-md-4 col-xs-7 p0">状态</dd>
                            <dd class="hidden-xs col-md-4 p0">操作</dd>
                   		</dl>
                	</li>
					<li class="listHtml">
						<div class="tac mt20" style="height:240px">
							加载中...
						</div>
					</li>
				</ul>
				<!-- pagelist -->
				<div id="pager" class="mt10 tac"></div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.zclip.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets_v2/js/ie8/ejs.ie8.js"></script>
<![endif]-->
<script id="discountTmp" type="template">
	<% var discount_type,status;for(var i=0;i<items.length;i++){  %>
	<% (items[i].discount_type==1) ? discount_type="满"+items[i].min_fee+"减"+items[i].discount_value +"" : discount_type="满"+items[i].min_fee+"打"+items[i].discount_value*10 +""+"折";%>
	<% switch (items[i].status) {
		case "0":
			status="启用";
			optBtn="stop";
			optText="停用";
			break;
		case -3:
			status="过期";
			optBtn="cgray";
			optText="----";
			break;
		case "-1":
			status="停用";
			optBtn="start";
			optText="启用";
			break; }
	%>
	<dl class="clearfix">
		<dd class="col-md-4 col-xs-7 p0"><a class="blue-link" href="/org.discount.info#<%=items[i].pk_discount%>"><%=items[i].name%></a></dd>
		<dd class="col-md-4 col-xs-6 p0"><%=discount_type%></dd>
		<dd class="col-md-4 hidden-xs p0"><%=items[i].limit_time%></dd>
		<dd class="col-md-4 col-xs-7 p0 discountType"><%=status%></dd>
		<dd class="col-xs-5 hidden-lg hidden-sm hidden-md"></dd>
		<dd class="col-md-4 col-xs-15 p0"><a href="javascript:;" id="copy<%=i%>" data-url="<%=items[i].url%>" class="blue-link copy mr10">复制链接</a><a href="/org.discount.edit#<%=items[i].pk_discount%>" class="blue-link mr10">编辑</a><a data-id="<%=items[i].pk_discount%>" class="<%=optBtn%> mr10 blue-link"><%=optText%></a> <a data-id="<%=items[i].pk_discount%>" class="opt-del blue-link">删除</a></dd>
	</dl>
	<% };%>
</script>
<script>

	$(function(){

		var discountList=$('#discountList');
		var conpunStatus=$('#conpunStatus');
		var searchForm=$('#search-form');
		var cStatus;
		// 搜索
		searchForm.on('click','#so-btn',function(){
			soFun();
		});
		searchForm.on('keydown','input[name="keyword"]',function(e){
			var e = e || event,
             	keycode = e.which || e.keyCode;
            if (keycode==13) {
				soFun();
             }

		});
		function soFun(){
			var _searchText=searchForm.find('input').val() || 0;
			if(_searchText==0){
				layer.msg('请输入关键词！');
				return false;
			}else{
				$('#so-del-btn').show();
			}
			cStatus=$("#conpunStatus").val();
			dList(cStatus,_searchText);
		}
		searchForm.on('click','#so-del-btn',function(){
			searchForm.find('input').val('');
			$(this).hide();
			dList();
		});
		// 改变状态筛选
		conpunStatus.change(function(){
			cStatus=$(this).val();
			if(cStatus=='on'){
				dList('','',cStatus);
			}else{
				dList(cStatus);
			}

		});
		// 获取列表
		dList();
		function dList(status,keys,timeover,curr){
			status = status || 3;
			var page = curr || 1;
			$.ajax('/org/discount/ListV2New',{
				dataType:'json',
				type:'get',
				data:{
					page:page,
					status:status,
	        		search:keys,
	        		time_over:timeover,
				},
				success:function (data) {
					if(data.code==0){
						items=data.data.items;
						if($.isEmptyObject(items)){
							return false;
						}
						var discountTmp=$('#discountTmp').html();
						discountList.find('.listHtml').html(ejs.render(discountTmp, { items:items }));

						// 复制链接
						$("#discountList").clipData({
							ele: '.copy',
							copyurl:'data-url',
						});

						laypage({
	                        cont: $("#pager"),
	                        pages: data.data.totalPage,
	                        curr: curr || 1,
	                        jump: function(obj, first){
	                            if(!first){
	                                dList(status,keys,timeover,obj.curr);
	                            }
	                        }
	                    });
					}else if(data.code == -1){
						var _html='<div class="tac mt20" style="height:240px"><img src="/assets_v2/img/platform/pet3.png"><p>优惠劵不存在~</p></div>';
						discountList.find('.listHtml').html(_html);
					}else {
						layer.msg('查询失败');
					}
				},
				error:function () {
					layer.msg('网络出错了');
				},
			});
		}

		// 根据id删除优惠劵
		discountList.on('click','.opt-del,.start,.stop',function(){
			var _this   = $(this),_className=_this.attr('class'),_status ;
			if(_this.hasClass("start")){
				var _dataid = _this.attr('data-id');
				_status= 0;
				UpdateDiscountStatus(_status,_dataid,_this);
			}else if(_this.hasClass("stop")){
				var _dataid = _this.attr('data-id');
				_status= -1;
				UpdateDiscountStatus(_status,_dataid,_this);
			}else{
				layer.confirm('确认删除优惠劵吗？',{
					btn: ['确认','取消']},
					function(index){
						var _dataid = _this.attr('data-id');
							_status = -2 ;
						UpdateDiscountStatus(_status,_dataid,_this);
						_this.parents('dl').remove();
						layer.close(index);
					},function(index){
						layer.close(index);
				});
			}

		});
	})
	function UpdateDiscountStatus(status,dataid,thisdiv){
		$.ajax('/org/discount/UpdateDiscountStatus',{
			dataType:'json',
			type:'post',
			data:{
				pk_discount:dataid,
				status:status,
			},
			success:function (r) {
				if(r.code==0){
					// layer.msg('操作成功');
					if(status== 0 ){
						thisdiv.text('停用').removeClass('start').addClass('stop');
						thisdiv.closest('dl').find('.discountType').text('启用');
					}else if(status== -1){
						thisdiv.text('启用').removeClass('stop').addClass('start');
						thisdiv.closest('dl').find('.discountType').text('停用');
					}else if(status== -2){
						thisdiv.closest('dl').remove();
						layer.msg('删除成功！');
					}
					//location.href = "/org.discount.listnew";
				}else{
					layer.msg('操作失败');
				}
			},
			error:function () {
				layer.msg('网络出错了');
			},
		});
	}


</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
