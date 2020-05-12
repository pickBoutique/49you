<?php
define('PERMI_CODE','trans_list_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');
$trans_id = intval($_REQUEST['trans_id']) ? intval($_REQUEST['trans_id']) : 0 ;
if(empty($act)){
	
	$where = " AND trans_id='".$trans_id."' ";
	$act = "add";
	
	if($trans_id)
	{
		$rs = $db_salve->getRow("SELECT * FROM ".DB_PREFIX."trans WHERE 1 $where ");
		$act = "edit";
		
		$mid = $db_salve->getOne("select member_id from ".DB_PREFIX."member where member_name='{$rs[trans_mname]}' ");
		$server = $db_salve->getAll("select mg_game_id,mg_game_name,mg_server_id,mg_server_name,game_web,mg_last_time from ".DB_PREFIX."mygame left join ".DB_PREFIX."game on mg_game_id=game_id where mg_mid='{$mid}' order by mg_last_time desc limit 0,4");
	}
	
	
	
	include_once("templates/trans-add.html");
}else if($act == 'add'){
	
	//if(empty($_REQUEST['trans_gid']) || empty($_REQUEST['trans_sid'])){
		//showMessage('请先选择游戏及服务器');
		//exit();
	//}
	require_once(WEB_ROOT."/include/user.class.php");
	$obj_member = new User();
	
	if(empty($_REQUEST['trans_mname'])){
		showMessage('帐号不能为空');
		exit();
	}
	if(intval($_REQUEST['trans_money']) <= 0){
		showMessage('充值金额必须大于0');
		exit();
	}
	
	
	$list_mname = explode("\r\n",$_REQUEST['trans_mname']);
	foreach($list_mname as $k => $v){
		if(empty($v)){
			continue;
		}
		$member_info = $obj_member->get_user_by_name($v);
		if(empty($member_info)){
			showMessage($v.' 帐号不存在');
			exit();
		}
	}
	
	$exsits_account = '';
	$spt = '';	
	foreach($list_mname as $k => $v){
		if(empty($v)){
			continue;
		}
		$code = genOrderCode();
		$order = array();
		$order['trans_code'] = $code;
		$order['trans_mid'] = $login_info[2];  //充值人
		$order['trans_mname'] = $v; //充值帐号
		$order['trans_money'] = intval($_REQUEST['trans_money']);
		$order['trans_gid'] = $_REQUEST['trans_gid'];
		$order['trans_sid'] = $_REQUEST['trans_sid'];
		$order['trans_gname'] = $_REQUEST['trans_gname'];
		$order['trans_sname'] = $_REQUEST['trans_sname'];
		$order['trans_addtime'] = time();
		$order['trans_type'] = 'console';  //后台到款
		$order['trans_ip'] = get_client_ip();
		//$order['trans_area'] = get_client_ip();  //TODO:所在区域
		//$order['trans_line'] = get_client_ip();  //TODO:使用线路
		$order['trans_advtype'] ='0';
		$order['trans_advid'] = '0';
		$order['trans_metrid'] = '0';
		$order['trans_phone'] = '';
		
		$ret = $db->insert($order,DB_PREFIX."trans");
		if($ret)
		{
			//showMessage("添加成功","trans-add.php");
		}
		else
		{
			$exsits_account .= $spt . $v;
			$spt = ',';
			//showMessage("添加失败，请重试");
		}
	}
	
	if(empty($exsits_account)){
		showMessage("添加成功","trans-add.php");
	}else{
		showMessage("抱歉，部份帐号添加失败：$exsits_account");
	}
	
}else if($act == 'edit'){
	
	if(empty($_REQUEST['trans_gid']) || empty($_REQUEST['trans_sid'])){
		showMessage('请先选择游戏及服务器');
		exit();
	}
	
	$order = array();
	$order['trans_gid'] = $_REQUEST['trans_gid'];
	$order['trans_sid'] = $_REQUEST['trans_sid'];
	$order['trans_gname'] = $_REQUEST['trans_gname'];
	$order['trans_sname'] = $_REQUEST['trans_sname'];
	
	
	$ret = $db->update($order,DB_PREFIX."trans",$trans_id);
	if($ret)
	{
		showMessage("修改成功","trans-add.php?trans_id=$trans_id");
	}
	else
	{
		showMessage("修改失败，请重试");
	}
	
}
?>
