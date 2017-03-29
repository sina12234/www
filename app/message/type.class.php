<?php

/**
 * @link https://wiki.gn100.com/doku.php?id=web:message
 */
class message_type
{
    /**
     * source web
     */
    const SOURCE_WEB = 20000;

    /**
     * source android
     */
    const SOURCE_ANDROID = 20001;

    /**
     * source ios
     */
    const SOURCE_IOS = 20002;

    /**
     * source pc
     */
    const SOURCE_PC = 20003;

    /**
     * system message
     */
    const SYSTEM = 10000;

    /**
     * system class remind
     */
    const SYSTEM_CLASS_REMIND = 10001;

    /**
     * system Interactive
     */
    const SYSTEM_INTERACTIVE = 10002;

    /**
     * Contact information
     */
    const SYSTEM_CONTACT_INFORMATION = 10003;

    /**
     * class end statistics
     */
    const SYSTEM_CLASS_END_STATISTICS = 10004;

    /**
     * preferential volume remind
     */
    const SYSTEM_PREFERENTIAL_VOLUME_REMIND = 10005;

    /**
     * class change feedback
     */
    const SYSTEM_CLASS_CHANGE_FEEDBACK = 10006;

    /**
     * course change
     */
    const SYSTEM_COURSE_CHANGE = 10007;

    /**
     * course arrangement
     */
    const SYSTEM_COURSE_ARRANGEMENT = 10008;

    /**
     * vote into
     */
    const SYSTEM_VOTE_INFO = 10009;

    /**
     * system course info
     */
    const SYSTEM_COURSE_INFO = 10012;

    /**
     * org info verify
     */
    const ORG_DATA_INFO_VERIFY = 10010;

    /**
     * org join verify
     */
    const ORG_JOIN_VERIFY = 10011;

    /**
     * interactive point of praise
     */
    const CLICK_PRAISE = 10013;

    /**
     * interactive comment
     */
    const INTERACTIVE_COMMENT = 10014;

    /**
     * Withdrawals VERIFY
     */
    const WITHDRAWALS_VERIFY = 10015;

    /**
     * bank card verify
     */
    const BANK_CARD_VERIFY = 10016;

    /**
     * open vip
     */
    const OPEN_VIP = 10017;

    /**
     * resell notice
     */
    const RESELL_NOTICE = 10018;
    const EXPIRE_NOTICE = 10019; //会员到期提醒
    /**
     * plan stat
     */
    const PLAN_STAT = 10023;
	const SIGN_COUSER = 10024;//报名成功

    const TEACHER_PUBLISH_TASK_NOTICE = 10020;  //发布作业
    const TEACHER_REPLY_TASK = 10021; //批改作业
    const TEACHER_CALL_TASK = 10022; //一建催交
    const TEACHER_REMIND = 10026; //老师上课提醒
    /**
     * @var array
     */
    static $messageType = [
        self::SYSTEM,
        self::SYSTEM_CLASS_REMIND,
        self::SYSTEM_INTERACTIVE,
        self::SYSTEM_CONTACT_INFORMATION
    ];

    /**
     * @var array
     */
    static $source = [
        self::SOURCE_WEB,
        self::SOURCE_ANDROID,
        self::SOURCE_IOS,
        self::SOURCE_PC
    ];
    
    /**
     * @var array
     */
    static $msgTypeMap = [

        self::TEACHER_REMIND                            => [
            'title' => '上课提醒',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::TEACHER_PUBLISH_TASK_NOTICE   => [
            'title' => '发布作业',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::TEACHER_REPLY_TASK  => [
            'title' => '批改作业',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::TEACHER_CALL_TASK   => [
            'title' => '消息推送',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::SYSTEM                            => [
            'title' => '系统消息',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::SYSTEM_CLASS_REMIND               => [
            'title' => '上课提醒',
            'thumb' => '/assets_v2/img/msg-icon2.png'
        ],
        self::SYSTEM_CLASS_END_STATISTICS       => [
            'title' => '下课统计',
            'thumb' => '/assets_v2/img/msg-icon4.png'
        ],
        self::SYSTEM_PREFERENTIAL_VOLUME_REMIND => [
            'title' => '优惠卷提醒',
            'thumb' => '/assets_v2/img/msg-icon2.png'
        ],
        self::SYSTEM_CLASS_CHANGE_FEEDBACK      => [
            'title' => '调班反馈通知',
            'thumb' => '/assets_v2/img/msg-icon2.png'
        ],
        self::SYSTEM_COURSE_CHANGE              => [
            'title' => '课程变动',
            'thumb' => '/assets_v2/img/msg-icon5.png'
        ],
        self::SYSTEM_COURSE_ARRANGEMENT         => [
            'title' => '排课通知',
            'thumb' => '/assets_v2/img/msg-icon5.png'
        ],
        self::SYSTEM_INTERACTIVE                => [
            'title' => '互动消息',
            'thumb' => '/assets_v2/img/msg-icon3.png'
        ],
        self::SYSTEM_VOTE_INFO                  => [
            'title' => '投票信息',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::ORG_DATA_INFO_VERIFY              => [
            'title' => '机构资料修改审核信息',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::ORG_JOIN_VERIFY                   => [
            'title' => '机构入驻审核',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::SYSTEM_COURSE_INFO                => [
            'title' => '课堂信息',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::WITHDRAWALS_VERIFY                => [
            'title' => '提现审核',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::BANK_CARD_VERIFY                  => [
            'title' => '银行卡审核',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::OPEN_VIP                          => [
            'title' => '开通会员',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::RESELL_NOTICE                     => [
            'title' => '分销通知',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
		self::EXPIRE_NOTICE                     => [
            'title' => '会员到期提醒',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
        self::PLAN_STAT                            => [
            'title' => '课堂统计',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
		 self::SIGN_COUSER                            => [
            'title' => '报名成功',
            'thumb' => '/assets_v2/img/msg-icon1.png'
        ],
    ];
	public static function getTypes(){
		return self::$msgTypeMap;
	}
}