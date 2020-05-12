function news_search(){
	var sern = encodeURI($("#sertxt").val());
	var infolink="news.html";
	infolink+=("?sertext="+ (sern));
	document.getElementById("ifram_list").src=infolink;
}
function info_show(index){
	var infolink="news_info.html";
	if(index!=''){
		infolink+=("?id="+index);
		document.getElementById("ifram_info").src=infolink;
		$("#Cusgameflash").show();
	}
}
function info_close(){
	$("#Cusgameflash").hide();
}
function set_search(sear){
	$("#sertxt").val(sear);
	news_search();
}
$(document.body).ready(function(){
	document.getElementById("sertxt").onclick=(function(){
				if($("#sertxt").val()=="请输入关键词查找"){
					$("#sertxt").val("");
				}
				$("#sertxt").css("color","#000"); 
			}
		);
	document.getElementById("clntxt").onclick=(function(){news_search()});
	}
);
