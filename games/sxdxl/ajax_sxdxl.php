<?php
require("include/json.class.php");
$act=$_REQUEST["act"];
$json = new JSON();
if($act=="getdate"){
	$xlinfo = array(0=>array('question'=>'传闻此处出现了一妖艳女子,吸引了众多散仙与她饮酒作乐,夜夜笙歌｡','opt1'=>'与众人一同狂欢!','opt2'=>'驱散众散仙,与她独饮!','prize1'=>'1000+等级×200铜钱,10声望','prize2'=>'1000+等级×100铜钱,5体力')
,1=>array('question'=>'传闻此地留有上古秘宝,有缘者方能得之｡','opt1'=>'纠集仙友,前往寻宝｡','opt2'=>'独自前往寻宝｡','prize1'=>'1000+等级×200铜钱,30声望','prize2'=>'1000+等级×200铜钱,10阅历')
,2=>array('question'=>'此地出现了一具强者骨骸,据闻此骨骸之中暗藏成仙之法,只是至今未有人参透其中奥秘｡','opt1'=>'以火炼之,必能得知秘密!','opt2'=>'什么奥秘?纯属扯淡!','prize1'=>'1000+等级×100铜钱,30阅历','prize2'=>'1000+等级×200铜钱,5体力')
,3=>array('question'=>'此地突然出现兽潮来袭,前往镇压兽潮修仙道友大都丧命其中｡','opt1'=>'遇佛杀佛,遇鬼杀鬼,誓要镇压兽潮!','opt2'=>'退避三舍,为死去的仙友默哀!','prize1'=>'1000+等级×200铜钱,50声望','prize2'=>'1000+等级×300铜钱,10阅历')
,4=>array('question'=>'传闻此地有四种草药,凤尾花､生灵骨草､茯苓青､炎龙金花,只要将其中几颗草药混合便能炼制成各种仙药,有起死回生之效｡','opt1'=>'凤尾花与生灵骨草混合炼制成融灵丹｡','opt2'=>'茯苓青与炎龙金花混合炼制玄龙丹','prize1'=>'铜钱,30阅历','prize2'=>'铜钱,50阅历')
,5=>array('question'=>'传闻此地有一隐世强者,如果他要收徒,将会有无数修仙者前来拜师!他觉得你资质极高,想收你为徒｡','opt1'=>'天降良师,马上叩拜!','opt2'=>'老子天资极高,无须拜师','prize1'=>'1000+等级×300铜钱,10声望','prize2'=>'1000+等级×300铜钱,30声望')
,6=>array('question'=>'魔将奉先与神将天蓬正在此地决战,胜者将会获得心爱之人的青睐｡','opt1'=>'帮助他们其中一方痛扁对方｡','opt2'=>'以一敌二,抱得美人归!','prize1'=>'1000+等级×300铜钱,10声望','prize2'=>'1000+等级×300铜钱,10阅历')
,7=>array('question'=>'妖道李易居然在此地东山再起,并修得比之前更加邪恶的魔功,准备找你复仇,你再次打败了他｡','opt1'=>'这次杀了他！','opt2'=>'再放他一马！','prize1'=>'1000+等级×100铜钱,10声望','prize2'=>'1000+等级×100铜钱,50声望')
,8=>array('question'=>'此地出现了一个巨大的蛹,它正准备羽化成蝶,可是在破开蛹的瞬间好似卡住了,不断挣扎着要出来｡','opt1'=>'以剑劈开,助它一臂之力｡','opt2'=>'放任其自生自灭｡','prize1'=>'1000+等级×200铜钱,50阅历','prize2'=>'1000+等级×300铜钱,50阅历')
,9=>array('question'=>'此地出现了传闻中的试剑神石,只要能够将自己的武器穿透神石,便能够进化为神器｡','opt1'=>'信以为真,运足真气,力透神石｡','opt2'=>'嗤之以鼻,抽身返回｡','prize1'=>'1000+等级×300铜钱,10阅历','prize2'=>'铜钱,阅历')
,10=>array('question'=>'传闻上古大阵化魔阵由于此地魔气大盛而重新出现,只要进入大阵中,便能成魔,拥有魔力｡','opt1'=>'毁去魔阵,阻止众人堕入魔道｡','opt2'=>'心志甚坚,即使进入魔阵,也能保持本性｡','prize1'=>'1000+等级×100铜钱,50阅历','prize2'=>'1000+等级×200铜钱,30阅历')
,11=>array('question'=>'此地出现了巨蟒兽,可是似乎还未完全化形,却早已搞得此处乌烟瘴气｡','opt1'=>'趁其未化形前收服它｡','opt2'=>'等其化形再行收服｡','prize1'=>'1000+等级×300铜钱,30声望','prize2'=>'1000+等级×100铜钱,5体力')
,12=>array('question'=>'此处出现了古籍《五芒星术法》残本,世人纷纷猜测其残缺的术法｡','opt1'=>'从左到右召唤火之芒星｡','opt2'=>'从上到下召唤水之芒星｡','prize1'=>'1000+等级×300铜钱,50声望','prize2'=>'10阅历,5体力')
,13=>array('question'=>'被散仙张氏封印百年后重生的猫妖,附生在一健壮男子身上作恶｡','opt1'=>'瞬间杀死此人,可毁猫妖元神｡','opt2'=>'以法术花费七七四十九天,炼化猫妖元神,保全此人性命｡','prize1'=>'30阅历,5体力','prize2'=>'1000+等级×200铜钱,20声望')
,14=>array('question'=>'风伯和雨师本为夫妻,却因意见不合,在此天人交战,刮风下雨,闹得百姓不得安宁｡','opt1'=>'以一敌二,狠狠教训他们｡','opt2'=>'好言相劝,息事宁人｡','prize1'=>'1000+等级×200铜钱,30声望','prize2'=>'1000+等级×100铜钱,40声望')
,15=>array('question'=>'魔道老人,传说中的魔界强者,却陨落于此地,只剩下虚无的元神游荡此间｡','opt1'=>'不辞辛苦,帮他重塑肉身｡','opt2'=>'坚守除妖灭魔大道,一招毁灭之｡','prize1'=>'1000+等级×100铜钱,30声望','prize2'=>'1000+等×100铜钱,10阅历')
,16=>array('question'=>'天界打神鞭误落此地,传闻此神器可打出世间生灵的善念与邪念｡','opt1'=>'用此鞭打出善念,让世人拥有善念｡','opt2'=>'用此鞭打出邪念,让世人拥有邪念｡','prize1'=>'10声望,30阅历','prize2'=>'1000+等级×200铜钱,50阅历')
,17=>array('question'=>'远处黑不见底的洞窟中传来一道凄厉的鬼叫声｡','opt1'=>'进入洞窟查看究竟｡','opt2'=>'转身回去,以免危险｡','prize1'=>'1000+等级×100铜钱,10阅历','prize2'=>'1000+等级×100铜钱,30声望')
,18=>array('question'=>'你误入九宫仙阵,需破解此阵,方能离开｡','opt1'=>'剑指乾坤,出走生门｡','opt2'=>'气吞离兑,出走休门｡','prize1'=>'1000+等级×300铜钱,5体力','prize2'=>'1000+等级×200铜钱,50声望')
,19=>array('question'=>'妖徒自持妖法高明,又善玄功变化,身外化身,炼就三尸元神,魂魄均可分化,任何厉害的飞剑法宝俱不能伤｡','opt1'=>'施展浑身解数消灭之｡','opt2'=>'以万千念力感化之｡','prize1'=>'1000+等级×200铜钱,30声望','prize2'=>'1000+等级×300铜钱,30阅历')
,20=>array('question'=>'魑魅魍魉,当年蚩尤魔头的四大家将,专喜吃美人,封印被破解,又出来毒害天下美人｡','opt1'=>'以龙之角做成战斗号角,吓走它们｡','opt2'=>'将东施抓来,吓死它们｡','prize1'=>'1000+等级×100铜钱,30阅历','prize2'=>'10声望,50阅历')
,21=>array('question'=>'偶遇仙女灵儿下凡寻找她成仙之前的恩人,要将仙家宝物紫金玉箫赠予他,以报大恩｡','opt1'=>'冒充恩人,骗取紫金玉箫｡','opt2'=>'历尽千辛万苦,助其寻到恩人｡','prize1'=>'1000+等级×300铜钱,50阅历','prize2'=>'1000+等级×100铜钱,20阅历')
,22=>array('question'=>'此地突然连下三年暴雨,以致洪水泛滥,百姓流离失所,苦不堪言｡','opt1'=>'率领百姓,祭拜天神,祈求上天不要再下雨了｡','opt2'=>'率领百姓,以仙法开路,再创大禹功业｡','prize1'=>'1000+等级×200铜钱,40阅历','prize2'=>'1000+等级×200铜钱,10声望')
,23=>array('question'=>'偶入一强者墓穴,发现已有众多散仙在此寻宝｡','opt1'=>'结伴寻宝,见机行事｡','opt2'=>'独自寻宝,机会多多｡','prize1'=>'30声望,5体力','prize2'=>'1000+等级×200铜钱,30阅历')
,24=>array('question'=>'此地有一魔剑,其中住着一位怨灵,口口声声喊着要复仇,原来他生前遭遇仇家灭门之祸｡','opt1'=>'正义感泛滥,助其寻找仇家,帮其复仇｡','opt2'=>'冤冤相报何时了,不如劝其投胎去｡','prize1'=>'10声望,10阅历','prize2'=>' 10声望,,5体力')
,25=>array('question'=>'此地出现女娲大神造人剩下的七彩神土,传闻使用神土可创造第三种人类｡','opt1'=>'创造第三人类,自诩为第三人类始祖｡','opt2'=>'遵守天地法则,封印神土,以免人间大乱｡','prize1'=>'50声望,30阅历','prize2'=>'1000+等级×300铜钱,30阅历')
,26=>array('question'=>'此地出现一颗早已具备灵智的丹药,传闻只要服下此丹药,便可飞升成仙｡','opt1'=>'强行抓拿,服下灵丹,等待飞升｡','opt2'=>'拘禁灵丹,日夜吸收灵气,提高修为｡','prize1'=>'1000+等级×100铜钱,10阅历','prize2'=>'30声望,50阅历')
,27=>array('question'=>'孤寂的帝俊天神,下凡到此寻找真心朋友｡','opt1'=>'与他成为好友,谈论人间趣事｡','opt2'=>'人神不可能成为朋友,避而不见｡','prize1'=>'30声望,30阅历','prize2'=>' 1000+等级×200铜钱,30声望')
,28=>array('question'=>'妖兽九婴生于天地初分之时,九头蛇身,再次作乱人间,三派悬赏高手前往击毙它,只因九婴有九个头,不知哪个方才是其要害?','opt1'=>'九婴正中巨头为其要害,一剑砍下,必能毙命｡','opt2'=>'以大神通一招秒杀九头,三派高手无不惊讶万分｡','prize1'=>'1000+等级×200铜钱,10阅历','prize2'=>'1000+等级×100铜钱,40阅历')
,29=>array('question'=>'误入"无限领域",此地乃仙之隔绝空间,唯有打败自己的分身,方能出来｡','opt1'=>'凝神与分身作战,竭尽全力打败分身｡','opt2'=>'主动认输,不干自相残杀之事｡','prize1'=>' 1000+等级×200铜钱,10阅历','prize2'=>'1000+等级×300铜钱,5体力')
,30=>array('question'=>'苍角长老正在此地渡劫飞升,天雷不断落下,眼见苍角长老就快撑不住了｡','opt1'=>'耗尽真元,助其一臂之力','opt2'=>'放任不管,成败皆有定数','prize1'=>'1000+等级×300铜钱,30声望','prize2'=>'1000+等级×200铜钱,10阅历')
,31=>array('question'=>'此处方圆百里之内的百姓纷纷中毒,神医华脱脱找到了一味可炼制解药的草药,只是不知草药本身有没毒性｡','opt1'=>'以身试毒,拯救苍生｡','opt2'=>'生命可贵,拒绝不必要的危险｡','prize1'=>'1000+等级×100铜钱,20声望','prize2'=>'1000+等级×300铜钱,10阅历')
,32=>array('question'=>'天地灵木玉神树,千年可产一次凝神汁,恰巧被你碰上这千年一次的机会,却有一只灵兽金毛猿守护着｡','opt1'=>'除掉守护灵兽,夺取凝神汁｡','opt2'=>'灵兽过于强大,遗憾的放弃｡','prize1'=>'50声望,50阅历','prize2'=>'1000+等级×100铜钱,10声望')
,33=>array('question'=>'仙家宝物昆仑镜具有穿梭时空之神通,不知流落何处,却在此地被你碰上｡','opt1'=>'开启昆仑镜,回到过去｡','opt2'=>'开启昆仑镜,穿越未来｡','prize1'=>'1000+等级×200铜钱,20阅历','prize2'=>'1000+等级×300铜钱,50阅历')
,34=>array('question'=>'偶然碰到被人追杀的母女二人,母亲体内经脉尽断,命不久矣,临死前将女儿托付给你｡','opt1'=>'收容此女,常伴左右｡','opt2'=>'送到门派中习武｡','prize1'=>'1000+等级×100铜钱,5体力','prize2'=>'1000+等级×300铜钱,40声望')
,35=>array('question'=>'此地突然涌出一道不老泉源,传闻饮用此泉水便能够长生不老,但却会从此失去修为,沦落为普通人｡','opt1'=>'饮用此不老泉水,做个不死凡人｡','opt2'=>'凭努力修炼成仙,同样能长生不老｡','prize1'=>'1000+等级×300铜钱,20阅历','prize2'=>'1000+等级×200铜钱,10声望')
,36=>array('question'=>'鬼谷子传人在此开山收徒,传授奇门遁甲,玄道法术｡','opt1'=>'拜师学艺,练就一身奇术｡','opt2'=>'专心修仙之道,不走歪路｡','prize1'=>'1000+等级×100铜钱,30阅历','prize2'=>'1000+等级×300铜钱,40阅历')
,37=>array('question'=>'桃源悠悠,仙气弥漫,人生几何,常有美酒佳肴相伴,在此一生何憾｡','opt1'=>'痛饮三百杯,醉卧桃花林｡','opt2'=>'修仙不二心,屏气而退｡','prize1'=>'1000+等级×200铜钱,40阅历','prize2'=>'：1000+等级×100铜钱,30阅历')
,38=>array('question'=>'百鬼夜行,你误入鬼地,百鬼环绕身旁｡','opt1'=>'驱赶百鬼回归鬼界｡','opt2'=>'斩妖除鬼,立杀无赦｡','prize1'=>'1000+等级×300铜钱,40声望','prize2'=>'1000+等级×200铜钱,50阅历')
,39=>array('question'=>'神兽水麒麟在此寻找能够让其重生的有缘之人,你便是它苦寻千年之有缘人｡','opt1'=>'大发善心,助其重生｡','opt2'=>'天地循环,劝其去鬼界轮回重生｡','prize1'=>'1000+等级×100铜钱,5体力','prize2'=>'1000+等级×300铜钱,30声望')
,40=>array('question'=>'《天极道法》仙家至强秘法,居然出现在此地,但是欲修此功,却必先废掉原先所学｡','opt1'=>'不顾一切,废掉修为,重新修炼此秘法｡','opt2'=>'忍痛不修,赠予门派｡','prize1'=>'1000+等级×300铜钱,50阅历','prize2'=>'1000+等级×300铜钱,50声望')
,41=>array('question'=>'此地出现了神兽金乌三足鸟,传闻每过五百年,便会多长出一只足,但却须吃人的心脏补充消耗的能量｡','opt1'=>'阻止其祸害人命｡','opt2'=>'耗尽真气助其补充能量｡','prize1'=>'1000+等级×300铜钱,5体力','prize2'=>'1000+等级×300铜钱,50声望')
,42=>array('question'=>'远处突然传来一阵悲鸣声,原来是只小青鸾鸟,它说它的父母青鸾火凤被王母囚禁,可恨自己没有能力救出父母｡','opt1'=>'喝斥小青鸾,王母岂是无情神｡','opt2'=>'悲愤异常,带着小青鸾找王母理论｡','prize1'=>'1000+等级×100铜钱,10声望','prize2'=>'1000+等级×200铜钱,20声望')
,43=>array('question'=>'灵山宝地出现了百草液,传闻此草蕴含人体精气,只要服下,便可修为精进｡','opt1'=>'马上服下,提高修为｡','opt2'=>'修炼无须倚靠外物｡','prize1'=>'1000+等级×300铜钱,50声望','prize2'=>'1000+等级×200铜钱,30声望')
,44=>array('question'=>'前方的山地突然塌陷,变成一个无底深渊,里面深不见底,不知有多少怪物,多少宝物｡','opt1'=>'为了宝物,何惧之有,闯入无底深渊｡','opt2'=>'等修为足够再行前来寻宝｡','prize1'=>'1000+等级×100铜钱,5体力','prize2'=>'30声望,10阅历')
,45=>array('question'=>'路遇坠入魔道的原本门高手赤炼子｡','opt1'=>'正邪不容,擒回去给掌门处置｡','opt2'=>'好言相劝其回归正道｡','prize1'=>'50阅历,5体力','prize2'=>'1000+等级×100铜钱,40声望')
,46=>array('question'=>'此地有一游方道士专门帮人算命,传闻此道士乃"铁齿神算,算无遗策｡"','opt1'=>'让其算上一卦,恭敬按其卦言行事｡','opt2'=>'喝斥其不要在此骗人,大打出手｡','prize1'=>'1000+等级×300铜钱,50声望','prize2'=>'1000+等级×200铜钱,50阅历')
,47=>array('question'=>'突然间风雷交加,电闪雷鸣,原来有一妖物愤恨天道无常,让其修炼千年仍然不得化形｡','opt1'=>'替天行道,除掉此妖物｡','opt2'=>'炼制丹药,助其化形｡','prize1'=>'50声望,10阅历','prize2'=>'1000+等级×100铜钱,30阅历')
,48=>array('question'=>'此地突然出现一头巨大的妖龙,传闻此妖龙曾一夜之间吃掉一千个人,故而天界神将都誓要抓其回去受刑｡','opt1'=>'调查妖龙身世,确认其罪行方才动手捉拿｡','opt2'=>'直接捉拿此妖龙,交给天将｡','prize1'=>'1000+等级×100铜钱,30阅历','prize2'=>'50声望,5体力')


	);

	$str = "{xlinfo:" . $json->encode($xlinfo). "}";
	exit($str);
	
}
?>