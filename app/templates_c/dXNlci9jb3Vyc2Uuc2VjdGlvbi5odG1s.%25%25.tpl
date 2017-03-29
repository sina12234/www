<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>创建课程章程</title>
<meta name="title" content="高能100 - 专业的K12在线教育平台 在线直播">
<meta name="keywords" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线 k12 小学数学 初中数学">
<meta name="description" content="高能100 - 专业的K12在线教育平台 高能100 - 专业的K12在线教育平台 在线直播">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class='mt40 container'>
	<?php echo tpl_function_part("/org.main.menu.course"); ?>
	<div class='right-main col-sm-9 col-md-16'>
			<div class='content'>
				<div class='course-bt col-sm-12'>
					<p><a href="/user.org.course"><?php echo tpl_modifier_tr('课程管理','site.course'); ?>><b></a><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["courseTypeShow"],'site.course'); ?></b></p>
				</div>
				<div class="step">
					<ul class="col-sm-9 col-md-20">
						<li class="col-sm-4 col-md-7">
						<p class="bg1"></p>
						<p class="n">
						1
						</p>
						<p class="text"><?php echo tpl_modifier_tr('课程信息','site.course'); ?></p>
						</li>
						<li class="col-sm-4 col-md-6">
						<p class="bg2"></p>
						<p class="n2">
						2
						</p>
						<p class="text2"><?php echo tpl_modifier_tr('设置章节','site.course'); ?></p>
						</li>
						<li class="col-sm-4 col-md-7">
						<p class="bg1"></p>
						<p class="n">
						3
						</p>
						<p class="text"><?php echo tpl_modifier_tr('开班授课','site.course'); ?></p>
						</li>
					</ul>
				</div>
				<div class='ordr-box' id="section_list">
					<!--div class='drafting'>
						<p><img src="<?php echo utility_cdn::img('/assets_v2/img/drafting.png'); ?>">拖拽章节号可调整顺序</p>
					</div-->
					<div class='col-sm-10 ordr-3 col-md-15'>
						<ul>
							<li class="col-sm-2 col-xs-2 col-md-5"><?php echo tpl_modifier_tr('序号','site.course'); ?></li>
							<li class="col-sm-10 col-xs-2 col-md-15"><?php echo tpl_modifier_tr('章节名','site.course'); ?></li>
						</ul>
					</div>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list_section_ret"])){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["list_section_ret"] as SlightPHP\Tpl::$_tpl_vars["_section"]){; ?>
						<ul class="col-sm-12 ordr-3-1 col-md-15 pd0">
							<li class="col-sm-2 col-xs-2 col-md-5 chapter-name"><b secname><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["_section"]->name,'site.index'); ?></b></li>
							<li sid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->section_id; ?>" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->course_id; ?>" class="col-sm-8 col-xs-2 col-md-15 sec_desc"><div data-desc="<?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->descript; ?>" style='border-right:none;'><?php echo SlightPHP\Tpl::$_tpl_vars["_section"]->descript; ?></div><span class='absolte'><p class="edit"><?php echo tpl_modifier_tr('修改','site.course'); ?></p><p class="submit"><?php echo tpl_modifier_tr('确定','site.course'); ?></p><b class="del"><?php echo tpl_modifier_tr('删除','site.course'); ?></b><b class="cancel"><?php echo tpl_modifier_tr('取消','site.course'); ?></b></span></li>
						</ul>
					<?php }; ?>
					<?php }; ?>
					<div class='clear'></div>
					<div class='close-btn col-md-20'>
					    <div class="col-md-5"></div>
                        <button id="sec_add" class="col-md-5">
							+<?php echo tpl_modifier_tr('添加章节','site.course'); ?>
						</button>
					</div>
					<form id="form">
						<div class='input-text' id="add_text" style="display:none;">
							<textarea name="descript" id="textsection" placeholder="<?php echo tpl_modifier_tr('支持批量添加，每行一个章节名称，章节按顺序自动编号','site.course'); ?>"></textarea>
							<div class='tian'>
								<button><?php echo tpl_modifier_tr('确定','site.course'); ?></button>
								<span id="but-cancel">取消</span>
								<?php /* <p><?php echo tpl_modifier_tr('单个章名称最长支持15个汉字','site.course'); ?></p> */?>
							</div>
						</div>
					</form>
					<div class='close-upd col-sm-12 col-md-20'>
						<div class="col-md-2"></div>
                        <a href="/user.org.editcourse.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>"><button class='up col-sm-3 col-md-5'><?php echo tpl_modifier_tr('上一步','site.course'); ?></button></a>
						<a href="/user.org.AddPlan.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>"><button class='down col-sm-3 col-md-5' id="next"><?php echo tpl_modifier_tr('下一步','site.course'); ?></button></a>
					<?php /*	<p>直接完成</p> */?>
					</div>
				</div>
			</div>
		</div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
<script>
$(document).ready(function(){
				$("#sec_add").click(function(){
					$("#add_text").toggle();
					$(this).hide();
				});
				$("#but-cancel").click(function(){
					$("#sec_add").show();
					$(".input-text").hide();
				})

				$("#next").click(function(){
					var seccount = $("#section_list .ordr-3-1").length;
					if(seccount==0){
						layer.msg("<?php echo tpl_modifier_tr('请至少设置一个章节','site.course'); ?>");
						return false;
					}
				});

			//	var subflag = true;
				$("#form").submit(function(){
					/*		var textarea = $("#textsection");
					var textval = textarea.val();
					var textarr = textval.split("\n");
					console.log(textarr);
					$.each(textarr,function(i,val){
						console.log("key"+i+"o val length"+val.length);
						if(val.length>10){
							subflag = false;
							alert("章节名称不能超过个字")
							console.log("subflag"+subflag);

						}
					});
					*/
					$.post("/user.org.addsectionAjaxv2.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>",$(this).serialize(),function(r){
						if(r.error){
							$("#error").html(r.error);
							$("[name="+r.field+"]").focus();
							return false;
						}
						if(r){
							location.reload();
							return false;
						}
					},"json");
					return false;
				});
				$(".del").click(function(){
					if(confirm("<?php echo tpl_modifier_tr('您确定要删除这个章节吗','site.course'); ?>")){
						var sid=$(this).parents("li").attr("sid");
						var cid=$(this).parents("li").attr("cid");
						console.log(sid);
						console.log(cid);
						$.post("/user.org.DelSectionAjaxv2",{ secid:sid,couid:cid },function(r){
							if(r.error){
								//	$("#error").html(r.error);
								alert(r.error);
								//		location.reload();//这里未完善
								return false;
							}
							if(r){
								location.reload();
								return false;
							}
						},"json");
					}
					return false;
				});
			});

		</script>
		<script>
			$(function () {
				var numId = $("#section_list .edit");
                var text_cancel = $("#section_list .cancel");
                var text_del = $("#section_list .del");
                var text_sub = $("#section_list .submit");
				//给单元格注册鼠标点击事件
				numId.click(function () {
					var this_text_sub = $(this).parents("li").find(".submit");
					var this_text_del = $(this).parents("li").find(".del");
					var this_text_cancel = $(this).parents("li").find(".cancel");
					this_text_sub.show();
                    $(this).hide();
                    this_text_cancel.show();
                    this_text_del.hide();
                    //找到对应当前鼠标点击的td，this对应的就是响应了click的那个td
					var sid=$(this).parents("li").attr("sid");
					var cid=$(this).parents("li").attr("cid");
					var tdObj = $(this);
					var tdObj = $(this).parents("li").find("div");
					//判断td中是否有文本框
					if (tdObj.children("input").length>0) {
						return false;
					}
					//获取表格中的内容
					var text = tdObj.html();
					//清空td中的内容
					tdObj.html("");
					var inputObj = $("<input type='text' class='add-chapter' >").css("border-width", "0").css("font-size", tdObj.css("font-size")).css("background-color", tdObj.css("background-color")).width(tdObj.width()).val(text).appendTo(tdObj);
					//文本框插入后先获得焦点、后选中
					inputObj.trigger("focus").trigger("select")
					//文本框插入后不能被触发单击事件
					inputObj.click(function () {
						return false;
					});
                    //处理取消的内容
					this_text_cancel.click(function(){
						//var text = $(this).parents("li").find("input").val();
						var this_text_edit = $(this).parents("li").find(".edit");
						tdObj.html(text);
						inputObj.hide();
						$(this).show();
						this_text_sub.hide();
						this_text_cancel.hide();
						this_text_edit.show();
						this_text_del.show();
						return false;
					});

				});

                //处理文本框上确认的操作
                text_sub.click(function () {				
					var this_text_edit = $(this).parents("li").find(".edit");
					var this_text_del = $(this).parents("li").find(".del");
					var this_text_cancel = $(this).parents("li").find(".cancel");
					var inputtext = $(this).parents("li").find("input").val();
					var sid=$(this).parents("li").attr("sid");
                    var cid=$(this).parents("li").attr("cid");
                    var tdObj = $(this);
                    var tdObj = $(this).parents("li").find("div");
					var oldtext = tdObj.attr('data-desc');
					inputtext = inputtext.replace(/<[^>]+>/g,"");
					tdObj.html(inputtext);
					$.post("/user.org.UpdateSectionAjaxv2.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>",{ section_id:sid,desc:inputtext },function(r){
						if(r.error){
							layer.msg(r.error);
							tdObj.html(oldtext);
							return false;
						}
						if(r){
							tdObj.html(inputtext);
							return false;
						}
					},"json");
					this_text_edit.show();
                    $(this).hide();
                    this_text_cancel.hide();
                    this_text_del.show();

                });
   
				//回车键处理文本框上确认的操作
				var $inp = $('.add-chapter'); 
				$inp.keypress(function(e) {
					var key = e.which; 
					if (key == 13) { 
		                var this_text_edit = $(this).parents("li").find(".edit");
		                var this_text_del = $(this).parents("li").find(".del");
		                var this_text_cancel = $(this).parents("li").find(".cancel");
						var inputtext = $(this).parents("li").find("input").val();
						var sid=$(this).parents("li").attr("sid");
						var cid=$(this).parents("li").attr("cid");
						var tdObj = $(this);
						var tdObj = $(this).parents("li").find("div");
						var oldtext = tdObj.attr('data-desc');
						inputtext = inputtext.replace(/<[^>]+>/g,"");
						tdObj.html(inputtext);
						$.post("/user.org.UpdateSectionAjaxv2.<?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>",{ section_id:sid,desc:inputtext },function(r){
							if(r.error){
								layer.msg(r.error);
								tdObj.html(oldtext);
								return false;
							}
							if(r){
								tdObj.html(inputtext);
								return false;
							}
						},"json");
						this_text_edit.show();
						$(this).show();
						this_text_cancel.hide();
						this_text_del.show();
						text_sub.hide();
					} 
				}); 

			});
		</script>

	</body>
</html>
