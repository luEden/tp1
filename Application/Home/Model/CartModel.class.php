<?php
/**
 * Created by PhpStorm.
 * User: LCJ10
 * Date: 2017/12/7
 * Time: 22:16
 */

namespace Home\Model;

use Think\Model;
class CartModel extends Model
{
    public $user_id;
    protected $fields= array('id','user_id','goods_id','goods_attr_id','goods_count');
    public function __construct()
    {
        parent::__construct();
        $user = cookie('userinfo');
        if(!$user){
            $this->user_id=think_decrypt($user['id']);
        }
    }

    public function addCart($goods_id,$goods_count,$goods_attr_id)
    {
        if($this->user_id){
            $map = array(
                'goods_id'=>$goods_id,
                'goods_attr_id'=>$goods_attr_id,
                'user_id'=>$this->user_id
            );
            $res = $this->where($map)->find();
            if($res){
                $this->where($map)->setInc('goods_count',$goods_count);

            }else{
                $map['goods_count']=$goods_count;
                $this->add($map);
            }
        }else{
            $cart = cookie('cart');
            $key = $goods_id.'-'.$goods_attr_id;
            if(array_key_exists($key,$cart)){
                $cart[$key]+=$goods_count;
            }else{
                $cart[$key]=$goods_count;
            }
            cookie('cart',$cart);

        }
    }

    public function getList()
    {
        if($this->user_id){
            $data = $this->where('user_id=%d',$this->user_id)->select();
        }else{
            $cart = cookie('cart');

            foreach ($cart as $key=>$value){
                $tmp = explode('-',$key);
                $data[] = array(
                    'goods_id'=>$tmp[0],
                    'goods_attr_id'=>$tmp[1],
                    'goods_count'=>$value
                );
            }
        }
        foreach ($data as $key=>$value){
            $data[$key]['info']=D('Goods')->where('id=%d',$value['goods_id'])->find();
            $data[$key]['attr']=D('Goods')->query("select a.attr_value,b.attr_name from shop_goods_attr a left join shop_attribute b on a.attr_id
          =b.id where a.id in ({$value['goods_attr_id']})");
        }
        return $data;
    }

    public function getTotal($data)
    {
        $count =$sum = 0;
        foreach ($data as $key =>$value){
            $count+=$value['goods_count'];
            $sum+=$value['goods_count']*$value['info']['shop_price'];
        }
        return array('count'=>$count,'sum'=>$sum);
    }

    public function delCart($goods_id,$goods_attr_id)
    {
        if($this->user_id){
            $map = array(
                'user_id'=>$this->user_id,
                'goods_id'=>$goods_id,
                'goods_attr_id'=>$goods_attr_id,
            );
            $this->where($map)->delete();

        }else{
            $cart = cookie('cart');
            $key = $goods_id.'-'.$goods_attr_id;
            unset($cart[$key]);
            cookie('cart',$cart);
        }
    }
    public function clearCart()
    {
        if($this->user_id){
            $this->where('user_id=%d',$this->user_id)->delete();
        }else{
            cookie('cart',null);
        }
    }

    public function cookie2db()
    {
        if(!$this->user_id){
            return false;
        }
        $cart = cookie('cart');
        foreach($cart as $key =>$value){
            $tmp = explode('-',$key);
            $map = array(
              'goods_id'=>$tmp[0],
                'goods_attr_id'=>$tmp[1],
                'user_id'=>$this->user_id,
            );
            if($this->where($map)->find()){
                $this->where($map)->setInc('goods_count',$value);
            }else{
                $map['goods_count'] = $value;
                $this->add($map);
            }
        }
        cookie('cart',null);
    }
}