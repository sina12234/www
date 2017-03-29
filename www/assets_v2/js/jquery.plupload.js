/*
*Date:07-27 2016
*Name:Changyuan.liu
*www.gn.100.com
*Jquery.plupload.js
*/

//file.upload
(function() {
    var swappic = {
        txt : "/assets_v2/img/lesson-txt.png",
        pdf : "/assets_v2/img/lesson-pdf.png",
        doc : "/assets_v2/img/lesson-doc.png",
        docx: "/assets_v2/img/lesson-doc.png",
        ppt : "/assets_v2/img/lesson-ppt.png",
        pptx: "/assets_v2/img/lesson-ppt.png",
        jpg : "/assets_v2/img/lesson-jpg.png",
        xls : "/assets_v2/img/lesson-xls.png",
        xlsx: "/assets_v2/img/lesson-xls.png",
    };
    var fileloader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'select-upload-btn',
        url: '/file/main/UploadFile',
        filters: {
            mime_types : [
                { title : "Word  files", extensions : "jpg,txt,pdf,doc,docx,ppt,pptx,xls,xlsx,rar,zip" }
            ],
            prevent_duplicates:false,
            max_file_size:"100mb"
        }
        ,multi_selection:true
        ,file_data_name:'upfile'
    });

    fileloader.init();

    fileloader.bind('FilesAdded', function(up, files) {
        if(up.files.length < 11) {
            fileloader.start();
            for(var i in files) {
                var fileUrl, fileStr, attrtitle;
                var att = files[i].name.split(".");
                    attrtitle = cutstr(att[0], 60);
                    fileStr = att.pop();
                var lower = fileStr.toLocaleLowerCase();

                    if(swappic.hasOwnProperty(lower)){
                        for(var x in swappic){
                            if(lower == x){
                                fileUrl = swappic[x];
                            }
                        }
                    }else{
                        fileUrl = "/assets_v2/img/lesson-doc.png";
                    }

                    addFileHtml(files[i].id, attrtitle, files[i].size, fileUrl);
            }
        }else {
            layer.msg('文件最多上传10个');
            setTimeout(function() {
                window.location.reload();
            }, 1000);
            return false;
        }
    });

    fileloader.bind('UploadProgress', function(up, file) {
        $('#percent_'+file.id).text('上传'+file.percent+"%");
    });

    fileloader.bind('Error', function(up, error) {
        if(error.errorcode == -1){
            layer.msg('文件过大，仅支持100Ｍ文件');
            return false;
        }else{
            layer.msg('文件过大，仅支持100Ｍ文件');
            return false;
        }
    });

    fileloader.bind('FileUploaded', function(up, file, info) {
        if(info.response){
            var r = jQuery.parseJSON(info.response);
            if(r.errorcode == 1){
                $("#type"+file.id).val(r.type);
                $('#thumb'+file.id).val(r.thumb);
                $('#attach'+file.id).val(r.attach);
                $('#file-num-tip').hide();
                $('#select-upload-btn').hide();
                $('#request-upload-btn').show();
                $('#file-list').show();
            }else {
				$('#file-list').find('li').remove();
                layer.msg(r.error);
            }
        }
    });

})();

function addFileHtml(fileId, fileName, fileSize, fileUrl) {
    var size, addHtml;
        f = parseFloat( (fileSize/1024)/1024 ).toFixed(1);
        size = f + 'M';

        if(0.5 >= f){
            f = parseInt( (fileSize/1024) );
            size = f + 'KB';
        }

        addHtml = '<li>'
        addHtml += '<span class="c-fl">'
        addHtml += '<img src="'+fileUrl+'" alt="">'
        addHtml += '</span>'
        addHtml += '<span class="c-fl">'+size+'</span>'
        addHtml += '<a href="javascript:;" class="c-fr" onclick="delet(this)">删除</a>'
        addHtml += '<div class="mr10 named">'
        addHtml += '命名：'
        addHtml += '<input id="attrtitle" class="file-title" type="text" value="'+fileName+'" />'
        addHtml += '<span class="cRed c-fr" id="percent_'+fileId+'"></span>'
        addHtml += '</div>'
        addHtml += '<input type="hidden" class="file-type" id="type'+fileId+'" />'
        addHtml += '<input type="hidden" class="file-thumb" id="thumb'+fileId+'" />'
        addHtml += '<input type="hidden" class="file-attach" id="attach'+fileId+'" />'
        addHtml += '</li>';

        $('#file-list').append(addHtml);
}

function cutstr(str, len) {
    var strLength = 0;
    var strLen = 0;
        strCut = new String();
        strLen = str.length;
    for (var i = 0; i < strLen; i++) {
        a = str.charAt(i);
        strLength++;
        if (escape(a).length > 4) {
            strLength++;
        }
        strCut = strCut.concat(a);
        if (strLength >= len) {
            strCut = strCut.concat("...");
            return strCut;
        }
    }
    if (strLength < len) {
        return str;
    }
}

function delet(obj) {
    var liParentsElement = $(obj).parents('li');
        liParentsElement.remove();
        if($('#file-list').find('li').length == 0) {
            $('#select-upload-btn').show();
            $('#file-num-tip').show();
            $('#file-list').hide();
            $('#request-upload-btn').hide();
        }
}
