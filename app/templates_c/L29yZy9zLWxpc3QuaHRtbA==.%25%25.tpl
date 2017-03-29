<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>学生管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 学生管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script>
<?php if(SlightPHP\Tpl::$_tpl_vars["list"]){; ?>
$(function() {
    page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
});
<?php }; ?>
</script>
<style>
#importStudent{ color:#198fee; }
#importStudent:hover{ color:#ffa81d;}
</style>
<body >
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
    <div class="container">
		<div class="row">
            <?php echo tpl_function_part("/org.main.menu.student"); ?>
            <div class="right-main col-md-16 col-sm-9">
                <div class="content">
                    <div class="t-list-order fs14 col-md-20 pd0">
                        <form action='/org/student/list' method="POST">
							<div style="float:right;">
								<div class="search-frame">
									<input name='keyword' class="search-input" id='kw' type="text" value="<?php echo SlightPHP\Tpl::$_tpl_vars["keyword"]; ?>" placeholder="<?php echo tpl_modifier_tr('搜索学生名称/手机号','org'); ?>">
                                    <button class="search-box org-t-search-btn" id="subsearch">
                                        <span class="search-icon" style="margin: 0;"></span>
                                        <div class='t-list-img clear-icon' id="t-delt-btn" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?>style="display:none;"<?php }; ?> >
                                        </div>
                                    </button>
								</div>
							</div>
							<div class="col-md-20" style="float:right;padding:0;">
                                <span class="c-fl fcg9"><?php echo tpl_modifier_tr('共','org'); ?> <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["searchCount"]; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["total"]; ?><?php }; ?><?php echo tpl_modifier_tr('人','org'); ?></span>
								<a id="importStudent" class="c-fr"><?php echo tpl_modifier_tr('导出Excel','org'); ?></a>
							</div>
                        </form>
                    </div>
                    <div class="t-list fs14 wrap-manage-box" style="padding:0;">
                        <table>
                            <tr>
                                <td class="hidden-lg col-sm-1 col-md-1"><?php echo tpl_modifier_tr('序号','org'); ?></td>
                                <td class="name col-sm-2 col-md-3" style="text-align:center;"><?php echo tpl_modifier_tr('学生姓名','org'); ?></td>
                                <td class="name col-sm-2 col-md-2" style="text-align:center;"><?php echo tpl_modifier_tr('昵称','org'); ?></td>
                                <td class="col-sm-1 col-md-2"><?php echo tpl_modifier_tr('性别','org'); ?></td>
                                <td class="col-sm-3 col-md-5"><?php echo tpl_modifier_tr('学校','org'); ?></td>
                                <td class="col-sm-3 col-md-4"><?php echo tpl_modifier_tr('联系方式','org'); ?></td>
                                <td class="hidden-sm col-sm-3 col-md-4"><?php echo tpl_modifier_tr('正在学习课程','org'); ?></td>
                            </tr>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["list"]){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <tr>
                            <td class="col-lg-1 col-sm-1 col-md-1 hidden-lg"><?php echo SlightPHP\Tpl::$_tpl_vars["k"]+1; ?></td>
                            <td class="name col-sm-2 col-md-3" style="padding:0 10px;">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["v"]['thumb']){; ?><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]['thumb']); ?>"><?php }; ?>
                                <a href="/org/student/detail/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['id']; ?>" class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['real_name']; ?></a>
                            </td>
                            <td class="col-sm-1 col-md-2"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['name']; ?></td>
                            <td class="col-sm-1 col-md-2"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]['gender'],'org'); ?></td>
                            <td class="col-sm-3 col-md-5"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['school']; ?></td>
                            <td class="col-sm-3 col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['mobile']; ?></td>
                            <td class="hidden-sm col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['courseNum']; ?></td>
                        </tr>
                        <?php }; ?>
						<?php }; ?>
                    </table>
					<?php if(empty(SlightPHP\Tpl::$_tpl_vars["searchCount"])){; ?>
					<div class="col-md-12 col-lg-12 mt15 fs14 tac" style="padding-top:60px;display:block;">
								<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" />
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?>
								<p><?php echo tpl_modifier_tr('您查询的学生不存在','org'); ?>~</p>
								<?php }else{; ?>
								<p><?php echo tpl_modifier_tr('您还没有学生','org'); ?>~</p>
								<?php }; ?>
					</div>
					
					<?php }; ?>
                </div>
                <div class="page-list" id="pagepage">
            </div>
            <div class="col-lg-12 tac mt10" style="display:none;padding-top:60px;">
                <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>">
                <p class="fs14" style="font-weight: bold; color:#666;"><?php echo tpl_modifier_tr('学生列表为空','org'); ?></p>
            </div>
        </div>
        <div class='clear'></div>
    </div>
	</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
<script>
$(function() {
    $.divselect(".divselect");
});
</script>	
<script>
$(function(){
    $(".t-list-img").click(function(){
    	$(this).hide();
    	$("#kw").val("");
        $('form').submit();
    });
    //调班弹框
    $('.s-lst-class-btn').on('click', function(){
        var uid=$(this).attr("dataId");

        layer.open({
            type: 2,
            title:'调班',
            area: ['500px', '400px'],
            shadeClose: true, //点击遮罩关闭
            content: '/layer/main/changeClass/'+uid
        });
		
    });
	$("#importStudent").click(function(){
		var keyword= $("#kw").val();
		window.location.href="/phpexcel/platformstu/importOrgOfStudent?keyword="+keyword;
        //$('form').submit();
    });
});
</script>
</body>
</html>
