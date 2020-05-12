<?php
include_once('init.inc.php');
//今日开放 时间在今日内的
$sqlstr="select * from ".DB_PREFIX."weblink a left join ".DB_PREFIX."kfbgame b on a.wl_gid=b.fg_id where wl_status=1 and wl_startdate>= unix_timestamp(curdate()) and wl_startdate < unix_timestamp(date_add(curdate(),INTERVAL 1 DAY)) order by wl_sort desc,wl_startdate desc";
$rs_today = $db->getAll($sqlstr);

//即将开放 时间大等明日
$sqlstr="select * from ".DB_PREFIX."weblink a left join ".DB_PREFIX."kfbgame b on a.wl_gid=b.fg_id where wl_status=1 and wl_startdate>=unix_timestamp(date_add(curdate(),INTERVAL 1 DAY)) order by wl_sort desc,wl_startdate desc";
$rs_fast = $db->getAll($sqlstr);

//已经开放 时间小于今日
$sqlstr="select * from ".DB_PREFIX."weblink a left join ".DB_PREFIX."kfbgame b on a.wl_gid=b.fg_id where wl_status=1 and wl_startdate < unix_timestamp(curdate()) order by wl_sort desc,wl_startdate desc";
$rs_opener = $db->getAll($sqlstr);
include_once('templates/index.html');
?>