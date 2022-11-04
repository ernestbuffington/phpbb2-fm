<!-- BEGIN email_is_sent -->
<div align="center" class="gen"><b>{MSG_SENT}</b></div>
<!-- END email_is_sent -->

<!-- BEGIN prepare_email -->
<script language="JavaScript" type="text/javascript">
<!--
function checkForm(formObj) {

	formErrors = false;    

	if (formObj.message.value.length < 2) {
		formErrors = "{L_EMPTY_MESSAGE_EMAIL}";
	}
	else if ( formObj.subject.value.length < 2)
	{
		formErrors = "{L_EMPTY_SUBJECT_EMAIL}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	}
}
//-->
</script>

{ERROR_BOX}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post" onSubmit="return checkForm(this)">
<tr> 
	<th colspan="2" class="thHead">{L_SEND_EMAIL}</th>
</tr>
<tr> 
	<td class="row2" colspan="2" align="center"><span class="gen">{MSG_HEADER}</span></td>
</tr>
<tr> 
	<td class="row1" width="22%"><b class="gen">{L_SUBJECT}:</b></td>
	<td class="row2" width="78%"><input type="text" name="subject" size="35" maxlength="100" style="width:400px" tabindex="0" class="post" value="{SUBJECT}" /></td>
</tr>
<tr> 
	<td class="row1" valign="top"><b class="gen">{L_MESSAGE_BODY}:</b><br /><span class="gensmall">{L_MESSAGE_BODY_DESC}</span></td>
	<td class="row2"><textarea name="message" rows="25" cols="35" wrap="virtual" style="width:400px" tabindex="1" class="post">{MESSAGE}</textarea></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" tabindex="2" name="submit" class="mainoption" value="{L_SEND_EMAIL}" /></td>
</tr>
</form></table>
<!-- END prepare_email -->
<br />
<div align="center"><b><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></b>
<br /><br />

<div class="copyright">Cricca Guestbook {VERSION} &copy 2004, {COPYRIGHT_YEAR} -Nessuno-</div>