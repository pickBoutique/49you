<?php
    header("Content-type: application/vnd.ms-excel; charset=GB2312");
	header("Content-Disposition: attachment; filename=huyi.xls");
	include_once('init.inc.php');
	$buycar_id = intval($_REQUEST['buycar_id'])>0?intval($_REQUEST['buycar_id']):0 ;
	$checkbox_buycar_id = $_REQUEST['checkbox_buycar_id'] ;
	if($buycar_id)
	{
		$where = " and buycar_id='".$buycar_id."' ";
	}
	else
	{
		$buycar_id = implode(',',$checkbox_buycar_id);
		$where = " and buycar_id in(".$buycar_id.") ";
	}
	//var_dump($buycar_id);exit;
	if($buycar_id)
	{
	    $sql = "SELECT * FROM ".DB_PREFIX."buycar WHERE 1 $where";
		//var_dump($sql);exit;
		$query = $db->query($sql);
		$rs = $db->get_data();
		if($rs)
		{
	
	    //文件标题 
	    echo iconv('UTF-8', 'GB2312', '互易注册平台导出信息') . "\t\n";
	
	    //产品信息 
	    echo iconv('UTF-8', 'GB2312', '产品名称') . "\t";
	    echo iconv('UTF-8', 'GB2312', '产品类型') . "\t";
	    echo iconv('UTF-8', 'GB2312', '产品价格') . "\t";
	    echo iconv('UTF-8', 'GB2312', '产品编号') . "\t";
	    echo iconv('UTF-8', 'GB2312', '购买容量') . "\t";
	    echo iconv('UTF-8', 'GB2312', '用户数') . "\t";
	    echo iconv('UTF-8', 'GB2312', '购买年限') . "\t";
	    echo iconv('UTF-8', 'GB2312', '产品状态') . "\t";
	    echo iconv('UTF-8', 'GB2312', '开通时间') . "\t";
	    echo iconv('UTF-8', 'GB2312', '到期时间') . "\t\n";
	
	    foreach ($rs AS $key => $list)
	    {
			switch($list['buy_status'])
			{
			    case 0:
				$buy_status="正常";
				break;
				case 1:
				$buy_status="暂停";
				break;
				case 2:
				$buy_status="催费";
				break;
				case 3:
				$buy_status="过期";
				break;
			}
			echo iconv('UTF-8', 'GB2312', $list['product_name']) ."\t";
	        echo iconv('UTF-8', 'GB2312', $list['product_cate_name']) ."\t";
	        echo iconv('UTF-8', 'GB2312', $list['price']) . "\t";
	        echo iconv('UTF-8', 'GB2312', $list['product_code'] ) . "\t";
	        echo iconv('UTF-8', 'GB2312', $list['buy_capacity']) . "\t";
	        echo iconv('UTF-8', 'GB2312', $list['product_rule']) . "\t";
	        echo iconv('UTF-8', 'GB2312', $list['buy_year']) . "\t";
	        echo iconv('UTF-8', 'GB2312', $buy_status) . "\t";
	        echo iconv('UTF-8', 'GB2312', date('Y-m-d H:i:s', $list['start_time'])) . "\t";
	        echo iconv('UTF-8', 'GB2312', date('Y-m-d H:i:s', $list['end_time'])) . "\t";
	        echo "\n";
	    }

    }
}
?>
