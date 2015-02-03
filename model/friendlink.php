<?php
namespace model;

/**
 +------------------------------
 *	友情链接类
 +------------------------------
 */
class Friendlink extends Front {
	
	//列表
	function items($status = 1){
		$sql = 'SELECT * FROM `operate_friendlink` ';
		if ($status == 1){
			$sql .= ' WHERE status = 1 ';
		}
		$sql .= ' ORDER BY status DESC, displayorder ASC, id ASC';
		return $this -> db -> rows($sql);
	}
	
	//信息
	function info($id){
		if (!isint($id) || $id <= 0){return false;}
		
		$sql = 'SELECT * FROM `operate_friendlink` WHERE id = '.$id.'';
		return $this -> db -> row($sql);
	}
	
	//添加
	function add($data){
		$title = trim($data['title']);
		$url = trim($data['url']); 
		$logo = trim($data['logo']);
		$content = trim($data['content']);
		$displayorder = intval($data['displayorder']);
		
		if (empty($title)){return false;}
		if (empty($url)){return false;}
		if (strtolower(substr($url, 0, 7)) != 'http://'){return false;}
		
		return $this -> db -> insert('operate_friendlink', array(
			'title' => $title,
			'url' => $url,
			'logo' => $logo,
			'content' => $content,
			'displayorder' => $displayorder,
			'add_time' => time(),
			'status' => 1
		));
	}
	
	//删除
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		
		$info = $this -> info($id);
		if ($info){} else {return false;}
		
		return $this -> db -> delete('operate_friendlink', array('id' => $id));
	}
	
	//更新
	function update($id, $data){
		if (!isint($id) || $id <= 0){return false;}
		
		$title = trim($data['title']);
		$url = trim($data['url']); 
		$logo = trim($data['logo']);
		$content = trim($data['content']);
		$displayorder = intval($data['displayorder']);
		
		if (empty($title)){return false;}
		if (empty($url)){return false;}
		if (strtolower(substr($url, 0, 7)) != 'http://'){return false;}
		
		return $this -> db -> update('operate_friendlink', array(
			'title' => $title,
			'content' => $content,
			'logo' => $logo,
			'url' => $url,
			'displayorder' => $displayorder,
		), array(
			'id' => $id
		));
	}
	
	//显示
	function show($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('operate_friendlink', array('status' => 1), array('id' => $id));
	}
	
	//隐藏
	function hide($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('operate_friendlink', array('status' => 0), array('id' => $id));
	}
}