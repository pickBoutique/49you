<?php
define('PERMI_CODE','cache_manage');
include_once('init.inc.php');

if($act == 'clean'){
	$runtime = time();
	if($_REQUEST["cache_date"]=="true"){
		$db->clear_cache();
		echo "<br>清除数据缓存 成功(秒)：".(time()-$runtime);
	}
		
	echo "<br>运行时间(秒)：".(time()-$runtime);
}else{
	
	$page_nav = "网站模块管理 >> 缓存管理";
	include_once("templates/cache-manage.html");
	
}

?>
