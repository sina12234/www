<!DOCTYPE html>
<html>
<head>
<title><?php echo tpl_function_part('/site.main.orgname'); ?> - 首页维护 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 首页维护 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery.dragsort-0.5.2.min.js'); ?>"></script>
</head>

<body>
<?php echo tpl_function_part("/site.main.nav"); ?>
<section class="pd30">
<div class="container">
	<div class="row">
  	<?php echo tpl_function_part("/org.main.menu.template"); ?>
    	<div class="right-main col-sm-9 col-md-16  pos-rel" id="template-course-data">
          <div class="content">
			<form>
            	<p class="fs14">
					<button type="submit" class="btn c-fr" id="index-release">首页发布</button>
                <a class="gray-btn c-fr mr10" id="set-class-add">+添加模板</a>
              </p>
              <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["templates"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==0){; ?>
              <?php foreach(SlightPHP\Tpl::$_tpl_vars["templates"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["tv"]){; ?>
              <div class="set-class-li" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">
              	<div class="set-title col-sm-12 col-xs-12 col-md-20">
                	<span class="set-title-icon"></span>
                  	<p class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>
						<input type="hidden" name="title[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>"/>
						<input type="hidden" name="template_id[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"/>
						<input type="hidden" name="owner_id[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->owner_id; ?>"/>
						<input type="hidden" name="row_count[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->row_count; ?>"/>
						<input type="hidden" name="recommend[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->recommend; ?>"/>
						<input type="hidden" name="query_str[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->query_str; ?>"/>
						<input type="hidden" name="order_by[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->order_by; ?>"/>
						<input type="hidden" name="course_ids[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->course_ids; ?>"/>
						<input type="hidden" name="sort[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->sort; ?>"/>
						<input type="hidden" name="create_time[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->create_time; ?>"/>
						<input type="hidden" name="last_updated[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->last_updated; ?>"/>
						<input type="hidden" name="set_url[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->set_url; ?>"/>
						<input type="hidden" name="type[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->type; ?>"/>
						<input type="hidden" name="thumb_left[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_left; ?>"/>
						<input type="hidden" name="thumb_right[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_right; ?>"/>
                      <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
                      (<span class="fs12"><?php echo tpl_modifier_tr('手动推荐','org'); ?></span>)
                      <?php }else{; ?>
                      (<span class="fs12"><?php echo tpl_modifier_tr('自动推荐','org'); ?> <?php echo tpl_modifier_tr('内容展示','org'); ?>:
                      <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate],'course.list'); ?><?php }else{; ?><?php echo tpl_modifier_tr('全年级','org'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate],'course.list'); ?><?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate],'course.list'); ?><?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->attr_value_id) and SlightPHP\Tpl::$_tpl_vars["valueNameStr"][SlightPHP\Tpl::$_tpl_vars["tv"]->template_id]){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["valueNameStr"][SlightPHP\Tpl::$_tpl_vars["tv"]->template_id]; ?><?php }else{; ?><?php echo tpl_modifier_tr('全科目','org'); ?><?php }; ?>
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type)&&SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type=="1,2,3"){; ?><?php echo tpl_modifier_tr('全部','org'); ?>
                                <?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==1)){; ?><?php echo tpl_modifier_tr('直播','org'); ?>
                                <?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==2)){; ?><?php echo tpl_modifier_tr('录播','org'); ?>
                                <?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==3)){; ?><?php echo tpl_modifier_tr('线下课','org'); ?>
								<?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type=="0,1"){; ?><?php echo tpl_modifier_tr('全部','org'); ?>
								<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type==1)){; ?><?php echo tpl_modifier_tr('收费','org'); ?>
								<?php }else{; ?><?php echo tpl_modifier_tr('免费','org'); ?>
								<?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='create_time:desc'){; ?><?php echo tpl_modifier_tr('最新创建','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='create_time:asc'){; ?><?php echo tpl_modifier_tr('最早创建','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='register:desc'){; ?><?php echo tpl_modifier_tr('报名人数多','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='register:asc'){; ?><?php echo tpl_modifier_tr('报名人数少','site.course'); ?><?php }; ?>
                                </span>)
                            <?php }; ?>
                            </p>
							<?php if(count(SlightPHP\Tpl::$_tpl_vars["templates"])>1){; ?>
                            <a href="javascript:void(0)" class="c-fr remove" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"></a>
							<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==1){; ?>
							<span class="sort fs12">
								<a href="javascript:void(0)" class="maunal-order" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"></a>
							</span>
							<?php }; ?>
                            <p class="maunal-div">
                                 <a href="javascript:void(0)" class="maunal-edit" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"><i class="maunal"></i></a>
                                <?php /*<span>
                                    <i class="arrow-up"></i>
                                   <?php echo tpl_modifier_tr('设置模板','org'); ?>
                                </span>*/?>
                            </p>
							
                            <?php if(SlightPHP\Tpl::$_tpl_vars["tk"]<count(SlightPHP\Tpl::$_tpl_vars["templates"])-1){; ?>
                            <a class="arrow-down fs12" href="javascript:void(0)" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" sort="<?php echo SlightPHP\Tpl::$_tpl_vars["tk"]; ?>"><?php echo tpl_modifier_tr('下移','org'); ?><span></span></a>
                            <?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["tk"]>0){; ?>
                            <a class="arrow-up fs12" href="javascript:void(0)" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" sort="<?php echo SlightPHP\Tpl::$_tpl_vars["tk"]; ?>"><?php echo tpl_modifier_tr('上移','org'); ?><span></span></a>
                            <?php }; ?>
                        </div>
                        <dl class="set-class col-xs-20 p0">
                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
                            <?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->courses as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["cv"]){; ?>
                            <dd cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" class="col-xs-5">
                                <div class="set-class-img">
                                    <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/1.png<?php }; ?>">
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status==-2){; ?>
                                    <div class="course-status">该课程已下架(首页不显示)</div>
                                    <?php }; ?>
                                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status<0){; ?>
                                    <div class="course-status">该课程已失效(首页不显示)</div>
                                    <?php }; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->restatus)&&SlightPHP\Tpl::$_tpl_vars["cv"]->restatus==-1){; ?>
                                    <div class="course-status">该课程在推广中已删除(首页不显示)</div>
                                    <?php }; ?>
                                    <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
                                    <p class="edit-classbg"></p>
                                    <a href="javascript:;" class="edit-classbtn top-course edit_course" top="<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>">
                                        <?php echo tpl_modifier_tr('点击修改','org'); ?>
                                    </a>
									<span class="remove pos-abs remove-thisTemplate" data-tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"></span>
                                    <?php }; ?>

                                </div>
                                <a href="/course.info.show.<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->title; ?></a>
                            </dd>
                            <?php }; ?>
                            <?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2 and isset(SlightPHP\Tpl::$_tpl_vars["tv"]->courses) and count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)<16){; ?>
                            <dd cid="1" class="col-xs-5 add-course">
                            <div class="set-class-img top-course no-class-img add_course" style="cursor:pointer;" top="<?php echo count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses); ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">+ <?php echo tpl_modifier_tr('添加推荐课程','org'); ?>
                                </div>
                            </dd>
                            <?php }; ?>
                        </dl>
                        <input type="hidden" name="listSort" value=""/>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==1  and isset(SlightPHP\Tpl::$_tpl_vars["tv"]->courses) and count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)%4==0){; ?>
                        <span class="c-fr add-class-id" style="cursor:pointer;" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"><?php echo tpl_modifier_tr('添加一行','org'); ?></span>
                        <?php }; ?>
                    </div>
                <?php }; ?>
				<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["templates"])&&SlightPHP\Tpl::$_tpl_vars["is_pro"]==1)){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["templates"] as SlightPHP\Tpl::$_tpl_vars["tk"]=>SlightPHP\Tpl::$_tpl_vars["tv"]){; ?>
                    <div class="set-class-li" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">
                        <div class="set-title col-sm-12 col-xs-12 col-md-20">
                            <span class="set-title-icon"></span>
                            <p class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>
							<input type="hidden" name="title[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->title; ?>"/>
							<input type="hidden" name="template_id[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"/>
							<input type="hidden" name="owner_id[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->owner_id; ?>"/>
							<input type="hidden" name="row_count[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->row_count; ?>"/>
							<input type="hidden" name="recommend[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->recommend; ?>"/>
							<input type="hidden" name="query_str[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->query_str; ?>"/>
							<input type="hidden" name="order_by[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->order_by; ?>"/>
							<input type="hidden" name="course_ids[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->course_ids; ?>"/>
							<input type="hidden" name="sort[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->sort; ?>"/>
							<input type="hidden" name="create_time[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->create_time; ?>"/>
							<input type="hidden" name="last_updated[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->last_updated; ?>"/>
							<input type="hidden" name="set_url[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->set_url; ?>"/>
							<input type="hidden" name="type[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->type; ?>"/>
							<input type="hidden" name="thumb_left[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_left; ?>"/>
							<input type="hidden" name="thumb_right[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_right; ?>"/>
							<input type="hidden" name="thumb_left_url[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_left_url; ?>"/>
							<input type="hidden" name="thumb_right_url[]" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_right_url; ?>"/>

                            <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
                                (<span class="fs12"><?php echo tpl_modifier_tr('手动推荐','org'); ?></span>)
                            <?php }else{; ?>
                                (<span class="fs12"><?php echo tpl_modifier_tr('自动推荐','org'); ?> <?php echo tpl_modifier_tr('内容展示','org'); ?>:
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->first_cate],'course.list'); ?><?php }else{; ?><?php echo tpl_modifier_tr('全年级','org'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->second_cate],'course.list'); ?><?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate) and SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate]){; ?><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["cateList"][SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->third_cate],'course.list'); ?><?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->attr_value_id) and SlightPHP\Tpl::$_tpl_vars["valueNameStr"][SlightPHP\Tpl::$_tpl_vars["tv"]->template_id]){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["valueNameStr"][SlightPHP\Tpl::$_tpl_vars["tv"]->template_id]; ?><?php }else{; ?><?php echo tpl_modifier_tr('全科目','org'); ?><?php }; ?>
								<?php if(isset(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type)&&SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type=="1,2,3"){; ?><?php echo tpl_modifier_tr('全部','org'); ?>
                                <?php }elseif( (!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==1)){; ?><?php echo tpl_modifier_tr('直播','org'); ?>
                                <?php }elseif( (!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==2)){; ?><?php echo tpl_modifier_tr('录播','org'); ?>
                                <?php }elseif( (!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->course_type==3)){; ?><?php echo tpl_modifier_tr('线下课','org'); ?>
								<?php }; ?>

                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type==1){; ?><?php echo tpl_modifier_tr('收费','org'); ?>
								<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type) and SlightPHP\Tpl::$_tpl_vars["tv"]->query_arr->fee_type=="0,1")){; ?><?php echo tpl_modifier_tr('全部','org'); ?><?php }else{; ?><?php echo tpl_modifier_tr('免费','org'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='create_time:desc'){; ?><?php echo tpl_modifier_tr('最新创建','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='create_time:asc'){; ?><?php echo tpl_modifier_tr('最早创建','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='register:desc'){; ?><?php echo tpl_modifier_tr('报名人数多','site.course'); ?><?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->order_by) and SlightPHP\Tpl::$_tpl_vars["tv"]->order_by=='register:asc'){; ?><?php echo tpl_modifier_tr('报名人数少','site.course'); ?><?php }; ?>
                                </span>)
                            <?php }; ?>
                            </p>
                            <?php if(count(SlightPHP\Tpl::$_tpl_vars["templates"])>1){; ?>
							<a href="javascript:void(0)" class="c-fr remove" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"></a>
							<?php }; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==1){; ?>
							<span class="sort fs12">
								<a href="javascript:void(0)" class="maunal-order" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"></a>
							</span>
							<?php }; ?>
                            <p class="maunal-div">
                                <a href="javascript:void(0)" class="maunal-edit" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"><i class="maunal"></i></a>
                                <?php /*<span>
                                <i class="arrow-up"></i>
                                <?php echo tpl_modifier_tr('设置模板','org'); ?>
                                
                                </span>*/?>
                            </p>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["tk"]<count(SlightPHP\Tpl::$_tpl_vars["templates"])-1){; ?>
                            <a class="arrow-down fs12" href="javascript:void(0)" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" sort="<?php echo SlightPHP\Tpl::$_tpl_vars["tk"]; ?>"><?php echo tpl_modifier_tr('下移','org'); ?><span></span></a>
                            <?php }; ?>
                            <?php if(SlightPHP\Tpl::$_tpl_vars["tk"]>0){; ?>
                            <a class="arrow-up fs12" href="javascript:void(0)" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" sort="<?php echo SlightPHP\Tpl::$_tpl_vars["tk"]; ?>"><?php echo tpl_modifier_tr('上移','org'); ?><span></span></a>
                            <?php }; ?>
                        </div>
			<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->type==0){; ?>
				<dl class="set-class col-xs-20 p0">
                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
                <?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->courses as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["cv"]){; ?>
                <dd cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" class="col-xs-4">
                	<div class="set-class-img">
                  	<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/1.png<?php }; ?>">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status==-2){; ?>
                    <div class="course-status">该课程已下架(首页不显示)</div>
                    <?php }; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status<0){; ?>
                    <div class="course-status">该课程已失效(首页不显示)</div>
                    <?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->restatus)&&SlightPHP\Tpl::$_tpl_vars["cv"]->restatus==-1){; ?>
                    <div class="course-status">该课程在推广中已删除(首页不显示)</div>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
                    <p class="edit-classbg"></p>
                    <a href="javascript:;" class="edit-classbtn top-course edit_course" top="<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"><?php echo tpl_modifier_tr('点击修改','org'); ?>
                    </a>
					<span class="remove pos-abs remove-thisTemplate" data-tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"></span>
                    <?php }; ?>
					</div>
                    <a href="/course.info.show.<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->title; ?></a>
                </dd>
                <?php }; ?>
                <?php }; ?>
                <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
					<dd cid="1" class="col-xs-5 add-course">
						<div class="set-class-img top-course no-class-img add_course" style="cursor:pointer;" top="<?php echo count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses); ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">+ <?php echo tpl_modifier_tr('添加推荐课程','org'); ?></div>
					</dd>

				<?php }; ?>
				</dl>
						<input type="hidden" name="listSort" value=""/>
                        <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==1 and isset(SlightPHP\Tpl::$_tpl_vars["tv"]->courses) and count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)%5==0){; ?>
                        <p class="col-xs-20"><span class="c-fr add-class-id" style="cursor:pointer;" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>"><?php echo tpl_modifier_tr('添加一行','org'); ?></span></p>
                        <?php }; ?>
			<?php }elseif((SlightPHP\Tpl::$_tpl_vars["tv"]->type==1)){; ?>
						<dl class="set-class col-xs-4 pd0 edit-upload-cover">
							<dt class="ad-img" id="imgid<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_left)){; ?>
								<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_left); ?>"/>
								<?php }else{; ?>
								<div class="no-course-img">
									<p>+添加图片</p>
									<p>上传尺寸：230*390</br>支持jpg、png</p>
								</div>
								<?php }; ?>
							</dt>
						</dl>
						<input type="hidden" name="local_thumb" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->type; ?>"/>
				<dl class="set-class col-xs-16 pd0">
            	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
              	<?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->courses as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["cv"]){; ?>
					<dd cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" class="col-xs-5">
						<div class="set-class-img">
						<img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/1.png<?php }; ?>">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status==-2){; ?>
						<div class="course-status">该课程已下架(首页不显示)</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status<0){; ?>
						<div class="course-status">该课程已失效(首页不显示)</div>
						<?php }; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->restatus)&&SlightPHP\Tpl::$_tpl_vars["cv"]->restatus==-1){; ?>
						<div class="course-status">该课程在推广中已删除(首页不显示)</div>
						<?php }; ?>
						<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
						<p class="edit-classbg"></p>
						<a href="javascript:;" class="edit-classbtn top-course edit_course" top="<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"><?php echo tpl_modifier_tr('点击修改','org'); ?></a>
						<span class="remove pos-abs remove-thisTemplate" data-tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"></span>
						<?php }; ?>
						</div>
						 <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->surl)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->surl; ?><?php }; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->title; ?></a>
                    </dd>
                    <?php }; ?>
                    <?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
					<dd cid="1" class="col-xs-5 add-course">
						<div class="set-class-img top-course no-class-img add_course" style="cursor:pointer;" top="<?php echo count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses); ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">+ <?php echo tpl_modifier_tr('添加推荐课程','org'); ?></div>
					</dd>
					<?php }; ?>
				</dl>
			<?php }elseif((SlightPHP\Tpl::$_tpl_vars["tv"]->type==2)){; ?>
				<dl class="set-class col-xs-16 pd0">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->courses)){; ?>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["tv"]->courses as SlightPHP\Tpl::$_tpl_vars["ck"]=>SlightPHP\Tpl::$_tpl_vars["cv"]){; ?>
                	<dd cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>" class="col-xs-5">
                  	<div class="set-class-img">
                    <img src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["cv"]->thumb_big); ?><?php }else{; ?>/assets_v2/img/1.png<?php }; ?>">
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->admin_status==-2){; ?><div class="course-status">该课程已下架(首页不显示)</div>
                    <?php }; ?>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status)&&SlightPHP\Tpl::$_tpl_vars["cv"]->resell_status<0){; ?>
                    <div class="course-status">该课程已失效(首页不显示)</div>
                    <?php }; ?>
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->restatus)&&SlightPHP\Tpl::$_tpl_vars["cv"]->restatus==-1){; ?>
                    <div class="course-status">该课程在推广中已删除(首页不显示)</div>
                    <?php }; ?>
                    <?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
                    <p class="edit-classbg"></p>
                    <a href="javascript:;" class="edit-classbtn top-course edit_course" top="<?php echo SlightPHP\Tpl::$_tpl_vars["ck"]; ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>">
                    <?php echo tpl_modifier_tr('点击修改','org'); ?>
                    </a>
					<span class="remove pos-abs remove-thisTemplate" data-tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>" data-cid="<?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->course_id; ?>"></span>
                    <?php }; ?>
                    </div>
                    <a href="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["cv"]->surl)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->surl; ?><?php }; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["cv"]->title; ?></a>
                </dd>
                    <?php }; ?>
                    <?php }; ?>
					<?php if(SlightPHP\Tpl::$_tpl_vars["tv"]->recommend==2){; ?>
					<dd cid="1" class="col-xs-4 add-course">
					<div class="set-class-img top-course no-class-img add_course" style="cursor:pointer;" top="<?php echo count(SlightPHP\Tpl::$_tpl_vars["tv"]->courses); ?>" tid="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">+ <?php echo tpl_modifier_tr('添加推荐课程','org'); ?></div>
					</dd>
					<?php }; ?>
				</dl>
				<dl class="set-class col-xs-4 pd0 edit-upload-cover">
					<dt class="ad-img" id="imgid<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->template_id; ?>">
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_right)){; ?>
						<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["tv"]->thumb_right); ?>"/>
						<?php }else{; ?>
						<div class="no-course-img">
							<p>+添加图片</p>
							<p>上传尺寸：230*390</br>支持jpg、png</p>
						</div>
						<?php }; ?>
					</dt>
				</dl>
				<input type="hidden" name="local_thumb" value="<?php echo SlightPHP\Tpl::$_tpl_vars["tv"]->type; ?>"/>
				<?php }; ?>
                </div>
                <?php }; ?>
				<?php }else{; ?>
				<div class="col-md-20  no-template Nocourse mt60" style="display:block;">
                    <div class="template-info ">
                        <div class="no-template-icon"></div>
                        <p>还没有课程不能进行首页模块设置哦！</p>
                       <!-- <div class="btn" id="tmpl-add">新建课程</div>-->
                    </div>
                    <!--<p>您还没有创建课程哦~</p>-->
                    <!--<p>创建课程后自动显示</p>-->
                    <!--<p>-->
                        <!--<a href="javascript:void(0);" class="btn" id="tmpl-add">添加模板</a>-->
                    <!--</p>-->
				</div>
                <?php }; ?>
				</form>
            <!-- 引导弹窗
            <div class="setDistriBoxBg"></div>
            <div class="setTemplateBox pos-abs">
                <a href="javascript:;" title="" id="setTemplateBox-closeBtn"></a>
            </div>
            /引导弹窗 -->
			<style>
				.Nocourse{ height:400px;text-align:center;line-height:200%; }
				.Nocourse p{ font-size:14px;color:#666;}
				.Nocourse a input{ padding:0 25px;height:30px;border-radius:3px;background:#ffa81d;color:#fff; }
                /*上传图片hover*/
                .edit-upload-cover:hover .ad-img:after{
                    position: absolute;top: 0;left: 0;width: 100%;height: 100%;line-height:230px;text-align: center;background-color: rgba(0,0,0,0.5);content:'修改推荐图';display: block;color:#fff;transition: .6s;
                }
			</style>
        </div>
    </div>
<div class='clear'></div>
<a href="#bottom" name="bottom"></a>
</div>
</div>
</section>
<script type="text/javascript">
$(function () {
	if(isPro == 1){
		$('#tab-app').show();
	}
	//添加广告
	var tmpl=$('#template-course-data');
	tmpl.find(".ad-img").click(function(){
		if($(this).attr('id')){
				var tid=''+$(this).attr('id');
		}else{
				var tid='';
		}
		if($(this).parents('.set-class-li').find('input[name="local_thumb"]').val()){
			var local_id = $(this).parents('.set-class-li').find('input[name="local_thumb"]').val();
		}else{
			var local_id = '';
		}

		layer.open({
			type: 2,
			title: ['<?php echo tpl_modifier_tr('图片设置','org'); ?>'],
			shadeClose: true,
			shade: 0.8,
			area: ['500px', '460px'],
			content: '<?php echo "/org.main.addCourseImg."; ?>'+tid+'/'+local_id,
			closeBtn:2
		});
	});
    //拖拽
    $(".maunal-order").click(function(){
        $(this).hide();
        var _this=$(this).parents(".set-class-li").find(".set-class");
        var thisId=$(this).closest(".set-class-li");
        _this.dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder, placeHolderTemplate: "<dd ></dd>" });
        function saveOrder() {
        var data = _this.find("dd").map(function() { return $(this).attr("cid"); }).get();
        _this.siblings("input[name=listSort]").val(data.join(","));
        };
        var cBtn='<input type="button" class="order-cancel" value="取消">';
        var sBtn="<a href='javascript:;' class='order-confirm'>保存</a>";
        thisId.find(".arrow-down,.arrow-up,.add-class-id,.maunal-div,.remove").hide();
        thisId.find(".set-title").append(cBtn,sBtn);
        $(".set-class-li").removeClass("set-class-border");
        thisId.addClass("set-class-border");
        thisId.find(".edit-classbg").css("display",'none');
        thisId.find(".edit-classbtn").css("display",'none');
        thisId.find(".add-course").remove();
    });
    //首页发布
    $('form').submit(function(){
		$.post('/org.main.setTemplate',$(this).serialize(),function(r){
    	if(r.code=="100"){
      	layer.msg("<?php echo tpl_modifier_tr('发布成功','org'); ?>",{ icon: 1 },function(){ });
      }else if(r.code=='-101'){
		layer.msg(r.error);
		return false;
	  }else{
        layer.msg("<?php echo tpl_modifier_tr('发布失败','org'); ?>",{ icon: 1 },function(){ });
      }
    },"json");
		return false;
    });

    $(".set-class-li").on("click","a.arrow-down",function(){
        var tid=$(this).attr('tid');
        var old=$(this).parents('.set-class-li').next().attr('tid');
        var sort=$(this).attr('sort');
        var thisId=$(this).closest(".set-class-li");
        var nextId=$(this).closest(".set-class-li").next(".set-class-li");
        var preId=$(this).closest(".set-class-li").prev(".set-class-li");
        var nums=$(".content").find(".set-class-li").length;
        if(nextId.html() != undefined){
            $.post('/org.main.moveDownAjax',{ 'tid':tid,'sort':sort,'old':old },function(r){
                if(r.error){
                    layer.msg(r.error);
                }else{
                    location.reload();
                }
            },"json");
        }else{
            layer.msg("<?php echo tpl_modifier_tr('不能再移了','org'); ?>");
        }
    });
    $(".set-class-li").on("click",".order-confirm",function(){
            var cids=$(this).parents('.set-class-li').find('input[name=listSort]').val();
            var tid=$(this).parents('.set-class-li').attr('tid');
            var thisId=$(this).closest(".set-class-li");
            if(cids!=''){
                $.post('/org.main.saveSortAjax',{ 'tid':tid,'cids':cids },function(r){
                    if(r.error){
                        layer.msg(r.error);
						return false;
                    }else{
					location.reload();
					}
                },"json");
            };
    })

    $(".set-class-li").on("click",".order-cancel",function(){
        layer.confirm('您确认要取消么？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            location.reload();
        }, function(){
        });
    })
    $(".set-class-li").on("click","a.arrow-up",function(){
        var tid=$(this).attr('tid');
        var old=$(this).parents('.set-class-li').prev().attr('tid');
        var sort=$(this).attr('sort');
        var thisId=$(this).closest(".set-class-li");
        var nextId=$(this).closest(".set-class-li").next(".set-class-li");
        var preId=$(this).closest(".set-class-li").prev(".set-class-li");
        if(preId.html() != undefined){
            $.post('/org.main.moveUpAjax',{ 'tid':tid,'sort':sort,'old':old },function(r){
                if(r.error){
                    layer.msg(r.error);
                }else{
                    location.reload();
                }
            },"json");
        }else{
            layer.msg("<?php echo tpl_modifier_tr('不能再移了','org'); ?>");
        }
    })
    $('.add-class-id').click(function(){
        var tid=$(this).attr('tid');
        var this_parent_li=$(this).closest('.set-class-li');
        var this_nums=this_parent_li.find('li').length;
        if (this_nums<16){
            $.post('/org.main.addRowsAjax',{ tid:tid },function(r){
                if(r.error){
                    layer.msg(r.error);
                }else{
                    var html='';
                    for(var i in r){
                        html+='<dd class="col-sm-4 add-course"><div class="set-class-img"><img src="'+r[i].thumb_big+'"></div><a href="/course.info.show/'+r[i].course_id+'">'+r[i].title+'</a></dd>';
                    }
                    if(i<3){
                        $('.add-class-id').remove();
                    }
                    this_parent_li.find('ul').append(html);
					if(r){
					location.reload();
					}
                }
            },"json");
        }else{
            layer.msg('最多只能添加4行');
        }
    });
    $(".maunal-edit,#set-class-add").click(function(){
        if($(this).attr('tid')){
            var tid='/'+$(this).attr('tid');
        }else{
            var tid='';
        }
        layer.open({
            type: 2,
            title: ['<?php echo tpl_modifier_tr('课程模版设置','org'); ?>'],
            shadeClose: true,
            shade: 0.8,
            area: ['700px', '500px'],
            content: '<?php echo "/org.main.SystemSet"; ?>'+tid,
            closeBtn:2
        });
    });
    $('.add_course').each(function(i){
        var top=$(this).attr('top');
        var tid=$(this).attr('tid');
        $(this).click(function(){
            layer.open({
                type: 2,
                title: ['<?php echo tpl_modifier_tr('课程推荐','org'); ?>'],
                shadeClose: true,
                shade: 0.8,
                area: ['800px', '600px'],
                content: '<?php echo "/org.main.iframeTemplateCourse."; ?>'+top+'/'+tid,
                closeBtn:2
            });
        });
    });
	$('.edit_course').each(function(i){
        var top=$(this).attr('top');
        var tid=$(this).attr('tid');
        var cid=$(this).attr('data-cid');

        $(this).click(function(){
            layer.open({
                type: 2,
                title: ['<?php echo tpl_modifier_tr('课程推荐修改','org'); ?>'],
                shadeClose: true,
                shade: 0.8,
                area: ['800px', '600px'],
                content: '<?php echo "/org.main.iframeTemplateCourseEdit."; ?>'+top+'/'+tid+'/'+cid,
                closeBtn:2
            });
        });
    });
    $('.set-title .remove').click(function(){
        var tid=$(this).attr('tid');
        var cid=$(this).attr('data-tid');
        layer.confirm('确认删除该模块课程吗？',{ btn:['确认','取消'] },function(){
        $.post('/org.main.deleteTemplateAjax',{ tid:tid,cid:cid },function(r){
            if(r.error){
                layer.msg(r.error);
            }else{
                location.reload();
            }
        },"json");
        },function(){ });
    });

    $('.set-class-img .remove-thisTemplate').click(function() {
		var cid=$(this).attr('data-cid');
		var tid=$(this).attr('data-tid');
        layer.confirm('确认删除该推荐的课程吗？', {
          btn: ['确定','取消']
        }, function(){
			 $.post("/org.main.delTemplateOfCourse",{ cid:cid,tid:tid },function(r){
					if(r){
						location.reload();
						return false;
					}
                },"json");
        }, function(){
        });
    })

    $('#setTemplateBox-closeBtn').click(function() {
        $('.setDistriBoxBg').hide();
        $('.setTemplateBox').hide();
    })
	$('#template-course-data').each(function(){
        var tid=$(this).find('.set-class-li').attr('tid');
        var this_nums=$(this).find('.set-class-li').length;
		if(this_nums<=1){
			$('.maunal-div').css({ 'margin-right':'8px','margin-top':'12px'})
			$('.maunal-div>span').css({ 'right':'-5px' })
		}else{
			$('.maunal-div').css({ 'margin-right':'0'})
		}
		
	})
})
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
n