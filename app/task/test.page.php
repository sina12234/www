<?php
header("Content-type: text/html; charset=utf-8");

class task_test extends STpl{

    function strRev($str)
    {
        //$str = strrev("asdfghjklp");
        if ($str == '') return 0 ;
        $rev_str = '';
        for ($i=(strlen($str)- 1); $i>=0; $i --){
            $rev_str .= $str[$i];
        }
        return $rev_str;
    }

    public function pageTest(){
        //测试
        $person=array(
            array('id'=>2,'name'=>'zhangsan','age'=>23),
            array('id'=>5,'name'=>'lisi','age'=>28),
            array('id'=>3,'name'=>'apple','age'=>17)
        );
        $result = $this->array_sort($person,'age',0);
        print_R($result);die;
    }


    //二维数组排序
    function array_sort($arr,$keys,$order=0){
        if(!is_array($arr)){
            return false;
        }
        $keysvalue=array();
        foreach($arr as $key => $val){
            $keysvalue[$key] = $val[$keys];
        }
        if($order == 0){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach($keysvalue as $key => $vals){
            $keysort[$key] = $key;
        }
        $new_array=array();
        foreach($keysort as $key=> $val){
            $new_array[$key]=$arr[$val];
        }
        return $new_array;
    }

    function quick_sort($array ) {
        if (count($array) <= 1) return  $array;
        $key = $array [0];
        $left_arr  = array();
        $right_arr = array();
        for ($i= 1; $i<count($array ); $i++){
            if ($array[ $i] <= $key)
                $left_arr [] = $array[$i];
            else
                $right_arr[] = $array[$i ];
        }
        $left_arr = $this->quick_sort($left_arr );
        $right_arr = $this->quick_sort( $right_arr);
        return array_merge($left_arr , array($key), $right_arr);
    }



}