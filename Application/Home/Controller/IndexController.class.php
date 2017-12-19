<?php
namespace Home\Controller;

class IndexController extends  CommonController
{
    public function index()
    {
        $goods = D('Goods');
        $this->recs = $goods->getRecGoods('is_rec');
        $this->news = $goods->getRecGoods('is_new');
        $this->hots = $goods->getRecGoods('is_hot');

        $this->assign('is_show',1);
        $this->display();
    }

    public function test1()
    {
        $data = I('get.test');
        echo $data;
        $str = think_encrypt($data) . '<br/>';
        echo $str;
        $str1 = think_decrypt($str) . '<br>';
        echo $str1;
    }


}