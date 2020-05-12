<?php
/*
creater devil 2010-08-16
*/


include_once('init_web.inc.php');
ob_start();

$rt_arr = array(0=>'country',1=>'province',2=>'city',3=>'town');
$rt1_arr = array(0=>'国家',1=>'省份',2=>'市',3=>'区');
$parent_id = intval($_REQUEST['parent_id']);
$id = intval($_REQUEST['id']);
$rt = intval($_REQUEST['rt']);
if(empty($parent_id )){
	$parent_id = '-1';
}
$rs = getRegions($parent_id);
if($rs)
{
	if($rt < 3){$onchange = " onchange=\"getRegions('".$rt_arr[$rt+1]."_label',this.value,'".($rt+1)."')\" ";}else{$onchange = "";}
	echo "<select name=\"area_".$rt_arr[$rt]."\" id=\"area_".$rt_arr[$rt]."\" $onchange>\n";
	//echo "<option value=\"\">".$rt1_arr[$rt]."</option>\n";
	foreach($rs as $v)
	{
		if($id == $v['region_id']){$selected = " selected=\"selected\" ";}else{$selected = "";}
		echo "<option value=\"".$v['region_id']."\" en=\"".$v['region_en']."\"  pscode=\"".$v['region_pscode']."\"  tel=\"".$v['region_tel']."\"  $selected>".$v['region_name']."</option>\n";
	}
	echo "</select>\n";
}
?>
