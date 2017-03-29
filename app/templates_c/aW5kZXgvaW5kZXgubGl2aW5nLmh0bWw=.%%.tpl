<ul class="list">
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planLive"])){; ?>
	<?php foreach(SlightPHP\Tpl::$_tpl_vars["planLive"] as SlightPHP\Tpl::$_tpl_vars["po"]){; ?>
	<li class="col-sm-5 col-xs-10 col-lg-4 col-md-4">
	<div class="border">
		<a class="live-c fs14" href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
			<div class="pic">
				<?php /*<p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["plan_course"][SlightPHP\Tpl::$_tpl_vars["po"]->course_id]->thumb_med); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>"></p>*/?>
				<p><img src="/live.live.thumb/<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>"></p>
				<div class="live-s-bg"></div>
				<div class="live-title">
					<?php /*<span class="icon time-icon"></span>
					<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_date; ?>*/?>
					<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?></span>
					<span class="c-fr hidden-sm hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->section_name; ?></span>
				</div>
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 2){; ?>
				<div class="liveing-icon">正在上课</div>
				<?php }; ?>
			</div>
			<div class="live-state fs12">
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->register == 1){; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->fee_type == 0){; ?>
				<span class="c-fl cGreen">免费课</span>
				<?php }else{; ?>
				<span class="c-fl cYellow">收费课</span>
				<?php }; ?>
				<?php }else{; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->fee_type == 0){; ?>
				<span class="c-fl cGreen">免费课<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>,可试看<?php }else{; ?>,需报名<?php }; ?></span>
				<?php }else{; ?>
				<span class="c-fl cYellow">收费课<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>,可试看<?php }else{; ?>,需报名<?php }; ?></span>
				<?php }; ?>
				<?php }; ?>
				<span class="c-fr hidden-sm hidden-xs">讲师:<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_name; ?><?php }; ?></span>
			</div>
			<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->status == 1){; ?>
			<div class="live-state-btn"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->start_date; ?>开始</div>
			<?php }else{; ?>
			<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->register == 1){; ?>
			<div class="live-state-btn"><button>进入课堂</button></div>
			<?php }else{; ?>
			<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>
			<div class="live-state-btn"><button>立即试看</button></div>
			<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->is_full == 1)){; ?>
			<div class="live-state-btn">报名已满></div>	
			<?php }else{; ?>
			<div class="live-state-btn">
				<button class="btn-gray register" data-classid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->class_id; ?>" data-courseid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_id; ?>" data-type="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->fee_type; ?>" data-domain="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->domain; ?>" data-planid=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>>报名学习</button>
			</div>
			<?php }; ?>
			<?php }; ?>								
			<?php }; ?>
		</a>
	</div>
	</li>
	<?php }; ?>
	<?php }; ?>
</ul>
<!--新增-->
<ul class="list" style="display:none">
	<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["planSplendid"])){; ?>
	<?php foreach(SlightPHP\Tpl::$_tpl_vars["planSplendid"] as SlightPHP\Tpl::$_tpl_vars["po"]){; ?>
	<li class="col-sm-5 col-xs-10 col-lg-4 col-md-4">
	<div class="border">
		<a class="live-c fs14" href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
			<div class="pic">
				<!--<em class="state_ing">精彩回放</em>-->
				<?php /*<p><img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["plan_course"][SlightPHP\Tpl::$_tpl_vars["po"]['course_id']]->thumb_med); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]['course_name']; ?>"></p>*/?>
				<p><img src="/live.live.thumb/<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>"></p>

				<div class="live-s-bg"></div>
				<div class="live-title">
					<span class="c-fl"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?></span>
					<span class="c-fr"><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->section_name; ?></span>
				</div>
				<!--div class="liveed-icon">精彩回放</div-->
			</div>
			<div class="live-state fs12">
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->register == 1){; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->fee_type == 0){; ?>
				<span class="c-fl cGreen">免费课</span>
				<?php }else{; ?>
				<span class="c-fl cYellow">收费课</span>
				<?php }; ?>
				<?php }else{; ?>
				<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->fee_type == 0){; ?>
				<span class="c-fl cGreen">免费课<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>,可试看<?php }else{; ?>,需报名<?php }; ?></span>
				<?php }else{; ?>
				<span class="c-fl cYellow">收费课<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>,可试看<?php }else{; ?>,需报名<?php }; ?></span>
				<?php }; ?>
				<?php }; ?>
				<span class="c-fr">讲师:<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_real_name; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["po"]->teacher_name; ?><?php }; ?></span>
			</div>
		</a>
		<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->register == 1){; ?>
		<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
			<div class="live-state-btn"><button>进入课堂</button></div>
		</a>
		<?php }else{; ?>
		<?php if(SlightPHP\Tpl::$_tpl_vars["po"]->try == 1){; ?>
		<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
			<div class="live-state-btn"><button>立即试看</button></div>
		</a>
		<?php }elseif((SlightPHP\Tpl::$_tpl_vars["po"]->is_full == 1)){; ?>
		<div class="live-state-btn">报名已满></div>	
		<?php }else{; ?>
		<a href="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_url; ?>" target="_blank" title="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_name; ?>">
		    <div class="live-state-btn">
			    <button class="btn-gray register" data-classid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->class_id; ?>" data-courseid="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->course_id; ?>" data-type="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->fee_type; ?>" data-domain="<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->domain; ?>" data-planid=<?php echo SlightPHP\Tpl::$_tpl_vars["po"]->plan_id; ?>>报名学习</button>
		    </div>
		</a>
		<?php }; ?>
		<?php }; ?>
	</div>
	</li>
	<?php }; ?>
	<?php }; ?>
</ul>
