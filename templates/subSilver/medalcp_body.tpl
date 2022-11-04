<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_MEDALS}" class="nav">{L_MEDALS}</a></td>
</tr>
</table>
<table class="forumline" width="100%" cellspacing="1" cellpadding="4"><form method="post" name="post" action="{S_MEDALCP_ACTION}">
<tr> 
	<td class="catHead" colspan="2" align="center"><span class="cattitle">{L_MEDAL_CP}</span></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_MEDAL_INFORMATION}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_MEDAL_NAME}:</b></td>
	<td class="row2"><b class="gen">{MEDAL_NAME}</b></td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_DESC}:</b></td>
	<td class="row2">{MEDAL_DESC}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_IMAGE}:</b></td>
	<td class="row2">{MEDAL_IMAGE_DISPLAY}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_MODERATOR}:</b></td>
	<td class="row2">{MEDAL_MODERATOR}</td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_MEMBERS}:</b><br /><span class="gensmall">{L_MEDAL_MEMBERS_EXPLAIN}</span></td>
	<td class="row2">{MEDAL_MEMBER}</td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_MEDAL_USER}</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input class="post" type="text" class="post" name="username" maxlength="50" size="20" /> <input type="hidden" name="mode" value="edit" />{S_HIDDEN_FIELDS} <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
</tr>
<tr> 
	<td class="row1"><b>{L_MEDAL_REASON}:</b><br /><span class="gensmall">{L_MEDAL_REASON_EXPLAIN}</span></td>
	<td class="row2"><textarea class="post" name="issue_reason" rows="6" cols="35"></textarea></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_UNMEDAL_USER}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_USERNAME}:</b><br /><span class="gensmall">{L_UNMEDAL_USER_EXPLAIN}</span></td>
	<td class="row2">{S_UNMEDAL_USERLIST_SELECT}</td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>
