<?php
namespace model;

/**
 +---------------------------
 *	网站图片信息类
 +---------------------------
 */
class Picture extends Front {
	
	//获取图片信息
	function info($pic_id, $fields = array()){
		if (!isint($pic_id) || $pic_id <= 0){return false;}
		$f = empty($fields) ? '*' : $this -> db -> implode_field_keys($fields, ',');
		$sql = 'SELECT '.$f.' FROM `picture` WHERE pic_id = \''.encode($pic_id).'\'';
		return $this -> db -> row($sql);
	}
	
	
	//根据图片地址获取图片信息
	function get_info_by_path($path){
		$sql = 'SELECT * FROM `picture` WHERE path = \''.encode($path).'\'';
		return $this -> db -> row($sql);
	}
	
	//根据图片地址获取图片ID
	function get_id_by_path($path){
		$sql = 'SELECT pic_id FROM `picture` WHERE path = \''.encode($path).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['pid'];
		} else {
			return false;
		}
	}
	
	//保存图片信息
	function add($data){
		$uid = intval($data['uid']);
		$adm_uid = intval($data['adm_uid']);
		$folder = $data['folder'];
		$path = $data['path'];
		$url = $data['url'];
		$width = intval($data['width']);
		$height = intval($data['height']);
		$size = intval($data['size']);
		$mime = trim($data['mime']);
		
		if ($uid == 0 && $adm_uid == 0){return false;}
		if (empty($folder) || empty($path)){return false;}
		if ($width == 0 || $height == 0 || $size == 0){return false;}
		
		return $this -> db -> insert('picture', array(
			'uid'		=> $uid,
			'adm_uid'	=> $adm_uid,
			'folder'	=> $folder,
			'path'		=> $path,
			'url'		=> $url,
			'width'		=> $width,
			'height'	=> $height,
			'size'		=> $size,
			'mime'		=> $mime,
			'add_time'	=> time(),
			'is_delete' => 0
		));
	}
	
	//删除图片信息
	function delete($pic_id){
		if (isint($pic_id) && $pic_id > 0){
			$where = array('pic_id' => $pic_id);
		} elseif (is_string($pic_id)){
			$where = array('url' => $pic_id);
		} else {
			return false;
		}
		return $this -> db -> update('picture', array('is_delete' => 1, 'delete_time' => time()), $where);	
	}
	
	//获取图片或缩略图访问地址
	function thumb_url($path, $width = 0, $height = 0){
		if (empty($path)){return false;}
		
		if ($width > 0 && $height > 0){
			$ext  = substr($path, -4, 4);
			$path = substr($path, 0, -4).'-'.$width.'-'.$height.$ext;
		} elseif ($width > 0 && $height == 0) {
			$ext  = substr($path, -4, 4);
			$path = substr($path, 0, -4).'-'.$width.$ext;
		}
		
		if (strpos($path, 'http://') === 0){} else {
			$path = ltrim($path, '/');
			$path = URI_UPLOAD.'/'.$path;
		}
		
		return $path;
	}
	
	/**
	 *	生成图片的缩略图地址
	 *	该地址将保存到数据库
	 */
	function thumb($path, $width = 0, $height = 0){
		if (empty($path)){return false;}
		
		if ($width > 0 && $height > 0){
			$ext  = substr($path, -4, 4);
			$path = substr($path, 0, -4).'-'.$width.'-'.$height.$ext;
		} elseif ($width > 0 && $height == 0) {
			$ext  = substr($path, -4, 4);
			$path = substr($path, 0, -4).'-'.$width.$ext;
		}
		
		if (strpos($path, 'http://') === 0){
			$path = str_replace(URI_UPLOAD, '', $path);
		}
		
		return ltrim($path, '/');
	}
	
	//获取图片的服务器路径
	function thumb_path($path, $width = 0, $height = 0){
		if (empty($path)){return false;}
		$upload = new upload();
		return $upload -> filepath($path, $width, $height);
	}
	
	//获取商品图片的访问地址
	function product_thumb_url($path, $width = 0, $height = 0){
		return $this -> thumb_url($path, $width, $height, IMG_SERVER);
	}
}