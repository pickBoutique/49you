<?php
define('PERMI_CODE','adv_mgs');
include_once('init.inc.php');

function get_system_key_by_id($pid){
	global $system;
	if(!empty($system)){
		foreach($system as $k=>$v){
			if($v['id'] == $pid){
				return $k;
			}
		}
	}
	return '';
}


if(empty($act)){
	$adv_id = intval($_REQUEST['adv_id']) ? intval($_REQUEST['adv_id']) : 0 ;
	$where = "and adv_id='".$adv_id."' ";
	$act = "add";
	if($adv_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."adv WHERE 1 $where";
		$rs = $db_admin->getRow($sql);
		
		$act = "edit";
	}
	//推讲渠道
	$sql = "SELECT advtype_id,advtype_name FROM ".DB_PREFIX."advtype";
	$advtype_group_arr = $db_admin->getAll($sql);
	//推讲渠道
	$sql = "SELECT advgroup_id,advgroup_name FROM ".DB_PREFIX."advgroup";
	$advgroup_arr = $db_admin->getAll($sql);
	/*//素材
	$sql = "SELECT material_id,material_name FROM ".DB_PREFIX."material";
	$material_group_arr = $db->getAll($sql);
	*/
	if(!empty($_REQUEST['change_tpl'])){
		$act = "bat_edit";
		include_once("templates/adv-change-tpl.html");
	}else if(!empty($_REQUEST['change_game'])){
		$act = "bat_edit";
		include_once("templates/adv-change-game.html");
	}else{
		include_once("templates/adv-add.html");
	}
}else if($act == 'add'){
	$_REQUEST["adv_udtime"]=time();
	$_REQUEST["adv_system"]=$system_id;
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."adv");
	if($ret)
	{
		
		$id = $db_admin->get_insertid();
		
		$key = get_system_key_by_id($_REQUEST["adv_pid"]);
		
		$url = gen_adv($id,true,$key);
		if(!empty($url)){
			
			$db_admin->update(array('adv_url'=>$url), DB_PREFIX."adv",$id );
			showMessage("添加成功! 您的广告地址为：".$url , "adv-add.php" );
			
		}else{
			
			showMessage("添加成功! 但无法生成广告页面，请联系管理员!" , "adv-add.php" );
			
		}
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["adv_udtime"]=time();
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."adv");
	if($ret)
	{
		showMessage("修改成功!", "adv-add.php?adv_id=".trim($_REQUEST['adv_id']) );
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
	
}else if($act == 'del'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db_admin->delete(array(),DB_PREFIX."adv",intval($v));
		if($ret){
			gen_adv(intval($v));
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
	$arr = array('adv_sort','adv_metrid','adv_metrid1','adv_metrid2','adv_title','adv_title1','adv_title2');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."adv",$id);
		if($ret)
		{	
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
}else if($act == 'update'){
	$id = intval($_REQUEST['id']);
	$row = $db_admin->getRow("SELECT adv_pid FROM ".DB_PREFIX."adv WHERE adv_id=".$id);
	$key = get_system_key_by_id($row["adv_pid"]);
	$url = gen_adv($id,true,$key);
	if(!empty($url)){
		$db_admin->update(array('adv_url'=>$url), DB_PREFIX."adv",$id );
		exit('E@更新成功');
		
	}else{
		exit('E@无法生成广告页面，请联系管理员');
	}
}else if($act == 'regen'){
	
	$count = 0;
	$list = $db_admin->getAll(" select adv_id,adv_pid from " .DB_PREFIX."adv WHERE 1 AND adv_system='{$system_id}'");
	foreach($list as $k => $v){
		$key = get_system_key_by_id($v["adv_pid"]);
		$url = gen_adv($v['adv_id'],true,$key);
		
		if(!empty($url)){
			$db_admin->update(array('adv_url'=>$url), DB_PREFIX."adv",$v['adv_id'] );
			$count++;
			//exit('E@更新成功');
		}else{
			//exit('E@无法生成广告页面，请联系管理员');
		}	
		
	}
	
	$fal = sizeof($list) - $count ;
	
	exit("E@已成功更新 $count 个广告页面， $fal 个广告页面更新失败。");
	
		
}else if($act == 'preview'){
	$id = intval($_REQUEST['id']);
	gen_adv($id,false);
}else if($act == 'set_gsid'){
	$gsid=array();
	$gsid['adv_pid']=$_REQUEST['pid'];
	$gsid['adv_gid']=$_REQUEST['gid'];
	$gsid['adv_sid']=$_REQUEST['sid'];
	$count = 0;
	if(!empty($gsid['adv_pid']) && !empty($gsid['adv_gid']) && !empty($gsid['adv_sid'])){
		$list = $db_admin->getAll(" select adv_id from " .DB_PREFIX."adv  WHERE 1 AND adv_system='{$system_id}'");
		foreach($list as $k => $v){
			if(!empty($gsid)){
				$db_admin->update($gsid, DB_PREFIX."adv", $v['adv_id'] );
				$count++;
				//exit('E@更新成功');
			}else{
				//exit('E@无法生成广告页面，请联系管理员');
			}	
		}
		
		$fal = sizeof($list) - $count ;
		exit("E@已成功更新 $count 个广告游戏服务器， $fal 个广告游戏服务器更新失败。");
	}else{
		exit("E@请先选择游戏和服务器，再进行批量切换！");
	}
	
}else if($act == 'bat_edit'){
	
	$arr_id = explode(',',$_REQUEST['ids']);
	
	$suc = 0;
	$fal = 0;
	foreach($arr_id as $k => $v){
		$ret = $db_admin->update($_REQUEST,DB_PREFIX."adv",intval($v));
		if($ret){
			$suc++;
		}else{
			$fal++;
		}
	}
	//$result = "E@已成功删除 $suc 条记录";
	//if($fal){
		//$result .= "，有 $fal 条记录删除失败";
	//}
	showMessage("修改成功!" );
}

?>