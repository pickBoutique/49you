<?php
define('PERMI_CODE','report_transret_type');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	//组建搜索项
	$filter = $_REQUEST['filter'];
	$where = get_where($filter);
	$where1="";
	$where_arg = explode("and",$where);
	$group = "";
	
	foreach($where_arg as $v){
		if(substr(trim($v),0,strlen("add_time"))=="add_time"){//注册时间
			$where1.=" and ".$v;
		}
		if(substr(trim($v),0,strlen("group"))=="group"){//统计方式
			$group = "group";
		}
	}

	//汇总统计
	$sqlstr="select '合计' dtime
,count(member_name) members
,sum(case when login_day >=2 then 1 else 0 end) level2
,sum(case when login_day >=3 then 1 else 0 end) level3
,sum(case when login_day >=5 then 1 else 0 end) level5
,count(trans_money) trans_rs
,sum(trans_money) trans_money
from (select member_name
,login_day
,sum(trans_money) trans_money
from ".DB_PREFIX."member a
left join ".DB_PREFIX."trans on trans_instatus = 1 and member_name=trans_mname
where member_recom>0 $where1
group by member_name
,login_day) a";
	
	//exit($sqlstr);
	$rechargerow = $db_salve->getRow($sqlstr);
	$rs=array();
	
	if(!empty($group)){
	//明细记录
	$sqlstr="select dtime
,count(member_name) members
,sum(case when login_day >=2 then 1 else 0 end) level2
,sum(case when login_day >=3 then 1 else 0 end) level3
,sum(case when login_day >=5 then 1 else 0 end) level5
,count(trans_money) trans_rs
,sum(trans_money) trans_money
from (select date_format(from_unixtime(add_time),'%Y-%m-%d') dtime
,member_name
,login_day
,sum(trans_money) trans_money
from ".DB_PREFIX."member a
left join ".DB_PREFIX."trans on trans_instatus = 1 and member_name=trans_mname
where member_recom>0 $where1
group by member_name
,login_day
,dtime) a
group by dtime";

	//查询数据库
	$page = floor(intval($_REQUEST['start']) / intval($_REQUEST['limit'])) + 1;
	$pager = array( 'page'=>$page, 'size'=>$_REQUEST['limit'],'sort'=>$_REQUEST['sort'],'dir'=>$_REQUEST['dir'] );
	$rs = $db_salve->getdata($sqlstr, &$pager);
	}
	$rs[] = $rechargerow;
	
	$record_count = 1;
	exit( get_list_json($rs, $record_count ) );

}else{
	
	$page_nav = "统计分析 >> 用户推广统计";
	include_once("templates/report-member-retransret.html");
	
}

?>
