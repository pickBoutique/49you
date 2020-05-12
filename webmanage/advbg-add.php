<?php
define('PERMI_CODE','advbg_mgs');
include_once('init.inc.php');

function process_maturl($url){
	
	if(strpos($url, 'http') === false){
		$url = YOU_ROOT . $url;
	}
	return $url;
	
}


if(empty($act)){
	$adv_id = intval($_REQUEST['advbg_id']) ? intval($_REQUEST['advbg_id']) : 0 ;
	$where = "and advbg_id='".$adv_id."' ";
	$act = "add";
	if($adv_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."advbg WHERE 1 $where";
		$rs = $db_admin->getRow($sql);
		
		$act = "edit";
	}
	
	include_once("templates/advbg-add.html");

}else if($act == 'add'){
	$_REQUEST["advbg_mat"] = process_maturl($_REQUEST["advbg_mat"]);
	$_REQUEST["advbg_udtime"]=time();
	$ret = $db_admin->insert($_REQUEST,DB_PREFIX."advbg");
	if($ret)
	{
		
		$id = $db_admin->get_insertid();
		
		
		$url = gen_advbg($id,true);
		if(!empty($url)){
			
			$db_admin->update(array('advbg_url'=>$url), DB_PREFIX."advbg",$id );
			showMessage("添加成功! 您的广告地址为：".$url , "advbg-add.php" );
			
		}else{
			
			showMessage("添加成功! 但无法生成广告页面，请联系管理员!" , "advbg-add.php" );
			
		}
	}
	else
	{
		showMessage("添加失败，请重试");
	}
	
}else if($act == 'edit'){
	$_REQUEST["advbg_mat"] = process_maturl($_REQUEST["advbg_mat"]);
	$_REQUEST["advbg_udtime"]=time();
	$ret = $db_admin->update($_REQUEST,DB_PREFIX."advbg");
	if($ret)
	{
		showMessage("修改成功!", "advbg-add.php?advbg_id=".trim($_REQUEST['advbg_id']) );
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
		$ret = $db_admin->delete(array(),DB_PREFIX."advbg",intval($v));
		if($ret){
			gen_advbg(intval($v));
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
	$arr = array('advbg_sort','advbg_status');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	if(in_array($name,$arr)){
		$row = array();
		$row[$name] = trim($value);
		$ret = $db_admin->update($row,DB_PREFIX."advbg",$id);
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
	$url = gen_advbg($id,true);
	if(!empty($url)){
		$db_admin->update(array('advbg_url'=>$url), DB_PREFIX."advbg",$id );
		exit('E@更新成功');
		
	}else{
		exit('E@无法生成广告页面，请联系管理员');
	}
}else if($act == 'regen'){
	
	$count = 0;
	$list = $db_admin->getAll(" select advbg_id from " .DB_PREFIX."advbg WHERE 1 ");
	foreach($list as $k => $v){
		$url = gen_advbg($v['advbg_id'],true);
		
		if(!empty($url)){
			$db_admin->update(array('advbg_url'=>$url), DB_PREFIX."advbg",$v['advbg_id'] );
			$count++;
			//exit('E@更新成功');
		}else{
			//exit('E@无法生成广告页面，请联系管理员');
		}	
		
	}
	
	$fal = sizeof($list) - $count ;
	
	exit("E@已成功更新 $count 个广告页面， $fal 个广告页面更新失败。");
	
		
}else if($act == 'preview'){
	echo "<html><head></head><body>";
	echo "<script language='javascript' >";
	$id = intval($_REQUEST['id']);
	gen_advbg($id,false);
	echo "advbg_obj.show();";
	echo "</script>";
	echo "</body></html>";
}

?>