<?php
define('PERMI_CODE','mainten_msg');
include_once('init.inc.php');

if(empty($act)){
	$mainten_id = intval($_REQUEST['mainten_id']) ? intval($_REQUEST['mainten_id']) : 0 ;
	$where = " AND mainten_id='".$mainten_id."' ";
	$act = "add";
	
	if($mainten_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."mainten WHERE 1 $where ");
		$act = "edit";
	}
	//游戏列表
	$sql = "SELECT game_id,game_name FROM ".DB_PREFIX."game";
	$query = $db->query($sql);
	$game_group_arr = $db->get_data();
	
	include_once("templates/mainten-add.html");
}else if($act == 'add'){
	$_REQUEST['mainten_start'] = strtotime($_REQUEST['mainten_start'] );
	$_REQUEST['mainten_end'] = strtotime($_REQUEST['mainten_end'] );
	$ret = $db->insert($_REQUEST,DB_PREFIX."mainten");
	if($ret)
	{
		showMessage("添加成功","mainten-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST['mainten_start'] = strtotime($_REQUEST['mainten_start'] );
	$_REQUEST['mainten_end'] = strtotime($_REQUEST['mainten_end'] );
	$ret = $db->update($_REQUEST,DB_PREFIX."mainten");
	if($ret)
	{
		showMessage("修改成功","mainten-add.php?mainten_id=".trim($_REQUEST['mainten_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."mainten", intval($v));
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
	$arr = array('mainten_recom','mainten_isnew','mainten_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."mainten",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else if($act == 'get_last_server'){
	$gid = intval($_REQUEST['gid']);
	$sid = intval($_REQUEST['sid']);
	$row = $db->getRow("select server_name,server_num,server_start from ".DB_PREFIX."server where server_id='{$sid}'  limit 0,1 ");
	$row['server_start'] = date('m月d日 11:00', $row['server_start'] );
	exit($json->encode($row));
}
?>
