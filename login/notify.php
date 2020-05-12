<?php

/**
 * 所有在线支付的网关通知接口
 */
 include_once(dirname(__FILE__) . '/../init_web.php');



function save_log(){
	$filename   =   WEB_ROOT . '/uploadfiles/login_respond.log'; 

	
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
	
			fclose($handle); 
	
	//}   else   { 
			//print   "文件   $filename   不可写 "; 
	//} 

}



save_log();
//登陆接口通知处理
if(!empty($_REQUEST['code'])){
	save_log();
	$code = $_REQUEST['code'];
	unset($_REQUEST['code']);
	unset($_GET['code']);
	unset($_POST['code']);
	//TODO:判断该登陆接口是否已启用
	if(file_exists(WEB_ROOT . "/login/$code.php")){
		$set_modules = true;
		require_once(WEB_ROOT . "/login/$code.php");
		$login_type = new $code();
		
		$name = $login_type->respond();
		if($name !== false ){
			//登陆成功
			$obj_user->login($name,'',true);
			redir( HTTP_ROOT . '/' ,true);
			
		}else{
			echo('本次登陆失败，请及时和我们取得联系。');
		}
	}
}

?>