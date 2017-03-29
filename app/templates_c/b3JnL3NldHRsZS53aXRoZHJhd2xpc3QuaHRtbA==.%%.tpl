<!DOCTYPE html>
<html>
<head>
<title>提现记录 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30 settle">
    <div class="container">
        <div class="row">
            <!--左侧-->
            <?php echo tpl_function_part("/org.main.menu.settle"); ?>
            <!--右侧-->
            <div class="right-main col-md-16">
                <div class="content">
                    <div class="tab-main">
                        <div class="tab-hd fs14">
                            <a class="tab-hd-opt" href="/org.settle">账户概览</a>
                            <a class="tab-hd-opt" href="/org/settle/accountlist/">结算账单</a>
                            <a class="tab-hd-opt curr" href="/org.settle.withdraw">提现记录</a>
                        </div>
                    </div>
                    <dl class="settle-list col-md-20 mt10">
						<form id="ftime" action="/org.settle.withdraw" method="get">
                        <dd class="sl-title fs14">
                        <i href="#" class="doubt-icon c-fl"></i><span class="c-fl cGray"><?php echo tpl_modifier_tr('自到账日起7天后未点击','org'); ?>"<?php echo tpl_modifier_tr('确认到账','org'); ?>",<?php echo tpl_modifier_tr('系统自动为已确认','org'); ?></span>
						<div class="time-picker-fr col-md-3">
							<input class="col-md-17" name="start" type="text" placeholder="<?php echo tpl_modifier_tr('选择日期','org'); ?>"  id="time-picker" value="<?php echo SlightPHP\Tpl::$_tpl_vars["createTime"]; ?>">
							<i class="settle-arrow-icon"></i>
							<i id="settle-cha" class="settle-close-icon"></i>
						</div>
                        <a href="<?php echo SlightPHP\Tpl::$_tpl_vars["exportUrl"]; ?>" class="sBlue c-fr mr10"><?php echo tpl_modifier_tr('导出','org'); ?> excel</a>
                        </dd>
                        <dd class="sl-th fs14 mt5">
                            <div class="col-md-4"><?php echo tpl_modifier_tr('银行卡','org'); ?></div>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('提现金额','org'); ?></div>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('交易时间','org'); ?></div>
                            <div class="col-md-4"><?php echo tpl_modifier_tr('备注','org'); ?></div>
                            <div class="col-md-3">
                                <div class="select-status" id="status" style="z-index: 1;">
                                    <cite>
									<?php if(is_numeric(SlightPHP\Tpl::$_tpl_vars["status"])){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["status"] == -1){; ?>
										<?php echo tpl_modifier_tr('申请失败','org'); ?>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["status"] == 0)){; ?>
										<?php echo tpl_modifier_tr('审核中','org'); ?>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["status"] == 1)){; ?>
										<?php echo tpl_modifier_tr('转入中','org'); ?>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["status"] == 2)){; ?>
										<?php echo tpl_modifier_tr('已到账','org'); ?>
										<?php }; ?>
									<?php }else{; ?>
									<?php echo tpl_modifier_tr('状态','org'); ?>
									<?php }; ?>
									</cite>
                                    <dl style="display: none; z-index: 1;">
										<dd><a href="#" selectid=""><?php echo tpl_modifier_tr('状态','org'); ?></a></dd>
                                        <dd><a href="#" selectid="-1"><?php echo tpl_modifier_tr('申请失败','org'); ?></a></dd>
                                        <dd><a href="#" selectid="0"><?php echo tpl_modifier_tr('审核中','org'); ?></a></dd>
                                        <dd><a href="#" selectid="1"><?php echo tpl_modifier_tr('转入中','org'); ?></a></dd>
										<dd><a href="#" selectid="2"><?php echo tpl_modifier_tr('已到账','org'); ?></a></dd>
                                    </dl>
                                    <input type="hidden" name="status" value=<?php echo SlightPHP\Tpl::$_tpl_vars["status"]; ?>>
                                </div>
                            </div>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('操作','org'); ?></div>
                        </dd>
						</form>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["withdrawList"]->items)){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["withdrawList"]->items as SlightPHP\Tpl::$_tpl_vars["wo"]){; ?>
                        <dt>
                            <div class="col-md-4"><?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->bank; ?>  <?php echo tpl_modifier_tr('尾号','org'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->card_no; ?></div>
                            <div class="col-md-3">￥<?php echo number_format(sprintf("%0.2f", floatval(SlightPHP\Tpl::$_tpl_vars["wo"]->withdraw/100)), 2); ?></div>
                            <div class="col-md-3"><?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->create_time; ?></div>
                            <div class="col-md-4 memo">
							<input name="wid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->pk_withdraw; ?>" type="hidden">
							<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->descript; ?></span>
							<i class="memoicon"></i>
							</div>
							<?php if(SlightPHP\Tpl::$_tpl_vars["wo"]->status == -1){; ?>
							<div class="col-md-3"><?php echo tpl_modifier_tr('申请失败','org'); ?></div>
                            <div class="col-md-3"><a href="/org.settle.applywithdraw" class="sBlue"><?php echo tpl_modifier_tr('重新申请','org'); ?></a></div>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["wo"]->status == 0)){; ?>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('审核中','org'); ?></div>
                            <div class="col-md-3"><?php echo tpl_modifier_tr('待审核','org'); ?></div>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["wo"]->status == 1)){; ?>
							<div class="col-md-3"><?php echo tpl_modifier_tr('转入中','org'); ?></div>
                            <div class="col-md-3"><a href="#" class="st-sure-btn srue-account" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->pk_withdraw; ?>"><?php echo tpl_modifier_tr('确认到账','org'); ?></a></div>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["wo"]->status > 1)){; ?>
							<div class="col-md-3"><?php echo tpl_modifier_tr('已到账','org'); ?></div>
								<?php if(SlightPHP\Tpl::$_tpl_vars["wo"]->status == 3){; ?>
								<div class="col-md-3"><a href="#" class="st-yet-btn"><?php echo tpl_modifier_tr('已确认','org'); ?></a></div>
								<?php }elseif((SlightPHP\Tpl::$_tpl_vars["wo"]->status == 2)){; ?>
								<div class="col-md-3"><a href="#" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["wo"]->pk_withdraw; ?>" class="st-sure-btn srue-account"><?php echo tpl_modifier_tr('确认到账','org'); ?></a></div>
								<?php }; ?>
							<?php }; ?>

                        </dt>
						<?php }; ?>
						<?php }else{; ?>
						<div class="list-img tac mt40">
                            <img src="/assets_v2/img/pet3.png">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["createTime"])){; ?>
							<p class="fs16 cGray"><?php echo tpl_modifier_tr('没有找到符合日期的提现记录','org'); ?>，<?php echo tpl_modifier_tr('请调整一下再试','org'); ?>~</p>
							<p class="fs12 cGray"><?php echo tpl_modifier_tr('您可以戳','org'); ?><a href="/org.settle.withdraw" class="sBlue"> <?php echo tpl_modifier_tr('这里','org'); ?> </a><?php echo tpl_modifier_tr('返回默认条件','org'); ?></p>
							<?php }else{; ?>
                            <p class="fs12 cGray"><?php echo tpl_modifier_tr('您还没有提现记录哦','org'); ?>~</p>
							<?php }; ?>
                        </div>
						<?php }; ?>
                    </dl>
					<div class="col-sm-20 col-md-20 col-xs-20">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["withdrawList"]->items)){; ?>
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
</body>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script>
$(function() {
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["createTime"])){; ?>
		$('.settle-close-icon').show();
	<?php }else{; ?>
		$('.settle-close-icon').hide();
	<?php }; ?>

	$('.settle-close-icon').click(function(){
		$('#time-picker').val('');
		$('#ftime').submit();
	});
	$.divselect("#status cite");
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["withdrawList"]->items)){; ?>
	page("settle_page","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["withdrawList"]->page; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["withdrawList"]->totalPage; ?>);
	<?php }; ?>

	$("#time-picker").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y/m/d',
		formatDate:'Y/m/d',
	});
	$('#time-picker').change(function(){
		$('#ftime').submit();
	});
	$('#status dd a').click(function(){
		$('input[name=status]').val($(this).attr('selectid'));
		$('#ftime').submit();
	})
	$('.srue-account').click(function(){
		var wid = $(this).attr('data-id');
		$.ajax({
			type:"post",
			url: '/org.settle.updatewithdrawajax',
			data:{ wid:wid,status:3 },
			dataType:'json',
			success:function(ret){
				if(ret.code == 0 ){
					window.location.reload();
				}else{
					layer.msg(ret.msg);
				}
			}
		})
	});

/*备注*/
var mInput=$('<div class="memo-input desc-div" data-id=""><input><a href="javascript:void(0)" class="sBlue memo-e">确定</a><a href="javascript:void(0)" class="cGray memo-c">取消</a></div>');
var mText='',iText='',wid='';
$('.memo').on('click','i',function(){
    mText=$(this).parent('div').find('span').html();
    $('.memo').find('span').show();
    $(this).parent('div').find('span').hide();
    mInput.find('input').val(mText);
	mInput.find('.desc-div').attr('data-id',wid);
    $(this).after(mInput);
});

$('.memo').on('click','.memo-e',function(){
	wid = $(this).parents('.memo').find('input[name=wid]').val();
    iText=$(this).parents('.memo-input').find('input').val();
    $(this).parents('.memo').find('span').html(iText).show();
    $(this).parents('.memo-input').remove();
	$.ajax({
		type:"post",
		url: '/org.settle.updatewithdrawajax',
		data:{ wid:wid,descript:iText },
		dataType:'json',
		success:function(ret){
			if(ret.code != 0 ){
				layer.msg(ret.msg);
			}
		}
	})
})

    $('.memo').on('click','.memo-c',function(){
        $(this).parents('.memo').find('span').html(mText).show();
        $(this).parents('.memo-input').remove();
    })
});
</script>
</html>
