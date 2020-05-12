<?php
set_time_limit(59);
define('PERMI_CODE','close');
define('CONN_SALVE',true);
include_once('init.inc.php');


include_once(WEB_ROOT.'/include/dnspod.class.php');
$dnspod = new DnspodApi('1134802336@qq.com','56uuadminjoy400');
$json = $dnspod->getDomainList();

print_r($json);
exit();
function HttpVisit($ip, $host, $url, $onlyheader=false)      
{      
    $errstr = '';      
    $errno = '';   
    $fp = @fsockopen ($ip, 80, $errno, $errstr, 30);   
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
  
  		if($onlyheader){
			$pos = strpos($response, "\r\n\r\n");
			$header_content = substr($response,0,$pos + 4);
			$response = explode("\r\n",$header_content);
		}else{
        	//去掉Header头信息   
        	$pos = strpos($response, "\r\n\r\n");   
        	$response = substr($response, $pos + 4);   
		}
        return $response;      
    }      
}   

$key = $_REQUEST['key'];
$server_ip = array(
		'adva'=>array('117.25.129.175','ygame.gy9y.com','/9v/6.html'),
		'advb'=>array('125.77.197.137','ygame.gy9y.com','/9v/6.html'),
		'advc'=>array('222.187.222.118','ygame.gy9y.com','/9v/6.html'),
		'advd'=>array('222.187.222.19','ygame.gy9y.com','/9v/6.html'),
		'adve'=>array('222.187.222.34','ygame.gy9y.com','/9v/6.html'),
		'advf'=>array('222.187.222.42','ygame.gy9y.com','/9v/6.html'),
		'advg'=>array('222.187.222.43','ygame.gy9y.com','/9v/6.html'),
		'advh'=>array('222.187.222.89','ygame.gy9y.com','/9v/6.html'),
		'advi'=>array('222.187.223.67','ygame.gy9y.com','/9v/6.html'),
		'advj'=>array('61.164.149.232','ygame.gy9y.com','/9v/6.html'),
		'advk'=>array('61.164.149.248','ygame.gy9y.com','/9v/6.html'),
		'advl'=>array('123.134.186.153','ygame.gy9y.com','/9v/6.html'),
		'advm'=>array('123.134.186.156','ygame.gy9y.com','/9v/6.html'),
		'advn'=>array('60.18.150.67','ygame.gy9y.com','/9v/6.html'),
		'advo'=>array('60.18.150.68','ygame.gy9y.com','/9v/6.html'),
		'advp'=>array('60.18.150.69','ygame.gy9y.com','/9v/6.html'),
		'advq'=>array('222.187.221.253','ygame.gy9y.com','/9v/6.html'),
		'weba'=>array('121.10.246.106','www.49you.com','/index.html'),
		'webb'=>array('121.10.246.107','www.49you.com','/index.html'),
		//'49you'=>'http://www.49you.com/',
		//'weba'=>'http://192.168.10.106/wodehoutaishishenmo/login.php',
		//'webb'=>'http://192.168.10.107/wodehoutaishishenmo/login.php'
	);
$need_sendmail=false;
$mail_content='';
foreach($server_ip as $k => $v){
	if(empty($key) || $key==$k){
		$start = time();
		$result = HttpVisit($v[0],$v[1],$v[2],true);
		$count = time()-$start;
		
		if(strpos($result[0],'200')===false){
		
			$time=date('d日H时i分');
			$need_sendmail=true;
			$mail_content.= '  '.$time.'_'.$v[0];
		}
		
		if(!empty($_GET['debug'])){
			print_r($result);
			echo($count);
		}
	}
}

if($need_sendmail){
	include_once(WEB_ROOT.'/include/email.class.php');
	$mail = new EMail();
	if($mail->sendMail('jy86070377@126.com',"check-server-http",$mail_content)){
		
	}
}

?>