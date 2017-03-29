<!DOCTYPE html>
<html>

<head>
    <title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> -云课 - 专业的在线直播教育学习平台 - 云课网 </title>
    <meta name="title" content="云课 - 专业的在线直播教学习平台">
    <meta name="keywords" content="云课 - 云课堂 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/index.main.header"); ?>
</head>
<style>
    #mceu_4 .mce-i-upload{ background: url("<?php echo utility_cdn::img('/assets_v2/img/upload_icon.png'); ?>") -3px 0px no-repeat; }
</style>

<script src="<?php echo utility_cdn::js('/assets_v2/js/tinymce/tinymce.min.js'); ?>"></script>
<script src="<?php echo utility_cdn::js('/assets_v2/js/tinymce/plugin.js'); ?>"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        language: "zh_CN",
        //paste_as_text: true,
        paste_auto_cleanup_on_paste : true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: true,
        //toolbar: "bold italic strikethrough link unlink numlist bullist blockquote upload image fullscreen styleselect formatselect fontselect fontsizeselect tiny_mce_wiris_formulaEditor tiny_mce_wiris_CAS",
        toolbar: "bold italic link unlink upload tiny_mce_wiris_formulaEditor fontsizeselect",
        width: 800,
        height: 400,
        upload_action: "/teacher/ajax/ArticleImgUpload/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>",//required
        upload_file_name: 'userfile',//required
        plugins: [
            "advlist autolink lists link image charmap  anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste upload",
            "tiny_mce_wiris"
        ]
    });

</script>

<body>
<!--header-->
<?php echo tpl_function_part("/index.main.top"); ?>
<!-- /header -->
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/index/teacherblog/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/article");</script>
<div class="container mt40 mb40">
    <div class="text_box col-md-offset-1" >
        <div class="text_tBut"><a href="/index/teacherblog/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/draft">草稿</a>&nbsp;丨&nbsp;<a href="/index/teacherblog/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>">返回文章列表</a> </div>
        <div class="tit_border text_tit">
            <input type="text" placeholder="请输入文章标题" maxlength="20" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["article"]->title; ?><?php }; ?>"/>
        </div>
        <!--        <div class="tit_border text_zy fs14">
                    <input id="text_zy" type="text" maxlength="100" placeholder="请输入文章摘要" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->summary)){; ?><?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["article"]->summary); ?><?php }; ?>" />
                </div>-->
        <div class="clear"></div>
        <form method="post" action="/teacher/ajax/publish" >
            <textarea name="content"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->content)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["article"]->content; ?><?php }; ?></textarea>
        </form>
        <div class="text_label">
            <div class="fl t_lab">
                <label>标签：</label><input maxlength="8" tagId="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->fk_tag)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["article"]->fk_tag; ?><?php }else{; ?>0<?php }; ?>" type="text" name="textMark" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->tagName)){; ?>value="<?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["article"]->tagName); ?>"<?php }; ?>/>
                <ul class="lab_ul" style="display: none">
                    <?php if(SlightPHP\Tpl::$_tpl_vars["tagList"]){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["tagList"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                    <li>
                        <a href="javascript:;" tagId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>">#<?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["v"]->name); ?>#</a>
                        <!--<em>热门</em>-->
                    </li>
                    <?php }; ?>
                    <?php }; ?>
                </ul>
            </div>
            <div class="fr">
                <input type="checkbox" name="textTop" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->top)){; ?>checked<?php }; ?> style="vertical-align:initial;">&nbsp;<label>置顶</label>
            </div>

        </div>
        <div class="text_bBut">
            <a href="javascript:;" class="publish fl"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->pk_article)){; ?>保存<?php }else{; ?>发表<?php }; ?></a>
            <a href="javascript:;" class="fr draft bBut_r">存草稿</a>
            <a href="/index/teacherblog/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" class="clear_pub fr bBut_r">取消</a>
        </div>
    </div>
</div>
<!-- /content -->
<?php echo tpl_function_part("/index.main.footer"); ?>
<script>

    //发表
    $(".text_label input[name='textMark']").bind({
        focus:function(){
            $(".lab_ul").show();
        },
        keyup:function(){
            $(this).attr('tagId','0');
            $(".lab_ul").hide();
        }
    });
    $(".t_lab ul li").click(function(){
        var li_text=$(this).find('a').text().split("#").join("");
        var siteId=$(this).find('a').attr('tagId');
        $(".text_label input[name='textMark']").val(li_text).attr('tagId',siteId);
        $(".t_lab ul").hide();
    });

    //发表，草稿请求
    $(".text_bBut .publish, .text_bBut .draft").click(function(){
        var content=tinyMCE.activeEditor.getContent();//内容
        var title=$(".text_tit input").val();//标题
        var summary=$(".text_zy input").val();//摘要
        var button=$(this).text();//发表||草稿
        var top=$("input[name='textTop']").is(":checked");//置顶
        var tagId=$("input[name='textMark']").attr('tagId');//标签，未选择热门为0
        var tagName=$("input[name='textMark']").val();//标签内容
        var tid = <?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>;
        var uid = <?php echo SlightPHP\Tpl::$_tpl_vars["uid"]; ?>;
        var articleId = <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["article"]->pk_article)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["article"]->pk_article; ?><?php }else{; ?>0<?php }; ?>

        if (tagId<1 && !tagName) {
            layer.msg('标签不能为空');
            return false;
        }
        if (!content) {
            layer.msg('内容不能为空');
            return false;
        }

        if (uid != tid) {
            layer.msg('不是当前登录用户');
            return false;
        }

        var data={ content:content,button:button,top:top,tagId:tagId,tagName:tagName,tid:tid,title:title,summary:summary,articleId:articleId };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/teacher/ajax/publish/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>",
            data: data,
            success: function(d){
                if (d.code == 0) {
                    layer.msg(d.errMsg,{ icon:1  }, function(){
                        location.href="/index/teacherblog/article/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>";
                    });
                } else {
                    layer.msg(d.errMsg);
                }
            },
            error:function(d){
                layer.msg(d.errMsg)
            }
        });
    })
</script>
</body>

</html>