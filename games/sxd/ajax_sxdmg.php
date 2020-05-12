<?php
//include_once('init_member.inc.php');
require("include/json.class.php");
$act=$_REQUEST["act"];
$json = new JSON();

if($act=="getmg"){
	//命格	品质	等级	效果
	$mginfo = array(0=>array("name"=>"飞仙","quality"=>"黄色","effect"=>"LV.1 增加气势25","group"=>"y","proportion"=>"1","money"=>27000)
,1=>array("name"=>"万寿无疆","quality"=>"黄色","effect"=>"LV.1 增加生命值2000","group"=>"y","proportion"=>"1","money"=>27000)
,2=>array("name"=>"霸者横栏","quality"=>"紫色","effect"=>"LV.1 增加暴击  3%","group"=>"f","proportion"=>"1","money"=>20250)
,3=>array("name"=>"千惊万喜","quality"=>"紫色","effect"=>"LV.1 增加闪避  2%","group"=>"f","proportion"=>"1","money"=>20250)
,4=>array("name"=>"一元复始","quality"=>"紫色","effect"=>"LV.1 增加命中  3%","group"=>"f","proportion"=>"1","money"=>20250)
,5=>array("name"=>"不死鸟","quality"=>"紫色","effect"=>"LV.1 增加生命值1000","group"=>"f","proportion"=>"1","money"=>20250)
,6=>array("name"=>"轩辕剑体","quality"=>"紫色","effect"=>"LV.1 增加绝技攻击力300","group"=>"f","proportion"=>"1","money"=>20250)
,7=>array("name"=>"逢龙遇虎","quality"=>"紫色","effect"=>"LV.1 增加普通攻击力300","group"=>"f","proportion"=>"1","money"=>20250)
,8=>array("name"=>"叱咤风云","quality"=>"紫色","effect"=>"LV.1 增加气势15","group"=>"f","proportion"=>"1","money"=>20250)
,9=>array("name"=>"快刀乱麻","quality"=>"紫色","effect"=>"LV.1 降低对方格挡4%","group"=>"f","proportion"=>"1","money"=>20250)
,10=>array("name"=>"天地无用","quality"=>"紫色","effect"=>"LV.1 增加格挡3%","group"=>"f","proportion"=>"1","money"=>20250)
,11=>array("name"=>"大破坏神","quality"=>"紫色","effect"=>"LV.1 增加法术攻击力300","group"=>"f","proportion"=>"1","money"=>20250)
,12=>array("name"=>"热血","quality"=>"蓝色","effect"=>"LV.1 增加气势 5","group"=>"b","proportion"=>"1","money"=>13500)
,13=>array("name"=>"夜叉","quality"=>"蓝色","effect"=>"LV.1 增加法术攻击力200","group"=>"b"	,"proportion"=>"1","money"=>13500)
,14=>array("name"=>"无惧","quality"=>"蓝色","effect"=>"LV.1 增加格挡2%","group"=>"b","proportion"=>"1","money"=>13500)
,15=>array("name"=>"破军","quality"=>"蓝色","effect"=>"LV.1 增加绝技攻击力200","group"=>"b"	,"proportion"=>"1","money"=>13500)
,16=>array("name"=>"贪狼","quality"=>"蓝色","effect"=>"LV.1 增加普通攻击力200","group"=>"b"	,"proportion"=>"1","money"=>13500)
,17=>array("name"=>"信牢","quality"=>"蓝色","effect"=>"LV.1 增加命中2%","group"=>"b","proportion"=>"1","money"=>13500)
,18=>array("name"=>"吉星","quality"=>"蓝色","effect"=>"LV.1 增加闪避1%","group"=>"b","proportion"=>"1","money"=>13500)
,19=>array("name"=>"散华","quality"=>"蓝色","effect"=>"LV.1 增加法术防御175","group"=>"b","proportion"=>"1","money"=>13500)
,20=>array("name"=>"虎贲","quality"=>"蓝色","effect"=>"LV.1 增加绝技防御175","group"=>"b","proportion"=>"1","money"=>13500)
,21=>array("name"=>"岩打","quality"=>"蓝色","effect"=>"LV.1 增加普通防御100","group"=>"b","proportion"=>"1","money"=>13500)
,22=>array("name"=>"斩铁","quality"=>"蓝色","effect"=>"LV.1 增加暴击2%","group"=>"b","proportion"=>"1","money"=>6750)
,23=>array("name"=>"兽腾","quality"=>"绿色","effect"=>"LV.1 增加普通攻击力100","group"=>"g","proportion"=>"1","money"=>6750)
,24=>array("name"=>"辉煌","quality"=>"绿色","effect"=>"LV.1 增加法术攻击力100","group"=>"g","proportion"=>"1","money"=>6750)
,25=>array("name"=>"战狂","quality"=>"绿色","effect"=>"LV.1 增加绝技攻击力100","group"=>"g","proportion"=>"1","money"=>6750)
,26=>array("name"=>"太初","quality"=>"红色","effect"=>"LV.1 增加1200点经验","group"=>"r","proportion"=>"1","money"=>27000)
,27=>array("name"=>"十衰九败","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,28=>array("name"=>"血光灾厄","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,29=>array("name"=>"衰神附体","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,30=>array("name"=>"死亡连锁","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,31=>array("name"=>"不死凶命","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,32=>array("name"=>"逢赌必输","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,33=>array("name"=>"穷鬼缠身","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,34=>array("name"=>"千年未竟","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,35=>array("name"=>"厄命","quality"=>"灰色","effect"=>"无","group"=>"d","proportion"=>"1","money"=>5400)
,36=>array("name"=>"百步穿杨","quality"=>"黄色","effect"=>"LV.1 增加命中6%","group"=>"y","proportion"=>"1","money"=>27000)
,37=>array("name"=>"斗转星移","quality"=>"黄色","effect"=>"LV.1 降低被爆击几率6%","group"=>"y","proportion"=>"1","money"=>27000)
,38=>array("name"=>"破碎虚空","quality"=>"黄色","effect"=>"LV.1 降低被格挡几率6%","group"=>"y","proportion"=>"1","money"=>27000)
,39=>array("name"=>"诛仙","quality"=>"黄色","effect"=>"LV.1 增加爆击伤害6%","group"=>"y","proportion"=>"1","money"=>27000)

	);
/*	
人物资料(铜)
序	名称	下一级人物相遇%	铜钱数	产生命格组别	组别的百分比
1	周一仙	50	8000	d,g	60,40
2	小仙女	40	10000	d,g,b	40,40,20
3	左慈	35	20000	d,g,b	30,40,30
4	张道陵	30	40000	g,b,f	40,40,20
5	姜子牙		60000	g,b,f,y	30,30,20,20
*/
	//铜钱
	$meetdata_tc = array(0=>array("name"=>"周一仙","percen_meet"=>50,"money"=>8000,"group_mg"=>"d,g","percen_mg"=>"60,40")
					,1=>array("name"=>"小仙女","percen_meet"=>40,"money"=>10000,"group_mg"=>"d,g,b","percen_mg"=>"40,40,20")
					,2=>array("name"=>"左慈","percen_meet"=>20,"money"=>20000,"group_mg"=>"d,g,b","percen_mg"=>"30,40,30")
					,3=>array("name"=>"张道陵","percen_meet"=>30,"money"=>40000,"group_mg"=>"g,b,f","percen_mg"=>"40,40,20")
					,4=>array("name"=>"姜子牙","percen_meet"=>0,"money"=>60000,"group_mg"=>"g,b,f,y","percen_mg"=>"30,30,20,20")
	);

/*
人物资料(宝)
序	名称	下一级人物相遇%	元宝	产生命格组别	组别的百分比
1	张道陵	40	200	f,r	40,60
2	姜子牙			f,y,r	30,30,40
*/

	//元宝
	$meetdata_yb = array(3=>array("name"=>"张道陵","percen_meet"=>50,"money"=>200,"group_mg"=>"r,f","percen_mg"=>"75,25")
					,4=>array("name"=>"姜子牙","percen_meet"=>0,"money"=>0,"group_mg"=>"r,f,y","percen_mg"=>"55,25,20")
	);
/*
人品计算
d=0,g=500,b=2000,f=5000,y=20000,r=1000

分数/元宝=RP值
5W铜币=50元宝
1K=1元宝
*/
	$rp_config= array("d"=>0,"g"=>0,"b"=>0,"f"=>15000,"y"=>120000,"r"=>0,"tctoyb"=>1000);
	$str = "{mginfo:" . $json->encode($mginfo) . ",meetdata_tc:" . $json->encode($meetdata_tc) . ",meetdata_yb:" . $json->encode($meetdata_yb). ",rp_config:" . $json->encode($rp_config) . "}";
	exit($str);
	
}
?>

