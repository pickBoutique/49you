<?php
define('PERMI_CODE','adv_mgs');
include_once('init.inc.php');

$adv_cls = array(
	array('id' => '1','name'=>'游戏类'),
	array('id' => '2','name'=>'小说类'),
	array('id' => '3','name'=>'动漫类'),
	array('id' => '4','name'=>'音乐类'),
	array('id' => '5','name'=>'论坛类'),
	array('id' => '6','name'=>'IT下载类'),
	array('id' => '7','name'=>'电影视频类'),
	array('id' => '8','name'=>'美女图片类'),
	array('id' => '9','name'=>'八卦娱乐类'),
	array('id' => '10','name'=>'综合资讯类'),
	array('id' => '11','name'=>'公文写作类')
);


function get_system_key_by_id($pid){
	global $system;
	if(!empty($system)){
		foreach($system as $k=>$v){
			if($v['id'] == $pid){
				return $k;
			}
		}
	}
	return '';
}


if(empty($act)){
	$adv_id = intval($_REQUEST['adv_id']) ? intval($_REQUEST['adv_id']) : 0 ;
	$where = "and adv_id='".$adv_id."' ";
	$act = "add";
	if(!empty($adv_id))
	{
		$sql = "SELECT * FROM ".DB_PREFIX."adv WHERE 1 $where";
		$rs = $db_admin->getRow($sql);
		
		$act = "edit";
		include_once("templates/adv-cls.html");
	}
}else if($act == 'dataget'){
	$adv_id = intval($_REQUEST['adv_id']) ? intval($_REQUEST['adv_id']) : 0 ;
	$where = "and adv_id='".$adv_id."' ";
	if(!empty($adv_id))
	{
		$sql = "SELECT * FROM ".DB_PREFIX."adv WHERE 1 $where";
		$rs = $db_admin->getRow($sql);

		$cls = $rs['adv_cls'];
		
		$cls_json = $json->decode($cls,1);
		foreach($adv_cls as $k => $v){
			$adv_cls[$k]['value'] = '0';
			$adv_cls[$k]['title'] = '';
			if(!empty($cls_json)){
				foreach($cls_json as $key => $val){
					if($v['id'] == $val['id']){
						$adv_cls[$k]['value'] = intval($val['value']);
						$adv_cls[$k]['title'] = !empty($val['title']) ? $val['title'] : '';
					}
				}
			}
			$url = $rs['adv_url'].'?cls='.$v['id'];
			$adv_cls[$k]['url'] = $url;
		}
	}
	$record_count = sizeof($adv_cls);
	exit( get_list_json($adv_cls, $record_count ) );

}else if($act == 'editor'){
	//$arr = array('adv_sort','adv_metrid','adv_metrid1','adv_metrid2','adv_title','adv_title1','adv_title2');
    $name = $_REQUEST['name'];
	$id = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	$advid = $_REQUEST['advid'];
	$sql = "SELECT * FROM ".DB_PREFIX."adv WHERE 1 and adv_id=$advid ";
	$rs = $db_admin->getRow($sql);
	if(!empty($rs)){
		$cls = $rs['adv_cls'];
	
		$cls_json = $json->decode($cls,1);
		foreach($adv_cls as $k => $v){
			if(!empty($cls_json)){
				foreach($cls_json as $key => $val){
					if($v['id'] == $val['id']){
						$adv_cls[$k]['value'] = intval($val['value']);
						$adv_cls[$k]['title'] = !empty($val['title']) ? $val['title'] : '';
					}
				}
			}
				
			if($v['id']==$id){
				$adv_cls[$k][$name] = $value;
			}
		}
		$cls_json = $json->encode($adv_cls);
	
		$row = array();
		$row['adv_cls'] = $cls_json;
		$ret = $db_admin->update($row,DB_PREFIX."adv",$advid);
		
		
		if($ret)
		{	
			exit('1');
		}
		else
		{
			exit("E@修改失败");
		}
	}
	
}

?>