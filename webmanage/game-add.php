<?php
define('PERMI_CODE','game_msg');
include_once('init.inc.php');

if(empty($act)){
	$game_id = intval($_REQUEST['game_id']) ? intval($_REQUEST['game_id']) : 0 ;
	$where = " AND game_id='".$game_id."' ";
	$act = "add";
	
	if($game_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."game WHERE 1 $where ");
		$act = "edit";
	}
	$gamepicdir="../uploadfiles/game/$rs[game_id]";
	
	//游戏列表
	$sql = "SELECT game_id,game_name FROM ".DB_PREFIX."game";
	$query = $db->query($sql);
	$game_group_arr = $db->get_data();
	
	include_once("templates/game-add.html");
}else if($act == 'add'){
	$_REQUEST["game_time"]=time();
	$ret = $db->insert($_REQUEST,DB_PREFIX."game");
	if($ret)
	{
		$gameid=$db->get_insertid();
		gameimg($gameid);
		showMessage("添加成功","game-add.php");
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	
	$ret = $db->update($_REQUEST,DB_PREFIX."game");
	if($ret)
	{
		$gameid=$_REQUEST["game_id"];
		gameimg($gameid);
		showMessage("修改成功","game-add.php?game_id=".trim($_REQUEST['game_id']));
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
		$ret = $db->delete(array(), DB_PREFIX."game", intval($v));
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
	$arr = array('game_recom','game_isnew','game_ishot','game_divide');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db->update($row,DB_PREFIX."game",$id);
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

function gameimg($gameid){
	$gamedir="../uploadfiles/game/$gameid";
	$picfiledata=array("pic1"=>array("W"=>"140","H"=>"129")
			,"pic2"=>array("W"=>"115","H"=>"58")
			,"pic3"=>array("W"=>"140","H"=>"72")
			,"pic4"=>array("W"=>"706","H"=>"240")
			,"pic5"=>array("W"=>"16","H"=>"16")
			,"pic6"=>array("W"=>"258","H"=>"144"));
	$return_arr= array();
	foreach($picfiledata as $k=>$v){
		$file = WEB_ROOT . $_REQUEST[$k];
		$picfile = "$gamedir/$k.jpg";
		$newfile = "$gamedir/$v[W]_$v[H].jpg";
		//文件存在，还有主图不等于原图
		if(file_exists($file) && $newfile != $file && !empty($_REQUEST[$k])){
			make_dir("$gamedir");
			$file2 = array();
			$file2=explode(".",basename($file));
			copy($file, $picfile);
			copy(dirname($file)."/$file2[0]_thumb.$file2[1]", $newfile);
		}
	}
}
?>
