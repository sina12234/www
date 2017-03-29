<!DOCTYPE html>
<html>
<head>
<title>个人中心 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="个人中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">

    <?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
	<?php echo tpl_function_part("/index.main.usernav/setting"); ?>
    <!--studentPortal start-->
    <section class="p20 container">
        <!-- leftmenu start-->
	  <?php echo tpl_function_part("/index.main.menu"); ?>
        <!-- leftmenu end -->
        <!--right start-->
        <div class="col-lg-16 col-md-16 col-sm-20 col-xs-20">
        <!--mob-->
        <p class="mob-nav hidden-lg hidden-md">
            <a href="/index.user.info" class="col-xs-6 col-sm-6">基础资料</a>
            <a href="/index.user.uploadPic/1" class="col-xs-6 col-sm-6">修改头像</a>
        </p>
          <!--con start-->
          <div class="right-content mainCon">
            <ul class="form fs14 mt40 wrap-form-select wrap-error-tip">
              <?php /*  <li class="pd-b20 row">
					  <div class="label col-lg-4 col-md-5 col-xs-8 col-sm-4" style="text-align:right;"><em class="red"></em> 本人头像：</div>
					  <div class="label-for col-lg-16 col-xs-12 col-md-15 col-sm-16 row">
						<div class="perinfo-photo">
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->avatar->large)){; ?>
						  <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->avatar->large; ?>" width="115" height="115" alt=""/>
							<?php }else{; ?>
						  <img src="<?php echo utility_cdn::img('/assets_v2/img/photo-default.jpg'); ?>" width="115" height="115" alt=""/>
						  <?php }; ?>
						  <a id="alert1" class="pip-modifylink col-xs-20" href="/index.user.uploadPic/1">修改头像</a>
						</div>
						<p class="cGray clear">请上传清晰的真实头像</p>
					  </div>
                </li>*/?>
                <li class="row item_nickname">
                  <div class="label col-lg-4 col-md-5 col-xs-6 col-sm-4" style="text-align:right;"><em class="red">*</em> 昵称：</div>
                  <div class="label-for col-lg-15 col-xs-12 col-md-15 " >
                    <input type="text" id="nickname"  name="name" placeholder="最多15个字符" class="col-sm-10 col-lg-5 col-md-10 " value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->name; ?>">
					<span class="tip1 cRed"></span>
                  </div>
				</li>
                <li class="row realname">
                  <div class="label col-lg-4 col-md-5 col-xs-6 col-sm-4" style="text-align:right;"><em class="red">*</em> 真实姓名：</div>
                  <div class="label-for col-lg-15 col-xs-12 col-md-15 ">
                    <input type="text" id="real_name"  name="real_name" placeholder="最多5个汉字" class="col-sm-10 col-lg-5 col-md-10" value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->profile->real_name; ?>">
					<span class="tip2 cRed"></span>
                  </div>
                </li>
                <li class="row">
                  <div class="label col-lg-4 col-md-5 col-xs-6 col-sm-4" style="text-align:right;"><em class="red">*</em> 性别：</div>
                  <div class="label-for col-lg-16 col-xs-12 col-md-15 ">
                    <label for="s1"><input <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="male"){; ?>checked<?php }; ?>  type="radio" name="gender" id="s1" value="male" style="margin-top:5px;"  > 男</label>
                    <label for="s2"><input <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="female"){; ?>checked<?php }; ?> type="radio" name="gender" id="s2" value="female" style="margin-top:5px;"  > 女</label>
					<span class="tip3 cRed"></span>
                  </div>
                </li>
                <li class="row ">
                  <div class="label col-lg-4 col-md-5 col-xs-6 col-sm-4" style="text-align:right;">出生日期：</div>
                  <div class="label-for col-lg-16  col-xs-14 col-md-15 col-sm-16" id="birthday" >
                   <select name="year" id="" class="col-sm-3  col-xs-6">
                    </select>
                    <select name="month" id="" class="col-sm-3 ml10  col-xs-5">
                    </select>
                    <select name="day" id="" class="col-sm-3 ml10  col-xs-5">
                    </select>
                  </div>
                </li>
                <li class="row">
                  <div class="label col-lg-4 col-md-5 col-sm-4 col-xs-6" style="text-align:right;"><em class="red">*</em> 常住地区：</div>
                  <div class="label-for col-lg-16 col-xs-14 col-md-15 col-sm-16">
                    <select name="region_level0" id="level0" class="col-sm-3 col-xs-6">
					  <option value="">请选择</option>
					  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level0"])){; ?>
					  <?php foreach(SlightPHP\Tpl::$_tpl_vars["level0"] as SlightPHP\Tpl::$_tpl_vars["region"]){; ?>
					  <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0==SlightPHP\Tpl::$_tpl_vars["region"]->region_id){; ?> selected <?php }; ?> value="<?php echo SlightPHP\Tpl::$_tpl_vars["region"]->region_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["region"]->name; ?></option>
					  <?php }; ?>
					<?php }; ?>
                    </select>
                    <select name="region_level1" id="level1" class="col-sm-3 col-xs-6 ml10">
                      <option value="">请选择</option>
                    </select>
					<select style="display:none" class="col-sm-3 col-xs-6 ml10" name="region_level2" id="level2">
					  <option value="">请选择</option>
					</select>
					<span id='region1' class="cRed"></span>
                  </div>
                </li>
                <li class="row">
                  <div class="label col-lg-4 col-md-5 col-sm-4 col-xs-6" style="text-align:right;"><em class="red">*</em> 教育阶段：</div>
                  <div class="label-for col-lg-16 col-xs-14 col-md-15 col-sm-16">
                    <select name="school_type" id="school_type" class="col-sm-3 col-xs-5">
                      <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==2){; ?>selected<?php }; ?>  value="2">学前</option>
                      <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==1){; ?>selected<?php }; ?>  value="1">小学</option>
                      <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==6){; ?>selected<?php }; ?>  value="6">中学</option>
                      <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==3){; ?>selected<?php }; ?>  value="3">大学</option>
                    </select>
                    <select name="school_id" id="school" class="col-sm-3 col-xs-6 ml10">
                      <option value="">学校</option>
                    </select>
                    <select name="grade" id="school_grade" class="col-sm-3 ml10 col-xs-5">
                      <option value="">年级</option>
                    </select>
					<span id='grades1' class="cRed c-fl"></span>
                    <p class="new-cGray clear col-xs-20" style="padding:0;">如果没有您的学校,请<a class="cYellow" href="/index.about">联系我们</a>添加</p>
                    <!--
                    	<a class="cYellow  about-us-btn" href="javscript:;">联系我们</a>
                    -->
                  </div>
                </li>
                <li class="row item_6" style="padding-bottom:20px;">
                  <div class="label col-lg-4 col-md-5 text-right col-sm-4 col-xs-8" style="padding-left:15px;"><em class="red"></em> 一句话简介：</div>
                  <div class="label-for col-lg-15 col-md-15">
				  <input type="text" class="col-sm-7"  id="brief_desc" name="brief_desc" value="<?php if(SlightPHP\Tpl::$_tpl_vars["isTeacher"]=='1'){; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc; ?><?php }; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->profile->desc; ?><?php }; ?>">&nbsp;&nbsp;
				  <span class="cGray" id="span_brief_desc">最多输入20个汉字</span>
				  </div>
                </li>
				<!--教师资料部分-->
				<?php if(SlightPHP\Tpl::$_tpl_vars["isTeacher"]=='1'){; ?>
				<li class="fs16 fob lh22 teacher-modul">教师资料</li>
				<li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-7 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">毕业院校：</div>
					<div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-14 row">
					<input type="text" class="col-lg-10 col-md-10 col-sm-10 col-xs-20" id="college" name="college" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->college)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->college; ?><?php }; ?>">
					<span class="cGray" style="margin-left:10px;">最多输入15个汉字</span>
					<span class="tip1 cRed"></span>
					</div>
                </li>
                <li class="pd-b10">
                     <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-5 text-right">学历：</div>
                    <div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-16 row">
                    <select name="diploma" id="" class="col-sm-5">
                     <option value="-1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==-1){; ?>selected<?php }; ?>>不显示</option>
					<option value="1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==1){; ?>selected<?php }; ?>>大专</option>
					<option value="2" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==2){; ?>selected<?php }; ?>>本科</option>
					<option value="3" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==3){; ?>selected<?php }; ?>>硕士</option>
					<option value="4" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==4){; ?>selected<?php }; ?>>博士</option>
					</select>
					</div>
                </li>
				<li>
					<div class="label col-lg-4 col-md-4 col-sm-3 col-xs-5 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">教龄：</div>
					<div class="label-for col-lg-16 col-xs-16 row">
						<select name='years'>
							<?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1;SlightPHP\Tpl::$_tpl_vars["i"]<31;SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
							<option value='<?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==SlightPHP\Tpl::$_tpl_vars["i"]){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>年</option>
							<?php }; ?>
							<option value='31' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==31){; ?>selected<?php }; ?>>30年以上</option>
						</select>
					</div>
				</li>
				<li class="item_1" id="class-status">
					<div class="label col-sm-3 col-xs-7 col-md-4 col-lg-4 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;教学阶段：</div>
					<div class="label-for col-sm-10 col-xs-20 col-lg-16 row">
					<label for="s1"><input type="checkbox" name="scopes[]" id="s1" value="preschool" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('preschool',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s1">&nbsp;学前
					</label>
						<label for="s2"><input type="checkbox" name="scopes[]" value="primary" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('primary',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s2">&nbsp;小学</label>
						<label for="s3"><input type="checkbox" name="scopes[]" value="junior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('junior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s3">&nbsp;初中</label>
						<label for="s4" ><input type="checkbox" name="scopes[]" value="senior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('senior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s4">&nbsp;高中</label>
						<span class="tip1 cRed"></span>
					</div>
				</li>
				<li class="item_2">
					<div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;擅长学科：</div>
					<div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-20 row">
					<div class="t-subj-content col-lg-10 col-md-13 col-sm-10 col-xs-15" id="t-subj-content"  style="border:1px solid #ccc">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data)){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->name)){; ?><div class="col-sm-6 delect-subj pd0 col-xs-5 col-lg-5 col-md-5 col-xs-5" contenteditable="false">
						<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?><input type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>" name="good_subject[]"></div>
						<?php }; ?>
						<?php }; ?>
					<?php }; ?>

					</div>
					<div class="t-subjects-but col-lg-10 col-md-5 col-sm-10 col-xs-5" style="padding:0;line-height:35px">&nbsp;&nbsp;<input id="click-but" type="button" value="添加标签" class="col-lg-4 col-md-15 col-sm-5 col-xs-16" style="padding:0;">&nbsp;<span class="cGray visible-lg col-lg-14">至少选择一项，最多选择三项</span>&nbsp;<span class="visible-sm cGray col-sm-13">至选少一项,最多三项</span></div></div>
				</li>
				<li>
					<div class="label col-sm-3" style="padding:0px;"></div>
					<div class="col-sm-14 col-md-offset-4 t-subjects-list pd0 mt10 col-md-14" id="t-subjects-list">
				   <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setArr"])){; ?>
				   <?php foreach(SlightPHP\Tpl::$_tpl_vars["setArr"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<div class="col-sm-1 pd0 <?php if(isset(SlightPHP\Tpl::$_tpl_vars["tagArr"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag,SlightPHP\Tpl::$_tpl_vars["tagArr"])){; ?>on<?php }; ?>" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></div>
					<?php }; ?>
					<?php }; ?>     
					</div>
				</li>
				<li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-7 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">我的头衔：</div>
					<div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-14 row">
					<input type="text" class="col-lg-10 col-md-10 col-sm-10 col-xs-20" id="title" name="title" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->title; ?><?php }; ?>">
					<span class="cGray" style="margin-left:10px;">最多输入10个汉字</span>
					<span class="tip1 cRed"></span>
					</div>
                </li>
				<li>
					<div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">教师简介：</div><div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-20 row"><textarea name="desc" id="desc" cols="30" rows="5" class="col-lg-14 col-md-15 col-sm-20 col-xs-20" placeholder=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->desc; ?><?php }; ?></textarea>
					<div class="col-lg-20 cGray c-fl">用一段话来介绍自己,让学生和家长更好的了解你.最多&nbsp;&nbsp;1000&nbsp;&nbsp;字.
					<span class="tip1 cRed"></span>
					</div>
					<p class="desc-intr"></p>
					</div>
					
				</li>
				<?php }; ?>
				<li style="padding-bottom:20px;">
                    <div class="label col-lg-4 col-sm-4 col-md-5"> </div><div class="label-for col-lg-16 col-xs-8 col-sm-10 offset-sm-5 col-md-15 row"><button class="btn blue-btn col-lg-4 col-md-8 col-sm-10 col-xs-20" id="submit">保存</button></div>
                </li>
				<input type="hidden" name="isTeacher" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["isTeacher"]; ?><?php }; ?>">
			<!-- 教师资料部分 
				<li class="fs16 fob teacher-modul">教师资料</li>
                <form  id="form"  method="post" autocomplete="off">
                <input type="password" style="display:none;">
                <li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-7 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">毕业院校：</div>
					<div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-14 row">
					<input type="text" class="col-lg-10 col-md-10 col-sm-10 col-xs-20" id="college" name="college" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->college)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->college; ?><?php }; ?>">
					<span class="cGray" style="margin-left:10px;">最多输入15个汉字</span>
					<span class="tip1 cRed"></span>
					</div>
                </li>
                <li class="pd-b10">
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-5 text-right">学历：</div>
                    <div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-16 row">
                        <select name="diploma" id="" class="col-sm-5">
                            <option value="-1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==-1){; ?>selected<?php }; ?>>不显示</option>
                            <option value="1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==1){; ?>selected<?php }; ?>>大专</option>
                            <option value="2" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==2){; ?>selected<?php }; ?>>本科</option>
                            <option value="3" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==3){; ?>selected<?php }; ?>>硕士</option>
                            <option value="4" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==4){; ?>selected<?php }; ?>>博士</option>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-5 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">教龄：</div>
                    <div class="label-for col-lg-16 col-xs-16 row">
                        <select name='years'>
                            <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1;SlightPHP\Tpl::$_tpl_vars["i"]<31;SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                            <option value='<?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==SlightPHP\Tpl::$_tpl_vars["i"]){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>年</option>
                            <?php }; ?>
                            <option value='31' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==31){; ?>selected<?php }; ?>>30年以上</option>
                        </select>
                    </div>
                </li>
                <?php /*<li>
                    <div class="label col-sm-2">教学阶段：</div>
                    <div class="label-for col-sm-10">
                        <label for="s1"><input type="radio" name="school" id="s1">小学</label>
                        <label for="s2"><input type="radio" name="school" id="s2">初中</label>
                        <label for="s3" ><input type="radio" name="school" id="s3">高中</label>
                    </div>
                </li>*/?>
                <li class="item_1">
                    <div class="label col-sm-3 col-xs-7 col-md-4 col-lg-4 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;教学阶段：</div>
                    <div class="label-for col-sm-10 col-xs-20 col-lg-16 row">
					<label for="s1"><input type="checkbox" name="scopes[]" id="s1" value="preschool" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('preschool',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s1">&nbsp;学前
					</label>
                        <label for="s2"><input type="checkbox" name="scopes[]" value="primary" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('primary',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s2">&nbsp;小学</label>
                        <label for="s3"><input type="checkbox" name="scopes[]" value="junior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('junior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s3">&nbsp;初中</label>
                        <label for="s4" ><input type="checkbox" name="scopes[]" value="senior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('senior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s4">&nbsp;高中</label>
						<span class="tip1 cRed"></span>
                    </div>
                </li>

                <li class="item_2">
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;擅长学科：</div>
                    <div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-20 row">
					<div class="t-subj-content col-lg-10 col-md-13 col-sm-10 col-xs-15" id="t-subj-content"  style="border:1px solid #ccc">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data)){; ?>
						<?php foreach(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->name)){; ?><div class="col-sm-6 delect-subj pd0 col-xs-5 col-lg-5 col-md-5 col-xs-5" contenteditable="false">
						<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?><input type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>" name="good_subject[]"></div>
						<?php }; ?>
						<?php }; ?>
					<?php }; ?>

					</div>
                    <div class="t-subjects-but col-lg-10 col-md-5 col-sm-10 col-xs-5" style="padding:0;line-height:35px">&nbsp;&nbsp;<input id="click-but" type="button" value="添加标签" class="col-lg-4 col-md-15 col-sm-5 col-xs-16" style="padding:0;">&nbsp;<span class="cGray visible-lg col-lg-14">至少选择一项，最多选择三项</span>&nbsp;<span class="visible-sm cGray col-sm-13">至选少一项,最多三项</span></div></div>
                </li>
				<li>
                    <div class="label col-sm-3" style="padding:0px;"></div>
                    <div class="col-sm-14 col-md-offset-4 t-subjects-list pd0 mt10 col-md-14" id="t-subjects-list">
                   <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setArr"])){; ?>
				   <?php foreach(SlightPHP\Tpl::$_tpl_vars["setArr"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
						<div class="col-sm-1 pd0 <?php if(isset(SlightPHP\Tpl::$_tpl_vars["tagArr"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag,SlightPHP\Tpl::$_tpl_vars["tagArr"])){; ?>on<?php }; ?>" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]->name; ?></div>
					<?php }; ?>
					<?php }; ?>     
                    </div>
                </li>
                <li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">我的头衔：</div><div class="label-for col-lg-16 col-sm-17 row col-md-16">
					<input type="text" class="col-lg-10 col-md-10 col-sm-10 col-xs-20" id="title" name="title" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->title; ?><?php }; ?>">
					<span class="cGray" style="margin-left:10px;">最多输入10个汉字</span>
					<span class="tip1 cRed"></span>
					</div>
                </li>
				 <li>
                    <div class="label col-sm-3 col-md-4 col-lg-4 text-right">一句话简介：</div>
                    <div class="label-for col-lg-16">
                        <input type="text" class="col-lg-10 row" name="brief_desc" id="brief_desc" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc; ?><?php }; ?>">&nbsp;&nbsp;
					<span class="cGray">最多输入20个汉字</span>

					<span class="tip1 cRed"></span></div>
                </li>
                <?php /*<li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right">获得荣誉：</div><div class="label-for col-sm-6"><textarea name="" id="" cols="30" rows="3" class="col-sm-12" placeholder="优秀教师"></textarea></div>
                    <div class="label-for col-lg-16 row">
                        <div class="pic"><img src="<?php echo utility_cdn::img('/assets_v2/img/1.png'); ?>" alt=""></div>
                        <button class="upload">上传证明</button>
                    </div>
                </li>*/?>
                <li>
                    <div class="label col-lg-4 col-md-4 col-sm-3 col-xs-20 text-right"><img src="<?php echo utility_cdn::img('/assets_v2/img/platform/x.png'); ?>">教师简介：</div><div class="label-for col-lg-16 col-md-16 col-sm-17 col-xs-20 row"><textarea name="desc" id="desc" cols="30" rows="5" class="col-lg-14 col-md-15 col-sm-20 col-xs-20" placeholder=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->desc; ?><?php }; ?></textarea>
                    <div class="col-lg-20 cGray c-fl">用一段话来介绍自己,让学生和家长更好的了解你.最多&nbsp;&nbsp;1000&nbsp;&nbsp;字.
					<span class="tip1 cRed"></span>
					</div>
                    <p class="desc-intr"></p>
					</div>
                    <?php /*<div class="col-sm-2">
                        <div class="pic"><img src="<?php echo utility_cdn::img('/assets_v2/img/1.png'); ?>" alt=""></div>
                        <button class="upload">上传视频</button>
                    </div>*/?>
                </li>
                <li>
                    <div class="label col-lg-4 col-md-4 col-sm-20 col-xs-20"> </div><div class="label-for col-xs-offset-3 col-lg-16 col-md-16 col-sm-20 col-xs-20 row col-sm-offset-5 col-md-offset-6"><button class="col-lg-4 col-md-8 col-sm-10 col-xs-10 btn blue-btn" type="submit">保存</button></div>
                </li>
            </form>
		 /教师资料部分 -->
            </ul>
            <div class="clearfix"></div>
          </div>
          <!--con end-->
        </div>
        <!--right start-->
    </section>
    <!--studentPortal end-->
	<?php echo tpl_function_part("/index.main.footer"); ?>

</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.day.js'); ?>"></script>
<script type="text/javascript">
	function addzero( num ){
		if( num < 10 ) {
			num = '0' + num;
		}
		return num;
	}
	function checkName( nickname ){
		$.ajax({
			type:'post',
			url :'/index.user.checkNickName',
			data: { nickname:nickname },
			dataType: 'json',
			success:function(data){
				if(data.code == 0){
					$('.tip1').html('');
					$('#nickname').removeClass('box-shadow');
				}else if(data.code == -1){
					$('.item_nickname .tip1').html(data.msg);
					$('#nickname').addClass('box-shadow');
          $('#nickname').focus();
				}else if(data.code == -2){
					$('.item_nickname .tip1').html(data.msg);
					$('#nickname').addClass('box-shadow');
				}
			}
		});
	}
$(function(){
	function mystr(str){
			var l = str.length; 
			var blen = 0; 
			for(i=0; i<l; i++) { 
			if ((str.charCodeAt(i) & 0xff00) != 0) { 
			blen ++; 
			} 
			blen ++; 
			}
			if(blen>15){
				return false;
			}
	}
	//弹框切换
    $('.genger-btn li').click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        $('.genger-content>li:eq(' + $(this).index() + ')').show().siblings().hide();
    })
    $('.about-us-btn').on('click', function(){
        layer.open({
            type: 1,
            title:['添加学校','color:#fff;background:#3f8ee5'],
            area:['410px', '275px'],
            shadeClose: true, //点击遮罩关闭
            content: ['about_us_layer.html','no']
        });
    });
	$('#real_name').blur(function(){
		var real_name = $(this).val();
		$('#real_name').removeClass('box-shadow');
		 if( !real_name ) {
		 	$('.tip2').html('请填写真实姓名');
		    $('#real_name').addClass('box-shadow');
		    return;
		 }else{
		 	$('.tip2').html('');
			$('#real_name').removeClass('box-shadow');
		 }

		 var flag = 0;
		 var reg = /^[\u4E00-\u9FA5]+$/;
		 if(reg.test(real_name)){
		    if(real_name.length>5){
		    	$('.tip2').html('真实姓名不超过5个汉字');
		        $('#real_name').addClass('box-shadow');
		        return;
		    }else{
		    	flag = 1;
		 		$('.tip2').html('');
				$('#real_name').removeClass('box-shadow');
		    }
		 }
		
		 var h_name = real_name.replace(/\s/g,"");
		 var eng_reg = /^[a-zA-Z]+$/;
		 if(eng_reg.test(h_name)){
		 	if(h_name.length >25 ){
		    	$('.tip2').html('真实姓名不超过25个英文字符!');
		        $('#real_name').addClass('box-shadow');
		        return;
		 	}else{
		     	flag = 1;
		 		$('.tip2').html('');
				$('#real_name').removeClass('box-shadow');
		 	}
		}
		 
		if( flag == 0 ) {
			$('.tip2').html('真实姓名格式不正确!');
		    $('#real_name').addClass('box-shadow');
		    return;
		}else{
			$('#real_name').removeClass('box-shadow');
		 	$('.tip2').html('');
		}
	
	
	});
		$("#birthday").birthday();

		var year  = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->year)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->year; ?><?php }; ?>";
		var month = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->month)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->month; ?><?php }; ?>";
		var day   = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->day)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->day; ?><?php }; ?>";
		if( year ) {
			$('select[name=year] option').each(function(){
				if( $(this).val() == year ) {
					$(this).attr('selected',true);
					return false;
				}

			});
		}
		if( month ) {
	       $('select[name=month] option').each(function(){
		   	  var se_month = addzero($(this).val());
		       if( se_month == month ) {
		           $(this).attr('selected',true);
		            return false;
		       }
		   });
	    }
		if( day ) {
		   $('select[name=day] option').each(function(){
		   		var se_day = addzero($(this).val());
		       if( se_day == day ) {
		           $(this).attr('selected',true);
		           return false;
		       }
	       });
		}
		
		$('#nickname').blur(function(){
			var nickname = $(this).val().replace(/(^\s*)|(\s*$)/g, "");
			var nick_flag = 0;
			if(mystr(nickname)==false){
				$('.item_nickname .tip1').html('昵称不能超过15个字符');
				$(this).addClass('box-shadow');
				nick_flag = 1;
			}
			if(!nickname){
				$('.item_nickname .tip1').html('请填写昵称');
				$(this).addClass('box-shadow');
				nick_flag = 1;
			}
			if(nick_flag == 0){
				checkName(nickname);
			}
		});
		//教师资料部分
		//验证毕业
$('#college').blur(function(){
		var college = $(this).val();
		$('#college').removeClass('box-shadow');
		 if( !college ) {
			$('#college').next().html('');
		 	$('#college').next().next().html('请填写毕业院校');
		    $('#college').addClass('box-shadow');
		    return;
		 }else if(college.length>15){
			$('#college').next().html('');
		 	$('#college').next().next().html('最多输入15个汉字');
			$('#college').addClass('box-shadow');
			return;
		 }else{
			$('#college').next().html('最多输入15个汉字');
		 	$('#college').next().next().html('');
			$('#college').removeClass('box-shadow');
		 }
});
//我的头衔
$('#title').blur(function(){
		var title = $(this).val();
		$('#title').removeClass('box-shadow');
		 if( !title ) {
			$('#title').next().html('');
		 	$('#title').next().next().html('请填写我的头衔');
		    $('#title').addClass('box-shadow');
		    return;
		 }else if(title.length>10){
			$('#title').next().html('');
		 	$('#title').next().next().html('最多输入10个汉字');
			$('#title').addClass('box-shadow');
			return;
		 }else{
			$('#title').next().html('最多输入10个汉字');
		 	$('#title').next().next().html('');
			$('#title').removeClass('box-shadow');
		 }
});
//一句话
$('#brief_desc').blur(function(){
		var brief_desc = $(this).val();
		$('#brief_desc').removeClass('box-shadow');
		 if(brief_desc.length>20){
			$('#span_brief_desc').css("color","red");
		 	$('#span_brief_desc').html('最多输入20个汉字');
			$('#brief_desc').addClass('box-shadow');
			return;
		 }else{
			$('#span_brief_desc').css("color","#999999");
		 	$('#span_brief_desc').html('最多输入20个汉字');
			$('#brief_desc').removeClass('box-shadow');
		 }
});
//介绍
$('#desc').blur(function(){
		var desc = $(this).val();
		$('#desc').removeClass('box-shadow');
		 if( !desc) {
		 	$('.desc-intr').html('请输入教师简介');
            $('.desc-intr').css("color","red")
		    $('#desc').addClass('box-shadow');
		    return;
		 }else if(desc.length>1000){
		 	$('#desc').next().html('最多输入1000个汉字');
			$('#desc').addClass('box-shadow');
			return;
		 }else{
		 	$('#desc').next().next().html('');
            $('.desc-intr').html('')
			$('#desc').removeClass('box-shadow');
		 }
});
/*
if($(this).find("input[name='scopes[]']:checked").length==0){
	$(".item_1 .tip1").html('请选择教学领域');
}else{
	$(".item_1 .tip1").html('');
}
*/
$(".label-for input[name='scopes[]']").each(function() {
	$(this).on('click',function() {
		 if($(this).attr("checked", true)) {
			$(".item_1 .tip1").html('');
		 }
	})
});

	
	
	$('input[type=text],select').on('change',function(){
        $(this).css("background-color","#FFF");
    })


	$(".t-subjects-but #click-but").click(function(){
                                $("#t-subjects-list").show();
                            })
                            var oT_Div_List = $("#t-subjects-list div");
                            oT_Div_List.on("click",function(){
                                var val = $(this).attr("rel");
                                    if($(this).hasClass("on")){
                                        return false;
                                    }else{
                                        if($('#t-subj-content>div').length>=3){
                                            layer.msg('只能添加三项');
                                        }else{
                                            $(this).addClass("on");
											$(".t-subjects-but .cGray").css("color","#999");
                                            $("#t-subj-content").append('<div class="col-sm-1 delect-subj pd0 col-lg-5 col-xs-5"  contenteditable="false">'+$(this).text()+'<input type="hidden" name="good_subject[]" value="'+val+'"/></div>');
                                        }
                                    }
                                })
                            $("#t-subj-content").on('click','div',function(){
                                var txt=$(this).text().replace(/\s/g,"");
                                $("#t-subjects-list>div").each(function(){
                                    $(this).text();
                                    if($(this).text()==txt){
                                        $(this).removeClass('on');
                                    }
                                    // console.log($(this).text());
                                });
                                $(this).remove();
                            })


    $('input[type=text],select').on('change',function(){
        $(this).css("background-color","#FFF");
    })
		$('#submit').on('click',function(){
			var year   = $('select[name=year]').val();
			var month  = addzero($('select[name=month]').val());
			var day    = addzero($('select[name=day]').val());
			var Arr =new Array();
			$("#class-status input").each(function(i) {
				if($(this).is(":checked")){
					Arr[i]=$(this).val();
				}
			});
			var gb=[];
			$(".delect-subj input").each(function(i) {
				gb.push($(this).val())
			});
			if($('input[name=title').val()){
				var stitle = $('input[name=title').val();
			}else{
				var stitle = '';
			}
			var params = {
				'name' : $('input[name=name]').val().replace(/(^\s*)|(\s*$)/g, ""),
				'real_name' : $('input[name=real_name]').val().replace(/(^\s*)|(\s*$)/g, ""),
				'gender'    : $('input[name=gender]:checked').val(),
				'birthday'  : year + '-' + month + '-'+ day,
				'region_level0' : $('select[name=region_level0]').val(),
				'region_level1' : $('select[name=region_level1]').val(),
				'region_level2' : $('select[name=region_level2]').val(),
				'school_type'   : $('select[name=school_type]').val(),
				'school_id'	    : $('select[name=school_id]').val(),
				'grade'	        : $('select[name=grade]').val(),
				'brief_desc'    : $('input[name=brief_desc]').val(),
				'college'		: $('input[name=college]').val(),
				'diploma'		: $('select[name=diploma]').val(),
				'years'			: $('select[name=years]').val(),
				'scopes'		: Arr,
				'good_subject'	: gb,
				'title'			: stitle,
				'desc'			: $('textarea[name=desc').val(),
			}
			var namestr = mystr(params.name);
			$('#real_name').removeClass('box-shadow');
			$('#nickname').removeClass('box-shadow');
			if(!params.name){
				$('.item_nickname .tip1').html('昵称不能为空');
			    $('#nickname').addClass('box-shadow').focus();
				return;	
			}
			if(namestr==false){
					return false;
			}
			if(!params.gender){
				$('.tip3').html('请选择性别');
				return;
			}
			if(params.brief_desc.length >20){
				//$('.item_6').next().html('最多输入20个汉字');
				$("#span_brief_desc").css("color","red").focus();
				$("#span_brief_desc").html("最多输入20个汉字");
				return;
			}
			if( !params.real_name ) {
				$('.tip2').html('请填写真实姓名');
				$('#real_name').addClass('box-shadow').focus();
				return;
			}else{
				$('.tip2').html('');
				$('#real_name').removeClass('box-shadow');
			}
			
			var flag = 0;
		    var reg = /^[\u4E00-\u9FA5]+$/;
		    if(reg.test(params.real_name)){
				if(params.real_name.length>5){
		   			$('.tip2').html('真实姓名不超过5个汉字');
					$('#real_name').addClass('box-shadow').focus();
					return;
				}else{
					flag = 1;
					$('.tip2').html('');
					$('#real_name').removeClass('box-shadow');
				}
		    }

			var h_name = params.real_name.replace(/\s/g,"");
			var eng_reg = /^[a-zA-Z]+$/;
			if(eng_reg.test(h_name)){
				if(params.real_name.length >25 ){
					$('.tip2').html('真实姓名不超过25个英文字符!');
					$('#real_name').addClass('box-shadow').focus();
					return;
				}else{
					flag = 1;
					$('#real_name').removeClass('box-shadow');
					$('.tip2').html('');
				}
			}

			if( flag == 0 ) {
				$('.tip2').html('真实姓名格式不正确!');
				$('#real_name').addClass('box-shadow').focus();
				return;
			}
			$('.tip2').html('');
			$('#real_name').removeClass('box-shadow');

			if(!params.region_level0 || !params.region_level1)
			{
				$('#region1').html('请选择常住地区');
			    $('#region1').addClass('box-shadow').focus();
				return;
			}else
			{
				$('#region1').html('');
			    $('#region1').removeClass('box-shadow');
			}
			
			if(params.school_type == 2 || params.school_type == 3){
				params.school_id = 0;
				params.grade = 0;
			}
			//教师部分
			if($("input[name=isTeacher]").val()=='1'){
                if( !params.college ) {
                $('#college').next().html('');
                $('#college').next().next().html('请填写毕业院校');
                $('#college').addClass('box-shadow');
                $('#college').focus();
                return false;
                }
                if( params.college.length>15 ) {
                $('#college').addClass('box-shadow').focus();
                return false;
                }
                if(params.scopes.length=='0'){
                    $(".item_1 .tip1").html('请选择教学领域');
                    return false;
                }
                if(params.good_subject==''){
                    $(".item_2 .cGray").css("color","red");
                    return false;
                }
                if(params.title==''){
                    $('#title').next().html('');
                    $('#title').next().next().html('请填写我的头衔');
                    $('#title').focus();
                    return false;
                }
				if(params.title.length >10 ){
                    $('#title').next().html('');
                    $('#title').next().next().html('最多输入10个汉字');
					$("#title").focus();
                    return false;
                }
                if(params.desc==''){
                    $('#desc').next().next().html('教师介绍不能为空');
                    $('#desc').focus();
                    return false;
                }
                if(params.brief_desc.length>20){
                    $(".item_4 .cGray").css("color","red");
                    return false;
                }
			}
			$.ajax({
				type:'post',
				url :'/index.user.setInfo',
				data: params,
				dataType: 'json',
				success:function(data){
					if(data.result.code == 0){
						layer.msg(data.result.msg);
				    	location = location;
					}else if(data.result.code == -3){
						layer.msg(data.result.msg);
					}else if(data.result.code == -1){
						$('.item_nickname .tip1').html(data.result.msg);
						$('#nickname').addClass('box-shadow').focus();
					}else if(data.result.code == -2){
						$('.tip2').html(data.result.msg);
						$('#real_name').addClass('box-shadow').focus();
					}else{
						layer.msg(data.result.msg);
					}
				}

			});
		});



		var region_level0 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0; ?><?php }; ?>";
		var region_level1 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level1; ?><?php }; ?>";
		var region_level2 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level2; ?><?php }; ?>";
		var school_id     = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_id; ?><?php }; ?>";
		var school_type   = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type; ?><?php }; ?>";
		var school_grade  = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->grade; ?><?php }; ?>";
		if(school_type == 0){
			school_type = 1;
		}

		$("#level0").change(function(){
			var pid = $(this).val();
			if(pid){
				$.get("/index.user.ListRegion."+pid,{ },function(item){
					$("#level1").html("");
					if(item){
						$("#level1").append("<option  value=''>请选择</option>");
						for(var i in item){
							var s="";
							if(item[i].region_id == region_level1){
								s=" selected ";
							}
							$("#level1").append("<option "+s+" value='"+item[i].region_id+"'>"+item[i].name+"</option>");
						}
						$("#level1").show().trigger("change");
					}else{
						$("#level1").hide();
					}
				},"json");
				$('#level2').hide();
			}else{
				$('#level1').empty();
				$("#level1").append("<option  value=''>请选择</option>");
				$('#level2').empty();
				$("#level2").append("<option  value=''>请选择</option>");
				$('#level2').hide();
			}
		}).trigger("change");


		$("#level1").change(function(){
			var pid = $(this).val();
			$("#level2").empty();
			$("#level2").append("<option  value=''>请选择</option>");
			if(pid){
				$.get("/index.user.ListRegion."+pid,{ },function(item){
					$("#level2").html("");
					if(item){
						$("#level2").append("<option value=''>请选择</option>");
						for(var i in item){
							var s="";
							if(item[i].region_id == region_level2){
								s=" selected ";
							}
							$("#level2").append("<option "+s+" value='"+item[i].region_id+"'>"+item[i].name+"</option>");
							}
						$("#level2").show().trigger("change");
					}else{
						$("#level2").hide();
					}
				},"json").fail(function(){
				$("#level2").hide();
				});
			}
		});


		$("#level2 ,#level1, #school_type").change(function(){
			var school_type = $('#school_type').val();	
			if(school_type == 1 || school_type == 6){
				var pid = $("#level2").val() ? $("#level2").val() : $("#level1").val();
				$.get("/index.user.ListSchool",{ school_type:$("#school_type").val(),
				region_id:pid },function(item){
					$("#school").html("<option value=''>学校</option>");
					if(item){
						for(var i in item){
							var s="";
							if(item[i].school_id== school_id){
								s=" selected ";
							}
							$("#school").append("<option "+s+" value='"+item[i].school_id+"'>"+item[i].school_name+"</option>");
						}
						$("#school").show();
					}else{
						$("#school").hide();
					}
					console.log(item);
				},"json");
				var name = $(this).attr('name');
				if(name == 'school_type'){
					$.ajax({
						type:'post',
						url :'/index.user.ListGrade',
						data: { school_type:school_type },
						dataType: 'json',
						success:function(item){
							$("#school_grade").html("<option value=''>年级</option>");
							if(item){
								for(var i in item){
									var s="";
									if(item[i].grade_id== school_grade){
										s=" selected ";
									}
									$("#school_grade").append("<option "+s+" value='"+item[i].grade_id+"'>"+item[i].grade_name+"</option>");
								}
								$("#school_grade").show();
							}else{
								$("#school_grade").hide();
							}
						}
					});
				}
			}else{
				$('#school').hide();
				$("#school_grade").hide();
			}
		
		});

		if(school_type == 1 || school_type == 6){
			$.ajax({
				type:'post',
				url :'/index.user.ListGrade',
				data: { school_type:school_type },
				dataType: 'json',
				success:function(item){
					$("#school_grade").html("<option value=''>年级</option>");
					if(item){
						for(var i in item){
							var s="";
							if(item[i].grade_id== school_grade){
								s=" selected ";
							}
							$("#school_grade").append("<option "+s+" value='"+item[i].grade_id+"'>"+item[i].grade_name+"</option>");
						}
						$("#school_grade").show();
					}else{
						$("#school_grade").hide();
					}
				}
			});
		}else{
			$('#school').hide();
			$("#school_grade").hide();
		}
	});
</script>
</body>
</html>