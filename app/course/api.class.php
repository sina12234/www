<?php

class course_api
{
    private static $attrs = array(
        'course_id'      => 'course_id',
        'title'          => 'title',
        'tags'           => 'tags',
        'desc'           => 'desc',
        'thumb_big'      => 'thumb_big',
        'thumb_med'      => 'thumb_med',
        'thumb_sma'      => 'thumb_small',
        'user_thumb_big' => 'user_thumb_big',
        'user_thumb_med' => 'user_thumb_med',
        'user_thumb_sma' => 'user_thumb_sma',
        'user_name'      => 'user_name',
        'cate_id'        => 'cate_id',
        'course_type'    => 'course_type',
        'grade_id'       => 'grade_id',
        'user_id'        => 'user_id',
        'public_type'    => 'public_type',
        'fee_type'       => 'fee_type',
        'max_user'       => 'max_user',
        'min_user'       => 'min_user',
        'user_total'     => 'user_total',
        'status'         => 'status',
        'admin_status'   => 'admin_status',
        'system_status'  => 'system_status',
        'start_time'     => 'start_time',
        'end_time'       => 'end_time',
        'create_time'    => 'create_time',
        'last_updated'   => 'last_updated',
        'class_id'       => 'class_id',
        'section_id'     => 'section_id',
        'price'          => 'price',
        'market_price'   => 'market_price',
        //新加上的
        'top'            => 'top',
    );
    private static $timesArr = array(
        'start_time'   => 0,
        'end_time'     => 0,
        'create_time'  => 0,
        'last_updated' => 0
    );

    public static function seekCourse($sdata)
    {
        $params     = new stdclass;
        $params->f  = new stdclass;
        $params->q  = new stdclass;
        $params->ob = new stdclass;
        /*		foreach(self::$attrs as $attrk=>$attrv){
                    if(isset($sdata["f"][$attrk]))$params->f->$attrk = $sdata["f"][$attrk];
                }
        */
        if (isset($sdata["f"])) $params->f = $sdata["f"];
        foreach (self::$attrs as $attrk => $attrv) {
            if (isset($sdata["q"][$attrk])) $params->q->$attrk = $sdata["q"][$attrk];
        }
        foreach (self::$attrs as $attrk => $attrv) {
            if (isset($sdata["ob"][$attrk])) $params->ob->$attrk = $sdata["ob"][$attrk];
        }
        if (isset($sdata["p"])) {
            $params->p = $sdata["p"];
        } else {
            $params->p = "1";
        }
        if (isset($sdata["pl"])) {
            $params->pl = $sdata["pl"];
        } else {
            $params->pl = "20";
        }
        $ret = utility_services::call("/seek/course/list/", $params);

        return $ret;
        /*	if(!empty($ret->result) && $ret->result->code==0){
            return true;
        }
            return false;
         */
    }

    public static function genId($uid)
    {
        $ret = utility_services::call("/course/info/genId/$uid");
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function genclassId($cid)
    {
        $ret = utility_services::call("/course/class/genclassid/$cid");
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getClass($class_id)
    {
        $ret = utility_services::call("/course/class/get/$class_id");
        if(!empty($ret->result)){
            return false;
        }

        return $ret;
    }

    public static function checkClassUserIsFull($classId)
    {
        $classInfo = self::getClass($classId);
        if (!empty($classInfo)) {
            if ($classInfo->user_total >= $classInfo->max_user) {
                return false;
            }
            return true;
        }

        return false;
    }

    public static function updateCourse($cid, $cdata, $price = 0,$attrValueIds=''){
        $params           = new stdclass;
        $params->title    = $cdata["title"];
        $params->fee_type = $cdata["fee_type"];
        if (isset($cdata["start_time"])) {
            $params->start_time = $cdata["start_time"];
        }
        if (isset($cdata["end_time"])) {
            $params->end_time = $cdata["end_time"];
        }
		$params->scope = $cdata["scope"];
        $params->descript = $cdata["descript"];
        if (isset($cdata["max_user"])) {
            $params->max_user = $cdata["max_user"];
        }
        if (isset($cdata["min_user"])) {
            $params->min_user = $cdata["min_user"];
        }
        if (isset($cdata["type"])) {
            $params->type = $cdata["type"];
        }
		if (isset($cdata["first_cate"])) {
            $params->first_cate = $cdata["first_cate"];
        }
		if (isset($cdata["second_cate"])) {
            $params->second_cate = $cdata["second_cate"];
        }
		if (isset($cdata["third_cate"])) {
            $params->third_cate = $cdata["third_cate"];
        }
        $params->thumb_big   = $cdata["thumb_big"];
        $params->thumb_med   = $cdata["thumb_med"];
        $params->thumb_small = $cdata["thumb_small"];
        if ($price) {
            $params->fee        = new stdclass;
            $params->fee->price = $price;
        }
		if($attrValueIds){
			$params->attr        = new stdclass;
            $params->attr->attr_value_ids = $attrValueIds;
		}
        $ret = utility_services::call("/course/info/update/$cid", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }
	
	public static function updateCourseV2($courseId, $data){
		if(empty($courseId)) return false;
		
		$params = new stdClass;
		if(!empty($data['title'])){
			$params->title = $data['title'];
		}
		if(!empty($data['scope'])){
			$params->scope = $data['scope'];
		}
		if(!empty($data['descript'])){
			$params->descript = $data['descript'];
		}
		if (isset($data["maxUser"])) {
            $params->max_user = $data["maxUser"];
        }
		if (isset($data["first_cate"])) {
            $params->first_cate = $data["first_cate"];
        }
		if (isset($data["second_cate"])) {
            $params->second_cate = $data["second_cate"];
        }
		if (isset($data["third_cate"])) {
            $params->third_cate = $data["third_cate"];
        }
		if(!empty($data['thumbBig'])){
			$params->thumb_big  = $data["thumbBig"];
		}
		if(!empty($data['thumbMed'])){
			$params->thumb_med  = $data["thumbMed"];
		}
		if(!empty($data['thumbSmal'])){
			$params->thumb_small = $data["thumbSmal"];
		}
        if(isset($data['is_distribute'])){
            $params->is_distribute = $data["is_distribute"];
        }
        $params->fee        = new stdclass;
		if(isset($data['price'])) {
            $params->fee->price = $data['price'];
        }
        if(isset($data['fee_type'])){
            $params->fee_type = $data['fee_type'];
        }
		if(!empty($data['attrValueIds'])){
			$params->attr        = new stdclass;
            $params->attr->attr_value_ids = $data['attrValueIds'];
		}
		
        $ret = utility_services::call("/course/info/update/$courseId", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
	}
	
	public static function setCourseImg($courseId, $data){
		if(empty($courseId)) return false;
		
		$params = new stdClass;
		if(!empty($data['thumbBig'])){
			$params->thumb_big  = $data["thumbBig"];
		}
		if(!empty($data['thumbMed'])){
			$params->thumb_med  = $data["thumbMed"];
		}
		if(!empty($data['thumbSmal'])){
			$params->thumb_small = $data["thumbSmal"];
		}
		if(!empty($data['scope'])){
			$params->scope = $data['scope'];
		}
		if(!empty($data['descript'])){
			$params->descript = $data['descript'];
		}
        if(isset($data['document'])){
            $params->document = $data['document'];
        }

		$ret = utility_services::call("/course/info/setCourseImg/$courseId", $params);
		
		if(empty($ret->code)){
			return true;
		}
		
        return false;
	}
	
	public static function addCourse( $cdata, $price = 0,$attrValueIds=''){
        $params           = new stdclass;
        $params->title    = $cdata["title"];
        $params->fee_type = $cdata["fee_type"];
		$params->user_id  = $cdata['user_id'];
        if (isset($cdata["start_time"])) {
            $params->start_time = $cdata["start_time"];
        }
        if (isset($cdata["end_time"])) {
            $params->end_time = $cdata["end_time"];
        }
		$params->scope = $cdata["scope"];
        $params->descript = $cdata["descript"];
        if (isset($cdata["type"])) {
            $params->type = $cdata["type"];
        }
		if (isset($cdata["first_cate"])) {
            $params->first_cate = $cdata["first_cate"];
        }
		if (isset($cdata["second_cate"])) {
            $params->second_cate = $cdata["second_cate"];
        }
		if (isset($cdata["third_cate"])) {
            $params->third_cate = $cdata["third_cate"];
        }
        $params->thumb_big   = $cdata["thumb_big"];
        $params->thumb_med   = $cdata["thumb_med"];
        $params->thumb_small = $cdata["thumb_small"];
        if ($price) {
            $params->fee        = new stdclass;
            $params->fee->price = $price;
        }
		if($attrValueIds){
			$params->attr        = new stdclass;
            $params->attr->attr_value_ids = $attrValueIds;
		}
        $ret = utility_services::call("/course/info/addCourse", $params);
        if (!empty($ret->data) ) {
            return $ret->data;
        }

        return false;
    }
	
	//新版建课程 
	public static function addCourseV2($data)
	{
		if(empty($data) || empty($data['user_id'])) return false;
		
		$params           = new stdclass;
		$params->title    = $data["title"];
        $params->fee_type = $data["fee_type"];
		$params->user_id  = $data['user_id'];
		$params->type     = $data["type"];
		$params->first_cate  = $data["first_cate"];
		$params->second_cate = $data["second_cate"];
		$params->third_cate  = $data["third_cate"];
		
		if(!empty($data['price'])){
			$params->fee        = new stdclass;
            $params->fee->price = $data['price'];
		}
		if(!empty($data['attrValueIds'])){
			$params->attr        = new stdclass;
            $params->attr->attr_value_ids = $data['attrValueIds'];
		}
		$ret = utility_services::call("/course/info/addCourse", $params);
        if (!empty($ret->data) ) {
            return $ret->data;
        }
		
		return false;	
    }
	

    public static function setCourseAdminStatus($cid, $status)
    {
        $params               = new stdclass;
        $params->admin_status = $status;
        $ret                  = utility_services::call("/course/info/SetAdminStatus/$cid", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    /*
     *获取单个课程
     */
    public static function getCourseOne($cid)
    {
        $ret = utility_services::call("/course/info/get/$cid");
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }
    /*
     *根据班级的id获取结束的plan的个数
     */
    public static function endGroupByClassIds ($userId=null,$classIdsArr,$type=null,$ut=null){
        $params           = new stdclass;
		$params->classIdsArr = $classIdsArr;
		$params->userId = $userId;
		$params->type = $type;
		$params->ut = $ut;
        $ret = utility_services::call("/course/plan/endGroupByClassIds/",$params);
        if (!empty($ret->data)) {
            return $ret;
        } else {
            return false;
        }
    }
	
	public static function endGroupByClassIdsV2 ($userId,$classIdsArr){
		$params           = new stdclass;
		$params->classIdsArr = $classIdsArr;
		$params->userId = $userId;
		$ret = utility_services::call("/course/plan/endGroupByClassIdsV2/",$params);
		if (!empty($ret->data)) {
			return $ret;
		} else {
			return false;
		}
	}
	
    /*
     *根据课程id获取章节的个数
     */
    public static function planGroupSectionByCourseIds($courseIdsArr){
        $params           = new stdclass;
		$params->courseIdsArr = $courseIdsArr;
		//$params->userId = $userId;
        $ret = utility_services::call("/course/section/planGroupSectionByCourseIds/",$params);
        if(empty($ret->code)) {
            return $ret->result;
        } else {
            return false;
        }
    }

    public static function getCourselist($page, $size, $fee_type, $grade_id = 0, $week = 0, $oid = 0, $shelf = false)
    {
        $params           = new stdclass;
        $params->fee_type = $fee_type;
        $params->grade_id = $grade_id;
        $params->week     = $week;
        if ($shelf != false) {
            $params->shelf = $shelf;
        }
        if ($oid != 0) {
            $params->oid = $oid;
        }
        $ret = utility_services::call("/course/info/courselist/$page/$size", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function CourseLikelist($userId,$courseIds,$data){
       $params           = new stdclass;
        $params->cond     = new stdclass;
        if (!empty($userId)) {
            $params->cond->user_id = $userId;
        }
        if (!empty($courseIds)) {
            $params->cond->course_ids = $courseIds;
        }
        if (!empty($data["search"])) {
            $params->cond->search = $data["search"];
        }
		if (!empty($data["page"])) {
            $params->cond->page = $data["page"];
        }
		if (!empty($data["course_type"])) {
            $params->cond->course_type = $data["course_type"];
        }
		if (!empty($data["st"])) {
            $params->cond->st = $data["st"];
        }
		if (isset($data["ut"])) {
            $params->cond->ut = $data["ut"];
        }
        $ret = utility_services::call("/course/info/courselikelist/", $params);
        if (!empty($ret->data)) {
            return $ret;
        } else {
            return false;
        }
    }
    public static function getCourselistindex($page, $size, $fee_type, $grade_id = 0, $week = 0)
    {
        $params           = new stdclass;
        $params->fee_type = $fee_type;
        $params->grade_id = $grade_id;
        $params->week     = $week;
        $ret              = utility_services::call("/course/info/courselistindex/$page/$size", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getFinishedCourselist($grade_id, $oid, $shelf, $page, $size)
    {
        $params           = new stdclass;
        $params->status   = "finished";
        $params->grade_id = $grade_id;
        $params->oid      = $oid;
        $params->shelf    = $shelf;
        $ret              = utility_services::call("/course/info/courselist/$page/$size", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function getCourselistByOid($page, $size, $oid, $data = array())
    {
        $params      = new stdclass;
        $params->oid = $oid;
        $dataArr     = array(
            "status"      => "status",
            "grade_id"    => "grade_id",
            "shelf"       => "shelf",
            "week"        => "week",
            "fee_type"    => "fee_type",
            "create_time" => "create_time",
            "user_total"  => "user_total",
            "search"      => "search",
			"type"		  => "type",	
        );
        foreach ($dataArr as $dak => $dav) {
            if (isset($data[$dak])) {
                $params->$dak = $data[$dak];
            }
        }
        $ret = utility_services::call("/course/info/courselist/$page/$size", $params);
        if (!empty($ret->data)) {
            return $ret;
        } else {
            return false;
        }
    }

    public static function getCate($cid)
    {
        $ret = utility_services::call("/course/cate/list/");
        if (!empty($ret->result)) {
            return $ret->result;
        } else {
            return false;
        }
    }

    public static function getCourseId($uid)
    {
        $ret = utility_services::call("/course/info/genId/$uid");
        if ($ret->result->code != 0) return false;
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function delClass($class_id, $cid)
    {
        $ret = utility_services::call("/course/class/del/$class_id/$cid");
        if (!empty($ret->result) && $ret->result->code == 0) {
            return $ret;
        } elseif (!empty($ret->result) && $ret->result->code == -4) {
            return $ret;
        } else {
            return false;
        }
    }

    public static function getSection($sid)
    {
        $ret = utility_services::call("/course/section/get/$sid");
        if (!empty($ret->data)) {
            return $ret->data;
        }

        return false;
    }

	/*
    private static $array_class = array(
        "user_class_id" => "user_class_id",
        "user_id"       => "user_id",
        "name"          => "name",
        "descript"      => "descript",
        "type"          => "type",
        "max_user"      => "max_user",
        "min_user"      => "min_user",
        "status"        => "status",
    );

    public static function addClass($cid, $cdata)
    {
        $params = new stdclass;
        foreach (self::$array_class as $key => $value) {
            $params->$key = $cdata["$value"];
        }
        //	$params->descript=$cdata["descript"];
        $ret = utility_services::call("/course/class/add/$cid", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }
	*/
	
	//新版建课程
	public static function addClass($courseId, $data){
		$params = new stdClass;
		$params->name      = $data["name"];
		$params->user_id   = $data['user_id'];	
		$params->maxUser   = !empty($data['max_user']) ? $data["max_user"] : 50;	
		$params->user_class_id = $data['user_class_id'];
		
		if(!empty($data['region_level0'])){
			$params->region_level0 = (int)$data['region_level0'];
		}
		if(!empty($data['region_level1'])){
			$params->region_level1 = (int)$data['region_level1'];
		}
		if(!empty($data['region_level2'])){
			$params->region_level2 = (int)$data['region_level2'];
		}
		if(!empty($data['address'])){
			$params->address = $data['address'];
		}
		$ret = utility_services::call("/course/class/add/$courseId", $params);
    
        if (!empty($ret->result) && $ret->code == 0) {
            return $ret->result->classId;
        }

        return false;
	}

    public static function updateClass($class_id, $cdata)
    {
        $params                = new stdclass;
		if(!empty($cdata['name'])){
			$params->name          = $cdata["name"];
		}
		if(!empty($cdata['user_class_id'])){
			$params->user_class_id = $cdata["user_class_id"];
		}
        $params->status        = empty($cdata["status"]) ? 1 : $cdata["status"];
        if (isset($cdata["descript"])) {
            $params->descript = $cdata["descript"];
        }
        if (isset($cdata["user_id"])) {
            $params->user_id = $cdata["user_id"];
        }
        if (isset($cdata["type"])) {
            $params->type = $cdata["type"];
        }
		if(!empty($cdata['max_user'])){
			$params->max_user = $cdata["max_user"];
		}
        if (isset($cdata["min_user"])) {
            $params->min_user = $cdata["min_user"];
        }
		//地点分类
        if (isset($cdata["region_level0"])) {
			$params->region_level0 = $cdata["region_level0"];
		}
		if (isset($cdata["region_level1"])) {
			$params->region_level1 = $cdata["region_level1"];
		}
		if (isset($cdata["region_level2"])) {
			$params->region_level2 = $cdata["region_level2"];
		}
		if (isset($cdata["address"])) {
			$params->address = $cdata["address"];
		}

        $ret = utility_services::call("/course/class/update/$class_id", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

	 public static function setClassProgress($classId, $data){
        $params = new stdclass;
		$params->progress_plan = $data['progress_plan'];
		if(isset($data['progress_percent'])){
			$params->progress_percent = $data['progress_percent'];
		}
        $ret = utility_services::call("/course/class/setProgress/$classId", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }
        return false;
    }
	
    public static function getClasslist($cid)
    {
        $ret = utility_services::call("/course/class/list/$cid");
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }
	public static function classListByCond($arrayin){
		$params = new stdclass;
		$array = array(
			"user_id"=>"user_id",
			"user_class_id"=>"user_class_id",
			"course_id"=>"course_id",
		);
		foreach($array as $k=>$v){
			if(!empty($arrayin[$v])){
				$params->$k = $arrayin[$v];
			}
		}
		$ret = utility_services::call("/course/class/listbycond/",$params);
		if (!empty($ret->data)) {
			return $ret;
		} else {
			return false;
		}
	}

	public static function classListByCourseIds($arrayin,$page=1,$length=10){
		$params = new stdclass;
		$params->cond = new stdclass;
		$array = array(
			"user_id"=>"user_id",
			"user_class_id"=>"user_class_id",
		);
		foreach($array as $k=>$v){
			if(!empty($arrayin[$v])){
				$params->$k = $arrayin[$v];
			}
		}
		$params->page = $page ;
		$params->length = $length ;
		if(!empty($arrayin["course_ids"])){
			$params->cond->course_ids = $arrayin["course_ids"];
		}
		$params->cond->st = !empty($arrayin["st"]) ? $arrayin["st"] : '';
		$params->cond->ut = isset($arrayin["ut"]) ? $arrayin["ut"] : '0';
		$params->cond->course_type = !empty($arrayin["course_type"]) ? $arrayin["course_type"] : '';
		$ret = utility_services::call("/course/class/classListByCourseIds/",$params);
		if (!empty($ret->data)) {
			return $ret;
		} else {
			return false;
		}
	}

	private static $array_plan = array(
        "user_id"           => "user_id",
        "user_plan_id"      => "user_plan_id",
        "course_id"         => "course_id",
        //	"descript"=>"descript",
        "section_id"        => "section_id",
        "class_id"          => "class_id",
        "start_time"        => "cstart_time",
		"end_time"          => "end_time",
        "live_public_type"  => "live_public_type",
        "video_public_type" => "video_public_type",
        "video_trial_time"  => "video_trial_time",
        "status"            => "status",
    );

    public static function addPlan($cid, $cdata)
    {
        $params = new stdclass;
        foreach (self::$array_plan as $key => $value) {
            if (isset($cdata[$value])) $params->$key = $cdata[$value];
        }

        $ret = utility_services::call("/course/plan/insert/$cid", $params);

        if(empty($ret->code)) {
            return $ret->result->planId;
        }
        return false;
    }

    public static function getPlan($plan_id)
    {
        $ret = utility_services::call("/course/plan/get/$plan_id");
        if(empty($ret->code)) {
            return $ret->result;
        }

        return false;
    }

    public static function getPlanuni($plan_data)
    {
        return false;
        /*
        $params = new stdclass;
        if (isset($plan_data["course_id"])) $params->course_id = $plan_data["course_id"];
        if (isset($plan_data["section_id"])) $params->section_id = $plan_data["section_id"];
        if (isset($plan_data["class_id"])) $params->class_id = $plan_data["class_id"];
        $ret = utility_services::call("/course/plan/getuni/", $params);
        if(empty($ret->code)) {
            return $ret->result;
        }

        return false;
         */
    }
    
    public static function updatePlanStatus($plan_id, $status)
    {
        $ret = utility_services::call("/course/plan/setStatus/$plan_id", array("status" => $status));
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

	/**
	 * 获取plan的状态，这个接口是从 主库 里获取，请尽量避免使用这个方法
	 * 推荐使用下面的方法 getPlanStatusV2()
    public static function getPlanStatus($plan_id)
    {
        $ret = utility_services::call("/course/plan/getStatus/$plan_id");
        if (!empty($ret->data)) {
            return $ret->data;
        }

        return false;
    }
	 **/
	/**
	 * 获取plan的状态，和上面方法一样，支持获取多个，参数可以是 int，或者 array
	 */
    public static function getPlanStatusV2($plan_ids)
    {
		$params=new stdclass;
		$params->plan_id = $plan_ids;
        $ret = utility_services::call("/course/plan/getStatusV2/",$params);
        if (!empty($ret->data)) {
            return $ret->data;
        }

        return false;
    }

    //该方法将要废弃
    public static function updatePlan($plan_id,$cdata)
    {
        $params = new stdclass;
        foreach (self::$array_plan as $key => $value) {
            if (isset($cdata[$value])) $params->$key = $cdata[$value];
        }
        $params->course_id = $cdata["course_id"];
        $params->plan_id = $plan_id;
        $ret               = utility_services::call("/course/plan/update/", $params);
        if(empty($ret->code)) {
            return true;
        }

        return false;
    }

    public static function updatePlanV2($planId, $data){
        if(empty($planId) || empty($data)) return false;

        $ret = utility_services::call("/course/plan/updateV2/".$planId, $data);
        if(empty($ret->code)) return true;

        return false;
    }

	public static function verifyPlan($user_id, $plan_id)
	{
		$params = new stdclass;
		$params->user_id = $user_id;
		$ret = utility_services::call("/course/plan/verify/$plan_id", $params);
		if (!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}
	public static function verifyPlanMulti($user_id, array $plan_ids)
	{
		$params = new stdclass;
		$params->user_id = $user_id;
		$params->plan_ids= $plan_ids;
		$ret = utility_services::call("/course/plan/verifyMulti/", $params);
		if (!empty($ret->data)){
			return $ret->data;
		}
		return false;
	}

    private static $list_plan_cond = array(
        "cid"           => "cid",
        "class_id"      => "class_id",
        "user_plan_id"  => "user_plan_id",
        "orgUserId"     => "orgUserId",
        "sid"           => "sid",
        "order_by"      => "order_by",
        "plan_id"       => "plan_id",
        "week"          => "week",
        "allcourse"     => "allcourse",
        "orgUserId"     => "orgUserId",
        "start_time"    => "start_time",
        "endstart_time" => "endstart_time",
        "status"        => "status",
        "type"          => "type",
        "video_id"      => "video_id",
        "totaltime"     => "totaltime",
		"startTime"     => "startTime",
        "resellOrgId"   => "resellOrgId",
        //""=>"",
    );

    public static function listPlan($plist)
    {
        $params = new stdclass;
        foreach (self::$list_plan_cond as $key => $value) {
            if (isset($plist[$value])) $params->$key = $plist[$value];
        }
        $page   = isset($plist['page']) ? $plist['page'] : 1;
        $length = isset($plist['length']) ? $plist['length'] : 200;
        //	$length = 100;
        $ret = utility_services::call("/course/plan/list/$page/$length", $params);
        if (!empty($ret->data)) {
            return $ret;
        }

        return false;
    }

    static private $array_reg = array(
        "cid"        => "cid",
        "course_id"  => "course_id",
        "class_id"   => "class_id",
        "uid"        => "uid",
        "user_owner" => "user_owner",
        "source"     => "source",
        "mobile"     => "mobile",
    );

    public static function addregistration($regdata)
    {
        $params = new stdclass;
        foreach (self::$array_reg as $k => $v) {
            if (isset($regdata[$v])) $params->$k = $regdata[$v];
        }
        $params->user_owner = $regdata["user_owner"];
        $params->class_id   = $regdata["class_id"];
        $params->status     = $regdata["status"];

        $ret                = utility_services::call("/course/info/addregistration/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            $course = array();
            $ret = utility_services::call("/course/info/get/$params->course_id");
            if(!empty($ret->data)){
                $course=$ret->data;
            }
            $class = utility_services::call("/course/class/get/$params->class_id");
            $studentId = empty($params->uid)?0:intval($params->uid);
            if(!empty($studentId)) {
                $studentInfo = user_api::getUser($studentId);
                $studentInfo->real_name = !empty($studentInfo->real_name) ? $studentInfo->real_name : $studentInfo->name;
            }
            $orgUserId = empty($course->user_id)?0:$course->user_id;
            $orgInfo = user_organization::getOrgByOwner($orgUserId);
            $smsData = new stdClass();
            $smsData->sms_type = 1;
            $smsData->course_name = $course->title;
            $smsData->mobile = !empty($studentInfo->mobile)?$studentInfo->mobile:0;
            $smsData->org_id = $orgInfo->oid;
            $smsData->org_name = !empty($orgInfo->subname)?$orgInfo->subname:(!empty($orgInfo->name)?$orgInfo->name:'');
            $smsData->course_fee = 2;
            $smsData->class_name = $class->name;
            if($course->fee_type==1){
                $smsData->course_fee = 1;
                //付费课程给班主任老师，机构管理员，学生发站内信
                // 	$params->uid 学生
                //	$headTeacher 班主任老师id
                //	$course['fk_user'] 机构userId
                if(!empty($studentId)){
                    $messageStudent =array(
                        'msgType'=>message_type::SIGN_COUSER,//报名信息
                        'userFrom'=>$course->user_id,
                        'userTo'=>$studentId,
                        'content'=>"【报名成功】恭喜你成功报名了课程：[".$course->title."]。",
                        'title'=>'报名成功',
                        'source'=>'20000',
                    );
                    $result=message_api::addDialog($messageStudent);
                }
                $headTeacher = empty($class->user_class_id)?0:$class->user_class_id;
                if(!empty($headTeacher)){
                    $messageTeacher =array(
                        'msgType'=>message_type::SIGN_COUSER,//报名信息
                        'userFrom'=>$course->user_id,
                        'userTo'=>$headTeacher,
                        'content'=>"【报名情况】".$studentInfo->real_name."（联系方式：".$studentInfo->mobile."）报名了[".$course->title."]".$class->name,
                        'title'=>'报名成功',
                        'source'=>'20000',
                    );
                    message_api::addDialog($messageTeacher);
                }
                $adminOrg 	=	array();
                $adminOrgs	= 	array();
                if($orgInfo){
                    $param = [
                        'condition' => "fk_org={$orgInfo->oid} and status=1 and user_role=5",
                        'item' => 'distinct(fk_user) as fk_user'
                    ];

                    $adminOrg = utility_services::call('/user/organizationUser/GetAdminList', $param);
                }
                if($adminOrg->items){
                    foreach($adminOrg->items as $v){
                        $adminOrgs[$v->fk_user] = $v->fk_user;
                    }
                }
                $adminOrgs[$course->user_id] = $course->user_id;
                if($adminOrgs){
                    foreach($adminOrgs as $adminUserId){
                        if($adminUserId == $headTeacher){
                            continue;
                        }
                        $messageTeacher =array(
                            'msgType'=>message_type::SIGN_COUSER,//报名信息
                            'userFrom'=>0,
                            'userTo'=>$adminUserId,
                            'content'=>"【报名情况】".$studentInfo->real_name."（联系方式：".$studentInfo->mobile."）报名了[".$course->title."]".$class->name,
                            'title'=>'报名成功',
                            'source'=>'20000',
                        );
                        message_api::addDialog($messageTeacher);
                    }

                }

            }
            sms_api::SmsSend($smsData);
            return true;
        }
        SLog::fatal(
            'add registration failed result[%s], params[%s]',
            var_export($ret, 1),
            var_export($regdata, 1)
        );

        return false;
    }

    public static function updateRegClass($course_user_id, $updata)
    {
        $params                 = new stdclass;
        $params->course_user_id = $course_user_id;
        $params->class_id       = $updata["class_id"];
        $params->course_id      = $updata["course_id"];
        $params->old_class_id   = $updata["old_class_id"];
        $params->old_course_id  = $updata["old_course_id"];
        $ret                    = utility_services::call("/course/info/updateregclass/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    public static function updateRegCount($updata)
    {
        $params                = new stdclass;
        $params->old_class_id  = $updata["old_class_id"];
        $params->old_course_id = $updata["old_course_id"];
        $params->new_class_id  = $updata["new_class_id"];
        $params->new_course_id = $updata["new_course_id"];
        $ret                   = utility_services::call("/course/info/updateregcount/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return $ret;
        }

        return false;
    }

	//没有分页
    public static function listRegistrationBycond($regdata,$page=null,$length=null){
        $params = new stdclass;
		if(empty($page)){
			$page = 0;
		}
		if(empty($length)){
			$length = 0;
		}
		$array_reg_data = array(
			"course_ids" => "course_ids",
			"class_id"  => "class_id",
			"uids"       => "uids",
			"user_owner" => "user_owner",
		);
		foreach ($array_reg_data as $k => $v) {
            if (isset($regdata[$v])) $params->$k = $regdata[$v];
        }
		//print_r($params);
        $ret = utility_services::call("/course/info/listregistrationbycond/$page/$length", $params);
        if (!empty($ret->data)) {
            //	return $ret->data;
            return $ret;
        } else {
            return false;
        }
    }
	
	 public static function listRegistration($regdata, $page = 1, $size = 0)
    {
        $params = new stdclass;
        foreach (self::$array_reg as $k => $v) {
            if (isset($regdata[$v])) $params->$k = $regdata[$v];
        }
        $ret = utility_services::call("/course/info/listRegistration/$page/$size", $params);
        if (!empty($ret->data)) {
            return $ret;
        } else {
            return false;
        }
    }


    public static function listPlanUser($plan_id)
    {
        $ret = utility_services::call("/course/info/planuser/$plan_id");
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static $fee_info_array = array(
        "user_id"   => "user_id",
        "course_id" => "course_id",
        "order_id"  => "order_id",
		"owner_id"  => "owner_id",
        "status"    => "status",
        "price"     => "price"
    );
    

    public static $fee_status_array = array(
        "order_id" => "order_id",
        "status"   => "status",
    );
	
    public static function genPayUrl($order_info, $course_info)
    {
        require_once(ROOT_LIBS."/alipay/alipay.config.php");
        require_once(ROOT_LIBS."/alipay/lib/alipay_submit.class.php");
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $sc = "http";
        if (utility_net::isHTTPS()) {
            $sc = "https";
        }
        $notify_url = $sc."://".$_SERVER['HTTP_HOST']."/course.buy.notify";

        //页面跳转同步通知页面路径
        $return_url = $sc."://".$_SERVER['HTTP_HOST']."/course.buy.return";

        //卖家支付宝帐户
        $seller_email = "gn100@talkweb.com.cn";
        //必填

        //商户订单号
        //$out_trade_no = $order_info->unique_order_id;
        $out_trade_no = $order_info->out_trade_id;
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $course_info->title;
        //必填

        //付款金额
        $total_fee = $order_info->price;
        //必填

        //订单描述

        $body = "";//mb_substr(strip_tags($course_info->descript),0,30,"utf8");
        //默认支付方式
        $paymethod = "bankPay";
        //必填
        //默认网银
        $defaultbank = "CMB";
        //必填，银行简码请参考接口技术文档

        //商品展示地址
        //$show_url = $sc."://www.gn100.com/course.info.show/3";
        $show_url = $sc."://".$_SERVER['HTTP_HOST']."/course.list/";
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service"           => "create_direct_pay_by_user",
            "partner"           => trim($alipay_config['partner']),
            "payment_type"      => $payment_type,
            "notify_url"        => $notify_url,
            "return_url"        => $return_url,
            "seller_email"      => $seller_email,
            "out_trade_no"      => $out_trade_no,
            "subject"           => $subject,
            "total_fee"         => $total_fee,
            "body"              => $body,
            "paymethod"         => $paymethod,
            "defaultbank"       => $defaultbank,
            "show_url"          => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip"   => $exter_invoke_ip,
            "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);

        return $alipaySubmit->alipay_gateway_new.$alipaySubmit->buildRequestParaToString($parameter, "get", "确认");
    }

    /**
     * 微信扫码支付
     */
    public static function genWeixinPayQrcodeUrl($order_info)
    {
        return weixin_api::getQrcodeUrl($order_info->out_trade_id);
    }

    public static function createDiscount($data, $uid)
    {
        $params                 = new stdclass;
        $params->user_id        = $uid;
        $params->name           = $data["name"];
        $params->introduction   = $data["introduction"];
        $params->course_id      = $data["course_id"];
        $params->discount_type  = (int)($data["discount_type"]);
        $params->discount_value = floatval($data["discount_value"]);
        $params->min_fee        = floatval($data["min_fee"]);
        $params->starttime      = $data["starttime"];
        $params->endtime        = $data["endtime"];
        $ret                    = utility_services::call("/course/discount/create", $params);

        return $ret;
    }

    public static function listDiscount($data, $uid)
    {
        $params          = new stdclass;
        $params->user_id = $uid;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listbyorg", $params);

        return $ret;
    }

    public static function forbidDiscount($uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        $ret                 = utility_services::call("/course/discount/forbid", $params);

        return $ret;
    }

    public static function recoverDiscount($uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        $ret                 = utility_services::call("/course/discount/recover", $params);

        return $ret;
    }

    public static function listDiscountCode($data, $uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listcodebydiscountid", $params);

        return $ret;
    }

    public static function createDiscountCode($data, $uid)
    {
        $params               = new stdclass;
        $params->user_id      = $uid;
        $params->introduction = $data["introduction"];
        $params->discount_id  = $data["discount_id"];
        $params->total_num    = (int)($data["total_num"]);
        $params->user_limit   = (int)($data["user_limit"]);
        $params->starttime    = $data["starttime"];
        $params->endtime      = $data["endtime"];
        $ret                  = utility_services::call("/course/discount/createcode", $params);

        return $ret;
    }

    public static function forbidDiscountCode($uid, $discount_code_id)
    {
        $params                   = new stdclass;
        $params->user_id          = $uid;
        $params->discount_code_id = $discount_code_id;
        $ret                      = utility_services::call("/course/discount/forbiddiscountcode", $params);

        return $ret;
    }

    public static function recoverDiscountCode($uid, $discount_code_id)
    {
        $params                   = new stdclass;
        $params->user_id          = $uid;
        $params->discount_code_id = $discount_code_id;
        $ret                      = utility_services::call("/course/discount/recoverdiscountcode", $params);

        return $ret;
    }

	/*
	 * (t_discount_code_used)
	 */
	public static function listDiscountCodeUid($data, $uid)
    {
        $params          = new stdclass;
        $params->user_id = $uid;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listcodeusedbyuid", $params);

        return $ret;
    }

	/*
	 * (t_discount_code)
	 */
	public static function listDisCodeIds($data,$codeIds)
    {
		$params = new stdclass;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
		$params->pk_discount_code = $codeIds;
        $ret = utility_services::call("/course/discount/listDisInId/",$params);
        return $ret;
    }

	/*
	 * (t_discount)
	 */
	public static function listDisIds($disIds)
    {
		$params = new stdclass;
		$params->fk_discount = $disIds;
        $ret = utility_services::call("/course/discount/ListDiscountInId/",$params);
        return $ret;
    }


    public static function listDiscountCodeUsed($data, $uid, $discount_code)
    {
        $params                = new stdclass;
        $params->user_id       = $uid;
        $params->discount_code = $discount_code;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listcodeusedbycodeid", $params);

        return $ret;
    }

    public static function getDiscountCodeUsed($order_id)
    {
        $params           = new stdclass;
        $params->order_id = $order_id;
        $ret              = utility_services::call("/course/discount/getdiscountcodeused", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function useDiscountCode($discount_code, $user_id, $unique_order_id)
    {
        $params                  = new stdclass;
        $params->discount_code   = $discount_code;
        $params->user_id         = $user_id;
        $params->unique_order_id = $unique_order_id;
        $ret                     = utility_services::call("/course/discount/usediscountcode", $params);

        return $ret;
    }

    public static function getCoursesByOrg($user_id)
    {
        $params          = new stdclass;
        $params->user_id = $user_id;
        $ret             = utility_services::call("/course/discount/getcoursesbyorg", $params);

        return $ret;
    }

    public static function getFeeCoursesByOrg($user_id)
    {
        $params          = new stdclass;
        $params->user_id = $user_id;
        $ret             = utility_services::call("/course/discount/getfeecoursesbyorg", $params);

        return $ret;
    }

    public static function getCourseVideo($plan_id)
    {
        $params          = new stdclass;
        $params->plan_id = $plan_id;
        $ret             = utility_services::call("/course/video/get/", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

	public static function setCourseVideoV2($video_id, $data)
    {
        $params = new stdclass;
		if(!empty($data['segs'])){
			$params->segs = $data['segs'];
		}
        if(!empty($data['totalTime'])){
			$params->segs_totaltime = $data['totalTime'];
		}
		if(!empty($data['thumb0'])){
			$params->thumb0 = $data['thumb0'];
		}

        $ret = utility_services::call("/course/video/update/$video_id", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function confirmDiscountCode($order_id)
    {
        $params           = new stdclass;
        $params->order_id = $order_id;
        $ret              = utility_services::call("/course/discount/confirmdiscountcode", $params);

        return $ret;
    }

    public static function cancelDiscountCode($order_id)
    {
        $params           = new stdclass;
        $params->order_id = $order_id;
        $ret              = utility_services::call("/course/discount/canceldiscountcode", $params);

        return $ret;
    }

    public static function getUsedsByCodeIdUserId($code, $user_id, $page = 1, $limit = 0)
    {
        $params          = new stdclass;
        $params->code    = $code;
        $params->user_id = $user_id;
        $params->page    = $page;
        $params->limit   = $limit;
        $ret             = utility_services::call("/course/mydiscount/getdiscountcodeusedsbycodeiduserid", $params);

        return $ret;
    }

    public static function getUsedsByUserId($user_id, $statuses, $page = 1, $limit = 0)
    {
        $params           = new stdclass;
        $params->user_id  = $user_id;
        $params->statuses = $statuses;
        $params->page     = $page;
        $params->limit    = $limit;
        $ret              = utility_services::call("/course/mydiscount/getdiscountcodeusedsbyuserid", $params);

        return $ret;
    }

    public static function countStudent($cids)
    {
        $ret = utility_services::call("/course/info/countStudent", array('cids' => $cids));

        return $ret;

    }

    public static function getStudentsByCid($cid)
    {
        $ret = utility_services::call("/course/info/getStudentsByCid/".$cid);

        return $ret;

    }

    public static function addCourseTop($cid)
    {
        $ret = utility_services::call("/course/info/addCourseTop/".$cid);

        return $ret;

    }

    public static function delCourseTop($cid)
    {
        $ret = utility_services::call("/course/info/delCourseTop/".$cid);

        return $ret;

    }

    public static function countPlanByOwner($org_owner, $status, $start_time = 0, $end_time = 0)
    {
        $params             = new stdCLass;
        $params->status     = $status;
        $params->start_time = $start_time;
        $params->end_time   = $end_time;
        $ret                = utility_services::call("/course/info/countPlanByOwner/$org_owner", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }

        return 0;
    }

    public static function countStudentByOwner($org_owner, $status, $start_time = 0, $end_time = 0)
    {
        $params             = new stdCLass;
        $params->status     = $status;
        $params->start_time = $start_time;
        $params->end_time   = $end_time;
        $ret                = utility_services::call("/course/info/countStudentByOwner/$org_owner", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }

        return 0;
    }

    public static function getPlanListByOwner($org_owner, $status, $start_time = 0, $end_time = 0)
    {
        $params             = new stdCLass;
        $params->status     = $status;
        $params->start_time = $start_time;
        $params->end_time   = $end_time;
        $ret                = utility_services::call("/course/plan/getPlanListByOwner/$org_owner", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        }

        return false;
    }

    public static function getOrgStudentList($param)
    {
        return utility_services::call('/course/student/list', $param);
    }

	public static function getStudentByOwnerId($ownerId){
        $ret = utility_services::call('/course/student/getStudentByOwnerId/'.$ownerId);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
    }
	
    public static function searchUserData($param)
    {
        return utility_services::call('/course/student/SearchUserData', $param);
    }

    public static function getCourseByCids($courseIdArr,$userOwnerId=0)
    {
		$params = new stdClass;
		if(!empty($courseIdArr)){
			$params->courseIdArr = $courseIdArr;
		}
		if(!empty($userOwnerId)){
			$params->userOwnerId = $userOwnerId;
		}
        $ret = utility_services::call("/course/info/getCourseByCids/", $params);
        if (!empty($ret->result)) {
            return $ret->result;
        }

        return false;
    }

    public static function countStudentByClassIds($params)
    {
        $ret = utility_services::call("/course/info/countStudentByClassIds", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        }

        return false;

    }

    /*
     * 为排课增加一条题目信息
     *
     */
    public static function addCoursePlanExam($data)
    {
        $params  = new stdclass;
        $arrkeys = array(
            "plan_id"     => "plan_id",
            "question_id" => "question_id",
            "type"        => "type",
            "q_desc"      => "q_desc",
            "q_desc_img"  => "q_desc_img",
            "a"           => "a",
            "b"           => "b",
            "c"           => "c",
            "d"           => "d",
            "e"           => "e",
            "answer_a_id" => "answer_a_id",
            "answer_b_id" => "answer_b_id",
            "answer_c_id" => "answer_c_id",
            "answer_d_id" => "answer_d_id",
            "answer_e_id" => "answer_e_id",
            "answer"      => "answer",
            "order_no"    => "order_no",
            "status"      => "status",
        );
        foreach ($arrkeys as $arrk => $arrv) {
            if (isset($data[$arrk])) {
                $params->$arrv = $data[$arrv];
            }
        }
        $ret = utility_services::call("/course/exam/add/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    public static function updateCoursePlanExam($examid, $data)
    {
        $params  = new stdclass;
        $arrkeys = array(
            "q_desc"      => "q_desc",
            "q_desc_img"  => "q_desc_img",
            "a"           => "a",
            "b"           => "b",
            "c"           => "c",
            "d"           => "d",
            "e"           => "e",
            "answer_a_id" => "answer_a_id",
            "answer_b_id" => "answer_b_id",
            "answer_c_id" => "answer_c_id",
            "answer_d_id" => "answer_d_id",
            "answer_e_id" => "answer_e_id",
            "answer"      => "answer",
            "order_no"    => "order_no",
            "status"      => "status",
        );
        foreach ($arrkeys as $arrk => $arrv) {
            if (isset($data[$arrv])) {
                $params->$arrv = $data[$arrv];
            }
        }
        $ret = utility_services::call("/course/exam/update/$examid", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    /*
     * 列取该plan下的题目信息
     *
     */
    public static function coursePlanExamList($data, $page = 0, $length = 0)
    {
        if (empty($length)) {
            $length = 1000;
        }
        if (empty($page)) {
            $page = 1;
        }
        $params          = new stdclass;
        $params->orderby = new stdclass;
        $params->plan_id = $data["plan_id"];
        if (isset($data["orderby"]["order_no"])) {
            $params->orderby->order_no = $data["orderby"]["order_no"];
        }
        $ret = utility_services::call("/course/exam/list/$page/$length", $params);
        if (!empty($ret->data)) {
            return $ret;
        }

        return false;
    }

    /*
     * 删除一条或多条题目信息
     * $data = array(1,2,3,4); 1 2 3 4 为题目id
     */
    public static function courseDelPlanExam($data)
    {
        $params = new stdclass;
        if (empty($data)) {
            return false;
        }
        $params->ids = $data;
        $ret         = utility_services::call("/course/exam/del/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    public static function createDiscountV2($data, $uid)
    {
        $params                 = new stdclass;
        $params->user_id        = $uid;
        $params->name           = $data["discount_name"];
        $params->course_id      = $data["course_id"];
        $params->discount_type  = (int)($data["discount_type"]);
        $params->discount_value = floatval($data["discount_value"]);
        $params->min_fee        = floatval($data["min_fee"]);
        $params->total_num      = (int)($data["total_num"]);
        $params->user_limit     = (int)($data["user_limit"]);
        $params->duration       = (int)($data["duration"]);
        if (!empty($data["create_code"])) {
            $params->create_code = 1;
        }
        $ret = utility_services::call("/course/discount/createv2", $params);

        return $ret;
    }
	


    public static function listDiscountV2($data, $uid)
    {
        $params          = new stdclass;
        $params->user_id = $uid;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listbyorgv2", $params);

        return $ret;
    }

    public static function listDiscountCodeV2($data, $uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listcodebydiscountidv2", $params);

        return $ret;
    }

    public static function listDiscountCodeUsedV2($data, $uid, $discountcode)
    {
        $params          = new stdclass;
        $params->user_id = $uid;
        $params->code    = $discountcode;
        if (!empty($data["limit"])) {
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"])) {
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/listcodeusedv2", $params);

        return $ret;
    }
	/*
	 * 通过用户id查找用户优惠券
	 */
	public static function listUserByCode($data)
    {
        $params          = new stdclass;
        $params->user_id = $data['user_id'];
        if (!empty($data["limit"]))
		{
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"]))
		{
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/ListUserByCode", $params);

        return $ret;
    }

	public static function ListDiscountByIds($data)
    {
        $params          = new stdclass;
        $params->dis_ids = $data['dis_ids'];
		if(!empty($data['owner']))
		{
			$params->owner = $data['owner'];
		}
        if (!empty($data["limit"]))
		{
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"]))
		{
            $params->page = $data["page"];
        }
		if (!empty($data['code']))
		{
			$params->code = $data['code'];
		}
        $ret = utility_services::call("/course/discount/ListDiscountByIds", $params);

        return $ret;
    }

	public static function getDiscountByIds($data)
    {
        $params          = new stdclass;
        $params->discountid = $data['discountid'];
        if (!empty($data["limit"]))
		{
            $params->limit = $data["limit"];
        }
        if (!empty($data["page"]))
		{
            $params->page = $data["page"];
        }
        $ret = utility_services::call("/course/discount/getDiscountByIds", $params);

        return $ret;
    }


    public static function createDiscountCodeV2($num, $uid, $discount_id)
    {
        $params              = new stdclass;
        $params->user_id     = $uid;
        $params->discount_id = $discount_id;
        $params->num         = (int)($num);
        $ret                 = utility_services::call("/course/discount/createcodev2", $params);

        return $ret;
    }

    public static function getClassByClassIdArr($class_id_arr)
    {

        $ret = utility_services::call("/course/class/getClassByClassIdArr", $class_id_arr);

        return $ret;
    }

    public static function addPlanAttach($planId, $data)
    {
        $params = new stdclass;
        $attArr = array(
            "title"    => "title",
            "order_no" => "order_no",
            "type"     => "type",
            "thumb"    => "thumb",
            "fk_user"  => "fk_user",
            "fk_plan"  => "fk_plan"
        );
        foreach ($attArr as $k => $v) {
            if (isset($data[$k])) {
                $params->$k = $data[$k];
            }
        }
        if (!isset($data["attach"])) {
            return false;
        }
        $params->attach = $data["attach"];
        $ret            = utility_services::call("/course/planattach/add/$planId", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return $ret;
        }

        return false;
    }

    /*
        public static function delPlanAttach($planId,$planAttId){
            $ret = utility_services::call("/course/planattach/del/$planAttId/$planId");
            if(!empty($ret->result) && $ret->result->code==0){
                return $ret;
            }else{
                return false;
            }
        }
    */
    public static function delPlanAttach($data)
    {
        $params = new stdclass;
        if (empty($data)) {
            return false;
        }
        $params->planAttIds = $data;
        $ret                = utility_services::call("/course/planattach/del/", $params);
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

	/*
    public static function listPlanAttach($plan_id,$page=0,$length=0)
    {
        $planId = (int)$plan_id;
        $ret    = utility_services::call("/course/planattach/list/".$planId."/".$page."/"."/".$length);
        if (!empty($ret->data)) {
            return $ret;
        } else {
            return false;
        }
    }
	*/
	
	/**
	 * @desc 获取课件列表(支持班级/排课/用户的课件)
	 */
	public static function listPlanAttach($data, $page=1, $length=100){
		$params = new stdCLass();
		if(!empty($data['classId'])){
			$params->classId = (int)$data['classId'];
		}
		if(!empty($data['planId'])){
			$params->planId = (int)$data['planId'];
		}
		if(!empty($data['userId'])){
			$params->userId = (int)$data['userId'];
		}
		
		$ret = utility_services::call("/course/planattach/list/".$page."/".$length, $params);
		if(empty($ret->code)){
			return $ret->result;
		}
		
		return false;
	}

    public static function getTopCourseByOwner($uid)
    {
        $ret = utility_services::call("/course/info/getTopCourseByOwner/".$uid);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function setCourse($cid, $params)
    {
        $ret = utility_services::call("/course/info/setCourse/".$cid, $params);

        return $ret;

    }

    public static function delHistoryTopCourse($uid, $top)
    {
        $ret = utility_services::call("/course/info/delHistoryTopCourse/".$uid, array('top' => $top));
        if (!empty($ret->result) && $ret->result->code == 0) {
            return true;
        }

        return false;
    }

    public static function getPlanExamsByPlan($plan_id, $user_id)
    {
        if (empty($plan_id) || empty($user_id)) {
            return false;
        }
        $params          = new stdclass;
        $params->plan_id = $plan_id;
        $params->user_id = $user_id;
        $ret             = utility_services::call("/course/exam/getplanexamsbyplan", $params);
        if (!empty($ret->data)) {
            return $ret->data;
        } else {
            return false;
        }
    }

    public static function updatePlanExamStatus($data, $user_id)
    {
        if (empty($data) || empty($user_id) || empty($data["plan_id"]) || empty($data["plan_exam_id"]) || empty($data["status"])) {
            return false;
        }
        $params               = new stdclass;
        $params->plan_id      = $data["plan_id"];
        $params->plan_exam_id = $data["plan_exam_id"];
        $params->status       = $data["status"];
        $params->user_id      = $user_id;
        $ret                  = utility_services::call("/course/exam/updateplanexamstatus", $params);
        if (0 == $ret->result->code) {
            return true;
        } else {
            return false;
        }
    }

    public static function updateUserClass($params)
    {
        $ret = utility_services::call("/course/info/UpdateUserClass", $params);

        return $ret->code ? false : true;
    }

    public static function checkIsRegistration($uid, $cid)
    {
        if (!(int)($uid) || !(int)($cid)) {
            return false;
        }

        $params  = ['uid' => $uid, 'courseId' => $cid];
        $regInfo = utility_services::call('/course/info/CheckUserIsReg', $params);

        if ($regInfo->code) return false;

        return [
            'courseUserId' => $regInfo->result->pk_course_user,
            'courseId'     => $regInfo->result->fk_course,
            'userId'       => $regInfo->result->fk_user,
            'userOwnerId'  => $regInfo->result->fk_user_owner,
            'classId'      => $regInfo->result->fk_class,
            'className'    => $regInfo->result->class_name,
            'status'       => $regInfo->result->status,
            'source'       => $regInfo->result->source,
            'expire_time'  => $regInfo->result->expire_time
        ];
    }

	//检查用户能否报名
	public static function checkUserCanRegistration($userId,$courseId,$classId){
		if(empty($classId) || empty($userId) || empty($courseId)){
			return false;
		}
		$classInfo = self::getclass($classId);
        if (empty($classInfo)){
			return false;
		}
		if ($classInfo->course_id != $courseId || $classInfo->status == 'invalid') {
            return false;
        }	
		$setIdArr  = self::getCourseOpenMemberSetIdArr($courseId,1);
		$memberRet = org_member_api::checkIsMemberOrExpire1($userId, $setIdArr, $courseId);
		if(isset($memberRet['regId'])){
			if($memberRet['isMemberRegType'] == 0){
				return false;
			}elseif($memberRet['isMemberRegType'] == 1){
				if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
					return false;
				}
			}
		}
		if(empty($memberRet['regId'])){
			if($classInfo->user_total >= $classInfo->max_user){
				return false;
			}	
		}
		return true;
	}

    public static function getScoreInfo($cid)
    {
        $res = comment_api::getTotal(array('course_id' => $cid));
        if (!empty($res)) {
            $student_score = (int)ceil($res[0]->student_score / $res[0]->total_user);
            $desc_score    = (int)ceil($res[0]->desc_score / $res[0]->total_user);
            $explain_score = (int)ceil($res[0]->explain_score / $res[0]->total_user);
            $avg_score     = (int)ceil($res[0]->avg_score / $res[0]->total_user);

            return array(
                'student_score' => $student_score > 5 ? 5 : $student_score,
                'desc_score'    => $desc_score > 5 ? 5 : $desc_score,
                'explain_score' => $explain_score > 5 ? 5 : $explain_score,
                'avg_score'     => $avg_score > 5 ? 5 : $avg_score
            );
        }

        return array();
    }

	public static function getOrgCourseCount($uidArr){
        $url = '/course/info/getOrgCourseCount';
        return utility_services::call($url,$uidArr);
	}

	public static function getCourseListByParams($page,$length,$params){
        $ret=utility_services::call('/course/info/getCourseListByParams/'.$page.'/'.$length,$params);
        return !empty($ret->data) ? $ret->data : false;
	}

	public static function checkUserRegisterCourse($course_id,$class_id,$uid,$owner_id=0){
		$params = new stdclass;
		$params->course_id = $course_id;
		$params->class_id = $class_id;
		$params->uid = $uid;
		$params->owner_id = $owner_id;
		$url = '/course/courseuser/checkUserRegisterCourse';
        return utility_services::call($url,$params);
	}

	public static function getPlanQuestionCountByPidArr($pid_arr){
        return utility_services::call('/course/exam/getPlanQuestionCountByPidArr', $pid_arr);
	}

	public static function getPlanQuestionByPid($pid){
        return utility_services::call('/course/exam/getPlanQuestionByPid', $pid);
	}

	public static function getPlanAttachByPidArr($pid_arr){
        return utility_services::call('/course/planattach/getPlanAttachByPidArr', $pid_arr);
	}

	public static function getPlanByPid($pid){
        $res = utility_services::call('/course/plan/get/'.$pid);
        if(empty($res->code)){
            return $res->result;
        }
        return false;
	}

	public static function getstudentCourse($uid)
	{
        return utility_services::call('/course/student/getstudentCourse/'.$uid);
	}
    public static function getCourseByPlan($plan_id){
        $params = new stdclass;
        $params->plan_id = $plan_id;
        $ret = utility_services::call("/course/plan/getcoursebyplan", $params);
        return $ret;
    }
	
	public static function getClassPlan($class_id_arr){	
		$ret = utility_services::call("/course/plan/getClassPlan", $class_id_arr);
		if(!empty($ret->data)){
			return $ret->data;
		}else{
			return false;
		}
	}
	
	public static function getCateByLevel($level){	
    	$url = '/course/cate/getCateByLevel/'.$level;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getCateByCateId($cateId){	
    	$url = '/course/cate/getCateByCid/'.$cateId;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getCateByCidStr($cateIdStr){	
		$params = new stdCLass;
		$params->cateIdStr = $cateIdStr;
    	$url = '/course/cate/getCateByCidStr/';
        $ret = utility_services::call($url,$params);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getCateList(){	
    	$url = '/course/cate/list/';
        $ret = utility_services::call($url);
		if(!empty($ret->result->items)){
			return $ret->result->items;
		}else{
			return false;
		}
	}
	
	public static function getNodeCate($cateId){	
    	$url = '/course/cate/getNodeCate/'.$cateId;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getAttrAndValueByCateId($cateId){	
    	$url = '/course/attr/getAttrAndValueByCateId/'.$cateId;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getAttrValueByAttrId($attrId){	
    	$url = '/course/attrValue/getAttrValueByAttrId/'.$attrId;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
	public static function exportOrgOfstudentData($param){
        return utility_services::call('/course/student/exportOfStudentData', $param);
    }
	public static function getCourseAttrValueByCourseId($courseId){	
    	$url = '/course/attrValue/getCourseAttrValueByCourseId/'.$courseId;
        $ret = utility_services::call($url);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}
    /*
     * 查询机构课程数量
     * @param $owner,$status,$startTime,$endTime
     * @return int
     * @author Panda <zhangtaifeng@gn100.com>	
     */
    public static function countCourseByOwner($owner, $status, $startTime = 0, $endTime = 0){
        $params= new stdCLass;
        $params->status=$status;
        $params->start_time=$startTime;
        $params->end_time=$endTime;
        $ret=utility_services::call("/course/organization/countCourseByOwner/$owner", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }
        return 0;
    }
	  /*查询教师进行中的课程数量
     * @param $uid
     * @return int
     * @author ljj
     */
    public static function getTeacherOngoingCourseCount($uid){
        $ret=utility_services::call("/course/teacher/countTeacherOngoingCourseByUid/$uid");
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return 0;
    }
    /*查询教师排课数
     * @param $uid,$data
     * @return int
     * @author ljj
     */
    public static function getTeacherPlanCountByUid($uid,$data){
        $ret=utility_services::call("/course/teacher/getTeacherPlanCountByUid/$uid", $data);
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return 0;
    }
    /*查询学生课程数量
     * @param $owner,$uid,status
     * @return int
     * @author Panda <zhangtaifeng@gn100.com>
     */
    public static function countstudentCourseByUid($owner,$uid,$status=1){
        $params= new stdCLass;
        $params->owner=$owner;
        $params->status=$status;
        $ret=utility_services::call("/course/student/countstudentCourseByUid/$uid", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }
        return 0;
    }
    /*查询学生排课数量
     * @param $owner,$uid,status,$startTime,$endTime
     * @return int
     * @author Panda <zhangtaifeng@gn100.com>
     */
    public static function countstudentPlanByUid($owner,$uid,$status=1,$startTime=0,$endTime=0){
        $params= new stdCLass;
        $params->owner=$owner;
        $params->status=$status;
        $params->start_time=$startTime;
        $params->end_time=$endTime;
        $ret=utility_services::call("/course/student/countstudentPlanByUid/$uid", $params);
        if (!empty($ret->data)) {
            return $ret->data->count;
        }
        return 0;
    }
	
	public static function checkCourseByFirstCateArr($firstCateArr,$ownerId='',$courseIds=''){
		$ret=utility_services::call("/course/cate/checkCourseByFirstCateArr/".$ownerId."/".$courseIds, $firstCateArr);
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return false;
	}
	
	public static function checkCourseBySecondCateArr($secondCateArr,$ownerId='',$courseIds=''){
		$ret=utility_services::call("/course/cate/checkCourseBySecondCateArr/".$ownerId."/".$courseIds, $secondCateArr);
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return false;
	}
	
	public static function checkCourseByThirdCateArr($thirdCateArr,$ownerId='',$courseIds=''){
		$ret=utility_services::call("/course/cate/checkCourseByThirdCateArr/".$ownerId."/".$courseIds, $thirdCateArr);
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return false;
	}
	
	public static function checkMappingCourseByAttrValueIdArr($attrValueIdArr,$ownerId=''){
		$ret=utility_services::call("/course/attrValue/checkMappingCourseByAttrValueIdArr/".$ownerId, $attrValueIdArr);
        if (!empty($ret->result)) {
            return $ret->result;
        }
        return false;
	}
	
	public static function recommendByCateId($cateId){
		$params = new stdclass;
		$params->cateId = $cateId;
        $ret = utility_services::call("/course/recommend/recommendByCateId/",$params);
		if(empty($ret->code)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getUserCourseCount($userId,$ownerId=0){
		$params = new stdClass;
		$params->uid = $userId;
		$params->owner_id = $ownerId;
		$ret = utility_services::call("/course/courseuser/getUserCourseCount", $params);
		if($ret->code == 0){
			return $ret->result;
		}else{
			return 0;
		}
	} 
	
	public static function getUserLivingCourse($userId,$type,$startTime,$ownerId=0){
		$params = new stdClass;
		$params->uid = $userId;
		$params->owner_id = $ownerId;
		$params->type = $type;
		$params->start_time = $startTime;
		$ret = utility_services::call("/course/courseuser/getUserLivingCourse", $params);
		if($ret->code == 0){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getUserRegisterCourseList($userId,$page,$length,$ownerId=0,$search='',$type=0){
		$params = new stdClass;
		$params->uid = $userId;
		$params->owner_id = $ownerId;
		$params->page = $page;
		$params->length = $length;
		$params->title = $search;
		$params->courseType = $type;
		$ret = utility_services::call("/course/courseuser/getUserRegisterCourseList", $params);
		if($ret->code == 0){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getAllAttrValue(){
		$ret = utility_services::call("/course/attr/getAllAttrValue");
		if($ret->code == 0){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getDiscountCodeByOrder($orderIds){
		$params = new stdClass;
		$params->orderId = $orderIds;
		$ret = utility_services::call("/course/discount/getDiscountCodeByOrder",$params);
		if(empty($ret->code)){
			return $ret->result;
		}else{
			return false;
		}
	}
	
	public static function getPhrase($data){
		$params = new stdClass;
		if(!empty($data['phraseId'])){
			$params->phraseId = $data['phraseId'];
		}
		if(!empty($data['type'])){
			$params->type = $data['type'];
		}
		$ret = utility_services::call("/course/exam/getPhrase",$params);
		return $ret;
	}
	
	public static function addPlanPhrase($params){
		
		$ret = utility_services::call("/course/exam/addPlanPhrase",$params);
		return $ret;
	}
	
	public static function getAttrValueByVidArr($vidArr){	
    	$url = '/course/attrValue/getAttrValueByVidArr/';
        $ret = utility_services::call($url,$vidArr);
		if(!empty($ret->result)){
			return $ret->result;
		}else{
			return false;
		}
	}

    public static function getOrgIdByCourseId($cid)
    {
        $res = utility_services::call('/course/info/GetOrgIdByCourseId/'.$cid);
        if (!empty($res->code)) return 0;

        return $res->result->orgId;
    }
	
	public static function getClassAndCourseList($params,$page='',$length='')
    {
        $res = utility_services::call("/course/class/getClassAndCourseList/".$page."/".$length,$params);
        return $res;
    }
	
	public static function getCourseOpenMemberSetIdArr($courseId){
		$courseMember = user_organization::getMemberPriorityByObjectId($courseId,1);
		$setIdArr = array();
		if(!empty($courseMember)){
			foreach($courseMember as $mo){
				if($mo->status == 1){
					$setIdArr[] = $mo->fk_member_set;
				}
			}
		}
		return $setIdArr;
	}
	
	public static function checkUserCourseIsExpire($userId,$courseId){
		$setIdArr = self::getCourseOpenMemberSetIdArr($courseId);
		$memberRet = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);
		if($memberRet['isMemberRegType'] == 1){
			if($memberRet['isMember'] == 1 && $memberRet['isExpire'] == 0){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	public static function delCourseTeacher($data){
		$params = new stdCLass;
		$params->courseId  = $data['courseId'];
		$params->teacherId = $data['teacherId'];
		
		$res = utility_services::call('/course/info/delCourseTeacher/', $params);
        if (!empty($res->code)) return false;

        return true;
	}

    public static function delTeacherByCourseId($courseId){
        $res = utility_services::call('/course/info/delByCourseId/'.$courseId);
        if (!empty($res->code)) return false;
        return true;
    }
	
	public static function addCourseTeacher($data){
		$params = new stdCLass;
		$params->courseId  = $data['courseId'];
		$params->teacherId = $data['teacherId'];
        $params->isTeacher = $data['isTeacher'];
        $params->isAssistan= $data['isAssistan'];
		
		$res = utility_services::call('/course/info/AddCourseTeacher/', $params);
        if (!empty($res->code)) return false;

        return true;
	}
	
	public static function getCourseTeacher($data, $page=0, $length=0){
		$params = new stdCLass;
        if(!empty($data['courseId'])){
		    $params->courseId = $data['courseId'];		
        }
        if(!empty($data['teacherId'])){
            $params->teacherId = $data['teacherId'];
        }
		$res = utility_services::call('/course/info/CourseTeacher/'.$page.'/'.$length, $params);

        if (!empty($res->code)) return array();
        return $res->result;
	}
	//取课程老师详细信息
	public static function getCourseTeacherInfo($courseIdArr){
		if(empty($courseIdArr)) return array();
		$courseTeachers = course_api::getCourseTeacher(array("courseId"=>$courseIdArr));
		if(empty($courseTeachers)) return array(); 
		$data=array();
		foreach($courseTeachers as $vt){
			$teacherIds[] = $vt->fk_user_teacher;
			if(!empty($data[$vt->fk_course])){
				$data[$vt->fk_course][$vt->fk_user_teacher]['teacherId']=$vt->fk_user_teacher;
			}else{
				$data[$vt->fk_course]=array();
				$data[$vt->fk_course][$vt->fk_user_teacher]['teacherId']=$vt->fk_user_teacher;
			}
		}
		$teacherInfos = user_api::listUserIdsBylikeNameArr($teacherIds,"");
		$teachers = array();
		if($teacherInfos->data){
			foreach($teacherInfos->data as $teacherInfo){
				if(empty($teachers[$teacherInfo->user_id])){
					$teachers[$teacherInfo->user_id] = array();
				}
				$teachers[$teacherInfo->user_id] = array(
					'userId' => $teacherInfo->user_id,
					'realName' => $teacherInfo->real_name,
					'name' => $teacherInfo->name,
				);
			}
		}
		if($data){
			foreach($data as $fk_course=>$teachersInfo){
				foreach($teachersInfo as $teachersId){	
					$data[$fk_course][$teachersId['teacherId']]['teacherName'] = empty($teachers[$teachersId['teacherId']]['realName']) ? "":$teachers[$teachersId['teacherId']]['realName'];
				}
				$data[$fk_course]=array_values($data[$fk_course]);
			}
		}
		return $data;
	}
	
	public static function getCourseStatByCid($courseId){
		$params = new stdCLass;
		$params->courseId = $courseId;		
		$res = utility_services::call('course/info/getCourseStatByCid', $params);

        if (!empty($res->code)) return array();
        return $res->result;
	}
	public static function getCourseSubject($courseIds){
		$params = new stdCLass;
		$params->courseIds = $courseIds;
		$res = utility_services::call('course/info/getCourseSubject', $params);
		if($res->code===0){
			if($res->result->items){
				 return $res->result->items;
			}
		}
		return array();
	}
	/*
		取机构有效分类id
		$uid 机构拥有者id
		$type 0所有 1 一级,2二级 3三级
	*/
	public static function getOrgValidCateIds($uid,$type=0,$extWhere=array()){
		$params = new stdCLass;
		$params->uid = $uid;	
		$params->type = $type;
		$params->extWhere = $extWhere;
		$result = utility_services::call('course/cate/orgvalidcateids', $params);
		if($result->code==0){
			return $result->result;
		}
		return array();
	}
	//取多个父分类id
	public static function getParentCateByIds($cateIds,$level=2){
		$params = new stdCLass;
		$params->cateIds = is_array($cateIds)? implode(',',$cateIds):$cateIds;	
		$params->level = intval($level);
		$result = utility_services::call('course/cate/getParentCateByIds', $params);
		$cates=array();
		if($result->code==0){
			foreach($result->result as $v){
				$cates[$v->nCateId] = array(
					'pCateId'=>$v->pCateId,
					'pName'=>$v->pName,
				);
			}
		}
		return $cates;
	}
	public static function getAttrValueList($thirdCate,$orgOwner,$attrValueId=-1){	
		$requestValueIdArr = array();
		if($attrValueId != -1){
			$requestValueIdArr = explode(',',$attrValueId);
		}
		$attrList = array();
		$attrRet = course_api::getAttrAndValueByCateId($thirdCate,$orgOwner);
		if(!empty($attrRet)){
			$attrTemp = array();
			$attrValueIdArr = array();
			foreach($attrRet as $attr){
				if(!empty($attr->attr_value)){
					foreach($attr->attr_value as $value){
						$attrValueIdArr[] = $value->attr_value_id;
						$attrTemp[$attr->attr_id][] = $value->attr_value_id;
					}
				}
			}
			
			if($attrValueIdArr){
				$courseRet = course_api::checkMappingCourseByAttrValueIdArr($attrValueIdArr,$orgOwner);
				if(!empty($courseRet)){
					foreach($courseRet as $ro){
						$tempAttrValue[] = $ro->fk_attr_value; 
					}
					$hideAttrValue = array_diff($attrValueIdArr,$tempAttrValue);
					foreach($attrRet as $ao){
						if($attrTemp[$ao->attr_id] != $hideAttrValue){
							$flag = 0;
							if(!empty($ao->attr_value)){
								foreach($ao->attr_value as $vo){
									if(!in_array($vo->attr_value_id,$hideAttrValue)){
										$attrValueTemp[] = $vo;
										if(in_array($vo->attr_value_id,$requestValueIdArr)){
											$flag = 1;
											$vo->checked = 1;
										}else{
											$vo->checked = 0;
										}
									}
								}
								if(!empty($attrValueTemp)){
									$ao->attr_value = $attrValueTemp;
									$ao->attr_vlaue_ids = $attrTemp[$ao->attr_id];
									$ao->flag = $flag;
									$attrList[] = $ao; 
								}
							}
						}	
					}
				}
			}
		}
		return $attrList;
	}
	//班主任
	public static function headteacher($courseId){
		$params = new stdCLass;
		$params->courseId = $courseId;
		$result = utility_services::call('user/teacher/GetHeaderTeacher', $params);
		$data = array();
		$userIds = array();
		if(empty($result->result->code) && !empty($result->data->items)){
			foreach($result->data->items as $v){
				$userIds[] = $v->fk_user_class;
			}
			if($userIds){
				$userInfo = user_api::listUserIdsBylikeNameArr($userIds,"");
				$userInfos = array();
				if(!empty($userInfo->data)){
					foreach($userInfo->data as $kk=>$vv){
						if($vv){
							$userInfos[$vv->user_id] = array(
								'teacherId' => $vv->user_id,
								'teacherName' => empty($vv->real_name)?$vv->name:$vv->real_name,
							);
						}
					}
				}
			}
			foreach($result->data->items as $v){
				if(!empty($userInfos[$v->fk_user_class])){
					$data[$v->fk_course][$v->fk_user_class]=$userInfos[$v->fk_user_class];
				}
			}
		}
		return $data;
	}
	
	public static function getCourseMaxId($ownerId,$courseType){
		$result = utility_services::call('/course/info/getCourseMaxId/'.$ownerId.'/'.$courseType);
		if($result->code == 0){
			return $result->result->courseId;
		}
		return false;
	}

    public static function getUserClassId($uId,$courseId){
        if(empty($uId)||empty($courseId)){
            return false;
        }
        $params["course_id"] = $courseId;
        $params["uid"] = $uId;
        $result = utility_services::call('/course/courseuser/CheckUserIsReg', $params);
        return $result;
    }



	public static function updateCourseUserExpireTime($userId,$courseId,$time){
        $params = [
            'userId' => $userId,
			'courseId' => $courseId,
            'expireTime'   => $time
        ];
        $res    = utility_services::call('/course/courseuser/updateExpireTimeByUidAndCid', $params);
        return $res;
    }


    /**
     * 科目下的  course_type
     */
    public static function GetCourseType($attrId,$uid){
        if(empty($attrId || $uid)) return interface_func::setMsg(1000);
        $attrId = (int)$attrId;
        $uid = (int)$uid;
        $params = new stdClass();
        $params->attrId = $attrId;
        $params->uid = $uid;
        $res    = utility_services::call('/course/cate/GetCourseType', $params);
        return $res;
    }
	
	public static function delCourse($courseId){
		if(empty($courseId)) return false;
		$res = utility_services::call('/course/info/DelCourse/'.$courseId);
		if(empty($res->code)){
			return true;
		}
		
		return false;
	}


    public static function GetNoteListByPlanIdAndClassId($classId,$planId,$uId,$page,$limit){
        $params = [
            'classId' => $classId,
            'planId' => $planId,
            'page'   => $page,
            'fk_user'=>$uId,
            'limit' => $limit
        ];
        $res = utility_services::call('/course/note/GetNoteListByPlanIdAndClassId/',$params);
        return $res;

    }
	public static function getOneCourseInfo($courseId){	
		$res = utility_services::call('course/info/getOneCourseInfo/'.$courseId);
        if (!empty($res->result)&&$res->code==0){
			return $res->result;
		}
       
	}
	
	//机构首页直播列表
	public static function getOrgLivingList($uid,$ownerId,$tips){
		if(!empty($uid)){
			//这里需要优化 TODO
            $reg_info = course_api::listRegistration(array("uid"=>$uid));
            if(!empty($reg_info->data)){
                foreach($reg_info->data as $rv){
                    $userCourseList[] = $rv->cid;
                    $userClassList[] = $rv->class_id;
                }
            }
        }
        //即将直播 一周内
        $time1=date('Y-m-d 00:00:00').','.date('Y-m-d 23:59:59',strtotime('+7 days'));
		if($tips==1){
			$size = 5;
		}else{
			$size = 4;
		}
		$orgInfo = user_organization::getOrgByOwner($ownerId);
        $timeArr1=array(
            "f"=>array(
                'course_id',
                'course_name',
                'plan_id',
                'class_name',
                'section_name',
                'admin_name',
                'admin_real_name',
                'start_time',
                'fee_type',
                'try',
                'status',
            ),
            "q"=>array(
                'start_time'=>$time1,
                'status'=>'1,2',
                'course_type'=>1,
                'admin_status'=>1,
                //'owner_id'=>$ownerId,
				'expression'   => "@resell_org_id =".$orgInfo->oid." | @org_id=".$orgInfo->oid,
            ),
            "ob"=>array(
                'start_time'=>'asc',
            ),
            "p"=>1,
            "pl"=>$size,
        );
        $unStartedPlan=seek_api::seekPlan($timeArr1);
        //精彩回放
        $timeArr2=array(
            "f"=>array(
                'course_id',
                'course_name',
                'plan_id',
                'class_name',
                'section_name',
                'admin_name',
                'admin_real_name',
                'start_time',
                'fee_type',
                'try',
                'status',
				'resell_org_id',
            ),
            "q"=>array(
                'status'=>'3',
                'course_type'=>1,
                'admin_status'=>1,
               // 'owner_id'=>$ownerId,
			   'expression'   => "@resell_org_id =".$orgInfo->oid." | @org_id=".$orgInfo->oid,
            ),
            "ob"=>array(
                'start_time'=>'desc',
            ),
            "p"=>1,
            "pl"=>$size,
        );
		
        $finishedPlan=seek_api::seekPlan($timeArr2);
		$now = date("d");
		$planList = array();
		//分销排课
		$params      = array('sort'=>0 ,'status'=>1,'uid'=>$orgInfo->oid);
        $resCourse  = course_resell_api::getResellCourselist(1, 500,$params);
		if(!empty($resCourse->data->items)){
			foreach($resCourse->data->items as $k=>$v){
				$resellCourseData[$v->course_id]['course_id'] 	  = !empty($v->course_id) ? $v->course_id : 0;
				$resellCourseData[$v->course_id]['fk_org_resell'] = !empty($v->fk_org_resell) ? $v->fk_org_resell : 0;
				$resellCourseData[$v->course_id]['price_resell']  = !empty($v->price_resell) ? $v->price_resell : 0;
			}
		}
		
		
        //处理即将直播数据
		if(!empty($unStartedPlan->data)){
			foreach($unStartedPlan->data as $usv){
				if(isset($resellCourseData[$usv->course_id]['price_resell'])){
					$usv->price_resell = $resellCourseData[$usv->course_id]['price_resell'];
				}
				
				if(date('d',strtotime($usv->start_time))==$now){
					$usv->start_time = "今日 ".date("H:i",strtotime($usv->start_time))." 上课";
				 }else{                  
					$usv->start_time = date("Y-m-d H:i",strtotime($usv->start_time));
				 } 
                //是否已报名
                if(!empty($userCourseList) && in_array($usv->course_id,$userCourseList)){
                    $usv->register=1;
                }else{
                    $usv->register=0;
                }
				$usv->oid = $orgInfo->oid;
                $planList['unstarted'][]=$usv;
			}
		}
        //处理精彩回放数据
		if(!empty($finishedPlan->data)){
			foreach($finishedPlan->data as $fv){
				if(isset($resellCourseData[$fv->course_id]['price_resell'])){
					$fv->price_resell =  $resellCourseData[$fv->course_id]['price_resell'];
				}
				if(date('d',strtotime($fv->start_time))==$now){
					$fv->start_time = "今日 ".date("H:i",strtotime($fv->start_time));
				 }else{                  
					$fv->start_time = date("Y-m-d H:i",strtotime($fv->start_time));
				 } 
                //是否已报名
                if(!empty($userCourseList) && in_array($fv->course_id,$userCourseList)){
                    $fv->register=1;
                }else{
                    $fv->register=0;
                }
				$fv->oid = $orgInfo->oid;
                $planList['finished'][]=$fv;
			}
		}
		return $planList;
	} 

    public static function getPlanByCourseId($courseId){
        if(empty($courseId) || !is_int($courseId)) return false;

        $param = new stdClass();
        $param->courseId = $courseId;
        $res = utility_services::call('course/plan/getPlanByCourseId/', $param);
        if(empty($res->code)) return $res->result;

        return false;
    }
	
	public static function GetplatformPoint($params){
        $res = utility_services::call('course/plan/GetplatformPoint/', $params);
		if($res->code ==0 && !empty($res->result)){
			return $res->result;
		}
		return false;
    }
	/*机构下获取分销课*/
	public static function getSalesCourse($page=1,$length=0,$condition){
        $salesInfo = course_resell_api::getSalesCourse($page=1,$length=0,$condition);
		$resell = array();
		if(!empty($salesInfo)){
			foreach($salesInfo as $k=>$v){
				$resell[$v->fk_course] =$v;
			}
		}
		if(!empty($resell)){
			return $resell;
		}else{
			return false;
		}
		
    }
	public static function getPlanListByCourseIdArr($condition){
        $res = utility_services::call('course/plan/GetPlanByCourseId/', $condition);
		if(!empty($res->result)){
			return $res->result;
		}else{
			return false;
		}
		
    }
	
}
