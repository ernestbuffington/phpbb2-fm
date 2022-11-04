<script language="JavaScript" type="text/javascript" name="commandz">
<!--
{MULTISERVERCOMMANDZ}
setTimeout('document.pjirc.requestSourceFocus();document.pjirc.setFieldText("/sound snd/{SOUND_BEEP}");document.pjirc.validateText();', {SOUND_BEEP_DELAY});
{BOT_COMMANDZ}
//-->
</script>

<script language="Javascript" type="text/javascript"> 
<!--
// Original script by AceJS, The JavaScript Directory - http://www.AceJS.com
// Adaptation, further dev and images by Midnightz
// The above lines MUST stay intact!

var musiccount=1;
function playmusic()
{
	document.graphix.src="{SITEPATH}mods/chatroom/player/animated.gif";
	{PLAYERLIST}
}

function stopmusic()
{
	document.graphix.src="{SITEPATH}mods/chatroom/player/nonanimated.gif";
	document.all.music.src='';
}

function forwardmusic()
{
	document.graphix.src="{SITEPATH}mods/chatroom/player/animated.gif";
	musiccount++;
	{PLAYERLIST}
}

function rewindmusic()
{
	document.graphix.src="{SITEPATH}mods/chatroom/player/animated.gif";
	musiccount--;
	{PLAYERLIST}
}
function music1()
{
	document.plbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB1}";
}
function music2()
{
	document.plbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB2}";
}
function music3()
{
	document.stbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB3}";
}
function music4()
{
	document.stbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB4}";
}
function music5()
{
	document.ffbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB5}";
}
function music6()
{
	document.ffbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB6}";
}
function music7()
{
	document.rrbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB7}";
}
function music8()
{
	document.rrbtn.src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB8}";
}

var XX = -70;
var YY = -70;
var cur_one = "emptycell"
var moving = false;
var xpos1 = 20;
var ypos1 = 20;
var myX = 0;
var myY = 0;

function InitializeMove() 
{
	cur_one = "movmenu";
	XX = eval("xpos1");
	YY = eval("ypos1");
}

function CaptureMove() 
{
	if (document.layers) document.captureEvents(Event.MOUSEMOVE);
}

function EndMove() 
{
	if (document.layers) document.releaseEvents(Event.MOUSEMOVE);

	cur_one = "emptycell"
	moving = false;
	document.close();
}

function WhileMove() 
{
	if (document.all) 
	{
		eval(cur_one+".style.left="+myX);
		eval(cur_one+".style.top="+myY);
	}
  
	if (document.layers) 
	{
		eval("document."+cur_one+".left="+myX);
		eval("document."+cur_one+".top="+myY);
	}
}

function MoveHandler(e) 
{
	myX = (document.all) ? event.clientX : e.pageX;
	myY = (document.all) ? event.clientY : e.pageY;

	if (!moving) 
	{
		diffX =  XX - myX;
		diffY = YY - myY;
		moving = true;
		if (cur_one == "emptycell") moving = false;
	}
	myX += diffX;
	myY += diffY;

	if (moving) 
	{
		xpos1 = myX;
		ypos1 = myY;
	}

	WhileMove();
}

function ClearError() 
{
	return true;
}

if (document.layers) 
{
	document.captureEvents(Event.CLICK);
	document.captureEvents(Event.DBLCLICK);
}
document.onmousemove = MoveHandler;
document.onclick = CaptureMove;
document.ondblclick = EndMove;
window.onerror = ClearError;
WhileMove();
//-->
</script>

<table align="center" cellpadding="4" cellspacing="1" class="forumline"><form name="track">
<tr>
	<th class="thHead">{L_TITLE}</th>
</tr>
<tr>
    <td align="center" class="row1"><table cellspacing="0" cellpadding="1">
    <tr>
    	<td align="center" valign="middle" bgcolor="{CURRENTCHATTABLE}" width="640" height="400">
        <applet codebase="mods/chatroom" name="pjirc" code=IRCApplet.class archive="irc.jar,pixx.jar,alias.jar,descbot.jar" width=640 height=400>
	<param name="CABINETS" value="irc.cab,securedirc.cab,pixx.cab,alias.cab,descbot.cab">
	<param name="nick" value="{NICKNAME}">
	<param name="alternatenick" value="{NICKNAME}???">
	<param name="name" value="{USERNAME}">
	<param name="asl" value="true">
	<param name="host" value="{SERVER}">
	<param name="serveralias" value="{SERVERALIAS}">
        <param name="serveralias" value="{MULTISERVERALIAS}">
	<param name="port" value="{PORT}">
	<param name="gui" value="pixx">
        <param name="command1" value="/join {CHANNEL}">
	<!-- BEGIN commands -->
	<param name="command{commands.NUMBER}" value="{commands.COMMAND}">
	<!-- END commands -->
	<!-- BEGIN sounds1 -->
        <param name="soundword{sounds1.SOUND1NUMBER}" value="{sounds1.SOUNDWORD1} {sounds1.SOUND1}">
        <!-- END sounds1 -->
        <!-- BEGIN sounds2 -->
        <param name="soundword{sounds2.SOUND2NUMBER}" value="{sounds2.SOUNDWORD2} {sounds2.SOUND2}">
        <!-- END sounds2 -->
	<param name="multiserver" value="{MULTISERVER}">
        <param name="authorizedjoinlist" value="{AUTHJOINLIST}">
        <param name="plugin1" value="adnd.Alias">
        <param name="alias:key1" value="awaymsg">
        <param name="alias:command1_1" value="/me is currently away. *">
        <param name="alias:command1_2" value="/away I am currently away.">
        <param name="alias:key2" value="backmsg">
        <param name="alias:command2_1" value="/me has returned. *">
        <param name="alias:command2_2" value="/away">
        {MIRRORBOT}
        <param name="language" value="{LANGUAGE}.txt">
        <param name="pixx:lngextension" value="txt"> 
        <param name="lngextension" value="txt">
        <param name="pixx:language" value="pixx-{LANGUAGE}">
	<param name="quitmessage" value="{QUIT_MESSAGE}">
	<param name="useinfo" value="{USE_INFO}">
	<param name="fingerreply" value="PJIRC for phpBB MOD user!">
	<param name="userinforeply" value="PJIRC for phpBB MOD user!">
        <param name="smileys" value="true">
	<param name="style:bitmapsmileys" value="true">
	{SMILIES}
	<param name="soundbeep" value="snd/{SOUND_BEEP}">
	<param name="soundquery" value="snd/{SOUND_QUERY}">
	<param name="pixx:timestamp" value="{TIME_STAMP}">
	<param name="pixx:showconnect" value="{SHOW_CONNECT}">
	<param name="pixx:showchanlist" value="{SHOW_CHANLIST}">
	<param name="pixx:showabout" value="{SHOW_ABOUT}">
	<param name="pixx:showhelp" value="{SHOW_HELP}">
	<param name="pixx:helppage" value="{HELPPAGE}">
        <param name="pixx:styleselector" value="{STYLE_SELECTOR}">
	<param name="pixx:setfontonstyle" value="{FONT_STYLE}">
	<param name="pixx:showclose" value="{SHOW_CLOSE}">
	<param name="pixx:showstatus" value="{SHOW_STATUS}">
	<param name="pixx:showdock" value="{SHOW_DOCK}">
	<param name="pixx:dockingconfig1" value="none+query all undock">
	<param name="pixx:highlight" value="{SHOW_HIGHLIGHT}">
	<param name="pixx:highlightnick" value="true">
	<param name="pixx:highlightcolor" value="{HIGHLIGHTCOLOR}">
        <param name="pixx:highlightwords" value="{HIGHLIGHTWORDS}">
	<param name="pixx:nickfield" value="{SHOW_NICKFIELD}">
	<param name="pixx:scrollspeed" value="{TOPICSCROLLER}">
	<param name="pixx:mouseurlopen" value="3 1">
	{NICK_STYLE}
        <param name="style:sourcefontrule1" value="{FONT_STYLE_DEFINITION}">
        <param name='style:sourcecolorrule1' value='{STYLE_SELECTOR_DEFINITION}'>
        {BACKGROUND}
        {CURRENTCHATTEMPLATE}
        <param name="pixx:configurepopup" value="true">
        <param name="pixx:popupmenustring1" value="View Profile">
        <param name="pixx:popupmenustring2" value="Instant Message">
        <param name="pixx:popupmenustring3" value="Ignore">
        <param name="pixx:popupmenustring4" value="Don't Ignore">
        <param name="pixx:popupmenustring5" value="Away Message">
        <param name="pixx:popupmenustring6" value="Back Message">
        <param name="pixx:popupmenustring7" value="Clear Window">
        <param name="pixx:popupmenustring8" value="Whois Status">
        {BOT_POPUP} <!-- is popmenustring9 -->
        <param name="pixx:popupmenucommand1_1" value="/play snd/{SOUND_PROFILE}">
        <param name="pixx:popupmenucommand1_2" value='/url "{SITEPATH}profile.php?mode=viewprofile&u=%4"'>
        <param name="pixx:popupmenucommand2_1" value="/play snd/{SOUND_IM}">
        <param name="pixx:popupmenucommand2_2" value="/query %1">
        <param name="pixx:popupmenucommand3_1" value="/play snd/{SOUND_IGNORE}">
        <param name="pixx:popupmenucommand3_2" value="/ignore %1">
        <param name="pixx:popupmenucommand4_1" value="/play snd/{SOUND_UNIGNORE}">
        <param name="pixx:popupmenucommand4_2" value="/unignore %1">
        <param name="pixx:popupmenucommand5_1" value="/play snd/{SOUND_AWAY}">
        <param name="pixx:popupmenucommand5_2" value="/awaymsg">
        <param name="pixx:popupmenucommand6_1" value="/play snd/{SOUND_BACK}">
        <param name="pixx:popupmenucommand6_2" value="/backmsg">
        <param name="pixx:popupmenucommand7_1" value="/play snd/{SOUND_CLEAR}">
        <param name="pixx:popupmenucommand7_2" value="/clear">
        <param name="pixx:popupmenucommand8_1" value="/play snd/{SOUND_WHOIS}">
        <param name="pixx:popupmenucommand8_2" value="/Whois %1">
        <param name="pixx:popupmenucommand9_1" value="/play snd/{SOUND_HELP}">
        <param name="pixx:popupmenucommand9_2" value="/msg $chan !$me_HELP">
        </applet>
    	<table bgcolor="{CURRENTCHATVB9}" cellpadding="2px" width="640px">
    	<tr>
    		<td align="center" valign="top">
        	<img src="{SITEPATH}mods/chatroom/player/nonanimated.gif" name="graphix"><br />
    		<img src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB8}" onClick="rewindmusic();var arrSplitPath = document.getElementById('music').src.split('/'); var name = arrSplitPath[arrSplitPath.length-1];var arrSplitExt = name.split('.'); document.getElementById('showtrack').value = '' + arrSplitExt[0];" name="rrbtn" style="cursor: hand" onMouseOver="music7()" onMouseOut="music8()">
        	<img src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB2}" name="plbtn" onClick="playmusic();var arrSplitPath = document.getElementById('music').src.split('/'); var name = arrSplitPath[arrSplitPath.length-1];var arrSplitExt = name.split('.'); document.getElementById('showtrack').value = '' + arrSplitExt[0];" style="cursor: hand" onMouseOver="music1()" onMouseOut="music2()">
        	<img src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB4}" onClick="stopmusic()" name="stbtn" style="cursor: hand" onMouseOver="music3()" onMouseOut="music4()">
        	<img src="{SITEPATH}mods/chatroom/player/{CURRENTCHATVB6}" onClick="forwardmusic();var arrSplitPath = document.getElementById('music').src.split('/'); var name = arrSplitPath[arrSplitPath.length-1];var arrSplitExt = name.split('.'); document.getElementById('showtrack').value = '' + arrSplitExt[0];" name="ffbtn" style="cursor: hand" onMouseOver="music5()" onMouseOut="music6()">
        	<bgsound src="#" id=music loop=1 autostart="true"><a class=movelink style="cursor:move;" onclick="InitializeMove()">
    		</td>
    		<td align="center" valign="top"><span class="gensmall">
    		<center>Now Playing:</center>
    		<input type="text" name="nowplaying" id="showtrack" size="12" style="color: {CURRENTCHATVB10}; background-color: {CURRENTCHATVB11};">
    		<center>Tracks: {PLAYERCOUNT}</center>
    		</span></td>
		    <td align="center" valign="middle">
		    {SMILIESBUTTONS}
		    </td>
	    </tr>
	    </table></td>
    	</tr>
    	</table></td>
</tr>
<tr>
	<td class="catBottom" align="center"><span class="gensmall"><b>Server:</b> {SERVER} <b>Port:</b> {PORT} <b>Channel:</b> {CHANNEL}</span></td>
</tr>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center"class="copyright"><a href="http://www.phpbb.com/phpBB/viewtopic.php?t=201400" target="_blank" class="copyright">PJIRC Chat</a> {PJIRC_MOD_VERSION} &copy; 2004, {COPYRIGHT_YEAR}</div>
