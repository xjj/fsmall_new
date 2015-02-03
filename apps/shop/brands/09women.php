<?php
namespace shop;
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	
	//http://www.sucia.net/sre/imgs/2014/b/141020_14782_01.jpg
	$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/www\.sucia\.net\/sre\/imgs\/[^\"]+)\"[^>]*?>/i";
	preg_match_all($patternBigs, $contents, $matcheBigs);
	if(count($matcheBigs[1])==0) return(-2);
	
	$filenames = array();
	$i = 0;
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
		$urld= new urld();
		$urld->save_file_multi($data);
		die;
		foreach ($data as $item){
			$sourcePic = $item['sourcePic'];
			$filename = $item['filePath'];
			$urld->downFile($sourcePic, $filename, $goods_id);
		}
	}
	return $filenames;
}
?>