{USERCOM_MENU}{PM_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<table width="100%" align="center"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<td align="right">{CONFIG_SELECT} <input type="submit" name="config" value="{L_GO}" class="liteoption" /></td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_PAGE_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_DISABLE_PRIVATE_MESSAGING}:</b><br /><span class="gensmall">{L_DISABLE_PRIVATE_MESSAGING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="privmsg_disable" value="0" {S_PRIVMSG_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="privmsg_disable" value="1" {S_PRIVMSG_DISABLED} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_NEWUSER_PRIVATE_MESSAGING}:</b><br /><span class="gensmall">{L_DISABLE_NEWUSER_PRIVATE_MESSAGING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="privmsg_newuser_disable" value="1" {S_PRIVMSG_NEWUSER_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="privmsg_newuser_disable" value="0" {S_PRIVMSG_NEWUSER_DISABLED} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_DISABLE_WPM}:</b><br /><span class="gensmall">{L_DISABLE_WPM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="wpm_disable" value="0" {S_WPM_ENABLED} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="wpm_disable" value="1" {S_WPM_DISABLED} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><b>{L_ALLOW_SWEARYWORDS}:</b><br /><span class="gensmall">{L_ALLOW_SWEARYWORDS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_swearywords" value="1" {SWEARYWORDS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_swearywords" value="0" {SWEARYWORDS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_PRIVMSG_SELF}:</b></td>
	<td class="row2"><input type="radio" name="privmsg_self" value="1" {S_PRIVMSG_SELF_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="privmsg_self" value="0" {S_PRIVMSG_SELF_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_INBOX_LIMIT}:</b><br /><span class="gensmall">{L_INBOX_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_inbox_privmsgs" value="{INBOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SENTBOX_LIMIT}:</b><br /><span class="gensmall">{L_SENTBOX_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_sentbox_privmsgs" value="{SENTBOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_SAVEBOX_LIMIT}:</b><br /><span class="gensmall">{L_SAVEBOX_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_savebox_privmsgs" value="{SAVEBOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<table width="95%" align="center" cellspacing="2" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{ACP_JUMPBOX}</td> 
  </tr> 
</table>