<!DOCTYPE html>
<html>
<head>
<title>运营工具 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 运营工具 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<style>
.page-list li{ display:block; }
.share-sns{ background: #fff; width: 90%;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section id="operat-tols" class="pd30">
    <div class="container">
		<div class="row">
		<?php echo tpl_function_part("/org.main.menu.vote"); ?>
		<div class="right-main col-md-16">
			<div class="content">
                <div class="tab-main">
                    <div class="tab-hd fs14">
                        <a class="tab-hd-opt<?php if(SlightPHP\Tpl::$_tpl_vars["sType"]=='all'){; ?> curr<?php }; ?>" href="/org.vote.list"><?php echo tpl_modifier_tr('全部','org'); ?></a>
                        <a class="tab-hd-opt<?php if(SlightPHP\Tpl::$_tpl_vars["sType"]=='ing'){; ?> curr<?php }; ?>" href="/org.vote.list?sType=ing"><?php echo tpl_modifier_tr('进行中','org'); ?></a>
                        <a class="tab-hd-opt<?php if(SlightPHP\Tpl::$_tpl_vars["sType"]=='end'){; ?> curr<?php }; ?>" href="/org.vote.list?sType=end"><?php echo tpl_modifier_tr('已结束','org'); ?></a>
                    </div>
                    <a href="/org.vote.add" class="c-fr add-teacher-btn fs14"><i class="add-btn-icon c-fl"></i><?php echo tpl_modifier_tr('发起新投票','org'); ?></a>
	                <form action="/org.vote.list" method="get">
		        		<div class="c-fr col-md-5 wrap-gnger-search">
	            			<input type="text" placeholder="<?php echo tpl_modifier_tr('搜索投票名称','org'); ?>" name="serh" value="<?php echo SlightPHP\Tpl::$_tpl_vars["keywords"]; ?>" class="col-md-17 fs14 genger-seach-ipt bor1px c-fl" /><button class="c-fr col-md-3 genger-sub-form" id="sub-form"></button>
							<i class="gnger-delt-btn"></i>
		            	</div>
	        		</form>
                </div>
                <ul class="genger-content clearfix mt10">
                	<li style="display:block;">
                		<div class="wrap-gnger-tab">
                			<div class="wrap-gnger-tab-title bor1px fs14 clearfix">
                				<div class="col-md-3"><?php echo tpl_modifier_tr('标题','org'); ?></div>
                				<div class="col-md-4"><?php echo tpl_modifier_tr('开始时间','org'); ?></div>
                				<div class="col-md-4"><?php echo tpl_modifier_tr('结束时间','org'); ?></div>
                				<div class="col-md-3"><?php echo tpl_modifier_tr('状态','org'); ?></div>
                				<div class="col-md-2" style="padding:0"><?php echo tpl_modifier_tr('参与人数','org'); ?></div>
                				<div class="col-md-4"><?php echo tpl_modifier_tr('操作','org'); ?></div>
                			</div>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                			<div class="wrap-gnger-tab-bd bor1px fs14 clearfix">
                				<div class="col-md-3 h40">
                					<a class="share_tit" href="/org/poll/info/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>" allurl="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->url; ?>" title=""><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></a>
                				</div>
                				<div class="col-md-4 h40"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->startTime; ?></div>
                				<div class="col-md-4 h40"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->endTime; ?></div>
                				<div class="col-md-3 h40">
									<?php if((SlightPHP\Tpl::$_tpl_vars["v"]->newTime >= SlightPHP\Tpl::$_tpl_vars["v"]->start_time) && (SlightPHP\Tpl::$_tpl_vars["v"]->newTime < SlightPHP\Tpl::$_tpl_vars["v"]->end_time)){; ?>
										<span class="opera-come-on fs12 mt10"><?php echo tpl_modifier_tr('进行中','org'); ?></span>
									<?php }elseif( (SlightPHP\Tpl::$_tpl_vars["v"]->newTime > SlightPHP\Tpl::$_tpl_vars["v"]->end_time)){; ?>
										<span class="opera-end fs12 mt10"><?php echo tpl_modifier_tr('已结束','org'); ?></span>
									<?php }else{; ?>
										<span class="opera-no-start fs12 mt10"><?php echo tpl_modifier_tr('未开始','org'); ?></span>
									<?php }; ?>
                				</div>
                				<div class="col-md-2 h40" style="padding:0">
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_count > 0){; ?>
                					<a href="/org.vote.tenderer/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>" title=""><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_count; ?><?php echo tpl_modifier_tr('人','org'); ?></a>
									<?php }else{; ?>
										<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->user_count; ?><?php echo tpl_modifier_tr('人','org'); ?>
									<?php }; ?>
                				</div>
                				<div class="col-md-4 h40">
									<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status == 2){; ?>
									<button class="delt-btn c-fl"><?php echo tpl_modifier_tr('已推送','org'); ?></button>
									<?php }else{; ?>
                                    <button onclick="addMsg(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>,'<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?>')" class="delt-btn c-fl"><?php echo tpl_modifier_tr('消息推送','org'); ?></button>
                					<?php }; ?>
									<button onclick="voteDel(<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>);" class="delt-btn c-fl"><?php echo tpl_modifier_tr('删除','org'); ?></button>
                					<div class="opera-tols-share c-fl">
                					<button class="share-btn"><?php echo tpl_modifier_tr('分享','org'); ?></button>
                					<!-- share-ct -->
                						<div class="share-contents bor1px bgf">
                							<i class="jianjiao"></i>
											<div class="share-sns fl">
                                                <a href="javascript:;" vid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>" class="share-qq" data-cmd="tqq" title="<?php echo tpl_modifier_tr('分享到QQ','org'); ?>"></a>
                                                <a href="javascript:;" vid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>" class="share-weixin" data-cmd="weixin" title="<?php echo tpl_modifier_tr('分享到微信','org'); ?>"></a>
                                                <a href="javascript:;" vid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_vote; ?>" class="share-tsina" data-cmd="tsina" title="<?php echo tpl_modifier_tr('分享到新浪微博','org'); ?>"></a>
											</div>
                						</div>
                					<!-- /share-ct -->
                					</div>
                				</div>
                			</div>
                			<?php }; ?>
                			<?php }else{; ?>
                			<div class="wrap-gnger-tab-bd wrap-gnger-no-bd bor1px fs14 clearfix">
                				<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>"  alt="">
                				<p><?php echo tpl_modifier_tr('您还没有发起过投票哦！','org'); ?></p>
                				<p><?php echo tpl_modifier_tr('让学生互动起来吧！ 评选出最优秀的老师或课程！','org'); ?></p>
                			</div>
							<?php }; ?>
                		</div>
                		<!-- page -->
                		<div class="page-list" id="page_list"></div>
                		<!-- /page -->
                	</li>
                </ul>    
			</div>	
		</div>
		</div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
  <script src="<?php echo utility_cdn::js('/assets_v2/js/jquery.share.js'); ?>"></script>
</body>
<style type="text/css">
.layui-layer{ border-radius:0;}
.layui-layer-btn a:hover{ color:#fff;}
</style>
</html>
<script type="text/javascript">
//删除	
function voteDel(voteId){
	layer.confirm("<?php echo tpl_modifier_tr('确定删除吗?','org'); ?>", {
		btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], 
		title:["<?php echo tpl_modifier_tr('取消删除','org'); ?>",'color:#fff;background:#ffa81d']
	}, function(){
		$.post("/org/vote/VoteDelAjax", { voteId:voteId }, function(r){
			if(r.code == 1){
				layer.msg('删除失败',{ icon:1,time:20000 });
				location.reload();
			}else{
				layer.msg('删除成功',{ icon:1,time:20000 });
			}
		});
	});
}

$(function() {
//搜索
    /*    
	$(".wrap-gnger-search .gnger-delt-btn").click(function() {
		$(this).hide();
		$(".wrap-gnger-search input[name='serh']").val("");
		location.reload();
	})
    */
	
//share
	var voteImg = '<?php echo SlightPHP\Tpl::$_tpl_vars["voteImg"]; ?>';
    $(".opera-tols-share").hover(function() {
        $(this).find(".share-contents").show();
    },function() {
        $(this).find(".share-contents").hide();
    })
	$(".share-sns a").click(function(){
		var vid = $(this).attr('vid');
        var orgName = "<?php echo SlightPHP\Tpl::$_tpl_vars["orgInfo"]->name; ?>";
		var dCmd=$(this).attr('data-cmd');
		var title = $(this).parents(".wrap-gnger-tab-bd").find(".share_tit").html();
		var url   = $(this).parents(".wrap-gnger-tab-bd").find(".share_tit").attr("allurl");

		$(".share-sns").share({
			sTitle:''+title+'['+orgName+']',
			sPic:voteImg,
			shareUrl:url,
            sDes:'小伙伴，我正在参与投票。快来参加吧，投上你宝贵的一票！'
		});
		
		$.post("/org/vote/StatShare",{ voteId:vid },function(r){
			if(dCmd!='weixin'){
				window.location.reload();
			}
		});
	});
	
	var keyword = $("input[name='serh']").val();
	if(keyword != ''){
		$(".gnger-delt-btn").show();
	}
	$(".gnger-delt-btn").click(function(){
		$("#serh").val('');
		$(".gnger-delt-btn").attr('display','none');
		window.location.href= '<?php echo SlightPHP\Tpl::$_tpl_vars["url"]; ?>/org.vote.list';
	});
})

function addMsg(voteId,title){
    if(voteId == '' || title == ''){
        return false;
    }
    $.post('/org/vote/addMsg',{ voteId:voteId,title:title },function(r){
        if(r.code == 1){
			layer.msg(r.msg);
			location.reload();
		}else{
			layer.msg(r.msg);
		}        
    },'json');
}

<?php if(SlightPHP\Tpl::$_tpl_vars["totalPage"] > 0){; ?>
	page("page_list","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
<?php }; ?>
</script>