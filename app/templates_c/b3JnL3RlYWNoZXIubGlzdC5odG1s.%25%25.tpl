<!DOCTYPE html>
<html>
<head>
<title>教师管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 教师管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script>
<?php if(!isset(SlightPHP\Tpl::$_tpl_vars["searchInfo"])){; ?>
    $(function() {
        page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["length"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["totalPage"]; ?>);
    });
<?php }; ?>
</script>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
    <div class="row">
        <?php echo tpl_function_part("/org.main.menu.teacher"); ?>
        <div class="right-main col-md-16 col-sm-12">
            <div class=" tl-manage-main2 ">
                <div class="tl-list fs14">
                    <div class="clearfix">
        				<form action="" method="post">
    						<div class="c-fl mb10">
                                <div class=" search-frame so-input-org">
        							<input name='keyword'  class="search-input" id='kw' type="text" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["keyword"]; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('搜索教师姓名/手机号','org'); ?>">
                                    <button class="search-box org-t-search-btn">
                                    <span class="search-icon"></span>
                                    <div class='t-list-img clear-icon' id="t-delt-btn" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?>style="display:none;"<?php }; ?>>
                                    </div>
                                    </button>
        						</div>
                            </div>
						</form>	
								
    					<a href="/org.teacher.add" class="c-fr add-button fs14">
                                    <i class="add-btn-icon add-icon c-fl"></i>
                                    <?php echo tpl_modifier_tr('添加老师','org'); ?>
                                </a>
                            </div>
                            <div class="clearfix">
                                <span class="c-fl fcg9"><?php echo tpl_modifier_tr('共','org'); ?><?php if(isset(SlightPHP\Tpl::$_tpl_vars["searchInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["searchInfo"]; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacherCount"]; ?><?php }; ?><?php echo tpl_modifier_tr('人','org'); ?></span>
                                <a id="exportTeacher" class="c-fr blue-link lh22"><?php echo tpl_modifier_tr('导出excel','org'); ?></a>
                            </div>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teachers"])){; ?>
                            <table class="table-grid tl-org-teacher-add-list" id="teacherlist">
                                <thead>
                                <tr>
                                    <td class="col-sm-1 col-md-1"></td>
                                    <td class=" col-sm-2 col-md-2 tec"><?php echo tpl_modifier_tr('姓名','org'); ?></td>
                                    <td class="col-sm-1 col-md-1"><?php echo tpl_modifier_tr('性别','org'); ?></td>
                                    <td class="col-sm-1 col-md-3"><?php echo tpl_modifier_tr('科目','org'); ?></td>
                                    <td class="hidden-sm hidden-md col-lg-1 col-sm-1 hidden-lg"><?php echo tpl_modifier_tr('毕业院校','org'); ?></td>
                                    <td class="hidden-sm hidden-md col-lg-1 col-sm-1 hidden-lg"><?php echo tpl_modifier_tr('教龄','org'); ?></td>
                                    <td class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('角色','org'); ?></td>
                                    <td class="col-sm-2 col-md-3"><?php echo tpl_modifier_tr('联系方式','org'); ?></td>
                                    <td class="col-sm-1 col-md-2"><?php echo tpl_modifier_tr('是否展示','org'); ?></td>
                                    <td class="hidden-sm hidden-md col-sm-2 col-md-3"><?php echo tpl_modifier_tr('最后登录时间','org'); ?></td>
                                    <td class="col-sm-1 col-md-2"><?php echo tpl_modifier_tr('操作','org'); ?></td>
                                </tr>
                                </thead>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["teachers"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["tv"]){; ?>
                                <tr>
                                    <td class="col-sm-1 col-md-1"><input type="checkbox"name="teacherId" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->user_id; ?>"></>
                                    <td class="name new-name col-lg-2 col-sm-2 col-md-1"><a class="blue-link" href="/org.teacher.info.<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->user_id; ?>"><img class="face" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/defaultPhoto.gif<?php }; ?>"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->real_name; ?><?php }; ?></a></td>
                                    <td class="col-sm-1 col-md-1"><?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->gender==1){; ?><?php echo tpl_modifier_tr('男','org'); ?><?php }elseif( SlightPHP\Tpl::$_tpl_vars["tv"]->gender==2){; ?><?php echo tpl_modifier_tr('女','org'); ?><?php }else{; ?><?php }; ?></td>
                                    <td class="col-sm-1 col-md-3">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tags"][SlightPHP\Tpl::$_tpl_vars["tv"]->user_id])){; ?>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["tags"][SlightPHP\Tpl::$_tpl_vars["tv"]->user_id] as SlightPHP\Tpl::$_tpl_vars["tag"]){; ?>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["tag"]; ?>
                                        <?php }; ?>
                                        <?php }; ?>
                                    </td>
                                    <td class="hidden-sm hidden-md hidden-lg col-lg-1"><?php if(mb_strlen(SlightPHP\Tpl::$_tpl_vars["tv"]->college,'utf-8')>10){; ?><?php echo mb_substr(SlightPHP\Tpl::$_tpl_vars["tv"]->college,0,10,'utf-8'); ?>...<?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->college; ?><?php }; ?></td>
                                    <td class="hidden-sm hidden-md col-lg-1 hidden-lg"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->years)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->years; ?><?php }; ?></td>
                                    <td class="col-sm-2 col-md-3">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->roles)){; ?>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->roles as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                        <?php if(isset(SlightPHP\Tpl::$_tpl_vars["roles"][SlightPHP\Tpl::$_tpl_vars["v"]])){; ?> <?php echo SlightPHP\Tpl::$_tpl_vars["roles"][SlightPHP\Tpl::$_tpl_vars["v"]]; ?><?php }; ?>
                                        <?php }; ?>
                                        <?php }; ?>
                                    </td>
                                    <td class="col-sm-2 col-md-3"><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->mobile; ?></td>
                                    <td class="col-sm-1 col-md-2"><?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->visiable==1){; ?><?php echo tpl_modifier_tr('是','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('否','org'); ?><?php }; ?></td>
                                    <td class="hidden-sm hidden-md col-md-3"><?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->last_login=='0000-00-00 00:00:00'){; ?><?php echo tpl_modifier_tr('未登录','org'); ?><?php }else{; ?><?php echo date('m', strtotime(SlightPHP\Tpl::$_tpl_vars["tv"]->last_login)); ?><?php echo tpl_modifier_tr('月','course.list'); ?><?php echo date('j', strtotime(SlightPHP\Tpl::$_tpl_vars["tv"]->last_login)); ?><?php echo tpl_modifier_tr('日','course.list'); ?> <?php echo date('H:i', strtotime(SlightPHP\Tpl::$_tpl_vars["tv"]->last_login)); ?><?php }; ?></td>
                                
                                    <td class="teacher-edutor-box col-sm-1 col-md-2" uid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->user_id; ?>">
									<a href="/org.teacher.edit.<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->user_id; ?>" class="blue-link"><?php echo tpl_modifier_tr('修改','org'); ?></a>
									<a href="#" class="blue-link remove"><?php echo tpl_modifier_tr('删除','org'); ?></a>
                                    </td>
                                </tr>
                                <?php }; ?>
                           </table>
                           <div class="col-sm-20 mt10">
                                <input type="checkbox" id="allselect"><label for="allselect">全选</label>
                                <a href="javascript:void(0);" id="teacher_check_id" class="cDarkgray ml10">删除</a>
                                <a href="javascript:void(0);" id="set_display" class="cDarkgray ml10">设置展现<input type="hidden" name="visiable" value="1"/></a>
                                <a href="javascript:void(0)" id="set_not_display" class="cDarkgray ml10">设置不展现<input type="hidden" name="visiable1" value="0"/></a>
                           </div>
								<?php }; ?>
							<?php if(empty(SlightPHP\Tpl::$_tpl_vars["teachers"])){; ?>
							<div class="col-md-20 col-lg-20 mt15 fs14 tac" style="padding-top:60px;display:block;">
								<img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" />
								<p><?php echo tpl_modifier_tr('您查询的老师不存在，快去添加吧','org'); ?>~</p>
							</div>
							<?php }; ?>
						</div>
                    </div>
					<div class="page-list" id="pagepage">
					</div>
					</div>
    </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>
$(function(){
//全选
    $("#allselect").click(function(){
        var teacherId=$("#teacherlist").find("input");
        for(var i=0;i<=teacherId.length;i++){
        //console.log(teacherId.eq(i).attr('checked'));
            if(teacherId.eq(i).attr('checked')){
                teacherId.eq(i).removeAttr('checked');
            }else{
                teacherId.eq(i).attr('checked','checked');
                teacherId.eq(i).prop('checked',true);
            }
        }
    });
	var allId;
	function selectId(allId){
		var valArr = new Array; 
		$("input[name='teacherId']:checked").each(function(i){ 
			valArr[i] = $(this).val(); 
		}); 
		var vals = valArr.join(',');
		if(vals==''){
			layer.msg("请选择教师~");
			return false;
		}
		return vals;
	}
	$("#teacher_check_id").click(function(){
		var teacher_id = selectId(allId);
		layer.confirm("<?php echo tpl_modifier_tr('确定删除吗','org'); ?>?", {
					title:"<?php echo tpl_modifier_tr('教师信息','org'); ?>",
                    btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
                    shade: false //不显示遮罩
                    }, function(){
					$.post("/org.teacher.DelTeacherAjax",{ teacher_id:teacher_id },function(r){
						if(r){
							layer.msg("删除成功",{ icon: 1 });
							location.reload();
							return false;
						}
					},"json");
					
		});
		
	});
	$("#set_display").click(function(){
		var teacher_id = selectId(allId);
		if(teacher_id==false){
			return false;
		}
		var visiable = $("input[name='visiable']").val();
		$.post("/org.teacher.setDisplay",{ teacher_id:teacher_id,visiable:visiable },function(r){
			if(r){
				layer.msg("设置成功",{ icon: 1 });
				location.reload();
				return false;
			}
		},"json");
	});
	$("#set_not_display").click(function(){
		var teacher_id = selectId(allId);
		if(teacher_id==false){
			return false;
		}
		var visiable = $("input[name='visiable1']").val();
		$.post("/org.teacher.setDisplay",{ teacher_id:teacher_id,visiable:visiable },function(r){
			if(r){
				layer.msg("设置成功",{ icon: 1 });
				location.reload();
				return false;
			}
		},"json");
	});
    $(".remove").click(function(){
		var uid = $(this).parents("td").attr("uid");
		layer.confirm("<?php echo tpl_modifier_tr('确定删除吗','org'); ?>?", {
                    title:"<?php echo tpl_modifier_tr('教师信息','org'); ?>",
                    btn: ["<?php echo tpl_modifier_tr('确定','org'); ?>","<?php echo tpl_modifier_tr('取消','org'); ?>"], //按钮
                    shade: false //不显示遮罩
                    }, function(){
                    $.post("/org.teacher.delTeacherAjax","uid="+uid,function(r){
					if(r){
						location.reload();
						return false;
					}
                },"json");
         }, function(){
            
        });
    });
    $(".teacher-edutor-box").hover(function(){
        $(this).find("ul").css("display","block");
    },function(){
        $(".teacher-edutor-box ul").css("display","none");
    })
	$("#exportTeacher").click(function(){
		window.location.href="/phpexcel/platformstu/exportTeacher/";
	});
	 $(".org-t-search-btn").click(function(){
		var keyword = $("input[name=keyword]").val();
    	window.location.href="/org/teacher/list"+keyword;
    });

    $("#t-delt-btn").click(function() {
        $(this).css("display",'none');
        $('#kw').val('');
    })
});
</script>
</html>
