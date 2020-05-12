<!--


Date.prototype.format=function(fmt) {   
    var o = {   
    "M+" : this.getMonth()+1, //月份   
    "d+" : this.getDate(), //日   
    "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时   
    "H+" : this.getHours(), //小时   
    "m+" : this.getMinutes(), //分   
    "s+" : this.getSeconds(), //秒   
    "q+" : Math.floor((this.getMonth()+3)/3), //季度   
    "S" : this.getMilliseconds() //毫秒   
    };   
    var week = {   
    "0" : "\u65e5",   
    "1" : "\u4e00",   
    "2" : "\u4e8c",   
    "3" : "\u4e09",   
    "4" : "\u56db",   
    "5" : "\u4e94",   
    "6" : "\u516d"  
    };   
    if(/(y+)/.test(fmt)){   
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));   
		
    }   
    if(/(E+)/.test(fmt)){   
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);   
    }   
    for(var k in o){   
        if(new RegExp("("+ k +")").test(fmt)){   
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
        }   
    }   
    return fmt;   
};



function cm_checkall(obj){
	var th = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
	var id = $(th).attr('id');
	var chks = $('input:[name=' + id + '_checkboxmodel]');
	
		for(var i = 0; i < chks.length; i++ ){
			chks[i].checked = obj.checked;
		}
	
	
	
}
function CheckboxModel(params) {
	
	this.header = params.header || '<input type="checkbox" name="chkAll" onclick="cm_checkall(this);" value="" />全选'; //地址
	this.dataIndex = params.dataIndex || '';
	this.sortable = params.sortable || false;
	this.width = params.width || '50';
	this.renderer = params.renderer || null;
	this.grid_id = '';
	
	this.renderer = function(rs,index){
		return '<input type="checkbox" name="' + this.grid_id + '_column_' + index + '_checkboxmodel" class="' + this.grid_id +  '_checkboxmodel" value="' + rs[this.dataIndex] + '" />';
	}
   
}

/* 已放弃
function onchange_editor(obj,act_url,act,name,id){
	var params = {};
	params['act'] = act;
	params['name'] = name;
	params['id'] = id;
	params['value'] = obj.value;
	
	if(obj.value != obj.defaultValue){
		ajax_action(act_url, params, null, function(data){
			if(data!='1'){
				obj.value = obj.defaultValue;
			}
		});
	}
}
*/
function TextboxModel(params){
	this.header = params.header || '编辑'; //地址
	this.dataIndex = params.dataIndex || '';
	this.sortable = params.sortable || false;
	this.width = params.width || '60';
	this.renderer = params.renderer || null;
	this.url = params.url || '';
	this.priIndex = params.priIndex || ''; //主键字段名
	this.func = params.func || null; //自定义的处理函数
	this.grid_id = '';
	this.col_index = '';
	
	
	this.renderer = function(rs,col_index,row_index){
		this.col_index = col_index;
		return '<input type="textbox" name="' + this.grid_id + '_column_' + col_index + '_textboxmodel" row_index="' + row_index + '"  value="' + rs[this.dataIndex] + '" class="grid_textbox action_editor" style="width:' + (this.width - 6) + 'px;"  />';
	}
	
	this.onchange_editor = function(obj,act_url,act,name,id){
		var params = {};
		params['act'] = act;
		params['name'] = name;
		params['id'] = id;
		params['value'] = obj.value;
		
		if(obj.value != obj.defaultValue){
			ajax_action(act_url, params, null, function(data){
				if(data!='1'){
					obj.value = obj.defaultValue;
				}
			});
		}
	}
	
	this.onrefresh = function(){
		
		var data = this.cm.grid.data[this.cm.grid.key_root];
		var boxs = document.getElementsByName(this.grid_id + '_column_' + this.col_index + '_textboxmodel');
		var tm = this;
		for(var i=0;i<boxs.length;i++){
			$(boxs[i]).bind('blur',function(){
				var row_index = this.getAttribute('row_index');
				if(tm.func != null){
					tm.func(this, tm.url,'editor', tm.dataIndex ,data[row_index][tm.priIndex]);
				}else{
					tm.onchange_editor(this, tm.url,'editor', tm.dataIndex ,data[row_index][tm.priIndex]);
				}
			});
		}
	}
}

//** BoolenModel start  **//
function on_change_boolen(obj,act_url,act,name,id){
	var params = {};
	params['act'] = act;
	params['name'] = name;
	params['id'] = id;
	
	
	var val = obj.getAttribute('val');
	var opt = eval('('+ obj.getAttribute('options') +')');
	var nextval = null;
	var isend = false;
	for(var i in opt){
		if(nextval == null){
			nextval = i;
		}
		if(isend){
			nextval = i;
			break;
		}
		if(i == val){
			isend=true;
		}
	}
	
	params['value'] = nextval;
	ajax_action(act_url, params, null, function(data){
		if(data!='1'){
		}else{
			obj.innerHTML = text_convert_to_img(opt[nextval]);
			obj.setAttribute('val',nextval);
		}
	});
	
}

function text_convert_to_img(text){
	if(text == 'yes.gif'){
		return '<img src="../images/yes.gif" border="0" />';
	}
	
	if(text == 'no.gif'){
		return '<img src="../images/no.gif"  border="0" />';
	}
	
	return text;
}

function BoolenModel(params){
	this.header = params.header || ''; //地址
	this.dataIndex = params.dataIndex || '';
	this.sortable = params.sortable || false;
	this.width = params.width || '50';
	this.options = params.renderer || "{'1':'yes.gif', '0':'no.gif'}";
	this.url = params.url || '';
	this.priIndex = params.priIndex || ''; //主键字段名
	this.align = params.align || 'center';//水平对齐
	this.grid_id = '';
	
	this.renderer = function(rs,index){
		
		var opt = eval('(' + this.options + ')');
		var text = '';
		var val = '';
		for(var i in opt){
			if(rs[this.dataIndex] == i){
				text = opt[i];
				val = i;
			}
		}
		
		var html = text_convert_to_img(text);
		return '<a href="javascript:void(0);"  options="' + this.options.replace(/\"/g,"\\\"") + '" val="' + val + '" onclick="on_change_boolen(this,\'' + this.url + '\',\'editor\',\'' + this.dataIndex + '\',\'' + rs[this.priIndex] + '\');"  >' + html + '</a>';
		
	}
}

//** BoolenModel  end **//

function ColumnModel(params) {
    this.params = params;
	for (var i in params) {
		params[i].cm = this;
	}
	this.grid = null;
	
	this.onrefresh = function(){
		for (var j in params) {
			if( typeof(params[j].onrefresh)  != 'undefined'){
				params[j].onrefresh();
			}
		}
	};
}

function GridView(options) {
    this.url = options.url || window.location.href; //地址
    this.id = options.id; //要呈现的对象的id
    this.size = options.size || 20; //每页行数
    this.columns = options.cm || null; //ColumnModel类的对象
    this.params = options.params || null; //请求时的参数集
    this.start = options.start || 1; //当前页的第一条记录索引值
    this.sort = options.sort || ""; //要排序的字段
    this.dir = options.dir || ""; //排序方向
	this.has_sortbar =  (typeof(options.has_sortbar) != 'undefined') ? options.has_sortbar : true; //是否有分页栏
	this.has_loading =  (typeof(options.has_loading) != 'undefined') ? options.has_loading : true; //是否有加载条
	
    //grid的id
    this.grid_id = this.id + "_grid_id";

    //第一页按钮的id
    this.first_id = this.id + "_first_id";
    //最后一页按钮的id
    this.last_id = this.id + "_last_id";
    //上一页按钮的id
    this.prev_id = this.id + "_prev_id";
    //下一页按钮的id
    this.next_id = this.id + "_next_id";
    //按钮按钮的id
    this.refresh_id = this.id + "_refresh_id";
    //搜索结果文本栏的id
    this.result_id = this.id + "_result_id";
    //搜索结果文本模板
    this.result_text = options.result_text || "显示{start}到{end}条数据, 共{count}条";
    //当前页码的id
    this.currpage_id = this.id + "_currpage_id";
    //总页数的id
    this.totalpage_id = this.id + "_totalpage_id";
	
    
    function loadParams(qs,params) {
        //处理请求的参数集
        if (params) {
            for (var i in params) {
                qs.set(i, params[i]);
            }
        }
        return qs;
    }
    
    
    if(this.columns != null){
		this.columns.grid = this;
	}

    
    
    function init() {
    }
    //要加载的数据
    this.data = null;
    this.key_root = "root";
    this.key_count = "totalCount";
    
  

    //根据现巳加载的数据呈现到页面
    this.refresh = function() {
        var gridhtml = this.getgrid();
        var pagerhtml = this.has_sortbar ? this.getpager() : [];

        document.getElementById(this.id).innerHTML = gridhtml.join('') + pagerhtml.join('');

        var sender = this;
        document.getElementById(this.first_id).onclick = function() {
            sender.start = 1;
            sender.load();
        };

        document.getElementById(this.prev_id).onclick = function() {
            if (sender.start - sender.size > 0) {
                sender.start = sender.start - sender.size;
                sender.load();
            } else {
                sender.start = 1;
                sender.load();
            }
        };

        document.getElementById(this.next_id).onclick = function() {
            if (sender.start + sender.size <= sender.count) {
                sender.start = sender.start + sender.size;
                sender.load();
            }
        };

        document.getElementById(this.last_id).onclick = function() {
            sender.start = Math.ceil(sender.count / sender.size) * sender.size - sender.size + 1;
            sender.load();
        };

        document.getElementById(this.refresh_id).onclick = function() {
            sender.load();
        };



        var text = this.result_text.replace("{start}", this.start);
        var end = this.start + this.size - 1 > this.count ? this.count : this.start + this.size - 1;
        text = text.replace("{end}", end);
        text = text.replace("{count}", this.count);
        document.getElementById(this.result_id).innerHTML = text;

        var currpage = Math.ceil(this.start / this.size);
        document.getElementById(this.currpage_id).value = currpage;
        var totalpage = Math.ceil(this.count / this.size);
        document.getElementById(this.totalpage_id).innerHTML = totalpage;


        document.getElementById(this.currpage_id).onblur = function() {
            var inputvalue = parseInt(this.value);
            if (inputvalue != currpage) {
                if (inputvalue >= 1 && inputvalue <= totalpage) {
                    sender.start = (inputvalue - 1) * sender.size + 1;
                }
                sender.load();
            }
        };

		
        //控制每列标题排序样式
		
		var list = $('.header_' + this.grid_id + '_sort');
		for(var i=0;i<list.length;i++){
			var obj = list[i];
			obj.onclick = function(){
				if(this.getAttribute('dataIndex') != ''){
					sender.sort = this.getAttribute('dataIndex');
					if (sender.dir == "asc" || sender.dir == "") {
						sender.dir = "desc";
					} else {
						sender.dir = "asc";
					}
					sender.load();
				}
			}
			
			if (obj.getAttribute('dataIndex') == sender.sort && sender.sort != '') {
                var dirhtml = sender.dir == "desc" ? "↓" : "↑";
                obj.childNodes[0].childNodes[0].childNodes[1].innerHTML = dirhtml;
            }
		}
		this.columns.onrefresh();
    };



    //处理请求的参数集
    this.qs = loadParams(new QueryString(), this.params);
    

    //重新加载数据
    this.reload = function(params,funs) {

        this.qs.set("start", this.start);
        this.qs.set("limit", this.size);
        this.qs.set("sort", this.sort);
        this.qs.set("dir", this.dir);
        this.qs.set("rnd", Math.random());
        if (params) {
            this.qs = loadParams(this.qs, params);
        }
        this.postBody = this.qs.toQs();
       
        var sender = this;
		
		$.ajax({
			type: "POST",
			url: this.url,
			data: this.postBody,
			success: function(data){
				sender.data = eval('(' + data + ')');
				sender.count = sender.data[sender.key_count];
				if(funs){
					funs(data);
				}
			}
		});
		

    };

    //以指定的参数重新加载数据并刷新列表显示
    this.load = function(options) {
		var obj = this;
		if(this.has_loading){
			document.getElementById(this.id).innerHTML = "<div><center><img src=\"/images/ajax_loading.gif\" />Loading．．．</center></div>";
		}
		if(options && options.params){
        	this.reload(options.params,function(data){obj.refresh();});
		}else{
			
			this.reload({},function(data){
				obj.refresh();
				}
				);
		}
		
        //this.refresh();
    };

	//获取选中的id
    this.get_selected_ids = function(){
		var selected_checkbox = $('#' + this.grid_id + ' .' + this.grid_id + '_checkboxmodel:checked');
		var ids = '';
		var spt = '';
		for(var i = 0; i < selected_checkbox.length; i++){
			ids += spt + selected_checkbox[i].value;
			spt = ',';
		}
		return ids;
    }

    //获取列表内容的html
    this.getgrid = function() {
  
        var html = [];
        html.push('<div id="' + this.grid_id + '" class="grid">');
        
        var data = this.data[this.key_root];
		html.push('<table class="grid_table"  cellspacing="0" cellpadding="0" border="0" >');
		html.push('<tr>');
        for (var i = 0; i < this.columns.params.length; i++) {
            var col = this.columns.params[i];
			this.columns.params[i].grid_id = this.grid_id;
            var width = col["width"] ? col["width"] + "px" : "";
            var header = col["header"] || "&nbsp;";
            var dataIndex = col["dataIndex"] || "";
			var sortable = col["sortable"] || false;
			
			var sortcss = "";
			if(sortable){
				sortcss = "_sort grid_header_sort";
			}
			html.push('<th id="' + this.grid_id + '_column_' + i + '"  style="width:' + width + '"  class="grid_header"  >');
			
			html.push('<table width="100%" cellspacing="0" cellpadding="0" border="0" dataIndex="' + dataIndex + '" class="header_' + this.grid_id + sortcss + '"><tr>');
			html.push('<td>');
			html.push(header);
			html.push('</td>');
			if(sortable){
			html.push('<td width="20">');
			html.push('　');
			html.push('</td>');
			}
			html.push('</tr>');
			html.push('</table>');
			
			html.push('</th>');
        }
		html.push('</tr>');
		
		
		for(var j in  data ){
			html.push('<tr>');
			var row = data[j];
			for(var i = 0; i < this.columns.params.length; i++) {
				var col = this.columns.params[i];
				var dataIndex = col["dataIndex"] || "";
				var align = col["align"] || "left";
				html.push('<td  class="grid_cell" align="'+ align + '" >');
				
				var text = '';
				if(col["renderer"]){
					if(jQuery.isFunction(col["renderer"]) ){
						text = col["renderer"](row,i,j,dataIndex);
					}else if(typeof(col["renderer"])=='string'){
						var arr = eval('(' + col["renderer"] + ')');
						text = arr[row[dataIndex]];
					}else{
						text = col["renderer"][row[dataIndex]];
					}
				}else{
					if(row[dataIndex]){
						if(col["format"]){
							
							if(parseInt(row[dataIndex])==0){
								text = '';
							}else{
								var objtime = new Date();
								objtime.setTime(parseInt(row[dataIndex]) * 1000);
								text = objtime.format(col["format"]);
							}
						}else{
							text = row[dataIndex];
						}
					}
					
					
				}
				if(jQuery.trim(text)==''){
					text='　';
				}
				html.push( text );
				html.push('</td>');
			}
			html.push('</tr>');
		}
		
        html.push( '</table>');
		html.push( '</div>');
		
        return html;
    }

    
    
    //获取分页器的html
    this.getpager = function() {
        var html = [];
		html.push('<table width="100%" cellspacing="0" cellpadding="0" border="0" class="grid_pager" >');
		html.push('<tr>');
		html.push('<td  id="' + this.first_id + '" width="40" height="30" >');
		html.push('首页');
		html.push('</td>');
		
		html.push('<td  id="' + this.prev_id + '" width="60" >');
		html.push('上一页');
		html.push('</td>');
		
		html.push('<td  width="40" >');
		html.push('<input id="' + this.currpage_id + '"  type="text" style="width:30px;" value="1">');
		html.push('</td>');
		
		html.push('<td  width="10"  >');
		html.push('/');
		html.push('</td>');
		
		html.push('<td  id="' + this.totalpage_id + '" width="20"  >');
		html.push('1');
		html.push('</td>');
		
		html.push('<td  id="' + this.next_id + '" width="60"  >');
		html.push('下一页');
		html.push('</td>');
		
		html.push('<td  id="' + this.last_id + '" width="40"  >');
		html.push('尾页');
		html.push('</td>');
		
		html.push('<td  id="' + this.refresh_id + '" width="40"  >');
		html.push('刷新');
		html.push('</td>');
		
		html.push('<td  id="' + this.result_id + '" width="200" >');
		html.push('');
		html.push('</td>');
		
		html.push('<td>');
		html.push('　');
		html.push('</td>');
		
		html.push('</tr>');
		html.push('</table>');
		
	
        return html;
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



//-->