<script language="JavaScript" type="text/javascript">
<!--
function emoticon(text) {
	var txtarea = document.post.message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}
//-->
</script>

<table width="100%" cellspacing="2" cellpadding="2"><form action="{S_PRIVMSGS_ACTION}" name="post" method="post">
  <tr>
	<td valign="middle" class="nav">{REPLY_PM_IMG}&nbsp;&nbsp;&nbsp;{BIG_QUOTE_IMG}</td>
	<td width="100%" class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
 	<td valign="middle" nowrap="nowrap"><table width="100%" cellspacing="0" cellpadding="0"> 
      	<tr> 
		<td><a href="{U_PRIVMSG_PREVIOUS}"><img src="{PREVIOUS_PM_IMG}" alt="{L_PRIVMSG_PREVIOUS}" title="{L_PRIVMSG_PREVIOUS}" /></a></td> 
                <td nowrap="nowrap">{SEARCH_PM_IMG}<a href="JavaScript:window.location.reload()"><img src="{REFRESH_PM_IMG}" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a></td> 
		<td><a href="{U_PRIVMSG_NEXT}"><img src="{NEXT_PM_IMG}" alt="{L_PRIVMSG_NEXT}" title="{L_PRIVMSG_NEXT}" /></a></td> 
	</tr> 
	</table></td> 
 </tr>
</table>

{CPL_MENU_OUTPUT}
	
	<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr> 
	  <th colspan="3" class="thHead" nowrap="nowrap">{BOX_NAME} :: {L_MESSAGE}</th>
	</tr>
	<tr> 
	  <td class="row2"><b class="genmed">{L_FROM}:</b></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{MESSAGE_FROM}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><b class="genmed">{L_TO}:</b></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{MESSAGE_TO}</span><span class="gensmall">{POSTER_TO_ONLINE_STATUS}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><b class="genmed">{L_POSTED}:</b></td>
	  <td width="100%" class="row2" colspan="2"><span class="genmed">{POST_DATE}</span></td>
	</tr>
	<tr> 
	  <td class="row2"><b class="genmed">{L_SUBJECT}:</b></td>
	  <td width="100%" class="row2"><span class="genmed">{POST_SUBJECT}</span></td>
	  <td nowrap="nowrap" class="row2" align="right"> {QUOTE_PM_IMG} {EDIT_PM_IMG}</td>
	</tr>
	<tr> 
	  <td valign="top" colspan="3" class="row1"><span class="postbody">{MESSAGE}</span>
<!-- BEGIN postrow -->
	  {ATTACHMENTS}
<!-- END postrow -->
	  </td>
	</tr>
	<tr> 
	  <td width="78%" height="28" valign="bottom" colspan="3" class="row1"> 
		<table cellspacing="0" cellpadding="0" height="18">
		  <tr> 
			<td valign="middle" nowrap="nowrap">{POSTER_FROM_ONLINE_STATUS_IMG} {PROFILE_IMG} {SEARCH_IMG} {EMAIL_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {XFI_IMG}</td><td>&nbsp;</td><td valign="top" nowrap="nowrap"><script language="JavaScript" type="text/javascript"><!-- 

		if ( navigator.userAgent.toLowerCase().indexOf('mozilla') != -1 && navigator.userAgent.indexOf('5.') == -1 && navigator.userAgent.indexOf('6.') == -1 )
			document.write('{ICQ_IMG}');
		else
			document.write('<div style="position:relative"><div style="position:absolute">{ICQ_IMG}</div><div style="position:absolute;left:3px">{ICQ_STATUS_IMG}</div></div>');
		  
		  //--></script><noscript>{ICQ_IMG}</noscript></td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="3" align="right"> {S_HIDDEN_FIELDS} 
		<input type="submit" name="save" value="{L_SAVE_MSG}" class="liteoption" />
		&nbsp; 
		<input type="submit" name="delete" value="{L_DELETE_MSG}" class="liteoption" />
<!-- BEGIN switch_attachments -->
		&nbsp; 
		<input type="submit" name="pm_delete_attach" value="{L_DELETE_ATTACHMENTS}" class="liteoption" />
<!-- END switch_attachments -->
	  </td>
	</tr>
</table>
<br />

<table cellpadding="4" cellspacing="1" class="forumline" width="100%" id="posting_body"><input type="hidden" name="reply" value="{REPLY}" /><input type="hidden" name="id" value="{REPLY_ID}" />
<tr> 
	<th colspan="2" class="thHead">{L_QUICK_PM_REPLY}</th>
</tr>
<tr>
	<td class="row1" width="22%"><b class="gen">{L_USERNAME}:</b></td>
	<td class="row2" width="78%"><input type="text" class="post" name="username" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" size="25" value="{MESSAGE_FROM_QUICK}" /></td>
</tr>
<tr>	
	<td class="row1"><b class="gen">{L_SUBJECT}:</b></td>
 	<td class="row2"><input type="text" name="subject" size="45" maxlength="120" style="width:300px" class="post" value="{POST_SUBJECT}" /></td>
</tr>
<tr>
	<td class="row1"><table align="center" cellspacing="0" cellpadding="5">
	<tr align="center"> 
		<td colspan="{S_SMILIES_COLSPAN}"><b class="gensmall">{L_EMOTICONS}</b></td>
	</tr>
	<!-- BEGIN smilies_row -->
	<tr align="center" valign="middle"> 
		<!-- BEGIN smilies_col -->
		<td><img src="{smilies_row.smilies_col.SMILEY_IMG}" onmouseover="this.style.cursor='hand';" onclick="emoticon('{smilies_row.smilies_col.SMILEY_CODE}');" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" /></td> 
		<!-- END smilies_col -->
	</tr>
	<!-- END smilies_row -->
	<!-- BEGIN switch_smilies_extra -->
	<tr> 
		<td align="center" colspan="{S_SMILIES_COLSPAN}"><a href="{U_MORE_SMILIES}" onclick="window.open('{U_MORE_SMILIES}', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=250');return false;" target="_phpbbsmilies" class="nav">{L_MORE_SMILIES}</a></td>
	</tr>
	<!-- END switch_smilies_extra -->
	</table></td>
	<td class="row1"><textarea name="message" rows="10" cols="35" wrap="virtual" style="width:100%" class="post">{L_REPLY_PM}</textarea></td>
</tr>
<tr> 
	<td colspan="2" align="center" class="catBottom">{S_HIDDEN_FIELDS}<input type="submit"{AJAXED_PREVIEW} name="preview" class="mainoption" value="{L_PREVIEW}" />&nbsp;&nbsp;<input type="submit" accesskey="s" name="post" class="mainoption" value="{L_SUBMIT}" /></td>
</tr>
</form></table>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td valign="middle" class="nav">{REPLY_PM_IMG}&nbsp;&nbsp;&nbsp;{BIG_QUOTE_IMG}</td>
	<td width="100%" class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
 	<td valign="middle" nowrap="nowrap"><table width="100%" cellspacing="0" cellpadding="0"> 
      	<tr> 
		<td><a href="{U_PRIVMSG_PREVIOUS}"><img src="{PREVIOUS_PM_IMG}" alt="{L_PRIVMSG_PREVIOUS}" title="{L_PRIVMSG_PREVIOUS}" /></a></td> 
                <td nowrap="nowrap">{SEARCH_PM_IMG}<a href="JavaScript:window.location.reload()"><img src="{REFRESH_PM_IMG}" alt="{L_REFRESH_PAGE}" title="{L_REFRESH_PAGE}" /></a></td> 
		<td><a href="{U_PRIVMSG_NEXT}"><img src="{NEXT_PM_IMG}" alt="{L_PRIVMSG_NEXT}" title="{L_PRIVMSG_NEXT}" /></a></td> 
	</tr> 
	</table></td> 
 </tr>
</table>

	</td>
</tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
