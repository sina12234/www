<!DOCTYPE html>
<html>
<head>
<title>会员管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<style type="text/css">
.layui-layer-btn a.layui-layer-btn0,.layui-layer-btn a.layui-layer-btn0:hover{ color:#fff; background: #ffa81e; }
.layui-layer-btn .layui-layer-btn1,.layui-layer-btn .layui-layer-btn1:hover{ background: #f5f5f5;color:#999; }
.layui-layer-setwin a{ background-position: -227px -51px; }
</style>
<body >
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30" id="operat-tols">
	<div class="container" id="member-tols">
		<div class="row">
		<!-- lt -->
       <?php echo tpl_function_part("/org.main.menu.member"); ?>
		<!-- /lt -->
		<!-- rt -->
			<div class="right-main col-md-16 col-sm-12">
				<div class="content">
					<div class="com-md-20 pd0 clearfix mb20">
						<div class="cDarkgray fs16 c-fl" style="font-weight:bolder;line-height:30px;">会员管理</div>
						<?php if(SlightPHP\Tpl::$_tpl_vars["memberCount"] >= 5){; ?>
						<a href="#" class="c-fr add-teacher-btn fs14" id="add_memberset">
                            <i class="add-btn-icon c-fl"></i>
                            新建会员类型
                        </a>
						<?php }else{; ?>
						<a href="/org.member.addmemberset" class="c-fr add-teacher-btn fs14">
                            <i class="add-btn-icon c-fl"></i>
                            新建会员类型
                        </a>
						<?php }; ?>
					</div>
					<div class="wrap-gnger-tab-title bor1px fs14 clearfix">
						<div class="col-md-3">会员类型</div>
						<div class="col-md-3">开通人数</div>
						<div class="col-md-3">有效人数</div>
						<div class="col-md-5">状态</div>
						<div class="col-md-6">操作</div>
					</div>
				<!-- list -->
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["msetList"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["msetList"] as SlightPHP\Tpl::$_tpl_vars["mo"]){; ?>
					<div class="wrap-gnger-tab-bd bor1px fs14 clearfix">
						<div class="col-md-3 h40 tec"><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->title; ?></div>
						<div class="col-md-3 h40"><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->open_user; ?>人</div>
						<div class="col-md-3 h40"><?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->valid_user; ?>人</div>
						<div class="col-md-5 h40">
							<?php if(SlightPHP\Tpl::$_tpl_vars["mo"]->status == 1){; ?>
								<span class="opera-on">启用</span>
								<span class="opera-end-btn" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>" >停用</span>
							<?php }elseif((SlightPHP\Tpl::$_tpl_vars["mo"]->status == 0)){; ?>
								<span class="opera-on-btn" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>">启用</span>	
								<span class="opera-end">停用</span>
							<?php }; ?>
							<span class="opera-dele-btn" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>" data-status="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->status; ?>">删除</span>
						</div>
						<div class="col-md-6 h40">
							<a href="/org.member.editmemberset/<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>" class="vip-edit-btn">编辑</a>
							<i></i>
							<span class="vip-list-btn" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>">会员列表</span>
							<i></i>
							<span data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>" data-status="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->status; ?>" class="vip-add-btn">添加会员</span>
						</div>
					</div>
					<?php }; ?>
				<?php }else{; ?>
	                <div class="col-sm-20 pd0 tec fs14 mt40 mb40">
	                    <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>" alt="">
	                    <p>还没有添加会员类型</p>
	                </div>
				<?php }; ?>
				<!-- /list -->
				</div>
			</div>
		</div>
	</div>	
</section>
<!-- 添加会员 -->
<section id="wrap-member-add" style="display:none;">
	<div class="col-xs-20 fs14 mt10">
		<div class="col-xs-4 pd0 tec lh22 mt5">手机号：</div>
		<input class="col-xs-16 pd0 bor1px" name="mobile" type="text"/>
	</div>
	<div class="col-xs-20 fs14 mt10 mb20">
		<div class="col-xs-4 pd0 tec lh22 mt5">有效期：</div>
		<div class="col-xs-16 lh22 pd0 mt5">
			<label class="c-fl mr5">
				<input type="radio"  name="price_type" value="30"/> 30天
			</label>
			<label class="c-fl mr5">
				<input type="radio" name="price_type" value="90"/> 90天
			</label>
			<label class="c-fl mr5">
				<input type="radio" name="price_type" value="180"/> 180天
			</label>
			<label class="c-fl mr5">
				<input type="radio" name="price_type" value="360"/> 360天
			</label>
		</div>
	</div>
	<input name="setId" type="hidden">
	<input name="status" type="hidden">
	<button class="but14 member-add-requestBtn col-xs-offset-5 c-fl mr10">添加</button>
	<button class="but14 member-add-cancleBtn c-fl">取消</button>
</section>
<!-- /添加会员 -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
$(function() {
	var validCount = <?php echo SlightPHP\Tpl::$_tpl_vars["validCount"]; ?>;
	$('#member-tols .opera-on-btn').click(function() {
		var $vipOperaOnBtn = $(this);
		var setId = $(this).attr('data-id');
		if(validCount >= 2){
			layer.confirm('最多同时启用2个会员类型', {
				btn: ['确定'],
				title: ['启用' ,'background:#ffa918;color:#fff;']
			}, function(){	
				layer.msg('确定', { icon: 1 });
			}, function(){

			});
		}else{
			$.ajax({
				type:"post",
				url: '/org.member.updatemembersetstatus',
				data:{ setId:setId,status:1 },
				dataType:'json',
				success:function(ret){
					if(ret.code == 0 ){
						window.location.reload();
					}else{
						layer.msg(ret.msg);
					}
				}
			})
		}	
	})
	$('#member-tols .opera-end-btn').click(function() {
		var setId = $(this).attr('data-id');
		layer.confirm('<p>停用后，该类型下的所有会员将无法享受</p><p>此会员权限是否停用？</p>', {
			btn: ['确定','取消'],
			title: ['停用' ,'background:#ffa918;color:#fff;']
		}, function(){
			$.ajax({
				type:"post",
				url: '/org.member.updatemembersetstatus',
				data:{ setId:setId,status:0 },
				dataType:'json',
				success:function(ret){
					if(ret.code == 0 ){
						window.location.reload();
					}else{
						layer.msg(ret.msg);
					}
				}
			})
		}, function(){

		});
	})

	$('#member-tols .vip-list-btn').click(function() {
		var $vipOperaOnBtn = $(this);
		var vipOperaDelet = $(this).parents(".wrap-gnger-tab-bd");
		var setId = $(this).attr('data-id');
		layer.open({
		  type: 2,
		  title: ['会员列表' ,'background:#ffa918;color:#fff;'],
		  area: ['780px', '560px'],
		  content: '/org.member.memberlist/'+setId
		});
	})

	$('#member-tols .opera-dele-btn').click(function() {
		var setId = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		var $vipOperaOnBtn = $(this);
		var vipOperaDelet = $(this).parents(".wrap-gnger-tab-bd");
		var delhtml = '';
		if(status == 1){
			layer.confirm('<p>该会员类型为启用，只有停用的会员类型才能删除</p>', {
				btn: ['确定'],
				title: ['删除' ,'background:#ffa918;color:#fff;']
			}, function(){
				layer.msg('确定', { icon: 1 });
			}, function(){
			});
		}else{
			layer.confirm('<p>删除后与该会员类型相关的所有数据都将删除</p><p>是否删除？</p>', {
				btn: ['确定','取消'],
				title: ['删除' ,'background:#ffa918;color:#fff;']
			}, function(){
				$.ajax({
					type:"post",
					url: '/org.member.updatemembersetstatus',
					data:{ setId:setId,status:-1 },
					dataType:'json',
					success:function(ret){
						if(ret.code == 0 ){
							window.location.reload();
						}else{
							layer.msg(ret.msg);
						}
					}
				})
			}, function(){

			});
		}
	})

	$('#member-tols .vip-add-btn').click(function() {
		var $vipOperaOnBtn = $(this);
		var vipOperaDelet = $(this).parents(".wrap-gnger-tab-bd");
		var setId = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		$('input[name=setId]').val(setId);
		$('input[name=status]').val(status);
		layer.open({
			type: 1,
			title: ['添加会员' ,'background:#ffa918;color:#fff;'],
			closeBtn:true,
			area: ['320px', '200px'],
			content: $('#wrap-member-add')
		});
	})

	$('#wrap-member-add .member-add-requestBtn').click(function() {
		layer.closeAll();
		var mobile = $(this).parents('#wrap-member-add').find('input[name=mobile]').val();
		var setId = $(this).parents('#wrap-member-add').find('input[name=setId]').val();
		var price_type = $(this).parents('#wrap-member-add').find('input[name=price_type]:checked').val();
		var status = $(this).parents('#wrap-member-add').find('input[name=status]').val();
		if(mobile == ''){
			layer.msg('请填写手机号！');
			return;
		}
		if(price_type == ''){
			layer.msg('请选择有效期');
			return;
		}
		$.ajax({
			type:"post",
			url: '/org.member.addmember',
			data:{ setId:setId,price_type:price_type,mobile:mobile,status:status },
			dataType:'json',
			success:function(ret){
				if(ret.code == 0 ){
					layer.msg(ret.msg);
					window.location.reload();
				}else{
					layer.msg(ret.msg);
				}
			}
		})
	})
	$('#add_memberset').click(function(){
		layer.confirm('最多管理5个会员类型', {
				btn: ['确定'],
				title: ['新建会员类型' ,'background:#ffa918;color:#fff;']
		}, function(){	
			layer.msg('确定', { icon: 1 });
		}, function(){

		});
	
	});
	$('#wrap-member-add .member-add-cancleBtn').click(function() {
		layer.closeAll();
		setTimeout(function() {
			layer.msg('取消', { icon: 1 });
		} ,300)
	})

})
</script>