<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
	preg_match_all($patternBigs, $contents, $matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);
	
	$cnn = $matcheBigs[1][0];
	
	$pattern = '/<img[\s\S]*?src=\"([^\"]+)\"[^>]*>/i';
	preg_match_all($pattern, $cnn, $matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);
	
	
	$filenames = array();
	$i = 0;
	$data = array();
	foreach($matcheBigs[1] as $val){
		if (strpos($val, 'http://') !== 0){
			$val = 'http://limview.co.kr'.$val;
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