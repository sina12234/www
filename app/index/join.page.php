<?php
class index_join extends STpl{
	private $domain;
	private $level = array("level"=>1);
    function __construct(){
		$domain_conf = SConfig::getConfig(ROOT_CONFIG."/const.conf","domain");
		$this->domain = $domain_conf->domain;
		$this->assign('domain', $this->domain);
        //如果没有登陆到登陆界面
        $this->user = user_api::loginedUser();
        if(empty($this->user)){
            $this->redirect("/index.main.login?url=/index.join.step1");
        }   
    }  
    public function pageCheckJoin($inPath){
        $orgInfo=user_organization::getOrgByUid($this->user['uid']); 
        if(!empty($orgInfo)&&$orgInfo->status==1){
            return true;
        }
        return true;
    }
	public function pageStep1($inPath){
        $orgInfo=user_organization::getOrgByUid($this->user['uid']);
        if(!empty($orgInfo)&&$orgInfo->status!=-1){
            $this->redirect("/index/join/step4");
        }   
		$this->render('index/join.step1.html');
	}
    public function pageAgreeProtocolAjax($inPath){
       utility_session::get()['orgJoin']['checked']=true; 
       return utility_session::get()['orgJoin']['checked'];
    }
	public function pageStep2($inPath){
        $orgInfo=user_organization::getOrgByUid($this->user['uid']);
		
        /*if(!empty($orgInfo)&&$orgInfo->status!=-1){
            $this->redirect("/index/join/step4");
        }*/  
        //获取资质
        $orgVerify=user_organization::getOrgVerify($this->user['uid']);
		//获取机构培训范围
		$cate = index_api::getCourseFirstCateInfo($this->level);
		$scopesArr = array();
		if(!empty($orgVerify->scopes)){
			$scopesArr = explode(",",$orgVerify->scopes);
		}
		$scopesData = array();
		$contain = array();
		if(!empty($cate->items)){
			foreach($cate->items as $k=>$v){
				$scopesData[$v->pk_cate]['pk_cate'] = isset($v->pk_cate) ? $v->pk_cate : 0;
				$scopesData[$v->pk_cate]['name_display'] = isset($v->name_display) ? $v->name_display : '';
			}
		}
		if(!empty($scopesArr)){
			foreach($scopesArr as $n){
				$contain[$n] = !empty($scopesData[$n]) ? $scopesData[$n] : '';
			}
			$orgVerify->scopes = $contain;
		}
		
		if(!empty($contain)){
			foreach($contain as $k=>$v){
				$nameDisplay[] = !empty($v['name_display']) ? $v['name_display'] : '';
				$pkCate[] = !empty($v['pk_cate']) ? $v['pk_cate'] : '';
			}
			$nameStr = implode(",",$nameDisplay);
			$pkCateStr = implode(",",$pkCate);
			$orgVerify->nameStr = $nameStr;
			$orgVerify->pkCateStr = $pkCateStr;
		}
        if(empty(utility_session::get()['orgJoin']['base'])&&!empty($orgVerify)){
            $data=array();
            $data['thumb_big']=$orgVerify->thumb_big;
            $data['thumb_med']=$orgVerify->thumb_med;
            $data['thumb_small']=$orgVerify->thumb_small;
            $data['desc']=$orgVerify->desc;
            $arr=explode('.',$orgVerify->subdomain);
            $subdomain=current($arr);
            $data['subdomain']=$subdomain;
            $data['subname']=$orgVerify->subname;
            $data['name']=$orgVerify->name;
            //$data['company']=$orgVerify->company;
            $data['scopes']=$contain;
            $data['province']=$orgVerify->province;
            $data['city']=$orgVerify->city;
            $data['county']=$orgVerify->county;
            $data['address']=$orgVerify->address;
            $data['hotline']=$orgVerify->hotline;
            $data['nameStr']=!empty($orgVerify->nameStr)?$orgVerify->nameStr :'';
            $data['pkCateStr']= !empty($orgVerify->pkCateStr)?$orgVerify->pkCateStr:'';
            utility_session::get()['orgJoin']['base']=$data;
			
        }
        if(empty(utility_session::get()['orgJoin']['checked'])){
            $this->redirect("/index/join/step1");
        } 
		
        if(!empty(utility_session::get()['orgJoin']['base'])){
            $this->assign("base",utility_session::get()['orgJoin']['base']);
        }  
		
        $province_list=user_organization::getRegionList(array('level'=>0));
        $this->assign("province_list",$province_list);
		$this->render('index/join.step2.html');
	}
    public function pageStep2Ajax($inPath){
        $result=new stdclass;
        if(empty(utility_session::get()['orgJoin']['checked'])){
            $result->error="页面已过期，请刷新后重试！";
            return $result;
        }   
        $data=array();
		//处理上传LOGO图片
		$path = ROOT_WWW.$_POST['thumb_big'];
        $filename = $path;
		/*if(empty($_REQUEST['w'])){
			$result->error="请先上传logo";
            $result->field="thumb_big";
            return $result;
		}*/
		if(is_file($filename)){
            list($width, $height, $type, $attr) = getimagesize($filename);
            $filename_dst = $path;
            $targ_w = $_REQUEST['w'];
            $targ_h = $_REQUEST['h'];
            //$src = 'demo_files/flowers.jpg';
            switch($type){
            case 1: $img_r = imagecreatefromgif($filename);break;
            case 2: $img_r = imagecreatefromjpeg($filename);break;
            default:
                $img_r = imagecreatefrompng($filename);
            }
            //imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
           // $r = imagejpeg($dst_r, $filename_dst);
            //if($r){
                $thumbnail = new SThumbnail($filename_dst, 100);
                $thumbnail->setMaxSize(180, 48);
                $filename_180 = utility_file::tempname("thumb");
                if($thumbnail->genFile($filename_180)===false){
                    $result->error = "不是有效果的图片";
                    return $result;
                };
                $file = utility_file::instance();
                $r1 = $file->upload($filename_180,user_api::getLoginUid(),"image",""); 
                if(empty($r1)){
                    $result->error = "图片处理失败";
                    return $result;
                }
                //图片尺寸都是一样的 
                $_POST['thumb_small']=$r1->fid;
                $_POST['thumb_med']=$r1->fid;
                $_POST['thumb_big']=$r1->fid;
                //unlink($filename);
                unlink($filename_dst);
                unlink($filename_180);
            //}
        }
        $data['thumb_big']=isset($_POST['thumb_big']) ? $_POST['thumb_big'] : '';
        $data['thumb_med']=isset($_POST['thumb_med']) ? $_POST['thumb_med'] : '';
        $data['thumb_small']=isset($_POST['thumb_small']) ? $_POST['thumb_small'] : '';
        $subdomain=!empty($_POST['subdomain'])?strtolower(trim($_POST['subdomain'])):'';
		/*if(empty($_POST['w'])&&empty($_POST['thumb_big'])){
            $result->error="请先上传logo";
            $result->field="thumb_big";
            return $result;
        }*/
		$pos = strpos($_POST['thumb_big'],"upload/tmp");              
		if(!empty($_POST['thumb_big'])&&$pos !=false){                          
			$result->error="LOGO异常,重新上传!";                                
			$result->field="LOGO";                                              
			return $result;  																		
		} 
        if(empty($subdomain)){
            $result->error="请填写机构域名";
            $result->field="subdomain";
            return $result;
        }
		
        if(!preg_match("/^\w*$/",$subdomain)){
            $result->error="机构域名只能是字母、数字";
            $result->field="subdomain";
            return $result;
        }
        if(strlen($subdomain)<4){
            $result->error="机构域名不能少于4个字符";
            $result->field="subdomain";
            return $result;
        }
		$isDomain = $subdomain.".".$this->domain;
        $subDomainYes=user_organization::getSubdomainByNameIsExist($isDomain);
        //$verifyYes=user_organization::getOrgVerifyBySubDomain($isDomain);
        if(!empty($subDomainYes)){
            $result->error="机构域名已存在";
            $result->field="subdomain";
            return $result;
        } 
        $data['subdomain']=$subdomain;
        $subname=!empty($_POST['subname'])?trim($_POST['subname']):'';
        $name=!empty($_POST['name'])?trim($_POST['name']):'';
        if(empty($subname)){
            $result->error="请填写机构简称";
            $result->field="subname";
            return $result;
        }
        if(strlen(iconv('utf-8','gb2312',$subname))>12){
            $result->error="机构简称不能超过12个字符";
            $result->field="subname";
            return $result;
        }
        if(!preg_match("/^[a-zA-Z\x7f-\xff]+$/",$subname)){
            $result->error="机构简称只能是汉字或字母";
            $result->field="subname";
            return $result;
        }
		if(strlen(iconv('utf-8','gb2312',$name))>90){
            $result->error="机构全称不能超过30个汉字或90个字符";
            $result->field="name";
            return $result;
        }
        $data['subname']=$subname;
        $data['name']=$name;
        $scopes=!empty($_POST['scopes']) ? trim($_POST['scopes']) : '';
        if(empty($scopes)){
            $result->error="请选择培训范围";
            $result->field="scopes";
            return $result;
        }
        $province=!empty($_POST['province'])?(int)$_POST['province']:0;
        $city=!empty($_POST['city'])?(int)$_POST['city']:0;
        $county=!empty($_POST['county'])?(int)$_POST['county']:0;
        if(empty($province)||empty($city)){
            $result->error="请选择所在地";
            $result->field="province";
            return $result;
        }
        $data['province']=$province;
        $data['city']=$city;
        $data['county']=$county;
        $address=!empty($_POST['address'])?trim($_POST['address']):'';
		$scopesArr = array();
		if(!empty($scopes)){
			$scopesArr = explode(",",$scopes);
		}
		$scopesData = array();
		$contain = array();
		//获取机构培训范围
		$cate = index_api::getCourseFirstCateInfo($this->level);
		if(!empty($cate->items)){
			foreach($cate->items as $k=>$v){
				$scopesData[$v->pk_cate]['pk_cate'] = isset($v->pk_cate) ? $v->pk_cate : 0;
				$scopesData[$v->pk_cate]['name_display'] = isset($v->name_display) ? $v->name_display : '';
			}
		}
		if(!empty($scopesArr)){
			foreach($scopesArr as $n){
				$contain[$n] = !empty($scopesData[$n]) ? $scopesData[$n] : '';
			}
			$data['scopes']= $contain;
		}
		$data['scopes']=$scopes;
		if(!empty($contain)){
			foreach($contain as $k=>$v){
				$nameDisplay[] = !empty($v['name_display']) ? $v['name_display'] : '';
				$pkCate[] = !empty($v['pk_cate']) ? $v['pk_cate'] : '';
			}
			$nameStr = implode(",",$nameDisplay);
			$pkCateStr = implode(",",$pkCate);
			$data['nameStr'] = $nameStr;
			$data['pkCateStr'] = $pkCateStr;
		}
        if(empty($address)){
            $result->error="请填写联系地址";
            $result->field="address";
            return $result;
        }
        $data['address']=$address;
        $hotline=!empty($_POST['hotline'])?trim($_POST['hotline']):0;
        if(empty($hotline)){
            $result->error="请填写服务电话";
            $result->field="hotline";
            return $result;
        }
		if(!preg_match('/\d{7,15}/',$hotline)){
			$result->error = "请填写正确的服务电话格式";
			$result->field = "hotline";
			return $result;
		}
        $data['hotline']=$hotline;
        $desc=!empty($_POST['desc'])?trim($_POST['desc']):'';
        if(empty($desc)){
            $result->error="请填写公司简介";
            $result->field="desc";
            return $result;
        }
        $data['desc']=$desc;
        utility_session::get()['orgJoin']['base']=$data;
        return $result;
    }
	public function pageStep3($inPath){
        if(empty(utility_session::get()['orgJoin']['checked'])){
            $this->redirect("/index/join/step1");
        }   
        if(empty(utility_session::get()['orgJoin']['base'])){
            $this->redirect("/index/join/step2");
        }   
        $orgInfo=user_organization::getOrgByUid($this->user['uid']);
        /*if(!empty($orgInfo)&&$orgInfo->status!=-1){
            $this->redirect("/index/join/step4");
        }*/
        $orgVerify=user_organization::getOrgVerify($this->user['uid']);
        if(!empty($orgVerify)){
            $verify=new stdClass; 
            $verify->idcard_pic=$orgVerify->idcard_pic;
            $verify->qualify_pic=$orgVerify->qualify_pic;
            $verify->email=$orgVerify->email;
            $this->assign("verify",$verify);
        }   
        if(!empty(utility_session::get()['checkMobile']['time'])){
            $time=time()-utility_session::get()['checkMobile']['time'];
            if($time<60){
                $this->assign("time",60-$time);
            }
        }
        //用户信息
        $userInfo=user_api::getUser($this->user['uid']);
        $this->assign("userInfo",$userInfo);
		$this->render('index/join.step3.html');
	}
    public function pageStep3Ajax($inPath){
        $result=new stdclass;
        //验证机构状态
        $orgInfo=user_organization::getOrgByUid($this->user['uid']);
        if(empty(utility_session::get()['orgJoin']['checked'])||empty(utility_session::get()['orgJoin']['base'])){
            $result->error="页面已过期，请刷新后重试！";
            return $result;
        }   
        $data=array();
        $data['uid']=$this->user['uid'];
        $real_name=!empty($_POST['real_name'])?trim($_POST['real_name']):'';
        if(empty($real_name)){
            $result->error="请填写申请人";
            $result->field="real_name";
            return $result;
        }
        if(!preg_match("/^[a-zA-Z\x7f-\xff]+$/",$real_name)){
            $result->error="申请人只能是汉字或字母";
            $result->field="real_name";
            return $result;
        }
        if(strlen(iconv('utf-8','gb2312',$real_name))>12){
            $result->error="申请人不能超过12个字符";
            $result->field="real_name";
            return $result;
        }
        $code=!empty($_POST['code'])?trim($_POST['code']):'';
        if(empty($code)){
            $result->error="请填写验证码";
            $result->field="code";
            return $result;
        }
        $userInfo=user_api::getUser($this->user['uid']);
        if(verify_api::verifyMobile($userInfo->mobile,$code)==false){
            $result->error="验证码错误";
            $result->field="code";
            return $result;
        }
        utility_session::get()['checkMobile']['checked']=true;
        $email=!empty($_POST['email'])?trim($_POST['email']):'';
        if(empty($email)){
            $result->error="请填写邮箱";
            $result->field="email";
            return $result;
        }
        if(!preg_match("/^[a-zA-Z0-9_]+@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]{2,3}){1,2}$/",$email)){
            $result->error="邮箱格式错误";
            $result->field="email";
            return $result;
        }
        $data['email']=$email;
        $idcard_pic=!empty($_POST['idcard_pic'])?trim($_POST['idcard_pic']):'';
        if(empty($idcard_pic)){
            $result->error="请上传身份证";
            $result->field="idcard_pic";
            return $result;
        }
        $data['idcard_pic']=$idcard_pic;
        $qualify_pic=!empty($_POST['qualify_pic'])?trim($_POST['qualify_pic']):'';
        if(empty($qualify_pic)){
            $result->error="请上传机构资质";
            $result->field="qualify_pic";
            return $result;
        }
        $data['qualify_pic']=$qualify_pic;
        //修改真实姓名
        $updateUserProfile=user_api::updateUserProfile($this->user['uid'],array('real_name'=>$real_name)); 
        if($updateUserProfile===false){
            $result->error="申请失败1，请重试";
            $result->field="real_name";
            return $result;
        }
        utility_session::get()['orgJoin']['verify']=$data;
        $base=utility_session::get()['orgJoin']['base'];
        $orgBase=array(
                'fk_user_owner'=>$this->user['uid'],
                'thumb_big'=>$base['thumb_big'],
                'thumb_med'=>$base['thumb_med'],
                'thumb_small'=>$base['thumb_small'],
                'desc'=>$base['desc'],
                'status'=>0,
                'create_time'=>date('Y-m-d H:i:s'),
                'last_updated'=>date('Y-m-d H:i:s'),
				'name'=>$base['name']
            );
        $orgInfo=user_organization::getOrgByUid($this->user['uid']);
        if(empty($orgInfo)){
            $oid=user_organization::addOrg($orgBase);
        }else{
            $r=user_organization::setOrg($orgInfo->user_owner_id,$orgBase);
            if($r===false){
                $result->error="申请失败4，请重试";
                return $result;
            }
            $oid=$orgInfo->oid;
        }
        if(empty($oid)){
            $result->error="申请失败2，请重试";
            return $result;
        }
		if(empty($base['subname']) || empty($base['scopes']) ){
            $result->error = "信息失效,返回上一步重新填写~";
            $result->field = "subname";
            return $result;
        }
        $orgProfile=array(
                'subname'	=> $base['subname'],
                //'company'	=> $base['company'],
                'scopes'	=> $base['scopes'],
                'province'	=> $base['province'],
                'city'		=> $base['city'],
                'county'	=> !empty($base['county']) ? $base['county'] : 0,
                'address'	=> $base['address'],
                'hotline'	=> $base['hotline'],
				'email'		=> $data['email'],
            );
        $setOrgProfile=user_organization::setOrgProfile($this->user['uid'],$orgProfile);
        if($setOrgProfile===false){
            $result->error="申请失败3，请重试";
            return $result;
        }
        $orgVerify=array(
                'fk_org'=>$oid,
                'fk_user_owner'=>$this->user['uid'],
                'subdomain'=>$base['subdomain'].'.'.$this->domain,
                'email'=>$data['email'],
                'idcard_pic'=>$data['idcard_pic'],
                'qualify_pic'=>$data['qualify_pic'],
            );

        $addOrgVerify=user_organization::addOrgVerify($orgVerify);
        if($addOrgVerify===false){
            $result->error="申请失败4，请重试";
            return $result;
        }
        //清空session
        unset(utility_session::get()['orgJoin']);
        //发送成功短信
        //verify_api::sendOrgVerifySMS($_REQUEST['mobile'],$_REQUEST['name']); 
        return $result;
	}
    public function pageCheckRealNameAjax($inPath){
        $result=new stdclass;
        $real_name=!empty($_POST['real_name'])?trim($_POST['real_name']):'';
        if(empty($real_name)){
            $result->error="请填写申请人";
        }
        if(!preg_match("/^[a-zA-Z\x7f-\xff]+$/",$real_name)){
            $result->error="申请人只能是汉字或字母";
        }
        if(strlen(iconv('utf-8','gb2312',$real_name))>12){
            $result->error="申请人不能超过12个字符";
        }
        return $result;
    }
    public function pageCheckEmailAjax($inPath){
        $result=new stdclass;
        $email=!empty($_POST['email'])?trim($_POST['email']):'';
        if(empty($email)){
            $result->error="请填写邮箱";
        }
        if(!preg_match("/^[a-zA-Z0-9_]+@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]{2,3}){1,2}$/",$email)){
            $result->error="邮箱格式错误";
        }
        return $result;
    }
    public function pageCheckMobileAjax($inPath){
        $result=new stdclass;
        $userInfo=user_api::getUser($this->user['uid']);
        if(empty($userInfo->mobile)){
            $result->error="手机号码获取失败";
            $result->field="code";
            return $result;
        }
        //发送验证码
        $r = verify_api::sendMobileCode($userInfo->mobile,$ret);
        if($r===false){
            $result->error="发送验证码错误,[".$ret->result->msg."]";
            $result->field="code";
            return $result;
         }   
        utility_session::get()['checkMobile']['time']=time();
        $result->field="code";
        $result->ok = 1;
        return $result; 
    }
	public function pageStep4($inPath){
        $success=!empty($_GET['success'])?$_GET['success']:'';
        $this->assign('success',$success);
        $orgInfo=user_organization::getOrgByUid($this->user['uid']); 
        if(!empty($orgInfo)){
			$info =user_organization::getApplyOrgSubdomainOfUser($this->user['uid']);
			$subdomain = !empty($info->subdomain) ? user_organization::course_domain($info->subdomain) : '';
			$this->assign('subdomain',$subdomain);
            $this->assign('orgInfo',$orgInfo);
		    $this->render('index/join.step4.html');
		}else{
            $this->redirect("/index/join/step1");
        }
	}
	public function pageorgCourseRange($inPath){
		$orgVerify=user_organization::getOrgVerify($this->user['uid']);
		if(!empty($orgVerify->scopes)){
			$selectScopes = explode(",",$orgVerify->scopes);
			$this->assign('selectScopes',$selectScopes);
		}
        $cate = index_api::getCourseFirstCateInfo($this->level);
		$this->assign('cate',$cate);
        $this->render("/layer/orgCourseRange.html");
	}
}


