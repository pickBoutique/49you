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
$game_id=37;
//新闻分类ID设置

$ids=array();
$ids['topindex']=327;//官网分类顶级ID
$ids['gamenews']=300;//游戏资讯
$ids['gn_rd']=301;//热点
$ids['gn_xw']=338;//新闻
$ids['gn_xwpic']=329;//新闻图片
$ids['gn_hd']=330;//活动
$ids['gn_hdpic']=339;//活动图片
$ids['gamedata']=331;//游戏资料
$ids['gd_xszn']=332;//新手指南
$ids['gd_xtjs']=333;//系统介绍
$ids['gd_tswf']=334;//特色玩法

$ids['gamegl']=335;//游戏攻略
$ids['gamecjwt']=309;//常见问题
$ids['gamerchd']=336;//玩家心情
$ids['gamemt']=337;//合作媒体
$ids['gamefla']=328;//首页开服Flash

$ids['bbs']=102;//BBS的fid

$is_ajax = !empty($_REQUEST['is_ajax']) ? true : false;
?>