<?php

$cfg_pay_status = array(0=>'未付款',1=>'已付款');
//$cfg_pay_type = array('prepay' => '0', 'alipay' => '1','bankpay' => '2');
//$cfg_order_pay_type = array( 0=>'预付款',1=>'支付宝', 2=>'银行汇款' );
//$cfg_order_source = array(0=>'前台订单', 1=>'后台订单');

$cfg_edu = array(
	1=>'初中以下',
	2=>'初中',
	3=>'高中|中专',
	4=>'大专',
	5=>'本科',
	6=>'硕士',
	7=>'博士以上'
);

$cfg_sex = array(
	1=>'先生',
	2=>'女士'
);

$cfg_member_status = array(
	0=>'未激活',
	1=>'已激活'
);

$cfg_game_type = array(
	1=>'战争策略',
	2=>'模拟经营',
	3=>'角色扮演',
	4=>'休闲竞技',
	5=>'武侠RPG',
	6=>'模版格斗'
);

//支付方式
$cfg_pay_type = array(
	'yeepay'=>'网银支付',
	'alipay'=>'支付宝',
	'yeepay_szx'=>'神州行充值',
	'yeepay_unicom'=>'联通卡充值',
	'yeepay_sndacard'=>'盛大卡充值',
	'yeepay_zhengtu'=>'征途卡充值',
	'yeepay_junnet_net'=>'骏网支付',
	'yeepay_jiuyou_net'=>'久游卡支付',
	'yeepay_wanmei_net'=>'完美卡支付',
	'yeepay_netease_net'=>'网易卡支付',
	'yeepay_qqcard_net'=>'Q币卡支付',
	'yeepay_telecom_net'=>'电信卡支付',
//	'vpay'=>'全国电话支付',
	'account'=>'平台币兑换'
	
);
//支付方式转换平台币比率 规则：1人民币转换多少平台币
$cfg_pay_rate = array(
	'alipay'=>10,
	'yeepay'=>10,
	'yeepay_szx'=>9,
	'yeepay_unicom'=>9,
	'yeepay_sndacard'=>8,
	'yeepay_zhengtu'=>8,
	'yeepay_junnet_net'=>8,
	'yeepay_jiuyou_net'=>8,
	'yeepay_wanmei_net'=>8.1,
	'yeepay_netease_net'=>8,
	'yeepay_qqcard_net'=>7,
	'yeepay_telecom_net'=>9,
//	'vpay'=>5,
	'account'=>1
);

//支付方式说明
$cfg_pay_desc = array(
	'yeepay'=>'只要您开通网上银行服务，足不出户即可实现快捷准确的帐号充值。请勿在此通道中使用其他方式充值。',
	'alipay'=>'阿里巴巴旗下专业支付平台。凡是拥有支付宝帐号的用户，都可以进行网上直充，方便快捷，同时支持网银。',
	'yeepay_szx'=>'支持中国移动全国发行的所有面额的神州行充值卡，无需手机，购买便捷。',
	'yeepay_unicom'=>'仅支持全国卡，卡号15位，密码19位，不支持地方充值卡。',
	'yeepay_sndacard'=>'仅支持卡号以CS或S加数字的卡号！最简单、普遍的充值方式，您可以在各地网吧、报刊亭购买任何一款盛大网络游戏卡，便可对帐号进行充值。',
	'yeepay_zhengtu'=>'凡是拥有征途卡的玩家可选此种方式进行充值，安全、有效、便捷，实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_junnet_net'=>'支持骏网一卡通实物卡、虚拟卡充值，实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_jiuyou_net'=>'支持久游实物卡、虚拟卡充值。实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_wanmei_net'=>'支持完美实物卡、虚拟卡充值。实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_netease_net'=>'支持网易实物卡、虚拟卡充值。实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_qqcard_net'=>'支持网易实物卡、虚拟卡充值。实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
	'yeepay_telecom_net'=>'支持网易实物卡、虚拟卡充值。实物卡可以通过各地网吧、电脑城、报刊亭等市面上购买。',
//	'vpay'=>'全国电话支付',
	'account'=>'平台币可以充值49you平台的任意一款游戏，可以通过推广返利和参加49you举办的活动　<a href=return.html >推广返利详情>></a>'
	
);


//支付方式帮助链接
$cfg_pay_help = array(
	'yeepay'=>'news_info_2600.html',
	'alipay'=>'news_info_2601.html',
	'yeepay_szx'=>'news_info_2602.html',
	'yeepay_unicom'=>'news_info_2603.html',
	'yeepay_sndacard'=>'news_info_2604.html',
	'yeepay_zhengtu'=>'news_info_2605.html',
	'yeepay_junnet_net'=>'news_info_2606.html',
	'yeepay_jiuyou_net'=>'news_info_2614.html',
	'yeepay_wanmei_net'=>'news_info_2608.html',
	'yeepay_netease_net'=>'news_info_2607.html',
	'yeepay_qqcard_net'=>'news_info_2615.html',
	'yeepay_telecom_net'=>'news_info_2613.html',
//	'vpay'=>'全国电话支付',
	'account'=>'news_info_2617.html'
	
);
//卡片状态
$cfg_card_timelimit = array(
	0=>"无限期",
	1=>"有限期"
);
$cfg_card_status = array(
	0=>"未领取",
	1=>"已领取"
);
//积分获取渠道
$cfg_integral_type = array(
	1=>"充值",
	2=>"邀请好友",
	3=>"好友充值"
);
//
$cfg_trans_status = array(
	0=>"未到账",
	1=>"已到账"
);
$cfg_onoff = array(
	0=>"已关闭",
	1=>"已开启"
);
$cfg_mainten_type = array(
	0=>"普通维护",
	1=>"内测"
);
$cfg_question = array(
	1=>"我的家乡在哪里？",
	2=>"我的父亲叫什么？",
	3=>"我的母亲叫什么？",
	4=>"我最喜欢是什么？",
	5=>"我最好的朋友叫什么？"
);

//广告模板
$cfg_adv_tpl = array(
	//'tpl_adv_flash'=>'游戏flash广告',
	//'tpl_adv_flash_ip'=>'游戏flash广告+统计',
	//'tpl_adv_flash_jschange'=>'游戏flash广告+自动换索材',
	'tpl_adv_flash_jschange_ip'=>'游戏flash广告+自动换索材+统计',
	//'tpl_adv_flash_jschange_ip_new'=>'游戏flash广告+自动换索材+统计（新）',
	//'tpl_adv_flash_sg'=>'微端游戏flash广告',
	'tpl_adv_flash_sg_jschange'=>'微端游戏flash广告+自动轮换素材+统计',
	'tpl_adv_flash_jschange_olduser'=>'游戏flash广告+轮换素材+统计+自动切换登录入口',
	//'tpl_adv_flash_game'=>'直接进游戏'
	'tpl_adv_flash_jschange_login'=>'游戏flash广告+换素材+统计+手动切换登陆入口'
);


//背投广告模板
$cfg_advbg_tpl = array(
	'tpl_advbg_flash'=>'背投flash广告',
	'tpl_advbg_flash_sample'=>'简易背投flash广告'
);
?>