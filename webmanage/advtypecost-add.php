<?php
define('PERMI_CODE','advtypecost_msg');
include_once('init.inc.php');

if(empty($act)){
	$advcost_id = intval($_REQUEST['advcost_id']) ? intval($_REQUEST['advcost_id']) : 0 ;
	$where = " AND advcost_id='".$advcost_id."' ";
	$act = "add";
	
	if($advcost_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."advtypecost WHERE 1 $where ");
		$act = "edit";
	}
	
	//推讲渠道
	$sql = "SELECT adv_id,adv_name FROM ".DB_PREFIX."adv";
	$advtype_group_arr = $db->getAll($sql);
		
	include_once("templates/advtypecost-add.html");
}else if($act == 'add'){
	$_REQUEST["advcost_time"]=strtotime($_REQUEST["advcost_time"]);
	$_REQUEST["advcost_time"]=strtotime(date("Y-m-d",$_REQUEST["advcost_time"]));
	
	$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."adv WHERE adv_id='{$_REQUEST[advcost_advid]}'");
	$_REQUEST["advcost_advtype"] = $rs["adv_type"];
	//$_REQUEST["advcost_time"]=strtotime($_REQUEST["advcost_time"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."advtypecost");
	if($ret)
	{
		showMessage("添加成功","advtypecost-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["advcost_time"]=strtotime($_REQUEST["advcost_time"]);
	//echo $_REQUEST["advcost_time"];
	$_REQUEST["advcost_time"]=strtotime(date("Y-m-d",$_REQUEST["advcost_time"]));
	//$_REQUEST["advcost_time"]=strtotime($_REQUEST["advcost_time"]);

	//echo date("Y-m-d",$_REQUEST["advcost_time"]);
	$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."adv WHERE adv_id='{$_REQUEST[advcost_advid]}'");
	$_REQUEST["advcost_advtype"] = $rs["adv_type"];
	$ret = $db->update($_REQUEST,DB_PREFIX."advtypecost");
	if($ret)
	{
		showMessage("修改成功","advtypecost-add.php?advcost_id=".trim($_REQUEST['advcost_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."advtypecost", intval($v));
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
	$arr = array('server_isnew','server_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."advtypecost",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}
?>
