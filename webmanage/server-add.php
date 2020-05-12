<?php
define('PERMI_CODE','server_msg');
include_once('init.inc.php');

if(empty($act)){
	$server_id = intval($_REQUEST['server_id']) ? intval($_REQUEST['server_id']) : 0 ;
	$where = " AND server_id='".$server_id."' ";
	$act = "add";
	
	if($server_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."server WHERE 1 $where ");
		$act = "edit";
	}
	//游戏列表
	$sql = "SELECT game_id,game_name FROM ".DB_PREFIX."game";
	$query = $db->query($sql);
	$game_group_arr = $db->get_data();
	
	include_once("templates/server-add.html");
}else if($act == 'add'){
	
	$gid=intval($_REQUEST["server_gid"]);
	$server_num=intval($_REQUEST["server_num"]);
	$row = $db->getRow("select * from ".DB_PREFIX."server where server_gid='{$gid}' order by server_num desc limit 0,1 ");
	if(!empty($row)){
		if($server_num != ($row['server_num'] + 1)){
			showMessage("服务器区号不正确，应该为".($row['server_num'] + 1));
			exit();
		}
		
		$server_sid = str_replace('s'.$row['server_num'],'s'.$server_num,  $row['server_sid']);
		if(is_numeric($row['server_sid'])){
			$server_sid = intval($row['server_sid']) + 1;
		}
		$_REQUEST['server_loginurl']=str_replace('s'.$row['server_num'],'s'.$server_num,  $row['server_loginurl']);
		$_REQUEST['server_sid']=$server_sid;
		$_REQUEST['server_type']=$row['server_type'];
		$_REQUEST['server_key']=$row['server_key'];
		$_REQUEST['server_paykey']=$row['server_paykey'];
		$_REQUEST['server_payurl']=str_replace('s'.$row['server_num'],'s'.$server_num,  $row['server_payurl']);
		$_REQUEST['server_isshow']=$row['server_isshow'];
	}
	
	$_REQUEST["server_time"]=time();
	$_REQUEST["server_start"]=strtotime($_REQUEST["server_start"]);
	$ret = $db->insert($_REQUEST,DB_PREFIX."server");
	if($ret)
	{
		showMessage("添加成功","server-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["server_start"]=strtotime($_REQUEST["server_start"]);
	$ret = $db->update($_REQUEST,DB_PREFIX."server");
	if($ret)
	{
		showMessage("修改成功","server-add.php?server_id=".trim($_REQUEST['server_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."server", intval($v));
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
		$ret = $db->update($row,DB_PREFIX."server",$id);
		if($ret)
		{
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else if($act == 'get_next_server'){
	$gid = intval($_REQUEST['gid']);
	$row = $db->getRow("select server_name,server_num from ".DB_PREFIX."server where server_gid='{$gid}' order by server_num desc limit 0,1 ");
	$row['server_name'] = str_replace($row['server_num'].'服',($row['server_num']+1).'服',$row['server_name']);
	$row['server_num'] = $row['server_num'] + 1;
	exit($json->encode($row));
}
?>
