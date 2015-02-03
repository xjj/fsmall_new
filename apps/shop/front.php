<?php
namespace shop;

if (!defined('START')) exit('No direct script access allowed');

use model as md;

load_function('common');

/**
 +------------------------------
 *	基础控制器
 +------------------------------
 */
class Front extends \Controller {
	
	public $islogin = false;		//是否登录
	static $status = 0;
	
	function __construct(){
		parent::__construct();
		
		
		
		//重新设定模版文件夹
		$this -> smarty -> setTemplateDir(TEMPLATE_PATH.'/default');
		$this -> smarty -> assign('template', 'default');
		
		//防止陷入死循环
		if (self::$status == 0){
			self::$status = 1;
			
			//判断是否登录及自动登录
			if (!isset($_SESSION['uid'])){
				//自动登录
				$login = new login();
				$this -> islogin = $login -> autologin();
			} else {
				$this -> islogin = true;
			}
			$this -> smarty -> assign('islogin', $this->islogin);
		}
	}
}