<?php
namespace shop;

use model as md;

/**
 *	设置头像
 */
class User_Head extends front {
	
	function index(){
		if (isset($_POST['submit'])){
			$this -> submit();
			exit();
		}
		
		$this -> smarty -> assign('title', '设置头像');
		$this -> smarty -> display('user/head.tpl');
	}
	
	//裁切并保存数据
	private function submit(){
		$pic = $_POST['pic'];	//图片地址
		
		if (empty($pic)){
			cpmsg(false, '您裁切的图片不存在，请先上传图片，再提交！');
		}
		
		$x  = intval($_POST['x']);		//X点坐标
		$y  = intval($_POST['y']);		//Y点坐标
		$xw = intval($_POST['xw']);		//裁切框宽
		$xh = intval($_POST['xh']);		//裁切框高
		$w  = intval($_POST['w']);		//图片缩放后的宽
		$h  = intval($_POST['h']);		//图片缩放后的高
		
		$upload = new md\upload();
		$f = $upload -> crop(array(
			'path' => $pic,
			'x' => $x,
			'y' => $y,
			'dw' => $xw,
			'dh' => $xh,
			'sw' => $w,
			'sh' => $h,
		));
		
		if ($f){
			//裁切成相应尺寸
			$upload -> thumb($pic, 'C', 200, 200);
			$upload -> thumb($pic, 'C', 50, 50);
			
			$uid = $_SESSION['uid'];
			//保存
			$user = new md\user();
			$user -> update($uid, array('head' => $pic));
			
			cpurl('/user/head');
		} else {
			cpmsg(false, '头像裁切失败！', -1);
		}
	}
}