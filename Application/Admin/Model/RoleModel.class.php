<?php
namespace Admin\Model;

use Think\Model;

class RoleModel extends Model
{
	protected $fields = array('id','role_name');

	protected $_validate = array(
		array('role_name','','名称不能重复',1,'unique',1)
		);
	//分页
	public function listData(){
		$p = I('get.p',0,'intval');
		$pagesize = 1;
		$count = $this->count();

		$page = new \Think\Page($count,$pagesize);
		$pageStr = $page->show();

		$list = $this->page($p,$pagesize)->select();

		if($list ===false)
			$this->error='操作错误';
		return array('list'=>$list,'pageStr'=>$pageStr);
	}
	//删除角色
	public function dels($id)
	{
		 return $list = $this->where('id=%d',$id)->delete();
		
	}
	
}