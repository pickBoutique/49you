<?php
/*
creater devil 2010-08-16
*/
@session_start();
if($_SESSION['sys_admin_login_status'] != 1)
{
	exit("<script type=\"text/javascript\">top.location.href='login.php';</script>");
}
?>