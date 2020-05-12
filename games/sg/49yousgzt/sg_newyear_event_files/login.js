if(typeof xd == 'undefined') document.write('<script type="text/javascript" src="http://web.xdcdn.net/xd/js/xd.js"></' + 'script>');
var hosts = ['game.verycd.com', 'local.game.verycd.com', 'sg.xd.com', 'local.sg.xd.com', 'game.office.verycd.com', 'sg.office.xd.com'];
if($.inArray(window.location.host, hosts) == -1){
    window.location = 'http://sg.xd.com';
}
var url = '/html/sg/entry.html';
function playGame(){
	window.location.href = url;
	
}
function showWarning(){
    $('body').append('<div class="black_drop2"></div>');
    var sTop = $(document).scrollTop();
    $('.black_drop2').show();
    $('.popup_password').css('top',(sTop + 100)+'px').show();
}
function showDangerCity(city){
    $('body').append('<div class="black_drop2"></div>');
    var sTop = $(document).scrollTop();
    $('.black_drop2').show();
    $('.popup_danger_city').css('top',(sTop + 100)+'px').show();
    $('#dangerCity').html(city);
}
function showCsdnUser(username){
    $('#csdn_user').html(username);
    $('body').append('<div class="black_drop2"></div>');
    var sTop = $(document).scrollTop();
    $('.black_drop2').show();
    $('.popup_csdn_user').css('top',(sTop + 100)+'px').show();
}
$('#popup_password_close').click(function(){
    $('.black_drop2').hide();
    $('.popup_password').hide();
});
$('.close_popup_danger_city').click(function(){
    xd.setCookie('xd[hddc]', 'hide', 30, '/', '.xd.com');
    $('.black_drop2').hide();
    $('.popup_danger_city').hide();
});
$('.close_popup_csdn').click(function(){
    $('.black_drop2').hide();
    $('.popup_csdn_user').hide();
});
//$('.logging').show();
function showUserInfo(user){
    if(user && user.id > 0){
		$('.no_login_padding').hide();
        $('.logging').hide();
        $('.yes_login').show();
        $('#logonusername').html(user.username);
        if(user.lastserver){
            url = xd.getPlayUrl(user.app, user.lastsid);
            url += '&client=web';
            var sname = user.lastserver;
        }else if(user.newserver){
            $('#newserver').show();
            $('#history').hide();
            url = xd.getPlayUrl(user.app, user.newsid);
            url += '&client=web';
            var sname = user.newserver;
        }
        if(user.dangerCity && xd.getCookie('xd[hddc]') !== 'hide'){
            showDangerCity(user.dangerCity);
        }else if(user.psst == '1'){
            showWarning();
        }else if(user.passwordLeaked){
            showCsdnUser(user.username);
        }
        $('#server a').attr('href', url).html(sname);
        	get_servers(user);
    }else{
		 $('.no_login').show();
	     $('.yes_login').hide();
    }
}
function get_servers(user){
    $.ajax({
        url: 'http://sgi.xd.com/api/servers',
        dataType: 'jsonp',
        success: function(data){
            if(data && data.length > 0){
                var maxSid = data.length - 1;                  
                $('#all_list').empty();
                $('#played_list').empty();
                for(var i in data){
                    var sid = 'S'+(maxSid-i);
                    var server = data[i].name;
                    var server_status = '';
                    var server_style = '';
                    if(data[i].weihu){
                        server_status = '<span style="color:grey;">(维护)</span>';
                        server_style = 'weihu';
                    }
                    var url = xd.getPlayUrl('sssg', sid) + '&client=web';
                    var element = '<a id="sid_'+sid+'" class="'+server_style+'" href="'+url+'" target="_blank">'+server+server_status+'</a>';
                    $('#all_list').append(element);
                    if(i == 0){
                    	$('#new_list').empty();
                        $('#new_list').append(element);
                    }
                }
                if(user.history && user.history.length > 0){
                	for(var i in user.history){
                		$('#played_list').append($('#all_list a#sid_'+user.history[i].sid).clone());
                        if(i >= 2){
                            break;
                        }
                    }
                }
                $('.popup_just a').not('.weihu').click(function(){
                    $('#server a').attr('href', $(this).attr('href')).html($(this).text());
                });
                if(window.location.href.indexOf('/card') > 0){
                    show_card(user.id, data);
                }
            }
        },
        data: {
            format : 'jsonp'
        }
    });
}
function logout(){
    xd.logout();
    $('.no_login_padding').show();
    $('.yes_login').hide();
    $('#login_authkey_div').hide();
    $('#login_error_div').hide();
    if(window.location.href.indexOf('/card') > 0){
        hide_card();
    }
	url = '/html/sg/entry4.html';
}

	xd.loginForm("#loginForm", showUserInfo , '/', 'sssg', false);
	$('#qq_login').click(function(){
		_gaq.push(['_trackEvent', 'QQ_Login', document.URL]);
        return false;
    })

