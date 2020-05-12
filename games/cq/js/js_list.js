// JavaScript Document
var sid=9;
var tp=1;
function getServerInfo() {
ajax_action('gamedata.html?act=getTop',{sid:sid,tp:tp},null,function(data){
$('#divtop').html(data);
});
}

function tabchange(id){
	tp=id;
	$(".on").removeClass('on');
	$("#li_"+id).addClass('on');
	getServerInfo();
}

function serverchange(id){
	sid=id;
	getServerInfo();
}
