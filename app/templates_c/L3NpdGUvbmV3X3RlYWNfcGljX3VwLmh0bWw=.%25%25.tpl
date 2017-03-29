<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["info"]->real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["info"]->real_name; ?><?php }; ?> - 教师相册 -云课 - 专业的在线直播教育学习平台 - 云课网 </title>
    <meta name="title" content="云课 - 专业的在线直播教学习平台">
    <meta name="keywords" content="云课 - 云课堂 - Yunke K12 在线学习 直播 云课网 在线教育">
    <meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
    <?php echo tpl_function_part("/index.main.header"); ?>
    
    <link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/libs/jquery-ui-1.11.2.custom/jquery-ui.css'); ?>" type="text/css"/>
    <link rel="stylesheet" href="<?php echo utility_cdn::css('/assets/js/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css"/>

    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery-1.11.1.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets/libs/jquery-ui-1.11.2.custom/jquery-ui.min.js'); ?>"></script>

    <!-- production -->
    <script src="<?php echo utility_cdn::js('/assets/js/plupload/js/plupload.full.min.js'); ?>"></script>
    <script src="<?php echo utility_cdn::js('/assets/js/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js'); ?>"></script>
    <style>
        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default,.ui-widget-content{ background: #fff; }
       #uploader_buttons #uploader_start,.plupload_header_content{ display: none; }
        .plupload_view_thumbs .plupload_content{ top:0; }
    </style>
</head>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<!--header_index-->
<div id="teacherNavHeader"></div>
<script>$("#teacherNavHeader").load("/index/teacherblog/NavHeader/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/style");</script>
<article class="th_pic_new container mt40 mb40">
    <ul class="col-md-20  th_picUp mt20">
        <li class="col-md-4 upPic "><a href="javascript:;"><img style="height: 142px;" src="<?php echo utility_cdn::img('/assets_v2/img/up_pic.jpg'); ?>" alt=""/></a></li>
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"])){; ?>
        <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
        <li name="upImg" class="col-md-4" data-id="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>" imgId="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>" >
            <em  class=" icon_pic"></em>
            <a href="/index/teacherblog/imgInfo/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['imgId']; ?>">
                <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['thumb_med']; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['name']; ?>"/>
            </a>
            <div class="length_sl isEvent" ><span class="length_sl" contenteditable="false"><?php echo htmlentities(SlightPHP\Tpl::$_tpl_vars["v"]['name']); ?></span><a href="javascript:;" class="fr want" style="display: none" contenteditable="false">确定</a> </div>
        </li>
        <?php }; ?>
        <?php }; ?>
    </ul>
    <!--上传弹层-->
    <form id="form" style="display: none;" class="up_pic col-md-12 none th_pic_new" method="post" action="#">
        <a href="javascript:;" class="cloose icon_pic"></a>
        <div id="uploader">
            <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
        </div>
        <br/>
        <input type="submit"class="upValue"  value="保存并上传"/>
    </form>
    <script type="text/javascript">
        // Initialize the widget when the DOM is ready
        $(function () {
            $("#uploader").plupload({
                // General settings
                runtimes: 'html5,flash,silverlight,html4',
                url: "/teacher/ajax/TeacherImgUpload/<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?>",

                // User can upload no more then 20 files in one go (sets multiple_queues to false)
                max_file_count: 20,

                chunk_size: '50mb',

                // Resize images on clientside if we can
//                resize: {
//                    width: 200,
//                    height: 200,
//                    quality: 90,
//                    crop: true // crop to exact dimensions
//                },

                filters: {
                    // Maximum file size
                    max_file_size: '8200kb',
                    // Specify what files to browse for
                    mime_types: [
                        { title: "Image files", extensions: "jpg,gif,png" },
                        //{ title: "Zip files", extensions: "zip" }
                    ]
                },

                // Rename files by clicking on their titles
                rename: true,

                    // Sort files
                sortable: true,

                // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
                dragdrop: true,

                // Views to activate
                views: {
                    list: true,
                    thumbs: true, // Show thumbs
                    active: 'thumbs'
                },

                // Flash settings
                flash_swf_url: '/assets/js/plupload/js/Moxie.swf',

                // Silverlight settings
                silverlight_xap_url: '/assets/js/plupload/js/Moxie.xap'
            });


            // Handle the case when form was submitted before uploading has finished
            $('#form').submit(function (e) {
                // Files in queue upload them first
                if ($('#uploader').plupload('getFiles').length > 0) {

                    // When all files are uploaded submit form
                    $('#uploader').on('complete', function () {
                        $('#form')[0].submit();
                    });

                    $('#uploader').plupload('start');
                } else {
                    alert("至少选择一个文件");
                }
                return false; // Keep the form from submitting
            });

        });
    </script>
    <div class="mask none"></div>
    <!--没有课程时状态-->
    <div class="col-md-4 col-md-offset-4 tec pd45 hidden">
        <img src="<?php echo utility_cdn::img('/assets_v2/img/blank_pic.jpg'); ?>" >
        <p class="mt10 c_a3 fs16"  >还没来得及添加哦 ...</p>
    </div>
</article>
<!-- /content -->
<script>
    $(function(){
        //图片修改区域
        $(".th_picUp li").not(".li_up").on('mousemove',function(){
                    var _this=$(this);
                    _this.find("em").addClass("delect");
                    _this.siblings().find("em").removeClass("delect");
                }
        );
        //上传
        $(".th_picUp .upPic").click(function(){
            var w_h=$(document).height();
            $(".up_pic").show();
            $(".mask").css('height',w_h).show();
        });

        $(".th_picUp li").not(".li_up").on('mouseout',function(){
            $(this).find("em").removeClass('delect')
        });

        $(".th_pic_new .cloose").click(function(){
            $("#form").hide();
            $(".mask").hide();
        });
        //编辑文字部分
        $(".th_picUp li div").on('click','span',function(){
            $(this).parents("div").css('border','1px solid #cecece');
            $(this).attr('contenteditable','true').siblings(".want").show()
        });

        //删除
        $(".th_picUp li").on('click','.delect',function(){
            var imgId=$(this).parents("li").attr("data-id");
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/teacher/ajax/delImg",
                data: { imgId:imgId,tid:<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?> },
                success: function(d){
                    if (d.code == 0) {
                        layer.msg('操作成功', { icon: 1 }, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg(d.errMsg);
                    }
                },
                error:function(){
                    layer.msg('操作错误');
                }
            });
        });

        //编辑
        $(".th_picUp li .want").on('click',function(event){
            var imgId=$(this).parents("li").attr("data-id");
            var name=$(this).siblings("span").text();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/teacher/ajax/UpdateTeacherImgName",
                data: { imgId:imgId,name:name,tid:<?php echo SlightPHP\Tpl::$_tpl_vars["tid"]; ?> },
                success: function(d){
                    /*$(this).parents("div").css('border',0).find(".want").hide();
                    layer.msg(d.errMsg);*/
                    if (d.code == 0) {
                        layer.msg('操作成功', { icon: 1 }, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg(d.errMsg);
                    }
                },
                error:function(){
                    layer.msg('操作错误');
                }
            });
            event.stopPropagation();
        });
        //点击页面空白区域
        $(document).bind('click',function(e){
            var e=e || window.event;
            var elem= e.target || e.srcElement;
            if(!($(elem).parents().hasClass("isEvent"))){
                $(".isEvent").css("border",0);
                $(".want").hide();
            }else{
                return false;
            }
        })
    })
</script>
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
