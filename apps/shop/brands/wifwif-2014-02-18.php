<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	$contents = file_get_contents($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<IMG\s*src=\"(http:\/\/s0529s\.img\.mywisa\.com\/__manage__\/[\s\S]*?\.jpg)\">/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[1])==0) {print_r($contents);return(-2);}
	
	$pat = "/xmlPath=(http:\/\/www\.wifwif\.co\.kr\/_skin\/user_1\/img\/flash\/xml\/detailView\.xml\.php&pdNo=[\d\:]+)/";
	preg_match_all($pat, $contents, $mx);
	foreach($mx[1] as $murl){
		$murl = str_replace('&','?',$murl);
		$mcontent = file_get_contents("http://www.fs-mall.com/luzhang/wifwif.php?str=".urlencode($murl));
		$pbig = "/(http:\/\/s0529s\.img\.mywisa\.com\/__manage__\/product_[\d]+\/[\d]+\-1\.jpg)/i";
		preg_match($pbig, $mcontent, $mimg);
		$matcheBigs[1][] = $mimg[1];
	}
	
	$i = 0;
	$filenames = array();
	foreach($matcheBigs[1] as $val){
		$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
		$filenames[] = $filename;
		
		//保存到数据库[待下载状态status=0]
		saveFileToDB($val, $filename, $goods_id);
		
		//下载
		downFile($val, $filename, $goods_id);
		
		$i += 1;
	}
	return $filenames;
}
?>