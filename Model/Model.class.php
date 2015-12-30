<?php
/**
 * Created by PhpStorm.
 * User: kison
 * Date: 2015/12/19
 * Time: 14:03
 */
defined('ACC') || exit('ACC Denied');
class Model{
    protected $table = null;
    protected $db = NUll;
    protected $pk = '';
    protected $fields =array();
    protected $_auto = array();
    protected $_valid = array();
    protected $error = array();
    /*
     *   array('is_hot','value',0),
        array('is_new',vlaue,0),
        array('is_best',value,0),
        array('add_time','function','time')
     */

    public function __construct(){
        $this->db = mysql::getIns();
    }

    public function table($table){
        $this->table = $table;
    }

    /*
     * 负责把传下来得数组，清除掉不用的单元
     * 留下与表的字段对应单元
     * 思路：
     * 循环数组，分别判断其Key，是否是表的字段
     * 自然，要先有表的字段
     * 表的字段可以desc表名来分析
     * 也可以手动写好
     * 以tp为例，两者都行
     * 先手动写
     */
    public function _facade($array=array()){
         $data = array();
        foreach($array as $k=>$v){
            if(in_array($k,$this->fields)){
                $data[$k] = $v;
            }
        }
        return $data;
    }
    /*
     * 自动填充
     * 负责把表中需要的值，而$_POST又没传的字段，附上值
     * 比如$_POST里没有add_time,即商品时间，
     * 则自动把time()的返回值赋过来
     */
    public function _autoFill($data){
        foreach($this->_auto as $k=>$v){
            if(!array_key_exists($v[0],$data)){
                switch ($v[1]){
                    case 'value': $data[$v[0]] = $v[2];
                        break;
                    case 'function':$data[$v[0]] = call_user_func($v[2]);
                        break;
                }
            }
        }
        return $data;
    }
    /*
     *  格式 $this->_valid = array(
     *          array('要验证的字段名'，0/1/2(验证场景)，'报错提示'，'require/in(某几种情况)/between(范围)/length(某个范围)'，'参数')；
     * )
     * array('goods_name',1,'必须有商品名','require'),
     * array('cat_id',1,'栏目ID必须是整型值'，'number'),
     * array('is_new',0,'is_new只能是0或1','in','0,1'),
     * array('goods_breif',2,'商品简介只能在10到100个字符'，'length','10,100')
     */
    public function _validate($data){
        if(empty($this->_valid)){
            return true;
        }

        $this->error = array();
        foreach($this->_valid as $k=>$v){
            switch($v[1]){
                case 1 :
                    if(!isset($data[$v[0]])){
                        $this->error[] = $v[2];
                        return false;
                    }
                    if(!$this->check($data[$v[0]],$v[3])){
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;
                case 0 :
                    if(isset($data[$v[0]])){
                        if($this->chesk($data[$v[0]],$v[3],$v[4])){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;
                case 1 :
                    if(isset($data[$v[0]]) && !empty($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v[3],$v[4])){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
            }
        }
        return true;
    }

    public function getErr(){
        return $this->error;
    }

    public function check($value,$rule='',$parm=''){
        switch($rule){
            case 'require':
                return !empty($value);
            case 'number' :
                return is_numeric($value);
            case 'in' :
                $tmp = explode(',',$parm);
                return in_array($value,$tmp);
            case 'between' :
                list($min,$max) = explode(',',$parm);
                return $value >= $min && $value <= $max;
            case 'length' :
                list($min,$max) = explode(',',$parm);
                return strlen($value) >= $min && strlen($value) <= $max;
            default :
                return false;
        }
    }
    /*
     *  parm array $data
     *  return bool
     */
    public function add($data){
        return $this->db->autoExecute($this->table,$data);
    }
    /*
     * parm int $id 主键
     * return int 影响的行数
     */
    public function delete($id){
        $sql = 'delete from ' .$this->table . 'where '.$this->pk . '='.$id;
        if($this->db->query($sql)){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }
    /*
     * parm array $data
     * parm int $id
     * return int 影响行数
     */
    public function update($data,$id){
        $rs = $this->db->autoExecute($this->table,$data,'update','where'.$this->pk.'='.$id);
        if($rs){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }
    /*
     * return array
     */
    public function select(){
        $sql = 'select * from '.$this->table;
        return $this->db->getAll($sql);
    }
    /*
     * parm int $id
     * return array
     */
    public function find($id){
        $sql = 'select * from ' . $this->table .'where id = ' .$id;
        return $this->db->getRow($sql);
    }
}