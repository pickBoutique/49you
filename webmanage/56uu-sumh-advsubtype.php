<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_56UU',true);
include_once('56uu-init.inc.php');
include_once('suminit.inc.php');
//$sumhp=strtotime(date("Y-m-d H:0:0",strtotime("-1 hour")));

if(empty($act)){
	$sumhourn=strtotime(date("Y-m-d H:0:0"));
	$sumhp=$sumhourn-3600;//上一个钟
	sumtotal($sumhp);
}else if($act=="resetday"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	for($i=0;$i<24;$i++){
		if($i>0)$sumhour+=3600;
		sumtotal($sumhour);
	}
}else if($act=="resethour"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d H:0:0",$sumhour));
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

	$sqlstr="delete from ".DB_PREFIX."sumh_advsubtype where sd_sumtime = $sumdatetime".$adwhere;
	$db_admin->query($sqlstr);
	
	//###################### 会员表统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime
,webchannel_id sd_advtype
,webads_id sd_advid
,material_id sd_metrid
,ResoureOne sd_subtype
".$platform."
,count(*) sd_memreg
,0 sd_memrecom
,0 sd_noreg
,0 sd_noregactive
from member22
where createtime >= {$sumdatetime} and createtime< {$sumhn}
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="insert into ".DB_PREFIX."sumh_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总 ######################
	$sqlstr="select {$sumdatetime} sd_sumtime
,sd_advtype
,sd_advid
,sd_metrid
,sd_subtype
".$platform."
,sum(sd_trancount) sd_trancount
,count(trans_mname) sd_trangromem
,sum(sd_trantotal) sd_trantotal
from (select webchannel_id sd_advtype
,webads_id sd_advid
,material_id sd_metrid
,ResoureOne sd_subtype
,member_id trans_mname
,count(*) sd_trancount
,sum(price) sd_trantotal
from paycord
where status = 1 and Inserttime >= '".date("Y-m-d H:0:0",$sumdatetime)."' and Inserttime <'".date("Y-m-d H:0:0",$sumhn)."'
group by sd_advtype,sd_advid,sd_metrid,sd_subtype,member_id) A
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
	//exit($sqlstr);
	$trs = $db_salve->getAll($sqlstr);
	if($trs){
		$tmptable=retsqltable($trs,&$strField);

	$sqlstr="update ".DB_PREFIX."sumh_advsubtype a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_subtype = b.sd_subtype and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
		$db_admin->query($sqlstr);
		
	$sqlstr="insert into ".DB_PREFIX."sumh_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

		$db_admin->query($sqlstr);
	}
	//###################### 充值统计汇总结束 ######################

	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}


?>
