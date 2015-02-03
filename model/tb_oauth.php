<?PHP
namespace model;

/**
 +------------------------------
 *	淘宝认证 模型
 +------------------------------
 */

class Tb_oauth extends Front {
	
	//查询
	function search($taobao_user_id, $page=1, $pagesize=10){
		$taobao_user_id = trim($taobao_user_id);
		$sql = 'SELECT * FROM `system_tb_oauth` where taobao_user_id = '.$taobao_user_id.'  LIMIT '.($page-1)*$pagesize.','.$pagesize;
		//echo $sql;
		$items = $this -> db -> rows($sql);	
		$sql = 'SELECT COUNT(*) AS num FROM `system_tb_oauth` where taobao_user_id = '.$taobao_user_id;

		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}	
	
}