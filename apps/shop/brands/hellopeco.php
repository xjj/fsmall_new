<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<img src=\"(http:\/\/jcool\.img9\.kr\/new\/[^\"]+)\">/i";
	#http://hellopeco.jpg2.kr/jcool/new/20140410/1/20140410_1_00.jpg
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) { #/web/web2/4486.jpg
		$patternBigs2 ="/<img src=\"(http:\/\/hellopeco\.jpg2\.kr\/jcool\/new\/[^\"]+)\">/i";
		preg_match_all($patternBigs2, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) {
		return(-2);
		}
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