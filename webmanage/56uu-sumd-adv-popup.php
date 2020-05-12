<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_56UU',true);
define('CONN_DAYWAN',true);
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
//$sumact = 指定统计项目 空为全部 ，mem 会员表统计 、tra 充值统计
//$cln = 清空统计表 1 清空, 0不清空
function sumtotal($sumdatetime,$sumact='',$cln=1){
	global $db,$db_admin,$db_salve,$db_daywan,$config;
	$tdate=date("Ymd",$sumdatetime);
	
	//写到通用库
	$adwhere = " and sd_platform=".PLATFORM_ID;
	if(!empty($cln)){
		$sqlstr="delete from ".DB_PREFIX."sumd_advmet_popup where sd_sumtime = $sumdatetime".$adwhere;
		$db_admin->query($sqlstr);
	}
	//###################### 广告素材点击汇总表日(popup) ######################
	if(empty($sumact) || $sumact=="popup"){
		$sqlstr="select adv_id from ".DB_PREFIX."adv";
		$rsadv=$db_admin->getAll($sqlstr);
		//循环获取广告信息
		if($rsadv)
		foreach($rsadv as $k=>$v){

			
			$sqlstr="SELECT {$sumdatetime} sd_sumtime
,sd_advtype
,sd_advid
,sd_metrid
,sum(case when pcount=0 then 1 else pcount end) sd_puptotal
,count(sd_ip) sd_ip
,sum(case when p = 1 then 1 else 0 end) sd_status1
,sum(case when p = 2 then 1 else 0 end) sd_status2
,sum(case when p = 3 then 1 else 0 end) sd_status3
,sum(case when p = 4 then 1 else 0 end) sd_status4
from (
SELECT webchannel_id sd_advtype
,wid sd_advid
,material_id sd_metrid
,ip sd_ip
,sum(case when p = 1 then 1 else 0 end) pcount
,max(p) p
from ad_log_".$v['adv_id']."_".$tdate."
where platform_id = '".PLATFORM_ID."'
GROUP BY sd_advtype,sd_advid,sd_metrid,sd_ip) a
group by sd_advtype,sd_advid,sd_metrid";
		//exit($sqlstr);
			$trs = $db_daywan->getAll($sqlstr);
			if($trs){
				$tmptable=retsqltable($trs,&$strField);
				$sqlstr="update ".DB_PREFIX."sumd_advmet_popup a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid 
set a.sd_puptotal = b.sd_puptotal
,a.sd_ip = b.sd_ip
,a.sd_status1 = b.sd_status1
,a.sd_status2 = b.sd_status2
,a.sd_status3 = b.sd_status3
,a.sd_status4 = b.sd_status4";
				$db_admin->query($sqlstr);
		
				$sqlstr="insert into ".DB_PREFIX."sumd_advmet_popup({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumd_advmet_popup c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid)";
	
				$db_admin->query($sqlstr);
			}
		}
		
		//重算 sumd_adv_popup 表
		$sqlstr="delete from ".DB_PREFIX."sumd_adv_popup where sd_sumtime = $sumdatetime".$adwhere;
		$db_admin->query($sqlstr);
		$sqlstr="insert into ".DB_PREFIX."sumd_adv_popup(sd_sumtime
,sd_advtype
,sd_advid
,sd_puptotal
,sd_ip
,sd_status1
,sd_status2
,sd_status3
,sd_status4
)
select sd_sumtime
,sd_advtype
,sd_advid
,sum(sd_puptotal) sd_puptotal
,sum(sd_ip) sd_ip
,sum(sd_status1) sd_status1
,sum(sd_status2) sd_status2
,sum(sd_status3) sd_status3
,sum(sd_status4) sd_status4
from ".DB_PREFIX."sumd_advmet_popup
where sd_sumtime = $sumdatetime
group by sd_sumtime,sd_advtype,sd_advid";

		$db_admin->query($sqlstr);
	}
	//###################### 广告素材点击汇总表日结束 ######################

	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db_admin->clear_cache($cachefile);
	}
}



?>
