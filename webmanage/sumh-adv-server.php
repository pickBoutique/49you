<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('49you-init.inc.php');
include_once('suminit.inc.php');
//$sumhp=strtotime(date("Y-m-d H:0:0",strtotime("-1 hour")));
if(empty($act)){
	$sumhourn=strtotime(date("Y-m-d H:0:0"));
	$sumhp=$sumhourn-3600;//上一个钟

	//统计当前钟数的记录
	sumtotal($sumhp);

}else if($act=="resetday"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	for($i=0;$i<24;$i++){
		if($i>0)$sumhour+=3600;
		//echo($sumhour.":".date("Y-m-d H:0:0",$sumhour)."<br>");
		//exit();
		sumtotal($sumhour);
	}
}else if($act=="resethour"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d H:0:0",$sumhour));
	//echo($sumhour.":".date("Y-m-d H:0:0",$sumhour)."<br>");
	//exit();
	sumtotal($sumhour);
}else if($act=="getnow"){
	$sumhour=strtotime(date("Y-m-d H:0:0"));
	
	sumtotal($sumhour);
}
fun_endrun();


//重算指定时间的小时统计汇总结果
//$sumdatetime=需要重算的时间戳
function sumtotal($sumdatetime){
	global $db,$db_admin,$db_salve,$config;
	$sumhn=$sumdatetime+3600;//下一个小时
	//写到通用库
	$adwhere = " and sd_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." sd_platform";
	
	$sqlstr="delete from ".DB_PREFIX."sumh_adv_server where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	//###################### 会员表统计汇总 ######################
		$sqlstr="select {$sumdatetime} sd_sumtime
,mg_advtype sd_advtype
,mg_adv sd_adv
,mg_game_id sd_gid
,mg_server_id sd_sid
".$platform."
,count(*) sd_memreg
from ".DB_PREFIX."mygame
where mg_regtime >= {$sumdatetime} and mg_regtime< {$sumhn}
and mg_first = 1 and (mg_advtype>0 or mg_adv>0)
group by sd_advtype,sd_adv,sd_gid,sd_sid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="insert into ".DB_PREFIX."sumh_adv_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_adv_server c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_adv = b.sd_adv
and c.sd_gid = b.sd_gid
and c.sd_sid = b.sd_sid
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 会员表统计汇总结束 ######################

	
	//###################### 充值统计汇总 ######################
	if(false){
		$sqlstr="select {$sumdatetime} sd_sumtime,trans_gid sd_gid
,trans_sid sd_sid
".$platform."
,sum(sd_trancount) sd_trancount
,count(trans_mname) sd_trangromem
,sum(sd_trantotal) sd_trantotal
from (select trans_gid
,trans_sid
,trans_mname
,count(*) sd_trancount
,sum(trans_money) sd_trantotal
from ".DB_PREFIX."trans
where trans_instatus = 1 and  trans_intime >= {$sumdatetime} and trans_intime< {$sumhn}
group by trans_gid,trans_sid,trans_mname) A
group by sd_gid,sd_sid";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="update ".DB_PREFIX."sumh_adv_server a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_gid = b.sd_gid and a.sd_sid = b.sd_sid and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
		$db_admin->query($sqlstr);
		
	$sqlstr="insert into ".DB_PREFIX."sumh_adv_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_adv_server c where c.sd_sumtime=b.sd_sumtime
and c.sd_gid = b.sd_gid
and c.sd_sid = b.sd_sid
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	}
	//###################### 充值统计汇总结束 ######################
}


?>
