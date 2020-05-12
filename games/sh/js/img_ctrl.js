var global_chgimg_timer;
var img_count;
var cur_img_index=1;
var li_arr;

var pic_offset=178;

function start_img_control(){
	
	img_count=document.getElementById("img_ctrl").getElementsByTagName("ul")[0].getElementsByTagName("li").length;
	
	for(var lc=0;lc<img_count;lc++){
		var li_obj=$("<li style=\"position:relative;cursor:pointer;\">"+(lc+1)+"</li>");
		$("#btn_ctrl ul").append(li_obj);
	}
	
	var offsetLeft=468;
	if(navigator.userAgent.indexOf("MSIE 6")!=-1){
		offsetLeft=468;
	}
	
	$("#btn_ctrl").css({top:"158px",left:(offsetLeft-img_count*20)+"px",visibility:"visible"});
	//$("#btn_ctrl").css({top:"260px",left:"75px",visibility:"visible"});
	
	var subChild=document.getElementById("btn_ctrl").firstChild;
	while(subChild.tagName!="UL"){
		subChild=subChild.nextSibling;
	}
	
	li_arr=subChild.getElementsByTagName("li");
	for(var lc=0;lc<img_count;lc++){
		var tmp_ii=lc;
		li_arr[lc].onclick=function(){on_li_click(this)};
	}
	
	set_img_by_index(1)
	global_chgimg_timer=setInterval(chg_img,5000);
}

function on_li_click(obj){
	clearInterval(global_chgimg_timer);
	cur_img_index=parseInt(obj.innerHTML);
	set_img_by_index(cur_img_index);
	global_chgimg_timer=setInterval(chg_img,5000);
}

function chg_img(){
	if(cur_img_index>=img_count)
		cur_img_index=1;
	else
		cur_img_index++;
		
	set_img_by_index(cur_img_index);
}

function set_img_by_index(index){
	
	document.getElementById("img_ctrl").getElementsByTagName("ul")[0].style.marginTop=(index-1)*pic_offset*(-1)+"px";
	
	li_arr[index-1].style.color="red";
	li_arr[index-1].style.background="url('images/qh_bg02.jpg')";
	for(var lii=0;lii<li_arr.length;lii++)
		if(lii!=index-1){
			li_arr[lii].style.color="black";

		}
}

start_img_control();