<?php
set_time_limit(50);
//define( "WEB_ROOT", str_replace('/api/mainten.php','',__FILE__) ); //网站根目录
//include_once( str_replace('/api/mainten.php','',__FILE__) .'/webmanage/init.inc.php');
require( str_replace('/api/mainten.php','',__FILE__) .'/include/config.inc.php' );
require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();
require(WEB_ROOT.'/include/function_common.inc.php');


//添加维护日志
$file = fopen(WEB_ROOT . "/api/mainten.log","a");
fwrite($file,date('Y-m-d H:i:s')."\n");


//$where = " AND mainten_status='1' AND ( mainten_start <= '".time()."' AND mainten_end >= '".time()."' ) ";
$where = " AND mainten_status='1' ";
$sql = "SELECT * FROM ".DB_PREFIX."mainten WHERE 1 $where ";
$rs = $db->getAll($sql);
if($rs)
{
	
	foreach($rs as $v)
	{
		$start = date('Y-m-d H:i',$v['mainten_start']);
		$end = date('Y-m-d H:i',$v['mainten_end']);
		$now = date('Y-m-d H:i',time());
		echo($start.' '.$now.' '.$end);
		if($start == $now){
			//$db->update(array('game_status'=>'0'),DB_PREFIX."game",$v['mainten_gid']);
			if(empty($v['mainten_sid'])){
				$db->query("update ".DB_PREFIX."server set server_status='0' where server_gid='$v[mainten_gid]' ");
			}else{
				$db->query("update ".DB_PREFIX."server set server_status='0' where server_id='$v[mainten_sid]' ");
			}
			//$db->update(array('server_status'=>'0'),DB_PREFIX."server",$v['mainten_sid']);
			fwrite($file,$start.' [game_'.$v['mainten_gid'].']=>closed　, [server_'.$v['mainten_sid'].']=>closed ');
		}
		
		if($end == $now){
			//$db->update(array('game_status'=>'1'),DB_PREFIX."game",$v['mainten_gid']);
			if($v['mainten_type']=='0'){
				if(empty($v['mainten_sid'])){
					$db->query("update ".DB_PREFIX."server set server_status='1' where server_gid='$v[mainten_gid]' ");
				}else{
					$db->query("update ".DB_PREFIX."server set server_status='1' where server_id='$v[mainten_sid]' ");
				}
			}
			//$db->update(array('server_status'=>'1'),DB_PREFIX."server",$v['mainten_sid']);
			
			$db->update(array('mainten_status'=>'0'),DB_PREFIX."mainten",$v['mainten_id']);
			
			fwrite($file,$end.' [game_'.$v['mainten_gid'].']=>started　, [server_'.$v['mainten_sid'].']=>started ');
		}
		
		//fwrite($file,'['.$v['rob_id'].']'. $v['domain'] ."　,　");
		//$rob_id = $v['rob_id'];
		//$order->interface_rob_reg($rob_id);
	}	
	fwrite($file,"\n");
}


///检查支付
/*
$start = strtotime("-800 seconds",time());
$end = strtotime("-680 seconds",time());

$where = " AND trans_instatus='0' AND trans_addtime<{$end} AND trans_addtime>{$start} ";
$sql = "SELECT * FROM ".DB_PREFIX."trans WHERE 1 $where ";
$rs = $db->getAll($sql);
if($rs)
{
	foreach($rs as $v)
	{
		$code = $v['trans_type'];
		include_once(WEB_ROOT."/pay/{$code}.php");
		$pay = new $code();
		$ret = $pay->query($v['trans_code']);
		if($ret['ret_code']=='1' && $ret['status']=='1'){
			
			order_paid($v['trans_code'], $code,'');
			
		}
		fwrite($file,date('Y-m-d H:i',time()).' [pay_'.$v['trans_code']."]=> {$ret[status]}　");
		fwrite($file,"\n");
	}
}
*/

fclose($file);
?>