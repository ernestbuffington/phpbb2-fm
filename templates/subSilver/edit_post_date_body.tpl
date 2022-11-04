<body onUnload="window.opener.location.reload()">
<table width="100%" cellspacing="0" cellpadding="10" align="center"> 
<tr> 
	<td>{EDIT_POST_DATE_ICON}</td>
	<td align="center" width="100%" valign="middle"><span class="maintitle">{L_EDIT_POST_DATE_TITLE}</span><br /><span class="gen">{L_EDIT_POST_DATE_EXPLAIN}<br />&nbsp;</span></td> 
</tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" nowrap="nowrap">&nbsp;{L_EDIT_POST_ID}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_CURRENT_POST_DATE}&nbsp;</th> 
</tr> 
<tr> 
	<td class="row1" align="center"><span class="gen">{POST_ID}</span></td>
	<td class="row1" align="center"><span class="gen">{CURRENT_POST_DATE}</span></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2">&nbsp;</td>
</tr>
</table>
<br />

<table width="80%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="vote_list" action="{S_MODE_ACTION}">
<tr>
	<th class="thHead">{L_CHANGE_POST_DATE}</th>
</tr> 
<tr> 
	<td class="row1" align="center" nowrap="nowrap"><span class="genmed">{S_MONTH_SELECT} {S_DAY_SELECT}<b>, </b>{S_YEAR_SELECT}&nbsp;&nbsp;&nbsp;{S_HOUR_SELECT}<b>:</b>{S_MINUTE_SELECT}&nbsp;{S_AMPM_SELECT}</span></td>
</tr>
<tr> 
	<td class="row2" align="center" nowrap="nowrap"><span class="cattitle">{EDIT_POST_VALID_DATE}</span></td>
</tr>
<tr>
	<td class="catBottom" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" onclick="this.onclick = new Function('return false');" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a><br /><span class="copyright">Edit Post Date 1.0.2  &copy 2002, 2006 <a href="mailto:ErDrRon@aol.com" class="copyright">ErDrRon</a></span></div>
