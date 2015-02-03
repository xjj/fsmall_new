<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);

	$patternBigs = "/<img\s*src=\"(http:\/\/(?:sky770077\.img9\.kr|wifwif01\.jpg2\.kr)\/detail(?:%20| )image\/[^\"]+)\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) {
		$patternBig2 = "/<img\s*src=\"(http:\/\/sky770077\.img35\.makeshop\.co\.kr\/__manage__\/[^\"]+)\">/i";
		preg_match_all($patternBig2, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) {
			return(-2);
		}
	}
	
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matcheBigs[1] as $val){
		$val = str_replace(array('%20', '&amp;'), array(' ', '&'), $val);
		
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