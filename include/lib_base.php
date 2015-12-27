<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/14
 * Time: 20:22
 */
  //递归转义数组
function _addslashes($arr){
    foreach($arr as $k=>$v){
        if(is_string($v)){
            $arr[$k] = addslashes($v);
        }elseif(is_array($v)){       //再加判断，如果是数组，在调用自身
            $arr[$k] = _addslashes($v);
        }
    }
    return $arr;
}