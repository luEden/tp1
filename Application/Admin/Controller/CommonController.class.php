<?php 
namespace Admin\Controller;
use Think\Controller;
/**
* 公共控制器
*/
class CommonController extends Controller
{
	//设置属性标签 对于
	public $is_check_rule = true;

	public $user = array();

	public $is_check_login = true;

	public function __construct()
	{
		// 执行父类的构造方法
		parent::__construct();
		// 对登录进行判断
		if ($this->is_check_login) {
			$user = cookie('_user');
			if (!$user) {
				// 没有登录
				$this->error('没有登录！', U('Login/login'));
			}
//登入后检查权限
			$this->auth();
		}
	}


	public function auth()
	{    //读取保存cookie中信息

		$user = cookie('_user');
		$this->user = S('Admin_'.$user['id']);

		if (!$this->user) {
			$this->user = $user;
			$role = D('AdminRole')->where("admin_id='%d'", $this->user['id'])->find();

			if (!$role) {
				$this->error('没有分配角色无法访问');
			}
			//得出用户的角色ID
			$this->user['role_id'] = $role['role_id'];

			if ($this->user['role_id'] == 12) {
				//超级管理员 不用效验
				$is_check_rule = false;
				//列出所有权限
				$rule_list = D('Rule')->select();
			} else {
				//非超级管理员，角色ID对应的权限ID
				$rule_ids = D('roleRule')->getRules($this->user['role_id']);

				foreach ($rule_ids as $key => $value) {
					//循环把权限ID放入一个一维数组，方便格式化
					$rule_id[] = $value['rule_id'];
				}
				//格式化数据,返回字符串
				$rule_id = implode(',', $rule_id);
				//列出权限ID对应的权限信息

				$rule_list = D('Rule')->where("id in ($rule_id)")->select();
			}

			foreach ($rule_list as $key => $value) {
				//拼接权限信息 并放入一维数组
				$this->user['rule_list'][] = $value['module_name'] . '/' . $value['controller_name'] . '/' . $value['action_name'];
				//格式化需要在导航栏显示的权限，保存在menus元素中
				if ($value['is_show'] == 1) {
					$this->user['menus'][] = $value;
				}
			}
			//比对登入用户ID的角色ID（一个用户对一个角色时）

			S('Admin_' . $user['id'], $this->user);
		}
		if($this->user['role_id']==12){
			$this->is_check_rule = false;
		}
		//非超管，就对当前访问的模块 控制器 方法进行判断
		//手动拼接后台处理页面
		if ($this->is_check_rule) {
			$this->user['rule_list'][] = 'admin/index/index';
			$this->user['rule_list'][] = 'admin/index/top';
			$this->user['rule_list'][] = 'admin/index/menu';
			$this->user['rule_list'][] = 'admin/index/main';
			$action = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);

			if (!in_array($action, $this->user['rule_list'])) {
				if (IS_AJAX) {
					$this->ajaxReturn(array('status' => 0, 'msg' => '没有权限访问'));
				} else {
					$this->error('没有权限！');
				}

			}
		}
	}
		
	
	
}
?>