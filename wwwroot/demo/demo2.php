<?php
$key = '422da0f618ed006bb505acd8a67c6e55 ';
$com = 'yd';
$no = '1901903437918';
$url= 'http://v.juhe.cn/exp/index?key='.$key.'&com='.$com.'&no='.$no;
$res = file_get_contents($url);
$qq = json_decode($res);
echo '<pre>';
var_dump($qq);