<?php
define('PERMI_CODE','user_mgs');
include_once('init.inc.php');
if(empty($act)){
	$member_id = intval($_REQUEST['member_id']) ? intval($_REQUEST['member_id']) : 0 ;
	$where = "and member_id='".$member_id."' ";
	$act = "add";
	if($member_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE 1 $where";
		$rs = $db->getRow($sql);
		
		$disabled = "disabled";
		$act = "edit";
		
		$server = $db->getAll("select mg_game_id,mg_game_name,mg_server_id,mg_server_name,game_web,mg_last_time,mg_time from online_mygame left join online_game on mg_game_id=game_id where mg_mid='{$rs[member_id]}' order by mg_last_time desc limit 0,8");
	}
	
	//会员等级
	$sql = "SELECT * FROM ".DB_PREFIX."member_level";
	$member_level_arr = $db->getAll($sql);

	include_once("templates/member-add.html");
	
}else if($act == 'add'){
	require_once(WEB_ROOT."/include/user.class.php");
	$obj_member = new User();
	$_REQUEST['member_pwd'] = $obj_member->encry_pwd($_REQUEST['member_name'],$_REQUEST['member_pwd']);
	$_REQUEST['birth'] = strtotime($_REQUEST['birth'] );
	$mid = $obj_member->register($_REQUEST);
	if($mid>0){
		showMessage("添加成功","member-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	require_once(WEB_ROOT."/include/user.class.php");
	$obj_member = new User();
	if(!empty($_REQUEST['member_pwd'])){
		$_REQUEST['member_pwd'] = $obj_member->encry_pwd($_REQUEST['member_name'],$_REQUEST['member_pwd']);
	}else{
		unset($_REQUEST['member_pwd']);
	}
	unset($_REQUEST['member_name']);
	$_REQUEST['birth'] = strtotime($_REQUEST['birth'] );
	$ret = $db->update($_REQUEST,DB_PREFIX."member");
	if($ret)
	{
		showMessage("修改成功","member-add.php?member_id=".trim($_REQUEST['member_id']));
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
		$ret = $db->delete(array(),DB_PREFIX."member",intval($v));
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
	$arr = array('sort_num');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."member",$id);
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
