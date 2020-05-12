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
$game_id=14;
//新闻分类ID设置

$ids=array();
$ids['topindex']=284;//官网分类顶级ID
$ids['gamenews']=285;//游戏资讯
$ids['gn_rd']=286;//热点
$ids['gn_xw']=287;//新闻
$ids['gn_hd']=288;//活动
$ids['gamedata']=289;//游戏资料
$ids['gd_xsjn']=290;//新手指南
$ids['gd_xtjs']=291;//系统介绍
$ids['gd_tswf']=292;//特色玩法

$ids['gamegl']=293;//游戏攻略
$ids['gamecjwt']=294;//常见问题
$ids['gamerchd']=298;//日常活动
$ids['gamemt']=296;//合作媒体
$ids['gamefla']=297;//首页开服Flash

$ids['bbs']=96;//BBS的fid

$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
?>