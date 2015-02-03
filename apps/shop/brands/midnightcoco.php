<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<div id=\"prdDetail\">[\s\S]*?<div id=\"prdRelated\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	$str = $matcheBigs[0][0];
	$pattern = "/<img[\s\S]*?src=\"([^\"]+)\"[^>]*?>/i";
	preg_match_all($pattern, $str, $matches);	
	//
	#var_dump( $matcheBigs);
	if(count($matches[1])==0) return(-2); //////
	
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matches[1] as $val){
		$xieyi = substr($val, 0, 7);
		if (strtolower($xieyi) != 'http://'){$val = 'http://www.24coco.com'.$val;}
		
		$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
		$filenames[] = $filename;
		
		$data[] = array(
			'goods_id' => $goods_id,
			'sourcePic' => $val,
			'filePath' => $filename
		);
		
		$i += 1;
	}
	
	if (!empty($data)){
		save_file_multi($data);
		foreach ($data as $item){
			$sourcePic = $item['sourcePic'];
			$filename = $item['filePath'];
			downFile($sourcePic, $filename, $goods_id);
		}
	}
	return $filenames;
}
?>