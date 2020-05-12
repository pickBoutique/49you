<?php
/* 站点配置项	*/
$cfg_indexset = array(
	'slide_bigimg' => array(
			'name' => '首个幻灯片大图',
			'type' => 'img_upload'
	),
	'slide_smallimg' => array(
			'name' => '首个幻灯片小图',
			'type' => 'img_upload'
	),
	'slide_newsid' => array(
			'name' => '即将开服新闻id',
			'type' => 'text',
			'maxlen' => '50'
	),
	'slide2_bigimg' => array(
			'name' => '第二个幻灯片大图',
			'type' => 'img_upload'
	),
	'slide2_smallimg' => array(
			'name' => '第二个幻灯片小图',
			'type' => 'img_upload'
	),
	'slide2_newsid' => array(
			'name' => '第二个即将开服新闻id',
			'type' => 'text',
			'maxlen' => '50'
	),
	'left_img1' => array(
			'name' => '左边栏图片1',
			'type' => 'img_upload'
	),
	'left_img1_link' => array(
			'name' => '左边栏图片1链接',
			'type' => 'text',
			'maxlen' => '250'
	),
	'left_img2' => array(
			'name' => '左边栏图片2',
			'type' => 'img_upload'
	),
	'left_img2_link' => array(
			'name' => '左边栏图片2链接',
			'type' => 'text',
			'maxlen' => '250'
	),
	'enabled_header_flash' => array(
			'name' => '是否启用顶部flash栏位',
			'type' => 'select',
			'options' => array(
					'0' => '关闭',
					'1' => '开启'
			)
	),
	'header_flash' => array(
			'name' => '顶部flash',
			'type' => 'img_upload',
			'width' => '500',
			'height' => '100'
	),
	'header_flash_link' => array(
			'name' => '顶部flash链接',
			'type' => 'text',
			'maxlen' => '250'
	),
	'enabled_floor_flash' => array(
			'name' => '是否启用浮动flash栏位',
			'type' => 'select',
			'options' => array(
					'0' => '关闭',
					'1' => '开启'
			)
	),
	'header_floor' => array(
			'name' => '浮动flash',
			'type' => 'img_upload',
			'width' => '250',
			'height' => '250'
	),
	'header_floor_link' => array(
			'name' => '浮动flash链接',
			'type' => 'text',
			'maxlen' => '250'
	),
	'header_floor_width' => array(
			'name' => '浮动flash宽度（默认120）',
			'type' => 'text',
			'maxlen' => '250'
	),
	'header_floor_height' => array(
			'name' => '浮动flash高度（默认300）',
			'type' => 'text',
			'maxlen' => '250'
	),
	'kaifu_news' => array(
			'name' => '平台-游戏动态-开服置顶新闻ID',
			'type' => 'text',
			'maxlen' => '250'
	)
	
);
?>