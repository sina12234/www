<?php

class course_resell_api
{
    const GET_COURSE_RESELL = '/course/resell/GetCourseResell/';

    const GET_COURSE_PROMOTE = '/course/resell/GetCoursePromote/';

    const ADD_RESELL_LOG = '/course/resell/AddResellLog/';

    const UPDATE_RESELL_ORDER_NUM = '/course/resell/UpdateResellOrderNum/';

    const UPDATE_PROMOTE_ORDER_NUM = '/course/resell/UpdatePromoteOrderNum/';
    
    public static $resell_status_arr = array(
                    '0'   => array('info'=>'异常'   ,'promote_info'=>'已停止' , 'tip'=>'初始'),
                    '1'   => array('info'=>'正常'   ,'promote_info'=>'正常' , 'tip'=>'正常'),
                    '-1'  => array('info'=>'删除'   ,'promote_info'=>'已停止' , 'tip'=>'删除'),
                    '-2'  => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'已停止'),
                    '-3'  => array('info'=>'已失效' ,'promote_info'=>'正常' , 'tip'=>'修改了课程成本价格'),  
                    '-4'  => array('info'=>'已失效' ,'promote_info'=>'正常' , 'tip'=>'推广方重新推广'),                  
                    '-5' => array('info'=>'已失效' ,'promote_info'=>'正常' , 'tip'=>'推广方重新推广'),
                    '-13' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'推广方停止了课程推广'),
                    '-14' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'推广方删除了课程推广'),
                    '-22' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'修改课程下架'),
                    '-23' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'修改课程上架'),
                    '-24' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'修改课程可报名人数'),
                    '-25' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'修改课程付费类型'),
                    '-26' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'修改课程原价'),
                    '-31' => array('info'=>'已失效' ,'promote_info'=>'已停止' , 'tip'=>'课程报满')
                 );

    public static function getCourseResell($courseId, $resellOrgId)
    {
        if (!$courseId || !$resellOrgId) return [];

        $params = [
            'courseId'    => $courseId,
            'resellOrgId' => $resellOrgId
        ];
        $res    = interface_func::requestApi(self::GET_COURSE_RESELL, $params);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    public static function getCoursePromote($courseId)
    {
        if (!$courseId) return [];

        $res = interface_func::requestApi(self::GET_COURSE_PROMOTE.$courseId);
        if (!empty($res['code'])) return [];

        return $res['result'];
    }

    public static function getResellCourseInfo($courseId, $resellOrgId)
    {
        $resellInfo = self::getCourseResell($courseId, $resellOrgId);
        if (empty($resellInfo)) return [];

        $promoteInfo = self::getCoursePromote($courseId);
        if (empty($promoteInfo)) return [];

        if ($resellInfo['status'] == 1 && $promoteInfo['status'] > 0 && $resellInfo['ver'] == $promoteInfo['ver']) {
            return [
                'courseId'    => $courseId,
                'resellOrgId' => $resellOrgId,
                'priceResell' => $resellInfo['price_resell']/100,
                'pricePromete'=> $promoteInfo['price_promote']/100
            ];
        }

        return [];
    }

    public static function addResellLog($data)
    {
        $res = interface_func::requestApi(self::ADD_RESELL_LOG, $data);
        if (!empty($res['code'])) return false;

        return true;
    }

    public static function updatePromoteOrderNum($courseId, $inCome)
    {
        if (!(int)$courseId) return false;

        $data = [
            'courseId' => (int)$courseId,
            'inCome'   => $inCome
        ];
        $res  = interface_func::requestApi(self::UPDATE_PROMOTE_ORDER_NUM, $data);
        if (!empty($res['code'])) return false;

        return true;
    }

    public static function updateResellOrderNum($courseId, $resellOrgId, $inCome)
    {
        $courseId    = (int)$courseId;
        $resellOrgId = (int)$resellOrgId;
        if (!$courseId || !$resellOrgId) return false;


        $data = [
            'courseId'    => $courseId,
            'resellOrgId' => $resellOrgId,
            'inCome'      => $inCome
        ];
        $res  = interface_func::requestApi(self::UPDATE_RESELL_ORDER_NUM, $data);
        if (!empty($res['code'])) return false;

        return true;
    }
         
         /* 获取章节数，分类 ，属性 信息 */
        public static function getCourseAttr($course_info){  
            if (empty($course_info)) return false;
            
            $attrValues = array();
            foreach($course_info as $key => $value){
                    $tmp = new stdClass();
                    //$tmp->sectionNum = count($value->section);
                    $tmp->second_cate_name = $value->second_cate_name;
                    $tmp->third_cate_name  = $value->third_cate_name;
                    $tmp->subdomain  = user_organization::course_domain($value->subdomain);
                    if(!empty($value->course_attr)){
                            foreach($value->course_attr as $val){
                                    if(!empty($val->attr_value)){
                                            $attr_value_name = '';
                                            foreach($val->attr_value as $v){
                                                    $attr_value_name .= $v->attr_value_name.',';
                                            }
                                            $tmp->attr_value = substr($attr_value_name, 0,-1); 
                                    }
                            }
                    }   
                    $attrValues[$value->course_id] = $tmp;            
            }   

            return $attrValues;
        }
        /* 从中间层获取数据 */
        public static function getSeekCourseInfo($course_ids,$searchType=1,$searchField=''){
            if (empty($course_ids)) return false;
            
            $fields = ["course_id","title","thumb_sma","price","market_price","first_cate","second_cate","third_cate","second_cate_name","third_cate_name","remain_user","status","course_type","desc","course_attr","org_subname","subdomain","price_promote","is_promote","fee_type",
                //"section_count","section"
            ];

            $query = [
                    'course_id'     => $course_ids,
            ];
            if($searchType==2){
                $query['org_subname']=$searchField;
            }
            foreach($query as $k=>$v){
                if($v=='' || $v==-1){
                    unset($query[$k]);
                }
            }
            $params = [
                    "f" => $fields,
                    "q" => $query,
                    "p" => 1,
                    "pl"=> 20,
                    "ob"=> ''
            ];
            $resCourse = seek_api::seekcourse($params);     

            $courseList  = array();
            if(!empty($resCourse->data)){
                    $courseList = $resCourse->data;
            }          
            if(!empty($resCourse->total)){
                    $count=$resCourse->total;       // 总条数
            }     
            $total = $count = 0; 
            $total = ceil($count/$resCourse->pagelength); // 总页数 
            
            return $courseList;
        }
        
        /*重新引入+删除分销课程*/
        public static function changeResellCourse($op,$params=array()){
            $courseResellList = utility_services::call("/course/resell/changeResellCourse/{$op}",$params);
            if(!empty($courseResellList)){
                return $courseResellList;
            }else{
                return false;
            }
        }
        
        /*(删除+暂停推广+重新推广)推广课程*/
        /*
        public static function changePromoteResellCourseStatus($op,$params=array()){
            $coursePromoteList = utility_services::call("/course/promote/SyncPromoteResellCourseStatus/{$op}",$params);
            if(!empty($coursePromoteList)){
                return $coursePromoteList;
            }else{
                return false;
            }
        }  */    
        /* 修改课程原价,修改课程付费类型,课程报满 */
        public static function changePromoteCourse($op,$params=array()){
            //$coursePromoteList = utility_services::call("/course/promote/SyncPromoteCourseStatusVer/{$op}",$params);
            $coursePromoteList = utility_services::call("/course/promote/SyncPRC/{$op}",$params);
            if(!empty($coursePromoteList)){
                return $coursePromoteList;
            }else{
                return false;
            }
        }        
        
        /* 修改推广课程信息 */
        public static function EditPromoteCourse($courseID,$params=array(),$op){
            $coursePromoteList = utility_services::call("/course/promote/EditPromoteCourse/{$op}/{$courseID}",$params);
            if(!empty($coursePromoteList)){
                return $coursePromoteList;
            }else{
                return false;
            }
        } 
        
        /* 修改分销课程信息 */
        public static function EditResellCourse($courseID,$params=array()){
            $coursePromoteList = utility_services::call("/course/resell/EditResellCourse/{$courseID}",$params);
            if(!empty($coursePromoteList)){
                return $coursePromoteList;
            }else{
                return false;
            }
        }	
        
###############################################################################   
	public static function getUserCourseRelation($params){
		$ret = utility_services::call("/course/resell/getUserCourseRelation",$params);
		if(!empty($ret)){
			return $ret;
		}else{
			return false;
		}
	}
	public static function getPromoteCourselist($page, $length , $params = array()){
		$res = utility_services::call("/course/promote/GetPromoteCourseList/".$page."/".$length,$params);
		if(!empty($res)){
			return $res;
		}else{
			return false;
		}
	}

	public static function getResellCourselist($page, $length , $params = array()){
		$res = utility_services::call("/course/resell/GetResellCourseList/".$page."/".$length,$params);
		if(!empty($res)){
			return $res;
		}else{
			return false;
		}
	}
        
        public static function courseResellCount($params = array()){
            $resellCount = utility_services::call("/course/resell/courseResellCount",$params);
            if(!empty($resellCount)){
                return $resellCount;
            }else{
                return 0;
            }
        }
        
        public static function coursePromoteCount($params = array()){
            $PromoteCount = utility_services::call("/course/promote/coursePromoteCount",$params);
            if(!empty($PromoteCount)){
                return $PromoteCount;
            }else{
                return 0;
            }
        }

        public static function addResell($params = array()){
            $ret = utility_services::call("/course/resell/AddResellCourse",$params);
            if(!empty($ret)){
                return $ret;
            }
            return false;
        }
 
        public static function addPromote($params=array()){
            $ret = utility_services::call("/course/promote/AddPromoteCourse",$params);
            if(!empty($ret)){
                return $ret;
            }
            return false;
        }

        /*获取分销、推广成交记录*/
        public static function getCourseResellLog($page, $length ,$params=array()){
            $courseResellLogList = utility_services::call("/course/resell/GetCourseResellLog/".$page."/".$length,$params);
            if(!empty($courseResellLogList)){
                return $courseResellLogList;
            }else{
                return false;
            }
        }
        
        /*获取机构分销课程*/
        public static function getSalesCourse($page,$length,$condition){
           $ret = utility_services::call("/course/resell/getSalesCourse/$page/$length", $condition);
           if (!empty($ret->data)) {
               return $ret->data;
           } else {
               return false;
           }
       }
       /* 获取课程信息(报满，线下课，courseID异常) */
        public static function getCourseInfo($cid,$params=array()){
            $ret = utility_services::call("/course/promote/GetResellCourseInfo/{$cid}",$params);
            if(!empty($ret)){
                return $ret;
            }else{
                return false;
            }
        }
       /* 发送分销消息
        * 参数 $courseInfo
        * 'org_subname'         : 推广机构名
        * 'title'               : 课程名称
        * 'price'               : 原价
        * 'price_new'           : 新原价
        * 'price_promote'       : 成本价
        * 'price_promote_new'   : 新成本价
        * 'price_resell'        : 成本价
        * 'price_resell_new'    : 新出售价
        * 'fee_type'            : 付费类型 （收费模式:0 免费课,1 收费课）
        * 'fee_type_new'        : 新付费类型        * 
        */
       public static function sendResellMessage($courseId,$op,$courseInfo)
        {
            $promote_org    = isset($courseInfo->org_subname) ? $courseInfo->org_subname :'' ;
            $course_title   = isset($courseInfo->title) ? $courseInfo->title : '';
            $price          = isset($courseInfo->price) ? $courseInfo->price/100 : '';
            $price_new      = isset($courseInfo->price_new) ? $courseInfo->price_new : '';
            $price_promote  = isset($courseInfo->price_promote) ? $courseInfo->price_promote/100 : '';
            $price_promote_new  = isset($courseInfo->price_promote_new) ? $courseInfo->price_promote_new : '';
            $price_resell       = isset($courseInfo->price_resell) ? $courseInfo->price_resell/100 : '';
            $price_resell_new   = isset($courseInfo->price_resell_new) ? $courseInfo->price_resell_new : '';
            $fee_type       = isset($courseInfo->fee_type) ? $courseInfo->fee_type : 0;
            $fee_type_new   = isset($courseInfo->fee_type_new) ? $courseInfo->fee_type_new : 0;
            $fee = ['0'=>'免费课','1'=>'收费课'];
            
            $contentMsg = [
                'orgDelPromote' => ['content'=>sprintf('%s 删除了您引入的课程［%s］',$promote_org,$course_title)],
                'orgStopPromote' => ['content'=>sprintf('您引入的［%s］已经被 %s 删除了',$course_title,$promote_org)],
                'orgStartPromote' => ['content'=>sprintf('%s 重新推广了您引入的课程［%s］',$promote_org,$course_title)],
                'updatePromotePrice' => ['content'=>sprintf('%s 将课程［%s］的成本价从%s修改为%s',$promote_org,$course_title,$price_promote,$price_promote_new)],
                
                'updateCoursePrice' => ['content'=>sprintf('%s 将课程［%s］的原价从%s修改为%s',$promote_org,$course_title,$price,$price_new)],
                'updateCourseFeetype' => ['content'=>sprintf('%s 将课程［%s］从 %s 调整为 %s ',$promote_org,$course_title,$fee[$fee_type],$fee[$fee_type_new])],
                'courseMaxUser' => ['content'=>sprintf('您引入的［%s］报名人数已满，课程已失效 ', $course_title)],
            ];
            // 发送消息
            $data = [
                'userFrom' => 0,
                'content' => $contentMsg[$op]['content'],
                'title' => '分销通知',
                'msgType' => message_type::RESELL_NOTICE,
            ];
				
            return course_resell_api::sendMessage($courseId,$data);
        }
        
        public static function sendMessage($courseId,$data)
        {           	
           $sendUser = course_resell_api::_getLocalOrgAdmin($courseId);
           foreach ( $sendUser as $v ) {
                $data['userTo'] = $v;
                if (message_api::addDialog($data) === false) {
                    SLog::fatal('发送通知管理员信息失败[%s]', var_export(
                        [
                            'data' => $data,
                            'user' => $sendUser
                        ], 1
                    ));
                }
            }

            return json_encode(['code'=>0, 'msg'=>'消息发送成功']);
        }
	public static function _getLocalOrgAdmin($courseId)
        {
            $orgId = course_api::getOrgIdByCourseId($courseId);
            if (!$orgId) return [];
            $param = [
                'condition' => "fk_org={$orgId} and status<>-1 and role=2"
            ];

            $r = utility_services::call('/user/organizationUser/GetAdminList', $param);

            $data = [];
            if (!empty($r->items)) {
                foreach ($r->items as $v) {
                    $data[] = $v->fk_user;
                }
            }

            return array_unique($data);
        }
	/*
	 *@desc   修改课程分销 原价/付费类型/分销状态   
	 *@param  $courseId 分销课程Id
	 *@param  $option  updateCourseFeetype 付费类型  updateCoursePrice 课程原价 courseMaxUser 课程报满
	 *@param  $data
	 *@return boole
	 */
	public static function updatePromoteTypeAndSetMsg($courseId, $option, $data,$orgId=0)
	{
		if(empty($courseId) || empty($option) || empty($data)){
			return false;
		}
		
		$params = new stdClass();
		$params->title       = $data['title'];
		$params->org_subname = $data['subname'];
		
		if(!empty($data['fee_type_new'])){
			$params->fee_type_new = $data['fee_type_new'];
		}
		if(!empty($data['fee_type_old'])){
			$params->fee_type = $data['fee_type_old'];
		}
		if(!empty($data['price'])){
			$params->price = $data['price_old'];
		}
		if(!empty($data['price_old'])){
			$params->price_new = $data['price'];
		}
		
		$promoteReg = self::changePromoteCourse($option, array('course_id'=>$courseId,'org_id'=>$orgId));  
		if($promoteReg){
			self::sendResellMessage($courseId, $option, $params);
			return true;
		}
		
		return false;
	}

    public static function getCourseRsellOrgList($courseId){
        $ret = utility_services::call("/course/resell/getCourseResellByCid/{$courseId}");
        if(!empty($ret->result->items)){
            return $ret->result->items;
        }else{
            return false;
        }
    }

    public static function getPromoteCourseResellCount($cidArr){
        $data = array("cidArr"=>$cidArr);
        $ret = utility_services::call("/course/resell/getPromoteCourseResellCount",$data);
        if(!empty($ret->data)){
            return $ret->data;
        }else{
            return false;
        }
    }

}
