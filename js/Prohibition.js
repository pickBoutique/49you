//禁止鼠标键盘复制
function key(){ 
if(event.shiftKey){
window.close();}
//禁止shift
if(event.altKey){
window.close();}
//禁止alt
if(event.ctrlKey){
window.close();}
//禁止ctrl
return false;}
document.onkeydown=key;
if (window.Event)
document.captureEvents(Event.MOUSEUP);
//swordmaple javascript article.
function nocontextmenu(){
event.cancelBubble = true
event.returnValue = false;
return false;}
function norightclick(e){
if (window.Event){
if (e.which == 2 || e.which == 3)
return false;}
else
if (event.button == 2 || event.button == 3){
event.cancelBubble = true
event.returnValue = false;
return false;}
}
//禁止右键
document.oncontextmenu = nocontextmenu;  // for IE5+
document.onmousedown = norightclick;  // for all others

