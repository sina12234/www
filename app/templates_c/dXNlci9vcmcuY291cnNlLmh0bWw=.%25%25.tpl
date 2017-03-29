<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>课程管理 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 课程管理 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
</head>
<body >
<?php echo tpl_function_part("/site.main.nav.home"); ?>
<section class="pd30">
<div class="container">
    <div class="row">
        <!-- left menu -->
        <?php echo tpl_function_part("/org.main.menu.course"); ?>
        <!-- right main -->
        <div class="right-main col-sm-9 col-md-16">
            <div class="tab-main">
                <div class="tab-hd fs14" id="courseType">
                    <a href="#" class="tab-hd-opt curr" attr="all"><?php echo tpl_modifier_tr('全部','site.course'); ?></a>
                    <a href="#"  class="tab-hd-opt" attr="online"><?php echo tpl_modifier_tr('直播课','site.course'); ?></a>
                    <a href="#" class="tab-hd-opt" attr="video"><?php echo tpl_modifier_tr('录播课','site.course'); ?></a>
                    <a href="#" class="tab-hd-opt" attr="offline"><?php echo tpl_modifier_tr('线下课','site.course'); ?></a>
                </div>
                <a href="/user.org.checkcourse" class="c-fr add-teacher-btn fs14">
                    <i class="add-btn-icon c-fl"></i><?php echo tpl_modifier_tr('新建课程','site.course'); ?>
                </a>
            </div>
            <div class="manage-path fs14 col-md-20 mt10">
				<dl class="order">
					<dt class="c-fl">
                        <span><?php echo tpl_modifier_tr('上架状态','site.course'); ?>：</span>
                        <select style="width:100px;" id="selshelf" class="divselected">
                            <option value="all" selected><?php echo tpl_modifier_tr('全部','site.course'); ?></option>
                            <option value="on"><?php echo tpl_modifier_tr('已上架','site.course'); ?></option>
                            <option value="off"><?php echo tpl_modifier_tr('未上架','site.course'); ?></option>
                        </select>
                    </dt>
                    <dt class="c-fr">
    					<span><?php echo tpl_modifier_tr('排序','site.course'); ?>：</span>
						<select style="width:100px;" id="selorder" class="divselected">
                            <option value="crtdesc" selected><?php echo tpl_modifier_tr('最新创建','site.course'); ?></option>
							<option value="crtasc"><?php echo tpl_modifier_tr('最早创建','site.course'); ?></option>
							<option value="regdesc"><?php echo tpl_modifier_tr('报名人数多','site.course'); ?></option>
							<option value="regasc"><?php echo tpl_modifier_tr('报名人数少','site.course'); ?></option>
						</select>
					</dt>
                    <div class="c-fr">
                        <div class="search-frame org-class-course">
                           <input name='keyword' class="search-input" id='sc_title' type="text" value="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["search"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["search"]; ?><?php }; ?>"  placeholder="<?php echo tpl_modifier_tr('搜索课程名称','site.course'); ?>">
                            <button class="search-box org-t-search-btn" id="subsearch">
                                <span class="search-icon" style="margin: 0;"></span>
                                <div class='t-list-img clear-icon' id="t-delt-btn" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["keyword"])){; ?>style="display:none;"<?php }; ?>></div>
                            </button>
                        </div>
                    </div>
                </dl>
            </div>
			<ul class="mode1" style="display:block">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course_list"])){; ?>
							<?php foreach(SlightPHP\Tpl::$_tpl_vars["course_list"] as SlightPHP\Tpl::$_tpl_vars["course"]){; ?>
							<li class="mb10">
							<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->admin_status!="normal"){; ?>
							<div class="edit-tip clearfix">
								<?php if((count(SlightPHP\Tpl::$_tpl_vars["course"]->class)) <=0){; ?>
								<p class="c-fl">
									<span class="edit-icon5 c-fl mr5"></span>
									<span class="mr20"><?php echo tpl_modifier_tr('该课程未完成创建','site.course'); ?></span>
								</p>
								<?php }else{; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->status == 'finished'){; ?>
										<p class="c-fl">
											<span class="edit-icon5 c-fl mr5"></span>
											<span class="mr20"><?php echo tpl_modifier_tr('该课程已完结','site.course'); ?></span>
										</p>
									<?php }; ?>
								<span class="org-user-course-bg-icon2 c-fr"><?php echo tpl_modifier_tr('未上架','site.course'); ?></span>
								<?php }; ?>
							</div>
							<?php }else{; ?>
                            <div class="edit-tip clearfix">
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->status == 'finished'){; ?>
									<p class="c-fl" style="padding-top:4px;">
										<span class="edit-icon5 c-fl mr5"></span>
										<span class="mr20" style="margin-right:80px;"><?php echo tpl_modifier_tr('该课程已完结','site.course'); ?></span>
									</p>
								<?php }; ?>
                                <span class="org-user-course-bg-icon1 c-fr"><?php echo tpl_modifier_tr('已上架','site.course'); ?></span>
                            </div>
							<?php }; ?>
							<div class="c-pic">
								<div class="pic">
									<a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
										<img class="imgPic" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->thumb_med)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["course"]->thumb_med); ?><?php }else{; ?>/assets/images/icon2.jpg<?php }; ?>">
                                        <?php if(SlightPHP\Tpl::$_tpl_vars["course"]->type_id==3){; ?>
										<div class="taped-icon"><?php echo tpl_modifier_tr('线下','LearningCenter'); ?></div>
										<?php }elseif((SlightPHP\Tpl::$_tpl_vars["course"]->type_id==2)){; ?>
										<div class="g-icon3"><?php echo tpl_modifier_tr('录播','LearningCenter'); ?></div>
										<?php }; ?>
                                    </a>
								</div>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->fee_type == 0){; ?>
								<!--<div class="g-icon1"></div> -->
								<?php }; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->status=="end"){; ?>
								<div class="g-icon3"></div>
								<?php }; ?>

							</div>
							<div class="c-info">
								<p class="item fs16"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank"><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->title; ?></a></p>
								<p class="item cDarkgray">
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->fee_type == 0){; ?>
								<span class="cGreen fs14"><?php echo tpl_modifier_tr('免费','site.course'); ?></span>
								<?php }else{; ?>
								<span class="cRed fs14">￥<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->fee->price; ?></span>
								<?php }; ?>
								| <?php echo tpl_modifier_tr('共','site.course'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->countsecs)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countsecs; ?><?php }else{; ?>0<?php }; ?><?php echo tpl_modifier_tr('章节','site.course'); ?></p>
								<p class="item cDarkgray">
								<?php echo tpl_modifier_tr('开设班级','site.course'); ?>
								(<?php if(empty(end(SlightPHP\Tpl::$_tpl_vars["course"]->class)->name)){; ?>
									<?php if((count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1) <=0){; ?>
										0
									<?php }else{; ?>
									<?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1; ?>
									<?php }; ?>
								<?php }else{; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class); ?><?php }; ?>)
								|
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->user_total > 0){; ?>
								<a href="/user.org.userCourse/?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
									<?php echo tpl_modifier_tr('学员','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->user_total; ?>)
								</a>
								<?php }else{; ?><?php echo tpl_modifier_tr('学员','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->user_total; ?>)
								<?php }; ?>
								</p>
								<p class="item cDarkgray">
									<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course"]->countvv)){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->countvv > 0){; ?>
											<a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
											<span class="g-icon11"></span>
											(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countvv; ?>)
											</a>
										<?php }else{; ?>
											<span class="g-icon11"></span>
											(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countvv; ?>)
										<?php }; ?>
									<?php }else{; ?>
										<span class="g-icon11"></span>
										0
									<?php }; ?>
									|
									<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course"]->countcomment)){; ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->countcomment > 0){; ?>
											<a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
												<?php echo tpl_modifier_tr('评论','course.list'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countcomment; ?>)
											</a>
										<?php }else{; ?>
											<?php echo tpl_modifier_tr('评论','course.list'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countcomment; ?>)
										<?php }; ?>
									<?php }else{; ?>
										<?php echo tpl_modifier_tr('评论','course.list'); ?>(0)
									<?php }; ?>
								</p>
							</div>
							<div class="c-class hidden-sm">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["course"]->status != 'finished'){; ?>
								<p class="item fs14 classEdit"
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->countsecs)){; ?>countsecs="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countsecs; ?>"<?php }else{; ?>countsecs="0"<?php }; ?>>
										<a href="user.org.editPlan.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" class="cDarkgray">
											<span class="edit-icon4 c-fl mr5"></span>
											<?php echo tpl_modifier_tr('班级管理','site.course'); ?>
										</a>
								</p>
                                <?php }; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class)){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->name)){; ?>
								<p class="item cDarkgray">
								<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->name; ?> |
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->teacher->pk_user)){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->teacher->pk_user]->real_name)){; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->teacher->pk_user]->real_name; ?>|
								<?php }else{; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->teacher->name; ?>|
								<?php }; ?>
								<?php }; ?>
								<?php /* 录播课*/?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->type_id!=2){; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->user_total >0){; ?>
								<a href="/user.org.userCourse/?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->class_id; ?>" target="_blank"><?php echo tpl_modifier_tr('学生','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->max_user; ?>)
								</a>
								<?php }else{; ?>
								<?php echo tpl_modifier_tr('学生','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->max_user; ?>)
								<?php }; ?>
								<?php }; ?>
								<?php /* 录播课*/?>

								</p>
								<?php }; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->name)){; ?>
								<p class="item cDarkgray">
								<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->name; ?> |

								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->teacher->pk_user]->real_name)){; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->teacher->pk_user]->real_name; ?>|
								<?php }else{; ?>
								<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->teacher->name; ?>|
								<?php }; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->user_total >0){; ?>
								<a href="/user.org.userCourse/?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->class_id; ?>" target="_blank"><?php echo tpl_modifier_tr('学生','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->max_user; ?>)</a>
								<?php }else{; ?>
								<?php echo tpl_modifier_tr('学生','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-2]->max_user; ?>)
								</p>
								<?php }; ?>
								<?php }; ?>

								<?php if(empty(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1]->name)){; ?>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->name)){; ?>
									<p class="item cDarkgray">
									<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->name; ?> |

									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->teacher->pk_user]->real_name)){; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["teaNameArr"][SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->teacher->pk_user]->real_name; ?>|
									<?php }else{; ?>
									<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->teacher->name; ?>|
									<?php }; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->user_total>0){; ?>
									<a href="/user.org.userCourse/?course_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>&class_id=<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->class_id; ?>" target="_blank">学生(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->max_user; ?>)</a>
									<?php }else{; ?>
									学生(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->user_total; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->class[count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-3]->max_user; ?>)
									</p>
									<?php }; ?>
									<?php }; ?>
								<?php }; ?>
							<?php }; ?>
							</div>
							<div class="c-edit fs14">
                                <?php if(SlightPHP\Tpl::$_tpl_vars["course"]->status != 'finished'){; ?>
								<p>
									<a class="org-edit clearfix" href="/user.org.editcourse.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon1 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('修改信息','site.course'); ?></span>
									</a>
								</p>
                                <?php }; ?>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class)){; ?>
                                <?php if(SlightPHP\Tpl::$_tpl_vars["course"]->admin_status!="normal"){; ?>
								<p>
									<a class="setadminstatus clearfix" href="" adminstatus="normal" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon3 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('上架','site.course'); ?></span>
									</a>
								</p>
								<?php }else{; ?>
								<p>
									<a class="setadminstatus clearfix" href="" adminstatus="offline" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon3_0 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('下架','site.course'); ?></span>
									</a>
								</p>
								<?php }; ?>
								<?php }; ?>
								<p>
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->type_id !=3){; ?>
									<a class="editVideo clearfix" href="/course.info.planlist/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>"
										<?php if((count(SlightPHP\Tpl::$_tpl_vars["course"]->class)) <=0){; ?>
										countclass="<?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class); ?>"
										<?php }else{; ?>
										countclass="<?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class); ?>"<?php }; ?>>
										<span class="edit-icon2 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('视频管理','site.course'); ?></span>
									</a>
								<?php }; ?>
								</p>
                            </div>
							</li>
							<?php }; ?>
							<?php }else{; ?>
							<div class="my-collect-no-class" style="padding-top:45px;">
								 <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
								 <p class="cGray fob"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["message"],'site.course'); ?></p>
							</div>
							<?php }; ?>
						</ul>
				<!--分页开始-->
                <div class="page-list" id="pagepage"></div>
				<!--分页结束-->
                </div>
			</div>
		</div>
	</div>
</section>
<script>
    $(document).ready(function() {
        addTabClass();
        var path = window.location.href;
        $(".divselected>option").prop("selected",false);
        $(".divselected>option").each(function(i,item){
            if(path.indexOf($(this).val())!==-1){
                $(this).attr("selected","true");
            }else{
                $(".divselected").val("crtasc");
            }
        });
        //找出黄色选项
        function courseType(){
            var ddDom = $("#courseType>a");
            var ddDomlength = ddDom.length-1;
            for (var i=0;i<=ddDomlength;i++){
                if(ddDom.eq(i).hasClass("curr")){
                    coursetype = ddDom.eq(i).attr('attr');
                    return coursetype;
                }
            }
        }
        //根据选项描黄
        function addTabClass(){
            $("#courseType>a").removeClass("curr");
            $("#courseType>a").each(function(i,item){
                if($(this).attr("attr")=="<?php echo SlightPHP\Tpl::$_tpl_vars["courseType"]; ?>"){
                     $(this).addClass("curr");
                }
            });
        }

        $("#courseType>a").click(function(){
            var p1 = $("#selshelf").children('option:selected').val();//这就是selected的值
            var p2 = $("#selorder").children('option:selected').val();//这就是selected的值
            var p3 = $(this).attr("attr");
            var s = $("#sc_title").val();
            if(s==""){
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3;//页面跳转并传参
                }else{
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3+"&s="+s;//页面跳转并传参
            }
            return false;
        })

        $('#selshelf').change(function(){
            var p1 = $(this).children('option:selected').val();//这就是selected的值
            var p2 = $("#selorder").children('option:selected').val();//这就是selected的值
            var p3 = courseType();
            var s = $("#sc_title").val();
            if(s==""){
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3;//页面跳转并传参
                }else{
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3+"&s="+s;//页面跳转并传参
            }
        })
        $('#selorder').change(function(){
            var p1 = $("#selshelf").children('option:selected').val();//这就是selected的值
            var p2 = $(this).children('option:selected').val();//这就是selected的值
            var p3 = courseType();
            var s = $("#sc_title").val();
            if(s==""){
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3;//页面跳转并传参
                }else{
                window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3+"&s="+s;//页面跳转并传参
            }
        })


        $("#subsearch").click(function() {
            var p1 = $("#selshelf").children('option:selected').val();//这就是selected的值
            var p2 = $("#selorder").children('option:selected').val();//这就是selected的值
            var p3 = courseType();
            var s = $("#sc_title").val();
            window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3+"&s="+s;//页面跳转并传参
            $(".t-list-img").show();
        })

        $(".t-list-img").click(function(){
            $(this).css("display","none");
            $("#sc_title").attr("value");
            $("#sc_title").val("");
            var p1 = $("#selshelf").children('option:selected').val();//这就是selected的值
            var p2 = $("#selorder").children('option:selected').val();//这就是selected的值
            var p3 = courseType();
            window.location.href="/user.org.course?shelf="+p1+"&orderby="+p2+"&type="+p3;//页面跳转并传参
        })
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->total)){; ?>
        page("pagepage","<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>",<?php echo SlightPHP\Tpl::$_tpl_vars["num"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["list"]->page; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["list"]->total; ?>);
    <?php }; ?>
    var selorder = $("#selorder");
    selcheck = "<?php echo SlightPHP\Tpl::$_tpl_vars["selorder"]; ?>"
    selorder.val(selcheck);

    var selshelf = $("#selshelf");
    shelfcheck = "<?php echo SlightPHP\Tpl::$_tpl_vars["shelf"]; ?>"
    selshelf.val(shelfcheck);

    });
    $(document).ready(function(){
        var s = $("#sc_title").val();
        if(s!=""){
            $(".t-list-img").show();
        }
        $("#sc_title").blur(function(){
        var keyin = $("#sc_title").val();
            if(keyin==""){
                console.log("s is"+keyin);
                $(".t-list-img").hide();
            }
        })

        $(".setadminstatus").click(function(){
            var adstatus = $(this).attr("adminstatus");
            var adcid = $(this).attr("cid");
            console.log("adcid="+adcid+"\n");
            console.log("adstatus="+adstatus);


            $.post("/user.org.setadminstatusajax."+adcid,{ adminstatus:adstatus },function(r){
                if(r.code==0){
                    layer.msg(r.error,{ time:2000 },function(){
                        location.reload();
                    }
                    );
                    return true;
                    }else{
                    layer.msg(r.error);
                    return false;
                }
            },"json");
            return false;
        })

        $(".classEdit").click(function(){
            var classedit = $(this).attr("countsecs");
            if(classedit==0){
                layer.msg("<?php echo tpl_modifier_tr('请设置章节','site.course'); ?>");
                return false;
            }else{
                return true;
            }
        })

        $(".editVideo").click(function(){
            var videoedit = $(this).attr("countclass");
            if(videoedit==0){
                layer.msg("<?php echo tpl_modifier_tr('请至少创建一个班级','site.course'); ?>");
                return false;
            }else{
                return true;
            }
        })
});
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
