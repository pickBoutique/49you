<?php
/*
*	creater mwz 2011-01-17
*	用于ajax回调获取中文拼音单词
*/

include_once('init_web.inc.php');
ob_start();

$str = $_REQUEST['str'];
if(!empty($str)){
	echo( get_pin_yin(iconv('utf-8','gb2312',$str)) );
}
?>