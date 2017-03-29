<!DOCTYPE html>
<html>
<head>
<title>推广中心 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 订单管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
    <div class="container">
        <div class="row">
        <!-- lt -->
        <?php echo tpl_function_part("/org.main.menu.promote"); ?>
        <!-- /lt -->
        <!-- rt -->
        <div class="right-main col-md-16 pos-rel">
            <div class="content">
                <?php echo tpl_function_part("/course.promote.navmodule/promotecenter"); ?>
                <p>
                    <!--<span class="left fs12 cGray c-fl">共 <span id="promote_course_count"><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['count']; ?></span> 个课程</span>-->
                </p>
                <?php echo tpl_function_part("/course.promote.filtermodule"); ?>
                <dl class="order-list">
                    <dt class="tec fs14">
                        <p class="col-md-8">课程信息</p>
                        <p class="col-md-10">成本价</p>
                    </dt>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <!-- 推广课程i start -->
                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_id > 0 and SlightPHP\Tpl::$_tpl_vars["v"]->remain_user > 0){; ?>
                    <dd class="fs14">
                        <div class="col-md-11 order-item-pro">
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" class="col-lg-8 order-item-img" target="_blank">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==2){; ?><span class="g-icon3">录播</span><?php }; ?>
                                <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_sma); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?>">
                            </a>
                            <div class="col-lg-12 order-item-title">
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" class="c-fl">
                                <span class="col-sm-20 pd0 cBlack"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></span>
                                <div class="cGray col-sm-20 pd0 mt5">
                                    <?php /*<?php echo tpl_modifier_tr('共','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->sectionNum; ?><?php echo tpl_modifier_tr('章节','course.list'); ?>*/?>
                                    <span class="ml10"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->second_cate_name; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->third_cate_name; ?>/
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id],'course.list'); ?><?php }; ?>
                                    </span>
                                </div>
                                <div style="float: left;width: 100%;margin-top: 5px;">
                                    <span class="cYellow1 mr10 mt5">
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price==0 && SlightPHP\Tpl::$_tpl_vars["v"]->fee_type==0){; ?>
                                            <span class="cGreen">免费</span>
                                        <?php }else{; ?>
                                            ￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price/100,2); ?><?php }; ?>
                                        <?php }; ?>
                                    </span>
                                    <!--<span class="cGray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_type_str; ?></span>-->
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==1){; ?>
                                        <span class="cGray">剩余人数：</span>
										<span class="cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->remain_user; ?></span>
                                    <?php }; ?>
                                </div>
                                </a>
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->subdomain; ?>" class="mt20 c-fl">来源：<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->org_subname; ?></a>
                            </div>
                        </div>
                        <p class="col-md-4 tec fs16 cYellow mt30">
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote==0 && SlightPHP\Tpl::$_tpl_vars["v"]->fee_type==0){; ?>
                                <span class="cGreen">免费</span>
                            <?php }else{; ?>
                                ￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100,2); ?><?php }; ?>
                            <?php }; ?>
                        </p>
                        <p class="col-md-5 tec">
                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->user_id <> SlightPHP\Tpl::$_tpl_vars["orgInfo"]->user_owner_id ){; ?>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["userCourseRelations"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]==-1){; ?>
                                <button class="btn lh22 mt30 distriBtn resell" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" act="orgStartResell">立即引入</button>
                                <?php }elseif( (SlightPHP\Tpl::$_tpl_vars["userCourseRelations"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])){; ?>
                                <button class="mt30 gray-button" disabled="disabled" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>">已引入</button>
                                <?php }else{; ?>
                                <button class="btn lh22 mt30 distriBtn" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>">立即引入</button>
                                <?php }; ?>
                            <?php }; ?>
                        </p>
                    </dd>
                    <?php }; ?>
                    <!-- 推广课程i end -->
                    <?php }; ?>
                    <?php }else{; ?>
                    <!-- 没有内容 -->
                    <dd class="nobor">
                        <div class="col-xs-20 fs14 tac" style="padding-top:60px;display:block;">
                            <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>">
                            <p>暂时还没有机构推广的课程哦~</p>
                        </div>
                    </dd>
                    <!-- /没有内容 -->
                    <?php }; ?>
                    <div class="clear"></div>

                </dl>
                <!-- page -->

                    <div class="page-list" id="pagepage"></div>
                    <script>
                            page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
                    </script>
                <!-- /page -->
            </div>
            <!-- 引导弹窗 -->
            <div class="setDistriBoxBg" style="display: none"></div>
            <div class="setDistriBox pos-abs" style="display: none">
                <a href="javascript:;" title="" class="setDistriBox-urlBtn" id="setDistriBox-urlBtn"></a>
                <a href="javascript:;" title="" id="setDistriBox-closeBtn"></a>
            </div>
            <!-- /引导弹窗 -->
        </div>
        <!-- /rt -->
        </div>
    </div>
</section>
<!-- 引入信息 -->
<section id="distriInfo" class="pt40 pl35 ml" style="display:none;">
    <div class="bgf fs14 col-xs-20">
        <p class="lhh36">课程信息：小学六年级加个公开课中心</p>
        <p class="lhh36">价格：<span class="cYellow">1200元</span></p>
        <p class="lhh36">课程来源：场均学校</p>
        <p class="lhh36">
            成本价：
            <span class="cYellow">800元</span>
            <span class="cGreen">免费</span>
        </p>
        <p>
            <span class="c-fl lh22 mt5">请输入出售价格：</span>
            <input class="bor1px col-xs-5 inputpd" type="text" style="border:1px solid #ddd;padding: 4px 6px;" /> &nbsp;元
            <span class="cLightgray lh22">请输入800-1200之间的价格</span>
        </p>
    <!--
        <p class="cGreen">该课程为免费课程，引入后可为学员带来免费的学习资源</p>
    -->
        <p class="mt30 tac">
            <button class="btn distriRequestBtn">确定引入</button>
            <span class="cLightgray">还可以引入<em class="colored">10</em>个课程</span>
        </p>
    </div>
</section>
<!-- /引入信息 -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
$(function() {
    //$('.setDistriBoxBg').hide();
    //$('.setDistriBox').hide();
//立即引入
    $('.distriBtn').click(function() {
        var courseId = $(this).attr('courseId');
        var _html = '';
        $.post("/course.promote.getPromoteCourseAjax",{ courseId:courseId},function(r){
            _html += '<div class="bgf fs14 col-xs-20 cDarkgray">';
            _html += '<p class="lhh36">课程名称：'+r.data['title']+'</p>';

            _html += '<p class="lhh36">课程来源：'+r.data['org_subname']+'</p>';
            if(r.data['price_promote']>0 || r.data['fee_type']==1) {
                _html += '<p class="lhh36">课程原价：<span class="cYellow">'+r.data['price']/100+'元</span></p>';
                _html += '<p class="lhh36">成本价： ';
                _html += '<span class="cYellow">'+r.data['price_promote']/100+'元</span>';
                _html += '</p>';
                _html += '<p>';
                _html += '<span class="c-fl lh22 mt5">请输入出售价格：</span>';
                _html += '<input class="bor1px col-xs-5 inputpd" style="border:1px solid #ddd;padding: 4px 6px;" id="salePrice" type="text"  price_promote="'+r.data['price_promote']/100+'"  price="'+r.data['price']/100+'"  fee_type="'+r.data['fee_type']+'"/> &nbsp;元';
                _html += '</p>';
                _html += '<p class="lh36 tel">';
                _html += '<span class="cLightgray lh22" id="errInfo">请输入不小于'+r.data['price_promote']/100+'的价格</span>';
                _html += '</p>';
            }else {
                _html += '<p class="lhh36">价格： ';
                _html += '免费';
                _html += '</p>';
                _html += '<p class="fcg9">该课程为免费课程，引入后可为学员带来免费的学习资源</p>';
            }
            _html += '<p class="mt10 tec">';
            _html += '<button class="btn distriRequestBtn" courseId="'+ r.data["course_id"]+'">确定引入</button>';
            if(r.count<=10) {
                _html += '<span class="cLightgray">还可以引入<em class="colored">' + r.count + '</em>个课程</span>';
            }
            _html += '</p>';
            _html += '</div>';
            $('#distriInfo').html(_html);
            $(this).attr('disabled' ,true);
            layer.open({
                type: 1,
                title:['确认引入信息'],
                area: ['520px' ,'360px'],
                shadeClose: true,
                content: $('#distriInfo')
            });
            add();
        },'json');
        /*$(this).removeClass('btn').addClass('gray-btn');
        $(this).html('已引入');
*/

    });

    $('.distriRequestBtn').click(function() {
        layer.closeAll();
    });

    $('.distri-list-scrool .priceTxt').click(function() {
        $(this).find('input').focus();
        $(this).css('border' ,'1px solid #ffb949');
    });

    $('.distri-list-scrool .priceTxt').mouseout(function() {
        $(this).find('input').blur();
        $(this).css('border' ,'1px solid #ddd');
    });

    $('#setDistriBox-closeBtn').click(function() {
        $.post( "course.resell.CloseGrowthTips", function(r) {
            $('.setDistriBoxBg').hide();
            $('.setDistriBox').hide();
        });

    });
    $('#setDistriBox-urlBtn').click(function() {
        $.post( "course.resell.CloseGrowthTips", function(r) {
            $('.setDistriBoxBg').hide();
            $('.setDistriBox').hide();
            window.location.href ='/org.main.template'
        });

    });
});
function add(){
    var  errInfo=$("#errInfo").text();
    $("#salePrice").keyup(function(){
        var fee_type = parseInt($(this).attr("fee_type"));
        var price_promote = parseInt($(this).attr("price_promote")*100);
        var price = parseInt($(this).attr("price")*100);
        var salePrice = parseInt($(this).val()*100);
        if (salePrice < 0) {
            $("#errInfo").css('color', 'red');
            $("#errInfo").text('价格不能小于零');
            return false;
        }else if (salePrice < price_promote) {
            $("#errInfo").css('color', 'red');
            $("#errInfo").text(errInfo);
            return false;
        } else if (isNaN(salePrice)) {
            $("#errInfo").css('color', 'red');
            $("#errInfo").text("价格只能输入数字");
            return false;
        } else {
            $("#errInfo").css('color', '');
            $("#errInfo").text('');
        }
    });
    $('.distriRequestBtn').click(function() {
        var courseId = $(this).attr("courseId");
        var salePrice = parseInt($("#salePrice").val()*100);
        var price_promote = parseInt($("#salePrice").attr("price_promote")*100);
        if(price_promote>0) {
            if (isNaN(salePrice)) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").text("价格只能输入数字");
                return false;
            } else if (salePrice <= 0) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").text('价格不能小于零');
                return false;
            } else if (isNaN(price_promote) || salePrice < price_promote) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").text(errInfo);
                return false;
            } else {
                $("#errInfo").css('color', '');
                $("#errInfo").text('');
            }
        }
        $.post("/course.resell.Add",{ courseId:courseId,salePrice:salePrice,op:'orgStartResell'},function(ret){
            if(ret.result['code']==0){
                $(".distriBtn[courseId="+courseId+"]").removeClass('btn').addClass('gray-btn');
                $(".distriBtn[courseId="+courseId+"]").html('已引入');
                $(".distriBtn[courseId="+courseId+"]").attr("disabled","disabled");
                $.ajax({
                    url:'/user/guide/guide',
                    type: 'POST',
                    dataType: 'json',
                    data:{ guide:[11]},
                    success:function(res) {
                        if (res.code == 0 && res.data['11'] == 'true') {
                            $('.setDistriBoxBg').show();
                            $('.setDistriBox').show();
                        }
                    }
                });
                layer.closeAll();
                layer.msg('引入成功');
            } else {
                layer.closeAll();
                layer.msg(ret.result.msg);
            }
        },'json');
    });
}
</script>
