<?php 
namespace Admin\Controller;

class AdminController extends CommonController 
{
	public $model;
	public function model()
	{
		if(!$this->model)
			$this->model = D('Admin');
		return $this->model;
	}
	//添加用户和关联角色
	public function add()
	{
		if(IS_GET){
			$res = D('Role')->select();
			if($res){
				$this->assign('res',$res);
				$this->display();
			}
			
			
		}else{
			$name = I('post.name');
			$pwd= I('post.pwd');
			$role_name= I('post.role_id');
			$res = $this->model()->insert($name,$pwd,$role_name);

			if($res ===false)
				$this->error($this->model()->getError());
			
				$this->success('ok');

		}
	}

	public function index()
	{
		if(IS_GET){

			$info = $this->model()->listData();
			$this->assign('info',$info);
			$this->display();
		}
		
	}

	public function dels()
	{
		if(IS_GET){
			$id = I('get.id');
			if($this->model()->delete($id)){
				$this->success('删除成功',U('index'));
			}

		}
	}

	public function edit()
	{
		if(IS_GET){
			$id = I('get.id',o,'intval');
			$list = $this->model()->lists($id);
			//dump($list);exit;
			$info = D('Role')->select();
			$this->assign('info',$info);
			$this->assign('list',$list);
			$this->display();
		}
		if(IS_POST){
			$username = I('post.username');
			$pwd = I('post.pwd');
			$role_name = I('post.role_id');
			$id=I('post.id');


			$res = $this->model()->update($username,$pwd,$role_name,$id);
			if($res ===false)
				$this->error($this->model()->getError());
			$this->success('ok',U('index'));

		}

	}
}


 ?>