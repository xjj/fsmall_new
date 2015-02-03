<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	// 先匹配这个
	$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/minsshop\.com\/web\/[^\"]+)\"[^>]*?>/i";
	preg_match_all($patternBigs, $contents, $matcheBigs);
	if(count($matcheBigs[1])==0){
		$patternBigs = "/<IMG[\s\S]*?src=\"((?:\/web\/zimg|\/web\/upload\/NNEditor\/[\d]{8}|http:\/\/minsshop\.com\/web\/zimg|\/Product\/Images\/\d+\/(?:SFSELFAA)?\d+\/Editor|http:\/\/shop2\.wjdals0909\.cafe24\.com\/web\/zimg)\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0){
			//http://minsshop.com/web/zim
			$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/minsshop\.com\/web\/[^\"]+)\"[^>]*?>/i";
			preg_match_all($patternBigs, $contents, $matcheBigs);
			if(count($matcheBigs[1])==0){
			return(-2);
			}
		} 
	}
	$filenames = array();
	$i = 0;
	$data = array();
	foreach($matcheBigs[1] as $val){
		if (strpos($val, 'http://') !== 0){
			$val = 'http://www.minsshop.com'.$val;
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