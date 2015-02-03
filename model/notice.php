<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +----------------------
 *	公告
 +----------------------
 */
class Notice extends Front {
	
	//信息
	function info($id){
		if (!isint($id) || $id <= 0){return false;}
		$sql = 'SELECT * FROM `operate_notice` WHERE id = '.$id;
		return $this -> db -> row($sql);
	}
	
	//添加
	function add($data){
		$title = trim($data['title']);
		$content = trim($data['content']);
		$auth = trim($data['auth']);
		$is_top = trim($data['is_top']);
		
		if (empty($title)){return false;}
		if (!in_array($auth, array(0,1,2))){$auth = 0;}
		if ($is_top != 1){$is_top = 0;}
		
		return $this -> db -> insert('operate_notice', array(
			'title' => $title,
			'content' => $content,
			'auth' => $auth,
			'is_top' => $is_top,
			'add_time' => time(),
			'visit_count' => 0,
			'status' => 1
		));
	}
	
	//编辑
	function edit($id, $data){
		if (!isint($id) || $id <= 0){return false;}
		
		$title = trim($data['title']);
		$content = trim($data['content']);
		$auth = intval($data['auth']);
		if (!in_array($auth, array(0,1,2))){$auth = 0;}
		$is_top = $data['is_top'];
		if ($is_top != 1){$is_top = 0;}
		
		if (empty($title)){return false;}
		
		return $this -> update($id, array(
			'title' => $title,
			'content' => $content,
			'auth' => $auth,
			'is_top' => $is_top, 
		));
	}
	
	//更新
	function update($id, $data){
		return $this -> db -> update('operate_notice', $data, array('id' => $id));
	}
	
	//删除
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> delete('operate_notice', array('id' => $id));
	}
	
	//显示
	function show($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('operate_notice', array('status' => 1), array('id' => $id));
	}
	
	//隐藏
	function hide($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('operate_notice', array('status' => 0), array('id' => $id));
	}
	
	
	//前台查询
	function search($params, $page, $pagesize){
		$k = trim($params['k']);	
		$grade_id = intval($params['grade_id']);
		
		$where = 'WHERE status = 1';
		if (!empty($k)){
			$where .= ' AND title like \'%'.encode($k).'%\'';	
		}
		
		if ($grade_id >= 2){ //批发会员
		} elseif ($grade_id == 1){ //注册会员
			$where .= ' AND auth <= 1';	
		} else {
			$where .= ' AND auth = 0';	
		}
		
		$sql = 'SELECT * FROM `operate_notice` '.$where.' ORDER BY is_top DESC, id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$notice_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `operate_notice` '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];	
		
		return array(
			'items' => $notice_items,
			'total' => $total
		);
		
	}
	
	//后台查询
	function search2($params, $page, $pagesize){
		
		$sql = 'SELECT * FROM `operate_notice` ORDER BY is_top DESC, id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$notice_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `operate_notice`';
		$row = $this -> db -> row($sql);
		$total = $row['num'];	
		
		return array(
			'items' => $notice_items,
			'total' => $total
		);
	}
}