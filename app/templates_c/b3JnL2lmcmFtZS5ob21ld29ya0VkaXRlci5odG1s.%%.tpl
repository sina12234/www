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
<div class="correct-flash" id="correct-flash" style="width:100%;height: 100%; overflow: hidden;" >
    <div id="HomeworkEditer"></div>
</div>
<script>
    function _Player(id,url,uploadURL,oldImg){
        this._id=id;
        this.status = false;
        this.uploadURL = uploadURL;
        this.url = url;
        this.orgURL = oldImg;
    }
    _Player.prototype.player=function(){
        if (navigator.appName.indexOf("Microsoft") != -1) {
            return window[this._id];
        } else {
            return document[this._id];
        }
    }
    _Player.prototype.inited = function(){
        this.openImage();
        this.status= true;
    }
    _Player.prototype.openImage = function(){
        var close = "HomeworkEditer.close";
        var uploadComplete = "HomeworkEditer.uploadComplete";
        this.player(this._id).openImage(this.url,this.orgURL,this.uploadURL,uploadComplete,close);
    }
    _Player.prototype.uploadComplete = function(data,w,h){
        var imgData = JSON.parse(data);
        if(imgData.code == 200){
            imgData['src_small'] = imgData['bigurl'];
            parent.$('#pt-work-detail').find('.img-box').eq(parent['activeImgIndex']).find('img').attr('src',imgData['bigurl']);
            var thumbImg = parent.$('#task-result').find('.image-box[data-index='+parent['activeImgIndex']+']');
            if(thumbImg.hasClass('image-box')){
                thumbImg.find('img').attr('src',imgData['bigurl']);
                thumbImg.find('input[name=bigImg]').val(imgData['big']);
                thumbImg.find('input[name=smallImg]').val(imgData['small']);
                thumbImg.find('input[name=smallWidth]').val(w);
                thumbImg.find('input[name=smallHeight]').val(h);
            }else{
                var imgHtml = parent.$('#image-box').html();
                parent.$('#task-result').append(ejs.render(imgHtml,{ index:parent['activeImgIndex'],imgUrl:imgData,className:'new',imgW:w,imgH:h}));
            }
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        }
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
            "/assets_v2/swf/HomeworkEditer.swf",
            "HomeworkEditer", "100%", "100%", "10.0.0",
            "//cdn-assets-ssl.gn100.com/assets/swf/expressInstall.swf?ver=0.0.265",
            flashvars, params, attributes);
    window['HomeworkEditer']=new _Player("HomeworkEditer");

    var uploadURL = "//" + location.host + '/file/main/taskFlashUpload';
    var url = parent['dataImg'];
    var oldImg = parent['oldImg'];
    if(window['HomeworkEditer'].status){
        window['HomeworkEditer'].url = url ;
        window['HomeworkEditer'].uploadURL = uploadURL ;
        window['HomeworkEditer'].openImage();
    }else {
        window['HomeworkEditer'] = new _Player("HomeworkEditer", url, uploadURL, oldImg);
    }
</script>
</body>
</html>
