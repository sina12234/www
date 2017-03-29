<?PHP
/**
 * test06
 *
 */
class interface_tmp extends interface_base{
	//课程配置
    public function pageGetNature(){
        $cateList = course_api::getCateList();
        $cate = array(7=>"1000",8=>"2000",9=>"3000");
        $i = 0;
        $attrRes = course_api::getAllAttrValue();
        $arr = array('id'=>0,'name'=>'全部');
        $attrValue = array();
        if(!empty($attrRes->items)){
            foreach($attrRes->items as $val){
                $attrValue[$val->fk_cate][0] = $arr;
                $attrValue[$val->fk_cate][] = [
                    'id'   => $val->pk_attr_value,
                    'name' => $val->name,
                ];
            }
        }
		
		foreach($cateList as $ke=>$val){
			if($val->level == 2){
                $data[0] = $arr;
				$data[$ke] = [
					'name' => $val->name,
					'id'   => $val->pk_cate
				];
				foreach($cateList as $k=>$v){
					if($v->level == 3 && $v->lft > $val->lft && $v->rgt < $val->rgt){
						
                        $data[$ke]['data'][0] = $arr;
						if(!empty($cate[$val->pk_cate])){
							$data[$ke]['data'][] = [
								'name' => $v->name,
								'id'   => $v->pk_cate,
								'oldId'=> $cate[$val->pk_cate],
								'attr' => !empty($attrValue[$v->pk_cate]) ? $attrValue[$v->pk_cate] : array(0=>array('id'=>0,'name'=>'全部'))
							];
						}else{
							$data[$ke]['data'][] = [
								'name' => $v->name,
								'id'   => $v->pk_cate,
								'attr' => !empty($attrValue[$v->pk_cate]) ? $attrValue[$v->pk_cate] : array(0=>array('id'=>0,'name'=>'全部'))
							];
						}
						
					}
				}
			}
		}
        $data = array_values($data);
        return $this->setData($data);
    }
	//老师配置
	public function pageTeacherConfig()
	{
		//教学范围
		$grades = ['4000' => '学前','1000'=>'小学','2000'=>'初中','3000'=>'高中'];
		
		//科目
		$group   = SConfig::getConfig(ROOT_CONFIG."/group.conf","group");
		$subject = tag_api::getTagByGroupId($group->subject);
		
		$arr = array('id'=>0,'name'=>'全部');
		foreach($subject as $key=>$val){
			$subjectArr[0] = $arr;
			$subjectArr[]  = [
				'id'   => $key,
				'name' => $val
			];
		}
		
		
		foreach($grades as $k=>$v){
			$data[0] = $arr;
			$data[]  = [
				'id'   => $k,
				'name' => $v,
				'data' => $subjectArr
			];
		}
		
		return $this->setData($data);
	}
}
