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
            qs.set("rnd", Math.random());
            var url = qs.url;
            var postBody = qs.toQs();
			$.ajax({
				type: "POST",
				url: url,
				data: postBody,
				async:asyn,
				success: function(data){
					if (data.indexOf("E@") == 0) {
						msgalert("警告", data.replace("E@", ""));
					}
					if (completefun) {
						completefun(data);
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

function choose_all(dom,checkname)
{
	var bln=false;
	if(dom.checked)
	{
		bln=true;
	}
	$("[name='"+checkname+"']").attr("checked",bln);
}

function chooseMySub(dom,subclass)
{
	var bln=false;
	if(dom.checked)
	{
		bln=true;
	}
	$("."+subclass).attr("checked",bln);
}

function OperateSubmit(dom,checkid,url)
{
	var is_ok = confirm("确定执行此操作吗？");
	if(is_ok)
	{
		var num=0;
		$("[name='"+checkid+"']").each(function(){
			if( $(this).attr("checked") )
			{
				num ++;
			}
		});
		if(num == 0)
		{
			alert('请选择要操作的记录');
			return false;
		}
		else
		{
			dom.action=url;
			dom.method="post";
			dom.submit();
		}
	}
}

function changeOperate(operate)
{
	$("#act").val(operate);
}

//检查管理员用户名是否已经被使用
function checkAdminExists(input_id)
{
	var admin_name = $("#"+input_id).val();
	if( !checkUserName(admin_name) )
	{
		alert("用户名格式不正确");
		return false;
	}
	else
	{
		var url =  "check-admin-ajax.php";
		var request = "admin_name="+admin_name+"&ref="+Math.random();
		postAjax(url,request);
	}
}

//检查会员用户名是否已经被使用
function checkMemberExists(input_id)
{
	var email = $("#"+input_id).val();
	if( !isEmail(email) )
	{
		alert("用户名格式不正确");
		return false;
	}
	else
	{
		var url =  "check-member-ajax.php";
		var request = "email="+email+"&ref="+Math.random();
		$.ajax({
			type: "POST",
			url: url,
			data: request,
			success: function(res){
				var arr = res.split('|');
				var error = arr[0];
				var message = arr[1];
				
				if(error == 0)
				{
					alert(message);return false;
				}
				else if(error == 1)
				{
					alert(message);return false;	
				}
				else if(error == 2)
				{
					alert(message);return true;
				}
			}
		});
	}
}

//添加订单 检查会员用户名是否已经被使用
function orderCheckMemberExists(input_id, obj)
{
	var email = $("#"+input_id).val();
	if( !isEmail(email) )
	{
		alert("用户名格式不正确");
		return false;
	}
	else
	{
		var url =  "order-check-member-ajax.php";
		var request = "email="+email+"&ref="+Math.random();
		$.ajax({
			type: "POST",
			url: url,
			data: request,
			success: function(res){
				var arr = res.split('|');
				var error = arr[0];
				var message = arr[1];
				
				if(error == 0)
				{
					alert(message);return false;
				}
				else if(error == 1)
				{
					alert(message);return true;	
				}
				else if(error == 2)
				{
					alert(message);
					$(obj).parent().parent().after(arr[2]);
				}
			}
		});
	}
}

function postAjax(posturl,request)
{
	$.ajax({
		type: "POST",
		url: posturl,
		data: request,
		success: function(html){
			alert(html);
		}
	});
}

function postAjaxLoad(domid,posturl,request)
{
	$.ajax({
		type: "POST",
		url: posturl,
		data: request,
		success: function(html){
			$("#"+domid).html(html);
		}
	});
}

function postAjax2(posturl,request)
{
	$.ajax({
		type: "POST",
		url: posturl,
		data: request,
		success: function(html){
			document.write(html);
		}
	});
}

function checkNumber(str)
{
	if( isNaN(str) || str.indexOf('.')!=-1 )
	{
		return false;
	}
	else
	{
		return true;
	}
}

//验证用户名
function checkUserName(username)
{
	if(username.length<6 || username.length>20)
	{
		return false;
	}
	else
	{
		var regexp = /^[a-zA-Z][a-z0-9A-Z_]{5,19}$/;
		if( regexp.test(username) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

//验证域名格式
function checkDomain(domain)
{
	var regex = /\w*\.\w+$/;//必须包含.且不能出现在末尾
	if( regex.test(domain) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//验证密码
function checkPassWord(username)
{
	if(username.length<6 || username.length>20)
	{
		return false;
	}
	else
	{
		return true;
	}
}

//验证IP
function checkIP(ip)
{
	var regexp =  /^((1?\d?\d|(2([0-4]\d|5[0-5])))\.){3}(1?\d?\d|(2([0-4]\d|5[0-5])))$/;
	if( regexp.test(ip) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//验证电话，传真
function isTel(object)
{
	var reg =/^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;
	if( reg.test(object) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//验证手机号码
function isMobile(object)
{
	var reg0=/^\d{5,13}$/;  //130--139。至少7位
	var reg1=/^15\d{5,9}$/;   //150--159。至少7位
	if (reg0.test(object)){return true;}else{return false;};
	if (reg1.test(object)){return true;}else{return false;};
	if (reg2.test(object)){return true;}else{return false;};
	if (reg3.test(object)){return true;}else{return false;};
	if (reg4.test(object)){return true;}else{return false;};
}

//验证邮箱
function isEmail(object)
{
	var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if( reg.test(object) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//检查邮箱或手机号码是否被使用
function checkEmailOrMobile(v)
{
	v = encodeURIComponent(v);
	var url =  "/check-member-ajax.php?email="+v+"&ref="+Math.random();
	var rs = returnAjax(url);
	if(rs == '1')
	{
		return true;
	}
	else
	{
		return false;
	}
}

//检查验证码是否输入正确
function checkSeccode(c)
{
	c = encodeURIComponent(c);
	var url =  "/check-seccode-ajax.php?seccode="+c+"&ref="+Math.random();
	var rs = returnAjax(url);
	if(rs == '1')
	{
		return true;
	}
	else
	{
		return false;
	}
}

//弹出新窗口
function popupWindow(strUrl, intWidth, intHeight)
{
  window.open(strUrl, (new Date()).getSeconds(), 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+intWidth+',height='+intHeight);
	return false;
}

//changeImg
function changeImg(input_id,img_url)
{
	if(input_id == '')
	{
		return false;
	}
	window.opener.document.getElementById(input_id).value = img_url;
	if(window.opener.document.getElementById('view_'+input_id+'_div'))
	{
		var imgHW = " width='100' ";
		if( input_id == 'ico' )
		{
			imgHW = " width='32' height='32' ";
		}
		window.opener.document.getElementById('view_'+input_id+'_div').innerHTML="<img src='"+img_url+"' "+imgHW+" border='0' />";
		window.opener.document.getElementById('view_'+input_id+'_div').style.display='';
	}
	window.close();
}
function changeImg2(input_id,img_url)
{
	if(input_id == '')
	{
		return false;
	}
	window.opener.document.getElementById(input_id).value = img_url;
	window.close();
}

//获取会员列表
function getMember(domid,input_id)
{
	if(input_id == '' || domid == '')
	{
		return false;
	}
	var member_name = $("#"+input_id).val();
	member_name = encodeURIComponent(member_name);
	var url = "ajax_getmember.php?member_name="+member_name+"&ref="+Math.random();
	$("#"+domid).load(url,function(){
		$("#search_member_span").hide();
	});
}

function formatMoney(money)
{
	money = money+'';
	if(money.indexOf('.') != -1)
	{
		var s = money.split(".");
		var m1 = s[1].substr(0,2);
		var m2 = s[1].substr(2,1);
		if(m2 >= 5)
		{
			m1 = parseInt(m1,10)+1;
		}
		money = s[0]+"."+m1;
	}
	else
	{
		money = money+".00";
	}
	return money;
}

function createXMLHttpRequest()
{
	var ajaxHttp;
	if(window.XMLHttpRequest)
	{
		ajaxHttp= new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		ajaxHttp= new ActiveXObject("Microsoft.XMLHTTP");
	}
	return ajaxHttp;
}

function returnAjax(url)
{
	try{
		ajaxHttp = createXMLHttpRequest();
		ajaxHttp.open("GET", url, false);
		ajaxHttp.setRequestHeader("Content-Type", "text/html;charset=utf8");
		ajaxHttp.send(null);
		return ajaxHttp.responseText;
	}
	catch(e)
	{
		alert(e.message);
		return false;
	}
}

function showJqDialog(domid,w,h,title){

	var left = ($(window).width() - w) / 2;

	
	$('#'+domid).dialog({
		bgiframe:true,
		modal: true,
		width: w,
		height: h,
		autoOpen: false,
		show:'explode',
		title:title,
		position: [left]
	 });
	$('#'+domid).dialog('open');
	
	//$('#'+domid).dialog().setPosition();
	/*
	
    $('#'+domid).dialog().css({top:top,left:left});
*/
}

function hideJqDialog(domid){
	$('#'+domid).dialog('close');
}

function on_sear_select_change(obj,next_name,url,parent_name){
	var p_val = '';
	if(parent_name){
		var parents = document.getElementsByName(parent_name);
		p_val = parents[0].value;
	}
	ajax_action(url,{id:obj.value , parent:p_val},null,function(ret){
		
			var dom_obj = document.getElementsByName(next_name)[0];
			dom_obj.innerHTML = '';
			
			var obj = eval("(" + ret + ")");
			for (var i in obj) {
				var data = obj[i];
				dom_obj.options.add(new Option(data.text,data.value));
			}
			if(dom_obj.onchange){
				dom_obj.onchange();
			}
		});
}

//初始化搜索栏 id为搜索栏的div id,funname为搜索按钮单击事件
function renderSearchBar(id, funname) {
  
    var searchbar = document.getElementById(id);
	if(searchbar){
		var search = searchbar.innerHTML;
		var arrsearchs = eval('[' + search + ']');
		var html = "";
		for (var i = 0; i < arrsearchs.length; i++) {
			var arrsub = arrsearchs[i];
			var defaultval = '';
			var defaultval2 = '';
			if( typeof(arrsub.defaultValue) != 'undefined' ){
				defaultval = arrsub.defaultValue;
			}
			if( typeof(arrsub.defaultValue2) != 'undefined' ){
				defaultval2 = arrsub.defaultValue2;
			}
			//if (arrsub.length >= 3) {
				html += "<div class='searchoptions'>";
				if (arrsub.type == "textbox") {
					html += arrsub.header + "：<input name='" + arrsub.dataIndex + "' oper='@'  type='text' class='searCom x-form-text' value='" + defaultval + "' />";
				}
				if (arrsub.type == "text") {
					html += arrsub.header + "：<input name='" + arrsub.dataIndex + "' oper='=' type='text' class='searCom x-form-text'  value='" + defaultval + "'  />";
				}
				if (arrsub.type == "intrange") {
					html += arrsub.header + "：<input name='" + arrsub.dataIndex + "' oper='>=' type='text'  class='searCom x-form-text' size='5' />—";
					html += "<input name='" + arrsub.dataIndex + "' oper='<=' type='text'  class='searCom x-form-text'  size='5' />";
				}
				if (arrsub.type == "date_1") {
					html += arrsub.header + "：<div class='search_datetime'><input id='" + arrsub.dataIndex.replace(".","_") + "' name='" + arrsub.dataIndex + "' oper='=' type='text'  class='searCom Wdate' size='10' format='timespan'  value='" + defaultval + "'  /></div>";
				}
				if (arrsub.type == "datetime") {
					html += arrsub.header + "：<div class='search_datetime'><input id='" + arrsub.dataIndex.replace(".","_") + "_1' name='" + arrsub.dataIndex + "' oper='>=' type='text'  class='searCom Wdate' size='10' format='timespan'   value='" + defaultval + "'  /></div> — ";
					html += "<div  class='search_datetime'><input id='" + arrsub.dataIndex.replace(".","_") + "_2' name='" + arrsub.dataIndex + "' oper='<=' type='text'  class='searCom Wdate'  size='10' format='timespan'  value='" + defaultval2 + "' /></div>";
				}
				
				if (arrsub.type == "time") {
					html += arrsub.header + "：<div class='search_datetime'><input id='" + arrsub.dataIndex.replace(".","_") + "_1' name='" + arrsub.dataIndex + "' oper='>=' type='text'  class='searCom Wtime' size='10' format='timespan'   value='" + defaultval + "'  /></div> — ";
					html += "<div  class='search_datetime'><input id='" + arrsub.dataIndex.replace(".","_") + "_2' name='" + arrsub.dataIndex + "' oper='<=' type='text'  class='searCom Wtime'  size='10' format='timespan'  value='" + defaultval2 + "' /></div>";
				}
			  
				if (arrsub.type == "select") {
					var on_change = '';
					if(arrsub.subIndex && arrsub.url){
						if(arrsub.parentIndex){
							on_change = ' onchange="on_sear_select_change(this,\'' + arrsub.subIndex + '\',\'' +arrsub.url + '\',\'' +arrsub.parentIndex + '\');" ';
						}else{
							on_change = ' onchange="on_sear_select_change(this,\'' + arrsub.subIndex + '\',\'' +arrsub.url + '\');" ';
						}
					}
					html += arrsub.header + "：<select name='" + arrsub.dataIndex + "' oper='='  class='searCom' " + on_change + " >";
					for (var j in arrsub.options){
						if(arrsub.options[j] == defaultval){
							html += "<option value='" + arrsub.options[j] + "' selected >" + j + "</option>";
						}else{
							html += "<option value='" + arrsub.options[j] + "'>" + j + "</option>";
						}
					}
					html += "</select>";
				}
				
				if (arrsub.type == "checkbox") {
					html += arrsub.header + "：";
					for (var j in arrsub.options){
						var checked = '';
						var vals = defaultval.split(",");
						for (var kv in vals){
							if(vals[kv]==arrsub.options[j]){
								checked = 'checked';
							}
						}
						html += "<input type='checkbox'  class='searCom' name='" + arrsub.dataIndex + "' oper='=' value='" + arrsub.options[j] + "' " + checked + " />" + j;
					}
				}
				html += "</div>";
			 
			//}
		}
	
		html += '<div class="search_button" ><a href="javascript:void(0)" id="' + id + '_searchbtn">搜索</a></div><div class="clear"></div>';
		searchbar.innerHTML = html;
	
		document.getElementById(id + '_searchbtn').onclick = function() {
			var input = $('.searCom');
	
			var sql = "[";
			var checkobj = {};
			var opt_spt = '';
			for (var i = 0; i < input.length; i++) {
		   		if(input[i].value != ''){
					if(input[i].getAttribute('type') == 'checkbox' ){
						if(input[i].checked){
							if(!checkobj[input[i].getAttribute('name')]){
								checkobj[input[i].getAttribute('name')] = new Array();
								
							}
							checkobj[input[i].getAttribute('name')].push(input[i].value);
						}
					}else{
						var val = input[i].value;
						if(input[i].getAttribute('format')){
							if(input[i].getAttribute('format') == 'timespan'){
								var valdate = Date.parse(val.replace(/-/g, "/"));
								val = Math.round(valdate/1000);
							}
						}
						sql += opt_spt + '{"name":"' + input[i].getAttribute('name') + '","oper":"' + input[i].getAttribute('oper') + '","value":"' + val + '"}';
						opt_spt = ',';
					}
				}
			}
			for ( var j in checkobj){
				var chkvalue = '';
				var spt = '';
				for ( var k in checkobj[j] ){
					chkvalue += spt + checkobj[j][k];
					spt = ',';
				}
				sql += opt_spt + '{"name":"' + j + '","oper":"IN","value":"' + chkvalue + '"}';
			}
			sql += "]";
			if(funname){
				funname(sql);
			}
		}
		
		
		//为日期控件附加事件
		$('.Wdate').each(function (index, domEle) {    
			var dateid = domEle.id;
			//初始化开始日期选择器
			if (dateid.indexOf("_1") > -1) {
				var endfiled = dateid.replace("_1", "_2");
				$('#' + dateid).bind("focus",function(e){
					WdatePicker({maxDate:'#F{$dp.$D(\'' + endfiled + '\',{d:-1});}'});
				}); 
			} else if(dateid.indexOf("_2") > -1) {
			//初始化结束日期选择器
				var startfiled = dateid.replace("_2", "_1");
				$('#' + dateid).bind("focus",function(e){ 
					WdatePicker({minDate:'#F{$dp.$D(\'' + startfiled + '\',{d:1});}'});
				}); 
			} else {
				$('#' + dateid).bind("focus",function(e){ 
					WdatePicker();
				});
			}
		});
		
		//为时间控件附加事件
		$('.Wtime').each(function (index, domEle) {    
			var dateid = domEle.id;
			//初始化开始日期选择器
			if (dateid.indexOf("_1") > -1) {
				var endfiled = dateid.replace("_1", "_2");
				$('#' + dateid).calendar();
			} else {
			//初始化结束日期选择器
				var startfiled = dateid.replace("_2", "_1");
				$('#' + dateid).calendar();
			}
		});
		
	}
}

function show_editor(options){
	JqueryDialog.init(options);
	JqueryDialog.Maximize();
}



/*start 游戏服务器级联下拉组件 */

/* 事件：下拉框数据加载完成后
function on_server_load(domid,parent_id,rt,id)
*/

/* 事件：下拉框值改变后
function on_server_change()
*/

function on_game_change(){
	get_servers(this.serverid,this.value,this.platform_id);
}

function on_platform_change(){
	get_games(this.gameid,this.serverid,this.value);
}

function get_platforms(domid,gameid,serverid){
	url = "ajax-games.php";
	ajax_action(url,{act:'get_platforms'},null,function(data){

			var dom_obj = document.getElementById(domid);
			dom_obj.innerHTML = '';
			
			dom_obj.gameid = gameid;
			dom_obj.serverid = serverid;
			dom_obj.onchange = on_platform_change;
			
			var obj = eval("(" + data + ")");
			
			if( typeof(on_platform_load) != "undefined"){
				on_platform_load(domid,obj);
			}
			
			for (var i in obj) {
				var data = obj[i];
				dom_obj.options.add(new Option(data.platform_name,data.platform_id));
			}
			var def_input = document.getElementById(domid + '_default');
			if(def_input && def_input.value != ''){
				dom_obj.value = def_input.value;
			}
			
			
			dom_obj.onchange();
			
		});
}


function get_games(domid,serverid,platform_id){
	url = "ajax-servers.php";
	ajax_action(url,{parent:platform_id},null,function(data){
		
			var dom_obj = document.getElementById(domid);
			dom_obj.innerHTML = '';
			dom_obj.platform_id = platform_id;
			dom_obj.serverid = serverid;
			dom_obj.onchange = on_game_change;
			
			var obj = eval("(" + data + ")");
			
			if( typeof(on_game_load) != "undefined"){
				on_game_load(domid,obj);
			}
			
			for (var i in obj) {
				var data = obj[i];
				dom_obj.options.add(new Option(data.game_name,data.game_id));
			}
			var def_input = document.getElementById(domid + '_default');
			if(def_input && def_input.value != ''){
				dom_obj.value = def_input.value;
			}
			
			
			dom_obj.onchange();
			
		});
}

function get_servers(domid,game_id,platform_id)
{
	if(!game_id){return false;}
	url = "ajax-servers.php";
	
	ajax_action(url,{game_id:game_id,parent:platform_id},null,function(data){
			var dom_obj = document.getElementById(domid);
			dom_obj.innerHTML = '';

			if( typeof(on_server_change) != "undefined"){
				dom_obj.onchange = on_server_change;
			}
			var obj = eval("(" + data + ")");
			if( typeof(on_server_load) != "undefined"){
				on_server_load(domid,game_id,obj);
			}
			
			for (var i in obj) {
				var data = obj[i];
				dom_obj.options.add(new Option(data.server_name,data.server_id));
			}
			var def_input = document.getElementById(domid + '_default');
			
			if(def_input && def_input.value != ''){
				$(dom_obj).val(def_input.value);
			}
			dom_obj.onchange();
		});
	
}


/*end 地区区域选择组件 */


/*start 地区区域选择组件 */

/* 事件：下拉框数据加载完成后
function on_region_load(domid,parent_id,rt,id)
*/

/* 事件：下拉框值改变后
function on_region_change()
*/

function getRegions(domid,parent_id,rt,id)
{
	if(!parent_id){return false;}
	url = "/ajax-regions.php?id="+id+"&parent_id="+parent_id+"&rt="+rt+"&ref="+Math.random();
	$("#"+domid).load(url ,function(){
		if( typeof(on_region_load) != "undefined"){
			
			on_region_load(domid,parent_id,rt,id);
		}
		if(rt==1){
			
			var def_val = $('#area_province_default').val();
			if(def_val > 0){
				$('#area_province').val(def_val);
			}
			$('#area_province').change();
		}
		
		if(rt==2){
			var def_val = $('#area_city_default').val();
			if(def_val > 0){
				$('#area_city').val(def_val);	
			}
			$('#area_city').change();
		}
		
		if(rt==3){
			$('#area_town').change(on_region_change);
			var def_val = $('#area_town_default').val();
			if(def_val > 0){
				$('#area_town').val(def_val);
			}
			$('#area_town').change();
		}
	});
	on_region_change();
}


function on_region_change(){
	
	//选择区域后自动填写拼音名称
	var en_pro = $("#area_province option:selected").attr("en") ;
	if(en_pro){
		$("#area_province_en").val(en_pro) ;
	}
	
	var en_city = $("#area_city option:selected").attr("en") ;
	if(en_city){
		$("#area_city_en").val(en_city) ;
	}
	
	var en_town = $("#area_town option:selected").attr("en") ;
	if(en_town){
		$("#area_town_en").val(en_town) ;
	}
	
	//选择区域后自动填写中文名称
	var cn_pro = $("#area_province option:selected").html() ;
	if(cn_pro){
		$("#area_province_name").val(cn_pro) ;
	}
	
	var cn_city = $("#area_city option:selected").html() ;
	if(cn_city){
		$("#area_city_name").val(cn_city) ;
	}
	
	var cn_town = $("#area_town option:selected").html() ;
	if(cn_town){
		$("#area_town_name").val(cn_town) ;
	}
	
	//选择区域后自动填写邮政编码
	/*
	var pscode = $("#area_province option:selected").attr("pscode");
	if(pscode){
		$("#postcode").val(pscode);
	}
	pscode = $("#area_city option:selected").attr("pscode");
	if(pscode){
		$("#postcode").val(pscode);
	}
	pscode = $("#area_town option:selected").attr("pscode");
	if(pscode){
		$("#postcode").val(pscode);
	}
	*/
	
	//选择区域后自动填写分机号
	var tel = $("#area_province option:selected").attr("tel");
	if(tel){
		$("#telephone1").val(tel);
		$("#fax1").val(tel);
	}
	tel = $("#area_city option:selected").attr("tel");
	if(tel){
		$("#telephone1").val(tel);
		$("#fax1").val(tel);
	}
	tel = $("#area_town option:selected").attr("tel");
	if(tel){
		$("#telephone1").val(tel);
		$("#fax1").val(tel);
	}
}
/*end 地区区域选择组件 */