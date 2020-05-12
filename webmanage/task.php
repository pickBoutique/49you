<?php
set_time_limit(59);
define('PERMI_CODE','close');
include_once('init.inc.php');

/*
for($i=0;$i<10000;$i++){
	file_put_contents(WEB_ROOT.'/uploadfiles/pp.log','(1,1,1,1,1,1)',FILE_USE_INCLUDE_PATH | FILE_APPEND);
	

	$row = array();
	$row['iplog_ip'] = '123.123.123.123';
	$row['iplog_adv'] = $i;
	$row['iplog_advtype'] = $i;
	$row['iplog_mat'] = $i;
	$row['iplog_subtype'] = $i;
	$db->insert($row,DB_PREFIX."iplog");
	
}
*/
function update_next($v){
	global $db;
	$last_time = time();
	if($v['task_last']>0){
		//$last_time = $v['task_last'];
	}
	
	$type = '';
	$format = '';
	if($v['task_type']==0){
		$type = 'days';
		$format = $v['task_hours'].':'.$v['task_minutes'].':00';
	}
	if($v['task_type']==1){
		$type = 'hours';
		$format = 'H:'.$v['task_minutes'].':00';
	}
	if($v['task_type']==2){
		$type = 'minutes';
		$format = 'H:i:00';
	}
	
	$t = strtotime('+'.$v['task_num'].' '.$type,$last_time);
	$t = strtotime(date('Y-m-d '.$format,$t));
	
	$row = array();
	$row['task_id'] = $v['task_id'];
	$row['task_next'] = $t;
	
	$db->update($row,DB_PREFIX.'task');
	return $t;
}



$curr = time();
$list = $db->getAll("select * from ".DB_PREFIX."task where ( $curr >= task_next or task_next=0 ) and task_enable=1 ");
//echo("select * from ".DB_PREFIX."task where task_next >= $curr or task_next=0 ");
if(!empty($list)){
	foreach($list as $k => $v){
		if($v['task_next'] == 0){
			$t = update_next($v);
			$list[$k]['task_next'] = $t;
		}
	}
	
	foreach($list as $k => $v){
		if( $curr >= $v['task_next'] ){
			$start = time();
			$error = '';
			try{
				$error = @file_get_contents($v['task_url']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
			$runtime = time()-$start;
			
			$arr = explode(',',$v['task_runtime']);
			$arr[] = $runtime;
			if(sizeof($arr)>10){
				array_shift($arr);
			}
			$str_runtime = implode(',',$arr);
			$v['task_runtime'] = $str_runtime;
			$v['task_last'] = time();
			$v['task_count'] = $v['task_count'] + 1;
			$v['task_error'] = $error;
			
			$row = array();
			$row['task_id'] = $v['task_id'];
			$row['task_runtime'] = $v['task_runtime'];
			$row['task_last'] = $v['task_last'];
			$row['task_count'] = $v['task_count'];
			$row['task_error'] = $v['task_error'];
			$db->update($row,DB_PREFIX.'task');
			
			update_next($v);
		}
	}
}



?>