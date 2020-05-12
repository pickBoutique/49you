<?php
include_once('init.inc.php');
$url = str_replace('http://','',HTTP_ROOT);
$filename = '49you游戏平台49天之刃.url';
$filename = iconv('utf-8','gb2312',$filename);
$Shortcut = "
[InternetShortcut]

URL=".$url."
IDList=IconIndex=43
IconFile=/favicon.ico
HotKey=1626
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2";
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
exit( $Shortcut);
?>