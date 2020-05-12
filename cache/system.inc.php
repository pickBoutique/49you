<?php
$system = array(
	'49you' => array(
		'id' => '5',
		'name' => '49you',
		'master' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => 'game_com',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'salve' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => 'game_com',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'ADV_HTTP' => 'http://web.d8pk.com',
		'CHE_HTTP' => 'http://www.d8pk.com/reg_adv.html',
		'LOG_HTTP' => 'http://www.d8pk.com/login.html?act=login',
		'GAM_HTTP' => 'http://www.d8pk.com/game_add.html?gid={$gid}&sid={$sid}',
		'REG_HTTP' => 'http://www.d8pk.com/reg_adv.html'
	),
	'56uu'  => array(
	    'id' => '2',
	    'name' => '56uu',
		'master' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => '56uu',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'salve' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => '56uu',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'ADV_HTTP' => 'http://56game.adv.com',
		'CHE_HTTP' => 'http://www.56uu.com/ywebads_regs_you.php',
		'LOG_HTTP' => 'http://www.56uu.com/?m=user&action=loginform',
		'GAM_HTTP' => 'http://www.56uu.com/?m=play&action={$gcode}play&sid={$snum}',
		'REG_HTTP' => 'http://www.56uu.com/ywebads_regs_you.php'
	),
	'joy400'=> array(
	    'id' => '1',
	    'name' => '快乐营',
		'master' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => 'joy400',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'salve' => array(
			'DB_HOST' => 'www.d8pk.com',
			'DB_DATABASE' => 'joy400',
			'DB_USER' => 'game_com',
			'DB_PASSWORD' => 'game_com_2011',
			'DB_PREFIX' => 'online_',
			'DB_CHARSET' => 'utf-8'
		),
		'ADV_HTTP' => 'http://jgame.adv.com',
		'CHE_HTTP' => 'http://www.joy400.com/ywebads_regs_you.php',
		'LOG_HTTP' => 'http://www.joy400.com/?m=user&action=loginform',
		'GAM_HTTP' => 'http://www.joy400.com/?m=game&game_id={$gid}&district_id={$sid}',
		'REG_HTTP' => 'http://www.joy400.com/ywebads_regs_you.php'
	)
);


?>