<?php
class course_phrase_api{
    public static function getPlanPhraseByPid($pid){
        return utility_services::call('/course/phrase/getPlanPhraseByPid', $pid);
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
    public static function getPhraseIdArr($idArr){
        $params = new stdClass;
        if(!empty($idArr)){
            $params->phraseId = $idArr;
        }
        $ret = utility_services::call("/course/phrase/getPhraseIdArr",$params);
        return $ret;
    }
}
