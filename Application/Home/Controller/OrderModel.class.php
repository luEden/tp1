<?php
namespace Home\Model;

use Think\Model;

class OrderModel extends Model
{
	public function order()
	{
		$cartModel = D('Cart');
		$goodsList =$cartModel->getList();

		foreach($goodsList as $key=>$value){
			if($value['info']['goods_number']<$value['goods_count']){
				$this->error = '库存不足';
				return false;
			}
		}
		$total =$cartModel->getTotal($goodsList);
		$data=$this->create();
		// 获取用户的ID
		$user = cookie('userinfo');
		$user_id = think_decrypt($user['id']);
		$data['user_id']=$user_id;
		$data['total_price']=$total['sum'];
		$data['addtime']=time();
		$order_id = $this->add($data);
		// 4、写入订单详情表
		foreach ($goodsList as $key => $value) {
			$orderDetail[]=array(
				'order_id'=>$order_id,
				'goods_id'=>$value['goods_id'],
				'goods_attr_id'=>$value['goods_attr_id'],
				'goods_count'=>$value['goods_count']
			);
		}
		D('OrderDetail')->addAll($orderDetail);
		// 5、清空购物车
		$cartModel ->clearCart();
		// 6、减少库存
		foreach ($goodsList as $key => $value) {
			D('Goods')->where("id=%d",$value['goods_id'])->setDec('goods_number',$value['goods_count']);
		}
		return $order_id;
	}
}