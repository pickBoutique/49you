<?php
define('PERMI_CODE','report_game_group_day');
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
	if($dateset == ''){
		$where .= " and ss_sumtime >='". (strtotime(date('Y-m-d'))-3600*24*7) ."'";
	}
	
	$sersql="select CONCAT(ss_platform,'_',ss_gid) pf_gid
	,ss_gid
from ".DB_PREFIX."sumd_server
where 1  {$where}
and ss_trantotal > 0
GROUP BY pf_gid,ss_gid
order by ss_platform desc,ss_gid desc";
	//print_r($sersql);
	$tmpstr="";
	$trs = $db_admin_salve->getAll($sersql);
	
	format_fields_by_id($db_admin_salve,&$trs,"pf_gid",DB_PREFIX."v_games","pf_gid",array("game_name"=>"game_name","pf_name"=>"pf_name"));	
	//format_fields_by_id($db_pd,&$trs,"ss_gid",DB_PREFIX."game","game_id",array("game_name"=>"game_name"));	
	
	$tfield = array(array());
	$tsum = array(array());
	//$avg = array();
	$tfield[0]["ss_sumtime"]="日 期";
	$tfield[0]["ss_trantotal"]="合 计";
	
	$tsum[0]["ss_sumtime"]="汇 总";
	$tsum[0]["ss_trantotal"]="合 计";
	$ids="";
	//print_r($trs);
	if($trs){
		foreach($trs as $k=>$v){
			$tfield[0]["s$k"]="$v[pf_name]<br>$v[game_name]";
			$tmpstr.=",sum(case when CONCAT(ss_platform,'_',ss_gid) = '".$v["pf_gid"]."' then ss_trantotal else 0 end) s{$k}";
			if($ids!="") $ids.=",";
			$ids.=$v["ss_gid"];
		}
	}
	$where.=" and ss_gid in({$ids})";
	$sqlstr="select date_format(from_unixtime(ss_sumtime),'%Y-%m-%d') ss_sumtime
,sum(ss_trantotal) ss_trantotal
".$tmpstr."
from ".DB_PREFIX."sumd_server
where 1 {$where}
and ss_trantotal > 0
group by ss_sumtime
order by ss_sumtime desc";
//exit($sqlstr);
	$rs = $db_admin_salve->getAll($sqlstr,3600);
	
	if($rs){
		foreach($rs as $k=>$v){
			
			$rs[$k]['ss_sumtime'] = $rs[$k]['ss_sumtime'] . '('.$week[date("l", strtotime($rs[$k]['ss_sumtime']))] .')'; //日期转换为星期格式
			foreach(array_keys($rs[0]) as $ik){
				if(! in_array($ik,array("ss_sumtime"))){
					$tsum[0][$ik]+=$v[$ik];
					
					if($v["ss_trantotal"]>0 && $ik!="ss_trantotal"){
						$rs[$k][$ik].="[".number_format($v[$ik]/$v["ss_trantotal"]*100,2)."%]";
					}
				}
			}
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
	
	$page_nav = "统计分析 >> 游戏充值统计(日)";
	include_once("templates/report-game-group-day.html");
	
}

?>
