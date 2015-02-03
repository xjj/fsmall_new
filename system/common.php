<?php
if (!defined('START')) exit('No direct script access allowed.');

/**
 +----------------------------------
 *	框架基础函数库
 +----------------------------------
 */

//自动加载类文件
function autoload($className){
	load_class($className);
}

//加载类文件
function load_class($class){
	$class = strtolower($class);
	$class = str_replace('\\', '/', $class);
	$class = trim($class, '/');
	
	if (strpos($class, '/') === false){
		$file = SYSTEM_PATH.'/class/'.$class.'.php';
	} else {
		$arrs = explode('/', $class);
		$controller = array_shift($arrs);
		$cls_file = implode('/', $arrs).'.php';
		
		$model = basename(MODEL_PATH);
		$service = basename(SERVICE_PATH);
		
		if ($controller == $model){
			$file = MODEL_PATH.'/'.$cls_file;
		} elseif ($controller == $service) {
			$file = SERVICE_PATH.'/'.$cls_file;
		} else {
			$file = CONTROLLER_PATH.'/'.$cls_file;
		}
		
		if (file_exists($file)){
			include_once ($file);
		} else {
			exit('not find "'.$class.'" class file.');
		}
	}
	
	if (file_exists($file)){
		include_once ($file);
	} else {
		exit('not find "'. $class .'" class file.');
	}
}

//加载函数文件
function load_function($name){
	$name = strtolower($name);
	$file = ROOT_PATH.'/function/'.$name.'.php';
	if (file_exists($file)){
		include_once ($file);
	} else {
		exit('not find "'.$name.'" function file.');
	}
}

//获取配置文件
function load_config($filename, $type = 'include'){
	$filename = strtolower($filename);
	$filename = $filename.'.php';
	$filepath = CONFIG_PATH.'/'.$filename;
	if (file_exists($filepath)){
		if ($type == 'include'){
			include_once ($filepath);
		} else {
			return include ($filepath);
		}
	} else {
		exit('not find "'.$filename.'" config file.');
	}
}

//获取实例[db,smarty,RTR]
function fetch_instance($class, $n = 0){
	$class = strtoupper($class);
	switch ($class){
		case 'DB':
			return fetch_db_instance($n);
			break;
		case 'RTR':
			return fetch_RTR_instance();
			break;
		case 'SMARTY':
			return fetch_smarty_instance();
			break;
	}
}

//获取数据库实例
function fetch_db_instance($num = 0){
	$name = '_MYSQL_DB_'.$num;
	if (is_object($GLOBALS[$name])){
		return $GLOBALS[$name];
	} else {
		global $_G;
		$GLOBALS[$name] = new DB($_G['DB'][$num]);
		return $GLOBALS[$name];
	}
}

//获取Smarty实例
function fetch_smarty_instance(){
	$name = '_SMARTY_';
	if (is_object($GLOBALS[$name])){
		return $GLOBALS[$name];
	} else {
		global $_G;
		$GLOBALS[$name] = new Smarty();
		$GLOBALS[$name] -> setTemplateDir($_G['SMARTY']['TEMPLATEDIR']);
		$GLOBALS[$name] -> setCompileDir($_G['SMARTY']['COMPILEDIR']);
		$GLOBALS[$name] -> setConfigDir($_G['SMARTY']['CONFIGDIR']);
		$GLOBALS[$name] -> setCacheDir($_G['SMARTY']['CACHEDIR']);
		$GLOBALS[$name] -> left_delimiter = $_G['SMARTY']['LEFTDELIMITER'];
		$GLOBALS[$name] -> right_delimiter = $_G['SMARTY']['RIGHTDELIMITER'];
		return $GLOBALS[$name];
	}
}

//获取路由实例
function fetch_RTR_instance(){
	$name = '_RTR_';
	if (is_object($GLOBALS[$name])){
		return $GLOBALS[$name];
	} else {
		$GLOBALS[$name] = new Router();
		return $GLOBALS[$name];
	}
}

//是否为整数 -- 也有可能为负数
function isint($str){
	if (!is_numeric($str) || strpos($str, '.') !== false){
		return false; 
	} else {
		return true;
	}
}

//获取随机字符串
function random($length) {
	$base62 = array (
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
		'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5',
		'6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
		'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
	);
	$output = '';
	for ($i = 0; $i < $length; $i++) {
		$j = rand(0, 61);
    	$output .= $base62[$j];
  	}
	return $output;
}

//转义
function encode($str){
	return addslashes($str);
}

//反转义
function decode($str){
	return stripslashes($str);
}

//获取IP地址
function getip(){
	if (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} elseif (getenv("REMOTE_ADDR")) {
		$ip = getenv("REMOTE_ADDR");
	} else {
		return false;
	}
	return bindec(decbin(ip2long($ip)));
}

//转换IP
function ip2str($number){
	if (empty($number)) {return false;}
	return long2ip($number);
}

//判断日期时间格式 yyyy-mm-dd hh:ii:ss
function isDate($str, $isDate = 0){
	$arr = explode(' ', trim($str));
	$date = $arr[0];
	$dArr = explode('-', $date);
	if (count($dArr) != 3){return false;}
	if (!isint($dArr[0]) || $dArr[0] <= 0){return false;}
	if (!isint($dArr[1]) || $dArr[1] > 12 || $dArr[1] < 1){return false;}
	if (!isint($dArr[2]) || $dArr[2] > 31 || $dArr[2] < 1){return false;}
	
	if (isset($arr[1]) && $isDate != 0){return false;}
	
	if (isset($arr[1])){
		$time = $arr[1];
		$tArr = explode(':', $time);
		if (count($tArr) <= 1 || count($tArr) > 3){return false;}
		if (!isint($tArr[0]) || $tArr[0] > 23 || $tArr[0] < 0){return false;}
		if (!isint($tArr[1]) || $tArr[1] > 59 || $tArr[1] < 0){return false;}
		if (isset($tArr[2])){
			if (!isint($tArr[2]) || $tArr[2] > 59 || $tArr[2] < 0){return false;}
		}
	}
	return true;
}


//判断邮箱有效性
function isEmail($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)){
		return true;
	}
	return false;
}


//判断图片是否存在
function image_is_exist($url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    $result = curl_exec($curl);
    $found = false;
    if ($result !== false) {
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
        if ($statusCode == 200) {
            $found = true;   
        }
    }
    curl_close($curl);
    return $found;
}

//获取链接数据
function fetch_url_query($data, $except = ''){
	if (!is_array($data) || empty($data)){return '';}
	
	if (!empty($except)){
		unset($data[$except]);	
	}
	
	if (empty($data)){return '';}
	
	$query = $dot = '';
	foreach ($data as $key => $val){
		if ($val !== ''){
			$query .= $dot.$key.'='.$val;
			$dot = '&';
		}
	}
	return $query;
}


/**
 +----------------------------------------------
 *	对二维数组进行排序
 +----------------------------------------------
 * 	$array
 * 	$keyid	排序的键值
 * 	$order	排序方式 'asc':升序 'desc':降序
 * 	$type	键值类型 'number':数字 'string':字符串
 */
function sort_array(&$array, $keyid, $order = 'asc', $type = 'number') {
    if (is_array($array)) {
        foreach($array as $val) {
            $order_arr[] = $val[$keyid];
        }

        $order = ($order == 'asc') ? SORT_ASC: SORT_DESC;
        $type = ($type == 'number') ? SORT_NUMERIC: SORT_STRING;

        array_multisort($order_arr, $order, $type, $array);
    }
}

//创建文件夹
function makedir($dir){
	if (!is_dir($dir)){
		if(makedir(dirname($dir))){mkdir($dir, 0777); return true;}
	} else {
		return true;
	}
}
/*
-------------------------------------
	 HTTP/HTTPS请求操作 ---- CURL(需要开启)
-------------------------------------
*/
function curl_request($url, $tar){
	$curl = curl_init();
	//서버 요청주소
	curl_setopt($curl, CURLOPT_URL, $url);//
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1 );
	curl_setopt($curl, CURLOPT_POSTFIELDS, $tar);//发送内容的字符串
	$tmpInfo = curl_exec ($curl);
	curl_close ($curl);
	return $tmpInfo;
}
