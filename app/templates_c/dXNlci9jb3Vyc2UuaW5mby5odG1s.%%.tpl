<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>创建课程班级 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 创建课程班级 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script src="<?php echo utility_cdn::js('/assets_v2/js/commonfun.js'); ?>"></script>
</head>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class='mt40'>
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/org.main.menu.course"); ?>
            <div class='right-main col-sm-9 col-md-16'>
        <div class='content'>
            <div class='course-bt col-sm-12 col-md-20'>
                <p><a href="/user.org.course"><?php echo tpl_modifier_tr('返回','site.course'); ?>><b></a><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["courseTypeShow"],'site.course'); ?></b></p>
            </div>
            <div class="step">
                    <ul class="col-sm-10 col-md-20">
                        <li class="col-sm-4 col-md-7">
                        <p class="bg2"></p>
                        <p class="n2">1</p>
                        <p class="text2"><?php echo tpl_modifier_tr('课程信息','site.course'); ?></p>
                        </li>
                        <li class="col-sm-4 col-md-6">
                        <p class="bg1"></p>
                        <p class="n">2</p>
                        <p class="text"><?php echo tpl_modifier_tr('设置章节','site.course'); ?></p>
                        </li>
                        <li class="col-sm-4 col-md-7">
                        <p class="bg1"></p>
                        <p class="n">3</p>
                        <p class="text"><?php echo tpl_modifier_tr('开班授课','site.course'); ?></p>
                        </li>
                    </ul>
                </div>
                <form autocomplete="off" id="form" role="form" onsubmit="return false">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["error"])){; ?>
                    <div class="form-group red">
                        <label class="col-sm-2 control-label"></label>
                        <h3 style="color:red"><?php echo SlightPHP\Tpl::$_tpl_vars["error"]; ?></h3>
                    </div>
                    <?php }; ?>
                    <input name="type_id" type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["type_id"]; ?>">
                    <input name="cid" type="hidden" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cid"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?><?php }; ?>">
                    <input id="thumb_big" type="hidden" name="thumb_big" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big; ?><?php }; ?>" />
                    <input id="thumb_med" type="hidden" name="thumb_med" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_med)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_med; ?><?php }; ?>" />
                    <input id="thumb_small" type="hidden" name="thumb_small" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_small)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_small; ?><?php }; ?>" />
                    <input type="hidden" id="price_old" name="price_old" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee->price)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee->price; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('设置价格','site.course'); ?>￥" />
                    <input type="hidden" id="fee_type_old"  name="fee_type_old" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type; ?><?php }; ?>" />
                    <input type="hidden" id="is_promote"  name="is_promote" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->is_promote)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->is_promote; ?><?php }; ?>" />
                    <ul class="form fs14">
                        <li>
                            <div class="label col-xs-5">
                                <?php echo tpl_modifier_tr('课程名称','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                                <input class="col-xs-10" type="text" name="title" placeholder="<?php echo tpl_modifier_tr('请在此填写课程名称','site.course'); ?>" id="title" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->title)){; ?><?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["course_ret"]->title); ?><?php }; ?>"/>
                            </div>
                        </li>
                        <li>
                            <div class="label col-xs-5">
                                <?php echo tpl_modifier_tr('付费类型','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                                <label>
                                    <input id="fee" type="radio" class="isCheckedRadio"  name="fee_type"  <?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type)){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type==1){; ?>checked<?php }; ?><?php }; ?> value="1"  >
                                    <span><?php echo tpl_modifier_tr('付费课','site.course'); ?></span>
                                </label>
                            <label>
                                <input id="free" type="radio"  class="isCheckedRadio" name="fee_type" value="0" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type)){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee_type==0){; ?>checked<?php }; ?><?php }else{; ?>checked<?php }; ?> value="0" >
                                <span><?php echo tpl_modifier_tr('免费课','site.course'); ?></span>
                            </label>
                            <div class='sec-bg' id="price">
                                <label>
                                    <p><?php echo tpl_modifier_tr('课程价格','site.course'); ?>:</p>
                                    <input type="text" id="money" name="price" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee->price)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->fee->price; ?><?php }; ?>" placeholder="<?php echo tpl_modifier_tr('设置价格','site.course'); ?>￥" onkeyup ="clearNoNum(this)" />
                                    <p><?php echo tpl_modifier_tr('元','site.course'); ?></p>
                                </label>
                            </div>
                            </div>
                        </li>
                    <!-- 会员课程-->

                    <!-- 会员课程 暂时不上线先注释-->
                    <?php if(SlightPHP\Tpl::$_tpl_vars["type_id"] != 3){; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["memberSetList"])){; ?>
                        <li class="member-course-isChecked" style="display:none;">
                            <div class="label col-xs-5">
                                <?php echo tpl_modifier_tr('会员课程','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                                <div class="col-md-20 pd0">

                                    <label class="c-fl mr20">
                                        <input type="radio" class="isCheckedRadio" name="is_member" value="1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseMember"])){; ?>checked<?php }; ?>/> 是
                                    </label>
                                    <label class="c-fl">
                                        <input type="radio" class="isCheckedRadio" name="is_member" value="0" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["courseMember"])){; ?>checked<?php }; ?> /> 否
                                    </label>
                                </div>
                                <div class="bgf7 member-course-category clearfix col-md-15" style="display:none;">
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["memberSetList"] as SlightPHP\Tpl::$_tpl_vars["mo"]){; ?>
                                    <label>
                                        <input type="checkbox" class="member-set-id" name="setIds[]" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["mo"]->checked)){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["mo"]->checked == 1){; ?>checked<?php }; ?><?php }; ?> value="<?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->pk_member_set; ?>"> <?php echo SlightPHP\Tpl::$_tpl_vars["mo"]->title; ?>
                                    </label>
                                    <?php }; ?>
                                </div>
                            </div>
                        </li>
                    <?php }; ?>
                    <?php }; ?>
                    <!--/会员课程 -->
                        <li class="course-cate">
                            <div class="label col-xs-5">
                            <?php echo tpl_modifier_tr('课程分类','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                            <select class="big-sec" id="level0" name="first_cate" style="min-width: 140px;">
                                <option value = "0"><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["firstCateList"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["firstCateList"] as SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
                                <option value = "<?php echo SlightPHP\Tpl::$_tpl_vars["fo"]->pk_cate; ?>" <?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->first_cate)){; ?><?php if(SlightPHP\Tpl::$_tpl_vars["course_ret"]->first_cate == SlightPHP\Tpl::$_tpl_vars["fo"]->pk_cate){; ?>selected<?php }; ?><?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["fo"]->name_display ,'course.list'); ?></option>
                                <?php }; ?>
                                <?php }; ?>
                            </select>
                            <select class="big-sec ml10" id="level1" name="second_cate" style="min-width:140px;">
                                <option value=""><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>
                            </select>
                            <select class="big-sec ml10" id="level2" name="third_cate" style="min-width:140px;">
                                <option value=""><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>
                            </select>
                            </div>
                        </li>
                       <li>
                            <div class="label col-xs-5">
                                课程标签:
                            </div>
                            <div class="label-for col-xs-15">
                                <div class="c-fl col-md-11 onmouse">
                                    <div id="onmouse" class="c-fl">
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["selected"])){; ?>
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["selected"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                    <span class="on" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>"><b></b><i></i><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
                                    <?php }; ?>
                                    <?php }; ?>
                                    </div>
                                  <div class="c-fl inputTaga tagNameStr">
                                    <input class="inputTagator" name="inputTagator" id="inputTagator"  type="text" placeholder="点击空白输入标签">
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["selected"])){; ?>
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["selected"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                    <input type='hidden' name='tagName[]' value='<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?>'/>
                                    <?php }; ?>
                                    <?php }; ?>
                                    </div>
                            </div>
                            <a href="#" class="doubt-icon c-fl ml10" title="1.课程标签是机构对课程的自定义分类;
2.管理员可在“页面设置”中设置展现在全部课程页的标签。"></a>
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"]) or !empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                            <div class="inputTaga-display col-lg-11" id="inputTaga">
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["often"])){; ?>
                                    <p>
                                    <span class="c-fl taga-course-info">最近使用:</span>&nbsp;
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["often"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                    <span class="c-fl taga-summer tag-name-info <?php if(isset(SlightPHP\Tpl::$_tpl_vars["selectdTagId"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag,SlightPHP\Tpl::$_tpl_vars["selectdTagId"])){; ?>taga-end<?php }; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>"><b></b><i></i><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
                                    <?php }; ?>
                                    </p>
                                    <?php }; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["lasted"])){; ?>
                                    <p><span class="c-fl taga-course-info">常用标签:</span>&nbsp;
                                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["lasted"] as SlightPHP\Tpl::$_tpl_vars["k"]=>SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                    <span class="c-fl taga-summer taga-info-tp <?php if(isset(SlightPHP\Tpl::$_tpl_vars["selectdTagId"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag,SlightPHP\Tpl::$_tpl_vars["selectdTagId"])){; ?>taga-end<?php }; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>"><b></b><i></i><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></span>
                                    <?php }; ?>
                                    </p>
                                    <?php }; ?>
                            </div>
                            <?php }; ?>
                            <p class="col-xs-20 pd0 cGray fs12">每个标签限10个字符，输入标签名称后点击回车，最多添加3个标签</p>
                        </li>
 <script>
function mystr(str){
	var l = str.length;
	var blen = 0;
	for(i=0; i<l; i++) {
        if ((str.charCodeAt(i) & 0xff00) != 0) {
			blen ++;
		}
		blen ++;
	}
    if(blen>10){
    	layer.msg("不能超过10个字符");
		return false;
    }
}
$(function(){
    if($('.onmouse .on').length >= 3) {
        $('.inputTaga').hide();
    };
    $('#onmouse span').each(function(){
        var that=$(this).text();
        $('.taga-summer').each(function (e){
            if(that == $(this).text()){
                $(this).addClass('taga-end');
            }
        })
    });
})
$('.onmouse').click(function(e){
        e.stopPropagation();
        $('.inputTaga-display').show();
        $(".inputTaga input").focus();
        if($('#onmouse >span').length > 2){
            $(".inputTaga input").hide();
        }else{
            $(".inputTaga input").show();
        }

})
$('#onmouse').on('click','#tagats-end',function(){
    $(this).parent().hide();
})
$("#inputTagator").keydown(function(e){
    var e = e || event,
        keycode = e.which || e.keyCode;
    if (keycode==32 || keycode==13 || (event.shiftKey && event.keyCode==13)) {
        var tagName = $.trim($(this).val());
        if(tagName==''){
            layer.msg('标签不能为空');
            return false;
        }
        if(mystr(tagName)==false){
            return false;
        }
        if($('#onmouse >span').length >= 3) {
            $('.inputTaga').hide();
            layer.msg('只能添加三项');
            $('#inputTagator').val('');
            return false;
        }else {
            Contrasts(tagName);
        }
    }
    if (keycode===8) {
        var len = $('.inputTagator').val();
        var s_len = $('#onmouse>span');
        if (len.length < 1){
            if(s_len.length>0){
                $('#onmouse span:last').remove();
            }
        }
    }
});
function Contrasts(that){
    var tagatHtml = '<span class="on"><b></b>'+that+'<i></i></span>';
    var tagsList=[];
    $('#onmouse .on').each(function(){
        tagsList.push($(this).text());
    });
    if($.inArray(that,tagsList) == -1){
        $("#onmouse").append(tagatHtml);
        $('input[name="inputTagator"]').val('');
        $(".tagNameStr").append("<input type='hidden' name='tagName[]' value='"+that+"'/>");
        tageEnd(that);
    }else{
        layer.msg('这个标签已经有了');
    }
    if($('#onmouse .on').length >= 3 ) {
        $('.inputTagator').hide();
        return false;
    }
}
function tageEnd(that){
        $(".taga-summer").each(function(){
            if($(this).text()==that){
                $(this).addClass('taga-end');
            }
        })
    }

$('#inputTaga .taga-summer').on('click',function (e){
        e.stopPropagation();
        var tagName=$(this).text();
        if(!$(this).hasClass('on')){
            addtable(tagName);
        }
});
    $('#onmouse').on('click','i',function(){
        if($(this).parent().attr('class')=="on"){
            var thatval = $(this).text();
            $("input[name='tagName[]").each(function (e){
            if(thatval==$(this).val()){
                $(this).remove();
            }
            });
            removetable($(this).parent());
        }
    })


function addtable(that){
    if($('.onmouse .on').length >= 3) {
        $('.inputTaga').hide();
        layer.msg('只能添加三项');return false;
    }
    Contrasts(that);
}
function removetable(that){
    that.remove().html();
    that.find("input[name='tagName[]']").remove();
    $('.taga-summer').each(function (e){
        if(that.text() == $(this).text()){
            $(this).removeClass('taga-end');
        }
    })
    $("input[name='tagName[]").each(function (e){
        if(that.text()==$(this).val()){
            $(this).remove();
        }
    })
    if($('.onmouse .on').length < 3) {
        $('.inputTaga').show();
    }
};
$('body,html').click(function (e){
    e.stopPropagation();
    $('.inputTaga-display').hide();
})
</script>
                        <li>
                            <div class="label col-xs-5">
                            <?php echo tpl_modifier_tr('课程封面','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                            <label>
                                <input type="radio" name="cover" checked/><?php echo tpl_modifier_tr('图片形式','site.course'); ?>
                            </label>
                        <div id="panel" class="col-xs-20 pd0">
                            <div class="img" style="float:left;width:240px;height:135px;overflow:hidden">
                                <img id="img_o" style="display:none;" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big)){; ?>src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big); ?>"<?php }; ?> >
                                <img id="img_p" style="max-width:240px;max-height:135px;border:1px;margin-top:5px;display:block;" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big)){; ?>src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["course_ret"]->thumb_big); ?>" <?php }else{; ?> src="<?php echo utility_cdn::img('/assets_v2/img/img-sc.png'); ?>"<?php }; ?>>
                            </div>
                            <p class="left cGray pd20">
                                <?php echo tpl_modifier_tr('图片大于480*270','site.course'); ?><br/><?php echo tpl_modifier_tr('支持JPG,JPEG,PNG格式','site.course'); ?>(<?php echo tpl_modifier_tr('6M以下','site.course'); ?>)
                            </p>
                        </div>
                            </div>
                    <div class='message-show' id="add-pic-box" style="display:none;">
                        <div class='show-hea'>
                            <p>
                                <?php echo tpl_modifier_tr('添加图片','site.course'); ?>
                                <b class="close">×</b>
                            </p>
                        </div>
                        <div class="mty" style="overflow:hidden;border:1px;">
                            <img id="img_new" width="" height="" src="<?php echo utility_cdn::img('/assets/images/mty.png'); ?>" alt="">
                        </div>
                        <div class='scbtn'>
                            <input type="button" id="browse" class="btn_yellow_1"value="<?php echo tpl_modifier_tr('上传图片','site.course'); ?>">
                            <p><?php echo tpl_modifier_tr('图片不能小于480*270','site.course'); ?>,<?php echo tpl_modifier_tr('且必须是16:9的宽屏比例','site.course'); ?></p>
                        </div>
                        <div class='show-wc'>
                            <input id="subpic" type="button" value="<?php echo tpl_modifier_tr('确定','site.course'); ?>">
                        </div>
                        <input type="hidden" size="4" id="x" name="x">
                        <input type="hidden" size="4" id="y" name="y">
                        <input type="hidden" size="4" id="x2" name="x2">
                        <input type="hidden" size="4" id="y2" name="y2">
                        <input type="hidden" size="4" id="w" name="w">
                        <input type="hidden" size="4" id="h" name="h">
                    </div>
                        </li>
                        <li>
                            <div class="label col-xs-5">
                            <?php echo tpl_modifier_tr('适合范围','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-15">
                            <textarea type="text" name="scope" rows="2" maxlength="101"  style="height:60px" class="col-xs-15" placeholder="请输入适合人群和阶段（100字内）" id="crowd" /><?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->scope)){; ?><?php echo htmlspecialchars(SlightPHP\Tpl::$_tpl_vars["course_ret"]->scope); ?><?php }; ?></textarea>
                            </div>
                        </li>
                        <li>
                            <div class="label col-xs-5">
                            <?php echo tpl_modifier_tr('课程介绍','site.course'); ?>:
                            </div>
                            <div class="label-for col-xs-14">
                            <script src="<?php echo utility_cdn::js('/assets/libs/ckeditor/ckeditor.js'); ?>"></script>
                            <script src="<?php echo utility_cdn::js('/assets/libs/ckeditor/adapters/jquery.js'); ?>"></script>
                            <textarea name="descript" id="descript" class="big-wid"><?php if(isset(SlightPHP\Tpl::$_tpl_vars["course_ret"]->descript)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->descript; ?><?php }; ?></textarea>
                            <script>
                                $(document).ready(function(){
                                    if(localStorage){
                                        //if($("desc").val()=="" && localStorage.xx!=""){
                                            //  $("desc").val(localStorage.xx);
                                            //}
                                        //setInterval(function(){ localStorage.xx=$("textarea").val(); },3000);
                                    }
                                    $( '#descript' ).ckeditor( { customConfig: '/assets/libs/activeNotice.config.js' } );
                                    $("input[type=text]").each(
                                    function(){
                                        $(this).keypress( function(e) {
                                            var key = window.event ? e.keyCode : e.which;
                                            if(key.toString() == "13"){
                                                return false;

                                            }
                                        });
                                    });

                                    $("onmouse input").each(
                                    function(){
                                    });
                                });
                            </script>
                            </div>
                        </li>
                        <li>
                            <div class="label col-xs-5"></div>
                            <div class="label-for col-xs-15">
                                <button class="next-btn" onClick="submitForm()"><?php echo tpl_modifier_tr('下一步','site.course'); ?></button>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        </div>
    </div>
</section>
<?php echo tpl_function_part("/site.main.footer"); ?>
<div id="bg">
</div>
<style>
#bg{ position: fixed; top: 0%; left: 0%; width: 100%; height: 100%; background-color: black; z-index:1001; -moz-opacity: 0.7; opacity:.70; filter: alpha(opacity=70); display: none; }
.message-show{ width: 620px;height:480px;background-color: #fff; z-index: 1002;position: fixed;top: 10%;left: 25%;border-radius:0;display:none; }
.message-show .show-hea{ height:35px;line-height:35px;background-color: #ffa81d;border-radius:0 ;}
.message-show .show-hea p{ padding:0 10px;font-size: 14px;position: relative;color:#fff;}
.message-show .show-hea p img{ margin-right: 10px; }
.message-show .show-hea p b{ float: right;font-size: 20px; cursor: pointer; font-weight: normal;position: absolute; top: 0px;right: 4px; color:#ecf0f1;font-size: 30px;}
.message-show .show-hea p b:hover{ color:#874900;}
.message-show .mty{ margin: 30px 0px 0px 114px; }
.message-show .scbtn{ margin: 10px 0px 0px 70px  }
.message-show .scbtn input{ float: left; width: 125px;height: 40px;margin-right: 20px;border:1px solid #ccc;line-height: 40px;/*background-image: -moz-linear-gradient(top, #00FF00, #FF0000); 火狐*/ }
.message-show .scbtn p{ line-height: 40px; color: #a1a1a1; }
.message-show .wbk input{ width: 380px;height: 45px;margin: 10px 0px 0px 70px  }
.message-show .show-wc input{ width: 240px;height: 45px; background-color: #FFA81D;border: none; border-radius: 5px;color: #fff;margin: 20px 0px 0px 200px;font-size: 16px; }
.btn_yellow_1{ color: #ff6600;background: #ffebcc;}
</style>
</body>
<script src="<?php echo utility_cdn::js('/assets/js/jcrop/js/jquery.Jcrop.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
<script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
<script>
var jcrop_api,boundx,boundy;
    $(function(){
        var uploader = new plupload.Uploader({
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
                url: '/file.main.uploadCoursePicV2.<?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>',
                filters: {
                    mime_types : [
                    { title : "Image files", extensions : "jpg,gif,png" }
                    ],
                    max_file_size:'6000kb'
                }
                ,multi_selection:false
        });
        uploader.init();
        uploader.bind('FilesAdded', function(up, files) {
            uploader.start();
        });
        uploader.bind('Error', function(up, error) {
                if(error.code == -600){
                    layer.msg("<?php echo tpl_modifier_tr('文件太大了，请选择小于6M的文件','site.course'); ?>");
                }else{
                    layer.msg("出错了，请重新选择文件或者刷新页面再重新选择文件");

                }
            });

            uploader.bind('UploadProgress', function(up, file) {
                $("#browse").val("<?php echo tpl_modifier_tr('上传中','site.course'); ?>："+ file.percent +"%");
            });
            uploader.bind('FileUploaded', function(up, file,info) {
                $("#browse").val("<?php echo tpl_modifier_tr('重新选择','site.course'); ?>");
                if(info.response){
                    var r = jQuery.parseJSON(info.response);
                    if(!r){
                        alert("上传失败");
                        return false;
                        }else if(r.error){
                        alert(r.error);
                        return false;
                    }
                    $("#browse").val("<?php echo tpl_modifier_tr('上传完成','site.course'); ?>");
                    if(jcrop_api)jcrop_api.destroy();
                    $("#img_plan").show();
                    //$("#img_p").hide();
                    //$("#img_o").show();
                    $("#img_p").show();
                    $("#img_o").hide();
                    $("#img_new").attr("src",r.file+"?"+Math.random());
                    $("#img_o").attr("src",r.file+"?"+Math.random());
                    $("#img_prew").attr("src",r.file+"?"+Math.random());
                    $('#img_new').Jcrop({
                        boxWidth:480,boxHeight:270,
                        onChange: updatePreview,
                        onSelect: updatePreview,
                        minSize:[480,270],
                        aspectRatio: 16/9 //xsize / ysize
                        },function(){
                        // Use the API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the API in the jcrop_api variable
                        jcrop_api = this;
                        this.setSelect([0,0,480,270]);
                    }).show();


                }

                function updatePreview(c){
                    if (parseInt(c.w) > 0){
                        $('#x').val(c.x);
                        $('#y').val(c.y);
                        $('#x2').val(c.x2);
                        $('#y2').val(c.y2);
                        $('#w').val(c.w);
                        $('#h').val(c.h);

                        $pcnt = $('#panel .img');
                        $pimg = $('#panel #img_o');

                        xsize = $pcnt.width();
                        ysize = $pcnt.height();
                        var rx = xsize / c.w;
                        var ry = ysize / c.h;

                $pimg.css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        };
    });
});
</script>
<script>
function submitForm(){
    var value = $("#title").val(); // 获取值
    var value1 = $("#img_o").attr("src");
    var cid = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cid"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["cid"]; ?>;<?php }else{; ?><?php }; ?>

    value = $.trim(value);
    value.replace(/<[^>]+>/g,"");
    if (value == ''){
        $("#title").focus();
        layer.msg("<?php echo tpl_modifier_tr('课程名称不能为空','site.course'); ?>!");
        return false;
    };
    if (typeof(value1) == "undefined"){
        layer.msg("<?php echo tpl_modifier_tr('请设置课程图片','site.course'); ?>!");
        return false;
    };
    if( $('select[name=first_cate]').val() == 0){
        layer.msg("<?php echo tpl_modifier_tr('请选择课程分类','site.course'); ?>!");
        return false;
    }
    if( $('select[name=second_cate]').val() == 0){
        layer.msg("<?php echo tpl_modifier_tr('请选择课程分类','site.course'); ?>!");
        return false;
    }
    if( $('select[name=third_cate]').val() == 0){
        layer.msg("<?php echo tpl_modifier_tr('请选择课程分类','site.course'); ?>!");
        return false;
    }
    var sub_flag = 0;
    $('.subject-list-txt').each(function(){
        if($(this).val() == ''){
            sub_flag = 1;
        }
    });
    if(sub_flag == 1){
        layer.msg("<?php echo tpl_modifier_tr('科目不能为空','site.course'); ?>!");
        return false;
    }
    $.ajax({
        type:"post",
        url: '/user.org.addCourseAjax',
        data:$('#form').serialize(),
        dataType:'json',
        success:function(ret){
            if(ret.code == 0 ){
                window.location.href = '/user.org.sectionInfo.'+ret.data;
            }else{
                layer.msg(ret.error);
            }
        }
    });
}
$(document).ready(function(){
    $(".form").on('keyup','textarea#crowd', function () {
        var curLength = $(this).val().length;
        if (curLength > 100) {
            var textin = $(this).text().substr(0, 100);
            $(this).text(textin);
            $(this).css("border","1px solid red");
            layer.msg("适合范围最多输入100字");
        }
    });
    $('#level1').hide();
    $('#level2').hide();
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_ret"])){; ?>
        var first_cate  = <?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->first_cate; ?>;
        var second_cate = <?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->second_cate; ?>;
        var third_cate  = <?php echo SlightPHP\Tpl::$_tpl_vars["course_ret"]->third_cate; ?>;
        var course_attr = <?php echo json_encode(SlightPHP\Tpl::$_tpl_vars["courseAttrValue"]); ?>;
        <?php }else{; ?>
        var first_cate  = '';
        var second_cate = '';
        var third_cate  = '';
        var course_attr = '';
        <?php }; ?>

        $('#form').on('click','.subject-list-txt',function(){
            var _this=$(this);
            console.log(_this);
            var attrId = _this.attr('data-attr');
            var attrValueId = (_this.attr('data-id')).split(',');
            var attrName = _this.attr('data-attr-name');
            $.get("/user.org.getAttrValueByAttrId."+attrId,{ },function(item){
                if(item.data){
                    var data = item.data;
                    var alert_bg='<div class="alert-bg" id="alert-bg"></div>';
                    var alert_content='<div class="alert-content" id="alert-main"><div id="alert-content" style="position:relative;width:100%;height:100%"></div></div>';
                    var alert_title_name= attrName;
                    var alert_title='<div class="alert-title">'+alert_title_name+'</div><div class="alert-close" id="alert-close"></div>';
                    var alert_c='<div class="alert-content-c" id="alert-content-c"></div>';
                    var alert_btn='<div class="subject-btn"><p class="cGray pdb10">最多可选择3个科目</p><button class="btn-green"><?php echo tpl_modifier_tr("确定","site.course"); ?></button><button class="btn-gray"><?php echo tpl_modifier_tr("取消","site.course"); ?></button></div>';
                    var alert_ul='<ul class="subject-list" id="subject-list">';

                    for(var i in data){
                            var aclass = '';
                            for(var j in attrValueId){
                                if(attrValueId[j] == data[i].pk_attr_value){
                                    var aclass = 'on';
                                    break;
                                }
                            }
                        alert_ul += '<li class="'+aclass+'" data-id="'+data[i].pk_attr_value+'">'+data[i].name+'</li>';
                    }
                    alert_ul+='</ul>';
                    $('body').append(alert_bg,alert_content);
                    $('#alert-content').append(alert_title,alert_c,alert_btn);
                    $('#alert-content-c').append(alert_ul);
                    //关闭弹层
                    $('#alert-close,.btn-gray').click(function(){
                        $('#alert-main').remove();
                        $('#alert-bg').remove();
                    });
                    //选中
                    var sub_list=[];
                    var sub_list_txt=[];
                    $("#subject-list").on("click","li",function(){
                        if($("#subject-list li.on").length<=3){
                            if($(this).is(".on")){
                                $(this).removeAttr("class");
                                if($("#subject-list li.on").length<3){
                                    $("#subject-list li.gray").removeClass("gray");
                                }
                            }else{
                                if(!$(this).is(".gray")){
                                    $(this).addClass("on");
                                }
                                if($("#subject-list li.on").length>=3){
                                    $("#subject-list li:not(.on)").addClass("gray");
                                }
                            }
                        };
                    });


                    //确认
                    $('.btn-green').click(function(){
                        $('#subject-list li.on').each(function(){
                            sub_list.push($(this).attr('data-id'));
                            sub_list_txt.push($(this).text());
                        })
                        _this.val(sub_list_txt);
                        _this.attr('data-id',sub_list);
                        _this.siblings('.attr-value-id').val(sub_list);
                        $('#alert-bg').remove();
                        $('#alert-main').remove();
                    });
                }
            },"json")

        });


        $("#level0").change(function(){
            var cateId = $(this).val();
            $(".cate-attr").remove();
            if(cateId){
                $.get("/user.org.getNodeCate."+cateId,{ },function(item){
                    $("#level1").html("");
                    if(item.data){
                        var data = item.data;
                        $("#level1").append("<option  value='0'><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>");
                        for(var i in data){
                            var s="";
                            if(data[i].pk_cate == second_cate){
                                s=" selected ";
                            }
                            $("#level1").append("<option "+s+" value='"+data[i].pk_cate+"'>"+data[i].name_display+"</option>");
                            //alert($('#level1').html());
                        }
                        $("#level1").show().trigger("change");
                    }else{
                        $("#level1").hide();
                    }
                },"json");
                $('#level2').hide();
            }else{
                $('#level1').empty();
                $("#level1").append("<option  value='0'><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>");
                $('#level2').empty();
                $("#level2").append("<option  value='0'><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>");
                $('#level2').hide();
                $('#level1').hide();
            }
        }).trigger("change");


        $("#level1").change(function(){
            var cateId = $(this).val();
            $(".cate-attr").remove();
            $('#level2').empty();
            if(cateId){
                $.get("/user.org.getNodeCate."+cateId,{ },function(item){
                    if(item.data){
                        var data = item.data;
                        $("#level2").append("<option value='0'><?php echo tpl_modifier_tr('请选择','site.course'); ?></option>");
                        for(var i in data){
                            var s="";
                            if(data[i].pk_cate == third_cate){
                                s=" selected ";
                            }
                            $("#level2").append("<option "+s+" value='"+data[i].pk_cate+"'>"+data[i].name_display+"</option>");
                            }
                        $("#level2").show().trigger("change");
                    }else{
                        $("#level2").hide();
                    }
                },"json").fail(function(){
                $("#level2").hide();
                });
            }
        });

        $("#level2").change(function(){
            var cateId = $(this).val();
            $(".cate-attr").remove();
            if(cateId){
                $(".cate-attr").remove();
                $.get("/user.org.getAttrAndValueByCateId."+cateId,{ },function(item){
                    if(item.data){
                        var data = item.data;
                        for(var i in data){
                            var attrCon = '';
                            var value_names = '';
                            var attr_value_ids = '';
                            for(var j in course_attr){
                                if(course_attr[j].attr_id == data[i].attr_id){
                                    value_names = course_attr[j].value_name;
                                    attr_value_ids = course_attr[j].attr_value_id;
                                    break;
                                }
                            }
                            attrCon += '<li class="cate-attr"><div class="label col-xs-5">'+data[i].name_display+':</div>'+
                                       '<div class="label-for col-xs-15"><input type="hidden" data-name= "'+data[i].name_display+'" name="attr_id[]" class="attr-id" value="'+data[i].attr_id+'" >'+
                                       '<input type="hidden" name="attr_value_id[]" class="attr-value-id" value="'+attr_value_ids+'" >'+
                                       '<input readOnly="true" data-attr="'+data[i].attr_id+'"  data-attr-name="'+data[i].name_display+'" type="text" name="attr_value_name[]" placeholder="'+data[i].name_display+'" class="subject-list-txt col-xs-10" data-id="'+attr_value_ids+'"  value="'+value_names+'"></div>'+
                                       '</li>';

                            $(".course-cate").after(attrCon);
                        }
                    }
                },"json")
            }
        });


        var che = $('input:radio[name="fee_type"]:checked');
        var isMemberChecked = $('input:radio[name="is_member"]:checked');

        if(che.val()!=0){
            $("#price").show();
            $('.member-course-isChecked').show();
            if(isMemberChecked.val() == 1){
                $('.member-course-category').show();
            }
        }else{
            $("#price").hide();
            $('.member-course-isChecked').hide();
            if(isMemberChecked.val() != 1){
                $('.member-course-category').hide();
            }
        }
        $("input[name='fee_type']").click(function(){
            var che = $('input:radio[name="fee_type"]:checked')
            if(che.val()!=0){
                $("#price").show(30);
                $('.member-course-isChecked').show(30);
            }else{
                $("#price").hide(30);
                $('.member-course-isChecked').hide(30);
            }

        })
        $('input:radio[name="is_member"]').click(function(){
            var isMemberChecked = $('input:radio[name="is_member"]:checked');
            if(isMemberChecked.val() != 0){
                $('.member-course-category').show(30);
            }else{
                $('.member-course-category').hide(30);
            }
        });
        $('.img').click(function(){
            var el = $("#add-pic-box");
            $('#bg').show();
            $('#add-pic-box').show();
        })
        $('.close,#bg').click(function(){
            $('#bg').hide();
            $('#add-pic-box').hide();
        })
        $('#subpic').click(function() {
            $('#add-pic-box').hide();
            $('#bg').hide();
            $("#img_p").hide();
            $("#img_o").show();
        })
    //添加标签
    $(".org-add-subject-btn").click(function() {
        layer.open({
            type: 1,
            title: ['添加标签', 'background:#ffa918;color:#ffffff'],
            closeBtn: 1,
            area: ['438px','290px'],
            shadeClose: true,
            content:$('#add-label')
        });
    })

    $("#add-label .add-label-title").click(function() {
        $(this).hide();
        $("#add-label .label-input-box").show();
    })
    $("#add-label .create-course-box").html("");
    $("#add-label .confirm-btn").click(function() {
        var createCourseVal = $("#add-label .createvale").val();

        if(createCourseVal == '') {
            layer.msg("内容不能为空");
        }else {
            var liVal = '<li class="clearfix">'+'<div class="c-fl">'+'<input type="checkbox" />'+createCourseVal+'</div>'+'<span class="comments-del create-del-btn c-fr">'+'</span>'+'<a href="javascript:;" class="request-cancle-btn c-fr">取消</a>'+'<a href="javascript:;" class="request-delt-btn c-fr mr10">确认删除</a>'+'</li>'
            $("#add-label .create-course-box").append(liVal);
            $("#add-label .createvale").val("");
            $("#add-label .add-label-ft").hide();
            $("#add-label .tec").hide();
            $("#add-label .create-add-label").show();
            $("#add-label .wrap-confirm-btn").show();
        }
    })

    $("#add-label .add-label-val").click(function() {
        $(this).parent(".create-add-label").hide();
        $("#add-label .tec").show();
        $("#add-label .add-label-title").hide();
        $("#add-label .wrap-confirm-btn").hide();
        $(".tec p").show();
        $("#add-label .tec").css("borderBottom","0");

    })

    $("#add-label .cancle-btn").click(function() {
        $(this).parents(".tec").hide();
        $("#add-label .create-add-label").show();
        $("#add-label .wrap-confirm-btn").show();
        $("#add-label .createvale").val("");
    })

    $("#add-label").on("click" ,".create-del-btn" ,function() {
        $(this).hide();
        $(this).parent("li").find(".request-delt-btn").show();
        $(this).parent("li").find(".request-cancle-btn").show();
    })

    $("#add-label").on("click" ,".request-delt-btn" ,function() {
        $(this).parent("li").remove();
    })

    $("#add-label").on("click" ,".request-cancle-btn" ,function() {
        $(this).hide();
        $(this).parent("li").find(".request-delt-btn").hide();
        $(this).parent("li").find(".create-del-btn").show();
    })

    $(".wrap-confirm-btn").click(function() {
        layer.closeAll();
    })



});
</script>
</html>
