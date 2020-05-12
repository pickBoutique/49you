<?php
define('PERMI_CODE','report_server_netstat_msg');
define('CONN_SALVE',true);
include_once('init.inc.php');


if($act == 'dataget'){
	$curdate = time(); //统计日期及时间默认为当前
	$mode = 'h';   //视图 默认为小时
	$unit = 'KB';  //计量单位 KB/MB 默认为 KB
	$dir = 'total';   //方向 up 发送 down 接收  total 全部
	$server_ip = array(
		'adva'=>'222.187.222.34',
		'advb'=>'222.187.222.42',
		'advc'=>'60.18.150.67',
		'advd'=>'222.187.222.19',
		'adve'=>'123.134.95.172',
		'advf'=>'125.77.197.137',
		'advg'=>'222.187.222.20',
		'advh'=>'60.18.150.68',
		'advi'=>'60.18.150.69'
	);
	
	if(!empty($_REQUEST['filter'])){
		$ret = $json->decode($_REQUEST['filter'],1);
		foreach ($ret as $k => $sub ){
			if($sub['name']=='date'){
				$curdate = $sub['value'];
			}
			if($sub['name']=='mode'){
				$mode = $sub['value'];
			}
			if($sub['name']=='hour'){
				$curdate = strtotime(date('Y-m-d '.$sub['value'].':i:s',intval($curdate)));
			}
			if($sub['name']=='unit'){
				$unit = $sub['value'];
			}
			if($sub['name']=='dir'){
				$dir = $sub['value'];
			}
		}
	}
	
	$column = 'netstat_up_total+netstat_down_total';
	if($dir=='up'){
		$column = 'netstat_up_total';
	}
	if($dir=='down'){
		$column = 'netstat_down_total';
	}
	
	require WEB_ROOT."/include/OFC/OFC_Chart.php";
	//设置图表数据
	$chart = new OFC_Chart();
	//初始化标题
	$title = new OFC_Elements_Title("服务器流量统计");
	$chart->set_title( $title );
	//初始化柱形图
	
	$rate = ($unit == 'KB' ? 1000 : (1000 * 1000));
	
	if($mode=='h'){
	
		$chartsdata = array();
		
		$list_server = $db_salve->getAll("select netstat_server from ".DB_PREFIX."netstat group by netstat_server");
		foreach($list_server as $k=>$v){
			$chartsdata[$v['netstat_server']] = array();
		}
		$date = array();
		$max=0;
		for($i=0;$i<24;$i++){
			$date[] = $i;
			$start = strtotime(date('Y-m-d '.$i.':00:00',$curdate));
			$end = strtotime(date('Y-m-d '.$i.':59:59',$curdate));
			
		
			
			$list = $db_salve->getAll("select sum($column) as total,netstat_server from ".DB_PREFIX."netstat where  netstat_time >= $start and netstat_time <= $end group by netstat_server");
			foreach($list_server as $key=>$val){
				
				$total = 0;
				if(!empty($list)){
				foreach($list as $k=>$v){
					if($val['netstat_server'] == $v['netstat_server']){
						$total = $v['total'] / $rate ;
						if($total>$max){
							$max = $total;
						}
					}
				}
				}
				
				$chartsdata[$val['netstat_server']][]=$total;
			}
		}
		
	
		
		$color = array('#D54C78','#35ECC8','#154CD8','#354C18','#B51CF8','#954C48','#F56C48','#45DC78','#E5CC18');
		$i=0;
		foreach($chartsdata as $k => $arr){
			$bar = new OFC_Charts_Line_Dot();
			$bar->set_values( $arr );
			$bar->set_key('服务器'.$k."($server_ip[$k])",'12');
			$bar->set_tooltip($k."(#val#)$unit");
			$bar->colour = $color[$i];
			$chart->add_element( $bar );
			$i=$i+1;
		}
		
	
		//初始化X轴
		$x_axis = new OFC_Elements_Axis_X();
		$x_axis->set_3d( 3 ); //立体效果
		$x_axis->colour = '#909090';
		$x_axis->set_offset( true );
		$x_axis->set_labels( $date );
		//$x_axis->set_labels_from_array( $date );
		$chart->set_x_axis( $x_axis);
		
		$max += intval($max * 0.1) > 1 ? intval($max * 0.1) : 1 ;
		$sept = ($max == 1 ? ($max/50) : intval($max/50));
		$y = new OFC_Elements_Axis_Y();
		$y->set_range( 0, $max, $sept );
		$chart->set_y_axis( $y );
		
		
		//输出JSON数据
		echo $chart->toPrettyString();
		exit();
	
	}else if($mode='m'){
		$chartsdata = array();
		
		$list_server = $db_salve->getAll("select netstat_server from ".DB_PREFIX."netstat group by netstat_server");
		foreach($list_server as $k=>$v){
			$chartsdata[$v['netstat_server']] = array();
		}
		$date = array();
		$max=0;
		for($i=0;$i<59;$i++){
			$date[] = $i;
			$start = strtotime(date('Y-m-d H:'.$i.':00',$curdate));
			$end = strtotime(date('Y-m-d H:'.$i.':59',$curdate));
			
		
			
			$list = $db_salve->getAll("select sum($column) as total,netstat_server from ".DB_PREFIX."netstat where  netstat_time >= $start and netstat_time <= $end group by netstat_server");
			foreach($list_server as $key=>$val){
				
				$total = 0;
				if(!empty($list)){
				foreach($list as $k=>$v){
					if($val['netstat_server'] == $v['netstat_server']){
						$total = $v['total'] / $rate ;
						if($total>$max){
							$max = $total;
						}
					}
				}
				}
				
				$chartsdata[$val['netstat_server']][]=$total;
			}
		}
		
	
		
		$color = array('#D54C78','#35ECC8','#154CD8','#354C18','#B51CF8','#954C48','#F56C48','#45DC78','#E5CC18');
		$i=0;
		foreach($chartsdata as $k => $arr){
			$bar = new OFC_Charts_Line_Dot();
			$bar->set_values( $arr );
			$bar->set_key('服务器'.$k."($server_ip[$k])",'12');
			$bar->set_tooltip($k."(#val#)$unit");
			$bar->colour = $color[$i];
			$chart->add_element( $bar );
			$i=$i+1;
		}
		
	
		//初始化X轴
		$x_axis = new OFC_Elements_Axis_X();
		$x_axis->set_3d( 3 ); //立体效果
		$x_axis->colour = '#909090';
		$x_axis->set_offset( true );
		$x_axis->set_labels( $date );
		//$x_axis->set_labels_from_array( $date );
		$chart->set_x_axis( $x_axis);
		
		$max += intval($max * 0.1) > 1 ? intval($max * 0.1) : 1 ;
		$sept = ($max == 1 ? ($max/30) : intval($max/30));
		$y = new OFC_Elements_Axis_Y();
		$y->set_range( 0, $max, $sept );
		$chart->set_y_axis( $y );
		
		
		//输出JSON数据
		echo $chart->toPrettyString();
		exit();

	}
}else{
	
	$page_nav = "统计分析 >> 服务器流量统计";
	include_once("templates/report-server-netstat.html");
	
}

?>