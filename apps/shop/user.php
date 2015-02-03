<?php
namespace shop;

use model as md;

/**
 +------------------------------
 *	用户控制器
 +------------------------------
 */

class User extends Front {
	
	function __construct(){
		parent::__construct();
		
		$user_data = $this -> userData();
		$this -> smarty -> assign('user_data', $user_data);
	}
	
	//获取用户信息
	function userData(){
		//判断是否登录
		if (!isset($_SESSION['uid'])){
			$login = new login();
			$login -> index();
			exit();
		}
		
		//获取用户信息
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		if ($user_data){} else {
			cpmsg(false, '该用户不存在或已被删除！', -1);
		}
		
		
		return $user_data;	
	}
	
	//个人中心首页
	function index(){
		$this -> smarty -> display('user/index.tpl');	
	}
	
	//帐号资料
	function profile(){
		$prof = new user_profile();
		$prof -> index();
	}
	
	//更新密码
	function password(){
		$pass = new user_password();
		$pass -> index();
	}
	
	//设置头像
	function head(){
		$head = new user_head();
		$head -> index();
	}
	
	//收货地址
	function address(){
		$addr = new user_address();
		
		$p = $this -> params[0];
		if ($p == 'add'){
			$addr -> add();
			exit();
		} elseif ($p == 'edit'){
			$addr -> edit();
			exit();
		} elseif ($p == 'del'){
			$addr -> del();
			exit();
		} elseif ($p == 'setdefault'){
			$addr -> setDefault();	
		} else {
			$addr -> items();	
		}
	}
	
	//退换货明细
	function refund(){
		$refund = new user_refund();
		$refund -> index();
	}
	
	//更新邮箱
	function email(){
		$user_email = new user_email();
		$user_email -> index();	
	}
	
	//修改用户名
	function uname(){
		$user_uname = new user_uname();
		$user_uname -> index();	
	}
}