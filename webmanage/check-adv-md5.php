<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('init.inc.php');


$server_ip = array(
		'adva'=>'adva.game.49you.com',
		'advb'=>'advb.game.49you.com',
		'advc'=>'advc.game.49you.com',
		'advd'=>'advd.game.49you.com',
		'advf'=>'advf.game.49you.com',
		'advg'=>'advg.game.49you.com',
		'advh'=>'advh.game.49you.com',
		'advi'=>'advi.game.49you.com'
	);
$advlist = $db_salve->getAll("select a.adv_id,b.advtype_code from ".DB_PREFIX."adv a left join ".DB_PREFIX."advtype b on a.adv_type=b.advtype_id order by adv_sort desc limit 0,20");
$mail_content = '';
$need_sendmail=false;
foreach($advlist as $key => $val){
	
	$src_content = file_get_contents('http://web.49you.com/'.$val['advtype_code'].'/'.$val['adv_id'].'.html');
	$src_md5 = md5($src_content);
	
	foreach($server_ip as $k => $v){
		$dst_content = file_get_contents('http://'.$v.'/'.$val['advtype_code'].'/'.$val['adv_id'].'.html');
		$dst_md5 = md5($dst_content);
		$time=date('YmdHis');
		if($src_md5 != $dst_md5 && !empty($dst_content) ){
			$need_sendmail=true;
			
			$mail_content.='<br /><div style=\'color:red;\'>'.'/uploadfiles/'.$time.'_'.$k.'_'.$val['advtype_code'].'_'.$val['adv_id'].'.txt</div>';
			file_put_contents(WEB_ROOT.'/uploadfiles/'.$time.'_'.$k.'_'.$val['advtype_code'].'_'.$val['adv_id'].'.txt',$dst_content,FILE_USE_INCLUDE_PATH | FILE_APPEND);
		}else{
			//$mail_content.='<br /><div style=\'color:green;\'>'.'/uploadfiles/'.$time.'_'.$k.'_'.$val['advtype_code'].'_'.$val['adv_id'].'.txt</div>';
		}
		
	}
}

if($need_sendmail){
	include_once(WEB_ROOT.'/include/email.class.php');
	$mail = new EMail();
	if($mail->sendMail('jy86070377@126.com',"check-adv-md5",$mail_content)){
		
	}
}
if(!empty($_GET['debug'])){
	exit($mail_content);
}
?>