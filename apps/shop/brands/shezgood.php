<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	$contents = file_get_contents($sourceHtmlUrl);
	
	if(strlen($contents)==0) return(-1);
	
	
	$patternBigs = "/<div id=\"prdDetail\"([\s\S]*?)id=\"prdChange\"/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	//$contents=  $matcheBigs[1];
	$contents = $matcheBigs[0][0];
	$patternBigs = "/<IMG src=\"(\/web\/prefix\/detail\/[^\"]+)\">(<BR(?: \/)?>)?(<BR(?: \/)?>)?/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0){
		$patternBigs = "/<center>[\s\S]*?<IMG src=\"(\/web\/prefix\/detail\/[^\"]+)\">[\s\S]*?<\/center>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0)return(-2);
			//return(-2); 
	}
	
	$data = array();
	$i = 0;
	$filenames = array();
	foreach($matcheBigs[1] as $val){
		$val = 'http://www.shezgood.com'.$val;
		
		$val = str_replace('&#9;','', $val);
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