<?php
define('PERMI_CODE','trans_member_list_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');

 

if($act == 'dataget'){
	//组建搜索项
	//$filter = $_REQUEST['filter'];
	//$where = get_where($filter);

	$where = "";
	$where1= "";
	$dateset="";
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub){
			if($sub['name']=='trans_gid' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='trans_sid' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='trans_type' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='trans_advid' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='trans_mname' && trim($sub['value'])!=''){
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
			}elseif($sub['name']=='trans_register' && trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}elseif($sub['name']=='trans_intime' && trim($sub['value'])!=''){
				if($sub['oper']=="<=") $sub['oper']="<";
				$where .= " and ".$sub['name'].$sub['oper']."'".$sub['value']."'";
				$dateset="sss";
			}elseif($sub['name']=='trans_money' && trim($sub['value'])!=''){
				$sub['oper']=">=";
				$where1 .= " and resum".$sub['oper'].intval($sub['value']);
			}
		}
	}
	if($dateset == ''){
		$where .= "and trans_intime>=".(strtotime(date("Y-m-d")));
	}

	$isexport = empty($_REQUEST['export']) ? false : true;

	$strsql="select * from (select  *
from (select trans_mname,count(trans_money) recount,sum(trans_money) resum,max(trans_intime) trans_intime 
from ".DB_PREFIX."trans
where 1 $where
and trans_instatus = 1
group by trans_mname";
if(!empty($where1)){
	$strsql.=" having 1 ".$where1;
}
$strsql.=") a ) a";

	if($isexport){
		$rs = $db_salve->getAll($strsql);
	}else{
		//查询数据库
		$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
		$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
		$rs=$db_salve->getdata($strsql, &$pager);
	}

	//print_r($db_salve->get_count_sql($strsql));
	format_fields_by_id($db_salve,&$rs,'trans_mname',DB_PREFIX.'member','member_name',array('member_id'=>'member_id','member_nickname'=>'member_nickname','email'=>'email','telephone'=>'telephone','mobile'=>'mobile','member_level'=>'member_level','add_time'=>'add_time','last_time'=>'last_time','member_advid'=>'member_advid','member_metrid'=>'member_metrid'));
	
	format_namelist_by_id(&$rs,"member_level","member_lvname","member_level","level_id","level_name");
	format_namelist_by_id(&$rs,"member_advid","adv_name","adv","adv_id","adv_name");
	format_namelist_by_id(&$rs,"member_metrid","material_name","material","material_id","material_name");
	
	if(!empty($rs)){
		foreach($rs as $k => $v){
			$rs[$k]['diffdate'] = dateDif($v['last_time'],time());
		}
	}
	//导出excel
	if($isexport){
		$key=array("trans_mname"=>"用户名"
		,"member_nickname"=>"用户昵称"
		,"member_lvname"=>"用户等级"
		//,"email"=>"邮箱地址"
		//,"telephone"=>"联系电话"
		//,"mobile"=>"手机"
		,"recount"=>"充值次数"
		,"resum"=>"充值金额"
		,"add_time"=>"注册时间"
		,"trans_intime"=>"最后充值时间"
		,"last_time"=>"最后登陆时间"
		,"diffdate"=>"未登陆天数"
		,"adv_name"=>"注册广告"
		,"material_name"=>"注册素材"

		);
		exportexcel($key,$rs);
		exit();
	}
	
	
	$record_count = $pager['count'];
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "会员管理 >> 充值会员列表";
	include_once("templates/trans-member-list.html");
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
	if($key)
	foreach($key as $v){
		echo iconv("utf-8", "gb2312", $v).$nx;
	}
	
	if($trs)
	foreach($trs as $i){
		echo $tline;
		foreach(array_keys($key) as $ik){
			if( in_array($ik,array("add_time","trans_intime","last_time"))){
				if(!empty($i[$ik])){
					echo iconv("utf-8", "gb2312", date("Y-m-d h:i",$i[$ik])).$nx;
				}
			}else{
				echo iconv("utf-8", "gb2312", $i[$ik]).$nx;
			}
		}
	}
}
?>
