function register(){
    var login_account = $("#reg_account").val();
    var password = $("#reg_password").val();
    var password1 = $("#reg_rpassword").val();
	var email = $("#email").val();
	var username = $("#name").val();
	var id_card_number = $("#id_card_number").val();

	if (!/^.{6,14}$/.test(login_account)) {
		alert("请输入长度为6~14位的帐号");
		return;
	}
	if (!/^.{6,14}$/.test(password)) {
		alert("密码有误,长度为6~14位的密码");
		return;
	}
	if (!/^.{6,14}$/.test(password1)) {
		alert("密码有误,长度为6~14位的密码");
		return;
	}
	if (password != password1) {
		alert("两次输入的密码不一致");
		return;
	}
	if(username==''){
		alert("请输入您的真实姓名！");
		return;
	}else{
		if(!/^[\u4e00-\u9fa5]+$/.test($("#name").val()) ){
			alert("请输入您的真实姓名！");
			return;
		}
	}
	if(id_card_number==''){
		alert("请输入您的身份证！");
		return;
	}
	if (!/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/i.test(email)) {
		alert("邮箱有误");
		return;
	}
	$.ajax({
		url:"/login.php?action=save",
	    data:{login_account:$("#reg_account").val(),
	    password:$("#reg_password").val(),
	    password1:$("#reg_rpassword").val(),
		sg:$("#p").val(),
	    email:email,
	    username:username,
	    id_card_number:id_card_number},
	    type: "POST",
	    dataType: "text",
	    success:function(json) {
	        if(json =='true'){
				 $("#register").hide();
			    	$(".loged").show();
	        	//window.location="/default.php";
	        }else{
	        	alert(json);
	        }
	    }
	});
}
$(function(){
	$("#userreg").click(function(){
		register();
	});
    $("#logout").click(function(){
    	$.ajax({
	        url:"/login.php?action=logout",
	        type: "POST",
	        async: false,
	        dataType:"html",
	        success:function(json) {
				
			    $("#register").show();
			    $(".loged").hide();
	        }
	    });
    });
	$.ajax({
		url:"/login.php?action=logint",
	    data:{},
	    type: "POST",
	    dataType: "text",
	    success:function(json) {
	        if(json){
				
				 $("#register").hide();
			    $(".loged").show();
				$("#font_1").html(json);
	        	//window.location="/default.php";
			}
	    }
	});
});