<?php
require_once('./config.php');
//下载分配函数
function downFile($sourceUrl, $filePath, $goods_id){
	$data = "{$filePath}|{$sourceUrl}|{$goods_id}";
	$ports = array(
		9100,9101,9102,9103,9104,9105,9106,9107,9108,9109,
		9110,9111,9112,9113,9114,9115,9116,9117,9118,9119,
		9120,9121,9122,9123,9124,9125,9126,9127,9128,9129
	);
	$index = array_rand($ports);
	$port = $ports[$index]; 
	$srv = 'udp://127.0.0.1:'.$port;
	$errno = 0;
	$errstr = '';
	$timeout = 300;
	$socket = stream_socket_client($srv, $errno, $errstr, $timeout);
	if ($socket) {
		stream_set_timeout( $socket, $timeout);
		fwrite( $socket, $data);
		fclose( $socket);
	}
}

//保存到数据库
function saveFileToDB($sourcePic, $filePath, $goods_id){
	$db = getdb();
	
	global $_save_dir;
	$filePath = str_replace($_save_dir.'/', '', $filePath);
	
	$db -> update('ecs_goods_pics', array('isdelete' => 1), array(
		'goods_id' => $goods_id,
		'pic_wb' => $sourcePic
	));
	
	$pid = $db -> insert('ecs_goods_pics', array(
		'goods_id' => $goods_id,
		'pic_wb' => $sourcePic,
		'pic_fs' => $filePath,
		'pic_tb' => '',
		'timeline' => time(),
		'status' => 0,
		'isdelete' => 0
	));
	return $pid;
}

//批量保存到数据库
//$data = array($sourcePic, $filePath, $goods_id);
function save_file_multi($data){
	$db = getdb();
	global $_save_dir;
	
	$sql_update = 'UPDATE `ecs_goods_pics` SET isdelete = 1 WHERE ';
	$sql_insert = 'INSERT INTO `ecs_goods_pics` (`goods_id`, `pic_wb`, `pic_fs`, `pic_tb`, `timeline`, `status`, `isdelete`) VALUES ';
	$or = '';
	$dt = '';
	foreach ($data as $item){
		$filePath = str_replace($_save_dir.'/', '', $item['filePath']);
		$goods_id = $item['goods_id'];
		$sourcePic = trim($item['sourcePic']);
		$sourcePic = str_replace('&amp;', '&', $sourcePic);
		
		$sql_update .= $or.' (`goods_id` = \''.$goods_id.'\' AND `pic_wb` = \''.encode($sourcePic).'\') ';
		$or = ' OR ';
		
		$sql_insert .= $dt.' (\''.$goods_id.'\', \''.encode($sourcePic).'\', \''.encode($filePath).'\', \'\', '.time().', 0, 0) ';
		$dt = ' , ';
	}
	$db -> cmd($sql_update);
	$db -> cmd($sql_insert);
}

//更新图片已下载
function updateFileStatus($filePath, $goods_id){
	
	global $_save_dir;
	$filePath = str_replace($_save_dir.'/', '', $filePath);
	$db = getdb();
	
	//更新图片状态 -- 已下载
	$db -> update('ecs_goods_pics', array(
		'timeline' => time(),
		'status' => 1
	), array(
		'goods_id' => $goods_id,
		'pic_fs' => $filePath,
		'isdelete' => 0
	));
}

//更新图片信息
function updateFileInfo($data){
	global $_save_dir;
	
	$filePath = $data['filePath'];
	$filePath = str_replace($_save_dir.'/', '', $filePath);
	
	$db = getdb();
	
	$db -> update('ecs_goods_pics', array(
		'timeline' => time(),
		'status' => 2,
		'width' => $data['width'],
		'height' => $data['height'],
		'number' => $data['number']
	), array(
		'goods_id' => $data['goods_id'],
		'pic_fs' => $filePath,
		'isdelete' => 0
	));
}

//更新图片已上传
function updateFileTaobao($filePath, $goods_id, $pic_tb, $pic_tb_id){
	
	global $_save_dir;
	$filePath = str_replace($_save_dir.'/', '', $filePath);
	$db = getdb();
	
	//更新图片状态 -- 已下载
	$db -> update('ecs_goods_pics', array(
		'timeline' => time(),
		'status' => 3,
		'pic_tb' => $pic_tb,
		'pic_tb_id' => $pic_tb_id
	), array(
		'goods_id' => $goods_id,
		'pic_fs' => $filePath,
		'isdelete' => 0
	));
}

function insertFileTaobao($data){
	global $_save_dir;
	$filePath = str_replace($_save_dir.'/', '', $data['filePath']);
	$db = getdb();
	
	//更新图片状态 -- 已下载
	$db -> insert('ecs_goods_pics', array(
		'timeline' => time(),
		'status' => 4,
		'pic_tb' => $data['pic_tb'],
		'pic_tb_id' => $data['pic_tb_id'],
		'pic_tb_pid' => $data['pic_tb_pid'],
		'pic_fs' => $filePath,
		'goods_id' => $data['goods_id'],
		'isdelete' => 0
	));
}
?>