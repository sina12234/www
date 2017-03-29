<?php
class sms_api{
    public static function yunpianCallback($data){
        $params = new stdclass;
        $params->sms_status = $data["sms_status"];
		$ret = utility_services::call("/sms/task/yunpianreturn", $params);
		if(!empty($ret->result) && $ret->result->code==0){
			return true;
		}
		return false;
    }

	//短信发送记录
	public static function messageSendList($data){
		$params = new stdClass();
		if(!isset($data['org_id']) || empty(trim($data['org_id']))) return interface_func::setMsg(3002);
		if(!empty($data['mobile'])) $params->mobile = trim($data['mobile']) ? (int)trim($data['mobile']) : '';
		if(!empty($data['sms_type'])) $params->sms_type = (int)$data['sms_type'];
		if(!empty($data['status'])) $params->status = (int)$data['status'];
		if(!empty($data['start_time'])) $params->start_time = $data['start_time'];
		if(!empty($data['end_time'])) $params->end_time = $data['end_time'];
		if(!empty($data['course_name'])) $params->course_name = trim($data['course_name']) ? trim(htmlspecialchars(strip_tags($data['course_name']))) : '';
		if(!empty($data['page'])) $params->page = $data['page'] ? (int)$data['page'] : 1;
		$params->org_id = (int)$data['org_id'];
		$params->page_size = (int)$data['page_size'];
		$ret = utility_services::call('/sms/message/messagesendlist',$params);
		return $ret;
	}

	//短信首页
	public static function smsIndex($data){
		if(empty($data->org_id)) return interface_func::setMsg(3002);
		$params = new stdClass();
		$params->org_id = (int)$data->org_id ? (int)$data->org_id : '';
		$ret = utility_services::call('/sms/message/smsindex',$params);
		return $ret;
	}

	//服务点击操作
	public static function smsOperation($data){
		if(empty($data->org_id) || empty($data->service_id)) return interface_func::setMsg(3002);
		$data->org_id = (int)$data->org_id;
		$data->service_id = (int)$data->service_id;
		 $ret = utility_services::call('/sms/message/messagesingle',$data);
		return $ret;
	}

	//服务点击确认按钮
	public static function smsUpdate($data){
		if(empty($data->org_id) || empty($data->service_id)) return interface_func::setMsg(3002);
		if(!isset($data->course_free) || !isset($data->course_charge) || !isset($data->status)) return interface_func::setMsg(3002);
		$data->course_charge = (int)$data->course_charge;
		$data->course_free = (int)$data->course_free;
		$data->org_id = (int)$data->org_id;
		$data->service_id = (int)$data->service_id;
		$data->status = (int)$data->status;
		if($data->status==1){
			if(empty($data->course_charge) && empty($data->course_free)){
				return interface_func::setMsg(3002,'必须选择其中一个类型');
			}
		}else{
			$data->course_charge = 0;
			$data->course_free = 0;
		}
		$ret = utility_services::call('/sms/message/messageservice',$data);
		return $ret;
	}

	//短信充值记录
	public static function smsRecharge($params){
		if(empty($params->org_id) || !is_numeric($params->org_id)) return interface_func::setMsg(3002);
		$params->org_id = (int)$params->org_id;
		$params->page = !empty($params->page) ? (int)trim($params->page) : 1;
		$params->page_size = !empty($params->page_size) ? (int)trim($params->page_size) : 20;
		$ret = utility_services::call('/order/info/getsmslist',$params);
		return $ret;
	}

	//发送短信 接口

    public static function SmsSend($params){
		if(isset($params->mobile)){
			$params->mobile = !empty($params->mobile) ? (int)trim($params->mobile) : '';
		}
		if(!empty($params->sms_type)){
			//短信类型  1 报名提醒 2 开课提醒 3 修改排课 4 课堂统计
			$params->sms_type = !empty($params->sms_type) ? (int)trim($params->sms_type) : '';
		}
		if(!empty($params->course_name)){
			$params->course_name = !empty($params->course_name) ? trim($params->course_name) : '';
		}
		if(!empty($params->org_id)){
			$params->org_id = !empty($params->org_id) ? (int)trim($params->org_id) : '';
		}

		if( empty($params->mobile) || empty($params->sms_type) || empty($params->course_name)  || empty($params->org_id)) return interface_func::setMsg(3002);
		if(empty($params->org_name)) return interface_func::setMsg(3002,'机构简称不能为空');
		//课程 是㤇收费 还是免费  1 收费 2 免费
		if(empty($params->course_fee)) return interface_func::setMsg(3002,'课程收费状态');

		if(!in_array($params->sms_type,array(1,3))) return interface_func::setMsg(3002,'不在发送范围内');
		if($params->sms_type==1){
			//报名成功
			if(!empty($params->class_name)){
				$params->class_name = !empty($params->class_name) ? trim($params->class_name) : '';
			}
			if(empty($params->class_name)) return interface_func::setMsg(3002,'班级名不能为空');
			$params->content = addslashes('恭喜你成功报名 '.$params->course_name.'——'.$params->class_name.'。 -'.$params->org_name) ;
			$params->msg = "#course#=$params->course_name&#class#=$params->class_name&#org#=$params->org_name";
			$params->tpl_id = 1731690;
		}elseif($params->sms_type==3){
			//修改排课
			if(!empty($params->order_no)) $params->order_no = !empty($params->order_no) ? trim($params->order_no) : '';
			if(!empty($params->start_time)) $params->start_time = !empty($params->start_time) ? trim($params->start_time) : '';
			if(empty($params->order_no)) return interface_func::setMsg(3002,'课时序号不能为空');
			if(empty($params->start_time)) return interface_func::setMsg(3002,'课程开课时间不能为空');
			$params->content = addslashes($params->course_name.'-'.$params->order_no .'上课时间调整到'.$params->start_time.'记得按时来上课哦~ -'.$params->org_name);
			$params->order_no = "第".$params->order_no."课时";
			$params->msg = "#course#=$params->course_name&#order_no#=$params->order_no&#time#=$params->start_time&#org#=$params->org_name";
			$params->tpl_id = 1731706;
		}

		$ret = utility_services::call('/sms/message/SmsSend',$params);
		return $ret;
	}
}
?>
