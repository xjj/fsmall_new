<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/wingsmall\.co\.kr\/wingsmall\/codistyle\/[^\"]+)\">/i";
	//http://wingsmall.co.kr/wingsmall/codistyle/14/03/112/party_01.jpg
	//$patternBigs = "/<IMG\s*src=\"(http:\/\/pinksecret\.co\.kr\/web\/img[^\"]+)\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);
	
	$filenames = array();
	$i = 0;
	$data = array();
	foreach($matcheBigs[1] as $val){
		if ($val == 'http://wingsmall.co.kr/wingsmall/codistyle/14/01/100/intro.jpg'){
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