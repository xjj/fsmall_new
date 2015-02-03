<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	$needPrefix = 0;
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$matcheBigs = $matcheBigs1 = $matcheBigs2 = $matcheBigs3 = array();
	$patternBigs = "/<img src=\"(http:\/\/blackmuse\.co\.kr\/web\/ex[^\"]+)\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/image\.gmarket\.co\.kr\/[^\"]+)\"[^>]+?>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs2);
		$patternBigs = "/<IMG src=\"(\/web\/ex\/[^\"]+)\">/i";
		if(preg_match_all($patternBigs, $contents, $matcheBigs3)){
				$needPrefix = 1;
				foreach($matcheBigs3[1] as $val){
					$matcheBigs[1][] = 'http://www.blackmuse.co.kr'.$val;
				}
		}
		if(!empty($matcheBigs1)){
				foreach($matcheBigs1[1] as $val){
					$matcheBigs[1][] = $val;
				}
		}
		if(!empty($matcheBigs2)){
				foreach($matcheBigs2[1] as $val){
					$matcheBigs[1][] = $val;
				}
		}
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