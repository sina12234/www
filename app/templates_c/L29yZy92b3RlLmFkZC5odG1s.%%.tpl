<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>发起新投票 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 发起新投票 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<style>
.class-cont{ position: relative; }
</style>
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav"); ?>
<section id="operat-tols" class="pd40">
    <div class="container">
        <div class="row">
        <!-- lt -->
        <?php echo tpl_function_part("/org.main.menu.vote"); ?>
        <!-- /lt -->
        <!-- rt -->
        <div class="right-main col-md-16">
            <div class="content">
                <div class="course-bt col-md-20">
                    <p>
                        <a href="/org.vote.list">
                            <?php echo tpl_modifier_tr('返回','org'); ?>&gt;<b></b>
                        </a>
                        <b style="color:#666;"><?php echo tpl_modifier_tr('发起新投票','org'); ?></b>
                    </p>
                </div>
            <form name="form1" method="post">
                    <ol class="send-new-content fs14 clearfix">
                        <li>
                            <span class="title fs14 ter col-md-5"><?php echo tpl_modifier_tr('投票新题','org'); ?></span>
                            <div class="rt-content col-md-15">
                                <input class="bor1px ipt col-md-10" name="title" placeholder="<?php echo tpl_modifier_tr('请填写投票内容','org'); ?>" type="text" />
                                <div class="error-tip col-md-4 fs12 tel" style='display:none;'>
                                    <i class="solid-icon c-fl"></i>
                                    <span id='msg'><?php echo tpl_modifier_tr('标题不能为空','org'); ?></span>
                                </div>
                                <i class="gnger-delt-btn add-vote-ct" style="right:150px"></i>
                                <span class="explain c-fl col-md-20 add-vote-areatxt mb10">+<?php echo tpl_modifier_tr('添加投票说明','org'); ?></span>
                                <div class="wrap-upload-box bor1px">
                                    <textarea class="wrap-upload-pic" id='descript' name='descript' placeholder="<?php echo tpl_modifier_tr('对投票活动进行简短的说明吧！','org'); ?>"></textarea>
                                    <!--<div class="upload-ft clearfix">
                                        <div class="pic-list c-fl"></div>
										<a href="javascript:;" id='add_file' class="a-upload c-fr"> </a>
                                    </div>-->
                                </div>
                            </div>
                        </li>
                        <li>
                            <span class="title ter col-md-5"><?php echo tpl_modifier_tr('投票选项','org'); ?>：</span>
                            <div class="rt-content sech-lst-ipt col-md-12 col-lg-12">
                                <select class="mb10 bor1px" name='objtype' id="option_select_val">
                                    <option value="1"><?php echo tpl_modifier_tr('课程','org'); ?></option>
                                    <option value="2"><?php echo tpl_modifier_tr('老师','org'); ?></option>
                                    <option value="3"><?php echo tpl_modifier_tr('章节','org'); ?></option>
                                </select>
                                <div id="new-lst">
                                    <div class="lst lst01 mb10 clearfix">
                                        <span class="ter">1</span>
                                        <input class="bor1px c-fl ipt col-md-12 txt" name="keyword" value="" placeholder="<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>" type="text" />

										<input type="hidden" value="" name="siteid" />
										<input type="hidden" value="" name="imgSrc" />

                                        <i class="gnger-delt-btn delt-class-val"></i>
                                        <ul class='last col-md-12' style="display:none;"></ul>
                                    </div>
                                    <div class="lst lst01 mb10 clearfix">
                                        <span class="ter">2</span>
                                        <input class="bor1px c-fl ipt col-md-12 txt" name="keyword" placeholder="<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>" type="text" />

										<input type="hidden" value="" name="siteid" />
										<input type="hidden" value="" name="imgSrc" />

                                        <i class="gnger-delt-btn delt-class-val"></i>
                                        <ul class='last col-md-12' style="display:none;"></ul>
                                    </div>
                                    <!--章节-->
                                    <div class="class-cont clearfix">
                                        <div class="lst mb10 clearfix c-fl">
                                            <span class="ter">1</span>
                                            <input class="bor1px txt c-fl ipt col-md-16" style="width:80%" name="keyword" placeholder="<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>" type="text" />
                                            <input type="hidden" value="" name="siteid" />
											<input type="hidden" value="" name="imgSrc" />

											<i class="gnger-delt-btn delt-class-val"></i>
                                            <ul class='last plan col-md-16 mr10' style="display:none;"></ul>
                                        </div>
                                        <select class="bor1px c-fl classSel mr10" onchange="selec.call($(this));">
                                            <option value="0"><?php echo tpl_modifier_tr('班级','org'); ?></option>
                                        </select>
                                        <select class="bor1px c-fl chapterSel">
                                            <option value="0"><?php echo tpl_modifier_tr('章节','org'); ?></option>
                                        </select>
                                    </div>
                                    <div class="class-cont clearfix">
                                        <div class="lst mb10 clearfix c-fl">
                                            <span class="ter">2</span>
                                            <input class="bor1px txt c-fl ipt col-md-16" style="width:80%" name="keyword" placeholder="<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>" type="text" />
                                            <input type="hidden" value="" name="siteid" />
											<input type="hidden" value="" name="imgSrc" />

											<i class="gnger-delt-btn delt-class-val"></i>
                                            <ul class='last plan col-md-16 mr10'  style="display:none;"></ul>
                                        </div>
                                        <select class="bor1px classSel c-fl mr10" onchange="selec.call($(this));">
                                            <option value="0"><?php echo tpl_modifier_tr('班级','org'); ?></option>
                                        </select>
                                        <select class="bor1px chapterSel c-fl">
                                            <option value="0"><?php echo tpl_modifier_tr('章节','org'); ?></option>
                                        </select>
                                    </div>


                                </div>
                                <div class="clearfix"></div>
                                <span class="explain add-select-class col-md-8">+<?php echo tpl_modifier_tr('添加选项','org'); ?></span>
                            </div>
                        </li>
                        <li>
                            <span class="title ter col-md-5"><?php echo tpl_modifier_tr('选择模式','org'); ?>：</span>
                            <div class="rt-content col-md-4 col-lg-4">
                                <label class="list-lay mt5 c-fl mr10">
                                    <input type="radio" name="multiSelect" value='1' checked /> <?php echo tpl_modifier_tr('单选','org'); ?>
                                </label>
                                <label class="mr10 list-lay c-fl mt5">
                                    <input type="radio" name="multiSelect" value='2' /> <?php echo tpl_modifier_tr('多选','org'); ?>
                                </label>
                                <select class="bor1px mos-select c-fl"  id="selectCount" name='selectCount'>
                                    <option value="2"><?php echo tpl_modifier_tr('最多选2项','org'); ?></option>
                                    <option value="3"><?php echo tpl_modifier_tr('最多选3项','org'); ?></option>
                                    <option value="4"><?php echo tpl_modifier_tr('最多选4项','org'); ?></option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <span class="title ter col-md-5"><?php echo tpl_modifier_tr('开始时间','org'); ?>：</span>
                            <input class="col-md-3 bor1px ipt datestart col-lg-4" name='startTime' placeholder="<?php echo tpl_modifier_tr('选择时间','org'); ?>" type="text" />
                            <span class="title col-md-2" style="width:13%;"><?php echo tpl_modifier_tr('结束时间','org'); ?>：</span>
                            <select class="bor1px col-md-4 costum-select mr10" id="endTime" name='endTime'>
                                <option value="1"><?php echo tpl_modifier_tr('一周','org'); ?></option>
                                <option value="2"><?php echo tpl_modifier_tr('一个月','org'); ?></option>
                                <option value="3"><?php echo tpl_modifier_tr('半年','org'); ?></option>
                                <option value="4"><?php echo tpl_modifier_tr('一年','org'); ?></option>
                                <option value="5"><?php echo tpl_modifier_tr('自定义','org'); ?></option>
                            </select>
                            <input class="bor1px ipt col-md-3 datestart costum-ipt" name='endTime1' placeholder="<?php echo tpl_modifier_tr('选择时间','org'); ?>" type="text" />
                        </li>
                        <li>
                            <span class="title ter col-md-5"><?php echo tpl_modifier_tr('投票结果','org'); ?>：</span>
                            <div class="lst">
                                <label class="mr10 list-lay-vote">
                                    <input type="radio" checked name="type" value='1' style="display: none;" /> <?php echo tpl_modifier_tr('投票后可见','org'); ?>
                                </label>
                                <!--<label class="list-lay-vote">
                                    <input type="radio" name="type" value='2' /> <?php echo tpl_modifier_tr('任何人可见','org'); ?>
                                </label>
                                -->
                            </div>
                        </li>
                        <li>
                            <span class="title ter col-md-5"><?php echo tpl_modifier_tr('是否显示','org'); ?>：</span>
                            <div class="lst">
                                <label class="mr10 list-lay-vote">
                                    <input type="checkbox" checked name="pubarticle"/> <?php echo tpl_modifier_tr('发布在文章中','org'); ?>
                                </label>
                            </div>
                        </li>
                        <li style="text-align:center;">
                            <button id='sub' class="btn_yellow_5 reque_btn"><?php echo tpl_modifier_tr('确定','org'); ?></button>
                        </li>
                    </ol>
             </form>
            </div>
        </div>
		
		<div class="add_file_logo" style='display:none;'>
			<div id='file_logo'>
				<div id='img_new'>图片比例为16:9,尺寸不小于150*84支持png、jpg</div>
			</div>
			 <div class="add_flie_input">
			<button id='save' style="display:none;">保存</button>
            <button id="browse">上传图片</button>
			</div>
			<input type="hidden" size="4" id="x" name="x">
			<input type="hidden" size="4" id="y" name="y">
			<input type="hidden" size="4" id="x2" name="x2">
			<input type="hidden" size="4" id="y2" name="y2">
			<input type="hidden" size="4" id="w" name="w">
			<input type="hidden" size="4" id="h" name="h">
		</div>
        <!-- /rt -->
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>

</body>
<style type="text/css">
.layui-layer{ border-radius:0;}
.layui-layer-btn a:hover{ color:#fff;}
.class-cont{ display:none;}
.right-main select{ width:91px;}
.add_file_logo { width:100%;float:left;padding:30px;}
.add_file_logo #file_logo{ margin:0 auto;width:500px;height:290px;background:#f7f7f7;text-align:center;line-height:290px;cursor:pointer;font-size:16px;color:#a5a5a5; }
.add_file_logo span{ color:#a9a9a9;padding-top:10px;float:left;width:100%;text-align:center; }
.add_file_logo .add_flie_input { float:left;margin-top:10px;width:100%;text-align:center; }
.add_file_logo .add_flie_input button { width:100px;height:35px;background:#ffa81d;border:none;cursor:pointer;color:#fff;border-radius:5px;margin:0 0 0 10px; }
.add_file_logo .add_flie_input button.cGray{ background:#d1d1d1;display:none; }
/*#img1 { width:275px;height:155px; }*/
.jcrop-holder { margin:0 auto; }
.moxie-shim{ display:none;}
</style>
<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
</html>
<script type="text/javascript">
var placeholder="";
function tabName(){
    var _this  = $(this);
    var _val   = $(this).find('span').html();
    if(_this.attr('s') == 1){
        var siteid = $(this).attr('siteid');
        var imgSrc = $(this).attr('imgurl');
        var _Val   = $(this).closest("div").find(".txt").val(_val);

        $(this).closest("div").find("input[name=siteid]").val(siteid);
		$(this).closest("div").find("input[name=imgSrc]").val(imgSrc);

        $(this).closest("ul").hide();
        //章节下拉 插入班级，章节部分
        if($("#option_select_val").val()==3){
            var siteid=_this.attr('siteid');

            $.post("/org.vote.searchClass",{ siteid:siteid },function(r){
				var chtml = '';
				var phtml = '';
                chtml += '<option value="0"><?php echo tpl_modifier_tr("班级","org"); ?></option>';
                for(var i in r){
                    chtml+='<option value='+i+'>'+r[i].name+'</option>';
                }
                _this.parents(".class-cont").find(".classSel").html(chtml);

            },'json');
        }
    }
    $(this).closest("ul").hide();
    return false;
}

function selec(){
	var _this   = $(this);
	var classId = $(this).val();
	$.post("/org.vote.searchPlan",{ classId:classId },function(r){
		var phtml = '<option value="0"><?php echo tpl_modifier_tr("章节","org"); ?></option>';
		for(var i in r){
			phtml+='<option value='+r[i].planId+'>'+r[i].sectionName+'</option>';
		}
		_this.siblings('.chapterSel').html(phtml);
	},'json');

}

$(function(){
	var jcrop_api,boundx,boundy;
	var add_file = new plupload.Uploader({
		runtimes:'html5,flash,silverlight,html4',
		browse_button: 'browse',
		url: '/org.vote.uploadImg/',
		filters: {
			mime_types : [
			{ title : "Image files", extensions : "jpg,gif,png" }
			],
			max_file_size:"800kb"
		}
		,multi_selection:false
	});
	add_file.init();

    add_file.bind('FilesAdded', function(up, files) {
        add_file.start();
    });

    add_file.bind('FileUploaded', function(up, file,info) {
		if(info.response){
            $("#browse").html("重新上传").css("background","#ffa81d");
			$("#save").show();
            if(jcrop_api)jcrop_api.destroy();
			$("#file_logo").html("<img id='img1' src='"+info.response+"?"+Math.random()+"'>");
	
			$('#img1').Jcrop({
				boxWidth:480,boxHeight:270,
				onChange: updatePreview,
				onSelect: updatePreview,
				minSize:[480,270],
				aspectRatio: 16/9 //xsize / ysize
				},function(){
				// Use the API to get the real image size
				var bounds = this.getBounds();
				jcrop_api = this;
				this.setSelect([0,0,480,270]);
			}).show();			
		}
    });
	
	function updatePreview(c){
		if (parseInt(c.w) > 0){
			$('#x').val(c.x);
			$('#y').val(c.y);
			$('#x2').val(c.x2);
			$('#y2').val(c.y2);
			$('#w').val(c.w);
			$('#h').val(c.h);
		}
	}
	
	
	$("#save").click(function(){
		var voteImg = $("#img1").attr('src');
		var w = $('#w').val();
		var h = $('#h').val();
		var y = $('#y').val();
		var x = $('#x').val();
		var x2= $('#x2').val(); 
		$.post("/org.vote.partImg/",{ voteImg:voteImg,w:w,h:h,y:y,x2:x2,x:x },function(r){
			if(r.fid != ''){
				var _HtmlBox='<div class="pic-list-box c-fl mr10">'
				_HtmlBox+="<img src='"+r.src+"' id='"+r.fid+"'/>"
				_HtmlBox+='<i class="delet-img"><i>'
				_HtmlBox+='</div>';
				$(".pic-list").append(_HtmlBox);
				if($(".pic-list").children(".pic-list-box").length>2){
					$("#add_file").hide();
				}
                layer.closeAll();
			}else{
				layer.msg("保存失败");
        }
		},'json');
});
	
	
	//删除图片
	$(".pic-list").on("click",".delet-img",function() {
		if($(".pic-list").children(".pic-list-box").length<4){
			$("#add_file").show();
		}
		$(this).parent().remove();
	})
	//添加投票说明
	$(".send-new-content .add-vote-areatxt").click(function() {
		if($(".send-new-content .wrap-upload-box").is(":hidden")) {
			$(".send-new-content .add-vote-areatxt").html("-<?php echo tpl_modifier_tr('添加投票说明','org'); ?>");
			$(".send-new-content .wrap-upload-box").show();
		}else{
			$(".send-new-content .add-vote-areatxt").html("+<?php echo tpl_modifier_tr('添加投票说明','org'); ?>");
			$(".send-new-content .wrap-upload-box").hide();
		}
	})

    //筛选类型
    $("#option_select_val").change(function() {
        $("#new-lst").children().find('input').val('');
        if($("#option_select_val").val()==3) {
            $(".class-cont").show();
            $("#new-lst .class-cont").show();
            $("#new-lst .lst01").hide();
        }else{
            $("#new-lst .class-cont").hide();
            $("#new-lst .lst01").show();
        }
    });
    $("#option_select_val").change(function(){
        placeholder = "<?php echo tpl_modifier_tr('搜索','org'); ?>"+$("#option_select_val option:selected").text()+"<?php echo tpl_modifier_tr('名称','org'); ?>";
		if($("#option_select_val").val()==3){
			placeholder = "<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>";
		}
        $("input[name='keyword']").attr("placeholder",placeholder);
    });
    //搜索选项数据
    $("#new-lst").on('keyup','input[type=text]',function(){
        var _This = $(this);
        var selectId=$("#option_select_val option:selected").attr("value");
        var optionVal=$(this).val();
        $(this).nextAll().val('');
        var siteidObj = $("input[name='siteid']");
        var siteidArr =new Array;
        for(var i=0;i<siteidObj.length;i++){
            if(siteidObj[i].value>0) {
                siteidArr[i] = siteidObj[i].value;
            }
        }
        $(this).siblings(".last").show();
        var _html='';
        $.post("/org.vote.search",{ selectId:selectId,optionVal:optionVal },function(r){
             if(jQuery.isEmptyObject(r.data)==true){
                 _html='<li class="li1" s='+r.code+' onclick="tabName.call($(this))">'+r.error+'</li>';
                _This.parents('.lst').find("ul").show();
                _This.parents('.lst').find("ul").html(_html);
             }else{
                 var errorInfo = '';
                $(r.data).each(function(i,item){
                    if(r.type == 1){
                        if(""+$.inArray(item.course_id+"", siteidArr)<0){
                            _html+='<li siteid='+item.course_id+' title="'+item.title+'" s='+r.code+' onclick="tabName.call($(this))" imgUrl='+item.thumb_med+'><span>'+item.title+'</span></li>';
                        }else{
                            errorInfo='没有找到课程';
                        }
                    }else if(r.type ==2){
                        if($.inArray(""+item.teacher_id+"", siteidArr)<0){
                            _html+='<li siteid='+item.teacher_id+' s='+r.code+'  title="'+item.title+'"  onclick="tabName.call($(this))" imgUrl='+item.thumb_big+'>'+'<img width="20" height="20" src="'+item.thumbBig+'"><span>'+item.real_name+'</span></li>';
                        }else{
                            errorInfo='没有找到老师';
                        }

                    }else{
                        _html+='<li siteid='+item.course_id+' s='+r.code+'  title="'+item.title+'"  onclick="tabName.call($(this))" imgUrl='+item.thumb_med+'><span>'+item.title+'</span></li>';

                    }
                });
                 if(_html==''){
                     _html='<li class="li1" s="-1" onclick="tabName.call($(this))">'+errorInfo+'</li>';
                 }
                _This.parent().find(".last").show();
                _This.parent().find(".last").html(_html);
             }
        },'json');
    });

    $(".send-new-content .add-select-class").click(function() {
        if($("#option_select_val").val()==3){
            if(placeholder == ''){
                placeholder = "<?php echo tpl_modifier_tr('搜索课程名称','org'); ?>";
            }
            var liLength = $("#new-lst .class-cont").length+1;
            var _Html='<div class="class-cont clearfix" style="display: block;">'
                        +'<div class="lst mb10 clearfix c-fl">'
                        +'<span class="ter">'+liLength+'</span>'
                        +'<input class="bor1px c-fl txt ipt col-md-16" style="width:80%" name="sech_class" placeholder="搜索课程名称" type="text" />'
                        +'<input type="hidden" value="" name="siteid" />'
                        +'<input type="hidden" value="" name="imgSrc" />'
						+'<i class="gnger-delt-btn delt-class-val"></i>'
                        +'<ul class="last plan col-md-16 mr10" style="display:none;"></ul>'
                        +'</div>'
                        +'<select class="bor1px classSel c-fl mr10" onchange="selec.call($(this));">'
                        +'<option value="0"><?php echo tpl_modifier_tr("班级","org"); ?></option>'
                        +'</select>'
                        +'<select class="bor1px chapterSel c-fl">'
                        +'<option value="0"><?php echo tpl_modifier_tr("章节","org"); ?></option>'
                        +'</select>'
                        +'<i class="gnger-delt-x fs14" style="right:18%">X</i>'
                        +'</div>';
            if(liLength < 61) {
                $("#new-lst").append(_Html);
            }else{
                return;
            }
        }else{
            if(placeholder == ''){
                placeholder = '<?php echo tpl_modifier_tr("搜索课程名称","org"); ?>';
            }
            var liLength = $("#new-lst .lst01").length+1;
            var _Html="<div class='lst lst01 mb10 clearfix'>"
                         +'<span class="ter">'+liLength+'</span>'
                             +'<input class="bor1px c-fl ipt col-md-12 txt" name="keyword" placeholder='+placeholder+' type="text" />'
                             +'<input type="hidden" value="" name="siteid" />'
                             +'<input type="hidden" value="" name="imgSrc" />'
                             +'<i class="gnger-delt-btn delt-class-val"></i>'
                             +'<i class="gnger-delt-x fs14">X</i>'
                             +'<ul class="last col-md-12" style="display:none"></ul>'
                         +'</div>';
                if(liLength < 61) {
                    $("#new-lst").append(_Html);
                }else{
                    return;
                }
        }
    });
    //动态追加元素
    $("#new-lst").on('click','.gnger-delt-x',function(){
        $(this).parent().nextAll().find('ul').html('');
        var obj = $(this).parent().nextAll().find('span').text();
        for(var i=0;i<obj.length;i++){
            $(this).parent().nextAll().eq(i).find('span').text(""+(obj[i]-1)+"");
        }
        $(this).parent().remove();
    })
    $("#new-lst").delegate('foucus','input[type="text"]',function(){
        $(this).css("border","1px solid #ffa81e");
    })
    $("#new-lst").delegate('blur','input[type="text"]',function(){
        $(this).css("border","1px solid #ddd");
    })
    //自定义时间
    $(".send-new-content .costum-select").change(function() {
        if($(this).val()==5) {
            $(".send-new-content .costum-ipt").show();
        }else{
            $(".send-new-content .costum-ipt").hide();
        }
    });
    //日期
    $('.datestart').datetimepicker({
      format:"Y-m-d H:m:s",
      timepicker:true,
      todayButton:false
    });
	//模式
	$(".rt-content .list-lay").click(function() {
		if($(this).find("input[name='multiSelect']").val()==2) {
			$(".mos-select").show();
		}else{
			$(".mos-select").hide();
		}
	})
});

$(".reque_btn").click(function(){
	$("form").submit(function(){
		return false;
	});

	var voteToken = '<?php echo SlightPHP\Tpl::$_tpl_vars["voteToken"]; ?>';
	var imgs = new Array();
	/**
	*$(".pic-list img").each(function(i) {
	*	imgs[i] = $(this).attr("id")
	*});
	*/
	
	var title = $("input[name='title']").val();
	title = $.trim(title);
	if(title.length == 0){
		$(".tel").show();
		return false;
	}
	if(title.length > 25){
		$(".tel").show();
		$("#msg").text('最多25个字');
		return false;
	}
	var objtype   = $('#option_select_val').val();
	var optionVal = new Array();
	if(objtype == 3){
		$(".class-cont .chapterSel").each(function(i){
			var pid = $(this).val();
			var img = $(this).closest("div").find("input[name=imgSrc]").val();
			var val = '';
			if(pid > 0){
				optionVal[i] = pid+'***'+val+'***'+img;
			}
		})
	}else{
		$("#new-lst input[type=text]").each(function(i) {
		var val = $(this).val();
		var sid = $(this).closest("div").find("input[name=siteid]").val();
		var img = $(this).closest("div").find("input[name=imgSrc]").val();
		if(sid != undefined && sid!= ''){
				optionVal[i] = sid+'***'+val+'***'+img;
			}
		})
	}
	if(optionVal.length < 2 ){
		layer.msg("投票选项最少添加二项");
		return false;
	}

	var descript    = $("#descript").val();
	var selectCount = $("#selectCount").val();
	var startTime   = $("input[name='startTime']").val();
	if(startTime == ''){
		layer.msg("请选择开始时间");
		return false;
	}
	var endTime     = $("#endTime").val();
	var endTime1    = $("input[name='endTime1']").val();
	if(endTime1 !='' && endTime1 < startTime){
		layer.msg("结束时间不能小于开始时间");
		endTime1 = '';
		return false;
	}
	var multiSelect = $("input[name='multiSelect']:checked").val();
	var type        = $("input[name='type']:checked").val();
    var pubarticle  = $("input[name='pubarticle']").is(':checked');
	$.post("/org.vote.add", { title:title,voteToken:voteToken,imgs:imgs,descript:descript,optionVal:optionVal,objtype:objtype,selectCount:selectCount,startTime:startTime,endTime:endTime,endTime1:endTime1,multiSelect:multiSelect,type:type, pubarticle:pubarticle}, function(r){
		if(r.code == 1){
			layer.msg('添加成功！');
			window.location.href='/org.vote.list';
		}else{
			layer.msg(r.msg);
			return false;
		}
	},'json');
})

$('#add_file').on('click', function(){
     $('#file_logo').html("<div id='img_new'>图片比例为16:9,尺寸不小于150*84支持png、jpg</div>");
	 $('#save').hide();
	 $("#browse").html("选择图片");
     layer.open({
        type: 1,
        title: '上传图片',
        shadeClose: true,
        shade: 0.8,
        area: ['600px', '500px'],
        content:$(".add_file_logo")
     })
})

</script>
