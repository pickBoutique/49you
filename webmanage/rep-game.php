<?php
define('PERMI_CODE','report_game_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	if($where == '')exit("{totalCount:0,root:{'0':{}}}");
	$where1="";
	$where2="";
	$where3="";
	$where4="";
	$where_arg = explode("and",$where);
	foreach($where_arg as $v){
		if(substr(trim($v),0,strlen("startime"))=="startime"){//统计时间
			$where2.=" and ".str_replace("startime","trans_intime",$v);
		}
	}
	
	$sqlstr="select dtime,trans_mm,trans_mm*10 trans_jf,trans_jf - trans_mm*10 trans_zs
	from
	(select dtime,round(trans_jf) trans_jf,trans_mm
	from 
	(select date_format(from_unixtime(trans_intime),'%Y-%m-%d') dtime,sum(trans_money) trans_jf,sum(trans_currency) trans_mm from ".DB_PREFIX."trans 
where trans_instatus = 1 {$where2}
group by dtime) A) B";


	$rs = $db_salve->getAll($sqlstr);

	$totalrow=array("dtime"=> "合计","trans_mm"=>0,"trans_jf"=>0,"trans_zs"=>0);
	if($rs)
	foreach($rs as $k=>$v){
		if($v["trans_mm"]==0){$rs[$k]["trans_jf"]=0;$v["trans_jf"]=0;}
		$totalrow["trans_mm"]+=$v["trans_mm"]; 
		$totalrow["trans_jf"]+=$v["trans_jf"]; 
		$totalrow["trans_zs"]+=$v["trans_zs"]; 
	}
	$totalrow["trans_mm"].="";
	$totalrow["trans_jf"].="";
	$totalrow["trans_zs"].="";
	$rs[] = $totalrow;

	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "充值统计分析 >> 充值统计";
	include_once("templates/rep-game.html");
	
}

?>
