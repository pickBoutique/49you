<?php
include_once('init.inc.php');
ob_start();

$admin_name = $_REQUEST['admin_name'] ? $_REQUEST['admin_name'] : '' ;
if('' == $admin_name)
{
	exit("用户名不合法");
}
$sql = "SELECT admin_id FROM ".DB_PREFIX."admin WHERE admin_name='".$admin_name."' ";
$query = $db->query($sql);
$rs = $db->get_data();
if($rs)
{
	echo "此名称已经被使用，请改用其他，谢谢。";
}
else
{
	echo "恭喜，您可以使用此名称。";
}
?>