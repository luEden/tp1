<?php
namespace Admin\Controller;

class RoleController extends CommonController
{	
	public $_model;

	public function model()
	{
		if(!$this->_model){
			$this->_model = D('Role');
		}
		return $this->_model;
	}
	//添加角色
	public function add(){
		if(IS_GET){
			
			$this->display();
			exit;
		}
		
		$data = $this->model()->create();

		if(!$data){
			$this->error($this->model()->getError());
		}
		if($this->model()->add($data))
			$this->success('ok',U('add'));
			

	}
	//显示角色列表
	public function index()
	{
		if(IS_GET){
			$info = $this->model()->listData();
			$this->assign('info',$info);
			$this->display();
		}
	}
	//删除角色
	public function dels(){
		if(IS_GET){
			$id = I('get.id');
		$res = $this->model()->dels($id);
		if($res ===false)
			$this->error($this->model()->getError());
		$this->success('ok',U('index'));

		}
		
	}
	//编辑角色
	public function edit()
	{
		if(IS_GET){
			$id = I('get.id');

			$info = $this->model()->where('id=%d',$id)->find();
			if($info){
				$this->assign('info',$info);
				$this->display();
			}
		}else{

				$data = $this->model()->create();
				dump($data);exit;
						$id= I('post.id');
						if(!$data)
							$this->error($this->model()->getError());
						$this->model()->where('id=%d',$id)->save($data);
						$this->success('ok',U('index'));

		}
					
	}
	public function disfetch()
	{
		if(IS_GET){
			// 获取当前角色拥有的权限信息
			$role_id = I('get.role_id',0,'intval');
			$hasRules = D('RoleRule')->getRules($role_id);
			// 将已经拥有的权限id保存到数组中
			foreach ($hasRules as $key => $value) {
				$hasRulesIds[]=$value['rule_id'];
			}
			$hasRulesIds = implode(',', $hasRulesIds);
			$this->assign('hasRules',$hasRulesIds);

			// 获取所有的权限信息
			$rules = D('Rule')->getTree();
			$this->assign('rules',$rules);

			$this->display();exit();
		}
		// 实现权限提交入库
		// 接受提交的角色ID以及权限id信息
		$role_id = I('post.role_id',0,'intval');
		$rule = I('post.rule');
		D('RoleRule')->disfetch($role_id,$rule);
		$this->success('ok');
	}




}