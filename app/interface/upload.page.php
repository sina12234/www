<?php

class interface_upload extends interface_base
{
    public function pageList()
    {
        $this->v(
            [
                'userId'    => 1000,
                'courseId'  => 1000,
                'sectionId' => 1000
            ]
        );

        $courseId  = $this->paramsInfo['params']['courseId']; //454
        $sectionId = $this->paramsInfo['params']['sectionId']; //1142
        $uid       = $this->paramsInfo['params']['userId']; //171

        $paramsCourse = [
            'q' => [
                'course_id'  => $courseId,
                'section_id' => $sectionId,
            ],
            'f' => ['title', 'thumb_med']
        ];

        $courseInfo = seek_api::seekCourse($paramsCourse);
        if (empty($courseInfo->data[0])) return [];
        $result[$courseId] = [
            'courseName' => $courseInfo->data[0]->title,
            'image'      => interface_func::imgUrl($courseInfo->data[0]->thumb_med),
            'videos'     => []
        ];

        $paramsPlan = [
            'q' => [
                'course_id'  => $courseId,
                'section_id' => $sectionId,
            ],
            'f' => ['plan_id', 'section_name', 'course_id']
        ];

        $planList = seek_api::seekPlan($paramsPlan);
        if (empty($planList->data))
            return interface_func::setData($result[$courseId]);

        $planIdArr = [];
        foreach ($planList->data as $item) {
            $planIdArr[$item->plan_id] = $item->plan_id;
            $result[$item->course_id] += ['sectionName' => $item->section_name];
        }

        $planIdStr = implode(',', $planIdArr); // 1076,1077
        $uploadList = interface_planApi::getUploadList($uid, $planIdStr);
        if (!empty($uploadList)) {
            $result[$courseId]['videos'] = array_values($uploadList);
        }

        return interface_func::setData($result[$courseId]);
    }
	
	public function pageUploadTask()
	{
		$this->v(
            [
                'userId' => 1000,
                'planId' => 1000
            ]
        );

        $planId  = (int)$this->paramsInfo['params']['planId']; 
        $uid     = (int)$this->paramsInfo['params']['userId']; 
		
		$uploadTask = live_file::getUploadTask($uid,$planId);
		
		if(!empty($uploadTask)){
			return $this->setData(array('code'=>'-11','msg'=>'视频转码中'));
        }
		if(live_file::addUploadTask($uid,$planId,'UPLOAD')){
			return $this->setData(array('code'=>'1','msg'=>'开始转码'));
		}else{
			return $this->setData(array('code'=>'-5','msg'=>'转码失败'));
		}
	}
	
	public function pageEncodingStatus()
	{		
		$this->v(['userId' => 1000,'planId' => 1000]);
		$planId  = (int)$this->paramsInfo['params']['planId']; 
        $uid     = (int)$this->paramsInfo['params']['userId']; 
		
		$uploadTask = live_file::getUploadTask($uid,$planId);
		
		if(empty($uploadTask)){
			$data = array('code'=>'0','msg'=>'开始转码');
		}elseif($uploadTask->status == '-1'){
			$data = array('code'=>'-1','msg'=>'转码失败');
		}elseif($uploadTask->status == 0 || $uploadTask->status == 1){
			$data = array('code'=>'-2','msg'=>'转码中');
		}else{
			$data = array('code'=>'1','msg'=>'转码成功');
		}
		
		$this->setData($data);
	}
	
	public function pageDelVideo()
	{
		$this->v(['userId' => 1000,'planId' => 1000]);
		$planId  = (int)$this->paramsInfo['params']['planId']; 
        $uid     = (int)$this->paramsInfo['params']['userId'];
		
		$uploadList = live_file::listUploadFile($uid, $planId);
		if(empty($uploadList)) return $this->setData(array('code'=>1045,'msg'=>'视频不存在'));

		foreach($uploadList as $val){
			if($val->user_id == $uid){
				$status = live_file::setUploadFileStatus($val->file_id);
			}
		}
		
		if(!$status) {
			return $this->setData(array('code'=>1046,'msg'=>'删除失败'));
		}
		return $this->setData(array('code'=>1,'planId'=>$planId,'msg'=>'删除成功'));

	}
	
}
