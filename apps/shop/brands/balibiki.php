<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	
	$patternBigs = "/<IMG[\s\S]+?src=\"((?:http:\/\/(?:www\.)?balibiki\.net)?\/web[\/]{1,2}(?:20[\d]{2}|product[\d]|upload[\d])\/[^\"]+)\"\s*?(?:width=700|width=\"700\"|width=\"850\"|width=850)?>/i";
	
	preg_match_all($patternBigs, $contents,$matcheBigs);
	
	if(count($matcheBigs[1])==0) {
		return(-2);
	}
		
	$filenames = array();
	$data = array();
	$i = 0;
	foreach($matcheBigs[1] as $val){
		
		if (strpos($val, 'http://') !== 0){
			$val = 'http://www.balibiki.net'.$val;
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