<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);

	$patternBigs = "/id=\"rb-recmd-detail\">([\s\S]*?)id=\"malltb_video_player\"/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
		die('div not match');
		return (-2); 
	} else {
		$content = $matcheBigs[0][0];
		$patternBigs = "/<IMG src=\"(http:\/\/happy1004cokr\.cafe24\.com\/[^\"]+)\"/i";
		preg_match_all($patternBigs, $content, $matcheBigs);
		
		if(count($matcheBigs[1])==0) {
			return (-2); 
		}
	}
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matcheBigs[1] as $val){
		//去除satr
		if(stripos($val,'star') ){
			continue;
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