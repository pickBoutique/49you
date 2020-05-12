<?php
define('PERMI_CODE','report_serveradvreg_hour');
define('CONN_SALVE',true);
include_once('init.inc.php');


//最大可显示的服务器数量
$server_num = 100;

if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$dateset="";
	$datehour="";
	$pfid="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='sd_platform' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pfid=$sub['value'];
			}
			elseif($sub['name']=='sd_sumtime' && trim($sub['value'])!=''){
				//if($sub['oper']=="<=") $sub['oper']="<";
				//$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$dateset=$sub['value'];
			}
			elseif($sub['name']=='sd_time' && trim($sub['value'])!=''){
				$datehour=" ".$sub['value'];
			}
			if($sub['name']=='sd_adv' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='advgroup_id' && trim($sub['value'])!=''){
				$where .= " and sd_adv in (".get_advidsbykey($sub['value']).")";
			}
		}
	}

	if($dateset == ''){
		$where .= "and sd_sumtime>=".(strtotime(date("Y-m-d H:00")));
	}else{
		if($datehour==""){
			$where .= "and sd_sumtime>=".$dateset." and sd_sumtime<".($dateset +3600*24);
		}else{
			$where .= "and sd_sumtime=".(strtotime(date("Y-m-d",$dateset).$datehour));
		}
	}
	
	$sersql="select CONCAT(sd_platform,'_',sd_sid) pf_sid,sd_gid,sd_sid
from ".DB_PREFIX."sumh_adv_server
where 1 {$where}
and sd_memreg > 0
GROUP BY pf_sid,sd_gid,sd_sid
order by pf_sid desc,sd_sid desc";
	//print_r($sersql);
	$tmpstr="";
	$trs = $db_admin_salve->getAll($sersql);
	
	format_fields_by_id($db_admin_salve,&$trs,"pf_sid",DB_PREFIX."v_games","pf_sid",array("server_name"=>"server_name","game_name"=>"game_name","pf_name"=>"pf_name"));	
	//format_fields_by_id($db_pd,&$trs,"sd_gid",DB_PREFIX."game","game_id",array("game_name"=>"game_name"));	
	
	$tfield = array(array());
	$tsum = array(array());
	//$avg = array();
	$tfield[0]["sd_sumtime"]="日 期";
	$tfield[0]["adv_name"]="广告位";
	$tfield[0]["sd_memreg"]="合 计";
	
	$tsum[0]["sd_sumtime"]="";
	$tsum[0]["adv_name"]="汇 总";
	$ids="";
	//print_r($trs);
	if($trs){
		foreach($trs as $k=>$v){
			$tfield[0]["s$k"]="$v[pf_name] $v[game_name]<br>$v[server_name]";
			$tmpstr.=",sum(case when CONCAT(sd_platform,'_',sd_sid) = '".$v["pf_sid"]."' then sd_memreg else 0 end) s{$k}";
			if($ids!="") $ids.=",";
			$ids.=$v["sd_sid"];
		}
	}
	if($ids==""){
		$record_count = 1;
		exit(get_list_json($rs, $record_count));
	}
	$where.=" and sd_sid in({$ids})";
	$sqlstr="select date_format(from_unixtime(sd_sumtime),'%Y-%m-%d %H') sd_sumtime
,sd_adv
,sum(sd_memreg) sd_memreg
".$tmpstr."
from ".DB_PREFIX."sumh_adv_server
where 1 {$where}
group by sd_sumtime,sd_adv
order by sd_sumtime desc";
//exit($sqlstr);
	$rs = $db_admin_salve->getAll($sqlstr,3600);
	
	if($rs){
		foreach($rs as $k=>$v){
			foreach(array_keys($rs[0]) as $ik){
				if(! in_array($ik,array("sd_sumtime","sd_adv"))){
					$tsum[0][$ik]+=$v[$ik];
				}
			}
		}
	}
	format_fields_by_id($db_admin_salve,&$rs,"sd_adv",DB_PREFIX."adv","adv_id",array("adv_name"=>"adv_name"));	

	$rsc=count($rs);
	//$rs[]=$avg;
	$j=0;
	for($i=0;$i<$rsc;$i+=17){
		if($rs) array_splice($rs,$i-$j,0,$tfield);
		$j++;
	}
	
	//$rs[]=$tsum;
	if($rs){
		array_splice($rs,1,0,$tsum);
	}
	$record_count = 1;
	exit(get_list_json($rs, $record_count));

}else{
	
	$page_nav = "统计分析 >> 服务器广告注册(时)";
	include_once("templates/report-serveradvreg-hour.html");
	
}
function get_advidsbykey($key){
	global $db_admin_salve;
	$sqlstr="select adv_id from ".DB_PREFIX."adv where adv_groupid='".$key."'";
	$trs=$db_admin_salve->getAll($sqlstr);
	if(!$trs){
		return "-1";
	}
	$ids = '';
	foreach($trs as $k=>$v){
		if($v["adv_id"]!=''){
			if(!empty($ids)) $ids.=",";
			$ids .= $v['adv_id'];
		}
	}
	return $ids;
}
?>
