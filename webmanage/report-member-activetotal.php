<?php
define('PERMI_CODE','report_member_acttotal_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$where= "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='regtime' && trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and sd_sumtime".$sub['oper']."'".$sub['value']."'";
				$setdate = "set";
			}
			if($sub['name']=='advtype_id' && trim($sub['value'])!=''){
				$where .= " and sd_advtype".$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_platform' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
		}
	}
	if($setdate == ''){
		$where .= " and sd_sumtime>=".strtotime(date("Y-m-d"));
	}
	
	$sqlstr="select *
from 
(select sd_advtype
,sum(sd_memonline) onlogin
,sum(sd_active5min) 5min
,sum(sd_active10min) 10min
,sum(sd_active15min) 15min
,sum(sd_active20min) 20min
,sum(sd_active25min) 25min
,sum(sd_active30min) 30min
,sum(sd_activeall) sd_activeall
from ".DB_PREFIX."sumh_adv_online
where 1 {$where}
group by sd_advtype) a";
	//exit($sqlstr);
	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);//$db_salve->getAll($sqlstr);
//	print_r($rs);
//	exit();
	format_namelist_by_id(&$rs,"sd_advtype","advtype_name","advtype","advtype_id","advtype_name");
	$totalrow=array("advtype_name"=> "合计","onlogin"=>0,"5min"=>0,"10min"=>0,"15min"=>0,"20min"=>0,"25min"=>0,"30min"=>0);
	if($rs){
		foreach($rs as $k=>$v){
			$totalrow["onlogin"]+=$v["onlogin"]; 
			$totalrow["5min"]+=$v["5min"]; $rs[$k]["5min"] .= parcen($v["5min"],$v["onlogin"]);
			$totalrow["10min"]+=$v["10min"]; $rs[$k]["10min"] .= parcen($v["10min"],$v["onlogin"]);
			$totalrow["15min"]+=$v["15min"]; $rs[$k]["15min"] .= parcen($v["15min"],$v["onlogin"]);
			$totalrow["20min"]+=$v["20min"]; $rs[$k]["20min"] .= parcen($v["20min"],$v["onlogin"]);
			$totalrow["25min"]+=$v["25min"]; $rs[$k]["25min"] .= parcen($v["25min"],$v["onlogin"]);
			$totalrow["30min"]+=$v["30min"]; $rs[$k]["30min"] .= parcen($v["30min"],$v["onlogin"]);
			$totalrow["sd_activeall"]+=$v["sd_activeall"]; $rs[$k]["sd_activeall"] .= parcen($v["sd_activeall"],$v["onlogin"]);
			
		}

		$totalrow["5min"] .= parcen($totalrow["5min"],$totalrow["onlogin"]);
		$totalrow["10min"] .= parcen($totalrow["10min"],$totalrow["onlogin"]);
		$totalrow["15min"] .= parcen($totalrow["15min"],$totalrow["onlogin"]);
		$totalrow["20min"] .= parcen($totalrow["20min"],$totalrow["onlogin"]);
		$totalrow["25min"] .= parcen($totalrow["25min"],$totalrow["onlogin"]);
		$totalrow["30min"] .= parcen($totalrow["30min"],$totalrow["onlogin"]);
		$totalrow["sd_activeall"] .= parcen($totalrow["sd_activeall"],$totalrow["onlogin"]);
	}

	$rs[] = $totalrow;
	$record_count = $pager['count'];
	exit(get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 渠道会员活跃统计";
	include_once("templates/report-member-activetotal.html");
}
function parcen($val,$total){
	return empty($total) ? "" : "[".number_format($val / $total * 100, 2, '.', ' ')."%]";
}
?>
