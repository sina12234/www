<!DOCTYPE html>
<html>
<head>
<title>教师资料 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 教师资料 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.day.js'); ?>"></script>
</head>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.divselect.js'); ?>"></script>
<body style="background:#f7f7f7;">
<?php echo tpl_function_part("/site.main.nav"); ?>
<section>
<div class='container pd40'>
            <div class=''row>	
                <?php echo tpl_function_part("/org.main.menu.teacher"); ?>
                <div class="right-main col-sm-9 col-md-16">

                    <div class="content">
                        <div class="manage-path fs14">
						<a href="/org.teacher.list"><?php echo tpl_modifier_tr('返回','org'); ?></a>>
						<span class="cGray"><?php echo tpl_modifier_tr('编辑教师资料','org'); ?></span>
						</div>
						
                        <form  id="form">
                            <ul class="form fs14">
								<li>
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"><?php echo tpl_modifier_tr('角色','org'); ?>：
                                    </div>
                                    <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12">
										<label>
											<input name="role[]" type="checkbox" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["special"]->roles)&&in_array('general',SlightPHP\Tpl::$_tpl_vars["special"]->roles)){; ?>checked<?php }; ?> value="general" /><?php echo tpl_modifier_tr('普通老师','org'); ?>
										</label> 
										<label>
                                        <?php /*<input name="role[]" type="checkbox" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["special"]->roles)&&in_array('assistant',SlightPHP\Tpl::$_tpl_vars["special"]->roles)){; ?>checked<?php }; ?> value="assistant" />助教*/?>
                                        <input name="role[]" type="checkbox" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["special"]->roles)&&in_array('admin',SlightPHP\Tpl::$_tpl_vars["special"]->roles)){; ?>checked<?php }; ?> value="admin" /><?php echo tpl_modifier_tr('管理员','org'); ?>
										</label>
                                    </div>
                                </li>
								<li>
                                    <div class="col-sm-2 col-md-4 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"><?php echo tpl_modifier_tr('是否展现在教师团队','org'); ?>：
                                    </div>
                                    <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12">
									<label>
                                        <input name="visiable" type="radio" <?php if(empty(SlightPHP\Tpl::$_tpl_vars["special"]->visiable)){; ?>checked<?php }; ?> value="0" /><?php echo tpl_modifier_tr('不展现','org'); ?>
									</label>
									<label>
                                        <input name="visiable" type="radio" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["special"]->visiable)&&SlightPHP\Tpl::$_tpl_vars["special"]->visiable==1){; ?>checked<?php }; ?> value="1" /><?php echo tpl_modifier_tr('展现','org'); ?>
									</label>
                                    </div>
                                </li>
                                <li>
									<div class="col-sm-2 col-md-3 col-xs-12 label fs14"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"><?php echo tpl_modifier_tr('昵称','org'); ?>：</div>
								  <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12" >
									<input type="text" id="nickname"  name="name" class="col-sm-5 col-xs-10" placeholder="<?php echo tpl_modifier_tr('最多15个字符','site.user'); ?>"  value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->name; ?>">
									<span class="tip3 cRed"></span>
								  </div>
								</li>
								<li>
								  <div class="col-sm-2 col-md-3 col-xs-12 label fs14"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"> <?php echo tpl_modifier_tr('真实姓名','org'); ?>：</div>
								  <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12">
									<input type="text" id="real_name"  name="real_name" class="col-sm-5 col-xs-10" placeholder="<?php echo tpl_modifier_tr('最多5个汉字','site.user'); ?>" value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->profile->real_name; ?>">
									<span class="tip2 cRed"></span>
								  <?php /*  <p class="cGray clear">不超过4个字，身份认证成功后，姓名不可修改</p>*/?>
								  </div>
								</li>
								<li>
									<div class="col-sm-2 col-md-3 col-xs-12 label fs14"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"> <?php echo tpl_modifier_tr('性别','org'); ?>：</div>
								  <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12">
									<label for="s1"><input <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="male"){; ?>checked<?php }; ?>  type="radio" name="gender" id="s1" value="male"  > <?php echo tpl_modifier_tr('男','org'); ?></label>
									<label for="s2"><input <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="female"){; ?>checked<?php }; ?> type="radio" name="gender" id="s2" value="female" > <?php echo tpl_modifier_tr('女','org'); ?></label>
								  </div>
								</li>
								<li>
									<div class="col-sm-2 col-md-3 col-xs-12 label fs14"><img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>"> <?php echo tpl_modifier_tr('常住地区','org'); ?>：</div>
								  <div class="label-for col-sm-10 col-md-9 col-lg-9 col-xs-12">
									<select name="region_level0" id="level0" class="col-sm-3 col-xs-4">
									  <option value=""><?php echo tpl_modifier_tr('请选择','org'); ?></option>
									  <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level0"])){; ?>
									  <?php foreach(SlightPHP\Tpl::$_tpl_vars["level0"] as SlightPHP\Tpl::$_tpl_vars["region"]){; ?>
									  <option <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0==SlightPHP\Tpl::$_tpl_vars["region"]->region_id){; ?> selected <?php }; ?> value="<?php echo SlightPHP\Tpl::$_tpl_vars["region"]->region_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["region"]->name; ?></option>
									  <?php }; ?>
									<?php }; ?>
									</select>
									<select name="region_level1" id="level1" class="col-sm-3 ml10 col-xs-4">
									  <option value=""><?php echo tpl_modifier_tr('请选择','org'); ?></option>
									</select>
									<select style="display:none" class="col-sm-3 ml10 col-xs-3" name="region_level2" id="level2">
									  <option value=""><?php echo tpl_modifier_tr('请选择','org'); ?></option>
									</select>
									<span id='region1' class="cRed"></span>
								  </div>
								</li>
                                <li>
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('毕业院校','org'); ?>：
                                    </div>
                                    <div class="label-for col-sm-9 col-md-17">
                                        <input type="text" class="col-sm-5" id="college" name="college" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->college)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->college; ?><?php }; ?>">&nbsp;&nbsp;
                                        <span class="cGray"><?php echo tpl_modifier_tr('最多输入15个汉字','org'); ?></span>
                                        <span class="tip1 cRed"></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14"><?php echo tpl_modifier_tr('学历','org'); ?>：</div>
                                    <div class="label-for col-sm-9 col-md-17">
                                        <select name="diploma" id="" class="col-sm-5">
                                            <option value="-1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==-1){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('不显示','site.teacher'); ?></option>
                                            <option value="1" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==1){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('大专','site.teacher'); ?></option>
                                            <option value="2" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==2){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('本科','site.teacher'); ?></option>
                                            <option value="3" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==3){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('硕士','site.teacher'); ?></option>
                                            <option value="4" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==4){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('博士','site.teacher'); ?></option>
                                        </select></div>
                                </li>
                                <li>
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('教龄','org'); ?>：
                                    </div>
                                    <select name='years'>
                                        <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1;SlightPHP\Tpl::$_tpl_vars["i"]<31;SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                                        <option value='<?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==SlightPHP\Tpl::$_tpl_vars["i"]){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?><?php echo tpl_modifier_tr('年','org'); ?></option>
                                        <?php }; ?>
                                        <option value='31' <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==31){; ?>selected<?php }; ?>><?php echo tpl_modifier_tr('30年以上','org'); ?></option>
                                    </select>
                                </li>
                                <li class="item_1">
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('教学阶段','org'); ?>：
                                    </div>
                                    <div class="label-for col-sm-9 col-md-17">
                                        <label for="s1"><input type="checkbox" name="scopes[]" id="s1" value="preschool" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('preschool',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s1">&nbsp;<?php echo tpl_modifier_tr('学前','org'); ?>
                                        </label>
                                        <label for="s2"><input type="checkbox" name="scopes[]" value="primary" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('primary',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s2">&nbsp;<?php echo tpl_modifier_tr('小学','org'); ?></label>
                                        <label for="s3"><input type="checkbox" name="scopes[]" value="junior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('junior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s3">&nbsp;<?php echo tpl_modifier_tr('初中','org'); ?></label>
                                        <label for="s4" ><input type="checkbox" name="scopes[]" value="senior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('senior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s4">&nbsp;<?php echo tpl_modifier_tr('高中','org'); ?></label>
                                        <span class="tip1 cRed"></span>
                                    </div>
                                </li>
                                <li class="item_2">
                                    <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                        <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('擅长学科','org'); ?>：
                                    </div>
                                    <div class="label-for col-sm-9 col-md-17 t-subj-content" id="t-subj-content"  style="border:1px solid #ccc;width:33%;">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data)){; ?>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->name)){; ?><div class="delect-subj" contenteditable="false">
                                            <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->name,'course.list'); ?><input type="hidden" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>" name="good_subject[]"></div>
                                        <?php }; ?>
                                        <?php }; ?>
                                        <?php }; ?>

                                    </div>
                                    <div class="t-subjects-but" style="line-height:35px;">&nbsp;&nbsp;<input id="click-but" type="button" value="<?php echo tpl_modifier_tr('添加标签','org'); ?>">&nbsp;
                                        <span class="cGray"><?php echo tpl_modifier_tr('至少选择一项，最多选择三项','org'); ?></span>
                                    </div>

                                    <?php /*
                                    <li>
                                        <div class="col-sm-2 col-md-3 col-xs-12 label fs14">
                                            <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">擅长学科：
                                        </div>

                                        <div class="label-for col-sm-9 col-md-17" class="divselect">
                                            <select name="major" id="" class="col-sm-2">
                                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["majors"] as SlightPHP\Tpl::$_tpl_vars["major_id"]=>SlightPHP\Tpl::$_tpl_vars["major_name"]){; ?>
                                                <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["major_id"]; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["major_id"]==SlightPHP\Tpl::$_tpl_vars["teacher"]->major){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["major_name"]; ?></option>
                                                <?php }; ?>
                                            </select>
                                        </div>
                                        */?>	
                                    </li>
                                    <li>
                                        <div class="label col-sm-2 col-lg-3"></div>
                                        <div class="col-sm-9 t-subjects-list pd0 col-lg-9" id="t-subjects-list">
                                            <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setArr"])){; ?>
											<?php foreach(SlightPHP\Tpl::$_tpl_vars["setArr"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
												<div class="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["tagArr"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag,SlightPHP\Tpl::$_tpl_vars["tagArr"])){; ?>on<?php }; ?>" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>"><?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->name,'course.list'); ?></div>
											<?php }; ?>
											<?php }; ?>  
                                        </div>
                                    </li>
                                    <li>
                                        <div class="label col-sm-3">
                                            <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('我的头衔','org'); ?>：
                                        </div>
                                        <div class="label-for col-sm-9 col-md-17">
                                            <input type="text" class="col-sm-7" id="title" name="title" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->title; ?><?php }; ?>">&nbsp;&nbsp;
                                            <span class="cGray"><?php echo tpl_modifier_tr('最多输入10个汉字','org'); ?></span>
                                            <span class="tip1 cRed"></span>
                                        </div>
                                    </li>
                                    <?php /*<li>
                                        <div class="label col-sm-3">获得荣誉：</div><div class="label-for col-sm-6"><textarea name="" id="" cols="30" rows="3" class="col-sm-12" placeholder="优秀教师"></textarea></div> 
                                        <div class="col-sm-9">
                                            <div class="pic"><img src="<?php echo utility_cdn::img('/assets_v2/img/1.png'); ?>" alt=""></div>
                                            <button class="upload">上传证明</button>
                                        </div>
                                    </li>*/?>
                                    <li>
                                        <div class="label col-sm-3"><?php echo tpl_modifier_tr('一句话简介','org'); ?>：</div>
                                        <div class="label-for col-sm-9 col-md-17">
                                            <input type="text" class="col-sm-7" id="brief_desc" name="brief_desc" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc; ?><?php }; ?>">&nbsp;&nbsp;
                                            <span class="cGray"><?php echo tpl_modifier_tr('最多输入20个汉字','org'); ?></span>
                                            <span class="tip1 cRed"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="label col-sm-3">
                                            <img src="<?php echo utility_cdn::img('/assets_v2/img/x.png'); ?>">&nbsp;<?php echo tpl_modifier_tr('教师简介','org'); ?>：
                                        </div>
                                            <div class="label-for col-sm-9 col-md-17">
                                                <textarea name="desc" id="desc" cols="30" rows="3" class="col-sm-12" placeholder=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->desc; ?><?php }; ?></textarea>
                                            <span class="cGray"><?php echo tpl_modifier_tr('用一段话来介绍自己,让学生和家长更好的了解你,最多1000字','org'); ?>&nbsp;&nbsp;</span>
                                            <span class="tip1 cRed"></span>
                                        </div>
                                        <?php /*<div class="col-sm-2">
                                            <div class="pic"><img src="<?php echo utility_cdn::img('/assets_v2/img/1.png'); ?>" alt=""></div>
                                            <button class="upload">上传视频</button>
                                        </div>*/?>
                                    </li>
                                    <li>
                                        <div class="label col-sm-3"> </div>
                                        <div class="label-for col-sm-9 col-md-17">
                                            <button class="col-sm-2 col-md-3" type="submit"><?php echo tpl_modifier_tr('保存','org'); ?></button>
                                        </div>
                                    </li>
                            </ul>
                            <input type="hidden" name="tid" value="<?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->uid; ?>">
                        </form>
                    </div>
                </div>
            </div>
            <div class='clear'></div>
        </div>
        </div>
    </section>
    <?php echo tpl_function_part("/site.main.footer"); ?>
</body>
<script>
$(function() {

	 $('input[type=text],select').on('change',function(){
        $(this).css("background-color","#FFF");
    })

//验证名字
$('#name').blur(function(){
		var name = $(this).val();
		$('#name').removeClass('box-shadow');
		 if( !name ) {
		 	$('#name').next().html('请填写教师名称');
		    $('#name').addClass('box-shadow');
		    return;
		 }else if(name.length>10){
		 	$('#name').next().html('最多输入10个汉字');
			$('#name').addClass('box-shadow');
			return;
		 }else{
		 	$('#name').next().html('');
			$('#name').removeClass('box-shadow');
		 }
});
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
$(".label-for input[name='scopes[]']").each(function() {
	$(this).on('click',function() {
		 if($(this).attr("checked", true)) {
			$(".item_1 .tip1").html('');
		 }
	})
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
			$('#brief_desc').next().html('');
		 	$('#brief_desc').next().next().html('最多输入20个汉字');
			$('#brief_desc').addClass('box-shadow');
			return;
		 }else{
			$('#brief_desc').next().html('最多输入20个汉字');
		 	$('#brief_desc').next().next().html('');
			$('#brief_desc').removeClass('box-shadow');
		 }
});	


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
                                            $("#t-subj-content").append('<div class="delect-subj"  contenteditable="false">'+$(this).text()+'<input type="hidden" name="good_subject[]" value="'+val+'"/></div>');
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
                                });
                                $(this).remove();
                            })

    $.divselect(".divselect>cite");
    $("#form").submit(function(){
		if($(this).find('input[name=name]').val()=='' ){
		   $('#nickname').html("<?php echo tpl_modifier_tr('请填写昵称','site.user'); ?>");
		   $('#nickname').addClass('box-shadow');
           return false;
        }if($(this).find('input[name=real_name]').val()=='' ){
		   $('#real_name').html('请填真实姓名');
		   $('#real_name').addClass('box-shadow');
           return false;
        }else if($(this).find('select[name=region_level0]').val()=='' || $(this).find('select[name=region_level1]').val()==''){
		   $('#region1').html('请选择常住地区');
		   $('#level0').addClass('box-shadow');
           return false;
        }if($(this).find('select[name=school_type]').val()=='' || $(this).find('select[name=school_id]').val()==''){
		   $('#grades1').html('请选择学校/年级');
		   $('#school').addClass('box-shadow');
           return false;
        }else if($(this).find('input[name=name]').val()==''){
		   $('#name').next().html('请填写教师名称');
           return false;
        }else if($(this).find('input[name=name]').val().length>15){
			$('#name').next().html('教师名称不能超过15个汉字');
            return false;
        }else if($(this).find('select[name=years]').val()!=''&&isNaN($(this).find('select[name=years]').val())){
           return false
        }else if($(this).find('select[name=years]').val()<0){
           layer.msg('教龄只允许输入正数字'); 
           $(this).find('select[name=years]').focus().css("background-color","#FFFFCC");
        }else if($(this).find('input[name=college]').val()==''){
			$('#college').next().next().html('请填写毕业院校');
            return false;
        }else if($(this).find('input[name=college]').val().length>15){
			$('#college').next().next().html('毕业院校名字不能超过15个汉字');
            return false;
        }else if($(this).find("input[name='scopes[]']:checked").length==0){
           $(".item_1 .tip1").html("<?php echo tpl_modifier_tr('请选择教学领域','site.user'); ?>");
            return false;
        }else if($("#t-subj-content .delect-subj").length==0){
            $(".item_2 .cGray").css("color","red");
            return false;
        }else if($(this).find('input[name=brief_desc]').val().length>20){
            return false;
        }else if($(this).find('input[name=title]').val()==''){
			$('#title').next().next().html("<?php echo tpl_modifier_tr('我的头衔不能为空','site.user'); ?>");
           return false;
        }else if($(this).find('input[name=title]').val().length>10){
			$('#title').next().next().html('我的头衔不能超过10个汉字');
            return false;
        }else if($(this).find('textarea[name=desc]').val()==''){
			$('#desc').next().next().html("<?php echo tpl_modifier_tr('教师简介不能为空','site.user'); ?>");
           return false;
        }else{
            $.post("/org.teacher.updateTeacherAjax",$(this).serialize(),function(r){
                if(r.error){
				//alert(r.error)
                    $("[name="+r.field+"]").focus().css("background-color","#FFFFCC").nextAll('.tip3').html(r.error);
					return false;
                }else{
                    layer.msg('修改成功');
                }
            },"json");
        }
        return false;
    });
});
</script>
<script type="text/javascript">
	function addzero( num ){
		if( num < 10 ) {
			num = '0' + num;
		}
		return num;
	}
	function checkName( nickname ){
		var tid = $("input[name=tid]").val();
		$.ajax({
			type:'post',
			url :'/org.teacher.checkNickName',
			data: { nickname:nickname,tid:tid },
			dataType: 'json',
			success:function(data){
				if(data.code == 0){
					$('.tip3').html('');
					$('#nickname').removeClass('box-shadow');
				}else if(data.code == -1){
					$('.tip3').html(data.msg);
					$('#nickname').addClass('box-shadow');
				}else if(data.code == -2){
					$('.tip3').html(data.msg);
					$('#nickname').addClass('box-shadow');
				}
			}

		});
	
	}
$(function(){
	$('#real_name').blur(function(){
		var real_name = $(this).val();
		$('#real_name').removeClass('box-shadow');
		 if( !real_name ) {
		 	$('#real_name').next().html("<?php echo tpl_modifier_tr('请填写真实姓名','site.user'); ?>");
		    $('#real_name').addClass('box-shadow');
		    return;
		 }else{
		 	$('#real_name').next().html('');
			$('#real_name').removeClass('box-shadow');
		 }

		 var flag = 0;
		 var reg = /^[\u4E00-\u9FA5]+$/;
		 if(reg.test(real_name)){
		    if(real_name.length>5){
		    	$('.tip2').html("<?php echo tpl_modifier_tr('真实姓名不超过5个汉字','site.user'); ?>");
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
		 	if(h_name.length >16 ){
		    	$('.tip2').html('真实姓名不超过16个英文字符!');
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
		
		$('#nickname').blur(function(){
			var nickname = $(this).val().replace(/(^\s*)|(\s*$)/g, "");
			var nick_flag = 0;
			if(nickname.length>15){
				$('#nickname').next().html('昵称不能超过15个字符');
				$(this).addClass('box-shadow');
				nick_flag = 1;
			}
			if(!nickname){
				$('#nickname').next().html("<?php echo tpl_modifier_tr('请填写昵称','site.user'); ?>");
				$(this).addClass('box-shadow');
				nick_flag = 1;
			}
			if(nick_flag == 0){
				checkName(nickname);
			}
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
				$.get("/user.course.ListRegion."+pid,{ },function(item){
					$("#level1").html("");
					if(item){
						$("#level1").append("<option  value=''><?php echo tpl_modifier_tr('请选择','org'); ?></option>");
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
				$("#level1").append("<option  value=''><?php echo tpl_modifier_tr('请选择','org'); ?></option>");
				$('#level2').empty();
				$("#level2").append("<option  value=''><?php echo tpl_modifier_tr('请选择','org'); ?></option>");
				$('#level2').hide();
			}
		}).trigger("change");


		$("#level1").change(function(){
			var pid = $(this).val();
			$("#level2").empty();
			$("#level2").append("<option  value=''><?php echo tpl_modifier_tr('请选择','org'); ?></option>");
			if(pid){
				$.get("/user.course.ListRegion."+pid,{ },function(item){
					$("#level2").html("");
					if(item){
						$("#level2").append("<option value=''><?php echo tpl_modifier_tr('请选择','org'); ?></option>");
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

    
		$.ajax({
				type:'post',
				url :'/student.main.ListGrade',
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

	});
	/*$('#alert1').on('click', function(){
		layer.open({
			type: 2,
			area: ['720px', '540px'],
			shadeClose: true, //点击遮罩关闭
			title:'修改头像',
			content: '<?php echo "/student.main.uploadPic"; ?>'
		});
	});*/
</script>
</html>
