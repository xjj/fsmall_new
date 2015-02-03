<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<div id=\"prdDetail\">[\s\S]*?<div id=\"prdRelated\">/i";
	
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[0])==0) {
		return (-2); 
	} else {
		$content = $matcheBigs[0][0];
		$patternBigs = "/<IMG src=\"([^\"]+)\">/i";
		preg_match_all($patternBigs, $content, $matcheBigs);
		
		if(count($matcheBigs[1])==0) {
			return (-2); 
		}
	}
	#if(count($matcheBigs[1])==0) return(-2);
	foreach($matcheBigs[1] as $k=>$v){
		if($v=='http://urbanholic.co.kr/web/product/datail01.jpg'||$v=='http://urbanholic.co.kr/web/product/datail02.jpg'){
			unset($matcheBigs[1][$k]);
		}
		
		
	}
		
	$filenames = array();
	$i = 0;
	$data = array();
	foreach($matcheBigs[1] as $val){
		if (strpos($val, 'http://') !== 0){
			$val = 'http://www.urbanholic.co.kr'.$val;
		}
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