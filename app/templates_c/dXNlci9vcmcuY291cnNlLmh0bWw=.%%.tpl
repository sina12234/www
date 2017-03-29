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
                <a href="/org.course.type" id="goCreateCourse" class="c-fr add-button fs14" target="_blank">
                    <i class="add-icon c-fl"></i><?php echo tpl_modifier_tr('新建课程','site.course'); ?>
                </a>
            <!-- 是否开启 -->
            	<div class="c-fr open-permiss clearfix mr20">
            		<div class="c-fl cleafix open-permiss-tip">
            			<span class="tip-icon c-fl mt10 mr5"></span>
            			<span class="fs14 c-fl">教师建课权限：</span>
            			<div class="open-permiss-infos fs12 p5">
            				<i class="permiss-border-arrow-up"></i>
            				<p>开启教师建课权限后，机构下的所有老师都可以建课，并且拥有与管理员相同的课程管理权限。</p>
            			</div>
            		</div>
            		<a href="javascript:;" onclick="optionCreate(1);" <?php if(SlightPHP\Tpl::$_tpl_vars["isTeacherCreateCourse"]==1){; ?> class="active c-fl" <?php }else{; ?> class="c-fl" <?php }; ?>>开启</a>
            		<a href="javascript:;" onclick="optionCreate(0);" <?php if(SlightPHP\Tpl::$_tpl_vars["isTeacherCreateCourse"]==0){; ?> class="active c-fl" <?php }else{; ?> class="c-fl" <?php }; ?>>关闭</a>
            	</div>
             <!-- 是否开启 -->
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
								<span class="org-user-course-bg-icon2 c-fr"><?php echo tpl_modifier_tr('未上架','site.course'); ?></span>
								<?php }; ?>
							</div>
							<?php }else{; ?>
                            <div class="edit-tip clearfix">
                                <span class="org-user-course-bg-icon1 c-fr"><?php echo tpl_modifier_tr('已上架','site.course'); ?></span>
                            </div>
							<?php }; ?>
							<div class="c-pic">
								<div class="pic">
									<a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
										<img class="imgPic" src="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->thumb_med)){; ?><?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["course"]->thumb_med); ?><?php }else{; ?>/assets_v2/img/course-load-img.jpg<?php }; ?>">
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
								<p class="item fs16"><a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->title; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->title; ?></a></p>
								<p class="item cDarkgray">
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->fee_type == 0){; ?>
								<span class="cGreen fs14"><?php echo tpl_modifier_tr('免费','site.course'); ?></span>
								<?php }else{; ?>
								<span class="cRed fs14">&yen;<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->fee->price; ?></span>
								<?php }; ?>
								<?php /*| <?php echo tpl_modifier_tr('共','site.course'); ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->countsecs)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countsecs; ?><?php }else{; ?>0<?php }; ?><?php echo tpl_modifier_tr('章节','site.course'); ?>*/?></p>
								<p class="item cDarkgray">
								<a href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
								<?php echo tpl_modifier_tr('开设班级','site.course'); ?>
								(<?php if(empty(end(SlightPHP\Tpl::$_tpl_vars["course"]->class)->name)){; ?>
									<?php if((count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1) <=0){; ?>
										0
									<?php }else{; ?>
									<?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class)-1; ?>
									<?php }; ?>
								<?php }else{; ?><?php echo count(SlightPHP\Tpl::$_tpl_vars["course"]->class); ?><?php }; ?>)
								</a>
								|
								<a href="/user.teacher.studentlist.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
								<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->user_total > 0){; ?>
								<?php echo tpl_modifier_tr('学员','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->user_total; ?>)
								<?php }else{; ?><?php echo tpl_modifier_tr('学员','site.course'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->user_total; ?>)
								<?php }; ?>
								</a>
								|
								<a href="/comment.manage.CommentList.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>" target="_blank">
									<?php if(isset(SlightPHP\Tpl::$_tpl_vars["course"]->countcomment)){; ?>
									<?php echo tpl_modifier_tr('评论','course.list'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->countcomment; ?>)
									<?php }else{; ?>
									<?php echo tpl_modifier_tr('评论','course.list'); ?> (0)
									<?php }; ?>
								</a>
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
								</p>
							</div>
							<div class="c-class hidden-sm">
								<p>
                                    <a class="del" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">删除</a>
									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class) && isset(SlightPHP\Tpl::$_tpl_vars["course"]->is_promote)&&SlightPHP\Tpl::$_tpl_vars["course"]->is_promote==1 && isset(SlightPHP\Tpl::$_tpl_vars["course"]->type_id) && SlightPHP\Tpl::$_tpl_vars["course"]->type_id !=3){; ?>
									|<a class="un-popularize cannel_promote" act="orgDelPromote"><?php echo tpl_modifier_tr('取消推广','site.course'); ?></a>
									<?php }elseif((!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class) &&isset(SlightPHP\Tpl::$_tpl_vars["course"]->is_promote)&&SlightPHP\Tpl::$_tpl_vars["course"]->is_promote==0&& SlightPHP\Tpl::$_tpl_vars["course"]->admin_status =='normal')){; ?>
									|<a class="popularize promote"><?php echo tpl_modifier_tr('推广','site.course'); ?></a>
									<?php }; ?>

									<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["course"]->class)){; ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["course"]->admin_status!="normal"){; ?>
									|<a class="putaway setadminstatus" adminstatus="normal" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>"><?php echo tpl_modifier_tr('上架','site.course'); ?></a>
									<?php }else{; ?>
									|<a class="putaway setadminstatus" adminstatus="offline" cid="<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>"><?php echo tpl_modifier_tr('下架','site.course'); ?></a>
									<?php }; ?>
									<?php }; ?>
								</p>
							</div>
							<div class="c-edit fs14">
								<p>
									<a class="org-edit clearfix" target="_blank" href="/org.course.edit.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('编辑课程','site.course'); ?></span>
									</a>
								</p>
								<p>
									<a class="org-edit clearfix" target="_blank" href="/org.course.plan.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon2 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('班级排课','site.course'); ?></span>
									</a>
								</p>
								<p>
									<a class="org-edit clearfix" target="_blank" href="/user.teacher.Statistics.<?php echo SlightPHP\Tpl::$_tpl_vars["course"]->course_id; ?>">
										<span class="edit-icon3 c-fl mr5"></span>
										<span class="c-fl"><?php echo tpl_modifier_tr('学习统计','site.course'); ?></span>
									</a>
								</p>
                            </div>
							</li>
							<?php }; ?>
							<?php }else{; ?>
							<li class="tac">
								<div class="my-collect-no-class tac" style="padding-top:45px;">
									 <img src="<?php echo utility_cdn::img('/assets_v2/img/platform/pet3.png'); ?>" alt="">
									 <p class="cGray fob"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["message"],'site.course'); ?></p>
								</div>
							</li>
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
<section id="popularize_comment" style="display: none;">
<div id="popularize_con" class="comment-layer form-horizontal popularize_form">
	<input type="hidden" name="courseId" value=""/>
	<input type="hidden" name="price" value="" />
	<div class="form-group col-md-20">
        <label class="col-md-5">课程名称:</label>
        <div class="col-md-13 course-name row course_title"></div>
    </div>
    <div class="form-group col-md-20 course_price" style="display: none;">
        <label class="col-md-5">成本价:</label>
		<input class="col-md-4" id="costPrice"/>
		<label class="col-md-1">元</label>
		<p class="ui-note help-inline pd0" id="errInfo">(不能高于课程原价<span data-price=""></span>元)</p>
	</div>
	 <div class="course_free col-md-20 tac" style="display: none;">
       该课程为免费课程，推广优质的免费课能为机构带来品牌效应和生源
    </div>
	<div class="course_free1 col-md-20 tac" style="display: none;">
		该课程还未上架，不能进行推广
	</div>
	<div class="col-md-20 tac mt20" id="ensure-btn">
		<button class="btn mr10 promote_confirm">确认</button>
	</div>

</div>
</section>
<script>
    $(document).ready(function() {
		var errInfo1 = '';

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
		var title;
        $(".popularize").click(function(){
			var title = $(this).parents().parent("li").find("a").eq(1).text();
			var price = $(this).parents().parent("li").find("span").eq(1).text();
			var courseId = $(this).next().attr("cid");
			$("input[name='courseId']").val(courseId);
			$("input[name='price']").val(price);
			$(".course_title").html(title);
			$.post("/course.promote.GetCoursePromoteById",{ optionVal:courseId },function(r){
			var str ='';
			if(r.code==1) {
				$(r.data).each(function (i, item) {
					str = item.fee_type;

				});
				if (str == '0') {
					$(".course_price").hide();
					$(".course_free").show();
					$(".course_free1").hide();
					$("#ensure-btn").show();
				} else {
					$(".course_free").hide();
					$(".course_free1").hide();
					$(".course_price").show();
					$("#ensure-btn").show();
					$(".course_price").find("span").text(price);
					errInfo1 = $("#errInfo").html();
				}
			}else{
				$(".course_price").hide();
				$(".course_free").hide();
				$(".course_free1").show();
				$("#ensure-btn").hide();
			}
			},"json");
            layer.open({
                type: 1,
                closeBtn: 1,
                area: ['430px','280px'],
				title: '推广课程',
                shadeClose: true,
                content:$('#popularize_comment')
            });
        })

		 $('.promote_confirm').click(function() {
			var courseId   = $("input[name='courseId']").val();
			var costPrice  = parseInt($("#costPrice").val()*100);
			var price = $("input[name='price']").val();
			 price = parseInt(price.substr(1)*100);
			 if(price>0) {
				if ((costPrice > price) || (costPrice < 0)) {
					$("#errInfo").css('color', 'red');
					$("#errInfo").html(errInfo1);
					return false;
				}
				if (isNaN(costPrice)) {
					$("#errInfo").css('color', 'red');
					$("#errInfo").text("价格只能输入数字");
					return false;
				} else {
					$("#errInfo").css('color', '');
					$("#errInfo").html(errInfo1);
				}
			}
			$.post("/course.promote.addPromoteCourse",{ courseId:courseId,costPrice:costPrice },function(r){
				if(r.result['code']==0){
					layer.closeAll();
					layer.msg('推广成功', { icon: 1 });
					$("#costPrice").val('');
					$("#price").val('');
					$("#siteid").val('');
					$("#selectCourse").val('');
					$("#chargeState").hide();
					location.reload(true);
				}
				if(r.result['code']==-4){
					layer.msg(r.result['msg'], { icon: 1 });
				}
				if(r.result['code']==-2){
					layer.msg(r.result['msg'], { icon: 1 });
				}

			},'json');
		})
		//取消推广
        $(".cannel_promote").click(function(){
			var cid 	= $(this).next().attr("cid");
			var ctitle 	= $(this).parents().parent("li").find("a").eq(1).text();
			var act = $(this).attr("act");
            layer.confirm("确定取消推广课程吗?", {
                btn: ["确定","取消"], //按钮
            }, function(){
                $.post("/course.promote.changePromoteCourseAjax/"+act,{ cid:cid,ctitle:ctitle},function(ret){
                    if (ret.code==0){
                        window.location.reload();
                        layer.msg(ctitle+'取消成功', { icon: 1 });
                    } else {
                        layer.msg(ctitle+'取消失败', { icon: 1 });
                    }
                },"json");

            }, function(){
                //取消callback
            });
        })
		//删除课程
		$(".del").click(function(){
			var courseId = $(this).attr("cid");
			layer.confirm('确定删除该课程', {
			  btn: ['确定','取消'],
			  title: ['删除课程']
			}, function(){
				$.post('/org.course.delcourse', { courseId:courseId }, function(r){
					if(r.code == 0){
						layer.msg('删除成功！');
						window.location.reload();
					}else{
						layer.msg('删除失败！');
					}
				},'json');
			}, function(){
				layer.closeAll();
			});
		})
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
		/*
        $(".classEdit").click(function(){
            var classedit = $(this).attr("countsecs");
            if(classedit==0){
                layer.msg("<?php echo tpl_modifier_tr('请设置章节','site.course'); ?>");
                return false;
            }else{
                return true;
            }
        })
		*/
        $(".editVideo").click(function(){
            var videoedit = $(this).attr("countclass");
            if(videoedit==0){
                layer.msg("<?php echo tpl_modifier_tr('请至少创建一个班级','site.course'); ?>");
                return false;
            }else{
                return true;
            }
        })
		$("#goCreateCourse").click(function(){
			$.post("/user.org.sourceurl",{  },function(r){

			});
		});
});

function optionCreate(type){
	$.post("/user.org.openCreate",{ type:type },function(r){
		if(r==1){
			layer.msg("<?php echo tpl_modifier_tr('操作成功','site.course'); ?>");
		}else{
			layer.msg("<?php echo tpl_modifier_tr('操作失败','site.course'); ?>");
		}
		location.reload();
	});
}
</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
