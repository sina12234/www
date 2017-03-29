<?php

class interface_course_orglist {

    /**
     * @desc  三级分类下课程
     * @param int $orgId
     * @param array $thirdIdArr  三级分类id
     * @return array
     */
    public static function thirdCate($orgId, $thirdIdArr){
        $data = [];
        if(!is_array($thirdIdArr) || empty($orgId)) return $data;

        foreach ($thirdIdArr as $val) {
            $params = [
                'q' => ['third_cate'=>$val,'admin_status'=>1],
                'f' => [
                    'course_id','title','thumb_med','user_total','second_cate','second_cate_name','third_cate_name',
                    'first_cate','create_time','third_cate'
                ],
                'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId,
                'p' => 1,
                'pl'=> 4,
                'ob'=> ['user_total'=>'desc']
            ];
            $seekCourse = seek_api::seekCourse($params);
            if(!empty($seekCourse->data)) {
                foreach ($seekCourse->data as $key=>$val) {
                    $data[$val->second_cate]['id']   = $val->second_cate;
                    $data[$val->second_cate]['name'] = $val->second_cate_name;
                    $data[$val->second_cate]['pId']  = $val->first_cate;
                    $data[$val->second_cate]['list'][$key]['courseId']   = $val->course_id;
                    $data[$val->second_cate]['list'][$key]['courseName'] = $val->title;
                    $data[$val->second_cate]['list'][$key]['imgurl']     = interface_func::imgUrl($val->thumb_med);
                    $data[$val->second_cate]['list'][$key]['createTime'] = $val->create_time;
                    $data[$val->second_cate]['list'][$key]['userTotal']  = $val->user_total;
                }
            }
        }

        return array_values($data);
    }

    /**
     * @desc  机构推荐课程
     * @param int $ownerId
     * @param int $orgId
     * @return array
     */
    public static function recommend($ownerId, $orgId){
        $data = array();

        $result = user_organization::getOrgTemplate($ownerId);
        if(empty($result)) return $data;

        foreach($result as $key=>$val){
            if($val->recommend == 1){
                if($val->query_arr->fee_type == 2){
                    unset($val->query_arr->fee_type);
                }
                if(!empty($val->query_arr)){
                    $val->query_arr->admin_status = 1;
                    $params = [
                        'q' => $val->query_arr,
                        'f' => [
                            'course_id','title','course_type','thumb_med','price','fee_type','org_subname',
                            'user_total','third_cate_name'
                        ],
                        'pl'=> $val->row_count * 4
                    ];
                    if(!empty($val->order_by)){
                        $params['ob'] = $val->order_by;
                    }
                    $seekCourse = seek_api::seekCourse($params);
                    if(!empty($seekCourse->data)){
                        foreach($seekCourse->data as $v){
                            $data[$key]['name'] = $val->title;
                            $data[$key]['list'][] = [
                                'courseId'   => $v->course_id,
                                'courseName' => $v->title,
                                'imgUrl'     => interface_func::imgUrl($v->thumb_med),
                                'userTotal'  => $v->user_total,
                                'thirdName'  => $v->third_cate_name,
                                'resellId'   => $orgId
                            ];
                        }
                    }
                }
            }elseif($val->recommend == 2){
                if(!empty($val->course_arr)){
                    $courseIds = implode(',', $val->course_arr);
                    $params = [
                        'q' => ['course_id'=>$courseIds,'admin_status'=>1],
                        'f' => [
                            'course_id','title','course_type','thumb_med','price','fee_type','org_subname',
                            'user_total','third_cate_name'
                        ]
                    ];
                    if(!empty($val->order_by)){
                        $params['ob'] = $val->order_by;
                    }
                    $seekCourse = seek_api::seekCourse($params);
                    if(!empty($seekCourse->data)){
                        foreach($seekCourse->data as $v){
                            $data[$key]['name'] = $val->title;
                            $data[$key]['list'][] = [
                                'courseId'   => $v->course_id,
                                'courseName' => $v->title,
                                'imgUrl'     => interface_func::imgUrl($v->thumb_med),
                                'userTotal'  => $v->user_total,
                                'thirdName'  => $v->third_cate_name,
                                'resellId'   => $orgId
                             ];
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 精选课程
     * @param int $orgId
     * @param int $threeId 年级id
     * @return array
     */
    public static function selectCourses($orgId, $thirdId){
        $data = array();
        $params = [
            'q' => ['third_cate'=>$thirdId,'fee_type'=>1,'status'=>'1,2','admin_status'=>1,
                'expression' => "@resell_org_id =".$orgId." | @org_id=".$orgId,
            ],
            'f' => ['course_id','title','thumb_med','user_total','org_subname','third_cate_name','org_id'],
            'p' => 1,
            'pl'=> 8,
            'ob'=> ['user_total'=>'desc'],
        ];
        $seekCourse = seek_api::seekCourse($params);

        if(!empty($seekCourse->data)){
            foreach($seekCourse->data as $val){
                $data[] = [
                    'courseId'   => $val->course_id,
                    'courseName' => $val->title,
                    'courseImg'  => interface_func::imgUrl($val->thumb_med),
                    'subname'    => $val->org_subname,
                    'userTotal'  => $val->user_total,
                    'thirdName'  => $val->third_cate_name,
                    'resellId'   => $orgId
                ];
            }

        }

        return $data;
    }

}
