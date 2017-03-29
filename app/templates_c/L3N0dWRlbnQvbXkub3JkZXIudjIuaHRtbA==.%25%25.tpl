<!DOCTYPE html>
<html>
<head>
<title>我的订单 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 我的订单 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script>
function ts(SysSecond,thisid,orderid) {
   var SysSecond,InterValObj,sec,min,s,this_id;
        this_id=$('#'+thisid);
        min=SysSecond.split(':')[0];
        sec=SysSecond.split(':')[1];
        s=parseInt(min*60)+parseInt(sec);
        InterValObj = window.setInterval(SetRemainTime, 1000);
        function SetRemainTime() {
            if (s > 0) {
                s = s - 1;
                var second = Math.floor(s % 60);
                var minite = Math.floor(s / 60);
                this_id.find('.cYellow').text(minite + ":" + second);
            } else {
                window.clearInterval(InterValObj);
				$.post("/student.order.updateStatusAjax",{ order_id:orderid,status:'expired' },function(r){
					if(r.error){
				    	alert(r.error);
				        return false;
				    }
				    if(r){
                		this_id.find('.oltc-3').html('超时失效');
                		var this_href=$(this_id).find('.pic a').attr('href');
						var content = '<a class="btn" href="'+this_href+'" target="_blank">重新购买</a>'+
						              '<p class="mt10"><a href="javascript:;" class="delete" order_id="'+orderid+'">删除订单</a></p>';
                		this_id.find('.oltc-4').html(content);
                     }
                 },"json");
            }
        }
    }
</script>
</head>

<body >
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<!-- mob nav -->
			<div class="mob-nav hidden-lg hidden-md">
				<p class="swiper-wrapper" id="mob-nav">
					<a href="/student.main.growth" class="swiper-slide active">我的首页</a>
					<a href="/student.course.mycourse" class="swiper-slide">我的课程</a>
					<a href="/task.commitTask.studentTaskListShow" class="swiper-slide">我的作业</a>
					<a href="/student.order.myorder" class="swiper-slide">我的订单</a>
					<a href="/student.fav.myfav" class="swiper-slide">我的收藏</a>
					<a href="/student.discount.mydiscount" class="swiper-slide">我的优惠券</a>
				</p>
			</div>
<!--studentPortal start-->
<section class="pd30">
    <div class="container">
      <div class="row">
        <!-- leftmenu start-->
		<div class="hidden-xs">
			<?php echo tpl_function_part("/user.main.menu.student.myorder"); ?>
		</div>
        <!-- leftmenu end -->
        <!--right start-->
        <div class="right-main col-md-16 col-xs-20">
          <!--con start-->
            <div class="content">
                <div class="tab-main">
                    <div class="tab-hd fs14">
               			<a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'all'){; ?>curr<?php }; ?>" href="/student.order.myorder/all"><?php echo tpl_modifier_tr('全部订单','LearningCenter'); ?></a>
               			<a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'suc'){; ?>curr<?php }; ?>" href="/student.order.myorder/suc"><?php echo tpl_modifier_tr('交易完成','LearningCenter'); ?></a>
               			<a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'exp'){; ?>curr<?php }; ?>" href="/student.order.myorder/exp"><?php echo tpl_modifier_tr('超时订单','LearningCenter'); ?></a>
               			<a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'cal'){; ?>curr<?php }; ?>" href="/student.order.myorder/cal"><?php echo tpl_modifier_tr('已取消','LearningCenter'); ?></a>
               		</div>
              </div>
              <div class="clear"></div>

              <div id="tabCon">
                <!--tc-li start-->
                <div class="tc-li">
                <!-- 筛选
                	<ul class="col-md-20 col-xs-20 fs14 tec">
                		<li class="col-md-2 col-xs-20">
                			<a href="#" title="" class="c-orange">全部</a>
                		</li>
                		<li class="col-md-2 col-xs-20">
                			<a href="#" title="">课程订单</a>
                		</li>
                		<li class="col-md-2 col-xs-20">
                			<a href="#" title="">会员订单</a>
                		</li>
                	</ul>
                 /筛选 -->
                  <div class="litit mt10 fs14 hidden-xs hidden-sm">
                    <div class="col-md-8 col-sm-9 col-lg-11"><?php echo tpl_modifier_tr('课程信息','LearningCenter'); ?></div>
                    <div class="col-md-4 col-sm-3 col-lg-2"><?php echo tpl_modifier_tr('实付金额','LearningCenter'); ?></div>
                    <div class="col-md-3 col-sm-3 col-lg-3 ter"><?php echo tpl_modifier_tr('订单状态','LearningCenter'); ?></div>
                    <div class="col-md-5 col-sm-5 col-lg-4"><?php echo tpl_modifier_tr('操作','LearningCenter'); ?></div>
                  </div>
                  <!--orderList start-->
				  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["order_list"])){; ?>
				  <?php foreach(SlightPHP\Tpl::$_tpl_vars["order_list"] as SlightPHP\Tpl::$_tpl_vars["o"]){; ?>
                  <div class="order-info mt10 fs14" id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->unique_order_id; ?>">
                  <div class="clearfix">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
                    <div class="col-md-9 col-xs-20  col-sm-9 col-lg-11 oltcon">
                      <div class="col-sm-8 col-xs-10 c-fl pic">
                          <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['url']; ?>">
                            <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['img']; ?>" width="100%" alt=""/>
							  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->course['courseType']==3){; ?>
							  	<span class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></span>
							  <?php }elseif((SlightPHP\Tpl::$_tpl_vars["o"]->course['courseType']==2)){; ?>
							  	<div class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></div>
							  <?php }; ?>
                              <!-- 订单来源-->
							  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->domainUrl[0]; ?>" target="_blank">
								<span class="client-logo-name">
									<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->org_name; ?>
								</span>
							  </a>
                          </a>
                      </div>
                      <div class="col-sm-12 col-xs-10 c-fl oltctxt fsellip">
						  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['url']; ?>">
							<h1><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['name']; ?></h1>
							<p class="">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course['className'])){; ?>
									<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["o"]->course['className'],'site.index'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
								<?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course['teacherName'])){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['teacherName']; ?>
								<?php }; ?>
							</p>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])){; ?>
							<p class="">
							<?php echo date('m',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?><?php echo tpl_modifier_tr('月','LearningCenter'); ?><?php echo date('d',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?><?php echo tpl_modifier_tr('日','LearningCenter'); ?>
							<?php echo date('H:i',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?> <?php echo tpl_modifier_tr('开课','LearningCenter'); ?>
							</p>
							<?php }; ?>
						  </a>
                      </div>
                    </div>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
					<div class="col-md-9 col-xs-20  col-sm-9 col-lg-11 oltcon">
                      <div class="col-sm-8 col-xs-10 c-fl pic">
                          <img src="<?php echo utility_cdn::img('/assets_v2/img/member-order.jpg'); ?>" width="100%" alt=""/>
						  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->domainUrl[0]; ?>">
							<span class="client-logo-name">
								<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->org_name; ?>
							</span>
						  </a>
					  </div>
                      <div class="col-sm-12 col-xs-10 c-fl oltctxt fsellip">
						<h1><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['name']; ?></h1>
						<p class="">有效期：<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['day']; ?>天</p>
                      </div>
                    </div>
					<?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
                    <div class="col-lg-2 col-sm-3 col-md-2 tac col-xs-20 oltcon col-sm-6 oltcon-status">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->price)){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->price_old==0){; ?>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }elseif( (SlightPHP\Tpl::$_tpl_vars["o"]->price_old == SlightPHP\Tpl::$_tpl_vars["o"]->price)){; ?>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }else{; ?>
								<p class="cGray" style="text-decoration:line-through">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price_old; ?></p>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }; ?>
						<?php }elseif( (SlightPHP\Tpl::$_tpl_vars["o"]->price==0)){; ?>

              			<p class="cRed">￥0</p>
						<?php }else{; ?>
							<?php echo tpl_modifier_tr('未设置','LearningCenter'); ?>
						<?php }; ?>
					</div>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
					<div class="col-lg-2 col-sm-3 col-md-2 tac col-xs-20 oltcon col-sm-6 oltcon-status">
						<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['price']; ?></p>
					</div>
					<?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'initial'){; ?>
                    <div class="col-sm-3 col-lg-2 col-md-6 oltcon tac oltcon-status">
						<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["status_array"][SlightPHP\Tpl::$_tpl_vars["o"]->status],'LearningCenter'); ?><script>ts('<?php echo date('i:s',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->expiration_time)-time()); ?>','<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->unique_order_id; ?>','<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>');</script>
						<br /><em class="cYellow"></em>
					</div>
					<?php }else{; ?>
                    <div class="col-sm-3 col-lg-3 col-md-3 hidden-xs oltcon tac oltcon-status">
						<?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["status_array"][SlightPHP\Tpl::$_tpl_vars["o"]->status],'LearningCenter'); ?>
					</div>
					<?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'initial'){; ?>
                    	<div class="col-xs-20 oltcon col-sm-5 col-lg-5 tac">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
                      		<a class="col-sm-20 col-xs-10" href="/order.main.pay/course/<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->uniqueOrderId; ?>" target="_blank">
							<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
							<a class="col-sm-20 col-xs-10" href="/order.main.pay/member/<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->uniqueOrderId; ?>" target="_blank">
							<?php }; ?>
                      		<button class="btn"><?php echo tpl_modifier_tr('立即支付','LearningCenter'); ?></button>
                      		</a>
                      		<p class="lh14 col-xs-10 col-sm-20"><a href="javascript:;" class="cancel" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>"><?php echo tpl_modifier_tr('取消订单','LearningCenter'); ?></a></p>
                    	</div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'success'){; ?>
						  <div class="col-md-6 col-xs-20 oltcon col-sm-5 tac col-lg-4 oltcon-status">
						 	 <?php echo tpl_modifier_tr('支付成功','LearningCenter'); ?>
		                  </div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'expired'){; ?>
                    	<div class="col-sm-5 col-lg-4 col-md-6 col-xs-20 oltcon">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
                      		<a class="col-sm-20 col-xs-10 tac oltcon_btn" href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['payUrl']; ?>" target="_blank">
							<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
							<a class="col-sm-20 col-xs-10 tac oltcon_btn" href='/member.list' target="_blank">
							<?php }; ?>
                      			<button class="btn"><?php echo tpl_modifier_tr('重新购买','LearningCenter'); ?></button>
                      		</a>
					        <p class="col-xs-6 col-sm-20 pad0 tac oltcon_del pd0">
					        	<a href="javascript:;"  class="delete" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>"><?php echo tpl_modifier_tr('删除订单','LearningCenter'); ?></a>
					        </p>
						</div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'cancel'){; ?>
                    	<div class="col-md-6 col-lg-4 col-xs-20 col-sm-5  oltcon oltcon-delete">
					 		<a href="javascript:;"  class="delete" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>"><?php echo tpl_modifier_tr('删除订单','LearningCenter'); ?></a>
						</div>
					  <?php }; ?>
					</div>
                    <div class="oltit">
                      <span class="col-sm-4 col-xs-7 pd0"><?php echo date('Y-m-d',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->create_time)); ?></span>
                      <span class="col-xs-13 col-sm-6"><?php echo tpl_modifier_tr('订单','LearningCenter'); ?>ID：<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->orderSn; ?></span>
						<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->pay_type){; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->price){; ?>
						<span class="hidden-xs col-sm-4"><?php echo tpl_modifier_tr('支付方式','LearningCenter'); ?>：<?php echo SlightPHP\Tpl::$_tpl_vars["pay_type"][SlightPHP\Tpl::$_tpl_vars["o"]->pay_type]; ?></span>
						<?php }; ?>
						<?php }; ?>
					</div>
                  </div>
				  <?php }; ?>
				  <?php }else{; ?>
					  <div class="row">
					  	<div class="my-collect-no-class col-sm-20  col-xs-20 course-schedule mt15">
					       <div class="col-md-20 col-xs-20 mt15 fs14 tac" style="padding-top:60px;">
					          <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" />
					          <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'all'){; ?>
					          <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您还没有订单哦','LearningCenter'); ?>~!</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'suc')){; ?>
					          <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您没有完成的订单哦','LearningCenter'); ?>~!</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'exp')){; ?>
					          <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您没有超时的订单哦','LearningCenter'); ?>~!</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'cal')){; ?>
					          <p style="font-weight:bold;color:#666;"><?php echo tpl_modifier_tr('您没有取消的订单哦','LearningCenter'); ?>~!</p>
					          <?php }; ?>
					       </div>
					   </div>
		            </div>
				  <?php }; ?>
                    <div class="col-sm-20 col-md-20 col-xs-20">
						<div class="page-list" id="order_page"></div>
                    </div>
                  <div class="clear"></div>
                </div>
              </div>
			   <script>
                $(document).ready(function() {
                    $('#tabCon .tc-li').first().show();
                    $('.list-tab>dd').click(function() {
                        $(this).addClass('curr').siblings().removeClass('curr');
                        $('#tabCon>.tc-li:eq(' + $(this).index() + ')').show().siblings().hide();
                    });
					page("order_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
                });
                </script>

          </div>
        </div>
      </div>
</div>
</section>
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
<script>
$(function() {
	$(".cancel").click(function(){
		if(confirm("您确定要取消这个订单吗")){
			var orderid = $(this).attr("order_id");
			var order_status = 'cancel';
			$.post("/student.order.updateStatusAjaxV2",{ order_id:orderid,status:order_status },function(r){
				if(r.code == -1){
					alert(r.msg);
					return false;
				}else{
		    		location.reload();
					return false;
                }
			},"json");
		}
        return false;
	});

    $(".delete").click(function(){
		if(confirm("您确定要删除这个订单吗")){
			var orderid = $(this).attr("order_id");
			var order_status = 'deleted';
            $.post("/student.order.updateStatusAjaxV2",{ order_id:orderid,status:order_status },function(r){
				if(r.code == -1){
					alert(r.msg);
	    			return false;
				}else{
		 			location.reload();
                    return false;
	  			}
	  		},"json");
        }
	  	return false;
    });
	$(".oltcon-delete a").css({
		'float':'left',
		'width':'100%',
		'text-align':'center',
		'padding-top':'38px',
		'line-height':'30px',
	})
});
</script>
</body>
</html>
