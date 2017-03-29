<!DOCTYPE html>
<html>
<head>
<title>个人资料 - <?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - 专业的在线学习平台</title>
<meta name="title" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 个人中心 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="<?php echo tpl_function_part('/site.main.orgname'); ?> - 云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/site.main.header"); ?>
</head>
<body >
<?php echo tpl_function_part("/site.main.usernav.student"); ?>
<!-- mob nav -->
<div class="mob-nav hidden-lg hidden-md">
    <p class="swiper-wrapper" id="mob-nav">
        <a href="/student.main.infobase" class="swiper-slide active">基础资料</a>
        <a href="/student.main.uploadPic/1" class="swiper-slide">修改头像</a>
        <a href="/student.security.password" class="swiper-slide">安全设置</a>
    </p>
</div>
<section class="pd30">
    <!--studentPortal start-->
    <div class="container">
        <div class="row">
            <!-- leftmenu start-->
            <?php echo tpl_function_part("/user.main.menu.infobase"); ?>
            <!-- leftmenu end -->
            <!--right start-->
            <div class="right-main col-md-16 col-xs-20">
          <!--con start-->
          <div class="mainCon s-new-info-base">
            <ul class="form fs14 mt30 form-horizontal ">
				      <li class="item_nickname form-group ">
                <div class="control-label col-xs-8 col-sm-5">
                  <em class='cRed'>*</em><?php echo tpl_modifier_tr('昵称','site.user'); ?>：
                </div>
                <div class="label-for col-xs-10 col-sm-10" >
                  <input type="text"  id="nickname"  data-valid="isNonEmpty||between:0-15" data-error="昵称不能为空||昵称不能超过15个字符" data-status="0"  name="name" class="col-xs-20 col-sm-12 required verify-judge" data-tip="最多15个字符"  value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->name; ?>">
				        </div>
				      </li>
              <li class="form-group ">
                <div class="control-label col-xs-8 col-sm-5"><em class="cRed">*</em><?php echo tpl_modifier_tr('真实姓名','site.user'); ?>：</div>
                <div class="label-for col-xs-10 col-sm-10">
                  <input type="text" id="real_name"  name="real_name" class="col-xs-20 col-sm-12 verify-judge"  value="<?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->profile->real_name; ?>">
					        <div class="tips col-xs-20 col-sm-8">
						        <span class="tips-icon"></span>
                    <span class="tips-text"></span>
					        </div>
                </div>
              </li>
              <li class="form-group ">
					      <div class="control-label col-xs-8 col-sm-5"><em class="cRed">*</em><?php echo tpl_modifier_tr('性别','site.user'); ?>：</div>
					      <div class="label-for col-xs-10 col-sm-10">
						      <label for="s1" class="f14" ><input  <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="male"){; ?>checked<?php }; ?>  type="radio" name="gender" id="s1" value="male"  > <?php echo tpl_modifier_tr('男','site.user'); ?></label>
						      <label for="s2" class="f14"><input  <?php if(SlightPHP\Tpl::$_tpl_vars["userinfo"]->gender=="female"){; ?>checked<?php }; ?> type="radio" name="gender" id="s2" value="female" > <?php echo tpl_modifier_tr('女','site.user'); ?></label>
					      </div>
				      </li>
              <li class="form-group ">
                <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('出生日期','site.user'); ?>：</div>
                <div class="label-for col-xs-10 col-sm-10" id="birthday" >
                  <div class="divselect divselect-32 mr10"  id="birthday-year" >
                    <cite>
                      <span class="cite-icon"></span>
                      <span class="cite-text"></span>
                    </cite>
                    <dl></dl>
                    <input type="hidden"   name="year">
                  </div>
                  <div class="divselect divselect-32 mr10"  id="birthday-month" >
                    <cite>
                      <span class="cite-icon"></span>
                      <span class="cite-text"></span>
                    </cite>
                    <dl></dl>
                    <input type="hidden"  name="month">
                  </div>
                  <div class="divselect divselect-32 mr10" id="birthday-day" >
                    <cite>
                      <span class="cite-icon"></span>
                      <span class="cite-text"></span>
                    </cite>
                    <dl></dl>
                    <input type="hidden"  name="day">
                  </div>
                </div>
              </li>
                <li class="form-group region_info">
                    <div class="control-label col-xs-8 col-sm-5"><em class="cRed">*</em><?php echo tpl_modifier_tr('常住地区','site.user'); ?>：</div>
                    <div class="label-for col-xs-10 col-sm-10">
                        <div class="divselect divselect-32 mr10" id="level0" >
                            <cite>
                                <span class="cite-icon"></span>
                                <span class="cite-text">请选择省市</span>
                            </cite>
                            <dl>
                                <dd>
                                    <a href="javascript:;" selectid="">请选择省市</a>
                                </dd>
                                <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["level0"])){; ?>
                                <?php foreach(SlightPHP\Tpl::$_tpl_vars["level0"] as SlightPHP\Tpl::$_tpl_vars["region"]){; ?>
                                <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0==SlightPHP\Tpl::$_tpl_vars["region"]->region_id){; ?> selected=selected <?php }; ?>>
                                    <a href="javascript:;"  selectid="<?php echo SlightPHP\Tpl::$_tpl_vars["region"]->region_id; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["region"]->name; ?></a>
                                </dd>
                                <?php }; ?>
                                <?php }; ?>
                            </dl>
                            <input type="hidden" class="verify-judge" name="region_level0">
                        </div>
                        <div class="divselect divselect-32 mr10" id="level1" >
                            <cite>
                                <span class="cite-icon"></span>
                                <span class="cite-text" >请选择地区</span>
                            </cite>
                            <dl>
                                <dd>
                                    <a href="javascript:;" selectid="">请选择地区</a>
                                </dd>
                            </dl>
                            <input type="hidden" class="verify-judge"  name="region_level1">
                        </div>
                        <div class="divselect divselect-32 mr10" id="level2" >
                            <cite>
                                <span class="cite-icon"></span>
                                <span class="cite-text" >请选择地区</span>
                            </cite>
                            <dl>
                                <dd>
                                    <a href="javascript:;" selectid="">请选择地区</a>
                                </dd>
                            </dl>
                            <input type="hidden" name="region_level2">
                        </div>
                        <div id='region1' class="tips pl20">
                            <span class="tips-icon"></span>
                            <span class="tips-text cRed"></span>
                        </div>
                      </div>
                </li>
                <li class="form-group school_info">
                    <div class="control-label col-xs-8 col-sm-5"><em class="cRed">*</em><?php echo tpl_modifier_tr('教育阶段','site.user'); ?>：</div>
                    <div class="label-for col-xs-10 col-sm-10">
                      <div class="divselect divselect-32" id="school_type" class="col-sm-3 col-xs-3">
                          <cite>
                              <span class="cite-icon"></span>
                              <span class="cite-text"><?php echo tpl_modifier_tr('学前','site.user'); ?></span>
                          </cite>
                          <dl>
                              <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==2){; ?>selected = selected<?php }; ?>>
                                  <a href="javascript:;" selectid="2"><?php echo tpl_modifier_tr('学前','site.user'); ?></a>
                              </dd>
                              <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==1){; ?>selected = selected<?php }; ?>>
                                  <a href="javascript:;" selectid="1"><?php echo tpl_modifier_tr('小学','site.user'); ?></a>
                              </dd>
                              <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==6){; ?>selected = selected<?php }; ?>>
                                  <a href="javascript:;" selectid="6"><?php echo tpl_modifier_tr('中学','site.user'); ?></a>
                              </dd>
                              <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student) && SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type==3){; ?>selected = selected<?php }; ?>>
                                  <a href="javascript:;" selectid="3"><?php echo tpl_modifier_tr('大学','site.user'); ?></a>
                              </dd>
                          </dl>
                          <input type="hidden" class="verify-judge" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type; ?><?php }; ?>"  name="school_type">
                      </div>
                      <div class="divselect divselect-32" id="school" class="col-sm-3 col-xs-4 ml10">
                          <cite>
                              <span class="cite-icon"></span>
                              <span class="cite-text" >请选择学校</span>
                          </cite>
                          <dl>
                              <dd>
                                  <a href="javascript:;" selectid="">请选择学校</a>
                              </dd>
                          </dl>
                          <input type="hidden"   name="school_id">
                      </div>
                      <div class="divselect divselect-32" id="school_grade" class="col-sm-3 ml10 col-xs-4">
                          <cite>
                              <span class="cite-icon"></span>
                              <span class="cite-text" >请选择年级</span>
                          </cite>
                          <dl>
                              <dd>
                                  <a href="javascript:;" selectid="">请选择年级</a>
                              </dd>
                          </dl>
                          <input type="hidden"  name="grade">
                      </div>
                    <div id='grades1' class="tips pl20">
                        <span class="tips-icon"></span>
                        <span class="tips-text cRed"></span>
                    </div>
                    <p class="new-cGray clear"><?php echo tpl_modifier_tr('如果没有您的学校,请','site.user'); ?><a class="cYellow" href="/about.main.contact"><?php echo tpl_modifier_tr('联系我们','site.user'); ?></a><?php echo tpl_modifier_tr('添加','site.user'); ?></p>
                  </div><!-- <a class="cYellow about-us-btn" href="javascript:;">联系我们</a> -->
                </li>
                <li class="form-group " >
                    <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('一句话简介','site.teacher'); ?>：</div>
                    <div class="label-for col-sm-15 col-xs-12">
						<input type="text" class="col-xs-20 col-sm-12 required verify-judge"  data-tip="最多输入20个汉字" data-valid="maxLength:20" data-error="最多20个字符"  id="brief_desc" name="brief_desc" value="<?php if(SlightPHP\Tpl::$_tpl_vars["isTeacher"]=='1'){; ?><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->brief_desc; ?><?php }; ?><?php }else{; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->profile->desc; ?><?php }; ?>">
						<!--<div id="cGray_length col-xs-20"><?php echo tpl_modifier_tr('最多输入20个汉字','site.teacher'); ?></div>		-->
					</div>
				</li>
                <!--教师资料部分-->
                <?php if(SlightPHP\Tpl::$_tpl_vars["isTeacher"]===true){; ?>
						<li class="fs16 fob lh22 teacher-modul"><?php echo tpl_modifier_tr('教师资料','site.teacher'); ?></li>
						<li class="form-group ">
							<div class="control-label col-xs-8 col-sm-5">
								<em class='cRed'>*</em><?php echo tpl_modifier_tr('毕业院校','site.teacher'); ?>：
							</div>
							<div class="label-for col-xs-10 col-sm-10">
								<input type="text" id="college" class="col-xs-20 col-sm-12 required verify-judge" name="college" data-tip="最多15个汉字" data-valid="isNonEmpty||onlyZh||maxLength:15" data-error="毕业院校不能为空||只能输入汉字||最多15个汉字"  value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->college)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->college; ?><?php }; ?>">
							</div>
						</li>
                        <li class="form-group ">
                            <div class="control-label col-xs-8 col-sm-5"><?php echo tpl_modifier_tr('学历','site.teacher'); ?>：</div>
                            <div class="label-for col-xs-10 col-sm-10">
                                <div class="divselect divselect-32"  id="diploma" >
                                    <cite>
                                        <span class="cite-icon"></span>
                                        <span class="cite-text"></span>
                                    </cite>
                                    <dl>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==-1){; ?>selected = selected<?php }; ?>>
                                            <a href="javascript:;" selectid="-1"><?php echo tpl_modifier_tr('不显示','site.teacher'); ?></a>
                                        </dd>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==1){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="1"><?php echo tpl_modifier_tr('大专','site.teacher'); ?></a>
                                        </dd>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==2){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="2"><?php echo tpl_modifier_tr('本科','site.teacher'); ?></a>
                                        </dd>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==3){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="3"><?php echo tpl_modifier_tr('硕士','site.teacher'); ?></a>
                                        </dd>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma==4){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="4"><?php echo tpl_modifier_tr('博士','site.teacher'); ?></a>
                                        </dd>
                                    </dl>
                                    <input type="hidden" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->diploma; ?><?php }; ?>"  name="diploma">
                                </div>
                            </div>
                        </li>
                        <li class="form-group ">
                            <div class="control-label col-xs-8 col-sm-5">
                            	<em class='cRed'>*</em><?php echo tpl_modifier_tr('教龄','site.teacher'); ?>：
                            </div>
                            <div class="label-for col-xs-10 col-sm-10">
                                <div class="divselect divselect-32"  >
                                    <cite>
                                        <span class="cite-icon"></span>
                                        <span class="cite-text"></span>
                                    </cite>
                                    <dl>
                                        <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1;SlightPHP\Tpl::$_tpl_vars["i"]<31;SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==SlightPHP\Tpl::$_tpl_vars["i"]){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="<?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["i"]; ?><?php echo tpl_modifier_tr('年','site.teacher'); ?></a>
                                        </dd>
                                        <?php }; ?>
                                        <dd <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)&&SlightPHP\Tpl::$_tpl_vars["teacher"]->years==31){; ?>selected=selected<?php }; ?>>
                                            <a href="javascript:;" selectid="31"><?php echo tpl_modifier_tr('30年以上','site.teacher'); ?></a>
                                        </dd>
                                    </dl>
                                    <input type="hidden"  value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->years)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->years; ?><?php }; ?>" name="years">
                                </div>
                            </div>
                        </li>
                        <li class="item_1 form-group" id="class-status">
                            <div class="control-label col-xs-8 col-sm-5">
								<em class='cRed'>*</em><?php echo tpl_modifier_tr('教学阶段','site.teacher'); ?>：
							</div>
                            <div class="label-for col-xs-10 col-sm-10">
                                <label for="s1"><input type="checkbox" class="scopes" name="scopes[]" id="s1" value="preschool" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('preschool',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s1">&nbsp;<?php echo tpl_modifier_tr('学前','site.teacher'); ?>
                                </label>
                                <label for="s2"><input type="checkbox" class="verify-judge scopes" name="scopes[]" value="primary" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('primary',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s2">&nbsp;<?php echo tpl_modifier_tr('小学','site.teacher'); ?></label>
                                <label for="s3"><input type="checkbox" class="scopes" name="scopes[]" value="junior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('junior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s3">&nbsp;<?php echo tpl_modifier_tr('初中','site.teacher'); ?></label>
                                <label for="s4" ><input type="checkbox"class="scopes" name="scopes[]" value="senior" <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)&&in_array('senior',SlightPHP\Tpl::$_tpl_vars["teacher"]->scopes)){; ?>checked<?php }; ?> id="s4">&nbsp;<?php echo tpl_modifier_tr('高中','site.teacher'); ?></label>
                                <div  class="tips tip1 pl20">
                                    <span class="tips-icon"></span>
                                    <span class="tips-text cRed"></span>
                                </div>
                            </div>
                        </li>
                        <li  class="item_2 form-group">
                            <div class="control-label col-xs-8 col-sm-5"><em class='cRed'>*</em><?php echo tpl_modifier_tr('擅长学科','site.teacher'); ?>：</div>
							<div class="label-for  col-xs-12 col-sm-15">
                                <div class="dropdown col-xs-6 col-sm-6 pd0">
                                    <!--<div class="t-subj-content dropdown-input" id="t-subj-content">-->
                                    <div class="dropdown-input" id="t-subj-content">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data)){; ?>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["good_subject"]->data as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["v"]->name)){; ?>
                                        <div class="delect-subj dropdown-show-tab pd0 c-fl" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>" contenteditable="false">
                                            <div class="left-side"></div>
                                            <div class="tab-delete"></div>
                                        <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->name,'course.list'); ?>
                                            <input type="hidden" class="verify-judge" value="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->fk_tag; ?>" name="good_subject[]">
                                        </div>
                                        <?php }; ?>
                                        <?php }; ?>
                                        <?php }; ?>
                                    </div>
                                    <div class=" dropdown-box pd0 " id="t-subjects-list">
                                        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["setArr"])){; ?>
                                        <?php foreach(SlightPHP\Tpl::$_tpl_vars["setArr"] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
                                        <div class="<?php if(isset(SlightPHP\Tpl::$_tpl_vars["tagArr"])&&in_array(SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag,SlightPHP\Tpl::$_tpl_vars["tagArr"])){; ?>on<?php }; ?> dropdown-tab" rel="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]->pk_tag; ?>">
                                            <div class="left-side"></div>
                                            <div class="tab-add"></div>
                                            <?php echo tpl_modifier_tr(SlightPHP\Tpl::$_tpl_vars["v"]->name,'course.list'); ?>
                                        </div>
                                        <?php }; ?>
                                        <?php }; ?>
                                        <div class="clear"></div>
                                    </div>
                                    <!--<div class="col-xs-20 t-subjects-list pd0 mt10" id="t-subjects-list">-->
                                </div>
                                <div class="t-subjects-but col-xs-8 col-sm-8">
                                    <div  class="tips tip1 pl20 hidden-xs">
                                        <span class="tips-icon"></span>
                                        <span class="tips-text"><?php echo tpl_modifier_tr('至少选择一项，最多选择三项','site.teacher'); ?></span>
                                    </div>
                                </div>
							</div>
                        </li>
                        <li class="item_8 form-group">
                            <div class="control-label col-xs-8 col-sm-5">
                            	<em class='cRed'>*</em><?php echo tpl_modifier_tr('我的头衔','site.teacher'); ?>：
                            </div>
                            <div class="label-for col-xs-10 col-sm-10">
                                <input type="text" class="verify-judge col-sm-7 col-xs-15 required" data-valid="isNonEmpty||onlyZh||maxLength:10"  data-error="不能为空||只能输入汉字||最多10个汉字" data-tip="最多输入10个汉字"   id="title" name="title" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->title)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->title; ?><?php }; ?>">
							</div>
                        </li>
                        <li class="form-group desc">
                            <div class="control-label col-xs-8 col-sm-5">
                            	<em class='cRed'>*</em><?php echo tpl_modifier_tr('教师介绍','site.teacher'); ?>：
                            </div>
                            <div class="label-for col-sm-15 col-xs-12">
                                <textarea name="desc" id="desc" cols="30" rows="3" class="verify-judge col-sm-12 col-xs-20" placeholder=""><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["teacher"]->desc)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["teacher"]->desc; ?><?php }; ?></textarea>
                                <div class="cGray col-sm-15 col-xs-20"><?php echo tpl_modifier_tr('用一段话来介绍自己,让学生和家长更好的了解你,最多1000字','site.teacher'); ?></div>
                                <div class="tips  ml5 col-xs-20">
                                    <span class="tips-icon"></span>
                                    <span class="tips-text vRed"></span>
                                </div>
							</div>
                    </li>
				<?php }; ?>
                <li>
                    <div class="label col-sm-5 col-xs-6"> </div>
					<div class="label-for col-sm-15 col-xs-14">
						<button class="btn" id="submit"><?php echo tpl_modifier_tr('保存','site.user'); ?></button>
					</div>
                </li>
                <input type="hidden" name="isTeacher" value="<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["isTeacher"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["isTeacher"]; ?><?php }; ?>">
            </ul>
            <div class="clear"></div>
          </div>
          <!--con end-->
        </div>
        <!--right start-->
      </div>
    </div>
</section>
    <!--studentPortal end-->
<div id="rightWindow"></div>
<script>$("#rightWindow").load("/org.rightWindow.rightWindow");</script>
<?php echo tpl_function_part("/site.main.footer"); ?>
</body>
</html>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.birthday.js'); ?>"></script>
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
			url :'/student.main.checkNickName',
			data: { nickname:nickname },
			dataType: 'json',
			success:function(data){
				if(data.code == 0){
					$('.tip1').html('');
					$('#nickname').removeClass('box-shadow');
				}else if(data.code == -1){
					$('.tipnickname').html(data.msg);
					$('#nickname').addClass('box-shadow');
					$('#nickname').focus();

				}else if(data.code == -2){
					$('.tip1').html(data.msg);
					$('#nickname').addClass('box-shadow');
				}
			}

		});

	}

//介绍
$('#desc').blur(function(){
		var desc = $(this).val();
		$('#desc').removeClass('verify-error').next().next().removeClass('error');
		 if( !desc) {
		 	$('#desc').next().next().addClass('error').find('.tips-text').html("<?php echo tpl_modifier_tr('请填写教师简介','site.user'); ?>");
		    $('#desc').addClass('verify-error');
		    return;
		 }else if(desc.length>1000){
		 	$('#desc').next().next().addClass('error').find('.tips-text').html('<br/>最多输入1000个汉字');
			$('#desc').addClass('verify-error');
			return;
		 }else{
		 	$('#desc').next().next().removeClass('error').find('.tips-text').html('');
			$('#desc').removeClass('verify-error');
		 }
});
$("#birthday").birthday();
var year  = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->year)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->year; ?><?php }; ?>";
var month = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->month)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->month; ?><?php }; ?>";
var day   = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->day)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->birthday->day; ?><?php }; ?>";
if( year && year!='0000' ) {
    $('#birthday-year').find('input').val(year);
    $('#birthday-year').find('dd a[selectid='+year+']').parent().attr('selected','selected');
    $('#birthday-year').find('.cite-text').text(year+'年');
    $('#birthday-year').find('.cite-text').trigger('valuechange');
}
if( month && month!='00'  ) {
    $('#birthday-month').find('dl dd ').each(function(){
        var se_month = addzero($(this).find('a').attr('selectid'));
        if( se_month == month ) {
            $(this).attr('selected','selected');
            $(this).parent().next('input').val(month);
            $(this).parent().prev('cite').find('.cite-text').trigger('valuechange');
            $(this).parent().prev('cite').find('.cite-text').text(month+'月').parent();
            return false;
        }
    });
}
if( day && day!='00' ) {
    $('#birthday-day').find('dl dd ').each(function(){
        var se_day = addzero($(this).find('a').attr('selectid'));
        if( se_day == day ) {
            $(this).attr('selected','selected');
            $(this).parent().next('input').val(parseInt(day));
            $(this).parent().prev('cite').find('.cite-text').trigger('valuechange');
            $(this).parent().prev('cite').find('.cite-text').text(day+'日');
            return false;
        }
    });
}else{
    $('#birthday-day').find('dl dd ').eq(0).attr('selected','selected');
    $('#birthday-day').find('.cite-text').text('01日');
    $('#birthday-day').find('input').val(1);
}
$(function(){
    $('.about-us-btn').on('click', function(){
        layer.open({
            type: 1,
            title:['添加学校','color:#fff;background:#ffa81d'],
            area:['410px', '275px'],
            shadeClose: true, //点击遮罩关闭
            content: ['/layer/about_us_layer.html','no']
        });
    });
    var _judgeText='';
    function _realName(className,text){
        $(this).parent().removeClass('active tip error').addClass(className).find('div.tips span.tips-text').text(text);
    }
	$('#real_name').on({
        blur:function () {
            var real_name = $.trim($(this).val());
            if( !real_name ) {
                _realName.call(this,'error','真实姓名不能为空');
                return;
            }else{
                var reg = /^[\u4E00-\u9FA5]+$/;
                var eng_reg = /^[a-zA-Z]+$/;
                if(reg.test(real_name)){
                    if(real_name.length>10){
                        _realName.call(this,'error','真实姓名不超过10个汉字');
                        return;
                    }else{
                        _realName.call(this,'tip','');
                    }
                }else if(eng_reg.test(real_name)){
                    if(real_name.length>50){
                        _realName.call(this,'error','真实姓名不超过50个英文字符');
                        return;
                    }else{
                        _realName.call(this,'tip','');
                    }
                }else{
                    _realName.call(this,'error','真实姓名只能输入汉字或者英文字符');
                }
            }
        },
        focus:function () {
            if($(this).parent().hasClass('tip')){
                _judgeText="";
            }else{
                _judgeText="<?php echo tpl_modifier_tr('请填写真实姓名','site.user'); ?>";
            }
            _realName.call(this,'active',_judgeText);
        }
    });
    $(".label-for input[name='scopes[]']").on('click',function() {
        $(this).parent().parent().find('.tips').css('display','none');
    })
    var region_level0 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level0; ?><?php }; ?>";
    var region_level1 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level1; ?><?php }; ?>";
    var region_level2 = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->region_level2; ?><?php }; ?>";
    var school_id     = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_id; ?><?php }; ?>";
    var school_type   = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->school_type; ?><?php }; ?>";
    var school_grade  = "<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["userinfo"]->student)){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["userinfo"]->student->grade; ?><?php }; ?>";
    if(school_type == 0){
        school_type = 1;
    }
    function _getSchoolItem() {
        var school_type = $('#school_type input').val();
        $('input[name=school_id]').val('');
        if(school_type == 1 || school_type == 6){
            var pid = $("#level2 input").val() ? $("#level2 input").val() : $("#level1 input").val();
            $.get("/user.course.ListSchool",{ 'school_type':school_type,
                'region_id':pid },function(item){
                $("#school dl").html("<dd><a href='javascript:;' selectid=''><?php echo tpl_modifier_tr('请选择学校','site.user'); ?></a></dd>");
                if(item.length>0){
                    for(var i in item){
                        var s="";
                        if(item[i].school_id== school_id){
                            s=" selected ";
                            $("#school").find('input').val(item[i].school_id);
                            $("#school").find('.cite-text').text(item[i].school_name);
                            $("#school dl").append("<dd selected="+s+" ><a href='javascript:;' selectid='"+item[i].school_id+"'>"+item[i].school_name+"</a></dd>");
                        }else{
                            $("#school dl").append("<dd><a href='javascript:;' selectid='"+item[i].school_id+"'>"+item[i].school_name+"</a></dd>");
                        }
                    }
                    $("#school").removeClass('hidden');
                }else{
                    $("#school").addClass('hidden').find('input').val('');
                    $("#school").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择学校','site.user'); ?>");
                }
            },"json");
        }else{
            $('#school').addClass('hidden').find('input').val('');
            $("#school").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择学校','site.user'); ?>");
        }
    }
    function _regionChange(regionId,next,last) {
        $('#region1').css('display','none');
        var pid = $(this).parent().parent().find('input').val();
        if(pid){
            $.get("/user.course.ListRegion."+pid,{ },function(item){
                $(next).find('dl').html("");
                if(regionId==''){
                    $(next).find('cite .cite-text').text("请选择");
                    $(next).find('input').val('');
                }
                if(last && regionId==''){
                    $(last).find('cite .cite-text').text("请选择");
                    $(last).find('input').val('');
                    $(last).addClass('hidden');
                }else{
                    $(last).removeClass('hidden');
                }
                if(item){
                    $(next).find('dl').append("<dd> <a href='javascript:;' selectid=''>请选择</a></dd>");
                    for(var i in item){
                        var s="";
                        if(item[i].region_id == regionId){
                            s="selected";
                            $(next).find(".cite-text").text(item[i].name);
                            $(next).find('input').val(item[i].region_id);
                            $(next).find('dl').append(" <dd selected='"+s+"'> <a href='javascript:;'  selectid='"+item[i].region_id+"'>"+item[i].name+"</a></dd>");
                        }else{
                            $(next).find('dl').append(" <dd > <a href='javascript:;'  selectid='"+item[i].region_id+"'>"+item[i].name+"</a></dd>");
                        }
                    }
                    $(next).removeClass('hidden');
                    if(next=='#level2'){
                        _getSchoolItem();
                    }
                }else{
                    $(next).addClass('hidden').find('input').val('');
                    if(next=='#level2'){
                        _getSchoolItem();
                    }
                }
            },"json").fail(function(){
                $(next).addClass('hidden').find('input').val('');
                if(last){
                    $(last).addClass('hidden').find('input').val('');
                }
                if(next=='#level2'){
                    _getSchoolItem();
                }
            });
        }else{
            $(next).addClass('hidden').find('dl').html("");
            $(next).find('input').val('');
            $(next).find('dl').append("<dd> <a href='javascript:;' selectid=''>请选择</a></dd>");
            if(last){
                $(last).find('dl').html("");
                $(last).find('input').val('');
                $(last).find('dl').append("<dd> <a href='javascript:;' selectid=''>请选择</a></dd>");
                $(last).addClass('hidden');
            }
        }
    }
    var _regionChangeCount1 = 0;
    var _regionChangeCount2 = 0;
    $("#level0 cite .cite-text").on('valuechange',function () {
        _regionChangeCount1++;
        if(_regionChangeCount1 > 1) {
            region_level1 = '';
        }
        _regionChange.call(this,region_level1,'#level1','#level2');
    });
    if(region_level0){
        $("#level0 input").val(region_level0);
    }
    $("#level1 cite .cite-text").on('valuechange',function () {
        _regionChangeCount2++;
        if(_regionChangeCount2 > 1) {
            region_level2 = '';
        }
        _regionChange.call(this,region_level2,'#level2');
    });
    $("#level2 cite .cite-text").on('valuechange',function () {
        _getSchoolItem();
    });
    $("#school_type cite .cite-text").on('valuechange',function (event) {
        var school_type = $('#school_type input').val();
        $('input[name=school_id],input[name=grade]').val('');
        if(school_type == 1 || school_type == 6){
            var pid = $("#level2 input").val() ? $("#level2 input").val() : $("#level1 input").val();
            $("#school").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择学校','site.user'); ?>");
            $("#school_grade").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择年级','site.user'); ?>");
            $.get("/user.course.ListSchool",{ 'school_type':school_type,
                'region_id':pid },function(item){
                $("#school dl").html("<dd><a href='javascript:;' selectid=''><?php echo tpl_modifier_tr('请选择学校','site.user'); ?></a></dd>");
                if(item.length>0){
                    for(var i in item){
                        var s="";
                        if(item[i].school_id== school_id){
                            s=" selected ";
                            $("#school").find('input').val(item[i].school_id);
                            $("#school").find('.cite-text').text(item[i].school_name);
                            $("#school dl").append("<dd selected="+s+" ><a href='javascript:;' selectid='"+item[i].school_id+"'>"+item[i].school_name+"</a></dd>");
                        }else{
                            $("#school dl").append("<dd><a href='javascript:;' selectid='"+item[i].school_id+"'>"+item[i].school_name+"</a></dd>");
                        }
                    }
                    $("#school").removeClass('hidden');
                }else{
                    $("#school").addClass('hidden').find('input').val('');
                    $("#school").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择学校','site.user'); ?>");
                }
            },"json");
            var name = $(this).parent().parent().find('input').attr('name');
            $('#grades1').css('display','none');
            $.ajax({
                type:'post',
                url :'/student.main.ListGrade',
                data: { 'school_type':school_type },
                dataType: 'json',
                success:function(item){
                    $("#school_grade dl").html("<dd><a href='javascript:;' selectid=''><?php echo tpl_modifier_tr('请选择年级','site.user'); ?></a></dd>");
                    if(item){
                        for(var i in item){
                            var s="";
                            if(item[i].grade_id== school_grade){
                                s=" selected ";
                                $("#school_grade").find('input').val(item[i].grade_id);
                                $("#school_grade").find('.cite-text').text(item[i].grade_name);
                                $("#school_grade dl").append("<dd selected="+s+" ><a href='javascript:;' selectid='"+item[i].grade_id+"'>"+item[i].grade_name+"</a></dd>");
                            }else{
                                $("#school_grade dl").append("<dd  ><a href='javascript:;' selectid='"+item[i].grade_id+"'>"+item[i].grade_name+"</a></dd>");
                            }
                        }
                        $("#school_grade").removeClass('hidden');
                    }else{
                        $("#school_grade").addClass('hidden');
                    }
                }
            });
        }else{
            $("#school").addClass('hidden').find('input').val('');
            $("#school").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择学校','site.user'); ?>");
            $("#school_grade").addClass('hidden').find('input').val('');
            $("#school_grade").find('.cite-text').text("<?php echo tpl_modifier_tr('请选择年级','site.user'); ?>");
        }
    });
    if(school_type == 1 || school_type == 6){
        $.ajax({
            type:'post',
            url :'/student.main.ListGrade',
            data: { school_type:school_type },
            dataType: 'json',
            success:function(item){
                $("#school_grade dl").html("<dd><a href='javascript:;' selectid=''><?php echo tpl_modifier_tr('请选择年级','site.user'); ?></a></dd>");
                if(item){
                    for(var i in item){
                        var s="";
                        if(item[i].grade_id== school_grade){
                            s=" selected ";
                            $("#school_grade").find('input').val(item[i].grade_id);
                            $("#school_grade").find('.cite-text').text(item[i].grade_name);
                            $("#school_grade dl").append("<dd selected="+s+" ><a href='javascript:;' selectid='"+item[i].grade_id+"'>"+item[i].grade_name+"</a></dd>");
                        }else{
                            $("#school_grade dl").append("<dd  ><a href='javascript:;' selectid='"+item[i].grade_id+"'>"+item[i].grade_name+"</a></dd>");
                        }
                    }
                    $("#school_grade").removeClass('hidden');
                }else{
                    $("#school_grade").addClass('hidden');
                }
            }
        });
    }else{
        $('#school').addClass('hidden');
        $("#school_grade").addClass('hidden');
    }
    $('.form').on('click', '#submit', function(event){
        event.preventDefault();
        var year   = $('input[name=year]').val();
        var month  = $('input[name=month]').val();
        var day    = $('input[name=day]').val();
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
        var validate = { };
        var validateJudge = true;
        var params = {
            'name' : $('input[name=name]').val().replace(/(^\s*)|(\s*$)/g, ""),
            'real_name' : $('input[name=real_name]').val().replace(/(^\s*)|(\s*$)/g, ""),
            'gender'    : $('input[name=gender]:checked').val(),
            'birthday'  : year + '-' + month + '-'+ day,
            'region_level0' : $('input[name=region_level0]').val(),
            'region_level1' : $('input[name=region_level1]').val(),
            'region_level2' : $('input[name=region_level2]').val(),
            'school_type'   : $('input[name=school_type]').val(),
            'school_id'	    : $('input[name=school_id]').val(),
            'grade'	        : $('input[name=grade]').val(),
            'brief_desc'    : $('input[name=brief_desc]').val(),
            'college'		: $('input[name=college]').val(),
            'diploma'		: $('input[name=diploma]').val(),
            'years'			: $('input[name=years]').val(),
            'scopes'		: Arr,
            'good_subject'	: gb,
            'title'			: $('input[name=title]').val(),
            'desc'			: $('textarea[name=desc]').val(),

        };
        $('#real_name').blur();
        if($('#real_name').parent().hasClass('error')){
            validate['real_name']=false;
        }else{
            validate['real_name']=true;
        };
        if(!params.region_level0 || !params.region_level1){
            validate['region']=false;
            $('#region1').css('display','block');
            $('#region1 .tips-text').html('请选择常住地区');
            $('#region1 .tips-icon').css('visibility','visible');
            $('#region1').parent().addClass('error').find('input[name=region_level0]').parent().addClass('error');
        }else{
            if(!params.region_level2 && !$('#level2').hasClass('hidden')){
                validate['region']=false;
                $('#region1').css('display','block');
                $('#region1 .tips-text').html('请选择常住地区');
                $('#region1 .tips-icon').css('visibility','visible');
                $('#region1').parent().addClass('error').find('input[name=region_level0]').parent().addClass('error');
            }else{
                validate['region']=true;
                $('#region1').css('display','none');
                $('#region1 .tips-text').html('');
            }
        };
        if(params.school_type != 2 && params.school_type != 3){
            if(!params.grade) {
                $('#grades1').css('display','block').find('.tips-text').html('请选择学校/年级');
                $('#grades1').find('.tips-icon').css('visibility','visible');
                $('#grades1').parent().addClass('error').find('input[name=school_type]').parent().addClass('error');
                validate['grade']=false;
            }else {
                if(!params.school_id && !$('#school').hasClass('hidden')){
                    $('#grades1').css('display','block').find('.tips-text').html('请选择学校/年级');
                    $('#grades1').find('.tips-icon').css('visibility','visible');
                    $('#grades1').parent().addClass('error').find('input[name=school_type]').parent().addClass('error');
                    validate['grade']=false;
                }else{
                    $('#grades1').css('display','none');
                    validate['grade']=true;
                }
            }
        }
        if($("input[name=isTeacher]").val()=='1'){
            if(params.scopes.length=='0'){
                validate['scopes']=false;
                $('#class-status .tips').css('display','block');
                $("#class-status .tips-text").html("<?php echo tpl_modifier_tr('请选择教学领域','site.user'); ?>");
                $('#class-status .tips-icon').css('visibility','visible');
                $('#class-status').find('input').parent().addClass('error');
            }else{
                validate['scopes']=true;
            };

            $('.dropdown').find('.dropdown-input').trigger('dropdownValidate');
            if(params.good_subject.length==0){
                validate['good_subject']=false;
            }else{
                validate['good_subject']=true;
            }
        }
//        //教师部分
        if(params.school_type == 2 || params.school_type == 3){
            params.school_id = 0;
            params.grade = 0;
        }
        for(var i in validate){
            if(!validate[i]){
                validateJudge = false;
            }
        }

        if($('.form').validate('submitValidate')&&validateJudge){
            $.ajax({
                type:'post',
                url :'/student.main.setInfo',
                data: params,
                dataType: 'json',
                success:function(data){
                    if(data.result.code == 0){
                        layer.msg(data.result.msg);
                        location.reload();
                    }else if(data.result.code == -3){
                        layer.msg(data.result.msg);
                    }else if(data.result.code == -1){
                        $('#nickname').focus().parent().removeClass('active tip error').addClass('error').find('div.tips span.tips-text').text(data.result.msg);
                    }else if(data.result.code == -2){
                        $('#real_name').focus().parent().removeClass('active tip error').addClass('error').find('div.tips span.tips-text').text(data.result.msg);
                    }
                }

            });
        }else{
            var scrollTop;
            var  $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $('.verify-judge').each(function () {
                if($(this).parent().hasClass('error')){
                    scrollTop=$(this).parent().offset().top;
                    $body.scrollTop(scrollTop);
                    $(this).blur();
                    return false;
                }
            });
            return false;
        }
    });
  $('.region_info,.school_info').on('click','.divselect',function () {
    $(this).parent().find('.tips').css('display','none');
  })
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
<script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/jquery/jquery.dropdown.tab.js'); ?>"></script>
</body>
</html>
