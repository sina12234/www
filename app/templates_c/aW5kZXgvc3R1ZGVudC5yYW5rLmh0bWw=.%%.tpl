<!DOCTYPE html>
<html>
<head>
<title>排行榜 - 云课 - 专业的在线学习平台</title>
<meta name="title" content="排行榜 - 云课 - 专业的在线学习平台">
<meta name="keywords" content="云课 - Yunke K12 在线学习 直播 云课网 在线教育">
<meta name="description" content="云课(Yunke.com) -专业的K12在线学习平台。以直播授课为核心，打破时间与空间的界限，为教师高效教学、学生高效学习、家长高效管控及机构高效管理提供完美解决方案">
<?php echo tpl_function_part("/index.main.header"); ?>
</head>
<body>
<?php echo tpl_function_part("/index.main.top"); ?>
<?php echo tpl_function_part("/index.main.nav/rank"); ?>
<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["uid"])){; ?>
<section class="mt20">
    <div class="container">
                <div class="curr-user-rank fs14">
                    <div class="col-lg-5 curr-user-info col-sm-7 col-xs-20">
                        <span class="user-face col-lg-6"><img src="<?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->avatar->large; ?>" alt=""></span>
                        <div class="user-name col-lg-12 fs16 col-sm-12 col-xs-10"><?php echo SlightPHP\Tpl::$_tpl_vars["userInfo"]->name; ?> <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->fk_level; ?>"></i></a></div>
                        <div class="user-value cGray2 col-sm-12 col-xs-10">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["userLevel"]->score; ?></div>
                    </div>
                    <div class="col-lg-15 rank-sum cGray2 col-sm-13">
                        <p>
							周排行榜：第<span class="cRed"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekSort"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["weekSort"]; ?><?php }else{; ?>--<?php }; ?></span>名  
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekSort"])){; ?><span class="ml20">击败全国<!--i class="rank-up"></i--><?php echo SlightPHP\Tpl::$_tpl_vars["weekPercent"]; ?>用户</span>
							<?php }else{; ?>
							<span>您暂时还没有排名</span>
							<?php }; ?>
						</p>
                        <p>
							月排行榜：第<span class="cRed"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthSort"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["monthSort"]; ?><?php }else{; ?>--<?php }; ?></span>名  
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthSort"])){; ?><span class="ml20">击败全国<!--i class="rank-up"></i--><?php echo SlightPHP\Tpl::$_tpl_vars["monthPercent"]; ?>用户</span>
							<?php }else{; ?>
							<span>您暂时还没有排名</span>
							<?php }; ?>
						</p>
                        <p>
							总排行榜：第<span class="cRed"><?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allSort"])){; ?><?php echo SlightPHP\Tpl::$_tpl_vars["allSort"]; ?><?php }else{; ?>--<?php }; ?></span>名  
							<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allSort"])){; ?><span class="ml20">击败全国<!--i class="rank-up"></i--><?php echo SlightPHP\Tpl::$_tpl_vars["allPercent"]; ?>用户</span>
							<?php }else{; ?>
							<span>您暂时还没有排名</span>
							<?php }; ?>
						</p>
                    </div>
        </div>
</div>
</section>
<?php }; ?>
<!-- 列表 -->
<section class="p20">
    <div class="container">
        <div>
            <p class="userrank-tab col-xs-20 col-sm-20 pd0" id="rank-tab"><a href="javascript:void(0)" class="curr col-xs-6 col-sm-4">周排行榜</a><a href="javascript:void(0)" class="col-xs-8 col-sm-4">月排行榜</a><a href="javascript:void(0)" class="col-xs-6 col-sm-4">总排行榜</a></p></div>
        <div id="rank">
            <div class="userrank-c" style="display:block">
                <div class="rank-toplist">
                    <ul class="rank-toplistc col-lg-16 col-xs-20 col-sm-20">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekRankHead"])){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1])){; ?>
                        <li class="col-xs-20 pd0 col-sm-6">
                            <p class="top-userface"><span class="rank-topbg2">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1]->name; ?>"></span><i class="rank-top2"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][1]->fk_level; ?>"></i></a>
                            </p>
                        </li>
						<?php }; ?>
                        <li class="col-xs-20 pd0 col-sm-8">
                            <p class="top-userface"><span class="rank-topbg1">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["weekRankHead"][0]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][0]->name; ?>"></span><i class="rank-top1"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][0]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][0]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][0]->fk_level; ?>"></i></a>
                            </p>
                        </li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2])){; ?>
                        <li class="col-xs-20 pd0 col-sm-6">
                            <p class="top-userface"><span class="rank-topbg3">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2]->name; ?>"></span><i class="rank-top3"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["weekRankHead"][2]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php }; ?>
					<?php }; ?>
                    </ul>
                </div>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekRankLeft"])){; ?>
                <ul class="rank-hotlist-left col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-xs-12 col-sm-10">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["weekRankLeft"] as SlightPHP\Tpl::$_tpl_vars["wlk"]=>SlightPHP\Tpl::$_tpl_vars["wlv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["wlv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["wlv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["wlv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["wlv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["wlv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["wlv"]->user_score; ?></p>
                    </li>
					<?php }; ?>
                </ul>
				<?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["weekRankRight"])){; ?>
                <ul class="rank-hotlist-right col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li class="visible-lg"><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-xs-12 col-sm-10">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["weekRankRight"] as SlightPHP\Tpl::$_tpl_vars["wrk"]=>SlightPHP\Tpl::$_tpl_vars["wrv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["wrv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["wrv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["wrv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["wrv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["wrv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["wrv"]->user_score; ?></p>
                    </li>
					<?php }; ?>
				</ul>
				<?php }; ?>
            </div>
            <div class="userrank-c">
                    <div class="rank-toplist">
                    <ul class="rank-toplistc col-lg-16">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthRankHead"])){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1])){; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg2">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1]->name; ?>"></span><i class="rank-top2"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][1]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php }; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg1">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["monthRankHead"][0]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][0]->name; ?>"></span><i class="rank-top1"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][0]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][0]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][0]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2])){; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg3">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2]->name; ?>"></span><i class="rank-top3"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2]->user_score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["monthRankHead"][2]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php }; ?>
					<?php }; ?>
                    </ul>
                    </div>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthRankLeft"])){; ?>
                <ul class="rank-hotlist-left col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li class="visible-lg"><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-xs-12 col-sm-10">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["monthRankLeft"] as SlightPHP\Tpl::$_tpl_vars["mlk"]=>SlightPHP\Tpl::$_tpl_vars["mlv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["mlv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["mlv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["mlv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["mlv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["mlv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5"><?php echo SlightPHP\Tpl::$_tpl_vars["mlv"]->user_score; ?></p>
                    </li>
					<?php }; ?>
                </ul>
				<?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["monthRankRight"])){; ?>
                <ul class="rank-hotlist-right col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li class="visible-lg"><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-xs-12 col-sm-10">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["monthRankRight"] as SlightPHP\Tpl::$_tpl_vars["mrk"]=>SlightPHP\Tpl::$_tpl_vars["mrv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["mrv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["mrv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["mrv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["mrv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["mrv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["mrv"]->user_score; ?></p>
                    </li>
					<?php }; ?>
				</ul>
				<?php }; ?>
                </div>
                <div class="userrank-c">
                    <div class="rank-toplist">
                    <ul class="rank-toplistc col-lg-16">
					<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allRankHead"])){; ?>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allRankHead"][1])){; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg2">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["allRankHead"][1]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][1]->name; ?>"></span><i class="rank-top2"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][1]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][1]->score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][1]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php }; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg1">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["allRankHead"][0]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][0]->name; ?>"></span><i class="rank-top1"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][0]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][0]->score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][0]->fk_level; ?>"></i></a>
                           </p>
                        </li>
						<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allRankHead"][2])){; ?>
                        <li>
                            <p class="top-userface"><span class="rank-topbg3">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["allRankHead"][2]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][2]->name; ?>"></span><i class="rank-top3"></i></p>
                            <p class="top-userinfo">
                                <span class="fs16"><?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][2]->name; ?></span>
                                <span class="fs14 cGray">经验值：<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][2]->score; ?></span>
                                <a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["allRankHead"][2]->fk_level; ?>"></i></a>
                            </p>
                        </li>
						<?php }; ?>
					<?php }; ?>
                    </ul>
                    </div>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allRankLeft"])){; ?>
                <ul class="rank-hotlist-left col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-xs-12 col-sm-10">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["allRankLeft"] as SlightPHP\Tpl::$_tpl_vars["alk"]=>SlightPHP\Tpl::$_tpl_vars["alv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["alv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["alv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["alv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["alv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["alv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["alv"]->score; ?></p>
                    </li>
					<?php }; ?>
                </ul>
				<?php }; ?>
				<?php if(!empty(SlightPHP\Tpl::$_tpl_vars["allRankRight"])){; ?>
                <ul class="rank-hotlist-right col-lg-10 fs16 col-xs-20 col-sm-20">
                    <li class="visible-lg"><p class="col-lg-5 col-xs-3 col-sm-5">排名</p><p class="col-lg-10 col-sm-10 col-xs-12">用户昵称</p><p class="col-lg-5 col-xs-5 col-sm-5">经验值</p></li>
					<?php foreach(SlightPHP\Tpl::$_tpl_vars["allRankRight"] as SlightPHP\Tpl::$_tpl_vars["ark"]=>SlightPHP\Tpl::$_tpl_vars["arv"]){; ?>
                    <li>
                        <p class="col-lg-5 col-xs-3 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["arv"]->sort; ?></p>
                        <p class="col-lg-10 col-xs-12 col-sm-10">
                            <span class="user-face col-lg-6 col-xs-5 col-sm-5">
							<img src="<?php echo utility_cdn::file(SlightPHP\Tpl::$_tpl_vars["arv"]->thumb_big); ?>" alt="<?php echo SlightPHP\Tpl::$_tpl_vars["arv"]->name; ?>">
							</span>
                            <span class="user-name col-lg-14 fs16 col-xs-12 col-sm-10"><em><?php echo SlightPHP\Tpl::$_tpl_vars["arv"]->name; ?></em><a href="/index.rank.rule" target="_blank"><i class="level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["arv"]->fk_level; ?>"></i></a></span>
                        </p>
                        <p class="col-lg-5 col-xs-5 col-sm-5"><?php echo SlightPHP\Tpl::$_tpl_vars["arv"]->score; ?></p>
                    </li>
					<?php }; ?>
				</ul>
				<?php }; ?>
        </div>
    </div>
</div>
</section>
<!-- footer -->
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
</html>
<script>
$(function() {
    $('#rank-tab a').click(function(){
        $(this).addClass('curr').siblings().removeClass('curr');
        $('#rank').find('.userrank-c:eq(' + $(this).index() + ')').show(100).siblings().hide(100);
    })
});
</script>
