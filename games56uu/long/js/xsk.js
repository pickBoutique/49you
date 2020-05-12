// JavaScript Document
function v(id){
	return document.getElementById(id);
}

function check_get_xsk(){
	
	var game_id = v('game_id').value;
	var server_id = v('server_id_card').value;
	var login_name = v('login_name_card').value;
	var login_pwd = v('login_pwd_card').value;
	if (server_id==""){
	 	alert("请选择服务器！");
		return false;
	 }	
	//alert(login_name);
	if (login_name==""){
	 	alert("请输入账号！");
		return false;
	}
	
	if (login_pwd==""){
	 	alert("请输入密码！");
		return false;
	}
	
	var paras='&act=1&login_name='+login_name+'&login_pwd='+login_pwd+'&server_id='+server_id;
	get_xsk(paras);
}

function check_get_xsk_radio(){
	
	var radioSelete = "Nothing";
    var seletedValue;
    for(i=0;i<document.frmXsk.server_id_card.length;i++){
        if(document.frmXsk.server_id_card[i].checked)  {
         radioSelete = "seleted";
         var server_id = document.frmXsk.server_id_card[i].value;
        }
    }
	
	alert(document.frmXsk.server_id_card.length);	
	var game_id = v('game_id').value;
	//var server_id = v('server_id_card').value;
	//var login_name = v('login_name_card').value;
	//var login_pwd = v('login_pwd_card').value;
	if ( server_id==""){
	 	alert("请选择服务器！");
		return false;
	 }	
	
	/*if (login_name==""){
	 	alert("请输入账号！");
		return false;
	}
	
	if (login_pwd==""){
	 	alert("请输入密码！");
		return false;
	}*/

	//var paras='&act=1&login_name='+login_name+'&login_pwd='+login_pwd+'&game_id='+game_id+'&server_id='+server_id;
	var paras='&act=1&game_id='+game_id+'&server_id='+server_id;
	get_xsk(paras);
}

function get_xsk(paras) {
	$.ajax(
    {
        type:"POST",
        url:"/get_xsk.php",
		data:paras,
        success: function(result){ 
					if(result=='error'){
						alert('领取失败，请检查是否登陆!');
						return false;
					}else if(result=='over'){
						alert('新手卡礼包卡已经全部赠送完了，请您及时向客服反应!');
						return false;
					}else if(result=='no'){
						alert('新手卡领取失败，请检查帐号跟角色一致，选择服务器没有!');
						return false;
					}else{
						v('xsk').style.display = "none";
						v('xsk_h').style.display = "block";
						v('card_id').innerHTML = result;
						return true;
					}
        },
        error:function(){
            alert('连接失败!');
            return false;
        }
    });
}