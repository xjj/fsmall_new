<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +----------------------------------
 *	图片上传类
 +----------------------------------
 *	errno说明：
 *	300 = PICTURE_IS_EMPTY = 图片为空
 *	301 = PICTURE_TYPE_NOT_ALLOW = 图片格式不正确
 *	302 = PICTURE_SIZE_OVER_LIMIT = 图片大小超出限制
 *	303 = PICTURE_SAVE_FAILE = 图片保存失败
 +----------------------------------
 */
class upload extends Front{
	private $maxsize 	= 4194304;	//上传图片的大小4M
	private $maxwidth 	= 1600;		//上传图片的最大宽度 --- 大于该值缩小到该尺寸
	
	//获取图片的服务器路径
	function filepath($path, $width = 0, $height = 0){
		$ext = substr($path, -4, 4);
		if ($width > 0 && $height > 0){
			$path = substr($path, 0, -4).'-'.$width.'-'.$height.$ext;
		} elseif ($width > 0 && $height == 0){
			$path = substr($path, 0, -4).'-'.$width.$ext;
		}
		if (strpos($path, UPLOAD_PATH) === false){
	 		return UPLOAD_PATH .'/'. ltrim($path, '/');
		} else {
			return $path;
		}
	}
	
	//新建文件名
	function newname($folder, $ext = 'jpg'){
		$ext  = trim($ext, '.');
		$path = $folder .'/'. date("Y/md");
		$filepath = UPLOAD_PATH .'/'. $path;
		if (!file_exists($filepath)){
			makedir($filepath);
		}
		return $path .'/'.date('His') . md5(uniqid()) .'.'. $ext;
	}
	
	//保存图片
	function save($pic, $folder){
		if (empty($pic) || $pic['size'] == 0){
			return array('error' => -1, 'message' => '图片域为空！');
		}

		$info = getimagesize($pic["tmp_name"]);
		if ($info){
			list($width, $height, $mime) = $info;
		} else {
			return array('error' => -1, 'message' => '获取图片信息失败！');
		}
		switch ($mime){
			case 1:
				$ext = 'gif';
				break;
			case 2:
				$ext = 'jpg';
				break;
			case 3:
				$ext = 'png';
				break;
			default:
				$ext = false;
				break;
		}
		
		if ($ext == false){
			return array('error' => -1, 'message' => '图片类型不正确！');
		}
		
		$size = $pic['size'];
		if ($size > $this -> maxsize){
			return array('error' => -1, 'message' => '图片大小超出了限制！');
		}
		
		//获取新图片地址
		$newname = $this -> newname($folder, $ext);
		$newpath = $this -> filepath($newname);
		
		//保存操作		
		if ($pic["error"] == 0 && is_uploaded_file($pic["tmp_name"])){
			$f = move_uploaded_file($pic["tmp_name"], $newpath);
			if ($f){
				if ($width > $this -> maxwidth){
					$rt = $this -> thumb_limited_width($newname);
					if ($rt){
						$width  = $rt['width'];
						$height = $rt['height'];
						$size	= $rt['size'];
					}
				}
				
				return array(
					'error' => 0, 
					'folder' => $folder,
					'path' 	=> $newname,
					'size' 	=> $size, 
					'width' => $width,
					'height'=> $height,
					'mime'	=> $pic['type']
				);
			}
		} 
		return array('error' => -1, 'message' => '图片上传失败！');
	}
	
	//超宽图片缩放
	//path = 
	function thumb_limited_width($path){
		
		$filepath = $this -> filepath($path);
		if (!file_exists($filepath)){return false;}
	 	$f = $this -> thumb($path, 'T', $this -> maxwidth);
		if ($f){
			list($width, $height, $mime) = getimagesize($filepath);
			$size = filesize($filepath);
			return array('path' => $path, 'width' => $width, 'height' => $height, 'size' => $size);
		} 
		return false;
	 }
	
	
	//生成缩略图
	//$path		图片保存在数据库的相对地址
	//$type		S=缩小 C=裁切 T=重置
	function thumb($path, $type, $width = 0, $height = 0){
		$filepath = $this -> filepath($path);
		if (!file_exists($filepath)){return false;}
		
		$type = strtoupper($type);
		if (!in_array($type, array('S', 'C', 'T'))){return false;}
		
		$info = getimagesize($filepath);
		if ($info){
			list($W, $H, $mime) = $info;
		} else {
			return false;
		}
		switch ($mime){
			case 1:
				$img = imagecreatefromgif($filepath);
				$ext = 'gif';
				break;
			case 2:
				$img = imagecreatefromjpeg($filepath);
				$ext = 'jpg';
				break;
			case 3:
				$img = imagecreatefrompng($filepath);
				$ext = 'png';
				break;
			default:
				return false;
				break;
		}
		
		//备份原宽度高度参数
		$sh = $height;
		$sw = $width;
		
		//求缩略图的真正宽高
		$copy = false;
		switch ($type){
			case 'S':
			case 'T':
				if ($width > 0){ //--按照宽度来缩
					if ($W <= $width){
						$width = $W;
						$height = $H;
						$copy = true;
					} else {
						$height = floor( $width * $H / $W );
						$cutW  = $W;
						$cutH  = $H;
					}
				} else { //--按照高度来缩
					if ($H <= $height){
						$width = $W;
						$height = $H;
						$copy = true;
					} else {
						$width = floor( $height * $W / $H );
						$cutW  = $W;
						$cutH  = $H;
					}
				} 
				break;
			case 'C':
				if ($W / $H > $width / $height){ //--高度不变，裁切宽
					$cutW 	= floor($width * $H / $height);
					$cutH	= $H;
				} else { //--宽度不变，裁切高
					$cutW	= $W;
					$cutH	= floor($height * $W / $width);
				}
				break;
		}
		
		//生成新的文件名
		if ($type == 'T'){
			$newpath = $filepath;
		} else {
			$newpath = $this -> filepath($path, $sw, $sh);
		}
		
		if ($copy){
			copy($filepath, $newpath);
			return true;
		}
		
		$pic = imagecreatetruecolor($width, $height);
		if ($ext == 'png'){
			$c = imagecolorallocatealpha($pic, 0, 0, 0, 127);
			imagealphablending($pic, false);
			imagefill($pic, 0, 0, $c);
			imagesavealpha($pic, true);
		}			
		
		//$width  	= 生成目标图片的宽度
		//$height 	= 生成目标图片的高度
		//$cutW  	= 原图切图的宽度
		//$cutH 	= 原图切图的高度
		imagecopyresampled($pic, $img, 0, 0, 0, 0, $width, $height, $cutW, $cutH);
		switch ($mime){
			case 1:
				imagegif($pic, $newpath);
				break;
			case 2:
				imagejpeg($pic, $newpath, 100); 
				break;
			case 3:
				imagepng($pic, $newpath);
				break;
		}
		
		imagedestroy($pic);
		imagedestroy($img);
		return true;
	}	
	
	//裁切
	function crop($data){
		$path = $data['path'];		//保存在数据库中的图片地址
		$x  = $data['x'];			//裁切点X坐标
		$y  = $data['y'];			//裁切点Y坐标
		$dw = $data['dw'];			//裁切框宽度
		$dh = $data['dh'];			//裁切框高度
		$sw = $data['sw'];			//裁切图缩放后的宽度
		$sh = $data['sh'];			//裁切图缩放后的高度
		
		
		if (empty($path)){return false;}
		if (!isint($x) || !isint($y)){return false;}
		if (!isint($dw) || $dw <= 0 || !isint($dh) || $dh <= 0){return false;}
		if (!isint($sw) || $sw <= 0 || !isint($sh) || $sh <= 0){return false;}
		
		$filepath = $this -> filepath($path);
		
		
		//获取原图的真实宽高数据
		$info = getimagesize($filepath);
		if ($info){
			list($W, $H, $mime) = $info;
		} else {
			return false;
		}
		
		switch ($mime){
			case 1:
				$img = imagecreatefromgif($filepath);
				$ext = 'gif';
				break;
			case 2:
				$img = imagecreatefromjpeg($filepath);
				$ext = 'jpg';
				break;
			case 3:
				$img = imagecreatefrompng($filepath);
				$ext = 'png';
				break;
			default:
				return false;
				break;
		}
		
		//重新计算裁切数据
		$cutX	= floor($x  * $W  / $sw); 		//裁切点在原图上的X坐标
		$cutY	= floor($y  * $H  / $sh);		//裁切点在原图上的Y坐标
		$cutW	= floor($dw * $W  / $sw);		//裁切的实际宽度
		$cutH	= floor($dh * $H  / $sh);		//裁切的实际高度
		
		//生成新的文件名
		$newpath = $filepath;
		
		
		$pic = imagecreatetruecolor($cutW, $cutH);
		if ($ext == 'png'){
			$c = imagecolorallocatealpha($pic, 0, 0, 0, 127);
			imagealphablending($pic, false);
			imagefill($pic, 0, 0, $c);
			imagesavealpha($pic, true);
		}
		
		imagecopyresampled($pic, $img, 0, 0, $cutX, $cutY, $cutW, $cutH, $cutW, $cutH);
		switch ($mime){
			case 1:
				imagegif($pic, $newpath); 
				break;
			case 2:
				imagejpeg($pic, $newpath, 100); 
				break;
			case 3:
				imagepng($pic, $newpath); 
				break;
		}
		imagedestroy($pic);
		imagedestroy($img);
		return true;
	} 
	

	//将网络图片下载到服务器
	function fetch_remote_image($folder, $url){
		$data = file_get_contents($url);
		if ($data){
			$newname  = $this -> newname($folder);
			$filepath = $this -> filepath($newname);
			$result = file_put_contents($filepath, $data);
			if ($result){
				$info = getimagesize($filepath);
				if ($info){
					list($width, $height, $mime) = $info;
					
					if ($mime == 1){
						$mimetype = 'image/gif';
					} elseif ($mime == 2){
						$mimetype = 'image/jpeg';
					} elseif ($mime == 3){
						$mimetype = 'image/png';
					} else {
						return false;
					}
								
					$size = filesize($filepath);
					return array(
						'path' => $newname,
						'width' => $width,
						'height' => $height,
						'size' => $size,
						'mime' => $mimetype,
					);
				}
			}
		}
		return false;
	}
}
?>