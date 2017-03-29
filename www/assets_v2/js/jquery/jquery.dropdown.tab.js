$(function () {
    (function () {
        var dropdown = $('.dropdown');
        var dropdownInput = dropdown.find('.dropdown-input');
        var dropdownShowTab;
        var dropdownBox = dropdown.find('.dropdown-box');
        var dropdownParentsId = dropdown.find('#dropdown-box');
        var validateError = '至少选择一项';
        var validateTip= '至少选择一项，最多选择三项';
        var validateWarn = '只能添加三项';
        var clearTime;
        dropdownInput.on('dropdownValidate',function () {
            dropdown = $(this).parent();
            dropdownShowTab = $(this).find('.dropdown-show-tab');
           if(dropdownShowTab.size()==0){
               dropdown.parent().find('.tips').addClass('tips-error').find('.tips-text').text(validateError);
               return false;
           }
        });
        dropdownBox.on('click','.dropdown-tab',function () {
            if($(this).parents('.dropdown-box').attr('id') != 'dropdown-box'){
                dropdown = $(this).parent().parent();
                dropdownShowTab = dropdown.find('.dropdown-show-tab');
                if(dropdown.parent().find('.tips').hasClass('tips-error')){
                    dropdown.parent().find('.tips').removeClass('tips-error').find('.tips-text').text(validateTip);
                };
                if($(this).hasClass('on')){
                    return false;
                }else{
                    var val = $(this).attr("rel");
                    if(dropdownShowTab.size()>=3){
                        dropdown.parent().find('.tips').addClass('tips-error').find('.tips-text').text(validateWarn);
                        clearTime = setTimeout(function () {
                            dropdown.parent().find('.tips').removeClass('tips-error').find('.tips-text').text(validateTip);
                            clearTimeout(clearTime);
                        },1500);
                        return false;
                    }else{
                        $(this).addClass('on');
                        dropdown.find('.dropdown-input').append(
                            ' <div class="delect-subj dropdown-show-tab pd0 c-fl" rel="'+val+'" contenteditable="false"><div class="left-side"></div> <div class="tab-delete"></div>'+$(this).text()+'<input type="hidden" name="good_subject[]" value="'+val+'"/></div>'
                        );
                    }
                }
            }else {
                $(this).addClass('on');
                var dropdownTabTxt = $(this).text(),_html;
                var labelContentLength = $('#label-content').find('.dropdown-show-tab').length;
                _html = '<div class="dropdown-show-tab p0 c-fl">'
                _html += '<div class="left-side"></div>'
                _html += '<div class="tab-delete"></div>'
                _html += dropdownTabTxt
                _html += '</div>';
                if(labelContentLength < 3 ) {
                    $('.course-name-ipt').val('');
                    $('#label-content').append(_html);
                }else {
                    layer.msg('最多添加三项');
                    $(this).removeClass('on');
                    $('.course-name-ipt').val('');
                    return false;
                }
            }
        });

        dropdown.on('keydown', '.course-name-ipt', function(e) {
            var e = e || event,
                keycode = e.which || e.keyCode,_html;
            if (keycode==32 || keycode==13 || (event.shiftKey && event.keyCode==13)) {
                var tagName = $.trim($(this).val());
                if(tagName==''){
                    layer.msg('标签不能为空');
                    return false;
                }else {
                    _html = '<div class="dropdown-show-tab p0 c-fl">'+'<div class="left-side">'+'</div>'+'<div class="tab-delete">'+'</div>'+tagName+'</div>';
                    var labelContentLength = $('#label-content').find('.dropdown-show-tab').length;

                    if(labelContentLength < 3 ) {
                        $('.course-name-ipt').val('');
                        $('#label-content').append(_html);
                    }else {
                        layer.msg('最多添加三项');
                        $('.course-name-ipt').val('');
                        return false;
                    }
                }
            }
        })
        dropdownInput.on('click',function (event) {
            event.stopPropagation();
            $(this).parents(".dropdown").find('.dropdown-box').attr('data-show','true').css('display','block');
        });
        $(document).on('click',function (event) {
            if(event.target.className.indexOf('dropdown-tab')== -1){
                $('.dropdown-box[data-show=true]').css('display','none');
            }
        });
        dropdownInput.on('click','.dropdown-show-tab .tab-delete',function () {
            dropdownShowTab = $(this).parent();
            dropdown = dropdownShowTab.parent().parent();
            var val = $(this).parent().attr('rel');
            dropdown.find('.dropdown-box .dropdown-tab[rel='+val+']').removeClass('on');
            $(this).parent().remove();
        });
    })();
});