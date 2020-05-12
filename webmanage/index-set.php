<?php
define('PERMI_CODE','index_config');	
include_once('init.inc.php');
$config = read_static_cache('index_config');
if(file_exists(WEB_ROOT."/cfg_file/cfg_indexset.php"))
{
	require(WEB_ROOT.'/cfg_file/cfg_indexset.php');
}

//修改操作
if('edit' == $_POST['act'])
{
	
	$save_config = array();
	if(!empty($cfg_indexset)){
        foreach($cfg_indexset as $k => $val){
			if(isset($_REQUEST[$k])){
				$save_config[$k] = $_REQUEST[$k];
				//$db->query(" replace into ".DB_PREFIX."config (config_name,config_value) values('$k','$_REQUEST[$k]') ");
			}
		}
	}
	
	write_static_cache('index_config',$save_config);
   
	showMessage("设置成功","index-set.php");
	
	
}
//页面导航
$page_nav = "网站信息管理 >> 平台首页设置";

include_once("templates/index-set.html");
?>
