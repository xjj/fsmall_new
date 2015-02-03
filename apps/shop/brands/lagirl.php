<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<IMG[\s\S]*?src=\"(\/image\/[^\"]+)\"/i";
	# "/<IMG[^\"]* src=\"(http:\/\/jcool2\.img10\.kr\/item\/[^\"]+)\">/i";
	//http://hellopeco.jpg2.kr/jcool2/item/20140306/2/20140306_2_09.jpg
	//http://lagirl.co.kr/image/140509/dd.jpg
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);//
	
	$filenames = array();
	$i = 0;
	$data = array();
	foreach($matcheBigs[1] as $val){
		$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
		$filenames[] = $filename;
		
		$data[] = array(
			'goods_id' => $goods_id,
			'sourcePic' => 'http://lagirl.co.kr'.$val,   //这个品牌的图片是相对路径  所以加上网站的网址
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