<?php
include_once('init_member.inc.php');
//自动补单程序， 未完成
$start = strtotime("-800 seconds",time());
$end = strtotime("-680 seconds",time());

$where = " AND trans_instatus='0' AND trans_addtime<{$end} AND trans_addtime>{$start} ";
$sql = "SELECT * FROM ".DB_PREFIX."trans WHERE 1  limit 0,20";
$rs = $db->getAll($sql);
if($rs)
{
	foreach($rs as $v)
	{
		$code = $v['trans_type'];
		include_once(WEB_ROOT."/pay/{$code}.php");
		$pay = new $code();
		//$ret = $pay->query($v['trans_code']);
		//if($ret['ret_code']=='1' && $ret['status']=='1'){
			echo($v['trans_code'].' ' .$code);
			//order_paid($v['trans_code'], $code,'');
			
		//}
		
	}
}

?>