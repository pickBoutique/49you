<?php
if(!empty($_REQUEST['parent'])){
	define('CURREN_SYSTEM',$_REQUEST['parent']);	
}
include_once('init.inc.php');

//1、搜索栏回调
if(isset($_REQUEST['id'])){
	$game_id = intval($_REQUEST['id']);
	if(empty($game_id)){
		$list = array( array( 'text'=>'全部','value'=>'') );
		$str = $json->encode($list);
		exit($str);
	}else{
		$list = $db->getAll("SELECT server_id,server_name FROM " .DB_PREFIX."server WHERE server_gid='{$game_id}' ");
		
		$retlist = array();
		$retlist[] = array( 'text'=>'全部','value'=>'');
		foreach($list as $k =>$row){
			$retlist[] = array( 'text'=>$row['server_name'],'value'=>$row['server_id']);
		}
		$str = $json->encode($retlist);
		exit($str);
	}
}else{
//2、表单级联下拉框回调
	$game_id = intval($_REQUEST['game_id']);
	if(empty($game_id)){
		$list = $db->getAll("SELECT game_id,game_name FROM " .DB_PREFIX."game ");
		$str = $json->encode($list);
		exit($str);
	}else{
		$list = $db->getAll("SELECT server_id,server_name FROM " .DB_PREFIX."server WHERE server_gid='{$game_id}' ");
		$str = $json->encode($list);
		exit($str);
	}
}
?>