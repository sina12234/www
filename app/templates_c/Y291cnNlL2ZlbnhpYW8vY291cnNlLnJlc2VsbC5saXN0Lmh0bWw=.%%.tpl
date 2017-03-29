<!DOCTYPE html>
<html>
<head>
<title>我引入的课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 订单管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/datetimepicker-master/jquery.datetimepicker.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo utility_cdn::css('/assets/libs/datetimepicker-master/jquery.datetimepicker.css'); ?>">
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<style type="text/css">
.layui-layer-btn a.layui-layer-btn0{ background: #ffa81d;}
.layui-layer-btn a.layui-layer-btn0:hover{ background: #ffb949;}
.layui-layer-btn .layui-layer-btn1,.layui-layer-btn .layui-layer-btn1:hover{ background: #e5e5e5;border:1px solid #ddd;}
.inputpd{ border:1px solid #ddd;padding: 4px 6px;}
</style>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<!--直播列表 start-->
<section class="pd30">
    <div class="container">
        <div class="row">
            <?php echo tpl_function_part("/org.main.menu.promote"); ?>
            <div class="col-lg-16 right-main">
                <div class="content">
                    <?php echo tpl_function_part("/course.promote.navmodule/reselllist"); ?>
                    <div class="col-md-20 pd0 mt10">
                        <span class="c-fl fs12 mt5 mr20">推广状态</span>
                        <ul class="c-fl bor1px distriSpread-option clearfix fs12">
                            <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status'] == ''){; ?>class="curr"<?php }; ?>>
                                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st',''); ?>" ><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
                            </li>
                            <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status'] == 'n'){; ?>class="curr"<?php }; ?>>
                                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st','n'); ?>" ><?php echo tpl_modifier_tr('正常','course.list'); ?></a>
                            </li>
                            <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status'] == 'f'){; ?>class="curr"<?php }; ?>>
                                <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'st','f'); ?>" ><?php echo tpl_modifier_tr('已失效','course.list'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <!--推广列表-->
                    <div class="order-list-num">
                        <span class="left fs12 cGray c-fl">共 <span id="resell_course_count"><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['count']; ?></span> 个课程</span>
                        <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["platform"]; ?>/index.help.extension" class="c-fr mt5 mark-ask">推广规则</a>
                    </div>
                    <dl class="order-list mt5">
                        <dt class="fs14">
                            <p class="col-lg-12">课程信息</p>
                            <p class="col-lg-4">推广规则</p>
                            <p class="col-lg-4">推广状态</p>
                        </dt>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_id > 0){; ?>
                        <dd class="fs14">
                            <div class="col-lg-12 order-item-pro">
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_org_resell; ?>" class="col-lg-8 order-item-img" target="_blank">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==2){; ?><span class="g-icon3">录播</span><?php }; ?>
                                    <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_small); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?>">
                                </a>
                                <div class="col-lg-12 order-item-title">
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_org_resell; ?>" class="left" target="_blank">
                                    <span class="col-sm-20 pd0 cBlack"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></span>
                                    <span class="cGray col-sm-20 pd0 mt5">
                                        <?php /*<?php echo tpl_modifier_tr('共','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->sectionNum; ?><?php echo tpl_modifier_tr('章节','course.list'); ?>*/?>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->second_cate_name; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->third_cate_name; ?>/
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->attr_value)){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->attr_value,'course.list'); ?><?php }; ?>
                                    </span>
                                    <span class="cGray col-sm-20 pd0 mt5">
                                        <span class="cYellow1">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price==0 && !empty(SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"])&& SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]==0){; ?>
                                                <span class="cGreen">免费</span>
                                            <?php }else{; ?>
                                                ￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price/100,2); ?><?php }; ?>
                                            <?php }; ?>
                                        </span>
                                        <!--<span class="cLightgray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_type_str; ?></span>-->
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==1){; ?>
                                            <span class="ml10">剩余人数：</span><span class="cDarkgray"><?php if(SlightPHP\Tpl::$_tpl_vars["v"]->remain_user > 0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->remain_user; ?><?php }else{; ?>0<?php }; ?></span>
                                        <?php }; ?>
                                    </span>
                                    </a>
                                    <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->subdomain; ?>" class="cDrakgray col-sm-20 pd0 mt25">来源：<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->org_name; ?></a>
                            </div>
                            </div>
                            <p class="col-lg-4 tec mt20">
                                <em class="cDarkgray">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote==0 && !empty(SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])&& SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]==0){; ?>
                                        <span class="cGreen">免费</span>
                                    <?php }else{; ?>
                                        成本价：￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100,2); ?><?php }; ?>
                                    <?php }; ?>
                                </em>
                                <em class="cDarkgray showResPrice">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote>0){; ?>
                                        出售价：￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_resell%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_resell/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price_resell/100,2); ?><?php }; ?>
                                    <?php }elseif( !empty(SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])&& SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]==1){; ?>
                                    出售价：￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_resell%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_resell/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price_resell/100,2); ?><?php }; ?>
                                    <?php }; ?>
                                </em>
                            </p>
                            <p class="col-lg-4 tec st_info mt20">
                                <em <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status_info<>'正常'){; ?>class="colored distriStatus"<?php }; ?> title="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->status_tip; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->status_info; ?><?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status<-1){; ?><i class="invalid-icon"></i><?php }; ?></em>
                            </p>
                            <p class="col-lg-20 order-item-thumb fs12">
                                <!--<span class="col-lg-5 pd0">共成交订单 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->order_count; ?></span> 个</span>-->
                                <span class="col-lg-5 pd0">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count>0){; ?>
                                    <a href="/course.resell.GetCourseResellLog?cId=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>">已报名 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count; ?></span> 个</a>
                                    <?php }else{; ?>
                                    已报名 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count; ?></span> 个
                                    <?php }; ?>
                                </span>
                                <span class="col-lg-5">预期总收益：<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->income%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->income/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->income/100,2); ?><?php }; ?></span>
                                <span class="right" crid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>-<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_org_resell; ?>" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" oid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_org_resell; ?>" opprice="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100; ?>" oprice="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?>" title='<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?>' orgname='<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->org_name; ?>'>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status > -10){; ?>
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status < -1 and SlightPHP\Tpl::$_tpl_vars["v"]->status > -10){; ?>
                                        <a href="javascript:void(0);" class="distriListReselltBtn ml10" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" >重新引入</a>
                                        <?php }else{; ?>
                                        <!--<a href="javascript:;" class="ml10 distriListStopBtn"  disabled="disabled" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" >暂停引入</a>-->
                                        <?php }; ?>
                                    <?php }; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status >= 1 and SlightPHP\Tpl::$_tpl_vars["v"]->price > 0){; ?>
                                    <a href="javascript:;" class="ml10 distriListPriceBtn" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" >改价</a>
                                    <?php }; ?>
                                    <a href="javascript:;" class="ml10 distriListDeltBtn" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" >删除</a>
                                </span>
                            </p>
                        </dd>
                        <?php }; ?>
                        <?php }; ?>
                    <!--分页开始-->
                        <div class="page-list" id="pagepage"></div>
                        <script>
                                page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path_page"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['size']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['page']; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['total']; ?>);
                        </script>
                        <?php }else{; ?>
                    <!-- 无内容 -->
                    <div class="col-xs-20 fs14 tac" style="padding-top:60px;display:block;">
                        <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>">
                        <p>您还没有引入课程哦~快去推广中心引入吧！</p>
                    </div>
                    <!-- /无内容 -->
                        <?php }; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 修改价格 -->
<section id="distriListPrice" style="display:none;">
    <ul class="col-xs-20 clearfix">
        <li class="mt20 col-xs-20 pd0">
            <span class="col-xs-7 fs14 mt5 pd0 ter">设置新的出售价：</span>
            <input class="inputpd bor1px col-xs-11" id="res_price"/>
        </li>
        <li class="mt20 col-xs-20 pd0">
            <div class="col-xs-7 pd0"></div>
            <div class="cl-xs-13 cLightgray" id="res_ErrInfo">
                请输入￥<span id="res_oriPPrice"></span>-<span id="res_oriPrice"></span>之间的价格
            </div>
            <input type="hidden" value="" name="res_cid" id="res_cid" />
            <input type="hidden" value="" name="res_oid" id="res_oid" />
        </li>
        <li class="mt20 col-xs-20 pd0 tec">
            <button class="btn mr10 setDistriResPriceBtn">确认</button>
        </li>
    </ul>
</section>
<!-- /修改价格 -->


<!-- 引入信息 -->
<section id="distriInfo" style="display:none;">
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
            <input class="bor1px col-xs-5 inputpd" type="text" /> &nbsp;元
            <span class="cLightgray lh22">请输入800-1200之间的价格</span>
        </p>
    <!--
        <p class="cGreen">该课程为免费课程，引入后可为学员带来免费的学习资源</p>
    -->
        <p class="mt30 tec">
            <button class="btn distriRequestBtn">确定引入</button>
            <span class="cLightgray">还可以引入<em class="colored">10</em>个课程</span>
        </p>
    </div>
</section>
<!-- /引入信息 -->
<!--直播列表 end-->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
$(function() {

//修改价格
    $('.distriListPriceBtn').click(function() {
        var cid = $(this).parent('span').attr("cid");
        var oid = $(this).parent('span').attr("oid");
        var opprice = $(this).parent('span').attr("opprice");
        var oprice = $(this).parent('span').attr("oprice");
        $('#res_oriPPrice').html(opprice);
        $('#res_oriPrice').html(oprice);
        $('#res_cid').val(cid);
        $('#res_oid').val(oid);
        errInfo = $("#res_ErrInfo").html();
        layer.open({
            type: 1,
            title:['修改价格','color:#fff;background:#ffa81d'],
            area: ['400px' ,'260px'],
            shadeClose: true,
            content: $('#distriListPrice')
        });
    })
    var errInfo = $("#res_ErrInfo").html();
    $("#res_price").keyup(function(){
        var resPrice = parseFloat($("#res_price").val()); // 新的成本价
        var resOriPrice = parseFloat($("#res_oriPrice").html()); // 原价
        var resOriPPrice = parseFloat($("#res_oriPPrice").html()); // 出售价

        if((resPrice>resOriPrice) || (resPrice<resOriPPrice) || (resPrice<0)){
            $("#res_ErrInfo").css('color','red');
            $("#res_ErrInfo").html(errInfo);
            return false;
        }else{
            $("#res_ErrInfo").css('color','');
            $("#res_ErrInfo").html(errInfo);
        }
        if(isNaN(resPrice)){
            $("#res_ErrInfo").css('color','red');
            $("#res_ErrInfo").text("价格只能输入数字");
            return false;
        }else{
            $("#res_ErrInfo").css('color','');
            $("#res_ErrInfo").html(errInfo);
        }
    });
    $('.setDistriResPriceBtn').click(function() {
        var resCid = $("#res_cid").val();
        var resOid = $("#res_oid").val();
        var resPrice = parseFloat($("#res_price").val()); // 新的成本价
        var resOriPrice = parseFloat($("#res_oriPrice").html()); // 原价
        var resOriPPrice = parseFloat($("#res_oriPPrice").html()); // 出售价

        if((resPrice>resOriPrice) || (resPrice<resOriPPrice) || (resPrice<0)){
            $("#res_ErrInfo").css('color','red');
            $("#res_ErrInfo").html(errInfo);
            return false;
        }
        if(isNaN(resPrice)){
            $("#res_ErrInfo").css('color','red');
            $("#res_ErrInfo").text("价格只能输入数字");
            return false;
        }else{
            $("#res_ErrInfo").css('color','');
            $("#res_ErrInfo").html(errInfo);
        }

        $.post("/course.resell.EditResellCourseAjax",{ cid:resCid,oid:resOid,price:resPrice,op:"updateResellPrice" },function(ret){
            if (ret.code==0){
                $('span[cid='+resCid+']').parents('dd').find('.showResPrice').html('出售价：￥'+resPrice);
                layer.closeAll();
                layer.msg('修改成功', { icon: 1 });
                window.location.reload();
            } else {
                layer.closeAll();
                layer.msg('修改失败', { icon: 1 });
            }
        },"json");
    })

//删除引入
    $('.distriListDeltBtn').click(function() {
        var distritDelElement = $(this).parents('dd');
        var crid = $(this).parent('span').attr("crid");

        layer.confirm('<p class="tec mt20">删除后将不能恢复，确定删除？</p>', {
          btn: ['确定','取消'],
          title:['删除']
        }, function(){
                $.post("/course.resell.changeResellCourseAjax/orgDelResell","crid="+crid,function(ret){
                    if(ret.code==0){
                        distritDelElement.remove();
                        $('#resell_course_count').html(parseInt($('#resell_course_count').html()-1));
                        layer.msg('<?php echo tpl_modifier_tr('删除成功','course.info'); ?>', { icon: 1 });
                    } else {
                        layer.msg('<?php echo tpl_modifier_tr('删除失败','course.info'); ?>', { icon: 1 });
                    }
                },"json");
        }, function(){
                //layer.msg('已取消', { icon: 1 } );
        });
    })

//关闭弹层
    $('.setDistriRequestBtn').click(function() {
        layer.closeAll();
    });

    $('.order-item-thumb').find('a').hover(function() {
        $(this).css('color' ,'#f93');
    } ,function() {
        $(this).css('color' ,'#333');
    });

/* 重新引入 */
    $('.distriListReselltBtn').click(function() {
        var courseId = $(this).attr('courseId');
        var _html = '';
        //$.post("/course.promote.getPromoteCourseAjax",{ courseId:courseId},function(r){
            var title = $(this).parent('span').attr("title");
            var orgname = $(this).parent('span').attr("orgname");
            var cid = $(this).parent('span').attr("cid");
            var oid = $(this).parent('span').attr("oid");
            var opprice = $(this).parent('span').attr("opprice");   // 成本价
            var oprice = $(this).parent('span').attr("oprice");     // 原价
            var count = <?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['resellCount']; ?>; // TODO

            _html += '<div class="bgf fs14 col-xs-20 cDarkgray">';
            _html += '<p class="lhh36">课程信息：'+title+'</p>';

            _html += '<p class="lhh36">课程名称：'+orgname+'</p>';
            if(oprice>0) {
                _html += '<p class="lhh36">课程原价：<span class="cYellow">'+oprice+'元</span></p>';
                _html += '<p class="lhh36">成本价： ';
                _html += '<span class="cYellow">'+opprice+'元</span>';
                _html += '</p>';
                _html += '<p>';
                _html += '<span class="c-fl lh22 mt5">请输入出售价格：</span>';
                _html += '<input class="bor1px col-xs-5 inputpd" id="salePrice" type="text"  price_promote="'+opprice+'"  price="'+oprice+'" /> &nbsp;元';
                _html += '</p>';
                _html += '<p class="lh36 tel">';
                _html += '<span class="cLightgray lh22" id="errInfo">请输入'+opprice+'-'+oprice+'之间的价格</span>';
                _html += '</p>';
            }else {
                _html += '<p class="lhh36">价格： ';
                _html += '免费';
                _html += '</p>';
                _html += '<p class="fcg9">该课程为免费课程，引入后可为学员带来免费的学习资源</p>';
            }
            _html += '<p class="mt10 tec">';
            _html += '<button class="btn distriRequestBtn" courseId="'+ cid+'">确定引入</button>';
            if(count<=10) {
                _html += '<span class="cLightgray">还可以引入<em class="colored">' + count + '</em>个课程</span>';
            }
            _html += '</p>';
            _html += '</div>';
            $('#distriInfo').html(_html);
            //$(this).attr('disabled' ,true);
            layer.open({
                type: 1,
                title:['确认引入信息','color:#fff;background:#ffa81d'],
                area: ['520px' ,'300px'],
                shadeClose: true,
                content: $('#distriInfo')
            });
            add();
        //},'json');
    });
    function add(){
        $("#salePrice").keyup(function(){
            var price_promote = parseInt($(this).attr("price_promote")*100);
            var price = parseInt($(this).attr("price")*100);
            var salePrice = parseInt($(this).val()*100);
            if((salePrice>price) || (salePrice<price_promote)){
                $("#errInfo").css('color','red');
            }else{
                $("#errInfo").css('color','');
            }
        });
        $('.distriRequestBtn').click(function() {
            var courseId = $(this).attr("courseId");
            var price_promote = parseInt($("#salePrice").attr("price_promote")*100);
            var price = parseInt($("#salePrice").attr("price")*100);
            var salePrice = parseInt($("#salePrice").val()*100);
            if((salePrice<0) || (salePrice>price) || (salePrice<price_promote)){
                $("#errInfo").css('color','red');
                return false;
            }
            $.post("/course.resell.Add",{ courseId:courseId,salePrice:salePrice,op:"orgStartResell"},function(ret){
                if(ret.result['code']==0){
                    $(".distriBtn[courseId="+courseId+"]").removeClass('btn').addClass('but14-default');
                    $(".distriBtn[courseId="+courseId+"]").html('已引入');
                    if(salePrice>0){
                        $('span[cid='+courseId+']').parents('dd').find('.showResPrice').html('出售价：￥'+salePrice/100);
                    } else {
                        //$('span[cid='+courseId+']').parents('dd').find('.showResPrice').html('免费');
                    }
                    $('span[cid='+courseId+']').parents('dd').children('.st_info').html('<em>正常</em>');  // 状态恢复正常
                    $('span[cid='+courseId+']').parents('dd').find('.distriListReselltBtn').hide(); // 隐藏重新引入

                    layer.closeAll();
                    layer.msg('引入成功', { icon: 1 });
                    //window.location.reload();
                }
            },'json');
        });
    }
})
</script>
