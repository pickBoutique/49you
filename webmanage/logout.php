<?php

include_once('init.inc.php');
$obj_user->clear_session();
$obj_user->clear_cookie();
exit("<script type=\"text/javascript\">top.location.href=\"login.php\";</script>");
?>