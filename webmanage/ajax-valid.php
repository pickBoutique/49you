<?php

/**
 * 前台校验
*/
define('PERMI_CODE','close');
include_once('init.inc.php');
/*------------------------------------------------------ */
//-- 获取校验列表
/*------------------------------------------------------ */
if ($act == 'list')
{
	
	
    /* 权限的判断 */
	
    //$filename = basename($_REQUEST["filename"]);
	$filename = str_replace(HTTP_ROOT,'',$_REQUEST['filename']);
	
    /* 查询校验列表 */
    $list = $db->getAll("SELECT * FROM " .DB_PREFIX."valid WHERE valid_pagecode='" . $filename . "'");
    die($json->encode($list));

}

/*------------------------------------------------------ */
//-- 唯一校验
/*------------------------------------------------------ */
elseif ($act == 'checkonlyone')
{
	$name = empty($_REQUEST['name']) ? '-1' : $_REQUEST['name'];
	//$filename = basename($_REQUEST["filename"]);
	$filename = str_replace(HTTP_ROOT,'',$_REQUEST['filename']);
	$value = empty($_REQUEST['value']) ? '-1' : $_REQUEST['value'];
	$row = $db->getRow("SELECT valid_tablename,valid_fieldname FROM " .DB_PREFIX."valid WHERE valid_pagecode='" . $filename . "' and valid_fieldname='" . $name . "'");
	
	if($row){
		
		$count = $db->getOne("SELECT count(*) as count FROM " . DB_PREFIX.$row['valid_tablename'] . " WHERE $row[valid_fieldname] ='" . $value . "' ");
		
		die($count);
	}
	die('0');
	
}

?>