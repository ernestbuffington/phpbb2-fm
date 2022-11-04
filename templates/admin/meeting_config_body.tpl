{MEETING_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_CONFIGURATION_TITLE}</h1> 

<p>{L_CONFIGURATION_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" align="center" class="forumline"><form action="{S_CONFIG_ACTION}" method="post">
<tr>
	<th class="thHead" colspan="2">{L_CONFIGURATION_TITLE}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_USER_ALLOW_ENTER_MEETING}:</b><br /><span class="gensmall">{L_USER_ALLOW_ENTER_MEETING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_user_enter_meeting" value="1" {USER_ALLOW_ENTER_MEETING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_user_enter_meeting" value="0" {USER_ALLOW_ENTER_MEETING_NO} /> {L_NO}&nbsp;&nbsp;<input type="radio" name="allow_user_enter_meeting" value="2" {USER_ALLOW_ENTER_MEETING_GROUP} /> {L_GROUPS}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_ALLOW_EDIT_MEETING}:</b><br /><span class="gensmall">{L_USER_ALLOW_EDIT_MEETING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_user_edit_meeting" value="1" {USER_ALLOW_EDIT_MEETING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_user_edit_meeting" value="0" {USER_ALLOW_EDIT_MEETING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_ALLOW_DELETE_MEETING}:</b><br /><span class="gensmall">{L_USER_ALLOW_DELETE_MEETING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_user_delete_meeting" value="1" {USER_ALLOW_DELETE_MEETING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_user_delete_meeting" value="0" {USER_ALLOW_DELETE_MEETING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_USER_ALLOW_DELETE_MEETING_COMMENTS}:</b><br /><span class="gensmall">{L_USER_ALLOW_DELETE_MEETING_COMMENTS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_user_delete_meeting_comments" value="1" {USER_ALLOW_DELETE_MEETING_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_user_delete_meeting_comments" value="0" {USER_ALLOW_DELETE_MEETING_COMMENTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_NOTIFY}:</b><br /><span class="gensmall">{L_MEETING_NOTIFY_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="meeting_notify" value="1" {MEETING_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="meeting_notify" value="0" {MEETING_NOTIFY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="hidden" name="config" value="1" /><input type="submit" name="submit_config" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<tr>
	<td class="row2" colspan="2" align="center">{S_USERGROUPS}</td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>
