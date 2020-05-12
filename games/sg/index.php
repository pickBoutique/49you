<?php
include_once('init.inc.php');
if(empty($act)){
$info_gid=$game_id;
//游戏资讯 180=>热点181=>公告活动182=>BOSS坐标
/*
179	0	0	盛世三国
180	0	179	新闻公告
181	0	179	活动资讯
182	0	179	boss坐标
183	0	179	游戏资料
184	0	183	新手上路
185	0	183	人物角色
186	0	183	特色系统
187	0	183	战争系统
188	0	183	高手进阶
189	189	179	游戏攻略
190	0	179	常见问题
191	0	179	首页幻灯片
192	0	179	合作媒体
193	0	179	成长指引
194	0	193	新手入门
195	0	193	小试牛刀
196	0	193	初露锋芒
197	0	193	略有所成
*/


$sqlstr="select * from (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 180 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7) a
		union all (select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 182 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7)";
$rs_news = $db->getAll($sqlstr);

//攻略
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 189 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamegs = $db->getAll($sqlstr);

//常见问题
$sqlstr="select cate_id,info_id,subtitle,add_time,attachment from ".DB_PREFIX."info where info_start<=".time()." and cate_id = 190 and info_gid={$info_gid} order by sort_num desc,add_time desc limit 0,7";
$rs_gamequ = $db->getAll($sqlstr);


include_once('templates/index.html');
}
?>