<?php
namespace admin\operate;

use model as md;
use admin as adm;

/**
 +------------------------------
 *	活动页面
 +------------------------------
 */

class Event extends adm\Front {
	
	function items(){
		
		$this -> smarty -> display('operate/event_list.tpl');	
	}
}
