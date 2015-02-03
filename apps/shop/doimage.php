<?php
namespace shop;
if (!defined('START')) exit('No direct script access allowed');
use model as md;

class doimage {
	//生成缩略图 -- 设置图片最大宽高
	function thumb($filepath, $width = 1000){
		if (!file_exists($filepath)){return false;}
		
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
		
		//求缩略图的真正宽高
		if ($W > $width){
			$height = floor( $width * $H / $W );
			$cutW   = $W;
			$cutH   = $H;
			
		} else { //--宽高在范围内
			$width = $W;
			$height = $H;
			$cutW = $W;
			$cutH = $H;
		}
		$newpath = $filepath;
		$pic = imagecreatetruecolor($width, $height);//创建画布
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
		unlink($filepath);
		switch ($mime){
			case 1:
				imagegif($pic, $newpath);
				break;
			case 2:
				imagejpeg($pic, $newpath, 90); 
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
	function crop($filepath, $height = 5000){
		if (empty($filepath)){return false;}
		if (!file_exists($filepath)){return false;}
		//获取原图的真实宽高数据
		$info = getimagesize($filepath);
		if ($info){
			list($W, $H, $mime) = $info;
		} else {
			return false;
		}
		if ($mime == 1){
			$img = imagecreatefromgif($filepath);
			$ext = 'gif';
		} elseif ($mime == 2){
			$img = imagecreatefromjpeg($filepath);
			$ext = 'jpg';
		} elseif ($mime == 3){
			$img = imagecreatefrompng($filepath);
			$ext = 'png';
		} else {
			return false;
		}
		if ($H <= $height){
			return array(
				'path' => $filepath,
				'number' => 1,
				'width' => $W,
				'height' => $H
			);
		}
		//求裁切的块数
		if ($H % $height == 0){
			$count = intval( $H / $height );
		} else {
			$count = ceil( $H / $height );
		}
		//裁切
		$data = array();
		for ($i = 0; $i < $count; $i++){
			$topheight = $height*$i;
			
			if ($count == $i+1){
				$height2 = $H - $height*$i;
			} else {
				$height2 = $height;
			}
			$pic = imagecreatetruecolor($W, $height2);
			if ($ext == 'png'){
				$c = imagecolorallocatealpha($pic, 0, 0, 0, 127);
				imagealphablending($pic, false);
				imagefill($pic, 0, 0, $c);
				imagesavealpha($pic, true);
			}
			imagecopyresampled($pic, $img, 0, 0, 0, $topheight, $W, $height2, $W, $height2);
			
			//生成新的文件名
			$basedir  = dirname($filepath);
			$basename = basename($filepath);
			$newpath  = $basedir.'/'.str_replace('.jpg','-'.$i.'.jpg', $basename);
			if ($mime == 1){
				imagegif($pic, $newpath);
			} elseif ($mime == 2){
				imagejpeg($pic, $newpath, 90); 
			} elseif ($mime == 3){
				imagepng($pic, $newpath); 
			}
			
			$data[] = $newpath;
			imagedestroy($pic);
		}
		imagedestroy($img);
		return array(
			'path' => $filepath,
			'number' => $count,
			'width' => $W,
			'height' => $H,
			'data' => $data
		);
	} 
}
?>