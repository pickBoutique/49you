<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_56UU',true);
include_once('56uu-init.inc.php');
include_once('suminit.inc.php');

$sumact=$_REQUEST["sumact"];
$cln= empty($_REQUEST["cln"]) ? 0 : 1;

if(empty($act)){//上一天
	$sumhourn=strtotime(date("Y-m-d"));
	$sumhp=$sumhourn-3600*24;
	$cln=1;
	sumtotal($sumhp,$sumact,$cln);

}else if($act=="resetday"){//指定日期
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	sumtotal($sumhour,$sumact,$cln);
	
}else if($act=="getnow"){//当天
	$sumhour=strtotime(date("Y-m-d"));
	sumtotal($sumhour,$sumact,$cln);
	
}else if($act=="rebf"){//多少天前
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
function sumtotal($sumdatetime,$sumact='',$cln=0){
	global $db,$db_admin,$db_salve,$config;
	$sumhn=$sumdatetime+3600*24;//下一天
	//写到通用库
	$adwhere = " and sd_platform=".PLATFORM_ID;
	$platform = ",".PLATFORM_ID." sd_platform";
/*
	if(!empty($cln)){
		$sqlstr="delete from ".DB_PREFIX."sumd_member_ref where sd_sumtime = $sumdatetime".$adwhere;
		$db_admin->query($sqlstr);
	}
*/
	//###################### 来源汇总(ref) ######################
	if(empty($sumact) || $sumact=="ref"){
		$sqlstr="select $sumdatetime sd_sumtime
,ref_domainbs sd_domainbs
".$platform."
,a.ref_refmd5 sd_refmd5
,max(referrer) sd_referrer
,max(keyword) sd_keyword 
,sum(guests) sd_guest 
,sum(regs) sd_register 
,sum(trans_rs) sd_paymember 
,sum(trans_money) sd_paytotal 
from (select ref_domainbs ,ref_refmd5 
,max(ref_referrer) referrer 
,max(ref_keyword) keyword 
,sum(case when ret_type=0 then 1 else 0 end) guests 
,sum(case when ret_type=1 then 1 else 0 end) regs 
from ".DB_PREFIX."member_ref 
where ref_addtime >= {$sumdatetime} and ref_addtime< {$sumhn}
and ref_domainbs !='' 
group by ref_domainbs,ref_refmd5) a 
left join (select ref_refmd5 
,count(username) trans_rs 
,sum(trans_money) trans_money 
from (select b.ref_refmd5 
,a.username ,sum(a.price) trans_money 
from paycord a inner join online_member_ref b on a.username = b.ref_membername and b.ref_addtime >={$sumdatetime} and b.ref_addtime< {$sumhn}
where 1 and b.ref_membername != '' and a.status = 1 group by ref_refmd5,username) t 
group by ref_refmd5) b on a.ref_refmd5 = b.ref_refmd5 

group by ref_domainbs,a.ref_refmd5";
	//exit($sqlstr);
		$trs = $db_salve->getAll($sqlstr);
		if($trs){
			$tmptable=retsqltable($trs,&$strField);


			$sqlstr="update ".DB_PREFIX."sumd_member_ref a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_refmd5 = b.sd_refmd5 and a.sd_platform = b.sd_platform
set a.sd_guest = b.sd_guest
,a.sd_register = b.sd_register
,a.sd_paymember = b.sd_paymember
,a.sd_paytotal = b.sd_paytotal";
			$db_admin->query($sqlstr);
	
			$sqlstr="insert into ".DB_PREFIX."sumd_member_ref({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_member_ref c where c.sd_sumtime=b.sd_sumtime
and c.sd_refmd5 = b.sd_refmd5
and c.sd_platform = b.sd_platform)";

			$db_admin->query($sqlstr);
		}
	}
	//###################### 来源汇总 ######################
	
	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}




?>
