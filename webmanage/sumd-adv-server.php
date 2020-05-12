<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('49you-init.inc.php');
include_once('suminit.inc.php');

$sumact=$_REQUEST["sumact"];
$cln= empty($_REQUEST["cln"]) ? 0 : 1;

if(empty($act)){//上一天
	$sumhourn=strtotime(date("Y-m-d"));
	$sumhp=$sumhourn-3600*24;
	sumtotal($sumhp,$sumact,$cln);
}else if($act=="resetday"){//今天
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	sumtotal($sumhour,$sumact,$cln);
}else if($act=="getnow"){//重算当天
	$sumhour=strtotime(date("Y-m-d"));
	sumtotal($sumhour,$sumact,$cln);
}else if($act=="rebf"){//重算几天前的？
	$sumhour=intval($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",strtotime($sumhour." days")));
	echo "日期：".date("Y-m-d",$sumhour);
	sumtotal($sumhour,$sumact,$cln);
}else if($act=="reset_days"){//指定日期到指定日期
	$sumdate_star=strtotime($_REQUEST["redt_star"]);
	$sumdate_end=strtotime($_REQUEST["redt_end"]);
	
	$sumdate_star=strtotime(date("Y-m-d",$sumdate_star));
	$sumdate_end =strtotime(date("Y-m-d",$sumdate_end));
	if(!empty($sumdate_star)){
		if(empty($sumdate_end) | $sumdate_end<$sumdate_star) $sumdate_end = $sumdate_star;
		if(($sumdate_end-$sumdate_star)/(3600*24) > 31){
			echo "重算日期请不要超过一个月！";
		}else{
			for($i=$sumdate_star;$i<=$sumdate_end;$i+=3600*24){
				sumtotal($i,$sumact,$cln);
				echo "<br/>日期：".date("Y-m-d",$i)." 成功";
			}
		}
	}
}

fun_endrun();


//重算指定时间的统计汇总结果
//$sumdatetime=需要重算的时间戳
//$sumact = 指定统计项目 空为全部 ，mem 会员表统计 、tra 充值统计
//$cln = 清空统计表 1 清空, 0不清空
function sumtotal($sumdatetime,$sumact='',$cln=1){
	global $db,$db_admin,$db_salve,$config;
	$sumhn=$sumdatetime+3600*24;//下一天
	//写到通用库
	$adwhere = " and sd_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." sd_platform";

	$sqlstr="delete from ".DB_PREFIX."sumd_adv_server where sd_sumtime = $sumdatetime" .$adwhere;
	$db_admin->query($sqlstr);

	//###################### 会员表统计汇总(mem) ######################
	if(empty($sumact) || $sumact=="mem"){
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

			$sqlstr="update ".DB_PREFIX."sumd_adv_server a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_adv = b.sd_adv and a.sd_gid = b.sd_gid and a.sd_sid = b.sd_sid and a.sd_platform = b.sd_platform
set a.sd_memreg = b.sd_memreg";

			$sqlstr="insert into ".DB_PREFIX."sumd_adv_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_adv_server c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_adv = b.sd_adv
and c.sd_gid = b.sd_gid
and c.sd_sid = b.sd_sid
and c.sd_platform = b.sd_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总(tra) ######################
	if((empty($sumact) || $sumact=="tra") && false){
		$sqlstr="select {$sumdatetime} sd_sumtime,server_gid sd_gid
,server_id sd_sid
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
right join ".DB_PREFIX."server on server_id = trans_sid
group by sd_gid,sd_sid";
	//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
			$sqlstr="update ".DB_PREFIX."sumd_adv_server a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_gid = b.sd_gid and a.sd_sid = b.sd_sid and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
			$db_admin->query($sqlstr);
		
			$sqlstr="insert into ".DB_PREFIX."sumd_adv_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_adv_server c where c.sd_sumtime=b.sd_sumtime
and c.sd_gid = b.sd_gid
and c.sd_sid = b.sd_sid
and c.sd_platform = b.sd_platform)";

			$db_admin->query($sqlstr);
		}
	}
}



?>
