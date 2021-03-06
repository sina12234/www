<!DOCTYPE html>
<html>
<head>
<title>添加会员类型 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 机构中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30" id="add-vipclass">
	<div class="container">
		<div class="row">
		<!-- lt -->
        <?php echo tpl_function_part("/org.main.menu.member"); ?>
		<!-- /lt -->
		<!-- rt -->
		<div class="right-main col-md-16 col-sm-12">
				<div class="com-md-20 pd0 clearfix mb20">
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setId"])){; ?>
					<div class="fs14 c-fl"><a href="/org.member">会员管理</a> > 编辑会员类型</div>
				<?php }else{; ?>
					<div class="fs14 c-fl"><a href="/org.member">会员管理</a> > 新建会员类型</div>
				<?php }; ?>
				</div>
				<form id="mform" class="mainCon s-new-info-base">
                <ul class="fs14 mt30 form-horizontal">
                    <li class="mt10 item_nickname form-group ">
                        <div class="control-label col-xs-8 col-sm-5 cDarkgray"><span class="cRed">*</span> 会员类型名称：</div>
                        <div class="label-for col-xs-10 col-sm-14">
                            <input class="col-xs-12" type="text" name="title" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['title']; ?><?php }; ?>" placeholder="最多输入8个字">
                        </div>
                    </li>
                    <li class="mt10 item_nickname form-group ">
                        <div class="control-label col-xs-8 col-sm-5 cDarkgray"><span class="cRed">*</span> 价格：</div>
                        <div class="label-for col-xs-10 col-sm-10" id="vip-class-item">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_30'])){; ?>
                            <div class="select col-xs-10 mt5"><i></i><input type="text" name="price_30"  value="<?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_30']/100; ?>"> 元/30天</div>
						<?php }else{; ?>
							<div class="col-xs-10 mt5"><i></i><input type="text" name="price_30" disabled> 元/30天</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_90'])){; ?>
                            <div class="select col-xs-10 mt5"><i></i><input type="text" name="price_90" value="<?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_90']/100; ?>" > 元/90天</div>
						<?php }else{; ?>
							<div class="col-xs-10 mt5"><i></i><input type="text" name="price_90" disabled> 元/90天</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_180'])){; ?>
                            <div class="select col-xs-10 mt5"><i></i><input type="text" name="price_180" value="<?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_180']/100; ?>"> 元/180天</div>
						<?php }else{; ?>
							<div class="col-xs-10 mt5"><i></i><input type="text" name="price_180" disabled> 元/180天</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_360'])){; ?>
                            <div class="select col-xs-10 mt5"><i></i><input type="text" name="price_360" value="<?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['price_360']/100; ?>"> 元/360天</div>
						<?php }else{; ?>
							<div class="col-xs-10 mt5"><i></i><input type="text" name="price_360" disabled> 元/360天</div>
						<?php }; ?>
                        </div>
                    </li>
                    <li class="mt10 form-group ">
                        <div class="control-label col-xs-8 col-sm-5 cDarkgray"><span class="cRed">*</span> 课程范围：</div>
                        <div class="label-for col-xs-10 col-sm-14">
                            <p><a href="javascript:void(0)" class="add-vipcourse" id="add-vipcourse">编辑课程范围</a></p>
                            <select name="select_course_id" style="width:60%;" id="course_select" size="5" class="multiple-select mt5 col-sm-12 ">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
								<?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
                                <option value ="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->object_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->course_name; ?></option>
								<?php }; ?>
								<?php }; ?>
                            </select>
                        </div>
						<input name="course_id" type="hidden" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseIdStr"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["courseIdStr"]; ?><?php }; ?>">
                    </li>
                    <li class="mt10 form-group ">
                        <div class="control-label col-xs-8 col-sm-5 cDarkgray"><span class="cRed">*</span> 描述：</div>
                        <div class="label-for col-xs-10 col-sm-14">
                            <textarea name="descript" id="" cols="30" rows="10" placeholder="最多输300个字" class="col-xs-12"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['desc']; ?><?php }; ?></textarea>
                        </div>
                    </li>
                    <li class="mt10 form-group ">
                        <div class="control-label col-xs-8 col-sm-5 ">&nbsp;</div>
                        <div class="label-for col-xs-10 col-sm-10">
                            <a class="btn" id="memberset_btn">确定</a>
                            <a href="/org.member" class="btn-default ml10">取消</a>
                        </div>
						<input name="setId" type="hidden" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setId"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["setId"]; ?><?php }; ?>">
						<input name="status" type="hidden" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetInfo"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["memberSetInfo"]['status']; ?><?php }else{; ?>0<?php }; ?>">
                    </li>
                </ul>
				</form>
			</div>
		</div>
	</div>
</section>
<div class="layer-courselist" style="display:none;">
    <div class="lc-so-name"><form action=""><input type="text" id="search_course" name="course_name" placeholder="搜索课程名称" class="col-xs-10"></form></div>
    <div class="lc-list mt15">
        <p class="fs14">全部课程<span class="cGray fs12">（只能将付费课添加为会员课程）</span></p>
        <ul class="multiple-select mt10" id="multiple-left" >
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["leftCourseList"])){; ?>
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["leftCourseList"] as SlightPHP\Tpl::$_tpl_vars["co"]){; ?>
            <li data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["co"]->course_id; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["co"]->selected == 1){; ?>class="selected"<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["co"]->title; ?></li>
			<?php }; ?>
			<?php }; ?>
        </ul>
    </div>
    <div class="co-option mt15" id="co-option"><a href="#" id="add-btn">添加 》</a><a href="#" class="mt10" id="del-btn">《 删除</a></div>
    <div class="lc-list mt15">
        <p class="fs14">已选课程</p>
        <ul class="multiple-select mt10" id="multiple-right">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["haveCourseList"])){; ?>
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["haveCourseList"] as SlightPHP\Tpl::$_tpl_vars["ho"]){; ?>
            <li data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["ho"]->object_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["ho"]->course_name; ?></li>
			<?php }; ?>
			<?php }else{; ?>
			<li class="defalut">还没有课程</li>
			<?php }; ?>

        </ul>
    </div>
    <div class="col-xs-20 mt10 pd0">
		<button class="btn mr20" id="course_add">确定</button>
		<a href="javascript:;" class="btn-default" id="course_cel">取消</a>
    </div>
</div>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
$(function() {
	var setId = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setId"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["setId"]; ?>;<?php }else{; ?><?php }; ?>

	$('#add-vipcourse').click(function() {
		var lihtml = '';
		$('#course_select option').each(function(){
			var courseId = $(this).attr('value');
			var title = $(this).text();
			lihtml += '<li data-id="'+courseId+'">'+title+'</li>';
			$('#multiple-left li').each(function(){
				if($(this).attr('data-id') == courseId){
					$(this).addClass('selected');
				}
			});
		});
		$('#multiple-right').html(lihtml);
		layer.open({
            type: 1,
            title: ['选择课程范围'],
            shadeClose: true,
            shade: 0.8,
            area: ['765px', '525px'],
            content: $('.layer-courselist')
        });
	});

	/*选择课程*/
    $('#multiple-right,#multiple-left').on('click','li:not(.selected)',function(){
        if($(this).hasClass('select')){
            $(this).removeClass('select');
        }else{
            $(this).addClass('select');
        }
        if($('#multiple-right li.select').length>0){
            $('#del-btn').addClass('allow');
        }else{
            $('#del-btn').removeClass('allow');
        }
        if($('#multiple-left li.select').length>0){
            $('#add-btn').addClass('allow');
        }else{
            $('#add-btn').removeClass('allow');
        }
    });
/*选择课程*/
    $('#add-btn').click(function(){
        if($(this).hasClass('allow')){
            var cHtml='';
            $('#multiple-left li.select').each(function() {
                cHtml += '<li data-id="'+$(this).attr('data-id')+'">'+$(this).html()+'</li>';
				$(this).removeClass('select');
				$(this).addClass('selected');
            });

            $('#multiple-right').append(cHtml);
            $('#multiple-right .defalut').remove();
            if($('#multiple-left li').length==0){
                $(this).removeClass('allow');
            }
        }else{
            $('#multiple-left').css('border','1px solid #ffa91e');
        }
    });
    $('#del-btn').click(function(){
        if($(this).hasClass('allow')){
            var cHtml='';
            $('#multiple-right li.select').each(function() {
				var id = $(this).attr('data-id');
                cHtml += '<li data-id="'+id+'">'+$(this).html()+'</li>';
                $(this).remove();
				$('#multiple-left').find('.selected').each(function(){
					if($(this).attr('data-id') == id){
						$(this).removeClass('selected');
					}
				})
            });
            if($('#multiple-right li').length==0){
                $(this).removeClass('allow');
                $('#multiple-right').append('<li class="defalut">还没有课程</li>');
            }
        }else{
            $('#multiple-right').css('border','1px solid #ffa91e');
        }
    });
    $('#course_add').click(function(){
		var select_text = '';
		var id_list = [];
		$('#multiple-right li').each(function(){
			var id = $(this).attr('data-id');
			var name = $(this).text();
			id_list.push(id);
			if(name != '还没有课程'){
				select_text += '<option value="'+id+'">'+name+'</option>';
			}
		});
		$('input[name=course_id]').val(id_list);
        $('#course_select').html(select_text);
        $('.layer-courselist').hide();
    })
    $('#course_cel,#course_add').click(function(){
       layer.closeAll();
    });
	//筛选课程
	$("#search_course").keyup(function(){
        var _This = $(this);
        var title=$(this).val();
        $.post("/org.member.searchCourse",{ title:title },function(r){
			if(r.code == 0){
				var _html='';
				$(r.data).each(function(i,item){
					var selected = 0;
					$('#multiple-right li').each(function(){
						if($(this).attr('data-id') == item.course_id){
							selected = 1;
						}
					});
					if(selected == 1){
						_html += '<li data-id='+item.course_id+' class="selected">'+item.title+'</li>'
					}else{
						_html += '<li data-id='+item.course_id+'>'+item.title+'</li>'
					}
				});
				$('#multiple-left').html(_html);
			}else{
				$('#multiple-left').html('');
			}
        },'json');
    });
	var page = 1;
	$('#multiple-left').scroll(function(){
		var scrollTop = $(this).scrollTop();
	　　var scrollHeight = $(this)[0].scrollHeight;
	　　var offetHeight  = $('#multiple-left').height();
	　　if(scrollTop + offetHeight == scrollHeight){
			page++;
			var title = $('#search_course').val();
			$.ajax({
				type:"post",
				url: '/org.member.searchCourse',
				data:{ page:page,title:title },
				dataType:'json',
				success:function(r){
					var data = r.data;
					if(data.length > 0 ){
						var _html='';
						$(data).each(function(i,item){
							var selected = 0;
							$('#multiple-right li').each(function(){
								if($(this).attr('data-id') == item.course_id){
									selected = 1;
								}
							});
							if(selected == 1){
								_html += '<li data-id='+item.course_id+' class="selected">'+item.title+'</li>'
							}else{
								_html += '<li data-id='+item.course_id+'>'+item.title+'</li>'
							}
						});
						$('#multiple-left').append(_html);
					}else{
						$('#multiple-left').unbind('scroll');
					}
				},
				complete:function(){
				},
				error: function(r){
					page--;
				}
			});
		}
	});

    $('#vip-class-item i').click(function(){
        if($(this).parent().hasClass('select')){
            $(this).parent().removeClass('select');
			$(this).parent().find('input').val('');
			$(this).parent().find('input').attr('disabled',true);
        }else{
            $(this).parent().addClass('select');
            $(this).siblings('input').removeAttr('disabled');
        }
    });
	$('#memberset_btn').click(function(){
		var title = $('input[name=title]').val();
		var course_id = $('input[name=course_id]').val();
		var descript = $('textarea[name=descript]').val();

		var flag = 0;
		if(title == ''){
			layer.msg('会员类型名称不能为空！');
			return;
		}
		if(title.length > 8){
			layer.msg('会员类型名称最多输入8个汉字！');
			return;
		}
		flag = 1;
		$('#vip-class-item').find('.select input').each(function(){
			var price = $(this).val();
			if( price == '' || price == 0 || price == '0.00'){
				flag = 0
			}
		})
		if(flag == 0){
			layer.msg('请填写价格！');
			return;
		}
		if( course_id == '' ){
			layer.msg('请选择课程范围！');
			return;
		}
		if( descript == '' ){
			layer.msg('请填写描述！');
			return;
		}
		if( descript.length > 300 ){
			layer.msg('描述最多输入300个字符！');
			return;
		}
		$.ajax({
			type:"post",
			url: '/org.member.updateMemberSetAjax',
			data:$('#mform').serialize(),
			dataType:'json',
			success:function(ret){
				if(ret.error){
					layer.msg(ret.error);
				}else if(ret.code == 0){
					layer.msg(ret.msg);
					window.location.href="/org.member";
				}else{
					layer.msg(ret.msg);
				}
			}
		})

	})
})
</script>
