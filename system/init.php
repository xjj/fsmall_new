<?php
if (!defined('START')) exit('No direct script access allowed.');

/**
 +-----------------------------
 *	框架初始化文件
 +-----------------------------
 */

//是否开启错误调试
if (defined("DEBUG") && DEBUG == TRUE){
	error_reporting(E_ALL ^ E_NOTICE);
} else {
	error_reporting(0);
}

//设置当前时区
date_default_timezone_set("Asia/Chongqing");

//开启SESSION
session_start();

//载入配置文件
require (ROOT_PATH.'/config/config.php');

//载入框架函数
require (SYSTEM_PATH.'/common.php');

//开启类自动加载
spl_autoload_register('autoload');

//加载框架类
load_class('db');
load_class('smarty');
load_class('controller');
load_class('router');

//调用控制器
$ctl = new Controller();
$ctl -> init();