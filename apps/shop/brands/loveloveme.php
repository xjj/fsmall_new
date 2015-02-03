<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<IMG[\s\S]+?src=\"(http:\/\/planbco\.cafe24\.com\/web\/img[^\"]+)\"[^>]*>/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0){///web/img/bot/zard0812_01.jpg
		$patternBigs = "/<IMG[\s\S]+?src=\"(\/web\/img\/[^\"]+)\"[^>]*>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0){
		return(-2);
		}
		
		
	} 
	
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matcheBigs[1] as $val){
		if (stripos($val, 'http://') === 0){} else {
			$val = 'http://www.loveloveme.com'.$val;
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