<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> - 文章资料 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  教师首页 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> -  云课(Yunke.com) - 专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript">var COOKIE_UID_NAME="<?php echo COOKIE_UID_NAME; ?>";</script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
</head>
<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<!-- /header -->
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/teacher/detail/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/article");</script>
<article class="container pd40">
    <!--左侧开始-->
    <div class="col-md-5 mt10 th_m_l">
        <article class="atr_ml">
            <ul class="one_atr_ul">
                <li>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["userType"] && SlightPHP\Tpl::$_tpl_vars["uid"] == SlightPHP\Tpl::$_tpl_vars["tid"]){; ?>
                    <div class="atr_rel tec atr_li atr_liBtn" style="margin-bottom: 10px;"><a href="/teacher/detail/publish/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>"><em class="icon_pic rel_icon"></em>发布文章</a> </div>
                    <?php }; ?>
                    <!--<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->articleList)){; ?>-->
                        <div class="<?php if(SlightPHP\Tpl::$_tpl_vars["tagId"] === 0){; ?>atr_li_on<?php }; ?>  atr_li" style="cursor:default;"><i class="icon_pic icon_on "></i>全部
                            <em><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->tagLists)){; ?>（<?php echo SlightPHP\Tpl::$_tpl_vars["list"]->tagLists->num; ?>）<?php }; ?></em>
                        </div>
                        <ul class="two_atr_ul" >
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->tagLists)){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"]->tagLists->list as SlightPHP\Tpl::$_tpl_vars["tag"]){; ?>
                                    <li  class="length_sl <?php if(SlightPHP\Tpl::$_tpl_vars["tag"]->fk_tag == SlightPHP\Tpl::$_tpl_vars["tagId"] && SlightPHP\Tpl::$_tpl_vars["tag"]->tag_status){; ?>atr_li_on<?php }; ?>" >
                                       <i class="icon_pic icon_on "></i><a href="/teacher/detail/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tag"]->fk_user; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["tag"]->fk_tag; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["tag"]->name; ?>（<?php echo SlightPHP\Tpl::$_tpl_vars["tag"]->total; ?>）</a>
                                    </li>
                                <?php }; ?>
                            <?php }; ?>
                        </ul>
                    <!--<?php }; ?>-->
                </li>

                <li id='draftBox' style="display: none;">
                    <div class="atr_li atr_liBtn <?php if(SlightPHP\Tpl::$_tpl_vars["tagId"] === 'draft'){; ?>atr_li_on<?php }; ?>">
                        <a href="/teacher/detail/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/draft"><i class="icon_pic icon"></i>草稿箱</a>
                    </div>
                </li>
            </ul>
        </article>
    </div>
    <!--右侧开始-->
    <div class="col-md-15  th_m_r">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->articleList)){; ?>
        <ul class="techear-courselist mt10 th_mr_data">
            <!--右侧带图-->
            <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"]->articleList as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
            <li class="col-md-20 col-sm-20 col-xs-20" dataId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_article; ?>">
                <a href="/teacher/detail/ArticleDetail/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_article; ?>">
                        <span class="col-md-16 col-sm-16 col-xs-20">
                            <h3 class="length_sl fs16 mb10"><?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["v"]->title); ?></h3>
                            <p class="fs14 c_a3"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->summary; ?></p>
                            <div class="data_icon mt30">
                                <span class="c_a3 fs14 mr20"><i class="icon_pic zf">&nbsp;</i>&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->share_num; ?></span>
                                <span class="c_a3 fs14 "><i class="icon_pic pl">&nbsp;</i>&nbsp;<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->comment_num; ?></span>
                            </div>
                        </span>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["v"]->thumb){; ?>
                            <span class="col-md-4 col-sm-4 col-xs-20"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->thumb; ?>"></span>
                            <!--<span class="col-md-4 col-sm-4"><img src="<?php echo utility_cdn::img('/assets_v2/img/zl_pic.jpg'); ?>"></span>-->
                        <?php }; ?>
                </a>
                <?php if(SlightPHP\Tpl::$_tpl_vars["userType"]){; ?>
                <div class="clearfix col-md-4 tec_oper editBox" style="display:none">
                    <a href="javascript:;" class="delect" >&nbsp;删除</a>
                    <a href="/teacher/detail/publish/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/edit/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_article; ?>">编辑&nbsp;丨</a>
                </div>
                <?php }; ?>
            </li>
            <?php }; ?>
        </ul>
        <?php }else{; ?>
        <!--没有时状态-->
        <div class="col-md-5 col-md-offset-2 mt20 tec pd45">
            <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
            <p class="mt10 c_a3 fs16"  >还没来得及添加哦 ...</p>
        </div>
        <?php }; ?>
<!--        <div class="page-list" id="pagepage">
            <ul>
                <li class="prev-disabled">上一页</li>
                <li class="active">1</li>
                <li class=""><a href="" title="">2</a></li>
                <li class=""><a href="/" title="">3</a></li>
                <li class=""><a href="" title="">4</a></li>
                <li class="prev"><a href="" title="下一页">下一页</a></li>
            </ul>
        </div>-->
    </div>
</article>
<!-- /content -->
<!-- 内容结束 -->
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
<script>
var uid = userApi.getUid();
//删除询问框
$(".delect").click(function(){
    var dataId=$(this).parents("li").attr("dataId");
    var _this=$(this);
    layer.confirm('你确定要删除这篇文章么？一旦删除将不可恢复。', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/teacher/ajax/delArticle",
            data:{ dataId:dataId, tid:<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?> },
            success: function(d){
                if (d.code == 0) {
                        /*layer.msg(d.errMsg, { icon: 1 }, function () {
                            $(this).parents("li").animate({ height:'toggle' }, 'fast');
                        });*/
                        layer.msg(d.errMsg, { icon: 1 }, function () {
                            _this.parents("li").slideUp("slow");
                        });
                    } else {
                        layer.msg(d.errMsg);
                    }
                },
                error:function(d){
                    layer.msg(d.errMsg)
                }
            });
        });
    });

    $(function(){
        $(".one_atr_ul .atr_liBtn").click(function(){
            var _this=$(this);
            if(_this.siblings().hasClass("two_atr_ul")){
                if(_this.hasClass("atr_li_on")){
                    _this.find("i").removeClass("icon_on").addClass("icon");
                    _this.removeClass("atr_li_on");
                    //_this.siblings(".two_atr_ul").slideToggle("normal");
                }else{
                    _this.find("i").removeClass("icon").addClass("icon_on");
                    _this.addClass("atr_li_on");
                    //_this.siblings(".two_atr_ul").slideToggle("normal")
                }
            };
        })

        if (uid && uid == <?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>) {
            $("#draftBox").show();
            $(".editBox").show();
        }
    })
</script>
</body>

</html>
