<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use model as md;

/**
 +-----------------------------
 *	登录控制器
 +-----------------------------
 */

class Login extends front {
	private $expired = '604800';
	private $path	 = '/';
	private $domain	 = '';
	
	
	//登录页面
	function index(){
		$this -> smarty -> display('login.tpl');
	}
	
	//验证页面
	function verify(){
		if (isset($_POST['uname']) && isset($_POST['upass'])){
			$uname = $_POST['uname'];
			$upass = $_POST['upass'];
			$remember = $_POST['remember'];
			
			$sys_user = new md\system_user();
			$user_data = $sys_user -> verify($uname, $upass);
			
			if ($user_data){
				$uid = $user_data['uid'];
				
				$this -> session($uid, $user_data);
				if ($remember == 1){
					$this -> cookies($uid);
				}
				$sys_user -> record($uid);
				cpurl('/');
			}
			
		} 	
		cpurl('/login');
	}
	
	//自动登录
	function autologin(){
		if (!empty($_COOKIE['admin_uid']) && $_COOKIE['admin_expired'] > time()){
			$uid = $_COOKIE['admin_uid'];
			$verify = $_COOKIE['admin_verify'];
			
			$encrypt = new \encrypt();
			$uid2  = $encrypt -> decode($verify);
			
			if (($uid2 == $uid) && isint($uid) && $uid > 0){
				$this -> session($uid);
				$this -> cookies($uid);
				
				$sys_user = new md\system_user();
				$sys_user -> record($uid);
				return true;
			}
		}
		return false;
	}
	
	//设置session
	function session($uid, $data = array()){
		if (empty($data)){
			$sys_user = new md\system_user();
			$data = $sys_user -> info($uid);	
		}

		$_SESSION['admin'] = array(
			'uid' => $data['uid'],
			'uname' => $data['uname'],
			'auths' => $data['auths'],
		);
		
	}
	
	//设置cookie
	function cookies($uid){
		$expired = time() + $this -> expired;
		
		$encrypt = new \encrypt();
		$verify  = $encrypt -> encode($uid, $this -> expired);
		
		setcookie("admin_uid",	 	$uid,   	$expired, $this -> path, $this -> domain);
		setcookie("admin_expired", 	$expired,	$expired, $this -> path, $this -> domain);
		setcookie("admin_verify",  	$verify,	$expired, $this -> path, $this -> domain);
	}
	
	//注销
	function destroy(){
		unset($_SESSION['admin']);
		session_destroy();
		
		$seconds = time()-3600;
		
		setcookie("admin_uid",   	'', $seconds, $this -> path, $this -> domain);
		setcookie("admin_expired",	'', $seconds, $this -> path, $this -> domain);
		setcookie('admin_verify', 	'', $seconds, $this -> path, $this -> domain);
	}
}