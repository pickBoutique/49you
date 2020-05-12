<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','website_config');	
include_once('init.inc.php');

if(file_exists(WEB_ROOT."/cfg_file/cfg_webset.php"))
{
	require(WEB_ROOT.'/cfg_file/cfg_webset.php');
}
//修改操作
if('edit' == $_POST['act'])
{
	
	$save_config = array();
	if(!empty($cfg_webset)){
        foreach($cfg_webset as $k => $val){ 
			if(isset($_REQUEST[$k])){
				$save_config[$k] = $_REQUEST[$k];
				$db->query(" replace into ".DB_PREFIX."config (config_name,config_value) values('$k','$_REQUEST[$k]') ");
			}
		}
	}
	
	write_static_cache('web_config',$save_config);
   
	showMessage("设置成功","web-set.php");
	
	
}
//页面导航
$page_nav = "网站信息管理 >> 网站信息设置";

include_once("templates/web-set.html");
?>
