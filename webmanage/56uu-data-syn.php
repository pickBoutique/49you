<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_56UU',true);
define('CONN_GUANGGAO',true);
include_once('56uu-init.inc.php');
include_once('suminit.inc.php');
$sumact=$_REQUEST["sumact"];

if(empty($act)){
	game_syn($sumact);
	$date_end=strtotime(date("Y-m-d H:0:0"));
	$date_sta=$date_end-3600;
	trans_syn("",date("Y-m-d H:0:0",$date_sta),date("Y-m-d H:0:0",$date_end));
}else if($act=="reset_days"){//指定日期到指定日期
	$sumdate_star=strtotime($_REQUEST["redt_star"]);
	$sumdate_end=strtotime($_REQUEST["redt_end"]);

	if(!empty($sumdate_star)){
		$sumdate_star=strtotime(date("Y-m-d",$sumdate_star));
		$sumdate_end =strtotime(date("Y-m-d",$sumdate_end));
		if(empty($sumdate_end) || $sumdate_end<$sumdate_star) $sumdate_end = $sumdate_star;

		if(($sumdate_end-$sumdate_star)/(3600*24) > 31){
			echo "重算日期请不要超过一个月！";
		}else{
			for($i=$sumdate_star;$i<=$sumdate_end;$i+=3600*24){
				$date_sta=$i;
				$date_end=$i+3600*24;
				trans_syn($sumact,date("Y-m-d",$date_sta),date("Y-m-d",$date_end));
				echo "<br/>日期：".date("Y-m-d",$i)." 成功";
			}
		}
	}else{
		$date_end=strtotime(date("Y-m-d"));
		$date_sta=$date_end-3600*24;
		trans_syn($sumact,date("Y-m-d",$date_sta),date("Y-m-d",$date_end));
	}
}
fun_endrun();


function game_syn($sumact=''){
	global $db,$db_salve,$db_guanggao,$config;
	//###################### 游戏表(游戏基本资料同步) ######################
	if(empty($sumact) || $sumact=="game"){
		//$sqlstr="truncate table ".DB_PREFIX."game";
		//$db->query($sqlstr);
		$sqlstr="SELECT game_id,title game_name,factory game_code,createtime game_time,description game_desc,url game_web,bbsurl game_bbs,orders game_recom,rate game_rate
,status game_status
from game";
	
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
			$sqlstr="update ".DB_PREFIX."game a 
right join ".$tmptable."
on a.game_id=b.game_id
set a.game_name = b.game_name
,a.game_code = b.game_code
,a.game_time = b.game_time
,a.game_desc = b.game_desc
,a.game_web = b.game_web
,a.game_status = b.game_status
,a.game_rate = b.game_rate";
			$db->query($sqlstr);
	
			$sqlstr="insert into ".DB_PREFIX."game({$strField})
select {$strField} from ".$tmptable."
where not exists (select * from ".DB_PREFIX."game c where c.game_id=b.game_id)";

			$db->query($sqlstr);
		}
	//###################### 游戏表结束 ######################
	
	//###################### 游戏服务器资料表 ######################
		//$sqlstr="truncate table ".DB_PREFIX."server";
		//$db->query($sqlstr);
		$sqlstr="SELECT district_id server_id,title server_name,game_id server_gid,servernum server_num,status server_status
,fromdate server_start
from district";
	
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
			$sqlstr="update ".DB_PREFIX."server a 
right join ".$tmptable."
on a.server_id=b.server_id
set a.server_name = b.server_name
,a.server_gid = b.server_gid
,a.server_num = b.server_num
,a.server_status = b.server_status
,a.server_start = b.server_start";
			$db->query($sqlstr);
	
			$sqlstr="insert into ".DB_PREFIX."server({$strField})
select {$strField} from ".$tmptable."
where not exists (select * from ".DB_PREFIX."server c where c.server_id=b.server_id)";

			$db->query($sqlstr);
		}
	}
	//###################### 游戏服务器资料表结束 ######################
	
}
function trans_syn($sumact,$date_sta,$date_end){
	global $db,$db_salve;
	//###################### 充值流水账(充值流水账同步) ######################
	if(empty($sumact) || $sumact=="tra"){
		//$sqlstr="truncate table ".DB_PREFIX."advtype";
		//$db->query($sqlstr);

		$sqlstr="SELECT paycord_id trans_id
,order_id trans_code
,member_id trans_mid
,username trans_mname
,price trans_money
,gold trans_currency
,game_id trans_gid
,district_id trans_sid
,unix_timestamp(Inserttime) trans_addtime
,payment_id trans_type
,ip trans_ip
,status trans_instatus
,unix_timestamp(Inserttime) trans_intime
,createtime trans_outtime
,gamestatus trans_outstatus
,webchannel_id trans_advtype
,webads_id trans_advid
,material_id trans_metrid
,ResoureOne trans_subtype
,regtime trans_register
FROM paycord
where status = 1
and Inserttime >='{$date_sta}' and Inserttime <'{$date_end}'";
	
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
		$sqlstr="insert into ".DB_PREFIX."trans({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."trans c where c.trans_id=b.trans_id)";
	
			$db->query($sqlstr);
		}

	//###################### 充值流水账结束 ######################
	}
}
?>
