<?php
if(get_magic_quotes_gpc()=='1'){
	function fun_mysql_real_escape_string($str){
		return $str;
	}
}else{
	function fun_mysql_real_escape_string($str){
		$str = stripslashes($str);
		return mysql_real_escape_string($str);
	}
}
/*
creater devil 2010-08-16
*/
class mysqldb{
    var $host=DB_HOST;
    var $user=DB_USER;
    var $pass=DB_PASSWORD;
    var $database=DB_DATABASE;
    var $conn;
    var $sql;
    var $n=0;
    var $m=0;
    var $result="";
    var $debug=false;
    var $err='';
    var $dbcharset=DB_CHARSET;
	var $msg = '';

    function __construct($host="",$user="",$pass="",$database=""){
        if ($host!="")$this->host=$host;
        if ($user!="")$this->user=$user;
        if ($pass!="")$this->pass=$pass;
        if ($database!="")$this->database=$database;

        if (!$this->conn = @mysql_connect($this->host,$this->user,$this->pass,true)){
            return $this->output("连接数据库 '".$this->host."' 失败。".mysql_error());
        }
		
        if(in_array(strtolower(DB_CHARSET), array('gbk', 'big5', 'utf-8'))) {
            $this->dbcharset = str_replace('-', '', DB_CHARSET);
        }
		
        if($this->dbcharset) {
            mysql_query("SET character_set_connection=".$this->dbcharset.", character_set_results=".$this->dbcharset.", character_set_client=binary", $this->conn);
        }

        if($this->version() > '5.0.1') {
            mysql_query("SET sql_mode=''", $this->conn);
        }
        $this->selectdb();
    }
		
		function __destruct()
		{
			$this->close();
		}

    function selectdb($database=''){
	 	mysql_query( "set   names   '".$this->dbcharset."' ", $this->conn);
        if ($database!=""&&$database!=$this->database)
            $this->database=$database;
        if (!mysql_select_db($this->database,$this->conn)){
            return $this->output("无法使用数据库 '".$this->database."'。");
        }
    }

	//返回 B
	function countDBSize($database=''){
		$size = 0;
		if($database == ''){$database = $this->database;}
		mysql_select_db($database,$this->conn);
		$sql = "SHOW TABLE STATUS FROM ".$database;
		$res = mysql_query($sql,$this->conn);
		while( $row = mysql_fetch_row($res) )
		{
			$size += $row[6]+$row[8];
		}
		$this->selectdb();
		return $size;
	}
	
    function query($sql="",$database=""){
        if ($sql!="")$this->sql=$sql;
        if ($database!=""&&$database!=$this->database){
            $olddb=$this->database;
            $this->selectdb($database);
        }
		mysql_query( "set   names   '".$this->dbcharset."' ", $this->conn);
		
		//sql语句调试
		if(defined('DEBUG_MODE') && DEBUG_MODE && !empty($_REQUEST['debug'])){
			echo($this->sql . '<br />');
		}
		
		
		
        $this->result= @mysql_query($this->sql,$this->conn);
		
        if (mysql_error($this->conn) != ""){
            return $this->output("执行以下SQL语句时失败：'".$this->sql."' <br><br>".mysql_error($this->conn));
        }
		
        $this->n = @mysql_affected_rows($this->conn);
        if (!empty($olddb)) $this->selectdb($olddb);
        return $this->result;
    }

    function get_data(){
		/*
        if(!$this->result || ! preg_match('/'."^Resource".'/i',$this->result)){
            return $this->output("没有数据，请先执行SQL的'select'语句!");
        }*/
        $count=0;
        $this->m = @mysql_num_rows($this->result);
        if ($this->m>0){
            while($row=mysql_fetch_array($this->result)){
                $data[$count]=$row;
                $count++;
            }
            return $this->result=$data;
        }else{
            return $this->output("数据为空!");
        }
        mysql_free_result($this->result);
    }
		
	function getOne($sql, $limited = false)
	{
		if ($limited == true)
		{
			$sql = trim($sql . ' LIMIT 1');
		}

		$res = $this->query($sql);
		if ($res !== false)
		{
			$row = mysql_fetch_row($res);

			return $row[0];
		}
		else
		{
			return false;
		}
	}

    function getAll($sql,$cache=0)
    {
		$ret_list = $this->read_cache($sql,$cache);
		if($ret_list != ''){
			return $ret_list;
		}
		
        $res = $this->query($sql);
        if ($res !== false)
        {
            $arr = array();
            while ($row = mysql_fetch_assoc($res))
            {
                $arr[] = $row;
            }
            if (count($arr) > 0) {
				$this->write_cache($sql,$arr,$cache);
                return $arr;
            } else {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

	function getRow($sql, $limited = false)
	{
		if ($limited == true)
		{
			$sql = trim($sql . ' LIMIT 1');
		}

		$res = $this->query($sql);
		if ($res !== false)
		{
			return mysql_fetch_assoc($res);
		}
		else
		{
			return false;
		}
	}

    function get_insertid(){
        return mysql_insert_id($this->conn);
    }

    function get_num(){
        return $this->m;
    }

    function get_count(){
        return $this->n;
    }

    function close(){
			if(is_resource($this->conn))
        mysql_close($this->conn);
    }
		
    function output($msg){
        $this->err=$msg;
        if ($this->debug)echo $msg;
        return false;
    }

    function version() {
        return mysql_get_server_info($this->conn);
    }
	
	function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        return mysql_fetch_array($query, $result_type);
    }
	
	//获取数据二维表
	//$sql=SQL语句 支持参数化查询 
	//$pager=查询分页器 数组 支持输入page=当前页面,size=每页大小,sort=排序字段,dir=排序方向,输出count=总记录数,totalpage=总页数
	//$cache=数据缓存秒数 默认0为不缓存
	//可变参数组，用于参数化查询中的参数
	function getdata($sql,$pager=null,$cache=0){
		/*
		//参数化查询，防止SQL注入
	    $args=func_get_args();
		$args=array_slice($args,2);

		$sql = str_replace("%","%%",$sql);
		$sql = str_replace("?","'%s'",$sql);
		
		for($i=0;$i<sizeof($args);$i++){
			$args[$i]= fun_mysql_real_escape_string($args[$i]);
		}
		
		$sql = vsprintf($sql, $args);
		*/
		
		

		//处理分页器
		if($pager){
			$pager['page'] = isset($pager['page']) ? $pager['page'] : 1 ;//当前页码
			$pager['size'] = isset($pager['size']) ? $pager['size'] : 20 ;//每页大小
			$pager['sort'] = isset($pager['sort']) ? $pager['sort'] : '' ;//排序字段
			$pager['dir'] = isset($pager['dir']) ? $pager['dir'] : '' ;//排序方向
			
			//未设置总行数时，自动计算总行数
			if(!isset($pager['count'])){
				$text = $this->get_count_sql($sql);
				//$text="select count(*) as count from ( $sql ) as a";
				$pager['count'] = $this->getOne($text);
			}
			$pager['totalpage'] = ceil($pager['count'] / $pager['size']);
			if($pager['page'] > $pager['totalpage'] && $pager['totalpage'] > 0){
				$pager['page'] = $pager['totalpage'];
			}
			
			
			if(isset($pager['table'])){
				$columns = $this->getAll('desc ' . $pager['table']);
				$prifield = $this->getPri($columns);
				
				if(!empty($pager['sort'] )){
					$prifield = $pager['sort'];
				}
				$opt = '>=';
				if($pager['dir']=='desc'){
					$opt = '<=';
				}
				
				$sql .= ' and ' . $prifield . $opt . '(select '.$prifield.' from '.$pager['table'] ;
				if(!empty($pager['sort'])){
					$sql .= ' order by ' . $pager['sort'] . ' ' . $pager['dir'];
				}
				$start = ($pager['page'] - 1) * $pager['size'];
				$sql .= ' limit ' . $start . ',1) limit '.$pager['size'];
				
			}else{
			
				if(!empty($pager['sort'])){
					$sql .= ' order by ' . $pager['sort'] . ' ' . $pager['dir'];
				}
				
				$start = ($pager['page'] - 1) * $pager['size'];
				$sql .= ' limit ' . $start . ',' . $pager['size'];
			}
		}
	    
		
		
		$ret_list = $this->read_cache($sql,$cache);
		if($ret_list != ''){
			return $ret_list;
		}
		
		$res = $this->query($sql);
        if ($res !== false)
        {
            $list = array();
            while ($row = mysql_fetch_assoc($res))
            {
				$list[] = $row;
				$s = sizeof($list)-1;
				foreach($row as $k => $v){
					$list[$s]["ori_$k"] = $v;
				}
				$list[$s]['row_state'] = 'O'; //行状态为 ori
            }
			
			$this->write_cache($sql,$list,$cache);

            return $list;
        }
        else
        {
            return false;
        }
		
	}
	
	
	/*
	读取数据缓存
	$sql=缓存的sql语句
	$cache=缓存的时间
	return 成功为 数据集合 失败为 空字符串
	*/
	function read_cache($sql,$cache=0){
		//sql语句调试
		if(defined('DEBUG_MODE') && DEBUG_MODE && !empty($_REQUEST['debug'])){
			return '';
		}
		if($cache>0){
			$dir_md5 = $this->get_cache_md5();
			$sql_md5 = md5($this->database.$sql);
			$file = WEB_ROOT . '/cache/sql_caches/' . $dir_md5 . '/' . $sql_md5;
			if(file_exists($file)){
				$createtime = filectime($file);
				if(time() < ($createtime + $cache) ){
					$content = file_get_contents($file);
					return unserialize($content);
				}
			}
		}
		return '';
	}
	
	/*
	写入数据缓存
	$sql=缓存的sql语句
	$list=要缓存的数据数组或对象
	$cache=缓存时间
	*/
	function write_cache($sql,$list,$cache=0){
		if($cache>0){
			$dir_md5 = $this->get_cache_md5();
			$sql_md5 = md5($this->database.$sql);
			$dir = WEB_ROOT . '/cache/sql_caches/' . $dir_md5;
			$file = $dir . '/' . $sql_md5;
			if(!file_exists($dir)){
				mkdir($dir,0777,true);
			}
			$content = serialize($list);
			file_put_contents($file, $content, LOCK_EX);
		}
	}
	
	/*
	* 获取当前路径缓存的md5码
	*/
	function get_cache_md5(){
		return md5($_SERVER['SCRIPT_FILENAME']);
	}
	
	/* 清除缓存
	* $md5 = 是否清除指定缓存 默认为空只清除所有的缓存
	*/
	function clear_cache($md5=''){
		$dir = WEB_ROOT . '/cache/sql_caches/' . $md5;
		if(file_exists($dir)){
			$this->deldir($dir);
		}
	}
	
	function deldir($dir) {    
		$dh=opendir($dir);
		while ($file=readdir($dh)) {        
			if($file!="." && $file!="..") {            
				$fullpath=$dir."/".$file;            
				if(!is_dir($fullpath)) {                
					unlink($fullpath);            
				} else {                
					$this->deldir($fullpath);            
				}        
			}    
		}    
		closedir($dh);   
		/* 
		if(rmdir($dir)) {        
		  return true;    
		} else {       
		  return false;    
		}
		*/
	}


	function get_count_sql($sql){
		$sql = strtolower($sql);
		$arr = explode(' from ',$sql);
		$sql = "select count(*) as count ";
		$select = 0;
		$from = 0;
		$isOk = false;
		foreach($arr as $k => $v){
			if($isOk==false){
				$ret = explode('select ',$v);
				$select += sizeof($ret) - 1;
				$from++;
				if($select == $from){
					$isOk = true;
				}
			}else{
				
				$sql .= ' from ' . $v;			
			}
			
		}
		return $sql;
	}

	/* 添加 */
	function insert($rs,$tbname){
		$columns = $this->getAll('desc ' . $tbname);
		$prifield = $this->getPri($columns);
		$sql = ' insert  into ' . $tbname . ' (';
		$spt = '';

		foreach($columns as $k=>$v){
			if(isset($rs[$v['Field']])){
				if($v['Field'] != $prifield){
					$sql .= $spt . $v['Field'];
					$spt = ',';
				}
			}
		}

		$sql .= ') values( ';
		$spt = '';
		foreach($columns as $k=>$v){
			if(isset($rs[$v['Field']])){
				if($v['Field'] != $prifield){
					$sql .= $spt . "'" . fun_mysql_real_escape_string($rs[$v['Field']]) . "'";
					$spt = ',';
				}
			}
		}
		$sql .= ')';
	
		return $this->query($sql);
		
	}
	
	/* 删除行	*/
	function delete($rs,$tbname,$id=NULL){
		$columns = $this->getAll('desc ' . $tbname);
		$prifield = $this->getPri($columns);
		if(isset($rs[$prifield])){
			$sql = ' delete from ' . $tbname . ' where ';
			$sql .= $prifield . '=' . "'" . fun_mysql_real_escape_string($rs[$prifield]) . "'";
			return $this->query($sql);
		}else{
			$sql = ' delete from ' . $tbname . ' where ';
			$spt = '';
			foreach($columns as $k=>$v){
				if(isset($rs[$v['Field']])){
					$sql .= $spt . $v['Field'] . '=' . "'" . fun_mysql_real_escape_string($rs[$v['Field']]) . "'";
					$spt = ' and ';
				}
			}
			if( $id != NULL ){
				$sql .= $spt . $prifield . '=' . "'" . fun_mysql_real_escape_string($id) . "'";
			}
			return $this->query($sql);
		}
		return 0;		
	}
	
	/*	修改		*/
	function update($rs,$tbname,$id=NULL){
		$columns = $this->getAll('desc ' . $tbname);
		$prifield = $this->getPri($columns);
	
		$need_update = false;
		if($rs['row_state'] == 'O'){
			foreach($columns as $k=>$v){
				if(isset($rs[$v['Field']])){
					if($rs[$v['Field']] != $rs['ori_'.$v['Field']]){
						$need_update = true;
						break;
					}
				}
			}
		}else{
			$need_update = true;
		}


		//处理更新行
		if( $need_update ){
			if(isset($rs[$prifield]) || $id != NULL ){
				$sql = ' update ' . $tbname . ' set ';
				$spt = '';
				foreach($columns as $k=>$v){
					if(isset($rs[$v['Field']])){
						if($v['Field'] != $prifield){
							if($rs['row_state'] == 'O'){
								if($rs[$v['Field']] != $rs['ori_'.$v['Field']]){
									$sql .= $spt . $v['Field'] . '=' . "'" . fun_mysql_real_escape_string($rs[$v['Field']]) . "'";
									$spt = ',';
								}
							}else{
								$sql .= $spt . $v['Field'] . '=' . "'" . fun_mysql_real_escape_string($rs[$v['Field']]) . "'";
								$spt = ',';
							}
						}
					}
				}
				$sql .= ' where ';
				if(isset($rs[$prifield])){
					$sql .= $prifield . '=' . "'" . fun_mysql_real_escape_string($rs[$prifield]) . "'";
				}else{
					$sql .= $prifield . '=' . "'" . fun_mysql_real_escape_string($id) . "'";
				}
				return $this->query($sql);
			}
		}

		
		return 0;
	}

	//获取指定数据表的主键列名
	function getPri($columns){ 
		for($i=0;$i<sizeof($columns);$i++){
			if($columns[$i]['Key']=='PRI'){ //是否为主键
				//if($columns[$i]['Extra']=='auto_increment'){ //是否为自增长
					return $columns[$i]['Field']; 
				//}
			}  
		}
		return "缺少主键列";
	}
}
?>
