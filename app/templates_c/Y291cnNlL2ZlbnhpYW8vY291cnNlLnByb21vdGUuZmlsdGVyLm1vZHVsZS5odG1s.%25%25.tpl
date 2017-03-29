			<style>
				.wrap-category-courses{ height:20px;float:left; }
				.wrap-category-courses .category-label-title{ border:none; }
				.wrap-category-courses .category-label-title:hover{ border:none;}
			</style>
            <div class="col-md-20 p0 mt10">
                <a href="/course.promote.center" class="c-fl">
                <?php echo tpl_modifier_tr('全部课程','course.list'); ?> >
                <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] != -1){; ?>
                    <a href="/course.promote.center?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>" class="c-fl ml10"><?php echo SlightPHP\Tpl::$_tpl_vars["firstCateInfo"]->name_display; ?><span class=" ml5 delMt8">></span></a>
                <?php }; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] != -1){; ?>
                    <a href="/course.promote.center?fc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate']; ?>&sc=<?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate']; ?>" class="c-fl ml10"><?php echo SlightPHP\Tpl::$_tpl_vars["secondCateInfo"]->name_display; ?></a>
                <?php }; ?>
                </a>
                <a href="//<?php echo SlightPHP\Tpl::$_tpl_vars["platform"]; ?>/index.help.extension" class="c-fr mark-ask">推广规则</a>
            </div>
                <div class="g-list g-course-list fs14">
                    <dl class="clearfix col-md-20 tec">
                        <dt><?php echo tpl_modifier_tr('分类','course.list'); ?> :</dt>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["showLevel"] == 1){; ?>
                        <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["firstCateList"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["firstCateList"] as SlightPHP\Tpl::$_tpl_vars["fk"]=>SlightPHP\Tpl::$_tpl_vars["fo"]){; ?>
                                        <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc',SlightPHP\Tpl::$_tpl_vars["fk"]); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['firstCate'] == SlightPHP\Tpl::$_tpl_vars["fk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["fo"],'course.list'); ?></a></dd>
                                <?php }; ?>
                                <?php }; ?>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 2)){; ?>
                        <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'fc','-1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["secondCateList"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["secondCateList"] as SlightPHP\Tpl::$_tpl_vars["sk"]=>SlightPHP\Tpl::$_tpl_vars["so"]){; ?>
                                        <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc',SlightPHP\Tpl::$_tpl_vars["sk"]); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['secondCate'] == SlightPHP\Tpl::$_tpl_vars["sk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["so"],'course.list'); ?></a></dd>
                                <?php }; ?>
                                <?php }; ?>
                        <?php }elseif((SlightPHP\Tpl::$_tpl_vars["showLevel"] == 3)){; ?>
                        <dd><a href="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sc','-1'); ?><?php }else{; ?><?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc','-1'); ?><?php }; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == -1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a></dd>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["thirdCateList"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["thirdCateList"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["to"]){; ?>
                                        <dd><a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'tc',SlightPHP\Tpl::$_tpl_vars["tk"]); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['thirdCate'] == SlightPHP\Tpl::$_tpl_vars["tk"]){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["to"],'course.list'); ?></a></dd>
                                <?php }; ?>
                                <?php }; ?>
                        <?php }; ?>
                        <div class="col-xs-5 hidden-lg hidden-md mb10 pd0  col-sm-9 col-sm-offset-2 col-sm-offset-lg-0">
                            <button class="course-scrool-btn pos-rel col-sm-4 mt5 c-fr">综合<i class="pos-abs"></i></button>
                        </div>
                    </dl>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attrValueList"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["attrValueList"] as SlightPHP\Tpl::$_tpl_vars["attr"]){; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value)){; ?>
                    <dl class="bgf nobort col-md-20 tec">
                            <dt><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["attr"]->name_display,'course.list'); ?> :</dt>
                            <dd>
                                    <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|-1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["attr"]->flag == 0){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
                            </dd>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["attr"]->attr_value as SlightPHP\Tpl::$_tpl_vars["value"]){; ?>
                            <dd>
                                    <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'vid',SlightPHP\Tpl::$_tpl_vars["attr"]->attr_id.'|'.SlightPHP\Tpl::$_tpl_vars["value"]->attr_value_id); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["value"]->checked == 1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["value"]->value_name,'course.list'); ?></a>
                            </dd>
                            <?php }; ?>
                    </dl>
                    <?php }; ?>
                    <?php }; ?>
                    <?php }; ?>
                    <dl class="nobort col-md-20 tec bgf">
                        <dt><?php echo tpl_modifier_tr('类型','course.list'); ?> :</dt>
                        <dd>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type',''); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 0){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('全部','course.list'); ?></a>
                        </dd>
                        <dd>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type','1'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 1){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('直播课','course.list'); ?></a>
                        </dd>
                        <dd>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type','2'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 2){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('录播课','course.list'); ?></a>
                        </dd>
                        <!--<dd>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'course_type','3'); ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['course_type'] == 3){; ?>class="c-active"<?php }; ?>><?php echo tpl_modifier_tr('线下课','course.list'); ?></a>
                        </dd>-->
                    </dl>
                </div>
 <!--
                <div class="list-scrool col-md-20 mb20 fs14 distri-list-scrool bor1px">
                    <span class="col-md-3 <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='recomm_weight:desc'){; ?>cYellow<?php }; ?> tec" style="border-right:1px solid #ddd;">
                        <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','recomm_weight:desc'); ?>">综合排序</a>
                    </span>
                    <span class="distriprice ml10 c-fl mr20 <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price_promote:asc' or  SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price_promote:desc'){; ?>cYellow<?php }; ?>">
                        <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price_promote:asc'){; ?>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price_promote:desc'); ?>"><?php echo tpl_modifier_tr('价格','course.list'); ?>
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/list-x.png'); ?>">
                            </a>
                        <?php }elseif( SlightPHP\Tpl::$_tpl_vars["pm"]['sort']=='price_promote:desc'){; ?>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price_promote:asc'); ?>"><?php echo tpl_modifier_tr('价格','course.list'); ?>
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/list-down.png'); ?>">
                            </a>
                        <?php }else{; ?>
                            <a href="<?php echo utility_tool::getUrl(SlightPHP\Tpl::$_tpl_vars["path"],'sort','price_promote:desc'); ?>"><?php echo tpl_modifier_tr('价格','course.list'); ?>
                                    <img src="<?php echo utility_cdn::img('/assets_v2/img/list-up.png'); ?>">
                            </a>
                        <?php }; ?>
                    </span>
                    <form id="full_search_pcourse_form" method="get" action="">
                    <div class="bor1px priceTxt mt4 c-fl bgf">
                        <span class="c-fl">&yen;</span>
                        <input type="text" class="inputpd c-fr" name="minprice" value="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['minprice']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['minprice']; ?><?php }; ?>" />
                    </div>
                    <span class="c-fl ml5 mr5">—</span>
                    <div class="bor1px priceTxt mt4 c-fl bgf">
                        <span class="c-fl">&yen;</span>
                        <input type="text" class="inputpd c-fr" name="maxprice" value="<?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['maxprice']){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["pm"]['maxprice']; ?><?php }; ?>" />
                    </div>
                    <button class="c-fl ml10 but14 mt4" id="fullsearch">确定</button>
                    </form>
                </div>
-->
<script>
    function fullSearchPcourseSubmit() {
	var url = '';
	var search_name = $(".divselect cite").text();
	url = '/course.promote.center';
	$('#full_search_pcourse_form').attr('action', url);
	$('#full_search_pcourse_form').submit();
    }

    $("#fullsearch").click(function(){
            fullSearchPcourseSubmit();
    });
</script>
