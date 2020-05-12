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
	$adwhere = " and ss_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." ss_platform";

	$sqlstr="delete from ".DB_PREFIX."sumd_server where ss_sumtime = $sumdatetime" .$adwhere;
	$db_admin->query($sqlstr);

	//###################### 会员表统计汇总(mem) ######################
	if(empty($sumact) || $sumact=="mem"){
		$sqlstr="select a.*
from (select {$sumdatetime} ss_sumtime,mg_game_id ss_gid
,mg_server_id ss_sid
".$platform."
,count(*) ss_memreg
,sum(case when mg_adv>0 then 1 else 0 end) ss_advreg
,sum(case when mg_adv=0 then 1 else 0 end) ss_natreg
from ".DB_PREFIX."mygame
where mg_regtime >= {$sumdatetime} and mg_regtime< {$sumhn}
and mg_first = 1
group by ss_gid,ss_sid) a
inner join ".DB_PREFIX."server b on server_gid=ss_gid and server_id = ss_sid";
	//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);

			$sqlstr="update ".DB_PREFIX."sumd_server a 
right join ".$tmptable."
on a.ss_sumtime=b.ss_sumtime and a.ss_gid = b.ss_gid and a.ss_sid = b.ss_sid and a.ss_platform = b.ss_platform
set a.ss_memreg = b.ss_memreg
,a.ss_advreg = b.ss_advreg
,a.ss_natreg = b.ss_natreg";

			$sqlstr="insert into ".DB_PREFIX."sumd_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_server c where c.ss_sumtime=b.ss_sumtime
and c.ss_gid = b.ss_gid
and c.ss_sid = b.ss_sid
and c.ss_platform = b.ss_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总(tra) ######################
	//usertype ： 0 老用户，1 新增自然用户，2 新增广告用户
	if(empty($sumact) || $sumact=="tra"){
		$sqlstr="select {$sumdatetime} ss_sumtime,server_gid ss_gid
,server_id ss_sid
".$platform."
,sum(ss_trancount) ss_trancount
,count(trans_mname) ss_trangromem
,sum(ss_trantotal) ss_trantotal
,sum(case when usertype=0 then 1 else 0 end) ss_active5min
,sum(case when usertype=0 then ss_trantotal else 0 end) ss_active10min
,sum(case when usertype=1 then 1 else 0 end) ss_active15min
,sum(case when usertype=1 then ss_trantotal else 0 end) ss_active20min
,sum(case when usertype=2 then 1 else 0 end) ss_active25min
,sum(case when usertype=2 then ss_trantotal else 0 end) ss_active30min
from (select trans_gid
,trans_sid
,trans_mname
,max(case when server_start>trans_register then 0 else (case when trans_advid=0 then 1 else 2 end) end) usertype
,count(*) ss_trancount
,sum(trans_money) ss_trantotal
from ".DB_PREFIX."trans
left join ".DB_PREFIX."server on trans_sid = server_id
where trans_intime >= {$sumdatetime} and trans_intime< {$sumhn}
and trans_instatus = 1 
group by trans_gid,trans_sid,trans_mname) A
right join ".DB_PREFIX."server on server_id = trans_sid
group by ss_gid,ss_sid";
	//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
			$sqlstr="update ".DB_PREFIX."sumd_server a 
right join ".$tmptable."
on a.ss_sumtime=b.ss_sumtime and a.ss_gid = b.ss_gid and a.ss_sid = b.ss_sid and a.ss_platform = b.ss_platform
set a.ss_trancount = b.ss_trancount
,a.ss_trangromem = b.ss_trangromem
,a.ss_trantotal = b.ss_trantotal
,a.ss_active5min = b.ss_active5min
,a.ss_active10min = b.ss_active10min
,a.ss_active15min = b.ss_active15min
,a.ss_active20min = b.ss_active20min
,a.ss_active25min = b.ss_active25min
,a.ss_active30min = b.ss_active30min

";
			$db_admin->query($sqlstr);
		
			$sqlstr="insert into ".DB_PREFIX."sumd_server({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_server c where c.ss_sumtime=b.ss_sumtime
and c.ss_gid = b.ss_gid
and c.ss_sid = b.ss_sid
and c.ss_platform = b.ss_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 充值统计汇总结束(tra) ######################
	

}



?>
