<?php

session_start();
header("content-type:text/html;charset=utf-8");
require('../config.inc.php');

require(WEB_ROOT.'/include/mysqldb.inc.php');
$db = new mysqldb();

require(WEB_ROOT.'/include/function_common.inc.php');
$config = read_static_cache('web_config');
$config_index = read_static_cache('index_config');


require(WEB_ROOT.'/games/game.inc.php');

$act = $_REQUEST['act'];
$game_id=10;
//新闻分类ID设置

$ids=array();
$ids['topindex']=228;//官网分类顶级ID
$ids['gamenews']=229;//游戏资讯
$ids['gn_rd']=230;//热点
$ids['gn_xw']=231;//新闻
$ids['gn_hd']=232;//活动
$ids['gamedata']=233;//游戏资料
$ids['gd_xsjn']=234;//新手指南
$ids['gd_xtjs']=235;//系统介绍
$ids['gd_tswf']=236;//特色玩法

$ids['gamegl']=237;//游戏攻略
$ids['gamecjwt']=238;//常见问题
$ids['gamerchd']=239;//日常活动
$ids['gamemt']=240;//合作媒体
$ids['gamefla']=241;//首页开服Flash

$ids['bbs']=62;//BBS的fid

$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
?>