<?php
//设置时区
date_default_timezone_set('Asia/Chongqing');

//设置数据库连接信息
$_db = array(
	'HOST' 	=> '210.124.164.174',		//数据库连接地183.86.217.159址
	'USER' 	=> 'zgf023',				//数据库登录用户名
	'PWD'  	=> '37213721`',				//数据库登录密码
	'NAME' 	=> 'fsmall_db'				//数据库名称
);

//基础目录
$_base_dir = '/home/wwwroot/img2.fs-mall.com/tools/download';

//图片下载目录
$_save_dir = '/home/wwwroot/img2.fs-mall.com';

//图片地址
$_base_url = 'http://img2.fs-mall.com';

$_callback = 'http://img2.fs-mall.com/tools/download/tb_callback.php';


function getdb(){
	global $_db;
	$db = new DB($_db);
	return $db;
}

function encode($str){
	return addslashes($str);
}
?>