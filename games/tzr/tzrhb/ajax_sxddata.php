<?php
require("include/json.class.php");
$act=$_REQUEST["act"];
$json = new JSON();
if($act=="getdata"){
	$sxdinfo = array(0=>array('name'=>'战神（男）','type'=>'防御性','fb'=>'刀','jg'=>'顺天斩、云龙斩、断魂斩、玄天斩
','sw'=>'铁杉经、易骨经、金刚经、灵愈经','fy'=>'雷霆一击、冲猛虎一击、霸者一击','zf'=>'神•战意、神•体之奥义','mb'=>'怒气神火刀中现，独闯龙潭破千魔。是团队中的防御型坦克，他的瞬间爆发力也是不可小觑的。','imgx'=>'x_zsn.jpg','imgd'=>'d_zsn.jpg')
,1=>array('name'=>'战神（女）','type'=>'防御性','fb'=>'刀','jg'=>'顺天斩、云龙斩、断魂斩、玄天斩
','sw'=>'铁杉经、易骨经、金刚经、灵愈经','fy'=>'雷霆一击、冲猛虎一击、霸者一击','zf'=>'神•战意、神•体之奥义','mb'=>'怒气神火刀中现，独闯龙潭破千魔。是团队中的防御型坦克，他的瞬间爆发力也是不可小觑的。','imgx'=>'x_zsl.jpg','imgd'=>'d_zsl.jpg')
,2=>array('name'=>'剑仙（男）','type'=>'闪爆型','fb'=>'剑','jg'=>'飞剑杀、无情杀、天剑杀、灭魔杀
','sw'=>'静心决、长生决、固体诀、专一诀','fy'=>'仙人漫步、凌波微步、白虹急步
','zf'=>'神•幻影、神•千剑流','mb'=>'仙风道骨云中仙，御剑伏魔为苍生。仙人飘飘，手持长剑。以强大的闪避和暴击让人闻风丧胆。
','imgx'=>'x_xjn.jpg','imgd'=>'d_xjn.jpg')
,3=>array('name'=>'剑仙（女）','type'=>'闪爆型','fb'=>'剑','jg'=>'飞剑杀、无情杀、天剑杀、灭魔杀
','sw'=>'静心决、长生决、固体诀、专一诀','fy'=>'仙人漫步、凌波微步、白虹急步
','zf'=>'神•幻影、神•千剑流','mb'=>'仙风道骨云中仙，御剑伏魔为苍生。仙人飘飘，手持长剑。以强大的闪避和暴击让人闻风丧胆。
','imgx'=>'x_xjl.jpg','imgd'=>'d_xjl.jpg')

		);
	$str = "{sxdinfo:" . $json->encode($sxdinfo) . "}";
	exit($str);
	
}
?>