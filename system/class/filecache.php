<?php
/**
 *	文件缓存类
 */
class FileCache {
	
	private $cachePath;
	private $expired = 3600;

	function __construct(){
		$this -> cachePath = ROOT_PATH.'/runtime/file_cache';
	}
	
	/**
	 *	获取数据
	 */
	function get($key){
		if (empty($key)){return false;}
		
		$file = $this -> getFileName($key);
		if (file_exists($file)){
			$content = file_get_contents($file);
			$data = unserialize($content);
			if (isset($data['time']) && $data['time'] > 0){
				$time = time();
				if ($time < $data['time']){
					return $data['data'];
				}
			}
		}
		
		return false;
	}
	
	/**
	 *	设置数据
	 */
	function set($key, $val, $expired = 0) {
		if (empty($key)){return false;}
		
		$file = $this -> getFileName($key);
		
		if (!file_exists(dirname($file))) {
			$this -> makedir(dirname($file));
		}
		
		$data = array(
			'time' => time() + ((empty($expired)) ? $this -> expired : intval($expired)),
			'data' => $val
		);
		
		file_put_contents($file, serialize($data));
		
		return true;
	} 
	
	/**
	 *	删除缓存
	 */
	function delete($key) {
		if (empty($key)) {
			return false;
		} else {
			$file = $this -> getFileName($key);
			@unlink($file);
			return true;
		} 
	}
	
	/**
	 *	获取文件路径
	 */
	function getFileName($key) {
		if (empty($key)) {return false;}
		
		return $this -> cachePath . '/' . md5($key) . '_'. $key . '.php';
	} 
	
	/**
	 * 	创建目录
	 */
	function makedir($dir){
		if(!is_dir($dir)){
			if($this -> makedir(dirname($dir))){mkdir($dir, 0777); return true;}
		} else {
			return true;
		}
	}
} 
    