<?PHP
namespace model;

/**
 +------------------------------
 *	供应商 发货账目统计类
 +------------------------------
 */

class Suplier_account extends Front {
	
	//查询账目列表
	function search($brand_id, $params, $page, $pagesize){
		$start_time = $params['start_time'];
		$end_time = $params['end_time'];
		
		$where = ' WHERE brand_id = '.$brand_id.' AND (amount_send > 0 OR amount_refund > 0)';
		if (isDate($start_time)){
			$where .= ' AND `date` >= '.strtotime($start_time).'';
		}
		if (isDate($end_time)){
			$where .= ' AND `date` <= '.strtotime($end_time).'';
		}
		if ($where == ''){
			$dt = date('Y-m-d');
			$time = strtotime($dt) - 60*24*3600; //最近60天	
			$where .= ' AND `date` >= '.$time.'';
		}
		
		//查询发货记录
		$sql = 'SELECT * FROM `suplier_account` '.$where.' ORDER BY `date` DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$account_items = array();
			foreach ($rows as $row){
				$m = date('Ym', $row['date']);
				$account_items[$m][] = $row;
			}
		} else {
			$account_items = false;
		}
		
		$sql = 'SELECT COUNT(*) AS num FROM `suplier_account` '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $account_items,
			'total' => $total
		);
	}
}