<?php 
namespace Admin\Model;
use Think\Model;

/**
* 后台管理员模型
*/
class AdminModel extends Model
{
	// 指定主键字段 如果主键字段为id可以省略
	protected $pk = 'id';
	// 自定义数据表的字段
	protected $fields=array('id','username','password','salt');
	// 定义字段映射
	protected $_map=array(
		'name'=>'username',
		'pwd'=>'password'
	);
	// 定义自动验证规则
	protected $_validate=array(
		array('username','require','用户名不能为空!'),
		array('password','checkLength','密码长度不合适!',1,'callback'),
	);
	// 检查密码的长度
	public function checkLength($str){
		if(strlen($str)<4){
			return false;
		}
		return true;
	}
	// 实现登录操作
	public function login($username,$password,$code)
	{
		// 1、检查验证码
		$obj = new \Think\Verify();	
		if(!$obj->check($code)){
			// 验证码不对
			$this->error = '验证码不匹配';//设置错误信息
			return false;
		}
		// 2、查询用户
		$userInfo = $this->where("username='%s'",$username)->find();
		if(!$userInfo){
			$this->error = '用户名错误';//设置错误信息
			return false;
		}
		// 3、比对密码
		if($userInfo['password'] != md6($password,$userInfo['salt'])){
			$this->error = '密码错误';//设置错误信息
			return false;
		}
		// 4、保存登录状态  考虑保存登录状态
		$isremember = I('post.remember');//接受是否需要保存登录状态
		$expire = 0;//默认不需要保存登录状态
		if($isremember){
			// 需要保存
			$expire = 3600*24*3;
		}
		// 可以考虑使用cookie加密 更加安全
		unset($userInfo['password']);
		unset($userInfo['salt']);
		//设置cookie
		cookie('_user',$userInfo,$expire);
		return true;
	}
	//添加用户和关联的角色
	public function insert($name,$pwd,$role_id)
	{	
		
		$res = $this->where("username='$name'")->find();

		
		if($res){
			$this->error = '用户名不能重复!';
			return false;
		}

		$salt = rand(111111,9999999);

		$password = md6($pwd,$salt);


		$info = $this->add(array('username'=>$name,'password'=>$password,'salt'=>$salt));
		
		$this->startTrans();
		if(!$info){
			$this->error='添加失败';
			$this->rollback();
			return false;
		}
		

		$info_id = D('AdminRole')->add(array('admin_id'=>$info,'role_id'=>$role_id));
		if(!$info_id){
			$this->error='出错拉';
			$this->rollback();
			return false;
		}
		$this->commit();
		return true;
	

	}

	public function listData()
	{
		$p = I('get.p');

		$pagesize = 3;
		$count = $this->count();

		$page = new \Think\Page($count,$pagesize);

		$pageStr = $page->show();

		$list = $this->alias('a')->field('a.id,a.username,c.role_name')->join('left join shop_admin_role b on a.id = b.admin_id left join shop_role c on c.id = b.role_id')->page($p,$pagesize)->select();
		return array('list'=>$list,'pageStr'=>$pageStr);
	}

	public function lists($id)
	{
		$list = $this->alias('a')->field('a.id,a.username,b.role_id,c.role_name')->join('left join shop_admin_role b on a.id = b.admin_id left join shop_role c on c.id = b.role_id')->page($p,$pagesize)->where('a.id='.$id)->find();
		return $list;
	}
	//修改用户名 密码 角色
	public function update($username,$pwd,$role_id,$id)
	{	
		$info = $this->where(array('username'=>$username,'id'=>array('neq',$id)))->find();
		
		if($info){
			$this->error='用户名已存在';
			return false;
		}
		$info_id = $this->where('id='.$id)->find();
		
		if(strlen($pwd)<=4){
			$this->error = '密码长度不能小于4位';
			return false;
		}else{
			$pwd_new = md6($pwd,$info_id['salt']);
			$res = $this->where('id='.$id)->save(array('username'=>$username,'password'=>$pwd_new));
			
		}
		if($role_id){
			$role = D('admin_role')->where('admin_id='.$id)->find();
			
				$role_res = D('admin_role')->where('admin_id='.$id)->save(array('role_id'=>$role_id));
				return true;
				

		}
		



	}

}
?>