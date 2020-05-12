// JavaScript Document
function member_referrer(){
	jQuery.cookie = function(name, value, options) {
		if (typeof value != 'undefined') { // name and value given, set cookie
			options = options || {};
			if (value === null) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
				var date;
				if (typeof options.expires == 'number') {
					date = new Date();
					date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
				} else {
					date = options.expires;
				}
				expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
			}
			var path = options.path ? '; path=' + options.path : '';
			var domain = options.domain ? '; domain=' + options.domain : '';
			var secure = options.secure ? '; secure' : '';
			document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		} else { // only name given, get cookie
			var cookieValue = null;
			if (document.cookie && document.cookie != '') {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = jQuery.trim(cookies[i]);
					// Does this cookie string begin with the name we want?
					if (cookie.substring(0, name.length + 1) == (name + '=')) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}
	};
	var member_ref="";
	var domain=document.location.href;
	var urlarr=domain.split(".");
	var com=urlarr[2].split("/");
	domain=urlarr[1]+"."+com[0];
	this.set_domain=function(dom){
		domain=dom;
	}
	this.set_refcookie=function(url_ref){
		$.cookie("member_referrer",url_ref,{expires: 7, path: '/', domain: domain}); 
		var pos_link="http://www."+domain+"/reg.html";
		pos_link+="?act=guestaccess";
		pos_link+=("&format=json&jsoncallback=?");
		$.getJSON(pos_link,function(data){return;});
	}
	
	member_ref=$.cookie('member_referrer');
	if(member_ref==null){
		member_ref=document.referrer;
		if(member_ref.indexOf(domain)>0) return;
		$.cookie("member_referrer",member_ref,{expires: 7, path: '/', domain: domain}); 
	}

	location_ref=$.cookie('location_ref');
	if(location_ref==null && member_ref!=null){
		location_ref=document.location.href;
		$.cookie("location_ref",location_ref,{expires: 7, path: '/', domain: domain}); 
		
		var pos_link="http://www."+domain+"/reg.html";
		pos_link+="?act=guestaccess";
		pos_link+=("&format=json&jsoncallback=?");
		$.getJSON(pos_link,function(data){return;});
	}
	
}

var memref= new member_referrer();