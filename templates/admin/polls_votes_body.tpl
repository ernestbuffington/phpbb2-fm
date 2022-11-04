{VOTE_MENU}{POST_MENU}{ATTACH_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!-- 
function __off(n) 
{ 
	if(n && n.style) 
	{ 
		if('none' != n.style.display) 
		{ 
			n.style.display = 'none'; 
		} 
	} 
} 

function __on(n) 
{ 
	if(n && n.style) 
	{ 
		if('none' == n.style.display) 
		{ 
			n.style.display = ''; 
		} 
	} 
} 

function __toggle(n) 
{ 
	if(n && n.style) 
	{ 
		if('none' == n.style.display) 
		{ 
			n.style.display = ''; 
		} 
		else 
		{ 
			n.style.display = 'none'; 
		} 
	} 
} 

function onoff(objName,bObjState) 
{ 
	var sVar = ''+objName; 
	var sOn = ''+objName+'_on'; 
	var sOff = ''+objName+'_off'; 
	var sOnStyle = bObjState ? ' style="display:none;" ':''; 
	var sOffStyle = !bObjState ? ' style="display:none;" ':''; 
	var sSymStyle = ' style="text-align: center;width: 13;height: 13;font-family: Arial,Verdana;font-size: 7pt;border-style: solid;border-width: 1;cursor: hand;color: #003344;background-color: #CACACA;" '; 

	if( (navigator.userAgent.indexOf("MSIE") >= 0) && document && document.body && document.body.style) 
	{ 
		document.write( '<span '+sOnStyle+'onclick="__on('+sVar+');__off('+sOn+');__on('+sOff+');" id="'+sOn+'" title="Click here to show details"'+sSymStyle+'>+<\/span>' + 
		'<span '+sOffStyle+'onclick="__off('+sVar+');__off('+sOff+');__on('+sOn+');" id="'+sOff+'" title="Click here to hide details"'+sSymStyle+'>-<\/span>' ); 
	} 
} 
// --> 
</script>

<h1>{L_ADMIN_VOTE_TITLE}</h1>

<p>{L_ADMIN_VOTE_EXPLAIN}</p>
 
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_MODE_ACTION}" method="post">
<tr> 
	<td align="right"><span class="genmed">{L_SELECT_SORT_METHOD} {S_FIELD_SELECT} {L_ORDER} {S_ORDER_SELECT} <input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr> 
	<th width="5%" class="thCornerL">&nbsp;{L_VOTE_ID}&nbsp;</th> 
	<th class="thTop">{L_POLL_TOPIC}</th> 
	<th class="thTop">{L_VOTE_USERNAME}</th> 
	<th class="thCornerR">{L_VOTE_END_DATE}</th> 
</tr> 
<!-- BEGIN votes --> 
<tr>
	<td class="{votes.COLOR}" align="center">{votes.VOTE_ID}</td> 
	<td class="{votes.COLOR}">
	<script language="JavaScript" type="text/javascript"> 
	<!-- 
		onoff('vote{votes.VOTE_ID}_switch',false); 
	//--> 
	</script>
	<a href="{votes.LINK}" class="topictitle">{votes.DESCRIPTION}</a></td> 
	<td class="{votes.COLOR}"><span class="gensmall">{votes.USER}</span></td> 
	<td class="{votes.COLOR}" align="center" width="120"><span class="gensmall">{votes.VOTE_DURATION}</span></td> 
</tr> 
<tr id="vote{votes.VOTE_ID}_switch" style="display:none;"> 
	<td class="row2" colspan="4"><table cellpadding="5" cellspacing="1"> 
	<!-- BEGIN detail --> 
	<tr> 
		<td class="row1">{votes.detail.OPTION} ({votes.detail.RESULT})</td> 
		<td class="row3"><span class="gensmall">{votes.detail.USER}</span></td> 
	</tr> 
	<!-- END detail --> 
	</table></td> 
</tr> 
<!-- END votes --> 
</table>
<table width="100%" align="center" cellspacing="2" cellpadding="2">
  <tr> 
	<td class="nav">{PAGE_NUMBER}</td>
	<td align="right" class="nav">{PAGINATION}</td>
  </tr>
</table>
