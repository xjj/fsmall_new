<?php
namespace shop;
use model as md;
if (!defined('START')) exit('No direct script access allowed');

/**
 +----------------------------
 *	
 +----------------------------
 */
class Urld_brand extends Front {
	//分析链接正则 每个品牌可加多个正则 正则为商品链接页的地址  
 	public $mapping = array(
		//shezgood
		array(
			'pattern'=>"/shezgood\.com\/product\/detail\.html\?product_no=([\d]+)/i",
			'file'=>'brands/shezgood.php',
			'brand'=>'shezgood',
			'brand_id' => 1
		),
		
		//cherryspoon
		//http://www.cherryspoon.co.kr/product/detail.html?product_no=14222&cate_no=32&display_group=1
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
		//http://www.loveloveme.com/product/detail.html?product_no=14809&cate_no=24&display_group=1
		//'pattern'=>"/loveloveme\.com\/front\/php\/product\.php\?product_no=([\d]+)/i", 
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
		
		//pinkboll  http://www.pinkboll.co.kr/product/detail.html?product_no=17123&cate_no=30&display_group=1
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
		//http://yubsshop.com/product/detail.html?product_no=27637&cate_no=12&display_group=1
		///yubsshop\.com\/Front\/Product\/\?url=Product&product_no=([^&]+)/i
		array(
			'pattern' => "/yubsshop\.com\/product\/detail\.html\?product_no=([^&]+)/i",
			'file' => 'brands/yubsshop.php',
			'brand' => 'yubsshop',
			'brand_id' => 120,
		),
		
		//wingsgirl
		//http://www.wingsgirl.co.kr/product/detail.html?product_no=21193&cate_no=135&display_group=1
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
		//styleforman  http://www.styleforman.co.kr/shop/shopdetail.html?branduid=990&xcode=001&mcode=003&scode=001&special=3
		array(
			'pattern' => "/styleforman\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/i",
			'file' => 'brands/styleforman.php',
			'brand' => 'styleforman',
			'brand_id' => 123,
		),
		#http://www.urbanholic.co.kr/product/detail.html?product_no=2316&cate_no=1&display_group=3
		array(
			'pattern' => "/urbanholic\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/i",
			'file' => 'brands/urbanholic.php',
			'brand' => 'urbanholic',
			'brand_id' => 124,
		),
		#http://www.vivaruby.co.kr/product/detail.html?product_no=1893&cate_no=1&display_group=3
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
		//lagirl http://lagirl.co.kr/product/detail.html?product_no=6056&cate_no=1&display_group=2
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
			'pattern' => "/minsshop\.com\/Front\/Product\/\?url=Product&product_no=([\s\S]+)/i",///?url=Product&product_no=SFSELFAA0002236
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
			'pattern' => "/chocostars\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",   //http://chocostars.co.kr/product/detail.html?product_no=907&cate_no=1&display_group=7
			'file' => 'brands/chocostars.php',
			'brand' => 'chocostars',
			'brand_id' => 135,
		),
		array(
			'pattern' => "/marsh-mallow\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",   //http://www.marsh-mallow.co.kr/product/detail.html?product_no=14025&cate_no=1&display_group=2
			'file' => 'brands/Marsh-mallow.php',
			'brand' => 'Marsh-mallow',
			'brand_id' => 136,
		),
		array(
			'pattern' => "/soim\.co\.kr\/shop\/shopdetail\.html\?branduid=([\d]+)/",   //http://www.soim.co.kr/shop/shopdetail.html?branduid=68451
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
			//http://www.high-eny.co.kr/product/detail.html?product_no=10656&cate_no=1&display_group=3
			'file' => 'brands/High-eny.php',
			'brand' => 'High-eny',
			'brand_id' => 139,
		),
		array(
			'pattern' => "/hotping\.co\.kr\/product\/detail\.html\?product_no=([\d]+)/",
			//http://hotping.co.kr/product/detail.html?product_no=2532&cate_no=25&display_group=
			'file' => 'brands/hotping.php',
			'brand' => 'Hotping',
			'brand_id' => 140,
		),
		array(
			'pattern' => "/kanchogirl\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/",
			//http://www.kanchogirl.com/shop/shopdetail.html?branduid=926993&xcode=009&mcode=003&scode=&special=3&GfDT=bm53UFw%3D
			'file' => 'brands/kanchogirl.php',
			'brand' => 'Kanchogirl',
			'brand_id' => 141,
		),
		array(
			'pattern' => "/09women\.com\/shop\/shopdetail\.html\?branduid=([\d]+)/",
			//http://www.09women.com/shop/shopdetail.html?branduid=48331&special=1&GfDT=Z2t3UF8%3D
			'file' => 'brands/09women.php',
			'brand' => '09women',
			'brand_id' => 142,
		),
		array(
			'pattern' => "/instylefit\.com\/product\/detail.html\?product_no=([\d]+)/",
			//http://www.09women.com/shop/shopdetail.html?branduid=48331&special=1&GfDT=Z2t3UF8%3D
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
			//http://www.soo-a.co.kr/shop/shopdetail.html?branduid=147689
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
		//var_dump( $data);
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
		if( $taobao->status==0  ){
			$product_pics = new md\product_pics();
			$data = $product_pics -> search(array('timeline'=> 1));
			$counts =$data['num'];
			if ($counts >= 4995){
				$msg.= '淘宝空间API调用次数已达到5000次，已经超出了调用限制。<br />';
				$msg.= '请与管理员联系，重新设置API参数。';
			}
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
			$data = $product -> search_by_array(array(kr_url=>$url));
			$num= intval($data['total']);
			$goods_id= $data['items'][0]['prd_id'] ;
			//var_dump($num);
			//die; 
			//$goods_id = get_goods_id($url);
			if ($num <= 0){
				$msg.=  '找不到本地商品，请确保该商品已经发布，且填写了相同的韩国官网商品链接地址！或者在后台把商品韩国官网地址改成短的那种形式即可';
			} else {
				if ($num > 0){
					$msg.=  '如果图片抓取错误，请联系我，如果有黑图请重新抓取，如果抓两次都抓不到，请移动到手动上传那里。';
					$msg.=  '<br />';
				}
				//抓取操作
				$_save_dir = IMG_SERVER;
				$flag = 0;
//				echo 22;
    // var_dump($this->mapping );
				foreach($this->mapping as $item){
					$pattern = $item['pattern'];
					$brand = $item['brand'];
					//var_dump($brand); 
					$brand_id = $item['brand_id'];
					$pattern_file = dirname(__FILE__).'/'.$item['file'];//CONTROLLER_PATH.
					
					$r = preg_match($pattern, $url, $matches);
					if ($r){
						$flag = 1;
		
						//设置图片保存位置
						$saveDir = $_save_dir."/details/".$brand."/".date('Y/md');
						
						//创建文件夹
						$dir1 = $_save_dir.'/details';
						$dir2 = $dir1.'/'.$brand;
						$dir3 = $dir2.'/'.date('Y');
						$dir4 = $dir3.'/'.date('md');
						!is_dir($dir1) && mkdir($dir1, 0777, true);
						!is_dir($dir2) && mkdir($dir2, 0777, true);
						!is_dir($dir3) && mkdir($dir3, 0777, true);
						!is_dir($dir4) && mkdir($dir4, 0777, true);
						
						if(!is_file($pattern_file)){
							$msg.=  '错误：图片匹配文件不存在！';
							//echo $pattern_file;
						} else {
							//再写一个方法
							require_once($pattern_file);//
							//require_once('dispatch.php');//
							//写一个类  根据传的品牌 调用 对应的方法
							//$this->downLarge();
							//echo 11;							//抓取图片
							$ret = downLarge($url, $saveDir, $goods_id);
							die;
							if($ret == -1){
								$msg.=  "获取文章内容失败";
							} elseif ($ret == -2){
								$msg.=  "指定正则取不到图片";
							} elseif (is_array($ret)){
		
								$msg.=  '<a href="http://www.fs-mall.com/goods.php?id='.$goods_id.'" target="_blank">点击这里查看商品图片抓取情况，图片下载可能有几秒的延迟。</a><br />';
								foreach($ret as $file){
									$file = str_replace($_save_dir, $_base_url, $file);
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
		//var_dump( $data);
		
		$this -> smarty ->assign('mapping',$data);
		$this -> smarty ->assign('msg',$msg);
		$this -> smarty -> display('urld.tpl');
	}
	//批量保存到数据库
	//$data = array($sourcePic, $filePath, $goods_id);
	function save_file_multi($data){
			//调用模型  
		$product_pics = new md\product_pics();
		//$data = $product_pics -> items(1); 	
		global $_save_dir;
		
		$sql_update = 'UPDATE `product_pics` SET isdelete = 1 WHERE ';
		$sql_insert = 'INSERT INTO `product_pics` (`prd_id`, `pic_wb`, `pic_fs`, `pic_tb`, `add_time`, `status`, `is_delete`) VALUES ';
		$or = '';
		$dt = '';
		foreach ($data as $item){
			$filePath = str_replace($_save_dir.'/', '', $item['filePath']);
			$goods_id = $item['goods_id'];
			$sourcePic = trim($item['sourcePic']);
			$sourcePic = str_replace('&amp;', '&', $sourcePic);
			
			$sql_update .= $or.' (`prd_id` = \''.$goods_id.'\' AND `pic_wb` = \''.encode($sourcePic).'\') ';
			$or = ' OR ';
			
			$sql_insert .= $dt.' (\''.$goods_id.'\', \''.encode($sourcePic).'\', \''.encode($filePath).'\', \'\', '.time().', 0, 0) ';
			$dt = ' , ';
		}
		var_dump($sql_insert );
		var_dump($sql_update );
		die;
		$this -> query($sql_update);
		//$db -> cmd($sql_insert);

		
		
	}
	//下载分配函数
	function downFile($sourceUrl, $filePath, $goods_id){
		$data = "{$filePath}|{$sourceUrl}|{$goods_id}";
		$ports = array(
			9100,9101,9102,9103,9104,9105,9106,9107,9108,9109,
			9110,9111,9112,9113,9114,9115,9116,9117,9118,9119,
			9120,9121,9122,9123,9124,9125,9126,9127,9128,9129
		);
		$index = array_rand($ports);
		$port = $ports[$index]; 
		$srv = 'udp://127.0.0.1:'.$port;
		$errno = 0;
		$errstr = '';
		$timeout = 300;
		$socket = stream_socket_client($srv, $errno, $errstr, $timeout);
		if ($socket) {
			stream_set_timeout( $socket, $timeout);
			fwrite( $socket, $data);
			fclose( $socket);
		}
	}

}