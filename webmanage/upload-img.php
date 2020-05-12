<?php
include_once('init.inc.php');

$upload_input = $_REQUEST['upload_input'] ? $_REQUEST['upload_input'] : '';
$thumb = intval($_REQUEST['thumb']);
$thumb_w = intval($_REQUEST['thumb_w']);
$thumb_h = intval($_REQUEST['thumb_h']);
if($_POST['act'] != 'upload')
{
	$page_nav = "文件上传";
	include_once("templates/upload-img.html");
}
else
{
	if($upload_input == 'ico')
	{
		if( $_FILES['pic']['type'] != 'image/x-icon' )
		{
			echo "
				<script type=\"text/javascript\">
					alert('文件格式不正确，应为ico格式的文件。');\n
					history.go(-1);\n
				</script>
			";
		}
		$img_WH = " width='32' height='32' ";
	}
	else
	{
		$img_WH = " width='100' ";
	}
	$directory = "../uploadfiles";
	if($_SESSION['web_member_id'])
	{
		$directory = $directory."/".$_SESSION['web_member_id'];
	}
	else
	{
		$directory = $directory."/webmanage";
	}
	if($thumb == 1)
	{
		$return_arr = UploadThumb($_FILES['pic'],$directory,$thumb_w,$thumb_h);
	}
	else
	{
		$return_arr = upload_file($_FILES['pic'],$directory);
	}
	if( $return_arr['attachment_url'] )
	{
		$author_type = $_SESSION['login_type'];
		if($author_type == 2)
		{
			$author_id = intval($_SESSION['web_member_id']);
			$author_name = $_SESSION['web_member_name'];
		}
		else
		{
			$author_id = intval($_SESSION['sys_admin_id']);
			$author_name = $_SESSION['sys_admin_name'];
		}
		$author_name = $author_name?$author_name:'管理员';
		$attachment_url = str_replace('../','/',$return_arr['attachment_url']);
		$sql = "INSERT INTO ".DB_PREFIX."attachment SET 
				attachment_url='".$attachment_url."',
				attachment_name='".$return_arr['attachment_name']."',
				source_name='".$_FILES['pic']['name']."',
				attachment_size='".$_FILES['pic']['size']."',
				attachment_type='".$_FILES['pic']['type']."',
				author_id='".$author_id."',
				author_name='".$author_name."',
				author_type='".$author_type."',
				add_time='".time()."' ";
		$db->query($sql);
		echo "
			<script type=\"text/javascript\" src=\"/js/jquery.js\"></script>
			<script type=\"text/javascript\" src=\"/js/function_common.js\"></script>
			<script type=\"text/javascript\">
				changeImg('".$upload_input."','".$attachment_url."');
				alert('上传成功');\n
				window.close();\n
			</script>
		";
	}
	else
	{
		echo "
			<script type=\"text/javascript\">
				alert('".$return_arr['info']."');\n
				window.close();\n
			</script>
		";
	}
}
?>