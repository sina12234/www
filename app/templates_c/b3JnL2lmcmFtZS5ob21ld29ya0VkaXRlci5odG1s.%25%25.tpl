<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <?php echo tpl_function_part("/site.main.header"); ?>
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/swfobject.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/ejs.min.js'); ?>"></script>
</head>
<body>
<div class="correct-flash" id="correct-flash"  >
    <div id="HomeworkEditer"></div>
</div>
<script>
    function _Player(id,url,uploadURL){
        this._id=id;
        this.status = false;
        this.uploadURL = uploadURL;
        this.url = url;
        this.layer = layer;
    }
    _Player.prototype.player=function(){
        if (navigator.appName.indexOf("Microsoft") != -1) {
            return window[this._id];
        } else {
            return document[this._id];
        }
    }
    _Player.prototype.inited = function(){
        this.openImage(this.uploadURL,this.url);
        this.status= true;
    }
    _Player.prototype.openImage = function(){
        var close = "HomeworkEditer.close";
        var uploadComplete = "HomeworkEditer.uploadComplete";
        this.player(this._id).openImage(this.url,this.uploadURL,uploadComplete,close);
    }
    _Player.prototype.uploadComplete = function(data){
        parent.$('#pt-work-detail').find('.img-box').eq(parent['activeImgIndex']).find('img').attr('src',data);
        var imgHtml = parent.$('#image-box').html();
        parent.$('#task-result').append(ejs.render(imgHtml,{ imgUrl:data,className:'new'}));
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭
    }
    _Player.prototype.close = function(){
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭
    }
    var flashvars = {
        inited:"HomeworkEditer.inited"
    };
    var params = {
        menu: "false",
        scale: "noScale",
        allowFullscreen: "true",
        allowScriptAccess: "always",
        bgcolor: "",
        wmode: "direct" // can cause issues with FP settings & webcam
    };
    var attributes = {
        id:"HomeworkEditer"
    };
    swfobject.embedSWF(
            "/assets_v2/img/HomeworkEditer.swf",
            "HomeworkEditer", "590px", "440px", "10.0.0",
            "//cdn-assets-ssl.gn100.com/assets/swf/expressInstall.swf?ver=0.0.265",
            flashvars, params, attributes);
    window['HomeworkEditer']=new _Player("HomeworkEditer");

    var uploadURL = "//" + location.host + '/file/main/taskFlashUpload';
    var url = parent['dataImg'];

    if(window['HomeworkEditer'].status){
        window['HomeworkEditer'].url = url ;
        window['HomeworkEditer'].uploadURL = uploadURL ;
        window['HomeworkEditer'].openImage();
    }else {
        window['HomeworkEditer'] = new _Player("HomeworkEditer", url, uploadURL, layer);
    }
</script>
</body>
</html>