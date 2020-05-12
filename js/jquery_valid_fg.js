<!--
function addEvent(oTarget, sEventType, fnHandler) {

    if (oTarget.addEventListener) {

        oTarget.addEventListener(sEventType, fnHandler, false);
    } else if (oTarget.attachEvent) {
		
		oTarget["e"+sEventType+fnHandler] = fnHandler; 
		oTarget[sEventType+fnHandler] = function() { oTarget["e"+sEventType+fnHandler]( window.event ); } 
		oTarget.attachEvent( "on"+sEventType, oTarget[sEventType+fnHandler] ); 
		
       
    } else {
        oTarget["on" + sEventType] = fnHandler;
    }
	
	EventCache.add(oTarget, sEventType, fnHandler);
};

function removeEvent( obj, type, fn ) { 

   var fun = EventCache.remove( obj, type, fn );
   if(fun){
	 
	   if ( obj.detachEvent ) { 
	   
	
		 obj.detachEvent("on" + type,obj[type+fn]);
    	 obj[type+fn]=null;
    	 obj["e"+type+fn]=null;
	   } else if(obj.removeEventListener){
		
		 obj.removeEventListener( type, fun, false ); 
	   }
	   
	  

   }
}

function getFuncName(_text) {
	
	var mat = _text.match(/^function\s*([^\(]+).*\r\n/);
	
	if(mat && mat.length>=2){
		return mat[1];
	}else{
		return Math.random();
	}
	
}


var EventCache = function(){ 
	var listEvents = []; 
	return { 
	
	listEvents : listEvents, 
	
	add : function(node, sEventName, fHandler){ 
	
	listEvents.push(arguments); 
	}, 
	
	flush : function(){ 
		var i, item; 
		for(i = listEvents.length - 1; i >= 0; i = i - 1){ 
			item = listEvents[i]; 
			
			removeEvent(item[0], item[1], item[2]);
			
		}; 
	},
	
	remove : function(node,sEventName, fHandler){
		var i, item; 
		for(i = 0; i < listEvents.length; i++){ 
			item = listEvents[i]; 
			if(item[0] == node && item[1] == sEventName){
			
				var name = getFuncName(fHandler.toString());
				
				var itemname = getFuncName(item[2].toString());
				
				if(name == itemname){
					listEvents.splice(i,1);
					return item[2];
				}
			}
		}
		return null;
	}
	
	
	
	}; 

}(); 
addEvent(window,'unload',EventCache.flush);

function call(obj, eventname) {
    if (navigator.appName.indexOf("Microsoft") != -1) {
        /*IE*/
        obj.fireEvent("on" + eventname);
    }
    else {
        /*Other*/
        var e = document.createEvent("Events");
        e.initEvent(eventname, true, true);
        obj.dispatchEvent(e);
    }
}


function Hashtable() {
    this._hash = new Object();
    this.add = function(key, value) {
        if (typeof (key) != "undefined") {
            if (this.contains(key) == false) {
                this._hash[key] = typeof (value) == "undefined" ? null : value;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    this.remove = function(key) { delete this._hash[key]; }
    this.count = function() { var i = 0; for (var k in this._hash) {  i++; } return i; }
    this.items = function(key) { return this._hash[key]; }
    this.contains = function(key) { return typeof (this._hash[key]) != "undefined"; }
    this.clear = function() { for (var k in this._hash) { delete this._hash[k]; } }
	this.clear();
}

//**气泡提示 start  id=容器的id tag=容器内需要应用气泡提示的html标签 **// 
function enableTooltips(id,tag) {
    var links, i, h;
    if (!document.getElementById || !document.getElementsByTagName) return;
    AddCss();
    h = document.createElement("span");
    h.id = "btc";
    h.setAttribute("id", "btc");
    h.style.position = "absolute";
    document.getElementsByTagName("body")[0].appendChild(h);
    var tagname = "a";
    if (tag) {  tagname = tag; }
    if (id == null) links = document.getElementsByTagName(tag);
    else links = document.getElementById(id).getElementsByTagName(tag);
    for (var i = 0; i < links.length; i++) {
        Prepare(links[i]);
    }
}

function Prepare(el) {
    var tooltip, t, b, s, l;
    t = el.getAttribute("tooltip");
    if (t == null || t.length == 0) { t = "" };
    //el.removeAttribute("tooltip");
    tooltip = CreateEl("span", "tooltip");
    s = CreateEl("span", "top");
    s.appendChild(document.createTextNode(t));
    tooltip.appendChild(s);
    b = CreateEl("b", "bottom");
    l = el.getAttribute("href");
    if (l == null || l.length == 0) l = "";
    if (l.length > 30) l = l.substr(0, 27) + "...";
    b.appendChild(document.createTextNode(l));
    tooltip.appendChild(b);
    setOpacity(tooltip);
    el.tooltipobj = tooltip;
    el.onmouseover = showTooltip;
    el.onmouseout = hideTooltip;
    el.onmousemove = Locate;
}

function showTooltip(e) {
    t = this.getAttribute("tooltip");
    if (t == null || t.length == 0) { t = "" };
    this.tooltipobj.childNodes[0].innerHTML = t;
    if (this.tooltipobj.childNodes[0].innerHTML != "") {
        document.getElementById("btc").appendChild(this.tooltipobj);
        Locate(e);
    }
}

function hideTooltip(e) {
    var d = document.getElementById("btc");
    if (d.childNodes.length > 0) d.removeChild(d.firstChild);
}

function setOpacity(el) {
    el.style.filter = "alpha(opacity:95)";
    el.style.KHTMLOpacity = "0.95";
    el.style.MozOpacity = "0.95";
    el.style.opacity = "0.95";
}

function CreateEl(t, c) {
    var x = document.createElement(t);
    x.className = c;
    x.style.display = "block";
    return (x);
}

function AddCss() {
/*
    var l = CreateEl("link");
    l.setAttribute("type", "text/css");
    l.setAttribute("rel", "stylesheet");
    l.setAttribute("href", "bt.css");
    l.setAttribute("media", "screen");
    document.getElementsByTagName("head")[0].appendChild(l);
    */
}

function Locate(e) {
    var posx = 0, posy = 0;
    if (e == null) e = window.event;
    if (e.pageX || e.pageY) {
        posx = e.pageX; posy = e.pageY;
    }
    else if (e.clientX || e.clientY) {
        if (document.documentElement.scrollTop) {
            posx = e.clientX + document.documentElement.scrollLeft;
            posy = e.clientY + document.documentElement.scrollTop;
        }
        else {
            posx = e.clientX + document.body.scrollLeft;
            posy = e.clientY + document.body.scrollTop;
        }
    }
    document.getElementById("btc").style.top = (posy + 10) + "px";
    document.getElementById("btc").style.left = (posx - 20) + "px";
}
//**气泡提示 end**//


function box_error_show(obj_input){
	
	var show_id = obj_input.data["valid_position"] != "" ? obj_input.data["valid_position"] : obj_input.id;
	box_hide(show_id);
	var show_left = $('#' + show_id).position().left + $('#' + show_id).width() + 10;
	var show_top = $('#' + show_id).position().top;
	var html = "<div id='valid_msg_" + show_id + "' class='valid_msg valid_error_msg' style='left:" + show_left + "px;top:" + show_top + "px;' >" + obj_input.getAttribute("tooltip") + "</div>";
	//$(document.body).append(html);
	$(obj_input).parent().append(html);
}

function box_refresh_position(){
	var objs = $('.valid_msg');
	for(var i=0;i<objs.length;i++){
		var show_id = objs[i].id.replace('valid_msg_','');
		var txt_obj = $('#' + show_id);
		var show_left = txt_obj.position().left + txt_obj.width() + 10;
		var show_top = txt_obj.position().top;
		
		if(txt_obj.is(':hidden')){
			objs[i].style.display = "none";
		}else{
			objs[i].style.left = show_left + 'px';
			objs[i].style.top = show_top + 'px';
			objs[i].style.display = '';
		}
	}
}


function box_hide(show_id){
	$('#valid_msg_' + show_id).remove();
}

function box_true_show(obj_input){
	
	var show_id = obj_input.data["valid_position"] != "" ? obj_input.data["valid_position"] : obj_input.id;
	box_hide(show_id);
	var show_left = $('#' + show_id).position().left + $('#' + show_id).width() + 10;
	var show_top = $('#' + show_id).position().top;
	var html = "<div id='valid_msg_" + show_id + "' class='valid_msg valid_true_msg' style='left:" + show_left + "px;top:" + show_top + "px;' >　</div>";
	//$(document.body).append(html);
	
	$(obj_input).parent().append(html);
}

/*
	*	显示冒泡提示
	*/
function show_tooltip(){
	
	var show_id = this.getAttribute('position') ?  this.getAttribute('position') : this.id;
	box_hide(show_id);
	var show_left = $('#' + show_id).position().left + $('#' + show_id).width() + 10;
	var show_top = $('#' + show_id).position().top;
	var html = "<div id='valid_msg_" + show_id + "' class='valid_msg valid_tooltip_msg' style='left:" + show_left + "px;top:" + show_top + "px;' >" + this.getAttribute('warning') + "</div>";
	$(this).parent().append(html);
}



///初始化表单各元素的数据校验
function initform(obj_form,qs_url) {
	if(obj_form){
	}else{
		obj_form = document.forms[0];
	}
	if(qs_url){
	}else{
		var qs = new QueryString(window.location.href);
		qs_url = qs.url;
	}
    //enableTooltips(null, "INPUT");
    var errtb = new Hashtable();
    function checktrue(obj) {
        if (errtb.contains(obj.name)) { errtb.remove(obj.name); }
		
		for(var key in errtb._hash){
			var elems = document.getElementsByName(key);
			if(elems && elems[0].data["valid_position"] == obj.data["valid_position"]){
				call(elems[0], "blur");
				return;
			}
		}
		
        if (obj.data["valid_style"] == "1") {
			//obj.style.background = '';
        }
        if (obj.data["valid_warning"] == "1") {
            if (obj.getAttribute("oritooltip") != null) {
                obj.setAttribute("tooltip", obj.getAttribute("oritooltip"));
                obj.setAttribute("oritooltip", null);
            }
			
			box_true_show(obj);
        }
    }

    function checkfalse(obj,error) {
	
		if (errtb.contains(obj.name)) { errtb.remove(obj.name); }
        errtb.add(obj.name, error);
        if (obj.data["valid_style"] == "1") {
			//obj.style.background = "url(../images/exclamation.gif) right top no-repeat";
           
        }
        if (obj.data["valid_warning"] == "1") {
            if (obj.getAttribute("oritooltip") == null) {
                var tooltip = obj.getAttribute("tooltip");
                if (tooltip == null) {
                    tooltip = "";
                }
                obj.setAttribute("oritooltip", tooltip);
            }
            obj.setAttribute("tooltip", error);
			
			box_error_show(obj);
        }
    }
	
	function check_valid() {
		var iserror = false;
		var errormsg = "";
		for(var d in this.validlist){
			this.data = this.validlist[d];
			this.data["valid_position"] = this.data["valid_position"] != "" ? this.data["valid_position"] : this.id;
			errormsg = this.data["valid_desc"] + " " + this.data["valid_error"];
			
			//没有达到校验条件 则不对此条配置进行校验
			if (this.data["valid_where"] != ""){
				var where = eval(this.data["valid_where"]);
				if(where == false){
					continue;
				}
			}
			
			
			//允许为空，并且内容为空 则不对其进行字符校验
			if(this.data["valid_empty"] == "1" && this.value.length == 0){
				
			}else{
				//正则校验
				if (this.data["valid_type"] == "1" && this.data["valid_regex"] != "") {
				
					var re = new RegExp(this.data["valid_regex"]);
					if (this.value.match(re) == null) {
						iserror = true;
						
					}
				}
				//指定字符校验
				if (this.data["valid_type"] == "0" && this.data["valid_regex"] != "") {
					var re = new RegExp("^[" + this.data["valid_regex"] + "]*$");
					if (this.value.match(re) == null) {
						iserror = true;
					}
				}
			}
  
			//空校验
			if (this.data["valid_empty"] == "0") {
				if (this.value.length == 0) {
					iserror = true;
					errormsg = this.data["valid_desc"] + " 不能为空";
				}
			}
  
			//长度校验
			var len = this.value.length;
			if ( (len < parseInt(this.data["valid_min"]) && parseInt(this.data["valid_min"]) > 0 ) || (len > parseInt(this.data["valid_max"]) && parseInt(this.data["valid_max"])>0 ) ) {
				iserror = true;
				if(this.data["valid_min"] == this.data["valid_max"]){
					errormsg = this.data["valid_desc"] + " 长度必须为 " + this.data["valid_min"] + " 位字符";
				}else{
					errormsg = this.data["valid_desc"] + " 字符长度必须在 " + this.data["valid_min"] + " 到 " + this.data["valid_max"] + " 之间";
				}
			}
  
  
			//唯一性校验
			if (this.data["valid_onlyone"] == "1") {
				if (this.value != this.defaultValue) {
					var validdata = this;
					ajax_action("/webmanage/ajax-valid.php",{act:'checkonlyone',name:this.data["valid_fieldname"],value:this.value,filename:encodeURI(qs_url) ,asyn:false} , null, function(ret) {																				
						if (parseInt(ret) > 0) {
							iserror = true;
							errormsg = validdata.data["valid_desc"] + " 已存在！";
							
						}
					});
				}
			}
			
			if(iserror){
				break;
			}
		}
		
		if (iserror) {
			checkfalse(this, errormsg);
		} else {
			checktrue(this);
		}
	}
	
	
	
	
	var qs = new QueryString(window.location.href);
	//var act = qs.get('act');
	var act = $('#valid_act',obj_form);
	
	if(act && (act.val() == 'add' || act.val() == 'edit')){
		
		ajax_action("/webmanage/ajax-valid.php", { act:'list', filename:encodeURI(qs_url), asyn:false } , null , function(ret) {
			if(jQuery.trim(ret) != ""){
				var obj = eval("(" + ret + ")");
				
				for (var i in obj) {
					var data = obj[i];
					
					if (data["valid_foreground"] == "0") { continue; }
					
					var elms = document.getElementsByName(data["valid_fieldname"]);
					
					var elmstypes = "text;password";
					if(elms){
						for(var j=0;j<elms.length;j++){
								
							if (((elmstypes.indexOf(elms[j].getAttribute("type").toLowerCase()) > -1) || (elms[j].getAttribute("tagName").toLowerCase() == "textarea"))) {
								if(elms[j].validlist){
								}else{
									elms[j].validlist = Array();
								}
								elms[j].validlist.push(data);
								//elms[j].data = data;
								
								removeEvent(elms[j],'blur', check_valid);
								
								addEvent(elms[j],'blur', check_valid);
								
							}
						}
					}
				}
				
				//为表单提交增加事件处理
				
				obj_form.onsubmit = check_form_all;
				obj_form.oncheck = check_form_all;
			}
		});
	
	}
	
	
	/*
	*	检查表单所有验证
	*/
	function check_form_all() {
				
		var elms = document.getElementsByTagName("INPUT");
		
		for (var i = 0; i < elms.length; i++) {
			call(elms[i], "blur");
		}
		
		if (errtb.count() <= 0) {
			return true;
		} else {
			for(var err in errtb._hash){
				var elms = document.getElementsByName(err);
				//$(elms[0]).focus();
				alert(errtb.items(err));
				break;
			}
			return false;
		}
	}
	
	
	
	
	var inps = document.getElementsByTagName("INPUT");
	for(var i=0;i<inps.length;i++){
		var inps_id = inps[i].id;
		var warning = inps[i].getAttribute('warning');
		if(warning && warning!=''){
			
			addEvent(inps[i],'focus',show_tooltip);
			//addEvent(inps[i],'blur', hide_tooltip);
		}
	}
	
}

addEvent(window, "resize", box_refresh_position);

//addEvent(window, "load", initform);



//-->