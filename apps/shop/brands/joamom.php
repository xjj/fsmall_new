<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	if(preg_match("/(?:http:\/\/)?(?:www\.)?joamom\.co\.kr\/index\.html\?branduid=([\d]+)/", $sourceHtmlUrl, $ang)){
		$sourceHtmlUrl = "http://www.joamom.co.kr/shop/shopdetail.html?branduid={$ang[1]}";
	}
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<IMG src=\"(http:\/\/joamom\.jpg2\.kr\/[^\"]+)\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
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