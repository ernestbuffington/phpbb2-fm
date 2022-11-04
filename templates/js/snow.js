/* fall Script Kurt Grigg - http://www.btinternet.com/~kurt.grigg/javascript
adjusted and re-enabled for Netscape6 by Zifnab - http://www.zifnab.de */

var isNetscape6=false;
if (navigator.appName == "Netscape" && parseFloat(navigator.appVersion) >= 5) 
isNetscape6=true;

num=8; //Smoothness depends on image file size, the smaller the size the more you can use!
stopafter=7200; //seconds!


//Pre-load images!
pics=new Array("images/effects/snfla.gif","images/effects/snflb.gif","images/effects/snflc.gif","images/effects/snfld.gif","images/effects/snfle.gif","images/effects/snflf.gif"); 
load=new Array();
for(i=0; i < pics.length; i++){
 load[i]=new Image();
 load[i].src=pics[i];
}
stopafter*=1000;
timer=null;
y=new Array();
x=new Array();
s=new Array();
s1=new Array();
s2=new Array();
if (document.layers){
 for (i=0; i < num; i++){
 randomleaf = pics[Math.floor(Math.random()*pics.length)];
 document.write("<LAYER NAME='leaf"+i+"' LEFT=0 TOP=0><img src="+randomleaf+"></LAYER>");
 }
}
if (document.all){
document.write('<div style="position:absolute;top:0px;left:0px"><div style="position:relative">');
 for (i=0; i < num; i++){
 randomleaf = pics[Math.floor(Math.random()*pics.length)];
 document.write('<img id="leaf'+i+'" src="'+randomleaf+'" style="position:absolute;top:0px;left:0px">');
 }
 document.write('</div></div>');
}
if (!document.all&&!document.layers){
 for (i=0; i < num; i++){
 randomleaf = pics[Math.floor(Math.random()*pics.length)];
 document.write("<div id='leaf"+i+"' style='position:absolute;top:0px;left:0px'><img src="+randomleaf+"></div>");
 }
}
inih=(document.all)?window.document.body.clientHeight:window.innerHeight-100;
iniw=(document.all)?window.document.body.clientWidth:window.innerWidth-100;
for (i=0; i < num; i++){                                                                
 y[i]=Math.round(Math.random()*inih);
 x[i]=Math.round(Math.random()*iniw);
 s[i]=Math.random()*3+1;
 s1[i]=0;
 s2[i]=Math.random()*0.1+0.05;
}
function fall(){
h=(document.all)?window.document.body.clientHeight:window.innerHeight;
if (isNetscape6) h=h-60;
w=(document.all)?window.document.body.clientWidth:window.innerWidth;
scy=(document.all)?document.body.scrollTop:window.pageYOffset;
scx=(document.all)?document.body.scrollLeft:window.pageXOffset;
for (i=0; i < num; i++){
sy=s[i]*Math.sin(90*Math.PI/180);
sx=s[i]*Math.cos(s1[i]);
y[i]+=sy;
x[i]+=sx; 
if (x[i]>w-scx-60) x[i]-=2*sx;
if (y[i] > h){
 y[i]=-60;
 x[i]=Math.round(Math.random()*(w-scx-60));
 s[i]=Math.random()*3+1;
}
s1[i]+=s2[i];
if (document.layers){
 document.layers["leaf"+i].left=x[i];
 document.layers["leaf"+i].top=y[i]+scy;
}
else{
 document.getElementById("leaf"+i).style.left=x[i];
 document.getElementById("leaf"+i).style.top=y[i]+scy;
} 
}
timer=setTimeout('fall()',20);
}
fall();

function dsbl(){
for (i=0; i < num; i++){
 if (document.layers)
 document.layers["leaf"+i].visibility="hide";
 else
 document.getElementById("leaf"+i).style.visibility="hidden";
}
clearTimeout(timer);
}
setTimeout('dsbl()',stopafter);