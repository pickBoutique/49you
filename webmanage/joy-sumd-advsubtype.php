<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_JOY',true);
include_once('joy-init.inc.php');
include_once('suminit.inc.php');

$sumact=$_REQUEST["sumact"];
$cln= empty($_REQUEST["cln"]) ? 0 : 1;

if(empty($act)){//上一天
	$sumhourn=strtotime(date("Y-m-d"));
	$sumhp=$sumhourn-3600*24;
	$cln=1;
	sumtotal($sumhp,$sumact,$cln);
}else if($act=="getnow"){//今天
	$sumhour=strtotime(date("Y-m-d"));
	sumtotal($sumhour,$sumact,$cln);
}else if($act=="resetday"){//重算当天
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
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

	if(!empty($cln)){
		$sqlstr="delete from ".DB_PREFIX."sumd_advsubtype where sd_sumtime = $sumdatetime".$adwhere;
		$db_admin->query($sqlstr);
	}
	
	//###################### 会员表统计汇总(mem) ######################
	if(empty($sumact) || $sumact=="mem"){
		$sqlstr="select {$sumdatetime} sd_sumtime
,webchannel_id sd_advtype
,webads_id sd_advid
,material_id sd_metrid
,siteman sd_subtype
".$platform."
,count(*) sd_memreg
,sum(case when login_day >=2 then 1 else 0 end) sd_actve2day
,sum(case when login_day >=3 then 1 else 0 end) sd_actve3day
,sum(case when login_day >=5 then 1 else 0 end) sd_actve5day
,sum(case when login_day >=7 then 1 else 0 end) sd_actve7day
,0 sd_memrecom
,0 sd_noreg
,0 sd_noregactive
from membersub
where createtime >= {$sumdatetime} and createtime< {$sumhn}
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
		$sqlstr="update ".DB_PREFIX."sumd_advsubtype a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_platform = b.sd_platform
 and a.sd_subtype = b.sd_subtype
set a.sd_memreg = b.sd_memreg
,a.sd_memrecom = b.sd_memrecom
,a.sd_noreg = b.sd_noreg
,a.sd_noregactive = b.sd_noregactive
,a.sd_actve2day = b.sd_actve2day
,a.sd_actve3day = b.sd_actve3day
,a.sd_actve5day = b.sd_actve5day
,a.sd_actve7day = b.sd_actve7day";
			$db_admin->query($sqlstr);
		
		$sqlstr="insert into ".DB_PREFIX."sumd_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 会员表统计汇总结束 ######################
	
	
	//###################### 充值统计汇总(tra) ######################
	if(empty($sumact) || $sumact=="tra"){
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
,siteman sd_subtype
,member_id trans_mname
,count(*) sd_trancount
,sum(price) sd_trantotal
from paycord
where status = 1 and intime>= {$sumdatetime} and intime<{$sumhn}
group by sd_advtype,sd_advid,sd_metrid,sd_subtype,member_id) A
group by sd_advtype,sd_advid,sd_metrid,sd_subtype";
		//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);
	
			$sqlstr="update ".DB_PREFIX."sumd_advsubtype a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid and a.sd_subtype = b.sd_subtype and a.sd_platform = b.sd_platform
set a.sd_trancount = b.sd_trancount
,a.sd_trangromem = b.sd_trangromem
,a.sd_trantotal = b.sd_trantotal";
			$db_admin->query($sqlstr);
			
			$sqlstr="insert into ".DB_PREFIX."sumd_advsubtype({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_advsubtype c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid
and c.sd_subtype = b.sd_subtype
and c.sd_platform = b.sd_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 充值统计汇总结束 ######################

	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}


?>
