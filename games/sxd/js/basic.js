// JavaScript Document
QueryString = function(qs) {
    this.p = {};
    this.url = "";
    if (!qs)
        url = location.search;
    if (qs) {
        var b = qs.indexOf('?');
        var e = qs.indexOf('#');
        if (b >= 0) {
            this.url = qs.substring(0, b);

            qs = e < 0 ? qs.substr(b + 1) : qs.substring(b + 1, e);
            if (qs.length > 0) {
                qs = qs.replace(/\+/g, ' ');
                var a = qs.split('&');
                for (var i = 0; i < a.length; i++) {
                    var t = a[i].split('=');
                    var n = decodeURIComponent(t[0]);
                    var v = (t.length == 2) ? decodeURIComponent(t[1]) : n;
                    this.p[n] = v;
                }
            }
        } else {
            this.url = qs;
        }
    }
    this.set = function(name, value) {
        this.p[name] = value;
        return this;
    };
    this.get = function(name, def) {
        var v = this.p[name];
        return (v != null) ? v : def;
    };
    this.has = function(name) {
        return this.p[name] != null;
    };
    this.toStr = function() {
        var r = this.url + '?' + this.toQs();
        return r;
    };
    this.toQs = function() {
        var r = '';
        var spt = "";
        for (var k in this.p) {
            r += spt + encodeURIComponent(k) + '=' + encodeURIComponent(this.p[k]);
            spt = "&";
        }
        return r;
    };
};

//ajax提交数据 
function ajax_action(act_url, params, confirmmsg, completefun) {
    function showconfirm(btn) {
        if (btn == "yes") {
            var qs = new QueryString(act_url);
            var asyn = true;
            if (params) {
                for (var i in params) {
                    if (i == "asyn") {
                        asyn = params[i];
                    } else {
                        qs.set(i, params[i]);
                    }
                }
            }
			qs.set("is_ajax", 't');
            qs.set("rnd", Math.random());
            var url = qs.url;
            var postBody = qs.toQs();
			$.ajax({
				type: "POST",
				url: url,
				data: postBody,
				async:asyn,
				success: function(data){
					var isOk = true;
					if (data.indexOf("E@") == 0) {
						msgalert("警告", data.replace("E@", ""));
						isOk = false;
					}
					if (completefun) {
						completefun(data,isOk);
					}
				}
			});
         
        }
    }

    if (confirmmsg == null) {
        showconfirm('yes');
    } else {
        msgconfirm('确认', confirmmsg, showconfirm);
    }
    
}


function msgconfirm(title,msg,fun){
	if(confirm(msg)){
		fun('yes');
	}else{
		fun('no');
	}
}

function msgalert(title,msg){
	alert(msg);
}

function copy_text(sUrl){
	var tempCurLink=sUrl;
	if(window.clipboardData){
		var ok=window.clipboardData.setData("Text",tempCurLink);
		if(ok){ 
			return true;
		}else if(window.netscape){  
			try{  
					  netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
			} catch   (e)   {  
				return false;
					   
			}  
			var   clip   =   Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);  
			if   (!clip)  
					  return;  
			var   trans   =   Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);  
			if   (!trans)  
					  return;  
			trans.addDataFlavor('text/unicode');  
			var   str   =   new   Object();  
			var   len   =   new   Object();  
			var   str   =   Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);  
			var   copytext   =   tempCurLink;  
			str.data   =   copytext;  
			trans.setTransferData("text/unicode",str,copytext.length*2);  
			var   clipid   =   Components.interfaces.nsIClipboard;  
			if   (!clip)  
					  return   false;  
			clip.setData(trans,null,clipid.kGlobalClipboard);
			return true;
		}  
	}
}

function AddFavorite(sURL, sTitle)
{
    try
    {
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e)
    {
        try
        {
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e)
        {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
function SetHome(obj,vrl){
	try{
			obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	}
	catch(e){
			if(window.netscape) {
					try {
							netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
					}
					catch (e) {
							alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 [signed.applets.codebase_principal_support]设置为'true'");
					}
					var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
					prefs.setCharPref('browser.startup.homepage',vrl);
			 }
	}
}