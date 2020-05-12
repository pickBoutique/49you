<?php
include_once('init.inc.php');

$upload_input = $_REQUEST['upload_input'] ? $_REQUEST['upload_input'] : '';
$upload_attachment_url = $_REQUEST['upload_attachment_url'] ? $_REQUEST['upload_attachment_url'] : '';
if($_POST['act'] != 'upload')
{
	$page_nav = "文件上传";
	include_once("templates/img-upload.html");
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
	$return_arr = upload_file($_FILES['pic'],$directory);
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
			$author_id = intval($_SESSION['web_member_id']);
			$author_name = $_SESSION['web_member_name'];
		}
		$author_name = $author_name?$author_name:'客户';
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
		
		$sql = "select attachment_id  from ".DB_PREFIX."attachment where
				attachment_url='".$attachment_url."' and 
				attachment_name='".$return_arr['attachment_name']."' and 
				source_name='".$_FILES['pic']['name']."' and 
				author_id='".$author_id."' and 
				author_name='".$author_name."' ";
				
				//echo $sql;
		$query = $db->query($sql);
        $rs = $db->get_data();
		if($rs)
		{
		   $attachment_id=$rs[0]['attachment_id'];
		}
		/*	<script type=\"text/javascript\" src=\"/js/jquery.js\"></script>
		<script type=\"text/javascript\" src=\"/js/function_common.js\"></script>*/
		//echo $upload_attachment_url;exit; 
		echo "
		  <script type=\"text/javascript\">
		    function changeImg2(input_id,attachment_url_id,img_url,img_id)
			{
				if(input_id == '')
				{
					return false;
				}
				window.opener.document.getElementById(input_id).value = img_url;
				window.opener.document.getElementById(attachment_url_id).value = img_id;
				window.close();
			}
		  </script>
			<script type=\"text/javascript\">
				changeImg2('".$upload_input."','".$upload_attachment_url."','".$attachment_id."','".$attachment_url."');
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
				window.history.go(-1);\n
			</script>
		";
	}
}
?>