<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +----------------------------
 *	FTP类
 +----------------------------
 */
class FTP {

	private $hostname	= '';
	private $username	= '';
	private $password	= '';
	private $port 		= 21;
	private $passive 	= TRUE;
	private $debug		= TRUE;
	private $connid 	= FALSE;
	
	
	function __construct($config = array()) {
		if (empty($config)) {
			global $_G;
			$config = $_G['FTP'];
		}
		$this -> _init($config);
	}
	
	/**
	 *	连接FTP服务器
	 */
	function connect($config = array()) {
		if (count($config) > 0) {
			$this->_init($config);
		}
		
		if (false === ($this -> connid = @ftp_connect($this->hostname, $this->port))) {
			if ($this -> debug === true) {
				$this -> _error("connect ftp server error. ip:".$this->hostname.' port:'.$this->port);
			}
			return false;
		}
		
		if (! $this -> _login()) {
			if ($this -> debug === true) {
				$this -> _error("login error, username:".$this->username.' password:'.$this->password);
			}
			return false;
		}
		
		if ($this -> passive === true) {
			ftp_pasv($this -> connid, true);
		}
		
		return true;
	}

	
	/**
	 *	改变目录
	 */
	function chgdir($path = '', $supress_debug = FALSE) {
		if ($path == '' || ! $this -> _isconn()) {
			return false;
		}
		
		$result = @ftp_chdir($this->connid, $path);
		
		if ($result === false) {
			if ($this -> debug === true && $supress_debug == false) {
				$this -> _error("unable change dir to `".$path."`.");
			}
			return false;
		}
		return true;
	}
	
	/**
	 *	目录生成
	 */
	function mkdir($path = '', $permissions = 0777) {
		if ($path == '' || ! $this->_isconn()) {
			return false;
		}
		
		$result = @ftp_mkdir($this->connid, $path);
		
		if ($result === false) {
			if ($this -> debug === true) {
				$this -> _error("mkdir error, `".$path."`.");
			}
			return false;
		}
		
		if (! is_null($permissions)) {
			$this -> chmod($path, $permissions);
		}
		
		return true;
	}
	
	/**
	 *	上传
	 */
	function upload($localpath, $remotepath, $mode = 'auto', $permissions = 0777) {
		if (! $this->_isconn()) {
			return false;
		}
		
		if (! file_exists($localpath)) {
			if ($this -> debug === TRUE) {
				$this -> _error("source file not exist, `".$localpath."`.");
			}
			return false;
		}
		
		if ($mode == 'auto') {
			$ext  = $this -> _getext($localpath);
			$mode = $this -> _settype($ext);
		}
		
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
		
		$result = @ftp_put($this -> connid, $remotepath, $localpath, $mode);
		
		if ($result === false) {
			if ($this -> debug === true) {
				$this -> _error("upload file error, `".$localpath."` ---> `".$remotepath."`.");
			}
			return false;
		}
		
		if (! is_null($permissions)) {
			$this->chmod($remotepath, $permissions);
		}
		
		return true;
	}
	
	/**
	 *	下载
	 */
	function download($remotepath, $localpath, $mode = 'auto') {
		if (! $this->_isconn()) {
			return false;
		}
		
		if ($mode == 'auto') {
			$ext  = $this->_getext($remotepath);
			$mode = $this->_settype($ext);
		}
		
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
		
		$result = @ftp_get($this->connid, $localpath, $remotepath, $mode);
		
		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error("download file error, `".$remotepath."` ---> `".$localpath."`.");
			}
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 *	重命名/移动
	 */
	function rename($oldname, $newname, $move = FALSE) {
		if (! $this->_isconn()) {
			return FALSE;
		}
		
		$result = @ftp_rename($this->connid, $oldname, $newname);
		
		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$msg = ($move == FALSE) ? "rename file error, `".$oldname."` ---> `".$newname."`." : "remove file error, `".$oldname."` ---> `".$newname."`.";
				$this->_error($msg);
			}
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 *	删除文件
	 */
	function delete_file($file) {
		if (! $this->_isconn()) {
			return FALSE;
		}
		
		$result = @ftp_delete($this->connid, $file);
		
		if ($result === FALSE) {
			if($this->debug === TRUE) {
				$this->_error("delete file error, `".$file."`.");
			}
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 *	删除文件夹
	 */
	function delete_dir($path) {
		if (! $this->_isconn()) {
			return FALSE;
		}
		
		//对目录宏的'/'字符添加反斜杠'\'
		$path = preg_replace("/(.+?)\/*$/", "\\1/", $path);
	
		//获取目录文件列表
		$filelist = $this->filelist($path);
		
		if ($filelist !== FALSE && count($filelist) > 0) {
			foreach($filelist as $item) {
				//如果我们无法删除,那么就可能是一个文件夹
				//所以我们递归调用delete_dir()
				if( ! @delete_file($item)) {
					$this->delete_dir($item);
				}
			}
		}
		
		//删除文件夹(空文件夹)
		$result = @ftp_rmdir($this->connid, $path);
		
		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error("delete folder error, `".$path."`.");
			}
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 *	修改文件权限
	 */
	function chmod($path, $perm) {
		if ( ! $this->_isconn()) {
			return FALSE;
		}
		
		//只有在PHP5中才定义了修改权限的函数(ftp)
		if (! function_exists('ftp_chmod')) {
			if($this->debug === TRUE) {
				$this->_error("ftp_chmod function not exist.");
			}
			return FALSE;
		}
		
		$result = @ftp_chmod($this->connid, $perm, $path);
		
		if ($result === FALSE) {
			if($this->debug === TRUE) {
				$this->_error("permission change error, `".$path." ---> ".$perm."`.");
			}
			return FALSE;
		}
		return TRUE;
	}
	
	//获取目录文件列表
	function filelist($path = '.') {
		if (! $this->_isconn()) {
			return FALSE;
		}
		return ftp_nlist($this->connid, $path);
	}
	
	//关闭FTP
	function close() {
		if (! $this->_isconn()) {
			return FALSE;
		}
		return @ftp_close($this->connid);
	}
	
	//初始化成员变量
	private function _init($config = array()) {
		foreach($config as $key => $val) {
			$key = strtolower($key);
			if (isset($this->$key)) {
				$this -> $key = $val;
			}
		}
		//特殊字符过滤
		$this->hostname = preg_replace('|.+?://|','',$this->hostname);
	}
	
	//登录
	private function _login() {
		return @ftp_login($this->connid, $this->username, $this->password);
	}
	
	//判断是否连接
	private function _isconn() {
		if (!is_resource($this->connid)) {
			if ($this -> debug === TRUE) {
				$this -> _error("no connection or connection has been lost.");
			}
			return false;
		}
		return true;
	}
	
	//获取后缀
	private function _getext($filename) {
		if (FALSE === strpos($filename, '.')) {
			return 'txt';
		}
		$extarr = explode('.', $filename);
		return end($extarr);
	}
	
	//根据后缀判断传输模式
	private function _settype($ext) {
		$text_type = array ('txt','text','php','phps','php4','js','css','htm','html','phtml','shtml','log','xml');
		return (in_array($ext, $text_type)) ? 'ascii' : 'binary';
	}
	
	//输出错误日志
	private function _error($msg) {
		$path = LOG_PATH . '/ftp';
		if (!file_exists($path)){mkdir($path, 0777);}
		$file = $path . '/' . date('Ymd') . '.txt';
		$message = "[" . date("H:i:s") . "] ---- " . $msg . PHP_EOL;
		return @file_put_contents($file, $message, FILE_APPEND);
	}
	
	/**
	 *	创建连续目录并进入目录
	 *	$path	为相对目录
	 */
	function makedir($path){
		$arrs = explode('/', trim($path, '/'));
		for ($i = 0; $i < count($arrs); $i++){
			$dir = $arrs[$i];
			if (!$this->chgdir($dir)){
				$f1 = $this->mkdir($dir, 0777);
				$f2 = $this->chgdir($dir);
				if ($f1 == false || $f2 == false){
					return false;
				}
			}
		}
		return true;
	}
}