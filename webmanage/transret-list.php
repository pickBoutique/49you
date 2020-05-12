<?php
define('PERMI_CODE','transret_list_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');

$cfg_transret_type = array(
	0=>"VIP充值返还",
	1=>"邀请好友充值返还"
);

if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	$isexport = empty($_REQUEST['export']) ? false : true;
	
	$sqlstr="SELECT * FROM ".DB_PREFIX."transret a  WHERE 1 $where ";
	
	if($isexport){
		$rs = $db_salve->getAll($sqlstr);
	}else{
		//查询数据库
		$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
		$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
		$rs = $db_salve->getdata($sqlstr, &$pager);
	}
	

	//导出excel
	if($isexport){
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]["transret_typename"]=$cfg_transret_type[$rs[$i]["transret_type"]];
		}
	
		$key=array("transret_code"=>"关联充值单号"
		,"transret_mname"=>"返还的会员帐号"
		,"transret_time"=>"返还时间"
		,"transret_rate"=>"返还率"
		,"transret_currency"=>"返还的平台币"
		,"transret_typename"=>"返还类型");
		exportexcel($key,$rs);
		exit();
	}
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 充值返还信息";
	include_once("templates/transret-list.html");
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
	if($key){
		foreach($key as $v){
			echo iconv("utf-8", "gb2312", $v).$nx;
		}
	}
	if($trs){
		foreach($trs as $i){
			echo $tline;
			foreach(array_keys($key) as $ik){
				if($ik=="transret_time"){
					echo iconv("utf-8", "gb2312", date("Y-m-d H:i",$i[$ik])).$nx;
				}else{
					echo iconv("utf-8", "gb2312", $i[$ik]).$nx;
				}
			}
		}
	}
}
?>
