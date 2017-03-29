<!DOCTYPE html>
<html>

<head>
<title>我的订单 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="我的订单 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
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
				$.post("/index.student.updateOrderStatusAjax",{ order_id:orderid,status:'expired' },function(r){
					if(r.error){
				    	alert(r.error);
				        return false;
				    }
				    if(r){
                		this_id.find('.oltc-3').html('超时失效');
                		var this_href=$(this_id).find('.pic a').attr('href');
						var content = '<input class="btn_yellow_2_1" onclick="location.href="'+this_href+'" value="重新购买" type="submit">'+
						              '<p class="mt10"><a href="javascript:;" class="delete" order_id="'+orderid+'">删除订单</a></p>';
                		this_id.find('.oltc-4').html(content);
                     }
                 },"json");
            }
        }
    }
  </script>
</head>
<body class="bgf7">
	<?php echo tpl_function_part("/index.main.usernav/student"); ?>
    <!--studentPortal start-->
    <section class="p20">
    <div class="container">
      <div class="row">
        <!-- leftmenu start-->
			<?php echo tpl_function_part("/index.main.menu/student"); ?>
        <!-- leftmenu end -->

        <!--right start-->
        <div class="col-md-16 col-sm-20 col-xs-20">
          <!--con start-->
          <div class="right-content">
              <dl class="list-tab mt30 fs14 cy-list-tab col-sm-20 col-xs-20">
               <a class="col-xs-5 col-sm-3" href="/index.student.order/all"><dd <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == "all"){; ?>class="curr"<?php }; ?>>全部订单<i></i></dd></a>
               <a class="col-xs-5 col-sm-3" href="/index.student.order/suc"><dd <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == "suc"){; ?>class="curr"<?php }; ?>>交易完成<i></i></dd></a>
               <a class="col-xs-5 col-sm-3" href="/index.student.order/exp"><dd <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == "exp"){; ?>class="curr"<?php }; ?>>超时订单<i></i></dd></a>
               <a class="col-xs-4 col-sm-3" href="/index.student.order/cal"><dd <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == "cal"){; ?>class="curr"<?php }; ?>>已取消<i></i></dd></a>
              </dl>
              <div class="clear"></div>

              <div id="tabCon" class="cy-tab-content">
                <!--tc-li start-->
                <div class="tc-li">
                <!-- 筛选 
                	<ul class="col-md-20 col-xs-20 fs14 tec">
                		<li class="col-md-3 col-xs-20">
                			<a href="#" title="" class="cBlue">全部</a>
                		</li>
                		<li class="col-md-3 col-xs-20">
                			<a href="#" title="">课程订单</a>
                		</li>
                		<li class="col-md-3 col-xs-20">
                			<a href="#" title="">会员订单</a>
                		</li>
                	</ul>		
                 /筛选 -->
                  <div class="litit mt10 fs14 hidden-xs">
                    <div class="col-md-8 col-sm-9 col-lg-11">课程信息</div>
                    <div class="col-md-4 col-sm-3 col-lg-2">实付金额</div>
                    <div class="col-md-4 col-sm-3 col-lg-2">订单状态</div>
                    <div class="col-md-4 col-sm-5 col-lg-5">操作</div>
                  </div>
                  <!--orderList start-->
				  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["order_list"])){; ?>
				  <?php foreach(SlightPHP\Tpl::$_tpl_vars["order_list"] as SlightPHP\Tpl::$_tpl_vars["o"]){; ?>
                  <div class="orderList mt20 fs14" id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->unique_order_id; ?>">
				  
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
                    <div class="col-md-9 oltcon oltc-1 col-xs-20  col-sm-9 col-lg-11">
                      <div class="col-sm-8 col-xs-10 c-fl pic">
                          <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['url']; ?>">
                             <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['img']; ?>" alt=""/>
							<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->course['courseType'] == 3){; ?>
							<span class="lineclass-icon fs12">线下</span>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["o"]->course['courseType'] == 2)){; ?>
							<span class="record-icon fs12">录播</span>
							<?php }; ?>
						</a>
							<div class="col-md-20 mt10 pd0">
							  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->domainUrl[0]; ?>" target="_blank">
							  	<span class="client-logo-name mt10">
                                	<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->org_name; ?>
                            	</span>
							  </a>
							 </div>
                      </div>
                      <div class="col-sm-12 col-xs-10 c-fl oltctxt fsellip">
						  <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['url']; ?>">
							<h1><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['name']; ?></h1>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course['className'])){; ?>
							<p class=""><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['className']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['teacherName']; ?>
							</p>
							<?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])){; ?>
							<p class=""><?php echo date('m',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?>月<?php echo date('d',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?>日
							<?php echo date('H:i',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->course['cstartTime'])); ?> 开课</p><?php }; ?>
							</a>
                      </div>
                    </div>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
					<div class="col-md-9 oltcon oltc-1 col-xs-20  col-sm-9 col-lg-11">
                      <div class="col-sm-8 col-xs-10 c-fl pic">
                          <img src="<?php echo utility_cdn::img('/assets_v2/img/member-order.jpg'); ?>" width="100%" alt=""/>
						  <span class="client-logo-name mt10"><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->org_name; ?></span>
					  </div>
					  <div class="col-sm-12 col-xs-10 c-fl oltctxt fsellip">
						<h1><?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['name']; ?></h1>
						<p class="">时长：<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['day']; ?>天</p>
                      </div>
                    </div>
					<?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
					<div class="col-lg-2 col-sm-3 col-md-2  col-xs-20 oltcon oltc-2 border-rt cRed">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->price)){; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->price_old==0){; ?>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["o"]->price_old==SlightPHP\Tpl::$_tpl_vars["o"]->price)){; ?>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }else{; ?>
								<p class="cGray" style="text-decoration:line-through">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price_old; ?></p>
								<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price; ?></p>
							<?php }; ?>
						<?php }elseif((SlightPHP\Tpl::$_tpl_vars["o"]->price==0)){; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->price_old)){; ?>
								<p class="cGray" style="text-decoration:line-through">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->price_old; ?></p>
							<?php }; ?>
              			<p class="cRed">￥0</p>
						<?php }else{; ?>
							未设置
						<?php }; ?>
					</div>
					<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
					<div class="col-lg-2 col-sm-3 col-md-2  col-xs-20 oltcon oltc-2 border-rt cRed">
						<p class="cRed">￥<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->member['price']; ?></p>
					</div>
					<?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'initial'){; ?>
                    <div class="col-md-3 col-lg-2 col-xs-20 col-sm-3  oltcon oltc-3 hd-lh30">
						<?php echo SlightPHP\Tpl::$_tpl_vars["status_array"][SlightPHP\Tpl::$_tpl_vars["o"]->status]; ?><script>ts('<?php echo date('i:s',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->expiration_time)-time()); ?>','<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->unique_order_id; ?>','<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>');</script>
						<br /><em class="cYellow"></em>
					</div>
					<?php }else{; ?>
                    <div class="col-sm-3 col-lg-2 col-md-3 hidden-xs oltcon oltc-2 hd-lh30">
						<?php echo SlightPHP\Tpl::$_tpl_vars["status_array"][SlightPHP\Tpl::$_tpl_vars["o"]->status]; ?>
					</div>
					<?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'initial'){; ?>
                    	<div class="col-xs-20 oltcon oltc-4 col-sm-5 col-lg-5 col-xs-offset-4 col-lg-offset-0 col-sm-offset-0">
                      		<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
							<input class="btn_yellow_2_1 col-sm-11 col-xs-12 pd0" onclick="location.href='<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->domainUrl[0]; ?>/order.main.pay/course/<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->uniqueOrderId; ?>'" value="立即支付" type="submit">
                      		<?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
							<input class="btn_yellow_2_1 col-sm-11 col-xs-12 pd0" onclick="location.href='<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->domainUrl[0]; ?>/order.main.pay/member/<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->uniqueOrderId; ?>'" value="立即支付" type="submit">
							<?php }; ?>
							<p class="mt10 lh14 hidden-xs col-sm-12 pd0"><a href="javascript:;" class="cancel" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>">取消订单</a></p>
                    	</div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'success'){; ?>
						  <div class="col-md-6 col-xs-20 oltcon oltc-2 hd-lh30 col-sm-5 col-lg-5">
						 	 支付成功
		                  </div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'expired'){; ?>
                    	<div class="col-sm-5 col-lg-5 col-md-6 col-xs-20 oltcon oltc-4">
                      		<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["o"]->course)){; ?>
							<input class="btn_reblack_buy col-sm-11 col-xs-8" onclick="location.href='<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->course['payUrl']; ?>'" value="重新购买" type="submit">
					        <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["o"]->member)){; ?>
							<input class="btn_reblack_buy col-sm-11 col-xs-8" onclick="location.href=''" value="重新购买" type="submit">
							<?php }; ?>
							<p class="mt10 lh14 col-xs-8 col-sm-11 pd0"><a href="javascript:;"  class="delete" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>">删除订单</a></p>
						</div>
					  <?php }; ?>
					  <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->status == 'cancel'){; ?>
                    	<div class="col-md-6 col-lg-5 col-xs-20 col-sm-5 oltcon oltc-2 hd-lh30">
					 		<a href="javascript:;"  class="delete" order_id="<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->fk_order; ?>">删除订单</a>
						</div>
					  <?php }; ?>
                    <div class="oltit">
                      <span class="hidden-xs col-sm-4 fs12 col-lg-5 col-md-4"><?php echo date('Y-m-d',strtotime(SlightPHP\Tpl::$_tpl_vars["o"]->create_time)); ?></span>
                      <span class="col-xs-20 col-sm-8 fs12">订单ID：<?php echo SlightPHP\Tpl::$_tpl_vars["o"]->orderSn; ?></span>
						<?php if(SlightPHP\Tpl::$_tpl_vars["o"]->pay_type){; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["o"]->price){; ?>
                            <span class="hidden-xs col-sm-5 col-lg-5">支付方式：<?php echo SlightPHP\Tpl::$_tpl_vars["pay_type"][SlightPHP\Tpl::$_tpl_vars["o"]->pay_type]; ?></span>
                            <?php }; ?>
                        <?php }; ?>
                    </div>
                  </div>
				  <?php }; ?>
				  <?php }else{; ?>
					  <div class="row">
					  	<div class="col-md-20  course-schedule mt15">
					       <div class="col-md-20 mt15 fs14 ta-c">
					          <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"/>
					          <?php if(SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'all'){; ?>
					          <p>你还没有订单哦!</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'suc')){; ?>
					          <p>你没有完成的订单哦！</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'exp')){; ?>
					          <p>你没有超时的订单哦！</p>
					          <?php }elseif((SlightPHP\Tpl::$_tpl_vars["get_sta"] == 'cal')){; ?>
					          <p>你没有取消的订单哦！</p>
					          <?php }; ?>
					       </div>
					   </div>
		            </div>
				  <?php }; ?>
                    <div class="col-sm-20">
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
                    })
					page("order_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
                });
                </script>
          </div>
        </div>
      </div>
    </div>
</section>
   
   <?php echo tpl_function_part("/index.main.footer"); ?>
   <script>
$(document).ready(function() {

	$(".cancel").click(function(){
		if(confirm("您确定要取消这个订单吗")){
			var orderid = $(this).attr("order_id");
			var order_status = 'cancel';
			$.post("/index.student.updateStatusAjax",{ order_id:orderid,status:order_status },function(r){
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
                var order_list=$(this).closest(".orderList");
                order_list.remove();
				$.post("/index.student.updateStatusAjax",{ order_id:orderid,status:order_status },function(r){
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
	})
   </script>
</body>
</html>
