<?php
set_time_limit(59);
define('PERMI_CODE','report_game_audit');
define('CONN_SALVE',true);
include_once('init.inc.php');

if($act == 'dataget'){
	//组建搜索项
	$where = "";
	$pfid = 5;
	$setdate="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='trans_outtime' & trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$setdate="set";
			}
			if($sub['name']=='sd_platform' & trim($sub['value'])!=''){
				$pfid=$sub['value'];
			}
			if($sub['name']=='trans_gid' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
			if($sub['name']=='trans_sid' & trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}
		}
	}
	$db_pd=create_system_db($pfid,true);
	if($setdate == ''){$where .= "and trans_outtime>=".(strtotime(date("Y-m-d")));}
	$isexport = empty($_REQUEST['export']) ? false : true;
	
	//支付方式
	$cfg_pay_type_56uu = array(
	'1'=>'易宝网银',
'2'=>'易宝神州行',
'3'=>'易宝骏网',
'6'=>'快钱网银',
'7'=>'快钱神州行',
'8'=>'易宝盛大卡',
'9'=>'易宝联通卡',
'10'=>'易宝网易卡',
'11'=>'易宝完美卡',
'12'=>'易宝征途卡',
'4'=>'支付宝',
'13'=>'易宝Q币卡',
'14'=>'联华OK卡',
'15'=>'固话充值(易宝e卡通)',
'16'=>'神州付移动卡',
'17'=>'神州付联通卡',
'18'=>'神州付电信卡',
'19'=>'全国固话充值(V币)',
'20'=>'人工充值'

	);	
	$cfg_pay_type_joy = array(
	'1'=>'银行卡',
'14'=>'移动充值卡',
'15'=>'WAP 银行卡',
'16'=>'征途游戏卡',
'17'=>'盛大游戏卡',
'18'=>'联通一卡充',
'19'=>'久游卡',
'20'=>'网易卡',
'21'=>'完美卡',
'22'=>'搜狐卡',
'23'=>'电信卡',
'24'=>'易宝一卡通',
'25'=>'骏网一卡通',
'26'=>'支付宝'

	);	
	
	$my_cfg_pay_type=array();
	
	$sqlstr="select * from (";
	if($pfid=="5"){
		//======= 49you =======
		$sqlstr.="select trans_outtime
,trans_code
,trans_mname
,trans_type
,trans_gid
,trans_sid
,(transout_gcurrency / (game_rate * 10)) trans_gmoney
,transout_gcurrency trans_currency
,trans_ip
from ".DB_PREFIX."trans 
left join ".DB_PREFIX."transout on trans_code=transout_code 
left join ".DB_PREFIX."game on game_id=trans_gid
where ( trans_outstatus =1) {$where}";

	$my_cfg_pay_type=$cfg_pay_type;

	}elseif($pfid=="2"){
		//======= 56uu =======
		$sqlstr.="select trans_outtime
,trans_code
,trans_mname
,trans_type
,trans_gid
,trans_sid
,(trans_currency / game_rate) trans_gmoney
,trans_currency
,trans_ip
from ".DB_PREFIX."trans 
left join ".DB_PREFIX."game on game_id=trans_gid
where ( trans_outstatus =1) {$where}";

	$my_cfg_pay_type=$cfg_pay_type_56uu;

	}elseif($pfid=="1"){
		//======= joy ======
		$sqlstr.="select trans_outtime
,trans_code
,trans_mname
,trans_type
,trans_gid
,trans_sid
,(trans_currency / game_rate) trans_gmoney
,trans_currency
,trans_ip
from ".DB_PREFIX."trans 
left join ".DB_PREFIX."game on game_id=trans_gid
where ( trans_outstatus =1) {$where}";

	$my_cfg_pay_type=$cfg_pay_type_joy;
	}
	$sqlstr.=") a";

	//exit($sqlstr);
	//查询数据库
	if($isexport){
		$rs = $db_pd->getAll($sqlstr);
	}else{
		$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
		$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
		
		$rs = $db_pd->getdata($sqlstr, &$pager,3600);
	}

	//format_namelist_by_id(&$rs,"sd_advid","adv_name","adv","adv_id","adv_name");
	format_fields_by_id($db_pd,&$rs,"trans_gid",DB_PREFIX."game","game_id",array("game_name"=>"game_name"));
	format_fields_by_id($db_pd,&$rs,"trans_sid",DB_PREFIX."server","server_id",array("server_name"=>"server_name"));

	for($i=0;$i<sizeof($rs);$i++){
		$rs[$i]["trans_typename"]=$my_cfg_pay_type[$rs[$i]["trans_type"]];
	}
	//导出excel
	if($isexport){
		$key=array("trans_outtime"=>"游戏到账时间"
		,"trans_code"=>"账单号"
		,"trans_mname"=>"会员账号"
		,"game_name"=>"游戏名称"
		,"server_name"=>"服务器"
		,"trans_typename"=>"充值类型"
		,"trans_gmoney"=>"对账金额"
		,"trans_currency"=>"游戏币"
		,"trans_ip"=>"IP地址");
		exportexcel($key,$rs);
		exit();
	}
	
	
	//$rs[] = $rechargerow;
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "财务管理 >> 游戏对账";
	include_once("templates/report-game-audit.html");
	
}

//导出 EXCEL
//$key = array("id"=>"编号","name"=>"名称")
//$trs = array(0=>array("id"=>1,"name"=>"小M"))
function exportexcel($key,$trs){
	header("Content-Type: application/vnd.ms-execl");
	header("Content-Disposition: attachment; filename=myExcel.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$tline="\r\n";
	$nx="	";
	if($key){
		foreach($key as $v){
			echo iconv("utf-8", "gb2312", $v).$nx;
		}
	}
	if($trs){
		foreach($trs as $i){
			echo $tline;
			foreach(array_keys($key) as $ik){
				if($ik=="trans_outtime"){
					echo iconv("utf-8", "gb2312", date("Y-m-d H:i:s",$i[$ik])).$nx;
				}else{
					echo iconv("utf-8", "gb2312", $i[$ik]).$nx;
				}
			}
		}
	}
}
?>
