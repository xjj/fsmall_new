<?php
namespace model;

/**
 +-----------------------------
 *	
 +-----------------------------
 */
class User_upgrade_apply extends Front {
		//查询
	function search($params, $page, $pagesize){

		$where=' where  app.status=0 ';
		$sql = 'SELECT u.uname, app.* FROM `user_upgrade_apply` app LEFT JOIN `user_info` u ON app.uid = u.uid '.$where.' ORDER BY app.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `user_upgrade_apply` app LEFT JOIN `user_info` u ON app.uid = u.uid '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $items,
			'total' => $total
		);	
	}
		//编辑
	function update($id, $data){
		if (!isint($id) || $id <= 0){return false;}
		
		$status = intval($data['status']);
		$adm_uid = intval($data['adm_uid']);
		
		return $this -> db -> update('user_upgrade_apply', array(
			'status' => $status, 
			'check_time' => time(),
			'adm_uid' => $adm_uid
		), array(
			'id' => $id
		));
	}
}