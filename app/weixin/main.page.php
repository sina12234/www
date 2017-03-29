<?php
require_once(ROOT_LIBS."/weixin/mp/wechat.class.php");
class weixin_main extends STpl{

	public function pageTest($inPath){
		$options = (weixin_api::getJsOptions());
		$this->assign("signPackage",$options);
		$this->render("weixin/test.html");
	}
	public function pageMenu($inPath){
		$weObj = new Wechat(weixin_api::getOptions());
		$newmenu =  SJson::decode(file_get_contents(ROOT_APP."/weixin/menu.txt"),true);
		print_R($newmenu);
		exit;
		if(!empty($newmenu)){
			$result = $weObj->createMenu($newmenu,$r);
			var_dump($result);
			var_dump($r);
		}
	}
	public function pageMain($inPath){
		$weObj = new Wechat(weixin_api::getOptions());
		$weObj->valid();
		$data=$weObj->getRev()->getRevData();
		$from_uid=$weObj->getRev()->getRevFrom();
		$from_uinfo=$weObj->getUserInfo($from_uid);
		$fk_user=0;
		if(!empty($from_uinfo['unionid'])){
			//获取用户信息，如果没有
			$parter_info = user_api::getParterner(user_const::SOURCE_WEIXIN,$from_uinfo['unionid']);
			if(empty($parter_info)){
				//创建parter信息
			}
			if(empty($parter_info->uid)){
				//没有绑定手机号，提示需要绑定后才能发图片
			}
			$params=new stdclass;
			$params->name=$from_uinfo['nickname'];
			$params->register_ip=utility_ip::realIp();
			$params->outer_user=new stdclass;
			$params->outer_user->source=user_const::SOURCE_WEIXIN;
			$params->outer_user->uid=$from_uinfo['unionid'];
			$params->outer_user->thumb_url=$from_uinfo['headimgurl'];
			$ret = utility_services::call("/user/info/create/",$params);
			if(!empty($ret->data->uid)){
				$fk_user=$ret->data->uid;
				$params=new stdclass;
				$params->to=$data['ToUserName'];
				$params->from=array("uid"=>$ret->data->uid, "union_id"=>$from_uinfo['unionid'],"open_id"=>$from_uinfo['openid']);
				$params->createtime=$data['CreateTime'];
				$params->type=$data['MsgType'];
				$params->msg_id=$data['MsgId'];
				$params->message=SJson::encode($data);
				$params->media_file=array();
				//media
				foreach($data as $k=>$v){
					if(stripos($k,"mediaid")!==false){
						//保存文件
						$file_data =  $weObj->getMedia($v);
						if($file_data!==false){
							$filename=utility_file::tempname("www_weixin_tmp");
							if(file_put_contents($filename,$file_data)!==false){
								//上传文件
								$file = utility_file::instance();
								$r1 = $file->upload($filename);
								if(!empty($r1->fid)){
									$fid = $r1->fid;
									$params->media_file[]=array("media_id"=>$v,"file_id"=>$r1->fid);
								}
							};
						}
					}
				}
				$ret = utility_services::call("/weixin/message/receive",$params);
			}
		}
		$type = $weObj->getRev()->getRevType();
		switch($type) {
		case Wechat::MSGTYPE_TEXT:
			if($weObj->getRevContent()=="测试"){
				$data=array(
					0=>array(
						"Title"=>"手机绑定",
						"Description"=>"您需要手机绑定我们才可以更好的为您服务",
						"PicUrl"=>"",
						"Url"=>"http://".$_SERVER['HTTP_HOST']."/weixin.user.login?url=/my",
					));
					$weObj->news($data)->reply();
			}
			//$weObj->text("hello")->reply();
			break;
		case Wechat::MSGTYPE_EVENT:
			break;
		case Wechat::MSGTYPE_IMAGE:
			break;
		default:
			//$weObj->text("help info")->reply();
		}

	}
}

