<?php
if(!empty($_REQUEST['id'])){
	define('CURREN_SYSTEM',$_REQUEST['id']);	
}
include_once('init.inc.php');


//1、搜索栏回调
if(empty($act)){
	if(empty($_REQUEST['id'])){
		$list = array( array( 'text'=>'全部','value'=>'') );
		$str = $json->encode($list);
		exit($str);
	}else{
		$list = $db->getAll("SELECT game_id,game_name FROM " .DB_PREFIX."game ");
		
		$retlist = array();
		$retlist[] = array( 'text'=>'全部','value'=>'');
		if(!empty($list)){
			foreach($list as $k =>$row){
				$retlist[] = array( 'text'=>$row['game_name'],'value'=>$row['game_id']);
			}
		}
		$str = $json->encode($retlist);
		exit($str);
	}
}else if($act=='get_platforms'){
	$retlist = array();
	if(!empty($system)){
		foreach($system as $k => $v){
			$retlist[] = array( 'platform_name'=>$v['name'],'platform_id'=>$v['id']);
		}
	}
	$str = $json->encode($retlist);
	exit($str);
}
/*
else{
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
}*/
?>