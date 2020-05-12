<?php
/*
creater devil 2010-08-16
*/
define('PERMI_CODE','pro_mgs');
include_once('init.inc.php');

if('attribute' == $act)
{
	$cate_id = intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : 0 ;
	if($cate_id)
	{
		$sql = "SELECT attribute FROM ".DB_PREFIX."productcate WHERE cate_id='".$cate_id."' ";
		$query = $db->query($sql);
		$rs = $db->get_data();
		if($rs)
		{
			$attribute = $rs[0]['attribute'];
		}
	}
?>
	促销价格：<input type="text" name="promotion_price" id="promotion_price" value="<?=$promotion_price?>" maxlength="10" class="input_w100" />&nbsp;元<br/>
	<?php
	if(ereg(',1,',','.$attribute.','))
	{
	?>
	年　　限：<input type="text" name="promotion_years" id="promotion_years" value="<?=$promotion_years?$promotion_years:"1"?>" maxlength="10" class="input_w100" />&nbsp;年<br/>
	<?php
	}
	if(ereg(',2,',','.$attribute.','))
	{
	?>
	用 户 数：<input type="text" name="promotion_users" id="promotion_users" value="<?=$promotion_users?>" maxlength="10" class="input_w100" />&nbsp;用户<br/>
	<?php
	}
	if(ereg(',3,',','.$attribute.','))
	{
	?>
	容　　量：<input type="text" name="promotion_disk" id="promotion_disk" value="<?=$promotion_disk?>" maxlength="10" class="input_w100" />&nbsp;M
	<?php
	}
	?>
<?php
}
?>