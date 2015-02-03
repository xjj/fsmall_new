<?php
namespace suplier;

if (!defined('START')) exit('No direct script access allowed');

use model as md;

load_function('common');

class Front extends \Controller {
	
	static $loginStatus = 0;	//静态属性 - 标记是否第一次执行
	public $mod;				//大栏目
	public $col;				//小栏目
	public $navArrs;
	
	
	function __construct(){
		parent::__construct();
		
		//重新设定模版文件夹
		$this -> smarty -> setTemplateDir(TEMPLATE_PATH.'/suplier');
		
		//加载语言文件
		if ($_SESSION['suplier']['language'] === 0){
			$LANG = include_once (LANGUAGE_PATH.'/suplier/cn.php');
		} else {
			$LANG = include_once (LANGUAGE_PATH.'/suplier/kr.php');
		}
		$this -> LANG = $LANG;
		$this -> smarty -> assign('LANG', $LANG);
		
		$this->navArrs = array(
			'order'   => $LANG['NAV_ORDER'],
			'product' => $LANG['NAV_PRODUCT'],
			'account' => $LANG['NAV_ACCOUNT'],
			'password' => $LANG['NAV_PASSWORD'],
		);
		
		//导航
		$this -> smarty -> assign('navArrs', $this->navArrs);
		
		//模块与栏目
		$this -> mod = $this->class;
		$this -> col = $this->method;
		$this -> smarty -> assign('mod', $this -> mod);
		$this -> smarty -> assign('col', $this -> col);
		
		$islogin = false;
		if (!isset($_SESSION['suplier']['sp_uid'])){
			if ($this->mod != 'login' && $this->mod != 'logout'){
				cpurl('/login');
			}
		} else {
			$islogin = true;
		}
		$this -> smarty -> assign('islogin', $islogin);
	}
}