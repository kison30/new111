<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 14:41
 * 作用：框架初始化
 * 过滤参数，用递归的方式过滤$_GET,$_POST,$_COOKIE,
 * 设置报错级别
 */
//初始化当前的绝对路径
defined("ACC") || exit("ACC Denied");
define('ROOT',str_replace('\\','/',dirname(__DIR__)).'/');
define('DEBUG',true);

require(ROOT . 'include/lib_base.php');

//自动加载类文件
function __autoload($class){
    if(strtolower(substr($class,-5)) == 'model'){
        require(ROOT . 'Model/' . $class . '.class.php');
    }else if(strtolower(substr($class,-4)) == 'tool'){
        require(ROOT . 'tool/' . $class . '.class.php');
    }else{
        require(ROOT . 'include/' . $class . '.class.php');
    }
}
//过滤参数，用递归的方式过滤$_GET,$_POST,$_COOKIE,暂时不会
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);
//开启session
session_start();
//设置报错级别
if(defined('DEBUG')){
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}
