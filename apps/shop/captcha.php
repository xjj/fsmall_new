<?php
namespace shop;

/**
 *	验证码
 */
class Captcha extends Front {
	
	/**
	 *	输出图片
	 */
	function show(){
		$captcha = new \captcha();
		$captcha -> doimg();
		$_SESSION['captcha'] = $captcha -> getCode();
	}
	
	/**
	 *	验证
	 */
	function verify($code){
		$code = trim($code);
		$code = strtoupper($code);
		if (!empty($code) && $code == strtoupper($_SESSION['captcha'])){
			return true;
		}
		return false;
	}
}