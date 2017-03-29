<?php
/*
 * chat传递消息接口
 */
class exam_log extends STpl{
	var $user;
	public function __construct($inPath){
		$this->user = user_api::loginedUser();
	}
	public function pageLogIssue($inPath){
		if(empty($this->user)){
			return false;
		}
		if(!empty($_POST)){
			return exam_api::logIssue($_POST, $this->user["uid"]);
		}
		return false;
	}
	public function pageLogUserQues($inPath){
		if(empty($this->user)){
			return false;
		}
        
		if(!empty($_POST)){
			$data = array();
			$data["plan_id"] = $_POST["plan_id"];
			$user_id = empty($_POST["user_id"]) ? (int) $this->user["uid"] : (int) $_POST["user_id"];
			$ret = exam_api::logUserQues($data, $user_id);    // 备课出题
			$retQuick = exam_api::logUserQuesQuick($data, $user_id);    // 快速出题 
             
			$returndata = new stdclass;
            if(!empty($ret->data) || !empty($retQuick->items)){
				$all = empty($ret->data) ? 0 : count($ret->data);
                $allQuick = count($retQuick->items);
				$correct = array();
				$mistake = array();
				$miss = array();
                
                // 各课出题 
                if(!empty($ret->data)){
                    foreach($ret->data as $k=>$v){
                        if(empty($v->options)){
                            $miss[] = $v;
                        }elseif($v->correct ==1){
                            $correct[] = $v;
                        }else{
                            $mistake[] = $v;
                        }
                    }
                }
                // 快速出题 
                if(!empty($retQuick->items)){
                    foreach($retQuick->items as $k=>$v){
                        if(empty($v->answer)){
                            $miss[] = $v;
                        }elseif($v->answer_status ==1){
                            $correct[] = $v;
                        }else{
                            $mistake[] = $v;
                        }
                    }
                }
				$countAll = (int)($all+$allQuick);
				$countCorrect = count($correct);
				$countMistake = count($mistake);
				$countMiss = count($miss);
				$returndata->all = $countAll;
				$returndata->correct = $countCorrect;
				$returndata->mistake = $countMistake;
				$returndata->miss = $countMiss;
			}else{
				$returndata->all = 0;
				$returndata->correct = 0;
				$returndata->mistake = 0;
				$returndata->miss = 0;
			//	$returndata = array("all"=>"0","correct"=>"0","mistake"=>"0","miss"=>"0");
			}
			return SJSON::encode($returndata);
		}
		return false;
	}
}
?>
