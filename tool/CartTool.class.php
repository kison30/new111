<?php
/**
 * Created by PhpStorm.
 * User: Kison
 * Date: 2016/1/6
 * Time: 16:30
 * 购物车类
 * 技术选型：session+单例
 * 功能分析
 * 判断某个商品是否存在
    添加商品
    删除商品
    修改商品的数量

    某商品数量加1
    某商品数量减1


    查询购物车的商品种类
    查询购物车的商品数量
    查询购物车里的商品总金额
    返回购物里的所有商品

    清空购物车
 */
defined('ACC') || exit('ACC Denied');
class CartTool{
    private static $ins = null;
    private $items = array();

    final protected function __construct(){

    }
    final protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    //获取实例
    protected static function getIns(){
        if(!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    //把购物车的单例对象放到Session里
    public static function getCart(){
        if(!isset($_SESSION['cart']) || !($_SESSION['cart']) instanceof self){
            $_SESSION['cart'] = self::getIns();
        }

        return $_SESSION['cart'];
    }
    /*
     * 添加商品
     * parm int $id 商品主键
     * parm string $name 商品名称
     * parm float $price 商品价格
     * parm int $num 购物数量
     */
    public function addItem($id,$name,$price,$num=1){
        if($this->hasItem($id)){  //如果商品已存在，直接加其数量
            $this->incNum($id,$num);
            return;
        }

        $item = array();
        $item['name'] = $name;
        $item['price'] = $price;
        $item['num'] = $num;
        $this->items[$id] = $item;
    }
    /*
     * 修改购物车数量
     * parm int $id 商品主键
     * parm int $num 某个商品修改后的数量
     */
    public function modNum($id,$num=1){
        if(!$this->hasItem($id)){
            return false;
        }
        $this->items[$id]['num'] += $num;
    }

    /*
     * 商品数量加1
     */
    public function incNum($id,$num=1){
        if($this->hasItem($id)){
            $this->items[$id]['num'] += $num;
        }
    }
    /*
     * 商品数量减1
     */
    public function decNum($id,$num=1){
        if($this->hasItem($id)){
            $this->items[$id]['num'] -= $num;
        }

        //如果减少后，数量为0，则将该商品从购物车删去
        if($this->items[$id]['num'] < 1){
            $this->delItem($id);
        }
    }

    /*
     * 判断商品是否存在
     */
    public function hasItem($id){
        return array_key_exists($id,$this->items);
    }
    /*
     * 删除商品
     */
    public function delItem($id){
        unset($this->item[$id]);
    }

    /*
     * 查询购物车种类
     */
    public function getCnt(){
        return count($this->items);
    }
    /*
     * 查询购物车商品数量
     */
    public function getNum(){
        if($this->getCnt() == 0){
            return 0;
        }
        $sum = 0;
        foreach($this->items as $item){
            $sum += $item['num'];
        }
        return $sum;
    }
    /*
     * 查询购物车中的总金额
     */
    public function getPrice(){
        if($this->getCnt() == 0){
            return 0;
        }

        $price = 0.0;
        foreach($this->items as $item){
            $price += $item['num'] * $item['price'];
        }

        return $price;
    }
    /*
     * 返回购物车所有商品
     */
    public function all(){
        return $this->items;
    }
    /*
     * 清空购物车
     */
    public function clear(){
        $this->items = array();
    }
}