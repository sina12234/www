//左右选择
$(function() {
	(function() {
		var $selectParentId = $('#multiple-select-list');
		var $selectAddChildren = $('#add-btn');
		var $selectDelChildren = $('#del-btn');
		var $selectMultipleRt = $('#multiple-right');
		var $selectMultipleLt = $('#multiple-left');
		var $selectCourseAdd = $('#course_add');
		var $selectCourseCancle = $('#course_cel');

			$selectParentId.on('click', 'li:not(.selected)', function() {
		        if($(this).hasClass('select')){
		            $(this).removeClass('select');
		        }else{
		            $(this).addClass('select');
		        }
		        if($selectMultipleRt.find('li.select').length>0){
		            $selectDelChildren.addClass('allow');
		        }else{
		            $selectDelChildren.removeClass('allow');
		        }
		        if($selectMultipleLt.find('li.select').length>0){
		            $selectAddChildren.addClass('allow');
		        }else{
		            $selectAddChildren.removeClass('allow');
		        }
			});

			$selectAddChildren.click(function() {
		        if($(this).hasClass('allow')){
		            var cHtml='';
		            $selectMultipleLt.find('li.select').each(function() {
		                cHtml += '<li data-id="'+$(this).attr('data-id')+'">'+$(this).html()+'</li>';
						$(this).removeClass('select');
						$(this).addClass('selected');
		            });

		            $selectMultipleRt.append(cHtml);
		            $selectMultipleRt.find('.defalut').remove();
		            if($selectMultipleLt.find('li').length==0){
		                $(this).removeClass('allow');
		            }
		        }else{
		            $selectMultipleLt.css('border','1px solid #ffa91e');
		        }
			})

			$selectDelChildren.click(function() {
		        if($(this).hasClass('allow')){
		            var cHtml='';
		            $selectMultipleRt.find('li.select').each(function() {
						var id = $(this).attr('data-id');
		                cHtml += '<li data-id="'+id+'">'+$(this).html()+'</li>';
		                $(this).remove();
						$selectMultipleLt.find('.selected').each(function(){
							if($(this).attr('data-id') == id){
								$(this).removeClass('selected');
							}
						})
		            });
		            if($selectMultipleRt.find('li').length==0){
		                $(this).removeClass('allow');
		                $selectMultipleRt.append('<li class="defalut">还没有课程</li>');
		            }
		        }else{
		            $selectMultipleRt.css('border','1px solid #ffa91e');
		        }
			})

			$selectCourseAdd.click(function() {
				layer.closeAll();
			})
			
			$selectCourseCancle.click(function() {
				layer.closeAll();
			})
	})();
})