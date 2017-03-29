<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"])){; ?>
<div class="container">
	<div class="row">
		<div class="box-title">
			<div class="box-title-left hidden-xs"></div>
			<div class="box-title-name fs24"><?php echo tpl_modifier_tr('最近直播','site.index'); ?></div>
			<div class="box-title-right hidden-xs"></div>
			<p class="clearfix"></p>
			<p class="square-red"><i></i></p>
		</div>
		<ul class="tab-live col-lg-20 col-md-20 col-sm-20 col-xs-20 fs14" id="tab-live">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['unstarted'])){; ?>
			<li class="curr c-fl">
				<a href="javascript:void(0);"><?php echo tpl_modifier_tr('即将开始','site.index'); ?></a>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['finished'])){; ?>
					<span class="ml10">|</span>
				<?php }; ?>
			</li>
			<?php }; ?>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['finished'])){; ?>
			<li class="c-fl">
				<a href="javascript:void(0);"><?php echo tpl_modifier_tr('精彩回放','site.index'); ?></a>
			</li>
			<?php }; ?>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['unstarted']) or !empty(SlightPHP\Tpl::$_tpl_vars["planList"]['finished'])){; ?>
				<a href="/live.list" class="c-fr"><?php echo tpl_modifier_tr('更多','site.index'); ?>></a>
			<?php }; ?>
		</ul>
		<div id="tab-list">
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['unstarted'])){; ?>
			<ul class="tab-list">
			<?php foreach(SlightPHP\Tpl::$_tpl_vars["planList"]['unstarted'] as SlightPHP\Tpl::$_tpl_vars["uplv"]){; ?>
				<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10">
				<a href="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["uplv"]->price_resell)){; ?>/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->plan_id; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->oid; ?><?php }else{; ?>/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->plan_id; ?><?php }; ?>" target="_blank">
					<div class="tab-li-img fs14">
						<img src="/live.live.thumb/<?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->plan_id; ?>" alt="">
						<p class="tab-tit-bg"></p>
						<p class="tab-tit">
						<em class="tab-title"><?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->course_name; ?></em>
						<em class="tab-chapter ter"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["uplv"]->section_name,'site.index'); ?></em>
						</p>
						<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->status==2){; ?>
						<span class="living-icon fs12"><?php echo tpl_modifier_tr('正在上课','LearningCenter'); ?></span>
						<?php }; ?>
					</div>
					<div class="tab-li-info fs12">
						<?php if(isset(SlightPHP\Tpl::$_tpl_vars["uplv"]->price_resell)){; ?>
								<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uplv"]->price_resell)){; ?>
										<span class="c-fl cYellow">
										<?php echo tpl_modifier_tr('收费课','LearningCenter'); ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->try==1){; ?>
										，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
										<?php }else{; ?>
										，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
										<?php }; ?>
										</span>
								<?php }else{; ?>
										<span class="c-fl cGreen">
										<?php echo tpl_modifier_tr('免费课','LearningCenter'); ?>
										<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->try==1){; ?>
										，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
										<?php }else{; ?>
										，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
										<?php }; ?>
										</span>
								<?php }; ?>
						<?php }else{; ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->fee_type==0){; ?>
								<span class="c-fl cGreen">
									<?php echo tpl_modifier_tr('免费课','LearningCenter'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->try==1){; ?>
									，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
									<?php }else{; ?>
									，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
									<?php }; ?>
								</span>
								<?php }else{; ?>
								<span class="c-fl cYellow">
									<?php echo tpl_modifier_tr('收费课','LearningCenter'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->try==1){; ?>
									，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
									<?php }else{; ?>
									，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
									<?php }; ?>
								</span>
								<?php }; ?>
						<?php }; ?>
						
						<span class="c-fr hidden-xs"><?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>:<?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->admin_real_name; ?></span>
					</div>
					<?php if(SlightPHP\Tpl::$_tpl_vars["uplv"]->status==1){; ?>
					<div class="tab-li-btn fs12">
						<span ><?php echo SlightPHP\Tpl::$_tpl_vars["uplv"]->start_time; ?> </span>
					</div>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["uplv"]->register==1){; ?>
					<div class="tab-li-btn fs12">
						<button><?php echo tpl_modifier_tr('进入课堂','LearningCenter'); ?></button>
					</div>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["uplv"]->try==1){; ?>
					<div class="tab-li-btn fs12">
						<button><?php echo tpl_modifier_tr('立即试看','LearningCenter'); ?></button>
					</div>
					<?php }else{; ?>
					<div class="tab-li-btn fs12">
						<button class="btn-gray"><?php echo tpl_modifier_tr('报名学习','LearningCenter'); ?></button>
					</div>
					<?php }; ?>
				</a>
				</li>
				<?php }; ?>
			</ul>
			<?php }; ?>
			<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['finished'])){; ?>
			<ul class="tab-list" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planList"]['unstarted'])){; ?>style="display:none"<?php }; ?>>
				<?php foreach(SlightPHP\Tpl::$_tpl_vars["planList"]['finished'] as SlightPHP\Tpl::$_tpl_vars["fplv"]){; ?>
				<li class="col-lg-5 col-md-5 col-sm-10 col-xs-10">
				<a href="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["fplv"]->price_resell)){; ?>/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->plan_id; ?>/<?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->oid; ?><?php }else{; ?>/course.plan.play/<?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->plan_id; ?><?php }; ?>" target="_blank">
					<div class="tab-li-img fs14">
						<img src="/live.live.thumb/<?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->plan_id; ?>" alt="">
						<p class="tab-tit-bg"></p>
						<p class="tab-tit">
						<em class="tab-title"><?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->course_name; ?></em>
						<em class="tab-chapter"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["fplv"]->section_name,'site.index'); ?></em>
						</p>
					</div>
					<div class="tab-li-info fs12">
						<?php if(isset(SlightPHP\Tpl::$_tpl_vars["fplv"]->price_resell)){; ?>
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["fplv"]->price_resell)){; ?>
								<span class="c-fl cYellow">
								<?php echo tpl_modifier_tr('收费课','LearningCenter'); ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->try==1){; ?>
								，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
								<?php }else{; ?>
								，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
								<?php }; ?>
								</span>
							<?php }else{; ?>
								<span class="c-fl cGreen">
									<?php echo tpl_modifier_tr('免费课','LearningCenter'); ?>
									<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->try==1){; ?>
									，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
									<?php }else{; ?>
									，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
									<?php }; ?>
								</span>
							<?php }; ?>
						
						<?php }else{; ?>
							<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->fee_type==0){; ?>
							<span class="c-fl cGreen">
								<?php echo tpl_modifier_tr('免费课','LearningCenter'); ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->try==1){; ?>
								，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
								<?php }else{; ?>
								，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
								<?php }; ?>
							</span>
							<?php }else{; ?>
							<span class="c-fl cYellow">
								<?php echo tpl_modifier_tr('收费课','LearningCenter'); ?>
								<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->try==1){; ?>
								，<?php echo tpl_modifier_tr('可试看','LearningCenter'); ?>
								<?php }else{; ?>
								，<?php echo tpl_modifier_tr('需报名','LearningCenter'); ?>
								<?php }; ?>
							</span>
							<?php }; ?>
						
						<?php }; ?>
						
						<span class="c-fr hidden-xs"><?php echo tpl_modifier_tr('讲师','LearningCenter'); ?>:<?php echo SlightPHP\Tpl::$_tpl_vars["fplv"]->admin_real_name; ?></span>
					</div>
					<?php if(SlightPHP\Tpl::$_tpl_vars["fplv"]->status==1){; ?>
					<div class="tab-li-btn fs12">
						<?php if(strtotime(SlightPHP\Tpl::$_tpl_vars["fplv"]->start_time)-strtotime(date("Y-m-d"))<86400 && strtotime(SlightPHP\Tpl::$_tpl_vars["fplv"]->start_time)-strtotime(date("Y-m-d"))>0){; ?>
						<span><?php echo tpl_modifier_tr('今日','LearningCenter'); ?><?php echo date("H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["fplv"]->start_time)); ?><?php echo tpl_modifier_tr('上课','LearningCenter'); ?></span>
						<?php }else{; ?>
						<span ><?php echo date("n-j H:i",strtotime(SlightPHP\Tpl::$_tpl_vars["fplv"]->start_time)); ?></span>
						<?php }; ?>
					</div>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["fplv"]->register==1){; ?>
					<div class="tab-li-btn fs12">
						<button><?php echo tpl_modifier_tr('进入课堂','LearningCenter'); ?></button>
					</div>
					<?php }elseif( SlightPHP\Tpl::$_tpl_vars["fplv"]->try==1){; ?>
					<div class="tab-li-btn fs12">
						<button><?php echo tpl_modifier_tr('立即试看','LearningCenter'); ?></button>
					</div>
					<?php }else{; ?>
					<div class="tab-li-btn fs12">
						<button class="btn-gray"><?php echo tpl_modifier_tr('报名学习','LearningCenter'); ?></button>
					</div>
					<?php }; ?>
				</a>
				</li>
				<?php }; ?>
			</ul>
			<?php }; ?>
		</div>
	</div>
</div>
</section>
<script>
$(function(){
    $(".tab-live li").hover(function(){
        $(this).addClass("curr");
        $(this).siblings().removeClass("curr");
        $("#tab-list").find("ul:eq("+$(this).index()+")").show().siblings().hide()
    },function(){ })
})
</script>
<?php }; ?>
