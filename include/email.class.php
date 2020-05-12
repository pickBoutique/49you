<?php
/**
 * 执行成功
 */
define('SUC_EMAIL',1); 
/**
 * 超时错误
 */
define('ERR_EMAIL_TIMEOUT',-1);

/*
*	电子邮箱类
*/
class EMail
{
	/*
	*	使用指定邮件模板发送邮件
	*   $to string 接收方的邮件地址，如abc@126.com
	*	$subject string 邮件标题
	*	$options array 可选项参数 可以为null
	*	$tpl_name string 模板名称 tpl_mail目录下的文件名称 不包括后缀名.php 
	*   $param array 模板需要用到的自定义参数
	*	$return true-成功 , false-失败 
	*/
	function send_template($to,$subject,$options=array("is_smtp"=>true,"is_html"=>true),$tpl_name,$param=array())
	{
		$html = '';
		$path = str_replace("\\","/",dirname(__FILE__)) . '/../tpl_mail';
		$file = $path . '/' . $tpl_name . '.html';
		if(!file_exists($file)){
			//模板文件不存在
			echo('无法找到指定的邮件模板：' . $tpl_name );
			return false;
		}
		
		if(isset($param['mid'])){
			$options['mid'] = $param['mid'];
		}
		if(isset($param['code'])){
			$options['code'] = $param['code'];
		}else{
			$param['code'] = $this->get_code($options['mid']);
			$options['code'] = $param['code'];
		}
		if(isset($param['asid'])){
			$options['asid'] = $param['asid'];
		}
		
		//获取邮件html内容输出
		$html = $this->parse_template($file,$param);
	
		
		
		//替换内容的相关文件路径
		$rootpath = "http://".$_SERVER['HTTP_HOST']."/tpl_mail/";
			
		$html = preg_replace('/(?<=src=[\'"])(?!(\/|http:))/sm', $rootpath, $html);
		$html = preg_replace('/(?<=url\()(?!(\/|http:))/sm', $rootpath, $html);
		//$html = preg_replace('/(?<=src=[\'"])(?=\\/)/sm', $rootpath, $str);
		$html = preg_replace('/(?<=href=[\'"])(?!(\/|http:))/sm', $rootpath, $html);
		//$html = preg_replace('/(?<=href=[\'"])(?=\\/)/sm', $rootpath, $html);
	
	
			
		return $this->sendMail($to,$subject,$html,$options);
	}
	
	/*
		获取并转换指定模板文件内容输出
		$file:模板文件名
		$param:模板所需要的数据
		$return string 转成后的html
	*/
	function parse_template($file,$param=array()){
		global $config;
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
	
	/*
	*	发送邮件 
	*	$to string 对方的邮件地址
	*	$subject string 邮件的标题
	*	$body string 邮件的内容
	*	$param array 邮件的发送设置
	*	$return true-成功 false-失败
	*/
	function  sendMail($to,$subject,$body,$param=array("is_smtp"=>true,"is_html"=>true))
	{
		global $db;
		
		include_once("phpmailer.class.php");
		if(!$to){return false;}
		if(!is_array($to))
		{
			$to = explode(",",$to);
		}
		$mail_user = "CS_49you@163.com";
		$mail_pwd = "497030";
		$mail_server = "smtp.163.com";
		$mail_name = "49you";
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
		
		if($rs){
			//添加到邮件发送记录表
			/*
			$row = array();
			$row['email_to'] = implode(",",$to);
			$row['email_sender'] = $mail_user;
			$row['email_subject'] = $subject;
			$row['email_content'] = $body;
			$row['email_mid'] = $param['mid'];
			$row['email_code'] = $param['code'];
			$row['email_ret'] = $rs;
			$row['email_time'] = time();
			$row['email_asid'] = $param['asid'];
			$db->insert($row,DB_PREFIX.'email');
			*/
		}
		
		return $rs;
	}
	
	
	/*
	*	生成md5校验码
	*/
	function get_code($mid='0'){
		return md5($mid.time());
	}
	
	/*
	*	校验邮件合法性
	*	$mid 会员的id
	*	$code string md5校验码
	*	$once bool true-一次性校验 
	*	$return bool true-合法 false-非法
	*/
	function check_valid($mid,$code,$once=true){
		global $db;
		$rs = $db->getRow("select email_id,email_code,email_state from " . DB_PREFIX ."email where email_mid='{$mid}' and email_state='0' and  email_code='{$code}' limit 0,1 ");
		if($rs){
			if($once){
				$rs['email_state'] = '1';
				$db->update($rs,DB_PREFIX ."email");
			}
			return true;
		}
		return false;
	}
}
?>