<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +--------------------------------
 *	淘宝数据包
 +--------------------------------
 */
class Tbdata extends adm\Front {
	//商品列表
	function items(){
		
		$page = intval($_GET['page']);
		$page = $page < 1 ? 1 : $page;
		$pagesize = 20;
		
		$prd = new md\product();
		$ret = $prd -> search($_GET, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		//分页设置
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET);}
		$params = fetch_url_query($GET);
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $GET
		));
		
		
		//查询所有类目信息
		$cat = new md\category();
		$cat_items = $cat -> layer_child_items(1, 1);
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items();
		
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('product/tbdata_product_list.tpl');
	}
	
	//批量做数据包
	function dotbdata_sel($have_name=1){
		
		$arr= $_REQUEST['prd_id'];
		
		if(is_array($arr)&&!empty($arr)){
			//var_dump($arr);
			foreach($arr as $v){
				 $this->dotbdata(intval($v),1,false);
			}
			cpmsg(true, '操作成功！', -1);
		}else{
			//var_dump($arr);
			cpmsg(false, '错误的请求！', -1);
			
		}
	}
	
		//做数据包
	function dotbdata($prd_id=0,$have_name=1,$cpmsg=true){
		define('HAVE_NAME',$have_name);
		if($prd_id==0){ //传参优先 无参则接收params
			$prd_id = $this -> params[1];
		}
		if (!isint($prd_id) || $prd_id <= 0){
			
			cpmsg(false, '错误的请求！', -1);
		}
		$HD = array();
		$prd_id=intval($prd_id);
		//获取商品信息
		$prd = new md\Product();
		$prd_data = $prd -> info($prd_id);
		if ($prd_data){} else {
			cpmsg(false, '该商品信息不存在或已被删除！', -1);
		}
		if($prd_data['is_spot']=='1'||$prd_data['is_on_sale']=='0'||$prd_data['is_soldout']=='1'||$prd_data['is_delete']=='1'){
			cpmsg(false, '该商品可能是现货或下架或断货或被删除 无法制作数据包！', -1);
		}
		$arr[0]=$prd_data;
		//查询类目信息
		$cat = new md\category();
		$cat_data = $cat -> info($prd_data['cat_id']);
		//属性
		$product_attr = new md\product_attr();
		$prd_tb_attr = $product_attr -> items($prd_id);
		$prd_tb_attr_str='';
		foreach($prd_tb_attr as $v){
			$prd_tb_attr_str.= $v['tb_attr_id'].':'.$v['tb_attr_value'].';'; 	
		}
		//销售属性
		$Product_sku = new md\Product_SKU();
		$prd_prop = $Product_sku -> items($prd_id,0);
			
		$prd_prop_str='';
		$prd_prop_str_alias='';
		foreach($prd_prop as $k=>$v){
			$prd_prop_str .= $prd_data['price'].':100::';
			$temp= $this->get_tb_prop($v['prop_value_ids'] );  
			//var_dump( $temp);
			$prd_prop_str .= $temp[0].';'.$temp[1].';' ;
			$temp2= explode(':',$v['prop_value'][$v['prop_value_ids'][0]]);
			$temp3= explode(':',$v['prop_value'][$v['prop_value_ids'][1]]); 
			$prd_prop_str_alias .= $temp[0].':'.$temp2[1].';'.$temp[1].':'.$temp3[1].';' ;
		}
		//必须先获取分类
		foreach ( $arr as $c ){
			//字段转换一下 要替换的太多了
			$c['goods_img'] = $c['pic'];
			$imgFileName = ''.md5('lexm'.$c['prd_id']).'.tbi';//lexm+商品id  md5  .tbi
			$flag = 0;
			$quitthis = 0;
			$ccfd = "";
			$ccf = $ccfd.$c['prd_id'].'.tbi'; ///存在就读取  you商品id的tbi优先读取
			if(file_exists($ccf)){
				$imgContent = file_get_contents($ccf);
			}else{//无 则 停500ms  然后继续
				usleep(500);
				while(1){
					$c['goods_img'] = str_replace(" ", "%20", $c['goods_img']);
					if (strpos($c['goods_img'], 'http://') === false){
						$c['goods_img'] = ltrim($c['goods_img'], '/');
						$c['goods_img'] = 'http://www.fs-mall.com/'. $c['goods_img'];//换成fs上的图片地址
					}
					$imgContent = file_get_contents($c['goods_img']);//读取
					if($flag++ >10) {$quitthis = 1; break;} //10张退出
					if( !empty($imgContent)&& substr($imgContent,0,6) != '<html>') break;//以html开头 退出循环
					usleep(500);
				}
				if($quitthis != 1) //写入到 tbi中   
					file_put_contents($ccf, $imgContent);
				else
					continue;
			}
			file_put_contents($imgFileName, $imgContent);//最终写到md5过的tbi中 
			
			$line = array();
			$c['goods_cn_name']= $c['product_name'];
			$c['goods_hh']= $c['product_sn'];
			
			if(HAVE_NAME == 1){
				$line[0] = $baobeimingcheng = $c['goods_cn_name'].' '.$c['goods_hh'];
			}else{
				$line[0] = $baobeimingcheng = $c['goods_hh'];
			}
			
			$line[1] = $baobeileimu = $cat_data['tb_cat_id'];//淘宝的类目id?
			$line[2] = $dianpuleimu = '';
			$line[3] = $xinjiuchengdu = 0;
			$line[4] = $sheng = '海外';
			$line[5] = $chengshi = '韩国';
			$line[6] = $chushoufangshi = 'b';
			$line[7] = $baobeijiage = round($c['price']*1.25);
			$line[8] = $jiajiafudu = 0;
			$line[9] = $baobeishuliang = 300;
			$line[10] = $youxiaoqi = 7;
			$line[11] = $yunfeichengdan = 1;
			$line[12] = $pingyou = 20;
			$line[13] = $ems = 20;
			$line[14] = $kuaidi = 10;
			$line[15] = $fukuanfangshi = '';
			$line[16] = $zhifubao = '';
			$line[17] = $fapiao = 0;
			$line[18] = $baoxiu = 0;
			$line[19] = $shandianfahuo = 0;
			$line[20] = $zidongchongfa = 1;
			$line[21] = $fangrucangku = 0;
			$line[22] = $chuchuangtuijian = 0;
			$line[23] = $kaishishijian = '1980/1/1  0:00:00';
			$line[24] = $xinqinggushi = '';
				
			$line[25] = $baobeimiaoshu = $c['content'];//-----?
			$line[26] = $baobeitupian = '';
			
				//print_r( $prd_tb_attr);
			$line[27] = $baobeishuxing = $prd_tb_attr_str."1632501:2147483647;20000:2147483647;";
			$line[28] = $tuangoujia = '';
			$line[29] = $zuixiaotuangoujianshu = '';
			$line[30] = $youfeimubanid = 0;
			$line[31] = $huiyuandazhe = 0;
			$line[32] = $xiugaishijian = '';
			$line[33] = $shangchuanzhuangtai = 200;
			$line[34] = $tupianzhuangtai = '';
			$line[35] = $fandianbili = 0;
			$line[36] = $xintupian = md5('lexm'.$c['prd_id']).':0:0:|;';
			$line[37] = $shipin = '';

			$line[38] = $prd_prop_str;//销售属性 ?
			$line[39] = $yonghushuruidchuan = ',20000,1632501';
			$line[39] = $yonghushuruidchuan = '20000,1632501';
				$exp = explode('-', $c['goods_hh']);
			$line[40] = $yonghushurumingzhidui = ",{$exp[0]},{$c['goods_hh']}";
			$line[40] = $yonghushurumingzhidui = "{$exp[0]},{$c['goods_hh']}";
			$line[41] = $shangjiabianma = $c['goods_hh'];
			
			$line[42] = $xiaoshoushuxingbieming = substr($prd_prop_str_alias, 0, -1);//销售属性别名 
			
			$line[43] = $daichongleixing = 0;
			$line[44] = $baobeibianhao = '';
			$line[45] = $shuziid = 0;
			$line[46] = 1;
			$line[47] = 2;
			$line[48] = '韩国';
			$line[49] = 0;
			
			$HD[] = $line;	
			
			unset($line);
		}
		//写入csv
		$chg = $this->array2csv($HD);
		file_put_contents("{$prd_id}.csv", $chg);
		//$chg= str_replace('"','',$chg);
		//$chg=mysql_real_escape_string($chg);
		
		//var_dump($HD);
		
		$chg = iconv(  'GB2312','UTF-8', $chg);
		//echo $chg.'<hr>';
		//写入表中
		$Product_tbdata = new md\Product_tbdata();
		$data= array('prd_id'=>$prd_id,
					'csv'=> $chg ,
					'pic'=>$imgFileName,
					'adm_uid'=> $_SESSION['admin']['uid'],
					  );
		$f = $Product_tbdata -> add($data);
		// var_dump($f);
		// echo mysql_error();
		if($cpmsg==true){
			if ($f){
				cpmsg(true, '操作成功！', -1);
			} else {
				//echo mysql_error();
				//var_dump($data);
				cpmsg(false, '操作失败！', -1);
			}
		}
	}
	
	//获取属性id对应的淘宝销售属性id
	function get_tb_prop($arr){
		$TB_Prop= new md\TB_Prop();
		if(count($arr)<2){ return false; }
		$re='';
		foreach($arr as $v){
			$temp= explode(':',$v);
			$r= $TB_Prop-> get_tb_prop( $temp[0] ,0);
			$s= $TB_Prop-> get_tb_prop( $temp[1] ,1);
			$re=$r['tb_prop_id'].':'.$s['tb_prop_id'].'';
			$ret[]= $re;
		}
		return $ret;
	}
	
	function array2csv(array &$array){
	   if (count($array) == 0) {
		 return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   unset($row);
	   foreach ($array as &$row) {
		  foreach($row as $kk => &$vv){
			$row[$kk] = iconv( 'UTF-8', 'GB2312', $vv);
		}
		  fputcsv($df, $row, chr(9));
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	/*----------------------------------分割线 下面的东西没用到 ----------------------------*/
}