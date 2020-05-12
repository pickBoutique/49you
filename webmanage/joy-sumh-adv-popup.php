<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_JOY',true);
define('CONN_DAYWAN',true);
include_once('joy-init.inc.php');
include_once('suminit.inc.php');

$sumact=$_REQUEST["sumact"];
$cln= empty($_REQUEST["cln"]) ? 0 : 1;

if(empty($act)){
	$sumhourn=strtotime(date("Y-m-d H:0:0"));
	$sumhp=$sumhourn-3600;//上一个钟

	sumtotal($sumhp,$sumact,$cln);
}else if($act=="resetday"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d",$sumhour));
	sumtotal($sumhour,$sumact,$cln,1);

}else if($act=="resethour"){
	$sumhour=strtotime($_REQUEST["redt"]);
	$sumhour=strtotime(date("Y-m-d H:0:0",$sumhour));
	sumtotal($sumhour,$sumact,$cln);
}else if($act=="getnow"){
	$sumhour=strtotime(date("Y-m-d H:0:0"));
	sumtotal($sumhour,$sumact,$cln);
}
fun_endrun();


//重算指定时间的统计汇总结果
//$sumdatetime=需要重算的时间戳
//$cln = 清空统计表 1 清空, 0不清空
//$allday = 整天 ？
function sumtotal($sumdatetime,$sumact='',$cln=1,$allday=0){
	global $db,$db_salve,$db_daywan,$config;
	$tdate=date("Ymd",$sumdatetime);
	$sumhn=$sumdatetime+3600;//下一个小时
	if(!empty($cln)){
		$sqlstr="delete from ".DB_PREFIX."sumh_advmet_popup where";
		if(empty($allday)){
			$sqlstr.="sd_sumtime = $sumdatetime";
		}else{
			$sqlstr.="sd_sumtime >= $sumdatetime and sd_sumtime<".($sumdatetime+3600*24);
		}
		$db->query($sqlstr);
	}
	//###################### 广告素材点击汇总表日(popup) ######################
	if(empty($sumact) || $sumact=="popup"){
		$sqlstr="select adv_id from ".DB_PREFIX."adv";
		$rsadv=$db->getAll($sqlstr);
		//循环获取广告信息
		if($rsadv)
		foreach($rsadv as $k=>$v){
			$sqlstr="select sd_sumtime
,sd_advtype
,sd_advid
,sd_metrid
,sum(case when pcount=0 then 1 else pcount end) sd_puptotal
,count(sd_ip) sd_ip
,sum(case when p = 1 then 1 else 0 end) sd_status1
,sum(case when p = 2 then 1 else 0 end) sd_status2
,sum(case when p = 3 then 1 else 0 end) sd_status3
,sum(case when p = 4 then 1 else 0 end) sd_status4
from (select ";

			if(empty($allday)){
				$sqlstr.="{$sumdatetime} sd_sumtime";
			}else{
				$sumhour=$sumdatetime;
				$sqlstr.="(case";
				for($i=0;$i<24;$i++){
					$sqlstr.=" when createtime >= ".($sumhour)." and createtime<".($sumhour+3600)." then ".($sumhour);
					$sumhour+=3600;
				}
				$sqlstr.=" end) sd_sumtime";
			}
$sqlstr.=",webchannel_id sd_advtype
,wid sd_advid
,material_id sd_metrid
,ip sd_ip
,sum(case when p = 1 then 1 else 0 end) pcount
,max(p) p
from ad_log_".$v['adv_id']."_".$tdate."
where platform_id = '".PLATFORM_ID."'
and createtime >= $sumdatetime and createtime < ". (empty($allday) ? $sumhn : $sumhour) ."
group by sd_sumtime,sd_advtype,sd_advid,sd_metrid,sd_ip) a
group by sd_sumtime,sd_advtype,sd_advid,sd_metrid";
		//exit($sqlstr);
			$trs = $db_daywan->getAll($sqlstr);
			if($trs){
				$tmptable=retsqltable($trs,&$strField);
				$sqlstr="update ".DB_PREFIX."sumh_advmet_popup a 
right join ".$tmptable."
on a.sd_sumtime=b.sd_sumtime and a.sd_advtype = b.sd_advtype and a.sd_advid = b.sd_advid and a.sd_metrid = b.sd_metrid 
set a.sd_puptotal = b.sd_puptotal
,a.sd_ip = b.sd_ip
,a.sd_status1 = b.sd_status1
,a.sd_status2 = b.sd_status2
,a.sd_status3 = b.sd_status3
,a.sd_status4 = b.sd_status4";
				$db->query($sqlstr);
		
				$sqlstr="insert into ".DB_PREFIX."sumh_advmet_popup({$strField})
select * from ".$tmptable."
where not exists (select * from ".DB_PREFIX."sumh_advmet_popup c where c.sd_sumtime=b.sd_sumtime
and c.sd_advtype = b.sd_advtype
and c.sd_advid = b.sd_advid
and c.sd_metrid = b.sd_metrid)";
	
				$db->query($sqlstr);
			}
		}
		
		//重算 sumh_adv_popup 表
		$sqlstr="delete from ".DB_PREFIX."sumh_adv_popup where ";
		if(empty($allday)){
			$sqlstr.="sd_sumtime = $sumdatetime";
		}else{
			$sqlstr.="sd_sumtime >= $sumdatetime and sd_sumtime<".($sumdatetime+3600*23);
		}
		
		$db->query($sqlstr);
		$sqlstr="insert into ".DB_PREFIX."sumh_adv_popup(sd_sumtime
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
from ".DB_PREFIX."sumh_advmet_popup
where ";

		if(empty($allday)){
			$sqlstr.="sd_sumtime = $sumdatetime";
		}else{
			$sqlstr.="sd_sumtime >= $sumdatetime and sd_sumtime<".($sumdatetime+3600*24);
		}
$sqlstr.=" group by sd_sumtime,sd_advtype,sd_advid";

		$db->query($sqlstr);
	}
	//###################### 广告素材点击汇总表日结束 ######################

	//清除缓存
	$cachefile=$_REQUEST["cachefile"];
	if(!empty($cachefile)){
		$db->clear_cache($cachefile);
	}
}



?>
