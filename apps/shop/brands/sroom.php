<?php
function downLarge($sourceHtmlUrl, $saveDir, $goods_id){
	
	$contents= request($sourceHtmlUrl);
	if(strlen($contents)==0) return(-1);
	$patternBigs = "/<div id=\"detail5\">([\s\S]*?)id=\"srd_du_frame\"/i";
	preg_match_all($patternBigs, $contents,$matcheBigs);
	if(count($matcheBigs[0])==0) {
		die('div not match');
		return (-2); 
	} else {
		$content = $matcheBigs[0][0];
		 $content =substr($content, strpos($content,'dtInfo'));
		$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
		preg_match_all($patternBigs, $content, $matcheBigs);
		
		if(count($matcheBigs[1])==0) {
			return (-2); 
		}
	}
	$i = 0;
	$filenames = array();
	$data = array();
	foreach($matcheBigs[1] as $val){
		if(stripos($val,'http://') !== 0){
			$val = 'http://file.sroom.co.kr'.$val;
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
	/*
	-------------------------------------
		 HTTP/HTTPS请求操作 ---- CURL(需要开启)
	-------------------------------------
	*/
	function request($url, $tar=''){
		$curl = curl_init();
		$headers =array(
			'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
'Accept-Encoding'=>'gzip, deflate, sdch',
'Accept-Language'=>'zh-CN,zh;q=0.8',
'Cache-Control'=>'max-age=0',
'Connection'=>'keep-alive',

'Cookie'=>'ACEFCID=UID-5492843FD5F1CD71D680B625; CTSFCID=UID-548FBF85FBC353DEDD55A85F; PHPSESSID=a1694ef1b8c61d14d87e89198bcad7db; wm_log_counter=2; click_prd=_5671; click_prd_5671=5671%A2%B7%A2%B963A8F9E307F0BF4473C24DD4DB17CEBD%A2%B7%A2%B9%BC%D2%B4%CF%BE%C6%BA%B8%C5%B8%C0%CC%B4%CF%C6%AE%A2%B7%A2%B910000%A2%B7%A2%B9_data%2Fproduct%2F200810%2F24%A2%B7%A2%B9%A2%B7%A2%B9%A2%B7%A2%B9%A2%B7%A2%B9ef89ecd4691211c6f268d300aff93ae7.jpg%A2%B7%A2%B9283%A2%B7%A2%B9412%A2%B7%A2%B986758cbd99530f89e5f27c22927df475.jpg%A2%B7%A2%B9283%A2%B7%A2%B9412; AVAP1A392407270=1418888256068556080%7C2%7C1418888256068556080%7C1%7C1418888256029K5M7V; _ga=GA1.3.512100619.1418888749; wcs_bt=s_67d0422b028:1418890496; ASAP1A392407270=1418888256068556080%7C1418890496035208000%7C1418888256068556080%7C0; ARAP1A392407270=http%3A//www.sroom.co.kr/shop/detail.php%3Fpno%3D63A8F9E307F0BF4473C24DD4DB17CEBDbookmark; _TRK_AUIDA_13262=7018e028a52023b8e78da319799a708a:1; _TRK_ASID_13262=3a20fbad56474088a20418d0a413e76c',
'Host'=>'www.sroom.co.kr',
'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
			);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		
		curl_setopt($curl, CURLOPT_URL, $url);//
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $tar);//发送内容的字符串
		$tmpInfo = curl_exec ($curl);
		curl_close ($curl);

        return $tmpInfo;
	}
?>