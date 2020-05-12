<?php
define('PERMI_CODE','vip_mgs');
include_once('init.inc.php');
if(empty($act)){
	$vipinfo_id = intval($_REQUEST['vipinfo_id']);
	$where = "and vipinfo_id='".$vipinfo_id."' ";
	$act = "add";
	if($vipinfo_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."vipinfo WHERE 1 $where ");
		$act = "edit";
	}
	
	include_once('templates/vip-mgs-add.html');
	
} else if("add"==$act){ 
//添加
	$_REQUEST['vipinfo_createtime']=time();
	$_REQUEST['vipinfo_admin_id'] = $login_info[2];
	$sql = "SELECT * FROM ".DB_PREFIX."member WHERE member_name='".$_REQUEST['vipinfo_member_name']."' ";
	$vipinfo = $db->getRow($sql);
	if(!empty($vipinfo)){
		$_REQUEST['vipinfo_points'] = $vipinfo['points'];
		$_REQUEST['vipinfo_member_id'] = $vipinfo['member_id'];
	}else{
		showMessage("账号不存在，请重试");
	}
	
	$ret = $db->insert($_REQUEST,DB_PREFIX."vipinfo");
	if($ret)
	{
		showMessage("添加成功","vip-mgs-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
} else if("edit"==$act){
	$row = array();
	$row['vipino_id'] = $_REQUEST['vipinfo_id'];
	$row['vipino_qq'] = $_REQUEST['vipinfo_qq'];
	$ret = $db->update($row,DB_PREFIX."vipinfo");
	if($ret)
	{
		showMessage("修改成功","vip-mgs-add.php?vipinfo_id=".trim($_REQUEST['vipinfo_id']));
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
		$row['vipinfo_id'] = intval($v);
		$ret = $db_admin->delete($row,DB_PREFIX."vipinfo");
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