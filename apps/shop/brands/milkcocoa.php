<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	ob_start();
	$ch = curl_init(); 
	$headerArr = array(
		'Connection:keep-alive',
		'Keep-Alive: 300',
	);
	curl_setopt($ch, CURLOPT_URL, $sourceHtmlUrl); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
	$contents = curl_exec($ch); 
	curl_close($ch);
	$contents = ob_get_contents();
	ob_end_clean();
 

	if(strlen($contents)==0) return -1;
	$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/milkcocoa\.img\.mywisa\.com\/[^\"]+)\"[\s\S]*?>/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) return -2;
	
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