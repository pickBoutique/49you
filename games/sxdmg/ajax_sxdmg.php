<?php
//include_once('init_member.inc.php');
require("include/json.class.php");
$act=$_REQUEST["act"];
$json = new JSON();
if($act=="getdate"){
	//命格	品质	等级	效果
	$mginfo = array(0=>array("name"=>"真·逢龙遇虎","quality"=>"黄色","effect"=>"增加普通攻击500","pic"=>"31.jpg"),1=>array("name"=>"真·大破坏神","quality"=>"黄色","effect"=>"增加法术攻击500","pic"=>"32.jpg"),2=>array("name"=>"真·轩辕剑体","quality"=>"黄色","effect"=>"增加绝技攻击500","pic"=>"33.jpg"),3=>array("name"=>"飞仙","quality"=>"黄色","effect"=>"增加气势25","pic"=>"0.jpg"),4=>array("name"=>"万寿无疆","quality"=>"黄色","effect"=>"增加生命值2000","pic"=>"1.jpg"),5=>array("name"=>"百步穿杨","quality"=>"黄色","effect"=>"增加命中6%","pic"=>"27.jpg"),6=>array("name"=>"斗转星移","quality"=>"黄色","effect"=>"降低被爆击几率6%","pic"=>"28.jpg"),7=>array("name"=>"破碎虚空","quality"=>"黄色","effect"=>"降低被格挡几率6%","pic"=>"29.jpg"),8=>array("name"=>"诛仙","quality"=>"黄色","effect"=>"增加爆击伤害6%","pic"=>"30.jpg"),9=>array("name"=>"霸者横栏","quality"=>"紫色","effect"=>"增加暴击 3%","pic"=>"2.jpg"),10=>array("name"=>"千惊万喜","quality"=>"紫色","effect"=>"增加闪避 2%","pic"=>"3.jpg"),11=>array("name"=>"一元复始","quality"=>"紫色","effect"=>"增加命中 3%","pic"=>"4.jpg"),12=>array("name"=>"不死鸟","quality"=>"紫色","effect"=>"增加生命值1000","pic"=>"5.jpg"),13=>array("name"=>"轩辕剑体","quality"=>"紫色","effect"=>"增加绝技攻击力300","pic"=>"6.jpg"),14=>array("name"=>"逢龙遇虎","quality"=>"紫色","effect"=>"增加普通攻击力300","pic"=>"7.jpg"),15=>array("name"=>"叱咤风云","quality"=>"紫色","effect"=>"增加气势15","pic"=>"8.jpg"),16=>array("name"=>"快刀乱麻","quality"=>"紫色","effect"=>"降低对方格挡4%","pic"=>"9.jpg"),17=>array("name"=>"天地无用","quality"=>"紫色","effect"=>"增加格挡3%","pic"=>"10.jpg"),18=>array("name"=>"大破坏神","quality"=>"紫色","effect"=>"增加法术攻击力300","pic"=>"11.jpg"),19=>array("name"=>"热血","quality"=>"蓝色","effect"=>"增加气势 5","pic"=>"12.jpg"),20=>array("name"=>"夜叉","quality"=>"蓝色","effect"=>"增加法术攻击力200","pic"=>"13.jpg"),21=>array("name"=>"无惧","quality"=>"蓝色","effect"=>"增加格挡2%","pic"=>"14.jpg"),22=>array("name"=>"破军","quality"=>"蓝色","effect"=>"增加绝技攻击力200","pic"=>"15.jpg"),23=>array("name"=>"贪狼","quality"=>"蓝色","effect"=>"增加普通攻击力200","pic"=>"16.jpg"),24=>array("name"=>"信牢","quality"=>"蓝色","effect"=>"增加命中2%","pic"=>"17.jpg"),25=>array("name"=>"吉星","quality"=>"蓝色","effect"=>"增加闪避1%","pic"=>"18.jpg"),26=>array("name"=>"散华","quality"=>"蓝色","effect"=>"增加法术防御175","pic"=>"19.jpg"),27=>array("name"=>"虎贲","quality"=>"蓝色","effect"=>"增加绝技防御175","pic"=>"20.jpg"),28=>array("name"=>"岩打","quality"=>"蓝色","effect"=>"增加普通防御100","pic"=>"21.jpg"),29=>array("name"=>"斩铁","quality"=>"蓝色","effect"=>"增加暴击2%","pic"=>"22.jpg"),30=>array("name"=>"兽腾","quality"=>"绿色","effect"=>"增加普通攻击力100","pic"=>"23.jpg"),31=>array("name"=>"辉煌","quality"=>"绿色","effect"=>"增加法术攻击力100","pic"=>"24.jpg"),32=>array("name"=>"战狂","quality"=>"绿色","effect"=>"增加绝技攻击力100","pic"=>"25.jpg"),33=>array("name"=>"太初","quality"=>"红色","effect"=>"增加命格经验","pic"=>"26.jpg")


	);
	
	$mgdata = array(0=>array(	1=>array("level"=>"LV.1","content"=>"增加普通攻击500")
	,2=>array("level"=>"LV.2","content"=>"增加普通攻击1000")
	,3=>array("level"=>"LV.3","content"=>"增加普通攻击1500")
	,4=>array("level"=>"LV.4","content"=>"增加普通攻击2000")
	,5=>array("level"=>"LV.5","content"=>"增加普通攻击2500")
	,6=>array("level"=>"LV.6","content"=>"增加普通攻击3000")
	,7=>array("level"=>"LV.7","content"=>"增加普通攻击3500")
	,8=>array("level"=>"LV.8","content"=>"增加普通攻击4000")
	,9=>array("level"=>"LV.9","content"=>"增加普通攻击4500")
	,10=>array("level"=>"LV.10","content"=>"增加普通攻击5000")
)	
,1=>array(	1=>array("level"=>"LV.1","content"=>"增加法术攻击500")
	,2=>array("level"=>"LV.2","content"=>"增加法术攻击1000")
	,3=>array("level"=>"LV.3","content"=>"增加法术攻击1500")
	,4=>array("level"=>"LV.4","content"=>"增加法术攻击2000")
	,5=>array("level"=>"LV.5","content"=>"增加法术攻击2500")
	,6=>array("level"=>"LV.6","content"=>"增加法术攻击3000")
	,7=>array("level"=>"LV.7","content"=>"增加法术攻击3500")
	,8=>array("level"=>"LV.8","content"=>"增加法术攻击4000")
	,9=>array("level"=>"LV.9","content"=>"增加法术攻击4500")
	,10=>array("level"=>"LV.10","content"=>"增加法术攻击5000")
)	
,2=>array(	1=>array("level"=>"LV.1","content"=>"增加绝技攻击500")
	,2=>array("level"=>"LV.2","content"=>"增加绝技攻击1000")
	,3=>array("level"=>"LV.3","content"=>"增加绝技攻击1500")
	,4=>array("level"=>"LV.4","content"=>"增加绝技攻击2000")
	,5=>array("level"=>"LV.5","content"=>"增加绝技攻击2500")
	,6=>array("level"=>"LV.6","content"=>"增加绝技攻击3000")
	,7=>array("level"=>"LV.7","content"=>"增加绝技攻击3500")
	,8=>array("level"=>"LV.8","content"=>"增加绝技攻击4000")
	,9=>array("level"=>"LV.9","content"=>"增加绝技攻击4500")
	,10=>array("level"=>"LV.10","content"=>"增加绝技攻击5000")
)	
,3=>array(	1=>array("level"=>"LV.1","content"=>"增加气势25")
	,2=>array("level"=>"LV.2","content"=>"增加气势30")
	,3=>array("level"=>"LV.3","content"=>"增加气势35")
	,4=>array("level"=>"LV.4","content"=>"增加气势40")
	,5=>array("level"=>"LV.5","content"=>"增加气势45")
	,6=>array("level"=>"LV.6","content"=>"增加气势50")
	,7=>array("level"=>"LV.7","content"=>"增加气势55")
	,8=>array("level"=>"LV.8","content"=>"增加气势60")
	,9=>array("level"=>"LV.9","content"=>"增加气势65")
	,10=>array("level"=>"LV.10","content"=>"增加气势70")
)	
,4=>array(	1=>array("level"=>"LV.1","content"=>"增加生命值2000")
	,2=>array("level"=>"LV.2","content"=>"增加生命值4000")
	,3=>array("level"=>"LV.3","content"=>"增加生命值6000")
	,4=>array("level"=>"LV.4","content"=>"增加生命值8000")
	,5=>array("level"=>"LV.5","content"=>"增加生命值10000")
	,6=>array("level"=>"LV.6","content"=>"增加生命值12000")
	,7=>array("level"=>"LV.7","content"=>"增加生命值14000")
	,8=>array("level"=>"LV.8","content"=>"增加生命值16000")
	,9=>array("level"=>"LV.9","content"=>"增加生命值18000")
	,10=>array("level"=>"LV.10","content"=>"增加生命值20000")
)	
,5=>array(	1=>array("level"=>"LV.1","content"=>"增加命中6%")
	,2=>array("level"=>"LV.2","content"=>"增加命中12%")
	,3=>array("level"=>"LV.3","content"=>"增加命中18%")
	,4=>array("level"=>"LV.4","content"=>"增加命中24%")
	,5=>array("level"=>"LV.5","content"=>"增加命中30%")
	,6=>array("level"=>"LV.6","content"=>"增加命中36%")
	,7=>array("level"=>"LV.7","content"=>"增加命中42%")
	,8=>array("level"=>"LV.8","content"=>"增加命中48%")
	,9=>array("level"=>"LV.9","content"=>"增加命中54%")
	,10=>array("level"=>"LV.10","content"=>"增加命中60%")
)	
,6=>array(	1=>array("level"=>"LV.1","content"=>"降低被爆击几率6%")
	,2=>array("level"=>"LV.2","content"=>"降低被爆击几率12%")
	,3=>array("level"=>"LV.3","content"=>"降低被爆击几率18%")
	,4=>array("level"=>"LV.4","content"=>"降低被爆击几率24%")
	,5=>array("level"=>"LV.5","content"=>"降低被爆击几率30%")
	,6=>array("level"=>"LV.6","content"=>"降低被爆击几率36%")
	,7=>array("level"=>"LV.7","content"=>"降低被爆击几率42%")
	,8=>array("level"=>"LV.8","content"=>"降低被爆击几率48%")
	,9=>array("level"=>"LV.9","content"=>"降低被爆击几率54%")
	,10=>array("level"=>"LV.10","content"=>"降低被爆击几率60%")
)	
,7=>array(	1=>array("level"=>"LV.1","content"=>"降低被格挡几率6%")
	,2=>array("level"=>"LV.2","content"=>"降低被格挡几率12%")
	,3=>array("level"=>"LV.3","content"=>"降低被格挡几率18%")
	,4=>array("level"=>"LV.4","content"=>"降低被格挡几率24%")
	,5=>array("level"=>"LV.5","content"=>"降低被格挡几率30%")
	,6=>array("level"=>"LV.6","content"=>"降低被格挡几率36%")
	,7=>array("level"=>"LV.7","content"=>"降低被格挡几率42%")
	,8=>array("level"=>"LV.8","content"=>"降低被格挡几率48%")
	,9=>array("level"=>"LV.9","content"=>"降低被格挡几率54%")
	,10=>array("level"=>"LV.10","content"=>"降低被格挡几率60%")
)	
,8=>array(	1=>array("level"=>"LV.1","content"=>"增加爆击伤害6%")
	,2=>array("level"=>"LV.2","content"=>"增加爆击伤害12%")
	,3=>array("level"=>"LV.3","content"=>"增加爆击伤害18%")
	,4=>array("level"=>"LV.4","content"=>"增加爆击伤害24%")
	,5=>array("level"=>"LV.5","content"=>"增加爆击伤害30%")
	,6=>array("level"=>"LV.6","content"=>"增加爆击伤害36%")
	,7=>array("level"=>"LV.7","content"=>"增加爆击伤害42%")
	,8=>array("level"=>"LV.8","content"=>"增加爆击伤害48%")
	,9=>array("level"=>"LV.9","content"=>"增加爆击伤害54%")
	,10=>array("level"=>"LV.10","content"=>"增加爆击伤害60%")
)	
,9=>array(	1=>array("level"=>"LV.1","content"=>"增加暴击 3%")
	,2=>array("level"=>"LV.2","content"=>"增加暴击 6%")
	,3=>array("level"=>"LV.3","content"=>"增加暴击 9%")
	,4=>array("level"=>"LV.4","content"=>"增加暴击 12%")
	,5=>array("level"=>"LV.5","content"=>"增加暴击 15%")
	,6=>array("level"=>"LV.6","content"=>"增加暴击 18%")
	,7=>array("level"=>"LV.7","content"=>"增加暴击 21%")
	,8=>array("level"=>"LV.8","content"=>"增加暴击 24%")
	,9=>array("level"=>"LV.9","content"=>"增加暴击 27%")
	,10=>array("level"=>"LV.10","content"=>"增加暴击 30%")
)	
,10=>array(	1=>array("level"=>"LV.1","content"=>"增加闪避 2%")
	,2=>array("level"=>"LV.2","content"=>"增加闪避 4%")
	,3=>array("level"=>"LV.3","content"=>"增加闪避 6%")
	,4=>array("level"=>"LV.4","content"=>"增加闪避 8%")
	,5=>array("level"=>"LV.5","content"=>"增加闪避 10%")
	,6=>array("level"=>"LV.6","content"=>"增加闪避 12%")
	,7=>array("level"=>"LV.7","content"=>"增加闪避 14%")
	,8=>array("level"=>"LV.8","content"=>"增加闪避 16%")
	,9=>array("level"=>"LV.9","content"=>"增加闪避 18%")
	,10=>array("level"=>"LV.10","content"=>"增加闪避 20%")
)	
,11=>array(	1=>array("level"=>"LV.1","content"=>"增加命中 3%")
	,2=>array("level"=>"LV.2","content"=>"增加命中 6%")
	,3=>array("level"=>"LV.3","content"=>"增加命中 9%")
	,4=>array("level"=>"LV.4","content"=>"增加命中 12%")
	,5=>array("level"=>"LV.5","content"=>"增加命中 15%")
	,6=>array("level"=>"LV.6","content"=>"增加命中 18%")
	,7=>array("level"=>"LV.7","content"=>"增加命中 21%")
	,8=>array("level"=>"LV.8","content"=>"增加命中 24%")
	,9=>array("level"=>"LV.9","content"=>"增加命中 27%")
	,10=>array("level"=>"LV.10","content"=>"增加命中 30%")
)	
,12=>array(	1=>array("level"=>"LV.1","content"=>"增加生命值1000")
	,2=>array("level"=>"LV.2","content"=>"增加生命值2000")
	,3=>array("level"=>"LV.3","content"=>"增加生命值3000")
	,4=>array("level"=>"LV.4","content"=>"增加生命值4000")
	,5=>array("level"=>"LV.5","content"=>"增加生命值5000")
	,6=>array("level"=>"LV.6","content"=>"增加生命值6000")
	,7=>array("level"=>"LV.7","content"=>"增加生命值7000")
	,8=>array("level"=>"LV.8","content"=>"增加生命值8000")
	,9=>array("level"=>"LV.9","content"=>"增加生命值9000")
	,10=>array("level"=>"LV.10","content"=>"增加生命值10000")
)	
,13=>array(	1=>array("level"=>"LV.1","content"=>"增加绝技攻击力300")
	,2=>array("level"=>"LV.2","content"=>"增加绝技攻击力450")
	,3=>array("level"=>"LV.3","content"=>"增加绝技攻击力600")
	,4=>array("level"=>"LV.4","content"=>"增加绝技攻击力750")
	,5=>array("level"=>"LV.5","content"=>"增加绝技攻击力900")
	,6=>array("level"=>"LV.6","content"=>"增加绝技攻击力1050")
	,7=>array("level"=>"LV.7","content"=>"增加绝技攻击力1200")
	,8=>array("level"=>"LV.8","content"=>"增加绝技攻击力1350")
	,9=>array("level"=>"LV.9","content"=>"增加绝技攻击力1500")
	,10=>array("level"=>"LV.10","content"=>"增加绝技攻击力1650")
)	
,14=>array(	1=>array("level"=>"LV.1","content"=>"增加普通攻击力300")
	,2=>array("level"=>"LV.2","content"=>"增加普通攻击力450")
	,3=>array("level"=>"LV.3","content"=>"增加普通攻击力600")
	,4=>array("level"=>"LV.4","content"=>"增加普通攻击力750")
	,5=>array("level"=>"LV.5","content"=>"增加普通攻击力900")
	,6=>array("level"=>"LV.6","content"=>"增加普通攻击力1050")
	,7=>array("level"=>"LV.7","content"=>"增加普通攻击力1200")
	,8=>array("level"=>"LV.8","content"=>"增加普通攻击力1350")
	,9=>array("level"=>"LV.9","content"=>"增加普通攻击力1500")
	,10=>array("level"=>"LV.10","content"=>"增加普通攻击力1650")
)	
,15=>array(	1=>array("level"=>"LV.1","content"=>"增加气势15")
	,2=>array("level"=>"LV.2","content"=>"增加气势19")
	,3=>array("level"=>"LV.3","content"=>"增加气势23")
	,4=>array("level"=>"LV.4","content"=>"增加气势27")
	,5=>array("level"=>"LV.5","content"=>"增加气势31")
	,6=>array("level"=>"LV.6","content"=>"增加气势35")
	,7=>array("level"=>"LV.7","content"=>"增加气势39")
	,8=>array("level"=>"LV.8","content"=>"增加气势43")
	,9=>array("level"=>"LV.9","content"=>"增加气势47")
	,10=>array("level"=>"LV.10","content"=>"增加气势51")
)	
,16=>array(	1=>array("level"=>"LV.1","content"=>"降低对方格挡4%")
	,2=>array("level"=>"LV.2","content"=>"降低对方格挡8%")
	,3=>array("level"=>"LV.3","content"=>"降低对方格挡12%")
	,4=>array("level"=>"LV.4","content"=>"降低对方格挡16%")
	,5=>array("level"=>"LV.5","content"=>"降低对方格挡20%")
	,6=>array("level"=>"LV.6","content"=>"降低对方格挡24%")
	,7=>array("level"=>"LV.7","content"=>"降低对方格挡28%")
	,8=>array("level"=>"LV.8","content"=>"降低对方格挡32%")
	,9=>array("level"=>"LV.9","content"=>"降低对方格挡36%")
	,10=>array("level"=>"LV.10","content"=>"降低对方格挡40%")
)	
,17=>array(	1=>array("level"=>"LV.1","content"=>"增加格挡3%")
	,2=>array("level"=>"LV.2","content"=>"增加格挡6%")
	,3=>array("level"=>"LV.3","content"=>"增加格挡9%")
	,4=>array("level"=>"LV.4","content"=>"增加格挡12%")
	,5=>array("level"=>"LV.5","content"=>"增加格挡15%")
	,6=>array("level"=>"LV.6","content"=>"增加格挡18%")
	,7=>array("level"=>"LV.7","content"=>"增加格挡21%")
	,8=>array("level"=>"LV.8","content"=>"增加格挡24%")
	,9=>array("level"=>"LV.9","content"=>"增加格挡27%")
	,10=>array("level"=>"LV.10","content"=>"增加格挡30%")
)	
,18=>array(	1=>array("level"=>"LV.1","content"=>"增加法术攻击力300")
	,2=>array("level"=>"LV.2","content"=>"增加法术攻击力450")
	,3=>array("level"=>"LV.3","content"=>"增加法术攻击力600")
	,4=>array("level"=>"LV.4","content"=>"增加法术攻击力750")
	,5=>array("level"=>"LV.5","content"=>"增加法术攻击力900")
	,6=>array("level"=>"LV.6","content"=>"增加法术攻击力1050")
	,7=>array("level"=>"LV.7","content"=>"增加法术攻击力1200")
	,8=>array("level"=>"LV.8","content"=>"增加法术攻击力1350")
	,9=>array("level"=>"LV.9","content"=>"增加法术攻击力1500")
	,10=>array("level"=>"LV.10","content"=>"增加法术攻击力1650")
)	
,19=>array(	1=>array("level"=>"LV.1","content"=>"增加气势 5")
	,2=>array("level"=>"LV.2","content"=>"增加气势 6")
	,3=>array("level"=>"LV.3","content"=>"增加气势 7")
	,4=>array("level"=>"LV.4","content"=>"增加气势 8")
	,5=>array("level"=>"LV.5","content"=>"增加气势 9")
	,6=>array("level"=>"LV.6","content"=>"增加气势 10")
	,7=>array("level"=>"LV.7","content"=>"增加气势 11")
	,8=>array("level"=>"LV.8","content"=>"增加气势 12")
	,9=>array("level"=>"LV.9","content"=>"增加气势 13")
	,10=>array("level"=>"LV.10","content"=>"增加气势 14")
)	
,20=>array(	1=>array("level"=>"LV.1","content"=>"增加法术攻击力200")
	,2=>array("level"=>"LV.2","content"=>"增加法术攻击力300")
	,3=>array("level"=>"LV.3","content"=>"增加法术攻击力400")
	,4=>array("level"=>"LV.4","content"=>"增加法术攻击力500")
	,5=>array("level"=>"LV.5","content"=>"增加法术攻击力600")
	,6=>array("level"=>"LV.6","content"=>"增加法术攻击力700")
	,7=>array("level"=>"LV.7","content"=>"增加法术攻击力800")
	,8=>array("level"=>"LV.8","content"=>"增加法术攻击力900")
	,9=>array("level"=>"LV.9","content"=>"增加法术攻击力1000")
	,10=>array("level"=>"LV.10","content"=>"增加法术攻击力1100")
)	
,21=>array(	1=>array("level"=>"LV.1","content"=>"增加格挡2%")
	,2=>array("level"=>"LV.2","content"=>"增加格挡4%")
	,3=>array("level"=>"LV.3","content"=>"增加格挡6%")
	,4=>array("level"=>"LV.4","content"=>"增加格挡8%")
	,5=>array("level"=>"LV.5","content"=>"增加格挡10%")
	,6=>array("level"=>"LV.6","content"=>"增加格挡12%")
	,7=>array("level"=>"LV.7","content"=>"增加格挡14%")
	,8=>array("level"=>"LV.8","content"=>"增加格挡16%")
	,9=>array("level"=>"LV.9","content"=>"增加格挡18%")
	,10=>array("level"=>"LV.10","content"=>"增加格挡20%")
)	
,22=>array(	1=>array("level"=>"LV.1","content"=>"增加绝技攻击力200")
	,2=>array("level"=>"LV.2","content"=>"增加绝技攻击力300")
	,3=>array("level"=>"LV.3","content"=>"增加绝技攻击力400")
	,4=>array("level"=>"LV.4","content"=>"增加绝技攻击力500")
	,5=>array("level"=>"LV.5","content"=>"增加绝技攻击力600")
	,6=>array("level"=>"LV.6","content"=>"增加绝技攻击力700")
	,7=>array("level"=>"LV.7","content"=>"增加绝技攻击力800")
	,8=>array("level"=>"LV.8","content"=>"增加绝技攻击力900")
	,9=>array("level"=>"LV.9","content"=>"增加绝技攻击力1000")
	,10=>array("level"=>"LV.10","content"=>"增加绝技攻击力1100")
)	
,23=>array(	1=>array("level"=>"LV.1","content"=>"增加普通攻击力200")
	,2=>array("level"=>"LV.2","content"=>"增加普通攻击力300")
	,3=>array("level"=>"LV.3","content"=>"增加普通攻击力400")
	,4=>array("level"=>"LV.4","content"=>"增加普通攻击力500")
	,5=>array("level"=>"LV.5","content"=>"增加普通攻击力600")
	,6=>array("level"=>"LV.6","content"=>"增加普通攻击力700")
	,7=>array("level"=>"LV.7","content"=>"增加普通攻击力800")
	,8=>array("level"=>"LV.8","content"=>"增加普通攻击力900")
	,9=>array("level"=>"LV.9","content"=>"增加普通攻击力1000")
	,10=>array("level"=>"LV.10","content"=>"增加普通攻击力1100")
)	
,24=>array(	1=>array("level"=>"LV.1","content"=>"增加命中2%")
	,2=>array("level"=>"LV.2","content"=>"增加命中4%")
	,3=>array("level"=>"LV.3","content"=>"增加命中6%")
	,4=>array("level"=>"LV.4","content"=>"增加命中8%")
	,5=>array("level"=>"LV.5","content"=>"增加命中10%")
	,6=>array("level"=>"LV.6","content"=>"增加命中12%")
	,7=>array("level"=>"LV.7","content"=>"增加命中14%")
	,8=>array("level"=>"LV.8","content"=>"增加命中16%")
	,9=>array("level"=>"LV.9","content"=>"增加命中18%")
	,10=>array("level"=>"LV.10","content"=>"增加命中20%")
)	
,25=>array(	1=>array("level"=>"LV.1","content"=>"增加闪避1%")
	,2=>array("level"=>"LV.2","content"=>"增加闪避2%")
	,3=>array("level"=>"LV.3","content"=>"增加闪避3%")
	,4=>array("level"=>"LV.4","content"=>"增加闪避4%")
	,5=>array("level"=>"LV.5","content"=>"增加闪避5%")
	,6=>array("level"=>"LV.6","content"=>"增加闪避6%")
	,7=>array("level"=>"LV.7","content"=>"增加闪避7%")
	,8=>array("level"=>"LV.8","content"=>"增加闪避8%")
	,9=>array("level"=>"LV.9","content"=>"增加闪避9%")
	,10=>array("level"=>"LV.10","content"=>"增加闪避10%")
)	
,26=>array(	1=>array("level"=>"LV.1","content"=>"增加法术防御175")
	,2=>array("level"=>"LV.2","content"=>"增加法术防御250")
	,3=>array("level"=>"LV.3","content"=>"增加法术防御325")
	,4=>array("level"=>"LV.4","content"=>"增加法术防御400")
	,5=>array("level"=>"LV.5","content"=>"增加法术防御475")
	,6=>array("level"=>"LV.6","content"=>"增加法术防御550")
	,7=>array("level"=>"LV.7","content"=>"增加法术防御625")
	,8=>array("level"=>"LV.8","content"=>"增加法术防御700")
	,9=>array("level"=>"LV.9","content"=>"增加法术防御775")
	,10=>array("level"=>"LV.10","content"=>"增加法术防御850")
)	
,27=>array(	1=>array("level"=>"LV.1","content"=>"增加绝技防御175")
	,2=>array("level"=>"LV.2","content"=>"增加绝技防御250")
	,3=>array("level"=>"LV.3","content"=>"增加绝技防御325")
	,4=>array("level"=>"LV.4","content"=>"增加绝技防御400")
	,5=>array("level"=>"LV.5","content"=>"增加绝技防御475")
	,6=>array("level"=>"LV.6","content"=>"增加绝技防御550")
	,7=>array("level"=>"LV.7","content"=>"增加绝技防御625")
	,8=>array("level"=>"LV.8","content"=>"增加绝技防御700")
	,9=>array("level"=>"LV.9","content"=>"增加绝技防御775")
	,10=>array("level"=>"LV.10","content"=>"增加绝技防御850")
)	
,28=>array(	1=>array("level"=>"LV.1","content"=>"增加普通防御100")
	,2=>array("level"=>"LV.2","content"=>"增加普通防御200")
	,3=>array("level"=>"LV.3","content"=>"增加普通防御300")
	,4=>array("level"=>"LV.4","content"=>"增加普通防御400")
	,5=>array("level"=>"LV.5","content"=>"增加普通防御500")
	,6=>array("level"=>"LV.6","content"=>"增加普通防御600")
	,7=>array("level"=>"LV.7","content"=>"增加普通防御700")
	,8=>array("level"=>"LV.8","content"=>"增加普通防御800")
	,9=>array("level"=>"LV.9","content"=>"增加普通防御900")
	,10=>array("level"=>"LV.10","content"=>"增加普通防御1000")
)	
,29=>array(	1=>array("level"=>"LV.1","content"=>"增加暴击2%")
	,2=>array("level"=>"LV.2","content"=>"增加暴击4%")
	,3=>array("level"=>"LV.3","content"=>"增加暴击6%")
	,4=>array("level"=>"LV.4","content"=>"增加暴击8%")
	,5=>array("level"=>"LV.5","content"=>"增加暴击10%")
	,6=>array("level"=>"LV.6","content"=>"增加暴击12%")
	,7=>array("level"=>"LV.7","content"=>"增加暴击14%")
	,8=>array("level"=>"LV.8","content"=>"增加暴击16%")
	,9=>array("level"=>"LV.9","content"=>"增加暴击18%")
	,10=>array("level"=>"LV.10","content"=>"增加暴击20%")
)	
,30=>array(	1=>array("level"=>"LV.1","content"=>"增加普通攻击力100")
	,2=>array("level"=>"LV.2","content"=>"增加普通攻击力150")
	,3=>array("level"=>"LV.3","content"=>"增加普通攻击力200")
	,4=>array("level"=>"LV.4","content"=>"增加普通攻击力250")
	,5=>array("level"=>"LV.5","content"=>"增加普通攻击力300")
	,6=>array("level"=>"LV.6","content"=>"增加普通攻击力350")
	,7=>array("level"=>"LV.7","content"=>"增加普通攻击力400")
	,8=>array("level"=>"LV.8","content"=>"增加普通攻击力450")
	,9=>array("level"=>"LV.9","content"=>"增加普通攻击力500")
	,10=>array("level"=>"LV.10","content"=>"增加普通攻击力550")
)	
,31=>array(	1=>array("level"=>"LV.1","content"=>"增加法术攻击力100")
	,2=>array("level"=>"LV.2","content"=>"增加法术攻击力150")
	,3=>array("level"=>"LV.3","content"=>"增加法术攻击力200")
	,4=>array("level"=>"LV.4","content"=>"增加法术攻击力250")
	,5=>array("level"=>"LV.5","content"=>"增加法术攻击力300")
	,6=>array("level"=>"LV.6","content"=>"增加法术攻击力350")
	,7=>array("level"=>"LV.7","content"=>"增加法术攻击力400")
	,8=>array("level"=>"LV.8","content"=>"增加法术攻击力450")
	,9=>array("level"=>"LV.9","content"=>"增加法术攻击力500")
	,10=>array("level"=>"LV.10","content"=>"增加法术攻击力550")
)	
,32=>array(	1=>array("level"=>"LV.1","content"=>"增加绝技攻击力100")
	,2=>array("level"=>"LV.2","content"=>"增加绝技攻击力150")
	,3=>array("level"=>"LV.3","content"=>"增加绝技攻击力200")
	,4=>array("level"=>"LV.4","content"=>"增加绝技攻击力250")
	,5=>array("level"=>"LV.5","content"=>"增加绝技攻击力300")
	,6=>array("level"=>"LV.6","content"=>"增加绝技攻击力350")
	,7=>array("level"=>"LV.7","content"=>"增加绝技攻击力400")
	,8=>array("level"=>"LV.8","content"=>"增加绝技攻击力450")
	,9=>array("level"=>"LV.9","content"=>"增加绝技攻击力500")
	,10=>array("level"=>"LV.10","content"=>"增加绝技攻击力550")
)	
,33=>array(	1=>array("level"=>"LV.1","content"=>"增加1200点经验")
)	




	);

	$str = "{mginfo:" . $json->encode($mginfo) . ",mgdata:" . $json->encode($mgdata) . "}";
	exit($str);
	
}
?>

