<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $tree = D('Admin/Category')->getTree();
        $this->assign('tree',$tree);
    }

    public function checkLogin()
    {
    	$user = cookie('userinfo');
    	if(!$user){
    		this->error('还未登入',U('user/login'));
    	}
    	$user_id = think_decrypt($user['id']);
    	if(!$user_id){
    		$this->error('没有登入',U('user/login'));
    	}
    }
}