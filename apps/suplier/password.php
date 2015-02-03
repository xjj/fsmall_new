<?php
namespace suplier;

use model as md;

class Password extends Front {
	
	//
	function index(){
		$brand_id = $_SESSION['suplier']['brand_id'];
		$this -> smarty -> display('password.tpl');
	}
	
	//更新密码
	function update(){
		$sp_uname = trim($_POST['sp_uname']);
		$sp_upass = trim($_POST['sp_upass']);
		$sp_upass2 = trim($_POST['sp_upass2']);
		
		if (isset($_SESSION['suplier']['sp_uid'])){
			$sp_uid = $_SESSION['suplier']['sp_uid'];
		} else {
			cpurl('/login');
		}
		
		$reg = "/^[A-Za-z][A-Za-z0-9_]{3,17}$/u";
		$count = preg_match($reg, $sp_uname, $arr);
		if ($count > 0){} else {
			cpmsg(false, $this->LANG['PROFILE_PASSWORD_SPUNAME'], -1);	
		}
		
		$sp_user = new md\suplier_user();
		
		//判断是否重复
		$f = $sp_user -> isExist_spuname($sp_uname, $sp_uid, $_SERVER['suplier']['brand_id']);
		if ($f){
			cpmsg(false, $this -> LANG['PROFILE_PASSWORD_SPUNAME_EXIST'], -1);	
		}
		
		$len = strlen($sp_upass);
		if ($len < 6 || $len > 18){
			cpmsg(false, $this -> LANG['PROFILE_PASSWORD_RESET'], -1);
		}
		
		if ($sp_upass != $sp_upass2){
			cpmsg(false, $this->LANG['PROFILE_PASSWORD_CONFIRM_ERROR'], -1);
		}
		
		$f = $sp_user -> update($sp_uid, array('sp_uname' => $sp_uname,'sp_upass' => md5($sp_upass), 'update_time' => time()));
		if ($f > 0){
			cpmsg(true, $this -> LANG['PROFILE_PASSWORD_UPDATE_TRUE'], '/login');
		} else {
			cpmsg(false, $this -> LANG['PROFILE_PASSWORD_UPDATE_FALSE'], -1);
		}
	}
}