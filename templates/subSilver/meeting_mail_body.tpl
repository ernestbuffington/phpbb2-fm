<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_MEETING}" class="nav">{L_MEETING}</a> -> <a href="{U_MEETING_DETAIL}" class="nav">{L_MEETING_DETAIL}</a></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION}" method="post" name="meeting_send_email">
<tr>
	<th class="thhead" colspan="2">{MEETING_SUBJECT} :: {L_MEETING_MAIL}</th>
</tr>
<tr>
	<td class="row1" align="right" width="25%" valign="top"><b>{L_MEETING_MAIL_TO}:</b></td>
	<td class="row2" width="75%"><input type="radio" name="mail_to" value="1" checked="checked" /> {L_MEETING_MAIL_SIGN_YES}&nbsp;&nbsp;<input type="radio" name="mail_to" value="2" /> {L_MEETING_MAIL_SIGN_NO}&nbsp;&nbsp;<input type="radio" name="mail_to" value="0" /> {L_MEETING_MAIL_ALL}</td>
</tr>
<tr>
	<td class="row1" align="right" valign="top"><span class="genmed"><b>{L_MEETING_MAIL_SUBJECT}:</b></span></td>
	<td class="row2"><input type="text" size="75" maxlength="100" value="" class="post" name="meeting_mail_subject" /></td>
</tr>
<tr>
	<td class="row1" align="right" valign="top"><span class="genmed"><b>{L_MEETING_MAIL_TEXT}:</b></span></td>
	<td class="row2"><textarea cols="100" rows="10" class="post" name="meeting_mail_text" /></textarea></td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="cancel" value="{L_CANCEL}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>
