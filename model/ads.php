<?PHP
namespace model;

/**
 +------------------------------
 *	广告 模型
 +------------------------------
 */

class Ads extends Front {
	
	//查询
	function search($adp_code, $page=1, $pagesize=10){
		$adp_code = trim($adp_code);
		$now=time();
		//判断当前时间是否过期 有效
		$where = " starttime<=$now and endtime>=$now and adi.status=1 ";
		if (!empty($adp_code) ){
			$where .= " and adp.adp_code = '".$adp_code."'";	
		}
		
		$sql = 'SELECT * FROM `operate_ad_position` adp left join `operate_ad_item` adi on adp.adp_id=adi.adp_id  WHERE 1 '.$where.'  LIMIT '.($page-1)*$pagesize.','.$pagesize;

		$items = $this -> db -> rows($sql);	
		$sql = 'SELECT COUNT(*) AS num FROM `operate_ad_position` adp left join `operate_ad_item` adi on adp.adp_id=adi.adp_id  WHERE 1 '.$where.'';

		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}	
	
}