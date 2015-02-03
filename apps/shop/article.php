<?php
namespace shop;

use model as md;

/**
 *	文章页面
 */
class Article extends Front {
	
	function index(){
		$this -> detail();
	}
	
	function detail(){
		$id = $this -> params[0];
		$f = false;	
		if (!isint($id) || $id <= 0){
			$f = true;	
		} else {
			$article = new md\article();
			$article_data = $article -> info($id);
			if ($article_data){} else {
				$f = true;	
			}
		}
		
		if ($f){
			cpmsg(false, '你访问的页面不存在，或已被移除！', -1);	
		}
		
		
		
		
		$this -> smarty -> assign('title', $article_data['title']);
		$this -> smarty -> assign('article_data', $article_data);
		$this -> smarty -> display('article.tpl');		
	}
}