<?php
//127.0.0.1 9100-9119
#注意:要跳转到的后续文件路径
set_time_limit(0);
date_default_timezone_set('Asia/Chongqing');

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);//创建一个socket
$ip = $argv[1];
$port = $argv[2];
socket_bind($socket, $ip, $port);//绑定监听
socket_set_option($socket, SOL_SOCKET, SO_RCVBUF, 1048576);

while (true){
	socket_recvfrom($socket, $content, 1024, 0, $ip, $port);//取消息
	if (!empty($content)){
		list($filePath,$sourceUrl,$goods_id) = explode('|',$content);
		$filePath = str_replace("\\","/",$filePath);
		$dirname = dirname($filePath);
		if(!file_exists($dirname)){
			exec("mkdir -p $dirname");
		}

		//尝试3遍下载文件
		$counter = 3;
		do {
			$fp = fopen($filePath, 'w');
			$ch = curl_init($sourceUrl);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
			curl_setopt($ch, CURLOPT_TIMEOUT, 900);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			if (strpos($sourceUrl, 'http://milkcocoa.img.mywisa.com/') === 0){
				//milkcocoa的反盗链
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.177.7.1', 'CLIENT-IP:61.177.7.1'));//IP
				curl_setopt($ch, CURLOPT_REFERER, "http://www.milkcocoa.co.kr/ ");   //来路
			}
			if (strpos($sourceUrl, 'http://pody75.imglink.kr/') === 0 || strpos($sourceUrl, 'http://dailymonday.imglink.kr/') === 0){
				//dailymonday的反盗链
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.177.7.1', 'CLIENT-IP:61.177.7.1'));//IP
				curl_setopt($ch, CURLOPT_REFERER, "http://www.dailymonday.com/ ");   //来路
			}
			if (strpos($sourceUrl, 'http://file.pinkboll0626.cafe24.com/') === 0 ){
				//dailymonday的反盗链
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.177.7.1', 'CLIENT-IP:61.177.7.1'));//IP
				curl_setopt($ch, CURLOPT_REFERER, "http://pinkboll.co.kr/ ");   //来路
			}
			
			$data = curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			$counter--;
		} while (!is_file($filePath) && $counter > 0);
		
		//后续处理
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($ch, CURLOPT_TIMEOUT, 900);
		curl_setopt($ch, CURLOPT_URL, 'http://admin.fs-mall.com/urld/doPic/?goods_id='.$goods_id.'&filepath='.urlencode($filePath).'');
		curl_exec($ch);
		curl_close($ch);
	}
}
socket_close($socket);