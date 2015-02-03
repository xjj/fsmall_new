<?php
namespace model;

/**
 +-------------------------------
 *	图片上传服务
 +-------------------------------
 */

class Upload_Picture extends Front {

	private $folder;
	
	
	//图片
	function upload($fpic, $uid, $adm_uid = 0){
		
		$folder = $this -> params[0];
		$folder = strtolower($folder);
		
		if (empty($folder)){
			return array('error' => -1, 'message' => '上传文件类型错误！');
		}
		
		//设置图片类型变量
		$this -> folder = $folder;
		
		
		//上传操作
		$upload = new upload();
		$data = $upload -> save($fpic, $this->folder);
		if ($data['error'] == 0){} else {
			return array('error' => -1, 'message' => '文件上传失败！');
		}
		
		$path = $data['path'];
		
		$pic = new Picture();
		$url = $pic -> thumb_url($path, 0, 0, IMG_SERVER);
		
		$pic_id = $pic -> add(array(
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'folder' => $this -> folder,
			'path' => $path,
			'url' => $url,
			'width' => $data['width'],
			'height' => $data['height'],
			'size' => $data['size'],
			'mime' => $data['mime'],
		));
		
		if ($pic_id){} else {
			return array('error' => -1, 'message' => '写入数据库错误！');
		}
		
		$ret = array(
			'error' => 0,
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'pic_id' => $pic_id,
			'folder' => $this->folder,
			'path' => $path,
			'url' => $url,
			'width' => $data['width'],
			'height' => $data['height'],
			'size' => $data['size'],
			'mime' => $data['mime'],
			'add_time' => time()
		);
		
		
		if ($folder == 'product'){
			$up = new upload();
			$up -> thumb($path, 'C', 500, 500);
			$up -> thumb($path, 'C', 300, 300);
			$up -> thumb($path, 'C', 150, 150);
		}
		
		return $ret;
	}
	
	//设置图片类型文件夹
	function setfolder($folder){
		$this -> folder = $folder;
	}
} 