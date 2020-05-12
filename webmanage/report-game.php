<?php
define('PERMI_CODE','report_game_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where="";
	$dateset="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='gid' & trim($sub['value'])!=''){
				$where .= " and trans_gid".$sub['oper']."'".$sub['value']."'";
			}
			elseif($sub['name']=='sid' & trim($sub['value'])!=''){
				$where .= " and trans_sid".$sub['oper']."'".$sub['value']."'";
			}
			elseif($sub['name']=='startime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				
				$where .= " and trans_intime".$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
		}
	}
	if($dateset == ''){
		$where .= "and trans_intime>=".(strtotime(date("Y-m-d")));
	}
	
	$sqlstr="select trans_gid,trans_sid
,count(trans_mname) trans_rs
,sum(trans_money) trans_money
from (select trans_gid,trans_sid,trans_mname,sum(trans_money) trans_money 
from ".DB_PREFIX."trans 
where trans_instatus = 1 and not (trans_type='console' and trans_gid=0) {$where} 
group by trans_gid,trans_sid,trans_mname) a
group by trans_gid,trans_sid";


	$rs = $db_salve->getAll($sqlstr);

	format_namelist_by_id(&$rs,"trans_gid","game_name","game","game_id","game_name");
	format_namelist_by_id(&$rs,"trans_sid","server_name","server","server_id","server_name");

	$totalrow=array("server_name"=> "合计","members"=>0,"onlogin"=>0,"trans_rs"=>0,"trans_money"=>0);
	if($rs){
		foreach($rs as $k=>$v){
			//$totalrow["members"]+=$v["members"]; 
			//$totalrow["onlogin"]+=$v["onlogin"]; 
			$totalrow["trans_rs"]+=$v["trans_rs"]; 
			$totalrow["trans_money"]+=$v["trans_money"]; 
		}
	}
	$totalrow["trans_money"].="";
	$rs[] = $totalrow;
	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 游戏统计";
	include_once("templates/report-game.html");
	
}

?>
