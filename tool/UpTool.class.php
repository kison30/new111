<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/25
 * Time: 17:26
 * 单文件上传类
 */

defined('ACC') || exit('ACC Denied');
/*
 * 上传文件
 * 配置允许的后缀
 * 配置允许的大小
 * 随机生成目录
 * 随机生成文件名
 * 获取文件后缀
 * 良好的报错支持
 */
class UpTool{
    protected $allowExt = 'jpg,jpeg,gif,bmp,png';
    protected $maxSize = 1; //1M M 为单位
    protected $errno = 0; // 错误代码
    protected $error = array(
        0 => '无错',
        1 => '上传文件超出系统限制',
        2 => '上传文件大小超出网页表单页面',
        3 => '文件只有部分被上传',
        4 => '没有文件被上传',
        6 => '找不到临时文件',
        7 => '文件写入失败',
        8 => '不允许的文件后缀',
        9 => '文件大小超出的类的允许范围',
        10 => '创建目录失败',
        11 => '移动失败'
    );

    public function up($key){
        if(!isset($_FILES[$key])){
            return false;
        }

        $f = $_FILES[$key];

        //检验上传有没有成功
        if($f['error']){
            $this->errno = $f['error'];
            return false;
        }

        //获取后缀
        $ext = $this->getExt($f['name']);
        //检查后缀
        if(!$this->isAllowExt($ext)){
            $this->errno = 8;
            return false;
        }
        //通过
        //创建目录
        $dir = $this->mk_dir();
        if($dir == false){
            $this->errno = 10;
            return false;
        }
        //生成随机文件名
        $newname = $this->randName() . '.' . $ext;
        $dir = $dir . '/' .$newname;

        //移动
        if(!move_upload_file($f['tmp_name'],$dir)){
            $this->errno = 11;
            return false;
        }
       return str_replace(ROOT,'',$dir);

    }

    public function getErr(){
        return $this->error[$this->errno];
    }

    /*
     * parm string $exts 允许的后缀
     */
    public function setExt($exts){
        $this->allowExt = $exts;
    }

    public function setSize($num){
        $this->maxSize = $num;
    }

    /*
     * parm String $ext 文件后缀
     * return bool
     * 防止大小写问题
     */

    protected function isAllowExt($ext){
        return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));
    }
    //检查文件大小
    protected function isAllowSize($size){
        return $size <= $this->maxSize * 1024 * 1024;
    }

    /*
     * 按日期创建目录的方法
     */

    protected function mk_dir(){
        $dir = ROOT . 'data/images/' . date('Ym/d');
        if(is_dir($dir) || mkdir($dir,0777,true)){
            return $dir;
        }else{
            return false;
        }
    }

    /*
     * 生成随机文件名
     */

    protected function randName($length = 6){
        $str = 'abcdefghijklmnopqrstuvwxyz123456789';
        return substr(str_shuffle($str),0,$length);
    }



}