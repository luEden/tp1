<?php
namespace Home\Controller;

class OrderController extends CommonController
{
	public function check()
	{
		$this->checkLogin();
		$data = D('Cart')->getList();
		$this->assign('data',$data);

		$total =D('Cart')->getTotal($data);
		$this->assign('total',$total);
		$this-display();

	}

	public function order()
	{
		$this->checkLogin();
		$model=D('Order');
		$res= $model->order();
		if($res===false){
			$this->error($model->getError());
		}
		echo 'OK';
	}
}