{BAN_MENU}
{USER_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BAN_TITLE}</h1>

<p>{L_BAN_EXPLAIN}</p>

<p>{L_BAN_EXPLAIN_WARN}</p>

<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" name="post" action="{S_BANLIST_ACTION}">
<tr> 
	<th class="thHead" colspan="2">{L_BAN_USER}</th>
</tr>
<tr> 
	<td class="row1" width="50%"><b>{L_USERNAME}:</b></td>
	<td class="row2"><input type="text" class="post" name="username"{AJAXED_USER_LIST} maxlength="50" size="20" /> <input type="hidden" name="mode" value="edit" />{S_HIDDEN_FIELDS} <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" />{AJAXED_USER_LIST_BOX}</td>
</tr>
<tr>
	<td class="row1"><b>{L_REASON}:</b></td>
	<td class="row2"><input type="text" class="post" name="reason" maxlength="75" size="35"></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_UNBAN_USER}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_USERNAME}:</b><br /><span class="gensmall">{L_UNBAN_USER_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_USERLIST_SELECT}</td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_BAN_IP}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_IP_OR_HOSTNAME}:</b><br /><span class="gensmall">{L_BAN_IP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_ip" size="35" /></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_UNBAN_IP}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_IP_OR_HOSTNAME}:</b><br /><span class="gensmall">{L_UNBAN_IP_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_IPLIST_SELECT}</td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_BAN_EMAIL}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_ADDRESS}:</b><br /><span class="gensmall">{L_BAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_email" size="35" /></td>
</tr>
<tr> 
	<th class="thHead" colspan="2">{L_UNBAN_EMAIL}</th>
</tr>
<tr> 
	<td class="row1"><b>{L_EMAIL_ADDRESS}:</b><br /><span class="gensmall">{L_UNBAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_EMAILLIST_SELECT}</td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>