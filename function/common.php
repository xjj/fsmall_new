<?php
if (!defined('START')) exit('No direct script access allowed.');

/**
 +-------------------------------
 *	项目函数库
 +-------------------------------
 */

//判断字符串是否由英文字母，数字，简体中文组成
function isCnWord($str, $min = 1, $max = 20){
	$reg = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{".$min.",".$max."}$/u";
	$count = preg_match($reg, $str, $arr);
	if ($count > 0){
		if ($arr[0] = $str){
			return true;
		}
	} 
	return false;
}

//判断字符串是否含有韩文
function hasKrWord($str){
	$reg = "/[\x{3130}-\x{318F}\x{AC00}-\x{D7A3}]/u";
	$count = preg_match($reg, $str, $arr);
	if ($count > 0){
		return true;
	} 
	return false;
}

//判断字符串里是否含有中文
function hasCnWord($str){
	$reg = "/[\x{4e00}-\x{9fa5}]/u";
	$count = preg_match($reg, $str, $arr);
	if ($count > 0){
		return true;
	} 
	return false;
}


//输出消息提示
function cpmsg($status, $content, $url = -1, $seconds = 5){
	$smarty = fetch_instance('smarty'); 
	$smarty -> assign('message' ,array(
		'status' => $status, 
		'content' => $content, 
		'url' => $url, 
		'seconds' => $seconds
	));
	$smarty -> display('message/message.tpl');
	exit();
}

//链接跳转
function cpurl($url){
	if (empty($url)){$url = '/';}
	header('location:'.$url);
	exit();
}

//替换掉连续的空格与回车
function blank_space_replace($str, $replace = ' '){
	$str = trim($str);
	$str = str_replace('　', ' ', $str);
	$str = preg_replace('/\s+/', ' ', $str);
	if ($replace != ' '){
		$str = str_replace(' ', $replace, $str);
	}
	return trim($str);
}

//货币格式
function format_money($number){
	return number_format($number, 2, '.', '');
}