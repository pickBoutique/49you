<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('init.inc.php');

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function HttpVisit($ip, $host, $url)      
{      
    $errstr = '';      
    $errno = '';   
    $fp = @fsockopen ($ip, 80, $errno, $errstr, 5);   
    if (!$fp)      
    {      
         return false;      
    }      
    else     
    {      
        $out = "GET {$url} HTTP/1.1\r\n";   
        $out .= "Host:{$host}\r\n";      
        $out .= "Connection: close\r\n\r\n";   
        fputs ($fp, $out);      
  
        while($line = fread($fp, 4096)){   
           $response .= $line;   
        }   
        fclose( $fp );   
  
 		$ret = array();
			$pos = strpos($response, "\r\n\r\n");
			$header_content = substr($response,0,$pos + 4);
		$ret['header'] = explode("\r\n",$header_content);
		   
        $ret['body'] = substr($response, $pos + 4);
		
        return $ret;      
    }      
}   

$key = $_REQUEST['key'];


$ip = $_REQUEST['ip'];
$domain = $_REQUEST['domain'];
if(empty($ip) || empty($domain)){
	exit();
}
$advlist = $db_admin_salve->getAll("select a.adv_id,b.advtype_code from ".DB_PREFIX."adv a left join ".DB_PREFIX."advtype b on a.adv_type=b.advtype_id order by adv_sort desc limit 0,40");
$mail_content = '';
$need_sendmail=false;
$insert_sql = "insert into `online_adv_status`(advstatus_addtime,advstatus_advid,advstatus_domain,advstatus_ip,advstatus_url,advstatus_time,advstatus_status,advstatus_md5,advstatus_match) values";
$spt = '';
foreach($advlist as $key => $val){
	
	
	$url = '/'.$val['advtype_code'].'/'.$val['adv_id'].'.html';
	$src_content = HttpVisit('121.10.246.107','web.49you.com',$url);
	$src_md5 = md5($src_content['body']);
	
		
		$start = microtime_float();
		$dst_content = HttpVisit($ip,$domain,$url);
		$end = intval((microtime_float()-$start) * 1000);
		$dst_md5 = '';
		$status = '0';
		if(!empty($dst_content)){
			$dst_md5 = md5($dst_content['body']);
			if(!empty($dst_content['header'][0])){
				$arr = explode(' ',$dst_content['header'][0]);
				$status = $arr[1];
			}
		}
		$is_match = $src_md5==$dst_md5 ? '1' : '0';
		$insert_sql .= $spt . "('".time()."','".$val['adv_id']."','".$domain."','".$ip."','".$url."','".$end."','".$status."','".$dst_md5."','".$is_match."')";
		$spt = ',';
		
	
}

$db_admin->query($insert_sql);

if($need_sendmail){
	include_once(WEB_ROOT.'/include/email.class.php');
	$mail = new EMail();
	if($mail->sendMail('jy86070377@126.com',"check-server-http",$mail_content)){
		
	}
}

?>