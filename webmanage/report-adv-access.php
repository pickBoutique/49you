<?php
define('PERMI_CODE','report_adv_access_msg');
include_once('init.inc.php');


if($act == 'dataget'){
	
	$curdate = time(); //统计日期及时间默认为当前
	$mode = 'h';   //视图 h-加载率 m-访问量
	$unit = 'KB';
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
		}
	}
	
	
	
	require WEB_ROOT."/include/OFC/OFC_Chart.php";
	//设置图表数据
	$chart = new OFC_Chart();
	//初始化标题
	$title = new OFC_Elements_Title("广告访问统计");
	$chart->set_title( $title );
	//初始化柱形图
	

	if($mode=='h'){
		$min_hours = 0;
		$max_hours = 24;
		$arr_url = array(
		'/advs/%'
		);
		
		$where = '(';
		$spt = '';
		foreach($arr_url as $v){
			$where .= $spt . " log_url like '$v' ";
			$spt = ' or ';
		}
		$where .= ')';
			
			
		$start = strtotime(date('Y-m-d 00:00:00',$curdate));
		$end = strtotime(date('Y-m-d 23:59:59',$curdate));
			
		$chartsdata = array();
		$chartsdata['28'] = array();
		/*
		$list_server = $db->getAll("select log_url,count(*) as total   from (select log_url,log_client from ".DB_PREFIX."access_log where  log_time >= '$start' and log_time <= '$end' and  $where group by log_url,log_client) a group by  log_url");
		
		if(!empty($list_server)){
		
			$subwhere = '(';
			$spt = '';
			
			foreach($list_server as $k=>$v){
				$arr = explode('/',$v['log_url']);
				if(isset($arr[3])){
				$id = $arr[3];//preg_replace('/^\/advs\/material\/([^\/]+)/i','$1',$v['log_url']);
				if(!isset($chartsdata[$id])){
					$chartsdata[$id] = array();
					$subwhere .= $spt . " log_url = '/advs/material/{$id}/index.swf' or  log_url = '/advs/material/{$id}/loadcomplate' ";
					$spt = ' or ';
				}
				}
			}
			$subwhere .= ')';
		}
		*/
		
		
		$date = array();
		$max=0;

		for($i=$min_hours;$i<$max_hours;$i++){
			$date[] = $i;
			$start = strtotime(date('Y-m-d '.$i.':00:00',$curdate));
			$end = strtotime(date('Y-m-d '.$i.':59:59',$curdate));
			
			
			$sql = "select log_url,count(*) as total   from (select log_url,log_client from ".DB_PREFIX."access_log where  log_time >= '$start' and log_time <= '$end' and  $subwhere group by log_url,log_client) a group by  log_url";
			
			echo($i.'小时：'.$sql);
			/*
			$list = $db->getAll($sql);
			
			foreach($chartsdata as $k=>$v){
				$total = 0;
				$loadcomplate=0;
				if(!empty($list)){
				foreach($list as $key=>$val){
					if("/advs/material/{$k}/index.swf"==$val['log_url']){
						$total = $val['total'];
					}
					if("/advs/material/{$k}/loadcomplate"==$val['log_url']){
						$loadcomplate = $val['total'];
					}
				}
				
				}
				$loadcomplate = $total > 0 ? $loadcomplate / $total * 100  : 0; 
				if($loadcomplate>$max){
			  		$max = $loadcomplate;
				}
				$chartsdata[$k][] = $loadcomplate;
				
			}
			*/
		}
		exit();
	
		
		$color = array('#D54C78','#35ECC8','#154CD8','#354C18','#B51CF8','#954C48','#F56C48','#45DC78','#E5CC18','#D54C78','#35ECC8','#154CD8','#354C18','#B51CF8','#954C48','#F56C48','#45DC78','#E5CC18');
		$i=0;
		foreach($chartsdata as $k => $arr){
			$bar = new OFC_Charts_Line_Dot();
			$bar->set_values( $arr );
			$bar->set_key('素材'.$k,'12');
			$bar->set_tooltip($k."(#val#)%");
			//$bar->set_tooltip($k."(#val#)%");
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
		
		$max += $max * 0.1;
		$max = $max > 100 ? 100 : $max;
		$sept = $max/30;
		//$sept = intval($max/50);
		$y = new OFC_Elements_Axis_Y();
		$y->set_range( 0, $max, $sept );
		$chart->set_y_axis( $y );
		
		
		//输出JSON数据
		echo $chart->toPrettyString();
		exit();
	}else{
		
		$arr_url = array(
		'/advs/%',
		'/9v/%',
		'/loadcomplate'
		);
		
		$where = '(';
		$spt = '';
		foreach($arr_url as $v){
			$where .= $spt . " log_url like '$v' ";
			$spt = ' or ';
		}
		$where .= ')';
		
		$chartsdata = array();
		$start = strtotime(date('Y-m-d 00:00:00',$curdate));
		$end = strtotime(date('Y-m-d 23:59:59',$curdate));
		$list_server = $db->getAll("select log_url from ".DB_PREFIX."access_log  where  log_time >= $start and log_time <= $end and $where group by log_url ");
		if(empty($list_server)){
			exit('没有任何数据E');
		}
		foreach($list_server as $k=>$v){
			$chartsdata[$v['log_url']] = array();
		}
		
		$date = array();
		$max=0;
		
		for($i=0;$i<24;$i++){
			$date[] = $i;
			$start = strtotime(date('Y-m-d '.$i.':00:00',$curdate));
			$end = strtotime(date('Y-m-d '.$i.':59:59',$curdate));
			
			$list = $db->getAll("select log_url,count(*) as total   from (select log_url,log_client from ".DB_PREFIX."access_log where  log_time >= $start and log_time <= $end and  $where group by log_url,log_client) a group by  log_url");
			
			foreach($list_server as $key=>$val){
				
				$total = 0;
				if(!empty($list)){
				foreach($list as $k=>$v){
					if($val['log_url'] == $v['log_url']){
						$total = $v['total'] ;
						if($total>$max){
							$max = $total;
						}
					}
				}
				}
				
				$chartsdata[$val['log_url']][]=intval($total);
			}
		}
		
		
		
		$color = array('#D54C78','#35ECC8','#154CD8','#354C18','#B51CF8','#954C48','#F56C48','#45DC78','#E5CC18');
		$i=0;
		foreach($chartsdata as $k => $arr){
			$bar = new OFC_Charts_Line_Dot();
			$bar->set_values( $arr );
			$bar->set_key($k,'12');
			$bar->set_tooltip($k."(#val#)");
			//$bar->set_tooltip($k."(#val#)%");
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
	}

}else{
	
	$page_nav = "统计分析 >> 广告访问统计";
	include_once("templates/report-adv-access.html");
	
}

?>
