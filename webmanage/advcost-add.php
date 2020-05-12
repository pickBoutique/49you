<?php
define('PERMI_CODE','advcost_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');

if(empty($act)){
	$advcost_id = intval($_REQUEST['advcost_id']) ? intval($_REQUEST['advcost_id']) : 0 ;
	$where = " AND advcost_id='".$advcost_id."' ";
	$act = "add";

	$pager = array( 'page'=>'1', 'size'=>'20','sort'=>'adv_udtime','dir'=>'desc');
	$rs_adv = $db_salve->getdata("SELECT adv_id,adv_name FROM ".DB_PREFIX."adv a", &$pager);	
	if($advcost_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."advcost WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/advcost-add.html");
}else if($act == 'add'){
	$_REQUEST['advcost_start'] = strtotime($_REQUEST['advcost_start'] );
	$_REQUEST['advcost_end'] = strtotime($_REQUEST['advcost_end'] );
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."advcost");
	if($ret)
	{
		showMessage("添加成功","advcost-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST['advcost_start'] = strtotime($_REQUEST['advcost_start'] );
	$_REQUEST['advcost_end'] = strtotime($_REQUEST['advcost_end'] );
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."advcost");
	if($ret)
	{
		showMessage("修改成功","advcost-add.php?advcost_id=".trim($_REQUEST['advcost_id']));
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
}else if($act == 'del'){
	
	$arr_id = explode(',', $_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db_admin->delete(array(), DB_PREFIX."advcost", intval($v));
		if($ret){
			$suc++;
		}else{
			$fal++;
		}
	}
	$result = "E@已成功删除 $suc 条记录";
	if($fal){
		$result .= "，有 $fal 条记录删除失败";
	}
	exit($result);
	
}else if($act == 'editor'){
	$dates=array();
	$dates['advcost_advid'] = $_REQUEST['advid'];
	$dates['advcost_start'] = $_REQUEST['edtime'];
	$dates['advcost_startcost'] = $_REQUEST['advcost'];
	$where = "";
	$where .=" and advcost_advid='".$dates['advcost_advid']."'";
	$where .=" and advcost_start='".$dates['advcost_start']."'";
	//$where .=" and advcost_startcost='".$dates['advcost_startcost']."'";
		
	$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."advcost WHERE 1 $where ");
	if($rs){
		$ret = $db_admin->update($dates,DB_PREFIX."advcost",$rs["advcost_id"]);
	}else{
		$ret = $db_admin->insert($dates,DB_PREFIX."advcost");
	}
	if($ret){
		exit('1');
	}else{
		exit("E@修改失败");
	}
}
?>
