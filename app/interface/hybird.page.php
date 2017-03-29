<?php
/**
 * 接口页面显示
 */
class interface_hybird extends STpl{
    
    public function pageOrder(){
        $this->render("/interface/liantong/order.html");
    }

    public function pageActive(){
        $this->render("/interface/liantong/active.html");
    }

    public function pageUnorder(){
        $this->render("/interface/liantong/unorder.html");
    }

    public function pageFlowquery(){
        $this->render("/interface/liantong/flowquery.html");
    }

    /**
     * 机构详情
     * @param int $inPath[3] 机构id
     */
    public function pageOrgInfo($inPath){
        $orgId = !empty($inPath[3]) ? (int)$inPath[3] : 0;
        if(empty($orgId)){
            return $this->render("interface/yunke/empty.html");
        }

        //机构详情
        $params = [
            'q' => ['org_id'=>$orgId,'status'=>1],
            'f' => [
                'org_id','name','subname','subdomain','thumb_med','desc','hotline','province',
                'city','address','visiable_teacher_count','course_count','user_owner_id'
            ],
        ];
        $orgInfo = seek_api::seekOrg($params);
        if(empty($orgInfo->data)){
            return $this->render("interface/yunke/empty.html");
        }

        //推荐老师
        $teacherList = user_organization::listOrgUser($orgId, 0, 1, 1, 5);

        //推荐课程
        $i = 0;
        $courseList= array();
        $orgTemReg = user_organization::getOrgTemplate($orgInfo->data[0]->user_owner_id);
        foreach($orgTemReg as $key=>$val){
            if($val->recommend == 1){
                if($val->query_arr->fee_type == 2){
                    unset($val->query_arr->fee_type);
                }
                if(!empty($val->query_arr)){
                    $params = [
                        'q' => $val->query_arr,
                        'f' => [
                            'course_id','title','course_type','thumb_med','price','fee_type','org_subname',
                            'user_total'
                        ],
                        'pl'=> $val->row_count * 4
                    ];
                    if(!empty($val->order_by)){
                        $params['ob'] = $val->order_by;
                    }
                    $seekCourse = seek_api::seekCourse($params);
                    if(!empty($seekCourse->data)){
                        foreach($seekCourse->data as $v){
                            $courseList[$key][] = $v;
                            if($i > 19) break;
                            $i++;
                        }
                    }
                }
            }elseif($val->recommend == 2){
                if(!empty($val->course_arr)){
                    $courseIds = implode(',', $val->course_arr);
                    $params = [
                        'q' => ['course_id'=>$courseIds],
                        'f' => [
                            'course_id','title','course_type','thumb_med','price','fee_type','org_subname',
                            'user_total'
                        ]
                    ];
                    if(!empty($val->order_by)){
                        $params['ob'] = $val->order_by;
                    }
                    $seekCourse = seek_api::seekCourse($params);
                    if(!empty($seekCourse->data)){
                        foreach($seekCourse->data as $v){
                            $courseList[$key][] = $v;
                            if($i > 19) break;
                            $i++;
                        }
                    }
                }
            }
        }

        $this->assign('info', $orgInfo->data[0]);
        $this->assign('teacherList', $teacherList);
        $this->assign('courseList', $courseList);

        $this->render("/interface/yunke/orginfo.html");
    }

    /**
     * 课程详情
     * @param int $courseId  课程id
     * @param int $userId    用户id
     * @param int $resellOrgId  分销机构id
     */
    public function pageCourseInfo(){
        $courseId    = !empty($_GET['courseId']) ? (int)$_GET['courseId'] : 0;
        $userId      = !empty($_GET['userId']) ? (int)$_GET['userId'] : 0;
        $resellOrgId = !empty($_GET['orgId']) ? (int)$_GET['orgId'] : 0;

        //课程信息
        $courseRes = course_detailed::getCourseInfo($courseId);

        if(!empty($resellOrgId) && $courseRes['isPromote']) {
            $resellCourseRes = course_resell_api::getCourseResell($courseId, $resellOrgId);
            $courseRes['price'] = !empty($resellCourseRes) ? $resellCourseRes['price_resell'] / 100 : $courseRes['price'];
        }

        if(empty($courseRes)){
            return $this->render("interface/yunke/empty.html");
        }
        $courseRes['isFree'] = 0;

        //报名信息
        $userReg = interface_user_api::getUserRegCourse($userId, array($courseId));
        $classId = !empty($userReg['items']) ? array_column($userReg['items'], 'fk_class', 'fk_class') : array();

        //班级信息
        $classData = $signClass = array();
        if($courseRes['courseType'] == 3){
            foreach($courseRes['class'] as $val){
                $classData[] = [
                    'className' => $val->name,
                    'address'   => $val->region_level0.' '.$val->region_level1.' '.$val->region_level2.' '.$val->address
                ];
                if(!empty($classId[$val->class_id])){
                    $signClass[] = [
                        'className' => $val->name,
                        'address'   => $val->region_level0.' '.$val->region_level1.' '.$val->region_level2.' '.$val->address
                    ];
                }
            }
        }

        //会员信息
        if(!empty($courseRes['memberset'])){
            foreach($courseRes['memberset'] as $v){
                $setIdArr[] = $v->member_set_id;
            }
            $memberRet = org_member_api::checkIsMemberOrExpire($userId, $setIdArr, $courseId);

            $isExpire = array();
            if(!empty($memberRet['userMemberSet'])){
                $isExpire = array_column($memberRet['userMemberSet'], 'is_expire', 'fk_member_set');
                $courseRes['isFree'] = in_array(0 ,$isExpire) ? 1 : 0;
            }
            //1 过期 0 未过期
            foreach($courseRes['memberset'] as &$value){
                $value->isExpire = isset($isExpire[$value->member_set_id]) ? $isExpire[$value->member_set_id] :1;
            }
        }

        //老师信息
        $teacher = array();
        $teacherIds = course_class_api::getTeacherByCourseId(array($courseId));
        if(!empty($teacherIds)){
            $teachersInfo = user_api::getTeacherInfoByIds($teacherIds);
            if(!empty($teachersInfo)){
                foreach($teachersInfo as $teacherInfo){
                    $teacher[]= [
                        'teacherId'   => $teacherInfo->pk_user,
                        'teacherName' => $teacherInfo->real_name,
                        'thumbMed'    => interface_func::imgUrl($teacherInfo->thumb_med),
                        'teacherDesc' => $teacherInfo->desc,
                    ];
                }
            }
        }

        $this->assign('signClass', $signClass);
        $this->assign('classData', $classData);
        $this->assign('data', $courseRes);
        $this->assign('teachers', $teacher);
        $this->render("interface/yunke/course.desc.html");
    }

}
