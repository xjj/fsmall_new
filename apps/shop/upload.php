<?php
namespace shop;

use model as md;

/**
 +-------------------------------
 *	上传控制器
 +-------------------------------
 */
class Upload extends front {
	
	function __construct(){
		parent::__construct();
		
		if (!isset($_SESSION['uid'])){
			echo json_encode(array(
				'error' => 101,
				'message' => '您需要登录！'
			));
			exit();
		}
	}
	
	//上传图片
	function picture(){
		$adm_uid = $_SESSION['admin']['uid'];
		$uid = $_SESSION['uid'];
		
		$pic = new md\upload_picture();
		$ret = $pic -> upload($_FILES['pic'], $uid, $adm_uid);
		echo json_encode($ret);
	}
} 