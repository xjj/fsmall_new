<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	
	//http://gi.esmplus.com/qqq4848/mallup/W511/W511_01.jpg
	$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/gi\.esmplus\.com\/[^\"]+)\"[^>]*?>/i";
	preg_match_all($patternBigs, $contents, $matcheBigs);
	if(count($matcheBigs[1])==0) {
		//http://softcg2.filelink.cafe24.com/mallup/M238/M238_1.jpg
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/[A-Za-z0-9]*\.filelink\.cafe24\.com\/[^\"]+)\"[^>]*?>/i";
	preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) {
			return(-2);
			}
		
		}
	
	
	
	
	
	$filenames = array();
	$i = 0;
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