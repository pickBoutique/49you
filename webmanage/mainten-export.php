<?php
define('PERMI_CODE','mainten_msg');
include_once('init.inc.php');
$mainten_id = intval($_REQUEST['mainten_id']) ? intval($_REQUEST['mainten_id']) : 0 ;
if(empty($act)){
	
	$where = " AND mainten_id='".$mainten_id."' ";
	$act = "";
	if($mainten_id)
	{
		$rs = $db->getRow("SELECT * FROM ".DB_PREFIX."mainten WHERE 1 $where ");
		$act = "export";
	}
	//游戏列表
	$sql = "SELECT game_id,game_name FROM ".DB_PREFIX."game";
	$query = $db->query($sql);
	$game_group_arr = $db->get_data();
	
	include_once("templates/mainten-export.html");
}else if($act == 'export'){
	$count = intval($_REQUEST['export_count']);
	$ts = array();
	$ts[] = array('beta_code'=>'邀请码');
	
	$suc = 0;
	$fal = 0;
	$sqlfieldstr="";
	$time = time();
	for($i=0;$i<$count;$i++){
		$code = md5(genOrderCode());
		
		if($suc % 1000 == 0){
			if($suc != 0){
				$db->query($sqlfieldstr);
				$sqlfieldstr="";
			}
			$sqlfieldstr.="insert into ".DB_PREFIX."beta (beta_code,beta_addtime,beta_mainten_id) values";
		}else{
			$sqlfieldstr.= ",";
		}
		
		$sqlfieldstr.=  "('$code','$time','$mainten_id')";
		$suc+=1;
		
		$ts[] = array('beta_code'=>$code);
	}
	$ret=$db->query($sqlfieldstr);
	
	export_to_excel($ts);
	exit();
}

//导出 EXCEL
//$key = array("id"=>"编号","name"=>"名称")
//$trs = array(0=>array("id"=>1,"name"=>"小M"))
function export_to_excel($trs,$filename='export.xls'){
	header("Content-Type: application/vnd.ms-execl");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$tline="";
	$nx="	";
	if(!empty($trs)){
		foreach($trs as $i => $v){
			echo $tline;
			foreach($v as $key => $val){
				echo iconv("utf-8", "gb2312", $val).$nx;
			}
			$tline="\r\n";
		}
	}
}
?>