<?php

include_once('init_member.inc.php');


if($_REQUEST['action'] == 'saveimage'){
	$row = array();
	$row['avatars_code'] = add_slashes($_REQUEST['id']);
	$row['avatars_name'] = add_slashes(iconv("gb2312","utf-8",$_REQUEST['n']));
	$row['avatars_body'] = add_slashes($_REQUEST['b']);
	$row['avatars_hr'] = add_slashes($_REQUEST['hR']);
	$row['avatars_hs'] = add_slashes($_REQUEST['hS']);
	$row['avatars_hs'] = add_slashes($_REQUEST['hS']);
	$row['avatars_hx'] = add_slashes($_REQUEST['hX']);
	$row['avatars_hy'] = add_slashes($_REQUEST['hY']);
	$row['avatars_mid'] = $login_info[2];
	$row['avatars_time'] = time();
	$avatarimg = '';
	if(file_exists(WEB_ROOT.'/zt/year/flash/avatars/m/'.$row['avatars_code'].'.jpg')){
		$avatarimg = 'avatars/m/'.$row['avatars_code'].'.jpg';
	}
	if(file_exists(WEB_ROOT.'/zt/year/flash/avatars/m/'.$row['avatars_code'].'.jpeg')){
		$avatarimg = 'avatars/m/'.$row['avatars_code'].'.jpeg';
	}
	if(file_exists(WEB_ROOT.'/zt/year/flash/avatars/m/'.$row['avatars_code'].'.gif')){
		$avatarimg = 'avatars/m/'.$row['avatars_code'].'.gif';
	}
	if(file_exists(WEB_ROOT.'/zt/year/flash/avatars/m/'.$row['avatars_code'].'.giff')){
		$avatarimg = 'avatars/m/'.$row['avatars_code'].'.giff';
	}
	$row['avatars_url'] = $avatarimg;
	$db->insert($row,DB_PREFIX.'avatars');
	/*
	请求参数
	action	saveimage
	b	7
	hR	0
	hS	50
	hX	0
	hY	-39
	id	ylxmhlbif6
	n	omjom6
	view	newyeargame
	*/
	exit('response=ok');
}

if($_REQUEST['action']=='uplodeimage' && !empty($_FILES) && !empty($_REQUEST['id'])){
	
	$ext = explode('.',$_FILES['Filedata']['name']);
	$ext = strtolower($ext[sizeof($ext)-1]);
	$filelimit = array('jpg','gif','jpeg','giff');
	if(in_array($ext,$filelimit)){
		copy($_FILES['Filedata']['tmp_name'],WEB_ROOT.'/zt/year/flash/avatars/m/'.$_REQUEST['id'].'.'.$ext);
	}else{
		exit("不支持该文件格式上传");
	}
	
	//$_REQUEST['src'] = $_FILES['Filedata']['tmp_name'];
	//$_REQUEST['desc'] = WEB_ROOT.'/games/year/flash/avatars/m/'.$_REQUEST['id'].'.'.$ext;
	//write_static_cache('upload_info',$_FILES);
	//write_static_cache('request_info',$_REQUEST);
	exit();
}

if(empty($act)){

	$id = 'mwxlzbkkm6';
	if(!empty($_REQUEST['id'])){
		$id = add_slashes($_REQUEST['id']);
	}
	
	$avatarid = '';
	$avatarimg = '';
	$body = '';
	$hr = '';
	$hs = '';
	$hx = '';
	$hy = '';
	
	
	$row = $db->getRow("select * from ".DB_PREFIX."avatars where avatars_code='$id' ");
	if(!empty($row)){
		if($login_status != SUC_LOGIN && empty($row['avatars_mid'])){
			echo("<script> alert('请先注册/登陆后，获取您的分享链接。'); window.location.href='login.html?returl=".urlencode($_SERVER["REQUEST_URI"])."'; </script>");
			exit();

		}
		if(empty($row['avatars_mid'])){
			$row['avatars_mid']=$login_info[2];
			$db->update($row,DB_PREFIX."avatars",$row["avatars_id"]);
		}
		$avatarid = $row['avatars_code'];
		$avatarimg = $row['avatars_url'];
		$body = $row['avatars_body'];
		$hr = $row['avatars_hr'];
		$hs = $row['avatars_hs'];
		$hx = $row['avatars_hx'];
		$hy = $row['avatars_hy'];
	}
	
	
	include_once('templates/zt_year.html');
}
?>