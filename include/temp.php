<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/16
 * Time: 20:16
 */
//查找子孙树
//迭代
/*
$arr=array(
    array('id'=>1,'name'=>'北京','parent'=>0),
    array('id'=>2,'name'=>'山西','parent'=>0),
    array('id'=>3,'name'=>'海淀','parent'=>1),
    array('id'=>4,'name'=>'运城','parent'=>2),
    array('id'=>5,'name'=>'上地','parent'=>3),
    array('id'=>6,'name'=>'临猗','parent'=>4),
    array('id'=>7,'name'=>'昌平','parent'=>1),
    array('id'=>8,'name'=>'太原','parent'=>2),
    array('id'=>9,'name'=>'尖草平','parent'=>8)
);
function subtree($arr,$id){
    $res = array();
    $task = array($id);
    while(!empty($task)){
        $parent=array_pop($task);
        foreach($arr as $v){
            if($v['parent'] == $parent){
                array_push($res,$v);
                array_push($task,$v['id']);
            }
        }
    }
    return $res;
}
print_r($arr,0);
*/
$ch = curl_init("http://www.domain.com/api/index.php?test=1") ;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
echo $output = curl_exec($ch) ;

/* 写入文件 */
$fh = fopen("out.html", 'w') ;
fwrite($fh, $output) ;
fclose($fh) ;


ignore_user_abort(); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
set_time_limit(0); // 执行时间为无限制，php默认执行时间是30秒，可以让程序无限制的执行下去
$interval=24*60*60; // 每隔一天运行一次
do{
    sleep(5); // 按设置的时间等待一小时循环执行
     //其他操作

    if()


}while(true);