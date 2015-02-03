<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	
	
	if(strlen($contents)==0) return(-1);
	$pattern1 = '/<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"\s*?>\s*?<tr><td height=\"250px\"><\/td><\/tr>\s*?<\/table>\s*?<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"1100px\"\s*?>([\s\S]*?)<\/table>/i';
	
	preg_match_all($pattern1, $contents, $matche1);
	if(count($matche1[1])==0){//新版的div匹配
		$pattern1 = '/<div id=\"prdDetail\">([\S\s]*?)<div class=\"xans-recommend-display\">/i';
		//$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($pattern1, $contents, $matche1);
		
	}
	if(count($matche1[1])==0) return(-2);
	
	$patternBigs = "/<IMG[\s\S]*?src=\"([\s\S]*?)\">/i";
	preg_match_all($patternBigs, $matche1[1][0], $matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);
	
	
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matcheBigs[1] as $val){
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