<?php
require("include/json.class.php");
$act=$_REQUEST["act"];
$json = new JSON();
if($act=="getdata"){
	$gvbhjnngfdew34drftvb = array(1=>array('name'=>'逍遥弓','pro'=>'飞羽','level'=>'20','cls'=>'降魔弓,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,3','pic'=>'1.jpg')
,2=>array('name'=>'逍遥剑','pro'=>'剑灵','level'=>'20','cls'=>'降魔剑,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,3','pic'=>'2.jpg')
,3=>array('name'=>'逍遥戟','pro'=>'将星','level'=>'20','cls'=>'降魔锤,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,3','pic'=>'3.jpg')
,4=>array('name'=>'逍遥拳套','pro'=>'武圣','level'=>'20','cls'=>'降魔拳套,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,3','pic'=>'4.jpg')
,5=>array('name'=>'逍遥杖','pro'=>'术士,方士','level'=>'20','cls'=>'降魔杖,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,3','pic'=>'5.jpg')
,6=>array('name'=>'逍遥战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'20','cls'=>'降魔战魂,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,5','pic'=>'6.jpg')
,7=>array('name'=>'逍遥元神','pro'=>'术士,方士','level'=>'20','cls'=>'降魔元神,玄天石,不醉石,念珠,象龙角','clc'=>'1,36,45,36,5','pic'=>'7.jpg')
,8=>array('name'=>'逍遥法袍','pro'=>'通用','level'=>'20','cls'=>'降魔法袍,绿珠,蓝宝石,紫幽羽','clc'=>'1,27,18,5','pic'=>'8.jpg')
,9=>array('name'=>'逍遥护符','pro'=>'通用','level'=>'20','cls'=>'降魔护符,绿珠,蓝宝石,紫幽羽','clc'=>'1,27,18,5','pic'=>'9.jpg')
,10=>array('name'=>'逍遥法冠','pro'=>'通用','level'=>'20','cls'=>'降魔法冠,绿珠,蓝宝石,紫幽羽','clc'=>'1,27,18,5','pic'=>'10.jpg')
,11=>array('name'=>'逍遥战靴','pro'=>'通用','level'=>'20','cls'=>'降魔战靴,绿珠,蓝宝石,紫幽羽','clc'=>'1,27,18,5','pic'=>'11.jpg')
,12=>array('name'=>'朱雀弓','pro'=>'飞羽','level'=>'40','cls'=>'逍遥弓,太阴鼎,玄阴石,魔木鼎,冰魄珠,炎石','clc'=>'1,48,30,40,25,60','pic'=>'12.jpg')
,13=>array('name'=>'朱雀剑','pro'=>'剑灵','level'=>'40','cls'=>'逍遥剑,太阴鼎,玄阴石,魔木鼎,冰魄珠,炎石','clc'=>'1,48,30,40,25,60','pic'=>'13.jpg')
,14=>array('name'=>'朱雀枪','pro'=>'将星','level'=>'40','cls'=>'逍遥戟,太阴鼎,玄阴石,魔木鼎,冰魄珠,炎石','clc'=>'1,48,30,40,25,60','pic'=>'14.jpg')
,15=>array('name'=>'朱雀拳套','pro'=>'武圣','level'=>'40','cls'=>'逍遥拳套,太阴鼎,玄阴石,魔木鼎,冰魄珠,炎石','clc'=>'1,48,30,40,25,60','pic'=>'15.jpg')
,16=>array('name'=>'朱雀杖','pro'=>'术士,方士','level'=>'40','cls'=>'逍遥杖,太阴鼎,玄阴石,魔木鼎,冰魄珠,炎石','clc'=>'1,48,30,40,25,60','pic'=>'16.jpg')
,17=>array('name'=>'朱雀战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'40','cls'=>'逍遥战魂,玄阴石,冰魄珠,太阴鼎,魔木鼎,炎石','clc'=>'1,30,50,60,40,60','pic'=>'17.jpg')
,18=>array('name'=>'朱雀元神','pro'=>'术士,方士','level'=>'40','cls'=>'逍遥元神,玄阴石,冰魄珠,太阴鼎,魔木鼎,炎石','clc'=>'1,30,50,60,40,60','pic'=>'18.jpg')
,19=>array('name'=>'朱雀法袍','pro'=>'通用','level'=>'40','cls'=>'逍遥法袍,冰魄珠,凤凰羽,血莲花,魔木鼎','clc'=>'1,25,20,25,40','pic'=>'19.jpg')
,20=>array('name'=>'朱雀护符','pro'=>'通用','level'=>'40','cls'=>'逍遥护符,冰魄珠,凤凰羽,血莲花,魔木鼎','clc'=>'1,25,20,25,40','pic'=>'20.jpg')
,21=>array('name'=>'朱雀法冠','pro'=>'通用','level'=>'40','cls'=>'逍遥法冠,冰魄珠,凤凰羽,血莲花,魔木鼎','clc'=>'1,25,20,25,40','pic'=>'21.jpg')
,22=>array('name'=>'朱雀战靴','pro'=>'通用','level'=>'40','cls'=>'逍遥战靴,冰魄珠,凤凰羽,血莲花,魔木鼎','clc'=>'1,25,20,25,40','pic'=>'22.jpg')
,23=>array('name'=>'玄奇弓','pro'=>'飞羽','level'=>'60','cls'=>'朱雀弓,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,36,72,72','pic'=>'23.jpg')
,24=>array('name'=>'玄奇剑','pro'=>'剑灵','level'=>'60','cls'=>'朱雀剑,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,36,72,72','pic'=>'24.jpg')
,25=>array('name'=>'玄奇棍','pro'=>'将星','level'=>'60','cls'=>'朱雀枪,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,36,72,72','pic'=>'25.jpg')
,26=>array('name'=>'玄奇拳套','pro'=>'武圣','level'=>'60','cls'=>'朱雀拳套,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,36,72,72','pic'=>'26.jpg')
,27=>array('name'=>'玄奇杖','pro'=>'术士,方士','level'=>'60','cls'=>'朱雀杖,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,36,72,72','pic'=>'27.jpg')
,28=>array('name'=>'玄奇战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'60','cls'=>'朱雀战魂,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,60,120,72','pic'=>'28.jpg')
,29=>array('name'=>'玄奇元神','pro'=>'术士,方士','level'=>'60','cls'=>'朱雀元神,魔玉,天雷珠,魔火邪珠,陨石,至尊鼎','clc'=>'1,54,12,60,120,72','pic'=>'29.jpg')
,30=>array('name'=>'玄奇法袍','pro'=>'通用','level'=>'60','cls'=>'朱雀法袍,天雷珠,至尊鼎,黑白珠,仙树杈,地府风灯','clc'=>'1,5,60,60,45,150','pic'=>'30.jpg')
,31=>array('name'=>'玄奇护符','pro'=>'通用','level'=>'60','cls'=>'朱雀护符,天雷珠,至尊鼎,黑白珠,仙树杈,地府风灯','clc'=>'1,5,60,60,45,150','pic'=>'31.jpg')
,32=>array('name'=>'玄奇法冠','pro'=>'通用','level'=>'60','cls'=>'朱雀法冠,天雷珠,至尊鼎,黑白珠,仙树杈,地府风灯','clc'=>'1,5,60,60,45,150','pic'=>'32.jpg')
,33=>array('name'=>'玄奇战靴','pro'=>'通用','level'=>'60','cls'=>'朱雀战靴,天雷珠,至尊鼎,黑白珠,仙树杈,地府风灯','clc'=>'1,5,60,60,45,150','pic'=>'33.jpg')
,34=>array('name'=>'倚天弓','pro'=>'飞羽','level'=>'70','cls'=>'玄奇弓,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'34.jpg')
,35=>array('name'=>'倚天剑','pro'=>'剑灵','level'=>'70','cls'=>'玄奇剑,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'35.jpg')
,36=>array('name'=>'倚天大刀','pro'=>'将星','level'=>'70','cls'=>'玄奇棍,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'36.jpg')
,37=>array('name'=>'倚天拳套','pro'=>'武圣','level'=>'70','cls'=>'玄奇拳套,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'37.jpg')
,38=>array('name'=>'倚天杖','pro'=>'术士,方士','level'=>'70','cls'=>'玄奇杖,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'38.jpg')
,39=>array('name'=>'倚天战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'70','cls'=>'玄奇战魂,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,123,120,120,90,60','pic'=>'39.jpg')
,40=>array('name'=>'倚天元神','pro'=>'术士,方士','level'=>'70','cls'=>'玄奇元神,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,123,120,120,90,60','pic'=>'40.jpg')
,41=>array('name'=>'倚天法袍','pro'=>'通用','level'=>'70','cls'=>'玄奇法袍,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'41.jpg')
,42=>array('name'=>'倚天护符','pro'=>'通用','level'=>'70','cls'=>'玄奇护符,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'42.jpg')
,43=>array('name'=>'倚天法冠','pro'=>'通用','level'=>'70','cls'=>'玄奇法冠,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'43.jpg')
,44=>array('name'=>'倚天战靴','pro'=>'通用','level'=>'70','cls'=>'玄奇战靴,陨石,狼尾草,鬼树枝,地火胆,天心花','clc'=>'1,96,96,96,72,48','pic'=>'44.jpg')
,45=>array('name'=>'赤霄弓','pro'=>'飞羽','level'=>'80','cls'=>'倚天弓,灵果,辟邪玉,麒麟角,紫火花,狼骨','clc'=>'1,50,60,60,60,70','pic'=>'45.jpg')
,46=>array('name'=>'赤霄剑','pro'=>'剑灵','level'=>'80','cls'=>'倚天剑,灵果,辟邪玉,麒麟角,紫火花,狼骨','clc'=>'1,50,60,60,60,70','pic'=>'46.jpg')
,47=>array('name'=>'赤霄斧','pro'=>'将星','level'=>'80','cls'=>'倚天大刀,灵果,辟邪玉,麒麟角,紫火花,狼骨','clc'=>'1,50,60,60,60,70','pic'=>'47.jpg')
,48=>array('name'=>'赤霄拳套','pro'=>'武圣','level'=>'80','cls'=>'倚天拳套,灵果,辟邪玉,麒麟角,紫火花,狼骨','clc'=>'1,50,60,60,60,70','pic'=>'48.jpg')
,49=>array('name'=>'赤霄杖','pro'=>'术士,方士','level'=>'80','cls'=>'倚天杖,灵果,辟邪玉,麒麟角,紫火花,狼骨','clc'=>'1,50,60,60,60,70','pic'=>'49.jpg')
,50=>array('name'=>'赤霄战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'80','cls'=>'倚天战魂,黑流玉,辟邪玉,麒麟角,紫火花,九宫瓶','clc'=>'1,45,90,60,60,90','pic'=>'50.jpg')
,51=>array('name'=>'赤霄元神','pro'=>'术士,方士','level'=>'80','cls'=>'倚天元神,黑流玉,辟邪玉,麒麟角,紫火花,九宫瓶','clc'=>'1,45,90,60,60,90','pic'=>'51.jpg')
,52=>array('name'=>'赤霄法袍','pro'=>'通用','level'=>'80','cls'=>'倚天法袍,灵果,辟邪玉,九宫瓶,紫火花,麒麟角','clc'=>'1,50,60,72,60,60','pic'=>'52.jpg')
,53=>array('name'=>'赤霄护符','pro'=>'通用','level'=>'80','cls'=>'倚天护符,灵果,辟邪玉,九宫瓶,紫火花,麒麟角','clc'=>'1,50,60,72,60,60','pic'=>'53.jpg')
,54=>array('name'=>'赤霄法冠','pro'=>'通用','level'=>'80','cls'=>'倚天法冠,灵果,辟邪玉,九宫瓶,紫火花,麒麟角','clc'=>'1,50,60,72,60,60','pic'=>'54.jpg')
,55=>array('name'=>'赤霄战靴','pro'=>'通用','level'=>'80','cls'=>'倚天战靴,灵果,辟邪玉,九宫瓶,紫火花,麒麟角','clc'=>'1,50,60,72,60,60','pic'=>'55.jpg')
,56=>array('name'=>'天罡弓','pro'=>'飞羽','level'=>'90','cls'=>'赤霄弓,天仙花,玉杯,不灭灯,仙宝石,云纹金钗','clc'=>'1,30,60,5,30,60','pic'=>'tgg.jpg')
,57=>array('name'=>'天罡剑','pro'=>'剑灵','level'=>'90','cls'=>'赤霄剑,天仙花,玉杯,不灭灯,仙宝石,云纹金钗','clc'=>'1,30,60,5,30,60','pic'=>'tgj.jpg')
,58=>array('name'=>'天罡锤','pro'=>'将星','level'=>'90','cls'=>'赤霄斧,天仙花,玉杯,不灭灯,仙宝石,云纹金钗','clc'=>'1,30,60,5,30,60','pic'=>'tgc.jpg')
,59=>array('name'=>'天罡拳套','pro'=>'武圣','level'=>'90','cls'=>'赤霄拳套,天仙花,玉杯,不灭灯,仙宝石,云纹金钗','clc'=>'1,30,60,5,30,60','pic'=>'tgqt.jpg')
,60=>array('name'=>'天罡杖','pro'=>'术士,方士','level'=>'90','cls'=>'赤霄杖,天仙花,玉杯,不灭灯,仙宝石,云纹金钗','clc'=>'1,30,60,5,30,60','pic'=>'tgz.jpg')
,61=>array('name'=>'天罡战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'90','cls'=>'赤霄战魂,仙宝石,火焰宝石,玉杯,绝色玉,不灭灯','clc'=>'1,30,30,90,60,5','pic'=>'tgzh.jpg')
,62=>array('name'=>'天罡元神','pro'=>'术士,方士','level'=>'90','cls'=>'赤霄战魂,仙宝石,火焰宝石,玉杯,绝色玉,不灭灯','clc'=>'1,30,60,30,24,5','pic'=>'tgys.jpg')
,63=>array('name'=>'天罡法袍','pro'=>'通用','level'=>'90','cls'=>'赤霄法袍,天仙花,玉杯,仙宝石,火焰宝石,不灭灯','clc'=>'1,30,60,30,24,5','pic'=>'tgfp.jpg')
,64=>array('name'=>'天罡护符','pro'=>'通用','level'=>'90','cls'=>'赤霄护符,天仙花,玉杯,仙宝石,火焰宝石,不灭灯','clc'=>'1,30,60,30,24,5','pic'=>'tghf.jpg')
,65=>array('name'=>'天罡法冠','pro'=>'通用','level'=>'90','cls'=>'赤霄法冠,天仙花,玉杯,仙宝石,火焰宝石,不灭灯','clc'=>'1,30,60,30,24,5','pic'=>'tgfg.jpg')
,66=>array('name'=>'天罡战靴','pro'=>'通用','level'=>'90','cls'=>'赤霄战靴,天仙花,玉杯,仙宝石,火焰宝石,不灭灯','clc'=>'1,30,60,30,24,5','pic'=>'tgzx.jpg')
,67=>array('name'=>'乾坤弓','pro'=>'飞羽','level'=>'100','cls'=>'天罡弓,妖果,蝎刺,仙人刺,血凝爪,一枝花','clc'=>'1,90,90,10,10,10','pic'=>'qkg.jpg')
,68=>array('name'=>'乾坤剑','pro'=>'剑灵','level'=>'100','cls'=>'天罡剑,妖果,蝎刺,仙人刺,血凝爪,一枝花','clc'=>'1,90,90,10,10,10','pic'=>'qkj.jpg')
,69=>array('name'=>'乾坤宝刀','pro'=>'将星','level'=>'100','cls'=>'天罡锤,妖果,蝎刺,仙人刺,血凝爪,一枝花','clc'=>'1,90,90,10,10,10','pic'=>'qkbd.jpg')
,70=>array('name'=>'乾坤拳套','pro'=>'武圣','level'=>'100','cls'=>'天罡拳套,妖果,蝎刺,仙人刺,血凝爪,一枝花','clc'=>'1,90,90,10,10,10','pic'=>'qkqt.jpg')
,71=>array('name'=>'乾坤杖','pro'=>'术士,方士','level'=>'100','cls'=>'天罡杖,妖果,蝎刺,仙人刺,血凝爪,一枝花','clc'=>'1,90,90,10,10,10','pic'=>'qkz.jpg')
,72=>array('name'=>'乾坤战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'100','cls'=>'天罡战魂,霸王鼎,血凝爪,龙鳞,仙人刺,一枝花','clc'=>'1,10,10,10,5,10','pic'=>'qkzh.jpg')
,73=>array('name'=>'乾坤元神','pro'=>'术士,方士','level'=>'100','cls'=>'天罡元神,霸王鼎,血凝爪,龙鳞,仙人刺,一枝花','clc'=>'1,10,10,10,5,10','pic'=>'qkys.jpg')
,74=>array('name'=>'乾坤法袍','pro'=>'通用','level'=>'100','cls'=>'天罡法袍,蝎刺,仙人刺,血凝爪,龙鳞,一枝花','clc'=>'1,90,10,10,10,10','pic'=>'qkfp.jpg')
,75=>array('name'=>'乾坤护符','pro'=>'通用','level'=>'100','cls'=>'天罡护符,蝎刺,仙人刺,血凝爪,龙鳞,一枝花','clc'=>'1,90,10,10,10,10','pic'=>'qkhf.jpg')
,76=>array('name'=>'乾坤法冠','pro'=>'通用','level'=>'100','cls'=>'天罡法冠,蝎刺,仙人刺,血凝爪,龙鳞,一枝花','clc'=>'1,90,10,10,10,10','pic'=>'qkfg.jpg')
,77=>array('name'=>'乾坤战靴','pro'=>'通用','level'=>'100','cls'=>'天罡战靴,蝎刺,仙人刺,血凝爪,龙鳞,一枝花','clc'=>'1,90,10,10,10,10','pic'=>'qkzx.jpg')
,78=>array('name'=>'降魔弓','pro'=>'飞羽','level'=>'10','cls'=>'','clc'=>'','pic'=>'56.jpg')
,79=>array('name'=>'降魔剑','pro'=>'剑灵','level'=>'10','cls'=>'','clc'=>'','pic'=>'57.jpg')
,80=>array('name'=>'降魔锤','pro'=>'将星','level'=>'10','cls'=>'','clc'=>'','pic'=>'58.jpg')
,81=>array('name'=>'降魔拳套','pro'=>'武圣','level'=>'10','cls'=>'','clc'=>'','pic'=>'59.jpg')
,82=>array('name'=>'降魔杖','pro'=>'术士,方士','level'=>'10','cls'=>'','clc'=>'','pic'=>'60.jpg')
,83=>array('name'=>'降魔战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'10','cls'=>'','clc'=>'','pic'=>'61.jpg')
,84=>array('name'=>'降魔元神','pro'=>'术士,方士','level'=>'10','cls'=>'','clc'=>'','pic'=>'62.jpg')
,85=>array('name'=>'降魔法袍','pro'=>'通用','level'=>'10','cls'=>'','clc'=>'','pic'=>'63.jpg')
,86=>array('name'=>'降魔护符','pro'=>'通用','level'=>'10','cls'=>'','clc'=>'','pic'=>'64.jpg')
,87=>array('name'=>'降魔法冠','pro'=>'通用','level'=>'10','cls'=>'','clc'=>'','pic'=>'65.jpg')
,88=>array('name'=>'降魔战靴','pro'=>'通用','level'=>'10','cls'=>'','clc'=>'','pic'=>'66.jpg')
,89=>array('name'=>'霸者弓','pro'=>'飞羽','level'=>'40','cls'=>'','clc'=>'','pic'=>'67.jpg')
,90=>array('name'=>'霸者剑','pro'=>'剑灵','level'=>'40','cls'=>'','clc'=>'','pic'=>'68.jpg')
,91=>array('name'=>'霸者锤','pro'=>'将星','level'=>'40','cls'=>'','clc'=>'','pic'=>'69.jpg')
,92=>array('name'=>'霸者拳套','pro'=>'武圣','level'=>'40','cls'=>'','clc'=>'','pic'=>'70.jpg')
,93=>array('name'=>'霸者杖','pro'=>'术士,方士','level'=>'40','cls'=>'','clc'=>'','pic'=>'71.jpg')
,94=>array('name'=>'霸者战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'40','cls'=>'','clc'=>'','pic'=>'72.jpg')
,95=>array('name'=>'霸者元神','pro'=>'术士,方士','level'=>'40','cls'=>'','clc'=>'','pic'=>'73.jpg')
,96=>array('name'=>'霸者法袍','pro'=>'通用','level'=>'40','cls'=>'','clc'=>'','pic'=>'74.jpg')
,97=>array('name'=>'霸者护符','pro'=>'通用','level'=>'40','cls'=>'','clc'=>'','pic'=>'75.jpg')
,98=>array('name'=>'霸者法冠','pro'=>'通用','level'=>'40','cls'=>'','clc'=>'','pic'=>'76.jpg')
,99=>array('name'=>'霸者战靴','pro'=>'通用','level'=>'40','cls'=>'','clc'=>'','pic'=>'77.jpg')
,100=>array('name'=>'水晶弓','pro'=>'飞羽','level'=>'60','cls'=>'','clc'=>'','pic'=>'78.jpg')
,101=>array('name'=>'水晶剑','pro'=>'剑灵','level'=>'60','cls'=>'','clc'=>'','pic'=>'79.jpg')
,102=>array('name'=>'水晶枪','pro'=>'将星','level'=>'60','cls'=>'','clc'=>'','pic'=>'80.jpg')
,103=>array('name'=>'水晶拳套','pro'=>'武圣','level'=>'60','cls'=>'','clc'=>'','pic'=>'81.jpg')
,104=>array('name'=>'水晶杖','pro'=>'术士,方士','level'=>'60','cls'=>'','clc'=>'','pic'=>'82.jpg')
,105=>array('name'=>'水晶战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'60','cls'=>'','clc'=>'','pic'=>'83.jpg')
,106=>array('name'=>'水晶元神','pro'=>'术士,方士','level'=>'60','cls'=>'','clc'=>'','pic'=>'84.jpg')
,107=>array('name'=>'水晶法袍','pro'=>'通用','level'=>'60','cls'=>'','clc'=>'','pic'=>'85.jpg')
,108=>array('name'=>'水晶护符','pro'=>'通用','level'=>'60','cls'=>'','clc'=>'','pic'=>'86.jpg')
,109=>array('name'=>'水晶法冠','pro'=>'通用','level'=>'60','cls'=>'','clc'=>'','pic'=>'87.jpg')
,110=>array('name'=>'水晶战靴','pro'=>'通用','level'=>'60','cls'=>'','clc'=>'','pic'=>'88.jpg')
,111=>array('name'=>'青虹弓','pro'=>'飞羽','level'=>'70','cls'=>'','clc'=>'','pic'=>'89.jpg')
,112=>array('name'=>'青虹剑','pro'=>'剑灵','level'=>'70','cls'=>'','clc'=>'','pic'=>'90.jpg')
,113=>array('name'=>'青虹大刀','pro'=>'将星','level'=>'70','cls'=>'','clc'=>'','pic'=>'91.jpg')
,114=>array('name'=>'青虹拳套','pro'=>'武圣','level'=>'70','cls'=>'','clc'=>'','pic'=>'92.jpg')
,115=>array('name'=>'青虹杖','pro'=>'术士,方士','level'=>'70','cls'=>'','clc'=>'','pic'=>'93.jpg')
,116=>array('name'=>'青虹战魂','pro'=>'飞羽,剑灵,将星,武圣','level'=>'70','cls'=>'','clc'=>'','pic'=>'94.jpg')
,117=>array('name'=>'青虹元神','pro'=>'术士,方士','level'=>'70','cls'=>'','clc'=>'','pic'=>'95.jpg')
,118=>array('name'=>'青虹法袍','pro'=>'通用','level'=>'70','cls'=>'','clc'=>'','pic'=>'96.jpg')
,119=>array('name'=>'青虹护符','pro'=>'通用','level'=>'70','cls'=>'','clc'=>'','pic'=>'97.jpg')
,120=>array('name'=>'青虹法冠','pro'=>'通用','level'=>'70','cls'=>'','clc'=>'','pic'=>'98.jpg')
,121=>array('name'=>'青虹战靴','pro'=>'通用','level'=>'70','cls'=>'','clc'=>'','pic'=>'99.jpg')







    );
	
	$sdf345g1df0235235 = array(1=>array('name'=>'一品武力丹','level'=>'10','cls'=>'狐皮,蜘蛛牙','clc'=>'2,2','pic'=>'1.jpg')
,2=>array('name'=>'一品绝技丹','level'=>'10','cls'=>'落英花,蜘蛛牙','clc'=>'2,2','pic'=>'2.jpg')
,3=>array('name'=>'一品法术丹','level'=>'10','cls'=>'狐皮,蜘蛛牙','clc'=>'2,2','pic'=>'3.jpg')
,4=>array('name'=>'一品葫芦','level'=>'10','cls'=>'黑水珠,地府风灯','clc'=>'6,6','pic'=>'4.jpg')
,5=>array('name'=>'二品武力丹','level'=>'20','cls'=>'落英花,胭脂','clc'=>'9,9','pic'=>'5.jpg')
,6=>array('name'=>'二品绝技丹','level'=>'20','cls'=>'蓝宝石,蜘蛛牙','clc'=>'9,6','pic'=>'6.jpg')
,7=>array('name'=>'二品法术丹','level'=>'20','cls'=>'蓝宝石,胭脂','clc'=>'9,9','pic'=>'7.jpg')
,8=>array('name'=>'二品葫芦','level'=>'20','cls'=>'黑水珠,地府风灯','clc'=>'12,12','pic'=>'8.jpg')
,9=>array('name'=>'三品武力丹','level'=>'30','cls'=>'绿珠,雷兽角,象龙角','clc'=>'14,5,5','pic'=>'9.jpg')
,10=>array('name'=>'三品绝技丹','level'=>'30','cls'=>'念珠,蓝宝石,象龙角','clc'=>'14,14,5','pic'=>'10.jpg')
,11=>array('name'=>'三品法术丹','level'=>'30','cls'=>'念珠,蓝宝石,象龙角','clc'=>'14,14,5','pic'=>'11.jpg')
,12=>array('name'=>'三品葫芦','level'=>'30','cls'=>'黑水珠,地府风灯,天雷珠','clc'=>'36,36,3','pic'=>'12.jpg')
,13=>array('name'=>'四品武力丹','level'=>'40','cls'=>'露凝草,乌舌兰,炎石,血莲花','clc'=>'24,24,24,18','pic'=>'13.jpg')
,14=>array('name'=>'四品绝技丹','level'=>'40','cls'=>'露凝草,玄阴石,炎石,血莲花','clc'=>'24,14,18,18','pic'=>'14.jpg')
,15=>array('name'=>'四品法术丹','level'=>'40','cls'=>'露凝草,玄阴石,炎石,血莲花','clc'=>'24,14,18,18','pic'=>'15.jpg')
,16=>array('name'=>'四品葫芦','level'=>'40','cls'=>'魔玉,狼尾草,魔火邪珠,地火胆','clc'=>'29,39,20,29','pic'=>'16.jpg')
,17=>array('name'=>'五品武力丹','level'=>'50','cls'=>'露凝草,炎石,乌舌兰,魔木鼎,冰魄珠','clc'=>'27,27,27,24,36','pic'=>'17.jpg')
,18=>array('name'=>'五品绝技丹','level'=>'50','cls'=>'露凝草,太阴鼎,乌舌兰,魔木鼎,冰魄珠','clc'=>'27,42,27,24,36','pic'=>'18.jpg')
,19=>array('name'=>'五品法术丹','level'=>'50','cls'=>'露凝草,太阴鼎,乌舌兰,魔木鼎,冰魄珠','clc'=>'27,42,27,24,36','pic'=>'19.jpg')
,20=>array('name'=>'五品葫芦','level'=>'50','cls'=>'魔火邪珠,陨石,狼尾草,鬼树枝,地火胆','clc'=>'29,45,58,58,44','pic'=>'20.jpg')
,21=>array('name'=>'六品武力丹','level'=>'60','cls'=>'玉珊瑚,黑白珠,天魔果,太阴鼎,地府风灯,天雷珠','clc'=>'42,60,42,42,53,4','pic'=>'21.jpg')
,22=>array('name'=>'六品绝技丹','level'=>'60','cls'=>'玉珊瑚,黑白珠,天魔果,仙树枝,地府风灯,冰魄珠','clc'=>'42,60,42,32,53,42','pic'=>'22.jpg')
,23=>array('name'=>'六品法术丹','level'=>'60','cls'=>'玉珊瑚,黑白珠,天魔果,仙树枝,地府风灯,冰魄珠','clc'=>'42,60,42,32,53,42','pic'=>'23.jpg')






	);

	$vwsq12sd7ft8bynoim099=array(1=>array('name'=>'狐皮','gets'=>'青丘山1-2','cls'=>'一品武力丹,一品法力丹','pic'=>'1.jpg')
,2=>array('name'=>'落英花','gets'=>'幽花林1','cls'=>'一品绝技丹,一品法力丹,二品武力丹','pic'=>'2.jpg')
,3=>array('name'=>'蜘蛛牙','gets'=>'幽花林2-3','cls'=>'一品武力丹,一品绝技丹,二品绝技丹','pic'=>'3.jpg')
,4=>array('name'=>'黑水珠','gets'=>'水下宫殿4-6','cls'=>'一品葫芦,二品葫芦,三品葫芦,六品法力丹','pic'=>'4.jpg')
,5=>array('name'=>'胭脂','gets'=>'须臾幻境3-4','cls'=>'二品法力丹','pic'=>'5.jpg')
,6=>array('name'=>'雷兽角','gets'=>'封神陵2-3','cls'=>'三品武力丹','pic'=>'6.jpg')
,7=>array('name'=>'露凝草','gets'=>'灵山2-3,5-7','cls'=>'四品武力丹,四品法力丹,五品武力丹,五品绝技丹','pic'=>'7.jpg')
,8=>array('name'=>'乌舌兰','gets'=>'灵山1-3','cls'=>'四品武力丹,四品法力丹,五品武力丹,五品绝技丹','pic'=>'8.jpg')
,9=>array('name'=>'玉珊瑚','gets'=>'水下宫殿1-3','cls'=>'六品武力丹,六品绝技丹','pic'=>'9.jpg')
,10=>array('name'=>'天魔果','gets'=>'玄冥界5-9','cls'=>'六品武力丹,六品绝技丹','pic'=>'10.jpg')
,11=>array('name'=>'念珠','gets'=>'葬剑谷1-5','cls'=>'逍遥弓,逍遥剑,逍遥拳套,逍遥杖,逍遥戟,逍遥战魂,逍遥元神,三品绝技丹,三品法力丹','pic'=>'11.jpg')
,12=>array('name'=>'玄天石','gets'=>'封神陵1-6','cls'=>'逍遥弓,逍遥剑,逍遥拳套,逍遥杖,逍遥戟,逍遥战魂,逍遥元神,三品法力丹','pic'=>'12.jpg')
,13=>array('name'=>'象龙角','gets'=>'封神陵4-5','cls'=>'逍遥弓,逍遥剑,逍遥拳套,逍遥杖,逍遥戟,逍遥战魂,逍遥元神,三品武力丹,三品绝技丹,三品法力丹','pic'=>'13.jpg')
,14=>array('name'=>'不醉石','gets'=>'须臾幻境1-2','cls'=>'逍遥弓,逍遥剑,逍遥拳套,逍遥杖,逍遥戟,逍遥战魂,逍遥元神,三品法力丹','pic'=>'14.jpg')
,15=>array('name'=>'蓝宝石','gets'=>'须臾幻境5-6','cls'=>'逍遥法冠,逍遥法袍,逍遥战靴,逍遥护符,三品绝技丹,三品法力丹','pic'=>'15.jpg')
,16=>array('name'=>'紫幽羽','gets'=>'封神陵6','cls'=>'逍遥法冠,逍遥法袍,逍遥战靴,逍遥护符','pic'=>'16.jpg')
,17=>array('name'=>'绿珠','gets'=>'须臾幻境7-8','cls'=>'逍遥法冠,逍遥法袍,逍遥战靴,逍遥护符,三品武力丹','pic'=>'17.jpg')
,18=>array('name'=>'太阴鼎','gets'=>'玄暝界1-9','cls'=>'朱雀拳套,朱雀剑,朱雀弓,朱雀枪,朱雀杖,朱雀战魂,朱雀元神,六品武力丹,六品法力丹','pic'=>'18.jpg')
,19=>array('name'=>'魔木鼎','gets'=>'大荒漠城1-9','cls'=>'朱雀拳套,朱雀剑,朱雀弓,朱雀枪,朱雀杖,朱雀法冠,朱雀法袍,朱雀战靴,朱雀护符,朱雀战魂,朱雀元神,五品武力丹,五品绝技丹,五品法力丹','pic'=>'19.jpg')
,20=>array('name'=>'炎石','gets'=>'烈焰洞1-7','cls'=>'朱雀拳套,朱雀剑,朱雀弓,朱雀枪,朱雀杖,朱雀战魂,朱雀元神,四品武力丹,四品绝技丹,四品法力丹','pic'=>'20.jpg')
,21=>array('name'=>'冰魄珠','gets'=>'玉都峰1-9','cls'=>'朱雀拳套,朱雀剑,朱雀弓,朱雀枪,朱雀杖,朱雀法冠,朱雀法袍,朱雀战靴,朱雀护符,朱雀战魂,朱雀元神,五品武力丹,五品绝技丹,五品法力丹,六品绝技丹','pic'=>'21.jpg')
,22=>array('name'=>'玄阴石','gets'=>'炼妖塔7-9','cls'=>'朱雀拳套,朱雀剑,朱雀弓,朱雀枪,朱雀杖,朱雀战魂,朱雀元神,四品绝技丹,四品法力丹','pic'=>'22.jpg')
,23=>array('name'=>'凤凰羽','gets'=>'灵山8','cls'=>'朱雀法冠,朱雀法袍,朱雀战靴,朱雀护符','pic'=>'23.jpg')
,24=>array('name'=>'血莲花','gets'=>'炼妖塔1-6','cls'=>'朱雀法冠,朱雀法袍,朱雀战靴,朱雀护符','pic'=>'24.jpg')
,25=>array('name'=>'天雷珠','gets'=>'虚天殿6','cls'=>'玄奇拳套,玄奇剑,玄奇弓,玄奇棍,玄奇杖,玄奇战魂,玄奇元神,玄奇法冠,玄奇法袍,玄奇战靴,玄奇护符,六品武力丹,六品法力丹','pic'=>'25.jpg')
,26=>array('name'=>'至尊鼎','gets'=>'虚天殿7-8','cls'=>'玄奇拳套,玄奇剑,玄奇弓,玄奇棍,玄奇杖,玄奇战魂,玄奇元神,玄奇法冠,玄奇法袍,玄奇战靴,玄奇护符','pic'=>'26.jpg')
,27=>array('name'=>'魔火邪珠','gets'=>'蜀山秘道5','cls'=>'玄奇拳套,玄奇剑,玄奇弓,玄奇棍,玄奇杖,玄奇战魂,玄奇元神,四品葫芦,五品葫芦','pic'=>'27.jpg')
,28=>array('name'=>'魔玉','gets'=>'蜀山秘道1-4','cls'=>'玄奇拳套,玄奇剑,玄奇弓,玄奇棍,玄奇杖,玄奇战魂,玄奇元神,四品葫芦','pic'=>'28.jpg')
,29=>array('name'=>'陨石','gets'=>'扶桑神树1','cls'=>'玄奇拳套,玄奇剑,玄奇弓,玄奇棍,玄奇杖,玄奇战魂,玄奇元神,倚天拳套,倚天剑,倚天弓,倚天大刀,倚天杖,倚天战魂,倚天元神,倚天法冠,倚天法袍,倚天战靴,倚天护符,五品葫芦','pic'=>'29.jpg')
,30=>array('name'=>'仙树枝','gets'=>'虚天殿2','cls'=>'玄奇法冠,玄奇法袍,玄奇战靴,玄奇护符,六品绝技丹','pic'=>'30.jpg')
,31=>array('name'=>'地府风灯','gets'=>'幽冥地府4-9','cls'=>'玄奇法冠,玄奇法袍,玄奇战靴,玄奇护符,一品葫芦,二品葫芦,三品葫芦,六品武力丹,六品绝技丹,六品法力丹','pic'=>'31.jpg')
,32=>array('name'=>'黑白珠','gets'=>'水下宫殿7-9','cls'=>'玄奇法冠,玄奇法袍,玄奇战靴,玄奇护符,六品武力丹,六品绝技丹,六品法力丹','pic'=>'32.jpg')
,33=>array('name'=>'地火胆','gets'=>'大裂谷1','cls'=>'倚天拳套,倚天剑,倚天弓,倚天大刀,倚天杖,倚天战魂,倚天元神,倚天法冠,倚天法袍,倚天战靴,倚天护符,四品葫芦,五品葫芦','pic'=>'33.jpg')
,34=>array('name'=>'狼尾草','gets'=>'大裂谷5','cls'=>'倚天拳套,倚天剑,倚天弓,倚天大刀,倚天杖,倚天战魂,倚天元神,倚天法冠,倚天法袍,倚天战靴,倚天护符,四品葫芦,五品葫芦','pic'=>'34.jpg')
,35=>array('name'=>'鬼树枝','gets'=>'扶桑神树10','cls'=>'倚天拳套,倚天剑,倚天弓,倚天大刀,倚天杖,倚天战魂,倚天元神,倚天法冠,倚天法袍,倚天战靴,倚天护符,五品葫芦','pic'=>'35.jpg')
,36=>array('name'=>'天心花','gets'=>'蜀山秘道11','cls'=>'倚天拳套,倚天剑,倚天弓,倚天大刀,倚天杖,倚天战魂,倚天元神,倚天法冠,倚天法袍,倚天战靴,倚天护符','pic'=>'36.jpg')
,37=>array('name'=>'灵果','gets'=>'冰晶河9','cls'=>'赤霄拳套,赤霄剑,赤霄弓,赤霄斧,赤霄杖,赤霄法冠,赤霄法袍,赤霄战靴,赤霄护符','pic'=>'37.jpg')
,38=>array('name'=>'辟邪玉','gets'=>'火焰山1','cls'=>'赤霄拳套,赤霄剑,赤霄弓,赤霄斧,赤霄杖,赤霄战魂,赤霄元神,赤霄法冠,赤霄法袍,赤霄战靴,赤霄护符','pic'=>'38.jpg')
,39=>array('name'=>'麒麟角','gets'=>'火焰山13','cls'=>'赤霄拳套,赤霄剑,赤霄弓,赤霄斧,赤霄杖,赤霄战魂,赤霄元神,赤霄法冠,赤霄法袍,赤霄战靴,赤霄护符','pic'=>'39.jpg')
,40=>array('name'=>'紫火花','gets'=>'火焰山11','cls'=>'赤霄拳套,赤霄剑,赤霄弓,赤霄斧,赤霄杖,赤霄战魂,赤霄元神,赤霄法冠,赤霄法袍,赤霄战靴,赤霄护符','pic'=>'40.jpg')
,41=>array('name'=>'九宫瓶','gets'=>'玄净寺','cls'=>'赤霄战魂,赤霄元神,赤霄法冠,赤霄法袍,赤霄战靴,赤霄护符','pic'=>'41.jpg')
,42=>array('name'=>'狼骨','gets'=>'冰晶河1','cls'=>'赤霄拳套,赤霄剑,赤霄弓,赤霄斧,赤霄杖','pic'=>'42.jpg')
,43=>array('name'=>'黑流玉','gets'=>'玄净寺13','cls'=>'赤霄战魂,赤霄元神','pic'=>'43.jpg')
,44=>array('name'=>'仙宝石','gets'=>'天镜11','cls'=>'天罡弓,天罡剑,天罡拳套,天罡杖,天罡锤,天罡护符,天罡法袍,天罡法冠,天罡战靴','pic'=>'44.jpg')
,45=>array('name'=>'火焰宝石','gets'=>'东瀛2','cls'=>'天罡元神,天罡战魂,,天罡护符,天罡法袍,天罡法冠,天罡战靴','pic'=>'45.jpg')
,46=>array('name'=>'玉杯','gets'=>'天镜2','cls'=>'天罡元神,天罡战魂,天罡弓,天罡剑,天罡拳套,天罡杖,天罡锤,天罡护符,天罡法袍,天罡法冠,天罡战靴','pic'=>'46.jpg')
,47=>array('name'=>'不灭灯','gets'=>'天镜15','cls'=>'天罡元神,天罡战魂,天罡弓,天罡剑,天罡拳套,天罡杖,天罡锤,天罡护符,天罡法袍,天罡法冠,天罡战靴','pic'=>'47.jpg')
,48=>array('name'=>'天仙花','gets'=>'跌水崖11','cls'=>'天罡元神,天罡战魂,天罡弓,天罡剑,天罡拳套,天罡杖,天罡锤,天罡护符,天罡法袍,天罡法冠,天罡战靴','pic'=>'48.jpg')
,49=>array('name'=>'绝色玉','gets'=>'东瀛11','cls'=>'天罡元神,天罡战魂','pic'=>'49.jpg')
,50=>array('name'=>'云纹金钗','gets'=>'跌水崖4','cls'=>'天罡弓,天罡剑,天罡拳套,天罡杖,天罡锤','pic'=>'50.jpg')
,51=>array('name'=>'蝎刺','gets'=>'水陆道场8','cls'=>'乾坤弓,乾坤剑,乾坤拳套,乾坤杖,乾坤锤,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'51.jpg')
,52=>array('name'=>'仙人刺','gets'=>'奈何桥5','cls'=>'乾坤元神,乾坤战魂,乾坤弓,乾坤剑,乾坤拳套,乾坤杖,乾坤锤,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'52.jpg')
,53=>array('name'=>'血凝爪','gets'=>'秦始皇陵1','cls'=>'乾坤元神,乾坤战魂,乾坤弓,乾坤剑,乾坤拳套,乾坤杖,乾坤锤,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'53.jpg')
,54=>array('name'=>'龙鳞','gets'=>'秦始皇陵10','cls'=>'乾坤元神,乾坤战魂,,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'54.jpg')
,55=>array('name'=>'一枝花','gets'=>'奈何桥15','cls'=>'乾坤元神,乾坤战魂,乾坤弓,乾坤剑,乾坤拳套,乾坤杖,乾坤锤,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'55.jpg')
,56=>array('name'=>'霸王鼎','gets'=>'秦始皇陵15','cls'=>'乾坤元神,乾坤战魂','pic'=>'56.jpg')
,57=>array('name'=>'妖果','gets'=>'水陆道场1','cls'=>'乾坤元神,乾坤战魂,,乾坤护符,乾坤法袍,乾坤法冠,乾坤战靴','pic'=>'57.jpg')







	);
    $hotkw = array(0=>"绿珠",1=>"念珠",2=>"不醉石",3=>"蓝宝石",4=>"紫幽羽",5=>"象龙角",6=>"玄天石",7=>"逍遥");
	$str = "{gvbhjnngfdew34drftvb:" . $json->encode($gvbhjnngfdew34drftvb) . ",sdf345g1df0235235:" . $json->encode($sdf345g1df0235235) . ",vwsq12sd7ft8bynoim099:" . $json->encode($vwsq12sd7ft8bynoim099). ",hotkw:" . $json->encode($hotkw) . "}";
	exit($str);
	
}
?>