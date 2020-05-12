<?php
define('PERMI_CODE','kefuqq_mgs');
include_once('init.inc.php');
if(empty($act)){
	$kefuqq_id = intval($_REQUEST['kefuqq_id']);
	$where = "and kefuqq_id='".$kefuqq_id."' ";
	$act = "add";
	if($kefuqq_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."kefuqq WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once('templates/kefuqq-mgs-add.html');
	
} else if("add"==$act){ 
//添加
	$kefuqq_game_id = (int)$_REQUEST['kefuqq_game_id']?(int)$_REQUEST['kefuqq_game_id']:0;
	$kefuqq_server_id = (int)$_REQUEST['kefuqq_server_id']?(int)$_REQUEST['kefuqq_server_id']:0;
	$where = " and kefuqq_game_id={$kefuqq_game_id} and kefuqq_server_id={$kefuqq_server_id}";
	
	$sql = "SELECT * FROM ".DB_PREFIX."kefuqq WHERE 1 ".$where;
	$kefuqqinfo = $db->getRow($sql);
	if(!empty($kefuqqinfo)){
		showMessage("此游戏的服务器已经添加qq联系人，请修改就行。");
	}
		
	$ret = $db->insert($_REQUEST,DB_PREFIX."kefuqq");
	if($ret)
	{
		showMessage("添加成功","kefuqq-mgs-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
} else if("edit"==$act){
	$row = array();
	$row['kefuqq_id'] = $_REQUEST['kefuqq_id'];
	$row['kefuqq_game_id'] = $_REQUEST['kefuqq_game_id'];
	$row['kefuqq_server_id'] = $_REQUEST['kefuqq_server_id'];
	$row['kefuqq_qq'] = $_REQUEST['kefuqq_qq'];
	$ret = $db->update($row,DB_PREFIX."kefuqq");
	if($ret)
	{
		showMessage("修改成功","kefuqq-mgs-add.php?kefuqq_id=".trim($_REQUEST['kefuqq_id']));
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
	
	
}else if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$row = array();
		$row['kefuqq_id'] = intval($v);
		$ret = $db_admin->delete($row,DB_PREFIX."kefuqq");
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
}
?>