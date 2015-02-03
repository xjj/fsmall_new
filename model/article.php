<?php
namespace model;

/**
 +------------------------------
 *	文章类
 +------------------------------
 */
class Article extends front {
	
	//添加
	function add($data){
		$title = trim($data['title']);
		$content = trim($data['content']);
		$short_url = trim($data['short_url']);
		$cat_id = intval($data['cat_id']);
		if (empty($title)){return false;}
		if ($cat_id <= 0){return false;}
		
		return $this -> db -> insert('operate_article', array(
			'title'	=> $title,
			'content' => $content,
			'cat_id' => $cat_id,
			'short_url'	=> $short_url,
			'displayorder' => intval($data['displayorder']),
			'add_time' => time()
		));
	}
	
	//获取文章信息
	function info($art_id){
		if (!isint($art_id) || $art_id <= 0){return false;}
		$sql= 'SELECT * FROM `operate_article` WHERE art_id = '.$art_id;
		return $this -> db -> row($sql);
	}
	
	//更新
	function update($art_id, $data){
		if (!isint($art_id) || $art_id <= 0){return false;}
		return $this -> db -> update('operate_article', $data, array('art_id' => $art_id));
	}
	
	//设置显示
	function validate($art_id, $status){
		if (!isint($art_id) || $art_id <= 0){return false;}
		if ($status != 1){$status = 0;}
		
		return $this -> db -> update('operate_article', array(
			'status' => $status
		), array(
			'art_id' => $art_id
		));
	}
	
	//删除
	function delete($art_id){
		if (!isint($art_id) || $art_id <= 0){return false;}
		return $this -> db -> delete('operate_article',' art_id= '.$art_id); 
	}
	
	//查询列表
	function search($params, $page, $pagesize){
		$cat_id = intval($params['cat_id']);
		$k = trim($params['k']);
		
		$where = 'WHERE 1=1 ';
		if ($cat_id > 0){
			$where .= ' AND a.cat_id = '.$cat_id.'';	
		}
		if (!empty($k)){
			$where .= ' AND (a.title like \'%'.encode($k).'%\' OR a.short_url like \'%'.encode($k).'%\') ';	
		}
		
		$sql = 'SELECT a.*, c.cat_name FROM `operate_article` a LEFT JOIN `operate_article_category` c ON a.cat_id = c.cat_id '.$where.' ORDER BY a.status DESC, a.art_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$article_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `operate_article` a '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $article_items,
			'total' => $total
		);
	}
}