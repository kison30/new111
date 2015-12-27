<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/10
 * Time: 19:00
 * 所有由用户直接访问到的这些页面
 * 都得先加载init.php
 */
define("ACC","access");
require('./include/init.php');

$mysql= mysql::getIns();
/*
ignore_user_abort(); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
set_time_limit(0); // 执行时间为无限制，php默认执行时间是30秒，可以让程序无限制的执行下去
$interval=24*60*60; // 每隔一天运行一次
do{
    sleep(5); // 按设置的时间等待一小时循环执行
    //其他操作

    $sql="select * from test";
    $res = $mysql->getAll($sql);
    foreach($res as $arr){
        if($arr['status'] == 1){
            $name = $arr['name'];
            $sql = "update test set status = 0 where name = '$name'";
            $mysql->query($sql);
        }
    }


}while(true);
*/

ignore_user_abort ( TRUE );
set_time_limit ( 0 );
$interval = 10;
$stop = 1;
do {
    if( $stop == 10 ) break;

    file_put_contents('liuhui.php',' Current Time: '.date("Y-m-d H:i:s",time()).' Stop: '.$stop);
    $stop++;
    sleep ( $interval );
} while ( true );





