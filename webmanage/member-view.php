<?php
define('PERMI_CODE','user_mgs');
define('CONN_SALVE',true);
include_once('init.inc.php');
if(empty($act)){
	$member_id = intval($_REQUEST['member_id']) ? intval($_REQUEST['member_id']) : 0 ;
	$where = "and member_id='".$member_id."' ";
	$act = "add";
	if($member_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."member WHERE 1 $where limit 0,1";
		$list = $db->getAll($sql);
		$rs = &$list[0];
		
		$disabled = "disabled";
		$act = "edit";
		
		$server = $db_salve->getAll("select mg_game_id,mg_game_name,mg_server_id,mg_server_name,game_web,mg_last_time,mg_time,(select sum(transout_gcurrency) from ".DB_PREFIX."transout where $rs[member_id] = transout_mid  and mg_server_id=transout_sid ) gcurrency  from online_mygame left join online_game on mg_game_id=game_id where mg_mid='{$rs[member_id]}' order by mg_last_time desc limit 0,8");
		
		$total_money = $db_salve->getOne("select sum(trans_money) from ".DB_PREFIX."trans where '$rs[member_name]' = trans_mname and trans_instatus = 1");
		
		$total_currency = $db_salve->getOne("select sum(transin_currency) from ".DB_PREFIX."transin where $rs[member_id] = transin_mid ");
		
		$total_transret = $db_salve->getOne("select sum(transret_currency) from ".DB_PREFIX."transret where $rs[member_id] = transret_mid and transret_type=0 ");
		
		$total_recomret = $db_salve->getOne("select sum(transret_currency) from ".DB_PREFIX."transret where $rs[member_id] = transret_mid and transret_type=1 ");
		
		$total_transint = $db_salve->getOne("select sum(integral_count) from ".DB_PREFIX."integral where $rs[member_id] = integral_mid and integral_type=1 ");
		
		$total_recomint = $db_salve->getOne("select sum(integral_count) from ".DB_PREFIX."integral where $rs[member_id] = integral_mid and integral_type=2 ");
		
		format_namelist_by_id(&$list,"member_advtype","advtype_name","advtype","advtype_id","advtype_name");
		format_namelist_by_id(&$list,"member_advid","adv_name","adv","adv_id","adv_name");
		format_namelist_by_id(&$list,"member_metrid","material_name","material","material_id","material_name");
	}
	
	//会员等级
	$sql = "SELECT * FROM ".DB_PREFIX."member_level";
	$member_level_arr = $db->getAll($sql);

	include_once("templates/member-view.html");
	
}


?>
