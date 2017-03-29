
$(function () {
    var type = $.getUrlParam('type');
    //作业内容
    $('#textarea').on({
        blur:function () {
            var val = $.trim($(this).text());
            var length = verifyValue (val);
            var placeHolder=$('#place-holder');
            var maxLength=$(this).attr('data-max');
            var _err=$('<div class="tips"><span class="tips-icon"></span><span class="tips-text cRed"></span></div>');
            if(length>maxLength){
                $(this).addClass('error-border');
                var message = '输入字符超过'+maxLength+'个';
                _err.find('.tips-text').html(message);
                placeHolder.html(_err);
            }else{
                placeHolder.html('最多输入'+maxLength+'个字符');
            }
        },
        focus:function () {
            $(this).removeClass('error-border');
        }
    })
    //图片上传
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'add-image',
        url: '/file/main/taskUpload',
        filters: {
            mime_types : [
                { title : "Image files", extensions : "jpg,jpeg,gif,png" }
            ],
            max_file_size:"5120kb"
        }
        ,multi_selection:false
    });

    uploader.init();

    uploader.bind('FilesAdded', function(up, files) {
        uploader.start();
        $('#task-result').append('<div class="image-box result-box left new no-img"><img src="/assets_v2/img/loading3.gif"></div>');
    });

    uploader.bind('Error', function(up, error) {
        if(error.code == -600){
            layer.msg('文件太大了，请选择小于5M的文件');
        }else{
            layer.msg('出错了,请重新选择文件或者刷新页面再重新选择文件');
        }
    });

    uploader.bind('FileUploaded', function(up, file,info) {
        if(info.response){
            var r = jQuery.parseJSON(info.response);
            if(r.code==200){
                var imgHtml = $('#image-box').html();
                $('#task-result').find('.no-img').remove();
                var tHtml='<div class="btn">保存</div><a href="/task/publishTask/teacherTaskList/" class="blue-link">取消返回上级</a>';
                var cHtml='<div class="btn">完成批改</div><a id="next-task" class="blue-link" href="javascript:;">完成并批改下一份作业</a>';
                var sHtml='<div class="btn">提交作业</div>';
                $('#submit').empty();
                if(type=='create'){
                    $('#submit').html(tHtml);
                }else if(type=='update'){
                    $('#submit').html(tHtml);
                }else{
                    $('#submit').html(sHtml);
                }

                $('#correct-sub').empty();
                $('#correct-sub').html(cHtml);
                $('#task-result').append(ejs.render(imgHtml,{ imgUrl:r.data,className:'new',imgW:r.data.small_width,imgH:r.data.small_height}));
            }
        }
    });
    //添加图片
    $('#add-image').on('click',function () {
        var imageLength = $('#task-result').find('.image-box').size();
        if(imageLength>9){
            uploader.disableBrowse(true);
            // layer.msg('最多上传10张图片!');
            $(this).siblings('.add-image-info').css('color','red');
        }else{
            uploader.disableBrowse(false);
        }
    })

    var fileloader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'add-file',
        url: '/file/main/uploadFile',
        filters: {
            mime_types : [
                { title : "Word  files", extensions : "jpg,txt,pdf,doc,docx,ppt,pptx,xls,xlsx" }
            ],
            max_file_size:"10mb"
        }
        ,multi_selection:false
        ,file_data_name:'upfile'
    });

    fileloader.init();

    fileloader.bind('FilesAdded', function(up, files) {
        $('#file-tag-box .file-uploading').length < 1 && $('#file-tag-box').append('<div class="file-uploading file-tag tac pl35 pt5"><img src="/assets_v2/img/loading3.gif"></div>');
        fileloader.start();
    });

    fileloader.bind('Error', function(up, error) {
        $('#file-tag-box .file-uploading').remove();
        console.log(error);
        if(error.code == -600){
            layer.msg('文件太大了,请选择小于10M的文件');
            return false;
        }else{
            layer.msg('出错了,请重新选择jpg,txt,pdf,doc,docx,ppt,pptx,xls,xlsx格式的文件');
            return false;
        }
    });

    fileloader.bind('FileUploaded', function(up, file, info) {
        $('#file-tag-box .file-uploading').remove();
        if(info.response){
            var r = jQuery.parseJSON(info.response);
            if(r.errorcode == 1){
                var fileRes = { };
                var fileName = file.name.split('.');
                fileRes['size'] = bytesToSize(file.size);
                fileRes['name'] = fileName[0];
                fileRes['thumb'] = r.thumb;
                fileRes['fileUrl'] = r.attach;
                fileRes['type'] = fileName[1];
                var fileHtml = $('#file-box').html();
                $('#file-tag-box').append(ejs.render(fileHtml,{ data:fileRes,className:'new'}));
            }
        }
    });
    //转换文件大小
    function bytesToSize(bytes) {
        if (bytes === 0) return '0 B';
        var k = 1024, // or 1024
            sizes = ['B', 'KB', 'M', 'G', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            i = Math.floor(Math.log(bytes) / Math.log(k));
        return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
    }
    //上传文件
    $('#add-file').on('click',function () {
        var fillLength = $('#file-tag-box').find('.file-tag').size();
        if(fillLength>2){
            fileloader.disableBrowse(true);
            // layer.msg('最多上传三个附件!');
            $(this).siblings('.cGray').css('color','red');
        }else{
            fileloader.disableBrowse(false);

        }
    })

    //删除文件
    $('#file-tag-box').on('click','.file-clear',function () {
        var parent = $(this).parent();
        if(!parent.hasClass('new')){
            var fileId = parent.find('input[name=fileId]').val();
            $.ajax('task/publishTask/deleteAttach',{
                type:'post',
                dataType:'json',
                data:{
                    pk_attach:fileId
                },
                success:function (data) {
                    if(data.result.code==200){
                        parent.remove();
                    }else{
                        layer.msg('删除文件失败!');
                    }
                },
                error:function () {
                    layer.msg('提交失败');
                    return false;
                }
            })
        }else{
            parent.remove();
        }
    })


    $('#task-result').on('click','.clear-icon',function () {
        var parent = $(this).parent();
        if(!parent.hasClass('new')){
            var imgId = parent.find('input[name=ImgId]').val();
            $.ajax('task/publishTask/deleteImage',{
                type:'post',
                dataType:'json',
                data:{
                    pk_thumb:imgId
                },
                success:function (data) {
                   if(data.result.code==200){
                       parent.remove();
                   }else{
                       layer.msg('删除图片失败!');
                   }
                },
                error:function () {
                    layer.msg('提交失败');
                    return false;
                }
            })
        }else{
            parent.remove();
        }

    })

    function verifyValue(str) {
        var reg = new RegExp(/[\u4E00-\u9FA5]/g),arr;
        arr = str.match(reg);
        if(arr){
            return str.length + arr.length ;
        }else{
            return str.length
        }
    };
})
function maxLengthKeyUp(text) {
    var reg = /^[a-zA-Z\d`~@#\$%\^&\*\(\)\-_=\+\[\]\{\}\\\|;\:'",<\.>\/\?]{0,20}$/;
    var vv = text.replace(/[^\x00-\xff]/g, "aa");
    return reg.test(vv);
}
