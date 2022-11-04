<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.privmsg_list.length; i++)
	{
		document.privmsg_list.elements[i].checked = status;
	}
}
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" name="privmsg_list" action="{S_PRIVMSGS_ACTION}">
<tr>
	<td align="right" colspan="3" class="nav">{INBOX} :: {SENTBOX} :: {OUTBOX} :: {SAVEBOX} :: {EXPORT} 
	<!-- BEGIN switch_mass_pm -->
	:: <a href="{switch_mass_pm.MASS_PM_URL}" class="nav" title="{switch_mass_pm.L_MASS_PM}">{switch_mass_pm.L_MASS_PM}</a>
	<!-- END switch_mass_pm -->
	</td>
</tr>
<tr> 
	<td valign="middle">{POST_PM_IMG}</td>
	<td width="100%" class="nav">&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right" nowrap="nowrap">{L_DISPLAY_MESSAGES}: <select name="msgdays">{S_SELECT_MSG_DAYS}</select> <input type="submit" value="{L_GO}" name="submit_msgdays" class="liteoption" /></span></td>
</tr>
</table>

{CPL_MENU_OUTPUT}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
<tr> 
	<th width="5%" class="thCornerL" nowrap="nowrap">&nbsp;{L_FLAG}&nbsp;</th>
	<th width="60%" class="thTop" nowrap="nowrap">&nbsp;{L_SUBJECT}&nbsp;</th>
	<th width="15%" class="thTop" nowrap="nowrap">&nbsp;{L_FROM_OR_TO}&nbsp;</th>
	<th width="15%" class="thTop" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
	<th width="5%" class="thCornerR" nowrap="nowrap">&nbsp;{L_MARK}&nbsp;</th>
</tr>
<!-- BEGIN listrow -->
<tr> 
	<td align="center" valign="middle" class="{listrow.ROW_CLASS}"><a href="{listrow.U_READ}"><img src="{listrow.PRIVMSG_FOLDER_IMG}" alt="{listrow.L_PRIVMSG_FOLDER_ALT}" title="{listrow.L_PRIVMSG_FOLDER_ALT}" /></a></td>
	<td valign="middle" class="{listrow.ROW_CLASS}">{listrow.PRIVMSG_ATTACHMENTS_IMG}<span class="topictitle">&nbsp;<a href="{listrow.U_READ}" class="topictitle">{listrow.SUBJECT}</a></span></td>
	<td align="center" valign="middle" class="{listrow.ROW_CLASS}"><span class="name">&nbsp;<a href="{listrow.U_FROM_USER_PROFILE}" class="genmed">{listrow.FROM}</a></span></td>
	<td align="center" valign="middle" class="{listrow.ROW_CLASS}" nowrap="nowrap"><span class="postdetails">{listrow.DATE}</span></td>
	<td align="center" valign="middle" class="{listrow.ROW_CLASS}"><input type="checkbox" name="mark[]2" value="{listrow.S_MARK_ID}" /></td>
</tr>
<!-- END listrow -->
<!-- BEGIN switch_no_messages -->
<tr> 
	<td class="row1" colspan="5" align="center" height="30"><span class="gen">{L_NO_MESSAGES}</span></td>
</tr>
<!-- END switch_no_messages -->
<tr> 
	<td class="catBottom" colspan="5" align="right">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SAVE_MARKED}" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="deleteall" value="{L_DELETE_ALL}" class="liteoption" /></td>
</tr>
</table>

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr> 
	<td valign="middle" class="nav">{POST_PM_IMG}</td>
	<td align="left" width="100%" class="nav">&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<td align="right" nowrap="nowrap"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></b><br /><span class="nav">{PAGE_NUMBER}<br />{PAGINATION}</span></td>
</tr>
</form></table>
<br />

<table width="100%" cellspacing="2" align="center" cellpadding="2">
<tr>
	<td align="right" width="100%">
	<!-- BEGIN switch_box_size_notice -->
	<table width="189" cellspacing="1" cellpadding="4" class="forumline">
	<tr> 
		<td colspan="3" width="189" class="row1" nowrap="nowrap"><span class="gensmall">{BOX_SIZE_STATUS}</span></td>
	</tr>
	<tr> 
		<td colspan="3" width="189" class="row2">
		<table cellspacing="0" cellpadding="1">
		<tr> 
			<td><img src="templates/{T_THEME}/images/vote_lcap.gif" width="4" height="12" alt="{INBOX_LIMIT_PERCENT}%" title="{INBOX_LIMIT_PERCENT}%" /><img src="templates/{T_THEME}/images/voting_bar.gif" width="{INBOX_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}%" title="{INBOX_LIMIT_PERCENT}%" /><img src="templates/{T_THEME}/images/vote_rcap.gif" width="4" height="12" alt="{INBOX_LIMIT_PERCENT}%" title="{INBOX_LIMIT_PERCENT}%" /></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr> 
		<td width="33%" class="row1"><span class="gensmall">0%</span></td>
		<td width="34%" align="center" class="row1"><span class="gensmall">50%</span></td>
		<td width="33%" align="right" class="row1"><span class="gensmall">100%</span></td>
	</tr>
	</table>
	<!-- END switch_box_size_notice -->
	</td>
	<td>
	<!-- BEGIN switch_box_size_notice -->
	<table width="189" cellspacing="1" cellpadding="4" class="forumline">
	<tr> 
		<td colspan="3" width="189" class="row1" nowrap="nowrap"><span class="gensmall">{ATTACH_BOX_SIZE_STATUS}</span></td>
	</tr>
	<tr> 
		<td colspan="3" width="189" class="row2">
		<table cellspacing="0" cellpadding="1">
		<tr> 
			<td><img src="templates/{T_THEME}/images/vote_lcap.gif" width="4" height="12" alt="{ATTACHBOX_LIMIT_PERCENT}%" title="{ATTACHBOX_LIMIT_PERCENT}%" /><img src="templates/{T_THEME}/images/voting_bar.gif" width="{ATTACHBOX_LIMIT_IMG_WIDTH}" height="12" alt="{ATTACHBOX_LIMIT_PERCENT}%" title="{ATTACHBOX_LIMIT_PERCENT}%" /><img src="templates/{T_THEME}/images/vote_rcap.gif" width="4" height="12" alt="{ATTACHBOX_LIMIT_PERCENT}%" title="{ATTACHBOX_LIMIT_PERCENTT}%" /></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr> 
		<td width="33%" class="row1"><span class="gensmall">0%</span></td>
		<td width="34%" align="center" class="row1"><span class="gensmall">50%</span></td>
		<td width="33%" align="right" class="row1"><span class="gensmall">100%</span></td>
	</tr>
	</table>
	<!-- END switch_box_size_notice -->
	</td>
</tr>
</table>
	</td>
</tr>
</table>
<table width="100%" cellspacing="2" align="center" cellpadding="2">
  <tr> 
	<td align="right" valign="top">{JUMPBOX}</td>
  </tr>
</table>
