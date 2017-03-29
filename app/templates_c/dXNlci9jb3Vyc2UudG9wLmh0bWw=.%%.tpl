<!-- tip -->
<div class="container">
	<div class="row">
		<h1 class="fs14 mt20">
		    <a href="/user.org.course">课程管理列表</a> > 
		    管理课程
		</h1>
	</div>
</div>
<?php if(empty(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumbMed'])){; ?>
<section>
<div class="tac course-hd-tip">
	<div class="container">
		<div class="row">
			您还没有添加课程图片，不能上架课程，请先添加图片
			<a href="/user.org.setcourseimg.<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>" title="">去添加</a>
			<span class="close-btn-tip"></span>
		</div>
	</div>
</div>
</section>
<?php }; ?>
<!-- /tip -->
<section class="pt20">
	<div class="container">
		<div class="row">
			<div class="gn-base-ct mb20 clearfix">
				<div class="col-md-10 pl0 pb15">
                    <a class="col-md-6 course-load-img p0 pos-rel" href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>">
						<img src="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['thumbMed']; ?>" type="<?php echo SlightPHP\Tpl::$_tpl_vars["type"]; ?>" width="160" height="90" alt="">
						<div class="taped-icon pos-abs" id="course-top-taped-icon" style="display:none;">线下课</div>
						<div class="g-icon3 pos-abs" id="course-top-lubo-icon" style="display:none;">录播课</div>
					</a>
					<div class="col-md-12">
                        <a href="/course.info.show/<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>" class="fs14 col-md-20 p0 course-title mb15"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['title']; ?></a>
						<p class="clearfix mb10 mt10">
                        <span class="fs14 fecolor c-fl"><?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price'] <= 0){; ?>免费<?php }else{; ?>￥<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['price']; ?><?php }; ?></span>
							<?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['publicType'] !=''){; ?>
							<span class="tryicon c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['publicType']; ?></span>
							<?php }; ?>
						</p>
						<p class="fs12">共<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['sectionNum']; ?>课时</p>
					</div>
				</div>
				<div class="col-md-7 fs14 pb15">
					<span class="mr30 mt40 c-fl">
						<i class="source-icon"></i> <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['commNum']; ?>分 <em class="ml20">|</em>
					</span>
					<span class="mr30 mt40 c-fl">
						<i class="trynum-icon"></i> <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['vv']; ?>次 <em class="ml20">|</em>
					</span>
					<span class="c-fl mt40">
						<i class="mannum-icon"></i> <?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['userTotal']; ?>个
					</span>
				</div>
				<div class="col-md-3 tac pb15">
                    <button <?php if(SlightPHP\Tpl::$_tpl_vars["courseInfo"]['adminStatus'] == 'normal'){; ?>class="fs14 mt30 green-button"<?php }else{; ?>class="fs14 mt30 gray-button"<?php }; ?> adminStatus="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['adminStatus']; ?>" courseId="<?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['courseId']; ?>" onclick="setCourseAdminStatus(this)"><?php echo SlightPHP\Tpl::$_tpl_vars["courseInfo"]['adminStatusName']; ?></button>
				</div>
			</div>
		</div>
	</div>	
</section>
