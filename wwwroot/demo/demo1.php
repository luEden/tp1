<?php
$username = $_REQUEST['username']?$_REQUEST['username']:'';
$password = $_REQUEST['password']?$_REQUEST['password']:'';
if(!$username || !$password){
	echo json_encode(array('Status'=>0,'Msg'=>'fail'));
	exit;
}
$dsn = ('mysql:host=127.0.0.1;port=3306;dbname=shop;charset=utf8');
$pdo = new PDO($dsn,'root','aa');

$password = md5($password);
$sql= "select *from shop_user where (username='$username' and password='$password')";
$res = $pdo->query($sql);
$row = $res->fetch(PDO::FETCH_ASSOC);

if(!$row){
	echo json_encode(array('Status'=>0,'Msg'=>'fail'));
	exit;
}else{
	echo json_encode(array('Status'=>1,'Msg'=>'ok','data'=>$row));
}
