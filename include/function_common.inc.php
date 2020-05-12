<?php
/*
creater devil 2010-08-16
*/
include_once("function_string.inc.php");


/**
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 *
 * @access      public
 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
 *
 * @return      bool
 */
function make_dir($folder)
{
    $reval = false;

    if (!file_exists($folder))
    {
        /* 如果目录不存在则尝试创建该目录 */
        @umask(0);
        /* 将目录路径拆分成数组 */
        preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);
        /* 如果第一个字符为/则当作物理路径处理 */
        $base = ($atmp[0][0] == '/') ? '/' : '';
        /* 遍历包含路径信息的数组 */
        foreach ($atmp[1] AS $val)
        {
            if ('' != $val)
            {
                $base .= $val;
                if ('..' == $val || '.' == $val)
                {
                    /* 如果目录为.或者..则直接补/继续下一个循环 */
                    $base .= '/';
                    continue;
                }
            }
            else
            {
                continue;
            }
            $base .= '/';
            if (!file_exists($base))
            {
                /* 尝试创建目录，如果创建失败则继续循环 */
                if (@mkdir(rtrim($base, '/'), 0777))
                {
                    @chmod($base, 0777);
                    $reval = true;
                }
            }
        }
    }
    else
    {
        /* 路径已经存在。返回该路径是不是一个目录 */
        $reval = is_dir($folder);
    }
    clearstatcache();
    return $reval;
}


/**
 * 检查文件类型
 *
 * @access      public
 * @param       string      filename            文件名
 * @param       string      realname            真实文件名
 * @param       string      limit_ext_types     允许的文件类型
 * @return      string
 */
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
    if ($realname)
    {
        $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
    }
    else
    {
        $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
    }
	
    
    if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
    {
        return '';
    }

    $str = $format = '';

    $file = @fopen($filename, 'rb');
    
    if ($file)
    {
        $str = @fread($file, 0x400); // 读取前 1024 个字节
        @fclose($file);
    }
    else
    {
        return '';
    }

    if ($format == '' && strlen($str) >= 2 )
    {
        if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
        {
            $format = 'mid';
        }
        elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
        {
            $format = 'wav';
        }
        elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
        {
            $format = 'jpg';
        }
        elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
        {
            $format = 'gif';
        }
        elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
        {
            $format = 'png';
        }
        elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
        {
            $format = 'bmp';
        }
        elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
        {
            $format = 'swf';
        }
        elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
        {   // D0CF11E == DOCFILE == Microsoft Office Document
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
            {
                $format = 'doc';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
            {
                $format = 'xls';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
            {
                $format = 'ppt';
            }
        } elseif (substr($str ,0, 4) == "PK\x03\x04")
        {
            $format = 'zip';
        } elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
        {
            $format = 'rar';
        } elseif (substr($str ,0, 4) == "\x25PDF")
        {
            $format = 'pdf';
        } elseif (substr($str ,0, 3) == "\x30\x82\x0A")
        {
            $format = 'cert';
        } elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
        {
            $format = 'chm';
        } elseif (substr($str ,0, 4) == "\x2ERMF")
        {
            $format = 'rm';
        } elseif ($extname == 'sql')
        {
            $format = 'sql';
        } elseif ($extname == 'txt')
        {
            $format = 'txt';
        }elseif ($extname == 'docx')
        {
            $format = 'docx';
        }elseif ($extname == 'xlsx')
        {
            $format = 'xlsx';
        }
		elseif ($extname == 'ico')
		{
			$format = 'ico';
		}
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
    {
        $format = '';
    }

    return $format;
}


/**
 * 将上传文件转移到指定位置
 *
 * @param string $file_name
 * @param string $target_name
 * @return blog
 */
function move_upload_file($file_name, $target_name = '')
{
    if (function_exists("move_uploaded_file"))
    {
        if (move_uploaded_file($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
        else if (copy($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
    }
    elseif (copy($file_name, $target_name))
    {
        @chmod($target_name,0755);
        return true;
    }
    return false;
}


/**
 * 处理上传文件，并返回上传结果array(上传结果,上传后文件地址,上传后文件名)
 * @param array     $upload     $_FILES 数组
 * @param array     $directory       图片上传目录
 */
function upload_file($upload, $directory = '')
{
	$return_arr = array('info'=>'','attachment_url'=>'');
    if (!empty($upload['tmp_name']))
    {
		if($_POST['tmp_name']['size']>2048)
		{
			$return_arr['info'] = "上传文件大小不能大于2M";
			return $return_arr;
		}
        $ftype = check_file_type($upload['tmp_name'], $upload['name'], '|png|jpg|jpeg|gif|doc|xls|txt|zip|ppt|pdf|rar|ico|swf|');
        if (!empty($ftype))
        {
            $name = date('Ymdhis');
            for ($i = 0; $i < 6; $i++)
            {
                $name .= chr(mt_rand(97, 122));
            }
            $target =  $directory.'/'.$name.'.'.$ftype;
	        /* 如果目标目录不存在，则创建它 */
	        if (!file_exists($directory))
	        {
				//echo $directory;
	            if (!make_dir($directory))
	            {
	                /* 创建目录失败 */
	            	$return_arr['info'] = "创建目录失败";
					return $return_arr;
	            }
	        }
            if (!move_upload_file($upload['tmp_name'], $target))
            {
                $return_arr['info'] = "上传失败，请重试";
				return $return_arr;
            }
            else
            {
                $return_arr['info'] = "上传成功";
				$return_arr['attachment_url'] = $target;
				$return_arr['attachment_name'] = $name.'.'.$ftype;
				return $return_arr;
            }
        }
        else
        {
            $return_arr['info'] = "上传文件格式不正确";
			return $return_arr;
        }
    }
    else
    {
		$return_arr['info'] = "请选择要上传的文件";
		return $return_arr;
    }
}


function UploadThumb($upload, $directory = '',$sw=100,$sh=100)
{
	global $uploadThumb;
	$return_arr = array();
	$uploadThumb->fileName = $upload;
	$uploadThumb->destination_folder = $directory;
	$uploadThumb->imgpreview = 1;
	$uploadThumb->sw = $sw;
	$uploadThumb->sh = $sh;
	$uploadThumb->up();
	$return_arr['info'] = $uploadThumb->info;
	$return_arr['attachment_url'] = $uploadThumb->bImg;
	$return_arr['attachment_name'] = $uploadThumb->bImgName;
	$return_arr['attachment_thumb_name'] = $uploadThumb->sImg;
	return $return_arr;
}


//以只读方式打开文件get_file_content(文件相对路径)，返回读取的内容String型
function readFileContent($strFileUrl)
{
	if( !file_exists($strFileUrl) ){return "";}
	$file_handle=fopen($strFileUrl,"r");
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   $strContent .=$line;
	}
	fclose($file_handle);
	return $strContent;
}


//以写入方式打开文件write_file_content(写入文件的相对路径，写入内容，写入方式[默认wb]),返回写入字符串长度Interger型
//此处不做字符串处理操作，请自行处理。
function writeFileContent($strFileUrl,$strContent,$strType="w")
{
	$file_handle = fopen($strFileUrl,$strType);
	flock($file_handle, LOCK_EX) ;
	$intWriteLen=fwrite($file_handle,$strContent);
	flock($file_handle, LOCK_UN);
	fclose($file_handle);
	@chmod($strFileUrl,0777);
	return $intWriteLen;
}


//判断是否为utf-8编码
function is_utf8($string)
{
	return preg_match('%^(?:
	[\x09\x0A\x0D\x20-\x7E] # ASCII
	| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
	| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
	| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
	| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
	| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
	| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
	| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
	)*$%xs', $string);
}


//判断编码
function isUTF8($str)
{
	if($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

//截取utf8字符串
function sub_str($str, $from, $len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}


//前台错误提示
function show_error($title = '',$desc = '',$ret_url=''){
	include(WEB_ROOT."/member/templates/show_error.html");
	exit();
}

//提示信息
function showMessage($str = '',$href = '')
{
	$ret = '';
	if('' == $href)
	{
		$ret = " <a href=\"javascript:void(0);\" onclick=\"history.go(-1);\">返回</a> ";
		/*$ret .= "<script>setTimeout(\"history.go(-1); \",3000);</script>";*/
	}
	else if($href=='close'){
		
		$ret = "<a href='"."javascript:parent.JqueryDialog.Close();"."'>返回</a>";
		$ret .= "<script>setTimeout('" . "parent.JqueryDialog.Close();" . "',3000);</script>";
	}else{
		$ret = "<a href=\"".$href."\">返回</a>";
		/*$ret .= "<script>setTimeout(\"window.location.href = '$href'; \",3000);</script>";*/
	}
	
	exit($str."&nbsp;&nbsp;".$ret);
}


//统计网站空间使用大小，返回MB
function countWebSize()
{
	global $db;
	$websize = countAttachmentSize() + $db->countDBSize();
	$websize = ($websize/1024)/1024;
	$websize = number_format($websize,2);
	return $websize;
}


//统计上传文件大小
function countAttachmentSize()
{
	global $db;
	$size = 0;
	$sql = "SELECT SUM(attachment_size) AS counter FROM ".DB_PREFIX."attachment ";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$size = intval($rs[0]['counter']);
	}
	return $size;
}


/*copy a direction’s all files to another direction  
//用法：   
// xCopy("feiy","feiy2",1):拷贝feiy下的文件到 feiy2,包括子目录   
// xCopy("feiy","feiy2",0):拷贝feiy下的文件到 feiy2,不包括子目录   
//参数说明：   
// $source:源目录名   
// $destination:目的目录名   
// $child:复制时，是不是包含的子目录
*/
function xCopy($source, $destination, $child=1)
{   
	if(!is_dir($source))
	{
		return 0;   
	}   
	if(!is_dir($destination))
	{   
		@mkdir($destination,0777); 
		@chmod($destination,0777);
	}
	$handle=dir($source);   
	while($entry=$handle->read()){
		if(($entry!=".")&&($entry!="..")){   
			if(is_dir($source."/".$entry)){   
				if($child){
					xCopy($source."/".$entry,$destination."/".$entry,$child); 
				}
			}   
			else
			{   
				@copy($source."/".$entry,$destination."/".$entry);  
				@chmod($destination."/".$entry,0777);
			}
		}
	}
	return 1;
}


function curlGet($url)
{
	$url=str_replace('&amp;','&',$url);//url处理
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	//curl_setopt($curl, CURLOPT_REFERER,$url);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; InfoPath.2)");
	curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
	$values = curl_exec($curl);
	curl_close($curl);
	return $values;
}


function getsubstr($str,$len=20)
{
	 $p_strIn=$str;
	 $p_intLen=$len;
	 $p_blnDot=1;
	 $p_strIn = preg_replace("/<.+?>/is", "", $p_strIn);
	 $p_strIn = preg_replace("/&nbsp;/is", " ", $p_strIn);
	 preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $p_strIn, $arrCharacter);
	 if (count($arrCharacter[0])>$p_intLen)
	 { 
		$content=join("", array_slice($arrCharacter[0], 0, $p_intLen)).($p_blnDot == "1" ? "..." : ""); 
	 }else{   
		$content=join("",array_slice($arrCharacter[0], 0, $p_intLen));
	 }
	 return $content;
}





//获取分类下的子分类
function getSubCateSelect($parent_id=0)
{
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE parent_id ='".$parent_id."' OR cate_id='".$parent_id."' ORDER BY cate_id ASC ";
	$db->query($sql);
	$rs = $db->get_data();
	return $rs;
}


//获取分类信息
function getCateInfo($cate_id)
{
	if(intval($cate_id) <= 0){return '';}
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE cate_id ='".$cate_id."' ";
	$db->query($sql);
	$rs = $db->get_data();
	return $rs;
}




//获取分类下的子分类下拉列表
function getCateSelect($parent_id=0,$selectid=0,$isJSON=false)
{
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE parent_id ='".$parent_id."' ORDER BY cate_id ASC ";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		foreach($rs as $v)
		{
			$str = "";
			for($i=1;$i<=$v['level'];$i++)
			{
				$str .= "　";
			}
			$str = $str!=""?$str."┣&nbsp;":"";
			$selected = $v['cate_id']==$selectid?"selected":"";
				
			if($isJSON){
				if(!empty($select_html)){
					$select_html .= ',';
				}
				$select_html .= '"' .$str. $v['cate_name'] . '":"' . $v['cate_id'] . '"';
			}else{
				$select_html .= "<option value=\"".$v['cate_id']."\" $selected>".$str.$v['cate_name']."</option>";
			}
			$sub_html = getCateSelect($v['cate_id'],$selectid,$isJSON);
			if($isJSON){
				if(!empty($sub_html)){
					$select_html .= ',';
				}
			}
			$select_html .= $sub_html;
		}
	}
	return $select_html;
}

//获取数据表的指定字段
function getTableSelect($tablename='',$fkey='',$fval='',$wherestr='1',$isJSON=false)
{
	global $db;
	$sql = "SELECT $fkey,$fval FROM ".DB_PREFIX."$tablename WHERE $wherestr";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		foreach($rs as $v)
		{
			if($isJSON){
				if(!empty($select_html)){
					$select_html .= ',';
				}
				$select_html .= '"' .$str. $v[$fval] . '":"' . $v[$fkey] . '"';
			}else{
				$select_html .= "<option value=\"".$v[$fkey]."\" $selected>".$str.$v[$fval]."</option>";
			}
			/*
			$sub_html = getCateSelect($v['cate_id'],$selectid,$isJSON);
			*/
			if($isJSON){
				if(!empty($sub_html)){
					$select_html .= ',';
				}
			}
			$select_html .= $sub_html;
		}
	}
	return $select_html;
}


//获取系统模块下拉框选项
//$isJSON   true=输出格式为json项格式，默认为下拉框option格式
function get_system_select($isJSON=false){
	global $system;
	$select_html = '';
	if($system)
	{
		foreach($system as $k => $v){
			if($isJSON){
				if(!empty($select_html)){
					$select_html .= ',';
				}
				$select_html .= '"' . $v['name'] . '":"' . $v['id'] . '"';	
			}else{
				$select_html .= "<option value=\"".$v['id']."\" $selected>".$v['name']."</option>";
			}
		}
	}
	return $select_html;
}

//创建指定系统数据库对象
//$is_salve 是否创建为从数据库 true=是 false=不是
function create_system_db($system_id,$is_salve=false){
	global $system;
	if(!empty($system)){
		foreach($system as $k=>$v){
			if($v['id'] == $system_id){
				if($is_slave){
					$db = new mysqldb($v['salve']['DB_HOST'],$v['salve']['DB_USER'],$v['salve']['DB_PASSWORD'],$v['salve']['DB_DATABASE']);
					return $db;
				}else{
					$db = new mysqldb($v['master']['DB_HOST'],$v['master']['DB_USER'],$v['master']['DB_PASSWORD'],$v['master']['DB_DATABASE']);
					return $db;
				}
			}
		}
	}
	return NULL;
}


//获取管理员分组列表
function getAdminGroupSelect($group_pid=0,$selectid=0,$isJSON=false)
{
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."admin_group WHERE group_pid ='".$group_pid."' ORDER BY group_id ASC ";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		foreach($rs as $v)
		{
			$str = "";
			for($i=1;$i<=$v['group_level'];$i++)
			{
				$str .= "　";
			}
			$str = $str!=""?$str."┣&nbsp;":"";
			
			$selected = $v['group_id']==$selectid?"selected":"";
			if($isJSON){
				if(!empty($select_html)){
					$select_html .= ',';
				}
				$select_html .= '"' .$str. $v['group_name'] . '":"' . $v['group_id'] . '"';	
			}else{
				$select_html .= "<option value=\"".$v['group_id']."\" $selected>".$str.$v['group_name']."</option>";
			}
			
			$sub_html = getAdminGroupSelect($v['group_id'],$selectid,$isJSON);
			if($isJSON){
				if(!empty($sub_html)){
					$select_html .= ',';
				}
			}
			$select_html .= $sub_html;
		}
	}
	return $select_html;
}


//获取分类下的所有子分类ID
function getSubCateID($parent_id=0)
{
	$cate_id_str = $parent_id;
	$cate_id_str = getSubCateID2($parent_id).$cate_id_str;
	return $cate_id_str;
}


function getSubCateID2($parent_id=0)
{
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."infocate WHERE parent_id ='".$parent_id."' ";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		foreach($rs as $v)
		{
			$cate_id_str .= $v['cate_id'].",";
			$cate_id_str .= getSubCateID2($v['cate_id']);
		}
	}
	return $cate_id_str;
}




//获取功能模块列表
//$purview_arr不为数组时取所有，为数组时取存在于此数组中的
function getModule($purview_admin_str='',$purview_str='')
{
	$where = "";
	if($purview_admin_str != 'admin')
	{
		$where = " AND module_id in(".$purview_admin_str.")";
	}
	$purview_arr = array();
	if($purview_str)
	{
		$purview_arr = explode(",",$purview_str);
	}
	global $db;
	$module_html = "";
	$sql = "SELECT * FROM ".DB_PREFIX."module WHERE parent_id='0' $where ORDER BY sort_num ASC ";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$module_html .= "<div class=\"module_list\">";
		foreach($rs as $v)
		{
			$strchecked = "";
			if( in_array($v['module_id'],$purview_arr) )
			{
				$strchecked = " checked ";
			}
			$module_html .= "<div class=\"module_list1\"><input name=\"purview_checkbox[]\" id=\"purview_checkbox_".$v['module_id']."\" type=\"checkbox\" value=\"".$v['module_id']."\" ".$strchecked." onclick=\"chooseMySub(this,'purview_input_".$v['module_id']."')\" />".$v['module_name']."</div>";
			//二级栏目
			$sql = "SELECT * FROM ".DB_PREFIX."module WHERE parent_id='".$v['module_id']."' $where ORDER BY sort_num ASC ";
			$db->query($sql);
			$rs2 = $db->get_data();
			if($rs2)
			{
				$strchecked2 = "";
				$module_html .= "<div class=\"module_list2\">";
				foreach($rs2 as $v2)
				{
					if( in_array($v2['module_id'],$purview_arr) )
					{
						$strchecked2 = " checked ";
					}
					$module_html .= "<label><input name=\"purview_checkbox[]\" class=\"purview_input_".$v['module_id']."\" id=\"purview_checkbox_".$v2['module_id']."\" type=\"checkbox\" value=\"".$v2['module_id']."\" ".$strchecked2." />".$v2['module_name']."</label>";
				}
				$module_html .= "</div>";
			}
		}
		$module_html .= "</div>";
	}
	return $module_html;
}




//比较两个时间相差多少天
function dateDif($date1,$date2)
{
	if(intval($date1)<=0 || intval($date2)<=0)
	{
		return 0;
	}
	$days = 0;
	$temp = $date2 - $date1;
	$days = intval($temp/(3600*24));
	//$days = $temp%(3600*24)>0?$days+1:$days;
	return $days;
}


//获取广告
function getAdvertisement($advertisement_position)
{
	global $db;
	$advertisement_arr = array();
	$sql = "SELECT * FROM ".DB_PREFIX."advertisement WHERE advertisement_position in(".$advertisement_position.") ORDER BY sort_num ASC";
	$db->query($sql);
	$rs = $db->get_data();
	if($rs)
	{
		foreach($rs as $v)
		{
			$advertisement_arr[$v['advertisement_position']][] = $v;
		}
	}
	return $advertisement_arr;
}


//获取广告
function getMemberMsgCount($member_id)
{
	global $db;
	$counter = 0;
	$sql = "SELECT count(message_id) as counter FROM ".DB_PREFIX."message WHERE member_id='".$member_id."' ";
	$db->getRow($sql);
	$rs = $db->get_data();
	if($rs)
	{
		$counter = $rs['counter'];
	}
	return $counter;
}


/**************************************************************
	depiction：发送短信
	@param sendType	(1单发,2群发)
	@param strMobile 要发送的手机号码(群发以分号隔开)
	@param strContent 发送内容
	@returns string
	Creater：devil
	Create Date：2010-06-30
	***************************************************************/
function sendSms($strMobile,$sendType=1,$strContent)
{
	//echo $strContent."<br/>";
	$ch2=curl_init();
	curl_setopt($ch2, CURLOPT_HEADER, 1);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_POST, 1);
	curl_setopt($ch2, CURLOPT_COOKIEJAR, 1);
	curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookie.txt');
	
	//login
	$url = 'http://www.lanz.net.cn/LANZGateway/Login.asp';
	$request = 'UserID=925559&Account=admin&Password=12345678';
	curl_setopt($ch2, CURLOPT_URL, $url);
	curl_setopt($ch2, CURLOPT_POSTFIELDS, $request);
	$sms_response = curl_exec($ch2);
	//echo $request."<br/>";
	//var_dump($sms_response);
	$ActiveID_start = strpos($sms_response,'<ActiveID>')+10;
	$ActiveID_end = strpos($sms_response,'</ActiveID>');
	$ActiveID = substr($sms_response,$ActiveID_start,$ActiveID_end-$ActiveID_start);
	
	if($ActiveID){
		//send sms
		if($sendType=='1'){
			if(substr($strMobile,0,1)=='1'){
				$SMSType='1';
			}
			else{
				$SMSType='0';
			}
			$url = 'http://www.lanz.net.cn/LANZGateway/SendSMS.asp';
			$request = 'ActiveID='.$ActiveID.'&SMSType='.$SMSType.'&Phone='.$strMobile.'&Content='.$strContent;
			curl_setopt($ch2, CURLOPT_URL, $url);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, $request);
			$sms_response = curl_exec($ch2);
			//echo $request."<br/>";
			//var_dump($sms_response);
			$ErrorNum_start = strpos($sms_response,'<ErrorNum>')+10;
			$ErrorNum_end = strpos($sms_response,'</ErrorNum>');
			$ErrorNum = substr($sms_response,$ErrorNum_start,$ErrorNum_end-$ErrorNum_start);
		}
		else{
			//获取JobID
			$url="http://www.lanz.net.cn/LANZGateway/MessSMSQuery.asp";
			$request = 'ActiveID='.$ActiveID.'&SMSType=1&Content='.$strContent;
			curl_setopt($ch2, CURLOPT_URL, $url);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, $request);
			$sms_response = curl_exec($ch2); 
			//echo $request;
			//var_dump($sms_response);
			$JobID_start = strpos($sms_response,'<JobID>')+7;
			$JobID_end = strpos($sms_response,'</JobID>');
			$JobID = substr($sms_response,$JobID_start,$JobID_end-$JobID_start);
			//群发短信
			$url = 'http://www.lanz.net.cn/LANZGateway/SendMessSMS.asp';
			$request = 'ActiveID='.$ActiveID.'&JobID='.$JobID.'&Phones='.$strMobile;
			curl_setopt($ch2, CURLOPT_URL, $url);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, $request);
			$sms_response = curl_exec($ch2); 
			//echo $request;
			//var_dump($sms_response);
			$ErrorNum_start = strpos($sms_response,'<ErrorNum>')+10;
			$ErrorNum_end = strpos($sms_response,'</ErrorNum>');
			$ErrorNum = substr($sms_response,$ActiveID_start,$ActiveID_end-$ActiveID_start);
		}
		
		//loginout
		$url = 'http://www.lanz.net.cn/LANZGateway/Logoff.asp';
		$request = 'ActiveID='.$ActiveID;
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $request);
		$sms_response = curl_exec($ch2);
		//echo $request;
		//var_dump($sms_response);
	}
	else{
		$ErrorNum='9099';
	}
	curl_close($ch2);
	
	//发送记录
	if($ErrorNum=='0'){$strResult='成功';}else{$strResult='失败';}
	//send_record($strResult,$strContent,$strMobile);
	
	return $ErrorNum;
}


include("phpmailer.class.php");
function  sendMail($to,$subject,$body,$param=array("is_smtp"=>true,"is_html"=>true))
{
	if(!$to){return false;}
	if(!is_array($to))
	{
		$to = explode(",",$to);
	}
	$mail_user = "service_send@sogou.com";
	$mail_pwd = "123456789";
	$mail_server = "smtp.sogou.com";
	$mail_name = "环球互易-注册平台";
	if(!$param['host']){$param['host'] = $mail_server;} // smtp服务器
	if(!$param['user']){$param['user'] = $mail_user;} // smtp用户名
	if(!$param['pwd']){$param['pwd'] = $mail_pwd;} // stmp密码
	$param['from_name'] = $param['from_name']?$param['from_name']:$mail_name;
	if('' == $param['is_smtp'])
	{
		$param['is_smtp'] = true;	//是否使用smtp发送
	}
	if('' == $param['is_html'])
	{
		$param['is_html'] = true;	//发送内容是否为html格式
	}
	
	$mail = new PHPMailer(); //建立邮件发送对象
	$mail->Host = $param['host'];
	if($param['is_smtp'])
	{
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Host = $param['host'];
		$mail->Username = $param['user'];
		$mail->Password = $param['pwd'];
	}
	$mail->From = $param['user']; //邮件发送者email地址
	$mail->FromName = $param['from_name'];	//发送者姓名
	//收件人
	foreach($to as $v)
	{
		if(is_array($v))
		{
			$mail->AddAddress($v[0],$v[1]);	//收件人地址,姓名
		}
		else
		{
			$mail->AddAddress($v,$v);	//收件人地址
		}
	}
	//抄送
	if($param['cc'])
	{
		if(!is_array($param['cc']))
		{
			$param['cc'] = explode(",",$param['cc']);
		}
		foreach($param['cc'] as $v)
		{
			if(is_array($v))
			{
				$mail->AddCC($v[0],$v[1]);	//抄送地址，姓名
			}
			else
			{
				$mail->AddCC($v,$v);	//抄送地址
			}
		}
	}
	//密抄
	if($param['bcc'])
	{
		if(!is_array($param['bcc']))
		{
			$param['bcc'] = explode(",",$param['bcc']);
		}
		foreach($param['bcc'] as $v)
		{
			if(is_array($v))
			{
				$mail->AddBCC($v[0],$v[1]);	//密抄人地址，姓名
			}
			else
			{
				$mail->AddBCC($v,$v);	//密送地址
			}
		}
	}
	//回复
	if($param['replyto'])
	{
		if(!is_array($param['replyto']))
		{
			$param['replyto'] = explode(",",$param['replyto']);
		}
		foreach($param['replyto'] as $v)
		{
			if(is_array($v))
			{
				$mail->AddReplyTo($v[0], $v[1]);	//回复人地址，姓名
			}
			else
			{
				$mail->AddReplyTo($v,$v);	//回复地址
			}
		}
	}
	//附件
	if($param['attachment'])
	{
		foreach($param['attachment'] as $v)
		{
			$mail->AddAttachment($v);	// 添加附件
		}
	}
	$mail->IsHTML($param['is_html']); //是否使用HTML格式
	$mail->Subject = $subject; //邮件标题
	$mail->Body = $body; //邮件内容
	$mail->AltBody = $param['alt_body']; //附加信息，可以省略
	$rs = $mail->Send();
	return $rs;
}


/*
	将浏览器页面重定向到指定地址
	$url:目标地址
	$exit:重定向后是否立即exit();
*/
function redir($url,$exit=false){
	header('location:' . $url);
	if($exit){
		exit();
	}
}


/*
	获取并转换指定模板文件内容输出
	$file:模板文件名
	$param:模板所需要的数据
*/
function parse_template($file,$param=array()){
	
	$html = '';
	if(file_exists($file)){
		ob_start();
		extract($param,EXTR_SKIP);
		require($file);
		$html = ob_get_contents();
		ob_end_clean();
	}
	
	return $html;
}

//获取地区列表
function getRegions($parent_id=0)
{
	global $db;
	$sql = "SELECT * FROM ".DB_PREFIX."region WHERE parent_id ='".$parent_id."' ";
	$db->query($sql);
	$rs = $db->get_data();
	return $rs;
}


/**
 * 获得所有模块的名称以及链接地址
 *
 * @access      public
 * @param       string      $directory      插件存放的目录
 * @return      array
 */
function read_modules($directory = '.')
{
	$dir         = @opendir($directory);
	$set_modules = true;
	$modules     = array();

	while (false !== ($file = @readdir($dir)))
	{
		if (preg_match("/^.*?\.php$/", $file))
		{
			include_once($directory. '/' .$file);
		}
	}
	@closedir($dir);
	unset($set_modules);

	foreach ($modules AS $key => $value)
	{
		ksort($modules[$key]);
	}
	ksort($modules);

	return $modules;
}
	
	
/**
 * 产生随机码
 */
function createCode($len = 6,$srcstr='ABCDEFGHIJKLMNPQRTUVWXYZ' )
{
	mt_srand();
	$strs="";
	for($i=0;$i<$len;$i++){
		$strs.=$srcstr[mt_rand(0,strlen($srcstr) - 1)];
	}
	return strtoupper($strs);
}
	
	
/* 根据ID获取省，市，区信息 */
function get_city($SP,$PC,$CC){
	global $db;
	$params = array();
	
	$sql = 'SELECT region_name,region_en FROM ' . DB_PREFIX . 'region' .
			" WHERE region_id = '$SP' ";
	$rs = $db->getRow($sql);
	$params['SP'] = $rs['region_name'];
	$params['SP_EN'] = $rs['region_en'];
	
	$sql = 'SELECT region_name,region_en FROM ' . DB_PREFIX . 'region' .
			" WHERE region_id = '$PC' ";
	$rs = $db->getRow($sql);
	$params['CITY'] = $rs['region_name'];
	$params['CITY_EN'] = $rs['region_en'];

	$sql = 'SELECT region_name,region_en FROM ' . DB_PREFIX . 'region' .
			" WHERE region_id = '$CC' ";
	$rs = $db->getRow($sql);
	$params['CC'] = $rs['region_name'];
	$params['CC_EN'] = $rs['region_en'];

	return $params;
}
	
	
/*
*	将以-号分隔的电话号码字符串转换为数组
*/
function get_tel_ext($tel){
	$tel_arr = explode('-',$tel);
	$ret = array();
	//+86.02086070377
	$ret['country'] = '86';
	$ret['region'] = $tel_arr[0];
	$ret['number'] = $tel_arr[1];
	if(sizeof($tel_arr)==3){
		$ret['ext'] = $tel_arr[2];
	}else{
		$ret['ext'] = '';
	}

	return $ret;
}

/*
*	根据指定的支付方式协议,将人民币转换到平台币
*	$money float 人民币金额
*	$pay_type string 支付方式
*	$return int  平台币
*/
function rmb_to_currency($money,$pay_type){
	global $cfg_pay_rate,$config;
	$rate = $config['default_to_currency'];
	if($cfg_pay_rate[$pay_type]){
		$rate = $cfg_pay_rate[$pay_type];
	}
	return floor($money * $rate);
}
	
	
/* 支付完成，改变订单状态 */
function order_paid($order_sn,$pay_type,$order_other=''){
	global $db,$cfg_pay_rate,$config;
	
	$order = $db->getRow("SELECT * FROM ". DB_PREFIX . "trans WHERE trans_code='{$order_sn}' AND trans_instatus='0' ");
	if($order){
		
		//更新交易单状态
		$sql = 'UPDATE ' . DB_PREFIX . 'trans' .
				" SET trans_instatus = '1', " .
					" trans_intime = '".time()."', " .
					" trans_type = '$pay_type' " .
		   "WHERE trans_code = '$order_sn' AND trans_instatus='0'  ";
		$db->query($sql);
		$ret = $db->get_count();
		if($ret > 0){
			
			$user = $db->getRow("select member_id,member_level from ".DB_PREFIX."member where member_name='{$order[trans_mname]}' ");
			$mid = $user['member_id'];
			//人民币转换成平台币数量
			$currency = rmb_to_currency($order['trans_money'],$pay_type );
			
			//增加会员平台币
			charge_currency($mid,$pay_type,$order['trans_money'], $currency, $order_sn);
			
			//VIP充值返利
			$ret_rate = $db->getOne("select ret_rate from ".DB_PREFIX."member_level where level_id='{$user[member_level]}' ");
			if(!empty($ret_rate)){
				$addtions = floor($currency * ($ret_rate / 100));
				return_currency($mid,$order['trans_mname'],($ret_rate / 100),$addtions, $order_sn);
			}
			
			//增加会员积分
			$integral = floor($currency * $config['money_to_integral']);
			add_integral($mid,'1', $integral, $order_sn);
			
			
			
			//为推荐人增加平台币和积分奖励
			$row = $db->getRow("select member_recom,member_reomname from " . DB_PREFIX . "member where member_id='{$mid}' ");
			if(!empty($row) && $row['member_recom']>0){
				
				$recom_curren = floor($currency * $config['recom_pay_money']);
	
				//返还会员平台币
				return_currency($row['member_recom'],$row['member_reomname'], $config['recom_pay_money'], $recom_curren, $order_sn,1);
				
				$recom_integral = floor($currency * $config['recom_pay_integral']);
				
				//增加推荐会员积分
				add_integral($row['member_recom'], '3', $recom_integral, $order_sn);
			}
	
		
			
		}else{
			//TODO:订单重复支付
		}
	}
	order_exchange($order_sn);
}





/*
*	平台币兑换游戏币
*/
function order_exchange($order_sn){
	global $db,$cfg_pay_rate,$config;

	$order = $db->getRow("SELECT * FROM ". DB_PREFIX . "trans WHERE trans_code='{$order_sn}' AND trans_outstatus='0' ");
	
	if( !empty($order) && ($order['trans_type']=='account'  ||  $order['trans_instatus']=='1')  ){
		//获取会员id
		$mid = $db->getOne("select member_id from ".DB_PREFIX."member where member_name='{$order[trans_mname]}' ");
		
		//人民币转换成平台币数量
		$currency = rmb_to_currency($order['trans_money'],$order['trans_type'] );
		
		//平台币转换成游戏币
		$game = $db->getRow("select game_rate,game_code from ".DB_PREFIX."game where game_id='{$order[trans_gid]}' ");
		$game_cur = floor($currency *  $game['game_rate'] );
		
		/*服务器信息*/
		$server = $db->getRow("select * from ".DB_PREFIX."server where server_id='{$order[trans_sid]}' ");
		
		
		/*调用充值接口*/
		require_once(WEB_ROOT."/include/user.class.php");
		$obj_user = new User();
		$user_info = $obj_user->get_user_by_id($mid);
		$code = $game['game_code'];
		$clsname = "Game_{$code}";
		if(file_exists(WEB_ROOT . '/include/game/'.$code.'.class.php')){
			
		}else{
			//接口未实现
			return -4;
		}
			
		//减少会员平台币
		$db->query("update " . DB_PREFIX . "member set money = money - $currency  where member_id='$mid' and money >= $currency ");
		$ret = $db->get_count();
		if($ret > 0){
			
			include(WEB_ROOT . '/include/game/'.$code.'.class.php');
			$login_obj = new $clsname();
			$order['trans_point'] = $game_cur;
			
			if( $order['trans_type']=='account' ){
				$order['trans_money'] = $order['trans_money'] / 10;
			}
			$ret = @$login_obj->to_pay($server,$user_info,$order);
			if($ret){
				
			}else{
				$db->query("update " . DB_PREFIX . "member set money = money + $currency  where member_id='$mid'  ");
				//调用接口异常
				return -5;
			}
			
			//更新交易单状态
			$sql = 'UPDATE ' . DB_PREFIX . 'trans' .
					" SET trans_outstatus = '1', " .
						" trans_outtime = '".time()."' " .
			   "WHERE trans_code = '$order_sn' AND trans_outstatus='0'  ";
			$db->query($sql);
			$ret = $db->get_count();
			if($ret > 0){
				
				//添加到交易信息
				$row = array();
				$row['transout_code'] = $order_sn;
				$row['transout_mid'] = $mid;
				$row['transout_time'] = time();
				$row['transout_gid'] = $order['trans_gid'];
				$row['transout_sid'] = $order['trans_sid'];
				$row['transout_currency'] = $currency;
				$row['transout_gcurrency'] = $game_cur;
				$row['transout_rate'] = $game['game_rate'];
				$db->insert($row, DB_PREFIX . 'transout');

				
				
				return 0;
			}else{
				
				//TODO:订单重复支付
				return -2;
			}
		}else{
			//TODO:余额不足
			return -1;
			
		}
	}else{
		//TODO:订单不存在
		return -3;
	}
}

/*
*	指定会员充值平台币
*	$mid int 会员号
*	$type string 充值方式
*	$money int 充值RMB
*	$currentcy int 充值平台币
*	$code	string 关联的订单号
*/
function charge_currency($mid,$type,$money,$currency,$code){
	global $db;
	if($money>0){
		
		//添加到交易信息
		$row = array();
		$row['transin_code'] = $code;
		$row['transin_mid'] = $mid;
		$row['transin_time'] = time();
		$row['transin_money'] = $money;
		$row['transin_currency'] = $currency;
		$row['transin_type'] = $type;
		$db->insert($row, DB_PREFIX . 'transin');
		$ret = $db->get_count();
		if($ret > 0){
			$db->query("update " . DB_PREFIX . "member set money=money+$currency  where member_id='$mid' ");
		}
	}
}





/*
*	返还会员指定平台币
*	$mid int 会员号
*	$mname string 用户名
*	$rate float 返还率
*	$money int 平台币数量
*	$code string 关联的订单号
*	$type int 0-VIP充值返还 1-推荐充值勤
*/
function return_currency($mid,$mname,$rate,$money,$code,$type=0){
	global $db;
	if($money>0){
		
		//添加到交易信息
		$row = array();
		$row['transret_code'] = $code;
		$row['transret_mid'] = $mid;
		$row['transret_mname'] = $mname;
		$row['transret_time'] = time();
		$row['transret_currency'] = $money;
		$row['transret_rate'] = $rate;
		$row['transret_type'] = $type;
		$db->insert($row, DB_PREFIX . 'transret');
		$ret = $db->get_count();
		if($ret > 0){
			$db->query("update " . DB_PREFIX . "member set money=money+$money  where member_id='$mid' ");
		}
	}
}

/*
*	增加指定会员积分
*	$mid int 获得积分的会员id
*	$type int 积分获取渠道
*	$count int  积分数量
*	$code string 关联的订单号
*	$recom_id int 被邀好友的id
*/
function add_integral($mid,$type,$count,$code,$recom_id=0){
	global $db;
	if($count>0){
		
		$row = array();
		$row['integral_mid']=$mid;
		$row['integral_time']=time();
		$row['integral_count']=$count;
		$row['integral_type']=$type;
		$row['integral_code']=$code;
		$row['integral_recom']=$recom_id;
		$db->insert($row, DB_PREFIX . 'integral');
		$ret = $db->get_count();
		if($ret > 0){
			$db->query("update " . DB_PREFIX . "member set points=points+$count  where member_id='$mid' ");
			update_level($mid);
		}
	}
}


/*
*	更新指定会员的当前等级
*	$mid int 要更新的会员id
*	return int 会员的当前等级id
*/
function update_level($mid){
	global $db;
	
	$user = $db->getRow("select member_level,points from ".DB_PREFIX."member where member_id='{$mid}' ");
	if(!empty($user)){
		$level = $db->getRow("select level_id from ".DB_PREFIX."member_level where points_down <={$user[points]} and  points_up>={$user[points]} ");
		if(!empty($level)){
			if($user['member_level'] != $level['level_id']){
				$db->update(array('member_level'=>$level['level_id']), DB_PREFIX.'member', $mid );
				return $level['level_id'];
			}
		}
	}
	
	return $user['member_level'];
}

/*
*	游戏充值
*/
function game_paid(){
	//TODO:游戏充值
}

	
/* 检查支付的金额是否相符 */
function check_money($order_sn, $total_fee){
	global $db;
	$sql = 'SELECT * FROM ' . DB_PREFIX . 'trans' .
		   " WHERE trans_code = '$order_sn' ";
	$rs = $db->getRow($sql);
	if($rs){
		if($rs['trans_money'] == $total_fee){
			return true;
		}
	}
	return false;
}
	
	
/* 获取支付接口帐号信息 */
function get_payment($code){
	$set_modules = true;
	require(WEB_ROOT . "/pay/$code.php");
	$params = array();
	foreach($modules[0]['config'] as $kev => $val){
		$params[$val['name']] = $val['value'];
	}
	return $params;
}

/*获取登陆接口配置信息 */
function get_login_config($code){
	$set_modules = true;
	require(WEB_ROOT . "/login/$code.php");
	$params = array();
	foreach($modules[0]['config'] as $kev => $val){
		$params[$val['name']] = $val['value'];
	}
	return $params;
}


	
	
/*
*	将中文转换为拼音串
*	str		string		要转换为拼音的字符串 需要为gbk格式
*	ishead	int			首字母是否为大写 默认为0-不是 1为是
*	isclose	int			是否多次调用时结果追加 默认为1-不是 0-为是
*	return				返回的拼音单词之间以空格分隔
*/
function get_pin_yin($str,$ishead=0,$isclose=1)
{
	global $pinyins;
	$restr = '';
	$str = trim($str);
	$slen = strlen($str);
	if($slen<2)
	{
		return $str;
	}
	if(count($pinyins)==0)
	{
		$fp = fopen(WEB_ROOT .'/include/pinyin.dat','r');
		while(!feof($fp))
		{
			$line = trim(fgets($fp));
			$pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
		}
		fclose($fp);
	}
	for($i=0;$i<$slen;$i++)
	{
		if(ord($str[$i])>0x80)
		{
			$c = $str[$i].$str[$i+1];
			$i++;
			if(isset($pinyins[$c]))
			{
				if($ishead==0)
				{
					/*首字母设为大写 */
					$fir = strtoupper($pinyins[$c][0]);
					$pinyins[$c][0] = $fir; 
					
					$restr .= ' ' .  $pinyins[$c];
				}
				else
				{
					$restr .= ' ' . $pinyins[$c][0];
				}
			}else
			{
				$restr .= "_";
			}
		}else if( eregi("[a-z0-9]",$str[$i]) )
		{
			$restr .= $str[$i];
		}
		else
		{
			$restr .= "_";
		}
	}
	if($isclose==0)
	{
		unset($pinyins);
	}
	return trim($restr);
}


/// <summary>
/// 转换搜索字符串为SQL字符串
/// 返回格式： and fieldA= '1' and fieldB like '%2%'
/// </summary>
/// <param name="filter">要转换的搜索字符串 格式： :.fieldA.:=.:1:.fieldB.:@.:2</param>
/// <returns></returns>
function get_where($filter)
{
	if (empty($filter))
	{
		return "";
	}
	
	require_once(WEB_ROOT.'/include/json.class.php');
	$json = new JSON();
	$arrfilter = $json->decode($filter,1);

	$sql = "";
	//$arrfilter = explode(":.",$filter);
	$list = array( "=", ">", ">=", "<=", "<", "@", "<>", "IN" );
	if($arrfilter){
	foreach ($arrfilter as $k => $sub ){
		//if($subfilter){
			//$sub = explode(".:",$subfilter);
			
			//if (sizeof($sub) >= 3){
				if ( in_array($sub['oper'],$list) ){
					if ($sub['oper'] == "@"){
						$sql .= ' and ' . mysql_real_escape_string($sub['name']) . " like " . " '%" . mysql_real_escape_string($sub['value']) . "%' ";
					}
					else if( $sub['oper'] == "IN" ){
						$sql .= ' and ' . mysql_real_escape_string($sub['name']) . " in " . " (" . mysql_real_escape_string($sub['value']) . ") ";
					}
					else{
						$sql .= ' and ' . mysql_real_escape_string($sub['name']) . $sub['oper'] . " '" . mysql_real_escape_string($sub['value']) . "' ";
					}
				}
			//}
		//}
	}
	}

	return $sql;
}
	
	
function get_list_json($rs,$count){
	global $json;
	$str = $json->encode($rs);
	return "{totalCount:" . $count . ",root:" . $str . "}";
}

	
/*
*	将配置项转换成  "'$v':'$k'" 的格式；
*/
function cfg_to_opt($arr,$isJson=true,$selid=-1,$isRevers=false){
	$ret = "";
	$spt = "";
	foreach($arr as $k => $v){
		$key = $k;
		$val = $v;
		if($isRevers){
			$key = $v;
			$val = $k;
		}
		if($isJson){
			$ret .= $spt . "'$val':'$key'";
			$spt = ",";
		}else{

			$selected = '';
			if($selid == $key){
				$selected = 'selected';
			}
			$ret .=  "<option value='{$key}' $selected  >{$val}</option>";
		}
		
	}
	return $ret;
}
	
	
//递归获取产品父类下所有id集
function get_clsids_by_parent($parent_ids)
{
	global $db;
	$sql = "SELECT cate_id FROM ".DB_PREFIX."productcate WHERE parent_id IN (".$parent_ids.") ";
	$rs = $db->getAll($sql);
	
	$ret_ids = $parent_ids;
	if($rs)
	{
		$cate_id_str = '';
		$spt = '';
		foreach($rs as $v)
		{
			$cate_id_str .= $spt . $v['cate_id'];
			$spt = ',';
		}
		$ret_ids = $ret_ids . ',' . $cate_id_str;
		$sub_ids = get_clsids_by_parent($cate_id_str);
		if(!empty($sub_ids)){
			$ret_ids = $ret_ids . ',' . $sub_ids;
		}
	}
	return $ret_ids;
}

//递归获取产品父类下所有id集
function get_cateids_by_parent($parent_ids)
{
	global $db;
	$sql = "SELECT cate_id FROM ".DB_PREFIX."infocate WHERE parent_id IN (".$parent_ids.") ";
	$rs = $db->getAll($sql);
	
	$ret_ids = $parent_ids;
	if($rs)
	{
		$cate_id_str = '';
		$spt = '';
		foreach($rs as $v)
		{
			$cate_id_str .= $spt . $v['cate_id'];
			$spt = ',';
		}
		$ret_ids = $ret_ids . ',' . $cate_id_str;
		$sub_ids = get_cateids_by_parent($cate_id_str);
		if(!empty($sub_ids)){
			$ret_ids = $ret_ids . ',' . $sub_ids;
		}
	}
	return $ret_ids;
}


//根据产品规格类型，取得产品分类编号集
function get_cls_by_pro_type($pro_type){
	global $cfg_product_type;
	//TODO:加入缓存机制
	if(isset($cfg_product_type[$pro_type])){
		return get_clsids_by_parent($cfg_product_type[$pro_type]);
	}
	return '';
}

	
/*
*	清除数组内所有前台空格
*/
function trim_all($arr){
	foreach($arr as $k => $v){
		$arr[$k] = trim($v);
	}
	return $arr;
}

/**
 * 读结果缓存文件
 *
 * @params  string  $cache_name
 *
 * @return  array   $data
 */
function read_static_cache($cache_name)
{
	/*
    if ((DEBUG_MODE & 2) == 2)
    {
        return false;
    }*/
    static $result = array();
    if (!empty($result[$cache_name]))
    {
        return $result[$cache_name];
    }
    $cache_file_path = WEB_ROOT . '/cache/static_caches/' . $cache_name . '.php';
    if (file_exists($cache_file_path))
    {
        include_once($cache_file_path);
        $result[$cache_name] = $data;
        return $result[$cache_name];
    }
    else
    {
        return false;
    }
}

/**
 * 写结果缓存文件
 *
 * @params  string  $cache_name
 * @params  string  $caches
 *
 * @return
 */
function write_static_cache($cache_name, $caches)
{
	/*
    if ((DEBUG_MODE & 2) == 2)
    {
        return false;
    }*/
    $cache_file_path = WEB_ROOT . '/cache/static_caches/' . $cache_name . '.php';
    $content = "<?php\r\n";
    $content .= "\$data = " . var_export($caches, true) . ";\r\n";
    $content .= "?>";
    file_put_contents($cache_file_path, $content, LOCK_EX);
}

/*
*	重写 var_export
*/
function var_export_encode($var){
    if (is_array($var)) {
        $code = 'array(';
        foreach ($var as $key => $value) {
            $code .= "'$key'=>".var_export_encode($value).',';
        }
        $code = chop($code, ','); //remove unnecessary coma
        $code .= ')';
        return $code;
    } else {
        if (is_string($var)) {
		   if(strpos($var,'$')===false) {
			 return '\''.add_slashes($var).'\'';
		   } else {
			 return $var;
		   }
        } elseif (is_bool($code)) {
            return ($code ? 'TRUE' : 'FALSE');
        } else {
            return 'NULL';
        }
    }
}
/*
*	获取客户端IP地址
*/
function get_client_ip()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	   $cip = $_SERVER["HTTP_CLIENT_IP"];
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	   $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	   $cip = $_SERVER["REMOTE_ADDR"];
	else
	   $cip = "";//无法获取
	return $cip;
}


//获取名称列表根据指定
function format_namelist_by_id($rs,$id,$name,$tb,$idfiled,$namefield){
	global $db;
	if(!$rs) return;
	$ids = '';
	$spt = '';
	foreach($rs as $k=>$v){
		if($v[$id]!=''){
			$ids .= $spt . "'$v[$id]'";
			$spt = ',';
		}
	}
	
	if(!empty($ids)){
		$list = $db->getAll("select $idfiled,$namefield from ".DB_PREFIX."$tb where $idfiled in ($ids) ");
		//print_r($db);
		if($list){
			foreach($rs as $k=> $v){
				foreach($list as $key=>$val){
					if($v[$id]==$val[$idfiled]){
						$rs[$k][$name] = $val[$namefield];
					}
				}
			}
		}
		
	}	
}

//获取名称列表根据指定
function format_fields_by_id($db,$rs,$src_id,$tb,$dst_id,$names){
	if(!$rs) return;
	$ids = '';
	$spt = '';
	foreach($rs as $k=>$v){
		if($v[$src_id]!=''){
			$ids .= $spt . "'$v[$src_id]'";
			$spt = ',';
		}
	}
	
	$dst_fields = '';
	foreach($names as $k => $v){
	    $dst_fields .= ',' . $k;
	}
	
	if(!empty($ids)){
		$list = $db->getAll("select $dst_id $dst_fields from $tb where $dst_id in ($ids) ");
	
		if($list){
			foreach($rs as $k=> $v){
				foreach($list as $key=>$val){
					if($v[$src_id]==$val[$dst_id]){
						foreach($names as $nk => $nv){
							if(!empty($nk)){
								$rs[$k][$nv] = $val[$nk];
							}
						}
						
					}
				}
			}
		}
		
	}	
}

//生成订单号:年月日时分秒+5位随机数字
function genOrderCode(){
	return date('YmdHis').genCode(4);
	//return time().$this->genCode();
}

//private 生成指定长度的数字随机数
function genCode($len=5){
	$rnd = mt_rand(("1".sprintf("%0".($len-1)."d", 0))+0,("1".sprintf("%0".$len."d", 0))-1);
	$var = sprintf("%0".$len."d", $rnd);
	return $var;
}
//
function pager_bar($page,$html_url){
	$mypager=$page;
	include($html_url);
}

function synlogin($username,$password,$info=''){
	
	//同步登陆到UC
	$synlogin_script = '';
	if(defined('UC_API')){
		require_once WEB_ROOT.'/uc_client/client.php';
		$ucresult = uc_user_checkname($username);
		if($ucresult==1){
			$email = 'nobody@49you.com';
			if(!empty($info)){
				$email = $info;
			}
			
			$uid = uc_user_register($username, $password, $email);
			if($uid <= 0) {
				if($uid == -1) {
					show_error('profile_username_illegal');
				} elseif($uid == -2) {
					show_error('profile_username_protect');
				} elseif($uid == -3) {
					show_error('profile_username_duplicate');
				} elseif($uid == -4) {
					show_error('profile_email_illegal');
				} elseif($uid == -5) {
					show_error('profile_email_domain_illegal');
				} elseif($uid == -6) {
					show_error('profile_email_duplicate');
				} else {
					show_error('undefined_action');
				}
			}
		}
		
		list($uc_uid) = uc_get_user($username);
		$synlogin_script = uc_user_synlogin($uc_uid);
	
	}
	
	return $synlogin_script;
}


/* 保存日志到库中 */
function log_save($name,$type,$data){
	global $db;
	$row = array();
	$row['log_type'] = $type;
	$row['log_name'] = $name;
	$row['log_time'] = time();
	$row['log_data'] = serialize($data);
	$db->insert($row,DB_PREFIX."log");
}

/* 生成广告静页地址 */
function gen_adv($adv_id,$saveFile=true,$system_key=''){
	global $db,$system,$db_admin,$json;
	$row = $db_admin->getRow("select * from ".DB_PREFIX."adv inner join  ".DB_PREFIX."v_games on adv_pid=pf_id and adv_gid=server_gid and adv_sid=server_id where adv_id='{$adv_id}' ");
	if(!empty($row)){
		$at = $db_admin->getRow("select * from ".DB_PREFIX."advtype where advtype_id='{$row[adv_type]}' ");
		
		//读取子站点分类素材
		$cls_mats = $json->decode($row['adv_cls'],1);
		
		if(!empty($cls_mats)){
			$ids = '';
			$spt = '';
			foreach($cls_mats as $k => $v){
				$ids .= $spt . $v['id'];
				$spt = ',';
			}
			$mats_cls =$db_admin->getAll("select material_id,material_left,material_top from ".DB_PREFIX."material where material_id in ({$ids}) ");
			foreach($cls_mats as $k => $v){
				if(!empty($mats_cls)){
					foreach($mats_cls as $key => $val){
						if($v['id'] == $val['material_id']){
							$cls_mats[$k]['left'] = $val['left'];
							$cls_mats[$k]['top'] = $val['top'];
						}
					}
				}
			}
		}
		$mats_cls = $json->encode($cls_mats);
		
		$mats = array();
		$mats[0] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid]}' ");
		$mats[1] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid1]}' ");
		$mats[2] = $db_admin->getRow("select * from ".DB_PREFIX."material where material_id='{$row[adv_metrid2]}' ");
	
		if($saveFile){
			ob_start();
			include(WEB_ROOT."/webmanage/templates/{$row[adv_tpl]}.html");
			$str   =   ob_get_contents();
			ob_end_clean();
			make_dir(WEB_ROOT."/advs/ad/");
			$f   =   fopen( WEB_ROOT."/advs/ad/" . $row['adv_id'] . ".html", "w"); 
			fwrite($f, $str);
			fclose($f); 
			
			$adv_root = '';
			foreach($system as $k => $v){
				if($v['id']==$row['adv_system']){
					$adv_root=$v['ADV_HTTP'];
				}
			}
			return $adv_root."/{$at[advtype_code]}/{$row[adv_id]}.html";
		}else{
			
			include(WEB_ROOT."/webmanage/templates/{$row[adv_tpl]}.html");
		}
	}else{
		
		@unlink(WEB_ROOT."/advs/ad/" . $adv_id . ".html");
	}
	return 0;
}


	/* 生成广告静页地址 */
	function gen_advbg($advbg_id,$saveFile=true){
		global $db_admin,$json;
		$row = $db_admin->getRow("select * from ".DB_PREFIX."advbg where advbg_id='{$advbg_id}' ");
		if(!empty($row)){
			
	
			if($saveFile){
				ob_start();
				include(WEB_ROOT."/webmanage/templates/{$row[advbg_tpl]}.html");
				$str   =   ob_get_contents();
				ob_end_clean();
				make_dir(WEB_ROOT."/api/js/");
				$f   =   fopen( WEB_ROOT."/api/js/" . $row['advbg_id'] . ".js", "w"); 
				fwrite($f, $str);
				fclose($f); 
				
				$adv_root = YOU_ROOT;
				return $adv_root."/api/js/{$row[advbg_id]}.js";
			}else{
				
				include(WEB_ROOT."/webmanage/templates/{$row[advbg_tpl]}.html");
			}
		}else{
			
			@unlink(WEB_ROOT."/api/js/" . $advbg_id . ".js");
		}
		return 0;
	}

	//返回当前IP的城市字符串   
	function convertip($ip) {   
		//IP数据文件路径   
		$dat_path = WEB_ROOT . '/include/QQWry.Dat';   
	   
		//检查IP地址   
		if(!preg_match("/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/", $ip)) {   
			return 'IP Address Error';   
		}   
		//打开IP数据文件   
		if(!$fd = @fopen($dat_path, 'rb')){   
			return 'IP date file not exists or access denied';   
		}   
	   
		//分解IP进行运算，得出整形数   
		$ip = explode('.', $ip);   
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];   
	   
		//获取IP数据索引开始和结束位置   
		$DataBegin = fread($fd, 4);   
		$DataEnd = fread($fd, 4);   
		$ipbegin = implode('', unpack('L', $DataBegin));   
		if($ipbegin < 0) $ipbegin += pow(2, 32);   
		$ipend = implode('', unpack('L', $DataEnd));   
		if($ipend < 0) $ipend += pow(2, 32);   
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;   
		   
		$BeginNum = 0;   
		$EndNum = $ipAllNum;   
	   
	   	$ip1num = 0;
		$ip2num = 0;
		$ipAddr1 = ''; 
		$ipAddr2 = '';
		//使用二分查找法从索引记录中搜索匹配的IP记录   
		while($ip1num>$ipNum || $ip2num<$ipNum) {   
			$Middle= intval(($EndNum + $BeginNum) / 2);   
	   
			//偏移指针到索引位置读取4个字节   
			fseek($fd, $ipbegin + 7 * $Middle);   
			$ipData1 = fread($fd, 4);   
			if(strlen($ipData1) < 4) {   
				fclose($fd);   
				return 'System Error';   
			}   
			//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂   
			$ip1num = implode('', unpack('L', $ipData1));   
			if($ip1num < 0) $ip1num += pow(2, 32);   
			   
			//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环   
			if($ip1num > $ipNum) {   
				$EndNum = $Middle;   
				continue;   
			}   
			   
			//取完上一个索引后取下一个索引   
			$DataSeek = fread($fd, 3);   
			if(strlen($DataSeek) < 3) {   
				fclose($fd);   
				return 'System Error';   
			}   
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));   
			fseek($fd, $DataSeek);   
			$ipData2 = fread($fd, 4);   
			if(strlen($ipData2) < 4) {   
				fclose($fd);   
				return 'System Error';   
			}   
			$ip2num = implode('', unpack('L', $ipData2));   
			if($ip2num < 0) $ip2num += pow(2, 32);   
	   
			//没找到提示未知   
			if($ip2num < $ipNum) {   
				if($Middle == $BeginNum) {   
					fclose($fd);   
					return 'Unknown';   
				}   
				$BeginNum = $Middle;   
			}   
		}   
	   
		$ipFlag = fread($fd, 1);   
		if($ipFlag == chr(1)) {   
			$ipSeek = fread($fd, 3);   
			if(strlen($ipSeek) < 3) {   
				fclose($fd);   
				return 'System Error';   
			}   
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));   
			fseek($fd, $ipSeek);   
			$ipFlag = fread($fd, 1);   
		}   
	   
		if($ipFlag == chr(2)) {   
			$AddrSeek = fread($fd, 3);   
			if(strlen($AddrSeek) < 3) {   
				fclose($fd);   
				return 'System Error';   
			}   
			$ipFlag = fread($fd, 1);   
			if($ipFlag == chr(2)) {   
				$AddrSeek2 = fread($fd, 3);   
				if(strlen($AddrSeek2) < 3) {   
					fclose($fd);   
					return 'System Error';   
				}   
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));   
				fseek($fd, $AddrSeek2);   
			} else {   
				fseek($fd, -1, SEEK_CUR);   
			}   
	   
			while(($char = fread($fd, 1)) != chr(0))   
				$ipAddr2 .= $char;   
	   
			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));   
			fseek($fd, $AddrSeek);   
	   
			while(($char = fread($fd, 1)) != chr(0))   
				$ipAddr1 .= $char;   
		} else {   
			fseek($fd, -1, SEEK_CUR);   
			while(($char = fread($fd, 1)) != chr(0))   
				$ipAddr1 .= $char;   
	   
			$ipFlag = fread($fd, 1);   
			if($ipFlag == chr(2)) {   
				$AddrSeek2 = fread($fd, 3);   
				if(strlen($AddrSeek2) < 3) {   
					fclose($fd);   
					return 'System Error';   
				}   
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));   
				fseek($fd, $AddrSeek2);   
			} else {   
				fseek($fd, -1, SEEK_CUR);   
			}   
			while(($char = fread($fd, 1)) != chr(0)){   
				$ipAddr2 .= $char;   
			}   
		}   
		fclose($fd);   
	   
		//最后做相应的替换操作后返回结果   
		if(preg_match('/http/i', $ipAddr2)) {   
			$ipAddr2 = '';   
		}   
		$ipaddr = "$ipAddr1 $ipAddr2";   
		$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);   
		$ipaddr = preg_replace('/^s*/is', '', $ipaddr);   
		$ipaddr = preg_replace('/s*$/is', '', $ipaddr);   
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {   
			$ipaddr = 'Unknown';   
		}   
	   
		return $ipaddr;   
	}  
	


?>