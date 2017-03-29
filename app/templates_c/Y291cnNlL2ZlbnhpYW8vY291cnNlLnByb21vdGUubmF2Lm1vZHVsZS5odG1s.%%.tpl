<div class="tab-main">
    <div class="tab-hd fs14">
        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=='promotecenter'){; ?>curr<?php }; ?>" href="/course.promote.center">云课推广中心</a>
        <a href="/course.promote.list" class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=='promotelist'){; ?>curr<?php }; ?>">我推广的课程</a>
        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=='reselllist'){; ?>curr<?php }; ?>" href="/course.resell.list">我引入的课程</a>
        <a class="tab-hd-opt <?php if(SlightPHP\Tpl::$_tpl_vars["nav"]=='reselllog'){; ?>curr<?php }; ?>" href="/course.resell.GetOrgPromoteLog">成交记录</a>
    </div>
    <div class="c-fr" <?php if(!isset(SlightPHP\Tpl::$_tpl_vars["path"])){; ?>style="display:none;"<?php }; ?>>
        <form id="search_pcourse_form" method="get" action="">
            <div class="search-frame org-class-course">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["searchType"])){; ?>
                <select name="search_type" id="search_type" style="width:60px;float:left;border-right:0">
                    <option value="1" <?php if(SlightPHP\Tpl::$_tpl_vars["searchType"]==1){; ?>selected<?php }; ?>>课程</option>
                    <option value="2" <?php if(SlightPHP\Tpl::$_tpl_vars["searchType"]==2){; ?>selected<?php }; ?>>机构</option>
                </select>
                <?php }; ?>
                <input name="search_field" class="search-input" id="sc_title" type="text" value="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['search_field']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['search_field']; ?><?php }; ?>" placeholder="搜索课程名称">
                <button class="search-box org-t-search-btn" id="subsearch" >
                    <span class="search-icon" style="margin:0;" ></span>
                    <div class='t-list-img discount-delt-btn clear-icon' id="t-delt-btn" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["pm"]['search_field'])){; ?>style="display:none;"<?php }; ?>></div>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function searchPcourseSubmit() {
	var url = '';
	var search_name = $(".divselect cite").text();
	url = <?php if(isset(SlightPHP\Tpl::$_tpl_vars["path"])){; ?>'<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>'<?php }else{; ?>''<?php }; ?>;
	$('#search_pcourse_form').attr('action', url);
	$('#search_pcourse_form').submit();
    }
    $("#search_type").change(function(){
        var Input = $("#sc_title");
        var thisValue = $(this).val();
        if(thisValue == 1){
            Input.attr('placeholder','搜索课程名称');
        }else if(thisValue == 2){
            Input.attr('placeholder','搜索机构名称');
        }
    })

    $("#subsearch").click(function(){
            searchPcourseSubmit();
    });
    $("#t-delt-btn").click(function() {
        $(this).css("display",'none');
        $('#sc_title').val('');
    })
</script>
