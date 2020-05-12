<?php
include_once('init.inc.php');
//今日开放 时间在今日内的
$act=$_REQUEST["act"];
if(empty($act)){
	$cdate=$_REQUEST["date"];
	$year=intval(substr($cdate,0,4));//取得年份
	$month=intval((int)substr($cdate,5,2));//取得月份
	$day=intval((int)substr($cdate,8,2));//取得几号
	
	$cdate= mktime(0,0,0,$month,$day,$year);
	if(empty($cdate)){
		$cdate=date("Y-m-d");
		$datetitle=date("Y年m月d日");
	}else{
		$datetitle=date("Y年m月d日",$cdate);
		$cdate=date("Y-m-d",$cdate);
	}
	$sqlstr="select * from ".DB_PREFIX."weblink a left join ".DB_PREFIX."kfbgame b on a.wl_gid=b.fg_id where wl_startdate>= unix_timestamp('{$cdate}') and wl_startdate < unix_timestamp(date_add('{$cdate}',INTERVAL 1 DAY)) order by wl_sort desc,wl_startdate desc";
	$rs_today = $db->getAll($sqlstr);
	
	
}else if($act=="slist"){
	$gid=intval($_REQUEST["gid"]);
		$sqlstr="select * from ".DB_PREFIX."weblink a left join ".DB_PREFIX."kfbgame b on a.wl_gid=b.fg_id where fg_id ={$gid} order by wl_sort desc,wl_startdate desc";
	$rs_today = $db->getAll($sqlstr);
}
include_once('templates/server.html');
?>