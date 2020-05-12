<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('49you-init.inc.php');
include_once('suminit.inc.php');
function get_ids($trs,$id=""){
	if(empty($trs) || empty($id)) return;
	$reids="";
	foreach($trs as $k){
		if(!empty($reids))$reids.=",";
		$reids.=$k["$id"];
	}
	return $reids;
}

/*###################### 会员表清理 ######################
1.会员未激活
2.没有充过值
3.二个月以上没有登陆
先添加到member_del表，然后删除
*/
$strField="member_id,member_name,email,email_isvalid,member_pwd,member_level,member_nickname,member_idvalid,member_truename,member_idcard,telephone,sex,birth,education,fax,mobile,mobile_isvalid,address_cn,job,postcode,money,points,add_time,is_setup,question1,answer1,question2,answer2,question3,answer3,member_status,account_type,account,last_time,last_ip,login_day,online_time,login_count,member_recom,member_reomname,member_advtype,member_advid,member_metrid,mac,member_recomgame,member_gamename,member_active";

$sqlstr="select member_id
from ".DB_PREFIX."member
where last_time <= unix_timestamp(DATE_ADD(now(),INTERVAL -2 MONTH)) 
and not exists (select 1 from online_trans where (trans_instatus=1 or trans_outstatus=1) and member_name=trans_mname)
limit 300";

$trs = $db_salve->getAll($sqlstr);
if($trs){
	//$tmptable=retsqltable($trs,&$strField);
	$ids=get_ids($trs,"member_id");
	$sqlstr="insert into ".DB_PREFIX."member_del({$strField})
select {$strField} from ".DB_PREFIX."member
where member_id in (".$ids.")";

	$db->query($sqlstr);
	
	$sqlstr="delete from ".DB_PREFIX."member 
where member_id in (".$ids.")";

	$db->query($sqlstr);
}

/*###################### 会员游戏表清理 ######################
1.三个月以上没有登陆过

$sqlstr="delete
from ".DB_PREFIX."mygame
where mg_last_time <= unix_timestamp(DATE_ADD(now(),INTERVAL -3 MONTH))
limit 300";

$db->query($sqlstr);
*/


/*###################### 充值记录表 ######################
1.平台未到账,游戏未到账
2.7天以上
先添加到trans_del表，然后删除
*/
$strField="trans_id,trans_code,trans_mid,trans_mname,trans_money,trans_monint,trans_currency,trans_gid,trans_gname,trans_sid,trans_sname,trans_addtime,trans_type,trans_ip,trans_area,trans_line,trans_instatus,trans_intime,trans_outstatus,trans_outtime,trans_advtype,trans_advid,trans_metrid,trans_phone,trans_subtype,trans_register";

$sqlstr="select trans_id
from ".DB_PREFIX."trans
where trans_instatus != 1 
and trans_outstatus !=1 
and trans_addtime <= unix_timestamp(DATE_ADD(now(),INTERVAL -7 DAY)) 
limit 300";

$trs = $db_salve->getAll($sqlstr);
if($trs){
	$ids=get_ids($trs,"trans_id");
	$sqlstr="insert into ".DB_PREFIX."trans_del({$strField})
select {$strField} from ".DB_PREFIX."trans
where trans_id in (".$ids.")";

	$db->query($sqlstr);
	
	$sqlstr="delete from ".DB_PREFIX."trans 
where trans_id in (".$ids.")";

	$db->query($sqlstr);
}


fun_endrun();
?>
