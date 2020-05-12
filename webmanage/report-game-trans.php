<?php
define('PERMI_CODE','report_game_trans');
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
$server_num = 160;

if($act == 'dataget'){
	//组建搜索项
	$g_id="";
	$s_id="";
	$where = "";
	$dateset="";
	$pfid="";
	//'充值金额':'0','充值人数':'1'
	$sumtype="0";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='gid' & trim($sub['value'])!=''){
				$g_id=$sub['value'];
			}
			elseif($sub['name']=='sid' & trim($sub['value'])!=''){
				$s_id=$sub['value'];
			}
			elseif($sub['name']=='ss_platform' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$pfid=$sub['value'];
			}
			elseif($sub['name']=='sumtype' & trim($sub['value'])!=''){
				$sumtype=$sub['value'];
			}
			elseif($sub['name']=='ss_sumtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}
		}
	}
	$db_pd=create_system_db($pfid,true);
	//print_r($db_pd);
	//exit();
	if($dateset == ''){$where .= "and ss_sumtime>=".(strtotime(date("Y-m-d"))-3600*24*93);}
	if($g_id=="") {
		$t_rs = $db_pd->getRow("select game_id from ".DB_PREFIX."game order by game_recom desc",true);
		$g_id= $t_rs["game_id"];
	}
	$sersql="select server_id sid,server_num snum
from ".DB_PREFIX."server a
where server_gid = '".$g_id."'";
	$sersql.=empty($s_id) ? "" : " and server_id<='".$s_id."'";
	$sersql.=" order by server_num desc limit 0,".$server_num;
	
	//print_r($sersql);
	
	$tmpstr="";
	$trs = $db_pd->getAll($sersql);
	
	
	$tfield = array(array());
	$tsum = array();
	$avg = array();
	$tfield[0]["ss_sumtime"]="日 期";
	$tfield[0]["ss_trantotal"]="总 额";
	$tfield[0]["ss_trangromem"]="人 数";
	$ids="";
	//print_r($trs);
	foreach($trs as $k=>$v){
		$tfield[0]["s$k"]="$v[snum]服";
		if($sumtype=="1"){
			$tmpstr.=",sum(case when ss_sid = '".$v["sid"]."' then ss_trangromem else 0 end) s{$k}";
		}else{
			$tmpstr.=",sum(case when ss_sid = '".$v["sid"]."' then ss_trantotal else 0 end) s{$k}";
		}
		if($ids!="") $ids.=",";
		$ids.=$v["sid"];
	}
	$where.=" and ss_sid in({$ids})";
	$sqlstr="select date_format(from_unixtime(ss_sumtime),'%Y-%m-%d') ss_sumtime
,sum(ss_trantotal) ss_trantotal
,sum(ss_trangromem) ss_trangromem
".$tmpstr."
from ".DB_PREFIX."sumd_server
where 1 {$where}
group by ss_sumtime
order by ss_sumtime desc";
//exit($sqlstr);
	$rs = $db_admin_salve->getAll($sqlstr,3600);
	if($rs)
	foreach($rs as $k=>$v){
		
		$rs[$k]['ss_sumtime'] = $rs[$k]['ss_sumtime'] . '('.$week[date("l", strtotime($rs[$k]['ss_sumtime']))] .')'; //日期转换为星期格式
		foreach(array_keys($rs[0]) as $ik){
			if($ik!="ss_sumtime"){
				$tsum[$ik]+=$v[$ik];
			}
			if($v[$ik]>0)$avg[$ik]+=1;
		}
	}
	
	$rsc=count($rs);
	$tsum["ss_sumtime"]="汇 总";
	$avg["ss_sumtime"]="平 均";
	foreach(array_keys($tsum) as $ik){
		if($ik!="ss_sumtime" & $avg[$ik]>0)
			$avg[$ik]=floor($tsum[$ik]/$avg[$ik])."";
	}

	$rs[]=$tsum;
	$rs[]=$avg;
	$j=0;
	for($i=0;$i<$rsc;$i+=17){
		if($rs) array_splice($rs,$i-$j,0,$tfield);
		$j++;
	}
	//print_r($avg);
	
	
/*
$a1=array(0=>"Dog",1=>"Cat");
$a2=array(0=>"Tiger",1=>"Lion");
array_splice($a1,1,0,$a2);
*/

	$record_count = 1;
	exit(get_list_json($rs, $record_count));

}else{
	
	$page_nav = "统计分析 >> 游戏充值统计";
	include_once("templates/report-game-trans.html");
	
}

?>
