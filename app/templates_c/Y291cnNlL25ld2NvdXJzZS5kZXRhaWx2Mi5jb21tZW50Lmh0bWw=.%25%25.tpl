<!-- 学员评论 -->
<section id="score_comment" style="display:block;">
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["commentList"]['data'])){; ?>
    <div class="col-sm-20 fs16 pdb15 pdt15" style="padding:15px 0;text-indent: 15px;border-left:1px solid #eee;border-right:1px solid #eee;"><i class="c-vertical-line mr5"></i><?php echo tpl_modifier_tr('学员评论','course.info'); ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["commentList"]['totalSize']; ?>)</div>
    <div class="i-comment" id="courserComment" style="padding:0;border-left:1px solid #eee;border-right:1px solid #eee;padding:0;">
        <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['avg_score'])){; ?>
        <div class="rate col-sm-3 col-xs-5">
            <strong><?php echo SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['avg_score']; ?></strong>
            <br><span class="fs16"><?php echo tpl_modifier_tr('综合评分','course.info'); ?></span>
        </div>
        <?php }; ?>
        <div class="percent fs14 col-sm-15 col-xs-15">
            <dl>
                <dt><?php echo tpl_modifier_tr('学生满意度','course.info'); ?>：</dt>
                <dd>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['student_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span class="sel"></span>
                    <?php }; ?>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=5-SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['student_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span></span>
                    <?php }; ?>
                </dd>
            </dl>
            <dl>
                <dt><?php echo tpl_modifier_tr('课程与描述相符','course.info'); ?>：</dt>
                <dd>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['desc_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span class="sel"></span>
                    <?php }; ?>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=5-SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['desc_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span></span>
                    <?php }; ?>
                </dd>
            </dl>
            <dl>
                <dt><?php echo tpl_modifier_tr('老师的讲解表达','course.info'); ?>：</dt>
                <dd>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['explain_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span class="sel"></span>
                    <?php }; ?>
                    <?php for(SlightPHP\Tpl::$_tpl_vars["i"]=1; SlightPHP\Tpl::$_tpl_vars["i"]<=5-SlightPHP\Tpl::$_tpl_vars["scoreInfo"]['explain_score']; SlightPHP\Tpl::$_tpl_vars["i"]++){; ?>
                    <span></span>
                    <?php }; ?>
                </dd>
            </dl>
        </div>
    </div>
    <?php }else{; ?>
        <div class="col-sm-20 fs16 pdb15 pdt15" style="padding:15px 0;text-indent:15px;border-left:1px solid #eee;border-right:1px solid #eee;"><i class="c-vertical-line mr5"></i>学员评论</div>
        <div class="i-comment"  id="courserComment" style="padding:0;border-left:1px solid #eee;border-right:1px solid #eee;">
            <div class="rate col-sm-4 col-xs-5" style="height:90px;">
                <span class="fs20"><?php echo tpl_modifier_tr('暂无评分','course.info'); ?></span>
            </div>
            <div class="percent fs14 col-sm-15 col-xs-15">
                <dl>
                    <dt><?php echo tpl_modifier_tr('学生满意度','course.info'); ?>：</dt>
                    <dd class="fs14 fcg9">
                        <?php echo tpl_modifier_tr('暂无评分','course.info'); ?>
                    </dd>
                </dl>
                <dl>
                    <dt><?php echo tpl_modifier_tr('课程与描述相符','course.info'); ?>：</dt>
                    <dd class="fs14 fcg9">
                       <?php echo tpl_modifier_tr('暂无评分','course.info'); ?>
                    </dd>
                </dl>
                <dl>
                    <dt><?php echo tpl_modifier_tr('老师的讲解表达','course.info'); ?>：</dt>
                    <dd class="fs14 fcg9">
                        <?php echo tpl_modifier_tr('暂无评分','course.info'); ?>
                    </dd>
                </dl>
            </div>
        </div>
    <?php }; ?>
</section>
<div class="col-sm-20 course-article-content style-borbottom1px pdt20 col-xs-20">
    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["commentList"]['data'])){; ?>
    <ol class="comments-list-hd  bor1px clearfix fs14" id="wrap-comments-list" style="border:1px solid #ededed;">
        <li class="col-sm-11 col-xs-7"><?php echo tpl_modifier_tr('评价心得','course.info'); ?></li>
        <li class="col-sm-5 col-xs-7"><?php echo tpl_modifier_tr('评分','course.info'); ?></li>
        <li class="col-sm-4 col-xs-6"><?php echo tpl_modifier_tr('评论者','course.info'); ?></li>
    </ol>
    <ul class="comments-list new_comments_list detail-comments-list" id="comments-list">
        <?php foreach(SlightPHP\Tpl::$_tpl_vars["commentList"]['data'] as SlightPHP\Tpl::$_tpl_vars["v"]){; ?>
        <li>
            <div class="col-sm-11 col-xs-8 pdr0 pdl0">
                <p class="fs14 cDarkgray"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['comment']; ?></p>
                <p class="fs12 mt15">
                    <span class="hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['className']; ?></span>
                    <span class="hidden-xs"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['sectionName']; ?></span>
                    <span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['ymd']; ?></span>
                    <span><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['his']; ?></span>
                    <span class="comments-del hidden" onclick="commentDel(this)" data-uid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userId']; ?>" data-pid="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['planId']; ?>" title="删除评论"></span>
                </p>
            </div>
            <div class="col-sm-5 col-xs-4 i-comment-tp mt10 fs18 c-fl fcorange tec pd0"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['studentScore']; ?>分</div>
            <div class="col-sm-4 col-xs-7 user-infos c-fl pd0">
                <div class="col-sm-7 col-xs-20 pic hidden-xs">
                    <img src="<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userThumb']; ?>" alt="">
                </div>
                <div class="col-sm-13 col-xs-20">
                    <span class="cours-user-name fs12"><?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userName']; ?></span>
                    <a href="https://<?php echo SlightPHP\Tpl::$_tpl_vars["platformUrl"]; ?>/index.rank.rule" target="_blank">
                        <span class="cours-students-lvs hidden-xs level-icon<?php echo SlightPHP\Tpl::$_tpl_vars["v"]['userLevel']; ?>"></span>
                    </a>
                </div>
            </div>
        </li>
        <?php }; ?>
    </ul>
    <?php }else{; ?>
        <ol class="comments-list-hd  bor1px clearfix fs14" id="wrap-comments-list" style="border:1px solid #ededed;">
            <li class="col-sm-11 col-xs-7"><?php echo tpl_modifier_tr('评价心得','course.info'); ?></li>
            <li class="col-sm-5 col-xs-7"><?php echo tpl_modifier_tr('评分','course.info'); ?></li>
            <li class="col-sm-4 col-xs-6"><?php echo tpl_modifier_tr('评论者','course.info'); ?></li>
        </ol>
        <ul class="comments-list new_comments_list detail-comments-list" id="comments-list">
            <li>
                <div class="col-sm-20 pd0 tec fs14 mt40 mb40">
                    <img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>" alt="">
                    <p class="fcg9"><?php echo tpl_modifier_tr('暂无评分','course.info'); ?></p>
                </div>
            </li>
        </ul>
    <?php }; ?>
    <!-- page -->
    <div class="page-list pd0" id="course-comment-list">
    </div>
    <?php if(empty(SlightPHP\Tpl::$_tpl_vars["commentList"]['totalPage'])){; ?><?php SlightPHP\Tpl::$_tpl_vars["commentList"]['totalPage']=0; ?><?php }; ?>
    <script type="text/javascript" src="<?php echo utility_cdn::js('/assets_v2/js/page.js'); ?>"></script>
    <script src="<?php echo utility_cdn::js('/assets_v2/js/user.js'); ?>"></script>
    <script>
        pageAjax("course-comment-list","/course.info.comment/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>",10,<?php echo SlightPHP\Tpl::$_tpl_vars["page"]; ?>,<?php echo SlightPHP\Tpl::$_tpl_vars["commentList"]['totalPage']; ?>,"commentList");
    </script>
    <!-- /page -->
</div>
<!-- /学员评论 -->
<script>
    var totalPage=<?php echo SlightPHP\Tpl::$_tpl_vars["commentList"]['totalPage']; ?>;
    function commentList(_path,_page,_num){
        $.post(""+_path+"",{ page:_page,num:_num,act:'ajax' },function(r){
            var _html = '';
            if(r.code==1){
                for (var i = 0; i < r.data.length; i++) {
                    _html+= '<li>';
                    _html+= '<div class="col-sm-11 col-xs-8 pdr0 pdl0">';                     _html+= '<p class="fs14 cDarkgray">'+r.data[i]["comment"]+'</p>';
                    _html+= '<p class="fs12 mt15">';
                    _html+= '<span class="hidden-xs">'+r.data[i]["className"]+'</span>';
                    _html+= '<span class="hidden-xs">'+r.data[i]["sectionName"]+'</span>';
                    _html+= '<span>'+r.data[i]["ymd"]+'</span>';
                    _html+= '<span>'+r.data[i]["his"]+'</span>';
                    if(userApi.getUid()==r.data[i]["userId"]){
                        _html+= '<span class="comments-del" onclick="commentDel(this)" data-uid="'+r.data[i]["userId"]+'" data-pid="'+r.data[i]["planId"]+'" title="删除评论"></span>';
                    }else{
                        _html+= '<span class="comments-del hidden" onclick="commentDel(this)" data-uid="'+r.data[i]["userId"]+'" data-pid="'+r.data[i]["planId"]+'" title="删除评论"></span>';
                    }
                    _html+= '</p>';
                    _html+= '</div>';
                    _html+= '<div class="col-sm-5 col-xs-4 i-comment-tp mt10 fs18 c-fl fcorange tec pd0">'+r.data[i]["studentScore"]+'分</div>';
                    _html+= '<div class="col-sm-4 col-xs-7 user-infos c-fl pd0">';
                    _html+= '<div class="col-sm-7 col-xs-20 pic hidden-xs">';
                    _html+= '<img src="'+r.data[i]["userThumb"]+'" alt="">';
                    _html+= '</div>';
                    _html+= '<div class="col-sm-13 col-xs-20">';
                    _html+= '<span class="cours-user-name fs12">'+r.data[i]["userName"]+'</span>';
                    _html+= '<a href="https://'+ r.platformUrl+'/index.rank.rule" target="_blank">';
                    _html+= '<span class="cours-students-lvs hidden-xs level-icon'+r.data[i]["userLevel"]+'"></span>';
                    _html+= '</a>';
                    _html+= '</div>';
                    _html+= '</div>';
                    _html+= '</li>';
                };
                totalPage= r.totalPage;
            }else{
                _html+= '<li>';
                _html+= '<div class="col-sm-20 pd0 tec fs14 mt40 mb40">';
                _html+= '<img src="<?php echo utility_cdn::img('/assets_v2/img/pet3.png'); ?>" alt="">';
                _html+= '<p class="fcg9"><?php echo tpl_modifier_tr('暂无评分','course.info'); ?></p>';
                _html+= '</div>';
                _html+= '</li>';
            }
            $("#comments-list").html(_html);
        },'json');
        $("#course-comment-list").html('');
        pageAjax("course-comment-list","/course.info.comment/<?php echo SlightPHP\Tpl::$_tpl_vars["courseId"]; ?>",10,_page,totalPage,"commentList");
    }
</script>