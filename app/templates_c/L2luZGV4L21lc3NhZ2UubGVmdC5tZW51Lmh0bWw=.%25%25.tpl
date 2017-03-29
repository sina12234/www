<div class="left-div col-sm-4 col-xs-20 hidden-xs">
    <div class="linkman-so mb10">
        <input placeholder="<?php echo tpl_modifier_tr('查找联系人','message'); ?>" id="user_sech_ipt" class="col-sm-16">
        <button class="col-sm-4" id="form_sub"></button>
        <i class="del-btn" id="del_lst_btn"></i>
    </div>
    <dl class="linkman">
        <div>
            <span class="linkman-tab fs14"><?php echo tpl_modifier_tr('联系人','message'); ?></span>
            <div class="linkman-set linkman-set-icon" id="my-mesages-set">
                <div class="mesages-set my-mesages-set bor1px">
                    <i class="rt-jiao"></i>
                    <h2><?php echo tpl_modifier_tr('消息箱设置','message'); ?></h2>
                    <ol class="set-category">
                        <li class="clearfix">
                            <div class="mr5 c-fl fs12"><?php echo tpl_modifier_tr('课堂消息','message'); ?></div>
                            <a href="javascript:;" class="class_set_btn c-fr fs12" onclick="setMsg('10012','<?php echo tpl_modifier_tr('课堂消息','message'); ?>')"><?php echo tpl_modifier_tr('设置','message'); ?></a>
                        </li>
                        <li class="clearfix">
                            <div class="mr5 c-fl fs12"><?php echo tpl_modifier_tr('系统消息','message'); ?></div>
                            <a href="javascript:;" class="system_set_btn c-fr fs12" onclick="setMsg('10000','<?php echo tpl_modifier_tr('系统消息','message'); ?>')"><?php echo tpl_modifier_tr('设置','message'); ?></a>
                        </li>
                        <!--
                            <li class="clearfix">
                                <div class="mr20 c-fl fs12">互动消息</div>
                                <a href="javascript:;" class="comment_set_btn c-fr fs12" onclick="setMsg('10002','互动消息')">设置</a>
                            </li>
                        -->
                        <li style="text-align:center;">
                            <button class="request-btn request-close" id="tip-close-btn"><?php echo tpl_modifier_tr('关闭','message'); ?></button>
                        </li>
                    </ol>
                </div>
                <!-- 设置内容 -->
                <div class="wrap-lt-set mesages-set my-msg-category-set bor1px">
                    <i class="rt-jiao"></i>
                    <h1 class="msg-set-title"></h1>
                    <div id="wrap_msg_remind" class="wrap-remind-tip fs12 up-class clearfix">测试</div>
                    <div style="text-align:center;width:100%;" class="mt10 mb10">
                        <button class="request-btn" onclick="RequestBtn()"><?php echo tpl_modifier_tr('确定','message'); ?></button>
                        <button class="cancle-btn"><?php echo tpl_modifier_tr('取消','message'); ?></button>
                    </div>
                </div>
                <!-- /设置内容 -->
            </div>
        </div>
        <ul id="linkMan"></ul>
    </dl>
</div>

<script id="groupListTpl" type='text/template'>
    <<#groupList>>
        <li>
            <p groupId="<<groupId>>"><i class="arrow-down"></i><<name>>（<<num>>）</p>
            <dl id="groupContacts<<groupId>>"></dl>
        </li>
    <</groupList>>
</script>

<script id="contactsListTpl" type='text/template'>
    <<#contactsList>>
        <dd>
            <span class="face">
                <img src="<<userThumb>>">
            </span>
            <span id="username" userId="<<userId>>" groupId="<<groupId>>"  class="c-fl"><<userName>></span>
            <div class="<<^display>>delt_black_name<</display>> c-fr mr20" groupId="<<groupId>>" onclick="DeltBlackName(<<userId>>, -4, this)"></div>
        </dd>
        <</contactsList>>
</script>

<script id="isRemindTpl" type='text/template'>
    <<#isRemind>>
        <p><<title>></p>
        <p class="clearfix">
            <label class="c-fl">
                <input type="radio" name="checked_remind<<msgType>>" <<^remind>>checked<</remind>> msgType=<<msgType>> value="1" /> <<remindStr>><<isChecked>>
            </label>
            <label class="c-fr">
                <input type="radio" name="checked_remind<<msgType>>" <<^noRemind>>checked<</noRemind>> msgType=<<msgType>> value="0" /> <<ignoreStr>>
            </label>
        </p>
        <</isRemind>>
</script>