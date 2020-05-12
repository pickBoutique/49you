<?php
//所有官网使用公共新闻文章底部
define('PUBLIC_INFOTAG',true);
//不使用公共新闻文章底部（填写所属官网的游戏ID）
$game_infotag=array();
//官网链接
$cfg_oflink= array(
	'xk' => array('link'=>'http://xk.49you.com','name'=>'侠客世界','ptname'=>''),
	'long' => array('link'=>'http://long.49you.com','name'=>'龙将','ptname'=>''),
	'sxd' => array('link'=>'http://sxd.49you.com','name'=>'神仙道','ptname'=>''),
	'yqdx' => array('link'=>'http://yqdx.49you.com','name'=>'一骑当先','ptname'=>''),
	'xz' => array('link'=>'http://xz.49you.com','name'=>'凡人修真2','ptname'=>''),
	'jhl' => array('link'=>'http://jhl.49you.com','name'=>'江湖令','ptname'=>''),
	'yx' => array('link'=>'http://yx.49you.com','name'=>'英雄王座','ptname'=>''),
	'xy' => array('link'=>'http://xy.49you.com','name'=>'飞音西游','ptname'=>''),
	'49you' => array('link'=>'http://www.49you.com','name'=>'49you网页游戏平台','ptname'=>'49you网页游戏平台')
	);
	
//#####################################
function get_newslink($id){
	return "news_{$id}.html";
}
?>