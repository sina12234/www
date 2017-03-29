<?php
/**
 *
 */
class user_const{
	//const SOURCE_QQ = 1; 	//迁移到了 config/parterner.conf
	const SOURCE_WEIXIN = 2;

	//const SOURCE_WEIBO = 3;//废弃
	//const SOURCE_ALIPAY = 4;//废弃
	#普通机构以100开始
	//智慧校
	const SOURCE_ZHIHUIXIAO = 100;
	//云校园
	const SOURCE_YUNXIAOYUAN= 101;
	//长征教育
	const SOURCE_CHANGZHENG = 102;
	//高招帮
	const SOURCE_GAOZHAOBANG = 103;
	//成长通
	const SOURCE_CZTONE = 104;
	//湖南和校园
	const SOURCE_HUNANHEXIAOYUAN = 105;

    //新的app接入，从10000开始，现在配置在config/parterner.conf文件里，未来存在数据库里

	//礼物类型 
    const FLOWER = 1;
}
