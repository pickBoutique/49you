<?php

session_start();
header("content-type:text/html;charset=utf-8");
require('../../config.inc.php');

require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();

require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');
$config_index = read_static_cache('index_config');


require(WEB_ROOT.'/games/game.inc.php');

$act = $_REQUEST['act'];
$game_id=16;
//新闻分类ID设置
$ids=array();
$ids['gamegl']=308;//游戏攻略

?>