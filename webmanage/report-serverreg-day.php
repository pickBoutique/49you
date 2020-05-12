<?php
define('PERMI_CODE','report_serverreg_day');
define('CONN_SALVE',true);
include_once('init.inc.php');

$week = array(
'Monday' => '星期一',
'Tuesday' => '星期二',
'Wednesday' => '星期三',
'Thursday' => '星期四',
'Friday' => '星期五',
'Saturday' => '星期六',
'Sunday' => '星期日'
);

//最大可显示的服务器数量
$server_num = 100;

if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$dateset="";
	$pfid="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='ss_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pfid=$sub['value'];
			}
			elseif($sub['name']=='ss_sumtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
		}
	}
	//$db_pd=create_system_db($pfid,true);
	//print_r($db_pd);
	//exit();
	if($dateset == ''){$where .= "and ss_sumtime>=".(strtotime(date("Y-m-d"))-3600*24*7);}
	
	$sersql="select CONCAT(ss_platform,'_',ss_sid) pf_sid,ss_gid,ss_sid
from ".DB_PREFIX."sumd_server 
where 1 {$where}
and ss_advreg > 0
GROUP BY pf_sid,ss_gid,ss_sid
order by pf_sid desc,ss_sid desc";
	//print_r($sersql);
	$tmpstr="";
	$trs = $db_admin_salve->getAll($sersql);
	
	format_fields_by_id($db_admin_salve,&$trs,"pf_sid",DB_PREFIX."v_games","pf_sid",array("server_name"=>"server_name","game_name"=>"game_name","pf_name"=>"pf_name"));	
	//format_fields_by_id($db_pd,&$trs,"ss_gid",DB_PREFIX."game","game_id",array("game_name"=>"game_name"));	
	
	$tfield = array(array());
	$tsum = array(array());
	//$avg = array();
	$tfield[0]["ss_sumtime"]="日 期";
	$tfield[0]["ss_advreg"]="合 计(自然注册)";
	
	$tsum[0]["ss_sumtime"]="汇 总";
	$ids="";
	//print_r($trs);
	if($trs){
		foreach($trs as $k=>$v){
			$tfield[0]["s$k"]="$v[pf_name] $v[game_name]<br>$v[server_name]";
			$tmpstr.=",sum(case when CONCAT(ss_platform,'_',ss_sid) = '".$v["pf_sid"]."' then ss_advreg else 0 end) s{$k}";
			$tmpstr.=",sum(case when CONCAT(ss_platform,'_',ss_sid)= '".$v["pf_sid"]."' then ss_natreg else 0 end) n{$k}";
			if($ids!="") $ids.=",";
			$ids.=$v["ss_sid"];
		}
	}
	$where.=" and ss_sid in({$ids})";
	$sqlstr="select date_format(from_unixtime(ss_sumtime),'%Y-%m-%d') ss_sumtime
,sum(ss_advreg) ss_advreg
,sum(ss_natreg) ss_natreg
".$tmpstr."
from ".DB_PREFIX."sumd_server
where 1 {$where}
group by ss_sumtime
order by ss_sumtime desc";
//exit($sqlstr);
	$rs = $db_admin_salve->getAll($sqlstr,3600);
	if($rs){
		foreach($rs as $k=>$v){
			
			$rs[$k]['ss_sumtime'] = $rs[$k]['ss_sumtime'] . '('.$week[date("l", strtotime($rs[$k]['ss_sumtime']))] .')'; //日期转换为星期格式
			$rs[$k]['ss_advreg'] .= "(".$v['ss_natreg'].")";
			foreach(array_keys($rs[0]) as $ik){
				if($ik!="ss_sumtime"){
					$tsum[0][$ik]+=$v[$ik];
					if(substr($ik,0,1)=="s" and $ik!="ss_advreg"){
						$natkey=str_replace("s","n",$ik);
						$rs[$k][$ik] .= "(".$v[$natkey].")";
					}
				}
			}
		}
	}
	
	$tsum[0]['ss_advreg'] .= "(".$tsum[0]['ss_natreg'].")";
	foreach(array_keys($tsum[0]) as $ik){
		if(substr($ik,0,1)=="s" and ! in_array($ik,array("ss_sumtime","ss_advreg"))){
			$natkey=str_replace("s","n",$ik);
			$tsum[0][$ik] .= "(".$tsum[0][$natkey].")";
		}
	}
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
	
	$page_nav = "统计分析 >> 服务器注册统计(日)";
	include_once("templates/report-serverreg-day.html");
	
}

?>
