<!DOCTYPE html>
<html>
<head>
<title>我推广的课程 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
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
                    <?php echo tpl_function_part("/course.promote.navmodule/promotelist"); ?>

                    <!--推广列表-->
                    <div class="order-list-num">
			            <span class="left fs14 cGray c-fl">共 <span id="promote_course_count"><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['count']; ?></span> 个课程</span>
                        <div class="c-fr">
                            <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["platform"]; ?>/index.help.extension" class="c-fl mark-ask mt5 mr10">推广规则</a>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['count'] < SlightPHP\Tpl::$_tpl_vars["pm"]['max_count']){; ?>
                            <a href="javascript:;" class="c-fr add-button fs14 setDistriCourseBtn">
                                <i class="add-icon c-fl"></i>推广课程
                            </a>
                            <?php }; ?>
                        </div>
					</div>
                    <dl class="order-list mt5" id="order-list">
                        <dt class="fs14">
                            <p class="col-lg-12">课程信息</p>
                            <p class="col-lg-4">成本价</p>
                            <p class="col-lg-4">推广状态</p>
                        </dt>
                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["courseList"])){; ?>
                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["courseList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_id > 0){; ?>
                        <dd class="fs14 <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status<-1){; ?>invalid<?php }; ?>">
                            <div class="col-lg-12 order-item-pro">
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" class="col-lg-8 order-item-img" target="_blank">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->course_type==2){; ?><span class="g-icon3">录播</span><?php }; ?>
                                    <img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["v"]->thumb_small); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?>">
				</a>
                                <div class="col-lg-12 order-item-title">
                                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->subdomain; ?>/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" class="c-fl" target="_blank">
                                    <span class="col-sm-20 pd0"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?></span>
                                    <span class="cGray col-sm-20 pd0 mt5">
                                        <?php /*<?php echo tpl_modifier_tr('共','course.list'); ?><?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->sectionNum; ?><?php echo tpl_modifier_tr('章节','course.list'); ?>*/?>
                                        <?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->second_cate_name; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->third_cate_name; ?>/
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->attr_value)){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attrValues"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]->attr_value,'course.list'); ?><?php }; ?>
                                    </span>
                                    </span>
                                    <span class="cGray col-sm-20 pd0 mt5">
                                        <span class="cYellow1">
                                            <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price==0 && SlightPHP\Tpl::$_tpl_vars["v"]->fee_type==0){; ?>
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
                                    <a class="cDarkgray col-sm-20 pd0 mt25 orgNums" data-course-id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>">已被<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["promoteResellCountArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["promoteResellCountArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]; ?><?php }else{; ?>0<?php }; ?>个机构引入</a>
                                </div>
                            </div>
                            <p class="col-lg-4 tec mt20">
                                <em class="cDarkgray showProPrice">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote==0 && !empty(SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"])&& SlightPHP\Tpl::$_tpl_vars["courseFeeTypeArr"][SlightPHP\Tpl::$_tpl_vars["v"]->course_id]==0){; ?>
                                        <span class="cGreen">免费</span>
                                    <?php }else{; ?>
                                        ￥<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100,2); ?><?php }; ?>
                                    <?php }; ?>
                                </em>
                            </p>
                            <p class="col-lg-4 tec st_info mt20">
                                <em <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status_info<>'正常'){; ?>class="colored distriStatus"<?php }; ?> title="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->status_tip; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->status_info; ?></em>
                            </p>
                            <p class="col-lg-20 order-item-thumb fs12">
				                    <span class="col-lg-5 pd0">
                                    <!--<a href="/course.resell.GetCourseResellLog?cId=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" target="_blank" >共成交订单 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->order_count; ?></span> 个</a>-->
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count>0){; ?>
                                    <a href="/course.resell.GetCoursePromoteLog?cId=<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>">已报名 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count; ?></span> 个</a>
                                    <?php }else{; ?>
                                    已报名 <span class="cYellow"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->enroll_count; ?></span> 个
                                    <?php }; ?>
                                </span>
                                <span class="col-lg-5">预期总收益：<?php if(SlightPHP\Tpl::$_tpl_vars["v"]->income%100==0){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->income/100; ?><?php }else{; ?><?php echo number_format(SlightPHP\Tpl::$_tpl_vars["v"]->income/100,2); ?><?php }; ?></span>
				  <span class="right" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->course_id; ?>" ctitle="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->title; ?>" oprice="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price/100; ?>" oldPrice="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->price_promote/100; ?>">
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==1){; ?>
                                    <a href="javascript:;" class="cGray distriListCloseBtn" act="orgStopPromote">暂停推广</a>
                                    <?php }elseif((SlightPHP\Tpl::$_tpl_vars["v"]->status==0 or SlightPHP\Tpl::$_tpl_vars["v"]->status==-2)){; ?>
                                    <a href="javascript:;" class="cGray distriListStartBtn" act="orgStartPromote">重新推广</a>
                                    <?php }; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->status==1 and SlightPHP\Tpl::$_tpl_vars["v"]->price > 0){; ?>
                                    <a href="javascript:;" class="ml10 distriListPriceBtn" act="updatePromotePrice">改价</a>
                                    <?php }; ?>
                                    <a href="javascript:;" class="ml10 distriListDelBtn"  act="orgDelPromote">删除</a>
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
                        <p>您还没有推广课程哦~快去添加推广课程吧！</p>
                    </div>
                    <!-- /无内容 -->
                        <?php }; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--直播列表 end-->
<section id="setDistriCourse" style="display: none;">
    <ul class="col-xs-20 clearfix">
        <li class="mt20 col-xs-20 pd0">
            <span class="col-xs-5 fs14 mt5 pd0 ter">选择课程：</span>
            <input class="inputpd bor1px col-xs-12" id="selectCourse">
            <input type="hidden" value="" name="siteid" id="siteid" />
            <input type="hidden" value="" name="price" id="price" />
            <span class="btn-clear" id="clearContentBtn"></span>
        </li>
        <li id="courseList">
            <span class="col-xs-5 fs14 mt5 pd0 ter"></span>
            <ul class="last fs14" style="display:none;"></ul>
        </li>
        <div id="chargeState" style="display: none;">
            <li class="mt20 col-xs-20 pd0">
                <span class="col-xs-5 fs14 mt5 pd0 ter">成本价：</span>
                <input class="inputpd bor1px col-xs-12" id="costPrice">
            </li>
            <li class="mt10 col-xs-20 pd0">
                <div class="col-xs-5 pd0"></div>
                <div class="cl-xs-15 cLightgray" id="errInfo">（不能高于课程原价￥<span id="originalPrice"></span>）</div>
            </li>
        </div>
        <div id="freeState" style="display: none;">
            <li class="mt20 col-xs-20 pd0">
                <div class="cl-xs-15 cGray cGrayTip">该课程为免费课程，推广优质的免费课能为机构带来品牌效应和生源</div>
            </li>
        </div>
        <li class="mt20 col-xs-20 pd0 tec">
            <button class="btn mr10 setDistriRequestBtn">确认</button>
            <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['promoteCount']<=10){; ?>
            <span class="cLightgray">还可以推广<em class="colored"><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['promoteCount']; ?></em>个课程</span>
            <?php }; ?>
        </li>
    </ul>
</section>

<!-- 修改价格 -->
<section id="distriListPrice" style="display:none;">
    <ul class="col-xs-20 clearfix">
        <li class="mt20 col-xs-20 pd0" id='li_pro_price'>
            <span class="col-xs-7 fs14 mt5 pd0 ter">设置新的成本价：</span>
            <input class="inputpd bor1px col-xs-11" id="pro_price"/> <!--成本价-->
        </li>
        <li class="mt20 col-xs-20 pd0">
            <div class="col-xs-7 pd0"></div>
            <div class="cl-xs-13 cLightgray" id="pro_ErrInfo">（不能高于课程原价￥<span id="pro_oriPrice"></span>）</div>
            <input type="hidden" value="" name="pro_cid" id="pro_cid" />
            <input type="hidden" value="" name="pro_oldPrice" id="pro_oldPrice" />
        </li>
        <li class="mt20 col-xs-20 pd0 tac">
            <button class="btn mr10 setDistriProPriceBtn">确认</button>
        </li>
    </ul>
</section>
<!-- /修改价格 -->
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript">
    var  errInfo1 = $("#errInfo").html();
$(function() {

    $('.btn-clear').css('right', '58px');

//推广课程
    $('.setDistriCourseBtn').click(function() {
        layer.open({
            type: 1,
            title:['设置课程'],
            area: ['400px' ,'260px'],
            shadeClose: true,
            content: $('#setDistriCourse')
        });
    })

    $('.setDistriRequestBtn').click(function() {
        var courseId =$("#siteid").val();
        var costPrice =parseInt($("#costPrice").val()*100);
        var price =parseInt($("#price").val()*100);
        if(price>0) {
            if ((costPrice > price) || (costPrice < 0)) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").html(errInfo1);
                return false;
            }
            if (isNaN(costPrice)) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").text("价格只能输入数字");
                return false;
            } else {
                $("#errInfo").css('color', '');
                $("#errInfo").html(errInfo1);
            }
        }
        $.post("/course.promote.addPromoteCourse",{ courseId:courseId,costPrice:costPrice },function(r){
            if(r.result['code']==0){
                layer.closeAll();
                layer.msg('推广成功', { icon: 1 });
                $("#costPrice").val('');
                $("#price").val('');
                $("#siteid").val('');
                $("#selectCourse").val('');
                $("#chargeState").hide();
                location.reload(true);
            }
            if(r.result['code']==-4){
                layer.msg(r.result['msg'], { icon: 1 });
            }
            if(r.result['code']==-2){
                layer.msg(r.result['msg'], { icon: 1 });
            }

        },'json');
    })

    $("#selectCourse").keyup(function(){
        var optionVal = $(this).val();
        var _html='';
        $('#clearContentBtn').show();
        $.post("/course.promote.addPromoteSearch",{ optionVal:optionVal },function(r){
            if(jQuery.isEmptyObject(r.data)==true){
                _html='<li class="li1" s='+r.code+' onclick="tabName.call($(this))">'+r.error+'</li>';
                $("#courseList").find('ul').show();
                $("#courseList").find('ul').html(_html);
            }else{
                var errorInfo = '';
                $(r.data).each(function(i,item){
                    _html += '<li price='+item.price+' siteid=' + item.course_id + ' title="' + item.title + '" s=' + r.code + ' onclick="tabName.call($(this))" imgUrl=' + item.thumb_med + '><span>' + item.title + '</span></li>';
                });
                $("#courseList").find("ul").show();
                $("#courseList").find("ul").html(_html);
            }
        },'json');
    });
    $("#costPrice").keyup(function(){
        var costPrice = parseInt($(this).val());
        var price = parseInt($("input[name=price]").val());
        if(price>0) {
            if (costPrice > price) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").html(errInfo1);
            } else {
                $("#errInfo").css('color', '');
                $("#errInfo").html(errInfo1);
            }
            if (isNaN(costPrice)) {
                $("#errInfo").css('color', 'red');
                $("#errInfo").text("价格只能输入数字");
                return false;
            } else {
                $("#errInfo").css('color', '');
                $("#errInfo").html(errInfo1);
            }
        }

    })

//修改成本价 distriListPriceBtn， 重新推广 distriListStartBtn
    $('.distriListPriceBtn , .distriListStartBtn').click(function() {
        var cid = $(this).parent('span').attr("cid");
        var oldPrice = $(this).parent('span').attr("oldPrice"); // 成本价
        var oprice = $(this).parent('span').attr("oprice");     // 原价
        $('#pro_oldPrice').val(oldPrice);
        $('#pro_oriPrice').html(oprice);
        errInfo=$("#pro_ErrInfo").html();
        $('#pro_cid').val(cid);
        if (oprice==0){
            $('#pro_price').val(0);
            $('#li_pro_price').hide();
            $('#pro_ErrInfo').html('该课程为免费课程，推广优质的免费课能为机构带来品牌效应和生源');
        }
        var act = $(this).attr("act");
        if (act=='orgStartPromote'){
            var title = '重新推广';
        } else if (act=='updatePromotePrice'){
            var title = '修改价格';
        }
        $('.setDistriProPriceBtn').attr('act',act);
        layer.open({
            type: 1,
            title:[title,'color:#fff;background:#ffa81d'],
            area: ['400px' ,'260px'],
            shadeClose: true,
            content: $('#distriListPrice')
        });
    });
    var  errInfo=$("#pro_ErrInfo").html();
    $("#pro_price").keyup(function(){
        var proPrice = parseFloat($("#pro_price").val()); // 新成本价
        var proOriPrice = parseFloat($("#pro_oriPrice").html()); // 原价
        if((proPrice>proOriPrice) || (proPrice<0)){
            $("#pro_ErrInfo").css('color','red');
            $("#pro_ErrInfo").html(errInfo);
            return false;
        }
        if(isNaN(proPrice)){
            $("#pro_ErrInfo").css('color','red');
            $("#pro_ErrInfo").text("价格只能输入数字");
            return false;
        }else{
            $("#pro_ErrInfo").css('color','');
            $("#pro_ErrInfo").html(errInfo);
        }
    });
    $('.setDistriProPriceBtn').click(function() {
        var proCid = $("#pro_cid").val();
        var proOldPrice = parseFloat($("#pro_oldPrice").val()); // 原成本价
        var proPrice = parseFloat($("#pro_price").val()); // 新成本价
        var proOriPrice = parseFloat($("#pro_oriPrice").html()); // 原价

        if((proPrice>proOriPrice) || (proPrice<0)){
            $("#pro_ErrInfo").css('color','red');
            return false;
        }
        if(isNaN(proPrice)){
            $("#pro_ErrInfo").css('color','red');
            $("#pro_ErrInfo").text("价格只能输入数字");
            return false;
        }else if(!($('#li_pro_price').is(":hidden"))){
            $("#pro_ErrInfo").css('color','');
            $("#pro_ErrInfo").html(errInfo);
        }
        var act = $(this).attr("act");
        $.post("/course.promote.EditPromoteCourseAjax/"+act,{ cid:proCid,price:proPrice,oprice:proOriPrice },function(ret){
            if (ret.code==0){
                $('span[cid='+proCid+']').parents('dd').find('.showProPrice').html('￥'+proPrice);
                layer.closeAll();
                layer.msg('操作成功', { icon: 1 });
                window.location.reload();//刷新当前页面
            } else {
                layer.closeAll();
                layer.msg('操作失败', { icon: 1 });
            }
        },"json");
    })

//删除推广 distriListDelBtn,暂停推广 distriListCloseBtn，重新推广 distriListStartBtn
    $('.distriListDelBtn,.distriListCloseBtn').click(function() {
        var distritElement = $(this).parents('dd');
        var thisBtn = $(this);
        var cid = $(this).parent('span').attr("cid");
        var ctitle = $(this).parent('span').attr("ctitle");
        var act = $(this).attr("act");
        var url = '/course.promote.changePromoteCourseAjax/';
        if (act=='orgDelPromote'){
            var title = '删除';
            var cstr = '删除后将不能恢复，确定删除？';
        } else if (act=='orgStopPromote'){
            var title = '暂停推广';
            var cstr = '<p class="tec mt20">该课程目前所有机构的引入将会消失，不能给</p><p class="tec">您带来更多购买订单，是否确认停止？</p>';
        } else if (act=='orgStartPromote'){
            var title = '重新推广';
            var cstr = '确定重新推广该课程？';
        }
        url += act;
        layer.confirm('<p class="tec mt40">'+cstr+'</p>', {
          btn: ['确定','取消'],
          title:[title]
        }, function(){
                $.post(url,{ cid:cid,ctitle:ctitle},function(ret){
                    if (ret.code==0){
                        if (act=='orgDelPromote'){ // 删除推广
                            distritElement.remove();
                            $('#promote_course_count').html(parseInt($('#promote_course_count').html()-1));
                        } else if (act=='orgStopPromote'){
                            distritElement.children('.st_info').html('<em class="colored distriStatus">已停止</em>');
                            //thisBtn.hide();
                        } else if (act=='orgStartPromote'){
                            distritElement.children('.st_info').html('<em>正常</em>');
                            //thisBtn.hide();
                        }
                        window.location.reload();
                        layer.msg(title+'成功', { icon: 1 });
                    } else {
                        layer.msg(title+'失败', { icon: 1 });
                    }
                },"json");
        }, function(){
            //layer.msg('已取消', { icon: 1 } );
        });
    })

    $('#clearContentBtn').click(function() {
        $(this).hide();
        $('#selectCourse').val('');
        $('#siteid').val('');
        $('#price').val('');
        $("#chargeState").hide();
        $("#freeState").hide();
    })

    //推广机构列表
    $('#order-list').on('click','.orgNums',function() {
        var courseId=$(this).attr('data-course-id');
        layer.open({
                type:2,
                title:['引入机构'],
                area: ['450px' ,'260px'],
                shadeClose: true,
                content: ['/course.promote.OrgList#'+courseId, 'yes']
        });
    })

});
function tabName(){
    var _this  = $(this);
    var _val   = $(this).find('span').html();
    if(_this.attr('s') == 1){
        var siteid = $(this).attr('siteid');
        var price = $(this).attr('price')/100;
            $('#selectCourse').attr('dataPrice', price);

        if(price>0){
            $("#chargeState").show();
            $("#freeState").hide()
        }else{
            $("#chargeState").hide();
            $("#freeState").show();
        }
        $(this).closest("section").find("#selectCourse").val(_val);
        $(this).closest("section").find("#originalPrice").text(price);
        $(this).closest("section").find("input[name=price]").val(price);
        $(this).closest("section").find("input[name=siteid]").val(siteid);
        errInfo1 = $("#errInfo").html();
        $(this).closest("ul").hide();
    }
    $(this).closest("ul").hide();
    return false;
}
</script>
