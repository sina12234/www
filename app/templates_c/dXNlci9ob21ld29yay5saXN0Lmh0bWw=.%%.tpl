<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>高能100 - 我的作业</title>
<?php echo tpl_function_part("/index.main.headeruser"); ?>
<script src="<?php echo utility_cdn::js('/assets/js/part.js'); ?>"></script>
    </head>
<style> 

        .user_content_box{ position: relative;height: 500px; }
        .stepbar_1{ width: 713px;margin-left: 1px; }
        .user_content_box .tbl-t{ padding:0 16px 0 0; }
        .user_content_box .tbl-t th{ text-align: center; color:#999999; }
        .user_content_box .tbl-t td{ border: 1px solid #f7f7f7;height: 75px;border-top: none;border-left: none;line-height: 25px;width: 150px;text-align: center; }
        .user_content_box .tbl-t .weid{ width: 330px; color:#3465CE;cursor: pointer; }
        .user_content_box .tbl-t .weid1{ cursor: pointer; }
        .user_content_box .tbl-t .header{ background: #f7f7f7;height: 40px; }
        .user_content_box .xk{ margin-top: 20px;margin-bottom: 40px; }
        .user_content_box .xk form .btn{ margin-top: -3px;}
</style>
<body>
  <!--top-->
      <?php echo tpl_function_part("/index.main.nav.user"); ?>
<div class='count'>
  <div class='container-fluid'>
    <div class='row'>
      <div class='col-md-3 user_menu hidden-xs'>
        <div id=''>
          <div id='menu'>
             <?php echo tpl_function_part("/user.home.menu.user"); ?>

          </div>
        </div>
      </div>
      <div class='col-md-9 user_content'>
        <div class='user_box'>
          <div class='container-fluid'>
            <div class='row'>
              <div class='user_content_box'>
             <div class='xk'>
              <form action = "<?php echo SlightPHP\Tpl::$_tpl_vars["path"]; ?>" method="post">
                <select style=" width: 222px;height: 34px;padding-left:10px;" name="class">
                    <option value="0">请选择班级</option>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["class"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["class"] as SlightPHP\Tpl::$_tpl_vars["c"]){; ?>
                    <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["c"]->class_id; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['class']==SlightPHP\Tpl::$_tpl_vars["c"]->class_id){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["c"]->class_name; ?></option>
                    <?php }; ?>
                    <?php }; ?>
                </select>
                <select style=" width: 222px;height: 34px;padding-left:10px;" name="section">
                    <option value="0">请选择章节</option>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["section"])){; ?>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["section"] as SlightPHP\Tpl::$_tpl_vars["s"]){; ?>
                    <option value="<?php echo SlightPHP\Tpl::$_tpl_vars["s"]->section_id; ?>" <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['section']==SlightPHP\Tpl::$_tpl_vars["s"]->section_id){; ?>selected<?php }; ?>><?php echo SlightPHP\Tpl::$_tpl_vars["s"]->section_name; ?></option>
                    <?php }; ?>
                    <?php }; ?>
                </select>
                <input class="btn btn-default" type="submit" value="提交">
              </form>
             </div>
                <div class="stepbar_1 mt25">
                    <ul>
                    <li <?php if(!SlightPHP\Tpl::$_tpl_vars["pm"]['status']){; ?>class="on"<?php }; ?>><b></b><a href="user.homework.list" class="fs14">全部</a></li>
                    <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status']=='not'){; ?>class="on"<?php }; ?>><b></b><a href="user.homework.list.not" class="fs14">未完成<?php if(isset(SlightPHP\Tpl::$_tpl_vars["count"][0]['status']) && SlightPHP\Tpl::$_tpl_vars["count"][0]['status']==0){; ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["count"][0]['count']; ?>)<?php }; ?></a></li>
                    <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status']=='ok'){; ?>class="on"<?php }; ?>><b></b><a href="user.homework.list.ok" class="fs14">已交完<?php if(isset(SlightPHP\Tpl::$_tpl_vars["count"][1]['status']) && SlightPHP\Tpl::$_tpl_vars["count"][1]['status']==1){; ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["count"][1]['count']; ?>)<?php }; ?></a></li>
                    <li <?php if(SlightPHP\Tpl::$_tpl_vars["pm"]['status']=='yes'){; ?>class="on"<?php }; ?>><b></b><a href="user.homework.list.yes" class="fs14">已批改<?php if(isset(SlightPHP\Tpl::$_tpl_vars["count"][2]['status']) && SlightPHP\Tpl::$_tpl_vars["count"][2]['status']==2){; ?>(<?php echo SlightPHP\Tpl::$_tpl_vars["count"][2]['count']; ?>)<?php }; ?></a></li>
                    </ul>
                </div> 
               
               <div class='clear'></div>
               <div class='tbl-t'>
                   <table>
                    <?php if(!empty(SlightPHP\Tpl::$_tpl_vars["list"]->data)){; ?>
                     <tr class='header'>
                      <th>课程名称</th>
                      <th>作业名称</th>
                      <th>完成情况</th>
                      <th>微信交作业</th>
                      <th>作业评星</th>
                    </tr>
                    <?php foreach(SlightPHP\Tpl::$_tpl_vars["list"]->data as SlightPHP\Tpl::$_tpl_vars["task"]){; ?>
                    <tr>
                      <td><?php echo SlightPHP\Tpl::$_tpl_vars["task"]->course_title; ?></td>
                      <td><a class="weid" href="user.homework.info.<?php echo SlightPHP\Tpl::$_tpl_vars["task"]->pk_task_reply; ?>"><?php echo SlightPHP\Tpl::$_tpl_vars["task"]->task_title; ?></a></td>
                      <td><?php if(SlightPHP\Tpl::$_tpl_vars["task"]->status==0){; ?>未完成<?php }elseif( SlightPHP\Tpl::$_tpl_vars["task"]->status==1){; ?>未批改<?php }elseif( SlightPHP\Tpl::$_tpl_vars["task"]->status==2){; ?>已批改<?php }; ?></td>
                      <td class='weid1'><span>点击查看二维码</span><img style="display:none;" height="100%" src="<?php echo utility_cdn::img('/assets/images/erwi.jpg'); ?>" alt=""></td>
                      <!--<td><img src="<?php echo utility_cdn::img('/assets/images/tmp_3.jpg'); ?>" alt=""></td>-->
                      <td>
                        <div class="starbox fl">
                          <ul class="star_ul fl disblk">
                            <li><a score="1" class="one-star <?php if(SlightPHP\Tpl::$_tpl_vars["task"]->score==1){; ?>active-star<?php }; ?>" title="很差"></a></li>
                            <li><a score="2" class="two-star <?php if(SlightPHP\Tpl::$_tpl_vars["task"]->score==2){; ?>active-star<?php }; ?>" title="差"></a></li>
                            <li><a score="3" class="three-star <?php if(SlightPHP\Tpl::$_tpl_vars["task"]->score==3){; ?>active-star<?php }; ?>" title="还行"></a></li>
                            <li><a score="4" class="four-star <?php if(SlightPHP\Tpl::$_tpl_vars["task"]->score==4){; ?>active-star<?php }; ?>" title="满意"></a></li>
                            <li><a score="5" class="five-star <?php if(SlightPHP\Tpl::$_tpl_vars["task"]->score==5){; ?>active-star<?php }; ?>" title="很满意"></a></li>
                          </ul>
                          <span class="fl s_result ml10 fs14 fcorange_1" style="color: rgb(255, 113, 27);"><?php echo SlightPHP\Tpl::$_tpl_vars["task"]->score; ?></span>
                        </div>
                      </td>
                    </tr>
                    <?php }; ?>
                    <?php }else{; ?>
                    <tr>
                      <td>暂无作业信息</td>
                    </tr>
                    <?php }; ?>
                    <!--<tr>
                      <td>精品奥数班</td>
                      <td class='weid'>精品奥数第三届随堂每日更新....</td>
                      <td>已批改</td>
                      <td class='weid1'>点击查看二维码</td>
                      <td><img src="<?php echo utility_cdn::img('/assets/images/tmp_3.jpg'); ?>" alt=""></td>
                    </tr>
                    <tr>
                      <td>初三数学</td>
                      <td class='weid'>精品奥数第三届随堂每日更新....</td>
                      <td>未批改</td>
                      <td class='weid1'>点击查看二维码</td>
                      <td><img src="<?php echo utility_cdn::img('/assets/images/tmp_3.jpg'); ?>" alt=""></td>
                    </tr>
                    <tr>
                      <td>初三数学</td>
                      <td class='weid'>精品奥数第三届随堂每日更新....</td>
                      <td>未批改</td>
                      <td class='weid1'><img src="<?php echo utility_cdn::img('/assets/images/erwi.jpg'); ?>" alt=""></td>
                      <td><img src="<?php echo utility_cdn::img('/assets/images/tmp_3.jpg'); ?>" alt=""></td>
                    </tr>-->
                 </table>
               </div>
            </div>
          </div>
        </div>
      </div>
    
          <div class="clear"></div>
    </div>
  </div>

</div>
<?php echo tpl_function_part("/index.main.footer"); ?>
</body>
<script type="text/javascript">
$(function  () {
    $('.weid1').click(function(){
        $(this).find('span,img').toggle();
    })

})
</script>
</html>
