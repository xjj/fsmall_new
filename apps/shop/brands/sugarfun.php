<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<img src=\"(http:\/\/file\.sugarfun1\.cafe24\.com\/Design\/web19\/web\/[^\"]+)\" border=\"?0\"?>/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) {
		$pattern = "/<img border=\"?0\"? src=\"(http:\/\/file\.sugarfun1\.cafe24\.com\/Design\/web19\/web\/[^\"]+)\"[\s]*?>/i";
		preg_match_all($pattern, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
	
	}
	
	
	
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