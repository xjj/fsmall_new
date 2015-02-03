<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs_5 = "/<img[\s\S]*?src=\"?(http:\/\/img\.momnuri\.com\/(?:[\S]*?)?product_img\/contents_[^\">]+)\"?[\s\S]*?\/>/i";
	preg_match_all($patternBigs_5, $contents,$matcheBigs_5);
	$arr3 = array_merge($matcheBigs_5[1]);
	if(count($arr3)==0) return(-2);
	
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($arr3 as $val){
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