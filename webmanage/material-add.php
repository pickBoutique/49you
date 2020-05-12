<?php
define('PERMI_CODE','material_msg');
include_once('init.inc.php');

if(empty($act)){
	$material_id = intval($_REQUEST['material_id']) ? intval($_REQUEST['material_id']) : 0 ;
	$where = " AND material_id='".$material_id."' ";
	$act = "add";
	
	if($material_id)
	{
		$rs = $db_admin->getRow("SELECT * FROM ".DB_PREFIX."material WHERE 1 $where ");
		$act = "edit";
		$directory = WEB_ROOT."/advs/material/{$material_id}/";
	}
	
	include_once("templates/material-add.html");
}else if($act == 'add'){
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."material");
	if($ret){
		$materialid=$db_admin->get_insertid();
		$directory = WEB_ROOT."/advs/material/{$materialid}/";
		make_dir("$directory");
		showMessage("添加成功","material-add.php");
	}
	else{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$materialid=$_REQUEST["material_id"];
	$directory = WEB_ROOT."/advs/material/{$materialid}/";
	foreach($_FILES['user_upload_file']['name'] as $k=>$v){
		@move_uploaded_file($_FILES['user_upload_file']['tmp_name'][$k],$directory.$_FILES['user_upload_file']['name'][$k]);
	}
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."material");
	if($ret){
		showMessage("修改成功","material-add.php?material_id=".trim($_REQUEST['material_id']));
	}
	else{
		showMessage("修改失败，请重试");
	}
	
}else if($act == 'del'){
	
	$arr_id = explode(',', $_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db_admin->delete(array(), DB_PREFIX."material", intval($v));
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
	$arr = array('material_recom','material_isnew','material_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."material",$id);
		if($ret){
			exit('1');
		}
		else{
			exit("E@修改失败");
		}
	}
}else if($act == 'delfile'){
	$filename = WEB_ROOT."/advs/material/{$_REQUEST[mat_id]}/{$_REQUEST[filename]}";
	if(file_exists($filename)){
		unlink($filename);
		exit('1');
	}else{
		exit("文件不存在，请确认！");
		//exit($filename);		
	}

}else if($act == 'preview'){
	$id = intval($_REQUEST['id']);
	$row = array();
	$row["adv_tpl"] = "tpl_adv_flash_jschange_ip";
	$row["adv_metrid"] = $id;
	$mats = array();
	$mats[] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid]}' ");
	$mats[] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid1]}' ");
	$mats[] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid2]}' ");
	include(WEB_ROOT."/webmanage/templates/{$row[adv_tpl]}.html");
}
?>
