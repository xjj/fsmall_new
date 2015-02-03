<?php
include_once (SYSTEM_PATH.'/libs/barcode/BCGColor.php');
include_once (SYSTEM_PATH.'/libs/barcode/BCGDrawing.php');
include_once (SYSTEM_PATH.'/libs/barcode/BCGFont.php');
include_once (SYSTEM_PATH.'/libs/barcode/BCGcode39.barcode.php'); 

/**
 +-----------------------------------
 *	打印条码
 +-----------------------------------
 */
class Barcode {
	
	function draw($bcText, $height){
		
		$font = new BCGFont(SYSTEM_PATH.'/libs/barcode/font/Arial.ttf', 10);
		
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255); 
	
		$code = new BCGcode39();
		$code->setScale(2); 						//
		$code->setThickness($height); 				//高度
		$code->setForegroundColor($color_black); 	//前景色
		$code->setBackgroundColor($color_white); 	//背景色
		$code->setFont(0); 							//条码字体 (or 0)
		$code->parse($bcText); 
		
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		header('Content-Type: image/png');
		
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}
}