<?php
namespace shop;
use model as md;
if (!defined('START')) exit('No direct script access allowed');
/**
 +----------------------------
 *	抓图 控制器 author:xjj
 +----------------------------
 */
class Urld extends Front {
	//图片下载目录
    public $_save_dir = UPLOAD_PATH;
	
	//基础目录
	public $_base_dir = '';
	//图片地址
	public $_base_url = IMG_SERVER;
	public $_callback = 'http://img2.fs-mall.com/tools/download/tb_callback.php';
	
	//分析链接正则 每个品牌可加多个正则 正则为商品链接页的地址  
 	public $mapping = array(
		//shezgood
		array(
			'pattern'=>"/shezgood\.com\/product\/detail\.html\?product_no=([\d]+)/i",
			'file'=>'brands/shezgood.php',
			'brand'=>'shezgood',
			'brand_id' => 1
		),
		array(
			'pattern'=>"/cherry(?:\-)?spoon[^\/]+\/front\/php\/product\.php\?product_no=([\d]+)/i", 
			'file'=>'brands/cherryspoon.php', 
			'brand'=>'cherryspoon',
			'brand_id' => 5
		),
		array(
			'pattern'=>"/cherry\-spoon\.com\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/cherryspoon.php', 
			'brand'=>'cherryspoon',
			'brand_id' => 5
		),
		array(
			'pattern'=>"/cherry-?spoon\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/cherryspoon.php', 
			'brand'=>'cherryspoon',
			'brand_id' => 5
		),
		//fiona
		array(
			'pattern'=>"/myfiona\.co\.kr\/shop\/shopdetail\.html\?brand(?:uid|code)=([\d]+)/i", 
			'file'=>'brands/fiona.php', 
			'brand'=>'fiona',
			'brand_id' => 7	
		),
		//mitoshop
		array(
			'pattern'=>"/mitoshop\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/mitoshop.php', 
			'brand'=>'mitoshop',
			'brand_id' => 15
		),
		//momnuri
		array(
			'pattern'=>"/momnuri\.com\/product\/product_detail\.asp\?[\s\S]*?product_code=([\d]+)/i", 
			'file'=>'brands/momnuri.php', 
			'brand'=>'momnuri',
			'brand_id' => 32
		),
		//pinksecret
		array(
			'pattern'=>"/pinksecret\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/pinksecret.php', 
			'brand'=>'pinksecret',
			'brand_id' => 73
		),
		//lovemarsh
		array(
			'pattern'=>"/lovemarsh\.com\/front\/php\/product\.php\?product_no=([\d]+)/i", 
			'file'=>'brands/lovemarsh.php', 
			'brand'=>'lovemarsh',
			'brand_id' => 77
		),
		//babirolen 
		array(
			'pattern'=> "/babirolen\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/i",
			'file'=>'brands/babirolen.php', 
			'brand'=>'babirolen',
			'brand_id' => 86	
		),
		array(
			'pattern'=> "/www\.babirolen\.com\/m\/product\.html\?branduid=([\d]+)/i",
			'file'=>'brands/babirolen.php', 
			'brand'=>'babirolen',
			'brand_id' => 86	
		),
		//attrangs
		array(
			//'pattern'=>"/attrangs\.co\.kr\/front\/php\/product\.php\?product_no=([\d]+)/i", 2014-03-05
			'pattern' => "/attrangs\.(?:co\.kr|com)\/product\/detail.html\?product_no=([\d]+)/i",
			'file'=>'brands/attrangs.php', 
			'brand'=>'attrangs',
			'brand_id' => 90
		),
		//miamasvin
		array(
			'pattern'=>"/miamasvin(?:\.co)?\.kr\/(?:front\/php\/product\.php|product\/detail\.html)\?product_no=([\d]+)/i", 
			'file'=>'brands/miamasvin.php', 
			'brand'=>'miamasvin',
			'brand_id' => 92
		),
		//bongjashop
		array(
			'pattern'=>"/bongjashop\.com\/shop\/view\.php\?index_no=([\d]+)/i", 
			'file'=>'brands/bongjashop.php', 
			'brand'=>'bongjashop',
			'brand_id' => 93
		),
		//midnightcoco|24coco
		array(
			'pattern'=>"/24coco\.com\/(?:product\/detail.html|front\/php\/product\.php)\?product_no=([\d]+)/i",  
			'file'=>'brands/midnightcoco.php', 
			'brand'=>'midnightcoco',
			'brand_id' => 94
		),
		//yansae|pink-heart  ?:yansae\.com|pink\-heart\.co\.kr
		array(
			'pattern'=>"/(?:pink\-heart\.co\.kr|yansae\.com|yansae\.co\.kr)\/front\/php\/product\.php\?product_no=([\d]+)/i", 
			'file'=>'brands/yansae.php', 
			'brand'=>'yansae',
			'brand_id' => 95
		),
		//stylestocker
		array(
			'pattern'=>"/stylestoker\.com\/Front\/Product\/\?url=Product&product_no=([\S]+)/i", 
			'file'=>'brands/stylestocker.php', 
			'brand'=>'stylestocker',
			'brand_id' => 96
		),
		//againby
		array(
			'pattern'=>"/againby\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/againby.php', 
			'brand'=>'againby',
			'brand_id'=> 98
		),
		//hellopeco
		array(
			'pattern'=>"/hellopeco\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/hellopeco.php', 
			'brand'=>'hellopeco',
			'brand_id' => 99
		),
		//milkcocoa
		array(
			'pattern'=>"/milkcocoa\.co\.kr\/shop\/detail\.php\?pno=([a-zA-Z0-9]+)/i", 
			'file'=>'brands/milkcocoa.php', 
			'brand'=>'milkcocoa',
			'brand_id' => 100
		),	
		//happymaman
		array(
			'pattern'=>"/happymaman(?:\.com|\.co\.kr)\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/happymaman.php', 
			'brand'=>'happymaman',
			'brand_id' => 101
		),
		//pinkbenny
		array(
			'pattern'=>"/pinkbenny\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/pinkbenny.php', 
			'brand'=>'pinkbenny',
			'brand_id' => 102
		),
		//nibbuns
		array(
			'pattern'=>"/nibbuns\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/nibbuns.php', 
			'brand'=>'nibbuns',
			'brand_id' => 103
		),
		//wifwif
		array(
			//'pattern'=>"/wifwif\.co\.kr\/shop\/detail\.php\?pno=([^&]+)/i", //2014-02-18 修改
			'pattern' => '/wifwif\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i',
			'file'=>'brands/wifwif.php', 
			'brand'=>'wifwif',
			'brand_id' => 105
		),
		//joamom
		array(
			'pattern'=>"/joamom\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/joamom.php', 
			'brand'=>'joamom',
			'brand_id' => 106
		),	
		//holicholic
		array(
			'pattern'=>"/holicholic\.com\/product\/detail\.html\?product_no=([\d]+)/", 
			'file'=>'brands/holicholic.php', 
			'brand'=>'holicholic',
			'brand_id' => 107
		),
		//jooen
		array(
			'pattern'=>"/jooen\.com\/front\/php\/product\.php\?product_no=([\d]+)/i", 
			'file'=>'brands/jooen.php', 
			'brand'=>'jooen',
			'brand_id' => 108,
		),
		array(
			'pattern'=>"/jooen\.com\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/jooen.php', 
			'brand'=>'jooen',
			'brand_id' => 108,
		),
		//mommymaru
		array(
			'pattern'=>"/mommymaru\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/mommymaru.php', 
			'brand'=>'mommymaru',
			'brand_id' => 109
		),
		//shesstory
		array(
			'pattern'=>"/shes\-story\.co\.kr\/Front\/Product\/\?url=Product&product_no=([^&]+?)/i", 
			'file'=>'brands/shesstory.php', 
			'brand'=>'shesstory',
			'brand_id' => 112
		),
		//blackmuse
		array(
			'pattern'=>"/blackmuse\.co\.kr\/front\/php\/product\.php\?product_no=([\d]+)/i", 
			'file'=>'brands/blackmuse.php', 
			'brand'=>'blackmuse',
			'brand_id' => 113
		),
		//loveloveme
		array(
			'pattern'=>"/loveloveme\.com\/product\/detail\.html\?product_no=([\d]+)/i", 
			'file'=>'brands/loveloveme.php', 
			'brand'=>'loveloveme',
			'brand_id' => 114
		),
		//eranzi
		array(
			'pattern'=>"/eranzi\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i", 
			'file'=>'brands/eranzi.php', 
			'brand'=>'eranzi',
			'brand_id' => 115
		),
		//pinkboll  
		array(
			'pattern'=>"/pinkboll\.co\.kr\/product\/detail\.html\?product_no=([^&]+)/i", 
			'file'=>'brands/pinkboll.php', 
			'brand'=>'pinkboll',
			'brand_id' => 116
		),
		array(
			'pattern'=>"/pinkboll\.co\.kr\/Front\/Product\/\?url=Product&product_no=([^&]+)/i", 
			'file'=>'brands/pinkboll.php', 
			'brand'=>'pinkboll',
			'brand_id' => 116
		),
		//dailymonday
		array(
			'pattern' => "/dailymonday\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/i",
			'file' => 'brands/dailymonday.php',
			'brand' => 'dailymonday',
			'brand_id' => 117,
		),
		//sonyunara
		array(
			'pattern' => "/sonyunara\.com\/shop\/view\.php\?index_no=([\d]+)/i",
			'file' => 'brands/sonyunara.php',
			'brand' => 'sonyunara',
			'brand_id' => 118,
		),
		//mybany
		array(
			'pattern' => "/mybany\.(?:com|co\.kr)\/Front\/Product\/\?url=Product&product_no=([^&]+)/i",
			'file' => 'brands/mybany.php',
			'brand' => 'mybany',
			'brand_id' => 119,
		),
		//yubsshop
		array(
			'pattern' => "/yubsshop\.com\/product\/detail\.html\?product_no=([^&]+)/i",
			'file' => 'brands/yubsshop.php',
			'brand' => 'yubsshop',
			'brand_id' => 120,
		),
		//wingsgirl
		array(
			'pattern' => "/wingsgirl\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/wingsgirl.php',
			'brand' => 'wingsgirl',
			'brand_id' => 121,
		),
		//wingsmall
		array(
			'pattern' => "/wingsmall\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/wingsmall.php',
			'brand' => 'wingsmall',
			'brand_id' => 122,
		),
		//styleforman 
		array(
			'pattern' => "/styleforman\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i",
			'file' => 'brands/styleforman.php',
			'brand' => 'styleforman',
			'brand_id' => 123,
		),
		#urbanholic
		array(
			'pattern' => "/urbanholic\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/urbanholic.php',
			'brand' => 'urbanholic',
			'brand_id' => 124,
		),
		#vivaruby
		array(
			'pattern' => "/vivaruby\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/vivaruby.php',
			'brand' => 'vivaruby',
			'brand_id' => 125,
		),
		//balibiki
		array(
			'pattern' => "/balibiki\.net\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/balibiki.php',
			'brand' => 'balibiki',
			'brand_id' => 126,
		),
		//shescoming
		array(
			'pattern' => "/shescoming\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/",
			'file' => 'brands/shescoming.php',
			'brand' => 'shescoming',
			'brand_id' => 127,
		),
		//lagirl 
		array(
			'pattern' => "/lagirl\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",
			'file' => 'brands/lagirl.php',
			'brand' => 'lagirl',
			'brand_id' => 128,
		),
		array(
			'pattern' => "/minsshop\.com\/product\/detail.html\?product_no=([\d]+)/",
			'file' => 'brands/minsshop.php',
			'brand' => 'minsshop',
			'brand_id' => 129,
		),
		array(
			'pattern' => "/minsshop\.com\/Front\/Product\/\?url=Product&product_no=([\s\S]+)/i",
			'file' => 'brands/minsshop.php',
			'brand' => 'minsshop',
			'brand_id' => 129,
		),
		array(
			'pattern' => "/limview\.co\.kr\/front\/php\/product.php\?product_no=([\d]+)/",
			'file' => 'brands/limview.php',
			'brand' => 'limview',
			'brand_id' => 130,
		),
		array(
			'pattern' => "/limview\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",
			'file' => 'brands/limview.php',
			'brand' => 'limview',
			'brand_id' => 130,
		),
		array(
			'pattern' => "/baby\-angel\.co\.kr\/shop\/goods\/goods_view\.php\?goodsno=([\d]+)/",
			'file' => 'brands/babyangel.php',
			'brand' => 'babyangel',
			'brand_id' => 131,
		),
		array(
			'pattern' => "/envylook\.com\/product\/detail\.html\?product_no==([\d]+)/",
			'file' => 'brands/envylook.php',
			'brand' => 'envylook10',
			'brand_id' => 132,
		),
		array(
			'pattern' => "/sugarfun\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",  
			'file' => 'brands/sugarfun.php',
			'brand' => 'sugarfun',
			'brand_id' => 133,
		),
		array(
			'pattern' => "/chocostars\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",   
			'file' => 'brands/chocostars.php',
			'brand' => 'chocostars',
			'brand_id' => 135,
		),
		array(
			'pattern' => "/marsh-mallow\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",   
			'file' => 'brands/Marsh-mallow.php',
			'brand' => 'Marsh-mallow',
			'brand_id' => 136,
		),
		array(
			'pattern' => "/soim\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/",   
			'file' => 'brands/Soim.php',
			'brand' => 'Soim',
			'brand_id' => 137,
		),
		array(
			'pattern' => "/envylook\.com\/product\/detail\.html\?product_no=([\d]+)/",
			'file' => 'brands/envylook.php',
			'brand' => 'envylook',
			'brand_id' => 138,
		),
		array(
			'pattern' => "/high-eny\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",
			'file' => 'brands/High-eny.php',
			'brand' => 'High-eny',
			'brand_id' => 139,
		),
		array(
			'pattern' => "/hotping\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",
			'file' => 'brands/hotping.php',
			'brand' => 'Hotping',
			'brand_id' => 140,
		),
		array(
			'pattern' => "/kanchogirl\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/",
			'file' => 'brands/kanchogirl.php',
			'brand' => 'Kanchogirl',
			'brand_id' => 141,
		),
		array(
			'pattern' => "/09women\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/",
			'file' => 'brands/09women.php',
			'brand' => '09women',
			'brand_id' => 142,
		),
		array(
			'pattern' => "/instylefit\.com\/product\/detail.html\?product_no=([\d]+)/",
			'file' => 'brands/instylefit.php',
			'brand' => 'instylefit',
			'brand_id' => 143,
		),
			array(
			'pattern' => "/sroom\.co\.kr\/shop\/detail.php\?([\s\S]*)pno=([\s\S]+)/",
			'file' => 'brands/sroom.php',
			'brand' => 'sroom',
			'brand_id' => 144,
		),
		array(
			'pattern' => "/soo-a\.co\.kr\/shop\/shopdetail\.html\?branduid=([\s\S]+)/",
			'file' => 'brands/soo-a.php',
			'brand' => 'soo-a',
			'brand_id' => 145,
		),
		array(
			'pattern' => "/unbutton\.co\.kr\/product\/detail\.html\?product_no=([\s\S]+)/",
			'file' => 'brands/unbutton.php',
			'brand' => 'unbutton',
			'brand_id' => 146,
		),
		array(
			'pattern' => "/happy10\.co\.kr\/shop\/shopdetail\.html\?branduid=([\s\S]+)/",
			'file' => 'brands/happy10.php',
			'brand' => 'happy10',
			'brand_id' => 148,
		),
	);
	//初始化
	function index(){
		if (isset($_POST['submit'])&&!empty($_POST['url'] )){
			$this -> get_pics();
			exit();
		}
		
		$brand = new md\brand();
		$data = $brand -> items(1); 
		$tboauth_status= $this->gettbauth_status();
		$this -> smarty ->assign('mapping',$data);
		$this -> smarty ->assign('tboauth_status',$tboauth_status);
		$this -> smarty -> display('urld.tpl');
	}
	//判断淘宝授权是否过期
	function gettbauth_status(){
		$taobao = new taobaooauth();
		$info = $taobao -> get_access_token();
		$msg='';
		//判断是否超过5000次
		$product_pics = new md\product_pics();
		$data = $product_pics -> search(array('add_time'=> 1));
		$counts =$data['num'];
		if ($counts >= 4995){
			$msg.= '淘宝空间API调用次数已达到5000次，已经超出了调用限制。<br />';
			$msg.= '请与管理员联系，重新设置API参数。';
		}
		if ($info){} else {
			$msg.= '淘宝空间API授权时间已过期，请重新登录以便获得授权。<br />';
			$msg.= '<a href="./tb_bind.php">点击这里进入授权页面</a><br />';
			$msg.= '注：使用帐号：curlbe 登录即可。授权需要手机[13270972073]的验证码。';
		}
		return $msg;
	}
	//抓图
	function get_pics(){
		$url= trim($_REQUEST['url']);
		if (!empty($url)){
			$product = new md\product();
			$data = $product -> search_by_array(array('kr_url'=>$url));
			$num= intval($data['total']);
			$goods_id= $data['items'][0]['prd_id'] ;
			if ($num <= 0){
				$msg.=  '找不到本地商品，请确保该商品已经发布，且填写了相同的韩国官网商品链接地址！或者在后台把商品韩国官网地址改成短的那种形式即可';
			} else {
				if ($num > 0){
					$msg.=  '如果图片抓取错误，请联系我，如果有黑图请重新抓取，如果抓两次都抓不到，请移动到手动上传那里。';
					$msg.=  '<br />';
				}
				//抓取操作 遍历 找到对应的品牌 匹配地址
				$flag = 0;
				foreach($this->mapping as $item){
					$pattern = $item['pattern'];
					$brand = $item['brand'];
					$brand_id = $item['brand_id'];
					$r = preg_match($pattern, $url, $matches);
					if ($r){
						$flag = 1;
						//设置图片保存位置
						$saveDir = $this->_save_dir."/details/".$brand."/".date('Y/md');///
						//创建文件夹
						$dir1 = $this->_save_dir.'/details';
						$dir2 = $dir1.'/'.$brand;
						$dir3 = $dir2.'/'.date('Y');
						$dir4 = $dir3.'/'.date('md');
						
						if(!is_dir($dir1)){ $r2= mkdir($dir1, 0777, true);}
						!is_dir($dir2) && mkdir($dir2, 0777, true);
						!is_dir($dir3) && mkdir($dir3, 0777, true);
						!is_dir($dir4) && $rm=mkdir($dir4, 0777, true);
						//dump($dir1);dump($r2);
						//抓取图片 每个品牌对应一个方法
						$funname=  downLarge_.$brand_id;
						if(!method_exists($this,$funname)){
							$msg.=  '错误：此品牌对应的抓图函数未定义！';
						} else {
							$ret =$this->$funname($url, $saveDir, $goods_id);//获得文件名
							if($ret == -1){
								$msg.=  "获取文章内容失败";
							} elseif ($ret == -2){
								$msg.=  "指定正则取不到图片";
							}elseif ($ret == -4){
								$msg.=  "韩国官网上不存在此商品";
							}
							elseif ($ret == -3){
								$msg.=  "未能抓取到源网页 可能商品断货或IP被屏蔽 ";
							}elseif (is_array($ret)){
								$msg.=  '<a href="http://www.fs-mall.com/goods.php?id='.$goods_id.'" target="_blank">点击这里查看商品图片抓取情况，图片下载可能有几秒的延迟。</a><br />';
								foreach($ret as $file){
									$file = str_replace($this->_save_dir, $this->_base_url, $file);
									$msg.=  "{$file}<br />";
								}
							} else {
								$msg.=  '错误：没有抓取到图片！';
							}
						}
						break;
					}
				}
				if ($flag == 0){
					$msg.=  "错误：商品页链接地址未找到匹配项！";//
				}
			}
		}
		$brand = new md\brand();
		$data = $brand -> items(1); 
		$this -> smarty ->assign('mapping',$data);
		$this -> smarty ->assign('msg',$msg);
		$this -> smarty -> display('urld.tpl');
	}
	
	//图片下载完成后的处理
	function dopic(){
		$goods_id = intval($_REQUEST['goods_id']);
		
		if (empty($goods_id)){
			exit('错误的商品id');
		}
		
		$filepath = urldecode($_REQUEST['filepath']);
		if (empty($filepath) || !file_exists($filepath)){
			exit('错误的图片地址');
		}
		
		$filepath = str_replace(' ', '%20', $filepath);
		
		//更新图片状态为已下载
		$r= $this->updateFileStatus($filepath, $goods_id, 1);
		

		//图片裁切
		$img = new doimage();
		$img -> thumb($filepath, 1000);
		$ret = $img -> crop($filepath, 4000);
		
		$number = 1;
		//更新图片状态为已裁切 2
		if ($ret) {
			$r2=$this->updateFileInfo(array(
				'filePath' => $filepath,
				'goods_id' => $goods_id,
				'number' => $ret['number'],
				'width' => $ret['width'],
				'height' => $ret['height'],
			));
			$number = $ret['number'];
		}
		//调用淘宝API上传图片  key需要跟授权的那个一致 才能上传 
		$taobao = new taobaooauth();
		//获取并设置access_token[sessonKey]
		$info = $taobao -> get_access_token();
		if ($info){
			$rt = $taobao -> upload($filepath);
			if (isset($rt['picture_upload_response'])){
				$pic_tb = $rt['picture_upload_response']['picture']['picture_path'];
				$pic_tb_id = $rt['picture_upload_response']['picture']['picture_id'];
				//更改为3
				$r3= updateFileTaobao($filepath, $goods_id, $pic_tb, $pic_tb_id);
				
			} else {
				print_r($rt);
				exit();
			}
			
			//上传截图
			if ($number > 1){
				for ($i = 0; $i < $number; $i++){
					$basedir  = dirname($filepath);
					$basename = basename($filepath);
					$newpath  = $basedir.'/'.str_replace('.jpg','-'.$i.'.jpg', $basename);
					$rt = $taobao -> upload($newpath);
					if (isset($rt['picture_upload_response'])){
						$pic2_tb = $rt['picture_upload_response']['picture']['picture_path'];
						$pic2_tb_id = $rt['picture_upload_response']['picture']['picture_id'];
						//裁切后是多张图的 插入 状态4
						insertFileTaobao(array(
							'filePath' => $newpath,
							'goods_id' => $goods_id,
							'pic_tb' => $pic2_tb,
							'pic_tb_id' => $pic2_tb_id,
							'pic_tb_pid' => $pic_tb_id
						));
					} else {
						print_r($rt);
					}		
				}
			}
		}else{
				file_put_contents(UPLOAD_PATH.'/test.txt', var_export($info,true),FILE_APPEND);

		}
		
	}

	/*批量保存到数据库
		---$data = array($sourcePic, $filePath, $goods_id);	
	*/
	function save_file_multi($data){
		$product_pics = new md\product_pics();
		$sql_insert = 'INSERT INTO `product_pics` (`prd_id`, `pic_wb`, `pic_fs`, `pic_tb`, `add_time`, `status`, `is_delete`) VALUES ';
		$or = '';
		$dt = '';
		foreach ($data as $item){
			
			$filePath = str_replace($this->_save_dir, $this->_base_url,$item['filePath']  );//???
			
			$goods_id = $item['goods_id'];
			$sourcePic = trim($item['sourcePic']);
			$sourcePic = str_replace('&amp;', '&', $sourcePic);//
			$sql_update = 'UPDATE `product_pics` SET is_delete = 1 WHERE `prd_id` = \''.$goods_id.'\'';
			$or = ' OR ';
			$sql_insert .= $dt.' (\''.$goods_id.'\', \''.encode($sourcePic).'\', \''.encode($filePath).'\', \'\', '.time().', 0, 0) ';
			$dt = ' , ';
		}
		$r= $this -> db->query($sql_update);
		#echosqlerr();
		$r2= $this ->db->query($sql_insert); // 先改后插
		#echosqlerr();
		#dump($r);dump($r2);dump($sql_update);
	}
	//保存到数据库 单个  这个没用   
	function saveFileToDB($sourcePic, $filePath, $goods_id){
		$product_pics = new md\product_pics();
		$filePath = str_replace($this->_save_dir.'/', '', $filePath);
		
		$product_pics -> update( array('is_delete' => 1), array(
			'prd_id' => $goods_id,
			'pic_wb' => $sourcePic
		));
		
		$pid =$product_pics -> insert( array(
			'prd_id' => $goods_id,
			'pic_wb' => $sourcePic,
			'pic_fs' => $filePath,
			'pic_tb' => '',
			'add_time' => time(),
			'status' => 0,
			'is_delete' => 0
		));
		return $pid;
	}
	//更新图片已下载
	function updateFileStatus($filePath, $goods_id,$status=1){
		$status = intval($status);
		
		$goods_id = intval($goods_id);
		$filePath = str_replace($this->_save_dir.'/', '', $filePath);
		$pic_fs = trim($filePath);
		$is_delete = 0;
		
		$product_pics= new md\product_pics();
		return $product_pics-> update(array('add_time' => time(),'status' => $status),array(
			'prd_id' => $goods_id,
			'pic_fs' => $filePath,
			'is_delete' => $is_delete
		));
	}
	//更新图片信息
	function updateFileInfo($data){
		$filePath = $data['filePath'];
		$filePath = str_replace($this->_save_dir.'/', '', $filePath);
		$product_pics= new md\product_pics();

		return $product_pics -> update( array(
			'add_time' => time(),
			'status' => 2,
			'width' => $data['width'],
			'height' => $data['height'],
			'number' => $data['number']
		), array(
			'prd_id' => $data['goods_id'],
			'pic_fs' => $filePath,
			'is_delete' => 0
		));
	}
	
	//更新图片已上传
	function updateFileTaobao($filePath, $goods_id, $pic_tb, $pic_tb_id){
		
		$filePath = str_replace($this->_save_dir.'/', '', $filePath);
				$product_pics= new md\product_pics();

		//更新图片状态 -- 已下载
		return $product_pics -> update( array(
			'add_time' => time(),
			'status' => 3,
			'pic_tb' => $pic_tb,
			'pic_tb_id' => $pic_tb_id
		), array(
			'prd_id' => $goods_id,
			'pic_fs' => $filePath,
			'is_delete' => 0
		));
	}
	
	function insertFileTaobao($data){
		$filePath = str_replace($this->_save_dir.'/', '', $data['filePath']);
						$product_pics= new md\product_pics();

		//更新图片状态 -- 已下载
		return $product_pics-> insert( array(
			'add_time' => time(),
			'status' => 4,
			'pic_tb' => $data['pic_tb'],
			'pic_tb_id' => $data['pic_tb_id'],
			'pic_tb_pid' => $data['pic_tb_pid'],
			'pic_fs' => $filePath,
			'prd_id' => $data['goods_id'],
			'is_delete' => 0
		));
	}
	//下载分配函数 遍历执行  
	function downFile($sourceUrl, $filePath, $goods_id){
		$data = "{$filePath}|{$sourceUrl}|{$goods_id}";
		$ports = array(
			9100,9101,9102,9103,9104,9105,9106,9107,9108,9109,
			9110,9111,9112,9113,9114,9115,9116,9117,9118,9119,
			9120,9121,9122,9123,9124,9125,9126,9127,9128,9129
		);
		$index = array_rand($ports);
		$port = $ports[0]; 
		$srv = 'udp://127.0.0.1:'.$port;
		$errno = 0;
		$errstr = '';
		$timeout = 300;
		$socket = stream_socket_client($srv, $errno, $errstr, $timeout);
		//dump($socket);
		if ($socket) {
			stream_set_timeout( $socket, $timeout);
			$r= fwrite( $socket, $data);
			
			fclose( $socket);
		}else{
			echo "ERROR: $errno - $errstr<br />\n";	
			//die;
		}
	}
		/*
	-------------------------------------
		 HTTP/HTTPS请求操作 ---- CURL(需要开启)
	-------------------------------------
	*/
	function request($url, $tar=''){
		$curl = curl_init();
		$headers =array(
			'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
'Accept-Encoding'=>'gzip, deflate, sdch',
'Accept-Language'=>'zh-CN,zh;q=0.8',
'Cache-Control'=>'max-age=0',
'Connection'=>'keep-alive',

'Cookie'=>'ACEFCID=UID-5492843FD5F1CD71D680B625; CTSFCID=UID-548FBF85FBC353DEDD55A85F; PHPSESSID=a1694ef1b8c61d14d87e89198bcad7db; wm_log_counter=2; click_prd=_5671; click_prd_5671=5671%A2%B7%A2%B963A8F9E307F0BF4473C24DD4DB17CEBD%A2%B7%A2%B9%BC%D2%B4%CF%BE%C6%BA%B8%C5%B8%C0%CC%B4%CF%C6%AE%A2%B7%A2%B910000%A2%B7%A2%B9_data%2Fproduct%2F200810%2F24%A2%B7%A2%B9%A2%B7%A2%B9%A2%B7%A2%B9%A2%B7%A2%B9ef89ecd4691211c6f268d300aff93ae7.jpg%A2%B7%A2%B9283%A2%B7%A2%B9412%A2%B7%A2%B986758cbd99530f89e5f27c22927df475.jpg%A2%B7%A2%B9283%A2%B7%A2%B9412; AVAP1A392407270=1418888256068556080%7C2%7C1418888256068556080%7C1%7C1418888256029K5M7V; _ga=GA1.3.512100619.1418888749; wcs_bt=s_67d0422b028:1418890496; ASAP1A392407270=1418888256068556080%7C1418890496035208000%7C1418888256068556080%7C0; ARAP1A392407270=http%3A//www.sroom.co.kr/shop/detail.php%3Fpno%3D63A8F9E307F0BF4473C24DD4DB17CEBDbookmark; _TRK_AUIDA_13262=7018e028a52023b8e78da319799a708a:1; _TRK_ASID_13262=3a20fbad56474088a20418d0a413e76c',
'Host'=>'www.sroom.co.kr',
'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
			);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		
		curl_setopt($curl, CURLOPT_URL, $url);//
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $tar);//发送内容的字符串
		$tmpInfo = curl_exec ($curl);
		curl_close ($curl);

        return $tmpInfo;
	}
	/*--------各品牌下载函数--------*/
	function downLarge_73($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return;
		$patternBigs = "/<IMG\s*src=\"(http:\/\/pinksecret\.co\.kr\/web\/img[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return;
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';/////
			$filenames[] = $filename;//
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this-> save_file_multi($data);//保存
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$r= $this->downFile($sourcePic, $filename, $goods_id);//下载
			}
		}
		return $filenames;
	}
	
	function downLarge_142($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		if(strpos($contents,'존재하지 않는 상품입니다')>=0) return(-4);
		if(strlen($contents)<1000) return(-3);
		//http://www.sucia.net/sre/imgs/2014/b/141020_14782_01.jpg
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/www\.sucia\.net\/sre\/imgs\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_1($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		
		if(strlen($contents)==0) return(-1);
		
		
		$patternBigs = "/<div id=\"prdDetail\"([\s\S]*?)id=\"prdChange\"/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		//$contents=  $matcheBigs[1];
		$contents = $matcheBigs[0][0];
		$patternBigs = "/<IMG src=\"(\/web\/prefix\/detail\/[^\"]+)\">(<BR(?: \/)?>)?(<BR(?: \/)?>)?/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0){
			$patternBigs = "/<center>[\s\S]*?<IMG src=\"(\/web\/prefix\/detail\/[^\"]+)\">[\s\S]*?<\/center>/i";
			preg_match_all($patternBigs, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0)return(-2);
				//return(-2); 
		}
		
		$data = array();
		$i = 0;
		$filenames = array();
		foreach($matcheBigs[1] as $val){
			$val = 'http://www.shezgood.com'.$val;
			
			$val = str_replace('&#9;','', $val);
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_5($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
		
		//过滤掉不必要的
		foreach($matcheBigs[1] as $k=>$v){
			if($v=='/web/upload/6-cod.jpg' || $v == '/web/2011/6338.jpg'){
				unset($matcheBigs[1][$k]);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://cherry-spoon.com'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
			
		}
		
		if (!empty($data)){
			$this->  save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->  downFile($sourcePic, $filename, $goods_id);
			}
		}
		
		return $filenames;
	}
	function downLarge_7($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$pattern = "/<P align=\"?center\"?>\s*<IMG[\s\S]*?src=\"([^\"]+)\"[\s\S]*?>/i";
		preg_match_all($pattern, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this-> save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this-> downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_77($sourceHtmlUrl, $saveDir, $goods_id){
		$filenames = array();
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/(?:\s*<P>&nbsp;<\/P>\s*)<P><IMG src=\"([^\"]+)\"><\/P>/i";
		$patternBigs = "/<IMG src=\"(http:\/\/lovemarsh\.com\/web\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this-> save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this-> downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_86($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<img[\s\S]+?src=\"(http:\/\/yeum790\.ktspeedway\.co\.kr\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_90($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		//http://aimg.sonyunara.com/bs/bs1258_07.jpg
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/(?:a?img\.sonyunara\.com|attrangs\.cafe24\.com\/attrangs)[^\"]+)\"[\s\S]*?>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_92($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG\s*src=\"((?:http:\/\/irisccc\.cafe24\.com)?\/(?:update|web\/product)\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1]) == 0) return (-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$val2 = strtolower($val);
			if (substr($val2, 0, 7) != 'http://'){
				$val = "http://www.miamasvin.co.kr/".$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_94($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<div id=\"prdDetail\">[\s\S]*?<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		$str = $matcheBigs[0][0];
		$pattern = "/<img[\s\S]*?src=\"([^\"]+)\"[^>]*?>/i";
		preg_match_all($pattern, $str, $matches);	
		//
		#var_dump( $matcheBigs);
		if(count($matches[1])==0) return(-2); //////
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matches[1] as $val){
			$xieyi = substr($val, 0, 7);
			if (strtolower($xieyi) != 'http://'){$val = 'http://www.24coco.com'.$val;}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_95($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/(?:yansae\.com|pink\-heart\.co\.kr|yansae\.co\.kr)\/web[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this-> downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_96($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/file\.bkenhano\.cafe24\.com\/Product\/Images[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this-> save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this-> downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_98($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[^\"]* src=\"(http:\/\/jcool2\.img10\.kr\/item\/[^\"]+)\">/i";
	
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) {
			$patternBigs2 = "/<IMG[^\"]* src=\"(http:\/\/hellopeco\.jpg2\.kr\/jcool2\/item\/[^\"]+)\">/i";
			preg_match_all($patternBigs2, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0) {
				return(-2);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		
		return $filenames;
	}
	function downLarge_99($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<img src=\"(http:\/\/jcool\.img9\.kr\/new\/[^\"]+)\">/i";
		#http://hellopeco.jpg2.kr/jcool/new/20140410/1/20140410_1_00.jpg
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) { #/web/web2/4486.jpg
			$patternBigs2 ="/<img src=\"(http:\/\/hellopeco\.jpg2\.kr\/jcool\/new\/[^\"]+)\">/i";
			preg_match_all($patternBigs2, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0) {
			return(-2);
			}
		}
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_101($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[^\"]* src=\"((?:http:\/\/happymaman\.co\.kr\/web\/web2\/|http:\/\/happymaman\.co\.kr\/web\/ss\/|http:\/\/www\.bmeshop\.co\.kr\/new\/)[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) { #/web/web2/4486.jpg
			$patternBigs2 = "/<IMG[^\"]* src=\"(\/web\/web2\/[^\"]+)\">/i";
			preg_match_all($patternBigs2, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0) {
				return(-2);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_102($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<p><img\s*?src=\"(http:\/\/(?:dearjane|pody75)\.(?:imglink\.kr|speedgabia\.com)\/[\s\S]*?\.jpg)\"><\/p>/i";;
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		
		return $filenames;
	}
	function downLarge_103($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG src=\"(http:\/\/piasom\.img9\.kr\/[\d]{4}_?[^\.]+\.jpg)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_105($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
	
		$patternBigs = "/<img\s*src=\"(http:\/\/(?:sky770077\.img9\.kr|wifwif01\.jpg2\.kr)\/detail(?:%20| )image\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) {
			$patternBig2 = "/<img\s*src=\"(http:\/\/sky770077\.img35\.makeshop\.co\.kr\/__manage__\/[^\"]+)\">/i";
			preg_match_all($patternBig2, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0) {
				return(-2);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$val = str_replace(array('%20', '&amp;'), array(' ', '&'), $val);
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_106($sourceHtmlUrl, $saveDir, $goods_id){
	
		if(preg_match("/(?:http:\/\/)?(?:www\.)?joamom\.co\.kr\/index\.html\?branduid=([\d]+)/", $sourceHtmlUrl, $ang)){
			$sourceHtmlUrl = "http://www.joamom.co.kr/shop/shopdetail.html?branduid={$ang[1]}";
		}
		
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG src=\"(http:\/\/joamom\.jpg2\.kr\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_107($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG\s*src=\"((?:http:\/\/(?:www\.)?holicholic\.com)?\/web\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(substr($val, 0, 4) != 'http') $val = "http://www.holicholic.com".$val;
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_108($sourceHtmlUrl, $saveDir, $goods_id){
	
		ob_start();
		$ch = curl_init(); 
		$headerArr = array(
			'Connection:keep-alive',
			'Keep-Alive:300',
			'Accept-Language:ko-kr,ko;q=0.8,en-us;q=0.5,en;q=0.3',
		);
		curl_setopt($ch, CURLOPT_URL, $sourceHtmlUrl); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
		$contents = curl_exec($ch); 
		curl_close($ch);
		$contents = ob_get_contents();
		ob_end_clean();
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<CENTER>\s*<IMG src=\"((?:http:\/\/[\s\S]*?\.com)?\/img\-up\/[^\"]+)\">\s*<\/CENTER>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0){
			//
			$patternBigs = "/<div class=\"cont\">[\s\S]*?<\/h3>[\s\S]*?<IMG src=\"((?:http:\/\/[\s\S]*?\.com)?\/img\-up\/[^\"]+)\">[\s\S]*?<\/div>/i";
			preg_match_all($patternBigs, $contents,$matcheBigs);
			if(count($matcheBigs[1])==0){
			 return(-2);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(substr($val,0,4) != 'http') {$val = 'http://www.jooen.com'.$val;}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_109($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		$contents = iconv('euc-kr', 'utf-8', $contents);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG.*?src=\"(http:\/\/mommymaru\.img8\.kr\/20[\d]{2}[^\"]+)\"[^>]*>/i";
		//$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/mommymaru\.img8\.kr\/[\s\S]*?jpg)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_113($sourceHtmlUrl, $saveDir, $goods_id){
		$needPrefix = 0;
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$matcheBigs = $matcheBigs1 = $matcheBigs2 = $matcheBigs3 = array();
		$patternBigs = "/<img src=\"(http:\/\/blackmuse\.co\.kr\/web\/ex[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs1);
			$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/image\.gmarket\.co\.kr\/[^\"]+)\"[^>]+?>/i";
			preg_match_all($patternBigs, $contents,$matcheBigs2);
			$patternBigs = "/<IMG src=\"(\/web\/ex\/[^\"]+)\">/i";
			if(preg_match_all($patternBigs, $contents, $matcheBigs3)){
					$needPrefix = 1;
					foreach($matcheBigs3[1] as $val){
						$matcheBigs[1][] = 'http://www.blackmuse.co.kr'.$val;
					}
			}
			if(!empty($matcheBigs1)){
					foreach($matcheBigs1[1] as $val){
						$matcheBigs[1][] = $val;
					}
			}
			if(!empty($matcheBigs2)){
					foreach($matcheBigs2[1] as $val){
						$matcheBigs[1][] = $val;
					}
			}
		if(count($matcheBigs[1])==0) return(-2);
	
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_116($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		
		
		if(strlen($contents)==0) return(-1);
		$pattern1 = '/<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"\s*?>\s*?<tr><td height=\"250px\"><\/td><\/tr>\s*?<\/table>\s*?<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"1100px\"\s*?>([\s\S]*?)<\/table>/i';
		
		preg_match_all($pattern1, $contents, $matche1);
		if(count($matche1[1])==0){//新版的div匹配
			$pattern1 = '/<div id=\"prdDetail\">([\S\s]*?)<div class=\"xans-recommend-display\">/i';
			//$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
			preg_match_all($pattern1, $contents, $matche1);
			
		}
		if(count($matche1[1])==0) return(-2);
		
		$patternBigs = "/<IMG[\s\S]*?src=\"([\s\S]*?)\">/i";
		preg_match_all($patternBigs, $matche1[1][0], $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_117($sourceHtmlUrl, $saveDir, $goods_id){
	
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<img src=\"(http:\/\/dailymonday\.imglink\.kr\/web\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0)return(-2); 
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_119($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/file\.(?:mybany1|yubsshop)\.cafe24\.com\/(?:mybany|yubs|Product\/Images)\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_120($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/src=\"([^\"]+)\"/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
		
		
		//过滤掉不必要的
		foreach($matcheBigs[1] as $k=>$v){
			if($v=='/design/kr/modelsize.jpg' || $v == '/design/kr/yubsplus.jpg'){
				unset($matcheBigs[1][$k]);
			}
		}
		
		/*
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/file\.(?:yubsshop|mybany1)\.cafe24\.com\/(?:yubs|Product\/Images)\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		*/
		
		
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://www.yubsshop.com'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_123($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
			$patternBigs = "/<IMG[\s\S]*?src=\"\s?(http:\/\/stfmimg\.sonyunara\.com\/files\/(?:goodssm|goods|product)\/[^\"]+)\">/i";
	
		//$patternBigs = http://stfmimg.sonyunara.com/files/product/2014-03/sk/0317-1_02.jpg;
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_125($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/vivaruby\.co\.kr\/web\/upload[^\"]+)\">/i";
		
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) {
			return(-2);
		}
			
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_126($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$patternBigs = "/<IMG[\s\S]+?src=\"((?:http:\/\/(?:www\.)?balibiki\.net)?\/web[\/]{1,2}(?:20[\d]{2}|product[\d]|upload[\d])\/[^\"]+)\"\s*?(?:width=700|width=\"700\"|width=\"850\"|width=850)?>/i";
		
		preg_match_all($patternBigs, $contents,$matcheBigs);
		
		if(count($matcheBigs[1])==0) {
			return(-2);
		}
			
		$filenames = array();
		$data = array();
		$i = 0;
		foreach($matcheBigs[1] as $val){
			
			if (strpos($val, 'http://') !== 0){
				$val = 'http://www.balibiki.net'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		
		return $filenames;
	}
	function downLarge_127($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/(?:aldydtlf3\.img9\.kr|tnlwm1\.diskn\.com)\/[^\"]+)\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_128($sourceHtmlUrl, $saveDir, $goods_id){
		
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(\/image\/[^\"]+)\"/i";
		# "/<IMG[^\"]* src=\"(http:\/\/jcool2\.img10\.kr\/item\/[^\"]+)\">/i";
		//http://hellopeco.jpg2.kr/jcool2/item/20140306/2/20140306_2_09.jpg
		//http://lagirl.co.kr/image/140509/dd.jpg
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);//
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => 'http://lagirl.co.kr'.$val,   //这个品牌的图片是相对路径  所以加上网站的网址
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_129($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$cnn = $matcheBigs[1][0];
		
		$pattern = '/<img[\s\S]*?src=\"([^\"]+)\"[^>]*>/i';
		preg_match_all($pattern, $cnn, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		
		$arr = array(
			'http://minsshop.com/web/upload/sunny/images/one.png',
			'http://minsshop.com/web/upload/sunny/images/detailmodel.png',
			'http://minsshop.com/web/upload/sunny/images/detailnotice.png',
		);
		
		foreach($matcheBigs[1] as $val){
			
			if (strpos($val, 'http://') !== 0){
				$val = 'http://minsshop.com'.$val;
			}
			
			if (in_array($val, $arr)){
				continue;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			
			$i += 1;
		}
	
		if (!empty($data)){
			
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_130($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$cnn = $matcheBigs[1][0];
		
		$pattern = '/<img[\s\S]*?src=\"([^\"]+)\"[^>]*>/i';
		preg_match_all($pattern, $cnn, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			if (strpos($val, 'http://') !== 0){
				$val = 'http://limview.co.kr'.$val;
			}
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_131($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$pattern = "/<IMG[\s\S]*?src=\"(http:\/\/maniaboarder\.diskn\.com\/\d{4}_SS\/[^\"]+)\"[\s\S]*?>/i";
		preg_match_all($pattern, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) {
			$patternBigs = "/<div id=\"contents\">([\s\S]*?)<br>/i";
			preg_match_all($patternBigs, $contents, $matcheBigs);
			if(count($matcheBigs[1])==0) return(-2);
			
			$cnn = $matcheBigs[1][0];
			//  /shop/lib/meditor/../../data/editor/e243d031b8ca9929
			/*$pattern = "/<IMG[\s\S]*?src=\"\/shop\/lib\/meditor\/[\s\S]*?>/i";*/
			$pattern = '/<img[\s\S]*?src=\"([^\"]+)\"[^>]*>/i';
			preg_match_all($pattern, $cnn, $matcheBigs);
			if(count($matcheBigs[1])==0) return(-2);
			}
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			if (strpos($val, 'http://') !== 0){
				$val = 'http://www.baby-angel.co.kr'.$val;
			}
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val, 
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_136($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/marsh-mallow\.co\.kr\/web\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0){
			 return(-2);
		}
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_137($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/ebbda12\.img4\.kr\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0){
			 return(-2);
		}
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_138($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
		//过滤掉不必要的
		foreach($matcheBigs[1] as $k=>$v){
			if($v=='/web/upload/ruben/images/envy_modelsize.jpg'){
				unset($matcheBigs[1][$k]);
			}
		}
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_139($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/high-eny\.co\.kr\/web\/up[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_140($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/gi\.esmplus\.com\/sseoqkr70\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_141($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<IMG[\s\S]*?src=\"(http:\/\/kanchogirl\.img11\.kr\/product\/[^\"]+)\"[^>]*?>/i";
		preg_match_all($patternBigs, $contents, $matcheBigs);
		if(count($matcheBigs[1])==0) return(-2);
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_143($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)<div id=\"prdRelated\">/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/src=\"([^\"]+)\"/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {////
				return (-2); 
			}////
		}
		//过滤掉不必要的
		foreach($matcheBigs[1] as $k=>$v){
			if($v=='/web/j215/top0.jpg' || $v == '/web/j215/top00.jpg' || $v == '/web/product/home/custom.jpg' || $v == '/web/product/home/modelsize11.jpg'){
				unset($matcheBigs[1][$k]);
			}
		}
		
		$filenames = array();
		$i = 0;
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://www.instylefit.com'.$val;
			}
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
	
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_144($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents= $this->request($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$patternBigs = "/<div id=\"detail5\">([\s\S]*?)id=\"srd_du_frame\"/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
		if(count($matcheBigs[0])==0) {
			die('div not match');
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			 $content =substr($content, strpos($content,'dtInfo'));
			$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://file.sroom.co.kr'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_145($sourceHtmlUrl, $saveDir, $goods_id){
	
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
		$contents2= substr($contents,0,strpos($contents,'id="malltb_video_player'));
		$contents= substr($contents2,strpos($contents2,'#imagebar'));
		$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
	
		if(count($matcheBigs[0])==0) {
		} else {
			if(count($matcheBigs[1])==0) {
				//return (-2); 
			}
		}
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://sooa1216.img7.kr'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
			
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_146($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
	
		$patternBigs = "/<div id=\"prdDetail\">([\s\S]*?)id=\"prdReview\"/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
			if(count($matcheBigs[0])==0) {
			die('div not match');
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/<img src=\"([^\"]+)\"[^>]*>/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
			
		//过滤掉模特介绍及文字说明  
		foreach($matcheBigs[1] as $k=>$v){
			if($v=='http://unbutton.co.kr/web/page/information.jpg'){
				unset($matcheBigs[1][$k]);
			}
		}
		
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			if(stripos($val,'http://') !== 0){
				$val = 'http://unbutton.co.kr'.$val;
			}
			
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
			
		}
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
	function downLarge_148($sourceHtmlUrl, $saveDir, $goods_id){
		$contents = file_get_contents($sourceHtmlUrl);
		if(strlen($contents)==0) return(-1);
	
		$patternBigs = "/id=\"rb-recmd-detail\">([\s\S]*?)id=\"malltb_video_player\"/i";
		preg_match_all($patternBigs, $contents,$matcheBigs);
			if(count($matcheBigs[0])==0) {
			die('div not match');
			return (-2); 
		} else {
			$content = $matcheBigs[0][0];
			$patternBigs = "/<IMG src=\"(http:\/\/happy1004cokr\.cafe24\.com\/[^\"]+)\"/i";
			preg_match_all($patternBigs, $content, $matcheBigs);
			
			if(count($matcheBigs[1])==0) {
				return (-2); 
			}
		}
		$i = 0;
		$filenames = array();
		$data = array();
		foreach($matcheBigs[1] as $val){
			//去除satr
			if(stripos($val,'star') ){
				continue;
			}
			$filename = $saveDir.'/'.date('His').'-'.$goods_id.'-no'.$i.'.jpg';
			$filenames[] = $filename;
			$data[] = array(
				'goods_id' => $goods_id,
				'sourcePic' => $val,
				'filePath' => $filename
			);
			$i += 1;
		}
		
		if (!empty($data)){
			$this->save_file_multi($data);
			foreach ($data as $item){
				$sourcePic = $item['sourcePic'];
				$filename = $item['filePath'];
				$this->downFile($sourcePic, $filename, $goods_id);
			}
		}
		return $filenames;
	}
}