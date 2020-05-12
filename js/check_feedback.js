function checkForm(dom)
{
	if( dom.remark.value == '')
	{
		alert('请输入备注');
		dom.remark.focus();
		return false;
	}
}

function setAct(n)
{
	$("#act").val(n);
}

function setAct_turnout(n)
{
	var order_id = $("#order_id").val();
	var member_id = $("#member_id").val();
	var order_type_num = $("#order_type_num").val();
	var member_text = $("#member_text").val(); 
	var admin_text = $("#admin_text").val(); 
	var posturl = "register-turnout-action.php";
	var request = "act="+n+"&order_id="+order_id+"&member_id="+member_id+"&order_type_num="+order_type_num+"&member_text="+member_text+"&admin_text="+admin_text+"&ref="+Math.random();
	postAjax2(posturl,request);
}

function setAct_transfer(n)
{
	var poundage_money = $("#poundage_money").val();
	var order_id = $("#order_id").val();
	var member_id = $("#member_id").val();
	var order_type_num = $("#order_type_num").val();
	var member_text = $("#member_text").val(); 
	var admin_text = $("#admin_text").val(); 
	var posturl = "register-transfer-action.php";
	var request = "act="+n+"&poundage_money="+poundage_money+"&order_id="+order_id+"&member_id="+member_id+"&order_type_num="+order_type_num+"&member_text="+member_text+"&admin_text="+admin_text+"&ref="+Math.random();
	postAjax2(posturl,request);
}

function setAct_enroll(n)
{
	var user_name = $("#user_name").val();
	var web_user_name = $("#web_user_name").val();
	var order_id = $("#order_id").val();
	var member_id = $("#member_id").val();
	var order_type_num = $("#order_type_num").val();
	var member_text = $("#member_text").val(); 
	var admin_text = $("#admin_text").val(); 
	var posturl = "register-enroll-action.php";
	var request = "act="+n+"&user_name="+user_name+"&web_user_name="+web_user_name+"&order_id="+order_id+"&member_id="+member_id+"&order_type_num="+order_type_num+"&member_text="+member_text+"&admin_text="+admin_text+"&ref="+Math.random();
	postAjax2(posturl,request);
}

function setAct_visit(n)
{
	var order_id = $("#order_id").val();
	var member_id = $("#member_id").val();
	var refund_type = $("#refund_type").val();
	var order_status_num = $("#order_status_num").val();
	var pay_status_num = $("#pay_status_num").val();
	var member_text = $("#member_text").val(); 
	var admin_text = $("#admin_text").val(); 
	var posturl = "customer-visit-action.php";
	var request = "act="+n+"&order_id="+order_id+"&member_id="+member_id+"&refund_type="+refund_type+"&order_status_num="+order_status_num+"&pay_status_num="+pay_status_num+"&member_text="+member_text+"&admin_text="+admin_text+"&ref="+Math.random();
	postAjax2(posturl,request);
}













