<?php
/*
creater MWZ 2011-05-03
*/
//define('PERMI_CODE','supper_mgs');
include_once('init.inc.php');
$admin_id = $login_info[2];
$where = "and admin_id='".$admin_id."' ";

if($admin_id)
{
	$sql = "SELECT * FROM ".DB_PREFIX."admin  WHERE 1 $where";
	$rs = $db_admin->getRow($sql);
}
if(empty($act)){
	
	$act = "edit";
	//页面导航
	$page_nav = "修改密码";
	
	include_once("templates/admin-pwd.html");
	
}else if($act == 'edit'){
	$old_pwd = $obj_user->encry_pwd($rs['admin_name'], md5(trim($_REQUEST['old_password'])) ); 
	if($old_pwd == $rs['admin_pwd']){
		if($_REQUEST['admin_pwd'] == $_REQUEST['admin_repwd']){
			$row = array();
			$row['admin_id'] = $admin_id;
			$row['admin_pwd'] = $obj_user->encry_pwd($rs['admin_name'], md5(trim($_REQUEST['admin_pwd'])) );
			$ret = $db_admin->update($row,DB_PREFIX."admin");
			
			showMessage("修改成功");
		}else{
			showMessage("两次输入的密码不一致");
		}
	}else{
		showMessage("原密码不正确");
	}
	
	
	
}
?>
