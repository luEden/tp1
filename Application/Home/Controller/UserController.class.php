<?php
namespace Home\Controller;

class UserController extends    CommonController
{
    public function regist()
    {
        if(IS_GET){
            $this->display();
        }else{
            $model = D('User');
            $data = $model->create();
            $res = $model->regist($data);

            if($res ===false)
                $this->error($model->getError());
            $this->success('ok');

        }

    }

    public function login()
    {
        if(IS_GET){
            $this->display();
            exit;
        }
        $model = D('User');
        $username = I('post.username');
        $password = I('post.password');

        $map = array(
            'username'=>$username,
            'password'=>md5($password)

        );

        $userinfo = $model->where($map)->find();

        if(!$userinfo){
            $this->error($model->getError());
        }
            unset($userinfo['password']);
            $userinfo['id'] = think_encrypt($userinfo['id']);

            $user = cookie('userinfo',$userinfo);
            $model->cookie2db();
            $this->redirect('index/index');
        }

        public function sendSms()
        {
        	$tel = I('request.tel');

        	if(!$tel ||strlen($tel)!=11){
        		$this->ajaxReturn(array('status'=>0,'msg'=>'error'));
        	}
        	$code = rand(1111,9999);
        	$limit = 600;
        	$res = send_sms($tel,array($code,$limit/60),1);

        	if(!$res){
        		$this->ajaxReturn(array('status'=>0,'msg'=>'发送失败'));
        	}

        	$data = array(
        		'code'=>$code,
        		'limit'=>$limit,
        		'time'=>time()
        		);
        	session('code',$data);
        	$this->ajaxReturn(array('status'=>1,'msg'=>'ok'));

        }

        public function test(){
        	dump(session('code'));
        }


}