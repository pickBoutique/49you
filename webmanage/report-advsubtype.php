<?php
set_time_limit(59);
define('PERMI_CODE','report_advsubtype_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='sd_sumtime' && trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$setdate="set";
			}
			if($sub['name']=='sd_platform' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='sd_subtype' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name']." like '%".$sub['value']."%'";
			}
			if($sub['name']=='sd_advid' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='advgroup_id' && trim($sub['value'])!=''){
				$where .= " and sd_advid in (".get_advidsbykey($sub['value']).")";
			}
			
		}
	}
	if($setdate == ''){$where .= "and sd_sumtime>=".(strtotime(date("Y-m-d")));}
	$isexport = empty($_REQUEST['export']) ? false : true;
	$sqlstr="select * from (select sd_sumtime ,sd_advid ,sd_subtype 
,sum(sd_memreg) sd_memreg 
,sum(sd_trangromem) sd_trangromem
,sum(sd_trantotal) sd_trantotal
,sum(sd_actve2day) sd_actve2day 
,sum(sd_actve3day) sd_actve3day 
,sum(sd_actve5day) sd_actve5day 
,sum(sd_actve7day) sd_actve7day 
from ".DB_PREFIX."sumd_advsubtype
where 1 $where
group by sd_sumtime,sd_advid,sd_subtype) a";

	//exit($sqlstr);
	//查询数据库
	if($isexport){
		$rs = $db_admin_salve->getAll($sqlstr);
	}else{
		$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
		$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
		
		$rs = $db_admin_salve->getdata($sqlstr, &$pager,3600);
	}

	format_namelist_by_id(&$rs,"sd_advid","adv_name","adv","adv_id","adv_name");
	
	//导出excel
	if($isexport){
		$key=array("sd_sumtime"=>"统计时间"
		,"adv_name"=>"渠道名称"
		,"sd_subtype"=>"子站编码"
		,"sd_memreg"=>"注册人数"
		,"sd_trangromem"=>"付费人数"
		,"sd_trantotal"=>"充值总额"
		,"sd_actve2day"=>"2天活跃"
		,"sd_actve3day"=>"3天活跃"
		,"sd_actve5day"=>"5天活跃"
		,"sd_actve7day"=>"7天活跃");
		exportexcel($key,$rs);
		exit();
	}
	
	
	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 渠道子站统计";
	include_once("templates/report-advsubtype.html");
	
}

//导出 EXCEL
//$key = array("id"=>"编号","name"=>"名称")
//$trs = array(0=>array("id"=>1,"name"=>"小M"))
function exportexcel($key,$trs){
	header("Content-Type: application/vnd.ms-execl");
	header("Content-Disposition: attachment; filename=myExcel.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$tline="\r\n";
	$nx="	";
	if($key)
	foreach($key as $v){
		echo iconv("utf-8", "gb2312", $v).$nx;
	}
	if($trs){
		foreach($trs as $i){
			echo $tline;
			foreach(array_keys($key) as $ik){
				if($ik=="sd_sumtime"){
					echo iconv("utf-8", "gb2312", date("Y-m-d",$i[$ik])).$nx;
				}else{
					echo iconv("utf-8", "gb2312", $i[$ik]).$nx;
				}
			}
		}
	}
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
