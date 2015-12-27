<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/11
 * Time: 9:32
 */
/**    直接插入排序,插入排序的思想是：当前插入位置之前的元素有序，
 *    若插入当前位置的元素比有序元素最后一个元素大，则什么也不做，
 *    否则在有序序列中找到插入的位置，并插入
 */
$arr = array(41,12,32,23,4);
function insertSort(&$arr){
    for($i=1;$i<count($arr);$i++){
        if($arr[$i-1] > $arr[$i]){
            for($j = $i-1;$j>=0;$j--){
                $temp = $arr[$j+1];
                if($arr[$j]>$temp){
                    $arr[$j+1] = $arr[$j];
                    $arr[$j] = $temp;
                }else{
                    break;
                }
            }
        }
    }
    return $arr;
}
 /*
 2     冒泡排序,冒泡排序思想：进行 n-1 趟冒泡排序， 每趟两两比较调整最大值到数组（子数组）末尾
 3 */
function bubbleSort(&$arr){
    for($i=1;$i<count($arr);$i++){
        for($j=0;$j<count($arr)-$i;$j++){
            if($arr[$j] > $arr[$j+1]){
                $temp = $arr[$j+1];
                $arr[$j+1] = $arr[$j];
                $arr[$j] = $temp;
            }
        }
    }
}

 /*
 2     简单选择排序, 简单排序思想：从数组第一个元素开始依次确定从小到大的元素
 3 */
function selectSort(&$arr){
    for($i=0;$i<count($arr);$i++){
        $k = $i;
        for($j = $i+1;$j < count($arr);$j++){
            if($arr[$k] > $arr[$j]){
                $k = $j;
            }
        }
        if($k!= $i){
            $temp = $arr[$i];
            $arr[$i] = $arr[$k];
            $arr[$k] = $temp;
        }
    }
}
/*
 * 递归删除目录
 */
function delDir($path){
    //不是目录，直接返回
    if(!is_dir($path)){
        return NULL;
    }
    $dh = opendir($path);
    while(($row = readdir($dh)) !== false){
        if($row == '.' || $row == '..'){
            continue;
        }
        //判断是否是普通文件
        if(!is_dir($path . '/'. $row)){
            unlink($path . '/'. $row);
        }else {
            delDir($path . '/'. $row);
        }
        echo $row,"<br />";
    }
    closedir($dh);
    rmdir($path);
    return true;
}
//无限极分类
//找子孙树
function subtree($arr,$id=0,$lev=1){
    static $subs = array();
    foreach($arr as $v){
        if($v['parent'] == $id){
            $v['lev'] = $lev;
            $subs[] = $v;
            subtree($arr,$v['id'],$lev+1);
        }
    }
    return $subs;
}

$tree = subtree($area,0,1);
foreach($tree as $v){
    echo str_repeat('&nbsp;&nbsp;',$v['lev']),$v['name'],'<br/>';
}





