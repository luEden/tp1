<?php
namespace Home\Controller;


class GoodsController extends CommonController
{
    public function index()
    {
        $goods_id = I('get.goods_id',0,'intval');
        if($goods_id<0){

            $this->redirect('index/index');
        }
        $model = D('Goods');
        $res = $model->where("id='%d'",$goods_id)->find();

        if(!$res){
            $this->redirect('index/index');
        }else{
            $res['goods_body'] = htmlspecialchars_decode($res['goods_body']);
            $this->assign('res',$res);
            $img = D('GoodsImg');
            $imgs = $img->where("goods_id='%d'",$goods_id)->select();
            $this->assign('imgs',$imgs);
            $attr = D('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_type')->
            join('left join shop_attribute b on a.attr_id = b.id')
                ->where('a.goods_id=%d',$goods_id)->select();
            foreach($attr as $key=>$value){
                if($value['attr_type']==1){
                    $unique[]=$value;
                }else{

                    $sigle[$value['attr_id']][]=$value;
                }
            }
            $this->assign('sigle',$sigle);
            $this->assign('unique',$unique);
            $this->display();
        }


    }


}