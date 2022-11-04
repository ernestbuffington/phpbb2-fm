/* fall Script Kurt Grigg - http://www.btinternet.com/~kurt.grigg/javascript
modified for "flying birds" by Zifnab - http://www.zifnab.de */

  num=1; //Smoothness depends on image file size, the smaller the size the more you can use!
  stopafter=7200; //seconds!
  highspeed=3;
  curl=0.5;
  border=120;


  //Pre-load images!
  picsl=new Array("images/effects/bird1l.gif");
  picsr=new Array("images/effects/bird1r.gif");
  loadl=new Array();
  loadr=new Array();
  for(i=0; i < picsl.length; i++){
   loadl[i]=new Image();
   loadl[i].src=picsl[i];
   loadr[i]=new Image();
   loadr[i].src=picsr[i];
  }
  stopafter*=1000;
  timer=null;
  y=new Array();
  x=new Array();
  sx=new Array();
  sy=new Array();
  lx=new Array();
  lc=new Array();
  ghost=new Array();

  inih=300;iniw=300;
  for (i=0; i < num; i++){
    ghost[i]=Math.floor(Math.random()*picsl.length);
    y[i]=border+Math.round(Math.random()*(inih-2*border));
    x[i]=border+Math.round(Math.random()*(iniw-2*border));
    sx[i]=1-Math.random()*2;
    sy[i]=1-Math.random()*2;
    lx[i]=sx[i];
    lc[i]=0;
  }

  if (document.layers){
    for (i=0; i < num; i++){
      document.write("<LAYER NAME='ghostl"+i+"' LEFT=0 TOP=0><img src="+picsl[ghost[i]]+" onClick='dsbl()'></LAYER>");
      document.write("<LAYER NAME='ghostr"+i+"' LEFT=0 TOP=0><img src="+picsr[ghost[i]]+" onClick='dsbl()'></LAYER>");
      if (sx[i]<0) turn("l",i); else turn("r",i);
    }
  }
  if (document.all){
    document.write('<div style="position:absolute;top:0px;left:0px"><div style="position:relative">');
    for (i=0; i < num; i++){
      document.write('<img id="ghostl'+i+'" src="'+picsl[ghost[i]]+'" style="position:absolute;top:0px;left:0px" onClick="dsbl()">');
      document.write('<img id="ghostr'+i+'" src="'+picsr[ghost[i]]+'" style="position:absolute;top:0px;left:0px" onClick="dsbl()">');
      if (sx[i]<0) turn("l",i); else turn("r",i);
    }
    document.write('</div></div>');
  }
  if (!document.all&&!document.layers){
    for (i=0; i < num; i++){
      document.write("<div id='ghostl"+i+"' style='position:absolute;top:0px;left:0px'><img src="+picsl[ghost[i]]+" onClick='dsbl()'></div>");
      document.write("<div id='ghostr"+i+"' style='position:absolute;top:0px;left:0px'><img src="+picsr[ghost[i]]+" onClick='dsbl()'></div>");
      if (sx[i]<0) turn("l",i); else turn("r",i);
    }
  }
  fly();
  setTimeout('dsbl()',stopafter);

function fly(){
  h=(document.all)?window.document.body.clientHeight:window.innerHeight;
  w=(document.all)?window.document.body.clientWidth:window.innerWidth;
  scy=(document.all)?document.body.scrollTop:window.pageYOffset;
  scx=(document.all)?document.body.scrollLeft:window.pageXOffset;
  for (i=0; i < num; i++){
    lc[i]++;
    nx=x[i]+sx[i];
    ny=y[i]+sy[i];
    if (nx>w-border || nx<border) {
      sx[i]=-sx[i];
      nx=x[i]+sx[i];
    }
    if (ny>h-border || ny<border) {
      sy[i]=-sy[i];
      ny=y[i]+sy[i];
    }
    x[i]=nx;
    y[i]=ny;

    newsx=sx[i]+curl-2*curl*Math.random();
    if (lc[i]<50 && (newsx<0 && sx[i]>0 || newsx>0 && sx[i]<0)) newsx=sx[i];
    sx[i]=newsx;
    sy[i]=sy[i]+curl-2*curl*Math.random();
    if (sx[i]>highspeed) sx[i]=highspeed;
    if (sx[i]<-highspeed) sx[i]=-highspeed;
    if (sy[i]>highspeed) sy[i]=highspeed;
    if (sy[i]<-highspeed) sy[i]=-highspeed;

    if (document.layers){
      document.layers["ghostl"+i].left=x[i];
      document.layers["ghostr"+i].left=x[i];
      document.layers["ghostl"+i].top=y[i]+scy;
      document.layers["ghostr"+i].top=y[i]+scy;
    }
    else{
      document.getElementById("ghostl"+i).style.left=x[i];
      document.getElementById("ghostr"+i).style.left=x[i];
      document.getElementById("ghostl"+i).style.top=y[i]+scy;
      document.getElementById("ghostr"+i).style.top=y[i]+scy;
    }
    if (sx[i]>0 && lx[i]<0) turn("r",i);
    else if (sx[i]<0 && lx[i]>0) turn("l",i);
    if (sx[i]!=0) lx[i]=sx[i];
  }
  timer=setTimeout('fly()',20);
}

function dsbl(){
  for (i=0; i < num; i++){
    if (document.layers) {
      document.layers["ghostl"+i].visibility="hide";
      document.layers["ghostr"+i].visibility="hide";
    }
    else {
      document.getElementById("ghostl"+i).style.visibility="hidden";
      document.getElementById("ghostr"+i).style.visibility="hidden";
    }
  }
  clearTimeout(timer);
}
function turn(d,i) {
    if (d=="l") pd="r"; else pd="l";
    if (document.layers) {
      document.layers["ghost"+d+i].visibility="show";
      document.layers["ghost"+pd+i].visibility="hide";
    }
    else {
      document.getElementById("ghost"+d+i).style.visibility="visible";
      document.getElementById("ghost"+pd+i).style.visibility="hidden";
    }
    lc[i]=0;
}