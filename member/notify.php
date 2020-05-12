<?php

include_once('init_member.inc.php');




function save_log(){
	$filename   =   WEB_ROOT . '/uploadfiles/respond.log'; 

	
	//   首先我们要确定文件存在并且可写。 
	//if   (is_writable($filename))   { 
			$querystring = "\n" . http_build_query($_REQUEST);
			//   在这个例子里，我们将使用添加模式打开$filename， 
			//   因此，文件指针将会在文件的开头， 
			//   那就是当我们使用fwrite()的时候，$somecontent将要写入的地方。 
			if   (!$handle   =   fopen($filename,   'a'))   { 
					  //print   "不能打开文件   $filename "; 
					  exit; 
			} 
	
			//   将$somecontent写入到我们打开的文件中。 
			if   (!fwrite($handle,   $querystring))   { 
					//print   "不能写入到文件   $filename "; 
					exit; 
			} 
	
			//print   "成功地将   $somecontent   写入到文件$filename "; 
			fwrite($handle,   "\r\n");
			fclose($handle); 
	
	//}   else   { 
			//print   "文件   $filename   不可写 "; 
	//} 

}



save_log();
$code=$_REQUEST['code'];
if(empty($_REQUEST['code'])){
	$code=$_REQUEST['ext1'];
}

//支付网关 支付通知处理
if(!empty($code)){
	save_log();
	//TODO:判断该支付方式是否已启用
	if(file_exists(WEB_ROOT . "/pay/$code.php")){
		$set_modules = true;
		require_once(WEB_ROOT . "/pay/$code.php");
		$pay_type = new $code();
		$retstatus=$pay_type->respond();
		if($_REQUEST['retstatus']=='1'){
			if($retstatus){
				exit('1');
			}else{
				exit('0');
			}
		}else{
			if($retstatus){
				show_error('支付成功','本次支付成功，感谢您的支持！','member.html');
				//redir( HTTP_ROOT . '/member.html' ,true);
				
				
				//echo('您此次的支付操作已成功！');
			}else{
				echo('本次支付失败，请及时和我们取得联系。');
			}
		}
	}
}

?>