<?php
/**
 * Created by PhpStorm.
 * User: LCJ10
 * Date: 2017/12/7
 * Time: 22:16
 */

namespace Home\Controller;


class CartController extends CommonController
{
    public function addCart()
    {
        $goods_id = I('post.goods_id',0,'intval');
        $goods_count = I('post.goods_count',0,'intval');
        $goods_attr_id = I('post.attr','');

        if($goods_attr_id){
            $goods_attr_id = implode(',',$goods_attr_id);

        }
        D('Cart')->addCart($goods_id,$goods_count,$goods_attr_id);
        echo'ok';
    }
    public function index()
    {
        $data = D('Cart')->getList();
        $this->assign('data',$data);
        $total =D('Cart')->getTotal($data);
        $this->assign('total',$total);
        $this->display();
    }

    public function delCart()
    {
        $goods_id = I('get.goods_id',0,'intval');
        $goods_attr_id = I('get.goods_attr_id','');
        D('Cart')->delCart($goods_id,$goods_attr_id);
        $this->success('删除成功',U('index'));
    }
    public function clearCart()
    {
        D('Cart')->clearCart();
        $this->success('清空成功',U('index'));
    }
}