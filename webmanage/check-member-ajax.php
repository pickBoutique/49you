<?php
include_once('init.inc.php');
ob_start();

$email = $_REQUEST['email'] ? $_REQUEST['email'] : '' ;
if('' == $email)
{
	exit("0|用户名不合法");
}
$sql = "SELECT member_id FROM ".DB_PREFIX."member WHERE email='".$email."' ";
$query = $db->query($sql);
$rs = $db->get_data();

if($rs)
{
	echo "1|电子邮箱已经存在，请选择其他。";
}
else
{
	echo "2|恭喜，您可以使用此名称。";
}

?>