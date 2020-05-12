function getsec(str){
	var str1=str.substring(1,str.length)*1; 
	var str2=str.substring(0,1); 
	if (str2=="s"){
	return str1*1000;
	}else if (str2=="h"){
	return str1*60*60*1000;
	}else if (str2=="d"){
	return str1*24*60*60*1000;
	}
}
function setCookie(name,value,time){
	var strsec = getsec(time);
	var exp = new Date();
	exp.setTime(exp.getTime() + strsec*1);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

function getCookie(name){
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg)) return unescape(arr[2]);
	else return null;
}

function delCookie(name){
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

function redirect(url){
    if(url){
        window.location.href = url+window.location.search;
    }
}

$(document).ready(function(){
(function(){
    try{
        var lastStr = '进入游戏';
        var intv;
        var lastBtn;
        function loading(btn){
		
        }
        function revertBtn(){
            $(lastBtn).removeClass('btn-link-disable');
            $(lastBtn).html(lastStr);
            clearInterval(intv);
        }

        var tmp_username_cookie = 'xd_sg_username';
        var tmp_sid_cookie = 'xd_sg_sid';
        var tmp_sname_cookie = 'xd_sg_sname';
		
	
        

        var logonUser = null;
		
		
		
		function showLogin(){
			$('#log-box').show().siblings().hide();
			$('#usname').focus();
		}

		function showRegister(){
			
			$('#reg-box').show().siblings().hide();
			$('#username').focus();
		}
		
		function memberStart() {
			$.getJSON(you_root + "/login.html?rnd="+Math.random()+"&act=ajlogin&gid="+gid+"&username=&password=&save_id=&format=json&jsoncallback=?",
			function(data){
				var _member = data;
				if(_member.errormsg == ""){
					$('.s_username').html(_member.username);
            		$('.s_name').html(_member.lasts_name);
					$('#serverSelect').val(_member.lasts_id);
					$('div #played_list .just').append($('#all_list .just a#sid_'+_member.lasts_id).clone());
            		
					//附加服务器点击事件
					$('a.s_list').not('.weihu').click(function(){
						$('#serverSelect').val($(this).attr('id').substring(4));
						$('.s_name').html($(this).text());
						$('.close_s').click();
					});
				}else{
					redirect('client_login.html');
				}
			});
		}
		
		function memberLogin() {
			
			$('#login_error_div').html('');
			
			var usname=escape($("#usname").val());
			var uspsd=escape($("#uspsd").val());
			var saveid=$("#save_id").val();
			
			$.getJSON(you_root + "/login.html?rnd="+Math.random()+"&act=ajlogin&gid="+gid+"&username="+usname+"&password="+uspsd+"&save_id="+saveid+"&format=json&jsoncallback=?",
			function(data){
				var _member = data;
				if(_member.errormsg == ""){
					if(usname != ''){
						setCookie('sg_client_usname',usname,"s999999");
						redirect('client_start.html');
					}
				}else{
					$('#login_error_div').html(_member.errormsg);
					$('#login_password').focus();
				}
				revertBtn();
			});
			
			return false;
		}
		
		function regsubmit(){
			$('#register_error_div').html('');

			function onregister(data){
				if(data['status'] > 0){
					//document.write(data['msg']);
					redirect('client_start.html');
				}else{
					$('#register_error_div').html(data['msg']);
				}
				revertBtn();
			}
			
			$.getJSON(you_root + "/reg.html?rnd="+Math.random()+"&act=ajaxreg&gid="+gid+"&loginname="+escape($('#username').val())+"&psw="+ escape($('#password').val())+"&psw2="+escape($('#password_confirm').val())+"&format=json&jsoncallback=?", onregister);
			
			return false;
		}
		
		
		var sg_client_usname = getCookie('sg_client_usname');
		if(sg_client_usname){
			$("#usname").val(sg_client_usname);
		}
		
		$('#login_submit_btn').click(memberLogin);
		$('#UserLoginForm').submit(memberLogin);
		$('#goto_reg_btn').click(showRegister);
		$('#goto_log_btn').click(showLogin);
		$('#reg_submit_btn').click(regsubmit);
		$('#UserRegisterForm').submit(regsubmit);
	
		if($('.choose_s').length){
			memberStart();
		}
		
		$('.choose_s').click(function(){
                $('#step1').hide();
                $('#step2').show();
        });
        $('.close_s').click(function(){
                $('#step2').hide();
                $('#step1').show();
        });
        $('.s_name').click(function(){
            //$(this).css('background','url(http://game.verycd.com/game/images/focus_line.jpg)')
        });
		
		$('#start_game').click(function(){
			  if(!$('#serverSelect').val()){
				  return false;
			  }
			  
			  $.getJSON(you_root + "/login.html?rnd="+Math.random()+"&act=ajlogin&format=json&jsoncallback=?",
			  function(data){
				  var _member = data;
				  if(_member.errormsg == ""){
					  redirect(you_root + '/game_add.html?gid=' + gid + '&sid=' + $('#serverSelect').val() + '&dir=1');
				  }else{
					  redirect('client_login.html');
				  }
			  });
			  
			  return false;
		  }).focus();
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
        
    }catch(err){
	}
})();

});