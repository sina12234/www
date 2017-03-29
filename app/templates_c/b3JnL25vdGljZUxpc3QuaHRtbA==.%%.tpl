<!DOCTYPE html>
<html>
<head>
<title>学习资讯- <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 公告列表 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body >
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
<div class="row">
<?php echo tpl_function_part("/org.main.menu.activeNotice"); ?>
    <!--right-->
    <div class="col-md-16 right-main">
        <div class="content">
        	<h1 class="fs16  nl-title-notice cDarkgray"><?php echo tpl_modifier_tr('学习资讯','org'); ?></h1>
            <div class="nl-notice-hea">
        		<div class="t-list-order fs14">
                    <div class="divselect nl-article-select ">
                        <cite> <span class="cite-icon"></span> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cate_name"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["cate_name"]; ?><?php }else{; ?><?php echo tpl_modifier_tr('全部文章','org'); ?><?php }; ?></cite>
                        <dl id="AllarticleNotice">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cateList"]->items)){; ?>
						<dd><a href="javascript:;" selectid="0">全部文章</a></dd>
						<dd><a href="javascript:;" selectid="-1">未分类</a></dd>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["cateList"]->items as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                            <dd><a href="javascript:;" selectid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_cate; ?>"><?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["v"]->name); ?></a></dd>
						<?php }; ?>
						 </dl>
						<?php }; ?>
                        <input name="" type="hidden" value="" />
                    </div>
                </div>
                <div class="  nl-new-announcement"> <!-- class add-btn-->
                    	<a href="/org/main/activeNoticeAdd" class="col-md-20" target="_blank"> <span></span> <?php echo tpl_modifier_tr('新建文章','org'); ?></a>
                    </div>
            	</div>
            	<div class="n-l-notice-content-list " id="notice-content">
            		<ul>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data)){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["noticeList"]->data as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
            			<li class="col-md-20 nl-notice-content-li">
            				<p>
								<a href="/activity/main/info/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>" target="_blank"><?php if(SlightPHP\Tpl::$_tpl_vars["v"]->fk_cate !='0'){; ?>【<?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["v"]->name); ?>】<?php }; ?><?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["v"]->notice_title); ?>
								</a>
							</p>
            				<span><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->update_time)&&date('Y',strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->update_time)) !='2016'){; ?><?php echo date('Y-m-d H:i',strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->update_time)); ?><?php }else{; ?><?php echo date('m-d H:i',strtotime(SlightPHP\Tpl::$_tpl_vars["v"]->update_time)); ?><?php }; ?></span>
            				<span id="notice" class="notice">
	            				<a href="javascript:;" class="notice-h <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->sort)){; ?>no-top<?php }else{; ?>is-top<?php }; ?>" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->sort)){; ?><?php echo tpl_modifier_tr('取消置顶','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('置顶','org'); ?><?php }; ?></a>
	            				<a href="/org.main.activeNoticeEdit/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>" target="_blank" class="edit-notice" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>"><?php echo tpl_modifier_tr('修改','org'); ?></a>
	            				<a href="javascript:;" class="remove-notice" data-nid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_notice_id; ?>"><?php echo tpl_modifier_tr('删除','org'); ?></a>
            				</span>
            			</li>
					<?php }; ?>
					 <?php }else{; ?>
					<div class="list-tu">
                    <div class="list-img">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                        <div class="list-book">
						<?php if(empty(SlightPHP\Tpl::$_tpl_vars["cate_name"])){; ?>
						<span>您还没有创建文章哦，快去添加吧~</span>
						<?php }else{; ?>
						<span>您搜索的分类还没有文章哦，快去添加吧~</span>
						<?php }; ?>
                        </div>
                    </div>
                   </div>
            		</ul>
            	</div>
                <div class="page-list" id="pagepage"></div>
				 <script>
                            page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->page; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["noticeList"]->total; ?>);
                 </script>
				
				 <?php }; ?>
               
                
            </div>

    </div>
</div>
</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<style>

</style>
<script>
/*$(".notice-h").on('click',function(){
    var notice_Top=$(this).html();
    if(notice_Top=='置顶'){
        $(this).html('取消置顶')
    }else{
        $(this).html('置顶')
    }
})
*/
$(".close-delete").on('click',function(){
    $(this).parent().parent().hide();
})
</script>

<script>
    $(function() {
        
		//修改
		/*$(".notice-content-list").on('click','.edit-notice',function(){
			var nid=$(this).attr('data-nid');
			location.href="/org.main.activeNoticeEdit/"+nid;
		});*/
        //删除公告
        $(".n-l-notice-content-list").on('click','.remove-notice',function(){
        var nid = $(this).attr("data-nid");
        layer.confirm("<?php echo tpl_modifier_tr('您确定要删除这条公告吗','org'); ?>？", {
                btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>", "<?php echo tpl_modifier_tr('取消','org'); ?>"], //可以无限个按钮
				title:"<?php echo tpl_modifier_tr('公告信息','org'); ?>",
            }, function(index, layero){
                $.post("/org.main.delNoticeAjax","nid="+nid,function(r){
                    if(r){
                        location.reload();
                    }
                },"json");
            }, function(index){
                layer.close(index);
        });
        });
		
		$(".n-l-notice-content-list").on('click','.is-top',function(){
			var nid = $(this).attr("data-nid");
			layer.confirm("<?php echo tpl_modifier_tr('您确定要将这条公告置顶吗','org'); ?>?", {
					btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>", "<?php echo tpl_modifier_tr('取消','org'); ?>"], //可以无限个按钮
					title:"<?php echo tpl_modifier_tr('公告信息','org'); ?>",
				}, function(index, layero){
					$.post("/org.main.topNoticeAjax","nid="+nid,function(r){
						if(r){
							location.reload();
						}
					},"json");
				}, function(index){
					layer.close(index);
			});
		});
		$(".n-l-notice-content-list").on('click','.no-top',function(){
			var nid = $(this).attr("data-nid");
			layer.confirm("<?php echo tpl_modifier_tr('您确定要将这条公告取消置顶吗','org'); ?>?", {
					btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>", "<?php echo tpl_modifier_tr('取消','org'); ?>"],//可以无限个按钮
					title:"<?php echo tpl_modifier_tr('公告信息','org'); ?>",
				}, function(index, layero){
					$.post("/org.main.downNoticeAjax","nid="+nid,function(r){
						if(r){
							location.reload();
						}
					},"json");
				}, function(index){
					layer.close(index);
			});
		});
		
		$("#AllarticleNotice").on("click","a",function(){
				var cate_id = $(this).attr("selectid");
				var cate_name = $(this).parent("dd").find("a").html();
				location.href="/org.main.activeNoticeList?c="+cate_id+"&cate_name="+cate_name;
		});
    });
</script>
</html>
