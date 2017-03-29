<?php

class interface_orgfront extends interface_base {

    /**
     * @version 1.3
     * @desc  小沃首页
     * @param int $orgId
     * @param string $condition (地区id,学段id,年级id)
     * @param int $width 轮播图宽度
     * @return object
     */
    public function pageHome(){
        $orgId    = !empty($this->paramsInfo['oid']) ? (int)$this->paramsInfo['oid'] : 0;
        $condition= !empty($this->paramsInfo['params']['condition']) ? explode(',', $this->paramsInfo['params']['condition']) : array();
        $width    = !empty($this->paramsInfo['dinfo']['rw']) ? (int)$this->paramsInfo['dinfo']['rw'] : 0;
        $userId   = !empty($this->paramsInfo['params']['userId']) ? (int)$this->paramsInfo['params']['userId'] : 0;

        if(empty($orgId) || empty($condition)){
            return $this->setMsg(1000);
        }

        $provinceId = !empty($condition[0]) ? (int)$condition[0] : 0;
        $secondId   = !empty($condition[1]) ? (int)$condition[1] : 0;
        $thirdId    = !empty($condition[2]) ? (int)$condition[2] : 0;

        $orgInfo = org_api::getOrgInfo($orgId);
        if(empty($orgInfo)) return $this->setMsg(1000, '该机构不存在');

        //机构轮播图和专题
        $bannerReg = interface_utility_banner::orgList($orgId, $width);
        $banners   = !empty($bannerReg['ad']) ? $bannerReg['ad'] : array();
        $specials  = !empty($bannerReg['special']) ? $bannerReg['special'] : array();

        //快速进入课程分类卡片
        $u = $this->paramsInfo['u'];
        $quickType = interface_utility_config::orgQuickType($orgId, $orgInfo->user_owner_id, $u);

        //直播列表
        $lives = interface_plan_orglist::getIndexLives($userId, $orgId);

        //名师列表(本地区下二级分类的老师)
        $teacherRes = user_api::getRecommendTeacherByAreaId($orgId,$provinceId);
        $tids = array();
        $teachers_temp = array();
        $teachers = array();
        if (!empty($teacherRes->result)) {       
            foreach ($teacherRes->result as $key => $value) {
                $tids[] = $value->tid;
            }            

            $tidStr = implode(',', $tids);
            $params = [
                'q' => ['teacher_id'=>$tidStr],
                'f' => [
                    'teacher_id','real_name','thumb_med','comment','totaltime','course_count','avg_score'
                ]
            ];
            $seekTeacher = seek_api::seekTeacher($params);
            if (!empty($seekTeacher->data)) {
                foreach ($seekTeacher->data as $key => $value) {
                    $teachers_temp[$value->teacher_id] = [
                        "teacherId" => $value->teacher_id,
                        "teacherName" => $value->real_name,
                        "teahcerImg" => $value->thumb_med,
                        "totalTime" => $value->totaltime,
                        "comment" => $value->comment,
                        "course_count" => $value->course_count,
                        "avg_score" => sprintf("%.1f", $value->avg_score)
                    ];
                }
            }
            if (!empty($tids)) {
                foreach ($tids as $key => $value) {//按mgr自定义顺序输出
                  $teachers[] = [
                    "teacherId" => $teachers_temp[$value]['teacherId'],
                    "teacherName" => $teachers_temp[$value]['teacherName'],
                    "teahcerImg" => $this->imgUrl($teachers_temp[$value]['teahcerImg']),
                    "totalTime" => $teachers_temp[$value]['totalTime'],
                    "comment" => $teachers_temp[$value]['comment'],
                    "course_count" => $teachers_temp[$value]['course_count'],
                    "avg_score" => sprintf("%.1f",$teachers_temp[$value]['avg_score'])
                  ];
                }
            }           
        }       

        //精选课程列表
        $selectCourses = interface_course_orglist::selectCourses($orgId, $thirdId);

        //推荐课程列表
        $recommends = interface_course_orglist::recommend($orgInfo->user_owner_id, $orgId);

        $data = [
            'banners'   => $banners,
            'specials'  => $specials,
            'types'     => $quickType,
            'lives'     => $lives,
            'teachers'  => $teachers,
            'recommends'=> $recommends,
            'selectCourses' => $selectCourses
        ];

        return $this->setData($data);
    }
}
