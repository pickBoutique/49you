<?php
/*
Creater Jason 2011-01-21
*/

if($area_province)
{
	$sql = "SELECT * FROM ".DB_PREFIX."region WHERE region_id='".$area_province."' ";
	$db->query($sql);
	$rs_region1 = $db->get_data();
	if($rs_region1){$area_province_cn = $rs_region1[0]['region_name'];}
}
if($area_city)
{
	$sql = "SELECT * FROM ".DB_PREFIX."region WHERE region_id='".$area_city."' ";
	$db->query($sql);
	$rs_region2 = $db->get_data();
	if($rs_region2){$area_city_cn = $rs_region2[0]['region_name'];}
}
if($area_town)
{
	$whereSql .= " AND region_id='".$area_town."' ";
	$sql = "SELECT * FROM ".DB_PREFIX."region WHERE region_id='".$area_town."' ";
	$db->query($sql);
	$rs_region3 = $db->get_data();
	if($rs_region3){$area_town_cn = $rs_region3[0]['region_name'];}
}
$area_cn = $area_province_cn.' - '.$area_city_cn.' - '.$area_town_cn;

?>

