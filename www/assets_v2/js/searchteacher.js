/*学生上课页面登录自动报名js*/

$(document).ready(function(e){
	$('#keyword').keyup(function(){
		var keyword=$(this).val();
        $(this).closest("div").find(".hide-icon").show();
		$.post("/org.main.SearchTeacherAjax",{ 'keyword':keyword },function(r){
        if(jQuery.isEmptyObject(r.data)==true){
			var html='<li>没有找到老师</li>';
			$('.last').show().html(html);
           //setTimeout("$('.last').hide()",3000);
        }else{
				var html='';
				$(r.data).each(function(i,item){
					html+='<li tid="'+item.teacher_id+'" dataname="'+item.real_name+'"><span class="face"><img src="'+item.thumb_big+'"></span>'+item.real_name+'</li>';
				});
				$('.last').show().html(html);
        }
		},"json");
        $(document).click(function(event){
            if(!$(event.target).is('#keyword')){
                $(".last").hide();
                if($("#teahidden").val()==""){
                    $("#keyword").val("");
                    $("#key-hide").hide();
                }
            }
        })
	});
	$('.last').on('click','li',function(){
		var tid=$(this).attr('tid');
		var dataname=$(this).attr('dataname');
		/*var dom = $('<input type="text" class="col-sm-12" name="editTeacher" id="editTeacher" value='+tid+'>');
		$("#keyword").after(dom);
		*/
		$("#teahidden").val(tid);
		$("#keyword").val(dataname);
		$('.last').hide();
		//var is_star=$('form').find('input[name=is_star]').val();
	/*	$.post("/org.main.setTeacherStarAjax",{ "tid":tid,"is_star":is_star },function(r){
			if(r.error){
				layer.msg(r.error);
			}else{
				parent.location.reload();
			}
		},"json");
		*/
	});
	$('#editkeyword').keyup(function(){
		var keyword=$(this).val();
        $(this).closest("div").find(".hide-icon").show();
		$.post("/org.main.SearchTeacherAjax",{ 'keyword':keyword },function(r){
        if(jQuery.isEmptyObject(r.data)==true){
			var html='<li>没有找到老师</li>';
			$('.editlast').show().html(html);
            //setTimeout("$('.editlast').hide()",3000);
        }else{
				var html='';
				$(r.data).each(function(i,item){
					html+='<li tid="'+item.teacher_id+'" dataname="'+item.real_name+'"><span class="face"><img src="'+item.thumb_big+'"></span>'+item.real_name+'</li>';
				//	html+='<li tid="'+item.teacher_id+'" dataname="'+item.name+'">'+item.name+'</li>';
				});
				$('.editlast').show().html(html);
			}
		},"json");
        $(document).click(function(event){
            if(!$(event.target).is('#editkeyword')){
                $(".editlast").hide();
                if($("#editTeacher").val()==""){
                    $("#eidtkeyword").val("");
                    $("#edit-hide").hide();
                }
            }
        })
	});
    $("#key-hide,#edit-hide").click(function(){
        var keyword=$("#keyword");
        var keyid=$("#teahidden");
        var keyword2=$("#editkeyword");
        var keyid2=$("#editTeacher");
        $(this).hide();
        keyword.val("");
        keyword2.val("");
        if(keyid.val()!=""){
            keyid.val("");
        }
        if(!keyid2.val()!=""){
            keyid2.val("");
        }
    })
	$('.editlast').on('click','li',function(){
		var tid=$(this).attr('tid');
		var dataname=$(this).attr('dataname');
		/*var dom = $('<input type="text" class="col-sm-12" name="editTeacher" id="editTeacher" value='+tid+'>');
		$("#keyword").after(dom);
		*/
		$("#editTeacher").val(tid);
		$("#editkeyword").val(dataname);
		$('.editlast').hide();
		//var is_star=$('form').find('input[name=is_star]').val();
	/*	$.post("/org.main.setTeacherStarAjax",{ "tid":tid,"is_star":is_star },function(r){
			if(r.error){
				layer.msg(r.error);
			}else{
				parent.location.reload();
			}
		},"json");
		*/
	});
	//input边框
	$(".new-keyword").focus(function(){
		$(this).css("border","solid 1px #ffa81e");
	});
	$(".new-keyword").blur(function(){
		$(this).css("border","solid 1px #dddddd");
	});
});


