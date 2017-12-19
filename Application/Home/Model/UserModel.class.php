<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model
{
	protected $fields = array('id','username','password','tel');

    public function regist($data)
    {
        $info = $this->where(array('username'=>$data['username']))->find();
        if($info){
            $this->error='用户名已被注册';
            return false;
        }
        if($this->where("tel=%d",$data['tel'])->find()){
        	$this->error='手机号已被注册';
        	return false;
        }
        $sessionData = seesion('code');
        if($sessionData['time']+$sessionData['limit']<time()){
        	$this->error = '验证码已经过期';
        	return false;
        }
        if(I('post.code') != $sessionData['code']){
        	$this->error ='验证码不正确';
        	return false;
        }
            $data['password'] = md5($data['password']);
            return $this->add($data);
        
    }


}