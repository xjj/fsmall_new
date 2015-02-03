<?php
namespace suplier;

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
		if (isset($_POST['sp_uname']) && isset($_POST['sp_upass'])){
			$sp_uname = $_POST['sp_uname'];
			$sp_upass = $_POST['sp_upass'];
			$language = $_POST['language'];
			if ($language != 1){$language = 0;}
			
			$login = new md\suplier_user();
			$udata = $login -> verify($sp_uname, $sp_upass);
			if ($udata){
				
				$sp_uid = $udata['sp_uid'];
				$brand_id = $udata['brand_id'];
				$_SESSION['suplier'] = array(
					'sp_uid'   => $sp_uid,
					'sp_uname' => $sp_uname,
					'language' => $language,
					'brand_id' => $brand_id,
				);
				
				//获取品牌信息
				$brand = new md\brand();
				$brand_data = $brand -> info($brand_id);
				if ($brand_data){} else {
					cpmsg(false, $this -> LANG['ERROR_BRAND_NOT_EXIST'], -1);
				}
				
				//获取品牌折扣信息
				$brand_discount = new md\suplier_brand_discount();
				$discount = $brand_discount -> discount($brand_id);
				if ($discount && $discount > 1 && $discount < 100){} else {
					cpmsg(false, $this->LANG['ERROR_SUPLIER_BRAND_DISCOUNT'], -1);
				}
				$_SESSION['suplier']['brand_name'] = $brand_data['brand_name'];
				$_SESSION['suplier']['discount'] = $discount/100;
				
				
				$login -> setRecords($uid);
				
				cpurl('/order');
			}
		} 	
		cpurl('/login');
	}
	
	//注销
	function destroy(){
		unset($_SESSION['suplier']);
		session_destroy();
	}
}