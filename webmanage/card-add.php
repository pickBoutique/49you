<?php
define('PERMI_CODE','card_msg');
include_once('init.inc.php');

if(empty($act)){
	$card_id = intval($_REQUEST['card_id']) ? intval($_REQUEST['card_id']) : 0 ;
	$where = " AND card_id='".$card_id."' ";
	$act = "add";
	
	if($card_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."card WHERE 1 $where ");
		$act = "edit";
	}
	//卡片列表
	$sql = "SELECT * FROM ".DB_PREFIX."cardtype";
	$query = $db->query($sql);
	$game_group_arr = $db->get_data();
	
	include_once("templates/card-add.html");
}else if($act == 'add'){

	//$directory = "../uploadfiles/card";
	
	//print_r($_FILES);
	//$return_file = upload_file($_FILES['cardtxt'],$directory);
	$return_file = WEB_ROOT.$_REQUEST["cardtxt"];
	//echo $return_file;
	//exit();
	if($return_file ==''){
		showMessage("导入失败，请先上传数据文件！");
		exit();
	}
	if (file_exists($return_file)){//检测文件是否存在
		$cardtxt_arr=file($return_file);//将文件全部内容读入到数组$array
	}else{
		showMessage("导入失败，上传文件不存在！");
		exit();
	}
	
	$cardtype = $db->getRow("SELECT * FROM  ".DB_PREFIX."cardtype WHERE cardtype_id='$_REQUEST[card_type]' limit 0,1 ");
	
	
	$_REQUEST["card_addtime"]=time();
	$_REQUEST["card_start"]=strtotime($_REQUEST["card_start"]);
	$_REQUEST["card_end"]=strtotime($_REQUEST["card_end"]);
	$_REQUEST["card_no"]=md5(readFileContent($return_file));
	$_REQUEST["card_timelimit"]=$cardtype['cardtype_timelimit'];
	$_REQUEST["card_start"]=$cardtype['cardtype_start'];
	$_REQUEST["card_end"]=$cardtype['cardtype_end'];
	$_REQUEST["card_gid"]=$cardtype['cardtype_gid'];
	$_REQUEST["card_sid"]=$cardtype['cardtype_sid'];
	
	$count = $db->getOne("SELECT count(*) FROM  ".DB_PREFIX."card WHERE card_no='$_REQUEST[card_no]' limit 0,1 ");
	if($count > 0){
		showMessage("导入失败，该文件已经导入过了，请勿重试");
		exit();
	}


	
	$suc = 0;
	$fal = 0;
	$sqlfieldstr="";
	foreach($cardtxt_arr as $k => $v){
		$val = trim($v);
		if(empty($val)) continue;
		//以每次1000条的速度进行导入
		if($suc % 1000 == 0){
			if($suc != 0){
				$db->query($sqlfieldstr);
				$sqlfieldstr="";
			}
			$sqlfieldstr.="insert into ".DB_PREFIX."card (card_type,card_code,card_no,card_timelimit,card_start,card_end,card_addtime,card_gid,card_sid) values";
		}else{
			$sqlfieldstr.= ",";
		}
		
		$sqlfieldstr.=  "($_REQUEST[card_type],'".$val."','$_REQUEST[card_no]',$_REQUEST[card_timelimit],$_REQUEST[card_start],$_REQUEST[card_end],$_REQUEST[card_addtime],$_REQUEST[card_gid],$_REQUEST[card_sid])";
		$suc+=1;
		
	}
	
	$ret=$db->query($sqlfieldstr);
	if($ret)
	{
		
		//更新总数
		$db->query("update ".DB_PREFIX."cardtype 
					set cardtype_count = (select count(*) from ".DB_PREFIX."card where card_type=cardtype_id and card_status='0' )
					where cardtype_id='$_REQUEST[card_type]' ");
		
		showMessage("成功导入($suc)条记录","card-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$ret = $db->update($_REQUEST,DB_PREFIX."card");
	if($ret)
	{
		showMessage("修改成功","card-add.php?card_id=".trim($_REQUEST['card_id']));
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
}else if($act == 'del'){
	
	$arr_id = explode(',', $_REQUEST['ids']);
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db->delete(array(), DB_PREFIX."card", intval($v));
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
	$arr = array('card_recom','card_isnew','card_ishot');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."card",$id);
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
