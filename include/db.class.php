<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 14:54
 * 数据库类
 */
abstract class db{
    //连接服务器
    //parms $h 服务器地址
    //parms $u 用户名
    //parms $p 密码
    //return bool
    public abstract function connect($h,$u,$p);
    /*
     * 发送查询
     * parms $sql 发送的SQL语句
     * return mixed bool/resource
     */
    public abstract function query($sql);
    /*
     * 查询单行数据
     * parms $sql select型语句
     * return array/bool
     */
    public abstract function getRow($sql);
    /*
     * 查询单个数据
     * parms $sql select型语句
     * return array/bool
     */
    public abstract function getOne($sql);
    /*
        * 查询单行数据
        * parms $sql select型语句
        * return array/bool
        */
    public abstract function getAll($sql);
    /*
     * 自动执行insert/update语句
     * parms $sql select型语句
     * return array/bool
     *
     * $this->autoExecute('user',array('username'=>'zahngsan','email'=>'zhang@163.com'),'insert')
     * 将发生自动形成 insert into user (username,email) values ('zhangsan','zhang@163.com');
     */
    public abstract function autoExecute($table,$data,$act='insert',$where='');

}