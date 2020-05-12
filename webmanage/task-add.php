<?php
define('PERMI_CODE','task_msg');
include_once('init.inc.php');

$task_type = array(0=>"天", 1=>"小时", 2=>"分钟");
if(empty($act)){
	$task_id = intval($_REQUEST['task_id']) ? intval($_REQUEST['task_id']) : 0 ;
	$where = " AND task_id='".$task_id."' ";
	$act = "add";
	
	if($task_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."task WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once("templates/task-add.html");
}else if($act == 'add'){
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."task");
	if($ret)
	{
		showMessage("添加成功","task-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["task_startdate"]=strtotime($_REQUEST["task_startdate"]);
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."task");
	if($ret)
	{
		showMessage("修改成功","task-add.php?task_id=".trim($_REQUEST['task_id']));
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
		$ret = $db_admin->delete(array(), DB_PREFIX."task", intval($v));
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
	$arr = array('task_enable');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."task",$id);
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
