<?php
namespace shop\api;

use model as md;
use api as api;

/**
 +--------------------------------------
 *	正品验证
 +--------------------------------------
 */
class Zhengpin_verify extends \Controller {
		//验证APPkey
	function verify_appkey(){
		$userOauth = array(
			1 => 'a3f9ce527aa0dda3b03435943fd87599',
		);
		$appid=  intval(trim($_POST['appid']));
		$appkey=  trim($_POST['appkey']);
		//
		if ( !empty($appid) &&!empty($appkey) && $userOauth[$appid] == $appkey){} else {
			echo json_encode(array('status' => -1, 'message' => 'access has been denied.请检查参数'));	
			exit();
		}
	}
	
	//正品验证
	function index(){ 
		$this->verify_appkey();
		
		$barcode=  trim($_POST['barcode']);//9/10位
		if (!isint($barcode)){
			echo json_encode(array(
				'status' => -1,
				'message' => 'paramter error.'
			));	
		}
		//初始状态
		$re=  array('status' => -1,'message' => '未查到此条码信息', 'data' => $barcode);

		
		$sql="select *,ob.id as ob_id from order_product_barcode ob left join order_product op on ob.op_id=op.op_id  where ob.barcode=$barcode  ";
		$r=$this->db->row($sql);
		if(!$r||!is_array($r)){
			
		}else{
			if(intval($r['verify_time'])!=0){
				$re['status']=2;
				$re['message']='此条码已失效';
			}
			if(intval($r['verify_time'])==0){
				$re['status']=1;
				$re['message']='success';
				$attr= unserialize( $r['prop_value']);
				
				$re['data']=array('barcode'=>$r['barcode'],
								  'product_sn'=>$r['product_sn'],
								  'product_name'=>$r['product_name'],
								  'product_name_kr'=>$r['product_name_kr'],
								  );
				$apimod= new md\api();
				$r2=$apimod->zhengpin_verify(intval($r['ob_id']));
			}
		}
		echo json_encode( $re  );
	}
}